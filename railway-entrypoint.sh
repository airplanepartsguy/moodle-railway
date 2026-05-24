#!/usr/bin/env bash
set -euo pipefail

# Ensure only one MPM module is enabled (prefork)
a2dismod mpm_event mpm_worker >/dev/null 2>&1 || true
a2enmod mpm_prefork >/dev/null 2>&1 || true

# Remove any leftover symlinks that could cause conflicts
rm -f /etc/apache2/mods-enabled/mpm_event.* /etc/apache2/mods-enabled/mpm_worker.* || true
ln -sf /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load || true
ln -sf /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf || true

# Fix permissions for the Railway Volume (moodledata)
mkdir -p /var/www/moodledata
chown -R www-data:www-data /var/www/moodledata
chmod -R 0775 /var/www/moodledata

# Persist config.php to the Railway volume.
# /var/www/html/ is part of the container image, so config.php is wiped on every
# redeploy. We can't symlink it (PHP's __DIR__ resolves through realpath, so
# install.php would bake in the moodledata path and require_once would look
# in the wrong place). Instead: copy volume -> html on boot, and run a tiny
# background loop that mirrors html -> volume after the install wizard writes.
HTML_CONFIG=/var/www/html/config.php
VOL_CONFIG=/var/www/moodledata/config.php

# Drop any stale symlink left from earlier versions of this entrypoint.
if [ -L "$HTML_CONFIG" ]; then
  rm -f "$HTML_CONFIG"
fi

if [ -f "$VOL_CONFIG" ]; then
  # If a prior boot wrote config.php via the symlink, __DIR__ resolved to the
  # volume path and require_once now points at /var/www/moodledata/lib/setup.php
  # which doesn't exist. Patch it in place so Moodle can boot.
  if grep -qF "__DIR__" "$VOL_CONFIG"; then
    sed -i "s|__DIR__|'/var/www/html'|g" "$VOL_CONFIG"
    echo "[railway-entrypoint] patched __DIR__ -> '/var/www/html' in saved config.php"
  fi
  cp "$VOL_CONFIG" "$HTML_CONFIG"
  chown www-data:www-data "$HTML_CONFIG"
  echo "[railway-entrypoint] restored config.php from volume"
else
  echo "[railway-entrypoint] no saved config.php on volume; install wizard will create it"
fi

# Background sync: mirror /var/www/html/config.php back to the volume whenever
# it changes (i.e. after the install wizard writes it, or after admin edits).
# Cheap, runs every 30s, only copies on content difference. Without this the
# install wizard's freshly-written config.php would be lost on the next redeploy.
(
  while true; do
    sleep 30
    if [ -f "$HTML_CONFIG" ] && [ ! -L "$HTML_CONFIG" ]; then
      if [ ! -f "$VOL_CONFIG" ] || ! cmp -s "$HTML_CONFIG" "$VOL_CONFIG"; then
        cp "$HTML_CONFIG" "$VOL_CONFIG"
        chown www-data:www-data "$VOL_CONFIG" 2>/dev/null || true
        echo "[railway-entrypoint] synced config.php -> volume"
      fi
    fi
  done
) &

