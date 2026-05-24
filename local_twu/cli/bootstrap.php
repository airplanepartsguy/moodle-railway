<?php
// TurbineWorks University bootstrap CLI.
//
// Idempotent. On first run, creates the ASA-100 course/category/cohort
// structure that mirrors TWF-4. Subsequent runs are no-ops unless the
// marker file at $CFG->dataroot/.twu-bootstrapped-v1 is removed, or
// --force is passed.
//
// Bump the marker version (and the suffix here) to re-bootstrap after
// a structural change.
//
// Run as:
//   php /var/www/html/local/twu/cli/bootstrap.php
//   php /var/www/html/local/twu/cli/bootstrap.php --force

define('CLI_SCRIPT', true);

require(__DIR__ . '/../../../config.php');
require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->dirroot . '/cohort/lib.php');
require_once($CFG->libdir . '/clilib.php');

$marker = $CFG->dataroot . '/.twu-bootstrapped-v1';
$force  = in_array('--force', $argv ?? [], true);
if (file_exists($marker) && !$force) {
    cli_writeln("[twu] already bootstrapped (marker present at $marker). Pass --force to re-run.");
    exit(0);
}

cli_heading('TurbineWorks University bootstrap');

// ---------------------------------------------------------------------------
// 1. Site name
// ---------------------------------------------------------------------------
if (get_config('core', 'fullname') !== 'TurbineWorks University') {
    set_config('fullname', 'TurbineWorks University');
    set_config('shortname', 'TWU');
    cli_writeln("[twu] site name set to TurbineWorks University (TWU)");
} else {
    cli_writeln("[twu] site name already TurbineWorks University; skipping");
}

// ---------------------------------------------------------------------------
// 2. Category structure
// ---------------------------------------------------------------------------
function twu_ensure_category(string $idnumber, string $name, int $parent = 0): \core_course_category {
    global $DB;
    $existing = $DB->get_record('course_categories', ['idnumber' => $idnumber]);
    if ($existing) {
        cli_writeln("[twu] category exists: $name (id=$existing->id)");
        return core_course_category::get($existing->id, MUST_EXIST);
    }
    $data = (object)[
        'name'     => $name,
        'idnumber' => $idnumber,
        'parent'   => $parent,
    ];
    $cat = core_course_category::create($data);
    cli_writeln("[twu] created category: $name (id=$cat->id)");
    return $cat;
}

$rootcat      = twu_ensure_category('twu_asa100',    'ASA-100 Compliance');
$initialcat   = twu_ensure_category('twu_initial',   'Initial Training', $rootcat->id);
$recurringcat = twu_ensure_category('twu_recurring', 'Recurring Training (6-month)', $rootcat->id);
$refcat       = twu_ensure_category('twu_reference', 'Reference Library', $rootcat->id);
$enginecat    = twu_ensure_category('twu_engine',    'Engine-Parts Specific', $rootcat->id);

// ---------------------------------------------------------------------------
// 3. Courses — Initial Training (mirrors TWF-4 rev. Original 1:1)
// ---------------------------------------------------------------------------
function twu_ensure_course(int $categoryid, array $data): stdClass {
    global $DB;
    $existing = $DB->get_record('course', ['idnumber' => $data['idnumber']]);
    if ($existing) {
        cli_writeln("[twu] course exists: {$data['shortname']} (id=$existing->id)");
        return $existing;
    }
    $course = (object)array_merge($data, [
        'category'         => $categoryid,
        'enablecompletion' => 1,
        'completionnotify' => 1,
        'visible'          => 1,
        'format'           => 'topics',
        'numsections'      => 3,
        'summaryformat'    => FORMAT_HTML,
        'showgrades'       => 1,
        'showreports'      => 1,
    ]);
    $created = create_course($course);
    cli_writeln("[twu] created course: {$data['shortname']} — {$data['fullname']} (id=$created->id)");
    return $created;
}

