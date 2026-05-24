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
        'TWF4-1' => local_twu_module_1_lessons(),
        'TWF4-2' => local_twu_module_2_lessons(),
        'TWF4-4' => local_twu_module_4_lessons(),
        'TWF4-5' => local_twu_module_5_lessons(),
        'TWF4-6' => local_twu_module_6_lessons(),
        'TWF4-7' => local_twu_module_7_lessons(),
        'TWF4-8' => local_twu_module_8_lessons(),
    ];
}

// ============================================================================
// MODULE 1 — Unapproved Parts & Counterfeit Materials (FAA AC 21-29D)
// ============================================================================
function local_twu_module_1_lessons(): array {
    return [
        [
            'name'  => 'Lesson 1.1 — Definitions: Unapproved, SUP, and Counterfeit',
            'intro' => '<p>The three categories of bad parts and why the legal distinctions matter.</p>',
            'content' => <<<'HTML'
<h3>Three categories of bad parts</h3>
<p>The FAA and ASA-100 distinguish three categories of parts that an accredited distributor must keep out of the supply chain. The distinctions are legally significant — they drive different reporting obligations and different enforcement consequences.</p>

<h4>Unapproved Part</h4>
<p>A part that does not conform to an approved type design, OR a part that is not in a condition for safe operation. The FAA definition is in <strong>14 CFR § 21.502</strong> and elaborated in <strong>FAA AC 21-29D</strong>.</p>
<p>An unapproved part is a definitive finding — the part has been determined, with evidence, not to meet approved design data. Examples:</p>
<ul>
  <li>A turbine blade made from the wrong alloy (verified by metallurgical analysis)</li>
  <li>A bearing produced by a non-approved manufacturer with no PMA or TSO authority</li>
  <li>A part stamped with an OEM part number but with documented dimensional non-conformance</li>
  <li>A part that was scrapped at an MRO but never mutilated, re-entered service through theft</li>
</ul>

<h4>Suspected Unapproved Part (SUP)</h4>
<p>A part where there is a <em>reasonable basis</em> to believe it may be unapproved, but where investigation is not yet complete. This is what receiving inspectors are most likely to encounter. The threshold is intentionally low — "reasonable basis," not "confirmed."</p>
<p>The moment a part becomes "suspected," it must be quarantined and investigated. You do not need certainty to flag a SUP. You need enough to justify suspicion.</p>
<p>Examples of what triggers SUP status:</p>
<ul>
  <li>FAA 8130-3 tag with a Block 15 certificate number not found in the FAA's public database</li>
  <li>Documentation that has been photocopied with no "TRUE COPY" attestation</li>
  <li>Serial number on the part doesn't match the serial number on the tag</li>
  <li>Markings on the part appear to have been altered (font mismatch, depth differences)</li>
  <li>Source supplier is not on the TurbineWorks approved-supplier list</li>
</ul>

<h4>Counterfeit Part</h4>
<p>A part that has been <em>deliberately misrepresented</em> as being something it is not — typically misrepresented as being from an approved source when it is not. Counterfeiting requires intent to deceive. This makes it a distinct legal matter, often crossing into criminal fraud.</p>
<p>The aerospace counterfeit problem accelerated in the 2000s with the global outsourcing of electronic component manufacturing. The SAE published <strong>AS5553</strong> (electronics) and <strong>AS6174</strong> (broader materiel) in response. ASA auditors increasingly check supplier-qualification programs against these standards.</p>

<h4>Why the distinctions matter</h4>
<ul>
  <li><strong>Reporting:</strong> SUP triggers FAA Form 8120-11 notification. Counterfeit may additionally trigger FBI/DOD reporting if it touches government contracts.</li>
  <li><strong>Disposition:</strong> Confirmed unapproved parts must be mutilated per FAA AC 21-38. SUP under investigation must be quarantined but cannot yet be destroyed (preserves evidence).</li>
  <li><strong>Liability:</strong> Knowingly selling a counterfeit part is fraud. Inadvertently selling an unapproved part is still a serious finding but typically administrative, not criminal.</li>
</ul>

<p><strong>Receiving inspector rule of thumb:</strong> when in doubt, treat as SUP and quarantine. Investigation can always confirm a part is good; an unapproved part that gets into inventory is much harder to find and recall.</p>
HTML
        ],
        [
            'name'  => 'Lesson 1.2 — How SUP Enters the Supply Chain',
            'intro' => '<p>The five main pathways unapproved parts use to reach distributors, and which ones to watch for.</p>',
            'content' => <<<'HTML'
<h3>How SUP enters the aviation supply chain</h3>
<p>To stop unapproved parts, you need to understand how they get into the supply chain in the first place. There are five recurring pathways.</p>

<h4>1. Scrap diversion</h4>
<p>The most common pathway. An MRO or operator scraps a part — typically because it has reached its life limit, failed inspection, or been damaged. The part is supposed to be mutilated per <strong>FAA AC 21-38</strong> so it physically cannot be reinstalled. Instead, someone diverts it: takes the part out of the scrap bin, re-cleans it, re-marks it, and sells it to an unsuspecting distributor.</p>
<p>Defense: verify supplier provenance. Parts from unknown brokers should trace back to an identifiable approved source.</p>

<h4>2. Theft from OEM or MRO facilities</h4>
<p>Parts are physically stolen from production lines or repair facilities — sometimes by employees, sometimes by organized theft rings. The stolen part may be technically conforming (it's a real part), but its documentation is missing or fabricated, and the OEM has no record of its release.</p>
<p>Defense: the FAA 8130-3 must trace back to an issuing organization that confirms it issued the tag for this part.</p>

<h4>3. Manufacturing without authorization</h4>
<p>A machine shop produces a part — possibly to legitimate dimensions — without PMA (Parts Manufacturer Approval) or TSO (Technical Standard Order) authorization from the FAA. The part is then sold with fabricated paperwork or no paperwork at all.</p>
<p>Defense: confirm the manufacturer holds the appropriate FAA approval. Cross-check against the FAA's public certificate database.</p>

<h4>4. Documentation fraud</h4>
<p>The part is real but the paperwork is fake. Photocopied 8130-3 tags, forged signatures, made-up certificate numbers, altered dates. Sometimes the original tag was for a different part and has been "reassigned."</p>
<p>Defense: every block of the 8130-3 must be verifiable. Block 15 certificate numbers must exist in the FAA database. Issuing organizations should be contactable to verify they issued the tag.</p>

<h4>5. Counterfeit manufacture</h4>
<p>A part is deliberately fabricated to look like a legitimate OEM part — same markings, same finish, same packaging — but produced by an unauthorized source. Common in electronic components and high-value bearings.</p>
<p>Defense: visual inspection comparing against known-good samples. Markings should match exactly in font, depth, position, and color. Suspicious-looking parts go to a metallurgical or dimensional check.</p>

<h4>Why aviation is a target</h4>
<p>Aviation parts have unusually high margins (a turbine blade can sell for tens of thousands of dollars) and the documentation infrastructure (8130-3 tags) is not always rigorously verified by buyers under cost pressure. This is exactly the market profile that attracts fraud.</p>

<h4>The TurbineWorks position</h4>
<p>As an accredited distributor, TurbineWorks is one of the lines of defense between the broker market and the operators flying aircraft. The customer is paying for our verification of provenance and documentation. If we accept a SUP into inventory, we are the failure point.</p>

<p><em>[TurbineWorks Procedure Reference: insert TurbineWorks approved-supplier list and the procedure for adding a new supplier here.]</em></p>
HTML
        ],
        [
            'name'  => 'Lesson 1.3 — Documentation Red Flags',
            'intro' => '<p>What to look for on incoming paperwork that should trigger SUP suspicion.</p>',
            'content' => <<<'HTML'
<h3>Documentation red flags</h3>
<p>Most SUPs are caught at the documentation review step of receiving inspection, not at the physical inspection. Bad paperwork is easier to spot than a well-counterfeited part.</p>

<h4>The 8130-3 itself</h4>
<ul>
  <li><strong>Photocopied signature</strong> in Block 14. Original signatures are required (or authorized electronic signatures with a verifiable digital trail). A grainy photocopy of a signature is a major red flag.</li>
  <li><strong>Block 15 certificate number not in FAA database.</strong> Look it up at <a href="https://av-info.faa.gov/" target="_blank" rel="noopener">av-info.faa.gov</a>. Every certificate the FAA has issued is searchable. If the certificate number doesn't exist, the tag is invalid.</li>
  <li><strong>Block 4 organization name doesn't match Block 15 certificate.</strong> The FAA database shows the certificate holder's name. It must match Block 4 exactly.</li>
  <li><strong>Form Block 3 tracking number missing</strong> or in a format that doesn't match the issuing organization's known sequence.</li>
  <li><strong>Block 17 date in the future</strong> (obvious error or deliberate manipulation).</li>
  <li><strong>Block 17 date implausibly old.</strong> A 2-year-old 8130-3 on a fresh shipment suggests the tag was reused from a different transaction.</li>
  <li><strong>Right-side installer blocks pre-filled.</strong> The right side (Blocks 18-22) is for the installer to complete when installing on an aircraft. If filled in on a part not yet installed, the part was previously installed and removed — investigate why.</li>
  <li><strong>Whiteout, correction fluid, or visible alteration</strong> on any block.</li>
  <li><strong>"TRUE COPY" stamps that aren't notarized</strong> or attested by an authorized entity. A copy of an 8130-3 is only acceptable as a true copy when properly attested.</li>
</ul>

<h4>Certificate of Conformance (COC) red flags</h4>
<ul>
  <li>No specific standard cited. A COC should reference the specific standard the part conforms to (e.g., "conforms to MIL-PRF-XXXXX rev. C").</li>
  <li>No serial number or lot number tying the COC to the specific part.</li>
  <li>Signed by someone whose authority is not documented (no title, no organization position).</li>
  <li>Generic letterhead that could be from anyone.</li>
</ul>

<h4>Packing list and invoice red flags</h4>
<ul>
  <li>Part number on packing list doesn't match the 8130-3.</li>
  <li>Shipping origin doesn't match the issuing organization's location.</li>
  <li>Multiple parts on one 8130-3 when each should have its own tag.</li>
  <li>Quantity on packing list doesn't match Block 9 of the 8130-3.</li>
</ul>

<h4>Supplier red flags</h4>
<ul>
  <li>Supplier is new to TurbineWorks and not on the approved-supplier list.</li>
  <li>Supplier address is a residential or PO-box-only location with no warehouse facility.</li>
  <li>Supplier offers parts at significantly below market price for the part type and condition.</li>
  <li>Supplier resists or refuses requests for additional documentation.</li>
</ul>

<h4>What to do when you spot a red flag</h4>
<p>Stop the receiving process for that specific part. Move the part and ALL its documentation to the Quarantine area without modification. Notify the QA Manager. Document what you saw and when. Do not return the part or the documentation to the shipper until the investigation is complete — the originals are evidence.</p>
HTML
        ],
        [
            'name'  => 'Lesson 1.4 — Physical Inspection Red Flags',
            'intro' => '<p>What to look for on the part itself.</p>',
            'content' => <<<'HTML'
<h3>Physical inspection red flags</h3>
<p>Once the documentation is reviewed, the part itself is examined. A skilled counterfeiter can produce convincing paperwork. The physical part is often harder to fake convincingly.</p>

<h4>Markings</h4>
<p>Every OEM has specific marking practices: font, size, position, depth of etch, color of paint-fill. Compare against a known-good reference part.</p>
<ul>
  <li><strong>Font mismatch.</strong> OEMs use specific fonts for part-number stamping. A counterfeit may use a generic Arial-like font where the OEM uses a specific machinery-engraving font.</li>
  <li><strong>Position drift.</strong> OEM markings are at consistent locations relative to part features. A counterfeit may have markings shifted by even a few millimeters.</li>
  <li><strong>Depth differences.</strong> Vibro-engraved markings have a characteristic depth and texture. Laser-etched markings look different. A part that should have been vibro-engraved but is instead laser-etched is suspicious.</li>
  <li><strong>Re-stamping evidence.</strong> A part that was originally marked one way, then over-stamped or ground down and re-marked, will show telltale grinding marks under the new stamp.</li>
  <li><strong>Paint-fill color.</strong> OEMs use specific paint-fill colors for part-number stamps. A red-filled stamp where the OEM uses white-filled is a flag.</li>
</ul>

<h4>Surface finish</h4>
<ul>
  <li>Machining marks consistent with OEM tooling? Modern CNC machining leaves a recognizable pattern that's hard to replicate.</li>
  <li>Surface plating uniform? A re-plated part may show plating-thickness variation, edge buildup, or runs.</li>
  <li>Heat tint colors on areas that should never have been heat-affected? Indicates the part was re-worked.</li>
  <li>Edges chamfered to OEM spec? Sharp edges where there should be chamfers (or vice versa) indicate non-OEM manufacture.</li>
</ul>

<h4>Material</h4>
<p>For high-value or critical parts, a metallurgical sample can confirm material composition. This is destructive testing and is reserved for confirmed SUP investigation, not routine receiving. The QA Manager authorizes destructive testing.</p>
<ul>
  <li>Weight off from the expected value? Different alloy or hollow part where it should be solid.</li>
  <li>Magnetism present on a part that should be non-magnetic (e.g., titanium or austenitic stainless)?</li>
  <li>Color or sheen of the metal off from a known-good reference?</li>
</ul>

<h4>Packaging</h4>
<ul>
  <li>OEM packaging used by the supplier? OEMs typically ship in specific packaging — official boxes, anti-static bags, branded foam inserts. Generic packaging is a flag.</li>
  <li>Packaging shows evidence of being repackaged? Tape lines, glue residue, torn labels under new labels.</li>
  <li>OEM labels match the part inside? A label for one part on a box containing a different part is a major flag.</li>
</ul>

<h4>Wear and use signs on a "new" part</h4>
<ul>
  <li>Wear marks on a "NEW" part (per Block 11 of the 8130-3) — bearing races showing run-in patterns, fastener threads showing torque marks, blade leading edges showing FOD.</li>
  <li>Discoloration consistent with use (carbon deposits, oil staining, heat tint from engine operation).</li>
  <li>Repair marks (weld beads, machining over original surface) on a part claimed as new.</li>
</ul>

<p><strong>Bottom line:</strong> a SUP investigation may end up requiring metallurgical or dimensional inspection by a qualified lab. The receiving inspector's job is to flag the part for investigation — not to definitively prove unapproved status. When in doubt, quarantine.</p>
HTML
        ],
        [
            'name'  => 'Lesson 1.5 — Reporting a Suspected Unapproved Part',
            'intro' => '<p>The FAA SUP reporting workflow and TurbineWorks internal escalation.</p>',
            'content' => <<<'HTML'
<h3>Reporting a Suspected Unapproved Part</h3>
<p>Once a part has been quarantined as a SUP, there is a defined reporting workflow. This lesson walks through it.</p>

<h4>Step 1 — Quarantine and preserve</h4>
<p>Move the part and all associated documentation to the Quarantine area. Tag the part as SUP under investigation. Do not return to shipper, do not destroy the documentation, do not modify the part in any way. Originals are evidence.</p>

<h4>Step 2 — Internal escalation</h4>
<p>Notify the <strong>Quality Assurance Manager</strong> in writing the same business day. Include: part number, serial number, supplier, purchase order, the specific red flag(s) observed, photographs. The QA Manager owns the investigation from this point.</p>

<h4>Step 3 — Verification with the issuing organization</h4>
<p>The QA Manager (or their designate) contacts the issuing organization listed in Block 4 of the 8130-3, using contact information found <em>independently</em> (FAA database, not the supplier's shipping documents — those may also be falsified). Confirm: did this organization issue an 8130-3 with this tracking number for this part? If they say no, the SUP is confirmed.</p>

<h4>Step 4 — FAA Form 8120-11</h4>
<p>When a SUP is confirmed (or when there is sufficient evidence to file even before full confirmation), TurbineWorks files <strong>FAA Form 8120-11, Suspected Unapproved Parts Report</strong>. The form is available at <a href="https://www.faa.gov/aircraft/safety/programs/sups/" target="_blank" rel="noopener">faa.gov/aircraft/safety/programs/sups/</a>.</p>
<p>The form asks for:</p>
<ul>
  <li>Reporter information (TurbineWorks contact)</li>
  <li>Part information (part number, serial number, manufacturer)</li>
  <li>Documentation accompanying the part</li>
  <li>Source supplier</li>
  <li>Description of why the part is suspected</li>
  <li>Disposition (where the part is now)</li>
</ul>
<p>The form can be submitted by mail, fax, or email to the FAA Aviation Safety Hotline. The FAA's SUP Program Office investigates from there.</p>

<h4>Step 5 — Customer notification</h4>
<p>If the SUP was purchased on behalf of a specific customer, that customer must be notified. If the part type matches anything already shipped to other customers, those customers must also be notified so they can investigate their own inventory and (if installed) the affected aircraft.</p>
<p>This is uncomfortable but mandatory. Hiding a SUP discovery to protect business relationships is itself a serious regulatory violation.</p>

<h4>Step 6 — Disposition</h4>
<p>The SUP remains in quarantine until the FAA's investigation concludes. The FAA may request the physical part as evidence. If returned to TurbineWorks for disposition after investigation, the part is mutilated per <strong>FAA AC 21-38</strong> and the mutilation documented in the records system.</p>

<h4>Step 7 — Corrective action</h4>
<p>Every SUP triggers a corrective-action investigation: how did this supplier get into our supply chain? Should they be removed from the approved-supplier list? Are there other parts from this supplier in inventory that need re-verification? Did our receiving inspection process catch this, or did the part slip through? If it slipped through, what process change is needed?</p>

<h4>Confidentiality during investigation</h4>
<p>SUP investigations are sensitive. The supplier may be a victim themselves (downstream of a fraud), or may be the perpetrator. Discussing the investigation outside QA leadership and the FAA can compromise the case or expose TurbineWorks to defamation claims. Keep it inside the chain.</p>

<h4>Recordkeeping</h4>
<p>SUP reports, FAA filings, supplier correspondence, mutilation records — all are retained as part of the quality records per ASA-100 §8. Auditors will review SUP handling history at every audit.</p>

<p><em>[TurbineWorks Procedure Reference: insert TurbineWorks SUP-handling SOP and the designated QA contact for FAA filings here.]</em></p>
HTML
        ],
        [
            'name'  => 'Lesson 1.6 — Module 1 Summary and Key References',
            'intro' => '<p>Recap, key terms, and source documents for unapproved/counterfeit parts.</p>',
            'content' => <<<'HTML'
<h3>Module 1 Summary</h3>

<h4>What you should now be able to answer</h4>
<ol>
  <li>What is the difference between an unapproved part, a SUP, and a counterfeit part?</li>
  <li>What are the five main pathways by which SUPs enter the supply chain?</li>
  <li>What are at least four documentation red flags that should trigger SUP suspicion?</li>
  <li>What is FAA Form 8120-11 and when is it filed?</li>
  <li>What is the role of FAA AC 21-38 in SUP disposition?</li>
</ol>

<h4>Key Terms</h4>
<dl>
  <dt><strong>Unapproved Part</strong></dt>
  <dd>A part determined not to conform to approved type design or not in a condition for safe operation (14 CFR §21.502).</dd>
  <dt><strong>Suspected Unapproved Part (SUP)</strong></dt>
  <dd>A part for which there is reasonable basis to believe it may be unapproved, pending investigation.</dd>
  <dt><strong>Counterfeit Part</strong></dt>
  <dd>A part deliberately misrepresented as being from an approved source. Requires intent to deceive.</dd>
  <dt><strong>PMA</strong></dt>
  <dd>Parts Manufacturer Approval — FAA authority to produce a replacement part.</dd>
  <dt><strong>TSO</strong></dt>
  <dd>Technical Standard Order — FAA standard for certain categories of articles.</dd>
  <dt><strong>FAA Form 8120-11</strong></dt>
  <dd>Suspected Unapproved Parts Report — the form filed with the FAA when a SUP is identified.</dd>
  <dt><strong>Scrap diversion</strong></dt>
  <dd>The most common SUP pathway: scrapped parts diverted from mutilation and reintroduced into supply.</dd>
</dl>

<h4>External Reference Documents</h4>
<ul>
  <li><a href="https://www.faa.gov/regulations_policies/advisory_circulars/index.cfm/go/document.information/documentID/1019042" target="_blank" rel="noopener">FAA AC 21-29D</a> — Detecting and Reporting SUP</li>
  <li><a href="https://www.faa.gov/aircraft/safety/programs/sups/" target="_blank" rel="noopener">FAA SUP Program Office</a></li>
  <li>SAE <strong>AS5553</strong> — Counterfeit Electronic Parts</li>
  <li>SAE <strong>AS6174</strong> — Counterfeit Materiel</li>
  <li>14 CFR §21.502 — Unapproved Parts Definition</li>
</ul>
HTML
        ],
    ];
}

// ============================================================================
// MODULE 2 — Receiving and Shipping Inspection (ASA-100 §6)
// ============================================================================
function local_twu_module_2_lessons(): array {
    return [
        [
            'name'  => 'Lesson 2.1 — ASA-100 §6 Receiving Inspection Overview',
            'intro' => '<p>The structure of receiving inspection as defined by ASA-100 §6.</p>',
            'content' => <<<'HTML'
<h3>ASA-100 §6 Receiving Inspection</h3>
<p>Receiving inspection is the single highest-leverage quality control point in a distributor's operation. A part that passes receiving inspection enters inventory and from there can be shipped to a customer. A defect that escapes detection here may never be caught.</p>

<h4>The required sequence</h4>
<p>ASA-100 §6 requires that incoming parts move through a defined sequence:</p>
<ol>
  <li><strong>Pre-receiving verification</strong> — match shipment to purchase order, verify supplier is on the approved list</li>
  <li><strong>Documentation review</strong> — 8130-3 (or equivalent), COC, packing list, traceability documents</li>
  <li><strong>Physical inspection</strong> — quantity count, visual condition, markings, packaging integrity</li>
  <li><strong>Disposition decision</strong> — accept (move to serviceable inventory), quarantine (under investigation), or reject (return to supplier)</li>
  <li><strong>Recordkeeping</strong> — receiving inspection record completed and filed</li>
</ol>

<h4>Who performs receiving inspection</h4>
<p>Only employees identified in the QAM as <strong>Receiving Inspectors</strong> may perform receiving inspection. The Receiving Inspector role requires:</p>
<ul>
  <li>Completion of TurbineWorks University ASA-100 Initial Training</li>
  <li>On-the-job training documented on form TWF-4 (or its digital equivalent)</li>
  <li>Designation by name in the QAM as a Receiving Inspector</li>
  <li>Recurring training every 6 months</li>
</ul>

<h4>Where receiving inspection happens</h4>
<p>Receiving inspection occurs in a designated <strong>Receiving area</strong> that is physically separate from serviceable inventory. Parts cannot move from the Receiving area into serviceable storage until the receiving inspection is complete and the disposition is "accept."</p>

<h4>What records must be created</h4>
<ul>
  <li>Receiving inspection record (TurbineWorks form referenced in QAM)</li>
  <li>Copy of the 8130-3 (or equivalent) filed against the part record</li>
  <li>Copy of the COC if applicable</li>
  <li>Photographs if any non-conformance is noted</li>
  <li>Cross-reference to the purchase order and supplier</li>
</ul>

<h4>What an auditor checks</h4>
<p>ASA auditors specifically observe receiving inspection in action during an on-site audit. They may:</p>
<ul>
  <li>Watch a receiving inspection from start to finish without interrupting</li>
  <li>Ask the inspector what step they are on and why</li>
  <li>Pull a random part from serviceable inventory and ask to see its receiving inspection record</li>
  <li>Check that the inspector named on the record is in fact authorized in the QAM</li>
  <li>Check that the receiving area is physically segregated from serviceable storage</li>
</ul>

<p><em>[TurbineWorks Procedure Reference: insert the TurbineWorks receiving inspection SOP reference and the current form designator here.]</em></p>
HTML
        ],
        [
            'name'  => 'Lesson 2.2 — Pre-Receiving Verification',
            'intro' => '<p>What to check before you even open the box.</p>',
            'content' => <<<'HTML'
<h3>Pre-Receiving Verification</h3>
<p>Before opening packaging, before examining the part, the receiving inspector performs a pre-receiving check. The point is to catch obvious problems early — wrong shipment, unapproved supplier, missing paperwork — without disturbing the physical part or its packaging.</p>

<h4>Step 1 — Identify the shipment</h4>
<ul>
  <li>Carrier and tracking number</li>
  <li>Shipper name and address (compare to PO supplier)</li>
  <li>Number of pieces in shipment</li>
  <li>Visible damage to outer packaging (photograph if present)</li>
</ul>

<h4>Step 2 — Match to the Purchase Order</h4>
<p>The shipment must match a current TurbineWorks Purchase Order. An unexpected shipment is treated as a finding — investigate before accepting.</p>
<ul>
  <li>PO number on the shipping documentation matches an open TurbineWorks PO?</li>
  <li>Supplier on the shipping documentation matches the PO supplier?</li>
  <li>Part numbers on the packing list match what was ordered?</li>
  <li>Quantities match what was ordered (or a partial shipment that the PO permits)?</li>
</ul>
<p>A shipment without a matching PO is held in the Receiving area pending investigation. Common causes: supplier shipped to the wrong customer, supplier shipped against a cancelled PO, paperwork mix-up in the supplier's shipping department. Sometimes it is a legitimate error; sometimes it is suspicious.</p>

<h4>Step 3 — Verify the supplier is approved</h4>
<p>TurbineWorks maintains an <strong>approved-supplier list</strong>. Parts from any supplier not on the list cannot be accepted into serviceable inventory without the QA Manager's authorization to add the supplier (which requires a supplier qualification process).</p>
<p>If the shipment is from an unknown or unapproved supplier:</p>
<ul>
  <li>Hold the shipment in Receiving</li>
  <li>Notify the QA Manager</li>
  <li>Do not begin the document review until the supplier status is resolved</li>
</ul>

<h4>Step 4 — Documentation completeness check</h4>
<p>Before opening the box, confirm the shipment includes:</p>
<ul>
  <li>Packing list</li>
  <li>FAA 8130-3 (or EASA Form 1, TCCA Form One — whichever is applicable)</li>
  <li>Certificate of Conformance if required by PO</li>
  <li>Test reports / material certifications if required by PO</li>
  <li>Country-of-origin documentation if required</li>
</ul>
<p>Missing required documentation is a hold condition. The part cannot be accepted until documentation arrives. Document the missing items and notify both the supplier and the QA Manager.</p>

<h4>Step 5 — Decide whether to open</h4>
<p>If pre-receiving checks raise no flags, proceed to documentation review and physical inspection. If any flag is raised, the part stays packaged and goes to a hold area until the issue is resolved. Opening the package starts the receiving inspection clock — once opened, the part is in process and must be dispositioned.</p>

<h4>Common pre-receiving issues</h4>
<ul>
  <li>Shipment received before PO was issued (drop-ship error)</li>
  <li>Quantity over-shipped without an updated PO</li>
  <li>Supplier name on the shipping label doesn't match Block 4 of the 8130-3 (drop-shipping by an unapproved third party)</li>
  <li>Part shipped from a country not authorized by the PO terms (e.g., export-control violation)</li>
</ul>
HTML
        ],
        [
            'name'  => 'Lesson 2.3 — Documentation Review',
            'intro' => '<p>What documents you must have and how to verify each one.</p>',
            'content' => <<<'HTML'
<h3>Documentation Review</h3>
<p>With the shipment open, the receiving inspector reviews the documentation that accompanies each part. <em>Every part has its own document set</em> — multiple parts on a single 8130-3 is generally not acceptable for engine parts (which are typically serialized).</p>

<h4>The FAA 8130-3 (or equivalent)</h4>
<p>See the dedicated Engine-Parts course "FAA 8130-3 — Block-by-Block Inspection" in the Reference Library category for the full walkthrough. At a minimum, verify:</p>
<ul>
  <li>Issuing authority (Block 1) is FAA, or an equivalent authority recognized by bilateral agreement</li>
  <li>Form is titled "Authorized Release Certificate" (Block 2) — not a different form type</li>
  <li>Tracking number (Block 3) is present</li>
  <li>Organization name (Block 4) matches the certificate holder per FAA database lookup of Block 15</li>
  <li>Description and part number (Blocks 7-8) match the physical part exactly</li>
  <li>Quantity (Block 9) matches the count</li>
  <li>Serial number (Block 10) matches the part data plate for serialized parts</li>
  <li>Status (Block 11) is one of: NEW, INSPECTED/TESTED, REPAIRED, OVERHAULED, MODIFIED</li>
  <li>Remarks (Block 12) include LLP time/cycle data if the part is life-limited</li>
  <li>Signature (Block 14) is original or verifiable electronic signature</li>
  <li>Certificate number (Block 15) is verifiable in the FAA database</li>
  <li>Date (Block 17) is plausible</li>
  <li>Right-side installer blocks (18-22) are blank for a part not yet installed</li>
</ul>

<h4>EASA Form 1 (European-origin parts)</h4>
<p>EASA Form 1 is the European Union equivalent of the FAA 8130-3. Under the FAA-EASA bilateral, an EASA Form 1 from an approved organization is accepted for parts. The form structure differs slightly:</p>
<ul>
  <li>Block 1: Approving Authority (EASA)</li>
  <li>Block 2: Title (Authorized Release Certificate / EASA Form 1)</li>
  <li>Blocks 6-8: Item description and part number</li>
  <li>Block 14: Authorization (the equivalent of FAA Block 14 signature)</li>
</ul>
<p>The same verification principles apply — verify the issuing organization's EASA approval number, check signature originality.</p>

<h4>TCCA Form One (Canadian-origin parts)</h4>
<p>The Transport Canada Civil Aviation equivalent. Similar structure to EASA Form 1, mutually recognized under FAA-TCCA bilateral. Verify the Canadian Approved Maintenance Organization (AMO) certificate number against TCCA's database.</p>

<h4>Certificate of Conformance (COC)</h4>
<p>A supplier-issued statement attesting that the part conforms to a specified standard or specification. Not a substitute for the 8130-3 on parts requiring airworthiness approval — but required for many raw materials, standard hardware, and consumables.</p>
<p>A valid COC includes:</p>
<ul>
  <li>Standard or specification being conformed to (with revision)</li>
  <li>Part number, serial number or lot number tying the COC to specific items</li>
  <li>Quantity</li>
  <li>Manufacturer name and address</li>
  <li>Signed by an authorized representative with name and title</li>
  <li>Date of issue</li>
</ul>

<h4>Material certifications and test reports</h4>
<p>For raw materials and parts where material conformance matters (turbine disks, fasteners in critical applications), the PO may require a material test report showing chemical composition and mechanical properties. Verify the test report references the correct heat/lot number that ties to the parts in the shipment.</p>

<h4>Country-of-origin documentation</h4>
<p>Required for U.S. export-control compliance and for some customer contracts. Verify the country of origin matches what the PO authorized.</p>

<h4>What to do with documentation</h4>
<p>Originals: file against the part record in the document control system. The originals are the audit-evidence chain — they must be retained per the ASA-100 §8 records-retention schedule (Module 5). Copies may be kept with the physical part for warehouse reference.</p>
HTML
        ],
        [
            'name'  => 'Lesson 2.4 — Physical Inspection',
            'intro' => '<p>What you inspect on the part itself.</p>',
            'content' => <<<'HTML'
<h3>Physical Inspection</h3>
<p>With documentation verified, the receiving inspector examines the physical part. The depth of physical inspection depends on the part type — a turbine blade with a serial number gets more attention than a box of standard fasteners with a lot number.</p>

<h4>Quantity verification</h4>
<p>Count the parts. Match to Block 9 of the 8130-3 and to the packing list. Discrepancies are investigated before disposition. Common discrepancies:</p>
<ul>
  <li>Over-shipment: more parts than ordered. Often legitimate (supplier sent extra in case of breakage) but must be documented.</li>
  <li>Under-shipment: fewer parts than ordered. Note as partial shipment, follow up with supplier.</li>
  <li>Count off by one: surprisingly common. Recount before recording.</li>
</ul>

<h4>Serial number verification</h4>
<p>For serialized parts, the data plate or stamped serial number must exactly match Block 10 of the 8130-3. Verify character by character. A single transposed digit is grounds for quarantine.</p>

<h4>Part number verification</h4>
<p>Part number stamped or marked on the part must match Block 8 of the 8130-3 and the PO. Look for revision letters — a part number "ABC-1234-5 Rev B" is not the same as "ABC-1234-5 Rev C" if the customer ordered Rev C.</p>

<h4>Markings and stampings</h4>
<p>See Module 1 Lesson 1.4 for the full list of marking red flags. At minimum, check:</p>
<ul>
  <li>Markings present where they should be (per OEM spec)</li>
  <li>Markings appear factory-original (no re-stamping evidence)</li>
  <li>Cure dates, manufacturing dates, lot codes legible and consistent with the documentation</li>
</ul>

<h4>Visual condition</h4>
<ul>
  <li>FOD (Foreign Object Damage): nicks, dents, dings on critical surfaces — especially turbine and compressor blade leading edges, sealing surfaces, bearing races</li>
  <li>Corrosion: pitting, discoloration, staining</li>
  <li>Heat distress: discoloration on areas that should not have been heat-affected</li>
  <li>Repair evidence: weld beads, machining marks where a part should be virgin material</li>
  <li>Wear: scoring, gouging, contact wear on bearing surfaces of a "NEW" part</li>
</ul>

<h4>Packaging integrity</h4>
<ul>
  <li>Was the part packaged appropriately for its type? (Anti-static for electronics, sealed bag for moisture-sensitive, individual wrap for finished surfaces)</li>
  <li>Is the packaging damaged in a way that suggests in-transit damage to the part?</li>
  <li>Preservation: oil-coated steel parts should have visible preservative; bearings in moisture-sensitive packaging should have intact desiccant</li>
</ul>

<h4>Dimensional verification</h4>
<p>Routine receiving inspection typically does not include dimensional measurement — that level of verification is the OEM's responsibility under their PMA or production approval. However, dimensional verification may be required by the PO for certain parts (custom-machined items, critical-fit parts, parts where the supplier history justifies extra verification).</p>
<p>When dimensional verification is required, use calibrated measuring tools that are listed in TurbineWorks' calibration program. Out-of-cal tools cannot be used — that's a finding by itself.</p>

<h4>What to do with non-conformance</h4>
<p>If the part fails any check, the disposition is <strong>Quarantine</strong>. Move to the Quarantine area, tag with non-conformance description, notify QA Manager. Do not move to serviceable inventory under any circumstances until the non-conformance is dispositioned.</p>
HTML
        ],
        [
            'name'  => 'Lesson 2.5 — Disposition Decision: Accept, Quarantine, or Reject',
            'intro' => '<p>The three possible outcomes of receiving inspection and how each is handled.</p>',
            'content' => <<<'HTML'
<h3>Disposition Decision</h3>
<p>Every receiving inspection ends with one of three dispositions. The choice is not the inspector's discretion — it is determined by what the inspection found.</p>

<h4>Accept</h4>
<p>Documentation is complete and verified, physical inspection finds no non-conformance. The part is approved for serviceable inventory.</p>
<p>Actions on accept:</p>
<ul>
  <li>Complete the receiving inspection record (sign, date)</li>
  <li>Apply the TurbineWorks serviceable tag to the part (per QAM-defined tag format)</li>
  <li>Move the part to the appropriate serviceable storage location</li>
  <li>Update inventory system (system marks part as available)</li>
  <li>File all original documentation against the part record in the document control system</li>
</ul>

<h4>Quarantine</h4>
<p>Documentation is incomplete or questionable, physical inspection found a non-conformance, or there is any other reason to delay disposition pending investigation. <strong>Quarantine is not rejection</strong> — the part may eventually be accepted after the issue is resolved. The point is to prevent the part from entering serviceable inventory while the investigation is in progress.</p>
<p>Actions on quarantine:</p>
<ul>
  <li>Move the part to the physically segregated Quarantine area</li>
  <li>Apply the Quarantine tag with reason for hold</li>
  <li>Document the non-conformance: what specifically was wrong, what is being investigated</li>
  <li>Notify the QA Manager</li>
  <li>Open a non-conformance report in the corrective-action system</li>
  <li>Do not return part or documentation to supplier without QA Manager authorization</li>
</ul>
<p>Typical reasons for quarantine:</p>
<ul>
  <li>SUP investigation (see Module 1)</li>
  <li>Missing documentation expected within a defined timeframe</li>
  <li>Visible damage that may have occurred in transit (insurance claim pending)</li>
  <li>Serial number discrepancy requiring supplier confirmation</li>
  <li>Supplier qualification status pending</li>
</ul>

<h4>Reject</h4>
<p>The part cannot be accepted under any reasonable resolution. Documentation is fraudulent, the part itself does not conform, the part is the wrong part number entirely. Rejected parts go back to the supplier OR are destroyed per ASA-100 §8 disposition procedures (for confirmed SUP that has been investigated).</p>
<p>Actions on reject:</p>
<ul>
  <li>Document the rejection reason in the receiving inspection record</li>
  <li>Notify the QA Manager and the buyer who issued the PO</li>
  <li>Tag the part as Rejected — do not let it touch serviceable inventory under any circumstances</li>
  <li>Coordinate return to supplier OR disposition (mutilation per AC 21-38) per QA Manager direction</li>
  <li>Open a corrective-action report</li>
  <li>Evaluate supplier performance — repeated rejections from the same supplier may trigger removal from the approved-supplier list</li>
</ul>

<h4>What you cannot do</h4>
<ul>
  <li>Accept a part with non-conformance because "it looks OK and the customer needs it"</li>
  <li>Quarantine a part for "a little while" and then accept it without resolving the non-conformance</li>
  <li>Return a SUP to the supplier without first investigating (that just shifts the SUP to the next distributor)</li>
  <li>Destroy a SUP without QA Manager authorization (loses evidence)</li>
  <li>Re-stamp, re-mark, or alter a part to make it pass inspection (this is itself fraud)</li>
</ul>

<h4>The receiving inspector's authority</h4>
<p>Receiving inspectors are authorized in the QAM to make the accept/quarantine/reject decision. <strong>This authority cannot be overridden by sales, by management, or by the QA Manager.</strong> The QA Manager can authorize the inspector to re-inspect, can request additional information, or can take the part for separate investigation — but the inspector who finds non-conformance cannot be ordered to ignore it. This is a foundational principle of ASA-100 quality systems.</p>
HTML
        ],
        [
            'name'  => 'Lesson 2.6 — Shipping: Outbound Documentation and Packaging',
            'intro' => '<p>What goes out the door with a customer shipment.</p>',
            'content' => <<<'HTML'
<h3>Shipping: Outbound Requirements</h3>
<p>The same documentation rigor that applies at receiving applies at shipping. The customer receiving a part from TurbineWorks should have everything they need to add the part to their own traceability records.</p>

<h4>Required outbound documentation</h4>
<p>Every outbound shipment includes:</p>
<ul>
  <li><strong>Packing list</strong> — TurbineWorks-generated, listing every part, serial number, quantity, customer PO reference</li>
  <li><strong>Original 8130-3</strong> (or copy with proper TRUE COPY attestation per QAM) — must accompany the part</li>
  <li><strong>Certificate of Conformance</strong> — TurbineWorks-issued, attesting that the part has been received, inspected, and stored per ASA-100 procedures</li>
  <li><strong>Material certifications</strong> if required by customer PO</li>
  <li><strong>Country-of-origin declaration</strong> for export shipments</li>
  <li><strong>Shipper's Declaration for Dangerous Goods</strong> if any hazmat is involved (see Module 8)</li>
</ul>

<h4>Packaging requirements</h4>
<ul>
  <li>Parts must be packaged per <strong>ATA Spec 300</strong> (or equivalent) container categories appropriate to the part type</li>
  <li>ESD-sensitive parts in ESD-safe packaging (see Module 7)</li>
  <li>Moisture-sensitive parts in sealed bags with desiccant and humidity indicator</li>
  <li>Critical surfaces protected (caps on bearing seats, plugs in ports, edge protectors on blades)</li>
  <li>Outer container appropriate for transit method (heavier carton for ocean freight than air)</li>
  <li>Hazmat packaging meets 49 CFR / IATA DGR requirements per Module 8</li>
</ul>

<h4>Marking and labeling</h4>
<ul>
  <li>Part identification visible without opening packaging (part number, serial number, customer PO)</li>
  <li>"FRAGILE" / "THIS SIDE UP" / "DO NOT STACK" labels as appropriate</li>
  <li>Hazmat labels and markings per regulation (Module 8)</li>
  <li>Customer-specified labeling (some customers require specific routing barcodes, customer part numbers, etc.)</li>
</ul>

<h4>Carrier and method</h4>
<ul>
  <li>Carrier authorized by TurbineWorks (some carriers do not meet customer or regulatory requirements for hazmat or high-value cargo)</li>
  <li>Service level appropriate (next-day for AOG / Aircraft on Ground urgent shipments; ground for routine)</li>
  <li>Insurance and declared value appropriate to part value</li>
  <li>Tracking number captured and provided to customer</li>
</ul>

<h4>Shipping inspection — the final check</h4>
<p>Before the carton is sealed, a shipping inspector verifies:</p>
<ul>
  <li>Documentation in the carton matches the part in the carton (serial number, part number)</li>
  <li>Packaging is appropriate</li>
  <li>Labels are correct and legible</li>
  <li>The shipment matches the customer PO</li>
  <li>Hazmat is properly documented if applicable</li>
</ul>

<h4>Recordkeeping</h4>
<ul>
  <li>Copy of the shipping documentation filed against the part record</li>
  <li>Customer PO reference cross-linked to the inventory record showing which serial number went to which customer</li>
  <li>Carrier tracking number recorded</li>
  <li>Date and time of shipment recorded</li>
</ul>
<p>This creates the audit chain: a customer can show TurbineWorks the part they received, and TurbineWorks can produce every record from receiving inspection through outbound shipment — including who inspected, when, against what documentation. That chain is the entire value proposition of an ASA-100 accredited distributor.</p>
HTML
        ],
        [
            'name'  => 'Lesson 2.7 — Module 2 Summary and Key References',
            'intro' => '<p>Receiving and shipping inspection recap.</p>',
            'content' => <<<'HTML'
<h3>Module 2 Summary</h3>

<h4>What you should now be able to answer</h4>
<ol>
  <li>What are the five steps of the ASA-100 §6 receiving inspection sequence?</li>
  <li>What pre-receiving checks are performed before opening packaging?</li>
  <li>What documents accompany an aircraft part shipment and how do you verify each?</li>
  <li>What is the difference between Quarantine and Reject dispositions?</li>
  <li>What must be included in an outbound shipment from TurbineWorks?</li>
  <li>Why is the receiving inspector's accept/reject authority not subject to override by management?</li>
</ol>

<h4>Key Terms</h4>
<dl>
  <dt><strong>8130-3</strong></dt><dd>FAA Airworthiness Approval Tag — the U.S. parts release certificate.</dd>
  <dt><strong>EASA Form 1 / TCCA Form One</strong></dt><dd>European and Canadian equivalents, mutually recognized under bilateral agreements.</dd>
  <dt><strong>COC</strong></dt><dd>Certificate of Conformance — supplier attestation of part conformance to a specified standard.</dd>
  <dt><strong>Approved-supplier list</strong></dt><dd>The list of suppliers from which TurbineWorks accepts parts without additional qualification.</dd>
  <dt><strong>Quarantine</strong></dt><dd>Physically segregated holding area for parts with unresolved non-conformance.</dd>
  <dt><strong>ATA Spec 300</strong></dt><dd>Air Transport Association specification for reusable packaging containers.</dd>
</dl>

<h4>References</h4>
<ul>
  <li>ASA-100 §6 (Receiving Inspection)</li>
  <li>FAA AC 21-29D (SUP detection — receiving inspection focus)</li>
  <li>TurbineWorks QAM Section [TBD] — Receiving Inspection Procedure</li>
  <li>TurbineWorks QAM Section [TBD] — Shipping Inspection Procedure</li>
  <li>Engine-Parts course: FAA 8130-3 Block-by-Block Inspection (Reference Library)</li>
</ul>
HTML
        ],
    ];
}

// ============================================================================
// MODULE 4 — Parts and Warehousing (ASA-100 §7)
// ============================================================================
function local_twu_module_4_lessons(): array {
    return [
        [
            'name'  => 'Lesson 4.1 — ASA-100 §7 Storage and Handling Overview',
            'intro' => '<p>The storage requirements that protect inventory integrity.</p>',
            'content' => <<<'HTML'
<h3>ASA-100 §7 Storage and Handling</h3>
<p>Receiving inspection is the gate that lets parts into inventory. Storage and handling is what protects them once they are in. A part correctly inspected at receiving but stored improperly is an inventory loss — possibly an undetected one.</p>

<h4>The storage environment</h4>
<p>ASA-100 §7 requires that storage areas:</p>
<ul>
  <li>Are physically segregated by part status (serviceable, quarantine, unserviceable, scrap)</li>
  <li>Protect parts from physical damage, contamination, deterioration, and unauthorized access</li>
  <li>Maintain environmental conditions appropriate to the parts stored (temperature, humidity, ESD control)</li>
  <li>Are organized so that any part can be located efficiently for shipment or audit</li>
  <li>Are clean and free of FOD generators</li>
</ul>

<h4>Status zones</h4>
<p>Every part in the warehouse is in one of four status zones:</p>
<dl>
  <dt><strong>Serviceable</strong></dt>
  <dd>Inspected and approved. Available for sale. Has a TurbineWorks serviceable tag attached.</dd>
  <dt><strong>Quarantine</strong></dt>
  <dd>Inspected and flagged for non-conformance investigation. Cannot be sold. Has a Quarantine tag with reason.</dd>
  <dt><strong>Unserviceable</strong></dt>
  <dd>Has been determined unfit for service but not yet finally dispositioned. Cannot be sold. May be repairable or may need to be scrapped after evaluation.</dd>
  <dt><strong>Scrap</strong></dt>
  <dd>Final disposition is destruction. Awaiting mutilation per FAA AC 21-38 (see Module 5).</dd>
</dl>
<p>The zones are <em>physically segregated</em>, not just logically. An auditor walking the warehouse must be able to immediately see which area is which. Color-coded shelving, painted floor lines, locked cages, signage — the implementation varies, but the segregation is non-negotiable.</p>

<h4>Why physical segregation matters</h4>
<p>If serviceable and quarantine parts share the same shelf, the inevitable human error — wrong tag, wrong location, distracted picker — puts a non-conforming part into a customer shipment. The whole point of quarantine is to make that error physically impossible.</p>

<h4>Access control</h4>
<p>Storage areas have controlled access. Not everyone in the building has the authority to move parts in and out of inventory. Typically:</p>
<ul>
  <li>Receiving Inspectors place parts into serviceable inventory</li>
  <li>Warehouse personnel pull parts for shipping</li>
  <li>The QA Manager (or designate) is the only person who can move parts between status zones (e.g., from Quarantine to Serviceable after non-conformance resolution)</li>
  <li>Visitors and unauthorized personnel cannot enter storage areas unaccompanied</li>
</ul>

<h4>What the auditor checks</h4>
<ul>
  <li>Random part pull: "show me this part's location" — must be findable within minutes</li>
  <li>Walk the warehouse: are the status zones visibly segregated?</li>
  <li>Tag check: every part has its required tag (serviceable, quarantine, unserviceable, or scrap)?</li>
  <li>Environmental conditions: is the ESD area properly grounded? Are shelf-life parts within date?</li>
  <li>Cleanliness: is the warehouse free of dust, debris, food, beverages, FOD generators?</li>
</ul>

<p><em>[TurbineWorks Procedure Reference: insert TurbineWorks warehouse layout diagram and zone-coding scheme here.]</em></p>
HTML
        ],
        [
            'name'  => 'Lesson 4.2 — Segregation: Serviceable, Quarantine, Unserviceable, Scrap',
            'intro' => '<p>How parts move between status zones and the records that track every move.</p>',
            'content' => <<<'HTML'
<h3>Segregation and Status Transitions</h3>
<p>A part's status in the inventory system and its physical location in the warehouse must always agree. When they don't, the cause is usually a missed status transition — a part was physically moved without the system being updated, or vice versa.</p>

<h4>Movement triggers</h4>
<table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse;">
  <tr style="background:#0d2240; color:#fff;">
    <th>From</th><th>To</th><th>Trigger</th><th>Who authorizes</th>
  </tr>
  <tr><td>Receiving</td><td>Serviceable</td><td>Receiving inspection: Accept disposition</td><td>Receiving Inspector</td></tr>
  <tr><td>Receiving</td><td>Quarantine</td><td>Receiving inspection: Quarantine disposition</td><td>Receiving Inspector</td></tr>
  <tr><td>Receiving</td><td>Reject (back to supplier)</td><td>Receiving inspection: Reject disposition</td><td>Receiving Inspector + QA Manager approves return</td></tr>
  <tr><td>Quarantine</td><td>Serviceable</td><td>Non-conformance resolved, part now conforms</td><td>QA Manager only</td></tr>
  <tr><td>Quarantine</td><td>Unserviceable / Scrap</td><td>Non-conformance investigation concluded the part is not serviceable</td><td>QA Manager only</td></tr>
  <tr><td>Serviceable</td><td>Quarantine</td><td>New non-conformance discovered after acceptance (rare but possible)</td><td>QA Manager only</td></tr>
  <tr><td>Serviceable</td><td>Customer shipment</td><td>Customer order pulls the part</td><td>Warehouse + Shipping Inspector</td></tr>
  <tr><td>Unserviceable</td><td>Scrap</td><td>Final determination: not repairable</td><td>QA Manager</td></tr>
  <tr><td>Scrap</td><td>Destroyed</td><td>Mutilation per FAA AC 21-38 completed</td><td>QA Manager certifies destruction</td></tr>
</table>

<h4>Every move is recorded</h4>
<p>Each transition above generates a record. The record includes:</p>
<ul>
  <li>Part number, serial number</li>
  <li>From zone → to zone</li>
  <li>Date and time</li>
  <li>Reason for transition</li>
  <li>Authorizing person (initials or signature)</li>
</ul>
<p>This audit trail is what lets TurbineWorks answer the question "where has this part been?" The answer should never be "we don't know."</p>

<h4>Why QA Manager authorization for some transitions</h4>
<p>Transitions that move parts <em>out of</em> Quarantine require QA Manager authorization because the QA Manager is the only role authorized to disposition non-conforming material. This is not a bureaucratic hurdle — it is the control that prevents informal "well, it looked OK so I moved it to serviceable" decisions that compromise inventory integrity.</p>

<h4>Cycle counting and reconciliation</h4>
<p>Periodic cycle counts verify that the physical inventory matches the inventory system. Discrepancies require investigation:</p>
<ul>
  <li>Physical part present but not in system: how did it get here? When was the receiving inspection done?</li>
  <li>Part in system but not physically present: was it shipped without updating system? Stolen? Misplaced?</li>
  <li>Part in wrong location: how did the transition record miss this move?</li>
</ul>
<p>Reconciliation discrepancies feed the corrective-action system. A pattern of discrepancies in the same area suggests a process problem, not a one-off mistake.</p>

<h4>What an auditor will probe</h4>
<ul>
  <li>"Walk me through the last 10 quarantine transitions." Are they all documented? Did the QA Manager authorize each move out of quarantine?</li>
  <li>"Show me a part that has been scrapped." Where is the mutilation record? Who certified the destruction?</li>
  <li>"Pick a random part — show me its complete movement history from receipt to today."</li>
</ul>
HTML
        ],
        [
            'name'  => 'Lesson 4.3 — Shelf-Life Management',
            'intro' => '<p>Parts with limited storage life: identifying them, tracking them, and rotating stock.</p>',
            'content' => <<<'HTML'
<h3>Shelf-Life Management</h3>
<p>Many aviation parts have a defined storage life beyond which they cannot be sold. The shelf-life clock starts at the manufacturer's specified date (usually cure date for elastomers, manufacture date for sealants and adhesives) and ends at an expiration date.</p>

<h4>Common shelf-life-limited categories</h4>
<ul>
  <li><strong>O-rings, seals, gaskets</strong> — elastomers degrade over time. Typical shelf life 5-10 years depending on compound, but some are shorter.</li>
  <li><strong>Sealants and adhesives</strong> — chemical aging. Often 6-24 months from manufacture.</li>
  <li><strong>Battery cells</strong> — chemistry-dependent. Some lithium primary cells have 5+ year shelf life; lead-acid much shorter.</li>
  <li><strong>Inflatables</strong> — life vests, slides, oxygen masks. Defined service life.</li>
  <li><strong>Pyrotechnics</strong> — flares, fire bottles. Strict shelf life. Hazmat handling required (Module 8).</li>
  <li><strong>Some lubricants and hydraulic fluids</strong> — particularly mil-spec fluids in sealed containers.</li>
</ul>

<h4>Tracking shelf-life</h4>
<p>Every shelf-life-limited part is tagged at receiving with:</p>
<ul>
  <li>Cure / manufacture date (from the supplier documentation)</li>
  <li>Expiration date (calculated from cure date plus shelf life per OEM/spec)</li>
  <li>Storage requirements (temperature, humidity, lighting if applicable)</li>
</ul>
<p>The inventory system tracks expiration dates and flags parts approaching expiration. ASA-100 requires that expired parts cannot be shipped to customers.</p>

<h4>FIFO and stock rotation</h4>
<p>First-In-First-Out (FIFO) is the default rule: ship the oldest stock first, so newer stock doesn't sit on the shelf until it expires while older stock continues to be picked. Warehouse layout and picking procedures must support FIFO — typically achieved by stocking in date order with the oldest at the front of the bin.</p>

<h4>What happens at expiration</h4>
<p>An expired part is moved to Unserviceable status. The disposition options:</p>
<ul>
  <li><strong>Scrap</strong> — most common. Expired elastomers and sealants are scrapped per ASA-100 §8 and FAA AC 21-38.</li>
  <li><strong>Re-life</strong> — possible for some parts if the manufacturer authorizes a re-life inspection (e.g., re-test of certain batteries). Documentation must support this — cannot be done informally.</li>
  <li><strong>Return to manufacturer</strong> — some manufacturers offer credit for expired stock returned.</li>
</ul>
<p><strong>Expired parts cannot be re-tagged as serviceable.</strong> An auditor will specifically look for expired parts in serviceable inventory — it is a common finding at first audits.</p>

<h4>Environmental conditions affecting shelf life</h4>
<p>Shelf life is specified assuming proper storage conditions. Improper conditions accelerate aging and effectively reduce shelf life:</p>
<ul>
  <li>Temperature outside spec range (elastomers degrade faster in heat)</li>
  <li>Direct sunlight or UV exposure (degrades rubber, plastic, some elastomers)</li>
  <li>Humidity outside spec (corrosion, moisture absorption)</li>
  <li>Ozone exposure (degrades rubber rapidly — keep elastomers away from electric motors that generate ozone)</li>
</ul>
<p>If storage conditions are exceeded for a documented period, the part's effective shelf life may be reduced. Document any environmental excursions (HVAC failure, etc.) and notify QA Manager — affected parts may need to be re-evaluated.</p>

<h4>What the auditor checks</h4>
<ul>
  <li>Random pull of shelf-life parts: are dates legible and within life?</li>
  <li>Expired parts in inventory: are any in serviceable? (Hard finding if yes.)</li>
  <li>FIFO discipline: pick a part and look at neighboring stock — is the oldest at the front?</li>
  <li>Environmental records: temperature/humidity logs available?</li>
</ul>

<p><em>[TurbineWorks Procedure Reference: insert TurbineWorks shelf-life monitoring SOP and the list of part categories tracked here.]</em></p>
HTML
        ],
        [
            'name'  => 'Lesson 4.4 — FOD Prevention',
            'intro' => '<p>Foreign Object Damage: what it is, where it comes from, and how to prevent it.</p>',
            'content' => <<<'HTML'
<h3>Foreign Object Damage (FOD) Prevention</h3>
<p>FOD — Foreign Object Damage — is any damage to a part caused by a foreign object during handling, packaging, or storage. In a turbine engine, a single small nick on a compressor blade leading edge can propagate into a crack and a catastrophic failure. FOD prevention is fundamental to warehouse discipline.</p>

<h4>Where FOD comes from</h4>
<p>FOD generators in a warehouse environment:</p>
<ul>
  <li>Tools left in or near parts (most common single source)</li>
  <li>Loose hardware: nuts, washers, fasteners on floor or shelves</li>
  <li>Packaging debris: cardboard fragments, tape pieces, plastic bag scraps</li>
  <li>Personal items: pens, jewelry, hardware in pockets</li>
  <li>Food and beverages: crumbs, spills</li>
  <li>Building materials: concrete chips, paint flakes, ceiling tile dust</li>
  <li>Other parts: parts bumping into each other during transport, unprotected critical surfaces</li>
</ul>

<h4>FOD-prone surfaces</h4>
<p>Some surfaces are more FOD-sensitive than others:</p>
<ul>
  <li><strong>Turbine and compressor blade leading edges</strong> — single biggest concern in engine parts</li>
  <li><strong>Sealing surfaces</strong> — bearing races, O-ring grooves, mating flanges</li>
  <li><strong>Optical surfaces</strong> — flame detectors, vibration sensors</li>
  <li><strong>Electrical contacts</strong> — connector pins, terminal blocks</li>
</ul>
<p>FOD-sensitive surfaces should be protected during storage: caps on bearing seats, plugs in ports, individual wrapping on blades, edge protectors on rotor disks.</p>

<h4>The FOD-free zone</h4>
<p>Areas of the warehouse where FOD-sensitive parts are handled are designated FOD-Free Zones. In a FOD-Free Zone:</p>
<ul>
  <li>No food or beverages</li>
  <li>No loose tools or hardware on work surfaces</li>
  <li>Personnel empty pockets before entering, or use pocket organizers</li>
  <li>Jewelry (rings, watches, bracelets) removed</li>
  <li>Hair restrained</li>
  <li>Tools accounted for at start and end of every shift (tool control)</li>
  <li>FOD walks performed at defined intervals — visually sweep the area for any FOD generators</li>
</ul>

<h4>Tool control</h4>
<p>Every tool used near aviation parts is accounted for. A torque wrench left inside a part's packaging can disappear into the customer shipment. Tool control practices:</p>
<ul>
  <li>Tool shadowboards: outlines showing each tool's storage location, so missing tools are visible at a glance</li>
  <li>Tool checkout logs: who has which tool and when</li>
  <li>End-of-shift accountability: every tool returned and verified before shift end</li>
  <li>Lost-tool reporting: a missing tool triggers a search and an investigation</li>
</ul>

<h4>FOD walks</h4>
<p>A FOD walk is a deliberate visual sweep of an area looking for FOD generators. Performed:</p>
<ul>
  <li>Before opening packaging on a FOD-sensitive part</li>
  <li>After completing a handling operation</li>
  <li>At defined intervals (start of shift, after breaks)</li>
  <li>After any unusual event (dropped item, spilled material)</li>
</ul>

<h4>Damage from FOD</h4>
<p>FOD damage may be:</p>
<ul>
  <li><strong>Obvious</strong> — visible nick, dent, scratch on a critical surface. Part goes to Unserviceable for evaluation.</li>
  <li><strong>Subtle</strong> — small mark that may or may not be acceptable per OEM repair limits. Requires evaluation by qualified inspection.</li>
  <li><strong>Latent</strong> — damage that initiates a crack that doesn't manifest until the part is in service.</li>
</ul>
<p>Any suspected FOD damage to a part is documented and the part is moved to Unserviceable for evaluation. <em>Do not attempt to "polish out" FOD damage</em> — that is a repair, which only authorized repair stations can perform.</p>

<h4>Reporting FOD incidents</h4>
<p>Any FOD incident — even one that didn't damage a part — should be reported. Examples:</p>
<ul>
  <li>Found a tool inside a part's packaging during a check (no damage, but the FOD escape was a near-miss)</li>
  <li>Dropped a part (no visible damage, but inspection required)</li>
  <li>Found loose hardware on a serviceable parts shelf (FOD generator, even if no part was damaged)</li>
</ul>
<p>Near-misses are early indicators of process problems. Reporting them lets the system improve before an actual incident.</p>
HTML
        ],
        [
            'name'  => 'Lesson 4.5 — Environmental Controls and Hazmat Storage',
            'intro' => '<p>Temperature, humidity, ESD, and hazardous materials storage in the warehouse.</p>',
            'content' => <<<'HTML'
<h3>Environmental Controls</h3>
<p>Aviation parts have storage requirements that go beyond "keep them clean and dry." Specific environmental controls protect specific part types.</p>

<h4>Temperature and humidity</h4>
<p>General warehouse: typically 60-80 °F (15-27 °C) and 30-60% relative humidity is acceptable for most parts. Specific parts may require tighter control:</p>
<ul>
  <li>Elastomers and rubber: cool, dark, low-ozone storage. Excursions above 100 °F accelerate aging.</li>
  <li>Sealants: refrigerated storage often required (32-40 °F / 0-4 °C). Bring to room temperature before use.</li>
  <li>Adhesives: similar to sealants.</li>
  <li>Batteries: temperature-controlled storage prevents capacity loss.</li>
  <li>Moisture-sensitive electronic components: per IPC J-STD-033, may require dry storage or sealed bags with desiccant.</li>
</ul>
<p>Environmental monitoring: temperature and humidity sensors with logging. Excursions outside spec range trigger an investigation: were any parts affected? Do they need to be re-evaluated?</p>

<h4>ESD-controlled areas</h4>
<p>See Module 7 (ESD Handling) for the full treatment. Briefly: areas where ESD-sensitive parts are handled must be ANSI/ESD S20.20 compliant — grounded mats, wrist straps, ionizers, ESD-safe flooring, controlled humidity.</p>

<h4>Hazmat storage</h4>
<p>See Module 8 (Hazmat Identification) for the full regulatory treatment. Hazmat in storage:</p>
<ul>
  <li>Segregated from non-hazmat (and from incompatible hazmat classes)</li>
  <li>In containers per 49 CFR / IATA DGR specifications</li>
  <li>Marked and labeled per hazmat regulations</li>
  <li>Stored per the hazmat's specific requirements (flammables in fire-rated cabinets, oxidizers separated from organics, etc.)</li>
  <li>SDS (Safety Data Sheet) accessible</li>
  <li>Spill kit accessible</li>
</ul>

<h4>Critical environmental excursions</h4>
<p>If environmental conditions exceed spec for a documented period (HVAC failure, refrigerator failure, fire, water leak, etc.):</p>
<ul>
  <li>Document the excursion: what failed, when, duration, magnitude</li>
  <li>Identify affected parts: what was in the affected area during the excursion?</li>
  <li>Quarantine affected parts pending evaluation</li>
  <li>QA Manager dispositions: which parts can return to service, which need re-inspection, which must be scrapped?</li>
  <li>Open a corrective-action report</li>
</ul>

<h4>Lighting</h4>
<ul>
  <li>General warehouse: enough light to perform receiving inspection accurately</li>
  <li>UV-sensitive parts (some elastomers): protected from direct sunlight and high-UV fluorescent lighting</li>
  <li>Optical components: protected from contamination during handling (lint-free environment, white glove handling)</li>
</ul>

<h4>Cleanliness</h4>
<ul>
  <li>Regular housekeeping schedule documented and executed</li>
  <li>No food, no drinks in storage areas</li>
  <li>Floors swept and free of FOD generators</li>
  <li>Shelving wiped down to prevent dust buildup on parts</li>
  <li>Trash removed regularly (don't accumulate cardboard or packaging waste in storage areas)</li>
</ul>

<h4>What the auditor checks</h4>
<ul>
  <li>Walk-through of storage areas: visible cleanliness, organization, segregation</li>
  <li>Environmental records: temperature/humidity logs reviewed, excursions investigated</li>
  <li>Hazmat segregation: are flammables, oxidizers, corrosives properly separated?</li>
  <li>SDS accessibility: can you produce the SDS for any hazmat on-site within minutes?</li>
  <li>ESD area integrity: testing of grounding, wrist straps</li>
</ul>
HTML
        ],
        [
            'name'  => 'Lesson 4.6 — Module 4 Summary and Key References',
            'intro' => '<p>Storage and warehousing recap.</p>',
            'content' => <<<'HTML'
<h3>Module 4 Summary</h3>

<h4>What you should now be able to answer</h4>
<ol>
  <li>What are the four status zones in the warehouse and what is the difference between them?</li>
  <li>Who is authorized to move parts out of Quarantine, and why is the authority limited to that role?</li>
  <li>What is FIFO and why does it matter for shelf-life parts?</li>
  <li>What is FOD and what are the main sources in a warehouse?</li>
  <li>What environmental conditions matter for which part categories?</li>
</ol>

<h4>Key Terms</h4>
<dl>
  <dt><strong>FOD</strong></dt><dd>Foreign Object Damage — damage caused by a foreign object during handling, packaging, or storage.</dd>
  <dt><strong>FIFO</strong></dt><dd>First-In-First-Out — stock rotation rule that ships oldest stock first.</dd>
  <dt><strong>Cure date</strong></dt><dd>The manufacturer-specified date from which shelf life is calculated for elastomers and similar materials.</dd>
  <dt><strong>Shelf life</strong></dt><dd>The defined period during which a part may be stored before expiration.</dd>
  <dt><strong>Tool control</strong></dt><dd>Practice of accounting for every tool used near aviation parts, preventing FOD from lost tools.</dd>
</dl>

<h4>References</h4>
<ul>
  <li>ASA-100 §7 (Storage and Handling)</li>
  <li>FAA AC 21-38 (Disposition of Unsalvageable Parts) — for expired/unsalvageable disposition</li>
  <li>ATA Spec 300 — Packaging</li>
  <li>NAS 412 / National Aerospace FOD Prevention Inc. — industry FOD prevention guidance</li>
</ul>
HTML
        ],
    ];
}

// ============================================================================
// MODULE 5 — Recordkeeping (ASA-100 §8)
// ============================================================================
function local_twu_module_5_lessons(): array {
    return [
        [
            'name'  => 'Lesson 5.1 — ASA-100 §8 Recordkeeping Overview',
            'intro' => '<p>Why recordkeeping is the audit-evidence backbone of an ASA-100 distributor.</p>',
            'content' => <<<'HTML'
<h3>ASA-100 §8 Recordkeeping</h3>
<p>The entire ASA-100 quality system rests on records. A receiving inspection that wasn't documented didn't happen, as far as an auditor is concerned. A SUP that was caught and reported but where the report wasn't filed is indistinguishable from a SUP that was caught and ignored.</p>

<h4>What records are required</h4>
<p>ASA-100 §8 requires retention of records covering every quality-system activity:</p>
<ul>
  <li>Receiving inspection records (every part, every receipt)</li>
  <li>Original 8130-3 tags (or equivalents)</li>
  <li>Certificates of Conformance</li>
  <li>Material test reports</li>
  <li>Non-conformance reports and corrective actions</li>
  <li>SUP reports and FAA filings</li>
  <li>Mutilation records for scrapped parts</li>
  <li>Internal audit records and findings</li>
  <li>Management review minutes</li>
  <li>Training records (initial and recurring, per employee)</li>
  <li>Supplier qualification and re-evaluation records</li>
  <li>Customer complaint records</li>
  <li>Outbound shipping documentation (what went to whom, when)</li>
  <li>Calibration records for inspection tools</li>
</ul>

<h4>Retention period</h4>
<p>The ASA-100 standard sets minimum retention periods. The typical baseline is <strong>7 years</strong>, but for some records (LLP traceability, for example) the retention is effectively forever — the back-to-birth record must be available for the entire service life of the part.</p>
<p>Customer contracts may impose longer retention requirements. The retention schedule in the TurbineWorks QAM is the controlling document — when in doubt, longer is safer.</p>

<h4>Format: physical or electronic</h4>
<p>Records may be paper or electronic. Electronic records are acceptable if:</p>
<ul>
  <li>The format is durable (PDF, TIFF, etc. — not proprietary formats that may become unreadable)</li>
  <li>The records are backed up against loss</li>
  <li>Electronic signatures, where used, are verifiable and tamper-evident</li>
  <li>The records can be retrieved for an auditor in reasonable time</li>
</ul>
<p>Originals of FAA 8130-3 tags are sometimes required to be retained in physical form depending on customer or regulatory requirement. The TurbineWorks document control system specifies which records require physical originals.</p>

<h4>Accessibility</h4>
<p>Records must be retrievable on demand. An auditor asking for the records on a specific part should receive them within minutes, not days. This requires:</p>
<ul>
  <li>Consistent organization (by part serial number, by date, by purchase order)</li>
  <li>Index or search capability</li>
  <li>Cross-references (the receiving inspection record links to the 8130-3 links to the COC links to the shipping record)</li>
  <li>Personnel trained on retrieval</li>
</ul>

<h4>Tamper evidence</h4>
<p>Records cannot be altered after the fact. If a record contains an error:</p>
<ul>
  <li>Do not erase, white-out, or overwrite the original entry</li>
  <li>Draw a single line through the error so it remains legible</li>
  <li>Write the correction beside it</li>
  <li>Initial and date the correction</li>
</ul>
<p>Electronic systems with audit trails (every change logged with who/when) satisfy this requirement automatically. Direct database edits that bypass the audit trail are a finding.</p>

<p><em>[TurbineWorks Procedure Reference: insert TurbineWorks records-retention schedule and document-control SOP here.]</em></p>
HTML
        ],
        [
            'name'  => 'Lesson 5.2 — Records Retention Schedule',
            'intro' => '<p>What gets kept, for how long, and how it ages out.</p>',
            'content' => <<<'HTML'
<h3>Records Retention Schedule</h3>
<p>The records-retention schedule defines, for every record category, the minimum retention period. ASA-100 §8 provides the baseline; customer contracts and TurbineWorks policy may impose longer requirements.</p>

<h4>Typical retention periods</h4>
<table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse;">
  <tr style="background:#0d2240; color:#fff;">
    <th>Record Type</th><th>Minimum Retention</th><th>Notes</th>
  </tr>
  <tr><td>Receiving inspection records</td><td>7 years from inspection date</td><td>Tied to the part record</td></tr>
  <tr><td>Original 8130-3 tags</td><td>7 years from receipt</td><td>Some customers require originals</td></tr>
  <tr><td>Outbound shipping records</td><td>7 years from shipment</td><td>Includes the customer's COC issued by TurbineWorks</td></tr>
  <tr><td>LLP back-to-birth records</td><td>Service life of the part (effectively forever)</td><td>Cannot be aged out while the part may still be in service</td></tr>
  <tr><td>Non-conformance / corrective action</td><td>7 years from closure</td><td>Some persistent issues retained longer</td></tr>
  <tr><td>SUP reports and FAA filings</td><td>7 years (10+ recommended)</td><td>May be subject to FAA subpoena beyond standard retention</td></tr>
  <tr><td>Mutilation records</td><td>7 years</td><td>Evidence that the part was destroyed and cannot re-enter supply</td></tr>
  <tr><td>Internal audit records</td><td>2 audit cycles minimum</td><td>So next-cycle auditor sees previous findings and resolutions</td></tr>
  <tr><td>Training records</td><td>Employment + 7 years</td><td>Per-employee training history</td></tr>
  <tr><td>Supplier qualification</td><td>While supplier is approved + 7 years after removal</td><td>Removed-supplier records justify the removal decision</td></tr>
  <tr><td>Calibration records (inspection tools)</td><td>Life of the tool + 7 years</td><td>Proves that tools used in past inspections were calibrated</td></tr>
  <tr><td>Management review minutes</td><td>5 years minimum</td><td>Shows the system is being actively managed</td></tr>
</table>

<h4>Aging records out</h4>
<p>Records past the retention period may be disposed of — but the disposal itself should be documented (what was disposed of, when, by whom, under what authority). Don't just delete or shred without a record.</p>
<p>Some records that have technically aged out may still be worth retaining: SUP investigations, major non-conformance corrective actions, anything related to a part that ended up in an accident or incident.</p>

<h4>Records lifecycle</h4>
<ol>
  <li><strong>Creation</strong> — record generated as part of the workflow (receiving inspection, shipment, etc.)</li>
  <li><strong>Filing</strong> — record placed in its retention location (paper file, electronic system) with cross-references</li>
  <li><strong>Active retention</strong> — record is in the active records system, retrievable on demand</li>
  <li><strong>Archive</strong> — record moves to archive storage (less-frequently accessed) but still retrievable</li>
  <li><strong>Disposal</strong> — record past retention is disposed of per the schedule, with disposal documented</li>
</ol>

<h4>Backup and disaster recovery</h4>
<p>Electronic records require a backup strategy. ASA-100 doesn't specify the technical implementation but expects that a fire, hardware failure, or ransomware attack would not destroy the records the audit relies on.</p>
<ul>
  <li>Off-site backup or cloud replication</li>
  <li>Tested restore procedures (regularly verify backups are usable)</li>
  <li>Documented recovery time objective (how quickly can records be restored after a loss?)</li>
</ul>

<h4>What the auditor checks</h4>
<ul>
  <li>Retention schedule documented and current</li>
  <li>Random pull of an old record (within retention): can it be produced?</li>
  <li>Aged-out records: were they disposed of per schedule with disposal documented?</li>
  <li>Backup strategy in place and tested?</li>
</ul>
HTML
        ],
        [
            'name'  => 'Lesson 5.3 — Back-to-Birth Traceability for Life Limited Parts',
            'intro' => '<p>The unbroken record chain that lets an LLP be installed on an aircraft.</p>',
            'content' => <<<'HTML'
<h3>Back-to-Birth Traceability for Life Limited Parts</h3>
<p>Life Limited Parts (LLPs) are critical engine components — turbine disks, compressor disks, shafts, certain bearings — with a defined retirement life (typically expressed in cycles, sometimes hours). An LLP installed past its life limit is a guaranteed catastrophic failure mode.</p>

<h4>What "back-to-birth" means</h4>
<p>An LLP's traceability must reach back to its manufacture ("birth"). The record chain must show:</p>
<ul>
  <li>Original manufacturer, part number, serial number, date of manufacture</li>
  <li>Every installation on an aircraft engine, with TSN/CSN (time/cycles since new) at installation</li>
  <li>Every removal, with TSN/CSN at removal</li>
  <li>Every overhaul or repair</li>
  <li>Cumulative TSN/CSN throughout life</li>
  <li>Current TSN/CSN and remaining life at the present time</li>
</ul>
<p>An LLP with a gap in this chain — a period of unknown history, an undocumented installation, an unverified TSN/CSN value — cannot be sold for installation. It is effectively scrap, even if physically perfect.</p>

<h4>Why the chain matters</h4>
<p>Consider a turbine disk with a life limit of 20,000 cycles. Currently shows 8,000 cycles. Remaining life: 12,000 cycles. Customer pays for that remaining life.</p>
<p>Now suppose the disk's records have a 3-year gap when it was at an unidentified repair facility. Maybe it accumulated 5,000 more cycles in that period. Maybe it didn't. There is no way to know. The disk's effective remaining life is unknown — possibly zero, possibly the full 12,000 cycles. <em>You cannot sell unknown remaining life on an LLP.</em></p>

<h4>What documents make up the chain</h4>
<ul>
  <li>Original birth certificate or manufacturer's release</li>
  <li>Engine log book entries from each operator the part has been on</li>
  <li>Repair station release certificates from each overhaul</li>
  <li>Removal documentation (when, why, TSN/CSN)</li>
  <li>FAA 8130-3 tags from each transition of ownership</li>
  <li>OEM authorized repair documentation</li>
</ul>

<h4>Receiving an LLP at TurbineWorks</h4>
<p>LLP receiving inspection has extra steps beyond standard receiving:</p>
<ul>
  <li>Verify the back-to-birth documentation is complete (no gaps)</li>
  <li>Verify TSN/CSN values are consistent across documents</li>
  <li>Verify against OEM's current life limit (life limits can change — current OEM publication wins)</li>
  <li>Calculate remaining life and document</li>
  <li>If documentation is incomplete or shows gaps, the part is quarantined and the supplier is contacted for additional history</li>
</ul>

<h4>Selling an LLP</h4>
<p>TurbineWorks' COC accompanying the sale documents the remaining life as of date of shipment. The customer's installer will further document installation TSN/CSN against their engine.</p>

<h4>OEM life limit changes</h4>
<p>OEMs occasionally revise life limits — sometimes upward (better data shows the part lasts longer than originally certified), sometimes downward (in-service failures or new analysis show the original limit was too generous). The currently-published life limit at time of installation controls. A part previously sold with "8,000 cycles remaining" may have a different remaining life if the OEM lowered the limit between sale and installation.</p>

<h4>The cost of a missed gap</h4>
<p>Selling an LLP with incomplete back-to-birth records is one of the most serious findings a distributor can have. It compromises every customer that installed parts from the affected lot. It may trigger a fleet-wide inspection by the operator. It is grounds for accreditation suspension.</p>

<h4>What the auditor checks</h4>
<ul>
  <li>Random LLP pull: produce the complete back-to-birth chain</li>
  <li>LLP COCs issued: do they document remaining life consistently with the records?</li>
  <li>LLP receiving: how does TurbineWorks verify back-to-birth on incoming LLPs?</li>
  <li>Gap handling: when has TurbineWorks identified a gap, and what was the disposition?</li>
</ul>
HTML
        ],
        [
            'name'  => 'Lesson 5.4 — FAA AC 21-38: Disposition of Unsalvageable Parts',
            'intro' => '<p>How parts that cannot be used must be destroyed to prevent re-entry into the supply chain.</p>',
            'content' => <<<'HTML'
<h3>FAA AC 21-38: Disposition of Unsalvageable Aircraft Parts</h3>
<p>When a part is determined to be unsalvageable — beyond economical repair, life-expired, or confirmed SUP after FAA investigation — it must be permanently destroyed so it cannot re-enter the supply chain. FAA Advisory Circular 21-38 specifies how.</p>

<h4>Why mutilation is required</h4>
<p>Without mutilation, a scrapped part can be picked out of a dumpster, re-cleaned, re-marked, and re-sold by someone willing to commit fraud. Mutilation makes the part physically unusable as an aircraft part.</p>

<h4>What "mutilation" means</h4>
<p>AC 21-38 defines acceptable mutilation methods. The principle: the part must be damaged such that it cannot be returned to a serviceable condition by any reasonable repair process, AND such that critical features (markings, serial numbers, data plates) are destroyed.</p>
<p>Common methods (vary by part type):</p>
<ul>
  <li>Crushing under hydraulic press</li>
  <li>Cutting into multiple pieces with a saw or shear</li>
  <li>Melting (for some metals)</li>
  <li>Drilling holes in critical surfaces</li>
  <li>Removing or grinding off the data plate</li>
  <li>Stamping "MUTILATED" or similar irreversible marking</li>
  <li>Acid etching to destroy identifiers</li>
</ul>
<p>The specific method depends on the part type. A turbine blade can be cut into pieces. A printed circuit board may be cut and have ICs delaminated. A composite panel may be ground or shredded.</p>

<h4>What does NOT count as mutilation</h4>
<ul>
  <li>"Painting over" the data plate (paint comes off)</li>
  <li>Removing the data plate and discarding it (someone could find it and stamp it onto a different part)</li>
  <li>Stamping "NOT FOR FLIGHT" on the part (stamping can be ground off)</li>
  <li>Storing in a "scrap" area without destruction (parts can be diverted out)</li>
</ul>

<h4>Who performs mutilation</h4>
<p>Mutilation is performed by trained personnel under QA Manager oversight. The QA Manager (or designate) witnesses or verifies the mutilation. A part cannot be designated "mutilated" without verification.</p>

<h4>Documentation</h4>
<p>Every mutilation generates a record:</p>
<ul>
  <li>Part number, serial number (or lot number)</li>
  <li>Reason for mutilation (life-expired, BER, SUP, etc.)</li>
  <li>Method of mutilation</li>
  <li>Date mutilated</li>
  <li>Personnel who performed mutilation</li>
  <li>QA Manager (or designate) certification of mutilation</li>
  <li>Photographs (recommended) showing the mutilated state</li>
</ul>
<p>The mutilation record is retained per the records retention schedule (typically 7+ years). The record is the evidence that the part will not re-enter the supply chain.</p>

<h4>Disposal of mutilated parts</h4>
<p>After mutilation, the part can be disposed of as scrap metal or general waste. Some materials (cadmium plating, beryllium, hazardous coatings) require hazardous-waste disposal. Some high-value materials (titanium, nickel-base superalloys) have salvage value as scrap — sell to a scrap dealer with documentation.</p>

<h4>The supply-chain integrity loop</h4>
<p>Mutilation is the closing of the loop. Without it, the SUP-prevention work at the front of the operation is undone at the back. ASA auditors specifically check mutilation handling because they understand it is the single point at which an inadequate process most directly causes SUP re-entry to the supply chain.</p>

<p><em>[TurbineWorks Procedure Reference: insert TurbineWorks mutilation SOP, including approved methods per part category and the designated mutilation area location.]</em></p>
HTML
        ],
        [
            'name'  => 'Lesson 5.5 — Document Control and Revision Management',
            'intro' => '<p>Keeping the documents that govern operations current and approved.</p>',
            'content' => <<<'HTML'
<h3>Document Control</h3>
<p>The QAM, SOPs, work instructions, forms, and procedures that govern TurbineWorks operations are themselves controlled documents. Document control ensures that everyone is working from the current revision, that obsolete revisions are retrieved, and that changes are reviewed and approved.</p>

<h4>What is a "controlled document"</h4>
<p>A controlled document is one that:</p>
<ul>
  <li>Has a unique identifier (document number)</li>
  <li>Has a revision number or letter</li>
  <li>Has an effective date</li>
  <li>Has been formally approved (signature of authorized person)</li>
  <li>Is distributed to all who need it</li>
  <li>Has obsolete revisions retrieved when superseded</li>
</ul>
<p>An uncontrolled copy (someone's printout from 6 months ago kept in a desk drawer) is not the current revision. Working from an uncontrolled copy is a finding.</p>

<h4>The TurbineWorks document hierarchy</h4>
<pre>
QAM                  — Quality Assurance Manual (top-level)
├── Procedures (TWP-xx)   — Process-level (Receiving, Storage, Shipping, etc.)
├── Work Instructions     — Step-by-step task instructions
├── Forms (TWF-xx)        — Records templates (TWF-4 training log, etc.)
└── External references   — FAA ACs, ASA-100, OEM publications
</pre>

<h4>Revision control</h4>
<p>When a document is revised:</p>
<ol>
  <li>The change is proposed (revision request)</li>
  <li>The change is reviewed by appropriate authority (typically QA Manager + affected process owner)</li>
  <li>The change is approved</li>
  <li>The new revision number/letter is assigned</li>
  <li>The effective date is set</li>
  <li>The new revision is distributed to all controlled locations</li>
  <li>Obsolete copies are retrieved (or marked OBSOLETE if kept for historical reference)</li>
  <li>Training on the change is delivered to affected personnel (and documented)</li>
</ol>

<h4>Form control</h4>
<p>Forms (like TWF-4 — Training Log) are documents too. Every blank form in use should be the current revision. When a form is revised:</p>
<ul>
  <li>Old blank forms are retrieved from circulation</li>
  <li>The new form is distributed</li>
  <li>Records created on old forms remain valid (they document the work done at the time)</li>
</ul>

<h4>Document control register</h4>
<p>A register lists every controlled document, its current revision, effective date, owner, and distribution list. The register is itself a controlled document — kept current.</p>

<h4>Why this matters</h4>
<p>ASA auditors test document control rigorously. They will:</p>
<ul>
  <li>Pull a form used recently and verify it was the current revision at the time</li>
  <li>Check the QAM revision on file against the QAM the QA Manager refers to</li>
  <li>Ask employees to produce the procedure they follow for a specific task and verify it is current</li>
  <li>Look for uncontrolled copies in work areas (a printed SOP at someone's workstation that is one revision behind is a finding)</li>
</ul>

<h4>Common document control failures</h4>
<ul>
  <li>SOP revised but the revision date never set</li>
  <li>New revision distributed but old copies still in use because no one retrieved them</li>
  <li>Forms revised but printed stockpiles still circulating</li>
  <li>Document register out of date — current revision in register doesn't match the actual current revision</li>
  <li>External references (FAA ACs) not updated when FAA issues a new revision</li>
</ul>

<p><em>[TurbineWorks Procedure Reference: insert TurbineWorks document control SOP and register location here.]</em></p>
HTML
        ],
        [
            'name'  => 'Lesson 5.6 — Module 5 Summary and Key References',
            'intro' => '<p>Recordkeeping recap.</p>',
            'content' => <<<'HTML'
<h3>Module 5 Summary</h3>

<h4>What you should now be able to answer</h4>
<ol>
  <li>What records must be retained under ASA-100 §8, and for how long?</li>
  <li>What does back-to-birth traceability mean for a Life Limited Part?</li>
  <li>What is mutilation, why is it required, and what methods are acceptable per FAA AC 21-38?</li>
  <li>What makes a document "controlled" and why does it matter?</li>
  <li>What is the correct way to make a correction to a record?</li>
</ol>

<h4>Key Terms</h4>
<dl>
  <dt><strong>LLP</strong></dt><dd>Life Limited Part — engine component with a defined retirement life in cycles or hours.</dd>
  <dt><strong>TSN / CSN</strong></dt><dd>Time Since New / Cycles Since New — cumulative operating time/cycles on a part.</dd>
  <dt><strong>Back-to-birth</strong></dt><dd>The unbroken record chain from a part's manufacture to its current state.</dd>
  <dt><strong>Mutilation</strong></dt><dd>Permanent physical destruction of a part so it cannot re-enter the supply chain.</dd>
  <dt><strong>Controlled document</strong></dt><dd>A document with formal revision control, approval, and distribution.</dd>
</dl>

<h4>References</h4>
<ul>
  <li>ASA-100 §8 (Recordkeeping)</li>
  <li>FAA AC 21-38 (Disposition of Unsalvageable Parts)</li>
  <li>FAA AC 120-77 (Maintenance Recordkeeping)</li>
  <li>14 CFR §43.10 (Maintenance Records)</li>
</ul>
HTML
        ],
    ];
}

// ============================================================================
// MODULE 6 — FAA AC 00-56 (Deep Dive)
// ============================================================================
function local_twu_module_6_lessons(): array {
    return [
        [
            'name'  => 'Lesson 6.1 — The Origin of AC 00-56',
            'intro' => '<p>How FAA arrived at industry-managed accreditation as the model for distributor oversight.</p>',
            'content' => <<<'HTML'
<h3>The Origin of FAA AC 00-56</h3>
<p>Module 3 introduced AC 00-56 as the framework that ASA-100 implements. Module 6 is the deep dive on the AC itself, why it exists, and what it actually requires.</p>

<h4>The problem in the 1980s-1990s</h4>
<p>The U.S. civil aviation parts supply chain was largely unregulated. Anyone could open as a parts broker. Documentation was paper-based and easily falsified. The FAA's own resources could not realistically inspect every broker, every distributor, every after-market reseller.</p>
<p>Counterfeit and unapproved parts entered the supply chain through brokers who either did not inspect documentation rigorously or actively participated in fraud. Several high-profile incidents (NTSB-investigated accidents traced to counterfeit or improperly-sourced parts) made it clear the supply chain needed structured oversight.</p>

<h4>The FAA's solution: industry accreditation</h4>
<p>Rather than create a new FAA enforcement bureaucracy to inspect every distributor (politically and budgetarily impractical), the FAA recognized that industry associations had the resources and expertise to do this work. The FAA's role would be to:</p>
<ul>
  <li>Recognize accreditation organizations that meet defined criteria</li>
  <li>Monitor those organizations' performance</li>
  <li>Maintain the public list of recognized organizations so customers know which accreditations to trust</li>
</ul>
<p>This is the model that became <strong>FAA Advisory Circular 00-56</strong>, first issued in 1996 and revised several times since. The current revision is 00-56B.</p>

<h4>Why "Advisory" not regulatory</h4>
<p>AC 00-56 is an Advisory Circular, not a regulation. The FAA cannot legally <em>require</em> a distributor to be accredited — there is no FAR mandating it. But the FAA can <em>recognize</em> accreditation as evidence of supply-chain integrity, and customers (airlines, MROs, government) can require accreditation as a contractual condition.</p>
<p>In practice, this voluntary structure has worked because the market enforces it. Major airlines will not buy from unaccredited distributors. The Department of Defense requires accreditation under DFARS clauses. The FAA's "recommendation" effectively functions as a market requirement.</p>

<h4>What AC 00-56 does NOT do</h4>
<ul>
  <li>Does not define a quality standard itself (that is the accreditation organization's job — ASA wrote ASA-100)</li>
  <li>Does not perform audits (the accreditation organization audits its members)</li>
  <li>Does not have enforcement authority over distributors (that comes through the accreditation contract)</li>
  <li>Does not certify individual employees or parts</li>
</ul>

<h4>What AC 00-56 DOES do</h4>
<ul>
  <li>Defines what an accreditation organization must require of its members (the floor — ASA-100 exceeds this in many areas)</li>
  <li>Defines what audit procedures the accreditation organization must use</li>
  <li>Defines how the FAA monitors and re-evaluates accreditation organizations</li>
  <li>Maintains the public list of FAA-recognized accreditation organizations</li>
</ul>

<h4>Why every TurbineWorks employee needs to know this</h4>
<p>Auditors ask "what regulation requires this procedure?" The honest answer for ASA-100 procedures is: no FAR directly requires it. ASA-100 requires it because TurbineWorks chose to be accredited to ASA-100. ASA-100 was written to meet AC 00-56 because ASA chose to be FAA-recognized.</p>
<p>The chain of authority is: <em>customer requirement → ASA-100 accreditation → ASA-100 standard → AC 00-56 framework</em>. Knowing this chain lets employees explain to an auditor (or to themselves) why the procedures matter.</p>
HTML
        ],
        [
            'name'  => 'Lesson 6.2 — Section-by-Section Walkthrough of AC 00-56B',
            'intro' => '<p>What each major section of the AC actually says.</p>',
            'content' => <<<'HTML'
<h3>AC 00-56B Section-by-Section</h3>
<p>This lesson walks through the major sections of AC 00-56B. Read the full AC at the link in the Reference Library — this lesson highlights what's most important.</p>

<h4>Section 1: Purpose</h4>
<p>Establishes the AC as guidance for industry accreditation of aircraft parts distributors. States the AC is voluntary but FAA-recognized.</p>

<h4>Section 2: Background</h4>
<p>The history that motivated the AC — concerns about unapproved parts entering the supply chain through inadequately-controlled distributors.</p>

<h4>Section 3: Definitions</h4>
<p>Key terms: Accreditation Organization (AO), accredited distributor, eligible part, quality system. The definitions establish what the AC's terms of art mean.</p>

<h4>Section 4: Accreditation Organization Requirements</h4>
<p>The substantive heart of the AC. To be FAA-recognized, an accreditation organization (like ASA) must:</p>
<ul>
  <li>Publish a written quality standard (ASA-100)</li>
  <li>Have qualified auditors with documented competency</li>
  <li>Have an audit program covering all relevant aspects of distributor operations</li>
  <li>Have a corrective-action follow-up process</li>
  <li>Maintain accreditation records</li>
  <li>Have a procedure for handling complaints about accredited distributors</li>
  <li>Have a procedure for de-accrediting distributors that fail to maintain compliance</li>
</ul>

<h4>Section 5: Distributor Quality System Elements</h4>
<p>The AC specifies the minimum elements a distributor's quality system must address (the floor — actual standards like ASA-100 require much more):</p>
<ul>
  <li>Receiving inspection</li>
  <li>Documentation control</li>
  <li>Identification and traceability</li>
  <li>Storage and handling</li>
  <li>Records</li>
  <li>Personnel training</li>
  <li>Internal audits</li>
  <li>Corrective action</li>
  <li>Management review</li>
</ul>
<p>These map roughly to ASA-100 sections — ASA-100 §6 is receiving inspection, §7 is storage and handling, §8 is records, etc.</p>

<h4>Section 6: Audit Procedures</h4>
<p>Defines minimum audit cadence, audit content, and audit reporting. Initial accreditation requires an on-site audit. Recurring audits are at intervals not to exceed 3 years.</p>

<h4>Section 7: FAA Oversight</h4>
<p>Defines how the FAA monitors accreditation organizations themselves. The FAA reviews each AO periodically, may conduct surveillance audits at accredited distributors, and may withdraw recognition of an AO that fails to maintain its program.</p>

<h4>Section 8: List of Recognized Accreditation Organizations</h4>
<p>Maintained as Appendix 1, periodically updated. Lists every currently-recognized AO. As of recent revisions, ASA is on this list. So is the FAA-recognized program operated by IAQG (which audits AS9120).</p>

<h4>Appendices</h4>
<ul>
  <li>Appendix 1: Recognized AO list</li>
  <li>Appendix 2: Procedure for an AO to apply for FAA recognition</li>
  <li>Appendix 3: Definitions and references</li>
</ul>

<h4>What changed in 00-56B vs. earlier revisions</h4>
<p>00-56B (current revision) tightened audit procedures, added more explicit counterfeit-parts provisions, and updated cross-references to current FAA regulations. Check the current FAA publication for the official text.</p>
HTML
        ],
        [
            'name'  => 'Lesson 6.3 — Mapping AC 00-56 to ASA-100 to TurbineWorks QAM',
            'intro' => '<p>How requirements flow down through the document hierarchy.</p>',
            'content' => <<<'HTML'
<h3>Requirements Flow-Down</h3>
<p>Every TurbineWorks procedure traces back through ASA-100 to AC 00-56. This lesson maps the flow.</p>

<h4>The flow-down diagram</h4>
<pre>
FAA AC 00-56B
   │
   ├── "Distributor must perform receiving inspection"
   │      │
   │      ▼
   │   ASA-100 §6 — detailed receiving inspection requirements
   │      │
   │      ▼
   │   TurbineWorks QAM Receiving Inspection Procedure
   │      │
   │      ▼
   │   Day-to-day inspector activity, documented on TWF-XX forms
   │
   ├── "Distributor must maintain records"
   │      │
   │      ▼
   │   ASA-100 §8 — specific record types, retention periods
   │      │
   │      ▼
   │   TurbineWorks QAM Records Retention Schedule
   │
   ├── "Distributor must train personnel"
   │      │
   │      ▼
   │   ASA-100 (training requirements throughout)
   │      │
   │      ▼
   │   TurbineWorks Training Program (TWU + TWF-4 records)
   │
   └── (... and so on for every requirement)
</pre>

<h4>Why this matters in an audit</h4>
<p>When an auditor questions a procedure, the answer always starts with the requirement and works down:</p>
<p><em>"This procedure exists because ASA-100 §6.3 requires verification of the 8130-3 form. ASA-100 was written to satisfy AC 00-56B Section 5, which requires distributor receiving inspection. The specific steps in this procedure are in TurbineWorks QAM section X.Y."</em></p>
<p>That answer demonstrates understanding of the regulatory chain. Compare to: <em>"We do it because the QA Manager said to."</em> The first answer passes audit scrutiny; the second invites deeper questions about whether the company actually understands its quality system.</p>

<h4>Where AC 00-56 is silent</h4>
<p>AC 00-56 sets minimums. ASA-100 typically exceeds them. For example:</p>
<ul>
  <li>AC 00-56 requires receiving inspection; ASA-100 specifies exactly what must be inspected</li>
  <li>AC 00-56 requires record retention; ASA-100 specifies 7 years for most records</li>
  <li>AC 00-56 requires training; ASA-100 requires both initial and recurring</li>
</ul>
<p>TurbineWorks operates to ASA-100, which means TurbineWorks exceeds AC 00-56 minimums in most areas. This is the intended design — AC 00-56 is the floor, not the ceiling.</p>

<h4>When AC 00-56 changes</h4>
<p>If the FAA revises AC 00-56, ASA reviews ASA-100 against the new revision and updates as needed. TurbineWorks then updates the QAM if ASA-100 changes affect TurbineWorks procedures. The flow-down propagates automatically — TurbineWorks does not need to monitor the FAA directly, only ASA-100 revisions.</p>

<h4>What the auditor expects</h4>
<p>An ASA auditor expects that:</p>
<ul>
  <li>The QA Manager can map any QAM procedure to the ASA-100 section it implements</li>
  <li>The QA Manager can map ASA-100 sections to the AC 00-56 requirements they satisfy</li>
  <li>Employees may not need to know the full flow-down, but should understand that "we do this because the standard requires it" — not "because someone says so"</li>
</ul>
HTML
        ],
        [
            'name'  => 'Lesson 6.4 — Other Accreditation Programs and Loss of Accreditation',
            'intro' => '<p>The broader accreditation landscape and what happens if accreditation is lost.</p>',
            'content' => <<<'HTML'
<h3>Other Programs and Loss of Accreditation</h3>

<h4>ASA-100 vs. AS9120</h4>
<p><strong>AS9120</strong> is the SAE/IAQG aerospace QMS standard for distributors. Like ASA-100, it is FAA-recognized under AC 00-56. The key differences:</p>
<table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse;">
  <tr style="background:#0d2240; color:#fff;">
    <th>Aspect</th><th>ASA-100</th><th>AS9120</th>
  </tr>
  <tr><td>Publisher</td><td>Aviation Suppliers Association</td><td>SAE / IAQG (International Aerospace Quality Group)</td></tr>
  <tr><td>Approach</td><td>Prescriptive — detailed specific requirements</td><td>QMS-focused — based on ISO 9001 + aerospace additions</td></tr>
  <tr><td>Typical scope</td><td>Aftermarket parts distribution</td><td>OEM-supply-chain distribution</td></tr>
  <tr><td>Audit duration</td><td>Typically 1-3 days for small distributors</td><td>Typically longer; ISO-style audit</td></tr>
  <tr><td>Cost</td><td>Generally lower</td><td>Generally higher</td></tr>
</table>
<p>Some distributors hold both. TurbineWorks may pursue AS9120 in the future if OEM-supply-chain business becomes a priority — the training overlap is significant (~70%), so building TWU to support both is an investment of an extra ~30% effort now for full future flexibility.</p>

<h4>Other recognized programs</h4>
<ul>
  <li><strong>TAC-2000</strong> — Transportation Association of Canada distributor standard</li>
  <li><strong>FAA AC 00-56B Appendix 1</strong> — full current list of recognized accreditation organizations</li>
</ul>

<h4>Loss of accreditation — what happens</h4>
<p>An accredited distributor can lose accreditation through:</p>
<ul>
  <li><strong>Failure to maintain compliance</strong> — major audit findings not corrected, repeat findings, deterioration of the quality system</li>
  <li><strong>Failure to renew</strong> — missing the recurring audit cycle</li>
  <li><strong>Critical findings</strong> — confirmed SUP that the distributor failed to report; falsified records; willful violation</li>
  <li><strong>Customer complaints</strong> — substantiated complaints that point to systemic quality failures</li>
  <li><strong>FAA referral</strong> — FAA investigation referred to the accreditation organization with findings</li>
</ul>

<h4>The consequences of losing accreditation</h4>
<ol>
  <li><strong>Immediate loss of market access.</strong> Customers requiring accredited suppliers cannot continue buying. Most major operators and MROs have this requirement.</li>
  <li><strong>Listing in the accreditation organization's public records.</strong> Loss of accreditation is publicly visible — and visible to competitors.</li>
  <li><strong>Customer notification obligation.</strong> Recent customers may need to be notified, potentially triggering quality investigations on parts they already received.</li>
  <li><strong>FAA attention.</strong> Loss of accreditation may prompt FAA surveillance audits of the distributor's prior shipments.</li>
  <li><strong>Insurance and contracts.</strong> Insurance policies and customer contracts may include accreditation as a condition.</li>
</ol>

<h4>The reinstatement process</h4>
<p>Losing accreditation is not permanent, but recovery is hard:</p>
<ul>
  <li>Identify and address the root causes of the loss</li>
  <li>Demonstrate sustained compliance over a defined period</li>
  <li>Re-apply for accreditation</li>
  <li>Pass a fresh on-site audit (which will scrutinize the corrective actions taken)</li>
  <li>Possibly accept conditional accreditation with enhanced surveillance for a period</li>
</ul>
<p>During the reinstatement period (which can take months or years), the distributor operates without accreditation — losing the customer base built on accreditation in the first place. The financial consequences typically dwarf the cost of maintaining accreditation in the first place.</p>

<h4>The takeaway for every employee</h4>
<p>Maintaining accreditation is not a QA Manager problem. Every employee's daily work is what an audit measures. A receiving inspector who skips a documentation check, a warehouse worker who moves a part out of quarantine, a sales person who promises a part the QA Manager hasn't released — each of these is a potential audit finding. Each finding moves the company closer to loss of accreditation.</p>
<p>The training program (TurbineWorks University) exists precisely so that every employee understands their role in maintaining accreditation. The audit ultimately measures whether the training is being executed in daily work.</p>
HTML
        ],
        [
            'name'  => 'Lesson 6.5 — Module 6 Summary and Key References',
            'intro' => '<p>AC 00-56 deep dive recap.</p>',
            'content' => <<<'HTML'
<h3>Module 6 Summary</h3>

<h4>What you should now be able to answer</h4>
<ol>
  <li>Why did the FAA create AC 00-56 instead of regulating distributors directly?</li>
  <li>What does AC 00-56 require of an accreditation organization?</li>
  <li>How do requirements flow from AC 00-56 → ASA-100 → TurbineWorks QAM → daily procedures?</li>
  <li>How does ASA-100 compare to AS9120 — when would TurbineWorks want both?</li>
  <li>What are the consequences of losing accreditation?</li>
</ol>

<h4>Key Terms</h4>
<dl>
  <dt><strong>Accreditation Organization (AO)</strong></dt><dd>FAA-recognized organization that audits distributors against a published standard.</dd>
  <dt><strong>AS9120</strong></dt><dd>Aerospace QMS standard for distributors; alternative or complement to ASA-100.</dd>
  <dt><strong>IAQG</strong></dt><dd>International Aerospace Quality Group; publisher of the AS9100 series.</dd>
  <dt><strong>Advisory Circular</strong></dt><dd>FAA guidance document; not regulatory but authoritative.</dd>
</dl>

<h4>References</h4>
<ul>
  <li><a href="https://www.faa.gov/regulations_policies/advisory_circulars/index.cfm/go/document.information/documentID/74250" target="_blank" rel="noopener">FAA AC 00-56B (full text)</a></li>
  <li>SAE AS9120 — Aerospace QMS Distributor Standard</li>
  <li>ASA-100 Standard (current revision via ASA)</li>
</ul>
HTML
        ],
    ];
}

// ============================================================================
// MODULE 7 — ESD Handling (ANSI/ESD S20.20)
// ============================================================================
function local_twu_module_7_lessons(): array {
    return [
        [
            'name'  => 'Lesson 7.1 — ESD Physics: What Static Discharge Actually Does',
            'intro' => '<p>Why a spark you cannot feel can destroy electronics you cannot see being damaged.</p>',
            'content' => <<<'HTML'
<h3>ESD Physics: The Invisible Threat</h3>
<p>You walk across a carpet, touch a doorknob, and get a small shock. That spark you felt was around 3,000 to 5,000 volts. The human body can detect electrostatic discharge starting at around 3,000 V. You can <em>hear</em> a discharge at around 5,000 V and <em>see</em> one at around 8,000 V.</p>
<p>A modern aviation electronic component can be permanently damaged by a discharge as low as <strong>100 volts</strong>. You cannot feel, hear, or see it happen. The component continues to look identical. It may even continue to function — until it fails in service.</p>

<h4>How static electricity builds</h4>
<p>Static charge builds when two surfaces are brought into contact and separated (triboelectric charging). Different materials gain or lose electrons in the process. Walking across a synthetic carpet, sliding out of a chair, removing a part from plastic packaging — all of these generate static.</p>
<p>Once charged, a person or object holds the charge until it has a path to discharge — typically by touching something at a different potential.</p>

<h4>ESD damage mechanisms</h4>
<p>An ESD event damages electronic components in three ways:</p>
<ul>
  <li><strong>Catastrophic failure</strong> — junction melting, oxide breakdown, metal trace vaporization. Component dies immediately. (Easiest to detect — it doesn't work.)</li>
  <li><strong>Latent damage</strong> — partial damage that the component can survive for hours or weeks but that eventually causes failure. (Hardest to detect — works at receiving inspection, fails six months later in an aircraft.)</li>
  <li><strong>Parametric drift</strong> — damage that shifts the component's operating characteristics outside specification without outright failure. May or may not cause aircraft-level problems.</li>
</ul>
<p>Latent damage is the killer. A turbine engine control unit fried by an ungrounded technician at TurbineWorks may pass all functional tests, ship to the customer, install on an engine, and fail two months later at altitude.</p>

<h4>ESD sensitivity classifications</h4>
<p>Electronic parts are classified by their susceptibility threshold. Common scales:</p>
<ul>
  <li>Class 0: damaged below 250 V (most sensitive — modern small-geometry semiconductors)</li>
  <li>Class 1A: 250-500 V</li>
  <li>Class 1B: 500-1,000 V</li>
  <li>Class 1C: 1,000-2,000 V</li>
  <li>Class 2: 2,000-4,000 V</li>
  <li>Class 3A: 4,000-8,000 V</li>
  <li>Class 3B: above 8,000 V (least sensitive)</li>
</ul>
<p>Modern aviation electronics are often Class 0 or Class 1. A discharge you cannot feel can destroy them.</p>

<h4>Why aviation has an extra problem</h4>
<p>Aviation electronics are often:</p>
<ul>
  <li>Small geometry (high sensitivity)</li>
  <li>Mission-critical (failure consequences are serious)</li>
  <li>Stored and handled for extended periods in a distributor's warehouse</li>
  <li>Exposed to many handling events between manufacture and installation</li>
</ul>
<p>Each handling event without ESD protection is an opportunity for damage. The cumulative risk through a distributor's chain is significant.</p>

<h4>What this means for TurbineWorks</h4>
<p>Any electronic part handled at TurbineWorks needs ESD-safe handling from the moment its packaging is opened until it is repackaged. The cost of an ESD program is small; the cost of a single latent-damage failure on an aircraft is enormous.</p>
HTML
        ],
        [
            'name'  => 'Lesson 7.2 — ANSI/ESD S20.20 Program Elements',
            'intro' => '<p>The industry-standard ESD control program TurbineWorks operates under.</p>',
            'content' => <<<'HTML'
<h3>ANSI/ESD S20.20 Program Elements</h3>
<p>The standard for an ESD control program in industry is <strong>ANSI/ESD S20.20</strong>, published by the Electrostatic Discharge Association. The ASA ESD Best Practices document references S20.20 as the technical standard. TurbineWorks' ESD program is structured to conform to S20.20.</p>

<h4>Program scope</h4>
<p>S20.20 covers protection of ESD-sensitive items from:</p>
<ul>
  <li>Charged person contact (direct discharge from a person to a part)</li>
  <li>Charged object contact (discharge from a charged tool, fixture, or surface)</li>
  <li>Field-induced charging (a part picks up charge from a nearby charged object)</li>
</ul>
<p>The program threshold is typically 100 V — anything below this is considered safe for most ESD-sensitive items.</p>

<h4>Required program elements</h4>
<ol>
  <li><strong>ESD Control Program Plan</strong> — the written document describing how the company implements the program. This is the equivalent of a QAM for ESD.</li>
  <li><strong>Training</strong> — ESD training for everyone who handles ESDS items. Initial training and periodic refresher.</li>
  <li><strong>ESD Protected Areas (EPAs)</strong> — designated areas where ESDS items can be handled. Outside the EPA, items must remain in ESD-safe packaging.</li>
  <li><strong>Personnel grounding</strong> — wrist straps for seated work, foot grounders or ESD-safe footwear for standing work.</li>
  <li><strong>Surface grounding</strong> — work surfaces, floors, and equipment in the EPA are grounded.</li>
  <li><strong>Ionization</strong> — for areas where ungrounded objects (some insulators) cannot be eliminated, ionizers neutralize their charge.</li>
  <li><strong>Packaging</strong> — ESD-safe packaging required outside the EPA.</li>
  <li><strong>Marking</strong> — ESDS items and EPAs marked clearly.</li>
  <li><strong>Compliance verification</strong> — regular testing of wrist straps, floors, work surfaces, ionizers.</li>
</ol>

<h4>The ESD Protected Area (EPA)</h4>
<p>The EPA is the protected zone where ESDS items can be handled. Key features:</p>
<ul>
  <li>Boundary clearly marked (floor tape, signage)</li>
  <li>ESD-safe flooring (conductive or dissipative)</li>
  <li>Grounded work surfaces (ESD mats connected to common ground point)</li>
  <li>Wrist strap stations at every seated position</li>
  <li>Foot grounders or ESD shoes required if standing work</li>
  <li>Common ground point connected to building ground via tested low-impedance path</li>
  <li>No insulators that cannot be controlled (no styrofoam, no plastic bags except ESD-safe, no synthetic carpet)</li>
  <li>Humidity controlled (low humidity dramatically increases static charging — typical EPA target 40-60% RH)</li>
</ul>

<h4>Verification testing</h4>
<p>S20.20 requires routine verification:</p>
<ul>
  <li>Wrist straps tested daily (continuous-monitor systems automatic; manual testers require recorded checks)</li>
  <li>Foot grounders / shoes tested daily by personnel using a verification station</li>
  <li>Work surface and floor grounding tested at defined intervals</li>
  <li>Ionizer balance and decay times tested</li>
</ul>
<p>Verification records are part of the quality records and are reviewed during audits.</p>

<h4>Marking</h4>
<p>ESDS items: yellow/black ESD susceptibility symbol on packaging. EPAs: boundary signage and floor marking. Tools and equipment in the EPA: identified as ESD-safe.</p>

<h4>Common findings at audits</h4>
<ul>
  <li>Wrist straps in use but not tested for the day</li>
  <li>ESDS items handled outside the marked EPA</li>
  <li>Unmarked ESDS items in storage (operator can't tell which need protection)</li>
  <li>Floor grounding broken (carpet patch, vinyl tile installed without conductivity)</li>
  <li>ESD-safe packaging reused beyond its useful life (loses conductivity over time)</li>
</ul>

<p><em>[TurbineWorks Procedure Reference: insert TurbineWorks ESD Control Program Plan and EPA layout here.]</em></p>
HTML
        ],
        [
            'name'  => 'Lesson 7.3 — Identifying ESD-Sensitive Aviation Parts',
            'intro' => '<p>Which parts at TurbineWorks need ESD-safe handling.</p>',
            'content' => <<<'HTML'
<h3>Identifying ESD-Sensitive Aviation Parts</h3>
<p>Not every aviation part is ESD-sensitive. The category is electronic and electromagnetic components. Knowing which parts need ESD protection prevents over-protecting (which is just extra work) and under-protecting (which damages parts).</p>

<h4>Clearly ESD-sensitive categories</h4>
<ul>
  <li>FADEC (Full-Authority Digital Engine Control) units</li>
  <li>EEC (Electronic Engine Control) units</li>
  <li>Engine sensors with electronic outputs (EGT thermocouples to the engine control, vibration sensors, position sensors)</li>
  <li>Igniter electronic exciters</li>
  <li>Fuel control electronic interfaces</li>
  <li>Engine condition monitoring units</li>
  <li>Any printed circuit board, populated or bare</li>
  <li>Any discrete semiconductor (ICs, transistors, MOSFETs, diodes in some packaging)</li>
  <li>Connectors with integrated electronics</li>
  <li>Memory devices, programmable logic devices</li>
</ul>

<h4>Categories that may surprise you</h4>
<ul>
  <li><strong>Some bearings</strong> — bearings with magnetic-impulse instrumentation built in</li>
  <li><strong>Some valves</strong> — solenoid valves with associated control electronics in the same housing</li>
  <li><strong>Some hydraulic units</strong> — with integrated electronic position feedback</li>
  <li><strong>Some "mechanical" parts</strong> with embedded RFID tags or electronic ID</li>
</ul>
<p>If a part has any electronic content, default to ESD-safe handling unless documentation says otherwise.</p>

<h4>How to know</h4>
<ul>
  <li>Manufacturer's documentation should specify ESD class</li>
  <li>OEM packaging will have ESD marking if ESD-sensitive (yellow/black triangle with hand symbol)</li>
  <li>Packing list or 8130-3 may note ESD requirements</li>
  <li>When in doubt, treat as ESD-sensitive (no harm done; failure to protect is the harm)</li>
</ul>

<h4>Categories typically NOT ESD-sensitive</h4>
<ul>
  <li>Purely mechanical parts (gears, shafts, blades, disks, springs, fasteners)</li>
  <li>Hydraulic and pneumatic components without electronic content</li>
  <li>Structural components</li>
  <li>Fluid lines, hoses, seals, gaskets</li>
  <li>Tooling and ground support equipment</li>
</ul>

<h4>Marking at receiving</h4>
<p>When an ESD-sensitive part is received, the TurbineWorks tag should note "ESDS" so the warehouse and shipping personnel know it requires ESD-safe handling for all subsequent operations.</p>

<h4>What can go wrong if you don't know</h4>
<p>An ungrounded technician opens packaging on what looks like a sensor (and may be — but with an integrated electronic interface). Handles it for a few minutes at a non-ESD-safe bench. Repackages and re-stocks. The part shows no visible damage. Ships to a customer two months later. Installs on an engine. Fails six months after that, at altitude, causing an in-flight engine control issue.</p>
<p>This sequence has happened in industry. The corrective-action investigation may or may not identify TurbineWorks as the source of the damage. Even if it does not, the failure is on an aircraft TurbineWorks supplied.</p>
HTML
        ],
        [
            'name'  => 'Lesson 7.4 — Personnel Grounding: Wrist Straps and Foot Grounders',
            'intro' => '<p>How a person becomes ESD-safe.</p>',
            'content' => <<<'HTML'
<h3>Personnel Grounding</h3>
<p>The single biggest ESD risk is the person handling the part. People accumulate static charge constantly — every step on a non-conductive floor, every motion in clothing. The role of personnel grounding is to drain that charge continuously to ground, so the person and the part are at the same potential when they make contact.</p>

<h4>Wrist straps (seated work)</h4>
<p>A wrist strap is a conductive band worn on the wrist, connected via a coiled cord to a grounded point (ESD work surface or dedicated ground point). The wrist strap has a built-in 1 megohm resistor — high enough to limit current through the wearer in case of accidental contact with energized equipment, low enough to drain static charge.</p>
<p>Wrist strap requirements:</p>
<ul>
  <li>Worn in continuous skin contact (not over clothing)</li>
  <li>Connected to the EPA common ground point</li>
  <li>Tested daily before use (continuity check)</li>
  <li>Replaced when damaged or when daily test fails</li>
  <li>Coil cord checked for damage</li>
</ul>

<h4>Daily testing</h4>
<p>The wrist strap is tested at the start of each shift using a wrist-strap tester. The test verifies the entire path: wearer's skin → strap → coil cord → tester ground. A pass result is recorded. A fail means the strap or cord is replaced before any ESDS handling.</p>
<p>Continuous-monitor systems replace daily testing with real-time monitoring — the system alerts immediately if the wrist strap loses ground continuity during work.</p>

<h4>Foot grounders (standing work)</h4>
<p>When standing work prevents wrist-strap use, foot grounders provide an alternative. A foot grounder is a heel-strap or shoe cover that conducts charge through the wearer's shoe to ESD-safe flooring.</p>
<p>Foot grounder requirements:</p>
<ul>
  <li>Both feet have foot grounders (a single grounder is not adequate)</li>
  <li>Foot grounder makes contact between shoe sole and conductor inside shoe touching the foot</li>
  <li>Tested at start of shift using a personnel grounding tester</li>
  <li>Worn over an ESD-safe floor (ineffective on insulating flooring)</li>
</ul>

<h4>ESD-safe footwear</h4>
<p>Alternative to foot grounders: shoes constructed with conductive or dissipative soles. Less hassle than foot grounders, but more expensive and requires verification that the shoes still meet spec (they wear out).</p>

<h4>Clothing</h4>
<p>Standard clothing can be a static problem. Synthetic fabrics (polyester, nylon) generate substantial charge from body movement. ESD-safe smocks or lab coats made of conductive-thread material are worn over street clothing in the EPA.</p>
<p>The smock isolates the EPA from the clothing's static. The smock itself is connected to the wearer's grounding (via wrist strap snap or skin contact) so it remains at ground potential.</p>

<h4>What employees should not do</h4>
<ul>
  <li>Wear a wrist strap and then take it off during work to do something else briefly (the unstrapped period is unprotected)</li>
  <li>Wear a foot grounder under only one foot</li>
  <li>Adjust the wrist-strap band to not actually contact skin (defeats the ground path)</li>
  <li>Skip the daily test because "it worked yesterday"</li>
  <li>Wear synthetic clothing without an ESD-safe smock in the EPA</li>
</ul>

<h4>What an auditor checks</h4>
<ul>
  <li>Wrist strap test log — current?</li>
  <li>Personnel in the EPA visibly grounded?</li>
  <li>Foot grounder/shoe verification log?</li>
  <li>ESD smocks in use?</li>
  <li>Spot-test a wrist strap in real time</li>
</ul>
HTML
        ],
        [
            'name'  => 'Lesson 7.5 — Workstation Grounding, Ionizers, and ESD-Safe Packaging',
            'intro' => '<p>The environment around the part: surfaces, air, and packaging.</p>',
            'content' => <<<'HTML'
<h3>Workstation Grounding and Packaging</h3>

<h4>ESD work surfaces</h4>
<p>The work surface where ESD-sensitive parts are placed must be dissipative — capable of draining charge to ground at a controlled rate (typical surface resistance 10^6 to 10^9 ohms; not so conductive that a charged part discharges quickly into the mat, not so insulating that the mat itself holds charge).</p>
<p>The work surface is connected via a grounding wire to the EPA common ground point. The same wrist strap snap socket is typically on the mat.</p>
<p>Verification: surface resistance tested at intervals (typically annually, more often if heavy use). Records retained.</p>

<h4>Flooring</h4>
<p>EPA flooring is conductive or dissipative — typically conductive vinyl, conductive tile, ESD epoxy coating, or interlocking ESD floor mats. Standard carpet, ceramic tile, and vinyl composition tile are all insulators and do not qualify.</p>
<p>The flooring is connected to building ground via tested low-impedance path. Floor resistance is measured at installation and at intervals to verify continued performance.</p>

<h4>Ionizers</h4>
<p>Some objects in an EPA cannot be made conductive — chip packaging, tool handles, some plastics. These objects can carry charge that field-couples into ESDS parts even without contact. Ionizers solve this by injecting ions into the air, which neutralize charge on nearby objects.</p>
<p>Ionizer requirements:</p>
<ul>
  <li>Positioned so the ionized air reaches the work area</li>
  <li>Balanced — equal positive and negative ions, so it doesn't itself charge objects to a non-zero potential</li>
  <li>Balance and decay time tested at intervals</li>
  <li>Maintained (filters cleaned, emitter points cleaned)</li>
</ul>

<h4>ESD-safe packaging</h4>
<p>Outside the EPA, ESDS items must be in ESD-safe packaging. Common packaging types:</p>
<ul>
  <li><strong>Pink poly</strong> — pink antistatic polyethylene. Dissipative. Prevents charge buildup from triboelectric effects but does not shield from external fields. Used for low-sensitivity items.</li>
  <li><strong>Metallized (silver) shielded bags</strong> — multi-layer with metallized layer. Provides Faraday cage shielding against external fields. Standard for moderate-sensitivity items.</li>
  <li><strong>Conductive (black) bags</strong> — high-conductivity polymer. Best protection for highly sensitive items.</li>
  <li><strong>Anti-static foam</strong> — pink or black foam with controlled conductivity. Used for cushioning inside packaging.</li>
  <li><strong>ESD-safe corrugated boxes</strong> — for outer packaging, often labeled.</li>
</ul>
<p>Regular cardboard, regular plastic bubble wrap, regular foam — all of these are insulators and can damage ESDS parts. Visible difference is the labeling: ESD-safe packaging is marked with the ESD symbol.</p>

<h4>Packaging integrity</h4>
<p>ESD-safe bags are not single-use indefinitely. Repeated handling abrades the antistatic coating; the bag loses its dissipative property. Visibly damaged bags are replaced. Bags older than a defined age (per manufacturer spec) are replaced.</p>

<h4>Humidity</h4>
<p>Humidity dramatically affects ESD. At 10% relative humidity, walking across a carpet can generate 35,000 V. At 65% RH, the same walk generates around 1,500 V. Most EPAs target 40-60% RH.</p>
<p>Humidity is monitored and recorded. If the EPA drops below the minimum (typically 30% RH), enhanced controls are needed: more frequent grounding verification, possibly suspending ESDS handling.</p>

<h4>Putting it together</h4>
<p>A correctly-configured EPA allows ESDS handling without damage:</p>
<ul>
  <li>Operator grounded (wrist strap or foot grounders + ESD floor)</li>
  <li>Work surface grounded</li>
  <li>Tools dissipative</li>
  <li>Ionization for residual insulators</li>
  <li>Humidity controlled</li>
  <li>ESDS item in ESD-safe packaging until in the EPA</li>
  <li>ESDS item re-bagged in ESD-safe packaging before leaving the EPA</li>
</ul>
<p>Break any of these links and the protection breaks.</p>
HTML
        ],
        [
            'name'  => 'Lesson 7.6 — Module 7 Summary and Key References',
            'intro' => '<p>ESD handling recap.</p>',
            'content' => <<<'HTML'
<h3>Module 7 Summary</h3>

<h4>What you should now be able to answer</h4>
<ol>
  <li>Why is ESD damage often undetectable at the time it occurs?</li>
  <li>What are the major elements of an ANSI/ESD S20.20 compliant program?</li>
  <li>What types of aviation parts at TurbineWorks are ESD-sensitive?</li>
  <li>How do personnel ground themselves for ESDS handling?</li>
  <li>What is the difference between pink antistatic, silver shielded, and conductive ESD packaging?</li>
</ol>

<h4>Key Terms</h4>
<dl>
  <dt><strong>ESD</strong></dt><dd>Electrostatic Discharge.</dd>
  <dt><strong>ESDS</strong></dt><dd>ESD-Sensitive item — a part that can be damaged by ESD.</dd>
  <dt><strong>EPA</strong></dt><dd>ESD Protected Area — designated zone where ESDS items can be handled safely.</dd>
  <dt><strong>Latent damage</strong></dt><dd>ESD damage that does not cause immediate failure but causes later in-service failure.</dd>
  <dt><strong>Wrist strap</strong></dt><dd>Personnel grounding device worn in skin contact, connected to ground via 1 megohm resistor.</dd>
</dl>

<h4>References</h4>
<ul>
  <li>ANSI/ESD S20.20 — ESD Control Program Standard (via <a href="https://www.esda.org/" target="_blank" rel="noopener">esda.org</a>)</li>
  <li>ASA ESD Best Practices document</li>
  <li>IPC J-STD-033 — Moisture-Sensitive Device Handling</li>
  <li>MIL-PRF-87893 — military ESD packaging spec</li>
</ul>
HTML
        ],
    ];
}

// ============================================================================
// MODULE 8 — Hazmat Identification (49 CFR / IATA DGR)
// ============================================================================
function local_twu_module_8_lessons(): array {
    return [
        [
            'name'  => 'Lesson 8.1 — The 9 DOT Hazard Classes',
            'intro' => '<p>The classification framework that determines all subsequent hazmat handling.</p>',
            'content' => <<<'HTML'
<h3>The 9 DOT Hazard Classes</h3>
<p>Every hazardous material is classified into one of 9 DOT hazard classes (with subdivisions). The classification drives every aspect of subsequent handling: packaging, labeling, marking, shipping documentation, mode of transport restrictions, and incident response.</p>

<h4>Class 1 — Explosives</h4>
<p>Materials with mass explosion or projection hazard. Subdivided 1.1 through 1.6 by sensitivity and hazard type.</p>
<p>Aviation examples: aircraft fire extinguisher bottles with explosive cartridges, ejection seat charges, escape slide inflators with pyrotechnic actuators, flares, certain fuses.</p>

<h4>Class 2 — Gases</h4>
<p>2.1 Flammable gas, 2.2 Non-flammable non-toxic gas, 2.3 Toxic gas.</p>
<p>Aviation examples: oxygen cylinders (2.2 oxidizer subsidiary), nitrogen accumulators (2.2), fire-extinguishing agents (2.2 or 2.3 depending on agent), fuel-system test gas.</p>

<h4>Class 3 — Flammable Liquids</h4>
<p>Liquids with flash point below 60 °C (140 °F).</p>
<p>Aviation examples: fuel system test fluids, hydraulic fluids in some categories, solvents, paints, adhesives.</p>

<h4>Class 4 — Flammable Solids; Spontaneously Combustible; Dangerous When Wet</h4>
<p>4.1 Flammable solid, 4.2 Spontaneously combustible, 4.3 Dangerous when wet.</p>
<p>Aviation examples: certain magnesium parts (4.1), certain pyrotechnic compositions (4.1), lithium metal batteries (4.3).</p>

<h4>Class 5 — Oxidizers and Organic Peroxides</h4>
<p>5.1 Oxidizers, 5.2 Organic peroxides.</p>
<p>Aviation examples: chemical oxygen generators (5.1), some hydraulic fluids (5.1 in concentrated form).</p>

<h4>Class 6 — Toxic and Infectious Substances</h4>
<p>6.1 Toxic, 6.2 Infectious.</p>
<p>Aviation examples: certain cleaning agents, fuel additives, paint solvents.</p>

<h4>Class 7 — Radioactive Material</h4>
<p>Special handling category.</p>
<p>Aviation examples: thoriated lighting elements (some older instruments), certain calibration sources, some smoke detectors.</p>

<h4>Class 8 — Corrosive Substances</h4>
<p>Materials that destroy living tissue or corrode metals.</p>
<p>Aviation examples: aircraft battery acid (lead-acid), some cleaning compounds, etchants.</p>

<h4>Class 9 — Miscellaneous Dangerous Goods</h4>
<p>Materials that present hazards but don't fit Classes 1-8. Includes elevated-temperature materials, marine pollutants, and — critically for aviation — <strong>lithium batteries</strong>.</p>
<p>Aviation examples: lithium-ion batteries (UN3480 standalone, UN3481 with equipment), lithium metal batteries (UN3090, UN3091), magnetized materials (large rotors, certain motors).</p>

<h4>Why classification matters</h4>
<p>The same physical substance may be hazmat or not depending on quantity, concentration, packaging, or transport mode. Lithium-ion batteries are heavily regulated for air transport but less so for ground. A 5 ml bottle of paint thinner may be below regulatory threshold; a 5 liter container is not.</p>
<p>Mis-classification (saying a Class 9 lithium battery is "not hazmat") is one of the most common — and most dangerous — hazmat errors. Lithium battery fires have caused aircraft losses (UPS Flight 6, Asiana 991).</p>

<h4>How TurbineWorks identifies hazmat</h4>
<p>Every part received is evaluated for hazmat content. Sources of information:</p>
<ul>
  <li>Supplier shipping documentation (a properly shipped hazmat item arrives with hazmat documentation)</li>
  <li>SDS (Safety Data Sheet) for chemical content</li>
  <li>Part type knowledge (oxygen cylinders are always hazmat)</li>
  <li>OEM documentation</li>
</ul>
<p>If hazmat status is unclear, the QA Manager makes the determination. Default to "treat as hazmat" pending clarification — over-handling is inconvenient but safe; under-handling is dangerous.</p>
HTML
        ],
        [
            'name'  => 'Lesson 8.2 — Hidden Hazmat in Aviation Parts',
            'intro' => '<p>The parts that surprise people: hazmat where you would not expect it.</p>',
            'content' => <<<'HTML'
<h3>Hidden Hazmat in Aviation Parts</h3>
<p>Some hazmat is obvious: oxygen bottles, fire extinguishers, battery acid. Other hazmat is concealed in parts that look benign. Hidden hazmat is one of the largest sources of hazmat violations in the aviation industry.</p>

<h4>Lithium batteries</h4>
<p>The biggest hidden-hazmat category. Lithium batteries are in:</p>
<ul>
  <li>Emergency Locator Transmitters (ELTs)</li>
  <li>Underwater Locator Beacons (ULBs / pingers)</li>
  <li>Cabin emergency lighting battery packs</li>
  <li>Some flight data recorders and cockpit voice recorders (battery backup)</li>
  <li>Engine control unit backup batteries (some FADECs)</li>
  <li>Wireless sensor systems</li>
  <li>Avionics with non-volatile memory backup</li>
  <li>Some IPADs and tablets used in cockpit (in maintenance shipments)</li>
</ul>
<p>Any of these arriving at TurbineWorks must be identified and shipped as Class 9 lithium battery (UN3090, UN3091, UN3480, or UN3481 depending on type and installation state). The regulatory threshold and packing requirements have tightened repeatedly since 2010 in response to in-flight fire incidents.</p>

<h4>Oxygen sources</h4>
<ul>
  <li>Crew oxygen bottles</li>
  <li>Passenger oxygen masks with chemical oxygen generators (Class 5.1)</li>
  <li>Portable oxygen units</li>
  <li>Emergency descent oxygen kits</li>
</ul>
<p>Chemical oxygen generators (Class 5.1 oxidizer) caused the 1996 ValuJet 592 crash and remain heavily regulated. A used (activated) generator is different hazmat than a new (unactivated) one.</p>

<h4>Inflatables with pressurized gas</h4>
<ul>
  <li>Escape slides (with inflation cylinders)</li>
  <li>Life vests with CO2 cartridges</li>
  <li>Life rafts</li>
  <li>Crash position indicator markers</li>
</ul>
<p>The inflation cylinder is Class 2.2 (non-flammable gas). The actuator may include explosive squibs (Class 1.4).</p>

<h4>Fire protection</h4>
<ul>
  <li>Cargo bay fire bottles (Halon or replacement agent — Class 2.2)</li>
  <li>Engine fire bottles (same)</li>
  <li>APU fire bottles (same)</li>
  <li>Hand-held fire extinguishers</li>
  <li>Fire detector loops with helium pressurization (Class 2.2)</li>
</ul>

<h4>Pyrotechnics</h4>
<ul>
  <li>Ejection seat charges (Class 1)</li>
  <li>Cargo door explosive bolts</li>
  <li>Engine fire suppression squibs</li>
  <li>Smoke flares for emergency use</li>
</ul>

<h4>Magnetized materials</h4>
<p>Aircraft components with strong permanent magnets — generator rotors, certain motors, magnetic chip detectors. Class 9 if the magnetic field at 4.5m exceeds regulatory threshold (0.00525 gauss).</p>

<h4>Elevated-temperature materials</h4>
<p>Class 9. Rare for shipped parts but applies if a part is shipped while hot (typically not relevant for distributor operations).</p>

<h4>Mercury</h4>
<p>Some older instruments contain mercury (mercury switches, mercury barometers). Class 8 corrosive (specific entry).</p>

<h4>Asbestos</h4>
<p>Some legacy brake pads, gaskets, and friction materials contain asbestos. Class 9, with significant additional handling requirements (OSHA, EPA). Modern parts use asbestos-free formulations but legacy parts in distributor inventory may still contain it.</p>

<h4>What this means for receiving inspection</h4>
<p>The receiving inspector cannot identify hazmat purely by visual inspection of a part. The inspector relies on:</p>
<ul>
  <li>Supplier hazmat documentation (when present)</li>
  <li>Part-type knowledge (ELTs always have lithium batteries)</li>
  <li>OEM documentation</li>
  <li>QA Manager review for unclear cases</li>
</ul>
<p>When a part arrives without hazmat documentation but its part-type is known to contain hazmat content, this is a hold condition. The supplier must provide the missing hazmat documentation before the part can be accepted into inventory.</p>
HTML
        ],
        [
            'name'  => 'Lesson 8.3 — UN Identification: Proper Shipping Name, UN Number, Class, Packing Group',
            'intro' => '<p>The international identification system that travels with every hazmat shipment.</p>',
            'content' => <<<'HTML'
<h3>UN Identification System</h3>
<p>Every hazmat shipment is identified using a UN system that crosses all transport modes (road, rail, sea, air) and most jurisdictions. The four key elements are: UN number, Proper Shipping Name, Hazard Class, Packing Group.</p>

<h4>UN Number</h4>
<p>A 4-digit number identifying the specific substance or article. Examples:</p>
<ul>
  <li>UN1057 — Lighters or lighter refills (Class 2.1)</li>
  <li>UN1845 — Carbon dioxide, solid (dry ice; Class 9)</li>
  <li>UN3090 — Lithium metal batteries</li>
  <li>UN3091 — Lithium metal batteries packed with equipment or contained in equipment</li>
  <li>UN3480 — Lithium-ion batteries</li>
  <li>UN3481 — Lithium-ion batteries packed with equipment or contained in equipment</li>
  <li>UN3164 — Articles pressurized pneumatic or hydraulic (e.g., accumulators)</li>
  <li>UN3268 — Safety devices, electrically initiated (e.g., escape slide actuators)</li>
  <li>UN3356 — Oxygen generator, chemical</li>
</ul>
<p>The UN number is the primary identifier. Two substances with similar names but different UN numbers have different regulatory requirements.</p>

<h4>Proper Shipping Name (PSN)</h4>
<p>The exact text description as listed in the regulations. Cannot be paraphrased. "Lithium-ion batteries" is correct; "Li-ion batteries" or "lithium batteries" is not. The PSN appears on the shipping documentation and on the package.</p>
<p>Some PSNs include qualifying information in the entry — e.g., "Lithium-ion batteries (including lithium-ion polymer batteries)."</p>

<h4>Hazard Class</h4>
<p>The DOT class number (1-9) with subdivision. May have subsidiary risks listed if the material has multiple hazards (e.g., a Class 3 flammable liquid that is also Class 8 corrosive would be "3 (8)").</p>

<h4>Packing Group</h4>
<p>Indicates the degree of hazard within the class:</p>
<ul>
  <li>PG I — High danger</li>
  <li>PG II — Medium danger</li>
  <li>PG III — Low danger</li>
</ul>
<p>Not all classes use packing groups. Class 1 (explosives), Class 2 (gases), Class 7 (radioactive) do not. Class 9 lithium batteries do not (regulated by specific provisions rather than PG).</p>

<h4>How to look up</h4>
<p>The authoritative reference is the <strong>49 CFR §172.101 Hazardous Materials Table</strong> for ground/sea/U.S.-domestic, or the <strong>IATA DGR §4.2 List of Dangerous Goods</strong> for air. Both are alphabetical and cross-indexed by UN number.</p>
<p>Online lookups: <a href="https://www.ecfr.gov/current/title-49/subtitle-B/chapter-I/subchapter-C/part-172/subpart-B" target="_blank" rel="noopener">eCFR Title 49 §172.101</a> (free) or IATA DGR subscription (paid, annual).</p>

<h4>Special provisions</h4>
<p>Many UN entries have associated "special provisions" — additional requirements or exceptions. Lithium batteries have extensive special provisions covering quantity limits, packaging, marking, documentation. Reading the PSN entry without checking the special provisions is incomplete.</p>

<h4>Excepted quantities and limited quantities</h4>
<p>Small quantities of some hazmat may ship under "excepted quantity" or "limited quantity" provisions with reduced requirements. Example: small lithium batteries below specific watt-hour or gram-lithium thresholds may ship under §173.185 with simplified packaging and marking.</p>
<p>Excepted-quantity shipments still require correct identification — the exception is from packaging and labeling burden, not from classification.</p>

<h4>Documentation</h4>
<p>Every hazmat shipment requires shipping papers that include:</p>
<ul>
  <li>Proper Shipping Name</li>
  <li>UN Number</li>
  <li>Hazard Class (and subsidiary risk if applicable)</li>
  <li>Packing Group (where applicable)</li>
  <li>Total quantity</li>
  <li>Number and type of packages</li>
  <li>Shipper's name and address</li>
  <li>Emergency response contact</li>
  <li>Shipper's certification statement and signature</li>
</ul>
<p>Air shipments require the <strong>Shipper's Declaration for Dangerous Goods</strong> (covered in Lesson 8.5).</p>
HTML
        ],
        [
            'name'  => 'Lesson 8.4 — IATA Dangerous Goods Regulations (DGR) for Air Shipment',
            'intro' => '<p>The air-transport-specific regulatory regime.</p>',
            'content' => <<<'HTML'
<h3>IATA Dangerous Goods Regulations</h3>
<p>Air shipment of hazmat is governed by ICAO Technical Instructions, implemented commercially through the IATA Dangerous Goods Regulations (DGR). The DGR is published annually — <strong>each edition supersedes the previous</strong>, and the current edition must be available where hazmat air shipments are prepared.</p>

<h4>Why air is stricter</h4>
<p>An in-flight hazmat incident is harder to contain than a ground incident. There is no shoulder to pull over to, no nearby fire station, no immediate evacuation option. Hazmat that is acceptable for ground shipment may be forbidden by air, or may require more rigorous packaging.</p>
<p>Multiple in-flight fires traced to mis-shipped or undeclared hazmat (notably lithium batteries) have driven progressive tightening of air hazmat rules.</p>

<h4>What air bans entirely</h4>
<p>Certain hazmat is forbidden on aircraft regardless of packaging:</p>
<ul>
  <li>Class 1 explosives above certain divisions on passenger aircraft (limited cargo-aircraft-only allowed for some types)</li>
  <li>Class 4.2 spontaneously combustible above certain quantities on passenger aircraft</li>
  <li>Most Class 7 radioactive above certain transport indices</li>
  <li>Lithium-ion and lithium-metal batteries shipped as cargo (UN3480, UN3090) — passenger aircraft prohibited; cargo aircraft only with specific provisions</li>
</ul>

<h4>Passenger vs. cargo aircraft</h4>
<p>The DGR distinguishes hazmat that may travel on passenger aircraft from hazmat that may only travel on cargo-only aircraft. Cargo-only restrictions are marked "CAO" (Cargo Aircraft Only). Documentation must specify the aircraft type allowed.</p>

<h4>State and operator variations</h4>
<p>Individual countries (called "States") and individual airlines (called "operators") may impose variations more restrictive than the baseline IATA DGR. The DGR lists state and operator variations as appendices. A shipment must comply with the variations of every state the shipment passes through and the operator carrying it.</p>
<p>Example: a shipment from the U.S. to Japan via a European hub on a German carrier must comply with U.S. variations, German variations, Japanese variations, AND the German carrier's operator variations. Not all of these are obvious — the DGR is the authoritative reference.</p>

<h4>Packaging</h4>
<p>Hazmat packaging for air must be UN-spec packaging tested and certified for the specific class. The packaging carries a UN marking indicating its test rating:</p>
<ul>
  <li>UN [packaging code] [packing group / hazard rating] [date and country]</li>
  <li>Example: UN 4G/X20/S/24/USA/M1234 — fiberboard box, packing group I, solid, 2024, USA, manufacturer code M1234</li>
</ul>
<p>Using non-UN-spec packaging for air hazmat shipment is a violation regardless of how robust the packaging may seem.</p>

<h4>Marks and labels</h4>
<p>Every package must have:</p>
<ul>
  <li>Proper Shipping Name</li>
  <li>UN Number with "UN" prefix</li>
  <li>Hazard class label (diamond-shaped, color-coded per class)</li>
  <li>Subsidiary risk labels if applicable</li>
  <li>Shipper and consignee names and addresses</li>
  <li>Orientation arrows (for liquid hazmat)</li>
  <li>Lithium battery handling label (for lithium battery shipments)</li>
  <li>Cargo Aircraft Only label if applicable</li>
</ul>

<h4>Lithium battery specifics</h4>
<p>Lithium battery air shipment is the most heavily-changed area of the DGR in recent years. Current requirements (consult current edition for specifics):</p>
<ul>
  <li>State of charge typically limited (often 30% for standalone shipment)</li>
  <li>Limits on package quantity and per-cell/per-pack capacity</li>
  <li>Lithium battery handling label required</li>
  <li>Special packaging tests required</li>
  <li>Some configurations cargo-aircraft-only; others forbidden entirely</li>
</ul>

<h4>Training requirement</h4>
<p>Personnel preparing hazmat air shipments must be trained per the DGR. Initial training plus recurrent training every 24 months minimum. TurbineWorks personnel doing air shipping must complete this beyond the general hazmat training in this module.</p>
HTML
        ],
        [
            'name'  => 'Lesson 8.5 — Shipper&apos;s Declaration for Dangerous Goods',
            'intro' => '<p>Completing the document that the carrier requires.</p>',
            'content' => <<<'HTML'
<h3>Shipper&apos;s Declaration for Dangerous Goods</h3>
<p>Air shipments of hazmat require the <strong>Shipper&apos;s Declaration for Dangerous Goods</strong> (sometimes called "DGD"). This document accompanies the shipment and certifies that the shipper has classified, packaged, marked, labeled, and documented the hazmat in compliance with the applicable regulations.</p>

<h4>Format</h4>
<p>The DGD is a standardized form (IATA-supplied or commercial-vendor equivalent). It is printed on paper with red diagonal hatching on the border indicating dangerous goods documentation.</p>

<h4>Required fields</h4>
<ol>
  <li><strong>Shipper</strong> — TurbineWorks name, address, contact</li>
  <li><strong>Consignee</strong> — Customer name, address</li>
  <li><strong>Air Waybill Number</strong> — links to the carrier's tracking</li>
  <li><strong>Page _ of _ Pages</strong></li>
  <li><strong>Aircraft limitations</strong> — Passenger and Cargo Aircraft, or Cargo Aircraft Only</li>
  <li><strong>Shipment type</strong> — Non-Radioactive or Radioactive</li>
  <li><strong>Airport of Departure</strong></li>
  <li><strong>Airport of Destination</strong></li>
  <li><strong>Nature and Quantity of Dangerous Goods</strong> — for each hazmat:
    <ul>
      <li>UN/ID Number</li>
      <li>Proper Shipping Name (and technical name if required)</li>
      <li>Class or Division (Subsidiary risk in parentheses if any)</li>
      <li>Packing Group</li>
      <li>Quantity and type of packaging</li>
      <li>Packing Instruction</li>
      <li>Authorization (if a special provision applies)</li>
    </ul>
  </li>
  <li><strong>Additional Handling Information</strong></li>
  <li><strong>Emergency contact telephone number</strong> — 24/7 reachable</li>
  <li><strong>Shipper&apos;s Certification</strong> — pre-printed legal statement signed by the shipper</li>
  <li><strong>Name and title of signer</strong></li>
  <li><strong>Place and date</strong></li>
  <li><strong>Signature</strong></li>
</ol>

<h4>The shipper certification statement</h4>
<p>The pre-printed text reads (in summary): "I hereby declare that the contents of this consignment are fully and accurately described above by the proper shipping name, and are classified, packaged, marked and labelled/placarded, and are in all respects in proper condition for transport according to applicable international and national governmental regulations."</p>
<p>Signing this statement creates personal legal liability for the signer if the declaration is inaccurate. Mis-declaration can be criminal (especially for lithium battery undeclared shipments that cause incidents).</p>

<h4>Who can sign</h4>
<p>Only personnel with current IATA DGR training (initial plus 24-month recurrent) may sign DGDs. Training records must be available — auditors and carriers may request.</p>

<h4>Common errors</h4>
<ul>
  <li>Using last year&apos;s DGR edition for the classification (regulations change annually)</li>
  <li>Incorrect Proper Shipping Name (using a common name instead of the exact regulatory text)</li>
  <li>Missing or wrong subsidiary risk class</li>
  <li>Missing emergency contact</li>
  <li>Wrong packing instruction</li>
  <li>Quantity exceeding the limit for the chosen packing instruction</li>
  <li>Marking or labeling on the package not matching the DGD</li>
  <li>Aircraft restriction (CAO) not declared</li>
  <li>Carrier-specific variations not applied</li>
</ul>

<h4>What the carrier checks</h4>
<p>Air carriers have hazmat-trained acceptance personnel who review every DGD before accepting the shipment. They:</p>
<ul>
  <li>Verify the DGD is correctly completed</li>
  <li>Verify the package marking and labeling match the DGD</li>
  <li>Verify the packaging is UN-spec for the hazmat declared</li>
  <li>Refuse acceptance for non-compliance</li>
</ul>
<p>A rejected hazmat shipment costs time, money, and customer relationships. Worse, an accepted-but-incorrect shipment that causes an in-flight incident leads to investigation that may identify the original shipper as the cause.</p>

<h4>Records</h4>
<p>A copy of every DGD signed by TurbineWorks is retained per ASA-100 records retention. Original goes with the shipment.</p>

<p><em>[TurbineWorks Procedure Reference: insert the list of TurbineWorks personnel currently trained and authorized to sign DGDs, with training expiration dates.]</em></p>
HTML
        ],
        [
            'name'  => 'Lesson 8.6 — Module 8 Summary and Key References',
            'intro' => '<p>Hazmat identification recap.</p>',
            'content' => <<<'HTML'
<h3>Module 8 Summary</h3>

<h4>What you should now be able to answer</h4>
<ol>
  <li>What are the 9 DOT hazard classes?</li>
  <li>What is "hidden hazmat" and what are the most common categories at TurbineWorks?</li>
  <li>What is the UN number system and what four elements identify a hazmat shipment?</li>
  <li>Why is air shipment of hazmat more restricted than ground shipment?</li>
  <li>Who can sign a Shipper&apos;s Declaration for Dangerous Goods and what does it certify?</li>
</ol>

<h4>Key Terms</h4>
<dl>
  <dt><strong>UN Number</strong></dt><dd>4-digit international identifier for a specific hazmat substance or article.</dd>
  <dt><strong>Proper Shipping Name (PSN)</strong></dt><dd>Exact regulatory text description of a hazmat.</dd>
  <dt><strong>Packing Group</strong></dt><dd>Degree-of-danger indicator (I/II/III) within a hazard class.</dd>
  <dt><strong>CAO</strong></dt><dd>Cargo Aircraft Only — restriction on certain hazmat for air shipment.</dd>
  <dt><strong>DGR</strong></dt><dd>Dangerous Goods Regulations — IATA&apos;s air-shipment hazmat reference, annual edition.</dd>
  <dt><strong>DGD</strong></dt><dd>Shipper&apos;s Declaration for Dangerous Goods — the hazmat shipping document.</dd>
</dl>

<h4>References</h4>
<ul>
  <li>49 CFR Parts 100-185 — DOT Hazardous Materials Regulations</li>
  <li>49 CFR §172.101 — Hazardous Materials Table</li>
  <li>IATA Dangerous Goods Regulations — current annual edition</li>
  <li>ICAO Technical Instructions for the Safe Transport of Dangerous Goods by Air</li>
  <li>IMDG Code (for sea transport)</li>
</ul>

<h4>Recurring training reminder</h4>
<p>DOT regulations require recurrent hazmat training every <strong>3 years</strong> minimum. IATA DGR requires recurrent training every <strong>24 months</strong> minimum for air-shipment personnel. TurbineWorks University&apos;s 6-month recurring cadence exceeds both — meaning hazmat training is current as long as recurring training is current.</p>
HTML
        ],
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
        [
            'shortname' => 'TWU-ENG-AD-SB',
            'fullname'  => 'Airworthiness Directives, Service Bulletins, and OEM Publications',
            'idnumber'  => 'TWU-ENG-AD-SB',
            'summary'   => '<p>How to read AD/SB/ASB/OSB publications, when each applies, and how compliance affects parts in TurbineWorks inventory.</p>',
            'lessons'   => local_twu_module_ad_sb_lessons(),
        ],
        [
            'shortname' => 'TWU-ENG-BSI',
            'fullname'  => 'Borescope Inspection (BSI) Familiarization',
            'idnumber'  => 'TWU-ENG-BSI',
            'summary'   => '<p>What borescope inspection is, what it can and cannot find, and how BSI reports affect the saleability of engine modules and rotables.</p>',
            'lessons'   => local_twu_module_bsi_lessons(),
        ],
        [
            'shortname' => 'TWU-ENG-TBO',
            'fullname'  => 'TBO, Hard Time, On-Condition, and Engine Time Tracking',
            'idnumber'  => 'TWU-ENG-TBO',
            'summary'   => '<p>The maintenance philosophies that govern engine and engine-component overhaul timing, and how each affects the value and saleability of inventory.</p>',
            'lessons'   => local_twu_module_tbo_lessons(),
        ],
        [
            'shortname' => 'TWU-ENG-CFM56',
            'fullname'  => 'CFM56 Family — Engine Familiarization',
            'idnumber'  => 'TWU-ENG-CFM56',
            'summary'   => '<p>The CFM56 family powers the 737 Classic/NG, A320ceo, KC-135R, and many other airframes. The world\'s most-produced turbofan, with deep aftermarket parts inventory.</p>',
            'lessons'   => local_twu_engine_cfm56_lessons(),
        ],
        [
            'shortname' => 'TWU-ENG-GE90-GENX',
            'fullname'  => 'GE90 / GEnx Family — Engine Familiarization',
            'idnumber'  => 'TWU-ENG-GE90-GENX',
            'summary'   => '<p>GE\'s widebody twins: GE90 (777 Classic), GEnx (787, 747-8), GE9X (777X). High-thrust, high-bypass turbofans with composite fan blades.</p>',
            'lessons'   => local_twu_engine_ge_widebody_lessons(),
        ],
        [
            'shortname' => 'TWU-ENG-PW1100G',
            'fullname'  => 'PW1000G GTF Family — Engine Familiarization',
            'idnumber'  => 'TWU-ENG-PW1100G',
            'summary'   => '<p>Pratt &amp; Whitney\'s geared turbofan family. PW1100G (A320neo), PW1500G (A220), PW1900G (E2). Different LLP cadence and parts mix than direct-drive turbofans.</p>',
            'lessons'   => local_twu_engine_pw_gtf_lessons(),
        ],
        [
            'shortname' => 'TWU-ENG-TRENT',
            'fullname'  => 'Rolls-Royce Trent Family — Engine Familiarization',
            'idnumber'  => 'TWU-ENG-TRENT',
            'summary'   => '<p>Trent 700 (A330), Trent 1000 (787), Trent XWB (A350), Trent 900 (A380). Three-shaft architecture distinct from competitor two-shaft designs — affects modular maintenance.</p>',
            'lessons'   => local_twu_engine_trent_lessons(),
        ],
        [
            'shortname' => 'TWU-OPS-AS9120',
            'fullname'  => 'AS9120 Familiarization (Aerospace QMS for Distributors)',
            'idnumber'  => 'TWU-OPS-AS9120',
            'summary'   => '<p>Overview of the SAE/IAQG aerospace QMS standard for distributors. Many ASA-100 distributors pursue AS9120 within 1-2 years of initial accreditation. ~70% training overlap with ASA-100.</p>',
            'lessons'   => [local_twu_overview_lesson(
                'AS9120 Familiarization',
                'SAE AS9120 (current rev), ISO 9001 (underlying), AS9100 series (related)',
                '1 hour',
                [
                    'Distinguish AS9120 from ASA-100 in scope, structure, and audit approach',
                    'Recognize the ISO 9001 process-based QMS that AS9120 extends with aerospace-specific requirements',
                    'Identify which TurbineWorks customer segments require AS9120 vs ASA-100 vs both',
                    'Map ASA-100 controls already in place to AS9120 clauses — gap analysis for AS9120 readiness',
                    'Understand AS9120 audit cadence and certification body options (BSI, DNV, NQA, etc.)',
                ],
                'AS9120 opens access to the OEM supply chain (Boeing tier-1/2, Airbus, GE Aerospace, Rolls-Royce direct programs). Many TurbineWorks customers will eventually require AS9120 in addition to ASA-100. Pre-positioning the training program to support both is the right long-term decision.'
            )],
        ],
        [
            'shortname' => 'TWU-OPS-CUSTOMER',
            'fullname'  => 'Customer Relations &amp; Quality Communication',
            'idnumber'  => 'TWU-OPS-CUSTOMER',
            'summary'   => '<p>How to communicate quality status, non-conformance, and audit-relevant information to customers (airlines, MROs, OEMs, government).</p>',
            'lessons'   => [local_twu_overview_lesson(
                'Customer Relations &amp; Quality Communication',
                'ASA-100 §10 (Customer Complaints), customer-specific contract terms',
                '1 hour',
                [
                    'Recognize what customers expect from an ASA-100 distributor in routine and exception communications',
                    'Handle customer complaints through the corrective-action system (not just sales channels)',
                    'Disclose part condition accurately on quotes, COCs, and shipping documents',
                    'Notify affected customers when a SUP, AD, or material recall affects shipped parts',
                    'Respond to customer audits (some customers audit their accredited distributors directly)',
                ],
                'A customer-discovered quality issue that TurbineWorks did not disclose is the single fastest way to lose customer trust. The same issue disclosed proactively builds the trust that drives repeat business. Communication is part of quality, not separate from it.'
            )],
        ],
        [
            'shortname' => 'TWU-OPS-INTSHIP',
            'fullname'  => 'International Shipping — ITAR, EAR, and Export Control',
            'idnumber'  => 'TWU-OPS-INTSHIP',
            'summary'   => '<p>U.S. export control regimes affecting aviation parts shipments overseas. Penalties for non-compliance include criminal prosecution.</p>',
            'lessons'   => [local_twu_overview_lesson(
                'International Shipping &amp; Export Control',
                'ITAR (22 CFR 120-130), EAR (15 CFR 730-774), OFAC sanctions lists',
                '1 hour',
                [
                    'Distinguish ITAR-controlled (military / defense article) from EAR-controlled (dual-use commercial) parts',
                    'Recognize when a routine commercial aircraft part may be on the U.S. Munitions List (USML) or Commerce Control List (CCL)',
                    'Screen end-users and end-uses against OFAC sanctioned-party lists before quoting or shipping',
                    'Apply correct ECCN (Export Control Classification Number) and licensing requirements',
                    'Document export determinations and licensing in TurbineWorks records (retained 5+ years per BIS rules)',
                ],
                'A single ITAR violation can result in personal criminal liability for the people involved, plus corporate penalties up to $1M per violation. Aviation parts are a high-scrutiny category — especially anything originally designed for military application that has commercial derivatives (engine parts shared with KC-135R, C-17, P-8, etc.).'
            )],
        ],
        [
            'shortname' => 'TWU-ENG-ATASPEC2000',
            'fullname'  => 'ATA Spec 2000 — Electronic Data Interchange for Parts',
            'idnumber'  => 'TWU-ENG-ATASPEC2000',
            'summary'   => '<p>The aviation industry standard for electronic exchange of parts data: RFQ, ordering, invoicing, and traceability.</p>',
            'lessons'   => [local_twu_overview_lesson(
                'ATA Spec 2000 — Electronic Data Interchange for Parts',
                'ATA Spec 2000 (current edition), ATA iSpec 2200 (tech pubs)',
                '1 hour',
                [
                    'Recognize ATA Spec 2000 chapters relevant to parts distribution (Chapter 9 - Procurement Planning, Chapter 12 - Inventory)',
                    'Use spec-compliant electronic RFQ and ordering with airlines and MROs',
                    'Map TurbineWorks inventory data to Spec 2000 EDI message formats',
                    'Distinguish Spec 2000 (operational data) from iSpec 2200 (technical publications)',
                ],
                'Most major airlines and MROs increasingly require ATA Spec 2000 EDI for parts procurement. A distributor without Spec 2000 capability is locked out of major customer pipelines. This module covers the framework; the operational implementation is in the TurbineWorks IT/ERP system documentation.'
            )],
        ],
    ];
}

// ============================================================================
// ENGINE-MODEL COURSES — CFM56, GE90/GEnx, PW1000G GTF, Rolls-Royce Trent
// ============================================================================

function local_twu_engine_cfm56_lessons(): array {
    return [
        [
            'name'  => 'CFM56 Family Overview',
            'intro' => '<p>The most-produced turbofan in history. Why CFM56 dominates the secondary parts market.</p>',
            'content' => <<<'HTML'
<h3>CFM56 Family</h3>
<p>The CFM56 is a family of high-bypass turbofan engines produced by CFM International, a 50/50 joint venture between GE Aerospace and Safran Aircraft Engines. Over 33,000 CFM56 engines have been delivered since 1982 — more than any other turbofan in history.</p>

<h4>Variants and applications</h4>
<table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse;">
  <tr style="background:#0d2240; color:#fff;">
    <th>Variant</th><th>Thrust</th><th>Primary Application</th>
  </tr>
  <tr><td>CFM56-2</td><td>22,000-24,000 lbf</td><td>KC-135R, DC-8, E-6</td></tr>
  <tr><td>CFM56-3</td><td>18,500-23,500 lbf</td><td>737 Classic (300/400/500)</td></tr>
  <tr><td>CFM56-5A/5B</td><td>22,000-33,000 lbf</td><td>A320 family (ceo)</td></tr>
  <tr><td>CFM56-5C</td><td>31,200-34,000 lbf</td><td>A340-200/300</td></tr>
  <tr><td>CFM56-7B</td><td>19,500-27,300 lbf</td><td>737NG (600/700/800/900)</td></tr>
</table>

<h4>Why CFM56 matters for TurbineWorks</h4>
<ul>
  <li>Largest installed base of any turbofan — biggest aftermarket parts demand</li>
  <li>Long production run means well-developed shop infrastructure and parts availability</li>
  <li>Many engines reaching mid-life and end-of-life retirement — strong used-parts market</li>
  <li>Mature SB / AD history — most known issues have known solutions</li>
</ul>

<h4>Key CFM56 LLPs</h4>
<p>The high-cycle life-limited parts that drive CFM56 inventory value:</p>
<ul>
  <li>Fan disk</li>
  <li>Booster (LPC) disks (stages 1-3)</li>
  <li>HPC disks (stages 3-9)</li>
  <li>HPT disk</li>
  <li>LPT disks (stages 1-4)</li>
  <li>HPT and LPT shafts</li>
</ul>
<p>Life limits are published in the CFM56 ESM and vary by variant. Current OEM publication controls — limits have been adjusted over time as service data accumulated.</p>

<h4>Common CFM56 ADs to watch</h4>
<ul>
  <li><strong>CFM56-7B fan blade AD (2018)</strong> — issued after Southwest 1380 in-flight fan blade failure. Triggered fleet-wide inspection.</li>
  <li><strong>CFM56-5B HPT shroud cracking</strong> — recurrent inspection AD.</li>
  <li><strong>CFM56-7B oil leak ADs</strong> — multiple over the years.</li>
</ul>
<p>The complete current AD list for the affected serial ranges is searchable in the FAA DRS (Dynamic Regulatory System).</p>

<h4>What every TurbineWorks employee handling CFM56 parts should know</h4>
<ul>
  <li>CFM56 part numbers can be either CFM-prefixed or GE-prefixed depending on the component source — verify carefully</li>
  <li>CFM56 ESM compliance is critical; CFM operates strict ESM revision control</li>
  <li>Some "CFM56" parts are common with the LEAP family (the CFM56 successor) — confirm specific application via IPC</li>
</ul>

<p><em>[TurbineWorks Procedure Reference: insert link to TurbineWorks CFM56-specific receiving inspection notes and supplier qualification list here.]</em></p>
HTML
        ],
        [
            'name'  => 'CFM56 Module Architecture',
            'intro' => '<p>How CFM56 breaks down into modules, and what that means for module-level trading.</p>',
            'content' => <<<'HTML'
<h3>CFM56 Module Architecture</h3>
<p>The CFM56, like most modern turbofans, is built and maintained as a set of replaceable modules. Module-level architecture matters for the parts distribution market because modules can be traded independently of complete engines.</p>

<h4>Standard module breakdown</h4>
<ul>
  <li><strong>Fan / Booster module</strong> — fan disk and blades, booster (LP compressor) section, fan case</li>
  <li><strong>HPC (High-Pressure Compressor) module</strong> — 9-stage axial compressor</li>
  <li><strong>Combustor module</strong> — combustion chamber, fuel nozzles, ignition system</li>
  <li><strong>HPT (High-Pressure Turbine) module</strong> — single-stage HP turbine</li>
  <li><strong>LPT (Low-Pressure Turbine) module</strong> — 4-stage LP turbine, LPT shaft</li>
  <li><strong>AGB (Accessory Gearbox) module</strong> — driven accessories: fuel pump, oil pumps, generator drive, starter</li>
</ul>

<h4>Module trading economics</h4>
<p>An operator removing a CFM56 from an aircraft has options beyond full-engine overhaul:</p>
<ul>
  <li><strong>Module exchange:</strong> the failed module is replaced with a serviceable one from inventory; the original is sent for overhaul</li>
  <li><strong>Component exchange:</strong> if the failure is at component-level (a specific blade set), only the component is changed</li>
  <li><strong>Whole-engine swap:</strong> entire engine replaced with another</li>
</ul>
<p>TurbineWorks participates in the module exchange market — buying and selling individual modules with their own LLP status, time/cycles, and shop visit history.</p>

<h4>Module documentation requirements</h4>
<p>Each module sold separately requires:</p>
<ul>
  <li>Module-level FAA 8130-3 (issued by an authorized organization)</li>
  <li>LLP records for every LLP within the module</li>
  <li>Build standard / SB compliance documentation for the module</li>
  <li>Most recent BSI (where applicable)</li>
  <li>Shop visit history of the module</li>
</ul>

<h4>Cross-module compatibility</h4>
<p>Not every CFM56 module is compatible with every other CFM56 module. Variant differences (CFM56-3 vs -7B, etc.) drive incompatibility. Sub-variant build standards drive further constraints.</p>
<p>The IPC and ESM define compatibility. Selling an HPT module from a CFM56-7B as if it were a CFM56-7B26 when it is actually a -7B27 is a misdeclaration — possibly fraud, depending on intent.</p>
HTML
        ],
        [
            'name'  => 'Module Summary',
            'intro' => '<p>CFM56 familiarization recap.</p>',
            'content' => '<h3>CFM56 Module Summary</h3><h4>Key takeaways</h4><ul><li>CFM56 is the largest installed-base turbofan — biggest aftermarket parts opportunity for TurbineWorks</li><li>Modular architecture (fan/booster, HPC, combustor, HPT, LPT, AGB) enables module-level trading</li><li>LLP set and current life limits per CFM ESM (current revision)</li><li>Cross-variant module compatibility is constrained — verify per IPC before any cross-application</li></ul><h4>References</h4><ul><li>CFM International ESM (CFM56 family, current revisions)</li><li>FAA Type Certificate Data Sheet E26NE (CFM56-7B) and related TCDS for other variants</li><li>FAA DRS — current AD list for affected CFM56 serial numbers</li></ul>',
        ],
    ];
}

function local_twu_engine_ge_widebody_lessons(): array {
    return [
        [
            'name'  => 'GE Widebody Engines — GE90, GEnx, GE9X',
            'intro' => '<p>GE\'s widebody turbofan family — large-thrust, high-bypass engines with composite fan blades.</p>',
            'content' => <<<'HTML'
<h3>GE Widebody Engine Family</h3>

<h4>GE90</h4>
<p>The GE90 family powers the Boeing 777 (Classic 200/300, 200ER/LR, 300ER, Freighter). Variants include:</p>
<ul>
  <li>GE90-77B / -85B / -90B / -94B — 777 Classic variants, 77,000-94,000 lbf thrust</li>
  <li>GE90-110B1 / -115B — 777-200LR / -300ER, the highest-thrust certified turbofan (115,000 lbf)</li>
</ul>
<p>The GE90 introduced composite fan blades to large commercial turbofans — a major industry innovation that GE has carried forward to subsequent designs.</p>

<h4>GEnx</h4>
<p>The GEnx family powers the Boeing 787 (GEnx-1B variants) and 747-8 (GEnx-2B variants). Thrust range 53,000-76,000 lbf. Features:</p>
<ul>
  <li>Composite fan blades (carried forward from GE90)</li>
  <li>Composite fan case (lighter than aluminum)</li>
  <li>TAPS combustor (Twin Annular Pre-mixing Swirler) — improved emissions</li>
  <li>10-stage HPC, 2-stage HPT, 7-stage LPT</li>
</ul>

<h4>GE9X</h4>
<p>Newest GE widebody engine, exclusively for the 777X. Thrust 105,000+ lbf. Larger fan diameter than any prior turbofan. Lower in-service hours but growing — emerging aftermarket as fleet expands.</p>

<h4>Distinguishing features for parts distribution</h4>
<ul>
  <li><strong>Composite fan blades</strong> — much higher unit value than metal blades, but also different failure modes and inspection criteria. Repair authority limited to specifically-trained shops.</li>
  <li><strong>Composite fan case</strong> — distinctive appearance, different inspection</li>
  <li><strong>High-value LLPs</strong> — GE90 / GEnx LLPs are among the highest-value rotables in the aftermarket</li>
</ul>

<h4>Common cross-shopping</h4>
<p>Operators sometimes mix and match GE90 modules across variants (where permitted by certification). The certification basis for cross-mixing is defined in the Type Certificate Data Sheet and OEM service literature.</p>

<h4>Key ADs</h4>
<ul>
  <li><strong>GE90 HPT issues</strong> — multiple ADs over the years addressing HPT durability</li>
  <li><strong>GEnx PRT (Power Recovery Turbine) issues</strong> — early production fixes</li>
  <li><strong>GEnx ice crystal icing</strong> — operational ADs and SBs after in-service icing events</li>
</ul>

<p><em>[TurbineWorks Procedure Reference: insert TurbineWorks GE90 / GEnx supplier qualification status and current parts inventory pointer here.]</em></p>
HTML
        ],
        [
            'name'  => 'Module Summary',
            'intro' => '<p>GE widebody recap.</p>',
            'content' => '<h3>GE Widebody Summary</h3><ul><li>GE90: 777 family, includes the highest-thrust certified turbofan (115K lbf)</li><li>GEnx: 787 and 747-8, composite fan blades + case, TAPS combustor</li><li>GE9X: 777X, newest entry, growing aftermarket</li><li>Composite components require specifically-authorized repair shops</li><li>High-value LLPs — careful back-to-birth verification critical</li></ul>',
        ],
    ];
}

function local_twu_engine_pw_gtf_lessons(): array {
    return [
        [
            'name'  => 'PW1000G Geared Turbofan Family',
            'intro' => '<p>Pratt &amp; Whitney\'s GTF — geared turbofan architecture that differs fundamentally from direct-drive turbofans.</p>',
            'content' => <<<'HTML'
<h3>PW1000G Geared Turbofan (GTF) Family</h3>
<p>The PW1000G family represents a fundamental architectural change in turbofan design: a reduction gearbox between the fan and the LP turbine allows each to spin at its own optimum speed, improving fuel efficiency at the cost of additional mechanical complexity.</p>

<h4>Variants and applications</h4>
<ul>
  <li><strong>PW1100G-JM</strong> — Airbus A320neo family. Thrust 24,000-33,000 lbf.</li>
  <li><strong>PW1500G</strong> — Airbus A220 (formerly Bombardier CSeries). Thrust 19,000-24,000 lbf.</li>
  <li><strong>PW1700G / PW1900G</strong> — Embraer E2 family.</li>
  <li><strong>PW1200G</strong> — Mitsubishi SpaceJet (program suspended).</li>
</ul>

<h4>What "geared turbofan" means</h4>
<p>In a direct-drive turbofan (CFM56, GE90, V2500), the fan is mechanically coupled to the LP turbine on the same shaft and rotates at the same speed. The fan must spin slowly enough to avoid supersonic tip speeds; the LP turbine must spin slowly along with it.</p>
<p>In a GTF, a planetary reduction gearbox sits between the fan and the LP turbine. The LP turbine can spin much faster (more efficient) while the fan spins slowly (also more efficient). Result: ~15% better fuel efficiency than equivalent direct-drive designs.</p>

<h4>Implications for the parts market</h4>
<ul>
  <li><strong>Gearbox is a new wear/maintenance item</strong> not present in direct-drive engines</li>
  <li><strong>Different LLP set</strong> than CFM56 — the slower fan disk has different cyclic loading, the faster LPT disk has different thermal loading</li>
  <li><strong>Newer fleet</strong> — fewer engines retired so far, smaller aftermarket parts pool</li>
  <li><strong>Early-life durability issues</strong> — known issues with combustor liner cracking, bearing #3 wear, oil migration in early variants. Many addressed by SBs and PIPs (Performance Improvement Packages)</li>
</ul>

<h4>Build standard significance</h4>
<p>The PW1100G has undergone multiple iterative improvements since entry into service. "Build standard" matters more for GTF than for mature direct-drive engines — a PW1100G with PIP-3 incorporated is operationally different from one with only PIP-1. Customers care about the build standard at sale.</p>

<h4>Inspection considerations</h4>
<p>Gearbox condition is checked via:</p>
<ul>
  <li>Oil debris monitoring (magnetic plug inspection, oil chip analysis)</li>
  <li>Vibration trending (gearbox failures often manifest as specific vibration spectra)</li>
  <li>Borescope inspection of accessible gearbox interior (limited compared to engine flow path)</li>
</ul>

<p><em>[TurbineWorks Procedure Reference: insert TurbineWorks PW1000G GTF parts inventory status, supplier list, and any GTF-specific receiving inspection notes here.]</em></p>
HTML
        ],
        [
            'name'  => 'Module Summary',
            'intro' => '<p>PW1000G GTF recap.</p>',
            'content' => '<h3>PW1000G GTF Summary</h3><ul><li>Geared architecture: reduction gearbox between fan and LP turbine, ~15% better fuel efficiency than direct-drive</li><li>PW1100G (A320neo), PW1500G (A220), PW1900G (E2) are the main variants</li><li>Gearbox is a new maintenance item not present in legacy engines</li><li>Build standard matters significantly — track PIP incorporation</li><li>Newer fleet means smaller aftermarket pool — opportunity as fleet matures</li></ul>',
        ],
    ];
}

function local_twu_engine_trent_lessons(): array {
    return [
        [
            'name'  => 'Rolls-Royce Trent Family',
            'intro' => '<p>Rolls-Royce three-shaft architecture — distinct from competitor two-shaft designs.</p>',
            'content' => <<<'HTML'
<h3>Rolls-Royce Trent Family</h3>
<p>The Trent is Rolls-Royce\'s widebody turbofan family. Distinctive feature: three concentric shafts (LP, IP, HP) instead of the two-shaft architecture used by CFM, GE, and Pratt &amp; Whitney. This affects module structure, LLP set, and maintenance philosophy.</p>

<h4>Variants and applications</h4>
<table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse;">
  <tr style="background:#0d2240; color:#fff;">
    <th>Variant</th><th>Thrust</th><th>Primary Application</th>
  </tr>
  <tr><td>Trent 700</td><td>67,500-72,000 lbf</td><td>A330ceo</td></tr>
  <tr><td>Trent 800</td><td>74,600-93,400 lbf</td><td>777 (some)</td></tr>
  <tr><td>Trent 900</td><td>70,000-80,000 lbf</td><td>A380</td></tr>
  <tr><td>Trent 1000</td><td>53,000-78,000 lbf</td><td>787</td></tr>
  <tr><td>Trent XWB</td><td>74,200-97,000 lbf</td><td>A350</td></tr>
  <tr><td>Trent 7000</td><td>68,000-72,000 lbf</td><td>A330neo</td></tr>
</table>

<h4>Three-shaft architecture</h4>
<p>A Trent has:</p>
<ul>
  <li><strong>LP shaft</strong> — drives the fan and a 1-stage LP turbine</li>
  <li><strong>IP (intermediate-pressure) shaft</strong> — drives the IPC (intermediate pressure compressor) and a 1-stage IP turbine</li>
  <li><strong>HP shaft</strong> — drives the HPC (high pressure compressor) and a 1-stage HP turbine</li>
</ul>
<p>Each shaft runs at its own speed, optimized independently. Functionally similar to a GTF concept, but achieved with rotating shafts instead of a gearbox.</p>

<h4>What this means for the parts market</h4>
<ul>
  <li><strong>Different module breakdown</strong> than CFM/GE/PW — Trent modules include the IPC and IPT</li>
  <li><strong>Different LLP set</strong> — includes IP disks and IP shaft</li>
  <li><strong>More LLPs per engine</strong> than two-shaft designs (three turbines, three compressor sections instead of two)</li>
  <li><strong>OEM-controlled aftermarket</strong> — Rolls operates a tighter OEM aftermarket than CFM or GE. Independent MROs are fewer.</li>
</ul>

<h4>Trent 1000 specific notes</h4>
<p>The Trent 1000 (787 application) has had well-publicized in-service durability issues — IPC blade cracking, IPT blade fatigue, others. These triggered ADs and SBs. Trent 1000 TEN (the post-issue improved variant) addresses many of the early issues.</p>
<p>Trent 1000 inventory should be tracked carefully by build standard — TEN vs pre-TEN configurations have substantially different value and applicability.</p>

<h4>Trent XWB</h4>
<p>The Trent XWB (A350) has been one of the most reliable Trent variants in service. Strong aftermarket as the A350 fleet matures.</p>

<p><em>[TurbineWorks Procedure Reference: insert Rolls-Royce TotalCare program implications for TurbineWorks aftermarket access here. Many Trent engines are under TotalCare power-by-the-hour contracts that may affect independent parts demand.]</em></p>
HTML
        ],
        [
            'name'  => 'Module Summary',
            'intro' => '<p>Trent family recap.</p>',
            'content' => '<h3>Trent Family Summary</h3><ul><li>Three-shaft architecture (LP / IP / HP) — distinct from competitor two-shaft designs</li><li>Trent 700 (A330), Trent 1000 (787), Trent XWB (A350), Trent 7000 (A330neo) are current applications</li><li>More LLPs per engine than two-shaft designs (3 sets of disks)</li><li>Rolls-Royce operates a tighter OEM aftermarket — independent distributor opportunities are narrower than CFM/GE</li><li>Trent 1000 TEN vs pre-TEN configurations: track build standard carefully</li></ul>',
        ],
    ];
}

// ============================================================================
// ENGINE-PARTS COURSE: Airworthiness Directives, Service Bulletins, OEM Pubs
// ============================================================================
function local_twu_module_ad_sb_lessons(): array {
    return [
        [
            'name'  => 'Lesson 1 — The OEM Publication Family',
            'intro' => '<p>The hierarchy of documents published by engine OEMs (CFM, GE, Pratt &amp; Whitney, Rolls-Royce, Honeywell) and which apply to which parts.</p>',
            'content' => <<<'HTML'
<h3>The OEM Publication Family</h3>
<p>Every engine OEM maintains a family of technical publications that govern how their engines and components are maintained, modified, and operated. As a parts distributor, TurbineWorks does not perform maintenance — but the OEM publications still matter because they determine what condition a part must be in to be saleable.</p>

<h4>The major publication types</h4>
<dl>
  <dt><strong>ESM — Engine Shop Manual</strong></dt>
  <dd>The master document for engine-level overhaul and shop work. Contains assembly drawings, dimensional limits, repair procedures, inspection requirements. Used by repair stations. TurbineWorks references ESM for inspection limits when evaluating a used part.</dd>

  <dt><strong>CMM — Component Maintenance Manual</strong></dt>
  <dd>Component-level equivalent of the ESM. One CMM per major component (fuel control, gearbox, accessory, etc.). Defines what the component is, how it&apos;s tested, and the maintenance allowed.</dd>

  <dt><strong>IPC / IPL — Illustrated Parts Catalog / Illustrated Parts List</strong></dt>
  <dd>The OEM&apos;s authoritative list of every part in the engine or component, with current part numbers, supersedure history, applicability, and exploded views. Foundational reference for parts distribution.</dd>

  <dt><strong>SB — Service Bulletin</strong></dt>
  <dd>OEM-issued instructions describing a modification, inspection, or operational change. Numbered (e.g., CFM SB 72-1234). Categorized by urgency: Mandatory, Recommended, Informational. May be incorporated into the engine via "SB compliance" with documentation.</dd>

  <dt><strong>ASB — Alert Service Bulletin</strong></dt>
  <dd>Higher-priority SB issued to address a safety concern. Often the document that the FAA later codifies as an Airworthiness Directive.</dd>

  <dt><strong>OSB — Optional Service Bulletin</strong></dt>
  <dd>Operator-choice modification. Common for improvements that aren&apos;t safety-critical (longer-life parts, better materials).</dd>

  <dt><strong>ATA 100 / ATA 2200 chapter numbering</strong></dt>
  <dd>The OEM publications follow ATA chapter numbering (Chapter 70 — Engine, Chapter 71 — Powerplant, Chapter 72 — Engine itself, Chapter 73 — Fuel and control, Chapter 74 — Ignition, Chapter 75 — Bleed air, etc.). The chapter tells you what the document covers.</dd>
</dl>

<h4>The TurbineWorks position</h4>
<p>TurbineWorks does not maintain a full library of every OEM publication for every engine. Instead, TurbineWorks:</p>
<ul>
  <li>Subscribes to the IPC/IPL for engines actively traded</li>
  <li>Has access to SB / ASB databases (typically via OEM portal or third-party service)</li>
  <li>Refers customers to the OEM&apos;s authoritative current data for installation guidance</li>
</ul>
<p>The reason: OEM publications change frequently. Maintaining a current local copy of every publication for every engine model is impractical. Instead, TurbineWorks verifies critical data (life limits, applicability, supersedures) against the current OEM source at the time of inventory evaluation and sale.</p>

<h4>Why this matters at receiving</h4>
<p>A received part&apos;s 8130-3 may reference SB compliance status (e.g., "SB 72-1234 incorporated, post-mod configuration"). The receiving inspector verifies that the part&apos;s configuration matches what the SB describes for "post-mod" — the SB itself defines what changed and what the post-mod state looks like.</p>
<p>This requires either having the SB or knowing how to retrieve it. The TurbineWorks Quality Assurance Manager owns the OEM publication subscription policy.</p>
HTML
        ],
        [
            'name'  => 'Lesson 2 — Airworthiness Directives (ADs)',
            'intro' => '<p>The FAA-mandated changes that override OEM optional bulletins and have legal force.</p>',
            'content' => <<<'HTML'
<h3>Airworthiness Directives (ADs)</h3>
<p>An Airworthiness Directive is a legally binding FAA order requiring action on an aircraft or component. Unlike a Service Bulletin (which is OEM guidance), an AD has the force of law under 14 CFR Part 39. Non-compliance with an applicable AD makes the affected aircraft not airworthy.</p>

<h4>Where ADs come from</h4>
<p>An AD is issued when the FAA determines that an unsafe condition exists in an aircraft, engine, or appliance. Common sources of AD initiation:</p>
<ul>
  <li>OEM-issued ASB (Alert Service Bulletin) that the FAA elevates to mandatory status</li>
  <li>NTSB accident/incident investigation findings</li>
  <li>FAA Safety Team or AVS Office findings</li>
  <li>International authority (EASA, TCCA) ADs that the FAA mirrors</li>
  <li>Service-difficulty reports (SDRs) showing a pattern of failures</li>
</ul>

<h4>Anatomy of an AD</h4>
<p>An AD typically contains:</p>
<ul>
  <li><strong>Applicability</strong> — exactly which engine models, serial number ranges, or part numbers are affected</li>
  <li><strong>Unsafe condition</strong> — what failure mode the AD addresses</li>
  <li><strong>Required actions</strong> — what must be done (inspect, replace, modify, retire)</li>
  <li><strong>Compliance time</strong> — when the action must be completed (immediately, within X flight hours, within X calendar months, before next flight, etc.)</li>
  <li><strong>Alternative methods of compliance (AMOC)</strong> — variations the FAA may accept</li>
  <li><strong>Effective date</strong> — when the AD becomes legally binding</li>
</ul>

<h4>AD numbering</h4>
<p>ADs are numbered YYYY-NN-NN. Example: AD 2024-15-08 — the 8th AD adopted in the 15th biweekly issue of 2024. Plus a sequence within the year. The number is the legal citation.</p>

<h4>AD vs SB — the practical difference</h4>
<table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse;">
  <tr style="background:#0d2240; color:#fff;">
    <th>Aspect</th><th>Service Bulletin</th><th>Airworthiness Directive</th>
  </tr>
  <tr><td>Issuer</td><td>OEM (engine manufacturer)</td><td>FAA</td></tr>
  <tr><td>Legal force</td><td>Recommendation (unless contractually binding)</td><td>Mandatory under 14 CFR Part 39</td></tr>
  <tr><td>Penalty for non-compliance</td><td>Loss of OEM warranty, possibly customer contract issue</td><td>Aircraft is not airworthy; cannot fly legally</td></tr>
  <tr><td>Compliance verification</td><td>Operator/repair station logbook entries</td><td>Logbook entries plus availability to FAA inspectors</td></tr>
  <tr><td>Supersedure</td><td>OEM may revise or withdraw at any time</td><td>FAA action required to revise or withdraw</td></tr>
</table>

<h4>How ADs affect TurbineWorks inventory</h4>
<p>When a new AD is issued affecting parts TurbineWorks stocks or trades:</p>
<ol>
  <li>Identify affected serial numbers / part numbers in current inventory</li>
  <li>Identify affected parts already shipped to customers (so they can be notified)</li>
  <li>Determine the AD compliance status of each affected part (pre-AD configuration vs post-AD; if action required to comply, is the part still saleable)</li>
  <li>Update inventory records with AD applicability</li>
  <li>Notify affected customers per contractual or regulatory requirement</li>
</ol>

<h4>Example: AD that requires parts replacement</h4>
<p>FAA issues AD 2024-XX-XX requiring replacement of certain turbine blade part numbers (specific serial ranges) on engines that have accumulated more than 5,000 cycles. Effective immediately.</p>
<p>TurbineWorks impact:</p>
<ul>
  <li>Affected blades currently in inventory: if pre-AD configuration, they can still be sold AS-IS only to customers who will retire them; cannot be installed without immediate replacement after install</li>
  <li>Replacement blades (post-AD configuration): demand spike</li>
  <li>Customer notification: any blade in the affected serial range shipped in the last [X] years needs to be communicated</li>
</ul>

<h4>Where to find ADs</h4>
<ul>
  <li><a href="https://drs.faa.gov/" target="_blank" rel="noopener">FAA Dynamic Regulatory System (DRS)</a> — official AD database, searchable</li>
  <li>The Federal Register — where ADs are first published</li>
  <li>Third-party AD-tracking subscription services</li>
</ul>

<h4>What every TurbineWorks employee should know</h4>
<ul>
  <li>An AD is law. SB is guidance.</li>
  <li>An AD&apos;s applicability statement is exact. A part one serial number outside the range is not affected.</li>
  <li>AD compliance status follows the part — it must be documented through the supply chain.</li>
  <li>New ADs affecting inventory require immediate action: inventory review, customer notification, sales hold if appropriate.</li>
</ul>
HTML
        ],
        [
            'name'  => 'Lesson 3 — Service Bulletins: Mandatory, Alert, Recommended, Optional',
            'intro' => '<p>The OEM bulletin categories and how each affects inventory value and customer disclosure.</p>',
            'content' => <<<'HTML'
<h3>Service Bulletin Categories</h3>
<p>Engine OEMs issue thousands of Service Bulletins over the life of an engine program. Not all SBs are equal. Understanding the categories is essential to assessing inventory value and to disclosing accurately to customers.</p>

<h4>Category 1: Mandatory Service Bulletin (MSB)</h4>
<p>The OEM mandates compliance. Typically these address safety, reliability, or operational issues that the OEM considers critical.</p>
<ul>
  <li>Compliance is required to maintain warranty</li>
  <li>Often becomes an FAA AD within months of issuance</li>
  <li>Often time-limited (must comply within X flight hours / X months)</li>
</ul>
<p>From a distribution standpoint: a part subject to a mandatory SB that has not been complied with is significantly devalued. Customers will require either a discount or post-SB configuration.</p>

<h4>Category 2: Alert Service Bulletin (ASB)</h4>
<p>Higher urgency than a routine SB. The OEM has identified a specific failure mode or safety concern. Often:</p>
<ul>
  <li>Issued in response to in-service failures or incidents</li>
  <li>Frequently leads to AD action by FAA</li>
  <li>Time-critical compliance</li>
</ul>
<p>When an ASB is issued, monitor for FAA AD action. The lag is typically 30-180 days.</p>

<h4>Category 3: Recommended Service Bulletin</h4>
<p>OEM-recommended but not mandatory. Examples: improved-material upgrades, fuel-economy improvements, life-extension modifications.</p>
<ul>
  <li>Compliance is operator choice</li>
  <li>Does not affect airworthiness</li>
  <li>May affect OEM warranty for certain failure modes</li>
</ul>
<p>From a distribution standpoint: pre-modification and post-modification configurations may have different demand and pricing. Both are saleable.</p>

<h4>Category 4: Optional Service Bulletin (OSB)</h4>
<p>Operator-choice modifications. Examples: aesthetic changes, customer-convenience options, alternative-vendor parts.</p>
<ul>
  <li>Pure operator preference</li>
  <li>No airworthiness implications</li>
  <li>Pre/post configurations equally saleable</li>
</ul>

<h4>Reading an SB</h4>
<p>A typical SB structure:</p>
<ol>
  <li><strong>SB Number and Category</strong> (e.g., "Mandatory" or "Alert")</li>
  <li><strong>Effectivity</strong> — which engine serial numbers / part numbers are affected</li>
  <li><strong>Reason for Issue</strong> — what problem the SB addresses</li>
  <li><strong>Description</strong> — what the modification or inspection involves</li>
  <li><strong>Compliance</strong> — when and how compliance must be achieved</li>
  <li><strong>Material/Parts Required</strong> — new parts needed for compliance</li>
  <li><strong>Accomplishment Instructions</strong> — step-by-step procedure</li>
  <li><strong>Recording Requirements</strong> — what records must be created showing compliance</li>
  <li><strong>Revision History</strong> — SBs are revised; current revision matters</li>
</ol>

<h4>SB compliance documentation</h4>
<p>When an SB is incorporated on a part, the compliance is documented:</p>
<ul>
  <li>Logbook entry citing the SB by number and revision</li>
  <li>Date of compliance</li>
  <li>Time/cycles on the part at compliance</li>
  <li>Possibly a new tag or stamp on the part indicating post-mod configuration</li>
  <li>Updated part number if the SB changed the part number</li>
</ul>
<p>The receiving inspector verifies these records when receiving a part that has been subject to an SB.</p>

<h4>SB supersedure</h4>
<p>SBs are revised. A part complied with Rev A of an SB may need to be re-evaluated against Rev B or Rev C if the SB was later revised. Current revision of the SB governs.</p>

<h4>SBs that change part numbers</h4>
<p>Some SBs change a part&apos;s part number to distinguish pre-mod from post-mod (e.g., "Old PN 1234-001, New PN 1234-002 after SB 72-9876 incorporation"). The IPC (Illustrated Parts Catalog) tracks these supersedures. A current IPC is essential for accurate parts identification.</p>

<h4>What this means at TurbineWorks</h4>
<ul>
  <li>Know the SB compliance status of every serialized part in inventory</li>
  <li>Disclose SB status accurately to customers in quotes and on COCs</li>
  <li>For pre-mod parts, the customer should know what would be required to comply post-installation</li>
  <li>Maintain access to current SB documents for engines actively traded</li>
</ul>
HTML
        ],
        [
            'name'  => 'Lesson 4 — Module Summary',
            'intro' => '<p>AD / SB / OEM publication recap.</p>',
            'content' => <<<'HTML'
<h3>AD / SB Module Summary</h3>

<h4>What you should now be able to answer</h4>
<ol>
  <li>What is the difference between an AD and an SB in terms of legal force?</li>
  <li>What are the four common SB categories (Mandatory, Alert, Recommended, Optional)?</li>
  <li>What documents should accompany a part that has had an SB incorporated?</li>
  <li>Where do you look up current ADs?</li>
  <li>Why does an SB sometimes change a part&apos;s part number?</li>
</ol>

<h4>Key Terms</h4>
<dl>
  <dt><strong>AD</strong></dt><dd>Airworthiness Directive — FAA-mandated action under 14 CFR Part 39.</dd>
  <dt><strong>SB / ASB / OSB</strong></dt><dd>Service Bulletin variants — OEM-issued guidance with varying urgency.</dd>
  <dt><strong>ESM / CMM</strong></dt><dd>Engine Shop Manual / Component Maintenance Manual.</dd>
  <dt><strong>IPC / IPL</strong></dt><dd>Illustrated Parts Catalog / List — OEM authoritative parts list.</dd>
  <dt><strong>AMOC</strong></dt><dd>Alternative Method of Compliance — FAA-accepted alternative to the literal AD action.</dd>
  <dt><strong>Post-mod / Pre-mod</strong></dt><dd>Configuration state of a part before or after a modification per SB.</dd>
</dl>

<h4>References</h4>
<ul>
  <li>14 CFR Part 39 — Airworthiness Directives</li>
  <li><a href="https://drs.faa.gov/" target="_blank" rel="noopener">FAA Dynamic Regulatory System (DRS)</a> — AD search</li>
  <li>OEM technical publication portals (CFM, GE Aerospace, Pratt &amp; Whitney, Rolls-Royce, Honeywell, etc.)</li>
  <li>ATA Spec 100 — chapter numbering convention</li>
</ul>
HTML
        ],
    ];
}

// ============================================================================
// ENGINE-PARTS COURSE: Borescope Inspection (BSI)
// ============================================================================
function local_twu_module_bsi_lessons(): array {
    return [
        [
            'name'  => 'Lesson 1 — What is Borescope Inspection?',
            'intro' => '<p>The non-destructive technique that allows internal engine inspection without disassembly.</p>',
            'content' => <<<'HTML'
<h3>Borescope Inspection (BSI) Familiarization</h3>
<p>A borescope is a flexible or rigid optical instrument that allows visual inspection of engine internals without disassembling the engine. The probe enters through dedicated borescope ports designed into modern engines, and the inspector views the internal components on a screen — including turbine blades, combustor liners, compressor stages, and seals.</p>
<p>TurbineWorks employees are not typically borescope inspectors — that&apos;s an MRO or operator function. But borescope inspection reports are documents TurbineWorks regularly encounters, particularly when:</p>
<ul>
  <li>Buying engines or modules that have recent BSI reports</li>
  <li>Selling engines or modules whose value depends on the BSI condition</li>
  <li>Evaluating LLPs that have remaining life but were removed because of BSI findings</li>
</ul>

<h4>Why borescope inspection exists</h4>
<p>Engine internals are subject to high-temperature, high-stress, oxidizing environment that produces predictable wear and damage patterns. Identifying these patterns early allows maintenance to be scheduled before failure. Without borescope inspection, you would have to disassemble the engine to look — which is expensive and itself a maintenance event.</p>

<h4>What a BSI can see</h4>
<ul>
  <li><strong>Combustor liner condition</strong> — cracks, missing pieces, thermal barrier coating loss</li>
  <li><strong>Turbine blade airfoils</strong> — burn-through, tip rub, edge erosion, FOD damage</li>
  <li><strong>Compressor blade leading edges</strong> — FOD damage from ingested debris</li>
  <li><strong>Vane shrouds and platforms</strong> — cracks, distortion</li>
  <li><strong>Seal land condition</strong> — wear, damage</li>
  <li><strong>Fuel nozzle condition</strong> — carbon buildup, erosion</li>
  <li><strong>Internal foreign objects</strong> — bolts, nuts, fragments left from prior maintenance</li>
</ul>

<h4>What a BSI cannot see</h4>
<ul>
  <li>Internal bores of disks (need ultrasonic or fluorescent penetrant)</li>
  <li>Subsurface cracks</li>
  <li>Dimensional measurements (BSI is visual only)</li>
  <li>Material properties</li>
  <li>Areas not accessible through borescope ports (always some hidden areas)</li>
</ul>

<h4>BSI tools</h4>
<ul>
  <li><strong>Rigid borescope</strong> — straight optical tube. Best image quality but limited reach.</li>
  <li><strong>Flexible borescope (fiberscope)</strong> — fiber-optic bundle. Reaches around bends. Lower resolution than rigid.</li>
  <li><strong>Video borescope</strong> — digital camera at the probe tip. Modern standard. High resolution, articulating tip for steering.</li>
  <li><strong>Measurement borescopes</strong> — calibrated borescopes that estimate damage size using stereo imaging or known reference dimensions.</li>
</ul>

<h4>The BSI report</h4>
<p>A BSI inspection produces a written report and (typically) photographs or video. The report documents:</p>
<ul>
  <li>Engine identification (serial number, model)</li>
  <li>BSI date, inspector, port(s) used</li>
  <li>Findings: location, type, dimensions of any damage observed</li>
  <li>Disposition: acceptable, monitor at next BSI, action required</li>
  <li>Reference to applicable inspection criteria (ESM section)</li>
</ul>
<p>The BSI report follows the engine. When TurbineWorks buys or sells an engine or module, BSI reports are part of the technical record package and affect value substantially.</p>
HTML
        ],
        [
            'name'  => 'Lesson 2 — Reading a BSI Report',
            'intro' => '<p>How to interpret findings without being a borescope inspector yourself.</p>',
            'content' => <<<'HTML'
<h3>Reading a BSI Report</h3>
<p>TurbineWorks employees evaluating engine inventory often see BSI reports without performing the inspections themselves. Understanding what a report says is key to pricing, disclosure, and customer communication.</p>

<h4>Standard finding categories</h4>
<dl>
  <dt><strong>"Within limits"</strong></dt>
  <dd>Damage observed but acceptable per the ESM. Engine continues in service. Most BSI reports have some "within limits" findings — most engines have some accumulated minor damage.</dd>

  <dt><strong>"Monitor"</strong></dt>
  <dd>Damage approaches limits. To be re-inspected at the next scheduled BSI interval. Documented so the rate of growth can be tracked.</dd>

  <dt><strong>"Repair required"</strong></dt>
  <dd>Damage exceeds in-service limits. Engine must be removed for shop visit to address. Or specific repair authorized per OEM data.</dd>

  <dt><strong>"Reject / scrap"</strong></dt>
  <dd>Damage exceeds repairable limits. Component must be replaced.</dd>
</dl>

<h4>Typical findings and meaning</h4>
<table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse;">
  <tr style="background:#0d2240; color:#fff;">
    <th>Finding</th><th>What it means</th><th>Typical impact on saleability</th>
  </tr>
  <tr>
    <td>HPT blade tip wear (within limits)</td>
    <td>Normal wear at high-pressure turbine blade tips from rubbing on shroud during thermal cycling</td>
    <td>Acceptable; common; minimal value impact</td>
  </tr>
  <tr>
    <td>Combustor liner crack (within limits)</td>
    <td>Cracks at known stress concentrations; growth monitored</td>
    <td>Common; affects life expectancy disclosure</td>
  </tr>
  <tr>
    <td>FOD damage on compressor blades</td>
    <td>Ingested foreign object (sand, ice, bird, runway debris) struck blade leading edges</td>
    <td>Depends on size and location; may require blade blending or replacement</td>
  </tr>
  <tr>
    <td>Burn-through in combustor</td>
    <td>Local overheating burned through liner material</td>
    <td>Repair required; significant value impact</td>
  </tr>
  <tr>
    <td>Missing thermal barrier coating (TBC)</td>
    <td>Ceramic coating on hot-section parts has spalled</td>
    <td>Reduces parent metal protection; OEM limits vary on how much TBC loss is acceptable</td>
  </tr>
  <tr>
    <td>Cracked turbine vane</td>
    <td>Crack in fixed vane between turbine stages</td>
    <td>Repair or replace depending on location and length</td>
  </tr>
</table>

<h4>Photographic documentation</h4>
<p>Modern BSI reports include photographs or video of every significant finding. Photos provide:</p>
<ul>
  <li>Evidence the finding was actually observed</li>
  <li>Reference for tracking growth at subsequent BSIs</li>
  <li>Documentation for customer disclosure when selling the engine</li>
</ul>
<p>A BSI report without photos is less authoritative than one with photos. Customers buying high-value engines typically require photo/video documentation.</p>

<h4>Inspection criteria reference</h4>
<p>Every BSI finding is evaluated against a specific section of the ESM. The report should cite the ESM section. Example: "Crack 0.150 inch in combustor liner panel 3 — within limit per ESM 72-00-00, Inspection Limits Section, allowable to 0.250 inch."</p>
<p>Without this reference, the "within limits" assessment is unsupportable. A finding asserted as "within limits" with no ESM citation is weak documentation.</p>

<h4>BSI vs disassembly</h4>
<p>BSI is non-destructive and faster than disassembly. But disassembly inspection is more thorough. An engine that "passes BSI" may still have issues that only disassembly reveals (subsurface cracks, dimensional wear, bearing wear). When TurbineWorks sells an engine with BSI documentation only (no recent shop visit), that should be disclosed to the buyer.</p>

<h4>BSI report retention</h4>
<p>BSI reports become part of the engine&apos;s permanent maintenance history. They follow the engine through ownership changes and are retained per maintenance record requirements (effectively the life of the engine).</p>
HTML
        ],
        [
            'name'  => 'Lesson 3 — BSI and TurbineWorks Inventory Valuation',
            'intro' => '<p>How BSI condition affects what a part or module is worth.</p>',
            'content' => <<<'HTML'
<h3>BSI and Inventory Valuation</h3>
<p>The economic value of an engine, module, or rotable is heavily influenced by its condition as documented by recent BSI. TurbineWorks employees involved in purchasing or valuation need to understand this relationship.</p>

<h4>What buyers care about</h4>
<ul>
  <li><strong>Time until next BSI</strong> — how long the engine can be operated before the buyer pays for the next inspection</li>
  <li><strong>Current condition margin</strong> — how much wear/damage has accumulated; how close to next-action thresholds</li>
  <li><strong>LLP remaining life</strong> — also influenced by BSI findings on hot-section components</li>
  <li><strong>Estimated time to next shop visit</strong> — biggest cost in engine operation; BSI condition is a leading indicator</li>
</ul>

<h4>Pricing framework (high level)</h4>
<p>Engine and module pricing typically uses a "green time" framework:</p>
<ul>
  <li>Engine value at zero hours / zero cycles since last shop visit = baseline market value</li>
  <li>Each accumulated cycle reduces value</li>
  <li>BSI findings beyond normal wear reduce value further</li>
  <li>Approaching LLP retirement reduces value steeply (an LLP at 95% of life limit has near-zero remaining value)</li>
  <li>"Half-life" pricing splits the difference between zero-time and at-overhaul value</li>
</ul>

<h4>BSI report freshness</h4>
<ul>
  <li>A BSI report less than 100 flight hours old is "current" — buyers can rely on it</li>
  <li>A BSI report 500+ flight hours old reflects an earlier engine condition; buyer may want a fresh BSI before purchase</li>
  <li>A BSI report 1,000+ flight hours old is largely informational; cannot be relied on for current condition</li>
</ul>
<p>The age of the BSI report at time of sale is a critical disclosure. TurbineWorks should always disclose the BSI date and total time accumulated since the BSI.</p>

<h4>"Test cell" alternative</h4>
<p>For engines being prepared for sale, some sellers run them in a test cell (controlled engine test facility) and document the test data. Test cell data is more comprehensive than BSI (includes performance parameters, vibration data, oil consumption) and may be more valuable to a buyer than recent BSI alone.</p>

<h4>Disclosure obligations</h4>
<p>TurbineWorks must accurately disclose:</p>
<ul>
  <li>Date of most recent BSI</li>
  <li>Findings of most recent BSI (or a summary; full report on request)</li>
  <li>Total time / cycles accumulated since most recent BSI</li>
  <li>Known issues identified in any BSI in the engine&apos;s history</li>
  <li>Whether the engine has been "preserved" (long-term storage with corrosion inhibitor) since the most recent BSI</li>
</ul>
<p>Misrepresenting BSI condition to make a sale is fraud. ASA-100 distributor accreditation is built on accurate disclosure.</p>

<h4>BSI for module-level transactions</h4>
<p>TurbineWorks often trades engine modules (HPC, HPT, LPT modules) rather than whole engines. BSI for modules:</p>
<ul>
  <li>Module BSI is a subset of full-engine BSI — only the components within that module are inspected</li>
  <li>Module BSI reports look similar to full-engine reports but with limited scope</li>
  <li>Module pricing follows the same green-time framework but applied to the module&apos;s own LLP set</li>
</ul>

<h4>When BSI is not available</h4>
<p>Some inventory arrives with no recent BSI documentation. This affects pricing and saleability:</p>
<ul>
  <li>"Sold AS-IS, no BSI" pricing is typically substantially below pricing with current BSI</li>
  <li>Buyer may require BSI before installation (at their cost or by contract)</li>
  <li>Disclosure as "no current BSI" is essential</li>
</ul>
HTML
        ],
        [
            'name'  => 'Lesson 4 — Module Summary',
            'intro' => '<p>BSI familiarization recap.</p>',
            'content' => <<<'HTML'
<h3>BSI Module Summary</h3>

<h4>What you should now be able to answer</h4>
<ol>
  <li>What is borescope inspection and what does it allow operators to do without disassembly?</li>
  <li>What types of damage can BSI see, and what can it not see?</li>
  <li>What are the standard BSI finding categories (within limits, monitor, repair, reject)?</li>
  <li>How does BSI condition affect the market value of an engine or module?</li>
  <li>What must be disclosed to a customer regarding BSI status of inventory being sold?</li>
</ol>

<h4>Key Terms</h4>
<dl>
  <dt><strong>BSI</strong></dt><dd>Borescope Inspection — visual inspection of engine internals via optical probe.</dd>
  <dt><strong>HPT / LPT / HPC / LPC</strong></dt><dd>High-Pressure Turbine / Low-Pressure Turbine / High-Pressure Compressor / Low-Pressure Compressor — major engine modules.</dd>
  <dt><strong>TBC</strong></dt><dd>Thermal Barrier Coating — ceramic coating on hot-section parts that protects parent metal.</dd>
  <dt><strong>FOD damage</strong></dt><dd>Damage to internal components from ingested objects.</dd>
  <dt><strong>Green time</strong></dt><dd>Operating time/cycles remaining before the next major maintenance event.</dd>
</dl>

<h4>References</h4>
<ul>
  <li>OEM Engine Shop Manuals — borescope inspection sections (per engine model)</li>
  <li>FAA AC 43-204 — Visual Inspection for Aircraft</li>
  <li>ATA Spec 105 — borescope inspection terminology</li>
</ul>
HTML
        ],
    ];
}

// ============================================================================
// ENGINE-PARTS COURSE: TBO, Hard Time, On-Condition, Engine Time
// ============================================================================
function local_twu_module_tbo_lessons(): array {
    return [
        [
            'name'  => 'Lesson 1 — Maintenance Philosophies: Hard Time, On-Condition, Condition Monitoring',
            'intro' => '<p>The three maintenance approaches that determine when engines and components come off-wing.</p>',
            'content' => <<<'HTML'
<h3>Engine Maintenance Philosophies</h3>
<p>Engines and engine components come off-wing for maintenance based on one of three philosophies. Understanding which applies to a given part is essential to valuing it accurately and to communicating with customers.</p>

<h4>Hard Time (HT)</h4>
<p>The component has a fixed retirement or overhaul time. At that time, regardless of condition, the component must be removed and either retired or sent for overhaul.</p>
<ul>
  <li>Applied to most LLPs (Life Limited Parts) — turbine and compressor disks, certain shafts</li>
  <li>Applied to some non-LLP components per OEM mandate</li>
  <li>Time expressed in cycles, flight hours, or calendar time (sometimes all three; whichever comes first)</li>
  <li>The clock cannot be reset — accumulated time is permanent</li>
</ul>
<p>From a distribution standpoint: hard-time components have predictable remaining value. A turbine disk with 60% of its hard-time life used has 40% of its value (very roughly — the relationship is not perfectly linear).</p>

<h4>On-Condition (OC)</h4>
<p>The component is operated until its condition (determined by inspection, monitoring, or operational performance) indicates maintenance is needed. No fixed retirement time.</p>
<ul>
  <li>Applied to many engine components: combustor liners, turbine blades and vanes, bearings, seals</li>
  <li>Condition is determined by BSI, oil analysis, vibration monitoring, performance trending</li>
  <li>Component is replaced or repaired when condition criteria are exceeded</li>
</ul>
<p>From a distribution standpoint: on-condition parts have value determined by their current condition, not by an accumulated time clock. A used HPT blade in good condition (recent BSI clean) has higher value than the same blade with marginal BSI findings, even if both have similar time-since-new.</p>

<h4>Condition Monitoring (CM)</h4>
<p>A more sophisticated form of on-condition that uses systematic data collection to predict maintenance needs. Used for systems where performance can be trended (engine performance parameters, oil consumption, vibration spectra).</p>
<ul>
  <li>No fixed maintenance schedule</li>
  <li>Maintenance triggered by trended data exceeding thresholds</li>
  <li>Increasingly the dominant philosophy for engines with comprehensive monitoring (modern FADEC-equipped engines)</li>
</ul>

<h4>The mix in modern engines</h4>
<p>A modern turbofan engine uses all three philosophies simultaneously:</p>
<ul>
  <li><strong>Hard time</strong>: LLPs (turbine and compressor disks, certain shafts)</li>
  <li><strong>On-condition</strong>: Hot-section airfoils (blades, vanes), combustor, bearings, seals</li>
  <li><strong>Condition monitoring</strong>: Overall engine performance, oil system, vibration</li>
</ul>
<p>The engine itself doesn&apos;t have a fixed TBO (Time Between Overhauls) in the way piston engines once did — it comes off-wing when condition monitoring or specific component limits indicate maintenance is needed.</p>

<h4>TBO (legacy concept)</h4>
<p><strong>TBO — Time Between Overhauls</strong> — was the dominant philosophy for piston engines and early turbines. The OEM specified a fixed overhaul interval (e.g., 2,000 flight hours for some piston engines). Modern turbofans have largely moved past TBO in favor of on-condition + hard-time-for-LLPs.</p>
<p>You still hear "TBO" used loosely to mean "expected time until next major shop visit," even for engines that don&apos;t have a formal TBO. Be precise when using the term.</p>

<h4>What this means for TurbineWorks valuation</h4>
<ul>
  <li>LLP value = a function of remaining hard-time life</li>
  <li>Hot-section component value = a function of current condition (BSI, etc.)</li>
  <li>Whole-engine value = function of LLP remaining life + hot-section condition + accessories condition + recent shop-visit history</li>
</ul>
HTML
        ],
        [
            'name'  => 'Lesson 2 — Engine Time Tracking: TSN, CSN, TSO, CSO',
            'intro' => '<p>The time-and-cycle accounting that determines remaining value and life.</p>',
            'content' => <<<'HTML'
<h3>Engine Time Tracking</h3>
<p>Aviation time tracking uses a small set of standard abbreviations. They apply at the engine level and at the component level. Confusion among them causes pricing errors and traceability gaps.</p>

<h4>The standard terms</h4>
<dl>
  <dt><strong>TSN — Time Since New</strong></dt>
  <dd>Total operating time since the engine or component was manufactured. Expressed in flight hours. Always increases.</dd>

  <dt><strong>CSN — Cycles Since New</strong></dt>
  <dd>Total operating cycles since manufacture. A cycle = one takeoff/landing (or one start/shutdown for some applications). Used primarily for hot-section life tracking. Always increases.</dd>

  <dt><strong>TSO — Time Since Overhaul</strong></dt>
  <dd>Operating time since the most recent overhaul. Resets to zero at each overhaul. Used to assess current wear state.</dd>

  <dt><strong>CSO — Cycles Since Overhaul</strong></dt>
  <dd>Cycles since most recent overhaul. Resets at each overhaul.</dd>

  <dt><strong>TSI / CSI — Time / Cycles Since Inspection</strong></dt>
  <dd>Since most recent specified inspection (BSI, shop visit, scheduled check). Resets at each inspection.</dd>

  <dt><strong>TSR / CSR — Time / Cycles Since Repair</strong></dt>
  <dd>Since most recent repair event.</dd>
</dl>

<h4>Why both time AND cycles</h4>
<p>Different failure modes track to different parameters:</p>
<ul>
  <li><strong>Thermal cycling damage</strong> tracks with cycles (each takeoff/landing is a thermal cycle of heating and cooling)</li>
  <li><strong>Steady-state wear</strong> tracks with time (continuous operation)</li>
  <li><strong>Calendar damage</strong> (corrosion, elastomer degradation) tracks with calendar time independent of operation</li>
</ul>
<p>OEM life limits typically express as "X cycles or Y hours, whichever comes first" — the limit is whichever is reached first.</p>

<h4>The TSN/CSN relationship</h4>
<p>For a given engine in regular service: CSN is typically a small fraction of TSN (a long-haul widebody might fly 14 hours per cycle; a regional jet might fly 1 hour per cycle). The TSN/CSN ratio reveals operating profile.</p>
<ul>
  <li>High TSN, low CSN: long-haul service (oceanic widebody operation)</li>
  <li>Low TSN, high CSN: short-haul service (regional or shuttle operation)</li>
</ul>
<p>For pricing: high CSN tends to indicate more hot-section wear and less remaining life per accumulated time. Two engines with identical TSN may have very different CSN and very different remaining value.</p>

<h4>Recording time at TurbineWorks</h4>
<p>When TurbineWorks receives an engine or component, the current TSN/CSN/TSO/CSO is recorded based on the documentation that arrived with the part. Going forward:</p>
<ul>
  <li>If the part remains in storage at TurbineWorks: TSN/CSN do not increase (no operation)</li>
  <li>Calendar time still increases — relevant for parts with calendar limits</li>
  <li>If the part is shipped to a customer: time tracking transfers to the customer&apos;s records</li>
</ul>

<h4>Time data integrity</h4>
<p>TSN/CSN values come from operator logbooks. If logbooks show inconsistencies (calculated time doesn&apos;t match recorded time, or values decrease between consecutive entries) — that&apos;s a traceability flag.</p>
<p>An engine arriving with a "gap" in its time records (period of unknown operation) is similar to an LLP with a gap in back-to-birth — the unknown period may have accumulated unknown time. Treat as a documentation flag.</p>

<h4>Time data on the 8130-3</h4>
<p>Block 12 (Remarks) of an 8130-3 for a serialized component typically includes:</p>
<ul>
  <li>TSN, CSN at time of release</li>
  <li>TSO, CSO if applicable (since most recent overhaul)</li>
  <li>Statement of LLP remaining life if applicable</li>
</ul>
<p>Verify these values are internally consistent (TSO ≤ TSN) and consistent with the supplier&apos;s logbook data.</p>
HTML
        ],
        [
            'name'  => 'Lesson 3 — Shop Visits, Overhauls, and Engine Build Standards',
            'intro' => '<p>What happens when an engine goes "off-wing" and what comes back differs.</p>',
            'content' => <<<'HTML'
<h3>Shop Visits and Build Standards</h3>
<p>An engine removed from an aircraft and sent to an MRO for maintenance is said to have a "shop visit." The scope of work and the resulting engine configuration vary widely. Understanding shop visit terminology is essential to evaluating engines.</p>

<h4>Shop visit scope categories</h4>
<dl>
  <dt><strong>Minor repair</strong></dt>
  <dd>Limited-scope work, often addressing a specific finding (a damaged component, an SB compliance). Engine is partially disassembled, work performed, reassembled. TSN/CSN continues; TSO/CSO does not reset.</dd>

  <dt><strong>Performance restoration</strong></dt>
  <dd>Significant work to restore engine performance. May include hot-section refurbishment, replacement of consumable hot-section parts, but not full overhaul of LLPs.</dd>

  <dt><strong>Heavy maintenance / Full Overhaul</strong></dt>
  <dd>Engine fully disassembled, every component inspected and either repaired, replaced, or restored. New or refurbished LLPs as needed. The engine returns essentially as a new build (within the limits of remaining LLP life).</dd>

  <dt><strong>Module exchange</strong></dt>
  <dd>Specific modules (HPC, HPT, LPT) swapped instead of overhauling the engine as a unit. Module exchange is faster and cheaper than full overhaul but requires careful tracking of which modules are now in this engine vs. their original engines.</dd>
</dl>

<h4>Build standards</h4>
<p>An engine emerging from a shop visit is built to a specific "build standard" — a defined configuration:</p>
<ul>
  <li>Which SBs are incorporated</li>
  <li>Which OEM modifications are present</li>
  <li>Which optional features are included</li>
  <li>What thrust rating (engines come in multiple thrust variants)</li>
</ul>
<p>The build standard is documented and follows the engine. When TurbineWorks buys or sells an engine, the build standard is part of the disclosed configuration.</p>

<h4>Shop visit documentation</h4>
<p>A shop visit produces extensive documentation:</p>
<ul>
  <li>Workscope document — what was done</li>
  <li>Parts list — what was replaced (with serial numbers in/out)</li>
  <li>SB compliance updates</li>
  <li>AD compliance verification</li>
  <li>Test cell results — post-build performance verification</li>
  <li>Final airworthiness release (often an 8130-3 for the engine as released from shop)</li>
</ul>
<p>This package is the "shop visit report" and follows the engine permanently.</p>

<h4>Green-time pricing relevance</h4>
<p>An engine just out of full overhaul: maximum green time (cycles remaining until next planned shop visit). Highest market value.</p>
<p>An engine approaching its next planned shop visit: minimum green time. Lowest market value relative to its post-overhaul value.</p>
<p>Pricing typically expressed in dollars per cycle of green time, or as a total dollar value reflecting the green time remaining.</p>

<h4>"Half-life" engines</h4>
<p>An engine roughly midway between overhauls is sometimes priced at "half-life" — half-way between the post-overhaul value and the at-overhaul value. The concept is loose but commonly used in informal market discussion.</p>

<h4>Module-level shop visits</h4>
<p>Some shop visits address only one module (e.g., HPT module exchange). The other modules remain unaffected. After such a shop visit:</p>
<ul>
  <li>The exchanged module has reset TSO/CSO</li>
  <li>The retained modules have continued TSO/CSO from their previous state</li>
  <li>The engine as a whole has a mixed state</li>
</ul>
<p>This significantly complicates valuation — different parts of the same engine have different remaining life. Module-level tracking and pricing becomes essential.</p>

<h4>What every TurbineWorks employee should know</h4>
<ul>
  <li>"Shop visit" is a broad term — always confirm scope</li>
  <li>"Just overhauled" requires verification — what specifically was done?</li>
  <li>Build standard affects value and customer compatibility (a customer wanting post-SB configuration won&apos;t accept pre-SB even if recently overhauled)</li>
  <li>Module-level exchange complicates traceability — verify each module&apos;s individual records</li>
</ul>
HTML
        ],
        [
            'name'  => 'Lesson 4 — Module Summary',
            'intro' => '<p>TBO / Time tracking recap.</p>',
            'content' => <<<'HTML'
<h3>TBO and Engine Time Module Summary</h3>

<h4>What you should now be able to answer</h4>
<ol>
  <li>What are the three maintenance philosophies (Hard Time, On-Condition, Condition Monitoring)?</li>
  <li>What does TSN, CSN, TSO, CSO each mean and when does each reset?</li>
  <li>Why do OEMs express life limits as both cycles AND hours, whichever comes first?</li>
  <li>What are the major categories of shop visit scope, and how do they affect engine configuration?</li>
  <li>What is "green time" and why does it determine inventory pricing?</li>
</ol>

<h4>Key Terms</h4>
<dl>
  <dt><strong>Hard Time</strong></dt><dd>Fixed retirement or overhaul time, regardless of condition.</dd>
  <dt><strong>On-Condition</strong></dt><dd>Maintenance triggered by inspected or monitored condition, no fixed time.</dd>
  <dt><strong>Condition Monitoring</strong></dt><dd>Data-driven maintenance triggered by trended performance.</dd>
  <dt><strong>TSN / CSN</strong></dt><dd>Total Time / Cycles Since New (manufacture).</dd>
  <dt><strong>TSO / CSO</strong></dt><dd>Time / Cycles Since (most recent) Overhaul.</dd>
  <dt><strong>Build Standard</strong></dt><dd>Defined configuration of an engine — SBs incorporated, mods, thrust rating.</dd>
  <dt><strong>Green Time</strong></dt><dd>Remaining operating time / cycles before next planned maintenance event.</dd>
  <dt><strong>Module Exchange</strong></dt><dd>Swap of a complete engine module instead of full-engine overhaul.</dd>
</dl>

<h4>References</h4>
<ul>
  <li>OEM Engine Shop Manuals — maintenance philosophy sections per engine model</li>
  <li>FAA AC 120-77 — Maintenance Recordkeeping</li>
  <li>ATA MSG-3 — Maintenance Steering Group methodology (the framework that drives maintenance philosophy decisions)</li>
</ul>
HTML
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
