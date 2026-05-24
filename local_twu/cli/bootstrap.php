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
require_once($CFG->dirroot . '/course/modlib.php');
require_once($CFG->dirroot . '/cohort/lib.php');
require_once($CFG->libdir . '/clilib.php');
require_once($CFG->libdir . '/completionlib.php');
require_once($CFG->libdir . '/filterlib.php');
require_once($CFG->libdir . '/enrollib.php');       // enrol_get_plugin / ENROL_INSTANCE_ENABLED
require_once($CFG->libdir . '/accesslib.php');      // create_role / assign_capability
require_once($CFG->dirroot . '/question/engine/bank.php');
require_once($CFG->dirroot . '/question/editlib.php');
require_once(__DIR__ . '/../content/content.php');
require_once(__DIR__ . '/../content/quizzes.php');
require_once(__DIR__ . '/../content/glossary.php');

// Run CLI as admin so question_save_from_form() etc. have proper $USER context.
\core\session\manager::set_user(get_admin());

// Bump the suffix here (and in the entrypoint marker docs) when adding new
// bootstrap steps that should run on existing sites.
$marker = $CFG->dataroot . '/.twu-bootstrapped-v35';
$force  = in_array('--force', $argv ?? [], true);
if (file_exists($marker) && !$force) {
    cli_writeln("[twu] already bootstrapped (marker present at $marker). Pass --force to re-run.");
    exit(0);
}

cli_heading('TurbineWorks University bootstrap');

// ---------------------------------------------------------------------------
// 1. Site name + frontpage identity
// ---------------------------------------------------------------------------
if (get_config('core', 'fullname') !== 'TurbineWorks University') {
    set_config('fullname', 'TurbineWorks University');
    set_config('shortname', 'TWU');
    cli_writeln("[twu] site name set to TurbineWorks University (TWU)");
} else {
    cli_writeln("[twu] site name already TurbineWorks University; skipping");
}

// Site summary — appears in the page header description and search engines.
// Frontpage layout: show course categories (not random courses) so the four
// categories we create become the primary nav for new visitors.
$sitesummary = <<<HTML
<p><strong>TurbineWorks University</strong> is the internal learning platform for <em>TurbineWorks Aircraft Engine Parts &amp; Components</em>, delivering ASA-100 compliance training, recurring 6-month refreshers, and engine-parts-specific technical training.</p>
<p>If you are a TurbineWorks employee, log in using the credentials provided by your manager. Your training assignments will appear on your dashboard.</p>
<p>If you are an auditor, customer, or visitor, please contact <a href="mailto:quality@turbineworks.com">quality@turbineworks.com</a> for access to specific training records.</p>
HTML;
set_config('summary', $sitesummary);
set_config('summaryformat', FORMAT_HTML);

// Frontpage layout — what shows on the home page.
// Format: comma-separated list of element IDs. The constants are:
//   FRONTPAGENEWS=0  FRONTPAGECOURSELIST=1  FRONTPAGECATEGORYNAMES=2
//   FRONTPAGECATEGORYCOMBO=4  FRONTPAGEENROLLEDCOURSELIST=5
//   FRONTPAGEALLCOURSELIST=6  FRONTPAGECOURSESEARCH=7
//
// Logged-out visitors see: categories (4) so the four ASA categories
// dominate the page.
// Logged-in users see: their enrolled courses (5), then categories (4) for
// browsing.
set_config('frontpage', '4,7');
set_config('frontpageloggedin', '5,4');
set_config('numsections', 1, 'site');
cli_writeln("[twu] frontpage layout: categories+search (visitors), enrolled+categories (logged in)");

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

$createdcourses = [];
foreach ($initialmodules as $mod) {
    $createdcourses[$mod['idnumber']] = twu_ensure_course($initialcat->id, $mod);
}

// Clear course overview/cover images. Snap renders overviewfiles as a
// full-width banner at the top of every course page AND as the catalog
// thumbnail — there's no setting to use it as thumbnail only. Letting
// Snap auto-generate placeholder tiles (colored by category) gives a
// cleaner look than the duplicated wordmark + title overlay we had.
$fs = get_file_storage();
foreach ($createdcourses as $idnumber => $course) {
    $coursecontext = context_course::instance($course->id);
    $fs->delete_area_files($coursecontext->id, 'course', 'overviewfiles', 0);
}
cli_writeln("[twu] cleared course overview images for Initial Training courses (Snap will auto-tile)");

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

// Status cohorts — track training lifecycle stage
$cohort_initial = twu_ensure_cohort(
    'twu_initial_trainees',
    'Initial Trainees',
    '<p>Employees in the initial ASA-100 training program. Assigned at hire; remains until all Initial Training (TWF4-1 through TWF4-8) is completed.</p>'
);
$cohort_recurring = twu_ensure_cohort(
    'twu_recurring_trainees',
    'Recurring Trainees (6-month)',
    '<p>Employees in the 6-month recurring training cycle. Cycled in 30 days before previous completion expires.</p>'
);

// All Employees cohort — every TurbineWorks employee. Auto-enrolls in
// every Initial Training course. The simplest path to "everyone trained
// on everything baseline" without per-employee enrolment management.
$cohort_all = twu_ensure_cohort(
    'twu_all_employees',
    'All Employees',
    '<p>Every TurbineWorks employee. Default cohort — auto-enrols into all eight Initial Training modules. Add a user to this cohort to grant baseline ASA-100 training access.</p>'
);

// Role-based cohorts — for role-specific recurring/additional training
// (e.g., hazmat handlers need DOT recertification every 3 years, DGR
// signers every 24 months). Membership is in addition to All Employees.
$cohort_warehouse = twu_ensure_cohort(
    'twu_role_warehouse',
    'Role: Warehouse Operators',
    '<p>Personnel performing warehouse work — receiving, storage, picking, packing. Focus on Modules 2 (Receiving/Shipping), 4 (Parts/Warehousing), 7 (ESD), 8 (Hazmat).</p>'
);
$cohort_qa = twu_ensure_cohort(
    'twu_role_qa',
    'Role: Quality Assurance',
    '<p>QA inspectors and QA management. Full Initial Training plus engine-model courses and AS9120 familiarization.</p>'
);
$cohort_shipping = twu_ensure_cohort(
    'twu_role_shipping',
    'Role: Shipping &amp; Receiving',
    '<p>Personnel involved in inbound and outbound logistics. Strong emphasis on Module 2, Module 5 (Records), Module 8 (Hazmat), and Export Control / ITAR / EAR.</p>'
);
$cohort_management = twu_ensure_cohort(
    'twu_role_management',
    'Role: Management',
    '<p>Accountable Manager, QA Manager, Operations Manager. Full Initial Training plus Operations courses (AS9120, Customer Relations, Export Control).</p>'
);

// Certification cohorts — track regulated certifications with renewal
// requirements. Membership demonstrates current certification status.
$cohort_hazmat = twu_ensure_cohort(
    'twu_cert_hazmat',
    'Certification: DOT Hazmat',
    '<p>Personnel currently certified for DOT hazmat handling per 49 CFR §172.704. Initial training plus recurrent every 3 years. Required for any employee classifying, packaging, marking, or shipping hazmat.</p>'
);
$cohort_dgr = twu_ensure_cohort(
    'twu_cert_dgr',
    'Certification: IATA DGR Air Shipper',
    '<p>Personnel currently authorised to sign Shipper&apos;s Declarations for Dangerous Goods (air). Per IATA DGR; recertification every 24 months. Subset of DOT Hazmat certification.</p>'
);
$cohort_esd = twu_ensure_cohort(
    'twu_cert_esd',
    'Certification: ESD Handler',
    '<p>Personnel trained on ANSI/ESD S20.20 program requirements and authorised to handle ESD-sensitive items within the EPA. Recurrent training per program schedule.</p>'
);
$cohort_dgd_signer = twu_ensure_cohort(
    'twu_role_dgd_signer',
    'Authority: SUP Reporter / DGD Signer',
    '<p>Specific personnel with delegated authority to file FAA Form 8120-11 (SUP) and to sign Shipper&apos;s Declarations. Tracked as a separate authority cohort independent of training certification.</p>'
);

// ---------------------------------------------------------------------------
// 4-pre. Custom roles — QA Manager / Trainer / Auditor
// ---------------------------------------------------------------------------
// Moodle's default roles (Manager / Course Creator / Teacher / Student) do not
// quite map to TurbineWorks audit roles. Adding three custom roles:
//   QA Manager — grades forums/assignments, sees all completion reports, can
//                edit certificates. Site-wide.
//   Trainer    — can edit/create course content. Course-level (not site-wide).
//   Auditor    — read-only across the whole site; intended for ASA inspector
//                or external auditor accounts during an audit visit.
//
// Wrapped in try/catch so a Moodle API change or capability-mismatch issue
// here does not kill subsequent lesson-seeding sections.
function twu_ensure_role(string $shortname, string $name, string $description,
                         int $archetype, array $contexts, array $capabilities): int {
    global $DB;
    $existing = $DB->get_record('role', ['shortname' => $shortname]);
    if ($existing) {
        $roleid = (int)$existing->id;
    } else {
        $roleid = create_role($name, $shortname, $description, '');
        cli_writeln("[twu] created role: $name ($shortname, id=$roleid)");
    }
    // Set context levels — where the role can be assigned.
    set_role_contextlevels($roleid, $contexts);
    // Apply capabilities (only if not already at this permission level).
    foreach ($capabilities as $capability => $permission) {
        $current = $DB->get_record('role_capabilities',
            ['roleid' => $roleid, 'capability' => $capability,
             'contextid' => context_system::instance()->id]);
        if (!$current || (int)$current->permission !== $permission) {
            assign_capability($capability, $permission, $roleid,
                context_system::instance()->id, true);
        }
    }
    return $roleid;
}

try {
// QA Manager — site-wide oversight of training records and grading.
twu_ensure_role(
    'twu_qa_manager',
    'QA Manager',
    'TurbineWorks Quality Assurance Manager. Oversight of all training records, can grade forums and assignments, view all completion reports, manage certificates.',
    0, // no archetype — clean slate
    [CONTEXT_SYSTEM],
    [
        // View users and their progress
        'moodle/user:viewdetails'              => CAP_ALLOW,
        'moodle/user:viewalldetails'           => CAP_ALLOW,
        'moodle/user:viewuseridentity'         => CAP_ALLOW,
        'report/completion:view'               => CAP_ALLOW,
        'report/outline:view'                  => CAP_ALLOW,
        'report/log:view'                      => CAP_ALLOW,
        'report/courseoverview:view'           => CAP_ALLOW,
        'report/participation:view'            => CAP_ALLOW,
        // Course-level oversight without owning content
        'moodle/course:view'                   => CAP_ALLOW,
        'moodle/course:viewhiddenactivities'   => CAP_ALLOW,
        'moodle/course:viewhiddencourses'      => CAP_ALLOW,
        'moodle/course:viewparticipants'       => CAP_ALLOW,
        'moodle/course:viewscales'             => CAP_ALLOW,
        // Grade everything
        'moodle/grade:viewall'                 => CAP_ALLOW,
        'moodle/grade:edit'                    => CAP_ALLOW,
        // Forum moderation
        'mod/forum:viewdiscussion'             => CAP_ALLOW,
        'mod/forum:editanypost'                => CAP_ALLOW,
        'mod/forum:replypost'                  => CAP_ALLOW,
        'mod/forum:startdiscussion'            => CAP_ALLOW,
        // Assignment grading
        'mod/assign:grade'                     => CAP_ALLOW,
        'mod/assign:view'                      => CAP_ALLOW,
        'mod/assign:viewgrades'                => CAP_ALLOW,
        // Quiz oversight
        'mod/quiz:viewreports'                 => CAP_ALLOW,
        'mod/quiz:grade'                       => CAP_ALLOW,
        // Cohort viewing
        'moodle/cohort:view'                   => CAP_ALLOW,
        // Cert oversight
        'mod/customcert:manage'                => CAP_ALLOW,
        'mod/customcert:viewreport'            => CAP_ALLOW,
        // TurbineWorks Reports admin pages
        'local/twu:viewreports'                => CAP_ALLOW,
    ]
);

// Trainer — can edit/create content but not see/grade student data.
twu_ensure_role(
    'twu_trainer',
    'Trainer / Content Editor',
    'TurbineWorks training content author. Can edit existing courses, create new lessons/quizzes, but is not part of the grading or reporting chain.',
    0,
    [CONTEXT_COURSE, CONTEXT_SYSTEM],
    [
        'moodle/course:view'                   => CAP_ALLOW,
        'moodle/course:manageactivities'       => CAP_ALLOW,
        'moodle/course:activityvisibility'     => CAP_ALLOW,
        'moodle/course:sectionvisibility'      => CAP_ALLOW,
        'moodle/course:update'                 => CAP_ALLOW,
        'moodle/course:viewhiddenactivities'   => CAP_ALLOW,
        'moodle/course:viewhiddensections'     => CAP_ALLOW,
        'mod/page:addinstance'                 => CAP_ALLOW,
        'mod/quiz:addinstance'                 => CAP_ALLOW,
        'mod/quiz:manage'                      => CAP_ALLOW,
        'mod/forum:addinstance'                => CAP_ALLOW,
        'mod/assign:addinstance'               => CAP_ALLOW,
        'mod/glossary:addinstance'             => CAP_ALLOW,
        'mod/glossary:manageentries'           => CAP_ALLOW,
        'moodle/question:add'                  => CAP_ALLOW,
        'moodle/question:editmine'             => CAP_ALLOW,
        'moodle/question:editall'              => CAP_ALLOW,
        'moodle/question:viewall'              => CAP_ALLOW,
        'moodle/question:usemine'              => CAP_ALLOW,
        'moodle/question:useall'               => CAP_ALLOW,
    ]
);

// Auditor — read-only across the whole site.
twu_ensure_role(
    'twu_auditor',
    'Auditor (Read-Only)',
    'External auditor (ASA inspector, customer auditor) or internal auditor. Read-only access to all training records, course content, completion reports, and certificates. Cannot modify any data.',
    0,
    [CONTEXT_SYSTEM],
    [
        'moodle/site:viewparticipants'         => CAP_ALLOW,
        'moodle/user:viewdetails'              => CAP_ALLOW,
        'moodle/user:viewalldetails'           => CAP_ALLOW,
        'moodle/user:viewuseridentity'         => CAP_ALLOW,
        'moodle/user:viewlastip'               => CAP_ALLOW,
        'moodle/course:view'                   => CAP_ALLOW,
        'moodle/course:viewparticipants'       => CAP_ALLOW,
        'moodle/course:viewhiddencourses'      => CAP_ALLOW,
        'moodle/course:viewhiddenactivities'   => CAP_ALLOW,
        'moodle/course:viewhiddensections'     => CAP_ALLOW,
        'report/completion:view'               => CAP_ALLOW,
        'report/outline:view'                  => CAP_ALLOW,
        'report/log:view'                      => CAP_ALLOW,
        'report/courseoverview:view'           => CAP_ALLOW,
        'report/participation:view'            => CAP_ALLOW,
        'moodle/grade:viewall'                 => CAP_ALLOW,
        'mod/forum:viewdiscussion'             => CAP_ALLOW,
        'mod/quiz:viewreports'                 => CAP_ALLOW,
        'mod/assign:view'                      => CAP_ALLOW,
        'mod/assign:viewgrades'                => CAP_ALLOW,
        'moodle/cohort:view'                   => CAP_ALLOW,
        'mod/customcert:viewreport'            => CAP_ALLOW,
        // TurbineWorks Reports admin pages — read-only
        'local/twu:viewreports'                => CAP_ALLOW,
    ]
);

cli_writeln("[twu] custom roles ensured: QA Manager, Trainer, Auditor");
} catch (Throwable $e) {
    cli_writeln("[twu] WARN custom-roles section failed: " . $e->getMessage() . " — continuing.");
}

// ---------------------------------------------------------------------------
// 4a. Custom user profile fields — TurbineWorks-specific HR / compliance data
// ---------------------------------------------------------------------------
// Stored in user_info_field; values per user in user_info_data. Drives the
// TurbineWorks reports (completion roster, cert expiry tracker, etc.) and
// makes role + certification status auditable per employee.
function twu_ensure_profile_category(string $name, int $sortorder): int {
    global $DB;
    $cat = $DB->get_record('user_info_category', ['name' => $name]);
    if ($cat) {
        return (int)$cat->id;
    }
    $id = $DB->insert_record('user_info_category', (object)[
        'name' => $name, 'sortorder' => $sortorder,
    ]);
    cli_writeln("[twu] created profile category: $name (id=$id)");
    return $id;
}

function twu_ensure_profile_field(array $field): void {
    global $DB;
    $existing = $DB->get_record('user_info_field', ['shortname' => $field['shortname']]);
    $record = (object)array_merge([
        'description'       => '',
        'descriptionformat' => FORMAT_HTML,
        'datatype'          => 'text',
        'required'          => 0,
        'locked'            => 0,
        'visible'           => 2,        // PROFILE_VISIBLE_ALL
        'forceunique'       => 0,
        'signup'            => 0,
        'defaultdata'       => '',
        'defaultdataformat' => FORMAT_HTML,
        'param1'            => 30,
        'param2'            => 2048,
        'param3'            => 0,
        'param4'            => '',
        'param5'            => '',
    ], $field);
    if ($existing) {
        $record->id = $existing->id;
        $DB->update_record('user_info_field', $record);
    } else {
        $DB->insert_record('user_info_field', $record);
        cli_writeln("[twu] created profile field: {$field['shortname']} ({$field['datatype']})");
    }
}