$initialmodules = [
    [
        'shortname' => 'TWU-ASA-UPCM',
        'fullname'  => 'Unapproved Parts, Counterfeit Parts and Materials',
        'idnumber'  => 'TWF4-1',
        'summary'   => '<p>FAA AC 21-29D — Suspected Unapproved Parts (SUP) identification and reporting. Counterfeit parts awareness per SAE AS5553 / AS6174.</p><p><em>Per TWF-4 Rev. Original (1 hour).</em></p>',
    ],
    [
        'shortname' => 'TWU-ASA-RSI',
        'fullname'  => 'Receiving and Shipping Inspection',
        'idnumber'  => 'TWF4-2',
        'summary'   => '<p>ASA-100 §6 receiving inspection procedures. Tag verification (FAA 8130-3, EASA Form 1, TCCA Form One), traceability, COC handling.</p><p><em>Per TWF-4 Rev. Original (1 hour).</em></p>',
    ],
    [
        'shortname' => 'TWU-ASA-FAM',
        'fullname'  => 'ASA-100 Familiarization',
        'idnumber'  => 'TWF4-3',
        'summary'   => '<p>Overview of the ASA-100 standard, distributor accreditation framework, and TurbineWorks Quality Assurance Manual (QAM) structure.</p><p><em>Per TWF-4 Rev. Original (1 hour).</em></p>',
    ],
    [
        'shortname' => 'TWU-ASA-PW',
        'fullname'  => 'Parts and Warehousing',
        'idnumber'  => 'TWF4-4',
        'summary'   => '<p>ASA-100 §7 storage, segregation, FOD prevention, shelf-life monitoring, handling of hazardous materials in inventory.</p><p><em>Per TWF-4 Rev. Original (1 hour).</em></p>',
    ],
    [
        'shortname' => 'TWU-ASA-RK',
        'fullname'  => 'Recordkeeping',
        'idnumber'  => 'TWF4-5',
        'summary'   => '<p>ASA-100 §8 records retention (7+ years), FAA 8130-3 traceability documentation, document control. Disposition of unsalvageable parts per FAA AC 21-38.</p><p><em>Per TWF-4 Rev. Original (1 hour).</em></p>',
    ],
    [
        'shortname' => 'TWU-ASA-AC56',
        'fullname'  => 'FAA AC 00-56 — Voluntary Industry Distributor Accreditation',
        'idnumber'  => 'TWF4-6',
        'summary'   => '<p>The regulatory framework that ASA-100 implements. The role of FAA-recognized accreditation organizations.</p><p><em>Per TWF-4 Rev. Original (2 hours).</em></p>',
    ],
    [
        'shortname' => 'TWU-ASA-ESD',
        'fullname'  => 'ESD Handling',
        'idnumber'  => 'TWF4-7',
        'summary'   => '<p>ANSI/ESD S20.20 ESD control program. TurbineWorks ESD-safe handling procedures, packaging, workstation requirements.</p><p><em>Per TWF-4 Rev. Original (1 hour).</em></p>',
    ],
    [
        'shortname' => 'TWU-ASA-HAZ',
        'fullname'  => 'Hazmat Identification',
        'idnumber'  => 'TWF4-8',
        'summary'   => '<p>49 CFR / IATA DGR hazmat identification, classification, packaging, marking, labeling, and shipping documentation.</p><p><em>Per TWF-4 Rev. Original (1 hour).</em></p>',
    ],
];

foreach ($initialmodules as $mod) {
    twu_ensure_course($initialcat->id, $mod);
}

// ---------------------------------------------------------------------------
// 4. Cohorts (site-wide, used for auto-enrollment workflows)
// ---------------------------------------------------------------------------
function twu_ensure_cohort(string $idnumber, string $name, string $description): int {
    global $DB;
    $existing = $DB->get_record('cohort', ['idnumber' => $idnumber]);
    if ($existing) {
        cli_writeln("[twu] cohort exists: $name (id=$existing->id)");
        return $existing->id;
    }
    $cohort = (object)[
        'contextid'          => context_system::instance()->id,
        'name'               => $name,
        'idnumber'           => $idnumber,
        'description'        => $description,
        'descriptionformat'  => FORMAT_HTML,
        'visible'            => 1,
    ];
    $id = cohort_add_cohort($cohort);
    cli_writeln("[twu] created cohort: $name (id=$id)");
    return $id;
}

twu_ensure_cohort(
    'twu_initial_trainees',
    'Initial Trainees',
    '<p>Employees enrolled in the initial ASA-100 training program. Manually assigned at hire; auto-removed after all initial courses are completed.</p>'
);
twu_ensure_cohort(
    'twu_recurring_trainees',
    'Recurring Trainees (6-month)',
    '<p>Employees in the 6-month recurring training cycle. Auto-enrolled 30 days before their last initial-or-recurring completion turns 6 months old.</p>'
);

// ---------------------------------------------------------------------------
// 5. Site-wide defaults useful for compliance training
// ---------------------------------------------------------------------------
if (get_config('core', 'enablecompletion') != 1) {
    set_config('enablecompletion', 1);
    cli_writeln("[twu] enabled course completion tracking site-wide");
}
if (get_config('core', 'enableavailability') != 1) {
    set_config('enableavailability', 1);
    cli_writeln("[twu] enabled activity availability restrictions");
}
// Records retention: keep logs 7+ years per FAA/ASA-100 expectations
if ((int)get_config('logstore_standard', 'loglifetime') !== 0) {
    set_config('loglifetime', 0, 'logstore_standard');
    cli_writeln("[twu] set logstore loglifetime=0 (never auto-purge — compliance retention)");
}

// ---------------------------------------------------------------------------
// 6. Mark done
// ---------------------------------------------------------------------------
file_put_contents($marker, date('c') . "\n");
cli_writeln("[twu] bootstrap complete; marker written to $marker");
exit(0);
