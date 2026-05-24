<?php
// TurbineWorks University — lesson content for ASA-100 Initial Training.
//
// Structure: array of [course idnumber => list of lesson objects]
// Each lesson becomes a Moodle Page activity (mod_page) attached to the
// course's first section. Pages are completion-tracked (mark complete on
// view). The bootstrap script seeds them idempotently by name.
//
// Content is grounded in:
//   - FAA AC 00-56B (Voluntary Industry Distributor Accreditation Program)
//   - FAA AC 20-62E (Eligibility, Quality, and Identification of Aero. Replacement Parts)
//   - FAA AC 21-29D (Detecting and Reporting Suspected Unapproved Parts)
//   - FAA AC 21-38  (Disposition of Unsalvageable Aircraft Parts)
//   - ASA-100 standard (current revision)
//   - SAE AS5553 / AS6174 (counterfeit prevention)
//   - ANSI/ESD S20.20 (ESD control programs)
//   - 49 CFR Parts 100-185 (DOT hazmat) / IATA DGR
//
// Sections marked "[TurbineWorks Procedure Reference]" are placeholders for
// content that must be authored by TurbineWorks QA personnel because it ties
// to the QAM (Quality Assurance Manual) — the audit will check that training
// content matches actual procedures, not generic industry text.

defined('MOODLE_INTERNAL') || die();

/**
 * Return the full course-content map.
 *
 * Each value is an indexed list (preserves order — lessons appear in Moodle
 * in this sequence). The bootstrap script reads this and creates one mod_page
 * per lesson.
 *
 * To add a new lesson: append to the relevant course's array.
 * To revise a lesson: edit the 'content' HTML and bump the marker version
 * in bootstrap.php so the bootstrap re-runs.
 */
