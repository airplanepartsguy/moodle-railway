<?php
// TurbineWorks Reports — admin-facing audit-evidence reports.
// Accessible at /local/twu/report.php?r=<reportname>
//
// Reports:
//   completion       — completion roster (user × course matrix)
//   recurring        — recurring training due tracker (completions aging)
//   certifications   — DOT Hazmat / IATA DGR / ESD certification expiry roster
//   quiz_attempts    — failed-quiz roster (users who failed quiz attempts)
//
// Each report supports format=html (default) or format=csv for download.

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');

$r      = optional_param('r', 'index', PARAM_ALPHA);
$format = optional_param('format', 'html', PARAM_ALPHA);

require_login();
require_capability('local/twu:viewreports', context_system::instance());

$context = context_system::instance();
$pageurl = new moodle_url('/local/twu/report.php', ['r' => $r]);

admin_externalpage_setup('twu_report_' . ($r === 'index' ? 'index' : $r), '', null, $pageurl);

global $DB, $PAGE, $OUTPUT, $CFG;

// ---------------------------------------------------------------------------
// Helper: emit CSV download response and exit.
// ---------------------------------------------------------------------------
function twu_csv_download(string $filename, array $headers, array $rows): void {
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    $fp = fopen('php://output', 'w');
    fputcsv($fp, $headers);
    foreach ($rows as $row) {
        fputcsv($fp, $row);
    }
    fclose($fp);
    exit(0);
}

