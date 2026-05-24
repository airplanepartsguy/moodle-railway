<?php
// TurbineWorks bootstrap diagnostic page.
// Browser-accessible alternative to digging through Railway logs.
// Shows: marker state, course counts, lesson counts per course,
// recent PHP errors, and a button to re-run the bootstrap.
//
// Restricted to site admins.

require(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');

require_login();
require_capability('moodle/site:config', context_system::instance());

$action = optional_param('action', '', PARAM_ALPHA);

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/twu/diag.php'));
$PAGE->set_pagelayout('admin');
$PAGE->set_title('TurbineWorks Diagnostic | TurbineWorks University');
$PAGE->set_heading('TurbineWorks Bootstrap Diagnostic');

echo $OUTPUT->header();

echo '<style>
.diag-section { margin:24px 0; padding:18px 22px; border:1px solid #ddd; border-radius:6px; background:#fff; }
.diag-section h3 { margin:0 0 12px 0; color:#0d2240; }
.diag-table { width:100%; border-collapse:collapse; }
.diag-table th, .diag-table td { padding:8px 12px; border-bottom:1px solid #eee; text-align:left; }
.diag-table th { background:#f7f8fa; color:#555; }
.ok { color:#1b5e20; font-weight:600; }
.bad { color:#c62828; font-weight:600; }
.warn { color:#f57c00; font-weight:600; }
.diag-log { background:#1e1e1e; color:#e0e0e0; padding:14px 18px; font-family:monospace; font-size:0.85em; max-height:600px; overflow-y:auto; white-space:pre-wrap; border-radius:6px; }
.diag-log .err { color:#ff8888; }
.diag-log .warn { color:#ffdd88; }
.diag-log .info { color:#88ddff; }
.diag-actions { margin:20px 0; }
.diag-actions a { display:inline-block; padding:10px 20px; background:#0d2240; color:#fff; text-decoration:none; border-radius:4px; margin-right:10px; }
.diag-actions a.warn { background:#f57c00; }
.diag-actions a.danger { background:#c62828; }
</style>';

// ===== Action handler =====
if ($action === 'rerun' && confirm_sesskey()) {
    // Capture output of the bootstrap script
    echo '<div class="diag-section"><h3>Bootstrap re-run output</h3>';
    echo '<div class="diag-log">';
    @ob_flush();
    @flush();
    $cmd = 'php ' . escapeshellarg($CFG->dirroot . '/local/twu/cli/bootstrap.php') . ' --force 2>&1';
    $output = [];
    $exitcode = 0;
    exec($cmd, $output, $exitcode);
    foreach ($output as $line) {
        $class = '';
        if (stripos($line, 'fatal') !== false || stripos($line, 'error:') !== false) {
            $class = ' class="err"';
        } else if (stripos($line, 'warn') !== false) {
            $class = ' class="warn"';
        } else if (strpos($line, '[twu]') === 0 || strpos($line, '[twu]') === 1) {
            $class = ' class="info"';
        }
        echo '<span' . $class . '>' . htmlspecialchars($line) . "</span>\n";
    }
    echo '</div>';
    echo '<p><strong>Exit code:</strong> ' . $exitcode . '</p>';
    echo '</div>';
}

if ($action === 'deletemarker' && confirm_sesskey()) {
    $deleted = 0;
    foreach (glob($CFG->dataroot . '/.twu-bootstrapped-v*') as $f) {
        @unlink($f);
        $deleted++;
    }
    echo '<div class="diag-section"><h3>Markers deleted</h3>';
    echo '<p>Removed ' . $deleted . ' marker file(s). Next deploy (or rerun) will start from scratch.</p>';
    echo '</div>';
}

// ===== State inspection =====

// Markers
echo '<div class="diag-section">';
echo '<h3>Bootstrap markers in $CFG->dataroot</h3>';
$markers = glob($CFG->dataroot . '/.twu-bootstrapped-v*');
if (!$markers) {
    echo '<p class="warn">No markers found. Bootstrap will run on next deploy.</p>';
} else {
    echo '<table class="diag-table"><thead><tr><th>Marker</th><th>Written</th></tr></thead><tbody>';
    foreach ($markers as $m) {
        echo '<tr><td>' . htmlspecialchars(basename($m)) . '</td>';
        echo '<td>' . htmlspecialchars(date('Y-m-d H:i:s', filemtime($m))) . ' &mdash; contents: ' . htmlspecialchars(trim(file_get_contents($m))) . '</td></tr>';
    }
    echo '</tbody></table>';
}
echo '</div>';

// Courses + lesson counts
echo '<div class="diag-section">';
echo '<h3>Courses and lesson counts</h3>';
$courses = $DB->get_records_sql("
    SELECT c.id, c.shortname, c.fullname, c.idnumber,
           (SELECT COUNT(*) FROM {course_modules} cm
             JOIN {modules} m ON m.id = cm.module
             WHERE cm.course = c.id AND m.name = 'page') AS pages,
           (SELECT COUNT(*) FROM {course_modules} cm
             JOIN {modules} m ON m.id = cm.module
             WHERE cm.course = c.id AND m.name = 'quiz') AS quizzes,
           (SELECT COUNT(*) FROM {course_modules} cm
             JOIN {modules} m ON m.id = cm.module
             WHERE cm.course = c.id AND m.name = 'forum') AS forums,
           (SELECT COUNT(*) FROM {course_modules} cm
             JOIN {modules} m ON m.id = cm.module
             WHERE cm.course = c.id AND m.name = 'customcert') AS certs,
           (SELECT COUNT(*) FROM {course_modules} cm
             JOIN {modules} m ON m.id = cm.module
             WHERE cm.course = c.id AND m.name = 'assign') AS assigns
      FROM {course} c
     WHERE c.id > 1
     ORDER BY c.idnumber, c.shortname");
echo '<table class="diag-table"><thead><tr><th>Course</th><th>ID#</th><th>Pages</th><th>Quizzes</th><th>Forums</th><th>Certs</th><th>Assigns</th></tr></thead><tbody>';
foreach ($courses as $c) {
    $pagecls = $c->pages > 0 ? 'ok' : 'bad';
    echo '<tr>';
    echo '<td>' . htmlspecialchars($c->shortname) . '<br><small>' . htmlspecialchars($c->fullname) . '</small></td>';
    echo '<td><code>' . htmlspecialchars($c->idnumber ?: '—') . '</code></td>';
    echo '<td class="' . $pagecls . '">' . $c->pages . '</td>';
    echo '<td>' . $c->quizzes . '</td>';
    echo '<td>' . $c->forums . '</td>';
    echo '<td>' . $c->certs . '</td>';
    echo '<td>' . $c->assigns . '</td>';
    echo '</tr>';
}
echo '</tbody></table>';
echo '</div>';

// Cohorts
echo '<div class="diag-section">';
echo '<h3>Cohorts</h3>';
$cohorts = $DB->get_records_sql("
    SELECT c.id, c.idnumber, c.name,
           (SELECT COUNT(*) FROM {cohort_members} cm WHERE cm.cohortid = c.id) AS members,
           (SELECT COUNT(*) FROM {enrol} e WHERE e.enrol = 'cohort' AND e.customint1 = c.id) AS syncs
      FROM {cohort} c
     ORDER BY c.idnumber");
if (!$cohorts) {
    echo '<p class="bad">No cohorts! The cohort section of the bootstrap halted.</p>';
} else {
    echo '<table class="diag-table"><thead><tr><th>ID#</th><th>Name</th><th>Members</th><th>Course syncs</th></tr></thead><tbody>';
    foreach ($cohorts as $c) {
        echo '<tr>';
        echo '<td><code>' . htmlspecialchars($c->idnumber) . '</code></td>';
        echo '<td>' . htmlspecialchars($c->name) . '</td>';
        echo '<td>' . $c->members . '</td>';
        echo '<td>' . $c->syncs . '</td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
}
echo '</div>';

// Custom roles + capability counts
echo '<div class="diag-section">';
echo '<h3>Custom roles</h3>';
$roles = $DB->get_records_select('role', "shortname LIKE 'twu_%'", null, 'shortname');
if (!$roles) {
    echo '<p class="warn">No custom roles. Either the role section failed or never ran.</p>';
} else {
    echo '<table class="diag-table"><thead><tr><th>Shortname</th><th>Name</th><th>Capabilities assigned</th></tr></thead><tbody>';
    foreach ($roles as $r) {
        $capcount = $DB->count_records('role_capabilities', ['roleid' => $r->id]);
        echo '<tr><td><code>' . htmlspecialchars($r->shortname) . '</code></td>';
        echo '<td>' . htmlspecialchars($r->name) . '</td>';
        echo '<td>' . $capcount . '</td></tr>';
    }
    echo '</tbody></table>';
    echo '<p><small>If a role exists but has 0 capabilities, every capability was rejected by Moodle. If it has fewer than expected, some capabilities were skipped (likely renamed/removed in this Moodle version) — the bootstrap log will show which.</small></p>';
}
echo '</div>';

// Profile fields
echo '<div class="diag-section">';
echo '<h3>Custom profile fields</h3>';
$pf = $DB->get_records_select('user_info_field', "shortname LIKE 'twu_%'", null, 'sortorder', 'shortname, name, datatype');
if (!$pf) {
    echo '<p class="warn">No custom profile fields. Either the section failed or never ran.</p>';
} else {
    echo '<table class="diag-table"><thead><tr><th>Shortname</th><th>Name</th><th>Type</th></tr></thead><tbody>';
    foreach ($pf as $f) {
        echo '<tr><td><code>' . htmlspecialchars($f->shortname) . '</code></td><td>' . htmlspecialchars($f->name) . '</td><td>' . htmlspecialchars($f->datatype) . '</td></tr>';
    }
    echo '</tbody></table>';
}
echo '</div>';

// Theme settings
echo '<div class="diag-section">';
echo '<h3>Theme configuration</h3>';
$theme = get_config('core', 'theme');
echo '<p><strong>Active theme:</strong> ' . htmlspecialchars($theme) . '</p>';
if ($theme === 'snap') {
    $themecolor = get_config('theme_snap', 'themecolor');
    $navbarbg   = get_config('theme_snap', 'navbarbg');
    echo '<p><strong>Snap themecolor:</strong> ' . htmlspecialchars($themecolor ?: '(default)') . '</p>';
    echo '<p><strong>Snap navbarbg:</strong> ' . htmlspecialchars($navbarbg ?: '(default)') . '</p>';
    if ($themecolor === '#0d2240') {
        echo '<p class="ok">Theme is properly configured (navy/gold).</p>';
    } else {
        echo '<p class="bad">Theme color is NOT set to TurbineWorks navy — bootstrap theme section never ran.</p>';
    }
}
echo '</div>';

// Recent PHP error log
echo '<div class="diag-section">';
echo '<h3>Recent PHP errors (Moodle log)</h3>';
$logfile = $CFG->dataroot . '/error_log';
$candidates = [
    $CFG->dataroot . '/error_log',
    '/var/log/apache2/error.log',
    '/var/log/php-error.log',
];
$shown = false;
foreach ($candidates as $cand) {
    if (file_exists($cand) && is_readable($cand)) {
        $content = @file_get_contents($cand);
        if ($content) {
            $lines = explode("\n", $content);
            $tail = array_slice($lines, -100);
            echo '<p><strong>' . htmlspecialchars($cand) . '</strong> (last 100 lines):</p>';
            echo '<div class="diag-log">' . htmlspecialchars(implode("\n", $tail)) . '</div>';
            $shown = true;
            break;
        }
    }
}
if (!$shown) {
    echo '<p class="warn">No accessible PHP error log found in standard locations.</p>';
    echo '<p>Checked: ' . implode(', ', array_map('htmlspecialchars', $candidates)) . '</p>';
}
echo '</div>';

// Actions
echo '<div class="diag-section">';
echo '<h3>Actions</h3>';
echo '<p class="diag-actions">';
$sesskey = sesskey();
echo '<a href="?action=rerun&sesskey=' . $sesskey . '" class="warn">Re-run bootstrap now (--force)</a>';
echo '<a href="?action=deletemarker&sesskey=' . $sesskey . '" class="danger">Delete bootstrap markers</a>';
echo '<a href="' . new moodle_url('/local/twu/diag.php') . '">Refresh</a>';
echo '</p>';
echo '<p><small>"Re-run bootstrap now" executes the bootstrap script with --force and shows every line of output (including any PHP errors). This is the fastest way to see exactly which line is crashing.</small></p>';
echo '</div>';

echo $OUTPUT->footer();