try {
$twcatid = twu_ensure_profile_category('TurbineWorks Employee Information', 10);

twu_ensure_profile_field([
    'shortname'  => 'twu_department',
    'name'       => 'Department',
    'datatype'   => 'menu',
    'categoryid' => $twcatid,
    'sortorder'  => 1,
    'param1'     => "Warehouse\nQuality Assurance\nShipping &amp; Receiving\nEngineering\nManagement\nSales\nAdministration\nOther",
    'description' => '<p>Primary department / functional area at TurbineWorks.</p>',
]);
twu_ensure_profile_field([
    'shortname'  => 'twu_jobtitle',
    'name'       => 'Job Title',
    'categoryid' => $twcatid,
    'sortorder'  => 2,
    'description' => '<p>Job title as it appears on the employee\'s offer letter (e.g., "QA Inspector", "Warehouse Operator", "Operations Manager").</p>',
]);
twu_ensure_profile_field([
    'shortname'  => 'twu_employeenum',
    'name'       => 'Employee Number',
    'categoryid' => $twcatid,
    'sortorder'  => 3,
    'description' => '<p>Internal TurbineWorks employee number — used in audit records to link Moodle training records to the company HR system.</p>',
]);
twu_ensure_profile_field([
    'shortname'  => 'twu_hiredate',
    'name'       => 'Hire Date',
    'datatype'   => 'datetime',
    'categoryid' => $twcatid,
    'sortorder'  => 4,
    'param1'     => 2010, // min year
    'param2'     => 2050, // max year
    'param3'     => 0,    // include time? no
    'description' => '<p>Employee\'s date of hire. Drives initial training assignment timeline (Initial Training must be completed within 90 days of hire per company policy).</p>',
]);
twu_ensure_profile_field([
    'shortname'  => 'twu_supervisor',
    'name'       => 'Supervisor',
    'categoryid' => $twcatid,
    'sortorder'  => 5,
    'description' => '<p>Name (or username) of the employee\'s direct supervisor. Used for training-completion notifications.</p>',
]);
twu_ensure_profile_field([
    'shortname'  => 'twu_hazmat_expiry',
    'name'       => 'DOT Hazmat Certification Expiry',
    'datatype'   => 'datetime',
    'categoryid' => $twcatid,
    'sortorder'  => 10,
    'param1'     => 2010,
    'param2'     => 2050,
    'param3'     => 0,
    'description' => '<p>Date that the employee\'s DOT Hazmat certification expires (per 49 CFR §172.704 — initial training plus recurrent every 3 years). Required for any employee classifying, packaging, marking, or shipping hazmat. Blank if not certified.</p>',
]);
twu_ensure_profile_field([
    'shortname'  => 'twu_dgr_expiry',
    'name'       => 'IATA DGR Certification Expiry',
    'datatype'   => 'datetime',
    'categoryid' => $twcatid,
    'sortorder'  => 11,
    'param1'     => 2010,
    'param2'     => 2050,
    'param3'     => 0,
    'description' => '<p>Date that the employee\'s IATA Dangerous Goods Regulations certification expires (per IATA DGR §1.5 — initial training plus recurrent every 24 months). Required for any employee signing Shipper&apos;s Declarations for air shipment. Blank if not certified.</p>',
]);
twu_ensure_profile_field([
    'shortname'  => 'twu_esd_expiry',
    'name'       => 'ESD Handler Certification Expiry',
    'datatype'   => 'datetime',
    'categoryid' => $twcatid,
    'sortorder'  => 12,
    'param1'     => 2010,
    'param2'     => 2050,
    'param3'     => 0,
    'description' => '<p>Date that the employee\'s ANSI/ESD S20.20 handler certification expires (per TurbineWorks ESD program — typically annual). Required for any employee handling ESD-sensitive items within the EPA. Blank if not certified.</p>',
]);
twu_ensure_profile_field([
    'shortname'  => 'twu_asa_cert',
    'name'       => 'ASA Cert / Training ID',
    'categoryid' => $twcatid,
    'sortorder'  => 20,
    'description' => '<p>Optional: external ASA-issued training-completion identifier, once TurbineWorks is accredited and ASA issues a training-program identifier.</p>',
]);
cli_writeln("[twu] custom profile fields ensured (9 fields across TurbineWorks Employee Information category)");
} catch (Throwable $e) {
    cli_writeln("[twu] WARN profile-fields section failed: " . $e->getMessage() . " — continuing.");
}

// ---------------------------------------------------------------------------
// 4b. Cohort sync — auto-enrol cohort members into their courses
// ---------------------------------------------------------------------------
function twu_ensure_cohort_sync(stdClass $course, int $cohortid, int $roleid = 5): void {
    global $DB;
    static $plugin = null;
    if ($plugin === null) {
        $plugin = enrol_get_plugin('cohort');
    }
    if (!$plugin) {
        cli_writeln("[twu] enrol_cohort plugin unavailable; cannot sync cohort to course");
        return;
    }
    $existing = $DB->get_record('enrol', [
        'courseid'   => $course->id,
        'enrol'      => 'cohort',
        'customint1' => $cohortid,
    ]);
    if ($existing) {
        return; // already linked
    }
    $plugin->add_instance($course, [
        'customint1' => $cohortid,
        'roleid'     => $roleid,
        'customint2' => 0, // no group sync
        'status'     => ENROL_INSTANCE_ENABLED,
    ]);
}

try {
// Wire All Employees → every Initial Training course
foreach ($createdcourses as $idnumber => $course) {
    twu_ensure_cohort_sync($course, $cohort_all);
}
cli_writeln("[twu] cohort sync: All Employees → 8 Initial Training courses");

// Initial Trainees cohort → also enrolled in Initial Training (same target,
// different status reason — being in this cohort means "currently working
// through initial training" vs All Employees which is permanent baseline)
foreach ($createdcourses as $idnumber => $course) {
    twu_ensure_cohort_sync($course, $cohort_initial);
}
cli_writeln("[twu] cohort sync: Initial Trainees → 8 Initial Training courses");
} catch (Throwable $e) {
    cli_writeln("[twu] WARN cohort-sync section failed: " . $e->getMessage() . " — continuing.");
}

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
// 5b. Seed lesson content (Page activities) for courses that have content
// ---------------------------------------------------------------------------
function twu_ensure_page_lesson(stdClass $course, int $sectionnum, string $name,
                                string $intro, string $content): ?int {
    global $DB;

    // Idempotency: skip if a Page activity with this name already exists in the course.
    $existing = $DB->get_record_sql(
        "SELECT cm.id
           FROM {course_modules} cm
           JOIN {modules} m ON m.id = cm.module AND m.name = 'page'
           JOIN {page} p ON p.id = cm.instance
          WHERE cm.course = :courseid AND p.name = :name",
        ['courseid' => $course->id, 'name' => $name]
    );
    if ($existing) {
        cli_writeln("[twu]   lesson exists: $name (cmid={$existing->id})");
        return (int)$existing->id;
    }

    $moduleinfo = (object)[
        'modulename'          => 'page',
        'course'              => $course->id,
        'section'             => $sectionnum,
        'visible'             => 1,
        'visibleoncoursepage' => 1,
        'name'                => $name,
        'intro'               => $intro,
        'introformat'         => FORMAT_HTML,
        // mod_page's add_instance reads $data->page['text'] / ['format'] (form
        // convention) and unpacks them into the page DB record's content fields.
        'page'                => ['text' => $content, 'format' => FORMAT_HTML],
        'display'             => 5, // PAGE_DISPLAY_OPEN
        'printheading'        => 1,
        'printintro'          => 0,
        'printlastmodified'   => 1,
        // Mark complete on view — appropriate for read-it-through training pages.
        'completion'          => COMPLETION_TRACKING_AUTOMATIC,
        'completionview'      => 1,
    ];

    try {
        $cm = add_moduleinfo($moduleinfo, $course);
        cli_writeln("[twu]   created lesson: $name (cmid={$cm->coursemodule})");
        return (int)$cm->coursemodule;
    } catch (Throwable $e) {
        cli_writeln("[twu]   could not create lesson '$name': " . $e->getMessage());
        return null;
    }
}

$alllessons = local_twu_get_lessons();
foreach ($createdcourses as $idnumber => $course) {
    $lessons = $alllessons[$idnumber] ?? [];
    if (!$lessons) {
        cli_writeln("[twu] no lessons defined yet for $idnumber; skipping content seed");
        continue;
    }
    cli_writeln("[twu] seeding " . count($lessons) . " lessons in $idnumber");
    foreach ($lessons as $lesson) {
        twu_ensure_page_lesson($course, 0, $lesson['name'], $lesson['intro'], $lesson['content']);
    }
}

// ---------------------------------------------------------------------------
// 5b2. Supplemental video resources per Initial Training module
// ---------------------------------------------------------------------------
// Curated video links from authoritative sources (FAA, NTSB, ESDA, IATA, etc.)
// embedded as a Page activity at the end of each module. Moodle's
// filter_mediaplugin auto-converts YouTube/Vimeo URLs in Page content to
// embedded video players when the filter is enabled site-wide.
//
// IMPORTANT: specific video URLs change. The QA Manager curates these
// quarterly — the lesson links to source CHANNELS (which are stable) rather
// than specific video IDs (which break). The QA Manager replaces channel
// links with specific video URLs as they identify good current content.

// Ensure mediaplugin filter is enabled site-wide so YouTube/Vimeo URLs
// render as embedded players inside Page activity HTML.
$filters = filter_get_global_states();
if (isset($filters['mediaplugin']) && (int)$filters['mediaplugin']->active !== TEXTFILTER_ON) {
    filter_set_global_state('mediaplugin', TEXTFILTER_ON);
    cli_writeln("[twu] enabled filter_mediaplugin globally (YouTube/Vimeo URL → embedded player)");
}

$videocatalog = [
    'TWF4-1' => [
        'name' => 'Supplemental Videos — SUP Awareness and Human Factors',
        'intro' => '<p>Curated authoritative training videos supporting Module 1 (Unapproved Parts). Authoritative sources: FAA, NTSB, FAA Safety Team. These videos are supplemental to the lesson content.</p>',
        'sections' => [
            [
                'heading' => 'FAA SUP Awareness',
                'description' => 'The FAA Safety Team publishes periodic videos on Suspected Unapproved Parts identification and reporting. The official channel is the authoritative source for current content.',
                'channels' => [
                    ['label' => 'FAA Safety Team (FAASafety.gov)', 'url' => 'https://www.faasafety.gov/'],
                    ['label' => 'FAA Official YouTube', 'url' => 'https://www.youtube.com/user/FAANews'],
                ],
                'curatednote' => '[QA Manager: insert URL of current FAA SUP awareness video]',
            ],
            [
                'heading' => 'Human Factors / "Dirty Dozen"',
                'description' => 'The FAA Human Factors in Aviation Maintenance course (AC 120-72) and the original Transport Canada "Dirty Dozen" framework are foundational. The Dirty Dozen identifies the 12 most common human-factor causes of maintenance errors (e.g., complacency, lack of communication, fatigue, lack of resources).',
                'channels' => [
                    ['label' => 'FAA Human Factors guidance', 'url' => 'https://www.faa.gov/regulations_policies/handbooks_manuals/aviation/'],
                    ['label' => 'FAA YouTube (search "Human Factors")', 'url' => 'https://www.youtube.com/user/FAANews/search?query=human+factors'],
                ],
                'curatednote' => '[QA Manager: insert URL of current FAA Human Factors / Dirty Dozen video]',
            ],
            [
                'heading' => 'Counterfeit Parts Case Studies',
                'description' => 'SAE International and industry organizations publish case studies on counterfeit electronic parts discovery and consequences. The 2012 Senate Armed Services Committee report on DoD counterfeit parts is foundational reading paired with industry video case studies.',
                'channels' => [
                    ['label' => 'SAE International', 'url' => 'https://www.sae.org/'],
                ],
                'curatednote' => '[QA Manager: insert URL of recent industry counterfeit-parts case study video]',
            ],
        ],
    ],
    'TWF4-2' => [
        'name' => 'Supplemental Videos — Receiving Inspection and Documentation',
        'intro' => '<p>Curated training videos on FAA 8130-3 anatomy, receiving inspection technique, and packaging integrity.</p>',
        'sections' => [
            [
                'heading' => 'FAA 8130-3 Walkthrough',
                'description' => 'Block-by-block anatomy of the FAA Form 8130-3 Airworthiness Approval Tag. Industry-published walkthroughs are available from multiple training providers.',
                'channels' => [
                    ['label' => 'FAA Form 8130-3 reference page', 'url' => 'https://www.faa.gov/forms/'],
                    ['label' => 'ASA training resources (members)', 'url' => 'https://www.aviationsuppliers.org/'],
                ],
                'curatednote' => '[QA Manager: insert URL of current 8130-3 walkthrough video]',
            ],
            [
                'heading' => 'Packaging Integrity at Receiving',
                'description' => 'Visual identification of damaged packaging, water staining, evidence of rough handling, and shipping carrier liability documentation.',
                'channels' => [],
                'curatednote' => '[QA Manager: insert URL of receiving inspection technique video]',
            ],
        ],
    ],
    'TWF4-3' => [
        'name' => 'Supplemental Videos — ASA-100 Familiarization',
        'intro' => '<p>Authoritative content from the Aviation Suppliers Association and FAA on the ASA-100 framework.</p>',
        'sections' => [
            [
                'heading' => 'ASA-100 Program Overview',
                'description' => 'The Aviation Suppliers Association publishes member and public content on the ASA-100 standard and accreditation process.',
                'channels' => [
                    ['label' => 'Aviation Suppliers Association', 'url' => 'https://www.aviationsuppliers.org/'],
                ],
                'curatednote' => '[QA Manager: insert URL of current ASA program overview video]',
            ],
        ],
    ],
    'TWF4-4' => [
        'name' => 'Supplemental Videos — FOD Prevention and Warehouse Practice',
        'intro' => '<p>FOD (Foreign Object Damage) prevention is operationally critical. NAS 412 is the consensus standard; the National Aerospace FOD Prevention organization (NAFPI) publishes extensive training material.</p>',
        'sections' => [
            [
                'heading' => 'NAS 412 / FOD Prevention',
                'description' => 'FOD has caused commercial aviation losses (Concorde Flight 4590, 2000 — tire debris caused fuel tank rupture). NAFPI publishes ongoing safety material and case studies.',
                'channels' => [
                    ['label' => 'National Aerospace FOD Prevention (NAFPI)', 'url' => 'https://www.nafpi.com/'],
                    ['label' => 'FAA FOD Awareness', 'url' => 'https://www.faa.gov/airports/airport_safety/'],
                ],
                'curatednote' => '[QA Manager: insert URL of current FOD prevention training video]',
            ],
            [
                'heading' => 'Warehouse Safety and Storage Discipline',
                'description' => 'OSHA and NSC publish warehouse-safety training; aviation-specific content from MROs covers segregation, shelf life, and storage environment.',
                'channels' => [
                    ['label' => 'OSHA Warehousing eTool', 'url' => 'https://www.osha.gov/etools/warehousing'],
                ],
                'curatednote' => '[QA Manager: insert URL of warehouse safety/storage video]',
            ],
        ],
    ],
    'TWF4-5' => [
        'name' => 'Supplemental Videos — Records and Document Control',
        'intro' => '<p>Industry videos on records retention, document control, and the audit-evidence value of complete recordkeeping.</p>',
        'sections' => [
            [
                'heading' => 'AC 21-38 Mutilation — Proper Disposition',
                'description' => 'AC 21-38 covers mutilation of unsalvageable parts. Industry training videos demonstrate approved mutilation methods by part category.',
                'channels' => [
                    ['label' => 'FAA AC 21-38', 'url' => 'https://www.faa.gov/regulations_policies/advisory_circulars/'],
                ],
                'curatednote' => '[QA Manager: insert URL of mutilation demonstration video]',
            ],
        ],
    ],
    'TWF4-6' => [
        'name' => 'Supplemental Videos — AC 00-56 and the Accreditation Framework',
        'intro' => '<p>FAA and industry content on the voluntary industry distributor accreditation program.</p>',
        'sections' => [
            [
                'heading' => 'FAA AC 00-56 Overview',
                'description' => 'The FAA publishes guidance and explanatory content on the distributor accreditation framework. ASA publishes complementary training material.',
                'channels' => [
                    ['label' => 'FAA AC 00-56B (current revision)', 'url' => 'https://www.faa.gov/regulations_policies/advisory_circulars/'],
                ],
                'curatednote' => '[QA Manager: insert URL of AC 00-56 explainer video]',
            ],
        ],
    ],
    'TWF4-7' => [
        'name' => 'Supplemental Videos — ESD Physics and Program Implementation',
        'intro' => '<p>The ESD Association (ESDA) publishes extensive training material on ANSI/ESD S20.20 program implementation, ESD physics, and verification testing.</p>',
        'sections' => [
            [
                'heading' => 'ESD Physics and Damage Mechanisms',
                'description' => 'Visual demonstrations of triboelectric charge generation, body capacitance, and ESD damage at the semiconductor level. Aviation-specific case studies highlight latent damage.',
                'channels' => [
                    ['label' => 'Electrostatic Discharge Association (ESDA)', 'url' => 'https://www.esda.org/'],
                    ['label' => 'ESDA YouTube', 'url' => 'https://www.youtube.com/user/EOSESDAssociation'],
                ],
                'curatednote' => '[QA Manager: insert URL of current ESDA training video]',
            ],
            [
                'heading' => 'ANSI/ESD S20.20 Program Elements',
                'description' => 'Detailed walkthroughs of the eight S20.20 program elements with EPA setup, personnel grounding verification, and ionizer balance testing.',
                'channels' => [
                    ['label' => 'ESDA training programs', 'url' => 'https://www.esda.org/training/'],
                ],
                'curatednote' => '[QA Manager: insert URL of S20.20 program walkthrough video]',
            ],
        ],
    ],
    'TWF4-8' => [
        'name' => 'Supplemental Videos — Hazmat Identification and Air Shipment',
        'intro' => '<p>FAA, IATA, and industry videos on hazmat classification, lithium battery air-transport hazards, and Shipper&apos;s Declaration completion.</p>',
        'sections' => [
            [
                'heading' => 'ValuJet 592 — Chemical Oxygen Generator Lesson',
                'description' => 'The 1996 ValuJet 592 crash is one of the most-cited aviation incidents driven by hazmat shipper misclassification. NTSB and aviation safety publishers maintain documentary content on the incident.',
                'channels' => [
                    ['label' => 'NTSB Aircraft Accident Reports', 'url' => 'https://www.ntsb.gov/investigations/AccidentReports/'],
                ],
                'curatednote' => '[QA Manager: insert URL of ValuJet 592 case study video]',
            ],
            [
                'heading' => 'Lithium Battery Air-Transport Hazards',
                'description' => 'UPS Flight 6 (2010) and Asiana Flight 991 (2011) drove the substantial tightening of lithium battery cargo rules. FAA and IATA publish ongoing training material on lithium battery handling.',
                'channels' => [
                    ['label' => 'FAA Lithium Battery Safety', 'url' => 'https://www.faa.gov/hazmat/'],
                    ['label' => 'IATA Lithium Batteries', 'url' => 'https://www.iata.org/en/programs/cargo/dgr/'],
                ],
                'curatednote' => '[QA Manager: insert URL of current lithium battery hazard video]',
            ],
            [
                'heading' => 'Shipper\'s Declaration Walkthrough',
                'description' => 'Field-by-field completion of the Shipper&apos;s Declaration for Dangerous Goods. IATA-approved training providers publish this content as part of the DGR training program.',
                'channels' => [
                    ['label' => 'IATA DGR training', 'url' => 'https://www.iata.org/en/training/courses/dgr-courses/'],
                ],
                'curatednote' => '[QA Manager: insert URL of DGD walkthrough video]',
            ],
        ],
    ],
];

