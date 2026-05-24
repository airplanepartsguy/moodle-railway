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
                'intro' => '<p>The foundational lesson. Where ASA-100 came from, what it is, why it exists, where it sits in the regulatory landscape, what it covers, and why every employee at TurbineWorks needs to understand it as the framework everything else in this training program rests on.</p>',
                'content' => <<<'HTML'
<h3>What is ASA-100?</h3>

<p><strong>ASA-100</strong> is the <em>Quality Assurance Standard for Aircraft Parts Distributors</em>, published and maintained by the Aviation Suppliers Association (ASA). It defines the quality-system requirements that a distributor of aircraft parts must implement to demonstrate that the parts it sells are traceable, properly handled, and eligible for installation on civil aircraft.</p>

<p>That sentence is the headline definition. The rest of this lesson unpacks each part of it: who publishes ASA-100, why distributors specifically need a standard, what the alternative looks like, how the standard relates to FAA regulations, what compliance practically means, and why this knowledge is foundational for every TurbineWorks employee — not just QA staff.</p>

<h4>Why a parts-distributor standard exists at all</h4>

<p>Aviation\'s safety record is built on a chain of accountability. The manufacturer designs and produces the part under FAA-approved processes and certifies it conforms to type design. The aircraft operator installs the part and is responsible for installation quality. The maintenance organization performs repairs under FAA-approved procedures. Each link in the chain has a documented role and accountability.</p>

<p>The distributor sits between manufacturer and operator. Historically, this link was the weakest part of the chain — least regulated, most subject to fraud, hardest for downstream operators to verify. The FAA recognized this gap in the 1980s and 1990s as counterfeit and unapproved parts began causing in-service incidents traceable to distributors with inadequate quality controls.</p>

<p>The FAA had two options for closing the gap. One: extend direct FAA regulation to distributors with FAA inspectors auditing each one. Two: create a framework for industry-managed accreditation under FAA recognition. The FAA chose option two — for budgetary, political, and practical reasons covered in detail in Module 6. The framework was published as FAA Advisory Circular 00-56 in 1996.</p>

<p>AC 00-56 created the framework, but it did not itself define a quality standard for distributors. That work was left to the accreditation organizations the FAA would recognize. The Aviation Suppliers Association responded by publishing ASA-100 as the standard their accreditation program would audit against. The FAA reviewed ASA\'s program, found it satisfied AC 00-56, and added ASA to the recognized-accreditation-organization list.</p>

<p>The result: a layered system where the FAA defines the framework, ASA defines the standard, and TurbineWorks complies with the standard through its Quality Assurance Manual. Each layer adds specificity. The FAA framework is general; ASA-100 is specific; the TurbineWorks QAM is operational.</p>

<h4>Who publishes and maintains ASA-100</h4>

<p>The <strong>Aviation Suppliers Association (ASA)</strong> is an industry trade association based in Washington, D.C. ASA membership includes distributors, brokers, surplus dealers, and other companies in the aviation parts supply chain. ASA serves multiple functions: industry representation, networking, training, and — most relevant here — accreditation.</p>

<p>ASA-100 is reviewed and updated periodically. The current revision (subject to change; always verify the latest at <a href="https://www.aviationsuppliers.org/" target="_blank" rel="noopener">aviationsuppliers.org</a>) reflects industry experience and FAA expectations as they evolve. Revisions historically have:</p>

<ul>
  <li>Tightened SUP detection requirements in response to industry incidents</li>
  <li>Added explicit counterfeit-prevention language as the counterfeit problem evolved</li>
  <li>Clarified records-retention expectations</li>
  <li>Refined training requirements</li>
  <li>Added or clarified hazmat handling requirements</li>
</ul>

<p>When ASA-100 is revised, accredited distributors are given a transition period to implement the changes. The QA Manager monitors revisions; the QAM is updated accordingly; TurbineWorks University training reflects the current revision.</p>

<h4>The alternative — what happens without ASA-100</h4>

<p>To understand why ASA-100 matters, consider what aviation parts distribution would look like without a quality standard:</p>

<ul>
  <li><strong>No standard receiving inspection.</strong> Each distributor sets its own procedures, ranging from rigorous to nonexistent. Customers cannot evaluate distributors at a distance.</li>
  <li><strong>No traceability discipline.</strong> Parts arrive at operators with documentation chains that may or may not connect to approved sources. Operators can\'t tell which distributors are reliable.</li>
  <li><strong>No SUP detection framework.</strong> Suspect parts pass through the chain because no distributor in the chain has clear obligation or procedure for detection.</li>
  <li><strong>No accountability.</strong> If a customer\'s aircraft fails due to a bad part, the chain of accountability is impossible to trace.</li>
</ul>

<p>This was the actual state of aviation parts distribution in the 1980s. The FAA SUP Program documented the consequences: NTSB investigations, in-service failures, the Partnair Convair 580 crash, others. ASA-100 (and AC 00-56 generally) exist because the unregulated alternative produced unacceptable safety outcomes.</p>

<h4>What ASA-100 actually says (overview of contents)</h4>

<p>The standard is structured by sections covering the lifecycle of a part at a distributor. Each section establishes specific requirements that the accredited distributor must implement in their QMS.</p>

<h5>Quality System Requirements (early sections)</h5>

<p>The standard opens with general QMS requirements: documented procedures, designated personnel, management commitment, resource adequacy. These set the foundation — the distributor must operate as an organized quality system, not as ad-hoc activities.</p>

<h5>Section 6 — Receiving Inspection</h5>

<p>The substantive heart of distributor operations. Defines what must be checked when a part arrives: documentation review, physical inspection, disposition decisions. Covered in operational depth in Module 2.</p>

<h5>Section 7 — Storage and Handling</h5>

<p>How parts are stored to preserve their integrity. ESD control, shelf-life management, FOD prevention, segregation of serviceable from non-serviceable, hazmat storage. Covered in Module 4.</p>

<h5>Section 8 — Recordkeeping</h5>

<p>What records must be kept, for how long, in what form, retrievable how quickly. The records are the audit-evidence backbone of the entire system. Covered in Module 5.</p>

<h5>Section 9 / 10 — Customer complaints, corrective action</h5>

<p>How non-conformances and complaints are handled. The corrective action process is the QMS\'s self-improvement mechanism — every finding becomes a learning opportunity and a process change.</p>

<h5>Internal audits, management review</h5>

<p>The distributor audits itself on a defined cadence (typically annually). Findings are addressed through the corrective action system. Management formally reviews the QMS at intervals (typically annually). This is the self-monitoring required to keep the QMS functioning between external audits.</p>

<h5>Training, supplier qualification, document control</h5>

<p>Supporting elements that hold the system together. Training ensures personnel can perform their roles correctly (this very course satisfies the ASA-100 training requirement). Supplier qualification controls supply-chain integrity at the front end. Document control ensures everyone works from the current revision of procedures.</p>

<p>The exact section numbering and detailed wording shift with revisions, but the substantive scope is consistent.</p>

<h4>"Voluntary but effectively required" — the market reality</h4>

<p>ASA-100 is a voluntary industry standard, not an FAA regulation. The FAA does not mandate that distributors be accredited. Yet virtually every commercial operator and MRO requires their parts suppliers to hold an FAA-recognized accreditation under AC 00-56 — and ASA-100 is the most widely adopted such accreditation.</p>

<h5>Why the market enforces what the FAA does not mandate</h5>

<ul>
  <li><strong>Risk management.</strong> An airline cannot inspect every part it buys to the level required to detect SUP. Buying only from accredited distributors transfers the verification burden to qualified intermediaries.</li>
  <li><strong>Liability insurance.</strong> Many liability insurers require operators to source parts only from accredited suppliers. The premium structure makes non-accredited sourcing financially impractical.</li>
  <li><strong>Customer expectations.</strong> Major customers in turn require their suppliers to source from accredited distributors. The requirement flows down the chain.</li>
  <li><strong>FAA expectations.</strong> While not formally mandated, the FAA in practice expects operators to source from accredited distributors. Failure to do so is a finding in FAA inspections of operators.</li>
  <li><strong>Audit efficiency.</strong> Operators perform supplier audits. Choosing accredited distributors reduces the operator\'s audit burden — the ASA-100 audit covers most of what the operator would otherwise audit themselves.</li>
</ul>

<p>The practical consequence: in the U.S. aftermarket aviation parts business, accreditation is functionally a market-entry requirement. A distributor without ASA-100 (or AS9120, or equivalent) accreditation is excluded from most major customer segments.</p>

<h4>The pre-accreditation period — where TurbineWorks is today</h4>

<p>TurbineWorks is currently building toward ASA-100 accreditation, not yet accredited. This affects the program:</p>

<ul>
  <li>The training program (TurbineWorks University) is part of the documentation the auditor will review during the initial accreditation audit</li>
  <li>The QAM is being developed; some of the procedural references in TurbineWorks University point to TBD locations in the QAM</li>
  <li>Internal audits will begin once the QAM is in place; corrective action history will accumulate before the external audit</li>
  <li>Receiving inspections performed during the pre-accreditation period generate records that will be reviewed by the auditor</li>
</ul>

<p>The pre-accreditation work is itself the audit-readiness preparation. By the time the on-site audit happens, the auditor expects to find a system that has been operating long enough to demonstrate consistent performance — not just a paper system that was set up last week. This is part of why we are investing in training and documentation now, ahead of any specific audit date.</p>

<h4>How accreditation works once granted</h4>

<p>The accreditation process is detailed in Lesson 3.3. At a summary level: TurbineWorks submits the QAM and supporting documents for ASA review (Stage 1, document review). ASA assigns a qualified auditor who performs an on-site audit (Stage 2). The audit produces findings — non-conformances that must be addressed before accreditation is granted. After corrective actions are implemented and verified, ASA issues the accreditation certificate. TurbineWorks is then listed in ASA\'s public database of accredited distributors.</p>

<p>Accreditation typically renews every 3 years through a full re-audit, with surveillance audits possible at any time if customer complaints or other concerns arise.</p>

<h4>Why every TurbineWorks employee needs to understand this</h4>

<p>You might be wondering why a receiving inspector or a warehouse worker needs to understand the regulatory framework. Three reasons:</p>

<ul>
  <li><strong>The ASA auditor will interview you.</strong> During the on-site audit, the auditor speaks with employees at all levels. Questions like "Why do you perform this procedure?" expect answers that demonstrate understanding of the regulatory context, not just procedural compliance. An employee who can answer "because ASA-100 §6 requires it and ASA-100 implements FAA AC 00-56" sounds different to an auditor than an employee who answers "because someone told me to."</li>
  <li><strong>Procedure changes have rationale.</strong> When ASA-100 is revised, or when the FAA issues new guidance, TurbineWorks procedures change. Employees who understand the framework can adapt to changes rather than being confused by them.</li>
  <li><strong>Quality culture rests on understanding.</strong> A quality system where every employee understands why the procedures exist functions differently than one where employees follow procedures mechanically. The understanding is what survives pressure, time, and turnover.</li>
</ul>

<h4>The relationship to other Modules</h4>

<p>Module 3 (this module) is the framework. Subsequent modules go into specific operational areas:</p>

<ul>
  <li>Module 1 — Unapproved parts and counterfeit materials (the threats the system defends against)</li>
  <li>Module 2 — Receiving and shipping inspection (the operational heart, implementing ASA-100 §6)</li>
  <li>Module 4 — Storage and handling (implementing ASA-100 §7)</li>
  <li>Module 5 — Recordkeeping (implementing ASA-100 §8)</li>
  <li>Module 6 — FAA AC 00-56 deep dive (the regulatory framework Module 3 introduces)</li>
  <li>Module 7 — ESD handling (a technical area that supports Module 4)</li>
  <li>Module 8 — Hazmat (a regulatory overlay on Modules 2 and 4)</li>
</ul>

<p>Each module assumes you understand the framework established here. If at any point in the later modules a reference to ASA-100, AC 00-56, the QAM, or the accreditation process is unclear, come back to Module 3.</p>

<h4>Key vocabulary</h4>

<dl>
  <dt><strong>ASA</strong></dt>
  <dd>Aviation Suppliers Association — the trade association publishing ASA-100 and operating the accreditation program.</dd>

  <dt><strong>ASA-100</strong></dt>
  <dd>The Quality Assurance Standard for Aircraft Parts Distributors. The standard TurbineWorks is being accredited against.</dd>

  <dt><strong>Accreditation Organization (AO)</strong></dt>
  <dd>An FAA-recognized organization that audits distributors against a published standard. ASA is the AO TurbineWorks is engaged with.</dd>

  <dt><strong>FAA AC 00-56</strong></dt>
  <dd>The Advisory Circular establishing the Voluntary Industry Distributor Accreditation Program. The framework ASA operates under.</dd>

  <dt><strong>QAM</strong></dt>
  <dd>Quality Assurance Manual. TurbineWorks\' top-level quality document, describing how TurbineWorks complies with ASA-100.</dd>

  <dt><strong>Accountable Manager</strong></dt>
  <dd>Senior company official (typically CEO or equivalent) who is corporately responsible for the quality system.</dd>

  <dt><strong>QA Manager</strong></dt>
  <dd>Day-to-day owner of the quality system; manages the QAM, internal audits, corrective action.</dd>

  <dt><strong>Voluntary but effectively required</strong></dt>
  <dd>Description of ASA-100\'s regulatory status. Not legally mandated; functionally required by the market.</dd>
</dl>

<h4>Self-check</h4>

<ol>
  <li>What does ASA-100 stand for and who publishes it?</li>
  <li>Why did the FAA choose industry accreditation over direct FAA regulation of distributors?</li>
  <li>What is the relationship between FAA AC 00-56, ASA, and ASA-100?</li>
  <li>What does "voluntary but effectively required" mean for ASA-100?</li>
  <li>Name three sections of ASA-100 and what each covers.</li>
  <li>Why does TurbineWorks operate to ASA-100 even though there is no FAR requiring it?</li>
  <li>What happens during the pre-accreditation period and why does training matter during that time?</li>
  <li>How often is ASA-100 accreditation typically renewed?</li>
  <li>Why does every TurbineWorks employee need to understand ASA-100, not just the QA staff?</li>
</ol>

<p><em>Continue to Lesson 3.2 — The FAA AC 00-56 Framework.</em></p>
HTML
            ],
            [
                'name' => 'Lesson 3.2 — The FAA AC 00-56 Framework',
                'intro' => '<p>The FAA Advisory Circular that created the accreditation framework ASA-100 implements. Historical context, structural design, the specific contents of AC 00-56B, how it relates to ASA-100 and to other accreditation programs, and what the framework means for every TurbineWorks employee.</p>',
                'content' => <<<'HTML'
<h3>FAA Advisory Circular 00-56: The Framework</h3>

<p>Lesson 3.1 introduced ASA-100 as the quality standard TurbineWorks is accredited against. This lesson covers the regulatory framework that made ASA-100 possible: <strong>FAA Advisory Circular 00-56</strong>. Without AC 00-56 there is no ASA accreditation program. Without ASA accreditation there is no ASA-100 standard. Without ASA-100 there is no TurbineWorks QAM. The framework matters because everything downstream rests on it.</p>

<h4>What an Advisory Circular is and is not</h4>

<p>The FAA issues regulatory material in several formats:</p>

<ul>
  <li><strong>Federal Aviation Regulations (FARs):</strong> codified in 14 CFR. Force of law. Violations carry civil and criminal penalties.</li>
  <li><strong>Advisory Circulars (ACs):</strong> FAA guidance documents. Not legally binding in themselves, but authoritative interpretations and accepted means of compliance. Following the AC is the safe path; deviating from it requires justification.</li>
  <li><strong>Orders and Notices:</strong> internal FAA documents directing FAA personnel. Visible to industry but binding on the FAA, not on operators directly.</li>
  <li><strong>Special Federal Aviation Regulations (SFARs):</strong> temporary rules with sunset provisions.</li>
</ul>

<p>AC 00-56 is in the second category — guidance. It is not itself a regulation that requires distributors to be accredited. It defines the framework under which voluntary accreditation operates with FAA recognition.</p>

<h5>The "voluntary" significance</h5>

<p>The FAA chose the voluntary framework for several reasons:</p>

<ul>
  <li><strong>Constitutional and procedural simplicity.</strong> Direct regulation of distributors would require an extensive rulemaking process under the Administrative Procedure Act, complete with notice and comment, congressional review, and budget appropriation for enforcement.</li>
  <li><strong>Enforcement burden.</strong> Direct regulation requires FAA inspectors. The FAA had neither the budget nor the staff to inspect every parts distributor in the country on a meaningful cadence.</li>
  <li><strong>Industry expertise.</strong> Industry associations like ASA had more practical familiarity with distributor operations than FAA inspectors. They could write a more rigorous standard than the FAA could.</li>
  <li><strong>Market enforcement.</strong> The FAA understood that if accreditation became the market standard for customer purchasing, distributors would seek accreditation regardless of whether the FAA mandated it. Voluntary accreditation could achieve the same effect as mandatory accreditation through market mechanisms.</li>
</ul>

<p>The choice has worked. ASA-100 has high uptake among major U.S. parts distributors. Major airlines and MROs require accreditation. The voluntary framework is, in practice, mandatory through market pressure.</p>

<h4>Why the FAA wrote AC 00-56</h4>

<p>The full historical context: in the 1980s and 1990s, the FAA identified a recurring pattern. Aircraft incidents and accidents were being traced to parts that had entered the supply chain through inadequately controlled distributors. Specific events drove the regulatory response:</p>

<ul>
  <li><strong>The Partnair Convair 580 crash (1989).</strong> Tail control bolts traced to counterfeit manufacture. The investigation led the FAA to deeply question the integrity of the U.S. parts distribution system.</li>
  <li><strong>FAA SUP Program findings (early 1990s).</strong> The Suspected Unapproved Parts Program documented hundreds of cases per year of parts traced to unaccredited or fraudulent distributors.</li>
  <li><strong>NTSB pattern findings.</strong> Multiple accident investigations across the late 1980s and early 1990s identified parts-source problems as contributing factors.</li>
  <li><strong>Congressional attention.</strong> Hearings on aviation safety in the early 1990s raised distribution-side issues as a regulatory gap.</li>
</ul>

<p>The FAA convened working groups, consulted industry, drafted the AC. The first version of AC 00-56 was published in 1996. It has been revised periodically since; the current revision is 00-56B.</p>

<h4>What AC 00-56 actually says</h4>

<p>The AC is structured into sections covering different aspects of the framework. The structure as of revision B:</p>

<h5>Section 1 — Purpose</h5>
<p>Establishes the AC as guidance for industry-led accreditation of aircraft parts distributors. States that the program is voluntary and that the FAA recognizes accreditation organizations meeting the AC\'s criteria.</p>

<h5>Section 2 — Background</h5>
<p>The history that motivated the AC — the parts-fraud concerns of the 1980s-1990s, the FAA\'s decision to use industry accreditation as the response. This is the section where the FAA explains why it chose voluntary accreditation rather than direct regulation.</p>

<h5>Section 3 — Definitions</h5>
<p>Key terms: <em>Accreditation Organization (AO)</em>, <em>accredited distributor</em>, <em>eligible part</em>, <em>quality system</em>, <em>recognition</em>, and others. The definitions establish what the AC\'s terms of art mean — and indirectly, what scope of activities the framework covers.</p>

<h5>Section 4 — Accreditation Organization Requirements</h5>

<p>This is the substantive heart of the AC. To be FAA-recognized, an accreditation organization must:</p>

<ul>
  <li>Publish a written quality standard (this is what ASA-100 is)</li>
  <li>Have qualified auditors with documented competency standards</li>
  <li>Have an audit program covering all relevant aspects of distributor operations</li>
  <li>Have a documented corrective-action follow-up process</li>
  <li>Maintain records of all accreditation activities (audits, findings, certifications)</li>
  <li>Have a procedure for handling complaints about accredited distributors</li>
  <li>Have a procedure for de-accrediting distributors that fail to maintain compliance</li>
  <li>Be financially independent of the distributors it accredits (no conflict of interest)</li>
  <li>Operate transparently — accredited-distributor lists are public</li>
</ul>

<p>An AO that does not meet these requirements is not FAA-recognized. The list of recognized AOs (Appendix 1) reflects which programs have demonstrated compliance.</p>

<h5>Section 5 — Distributor Quality System Elements</h5>

<p>The AC specifies the minimum elements a distributor\'s quality system must address. This is the floor that the AO\'s standard (ASA-100) must require:</p>

<ul>
  <li>Receiving inspection</li>
  <li>Documentation control</li>
  <li>Identification and traceability</li>
  <li>Storage and handling</li>
  <li>Records (with retention requirements)</li>
  <li>Personnel training</li>
  <li>Internal audits</li>
  <li>Corrective action</li>
  <li>Management review</li>
  <li>Supplier qualification</li>
  <li>Customer complaint handling</li>
</ul>

<p>ASA-100 sections map to these requirements. The mapping is not always one-to-one — ASA-100 may combine multiple AC requirements into a single section or split one AC requirement across multiple sections — but every AC-required element is addressed somewhere in ASA-100.</p>

<h5>Section 6 — Audit Procedures</h5>

<p>Defines minimum audit cadence and content. Initial accreditation requires an on-site audit. Recurring audits are at intervals not exceeding 3 years. Surveillance audits are at the AO\'s discretion based on complaints, performance, or other concerns.</p>

<p>Audit content requirements:</p>

<ul>
  <li>Document review (the QAM and supporting procedures)</li>
  <li>Personnel interviews</li>
  <li>Records review</li>
  <li>Observation of actual operations</li>
  <li>Facility walkthrough</li>
  <li>Sample part traceability verification (pull random parts, trace through receiving)</li>
</ul>

<p>The audit format is structured but not rigidly prescribed. AOs have latitude to apply judgment, but the minimum elements must be covered.</p>

<h5>Section 7 — FAA Oversight</h5>

<p>The FAA monitors the AOs themselves. Activities include:</p>

<ul>
  <li>Periodic FAA review of each AO\'s program (typically every 2-3 years)</li>
  <li>FAA surveillance audits of accredited distributors (the FAA may show up at a distributor independently of ASA)</li>
  <li>FAA review of accreditation withdrawals to ensure the AO is applying its standards consistently</li>
  <li>Authority to withdraw FAA recognition of an AO that fails to maintain its program</li>
</ul>

<p>The FAA\'s recognition of an AO is itself revocable. An AO that becomes lax — that issues accreditation to distributors who don\'t actually meet the standard — risks losing FAA recognition. This keeps the AOs honest in their own audit programs.</p>

<h5>Section 8 — List of Recognized Accreditation Organizations</h5>

<p>Maintained as Appendix 1 to the AC. Periodically updated. Includes contact information for each recognized AO. ASA is on this list. AS9120-administering registrars are typically also listed, though through a slightly different structure.</p>

<h5>Appendices</h5>

<ul>
  <li><strong>Appendix 1:</strong> Recognized AO list (the authoritative current list)</li>
  <li><strong>Appendix 2:</strong> Procedure for an AO to apply for FAA recognition</li>
  <li><strong>Appendix 3:</strong> Definitions and references</li>
</ul>

<h4>What changed in 00-56B vs. earlier revisions</h4>

<p>The current revision B was published as an update to the original 1996 AC and subsequent revision A. Significant changes in revision B included:</p>

<ul>
  <li>Tightened audit procedures (more specific minimum requirements)</li>
  <li>Added explicit counterfeit-parts provisions in response to the electronic counterfeit problem</li>
  <li>Updated cross-references to current FAA regulations (since the FARs had evolved since 1996)</li>
  <li>Clarified state of accreditation withdrawal and reinstatement procedures</li>
  <li>Updated definitions to align with current industry usage</li>
</ul>

<p>The AC will continue to evolve. Whenever a new revision is published, ASA reviews ASA-100 against the new revision and updates as needed. TurbineWorks then updates the QAM if ASA-100 changes affect operations. The cascade is automatic — TurbineWorks doesn\'t need to track the FAA directly, only ASA-100 revisions.</p>

<h4>How AC 00-56 connects to ASA-100</h4>

<p>The connection is structural, not numerical. ASA-100 is not a derivative of AC 00-56 — it\'s ASA\'s response to AC 00-56\'s framework. ASA chose its own structure, terminology, and section numbering. But every requirement in AC 00-56 §5 must be addressed somewhere in ASA-100.</p>

<p>The chain of authority looks like this:</p>

<pre>
FAA AC 00-56B
  └─ defines accreditation framework

ASA
  └─ FAA-recognized accreditation organization under AC 00-56
     └─ publishes ASA-100 as the standard it audits against
        └─ audits accredited distributors against ASA-100

ASA-100 Standard
  └─ defines specific quality-system requirements for distributors

TurbineWorks
  └─ accredited to ASA-100 (or pursuing accreditation)
     └─ publishes the QAM describing how TurbineWorks complies
        └─ supporting SOPs and work instructions
           └─ day-to-day employee work
</pre>

<p>At each level, the document or organization at one layer enables the layer below. AC 00-56 enables ASA. ASA enables ASA-100. ASA-100 enables the QAM. The QAM enables the SOPs. The SOPs enable employee work.</p>

<h4>Other accreditation programs under AC 00-56</h4>

<p>ASA-100 is not the only standard recognized under AC 00-56. Other accreditation programs the FAA recognizes:</p>

<h5>AS9120</h5>

<p>The aerospace-industry quality management system standard for distributors. Published by SAE International on behalf of the International Aerospace Quality Group (IAQG). Built on ISO 9001 with aerospace-specific additions. Often pursued <em>in addition to</em> ASA-100 by distributors selling into the OEM supply chain (Boeing tier-1/2, Airbus, etc.). Audited by IAQG-recognized registrars (BSI, DNV, NQA, SAI Global, LRQA, others).</p>

<p>AS9120 takes a different approach than ASA-100: process-based QMS structure derived from ISO 9001, with aerospace additions for risk management, configuration management, counterfeit prevention. Where ASA-100 is prescriptive (specific receiving inspection requirements, specific records to keep), AS9120 is more outcome-based (you must have a quality system that achieves these objectives; you describe how).</p>

<p>Many large distributors hold both ASA-100 and AS9120. The two are complementary — ASA-100 serves the FAA-recognized aftermarket; AS9120 serves the IAQG OEM-supply-chain market.</p>

<h5>TAC-2000</h5>

<p>Transportation Association of Canada equivalent. Used by Canadian-based distributors and some U.S. distributors selling into Canadian markets.</p>

<h5>Other recognized programs</h5>

<p>Appendix 1 of AC 00-56 lists all currently recognized AOs. The list changes over time as new programs gain recognition or existing programs lose it. Distributors and their customers should consult the current Appendix 1 directly for the authoritative list.</p>

<h4>What FAA recognition means in practice</h4>

<p>An FAA-recognized AO has a specific status:</p>

<ul>
  <li>The FAA accepts the AO\'s accreditation as evidence that an accredited distributor meets the AO\'s standard</li>
  <li>The FAA does not separately certify accredited distributors — it relies on the AO\'s audit work</li>
  <li>The FAA may use the AO\'s findings in its own enforcement work</li>
  <li>The FAA periodically reviews the AO to ensure it continues to meet AC 00-56 requirements</li>
  <li>Loss of FAA recognition would mean the AO\'s accreditations no longer have FAA standing — a serious consequence both for the AO and for its accredited members</li>
</ul>

<p>From TurbineWorks\' perspective, ASA recognition means: ASA accreditation is meaningful to customers, to the FAA, and to insurers. ASA derecognition would mean: TurbineWorks would need to migrate to another AO\'s program quickly to maintain market access.</p>

<h4>What this framework means for TurbineWorks employees</h4>

<p>The framework is the answer to the question every employee should be able to answer: "Why do I do this procedure?"</p>

<p>Working backward from the employee\'s daily work to the framework:</p>

<ul>
  <li>"I follow this procedure because the TurbineWorks QAM Section X.Y describes it"</li>
  <li>"The QAM requires this because ASA-100 §Z mandates it"</li>
  <li>"ASA-100 mandates it because AC 00-56 Section 5 requires distributors to address this element"</li>
  <li>"AC 00-56 requires it because the FAA determined that distributor controls in this area are necessary for supply-chain integrity"</li>
</ul>

<p>That chain is the answer. An employee who can articulate it demonstrates understanding of why their work matters. An employee who cannot articulate it may still perform the procedure correctly — but mechanically, without the resilience that understanding provides under pressure.</p>

<h4>Key takeaways</h4>

<ul>
  <li>AC 00-56 is the FAA-issued framework for industry-led distributor accreditation</li>
  <li>It is guidance (an Advisory Circular), not a regulation — voluntary but effectively required through market mechanisms</li>
  <li>ASA is one of several FAA-recognized accreditation organizations under AC 00-56</li>
  <li>ASA-100 is ASA\'s published standard — the criteria TurbineWorks must comply with</li>
  <li>The chain of authority runs: AC 00-56 → ASA → ASA-100 → TurbineWorks QAM → SOPs → daily employee work</li>
  <li>Other accreditation programs (AS9120, TAC-2000, others) are also recognized under AC 00-56</li>
  <li>The FAA monitors AOs themselves and can withdraw recognition if an AO\'s program becomes inadequate</li>
</ul>

<h4>Self-check</h4>

<ol>
  <li>What is the difference between a Federal Aviation Regulation and an Advisory Circular in terms of legal force?</li>
  <li>Why did the FAA choose voluntary industry accreditation over direct FAA regulation of distributors?</li>
  <li>What four major events in the 1980s-1990s motivated AC 00-56?</li>
  <li>What are the requirements an accreditation organization must meet to be FAA-recognized?</li>
  <li>What 11 distributor quality system elements must AC 00-56 §5 address?</li>
  <li>What is the relationship between AC 00-56, ASA, ASA-100, and the TurbineWorks QAM?</li>
  <li>Name three other accreditation programs recognized under AC 00-56 besides ASA-100.</li>
  <li>What does FAA recognition of an AO mean in practical terms?</li>
  <li>Why does every TurbineWorks employee need to understand this framework, not just QA staff?</li>
</ol>

<p><em>Continue to Lesson 3.3 — The Accreditation Process.</em></p>
HTML
            ],
            [
                'name' => 'Lesson 3.3 — The Accreditation Process',
                'intro' => '<p>End-to-end walkthrough of becoming and remaining ASA-100 accredited. Each phase in operational detail: application, document submission, on-site audit, findings, corrective action, accreditation grant, recurring audits, surveillance. Every employee needs this context because the audit interviews and observes employees at all levels.</p>',
                'content' => <<<'HTML'
<h3>The ASA-100 Accreditation Process</h3>

<p>Accreditation is not a one-time event. It\'s a continuing relationship between TurbineWorks and ASA that begins with the application, peaks at the on-site audit, continues through corrective action, settles into a steady state with recurring audits, and may be punctuated by surveillance audits in between. Every TurbineWorks employee is part of this process, whether or not they realize it.</p>

<p>This lesson walks through each phase in operational depth. The point is to demystify the audit — to make it clear what the auditor is doing, what they\'re looking for, what they\'ll ask employees, and what \'good\' looks like at each phase. Demystified, the audit is much less scary.</p>

<h4>Phase 1 — Application and document submission</h4>

<p>The accreditation journey begins with an application. TurbineWorks formally applies to ASA, providing:</p>

<ul>
  <li>Application form completed with TurbineWorks identification, scope of business, products handled, customer base</li>
  <li>The complete Quality Assurance Manual (QAM)</li>
  <li>All referenced procedures and work instructions</li>
  <li>Organizational chart showing reporting lines</li>
  <li>List of personnel by role (especially Accountable Manager, QA Manager, Receiving Inspectors)</li>
  <li>Facility information (location, square footage, storage zones)</li>
  <li>List of customers and major suppliers</li>
  <li>Any prior accreditation or audit history</li>
  <li>Initial accreditation fee</li>
</ul>

<p>ASA assigns a reviewer to evaluate the documentation. The reviewer checks:</p>

<ul>
  <li>QAM addresses every section of ASA-100 — no sections missing</li>
  <li>Procedures referenced in the QAM actually exist and are controlled (revision number, approval, effective date)</li>
  <li>Forms referenced in procedures exist and are controlled</li>
  <li>Training program is described and records exist demonstrating actual training delivery</li>
  <li>Records-retention policy meets the 7+ year baseline</li>
  <li>Roles and responsibilities are clearly assigned</li>
  <li>The organizational structure is consistent with the QAM</li>
</ul>

<h5>Common Phase 1 findings</h5>

<p>Document-stage findings the reviewer might raise (these would need to be addressed before the on-site audit is scheduled):</p>

<ul>
  <li>"QAM does not describe procedures for [specific ASA-100 element]"</li>
  <li>"Procedure X references Form Y, but Form Y is not provided and appears not to exist as a controlled document"</li>
  <li>"Training records show only management personnel; receiving inspectors have no documented training"</li>
  <li>"Records-retention schedule shows 3 years for receiving records; ASA-100 expects 7+ years"</li>
  <li>"Accountable Manager identified in QAM is not the same as on the organizational chart"</li>
</ul>

<p>If document review identifies gaps, TurbineWorks addresses them and resubmits. Only when document review is satisfactory does ASA schedule the on-site audit. The document review can take 4–8 weeks; with cycles of revisions it can extend longer.</p>

<h4>Phase 2 — On-site audit</h4>

<p>An ASA-qualified auditor visits TurbineWorks. For a typical small-to-medium distributor, the on-site audit takes 1–3 days. The auditor\'s objective: verify that what the QAM says is true — that procedures described on paper are actually being executed in practice.</p>

<h5>Audit opening</h5>

<p>The audit typically opens with an opening meeting attended by the Accountable Manager, QA Manager, and other key personnel. The auditor explains the scope, schedule, and approach. The auditor asks for any updates or changes since document submission.</p>

<p>Most auditors will provide an initial agenda — what they plan to look at on day 1, day 2, etc. The agenda is flexible; the auditor adjusts based on what they find.</p>

<h5>Personnel interviews</h5>

<p>The auditor interviews employees at multiple levels. Expected interview subjects:</p>

<ul>
  <li>Accountable Manager — corporate commitment to the quality system, resource allocation, management review</li>
  <li>QA Manager — every aspect of the QMS</li>
  <li>Receiving Inspectors — daily work, authority, independence, training</li>
  <li>Warehouse personnel — storage practices, segregation, handling procedures</li>
  <li>Shipping personnel — outbound documentation, customer-side compliance</li>
  <li>Sales/customer service — customer interactions, complaint handling</li>
  <li>IT (if applicable) — backup procedures, data security</li>
</ul>

<p>Interview questions are open-ended and probing. The auditor wants to understand whether employees know their role, whether they can articulate why they do what they do, and whether the system actually works under everyday operations.</p>

<p>Typical questions you might be asked as a receiving inspector:</p>

<ul>
  <li>"Walk me through what you do when a shipment arrives."</li>
  <li>"What\'s your authority to reject a part? Has anyone ever told you not to use it?"</li>
  <li>"What training have you had? When?"</li>
  <li>"Show me where the QAM describes your job."</li>
  <li>"Tell me about a quarantine decision you made. Walk me through it."</li>
  <li>"What do you do if the documentation doesn\'t match the part?"</li>
  <li>"How do you verify a Block 15 certificate number?"</li>
  <li>"Has anyone ever asked you to skip a procedure step? What did you do?"</li>
</ul>

<h5>Observation of operations</h5>

<p>The auditor watches actual receiving inspections in real time. They observe:</p>

<ul>
  <li>Does the inspector follow the documented procedure?</li>
  <li>Are required documents reviewed in the right sequence?</li>
  <li>Is the FAA database actually checked for Block 15 numbers?</li>
  <li>Is the receiving inspection record completed contemporaneously?</li>
  <li>Is the disposition decision actually made and recorded?</li>
</ul>

<p>The auditor doesn\'t interrupt during the observation — they take notes and may ask clarifying questions afterward.</p>

<h5>Records pull and audit</h5>

<p>The auditor pulls random parts from serviceable inventory and asks to see their complete receiving inspection records. The expectation: records retrievable within minutes, complete, signed by an authorized inspector.</p>

<p>The auditor may also pull recent quarantine and reject decisions, training records, internal audit records, corrective action records, supplier qualification records, shipping documents, customer complaint records. The pull is somewhat random — the auditor uses this technique to test whether the QMS actually applies across the operation, not just at one or two showpiece points.</p>

<h5>Facility walkthrough</h5>

<p>The auditor walks the facility:</p>

<ul>
  <li>Is the receiving area physically segregated from serviceable inventory?</li>
  <li>Is the quarantine area marked, accessible only to authorized personnel?</li>
  <li>Are the storage zones organized per the QAM?</li>
  <li>Is the ESD area properly grounded and equipped?</li>
  <li>Is hazmat storage separated and labeled?</li>
  <li>Are shelf-life items rotated FIFO?</li>
  <li>Is the facility FOD-controlled?</li>
  <li>Are housekeeping standards consistent with the QAM?</li>
</ul>

<h5>Closing meeting</h5>

<p>At the end of the on-site audit, the auditor convenes a closing meeting. Attended by the same group as the opening. The auditor presents preliminary findings — what they found that wasn\'t in conformance with ASA-100, and what they observed that was. The findings are sometimes given verbally with a written report following within days.</p>

<h4>Phase 3 — Findings and corrective action</h4>

<p>The audit report categorizes findings:</p>

<ul>
  <li><strong>Major Non-Conformance.</strong> A significant gap that compromises the integrity of the quality system. Examples: receiving inspectors are not actually checking Block 15 numbers; the QA Manager is not independent; quarantine area is not segregated. Major non-conformances must be corrected before accreditation is granted.</li>
  <li><strong>Minor Non-Conformance.</strong> A deviation from a specific requirement but isolated. Examples: a single record missing a signature; a single training file out of date; a single procedure not reflecting current practice. Minor non-conformances must be addressed but typically don\'t delay accreditation.</li>
  <li><strong>Observation.</strong> A practice that is technically compliant but where the auditor sees a risk or improvement opportunity. Not required to be addressed, but the distributor often responds to observations to strengthen the system.</li>
</ul>

<p>For each finding, TurbineWorks must:</p>

<ol>
  <li>Acknowledge the finding (typically by signing the audit report)</li>
  <li>Investigate the root cause</li>
  <li>Define a corrective action that addresses the root cause</li>
  <li>Implement the corrective action</li>
  <li>Verify effectiveness</li>
  <li>Submit evidence to the auditor</li>
  <li>Wait for auditor verification (sometimes remote, sometimes a follow-up visit)</li>
</ol>

<p>The corrective-action submission typically includes: revised procedures, training records showing personnel are now trained on the revised procedure, evidence of implementation (sample records, photographs, etc.), and a statement of effectiveness.</p>

<h4>Phase 4 — Accreditation granted</h4>

<p>Once all major non-conformances are closed and corrective actions verified, ASA issues the accreditation certificate. TurbineWorks is added to ASA\'s public accredited-distributor database. The certificate is good for a specified period (typically 3 years) subject to surveillance and re-audit.</p>

<p>The accreditation certificate is a visible, marketable asset:</p>

<ul>
  <li>Displayed at the TurbineWorks facility</li>
  <li>Referenced in customer communications</li>
  <li>Included in marketing materials</li>
  <li>Listed on the TurbineWorks website</li>
  <li>Verifiable via the public ASA database</li>
</ul>

<p>Customers verify accreditation through the ASA database before adding TurbineWorks to their approved-supplier list. The certificate alone is not sufficient — they confirm via the authoritative database.</p>

<h4>Phase 5 — Surveillance and recurring audits</h4>

<p>Accreditation is not permanent. ASA-100 accreditation includes:</p>

<h5>Surveillance audits</h5>

<p>ASA may conduct surveillance audits at any time during the accreditation period, particularly if:</p>

<ul>
  <li>Customer complaints have been filed</li>
  <li>FAA SUP referrals point at TurbineWorks</li>
  <li>Industry alerts identify pattern issues TurbineWorks might be involved in</li>
  <li>ASA\'s own pattern analysis suggests follow-up</li>
  <li>Random sampling under ASA\'s oversight program</li>
</ul>

<p>Surveillance audits are typically narrower than full audits — focused on specific concerns rather than the entire QMS. But they\'re unannounced or short-notice, so the QMS must be in a state of continuous readiness.</p>

<h5>Recurring full audit (every 3 years)</h5>

<p>The full audit cycle repeats, typically every 3 years. The recurring audit looks at:</p>

<ul>
  <li>Continued conformance to ASA-100 (now possibly a newer revision than the original accreditation)</li>
  <li>Closure and effectiveness of findings from the previous audit</li>
  <li>QMS evolution — has the distributor maintained the system or let it deteriorate?</li>
  <li>New activities, new customers, new suppliers, new part categories</li>
  <li>Surveillance audit follow-ups</li>
</ul>

<p>The recurring audit is conducted similarly to the initial audit but with the auditor\'s knowledge of the distributor\'s history. The auditor may probe areas where past findings occurred or where surveillance has raised concerns.</p>

<h4>What can go wrong — accreditation suspension or withdrawal</h4>

<p>Accreditation can be suspended or withdrawn for cause:</p>

<ul>
  <li>Failure to maintain ASA-100 compliance — major findings repeated, system deterioration</li>
  <li>Failure to complete corrective action on prior findings</li>
  <li>Critical findings such as: confirmed SUP that was not reported, falsified records, willful violations</li>
  <li>Substantiated customer complaints pointing to systemic quality failures</li>
  <li>FAA referral with findings against the distributor</li>
  <li>Failure to pay accreditation fees (rare cause but procedural)</li>
</ul>

<p>Suspension is typically temporary — the distributor has a defined period to address the cause, after which accreditation is reinstated or withdrawn. Withdrawal is more serious — the distributor must reapply, often after a defined cooling-off period, and undergo a full new audit.</p>

<p>Loss of accreditation has immediate market consequences (Module 6 covers in depth) — customers requiring accredited suppliers cannot continue buying. The financial impact is significant. The reputational impact is even greater.</p>

<h4>What every employee should know about audits</h4>

<p>Auditing is a structured but not adversarial process. The auditor is not trying to fail TurbineWorks — they\'re trying to objectively assess whether the QMS meets the standard. Findings are normal even at well-run distributors. The question is whether findings are minor and addressed quickly, or major and indicative of systemic issues.</p>

<h5>If the auditor interviews you</h5>

<ul>
  <li><strong>Be honest.</strong> "I don\'t know" is acceptable. Making up answers to seem competent is detectable and damaging.</li>
  <li><strong>Reference the QAM.</strong> "I follow Procedure X, which is in QAM Section Y" is the strongest answer when applicable.</li>
  <li><strong>Speak from experience.</strong> "Last week I had a quarantine situation where I..." is more compelling than abstract description.</li>
  <li><strong>Stick to what you know.</strong> If asked about something outside your role, say so and offer to find someone who can answer.</li>
  <li><strong>Don\'t volunteer problems.</strong> Answer what\'s asked, accurately. Auditors will probe; that\'s their job.</li>
  <li><strong>Don\'t criticize colleagues or management.</strong> If you have system concerns, raise them through the corrective-action system, not in an audit interview.</li>
</ul>

<h5>Common audit-interview mistakes</h5>

<ul>
  <li>Giving the answer you think the auditor wants rather than the truthful answer</li>
  <li>Inflating your role or knowledge</li>
  <li>Glossing over weaknesses ("everything works great")</li>
  <li>Disparaging the QMS in an unhelpful way</li>
  <li>Discussing the audit with other employees during breaks (some auditors check for this)</li>
</ul>

<h4>Your training is part of the audit</h4>

<p>The training records the auditor reviews include yours. Your completion of TurbineWorks University Modules 1–8, and your participation in recurring training, is evidence the company\'s training program is being executed. Skipping training or not completing courses you\'re enrolled in becomes visible during the audit.</p>

<p>This is part of the design. The auditor isn\'t just looking at whether the company has a training program on paper — they\'re looking at whether the program is being delivered, completed, and applied. Your training record is part of the audit-evidence portfolio.</p>

<h4>Key takeaways</h4>

<ul>
  <li>Accreditation is a 5-phase process: application, document review, on-site audit, findings/corrective action, accreditation grant, then ongoing surveillance and recurring re-audit</li>
  <li>The on-site audit looks at documents, observes operations, interviews personnel, examines records, walks the facility</li>
  <li>Findings are normal — even mature distributors get findings. What matters is response time and effectiveness</li>
  <li>Surveillance audits can happen any time; recurring full audits are every 3 years</li>
  <li>Accreditation can be suspended or withdrawn for cause — loss has serious market consequences</li>
  <li>Every employee may be interviewed; honesty and reference to the QAM are the right approach</li>
  <li>Training records (including TurbineWorks University completion) are part of audit evidence</li>
</ul>

<h4>Self-check</h4>

<ol>
  <li>What are the five phases of the ASA-100 accreditation process?</li>
  <li>What does ASA review during Phase 1 (document submission)?</li>
  <li>Name three things an auditor does during the on-site audit.</li>
  <li>What is the difference between Major, Minor, and Observation findings?</li>
  <li>What is the typical recurring audit cycle?</li>
  <li>What triggers a surveillance audit?</li>
  <li>What are five reasons accreditation can be suspended or withdrawn?</li>
  <li>How should you respond if an auditor interviews you?</li>
  <li>Why are training records part of the audit evidence?</li>
</ol>

<p><em>Continue to Lesson 3.4 — Roles, Responsibilities, and the QAM.</em></p>
HTML
            ],
            [
                'name' => 'Lesson 3.4 — Roles, Responsibilities, and the QAM',
                'intro' => '<p>Who at TurbineWorks is responsible for what under ASA-100, how those roles relate to each other, and how the Quality Assurance Manual ties the entire quality system together. The lesson that establishes the organizational backbone of the ASA-100 program.</p>',
                'content' => <<<'HTML'
<h3>Roles, Responsibilities, and the QAM</h3>

<p>ASA-100 requires that every accredited distributor define specific roles with specific responsibilities, document them in the Quality Assurance Manual, assign them to named individuals, and maintain consistency between what the QAM says and what those individuals actually do. The structure is the organizational backbone of the quality system. Without role clarity, accountability dissolves — and without accountability, the quality system fails regardless of how good the procedures look on paper.</p>

<p>This lesson covers each standard ASA-100 role in detail, the QAM as the document that ties them together, the document hierarchy that places the QAM in context, and what every employee needs to understand about role boundaries and authority.</p>

<h4>Why role clarity matters</h4>

<p>A quality system functions through specific people doing specific things. "TurbineWorks ensures quality" is meaningless. "The Receiving Inspector performs incoming inspection per Procedure TWP-06, with accept/quarantine/reject authority, and the QA Manager dispositions all quarantined material" is meaningful.</p>

<p>The ASA auditor verifies that:</p>

<ul>
  <li>Every role required by ASA-100 has a named person assigned to it in the QAM</li>
  <li>The named persons are actually performing the responsibilities the QAM assigns to them</li>
  <li>Anyone making quality decisions is named in the QAM as authorized to make those decisions</li>
  <li>Anyone NOT in a quality role is NOT making quality decisions (sales is not authorizing receiving inspector disposition reversals, for example)</li>
</ul>

<p>Role clarity also matters for the day-to-day functioning of the system. An employee who clearly understands their role acts decisively within it. An employee unclear on role boundaries either over-reaches (making decisions they shouldn\'t) or under-reaches (passing decisions up that they should make themselves).</p>

<h4>The standard ASA-100 roles</h4>

<h5>Accountable Manager</h5>

<p>The senior company official with corporate authority over the quality system. In small distributors this is the CEO, President, or General Manager. In larger organizations it may be a VP of Quality or a Chief Quality Officer.</p>

<p><strong>Responsibilities:</strong></p>

<ul>
  <li>Signs the QAM as the company\'s commitment to the quality system</li>
  <li>Has corporate authority to commit resources to maintain ASA-100 compliance (budget, headcount, facility, equipment)</li>
  <li>Receives reports from the QA Manager on QMS performance</li>
  <li>Conducts or participates in management review (formal QMS-effectiveness review at defined intervals)</li>
  <li>Ultimately responsible to ASA and the FAA for the company\'s accreditation</li>
  <li>Cannot delegate ultimate responsibility — though specific tasks are delegated to the QA Manager</li>
</ul>

<p>What the auditor checks at the Accountable Manager interview:</p>

<ul>
  <li>Does the Accountable Manager understand their role and responsibility?</li>
  <li>Can they articulate the quality policy and the company\'s commitment to ASA-100?</li>
  <li>Is the QA Manager able to access resources when needed (does the system actually function)?</li>
  <li>Does management review happen on schedule with meaningful content?</li>
  <li>Are there examples of resource commitments the Accountable Manager has made in support of the QMS?</li>
</ul>

<h5>Quality Assurance Manager</h5>

<p>Day-to-day owner of the quality system. The QA Manager is the operational head of the QMS — the person who maintains the QAM, manages internal audits, manages corrective action, owns the relationship with ASA, signs COCs, dispositions non-conforming material, and is accountable to the Accountable Manager for QMS performance.</p>

<p><strong>Responsibilities:</strong></p>

<ul>
  <li>Maintains the QAM and supporting procedures (revisions, distribution, document control)</li>
  <li>Schedules and conducts internal audits</li>
  <li>Investigates findings and manages corrective actions</li>
  <li>Reviews supplier qualifications and maintains the Approved-Supplier List</li>
  <li>Dispositions quarantined material after investigation</li>
  <li>Reviews and signs the TurbineWorks Certificate of Conformance on outbound shipments</li>
  <li>Liaises with ASA (audit coordination, accreditation maintenance)</li>
  <li>Handles customer complaints and customer audits</li>
  <li>Manages SUP investigations and FAA Form 8120-11 filings</li>
  <li>Owns the training program (or coordinates with HR/training function)</li>
  <li>Reports to the Accountable Manager on QMS performance</li>
</ul>

<p><strong>Critical authority:</strong> the QA Manager has independent authority to stop shipments. If the QA Manager determines that a shipment cannot be released for any quality reason, sales/operations/management cannot override the decision. This independence is a foundational ASA-100 principle (parallel to the Receiving Inspector\'s independence).</p>

<p>What the auditor checks at the QA Manager interview:</p>

<ul>
  <li>Walk through any recent corrective action and explain it</li>
  <li>Show me the internal audit schedule and the last audit\'s findings</li>
  <li>Has anyone ever overridden one of your decisions? If so, what happened?</li>
  <li>How do you maintain the QAM? Show me the last revision and explain the change</li>
  <li>How do you stay current on ASA-100 revisions and FAA updates?</li>
  <li>What\'s your most recent SUP investigation? Walk me through it.</li>
</ul>

<h5>Receiving Inspectors</h5>

<p>Front-line quality role. Receiving Inspectors are the people performing incoming-parts inspection per the QAM. The role and its authority are covered in detail in Module 2; this section emphasizes the organizational placement.</p>

<p><strong>Responsibilities:</strong></p>

<ul>
  <li>Performs receiving inspection per the documented procedure</li>
  <li>Verifies documentation (8130-3 and equivalents, COCs, packing lists, material certs)</li>
  <li>Performs physical inspection at the depth appropriate to part category</li>
  <li>Makes the accept/quarantine/reject disposition decision</li>
  <li>Creates and maintains receiving inspection records</li>
  <li>Tags parts appropriately (Serviceable, Quarantine, Reject)</li>
  <li>Escalates quarantine and reject decisions to the QA Manager same business day</li>
</ul>

<p><strong>Authority:</strong> independent decision authority on accept/quarantine/reject. Cannot be overridden by sales, operations, or management on quarantine decisions. The QA Manager can investigate and disposition after quarantine, but cannot prevent the initial quarantine.</p>

<h5>Warehouse / Storage personnel</h5>

<p>The people responsible for storing parts after receiving inspection and before shipping.</p>

<p><strong>Responsibilities:</strong></p>

<ul>
  <li>Maintains storage conditions per QAM and OEM requirements (ESD, hazmat, shelf-life, temperature, humidity, UV)</li>
  <li>Maintains the FOD-free environment (FOD walks, tool control, cleanliness)</li>
  <li>Maintains physical segregation of serviceable, quarantine, unserviceable, and scrap zones</li>
  <li>Executes FIFO rotation for shelf-life-limited items</li>
  <li>Maintains storage records (which part is in which location)</li>
  <li>Identifies storage anomalies (environmental excursions, damage discoveries) and reports to QA</li>
</ul>

<p>Warehouse personnel are not authorized to make quality dispositions. They handle parts that have already been dispositioned (serviceable or quarantine) without changing the disposition. Moving a part out of quarantine requires QA Manager authorization, not warehouse-level decision.</p>

<h5>Shipping personnel</h5>

<p>Responsible for preparing and dispatching outbound shipments per Module 2 Lesson 2.6.</p>

<p><strong>Responsibilities:</strong></p>

<ul>
  <li>Verifies the parts pulled for shipping match the customer PO</li>
  <li>Ensures documentation accompanies the shipment (8130-3, TurbineWorks COC, customer-specific documents)</li>
  <li>Packages per requirements (ATA Spec 300, ESD, hazmat as applicable)</li>
  <li>Labels and marks per regulation and customer requirements</li>
  <li>Performs final shipping inspection</li>
  <li>Maintains shipping records</li>
  <li>Handles hazmat shipping documentation if qualified (some hazmat air shipments require specifically-trained shipping personnel)</li>
</ul>

<h5>Sales and customer service</h5>

<p>Often the first to interact with customers and the first to hear of customer concerns.</p>

<p><strong>Responsibilities:</strong></p>

<ul>
  <li>Communicates with customers about products, availability, lead times, status</li>
  <li>Creates quotes and processes orders</li>
  <li>Coordinates with QA Manager on parts condition disclosure (Module Customer Relations)</li>
  <li>Routes customer complaints into the corrective-action system (not just informal email handling)</li>
  <li>Cannot make quality decisions or override QA decisions</li>
  <li>Cannot promise parts to customers without QA confirmation of availability and condition</li>
</ul>

<p>The boundary between sales and QA is critical. Sales pressure on quality decisions is the most common pathway by which quality systems erode. ASA auditors specifically probe this boundary during interviews.</p>

<h5>Other roles as applicable</h5>

<p>Larger distributors may have additional formally-defined roles:</p>

<ul>
  <li><strong>Document Control Coordinator</strong> — manages the document control system on behalf of the QA Manager</li>
  <li><strong>Training Coordinator</strong> — administers TurbineWorks University and tracks training compliance</li>
  <li><strong>Internal Auditor(s)</strong> — performs internal audits separately from the QA Manager (sometimes external contractors)</li>
  <li><strong>Hazmat Coordinator</strong> — qualified for hazmat shipping documentation per DOT/IATA</li>
  <li><strong>Export Compliance Officer / Empowered Official</strong> — qualified for ITAR/EAR export determinations</li>
  <li><strong>IT / Cybersecurity</strong> — for records system integrity and security</li>
</ul>

<p>These roles may overlap with the core roles in small organizations (the QA Manager often plays Document Control, Training, and Internal Auditor in addition to QA Manager). The QAM documents the actual structure regardless of the formal role labels.</p>

<h4>The Quality Assurance Manual (QAM)</h4>

<p>The QAM is the single document that ties everything together. The auditor treats the QAM as the authoritative description of TurbineWorks\' quality system. Anything observed during the audit that contradicts the QAM is a finding — even if what\'s being done is "better" than what the QAM says.</p>

<h5>What the QAM contains</h5>

<ul>
  <li><strong>Company overview.</strong> What TurbineWorks does, scope of accreditation (what parts, what services, what customer types).</li>
  <li><strong>Quality policy.</strong> Statement of management commitment to ASA-100 compliance, signed by the Accountable Manager.</li>
  <li><strong>Organizational chart.</strong> Reporting lines for quality decisions. Shows the Accountable Manager → QA Manager → Receiving Inspectors / Warehouse / Shipping chain. Sales is shown as a separate line.</li>
  <li><strong>Role definitions.</strong> Each role from Accountable Manager down. Named individuals (sometimes by position with a separate current-personnel roster).</li>
  <li><strong>Procedures.</strong> Detailed how-to for each ASA-100 element: receiving inspection, storage, shipping, recordkeeping, internal audit, corrective action, supplier qualification, training, document control, customer complaint handling.</li>
  <li><strong>Forms referenced.</strong> Each TWF-XX form referenced in procedures is included or referenced in the QAM.</li>
  <li><strong>Cross-reference to ASA-100.</strong> Each section of the QAM identifies which ASA-100 section it implements.</li>
  <li><strong>Records-retention schedule.</strong> What\'s kept, for how long.</li>
  <li><strong>Document control rules.</strong> How revisions happen, how they\'re approved, distributed, and retrieved.</li>
  <li><strong>Revision history.</strong> Past revisions of the QAM with summary of changes.</li>
</ul>

<h5>QAM structure variations</h5>

<p>Different distributors organize their QAM differently. Common approaches:</p>

<ul>
  <li><strong>ASA-100-mirroring.</strong> The QAM follows the ASA-100 section structure section by section. Easiest for auditors to navigate but sometimes awkward for daily operational reference.</li>
  <li><strong>Process-based.</strong> The QAM is organized by major business process (receiving, storage, shipping, customer interaction, internal QMS). Easier for employees to find their section but requires a cross-reference matrix to ASA-100.</li>
  <li><strong>Hybrid.</strong> Process-based at the top level with explicit ASA-100 cross-references in each section.</li>
</ul>

<p>The QAM is unambiguously controlled — single owner (QA Manager), formal revision process, distribution list, retrieval of obsolete copies. Uncontrolled copies are a finding.</p>

<h4>The document hierarchy</h4>

<p>The QAM doesn\'t exist in isolation. It sits within a hierarchy of documents that govern operations:</p>

<pre>
14 CFR (Federal Aviation Regulations)
  └─ The law. Wins all conflicts.

FAA Advisory Circulars
  └─ Authoritative FAA interpretation and guidance.

ASA-100 Standard
  └─ Industry standard TurbineWorks is accredited to.

TurbineWorks Quality Assurance Manual (QAM)
  └─ How TurbineWorks complies with ASA-100.

Standard Operating Procedures (TWP-XX)
  └─ Process-level procedures referenced by the QAM.

Work Instructions
  └─ Task-level step-by-step for specific activities.

Forms (TWF-XX)
  └─ Records templates capturing evidence of work performed.

Records
  └─ Filled-in forms and other artifacts proving the work was done.
</pre>

<h5>Conflict resolution rule</h5>

<p>When two documents conflict, the higher level wins. If the QAM says "do X" and an SOP says "do Y," the QAM wins and the SOP must be corrected. If ASA-100 conflicts with the QAM, ASA-100 wins and the QAM must be corrected. If a FAR conflicts with ASA-100, the FAR wins.</p>

<p>This rule is not just abstract — it determines what happens when a procedural question arises in operations. The employee checks the lower-level document first (the work instruction or SOP) for specific guidance. If the guidance is unclear or contradicted by higher-level doc, the employee escalates.</p>

<h4>What every employee needs to know about their role</h4>

<h5>Know your role title in the QAM</h5>

<p>If you are a "Receiving Inspector" per the QAM, the auditor will hold you accountable for what the QAM says Receiving Inspectors do. If you are a "Warehouse Technician," you are accountable for those responsibilities. If you do work outside your QAM role, document the basis (QA Manager-authorized exception, temporary assignment, etc.).</p>

<h5>Know where the QAM lives and how to find your section</h5>

<p>The QAM is typically accessible electronically (current revision) at a documented location. Bound copies may exist at specific stations. Every employee should know how to access the QAM and find the section describing their role.</p>

<p>An auditor will sometimes ask: "Show me where the QAM describes your job." A response of "I\'ve never looked at the QAM" or "I\'m not sure where it is" is a finding by itself.</p>

<h5>Don\'t make quality decisions outside your role</h5>

<p>If you are not the QA Manager, you do not approve non-conforming parts for use. You do not release a quarantined part. You do not waive a procedural requirement. You do not make supplier qualification decisions. These are QA Manager authorities.</p>

<p>Your role authority is what the QAM says it is — no more. Stretching beyond your authority is itself a quality-system failure.</p>

<h5>Raise conflicts through the system</h5>

<p>If you notice a conflict between the QAM and what\'s actually happening — or a procedure that doesn\'t work in practice, or a form that\'s incomplete, or a rule that doesn\'t match reality — the right response is to raise it through the corrective-action system. Open an NCR, talk to the QA Manager, propose a change.</p>

<p>Quality systems improve through systematic feedback. Quietly working around a broken procedure is the worst response — it hides the problem and lets the system drift further from reality.</p>

<h4>The auditor\'s view of roles</h4>

<p>An auditor probes role clarity at every interview:</p>

<ul>
  <li>"What\'s your job title here?"</li>
  <li>"Show me where that role is described in the QAM."</li>
  <li>"What are your responsibilities under that role?"</li>
  <li>"What decisions are you authorized to make?"</li>
  <li>"What decisions go to the QA Manager?"</li>
  <li>"What training does your role require?"</li>
  <li>"How is your role training documented?"</li>
</ul>

<p>An employee who can answer these clearly demonstrates that the role structure is meaningful, not just paper. An employee who can\'t demonstrates a role-clarity gap.</p>

<h4>Key takeaways</h4>

<ul>
  <li>ASA-100 requires specific roles with specific responsibilities, documented in the QAM</li>
  <li>The Accountable Manager has corporate responsibility; the QA Manager has day-to-day operational responsibility</li>
  <li>The QA Manager has independent authority to stop shipments — cannot be overridden by sales or management</li>
  <li>Receiving Inspectors have independent authority on accept/quarantine/reject decisions</li>
  <li>Warehouse, Shipping, Sales each have defined responsibilities and boundaries</li>
  <li>The QAM ties everything together — anything that contradicts the QAM is a finding</li>
  <li>Document hierarchy: FARs → ACs → ASA-100 → QAM → SOPs → Work Instructions → Forms → Records</li>
  <li>Higher level wins in conflict</li>
  <li>Every employee needs to know their role, where the QAM describes it, and what authority they have</li>
</ul>

<h4>Self-check</h4>

<ol>
  <li>What is the Accountable Manager\'s role and what specific responsibilities are theirs alone?</li>
  <li>What independent authority does the QA Manager have, and why is it independent?</li>
  <li>What are the responsibilities of a Receiving Inspector?</li>
  <li>What can warehouse personnel NOT do (what decisions are not theirs)?</li>
  <li>Why is the sales/QA boundary particularly important to the audit?</li>
  <li>What 10 elements does a typical QAM contain?</li>
  <li>Draw or describe the document hierarchy from FARs to records.</li>
  <li>What is the conflict-resolution rule when two documents disagree?</li>
  <li>What should every employee know about their role, and where do they find it?</li>
</ol>

<p><em>[TurbineWorks Procedure Reference: insert link to the current TurbineWorks QAM and the organizational chart here once the QAM is published in the document control system.]</em></p>
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
            'intro' => '<p>The three categories of bad parts and why the legal distinctions matter. Foundational vocabulary for every other lesson in this module — and for every receiving inspection you will ever perform at TurbineWorks.</p>',
            'content' => <<<'HTML'
<h3>The Three Categories of "Bad" Parts</h3>

<p>Before you can prevent unapproved parts from entering TurbineWorks inventory, you have to know exactly what an unapproved part is — and how it differs from the related but distinct categories of <em>suspected unapproved</em> and <em>counterfeit</em>. These three terms get used interchangeably in everyday conversation. They are not interchangeable in FAA regulation, in ASA-100 procedure, or in court. Using them imprecisely is itself a quality-system finding.</p>

<p>This lesson establishes the precise definitions the FAA uses, the differences in legal consequence, and the practical implications for what a TurbineWorks receiving inspector does when they encounter each category. Every other lesson in Module 1 — and most lessons in Modules 2, 4, and 5 — rests on this vocabulary.</p>

<h4>Why the distinction was created</h4>

<p>The FAA introduced the formal taxonomy of unapproved / suspected unapproved / counterfeit in the 1990s in response to a wave of high-profile incidents:</p>

<ul>
  <li><strong>The Partnair Convair 580 crash (1989)</strong>, where investigators found that bogus bolts had been installed in the tail control system, contributing to in-flight breakup. Investigation traced the parts through multiple distributors back to an unauthorized manufacturer.</li>
  <li><strong>NTSB findings through the 1990s</strong> documenting repeated cases of misrepresented parts entering commercial fleets through the open broker market.</li>
  <li><strong>The 1995 FAA Suspected Unapproved Parts program</strong>, establishing FAA Form 8120-11 reporting and the SUP investigation framework.</li>
</ul>

<p>Before the formal taxonomy, "bad part" was the operative term and it covered everything from a part with no paperwork to a deliberately fabricated counterfeit. The categories distinguished them because each requires different actions: a part you suspect needs to be investigated; a part you have confirmed needs to be removed from service; a deliberately misrepresented part triggers fraud-investigation procedures that may involve criminal authorities. Lumping all three together under "bad part" causes practical failures — you either over-react and treat every paperwork question as a federal crime, or you under-react and treat clear fraud as administrative.</p>

<h4>Category 1 — Unapproved Part</h4>

<p><strong>Legal source:</strong> <code>14 CFR §21.502</code> defines unapproved parts. The same regulation is summarized at length in <strong>FAA Advisory Circular 21-29D</strong>, Section 3.</p>

<p>The CFR definition (paraphrased for clarity): an unapproved part is one of the following:</p>

<ol>
  <li>A part that does not conform to an approved type design, OR</li>
  <li>A part that is not in a condition for safe operation, OR</li>
  <li>A part that was not produced under an approved production system (no PMA, TSO, or production certificate authority), AND that does not fit one of the recognized exceptions (e.g., owner-produced parts under 14 CFR §43.13).</li>
</ol>

<p>"Unapproved" is a <strong>definitive</strong> status. Calling a part unapproved means TurbineWorks (or another party with authority) has reached a conclusion based on evidence. The evidence might be:</p>

<ul>
  <li>Metallurgical analysis showing the part is made from the wrong alloy</li>
  <li>Dimensional inspection showing the part doesn't match the approved drawing</li>
  <li>OEM confirmation that the part was not produced by them and the supposed source has no production authority</li>
  <li>Documentation analysis confirming that the 8130-3 was fabricated and the supposed issuing organization has no record of it</li>
  <li>FAA SUP investigation conclusion declaring the part unapproved</li>
</ul>

<p>Concrete examples of parts that have been confirmed unapproved in real industry cases:</p>

<ul>
  <li><strong>Wrong-alloy turbine blade.</strong> A blade marked with the correct OEM part number and serial number but produced from a substitute alloy with inferior high-temperature properties. Discovered through routine metallurgical sampling at a customer MRO. The part conforms dimensionally but fails the type-design requirement of correct material.</li>
  <li><strong>Unauthorized manufacturer bearing.</strong> A bearing produced by a job shop with no PMA or TSO authority, marked with the OEM\'s identifying numbers to make it look authorized. The bearing might even meet dimensional spec and might function adequately for many hours — but it was not produced under any FAA-approved production system, so by regulation it cannot be installed on a type-certificated aircraft.</li>
  <li><strong>Reactivated scrap.</strong> A turbine disk that an operator scrapped because it had reached its cycle limit. The disk should have been mutilated per FAA AC 21-38 so it could never re-enter the supply chain. Instead, someone in the disposal chain diverted it, re-cleaned and re-marked it with a different serial number, and sold it. The part is in a "condition for safe operation" only if you ignore the unknown cycle accumulation — but the documentation trail is fabricated and the part is unapproved.</li>
  <li><strong>Repair beyond authority.</strong> A blade that an unauthorized facility welded and machined to repair a damaged airfoil. The work was performed outside any OEM-approved repair authority. The part is now a different part than what the OEM designed and certified. Unapproved.</li>
</ul>

<p>A key point in all of these examples: the part may be <em>physically indistinguishable</em> from an approved part. You cannot determine unapproved status just by looking. The determination is about provenance, conformance, and authority — and it requires evidence to support.</p>

<h4>Category 2 — Suspected Unapproved Part (SUP)</h4>

<p><strong>Legal source:</strong> FAA AC 21-29D defines the SUP investigation framework. SUP status is regulatory shorthand for "we have a reasonable basis to suspect this part may be unapproved, but we have not concluded the investigation."</p>

<p>The threshold for treating a part as a SUP is intentionally low. The standard from AC 21-29D is "reasonable basis to believe." This is not "more likely than not" and certainly not "beyond a reasonable doubt." It is a much lower bar — the standard that ordinary, prudent care would use to flag a part for further investigation.</p>

<p>Why is the threshold so low? Because the cost asymmetry is enormous:</p>

<ul>
  <li><strong>Cost of flagging a part as SUP that turns out to be fine:</strong> a few days of investigation, a paperwork file, possibly an apologetic note to a supplier. Maybe $500 in process cost. Reversible.</li>
  <li><strong>Cost of failing to flag a part that turns out to be unapproved:</strong> the part may be installed on a customer\'s aircraft, may cause in-service failure, may injure or kill people, may trigger FAA enforcement action against TurbineWorks, may result in revocation of accreditation. Catastrophic. Possibly irreversible.</li>
</ul>

<p>The asymmetry justifies a hair-trigger. If you see something that doesn\'t look right, you flag it. The investigation either confirms it as a real problem, or it confirms the part is fine and you release it. Either outcome is acceptable. What is <em>not</em> acceptable is failing to investigate something that warranted suspicion.</p>

<p>The triggers that should put a TurbineWorks receiving inspector into SUP mode include any of the following (this is not exhaustive — anything that strikes you as off justifies the call):</p>

<ul>
  <li><strong>Documentation that fails the Block 15 lookup test.</strong> Every FAA 8130-3 has a certificate number in Block 15 identifying the issuing organization. The FAA maintains a public database of all certificate holders at <a href="https://av-info.faa.gov/" target="_blank" rel="noopener">av-info.faa.gov</a>. Every legitimate Block 15 number is in that database. A Block 15 number that is not in the database is, in essentially every case, fabricated.</li>

  <li><strong>Documentation with a photocopied signature.</strong> The Block 14 signature on an 8130-3 is supposed to be an original wet-ink signature or a verifiable electronic signature with audit trail. A photocopied signature is almost always evidence that the form has been duplicated or altered. (There are narrow exceptions where a "TRUE COPY" attestation from an authorized custodian explains the photocopy, but in those cases the attestation itself is on the form.)</li>

  <li><strong>Serial number mismatch between the part and the documentation.</strong> Block 10 of the 8130-3 lists the serial number. The part itself has the serial number stamped or etched on a data plate or directly on the part. The two must match exactly — including every character and including any letter prefixes. A single transposed digit is a SUP indicator.</li>

  <li><strong>Markings on the part that appear to have been altered.</strong> Examples: a stamped part number with telltale grinding marks underneath suggesting a previous mark was removed; a stamping font that doesn\'t match the OEM\'s known stamping pattern; characters of inconsistent depth suggesting hand-stamped re-marking over factory-original marking.</li>

  <li><strong>Source supplier not on the TurbineWorks approved-supplier list.</strong> The approved-supplier list is the result of supplier qualification — TurbineWorks has confirmed that this supplier operates a quality system, has procedures for proper documentation, and has a track record. A shipment from an unknown supplier may be perfectly legitimate, or it may be the entry point of a SUP. Either way, the receiving inspection cannot proceed normally until the supplier is qualified or the QA Manager has authorized one-time receipt.</li>

  <li><strong>Pricing that is anomalously low.</strong> If a part type that normally trades at $40,000 is being offered at $12,000, there is a reason. Sometimes it is a legitimate distressed-inventory sale. Sometimes it is the economic signature of a counterfeit or stolen part — the supplier can offer below market because they didn\'t pay market for the inputs. Anomalous pricing alone is not sufficient evidence to declare SUP status, but combined with any other indicator it is a strong signal.</li>

  <li><strong>Documentation that doesn\'t match the part type.</strong> An 8130-3 referencing a fuel-control unit attached to a bearing — clearly the tag was applied to the wrong part, or the tag has been deliberately matched to a different part. Either way, investigate.</li>
</ul>

<p>What changes when a part is in SUP status:</p>

<ul>
  <li>The part moves immediately to <strong>Quarantine</strong> — a physically segregated holding area that prevents the part from entering serviceable inventory.</li>
  <li>The part may NOT be shipped to any customer.</li>
  <li>The part may NOT be returned to the supplier without QA Manager authorization. Returning a SUP without investigation just transfers the SUP to the next distributor in the chain and conceals the evidence.</li>
  <li>All accompanying documentation is preserved in its original form. Do not annotate the documents, do not stamp them, do not photocopy and discard the originals. The originals are evidence.</li>
  <li>The QA Manager is notified the same business day.</li>
  <li>A Non-Conformance Report is opened in the corrective-action system.</li>
</ul>

<h4>Category 3 — Counterfeit Part</h4>

<p><strong>Legal source:</strong> There is no single CFR section defining counterfeit aviation parts the way 14 CFR §21.502 defines unapproved parts. The term is used in industry standards (SAE AS5553, SAE AS6174) and in 41 U.S.C. §4109 (counterfeit electronic parts in defense contracts). The distinguishing feature, in every relevant definition, is <strong>deliberate misrepresentation</strong>.</p>

<p>An unapproved part may have entered the supply chain by accident, mistake, or negligence. A counterfeit part requires that someone, somewhere along the chain, deliberately misrepresented the part — usually misrepresented its source as being an approved manufacturer when it was not. Counterfeiting is fraud. It is investigated and prosecuted as fraud by the FBI, DOD inspectors general, and other federal authorities.</p>

<p>Practical implication: in receiving inspection, you may identify a SUP. You investigate. The investigation may conclude:</p>

<ul>
  <li><strong>"This part is fine, the suspicion was a false alarm."</strong> Part returns to serviceable.</li>
  <li><strong>"This part is unapproved — the supplier never had authority to produce it."</strong> The part is unapproved. Mutilation per AC 21-38, FAA notification via Form 8120-11.</li>
  <li><strong>"This part is counterfeit — someone deliberately misrepresented its source."</strong> All of the above PLUS the matter is referred for potential criminal investigation. The FBI may take possession of the part. The supplier may be referred for prosecution. Other affected customers may need urgent notification.</li>
</ul>

<p>The aerospace counterfeit problem has intensified in two distinct waves:</p>

<ol>
  <li><strong>Mechanical part counterfeiting (1980s-2000s).</strong> Job shops producing parts to OEM dimensions without authority, then selling them with fabricated documentation through broker chains. Mostly targeted high-value commodity items: bolts, bearings, brackets. The Partnair Convair 580 case is the canonical example.</li>
  <li><strong>Electronic counterfeit (2000s-present).</strong> The global outsourcing of electronics manufacturing created opportunities for counterfeit chips, capacitors, and assemblies. SAE AS5553 was published in 2009 in direct response. AS6174 followed for broader materiel. DoD has tightened counterfeit-prevention requirements through DFARS clauses 252.246-7007/7008.</li>
</ol>

<h4>Side-by-side comparison</h4>

<table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse;">
  <tr style="background:#0d2240; color:#fff;">
    <th>Dimension</th><th>Unapproved</th><th>SUP</th><th>Counterfeit</th>
  </tr>
  <tr>
    <td>Status</td>
    <td>Confirmed by evidence</td>
    <td>Suspected, under investigation</td>
    <td>Confirmed AND deliberate misrepresentation</td>
  </tr>
  <tr>
    <td>Threshold</td>
    <td>"Determined" — evidence supports conclusion</td>
    <td>"Reasonable basis to suspect" — intentionally low</td>
    <td>"Beyond reasonable doubt" if criminal prosecution</td>
  </tr>
  <tr>
    <td>Disposition</td>
    <td>Mutilate per AC 21-38</td>
    <td>Quarantine pending investigation</td>
    <td>Preserve as evidence; coordinate with FBI/FAA</td>
  </tr>
  <tr>
    <td>FAA reporting</td>
    <td>Form 8120-11</td>
    <td>Form 8120-11 when reasonable basis confirmed</td>
    <td>Form 8120-11 plus criminal referral</td>
  </tr>
  <tr>
    <td>Civil/criminal</td>
    <td>Typically administrative</td>
    <td>Typically administrative (pending outcome)</td>
    <td>Fraud — civil and criminal exposure</td>
  </tr>
  <tr>
    <td>Required intent</td>
    <td>None — accidental possible</td>
    <td>None — based on indicators</td>
    <td>Required — deliberate misrepresentation</td>
  </tr>
</table>

<h4>The most common practical question: when should a TurbineWorks inspector use which term?</h4>

<p>The default answer for a receiving inspector is <strong>SUP</strong>. The inspector identifies indicators of possible non-conformance. The inspector does not have the evidence base, the authority, or typically the technical training to declare a part definitively unapproved or counterfeit. That determination requires investigation, often outside expertise, and the QA Manager\'s judgment.</p>

<p>Practical phrasing for the receiving inspector:</p>

<ul>
  <li><em>What to say:</em> "I\'m flagging this part as SUP because the Block 15 certificate number is not in the FAA database."</li>
  <li><em>What NOT to say:</em> "This is counterfeit." (Premature determination; counterfeit is a fraud finding that requires evidence of intent.)</li>
  <li><em>What NOT to say:</em> "This is unapproved." (Premature determination; unapproved status requires investigation conclusion.)</li>
</ul>

<p>The QA Manager, after investigation, makes the determination of unapproved status. The FAA or law enforcement, after their own investigation, makes the determination of counterfeit. The receiving inspector\'s contribution to the entire workflow is the initial identification of an indicator that justified the SUP flag.</p>

<h4>Common misconceptions corrected</h4>

<dl>
  <dt><strong>"A part with no 8130-3 is automatically unapproved."</strong></dt>
  <dd>Not necessarily. Some parts (raw materials, standard hardware, consumables under threshold) don\'t require 8130-3. Some parts may be in transit with the tag arriving separately. Missing documentation is a hold condition, not automatic unapproved status.</dd>

  <dt><strong>"If a part works in service, it must not be unapproved."</strong></dt>
  <dd>Unapproved status is about conformance to approved design and production authority, not about whether the part happens to function. A counterfeit bearing may run for thousands of hours before failing — it is still unapproved.</dd>

  <dt><strong>"All cheap parts are suspect."</strong></dt>
  <dd>Anomalously low pricing is one indicator. Some legitimate parts are genuinely cheap: liquidation sales, surplus dispositions, supplier inventory reductions. Price alone is insufficient. Price combined with other indicators is significant.</dd>

  <dt><strong>"Counterfeit only affects electronics."</strong></dt>
  <dd>Electronics counterfeit gets more news attention because of DFARS focus and high visibility. Mechanical counterfeit has been a persistent industry problem since at least the 1980s. Both happen. Both matter at TurbineWorks.</dd>

  <dt><strong>"If we caught it at receiving, no harm done."</strong></dt>
  <dd>Catching SUP at receiving is the goal. But the investigation is what closes the loop — identifying the source supplier, removing them from the approved list if confirmed, notifying other distributors who may have received parts from the same source, and reporting to the FAA so the broader supply chain knows.</dd>

  <dt><strong>"The FAA will investigate any SUP we file."</strong></dt>
  <dd>The FAA prioritizes SUP filings based on safety severity and pattern indicators. A single isolated SUP filing may receive limited FAA attention. A pattern of filings from multiple distributors pointing to the same supplier or part type triggers escalation. Either way, TurbineWorks files. The filing itself is the obligation.</dd>
</dl>

<h4>Self-check</h4>

<p>Before moving to Lesson 1.2, you should be able to answer the following without referring back:</p>

<ol>
  <li>What is the precise difference between "unapproved" and "suspected unapproved"? What burden of proof does each require?</li>
  <li>Which CFR section defines unapproved parts?</li>
  <li>What makes a part "counterfeit" as distinct from merely "unapproved"?</li>
  <li>If a Block 15 certificate number doesn\'t appear in the FAA database, what is the correct receiving inspector action?</li>
  <li>Why is returning a SUP to the supplier without investigation incorrect?</li>
  <li>What term should the receiving inspector use when flagging a part with suspect documentation: "unapproved," "SUP," or "counterfeit"?</li>
  <li>Why is the SUP threshold ("reasonable basis to suspect") intentionally low?</li>
  <li>Name three indicators on an 8130-3 that should trigger SUP status.</li>
</ol>

<p>If any of those is unclear, re-read the corresponding section above before proceeding. The remaining lessons in Module 1 assume mastery of this vocabulary.</p>
HTML
        ],
        [
            'name'  => 'Lesson 1.2 — How SUP Enters the Supply Chain',
            'intro' => '<p>The recurring pathways unapproved parts use to reach distributors. Why understanding the pathway matters more than understanding the part: the pathway tells you where the defense has to live, and where TurbineWorks fits in the chain.</p>',
            'content' => <<<'HTML'
<h3>How Unapproved Parts Reach the Supply Chain</h3>

<p>Lesson 1.1 established what unapproved, SUP, and counterfeit parts <em>are</em>. This lesson covers how they <em>get into the supply chain</em>. The distinction matters because preventing a SUP from entering TurbineWorks inventory requires understanding the pathway — where the part came from, what failure of controls allowed it to reach you, and what defenses interrupt that pathway.</p>

<p>There are six recurring pathways. The first five are the historical industry pattern. The sixth is emerging and increasingly important. Every receiving-inspection control TurbineWorks operates is, ultimately, defense against one or more of these pathways.</p>

<h4>Why understanding pathways is operationally critical</h4>

<p>It is tempting to think of SUP defense as a series of checks performed on an arriving part: verify the 8130-3, inspect the part, validate the supplier. That checklist works. But it works <em>better</em> if the inspector understands why each check exists. A check performed without understanding becomes mechanical — easy to skip when time pressure rises, and easy to satisfy with the appearance of compliance rather than actual scrutiny.</p>

<p>An inspector who understands that scrap diversion is the largest single SUP pathway will treat parts from unfamiliar brokers with particular skepticism, because broker chains are the natural delivery mechanism for diverted scrap. An inspector who understands that documentation fraud is the easiest form of SUP to attempt will verify Block 15 against the FAA database without exception, because that single check breaks the most common fraud pattern. Operational understanding is force-multiplied by pathway awareness.</p>

<h4>Pathway 1 — Scrap diversion</h4>

<p>The single most common pathway. The mechanics:</p>

<ol>
  <li>An operator or MRO determines that a part has reached the end of its useful life. The part may have reached its hard-time cycle limit (LLPs), may have failed inspection beyond economical repair, may have been damaged in service. Whatever the cause, the operator decides the part will not be installed on another aircraft.</li>
  <li>The part is supposed to be <strong>mutilated</strong> — physically destroyed per FAA AC 21-38 — so it cannot re-enter the supply chain. Mutilation might involve cutting the part into pieces, drilling holes in critical surfaces, removing and destroying the data plate, or other irreversible damage.</li>
  <li>The part is supposed to be <strong>documented as destroyed</strong> — a mutilation record retained in the operator/MRO\'s quality system.</li>
  <li>Instead, somewhere between the scrap-decision and the mutilation, the part is <strong>diverted</strong>. Someone in the disposal chain takes the part out of the scrap bin before it is destroyed. They re-clean it, possibly re-mark it with a different serial number, and reintroduce it into the supply chain — often through a broker who buys "surplus" or "salvage" inventory.</li>
  <li>The diverted part is then sold, typically through one or more intermediary brokers, eventually reaching a distributor who may not realize the part should not be in service.</li>
</ol>

<p><strong>Why scrap diversion is the largest pathway:</strong> the volume is enormous. Every year, thousands of aviation parts are scrapped — turbine disks reaching life limits, blades damaged by FOD events, bearings replaced as preventive maintenance, sensors with cumulative drift. Each scrapped part is a candidate for diversion. The economic incentive is large: a scrapped turbine disk might sell on the gray market for $5,000-$15,000 even though it has no remaining service life. Multiply by the number of operators and the number of parts and the financial opportunity is significant.</p>

<p><strong>Industry case:</strong> in the 1990s and 2000s, multiple FAA SUP investigations traced fraudulent turbine blades and disks back to operators who failed to enforce mutilation. The parts were sold through broker chains across multiple distributors before being identified — often only after in-service incidents. Some of these cases reached criminal prosecution.</p>

<p><strong>TurbineWorks defense against scrap diversion:</strong></p>

<ul>
  <li><strong>Approved-supplier qualification.</strong> The first line of defense is who TurbineWorks buys from. Suppliers on the approved list have been qualified — facility verified, references checked, history examined. Suppliers not on the list go through qualification before any parts are accepted.</li>
  <li><strong>Provenance documentation.</strong> The 8130-3 chain should trace back to an identifiable approved source — an OEM, an authorized repair station, or an operator releasing parts under proper records. A part whose history starts with an unfamiliar broker and no traceable origin is a strong scrap-diversion indicator.</li>
  <li><strong>LLP back-to-birth verification.</strong> For life-limited parts, the complete cycle history must be documentable. A part whose claimed cycles are inconsistent with its visible wear, or whose records contain a gap, is suspect. LLPs are the highest-value target for scrap diversion because the part may look fine while having no remaining life.</li>
  <li><strong>Anomalous-pricing flagging.</strong> Scrap-diverted parts can be sold below market because the diverter didn\'t pay market for the input — they took it from someone else\'s scrap bin. Pricing significantly below market is a flag, not by itself proof, but combined with other indicators it is significant.</li>
</ul>

<h4>Pathway 2 — Theft from OEM, MRO, or operator facilities</h4>

<p>Parts are physically stolen — either from production lines (in the case of OEMs), from repair facilities (MROs), or from operator inventories. The mechanics:</p>

<ol>
  <li>A part with legitimate identity is stolen — the part is a real OEM part, with real markings, real material conformance, possibly real performance.</li>
  <li>The OEM, MRO, or operator from whom the part was stolen has no record of releasing it. They may not realize it was stolen until inventory reconciliation, which can lag the theft by weeks or months.</li>
  <li>The thief sells the part on the gray market, typically through one or more brokers, with either no documentation or fabricated documentation.</li>
  <li>An unsuspecting distributor accepts the part, perhaps believing the broker\'s explanation of source ("surplus from an aircraft retirement," "obtained from a closing repair facility").</li>
</ol>

<p><strong>Theft cases TurbineWorks has visibility into typically come from:</strong></p>

<ul>
  <li>Employee theft from production lines — a worker takes parts home and sells them privately</li>
  <li>Organized theft rings targeting MRO inventories — break-ins, insider cooperation</li>
  <li>Operator-side inventory loss — parts disappear from operator warehouses, sometimes through staff and sometimes through external theft</li>
  <li>Transit theft — parts disappear during shipping, especially in regions with weak logistics security</li>
</ul>

<p><strong>The thing that distinguishes theft from scrap diversion:</strong> the part itself is typically conforming. The theft pathway delivers <em>real</em> parts. The problem is provenance — the operator who originally had the part has no record of releasing it, so the 8130-3 trail is either missing entirely or fabricated to fill the gap.</p>

<p><strong>TurbineWorks defense:</strong></p>

<ul>
  <li><strong>The 8130-3 chain must trace back to the issuing organization\'s confirmation.</strong> If TurbineWorks contacts the issuing organization listed in Block 4 and they confirm having issued the tag for this part on the indicated date, the chain is verified. If they have no record, the tag is fabricated and the part may be stolen.</li>
  <li><strong>Independent contact with the issuing organization.</strong> Contact information from the FAA database or other independent source — not from the shipping documents that came with the part. If the part is fraudulent, the supplier may have provided a fake "verification" phone number.</li>
  <li><strong>Operator-side notification systems.</strong> Major OEMs maintain stolen-part databases that distributors can query. If the part\'s serial number appears in a stolen-parts notification, that is dispositive.</li>
</ul>

<h4>Pathway 3 — Manufacturing without authorization</h4>

<p>A machine shop produces a part to OEM specifications without holding FAA authority to produce it. The shop may have legitimate capabilities — CNC machines, materials, heat treatment — but it has no PMA (Parts Manufacturer Approval), no TSO authorization, no production certificate. The part it produces is unapproved by definition under 14 CFR §21.502, regardless of how well it performs.</p>

<p>The mechanics:</p>

<ol>
  <li>The shop obtains OEM drawings, samples, or reverse-engineered specifications.</li>
  <li>The shop produces the part. Quality may range from excellent (true counterfeit) to poor (cheap knockoff).</li>
  <li>The shop fabricates documentation — typically a fake 8130-3 referencing a nonexistent or stolen FAA certificate.</li>
  <li>The part enters the supply chain through brokers willing to source from non-approved manufacturers, or through documentation laundering that obscures the source.</li>
</ol>

<p><strong>Why this happens:</strong> the economics of aviation parts are attractive. A turbine blade may have a manufacturing cost of $200-500 and a market price of $3,000-$15,000. An unauthorized shop can produce the part for a fraction of the OEM price and capture significant margin even at "discount" pricing. The legal risk is real but historically prosecution has lagged the activity.</p>

<p><strong>Defense:</strong></p>

<ul>
  <li><strong>FAA database verification.</strong> Block 15 certificate verification catches the most common form of this pathway — fabricated FAA certificate numbers. The numbers either don\'t exist or don\'t match the claimed issuing organization.</li>
  <li><strong>OEM authorization verification.</strong> For PMA parts in particular, the PMA holder is in the FAA database and the PMA scope is defined. A part claiming to be PMA-produced by an organization not actually holding the relevant PMA is unapproved.</li>
  <li><strong>Physical inspection.</strong> Unauthorized manufacture often (not always) leaves physical indicators — OEM-specific fingerprints in machining patterns, tool marks, surface finish, and stamping that differ from authorized production. Lesson 1.4 covers this in detail.</li>
</ul>

<h4>Pathway 4 — Documentation fraud</h4>

<p>The part itself is real but the paperwork is forged or altered. Variations include:</p>

<ul>
  <li><strong>Forged 8130-3 from scratch.</strong> The fraudster fabricates the entire form, including a plausible-looking Block 15 number that may or may not pass database lookup.</li>
  <li><strong>Re-used 8130-3.</strong> A real 8130-3 from a different part is photocopied or scanned, then matched up with a different physical part that the fraudster has obtained separately. The Block 10 serial number may not match the physical part.</li>
  <li><strong>Altered 8130-3.</strong> A real 8130-3 is modified — date changed, status changed, or remarks added to claim AD/SB compliance that wasn\'t actually performed.</li>
  <li><strong>Documentation laundering through broker chains.</strong> A part passes through multiple intermediary brokers, each adding documentation, until the documentation trail obscures the original (unapproved) source.</li>
</ul>

<p><strong>Why documentation fraud is the easiest form:</strong> producing a convincing-looking paper document is much easier than producing a convincing physical part. A scanner, photo editor, and laser printer are sufficient. The bad actor doesn\'t need machine tools, materials, or aerospace manufacturing expertise.</p>

<p><strong>Why documentation fraud is the most-caught form:</strong> every block of an 8130-3 ties to something independently verifiable. Block 15 ties to the FAA database. Block 4 ties to that certificate. Block 10 ties to the data plate. Block 12 should be consistent with the part\'s history. A forger has to fabricate consistently across many fields and survive cross-reference checks. They rarely do.</p>

<p><strong>Defense:</strong> Lesson 1.3 covers documentation review in comprehensive depth. The summary defenses:</p>

<ul>
  <li>FAA database verification for Block 15 numbers</li>
  <li>Cross-reference between Block 4 (organization name) and Block 15 (certificate)</li>
  <li>Cross-reference between Block 10 (serial) and the physical data plate</li>
  <li>Originality of Block 14 signature (not a photocopy)</li>
  <li>Date plausibility (Block 17)</li>
  <li>Internal consistency of Block 12 remarks</li>
</ul>

<h4>Pathway 5 — Counterfeit manufacture</h4>

<p>Distinct from manufacture without authorization (Pathway 3), counterfeiting is the deliberate fabrication of a part to look like a legitimate OEM part — same markings, same finish, same packaging — produced by an entity that knows it is misrepresenting the source. Counterfeiting is fraud with intent.</p>

<p>Counterfeit cases in aviation typically target:</p>

<ul>
  <li><strong>High-value mechanical commodities</strong> — turbine blades, bearings, brackets, fasteners in high-strength alloys</li>
  <li><strong>Electronic components</strong> — chips, capacitors, connectors. The electronic counterfeit problem grew with offshore manufacturing in the 2000s.</li>
  <li><strong>Parts with strong OEM markings</strong> — the more recognizable the OEM brand, the more counterfeitable. Genuine-looking OEM marks are the product the counterfeiter sells.</li>
</ul>

<p><strong>The legal distinction matters because:</strong></p>

<ul>
  <li>Civil enforcement under FAA regulations applies to unapproved parts generally</li>
  <li>Criminal enforcement under fraud statutes (18 U.S.C. §1341 mail fraud, §1343 wire fraud, §1957 money laundering) applies when there is intent to deceive</li>
  <li>DFARS counterfeit-parts clauses (252.246-7007/7008) impose specific obligations on defense contractors and their suppliers</li>
</ul>

<p>For a TurbineWorks receiving inspector, the practical distinction is academic at the point of receiving — the inspector identifies a SUP and quarantines. The QA Manager investigates. If the investigation finds evidence of deliberate misrepresentation, the matter is referred for criminal investigation. The inspector\'s appropriate output is the SUP flag; the legal classification is downstream.</p>

<p><strong>Defense against counterfeiting:</strong></p>

<ul>
  <li><strong>Supplier qualification with anti-counterfeit emphasis.</strong> SAE AS5553 (electronics) and AS6174 (broader materiel) define supplier-qualification practices specifically aimed at counterfeit detection. TurbineWorks suppliers should be assessed against these standards.</li>
  <li><strong>Trusted-supplier preference.</strong> Buying from OEMs and OEM-authorized distributors substantially reduces counterfeit exposure. The further into the broker market TurbineWorks ventures, the higher the counterfeit risk.</li>
  <li><strong>Physical inspection.</strong> Counterfeit parts often (not always) have subtle physical indicators — markings that don\'t match the OEM\'s style exactly, surface finish that differs, material properties that don\'t pass laboratory verification.</li>
  <li><strong>Receiving lab capability for high-value items.</strong> Some distributors maintain or contract for metallurgical/dimensional/X-ray inspection capability for high-value parts where the counterfeit risk justifies the cost.</li>
</ul>

<h4>Pathway 6 — Emerging: cyber-enabled supply chain attacks</h4>

<p>The newest pathway, increasingly relevant. The mechanics:</p>

<ul>
  <li>A supplier\'s ERP or document-management system is compromised — typically through phishing, ransomware, or credential theft.</li>
  <li>The attacker manipulates the supplier\'s issued documentation — modifying 8130-3 records, altering serial number databases, or generating fraudulent documents that appear to come from the legitimate supplier.</li>
  <li>The fraudulent documentation accompanies parts that may be of any origin — diverted scrap, counterfeit, unauthorized manufacture.</li>
  <li>The supplier may not realize their systems were compromised until weeks or months later, by which time the fraudulent documents have been used to release multiple parts.</li>
</ul>

<p>This pathway is harder to detect because the documentation may verify cleanly against external databases — the supplier really exists, the certificate is real, the form structure is correct. The fraud lives inside the supplier\'s systems.</p>

<p><strong>Defense (still emerging as an industry practice):</strong></p>

<ul>
  <li>Out-of-band verification when documentation is high-stakes — phone calls to verify, not just email confirmations</li>
  <li>Pattern analysis — if a supplier suddenly has unusual volume or unusual part types, investigate</li>
  <li>Cyber-hygiene requirements in supplier qualification — increasingly common in OEM tier-1 supplier programs</li>
  <li>Industry information sharing — when one distributor identifies a compromised supplier, the information flows through ASA, ATA, and FAA channels to alert others</li>
</ul>

<h4>Why aviation is a particularly attractive target for SUP entry</h4>

<p>Several factors combine to make aviation a high-target environment:</p>

<ul>
  <li><strong>High margins.</strong> Aviation parts trade at multiples over manufacturing cost. The economic incentive for fraud is substantial.</li>
  <li><strong>Complex documentation.</strong> The 8130-3 system, while comprehensive, depends on verification disciplines that not every buyer in the chain practices rigorously.</li>
  <li><strong>Long supply chains with many intermediaries.</strong> A part may pass through multiple brokers and distributors before reaching the operator. Each handoff is an opportunity for fraud, and each handoff also provides plausible deniability ("we got it from a reputable broker").</li>
  <li><strong>Cost pressure on operators.</strong> Airlines and MROs operate under significant cost pressure. The cheapest source that satisfies paperwork requirements often gets the order, and rigorous verification is the cost-cutting target.</li>
  <li><strong>Aging fleets with declining OEM support.</strong> As aircraft and engines age, OEM parts availability declines and prices rise. The gap is filled by the aftermarket, including brokers willing to source from less-controlled channels.</li>
</ul>

<h4>Where TurbineWorks fits in the chain</h4>

<p>TurbineWorks is positioned in the middle of the parts supply chain. Upstream are the part sources — OEMs, repair stations, operators releasing parts, brokers consolidating supply. Downstream are the customers — airlines, MROs, OEM-direct programs.</p>

<p>As an ASA-100 accredited distributor, TurbineWorks acts as a verification node. Customers buying from TurbineWorks are paying, in part, for TurbineWorks\' verification work. The customer expects that parts they receive from TurbineWorks have been:</p>

<ul>
  <li>Sourced from qualified suppliers</li>
  <li>Documented with verifiable provenance</li>
  <li>Inspected on receipt</li>
  <li>Stored under proper conditions</li>
</ul>

<p>If a SUP reaches a customer through TurbineWorks, TurbineWorks has failed the verification role. This is the reputation risk, the customer-relationship risk, the accreditation risk, and in the worst cases the legal-liability risk. Every receiving-inspection check is, ultimately, the TurbineWorks verification work the customer is paying for.</p>

<h4>The economics of fraud (why this keeps happening)</h4>

<p>Fraud persists because it pays. A scrap-diverted turbine disk might cost the diverter $50 in handling and yield $5,000 in sale price — a 100x return. A counterfeit-manufactured electronic component might cost $5 to produce and sell for $400 — an 80x return. Even with low success rates (most fraud attempts get caught), the economics support continued attempts.</p>

<p>The defense is not zero-fraud — fraud attempts will continue. The defense is making TurbineWorks expensive to attack. A supplier that knows TurbineWorks rigorously verifies documentation, qualifies suppliers, and reports SUP attempts to the FAA will direct their fraud attempts to less rigorous distributors. The market sorts itself: rigorous distributors get clean inventory; lax distributors get the residual fraud risk.</p>

<h4>How the FAA tracks supply chain integrity</h4>

<p>The FAA\'s SUP Program Office maintains aggregated data on:</p>

<ul>
  <li>SUP reports filed (Form 8120-11)</li>
  <li>Patterns by supplier — which suppliers are repeatedly named in SUP reports</li>
  <li>Patterns by part type — which categories show elevated SUP activity</li>
  <li>Outcomes — which SUP investigations confirmed unapproved status</li>
</ul>

<p>The aggregated data informs FAA enforcement priorities, accreditation organization oversight, and industry-wide alerts. TurbineWorks\' SUP filings contribute to this data — and TurbineWorks may receive industry alerts based on others\' filings. Reporting is not just compliance; it is participation in the system that defends the broader supply chain.</p>

<h4>Self-check</h4>

<ol>
  <li>What is scrap diversion and why is it the largest single SUP pathway?</li>
  <li>How does theft differ from scrap diversion in terms of part conformity?</li>
  <li>Why does unauthorized manufacture make a part legally unapproved even if the part performs adequately?</li>
  <li>Why is documentation fraud the easiest SUP form to attempt but also the most-caught?</li>
  <li>What distinguishes counterfeit manufacture from manufacture without authorization?</li>
  <li>Why is supplier qualification a defense against multiple pathways at once?</li>
  <li>What is TurbineWorks\' role in the supply chain that customers are paying for?</li>
</ol>

<p><em>[TurbineWorks Procedure Reference: insert TurbineWorks approved-supplier list and the procedure for adding a new supplier here.]</em></p>
HTML
        ],
        [
            'name'  => 'Lesson 1.3 — Documentation Red Flags',
            'intro' => '<p>The receiving inspector\'s primary line of defense. Comprehensive walk-through of every red flag that should put you into SUP mode, with database-lookup procedures, scenario walkthroughs, and the complete checklist.</p>',
            'content' => <<<'HTML'
<h3>Documentation Red Flags: The Receiving Inspector\'s Primary Defense</h3>

<p>If you remember nothing else from Module 1, remember this: <strong>most suspected unapproved parts are caught at the documentation review step of receiving inspection, not at the physical inspection.</strong> Bad paperwork is dramatically easier to spot than a well-counterfeited part. The physical part may be machined to OEM dimensions, plated correctly, marked convincingly. The paperwork is where the fraud typically shows.</p>

<p>This lesson is the operational core of Module 1. It walks through every documentation red flag — what to look for, why it matters, and what to do when you see it. By the end of this lesson you should be able to perform documentation review on an arriving shipment and identify which paperwork warrants SUP quarantine.</p>

<h4>Why documentation review catches more SUPs than physical inspection</h4>

<p>Counterfeiting a part physically is hard. The bad actor needs:</p>

<ul>
  <li>A machine shop capable of producing aerospace-grade parts to OEM dimensions</li>
  <li>Materials of the correct alloy, with documentation supporting the alloy claim</li>
  <li>Heat treatment, plating, or surface treatment matching OEM spec</li>
  <li>Stamping and marking equipment matching the OEM\'s style</li>
  <li>Knowledge of OEM-specific manufacturing fingerprints (toolmarks, edge prep, etc.)</li>
</ul>

<p>Counterfeiting <em>paperwork</em> is dramatically easier. Anyone with a scanner, a photo editor, and a printer can produce something that <em>looks</em> like an FAA 8130-3. But the paperwork has to survive scrutiny — and every block of a real 8130-3 ties to something independently verifiable. Block 15 ties to the FAA certificate database. Block 4 ties to that certificate. Block 3 ties to the issuing organization\'s internal records. Block 10 ties to the data plate on the actual physical part.</p>

<p>The receiving inspector\'s job is to perform that verification systematically. The bad actor only has to fail one cross-check to be caught.</p>

<h4>FAA 8130-3 — Block-by-block red flags</h4>

<p>An FAA 8130-3 has 22 numbered blocks. Each can contain red-flag indicators. We\'ll walk every block.</p>

<h5>Block 1 — Approving Civil Aviation Authority / Country</h5>
<p>Should read "FAA / United States" for an FAA-issued tag. Other approved authorities (EASA, TCCA, etc.) issue equivalent forms under bilateral agreement — but those forms have different titles (EASA Form 1, TCCA Form One) and shouldn\'t appear with "FAA / United States" in Block 1.</p>
<p><strong>Red flag:</strong> Block 1 reads "FAA" but the rest of the form has the layout of an EASA Form 1 or another form type. The form is hybrid — pieced together from multiple sources.</p>

<h5>Block 2 — Form Title</h5>
<p>Should read exactly: "AUTHORIZED RELEASE CERTIFICATE / FAA Form 8130-3, AIRWORTHINESS APPROVAL TAG"</p>
<p><strong>Red flag:</strong> Title is reworded, abbreviated, or contains typos. The official text is fixed; any variation is suspicious.</p>

<h5>Block 3 — Form Tracking Number</h5>
<p>Unique identifier assigned by the issuing organization. Format varies by organization but is consistent within each organization\'s tags.</p>
<p><strong>Red flags:</strong></p>
<ul>
  <li>Block 3 is blank or contains placeholder text ("XXXXX," "TBD")</li>
  <li>Format obviously inconsistent with the issuing organization\'s known sequence (after you\'ve seen a few of their tags)</li>
  <li>Block 3 appears to have been altered — characters of inconsistent depth or alignment</li>
</ul>

<h5>Block 4 — Organization Name and Address</h5>
<p>The Repair Station, Production Approval Holder, or other approved organization issuing the certificate.</p>
<p><strong>Red flags:</strong></p>
<ul>
  <li>Organization name does not match the certificate holder per FAA database lookup of Block 15</li>
  <li>Address is a P.O. Box only — most legitimate aviation organizations have a physical facility</li>
  <li>Address geographic mismatch — the organization\'s known location vs. the address on the tag don\'t agree</li>
  <li>The organization name is unfamiliar AND the certificate number lookup fails</li>
</ul>

<h5>Block 5 — Work Order / Contract / Invoice Number</h5>
<p>Cross-references the issuing organization\'s internal records.</p>
<p><strong>Red flags:</strong></p>
<ul>
  <li>Block 5 is blank when it shouldn\'t be — the issuing organization needs an internal reference</li>
  <li>Block 5 contains nonsense or placeholder text</li>
</ul>

<h5>Block 6 — Item Number</h5>
<p>Sequential number for multi-item forms. For most engine parts this is "1" since serialized parts get one form per part.</p>

<h5>Block 7 — Description</h5>
<p>The OEM\'s description of the part (e.g., "TURBINE BLADE, HPT STAGE 1").</p>
<p><strong>Red flags:</strong></p>
<ul>
  <li>Description doesn\'t match the actual physical part</li>
  <li>Description is a generic term where the OEM uses a specific one</li>
  <li>Description language is awkward or non-native English — sometimes an indicator of foreign counterfeiting attempts</li>
</ul>

<h5>Block 8 — Part Number</h5>
<p>OEM part number including any dash suffix and revision letter.</p>
<p><strong>Red flags:</strong></p>
<ul>
  <li>Part number doesn\'t match the part stamping/marking on the physical part</li>
  <li>Part number doesn\'t exist in the OEM\'s current IPC (verify against current IPC revision)</li>
  <li>Part number has been altered — characters of inconsistent typography</li>
</ul>

<h5>Block 9 — Quantity</h5>
<p>Must match the actual count of parts in the shipment.</p>

<h5>Block 10 — Serial Number</h5>
<p>For serialized parts, must match the part\'s data plate exactly. Character by character. Including any letter prefixes or suffixes.</p>
<p><strong>Red flags:</strong></p>
<ul>
  <li>Serial number on the form does not match the part\'s data plate</li>
  <li>The data plate appears to have been removed and replaced (paint disturbance, mounting screw witness marks)</li>
  <li>Serial number on the form has been altered (whiteout, over-stamping, ink color difference)</li>
  <li>Serial number doesn\'t fit the OEM\'s known serial-number format (length, character set, structure)</li>
</ul>

<h5>Block 11 — Status / Work</h5>
<p>One of: NEW / INSPECTED/TESTED / REPAIRED / OVERHAULED / MODIFIED / PROTOTYPE (for parts in development; rare at TurbineWorks).</p>
<p><strong>Red flags:</strong></p>
<ul>
  <li>"NEW" on a part showing evidence of use (wear marks, oil staining, run-in patterns on bearing races)</li>
  <li>Block 11 left blank or with non-standard term</li>
  <li>Multiple boxes checked — should be exactly one</li>
</ul>

<h5>Block 12 — Remarks</h5>
<p>Free-text field. Critical content for engine parts: TSN, CSN, LLP remaining life, AD compliance, SB compliance, traceability to source engine.</p>
<p><strong>Red flags:</strong></p>
<ul>
  <li>Block 12 is blank on a serialized LLP — the LLP life data should be here</li>
  <li>Remarks reference SBs or ADs that don\'t exist (verify against FAA DRS and OEM SB database)</li>
  <li>Remarks reference incompatible items — claims to have come from a CFM56-7B engine but the part is a CFM56-5B part</li>
  <li>TSN/CSN values that decrease vs. previous documentation — time accumulates, it doesn\'t decrease</li>
</ul>

<h5>Block 13 — Conformance Statement</h5>
<p>Boxed pre-printed text with two checkboxes — one for approved-design conformance, one for non-approved-design.</p>
<p><strong>Red flags:</strong></p>
<ul>
  <li>Neither checkbox is checked</li>
  <li>Both checkboxes are checked</li>
  <li>The pre-printed text has been altered</li>
  <li>The "non-approved design" box is checked without explanation in Block 12 of what design data the part conforms to</li>
</ul>

<h5>Block 14 — Approving Signature</h5>
<p>Original wet-ink signature OR verifiable electronic signature with audit trail.</p>
<p><strong>Red flags (any of these is a quarantine condition by itself):</strong></p>
<ul>
  <li><strong>Photocopied signature.</strong> Look for: grainy edges where ink should be sharp; uniform "ink" color where wet ink would have density variation; lines that are dotted from printer rasterization.</li>
  <li>Signature appears computer-generated (clean curves, no human variation)</li>
  <li>"Stamped" signature without authorization — many organizations don\'t permit signature stamps</li>
  <li>Signature doesn\'t match the printed name in Block 16</li>
  <li>Empty space where signature should be</li>
</ul>

<h5>Block 15 — Authorization / Certificate Number</h5>
<p>The single most important block for fraud detection. This is the FAA certificate number of the issuing organization. <strong>Every legitimate certificate number is in the FAA public database.</strong></p>

<p><strong>Database lookup procedure (perform every time a new supplier or unfamiliar tag arrives):</strong></p>
<ol>
  <li>Open <a href="https://av-info.faa.gov/" target="_blank" rel="noopener">av-info.faa.gov</a></li>
  <li>Navigate to the certificate search appropriate to the type — Repair Stations, Production Approvals, Designees, etc.</li>
  <li>Enter the Block 15 number. The database should return the certificate holder\'s name, type, status, and location.</li>
  <li>Compare the returned name and location to Block 4 of the 8130-3. They must match.</li>
  <li>Check the status — active, expired, suspended? Only active certificates can issue valid 8130-3 tags.</li>
</ol>

<p><strong>Red flags:</strong></p>
<ul>
  <li>Block 15 number returns no result in the FAA database</li>
  <li>Block 15 number is in the database but the name doesn\'t match Block 4</li>
  <li>Block 15 number is in the database but the certificate is suspended, expired, or revoked</li>
  <li>Block 15 number format is implausible — letters where there should only be digits, wrong length, etc.</li>
</ul>

<h5>Block 16 — Name of Signer</h5>
<p>Printed name of the person signing in Block 14.</p>
<p><strong>Red flags:</strong></p>
<ul>
  <li>Block 16 is blank</li>
  <li>Block 16 contains only a first name or a nickname</li>
  <li>The name doesn\'t match the signature style</li>
</ul>

<h5>Block 17 — Date</h5>
<p>Date the form was signed.</p>
<p><strong>Red flags:</strong></p>
<ul>
  <li>Date in the future</li>
  <li>Date implausibly old (an 18-month-old 8130-3 on a "freshly shipped" part — where was the part during that time?)</li>
  <li>Date that doesn\'t correspond to the issuing organization\'s known operating period (a tag dated 2018 from an organization that wasn\'t certified until 2020)</li>
  <li>Date that has been altered (the year digit changed, etc.)</li>
</ul>

<h5>Blocks 18-22 — Installer Section</h5>
<p>Right side of the form. Completed by the installer when the part is installed on an aircraft. <strong>Should be BLANK on a part not yet installed.</strong></p>
<p><strong>Red flags:</strong></p>
<ul>
  <li>Any of Blocks 18-22 are completed on a part you are receiving as "new" or "unused"</li>
  <li>Completion of these blocks indicates the part was previously installed and removed. Investigate the history before accepting.</li>
</ul>

<h5>General form-level red flags (beyond individual blocks)</h5>
<ul>
  <li><strong>Whiteout, correction fluid, or visible alteration</strong> on any block. Even a small correction should be a struck-through-with-initials correction, not whiteout.</li>
  <li><strong>Photocopy passing as original.</strong> A photocopy is only acceptable if it bears a "TRUE COPY" attestation from an authorized custodian — and the attestation itself must be verifiable.</li>
  <li><strong>Form printed on incorrect paper.</strong> 8130-3 forms come from the FAA on specific paper stock. A form on plain copier paper without a TRUE COPY attestation is suspicious.</li>
  <li><strong>Misalignment, font mismatch.</strong> The pre-printed form has fixed typography. Hand-filled fields are added in pen or by direct printer. A field that uses a font matching the pre-printed text is suspicious — it suggests the form was reconstructed in software.</li>
  <li><strong>Multiple parts on one form when each should have its own.</strong> Serialized engine parts typically each get their own 8130-3. Multi-part forms are appropriate for non-serialized batches (e.g., standard hardware).</li>
</ul>

<h4>EASA Form 1 — Specific red flags</h4>

<p>Most of the same principles apply, with EASA-specific differences:</p>

<ul>
  <li>Block 1 should read "EASA" (or the specific national authority under EASA delegation)</li>
  <li>Block 2 title is "AUTHORIZED RELEASE CERTIFICATE / EASA Form 1"</li>
  <li>The EASA equivalent of Block 15 is the organization\'s approval number — verify against <a href="https://www.easa.europa.eu/" target="_blank" rel="noopener">easa.europa.eu</a> organization directory</li>
  <li>Some legitimate EASA-approved organizations are in non-EU countries under bilateral agreement — verify they appear in EASA\'s recognized-organization list</li>
</ul>

<h4>TCCA Form One — Specific red flags</h4>

<ul>
  <li>Block 1 reads "TCCA / Canada"</li>
  <li>Block 2 title is "AUTHORIZED RELEASE CERTIFICATE / TCCA Form One"</li>
  <li>Approval number is the Canadian Approved Maintenance Organization (AMO) number, verifiable through Transport Canada\'s online database</li>
  <li>Otherwise identical structure to the EASA Form 1, since both follow the bilateral-harmonized format</li>
</ul>

<h4>Certificate of Conformance (COC) red flags</h4>

<p>COCs are supplier-issued, less standardized than 8130-3 tags. The red flags are different:</p>

<ul>
  <li><strong>No specific standard cited.</strong> A valid COC references the specific standard the part conforms to: "conforms to MIL-PRF-XXXXX rev. C," "manufactured per AMS 5832 rev. F." A COC that just says "conforms to applicable standards" is meaningless.</li>
  <li><strong>No serial or lot number tying the COC to the specific items.</strong> A COC must identify which physical parts it certifies. "All parts shipped this month" is not adequate.</li>
  <li><strong>Generic letterhead.</strong> Legitimate suppliers use letterhead with their company name, address, and contact information. A COC on plain paper or generic stationery is suspect.</li>
  <li><strong>Signed by someone whose authority isn\'t documented.</strong> A name without a title, or a title that doesn\'t imply authority to attest conformance (e.g., "Sales Representative" rather than "Quality Manager"), suggests the COC wasn\'t issued by an authorized person.</li>
  <li><strong>COC dated before the parts were manufactured.</strong> Internal inconsistency — the COC can\'t pre-date the parts it certifies.</li>
</ul>

<h4>Packing list and invoice red flags</h4>

<ul>
  <li>Part number on packing list doesn\'t match the 8130-3</li>
  <li>Quantity on packing list doesn\'t match Block 9 of the 8130-3 or the actual count</li>
  <li>Shipping origin (return address) doesn\'t match the issuing organization\'s known location</li>
  <li>Packing list and invoice from different companies that don\'t obviously relate to each other</li>
  <li>Multiple serial numbers listed on packing list but only one 8130-3 in the package</li>
</ul>

<h4>Supplier-level red flags (before opening the shipment)</h4>

<ul>
  <li><strong>Supplier is new to TurbineWorks and not on the approved-supplier list.</strong> Even legitimate suppliers go through qualification before TurbineWorks accepts their shipments routinely.</li>
  <li><strong>Supplier address is a residential or P.O. Box-only location.</strong> Aviation parts brokers operating from a P.O. Box without a physical warehouse facility are a recognized pattern for fraudulent intermediaries.</li>
  <li><strong>Supplier offers parts at significantly below market price.</strong> The signature of either (a) a legitimate distressed sale or (b) parts the supplier didn\'t pay market for. Investigate which.</li>
  <li><strong>Supplier resists or refuses requests for additional documentation.</strong> Legitimate suppliers welcome documentation requests. Pushback is itself a red flag.</li>
  <li><strong>Supplier offers parts that are very hard to find from legitimate sources.</strong> If a part is in genuine short supply, a supplier suddenly having stock should be probed — where did they get it?</li>
  <li><strong>Supplier has just appeared in the market.</strong> Suppliers with no track record in aviation parts may be entry vehicles for one-time fraud attempts.</li>
</ul>

<h4>Scenario walkthroughs</h4>

<p>Each scenario describes a documentation situation a TurbineWorks inspector might encounter. Read each, decide your action, then check against the analysis.</p>

<h5>Scenario 1</h5>
<p>An HPT blade arrives. The 8130-3 shows Block 15 certificate number 5P3R784D. You look it up at av-info.faa.gov and the database returns "no records found." Block 4 names a repair station called "Northeast Aviation Services" in Massachusetts. The part itself looks fine.</p>

<p><strong>Your action?</strong></p>

<p><em>Analysis:</em> Quarantine. Block 15 not in FAA database is one of the strongest single SUP indicators. The part looking fine doesn\'t change anything — a counterfeit part may be physically convincing. Notify QA Manager same day. Begin investigation: contact "Northeast Aviation Services" through independently sourced contact information (not from the supplier\'s shipping documents) to verify they issued the tag. If the FAA database has no record of the certificate number, this organization almost certainly does not have FAA authority and the tag is fabricated.</p>

<h5>Scenario 2</h5>
<p>A turbine disk arrives with an 8130-3 from a well-known OEM repair station. Block 15 verifies in the FAA database, Block 4 matches, signature looks original. But Block 17 dates the form to August 2021 — over four years ago. The part is described as "Newly Overhauled."</p>

<p><strong>Your action?</strong></p>

<p><em>Analysis:</em> Hold and investigate. The tag may be legitimate but the four-year gap between tag issue and current shipment requires explanation. Where was the part during that period? In sealed storage? Re-installed and removed? Damaged? Investigate before accepting. The supplier should be able to provide a chain-of-custody narrative covering the four-year gap.</p>

<h5>Scenario 3</h5>
<p>A fuel control unit arrives. The 8130-3 looks valid in every block. The part has the OEM\'s name and a serial number. But the OEM mark on the part appears to have been stamped over a previous mark — you can see grinding marks under the new stamping.</p>

<p><strong>Your action?</strong></p>

<p><em>Analysis:</em> Quarantine. Documentation is the front line but it\'s not the only check. The physical evidence of re-stamping suggests the part may have been re-marked to match the documentation — meaning the original identity of the part is now hidden. The 8130-3 may have been issued for one part and the documentation reused with a different part. Investigate the part\'s actual identity through metallurgical / dimensional analysis if needed.</p>

<h5>Scenario 4</h5>
<p>A small lot of bearings arrives from a supplier you haven\'t bought from before. The supplier is offering them at about 40% below market. They\'ve provided an 8130-3 that verifies cleanly in every block. The COC references the correct OEM specification. Everything looks fine.</p>

<p><strong>Your action?</strong></p>

<p><em>Analysis:</em> Hold pending supplier qualification. Even with clean documentation, a new supplier offering anomalously low pricing is a flag. Two of the recognized SUP-entry patterns are: (1) supplier qualification gap and (2) below-market pricing as economic signature of irregular sourcing. The QA Manager should evaluate the supplier through standard qualification — facility verification, references, history check — before TurbineWorks accepts this shipment. The shipment itself isn\'t necessarily bad, but receiving it without qualification creates audit risk.</p>

<h5>Scenario 5</h5>
<p>An HPC blade set arrives with an 8130-3 that shows Block 14 with a clearly photocopied signature — the signature has the rasterized edges typical of laser printer output rather than wet ink. Everything else about the form verifies.</p>

<p><strong>Your action?</strong></p>

<p><em>Analysis:</em> Quarantine. The photocopied signature is itself a critical defect. A legitimate 8130-3 has an original signature OR an electronic signature with verifiable audit trail. A photocopy is acceptable only with a TRUE COPY attestation from an authorized custodian — and that attestation has its own signature requirements. Contact the issuing organization (using independently obtained contact info) and ask: did you issue an 8130-3 with this tracking number for this part, and if so, can you confirm the form details? Either they confirm and provide a properly signed copy, or they don\'t — and that tells you what you need to know.</p>

<h4>What NOT to do when you spot a red flag</h4>

<p>This is as important as what to do. The wrong actions can compromise both the investigation and the evidence:</p>

<ul>
  <li><strong>Don\'t return the part to the supplier without QA Manager authorization.</strong> Returning a SUP just shifts the part to the next distributor in the chain. The original tag is now in the supplier\'s hands and may be re-used.</li>
  <li><strong>Don\'t correct the documentation.</strong> If you "fix" a typo in the part number to match what you think it should be, you\'ve altered evidence. Even if you\'re right, the alteration is now on the original document.</li>
  <li><strong>Don\'t photocopy and discard the originals.</strong> The original physical paperwork is evidence. A photocopy in the file is not the same as the original 8130-3.</li>
  <li><strong>Don\'t accept the supplier\'s verbal explanation alone.</strong> "Oh, sorry about that, we\'ll send a corrected tag" is fine — but the corrected tag is a separate issue. The original suspect tag and the part it accompanied stay in quarantine.</li>
  <li><strong>Don\'t accept the part because the customer is waiting.</strong> AOG urgency is real but it doesn\'t override the quarantine decision. The receiving inspector\'s authority is not overridable by sales or by customer pressure.</li>
  <li><strong>Don\'t discuss the SUP investigation publicly.</strong> If it turns out to be a confirmed SUP, supplier relationships and possibly criminal proceedings may follow. Loose talk can compromise both. Keep the investigation inside the QA chain.</li>
</ul>

<h4>The complete documentation red-flag checklist</h4>

<p>For each incoming shipment of a serialized aviation part:</p>

<ol>
  <li>Match the 8130-3 to a current TurbineWorks PO ☐</li>
  <li>Match the 8130-3 part number (Block 8) to the PO part number ☐</li>
  <li>Match the 8130-3 serial number (Block 10) to the part\'s data plate ☐</li>
  <li>Match the 8130-3 quantity (Block 9) to the actual count ☐</li>
  <li>Block 15 certificate number verified in FAA database ☐</li>
  <li>Block 4 organization name matches the certificate database result ☐</li>
  <li>Block 4 address matches the database location ☐</li>
  <li>Certificate is active (not suspended, expired, or revoked) ☐</li>
  <li>Block 14 signature is original (or properly attested electronic) ☐</li>
  <li>Block 14 signature is not a photocopy ☐</li>
  <li>Block 16 printed name matches the signature style ☐</li>
  <li>Block 17 date is plausible — not future, not unreasonably old ☐</li>
  <li>Block 17 date is consistent with the certificate\'s active period ☐</li>
  <li>Block 11 status is one of the standard values ☐</li>
  <li>Block 12 remarks contain LLP time/cycle data if applicable ☐</li>
  <li>Block 13 has exactly one box checked ☐</li>
  <li>Right-side blocks (18-22) are blank ☐</li>
  <li>No whiteout, correction fluid, or visible alteration ☐</li>
  <li>Supplier is on the approved-supplier list, or QA Manager has authorized one-time receipt ☐</li>
  <li>Shipping origin matches Block 4 organization location ☐</li>
  <li>Packing list part number matches Block 8 ☐</li>
  <li>If COC required: COC cites specific standard, identifies the parts by serial/lot, signed by authorized person ☐</li>
  <li>Pricing is plausible for the part type and condition ☐</li>
</ol>

<p>Any unchecked item warrants either resolution before continuing receiving inspection, or quarantine pending investigation. The threshold is intentionally low. When in doubt, quarantine.</p>

<h4>Self-check</h4>

<ol>
  <li>What is the single most important block on an 8130-3 for fraud detection? Why?</li>
  <li>Where do you verify a Block 15 certificate number?</li>
  <li>Why is a photocopied Block 14 signature a quarantine condition?</li>
  <li>If Blocks 18-22 (right side) are filled in on a part you are receiving, what does that suggest?</li>
  <li>What should you do FIRST when you spot a documentation red flag — call the supplier, call the QA Manager, or quarantine the part?</li>
  <li>Why is "returning the part to the supplier without investigation" the wrong action?</li>
  <li>Name three supplier-level red flags that should put you on alert before you even open the shipment.</li>
  <li>What does a COC need to contain to be considered valid?</li>
</ol>

<p>If any of these are unclear, re-read the relevant section. The next lessons in Module 1 (1.4 physical red flags and 1.5 SUP reporting) build directly on this material.</p>
HTML
        ],
        [
            'name'  => 'Lesson 1.4 — Physical Inspection Red Flags',
            'intro' => '<p>The second line of defense after documentation review. What to look for on the part itself: markings, surface finish, material indicators, wear evidence, and packaging. Detailed enough to be operational at a receiving bench.</p>',
            'content' => <<<'HTML'
<h3>Physical Inspection Red Flags</h3>

<p>Lesson 1.3 covered the paperwork. This lesson covers the part itself. Documentation review catches most SUPs — but not all. Some bad actors have invested in producing convincing documentation; the physical part is where their effort runs into the limits of their manufacturing capability.</p>

<p>This lesson is the operational reference for what to examine on a physical aviation part and what each observation might indicate. By the end of the lesson, an inspector should be able to perform a meaningful physical inspection of an arriving part beyond just visual confirmation that "it looks like a turbine blade."</p>

<h4>Why physical inspection is necessary even after documentation review</h4>

<p>A well-prepared bad actor can produce documentation that survives database lookup, signature verification, and cross-reference. Sometimes the bad actor genuinely has access to a real FAA-certified organization\'s documents — through fraud, theft, or insider access. In those cases the paperwork passes scrutiny entirely, and the physical part is the only remaining check.</p>

<p>Physical inspection also catches a category of SUP that documentation review can\'t: parts that are real OEM parts but are not the part the documentation describes. The part is genuine; the paperwork describes a different part; the bad actor has matched them up to deliver something other than what was ordered.</p>

<p>Finally, physical inspection catches the parts where the bad actor invested in convincing paper but cut corners on the part itself — a fabricated bearing with the right markings but wrong material, a turbine blade machined to wrong dimensions, a connector with the right OEM name but inferior plating.</p>

<h4>Markings — the highest-information inspection target</h4>

<p>Every OEM has a marking signature. The font used for part numbers, the depth of the stamp or etch, the position relative to part features, the color of any paint-fill — all of these are characteristic. A counterfeit can match dimensions and even material, but matching markings requires either the OEM\'s actual stamping equipment or careful study of multiple genuine samples.</p>

<h5>Font matching</h5>

<p>OEMs typically use specific fonts for part-number stamping. Different OEMs use different fonts, and within a single OEM different processes (vibro-engraving, laser etching, electrochemical etching, dot-peen) produce visibly different character forms.</p>

<p><strong>What to compare:</strong></p>
<ul>
  <li>The shape of "0" — round, oval, with or without slash, narrow or wide</li>
  <li>The shape of "1" — with or without serifs, narrow or wide</li>
  <li>The shape of "8" — round and balanced, or with one larger loop</li>
  <li>The angle of slants on "4," "7," "9"</li>
  <li>Letter forms — particularly "A" (with or without crossbar variation), "G" (with or without spur), "R" (with or without leg)</li>
</ul>

<p>A part you suspect should be compared against a reference part you know to be genuine. Different OEMs have different signature characters; CFM stamping looks different from Pratt &amp; Whitney stamping, which looks different from Rolls-Royce. With experience you develop pattern recognition; without experience, side-by-side comparison with a known-good reference catches most font mismatches.</p>

<h5>Stamping or etching depth</h5>

<p>Vibro-engraving (oscillating diamond stylus) produces a characteristic depth and texture — moderate depth, slightly rough bottom, visible scribe path. Laser etching produces shallower marks with a different texture — sometimes glossy, sometimes oxidized depending on the laser settings. Electrochemical etching (EChE) produces yet another texture — shallow but with characteristic color (often dark) from the chemical reaction.</p>

<p>Each OEM specifies which marking method to use for which part. A turbine blade that should have been vibro-engraved per OEM spec but appears to be laser-etched is suspicious — possibly counterfeit, possibly re-marked.</p>

<h5>Re-stamping evidence</h5>

<p>One of the most common physical SUP indicators. The bad actor obtained a part with the wrong markings (or unmarked) and stamped over with the desired markings. Signs:</p>

<ul>
  <li>Visible grinding marks <em>under</em> the new stamping — the original mark was ground off before re-stamping</li>
  <li>Surface finish disturbance around the stamping area</li>
  <li>Stamping depth inconsistent with the surrounding metal\'s surface — over-stamping leaves a different witness than original factory marking</li>
  <li>Two layers of stamping at different angles, partially visible</li>
  <li>Stamping that crosses the original\'s position with characters of different style or depth</li>
</ul>

<p>If you can see evidence that a part was previously marked differently, the identity of the part — and therefore its life history, conformity, and approval status — is in question.</p>

<h5>Position drift</h5>

<p>OEMs place markings at consistent locations relative to part features. The part number on a CFM56 HPT blade is in a specific spot. Pratt &amp; Whitney has different conventions. A counterfeit may place the markings at approximately the right location but off by a few millimeters or rotated by a few degrees — small enough that the casual observer doesn\'t notice, large enough to compare unfavorably against a reference.</p>

<h5>Paint-fill</h5>

<p>Some OEMs paint-fill stamping for visibility. The color of paint-fill is part of the OEM\'s signature. A part marked with red paint-fill where the OEM uses white is suspicious. The paint-fill itself can be examined — original factory paint-fill is uniform and well-bonded; counterfeit paint-fill is often uneven, bleeding, or showing brush marks.</p>

<h4>Surface finish — manufacturing-fingerprint analysis</h4>

<p>Modern CNC machining leaves distinctive surface patterns. The tool path, the feed rate, the tool geometry — all combine to produce a finish that experienced inspectors recognize. Counterfeit manufacturing using different tooling or different machining parameters produces a visibly different finish.</p>

<h5>Machining marks</h5>

<p>OEM machining produces consistent tool-path patterns. The marks on a turbine blade airfoil have characteristic direction, spacing, and depth based on the OEM\'s manufacturing process. A counterfeit produced on different equipment will have different mark patterns — sometimes obviously different (cross-hatching vs. parallel), sometimes subtly different (mark angle off by 5 degrees).</p>

<h5>Plating</h5>

<p>Many aviation parts are plated — cadmium, zinc, nickel, chromium, others. OEM plating is consistent in thickness, color, and edge characteristics. Re-plated or off-spec plating typically shows:</p>

<ul>
  <li>Thickness variation visible at edges</li>
  <li>Edge buildup or runs from improper plating bath operation</li>
  <li>Color shift from spec — chromium that\'s too blue, cadmium that\'s too yellow</li>
  <li>Plating over surface defects that should have been corrected before plating</li>
  <li>Inadequate adhesion — plating that flakes when stressed</li>
</ul>

<h5>Heat tint</h5>

<p>Heat affects metal color. Areas that have been heated during processing have specific tint patterns. A part claimed to be in "new" or "inspected" condition should not show heat tint on areas that wouldn\'t normally be heat-affected. Heat tint in unexpected locations suggests the part has been re-worked — possibly to repair damage, possibly to alter its identity.</p>

<h5>Edge prep</h5>

<p>OEM parts have specific edge treatments — chamfers, radii, fillets. The dimensions of these features are specified in the OEM drawing. A counterfeit or repaired part may have edges that are sharper or rounder than spec, or that have been blended in ways the OEM drawing doesn\'t authorize.</p>

<h4>Material indicators</h4>

<p>The most authoritative material verification is laboratory analysis — spectroscopy, hardness testing, density measurement. These are not routine receiving-inspection steps; they\'re reserved for confirmed-SUP investigation. But several physical indicators of material discrepancy can be observed at the receiving bench.</p>

<h5>Weight</h5>

<p>Different alloys have different densities. A part that weighs significantly off from the expected value (verifiable against the OEM drawing or against a known-good reference) is suspicious. Variations of 5-10% may indicate material substitution; variations beyond 10% are strong indicators.</p>

<p>For an inspector, the practical procedure: weigh the part on a calibrated scale. Compare against the OEM-published weight or against a reference sample. If off significantly, hold for investigation.</p>

<h5>Magnetism</h5>

<p>Some aerospace alloys are non-magnetic — titanium, austenitic stainless steels (300 series), some nickel-base superalloys. If a part that should be non-magnetic is observably magnetic when checked with a small magnet, the material is probably a substitute. Conversely, ferrous alloys should be magnetic; a "steel" part that doesn\'t pick up to a magnet is also suspicious.</p>

<h5>Color and sheen</h5>

<p>Different alloys have different color and sheen even when polished. Titanium has a characteristic grey-blue tone. Inconel has a darker grey. Aluminum is brighter, lighter. Steel alloys vary. With experience an inspector can identify obvious material substitutions visually. The skill is built by handling many parts of known material.</p>

<h5>Sound</h5>

<p>Different alloys ring differently when tapped lightly. A trained ear can sometimes detect material differences by ringing tone. This isn\'t a primary inspection method, but it can catch hollow or laminated parts that should be solid.</p>

<h4>Specific part categories</h4>

<h5>Turbine and compressor blades</h5>

<ul>
  <li>Airfoil dimensions — check against OEM drawing for chord, thickness, twist</li>
  <li>Trailing edge — should be sharp and clean; counterfeit blades often have thicker or asymmetric trailing edges</li>
  <li>Leading edge — should be smooth and free of nicks (unless the part is sold as "as-removed" with documented FOD damage)</li>
  <li>Root geometry — fir-tree or dovetail root must match the OEM specification exactly; the root holds the blade to the disk under enormous centrifugal load</li>
  <li>Cooling holes (for cooled blades) — must be present in the correct number and position, free of obstruction</li>
  <li>Thermal barrier coating (TBC) — color, thickness, adhesion appropriate to OEM spec</li>
</ul>

<h5>Disks (LLPs)</h5>

<ul>
  <li>Disk geometry — bore, web, rim dimensions per OEM drawing</li>
  <li>Surface finish on critical fillets — the disk web-to-rim transition is a fatigue-critical area</li>
  <li>Markings — disks are heavily marked, including part number, serial number, manufacturing date, and material heat number</li>
  <li>No evidence of repair or re-work — disks generally cannot be repaired; any modification is suspect</li>
  <li>Inspection records — disks should have NDT records (eddy current, FPI, ultrasonic) at every overhaul</li>
</ul>

<h5>Bearings</h5>

<ul>
  <li>Race surfaces — should be smooth and free of pitting, brinelling, or scratches</li>
  <li>Roller or ball surfaces — same</li>
  <li>Cage condition — no cracks, no excessive wear</li>
  <li>Markings — OEM bearings have specific marking on race or cage</li>
  <li>Packaging — OEM bearings ship in specific protective packaging; aftermarket packaging suggests aftermarket source</li>
  <li>Preservation oil/grease — should be present, of correct type, not contaminated</li>
</ul>

<h5>Electronic components and assemblies</h5>

<ul>
  <li>Marking quality — OEM-marked chips have laser-etched markings; counterfeit chips often have ink markings or poorly-laser-etched markings</li>
  <li>Surface texture — original semiconductors have characteristic surface texture; refurbished chips often show evidence of cleaning (rougher surface, residue)</li>
  <li>Lead/pin condition — original leads are uniform; refurbished or repackaged components often have damaged or re-tinned leads</li>
  <li>Markings position — OEM marks are at standard positions; counterfeits often have markings off-center or askew</li>
  <li>Date codes — should be plausible for the part\'s claimed production period</li>
</ul>

<h5>Fasteners</h5>

<ul>
  <li>Head marking — most aviation fasteners are marked on the head with manufacturer, material spec, and grade</li>
  <li>Thread quality — threads should be clean, sharp, uniform; rolled threads (most aerospace fasteners) have characteristic appearance</li>
  <li>Plating uniformity — even coverage including in the thread roots</li>
  <li>Length and head diameter — measure against OEM spec; counterfeit fasteners are sometimes slightly off-dimension</li>
</ul>

<h4>Wear and use signs on a part claimed "new"</h4>

<p>Block 11 of the 8130-3 reports the status. A part marked "NEW" should have no signs of installation or use. Indicators of prior use on a "new" part:</p>

<ul>
  <li>Bearing races showing run-in patterns (slight discoloration in the load-bearing path)</li>
  <li>Bolt threads showing torque marks (compression on the leading flank of the thread)</li>
  <li>Blade leading edges showing FOD nicks or smoothing from gas-path wear</li>
  <li>Carbon deposits on surfaces that would only be carbon-coated from combustion exposure</li>
  <li>Oil staining on parts that would only contact oil during operation</li>
  <li>Heat tint from operating temperatures on parts that shouldn\'t have been heated except in service</li>
  <li>Wear marks from contact with adjacent parts that would only occur during assembled operation</li>
  <li>Residue from cleaning solvents indicating the part was cleaned to remove evidence of use</li>
</ul>

<p>A "NEW" part with any of these indicators is misrepresented. The Block 11 status is wrong — either through documentation fraud or through error. Either way, the disposition is at minimum Quarantine pending investigation.</p>

<h4>Packaging examination</h4>

<p>Most OEMs ship in characteristic packaging. The packaging is part of the manufacturing fingerprint:</p>

<ul>
  <li>OEM-branded boxes with specific printing and graphics</li>
  <li>Specific foam inserts, molded for the part</li>
  <li>ESD-safe bags (pink poly, silver shielded) for electronic components</li>
  <li>Anti-corrosion treatment (oil coating, VCI paper) for ferrous parts</li>
  <li>Desiccant packs in sealed packages for moisture-sensitive items</li>
  <li>OEM serial labels with barcodes</li>
</ul>

<p>Signs that packaging has been tampered with or substituted:</p>

<ul>
  <li>Tape residue suggesting the box was opened and re-sealed</li>
  <li>OEM label over a different (covered) label — peel-back may reveal the original</li>
  <li>Generic packaging where OEM packaging would be expected</li>
  <li>Foam inserts that don\'t fit the part well (suggesting the foam is from a different part)</li>
  <li>Preservation that doesn\'t match expectation (wrong oil type, missing desiccant)</li>
  <li>Label printing that looks home-printed (laser-printed labels on aviation parts are uncommon; OEMs use specific label-printing systems)</li>
</ul>

<h4>When to escalate to laboratory analysis</h4>

<p>The receiving inspector\'s physical inspection is not the same as a laboratory analysis. The inspector identifies indicators worth investigating; the investigation may require laboratory work. The QA Manager authorizes laboratory analysis when:</p>

<ul>
  <li>The part is high-value enough to justify the cost of analysis</li>
  <li>The pattern of indicators is significant enough that confirming or refuting the suspicion changes inventory decisions</li>
  <li>Other distributors or the FAA need confirmation for a broader investigation</li>
</ul>

<p>Common laboratory techniques:</p>

<ul>
  <li><strong>X-ray fluorescence (XRF):</strong> elemental analysis to confirm alloy composition. Non-destructive. Quick.</li>
  <li><strong>Spectroscopy (optical emission, mass spec):</strong> more detailed elemental analysis. May require sample preparation.</li>
  <li><strong>Hardness testing:</strong> Rockwell or Brinell hardness as a property of heat treatment.</li>
  <li><strong>Dimensional inspection:</strong> coordinate measuring machine (CMM) or specialized fixtures verifying dimensions against the OEM drawing.</li>
  <li><strong>Metallography:</strong> microscopic examination of polished cross-sections to verify microstructure.</li>
  <li><strong>Non-destructive testing (NDT):</strong> ultrasonic, eddy current, fluorescent penetrant for surface and subsurface flaw detection.</li>
  <li><strong>Industrial CT scanning:</strong> X-ray computed tomography to visualize internal features without cutting the part.</li>
</ul>

<p>TurbineWorks may not have all these capabilities in-house. Several commercial laboratories specialize in aerospace materials analysis and can perform these tests on a contract basis.</p>

<h4>The receiving inspector\'s contribution: pattern recognition</h4>

<p>The single most valuable skill in physical receiving inspection is pattern recognition. An inspector who has examined hundreds of CFM56 HPT blades develops an internal model of what a real blade looks like — the marking style, surface finish, edge prep, weight feel. When a counterfeit or substituted blade arrives, the deviation from the internal model is detected even before specific indicators are consciously identified.</p>

<p>Building this skill requires exposure. Inspectors should handle as many genuine parts as possible, photograph them, document their characteristics. Reference samples from known-good sources are valuable training tools. Side-by-side comparison of a suspect part with a reference part of the same type is often the fastest way to identify what specifically is off.</p>

<h4>Self-check</h4>

<ol>
  <li>Why does physical inspection matter even when documentation review has been completed and the documents verify?</li>
  <li>Name three specific aspects of OEM markings that can be compared against a reference part.</li>
  <li>What does "re-stamping evidence" look like? Why is it a strong SUP indicator?</li>
  <li>How can weight be used as a material-verification check at the receiving bench?</li>
  <li>What does it mean for a part to be "magnetic when it should not be"?</li>
  <li>List four indicators that a part claimed "NEW" has actually been in service.</li>
  <li>When does the QA Manager authorize laboratory analysis of a part?</li>
  <li>Why is pattern recognition the most important receiving-inspection skill?</li>
</ol>

<p><strong>Bottom line:</strong> physical inspection complements documentation review. Documentation review catches most SUPs; physical inspection catches the ones where the bad actor invested in convincing paper but cut corners on the part. The receiving inspector\'s job is to identify indicators worthy of investigation — not to declare definitively unapproved or counterfeit. When in doubt, quarantine and let the QA Manager and (where needed) laboratory analysis make the call.</p>
HTML
        ],
        [
            'name'  => 'Lesson 1.5 — Reporting a Suspected Unapproved Part',
            'intro' => '<p>The complete reporting workflow from initial identification through FAA filing, customer notification, criminal referral (when applicable), and corrective action. The process that turns a single SUP catch into supply-chain protection for the entire industry.</p>',
            'content' => <<<'HTML'
<h3>Reporting a Suspected Unapproved Part</h3>

<p>Identifying a SUP at receiving inspection is the start of the process, not the end. Without proper reporting, an SUP catch helps only TurbineWorks — the same supplier may continue selling fraudulent parts to other distributors, the same diverted scrap may continue circulating, the same counterfeit manufacturer may continue producing. The reporting process is what turns a single catch into broader industry protection.</p>

<p>This lesson walks through the complete reporting workflow: who does what, when, in what form, with what records, under what confidentiality constraints. By the end of the lesson, an inspector should understand not just their own role but the full system that their SUP identification triggers.</p>

<h4>Why reporting matters operationally</h4>

<p>A SUP that TurbineWorks catches and quietly returns to the supplier has done almost nothing for safety. The supplier simply re-routes the part to a less rigorous distributor. The part eventually finds a home in someone\'s inventory and then on someone\'s aircraft. The TurbineWorks catch was effort wasted.</p>

<p>A SUP that TurbineWorks catches and reports through the proper channels has multiple positive effects:</p>

<ul>
  <li>The FAA SUP Program Office aggregates the report into industry-wide intelligence. Patterns across multiple reports identify problem suppliers, problem part types, and emerging fraud techniques.</li>
  <li>Other distributors and operators may receive FAA alerts about the supplier or part type, defending the broader industry.</li>
  <li>The accreditation organization (ASA) may flag the supplier for additional scrutiny in its own oversight work.</li>
  <li>Criminal investigators (FBI, DOD IG) can build cases against deliberate fraud operations. The SUP report is part of the evidence base.</li>
  <li>TurbineWorks own corrective-action work strengthens the receiving inspection process, reducing future SUP escape risk.</li>
</ul>

<p>Reporting is also an ASA-100 obligation. ASA-100 §10 requires documented complaint and non-conformance handling, including external reporting where warranted. An ASA auditor will check whether SUP catches have been properly reported — failure to report is a finding by itself.</p>

<h4>Step 1 — Quarantine and Preserve (Immediate)</h4>

<p>This step is performed by the receiving inspector at the moment of SUP identification. It should be done before any other action.</p>

<h5>Physical movement</h5>

<p>Move the suspect part and all associated documentation to the designated Quarantine area. The Quarantine area is physically separate from serviceable inventory — it should be marked, ideally locked or otherwise access-controlled, and easily identifiable to anyone walking the warehouse.</p>

<h5>Tagging</h5>

<p>Apply a TurbineWorks Quarantine tag to the part. The tag should record:</p>

<ul>
  <li>Date of quarantine</li>
  <li>Receiving inspector name and signature</li>
  <li>Reason for quarantine (the specific red flag — Block 15 not in database, photocopied signature, serial number mismatch, etc.)</li>
  <li>Reference to the corresponding receiving inspection record</li>
  <li>"DO NOT SHIP" or equivalent warning</li>
</ul>

<h5>Document preservation</h5>

<p>The original physical paperwork that accompanied the part is now evidence. Specifically:</p>

<ul>
  <li><strong>Do not write on, stamp, or annotate the original documents.</strong> Annotations are alterations of evidence.</li>
  <li><strong>Do not photocopy the originals and discard them.</strong> The originals must be retained.</li>
  <li><strong>Do not return the originals to the supplier.</strong> Once returned, you have no evidence.</li>
  <li>You may photocopy for your internal file, but the originals stay in TurbineWorks custody.</li>
  <li>Original packaging materials should also be preserved — packaging labels, dunnage labels, shipping documents that came with the package.</li>
</ul>

<h5>Photographic documentation</h5>

<p>Take photographs of:</p>

<ul>
  <li>The part as received (multiple angles)</li>
  <li>The specific markings or features that flagged the suspicion</li>
  <li>The original documentation (each page)</li>
  <li>The packaging</li>
  <li>The shipping label and any external markings</li>
</ul>

<p>Photographs are evidence and they support the investigation. They\'re also useful if the part is eventually returned to the FAA or to another investigator — they document the as-received condition.</p>

<h4>Step 2 — Internal Escalation (Same Business Day)</h4>

<p>The receiving inspector notifies the Quality Assurance Manager in writing the same business day. "In writing" means email or a formal NCR (Non-Conformance Report) entry — not just a verbal mention in passing. The written notification creates the audit trail.</p>

<p>The notification should include:</p>

<ul>
  <li>Date and time of receipt and quarantine</li>
  <li>Part number and serial number</li>
  <li>Supplier name and the TurbineWorks PO reference</li>
  <li>Specific red flag(s) observed — be precise: "Block 15 certificate number 5P3R784D not found in FAA av-info.faa.gov database"</li>
  <li>Photographs (attached or referenced)</li>
  <li>Reference number for the quarantine and NCR records</li>
</ul>

<p>The QA Manager owns the investigation from this point forward. The receiving inspector\'s primary role is complete after this notification, although the inspector may be asked for additional observations as the investigation proceeds.</p>

<h4>Step 3 — Verification with the Issuing Organization (QA Manager)</h4>

<p>The QA Manager contacts the organization listed in Block 4 of the 8130-3 to verify the documentation. This contact must use <strong>independently sourced</strong> contact information — not the contact information that came with the shipment. Why? Because if the documentation is fraudulent, the supplier may have provided fake "verification" contact information that goes to a confederate.</p>

<p>Sources of independent contact information:</p>

<ul>
  <li>FAA av-info.faa.gov database — typically lists the certificate holder\'s registered address and contact</li>
  <li>OEM customer service directories (for OEM-issued tags)</li>
  <li>Industry association directories (ASA member directory, others)</li>
  <li>Direct web search to find the organization\'s official website and contact</li>
</ul>

<p>The verification question to ask: "Did your organization issue an FAA 8130-3 with tracking number [Block 3] for part number [Block 8], serial number [Block 10], on [Block 17 date]?"</p>

<p>The issuing organization\'s response is determinative:</p>

<ul>
  <li><strong>Yes, we issued that tag, here are our records:</strong> documentation verifies. The investigation may need to look at other indicators (physical condition, supplier qualification, etc.), but the paperwork is real.</li>
  <li><strong>No, we did not issue any tag with that tracking number:</strong> the tag is fabricated. SUP is confirmed. The investigation now expands to identify the source of the fabrication.</li>
  <li><strong>We issued a tag with that tracking number but for a different part:</strong> the tag has been re-used or altered. SUP confirmed. Possibly evidence of broader documentation laundering.</li>
  <li><strong>We did issue that tag but our records show it was issued for an installation, not a sale:</strong> the part may have been stolen or improperly diverted from a maintenance event. Investigate the chain of custody.</li>
</ul>

<p>The conversation with the issuing organization may itself yield investigative leads. The organization may have already received other inquiries about the same tag (suggesting a broader pattern), or may be willing to share what they know about the supplier or part.</p>

<h4>Step 4 — FAA Form 8120-11 (QA Manager Files)</h4>

<p>When the investigation has accumulated sufficient evidence — typically after the issuing-organization verification — the QA Manager files FAA Form 8120-11, Suspected Unapproved Parts Notification.</p>

<h5>The form: field by field</h5>

<ul>
  <li><strong>Reporter information:</strong> name, organization (TurbineWorks), address, phone, email, role</li>
  <li><strong>Part identification:</strong> part number, serial number, manufacturer name, OEM model the part fits</li>
  <li><strong>Quantity:</strong> number of suspect parts in the shipment</li>
  <li><strong>Documentation:</strong> description of the documentation that accompanied the part — 8130-3 tracking number, issuing organization claimed in Block 4, dates</li>
  <li><strong>Source:</strong> the supplier from which TurbineWorks received the part. Include their address, contact information, and any reference numbers (their packing list, their invoice, etc.)</li>
  <li><strong>Reasons for suspicion:</strong> the specific indicators that triggered SUP status. Be detailed — "Block 15 certificate number 5P3R784D not found in FAA av-info.faa.gov database; issuing organization claimed in Block 4 (Northeast Aviation Services) contacted via FAA-listed phone and confirmed they did not issue this tag."</li>
  <li><strong>Current disposition:</strong> "Part in Quarantine at TurbineWorks, Address X, Y, Z. Documentation preserved in original form."</li>
  <li><strong>Date of receipt and date of notification</strong></li>
  <li><strong>Signature</strong> of the person filing</li>
</ul>

<h5>How to submit</h5>

<p>Form 8120-11 can be submitted to the FAA SUP Program Office by:</p>

<ul>
  <li>Email to the FAA SUP Program Office address (published at <a href="https://www.faa.gov/aircraft/safety/programs/sups/" target="_blank" rel="noopener">faa.gov/aircraft/safety/programs/sups/</a>)</li>
  <li>Mail to the FAA SUP Program Office address</li>
  <li>Fax (legacy option, still accepted)</li>
  <li>The FAA Aviation Safety Hotline for urgent cases</li>
</ul>

<p>TurbineWorks retains a copy of the filed form, along with all supporting evidence (photographs, the original documentation, the receiving inspection record, the issuing-organization verification correspondence).</p>

<h5>What happens after filing</h5>

<p>The FAA SUP Program Office triages incoming reports based on safety severity and pattern indicators. The Office may:</p>

<ul>
  <li>Acknowledge receipt and add the report to their database without immediate further action (typical for isolated reports)</li>
  <li>Contact TurbineWorks for additional information</li>
  <li>Request that the physical part be made available for FAA inspection or transfer to FAA custody</li>
  <li>Refer the matter to FAA enforcement for action against the supplier or others in the chain</li>
  <li>Refer the matter to criminal authorities (FBI, US Attorney) when fraud is indicated</li>
  <li>Issue an FAA Safety Alert for Operators (SAFO) or similar broader-industry notification if the pattern warrants</li>
</ul>

<p>TurbineWorks may not learn what happened next. The FAA does not always report back to the reporter. This is normal. The obligation is to file; the FAA disposition is the FAA\'s.</p>

<h4>Step 5 — Customer Notification (When Applicable)</h4>

<p>If the SUP was being procured on behalf of a specific TurbineWorks customer (a back-to-back order rather than stock), the customer must be notified. If the part type or supplier matches anything TurbineWorks has already shipped to other customers, those customers should be notified as well so they can investigate their own inventory.</p>

<h5>Notification content</h5>

<ul>
  <li>Description of the SUP discovery</li>
  <li>Part numbers, serial numbers, and shipments potentially affected</li>
  <li>What TurbineWorks is doing (FAA notification, investigation, corrective action)</li>
  <li>What the customer should do (inspect their inventory, quarantine pending verification)</li>
  <li>Contact at TurbineWorks for follow-up questions</li>
</ul>

<h5>Why this is hard</h5>

<p>Customer notification is uncomfortable. The customer may have already installed the affected part on an aircraft. They may need to remove and replace it. They may need to ground aircraft pending replacement. These are expensive consequences and the customer\'s frustration is directed at TurbineWorks even though TurbineWorks caught the SUP and acted on it.</p>

<p>Hiding a SUP discovery to avoid customer impact is not an option:</p>

<ul>
  <li>It is a regulatory violation under ASA-100 §10 — affected customers must be notified</li>
  <li>It exposes TurbineWorks to civil liability if the unreported SUP causes a subsequent incident</li>
  <li>It exposes TurbineWorks to criminal liability if the failure to notify is deemed willful concealment</li>
  <li>When the customer eventually discovers the issue (and they will), the loss of trust is far greater than the loss of trust from proactive disclosure</li>
</ul>

<h5>The right way to handle customer notification</h5>

<ul>
  <li>The QA Manager (not Sales) leads the customer notification</li>
  <li>Notification is in writing, with verbal follow-up by phone</li>
  <li>Tone is factual and matter-of-fact — not apologetic to the point of admitting liability, not defensive to the point of evasion</li>
  <li>Emphasize what TurbineWorks is doing to investigate and prevent recurrence</li>
  <li>Offer to assist with the customer\'s investigation — provide records, take their calls promptly, provide expert assistance</li>
</ul>

<h4>Step 6 — Disposition of the SUP (After Investigation)</h4>

<p>The SUP remains in Quarantine during the investigation. Disposition occurs only after the FAA SUP Program Office\'s investigation concludes or after TurbineWorks has sufficient internal evidence to disposition independently.</p>

<h5>Disposition options</h5>

<ul>
  <li><strong>Confirmed unapproved:</strong> the part is mutilated per FAA AC 21-38. The mutilation is witnessed by the QA Manager (or designate) and documented. The mutilation record is retained as part of the quality records.</li>
  <li><strong>FAA requests the part as evidence:</strong> the part is transferred to FAA custody with a receipt. TurbineWorks retains the receipt as evidence of transfer.</li>
  <li><strong>Suspicion not confirmed:</strong> the part is released from quarantine. The corrective-action record documents why suspicion was raised and why it was ultimately not confirmed. The part may be returned to the supplier (with documentation) or re-inspected for serviceable inventory.</li>
  <li><strong>Inconclusive:</strong> investigation cannot definitively confirm or refute. The part remains in quarantine or is mutilated as a conservative disposition. Records document the reasoning.</li>
</ul>

<h4>Step 7 — Corrective Action</h4>

<p>Every SUP triggers a corrective-action investigation focused on TurbineWorks\' own processes:</p>

<ul>
  <li><strong>How did this supplier enter TurbineWorks\' supply chain?</strong> Was qualification adequate? Was qualification skipped?</li>
  <li><strong>Should the supplier be removed from the approved-supplier list?</strong> Confirmed SUP from a supplier is typically grounds for removal. Suspected-but-not-confirmed SUP may warrant supplier reassessment.</li>
  <li><strong>Are there other parts from this supplier in TurbineWorks inventory that need re-verification?</strong> Hold all stock from the supplier pending re-verification of each item.</li>
  <li><strong>Did the receiving inspection process catch this, or did the part slip through?</strong> If caught: the process worked, document and reinforce. If slipped through: process gap to be closed.</li>
  <li><strong>What process change prevents recurrence?</strong> Specific procedural change, not just "be more careful." Examples: require pre-receipt FAA database check for all new suppliers; add second-inspector review for parts above threshold value; require photographic documentation of every receiving inspection.</li>
  <li><strong>How is the change implemented and verified?</strong> Procedure revision, training delivered, audit confirms effectiveness.</li>
</ul>

<p>Corrective action is documented in the QMS, reviewed by the QA Manager, and eventually closed when effectiveness is verified. The corrective-action file becomes part of the quality records reviewed at the next ASA-100 audit.</p>

<h4>Confidentiality During Investigation</h4>

<p>SUP investigations are sensitive for multiple reasons:</p>

<ul>
  <li>The supplier may turn out to be a victim (downstream of fraud upstream from them). Publicly identifying them as a "SUP source" could be defamation.</li>
  <li>The supplier may be the perpetrator. Public disclosure could compromise FBI investigation or allow the perpetrator to destroy evidence.</li>
  <li>The customer relationship is sensitive. The customer being notified about a SUP wants discretion.</li>
  <li>Other distributors who may have received parts from the same supplier need to be informed through proper FAA channels, not through informal industry gossip.</li>
</ul>

<p>Practical rules:</p>

<ul>
  <li>Discuss the investigation only within QA leadership and with the FAA / law enforcement as the chain develops</li>
  <li>Do not post about the investigation on social media or industry forums</li>
  <li>Do not name the supplier in casual industry conversation</li>
  <li>Do not discuss the customer notification with anyone outside the relationship</li>
  <li>If asked by other distributors whether you\'ve heard anything about supplier X, refer them to the FAA SUP Program Office</li>
</ul>

<h4>Recordkeeping</h4>

<p>SUP files include:</p>

<ul>
  <li>The original receiving inspection record</li>
  <li>The quarantine tag (or copy of it)</li>
  <li>Photographs of the part, documentation, and packaging as received</li>
  <li>The original 8130-3 and accompanying documents (in physical form)</li>
  <li>Internal escalation correspondence (email, NCR entry)</li>
  <li>Verification correspondence with the issuing organization</li>
  <li>The FAA Form 8120-11 (filed copy)</li>
  <li>Customer notification correspondence</li>
  <li>Investigation findings and disposition</li>
  <li>Corrective action record and effectiveness verification</li>
  <li>Mutilation record (if part was destroyed)</li>
  <li>FAA correspondence (if any)</li>
</ul>

<p>SUP files are retained for the standard records-retention period (typically 7+ years for ASA-100), often longer because SUP files may become evidence in future enforcement or criminal proceedings. Some distributors retain SUP files permanently.</p>

<h4>Real industry examples</h4>

<p>(The following are composite examples illustrating typical SUP-reporting outcomes. Specific details abstracted for confidentiality.)</p>

<h5>Example 1 — Verified, FAA enforcement</h5>
<p>A distributor received bearings from a new supplier at 60% of typical market price. Block 15 verified in the FAA database but Block 4 organization, when contacted, confirmed they had not issued the tag with the indicated tracking number — their actual recently-issued tag bearing that number was for a completely different part. The supplier was a documentation-fraud operation that had stolen the certificate number from another organization\'s public records. The FAA pursued enforcement against the supplier, who eventually had their (limited) FAA approvals revoked and was referred for criminal prosecution.</p>

<h5>Example 2 — Verified, broader industry alert</h5>
<p>A distributor received turbine blades whose markings showed re-stamping evidence. Investigation confirmed the blades had been scrapped at an MRO 18 months earlier, mutilation had been improperly performed (data plate removed but blade itself intact), and the blades had been diverted by an employee. The FAA investigation expanded when reports came in from three other distributors who had received blades from the same scrapyard intermediary. An industry alert was issued. The intermediary was prosecuted; the MRO restructured its mutilation procedures.</p>

<h5>Example 3 — Suspicion not confirmed</h5>
<p>A distributor flagged an 8130-3 because Block 17 dated the tag 14 months before the shipment date. Investigation revealed a legitimate explanation — the part had been in long-term sealed storage at an aircraft retirement facility, had not been re-tagged because no work had been performed on it in the intervening period, and the storage facility had records confirming the chain of custody. The part was released from quarantine after the investigation. The receiving inspector\'s flag was appropriate; the investigation cleared it.</p>

<h4>Self-check</h4>

<ol>
  <li>What is the very first thing to do when you identify a SUP?</li>
  <li>Why must the original documentation NOT be returned to the supplier or annotated?</li>
  <li>What information should be included in the same-business-day notification to the QA Manager?</li>
  <li>Why must the issuing-organization verification use independently sourced contact information?</li>
  <li>Name three pieces of information FAA Form 8120-11 requires.</li>
  <li>When and how must customers be notified?</li>
  <li>What are the four possible dispositions of a SUP after investigation?</li>
  <li>Why is confidentiality during SUP investigation important?</li>
  <li>What corrective-action questions does every SUP discovery raise about TurbineWorks\' own processes?</li>
</ol>

<p><em>[TurbineWorks Procedure Reference: insert TurbineWorks SUP-handling SOP and the designated QA contact for FAA filings here. Also insert the customer-notification template letter and the corrective-action workflow reference.]</em></p>
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
            'intro' => '<p>The structure, scope, and significance of receiving inspection. Why ASA-100 §6 is the section auditors scrutinize most, and what it requires.</p>',
            'content' => <<<'HTML'
<h3>ASA-100 §6 Receiving Inspection</h3>

<p>Receiving inspection is the single highest-leverage quality control point in a parts distributor\'s operation. Every part that ever leaves TurbineWorks went through receiving first. Every defect that ever reaches a customer was either created at TurbineWorks (storage damage, mishandling) or escaped detection at receiving. The receiving inspection function is therefore the place where the entire ASA-100 quality system either works or doesn\'t.</p>

<p>This lesson establishes the framework. Subsequent lessons in Module 2 walk through the operational steps in detail. By the end of this lesson you should understand what receiving inspection is supposed to accomplish, who is authorized to perform it, where it happens, what records it creates, and why an ASA auditor focuses here more than almost anywhere else.</p>

<h4>Why receiving inspection matters disproportionately</h4>

<p>Consider the alternatives. If a defect is going to be caught somewhere in the supply chain, where is the best place?</p>

<ul>
  <li><strong>At the original manufacturer:</strong> the OEM\'s production QC catches manufacturing defects. But OEM QC has no visibility into what happens to the part after it leaves the factory — storage damage, transit damage, fraudulent re-marking, scrap diversion. The OEM\'s scope ends at delivery.</li>
  <li><strong>At the first downstream buyer (typically the operator):</strong> would catch defects but at significant cost — the part may have already been shipped, the operator now has the disposal burden, the original supplier is harder to hold accountable.</li>
  <li><strong>At the customer (further downstream):</strong> worst case. The defective part may have already been installed on an aircraft. Removal and replacement is expensive. Investigation has to reach back through multiple intermediaries to identify the source.</li>
  <li><strong>At TurbineWorks receiving:</strong> the optimal point. Before the part enters inventory, before it touches a customer\'s aircraft, before it complicates downstream operations.</li>
</ul>

<p>This is why ASA-100 puts so much weight on §6, and why auditors give it disproportionate attention. The receiving inspection is the chokepoint where a distributor either provides value (catching defects before customers see them) or fails to provide value (letting defects pass through).</p>

<h4>What §6 actually requires</h4>

<p>ASA-100 §6 (current revision) establishes the framework. The exact section numbering and wording can shift with revisions, but the substantive requirements are consistent. At minimum, the standard requires:</p>

<ol>
  <li>A documented receiving inspection procedure</li>
  <li>Designated personnel authorized to perform receiving inspection</li>
  <li>A physically segregated receiving area separate from serviceable inventory</li>
  <li>Documentation verification appropriate to the part type</li>
  <li>Physical inspection of the part</li>
  <li>A disposition decision (accept / quarantine / reject) recorded on every receipt</li>
  <li>Receiving inspection records retained per the records-retention schedule</li>
  <li>Non-conforming material handling procedure for quarantine and reject dispositions</li>
</ol>

<p>The TurbineWorks QAM implements these requirements with specific procedures, forms, and roles. The auditor evaluates whether the QAM addresses each requirement AND whether actual practice matches the QAM.</p>

<h4>The five-step receiving inspection sequence</h4>

<p>The §6 framework, translated into the operational sequence TurbineWorks performs:</p>

<ol>
  <li><strong>Pre-receiving verification.</strong> Before opening packaging, confirm the shipment matches a current TurbineWorks Purchase Order, the supplier is on the approved-supplier list, and required documentation is present. This step catches obvious problems — wrong shipment, unapproved supplier, missing paperwork — without disturbing the physical part. Detailed in Lesson 2.2.</li>

  <li><strong>Documentation review.</strong> The 8130-3 (or equivalent), Certificate of Conformance, packing list, material certifications, country-of-origin documentation, and any other required documents are reviewed for completeness, internal consistency, and external verification. The block-by-block walkthrough is in Lesson 2.3.</li>

  <li><strong>Physical inspection.</strong> The actual part is examined — quantity count, visual condition, markings, packaging integrity. Depth of physical inspection depends on the part type and TurbineWorks procedure. Detailed in Lesson 2.4.</li>

  <li><strong>Disposition decision.</strong> Based on documentation review and physical inspection, the inspector decides: Accept (move to serviceable inventory), Quarantine (hold pending investigation), or Reject (return to supplier or destroy). This decision is the inspector\'s authority and cannot be overridden by sales or management. Detailed in Lesson 2.5.</li>

  <li><strong>Recordkeeping.</strong> The receiving inspection record is completed, signed, and filed. The record ties the physical part to the documentation chain and to TurbineWorks\' inventory records. Recordkeeping requirements are detailed in Module 5 but the receiving inspection record is created here.</li>
</ol>

<p>Each step has its own controls, criteria, and failure modes. Skipping or rushing any step compromises the integrity of the whole receiving inspection.</p>

<h4>Who is authorized — the Receiving Inspector role</h4>

<p>Only employees explicitly designated in the QAM as Receiving Inspectors may perform receiving inspection. This is not interchangeable with other warehouse roles. The designation is formal — the QAM lists the people by name (or by position with an attached current-personnel roster), and each named inspector has a documented qualification path.</p>

<h5>Qualifications</h5>

<ul>
  <li>Completion of TurbineWorks University ASA-100 Initial Training (all 8 modules including this Module 2)</li>
  <li>On-the-job training documented on form TWF-4 (or its digital equivalent in Moodle)</li>
  <li>Demonstrated competency — typically through a supervised period of receiving inspections, evaluated by the QA Manager before full authorization</li>
  <li>Recurring training every 6 months per the TurbineWorks training cadence</li>
  <li>No regulatory or disciplinary issues that would compromise the integrity of the inspector role</li>
</ul>

<h5>The independence principle</h5>

<p>Receiving Inspectors have <strong>independent authority</strong> over their accept/quarantine/reject decisions. This is a foundational ASA-100 principle. The inspector\'s decision cannot be overridden by:</p>

<ul>
  <li>The Sales team because a customer is waiting</li>
  <li>Operations because schedule pressure is high</li>
  <li>Senior management because of business pressure</li>
  <li>The Accountable Manager except to authorize a re-inspection by a different inspector (which is itself an unusual step and must be documented)</li>
</ul>

<p>The Quality Assurance Manager can <em>review</em> an inspector\'s quarantine decision and authorize disposition after investigation — but the inspector\'s authority to <em>quarantine</em> in the first place is not overridable. An auditor will probe this in interviews: "Has anyone ever asked you to release a quarantined part without a documented QA Manager disposition?" The expected answer is no.</p>

<h5>What happens if an inspector\'s judgment is found wanting</h5>

<p>If an inspector\'s decisions repeatedly diverge from what the QA Manager believes is correct — too many false alarms, or worse, releases that should have been quarantined — the response is training and process, not pressure. The inspector receives additional training, possibly works under supervision for a period, and may be removed from the Receiving Inspector designation if competency cannot be re-established. The independence principle protects inspectors from pressure but does not protect them from accountability for technical competency.</p>

<h4>Where receiving inspection happens — the physical environment</h4>

<p>The Receiving area is a designated, physically segregated zone:</p>

<ul>
  <li><strong>Separated from serviceable inventory.</strong> Parts in receiving cannot accidentally drift into serviceable storage. The boundary is clear — marked on the floor, signed, sometimes physically partitioned.</li>
  <li><strong>Adequate workspace.</strong> Receiving Inspectors need bench space to lay out parts and documentation, lighting to examine markings, and reference materials accessible (IPCs, current OEM service bulletin lists, the FAA database via internet access).</li>
  <li><strong>Controlled environment if applicable.</strong> If the receiving area handles ESD-sensitive parts, the area is an EPA per Module 7 requirements. If it handles hazmat, the appropriate hazmat controls apply.</li>
  <li><strong>Access controlled.</strong> Not everyone in the building has the authority to enter the receiving area unaccompanied. Visitors are escorted.</li>
  <li><strong>FOD-controlled.</strong> Receiving is a FOD-Free Zone with the practices established in Module 4 — tool control, no loose hardware, FOD walks.</li>
</ul>

<p>The auditor will walk the receiving area during the on-site audit. The auditor will look for visible signs that the area is in active use as a quality control zone, not just a place where boxes are opened.</p>

<h4>What records are created</h4>

<p>Every receiving inspection generates records. The standard package:</p>

<ul>
  <li><strong>Receiving Inspection Record</strong> — the form (TurbineWorks-specific, referenced in the QAM) documenting the inspector\'s findings and disposition. Includes inspector name and signature, date, part identification, supplier reference, PO reference, observations, and disposition.</li>
  <li><strong>Copy of the 8130-3</strong> (or equivalent) filed against the part record. The original physical 8130-3 stays in TurbineWorks records; copies travel with the part as needed.</li>
  <li><strong>Copy of the COC</strong> if a COC was required by the PO and provided by the supplier.</li>
  <li><strong>Material certifications</strong> if applicable.</li>
  <li><strong>Photographs</strong> documenting any non-conformance observed, or for high-value items as a routine "as-received" record.</li>
  <li><strong>Cross-references</strong> to the supplier qualification record (confirming supplier was approved at time of receipt) and to the PO record (confirming order was authorized).</li>
</ul>

<p>The receiving inspection record is the primary audit-evidence artifact. When an auditor pulls a random serviceable part and asks "show me this part\'s receiving inspection," this is the record that comes out of the file. If the record is missing, incomplete, or doesn\'t match the part, that is an audit finding.</p>

<h4>What an ASA auditor specifically checks at §6</h4>

<p>ASA auditors have well-developed playbooks for §6. Expected activities during the on-site audit:</p>

<ul>
  <li><strong>Observation of a real receiving inspection.</strong> The auditor watches a current receipt from start to finish, without interrupting except to ask clarifying questions afterward. The auditor looks at: does the inspector follow the documented procedure? Are required documents reviewed? Is the disposition decision made and recorded?</li>
  <li><strong>Personnel interview.</strong> The auditor speaks with the Receiving Inspector(s). Questions: Where is your authority documented? What training have you received? Walk me through your last quarantine decision. Has anyone ever pressured you to release a part you wanted to quarantine?</li>
  <li><strong>Records pull and audit.</strong> The auditor selects parts from serviceable inventory at random and asks to see their complete receiving inspection records. The records must be retrievable within minutes, complete, and signed by an authorized inspector.</li>
  <li><strong>Verification of inspector authorization.</strong> The inspector named on a record must be currently authorized in the QAM. An inspection signed by someone who is not designated is a finding.</li>
  <li><strong>Physical segregation check.</strong> The auditor walks the receiving area and confirms it is segregated from serviceable inventory. Comments unprompted on whether the segregation is visible and effective.</li>
  <li><strong>Approved-supplier list audit.</strong> The auditor reviews recent receipts to confirm parts came only from approved suppliers, or that the QA Manager documented one-time receipt authorization for any exceptions.</li>
  <li><strong>Non-conformance trail.</strong> The auditor reviews recent quarantine and reject decisions. Were they investigated? Dispositioned properly? Did corrective action follow?</li>
</ul>

<h4>Common ASA-100 §6 audit findings (industry-wide)</h4>

<p>Recurring findings at distributor audits:</p>

<ul>
  <li>Inspector named on records is not currently designated in the QAM (the QAM hasn\'t been updated, or the inspector\'s training is expired)</li>
  <li>Receiving area not physically segregated from serviceable storage (parts mixed)</li>
  <li>Records incomplete — missing signature, missing date, missing PO reference</li>
  <li>Parts in serviceable inventory without a corresponding receiving inspection record</li>
  <li>Approved-supplier list out of date</li>
  <li>One-time receipt from unapproved supplier without documented QA Manager authorization</li>
  <li>Quarantine area shared with non-quarantine inventory</li>
  <li>Inspector observed skipping documentation verification under time pressure</li>
  <li>Block 15 FAA database verification not part of the procedure (or not actually done)</li>
</ul>

<p>Avoiding these findings requires the procedure to be in place AND the practice to match. An auditor who finds a deviation between the documented procedure and the observed practice will write a finding even if the practice is technically working.</p>

<h4>The receiving inspection mindset</h4>

<p>A new Receiving Inspector eventually develops an internal mindset for the role. The key elements:</p>

<ul>
  <li><strong>Default skepticism.</strong> Every shipment is treated as if something might be wrong, until verified. The skepticism isn\'t paranoia — it\'s the appropriate professional disposition for the role.</li>
  <li><strong>Pattern recognition.</strong> Over time the inspector recognizes what "normal" looks like for each supplier and part type. Deviations from normal warrant attention.</li>
  <li><strong>Documentation discipline.</strong> Every observation is recorded. Memory is unreliable; records are reliable.</li>
  <li><strong>Independence.</strong> The inspector\'s job is to apply the standard, not to please anyone. When pressure rises, the standard wins.</li>
  <li><strong>Continuous learning.</strong> New SUP patterns emerge. New OEM issues affect parts already in inventory. The inspector reads the FAA SUP Program bulletins, follows industry alerts, and stays current.</li>
</ul>

<h4>Self-check</h4>

<ol>
  <li>What is the 5-step receiving inspection sequence per ASA-100 §6?</li>
  <li>Who is authorized to perform receiving inspection at TurbineWorks?</li>
  <li>What are the qualification requirements for the Receiving Inspector role?</li>
  <li>What is the "independence principle" and why does it matter?</li>
  <li>Why must the receiving area be physically segregated from serviceable inventory?</li>
  <li>What records must be created for every receiving inspection?</li>
  <li>Name three things an ASA auditor specifically checks at §6 during an on-site audit.</li>
  <li>Why is receiving inspection considered the "highest-leverage" quality control point in a distributor\'s operation?</li>
</ol>

<p><em>[TurbineWorks Procedure Reference: insert the TurbineWorks Receiving Inspection SOP reference, the current form designator, and the list of currently-authorized Receiving Inspectors here.]</em></p>
HTML
        ],
        [
            'name'  => 'Lesson 2.2 — Pre-Receiving Verification',
            'intro' => '<p>Everything that happens before the box is opened. The pre-receiving phase catches obvious problems early — wrong shipment, unapproved supplier, missing paperwork — without disturbing the physical part. Done well, it dramatically reduces the burden on the rest of the receiving inspection.</p>',
            'content' => <<<'HTML'
<h3>Pre-Receiving Verification</h3>

<p>Receiving inspection has a sequence for a reason. The most efficient inspection catches the easiest problems first. Pre-receiving — everything before the packaging is opened — handles the kind of issue you can identify without disturbing the part: paperwork mismatches, supplier issues, shipment errors. By resolving these first you avoid wasted effort on parts that shouldn\'t have been received in the first place.</p>

<p>This lesson covers the pre-receiving phase in operational detail. Every step has a purpose, every step has failure modes, and skipping any step creates downstream problems.</p>

<h4>Why open packaging is a one-way door</h4>

<p>Opening a sealed package starts a clock. Once open, the part has been touched, the preservation may have been disturbed, the supplier\'s sealed-shipment guarantee is consumed. If you discover at this point that the shipment shouldn\'t have been opened — wrong customer, wrong PO, unapproved supplier — returning the part to the supplier in its original sealed condition is no longer possible. The supplier may resist returns of opened shipments.</p>

<p>Pre-receiving verification is the way to confirm, before opening, that opening is appropriate. Done correctly, pre-receiving either resolves any concern (clear to open) or holds the package unopened until the concern is investigated.</p>

<h4>Step 1 — Identify the shipment</h4>

<p>When a shipment arrives at the receiving dock, the inspector first documents what showed up. This is the receiving phase before the receiving inspection technically begins — it\'s the intake.</p>

<h5>Information captured</h5>

<ul>
  <li><strong>Carrier and tracking number.</strong> Which carrier delivered, what is their reference number. Useful if there\'s a discrepancy later about what was delivered and when.</li>
  <li><strong>Shipper name and address (from the shipping label).</strong> This is what the carrier was given. It may or may not match the supplier listed on the TurbineWorks PO.</li>
  <li><strong>Receiving party (from the shipping label).</strong> Confirms the shipment was actually addressed to TurbineWorks. Mis-addressed shipments occasionally arrive — confirm before assuming you should accept it.</li>
  <li><strong>Number of pieces / packages in the shipment.</strong> If the PO expected 3 boxes and 2 arrived, that\'s a partial shipment to track.</li>
  <li><strong>Visible damage to outer packaging.</strong> Photograph any visible damage before proceeding. The damage may have happened in transit, in which case the carrier\'s insurance applies. Or the damage may be the carrier\'s evidence of mishandling — important for any subsequent claim.</li>
  <li><strong>Date and time of receipt.</strong> Documented on the receipt record.</li>
  <li><strong>Seal integrity.</strong> If the shipment includes tamper-evident seals or carrier security tape, are they intact? Broken seals on a sealed shipment is a flag — investigate before opening.</li>
</ul>

<h5>What happens if damage or seal issues are observed</h5>

<ul>
  <li>Document with photographs before doing anything else</li>
  <li>Note specifics: which box, what type of damage, what seals are affected</li>
  <li>Decide whether to refuse the shipment, accept under "received damaged" status, or accept and investigate</li>
  <li>The decision depends on the part type and the apparent severity — a crushed corner on a fastener carton may be cosmetic; a punctured ESD bag on an electronic component is serious</li>
  <li>Notify the QA Manager for any non-trivial damage</li>
  <li>Notify the carrier per the carrier-specific damage claim procedure</li>
</ul>

<h4>Step 2 — Match the shipment to a Purchase Order</h4>

<p>Every shipment received at TurbineWorks must correspond to a current authorized PO. Unauthorized shipments — parts arriving without a corresponding PO — are not accepted. The shipment is held in receiving while the situation is investigated.</p>

<h5>The PO matching check</h5>

<ul>
  <li><strong>Does the PO number on the supplier\'s shipping documentation match an open TurbineWorks PO?</strong> The supplier should be referencing the PO number TurbineWorks gave them.</li>
  <li><strong>Does the supplier on the shipping documents match the PO supplier?</strong> Sometimes a supplier sub-contracts manufacturing to another company; in that case the shipping address may not match the PO supplier. This is an issue worth investigating.</li>
  <li><strong>Do the part numbers on the packing list match the PO line items?</strong> Each line on the PO has a specific part number with specific quantity. The packing list should match.</li>
  <li><strong>Do the quantities match?</strong> Full shipment of the PO quantity, or a partial shipment within the PO\'s allowed partial-shipment terms? A quantity higher than the PO is unauthorized — TurbineWorks cannot accept more than ordered.</li>
</ul>

<h5>Common PO mismatch scenarios</h5>

<ul>
  <li><strong>Drop-ship error.</strong> The supplier intended this shipment for a different customer and shipped to TurbineWorks by mistake. The supplier\'s system has the right shipping address for both customers but the shipper grabbed the wrong label. Refuse the shipment or coordinate with the supplier for re-routing.</li>
  <li><strong>Cancelled-PO shipment.</strong> TurbineWorks cancelled a PO but the supplier shipped anyway, either because they didn\'t process the cancellation or because the cancellation came after they\'d already shipped. Hold the shipment and contact the supplier.</li>
  <li><strong>Over-shipment.</strong> The PO authorized 5 turbine blades; the supplier shipped 7. The excess is not authorized — either return the excess, get a PO amendment, or document the over-shipment for accounting reconciliation. Cannot just put the extras into inventory.</li>
  <li><strong>Wrong part number.</strong> The packing list and the PO refer to different part numbers. Either the supplier shipped the wrong part, or there\'s a part-number transcription error somewhere. Investigate before opening the package.</li>
  <li><strong>Different supplier than expected.</strong> The PO was issued to Supplier A but the package arrives from Supplier B. Possibly drop-shipping (Supplier A had their part drop-shipped from Supplier B); possibly the shipping address is correct but Supplier A is unaware. Investigate.</li>
</ul>

<p>The receiving inspector\'s response to PO mismatch is always: hold and investigate, do not open. Opening commits TurbineWorks to handling the part; investigation may reveal that the part shouldn\'t have been delivered in the first place.</p>

<h4>Step 3 — Verify the supplier is on the approved-supplier list</h4>

<p>TurbineWorks maintains an Approved-Supplier List (ASL). Parts from suppliers on the ASL can be accepted into inventory through the normal receiving process. Parts from suppliers NOT on the ASL cannot be accepted without explicit QA Manager authorization, which typically requires going through the supplier qualification process before any parts from that supplier are accepted.</p>

<h5>What the ASL contains</h5>

<ul>
  <li>Supplier company name (and any DBA names)</li>
  <li>Supplier physical address (warehouse / facility)</li>
  <li>Approval date and last re-evaluation date</li>
  <li>Approval scope — what categories of parts this supplier is approved for (some suppliers are approved only for specific part types)</li>
  <li>Specific accreditations the supplier holds (ASA-100, AS9120, FAA certificates, etc.)</li>
  <li>Performance history — recent quality findings, on-time delivery, customer complaints</li>
  <li>Owner of the supplier relationship at TurbineWorks (typically the buyer)</li>
</ul>

<h5>How a supplier gets on the ASL</h5>

<p>Supplier qualification is the QA Manager\'s responsibility (with buyer input). Typical qualification steps:</p>

<ol>
  <li>Supplier information collection — capabilities, accreditations, references</li>
  <li>Facility assessment — either an on-site visit (for higher-risk suppliers) or a desk audit of their QMS documentation</li>
  <li>Reference checks — talk to other distributors or customers about their experience with the supplier</li>
  <li>Initial test order with enhanced receiving inspection — qualify performance on a small order before opening the relationship to volume</li>
  <li>Documented qualification decision by the QA Manager, with the supplier added to the ASL</li>
</ol>

<h5>The "new supplier" scenario at receiving</h5>

<p>If a shipment arrives from a supplier not on the ASL, the receiving inspector\'s response:</p>

<ul>
  <li>Hold the shipment unopened in Receiving</li>
  <li>Notify the QA Manager same business day</li>
  <li>Do not begin documentation review or physical inspection until the supplier status is resolved</li>
</ul>

<p>The QA Manager may:</p>

<ul>
  <li>Authorize one-time receipt with enhanced inspection (documented decision)</li>
  <li>Begin supplier qualification and add to the ASL before accepting the part</li>
  <li>Refuse the shipment — return to sender without opening</li>
</ul>

<h4>Step 4 — Documentation completeness check</h4>

<p>Before opening the package, confirm the required documentation is present. Required documents depend on the part type and the PO terms but typically include:</p>

<ul>
  <li><strong>Packing list.</strong> The supplier\'s document listing what was packed, in what quantity, with reference to the PO.</li>
  <li><strong>FAA 8130-3 (or equivalent — EASA Form 1, TCCA Form One).</strong> For parts requiring airworthiness approval documentation, the tag must accompany the shipment. Note: the tag is sometimes inside the package (for some part types); if you can see through clear packaging that the tag is enclosed, that satisfies the "present" check.</li>
  <li><strong>Certificate of Conformance (COC).</strong> Required for many raw materials and standard hardware. The PO terms specify when COC is required.</li>
  <li><strong>Material test reports.</strong> Required for some parts where material conformance matters (turbine disks, fasteners in critical applications). PO terms specify.</li>
  <li><strong>Country-of-origin documentation.</strong> Required for export compliance and some customer contracts.</li>
  <li><strong>Hazmat documentation.</strong> Required for any hazmat shipment (Shipper\'s Declaration for Dangerous Goods for air shipments).</li>
  <li><strong>Other contract-specific documents.</strong> Some customers require specific paperwork that flows down through the PO to the supplier.</li>
</ul>

<p>If a required document is missing, the shipment is held pending documentation. The supplier is notified. The QA Manager is notified. The part cannot be accepted into inventory until the missing documents arrive.</p>

<p>An exception: sometimes documentation is shipped separately from the part — by email, by separate courier. If the documentation has been received via the separate channel, the missing-from-package status is acceptable. Record the path through which the documentation arrived.</p>

<h4>Step 5 — Decide whether to open</h4>

<p>By this point, the inspector has confirmed (or not) each pre-receiving check:</p>

<ul>
  <li>Shipment identified and physically intact ☐</li>
  <li>PO matched to an open authorized PO ☐</li>
  <li>Supplier verified on the ASL ☐</li>
  <li>Required documentation present ☐</li>
</ul>

<p>If every check is satisfied, proceed to opening and the documentation review (Lesson 2.3) and physical inspection (Lesson 2.4).</p>

<p>If any check failed, the shipment stays unopened in the Receiving hold area until the failure is resolved. The QA Manager dispositions any open issues. The shipment may eventually be:</p>

<ul>
  <li>Cleared to proceed (after documentation arrives, supplier is qualified, PO is corrected)</li>
  <li>Returned to the supplier (refuse the shipment)</li>
  <li>Held for further investigation</li>
</ul>

<h4>Common pre-receiving issues (deep)</h4>

<h5>Shipment received before PO was issued</h5>
<p>Variation: the buyer ordered verbally, the supplier shipped, the PO was never written. This happens occasionally with rush orders. Procedure: hold the shipment, get a PO issued retroactively, document the PO timing. Repeated occurrences are a procedural finding for the buying group.</p>

<h5>Over-shipment without PO authorization</h5>
<p>The supplier shipped more than the PO authorized. Possibilities: supplier mistake, supplier intentionally over-shipping (rare but happens), supplier trying to clear excess inventory. Procedure: accept what was authorized; the excess is unauthorized and must be either returned or accepted via PO amendment.</p>

<h5>Drop-shipping by an unapproved third party</h5>
<p>The PO is to Supplier A. The package arrives from Supplier B because Supplier A sourced the part from B and asked B to drop-ship to TurbineWorks. Supplier B is not on the TurbineWorks ASL. The shipment is in an awkward position: the buyer thinks Supplier A is delivering, but the actual chain of custody runs through B. Procedure: hold, investigate, confirm with Supplier A what happened, evaluate whether to qualify Supplier B or refuse the shipment.</p>

<h5>Country-of-origin issue</h5>
<p>The PO authorized parts from a specific country (export-control or customer-contract reason). The shipment arrives from a different country. Particularly relevant for ITAR-controlled or sanctioned-country issues. Procedure: hold, notify QA Manager and Export Control function, do not open until resolved. Opening may itself create export-control exposure.</p>

<h5>Tamper-evident seals broken or missing</h5>
<p>The shipment was supposed to arrive with tamper-evident seals or carrier security tape. The seals are broken or absent. Possibilities: tampering in transit, supplier neglect to apply seals, legitimate inspection by customs en route. Procedure: photograph the condition, hold the shipment, investigate before opening. A broken seal on a shipment of high-value parts is a serious indicator.</p>

<h5>Documentation received separately</h5>
<p>The shipment arrives without the 8130-3 in the package. The supplier has previously emailed a copy. Acceptable? Sometimes — if the emailed copy is verifiable and the original will follow. Document the path. Some PO terms require the original to be in the package; others allow electronic.</p>

<h4>Why pre-receiving deserves its own discipline</h4>

<p>An inspector might be tempted to skip pre-receiving and just open the package — "I\'ll check the paperwork after I look at the part." This is wrong for several reasons:</p>

<ul>
  <li>Once opened, returns become harder. The supplier may resist returns of opened shipments.</li>
  <li>Once opened, the seal-tampering indicator is gone. You can\'t later prove the shipment arrived unsealed if you opened it without recording the seal status.</li>
  <li>Investigation effort is wasted on parts that shouldn\'t have been accepted in the first place. Better to identify "wrong supplier" before performing a full receiving inspection on the part.</li>
  <li>The pre-receiving discipline is itself an auditable record. ASA auditors will look at recent receipts and check whether pre-receiving was documented.</li>
</ul>

<h4>Self-check</h4>

<ol>
  <li>Why is opening a sealed shipment described as a "one-way door"?</li>
  <li>What information is captured during Step 1 (shipment identification)?</li>
  <li>What are five common PO mismatch scenarios?</li>
  <li>What does the Approved-Supplier List contain, and what is the role of the QA Manager in maintaining it?</li>
  <li>What is the receiving inspector\'s response to a shipment from a supplier not on the ASL?</li>
  <li>What documents are typically required to accompany an aviation parts shipment?</li>
  <li>What does the receiving inspector do if a required document is missing from the package?</li>
  <li>Why is it wrong to skip pre-receiving and go directly to opening the package?</li>
</ol>

<p><em>[TurbineWorks Procedure Reference: insert the current TurbineWorks Approved-Supplier List location and access, the PO-matching workflow, and the hold-area location and protocol here.]</em></p>
HTML
        ],
        [
            'name'  => 'Lesson 2.3 — Documentation Review',
            'intro' => '<p>The systematic verification workflow for every document accompanying an aviation parts shipment. The single highest-value receiving-inspection skill at TurbineWorks — most SUPs that get caught are caught here.</p>',
            'content' => <<<'HTML'
<h3>Documentation Review</h3>

<p>With pre-receiving complete and the package opened, the receiving inspector now reviews the documentation accompanying each part. This lesson covers the full verification workflow — what to check on each document type, in what order, against what reference, with what response when something is wrong.</p>

<p>Documentation review is the receiving inspector\'s primary skill. It\'s where most defects are caught. It\'s where the audit trail begins. It\'s the activity an ASA auditor will most carefully observe during an on-site audit. Master this and the rest of receiving inspection becomes secondary.</p>

<p>Module 1 Lesson 1.3 covered <em>red flags</em> — the indicators that something is wrong. This lesson covers the <em>workflow</em> — the operational sequence of checks performed on every shipment. The two are complementary: this lesson tells you what to do; Lesson 1.3 tells you what bad looks like.</p>

<h4>The "one part, one document set" principle</h4>

<p>For serialized engine parts — the bulk of what TurbineWorks handles — each part has its own document set. Specifically, each serialized part has its own 8130-3 (or equivalent). Multiple parts cannot be released on a single 8130-3 when they are serialized — the regulation requires per-part traceability.</p>

<p>For non-serialized items (standard hardware, consumables, raw materials in batches), a single document set may cover the whole lot. The packing list will indicate quantity and the documentation references the lot number.</p>

<p>When you receive a multi-part shipment, the first thing to confirm is that each serialized part has its own documentation, and each lot of non-serialized parts has appropriate batch documentation.</p>

<h4>The FAA 8130-3 — Block-by-block verification</h4>

<p>This is the systematic check performed on every 8130-3 in a shipment. Each block has a specific purpose and a specific verification.</p>

<h5>Block 1 — Approving Civil Aviation Authority / Country</h5>
<p><strong>Expected:</strong> "FAA / United States" for an FAA-issued tag. Foreign authority equivalent forms (EASA Form 1, TCCA Form One) have different titles.</p>
<p><strong>Verify:</strong> matches the form type the supplier promised, and is recognized by U.S. bilateral if foreign. Inconsistency suggests the form was constructed from pieces of multiple sources.</p>

<h5>Block 2 — Form Title</h5>
<p><strong>Expected exactly:</strong> "AUTHORIZED RELEASE CERTIFICATE / FAA Form 8130-3, AIRWORTHINESS APPROVAL TAG"</p>
<p><strong>Verify:</strong> exact text match. Variations or typos suggest fabrication.</p>

<h5>Block 3 — Form Tracking Number</h5>
<p><strong>Expected:</strong> unique alphanumeric identifier from the issuing organization. Format consistent within each issuing organization\'s sequence.</p>
<p><strong>Verify:</strong> field is populated (not blank or placeholder); format is plausible for the issuing organization; not blatantly out-of-sequence with other tags from the same organization.</p>

<h5>Block 4 — Organization Name and Address</h5>
<p><strong>Expected:</strong> name and address of the FAA-certificated organization issuing the tag.</p>
<p><strong>Verify:</strong></p>
<ul>
  <li>Name matches Block 15 certificate-holder name (look up Block 15 in FAA database; compare to Block 4)</li>
  <li>Address matches the certificate holder\'s registered address per FAA records</li>
  <li>Address is not a P.O. Box (legitimate organizations have physical facilities)</li>
</ul>

<h5>Block 5 — Work Order / Contract / Invoice Number</h5>
<p><strong>Expected:</strong> the issuing organization\'s internal reference number for the work this tag documents.</p>
<p><strong>Verify:</strong> field is populated. The number is the cross-reference to the issuing organization\'s records — if you ever need to contact them to verify the tag, you reference this number.</p>

<h5>Block 6 — Item Number</h5>
<p><strong>Expected:</strong> sequential item number on a multi-line form. "1" for single-part forms.</p>

<h5>Block 7 — Description</h5>
<p><strong>Expected:</strong> OEM\'s description of the part (e.g., "TURBINE BLADE, HPT STAGE 1," "BEARING, COMPRESSOR ROTOR").</p>
<p><strong>Verify:</strong> description matches the physical part; uses the OEM\'s standard terminology; matches the PO description.</p>

<h5>Block 8 — Part Number</h5>
<p><strong>Expected:</strong> OEM part number including any dash suffix and revision letter.</p>
<p><strong>Verify:</strong></p>
<ul>
  <li>Matches the part number stamped on the physical part (Lesson 2.4)</li>
  <li>Matches the part number on the TurbineWorks PO</li>
  <li>Exists in the current OEM IPC (Illustrated Parts Catalog)</li>
  <li>Matches Block 8 character-for-character — including any dash suffixes and revision letters</li>
</ul>

<h5>Block 9 — Quantity</h5>
<p><strong>Expected:</strong> the count of parts the tag covers.</p>
<p><strong>Verify:</strong> matches the actual count in the shipment.</p>

<h5>Block 10 — Serial Number</h5>
<p><strong>Expected:</strong> the specific serial number of the part for serialized items.</p>
<p><strong>Verify:</strong></p>
<ul>
  <li>Matches the data plate stamping on the physical part — character by character</li>
  <li>Fits the OEM\'s known serial-number format (length, character set, structure)</li>
  <li>Has not been altered on the tag (no whiteout, no over-stamping, no ink color change)</li>
</ul>

<h5>Block 11 — Status / Work</h5>
<p><strong>Expected:</strong> one of: NEW / INSPECTED/TESTED / REPAIRED / OVERHAULED / MODIFIED / PROTOTYPE</p>
<p><strong>Verify:</strong></p>
<ul>
  <li>One of the standard status codes (no improvised terms)</li>
  <li>Consistent with the physical condition of the part (e.g., "NEW" with no signs of use)</li>
  <li>Consistent with what the supplier represented in their quote and what the PO ordered</li>
</ul>

<h5>Block 12 — Remarks</h5>
<p>Most consequential block for engine parts. Free-text field containing critical metadata:</p>
<ul>
  <li><strong>For LLPs:</strong> TSN, CSN, life limit, remaining life</li>
  <li><strong>For overhauled/repaired parts:</strong> shop visit reference, work performed</li>
  <li><strong>SB / AD compliance:</strong> which SBs have been incorporated, AD compliance status</li>
  <li><strong>Engine traceability:</strong> source engine if removed from a specific engine</li>
  <li><strong>Special conditions:</strong> any limitations or notes affecting use of the part</li>
</ul>
<p><strong>Verify:</strong></p>
<ul>
  <li>LLP data is present for any LLP (a blank Block 12 on an LLP is a serious deficiency)</li>
  <li>TSN/CSN values are internally consistent (TSO ≤ TSN; values from previous documentation match or properly accumulate)</li>
  <li>Cited SBs and ADs exist (look them up in the appropriate databases)</li>
  <li>Engine traceability references make sense (engine serial number format is plausible, engine model matches the part)</li>
</ul>

<h5>Block 13 — Conformance Statement</h5>
<p><strong>Expected:</strong> pre-printed conformance text with two checkboxes. Exactly one box checked.</p>
<p><strong>Verify:</strong> one box and only one box is checked. Neither checked or both checked is a defective form.</p>

<h5>Block 14 — Approving Signature</h5>
<p><strong>Expected:</strong> original wet-ink signature OR verifiable electronic signature with audit trail.</p>
<p><strong>Verify:</strong></p>
<ul>
  <li>Signature appears original — not photocopied (look for rasterization, uniform ink color, dotted lines)</li>
  <li>Signature is hand-drawn (not computer-generated)</li>
  <li>Signature matches the printed name in Block 16</li>
  <li>For electronic signatures: there is an associated audit trail (timestamp, digital signature certificate, or attestation)</li>
</ul>

<h5>Block 15 — Authorization / Certificate Number</h5>
<p>The single most important block for fraud detection.</p>
<p><strong>Expected:</strong> the FAA certificate number of the issuing organization.</p>
<p><strong>Verify against FAA database at av-info.faa.gov:</strong></p>
<ol>
  <li>Number returns a result in the database</li>
  <li>The certificate holder name in the database matches Block 4 exactly</li>
  <li>The location matches Block 4 address</li>
  <li>The certificate is currently active (not suspended, expired, or revoked)</li>
  <li>The certificate type matches what is appropriate for issuing this 8130-3 (PMA, Repair Station, Production Approval, etc.)</li>
</ol>
<p>This database check is the single most powerful fraud detection step. Skipping it is the single most common §6 audit finding.</p>

<h5>Block 16 — Name of Signer</h5>
<p><strong>Expected:</strong> printed name of the person signing in Block 14.</p>
<p><strong>Verify:</strong> matches signature style; is plausible for the issuing organization (an "Inspector" name on a tag from a small one-person shop is suspicious; a "VP of Operations" signature on a routine tag from a large repair station is also suspicious).</p>

<h5>Block 17 — Date</h5>
<p><strong>Expected:</strong> the date the form was signed.</p>
<p><strong>Verify:</strong></p>
<ul>
  <li>Date is not in the future</li>
  <li>Date is not unreasonably old (a 14-month-old tag on a freshly-shipped part has a gap to explain)</li>
  <li>Date is within the certificate\'s active period (not before the certificate was granted, not after it was suspended)</li>
  <li>Date has not been altered (no whiteout, no over-stamping)</li>
</ul>

<h5>Blocks 18-22 — Installer Section</h5>
<p><strong>Expected:</strong> blank on a part not yet installed.</p>
<p><strong>Verify:</strong> nothing filled in. Anything filled in indicates prior installation on an aircraft — investigate the history.</p>

<h4>EASA Form 1 — Specific differences</h4>

<p>EASA Form 1 is the European Union equivalent. Mutually recognized under FAA-EASA bilateral agreement. Structural differences from FAA 8130-3:</p>

<ul>
  <li>Block 1 reads "EASA / European Union" (or the specific national authority operating under EASA delegation)</li>
  <li>Block 2 title is "AUTHORIZED RELEASE CERTIFICATE / EASA Form 1"</li>
  <li>Blocks 6-8 cover item description and part number (similar but not identical numbering to FAA 8130-3)</li>
  <li>Block 14 contains the authorization signature</li>
  <li>The equivalent of FAA Block 15 is the organization\'s EASA approval number — verifiable through the <a href="https://www.easa.europa.eu/" target="_blank" rel="noopener">EASA approved-organization directory</a></li>
</ul>

<p>The verification principles are the same: verify the certificate number against the appropriate database; check that the certificate holder name matches Block 4 equivalent; confirm signature is original; check date plausibility.</p>

<p>Some EASA-approved organizations are physically located outside the EU under bilateral arrangements. The verification is the same: confirm via EASA database that the organization holds the relevant approval.</p>

<h4>TCCA Form One — Canadian equivalent</h4>

<p>Structure is similar to EASA Form 1 since both follow the bilateral-harmonized format. The certificate number is the Canadian Approved Maintenance Organization (AMO) number, verifiable through Transport Canada\'s online database.</p>

<h4>Certificate of Conformance (COC) verification</h4>

<p>COCs accompany many parts where formal 8130-3 documentation isn\'t required — raw materials, standard hardware, consumables. The COC is supplier-issued, less standardized, but must contain specific elements to be valid.</p>

<h5>Required content</h5>

<ul>
  <li>Standard or specification the parts conform to — with revision: "conforms to MIL-PRF-XXXXX rev. C," not just "conforms to applicable specifications"</li>
  <li>Part number identification of the items covered</li>
  <li>Serial number, lot number, or batch number tying the COC to the specific parts shipped</li>
  <li>Quantity</li>
  <li>Manufacturer or processor name and address (not just a generic letterhead)</li>
  <li>Signature of an authorized representative with name and title</li>
  <li>Date of issue</li>
</ul>

<h5>COC red flags</h5>

<ul>
  <li>No specific standard cited — "complies with industry standards" is meaningless</li>
  <li>No serial/lot/batch tying — "all parts in this shipment" without identifying which parts</li>
  <li>Generic letterhead that could be from anyone</li>
  <li>Signed by someone without an authority title — "Sales Rep" isn\'t the right person to attest conformance</li>
  <li>Date before the parts were manufactured (internal inconsistency)</li>
  <li>Photocopied signature or obviously computer-generated</li>
</ul>

<h4>Material certifications and test reports</h4>

<p>For raw materials and parts where material conformance is critical (turbine disks, structural fasteners), the PO may require a material test report (MTR). The MTR documents the chemical composition and mechanical properties of the heat or lot from which the parts were produced.</p>

<h5>MTR verification</h5>

<ul>
  <li>References the correct material specification</li>
  <li>References the heat number, lot number, or melt number that ties to the parts in the shipment</li>
  <li>Includes the actual measured values for chemistry (percentage of each alloying element) and mechanical properties (tensile strength, yield strength, elongation, hardness)</li>
  <li>Measurements fall within the specification range</li>
  <li>Issued by a qualified laboratory (sometimes the OEM\'s own, sometimes an accredited third-party lab)</li>
  <li>Includes the test date</li>
</ul>

<p>A test report with values that don\'t meet the specification is a major issue — the material claimed to be on spec wasn\'t. A test report missing key values is incomplete. A test report not tied to the heat/lot of the parts is irrelevant — it documents some other material, not the material in the shipment.</p>

<h4>Country-of-origin documentation</h4>

<p>For U.S. export compliance and for some customer contracts, country-of-origin documentation is required. This certifies where the part was manufactured (not just where it was last shipped from).</p>

<p>Verify the country of origin matches what the PO authorized. If the PO restricted origin (e.g., "United States or NATO countries only"), parts from other countries cannot be accepted regardless of how good the 8130-3 looks.</p>

<h4>Hazmat documentation (when applicable)</h4>

<p>For hazardous materials, the shipment includes hazmat shipping documentation:</p>

<ul>
  <li>Shipper\'s Declaration for Dangerous Goods (for air shipments)</li>
  <li>Hazmat shipping papers (for ground/sea)</li>
  <li>SDS (Safety Data Sheet) for the hazmat</li>
</ul>

<p>If hazmat documentation is missing on a shipment containing hazmat, that is a serious finding — not just an internal TurbineWorks problem but a regulatory violation by the shipper. Refuse the shipment until proper documentation arrives. Module 8 covers hazmat in depth.</p>

<h4>Cross-document consistency</h4>

<p>After verifying each document individually, perform cross-document consistency checks:</p>

<ul>
  <li>Part number on 8130-3 matches packing list matches PO matches the part itself</li>
  <li>Serial number on 8130-3 matches data plate matches packing list</li>
  <li>Quantity on 8130-3 matches packing list matches actual count</li>
  <li>Manufacturer name on 8130-3 matches manufacturer on COC if both are provided</li>
  <li>Heat/lot number on MTR matches what is referenced on the 8130-3 or COC</li>
  <li>Country of origin documented and matches PO requirements</li>
</ul>

<p>Cross-document inconsistencies are a stronger SUP indicator than single-document red flags. A bad actor preparing fraudulent documentation typically focuses on making each document look right individually but may miss the cross-references.</p>

<h4>Documentation review workflow checklist</h4>

<p>The full systematic workflow for documentation review on a typical serialized aviation part:</p>

<ol>
  <li>Identify all documents accompanying the shipment ☐</li>
  <li>Confirm each serialized part has its own 8130-3 ☐</li>
  <li>Block-by-block verification of the 8130-3 ☐</li>
  <li>Block 15 verified in FAA database ☐</li>
  <li>Block 4 organization name matches database result ☐</li>
  <li>Block 14 signature is original (not photocopied) ☐</li>
  <li>Block 10 serial number matches the physical part ☐</li>
  <li>Block 12 includes LLP data if applicable ☐</li>
  <li>Block 17 date is plausible ☐</li>
  <li>Right-side blocks 18-22 are blank ☐</li>
  <li>COC verified if required (specific standard cited, parts identified, authorized signer) ☐</li>
  <li>Material test report verified if required (heat/lot matches; values on spec) ☐</li>
  <li>Country-of-origin documentation verified if required ☐</li>
  <li>Hazmat documentation verified if applicable ☐</li>
  <li>Cross-document consistency confirmed (part number, serial, quantity, manufacturer) ☐</li>
</ol>

<p>Each unchecked box requires resolution. The inspector either resolves the question (looks up the certificate, contacts the supplier, etc.) or quarantines pending investigation.</p>

<h4>What to do with the documents after review</h4>

<p>Originals: file against the part record in the document control system. The originals are the audit-evidence chain. Module 5 covers records retention in detail. For purposes of receiving inspection, the key principle is: do not alter, annotate, or stamp the original documents. Make copies if you need to mark up something for internal use; the originals stay clean and complete.</p>

<p>Copies: may be kept with the physical part for warehouse reference, but the controlling record is the original in the document control file.</p>

<h4>Self-check</h4>

<ol>
  <li>What is the "one part, one document set" principle for serialized engine parts?</li>
  <li>What is the single most important block on an 8130-3 for fraud detection? Why?</li>
  <li>Where do you verify a Block 15 certificate number?</li>
  <li>What must be included in Block 12 (Remarks) for a Life Limited Part?</li>
  <li>Name the 6 required elements of a valid Certificate of Conformance.</li>
  <li>Why is a Material Test Report that doesn\'t reference the heat/lot number of the parts in the shipment "irrelevant"?</li>
  <li>What is the difference between a single-document red flag and a cross-document inconsistency?</li>
  <li>What should you do with the original 8130-3 after documentation review?</li>
</ol>

<p><em>[TurbineWorks Procedure Reference: insert TurbineWorks document control SOP reference and the location of the document filing system here.]</em></p>
HTML
        ],
        [
            'name'  => 'Lesson 2.4 — Physical Inspection',
            'intro' => '<p>The systematic physical examination of the part after documentation is verified. Operational depth on quantity, identifiers, markings, condition, packaging, and when calibrated dimensional verification is required.</p>',
            'content' => <<<'HTML'
<h3>Physical Inspection</h3>

<p>With documentation review complete and consistent, the receiving inspector now examines the physical part itself. This lesson covers the systematic workflow for physical inspection — what to look at, in what order, with what reference, at what level of detail.</p>

<p>Module 1 Lesson 1.4 covered <em>physical red flags</em> — the indicators that signal something is wrong. This lesson covers the <em>routine workflow</em> — what every receiving inspector does on every part, before red-flag-level investigation kicks in. The two are complementary: this lesson is the everyday discipline; 1.4 is the deeper-look response when something looks off.</p>

<h4>Depth of physical inspection — calibrating to the part</h4>

<p>Not every part receives the same physical inspection depth. A box of MS-standard fasteners gets a different inspection than a $40,000 HPT blade. The QAM should define inspection depth by part category. Typical categories:</p>

<ul>
  <li><strong>Standard hardware</strong> (fasteners, washers, cotter pins, standard fittings) — quantity verification, lot number / cure date check, visible condition. A box of 500 cotter pins gets a sampling inspection, not item-by-item.</li>
  <li><strong>Consumables</strong> (sealants, adhesives, lubricants) — quantity, expiration date, packaging integrity, label verification, hazmat handling if applicable.</li>
  <li><strong>Components, non-serialized</strong> (lower-value rotables, accessories) — quantity, part number verification, visual condition, packaging.</li>
  <li><strong>Serialized parts</strong> (turbine blades, disks, fuel controls, instruments) — item-by-item, including serial number verification per Block 10, full visual examination, markings check.</li>
  <li><strong>LLPs (Life Limited Parts)</strong> — most thorough. Item-by-item, full visual, full markings verification, condition assessment, life-record cross-check.</li>
  <li><strong>Modules and engines</strong> — receiving inspection at this level usually involves the QA Manager directly. Module-level documentation review, BSI photo review if available, accessories inventory, build standard verification.</li>
</ul>

<p>The TurbineWorks QAM specifies inspection depth per category. The receiving inspector applies the right depth without escalation; the QA Manager handles the highest-stakes inspections (modules, engines, high-value LLPs) personally or with senior inspector involvement.</p>

<h4>Step 1 — Quantity verification</h4>

<p>Count the parts in the shipment. Match against:</p>

<ul>
  <li>Block 9 of the 8130-3 (or equivalent — Block 6 of EASA Form 1)</li>
  <li>The packing list quantity</li>
  <li>The PO line item quantity</li>
</ul>

<p>All three should agree. Discrepancies require investigation before continuing.</p>

<h5>Common quantity discrepancies</h5>

<ul>
  <li><strong>Over-shipment.</strong> Supplier shipped more than ordered. Sometimes legitimate (supplier sent extras to cover potential breakage; supplier intends invoice adjustment). Sometimes a tracking problem. Document the excess; the excess is not authorized for inventory until the PO is amended or the excess is returned.</li>
  <li><strong>Under-shipment.</strong> Fewer parts than ordered. Document as partial shipment. Confirm whether the remaining quantity is in a future shipment or whether the supplier cancelled the balance.</li>
  <li><strong>Count off by one.</strong> Surprisingly common; usually a counting error rather than fraud. Recount before recording. If discrepancy persists, count a third time with a witness.</li>
  <li><strong>Count significantly off.</strong> A 5-piece order showing 10 pieces or 2 pieces is not a counting error. Investigate the cause — likely a paperwork mix-up or a wrong-part shipment.</li>
</ul>

<p>Verified count goes into the receiving inspection record. The recorded count is what subsequent inventory tracking is based on.</p>

<h4>Step 2 — Serial number verification</h4>

<p>For each serialized part, the serial number on the data plate (or stamped/etched on the part) must match Block 10 of the 8130-3 character-by-character.</p>

<h5>Where serial numbers appear on parts</h5>

<ul>
  <li><strong>Data plate</strong> — most common for major components (fuel controls, electronic units, accessories). Removable plate with stamped or etched serial number, attached by rivets or screws.</li>
  <li><strong>Direct stamping</strong> — typical for blades, disks, shafts. Serial number stamped or etched directly on the part in a non-stressed area.</li>
  <li><strong>Etched serial</strong> — common for smaller parts. Electrochemically etched (EChE) or laser etched.</li>
  <li><strong>Bar code / QR code</strong> — increasingly common on newer parts, in addition to readable serial number.</li>
  <li><strong>RFID tag</strong> — some recent parts have embedded RFID for tracking. Auxiliary to the human-readable identifier.</li>
</ul>

<h5>Verification procedure</h5>

<ol>
  <li>Read the serial number from the physical part. If multiple locations (data plate AND direct stamping), confirm both match.</li>
  <li>Read the serial number from Block 10 of the 8130-3.</li>
  <li>Compare character by character — letters and digits exactly as written. "0" vs "O" matters. "1" vs "I" matters. A leading zero counts.</li>
  <li>If they don\'t match, do NOT correct the 8130-3 or re-stamp the part. The mismatch is the finding. Quarantine.</li>
</ol>

<h5>Data plate condition</h5>

<p>Examine the data plate itself for evidence of tampering:</p>

<ul>
  <li>Plate appears to have been removed and reinstalled (mounting screw witness marks, paint disturbance around plate edges)</li>
  <li>Plate is loose, cracked, or shows damage inconsistent with normal handling</li>
  <li>Plate has secondary stamping over the original (overstamping is a known SUP indicator)</li>
  <li>Plate is missing entirely (a part with no data plate cannot be serialized-verified; quarantine)</li>
</ul>

<h4>Step 3 — Part number verification</h4>

<p>The part number stamped or marked on the part must match Block 8 of the 8130-3 AND the PO part number.</p>

<h5>Watch for revision letters</h5>

<p>Part numbers often include a revision letter or dash number that distinguishes pre-mod from post-mod configurations: "ABC-1234-5 Rev B" is not the same part as "ABC-1234-5 Rev C." A customer ordering Rev C will reject a Rev B shipment even if everything else looks correct. Verify the revision letter character-by-character.</p>

<h5>Cross-reference against the OEM IPC</h5>

<p>The part number must exist in the OEM\'s current Illustrated Parts Catalog. A part number that doesn\'t exist in the IPC may be:</p>

<ul>
  <li>An old part number superseded by a current one (verify the supersedure)</li>
  <li>A typo on the documentation</li>
  <li>A fabricated part number that doesn\'t correspond to any actual OEM part</li>
</ul>

<p>Reference current OEM IPC documentation for the engine model. If the part number is unfamiliar, look it up.</p>

<h4>Step 4 — Markings and stampings</h4>

<p>Beyond serial number and part number, modern aviation parts carry additional markings. Verify their presence and consistency.</p>

<h5>Typical additional markings on engine parts</h5>

<ul>
  <li><strong>Manufacturing date code</strong> — when the part was produced. Format varies by OEM.</li>
  <li><strong>Heat number / lot number</strong> — material traceability identifier.</li>
  <li><strong>Cure date</strong> — for elastomers, adhesives, anything with a shelf-life clock.</li>
  <li><strong>Inspector stamp</strong> — sometimes present from OEM final inspection.</li>
  <li><strong>OEM mark / brand</strong> — the manufacturer\'s identifying mark.</li>
  <li><strong>Modification level / build standard marking</strong> — for parts with multiple configurations.</li>
</ul>

<h5>Verification</h5>

<ul>
  <li>Required markings are present per OEM specification (verify against IPC if uncertain what markings should be there)</li>
  <li>Markings appear factory-original (Module 1 Lesson 1.4 covers indicators of re-stamping in depth)</li>
  <li>Cure dates, manufacturing dates, and date codes are legible and consistent</li>
  <li>Markings are consistent across all the parts in the lot if non-serialized (variation suggests mixed-source parts)</li>
</ul>

<h4>Step 5 — Visual condition</h4>

<p>Examine the physical condition of the part. The depth of visual examination depends on the part type and condition claim (per Block 11 of the 8130-3).</p>

<h5>Foreign Object Damage (FOD)</h5>

<p>Look for damage from impact, debris, or rough handling:</p>

<ul>
  <li>Nicks, dings, dents on critical surfaces — especially turbine and compressor blade leading edges, sealing surfaces, bearing races</li>
  <li>Scoring or scratch marks on functional surfaces</li>
  <li>Bent or deformed features</li>
</ul>

<p>A part claimed "NEW" with visible FOD is misrepresented. A part claimed "AS-REMOVED" with FOD may be expected — but the FOD should be documented and the customer should know what they\'re getting.</p>

<h5>Corrosion</h5>

<ul>
  <li>Pitting</li>
  <li>Discoloration suggesting oxidation</li>
  <li>Staining or rust spots</li>
  <li>Plating loss or blistering</li>
</ul>

<p>Corrosion on a part stored in proper environmental conditions shouldn\'t happen. Corrosion suggests the part was stored improperly or has been in service longer than represented.</p>

<h5>Heat distress</h5>

<ul>
  <li>Discoloration on surfaces that shouldn\'t have been heat-affected (blue, purple, gold tints on parts that should be silvery)</li>
  <li>Bluing on bolts that should be uncolored</li>
  <li>Heat checking (fine crazing) on parts that have been overheated</li>
</ul>

<p>Heat distress on a "NEW" part is a major red flag. Even on a used part, heat distress beyond normal operating exposure suggests the part exceeded its design envelope.</p>

<h5>Repair evidence</h5>

<ul>
  <li>Weld beads on parts that should be virgin material</li>
  <li>Machining over original surfaces (one machining pattern crossing another)</li>
  <li>Blending or grinding evidence on edges or surfaces</li>
  <li>Plating over evident defects (sometimes used to hide damage)</li>
</ul>

<p>Repair without authorization is unapproved status. Even authorized repair may have changed the part\'s applicability or its remaining service life — confirm against the 8130-3 Block 11 status.</p>

<h5>Wear indicators</h5>

<ul>
  <li>Bearing race showing run-in patterns or pitting</li>
  <li>Fastener threads showing torque marks (lead-edge compression)</li>
  <li>Contact surfaces showing wear marks from adjacent part contact</li>
  <li>Sealing surfaces showing groove wear from O-ring contact</li>
  <li>Gear teeth showing wear patterns</li>
</ul>

<p>Wear on a "NEW" part is misrepresentation. Wear on used parts is expected but should be within published service limits.</p>

<h4>Step 6 — Packaging integrity</h4>

<p>The packaging that arrived with the part conveys information about how the part has been handled.</p>

<h5>Appropriate packaging for part type</h5>

<ul>
  <li><strong>ESD-sensitive electronics</strong> — should be in ESD-safe packaging (pink antistatic poly, silver shielded bags, conductive foam). Standard cardboard or bubble wrap on electronics is a flag.</li>
  <li><strong>Moisture-sensitive parts</strong> — sealed bag with desiccant. Humidity indicator if applicable.</li>
  <li><strong>Bearings and finished surfaces</strong> — protective wrap or VCI paper preventing surface contact damage.</li>
  <li><strong>Hazmat</strong> — UN-spec packaging with proper labels per 49 CFR / IATA DGR.</li>
  <li><strong>High-value parts</strong> — typically OEM-branded packaging, foam inserts molded for the part, protection of critical surfaces.</li>
</ul>

<h5>Packaging condition</h5>

<ul>
  <li>Outer carton intact or damaged?</li>
  <li>Inner packaging intact?</li>
  <li>Anti-corrosion preservation present (oil coat, VCI, desiccant)?</li>
  <li>Caps on bearing seats, plugs in ports, edge protectors on blades?</li>
  <li>OEM labels match the contents?</li>
</ul>

<h5>Repackaging indicators</h5>

<p>Signs that the packaging has been opened and re-sealed at some point in the supply chain:</p>

<ul>
  <li>Tape residue on the carton suggesting prior opening</li>
  <li>OEM label over a different (covered) label — peel back may reveal the original</li>
  <li>Foam inserts that don\'t fit the part well (suggesting the foam came from a different part)</li>
  <li>Generic packaging substituting what should be OEM packaging</li>
  <li>Preservation materials that don\'t match what the OEM uses</li>
</ul>

<h4>Step 7 — Dimensional verification (when required)</h4>

<p>Routine receiving inspection typically does NOT include dimensional measurement. The OEM\'s production QC under their PMA / TSO / production certificate authority ensures dimensional conformance, and the 8130-3 attests to this.</p>

<p>Dimensional verification at TurbineWorks receiving is performed when:</p>

<ul>
  <li>The PO requires it (specific contract terms)</li>
  <li>The customer requires it (customer-specified incoming inspection plan)</li>
  <li>The part type warrants it (custom-machined items, critical-fit parts)</li>
  <li>Supplier history warrants it (suppliers with past dimensional issues get tighter inspection)</li>
  <li>SUP investigation requires it (confirming a suspect part\'s dimensions against the OEM drawing)</li>
</ul>

<h5>Dimensional verification requirements</h5>

<ul>
  <li><strong>Calibrated measuring tools.</strong> Any tool used for dimensional verification must be listed in TurbineWorks\' calibration program with a current calibration certificate. An out-of-cal tool used for inspection is itself a finding.</li>
  <li><strong>Reference to OEM drawing.</strong> The measurement is against the OEM\'s specified dimension with tolerance. Without the drawing, you don\'t know what you\'re measuring against.</li>
  <li><strong>Documented measurements.</strong> Each measurement value is recorded on the inspection sheet, not just "in-spec" or "OK." Auditors and customers may want to see actual values.</li>
  <li><strong>Identification of the inspector.</strong> The person performing the measurement is named on the record.</li>
</ul>

<h5>Dimensional findings</h5>

<p>If a dimension is out of spec, the part is non-conforming. Disposition is quarantine pending QA Manager review. The QA Manager may:</p>

<ul>
  <li>Authorize re-measurement by a second inspector with a different tool to rule out measurement error</li>
  <li>Reject the part as non-conforming and return to supplier</li>
  <li>Refer to engineering for use-as-is determination (rare; only for parts where the OEM authorizes specific tolerance deviations)</li>
</ul>

<h4>Step 8 — Other condition-specific checks</h4>

<p>Some part types require category-specific checks beyond general visual examination:</p>

<h5>Electronic components</h5>
<ul>
  <li>ESD-safe handling from the moment packaging opens</li>
  <li>Visual inspection of leads or pins for damage or contamination</li>
  <li>Date code verification — plausible for the manufacturer\'s production period</li>
  <li>Marking texture and quality (laser-etched vs. ink — counterfeits often differ)</li>
</ul>

<h5>Bearings</h5>
<ul>
  <li>Race surfaces under magnification — any pitting or brinelling</li>
  <li>Roller / ball examination through the cage gaps</li>
  <li>Cage condition — no cracks, no excessive wear</li>
  <li>Preservation oil/grease — present, correct type, not contaminated</li>
  <li>Smooth rotation when turned slowly by hand (for accessible bearings)</li>
</ul>

<h5>Blades</h5>
<ul>
  <li>Airfoil leading edges and trailing edges — sharp where they should be, free of FOD</li>
  <li>Root attachment surfaces (fir-tree, dovetail) — undamaged, conformant to OEM geometry</li>
  <li>TBC (Thermal Barrier Coating) on hot-section blades — present, intact, no excessive spalling</li>
  <li>Cooling holes (cooled blades) — present in correct number and location, free of obstruction</li>
  <li>Tip condition — no excessive rub damage</li>
</ul>

<h5>Disks (LLPs)</h5>
<ul>
  <li>Bore, web, rim dimensions per OEM (dimensional check often required by PO for LLPs)</li>
  <li>Surface finish on fatigue-critical fillets (web-to-rim transition)</li>
  <li>Inspection records reviewed (NDT records from OEM and from any prior shop visits)</li>
  <li>No repair or rework evidence — disks generally cannot be repaired</li>
  <li>Markings: part number, serial number, manufacturing date, material heat number all present</li>
</ul>

<h4>Step 9 — Update the receiving inspection record</h4>

<p>Throughout physical inspection, the inspector records observations on the receiving inspection record. The record includes:</p>

<ul>
  <li>Inspector name, date, and time of inspection</li>
  <li>Part identification (part number, serial number, quantity)</li>
  <li>Documents reviewed</li>
  <li>Findings — any non-conformances observed, with descriptive detail</li>
  <li>Dimensional measurements if performed (actual values)</li>
  <li>Photographs taken (referenced by photo identifier)</li>
  <li>Disposition (pending until Lesson 2.5 — accept / quarantine / reject)</li>
  <li>Inspector signature</li>
</ul>

<p>The record is contemporaneous — written during or immediately after the inspection, not days later from memory. Auditors will look at the timestamp and consistency.</p>

<h4>What to do with any non-conformance</h4>

<p>If the part fails any check during physical inspection, the disposition is Quarantine. Lesson 2.5 covers the disposition decision in depth. For now: any non-conformance, no matter how minor it appears, results in Quarantine pending QA Manager review. The inspector\'s authority is to identify and quarantine, not to disposition non-conforming material.</p>

<h4>Self-check</h4>

<ol>
  <li>What factors determine the depth of physical inspection for a given part?</li>
  <li>What three references must agree on quantity?</li>
  <li>Why is character-by-character serial number verification important?</li>
  <li>What does it mean for a data plate to "appear to have been removed and reinstalled"?</li>
  <li>Why does the revision letter on a part number matter?</li>
  <li>Name four indicators of wear that suggest a "NEW" part has actually been in service.</li>
  <li>What is the difference between ESD-safe packaging types (pink poly, silver shielded, conductive)?</li>
  <li>Under what circumstances does TurbineWorks perform dimensional verification at receiving?</li>
  <li>What happens to a part that fails any physical inspection check?</li>
</ol>

<p><em>[TurbineWorks Procedure Reference: insert TurbineWorks receiving inspection record form designator and the calibration program reference here.]</em></p>
HTML
        ],
        [
            'name'  => 'Lesson 2.5 — Disposition Decision: Accept, Quarantine, or Reject',
            'intro' => '<p>The three possible outcomes of every receiving inspection. The decision the inspector is authorized to make, the actions that follow each, and the foundational independence principle that protects the integrity of the entire quality system.</p>',
            'content' => <<<'HTML'
<h3>Disposition Decision</h3>

<p>Every receiving inspection ends with a single decision: Accept, Quarantine, or Reject. This is the inspector\'s most important responsibility — the moment where pre-receiving, documentation review, and physical inspection converge into a binding determination about what happens to the part.</p>

<p>This lesson covers each disposition in operational depth: what triggers it, what the inspector does, what records are created, who is notified, and what happens next. It also covers the foundational independence principle: the inspector\'s disposition authority cannot be overridden by sales, management, or even the QA Manager — and why that matters.</p>

<h4>The disposition is determined by findings, not by preference</h4>

<p>A common misunderstanding: the inspector "decides" the disposition. In one sense yes — the inspector signs the record. But the disposition is not a judgment call about whether to accept the part. The disposition is the application of standard criteria to the inspection findings.</p>

<ul>
  <li>Documentation complete and verified, physical inspection finds nothing of concern → Accept</li>
  <li>Documentation incomplete, internally inconsistent, or externally unverifiable; OR physical inspection finds a non-conformance; OR pre-receiving raised an unresolved concern → Quarantine</li>
  <li>The part cannot be accepted under any reasonable resolution — documentation is confirmed fraudulent, the part is the wrong part entirely, the supplier is confirmed unqualified → Reject</li>
</ul>

<p>This means: an inspector who finds non-conformance is not "deciding" to quarantine; the inspector is recording the disposition required by the finding. The disposition is binding. It cannot be talked away by sales, customer urgency, or schedule pressure.</p>

<h4>Disposition 1 — Accept</h4>

<p>Triggered when: documentation review and physical inspection are complete, all checks pass, no non-conformance observed.</p>

<h5>Actions on Accept</h5>

<ol>
  <li><strong>Complete the receiving inspection record.</strong> Inspector name, signature, date, time. Findings: "documentation verified per checklist; physical inspection complete; no non-conformance observed." Disposition: Accept.</li>
  <li><strong>Apply the TurbineWorks serviceable tag.</strong> The QAM defines the tag format. Typical content: part number, serial number, condition (NEW/NE/OH/SV/AR), TurbineWorks reference number, receiving inspection date, inspector identifier. The tag stays with the part through storage and shipping.</li>
  <li><strong>Move the part to the appropriate serviceable storage location.</strong> The storage location depends on the part type — ESD-controlled area for electronics, hazmat cabinet for hazmat, climate-controlled area for shelf-life items, general shelving for hardware. The location is recorded in the inventory system.</li>
  <li><strong>Update the inventory system.</strong> The system marks the part as available for sale. Quantity on hand increases. The receiving inspection record is linked to the part record.</li>
  <li><strong>File all original documentation against the part record.</strong> Originals to the document control system per the records-retention schedule. Copies may be filed with the warehouse storage record if helpful, but the controlling record is the original in document control.</li>
</ol>

<h5>What the customer is paying for at this point</h5>

<p>When TurbineWorks ships an accepted part to a customer, the customer is paying for the verification work that backed up the Accept disposition. The TurbineWorks Certificate of Conformance that accompanies the outbound shipment attests that this verification was performed. If the verification was inadequate — if the inspector cut corners or missed an indicator — the COC misrepresents what TurbineWorks did. That\'s a contract issue and an audit issue.</p>

<h4>Disposition 2 — Quarantine</h4>

<p>Triggered when: any check during pre-receiving, documentation review, or physical inspection raises a question that cannot be resolved in the routine flow. Quarantine is not rejection — the part may eventually be accepted after the question is answered.</p>

<h5>Common triggers for Quarantine</h5>

<ul>
  <li>SUP investigation — any of the SUP indicators from Module 1 Lessons 1.3 and 1.4</li>
  <li>Missing documentation that is expected to arrive within a defined timeframe</li>
  <li>Visible damage to the part or its packaging that may have occurred in transit (insurance claim pending)</li>
  <li>Serial number discrepancy requiring supplier confirmation</li>
  <li>Supplier qualification status pending — QA Manager needs to authorize one-time receipt or complete supplier qualification first</li>
  <li>Documentation date inconsistency requiring clarification</li>
  <li>Block 15 certificate database lookup returned no result — pending verification of issuing organization</li>
  <li>Dimensional verification revealed a measurement that needs re-check or QA Manager review</li>
  <li>Customer-specific requirement not met (the customer-specific incoming inspection plan flagged something)</li>
</ul>

<h5>Actions on Quarantine</h5>

<ol>
  <li><strong>Move the part to the physically segregated Quarantine area.</strong> The part cannot remain in the Receiving area while quarantine is active, because Receiving needs to handle subsequent shipments. The Quarantine area is separate, marked, and access-controlled.</li>
  <li><strong>Apply the Quarantine tag.</strong> The tag identifies: part number, serial number, supplier, PO reference, date of quarantine, inspector who initiated, reason for quarantine, status of investigation. The tag stays with the part until disposition.</li>
  <li><strong>Document the non-conformance in detail.</strong> "Failed receiving inspection" is not sufficient. The record specifies: what check failed, what was observed, what reference the observation was compared against. Photographs.</li>
  <li><strong>Notify the QA Manager same business day.</strong> The QA Manager owns the investigation from this point. The inspector is the source of the finding but not the investigator.</li>
  <li><strong>Open an NCR (Non-Conformance Report) in the corrective-action system.</strong> The NCR tracks the investigation through to closure. The receiving inspection record references the NCR; the NCR references the receiving inspection record.</li>
  <li><strong>Preserve all original documentation.</strong> Do not annotate, stamp, or alter. The originals are evidence for the investigation.</li>
  <li><strong>Do not return the part or documentation to the supplier without QA Manager authorization.</strong> Returning a quarantined part without investigation defeats the purpose of quarantine.</li>
</ol>

<h5>What happens during quarantine</h5>

<p>The QA Manager investigates. Depending on the specific concern, the investigation may include:</p>

<ul>
  <li>Verification with the issuing organization (Module 1 Lesson 1.5)</li>
  <li>Re-inspection by a second inspector or by the QA Manager personally</li>
  <li>Laboratory analysis if warranted</li>
  <li>Supplier engagement to obtain missing documentation or clarification</li>
  <li>FAA database checks or industry alerts review</li>
</ul>

<p>The investigation concludes with a QA Manager disposition. The disposition is documented on the NCR and the receiving inspection record.</p>

<h5>Possible disposition outcomes after quarantine investigation</h5>

<ul>
  <li><strong>Released to Accept.</strong> Investigation resolved the concern. The part moves to serviceable inventory. The receiving inspection record is updated; the NCR is closed with the resolution documented.</li>
  <li><strong>Confirmed Reject.</strong> Investigation confirmed the non-conformance is real and not resolvable. The part moves to the Reject disposition (below). The NCR continues to track the reject handling.</li>
  <li><strong>Confirmed SUP.</strong> Investigation confirmed the part is a Suspected Unapproved Part. SUP reporting workflow per Module 1 Lesson 1.5 begins.</li>
  <li><strong>Inconclusive.</strong> Investigation cannot definitively confirm or refute. QA Manager dispositions conservatively — typically Reject, with documented reasoning. Records reflect the inconclusive finding so the supplier and part type are flagged for future scrutiny.</li>
</ul>

<h4>Disposition 3 — Reject</h4>

<p>Triggered when: the part cannot be accepted under any reasonable resolution. The reject decision is typically made by the QA Manager after quarantine investigation, but in clear-cut cases an inspector may reject directly (with concurrent QA Manager notification).</p>

<h5>Clear-cut reject triggers</h5>

<ul>
  <li>Documentation confirmed fraudulent (Block 15 not in FAA database, supplier confirms they did not issue the tag, etc.)</li>
  <li>Wrong part — the part shipped does not match the PO part number, no possible reconciliation</li>
  <li>Part visibly destroyed in transit beyond serviceable condition</li>
  <li>Supplier confirmed unqualified, no path to qualification</li>
  <li>Sanctioned-party screening returns the supplier or end-user — TurbineWorks cannot transact</li>
</ul>

<h5>Actions on Reject</h5>

<ol>
  <li><strong>Document the rejection reason in the receiving inspection record.</strong> Detailed: what was found, what investigation occurred, why no resolution was possible.</li>
  <li><strong>Notify the QA Manager and the buyer who issued the PO.</strong> Sales and the buyer need to know to manage the customer relationship if the part was for a specific customer order.</li>
  <li><strong>Apply the Reject tag.</strong> The tag\'s purpose is to make absolutely clear the part cannot enter serviceable inventory under any circumstances.</li>
  <li><strong>Hold the part pending disposition direction from the QA Manager.</strong> Reject parts may be returned to supplier OR destroyed per AC 21-38, depending on the nature of the rejection.</li>
  <li><strong>Open a corrective-action report.</strong> Every reject generates a CAPA file. The investigation: why did this reject occur? Was the supplier qualified properly? Did receiving inspection catch this, or did downstream review catch it? What process change prevents recurrence?</li>
  <li><strong>Evaluate supplier performance.</strong> Repeated rejects from the same supplier trigger supplier review. The QA Manager evaluates whether the supplier remains on the Approved-Supplier List.</li>
</ol>

<h5>Two paths after Reject</h5>

<ul>
  <li><strong>Return to supplier.</strong> Used for routine reject conditions where the part is the supplier\'s problem — wrong part shipped, damaged in transit, documentation incomplete and unrecoverable. Return is coordinated with the supplier; documented return record retained.</li>
  <li><strong>Destruction per FAA AC 21-38.</strong> Used for confirmed unapproved parts (SUP investigation concluded). The part is mutilated; the mutilation witnessed and documented. The part cannot return to the supplier because that would just transfer the SUP to the next distributor.</li>
</ul>

<h4>What inspectors cannot do — the forbidden actions</h4>

<p>The disposition decision has clear authority but also clear limits. Things an inspector is NOT permitted to do:</p>

<ul>
  <li><strong>Accept a part with non-conformance because "it looks OK and the customer needs it."</strong> The non-conformance is what matters; customer urgency does not override.</li>
  <li><strong>Quarantine a part briefly and then quietly accept it without resolving the non-conformance.</strong> Quarantine requires investigation and QA Manager disposition. "Just hold it for a day and then release" without documentation is forbidden.</li>
  <li><strong>Return a SUP to the supplier without investigation.</strong> That just shifts the SUP to the next distributor and destroys evidence.</li>
  <li><strong>Destroy a SUP or evidence without QA Manager authorization.</strong> Evidence preservation is critical to investigation; premature destruction may compromise FAA or law-enforcement work.</li>
  <li><strong>Re-stamp, re-mark, or alter a part to make it pass inspection.</strong> This is itself fraud — converting a non-conforming part into "conforming" through alteration of identifiers.</li>
  <li><strong>Annotate or correct original documentation to "fix" a discrepancy.</strong> The documentation is evidence. Alteration is evidence tampering.</li>
  <li><strong>Skip required checks because the inspection workload is high.</strong> Schedule pressure is real but doesn\'t override the standard.</li>
</ul>

<p>These prohibitions are non-negotiable. An inspector who repeatedly performs any of these actions is removed from the Receiving Inspector designation. An inspector who does so under direction from sales or management is in a difficult position — the right response is to refuse, document the direction received, and escalate to the QA Manager or Accountable Manager.</p>

<h4>The independence principle</h4>

<p>This is the foundational ASA-100 quality-system principle: <strong>the inspector\'s disposition authority is independent of commercial pressure.</strong></p>

<p>Specifically, the inspector\'s decision to Quarantine or Reject a part cannot be overridden by:</p>

<ul>
  <li><strong>Sales</strong> because a customer is waiting</li>
  <li><strong>Operations</strong> because schedule is tight</li>
  <li><strong>Senior management</strong> because of business pressure</li>
  <li><strong>The Accountable Manager</strong> except to authorize a re-inspection by a different inspector (which is itself an unusual step that must be documented)</li>
  <li><strong>The Quality Assurance Manager</strong> on the inspector\'s decision to quarantine. The QA Manager can disposition the quarantined part after investigation, but cannot order the inspector to "not quarantine" in the first place.</li>
</ul>

<p>This is unusual. In most business contexts, employees follow direction from management. In ASA-100 quality systems, the receiving inspector is deliberately structured outside that hierarchy on quality matters specifically. The reason: without inspector independence, commercial pressure inevitably erodes the quality system. Inspectors find ways to make non-conformances disappear. Suppliers learn which distributors are "easy" and route bad inventory accordingly. Customers eventually pay for the failures, and the accreditation eventually catches up.</p>

<h5>What independence looks like in practice</h5>

<ul>
  <li>If sales asks the inspector to "just accept it, the customer is screaming," the right answer is: "I can\'t override the standard. If there\'s a path forward, it\'s through the QA Manager."</li>
  <li>If management asks the inspector to skip a check, the right answer is: "The check is required by the QAM. I\'ll perform it. If you want the procedure changed, that\'s a QAM revision, not an instruction to me."</li>
  <li>If a customer threatens to cancel an order over a quarantine decision, the right answer (typically delivered by the QA Manager, not by the inspector) is: "We can\'t ship a part with unresolved non-conformance. We\'re working the investigation as fast as possible. We\'ll keep you informed."</li>
</ul>

<h5>The audit verification of independence</h5>

<p>ASA auditors specifically probe independence in personnel interviews. Common questions:</p>

<ul>
  <li>"Has anyone ever asked you to release a quarantined part without QA Manager disposition?"</li>
  <li>"Has anyone ever told you to skip a step in your inspection procedure?"</li>
  <li>"What would you do if your manager told you to accept a non-conforming part?"</li>
  <li>"Have you ever felt commercial pressure on a quality decision?"</li>
</ul>

<p>The expected answers, in order: "No," "No," "Refuse and escalate," "Sometimes, and the right response is to refuse and document." Inspectors who answer differently — yes there\'s pressure, yes they\'ve been overridden, yes they\'ve felt forced to compromise — are evidence the quality system isn\'t functioning, regardless of what the paper procedures say.</p>

<h4>The QA Manager\'s role in disposition</h4>

<p>The QA Manager doesn\'t override the inspector\'s quarantine decision. But the QA Manager IS the authority for disposition of quarantined material:</p>

<ul>
  <li>Reviews the inspector\'s finding and the supporting evidence</li>
  <li>Authorizes investigation (verification with issuing organization, laboratory analysis, supplier engagement)</li>
  <li>Reviews investigation results</li>
  <li>Dispositions the part: Release to Accept, Move to Reject, Confirm SUP, Inconclusive</li>
  <li>Signs the NCR closure</li>
  <li>Drives the corrective action — what process change prevents recurrence</li>
</ul>

<p>The QA Manager and the inspector work as complementary roles: inspector identifies; QA Manager investigates and dispositions. Both functions are required for the system to work.</p>

<h4>Self-check</h4>

<ol>
  <li>What are the three possible dispositions in receiving inspection?</li>
  <li>Why is the disposition described as "determined by findings, not by preference"?</li>
  <li>What 5 actions follow an Accept disposition?</li>
  <li>What is the difference between Quarantine and Reject?</li>
  <li>What 7 actions follow a Quarantine disposition?</li>
  <li>What are the four possible outcomes of a quarantine investigation?</li>
  <li>List five "forbidden actions" inspectors cannot perform.</li>
  <li>What is the "independence principle" and why does it matter for the quality system?</li>
  <li>What is the QA Manager\'s role relative to the inspector\'s disposition authority?</li>
</ol>

<p><em>[TurbineWorks Procedure Reference: insert TurbineWorks NCR system reference, the Quarantine area location, and the disposition workflow steps here.]</em></p>
HTML
        ],
        [
            'name'  => 'Lesson 2.6 — Shipping: Outbound Documentation and Packaging',
            'intro' => '<p>Everything that goes out the door with a customer shipment. The shipping inspection is the receiving inspection in reverse — the customer\'s receiving inspector will check what TurbineWorks ships against the same kind of criteria TurbineWorks applies to incoming shipments. Get this right and the customer relationship works; get it wrong and the customer becomes a quality complaint case.</p>',
            'content' => <<<'HTML'
<h3>Shipping: Outbound Documentation and Packaging</h3>

<p>The discipline applied to receiving inspection applies, in mirror image, to outbound shipping. The customer receiving a part from TurbineWorks is going to perform their own receiving inspection. They will check documentation, verify markings, examine condition, look for red flags. What they find is a direct reflection of TurbineWorks\' shipping work.</p>

<p>This lesson covers what every outbound shipment includes, how it\'s packaged, how it\'s labeled, how it\'s shipped, and how the records close the loop. By the end you should understand that shipping inspection is not packaging — it\'s the final quality control checkpoint before the part leaves TurbineWorks custody.</p>

<h4>The shipping inspection mindset</h4>

<p>An effective shipping inspector thinks like the customer\'s receiving inspector:</p>

<ul>
  <li>What will the customer\'s inspector check first?</li>
  <li>Will the documentation be complete and clear?</li>
  <li>Will the part match what the documentation says?</li>
  <li>Will the packaging protect the part during transit and during the customer\'s own storage?</li>
  <li>Are there any obvious problems that will trigger a customer NCR or rejection?</li>
</ul>

<p>A part that arrives at a customer with anything wrong becomes a customer complaint that takes more time to resolve than the original shipment would have taken to prepare correctly. The shipping inspector\'s job is to prevent that.</p>

<h4>Required outbound documentation</h4>

<p>Every outbound shipment includes a documentation package. Specific contents depend on the part type and customer PO terms, but the typical package:</p>

<h5>Packing list — TurbineWorks-generated</h5>

<p>Lists every line item being shipped. For each:</p>

<ul>
  <li>Part number (with revision letter if applicable)</li>
  <li>Serial number for serialized parts</li>
  <li>Quantity</li>
  <li>Customer PO line reference</li>
  <li>Condition code (NEW, NE, OH, SV, AR)</li>
</ul>

<p>Header information: TurbineWorks PO number, customer PO number, customer name, ship-to address, ship date, TurbineWorks shipping reference (often a tracking number or unique shipment ID).</p>

<p>The packing list is the customer\'s starting point — they reference it when receiving and use it to reconcile against their PO.</p>

<h5>Original 8130-3 (or equivalent)</h5>

<p>The airworthiness approval tag that came in with the part stays with the part through TurbineWorks custody and ships with the part to the customer. The original physical tag is what the customer needs for their own records and for any onward installation.</p>

<p><strong>True Copy alternative.</strong> Some PO terms allow the customer to accept a "True Copy" attestation in place of the physical original. A True Copy is a photocopy bearing an attestation from an authorized custodian (typically the QA Manager) stating it is an exact copy of the original. True Copy use is governed by the QAM and the customer-specific contract — not all customers accept True Copies.</p>

<p>TurbineWorks must retain a copy (electronic or physical) of every 8130-3 that ships out, even when the original goes to the customer. This is part of the records-retention requirement (Module 5).</p>

<h5>Certificate of Conformance — TurbineWorks-issued</h5>

<p>The TurbineWorks COC attests that:</p>

<ul>
  <li>The part has been received and inspected per ASA-100 procedures</li>
  <li>The accompanying 8130-3 (or equivalent) was verified during receiving inspection</li>
  <li>The part has been stored under proper conditions while in TurbineWorks custody</li>
  <li>Any non-conformances or relevant findings are disclosed</li>
</ul>

<p>The COC is signed by an authorized TurbineWorks person (typically the QA Manager or a designated alternate). It is on TurbineWorks letterhead, with TurbineWorks contact information.</p>

<p><strong>What the COC must NOT say:</strong> TurbineWorks cannot certify airworthiness on parts where airworthiness was certified by another organization. The OEM\'s 8130-3 certifies airworthiness; TurbineWorks\' COC certifies the distribution-side handling. Conflating the two on the COC is regulatory misrepresentation.</p>

<h5>Material certifications</h5>

<p>If the PO required material test reports at receiving, they are forwarded to the customer. The customer needs them for their own material traceability records.</p>

<h5>Country-of-origin declaration</h5>

<p>Required for export shipments and for many customer contracts. Identifies where the part was originally manufactured.</p>

<h5>Hazmat documentation</h5>

<p>If any hazmat is involved, the Shipper\'s Declaration for Dangerous Goods (for air shipments) or equivalent ground-mode documentation is required per Module 8. Hazmat shipping without proper documentation is a regulatory violation.</p>

<h5>Customer-specific documentation</h5>

<p>Some customers (especially OEMs and major MROs) require additional documentation: their own COC template filled out, supplier-quality forms, traceability matrices, specific test results. The PO terms specify what\'s required. Failing to include customer-specific documentation typically results in receiving rejection by the customer.</p>

<h4>Packaging requirements</h4>

<p>Packaging must protect the part during transit and during the customer\'s own storage (which may extend for weeks or months before installation).</p>

<h5>Container selection per ATA Spec 300</h5>

<p>ATA Spec 300 is the Air Transport Association specification for reusable containers in aviation parts shipping. The spec defines container categories:</p>

<ul>
  <li><strong>Category I:</strong> reusable for many shipments (heavy-duty plastic cases with foam inserts)</li>
  <li><strong>Category II:</strong> reusable for moderate number of shipments (corrugated plastic cases)</li>
  <li><strong>Category III:</strong> limited reuse (heavy corrugated cardboard with reinforcement)</li>
</ul>

<p>The selection depends on part value, fragility, transit mode, and reuse expectation. High-value rotables typically ship in Category I containers. Standard hardware ships in Category III or simpler. TurbineWorks QAM specifies appropriate container selection by part category.</p>

<h5>ESD-sensitive parts</h5>

<p>Module 7 covers ESD in depth. For shipping: ESD-sensitive parts go in appropriate ESD packaging — silver shielded bags for moderate sensitivity, conductive bags for highly sensitive components, anti-static foam for cushioning. The outer container should be labeled with the ESD symbol.</p>

<p>An ESD-sensitive part shipped without ESD packaging is at risk of latent damage. The customer receiving such a shipment will note the packaging deficiency and may reject the shipment.</p>

<h5>Moisture-sensitive parts</h5>

<p>Per IPC J-STD-033 for moisture-sensitive electronic devices, and per OEM spec for other moisture-sensitive items: sealed bag with desiccant pack and humidity indicator card. The customer can see the humidity indicator to verify the package wasn\'t breached in transit.</p>

<h5>Critical surface protection</h5>

<ul>
  <li>Caps on bearing seats</li>
  <li>Plugs in ports (fuel, hydraulic, oil)</li>
  <li>Edge protectors on blade leading and trailing edges</li>
  <li>Padded wrap on finished optical surfaces</li>
  <li>Anti-corrosion oil coat on ferrous parts</li>
  <li>VCI paper for parts susceptible to corrosion</li>
</ul>

<h5>Outer container</h5>

<p>The outer container needs to survive the transit mode. Air freight is relatively gentle (handled by humans, climate-controlled cargo holds). Ocean freight is rougher (containers stacked, exposed to humidity, sometimes salt spray). Truck and rail are intermediate.</p>

<p>For high-value or fragile parts, the outer container is over-built relative to what minimum-spec packaging would require. The cost of better packaging is far less than the cost of a damaged-in-transit claim.</p>

<h5>Hazmat packaging</h5>

<p>UN-spec packaging per 49 CFR / IATA DGR. The UN packaging mark indicates the package has been tested to specific drop, leak, and pressure requirements. Hazmat in non-UN packaging cannot be legally shipped.</p>

<h4>Marking and labeling</h4>

<p>The outer container is labeled to identify the shipment and any handling requirements:</p>

<h5>Required labels</h5>

<ul>
  <li>From / To addresses</li>
  <li>Tracking number / waybill</li>
  <li>Customer PO reference visible without opening</li>
  <li>Part identification (part number, serial number, quantity) on the package exterior — minimizes need to open packaging to identify contents</li>
</ul>

<h5>Handling labels (as applicable)</h5>

<ul>
  <li>"FRAGILE"</li>
  <li>"THIS SIDE UP"</li>
  <li>"DO NOT STACK"</li>
  <li>"KEEP DRY"</li>
  <li>"ESD SENSITIVE"</li>
  <li>Orientation arrows (for liquids or parts that must remain in specific orientation)</li>
</ul>

<h5>Hazmat labels and markings</h5>

<p>Per 49 CFR / IATA DGR per Module 8. Hazard class diamond, UN number, proper shipping name, packing group indication, shipper and consignee, emergency response phone number.</p>

<h5>Customer-specific labels</h5>

<p>Some customers require specific routing barcodes, customer part numbers, or scannable labels per their warehouse management system. These are dictated by the customer\'s receiving operation, not by TurbineWorks preference.</p>

<h4>Carrier and shipping method</h4>

<h5>Carrier selection</h5>

<ul>
  <li><strong>Authorized carrier.</strong> Some carriers don\'t handle aviation parts, hazmat, or high-value cargo. TurbineWorks maintains a list of authorized carriers. Shipping outside the list requires QA Manager authorization.</li>
  <li><strong>Hazmat-qualified carrier</strong> if hazmat is involved. Not every carrier is qualified for every hazmat class.</li>
  <li><strong>Insurance and declared value</strong> appropriate to part value. High-value parts need declared-value coverage; the carrier\'s default liability is typically per-pound and inadequate for aviation parts.</li>
</ul>

<h5>Service level</h5>

<ul>
  <li><strong>AOG (Aircraft on Ground):</strong> next-flight-out air. Customer has a grounded aircraft waiting; cost of delay is enormous.</li>
  <li><strong>Critical:</strong> next-day air. Customer urgency but not AOG.</li>
  <li><strong>Standard:</strong> ground or deferred air. Routine shipments where 3-5 day transit is acceptable.</li>
  <li><strong>Ocean / surface:</strong> non-urgent international shipments, low-value bulk items.</li>
</ul>

<h5>Tracking</h5>

<p>The tracking number is captured and provided to the customer. Customer can monitor transit; TurbineWorks can monitor for problems (delays, damage notifications). The tracking number is part of the shipping record.</p>

<h4>The shipping inspection — final pre-shipment quality check</h4>

<p>Before the carton is sealed, a shipping inspector performs a final verification:</p>

<ol>
  <li><strong>Documentation in the carton matches the part in the carton.</strong> Serial number on the 8130-3 in the package matches the serial number on the actual part being shipped.</li>
  <li><strong>Packaging is appropriate.</strong> ESD packaging if needed, moisture barrier if needed, critical surface protection in place.</li>
  <li><strong>Labels are correct and legible.</strong> Address correct; part identification visible; handling labels appropriate.</li>
  <li><strong>The shipment matches the customer PO.</strong> Right parts, right quantities, right configuration.</li>
  <li><strong>Hazmat is properly documented and packaged.</strong> If applicable.</li>
  <li><strong>Customer-specific requirements are satisfied.</strong> Per-customer documentation, labeling, or packaging.</li>
  <li><strong>The TurbineWorks COC is signed and included.</strong></li>
</ol>

<p>The shipping inspector signs the shipping inspection record certifying the final check was performed. The signature is meaningful — the shipping inspector is accountable for the shipment\'s correctness as it leaves TurbineWorks.</p>

<h4>Common shipping inspection failures</h4>

<ul>
  <li>Wrong serial number in the package (part swapped with documentation from another shipment)</li>
  <li>Missing 8130-3 (forgotten in document control)</li>
  <li>ESD-sensitive part in non-ESD packaging</li>
  <li>Hazmat shipped without DGD or with wrong UN number</li>
  <li>Customer-specific documentation omitted</li>
  <li>Outer container damaged before shipping (should have been replaced)</li>
  <li>Tracking number wrong on the packing list (different from the actual waybill)</li>
  <li>Ship-to address wrong (typo, outdated customer information)</li>
</ul>

<p>Each of these creates a customer complaint, a possible regulatory issue, or a customer rejection. Catching them before shipping is the shipping inspector\'s job.</p>

<h4>Recordkeeping</h4>

<p>Every outbound shipment generates records:</p>

<ul>
  <li>Copy of the shipping documentation (packing list, COC, copy of the 8130-3 that shipped with the part)</li>
  <li>Customer PO cross-reference</li>
  <li>Internal sales order / shipment reference</li>
  <li>Inventory record update — which serial number went to which customer on which date</li>
  <li>Carrier tracking number</li>
  <li>Date and time of shipment</li>
  <li>Shipping inspector identification</li>
  <li>Photographs of the prepared shipment if customer or part value warrants</li>
</ul>

<p>These records close the loop. A customer can show TurbineWorks the part they received and reference the shipment. TurbineWorks can produce every record from the receiving inspection of that part through the outbound shipment — including who inspected at each step, when, against what documentation. That chain is the audit-evidence proof of the entire ASA-100 quality system in action.</p>

<h4>The customer experience</h4>

<p>From the customer\'s perspective, what they see when a TurbineWorks shipment arrives:</p>

<ul>
  <li>Properly addressed package with clear labels</li>
  <li>Outer packaging intact and appropriate for the part type</li>
  <li>Inside: organized documentation package and the part itself in protective packaging</li>
  <li>The documentation matches the part exactly</li>
  <li>The packing list matches the PO</li>
  <li>Everything looks professional, complete, and unambiguous</li>
</ul>

<p>This experience is what builds the customer relationship. A customer who has received 50 perfect TurbineWorks shipments treats TurbineWorks differently than a customer who has received 5 perfect shipments and 1 partially-broken one. The shipping inspector\'s discipline is what produces the perfect-shipment track record.</p>

<h4>Self-check</h4>

<ol>
  <li>What is the "shipping inspection mindset" and why does thinking like the customer\'s receiving inspector matter?</li>
  <li>What 5 documents typically accompany an outbound aviation parts shipment?</li>
  <li>What does TurbineWorks\' COC attest, and what must it NOT claim?</li>
  <li>When is a True Copy of the 8130-3 acceptable instead of the original?</li>
  <li>What is ATA Spec 300 and what does it govern?</li>
  <li>Name 4 categories of critical surface protection that may be required in packaging.</li>
  <li>What is the service level "AOG" and when does it apply?</li>
  <li>What 7 checks does the shipping inspector perform before the carton is sealed?</li>
  <li>What records are created for every outbound shipment, and why does this matter for ASA-100 audit purposes?</li>
</ol>

<p><em>[TurbineWorks Procedure Reference: insert TurbineWorks shipping inspection SOP, COC template, and the list of authorized carriers here.]</em></p>
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
            'intro' => '<p>The framework section that governs everything that happens to a part between receiving inspection acceptance and customer shipment. ASA-100 §7 in operational depth — what the requirements are, why they exist, how they translate into TurbineWorks warehouse practices.</p>',
            'content' => <<<'HTML'
<h3>ASA-100 §7 Storage and Handling</h3>

<p>Receiving inspection (Module 2) is the gate that determines what enters inventory. Storage and handling is what protects parts once they are in. A part correctly inspected at receiving but stored improperly is an inventory loss — possibly an undetected one. The customer who receives a damaged part won\'t care that receiving inspection was performed correctly; they\'ll see TurbineWorks as the failure point.</p>

<p>This module covers ASA-100 §7 and the operational practices that satisfy it. Lesson 4.1 (this lesson) establishes the framework. Subsequent lessons cover specific operational areas: segregation, shelf-life management, FOD prevention, environmental controls and hazmat storage. The lessons are interconnected — each requirement reinforces the others.</p>

<h4>What §7 actually requires</h4>

<p>ASA-100 §7 (current revision; verify against published standard) establishes that the accredited distributor must maintain storage practices that:</p>

<ol>
  <li><strong>Physically segregate parts by status.</strong> Serviceable, quarantine, unserviceable, and scrap zones must be physically distinct — not merely logically separated in software.</li>
  <li><strong>Protect parts from damage, contamination, deterioration, and unauthorized access.</strong> Storage conditions appropriate to the part type. Environmental controls where required. Access controls to prevent tampering or unauthorized handling.</li>
  <li><strong>Maintain environmental conditions per OEM specifications and applicable industry standards.</strong> Temperature, humidity, lighting, ESD controls where applicable.</li>
  <li><strong>Enable efficient location and retrieval.</strong> Any part in inventory must be locatable within minutes — for shipping, for inspection, for audit.</li>
  <li><strong>Remain clean and free of FOD generators.</strong> The warehouse is itself a controlled environment, not just a place where parts happen to be stored.</li>
  <li><strong>Maintain handling procedures appropriate to each part category.</strong> ESD-sensitive parts handled in ESD-controlled areas; hazmat per 49 CFR; moisture-sensitive items per IPC J-STD-033 where applicable.</li>
  <li><strong>Provide records linking each part to its storage location.</strong> Inventory records show where each part is at any given time.</li>
</ol>

<p>These are the substantive requirements. The TurbineWorks QAM translates them into specific procedures that operations can execute and audit.</p>

<h4>Why storage discipline matters disproportionately</h4>

<p>Receiving inspection catches defects coming in. Shipping catches errors going out. Storage is everything in between — typically the longest period a part spends in TurbineWorks custody. Storage failures account for a recurring class of customer complaints:</p>

<ul>
  <li><strong>Damage during storage.</strong> Parts get bumped, dropped, scraped during warehouse handling. The damage may be subtle (a nicked turbine blade leading edge, a scratch on a sealing surface) but the customer receives a part that wasn\'t in the condition TurbineWorks attested to.</li>
  <li><strong>Wrong-part pulls.</strong> Two similar parts stored adjacent to each other; the picker grabs the wrong one. The customer receives something other than what they ordered.</li>
  <li><strong>Mixed-status confusion.</strong> Serviceable and quarantine parts not adequately segregated; the picker grabs a quarantined part. The customer receives a non-conforming part with TurbineWorks COC attesting it\'s serviceable.</li>
  <li><strong>Environmental damage.</strong> Shelf-life-limited parts left in inventory past their expiration. Humidity-sensitive parts allowed to absorb moisture. ESD-sensitive parts handled without proper grounding. The damage may not be visible until the part fails in service.</li>
  <li><strong>Loss of traceability.</strong> Parts in storage without proper tags or with degraded tags become indistinguishable from each other. The 8130-3 chain breaks down because the physical part can no longer be linked to its documentation.</li>
</ul>

<p>Every one of these failures represents a quality system breakdown in the storage area, not in receiving or shipping. An ASA auditor who walks the warehouse and sees disorganization is looking at the storage-discipline failure that creates these defects downstream.</p>

<h4>Status zones — the four-zone model</h4>

<p>Every part in TurbineWorks inventory exists in one of four status zones at all times. Each zone has specific physical, procedural, and access controls.</p>

<h5>Serviceable</h5>

<p>Inspected and approved through receiving inspection. Available for sale to customers. Has the TurbineWorks serviceable tag attached, identifying part number, serial number, condition code (NEW/NE/OH/SV/AR), and TurbineWorks reference. The tag travels with the part through any subsequent movement.</p>

<p>Serviceable inventory is the largest zone — most parts spend most of their TurbineWorks time here. The zone is organized for efficient pick-pack-ship operations. Storage location within the serviceable zone may further organize by:</p>

<ul>
  <li>Part category (rotables, consumables, hardware, electronics)</li>
  <li>Customer assignment (some inventory may be reserved for specific customers)</li>
  <li>Environmental requirements (ESD-controlled, climate-controlled, hazmat)</li>
  <li>Velocity (fast-moving items in accessible locations; slow-moving items further back)</li>
  <li>FIFO requirements (oldest stock at the front for shelf-life-limited items)</li>
</ul>

<h5>Quarantine</h5>

<p>Parts under non-conformance investigation. Cannot be sold. Cannot move out of quarantine without QA Manager disposition. Has a Quarantine tag identifying the reason for hold and the open NCR reference.</p>

<p>The Quarantine zone is physically separate from Serviceable — typically a marked area, sometimes a locked cage, always identifiable to anyone walking through. The separation prevents the recurring failure mode where a part in quarantine accidentally migrates to serviceable storage and then gets pulled for a customer shipment.</p>

<p>Time in Quarantine should be bounded. A part that sits in Quarantine indefinitely is a system failure — either the investigation isn\'t happening, or the disposition isn\'t being recorded. The QA Manager monitors Quarantine inventory and ensures timely investigation closure.</p>

<h5>Unserviceable</h5>

<p>Parts determined unfit for service but not yet finally dispositioned. May be repairable (and queued for return to a repair station) or may be moving toward scrap. Cannot be sold. Has an Unserviceable tag.</p>

<p>The Unserviceable zone is separate from both Serviceable and Quarantine. Parts in Unserviceable have already been through the disposition decision — they\'re not under investigation. They\'re waiting for the final path (repair, return, scrap).</p>

<h5>Scrap</h5>

<p>Final disposition is destruction. Awaiting mutilation per FAA AC 21-38 (Module 5 covers in detail). The Scrap zone is the destination just before parts are destroyed. Often the smallest zone — parts don\'t spend long here because mutilation is performed on a regular cadence.</p>

<p>Scrap zone access is tightly controlled. Diverted scrap is the largest single pathway by which unapproved parts enter the supply chain (Module 1 Lesson 1.2). Preventing scrap diversion is a specific TurbineWorks responsibility — once parts are designated Scrap, the disposition must complete promptly and verifiably.</p>

<h4>Why physical segregation matters operationally</h4>

<p>The four-zone model is enforced by physical separation, not by labeling alone. Some operations have tried to operate with logical-only separation — "the inventory system tracks each part\'s status; physical placement doesn\'t matter." This consistently fails. Here\'s why:</p>

<ul>
  <li><strong>Human error.</strong> Pickers make mistakes. A picker focused on a customer order grabs the part adjacent to the one specified. If the adjacent part is a different status, the error propagates.</li>
  <li><strong>Tag damage or loss.</strong> Tags can be torn, become illegible, or get separated from parts during handling. If the only indicator of status is the tag, a damaged tag means the part\'s status becomes uncertain.</li>
  <li><strong>System failure or lag.</strong> The inventory system may be updated correctly but at delay relative to the physical movement. A part scanned out of Quarantine into Serviceable may not show up in the system as serviceable for hours. During that gap, the physical reality is the only truth.</li>
  <li><strong>New personnel.</strong> A new warehouse hire who hasn\'t internalized the status nuances may handle parts based on what they look like, not on what their tags say. If serviceable and quarantine parts are physically mixed, the new hire\'s default ("they look the same") becomes the error mode.</li>
  <li><strong>Auditor visibility.</strong> The auditor walking the warehouse must be able to immediately see status zones. Logical-only separation looks the same to an auditor as no separation at all. Physical segregation is the visible audit-evidence.</li>
</ul>

<p>The implementation of physical segregation varies. Common approaches:</p>

<ul>
  <li>Painted floor lines marking zone boundaries</li>
  <li>Color-coded shelving (e.g., green for serviceable, yellow for quarantine, red for scrap)</li>
  <li>Locked cages for higher-control zones (especially Quarantine and Scrap)</li>
  <li>Signage at zone entries and throughout</li>
  <li>Different rooms or building sections for different zones</li>
  <li>Combination of the above</li>
</ul>

<p>The TurbineWorks QAM specifies the actual implementation. Whatever the implementation, the visual distinction is unambiguous.</p>

<h4>Access control — who handles parts where</h4>

<p>Different personnel have different authorities for handling parts in different zones:</p>

<table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse;">
  <tr style="background:#0d2240; color:#fff;">
    <th>Zone</th>
    <th>Who can place parts here</th>
    <th>Who can remove parts</th>
  </tr>
  <tr>
    <td>Serviceable</td>
    <td>Receiving Inspector (after acceptance disposition)</td>
    <td>Warehouse personnel (for customer pulls)</td>
  </tr>
  <tr>
    <td>Quarantine</td>
    <td>Receiving Inspector or QA Manager (after quarantine disposition)</td>
    <td>QA Manager only (after investigation/disposition)</td>
  </tr>
  <tr>
    <td>Unserviceable</td>
    <td>QA Manager (after disposition from quarantine or from in-service finding)</td>
    <td>QA Manager (for return-to-repair or transition to scrap)</td>
  </tr>
  <tr>
    <td>Scrap</td>
    <td>QA Manager (after final scrap disposition)</td>
    <td>QA Manager + designated mutilation personnel only</td>
  </tr>
</table>

<p>This authority structure prevents informal status changes. A warehouse worker cannot move a part from Quarantine to Serviceable because they think it looks fine — only the QA Manager has that authority, and only after documented disposition.</p>

<h5>Visitor and unauthorized access</h5>

<p>Storage areas are not open access. Visitors are escorted. Sales personnel, customer representatives, and other non-warehouse staff cannot enter storage areas unaccompanied. This isn\'t about secrecy — it\'s about preventing accidental tampering with inventory, accidental status changes, and contamination of controlled environments (ESD, hazmat).</p>

<h4>The auditor\'s walk-through</h4>

<p>During an on-site audit, the ASA auditor walks the warehouse. They are forming an overall impression of how well the storage discipline functions. Specific things they observe:</p>

<ul>
  <li><strong>Status zone visibility.</strong> Can the auditor tell, by walking through, which area is which? Or are the zones blended together?</li>
  <li><strong>Tag presence and legibility.</strong> Every part has its appropriate tag (serviceable, quarantine, etc.). Tags are readable, not torn or faded.</li>
  <li><strong>Organization.</strong> Parts are arranged logically. Fast-moving items in accessible locations. Stock rotated FIFO where required.</li>
  <li><strong>Cleanliness.</strong> No food, no beverages, no debris, no FOD generators on floor or shelves.</li>
  <li><strong>Environmental controls.</strong> ESD area is properly grounded. Hazmat segregated. Climate-controlled areas at appropriate conditions.</li>
  <li><strong>Random pull verification.</strong> The auditor selects a random part and asks the warehouse personnel to retrieve it. Time-to-retrieve, and whether the actual location matches the inventory system, are observed.</li>
  <li><strong>Quarantine area.</strong> The auditor specifically checks the Quarantine area — what\'s currently in quarantine, how long has each part been there, is the documentation present.</li>
  <li><strong>Scrap area.</strong> Similar — what\'s in Scrap, how long, what\'s the mutilation cadence.</li>
</ul>

<h5>Common §7 audit findings industry-wide</h5>

<ul>
  <li>Serviceable and Quarantine zones not clearly segregated</li>
  <li>Parts in serviceable inventory missing serviceable tags or with illegible tags</li>
  <li>Quarantined parts present without QA Manager investigation documented</li>
  <li>Stock rotation not FIFO for shelf-life items (older stock blocked by newer stock)</li>
  <li>Expired parts present in serviceable inventory</li>
  <li>Environmental conditions out of spec (humidity, temperature) without corrective action</li>
  <li>ESD area not properly grounded (testing reveals continuity failure)</li>
  <li>Hazmat improperly segregated from non-hazmat or from incompatible hazmat classes</li>
  <li>Cleanliness deficient — debris, dust accumulation, food/beverage residue</li>
  <li>Random pull part not findable at the system-indicated location</li>
</ul>

<p>Avoiding these findings is the operational outcome of consistent application of §7 requirements. The procedures exist; the practice has to match.</p>

<h4>The storage discipline mindset</h4>

<p>Effective warehouse operations require a specific mindset:</p>

<ul>
  <li><strong>Status awareness.</strong> Every part has a status; the status determines how it can be handled and where it can go. Status is not a label — it\'s a real constraint on physical movement.</li>
  <li><strong>Tag discipline.</strong> Tags stay with parts. If a tag is damaged or missing, the part is held until the tag is restored — not assumed to be the same status it "probably" was.</li>
  <li><strong>FIFO habit.</strong> For shelf-life items, the oldest goes first. New stock goes to the back. This becomes automatic with practice.</li>
  <li><strong>Cleanliness as quality.</strong> A clean warehouse is a quality control practice, not just aesthetics. Debris on shelves means FOD risk to parts. Food and beverages mean contamination risk.</li>
  <li><strong>Environmental awareness.</strong> If you notice the temperature is unusually high, the humidity is unusually low, the lighting has changed, or any environmental condition seems off, report it. Environmental excursions affect parts even when the excursion is brief.</li>
</ul>

<h4>References</h4>

<ul>
  <li><strong>ASA-100 §7</strong> — Storage and Handling requirements (current revision)</li>
  <li><strong>FAA AC 21-38</strong> — Disposition of Unsalvageable Aircraft Parts (covered in Module 5)</li>
  <li><strong>ATA Spec 300</strong> — Packaging of Aircraft Parts (handling and packaging during storage and shipping)</li>
  <li><strong>NAS 412</strong> — National Aerospace Standard for FOD Prevention (covered in Lesson 4.4)</li>
  <li><strong>ANSI/ESD S20.20</strong> — ESD Control Program Standard (covered in Module 7)</li>
  <li><strong>49 CFR Parts 100-185</strong> — DOT Hazmat (covered in Module 8; applies to hazmat storage)</li>
  <li><strong>IPC J-STD-033</strong> — Moisture-Sensitive Device Handling (applies to certain electronic components)</li>
  <li>TurbineWorks QAM Section [TBD] — Storage and Handling Procedure</li>
</ul>

<h4>Self-check</h4>

<ol>
  <li>What 7 substantive requirements does ASA-100 §7 establish?</li>
  <li>Why is storage discipline as important as receiving inspection?</li>
  <li>Name and describe the 4 status zones.</li>
  <li>Why must the 4 zones be PHYSICALLY segregated, not just logically separated?</li>
  <li>Who is authorized to place parts in each zone, and who is authorized to remove them?</li>
  <li>Name 5 things an ASA auditor specifically observes during a warehouse walk-through.</li>
  <li>List 5 common §7 audit findings that distributors industry-wide receive.</li>
  <li>What 5 mental disciplines constitute the "storage discipline mindset"?</li>
</ol>

<p><em>[TurbineWorks Procedure Reference: insert TurbineWorks warehouse layout diagram, zone-coding scheme, and access-control matrix here.]</em></p>
HTML
        ],
        [
            'name'  => 'Lesson 4.2 — Segregation: Serviceable, Quarantine, Unserviceable, Scrap',
            'intro' => '<p>How parts move between status zones and the records that track every move. The status transition system is the operational reality of the four-zone model — done well it prevents non-conforming material from ever reaching customers; done poorly it\'s the largest single source of quality system failures.</p>',
            'content' => <<<'HTML'
<h3>Segregation and Status Transitions</h3>

<p>Lesson 4.1 established the four status zones. This lesson covers how parts move between zones — who authorizes each transition, what records are created, how the inventory system stays synchronized with physical reality, and what auditors probe to verify the discipline.</p>

<p>A part\'s status in the inventory system and its physical location in the warehouse must always agree. When they don\'t — and this happens — the cause is usually a missed status transition. A part was physically moved without the system being updated, or the system was updated without the physical move occurring. Either form creates a quality system failure that can take weeks to detect and longer to investigate.</p>

<h4>The transition matrix</h4>

<p>Every status transition follows defined rules. The table below shows authorized transitions, what triggers each, and who has the authority to make them.</p>

<table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse;">
  <tr style="background:#0d2240; color:#fff;">
    <th>From</th>
    <th>To</th>
    <th>Trigger</th>
    <th>Authority</th>
  </tr>
  <tr>
    <td>Receiving</td>
    <td>Serviceable</td>
    <td>Receiving inspection complete; Accept disposition</td>
    <td>Receiving Inspector</td>
  </tr>
  <tr>
    <td>Receiving</td>
    <td>Quarantine</td>
    <td>Receiving inspection complete; Quarantine disposition</td>
    <td>Receiving Inspector</td>
  </tr>
  <tr>
    <td>Receiving</td>
    <td>Reject (return to supplier)</td>
    <td>Receiving inspection complete; Reject disposition</td>
    <td>Receiving Inspector + QA Manager approval for return</td>
  </tr>
  <tr>
    <td>Quarantine</td>
    <td>Serviceable</td>
    <td>Investigation complete; non-conformance resolved; part now conforms</td>
    <td>QA Manager only</td>
  </tr>
  <tr>
    <td>Quarantine</td>
    <td>Unserviceable</td>
    <td>Investigation complete; part is not serviceable but may be repairable</td>
    <td>QA Manager only</td>
  </tr>
  <tr>
    <td>Quarantine</td>
    <td>Scrap</td>
    <td>Investigation complete; part confirmed unapproved or beyond repair</td>
    <td>QA Manager only</td>
  </tr>
  <tr>
    <td>Serviceable</td>
    <td>Quarantine</td>
    <td>New non-conformance discovered after acceptance (recall, AD impact, post-acceptance damage)</td>
    <td>QA Manager only</td>
  </tr>
  <tr>
    <td>Serviceable</td>
    <td>Customer shipment</td>
    <td>Customer order; pick/pack/ship</td>
    <td>Warehouse + Shipping Inspector</td>
  </tr>
  <tr>
    <td>Unserviceable</td>
    <td>Repair (return to repair station)</td>
    <td>Part determined repairable; sent to authorized facility</td>
    <td>QA Manager</td>
  </tr>
  <tr>
    <td>Repair (after work)</td>
    <td>Serviceable (or Receiving)</td>
    <td>Repair complete; documentation received from repair station</td>
    <td>Re-receive per Module 2 receiving inspection</td>
  </tr>
  <tr>
    <td>Unserviceable</td>
    <td>Scrap</td>
    <td>Final determination: not economically repairable</td>
    <td>QA Manager</td>
  </tr>
  <tr>
    <td>Scrap</td>
    <td>Destroyed (out of inventory)</td>
    <td>Mutilation per FAA AC 21-38 completed</td>
    <td>QA Manager certifies destruction</td>
  </tr>
</table>

<h4>What is NOT a permitted transition</h4>

<p>Some movements look superficially reasonable but are not permitted under ASA-100:</p>

<ul>
  <li><strong>Serviceable → Returned to supplier without investigation.</strong> If a part in serviceable inventory turns out to have an issue, the issue requires investigation through the corrective-action system. The part can\'t simply be returned to supplier and the inventory reduced.</li>
  <li><strong>Quarantine → Returned to supplier without investigation.</strong> Same logic. Returning a quarantined part without investigation just transfers the SUP risk downstream and destroys evidence.</li>
  <li><strong>Scrap → Anywhere except destruction.</strong> Once a part is dispositioned to Scrap, it cannot move out of that zone except by mutilation. Restoring a Scrap-designated part to Serviceable is fraud.</li>
  <li><strong>Bypass receiving for incoming parts.</strong> Every incoming part goes through Receiving first, regardless of how rushed the customer order is or how trusted the supplier is.</li>
</ul>

<p>The transition matrix is intentionally restrictive. Every restriction prevents a recognized failure mode in distributor operations.</p>

<h4>Every move is recorded</h4>

<p>Each transition above generates a record in the inventory system. The record contains:</p>

<ul>
  <li>Part number and serial number (or lot/batch number for non-serialized)</li>
  <li>From zone → To zone</li>
  <li>Date and time of the transition</li>
  <li>Specific reason for the transition (e.g., "Accept disposition," "Quarantine investigation closed: cleared," "Mutilation complete")</li>
  <li>Authorizing person — initials or signature, or digital authorization in the inventory system</li>
  <li>Reference to related documents — NCR number, receiving inspection record, mutilation certificate, customer shipment reference</li>
</ul>

<p>The transition record is the audit-evidence trail. When an auditor asks "where has this part been since it arrived?" the answer is a chronological list of zone-to-zone movements with authorizing personnel and dates. Any gaps or unauthorized transitions are findings.</p>

<h5>The "transition log" concept</h5>

<p>Many inventory systems maintain a transition log per part — a chronological record of every status change. The log enables three audit questions:</p>

<ul>
  <li><strong>"Where is this part right now?"</strong> — last entry in the log</li>
  <li><strong>"Where has this part been?"</strong> — full log history</li>
  <li><strong>"Who authorized each move?"</strong> — authorizing person on each log entry</li>
</ul>

<p>An auditor pulling a random part and asking for its log will see all three pieces of information. The log is part of the records retained per the records-retention schedule (Module 5).</p>

<h4>Why QA Manager authorization for transitions out of Quarantine</h4>

<p>Notice that all transitions OUT of Quarantine require QA Manager authorization. This is intentional and structural. The reasoning:</p>

<ul>
  <li>Quarantine exists to hold non-conforming material pending investigation</li>
  <li>The investigation determines whether the non-conformance can be resolved or whether the part must be rejected</li>
  <li>Only the QA Manager has the authority to disposition non-conforming material — Module 2 Lesson 2.5 establishes this</li>
  <li>Therefore only the QA Manager can authorize moving parts out of Quarantine</li>
</ul>

<p>A warehouse worker who sees a part in Quarantine and decides "this looks fine to me, let me move it to Serviceable" is making a quality decision they\'re not authorized to make. The decision is wrong even if the part actually is fine — because the disposition wasn\'t documented, didn\'t go through investigation, and didn\'t have authorized closure.</p>

<p>This boundary is one of the most consistently probed areas in ASA audits. An auditor will pull recent Quarantine releases and verify each was authorized by the QA Manager with documented investigation.</p>

<h4>Cycle counting and inventory reconciliation</h4>

<p>Periodic cycle counts verify that the physical inventory matches the inventory system. Cycle counts are an inventory-management practice but they also serve quality-system integrity.</p>

<h5>What cycle counts find</h5>

<ul>
  <li><strong>Physical part present, not in system.</strong> How did it get here? When was receiving inspection done? The part may be unaccounted-for inventory from a missed receiving, or worse, a part placed without going through receiving inspection at all.</li>
  <li><strong>Part in system, not physically present.</strong> Was it shipped without updating the system? Stolen? Misplaced? Damaged and destroyed without the system being updated?</li>
  <li><strong>Part in wrong location.</strong> The system says location A but the part is in location B. How did the transition record miss this move? Was the move authorized?</li>
  <li><strong>Wrong status.</strong> System says Serviceable but the physical tag is Quarantine, or vice versa. Status synchronization failure.</li>
  <li><strong>Wrong part number or serial number.</strong> The part at the location doesn\'t match what the system says is there. Wrong-part-pulled error from a prior pick, or system data entry error.</li>
</ul>

<h5>How reconciliation discrepancies feed corrective action</h5>

<p>Each discrepancy generates an NCR. The corrective action investigates:</p>

<ul>
  <li>Why did this discrepancy occur?</li>
  <li>Is this a one-off (data entry error, pick error) or systemic (procedure gap, training issue, system bug)?</li>
  <li>If systemic, what process change prevents recurrence?</li>
  <li>Are there similar discrepancies elsewhere in inventory that need investigation?</li>
</ul>

<p>A pattern of discrepancies in the same area (e.g., the ESD zone, the high-velocity rack, the engine-specific area) suggests a localized process problem worth focused investigation.</p>

<h5>Cycle count cadence</h5>

<p>Different cadences are appropriate to different inventory:</p>

<ul>
  <li><strong>High-value or LLP inventory</strong> — counted more frequently (monthly or quarterly)</li>
  <li><strong>Fast-moving inventory</strong> — counted more frequently (high handling means more error opportunity)</li>
  <li><strong>Standard inventory</strong> — counted on a rolling schedule (e.g., 10% per month, full inventory cycled annually)</li>
  <li><strong>Quarantine and Scrap zones</strong> — counted on every cycle because they\'re smaller and higher-stakes</li>
</ul>

<p>The TurbineWorks QAM specifies the cadence. ASA auditors will check cycle count records during the on-site audit.</p>

<h4>Transition record retention</h4>

<p>Transition records are retained per the records-retention schedule (Module 5 covers this in depth). Typical retention: 7+ years for transitions of serialized parts; same for non-serialized lots. Transitions affecting LLPs may be retained longer because the LLP\'s full life history must be preservable for the part\'s entire service life.</p>

<p>Electronic records are acceptable provided the standard requirements (durability, backup, tamper-evidence, retrievability) are met. Most modern inventory systems maintain transition logs natively.</p>

<h4>Common segregation failures and how they happen</h4>

<h5>Failure 1 — Tag damage or removal</h5>
<p>A serviceable tag gets torn or smudged. The part is still in inventory but the tag is now illegible. Without the tag, the part\'s status is uncertain. If a picker handles the part during this window, they may treat it as serviceable (because that\'s where it\'s located) without verifying. <strong>Response:</strong> tag is restored or replaced before any further handling; QA Manager notified if the original tag content is no longer determinable.</p>

<h5>Failure 2 — Inventory system lag</h5>
<p>The picker takes a part out of serviceable for a customer order. The pick is recorded in the system minutes later. During the gap, another customer query might show the part as available when it\'s actually being packaged. Worse, if the system records the pick incorrectly (wrong part), the actual part shipped doesn\'t match the system record. <strong>Response:</strong> picks are recorded in real time using barcode scanning or similar; pick errors are caught at shipping inspection (the part-vs-paperwork verification).</p>

<h5>Failure 3 — Quarantine drift</h5>
<p>A part is placed in Quarantine pending investigation. The investigation takes longer than expected. Months pass. The Quarantine tag fades, the NCR record drifts in the system, the original receiving inspector and possibly the QA Manager have moved on. The part is now in Quarantine purgatory — no one remembers exactly why or what to do about it. <strong>Response:</strong> QA Manager monitors Quarantine inventory regularly; aged Quarantine items trigger escalation; no part stays in Quarantine indefinitely without active investigation.</p>

<h5>Failure 4 — Scrap diversion</h5>
<p>A part dispositioned to Scrap is not mutilated promptly. It sits in Scrap area for weeks. During that time, an employee with bad intent (or just unaware of mutilation requirements) takes the part out — either to use it for some other purpose or to sell it externally. <strong>Response:</strong> Scrap is mutilated on a regular cadence; Scrap inventory is access-controlled; mutilation is witnessed by QA Manager and documented.</p>

<h5>Failure 5 — Location mismatch</h5>
<p>The system says part is in location A1-23. Picker goes to A1-23 and finds the location empty (or finds a different part). The actual part is at A1-32 (transposed). Picker spends 20 minutes searching. <strong>Response:</strong> location mismatches trigger an NCR. Repeated mismatches in the same area suggest a labeling or system issue worth investigating. Locations are clearly marked and the system uses verifiable location codes.</p>

<h4>What an auditor probes</h4>

<p>Specific audit questions a TurbineWorks employee may face:</p>

<ul>
  <li>"Walk me through the last 10 transitions out of Quarantine. Are they all documented? Did the QA Manager authorize each?"</li>
  <li>"Show me a part that has been scrapped this quarter. Where is the mutilation record? Who witnessed the destruction?"</li>
  <li>"Pick a random part from serviceable inventory. Show me its complete transition history from receipt to today."</li>
  <li>"Show me the cycle count records for the last six months. How many discrepancies were found, and what corrective actions resulted?"</li>
  <li>"Show me your Quarantine inventory. For each item, what\'s the open NCR and where is the investigation?"</li>
  <li>"What\'s the oldest item in Quarantine? Why is it still there?"</li>
</ul>

<p>An employee who can answer these clearly demonstrates that the segregation system is functioning. Inability to answer, or finding gaps in the documented chain, is a finding.</p>

<h4>References</h4>

<ul>
  <li><strong>ASA-100 §7</strong> — Storage and Handling (segregation specifics)</li>
  <li><strong>ASA-100 §8</strong> — Recordkeeping (transition record retention; covered in Module 5)</li>
  <li><strong>FAA AC 21-38</strong> — Disposition of Unsalvageable Aircraft Parts (mutilation procedures for Scrap; Module 5)</li>
  <li>TurbineWorks QAM Section [TBD] — Segregation and Status Transition Procedure</li>
</ul>

<h4>Self-check</h4>

<ol>
  <li>What is the transition matrix and why is it intentionally restrictive?</li>
  <li>Name four transitions that are NOT permitted under ASA-100.</li>
  <li>What 6 pieces of information does every transition record contain?</li>
  <li>Why do transitions OUT of Quarantine require QA Manager authorization?</li>
  <li>What 5 things can cycle counts find?</li>
  <li>What is "Quarantine drift" and how is it prevented?</li>
  <li>What is "Scrap diversion" and what controls prevent it?</li>
  <li>What 6 audit questions might a TurbineWorks employee face on segregation?</li>
</ol>

<p><em>[TurbineWorks Procedure Reference: insert TurbineWorks inventory system reference, transition log access, and cycle-count schedule here.]</em></p>
HTML
        ],
        [
            'name'  => 'Lesson 4.3 — Shelf-Life Management',
            'intro' => '<p>Parts with limited storage life — identifying them, tracking them, rotating stock, dispositioning at expiration. The most common single source of "found expired parts in inventory" audit findings industry-wide, and the operational discipline that prevents it.</p>',
            'content' => <<<'HTML'
<h3>Shelf-Life Management</h3>

<p>Many aviation parts have a defined storage life beyond which they cannot be sold for installation. The shelf-life clock starts at the manufacturer\'s specified date (usually the <em>cure date</em> for elastomers, or the <em>manufacture date</em> for sealants, adhesives, and batteries) and ends at an expiration date determined by adding the OEM-specified shelf life to the start date.</p>

<p>An expired part is not necessarily a damaged part. It may look pristine; it may even still function. But the regulatory and contractual reality is binary — once expired, the part cannot be sold for installation on a type-certificated aircraft. The OEM\'s warrant of conformance expires with the shelf life.</p>

<p>This lesson covers identifying which parts have shelf lives, tracking the expiration clock, rotating stock to ship oldest first, and properly dispositioning parts that reach expiration. The discipline is operationally simple but consistently fails at distributors that don\'t treat it as a deliberate practice.</p>

<h4>Why shelf-life is a real engineering concern, not just paperwork</h4>

<p>Some aviation employees develop the view that shelf-life is a regulatory technicality — that the part is fine, the date is just the OEM\'s liability hedge. This is wrong. Shelf-life limits reflect real material science:</p>

<ul>
  <li><strong>Elastomers</strong> (O-rings, seals, gaskets) chemically age. The polymer cross-links continue to evolve over time even in storage. Hardness changes; compression set increases. After years in storage, an O-ring may install but fail prematurely from accelerated aging.</li>
  <li><strong>Sealants and adhesives</strong> undergo slow chemical changes — partial polymerization, solvent migration, viscosity change. A sealant past its shelf life may not bond properly when applied.</li>
  <li><strong>Batteries</strong> self-discharge and the electrolyte chemistry evolves. Lithium primary cells lose capacity. Lead-acid batteries sulfate. A battery past its shelf life may have insufficient capacity for its rated application.</li>
  <li><strong>Inflatables</strong> (life vests, slides, masks) have elastomeric or coated-fabric components that age. The inflation mechanism may also have shelf-life-limited gas cartridges or pyrotechnic actuators.</li>
  <li><strong>Pyrotechnics</strong> degrade unpredictably. An expired pyrotechnic might fail to function (worse: might function partially or with degraded timing).</li>
</ul>

<p>The OEM\'s shelf-life specification is the OEM\'s engineering judgment about when the part can no longer be relied on to meet specification. Selling an expired part to a customer is selling a part the OEM has effectively withdrawn from service. The customer who installs it does so without the OEM\'s warrant.</p>

<h4>Common shelf-life-limited categories at TurbineWorks</h4>

<h5>Elastomers</h5>

<ul>
  <li>O-rings (Buna-N, fluorocarbon, EPDM, silicone, etc. — each chemistry has different shelf life)</li>
  <li>Gaskets and seals</li>
  <li>Hoses with rubber components</li>
  <li>Boots, bellows, diaphragms</li>
  <li>Vibration mounts</li>
</ul>

<p>Typical shelf life: 5–10 years from cure date for many fluorocarbon and Buna-N parts, shorter for some specialty compounds. The actual life is specified by the OEM (or by mil-spec) — verify against current OEM documentation. Some industries (military, NASA) use shorter shelf lives than commercial.</p>

<h5>Sealants and adhesives</h5>

<ul>
  <li>Fuel tank sealants (PR-1422, AMS-S-8802, etc.)</li>
  <li>Aerospace adhesives (epoxy systems, structural adhesives)</li>
  <li>RTV silicones</li>
  <li>Anaerobic adhesives (thread-lockers, retaining compounds)</li>
  <li>Cyanoacrylates (limited aviation use but present in some assemblies)</li>
</ul>

<p>Typical shelf life: 6–24 months from manufacture date. Refrigerated storage extends shelf life for some products. The OEM specification controls.</p>

<h5>Batteries</h5>

<ul>
  <li>Aircraft batteries (lead-acid, nickel-cadmium) — typically shorter shelf life than installed life</li>
  <li>Emergency Locator Transmitter (ELT) batteries — typical 5-year shelf life; replaced on schedule</li>
  <li>Underwater Locator Beacon (ULB / pinger) batteries</li>
  <li>Backup batteries for FADECs, flight recorders, emergency lighting</li>
  <li>Lithium primary cells in various electronic applications</li>
</ul>

<p>Battery shelf life varies by chemistry. Storage temperature matters significantly — many battery types have spec\'d shelf lives at room temperature with longer life under refrigerated storage.</p>

<h5>Inflatables and emergency equipment</h5>

<ul>
  <li>Life vests</li>
  <li>Slide/rafts</li>
  <li>Emergency oxygen masks (some types)</li>
  <li>Crash position indicator marker buoys</li>
</ul>

<p>These typically have service-life rather than shelf-life — the part is overhauled rather than scrapped at the end of life. But during storage as new inventory, the cure-date and shelf-life clock applies.</p>

<h5>Pyrotechnics</h5>

<ul>
  <li>Fire bottle squibs and cartridges</li>
  <li>Escape slide inflation actuators</li>
  <li>Ejection seat charges</li>
  <li>Flare pyrotechnics</li>
  <li>Emergency exit explosive bolts</li>
</ul>

<p>Pyrotechnics have strict shelf lives and require hazmat handling (Module 8). Storage conditions matter — temperature, humidity, segregation from incompatible materials.</p>

<h5>Specialty fluids and lubricants</h5>

<ul>
  <li>Some mil-spec hydraulic fluids in sealed containers</li>
  <li>Some greases (especially specialty aerospace lubricants)</li>
  <li>Engine starting compounds</li>
  <li>Coolants and de-icing fluids in specific applications</li>
</ul>

<p>Most engine oils and hydraulic fluids in unopened sealed containers have very long shelf lives (10+ years), but specialty fluids may be shorter.</p>

<h4>Tracking shelf-life at receiving</h4>

<p>Every shelf-life-limited part receives specific handling at receiving inspection:</p>

<h5>Information captured</h5>

<ul>
  <li><strong>Cure date</strong> (for elastomers) or <strong>manufacture date</strong> (for sealants, adhesives, batteries, etc.) — read from the part marking or supplier documentation</li>
  <li><strong>Shelf life per OEM/spec</strong> — verified against current OEM documentation; not assumed from prior knowledge</li>
  <li><strong>Calculated expiration date</strong> — cure or manufacture date plus shelf life</li>
  <li><strong>Storage requirements</strong> — temperature range, humidity range, lighting restrictions, segregation requirements</li>
  <li><strong>Lot or batch number</strong> — for traceability if a defect is discovered in the lot</li>
</ul>

<h5>Tagging</h5>

<p>The serviceable tag for shelf-life-limited parts includes the expiration date. The expiration date is also entered into the inventory system so that aging alerts can trigger.</p>

<h5>Storage placement</h5>

<p>Shelf-life-limited parts are stored where:</p>

<ul>
  <li>Environmental conditions match the OEM specification</li>
  <li>FIFO rotation is enforceable (oldest accessible first)</li>
  <li>Quick identification of expiration date is possible (tag visible without picking the part)</li>
</ul>

<h4>The inventory system\'s role</h4>

<p>The inventory system tracks expiration dates and provides several supporting functions:</p>

<ul>
  <li><strong>Aging reports</strong> — list of parts approaching expiration within a defined window (e.g., next 90 days). The QA Manager and warehouse review these periodically.</li>
  <li><strong>Pick-priority enforcement</strong> — when a customer order pulls a shelf-life part, the system identifies the oldest in-life unit, supporting FIFO.</li>
  <li><strong>Expiration alerts</strong> — automatic flags when a part reaches expiration. The system prevents pulling expired parts for customer shipment.</li>
  <li><strong>Expiration history</strong> — record of parts that have expired (where they came from, when expired, how dispositioned). Useful for inventory-cost analysis and supplier-performance review.</li>
</ul>

<h4>FIFO — first in, first out</h4>

<p>FIFO is the default rotation rule. The intent: ship the oldest stock first so newer stock doesn\'t sit on the shelf until it expires while the picker continues to grab the easy-to-reach (newer) stock.</p>

<h5>Implementation</h5>

<ul>
  <li>New stock goes to the back of the bin or shelf location</li>
  <li>Pickers grab from the front</li>
  <li>Bin organization supports FIFO — older stock is physically accessible while newer stock is behind</li>
  <li>System-supported picking identifies the oldest in-life unit and directs the picker to it</li>
</ul>

<h5>Practical challenges</h5>

<ul>
  <li><strong>Picker convenience.</strong> Pickers grab what\'s easy to reach. Newer stock at the front bypasses FIFO. Layout must make FIFO the easy path.</li>
  <li><strong>Lot mixing.</strong> If multiple lots are stored together with different cure dates, identifying the oldest in-life unit requires reading every tag. Bins should organize lots so older is in front by physical position.</li>
  <li><strong>Single-piece stock.</strong> For non-bulk parts where each item has its own location, FIFO is enforced by system pick instruction rather than physical position. Picker must follow the system.</li>
</ul>

<h5>FIFO discipline check</h5>

<p>An auditor verifying FIFO discipline pulls a part from a bin and looks at the neighbors. If the part just pulled is newer than the parts remaining, FIFO is failing. If the part pulled is the oldest in the bin, FIFO is working.</p>

<h4>What happens at expiration</h4>

<p>An expired part is moved to Unserviceable status. The transition is recorded; the QA Manager dispositions further. Options:</p>

<h5>Scrap</h5>

<p>Most common outcome. Expired elastomers, sealants, adhesives are scrapped per ASA-100 §8 and FAA AC 21-38. Mutilation is appropriate to the part type (cutting, drilling, irreversible damage; for liquids, opening the seal and labeling as expired). Mutilation is documented.</p>

<h5>Re-life</h5>

<p>Possible for some part types if the OEM specifically authorizes a re-life inspection. Examples: some batteries can be re-tested and re-life\'d if test results meet criteria; some pyrotechnics can be inspected and re-certified. Re-life requires:</p>

<ul>
  <li>OEM-published procedure authorizing re-life</li>
  <li>Test/inspection performed by qualified personnel or facility</li>
  <li>New release documentation reflecting the re-life action</li>
  <li>Updated expiration date based on re-life results</li>
</ul>

<p>Re-life is not informal. It cannot happen at TurbineWorks unilaterally. The OEM authorization controls.</p>

<h5>Return to manufacturer</h5>

<p>Some manufacturers offer credit for expired stock returned. This is contractual between TurbineWorks and the manufacturer. The expired part leaves TurbineWorks inventory through this return path (recorded as such) rather than through scrap.</p>

<h5>Sale to non-aviation use</h5>

<p>Some expired aviation parts have legitimate non-aviation applications (e.g., expired elastomer O-rings can sometimes be sold as industrial parts). This is allowed but requires explicit documentation that the part is being sold for non-aviation use. The part cannot be sold with any aviation documentation; the buyer must be aware the part is expired for aviation purposes.</p>

<h5>What CANNOT happen</h5>

<ul>
  <li><strong>Expired parts cannot be re-tagged as serviceable.</strong> An ASA auditor will specifically look for expired parts in serviceable inventory. Finding one is a hard finding — one of the most common in first audits.</li>
  <li><strong>Expired parts cannot be "ignored" in inventory.</strong> They must be dispositioned. Sitting expired-but-undecided is itself a quality system failure.</li>
  <li><strong>Expired parts cannot be quietly disposed of without records.</strong> Disposition is recorded. Scrap is documented.</li>
</ul>

<h4>Environmental conditions affecting shelf life</h4>

<p>Shelf life specifications assume proper storage conditions. Improper conditions accelerate aging and effectively reduce shelf life. The OEM\'s 5-year shelf life assumes the part has been stored at the OEM\'s specified temperature and humidity; storage outside that range may reduce effective life significantly.</p>

<h5>Critical environmental factors</h5>

<ul>
  <li><strong>Temperature.</strong> Elastomers degrade exponentially faster at high temperatures. An OEM 10-year shelf life at 70°F may be 3-4 years at 85°F. Refrigerated storage (32-40°F) extends shelf life for many materials.</li>
  <li><strong>UV exposure.</strong> Direct sunlight or high-UV lighting degrades rubber, some plastics, certain elastomers. Store light-sensitive items in opaque containers or shaded areas.</li>
  <li><strong>Humidity.</strong> High humidity causes corrosion in ferrous parts, moisture absorption in moisture-sensitive components, mold growth in some packaging materials. Low humidity can cause static buildup and brittleness in some materials.</li>
  <li><strong>Ozone exposure.</strong> Ozone is particularly aggressive on rubber. Keep elastomers away from sources of ozone — electric motors, ozone-producing equipment, some types of UV light. A few hours of high-ozone exposure can degrade rubber more than years of normal aging.</li>
  <li><strong>Atmospheric contamination.</strong> Industrial pollutants, salt air (coastal locations), chemical fumes — all may affect specific materials.</li>
</ul>

<h5>Environmental monitoring</h5>

<p>Temperature and humidity in storage areas are monitored, typically with continuous data-logging sensors. Logs are retained as part of quality records. Excursions outside spec are documented:</p>

<ul>
  <li>What environmental condition went out of spec</li>
  <li>How long it remained out of spec</li>
  <li>What parts may have been affected</li>
  <li>Whether the excursion was severe enough to warrant re-evaluation of affected parts</li>
</ul>

<p>Significant excursions (HVAC failure for 24+ hours, refrigerator failure, fire, water leak) trigger explicit QA Manager review. Affected parts may be moved to Quarantine pending engineering or OEM consultation on whether their effective shelf life has been compromised.</p>

<h4>What the auditor checks</h4>

<p>ASA-100 §7 audits give specific attention to shelf-life discipline. Common audit activities:</p>

<ul>
  <li>Random pull of shelf-life parts and verification that dates are legible and in life</li>
  <li>Inspection of inventory for any expired parts present in serviceable storage (hard finding)</li>
  <li>FIFO discipline check — pulling a part and examining neighboring stock</li>
  <li>Environmental records review — temperature/humidity logs available, excursions documented</li>
  <li>Disposition records for expired parts — what happened to them, when, who authorized</li>
  <li>Review of the aging report and follow-up on items approaching expiration</li>
  <li>Specific check on hazmat-classified shelf-life items (pyrotechnics, batteries) for proper segregation</li>
</ul>

<h5>Common audit findings</h5>

<ul>
  <li>Expired part in serviceable inventory (the canonical finding)</li>
  <li>Cure dates illegible on packaging — cannot verify in-life status</li>
  <li>FIFO failure — older stock blocked behind newer</li>
  <li>Environmental monitoring records gaps — sensor failures, missing logs</li>
  <li>Expiration excursions documented but no corrective action recorded</li>
  <li>Aging report shows items approaching expiration but no follow-up action</li>
</ul>

<h4>References</h4>

<ul>
  <li><strong>ASA-100 §7</strong> — Storage and handling requirements (shelf-life specific)</li>
  <li><strong>OEM specifications and IPCs</strong> — authoritative shelf-life data per part</li>
  <li><strong>Mil-spec / industry standards</strong> — for parts produced to military or industry specifications (e.g., AMS-S-8802 for fuel tank sealants)</li>
  <li><strong>FAA AC 21-38</strong> — Disposition of unsalvageable parts (covers Scrap disposition of expired items)</li>
  <li>TurbineWorks QAM Section [TBD] — Shelf-Life Management Procedure</li>
</ul>

<h4>Self-check</h4>

<ol>
  <li>Why is shelf-life a real engineering concern and not just paperwork?</li>
  <li>Name 5 shelf-life-limited categories common at TurbineWorks.</li>
  <li>What information is captured at receiving for every shelf-life-limited part?</li>
  <li>What is FIFO and what 4 implementation elements support it?</li>
  <li>What 4 disposition options exist for expired parts?</li>
  <li>What disposition is NOT permitted for expired parts?</li>
  <li>Name 5 environmental factors that affect shelf life.</li>
  <li>What does an auditor typically check during shelf-life audit activities?</li>
  <li>What is the canonical "expired-parts" audit finding and why is it considered hard?</li>
</ol>

<p><em>[TurbineWorks Procedure Reference: insert TurbineWorks shelf-life monitoring SOP, the list of part categories tracked, and the environmental monitoring system reference here.]</em></p>
HTML
        ],
        [
            'name'  => 'Lesson 4.4 — FOD Prevention',
            'intro' => '<p>Foreign Object Damage prevention. NAS 412 framework, the practical disciplines that prevent FOD, tool control, FOD walks, what to do when FOD is suspected. The hardest discipline to maintain consistently and one of the highest-impact failures when it lapses.</p>',
            'content' => <<<'HTML'
<h3>Foreign Object Damage (FOD) Prevention</h3>

<p><strong>FOD — Foreign Object Damage</strong> — is any damage to a part caused by a foreign object during handling, packaging, or storage. In aviation, FOD can be catastrophic. A single small nick on a compressor blade leading edge can propagate into a crack and cause in-service failure. A bolt left in a fuel tank during assembly can puncture a tank wall under flight loads. A loose washer in a control linkage can jam the linkage at a critical phase of flight.</p>

<p>FOD prevention is a deliberate, ongoing discipline. It cannot be intermittent — a 99%-effective FOD program is still failing 1% of the time, and that 1% might be a fan blade leading edge or a fuel control inlet. This lesson covers the FOD framework defined by NAS 412 (the National Aerospace Standard for FOD prevention), the practical disciplines that TurbineWorks operates, and what every employee needs to know and do.</p>

<h4>NAS 412 — the aerospace FOD prevention standard</h4>

<p><strong>NAS 412</strong> (National Aerospace Standard 412), maintained by the Aerospace Industries Association (AIA), is the industry-standard reference for FOD prevention programs. It is widely adopted across aerospace manufacturing, maintenance, and distribution. ASA-100 does not formally require NAS 412 compliance but virtually all credible distributor FOD programs implement NAS 412 principles. AS9100/AS9120 customers may explicitly require NAS 412 conformance.</p>

<h5>What NAS 412 establishes</h5>

<p>NAS 412 defines the elements of a credible FOD prevention program. The core elements:</p>

<ol>
  <li><strong>Documented FOD prevention program.</strong> Written, controlled, periodically reviewed and updated.</li>
  <li><strong>Designated FOD program manager.</strong> A specific person responsible for program effectiveness (at TurbineWorks, this typically rolls up to the QA Manager).</li>
  <li><strong>Defined FOD-Critical Areas.</strong> Specific physical zones where FOD prevention discipline is enforced — receiving, storage, packaging, shipping for sensitive part types.</li>
  <li><strong>Personnel training.</strong> Every employee working in or entering FOD-Critical Areas receives FOD training, with documented training records.</li>
  <li><strong>Tool control.</strong> Tools used in FOD-Critical Areas are accounted for at start and end of every shift.</li>
  <li><strong>Hardware control.</strong> Loose hardware (bolts, washers, etc.) used in handling is similarly accounted for.</li>
  <li><strong>FOD walks.</strong> Routine visual sweeps of FOD-Critical Areas to identify and remove FOD generators.</li>
  <li><strong>Housekeeping standards.</strong> Cleanliness expectations, debris control, food/beverage prohibition in critical areas.</li>
  <li><strong>FOD reporting and investigation.</strong> Any FOD event or near-miss is reported and investigated for root cause.</li>
  <li><strong>Continuous improvement.</strong> The program reviews findings and adjusts procedures, training, and controls.</li>
</ol>

<p>The TurbineWorks FOD prevention program implements these elements. The QAM should reference NAS 412 and identify how each element is satisfied.</p>

<h4>Sources of FOD in a warehouse environment</h4>

<p>FOD generators come from everywhere. Understanding the categories helps target prevention:</p>

<h5>Tools and equipment</h5>

<p>The single largest source category. Tools that leave a workstation become FOD when they migrate to a part:</p>

<ul>
  <li>Hand tools (screwdrivers, pliers, wrenches) left in or near parts</li>
  <li>Cutting tools (knives, scissors, deburring tools)</li>
  <li>Measuring tools (calipers, gauges, scales)</li>
  <li>Cleaning tools (brushes, cloths, applicators)</li>
  <li>Marking and tagging tools (pens, stamps, label printers)</li>
</ul>

<p>A torque wrench left inside a part\'s packaging can disappear into the customer shipment. A measuring caliper resting on a part during inspection can fall into a port. A pen in a pocket can drop into an open assembly.</p>

<h5>Hardware</h5>

<p>Loose fasteners are perfect FOD — small, hard, often metal:</p>

<ul>
  <li>Bolts, nuts, washers, screws — on floors, shelves, in bins where they don\'t belong</li>
  <li>Cotter pins, lockwire ends, safety wire scraps</li>
  <li>Spare hardware from kitting operations</li>
  <li>Damaged or rejected hardware not immediately disposed of</li>
</ul>

<h5>Packaging debris</h5>

<ul>
  <li>Cardboard fragments from cartons</li>
  <li>Tape pieces, peeling tape, double-stick residue</li>
  <li>Plastic bag scraps</li>
  <li>Foam pieces, especially small chunks of polystyrene</li>
  <li>Bubble wrap pops and fragments</li>
  <li>Shrink wrap remnants</li>
  <li>Banding metal or plastic</li>
  <li>Paper and labels</li>
</ul>

<h5>Personal items</h5>

<ul>
  <li>Pens, pencils, markers in pockets</li>
  <li>Coins and small change</li>
  <li>Jewelry (rings, bracelets, necklaces, earrings, watches) — can fall off, especially during physical handling</li>
  <li>Eyeglasses, especially loose nose pads</li>
  <li>Phone earbuds, especially with detachable tips</li>
  <li>Buttons, snaps, decorative items on clothing</li>
  <li>Hair clips, bobby pins</li>
</ul>

<h5>Food, beverages, biological</h5>

<ul>
  <li>Food crumbs, wrappers, packaging</li>
  <li>Beverage spills, ice cubes, straws</li>
  <li>Hair, dandruff, skin flakes (longer hair restrained; beards may need covers in some operations)</li>
  <li>Gum, mints, candy wrappers</li>
</ul>

<h5>Building materials and environmental sources</h5>

<ul>
  <li>Concrete chips and dust from floor wear</li>
  <li>Paint flakes from walls or fixtures</li>
  <li>Ceiling tile dust, especially after HVAC work</li>
  <li>Insulation fragments</li>
  <li>Sawdust from any wood being worked nearby</li>
  <li>Metal shavings or chips from any machining activity</li>
  <li>Dust accumulation on shelves and equipment</li>
</ul>

<h5>Other parts</h5>

<ul>
  <li>Parts bumping into each other during transport</li>
  <li>Unprotected critical surfaces contacting each other</li>
  <li>Components shedding scale, plating fragments, or coatings</li>
  <li>Edges or burrs that scrape adjacent parts</li>
</ul>

<h4>FOD-sensitive surfaces (what FOD damages)</h4>

<p>Some surfaces on aviation parts are particularly vulnerable to FOD. Understanding these helps prioritize protection:</p>

<h5>Turbine and compressor blade leading edges</h5>

<p>The single biggest concern in engine parts. A nick or dent on a blade leading edge creates a stress concentration. Under operating cyclic loading, the stress concentration can initiate a fatigue crack. The crack propagates over operating cycles until catastrophic blade failure — possibly an uncontained engine failure with severe consequences.</p>

<p>OEM blade-edge inspection limits are tight. A nick a millimeter or two deep may be within blendable limits; a nick a few millimeters may require blade replacement. The cost of a single damaged blade can run tens of thousands of dollars; the cost of an undetected damaged blade installed and run to failure is far higher.</p>

<h5>Sealing surfaces</h5>

<ul>
  <li>Bearing races — must be smooth for proper rolling element contact</li>
  <li>O-ring grooves — must be free of nicks that would cut or damage the seal</li>
  <li>Mating flanges where two parts seal against each other — any surface defect creates a leak path</li>
  <li>Polished shaft surfaces where seals ride — scratches damage the seal</li>
</ul>

<h5>Optical and electronic interfaces</h5>

<ul>
  <li>Flame detectors and other optical sensors — surface debris obscures the sensing element</li>
  <li>Vibration sensors with optical coupling</li>
  <li>Connector pins — bent pins from FOD contact prevent mating</li>
  <li>Terminal blocks — contamination affects conductivity</li>
</ul>

<h5>High-precision functional surfaces</h5>

<ul>
  <li>Fuel injector nozzles — internal passages must be free of obstruction</li>
  <li>Gear teeth — surface damage propagates through the gear under load</li>
  <li>Servo valves with precision spool fits</li>
  <li>Bushings and bearings with precise dimensional fits</li>
</ul>

<h4>FOD-sensitive surfaces are protected during storage</h4>

<p>Standard protections for FOD-sensitive surfaces:</p>

<ul>
  <li>Caps on bearing seats and shaft ends</li>
  <li>Plugs in ports (fuel, hydraulic, oil, pneumatic, electrical connector caps)</li>
  <li>Individual wrapping on blades — typically tissue or specialty cushioning material</li>
  <li>Edge protectors on rotor disks — molded plastic or foam pieces matching the part geometry</li>
  <li>Anti-FOD bags for small parts</li>
  <li>Static-controlled and dust-free packaging for electronics</li>
</ul>

<p>These protections accompany the part through TurbineWorks custody and ideally accompany the part to the customer. Removing protection prematurely (e.g., during a quick visual inspection) without replacing creates FOD exposure.</p>

<h4>FOD-Critical Areas at TurbineWorks</h4>

<p>NAS 412\'s concept of "FOD-Critical Areas" translates to specific zones at TurbineWorks. These are areas where FOD prevention is actively enforced through discipline rather than just casually expected:</p>

<ul>
  <li><strong>Receiving inspection area</strong> — where packages are opened and parts physically inspected</li>
  <li><strong>Serviceable storage</strong> — where accepted parts are held</li>
  <li><strong>Quarantine</strong> — where parts under investigation are held (FOD here can complicate the investigation)</li>
  <li><strong>Shipping / packaging area</strong> — where outbound shipments are prepared</li>
  <li><strong>ESD-controlled areas</strong> — which are also typically FOD-controlled because the same parts that are ESD-sensitive are often FOD-sensitive</li>
</ul>

<p>FOD-Critical Areas should be marked. Personnel entering should know they are entering a FOD-controlled environment. Visitors are escorted and briefed.</p>

<h4>FOD-Critical Area rules</h4>

<p>In a FOD-Critical Area, certain practices are mandatory:</p>

<ul>
  <li><strong>No food or beverages.</strong> Including water bottles unless explicitly contained per QAM (some operations allow capped containers on shelves outside immediate work area; others prohibit entirely).</li>
  <li><strong>No loose tools or hardware on work surfaces.</strong> Tools in use are on a controlled work surface; tools not in use are returned to their designated location (tool shadowboard, tool cabinet).</li>
  <li><strong>Pocket discipline.</strong> Personnel empty pockets before entering, or use pocket organizers that prevent items from falling out (pockets sewn shut, zippered pockets, etc.). Some operations prohibit certain pocket items entirely.</li>
  <li><strong>Jewelry restrictions.</strong> Rings, watches, bracelets typically removed. Some operations allow plain wedding bands; others require all jewelry off.</li>
  <li><strong>Hair restraint.</strong> Long hair tied back. Some operations require hair covers (especially during direct part handling). Beards may need covers in some operations.</li>
  <li><strong>Clothing constraints.</strong> Loose buttons removed or secured. Decorative items removed. Some operations use FOD-controlled smocks or coveralls.</li>
  <li><strong>Tool accountability.</strong> Tools used are accounted for at start and end of every shift.</li>
  <li><strong>FOD walks.</strong> Performed at defined intervals — typically start of shift, after breaks, before opening sensitive parts, after any handling event.</li>
</ul>

<h4>Tool control — the operational discipline</h4>

<p>Tool control is the single most-developed FOD prevention practice. Every tool used in a FOD-Critical Area is accounted for. The discipline:</p>

<h5>Tool shadowboards</h5>

<p>Tools are stored on a board with outlines showing each tool\'s location. At a glance, you can see which tools are present and which are missing. A missing tool is immediately apparent.</p>

<h5>Tool checkout</h5>

<p>For tools that leave their home location, a checkout log records who has the tool and when it was taken. The log may be physical (sign-out sheet) or electronic (barcode scan, RFID).</p>

<h5>End-of-shift accountability</h5>

<p>At the end of every shift, every tool used during that shift is returned to its home location. The shadowboard or checkout log is reconciled. Missing tools trigger immediate action.</p>

<h5>Lost-tool protocol</h5>

<p>A tool that cannot be accounted for at end of shift initiates a structured response:</p>

<ol>
  <li>Immediate search of the work area where the tool was last used</li>
  <li>Search of any parts handled during the shift — could the tool be inside packaging?</li>
  <li>Search of trash receptacles emptied during the shift</li>
  <li>Notification to QA Manager and to other personnel who may have been in the area</li>
  <li>If the tool cannot be located, customer notification for any shipments that left during the period — were any of them potentially compromised?</li>
  <li>NCR opened; investigation continues until either the tool is found or the investigation concludes the tool is unrecoverable</li>
</ol>

<p>Lost-tool incidents are rare but consequential. The structured response prevents a missing torque wrench from being discovered inside a customer\'s engine three weeks after shipment.</p>

<h4>FOD walks — the routine sweep</h4>

<p>A FOD walk is a deliberate visual sweep of an area looking for FOD generators. Walks are not casual glances — they\'re a focused activity with a specific purpose and a defined area to cover.</p>

<h5>When FOD walks are performed</h5>

<ul>
  <li>Start of shift</li>
  <li>After breaks (when personnel may have brought items back into the area)</li>
  <li>Before opening packaging on FOD-sensitive parts</li>
  <li>After completing handling operations</li>
  <li>After any unusual event — dropped item, spilled material, packaging incident, tool dropped</li>
  <li>End of shift, as the area is being prepared for the next shift</li>
</ul>

<h5>FOD walk procedure</h5>

<ol>
  <li>Walk the area systematically — don\'t miss corners, under shelves, behind equipment</li>
  <li>Look on horizontal surfaces (floor, shelves, work surfaces) and vertical (walls, fixtures where items might cling)</li>
  <li>Pick up anything found that doesn\'t belong</li>
  <li>If a FOD item is found, identify the source — where did it come from?</li>
  <li>Log significant findings in the FOD walk record</li>
  <li>If FOD risk to specific parts is identified, those parts go for inspection</li>
</ol>

<h5>FOD walk records</h5>

<p>The walk is recorded — date, time, area, personnel, findings. Records support audit verification and help identify pattern problems (e.g., the receiving area consistently produces packaging debris on the floor; the packaging procedure should be reviewed).</p>

<h4>Damage from FOD — categories</h4>

<p>FOD damage to parts comes in three categories:</p>

<h5>Obvious damage</h5>

<p>Visible nick, dent, scratch, gouge on a critical surface. Identified during physical inspection. Part goes to Unserviceable for evaluation. Engineering or qualified inspection determines whether the damage is within OEM blendable limits (rare, depending on location) or requires repair / scrap.</p>

<h5>Subtle damage</h5>

<p>A small mark that may or may not be acceptable. Requires evaluation by qualified inspection — possibly dimensional measurement, surface analysis, or comparison against OEM blendable-limit drawings. The TurbineWorks inspector may not have authority to disposition; refers to QA Manager or to a qualified inspection service.</p>

<h5>Latent damage</h5>

<p>Damage that initiates a crack or stress concentration but doesn\'t manifest until the part is in service. The part passes visual inspection. The part is sold. The part is installed. Failure occurs months or years later. This is the most insidious form of FOD damage because there is no detection at the receiving distributor.</p>

<p>The defense against latent FOD damage is to <em>prevent the FOD event entirely</em>. Once FOD has contacted a sensitive surface, latent damage may exist regardless of whether visible damage is observed.</p>

<h4>What you cannot do</h4>

<ul>
  <li><strong>Do not "polish out" FOD damage.</strong> Polishing is a form of repair. Repair authority is limited to authorized repair stations. Polishing out a nick on a turbine blade leading edge — even if it appears to remove the visible damage — is unapproved repair.</li>
  <li><strong>Do not "blend out" damage.</strong> Same principle. Blending is engineering-authorized rework within OEM limits, performed by qualified personnel with proper documentation.</li>
  <li><strong>Do not hide FOD damage.</strong> Repackaging a part without disclosing the discovered FOD is misrepresentation and fraud.</li>
  <li><strong>Do not assume FOD-handled parts are still serviceable just because they look OK.</strong> Latent damage may exist. The conservative response is to move the part to Unserviceable for evaluation.</li>
</ul>

<h4>Reporting FOD incidents and near-misses</h4>

<p>Every FOD event — including near-misses — is reported. Examples that warrant reporting:</p>

<ul>
  <li>Found a tool inside a part\'s packaging during a check (no damage to the part, but the FOD escape was a near-miss)</li>
  <li>Dropped a part during handling (no visible damage, but inspection required)</li>
  <li>Found loose hardware on a serviceable parts shelf (FOD generator, even if no part was damaged)</li>
  <li>Spotted that a packaging cap was missing from a stored part (FOD exposure occurred)</li>
  <li>Discovered debris (paint flakes, dust) on parts during a routine walk</li>
  <li>FOD walk found a foreign object — even if no immediate damage</li>
</ul>

<h5>Why near-miss reporting matters</h5>

<p>Near-misses are early indicators of system problems. They\'re free information about where the system is weak before it produces an actual failure. Reporting near-misses lets the QA Manager improve procedures, training, or controls before the next event becomes a real defect.</p>

<p>Cultures where near-miss reporting is discouraged (because reporters get blamed) inevitably end up with actual failures that surprise everyone. Cultures where near-miss reporting is encouraged surface problems early and fix them.</p>

<h5>The corrective action loop</h5>

<p>Every FOD incident (including near-misses) opens an NCR. The investigation:</p>

<ul>
  <li>What happened</li>
  <li>What could have happened (severity if the near-miss had become a real event)</li>
  <li>Root cause — why did this happen?</li>
  <li>Corrective action — what changes prevent recurrence?</li>
  <li>Verification — is the corrective action effective?</li>
</ul>

<h4>What the auditor probes</h4>

<ul>
  <li>"Show me your FOD prevention program documentation."</li>
  <li>"What\'s your FOD walk schedule? When was the last walk performed?"</li>
  <li>"Show me a tool checkout log from yesterday."</li>
  <li>"Has any tool ever been lost? If so, walk me through what happened."</li>
  <li>"How are FOD-sensitive surfaces protected during storage?"</li>
  <li>"What\'s in your FOD-Critical Area trash bin right now?"</li>
  <li>"Talk to a warehouse worker — ask them what they do if they find loose hardware on a shelf."</li>
  <li>"Show me the last FOD-related NCR. What was the corrective action?"</li>
</ul>

<h4>References</h4>

<ul>
  <li><strong>NAS 412</strong> — Aerospace Industries Association FOD Prevention Standard (the primary reference)</li>
  <li><strong>National Aerospace FOD Prevention, Inc. (NAFPI)</strong> — industry organization promoting FOD awareness</li>
  <li><strong>ASA-100 §7</strong> — Storage and handling requirements include FOD prevention</li>
  <li>TurbineWorks QAM Section [TBD] — FOD Prevention Program</li>
</ul>

<h4>Self-check</h4>

<ol>
  <li>What is NAS 412 and what 10 elements does it require in a FOD prevention program?</li>
  <li>Name 7 categories of FOD generators in a warehouse environment.</li>
  <li>What are the 4 most FOD-sensitive surface types on aviation parts?</li>
  <li>What 8 rules apply in a FOD-Critical Area?</li>
  <li>Describe the lost-tool protocol — what 6 steps follow a tool that can\'t be accounted for?</li>
  <li>When are FOD walks performed (6 trigger events)?</li>
  <li>What are the 3 categories of FOD damage and which is most insidious?</li>
  <li>What 4 things should you NOT do when FOD damage is discovered?</li>
  <li>Why does near-miss reporting matter, and how does culture affect it?</li>
</ol>

<p><em>[TurbineWorks Procedure Reference: insert TurbineWorks FOD Prevention Program documentation, FOD-Critical Area diagram, tool control procedure, and FOD walk schedule here.]</em></p>
HTML
        ],
        [
            'name'  => 'Lesson 4.5 — Environmental Controls and Hazmat Storage',
            'intro' => '<p>Temperature, humidity, ESD, lighting, cleanliness, and hazmat storage in the warehouse. The environmental discipline that keeps inventory in spec across storage periods that may extend for months. Excursion handling when conditions deviate. The interface with Module 7 (ESD) and Module 8 (Hazmat).</p>',
            'content' => <<<'HTML'
<h3>Environmental Controls and Hazmat Storage</h3>

<p>Aviation parts have storage requirements that go beyond "keep them clean and dry." Specific part categories require specific environmental conditions, and excursions outside those conditions can damage parts in ways that are invisible until the part fails in service. This lesson covers temperature and humidity controls, ESD areas (interfaces with Module 7), hazmat storage (interfaces with Module 8), lighting, cleanliness, and excursion handling.</p>

<p>The framework principle: every storage area has defined environmental conditions; those conditions are monitored continuously where required; excursions outside spec trigger documented investigation; affected parts are evaluated and dispositioned. This is the discipline that turns a generic warehouse into an ASA-100-compliant storage facility.</p>

<h4>Temperature and humidity</h4>

<h5>General warehouse conditions</h5>

<p>Typical general-warehouse environmental targets:</p>

<ul>
  <li>Temperature: 60–80°F (15–27°C). Some parts tolerate wider ranges; some require tighter control.</li>
  <li>Relative humidity: 30–60% RH. Below 30% can cause static accumulation; above 60% can cause corrosion and moisture absorption.</li>
  <li>Stable conditions preferred over fluctuating. Wide diurnal swings (cold nights, hot days) can accelerate aging more than steady moderate-warm conditions.</li>
</ul>

<h5>Part-category-specific requirements</h5>

<table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse;">
  <tr style="background:#0d2240; color:#fff;">
    <th>Part Category</th>
    <th>Typical Temp Range</th>
    <th>Typical RH Range</th>
    <th>Other</th>
  </tr>
  <tr>
    <td>Elastomers (O-rings, seals)</td>
    <td>40–80°F</td>
    <td>30–50%</td>
    <td>Dark, low ozone; avoid &gt;100°F excursions which accelerate aging</td>
  </tr>
  <tr>
    <td>Sealants (PR-1422, AMS-S-8802, etc.)</td>
    <td>32–40°F (refrigerated)</td>
    <td>controlled (sealed containers)</td>
    <td>Bring to room temperature before use; OEM-specified handling</td>
  </tr>
  <tr>
    <td>Aerospace adhesives</td>
    <td>32–40°F (refrigerated, often)</td>
    <td>controlled</td>
    <td>OEM-specified; some require freezer storage (below 0°F)</td>
  </tr>
  <tr>
    <td>Aircraft batteries</td>
    <td>50–80°F</td>
    <td>30–60%</td>
    <td>Some battery chemistries benefit from refrigeration for storage</td>
  </tr>
  <tr>
    <td>Moisture-sensitive electronics</td>
    <td>60–80°F</td>
    <td>&lt;60% (often controlled with desiccant)</td>
    <td>Per IPC J-STD-033; may require dry-cabinet storage</td>
  </tr>
  <tr>
    <td>Hazmat (pyrotechnics, oxidizers)</td>
    <td>varies by material</td>
    <td>varies</td>
    <td>Per 49 CFR; specific separation requirements</td>
  </tr>
  <tr>
    <td>ESD-sensitive electronics</td>
    <td>60–80°F</td>
    <td>40–60% (avoid &lt;30%)</td>
    <td>Per ANSI/ESD S20.20</td>
  </tr>
</table>

<p>The actual specifications come from the OEM or the applicable industry standard. The TurbineWorks QAM lists requirements per part category. Pay particular attention when handling parts with refrigerator or freezer requirements — these are easy to overlook if storage isn\'t organized to support them.</p>

<h4>Environmental monitoring</h4>

<p>Storage areas with environmental requirements are monitored with sensors and logging. The monitoring infrastructure typically includes:</p>

<ul>
  <li><strong>Continuous temperature sensors</strong> in each controlled zone, logging at intervals (typically every 5–15 minutes)</li>
  <li><strong>Humidity sensors</strong> in zones with humidity requirements</li>
  <li><strong>Refrigerator/freezer thermometers</strong> for cold storage</li>
  <li><strong>Calibration</strong> of sensors per defined cadence — typically annually with documented calibration certificate</li>
  <li><strong>Alerts</strong> when conditions exceed defined thresholds — automated email or notification to QA personnel</li>
  <li><strong>Backup monitoring</strong> independent of the primary system, in case primary fails</li>
</ul>

<h5>Records</h5>

<p>Environmental logs are retained as part of quality records (typically 7+ years per ASA-100 §8). The logs show:</p>

<ul>
  <li>Continuous record of conditions in each monitored zone</li>
  <li>Any excursions and their duration</li>
  <li>Investigation records for significant excursions</li>
  <li>Sensor calibration records</li>
</ul>

<p>ASA auditors will pull these logs and look at them. Gaps in the log (sensor failures, recording outages) are findings. Excursions documented but not investigated are findings.</p>

<h4>Environmental excursion handling</h4>

<p>An "excursion" is a period when environmental conditions exceeded spec. Excursions happen — HVAC failures, refrigerator malfunctions, fire alarms triggering evacuations during which conditions drifted, water leaks. The question is how the system handles excursions.</p>

<h5>Excursion response procedure</h5>

<ol>
  <li><strong>Identify the excursion.</strong> Automated alerts or routine log review identify that conditions exceeded spec.</li>
  <li><strong>Document immediately.</strong> When was the excursion? What zone? What conditions reached? What was the duration?</li>
  <li><strong>Identify affected parts.</strong> What was in the affected zone during the excursion? The inventory system can produce this list if location tracking is current.</li>
  <li><strong>Quarantine affected parts pending evaluation.</strong> Don\'t assume the excursion didn\'t affect parts. Move to Quarantine for QA Manager review.</li>
  <li><strong>QA Manager dispositions.</strong> Depending on the part type, excursion severity, and duration, the QA Manager may:
    <ul>
      <li>Release back to serviceable (excursion brief, parts not sensitive to that condition)</li>
      <li>Re-inspect with enhanced criteria</li>
      <li>Consult OEM or engineering for guidance on whether parts can be returned to service</li>
      <li>Reduce effective shelf life (for shelf-life parts) to account for accelerated aging during the excursion</li>
      <li>Scrap if parts are confirmed damaged or cannot be confidently dispositioned</li>
    </ul>
  </li>
  <li><strong>Open NCR and corrective action.</strong> Why did the excursion happen? What prevents recurrence? Was the alert system effective? Was the response timely?</li>
  <li><strong>Documentation.</strong> All of the above is recorded. The excursion file is retained as part of quality records.</li>
</ol>

<h5>Specific excursion types and concerns</h5>

<ul>
  <li><strong>HVAC failure.</strong> Temperature drifts toward outside ambient. Depending on outside conditions, temperatures can exceed 90°F or drop below 40°F in extended failures. Affected parts: elastomers (heat-aging), batteries, some adhesives, moisture-sensitive items (humidity may also drift).</li>
  <li><strong>Refrigerator/freezer failure.</strong> Refrigerated sealants and adhesives warm. Effective shelf life is reduced; some manufacturers void warranty on materials that have exceeded temperature limits.</li>
  <li><strong>Fire or water leak.</strong> Direct physical damage to packaging and possibly parts. Parts may need destruction even if visually intact.</li>
  <li><strong>Power outage.</strong> Often combines HVAC and refrigerator failures. Duration matters significantly — 2 hours is rarely critical; 24+ hours often is.</li>
  <li><strong>Door left open.</strong> Smaller-scale but still a documented excursion. Common in shipping/receiving areas where doors are opened frequently and may be left propped.</li>
</ul>

<h4>ESD-controlled areas</h4>

<p>Module 7 covers ESD in depth. For warehouse purposes, the key points:</p>

<ul>
  <li>ESD-sensitive parts are stored in ESD-controlled zones — specifically engineered for ESD control per ANSI/ESD S20.20</li>
  <li>The ESD zone has conductive or dissipative flooring, grounded work surfaces, wrist strap stations, humidity control (typically 40–60% RH; below 30% increases static buildup dramatically)</li>
  <li>Personnel entering wear appropriate grounding (wrist straps for seated work, foot grounders or ESD-safe shoes for standing)</li>
  <li>Parts remain in ESD-safe packaging while in storage. Packaging is only opened in the ESD-controlled area with proper grounding in place</li>
  <li>The ESD zone is verified periodically — flooring resistance, work surface grounding, wrist-strap function, ionizer balance</li>
</ul>

<p>An auditor specifically tests ESD zone integrity. Testing may include:</p>

<ul>
  <li>Continuity check of grounding points</li>
  <li>Visual inspection of grounding cords and connections</li>
  <li>Wrist-strap function test</li>
  <li>Review of periodic verification records</li>
  <li>Walking on the ESD floor while wearing grounders to confirm static drains</li>
</ul>

<h4>Hazmat storage</h4>

<p>Module 8 covers hazmat in regulatory depth. For warehouse purposes:</p>

<h5>Segregation requirements</h5>

<p>Hazmat is segregated from non-hazmat AND segregated by class (incompatible classes apart from each other):</p>

<ul>
  <li><strong>Flammables (Class 3)</strong> — in fire-rated cabinets, away from ignition sources, away from oxidizers</li>
  <li><strong>Oxidizers (Class 5.1)</strong> — away from flammables, away from organics, away from combustibles</li>
  <li><strong>Corrosives (Class 8)</strong> — in containment trays to capture leaks, away from incompatible chemistries</li>
  <li><strong>Pyrotechnics (Class 1)</strong> — typically in dedicated explosive-storage cabinet or magazine</li>
  <li><strong>Lithium batteries (Class 9)</strong> — segregated, in fire-resistant containers or dedicated cabinets</li>
  <li><strong>Compressed gases (Class 2)</strong> — secured against falling, valve protection, segregated by type (flammable from non-flammable from oxidizing)</li>
</ul>

<h5>Storage container requirements</h5>

<p>Hazmat in storage is in containers that meet 49 CFR / IATA DGR requirements for the material:</p>

<ul>
  <li>UN-spec packaging for transport-ready hazmat</li>
  <li>OEM-original containers preferred while parts are in storage</li>
  <li>Damaged or compromised containers are not acceptable for hazmat storage — must be repackaged or dispositioned</li>
  <li>Containers labeled per hazmat regulations</li>
</ul>

<h5>Marking and labeling</h5>

<ul>
  <li>Hazard class diamond on the container</li>
  <li>UN number and proper shipping name</li>
  <li>Packing group indication</li>
  <li>Cargo Aircraft Only marking if applicable</li>
  <li>Quantity</li>
  <li>Lot/batch number for traceability</li>
</ul>

<h5>SDS accessibility</h5>

<p>Safety Data Sheets (SDS) for every hazmat on-site must be accessible to personnel. Typically maintained in a binder at the storage area entrance and/or electronically accessible via a defined system. SDS access during an emergency (fire, spill) must be possible without delay.</p>

<h5>Spill response</h5>

<p>Spill kits are accessible. Personnel are trained on spill response for the hazmat types stored. The QAM specifies the spill response procedure for each material type.</p>

<h5>Audit attention to hazmat</h5>

<p>ASA auditors and DOT auditors (separate scope) both check hazmat handling. Common audit checks:</p>

<ul>
  <li>Walk the hazmat storage area — visible segregation by class</li>
  <li>Pull SDS for a specific material — can you produce it within minutes?</li>
  <li>Container inspection — UN-spec where required, undamaged, properly labeled</li>
  <li>Spill kit accessibility</li>
  <li>Personnel training records — are hazmat handlers current on training?</li>
  <li>Excursion records — temperature excursions affecting hazmat (some hazmat is temperature-sensitive)</li>
</ul>

<h4>Lighting</h4>

<p>Storage area lighting matters for both visibility and material protection:</p>

<ul>
  <li><strong>General warehouse:</strong> sufficient light to read tags, perform receiving inspection, identify part details. Typical target 50–100 foot-candles in work areas.</li>
  <li><strong>UV-sensitive parts</strong> (elastomers, some plastics, photographic and optical components): protected from direct sunlight and high-UV fluorescent lighting. Some operations use UV-filtered lighting in elastomer storage areas.</li>
  <li><strong>Optical components</strong> (lenses, mirrors, detector windows): protected from contamination during handling. Lint-free gloves; white-glove protocols for some operations.</li>
  <li><strong>Emergency lighting:</strong> required by code; supports safe egress during power outages. Not specifically a quality requirement but operationally essential.</li>
</ul>

<h4>Cleanliness</h4>

<p>Warehouse cleanliness is a quality control practice, not just aesthetics. A clean warehouse:</p>

<ul>
  <li>Reduces FOD risk to parts (Lesson 4.4)</li>
  <li>Reveals problems faster (a debris-free shelf shows damage or spills immediately)</li>
  <li>Indicates discipline — a clean facility suggests an organization that takes details seriously, which auditors and customers notice</li>
  <li>Reduces contamination risk to ESD and moisture-sensitive parts</li>
</ul>

<h5>Cleanliness practices</h5>

<ul>
  <li>Regular housekeeping schedule — documented, with assigned personnel</li>
  <li>No food, no drinks in storage areas (consistent with FOD prevention rules)</li>
  <li>Floors swept regularly; spills cleaned immediately</li>
  <li>Shelving wiped down to prevent dust buildup on parts</li>
  <li>Trash removed regularly — don\'t accumulate cardboard or packaging waste in storage areas</li>
  <li>Restricted personal items (jewelry, certain pocket items, etc.) for personnel entering controlled areas</li>
</ul>

<h4>What the auditor checks</h4>

<p>Specific environmental and cleanliness audit activities:</p>

<ul>
  <li>Walk-through of storage areas — visible cleanliness, organization, segregation</li>
  <li>Environmental log review — last 12 months of temperature and humidity data, sensor calibration records</li>
  <li>Excursion records — were excursions documented? Were affected parts dispositioned? Were corrective actions effective?</li>
  <li>Hazmat segregation — incompatible classes separated, containers in good condition, SDS accessible, spill kit accessible</li>
  <li>ESD zone integrity — visible grounding, periodic verification records</li>
  <li>Lighting adequate — try to read a tag in a corner where lighting is dimmest</li>
  <li>General cleanliness — random shelf wipe-down test; look for food/beverage residue</li>
</ul>

<h5>Common audit findings</h5>

<ul>
  <li>Temperature log shows excursions but no documented investigation</li>
  <li>Hazmat containers not properly labeled or labels not legible</li>
  <li>Incompatible hazmat classes stored adjacent to each other</li>
  <li>SDS for materials on-site cannot be produced within reasonable time</li>
  <li>ESD zone grounding test fails or no recent verification records</li>
  <li>Refrigerated sealants stored at room temperature</li>
  <li>Dust accumulation on parts or shelves</li>
  <li>Food, beverages, or food residue in controlled storage</li>
</ul>

<h4>References</h4>

<ul>
  <li><strong>ASA-100 §7</strong> — Storage and handling general requirements</li>
  <li><strong>49 CFR Parts 100-185</strong> — DOT Hazmat (deeper coverage in Module 8)</li>
  <li><strong>IATA DGR</strong> — air shipment hazmat (Module 8)</li>
  <li><strong>ANSI/ESD S20.20</strong> — ESD control program (Module 7)</li>
  <li><strong>IPC J-STD-033</strong> — Moisture-sensitive device handling</li>
  <li><strong>OEM specifications</strong> — authoritative storage requirements per part</li>
  <li>TurbineWorks QAM Section [TBD] — Environmental controls and hazmat storage procedures</li>
</ul>

<h4>Self-check</h4>

<ol>
  <li>What are typical general warehouse temperature and humidity targets?</li>
  <li>Why do sealants and adhesives typically require refrigerated storage?</li>
  <li>What 6 elements does environmental monitoring infrastructure include?</li>
  <li>Describe the 7-step excursion response procedure.</li>
  <li>Why is hazmat segregated both from non-hazmat AND by class?</li>
  <li>Name 6 hazmat classes typically stored at distributors and their segregation requirements.</li>
  <li>Why is SDS accessibility important during an emergency?</li>
  <li>How is warehouse cleanliness a quality control practice?</li>
  <li>What 8 common audit findings does this lesson identify?</li>
</ol>

<p><em>[TurbineWorks Procedure Reference: insert environmental monitoring system details, hazmat storage area diagram, SDS binder location, and spill response procedure here.]</em></p>
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
            'summary'   => '<p>The SAE/IAQG aerospace QMS standard for distributors. Many ASA-100 distributors pursue AS9120 within 1-2 years of initial accreditation. ~70% training overlap with ASA-100.</p>',
            'lessons'   => local_twu_as9120_lessons(),
        ],
        [
            'shortname' => 'TWU-OPS-CUSTOMER',
            'fullname'  => 'Customer Relations &amp; Quality Communication',
            'idnumber'  => 'TWU-OPS-CUSTOMER',
            'summary'   => '<p>How to communicate quality status, non-conformance, and audit-relevant information to customers (airlines, MROs, OEMs, government).</p>',
            'lessons'   => local_twu_customer_relations_lessons(),
        ],
        [
            'shortname' => 'TWU-OPS-INTSHIP',
            'fullname'  => 'International Shipping — ITAR, EAR, and Export Control',
            'idnumber'  => 'TWU-OPS-INTSHIP',
            'summary'   => '<p>U.S. export control regimes affecting aviation parts shipments overseas. Penalties for non-compliance include criminal prosecution.</p>',
            'lessons'   => local_twu_export_control_lessons(),
        ],
        [
            'shortname' => 'TWU-ENG-LEAP',
            'fullname'  => 'CFM LEAP Family — Engine Familiarization',
            'idnumber'  => 'TWU-ENG-LEAP',
            'summary'   => '<p>CFM\'s successor to the CFM56. LEAP-1A (A320neo), LEAP-1B (737 MAX), LEAP-1C (COMAC C919). Direct-drive competitor to the PW1000G GTF.</p>',
            'lessons'   => local_twu_engine_leap_lessons(),
        ],
        [
            'shortname' => 'TWU-ENG-V2500',
            'fullname'  => 'IAE V2500 Family — Engine Familiarization',
            'idnumber'  => 'TWU-ENG-V2500',
            'summary'   => '<p>International Aero Engines (IAE) V2500 — the alternative powerplant on the A320ceo family. Joint venture of Pratt &amp; Whitney and Rolls-Royce. Strong aftermarket inventory base.</p>',
            'lessons'   => local_twu_engine_v2500_lessons(),
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

// ============================================================================
// OPERATIONS COURSE: AS9120 Familiarization (4 lessons)
// ============================================================================
function local_twu_as9120_lessons(): array {
    return [
        [
            'name'  => 'Lesson 1 — What is AS9120 and Where It Fits',
            'intro' => '<p>Origin of AS9120, its relationship to ISO 9001 and the AS9100 series, and why it matters for aviation parts distributors.</p>',
            'content' => <<<'HTML'
<h3>What is AS9120?</h3>
<p><strong>AS9120</strong> is the aerospace quality management system standard for distributors of aviation, space, and defense parts. Published by SAE International on behalf of the International Aerospace Quality Group (IAQG), AS9120 extends the general ISO 9001 QMS framework with aerospace-specific requirements.</p>

<h4>The AS9100 family of standards</h4>
<ul>
  <li><strong>AS9100</strong> — Aerospace QMS for design and manufacturing organizations (OEMs, tier-1 suppliers)</li>
  <li><strong>AS9110</strong> — Aerospace QMS for maintenance organizations (MROs, repair stations)</li>
  <li><strong>AS9120</strong> — Aerospace QMS for distributors (parts brokers, stockists, traders)</li>
</ul>
<p>All three share the ISO 9001 foundation and add aerospace requirements appropriate to the type of work being done. AS9120 is the right standard for TurbineWorks because TurbineWorks does not design, manufacture, or repair — it stocks, inspects, and distributes.</p>

<h4>Why AS9120 exists alongside ASA-100</h4>
<p>ASA-100 and AS9120 cover overlapping ground but serve different markets:</p>
<table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse;">
  <tr style="background:#0d2240; color:#fff;">
    <th>Aspect</th><th>ASA-100</th><th>AS9120</th>
  </tr>
  <tr><td>Primary customer base</td><td>Aftermarket: airlines, MROs, parts brokers</td><td>OEM supply chain: Boeing, Airbus, GE, Rolls-Royce tier-1/2</td></tr>
  <tr><td>Approach</td><td>Prescriptive — specific requirements per section</td><td>QMS-process-based — requirements expressed as process outcomes</td></tr>
  <tr><td>Foundation</td><td>FAA AC 00-56B framework</td><td>ISO 9001 + AS9100 family</td></tr>
  <tr><td>Audit organization</td><td>ASA-recognized auditors</td><td>IAQG-recognized registrars (BSI, DNV, NQA, SAI Global, etc.)</td></tr>
  <tr><td>Recognition</td><td>FAA recognized under AC 00-56</td><td>IAQG OASIS database; industry-recognized</td></tr>
</table>

<h4>Which customers care about AS9120</h4>
<ul>
  <li>OEM direct programs (e.g., Boeing 787 supplier program participants)</li>
  <li>Tier-1 aerospace manufacturers (Spirit AeroSystems, Triumph, Collins)</li>
  <li>Defense primes for non-ITAR commercial parts (Raytheon Technologies, Northrop Grumman commercial work)</li>
  <li>Some major MROs that align supplier requirements to AS9120</li>
</ul>

<h4>Why pre-position for AS9120 now</h4>
<p>~70% of the controls required by AS9120 are already required by ASA-100 — receiving inspection, traceability, records retention, training, internal audits, corrective action. Implementing TurbineWorks University to support both training programs is roughly 30% more work than ASA-100 alone, but it positions TurbineWorks to add AS9120 certification without rebuilding the training program later.</p>

<p><em>[TurbineWorks Procedure Reference: insert the QA Manager\'s assessment of customer demand for AS9120 vs ASA-100, and the target date (if any) for AS9120 certification pursuit.]</em></p>
HTML
        ],
        [
            'name'  => 'Lesson 2 — Key AS9120 Clauses',
            'intro' => '<p>The aerospace-specific additions that distinguish AS9120 from generic ISO 9001.</p>',
            'content' => <<<'HTML'
<h3>Key AS9120 Clauses</h3>
<p>AS9120 follows the ISO 9001 clause structure (clauses 4-10) and adds aerospace-specific requirements within each clause. The aerospace additions are where AS9120 differs meaningfully from generic ISO 9001 — and where TurbineWorks needs to do work beyond what ASA-100 already requires.</p>

<h4>Configuration management (AS9120 §8.1.2 area)</h4>
<p>Configuration management is the discipline of identifying, controlling, and tracking the configuration of products throughout their lifecycle. For a distributor, this means:</p>
<ul>
  <li>Knowing exactly which revision of a part you have in stock (not just the part number, but the dash number, modification status, SB compliance state)</li>
  <li>Tracking part-number supersedures (when an OEM updates a part number to indicate a configuration change)</li>
  <li>Documenting build standard transitions clearly to customers</li>
</ul>
<p>ASA-100 touches on traceability but AS9120 demands more explicit configuration tracking.</p>

<h4>Risk-based thinking (ISO 9001 + AS9120 §6.1)</h4>
<p>AS9120 requires identifying risks and opportunities affecting the QMS and planning actions to address them. For a distributor:</p>
<ul>
  <li>Supplier risk (key supplier loss, supplier quality failures)</li>
  <li>Counterfeit parts risk (supply chain integrity)</li>
  <li>Customer concentration risk (heavy dependence on a few customers)</li>
  <li>Regulatory risk (AD impact on inventory)</li>
</ul>
<p>Documented risk assessment is required. The auditor will ask to see how risks were identified, evaluated, and addressed.</p>

<h4>Supply chain control (AS9120 §8.4)</h4>
<p>Heavier focus on supplier qualification than ASA-100:</p>
<ul>
  <li>Documented supplier-approval process</li>
  <li>Initial qualification: supplier QMS evaluation, sample inspection, performance commitments</li>
  <li>Ongoing performance monitoring (PPM defect rates, on-time delivery, quality findings)</li>
  <li>Periodic re-evaluation</li>
  <li>Removal process for under-performing suppliers</li>
</ul>

<h4>Special processes</h4>
<p>If TurbineWorks performs any "special processes" — processes whose results cannot be fully verified by subsequent inspection (e.g., kitting that combines multiple parts, repackaging that affects traceability) — those processes need validation and qualified personnel.</p>

<h4>Counterfeit parts prevention (AS9120 §8.1.4)</h4>
<p>Explicit AS9120 clause requiring documented counterfeit-parts prevention program. Aligns with SAE AS5553 / AS6174. This is also covered by ASA-100 SUP procedures — overlap is heavy here.</p>

<h4>Configuration and change control on QMS itself</h4>
<p>Changes to procedures, work instructions, and the QMS structure itself must be controlled — proposed, reviewed, approved, communicated, and effectiveness verified. The QMS evolves; AS9120 requires that evolution itself be controlled.</p>
HTML
        ],
        [
            'name'  => 'Lesson 3 — AS9120 vs ASA-100: Gap Analysis',
            'intro' => '<p>Where ASA-100 and AS9120 align, where they don\'t, and what TurbineWorks would need to add for AS9120 certification.</p>',
            'content' => <<<'HTML'
<h3>AS9120 vs ASA-100 Gap Analysis</h3>
<p>Practical view: if TurbineWorks holds ASA-100 today, what additional work would AS9120 certification require?</p>

<h4>Areas of high overlap (do once, satisfy both)</h4>
<ul>
  <li>Receiving inspection (ASA-100 §6 ≈ AS9120 §8.6)</li>
  <li>Records retention (ASA-100 §8 ≈ AS9120 §7.5)</li>
  <li>Personnel training (ASA-100 training requirements ≈ AS9120 §7.2)</li>
  <li>Storage and handling (ASA-100 §7 ≈ AS9120 §8.5.4)</li>
  <li>Counterfeit prevention (ASA-100 SUP procedures ≈ AS9120 §8.1.4)</li>
  <li>Calibration of inspection tools (both require)</li>
  <li>Document control (both require)</li>
</ul>
<p>Estimated ~70% of total work is shared.</p>

<h4>AS9120 additions beyond ASA-100</h4>
<ul>
  <li><strong>Documented risk management process</strong> with periodic review and action tracking</li>
  <li><strong>Quality objectives</strong> with measurable KPIs (on-time delivery, defect PPM, customer satisfaction) tracked over time</li>
  <li><strong>Customer satisfaction measurement</strong> — formal process, not just informal feedback</li>
  <li><strong>Management review</strong> with explicit agenda items: QMS performance, audit findings, customer feedback, risks, opportunities, resource needs</li>
  <li><strong>Internal audit program</strong> — both ASA-100 and AS9120 require, but AS9120 is more prescriptive about scope, frequency, auditor qualification, and follow-up</li>
  <li><strong>Configuration management</strong> as explicit discipline</li>
  <li><strong>Process-based QMS structure</strong> — AS9120 expects you to define your QMS as a set of processes with inputs, outputs, controls, and measurements (ISO 9001 process approach)</li>
</ul>

<h4>Things ASA-100 has that AS9120 doesn\'t emphasize</h4>
<ul>
  <li>Specific FAA references (FAA ACs, FAA forms like 8120-11)</li>
  <li>Specific FAA 8130-3 verification procedures (AS9120 references airworthiness documents more generically)</li>
  <li>Tight links to FAA AC 00-56 framework</li>
</ul>

<h4>The certification process for AS9120</h4>
<ol>
  <li>Select a registrar (IAQG-recognized certification body: BSI, DNV, NQA, SAI Global, LRQA, etc.)</li>
  <li>Pre-assessment (optional but recommended): registrar reviews QMS for readiness</li>
  <li>Stage 1 audit: document review</li>
  <li>Stage 2 audit: on-site implementation audit</li>
  <li>Corrective action on findings</li>
  <li>Certification granted, listed in <a href="https://www.iaqg.org/oasis/" target="_blank" rel="noopener">IAQG OASIS database</a></li>
  <li>Surveillance audits at intervals (typically annually) plus recertification at 3 years</li>
</ol>

<h4>The dual-accreditation reality</h4>
<p>Many aerospace distributors hold both ASA-100 and AS9120. ASA-100 satisfies the aftermarket / FAA-recognized side; AS9120 satisfies the OEM / IAQG side. Customers either side check the respective database (ASA accredited list, IAQG OASIS) before placing orders.</p>

<p><em>[TurbineWorks Procedure Reference: insert TurbineWorks QA Manager\'s gap analysis result and target AS9120 readiness date here.]</em></p>
HTML
        ],
        [
            'name'  => 'Lesson 4 — Module Summary',
            'intro' => '<p>AS9120 recap and references.</p>',
            'content' => '<h3>AS9120 Module Summary</h3><h4>What you should know</h4><ol><li>AS9120 is the aerospace QMS standard for distributors, published by SAE/IAQG, based on ISO 9001 with aerospace additions</li><li>AS9120 and ASA-100 overlap ~70% — same receiving inspection, traceability, training, counterfeit prevention</li><li>AS9120 adds: explicit risk management, KPIs, customer satisfaction measurement, configuration management, process-based QMS structure</li><li>AS9120 certification is via IAQG-recognized registrars (BSI, DNV, NQA, etc.), not ASA</li><li>Many distributors hold both ASA-100 (aftermarket) and AS9120 (OEM supply chain)</li></ol><h4>References</h4><ul><li>SAE AS9120 (current revision)</li><li>ISO 9001 (foundational QMS standard)</li><li>AS9100 (related — manufacturing) and AS9110 (related — maintenance)</li><li><a href="https://www.iaqg.org/oasis/" target="_blank" rel="noopener">IAQG OASIS</a> — certified supplier database</li></ul>',
        ],
    ];
}

// ============================================================================
// OPERATIONS COURSE: Customer Relations & Quality Communication (4 lessons)
// ============================================================================
function local_twu_customer_relations_lessons(): array {
    return [
        [
            'name'  => 'Lesson 1 — Who Your Customers Are and What They Need',
            'intro' => '<p>The five customer segments TurbineWorks sells to, and what each segment expects.</p>',
            'content' => <<<'HTML'
<h3>The Five Customer Segments</h3>
<p>TurbineWorks customers fall into roughly five segments. Each segment has different procurement processes, different risk tolerances, and different communication expectations.</p>

<h4>1. Commercial airlines</h4>
<p>Airlines buy parts to maintain their own fleet (in-house maintenance) or to support their MROs. Characteristics:</p>
<ul>
  <li>Procurement teams that work from approved-supplier lists — vendor qualification typically required</li>
  <li>Often require ASA-100 accreditation as a contractual prerequisite</li>
  <li>AOG (Aircraft On Ground) urgency is real — a grounded aircraft costs $30k-$200k/hour in lost revenue</li>
  <li>Tight focus on traceability documentation; require COCs and complete back-to-birth for LLPs</li>
  <li>Pricing pressure significant; pre-qualified suppliers compete on quality + speed + price</li>
</ul>

<h4>2. MROs (Maintenance, Repair, Overhaul)</h4>
<p>Independent MROs (Lufthansa Technik, ST Engineering, AAR, MTU Maintenance) buy parts to perform maintenance for airlines.</p>
<ul>
  <li>Similar requirements to airlines but with higher volume per relationship</li>
  <li>Often run shop-level inventory and need broad parts coverage from suppliers</li>
  <li>May have their own QMS requirements (AS9110 for the MRO itself, suppliers expected to align)</li>
</ul>

<h4>3. OEMs (Original Equipment Manufacturers)</h4>
<p>Boeing, Airbus, Lockheed Martin, GE Aerospace, Pratt &amp; Whitney, Rolls-Royce. The OEM direct supply chain.</p>
<ul>
  <li>Usually require AS9120 in addition to ASA-100</li>
  <li>Configuration management is heavy — they care about exact build-standard alignment</li>
  <li>Long-term contracts, formal supplier programs</li>
  <li>Lower volume, higher requirements, longer sales cycles</li>
</ul>

<h4>4. Government and defense</h4>
<p>U.S. military, allied military, U.S. government agencies (NASA, etc.).</p>
<ul>
  <li>DFARS compliance for DoD work</li>
  <li>QSLD (Qualified Suppliers List of Distributors) or similar approval may be required</li>
  <li>ITAR scrutiny for ITAR-controlled parts</li>
  <li>Contract Data Requirements Lists (CDRLs) drive specific documentation deliverables</li>
</ul>

<h4>5. Other distributors / brokers</h4>
<p>Some TurbineWorks customers are themselves distributors who buy from TurbineWorks to fill orders to their own customers.</p>
<ul>
  <li>Pricing-sensitive, volume-oriented</li>
  <li>Generally less rigorous documentation expectations</li>
  <li>Higher risk of downstream SUP issues — the broker chain can obscure original provenance</li>
</ul>

<h4>What every customer expects</h4>
<ul>
  <li>Accurate quoting (price, lead time, configuration)</li>
  <li>Honest condition disclosure</li>
  <li>Complete documentation accompanying every shipment</li>
  <li>Proactive notification when something changes (AD issued, recall, error discovered)</li>
  <li>Timely response to inquiries — same business day for routine, ~immediate for AOG</li>
</ul>
HTML
        ],
        [
            'name'  => 'Lesson 2 — Communicating Quality: Quotes, COCs, Disclosure',
            'intro' => '<p>How to communicate part condition and traceability accurately at every customer touchpoint.</p>',
            'content' => <<<'HTML'
<h3>Communicating Quality at Every Touchpoint</h3>
<p>The quality system is invisible to the customer — they see only what TurbineWorks communicates. Quotes, COCs, shipping documents, and follow-up emails are where the quality system becomes visible.</p>

<h4>Quote-stage communication</h4>
<p>A quote sent to a customer should disclose:</p>
<ul>
  <li>Part number and dash number (exactly as in TurbineWorks inventory)</li>
  <li>Condition (NEW / NE / OH / SV / AR — using industry-standard terms)
    <ul>
      <li>NEW: never installed</li>
      <li>NE: new equivalent — never installed, may have been in OEM inventory long-term</li>
      <li>OH: overhauled</li>
      <li>SV: serviceable (inspected, no overhaul performed)</li>
      <li>AR: as-removed (no shop work since removal)</li>
    </ul>
  </li>
  <li>Trace (where the part came from — operator, OEM direct, etc.)</li>
  <li>For LLP: current TSN/CSN, remaining life</li>
  <li>For modules / engines: build standard, SB compliance status, recent BSI</li>
  <li>Documentation included (8130-3, EASA Form 1, COC, etc.)</li>
  <li>Lead time and shipping mode</li>
</ul>
<p>A quote that leaves any of these vague creates downstream problems. "AR with 8130-3" doesn\'t answer questions about LLP life remaining.</p>

<h4>TurbineWorks COC (Certificate of Conformance)</h4>
<p>Issued with every outbound shipment. Attests:</p>
<ul>
  <li>Part was received and inspected per ASA-100 procedures</li>
  <li>Documentation has been verified</li>
  <li>Storage met requirements (ESD, hazmat, environmental)</li>
  <li>Part is in the disclosed condition</li>
</ul>
<p>The COC is a TurbineWorks legal attestation — issued under the signature of an authorized person. Misrepresentation on the COC has legal consequences.</p>

<h4>What you cannot say in writing to a customer</h4>
<ul>
  <li>"This part is FAA-approved" — accurate is "This part has an 8130-3 from [organization]"; only the issuing org\'s tag is the approval evidence, not TurbineWorks\' statement</li>
  <li>"Like new condition" — use the actual ATA condition code (NEW, NE, OH, SV, AR)</li>
  <li>"As good as OEM" — only OEM-source parts are OEM parts; PMA or used-OEM has different status</li>
  <li>Specifications that contradict the OEM\'s published data without OEM authorization</li>
</ul>

<h4>Communication failures that hurt</h4>
<ul>
  <li>Late notification of shipping delay — customer plans around the original commitment, has to scramble when reality differs</li>
  <li>Documentation arriving separately from the part (paper trail and physical part should travel together)</li>
  <li>Quoting a part that turns out not to be in inventory ("phantom inventory") — accurate inventory is foundational</li>
  <li>Forwarding customer questions to an unstaffed inbox</li>
</ul>

<h4>Proactive disclosure protects everyone</h4>
<p>When something goes wrong — wrong part shipped, AD affects a part already shipped, supplier discovered to have provided suspect documentation — the right answer is to call the customer first. Customer-discovered problems erode trust permanently. Vendor-disclosed problems build trust by demonstrating the quality system actually works.</p>
HTML
        ],
        [
            'name'  => 'Lesson 3 — Complaints, Corrective Action, and Customer Audits',
            'intro' => '<p>What to do when a customer reports a problem, and how to handle customer audits.</p>',
            'content' => <<<'HTML'
<h3>When Things Go Wrong</h3>

<h4>The complaint workflow</h4>
<p>A customer reports a problem with a part TurbineWorks shipped. The ASA-100 §10 complaint-handling process kicks in:</p>
<ol>
  <li><strong>Acknowledge receipt</strong> within one business day. The customer needs to know the issue is being investigated, even before TurbineWorks knows what happened.</li>
  <li><strong>Open a Non-Conformance Report (NCR)</strong> in the corrective action system. The complaint enters the formal quality process, not just an email thread.</li>
  <li><strong>Investigate</strong> — pull TurbineWorks receiving inspection record, shipping record, supplier records. Was the part as TurbineWorks shipped? If not, where did the divergence happen?</li>
  <li><strong>Containment</strong> — if the issue may affect other inventory or other customers, identify and contain. Hold any inventory of the same supplier lot pending investigation.</li>
  <li><strong>Root cause</strong> — why did this happen? Receiving inspection process gap? Supplier issue? Storage handling? Documentation error?</li>
  <li><strong>Corrective action</strong> — what specifically changes to prevent recurrence?</li>
  <li><strong>Verification</strong> — confirm the corrective action is effective (not just implemented, but actually working)</li>
  <li><strong>Customer follow-up</strong> — close the loop with the customer. Reimbursement, replacement, credit, formal apology as appropriate.</li>
  <li><strong>Record retention</strong> — the complete complaint and corrective action file is retained per records schedule</li>
</ol>

<h4>What you cannot do</h4>
<ul>
  <li>Settle the complaint informally outside the corrective action system (auditor cannot verify; root cause never identified)</li>
  <li>Push back on the customer that the complaint is invalid before investigating</li>
  <li>Refuse to disclose investigation findings to the customer</li>
  <li>Suppress the complaint to protect TurbineWorks reputation in the moment (this always backfires later)</li>
</ul>

<h4>Customer audits</h4>
<p>Some customers (especially large OEMs and major airlines) audit their distributors directly, separate from the ASA-100 accreditation audit. A customer audit is similar to but typically narrower than an ASA audit.</p>

<h5>What customer auditors typically check</h5>
<ul>
  <li>Specific shipments — pull records for parts shipped to the customer, trace through receiving / storage / shipping</li>
  <li>How non-conformances were handled if any occurred</li>
  <li>Storage area where the customer\'s part types are stored</li>
  <li>Customer-specific contract requirements compliance</li>
</ul>

<h5>How to host a customer audit</h5>
<ul>
  <li>Assign a single TurbineWorks point of contact for the audit</li>
  <li>Pre-prepare records the auditor will likely want (shipping history with the customer, NCR history, training records)</li>
  <li>Have the QA Manager present</li>
  <li>Be transparent — auditors will probe for hidden issues if they sense evasiveness</li>
  <li>Document audit findings the same way as ASA findings; corrective actions feed the same system</li>
</ul>

<h4>When a complaint escalates</h4>
<p>If a customer believes TurbineWorks delivered a SUP, the complaint may trigger:</p>
<ul>
  <li>FAA notification by the customer (which becomes an FAA inquiry to TurbineWorks)</li>
  <li>ASA inquiry (the customer can complain to ASA about an accredited distributor)</li>
  <li>Loss of customer\'s approved-vendor status</li>
  <li>In extreme cases, litigation</li>
</ul>
<p>Proactive cooperation, complete documentation, and demonstrating the quality system\'s root-cause / corrective action work are how distributors recover from these situations. Denial and obstruction guarantee escalation.</p>
HTML
        ],
        [
            'name'  => 'Lesson 4 — Module Summary',
            'intro' => '<p>Customer relations recap.</p>',
            'content' => '<h3>Customer Relations Summary</h3><h4>What you should know</h4><ol><li>Five customer segments (airlines, MROs, OEMs, gov/defense, distributors) each with different expectations</li><li>Communicate quality at every touchpoint — quote, COC, shipping docs, follow-up</li><li>Use industry-standard ATA condition codes (NEW, NE, OH, SV, AR)</li><li>Customer complaints go through the formal corrective-action system, not informal email</li><li>Customer audits are common from large OEMs and major airlines — prepare and be transparent</li><li>Proactive disclosure protects trust; cover-ups always backfire</li></ol>',
        ],
    ];
}

// ============================================================================
// OPERATIONS COURSE: Export Control / ITAR / EAR (4 lessons)
// ============================================================================
function local_twu_export_control_lessons(): array {
    return [
        [
            'name'  => 'Lesson 1 — Export Control Overview: ITAR vs EAR',
            'intro' => '<p>The two U.S. export control regimes — when each applies and why the distinction matters.</p>',
            'content' => <<<'HTML'
<h3>U.S. Export Control: The Two Regimes</h3>
<p>U.S. export control is divided between two regulatory regimes administered by different agencies. Knowing which applies to a given part is the foundational export-control decision.</p>

<h4>ITAR — International Traffic in Arms Regulations</h4>
<ul>
  <li><strong>22 CFR Parts 120-130</strong></li>
  <li>Administered by the U.S. Department of State, Directorate of Defense Trade Controls (DDTC)</li>
  <li>Controls "defense articles" listed on the <strong>U.S. Munitions List (USML)</strong></li>
  <li>Controls "defense services" — assistance, training, or technical data related to defense articles</li>
  <li>Controls "brokering" — facilitating transactions involving defense articles</li>
</ul>

<h4>EAR — Export Administration Regulations</h4>
<ul>
  <li><strong>15 CFR Parts 730-774</strong></li>
  <li>Administered by the U.S. Department of Commerce, Bureau of Industry and Security (BIS)</li>
  <li>Controls "dual-use" items — those with both commercial and potential military application</li>
  <li>Items listed on the <strong>Commerce Control List (CCL)</strong></li>
  <li>Each listed item has an <strong>ECCN (Export Control Classification Number)</strong></li>
</ul>

<h4>How a part gets classified</h4>
<p>Three possible classifications:</p>
<ol>
  <li><strong>ITAR-controlled (USML)</strong> — most restrictive. Export licensing through State Department. Generally cannot be exported to ITAR-prohibited destinations (China, Russia, Iran, North Korea, others).</li>
  <li><strong>EAR-controlled (CCL with specific ECCN)</strong> — restrictions depend on the ECCN and the destination. Some destinations require licensing; others may be license-exception eligible.</li>
  <li><strong>EAR99</strong> — EAR jurisdiction but not on the CCL. Lowest restriction level. Most commercial aviation parts fall here.</li>
</ol>

<h4>Why aviation parts are complicated</h4>
<p>Many commercial aviation parts have military equivalents or military origins:</p>
<ul>
  <li>CFM56 powers both A320ceo (commercial) and KC-135R (military tanker) — some parts cross over</li>
  <li>GE F110 (F-16 engine) shares technology with GE90 (commercial)</li>
  <li>Pratt &amp; Whitney F100 (F-15, F-16) shares heritage with PW2000 / PW4000 (commercial)</li>
  <li>Helicopter engines used by both civil operators and military</li>
</ul>
<p>A part originally designed for military application is presumptively ITAR-controlled even if it has a commercial part number, unless and until it has been formally re-classified.</p>

<h4>Penalties for getting it wrong</h4>
<ul>
  <li>Civil penalties: up to $1M per violation</li>
  <li>Criminal penalties: up to $1M and 20 years imprisonment per willful violation</li>
  <li>Debarment: loss of export privileges, often for years</li>
  <li>Personal liability: officers and employees can be individually prosecuted, not just the corporation</li>
</ul>
<p>The U.S. government takes export control extremely seriously, especially when ITAR is involved. "I didn\'t know" is not a defense — companies are expected to know the classification of what they ship.</p>
HTML
        ],
        [
            'name'  => 'Lesson 2 — ITAR Specifics',
            'intro' => '<p>How to recognize ITAR-controlled parts and what to do when one shows up.</p>',
            'content' => <<<'HTML'
<h3>ITAR Specifics</h3>

<h4>What\'s on the U.S. Munitions List</h4>
<p>The USML has 21 categories. The aviation-relevant categories include:</p>
<ul>
  <li><strong>Category VIII</strong> — Aircraft and Related Articles (military aircraft, military helicopters, related parts and components)</li>
  <li><strong>Category XIX</strong> — Gas Turbine Engines and Associated Equipment (military engines; some commercial engines may be partially ITAR if militarily-derived)</li>
  <li><strong>Category IV</strong> — Launch Vehicles, Guided Missiles, Ballistic Missiles (rocket engines, related)</li>
  <li><strong>Category XV</strong> — Spacecraft Systems and Associated Equipment</li>
</ul>

<h4>The technical data trap</h4>
<p>ITAR doesn\'t just control hardware. It controls:</p>
<ul>
  <li>Drawings, specifications, technical manuals related to defense articles</li>
  <li>Software for design, manufacture, or operation of defense articles</li>
  <li>Training related to defense articles</li>
  <li>Defense services performed for foreign entities</li>
</ul>
<p>Emailing a spec sheet for an ITAR-controlled part to a foreign person — even one in the U.S. — is itself an export under ITAR. "Deemed exports" to foreign nationals on U.S. soil are real and prosecutable.</p>

<h4>Brokering</h4>
<p>ITAR §129 covers "brokering" — facilitating transactions involving defense articles. A distributor that merely sources and resells ITAR-controlled parts may be a "broker" under ITAR even without taking physical possession. Brokering activities require ITAR registration with DDTC.</p>

<h4>If TurbineWorks identifies an ITAR-controlled part</h4>
<ol>
  <li><strong>Stop the transaction.</strong> Do not ship without ITAR licensing in place.</li>
  <li><strong>Notify the QA Manager and the Accountable Manager.</strong> ITAR decisions are made above the operational level.</li>
  <li><strong>Determine TurbineWorks\' ITAR registration status.</strong> If not registered, the appropriate path may be to decline the transaction entirely.</li>
  <li><strong>If proceeding, obtain a license.</strong> Apply through DDTC for export authorization for the specific transaction.</li>
  <li><strong>End-use and end-user verification.</strong> The license application requires identification of who will receive the part and what they will do with it.</li>
  <li><strong>Documentation.</strong> ITAR transactions have specific record requirements (typically retained 5 years).</li>
</ol>

<h4>Common scenarios at TurbineWorks</h4>
<ul>
  <li><strong>KC-135R operator inquiry:</strong> the KC-135R is a military aircraft using CFM56 engines. KC-135R-specific parts may be ITAR. Commercial CFM56 parts that happen to be also installable on KC-135R may or may not be ITAR — fact-specific determination required.</li>
  <li><strong>Foreign military buyer:</strong> foreign government attempting to buy parts, possibly for a US-supplied aircraft. Even commercial-origin parts may require FMS (Foreign Military Sales) channel, not commercial export.</li>
  <li><strong>U.S. customer requesting export-ready stock:</strong> customer in the U.S. but parts will be re-exported by them. Re-export controls apply even though TurbineWorks ships domestically.</li>
</ul>

<p><em>[TurbineWorks Procedure Reference: insert TurbineWorks ITAR registration status, designated Empowered Official, and the procedure for handling potential ITAR-controlled inquiries here.]</em></p>
HTML
        ],
        [
            'name'  => 'Lesson 3 — EAR, ECCN Classification, and OFAC Screening',
            'intro' => '<p>The EAR regime, how to classify a part by ECCN, and the OFAC sanctions screening that applies to every transaction.</p>',
            'content' => <<<'HTML'
<h3>EAR, ECCN, and OFAC</h3>

<h4>ECCN classification</h4>
<p>Every CCL-listed item has an ECCN. The format is alphanumeric (e.g., 9A991, 9E991). The structure encodes:</p>
<ul>
  <li><strong>First digit (0-9)</strong>: Category (9 = Aerospace and Propulsion)</li>
  <li><strong>Letter (A-E)</strong>: Type (A = Equipment, B = Test equipment, C = Materials, D = Software, E = Technology)</li>
  <li><strong>Next digits</strong>: Specific entry within category</li>
</ul>

<h4>Determining the ECCN</h4>
<p>For aviation parts, common ECCNs include:</p>
<ul>
  <li><strong>9A991</strong> — "Aircraft and components" not specified elsewhere. Many commercial aviation parts fall here.</li>
  <li><strong>9A610</strong> — Military aircraft and related items (mostly redirected to ITAR)</li>
  <li><strong>9E991</strong> — Technology for aircraft</li>
  <li><strong>EAR99</strong> — Not specifically listed; lowest restriction</li>
</ul>
<p>The classification process:</p>
<ol>
  <li>Determine if the item is ITAR (USML) — if yes, ITAR applies, not EAR</li>
  <li>If not ITAR, check the CCL for specific listing</li>
  <li>If on CCL, the ECCN determines restrictions per destination country</li>
  <li>If not on CCL but otherwise subject to EAR, classify as EAR99</li>
</ol>

<h4>License exceptions</h4>
<p>EAR has license exceptions that allow export without a license under specific conditions:</p>
<ul>
  <li><strong>License Exception STA</strong> (Strategic Trade Authorization): exports to allied countries</li>
  <li><strong>License Exception RPL</strong> (Servicing and Replacement of Parts and Equipment): replacement parts for previously exported items</li>
  <li><strong>License Exception ENC</strong>: certain encryption</li>
  <li>Several others, each with specific eligibility rules</li>
</ul>
<p>Using a license exception requires documenting the basis at the time of export.</p>

<h4>OFAC screening (separate from EAR/ITAR)</h4>
<p>OFAC administers U.S. sanctions programs. OFAC restrictions apply to TRANSACTIONS, not just exports — even shipping domestically to a sanctioned party is prohibited.</p>
<p>Maintained lists:</p>
<ul>
  <li><strong>SDN list</strong> (Specially Designated Nationals) — comprehensive sanctioned-party list</li>
  <li>Country-specific sanctions (Iran, Russia, North Korea, others)</li>
  <li>Sectoral sanctions (specific industries within otherwise non-sanctioned countries)</li>
</ul>
<p>Every customer, every consignee, every intermediary must be screened against current OFAC lists before TurbineWorks transacts. Screening is automated in modern ERP systems but the underlying obligation is TurbineWorks\'.</p>

<h4>Recordkeeping</h4>
<p>BIS requires retention of export records for 5 years from date of export. TurbineWorks records typically retain longer (ASA-100 retention schedule), which satisfies the 5-year minimum.</p>

<h4>What every TurbineWorks employee should know</h4>
<ul>
  <li>Even a casual email reply to a foreign inquiry about a part can be an "export of technical data"</li>
  <li>Quotes are not exempt — quoting a foreign party for an ITAR-controlled part may itself be a violation</li>
  <li>"Hold the shipment, check with QA Manager / Empowered Official" is always the right answer when export status is unclear</li>
  <li>Internal documentation of the export decision is mandatory — verbal classifications are not sufficient</li>
</ul>
HTML
        ],
        [
            'name'  => 'Lesson 4 — Module Summary',
            'intro' => '<p>Export control recap.</p>',
            'content' => '<h3>Export Control Summary</h3><h4>What you should know</h4><ol><li>Two regimes: ITAR (State, defense articles, USML) and EAR (Commerce, dual-use, CCL)</li><li>Most commercial aviation parts are EAR (often EAR99) but military-derivative parts may be ITAR</li><li>ITAR violations carry criminal penalties — personal and corporate</li><li>ECCN drives EAR licensing — license exceptions may apply for some destinations</li><li>OFAC sanctions screening is mandatory for every transaction regardless of export status</li><li>Records retained 5+ years (BIS minimum; ASA-100 retention exceeds)</li></ol><h4>When in doubt</h4><p>Hold the shipment. Notify the QA Manager and the designated Empowered Official. "I checked with the EO and we determined X" is defensible. "I shipped it without checking because I assumed it was fine" is not.</p>',
        ],
    ];
}

// ============================================================================
// ENGINE-MODEL COURSE: CFM LEAP (3 lessons)
// ============================================================================
function local_twu_engine_leap_lessons(): array {
    return [
        [
            'name'  => 'Lesson 1 — LEAP Family Overview',
            'intro' => '<p>The CFM successor to the CFM56 — direct-drive turbofan competing with the PW1000G GTF.</p>',
            'content' => <<<'HTML'
<h3>CFM LEAP Family</h3>
<p>LEAP is CFM International\'s successor to the CFM56, introduced into service starting 2016. While the PW1000G GTF approached the next-generation efficiency target with a geared architecture, CFM took a different path: keep the direct-drive architecture but push it harder with composites, ceramics, and additive manufacturing.</p>

<h4>Variants and applications</h4>
<ul>
  <li><strong>LEAP-1A</strong> — Airbus A320neo family. Thrust 24,500-32,900 lbf. Competes head-to-head with PW1100G.</li>
  <li><strong>LEAP-1B</strong> — Boeing 737 MAX. Thrust 23,000-28,000 lbf. Sole-source on 737 MAX (PW does not offer for MAX).</li>
  <li><strong>LEAP-1C</strong> — COMAC C919. Thrust ~30,000 lbf. Chinese narrowbody.</li>
</ul>

<h4>Architectural choices vs. CFM56</h4>
<ul>
  <li><strong>Higher bypass ratio</strong> (~11:1 vs ~5.5:1 for CFM56-7B). Larger fan, smaller core.</li>
  <li><strong>Composite fan blades</strong> with 3D woven preform (carried forward from GE90/GEnx)</li>
  <li><strong>Composite fan case</strong></li>
  <li><strong>Ceramic Matrix Composite (CMC) HPT shrouds</strong> — first commercial use of CMC in a hot-section component. Allows higher operating temperatures.</li>
  <li><strong>3D-printed fuel nozzle</strong> — DMLS (direct metal laser sintering) consolidates 18 conventional parts into 1. First commercial 3D-printed engine part in production volume.</li>
  <li><strong>TAPS combustor</strong> (similar to GEnx) — improved emissions</li>
</ul>

<h4>What this means for the aftermarket</h4>
<ul>
  <li><strong>Newer fleet</strong> — limited aftermarket parts pool yet, but growing rapidly</li>
  <li><strong>Composite + ceramic + 3D-printed components</strong> are new — repair authority limited to specifically-authorized facilities</li>
  <li><strong>CFM56 successor inventory</strong> — as CFM56 fleets retire, capital may move to LEAP. Position now.</li>
</ul>

<h4>In-service issues to know</h4>
<ul>
  <li><strong>LEAP-1A HPC blade durability</strong> — early in-service issues addressed by SBs and subsequent build improvements</li>
  <li><strong>LEAP-1B HPT issues</strong> on early production — addressed by HPT module improvements</li>
  <li><strong>Combustor durability</strong> on both variants in early production</li>
  <li><strong>Time-on-wing shorter than promised</strong> early in service — improving as PIPs are incorporated</li>
</ul>

<p><em>[TurbineWorks Procedure Reference: insert TurbineWorks LEAP inventory status, supplier qualification, and any LEAP-specific receiving notes here.]</em></p>
HTML
        ],
        [
            'name'  => 'Lesson 2 — LEAP Modules and LLPs',
            'intro' => '<p>LEAP module breakdown and the LLP set that drives inventory value.</p>',
            'content' => <<<'HTML'
<h3>LEAP Modules and LLPs</h3>

<h4>Module breakdown</h4>
<p>LEAP follows the standard CFM modular architecture:</p>
<ul>
  <li><strong>Fan / Booster module</strong> — composite fan blades, composite fan case, 3-stage booster (LPC)</li>
  <li><strong>HPC module</strong> — 10-stage high-pressure compressor (one more stage than CFM56)</li>
  <li><strong>Combustor module</strong> — TAPS combustor, 3D-printed fuel nozzles</li>
  <li><strong>HPT module</strong> — 2-stage HPT (CFM56 was single-stage); CMC shrouds</li>
  <li><strong>LPT module</strong> — 7-stage LPT (CFM56 was 4-stage; LEAP needs more stages to drive the larger fan slowly)</li>
  <li><strong>AGB / accessory module</strong></li>
</ul>

<h4>Major LLPs</h4>
<ul>
  <li>Fan disk</li>
  <li>Booster disks (stages 1-3)</li>
  <li>HPC disks (multiple stages)</li>
  <li>HPT disks (stages 1-2)</li>
  <li>LPT disks (multiple stages)</li>
  <li>HPT and LPT shafts</li>
</ul>
<p>Life limits per current CFM ESM. As with all newly-introduced engines, life limits may be revised as in-service data accumulates.</p>

<h4>Module-level trading considerations</h4>
<ul>
  <li>LEAP module exchange market is nascent — fewer used modules available than for CFM56</li>
  <li>OEM (CFM) operates a structured aftermarket — independent module trading less common than for CFM56</li>
  <li>Build standard tracking is critical — LEAP has had multiple PIP incorporations addressing in-service issues; pre-PIP modules have different value than post-PIP</li>
</ul>

<h4>Composite blade handling</h4>
<p>LEAP composite fan blades have different handling considerations than metal blades:</p>
<ul>
  <li>Cannot be blended on-wing the way metal blades sometimes can — composite repair authority is specifically restricted</li>
  <li>Impact damage assessment is different — visible damage may or may not correlate with structural damage</li>
  <li>Storage requirements include UV protection and humidity control</li>
</ul>
HTML
        ],
        [
            'name'  => 'Lesson 3 — Module Summary',
            'intro' => '<p>LEAP recap.</p>',
            'content' => '<h3>LEAP Summary</h3><ul><li>CFM successor to CFM56 — direct-drive architecture (vs PW GTF approach)</li><li>LEAP-1A (A320neo), LEAP-1B (737 MAX sole-source), LEAP-1C (C919)</li><li>Composite fan blades + case, CMC HPT shrouds, 3D-printed fuel nozzles</li><li>Higher bypass ratio (~11:1 vs CFM56 ~5.5:1)</li><li>Newer fleet — aftermarket emerging; CFM operates structured aftermarket so independent trade narrower than CFM56</li><li>Build standard / PIP tracking critical — multiple in-service issues addressed iteratively</li></ul>',
        ],
    ];
}

// ============================================================================
// ENGINE-MODEL COURSE: V2500 (3 lessons)
// ============================================================================
function local_twu_engine_v2500_lessons(): array {
    return [
        [
            'name'  => 'Lesson 1 — V2500 Family Overview',
            'intro' => '<p>The IAE V2500 — the alternative engine on A320ceo, with a strong installed base and parts aftermarket.</p>',
            'content' => <<<'HTML'
<h3>V2500 Family</h3>
<p>The V2500 is a high-bypass turbofan produced by International Aero Engines (IAE), a multi-national joint venture led by Pratt &amp; Whitney and Rolls-Royce. It powers the A320ceo family (A319/A320/A321) as the alternative to CFM\'s CFM56-5B. Approximately 7,800 engines produced.</p>

<h4>Variants</h4>
<ul>
  <li><strong>V2500-A1</strong> — original variant, A320</li>
  <li><strong>V2500-A5</strong> — main current variant, A320ceo family. Multiple thrust ratings (V2522-A5, V2524-A5, V2527-A5, V2530-A5, V2533-A5).</li>
  <li><strong>V2500-E5</strong> — for Embraer KC-390 military application</li>
</ul>

<h4>IAE consortium history</h4>
<p>The IAE consortium has shifted over the years. Original partners included Pratt &amp; Whitney, Rolls-Royce, MTU, and Japanese Aero Engines Corporation (JAEC). As of recent years, Pratt &amp; Whitney holds majority interest. This matters for aftermarket sourcing — different consortium members produced different components.</p>

<h4>Architecture</h4>
<ul>
  <li>2-shaft direct-drive turbofan (contrast: PW1100G GTF, Trent 3-shaft)</li>
  <li>Single-stage fan</li>
  <li>4-stage LPC (booster)</li>
  <li>10-stage HPC</li>
  <li>Single annular combustor</li>
  <li>2-stage HPT</li>
  <li>5-stage LPT</li>
</ul>

<h4>Why V2500 matters for TurbineWorks</h4>
<ul>
  <li>Large installed base — ~7,800 engines, much of which is mid-to-late life</li>
  <li>Strong used-parts market as A320ceo fleets transition to A320neo</li>
  <li>Reliable engine with mature SB / AD history — most known issues have established solutions</li>
  <li>Direct competitor to CFM56-5B in the aftermarket; some operators have both</li>
</ul>

<h4>Common ADs and SBs</h4>
<ul>
  <li>HPT durability ADs over the years addressing turbine blade and disk life limits</li>
  <li>HPC airfoil cracking SBs/ADs</li>
  <li>Bearing-related ADs</li>
</ul>
<p>Current AD list searchable in FAA DRS for V2500 serial number ranges.</p>

<h4>Aftermarket dynamics</h4>
<p>The A320ceo fleet is being retired in significant numbers as operators upgrade to A320neo. This creates:</p>
<ul>
  <li>Increasing supply of used V2500 engines and parts</li>
  <li>Lower per-cycle pricing as supply increases relative to demand</li>
  <li>Opportunity for distributors holding inventory at the right price point</li>
</ul>

<p><em>[TurbineWorks Procedure Reference: insert TurbineWorks V2500 inventory and supplier status here.]</em></p>
HTML
        ],
        [
            'name'  => 'Lesson 2 — V2500 LLPs and Module Trading',
            'intro' => '<p>V2500 LLP set, module breakdown, and used-engine market considerations.</p>',
            'content' => <<<'HTML'
<h3>V2500 LLPs and Module Trading</h3>

<h4>Major LLPs</h4>
<ul>
  <li>Fan disk</li>
  <li>LPC (booster) disks (stages 1-4)</li>
  <li>HPC disks (stages 1-10)</li>
  <li>HPT disks (stages 1-2)</li>
  <li>LPT disks (stages 1-5)</li>
  <li>HPT and LPT shafts</li>
</ul>
<p>Life limits per current IAE ESM. The number of LLPs per V2500 engine is relatively high compared to two-shaft engines with fewer compressor stages — drives both inspection burden and parts opportunity.</p>

<h4>Module breakdown</h4>
<ul>
  <li>Fan / LPC module</li>
  <li>HPC module</li>
  <li>Combustion section</li>
  <li>HPT module</li>
  <li>LPT module</li>
  <li>Turbine exhaust case</li>
  <li>Accessory drive</li>
</ul>
<p>V2500 is a maintainable engine with established module-level trade — used HPT modules, LPT modules, and fan/LPC modules are all market commodities.</p>

<h4>Build standards</h4>
<p>The V2500-A5 has undergone multiple PIP (Performance Improvement Package) incorporations. PIP level matters for valuation:</p>
<ul>
  <li>Original A5 build vs Select One vs Select Two configurations have different fuel-burn performance and durability</li>
  <li>Customer interest in specific build standards (operators value newer PIP levels)</li>
  <li>Build standard documented on the 8130-3 and in the engine logbook</li>
</ul>

<h4>End-of-fleet considerations</h4>
<p>As A320ceo operators retire their V2500-powered aircraft:</p>
<ul>
  <li>Large engines and modules become available at falling prices</li>
  <li>Many engines have substantial green time remaining when retired (the airframe is retired before the engine reaches its next shop visit)</li>
  <li>LLPs from retired engines feed the parts market as components</li>
  <li>Some engines repurposed for non-aviation uses (industrial power generation) — those parts then aren\'t available for aviation</li>
</ul>

<h4>Common cross-shopping with CFM56-5B</h4>
<p>A320ceo operators often have mixed fleets — some V2500-powered, some CFM56-5B-powered. The engines are NOT interchangeable — the FADEC, accessory locations, and mounting are different — but operators of both have requirements for both. A distributor with inventory in both is more valuable to mixed-fleet operators.</p>
HTML
        ],
        [
            'name'  => 'Lesson 3 — Module Summary',
            'intro' => '<p>V2500 recap.</p>',
            'content' => '<h3>V2500 Summary</h3><ul><li>IAE V2500 — joint venture engine for A320ceo, ~7,800 engines installed</li><li>2-shaft direct-drive architecture, competitor to CFM56-5B on same airframe</li><li>Build standards (PIP levels) significantly affect performance and valuation</li><li>End-of-fleet dynamics create strong used-parts supply as A320ceo retirement accelerates</li><li>Mixed-fleet operators value distributors with both V2500 and CFM56-5B inventory</li></ul>',
        ],
    ];
}
