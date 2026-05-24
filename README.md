# Moodle on Railway — TurbineWorks University fork

This is a customized fork of [JesseZweers/moodle-railway](https://github.com/JesseZweers/moodle-railway), adapted to power **TurbineWorks University** — the LMS at [learn.turbineworks.com](https://learn.turbineworks.com) used for ASA-100 compliance training (initial + recurring) for TurbineWorks Aircraft Engine Parts & Components.

Upstream gives you a Moodle sandbox. This fork hardens it enough to actually run a real training program on Railway without nightly footguns. See [What's different in this fork](#whats-different-in-this-fork) below.

> The upstream template is explicitly "not for production." This fork closes the gaps that made that true on Railway specifically. It still isn't a multi-region, HA, fully-hardened Moodle install — but for a single-tenant compliance-training site behind Railway's edge, it's solid.

---

## What's different in this fork

All changes live in `railway-entrypoint.sh` (no Moodle code modifications):

| Fix | Why it matters |
|-----|----------------|
| **`config.php` persists to the Railway volume** (copy on boot + 30s background sync) | Upstream wipes `config.php` on every redeploy because `/var/www/html/` is not on a volume. You'd re-run the install wizard every deploy. Now the config survives. |
| **`$CFG->reverseproxy = false`** (Railway-correct default) | Moodle's `reverseproxy = true` expects the proxy to *rewrite* the Host header. Railway (and most modern proxies) passes the public Host through unchanged → `reverseproxyabused` error on every request → site down. Disabled by default; set `MOODLE_REVERSEPROXY=1` only if you front Moodle with a Host-rewriting proxy. |
| **`$CFG->sslproxy = true`** | Required so Moodle treats requests as HTTPS even though Apache itself is plain HTTP behind Railway's TLS terminator. Otherwise Moodle generates `http://` URLs and login cookies don't stick. |
| **`$CFG->getremoteaddrconf = 1`** (`GETREMOTEADDR_SKIP_HTTP_CLIENT_IP`) | Moodle reads the real client IP from `X-Forwarded-For` instead of Railway's rotating internal proxy IP. Stops `installhijacked` from firing mid-install and stops session IP-flap from logging admins out. Uses the integer value (`1`) because the named constant isn't defined until `lib/setup.php` loads, after `config.php` is parsed. |
| **Proxy block re-injected on every boot** (marker-bounded, idempotent) | Lets future config tweaks (env-var overrides, new defaults) propagate without manual `config.php` edits. |
| **Auto-patches stale `__DIR__` in saved `config.php`** | An earlier version of this entrypoint symlinked `config.php` to the volume. PHP's `realpath()` resolved through the symlink, so `__DIR__` baked in the volume path and `require_once(__DIR__ . '/lib/setup.php')` looked in the wrong place. Old configs are auto-repaired in place; new configs are written directly to `/var/www/html/` and mirrored to the volume by the sync loop. |
| **Versioned Moodle cache purge** (`PURGE_VERSION` marker) | Moodle caches `$CFG` to disk in `moodledata/muc/`. Direct SQL writes to `mdl_config` don't invalidate it. Bump `PURGE_VERSION` in the entrypoint (or set `MOODLE_PURGE_CACHE=1`) to force a one-shot wipe on the next boot. |

### Env-var overrides

| Variable | Default | Purpose |
|----------|---------|---------|
| `MOODLE_REVERSEPROXY` | `0` | Set to `1` only if you have a Host-rewriting proxy in front (not Railway's edge) |
| `MOODLE_SSLPROXY` | `1` | Set to `0` if you ever run Moodle without TLS termination upstream |
| `MOODLE_SKIP_HTTP_CLIENT_IP` | `1` | Set to `0` to allow `HTTP_CLIENT_IP` as a client-IP source (not recommended) |
| `MOODLE_PURGE_CACHE` | `0` | Set to `1` to force a one-time cache wipe on the next boot |
| `TWU_THEME` | `snap` | Override the active theme. Set to `moove` to fall back to Moove. |

---

## TurbineWorks University content build

The `local_twu` plugin is a TurbineWorks-specific Moodle local plugin that bootstraps the entire **TurbineWorks University** course structure, content, quizzes, glossary, and theme configuration on every redeploy. Idempotent — re-runs only when the marker version is bumped or `--force` is passed.

### What the bootstrap creates

- **Site identity**: renames the Moodle site to "TurbineWorks University," activates Snap theme with TurbineWorks navy (`#0d2240`) + gold (`#ffc800`) branding, suppresses the conecti.me theme attribution footer.
- **Course category hierarchy**:
  - `ASA-100 Compliance`
    - `Initial Training` — 8 courses (TWF4-1 through TWF4-8) mirroring the TWF-4 training-record form
    - `Recurring Training (6-month)` — 6-month refresher cycle
    - `Reference Library` — FAA ACs, Industry Standards, Aviation Terminology Glossary
    - `Engine-Parts Specific` — FAA 8130-3 deep dive, LLP, AD/SB, BSI, TBO, ATA Spec 2000, CFM56, GE90/GEnx, PW1000G GTF, Trent, LEAP, V2500
- **Cohorts**: "Initial Trainees" and "Recurring Trainees (6-month)" for auto-enrollment workflows.
- **Lesson content** (Moodle `mod_page` activities): deep-expanded substantive lessons for every module. Currently ~115,000 words across Modules 1–5 (Module 5 just completed); Modules 6–8 in progress.
- **Quizzes** (`mod_quiz` with multichoice questions): per-module knowledge checks; 80% pass mark; 3 attempts max; question banks created in course context.
- **Aviation Glossary** (`mod_glossary`): 98+ terms covering ASA-100 framework, FAA forms, SUP/counterfeit, engine architecture, OEMs (CFM56/LEAP/GE90/GEnx/PW1000G/Trent/V2500), warehousing (FOD/FIFO/mutilation), ESD, hazmat, export control.
- **Custom Certificates** (`mod_customcert`): branded PDF certificates auto-issued on Initial Training course completion. Elements include TurbineWorks University header, recipient name, course name, completion date, unique verification code.
- **Forums** (`mod_forum`): "Ask the QA Manager" Q&A forum in every Initial Training course.
- **Branded course title cards**: navy gradient + gold corner accent PNG cards.
- **Compliance defaults**: completion tracking on site-wide, log retention set to never auto-purge (7+ year ASA-100 expectation).

### Files

```
local_twu/
├── version.php                    — Moodle plugin metadata
├── lang/en/local_twu.php          — Plugin name string
├── cli/bootstrap.php              — The orchestration script run by the entrypoint
├── content/content.php            — All lesson content (the big one)
├── content/quizzes.php            — Quiz question banks per module
├── content/glossary.php           — Aviation glossary entries
└── assets/                        — Logo, favicon, branded course cards
scripts/
└── generate_course_cards.py       — Pillow-based generator for branded card images
```

### Marker version

`local_twu/cli/bootstrap.php` defines `$marker = $CFG->dataroot . '/.twu-bootstrapped-vNN'`. Bump `NN` to force the bootstrap to re-run on next deploy. Each push that adds content or changes site config bumps the marker.

### Domain-knowledge sources referenced in the training content

The training material is grounded in the actual regulatory and industry-standard documents that govern aviation parts distribution:

- **FAA**: AC 00-56B (Voluntary Industry Distributor Accreditation), AC 20-62E, AC 21-29D (SUP detection), AC 21-38 (unsalvageable parts disposition / mutilation), AC 120-77 (maintenance recordkeeping); 14 CFR Parts 21, 39, 43, 145; FAA Forms 8130-3 and 8120-11; FAA DRS; av-info.faa.gov certificate database.
- **International equivalents**: EASA Form 1, TCCA Form One, IAQG OASIS.
- **Industry standards**: ASA-100, AS9120 (and AS9100/AS9110 family), SAE AS5553 (counterfeit electronics), AS6174 (counterfeit materiel), ISO 9001, **NAS 412 (FOD prevention)**, **ATA Spec 300 (packaging)**, **ATA iSpec 2200 (technical publications)**, ATA Spec 2000 (EDI for parts).
- **Hazmat**: 49 CFR Parts 100–185 (DOT), IATA DGR, IPC J-STD-033 (moisture-sensitive devices).
- **ESD**: ANSI/ESD S20.20.
- **Export control**: ITAR (22 CFR 120–130), EAR (15 CFR 730–774), OFAC sanctions screening, BIS, ECCN classification.
- **DoD / procurement**: DFARS 252.246-7007 / -7008, 41 U.S.C. §4109.

---

## Quickly spin up a [Moodle](https://moodle.org) LMS sandbox on [Railway](https://railway.com)

This fork still works as a generic Railway Moodle deployment — it's just a hardened version of the upstream template.

> **This setup is intended for development and sandbox use only. It is not recommended for production. Use at your own risk.**

---

## Table of contents

- [What this is (and isn't)](#what-this-is-and-isnt)
- [Why Railway?](#why-railway)
- [What's in this repo](#whats-in-this-repo)
- [Deploy to Railway](#deploy-to-railway)
- [How the entrypoint works](#how-the-entrypoint-works)
- [Changing the Moodle version](#changing-the-moodle-version)
- [Troubleshooting](#troubleshooting)
- [Resources](#resources)
- [Disclaimer](#disclaimer)
- [License](#license)

---

## What this is (and isn't)

This repo gives you the fastest way to get Moodle running on Railway for:

- Local-ish development without managing a VPS
- Demoing Moodle to stakeholders
- Testing plugins, themes, or configurations
- Learning how Moodle works before committing to a production setup

**It is not hardened for production.** For a production Moodle deployment, you should consider dedicated hosting, a proper backup strategy, a tuned PHP/database configuration, and a security review.

---

## Why Railway?

Railway gives you a managed cloud platform with persistent volumes, built-in PostgreSQL/MySQL databases, automatic HTTPS, and deploy-from-GitHub — everything you need to get a Moodle sandbox running without provisioning a VPS or managing Nginx configs yourself.

---

## What's in this repo

| File | Purpose |
|------|---------|
| `Dockerfile` | Pulls the official Moodle PHP/Apache image and clones the Moodle 4.5 LTS stable branch |
| `railway-entrypoint.sh` | Fixes Apache MPM config, sets up `moodledata` permissions, and configures Railway's reverse-proxy HTTPS passthrough |

---

## Deploy to Railway

### Step 1 — Deploy the template

Click the button below to deploy this template to your Railway account. This will automatically provision the Moodle service, a PostgreSQL database, and a persistent volume for Moodle data.

[![Deploy on Railway](https://railway.com/button.svg)](https://railway.com/deploy/moodle-lms?referralCode=6VSPtD&utm_medium=integration&utm_source=template&utm_campaign=generic)

After the template is deployed, your Railway project will look something like this:

![Railway project overview after deploying the template](screenshots/01-railway-services.png)

### Step 2 — Run the Moodle installer

Once the services are running, open your Moodle URL. You can find it in your Railway project by clicking the **Moodle** service → **Settings** → **Networking** → **Public Networking**. You'll be walked through a series of setup screens.

#### 2.1 — Language

Select your preferred language and click **Next**.

#### 2.2 — Paths

Leave the paths as they are and click **Next**.

#### 2.3 — Database

Select **PostgreSQL (native/pgsql)** as the database type and click **Next**.

#### 2.4 — Database settings

Fill in the database connection form using the credentials from your Railway PostgreSQL service:

| Field | Railway variable |
|-------|-----------------|
| **Database host** | `PGHOST` |
| **Database name** | `PGDATABASE` |
| **Database user** | `PGUSER` |
| **Database password** | `PGPASSWORD` |
| **Tables prefix** | Leave as is (prefilled) |
| **Database port** | `PGPORT` |
| **Unix socket** | Leave empty |

Copy the value of each variable from your Railway project by clicking on the **Postgres** service → **Variables** tab.

![Railway PostgreSQL variables](screenshots/02-railway-postgres-variables.png)

Once filled in, click **Next**.

#### 2.5 — Copyright notice

Read through the copyright notice and click **Continue**.

#### 2.6 — Server checks

Moodle will run a series of checks on your server environment. If everything shows as **OK**, click **Continue**. If any checks fail, check the build logs in your Railway project for hints.

#### 2.7 — System installation

Moodle will now run the full database installation. This may take a minute or two — you'll see a log of items being installed as it progresses. Scroll down to the bottom of the page and wait for the last item (`factor_webauthn`) to appear, then click **Continue**.

> If you see *"Installation must be finished from the original IP address, sorry."* after this step, see [IP address mismatch](#ip-address-mismatch) in the Troubleshooting section.

#### 2.8 — Create main administrator account

Fill in the administrator account details as you see fit, then click **Update profile**.

#### 2.9 — Settings

Configure your site settings as you see fit, then click **Save changes**.

#### 2.10 — Register your site (optional)

You'll be prompted to register your site with Moodle HQ. This is entirely optional — skip it if you prefer, or fill in your details and submit. Either way, you're done. Your Moodle sandbox is up and running.

---

## How the entrypoint works

Railway sits behind a reverse proxy that terminates TLS. The `railway-entrypoint.sh` script handles two Railway-specific quirks:

- **HTTPS detection** — adds an Apache rule so that `X-Forwarded-Proto: https` is correctly passed to PHP as `HTTPS=on`, preventing Moodle from generating `http://` URLs for assets.
- **MPM prefork** — ensures Apache uses the `prefork` MPM (required for `mod_php`) rather than `mpm_event`, which ships as the default in some base images.

---

## Changing the Moodle version

This repo tracks the **`MOODLE_405_STABLE`** branch — Moodle's 4.5 Long Term Support (LTS) stable branch, the latest LTS version as of February 2026. This means every rebuild automatically picks up the latest patch release (bug fixes, security patches) without manually bumping a version tag.

To switch to a different Moodle version, change the branch in the `Dockerfile`:

```dockerfile
RUN git clone --depth 1 -b MOODLE_404_STABLE https://github.com/moodle/moodle.git /var/www/html \
```

Browse available stable branches and release tags on the [Moodle GitHub tags page](https://github.com/moodle/moodle/tags). Redeploy after changing the branch — Moodle's built-in upgrade script will run automatically on next visit if the new version is higher than the installed one.

> Always back up your database and volume before changing versions.

---

## Troubleshooting

### IP address mismatch

After the system installation step you may see the error: *"Installation must be finished from the original IP address, sorry."* ([installhijacked](https://docs.moodle.org/405/en/error/admin/installhijacked)). This is a known side effect of Railway's reverse proxy and is not a security issue in this context. Simply **refresh the page** — it usually resolves within a few refreshes (typically less than 20) and you'll be taken to the next step automatically.

#### Guaranteed fix — update the IP in the database

If refreshing doesn't work, you can resolve this by updating the IP address stored in the database to match your current one:

**1. Find your current IP**

In your Railway project, open the **Moodle** service, open the current deployment, then go to the **Deploy Logs** tab. At the bottom of the log you'll see your current IP address — for example, you may have started the installation on `100.64.0.1` but are now on `100.64.0.2`. Copy this IP.

![Moodle deploy logs showing current IP](screenshots/03-deploy-logs-ip.png)

**2. Update the IP in the database**

Open the **Postgres** service and go to the **Database** tab. Find the `mdl_user` table and open it. Locate the record with the username `admin`, click on it, and change the `lastip` field to the IP you just copied. Save the record, then reload your Moodle tab.

![mdl_user table in Railway Postgres with lastip field](screenshots/04-mdl-user-lastip.png)

### Permission errors on moodledata

The entrypoint sets ownership and permissions on `/var/www/moodledata` at startup. If you see permission errors, confirm the volume is mounted at exactly `/var/www/moodledata`.

### "Database connection failed" on installer

Double-check that the values entered in [step 2.4](#24--database-settings) match the actual variable values shown in the **Postgres** service → **Variables** tab in your Railway project.

### Build takes a long time

The Moodle codebase is large (~400 MB). Railway caches Docker layers — subsequent deploys that don't change the `Dockerfile` will be much faster.

---

## Resources

- [Moodle documentation](https://docs.moodle.org)
- [Railway documentation](https://docs.railway.com)
- [moodlehq/moodle-php-apache on Docker Hub](https://hub.docker.com/r/moodlehq/moodle-php-apache)
- [Moodle system requirements](https://docs.moodle.org/405/en/Installing_Moodle#Requirements)

---

## Disclaimer

This project is provided as-is for sandbox and development purposes. No guarantees are made regarding security, stability, or suitability for any particular use. **Use at your own risk.** For production Moodle deployments, refer to the [official Moodle installation documentation](https://docs.moodle.org/405/en/Installing_Moodle).

---

## License

This repository's configuration files are released under the [MIT License](https://opensource.org/licenses/MIT).

Copyright (c) 2026 Jesse J.T. Zweers

Moodle itself is licensed under the [GNU GPL v3](https://docs.moodle.org/dev/License).