function twu_render_video_section(array $section): string {
    $html = '<h4>' . $section['heading'] . '</h4>';
    $html .= '<p>' . $section['description'] . '</p>';
    if (!empty($section['channels'])) {
        $html .= '<p><strong>Authoritative sources:</strong></p><ul>';
        foreach ($section['channels'] as $ch) {
            $html .= '<li><a href="' . $ch['url'] . '" target="_blank" rel="noopener">' . $ch['label'] . '</a></li>';
        }
        $html .= '</ul>';
    }
    if (!empty($section['curatednote'])) {
        $html .= '<p style="background:#fff8e1; border-left:4px solid #ffc800; padding:10px 14px; margin:10px 0; font-style:italic;">' . $section['curatednote'] . '</p>';
    }
    if (!empty($section['embed_url'])) {
        // YouTube/Vimeo URLs are auto-converted by filter_mediaplugin if it
        // appears as bare URL inside a paragraph.
        $html .= '<p>' . $section['embed_url'] . '</p>';
    }
    return $html;
}

foreach ($videocatalog as $idnumber => $catalog) {
    if (!isset($createdcourses[$idnumber])) {
        continue;
    }
    $course = $createdcourses[$idnumber];
    $content = '<p>' . $catalog['intro'] . '</p>';
    $content .= '<div style="background:#f4f6fa; border-left:4px solid #0d2240; padding:14px 18px; margin:14px 0;">';
    $content .= '<p style="margin:0;"><strong>How to use this page.</strong> The videos linked below are curated supplemental content from authoritative sources (FAA, NTSB, ESDA, IATA, industry organizations). Video URLs change over time — when a specific video link is broken, follow the channel link to find current content. Items marked <em>[QA Manager: insert URL]</em> are placeholders for the QA Manager to populate as authoritative content is identified.</p>';
    $content .= '</div>';

    foreach ($catalog['sections'] as $section) {
        $content .= twu_render_video_section($section);
    }

    $content .= '<h4>Recording your video review</h4>';
    $content .= '<p>If your training requires you to review a supplemental video as part of this module, note the video title, source, and date watched in your training log. Some videos may be assigned as required pre-reading by the QA Manager.</p>';

    twu_ensure_page_lesson($course, 0, $catalog['name'], $catalog['intro'], $content);
}
cli_writeln("[twu] supplemental video resources page added to " . count($videocatalog) . " Initial Training modules");

// ---------------------------------------------------------------------------
// 5e2. Quizzes — multichoice knowledge-check at the end of each Initial Training module
// ---------------------------------------------------------------------------
function twu_ensure_question_category(stdClass $course, string $name): stdClass {
    global $DB;
    $coursecontext = context_course::instance($course->id);
    $existing = $DB->get_record('question_categories',
        ['name' => $name, 'contextid' => $coursecontext->id]);
    if ($existing) {
        return $existing;
    }
    // Ensure top-level category exists in this context.
    $top = $DB->get_record('question_categories',
        ['contextid' => $coursecontext->id, 'parent' => 0]);
    if (!$top) {
        $top = new stdClass();
        $top->name = 'top';
        $top->contextid = $coursecontext->id;
        $top->info = '';
        $top->infoformat = FORMAT_HTML;
        $top->parent = 0;
        $top->sortorder = 0;
        $top->stamp = make_unique_id_code();
        $top->idnumber = null;
        $top->id = $DB->insert_record('question_categories', $top);
    }
    $cat = new stdClass();
    $cat->name = $name;
    $cat->contextid = $coursecontext->id;
    $cat->info = '';
    $cat->infoformat = FORMAT_HTML;
    $cat->parent = $top->id;
    $cat->sortorder = 999;
    $cat->stamp = make_unique_id_code();
    $cat->idnumber = null;
    $cat->id = $DB->insert_record('question_categories', $cat);
    return $cat;
}

function twu_create_multichoice(stdClass $qcategory, array $qdata, int $contextid): ?int {
    global $DB;

    // Check by question name within the category — idempotent.
    $existing = $DB->get_record_sql(
        "SELECT q.id FROM {question} q
           JOIN {question_versions} qv ON qv.questionid = q.id
           JOIN {question_bank_entries} qbe ON qbe.id = qv.questionbankentryid
          WHERE qbe.questioncategoryid = :catid AND q.name = :name",
        ['catid' => $qcategory->id, 'name' => $qdata['name']]
    );
    if ($existing) {
        return (int)$existing->id;
    }

    $qtype = \question_bank::get_qtype('multichoice');

    $form = new stdClass();
    $form->category = $qcategory->id . ',' . $contextid;
    $form->name = $qdata['name'];
    $form->questiontext = ['text' => $qdata['question'], 'format' => FORMAT_HTML, 'itemid' => 0];
    $form->generalfeedback = ['text' => $qdata['explain'] ?? '', 'format' => FORMAT_HTML, 'itemid' => 0];
    $form->defaultmark = 1.0;
    $form->penalty = 0.3333333;
    $form->qtype = 'multichoice';
    $form->single = 1;
    $form->shuffleanswers = 1;
    $form->answernumbering = 'abc';
    $form->showstandardinstruction = 0;
    $form->correctfeedback = ['text' => '<p>Correct.</p>', 'format' => FORMAT_HTML, 'itemid' => 0];
    $form->partiallycorrectfeedback = ['text' => '', 'format' => FORMAT_HTML, 'itemid' => 0];
    $form->incorrectfeedback = ['text' => '<p>Not quite — review the explanation and the corresponding lesson.</p>', 'format' => FORMAT_HTML, 'itemid' => 0];
    $form->shownumcorrect = 0;
    $form->status = \core_question\local\bank\question_version_status::QUESTION_STATUS_READY;
    $form->idnumber = '';
    $form->tags = [];

    $opts = $qdata['options'];
    $correct = (int)$qdata['correct'];
    $form->answer = [];
    $form->fraction = [];
    $form->feedback = [];
    foreach ($opts as $i => $opttext) {
        $form->answer[$i]   = ['text' => $opttext, 'format' => FORMAT_HTML, 'itemid' => 0];
        $form->fraction[$i] = ($i === $correct) ? '1.0' : '0';
        $form->feedback[$i] = ['text' => '', 'format' => FORMAT_HTML, 'itemid' => 0];
    }

    $stub = new stdClass();
    $stub->createdby = 2; // admin user
    $stub->idnumber = null;
    $stub->id = 0;

    try {
        $question = $qtype->save_question($stub, $form);
        return (int)$question->id;
    } catch (Throwable $e) {
        cli_writeln("[twu]     could not save question '{$qdata['name']}': " . $e->getMessage());
        return null;
    }
}

function twu_ensure_quiz(stdClass $course, array $quizdef): ?int {
    global $DB, $CFG;
    require_once($CFG->dirroot . '/mod/quiz/locallib.php');

    // Idempotency: skip if a Quiz with this name already exists in the course.
    $existing = $DB->get_record_sql(
        "SELECT cm.id, q.id AS quizid
           FROM {course_modules} cm
           JOIN {modules} m ON m.id = cm.module AND m.name = 'quiz'
           JOIN {quiz} q ON q.id = cm.instance
          WHERE cm.course = :courseid AND q.name = :name",
        ['courseid' => $course->id, 'name' => $quizdef['name']]
    );
    if ($existing) {
        cli_writeln("[twu]   quiz exists: {$quizdef['name']} (cmid={$existing->id})");
        return (int)$existing->id;
    }

    // 1. Question category in course context.
    $qcategory = twu_ensure_question_category($course, $quizdef['name'] . ' — Question Bank');
    $coursecontext = context_course::instance($course->id);

    // 2. Create the questions.
    $questionids = [];
    foreach ($quizdef['questions'] as $qdata) {
        $qid = twu_create_multichoice($qcategory, $qdata, $coursecontext->id);
        if ($qid) {
            $questionids[] = $qid;
        }
    }
    if (!$questionids) {
        cli_writeln("[twu]   no questions created for {$quizdef['name']}; skipping quiz creation");
        return null;
    }

    // 3. Create the quiz module via add_moduleinfo.
    $moduleinfo = (object)[
        'modulename'          => 'quiz',
        'course'              => $course->id,
        'section'             => 0,
        'visible'             => 1,
        'visibleoncoursepage' => 1,
        'name'                => $quizdef['name'],
        'intro'               => $quizdef['intro'] ?? '',
        'introformat'         => FORMAT_HTML,
        'timeopen'            => 0,
        'timeclose'           => 0,
        'timelimit'           => 0,
        'overduehandling'     => 'autosubmit',
        'graceperiod'         => 0,
        'preferredbehaviour'  => 'deferredfeedback',
        'attempts'            => 3,
        'attemptonlast'       => 0,
        'grademethod'         => QUIZ_GRADEHIGHEST,
        'questionsperpage'    => 1,
        'navmethod'           => 'free',
        'shuffleanswers'      => 1,
        'sumgrades'           => count($questionids),
        'grade'               => 100,
        'gradepass'           => 80,
        'decimalpoints'       => 2,
        'questiondecimalpoints' => -1,
        'showuserpicture'     => 0,
        'showblocks'          => 0,
        'reviewattempt'       => 0x10000 + 0x01000 + 0x00100 + 0x00010 + 0x00001,
        'reviewcorrectness'   => 0x10000 + 0x01000,
        'reviewmarks'         => 0x10000 + 0x01000,
        'reviewspecificfeedback' => 0x10000 + 0x01000,
        'reviewgeneralfeedback'  => 0x10000 + 0x01000,
        'reviewrightanswer'   => 0x10000,
        'reviewoverallfeedback' => 0x10000 + 0x01000,
        'completion'          => COMPLETION_TRACKING_AUTOMATIC,
        'completionview'      => 0,
        'completionpassgrade' => 1,
        'completionattemptsexhausted' => 0,
    ];

    try {
        $cm = add_moduleinfo($moduleinfo, $course);
    } catch (Throwable $e) {
        cli_writeln("[twu]   could not create quiz module: " . $e->getMessage());
        return null;
    }

    // 4. Attach each question to the quiz.
    $quiz = $DB->get_record('quiz', ['id' => $cm->instance], '*', MUST_EXIST);
    foreach ($questionids as $qid) {
        try {
            quiz_add_quiz_question($qid, $quiz, 0);
        } catch (Throwable $e) {
            cli_writeln("[twu]     could not attach question $qid: " . $e->getMessage());
        }
    }
    cli_writeln("[twu]   created quiz: {$quizdef['name']} (cmid={$cm->coursemodule}, " . count($questionids) . " questions)");
    return (int)$cm->coursemodule;
}

// ---------------------------------------------------------------------------
// 5e2c. Cumulative Final Exam — randomized across all 8 module banks
// ---------------------------------------------------------------------------
// Creates a separate course (TWU-ASA-FINAL) containing a single cumulative
// exam that pulls random questions from each of the 8 module question banks.
// Availability is gated behind completion of all 8 Initial Training modules
// — so only employees who have passed every module's individual quiz can
// even attempt the cumulative exam. Passing this exam earns a master cert.

$finalcoursedata = [
    'shortname' => 'TWU-ASA-FINAL',
    'fullname'  => 'ASA-100 Initial Training — Cumulative Final Exam',
    'idnumber'  => 'TWF4-FINAL',
    'summary'   => '<p>The capstone cumulative exam covering all eight Initial Training modules. 40 questions drawn at random from each module&apos;s question bank (5 per module). 80% pass mark. Required for the TurbineWorks University Initial Training master certification.</p><p><strong>Availability:</strong> this exam becomes available only after all eight Initial Training modules (TWF4-1 through TWF4-8) are complete.</p>',
];
$finalcourse = twu_ensure_course($initialcat->id, $finalcoursedata);

// Wire cohort sync for the final exam course.
twu_ensure_cohort_sync($finalcourse, $cohort_all);
twu_ensure_cohort_sync($finalcourse, $cohort_initial);

// Gate availability — require each prerequisite module's QUIZ to be passing-
// complete before the final exam content is accessible. Uses the standard
// availability_completion condition (well-supported in Moodle 4.5) rather
// than the less-standard "completion_course" type. The gate is applied to
// section 0 of the final exam course, hiding all its activities until each
// referenced quiz cm is complete.
//
// We defer the call until after per-module quizzes are created (their cm
// ids do not exist yet here). The function is defined now and invoked later.
function twu_gate_final_exam_section(stdClass $course, array $prereqcourseids): int {
    global $DB;
    if (!$prereqcourseids) {
        return 0;
    }
    // Find each prereq course's quiz cmid.
    list($insql, $params) = $DB->get_in_or_equal($prereqcourseids, SQL_PARAMS_NAMED, 'c');
    $cmids = $DB->get_fieldset_sql(
        "SELECT cm.id
           FROM {course_modules} cm
           JOIN {modules} m ON m.id = cm.module AND m.name = 'quiz'
          WHERE cm.course $insql",
        $params
    );
    if (!$cmids) {
        return 0;
    }
    $conditions = [];
    foreach ($cmids as $cmid) {
        // e=1 means COMPLETION_COMPLETE — module marked complete (which for
        // a quiz with completionpassgrade requires a passing attempt).
        $conditions[] = ['type' => 'completion', 'cm' => (int)$cmid, 'e' => 1];
    }
    $availability = json_encode([
        'op'    => '&',
        'c'     => $conditions,
        'showc' => array_fill(0, count($conditions), true),
    ]);
    $sec0 = $DB->get_record('course_sections', ['course' => $course->id, 'section' => 0]);
    if ($sec0 && $sec0->availability !== $availability) {
        $DB->set_field('course_sections', 'availability', $availability, ['id' => $sec0->id]);
    }
    return count($cmids);
}

// Build the cumulative exam definition. Pull 5 random questions from each
// of the 8 module question categories. The quiz module supports random
// questions via quiz_add_random_questions().
require_once($CFG->dirroot . '/mod/quiz/locallib.php');

function twu_ensure_cumulative_exam(stdClass $course, array $modulecourses): ?int {
    global $DB, $CFG;
    $name = 'Cumulative Final Exam — ASA-100 Initial Training';

    $existing = $DB->get_record_sql(
        "SELECT cm.id, q.id AS quizid
           FROM {course_modules} cm
           JOIN {modules} m ON m.id = cm.module AND m.name = 'quiz'
           JOIN {quiz} q ON q.id = cm.instance
          WHERE cm.course = :courseid AND q.name = :name",
        ['courseid' => $course->id, 'name' => $name]
    );
    if ($existing) {
        cli_writeln("[twu]   cumulative exam exists: $name (cmid={$existing->id})");
        return (int)$existing->quizid;
    }

    // Random question slots: 5 from each of 8 module question categories = 40 total.
    $totalq = 40;

    $moduleinfo = (object)[
        'modulename'          => 'quiz',
        'course'              => $course->id,
        'section'             => 0,
        'visible'             => 1,
        'visibleoncoursepage' => 1,
        'name'                => $name,
        'intro'               => '<p>40 questions drawn at random from the 8 Initial Training module question banks (5 per module). Pass mark: 80% (32/40). Up to 3 attempts. Deferred feedback — full review after each attempt.</p><p>This is the capstone assessment for the TurbineWorks University Initial Training program. Passing earns the master certification.</p>',
        'introformat'         => FORMAT_HTML,
        'timeopen'            => 0,
        'timeclose'           => 0,
        'timelimit'           => 60 * 60, // 60 minutes
        'overduehandling'     => 'autosubmit',
        'graceperiod'         => 0,
        'preferredbehaviour'  => 'deferredfeedback',
        'attempts'            => 3,
        'attemptonlast'       => 0,
        'grademethod'         => QUIZ_GRADEHIGHEST,
        'questionsperpage'    => 1,
        'navmethod'           => 'free',
        'shuffleanswers'      => 1,
        'sumgrades'           => $totalq,
        'grade'               => 100,
        'gradepass'           => 80,
        'decimalpoints'       => 2,
        'questiondecimalpoints' => -1,
        'showuserpicture'     => 0,
        'showblocks'          => 0,
        'reviewattempt'       => 0x10000 + 0x01000 + 0x00100 + 0x00010 + 0x00001,
        'reviewcorrectness'   => 0x10000 + 0x01000,
        'reviewmarks'         => 0x10000 + 0x01000,
        'reviewspecificfeedback' => 0x10000 + 0x01000,
        'reviewgeneralfeedback'  => 0x10000 + 0x01000,
        'reviewrightanswer'   => 0x10000,
        'reviewoverallfeedback' => 0x10000 + 0x01000,
        'completion'          => COMPLETION_TRACKING_AUTOMATIC,
        'completionview'      => 0,
        'completionpassgrade' => 1,
        'completionattemptsexhausted' => 0,
    ];

    try {
        $cm = add_moduleinfo($moduleinfo, $course);
    } catch (Throwable $e) {
        cli_writeln("[twu]   could not create cumulative exam: " . $e->getMessage());
        return null;
    }
    $quiz = $DB->get_record('quiz', ['id' => $cm->instance], '*', MUST_EXIST);

    // For each module course, find its question category and add 5 random
    // questions to the cumulative exam.
    $added = 0;
    foreach ($modulecourses as $modcourse) {
        $coursecontext = context_course::instance($modcourse->id);
        $qcat = $DB->get_record_sql(
            "SELECT id FROM {question_categories}
              WHERE contextid = :ctx AND name LIKE :name
              ORDER BY id ASC LIMIT 1",
            ['ctx' => $coursecontext->id, 'name' => '%Knowledge Check%']
        );
        if (!$qcat) {
            cli_writeln("[twu]   no question category for course id={$modcourse->id}; skipping");
            continue;
        }
        try {
            quiz_add_random_questions($quiz, 0, $qcat->id, 5, false);
            $added += 5;
        } catch (Throwable $e) {
            cli_writeln("[twu]   could not add 5 random questions from category {$qcat->id}: " . $e->getMessage());
        }
    }

    cli_writeln("[twu]   created cumulative exam: $name (cmid={$cm->coursemodule}, $added random slots)");
    return (int)$cm->instance;
}

// Add the final exam quiz AFTER per-module quizzes have been created (their
// question categories must exist first). We defer this call until after the
// per-module quiz loop completes.