function local_twu_get_lessons(): array {
    return [
        'TWF4-3' => [
            [
                'name' => 'Lesson 3.1 — What is ASA-100?',
                'intro' => '<p>An introduction to the ASA-100 standard and where it fits in the FAA / aviation regulatory landscape.</p>',
                'content' => <<<HTML
<h3>What is ASA-100?</h3>
<p><strong>ASA-100</strong> is the <em>Quality Assurance Standard for Aircraft Parts Distributors</em>, published and maintained by the
<a href="https://www.aviationsuppliers.org/" target="_blank" rel="noopener">Aviation Suppliers Association (ASA)</a>.
It defines the quality-system requirements that a distributor of aircraft parts must implement to demonstrate that the parts it sells
are traceable, properly handled, and eligible for installation on civil aircraft.</p>

<h4>Why does ASA-100 exist?</h4>
<p>Anyone can buy a turbine blade on the open market and resell it. Without a standard for how that blade is documented, inspected,
stored, and shipped, there is no way for the airline buying it to know:</p>
<ul>
  <li>Where the part originally came from (an FAA-approved manufacturer? a teardown of a high-time engine? a scrapyard?)</li>
  <li>Whether the documentation is real or fabricated</li>
  <li>Whether the part has been damaged in storage</li>
  <li>Whether the part has been mislabeled, mixed up, or substituted for a counterfeit</li>
</ul>
<p>ASA-100 standardizes the answers to all of these questions. A distributor accredited to ASA-100 has proven, through a third-party
audit, that they follow a documented quality system covering receiving inspection, traceability, storage, recordkeeping, and shipping.</p>

<h4>ASA-100 is voluntary — but effectively required</h4>
<p>ASA-100 is a <em>voluntary</em> industry standard, not an FAA regulation. The FAA does not mandate that distributors be accredited.
However, virtually every airline, MRO (Maintenance, Repair, and Overhaul facility), and government customer requires their parts
suppliers to hold an FAA-recognized accreditation under <strong>FAA Advisory Circular 00-56</strong> — and ASA-100 is the most
widely adopted accreditation program under that AC.</p>
<p>In practice: if TurbineWorks wants to sell parts to commercial operators, ASA-100 accreditation (or an equivalent like AS9120)
is a contractual prerequisite. The standard isn't just a quality nicety — it's a market-access requirement.</p>

<h4>What ASA-100 covers (at a glance)</h4>
<ul>
  <li><strong>Section 6 — Receiving Inspection.</strong> What must be checked when a part comes in the door, what documentation
      must accompany it, and how non-conforming parts are quarantined.</li>
  <li><strong>Section 7 — Storage and Handling.</strong> ESD control, shelf-life monitoring, segregation of serviceable vs.
      non-serviceable parts, FOD (Foreign Object Damage) prevention.</li>
  <li><strong>Section 8 — Recordkeeping.</strong> What documentation must be retained, for how long (typically 7+ years), and
      how records must be made available during an audit.</li>
  <li><strong>Internal audits, corrective action, and management review</strong> — the standard requires the distributor to audit
      itself on a regular cadence and act on findings.</li>
</ul>

<h4>Key takeaways</h4>
<ul>
  <li>ASA-100 is the quality standard that lets TurbineWorks sell parts to commercial operators.</li>
  <li>It is voluntary in regulation but effectively mandatory in the marketplace.</li>
  <li>It is built on top of FAA Advisory Circular 00-56 (covered in detail in Module 6).</li>
  <li>Compliance is proven by passing a third-party on-site audit, then re-audited periodically.</li>
</ul>

<p><em>Continue to Lesson 3.2 — The FAA AC 00-56 Framework.</em></p>
HTML
            ],
            [
                'name' => 'Lesson 3.2 — The FAA AC 00-56 Framework',
                'intro' => '<p>How FAA Advisory Circular 00-56 created the accreditation framework that ASA-100 implements.</p>',
                'content' => <<<HTML
<h3>FAA Advisory Circular 00-56: The Framework</h3>
<p><strong>FAA AC 00-56B</strong> — "Voluntary Industry Distributor Accreditation Program" — is the FAA document that defines
the framework for industry-managed accreditation of aircraft parts distributors. It is the regulatory backbone that ASA-100
(and competing standards like AS9120) implement.</p>

<h4>Why the FAA wrote AC 00-56</h4>
<p>In the 1980s and 1990s, the FAA identified a recurring problem: counterfeit, mislabeled, and stolen parts were entering the
civil aviation supply chain through unaccredited distributors. The FAA's own resources could not realistically inspect every
parts distributor in the country. Instead, the FAA chose to recognize <em>industry-led</em> accreditation programs that would
audit distributors on the FAA's behalf, against published standards.</p>
<p>AC 00-56 defines:</p>
<ul>
  <li>What an accreditation program must require of its distributors</li>
  <li>How accreditation organizations (like ASA) become FAA-recognized</li>
  <li>What audit procedures the accreditation organization must use</li>
  <li>How the FAA monitors the accreditation organizations themselves</li>
</ul>

<h4>How AC 00-56 connects to ASA-100</h4>
<p>The Aviation Suppliers Association applied to the FAA in the 1990s to be a recognized accreditation organization under AC 00-56.
ASA published the ASA-100 standard as its accreditation criteria. The FAA reviewed ASA's program and added ASA to its list of
recognized accreditation organizations.</p>
<p>The chain looks like this:</p>
<pre>
FAA AC 00-56B  ──►  defines accreditation framework
       │
       ▼
ASA      ──►  FAA-recognized accreditation organization
       │
       ▼
ASA-100 standard  ──►  what distributors must comply with
       │
       ▼
TurbineWorks QAM  ──►  how TurbineWorks specifically implements ASA-100
       │
       ▼
SOPs and work instructions  ──►  what each employee does day-to-day
</pre>

<h4>Other accreditation programs under AC 00-56</h4>
<p>ASA-100 is not the only accreditation standard recognized under AC 00-56. Others include:</p>
<ul>
  <li><strong>AS9120</strong> — the aerospace-industry QMS standard for distributors (managed by SAE / IAQG). Often pursued
      <em>in addition to</em> ASA-100 by distributors selling into the OEM supply chain.</li>
  <li><strong>TAC-2000</strong> — Transportation Association of Canada equivalent.</li>
  <li><strong>FAA AC 00-56B Appendix 1 list</strong> — the current authoritative list of recognized accreditation organizations
      is maintained by the FAA and updated periodically.</li>
</ul>

<h4>What this means for TurbineWorks employees</h4>
<p>When an ASA auditor visits TurbineWorks for accreditation, they are checking compliance against the ASA-100 standard. But
ASA's authority to audit TurbineWorks at all comes from the FAA's recognition under AC 00-56. An employee asked "what regulation
requires this?" can answer accurately: <em>"This procedure implements ASA-100, which is the standard the FAA recognizes under
AC 00-56 for distributor accreditation."</em></p>

<h4>Key takeaways</h4>
<ul>
  <li>AC 00-56 is the FAA document that created the accreditation framework.</li>
  <li>ASA is one of several FAA-recognized accreditation organizations under AC 00-56.</li>
  <li>ASA-100 is ASA's published standard — the actual criteria TurbineWorks must comply with.</li>
  <li>TurbineWorks' QAM tells the auditor <em>how</em> TurbineWorks complies with ASA-100.</li>
</ul>

<p><em>Continue to Lesson 3.3 — The Accreditation Process.</em></p>
HTML
            ],
            [
                'name' => 'Lesson 3.3 — The Accreditation Process',
                'intro' => '<p>What happens during initial accreditation and recurring audits, and what an auditor looks for.</p>',
                'content' => <<<HTML
<h3>The ASA-100 Accreditation Process</h3>
<p>This lesson walks through what TurbineWorks goes through to <em>become</em> ASA-100 accredited and what happens at
recurring audits afterward. Understanding the process helps every employee know <em>why</em> certain procedures exist and what
an auditor will be looking for.</p>

<h4>Phase 1 — Application and Document Submission</h4>
<p>TurbineWorks submits an application to ASA along with the complete Quality Assurance Manual (QAM), procedures, and supporting
documents. ASA reviews these for completeness and conformance to the ASA-100 standard <em>on paper</em> before scheduling an
on-site audit.</p>
<p>Common findings at this stage:</p>
<ul>
  <li>QAM does not address every section of ASA-100</li>
  <li>Procedures reference forms that don't exist or aren't controlled</li>
  <li>Training program is described but no records exist of actual training delivery</li>
  <li>Records-retention policy doesn't meet the 7+ year expectation</li>
</ul>

<h4>Phase 2 — On-Site Audit</h4>
<p>An ASA-qualified auditor visits TurbineWorks (typically 1–3 days depending on company size) and reviews:</p>
<ol>
  <li><strong>Receiving inspection in action</strong> — the auditor watches a real receiving inspection from start to finish.
      Does the inspector follow the procedure? Are the right documents checked? Is the tagging correct?</li>
  <li><strong>Storage and traceability</strong> — the auditor pulls random parts from inventory and asks "show me the
      traceability for this part." Every record from receipt through current storage must be available within minutes.</li>
  <li><strong>Records</strong> — the auditor reviews completed inspection records, COCs, 8130-3 tags, shipping documents,
      training records, internal audit records, and corrective-action records.</li>
  <li><strong>Personnel interviews</strong> — the auditor speaks with employees and asks what their role is, where their
      procedures are documented, and what training they have completed.</li>
  <li><strong>SUP / disposition handling</strong> — the auditor specifically checks how suspected unapproved parts have been
      handled and how unsalvageable parts are disposed of (mutilated or scrapped per FAA AC 21-38).</li>
</ol>

<h4>Phase 3 — Findings and Corrective Action</h4>
<p>The auditor issues findings (non-conformances) and observations. For initial accreditation, all major findings must be
corrected before the accreditation is granted. TurbineWorks submits a corrective-action plan, evidence of implementation, and
the auditor verifies (either remotely or at a follow-up visit).</p>

<h4>Phase 4 — Accreditation Granted</h4>
<p>Once findings are closed, ASA issues the accreditation certificate. TurbineWorks is then listed in the public ASA-accredited
distributor database, which is the proof customers check before placing orders.</p>

<h4>Phase 5 — Recurring Audits</h4>
<p>Accreditation is not permanent. ASA-100 accreditation is typically renewed every 3 years with a full re-audit, and ASA
reserves the right to conduct interim audits without notice if customer complaints, FAA referrals, or other concerns arise.</p>

<h4>What every employee should know</h4>
<ul>
  <li><strong>The auditor may interview you.</strong> Be honest. The right answer is often "I follow procedure X, which is in
      the QAM at section Y." Auditors are not trying to trick you — they are checking that the system actually works.</li>
  <li><strong>Don't make things up.</strong> If you don't know the answer, say "I don't know — let me find out." A culture of
      honest answers is what passes audits.</li>
  <li><strong>The training you complete in TurbineWorks University is part of the audit.</strong> Your completion records will
      be reviewed by the auditor as evidence that the company's training program is being executed.</li>
  <li><strong>Findings are normal.</strong> Even mature ASA-100 distributors get findings at most audits. The question is
      whether the company responds quickly and effectively.</li>
</ul>

<p><em>Continue to Lesson 3.4 — Roles, Responsibilities, and the QAM.</em></p>
HTML
            ],
            [
                'name' => 'Lesson 3.4 — Roles, Responsibilities, and the QAM',
                'intro' => '<p>Who is responsible for what under ASA-100, and how the Quality Assurance Manual ties it all together.</p>',
                'content' => <<<HTML
<h3>Roles, Responsibilities, and the QAM</h3>
<p>ASA-100 requires that every distributor define <em>specific roles</em> with specific responsibilities, and document them in
the Quality Assurance Manual. The auditor will check that the people named in the QAM are actually doing what the QAM says
they do — and that anyone <em>not</em> named in the QAM is not making quality decisions.</p>

<h4>Standard ASA-100 Roles</h4>
<table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse;">
  <tr style="background:#0d2240; color:#fff;">
    <th>Role</th><th>Typical Responsibilities</th>
  </tr>
  <tr>
    <td><strong>Accountable Manager</strong></td>
    <td>Has corporate authority to commit resources to maintain ASA-100 compliance. Usually the CEO, President, or General
        Manager. Signs the QAM. Ultimately responsible for the quality system.</td>
  </tr>
  <tr>
    <td><strong>Quality Assurance Manager</strong></td>
    <td>Day-to-day owner of the quality system. Maintains the QAM, manages internal audits, manages corrective action, owns
        the relationship with the accreditation body. Independent authority to stop shipments.</td>
  </tr>
  <tr>
    <td><strong>Receiving Inspector(s)</strong></td>
    <td>Performs incoming-parts inspection per the QAM. Authorized to accept, reject, or quarantine parts. Maintains
        receiving inspection records.</td>
  </tr>
  <tr>
    <td><strong>Warehouse / Storage personnel</strong></td>
    <td>Maintains storage conditions (ESD, hazmat, shelf-life). Maintains FOD-free environment. Maintains physical segregation
        of serviceable vs. quarantined parts.</td>
  </tr>
  <tr>
    <td><strong>Shipping personnel</strong></td>
    <td>Ensures outbound shipments include correct documentation, packaging meets requirements (including hazmat where
        applicable), and traceability is maintained through delivery.</td>
  </tr>
  <tr>
    <td><strong>Sales / Customer Service</strong></td>
    <td>Often the first to hear customer complaints. Must escalate complaints into the corrective-action system. May not
        promise parts to customers without QA confirmation of availability and condition.</td>
  </tr>
</table>

<h4>The Quality Assurance Manual (QAM)</h4>
<p>The QAM is the single document that ties together everything about how TurbineWorks complies with ASA-100. The auditor
treats the QAM as the authoritative description of the company's quality system. Anything you do that contradicts the QAM is
a non-conformance — even if what you are doing is "better."</p>
<p>The QAM typically contains:</p>
<ul>
  <li>Company overview, scope of accreditation (what parts, what services)</li>
  <li>Organizational chart showing reporting lines for quality decisions</li>
  <li>Procedures cross-referenced to each section of ASA-100</li>
  <li>Forms referenced (every TWF-XX form, like the TWF-4 training record, lives in the QAM)</li>
  <li>Document control: how revisions are approved, distributed, and obsolete versions retrieved</li>
  <li>Records-retention schedule</li>
</ul>

<h4>The hierarchy of documents</h4>
<pre>
14 CFR (FARs)               ← Law. Always wins in conflict.
   │
FAA Advisory Circulars      ← FAA guidance. Authoritative interpretation.
   │
ASA-100 Standard            ← Industry standard we are accredited to.
   │
TurbineWorks QAM            ← How we comply with ASA-100.
   │
SOPs / Work Instructions    ← Step-by-step for specific tasks.
   │
Forms / Records             ← Evidence the work was done correctly.
</pre>
<p>When two documents conflict, the higher level wins. If the QAM says "do X" and an SOP says "do Y," the QAM wins (and the
SOP needs to be corrected).</p>

<h4>What every employee should know</h4>
<ul>
  <li>Know <strong>which role</strong> in the QAM applies to you. If your job title in the QAM is "Receiving Inspector,"
      the auditor will hold you accountable for what the QAM says a Receiving Inspector does.</li>
  <li>Know <strong>where the QAM lives</strong> and how to find your section. Auditors regularly ask "show me where the QAM
      describes your job."</li>
  <li><strong>Don't make quality decisions outside your role.</strong> If you're not the QA Manager, you don't approve a
      non-conforming part for use. Refer it up.</li>
  <li>If you spot a conflict between the QAM and what's actually happening, <strong>raise it.</strong> That's how the system
      improves. It is not an accusation — it's the corrective-action system working as designed.</li>
</ul>

<p><em>[TurbineWorks Procedure Reference: insert link to the current TurbineWorks QAM and organizational chart here once
the QAM is published in the document control system.]</em></p>
HTML
            ],
            [
                'name' => 'Lesson 3.5 — Module 3 Summary and Key References',
                'intro' => '<p>Recap, key terms, and the canonical reference documents for ASA-100 familiarization.</p>',
                'content' => <<<HTML
<h3>Module 3 Summary</h3>
<p>You have now completed the four content lessons in this module. Before moving on:</p>

<h4>What you should now be able to answer</h4>
<ol>
  <li>What is ASA-100, and who publishes it?</li>
  <li>Why does the FAA defer to industry-managed accreditation programs instead of inspecting distributors directly?</li>
  <li>What is the relationship between FAA AC 00-56, ASA, and the ASA-100 standard?</li>
  <li>What are the five major phases of the ASA-100 accreditation process?</li>
  <li>What is the Quality Assurance Manual (QAM), and why is it the authoritative reference?</li>
  <li>What is the document hierarchy from the FARs down to forms? Which wins in a conflict?</li>
</ol>

<h4>Key Terms</h4>
<dl>
  <dt><strong>ASA-100</strong></dt>
  <dd>Quality Assurance Standard for Aircraft Parts Distributors, published by the Aviation Suppliers Association.</dd>

  <dt><strong>FAA AC 00-56B</strong></dt>
  <dd>The FAA Advisory Circular establishing the Voluntary Industry Distributor Accreditation Program — the framework under
      which accreditation organizations like ASA operate.</dd>

  <dt><strong>Accreditation Organization (AO)</strong></dt>
  <dd>An FAA-recognized organization (like ASA) that audits distributors against a published standard and issues accreditation.</dd>

  <dt><strong>QAM (Quality Assurance Manual)</strong></dt>
  <dd>TurbineWorks' top-level quality document, describing how TurbineWorks complies with ASA-100. The auditor's primary
      reference during an on-site audit.</dd>

  <dt><strong>Accountable Manager</strong></dt>
  <dd>The senior company official (CEO or equivalent) who is corporately responsible for the quality system and has authority
      to commit the resources needed to maintain it.</dd>

  <dt><strong>Non-conformance / Finding</strong></dt>
  <dd>A documented deviation from the requirements of ASA-100 or the QAM. Findings drive corrective action.</dd>

  <dt><strong>Corrective Action</strong></dt>
  <dd>The documented investigation, root-cause analysis, and remediation that follows a finding. ASA-100 requires a formal
      corrective-action system.</dd>
</dl>

<h4>Reference Documents (External Links)</h4>
<ul>
  <li><a href="https://www.aviationsuppliers.org/" target="_blank" rel="noopener">Aviation Suppliers Association (ASA)</a> —
      the publisher of ASA-100 and the accreditation body.</li>
  <li><a href="https://www.faa.gov/regulations_policies/advisory_circulars/index.cfm/go/document.information/documentID/74250"
      target="_blank" rel="noopener">FAA AC 00-56B</a> — Voluntary Industry Distributor Accreditation Program.</li>
  <li><a href="https://www.faa.gov/regulations_policies/advisory_circulars/index.cfm/go/document.information/documentID/22182"
      target="_blank" rel="noopener">FAA AC 20-62E</a> — Eligibility, Quality, and Identification of Aero. Replacement Parts.</li>
  <li><a href="https://www.faa.gov/regulations_policies/advisory_circulars/index.cfm/go/document.information/documentID/1019042"
      target="_blank" rel="noopener">FAA AC 21-29D</a> — Detecting and Reporting Suspected Unapproved Parts (covered in
      detail in Module 1).</li>
</ul>

<h4>What's next</h4>
<p>Module 3 establishes the regulatory and organizational context. The next modules go into the specific operational areas:</p>
<ul>
  <li><strong>Module 1</strong> — Unapproved Parts &amp; Counterfeit Materials (FAA AC 21-29D)</li>
  <li><strong>Module 2</strong> — Receiving and Shipping Inspection (ASA-100 §6)</li>
  <li><strong>Module 4</strong> — Parts and Warehousing (ASA-100 §7)</li>
  <li><strong>Module 5</strong> — Recordkeeping (ASA-100 §8)</li>
  <li><strong>Module 6</strong> — FAA AC 00-56 (deep dive on the framework introduced here)</li>
  <li><strong>Module 7</strong> — ESD Handling (ANSI/ESD S20.20)</li>
  <li><strong>Module 8</strong> — Hazmat Identification (49 CFR / IATA DGR)</li>
</ul>

<p><em>[TurbineWorks Procedure Reference: the Quality Assurance Manager should review and sign off on this module's content
before it is delivered as part of ASA-100 initial training. Substitute generic references with the specific TurbineWorks QAM
section numbers once the QAM is finalized.]</em></p>
HTML
            ],
        ],
        // Other 7 Initial Training modules currently ship with a "module
        // overview" welcome page only. Full lesson content for each lands
        // after Module 3's depth/style is approved.
        'TWF4-1' => [local_twu_overview_lesson(
            'Module 1 — Unapproved Parts &amp; Counterfeit Materials',
            'FAA AC 21-29D, SAE AS5553, SAE AS6174',
            '1 hour',
            [
                'Define unapproved parts, suspected unapproved parts (SUP), and counterfeit parts',
                'Recognize the red flags in documentation and physical parts that indicate possible SUP',
                'Follow the FAA SUP reporting process (FAA Form 8120-11) and TurbineWorks internal escalation',
                'Apply SAE AS5553 / AS6174 counterfeit-prevention controls in the supply chain',
            ],
            'Why this matters: a single counterfeit or unapproved part in service can ground an aircraft and expose TurbineWorks and its customer to FAA enforcement action, civil liability, and criminal prosecution. ASA auditors specifically test SUP handling at every on-site audit.'
        )],
        'TWF4-2' => [local_twu_overview_lesson(
            'Module 2 — Receiving and Shipping Inspection',
            'ASA-100 §6, FAA 8130-3, EASA Form 1, TCCA Form One',
            '1 hour',
            [
                'Follow the ASA-100 §6 receiving inspection sequence per the TurbineWorks QAM',
                'Verify FAA 8130-3 Airworthiness Approval Tags block-by-block',
                'Recognize valid EASA Form 1 and TCCA Form One documentation',
                'Handle Certificates of Conformance (COC) and non-conforming material correctly',
                'Apply shipping packaging and documentation requirements for outbound parts',
            ],
            'Receiving inspection is the single highest-leverage quality control point at TurbineWorks. A part that passes receiving inspection without proper documentation cannot be unwound later — it contaminates inventory, traceability, and customer trust.'
        )],
        'TWF4-4' => [local_twu_overview_lesson(
            'Module 4 — Parts and Warehousing',
            'ASA-100 §7, ATA Spec 300, FOD prevention best practices',
            '1 hour',
            [
                'Maintain serviceable, quarantined, and unserviceable parts in physical segregation',
                'Apply shelf-life monitoring procedures for elastomers, adhesives, and time-controlled items',
                'Prevent Foreign Object Damage (FOD) in the warehouse and packaging areas',
                'Use ATA Spec 300 packaging and the TurbineWorks-approved container types',
                'Handle hazardous materials in storage per 49 CFR and the TurbineWorks hazmat SOP',
            ],
            'Storage is where good incoming inspection gets undone. A correctly-inspected part stored without ESD protection, or beyond shelf-life, becomes unsellable. Warehouse discipline is the difference between an inventory and a write-off.'
        )],
        'TWF4-5' => [local_twu_overview_lesson(
            'Module 5 — Recordkeeping',
            'ASA-100 §8, FAA AC 21-38, 14 CFR Part 21',
            '1 hour',
            [
                'Apply the ASA-100 §8 records-retention schedule (7+ years for most records)',
                'Maintain back-to-birth traceability for life-limited parts (LLPs)',
                'Document the disposition of unsalvageable parts per FAA AC 21-38 (mutilation, scrap records)',
                'Use the TurbineWorks document-control system (revision tracking, approval signatures)',
                'Produce audit-ready records on demand for an ASA inspector or customer auditor',
            ],
            'When an auditor or customer asks "show me the traceability for this part," the answer has to be available within minutes — not "let me dig through three years of paperwork." Recordkeeping discipline is what makes TurbineWorks <em>auditable</em>.'
        )],
        'TWF4-6' => [local_twu_overview_lesson(
            'Module 6 — FAA AC 00-56',
            'FAA Advisory Circular 00-56B, 14 CFR Part 21, ASA-100',
            '2 hours',
            [
                'Read FAA AC 00-56B and locate the requirements it places on accreditation organizations and distributors',
                'Map AC 00-56 requirements to the corresponding ASA-100 sections and TurbineWorks QAM sections',
                'Understand the FAA\'s oversight model: why industry accreditation exists and how it is monitored',
                'Recognize the consequences of falling out of accreditation (loss of customer access, FAA enforcement)',
            ],
            'Module 3 introduced AC 00-56 as the framework. Module 6 is the deep dive — required as the longest module (2 hours) because every other ASA-100 procedure ultimately traces back to a specific requirement in this Advisory Circular.'
        )],
        'TWF4-7' => [local_twu_overview_lesson(
            'Module 7 — ESD Handling',
            'ANSI/ESD S20.20, IPC J-STD-033, ASA ESD Best Practices',
            '1 hour',
            [
                'Recognize ESD-sensitive parts (avionics components, certain bearings, electronic engine controls)',
                'Use the TurbineWorks ESD control program: wrist straps, mats, grounded workstations',
                'Apply ESD-safe packaging (pink poly, shielded bags, anti-static foam) correctly',
                'Identify ESD damage symptoms vs. ESD-safe handling failures',
                'Handle moisture-sensitive devices per IPC J-STD-033 (where applicable)',
            ],
            'A turbine engine controller fried by an ungrounded technician looks identical to one in working condition — until it fails in service. ESD damage is almost always latent and undetectable until the part is energized in an aircraft.'
        )],
        'TWF4-8' => [local_twu_overview_lesson(
            'Module 8 — Hazmat Identification',
            '49 CFR Parts 100-185 (DOT), IATA Dangerous Goods Regulations, IMDG (sea)',
            '1 hour',
            [
                'Identify the 9 DOT hazard classes and which apply to aerospace parts (lithium batteries, oxygen generators, etc.)',
                'Apply correct UN proper shipping name, class, packing group, and labels',
                'Use the current edition of the IATA DGR for air shipments',
                'Recognize hidden hazmat in seemingly innocuous parts (life vests, ELTs, oxygen bottles)',
                'Complete the Shipper\'s Declaration for Dangerous Goods correctly',
            ],
            'Hazmat is the only ASA-100 area where a single mistake — a mislabeled lithium battery shipment — can result in a fire on an aircraft and a federal felony charge. The training cadence reflects the risk: full re-training every 24 months minimum per DOT, more frequent here.'
        )],
    ];
}

