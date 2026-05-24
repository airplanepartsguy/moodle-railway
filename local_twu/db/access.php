<?php
// Capability definitions for the local_twu plugin.

defined('MOODLE_INTERNAL') || die();

$capabilities = [
    // Permission to view the TurbineWorks Reports admin pages.
    'local/twu:viewreports' => [
        'captype'      => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes'   => [
            'manager'        => CAP_ALLOW,
        ],
    ],
];