$allquizzes = local_twu_get_quizzes();
foreach ($createdcourses as $idnumber => $course) {
    if (!isset($allquizzes[$idnumber])) {
        continue;
    }
    cli_writeln("[twu] seeding quiz for $idnumber");
    twu_ensure_quiz($course, $allquizzes[$idnumber]);
}

// Now create the cumulative final exam — question categories exist as of
// the per-module quiz creation above.
cli_writeln("[twu] creating cumulative final exam in TWF4-FINAL");

try {
// Add an intro Page so the course has a visible "About the exam" entry
// before the actual quiz. Acts as documentation when the quiz is gated.
twu_ensure_page_lesson(
    $finalcourse, 0, 'About the Cumulative Final Exam',
    '<p>What you need to know before attempting the cumulative final exam.</p>',
    '<h3>About the Cumulative Final Exam</h3>
<p>This course contains the capstone assessment for the TurbineWorks University ASA-100 Initial Training program: a single 40-question exam drawing random questions from each of the eight Initial Training modules.</p>

<h4>Prerequisites</h4>
<p>Before this exam unlocks, you must have <strong>passed the end-of-module quiz</strong> in each of the eight Initial Training modules (TWF4-1 through TWF4-8). The exam content is locked until each module quiz is marked complete in your training record.</p>

<h4>Exam structure</h4>
<ul>
  <li><strong>40 questions</strong> total — 5 randomly drawn from each of the 8 module question banks</li>
  <li><strong>60 minutes</strong> to complete (one attempt session)</li>
  <li><strong>80% pass mark</strong> — 32 of 40 correct</li>
  <li><strong>Up to 3 attempts</strong> — best score counts</li>
  <li><strong>Deferred feedback</strong> — full review of correct answers shown after each attempt</li>
  <li><strong>Random shuffle</strong> — question order and answer order randomized per attempt</li>
</ul>

<h4>What you earn by passing</h4>
<ul>
  <li>TurbineWorks University Initial Training master certificate (branded PDF with unique verification code)</li>
  <li>Your training record reflects "ASA-100 Initial Training Complete" status</li>
  <li>You enter the 6-month recurring cycle (next recurring training due ~180 days from completion)</li>
</ul>

<h4>What if you do not pass</h4>
<p>The system records the failed attempt and lets you try again (up to 3 total). After a third failure, contact the QA Manager. The QA Manager will review the per-question results, identify which module(s) need additional review, and may assign supplemental study or coaching before a fourth attempt is allowed.</p>

<h4>Failed attempts are not punishment</h4>
<p>The exam is hard by design. Passing it means you genuinely understand the material across all eight modules — that is the credential ASA accreditation requires. If you struggle on first attempt, that is normal and indicates the assessment is doing its job. Use the feedback after each attempt to study and re-attempt.</p>

<h4>Recurring training cadence</h4>
<p>Per TurbineWorks policy (more rigorous than ASA-100&apos;s annual minimum), the master training credential expires every 180 days. The recurring reset task will mark your module completions as expired automatically; you will need to re-take the modules and re-pass this exam to maintain currency.</p>'
);

twu_ensure_cumulative_exam($finalcourse, $createdcourses);

// Now that per-module quizzes exist, gate the final exam section behind
// passing-completion of each module quiz.
$gated = twu_gate_final_exam_section($finalcourse, array_map(fn($c) => $c->id, $createdcourses));
cli_writeln("[twu] final exam section gated behind $gated module quiz completions");

// Issue a master cert on the final exam course.
twu_ensure_customcert($finalcourse);
} catch (Throwable $e) {
    cli_writeln("[twu] WARN cumulative-exam wiring failed: " . $e->getMessage() . " — continuing.");
}

// ---------------------------------------------------------------------------
// 5e2b. Practical Assignments — competency assessment (online text response)
// ---------------------------------------------------------------------------
// One practical assignment per Initial Training module. Trainee submits a
// 200-500 word written response to a real operational scenario; QA Manager
// grades. Adds competency-assessment evidence beyond multiple-choice quiz.
function twu_ensure_assignment(stdClass $course, array $def): ?int {
    global $DB, $CFG;
    require_once($CFG->dirroot . '/mod/assign/locallib.php');

    $existing = $DB->get_record_sql(
        "SELECT cm.id, a.id AS assignid
           FROM {course_modules} cm
           JOIN {modules} m ON m.id = cm.module AND m.name = 'assign'
           JOIN {assign} a ON a.id = cm.instance
          WHERE cm.course = :courseid AND a.name = :name",
        ['courseid' => $course->id, 'name' => $def['name']]
    );
    if ($existing) {
        cli_writeln("[twu]   assignment exists: {$def['name']} (cmid={$existing->id})");
        return (int)$existing->id;
    }

    $moduleinfo = (object)[
        'modulename'                                  => 'assign',
        'course'                                      => $course->id,
        'section'                                     => 0,
        'visible'                                     => 1,
        'visibleoncoursepage'                         => 1,
        'name'                                        => $def['name'],
        'intro'                                       => $def['intro'],
        'introformat'                                 => FORMAT_HTML,
        'alwaysshowdescription'                       => 1,
        'submissiondrafts'                            => 0,
        'requiresubmissionstatement'                  => 0,
        'sendnotifications'                           => 0,
        'sendlatenotifications'                       => 0,
        'sendstudentnotifications'                    => 1,
        'duedate'                                     => 0,
        'cutoffdate'                                  => 0,
        'gradingduedate'                              => 0,
        'allowsubmissionsfromdate'                    => 0,
        'grade'                                       => 100,
        'gradecat'                                    => 0,
        'gradepass'                                   => 70,
        'teamsubmission'                              => 0,
        'requireallteammemberssubmit'                 => 0,
        'teamsubmissiongroupingid'                    => 0,
        'blindmarking'                                => 0,
        'attemptreopenmethod'                         => 'untilpass',
        'maxattempts'                                 => -1,
        'markingworkflow'                             => 0,
        'markingallocation'                           => 0,
        'preventsubmissionnotingroup'                 => 0,
        // Online text submission only (no file upload required).
        'assignsubmission_onlinetext_enabled'         => 1,
        'assignsubmission_onlinetext_wordlimit_enabled' => 1,
        'assignsubmission_onlinetext_wordlimit'       => 800,
        'assignsubmission_file_enabled'               => 0,
        'assignsubmission_comments_enabled'           => 0,
        // Feedback by QA Manager via comments.
        'assignfeedback_comments_enabled'             => 1,
        'assignfeedback_file_enabled'                 => 0,
        'assignfeedback_offline_enabled'              => 0,
        'completion'                                  => COMPLETION_TRACKING_AUTOMATIC,
        'completionview'                              => 0,
        'completionusegrade'                          => 1,
        'completionpassgrade'                         => 1,
    ];

    try {
        $cm = add_moduleinfo($moduleinfo, $course);
        cli_writeln("[twu]   created assignment: {$def['name']} (cmid={$cm->coursemodule})");
        return (int)$cm->coursemodule;
    } catch (Throwable $e) {
        cli_writeln("[twu]   could not create assignment '{$def['name']}': " . $e->getMessage());
        return null;
    }
}

$assignments = [
    'TWF4-1' => [
        'name' => 'Practical: Photocopied 8130-3 Decision Process',
        'intro' => '<h4>Scenario</h4>
<p>You are at receiving inspection. A part arrives from a supplier you have not used before. The supplier&apos;s box contains a photocopied FAA Form 8130-3 with a stamp that says &ldquo;TRUE COPY&rdquo; in red ink. The part itself is in its OEM packaging. Block 15 (certificate number) on the 8130-3 looks unfamiliar.</p>

<h4>Your task</h4>
<p>In 300–500 words, walk through your decision process step by step. Specifically address:</p>
<ol>
  <li>What is the first thing you do when you see the photocopy?</li>
  <li>How do you verify whether the &ldquo;TRUE COPY&rdquo; stamp is legitimate?</li>
  <li>What independent verification do you perform on Block 15 (certificate number)?</li>
  <li>Under what circumstances would you accept the part into serviceable inventory?</li>
  <li>Under what circumstances would you initiate a SUP (Suspected Unapproved Part) procedure?</li>
  <li>What records do you create regardless of the outcome?</li>
</ol>
<p>Your answer will be reviewed by the QA Manager. Pass mark: 70%. Demonstrating careful, sequential thinking matters more than reaching the &ldquo;right&rdquo; conclusion — there are multiple correct paths depending on what the verification turns up.</p>',
    ],
    'TWF4-2' => [
        'name' => 'Practical: Damaged Carton Receiving Inspection',
        'intro' => '<h4>Scenario</h4>
<p>A shipment arrives at TurbineWorks. The outer carton has visible crush damage on one side. The bill of lading shows the contents include a high-value FADEC unit destined for a customer order shipping next week. The carrier driver is waiting for your signature.</p>

<h4>Your task</h4>
<p>In 300–500 words, document the full receiving inspection sequence for this shipment. Address:</p>
<ol>
  <li>Do you sign for the shipment? Under what conditions? What annotations do you make on the carrier paperwork?</li>
  <li>Before opening the box, what photographs and records do you create?</li>
  <li>When you open the box, what do you check first about the FADEC&apos;s ESD-safe inner packaging?</li>
  <li>If the inner packaging looks fine but the carton is damaged, do you accept the FADEC into serviceable inventory? What\'s your reasoning?</li>
  <li>What communication do you initiate with the supplier? With the carrier? Within what timeframe?</li>
  <li>What disposition options exist if the FADEC may have been damaged in transit?</li>
</ol>
<p>QA Manager review. Pass mark: 70%.</p>',
    ],
    'TWF4-3' => [
        'name' => 'Practical: Map ASA-100 Section 6 to Operational Reality',
        'intro' => '<h4>Scenario</h4>
<p>An ASA inspector visits TurbineWorks and asks: "Show me how you satisfy ASA-100 Section 6 (Receiving Inspection) in your daily operations. Walk me through one part from arrival to inventory."</p>

<h4>Your task</h4>
<p>In 300–500 words, describe one specific receiving scenario (you choose: a fan blade, an HMU, a fuel nozzle set, or another part type) and walk through how each of the ASA-100 Section 6 requirements is implemented in your daily work. Address:</p>
<ol>
  <li>Visual inspection — what specifically you check</li>
  <li>Documentation verification — which records, in what order</li>
  <li>Traceability confirmation — how you establish back-to-birth status</li>
  <li>Disposition — accepted to serviceable, quarantined, or rejected, and the record trail</li>
  <li>Where each step ties to a specific section of the TurbineWorks QAM</li>
</ol>
<p>You may reference the TurbineWorks QAM (use your best understanding of what it contains; if you are unsure of a specific section, note that as something to verify). QA Manager review.</p>',
    ],
    'TWF4-4' => [
        'name' => 'Practical: FOD Walk-Through Risk Assessment',
        'intro' => '<h4>Scenario</h4>
<p>You walk the TurbineWorks warehouse for a FOD risk assessment. You find three areas of concern.</p>

<h4>Your task</h4>
<p>In 300–500 words, identify three specific FOD risks you observe (or could realistically observe) in the warehouse. For each:</p>
<ol>
  <li>Describe the risk specifically (what could enter inventory and what damage it could cause)</li>
  <li>Identify which parts in inventory are most affected (e.g., engine-flow-path parts vs structural parts)</li>
  <li>Propose a corrective action — what would you change about workflow, layout, or training?</li>
  <li>Estimate the cost vs benefit of the change</li>
</ol>
<p>You may use real or hypothetical examples. Be specific — &ldquo;keep the warehouse cleaner&rdquo; is not adequate; &ldquo;establish a FOD walk-down checklist for the receiving bench at end of each shift&rdquo; is. QA Manager review.</p>',
    ],
    'TWF4-5' => [
        'name' => 'Practical: Reconstruct an LLP&apos;s Back-to-Birth Records',
        'intro' => '<h4>Scenario</h4>
<p>A customer requests proof of back-to-birth records for a CFM56-7B HPT disk. You retrieve the records package from TurbineWorks inventory and find: original FAA 8130-3 from manufacture, two subsequent 8130-3s from intermediate operators, a partial shop visit log from one MRO, and a current 8130-3 from the last operator. The cycle count math does not quite add up — there is a 200-cycle discrepancy between the engine logs and the LLP records.</p>

<h4>Your task</h4>
<p>In 300–500 words, walk through your decision process:</p>
<ol>
  <li>What does the 200-cycle discrepancy potentially indicate?</li>
  <li>What sources of additional records would you pursue to resolve it?</li>
  <li>Can you ship the disk as-is to the customer with a note about the discrepancy? Why or why not?</li>
  <li>If the discrepancy cannot be resolved, what dispositions are available?</li>
  <li>What records do you create about the investigation and the final disposition?</li>
</ol>
<p>QA Manager review.</p>',
    ],
    'TWF4-6' => [
        'name' => 'Practical: Trace an AC 00-56 Requirement to Daily Operations',
        'intro' => '<h4>Scenario</h4>
<p>You are explaining the regulatory framework to a new employee. They ask: "Where does our daily receiving inspection procedure come from? Who decided we have to do it this way?"</p>

<h4>Your task</h4>
<p>In 300–500 words, trace one specific operational practice (receiving inspection of an LLP, mutilation of a scrap part, hazmat shipping documentation, or another practice you choose) through the regulatory hierarchy. Show:</p>
<ol>
  <li>Which FAA regulation or AC sets the framework (e.g., AC 00-56)</li>
  <li>Which industry standard interprets the framework (e.g., ASA-100)</li>
  <li>Which TurbineWorks QAM section implements the standard</li>
  <li>Which specific SOP describes the daily procedure</li>
  <li>How a TurbineWorks employee learns to do it correctly</li>
</ol>
<p>The point is to show the flow-down from federal regulation to daily work. QA Manager review.</p>',
    ],
    'TWF4-7' => [
        'name' => 'Practical: Disposition After an ESD Handling Incident',
        'intro' => '<h4>Scenario</h4>
<p>A new employee, not yet fully trained on ESD, removes an ESD-sensitive engine sensor from its packaging at a standard workbench (not in the EPA) and handles it for approximately 5 minutes while taking photographs for the customer. The employee was not wearing a wrist strap or foot grounders. The sensor visually appears undamaged and works correctly on a quick test. The customer is expecting it next week.</p>

<h4>Your task</h4>
<p>In 300–500 words, walk through your disposition decision:</p>
<ol>
  <li>What is the immediate risk to the sensor?</li>
  <li>Why does &ldquo;it works correctly on the quick test&rdquo; not eliminate the risk?</li>
  <li>What disposition options exist? (Ship as-is with disclosure, return to OEM for evaluation, scrap, or other?)</li>
  <li>How would you weigh customer relationship impact against ESD-risk-of-shipment?</li>
  <li>What incident records do you create regardless of disposition?</li>
  <li>What follow-up actions for the employee and the procedure?</li>
</ol>
<p>QA Manager review.</p>',
    ],
    'TWF4-8' => [
        'name' => 'Practical: Oxygen Generator Receiving With Missing Documentation',
        'intro' => '<h4>Scenario</h4>
<p>A shipment arrives at TurbineWorks. The supplier&apos;s packing slip lists &ldquo;oxygen mask drop assembly, P/N XYZ-123, quantity 2&rdquo;. You open the outer carton and find what appears to be two passenger oxygen masks with chemical oxygen generator canisters attached. The shipment includes no hazmat documentation. The supplier&apos;s packing slip is the only paperwork in the box.</p>

<h4>Your task</h4>
<p>In 300–500 words, walk through your full response:</p>
<ol>
  <li>What is the regulatory status of a chemical oxygen generator? What UN number and hazard class would apply?</li>
  <li>What hazmat documentation should have accompanied this shipment?</li>
  <li>What is your immediate physical handling response when you realize what is in the box? What do you NOT do?</li>
  <li>Where do you quarantine the shipment, and what marking do you apply?</li>
  <li>What supplier communication do you initiate, and within what timeframe?</li>
  <li>What incident record do you create — does this rise to the level of a supplier quality finding, an FAA SUP, or other regulatory reporting?</li>
  <li>Why is this scenario particularly serious from a flight-safety perspective? (Reference: ValuJet 592)</li>
</ol>
<p>QA Manager review. Pass mark: 70%.</p>',
    ],
];

foreach ($createdcourses as $idnumber => $course) {
    if (isset($assignments[$idnumber])) {
        twu_ensure_assignment($course, $assignments[$idnumber]);
    }
}
cli_writeln("[twu] practical assignments seeded in " . count(array_intersect_key($createdcourses, $assignments)) . " modules");