/**
 * Helper: build the standard "Module Overview" page for a course whose full
 * lesson content has not been written yet. Gives empty courses a real face
 * — learning objectives, standards covered, duration — so an auditor or
 * employee landing on the course sees intent, not a placeholder.
 */
function local_twu_overview_lesson(string $title, string $standards, string $duration,
                                   array $objectives, string $why): array {
    $objectiveshtml = '';
    foreach ($objectives as $o) {
        $objectiveshtml .= "  <li>" . $o . "</li>\n";
    }
    $content = <<<HTML
<h3>{$title}</h3>

<div style="background:#f4f6fa; border-left:4px solid #0d2240; padding:14px 18px; margin:14px 0;">
  <p style="margin:0 0 6px 0;"><strong>Governing standards:</strong> {$standards}</p>
  <p style="margin:0;"><strong>Estimated duration:</strong> {$duration}</p>
</div>

<h4>Learning Objectives</h4>
<p>After completing this module you will be able to:</p>
<ul>
{$objectiveshtml}</ul>

<h4>Why this module exists</h4>
<p>{$why}</p>

<h4>Status</h4>
<p style="background:#fff8e1; border:1px solid #ffc800; padding:10px 14px; border-radius:4px;">
  <strong>Full lesson content is being authored.</strong> This module overview is the audit-evidence
  placeholder — it documents that the module exists in the training program, what standards it covers,
  and what learning objectives it satisfies. Detailed lesson content will follow as TurbineWorks QA
  finalizes the corresponding QAM procedure references.
</p>

<h4>What to do now</h4>
<ul>
  <li>Read Module 3 — ASA-100 Familiarization first if you have not already. It establishes the
      regulatory framework that every other module depends on.</li>
  <li>Visit the <em>Reference Library</em> category for the official source documents this module
      will cover (FAA Advisory Circulars, ASA standards, ANSI/ESD S20.20, etc.).</li>
  <li>If you have questions about the current state of TurbineWorks procedures in this area before
      the full lesson content is available, contact the Quality Assurance Manager.</li>
</ul>
HTML;

    return [
        'name'    => $title,
        'intro'   => '<p>Module overview, learning objectives, and governing standards. Full lesson content in development.</p>',
        'content' => $content,
    ];
}

