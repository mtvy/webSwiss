<?php
/**
 * @package dshop
 */
/*
Plugin Name: DShop Extension MedLab
Plugin URI: 
Description: Расширение функционала магазина, корзина, заказы, ЛК юзера, администрирование заказов
Version: 1.0
Depends: DShop (>= 1)
Author: me
Author URI: my
License: GPLv2 or later
Text Domain: dshop
*/


/* 
 * dshop_ext_medlab.php
Provides: cpt-foo
 */



ini_set("display_errors", "1");
ini_set("display_startup_errors", "1");
ini_set('error_reporting', E_ALL);
if(session_status() != PHP_SESSION_ACTIVE){
    session_start();
}

add_filter( 'plugin_action_links', 'ds_ext_ml_settings_link', 10, 2 );

function ds_ext_ml_settings_link( $actions, $plugin_name ){
    if( false === strpos( $plugin_name, basename(__FILE__) ) )
        return $actions;

//    $settings_link = '<a href="options-general.php?page='.
//         basename(dirname(__FILE__)).'/settings.php' .'">'.__("Settings").'</a>'; 
//    $settings_link = '<a href="options-general.php?page='.
//         basename(dirname(__FILE__)).'.php' .'">'.__("Settings").'</a>'; 
//    $settings_link = '<a href="admin.php?page='.
//         basename(dirname(__FILE__)).'.php' .'">'.__("Settings").'</a>'; 
    $settings_link = '<a href="edit.php?post_type=dsorder&page=dsorder_extml_settings.php' .'">'.__("Settings").'</a>'; 
    
//    http://analizysl.com/wp-admin/edit.php?post_type=dsorder&page=dsorder_extml_settings.php
    array_unshift( $actions, $settings_link ); 
    return $actions;
}


define( 'DSHOP_EXT_ML_DIR', plugin_dir_path( __FILE__ ) );

//global $ds_ext_ml_admsetts;
//$ds_ext_ml_admsetts=null;
global $ds_ext_ml;
$ds_ext_ml = null;
if(defined('DSHOP_DIR') && file_exists(DSHOP_DIR.'trait.DShopAdminOptions.php')){
    require_once( DSHOP_EXT_ML_DIR . 'class.DShopExtensionMedLab.php' );
    $ds_ext_ml = new DShopExtensionMedLab();
    add_action( 'init', array( $ds_ext_ml, 'init' ) );
}

//                    \plugins
//                    \dshop\trait.DShopAdminOptions.php
if(defined('DSHOP_DIR') && file_exists(DSHOP_DIR.'trait.DShopAdminOptions.php')){
    require_once( DSHOP_EXT_ML_DIR . 'class.DSExtMedLabAdmin.php' ); // DSPaymentAdmin
    $ds_ext_ml_admsetts = new DSExtMedLabAdmin();
}


require_once( DSHOP_EXT_ML_DIR . 'class.WSDLogin.php' ); // DSPaymentAdmin
$wsd_login = new WSDLogin();