// ---------------------------------------------------------------------------
// 5e3. Custom Certificate — auto-issued PDF on Initial Training course completion
// ---------------------------------------------------------------------------
function twu_ensure_customcert(stdClass $course): ?int {
    global $DB, $CFG;
    require_once($CFG->dirroot . '/mod/customcert/locallib.php');

    $ismaster_pre = (!empty($course->idnumber) && $course->idnumber === 'TWF4-FINAL');
    if ($ismaster_pre) {
        $certname = 'Master Certification — ASA-100 Initial Training';
        $intro = '<p>The TurbineWorks University <strong>Master Certification</strong> in ASA-100 Initial Training. Issued automatically upon passing the cumulative final exam. Distinct from the per-module Certificates of Completion; this is the capstone credential.</p><p>The PDF carries a unique verification code and the public verification URL. Auditors and customer quality teams can verify authenticity in real time at that URL.</p>';
        // If a previous deploy created a standard "Certificate of Completion"
        // here, rename it in place so the master cert layout takes effect.
        $oldcert = $DB->get_record_sql(
            "SELECT cc.id FROM {customcert} cc
               JOIN {course_modules} cm ON cm.instance = cc.id
               JOIN {modules} m ON m.id = cm.module AND m.name = 'customcert'
              WHERE cm.course = :courseid AND cc.name = 'Certificate of Completion'",
            ['courseid' => $course->id]
        );
        if ($oldcert) {
            $DB->set_field('customcert', 'name', $certname, ['id' => $oldcert->id]);
            $DB->set_field('customcert', 'intro', $intro, ['id' => $oldcert->id]);
            cli_writeln("[twu]   renamed standard cert -> master cert in final exam course");
        }
    } else {
        $certname = 'Certificate of Completion';
        $intro = '<p>Your TurbineWorks University Certificate of Completion will be available here once you have finished all required activities in this module. The certificate is a branded PDF with a unique verification serial and may be used as audit evidence of training completion.</p>';
    }

    // Idempotency: skip if a customcert with this name already exists in the course.
    $existing = $DB->get_record_sql(
        "SELECT cm.id, cc.id AS certid, cm.id AS cmid
           FROM {course_modules} cm
           JOIN {modules} m ON m.id = cm.module AND m.name = 'customcert'
           JOIN {customcert} cc ON cc.id = cm.instance
          WHERE cm.course = :courseid AND cc.name = :name",
        ['courseid' => $course->id, 'name' => $certname]
    );
    if ($existing) {
        cli_writeln("[twu]   certificate exists for course $course->id (cmid={$existing->cmid})");
        return (int)$existing->cmid;
    }

    $moduleinfo = (object)[
        'modulename'        => 'customcert',
        'course'            => $course->id,
        'section'           => 0,
        'visible'           => 1,
        'visibleoncoursepage' => 1,
        'name'              => $certname,
        'intro'             => $intro,
        'introformat'       => FORMAT_HTML,
        'requiredtime'      => 0,
        'emailteachers'     => 0,
        'emailothers'       => '',
        'verifyany'         => 1,
        'protection_print'  => 0,
        'protection_modify' => 0,
        'protection_copy'   => 0,
        'deliveryoption'    => 0,
        'completion'        => 0,
    ];

    try {
        $cm = add_moduleinfo($moduleinfo, $course);
    } catch (Throwable $e) {
        cli_writeln("[twu]   could not create customcert: " . $e->getMessage());
        return null;
    }

    // Each customcert activity has its own template. Find it (auto-created
    // on activity creation by mod_customcert) or create one.
    $ctxid = context_module::instance($cm->coursemodule)->id;
    $template = $DB->get_record('customcert_templates', ['contextid' => $ctxid]);
    if (!$template) {
        $template = (object)[
            'name'         => $certname,
            'contextid'    => $ctxid,
            'timecreated'  => time(),
            'timemodified' => time(),
        ];
        $template->id = $DB->insert_record('customcert_templates', $template);
        // Link the template to the customcert instance.
        $DB->set_field('customcert', 'templateid', $template->id, ['id' => $cm->instance]);
    }

    // Default page: A4 landscape, 297 x 210 mm.
    $page = $DB->get_record('customcert_pages', ['templateid' => $template->id]);
    if (!$page) {
        $page = (object)[
            'templateid'  => $template->id,
            'width'       => 297,
            'height'      => 210,
            'leftmargin'  => 0,
            'rightmargin' => 0,
            'sequence'    => 1,
            'timecreated' => time(),
            'timemodified' => time(),
        ];
        $page->id = $DB->insert_record('customcert_pages', $page);
    }

    // Wipe any pre-existing elements so re-runs converge to the current layout.
    $DB->delete_records('customcert_elements', ['pageid' => $page->id]);

    // Determine if this is the cumulative-exam course → use the Master
    // variant of the cert layout (different wording, gold border, larger
    // recipient line).
    $ismaster = (!empty($course->idnumber) && $course->idnumber === 'TWF4-FINAL');

    $now = time();
    if ($ismaster) {
        // Master cert: distinct from per-module cert. Gold border, larger
        // recipient line, "MASTER CERTIFICATION" wording.
        $elements = [
            // Gold border around the whole page
            ['element' => 'border', 'name' => 'GoldBorder', 'data' => json_encode(['width' => 4]), 'font' => 'helvetica', 'fontsize' => 12, 'colour' => '#ffc800', 'posx' => 0, 'posy' => 0, 'width' => 297, 'alignment' => 'L', 'refpoint' => 0, 'sequence' => 0],
            ['element' => 'text', 'name' => 'Header',    'data' => json_encode(['text' => 'TurbineWorks University']),                                         'font' => 'helveticab', 'fontsize' => 32, 'colour' => '#0d2240', 'posx' => 148, 'posy' => 22, 'width' => 270, 'alignment' => 'C', 'refpoint' => 1, 'sequence' => 1],
            ['element' => 'text', 'name' => 'Subtitle',  'data' => json_encode(['text' => 'MASTER CERTIFICATION']),                                            'font' => 'helveticab', 'fontsize' => 22, 'colour' => '#ffc800', 'posx' => 148, 'posy' => 42, 'width' => 270, 'alignment' => 'C', 'refpoint' => 1, 'sequence' => 2],
            ['element' => 'text', 'name' => 'Subhead',   'data' => json_encode(['text' => 'ASA-100 Initial Training — Cumulative Certification']),             'font' => 'helvetica',  'fontsize' => 14, 'colour' => '#444444', 'posx' => 148, 'posy' => 58, 'width' => 270, 'alignment' => 'C', 'refpoint' => 1, 'sequence' => 3],
            ['element' => 'text', 'name' => 'GoldBar',   'data' => json_encode(['text' => '_____________________________________________']),                   'font' => 'helvetica',  'fontsize' => 16, 'colour' => '#ffc800', 'posx' => 148, 'posy' => 68, 'width' => 270, 'alignment' => 'C', 'refpoint' => 1, 'sequence' => 4],
            ['element' => 'text', 'name' => 'IntroLine', 'data' => json_encode(['text' => 'This certifies that']),                                             'font' => 'helvetica',  'fontsize' => 14, 'colour' => '#333333', 'posx' => 148, 'posy' => 82, 'width' => 270, 'alignment' => 'C', 'refpoint' => 1, 'sequence' => 5],
            ['element' => 'studentname', 'name' => 'Recipient', 'data' => '',                                                                                  'font' => 'helveticab', 'fontsize' => 32, 'colour' => '#0d2240', 'posx' => 148, 'posy' => 95, 'width' => 270, 'alignment' => 'C', 'refpoint' => 1, 'sequence' => 6],
            ['element' => 'text', 'name' => 'BodyLine1', 'data' => json_encode(['text' => 'has successfully completed all eight TWF-4 Initial Training modules']), 'font' => 'helvetica', 'fontsize' => 13, 'colour' => '#333333', 'posx' => 148, 'posy' => 128, 'width' => 270, 'alignment' => 'C', 'refpoint' => 1, 'sequence' => 7],
            ['element' => 'text', 'name' => 'BodyLine2', 'data' => json_encode(['text' => 'and passed the cumulative final exam at TurbineWorks University.']),'font' => 'helvetica', 'fontsize' => 13, 'colour' => '#333333', 'posx' => 148, 'posy' => 138, 'width' => 270, 'alignment' => 'C', 'refpoint' => 1, 'sequence' => 8],
            ['element' => 'text', 'name' => 'OnLabel',   'data' => json_encode(['text' => 'Issued']),                                                          'font' => 'helvetica',  'fontsize' => 11, 'colour' => '#666666', 'posx' => 148, 'posy' => 153, 'width' => 270, 'alignment' => 'C', 'refpoint' => 1, 'sequence' => 9],
            ['element' => 'date', 'name' => 'Date',      'data' => json_encode(['dateitem' => -1, 'dateformat' => 'F j, Y']),                                  'font' => 'helveticab', 'fontsize' => 14, 'colour' => '#0d2240', 'posx' => 148, 'posy' => 162, 'width' => 270, 'alignment' => 'C', 'refpoint' => 1, 'sequence' => 10],
            ['element' => 'text', 'name' => 'Validity',  'data' => json_encode(['text' => 'Valid for 180 days per TurbineWorks recurring training policy.']),  'font' => 'helvetica',  'fontsize' => 9, 'colour' => '#999999', 'posx' => 148, 'posy' => 174, 'width' => 270, 'alignment' => 'C', 'refpoint' => 1, 'sequence' => 11],
            ['element' => 'text', 'name' => 'VerifyLabel', 'data' => json_encode(['text' => 'Verify this certificate at: ' . (defined('CFG') ? $GLOBALS['CFG']->wwwroot : 'https://learn.turbineworks.com') . '/local/twu/verify.php']), 'font' => 'helvetica', 'fontsize' => 9, 'colour' => '#999999', 'posx' => 148, 'posy' => 188, 'width' => 270, 'alignment' => 'C', 'refpoint' => 1, 'sequence' => 12],
            ['element' => 'text', 'name' => 'CodeLabel', 'data' => json_encode(['text' => 'Code:']),                                                           'font' => 'helvetica',  'fontsize' => 10, 'colour' => '#999999', 'posx' => 130, 'posy' => 196, 'width' => 50, 'alignment' => 'R', 'refpoint' => 0, 'sequence' => 13],
            ['element' => 'code', 'name' => 'VerifyCode','data' => '',                                                                                         'font' => 'courier',    'fontsize' => 13, 'colour' => '#0d2240', 'posx' => 165, 'posy' => 196, 'width' => 80, 'alignment' => 'L', 'refpoint' => 0, 'sequence' => 14],
        ];
    } else {
        // Standard per-module cert layout.
        $verifyurl = (isset($GLOBALS['CFG']) ? $GLOBALS['CFG']->wwwroot : 'https://learn.turbineworks.com') . '/local/twu/verify.php';
        $elements = [
            ['element' => 'text',        'name' => 'Header',       'data' => json_encode(['text' => 'TurbineWorks University']),                              'font' => 'helveticab', 'fontsize' => 30, 'colour' => '#0d2240', 'posx' => 148, 'posy' => 28, 'width' => 250, 'alignment' => 'C', 'refpoint' => 1, 'sequence' => 1],
            ['element' => 'text',        'name' => 'Subtitle',     'data' => json_encode(['text' => 'Certificate of Completion']),                            'font' => 'helvetica',  'fontsize' => 18, 'colour' => '#ffc800', 'posx' => 148, 'posy' => 48, 'width' => 250, 'alignment' => 'C', 'refpoint' => 1, 'sequence' => 2],
            ['element' => 'text',        'name' => 'GoldBar',      'data' => json_encode(['text' => '_____________________________________________']),         'font' => 'helvetica',  'fontsize' => 16, 'colour' => '#ffc800', 'posx' => 148, 'posy' => 60, 'width' => 250, 'alignment' => 'C', 'refpoint' => 1, 'sequence' => 3],
            ['element' => 'text',        'name' => 'IntroLine',    'data' => json_encode(['text' => 'This is to certify that']),                              'font' => 'helvetica',  'fontsize' => 14, 'colour' => '#333333', 'posx' => 148, 'posy' => 78, 'width' => 250, 'alignment' => 'C', 'refpoint' => 1, 'sequence' => 4],
            ['element' => 'studentname', 'name' => 'Recipient',    'data' => '',                                                                              'font' => 'helveticab', 'fontsize' => 26, 'colour' => '#0d2240', 'posx' => 148, 'posy' => 92, 'width' => 250, 'alignment' => 'C', 'refpoint' => 1, 'sequence' => 5],
            ['element' => 'text',        'name' => 'CompletedLine','data' => json_encode(['text' => 'has successfully completed the ASA-100 training module']), 'font' => 'helvetica',  'fontsize' => 14, 'colour' => '#333333', 'posx' => 148, 'posy' => 118, 'width' => 250, 'alignment' => 'C', 'refpoint' => 1, 'sequence' => 6],
            ['element' => 'coursename',  'name' => 'CourseName',   'data' => '',                                                                              'font' => 'helveticab', 'fontsize' => 18, 'colour' => '#0d2240', 'posx' => 148, 'posy' => 133, 'width' => 270, 'alignment' => 'C', 'refpoint' => 1, 'sequence' => 7],
            ['element' => 'text',        'name' => 'On',           'data' => json_encode(['text' => 'on']),                                                   'font' => 'helvetica',  'fontsize' => 12, 'colour' => '#666666', 'posx' => 148, 'posy' => 153, 'width' => 250, 'alignment' => 'C', 'refpoint' => 1, 'sequence' => 8],
            ['element' => 'date',        'name' => 'Date',         'data' => json_encode(['dateitem' => -1, 'dateformat' => 'F j, Y']),                       'font' => 'helveticab', 'fontsize' => 14, 'colour' => '#0d2240', 'posx' => 148, 'posy' => 163, 'width' => 250, 'alignment' => 'C', 'refpoint' => 1, 'sequence' => 9],
            ['element' => 'text',        'name' => 'Validity',     'data' => json_encode(['text' => 'Valid for 180 days per TurbineWorks recurring training policy.']), 'font' => 'helvetica', 'fontsize' => 9, 'colour' => '#999999', 'posx' => 148, 'posy' => 177, 'width' => 250, 'alignment' => 'C', 'refpoint' => 1, 'sequence' => 10],
            ['element' => 'text',        'name' => 'VerifyLabel',  'data' => json_encode(['text' => 'Verify this certificate at: ' . $verifyurl]),            'font' => 'helvetica',  'fontsize' => 9, 'colour' => '#999999', 'posx' => 148, 'posy' => 186, 'width' => 250, 'alignment' => 'C', 'refpoint' => 1, 'sequence' => 11],
            ['element' => 'text',        'name' => 'CodeLabel',    'data' => json_encode(['text' => 'Code:']),                                                'font' => 'helvetica',  'fontsize' => 10, 'colour' => '#999999', 'posx' => 130, 'posy' => 195, 'width' => 50, 'alignment' => 'R', 'refpoint' => 0, 'sequence' => 12],
            ['element' => 'code',        'name' => 'VerifyCode',   'data' => '',                                                                              'font' => 'courier',    'fontsize' => 12, 'colour' => '#0d2240', 'posx' => 165, 'posy' => 195, 'width' => 80, 'alignment' => 'L', 'refpoint' => 0, 'sequence' => 13],
        ];
    }

    // Probe for the optional QR code element type (provided by the
    // customcertelement_qrcode plugin if installed). Adds a QR linking to
    // the verification page for instant phone-scan verification by auditors.
    // If the plugin is not installed, this is silently skipped.
    if (is_dir($CFG->dirroot . '/mod/customcert/element/qrcode')) {
        $verifyurl = $CFG->wwwroot . '/local/twu/verify.php';
        $elements[] = [
            'element'   => 'qrcode',
            'name'      => 'VerifyQR',
            'data'      => json_encode(['url' => $verifyurl, 'includecode' => 1]),
            'font'      => 'helvetica',
            'fontsize'  => 10,
            'colour'    => '#000000',
            'posx'      => $ismaster ? 260 : 260,
            'posy'      => $ismaster ? 165 : 158,
            'width'     => 30,
            'alignment' => 'L',
            'refpoint'  => 0,
            'sequence'  => 99,
        ];
    }

    foreach ($elements as $el) {
        $record = (object)$el;
        $record->pageid = $page->id;
        $record->timecreated = $now;
        $record->timemodified = $now;
        $DB->insert_record('customcert_elements', $record);
    }

    $label = $ismaster ? 'MASTER cert' : 'cert';
    cli_writeln("[twu]   created $label for course $course->id (cmid={$cm->coursemodule}, " . count($elements) . " elements)");
    return (int)$cm->coursemodule;
}

foreach ($createdcourses as $idnumber => $course) {
    twu_ensure_customcert($course);
}

// ---------------------------------------------------------------------------
// 5e4. Forum — "Ask the QA Manager" Q&A in each Initial Training course
// ---------------------------------------------------------------------------
function twu_ensure_forum(stdClass $course, string $name, string $intro): ?int {
    global $DB, $CFG;
    require_once($CFG->dirroot . '/mod/forum/lib.php');

    $existing = $DB->get_record_sql(
        "SELECT cm.id, f.id AS forumid
           FROM {course_modules} cm
           JOIN {modules} m ON m.id = cm.module AND m.name = 'forum'
           JOIN {forum} f ON f.id = cm.instance
          WHERE cm.course = :courseid AND f.name = :name",
        ['courseid' => $course->id, 'name' => $name]
    );
    if ($existing) {
        cli_writeln("[twu]   forum exists: $name (cmid={$existing->id})");
        return (int)$existing->forumid;
    }

    $moduleinfo = (object)[
        'modulename'          => 'forum',
        'course'              => $course->id,
        'section'             => 0,
        'visible'             => 1,
        'visibleoncoursepage' => 1,
        'name'                => $name,
        'intro'               => $intro,
        'introformat'         => FORMAT_HTML,
        'type'                => 'general',
        'assessed'            => 0,
        'scale'               => 0,
        'maxbytes'            => 0,
        'maxattachments'      => 3,
        'forcesubscribe'      => FORUM_FORCESUBSCRIBE,
        'trackingtype'        => FORUM_TRACKING_OPTIONAL,
        'completion'          => 0,
    ];

    try {
        $cm = add_moduleinfo($moduleinfo, $course);
        cli_writeln("[twu]   created forum: $name (cmid={$cm->coursemodule})");
        return (int)$cm->instance; // forum.id, not cm.id — needed by post seeder
    } catch (Throwable $e) {
        cli_writeln("[twu]   could not create forum: " . $e->getMessage());
        return null;
    }
}

// Seed a starter discussion in a forum if no discussion with this subject
// already exists. Posted by admin (acts as QA Manager until a real account
// is assigned).
function twu_ensure_forum_starter(stdClass $course, int $forumid, string $subject, string $message): void {
    global $DB, $CFG;
    require_once($CFG->dirroot . '/mod/forum/lib.php');

    // Idempotent per (forum, subject) — allows multiple seeded threads in
    // one forum (welcome + multiple FAQs) without re-inserting on reruns.
    if ($DB->record_exists('forum_discussions', ['forum' => $forumid, 'name' => $subject])) {
        return;
    }

    $forum = $DB->get_record('forum', ['id' => $forumid], '*', MUST_EXIST);
    $admin = get_admin();
    $now = time();

    $discussion = (object)[
        'course'       => $course->id,
        'forum'        => $forumid,
        'name'         => $subject,
        'firstpost'    => 0, // populated after post insert
        'userid'       => $admin->id,
        'groupid'      => -1, // no group
        'assessed'     => 0,
        'timemodified' => $now,
        'usermodified' => $admin->id,
        'timestart'    => 0,
        'timeend'      => 0,
        'pinned'       => 1, // pin the welcome thread
        'timelocked'   => 0,
    ];
    $discussionid = $DB->insert_record('forum_discussions', $discussion);

    $post = (object)[
        'discussion'  => $discussionid,
        'parent'      => 0,
        'userid'      => $admin->id,
        'created'     => $now,
        'modified'    => $now,
        'mailed'      => 0,
        'subject'     => $subject,
        'message'     => $message,
        'messageformat' => FORMAT_HTML,
        'messagetrust' => 0,
        'attachment'   => '',
        'totalscore'   => 0,
        'mailnow'      => 0,
        'deleted'      => 0,
        'privatereplyto' => 0,
        'wordcount'    => null,
        'charcount'    => null,
    ];
    $postid = $DB->insert_record('forum_posts', $post);

    // Backfill firstpost pointer.
    $DB->set_field('forum_discussions', 'firstpost', $postid, ['id' => $discussionid]);

    cli_writeln("[twu]   seeded forum starter: $subject (forum=$forumid, post=$postid)");
}