/**
 * Reference Library content — pages added to courses in the
 * Reference Library category. These exist so employees can find the official
 * source documents in one place instead of hunting for FAA.gov URLs.
 */
function local_twu_get_reference_library(): array {
    return [
        [
            'shortname' => 'TWU-REF-FAA-AC',
            'fullname'  => 'FAA Advisory Circulars (Reference)',
            'idnumber'  => 'TWU-REF-FAA-AC',
            'summary'   => '<p>Direct links to the FAA Advisory Circulars that govern aircraft parts distribution and the ASA-100 framework.</p>',
            'lessons'   => [
                [
                    'name'  => 'AC 00-56B — Voluntary Industry Distributor Accreditation Program',
                    'intro' => '<p>The framework that recognizes ASA as an accreditation organization.</p>',
                    'content' => '<h3>FAA AC 00-56B</h3><p><strong>Title:</strong> Voluntary Industry Distributor Accreditation Program</p><p>Defines the FAA-recognized framework under which industry organizations (like ASA) accredit distributors. ASA-100 is one of several standards published under this AC.</p><p><strong>Official document:</strong> <a href="https://www.faa.gov/regulations_policies/advisory_circulars/index.cfm/go/document.information/documentID/74250" target="_blank" rel="noopener">faa.gov — AC 00-56B</a></p><p>Required reading for: all employees performing receiving inspection, quality assurance, or management roles.</p>',
                ],
                [
                    'name'  => 'AC 20-62E — Eligibility, Quality, and Identification of Aero. Replacement Parts',
                    'intro' => '<p>What makes a part eligible for installation on a type-certificated aircraft.</p>',
                    'content' => '<h3>FAA AC 20-62E</h3><p><strong>Title:</strong> Eligibility, Quality, and Identification of Aeronautical Replacement Parts</p><p>The FAA\'s guidance on what makes a part legally eligible for installation. Defines acceptable provenance: PMA, TSO, surplus from approved sources, etc.</p><p><strong>Official document:</strong> <a href="https://www.faa.gov/regulations_policies/advisory_circulars/index.cfm/go/document.information/documentID/22182" target="_blank" rel="noopener">faa.gov — AC 20-62E</a></p>',
                ],
                [
                    'name'  => 'AC 21-29D — Detecting and Reporting Suspected Unapproved Parts',
                    'intro' => '<p>How to identify SUP and the FAA reporting process.</p>',
                    'content' => '<h3>FAA AC 21-29D</h3><p><strong>Title:</strong> Detecting and Reporting Suspected Unapproved Parts</p><p>Defines what constitutes a Suspected Unapproved Part, the red flags to look for, and the FAA Form 8120-11 reporting process. Required reading before performing receiving inspection.</p><p><strong>Official document:</strong> <a href="https://www.faa.gov/regulations_policies/advisory_circulars/index.cfm/go/document.information/documentID/1019042" target="_blank" rel="noopener">faa.gov — AC 21-29D</a></p>',
                ],
                [
                    'name'  => 'AC 21-38 — Disposition of Unsalvageable Aircraft Parts',
                    'intro' => '<p>How unsalvageable parts must be disposed of to prevent re-entry into the supply chain.</p>',
                    'content' => '<h3>FAA AC 21-38</h3><p><strong>Title:</strong> Disposition of Unsalvageable Aircraft Parts</p><p>Defines mutilation methods and recordkeeping for parts that have been declared beyond economical repair. Critical for preventing SUP re-entry through informal "scrap" channels.</p><p><strong>Official document:</strong> <a href="https://www.faa.gov/regulations_policies/advisory_circulars/index.cfm" target="_blank" rel="noopener">faa.gov — Advisory Circulars index</a> (search for 21-38)</p>',
                ],
            ],
        ],
        [
            'shortname' => 'TWU-REF-STANDARDS',
            'fullname'  => 'Industry Standards (Reference)',
            'idnumber'  => 'TWU-REF-STANDARDS',
            'summary'   => '<p>The non-FAA industry standards that ASA-100 references or that TurbineWorks must comply with.</p>',
            'lessons'   => [
                [
                    'name'  => 'ASA-100 — The Standard',
                    'intro' => '<p>The Quality Assurance Standard for Aircraft Parts Distributors.</p>',
                    'content' => '<h3>ASA-100</h3><p>Published by the Aviation Suppliers Association. The standard TurbineWorks is accredited (or being accredited) against.</p><p><strong>Where to obtain:</strong> ASA member portal at <a href="https://www.aviationsuppliers.org/" target="_blank" rel="noopener">aviationsuppliers.org</a>. The current revision number should match what the audit will be conducted against — confirm with ASA before training rollout.</p><p><strong>Internal copy:</strong> [TurbineWorks Procedure Reference — attach current PDF revision to this page once obtained from ASA.]</p>',
                ],
                [
                    'name'  => 'SAE AS5553 / AS6174 — Counterfeit Prevention',
                    'intro' => '<p>Industry standards for counterfeit electronic parts (AS5553) and materiel broadly (AS6174).</p>',
                    'content' => '<h3>SAE AS5553 &amp; AS6174</h3><p><strong>AS5553:</strong> Counterfeit Electronic Parts; Avoidance, Detection, Mitigation, and Disposition. The seminal industry standard on counterfeit electronic component prevention.</p><p><strong>AS6174:</strong> Counterfeit Materiel; Assuring Acquisition of Authentic and Conforming Materiel. Broader scope than AS5553 — covers all materiel, not just electronics.</p><p>Both are published by SAE International. Available at <a href="https://www.sae.org/" target="_blank" rel="noopener">sae.org</a> (paid). ASA auditors increasingly cite these standards when reviewing supplier qualification procedures.</p>',
                ],
                [
                    'name'  => 'ANSI/ESD S20.20 — ESD Control Program',
                    'intro' => '<p>The authoritative standard for ESD control programs.</p>',
                    'content' => '<h3>ANSI/ESD S20.20</h3><p>The standard against which a facility\'s ESD control program is measured. Covers personnel grounding, workstation grounding, packaging requirements, and program audits.</p><p><strong>Where to obtain:</strong> ESDA (Electrostatic Discharge Association) at <a href="https://www.esda.org/" target="_blank" rel="noopener">esda.org</a>. The ASA ESD Best Practices document references S20.20 as the primary technical standard.</p>',
                ],
                [
                    'name'  => '49 CFR Parts 100-185 — DOT Hazmat',
                    'intro' => '<p>U.S. federal regulations for hazardous materials in transportation.</p>',
                    'content' => '<h3>49 CFR Parts 100-185</h3><p>The U.S. Department of Transportation hazardous materials regulations. Covers classification, packaging, marking, labeling, and shipping documentation for any material that meets the DOT hazmat definition.</p><p><strong>Free online:</strong> <a href="https://www.ecfr.gov/current/title-49/subtitle-B/chapter-I/subchapter-C" target="_blank" rel="noopener">eCFR — Title 49 Subchapter C</a> (the authoritative, always-current source).</p>',
                ],
                [
                    'name'  => 'IATA Dangerous Goods Regulations (DGR)',
                    'intro' => '<p>The reference for air shipment of hazardous materials.</p>',
                    'content' => '<h3>IATA DGR</h3><p>The Dangerous Goods Regulations published annually by the International Air Transport Association. Required reference for any air shipment of hazardous goods. <strong>Each year\'s edition supersedes the previous</strong> — ASA expects the current edition to be on hand.</p><p><strong>Where to obtain:</strong> <a href="https://www.iata.org/en/publications/dgr/" target="_blank" rel="noopener">iata.org/en/publications/dgr</a> (paid annual subscription).</p>',
                ],
            ],
        ],
    ];
}

