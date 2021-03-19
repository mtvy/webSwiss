<?php

/* 
 * access_control.php
 */

/*
Plugin Name: Access control
Plugin URI: 
Description: Расширение функционала администрирования, позволяет распределять доступ к страницам, между ролями
Version: 1.0
Depends: DShop (>= 1)
Author: me
Author URI: my
License: GPLv2 or later
Text Domain: dshop
*/

//=============================

define( 'PLAGINS_DIR', plugin_dir_path( plugin_dir_path( __FILE__ ) ) );
include_once PLAGINS_DIR.'dshop/trait.DShopHtml.php';
include_once 'class.AC.php';
include_once 'class.ACAdmin.php';
global $ac,$aca;
$ac = new AC();
$aca = new ACAdmin();
add_action( 'init', array( $ac, 'init' ) );
add_action( 'init', array( $aca, 'init' ) );


add_filter( 'plugin_action_links', 'ac_settings_link', 10, 2 );

function ac_settings_link( $actions, $plugin_name ){
    if( false === strpos( $plugin_name, basename(__FILE__) ) )
        return $actions;

//    $settings_link = '<a href="options-general.php?page='.
//         basename(dirname(__FILE__)).'/settings.php' .'">'.__("Settings").'</a>'; 
//    $settings_link = '<a href="options-general.php?page='.
//         basename(dirname(__FILE__)).'.php' .'">'.__("Settings").'</a>'; 
//    $settings_link = '<a href="admin.php?page='.
//         basename(dirname(__FILE__)).'.php' .'">'.__("Settings").'</a>'; 
//    $settings_link = '<a href="edit.php?post_type=dsorder&page=ac' .'">'.__("Settings").'</a>'; 
    $settings_link = '<a href="edit.php?page=ac' .'">'.__("Settings").'</a>';
    
//    http://analizysl.com/wp-admin/edit.php?post_type=dsorder&page=dsorder_extml_settings.php
    array_unshift( $actions, $settings_link ); 
    return $actions;
}

//
//global $ht;
//$roles = get_option('wp_user_roles',[]);
//$this->n($ht->pre($roles));