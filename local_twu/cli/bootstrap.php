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
require_once($CFG->dirroot . '/question/engine/bank.php');
require_once($CFG->dirroot . '/question/editlib.php');
require_once(__DIR__ . '/../content/content.php');
require_once(__DIR__ . '/../content/quizzes.php');
require_once(__DIR__ . '/../content/glossary.php');

// Run CLI as admin so question_save_from_form() etc. have proper $USER context.
\core\session\manager::set_user(get_admin());

// Bump the suffix here (and in the entrypoint marker docs) when adding new
// bootstrap steps that should run on existing sites.
$marker = $CFG->dataroot . '/.twu-bootstrapped-v23';
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

$allquizzes = local_twu_get_quizzes();
foreach ($createdcourses as $idnumber => $course) {
    if (!isset($allquizzes[$idnumber])) {
        continue;
    }
    cli_writeln("[twu] seeding quiz for $idnumber");
    twu_ensure_quiz($course, $allquizzes[$idnumber]);
}

// ---------------------------------------------------------------------------
// 5e3. Custom Certificate — auto-issued PDF on Initial Training course completion
// ---------------------------------------------------------------------------
function twu_ensure_customcert(stdClass $course): ?int {
    global $DB, $CFG;
    require_once($CFG->dirroot . '/mod/customcert/locallib.php');

    $certname = 'Certificate of Completion';
    $intro = '<p>Your TurbineWorks University Certificate of Completion will be available here once you have finished all required activities in this module. The certificate is a branded PDF with a unique verification serial and may be used as audit evidence of training completion.</p>';

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

    // Branded layout (A4 landscape, all positions in mm, refpoint=1 = top-center).
    $now = time();
    $elements = [
        ['element' => 'text',        'name' => 'Header',       'data' => json_encode(['text' => 'TurbineWorks University']),                              'font' => 'helveticab', 'fontsize' => 30, 'colour' => '#0d2240', 'posx' => 148, 'posy' => 30,  'width' => 250, 'alignment' => 'C', 'refpoint' => 1, 'sequence' => 1],
        ['element' => 'text',        'name' => 'Subtitle',     'data' => json_encode(['text' => 'Certificate of Completion']),                            'font' => 'helvetica',  'fontsize' => 18, 'colour' => '#ffc800', 'posx' => 148, 'posy' => 50,  'width' => 250, 'alignment' => 'C', 'refpoint' => 1, 'sequence' => 2],
        ['element' => 'text',        'name' => 'GoldBar',      'data' => json_encode(['text' => '_____________________________________________']),         'font' => 'helvetica',  'fontsize' => 16, 'colour' => '#ffc800', 'posx' => 148, 'posy' => 62,  'width' => 250, 'alignment' => 'C', 'refpoint' => 1, 'sequence' => 3],
        ['element' => 'text',        'name' => 'IntroLine',    'data' => json_encode(['text' => 'This is to certify that']),                              'font' => 'helvetica',  'fontsize' => 14, 'colour' => '#333333', 'posx' => 148, 'posy' => 80,  'width' => 250, 'alignment' => 'C', 'refpoint' => 1, 'sequence' => 4],
        ['element' => 'studentname', 'name' => 'Recipient',    'data' => '',                                                                              'font' => 'helveticab', 'fontsize' => 26, 'colour' => '#0d2240', 'posx' => 148, 'posy' => 95,  'width' => 250, 'alignment' => 'C', 'refpoint' => 1, 'sequence' => 5],
        ['element' => 'text',        'name' => 'CompletedLine','data' => json_encode(['text' => 'has successfully completed the ASA-100 training module']), 'font' => 'helvetica',  'fontsize' => 14, 'colour' => '#333333', 'posx' => 148, 'posy' => 120, 'width' => 250, 'alignment' => 'C', 'refpoint' => 1, 'sequence' => 6],
        ['element' => 'coursename',  'name' => 'CourseName',   'data' => '',                                                                              'font' => 'helveticab', 'fontsize' => 18, 'colour' => '#0d2240', 'posx' => 148, 'posy' => 135, 'width' => 270, 'alignment' => 'C', 'refpoint' => 1, 'sequence' => 7],
        ['element' => 'text',        'name' => 'On',           'data' => json_encode(['text' => 'on']),                                                   'font' => 'helvetica',  'fontsize' => 12, 'colour' => '#666666', 'posx' => 148, 'posy' => 155, 'width' => 250, 'alignment' => 'C', 'refpoint' => 1, 'sequence' => 8],
        ['element' => 'date',        'name' => 'Date',         'data' => json_encode(['dateitem' => -1, 'dateformat' => 'F j, Y']),                       'font' => 'helveticab', 'fontsize' => 14, 'colour' => '#0d2240', 'posx' => 148, 'posy' => 165, 'width' => 250, 'alignment' => 'C', 'refpoint' => 1, 'sequence' => 9],
        ['element' => 'text',        'name' => 'VerifyLabel',  'data' => json_encode(['text' => 'Verification Code:']),                                   'font' => 'helvetica',  'fontsize' => 10, 'colour' => '#999999', 'posx' => 148, 'posy' => 188, 'width' => 250, 'alignment' => 'C', 'refpoint' => 1, 'sequence' => 10],
        ['element' => 'code',        'name' => 'VerifyCode',   'data' => '',                                                                              'font' => 'courier',    'fontsize' => 12, 'colour' => '#0d2240', 'posx' => 148, 'posy' => 195, 'width' => 250, 'alignment' => 'C', 'refpoint' => 1, 'sequence' => 11],
    ];
    foreach ($elements as $el) {
        $record = (object)$el;
        $record->pageid = $page->id;
        $record->timecreated = $now;
        $record->timemodified = $now;
        $DB->insert_record('customcert_elements', $record);
    }

    cli_writeln("[twu]   created customcert for course $course->id (cmid={$cm->coursemodule}, " . count($elements) . " elements)");
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
        return (int)$existing->id;
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
        return (int)$cm->coursemodule;
    } catch (Throwable $e) {
        cli_writeln("[twu]   could not create forum: " . $e->getMessage());
        return null;
    }
}

foreach ($createdcourses as $idnumber => $course) {
    twu_ensure_forum(
        $course,
        'Ask the QA Manager',
        '<p>Post questions about the module content or how it applies to TurbineWorks procedures. The QA Manager (or designate) will respond. Threads stay visible to all employees so common questions become reference material.</p>'
    );
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
foreach (local_twu_get_engine_parts_courses() as $coursedata) {
    $lessons = $coursedata['lessons'] ?? [];
    unset($coursedata['lessons']);
    $course = twu_ensure_course($enginecat->id, $coursedata);
    cli_writeln("[twu] seeding " . count($lessons) . " engine-parts lessons in {$coursedata['shortname']}");
    foreach ($lessons as $lesson) {
        twu_ensure_page_lesson($course, 0, $lesson['name'], $lesson['intro'], $lesson['content']);
    }
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
// 7. Mark done
// ---------------------------------------------------------------------------
file_put_contents($marker, date('c') . "\n");
cli_writeln("[twu] bootstrap complete; marker written to $marker");
exit(0);
