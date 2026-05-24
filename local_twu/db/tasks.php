<?php
// Scheduled tasks for the local_twu plugin.
// Registered with Moodle on plugin install/upgrade.

defined('MOODLE_INTERNAL') || die();

$tasks = [
    [
        // Daily 6-month recurring training reset. Scans Initial Training course
        // completions; for any user whose completion is older than 180 days,
        // resets their progress in that course so they must re-complete.
        'classname' => 'local_twu\\task\\recurring_reset',
        'blocking'  => 0,
        'minute'    => '7',
        'hour'      => '2',
        'day'       => '*',
        'month'     => '*',
        'dayofweek' => '*',
    ],
];
