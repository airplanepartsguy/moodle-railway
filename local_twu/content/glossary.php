<?php
// TurbineWorks University — aviation / ASA-100 / compliance glossary.
//
// Each entry becomes a Glossary activity entry in the site-wide "Aviation
// Terminology" glossary, attached to a course in the Reference Library.
// Glossary entries can be automatically linked from text throughout the site
// when the auto-link filter is enabled.

defined('MOODLE_INTERNAL') || die();

function local_twu_get_glossary_entries(): array {
    return [
        // ASA / FAA / regulatory framework
        ['concept' => 'ASA-100', 'definition' => 'Quality Assurance Standard for Aircraft Parts Distributors, published by the Aviation Suppliers Association (ASA). The standard TurbineWorks is accredited (or being accredited) against.'],
        ['concept' => 'AS9120', 'definition' => 'Aerospace quality management system standard for distributors, published by SAE/IAQG. Complement or alternative to ASA-100; based on ISO 9001 with aerospace additions.'],
        ['concept' => 'FAA AC 00-56B', 'definition' => 'FAA Advisory Circular establishing the Voluntary Industry Distributor Accreditation Program — the framework under which ASA and other accreditation organizations operate.'],
        ['concept' => 'FAA AC 20-62E', 'definition' => 'FAA Advisory Circular on Eligibility, Quality, and Identification of Aeronautical Replacement Parts.'],
        ['concept' => 'FAA AC 21-29D', 'definition' => 'FAA Advisory Circular on Detecting and Reporting Suspected Unapproved Parts.'],
        ['concept' => 'FAA AC 21-38', 'definition' => 'FAA Advisory Circular on Disposition of Unsalvageable Aircraft Parts — defines mutilation methods and recordkeeping.'],
        ['concept' => '14 CFR Part 21', 'definition' => 'Federal Aviation Regulation governing Certification Procedures for Products and Articles.'],
        ['concept' => '14 CFR Part 39', 'definition' => 'Federal Aviation Regulation governing Airworthiness Directives — the legal basis for AD compliance.'],
        ['concept' => '14 CFR Part 43', 'definition' => 'Federal Aviation Regulation on Maintenance, Preventive Maintenance, Rebuilding, and Alteration — includes recordkeeping requirements at §43.10.'],
        ['concept' => 'Accreditation Organization (AO)', 'definition' => 'An FAA-recognized organization (such as ASA) that audits parts distributors against a published standard and issues accreditation.'],

        // Documents and tags
        ['concept' => 'FAA 8130-3', 'definition' => 'FAA Form 8130-3, Authorized Release Certificate / Airworthiness Approval Tag. The U.S. document attesting that a part conforms to type design and is in a condition for safe operation.'],
        ['concept' => 'EASA Form 1', 'definition' => 'European Union Aviation Safety Agency equivalent of the FAA 8130-3. Recognized in the U.S. under FAA-EASA bilateral agreement.'],
        ['concept' => 'TCCA Form One', 'definition' => 'Transport Canada Civil Aviation equivalent of the FAA 8130-3. Recognized in the U.S. under FAA-TCCA bilateral agreement.'],
        ['concept' => 'COC (Certificate of Conformance)', 'definition' => 'Supplier-issued certificate attesting that a part conforms to a specified standard or specification. Not a substitute for 8130-3 on airworthiness-required parts.'],
        ['concept' => 'QAM', 'definition' => 'Quality Assurance Manual — TurbineWorks\' top-level quality document, describing how the company complies with ASA-100.'],
        ['concept' => 'PMA', 'definition' => 'Parts Manufacturer Approval — FAA authority granted to a manufacturer to produce a replacement part.'],
        ['concept' => 'TSO', 'definition' => 'Technical Standard Order — FAA-issued standard for certain categories of articles. TSO authorization is one path to producing approved parts.'],

        // SUP and counterfeit
        ['concept' => 'SUP (Suspected Unapproved Part)', 'definition' => 'A part for which there is reasonable basis to believe it may be unapproved, pending investigation. Triggers quarantine and (when confirmed) FAA Form 8120-11 reporting.'],
        ['concept' => 'Unapproved Part', 'definition' => 'A part determined not to conform to approved type design, or not in a condition for safe operation. Defined in 14 CFR §21.502.'],
        ['concept' => 'Counterfeit Part', 'definition' => 'A part deliberately misrepresented as being from an approved source. Requires intent to deceive — distinct from "unapproved" in that fraud is involved.'],
        ['concept' => 'FAA Form 8120-11', 'definition' => 'Suspected Unapproved Parts Notification — the form filed with the FAA when a SUP is identified.'],
        ['concept' => 'SAE AS5553', 'definition' => 'Industry standard on Counterfeit Electronic Parts; Avoidance, Detection, Mitigation, and Disposition.'],
        ['concept' => 'SAE AS6174', 'definition' => 'Industry standard on Counterfeit Materiel; broader scope than AS5553, applies to all materiel not only electronics.'],
        ['concept' => 'Scrap diversion', 'definition' => 'The most common pathway for unapproved parts entering the supply chain: scrapped parts that were not properly mutilated, picked up, re-marked, and re-sold.'],

        // Engine parts technical
        ['concept' => 'LLP (Life Limited Part)', 'definition' => 'Engine component with a defined retirement life expressed in cycles or hours. Examples: turbine and compressor disks, certain shafts. An LLP installed past its life limit is a guaranteed catastrophic failure mode.'],
        ['concept' => 'TSN (Time Since New)', 'definition' => 'Total operating time on a part since manufacture, expressed in flight hours. Always increases.'],
        ['concept' => 'CSN (Cycles Since New)', 'definition' => 'Total operating cycles on a part since manufacture. A cycle is typically one takeoff/landing.'],
        ['concept' => 'TSO (Time Since Overhaul)', 'definition' => 'Operating time since the most recent overhaul. Resets to zero at each overhaul. (Distinct from TSO as Technical Standard Order — context determines meaning.)'],
        ['concept' => 'CSO (Cycles Since Overhaul)', 'definition' => 'Operating cycles since the most recent overhaul. Resets at overhaul.'],
        ['concept' => 'Back-to-birth traceability', 'definition' => 'Unbroken documentation chain for an LLP from manufacture through every installation, removal, and overhaul to the present. Required for LLP installation eligibility.'],
        ['concept' => 'Hard Time (HT)', 'definition' => 'Maintenance philosophy where a component is removed at a fixed time regardless of condition. Applied to LLPs and some other components.'],
        ['concept' => 'On-Condition (OC)', 'definition' => 'Maintenance philosophy where a component is operated until its condition (per inspection/monitoring) indicates maintenance is needed. No fixed time limit.'],
        ['concept' => 'Condition Monitoring (CM)', 'definition' => 'Data-driven maintenance philosophy where performance trends trigger maintenance. Increasingly dominant in modern FADEC-equipped engines.'],
        ['concept' => 'TBO (Time Between Overhauls)', 'definition' => 'Fixed overhaul interval. Mostly a legacy concept — modern turbofans use on-condition + hard-time-for-LLPs instead.'],
        ['concept' => 'BSI (Borescope Inspection)', 'definition' => 'Non-destructive visual inspection of engine internals using an optical probe, without disassembly.'],
        ['concept' => 'FADEC', 'definition' => 'Full-Authority Digital Engine Control. Modern electronic engine control unit.'],
        ['concept' => 'EEC (Electronic Engine Control)', 'definition' => 'Engine control computer; FADEC is the modern form.'],
        ['concept' => 'TBC (Thermal Barrier Coating)', 'definition' => 'Ceramic coating applied to hot-section engine parts (combustor liner, turbine airfoils) to protect parent metal from high-temperature degradation.'],
        ['concept' => 'Green time', 'definition' => 'Operating time or cycles remaining on an engine or component before the next planned maintenance event. Drives inventory pricing.'],
        ['concept' => 'Build standard', 'definition' => 'Defined configuration of an engine or component — which Service Bulletins are incorporated, OEM modifications present, thrust rating, etc.'],
        ['concept' => 'Module exchange', 'definition' => 'Replacement of an entire engine module (HPC, HPT, LPT) instead of full-engine overhaul. Faster than overhaul but complicates engine traceability.'],

        // ADs / SBs / OEM publications
        ['concept' => 'AD (Airworthiness Directive)', 'definition' => 'FAA-issued legally binding order under 14 CFR Part 39 requiring action on an aircraft, engine, or component to address an unsafe condition.'],
        ['concept' => 'SB (Service Bulletin)', 'definition' => 'OEM-issued instruction describing a modification, inspection, or operational change. Categorized by urgency: Mandatory, Alert, Recommended, Optional.'],
        ['concept' => 'ASB (Alert Service Bulletin)', 'definition' => 'Higher-urgency Service Bulletin issued in response to safety concerns. Often becomes an FAA AD.'],
        ['concept' => 'AMOC', 'definition' => 'Alternative Method of Compliance — FAA-accepted alternative to the literal AD action.'],
        ['concept' => 'ESM (Engine Shop Manual)', 'definition' => 'OEM master document for engine-level overhaul and shop work, including dimensional limits, repair procedures, inspection requirements.'],
        ['concept' => 'CMM (Component Maintenance Manual)', 'definition' => 'OEM component-level equivalent of the ESM. One CMM per major component.'],
        ['concept' => 'IPC (Illustrated Parts Catalog)', 'definition' => 'OEM authoritative list of every part in the engine or component, with current part numbers, supersedure history, applicability.'],

        // Warehousing and handling
        ['concept' => 'FOD (Foreign Object Damage)', 'definition' => 'Damage to a part caused by a foreign object during handling, packaging, or storage. A single nick on a compressor blade leading edge can cause catastrophic failure.'],
        ['concept' => 'FIFO (First-In-First-Out)', 'definition' => 'Stock rotation rule that ships oldest inventory first. Critical for shelf-life-limited items.'],
        ['concept' => 'Cure date', 'definition' => 'Manufacturer-specified date from which shelf life is calculated for elastomers and similar materials.'],
        ['concept' => 'Shelf life', 'definition' => 'Defined period during which a part may be stored before expiration. Expired parts cannot be tagged serviceable.'],
        ['concept' => 'Quarantine', 'definition' => 'Physically segregated holding area for parts with unresolved non-conformance. Parts cannot move to serviceable inventory while in quarantine.'],
        ['concept' => 'Mutilation', 'definition' => 'Permanent physical destruction of an unsalvageable part per FAA AC 21-38, so the part cannot re-enter the supply chain.'],
        ['concept' => 'ATA Spec 300', 'definition' => 'Air Transport Association specification for reusable packaging containers for aviation parts.'],
        ['concept' => 'ATA Spec 2000', 'definition' => 'ATA specification for electronic data interchange in aviation parts procurement, ordering, invoicing, and traceability.'],

        // ESD
        ['concept' => 'ESD (Electrostatic Discharge)', 'definition' => 'Sudden flow of electricity between two objects at different electrical potential. Can damage electronic components below human-detectable thresholds.'],
        ['concept' => 'ESDS (ESD-Sensitive)', 'definition' => 'A part that can be damaged by electrostatic discharge. Modern aviation electronics often fail below 100 V.'],
        ['concept' => 'EPA (ESD Protected Area)', 'definition' => 'Designated zone meeting ANSI/ESD S20.20 requirements where ESDS items can be handled safely.'],
        ['concept' => 'ANSI/ESD S20.20', 'definition' => 'Industry standard for ESD control programs, published by the Electrostatic Discharge Association.'],
        ['concept' => 'Latent damage', 'definition' => 'ESD damage that does not cause immediate failure but causes the component to fail later in service. The most insidious failure mode.'],
        ['concept' => 'Wrist strap', 'definition' => 'Conductive band worn in skin contact, connected to ground via a 1 megohm resistor. Drains static charge from the wearer.'],

        // Hazmat
        ['concept' => 'DOT (Department of Transportation)', 'definition' => 'U.S. federal department; regulates hazardous materials in transportation under 49 CFR Parts 100-185.'],
        ['concept' => 'IATA DGR', 'definition' => 'International Air Transport Association Dangerous Goods Regulations. Annual publication governing air shipment of hazmat.'],
        ['concept' => 'UN Number', 'definition' => 'Four-digit international identifier for a specific hazmat substance or article (e.g., UN3480 for lithium-ion batteries).'],
        ['concept' => 'PSN (Proper Shipping Name)', 'definition' => 'Exact regulatory text description of a hazmat. Cannot be paraphrased — must match the regulation\'s text exactly.'],
        ['concept' => 'CAO (Cargo Aircraft Only)', 'definition' => 'Restriction marking on certain hazmat air shipments — passenger aircraft prohibited.'],
        ['concept' => 'DGD', 'definition' => 'Shipper\'s Declaration for Dangerous Goods — the document accompanying every air hazmat shipment, signed by the shipper.'],
        ['concept' => 'SDS (Safety Data Sheet)', 'definition' => 'Document describing the properties, hazards, and handling of a hazmat. Required to be accessible where hazmat is stored or handled.'],

        // Engine models / OEMs
        ['concept' => 'CFM56', 'definition' => 'CFM International turbofan family — most-produced turbofan in history. Powers 737 Classic/NG, A320ceo, KC-135R. CFM56-3 (Classic), -5A/B (A320), -7B (NG) are the main commercial variants.'],
        ['concept' => 'CFM International', 'definition' => '50/50 joint venture between GE Aerospace and Safran Aircraft Engines. Producer of the CFM56 and LEAP engine families.'],
        ['concept' => 'LEAP', 'definition' => 'CFM\'s successor to the CFM56 — LEAP-1A (A320neo), LEAP-1B (737 MAX), LEAP-1C (C919). Direct-drive competitor to the PW1000G GTF.'],
        ['concept' => 'GE90', 'definition' => 'GE Aerospace high-thrust widebody turbofan for 777 family. GE90-115B is the highest-thrust certified turbofan at 115,000 lbf. Introduced composite fan blades to large commercial turbofans.'],
        ['concept' => 'GEnx', 'definition' => 'GE Aerospace turbofan for 787 (GEnx-1B) and 747-8 (GEnx-2B). Composite fan blades and fan case, TAPS combustor.'],
        ['concept' => 'GE9X', 'definition' => 'GE Aerospace turbofan exclusively for the 777X. 105,000+ lbf thrust. Largest fan diameter of any commercial turbofan.'],
        ['concept' => 'PW1000G', 'definition' => 'Pratt &amp; Whitney geared turbofan (GTF) family. PW1100G (A320neo), PW1500G (A220), PW1900G (E2). Reduction gearbox between fan and LP turbine for ~15% better fuel efficiency.'],
        ['concept' => 'GTF (Geared Turbofan)', 'definition' => 'Turbofan architecture with a reduction gearbox between the fan and LP turbine, allowing each to spin at its own optimum speed. PW1000G is the primary commercial GTF family.'],
        ['concept' => 'V2500', 'definition' => 'International Aero Engines (IAE) turbofan for A320 family. Direct competitor to CFM56-5B on the same airframe. Joint venture of Pratt &amp; Whitney and Rolls-Royce.'],
        ['concept' => 'Trent', 'definition' => 'Rolls-Royce widebody turbofan family. Trent 700 (A330), Trent 1000 (787), Trent XWB (A350), Trent 7000 (A330neo), Trent 900 (A380). Three-shaft architecture distinguishes Trent from competitor two-shaft designs.'],
        ['concept' => 'Trent 1000 TEN', 'definition' => 'Improved variant of the Trent 1000 (787) addressing in-service durability issues with the original variant. Build standard matters for valuation: TEN vs pre-TEN configurations differ substantially.'],
        ['concept' => 'Three-shaft engine', 'definition' => 'Turbofan with three concentric shafts: LP (fan + LP turbine), IP (IPC + IPT), HP (HPC + HPT). Rolls-Royce Trent family is the major commercial three-shaft architecture. Different module breakdown and more LLPs than two-shaft designs.'],
        ['concept' => 'IPC (Intermediate Pressure Compressor)', 'definition' => 'Compressor section unique to three-shaft engines (Rolls-Royce Trent family); sits between the booster (LPC) and HPC.'],
        ['concept' => 'IPT (Intermediate Pressure Turbine)', 'definition' => 'Turbine section unique to three-shaft engines, driving the IPC via the IP shaft.'],
        ['concept' => 'TotalCare', 'definition' => 'Rolls-Royce\'s power-by-the-hour service program for Trent engines. Operators pay per flight hour for guaranteed engine availability. Affects independent aftermarket parts demand because OEM controls more of the supply chain.'],
        ['concept' => 'PIP (Performance Improvement Package)', 'definition' => 'OEM-issued package of modifications addressing in-service performance or durability issues. PW1000G GTF builds are typically identified by PIP level (PIP-1, PIP-2, PIP-3, etc.).'],

        // Engine architecture
        ['concept' => 'HPC (High Pressure Compressor)', 'definition' => 'Final compressor section before the combustor. Driven by the HP turbine via the HP shaft.'],
        ['concept' => 'HPT (High Pressure Turbine)', 'definition' => 'First turbine section after the combustor. Receives hottest gas temperatures; drives the HPC.'],
        ['concept' => 'LPT (Low Pressure Turbine)', 'definition' => 'Turbine section after the HPT (and IPT if three-shaft). Drives the fan (and booster in two-shaft designs) via the LP shaft.'],
        ['concept' => 'LPC (Low Pressure Compressor) / Booster', 'definition' => 'Compressor stages upstream of the HPC, on the LP shaft (in two-shaft engines). Boost the air pressure before HPC entry.'],
        ['concept' => 'Combustor', 'definition' => 'Section where compressed air is mixed with fuel and ignited. Hot section component subject to thermal cycling, cracking, and TBC degradation.'],
        ['concept' => 'AGB (Accessory Gearbox)', 'definition' => 'Driven by the HP shaft (typically); drives fuel pumps, oil pumps, generators, starters, hydraulic pumps.'],
        ['concept' => 'Fan blade', 'definition' => 'Large airfoil at the front of the engine. Modern designs use titanium or composite blades. Subject to FOD damage and bird strike events.'],
        ['concept' => 'TAPS combustor', 'definition' => 'Twin Annular Pre-mixing Swirler — GE combustor design used on GEnx and other modern engines. Improved emissions through fuel pre-mixing.'],

        // Export control
        ['concept' => 'ITAR (International Traffic in Arms Regulations)', 'definition' => '22 CFR Parts 120-130. U.S. export control regime for defense articles (USML-listed items) and defense services. Aviation parts originally designed for military application may be ITAR-controlled. Violations carry criminal penalties.'],
        ['concept' => 'EAR (Export Administration Regulations)', 'definition' => '15 CFR Parts 730-774. U.S. export control regime for dual-use commercial items (CCL-listed). Most commercial aviation parts fall under EAR, not ITAR.'],
        ['concept' => 'USML (United States Munitions List)', 'definition' => 'ITAR list of defense articles. Items on USML require State Department licensing to export.'],
        ['concept' => 'CCL (Commerce Control List)', 'definition' => 'EAR list of dual-use commercial items. Items on CCL have ECCN classifications driving export license requirements.'],
        ['concept' => 'ECCN (Export Control Classification Number)', 'definition' => 'EAR classification code identifying a controlled item. Determines licensing requirements for export to specific destinations.'],
        ['concept' => 'OFAC', 'definition' => 'U.S. Treasury Office of Foreign Assets Control. Maintains sanctioned-party lists (SDN list, etc.) against which all transactions must be screened — separate from ITAR/EAR licensing.'],
        ['concept' => 'BIS (Bureau of Industry and Security)', 'definition' => 'U.S. Commerce Department bureau administering the EAR. Issues commercial export licenses and maintains the CCL.'],
        ['concept' => 'EUC (End Use Certificate)', 'definition' => 'Document attesting to the intended use and end-user of an exported article. Required for many ITAR exports and for some EAR exports.'],

        // Process / quality
        ['concept' => 'NCR (Non-Conformance Report)', 'definition' => 'Documented record of a quality finding — a part, process, or document that does not meet requirements. Triggers the corrective-action process.'],
        ['concept' => 'CAPA (Corrective and Preventive Action)', 'definition' => 'Quality system process for investigating non-conformances, identifying root cause, implementing corrective action, and verifying effectiveness.'],
        ['concept' => 'Root Cause Analysis', 'definition' => 'Systematic investigation to identify the underlying cause of a non-conformance (vs. the symptom). Required by ASA-100 corrective action process.'],
        ['concept' => 'MRB (Material Review Board)', 'definition' => 'Formal review process for disposition of non-conforming material. Distinct from Receiving Inspector quarantine — MRB makes the final use/repair/scrap decision on complex non-conformances.'],
        ['concept' => 'AOG (Aircraft On Ground)', 'definition' => 'Customer urgency status indicating an aircraft is grounded waiting for the part. AOG shipments are highest priority — typically next-flight-out.'],
        ['concept' => 'PMA-TC (Type Certificate)', 'definition' => 'FAA certification basis for an aircraft, engine, or component. The TC and the TCDS (Type Certificate Data Sheet) define what configuration is approved.'],
    ];
}
