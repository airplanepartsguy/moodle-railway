# TurbineWorks University (TWU) — Migration Scope

**Project:** Replace forked Moodle (`learn.turbineworks.com`) with a custom, audit-grade Learning Management System purpose-built for ASA-100 compliance training at TurbineWorks.

**Repo name (locked):** `av-lms` — generic enough to be packageable for sale to other ASA-100-accredited aviation parts distributors, specific enough to signal aviation-LMS domain.

**Topology (locked):** **Separate repo, deeply integrated with TW-OPS.** Not a monorepo. Standalone deployable app with explicit federation contracts to TW-OPS. Designed so it can later be sold as a standalone product without disentanglement.

**Document status:** v1.1 — Topology decision locked (2026-05-24). Strategic scope, ready for execution planning.
**Author:** Engineering, with QA Manager review pending.
**Last revised:** 2026-05-24.
**Audience:** TurbineWorks engineering, QA Manager, future external auditors (ASA, FAA, ESD program assessor, DoD prime contractor flow-down auditors).

---

## ADR-0001 — Repo topology: federated, not monorepo

**Status:** Accepted (2026-05-24).
**Context:** The scope document raised Mode A (monorepo with TW-OPS) vs Mode B (separate repos with federated integration). User decision: Mode B.

**Decision rationale:**

1. **Sellability.** av-lms is designed to be a standalone product — aviation parts distributors industry-wide need ASA-100 compliance training infrastructure. Mode A would couple LMS code to TW-OPS internals (TurbineWorks-specific QMS, supplier lists, AD trackers) and require painful disentanglement at the time of any future commercialization. Mode B keeps clean product boundaries from day 1.
2. **Independent lifecycle.** Different deploy cadence (LMS changes when training content changes; TW-OPS changes when operational procedures change), different user populations (LMS includes auditors/customers; TW-OPS is internal-only), different security profile (public cert verification on LMS; ops data internal-only).
3. **Blast radius.** LMS outages don't affect ops, and vice versa.
4. **Versioning.** Each app evolves independently. LMS can ship to one customer's auditor on a stable version while TurbineWorks itself runs newer features internally.

**Deep-integration contracts (the "deeply integrate" half of the decision):**

| Contract | Direction | Mechanism |
|---|---|---|
| **Identity / SSO** | TW-OPS → av-lms | Shared IdP (Better Auth instance, hosted alongside TW-OPS). av-lms verifies tokens issued by the shared IdP. New TurbineWorks employees log into av-lms with their TW-OPS credentials. For non-TW customers, av-lms can run its own Better Auth instance — same code path. |
| **User roster sync** | TW-OPS → av-lms | TW-OPS is source-of-truth for employee identity (name, email, department, job title, hire date, supervisor). av-lms ingests via webhook on user create/update, OR polls a `GET /api/users` endpoint on TW-OPS. av-lms maintains local user table with foreign-key relationships to training records. |
| **Training-record reporting** | av-lms → TW-OPS | av-lms exposes a read-only API: `GET /api/training/{user_id}` returns completion status, certifications, expiry dates. TW-OPS can embed this on the employee profile page via an iframe or server-side fetch. |
| **Training assignment events** | TW-OPS → av-lms | When TW-OPS adds a user to a role/cohort that triggers training, TW-OPS emits an event (webhook) that av-lms consumes to assign the user to the corresponding LMS course. |
| **Design system** | Bidirectional | Both apps consume a shared shadcn/ui-derived component library published as an npm package (e.g., `@turbineworks/design-system`). Visual consistency across both apps. For external av-lms customers, the design system is forkable/themeable. |
| **Audit log correlation** | Bidirectional | Each app maintains its own audit log (per ALCOA+ — each is the authoritative record for its own data). When events cross the boundary (e.g., TW-OPS-initiated training assignment), both logs record their side of the event with a shared correlation ID. |
| **Cert verification** | Public | The cert verification page (`/verify/[code]`) is on av-lms only and is publicly accessible regardless of either system's auth state. |

**Consequences:**

- ✅ Future commercialization path remains open without architectural debt.
- ✅ Either system can be replaced/rewritten without touching the other (within the API contract).
- ✅ Clean security boundary: an auditor account for av-lms can be issued without granting any TW-OPS access.
- ⚠️ Slightly more infrastructure to operate (two apps instead of one, two deploy pipelines).
- ⚠️ Federation contracts must be versioned and stable; breaking changes require coordinated deploys.
- ⚠️ Single sign-on session handling needs care to not duplicate identity tokens.

---

## ADR-0002 — Hosting: Railway

**Status:** Accepted (2026-05-24).
**Context:** TW-OPS is hosted on Vercel (Next.js). The scope's original recommendation was Railway; mid-discussion I flipped to recommending Vercel for "match TW-OPS" DX; on user pushback I returned to Railway. The choice is durable across all federation contracts (ADR-0001) — host doesn't affect any cross-system contract.

**Decision rationale:**

1. **Container-portable for future commercial sale.** av-lms is designed to ship as a standalone product to other ASA-100-accredited aviation parts distributors. Many enterprise B2B customers want self-hosted or want the product deployable to their own AWS/GCP/Azure. Railway's container model maps directly to a Docker image we can publish; extracting from Vercel's serverless-specific patterns would be painful at sale time.
2. **Postgres co-location.** Railway hosts app + Postgres in the same datacenter (~1ms latency). Vercel forces external Postgres (Neon/Supabase) with 20-50ms serverless query routing per call. At low scale this is imperceptible, but it adds up on dashboard renders that do 8-12 queries.
3. **No function timeout ceiling.** Cert PDF generation, hash-chain audit-log verification, batch operations, and recurring-training reset all run as ordinary code in a container with no timeout cap. Vercel Pro caps at 60 seconds; Hobby at 10 seconds.
4. **Cost predictability.** Compliance training has bursty traffic (large fractions of the user base hit the system simultaneously during the recurring window). Railway charges flat per container; Vercel charges metered execution + bandwidth. Predictable bills.
5. **Background workers are first-class.** Recurring training reset, batch certificate generation, audit-log integrity checks, and notification sends all run on a dedicated Railway worker service in the same project, without bolting on Inngest/Trigger.dev as external dependencies.
6. **Familiarity.** The Moodle deployment runs on Railway today. The team knows the platform.

**Trade-offs accepted:**

- **DX win from matching TW-OPS deploy story is forfeit.** Different CI/CD secrets, different monitoring panels, different deploy patterns. Mitigated by the fact that the two apps don't share infrastructure runtime — only contracts.
- **Slightly higher monthly cost than Vercel free tier at MVP scale** (~$35-65/mo Railway vs ~$0-20/mo Vercel). Negligible.

**Stack (locked):**

| Layer | Choice |
|-------|---|
| Hosting | Railway (app service + worker service) |
| Database | Railway Postgres (same project, same region) |
| ORM | Drizzle |
| Auth | Better Auth (self-hosted on app service) |
| Background jobs | Railway worker service running BullMQ on Railway Redis — *or* Inngest if managed durability is preferred (Phase 0 decision) |
| Cron | Railway native scheduled jobs |
| File storage | Cloudflare R2 (S3-compatible, cheap, no egress fees) |
| Email | Resend |
| Observability | Sentry (errors) + Axiom (logs) |
| CDN | Cloudflare in front of Railway (custom domain) |

**Cost (revised estimate):**

| Item | Monthly |
|---|---|
| Railway app service | $5-15 |
| Railway worker service | $5-10 |
| Railway Postgres | $10-20 |
| Railway Redis (if BullMQ chosen) | $5-10 |
| Cloudflare R2 | $0-5 |
| Resend | $0-10 |
| Sentry + Axiom | $0 (free tiers) |
| **Total** | **~$25-70/mo at MVP scale** |

**Consequences for federation contracts (ADR-0001):**

All contracts continue to work cross-host (Vercel ↔ Railway). No changes required to ADR-0001. Specifically:
- SSO: OIDC token exchange works regardless of host.
- User sync webhook: HTTP from `tw-ops.vercel.app` to `av-lms.turbineworks.com` works exactly the same as same-host.
- Training-record API: same.
- Shared design system: npm package, no infrastructure dependency.
- Audit correlation: shared correlation ID, no infrastructure dependency.

---

## ADR-0004 — Workflow orchestration layer: n8n (external glue)

**Status:** Accepted (2026-05-24).
**Context:** TurbineWorks already operates an **n8n** instance for cross-system automation. The av-lms scope needs to decide: do orchestration tasks (cross-system event routing, notifications, scheduled reminders, audit-prep workflows) live inside av-lms, inside TW-OPS, or in n8n? This ADR draws the boundary.

### Decision

**Use n8n for the async / glue / notification layer.** av-lms remains authoritative for compliance-relevant data and decisions; n8n subscribes to av-lms events and orchestrates downstream actions.

### What n8n does

| Workflow | Trigger | Action |
|---|---|---|
| TW-OPS → av-lms user sync | TW-OPS webhook on user create/update | Transform payload → POST to av-lms `/api/sync/user` |
| Cert expiry reminders | n8n cron, daily | Query av-lms `/api/reports/certifications` → fan out personalized emails 30/14/7/1 days before expiry |
| Recurring training reminders | n8n cron, daily | Query av-lms `/api/reports/recurring-due` → email users + supervisor |
| Failed-quiz escalation | av-lms webhook on `quiz.attempt_failed` with attempt count = max | Slack QA Manager + create TW-OPS task + flag user record |
| Audit-prep package | Manual trigger or quarterly cron | Query av-lms + TW-OPS + supplier list → assemble bundle → email QA Manager |
| Cross-system status push | av-lms webhook on `certificate.issued` | Mirror cert metadata to TW-OPS employee profile |

### What n8n does NOT do

Hard boundary — these stay inside av-lms with full audit trail:
- Quiz grading and completion calculation
- Certificate signing (Ed25519 keypair never leaves av-lms)
- `audit_log` writes (append-only with hash chain, in av-lms Postgres only)
- Auth / login flow
- Anything in the synchronous user request path

n8n can **read** from av-lms (via API) and **receive events** from av-lms (via webhooks), but n8n must never **write to** av-lms's authoritative compliance tables. Write paths go through av-lms's own API endpoints with proper auth, validation, and audit logging.

### Event contract: av-lms → n8n

av-lms emits events to a single n8n webhook endpoint (configured via env var `N8N_WEBHOOK_URL`). Each event:

```typescript
type AvLmsEvent = {
  // Stable event identifier — same correlation ID lands in av-lms audit_log
  correlationId: string;
  // The event type — see audit-log.ts auditEventTypeEnum for the closed set
  type: AuditEventType;
  // ISO 8601 timestamp, server time
  occurredAt: string;
  // Entity reference
  entityType: string;
  entityId: string;
  // Compact payload (NOT the full audit log row — n8n re-queries av-lms if it
  // needs more detail, keeping events small and immutable)
  payload: Record<string, unknown>;
  // HMAC-SHA256 over the canonical JSON of the above, keyed by N8N_WEBHOOK_SECRET
  signature: string;
};
```

Events emitted (initial set; expand per workflow needs):
- `user.created` — payload: `{userId, email, firstName, lastName, department}`
- `user.role_granted` — payload: `{userId, role, grantedBy}`
- `certification.issued` — payload: `{userId, kind, expiresAt}`
- `certification.expiring_soon` — payload: `{userId, kind, expiresAt, daysRemaining}` (emitted by daily cron)
- `quiz.attempt_failed` — payload: `{userId, quizId, attemptNumber, score, isMaxAttempts}`
- `completion.recorded` — payload: `{userId, courseId, completedAt}`
- `completion.reset_recurring` — payload: `{userId, courseId, originalCompletedAt}`
- `certificate.issued` — payload: `{userId, courseId, certificateId, verificationCode, validUntil}`

### Reliability

