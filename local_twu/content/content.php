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
        // Other 7 modules (TWF4-1, TWF4-2, TWF4-4..TWF4-8) intentionally
        // empty for now. After review of Module 3's depth/style, batch-fill
        // the rest with parallel structure.
        'TWF4-1' => [],
        'TWF4-2' => [],
        'TWF4-4' => [],
        'TWF4-5' => [],
        'TWF4-6' => [],
        'TWF4-7' => [],
        'TWF4-8' => [],
    ];
}
