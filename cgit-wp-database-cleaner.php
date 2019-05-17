<?php

/*

Plugin Name: Castlegate IT WP Database Cleaner
Plugin URI: https://github.com/castlegateit/cgit-wp-database-cleaner
Description: Remove redundant database records.
Version: 0.2
Author: Castlegate IT
Author URI: https://www.castlegateit.co.uk/
License: AGPLv3
License URI: https://www.gnu.org/licenses/agpl.txt
Network: true

Copyright (c) 2019 Castlegate IT. All rights reserved.

*/

if (!defined('ABSPATH')) {
    wp_die('Access denied');
}

require_once __DIR__ . '/classes/autoload.php';

$plugin = new \Cgit\DatabaseCleaner\Plugin(__FILE__);

do_action('cgit_database_cleaner_plugin', $plugin);
do_action('cgit_database_cleaner_loaded');
