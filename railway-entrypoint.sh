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
# Without this, $CFG->wwwroot mismatches, installhijacked fires because the
# user's apparent IP rotates each request, and admin logins flap.
# Runs idempotently every boot; only applies once config.php exists
# (i.e. after the web installer has written it to the volume).
# Env-var overrides (defaults are Railway-correct, leave unset to accept):
#   MOODLE_REVERSEPROXY=1           -> $CFG->reverseproxy = true
#   MOODLE_SSLPROXY=1               -> $CFG->sslproxy = true
#   MOODLE_SKIP_HTTP_CLIENT_IP=1    -> $CFG->getremoteaddrconf = GETREMOTEADDR_SKIP_HTTP_CLIENT_IP
CONFIG_PHP=/var/www/html/config.php
PROXY_MARKER_PREFIX="// BEGIN railway-entrypoint proxy block"
if [ -f "$CONFIG_PHP" ]; then
  if grep -qF "$PROXY_MARKER_PREFIX" "$CONFIG_PHP"; then
    echo "[railway-entrypoint] proxy block already present in config.php; skipping"
  else
    reverseproxy="${MOODLE_REVERSEPROXY:-1}"
    sslproxy="${MOODLE_SSLPROXY:-1}"
    skipclientip="${MOODLE_SKIP_HTTP_CLIENT_IP:-1}"
    tmp="$(mktemp)"
    awk -v rp="$reverseproxy" -v sp="$sslproxy" -v sc="$skipclientip" '
      function emit_block() {
        print "// BEGIN railway-entrypoint proxy block (managed by railway-entrypoint.sh)"
        if (rp == "1") print "$CFG->reverseproxy = true;"
        if (sp == "1") print "$CFG->sslproxy = true;"
        if (sc == "1") print "$CFG->getremoteaddrconf = GETREMOTEADDR_SKIP_HTTP_CLIENT_IP;"
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
    ' "$CONFIG_PHP" > "$tmp" && mv "$tmp" "$CONFIG_PHP"
    chown www-data:www-data "$CONFIG_PHP"
    echo "[railway-entrypoint] injected proxy block into config.php (reverseproxy=$reverseproxy sslproxy=$sslproxy skip_client_ip=$skipclientip)"
  fi
else
  echo "[railway-entrypoint] config.php not found yet; run the web installer, then redeploy to inject the proxy block"
fi

# Start the original entrypoint + Apache
exec /usr/local/bin/moodle-docker-php-entrypoint apache2-foreground
