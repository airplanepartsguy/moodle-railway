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
    ];
}
