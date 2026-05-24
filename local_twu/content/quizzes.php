<?php
// TurbineWorks University — quiz question banks for each Initial Training module.
//
// Each module gets a Quiz activity (mod_quiz) at the end with multiple choice
// questions drawn from these banks. Pass mark 80%, up to 3 attempts, deferred
// feedback (whole-quiz at end).
//
// Question structure:
//   [
//     'name'      => short label,
//     'question'  => HTML question stem,
//     'options'   => indexed list of choice texts (HTML allowed),
//     'correct'   => zero-based index of the correct option,
//     'explain'   => HTML explanation shown after the attempt,
//   ]

defined('MOODLE_INTERNAL') || die();

/**
 * Return per-course-idnumber quiz definitions.
 */
function local_twu_get_quizzes(): array {
    return [
        'TWF4-1' => [
            'name'  => 'Module 1 Knowledge Check — Unapproved Parts &amp; Counterfeit Materials',
            'intro' => '<p>10 questions. 80% to pass. Up to 3 attempts. Feedback delivered after each attempt.</p>',
            'questions' => [
                [
                    'name' => 'M1Q1', 'correct' => 1,
                    'question' => '<p>Which of the following best defines a <strong>Suspected Unapproved Part (SUP)</strong>?</p>',
                    'options' => [
                        'A part definitively confirmed to be counterfeit by laboratory analysis',
                        'A part for which there is reasonable basis to believe it may be unapproved, pending investigation',
                        'Any part purchased from a broker',
                        'Any part more than 10 years old',
                    ],
                    'explain' => 'SUP is the in-investigation status — reasonable basis for suspicion, not yet confirmed. Confirmed unapproved is a separate category. (FAA AC 21-29D)',
                ],
                [
                    'name' => 'M1Q2', 'correct' => 2,
                    'question' => '<p>What is the FAA form used to report a Suspected Unapproved Part?</p>',
                    'options' => ['FAA Form 8130-3', 'FAA Form 337', 'FAA Form 8120-11', 'FAA Form 8050-2'],
                    'explain' => 'FAA Form 8120-11 (Suspected Unapproved Parts Notification) is the reporting form. 8130-3 is the airworthiness approval tag — a different document.',
                ],
                [
                    'name' => 'M1Q3', 'correct' => 0,
                    'question' => '<p>You discover a part where the Block 14 signature on the 8130-3 appears to be a photocopy. What is the correct first action?</p>',
                    'options' => [
                        'Quarantine the part and notify the QA Manager; do not return to supplier yet',
                        'Return the part to the supplier with a complaint letter',
                        'Accept the part since the data appears to match the part itself',
                        'Destroy the documentation and request a fresh tag',
                    ],
                    'explain' => 'Quarantine preserves evidence. Returning to supplier just shifts the SUP downstream. Destroying documentation eliminates evidence the FAA may need.',
                ],
                [
                    'name' => 'M1Q4', 'correct' => 1,
                    'question' => '<p>Which of the following is the MOST common pathway by which unapproved parts enter the aviation supply chain?</p>',
                    'options' => [
                        'Counterfeit manufacture in unauthorized factories',
                        'Diversion of scrapped parts that were not properly mutilated',
                        'Theft from operating aircraft',
                        'Customs fraud during international shipment',
                    ],
                    'explain' => 'Scrap diversion is the largest single pathway industry-wide. This is why FAA AC 21-38 mutilation requirements matter — they close the loop.',
                ],
                [
                    'name' => 'M1Q5', 'correct' => 3,
                    'question' => '<p>SAE AS5553 and AS6174 are industry standards covering what topic?</p>',
                    'options' => [
                        'ESD control programs',
                        'Recordkeeping and traceability',
                        'Borescope inspection criteria',
                        'Counterfeit parts prevention (electronics and broader materiel)',
                    ],
                    'explain' => 'AS5553 covers counterfeit electronic parts; AS6174 covers counterfeit materiel broadly. ASA auditors increasingly cite these in supplier qualification reviews.',
                ],
                [
                    'name' => 'M1Q6', 'correct' => 2,
                    'question' => '<p>An 8130-3 lists a certificate number in Block 15 that does not appear in the FAA public Certificate Search. What does this indicate?</p>',
                    'options' => [
                        'Normal — the FAA database is often out of date',
                        'The certificate is from a foreign authority',
                        'The 8130-3 may be fabricated; the part should be quarantined as SUP',
                        'The part is over 5 years old and the certificate has expired',
                    ],
                    'explain' => 'Every FAA-issued certificate is in the public database. A certificate number not found is a strong indicator of fabrication. Quarantine immediately.',
                ],
                [
                    'name' => 'M1Q7', 'correct' => 0,
                    'question' => '<p>A counterfeit part differs from an unapproved part primarily in that:</p>',
                    'options' => [
                        'Counterfeit involves deliberate misrepresentation; unapproved may be unintentional',
                        'Counterfeit is always electronic; unapproved is mechanical',
                        'Counterfeit is reported to NTSB; unapproved is reported to FAA',
                        'There is no difference; the terms are synonymous',
                    ],
                    'explain' => 'Counterfeit requires intent to deceive — it is fraud. An unapproved part may simply not conform to type design without anyone having intended deception.',
                ],
                [
                    'name' => 'M1Q8', 'correct' => 1,
                    'question' => '<p>After a SUP has been confirmed and the FAA notified, the part is typically disposed of via:</p>',
                    'options' => [
                        'Return to the supplier for a refund',
                        'Mutilation per FAA AC 21-38, documented in TurbineWorks records',
                        'Resale to a non-aviation industry at a discount',
                        'Placement in long-term storage as a training exhibit',
                    ],
                    'explain' => 'Mutilation per AC 21-38 is the correct disposition. The mutilation record is the evidence that the SUP cannot re-enter the supply chain.',
                ],
                [
                    'name' => 'M1Q9', 'correct' => 3,
                    'question' => '<p>You are evaluating a part shipment that arrived in OEM-branded packaging. The packaging shows tape residue and re-sealed edges suggesting it was opened and re-sealed. The 8130-3 looks valid. What should you do?</p>',
                    'options' => [
                        'Accept — the 8130-3 is the controlling document',
                        'Reject and return — packaging integrity is not a quality issue',
                        'Photograph the packaging but proceed with normal receiving',
                        'Quarantine pending investigation — repackaging may indicate substitution or tampering',
                    ],
                    'explain' => 'Compromised packaging on an OEM-marked box is a real flag that contents may have been substituted. Quarantine and investigate before accepting.',
                ],
                [
                    'name' => 'M1Q10', 'correct' => 2,
                    'question' => '<p>Who at TurbineWorks has the authority to override a Receiving Inspector\'s decision to quarantine a SUP?</p>',
                    'options' => [
                        'The General Manager, since they have corporate authority',
                        'The Sales Director, if a customer is waiting for the part',
                        'No one — the inspector\'s quarantine decision cannot be overridden; the QA Manager dispositions after investigation',
                        'The customer, if they sign a release-on-receipt waiver',
                    ],
                    'explain' => 'A foundational principle of ASA-100: quality decisions made by qualified inspectors cannot be overridden by commercial pressure. Only the QA Manager dispositions, and only after investigation.',
                ],
            ],
        ],

        'TWF4-2' => [
            'name'  => 'Module 2 Knowledge Check — Receiving and Shipping Inspection',
            'intro' => '<p>10 questions. 80% to pass. Up to 3 attempts.</p>',
            'questions' => [
                [
                    'name' => 'M2Q1', 'correct' => 1,
                    'question' => '<p>The ASA-100 §6 receiving inspection sequence begins with:</p>',
                    'options' => [
                        'Physical inspection of the part',
                        'Pre-receiving verification — match shipment to PO, verify supplier is approved',
                        'Disposition decision (accept / quarantine / reject)',
                        'Documentation review of the 8130-3',
                    ],
                    'explain' => 'Pre-receiving catches obvious problems (wrong shipment, unapproved supplier, missing documentation) before opening packaging.',
                ],
                [
                    'name' => 'M2Q2', 'correct' => 2,
                    'question' => '<p>What document is the European Union equivalent of the FAA 8130-3?</p>',
                    'options' => ['ICAO Form 1', 'JAA Form 8130', 'EASA Form 1', 'EU Form A1'],
                    'explain' => 'EASA Form 1 is the EU equivalent under FAA-EASA bilateral. TCCA Form One is the Canadian equivalent.',
                ],
                [
                    'name' => 'M2Q3', 'correct' => 0,
                    'question' => '<p>On a FAA 8130-3 tag for a serialized engine part, the right-side installer blocks (18-22) should normally be:</p>',
                    'options' => [
                        'Blank — they are completed by the installer when the part is installed on an aircraft',
                        'Filled in by the issuing organization',
                        'Stamped "VOID" until installation',
                        'Removed from the form before shipping',
                    ],
                    'explain' => 'Pre-filled installer blocks on a not-yet-installed part suggest prior installation and removal — a history flag worth investigating.',
                ],
                [
                    'name' => 'M2Q4', 'correct' => 3,
                    'question' => '<p>What is the correct disposition when a part arrives with an 8130-3 but no Certificate of Conformance required by the PO?</p>',
                    'options' => [
                        'Accept — the 8130-3 is sufficient',
                        'Reject and return to supplier',
                        'Scrap the part per AC 21-38',
                        'Hold the part pending receipt of the missing COC',
                    ],
                    'explain' => 'Missing required documentation is a hold condition. The part may eventually be accepted once documentation arrives.',
                ],
                [
                    'name' => 'M2Q5', 'correct' => 1,
                    'question' => '<p>The three possible disposition outcomes of a receiving inspection are:</p>',
                    'options' => [
                        'Pass, Conditional Pass, Fail',
                        'Accept, Quarantine, Reject',
                        'Approve, Disapprove, Refer',
                        'Service, Repair, Scrap',
                    ],
                    'explain' => 'Accept moves to serviceable; Quarantine holds for investigation; Reject removes from the supply chain. These are the only ASA-100 §6 outcomes.',
                ],
                [
                    'name' => 'M2Q6', 'correct' => 2,
                    'question' => '<p>Which block of an 8130-3 typically contains LLP time/cycle data (TSN, CSN) for a life-limited part?</p>',
                    'options' => [
                        'Block 4 (Organization)',
                        'Block 9 (Quantity)',
                        'Block 12 (Remarks)',
                        'Block 15 (Certificate Number)',
                    ],
                    'explain' => 'Block 12 (Remarks) is the free-text area where LLP times, AD compliance, SB compliance, and other critical information appears.',
                ],
                [
                    'name' => 'M2Q7', 'correct' => 0,
                    'question' => '<p>For outbound shipments to customers, ATA Spec 300 governs:</p>',
                    'options' => [
                        'Reusable packaging container categories',
                        'Hazmat documentation',
                        'Country-of-origin labeling',
                        'Customer invoice format',
                    ],
                    'explain' => 'ATA Spec 300 is the Air Transport Association packaging standard for reusable containers. Different ATA Specs cover other domains.',
                ],
                [
                    'name' => 'M2Q8', 'correct' => 2,
                    'question' => '<p>Why must the Receiving Inspector\'s accept/reject authority NOT be subject to override by Sales or management?</p>',
                    'options' => [
                        'Because it would violate union work rules',
                        'Because the inspector is paid extra for the responsibility',
                        'Because ASA-100 requires quality decisions to be independent of commercial pressure — overriding undermines the entire quality system',
                        'Because Sales does not have access to the receiving area',
                    ],
                    'explain' => 'Independent quality authority is a foundational ASA-100 principle. Without it, the audit cannot verify that quality decisions are made for quality reasons.',
                ],
                [
                    'name' => 'M2Q9', 'correct' => 1,
                    'question' => '<p>A serialized part arrives with Serial Number ABC-12345 stamped on the data plate. The 8130-3 Block 10 reads ABC-12346 (off by one digit). What is the correct disposition?</p>',
                    'options' => [
                        'Accept — this is a transcription error and the part is clearly the right one',
                        'Quarantine — investigate the discrepancy before accepting',
                        'Correct the 8130-3 to match the part with a pen and initial the correction',
                        'Stamp the part to match the 8130-3',
                    ],
                    'explain' => 'Serial number discrepancy is a hard quarantine. Never alter documentation or markings to "match" them. Investigate with the issuing organization.',
                ],
                [
                    'name' => 'M2Q10', 'correct' => 3,
                    'question' => '<p>A shipping inspector verifies a customer outbound shipment by checking all of the following EXCEPT:</p>',
                    'options' => [
                        'Documentation in the carton matches the serial number of the part',
                        'Packaging is appropriate for the part type',
                        'Labels and markings are correct and legible',
                        'The customer has been pre-paid for the shipment',
                    ],
                    'explain' => 'Payment terms are a sales/AR function, not a shipping quality check. The shipping inspector verifies physical and documentation correctness.',
                ],
            ],
        ],

        'TWF4-3' => [
            'name'  => 'Module 3 Knowledge Check — ASA-100 Familiarization',
            'intro' => '<p>8 questions. 80% to pass. Up to 3 attempts.</p>',
            'questions' => [
                [
                    'name' => 'M3Q1', 'correct' => 2,
                    'question' => '<p>ASA-100 is published by:</p>',
                    'options' => [
                        'The Federal Aviation Administration (FAA)',
                        'The International Civil Aviation Organization (ICAO)',
                        'The Aviation Suppliers Association (ASA)',
                        'The Society of Automotive Engineers (SAE)',
                    ],
                    'explain' => 'ASA-100 is published and maintained by the Aviation Suppliers Association. The FAA recognizes ASA as an accreditation organization under AC 00-56.',
                ],
                [
                    'name' => 'M3Q2', 'correct' => 0,
                    'question' => '<p>ASA-100 accreditation is, technically speaking:</p>',
                    'options' => [
                        'Voluntary — there is no FAR requiring it, but customers effectively require it',
                        'Mandatory under 14 CFR Part 21 for any parts distributor',
                        'Required only for distributors of military aviation parts',
                        'A self-declared status with no third-party audit',
                    ],
                    'explain' => 'ASA-100 is voluntary in regulation but a market requirement in practice — major airlines and MROs will not buy from unaccredited distributors.',
                ],
                [
                    'name' => 'M3Q3', 'correct' => 3,
                    'question' => '<p>The TurbineWorks Quality Assurance Manual (QAM) is the company\'s top-level quality document. In the document hierarchy, it sits:</p>',
                    'options' => [
                        'Above the FARs',
                        'Above the ASA-100 standard',
                        'At the same level as the FARs',
                        'Below the FARs, FAA Advisory Circulars, and ASA-100 — it describes how TurbineWorks complies with them',
                    ],
                    'explain' => 'The QAM is implementation. The FARs, ACs, and ASA-100 are the requirements being implemented. Higher-level documents win in conflict.',
                ],
                [
                    'name' => 'M3Q4', 'correct' => 1,
                    'question' => '<p>How often is the ASA-100 accreditation typically renewed?</p>',
                    'options' => [
                        'Annually',
                        'Every 3 years (with surveillance audits possible at any time)',
                        'Every 5 years',
                        'Once — accreditation is permanent unless revoked',
                    ],
                    'explain' => 'Accreditation cycles are typically 3 years with a full re-audit, plus ASA reserves the right to surveillance audits in between.',
                ],
                [
                    'name' => 'M3Q5', 'correct' => 0,
                    'question' => '<p>Which role at an ASA-100 accredited distributor has corporate authority to commit resources to maintain the quality system?</p>',
                    'options' => [
                        'Accountable Manager',
                        'Quality Assurance Manager',
                        'Receiving Inspector',
                        'Sales Director',
                    ],
                    'explain' => 'The Accountable Manager (typically CEO/President) is corporately responsible and has resource authority. The QA Manager owns day-to-day operations of the quality system.',
                ],
                [
                    'name' => 'M3Q6', 'correct' => 2,
                    'question' => '<p>An ASA auditor pulls a random part from your serviceable inventory and asks "Show me this part\'s receiving inspection record." How quickly should this be retrievable?</p>',
                    'options' => [
                        'Within 5 business days',
                        'By the end of the audit',
                        'Within minutes',
                        'On request to the FAA SUP office',
                    ],
                    'explain' => 'Audit-ready records are retrievable on demand. Records that take days to find indicate a recordkeeping system that does not actually work.',
                ],
                [
                    'name' => 'M3Q7', 'correct' => 1,
                    'question' => '<p>If you do not know the answer to a question an ASA auditor asks you, the correct response is:</p>',
                    'options' => [
                        'Make a reasonable guess based on what you know',
                        'Say "I don\'t know — let me find out" and then find out',
                        'Refer the auditor to the QA Manager',
                        'Say "It is in the QAM" without specifying where',
                    ],
                    'explain' => 'Honesty is what passes audits. Fabricated answers, even confident ones, are detected and cost more than admitting uncertainty.',
                ],
                [
                    'name' => 'M3Q8', 'correct' => 3,
                    'question' => '<p>When the FAA recognizes an accreditation organization under AC 00-56, the FAA is:</p>',
                    'options' => [
                        'Inspecting every accredited distributor directly',
                        'Issuing FAR regulations covering distributors',
                        'Selling parts directly to airlines',
                        'Defering distributor oversight to the industry organization, while monitoring the organization itself',
                    ],
                    'explain' => 'AC 00-56 was the FAA\'s solution to needing distributor oversight without expanding FAA inspection staff — recognize industry programs that do the auditing.',
                ],
            ],
        ],

        'TWF4-4' => [
            'name'  => 'Module 4 Knowledge Check — Parts and Warehousing',
            'intro' => '<p>8 questions. 80% to pass. Up to 3 attempts.</p>',
            'questions' => [
                [
                    'name' => 'M4Q1', 'correct' => 1,
                    'question' => '<p>The four physical status zones in an ASA-100 warehouse are:</p>',
                    'options' => [
                        'New, Used, Repaired, Scrap',
                        'Serviceable, Quarantine, Unserviceable, Scrap',
                        'In-stock, Picked, Packed, Shipped',
                        'Critical, Standard, Consumable, Hazmat',
                    ],
                    'explain' => 'These four zones must be PHYSICALLY segregated (color-coded shelves, locked cages, painted floor lines), not just tracked in software.',
                ],
                [
                    'name' => 'M4Q2', 'correct' => 0,
                    'question' => '<p>Who is authorized to move a part from Quarantine status to Serviceable status after a non-conformance is resolved?</p>',
                    'options' => [
                        'The QA Manager (only)',
                        'Any Receiving Inspector',
                        'Any warehouse picker',
                        'The Sales team upon customer order',
                    ],
                    'explain' => 'Movement OUT of Quarantine requires QA Manager authorization. This is the control that prevents informal "well, it looked OK so I moved it" decisions.',
                ],
                [
                    'name' => 'M4Q3', 'correct' => 2,
                    'question' => '<p>FIFO stock rotation is most critical for which parts category?</p>',
                    'options' => ['Turbine blades', 'Bearings', 'Shelf-life-limited items (elastomers, sealants, batteries)', 'Fasteners'],
                    'explain' => 'FIFO ensures oldest stock ships first so newer stock does not sit until it expires.',
                ],
                [
                    'name' => 'M4Q4', 'correct' => 3,
                    'question' => '<p>The single largest source of Foreign Object Damage (FOD) in a warehouse environment is typically:</p>',
                    'options' => ['Building debris', 'Personal jewelry', 'Cardboard fragments', 'Tools left in or near parts'],
                    'explain' => 'Tool control — accounting for every tool used near aviation parts — addresses the single largest FOD source.',
                ],
                [
                    'name' => 'M4Q5', 'correct' => 1,
                    'question' => '<p>A turbine engine controller arrives at receiving. It contains electronic components. Where must it be handled?</p>',
                    'options' => [
                        'On the standard receiving bench',
                        'In an ESD Protected Area (EPA) with grounded personnel and surfaces',
                        'In the hazmat cabinet',
                        'In the customer pickup area',
                    ],
                    'explain' => 'Any part with electronic content is treated as ESD-sensitive by default. ESD damage is often latent and undetectable.',
                ],
                [
                    'name' => 'M4Q6', 'correct' => 2,
                    'question' => '<p>An O-ring in inventory has reached its cure-date-based expiration. The disposition is:</p>',
                    'options' => [
                        'Re-tag as serviceable; elastomers do not really degrade',
                        'Sell at a discount with disclosure',
                        'Move to Unserviceable; cannot be shipped to customers',
                        'Re-test internally and re-issue an 8130-3',
                    ],
                    'explain' => 'Expired parts cannot be tagged serviceable. Manufacturer-authorized re-life is the only exception and requires explicit OEM documentation.',
                ],
                [
                    'name' => 'M4Q7', 'correct' => 0,
                    'question' => '<p>Per ASA-100 §7, what is the requirement regarding mixing serviceable and quarantined parts on the same shelf?</p>',
                    'options' => [
                        'They must be in physically segregated areas',
                        'Acceptable if tagged distinctly',
                        'Acceptable if separated by 6 inches or more',
                        'Acceptable for short-duration storage',
                    ],
                    'explain' => 'Physical segregation, not just labeling, is required. The goal is to make human error physically impossible.',
                ],
                [
                    'name' => 'M4Q8', 'correct' => 1,
                    'question' => '<p>A "FOD walk" is:</p>',
                    'options' => [
                        'An inspector\'s tour of the entire warehouse',
                        'A deliberate visual sweep of a specific area to identify FOD generators before handling FOD-sensitive parts',
                        'A safety briefing on personal protective equipment',
                        'A monthly inventory reconciliation procedure',
                    ],
                    'explain' => 'FOD walks happen at the start of shift, before opening packaging on sensitive parts, after handling operations, and after unusual events.',
                ],
            ],
        ],

        'TWF4-5' => [
            'name'  => 'Module 5 Knowledge Check — Recordkeeping',
            'intro' => '<p>8 questions. 80% to pass. Up to 3 attempts.</p>',
            'questions' => [
                [
                    'name' => 'M5Q1', 'correct' => 1,
                    'question' => '<p>The typical minimum records-retention period under ASA-100 §8 is:</p>',
                    'options' => ['3 years', '7 years (with LLP records effectively forever)', '10 years', '25 years'],
                    'explain' => 'Most records are retained 7+ years. LLP back-to-birth records must be available for the part\'s entire service life.',
                ],
                [
                    'name' => 'M5Q2', 'correct' => 2,
                    'question' => '<p>"Back-to-birth" traceability for a Life Limited Part means:</p>',
                    'options' => [
                        'Documentation from the part\'s most recent overhaul forward',
                        'Documentation from the most recent ownership transfer forward',
                        'Unbroken documentation from manufacture through every installation, removal, and overhaul to the present',
                        'A genetic-style trace of the part\'s original material lot',
                    ],
                    'explain' => 'An LLP with a gap in the chain has unknown accumulated cycles in the gap period. The part is effectively scrap, even if physically perfect.',
                ],
                [
                    'name' => 'M5Q3', 'correct' => 0,
                    'question' => '<p>FAA AC 21-38 governs:</p>',
                    'options' => [
                        'Disposition of unsalvageable aircraft parts (including mutilation methods)',
                        'Receiving inspection requirements for distributors',
                        'Pilot type-rating training',
                        'Aircraft registration procedures',
                    ],
                    'explain' => 'AC 21-38 defines acceptable mutilation methods so scrapped parts cannot re-enter the supply chain.',
                ],
                [
                    'name' => 'M5Q4', 'correct' => 3,
                    'question' => '<p>A receiving inspection record contains an error in the date field. How should the correction be made?</p>',
                    'options' => [
                        'Erase and rewrite',
                        'Cover with correction fluid and rewrite',
                        'Discard and complete a fresh form',
                        'Single line through the error (leaving it legible), correction beside it, initialed and dated',
                    ],
                    'explain' => 'The audit trail requires the original entry remain visible. Erasing or covering destroys evidence of the correction.',
                ],
                [
                    'name' => 'M5Q5', 'correct' => 1,
                    'question' => '<p>An auditor asks to see the procedure governing receiving inspection. You produce a printed SOP from your desk. The auditor checks the revision date and finds it is from 14 months ago, but the current revision is 6 months old. What is this finding?</p>',
                    'options' => [
                        'No finding — printed copies are reference only',
                        'Document control finding — uncontrolled / outdated copy in use',
                        'Records retention finding — the old SOP should have been kept',
                        'Training finding — you were not trained on the current revision',
                    ],
                    'explain' => 'Working from an outdated copy is a document-control finding. Every controlled document copy must be the current revision, or be retrieved when superseded.',
                ],
                [
                    'name' => 'M5Q6', 'correct' => 2,
                    'question' => '<p>Acceptable methods of mutilating an unsalvageable turbine blade per FAA AC 21-38 include:</p>',
                    'options' => [
                        'Spray-painting "REJECTED" on the airfoil',
                        'Removing and disposing of the data plate only',
                        'Cutting the blade into multiple pieces with a saw',
                        'Storing in a locked scrap cage indefinitely',
                    ],
                    'explain' => 'Mutilation must render the part physically unusable. Painting can be cleaned off; data-plate removal alone allows the plate to be applied to a different part.',
                ],
                [
                    'name' => 'M5Q7', 'correct' => 0,
                    'question' => '<p>Why must mutilation be witnessed or certified by the QA Manager (or designate)?</p>',
                    'options' => [
                        'Because the mutilation record is the legal evidence the part cannot re-enter the supply chain',
                        'Because the QA Manager must approve the disposal of any inventory',
                        'Because OSHA requires supervision of cutting operations',
                        'Because the OEM requires it for warranty purposes',
                    ],
                    'explain' => 'Without QA certification, there is no audit evidence the part was actually destroyed. The mutilation record is the closing of the supply-chain integrity loop.',
                ],
                [
                    'name' => 'M5Q8', 'correct' => 1,
                    'question' => '<p>For electronic records to satisfy ASA-100 retention requirements, they must:</p>',
                    'options' => [
                        'Be stored in PDF format only',
                        'Be in a durable format, backed up, with verifiable change history (tamper-evident), and retrievable on demand',
                        'Be approved by the FAA before storage',
                        'Be printed and stored as physical originals',
                    ],
                    'explain' => 'Format flexibility is fine. The requirements are durability, recoverability, tamper-evidence, and on-demand retrievability.',
                ],
            ],
        ],

        'TWF4-6' => [
            'name'  => 'Module 6 Knowledge Check — FAA AC 00-56',
            'intro' => '<p>6 questions. 80% to pass. Up to 3 attempts.</p>',
            'questions' => [
                [
                    'name' => 'M6Q1', 'correct' => 1,
                    'question' => '<p>FAA Advisory Circular 00-56B is:</p>',
                    'options' => [
                        'A regulation requiring distributor accreditation',
                        'Guidance defining the framework for FAA-recognized voluntary distributor accreditation',
                        'A mandatory training curriculum for receiving inspectors',
                        'An FAA-published standard that competes with ASA-100',
                    ],
                    'explain' => 'AC 00-56B is the AC framework. ASA-100 is the standard that ASA published to meet AC 00-56 criteria.',
                ],
                [
                    'name' => 'M6Q2', 'correct' => 2,
                    'question' => '<p>The primary reason the FAA created AC 00-56 was:</p>',
                    'options' => [
                        'To compete with ICAO\'s international standards',
                        'To replace 14 CFR Part 21',
                        'To provide a framework for industry-managed distributor accreditation that the FAA itself could not staff for',
                        'To consolidate multiple existing FAA inspection programs',
                    ],
                    'explain' => 'The FAA could not inspect every distributor with its own resources. AC 00-56 delegates the work to industry organizations the FAA recognizes.',
                ],
                [
                    'name' => 'M6Q3', 'correct' => 0,
                    'question' => '<p>ASA-100 and AS9120 differ primarily in that:</p>',
                    'options' => [
                        'ASA-100 is more prescriptive about aftermarket distributor operations; AS9120 is ISO-9001-based aerospace QMS structure',
                        'ASA-100 is mandatory; AS9120 is voluntary',
                        'ASA-100 covers electronics; AS9120 covers mechanical parts',
                        'There is no difference; the terms are interchangeable',
                    ],
                    'explain' => 'Both are FAA-recognized under AC 00-56. Approach and scope differ.',
                ],
                [
                    'name' => 'M6Q4', 'correct' => 3,
                    'question' => '<p>The chain of authority for a TurbineWorks receiving inspection procedure traces:</p>',
                    'options' => [
                        'OEM publication → ASA-100 → QAM',
                        'Customer contract → QA Manager → SOP',
                        'NTSB recommendation → FAA AC → QAM',
                        'AC 00-56 → ASA-100 → TurbineWorks QAM → SOP / work instructions',
                    ],
                    'explain' => 'Every procedure should trace back through the document hierarchy.',
                ],
                [
                    'name' => 'M6Q5', 'correct' => 2,
                    'question' => '<p>If TurbineWorks loses ASA-100 accreditation, the most immediate practical consequence is:</p>',
                    'options' => [
                        'FAA criminal prosecution',
                        'Mandatory shutdown of operations',
                        'Loss of customer access — customers requiring accredited suppliers cannot continue buying',
                        'Removal from the IATA membership',
                    ],
                    'explain' => 'The market enforces accreditation. The FAA does not directly mandate it, but losing accreditation removes you from the market segment that requires it.',
                ],
                [
                    'name' => 'M6Q6', 'correct' => 0,
                    'question' => '<p>An auditor asks "what FAR requires the procedure you just demonstrated?" If the procedure implements an ASA-100 requirement (not a direct FAR requirement), the correct answer is:</p>',
                    'options' => [
                        'There is no specific FAR — this procedure satisfies ASA-100 §X.Y, which we follow because we are accredited under AC 00-56',
                        'Refuse to answer; refer to the QA Manager',
                        'Cite the closest-related FAR even if not exactly applicable',
                        'Say "We have always done it this way"',
                    ],
                    'explain' => 'Honest, accurate answers about the chain of authority demonstrate understanding. Inventing a regulation that does not apply will be caught and damage credibility.',
                ],
            ],
        ],

        'TWF4-7' => [
            'name'  => 'Module 7 Knowledge Check — ESD Handling',
            'intro' => '<p>8 questions. 80% to pass. Up to 3 attempts.</p>',
            'questions' => [
                [
                    'name' => 'M7Q1', 'correct' => 2,
                    'question' => '<p>The lowest voltage that a human can typically feel as a static discharge is approximately:</p>',
                    'options' => ['100 V', '500 V', '3,000 V', '50,000 V'],
                    'explain' => 'Modern ESD-sensitive components can be damaged below 100 V — well below the human detection threshold of ~3,000 V. ESD damage occurs invisibly.',
                ],
                [
                    'name' => 'M7Q2', 'correct' => 1,
                    'question' => '<p>The industry standard for an ESD control program is:</p>',
                    'options' => ['MIL-STD-883', 'ANSI/ESD S20.20', 'NFPA 70', 'IPC-A-610'],
                    'explain' => 'ANSI/ESD S20.20 is published by the Electrostatic Discharge Association. ASA ESD Best Practices references it.',
                ],
                [
                    'name' => 'M7Q3', 'correct' => 3,
                    'question' => '<p>"Latent damage" from ESD refers to:</p>',
                    'options' => [
                        'Damage visible only with magnification',
                        'Damage that occurs hours after the ESD event',
                        'Damage to the operator, not the part',
                        'Partial damage that allows the component to function for hours or weeks before failing',
                    ],
                    'explain' => 'Latent damage is the killer — the part passes functional test, ships to customer, installs on aircraft, fails later. The ESD event is impossible to trace.',
                ],
                [
                    'name' => 'M7Q4', 'correct' => 1,
                    'question' => '<p>A wrist strap connects the wearer to ground via a series resistor of approximately:</p>',
                    'options' => ['100 ohms', '1 megohm', '1 kilohm', 'No resistor — direct ground'],
                    'explain' => '1 megohm limits current in case of accidental contact with energized equipment, while still draining static charge effectively.',
                ],
                [
                    'name' => 'M7Q5', 'correct' => 0,
                    'question' => '<p>A wrist strap should be tested:</p>',
                    'options' => [
                        'Daily, before ESD-sensitive work begins',
                        'Annually during plant maintenance',
                        'Only when visibly damaged',
                        'After each individual ESD-sensitive part is handled',
                    ],
                    'explain' => 'Daily testing catches degradation before it causes damage. Continuous-monitor systems automate this.',
                ],
                [
                    'name' => 'M7Q6', 'correct' => 2,
                    'question' => '<p>Which packaging type provides Faraday cage shielding against external electric fields, in addition to preventing tribocharging?</p>',
                    'options' => [
                        'Pink antistatic polyethylene',
                        'Anti-static bubble wrap',
                        'Metallized (silver) shielded bags',
                        'Standard ESD-marked corrugated cardboard',
                    ],
                    'explain' => 'Pink poly prevents charge buildup but does not shield. Silver shielded bags add a Faraday-cage layer for protection against external fields.',
                ],
                [
                    'name' => 'M7Q7', 'correct' => 3,
                    'question' => '<p>Why does an ESD Protected Area (EPA) typically maintain controlled humidity in the 40-60% RH range?</p>',
                    'options' => [
                        'For operator comfort during long work periods',
                        'To prevent corrosion of bare metal surfaces',
                        'To match the engine compartment environment',
                        'Because low humidity dramatically increases triboelectric charging from clothing and walking',
                    ],
                    'explain' => 'At 10% RH, walking on carpet can generate 35,000 V. At 65% RH, the same walk generates ~1,500 V. Humidity is the cheapest ESD control.',
                ],
                [
                    'name' => 'M7Q8', 'correct' => 1,
                    'question' => '<p>If you are unsure whether a part is ESD-sensitive, you should:</p>',
                    'options' => [
                        'Assume not — most parts are not sensitive',
                        'Treat as sensitive — over-protection is harmless; under-protection is destructive',
                        'Use a wrist strap but not other ESD controls',
                        'Skip handling and wait for the QA Manager',
                    ],
                    'explain' => 'Asymmetric consequences: extra ESD precaution costs nothing; missing precaution may destroy the part invisibly. Default to protection.',
                ],
            ],
        ],

        'TWF4-8' => [
            'name'  => 'Module 8 Knowledge Check — Hazmat Identification',
            'intro' => '<p>8 questions. 80% to pass. Up to 3 attempts.</p>',
            'questions' => [
                [
                    'name' => 'M8Q1', 'correct' => 2,
                    'question' => '<p>The DOT regulates hazardous materials in transportation under:</p>',
                    'options' => ['14 CFR Part 121', '40 CFR Part 261', '49 CFR Parts 100-185', '21 CFR Part 211'],
                    'explain' => '49 CFR is Transportation. Subchapter C (Parts 100-185) is the HMR.',
                ],
                [
                    'name' => 'M8Q2', 'correct' => 0,
                    'question' => '<p>The IATA Dangerous Goods Regulations (DGR) are:</p>',
                    'options' => [
                        'Published annually; each edition supersedes the previous',
                        'A static standard updated every 10 years',
                        'A subset of 49 CFR specific to air shipment',
                        'Free guidance documents from the FAA',
                    ],
                    'explain' => 'The DGR is a paid annual subscription. Current edition must be on hand where air hazmat shipments are prepared.',
                ],
                [
                    'name' => 'M8Q3', 'correct' => 3,
                    'question' => '<p>Lithium-ion batteries are classified as DOT Hazard Class:</p>',
                    'options' => ['Class 2.1 (Flammable Gas)', 'Class 3 (Flammable Liquid)', 'Class 4.3 (Dangerous When Wet)', 'Class 9 (Miscellaneous Dangerous Goods)'],
                    'explain' => 'Class 9, with specific UN numbers depending on whether standalone (UN3480) or with/in equipment (UN3481). Air shipment has tightened repeatedly due to in-flight fire incidents.',
                ],
                [
                    'name' => 'M8Q4', 'correct' => 1,
                    'question' => '<p>An Emergency Locator Transmitter (ELT) arrives in a shipment to TurbineWorks with no hazmat documentation. The correct action is:</p>',
                    'options' => [
                        'Accept — ELTs are not hazmat',
                        'Hold pending hazmat documentation — ELTs contain lithium batteries (Class 9 hazmat)',
                        'Open the unit and remove the battery before storage',
                        'Place in standard storage and ship without hazmat declaration',
                    ],
                    'explain' => 'ELTs contain lithium batteries and are heavily regulated for air shipment. Missing hazmat documentation is a hold condition.',
                ],
                [
                    'name' => 'M8Q5', 'correct' => 2,
                    'question' => '<p>The shipper certification statement on a Shipper\'s Declaration for Dangerous Goods:</p>',
                    'options' => [
                        'Is a formality with no legal weight',
                        'Is signed by the carrier on receipt',
                        'Creates personal legal liability for the signer regarding the accuracy of the declaration',
                        'Is only required for international shipments',
                    ],
                    'explain' => 'Signing a misdeclaration is criminal, especially for undeclared lithium battery shipments that cause incidents. Personal liability.',
                ],
                [
                    'name' => 'M8Q6', 'correct' => 1,
                    'question' => '<p>A passenger oxygen mask contains a chemical oxygen generator. The hazmat classification is:</p>',
                    'options' => [
                        'Class 2.2 (Compressed Gas)',
                        'Class 5.1 (Oxidizer)',
                        'Not hazmat',
                        'Class 8 (Corrosive)',
                    ],
                    'explain' => 'Chemical oxygen generators are Class 5.1. ValuJet 592 (1996) was caused by improperly-shipped chemical oxygen generators in cargo.',
                ],
                [
                    'name' => 'M8Q7', 'correct' => 3,
                    'question' => '<p>The Proper Shipping Name on a hazmat shipping document must be:</p>',
                    'options' => [
                        'A reasonable description of the hazmat',
                        'In the language of the destination country',
                        'Abbreviated to fit on the form',
                        'The exact text as it appears in the regulatory hazmat table — paraphrasing is not allowed',
                    ],
                    'explain' => 'PSN is regulatory exact text. "Li-ion batteries" is not the correct PSN. "Lithium-ion batteries" is.',
                ],
                [
                    'name' => 'M8Q8', 'correct' => 0,
                    'question' => '<p>The minimum DOT hazmat recurrent training interval is:</p>',
                    'options' => [
                        '3 years',
                        '5 years',
                        '10 years',
                        'Annual',
                    ],
                    'explain' => 'DOT requires 3-year recurrent. IATA DGR requires 2 years for air shippers. TurbineWorks 6-month recurring schedule exceeds both — hazmat training stays current as long as recurring training is current.',
                ],
            ],
        ],
    ];
}