// ---------------------------------------------------------------------------
// Report: completion roster (user × Initial Training course matrix)
// ---------------------------------------------------------------------------
function twu_report_completion(string $format): void {
    global $DB, $OUTPUT;

    // Get all Initial Training courses (idnumber TWF4-*).
    $courses = $DB->get_records_select('course', "idnumber LIKE 'TWF4-%'",
        null, 'idnumber ASC', 'id, shortname, fullname, idnumber');
    if (!$courses) {
        echo $OUTPUT->notification('No Initial Training courses found (expected idnumber TWF4-*).', 'notifyproblem');
        return;
    }

    // Get all users with enrolment in at least one Initial Training course.
    list($insql, $inparams) = $DB->get_in_or_equal(array_keys($courses), SQL_PARAMS_NAMED, 'c');
    $users = $DB->get_records_sql(
        "SELECT DISTINCT u.id, u.firstname, u.lastname, u.username, u.email, u.suspended
           FROM {user} u
           JOIN {user_enrolments} ue ON ue.userid = u.id
           JOIN {enrol} e ON e.id = ue.enrolid AND e.status = 0
          WHERE e.courseid $insql AND u.deleted = 0
          ORDER BY u.lastname, u.firstname",
        $inparams
    );

    // Per-user, per-course completion status.
    $completions = $DB->get_records_sql(
        "SELECT CONCAT(cc.userid, '-', cc.course) AS k, cc.userid, cc.course, cc.timecompleted
           FROM {course_completions} cc
          WHERE cc.course $insql AND cc.timecompleted IS NOT NULL",
        $inparams
    );

    if ($format === 'csv') {
        $headers = ['Username', 'Last Name', 'First Name', 'Email', 'Suspended'];
        foreach ($courses as $c) {
            $headers[] = $c->idnumber . ' completed';
        }
        $headers[] = 'Total completed';
        $rows = [];
        foreach ($users as $u) {
            $row = [$u->username, $u->lastname, $u->firstname, $u->email, $u->suspended ? 'Yes' : ''];
            $count = 0;
            foreach ($courses as $c) {
                $key = $u->id . '-' . $c->id;
                if (isset($completions[$key])) {
                    $row[] = userdate($completions[$key]->timecompleted, '%Y-%m-%d');
                    $count++;
                } else {
                    $row[] = '';
                }
            }
            $row[] = $count . ' / ' . count($courses);
            $rows[] = $row;
        }
        twu_csv_download('twu-completion-roster-' . date('Y-m-d') . '.csv', $headers, $rows);
    }

    echo '<h3>Initial Training Completion Roster</h3>';
    echo '<p>One row per enrolled employee; one column per Initial Training course. Cell shows completion date or blank if not complete.</p>';
    echo '<p><a class="btn btn-primary" href="?r=completion&format=csv">Download CSV</a></p>';

    if (!$users) {
        echo $OUTPUT->notification('No users enrolled in Initial Training courses yet. Add users to the All Employees or Initial Trainees cohort to populate this roster.', 'notifyinfo');
        return;
    }

    echo '<table class="generaltable" style="width:100%; border-collapse:collapse;">';
    echo '<thead><tr style="background:#0d2240; color:#fff;">';
    echo '<th style="padding:8px; border:1px solid #ddd;">Employee</th>';
    foreach ($courses as $c) {
        echo '<th style="padding:8px; border:1px solid #ddd;">' . s($c->idnumber) . '</th>';
    }
    echo '<th style="padding:8px; border:1px solid #ddd;">Total</th>';
    echo '</tr></thead><tbody>';

    foreach ($users as $u) {
        $count = 0;
        $rowcells = '';
        foreach ($courses as $c) {
            $key = $u->id . '-' . $c->id;
            if (isset($completions[$key])) {
                $rowcells .= '<td style="padding:6px; border:1px solid #ddd; background:#e8f5e9; text-align:center;">✓ ' . userdate($completions[$key]->timecompleted, '%Y-%m-%d') . '</td>';
                $count++;
            } else {
                $rowcells .= '<td style="padding:6px; border:1px solid #ddd; background:#fff; text-align:center; color:#999;">—</td>';
            }
        }
        $namelink = '<a href="' . (new moodle_url('/user/profile.php', ['id' => $u->id])) . '">' . s(fullname($u)) . '</a>';
        $suspended = $u->suspended ? ' <small style="color:#999;">(suspended)</small>' : '';
        echo '<tr>';
        echo '<td style="padding:6px; border:1px solid #ddd;">' . $namelink . $suspended . '<br><small>' . s($u->email) . '</small></td>';
        echo $rowcells;
        $pct = round(($count / count($courses)) * 100);
        $bgcolor = $pct === 100 ? '#c8e6c9' : ($pct >= 50 ? '#fff9c4' : '#ffcdd2');
        echo '<td style="padding:6px; border:1px solid #ddd; text-align:center; background:' . $bgcolor . ';"><strong>' . $count . ' / ' . count($courses) . '</strong><br><small>' . $pct . '%</small></td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
}

// ---------------------------------------------------------------------------
// Report: recurring training due tracker
// ---------------------------------------------------------------------------
function twu_report_recurring(string $format): void {
    global $DB, $OUTPUT;

    $now = time();
    $expiringsoon = $now + (30 * DAYSECS);  // due in next 30 days
    $expiry180    = $now - (180 * DAYSECS); // 180 day mark

    // Find all Initial Training completions and compute days until next-due.
    $rows = $DB->get_records_sql(
        "SELECT cc.id AS ccid, u.id AS userid, u.firstname, u.lastname, u.username, u.email,
                c.id AS courseid, c.shortname, c.idnumber, cc.timecompleted
           FROM {course_completions} cc
           JOIN {user} u ON u.id = cc.userid
           JOIN {course} c ON c.id = cc.course
          WHERE c.idnumber LIKE 'TWF4-%'
            AND cc.timecompleted IS NOT NULL
            AND u.deleted = 0
          ORDER BY cc.timecompleted ASC"
    );

    // Augment each with derived fields.
    $output = [];
    foreach ($rows as $r) {
        $dueat = $r->timecompleted + (180 * DAYSECS);
        $daysleft = (int)floor(($dueat - $now) / DAYSECS);
        $output[] = [
            'user'      => $r,
            'course'    => $r->shortname,
            'idnumber'  => $r->idnumber,
            'completed' => $r->timecompleted,
            'dueat'     => $dueat,
            'daysleft'  => $daysleft,
            'status'    => $daysleft < 0 ? 'expired' : ($daysleft <= 30 ? 'expiring' : 'current'),
        ];
    }

    if ($format === 'csv') {
        $rows_csv = [];
        foreach ($output as $row) {
            $rows_csv[] = [
                $row['user']->username,
                $row['user']->lastname,
                $row['user']->firstname,
                $row['user']->email,
                $row['idnumber'],
                userdate($row['completed'], '%Y-%m-%d'),
                userdate($row['dueat'], '%Y-%m-%d'),
                $row['daysleft'],
                $row['status'],
            ];
        }
        twu_csv_download('twu-recurring-due-' . date('Y-m-d') . '.csv',
            ['Username', 'Last Name', 'First Name', 'Email', 'Course', 'Completed', 'Due At', 'Days Remaining', 'Status'],
            $rows_csv);
    }

    echo '<h3>Recurring Training Due Tracker</h3>';
    echo '<p>Per TurbineWorks 6-month cadence, each Initial Training completion expires 180 days after completion. Reset is automatic via the scheduled task.</p>';
    echo '<p><a class="btn btn-primary" href="?r=recurring&format=csv">Download CSV</a></p>';

    $expiredc = count(array_filter($output, fn($r) => $r['status'] === 'expired'));
    $expiringc = count(array_filter($output, fn($r) => $r['status'] === 'expiring'));
    $currentc = count(array_filter($output, fn($r) => $r['status'] === 'current'));
    echo '<p><strong>Summary:</strong> ';
    echo '<span style="color:#d32f2f;">Expired (overdue): ' . $expiredc . '</span> · ';
    echo '<span style="color:#f57c00;">Expiring within 30 days: ' . $expiringc . '</span> · ';
    echo '<span style="color:#388e3c;">Current: ' . $currentc . '</span></p>';

    if (!$output) {
        echo $OUTPUT->notification('No completions recorded yet.', 'notifyinfo');
        return;
    }

    echo '<table class="generaltable" style="width:100%; border-collapse:collapse;">';
    echo '<thead><tr style="background:#0d2240; color:#fff;">';
    foreach (['Employee', 'Course', 'Completed', 'Due At', 'Days Left', 'Status'] as $h) {
        echo '<th style="padding:8px; border:1px solid #ddd;">' . $h . '</th>';
    }
    echo '</tr></thead><tbody>';
    foreach ($output as $row) {
        $bgcolor = $row['status'] === 'expired' ? '#ffcdd2' : ($row['status'] === 'expiring' ? '#fff9c4' : '#e8f5e9');
        echo '<tr style="background:' . $bgcolor . ';">';
        echo '<td style="padding:6px; border:1px solid #ddd;">' . s(fullname($row['user'])) . '<br><small>' . s($row['user']->email) . '</small></td>';
        echo '<td style="padding:6px; border:1px solid #ddd;">' . s($row['idnumber']) . '</td>';
        echo '<td style="padding:6px; border:1px solid #ddd;">' . userdate($row['completed'], '%Y-%m-%d') . '</td>';
        echo '<td style="padding:6px; border:1px solid #ddd;">' . userdate($row['dueat'], '%Y-%m-%d') . '</td>';
        echo '<td style="padding:6px; border:1px solid #ddd; text-align:center;"><strong>' . $row['daysleft'] . '</strong></td>';
        echo '<td style="padding:6px; border:1px solid #ddd;">' . ucfirst($row['status']) . '</td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
}

// ---------------------------------------------------------------------------
// Report: certification expiry roster (DOT Hazmat / IATA DGR / ESD)
// ---------------------------------------------------------------------------
function twu_report_certifications(string $format): void {
    global $DB, $OUTPUT;

    $fieldids = $DB->get_records_select('user_info_field',
        "shortname IN ('twu_hazmat_expiry', 'twu_dgr_expiry', 'twu_esd_expiry', 'twu_jobtitle', 'twu_department')",
        null, '', 'shortname, id, name');
    if (count($fieldids) < 3) {
        echo $OUTPUT->notification('Custom profile fields not yet created. Run the bootstrap CLI to create them.', 'notifyproblem');
        return;
    }

    $hazmatid = $fieldids['twu_hazmat_expiry']->id ?? null;
    $dgrid    = $fieldids['twu_dgr_expiry']->id    ?? null;
    $esdid    = $fieldids['twu_esd_expiry']->id    ?? null;
    $jobid    = $fieldids['twu_jobtitle']->id      ?? null;
    $deptid   = $fieldids['twu_department']->id    ?? null;

    $now = time();
    $users = $DB->get_records_sql(
        "SELECT u.id, u.firstname, u.lastname, u.username, u.email, u.suspended,
                d_haz.data AS hazmat_expiry,
                d_dgr.data AS dgr_expiry,
                d_esd.data AS esd_expiry,
                d_job.data AS jobtitle,
                d_dept.data AS department
           FROM {user} u
      LEFT JOIN {user_info_data} d_haz ON d_haz.userid = u.id AND d_haz.fieldid = :h
      LEFT JOIN {user_info_data} d_dgr ON d_dgr.userid = u.id AND d_dgr.fieldid = :g
      LEFT JOIN {user_info_data} d_esd ON d_esd.userid = u.id AND d_esd.fieldid = :e
      LEFT JOIN {user_info_data} d_job ON d_job.userid = u.id AND d_job.fieldid = :j
      LEFT JOIN {user_info_data} d_dept ON d_dept.userid = u.id AND d_dept.fieldid = :de
          WHERE u.deleted = 0
            AND (d_haz.data IS NOT NULL OR d_dgr.data IS NOT NULL OR d_esd.data IS NOT NULL)
          ORDER BY u.lastname, u.firstname",
        ['h' => $hazmatid, 'g' => $dgrid, 'e' => $esdid, 'j' => $jobid, 'de' => $deptid]
    );

    if ($format === 'csv') {
        $rows = [];
        foreach ($users as $u) {
            $rows[] = [
                $u->username, $u->lastname, $u->firstname, $u->email,
                $u->department, $u->jobtitle,
                $u->hazmat_expiry ? userdate((int)$u->hazmat_expiry, '%Y-%m-%d') : '',
                $u->dgr_expiry ? userdate((int)$u->dgr_expiry, '%Y-%m-%d') : '',
                $u->esd_expiry ? userdate((int)$u->esd_expiry, '%Y-%m-%d') : '',
            ];
        }
        twu_csv_download('twu-certifications-' . date('Y-m-d') . '.csv',
            ['Username', 'Last Name', 'First Name', 'Email', 'Department', 'Job Title',
             'DOT Hazmat Expiry', 'IATA DGR Expiry', 'ESD Expiry'],
            $rows);
    }

    echo '<h3>Certification Expiry Roster</h3>';
    echo '<p>DOT Hazmat (49 CFR §172.704 — recurrent every 3 years), IATA DGR (every 24 months), ESD (annual per TurbineWorks program).</p>';
    echo '<p><a class="btn btn-primary" href="?r=certifications&format=csv">Download CSV</a></p>';

    if (!$users) {
        echo $OUTPUT->notification('No users with certification expiry dates recorded. Edit user profiles to populate the certification fields.', 'notifyinfo');
        return;
    }

    $celldate = function (?string $stamp) use ($now) {
        if (!$stamp) return '<td style="padding:6px; border:1px solid #ddd; color:#999;">—</td>';
        $ts = (int)$stamp;
        $days = (int)floor(($ts - $now) / DAYSECS);
        $bgcolor = $days < 0 ? '#ffcdd2' : ($days <= 30 ? '#fff9c4' : '#e8f5e9');
        $label = $days < 0 ? "$days days overdue" : "in $days days";
        return '<td style="padding:6px; border:1px solid #ddd; background:' . $bgcolor . ';">' . userdate($ts, '%Y-%m-%d') . '<br><small>' . $label . '</small></td>';
    };

    echo '<table class="generaltable" style="width:100%; border-collapse:collapse;">';
    echo '<thead><tr style="background:#0d2240; color:#fff;">';
    foreach (['Employee', 'Department / Role', 'DOT Hazmat', 'IATA DGR', 'ESD'] as $h) {
        echo '<th style="padding:8px; border:1px solid #ddd;">' . $h . '</th>';
    }
    echo '</tr></thead><tbody>';
    foreach ($users as $u) {
        echo '<tr>';
        echo '<td style="padding:6px; border:1px solid #ddd;">' . s(fullname($u)) . '<br><small>' . s($u->email) . '</small></td>';
        echo '<td style="padding:6px; border:1px solid #ddd;">' . s($u->department ?: '—') . '<br><small>' . s($u->jobtitle ?: '') . '</small></td>';
        echo $celldate($u->hazmat_expiry);
        echo $celldate($u->dgr_expiry);
        echo $celldate($u->esd_expiry);
        echo '</tr>';
    }
    echo '</tbody></table>';
}

// ---------------------------------------------------------------------------
// Report: failed quiz attempts
// ---------------------------------------------------------------------------
function twu_report_quiz_attempts(string $format): void {
    global $DB, $OUTPUT;

    // Failed quiz attempts (finished but below the quiz gradepass).
    $rows = $DB->get_records_sql(
        "SELECT qa.id AS attemptid, u.id AS userid, u.firstname, u.lastname, u.username, u.email,
                c.id AS courseid, c.shortname, c.idnumber,
                q.name AS quizname, q.id AS quizid, q.gradepass,
                qa.sumgrades, q.sumgrades AS maxgrades, qa.timefinish, qa.attempt
           FROM {quiz_attempts} qa
           JOIN {quiz} q ON q.id = qa.quiz
           JOIN {user} u ON u.id = qa.userid
           JOIN {course} c ON c.id = q.course
          WHERE qa.state = 'finished'
            AND qa.timefinish > 0
            AND u.deleted = 0
            AND q.gradepass > 0
            AND ((qa.sumgrades / q.sumgrades) * q.grade) < q.gradepass
          ORDER BY qa.timefinish DESC"
    );

    if ($format === 'csv') {
        $rows_csv = [];
        foreach ($rows as $r) {
            $scorepct = $r->maxgrades > 0 ? round(($r->sumgrades / $r->maxgrades) * 100, 1) : 0;
            $rows_csv[] = [
                $r->username, $r->lastname, $r->firstname, $r->email,
                $r->idnumber, $r->quizname, $r->attempt,
                userdate($r->timefinish, '%Y-%m-%d %H:%M'),
                $scorepct . '%',
                round($r->gradepass) . '%',
            ];
        }
        twu_csv_download('twu-failed-quizzes-' . date('Y-m-d') . '.csv',
            ['Username', 'Last Name', 'First Name', 'Email', 'Course', 'Quiz', 'Attempt #',
             'Finished', 'Score', 'Pass Threshold'],
            $rows_csv);
    }

    echo '<h3>Failed Quiz Attempts</h3>';
    echo '<p>Finished quiz attempts where the score is below the quiz pass threshold (default 80%). Use to identify trainees who may need additional support or coaching.</p>';
    echo '<p><a class="btn btn-primary" href="?r=quiz_attempts&format=csv">Download CSV</a></p>';

    if (!$rows) {
        echo $OUTPUT->notification('No failed quiz attempts on record.', 'notifyinfo');
        return;
    }

    echo '<table class="generaltable" style="width:100%; border-collapse:collapse;">';
    echo '<thead><tr style="background:#0d2240; color:#fff;">';
    foreach (['Employee', 'Course', 'Quiz', 'Attempt', 'Finished', 'Score', 'Pass'] as $h) {
        echo '<th style="padding:8px; border:1px solid #ddd;">' . $h . '</th>';
    }
    echo '</tr></thead><tbody>';
    foreach ($rows as $r) {
        $scorepct = $r->maxgrades > 0 ? round(($r->sumgrades / $r->maxgrades) * 100, 1) : 0;
        echo '<tr style="background:#ffe0e0;">';
        echo '<td style="padding:6px; border:1px solid #ddd;">' . s(fullname($r)) . '<br><small>' . s($r->email) . '</small></td>';
        echo '<td style="padding:6px; border:1px solid #ddd;">' . s($r->idnumber) . '</td>';
        echo '<td style="padding:6px; border:1px solid #ddd;">' . s($r->quizname) . '</td>';
        echo '<td style="padding:6px; border:1px solid #ddd; text-align:center;">#' . $r->attempt . '</td>';
        echo '<td style="padding:6px; border:1px solid #ddd;">' . userdate($r->timefinish, '%Y-%m-%d %H:%M') . '</td>';
        echo '<td style="padding:6px; border:1px solid #ddd; text-align:center;"><strong>' . $scorepct . '%</strong></td>';
        echo '<td style="padding:6px; border:1px solid #ddd; text-align:center;">' . round($r->gradepass) . '%</td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
}

// ---------------------------------------------------------------------------
// Index
// ---------------------------------------------------------------------------
function twu_report_index(): void {
    echo '<h3>TurbineWorks Reports</h3>';
    echo '<p>Audit-ready training reports for ASA-100 accreditation and ongoing quality assurance.</p>';
    $reports = [
        'completion' => [
            'title' => 'Initial Training Completion Roster',
            'desc'  => 'User × course matrix showing who has completed which Initial Training modules.',
        ],
        'recurring' => [
            'title' => 'Recurring Training Due Tracker',
            'desc'  => 'Completions sorted by days until 180-day expiry. Identify upcoming and overdue recurring training.',
        ],
        'certifications' => [
            'title' => 'Certification Expiry Roster',
            'desc'  => 'DOT Hazmat, IATA DGR, and ESD certification expiry dates by employee.',
        ],
        'quiz_attempts' => [
            'title' => 'Failed Quiz Attempts',
            'desc'  => 'Trainees who failed a quiz attempt — candidates for follow-up coaching.',
        ],
    ];
    echo '<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-top:20px;">';
    foreach ($reports as $key => $info) {
        $url = new moodle_url('/local/twu/report.php', ['r' => $key]);
        echo '<div style="border:1px solid #ddd; padding:20px; border-left:4px solid #0d2240;">';
        echo '<h4><a href="' . $url . '">' . $info['title'] . '</a></h4>';
        echo '<p>' . $info['desc'] . '</p>';
        echo '<p><a class="btn btn-secondary" href="' . $url . '">View report</a></p>';
        echo '</div>';
    }
    echo '</div>';
    echo '<h4 style="margin-top:30px;">Notes for auditors</h4>';
    echo '<ul>';
    echo '<li>Reports support CSV download for offline analysis or audit-file inclusion.</li>';
    echo '<li>Underlying data is in Moodle\'s standard tables (course_completions, quiz_attempts, user_info_data) — auditors with database access can verify report contents directly.</li>';
    echo '<li>Recurring training reset is performed by the scheduled task <code>local_twu\\task\\recurring_reset</code> (runs daily 02:07 server time).</li>';
    echo '</ul>';
}

// ---------------------------------------------------------------------------
// Dispatcher
// ---------------------------------------------------------------------------
echo $OUTPUT->header();

switch ($r) {
    case 'completion':
        twu_report_completion($format);
        break;
    case 'recurring':
        twu_report_recurring($format);
        break;
    case 'certifications':
        twu_report_certifications($format);
        break;
    case 'quiz_attempts':
        twu_report_quiz_attempts($format);
        break;
    default:
        twu_report_index();
        break;
}

echo $OUTPUT->footer();