// "Ask the QA Manager" forum + starter discussion, one per Initial Training course.
$forumstarter_subject = 'Welcome — how this forum works';
$forumstarter_message = '<p>Welcome to this module&apos;s Q&amp;A forum. Use this space to ask questions about the module content, about how the standard applies to TurbineWorks operations, or about edge cases you have encountered in your work.</p>
<p><strong>How this forum is moderated:</strong></p>
<ul>
  <li>The QA Manager (or designate) reviews new posts and responds.</li>
  <li>Threads remain visible to all employees so common questions become permanent reference material.</li>
  <li>If a question reveals a gap in our procedures or training content, we will update the procedure or the training, and the thread becomes the audit trail of the change.</li>
</ul>
<p><strong>Good questions to ask here:</strong></p>
<ul>
  <li>&ldquo;The lesson says X — does that apply when we receive Y from supplier Z?&rdquo;</li>
  <li>&ldquo;I encountered situation A today; how should I document it?&rdquo;</li>
  <li>&ldquo;What is the difference between term P and term Q in practice?&rdquo;</li>
  <li>&ldquo;The SOP step is unclear — can someone walk through it with an example?&rdquo;</li>
</ul>
<p><strong>Not for here:</strong> emergencies (call/Slack the QA Manager directly), confidential customer or supplier discussions (use the appropriate channel), personnel issues (HR).</p>
<p>Post freely. If you are unsure whether a question is &ldquo;dumb&rdquo;, post it anyway — chances are someone else is wondering the same thing, and the answer benefits the whole team.</p>
<p>&mdash; QA Manager</p>';

// Per-module FAQ thread seeds — five common questions per module with the
// QA Manager's expected answer. Pre-seeds reference material so forums are
// useful from day 1 rather than empty until first user question.
$forum_faqs = [
    'TWF4-1' => [
        ['Q: A part shows up at receiving with a photocopied 8130-3. Should I accept it?',
         'A: A photocopy without a "TRUE COPY" stamp from an authorized organization is not a valid release document. Quarantine the part, document the receipt (photographs, who delivered, original packaging condition), and request the original from the supplier. If the supplier cannot produce an original, this is a SUP situation and you should escalate to me before doing anything else with the part. Do not return the part to the shipper — that just moves the SUP somewhere else.'],
        ['Q: What if I find what looks like a counterfeit part but I am not 100% sure?',
         'A: "Suspect" is the right word. Quarantine the part immediately. Document what made you suspect it (photographs of part markings, comparison to known-good examples, any documentation discrepancies). Bring it to me. We will investigate together. The cost of pausing for 30 minutes is much less than the cost of an undetected counterfeit reaching a customer.'],
        ['Q: I noticed a small discrepancy on an 8130-3 but the part itself looks fine. Should I still flag it?',
         'A: Yes — always. Discrepancies, even small ones, get documented. Many SUP investigations have started with what initially seemed like a "small" paperwork issue. Document the discrepancy on the receiving inspection report and have me review before accepting.'],
        ['Q: We received a part from a supplier we use regularly and have never had problems with. Do I still need to do the full receiving inspection?',
         'A: Yes. Familiarity with a supplier does not change the receiving inspection requirement. ASA-100 expects every part to receive the same inspection regardless of source. A "trusted supplier" assumption is exactly the cultural opening that lets bad parts through eventually.'],
        ['Q: What is the difference between an "unapproved" part and a "counterfeit" part?',
         'A: An unapproved part is one that does not meet its certification basis — could be wrong materials, wrong manufacture process, expired status, missing documentation. A counterfeit part is specifically one that was made to deceive — re-stamped data plate, fake serial number, intentional misrepresentation. All counterfeits are unapproved. Not all unapproved parts are counterfeit (some are honest errors). Either way, both get quarantined and investigated.'],
    ],
    'TWF4-2' => [
        ['Q: The carton arrived crushed but the part inside looks fine. Do I still note the carton damage?',
         'A: Yes. Document the carton damage at receiving (photographs, notes on the receiving record). The damage may have caused latent issues with the part that are not visually obvious — especially for ESD-sensitive or precision-machined parts. The supplier and carrier need this documentation if any future claim arises. Never discard the original packaging until receiving is complete.'],
        ['Q: An 8130-3 came in with the right-side installer blocks already filled in. Is this a problem?',
         'A: Yes — a major one. The right side is reserved for the installing mechanic when the part is installed on an aircraft. If they are filled in on a part shipped as new or repaired, it means the part may have been installed previously and removed. Quarantine and investigate the history. Could be a SUP indicator.'],
        ['Q: We get parts shipped on Saturday but I am not in the warehouse. Can someone else just sign for them?',
         'A: A non-trained employee can take physical delivery (sign the carrier paperwork) but cannot perform the receiving inspection. The parts go to a designated holding area until a trained inspector performs the inspection. They do not enter serviceable inventory until inspection is complete.'],
        ['Q: I do not have all the documentation listed in the PO — should I reject the shipment?',
         'A: Do not reject outright. Document the missing items, quarantine the parts, and contact the supplier for the missing documentation. Most cases are simple — the supplier forgot to include something. Reject only if the supplier cannot or will not provide what is required and the discrepancy is material.'],
        ['Q: How long should the receiving inspection take per part?',
         'A: Depends on the part. A box of 100 standard hardware items may take 5 minutes total. A single high-value rotable (HPT disk, FADEC) may take 30-60 minutes if records verification is involved. Do not rush. The records verification step is what catches the issues that matter.'],
    ],
    'TWF4-3' => [
        ['Q: How is ASA-100 different from AS9120?',
         'A: ASA-100 is published by ASA specifically for aircraft parts distributors. AS9120 is published by SAE/IAQG for the broader aerospace QMS context. Both are valid; ASA-100 is more aviation-distribution-specific. Many distributors hold both. We are pursuing ASA-100 first as the foundational accreditation. AS9120 may follow in 1-2 years.'],
        ['Q: Where do I find the current version of the ASA-100 standard?',
         'A: Through ASA membership at aviationsuppliers.org. The standard is not free — it is a controlled industry document. The current revision number is something the QA Manager confirms with ASA before any audit. We have a current copy on file in the QMS.'],
        ['Q: What is "voluntary accreditation"? Is it actually required?',
         'A: Technically voluntary — the FAA does not legally require ASA-100. In practice, many customers (major airlines, leasing companies, MRO networks) will not buy from unaccredited distributors. Accreditation is the practical entry credential. "Voluntary" in name; commercially required in practice.'],
        ['Q: What does an ASA audit actually look like?',
         'A: A trained ASA auditor visits the facility for 1-3 days. They review the QAM, walk the facility, interview employees, sample inventory and trace records, review training records, observe receiving and shipping operations. Findings are issued at exit. Corrective actions follow within defined timeframes. Successful audit = accreditation status maintained or granted.'],
    ],
    'TWF4-4' => [
        ['Q: We do not have a formal ESD area yet — where should I put ESD-sensitive parts?',
         'A: Until the EPA is built out, ESD-sensitive parts stay in their original ESD-safe packaging in a designated section of the warehouse. Do not open the packaging outside an EPA. If you need to inspect an ESDS part, coordinate with me so we can do it under correct ESD controls. This is a real gap we are working on.'],
        ['Q: How strict is "FIFO" enforcement for shelf-life items?',
         'A: First-in-first-out is the requirement for shelf-life-limited items — older stock ships first. Strict enforcement: if you find newer stock in front of older stock, rotate it. Items that exceed shelf life cannot be sold for installation; they are either scrap or revert to expired-stock disposition per the QMS.'],
        ['Q: What is the difference between FOD and "loose debris"?',
         'A: FOD (Foreign Object Damage / Debris) is specifically debris that could enter an engine or aircraft system. Loose hardware on the warehouse floor near a part is FOD risk. Loose hardware in a sealed shipping carton is just sloppy packing — still a quality issue, but not an FOD finding. The distinction matters at audit.'],
    ],
    'TWF4-5' => [
        ['Q: How long do we retain training records?',
         'A: Per ASA-100 expectations, training records are retained for 7+ years from training completion. Our Moodle system is configured to retain log data indefinitely. Certificates issued are also stored in the system. If you need a record for an external audit or customer inquiry, contact me.'],
        ['Q: I made an error on a receiving record. Can I correct it after the fact?',
         'A: Yes — but with discipline. Strike through the original entry (single line so the original remains readable), write the correction next to it, initial and date the correction. Do not use whiteout. Do not erase. The audit trail must show what was originally entered and what was changed. This is records-discipline 101.'],
        ['Q: A customer is asking for the back-to-birth records on an LLP we are about to ship. What do I send?',
         'A: The complete LLP records package: manufacturer\'s original release, all 8130-3s (original and subsequent releases), installation history, cycle accumulation records, shop visit history, SB compliance, AD compliance, current 8130-3. Copies are acceptable for customer use. Originals stay with the part until installed on an aircraft.'],
    ],
    'TWF4-6' => [
        ['Q: What is the relationship between AC 00-56 and ASA-100?',
         'A: AC 00-56 is the FAA Advisory Circular that establishes the framework for voluntary industry distributor accreditation. It defines what accreditation programs the FAA recognizes. ASA-100 is one such accreditation program — published by ASA, which is itself an FAA-recognized Accreditation Organization (AO) under AC 00-56. So the chain is: FAA → AC 00-56 → ASA (AO) → ASA-100 (standard) → TurbineWorks (accredited distributor).'],
        ['Q: Does our QAM need to literally reference AC 00-56?',
         'A: No — but it must reflect AC 00-56 requirements via ASA-100. AC 00-56 sets requirements on the AO (ASA); ASA-100 sets requirements on the distributor (us); the QAM defines how TurbineWorks satisfies ASA-100. We do not need to cite AC 00-56 line-by-line in the QAM, but the requirements flow through.'],
        ['Q: What happens if we lose accreditation?',
         'A: Customer contracts that require ASA-100 accreditation immediately become non-compliant. Affected customers must be notified. Lost revenue from those customers can be substantial. Recovery requires resubmitting accreditation application — a multi-month process and not guaranteed. This is why the QMS and the training program matter — they keep us audit-ready.'],
    ],
    'TWF4-7' => [
        ['Q: How do I know if a part is ESD-sensitive without opening the packaging?',
         'A: Three sources: (1) the supplier packaging — ESD-safe packaging is marked with the ESD susceptibility symbol; (2) the part-master record in our system — we flag known-ESDS part numbers; (3) part-type knowledge — FADECs, ECUs, sensors with electronic outputs, any printed circuit board, are always ESDS. When uncertain, treat as ESDS.'],
        ['Q: I forgot to put on my wrist strap before picking up an ESDS part. What do I do?',
         'A: Stop. Set the part back down. Put on the wrist strap. Re-inspect the part for any visible damage (there usually is none — that\'s the latent damage problem). Report the incident on the ESD log so we can track frequency and improve the procedure if needed. Most importantly: tell me. If the part is high-value or destined for a critical customer, we may decide to send it for testing rather than ship as serviceable.'],
        ['Q: Do all ESD-safe bags last forever?',
         'A: No. Pink antistatic bags lose their antistatic property within 6-12 months. Metallized shielded bags last longer but degrade with repeated handling. Visibly damaged bags get replaced. We track packaging age in the inventory system and rotate as needed.'],
        ['Q: What humidity should the ESD area be at?',
         'A: Target 40-60% relative humidity. Below 30% RH, enhanced grounding verification applies and Class 0 component handling may be suspended until humidity is restored. Above 70% RH starts to risk moisture-related issues. We monitor continuously.'],
    ],
    'TWF4-8' => [
        ['Q: A part arrived without hazmat documentation, but I know from the part type it should be hazmat. What do I do?',
         'A: Quarantine the part. Document the missing documentation as a supplier-quality issue. Contact the supplier for the missing paperwork. Do not accept the part into inventory and do not ship it as non-hazmat. If the supplier cannot or will not provide proper hazmat documentation, the shipment may need to be returned. Document everything for the supplier quality record.'],
        ['Q: We need to ship a lithium battery — what training is required?',
         'A: For air shipment of lithium batteries (UN3480/3481), the signer of the Shipper\'s Declaration must have current IATA DGR training (initial plus recurrent every 24 months). Without DGR training, you cannot sign a DGD. Ground shipments may be done under broader DOT hazmat training. We have one DGD signer designated; if they are unavailable, the shipment waits.'],
        ['Q: What is the difference between UN3480 and UN3481?',
         'A: UN3480 = standalone lithium-ion batteries (in a box, not connected to anything). UN3481 = lithium-ion batteries packed with equipment or contained in equipment. Different packing instructions, different state-of-charge limits, different documentation. The choice depends on how the battery is shipped — alone, accompanying equipment, or installed in equipment.'],
        ['Q: I think we received an oxygen generator with no hazmat documentation. How serious is this?',
         'A: Very serious. Oxygen generators are UN3356 / Class 5.1 oxidizer. They caused the ValuJet 592 crash in 1996. Quarantine immediately, do not move or jostle the part, get me involved. We will need supplier documentation and possibly OEM disposition guidance.'],
    ],
];

foreach ($createdcourses as $idnumber => $course) {
    $forumid = twu_ensure_forum(
        $course,
        'Ask the QA Manager',
        '<p>Post questions about the module content or how it applies to TurbineWorks procedures. The QA Manager (or designate) will respond. Threads stay visible to all employees so common questions become reference material.</p>'
    );
    if ($forumid) {
        twu_ensure_forum_starter($course, $forumid, $forumstarter_subject, $forumstarter_message);
        // Seed FAQ threads for this module if defined.
        $faqs = $forum_faqs[$idnumber] ?? [];
        foreach ($faqs as $faq) {
            twu_ensure_forum_starter($course, $forumid, $faq[0], '<p>' . str_replace("\n", '</p><p>', $faq[1]) . '</p>');
        }
        if ($faqs) {
            cli_writeln("[twu]   seeded " . count($faqs) . " FAQ threads in $idnumber forum");
        }
    }
}

// ---------------------------------------------------------------------------
// 5c. Reference Library courses + lessons (fills the empty Reference category)
// ---------------------------------------------------------------------------
foreach (local_twu_get_reference_library() as $coursedata) {
    $lessons = $coursedata['lessons'] ?? [];
    unset($coursedata['lessons']);
    $course = twu_ensure_course($refcat->id, $coursedata);
    cli_writeln("[twu] seeding " . count($lessons) . " reference pages in {$coursedata['shortname']}");
    foreach ($lessons as $lesson) {
        twu_ensure_page_lesson($course, 0, $lesson['name'], $lesson['intro'], $lesson['content']);
    }
}

// ---------------------------------------------------------------------------
// 5d. Engine-Parts Specific courses + lessons
// ---------------------------------------------------------------------------
$enginecourses = [];
$opscourses    = [];
foreach (local_twu_get_engine_parts_courses() as $coursedata) {
    $lessons = $coursedata['lessons'] ?? [];
    unset($coursedata['lessons']);
    $course = twu_ensure_course($enginecat->id, $coursedata);
    cli_writeln("[twu] seeding " . count($lessons) . " engine-parts lessons in {$coursedata['shortname']}");
    foreach ($lessons as $lesson) {
        twu_ensure_page_lesson($course, 0, $lesson['name'], $lesson['intro'], $lesson['content']);
    }
    // Distinguish engine-model courses from operations courses by shortname
    // prefix. TWU-OPS-* are operations; everything else under enginecat is
    // engine-parts technical content.
    if (strncmp($coursedata['shortname'], 'TWU-OPS-', 8) === 0) {
        $opscourses[$coursedata['shortname']] = $course;
    } else {
        $enginecourses[$coursedata['shortname']] = $course;
    }
}

// Cohort sync — role-based auto-enrolment into supplemental courses
// Engine-model + technical courses → QA and Management (deep product knowledge
// required for inspection and commercial decisions).
try {
    foreach ($enginecourses as $shortname => $course) {
        twu_ensure_cohort_sync($course, $cohort_qa);
        twu_ensure_cohort_sync($course, $cohort_management);
    }
    if (!empty($enginecourses)) {
        cli_writeln("[twu] cohort sync: QA + Management → " . count($enginecourses) . " engine/technical courses");
    }

    // Operations courses (AS9120, Customer Relations, Export Control) →
    // Management (always) and QA (always). Export Control specifically also →
    // Shipping/Receiving cohort.
    foreach ($opscourses as $shortname => $course) {
        twu_ensure_cohort_sync($course, $cohort_qa);
        twu_ensure_cohort_sync($course, $cohort_management);
        if ($shortname === 'TWU-OPS-INTSHIP') {
            twu_ensure_cohort_sync($course, $cohort_shipping);
        }
    }
    if (!empty($opscourses)) {
        cli_writeln("[twu] cohort sync: QA + Management → " . count($opscourses) . " operations courses");
    }
} catch (Throwable $e) {
    cli_writeln("[twu] WARN engine/ops cohort sync failed: " . $e->getMessage() . " — continuing.");
}

// ---------------------------------------------------------------------------
// 5e. Recurring Training skeleton course
// ---------------------------------------------------------------------------
foreach (local_twu_get_recurring_courses() as $coursedata) {
    $lessons = $coursedata['lessons'] ?? [];
    unset($coursedata['lessons']);
    $course = twu_ensure_course($recurringcat->id, $coursedata);
    cli_writeln("[twu] seeding " . count($lessons) . " recurring lessons in {$coursedata['shortname']}");
    foreach ($lessons as $lesson) {
        twu_ensure_page_lesson($course, 0, $lesson['name'], $lesson['intro'], $lesson['content']);
    }
}

