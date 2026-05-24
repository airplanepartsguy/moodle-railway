<?php
// TurbineWorks University local plugin — bootstraps the ASA-100 course
// structure (categories, courses, cohorts, site name) on first deploy.

defined('MOODLE_INTERNAL') || die();

$plugin->version   = 2026052404;
$plugin->requires  = 2024100100; // Moodle 4.5 LTS
$plugin->component = 'local_twu';
$plugin->maturity  = MATURITY_STABLE;
$plugin->release   = '1.3.0'; // adds rich public cert verification page, master cert variant
