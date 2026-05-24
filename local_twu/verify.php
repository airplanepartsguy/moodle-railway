<?php
// Public certificate verification landing page.
//
// Accessible at /local/twu/verify.php?code=XXXX with no login required.
// Designed for ASA inspectors, customer auditors, and anyone holding a
// physical TurbineWorks University certificate who needs to confirm
// authenticity and current validity status.
//
// Shows richer information than mod_customcert's default verify page:
//   - Recipient name + department + role (if profile fields populated)
//   - Course name + certificate type
//   - Original issuance date + 180-day validity expiry
//   - Current completion status from {course_completions} (so an auditor
//     can see "expired due to 6-month recurring" vs "currently valid")
//   - Audit context paragraph

require(__DIR__ . '/../../config.php');

$code = optional_param('code', '', PARAM_ALPHANUM);

// This page is public — no require_login(). Set context to system.
$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/twu/verify.php', $code ? ['code' => $code] : []));
$PAGE->set_pagelayout('embedded'); // clean view without site nav clutter
$PAGE->set_title('Certificate Verification | TurbineWorks University');
$PAGE->set_heading('Certificate Verification');

echo $OUTPUT->header();

// Brand header
echo '<style>
.twu-card { max-width:780px; margin:30px auto; padding:0; border:1px solid #ddd; border-radius:8px; background:#fff; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.05); }
.twu-cardhead { background:#0d2240; color:#fff; padding:24px 32px; }
.twu-cardhead h1 { color:#fff; margin:0; font-size:1.6em; }
.twu-cardhead .sub { color:#ffc800; margin:6px 0 0 0; font-size:0.95em; letter-spacing:0.5px; text-transform:uppercase; }
.twu-cardbody { padding:24px 32px; }
.twu-status { padding:14px 18px; border-radius:6px; margin:18px 0; font-size:1.05em; }
.twu-status.valid { background:#e8f5e9; border-left:4px solid #2e7d32; color:#1b5e20; }
.twu-status.expired { background:#fff3cd; border-left:4px solid #ff9800; color:#8a6d00; }
.twu-status.notfound { background:#ffebee; border-left:4px solid #c62828; color:#7f0000; }
.twu-status strong { display:block; font-size:1.15em; margin-bottom:4px; }
.twu-detail { width:100%; border-collapse:collapse; margin:18px 0; }
.twu-detail th, .twu-detail td { padding:10px 14px; border-bottom:1px solid #eee; vertical-align:top; text-align:left; }
.twu-detail th { color:#555; font-weight:600; width:35%; background:#f7f8fa; }
.twu-detail td { color:#222; }
.twu-detail code { background:#f4f6fa; padding:3px 8px; border-radius:3px; font-size:0.95em; color:#0d2240; }
.twu-cardfoot { background:#f7f8fa; padding:18px 32px; font-size:0.85em; color:#666; border-top:1px solid #eee; }
.twu-form { margin:18px 0; }
.twu-form input { padding:10px 14px; border:1px solid #ccc; border-radius:4px; font-size:1em; width:300px; }
.twu-form button { padding:10px 22px; background:#0d2240; color:#fff; border:0; border-radius:4px; font-size:1em; cursor:pointer; }
</style>';

echo '<div class="twu-card">';
echo '<div class="twu-cardhead">';
echo '<h1>Certificate Verification</h1>';
echo '<div class="sub">TurbineWorks University — ASA-100 Compliance Training</div>';
echo '</div>';
echo '<div class="twu-cardbody">';

if (!$code) {
    // No code → show the lookup form
    echo '<p>Enter the verification code printed on the certificate to confirm its authenticity and current validity.</p>';
    echo '<form method="get" class="twu-form" action="' . (new moodle_url('/local/twu/verify.php')) . '">';
    echo '<input type="text" name="code" placeholder="Verification code (e.g. ABC123DEF4)" autofocus required pattern="[A-Za-z0-9]+">';
    echo '<button type="submit">Verify</button>';
    echo '</form>';
    echo '<p style="font-size:0.9em; color:#666; margin-top:24px;">The verification code is a 10-character alphanumeric string printed near the bottom of the certificate PDF.</p>';
} else {
    // Look up the certificate issue by code
    $issue = $DB->get_record_sql("
        SELECT ci.id AS issueid, ci.code, ci.timecreated AS issued, ci.userid,
               cc.id AS customcertid, cc.name AS certname,
               c.id AS courseid, c.fullname AS coursename, c.shortname, c.idnumber,
               u.firstname, u.lastname, u.username, u.email, u.deleted
          FROM {customcert_issues} ci
          JOIN {customcert} cc ON cc.id = ci.customcertid
          JOIN {course_modules} cm ON cm.instance = cc.id
          JOIN {modules} m ON m.id = cm.module AND m.name = 'customcert'
          JOIN {course} c ON c.id = cm.course
          JOIN {user} u ON u.id = ci.userid
         WHERE ci.code = :code",
        ['code' => $code]
    );

    if (!$issue || $issue->deleted) {
        echo '<div class="twu-status notfound">';
        echo '<strong>Certificate not found</strong>';
        echo 'The verification code <code>' . s($code) . '</code> does not match any certificate on record. ';
        echo 'Possible causes: the code was transcribed incorrectly, the certificate is fabricated, or the recipient\'s account has been removed.';
        echo '</div>';
        echo '<p><a href="' . (new moodle_url('/local/twu/verify.php')) . '">← Try another code</a></p>';
    } else {
        // Get the user's current course completion status
        $completion = $DB->get_record('course_completions', [
            'userid' => $issue->userid,
            'course' => $issue->courseid,
        ]);

        // Get profile fields
        $profiledata = $DB->get_records_sql("
            SELECT uif.shortname, uid.data
              FROM {user_info_data} uid
              JOIN {user_info_field} uif ON uif.id = uid.fieldid
             WHERE uid.userid = :uid
               AND uif.shortname IN ('twu_department', 'twu_jobtitle',
                   'twu_employeenum', 'twu_hiredate', 'twu_supervisor')",
            ['uid' => $issue->userid]
        );
        $profile = [];
        foreach ($profiledata as $row) {
            $profile[$row->shortname] = $row->data;
        }

        $now = time();
        $issuedate = (int)$issue->issued;
        $cycleexpiry = $issuedate + (180 * DAYSECS);

        // Determine status from BOTH cert issuance AND current course completion
        $lastcompletion = $completion ? (int)$completion->timecompleted : 0;
        $completionage = $lastcompletion ? $now - $lastcompletion : null;

        if ($lastcompletion && $completionage < (180 * DAYSECS)) {
            $status = 'current';
            $statuslabel = 'CURRENT';
            $statusmsg = 'This certificate is current. The recipient has a verified course completion within the past 180 days. The 6-month recurring training cadence is on track.';
            $statusclass = 'valid';
        } else if ($lastcompletion) {
            $status = 'recurring_due';
            $statuslabel = 'RECURRING TRAINING DUE';
            $statusmsg = 'The most recent verified completion is more than 180 days old. The recipient is due for the 6-month recurring training cycle. The original training event is historically valid; the certificate is no longer within its current-validity window.';
            $statusclass = 'expired';
        } else {
            // Completion record absent — was reset by the recurring task
            $status = 'expired';
            $statuslabel = 'EXPIRED — RECURRING RESET';
            $statusmsg = 'The completion record for this certificate has been reset on the 6-month recurring cycle and the recipient has not yet re-completed the training. The historical completion is preserved in the audit log; the certificate is no longer within its current-validity window.';
            $statusclass = 'expired';
        }

        echo '<div class="twu-status ' . $statusclass . '">';
        echo '<strong>Status: ' . $statuslabel . '</strong>';
        echo $statusmsg;
        echo '</div>';

        // Recipient details
        echo '<table class="twu-detail">';
        echo '<tr><th>Recipient</th><td>' . s(fullname($issue)) . '</td></tr>';
        if (!empty($profile['twu_jobtitle'])) {
            echo '<tr><th>Job Title</th><td>' . s($profile['twu_jobtitle']) . '</td></tr>';
        }
        if (!empty($profile['twu_department'])) {
            echo '<tr><th>Department</th><td>' . s($profile['twu_department']) . '</td></tr>';
        }
        if (!empty($profile['twu_employeenum'])) {
            echo '<tr><th>Employee Number</th><td>' . s($profile['twu_employeenum']) . '</td></tr>';
        }
        if (!empty($profile['twu_hiredate']) && is_numeric($profile['twu_hiredate'])) {
            echo '<tr><th>Hire Date</th><td>' . userdate((int)$profile['twu_hiredate'], '%B %e, %Y') . '</td></tr>';
        }

        echo '<tr><th colspan="2" style="background:#0d2240; color:#fff; padding:8px 14px; font-size:0.9em;">CERTIFICATE</th></tr>';
        echo '<tr><th>Certificate Type</th><td>' . s($issue->certname) . '</td></tr>';
        echo '<tr><th>Course</th><td>' . s($issue->coursename) . ' <small>(' . s($issue->idnumber) . ')</small></td></tr>';
        echo '<tr><th>Issued</th><td>' . userdate($issuedate, '%B %e, %Y at %H:%M') . '</td></tr>';
        echo '<tr><th>Initial Validity Period</th><td>180 days (until ' . userdate($cycleexpiry, '%B %e, %Y') . ')</td></tr>';
        if ($lastcompletion) {
            echo '<tr><th>Most Recent Completion</th><td>' . userdate($lastcompletion, '%B %e, %Y') . '</td></tr>';
        } else {
            echo '<tr><th>Most Recent Completion</th><td><em>Reset by recurring task; recipient has not yet re-completed.</em></td></tr>';
        }
        echo '<tr><th>Verification Code</th><td><code>' . s($issue->code) . '</code></td></tr>';
        echo '</table>';

        // Audit context
        echo '<div style="background:#f7f8fa; border-left:4px solid #ffc800; padding:14px 18px; margin:20px 0; font-size:0.9em;">';
        echo '<strong>For auditors and inspectors:</strong> ';
        echo 'This page is the public verification endpoint for TurbineWorks University ';
        echo '(<code>' . s($CFG->wwwroot) . '/local/twu/verify.php</code>). ';
        echo 'The data shown is read directly from the Moodle database — courses, ';
        echo 'certificate issuance, course completion records, and user profile fields. ';
        echo 'No data is cached or filtered. Refresh this page to see current status at any time.';
        echo '</div>';

        echo '<p><a href="' . (new moodle_url('/local/twu/verify.php')) . '">← Verify another certificate</a></p>';
    }
}

echo '</div>'; // cardbody
echo '<div class="twu-cardfoot">';
echo 'TurbineWorks University is the compliance training platform supporting TurbineWorks\' ASA-100 accreditation. ';
echo 'Certificates record successful completion of training modules and pass-grade quiz attempts. ';
echo 'For questions about a specific certificate or about the training program: ';
echo '<a href="mailto:quality@turbineworks.com">quality@turbineworks.com</a>.';
echo '</div>';
echo '</div>'; // card

echo $OUTPUT->footer();
