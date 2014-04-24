<?php if(!defined('ABSPATH')) exit; // Exit if accessed directly
/*
Plugin Name: Easy Digital Downloads Plugin Template
Description: This is an Easy Digital Downloads plugin template
Author: Diego Zanella
Version: 0.1.0
*/

require_once('src/lib/classes/install/aelia-edd-requirementscheck.php');

// If requirements are not met, deactivate the plugin
if(Aelia_EDD_RequirementsChecks::factory()->check_requirements()) {
	require_once 'src/plugin-main.php';
}
