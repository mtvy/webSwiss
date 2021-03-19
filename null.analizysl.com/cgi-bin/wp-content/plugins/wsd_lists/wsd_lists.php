<?php

/**
 * @package WSD
 * wsd_lists.php
 */
/*
Plugin Name: WSD -- Lists
Plugin URI: 
Description: WSD Lists -- Списки данных в отдельных таблицах. 
Version: 1.0
Author: wsd
Author URI: wsd
License: GPLv2 or later
Text Domain: wsd
*/

define( 'WSD_LISTS_DIR', plugin_dir_path( __FILE__ ) );
    ini_set("display_errors", "1");
    ini_set("display_startup_errors", "1");
    ini_set('error_reporting', E_ALL);
    

include_once 'lists/class.WSDLists.php';
include_once 'list/class.WSDList.php';

global $wsd_lists;
$wsd_lists = new WSDLists();
add_action( 'init', array( $wsd_lists, 'init' ) );


add_shortcode('wsd_list_curier_cargo_doc-orders',[$wsd_lists, 'shortcode_reserve']);
add_shortcode('wsd_list_curier_cargo_doc-blank',[$wsd_lists, 'shortcode_reserve']);

