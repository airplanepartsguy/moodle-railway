# Moodle on Railway — TurbineWorks University fork

This is a customized fork of [JesseZweers/moodle-railway](https://github.com/JesseZweers/moodle-railway), adapted to power **TurbineWorks University** — the LMS at [learn.turbineworks.com](https://learn.turbineworks.com) used for ASA-100 compliance training (initial + 6-month recurring) for TurbineWorks Aircraft Engine Parts & Components.

Upstream gives you a generic Moodle sandbox. This fork:
- Hardens it for Railway-specific deployment quirks (proxy, config persistence, cache)
- Adds a fully content-driven local plugin (`local_twu`) that bootstraps the entire course structure, content, quizzes, certificates, cohorts, reports, and theme on every deploy
- Ships ~200,000 words of audit-grade ASA-100 compliance training content as PHP source-of-truth, idempotently imported into Postgres

See [What's different in this fork](#whats-different-in-this-fork) for the Railway hardening and [TurbineWorks University content build](#turbineworks-university-content-build) for the local_twu plugin.

> The upstream template is explicitly "not for production." This fork closes most of the gaps that made that true on Railway. It's still not multi-region, HA, or fully-hardened — but for a single-tenant compliance-training site behind Railway's edge, it's solid.

---

## What's different in this fork

### Railway hardening (`railway-entrypoint.sh`)

All changes live in the entrypoint — no Moodle core modifications:

| Fix | Why it matters |
|-----|----------------|
| **`config.php` persists to the Railway volume** (copy on boot + 30s background sync) | Upstream wipes `config.php` on every redeploy because `/var/www/html/` is not on a volume. You'd re-run the install wizard every deploy. Now the config survives. |
| **`$CFG->reverseproxy = false`** (Railway-correct default) | Moodle's `reverseproxy = true` expects the proxy to *rewrite* the Host header. Railway passes the public Host through unchanged → `reverseproxyabused` error on every request. |
| **`$CFG->sslproxy = true`** | Required so Moodle treats requests as HTTPS even though Apache itself is plain HTTP behind Railway's TLS terminator. |
| **`$CFG->getremoteaddrconf = 1`** | Moodle reads client IP from `X-Forwarded-For`. Stops `installhijacked` and session IP-flap logouts. |
| **Proxy block re-injected on every boot** | Lets future config tweaks propagate without manual `config.php` edits. |
| **Auto-patches stale `__DIR__` in saved `config.php`** | Cleans up an artifact from an earlier symlink-based approach. |
| **Versioned Moodle cache purge** | Force a one-shot wipe on next boot by bumping `PURGE_VERSION`. |
| **Runs plugin upgrade + local_twu bootstrap on every boot** | Plugin DB upgrades + content/structure refresh happen automatically. |

### Env-var overrides

| Variable | Default | Purpose |
|----------|---------|---------|
| `MOODLE_REVERSEPROXY` | `0` | Set to `1` only if you have a Host-rewriting proxy in front (not Railway's edge) |
| `MOODLE_SSLPROXY` | `1` | Set to `0` if you ever run Moodle without TLS termination upstream |
| `MOODLE_SKIP_HTTP_CLIENT_IP` | `1` | Set to `0` to allow `HTTP_CLIENT_IP` as a client-IP source |
| `MOODLE_PURGE_CACHE` | `0` | Set to `1` to force a one-time cache wipe on the next boot |
| `TWU_THEME` | `snap` | Override the active theme. Set to `moove` to fall back to Moove. |

---

## TurbineWorks University content build

The `local_twu` plugin is a Moodle local plugin that bootstraps the entire **TurbineWorks University** training environment on every redeploy. Idempotent — each helper checks for existing state before creating. Re-runs occur when the marker version is bumped or `--force` is passed.

### What the bootstrap creates

#### Site identity and theme
- Renames the Moodle site to "TurbineWorks University"
- Activates Snap theme with TurbineWorks navy (`#0d2240`) + gold (`#ffc800`) branding
- Replaces the default "Built with Open LMS" footer with TurbineWorks copyright + contact
- Configures frontpage layout: categories+search for visitors, enrolled courses+categories for logged-in users

#### Course category hierarchy
- **ASA-100 Compliance**
  - **Initial Training** — 8 courses (TWF4-1 through TWF4-8) mirroring the TWF-4 training-record form, plus a **Cumulative Final Exam** course (TWF4-FINAL)
  - **Recurring Training (6-month)** — placeholder for refresher cycle content
  - **Reference Library** — FAA ACs, Industry Standards, Auditor-facing reference content, Aviation Terminology Glossary
  - **Engine-Parts Specific** — FAA 8130-3 deep dive, LLP, AD/SB, BSI, TBO, ATA Spec 2000, CFM56 (deep), GE90/GEnx, PW1000G GTF, Trent, LEAP, V2500, plus Operations courses (AS9120, Customer Relations, Export Control / ITAR / EAR)

#### Lesson content (Moodle `mod_page` activities)
~200,000 words of audit-grade lessons. Each substantive lesson is ~4,000–5,000 words with:
- Historical context (case studies — ValuJet 592, UPS Flight 6, Southwest 1380)
- CFR citations and FAA AC references
- Specific operational scenarios
- Comparison tables
- Common misconceptions / red flags
- Self-check questions

Module coverage:
- **Module 1 — Unapproved Parts** (~21k words, 5 lessons)
- **Module 2 — Receiving & Shipping** (~25k words, 6 lessons)
- **Module 3 — ASA-100 Familiarization** (~18k words, 4 lessons)
- **Module 4 — Parts & Warehousing** (~22k words, 5 lessons; NAS 412 FOD prominent in 4.4)
- **Module 5 — Recordkeeping** (~24k words, 5 lessons; includes user-specified component-level identity mutilation)
- **Module 6 — FAA AC 00-56 Deep Dive** (~19k words, 4 lessons)
- **Module 7 — ESD Handling** (~22k words, 5 lessons per ANSI/ESD S20.20)
- **Module 8 — Hazmat Identification** (8.1–8.3 deep-expanded ~18k words; 8.4–8.5 moderate)
- **CFM56 Engine** (7 lessons, ~33k words including practical operations + cheat sheets)
- **Supplemental Video Pages** in each Initial Training course with curated authoritative-source channel links

#### Quizzes and assessments
- **Per-module knowledge checks** (`mod_quiz`): multichoice questions, 80% pass mark, 3 attempts, deferred feedback, randomized order
- **Cumulative Final Exam**: 40 random questions drawn 5-each from the 8 module banks, 60-minute time limit, 80% pass mark, 3 attempts. Gated behind completion of all module quizzes. Earns master certification.
- **Practical assignments** (`mod_assign`): one per module. Online text submission, 300–500 word response to a real operational scenario, QA Manager grades.

#### Cohorts (for auto-enrollment)
- **All Employees** — baseline for every employee; auto-enrols into all Initial Training
- **Initial Trainees** — currently working through Initial Training
- **Recurring Trainees (6-month)** — in the 6-month recurring cycle
- **Role: Warehouse Operators / QA / Shipping & Receiving / Management**
- **Certification: DOT Hazmat / IATA DGR Air Shipper / ESD Handler**
- **Authority: SUP Reporter / DGD Signer**

Cohort sync (Moodle's `enrol_cohort`) auto-enrols members into the appropriate courses.

#### Custom Moodle roles
- **QA Manager** (`twu_qa_manager`) — site-wide oversight. Grades forums/assignments, views all completion reports, manages certificates. Reads but does not edit content.
- **Trainer / Content Editor** (`twu_trainer`) — can edit/create courses, lessons, quizzes, question banks. No grading access.
- **Auditor (Read-Only)** (`twu_auditor`) — full read-only access for ASA inspectors or customer auditors. All training records, completion data, course content — read-only.

#### Custom user profile fields ("TurbineWorks Employee Information")
- Department (menu: Warehouse / QA / Shipping / Engineering / Management / Sales / Admin / Other)
- Job Title (text)
- Employee Number (text)
- Hire Date (datetime)
- Supervisor (text)
- DOT Hazmat Certification Expiry (datetime; per 49 CFR §172.704)
- IATA DGR Certification Expiry (datetime; per IATA DGR §1.5)
- ESD Handler Certification Expiry (datetime; per ANSI/ESD S20.20)
- ASA Cert / Training ID (text; post-accreditation)

#### Course completion criteria
Every Initial Training, engine, and operations course is configured to be "complete when all tracked activities (page lessons + quizzes) are complete." Moodle's `course_completions` table reflects actual completion, not just enrolment.

#### Certificates (`mod_customcert`)
- **Per-module certificates** — branded PDF, navy/gold, includes recipient name, course, completion date, 180-day validity disclaimer, unique 10-character verification code, public verification URL
- **Master Certification** (cumulative exam course) — distinct layout, gold border, "MASTER CERTIFICATION" wording, 14 elements
- **Certificate availability gating** — certs are visible but unavailable until completion criteria are met (no premature downloads)
- **QR code element** — probed at deploy; auto-included if the optional `customcertelement_qrcode` plugin is installed
- **Public verification page** at `/local/twu/verify.php` shows recipient, employee details (from custom profile fields), course, issue date, 180-day validity window, current completion status from `course_completions`, and explicit status (CURRENT / RECURRING TRAINING DUE / EXPIRED — RECURRING RESET)

#### Forums (`mod_forum`)
- **"Ask the QA Manager"** forum in every Initial Training course
- Each forum pre-seeded with a pinned welcome thread + 3–5 FAQ threads (real questions with QA Manager answers)

#### Welcome blocks
Each Initial Training course has an HTML block in the right column with: module description, estimated time, step-by-step completion guide, pointer to the Q&A forum.

#### Glossary (`mod_glossary`)
98+ aviation terms covering ASA-100, FAA forms, SUP/counterfeit, engine architecture, OEMs, warehousing (FOD/FIFO/mutilation), ESD, hazmat, export control. Auto-linked from any lesson text via Moodle's auto-link filter.

#### Reports admin pages
Accessible at **Site Admin → Reports → TurbineWorks Reports** (also at `/local/twu/report.php`):
- **Initial Training Completion Roster** — user × course matrix with completion dates and per-user totals
- **Recurring Training Due Tracker** — completions sorted by days to 180-day expiry; identifies expired/expiring/current
- **Certification Expiry Roster** — DOT Hazmat / IATA DGR / ESD expiry by employee
- **Failed Quiz Attempts** — coaching candidates

Each report supports HTML (color-coded) and CSV download.

#### Site calendar events
Recurring compliance milestones: quarterly management reviews, annual ASA audit prep window, DOT Hazmat status checks, IATA DGR semi-annual reviews, 6-month recurring training windows.

#### Scheduled tasks
- **`local_twu\task\recurring_reset`** — runs daily at 02:07. Scans Initial Training course completions; for any user whose completion is older than 180 days, resets activity completion records, quiz attempts, and course completion so they must re-do the training. Implements TurbineWorks' 6-month recurring training cadence.

#### Demo template users (suspended)
- `demo.warehouse` (Warehouse Operator role)
- `demo.qa` (QA Inspector role)
- `demo.shipping` (Shipping & Receiving role)
- `demo.manager` (Management role)

Suspended by default — activate via Site Admin to use as real accounts, or copy their cohort memberships when creating actual employee accounts.

#### Site-wide settings
- Course completion tracking enabled site-wide
- Activity availability restrictions enabled
- Log retention set to never auto-purge (`loglifetime=0` — supports 7+ year ASA-100 expectation)
- `filter_mediaplugin` enabled site-wide (YouTube/Vimeo URLs in lesson HTML render as embedded video players)

---

### Files

```
local_twu/
├── version.php                              Plugin metadata (currently 1.3.0)
├── lang/en/local_twu.php                    Plugin name string
├── settings.php                             Admin tree registration for Reports section
├── db/
│   ├── access.php                           Capability definition (local/twu:viewreports)
│   └── tasks.php                            Scheduled task registration (recurring_reset)
├── classes/task/
│   └── recurring_reset.php                  6-month recurring training reset logic
├── cli/
│   └── bootstrap.php                        Main orchestration script (~2,900 lines)
├── content/
│   ├── content.php                          All lesson HTML (~14,000 lines, ~200k words)
│   ├── quizzes.php                          Question banks per module (~66 questions)
│   └── glossary.php                         Aviation glossary entries (98+ terms)
├── report.php                               TurbineWorks Reports admin pages
├── verify.php                               Public cert verification landing page
├── diag.php                                 Bootstrap diagnostic page (admin-only)
└── assets/                                  Logo, favicon, course tile images

Dockerfile                                   Moodle 4.5 LTS + Snap + Moove + mod_customcert
railway-entrypoint.sh                        Railway hardening + bootstrap invocation
```

### Bootstrap marker

`local_twu/cli/bootstrap.php` defines:
```php
$marker = $CFG->dataroot . '/.twu-bootstrapped-vNN';
```

Bump `NN` to force the bootstrap to re-run on next deploy. Every push that adds content or changes site config bumps the marker.

### Bootstrap resilience

Each "infrastructure" section (custom roles, profile fields, cohort sync, calendar events, demo users, completion criteria, cert availability, welcome blocks, course cache rebuild) is wrapped in `try/catch`. After a caught exception, `twu_reset_db_transaction()` defensively force-rolls-back any open transaction so subsequent sections can write. A failure in one section logs `[twu] WARN <section> failed: <reason> — continuing.` and the script proceeds.

Each capability assignment in `twu_ensure_role` is also individually wrapped — a single invalid/renamed Moodle 4.5 capability (e.g., one that was renamed since the role definition was written) is logged and skipped without aborting the role's other capabilities.

### Bootstrap diagnostic page

Visit **`/local/twu/diag.php`** as admin to see:
- Bootstrap marker history
- Per-course counts of pages / quizzes / forums / certs / assignments (instantly identifies courses missing content)
- Cohort list with member + sync counts
- Custom roles
- Custom profile fields
- Active theme + theme color config
- Recent PHP errors
- **"Re-run bootstrap now (--force)"** button — executes the bootstrap synchronously and streams every line of output to the page, including PHP fatal errors with file:line

This is the primary debugging tool when something doesn't appear as expected on a deploy.

### Domain-knowledge sources referenced in the training content

The training material is grounded in actual regulatory and industry-standard documents:

- **FAA**: AC 00-56B (Voluntary Industry Distributor Accreditation), AC 20-62E, AC 21-29D (SUP detection), AC 21-38 (unsalvageable parts disposition / mutilation), AC 120-77 (maintenance recordkeeping); 14 CFR Parts 21, 39, 43, 145; 14 CFR §33.70 (engine LLPs); FAA Forms 8130-3 and 8120-11; FAA DRS; av-info.faa.gov certificate database
- **International equivalents**: EASA Form 1, TCCA Form One, IAQG OASIS
- **Industry standards**: ASA-100, AS9120 (and AS9100/AS9110 family), SAE AS5553 (counterfeit electronics), AS6174 (counterfeit materiel), ISO 9001, **NAS 412 (FOD prevention)**, **ATA Spec 300 (packaging)**, **ATA iSpec 2200 (technical publications)**, ATA Spec 2000 (EDI for parts)
- **Hazmat**: 49 CFR Parts 100–185 (DOT), IATA DGR (annual edition), IPC J-STD-033 (moisture-sensitive devices), ICAO Technical Instructions, IMDG Code
- **ESD**: ANSI/ESD S20.20, ESD TR53, ANSI/ESD S8.1, ANSI/ESD S541
- **Export control**: ITAR (22 CFR 120–130), EAR (15 CFR 730–774), OFAC sanctions screening, BIS, ECCN classification
- **DoD / procurement**: DFARS 252.246-7007 / -7008, 41 U.S.C. §4109
- **Incidents referenced as case studies**: ValuJet 592 (1996, chemical oxygen generators), UPS Flight 6 (2010, lithium batteries), Asiana 991 (2011, lithium batteries), Southwest 1380 (2018, CFM56-7B fan blade), Partnair 394 (1989, SUP-related fatigue)

---

## Quickly spin up a generic [Moodle](https://moodle.org) sandbox on [Railway](https://railway.com)

This fork still works as a generic Railway Moodle deployment — it's just a hardened version of the upstream template with the TurbineWorks-specific content layered on top.

> **The Moodle install is hardened for Railway, but the TurbineWorks content (`local_twu`) is specific to ASA-100 aviation parts distribution training and won't be useful in other contexts. Fork and replace `local_twu/content/*.php` with your own content arrays, or remove the plugin if you just want a generic Moodle.**

---

## Table of contents

- [What's different in this fork](#whats-different-in-this-fork)
- [TurbineWorks University content build](#turbineworks-university-content-build)
- [Deploy to Railway](#deploy-to-railway)
- [How the entrypoint works](#how-the-entrypoint-works)
- [Operations](#operations)
- [Changing the Moodle version](#changing-the-moodle-version)
- [Troubleshooting](#troubleshooting)
- [Resources](#resources)
- [Disclaimer](#disclaimer)
- [License](#license)

---

## Deploy to Railway

### Step 1 — Deploy the template

Click the button below to deploy this template to your Railway account. This will automatically provision the Moodle service, a PostgreSQL database, and a persistent volume for Moodle data.

[![Deploy on Railway](https://railway.com/button.svg)](https://railway.com/deploy/moodle-lms?referralCode=6VSPtD&utm_medium=integration&utm_source=template&utm_campaign=generic)

### Step 2 — Run the Moodle installer

Once the services are running, open your Moodle URL. You'll be walked through a series of setup screens.

#### 2.1–2.3 — Language, paths, database type

Default through. Select **PostgreSQL (native/pgsql)** for database type.

#### 2.4 — Database settings

| Field | Railway variable |
|-------|-----------------|
| **Database host** | `PGHOST` |
| **Database name** | `PGDATABASE` |
| **Database user** | `PGUSER` |
| **Database password** | `PGPASSWORD` |
| **Tables prefix** | Leave as is (prefilled) |
| **Database port** | `PGPORT` |
| **Unix socket** | Leave empty |

#### 2.5–2.7 — Copyright, server checks, install

Click through. The system install may take a minute or two.

> If you see *"Installation must be finished from the original IP address, sorry."* see [IP address mismatch](#ip-address-mismatch) in the Troubleshooting section.

#### 2.8 — Create admin account

Fill in admin details. After you finish the wizard, the `local_twu` bootstrap will run automatically on the next container boot and populate all the TurbineWorks content, cohorts, roles, and theme.

---

## Operations

Once Moodle is installed and the local_twu bootstrap has run, the system is operationally complete enough for ASA-100 audit purposes.

### Site URLs

| URL | Purpose |
|-----|---------|
| `/` | Site home — categories visible to anyone, enrolled courses visible to logged-in users |
| `/login/index.php` | Login |
| `/local/twu/verify.php` | Public certificate verification (no login required) |
| `/local/twu/verify.php?code=XXX` | Verify a specific certificate |
| `/local/twu/diag.php` | Bootstrap diagnostic (admin only) — see what got created, view PHP errors, re-run bootstrap |
| `/local/twu/report.php` | TurbineWorks Reports admin pages (QA Manager / Auditor / admin) |
| `/admin/` | Standard Moodle Site Admin |

### Day-to-day operations

**Adding a new employee:**
1. Site Admin → Users → Add a new user
2. Set username, password, email
3. Populate TurbineWorks Employee Information profile fields (Department, Job Title, etc.)
4. Add the user to the appropriate cohort(s):
   - "All Employees" — always
   - Role cohort matching their job (Warehouse / QA / Shipping / Management)
   - Certification cohort if currently certified (Hazmat / DGR / ESD)
5. Cohort sync auto-enrols them in the appropriate courses (typically within 1 minute)

**Reviewing training status:**
- Site Admin → Reports → TurbineWorks Reports → **Initial Training Completion Roster** for matrix view
- **Recurring Training Due Tracker** for upcoming/overdue
- **Certification Expiry Roster** for DOT/DGR/ESD certifications

**During an ASA audit:**
- Create an Auditor user (assign the `twu_auditor` role at the system context)
- Walk through reports, sample completion records, demonstrate certificate verification at `/local/twu/verify.php`
- Inspector can verify any certificate by entering its verification code

**Recurring training cadence:**
- The `local_twu\task\recurring_reset` scheduled task runs daily at 02:07 server time
- Completions older than 180 days are automatically reset (activity completion, quiz attempts, and `course_completions` rows removed)
- Affected users see those modules as "not complete" and must re-do them
- Cohort sync ensures they're still enrolled

### Bootstrap re-run

If the bootstrap halted or you suspect content is out of sync:
1. Visit `/local/twu/diag.php`
2. Check the course counts table — courses showing 0 pages are missing lessons
3. Click **"Re-run bootstrap now (--force)"**
4. The page shows live output; any errors are visible with file:line
5. Refresh after completion to see updated state

If markers need wiping (a "from scratch" re-run):
1. `/local/twu/diag.php` → **"Delete bootstrap markers"**
2. Re-run bootstrap or redeploy

---

## Changing the Moodle version

This repo tracks the **`MOODLE_405_STABLE`** branch — Moodle's 4.5 Long Term Support (LTS) stable branch. Every rebuild automatically picks up the latest patch release.

To switch versions, change the branch in the `Dockerfile`:

```dockerfile
RUN git clone --depth 1 -b MOODLE_404_STABLE https://github.com/moodle/moodle.git /var/www/html \
```

Browse stable branches on the [Moodle GitHub tags page](https://github.com/moodle/moodle/tags). Redeploy after changing the branch — Moodle's built-in upgrade script runs automatically.

> Always back up your database and volume before changing versions.

---

## Troubleshooting

### Courses exist but have no lessons / theme is default purple

The local_twu bootstrap halted partway. Hit `/local/twu/diag.php` → click "Re-run bootstrap now" → look at the streamed output for the first `[twu]   could not create ...` or `[twu] WARN ...` line. The message gives the actual error. Most common causes:

- A Moodle 4.5 capability was renamed and `assign_capability` threw → corrupted DB transaction → all subsequent writes failed with "Error writing to database". Fixed by per-capability try/catch + `twu_reset_db_transaction()` after each catch.
- mod_customcert dependency missing (`locallib.php` was removed in v4.4+). Fixed by gating require_once on `file_exists`.
- Plugin upgrade hasn't run — the local_twu plugin's version bumps require Moodle to register new capabilities / tasks. Make sure the entrypoint's `admin/cli/upgrade.php` step succeeded.

### IP address mismatch (installhijacked)

After install you may see *"Installation must be finished from the original IP address, sorry."* This is a Railway reverse-proxy artifact. **Refresh the page** — usually clears within 20 refreshes. If not, edit `mdl_user` table → `admin` row → set `lastip` to the IP shown in the Railway deploy logs.

### "Database connection failed"

Check that the values in step 2.4 match your Railway Postgres variables exactly.

### Permission errors on moodledata

The entrypoint sets ownership/permissions on `/var/www/moodledata`. Confirm the volume is mounted at exactly that path.

### Build takes a long time

The Moodle codebase is ~400 MB. Railway caches Docker layers — subsequent deploys that don't change the Dockerfile are much faster.

---

## Resources

- [Moodle documentation](https://docs.moodle.org)
- [Railway documentation](https://docs.railway.com)
- [moodlehq/moodle-php-apache on Docker Hub](https://hub.docker.com/r/moodlehq/moodle-php-apache)
- [ASA-100 standard](https://www.aviationsuppliers.org/) (member portal)
- [FAA AC 00-56B](https://www.faa.gov/regulations_policies/advisory_circulars/)
- [FAA Form 8130-3 reference](https://www.faa.gov/forms/)
- [ANSI/ESD S20.20](https://www.esda.org/)
- [IATA DGR](https://www.iata.org/en/publications/dgr/)

---

## Disclaimer

This project is provided as-is for TurbineWorks-specific use. The training content reflects best understanding of the regulatory framework at time of authoring but should not be relied upon for legal interpretation — the underlying regulations (49 CFR, 14 CFR, ICAO TI, IATA DGR, ANSI/ESD S20.20) are authoritative.

For production Moodle deployments, refer to the [official Moodle installation documentation](https://docs.moodle.org/405/en/Installing_Moodle).

---

## License

This repository's configuration files are released under the [MIT License](https://opensource.org/licenses/MIT).

Original Railway template Copyright (c) 2026 Jesse J.T. Zweers. TurbineWorks-specific extensions copyright (c) 2026 TurbineWorks Aircraft Engine Parts & Components.

Moodle itself is licensed under the [GNU GPL v3](https://docs.moodle.org/dev/License).