- av-lms event emitter has retry-with-exponential-backoff (3 attempts, 1s/4s/16s) backed by BullMQ
- If all retries fail, event lands in a `dead_letter_events` table in av-lms Postgres for manual replay
- n8n workflows must be idempotent on `correlationId` (n8n's built-in dedup or explicit check)
- HMAC signature verification on n8n side prevents event spoofing

### Operational

- n8n instance: existing TurbineWorks deployment (not part of av-lms's Railway project)
- Network: av-lms → n8n is HTTPS over public internet; n8n → av-lms API uses service token (`N8N_SERVICE_TOKEN` env var in av-lms; `Authorization: Bearer <token>` header from n8n)
- Monitoring: n8n's own execution log + Sentry breadcrumbs on av-lms emit side
- Workflow source-of-truth: n8n workflows exported to JSON and committed to a sibling repo (e.g., `av-lms-workflows`) for review and CI deployment

### Consequences

- ✅ Compliance logic stays in av-lms with full audit trail
- ✅ Cross-system orchestration is visual and easy to evolve
- ✅ TW-OPS doesn't need to know av-lms's API surface in detail — n8n is the translator
- ✅ Notification logic (the most-changed part) lives outside the app's deploy cycle
- ⚠️ Adds a third moving part (after av-lms + TW-OPS) to monitor
- ⚠️ HMAC signatures + correlation IDs add a small implementation burden in av-lms emit code

---

## ADR-0003 — v1 defaults: MFA, jobs runtime, cert signatures

**Status:** Accepted (2026-05-24).
**Context:** Three open questions from the scope needed v1 decisions before Phase 0 cuts code.

### 3a — MFA scope

**Decision:** **Mandatory** for `admin`, `qa_manager`, and `auditor` roles. **Optional** (recommended) for `employee`. **Mandatory** for `trainer` if implemented.

Rationale: privileged accounts have access to grade overrides, completion records, and audit logs — these need second-factor protection. Employee accounts are read-mostly with attestation-based writes (taking a quiz, submitting an assignment); MFA there adds friction without proportional risk reduction. Auditor accounts get MFA mandatory because they're externally-issued and need to be defensible at audit time.

Implementation: TOTP (RFC 6238) as primary, backup codes as recovery. Better Auth supports this natively. WebAuthn/passkey support deferred to post-v1 unless customer demand emerges.

### 3b — Background jobs runtime

**Decision:** **BullMQ on Railway Redis**, hosted as a dedicated Railway worker service.

Rationale: keeps everything in one Railway project (one billing relationship, one deploy story, one monitoring panel). BullMQ is mature, well-documented, has TypeScript bindings, and supports the job patterns we need (cron-scheduled recurring reset, queued cert generation, retry-with-backoff for failed webhooks to TW-OPS). Inngest would add an external vendor and dashboard for marginal benefit at our scale.

Implementation:
- Single `worker` service in Railway running BullMQ workers.
- Railway Redis as the queue backend (~$5-10/mo).
- Job types: `recurring_reset.daily`, `certificate.generate`, `notification.email`, `webhook.outbound`, `audit_log.verify_chain`.
- Each job is idempotent; failed jobs go to a dead-letter queue with structured error logging to Sentry.

Reservation: if BullMQ proves operationally painful (e.g., Redis memory issues at scale), Inngest is a clean migration path — same job-handler signatures, just swap the queue abstraction.

### 3c — Certificate cryptographic signatures

**Decision:** **Yes — Ed25519 signatures on every issued certificate at v1.**

Rationale: certs are audit-defensive evidence. The 21 CFR Part 11 framework explicitly contemplates electronic signatures with cryptographic integrity. A cert with a signature is not just "an issued PDF" but "an issued PDF whose contents are cryptographically attested by a known key, verifiable on the public verification page." This is the strongest possible defense at audit time. The implementation effort is small (Ed25519 is built into Node.js crypto), and it puts us in a position to defend any cert against forgery claims.

Implementation:
- Server-side keypair generated at deploy time; private key stored as a Railway environment variable (not committed); public key published at `https://av-lms.turbineworks.com/.well-known/cert-pubkey.pem` for verification.
- On cert issuance: serialize cert contents to canonical JSON, sign with Ed25519, store signature alongside the certificate record in DB.
- On cert PDF generation: embed signature value in PDF (visible as a "Digitally signed" line below the verification code; alternatively as a PDF metadata field).
- On verification: fetch cert record by code, recompute canonical JSON, verify signature against the published public key. If signature does not verify, surface as "Tampered certificate — invalid signature."
- Key rotation: handled via a versioned `cert_signing_keys` table. Old certs verify against the key version they were signed with. Future-proof.

**Consequences:**
- ✅ Compliance posture is strongest possible.
- ✅ Tamper-evident certificates that survive Adobe / PDF tooling re-saves (signature in the canonical JSON, not just embedded in PDF).
- ⚠️ Adds ~50 lines of crypto code + a `cert_signing_keys` table. Acceptable.
- ⚠️ Private key must be properly secured in Railway env vars and rotated periodically. Document in runbooks.

---

## Table of Contents

1. Executive Summary
2. Background and Strategic Context
3. Goals and Non-Goals
4. Compliance Landscape — Deep Dive
5. Data Integrity Principles (ALCOA+)
6. System Architecture
7. Database Schema
8. Feature Specifications
9. Security Model
10. Operational Concerns
11. Build Phases and Acceptance Criteria
12. Migration Strategy
13. Open Questions
14. Cost and Operational Estimate
15. Risks and Mitigations
16. Appendix A — Regulatory Citation Index
17. Appendix B — Glossary of Internal Acronyms

---

## 1. Executive Summary

### 1.1 What we are building

A custom, single-tenant, audit-grade Learning Management System (LMS) for TurbineWorks called **TurbineWorks University** (TWU), replacing the existing Moodle 4.5 LTS deployment at `learn.turbineworks.com`. The new system is purpose-built around the actual training obligations of an ASA-100-accredited aviation parts distributor, the operational realities of a 2-person company scaling toward an initial accreditation audit, and the data-integrity expectations that auditors, DoD prime contractors, and product-liability insurers will eventually apply to TurbineWorks' training records.

The system delivers eight Initial Training modules (TWF4-1 through TWF4-8), a Cumulative Final Exam, ongoing Recurring Training on a six-month cadence (more rigorous than ASA-100's annual minimum), a Reference Library, engine-parts-specific technical training (CFM56 first, then CF6/V2500/PW4000), and an Operations track for customer-facing staff. Every consumption event — viewing a lesson, attempting a quiz, completing a course, issuing a certificate, resetting recurring training — is captured in an append-only, hash-chained audit log designed to satisfy a 21 CFR Part 11–style integrity standard even though the parts-distribution industry is not formally subject to Part 11.

### 1.2 Why we are doing this

The forked Moodle has consumed weeks of engineering effort to keep operational. Capability mismatches, transaction-state corruption on plugin upgrades, theme configuration drift, and the inability to deploy `local_twu` cleanly across Railway redeploys have made the platform unstable in exactly the way it cannot afford to be — the platform that stores legally-required training evidence for a company preparing for an FAA-recognized accreditation audit. Every operational hour spent debugging Moodle internals is an hour not spent on TurbineWorks' core ASA-100 readiness. A purpose-built system built on a modern, well-understood stack (Next.js + Postgres + Drizzle) will be smaller, more legible, more reliable, and faster to evolve than continuing to wrestle a 700-table general-purpose LMS into compliance shape.

The content is trivially portable. It lives in clean PHP arrays (`content.php`, `quizzes.php`, `glossary.php`) totaling ~14,450 lines of authored material that map directly to JSON or TypeScript fixtures. The orchestration patterns the Moodle plugin demonstrates — courses keyed by `idnumber` (`TWF4-1` … `TWF4-8`), cohort assignments by role and certification, 6-month recurring resets via scheduled task, a public verification page with rich status — all translate to a tighter, type-checked implementation.

### 1.3 Success criteria

Cutover is successful when, in priority order:

1. **All authoritative training records from the current Moodle deployment have been migrated to the new system** with attribution preserved (who completed what, when, what version of the content). In practice the current Moodle holds only the admin account so this is a near-empty migration — but the migration script must exist, be tested, and produce auditable output.
2. **An external auditor, given read access to the new system, can answer the question "Show me who is currently certified to handle hazmat air shipments, with what training, completed on what date, expiring when" in under two minutes** via the Reports module without engineering involvement.
3. **A third party holding a printed TurbineWorks training certificate can visit `learn.turbineworks.com/verify/{code}` and see the certificate's current status** (VALID / RECURRING DUE / EXPIRED / REVOKED) along with a cryptographic verification of the certificate signature.
4. **Six-month recurring training resets execute automatically each night** with email notification to affected users and an audit-log entry per reset.
5. **The audit log is append-only, hash-chained, and a tampered entry is detectable in O(1) verification per entry** plus O(n) chain traversal.
6. **The system passes a WCAG 2.1 AA self-assessment** on lesson viewing, quiz-taking, and certificate-verification flows.
7. **All admin actions that materially affect training records require MFA** (re-authentication or step-up authentication).
8. **The current Moodle can be shut down** without loss of records, without breaking outstanding certificate verification links, and without retraining the QA Manager on a foreign system. The certificate verification URL format and codes are preserved across the cutover.

### 1.4 Top-line tech stack

| Layer | Choice | Why (one sentence) |
| --- | --- | --- |
| Application framework | Next.js 15 (App Router), TypeScript strict | Mature SSR/RSC story, server actions for mutations, broad hiring market |
| UI | shadcn/ui + Tailwind v4 | Headless components owned in repo, no upstream lock-in, consistent design tokens |
| Database | Postgres 16 | Boring, transactional, supports RLS for tenant isolation, JSONB for snapshots |
| ORM | Drizzle ORM | TypeScript-first, generates SQL that is auditable, no runtime DSL overhead |
| Authentication | Better Auth (self-hosted) | Sessions in own DB, no external dependency, replaces deprecated Lucia and aging NextAuth |
| Background jobs | Inngest | Step-based durable execution, serverless-friendly, native cron + event handlers |
| Email | Resend | Developer-friendly, React Email templates, deliverability good enough for ~50 users |
| PDF | `@react-pdf/renderer` for certs; Puppeteer rejected on bundle size and cold-start | Deterministic, fast, ships clean in Vercel/Railway functions |
| File storage | Cloudflare R2 (S3-compatible) | Egress-free; cheap for low-volume cert PDF archives and assignment uploads |
| Hosting | Railway (app + Postgres co-located) | Container model, integrated Postgres, predictable cost, no per-seat tax |
| Email + outbound DNS | Cloudflare DNS, Resend domain | Already managed in Cloudflare for `turbineworks.com` |
| Observability | Sentry (errors) + Axiom (logs) | Sentry is industry standard; Axiom is cheap structured log search at this scale |
| Search | Postgres full-text (tsvector) | One backend, indexed at write time, no Algolia/Meilisearch fee for ~10k rows |
| Cryptography | Node.js `crypto` (Ed25519) | Standard library; certificate signing keys live in env / managed secrets |

### 1.5 Build effort estimate

Working at a steady single-engineer pace with QA Manager review on content and acceptance criteria, the build is **six phases of ~1 week each**, totaling **5–7 calendar weeks of focused work** (Section 11). The honest upper bound including accessibility audit, security audit, content review, and parallel-run cutover is **8 weeks**. This compares to the current open-ended Moodle debugging which has consumed roughly that much engineering time over the past months without producing a stable platform.

### 1.6 Operational cost estimate

| Item | Monthly cost | Notes |
| --- | --- | --- |
| Railway (Pro + Postgres) | $20 + ~$15 usage | Application + DB in same project |
| Resend (Pro) | $20 | 50K emails/month; far above need |
| Sentry (Team) | $26 | Optional at start; free tier covers 5K errors |
| Axiom (Personal Pro) | $25 | Structured logs with 30-day retention |
| Cloudflare R2 | <$5 | Cert PDF + assignment storage, 10 GB |
| Inngest (Hobby) | $0 | Free tier sufficient at this user count |
| Domain + DNS | already covered | `turbineworks.com` zone |
| **Total** | **~$85–110/mo** | All-in for a fully-monitored production system |

The current Moodle on Railway is consuming roughly the same `$35-50/mo` with worse observability and ongoing instability. The marginal cost of the new system is therefore essentially zero in operating dollars, with significant gains in engineering hours saved.

---

## 2. Background and Strategic Context

### 2.1 The company

TurbineWorks is an aviation parts distributor based in the United States, specializing in aircraft engine parts and components — currently focused on the CFM56 family, with planned expansion into CF6, V2500, and PW4000. The company has two employees: a managing principal and a junior operations staffer. The business is pre-accreditation but on a defined glide path to an initial ASA-100 audit and FAA AC 00-56B accreditation listing. The training program documented and partially deployed in the existing Moodle plugin is the substantive evidence that TurbineWorks satisfies the training-system requirements of ASA-100 §3 and AC 00-56B paragraph 3 (training elements within the quality system).

The company also touches several adjacent regulatory regimes by virtue of who its customers are and what it ships:

- **DOT Hazmat regulations (49 CFR Parts 100–185)** — engine components frequently contain residual fuel, certain components are themselves classified hazmat (lithium batteries in BMUs, magnesium castings in some legacy parts), and shipping these requires trained-and-certified personnel.
- **IATA Dangerous Goods Regulations** — when shipments cross by air (the typical mode for high-value engine parts), the shipper's declaration must be prepared by IATA-DGR-trained personnel.
- **ANSI/ESD S20.20** — electronic engine controls (FADEC/EEC) and avionics-adjacent components are ESD-sensitive; an ESD control program with documented training is increasingly expected by customers.
- **DFARS 252.246-7007 / 7008** — any flow-down from defense prime contractors (Lockheed Martin, Raytheon, Boeing Defense) brings counterfeit-parts-prevention obligations.
- **NIST 800-171** — if TurbineWorks accepts technical drawings or specifications marked CUI from a DoD customer, the entire IT environment that touches those records, including training records that prove personnel are cleared to handle CUI, falls under 800-171's 97 controls.

The training LMS is therefore not a nice-to-have HR system. It is a piece of compliance infrastructure whose reliability and auditability is on the critical path to operating the business legally.

### 2.2 Current state — why Moodle is being abandoned

The forked Moodle deployment at `learn.turbineworks.com` was built as the fastest path to a substantive training program. Moodle is mature, free, well-documented, and was the right initial choice. In practice the deployment has been blocked by:

- **Capability mismatches across deploys.** The `local_twu` plugin defines a `local/twu:viewreports` capability assigned to the `manager` archetype. On Railway redeploys, capability cache invalidation has not been reliable; the QA Manager has lost access to the reports interface multiple times despite a valid role assignment, requiring SQL surgery on `mdl_role_capabilities`.
- **Plugin version drift on deploy.** Bumping `$plugin->version` in `version.php` triggers Moodle's plugin-installation transaction. Railway's deploy cycle has caused these transactions to enter abandoned-but-not-rolled-back states, leaving `mdl_config_plugins` in an inconsistent state that requires manual cleanup.
- **Theme config resets.** Custom theme overrides for the verification page styling have been silently reverted on platform upgrades.
- **Bootstrap idempotency.** The CLI bootstrap script (`local_twu/cli/bootstrap.php`) seeds courses, cohorts, lessons, and quizzes from the content PHP files. Idempotency is implemented by name-based lookup; this has produced duplicate lessons when names have been edited mid-deploy.
- **PHP runtime and Postgres extension surface area.** Each Railway image rebuild reintroduces the question of which PHP extensions are present, which is the wrong question for a Node-based engineering organization to be answering on a recurring basis.
- **Auditability of the audit log.** Moodle's `mdl_logstore_standard_log` is a perfectly reasonable internal log but is not designed for the externally-defensible cryptographic-integrity standard appropriate to training records under a 21 CFR Part 11–inspired regime. Tampering with it leaves no signal.
- **Velocity ceiling.** Adding a feature — for example, a "training-needs analysis" report or a structured master-cert variant — requires becoming temporarily a Moodle plugin developer, which is not a transferable skill investment.

The decision to migrate is not a rejection of Moodle as a platform. It is a recognition that Moodle's design constraints, optimized for a university or corporate L&D environment with hundreds of courses and thousands of users, are paying for capabilities TurbineWorks does not need and cannot maintain.

### 2.3 What carries forward

The substantive product of the Moodle work — the content, the structure, the cohort model, the cadence — carries forward intact and is in fact already in a portable form.

- **Lesson content (~200,000 words).** Lives in `local_twu/content/content.php` as PHP arrays. Each lesson is a heredoc HTML string with stable `name` and `intro` fields. Migration is mechanical: parse the PHP arrays, emit JSON or TypeScript fixtures, seed the new database.
- **Quiz banks (~66 questions across 8 modules + cumulative final).** Live in `local_twu/content/quizzes.php`. Each question has `name`, `question` (HTML), `options`, `correct` (0-based index), `explain`. Migration is direct.
- **Glossary (98 entries).** Lives in `local_twu/content/glossary.php`. Each entry has `concept`, `definition`. Migration is direct.
- **Course catalog structure.** The course id-numbers (`TWF4-1` through `TWF4-8`, `TWF4-CFM56`, `TWF4-EngineDocs`, etc.) are preserved exactly so that printed certificates referencing these idnumbers remain valid.
- **Cohort model.** All Employees / Role: Warehouse, QA, Shipping, Management / Certification: DOT Hazmat, IATA DGR, ESD / Authority: SUP Reporter, DGD Signer — re-encoded as enums in the new schema.
- **Custom profile fields.** Department, job title, employee number, hire date, supervisor, DOT Hazmat expiry, IATA DGR expiry, ESD cert expiry, ASA cert ID — promoted to first-class columns on the `users` table rather than EAV-style profile-field rows.
- **6-month recurring training cadence.** Implemented in `classes/task/recurring_reset.php`. Reimplemented as an Inngest scheduled function with the same 180-day threshold against course completion timestamps.
- **Public verification page.** Currently at `/local/twu/verify.php?code=X`; new system serves `/verify/{code}` with status-rich output (VALID / RECURRING DUE / EXPIRED / REVOKED) plus a cryptographic verification of the cert signature.
- **Master cert variant for the cumulative final.** Currently emitted via mod_customcert with a styled background. Reimplemented as a `master: true` flag on the issued certificate record with a distinct PDF template.

### 2.4 Relationship to TW-OPS

TurbineWorks is concurrently building **TW-OPS**, an internal operations platform that owns the Quality Manual (QAM), controlled SOPs, controlled forms, supplier qualification records, receiving inspection records, and the document-control workflow generally. The relationship between TWU and TW-OPS is a live design question:

- **Shared identity.** TWU and TW-OPS must share user accounts. A receiving inspector logging into TW-OPS to sign off on a receiving inspection record should not have a separate TWU account. Practically this means TWU's auth system is either (a) a thin frontend over TW-OPS's identity service, (b) federated to a common identity provider that both services use, or (c) a single application monorepo where TWU is one route group within TW-OPS.
- **Cross-linking content.** Training material in TWU should be able to link to controlled SOPs in TW-OPS by stable identifier — for example, Lesson 2.3 references "see SOP-QA-002 §4 for the documentation review checklist," and the link should resolve to the current revision of SOP-QA-002 in TW-OPS without manual maintenance when SOP-QA-002 is reissued.
- **Audit aggregation.** An auditor performing an ASA-100 surveillance audit needs to see both training records (TWU) and operational records (TW-OPS) and to verify that the people performing recorded operations were trained as of the date of the operation. This cross-reference is conceptually a join across two systems; in practice it is best served by either a shared database (preferred if TW-OPS is also Postgres) or a periodic data export from TWU to TW-OPS.

This document treats TWU as a standalone service with well-defined integration points, but the open question of whether TWU is a separate app or a route group within TW-OPS is tracked in Section 13 (Open Questions) and should be resolved before Phase 0 (Foundation) work begins. The schema and authentication choices in this scope are compatible with either outcome.

---

## 3. Goals and Non-Goals

### 3.1 Goals (what the system must do)

The system must:

1. **Deliver structured training content** — courses, modules, lessons (HTML pages), quizzes (multiple choice with explanations), assignments (text submission with grading), glossary (auto-linked from lesson text).
2. **Track completion in audit-defensible form** — every lesson view, quiz attempt, and assignment grade is captured with attribution, immutable timestamp, and a chain-of-custody hash.
3. **Issue and verify certificates** — PDF certificates with cryptographic signatures, public verification page, master certificate variant for the cumulative final, revocation support.
4. **Implement 6-month recurring training** — automatic detection of completions older than 180 days, automated reset of progress, email notification, audit-log entry per reset, full preservation of prior completion history.
5. **Manage compliance-relevant user attributes** — department, job title, employee number, hire date, supervisor, DOT hazmat / IATA DGR / ESD expiry dates, ASA cert ID — and surface these in expiry-tracker reports.
6. **Group users into cohorts** — by role, by required certification, by authority — and assign training to cohorts not individuals.
7. **Provide first-class reports** — completion roster (matrix), recurring-due tracker, certification expiry roster, failed quiz attempts, training hours by user/department, audit-log query interface, all exportable to CSV/Excel.
8. **Provide an admin UI** — user CRUD, cohort CRUD, content CRUD, reports access, audit-log viewer, system settings.
9. **Authenticate with strong defaults** — password + MFA mandatory for admin / QA Manager / auditor roles; password + optional MFA for employee role; session timeout configurable; brute-force protection.
10. **Be accessible** — WCAG 2.1 AA on lesson viewing, quiz-taking, and certificate verification flows.
11. **Be observable** — every error reported to Sentry, every structured log queryable in Axiom, every state-changing action recorded in the audit log.
12. **Be backed up** — Postgres point-in-time recovery, weekly logical dumps to encrypted off-platform storage, documented restore drill quarterly.

### 3.2 Non-goals (explicitly out of scope)

The system will not, at least in v1:

- **Support social / discussion features.** No forums beyond perhaps a single moderated announcement channel. Comment sections, peer messaging, threaded discussions are out.
- **Be a native mobile app.** The web UI is responsive and works on mobile browsers. Building React Native is deferred indefinitely.
- **Integrate with external HR systems.** No ADP / BambooHR / Workday sync. User records originate from TWU or from TW-OPS via shared identity, not from an HRIS.
- **Be multi-tenant.** TWU exists for TurbineWorks. Multi-tenancy adds RLS complexity that delivers no value at the current org size. The schema is designed in a way that does not preclude later multi-tenancy (Section 7.5).
- **Support SCORM or xAPI.** These are industry-standard content interchange formats whose primary value is portability of content across vendor LMSes. TurbineWorks owns its content (PHP arrays → TS fixtures) and is the only consumer. SCORM/xAPI compliance would be ~2 weeks of work delivering negative ROI.
- **Be multi-language.** All training is in U.S. English; the FAA and ASA operate in English; TurbineWorks staff is English-speaking. i18n infrastructure is deferred.
- **Replicate Moodle's gradebook complexity.** Quizzes have a simple pass/fail threshold (80%). Assignments are graded out of 100. No weighted gradebooks, no category weights, no extra-credit columns.
- **Implement video-conferencing or live training.** Live training events are tracked manually as completed-by-instructor records, not orchestrated by TWU.
- **Implement payment / commerce.** TWU is not selling courses externally.
- **Implement an end-user-facing course catalog discovery experience.** Users are assigned to cohorts, cohorts have required courses, and the dashboard shows the user's assigned courses. There is no "browse courses" tab.

---

## 4. Compliance Landscape — Deep Dive

This section documents each regulatory and industry-standard regime that bears on the LMS, with specific paragraph citations, retention requirements, and the LMS implementation implications for each. Where a regime is not legally binding on TurbineWorks today, the recommendation is to design to the more stringent of (a) the binding regime, (b) the regime TurbineWorks expects to come under within 24 months, and (c) the regime the most demanding customer is likely to flow down.

### 4.1 ASA-100 (Quality Assurance Standard for Aircraft Parts Distributors)

**Authority:** Aviation Suppliers Association, current revision 5.0 Update 1 (as of 2026).
**Binding on TurbineWorks:** Yes — once accredited. Currently advisory as TurbineWorks prepares.

**Training-related requirements:**
- The distributor must operate a documented training program covering the elements of the quality system that fall within the distributor's scope (per AC 00-56B paragraph 3, which ASA-100 implements).
- Training must be recurrent. ASA-100 does not specify a minimum frequency but auditor-published guidance and industry practice converge on annual minimum, with more rigorous cadences (semi-annual, as TurbineWorks operates) considered favorably.
- On-the-job training (OJT) records are explicitly required. Missing OJT records or failing to record the length of instruction is cited by ASA as a frequent "minor" non-conformance in audit findings. ([Sofema](https://sofemaaviation.com/blog/asa-100-accreditation-for-parts-distributors), [simpleQuE](https://www.simpleque.com/standards/asa-100-certification/))
- Records of training must be retrievable for audit. ASA-100 establishes a **seven-year minimum document retention** for accredited distributors. ([ASA Web Log](https://aviation-suppliers.org/2016/03/02/832/)) This is the primary numerical anchor for the LMS retention policy.

**LMS implementation implications:**

| Requirement | TWU Implementation |
| --- | --- |
| Recurrent training tracked | 6-month auto-reset via Inngest scheduled function; reset history preserved indefinitely |
| OJT records | First-class `training_records` table separate from `course_completions`, supports manual OJT entries with `delivered_by`, `delivered_to`, `duration_minutes`, `topic`, `notes` |
| 7-year retention minimum | Default retention = forever. No automated purge logic in v1. Documented in System Settings. |
| Retrievable for audit | Reports module + audit-log query interface; export to CSV/Excel for inclusion in audit packages |
| Records identify who did what | Audit log captures actor user id, IP, user agent, session id; certificates carry recipient name, course, dates, and crypto signature |

### 4.2 FAA AC 00-56B (Voluntary Industry Distributor Accreditation Program)

**Authority:** FAA Advisory Circular 00-56B (with Change 1).
**Binding on TurbineWorks:** No (advisory circular), but compliance is the gateway to ASA-100 accreditation, which is the operational goal.

**Training-related requirements (paragraph 3, Quality System Elements):**
- "A system for training the distributor's personnel is required to ensure that the distributor properly executes the quality system, including the elements of the quality system that fall within the distributor's scope of operations." ([FAA AC 00-56B](https://www.faa.gov/documentlibrary/media/advisory_circular/ac_00-56b.pdf))
- AC 00-56B does not itself specify training content, frequency, or recordkeeping — it delegates these to the accreditation organization's quality standard. For TurbineWorks (accredited via ASA), this means ASA-100 governs.
- Accreditation is granted for **36 months** with at least one **surveillance audit** during the term. The LMS must therefore be in continuously presentable state, not just at initial-audit time.

**LMS implementation implications:**
- The system must be **continuously available** to support surveillance audits with no advance notice. SLO target: 99.5% monthly uptime, which is what Railway's container model provides given dual-region database failover.
- The system must support **immediate auditor read access** — a guest auditor account with read-only access to reports, course content, and the audit log, provisioned by the QA Manager.

### 4.3 14 CFR Part 21, Part 43, Part 145 (regulatory framework)

**Part 21** governs certification of products and articles — relevant to TurbineWorks indirectly because the parts TurbineWorks distributes were originally produced under Part 21 (PMA, TSO, production certificate) or installed under Part 43.
**Part 43** governs maintenance records; relevant to TurbineWorks because the records that travel with a part (8130-3, work scope, last-removal record) originate under Part 43.
**Part 145** governs repair stations; TurbineWorks is not a repair station but its customers often are, and Part 145 §145.219 mandates **2-year retention of repair records from approval-for-return-to-service date**. ([eCFR 145.219](https://www.ecfr.gov/current/title-14/chapter-I/subchapter-H/part-145/subpart-E/section-145.219))

**LMS implementation implications:**
- The LMS itself is not directly subject to Parts 21/43/145, but the **training content** must accurately reflect these regulations, and the lessons in `content.php` carry citation chains (see Lesson 3.1 reference list). The LMS must allow lesson revisions to be **versioned**, with prior versions retained, so that an auditor inspecting a 2023 training completion can see the content the trainee actually completed.
- This drives a `lesson_versions` table separate from `lessons`; completions reference the specific version completed.

### 4.4 21 CFR Part 11 (FDA — Electronic Records and Electronic Signatures)

**Authority:** Food and Drug Administration; codified at 21 CFR Part 11.
**Binding on TurbineWorks:** No. This is FDA jurisdiction (pharma, medical devices). The FAA has not published an equivalent rule for electronic records in aviation parts distribution.
**Why we design to it anyway:** Part 11 is the most rigorous and well-developed electronic-record integrity standard in U.S. federal regulation. Aviation auditors and DoD prime contractors increasingly cite Part 11 principles when evaluating supplier electronic systems even where Part 11 is not formally binding. Designing to Part 11 today is the cheapest insurance against future regulatory drift.

**Key provisions and TWU implementation:**

| 21 CFR Part 11 Provision | Plain-English Requirement | TWU Implementation |
| --- | --- | --- |
| §11.10(a) — Validation | Systems handling electronic records must be validated for accuracy, reliability, consistent performance | Tests for every audit-log-emitting code path; documented test plan retained with code; cutover validation per Section 11 |
| §11.10(b) — Record generation | Ability to generate accurate and complete copies of records in human-readable and electronic form | CSV/Excel/PDF export for every report; raw SQL dump on request |
| §11.10(c) — Record protection | Records protected to enable accurate and ready retrieval throughout retention period | Postgres PITR + weekly logical dumps to R2 with envelope encryption; quarterly restore drill |
| §11.10(d) — Access limits | System access limited to authorized individuals | Role-based access with MFA for elevated roles; session timeout; audit log of every authentication and authorization decision |
| §11.10(e) — Audit trail | Secure, computer-generated, time-stamped audit trails that record date and time of operator entries that create, modify, or delete electronic records; record changes shall not obscure previously recorded information; audit trail documentation retained as long as required for subject electronic records and available for agency review and copying ([eCFR Part 11](https://www.ecfr.gov/current/title-21/chapter-I/subchapter-A/part-11)) | `audit_log` table append-only via RLS policy denying UPDATE/DELETE to application role; hash-chained with prior-row hash; entries include actor, IP, user-agent, before/after JSON snapshot; retention indefinite |
| §11.10(f) — Sequence enforcement | Use of operational system checks to enforce permitted sequencing of steps and events | Lesson-completion gating: quiz unlocks after all lessons viewed; certificate issuance gated on course completion; recurring-due users blocked from issuing fresh certs until re-completion |
| §11.10(g) — Authority checks | Ensure only authorized individuals can use the system, electronically sign records, alter records, etc. | Permission checks at every server action; matrix in Section 9.2 |
| §11.10(h) — Device checks | Use of device checks to determine validity of source of data input or operational instruction | Source IP captured in audit log; user-agent captured; admin actions trigger session re-verification |
| §11.10(i) — Personnel qualifications | Documentation of training, experience of system users | The LMS records the QA Manager's own training, including completion of an "LMS administrator orientation" course |
| §11.10(j) — Written policies | Written policies that hold individuals accountable for actions initiated under their electronic signatures | Documented in TurbineWorks IT policy referenced from system settings; reviewed annually |
| §11.10(k) — Documentation control | Appropriate controls over system documentation | Code in git with signed commits; deployment artifacts versioned; documentation in TW-OPS |
| §11.30 — Open systems | Additional controls for open systems | TWU is treated as a closed system (auth-required); public verify page is read-only and crypto-verified |
| §11.50 — Signature manifestations | Signed electronic record contains: printed name, date/time of signing, meaning of signing | When a QA Manager signs off on a training completion override or assignment grade, the signature is recorded with printed name (denormalized at sign-time), ISO 8601 UTC timestamp, and `meaning` field (e.g., "APPROVED — override unmet completion") |
| §11.70 — Signature/record linking | Electronic signatures permanently linked to their respective records to prevent excision, copy, or transfer | Signature record carries a foreign key to the signed record plus a stored hash of the signed record's canonical JSON; tamper changes the hash |
| §11.100 — General requirements | Each electronic signature shall be unique to one individual and shall not be reused | Hard-enforced at DB level: signatures reference `users.id` (immutable, never reused on user deletion) |
| §11.200 — Non-biometric signature components | Signatures based on two distinct identification components (e.g., password + ID) — at least one must be a password the signer reuses for that signing | MFA on every signing action: password + TOTP at signing time, distinct from session auth |
| §11.300 — Controls for passwords | Password aging, uniqueness, loss procedures, transaction unauthorized-use safeguards | Bcrypt with cost 12; password rotation policy 180 days for admin; lost-password requires identity-verification email to a secondary address |

### 4.5 ALCOA+ Data Integrity Principles (See Section 5)

ALCOA+ is the principles-level framework that 21 CFR Part 11 operationalizes. Treated as its own design lens in Section 5.

### 4.6 DOT Hazmat — 49 CFR §172.704 (Training Requirements)

**Authority:** Department of Transportation; codified at 49 CFR §172.704. ([eCFR 172.704](https://www.ecfr.gov/current/title-49/subtitle-B/chapter-I/subchapter-C/part-172/subpart-H/section-172.704))
**Binding on TurbineWorks:** Yes, for any employee whose function affects the safety of hazmat transportation, including selecting hazardous-materials packaging, completing shipping papers, or handling the package. In practice every TurbineWorks employee who interacts with outbound shipments is a "hazmat employee" under the DOT definition.

**Training components required (per §172.704(a)):**

1. **General awareness/familiarization training** — recognize and identify hazardous materials consistent with hazard communication standards.
2. **Function-specific training** — applicable to the employee's job function.
3. **Safety training** — emergency response, measures to protect from hazards, methods to avoid accidents.
4. **Security awareness training** — security risks in hazmat transportation, how to recognize and respond to possible security threats.
5. **In-depth security training** — for employees of shippers/carriers required to have a security plan (HM-232).

**Frequency:**
- **Initial training** prior to performing function (or within 90 days under supervision per §172.704(c)(1)).
- **Recurrent training at least once every 3 years.** ([eCFR](https://www.ecfr.gov/current/title-49/subtitle-B/chapter-I/subchapter-C/part-172/subpart-H/section-172.704))
- In-depth security training (paragraph (a)(5)) recurrent at least every 3 years, or within 90 days of a security plan revision.

**Records retention:**
- A record of current training, inclusive of preceding 3 years (i.e., the prior cycle plus the current cycle), kept for as long as the employee performs functions, plus 90 days thereafter.
- Records must include: employee's name; most recent training completion date; description, copy, or location of training materials used to meet the requirement; name and address of trainer; certification that employee was trained and tested.

**LMS implementation implications:**
- DOT Hazmat training is a **certification** track in TWU (separate from the ASA-100 Initial Training track). A user with the DOT Hazmat certification cohort membership sees the DOT Hazmat course set. Completion records include all five required content components (general awareness, function-specific, safety, security awareness, in-depth security).
- The LMS stores the **trainer's name and address** at the time of training. For self-paced LMS-delivered training, trainer = "TurbineWorks University, [QA Manager name], [TurbineWorks address]"; for in-person or vendor-delivered training, the QA Manager enters trainer information manually when recording the completion.
- The LMS issues a **DOT Hazmat training certificate** valid for 3 years; the recurring engine flags it as RECURRING DUE at 90 days before expiry; the user (and QA Manager) receive escalating email reminders at 90, 60, 30, 14, and 7 days; the certificate enters EXPIRED state at the 3-year mark.
- The "name and address of trainer" plus "certification that employee was trained and tested" plus "description of training materials used" are stored as denormalized fields on the certificate record so that the cert PDF is self-contained for audit even if the underlying lesson content is later revised.

### 4.7 IATA Dangerous Goods Regulations (DGR)

**Authority:** International Air Transport Association.
**Binding on TurbineWorks:** Yes, for any air shipment of dangerous goods. Aviation customers expect shippers' declarations prepared by IATA-DGR-certified personnel; ground-shipping-only operation is not realistic for TurbineWorks given the customer base.

**Training requirements:**
- Initial training for personnel preparing, accepting, or processing dangerous goods consignments.
- **Recurrent training every 24 months.** ([IATA](https://www.iata.org/en/training/courses/dgr-acceptance-recurrent/dgc022veen02/en/))
- Certificate valid for 24 months from completion date.
- Current edition (2026): 67th Edition of the IATA DGR.

**LMS implementation implications:**
- IATA DGR training is typically **vendor-delivered** (IATA Training Center, regional partner, or in-house IATA-instructor-led). TWU's role is to **record** these completions rather than to deliver them. The QA Manager enters a completion record into TWU with: course (IATA DGR Acceptance / Processing / Preparing — Recurrent), provider, instructor name, instructor IATA credential reference, completion date, expiry (auto-computed = +24 months), uploaded scan of the IATA-issued certificate.
- The LMS issues its **own internal certificate** mirroring the externally-issued one, so that the TurbineWorks employee directory and verification page show "IATA DGR — Preparing — current; expires 2027-XX-XX" consistently.
- Reminder cadence: 90, 60, 30, 14, 7 days before expiry.

### 4.8 ANSI/ESD S20.20

**Authority:** ESD Association / ANSI; current revision ANSI/ESD S20.20-2021. ([ANSI Blog](https://blog.ansi.org/ansi/ansi-esd-s20-20-2021-protection-electronic-parts/))
**Binding on TurbineWorks:** Yes if TurbineWorks operates an ESD-protected area and ships ESD-sensitive parts (FADEC/EEC components). Increasingly expected by aerospace customers.

**Training requirements:**
- Training plan must be established to ensure all personnel handling or coming into contact with ESDS items are provided with initial and recurrent ESD awareness and prevention training. Initial training before personnel handle ESDS items.
- Type and frequency defined in the training plan (organization-defined).
- Training plan includes methods to verify trainee comprehension.
- **Personnel training records must be maintained** and the storage location documented.

**LMS implementation implications:**
- ESD training is delivered in TWU as Module 7 of the Initial Training program, plus a standalone ESD-only refresher for the ESD-certified cohort.
- Comprehension verification = Module 7 quiz with 80% passing threshold.
- TurbineWorks's training plan documents the recurrent cadence as 6 months (per existing program rigor); the recurring engine handles this automatically.
- The "storage location" for ESD training records is documented in System Settings as "TurbineWorks University, learn.turbineworks.com, Postgres database `twu_prod`, with off-platform encrypted backups in Cloudflare R2".

### 4.9 DFARS 252.246-7007 / 252.246-7008 (Counterfeit Electronic Parts)

**Authority:** Defense Federal Acquisition Regulation Supplement.
**Binding on TurbineWorks:** Flow-down clause — applies whenever TurbineWorks sells to a prime contractor whose DoD contract incorporates the clause. ([Acquisition.GOV](https://www.acquisition.gov/dfars/252.246-7007-contractor-counterfeit-electronic-part-detection-and-avoidance-system.))

**Training-relevant requirements (DFARS 252.246-7007(c)):**
- Contractors must maintain a counterfeit electronic part detection and avoidance system that includes, among other elements, **personnel training**.
- Training must be documented.
- Risk-based — must address: inspection and testing, traceability, alerts (GIDEP), control of obsolete parts, reporting and quarantine.

**LMS implementation implications:**
- Module 1 of Initial Training (Unapproved Parts and Counterfeit Materials) covers counterfeit-prevention content meeting DFARS expectations.
- The LMS produces a "DFARS 252.246-7007 Training Evidence" report on demand, listing each employee with their counterfeit-prevention training completion(s), the content version, the pass score, the certificate code.
- Specific lesson content cross-references SAE AS5553 (counterfeit electronic parts) and AS6174 (counterfeit materiel) — the industry standards that DFARS implicitly relies on.

### 4.10 SAE AS5553 / AS6174

**Authority:** SAE International / industry consensus.
**Binding on TurbineWorks:** Voluntary; flowed down from defense and commercial primes increasingly often.

**LMS implementation:** Already covered in Module 1 lesson content; no schema impact beyond the cross-reference list maintained in lesson metadata.

### 4.11 NIST SP 800-171 and CMMC

**Authority:** NIST (800-171), DoD (CMMC).
**Binding on TurbineWorks:** If TurbineWorks handles Controlled Unclassified Information (CUI), 800-171's 97 controls (currently across 14 families in r2; reorganized into 17 in r3) apply. ([NIST SP 800-171](https://csrc.nist.gov/pubs/sp/800/171/r3/final))

**LMS implementation implications (selected controls relevant to TWU):**

| Control Family | Relevant Controls | TWU Implementation |
| --- | --- | --- |
| 3.1 Access Control | 3.1.1, 3.1.2, 3.1.7 (limit access to authorized users; transactions and functions that authorized users are permitted to execute; prevent non-privileged users from executing privileged functions) | RBAC + MFA + audit log of every authorization decision |
| 3.3 Audit and Accountability | 3.3.1, 3.3.2, 3.3.8, 3.3.9 (create and retain audit logs; ensure actions of individual users can be uniquely traced; protect audit information from unauthorized access; limit management of audit logs) | Append-only hash-chained audit log with RLS deny on UPDATE/DELETE for app role; only DBA-equivalent role can read audit log query interface |
| 3.5 Identification and Authentication | 3.5.1, 3.5.3, 3.5.7-3.5.11 (identify users; MFA for privileged accounts; password complexity; obscured feedback of authentication info) | Better Auth + TOTP MFA; password policy enforced server-side; rate-limited login |
| 3.13 System and Communications Protection | 3.13.1, 3.13.8, 3.13.11 (monitor, control, and protect communications; cryptographic mechanisms; FIPS-validated crypto for protecting CUI) | TLS 1.3 only; Ed25519 (acceptable for non-CUI integrity), AES-256-GCM for envelope encryption of backups |
| 3.14 System and Information Integrity | 3.14.1, 3.14.6 (identify and correct flaws; monitor security alerts) | Sentry + dependency scanning (Dependabot/Renovate); patch SLA documented |

The LMS itself is unlikely to **store** CUI directly — training content is not CUI — but it is part of the IT environment that must be in scope for an 800-171 self-assessment if TurbineWorks's other systems handle CUI. The architecture choices in this document (single-tenant, US-hosted, FIPS-acceptable crypto, MFA-by-default-for-elevated-roles) keep the LMS comfortably inside 800-171 boundaries.

### 4.12 General Records Retention — Aviation Industry

| Record Type | Minimum Retention | Source |
| --- | --- | --- |
| ASA-100 distributor records | 7 years | ASA policy |
| FAA Part 145 repair records | 2 years from approval-for-return-to-service | 14 CFR §145.219 |
| Air carrier training records (DOT) | 3 years (domestic), 2 years (international) | DOT |
| Hazmat training records (49 CFR §172.704) | 3 years (current cycle) + duration of employment + 90 days | 49 CFR §172.704(d) |
| ESD training records | Per organization's training plan; typically duration of employment | ANSI/ESD S20.20 |
| Serialized / life-limited part scrap records | 7 years | ASA-100 |
| Product liability exposure | Open-ended | Tort statute of repose, state-dependent |

**TWU retention policy: indefinite by default** for all training-related records. No automated purge in v1. If purge is later required for privacy reasons (employee departure + a data-minimization policy), implementation is a single Inngest job operating on the `users.departure_date + 7y` rule, gated behind QA Manager approval.

---

## 5. Data Integrity Principles (ALCOA+)

ALCOA+ is the FDA-originated data-integrity framework (1990s, formalized through PIC/S, EMA, MHRA) that 21 CFR Part 11 operationalizes. TWU adopts ALCOA+ as its **primary integrity design lens** because it is principles-level (does not over-constrain implementation), well-understood by auditors across regulated industries (aviation auditors increasingly cite it), and maps cleanly onto database and application design.

### 5.1 The nine principles and TWU implementation

**Attributable** — Every record must identify who created it and when.
*TWU*: Every row in every business-relevant table carries `created_at` (`timestamptz`), `created_by` (FK to `users.id`), `updated_at`, `updated_by`. The `audit_log` is the authoritative attribution record; the `created_*`/`updated_*` columns on individual tables are denormalized convenience.

**Legible** — Records must be readable and understandable now and throughout retention.
*TWU*: All text is UTF-8. Lesson content stored as sanitized HTML with a canonical text-extracted version for FTS and accessibility. Snapshots in `audit_log.before_json` / `after_json` use canonical JSON serialization (sorted keys, no trailing zeros, ISO 8601 timestamps) so that an entry written in 2026 is decodable identically in 2036.

**Contemporaneous** — Records made at the time of the activity.
*TWU*: All event timestamps are server-side. Client-supplied timestamps are accepted only as user-context metadata, never as authoritative event times. Database `now()` defaults are at `timestamptz` precision (microsecond) and stored in UTC.

**Original** — First capture (or certified true copy) must be retained.
*TWU*: Audit log entries are write-once. Original lesson content versions are preserved in `lesson_versions`; a completion records the specific `lesson_version_id` consumed. Quiz attempts capture the exact question text and option text as `quiz_responses.snapshot_json`, immune to later question edits.

**Accurate** — Records reflect the true observation.
*TWU*: Server-side validation on every input. Tests covering happy path + edge cases for every audit-emitting code path. CI gate: no merge without passing test suite.

**Complete** — All data, including repeated tries and changes, is retained.
*TWU*: Every quiz attempt is retained (not just the passing one). Every cert issuance, revocation, and re-issuance is retained. Every recurring-reset is retained. Soft delete by `deleted_at` timestamp where deletion is conceptually meaningful; hard delete is reserved for compliance-driven purge only and is itself an audit event.

**Consistent** — Records are in chronological order with date/time stamps.
*TWU*: Audit log carries monotonically increasing `id` (bigserial) plus `timestamptz`. Hash chain links each entry to the prior, making out-of-order insertion detectable. Database has `transaction_timestamp()`-based defaults that prevent backdating.

**Enduring** — Records must persist throughout retention.
*TWU*: Postgres with point-in-time recovery + weekly encrypted dumps to off-platform R2 + quarterly restore drill. Storage redundancy at provider level (Railway managed Postgres) plus off-platform second copy.

**Available** — Records accessible for audit/inspection.
*TWU*: Auditor read-only role; report module; raw CSV export; SQL query interface gated behind QA Manager auth. Indexed for sub-second query response on common audit queries (completions by user, by date range, by course).

### 5.2 ALCOA++ extensions (Traceable, Reliable)

PIC/S PI 041 added **Traceable** (chain-of-custody for derived data) and some practitioners add **Reliable** (system maintained per validated state). Both are subsumed by Part 11 in TWU's implementation.

---

## 6. System Architecture

### 6.1 High-level diagram (described in ASCII)

```
                    [ Public Internet ]
                            |
                            | TLS 1.3
                            v
                  +---------------------+
                  |  Cloudflare DNS +   |
                  |  Cloudflare Proxy   |
                  |  (DDoS, WAF, rate-  |
                  |   limit edge rules) |
                  +----------+----------+
                             |
                             v
              +--------------+--------------+
              | Railway: Next.js app server |
              | (Node 22, Next 15 App Router)|
              |                              |
              |  - SSR pages + RSC           |
              |  - Server actions            |
              |  - Public verify endpoint    |
              |  - REST/RPC for Inngest      |
              +-------+-----------+----------+
                      |           |
        Drizzle (TLS) |           | Inngest SDK (HTTPS)
                      v           v
            +---------+---+   +---+----------------+
            |  Postgres   |   | Inngest Cloud      |
            |  16 (Railway|   | (event broker +    |
            |  managed)   |   |  durable steps)    |
            |             |   |                    |
            | - main      |   | - recurring reset  |
            | - audit_log |   | - expiry reminders |
            |   (RLS deny |   | - assignment      |
            |   UPDATE)   |   |   notify           |
            +------+------+   +---------+----------+
                   |                    |
                   |                    | calls back to
                   |                    | app server via
                   |                    | signed webhook
                   |                    v
                   |          +---------+----------+
                   |          | Resend (transactional |
                   |          |  email)             |
                   |          +--------------------+
                   |
                   | logical dumps weekly + WAL streaming
                   v
            +------+------+
            | Cloudflare  |
            | R2 (cert    |
            | PDFs, DB    |
            | backups,    |
            | assignment  |
            | uploads)    |
            +-------------+

                   Sentry  <-----  errors (browser + server)
                   Axiom   <-----  structured logs (server)
```

### 6.2 Tech stack — detailed choices and rationale

#### 6.2.1 Application framework: Next.js 15 (App Router) + TypeScript strict

Recommended. Next.js gives mature server-side rendering, React Server Components for natural data-fetching co-location, server actions for mutations (preferred over traditional REST for this app's scale), and a vibrant ecosystem. TypeScript strict mode is non-negotiable for an audit-grade system. Specific version pin: Next.js `15.x` latest stable at Phase 0 kickoff; pinned and renovate-managed thereafter.

Alternatives considered:
- **Remix / React Router 7** — equally capable, smaller ecosystem; rejected for hiring market reasons.
- **SvelteKit** — excellent DX but smaller TypeScript ecosystem for the libraries we need (PDF generation, etc.); rejected for ecosystem.
- **Plain Express + React SPA** — more moving parts, no SSR benefit, rejected.

#### 6.2.2 UI: shadcn/ui + Tailwind v4 + Radix Primitives

Recommended. shadcn/ui is not a runtime dependency; components are copied into the repo and owned. This means no upstream version drift breaking the UI mid-audit. Radix Primitives (which shadcn wraps) is well-maintained and accessibility-first.

#### 6.2.3 Database: Postgres 16

Recommended. Boring, transactional, ACID, JSONB for snapshot columns in audit log, row-level security for the audit table's append-only enforcement, full-text search via tsvector, well-understood backup story.

#### 6.2.4 ORM: Drizzle ORM (over Prisma)

**Recommended: Drizzle.** Drizzle's TypeScript-first schema, SQL-mirror query API, and lightweight runtime are superior for an audit-defensible system where SQL legibility matters (an auditor reading the application code should be able to recognize the queries against the database schema). Drizzle migrations are SQL files committed to git, easily reviewed.

Prisma was considered and rejected:
- Prisma's runtime engine is an additional binary (~14 MB) on every deploy.
- Prisma's query DSL obscures the actual SQL, which complicates audit-trail review.
- Drizzle's RLS support (released 2024) is native; Prisma's RLS story requires raw queries.

Citation: [Drizzle RLS docs](https://orm.drizzle.team/docs/rls).

#### 6.2.5 Authentication: Better Auth (over NextAuth, Lucia, Clerk)

**Recommended: Better Auth (self-hosted).**

Comparison:

| Library | Self-hosted? | TypeScript? | Status | Verdict |
| --- | --- | --- | --- | --- |
| Better Auth | Yes, sessions in own DB | Yes, first-class | Active, took over Auth.js maintenance 2025 | **Recommended** |
| NextAuth / Auth.js | Yes | Partial | Security-patch mode; team points to Better Auth for new projects | Pass |
| Lucia | Yes | Yes | Deprecated March 2025 | Pass |
| Clerk | No (hosted) | Yes | Active | Pass — compliance blocker; user data lives in Clerk infrastructure, complicating 800-171 and ASA audit scope |
| Auth0 | No (hosted) | Yes | Active | Pass — same hosted-data concern as Clerk |

Better Auth provides full data ownership (sessions live in our Postgres), code-first integration, TOTP MFA out of the box, OAuth provider plug-ins if we later want Google SSO for TurbineWorks Google Workspace.

Citations: [Better Auth comparison](https://supastarter.dev/blog/better-auth-vs-nextauth-vs-clerk), [LogRocket 2026 review](https://blog.logrocket.com/best-auth-library-nextjs-2026/).

#### 6.2.6 PDF generation: @react-pdf/renderer (over Puppeteer)

**Recommended: @react-pdf/renderer.**

Comparison:

| Approach | Speed | Bundle | Layout flexibility | Verdict |
| --- | --- | --- | --- | --- |
| @react-pdf/renderer | <500ms | small | Flexbox subset, no CSS Grid | **Recommended** — fast, deterministic, deploys cleanly |
| Puppeteer / Chromium | 2-5s | ~100MB Chromium | Full HTML/CSS | Pass — bundle too large for Vercel function (50MB limit); Railway viable but slow |
| PDFKit | <500ms | small | Manual drawing | Pass — too low-level for our cert layouts |
| pdfmake | <500ms | medium | Declarative JSON | Considered; React-PDF wins on dev experience |

Certificates have fixed layouts (TurbineWorks branding, recipient name, course name, dates, verification code, QR code, optional Ed25519 signature block). Flexbox is sufficient. React-PDF's component model matches the rest of the codebase.

Citation: [Puppeteer vs React-PDF production comparison](https://dev.to/iurii_rogulia/pdf-generation-on-the-server-puppeteer-vs-react-pdfrenderer-a-production-comparison-44cg).

#### 6.2.7 Email: Resend

**Recommended: Resend.** React Email template support, $20/mo Pro plan covers 50K emails (far above need), clean developer experience. Postmark was the runner-up for slightly better deliverability at very small volumes; rejected on developer-experience and React Email integration.

Citations: [Resend vs SendGrid vs Postmark pricing](https://www.buildmvpfast.com/api-costs/email).

#### 6.2.8 Background jobs: Inngest

**Recommended: Inngest.** Step-based durable execution, native cron, event handlers, no Redis to operate, free Hobby tier sufficient at this scale. Trigger.dev was the runner-up for slightly better observability; rejected on simpler-is-better grounds. Vercel Cron alone is insufficient — too limited for retries and step composition.

Citations: [HashBuilds comparison](https://www.hashbuilds.com/articles/next-js-background-jobs-inngest-vs-trigger-dev-vs-vercel-cron).

#### 6.2.9 Hosting: Railway (over Vercel)

**Recommended: Railway.**

Comparison:

| Platform | DB included | Cost at our scale | Verdict |
| --- | --- | --- | --- |
| Railway | Yes, integrated managed Postgres | ~$35/mo (Pro seat + DB usage) | **Recommended** — container model, predictable, DB co-located in private network |
| Vercel | No (Neon partner) | ~$20/mo seat + Neon ~$20 = $40+ | Pass — Vercel function size limits constrain bundle, no DB co-location |
| Render | Yes | similar to Railway | Considered; Railway's DX is slightly ahead |
| Fly.io | Yes (Postgres clusters) | similar | Considered; Railway's UX is simpler for a 1-engineer team |

Railway's container model + integrated Postgres means the application and database share a private network (lower latency, no egress charge between them), the deploy story is uniform across services, and there is no per-seat tax that bites as the team grows from 1 to 3 engineers. We are already on Railway with the existing Moodle.

Citation: [Railway vs Vercel pricing](https://www.13labs.au/compare/railway-vs-vercel).

#### 6.2.10 File storage: Cloudflare R2 (over S3 / Vercel Blob)

**Recommended: Cloudflare R2.** S3-compatible API, zero egress fees (we serve cert PDFs from R2 directly to the verify page), 10 GB storage covers years of cert PDFs + assignment uploads at <$5/mo.

#### 6.2.11 Search: Postgres FTS

**Recommended: Postgres FTS (tsvector + GIN index).** Our corpus is ~10k rows of lesson content + glossary; Postgres FTS is more than sufficient and avoids the operational burden of a separate search service. tsvector columns are maintained at write time via a generated column or trigger.

#### 6.2.12 Observability: Sentry + Axiom

**Recommended:** Sentry for errors (browser + server), Axiom for structured logs. Both have free or near-free tiers at our scale. Sentry's error grouping and release tracking is industry standard. Axiom's log retention and query model is the cleanest at this price point.

#### 6.2.13 Cryptography

**Recommended:**
- **TLS 1.3** everywhere — enforced by Railway + Cloudflare.
- **Ed25519** for certificate signatures — small signatures (64 bytes), fast verification, no parameter choices to get wrong. ([Ed25519](https://ed25519.cr.yp.to/))
- **bcrypt cost 12** for password hashing (or **argon2id** if Better Auth uses it natively).
- **AES-256-GCM** for envelope-encrypting Postgres dumps before write to R2.
- **SHA-256** for audit-log hash chains.

### 6.3 Integration points with TW-OPS

These are designed for either of two end states:
- (A) **TWU and TW-OPS share a database and a Next.js application** — TWU is a route group within TW-OPS. Schema integration is direct.
- (B) **TWU and TW-OPS are separate Next.js applications sharing only an authentication issuer** — they exchange data over signed HTTPS RPC, with auth issued by a shared Better Auth instance (or TW-OPS owns auth and TWU validates JWTs issued by it).

The schema in Section 7 is written to be compatible with both; integration-specific tables (e.g., `tw_ops_user_link`) are noted where they would appear.

| Integration concern | Mode A (monorepo) | Mode B (separate apps) |
| --- | --- | --- |
| User records | Same `users` table | Federated; TWU stores a `tw_ops_user_id` pointer |
| Authentication | Same Better Auth instance | TW-OPS hosts Better Auth; TWU validates JWT |
| SOP cross-links | Direct FK or stable identifier | Stable identifier (e.g., `SOP-QA-002`) resolves via TW-OPS API |
| Audit aggregation | Shared `audit_log` | TWU exports `audit_log` rows to TW-OPS nightly |

**The scope assumes Mode B as a planning conservative** and explicitly notes (in Section 13) that resolving this question is a Phase 0 prerequisite.

### 6.4 Data flow — primary user journeys

**Journey 1: Employee completes a lesson.**
1. Browser GETs `/courses/TWF4-3/lessons/3.1` (signed-in session).
2. Server component fetches `lessons` joined with `lesson_versions` for the current published version, joined with the user's existing `lesson_views` row if any.
3. Server renders sanitized HTML.
4. On scroll-to-bottom or 30-second dwell (whichever first), browser fires a `view-recorded` server action.
5. Server action inserts a row into `lesson_views` (or updates `seen_at` if exists), inserts an `audit_log` entry (event `lesson.viewed`, includes prior + new state), checks course-completion criteria, and if satisfied, inserts a `course_completions` row triggering certificate issuance via Inngest event.

**Journey 2: Recurring reset (nightly).**
1. Inngest cron fires `lms.recurring.daily-reset` at 02:07 UTC.
2. Step 1: Query `course_completions` where `completed_at < now() - 180 days` AND no subsequent `recurring_resets.reset_at` for same `(user_id, course_id)`.
3. Step 2 (per row): Insert `recurring_resets` row, insert `audit_log` entry, mark certificate as `recurring_due` (via `certificates.status` enum update — which is itself audit-logged), clear `lesson_views` for that user/course, clear `quiz_attempts` for that user/course (preserve them — copy to history table), fire Inngest event `lms.email.recurring-due` per affected user.
4. Step 3: `lms.email.recurring-due` handler renders the email template via Resend.

**Journey 3: Public certificate verification.**
1. Browser GETs `/verify/{code}` (no auth required).
2. Server component looks up `certificates` by `verification_code`.
3. If found: render status (VALID / RECURRING DUE / EXPIRED / REVOKED) plus recipient, course, dates. If certificate has Ed25519 signature, verify against canonical record JSON and display "Signature verified."
4. Server emits one audit-log row (`certificate.verified`, with verifier IP) — yes, public verification is audit-logged.
5. Rate-limit: 60 requests per IP per hour, enforced at Cloudflare edge.

---

## 7. Database Schema

The schema below is written as Drizzle TypeScript with embedded SQL DDL comments. All tables are in the `public` schema unless noted. All `id` columns are `bigserial` unless noted (UUID for `certificates` and `audit_log.id` is internal-only — verification codes are short, alphanumeric).

### 7.1 Identity and access

```ts
// users — primary identity table; TurbineWorks-specific compliance fields are first-class.
export const users = pgTable('users', {
  id: bigserial('id', { mode: 'bigint' }).primaryKey(),
  email: text('email').notNull().unique(),
  email_verified_at: timestamp('email_verified_at', { withTimezone: true }),
  password_hash: text('password_hash'),               // null if SSO-only

  // Identity / display
  first_name: text('first_name').notNull(),
  last_name: text('last_name').notNull(),
  preferred_name: text('preferred_name'),

  // Employment
  employee_number: text('employee_number').unique(),  // assigned by HR/payroll
  department: text('department').notNull(),           // warehouse|qa|shipping|management|admin
  job_title: text('job_title').notNull(),
  hire_date: date('hire_date'),
  supervisor_id: bigint('supervisor_id', { mode: 'bigint' }).references(() => users.id),
  departure_date: date('departure_date'),             // null = active

  // External compliance identifiers
  asa_cert_id: text('asa_cert_id'),                   // ASA-issued individual cert if applicable

  // System
  status: text('status').notNull().default('active'), // active|suspended|departed
  last_login_at: timestamp('last_login_at', { withTimezone: true }),
  password_changed_at: timestamp('password_changed_at', { withTimezone: true }),

  // ALCOA+ attribution
  created_at: timestamp('created_at', { withTimezone: true }).notNull().defaultNow(),
  created_by: bigint('created_by', { mode: 'bigint' }).references(() => users.id),
  updated_at: timestamp('updated_at', { withTimezone: true }).notNull().defaultNow(),
  updated_by: bigint('updated_by', { mode: 'bigint' }).references(() => users.id),
}, t => ({
  emailLowerIdx: uniqueIndex('users_email_lower_idx').on(sql`lower(${t.email})`),
  departmentIdx: index('users_department_idx').on(t.department),
  statusIdx: index('users_status_idx').on(t.status),
}));

// roles — explicit, enumerated. Not free-form to keep audit defensible.
export const roleEnum = pgEnum('role', [
  'employee',          // base role; every user has at least this
  'qa_manager',        // grades assignments, overrides completion, runs reports
  'trainer',           // can deliver and record OJT entries
  'auditor',           // read-only access to all records (for external auditors)
  'admin',             // system administration; user provisioning; settings
  'super_admin',       // emergency-only, cannot self-grant audit-log delete (none exists)
]);

export const user_roles = pgTable('user_roles', {
  id: bigserial('id', { mode: 'bigint' }).primaryKey(),
  user_id: bigint('user_id', { mode: 'bigint' }).notNull().references(() => users.id),
  role: roleEnum('role').notNull(),
  granted_at: timestamp('granted_at', { withTimezone: true }).notNull().defaultNow(),
  granted_by: bigint('granted_by', { mode: 'bigint' }).references(() => users.id),
  revoked_at: timestamp('revoked_at', { withTimezone: true }),
  revoked_by: bigint('revoked_by', { mode: 'bigint' }).references(() => users.id),
}, t => ({
  activeRoleUnique: uniqueIndex('user_roles_active_unique')
    .on(t.user_id, t.role)
    .where(sql`revoked_at IS NULL`),
}));

// Sessions — owned by Better Auth, schema follows BA conventions
export const sessions = pgTable('sessions', {
  id: text('id').primaryKey(),                         // BA session id
  user_id: bigint('user_id', { mode: 'bigint' }).notNull().references(() => users.id),
  expires_at: timestamp('expires_at', { withTimezone: true }).notNull(),
  ip_address: inet('ip_address'),
  user_agent: text('user_agent'),
  created_at: timestamp('created_at', { withTimezone: true }).notNull().defaultNow(),
});

// MFA factors
export const mfa_factors = pgTable('mfa_factors', {
  id: bigserial('id', { mode: 'bigint' }).primaryKey(),
  user_id: bigint('user_id', { mode: 'bigint' }).notNull().references(() => users.id),
  factor_type: text('factor_type').notNull(),         // totp
  secret_encrypted: text('secret_encrypted').notNull(),
  verified_at: timestamp('verified_at', { withTimezone: true }),
  created_at: timestamp('created_at', { withTimezone: true }).notNull().defaultNow(),
});
```

**Design notes:**
- `users.department` and `users.job_title` are first-class columns rather than profile-field rows. This privileges the access pattern (every report query filters or groups by department) and avoids EAV pain.
- `users.supervisor_id` is a self-FK enabling org-chart traversal.
- `users.password_hash` nullable supports SSO-only users (future).
- `user_roles` uses a soft-revoke pattern (`revoked_at`) so role history is preserved for audit — a critical capability for an auditor asking "did this person have QA Manager rights when they signed off on this completion?"
- Unique partial index on `(user_id, role) WHERE revoked_at IS NULL` enforces "one active grant per role per user."

### 7.2 User certifications (external/separate from course completions)

```ts
// user_certifications — DOT Hazmat, IATA DGR, ESD, etc.
// Separate from course_completions because these certifications may be
// delivered by external vendors (IATA Training Center) and recorded in TWU
// rather than completed in TWU. They have their own expiry semantics.
export const certificationKind = pgEnum('certification_kind', [
  'dot_hazmat',       // 49 CFR §172.704 — 3 year cycle
  'iata_dgr',         // IATA DGR — 24 month cycle
  'esd',              // ANSI/ESD S20.20 — org-defined cycle, TWU = 6 months
  'forklift',         // OSHA-style; included for HR completeness
  'first_aid_cpr',    // optional; for completeness
  'other',            // catch-all with required free-form name
]);

export const user_certifications = pgTable('user_certifications', {
  id: bigserial('id', { mode: 'bigint' }).primaryKey(),
  user_id: bigint('user_id', { mode: 'bigint' }).notNull().references(() => users.id),
  kind: certificationKind('kind').notNull(),
  other_name: text('other_name'),                     // required if kind = 'other'

  // Issuance metadata
  issuing_authority: text('issuing_authority').notNull(), // "IATA Training Center DAL"
  trainer_name: text('trainer_name'),                  // 49 CFR §172.704 requires
  trainer_address: text('trainer_address'),            // 49 CFR §172.704 requires
  trainer_credential_ref: text('trainer_credential_ref'), // e.g., IATA instructor #

  // Dates
  issued_on: date('issued_on').notNull(),
  valid_until: date('valid_until').notNull(),         // computed at issue by kind cadence

  // Document
  document_storage_key: text('document_storage_key'),  // R2 key for scanned cert
  document_sha256: text('document_sha256'),            // integrity check for scan
  notes: text('notes'),

  // Lifecycle
  revoked_at: timestamp('revoked_at', { withTimezone: true }),
  revoked_by: bigint('revoked_by', { mode: 'bigint' }).references(() => users.id),
  revocation_reason: text('revocation_reason'),

  // ALCOA+ attribution
  created_at: timestamp('created_at', { withTimezone: true }).notNull().defaultNow(),
  created_by: bigint('created_by', { mode: 'bigint' }).notNull().references(() => users.id),
  updated_at: timestamp('updated_at', { withTimezone: true }).notNull().defaultNow(),
  updated_by: bigint('updated_by', { mode: 'bigint' }).notNull().references(() => users.id),
}, t => ({
  userKindIdx: index('user_cert_user_kind_idx').on(t.user_id, t.kind),
  expiryIdx: index('user_cert_expiry_idx').on(t.valid_until),
  activeUserKindUnique: uniqueIndex('user_cert_active_user_kind_unique')
    .on(t.user_id, t.kind)
    .where(sql`revoked_at IS NULL AND valid_until >= current_date`),
}));
```

**Design notes:**
- `kind` is enumerated; `other_name` for the long tail.
- `trainer_*` columns satisfy 49 CFR §172.704(d) recordkeeping (trainer name and address).
- `document_storage_key` + `document_sha256` lets us store a scan of the external certificate in R2 with integrity verification.
- Partial unique index enforces "one active certification of each kind per user."

### 7.3 Cohorts

```ts
export const cohortKind = pgEnum('cohort_kind', [
  'all_employees',
  'role',                  // role-based: warehouse, qa, shipping, management
  'certification',         // requires specific cert: dot_hazmat, iata_dgr, esd
  'authority',             // sup_reporter, dgd_signer
  'custom',
]);

export const cohorts = pgTable('cohorts', {
  id: bigserial('id', { mode: 'bigint' }).primaryKey(),
  name: text('name').notNull().unique(),
  kind: cohortKind('kind').notNull(),
  description: text('description'),

  // Auto-membership rule (JSON predicate, evaluated by membership sync job)
  auto_membership_rule_json: jsonb('auto_membership_rule_json'),
  // example: {"all": [{"users.department":"warehouse"}]}
  // example: {"all": [{"user_certifications.kind":"dot_hazmat","valid":true}]}

  created_at: timestamp('created_at', { withTimezone: true }).notNull().defaultNow(),
  created_by: bigint('created_by', { mode: 'bigint' }).notNull().references(() => users.id),
});

export const cohort_memberships = pgTable('cohort_memberships', {
  id: bigserial('id', { mode: 'bigint' }).primaryKey(),
  cohort_id: bigint('cohort_id', { mode: 'bigint' }).notNull().references(() => cohorts.id),
  user_id: bigint('user_id', { mode: 'bigint' }).notNull().references(() => users.id),

  added_at: timestamp('added_at', { withTimezone: true }).notNull().defaultNow(),
  added_by: bigint('added_by', { mode: 'bigint' }).references(() => users.id),
  added_via: text('added_via').notNull(),             // manual|auto_rule|migration
  removed_at: timestamp('removed_at', { withTimezone: true }),
  removed_by: bigint('removed_by', { mode: 'bigint' }).references(() => users.id),
}, t => ({
  activeMembershipUnique: uniqueIndex('cohort_memberships_active_unique')
    .on(t.cohort_id, t.user_id)
    .where(sql`removed_at IS NULL`),
}));
```

### 7.4 Course catalog

```ts
export const courseCategory = pgEnum('course_category', [
  'initial_training',       // TWF4-1 through TWF4-8
  'cumulative_final',       // TWF4-FINAL
  'recurring_training',     // explicit recurring tracks (currently same as initial)
  'reference_library',
  'engine_parts_specific',  // CFM56, CF6, V2500, PW4000
  'operations',
  'compliance_certifications', // DOT Hazmat in-house refresher etc.
]);

export const courses = pgTable('courses', {
  id: bigserial('id', { mode: 'bigint' }).primaryKey(),
  // Stable external identifier — preserved across migrations, printed on certs.
  // Maps directly to Moodle idnumber: TWF4-1, TWF4-2, ..., TWF4-CFM56, etc.
  idnumber: text('idnumber').notNull().unique(),
  name: text('name').notNull(),
  short_name: text('short_name').notNull(),
  category: courseCategory('category').notNull(),

  // Audience
  required_for_cohorts: jsonb('required_for_cohorts').notNull().default(sql`'[]'::jsonb`),
  // array of cohort_ids

  // Completion criteria — stored as structured config, not free-form code
  completion_criteria_json: jsonb('completion_criteria_json').notNull(),
  // example: {
  //   "all_lessons_viewed": true,
  //   "quiz_passed": {"quiz_idnumber":"TWF4-1-Q","passing_score":80},
  //   "assignment_graded": null
  // }

  // Certificate config
  issues_certificate: boolean('issues_certificate').notNull().default(true),
  certificate_template: text('certificate_template').notNull().default('standard'),
  // standard | master | dot_hazmat | iata_dgr | esd

  // Validity (in days) — if 0/null, certificate doesn't expire
  validity_days: integer('validity_days').default(180),  // 6-month default

  // Display order in user dashboard
  sort_order: integer('sort_order').notNull().default(0),

  // Visibility
  status: text('status').notNull().default('published'), // draft|published|archived

  created_at: timestamp('created_at', { withTimezone: true }).notNull().defaultNow(),
  created_by: bigint('created_by', { mode: 'bigint' }).notNull().references(() => users.id),
  updated_at: timestamp('updated_at', { withTimezone: true }).notNull().defaultNow(),
  updated_by: bigint('updated_by', { mode: 'bigint' }).notNull().references(() => users.id),
}, t => ({
  idnumberIdx: uniqueIndex('courses_idnumber_idx').on(t.idnumber),
  categoryIdx: index('courses_category_idx').on(t.category),
}));

export const modules = pgTable('modules', {
  id: bigserial('id', { mode: 'bigint' }).primaryKey(),
  course_id: bigint('course_id', { mode: 'bigint' }).notNull().references(() => courses.id),
  name: text('name').notNull(),
  sort_order: integer('sort_order').notNull().default(0),
  intro_html: text('intro_html'),
});

export const lessons = pgTable('lessons', {
  id: bigserial('id', { mode: 'bigint' }).primaryKey(),
  course_id: bigint('course_id', { mode: 'bigint' }).notNull().references(() => courses.id),
  module_id: bigint('module_id', { mode: 'bigint' }).references(() => modules.id),
  // Stable identifier within a course — survives content edits
  slug: text('slug').notNull(),                       // e.g., "lesson-3-1"
  name: text('name').notNull(),                       // "Lesson 3.1 — What is ASA-100?"
  intro_html: text('intro_html'),
  sort_order: integer('sort_order').notNull().default(0),

  // The current published version is in lesson_versions; lessons table holds metadata
  current_version_id: bigint('current_version_id', { mode: 'bigint' }),

  // Required-for-completion (vs optional / reference)
  required_for_completion: boolean('required_for_completion').notNull().default(true),

  created_at: timestamp('created_at', { withTimezone: true }).notNull().defaultNow(),
  updated_at: timestamp('updated_at', { withTimezone: true }).notNull().defaultNow(),
}, t => ({
  courseSlugUnique: uniqueIndex('lessons_course_slug_unique').on(t.course_id, t.slug),
}));

export const lesson_versions = pgTable('lesson_versions', {
  id: bigserial('id', { mode: 'bigint' }).primaryKey(),
  lesson_id: bigint('lesson_id', { mode: 'bigint' }).notNull().references(() => lessons.id),
  version_number: integer('version_number').notNull(),  // 1, 2, 3, ...

  // The actual content
  body_html: text('body_html').notNull(),              // sanitized HTML
  body_text: text('body_text').notNull(),              // extracted plain text for FTS
  video_embed_url: text('video_embed_url'),            // YouTube / Vimeo embed URL
  estimated_minutes: integer('estimated_minutes'),

  // ALCOA+
  published_at: timestamp('published_at', { withTimezone: true }).notNull().defaultNow(),
  published_by: bigint('published_by', { mode: 'bigint' }).notNull().references(() => users.id),
  body_html_sha256: text('body_html_sha256').notNull(),// integrity hash of body_html at publish
}, t => ({
  lessonVersionUnique: uniqueIndex('lesson_versions_unique').on(t.lesson_id, t.version_number),
  searchVector: index('lesson_versions_fts_idx').using('gin', sql`to_tsvector('english', ${t.body_text})`),
}));
```

**Design notes:**
- `lessons.current_version_id` is a forward FK to `lesson_versions`. When a lesson is edited, a new `lesson_versions` row is inserted and `lessons.current_version_id` is updated; the prior version is retained intact.
- `lesson_versions.body_html_sha256` lets us verify post-hoc that the version content has not been tampered with.
- The FTS GIN index supports search across lesson content.
- `lesson_views` (Section 7.6) records the exact `lesson_version_id` viewed.

### 7.5 Quizzes and questions

```ts
export const quizzes = pgTable('quizzes', {
  id: bigserial('id', { mode: 'bigint' }).primaryKey(),
  course_id: bigint('course_id', { mode: 'bigint' }).notNull().references(() => courses.id),
  module_id: bigint('module_id', { mode: 'bigint' }).references(() => modules.id),
  idnumber: text('idnumber').notNull().unique(),       // "TWF4-1-Q", "TWF4-FINAL"
  name: text('name').notNull(),
  intro_html: text('intro_html'),

  // Behavior
  passing_score: integer('passing_score').notNull().default(80),  // percent
  max_attempts: integer('max_attempts').notNull().default(3),
  time_limit_minutes: integer('time_limit_minutes'),    // null = untimed
  shuffle_questions: boolean('shuffle_questions').notNull().default(true),
  shuffle_options: boolean('shuffle_options').notNull().default(true),
  feedback_mode: text('feedback_mode').notNull().default('deferred'), // deferred|immediate
  questions_drawn: integer('questions_drawn'),          // if null, all; else random N from pool

  created_at: timestamp('created_at', { withTimezone: true }).notNull().defaultNow(),
  updated_at: timestamp('updated_at', { withTimezone: true }).notNull().defaultNow(),
});

export const questionType = pgEnum('question_type', [
  'multichoice',      // single correct answer
  'multichoice_multi',// multiple correct answers
  'truefalse',
  'short_answer',     // simple text match (case-insensitive)
  'matching',         // pair left-column with right-column
]);

export const questions = pgTable('questions', {
  id: bigserial('id', { mode: 'bigint' }).primaryKey(),
  quiz_id: bigint('quiz_id', { mode: 'bigint' }).notNull().references(() => quizzes.id),
  name: text('name').notNull(),                       // "M1Q1"
  type: questionType('type').notNull().default('multichoice'),
  stem_html: text('stem_html').notNull(),
  explanation_html: text('explanation_html'),         // shown after attempt
  sort_order: integer('sort_order').notNull().default(0),

  // Versioning — same pattern as lessons
  current_version: integer('current_version').notNull().default(1),

  created_at: timestamp('created_at', { withTimezone: true }).notNull().defaultNow(),
  updated_at: timestamp('updated_at', { withTimezone: true }).notNull().defaultNow(),
});

export const answer_choices = pgTable('answer_choices', {
  id: bigserial('id', { mode: 'bigint' }).primaryKey(),
  question_id: bigint('question_id', { mode: 'bigint' }).notNull().references(() => questions.id),
  text_html: text('text_html').notNull(),
  is_correct: boolean('is_correct').notNull().default(false),
  sort_order: integer('sort_order').notNull().default(0),  // canonical order
}, t => ({
  questionIdx: index('answer_choices_question_idx').on(t.question_id),
}));

// Snapshot the question + choices into each attempt to defeat post-hoc edits
export const quiz_attempts = pgTable('quiz_attempts', {
  id: bigserial('id', { mode: 'bigint' }).primaryKey(),
  quiz_id: bigint('quiz_id', { mode: 'bigint' }).notNull().references(() => quizzes.id),
  user_id: bigint('user_id', { mode: 'bigint' }).notNull().references(() => users.id),
  attempt_number: integer('attempt_number').notNull(), // 1, 2, 3...

  // Lifecycle
  started_at: timestamp('started_at', { withTimezone: true }).notNull().defaultNow(),
  submitted_at: timestamp('submitted_at', { withTimezone: true }),
  abandoned_at: timestamp('abandoned_at', { withTimezone: true }),

  // Result
  score_raw: integer('score_raw'),                    // # correct
  score_total: integer('score_total'),                // # possible
  score_percent: numeric('score_percent', { precision: 5, scale: 2 }),
  passed: boolean('passed'),

  // Snapshot of the question set at attempt time (frozen)
  question_set_snapshot_json: jsonb('question_set_snapshot_json').notNull(),
  // { questions: [{id, version, stem_html, choices: [{id, text_html, is_correct}]}], ordering: [...]}

  // Audit
  client_ip: inet('client_ip'),
  client_ua: text('client_ua'),
}, t => ({
  userQuizIdx: index('quiz_attempts_user_quiz_idx').on(t.user_id, t.quiz_id),
  quizUserAttemptUnique: uniqueIndex('quiz_attempts_unique').on(t.quiz_id, t.user_id, t.attempt_number),
}));

export const quiz_responses = pgTable('quiz_responses', {
  id: bigserial('id', { mode: 'bigint' }).primaryKey(),
  attempt_id: bigint('attempt_id', { mode: 'bigint' }).notNull().references(() => quiz_attempts.id),
  question_id: bigint('question_id', { mode: 'bigint' }).notNull().references(() => questions.id),
  question_version: integer('question_version').notNull(),

  // What the user picked (selected choice ids), preserved literally
  selected_choice_ids: jsonb('selected_choice_ids').notNull(),  // [123, 456]
  short_answer_text: text('short_answer_text'),

  is_correct: boolean('is_correct'),

  responded_at: timestamp('responded_at', { withTimezone: true }).notNull().defaultNow(),
});
```

**Design notes:**
- `quiz_attempts.question_set_snapshot_json` freezes the exact questions and choices the user saw, so an audit two years later can reconstruct exactly what was asked even if the question bank has since been edited.
- `quiz_responses.question_version` is recorded so that responses are always tied to the version of the question presented.
- The unique index on `(quiz_id, user_id, attempt_number)` enforces attempt-number sequencing.

### 7.6 Lesson views and assignments

```ts
export const lesson_views = pgTable('lesson_views', {
  id: bigserial('id', { mode: 'bigint' }).primaryKey(),
  lesson_id: bigint('lesson_id', { mode: 'bigint' }).notNull().references(() => lessons.id),
  lesson_version_id: bigint('lesson_version_id', { mode: 'bigint' }).notNull().references(() => lesson_versions.id),
  user_id: bigint('user_id', { mode: 'bigint' }).notNull().references(() => users.id),

  first_viewed_at: timestamp('first_viewed_at', { withTimezone: true }).notNull().defaultNow(),
  last_viewed_at: timestamp('last_viewed_at', { withTimezone: true }).notNull().defaultNow(),
  view_count: integer('view_count').notNull().default(1),
  // Time-on-page in seconds (capped per-view to defeat tab-open-and-walk-away)
  total_dwell_seconds: integer('total_dwell_seconds').notNull().default(0),
  marked_complete_at: timestamp('marked_complete_at', { withTimezone: true }),

  client_ip: inet('client_ip'),
}, t => ({
  userLessonUnique: uniqueIndex('lesson_views_unique').on(t.user_id, t.lesson_id),
  lessonIdx: index('lesson_views_lesson_idx').on(t.lesson_id),
}));

export const assignments = pgTable('assignments', {
  id: bigserial('id', { mode: 'bigint' }).primaryKey(),
  course_id: bigint('course_id', { mode: 'bigint' }).notNull().references(() => courses.id),
  module_id: bigint('module_id', { mode: 'bigint' }).references(() => modules.id),
  name: text('name').notNull(),
  prompt_html: text('prompt_html').notNull(),
  submission_type: text('submission_type').notNull().default('text'), // text|file|both
  max_points: integer('max_points').notNull().default(100),
  passing_points: integer('passing_points').notNull().default(70),
  due_after_days: integer('due_after_days'),          // null = no due date

  created_at: timestamp('created_at', { withTimezone: true }).notNull().defaultNow(),
  updated_at: timestamp('updated_at', { withTimezone: true }).notNull().defaultNow(),
});

export const assignment_submissions = pgTable('assignment_submissions', {
  id: bigserial('id', { mode: 'bigint' }).primaryKey(),
  assignment_id: bigint('assignment_id', { mode: 'bigint' }).notNull().references(() => assignments.id),
  user_id: bigint('user_id', { mode: 'bigint' }).notNull().references(() => users.id),
  attempt_number: integer('attempt_number').notNull().default(1),

  text_response: text('text_response'),
  file_storage_keys: jsonb('file_storage_keys'),       // ["r2/key1.pdf", "r2/key2.pdf"]

  submitted_at: timestamp('submitted_at', { withTimezone: true }).notNull().defaultNow(),

  // Grading
  graded_at: timestamp('graded_at', { withTimezone: true }),
  graded_by: bigint('graded_by', { mode: 'bigint' }).references(() => users.id),
  grade_points: integer('grade_points'),
  passed: boolean('passed'),
  feedback_html: text('feedback_html'),

  // QA Manager signature on the grade (Part 11–style)
  grade_signature_id: bigint('grade_signature_id', { mode: 'bigint' }), // FK to signatures
}, t => ({
  assignUserAttemptUnique: uniqueIndex('assignment_subs_unique')
    .on(t.assignment_id, t.user_id, t.attempt_number),
}));
```

### 7.7 Course completions and recurring resets

```ts
export const course_completions = pgTable('course_completions', {
  id: bigserial('id', { mode: 'bigint' }).primaryKey(),
  course_id: bigint('course_id', { mode: 'bigint' }).notNull().references(() => courses.id),
  user_id: bigint('user_id', { mode: 'bigint' }).notNull().references(() => users.id),

  completed_at: timestamp('completed_at', { withTimezone: true }).notNull(),
  // Mechanism: how completion was achieved
  completion_method: text('completion_method').notNull(),
  // auto_criteria | manual_override | imported | qa_signoff

  // Override metadata (if manual)
  overridden_by: bigint('overridden_by', { mode: 'bigint' }).references(() => users.id),
  override_reason: text('override_reason'),
  override_signature_id: bigint('override_signature_id', { mode: 'bigint' }),

  // Pointer to the specific evidence (latest quiz_attempt id, etc.)
  evidence_json: jsonb('evidence_json').notNull(),
  // {quiz_attempt_id: 123, assignment_submission_id: 456, lesson_view_ids: [...]}

  // Lifecycle — recurring-expired but not deleted
  expired_at: timestamp('expired_at', { withTimezone: true }),
  expired_via_recurring_reset_id: bigint('expired_via_recurring_reset_id', { mode: 'bigint' }),

  created_at: timestamp('created_at', { withTimezone: true }).notNull().defaultNow(),
  created_by: bigint('created_by', { mode: 'bigint' }).references(() => users.id),
}, t => ({
  // Multiple completions allowed per user/course over time (recurring re-completions).
  // No unique constraint on (user_id, course_id) — chronology preserved.
  userCourseIdx: index('course_completions_user_course_idx').on(t.user_id, t.course_id),
  courseCompletedIdx: index('course_completions_course_completed_idx').on(t.course_id, t.completed_at),
}));

export const recurring_resets = pgTable('recurring_resets', {
  id: bigserial('id', { mode: 'bigint' }).primaryKey(),
  user_id: bigint('user_id', { mode: 'bigint' }).notNull().references(() => users.id),
  course_id: bigint('course_id', { mode: 'bigint' }).notNull().references(() => courses.id),
  prior_completion_id: bigint('prior_completion_id', { mode: 'bigint' }).references(() => course_completions.id),

  reset_at: timestamp('reset_at', { withTimezone: true }).notNull().defaultNow(),
  reset_reason: text('reset_reason').notNull(),       // "6-month recurring cycle"
  reset_by: text('reset_by').notNull(),               // "system:inngest:recurring-reset"

  // What was reset (for forensic reconstruction)
  reset_payload_json: jsonb('reset_payload_json').notNull(),
  // {lesson_view_ids: [...], quiz_attempt_ids: [...]}

  notification_sent_at: timestamp('notification_sent_at', { withTimezone: true }),
  notification_sent_to: text('notification_sent_to'), // email at send time
}, t => ({
  userCourseIdx: index('recurring_resets_user_course_idx').on(t.user_id, t.course_id),
  resetAtIdx: index('recurring_resets_reset_at_idx').on(t.reset_at),
}));
```

**Design notes — recurring semantics:**
- A new `course_completions` row is inserted on every (re-)completion. Prior rows are not deleted; they are flagged with `expired_at` and a pointer to the `recurring_resets` row that expired them.
- The current valid completion for a user/course is: the row with `MAX(completed_at)` where `expired_at IS NULL`. This is a queryable invariant rather than a denormalized "is_current" boolean (which would tempt drift).
- The `recurring_resets.reset_payload_json` captures the specific lesson_view and quiz_attempt records that were "reset" so that an auditor can answer "what was their state immediately before the reset?"

### 7.8 Certificates

```ts
export const certificateStatus = pgEnum('certificate_status', [
  'valid',
  'recurring_due',     // past 6 months but no reset yet (informational)
  'expired',           // post-reset state
  'revoked',
]);

export const certificates = pgTable('certificates', {
  id: bigserial('id', { mode: 'bigint' }).primaryKey(),

  // Verification code — public-facing, alphanumeric, cryptographically random
  verification_code: text('verification_code').notNull().unique(),  // 10 chars, base32

  // Issuance
  user_id: bigint('user_id', { mode: 'bigint' }).notNull().references(() => users.id),
  course_id: bigint('course_id', { mode: 'bigint' }).notNull().references(() => courses.id),
  completion_id: bigint('completion_id', { mode: 'bigint' }).notNull().references(() => course_completions.id),
  template: text('template').notNull().default('standard'), // standard|master|dot_hazmat|iata_dgr|esd

  // Denormalized snapshot — cert PDF is reproducible from these fields alone
  recipient_name_snapshot: text('recipient_name_snapshot').notNull(),
  recipient_employee_number_snapshot: text('recipient_employee_number_snapshot'),
  recipient_department_snapshot: text('recipient_department_snapshot'),
  course_name_snapshot: text('course_name_snapshot').notNull(),
  course_idnumber_snapshot: text('course_idnumber_snapshot').notNull(),

  // Dates
  issued_at: timestamp('issued_at', { withTimezone: true }).notNull().defaultNow(),
  valid_until: date('valid_until'),                   // null = perpetual

  // Cryptographic signature (Ed25519 over canonical-JSON of the cert payload)
  signature_alg: text('signature_alg').notNull().default('ed25519'),
  signature_public_key_id: text('signature_public_key_id').notNull(), // key version
  signature_b64: text('signature_b64').notNull(),
  signed_payload_canonical: jsonb('signed_payload_canonical').notNull(),
  // {v:1, code, user_id, course_id, completion_id, issued_at, valid_until, ...}

  // Status / revocation
  status: certificateStatus('status').notNull().default('valid'),
  revoked_at: timestamp('revoked_at', { withTimezone: true }),
  revoked_by: bigint('revoked_by', { mode: 'bigint' }).references(() => users.id),
  revocation_reason: text('revocation_reason'),
  revocation_signature_id: bigint('revocation_signature_id', { mode: 'bigint' }),

  // PDF storage (regenerated on demand from canonical payload; stored copy is convenience)
  pdf_storage_key: text('pdf_storage_key'),
  pdf_sha256: text('pdf_sha256'),
}, t => ({
  userCourseIdx: index('certificates_user_course_idx').on(t.user_id, t.course_id),
  validUntilIdx: index('certificates_valid_until_idx').on(t.valid_until),
  statusIdx: index('certificates_status_idx').on(t.status),
}));
```

**Design notes:**
- `signed_payload_canonical` is the deterministic JSON serialization that was signed. To verify a certificate years later, reconstruct the canonical JSON from current state, compare to `signed_payload_canonical`, then verify `signature_b64` against `signed_payload_canonical` with the public key identified by `signature_public_key_id`.
- Keys are rotated via `signature_public_key_id` — old keys remain valid for verifying old certs; new signings use the new key.
- The denormalized `*_snapshot` columns make cert PDFs self-contained — a name change after issuance does not retroactively edit the cert content.

### 7.9 Audit log

```ts
// THE table. Append-only, hash-chained, indefinite retention.
export const audit_log = pgTable('audit_log', {
  id: bigserial('id', { mode: 'bigint' }).primaryKey(),
  occurred_at: timestamp('occurred_at', { withTimezone: true, precision: 6 })
    .notNull().defaultNow(),

  // What
  event_type: text('event_type').notNull(),           // 'lesson.viewed', 'cert.issued', 'user.role_granted'
  entity_type: text('entity_type').notNull(),         // 'lesson_view', 'certificate', 'user_role'
  entity_id: text('entity_id').notNull(),             // stringified id

  // Who
  actor_user_id: bigint('actor_user_id', { mode: 'bigint' }).references(() => users.id),
  actor_role_at_event: text('actor_role_at_event'),   // role at time of event (frozen)
  actor_email_snapshot: text('actor_email_snapshot'),

  // Where
  actor_ip: inet('actor_ip'),
  actor_ua: text('actor_ua'),
  session_id: text('session_id'),
  request_id: text('request_id'),

  // Snapshot
  before_json: jsonb('before_json'),                  // null for inserts
  after_json: jsonb('after_json'),                    // null for deletes
  diff_json: jsonb('diff_json'),                      // computed diff for legibility

  // Hash chain
  prev_hash: text('prev_hash'),                       // null only for first row ever
  this_hash: text('this_hash').notNull(),
  // this_hash = sha256(
  //   prev_hash || '|' || canonical_json({id, occurred_at, event_type, entity_type, entity_id,
  //                                        actor_user_id, before_json, after_json})
  // )

  // Notes (operational)
  note: text('note'),
}, t => ({
  occurredIdx: index('audit_log_occurred_idx').on(t.occurred_at),
  entityIdx: index('audit_log_entity_idx').on(t.entity_type, t.entity_id),
  eventIdx: index('audit_log_event_idx').on(t.event_type),
  actorIdx: index('audit_log_actor_idx').on(t.actor_user_id),
}));
```

**RLS policy (raw SQL DDL):**
```sql
-- The audit_log table is append-only at the database level.
ALTER TABLE audit_log ENABLE ROW LEVEL SECURITY;

-- The application role can INSERT and SELECT but never UPDATE or DELETE.
CREATE POLICY audit_log_app_insert ON audit_log FOR INSERT TO twu_app WITH CHECK (true);
CREATE POLICY audit_log_app_select ON audit_log FOR SELECT TO twu_app USING (true);
-- (No UPDATE or DELETE policy created. Without a policy, those operations are denied.)

-- Only the maintenance role (DBA-equivalent) can perform raw DDL or row deletion,
-- and is used only for documented retention purges (which themselves audit-log).
REVOKE UPDATE, DELETE ON audit_log FROM twu_app;
GRANT INSERT, SELECT ON audit_log TO twu_app;
```

**Hash chain integrity verification (pseudocode):**
```ts
async function verifyAuditChain(fromId: bigint, toId: bigint) {
  let prev: string | null = null;
  for (const row of await db.select().from(audit_log).where(...).orderBy(audit_log.id)) {
    const expected = sha256(
      (prev ?? '') + '|' +
      canonicalJsonStringify({
        id: row.id, occurred_at: row.occurred_at,
        event_type: row.event_type, entity_type: row.entity_type, entity_id: row.entity_id,
        actor_user_id: row.actor_user_id,
        before_json: row.before_json, after_json: row.after_json,
      })
    );
    if (expected !== row.this_hash) throw new ChainBreakError(row.id);
    if ((row.prev_hash ?? '') !== (prev ?? '')) throw new ChainBreakError(row.id);
    prev = row.this_hash;
  }
}
```

Citation for design: [Tamper-evident audit logs with hash chaining](https://tracehold.ai/blog/immutable-audit-log-hmac-hash-chain/), [Postgres tamper-evident audit trails](https://appmaster.io/blog/tamper-evident-audit-trails-postgresql).

### 7.10 Signatures (Part 11–style)

```ts
export const signatures = pgTable('signatures', {
  id: bigserial('id', { mode: 'bigint' }).primaryKey(),
  signer_user_id: bigint('signer_user_id', { mode: 'bigint' }).notNull().references(() => users.id),
  signed_at: timestamp('signed_at', { withTimezone: true }).notNull().defaultNow(),

  // 21 CFR §11.50 components
  printed_name_snapshot: text('printed_name_snapshot').notNull(),
  meaning: text('meaning').notNull(),                 // 'Approved', 'Reviewed', 'Authored', 'Override'

  // What was signed (entity reference + content hash)
  subject_entity_type: text('subject_entity_type').notNull(),
  subject_entity_id: text('subject_entity_id').notNull(),
  subject_canonical_json: jsonb('subject_canonical_json').notNull(),
  subject_sha256: text('subject_sha256').notNull(),

  // How the signer authenticated for this signing event
  // (Part 11 §11.200 — distinct from session auth; we require fresh MFA)
  auth_method: text('auth_method').notNull(),         // 'password+totp'
  mfa_challenge_id: text('mfa_challenge_id'),
  client_ip: inet('client_ip'),
}, t => ({
  signerIdx: index('signatures_signer_idx').on(t.signer_user_id),
  subjectIdx: index('signatures_subject_idx').on(t.subject_entity_type, t.subject_entity_id),
}));
```

### 7.11 Training records (OJT and manual)

```ts
// Non-LMS training: in-person, OJT, vendor classroom. Recorded manually by QA Manager.
export const training_records = pgTable('training_records', {
  id: bigserial('id', { mode: 'bigint' }).primaryKey(),
  user_id: bigint('user_id', { mode: 'bigint' }).notNull().references(() => users.id),
  topic: text('topic').notNull(),
  description: text('description'),

  delivered_at: timestamp('delivered_at', { withTimezone: true }).notNull(),
  duration_minutes: integer('duration_minutes').notNull(),
  delivered_by: bigint('delivered_by', { mode: 'bigint' }).references(() => users.id),
  delivered_by_external: text('delivered_by_external'), // if not a user

  // Source materials reference (49 CFR §172.704 expects this)
  materials_description: text('materials_description'),
  materials_storage_keys: jsonb('materials_storage_keys'), // R2 keys for uploaded copies

  signature_id: bigint('signature_id', { mode: 'bigint' }).references(() => signatures.id),

  created_at: timestamp('created_at', { withTimezone: true }).notNull().defaultNow(),
  created_by: bigint('created_by', { mode: 'bigint' }).notNull().references(() => users.id),
});
```

### 7.12 Training assignments and notifications

```ts
// When a manager assigns extra training to a specific user (off-cohort)
export const training_assignments = pgTable('training_assignments', {
  id: bigserial('id', { mode: 'bigint' }).primaryKey(),
  user_id: bigint('user_id', { mode: 'bigint' }).notNull().references(() => users.id),
  course_id: bigint('course_id', { mode: 'bigint' }).notNull().references(() => courses.id),
  assigned_at: timestamp('assigned_at', { withTimezone: true }).notNull().defaultNow(),
  assigned_by: bigint('assigned_by', { mode: 'bigint' }).notNull().references(() => users.id),
  due_at: timestamp('due_at', { withTimezone: true }),
  reason: text('reason'),
  completed_via_completion_id: bigint('completed_via_completion_id', { mode: 'bigint' })
    .references(() => course_completions.id),
});

export const notifications = pgTable('notifications', {
  id: bigserial('id', { mode: 'bigint' }).primaryKey(),
  user_id: bigint('user_id', { mode: 'bigint' }).notNull().references(() => users.id),
  kind: text('kind').notNull(),                       // 'recurring_due', 'cert_expiring', 'assignment_due'
  payload_json: jsonb('payload_json'),
  sent_at: timestamp('sent_at', { withTimezone: true }),
  sent_to_email: text('sent_to_email'),
  delivery_provider_id: text('delivery_provider_id'), // Resend message id
  read_at: timestamp('read_at', { withTimezone: true }),
  created_at: timestamp('created_at', { withTimezone: true }).notNull().defaultNow(),
});
```

### 7.13 System settings and background jobs

```ts
export const system_settings = pgTable('system_settings', {
  key: text('key').primaryKey(),
  value_json: jsonb('value_json').notNull(),
  description: text('description'),
  updated_at: timestamp('updated_at', { withTimezone: true }).notNull().defaultNow(),
  updated_by: bigint('updated_by', { mode: 'bigint' }).references(() => users.id),
});
// Seed values (one row each):
// recurring.window_days = 180
// session.idle_timeout_minutes = 30
// session.absolute_timeout_hours = 12
// password.policy.min_length = 14
// password.policy.rotation_days = 180
// cert.validity_default_days = 180
// audit.retention_policy = 'indefinite'
// org.legal_name = 'TurbineWorks Inc.'
// org.address = '...'

// Inngest manages job durability — but a local jobs_history table mirrors event ids for audit.
export const job_history = pgTable('job_history', {
  id: bigserial('id', { mode: 'bigint' }).primaryKey(),
  function_name: text('function_name').notNull(),     // 'lms.recurring.daily-reset'
  inngest_event_id: text('inngest_event_id'),
  inngest_run_id: text('inngest_run_id'),
  status: text('status').notNull(),                    // running|completed|failed
  started_at: timestamp('started_at', { withTimezone: true }).notNull().defaultNow(),
  finished_at: timestamp('finished_at', { withTimezone: true }),
  error_text: text('error_text'),
  summary_json: jsonb('summary_json'),
});
```

### 7.14 Cross-cutting design decisions

- **All timestamps are `timestamptz` UTC.** Display timezone is a UI concern; persistence is unambiguous.
- **All IDs are `bigserial`** except sessions (text), certs verification_code (text), and audit_log primary key (which is also bigserial; verification of the chain is by hash, not by id).
- **Soft deletes are explicit.** Each table that supports "deletion" has a `deleted_at` column rather than removing rows. Audit-log entries are emitted on transition.
- **All snapshots use canonical JSON.** RFC 8785-style: sorted keys, ISO 8601 timestamps, no insignificant whitespace.
- **All money / scores are integers or `numeric`** — no floats anywhere.

### 7.15 Multi-tenancy readiness

The schema is single-tenant. To make it multi-tenant in the future without disruption:
- Add an `organization_id` column to every business-relevant table with a default tenant id (`1` = TurbineWorks).
- Enable RLS on every table with policy `USING (organization_id = current_setting('app.organization_id')::bigint)`.
- Application sets `app.organization_id` per session.
- Backfill is a single migration.

This is not implemented in v1 but is intentionally a small future delta.

---

## 8. Feature Specifications

### 8.1 Authentication and user management

**Registration:** Admin-only initially. The QA Manager creates user accounts; the user receives a "set your password" email with a single-use, 24-hour token. No public sign-up. (Rationale: a 2-person company does not need a sign-up flow; admin-driven is more auditable.)

**Login flow:**
1. Email + password.
2. If MFA enrolled, prompt for TOTP.
3. On success: create session, set HTTP-only secure SameSite=Lax cookie.
4. Audit log: `auth.login` event with IP, UA, success status. Failed logins also logged.

**Password policy:**
- Minimum 14 characters.
- bcrypt (cost 12) or argon2id (per Better Auth default).
- Rotation every 180 days for admin / QA Manager / auditor.
- No rotation forced for employee role (per current NIST 800-63B guidance — rotation only triggers reuse).
- Last 5 passwords retained as hashes; new password cannot match prior 5.

**MFA:**
- **Mandatory** for: admin, super_admin, qa_manager, auditor.
- **Optional but encouraged** for: trainer, employee.
- TOTP only in v1; WebAuthn (security keys) considered for v2.
- Enrollment self-service from `/account/security`.
- MFA backup codes: 10 single-use codes printed at enrollment, regeneratable.

**Session timeout:**
- Idle timeout: 30 minutes (configurable in `system_settings`).
- Absolute timeout: 12 hours.
- "Remember me" not offered for elevated roles.

**Re-authentication for elevated actions:**
- Issuing an override-completion signature, revoking a certificate, granting a role, or editing audit-log-relevant system settings triggers a fresh MFA challenge.

**Role assignments:** Through admin UI; emits `user.role_granted` / `user.role_revoked` audit events.
**Cohort assignments:** Auto-rule-driven (e.g., department=warehouse → All Employees + Warehouse cohorts) plus manual override.

### 8.2 Lesson rendering

**HTML sanitization:** All lesson body HTML stored in `lesson_versions.body_html` is sanitized at write time using `rehype-sanitize` with an allowlist. Allowed tags: structural (`h2-h6`, `p`, `ul`, `ol`, `li`, `blockquote`), inline (`strong`, `em`, `code`, `a`), media (`img`, `figure`, `figcaption`), tables (`table`, `thead`, `tbody`, `tr`, `th`, `td`). Allowed attributes: `href` (with URL allowlist for `a`), `src`, `alt`, `width`, `height`, `class` (with class allowlist). Inline scripts and event handlers blocked.

**Embedded video:** Stored as canonical embed URL in `lesson_versions.video_embed_url`; rendered as iframe with `sandbox="allow-scripts allow-same-origin allow-presentation"` plus CSP allowlist for YouTube/Vimeo domains.

**Auto-linking glossary terms:** A server-side rehype pass during lesson render walks text nodes and replaces matched glossary terms with `<a class="glossary-term" data-concept="X">X</a>` (longest-match first to handle e.g. "FAA Form 8130-3" before "FAA"). Tooltips on hover show the definition; click opens the glossary detail page.

**Print-friendly view:** `/lessons/[id]/print` renders the lesson with a print stylesheet (no nav, no header, page-break-friendly).

**Accessibility (WCAG 2.1 AA):**
- Skip-link at top.
- Proper heading hierarchy (h1 page title, h2 lesson title, h3+ subsections).
- All images carry meaningful `alt` text (or `alt=""` for purely decorative).
- Color contrast ratio >= 4.5:1 for normal text, 3:1 for large text — enforced via Tailwind palette pre-screened with contrast checker.
- Keyboard navigation: every interactive element reachable; visible focus ring; logical tab order.
- ARIA landmarks: `<main>`, `<nav>`, `<header>`, `<footer>`.
- Form labels associated explicitly via `for`/`id`.
- Live regions for dynamic content (e.g., quiz feedback).

Citations: [WCAG 2.1 AA checklist](https://accessible.org/wcag/), [LMS accessibility guide](https://www.accessiblu.com/insights/ultimate-guide-for-an-accessible-lms/).

### 8.3 Quiz engine

**Question types (v1):** `multichoice` (single correct), `multichoice_multi` (multiple correct), `truefalse`, `short_answer` (case-insensitive trimmed exact-match against an allowlist of correct strings).

`matching` deferred to v2 — no current questions require it.

**Randomization:** Per attempt, the server picks the question order (if `shuffle_questions`) and the option order (if `shuffle_options`), records the order in `quiz_attempts.question_set_snapshot_json`, and presents accordingly. The browser receives the snapshot; the server uses it as the source of truth on submission.

**Question pools / random draw:** A quiz with `questions_drawn = 5` and 10 questions in the bank draws 5 randomly at attempt start. The 5 chosen and their order are part of the snapshot.

**Time limits and autosubmit:** If `time_limit_minutes` is set, the client displays a countdown; on expiry the client autosubmits. The server independently enforces the limit — any submission whose elapsed > `time_limit_minutes + 60s` grace is rejected as "Time exceeded" and the attempt closed with whatever answers were last received.

**Multiple attempts with cap:** `max_attempts` defaults to 3. Each attempt is a separate `quiz_attempts` row. The course completion uses the highest passing score.

**Feedback mode:**
- `deferred` (default): no per-question feedback during the attempt; full attempt review shown after submission.
- `immediate`: per-question feedback shown after each question selection (less defensible for audit but useful for low-stakes refreshers).

**Pass threshold:** `quizzes.passing_score`, default 80%. Failure does not block re-attempts (up to cap).

**No negative marking** — wrong answers count zero, not negative. Simpler, more defensible.

### 8.4 Assignment engine

**Submission types:** `text`, `file`, or `both`. v1 supports only `text` (HTML-sanitized rich text) and `file` (PDF/DOCX up to 10 MB stored in R2). Plagiarism check is out of scope for v1 (the assignment set is small and the QA Manager personally grades each).

**Grading workflow:**
1. User submits → `assignment_submissions` row inserted; `notify-assignment-submitted` Inngest event fires.
2. QA Manager receives email with link to `/admin/assignments/[id]/submissions/[subid]`.
3. QA Manager grades inline: assigns points, writes feedback (rich text), clicks "Sign and submit grade."
4. Signing flow requires fresh MFA challenge; emits a `signatures` row referencing the submission.
5. Submission updated with `graded_at`, `graded_by`, `grade_points`, `passed`, `grade_signature_id`.
6. User receives email with grade.

**Rubric support:** v1 = free-form feedback text + numeric grade. v2 = structured rubric (criterion/level/score).

### 8.5 Completion tracking

**Auto-completion criteria** (declarative in `courses.completion_criteria_json`):
- `all_lessons_viewed: true` — every `required_for_completion=true` lesson has a `lesson_views` row.
- `quiz_passed: { quiz_idnumber, passing_score }` — at least one `quiz_attempts` with `passed=true`.
- `assignment_graded: { assignment_id, must_pass }` — `assignment_submissions` with `graded_at` set, optionally `passed=true`.

Completion is evaluated server-side on every state change (lesson view, quiz submission, assignment grade). When all criteria are met, a `course_completions` row is inserted and a `course.completed` Inngest event fires (which in turn issues the certificate).

**Manual completion override:** QA Manager only. Triggers a signature, audit-log entry, and `completion_method = 'manual_override'`. Override reason is mandatory.

**Recurring re-completion:** A user who has a prior expired completion can re-complete the course; a new `course_completions` row is inserted; certificate re-issued with fresh `valid_until`.

### 8.6 Certificate generation

**PDF layout (standard template):**
- TurbineWorks logo + brand bar (navy + gold per existing verify.php styles).
- Title: "TurbineWorks University — Certificate of Completion."
- "Awarded to {recipient_name_snapshot}" — large.
- "{course_name_snapshot}" — medium.
- "Course ID: {course_idnumber_snapshot}".
- "Issued: {issued_at:Y-m-d}    Valid until: {valid_until:Y-m-d}" (or "Perpetual" if null).
- QR code linking to `https://learn.turbineworks.com/verify/{verification_code}`.
- "Verification code: {verification_code}" in monospace.
- "Cryptographic signature: {first 8 chars of signature_b64}..." with a footer note pointing to the verification page.

**Master template** (cumulative final exam): full-bleed navy border, embossed-style gold seal, "Master Certificate" subtitle, formatted differently to be visually distinct.

**Variant templates:** `dot_hazmat`, `iata_dgr`, `esd` — each carries the regulatory citation in the footer (e.g., "Issued per 49 CFR §172.704 recordkeeping requirements") and the trainer info from `user_certifications` if applicable.

**Generation pipeline:**
1. `course.completed` event handler computes `valid_until = completed_at + course.validity_days`.
2. Generates `verification_code` (10 chars from `crypto.randomBytes(8)` base32-encoded, retry on collision).
3. Constructs canonical JSON payload, signs with Ed25519 key (key loaded from env / Railway secret), stores signature + canonical payload.
4. Renders PDF via `@react-pdf/renderer`, computes SHA-256 of bytes, uploads to R2, stores `pdf_storage_key` + `pdf_sha256`.
5. Sends email to user with verification link.

**Verification (server-side, public endpoint):**
1. Look up `certificates` by `verification_code`.
2. Reconstruct canonical JSON from current row state, compare to `signed_payload_canonical` — must match exactly (drift = tamper signal).
3. Verify Ed25519 signature against `signed_payload_canonical` using public key for `signature_public_key_id`.
4. Determine status:
   - `revoked_at` set → REVOKED.
   - `valid_until < today` AND course exists in recurring → EXPIRED (RECURRING).
   - Today is within 30 days of `valid_until` for recurring course → RECURRING DUE.
   - Otherwise → VALID.

### 8.7 Recurring training engine

**Cron:** Inngest scheduled function, daily at 02:07 UTC (offset from typical 02:00 to spread infrastructure load).

**Algorithm:**
```ts
inngest.createFunction(
  { id: 'lms.recurring.daily-reset' },
  { cron: '7 2 * * *' },
  async ({ step }) => {
    const cutoff = sub(new Date(), { days: 180 });

    const candidates = await step.run('find-candidates', () =>
      db.select().from(course_completions)
        .innerJoin(courses, eq(courses.id, course_completions.course_id))
        .where(and(
          isNull(course_completions.expired_at),
          lt(course_completions.completed_at, cutoff),
          inArray(courses.category, ['initial_training', 'recurring_training',
                                       'compliance_certifications'])
        ))
    );

    for (const c of candidates) {
      await step.run(`reset-${c.id}`, async () => {
        await db.transaction(async tx => {
          const [reset] = await tx.insert(recurring_resets).values({...}).returning();
          await tx.update(course_completions).set({
            expired_at: new Date(),
            expired_via_recurring_reset_id: reset.id,
          }).where(eq(course_completions.id, c.id));
          await tx.update(certificates).set({ status: 'expired' })
            .where(eq(certificates.completion_id, c.id));
          await tx.delete(lesson_views)
            .where(and(eq(lesson_views.user_id, c.user_id), ...));
          await emitAudit(tx, { event_type: 'course.recurring_reset', ... });
        });
      });

      await step.sendEvent('notify', {
        name: 'lms.email.recurring-due',
        data: { user_id: c.user_id, course_id: c.course_id },
      });
    }
  }
);
```

**Notification email:** Subject "Recurring training due: {course.name}". Body: "Per TurbineWorks' 6-month recurring training cycle (more rigorous than ASA-100's annual minimum), please re-complete {course.name} at your earliest convenience. Direct link: {url}. Your prior completion is preserved in your training history; this is a regular cycle, not a remedial action."

**Forensic preservation:** Prior lesson_views and quiz_attempts are not deleted — they are copied to history tables or simply preserved with `course_completions.expired_at` set. Auditors can reconstruct historical state.

### 8.8 Reports and analytics

| Report | Description | Filters | Exports |
| --- | --- | --- | --- |
| Completion roster (matrix) | Users × Courses, cell = status (current/expired/never/in_progress) | Department, role, cohort | CSV, Excel, PDF |
| Recurring due tracker | Users with completions in last 7 / 30 days of validity window | Cohort, course | CSV |
| Certification expiry roster | Users with DOT/IATA/ESD certs nearing expiry | Kind, days_to_expiry | CSV, PDF (for QA Manager filing) |
| Failed quiz attempts | Attempts where `passed=false` | Course, user, date range | CSV |
| Training hours by user | Sum of estimated_minutes for completed lessons + duration_minutes for training_records | Date range | CSV |
| Training hours by department | Aggregated | Date range | CSV |
| Audit log query | Filter audit_log by actor, entity, event_type, date range | Many | CSV, JSONL |
| Training-needs analysis | For each role, what courses are required and who is missing | Role | CSV |
| ASA-100 audit packet | Compiled report: user roster + completion roster + recurring-due tracker + certifications roster + audit-log-of-the-last-12-months for chosen entities | Date range | PDF (multi-page printable) |

### 8.9 Public verification page

**URL:** `/verify/{code}` (e.g., `/verify/A3F9B2X7K1`).

**No authentication required.**

**Layout:**
```
+---------------------------------------------+
| TurbineWorks University                     |
| Certificate Verification — ASA-100 Compliance|
+---------------------------------------------+
|  [STATUS BADGE: VALID / RECURRING DUE /     |
|                EXPIRED / REVOKED]            |
|                                              |
|  Recipient:    Jane Doe (Employee #1042)     |
|  Department:   Warehouse                     |
|  Course:       Module 1 — SUPs & Counterfeit |
|  Course ID:    TWF4-1                        |
|  Issued:       2026-01-15                    |
|  Valid until:  2026-07-14                    |
|                                              |
|  Signature: ed25519:Vp+...verified           |
|                                              |
|  Verification code: A3F9B2X7K1               |
+---------------------------------------------+
|  About this verification...                  |
|  TurbineWorks operates recurring training    |
|  every 6 months — more rigorous than the     |
|  annual ASA-100 minimum.                     |
+---------------------------------------------+
```

**Status colors:**
- VALID — green (#2e7d32).
- RECURRING DUE — amber (#ff9800).
- EXPIRED — amber-red, with note "completion has aged past the recurring training cycle; re-completion in progress."
- REVOKED — red (#c62828), with the (non-confidential) revocation reason if set.

**Rate limiting:** 60 requests per IP per hour at Cloudflare edge; additional in-app throttle 600/hour total to detect enumeration attempts. Each verify emits an audit-log row.

### 8.10 Admin UI

**User management** (`/admin/users`):
- List with filters (department, role, status, last_login_at, recurring-due count).
- Create / edit user (admin or qa_manager).
- Grant / revoke role (admin only; with signature).
- Reset password (sends email).
- Force MFA enrollment (toggle).
- View user's training history (jump to per-user dashboard).

**Cohort management** (`/admin/cohorts`):
- List of cohorts with member counts.
- Edit name, description, auto-membership rule.
- Manually add/remove members.

**Content management** (`/admin/content`):
- Course CRUD: create, edit metadata, archive.
- Lesson CRUD: edit body (rich text editor), publishes a new `lesson_versions` row on save.
- Quiz CRUD: edit questions, options, correct answers.
- Glossary CRUD.
- "Reseed from fixtures" action: import lessons/quizzes/glossary from JSON fixture (used by migration script).

**Reports** (`/admin/reports`): As Section 8.8.

**Audit log viewer** (`/admin/audit-log`):
- Filter by actor, entity, event_type, date range.
- Click an entry to see full before/after JSON diff.
- "Verify hash chain" button: runs chain verification across the displayed range and shows result.
- Export CSV / JSONL.

**System settings** (`/admin/settings`): Edit `system_settings` rows; every edit signed and audit-logged.

### 8.11 Audit log specification

**Events emitted:**

| Category | event_type | Trigger |
| --- | --- | --- |
| Auth | `auth.login.success` | Successful login |
| Auth | `auth.login.failure` | Failed login attempt |
| Auth | `auth.logout` | Session end |
| Auth | `auth.mfa.enrolled` | TOTP enrollment |
| Auth | `auth.mfa.challenged` | MFA prompt shown |
| User | `user.created` | New user |
| User | `user.updated` | Profile change |
| User | `user.role_granted` | Role grant |
| User | `user.role_revoked` | Role revoke |
| User | `user.suspended` | Status → suspended |
| User | `user.departed` | departure_date set |
| Cohort | `cohort.created` / `cohort.updated` / `cohort.membership_added` / `cohort.membership_removed` | |
| Course | `course.created` / `course.updated` / `course.archived` | |
| Lesson | `lesson.viewed` | First view per user/lesson |
| Lesson | `lesson_version.published` | New version published |
| Quiz | `quiz.attempt_started` / `quiz.attempt_submitted` | |
| Assignment | `assignment.submitted` / `assignment.graded` | |
| Completion | `course.completed` | Criteria met or manual override |
| Completion | `course.recurring_reset` | Reset by cron |
| Certificate | `certificate.issued` / `certificate.revoked` / `certificate.verified` (public lookup) | |
| Cert | `user_certification.recorded` / `user_certification.revoked` | |
| Signature | `signature.created` | Any Part 11 signature |
| Settings | `settings.updated` | System setting change |
| System | `system.restore` / `system.backup_verified` | Quarterly restore drill outcome |

**Indefinite retention.** Reports module gives QA Manager an "ASA-100 audit packet" export that bundles audit_log + roster + cert roster as a single PDF + JSONL for handing to an auditor.

---

## 9. Security Model

### 9.1 Threat model (brief)

- **External unauthenticated attacker** — must be blocked at edge (Cloudflare) and application (Better Auth, rate limiting, CSP). Public surface = verify page + login + password reset.
- **Authenticated user trying to escalate** — RBAC checks at every server action; audit log emits on failures too.
- **Authenticated user trying to backdate or fabricate a completion** — write paths only through audited code paths; audit-log hash chain detects post-hoc tampering.
- **Compromised admin account** — fresh MFA on elevated actions limits blast radius; audit log emits on every action; alerts on unusual patterns.
- **Database insider (DBA-equivalent)** — append-only RLS makes raw INSERTs to the audit log without going through application code visible (gap in chain); off-platform backups limit ability to retroactively rewrite history.
- **Lost device** — session invalidation by user from `/account/security`; admin can force-invalidate.

### 9.2 Authorization matrix

| Action | employee | trainer | qa_manager | auditor | admin |
| --- | --- | --- | --- | --- | --- |
| View own dashboard | Y | Y | Y | N | Y |
| Take quiz / view lessons | Y | Y | Y | N | Y |
| View all reports | N | N | Y | Y (read-only) | Y |
| Grade assignment | N | N | Y | N | Y |
| Override completion | N | N | Y (signed) | N | Y (signed) |
| Revoke certificate | N | N | Y (signed) | N | Y (signed) |
| Manage users | N | N | Limited | N | Y |
| Manage roles | N | N | N | N | Y (signed) |
| Manage content | N | N | Y | N | Y |
| Edit system settings | N | N | N | N | Y (signed) |
| Verify audit-log hash chain | N | N | Y | Y | Y |
| Run "ASA-100 audit packet" export | N | N | Y | Y | Y |

### 9.3 Cryptography

- **TLS 1.3 only**, enforced at Cloudflare and Railway. HSTS max-age 1 year, includeSubDomains, preload.
- **Ed25519** for certificate signing. Private key in Railway secrets, referenced by `signature_public_key_id`. Public key published at `/.well-known/twu-cert-pubkey.json` (versioned).
- **bcrypt cost 12** (Better Auth default) for password hashing.
- **AES-256-GCM** for envelope-encrypting Postgres dumps before write to R2. Key wrapped by Railway secret.
- **SHA-256** for audit-log hash chains.
- **HMAC-SHA-256** for webhook signing (Inngest → app, app → Resend).

### 9.4 CSP and security headers

```
Content-Security-Policy: default-src 'self';
  script-src 'self' 'nonce-{generated}';
  style-src 'self' 'unsafe-inline';
  img-src 'self' data: https://*.r2.dev https://i.ytimg.com https://i.vimeocdn.com;
  frame-src 'self' https://www.youtube.com https://player.vimeo.com;
  connect-src 'self' https://*.sentry.io https://*.axiom.co;
  object-src 'none'; base-uri 'self'; form-action 'self'; frame-ancestors 'none';
Strict-Transport-Security: max-age=31536000; includeSubDomains; preload
X-Content-Type-Options: nosniff
Referrer-Policy: strict-origin-when-cross-origin
Permissions-Policy: camera=(), microphone=(), geolocation=()
```

### 9.5 Backup integrity

Weekly logical dump (`pg_dump --format=custom`) encrypted with AES-256-GCM, uploaded to R2. The dump's SHA-256 and the GCM tag are recorded in a `system_settings` row (`backup.latest.{date}`). Restore drill quarterly: provision a scratch Postgres, restore the dump, run a spot-check query, emit `system.backup_verified` audit event.

### 9.6 Rate limiting

- Login: 5 attempts / IP / 5 minutes; 20 attempts / account / hour.
- Password reset: 3 / email / hour.
- Verify endpoint: 60 / IP / hour (Cloudflare).
- All other authenticated endpoints: 600 / user / minute (generous, prevents runaway clients).

### 9.7 Dependency hygiene

- Renovate or Dependabot enabled.
- `npm audit` on every CI run; high/critical fails the build.
- Pin Node.js LTS in `engines`; pin Postgres major in Railway service config.
- Document patch SLA: critical = 24 hours, high = 7 days, medium = 30 days.

### 9.8 SOC 2 / ISO 27001 readiness (future)

Not pursuing certification in v1, but the architecture supports it:
- Audit log + change management → SOC 2 CC8.1.
- Backup + DR drill → CC7.5.
- Access control + MFA → CC6.6.
- Encryption in transit + at rest → CC6.7.
- Vulnerability management → CC7.1.

---

## 10. Operational Concerns

### 10.1 Hosting topology

- **Production:** Railway project `turbineworks-twu-prod`, two services: `app` (Next.js) and `db` (Postgres 16 managed).
- **Staging:** Railway project `turbineworks-twu-staging`, identical topology, smaller plan.
- **Local dev:** Docker Compose with Postgres 16 + app via `next dev`.

### 10.2 Database backups + restore drill

- **WAL streaming PITR** via Railway managed Postgres — 7-day window.
- **Weekly logical dumps** to R2, encrypted, retained 4 years.
- **Quarterly restore drill** — restore latest weekly dump to a scratch Railway Postgres, run smoke tests, emit `system.backup_verified` audit event, document outcome.
- **DR RTO target:** 4 hours.
- **DR RPO target:** 1 hour (PITR window).

### 10.3 Monitoring + alerting

- **Sentry alerts** on new error types, error rate spikes, unhandled promise rejections.
- **Axiom alerts** on absence of expected events (e.g., `lms.recurring.daily-reset` did not run for 26+ hours), spike in `auth.login.failure`, spike in `certificate.verified` (could indicate enumeration), audit-log chain verification failure.
- **Health endpoint** at `/api/health`: returns 200 if app + DB + Inngest reachable.
- **Uptime monitor** (Uptime Robot or Better Stack) on the health endpoint + the verify page; pages on >2 minutes of downtime.

### 10.4 Logging

Structured JSON logs via Pino or equivalent, shipped to Axiom. Levels: trace/debug suppressed in prod; info for normal flow; warn for recoverable anomalies; error for unhandled. Every server action emits at least one info log with `requestId`, `userId`, `action`, `outcome`. PII rules: log emails truncated to first 4 chars + domain; no passwords ever logged; no full audit_log content logged (only event_type + entity_id).

### 10.5 Performance budgets

- **Lesson page TTFB:** P95 < 500 ms.
- **Lesson page LCP:** P95 < 2 s.
- **Quiz submission:** P95 < 1 s.
- **Cert PDF generation:** P95 < 2 s.
- **Verify page (cached cert):** P95 < 300 ms.
- **Report query (typical):** P95 < 3 s; (audit log, large date range): P95 < 10 s.

### 10.6 SLO / SLA

| SLO | Target | Measurement |
| --- | --- | --- |
| Monthly availability | 99.5% | Uptime monitor on `/api/health` |
| Recurring reset job success | 99.9% per run | Audit-log presence of `course.recurring_reset` or no-op completion |
| Cert verification availability | 99.9% | Uptime monitor on `/verify/{known-code}` |
| Backup completion | 100% weekly | `system.backup_verified` event |

### 10.7 Incident response

- Runbook at `docs/runbook.md` covering: app down, DB down, Inngest stalled, R2 unreachable, certificate signature key compromise (rotation procedure), audit-log chain break detected.
- On-call: single engineer for v1 (small org realism). Escalation = page principal.

---

## 11. Build Phases and Acceptance Criteria

### 11.1 Phase 0 — Foundation (Week 1)

**Goal:** Skeleton repository, CI, deploy pipeline, base schema, base auth.

**Tasks:**
- Initialize Next.js 15 monorepo (or single app, per Mode A vs B resolution).
- Set up TypeScript strict, ESLint, Prettier, Vitest, Playwright.
- Initialize Drizzle, write base migrations: `users`, `roles`, `user_roles`, `sessions`, `mfa_factors`, `audit_log`, `signatures`, `system_settings`, `job_history`.
- Integrate Better Auth with email/password + TOTP.
- Set up Railway projects (staging + prod), provision Postgres, configure environment variables.
- Set up GitHub Actions CI: lint, typecheck, unit tests, build.
- Deploy first version to staging — login page + dashboard placeholder.
- Configure Cloudflare DNS, TLS, edge rate limiting on verify route (placeholder).
- Set up Sentry + Axiom.

**Acceptance criteria:**
- Admin can log in via password + TOTP.
- Failed logins are rate-limited; logged in audit log.
- Audit log INSERT works; chain integrity verifiable via a CLI script.
- Staging deploys on every push to `main`; production deploy via manual approval.
- Health endpoint returns 200; `/api/version` returns commit SHA.

### 11.2 Phase 1 — Core LMS read path (Week 2)

**Goal:** Users can see courses, view lessons, take quizzes.

**Tasks:**
- Schema: `courses`, `modules`, `lessons`, `lesson_versions`, `quizzes`, `questions`, `answer_choices`, `quiz_attempts`, `quiz_responses`, `lesson_views`.
- Content-import CLI: read PHP fixtures (or pre-converted JSON), seed `courses` / `lessons` / `lesson_versions` / `quizzes` / `questions` / `answer_choices`.
- Server components for `/courses`, `/courses/[idnumber]`, `/courses/[idnumber]/lessons/[slug]`, `/courses/[idnumber]/quizzes/[idnumber]`.
- Server action: record lesson view.
- Server action: start quiz attempt, submit quiz attempt.
- Quiz scoring + result rendering.
- Glossary auto-link rehype plugin.

**Acceptance criteria:**
- All 8 Initial Training courses + Cumulative Final + Engine Parts courses appear in dashboard for an `employee`-roled user.
- Clicking a lesson renders the content with sanitized HTML, glossary terms auto-linked.
- Quiz attempt → score → audit log entries all observable.
- Lighthouse a11y score >= 90 on lesson page.

### 11.3 Phase 2 — Completion and certificates (Week 3)

**Goal:** Users can complete courses; certificates issued and verifiable.

**Tasks:**
- Schema: `course_completions`, `certificates`, `assignments`, `assignment_submissions`.
- Completion-evaluation logic: on lesson view / quiz pass / assignment grade, evaluate `completion_criteria_json`, insert `course_completions` if satisfied.
- Ed25519 key generation + secret loading + canonical-JSON utility.
- PDF generation via `@react-pdf/renderer` for `standard` and `master` templates (other variants in Phase 3).
- R2 upload helper.
- `/verify/[code]` public route with status badge + signature verification.

**Acceptance criteria:**
- A test user completing all lessons in TWF4-1 and passing the quiz receives a certificate.
- Certificate PDF downloads; verification code is on page 1.
- `/verify/{code}` renders VALID status, recipient name, dates, "Signature verified."
- Manually tampering a row in `certificates` (e.g., editing recipient name) causes the canonical comparison to fail → verify page shows "Signature mismatch — please contact TurbineWorks."

### 11.4 Phase 3 — Compliance and recurring (Week 4)

**Goal:** Recurring training engine + user certifications + audit-log query + accessibility audit.

**Tasks:**
- Schema: `recurring_resets`, `user_certifications`, `training_records`, `training_assignments`, `notifications`.
- Inngest setup; deploy `lms.recurring.daily-reset` function.
- Email templates via Resend + React Email: recurring-due, cert-expiring, password-reset, assignment-graded.
- Profile-page UI for user certifications.
- Variant cert templates (`dot_hazmat`, `iata_dgr`, `esd`).
- Audit-log viewer + filter UI.
- WCAG 2.1 AA self-audit on lesson/quiz/verify flows.

**Acceptance criteria:**
- A user with a completion older than 180 days is reset overnight; receives email; their dashboard shows the course as "Re-completion required."
- Their certificate shows EXPIRED status on the verify page.
- A user certification (DOT Hazmat) with `valid_until` in 30 days triggers a reminder email.
- Audit-log viewer can answer "What did jane@example.com do on 2026-03-15?" in one query.
- a11y self-audit: zero critical / serious issues on tested flows.

### 11.5 Phase 4 — Admin (Week 5)

**Goal:** All admin operations available in-UI.

**Tasks:**
- Admin user CRUD.
- Admin cohort CRUD + auto-rule editor.
- Admin content CRUD: course / module / lesson / quiz / glossary editors.
- All seven reports + CSV/Excel/PDF export.
- ASA-100 audit packet export (multi-page PDF assembly).
- Signature workflow for elevated actions (override completion, revoke cert, grant role).

**Acceptance criteria:**
- QA Manager can perform every routine task without engineering involvement.
- The seven reports execute on a 100-user test dataset in < 5 s each.
- ASA-100 audit packet PDF generates in < 30 s and is internally consistent.
- All signature events emit signatures rows + audit-log rows; reverification of a signature works.

### 11.6 Phase 5 — Polish and cutover (Week 6)

**Goal:** Production-ready; current Moodle decommissioned.

**Tasks:**
- Content migration script: extract from Moodle DB (and/or PHP fixtures), reseed TWU production.
- Parallel run: keep Moodle running for 1 week; spot-check that any completions in TWU match expected.
- Security audit: external read of code, penetration test on staging (manual via OWASP ASVS L2 checklist).
- Accessibility audit: VPAT-style document produced.
- Production data load.
- Migrate verify URL: `learn.turbineworks.com/local/twu/verify.php?code=X` → `learn.turbineworks.com/verify/X` (with 301 redirects from old format).
- Decommission Moodle (export full DB to R2 for cold archival, shut down service).
- Documentation: admin guide, runbook, restore drill procedure, signature key rotation procedure.

**Acceptance criteria:**
- All Section 1.3 success criteria satisfied.
- Quarterly restore drill executed once, documented, passing.
- VPAT document drafted.
- Engineering can take a full week off without operational intervention.

---

## 12. Migration Strategy

### 12.1 Content extraction

Current Moodle content lives in:
- `local_twu/content/content.php` — lessons (function `local_twu_get_lessons()`)
- `local_twu/content/quizzes.php` — quizzes (function `local_twu_get_quizzes()`)
- `local_twu/content/glossary.php` — glossary (function `local_twu_get_glossary_entries()`)

Extraction approach:
1. Write a one-shot PHP CLI script `tools/export-fixtures.php` that calls each function and emits JSON files to `/fixtures/lessons.json`, `/fixtures/quizzes.json`, `/fixtures/glossary.json`.
2. Commit these JSON files to the new TWU repo.
3. The TWU content-import CLI (`pnpm tsx scripts/seed-content.ts`) reads them and inserts into the database via Drizzle.
4. The JSON files become the source of truth going forward; PHP fixtures are archived.

Alternative: transcode directly to TypeScript fixtures (`content.ts` exporting typed arrays). Pro: type-checked. Con: large files; JSON simpler. Recommendation: JSON for the bulk content, with TypeScript types defined separately and validated at import.

### 12.2 User account migration

Current Moodle holds only the admin account. Migration of users is essentially "create the QA Manager's account in TWU and any other employees who need access" — manual, takes 5 minutes. No data migration needed.

### 12.3 Certificate / completion migration

If there are any issued certificates in Moodle at cutover time (likely none, given current state), they migrate via a one-off SQL extraction:
```sql
-- Run on Moodle Postgres
COPY (
  SELECT ci.id, ci.code, ci.timecreated, ci.userid,
         u.firstname, u.lastname, u.email,
         c.idnumber, c.fullname
  FROM mdl_customcert_issues ci
  JOIN mdl_user u ON u.id = ci.userid
  ...
) TO STDOUT WITH (FORMAT csv, HEADER true);
```
Then a TWU import script ingests, creating `course_completions` (with `completion_method='imported'`) and `certificates` (preserving `verification_code` so existing printed certs remain valid).

### 12.4 Parallel run + cutover criteria

- **Week 5 (Phase 5):** Moodle still running, TWU staging fully built, content fully imported.
- **Week 6 start:** TWU promoted to production at a temporary URL (`twu-new.turbineworks.com`). QA Manager spends 2 days exercising every flow: take a quiz, complete a course, verify a cert, run every report, run the audit-log query interface.
- **Cutover gate (go/no-go meeting):** Check all Section 1.3 success criteria + WCAG 2.1 AA pass + security audit completed + restore drill passed.
- **Cutover (Friday afternoon):** DNS for `learn.turbineworks.com` switched to TWU; old Moodle responds with a maintenance page; 24-hour bake. Monday: Moodle decommissioned.

### 12.5 Rollback plan

If cutover fails within 24 hours:
- DNS flipped back to Moodle.
- No data loss because TWU has only been doing writes for completion attempts during the bake window; the QA Manager scrubs those (most likely test data).
- Re-plan the gap and re-cut over the following week.

After 24 hours, rollback gets harder (real completions in TWU). Mitigation: monitor closely during the 24-hour bake; either confirm green or roll back before it gets expensive.

---

## 13. Open Questions

These must be resolved before or during Phase 0; tracked here for visibility.

| # | Question | Owner | Default if unresolved |
| --- | --- | --- | --- |
| 1 | TW-OPS stack — Mode A (monorepo, shared DB/auth) or Mode B (separate apps, federated auth)? | Principal + Engineering | Mode B (separate); schema in this scope is compatible with either |
| 2 | TW-OPS controlled-document URL scheme for cross-linking SOPs from lessons | Principal | `https://ops.turbineworks.com/sops/{id}` placeholder |
| 3 | Hosting: confirm Railway (current default) vs Vercel | Engineering | Railway |
| 4 | Email provider: confirm Resend vs Postmark | Engineering | Resend |
| 5 | MFA mandatory for all roles or only elevated? | Principal | Elevated only; employee role optional |
| 6 | Cryptographic signatures on certs — confirm (recommended, +1 day) | Principal | Yes — included in scope |
| 7 | Multi-language support — defer? | Principal | Defer (English only v1) |
| 8 | SCORM/xAPI — defer? | Principal | Defer indefinitely |
| 9 | Public sign-up vs admin-only registration | Principal | Admin-only |
| 10 | Cohort auto-rule predicate DSL — JSON Logic, custom, or Drizzle expressions? | Engineering | JSON Logic (well-documented, sandboxed) |
| 11 | Cert PDF visual design — does Principal want a specific layout / brand assets? | Principal | Reuse existing `verify.php` palette (navy + gold) |
| 12 | Auditor role — does TurbineWorks want a "guest auditor" account on the live system, or generate read-only PDF audit packet for handoff? | Principal | Both (guest auditor optional; audit packet primary) |
| 13 | Signature key — TurbineWorks-controlled key or use a managed KMS? | Engineering | App-managed via Railway secret; KMS deferred |
| 14 | Departure of an employee — data retention policy (keep all training records forever, or anonymize after 7 years)? | Principal | Keep forever |
| 15 | OJT records — how often does the QA Manager record these? Drives UI priority. | Principal | Assume frequent; UI prioritized in Phase 4 |

---

## 14. Cost and Operational Estimate

### 14.1 Infrastructure monthly run cost

| Item | Cost | Notes |
| --- | --- | --- |
| Railway Pro seat | $20 | First engineer seat |
| Railway Postgres (managed) | ~$10 | Usage-based, low traffic |
| Railway compute (app) | ~$15 | Usage-based, low traffic |
| Resend Pro | $20 | 50K emails/month |
| Sentry Team | $26 | Optional; free tier covers errors at this scale |
| Axiom Personal Pro | $25 | Optional; can defer |
| Cloudflare R2 | <$5 | 10 GB + zero egress fee |
| Cloudflare (DNS, edge rules) | $0 | Free tier |
| Inngest | $0 | Free tier (Hobby) |
| **Total — minimum (without Sentry/Axiom)** | **~$70/mo** | |
| **Total — full observability** | **~$120/mo** | |

### 14.2 Build cost (developer hours)

Phase | Duration | Hours (single engineer) |
| --- | --- | --- |
Phase 0 — Foundation | 1 week | 40 |
Phase 1 — Core read path | 1 week | 40 |
Phase 2 — Completion + certs | 1 week | 40 |
Phase 3 — Compliance + recurring | 1 week | 40 |
Phase 4 — Admin | 1 week | 40 |
Phase 5 — Polish + cutover | 1 week | 40 |
**Total** | **6 weeks** | **240 hours** |

Add 25% buffer for unknowns and the realistic span is **8 weeks / 320 hours**.

### 14.3 Ongoing maintenance burden

- **Steady-state weekly:** 1-2 hours for dependency updates, log review, occasional content edits.
- **Quarterly:** Restore drill (~1 hour), accessibility re-check (~1 hour), security review (~2 hours).
- **Annually:** Renew TLS cert reminders (automatic with Cloudflare/Letsencrypt anyway), audit-log archival snapshot, signature key rotation review.

Compare to Moodle: ongoing was ~4-8 hours per week of either feature delivery (slow) or platform debugging (frequent). TWU's expected steady-state is roughly 1/4 to 1/8 of Moodle's.

---

## 15. Risks and Mitigations

| # | Risk | Likelihood | Impact | Mitigation |
| --- | --- | --- | --- | --- |
| 1 | Next.js 16 major release introduces breaking changes mid-build | Medium | Medium | Pin Next.js 15.x; defer major upgrades until 16.x is stable (typically 6 months); Renovate PRs reviewed before merge |
| 2 | Better Auth major version change | Low | Medium | BA is on a stable 1.x; the team has stated long-term support; we own session data so a switch to another lib is contained |
| 3 | Railway price hike or outage | Medium | High | Architecture is portable to Fly/Render with <2 days of work; weekly off-platform backups to R2 mean data is not Railway-trapped |
| 4 | Postgres data corruption | Low | High | PITR + weekly logical dumps + quarterly restore drill |
| 5 | Cert signing key compromise | Low | High | Key rotation procedure documented; `signature_public_key_id` lets new signings use a new key while old certs remain verifiable; in worst case revoke all certs signed with compromised key and re-issue |
| 6 | Audit-log chain break due to bug | Low | High | Hash verification job runs nightly; alerts on first break; investigate before continuing operations |
| 7 | Content drift between training material and current regulations | Medium | High | Annual content review by QA Manager; lesson versions retained so old completions remain attached to the content actually consumed; review process documented in QAM |
| 8 | Auditor expects SCORM/xAPI export | Low | Low | "ASA-100 Audit Packet" PDF + CSV exports are functionally equivalent for ASA inspectors; SCORM is not an ASA requirement |
| 9 | Accessibility regression on a future content edit | Medium | Medium | Lint rule for lesson HTML requiring `alt` on `img`; automated Axe-core check in CI on representative lesson pages |
| 10 | Email deliverability degradation | Low | Medium | Resend monitors deliverability; SPF/DKIM/DMARC configured at Cloudflare; bounces auto-flagged to QA Manager |
| 11 | TWU-OPS integration spec changes mid-build | Medium | Medium | Mode B (federated) chosen as default isolates TWU from TW-OPS changes; if Mode A becomes attractive, refactoring is a 1-2 day task |
| 12 | Regulatory change (e.g., ASA-100 rev 6.0 introduces new training requirements) | Low | Medium | Content edits are routine; schema is generic enough that new course categories or cert kinds slot in as enum additions |
| 13 | QA Manager unfamiliar with new admin UI; relies on engineer | Medium | Low | Admin UI built with familiar patterns from Moodle reports/quiz editor; admin guide written in Phase 5 |
| 14 | Single-engineer bus factor | Medium | High | Documentation as deliverable in every phase; commit messages descriptive; runbook covers all operational scenarios; code style consistent with hiring norms (TS strict + Drizzle is well-known) |
| 15 | Cost overrun on phase 0/1 if scope creeps | Medium | Medium | Strict phase gates with acceptance criteria; out-of-phase work moved to a "v2 backlog" file |

---

## 16. Appendix A — Regulatory Citation Index

| Regulation | Section | Plain-English Topic | TWU Implementation Location |
| --- | --- | --- | --- |
| FAA AC 00-56B | ¶3 | Training within quality system | Section 4.2; Phase 3 |
| FAA AC 21-29D | (general) | SUP detection and reporting | Module 1 lesson content |
| FAA AC 21-38 | (general) | Disposition of unsalvageable parts | Module 5 lesson content |
| FAA AC 20-62E | (general) | Eligibility and ID of replacement parts | Module 2/5 lesson content |
| 14 CFR Part 21 | §21.502 | Definition of unapproved part | Module 1, Glossary |
| 14 CFR Part 43 | §43.10 | Disposition of life-limited parts | Module 5 |
| 14 CFR Part 43 | §43.9 | Maintenance records | Lesson reference |
| 14 CFR Part 145 | §145.219 | Repair station recordkeeping | Lesson reference |
| 21 CFR Part 11 | §11.10(e), §11.50, §11.70, §11.100, §11.200, §11.300 | Electronic records, signatures, audit trails | Section 4.4, 7.9, 7.10, 8.4 |
| 49 CFR Part 172 | §172.704 | DOT hazmat training | Section 4.6; user_certifications |
| ASA-100 | §3, §6-§10 | QMS requirements | All Initial Training modules |
| ANSI/ESD S20.20 | §6 (training) | ESD training program | Module 7; user_certifications |
| DFARS | 252.246-7007, 7008 | Counterfeit detection and avoidance | Module 1 |
| SAE | AS5553, AS6174 | Counterfeit electronic / materiel | Module 1 |
| NIST SP | 800-171 r3 | CUI protection | Section 4.11, 9 |
| IATA DGR | (current ed.) | Dangerous goods by air | user_certifications |

---

## 17. Appendix B — Glossary of Internal Acronyms

| Acronym | Meaning |
| --- | --- |
| TWU | TurbineWorks University — this LMS |
| TW-OPS | TurbineWorks Operations — sister internal platform (QMS, SOPs, etc.) |
| QAM | Quality Assurance Manual — TurbineWorks top-level quality document |
| OJT | On-the-Job Training |
| SUP | Suspected Unapproved Part |
| AO | Accreditation Organization (e.g., ASA) |
| DGR | Dangerous Goods Regulations (IATA) |
| DGD | Dangerous Goods Declaration |
| ESD | Electrostatic Discharge |
| CUI | Controlled Unclassified Information |
| LLP | Life Limited Part |
| FTS | Full Text Search (Postgres tsvector) |
| RLS | Row Level Security (Postgres) |
| PITR | Point In Time Recovery |
| RBAC | Role Based Access Control |
| MFA | Multi-Factor Authentication |
| TOTP | Time-based One Time Password |
| FAA | Federal Aviation Administration |
| AC | Advisory Circular (FAA) |
| ASA | Aviation Suppliers Association |
| ASA-100 | ASA Quality Standard for Aircraft Parts Distributors |
| DOT | Department of Transportation |
| DFARS | Defense Federal Acquisition Regulation Supplement |
| NIST | National Institute of Standards and Technology |
| ALCOA+ | Attributable, Legible, Contemporaneous, Original, Accurate (plus Complete, Consistent, Enduring, Available) |

---

## Sources

Compliance and standards:
- [ASA-100 — Aviation Suppliers Association](https://www.aviationsuppliers.org/asa-100)
- [ASA-100 distributor records retention — 7 years](https://aviation-suppliers.org/2016/03/02/832/)
- [Sofema — ASA-100 Accreditation for Parts Distributors](https://sofemaaviation.com/blog/asa-100-accreditation-for-parts-distributors)
- [FAA AC 00-56B (PDF)](https://www.faa.gov/documentlibrary/media/advisory_circular/ac_00-56b.pdf)
- [FAA — Voluntary Industry Distributor Accreditation Program](https://www.faa.gov/aircraft/safety/programs/AC00-56)
- [eCFR — 21 CFR Part 11](https://www.ecfr.gov/current/title-21/chapter-I/subchapter-A/part-11)
- [eCFR — 49 CFR §172.704](https://www.ecfr.gov/current/title-49/subtitle-B/chapter-I/subchapter-C/part-172/subpart-H/section-172.704)
- [eCFR — 14 CFR §145.219](https://www.ecfr.gov/current/title-14/chapter-I/subchapter-H/part-145/subpart-E/section-145.219)
- [DFARS 252.246-7007 — Counterfeit Electronic Part Detection](https://www.acquisition.gov/dfars/252.246-7007-contractor-counterfeit-electronic-part-detection-and-avoidance-system.)
- [NIST SP 800-171 r3](https://csrc.nist.gov/pubs/sp/800/171/r3/final)
- [IATA Dangerous Goods Regulations — Recurrent training](https://www.iata.org/en/training/courses/dgr-acceptance-recurrent/dgc022veen02/en/)
- [ANSI/ESD S20.20-2021 overview](https://blog.ansi.org/ansi/ansi-esd-s20-20-2021-protection-electronic-parts/)
- [ALCOA+ Principles — IntuitionLabs](https://intuitionlabs.ai/articles/alcoa-plus-gxp-data-integrity)
- [21 CFR Part 11 Audit Trail requirements — SimplerQMS](https://simplerqms.com/21-cfr-part-11-audit-trail/)
- [WCAG 2.1 AA Checklist — Accessible.org](https://accessible.org/wcag/)
- [LMS WCAG compliance guide — Accessiblu](https://www.accessiblu.com/insights/ultimate-guide-for-an-accessible-lms/)

Tech stack:
- [Better Auth vs NextAuth vs Clerk — Supastarter](https://supastarter.dev/blog/better-auth-vs-nextauth-vs-clerk)
- [Best Auth Library for Next.js 2026 — LogRocket](https://blog.logrocket.com/best-auth-library-nextjs-2026/)
- [Drizzle ORM with Postgres in Next.js 15 — Strapi](https://strapi.io/blog/how-to-use-drizzle-orm-with-postgresql-in-a-nextjs-15-project)
- [Drizzle Row-Level Security docs](https://orm.drizzle.team/docs/rls)
- [Resend vs SendGrid vs Postmark pricing — BuildMVPFast](https://www.buildmvpfast.com/api-costs/email)
- [Inngest vs Trigger.dev vs Vercel Cron — HashBuilds](https://www.hashbuilds.com/articles/next-js-background-jobs-inngest-vs-trigger-dev-vs-vercel-cron)
- [Puppeteer vs @react-pdf/renderer comparison — DEV](https://dev.to/iurii_rogulia/pdf-generation-on-the-server-puppeteer-vs-react-pdfrenderer-a-production-comparison-44cg)
- [Vercel vs Railway pricing — 13Labs](https://www.13labs.au/compare/railway-vs-vercel)
- [Ed25519 — Daniel J. Bernstein](https://ed25519.cr.yp.to/)
- [Tamper-evident audit trails with PostgreSQL — AppMaster](https://appmaster.io/blog/tamper-evident-audit-trails-postgresql)
- [Immutable audit log with HMAC hash chaining — Tracehold](https://tracehold.ai/blog/immutable-audit-log-hmac-hash-chain/)
- [Trillian — append-only transparency log](https://transparency.dev/)
