<?php
/**
 * @package medlab
 */
/*
Plugin Name: Medical Laboratory
Plugin URI: 
Description: позволяет врачам и пациентам, взаимодействовать с лабораториями и результатами анализов
Version: 1.0
Author: me
Author URI: my
License: GPLv2 or later
Text Domain: wsd
*/

//    ini_set("display_errors", "1");
//    ini_set("display_startup_errors", "1");
//    ini_set('error_reporting', E_ALL);
//    

//define('TIMER_LOG', false);
if(! defined('TIMER_LOG'))define('TIMER_LOG', true);
//define('DICT_SAVE_LOG', false);
if(! defined('DICT_SAVE_LOG'))define('DICT_SAVE_LOG', true);
define( 'MEDLAB__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define('MLBARCODELOADS',ABSPATH.'wp-content/uploads/ml_order_bar_code/');

require_once( MEDLAB__PLUGIN_DIR . 'common.php' );
require_once( MEDLAB__PLUGIN_DIR . 'connect.php' );
//require_once( MEDLAB__PLUGIN_DIR . 'ccab_mail.php' );
require_once( MEDLAB__PLUGIN_DIR . 'class.MedLabInit.php' );
require_once( MEDLAB__PLUGIN_DIR . 'class.MedLab.php' );
require_once( MEDLAB__PLUGIN_DIR . 'class.MedLabAdmin.php' );
require_once( MEDLAB__PLUGIN_DIR . 'class.MedLabXmlDesc.php' );
require_once( MEDLAB__PLUGIN_DIR . 'class.MLReferral.php' );

require_once( MEDLAB__PLUGIN_DIR . 'class.MLWDashboardStatDict.php' );


add_action( 'wp_ajax_medlab', ['MedLab', 'ajax'] );
// wp_ajax_{ЗНАЧЕНИЕ ПАРАМЕТРА ACTION!!}
add_action( 'wp_ajax_nopriv_medlab', ['MedLab', 'ajax'] );
// wp_ajax_nopriv_{ЗНАЧЕНИЕ ACTION!!}
// первый хук для авторизованных, второй для не авторизованных пользователей

add_action( 'init', array( 'MedLab', 'init' ) );
add_action( 'init', array( 'MedLabXmlDesc', '_init' ) );

//add_filter('the_content', [ 'MedLab', '_content']);

$DShopAdmin = new MedLabAdmin(); 
$mlr = new MLReferral();
$mlwdsd = new MLWDashboardStatDict();