// ---------------------------------------------------------------------------
// 5f. Aviation Terminology Glossary (in Reference Library)
// ---------------------------------------------------------------------------
function twu_ensure_glossary(stdClass $course, string $name, string $intro, array $entries): ?int {
    global $DB, $CFG;
    require_once($CFG->dirroot . '/mod/glossary/lib.php');

    // Idempotency: skip if a Glossary with this name already exists.
    $existing = $DB->get_record_sql(
        "SELECT cm.id, g.id AS glossaryid
           FROM {course_modules} cm
           JOIN {modules} m ON m.id = cm.module AND m.name = 'glossary'
           JOIN {glossary} g ON g.id = cm.instance
          WHERE cm.course = :courseid AND g.name = :name",
        ['courseid' => $course->id, 'name' => $name]
    );

    if ($existing) {
        $glossaryid = $existing->glossaryid;
        cli_writeln("[twu]   glossary exists: $name (cmid={$existing->id}); will add only missing entries");
    } else {
        $moduleinfo = (object)[
            'modulename'          => 'glossary',
            'course'              => $course->id,
            'section'             => 0,
            'visible'             => 1,
            'visibleoncoursepage' => 1,
            'name'                => $name,
            'intro'               => $intro,
            'introformat'         => FORMAT_HTML,
            'globalglossary'      => 1, // site-wide auto-link if enabled
            'mainglossary'        => 1,
            'defaultapproval'     => 1,
            'displayformat'       => 'dictionary',
            'showspecial'         => 1,
            'showalphabet'        => 1,
            'showall'             => 1,
            'allowduplicatedentries' => 0,
            'allowcomments'       => 0,
            'allowprintview'      => 1,
            'usedynalink'         => 1,
            'entbypage'           => 25,
            'rsstype'             => 0,
            'rssarticles'         => 0,
            'assessed'            => 0,
            'scale'               => 0,
            'editalways'          => 0,
            'completion'          => COMPLETION_TRACKING_AUTOMATIC,
            'completionview'      => 1,
        ];
        try {
            $cm = add_moduleinfo($moduleinfo, $course);
            $glossaryid = $cm->instance;
            cli_writeln("[twu]   created glossary: $name (cmid={$cm->coursemodule})");
        } catch (Throwable $e) {
            cli_writeln("[twu]   could not create glossary: " . $e->getMessage());
            return null;
        }
    }

    // Insert any missing entries (idempotent by concept).
    $now = time();
    $admin = get_admin();
    $added = 0;
    foreach ($entries as $entry) {
        $exists = $DB->record_exists('glossary_entries',
            ['glossaryid' => $glossaryid, 'concept' => $entry['concept']]);
        if ($exists) {
            continue;
        }
        $record = (object)[
            'glossaryid'      => $glossaryid,
            'userid'          => $admin->id,
            'concept'         => $entry['concept'],
            'definition'      => $entry['definition'],
            'definitionformat' => FORMAT_HTML,
            'definitiontrust' => 0,
            'attachment'      => '',
            'timecreated'     => $now,
            'timemodified'    => $now,
            'teacherentry'    => 1,
            'sourceglossaryid' => 0,
            'usedynalink'     => 1,
            'casesensitive'   => 0,
            'fullmatch'       => 0,
            'approved'        => 1,
        ];
        $DB->insert_record('glossary_entries', $record);
        $added++;
    }
    cli_writeln("[twu]   glossary $name: added $added new entries (" . count($entries) . " defined)");
    return null;
}

// Find or create the host course for the glossary inside Reference Library.
$glossarycoursedata = [
    'shortname' => 'TWU-REF-GLOSSARY',
    'fullname'  => 'Aviation Terminology &amp; ASA-100 Glossary',
    'idnumber'  => 'TWU-REF-GLOSSARY',
    'summary'   => '<p>Searchable glossary of aviation, ASA-100, and compliance terminology used throughout TurbineWorks University. Terms are auto-linked from any text on the site when the auto-link filter is enabled.</p>',
];
$glossarycourse = twu_ensure_course($refcat->id, $glossarycoursedata);
twu_ensure_glossary(
    $glossarycourse,
    'Aviation Terminology &amp; ASA-100 Glossary',
    '<p>Search or browse aviation parts distribution, ASA-100, FAA, ESD, hazmat, and engine-specific terminology. Click any term for its definition.</p>',
    local_twu_get_glossary_entries()
);

// ---------------------------------------------------------------------------
// 6. Theme: prefer Snap (modern, Pinterest-style cards). Fall back to Moove
//          if Snap is not installed. Override via TWU_THEME env var.
// ---------------------------------------------------------------------------
$preferredtheme = getenv('TWU_THEME') ?: 'snap';
if (!is_dir($CFG->dirroot . '/theme/' . $preferredtheme)) {
    cli_writeln("[twu] preferred theme '$preferredtheme' not installed; falling back to moove");
    $preferredtheme = 'moove';
}
if (get_config('core', 'theme') !== $preferredtheme) {
    set_config('theme', $preferredtheme);
    cli_writeln("[twu] activated theme: $preferredtheme");
} else {
    cli_writeln("[twu] theme already $preferredtheme; skipping");
}

// Snap-specific configuration (only applied if Snap is the active theme).
//
// Snap setting names (verified against MR-4.5 settings/*.php):
//   themecolor       — primary brand color (purple by default)
//   customisenavbar  — MUST be 1 before navbarbg / navbarlink apply
//   navbarbg         — navbar background color
//   navbarlink       — navbar text/link color
//   customisenavbutton + navbarbuttoncolor + navbarbuttonlink — nav button colors
//   footnote         — HTML replacing the default footer text ("Built with Open LMS")
//   footerbg         — footer background color
//   footertxt        — footer text color
//   subtitle         — short site tagline
//   customcss        — site-wide custom CSS injected at theme load
if ($preferredtheme === 'snap') {
    set_config('themecolor', '#0d2240', 'theme_snap');

    // Navbar customization MUST be enabled before color settings take effect.
    set_config('customisenavbar', 1, 'theme_snap');
    set_config('navbarbg',   '#0d2240', 'theme_snap');
    set_config('navbarlink', '#ffffff', 'theme_snap');

    set_config('customisenavbutton',  1, 'theme_snap');
    set_config('navbarbuttoncolor', '#ffc800', 'theme_snap');
    set_config('navbarbuttonlink',  '#0d2240', 'theme_snap');

    set_config('subtitle', 'ASA-100 Compliance & Engine-Parts Technical Training', 'theme_snap');

    // Footer: override the "Built with Open LMS, a Moodle-based product"
    // attribution with TurbineWorks footer content. Color it navy/gold.
    set_config('footnote',
        '<div style="text-align:center;">'
        . '<p style="margin:0.5em 0;"><strong>TurbineWorks University</strong> &mdash; ASA-100 Compliance Training</p>'
        . '<p style="margin:0.5em 0; font-size:0.85em; opacity:0.8;">For training records or audit-related inquiries, contact <a href="mailto:quality@turbineworks.com" style="color:#ffc800;">quality@turbineworks.com</a></p>'
        . '<p style="margin:0.5em 0; font-size:0.75em; opacity:0.6;">&copy; ' . date('Y') . ' TurbineWorks Aircraft Engine Parts &amp; Components</p>'
        . '</div>',
        'theme_snap');
    set_config('footerbg',  '#0d2240', 'theme_snap');
    set_config('footertxt', '#ffffff', 'theme_snap');

    // Custom CSS — additional polish that doesn't fit a setting:
    // - hide the "Built with Open LMS, a Moodle-based product" line
    //   (rendered outside the footnote area in some Snap layouts)
    // - hide the "Copyright 2026 Open LMS, All Rights Reserved" line
    // - hide any element with "powered-by" / "themecredits" classes
    set_config('customcss', <<<CSS
/* Hide Open LMS / theme attribution rendered outside footnote */
.themecredits,
.theme-credits,
[class*="poweredby"],
[class*="powered-by"],
[class*="builtwith"],
.snap-footer-poweredby,
#snap-page-footer-copyright,
.snap-credits {
    display: none !important;
}
/* Hide any footer paragraph mentioning Open LMS explicitly */
footer p:has-text("Open LMS"),
footer p:has-text("Built with") { display: none !important; }
CSS,
    'theme_snap');

    // Snap logo: belt-and-suspenders.
    // Upload to BOTH core_admin (site-wide) AND theme_snap (theme-specific)
    // logo + favicon fileareas. Different parts of Snap render from different
    // sources; uploading to all four guarantees the logo shows up in the
    // header, login page, and browser tab.
    $logosrc = $CFG->dirroot . '/local/twu/assets/logo.png';
    $favsrc  = $CFG->dirroot . '/local/twu/assets/favicon.png';
    if (file_exists($logosrc)) {
        $fs = get_file_storage();
        $syscontext = context_system::instance();
        $uploads = [
            // [component, filearea, source-file, target-filename]
            ['core_admin', 'logo',       $logosrc, 'logo.png'],
            ['core_admin', 'favicon',    $favsrc,  'favicon.png'],
            ['theme_snap', 'logo',       $logosrc, 'logo.png'],
            ['theme_snap', 'favicon',    $favsrc,  'favicon.png'],
            ['theme_snap', 'loginlogo',  $logosrc, 'logo.png'],
            ['theme_snap', 'loginheaderlogo', $logosrc, 'logo.png'],
        ];
        foreach ($uploads as [$component, $filearea, $src, $filename]) {
            if (!file_exists($src)) {
                continue;
            }
            $fs->delete_area_files($syscontext->id, $component, $filearea);
            try {
                $fs->create_file_from_pathname([
                    'contextid' => $syscontext->id,
                    'component' => $component,
                    'filearea'  => $filearea,
                    'itemid'    => 0,
                    'filepath'  => '/',
                    'filename'  => $filename,
                ], $src);
                cli_writeln("[twu] uploaded $filename to $component/$filearea");
            } catch (Exception $e) {
                cli_writeln("[twu] could not upload to $component/$filearea: " . $e->getMessage());
            }
        }
    } else {
        cli_writeln("[twu] no logo file at $logosrc; skipping all logo uploads");
    }

    // Force Snap to render the logo (some Snap versions gate this).
    set_config('logodisplay', 1, 'theme_snap');

    // Bump theme revision so browsers re-fetch theme assets immediately.
    set_config('themerev', time(), 'core');

    theme_reset_all_caches();
    cli_writeln("[twu] purged theme caches and bumped themerev (Snap active)");
}

// Keep the Moove configuration block below intact — it's a no-op when Snap is
// active but lets you fall back to Moove (set TWU_THEME=moove env var) without
// re-running this bootstrap.
$themedir = $CFG->dirroot . '/theme/moove';
if (is_dir($themedir)) {
    // (Moove config follows — only applies if Moove is the active theme)

    // Brand colors pulled from turbineworks.com:
    //   primary  = navy/blue header tone seen on the booklet
    //   gold     = #ffc800 accent (matches the booklet underline)
    set_config('brandcolor', '#0d2240', 'theme_moove');
    cli_writeln("[twu] set Moove brandcolor = #0d2240 (TurbineWorks navy)");

    // Moove's "secondary" colors live under several keys depending on the
    // theme version; set the common ones harmlessly.
    foreach (['secondarycolor' => '#ffc800', 'navbarbg' => '#0d2240', 'navbarcolor' => '#ffffff'] as $key => $val) {
        set_config($key, $val, 'theme_moove');
    }
    cli_writeln("[twu] set Moove secondary/navbar colors (gold accent, navy nav)");

    // Strip the orange "This theme was proudly developed by conecti.me" footer
    // bar by hiding the link and its containing block via custom CSS.
    // Hidden three ways for resilience across Moove versions:
    //   1. direct link selector
    //   2. parent-of-link via :has() (modern browsers)
    //   3. common wrapper classes Moove uses for theme credits
    $customcss = <<<CSS
/* === TurbineWorks University: strip third-party theme credits === */
a[href*="conecti.me"],
a[href*="conecti.me"] img,
*:has(> a[href*="conecti.me"]),
.theme-credits,
.theme-credit,
.theme-footer-credit,
.bg-warning:has(a[href*="conecti.me"]),
.moove-footer-credits {
    display: none !important;
    visibility: hidden !important;
    height: 0 !important;
    padding: 0 !important;
    margin: 0 !important;
    overflow: hidden !important;
}
CSS;
    set_config('customcss', $customcss, 'theme_moove');
    cli_writeln("[twu] injected customcss to hide conecti.me footer block");

    // Upload the official TurbineWorks logo + favicon into Moove.
    //   logo.png       — converted from turbineworks.com/assets/img/logos/logo.webp
    //   favicon.png    — 256x256 square derived from same source
    $assetbase = $CFG->dirroot . '/local/twu/assets';
    $fs = get_file_storage();
    $syscontext = context_system::instance();
    $logouploads = [
        'logo'    => ['file' => 'logo.png',    'filename' => 'logo.png'],
        'favicon' => ['file' => 'favicon.png', 'filename' => 'favicon.png'],
    ];
    foreach ($logouploads as $filearea => $info) {
        $src = $assetbase . '/' . $info['file'];
        if (!file_exists($src)) {
            cli_writeln("[twu] no $filearea file at $src; skipping");
            continue;
        }
        $fs->delete_area_files($syscontext->id, 'theme_moove', $filearea);
        try {
            $fs->create_file_from_pathname([
                'contextid' => $syscontext->id,
                'component' => 'theme_moove',
                'filearea'  => $filearea,
                'itemid'    => 0,
                'filepath'  => '/',
                'filename'  => $info['filename'],
            ], $src);
            cli_writeln("[twu] uploaded {$info['filename']} to theme_moove/$filearea");
        } catch (Exception $e) {
            cli_writeln("[twu] could not upload to $filearea: " . $e->getMessage());
        }
    }

    // Moove frontpage settings — fills the "Welcome to TurbineWorks University"
    // hero, About section, and marketing tiles so logged-out visitors see a
    // real portal, not a stock Moodle homepage with a course list.
    set_config('headerimg', '', 'theme_moove'); // use site logo, not a separate banner image
    set_config('bannercontent', '<h1 style="color:#fff;">TurbineWorks University</h1><p style="color:#ffc800; font-size:1.2em;">ASA-100 Compliance &amp; Engine-Parts Technical Training</p>', 'theme_moove');
    set_config('bannercontentlocation', 'middle', 'theme_moove');
    set_config('displaymarketingbox', 1, 'theme_moove');
    set_config('marketingheading', 'What you can do here', 'theme_moove');
    set_config('marketingcontent', '<p>TurbineWorks University delivers the ASA-100 quality training program required for all employees touching aircraft parts. Browse the categories below to find your assigned training or explore the reference library.</p>', 'theme_moove');

    // 4 marketing tiles → the 4 categories
    $tiles = [
        1 => ['icon' => 'fa-graduation-cap', 'heading' => 'ASA-100 Initial Training', 'content' => 'New-hire training across 8 modules: receiving inspection, SUP detection, recordkeeping, hazmat, ESD, and more.'],
        2 => ['icon' => 'fa-rotate-right',   'heading' => '6-Month Recurring Training',  'content' => 'Refresher training every 6 months for all employees. Auto-enrolled 30 days before your last completion expires.'],
        3 => ['icon' => 'fa-book',           'heading' => 'Reference Library',           'content' => 'Direct access to FAA Advisory Circulars, ASA-100, ANSI/ESD S20.20, IATA DGR, and other source documents.'],
        4 => ['icon' => 'fa-gears',          'heading' => 'Engine-Parts Specific',       'content' => 'Deep-dive training on FAA 8130-3 tag inspection, Life Limited Parts tracking, and OEM service bulletins.'],
    ];
    foreach ($tiles as $n => $tile) {
        set_config("marketing{$n}icon",    $tile['icon'],    'theme_moove');
        set_config("marketing{$n}heading", $tile['heading'], 'theme_moove');
        set_config("marketing{$n}content", $tile['content'], 'theme_moove');
        set_config("marketing{$n}url",     $CFG->wwwroot . '/course/index.php', 'theme_moove');
    }
    cli_writeln("[twu] configured Moove frontpage: banner, marketing box, 4 category tiles");

    // Bust theme caches so the CSS/logo/frontpage changes apply immediately.
    theme_reset_all_caches();
    cli_writeln("[twu] purged theme caches");
} else {
    cli_writeln("[twu] theme_moove directory missing; skipping theme config");
}

// ---------------------------------------------------------------------------
// 6b. Demo seed users — one per role cohort, suspended by default
// ---------------------------------------------------------------------------
// These accounts demonstrate the cohort model but cannot log in (suspended=1)
// until an administrator activates them. The username/email pattern uses
// example.com so they are unmistakably placeholders. Real employee accounts
// should be created via Moodle's admin UI (or bulk-uploaded) and added to
// the appropriate cohorts.
function twu_ensure_demo_user(string $username, string $firstname, string $lastname,
                              string $email, int $cohortid, string $description): void {
    global $DB, $CFG;
    require_once($CFG->dirroot . '/user/lib.php');
    require_once($CFG->dirroot . '/cohort/lib.php');

    $user = $DB->get_record('user', ['username' => $username]);
    if (!$user) {
        $user = (object)[
            'auth'         => 'manual',
            'confirmed'    => 1,
            'mnethostid'   => $CFG->mnet_localhost_id,
            'username'     => $username,
            'password'     => hash_internal_user_password('!disabled' . random_string(16)),
            'firstname'    => $firstname,
            'lastname'     => $lastname,
            'email'        => $email,
            'maildisplay'  => 0,
            'mailformat'   => 1,
            'lang'         => $CFG->lang,
            'timezone'     => $CFG->timezone,
            'description'  => $description,
            'descriptionformat' => FORMAT_HTML,
            'city'         => '',
            'country'      => 'US',
            'suspended'    => 1, // disabled until admin activates
            'timecreated'  => time(),
            'timemodified' => time(),
        ];
        $user->id = user_create_user($user, false, false);
        cli_writeln("[twu] demo user created (suspended): $username");
    }

    // Ensure cohort membership.
    if (!$DB->record_exists('cohort_members', ['cohortid' => $cohortid, 'userid' => $user->id])) {
        cohort_add_member($cohortid, $user->id);
    }
    // Also always in All Employees baseline.
    if (isset($GLOBALS['twu_cohort_all'])
        && !$DB->record_exists('cohort_members', ['cohortid' => $GLOBALS['twu_cohort_all'], 'userid' => $user->id])) {
        cohort_add_member($GLOBALS['twu_cohort_all'], $user->id);
    }
}

// Make the All Employees cohort id available to the helper without re-querying.
$GLOBALS['twu_cohort_all'] = $cohort_all;

