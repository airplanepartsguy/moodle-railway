<?php
// Admin tree registration — adds TurbineWorks Reports under Site Admin → Reports.

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig || has_capability('local/twu:viewreports', context_system::instance())) {
    $ADMIN->add('reports', new admin_category('twu_reports_cat', 'TurbineWorks Reports'));

    $ADMIN->add('twu_reports_cat', new admin_externalpage(
        'twu_report_index',
        'Reports Index',
        new moodle_url('/local/twu/report.php'),
        'local/twu:viewreports'
    ));
    $ADMIN->add('twu_reports_cat', new admin_externalpage(
        'twu_report_completion',
        'Completion Roster',
        new moodle_url('/local/twu/report.php', ['r' => 'completion']),
        'local/twu:viewreports'
    ));
    $ADMIN->add('twu_reports_cat', new admin_externalpage(
        'twu_report_recurring',
        'Recurring Training Due Tracker',
        new moodle_url('/local/twu/report.php', ['r' => 'recurring']),
        'local/twu:viewreports'
    ));
    $ADMIN->add('twu_reports_cat', new admin_externalpage(
        'twu_report_certifications',
        'Certification Expiry Roster',
        new moodle_url('/local/twu/report.php', ['r' => 'certifications']),
        'local/twu:viewreports'
    ));
    $ADMIN->add('twu_reports_cat', new admin_externalpage(
        'twu_report_quiz_attempts',
        'Failed Quiz Attempts',
        new moodle_url('/local/twu/report.php', ['r' => 'quiz_attempts']),
        'local/twu:viewreports'
    ));
}