/**
 * Engine-Parts Specific category content — courses unique to TurbineWorks'
 * focus on aircraft engine parts and components. These supplement the
 * generic ASA-100 modules with type-specific knowledge.
 */
function local_twu_get_engine_parts_courses(): array {
    return [
        [
            'shortname' => 'TWU-ENG-8130',
            'fullname'  => 'FAA 8130-3 Tag — Block-by-Block Inspection',
            'idnumber'  => 'TWU-ENG-8130',
            'summary'   => '<p>The single most-checked document at receiving inspection. Detailed walkthrough of every block on the FAA 8130-3 Airworthiness Approval Tag and how to verify each one.</p>',
            'lessons'   => [
                [
                    'name'  => 'What is a FAA 8130-3 Tag?',
                    'intro' => '<p>The purpose of the FAA 8130-3 and why it is the cornerstone of aviation parts traceability.</p>',
                    'content' => '<h3>FAA 8130-3 Airworthiness Approval Tag</h3><p>The <strong>FAA Form 8130-3</strong> is the U.S. Airworthiness Approval Tag — the document attached to an aircraft part attesting that it conforms to its type design and is in a condition for safe operation. For engine parts in particular, the 8130-3 is the documentation that turns a piece of metal into an installable aircraft part.</p><h4>Equivalents in other jurisdictions</h4><ul><li><strong>EASA Form 1</strong> — European Union Aviation Safety Agency equivalent</li><li><strong>TCCA Form One</strong> — Transport Canada Civil Aviation equivalent</li></ul><p>Under bilateral agreements, FAA 8130-3, EASA Form 1, and TCCA Form One are mutually recognized for most categories of parts. Other countries have their own equivalents that may or may not be recognized — verify case-by-case.</p><h4>What an 8130-3 attests</h4><p>The signing person (an FAA-authorized representative — Designated Airworthiness Representative (DAR), an Authorized Release Agent, or a Repair Station Quality Inspector) is certifying:</p><ul><li>The part was inspected and found to conform to its type design</li><li>The part is in a condition for safe operation</li><li>The work performed (if any) is properly documented</li><li>The records support the determination</li></ul><p>An 8130-3 is not just paperwork. It is a legal attestation that creates personal accountability for the signer.</p>',
                ],
                [
                    'name'  => 'Block 1-8: Identifying Information',
                    'intro' => '<p>What to check in the upper portion of the 8130-3 (issuing authority, form tracking, organization details).</p>',
                    'content' => '<h3>8130-3 Blocks 1 through 8 — Identifying Information</h3><p>The top portion of the 8130-3 identifies the form itself, the issuing authority, and the organization releasing the part.</p><h4>Block 1 — Approving Civil Aviation Authority / Country</h4><p>Should say "FAA / United States" for FAA-issued tags. EASA Form 1 will say "EASA / European Union." If neither, verify the issuing authority is recognized by bilateral agreement before accepting.</p><h4>Block 2 — Form Title</h4><p>Should read "AUTHORIZED RELEASE CERTIFICATE / FAA Form 8130-3, AIRWORTHINESS APPROVAL TAG." Any deviation suggests a non-standard or counterfeit form.</p><h4>Block 3 — Form Tracking Number</h4><p>Unique identifier assigned by the issuing organization. Must be present. Used for traceability back to the issuing organization\'s records.</p><h4>Block 4 — Organization Name and Address</h4><p>The Repair Station, Production Approval Holder, or other approved organization issuing the certificate. Verify against the FAA\'s public certificate-holder database if the organization is unfamiliar.</p><h4>Block 5 — Work Order/Contract/Invoice Number</h4><p>Cross-references the organization\'s internal records. Required for traceability.</p><h4>Blocks 6, 7, 8 — Item, Description, Part Number</h4><p>Must match the physical part exactly. Any discrepancy between Block 7-8 and the part itself is grounds for rejection or quarantine pending investigation.</p><p><strong>Receiving inspection checkpoint:</strong> Compare Block 7 (description) and Block 8 (part number) to the physical part marking and the purchase order. All three must agree.</p>',
                ],
                [
                    'name'  => 'Block 9-13: The Critical Conformance Block',
                    'intro' => '<p>Quantity, serial numbers, status of work performed, remarks, and the conformance statement that is the heart of the 8130-3.</p>',
                    'content' => '<h3>8130-3 Blocks 9 through 13 — The Conformance Section</h3><p>This is the substantive portion of the form — what the certificate actually attests.</p><h4>Block 9 — Quantity</h4><p>Match to the physical count and the purchase order. Off-by-one quantity discrepancies are surprisingly common and indicate sloppy issuing practice.</p><h4>Block 10 — Serial Number</h4><p>For serialized parts, must match the part\'s data plate exactly. Mismatches are an immediate quarantine condition.</p><h4>Block 11 — Status / Work</h4><p>States what was done to the part — typically one of:</p><ul><li><strong>"NEW"</strong> — new production part</li><li><strong>"INSPECTED/TESTED"</strong> — used part inspected for conformance, no repair</li><li><strong>"REPAIRED"</strong> — repaired and returned to service</li><li><strong>"OVERHAULED"</strong> — overhauled per OEM or approved manual</li><li><strong>"MODIFIED"</strong> — modified per approved data</li></ul><p>The status drives the maintenance status of the part on the aircraft. A wrong Block 11 misleads the installing mechanic.</p><h4>Block 12 — Remarks</h4><p>Free-text field. Often contains critical information: airworthiness directive (AD) compliance status, service bulletin (SB) compliance, time-since-new (TSN), cycles-since-new (CSN), time-since-overhaul (TSO), engine/airframe traceability ("removed from aircraft N12345"), and any special conditions of release.</p><p><strong>For engine parts (TurbineWorks focus):</strong> verify Block 12 contains the time/cycle information needed to maintain LLP (Life Limited Part) traceability. Missing LLP data on a life-limited part is a hard reject.</p><h4>Block 13 — Conformance Statement</h4><p>The boxed statement: <em>"Certifies that the items identified above were manufactured in conformity to: [approved design data and are in a condition for safe operation] OR [non-approved design data specified in Block 12]"</em>. The correct box must be checked. Both checked or neither checked is a defective form.</p>',
                ],
                [
                    'name'  => 'Block 14-22: Authorized Release Signature',
                    'intro' => '<p>The signature block — who signed, under what authority, and how to verify it is legitimate.</p>',
                    'content' => '<h3>8130-3 Blocks 14 through 22 — Release Authorization</h3><h4>Block 14 — Approving / Authorized Signature</h4><p>The signature of the person authorized to release the part. Original signature preferred; digital signatures acceptable if the issuing organization is authorized for electronic signatures.</p><h4>Block 15 — Authorization / Certificate Number</h4><p>The FAA certificate number of the issuing organization (e.g., the Repair Station number). <strong>Verify this against the FAA\'s public Certificate Search</strong> at <a href="https://av-info.faa.gov/" target="_blank" rel="noopener">av-info.faa.gov</a> if the issuing organization is new to TurbineWorks.</p><h4>Block 16 — Name of Signer</h4><p>Printed name corresponding to the signature in Block 14.</p><h4>Block 17 — Date</h4><p>Date the form was signed. Should be reasonably close to the date the part was shipped. A 6-month-old 8130-3 may indicate the part has been sitting in transit or held — investigate.</p><h4>Blocks 18-22 (right side) — Installer Verification</h4><p>The right side of the form is for the <em>installer</em> to complete when the part is installed on an aircraft. These should be <strong>blank</strong> on a part that has not yet been installed. If filled in, the part may have been installed previously and removed — investigate the history before accepting.</p><h4>Receiving-inspection red flags</h4><ul><li>Photocopied signature in Block 14 (indicator of counterfeiting)</li><li>Block 15 certificate number not found in FAA\'s database</li><li>Block 17 date in the future, or unreasonably old</li><li>Right-side installer blocks already filled in</li><li>Whiteout or obvious correction on any block</li><li>Form is a photocopy without a "TRUE COPY" stamp from an authorized entity</li></ul><p>Any of these is grounds for quarantine pending investigation. Do not accept the part into serviceable inventory.</p>',
                ],
                [
                    'name'  => 'Practical: Verifying a Suspicious 8130-3',
                    'intro' => '<p>Workflow for what to do when you have an 8130-3 in hand that does not look right.</p>',
                    'content' => '<h3>Verifying a Suspicious 8130-3</h3><p>You have a part in front of you. The 8130-3 has something off about it — maybe a block is missing, or the certificate number doesn\'t look familiar, or the signature looks photocopied. What do you do?</p><h4>Step 1 — Do not accept the part into serviceable inventory</h4><p>Move the part and its documentation immediately to the <strong>Quarantine</strong> area. Do not return it to the shipper, do not discard the documentation, do not photocopy the 8130-3 and put the "good copy" in your file. Preserve the original physical part and the original documentation exactly as received.</p><h4>Step 2 — Document the receipt</h4><p>Record the date and time received, the carrier, the shipper, the purchase order reference, and a description of what specifically looks wrong. Take photographs.</p><h4>Step 3 — Verify the issuing organization</h4><p>Look up Block 15 (certificate number) in the FAA\'s <a href="https://av-info.faa.gov/" target="_blank" rel="noopener">Certificate Search</a>. If the certificate number does not exist, or the organization name in Block 4 does not match what FAA shows, this is a Suspected Unapproved Part.</p><h4>Step 4 — Contact the issuing organization directly</h4><p>Using contact information you find <em>independently</em> (FAA database, not the shipping documents that came with the part), call the issuing organization. Ask them to confirm: did they issue an 8130-3 with this tracking number (Block 3) for this part (Block 7-8)?</p><p>If they say no, you have confirmed a SUP. Proceed to FAA reporting.</p><h4>Step 5 — Report the SUP</h4><p>File FAA Form 8120-11 (Suspected Unapproved Parts Notification). Notify the QA Manager and the Accountable Manager. Document everything in the corrective-action system.</p><h4>What not to do</h4><ul><li>Do not "give the benefit of the doubt" and accept the part.</li><li>Do not return the part to the shipper without first investigating — that just moves the SUP to someone else\'s inventory.</li><li>Do not discuss the SUP investigation with anyone outside TurbineWorks QA leadership and the FAA. The investigation may need to be confidential.</li><li>Do not destroy or alter the documentation. The originals are evidence.</li></ul><p><em>[TurbineWorks Procedure Reference: insert the TurbineWorks SUP-handling SOP reference here, including who at TurbineWorks is the designated point of contact for FAA SUP filings.]</em></p>',
                ],
            ],
        ],
        [
            'shortname' => 'TWU-ENG-LLP',
            'fullname'  => 'Life Limited Parts (LLP) Tracking',
            'idnumber'  => 'TWU-ENG-LLP',
            'summary'   => '<p>Back-to-birth traceability for LLP rotables — the documentation chain that lets an LLP be installed on an aircraft.</p>',
            'lessons'   => [local_twu_overview_lesson(
                'Life Limited Parts (LLP) Tracking',
                'FAA 14 CFR §43.10, OEM Engine Manuals, FAA AC 120-77',
                '1 hour',
                [
                    'Identify which engine parts are LLPs (turbine disks, compressor disks, shafts, certain bearings)',
                    'Verify back-to-birth traceability records (TSN, CSN, time-since-overhaul)',
                    'Recognize an LLP with incomplete or missing life records — and why it cannot be sold for installation',
                    'Apply the OEM\'s life-limit values from the current revision of the Engine Manual',
                    'Document LLP transactions in the TurbineWorks inventory system',
                ],
                'A turbine disk installed past its life limit is a guaranteed catastrophic failure. The LLP traceability system is what prevents this. A turbine disk without back-to-birth records is effectively scrap — it cannot be installed legally. This module exists because engine parts are TurbineWorks\' core business.'
            )],
        ],
    ];
}

