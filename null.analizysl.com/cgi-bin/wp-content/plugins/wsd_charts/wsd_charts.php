<?php

/* 
 * @package wsd
 * wsd_charts.php
 */
/*
Plugin Name: WSD Charts
Plugin URI: 
Description: Charts
Version: 1.0
Author: WSD
Author URI: webstudiodreams.com
License: GPLv2 or later
Text Domain: wsd
*/

ini_set("display_errors", "1");
ini_set("display_startup_errors", "1");
ini_set('error_reporting', E_ALL);

include_once 'class.WSDCharts.php';

global $wsdchart;
$wsdchart = new WSDCharts();
add_action( 'init', array( $wsdchart, 'init' ) );