# Moodle config / muc / localcache purge.
# Moodle caches $CFG to disk in moodledata/muc; direct SQL writes to mdl_config
# don't invalidate it. Bump PURGE_VERSION (or set MOODLE_PURGE_CACHE=1) to force
# a one-shot wipe on next boot. After the marker is dropped, subsequent boots
# leave the cache alone (so we don't slow every restart).
PURGE_VERSION="1"
PURGE_MARKER="/var/www/moodledata/.railway-purged-v${PURGE_VERSION}"
if [ ! -f "$PURGE_MARKER" ] || [ "${MOODLE_PURGE_CACHE:-0}" = "1" ]; then
  echo "[railway-entrypoint] purging Moodle muc/cache/localcache (v${PURGE_VERSION})"
  rm -rf /var/www/moodledata/muc/* 2>/dev/null || true
  rm -rf /var/www/moodledata/cache/* 2>/dev/null || true
  rm -rf /var/www/moodledata/localcache/* 2>/dev/null || true
  touch "$PURGE_MARKER" 2>/dev/null || true
  chown www-data:www-data "$PURGE_MARKER" 2>/dev/null || true
else
  echo "[railway-entrypoint] cache purge already done (v${PURGE_VERSION}); skipping"
fi

# Log which MPM module is loaded
apache2ctl -M 2>/dev/null | grep mpm || true

# Railway reverse-proxy: treat request as HTTPS when the proxy indicates it
echo "SetEnvIf X-Forwarded-Proto https HTTPS=on" > /etc/apache2/conf-available/railway-proxy.conf
a2enconf railway-proxy >/dev/null 2>&1 || true

# Railway reverse-proxy: patch Moodle config.php so it trusts the proxy headers.
# Without this, the user's apparent IP rotates each request (breaking sessions
# and audit logs) and generated URLs end up http:// instead of https://.
#
# Defaults are tuned for Railway specifically:
#   sslproxy = true            Moodle treats the request as HTTPS even though
#                              Apache itself is plain HTTP behind Railway's TLS
#                              terminator.
#   getremoteaddrconf = 1      Moodle reads the client IP from X-Forwarded-For
#                              (which Railway sets) and ignores HTTP_CLIENT_IP.
#                              Works independently of $CFG->reverseproxy.
#   reverseproxy = false       *Disabled* for Railway. Moodle's reverseproxy
#                              flag expects the proxy to REWRITE the Host header
#                              to an internal hostname (see lib/setuplib.php
#                              comment around 'reverseproxyabused'). Railway
#                              passes the public Host through unchanged, so
#                              enabling reverseproxy fires reverseproxyabused
#                              on every request and the site goes down.
#
# Env-var overrides (set to 1/0 on the Moodle service in Railway):
#   MOODLE_REVERSEPROXY              default 0 (only enable behind a
#                                    Host-rewriting proxy, NOT Railway's edge)
#   MOODLE_SSLPROXY                  default 1
#   MOODLE_SKIP_HTTP_CLIENT_IP       default 1
CONFIG_PHP=/var/www/html/config.php
PROXY_MARKER_PREFIX="// BEGIN railway-entrypoint proxy block"
if [ -f "$CONFIG_PHP" ]; then
  # Always strip any pre-existing block before re-injecting. This lets us
  # change what we put in the block (e.g. switching reverseproxy default to 0
  # to fix reverseproxyabused) without needing manual config.php edits.
  if grep -qF "$PROXY_MARKER_PREFIX" "$CONFIG_PHP"; then
    sed -i '/\/\/ BEGIN railway-entrypoint proxy block/,/\/\/ END railway-entrypoint proxy block/d' "$CONFIG_PHP"
    echo "[railway-entrypoint] removed existing proxy block; will re-inject with current settings"
  fi
  reverseproxy="${MOODLE_REVERSEPROXY:-0}"
  sslproxy="${MOODLE_SSLPROXY:-1}"
  skipclientip="${MOODLE_SKIP_HTTP_CLIENT_IP:-1}"
  tmp="$(mktemp)"
  awk -v rp="$reverseproxy" -v sp="$sslproxy" -v sc="$skipclientip" '
    function emit_block() {
      print "// BEGIN railway-entrypoint proxy block (managed by railway-entrypoint.sh)"
      if (rp == "1") print "$CFG->reverseproxy = true;"
      if (sp == "1") print "$CFG->sslproxy = true;"
      if (sc == "1") print "$CFG->getremoteaddrconf = 1; // GETREMOTEADDR_SKIP_HTTP_CLIENT_IP (constant not defined until lib/setup.php loads)"
      print "// END railway-entrypoint proxy block"
    }
    !done && /require_once.*setup\.php/ {
      emit_block()
      print ""
      done = 1
    }
    { print }
    END {
      if (!done) {
        print ""
        emit_block()
      }
    }
  ' "$CONFIG_PHP" > "$tmp" && cat "$tmp" > "$CONFIG_PHP" && rm -f "$tmp"
  chown www-data:www-data "$CONFIG_PHP"
  echo "[railway-entrypoint] injected proxy block into config.php (reverseproxy=$reverseproxy sslproxy=$sslproxy skip_client_ip=$skipclientip)"
else
  echo "[railway-entrypoint] config.php not found yet; run the web installer, then redeploy to inject the proxy block"
fi

# Run plugin DB upgrades and the TurbineWorks University bootstrap.
# Only safe to run once Moodle is installed (config.php exists AND DB is
# reachable AND tables are in place). The CLI scripts handle their own
# detection — we just call them and treat failures as non-fatal so a
# half-installed site doesn't get stuck in a crash loop.
#
# Run as www-data so any files created (e.g. cache, marker) get the right
# ownership and Apache can read them on the next request.
if [ -f "$CONFIG_PHP" ]; then
  echo "[railway-entrypoint] running plugin DB upgrade (non-interactive)"
  runuser -u www-data -- php /var/www/html/admin/cli/upgrade.php --non-interactive 2>&1 \
    | sed 's/^/[upgrade] /' \
    | tail -30 || echo "[railway-entrypoint] upgrade.php exited non-zero (non-fatal)"

  echo "[railway-entrypoint] running TurbineWorks University bootstrap"
  runuser -u www-data -- php /var/www/html/local/twu/cli/bootstrap.php 2>&1 \
    | sed 's/^/[twu] /' || echo "[railway-entrypoint] twu bootstrap exited non-zero (non-fatal)"
else
  echo "[railway-entrypoint] config.php absent; skipping plugin upgrade + twu bootstrap (run web installer first)"
fi

# Start the original entrypoint + Apache
exec /usr/local/bin/moodle-docker-php-entrypoint apache2-foreground