/**
 * Recurring Training course skeleton — the 6-month refresher course.
 */
function local_twu_get_recurring_courses(): array {
    return [
        [
            'shortname' => 'TWU-RECUR-ASA',
            'fullname'  => 'ASA-100 Recurring Training (6-Month Refresher)',
            'idnumber'  => 'TWU-RECUR-ASA',
            'summary'   => '<p>6-month recurring refresher covering high-risk areas: SUP detection, recordkeeping, hazmat, and any changes to TurbineWorks procedures since the last training cycle.</p>',
            'lessons'   => [
                [
                    'name'  => 'Recurring Training Overview',
                    'intro' => '<p>How TurbineWorks structures recurring training and what employees should expect.</p>',
                    'content' => '<h3>ASA-100 Recurring Training</h3><h4>Cadence</h4><p>Every employee with quality-system responsibilities completes recurring training <strong>every 6 months</strong>. This is more frequent than the ASA-100 annual minimum, reflecting TurbineWorks\' decision to prioritize fresh familiarity over minimum compliance.</p><h4>Auto-enrollment</h4><p>TurbineWorks University enrolls you in this course automatically <strong>30 days before</strong> your last completion turns 6 months old. You will receive a notification email and the course will appear on your dashboard.</p><h4>What is covered</h4><p>Recurring training is intentionally narrower than initial training. It focuses on:</p><ul><li><strong>SUP detection</strong> — the highest-risk receiving-inspection failure mode</li><li><strong>Recordkeeping and traceability</strong> — the most common audit finding industry-wide</li><li><strong>Hazmat refresher</strong> — DOT and IATA require recurrent hazmat training every 24 months minimum; we exceed it with every-6-month exposure</li><li><strong>Any TurbineWorks procedure changes</strong> since your last training cycle</li><li><strong>Any new FAA Advisory Circulars or ASA-100 revisions</strong> since your last training cycle</li></ul><h4>Completion</h4><p>Pass each module quiz with 80% or better. Your recurring training completion is documented in your training record alongside your initial training, and feeds the audit reports the QA Manager produces for ASA inspections.</p><h4>Status</h4><p style="background:#fff8e1; border:1px solid #ffc800; padding:10px 14px; border-radius:4px;"><strong>Recurring module content is being authored.</strong> The structure and cadence are in place; content for each refresher module will track each completed initial-training module.</p>',
                ],
            ],
        ],
    ];
}
