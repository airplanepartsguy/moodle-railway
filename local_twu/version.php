<?php
// TurbineWorks University local plugin — bootstraps the ASA-100 course
// structure (categories, courses, cohorts, site name) on first deploy.

defined('MOODLE_INTERNAL') || die();

$plugin->version   = 2026052401;
$plugin->requires  = 2024100100; // Moodle 4.5 LTS
$plugin->component = 'local_twu';
$plugin->maturity  = MATURITY_STABLE;
$plugin->release   = '1.0.0';