try {
twu_ensure_demo_user(
    'demo.warehouse',
    'Demo',
    'Warehouse Operator',
    'demo-warehouse@example.com',
    $cohort_warehouse,
    '<p>Demo / template user for the Warehouse Operator role. Suspended by default; activate via Site Admin to use as a real account, or create a fresh user and copy this account&apos;s cohort memberships.</p>'
);
twu_ensure_demo_user(
    'demo.qa',
    'Demo',
    'QA Inspector',
    'demo-qa@example.com',
    $cohort_qa,
    '<p>Demo / template user for the Quality Assurance role. Enrolled in all Initial Training plus engine-model + operations courses via the QA cohort.</p>'
);
twu_ensure_demo_user(
    'demo.shipping',
    'Demo',
    'Shipping Receiving',
    'demo-shipping@example.com',
    $cohort_shipping,
    '<p>Demo / template user for the Shipping &amp; Receiving role. Emphasis on Export Control / ITAR / EAR alongside Initial Training.</p>'
);
twu_ensure_demo_user(
    'demo.manager',
    'Demo',
    'Management',
    'demo-manager@example.com',
    $cohort_management,
    '<p>Demo / template user for the Management role. Full curriculum including operations courses.</p>'
);

cli_writeln("[twu] 4 demo template users created (suspended; in role + All Employees cohorts)");
} catch (Throwable $e) {
    cli_writeln("[twu] WARN demo users section failed: " . $e->getMessage() . " — continuing.");
}

// ---------------------------------------------------------------------------
// 6b2. Site calendar events — recurring compliance and training milestones
// ---------------------------------------------------------------------------
// Seeds a small set of recurring site-wide calendar events visible in the
// Moodle calendar block on the dashboard. Helps employees see upcoming
// compliance deadlines without separate tracking.
function twu_ensure_site_event(string $name, string $description, int $timestart,
                               int $duration, string $eventtype, ?int $repeatuntil = null): void {
    global $DB, $CFG;
    require_once($CFG->dirroot . '/calendar/lib.php');

    // Idempotent on name + eventtype + timestart-day so reruns do not duplicate.
    $daystart = strtotime(date('Y-m-d', $timestart));
    $dayend   = $daystart + DAYSECS;
    $existing = $DB->record_exists_sql(
        "SELECT 1 FROM {event}
          WHERE name = :name AND eventtype = :type
            AND timestart >= :ds AND timestart < :de",
        ['name' => $name, 'type' => $eventtype, 'ds' => $daystart, 'de' => $dayend]
    );
    if ($existing) {
        return;
    }

    $admin = get_admin();
    $event = (object)[
        'name'          => $name,
        'description'   => $description,
        'format'        => FORMAT_HTML,
        'courseid'      => SITEID,
        'groupid'       => 0,
        'userid'        => $admin->id,
        'modulename'    => 0,
        'instance'      => 0,
        'eventtype'     => $eventtype,
        'type'          => 0,
        'timestart'     => $timestart,
        'timeduration'  => $duration,
        'visible'       => 1,
        'sequence'      => 1,
        'timemodified'  => time(),
        'priority'      => null,
    ];
    \calendar_event::create($event, false);
}

// Calendar events: management review (quarterly), audit prep (annual),
// recurring training reminders, ASA accreditation milestones.
try {
$now = time();
$thisyear = (int)date('Y');
$year = $thisyear;

// Quarterly management review meetings — Q1/Q2/Q3/Q4 of current and next year.
foreach ([$thisyear, $thisyear + 1] as $y) {
    foreach ([['03', 'Q1'], ['06', 'Q2'], ['09', 'Q3'], ['12', 'Q4']] as $qm) {
        $ts = strtotime("$y-{$qm[0]}-15 10:00:00");
        if ($ts && $ts > $now) {
            twu_ensure_site_event(
                "Quarterly Management Review ({$qm[1]} $y)",
                '<p>Quarterly management review meeting per ASA-100 management-review requirements. Agenda: training completion review, supplier quality, customer complaints, internal audit findings, CAPA status, resource needs. QA Manager facilitates; Accountable Manager chairs.</p>',
                $ts, 90 * 60, 'site'
            );
        }
    }
}

// Annual ASA-100 audit prep (~30 days before anniversary; placeholder Q1)
$audit_prep = strtotime("$thisyear-02-01 09:00:00");
if ($audit_prep && $audit_prep > $now) {
    twu_ensure_site_event(
        'Annual ASA Audit Preparation Window Opens',
        '<p>30-day audit preparation window opens. Checklist: training records current for all employees, internal audit complete, CAPA log reviewed, supplier qualification list current, all SOPs at current revision. QA Manager owns prep.</p>',
        $audit_prep, 30 * DAYSECS, 'site'
    );
}

// Annual hazmat recurrent training deadline (DOT — every 3 years per
// 49 CFR §172.704). Reminder placed annually as a "check status" event.
$hazmat_check = strtotime("$thisyear-04-01 09:00:00");
if ($hazmat_check && $hazmat_check > $now) {
    twu_ensure_site_event(
        'DOT Hazmat Certification Status Check',
        '<p>Annual review of DOT Hazmat certifications for all hazmat-handling personnel. Per 49 CFR §172.704 — initial training plus recurrent every 3 years. Check the Certification Expiry Roster (Site Admin → TurbineWorks Reports) for any expirations within 90 days.</p>',
        $hazmat_check, 60 * 60, 'site'
    );
}

// Bi-annual IATA DGR check (24-month renewal cycle)
$dgr_check = strtotime("$thisyear-05-01 09:00:00");
if ($dgr_check && $dgr_check > $now) {
    twu_ensure_site_event(
        'IATA DGR Air-Shipper Certification Status Check',
        '<p>Semi-annual review of IATA DGR certifications for personnel signing Shipper&apos;s Declarations. Per IATA DGR — initial training plus recurrent every 24 months. Check the Certification Expiry Roster.</p>',
        $dgr_check, 60 * 60, 'site'
    );
}

// 6-month recurring training cohort cycle reminders
$semi1 = strtotime("$thisyear-01-15 09:00:00");
$semi2 = strtotime("$thisyear-07-15 09:00:00");
foreach ([$semi1, $semi2] as $ts) {
    if ($ts && $ts > $now) {
        twu_ensure_site_event(
            '6-Month Recurring Training Window',
            '<p>The 6-month recurring training reset task has run. Employees whose completions have expired are now showing as incomplete in their courses. Run the Recurring Training Due Tracker report to see status.</p>',
            $ts, DAYSECS, 'site'
        );
    }
}

cli_writeln("[twu] site calendar events ensured (management reviews, hazmat/DGR checks, recurring windows)");
} catch (Throwable $e) {
    cli_writeln("[twu] WARN site calendar events failed: " . $e->getMessage() . " — continuing.");
}

// ---------------------------------------------------------------------------
// 6c. Course completion criteria — every tracked activity must be completed
// ---------------------------------------------------------------------------
// Sets each Initial Training (and supplemental) course to "complete when all
// tracked activities (page lessons + quiz) are complete". This is what makes
// course completion auditable: an employee's course-completion record reflects
// that they actually viewed every lesson and passed the quiz, not just that
// they were enrolled.
function twu_ensure_course_completion_criteria(stdClass $course): int {
    global $DB, $CFG;
    require_once($CFG->dirroot . '/completion/completion_aggregation.php');

    // Find tracked activities in this course (pages + quizzes; cert is the
    // output, forum is supplementary — neither participates in completion).
    $modules = $DB->get_records_sql(
        "SELECT cm.id AS cmid, cm.module AS modid, m.name AS modname, cm.completion
           FROM {course_modules} cm
           JOIN {modules} m ON m.id = cm.module
          WHERE cm.course = :courseid
            AND cm.completion > 0
            AND m.name IN ('page', 'quiz')",
        ['courseid' => $course->id]
    );
    if (!$modules) {
        return 0;
    }

    // Overall aggregation method (one row per course; criteriatype = NULL).
    $rowall = $DB->get_record('course_completion_aggr_methd',
        ['course' => $course->id, 'criteriatype' => null]);
    if (!$rowall) {
        $DB->insert_record('course_completion_aggr_methd', (object)[
            'course'       => $course->id,
            'criteriatype' => null,
            'method'       => 1, // COMPLETION_AGGREGATION_ALL
            'value'        => null,
        ]);
    } elseif ((int)$rowall->method !== 1) {
        $DB->set_field('course_completion_aggr_methd', 'method', 1, ['id' => $rowall->id]);
    }

    // Activity-criterion aggregation (criteriatype = 1 — COMPLETION_CRITERIA_TYPE_ACTIVITY).
    $rowact = $DB->get_record('course_completion_aggr_methd',
        ['course' => $course->id, 'criteriatype' => 1]);
    if (!$rowact) {
        $DB->insert_record('course_completion_aggr_methd', (object)[
            'course'       => $course->id,
            'criteriatype' => 1,
            'method'       => 1, // ALL
            'value'        => null,
        ]);
    }

    // Insert one criterion row per tracked module. Idempotent on (course,
    // criteriatype, moduleinstance).
    $added = 0;
    foreach ($modules as $cm) {
        $existing = $DB->get_record('course_completion_criteria', [
            'course'         => $course->id,
            'criteriatype'   => 1,
            'moduleinstance' => $cm->cmid,
        ]);
        if ($existing) {
            continue;
        }
        $DB->insert_record('course_completion_criteria', (object)[
            'course'         => $course->id,
            'criteriatype'   => 1,
            'module'         => $cm->modname,
            'moduleinstance' => $cm->cmid,
            'courseinstance' => null,
            'enrolperiod'    => null,
            'timeend'        => null,
            'gradepass'      => null,
            'role'           => null,
        ]);
        $added++;
    }
    return $added;
}

try {
// Apply to every course we created in this bootstrap, including the final
// exam course.
$alltargetcourses = array_merge(
    array_values($createdcourses),
    [$finalcourse],
    array_values($enginecourses),
    array_values($opscourses)
);
$totalcriteria = 0;
foreach ($alltargetcourses as $course) {
    $totalcriteria += twu_ensure_course_completion_criteria($course);
}
cli_writeln("[twu] course completion criteria: $totalcriteria activity-criterion rows across " . count($alltargetcourses) . " courses");
} catch (Throwable $e) {
    cli_writeln("[twu] WARN course completion criteria failed: " . $e->getMessage() . " — continuing.");
}

// ---------------------------------------------------------------------------
// 6d. Certificate availability — gate cert behind completion of all lessons + quiz
// ---------------------------------------------------------------------------
// The customcert is visible on the course page but should be unavailable
// (greyed out with "Restricted") until the user completes every tracked
// activity. This is what prevents users from downloading the cert before
// they've earned it.
function twu_ensure_cert_availability(stdClass $course): void {
    global $DB;

    // Find the customcert for this course.
    $cert = $DB->get_record_sql(
        "SELECT cm.id AS cmid
           FROM {course_modules} cm
           JOIN {modules} m ON m.id = cm.module AND m.name = 'customcert'
          WHERE cm.course = :courseid",
        ['courseid' => $course->id]
    );
    if (!$cert) {
        return; // no cert in this course (engine/ops courses don't have one)
    }

    // Find all tracked page + quiz cmids — these are the prerequisites.
    $prereqcmids = $DB->get_fieldset_sql(
        "SELECT cm.id
           FROM {course_modules} cm
           JOIN {modules} m ON m.id = cm.module
          WHERE cm.course = :courseid
            AND cm.completion > 0
            AND m.name IN ('page', 'quiz')",
        ['courseid' => $course->id]
    );
    if (!$prereqcmids) {
        return;
    }

    // Build the availability JSON. Each c entry requires the named cm to be
    // complete (e=1 means COMPLETION_COMPLETE). showc=false hides the
    // individual condition descriptions; one combined message is cleaner.
    $conditions = [];
    foreach ($prereqcmids as $cmid) {
        $conditions[] = ['type' => 'completion', 'cm' => (int)$cmid, 'e' => 1];
    }
    $availability = json_encode([
        'op'    => '&',
        'c'     => $conditions,
        'showc' => array_fill(0, count($conditions), false),
    ]);

    // Only update if different from current value.
    $current = $DB->get_field('course_modules', 'availability', ['id' => $cert->cmid]);
    if ($current !== $availability) {
        $DB->set_field('course_modules', 'availability', $availability, ['id' => $cert->cmid]);
    }
}

try {
foreach ($createdcourses as $idnumber => $course) {
    twu_ensure_cert_availability($course);
}
// Gate the master cert on the final exam course too.
twu_ensure_cert_availability($finalcourse);
cli_writeln("[twu] certificate availability: gated behind completion of all lessons + quiz in " . (count($createdcourses) + 1) . " Initial Training courses");
} catch (Throwable $e) {
    cli_writeln("[twu] WARN cert availability gating failed: " . $e->getMessage() . " — continuing.");
}

// ---------------------------------------------------------------------------
// 6e. Welcome HTML blocks on each Initial Training course page
// ---------------------------------------------------------------------------
function twu_ensure_course_html_block(stdClass $course, string $title, string $html,
                                      string $region = 'side-post', int $weight = -10): void {
    global $DB;
    $context = context_course::instance($course->id);

    // Idempotent: skip if an HTML block with this title already exists in
    // this course context.
    $existing = $DB->get_records('block_instances', [
        'blockname'        => 'html',
        'parentcontextid'  => $context->id,
    ]);
    foreach ($existing as $bi) {
        // configdata is base64-encoded serialized; decode and compare title.
        $cfg = unserialize(base64_decode($bi->configdata));
        if (is_object($cfg) && !empty($cfg->title) && $cfg->title === $title) {
            return;
        }
    }

    $configobj = (object)[
        'title'  => $title,
        'text'   => $html,
        'format' => FORMAT_HTML,
        'classes' => '',
    ];
    $configdata = base64_encode(serialize($configobj));

    $DB->insert_record('block_instances', (object)[
        'blockname'         => 'html',
        'parentcontextid'   => $context->id,
        'showinsubcontexts' => 0,
        'pagetypepattern'   => 'course-view-*',
        'subpagepattern'    => null,
        'defaultregion'     => $region,
        'defaultweight'     => $weight,
        'configdata'        => $configdata,
        'timecreated'       => time(),
        'timemodified'      => time(),
    ]);
}

// Per-module welcome block content.
$welcome_blocks = [
    'TWF4-1' => [
        'title' => 'Module 1 at a glance',
        'time'  => '~1 hour core + ~20 min supplemental videos',
        'about' => 'How to recognize Suspected Unapproved Parts (SUP) and counterfeit parts. The receiving-line discipline that protects TurbineWorks and our customers from non-conforming material in the supply chain.',
    ],
    'TWF4-2' => [
        'title' => 'Module 2 at a glance',
        'time'  => '~1 hour core + ~15 min supplemental',
        'about' => 'Receiving inspection workflow, the FAA 8130-3 block-by-block, package integrity, and outbound shipping discipline. The two interfaces where most quality findings happen.',
    ],
    'TWF4-3' => [
        'title' => 'Module 3 at a glance',
        'time'  => '~1 hour',
        'about' => 'Where ASA-100 fits in the FAA framework. The relationship between AC 00-56, ASA, ASA-100, and our QAM. Foundation for everything else in the curriculum.',
    ],
    'TWF4-4' => [
        'title' => 'Module 4 at a glance',
        'time'  => '~1 hour core + ~15 min supplemental',
        'about' => 'Storage, segregation, FOD prevention per NAS 412, shelf-life control, and warehouse environmental discipline. Where most parts spend most of their TurbineWorks time.',
    ],
    'TWF4-5' => [
        'title' => 'Module 5 at a glance',
        'time'  => '~1 hour',
        'about' => 'Recordkeeping discipline, document control, the 7+ year retention expectation, and AC 21-38 mutilation. The audit-evidence backbone of ASA-100.',
    ],
    'TWF4-6' => [
        'title' => 'Module 6 at a glance',
        'time'  => '~2 hours',
        'about' => 'Deep dive on AC 00-56. Why it exists, what it does and does not require, how it flows down to ASA-100 and our QAM. Longest module by design.',
    ],
    'TWF4-7' => [
        'title' => 'Module 7 at a glance',
        'time'  => '~1 hour core + ~20 min supplemental',
        'about' => 'ESD physics, ANSI/ESD S20.20 program elements, identification of ESD-sensitive parts, personnel grounding, and ESD-safe packaging. The latent-damage problem.',
    ],
    'TWF4-8' => [
        'title' => 'Module 8 at a glance',
        'time'  => '~1 hour core + ~20 min supplemental',
        'about' => 'Hazmat identification, the 9 DOT classes, hidden hazmat in aviation parts, UN system, IATA DGR for air shipment, and the Shipper&apos;s Declaration.',
    ],
];

try {
foreach ($createdcourses as $idnumber => $course) {
    if (!isset($welcome_blocks[$idnumber])) {
        continue;
    }
    $w = $welcome_blocks[$idnumber];
    $html = '<div style="font-size:0.95em;">';
    $html .= '<p style="margin:0 0 8px 0;"><strong>Estimated time:</strong> ' . $w['time'] . '</p>';
    $html .= '<p style="margin:0 0 8px 0;">' . $w['about'] . '</p>';
    $html .= '<hr style="border:none; border-top:1px solid #ffc800; margin:10px 0;">';
    $html .= '<p style="margin:0 0 6px 0;"><strong>How to complete this module:</strong></p>';
    $html .= '<ol style="margin:0 0 10px 18px; padding:0;">';
    $html .= '<li>Read each lesson in order</li>';
    $html .= '<li>Review the supplemental videos page</li>';
    $html .= '<li>Pass the end-of-module quiz (80% required)</li>';
    $html .= '<li>Your certificate will become available automatically</li>';
    $html .= '</ol>';
    $html .= '<p style="margin:0; font-size:0.9em; opacity:0.85;">Questions? Post in the &ldquo;Ask the QA Manager&rdquo; forum.</p>';
    $html .= '</div>';
    twu_ensure_course_html_block($course, $w['title'], $html, 'side-post', -10);
}
cli_writeln("[twu] welcome HTML blocks added to " . count($createdcourses) . " Initial Training courses");
} catch (Throwable $e) {
    cli_writeln("[twu] WARN welcome blocks failed: " . $e->getMessage() . " — continuing.");
}

// Rebuild course cache for all touched courses so completion / availability /
// cohort-sync changes are visible immediately rather than after the next
// nightly cron rebuild.
try {
foreach ($alltargetcourses as $course) {
    rebuild_course_cache($course->id, true);
}
cli_writeln("[twu] rebuilt course cache for " . count($alltargetcourses) . " courses");
} catch (Throwable $e) {
    cli_writeln("[twu] WARN course cache rebuild failed: " . $e->getMessage() . " — continuing.");
}

// ---------------------------------------------------------------------------
// 7. Mark done
// ---------------------------------------------------------------------------
file_put_contents($marker, date('c') . "\n");
cli_writeln("[twu] bootstrap complete; marker written to $marker");
exit(0);
