<?php

/* 
 * access_menu.php
 */

/*
Plugin Name: Access menu
Plugin URI: 
Description: Расширение функционала отображения, позволяет скрывать пунктам меню, к страницам страницам которых, пользователь не имеет доступа.
Version: 1.0
Depends: access_control (>= 1)
Author: me
Author URI: my
License: GPLv2 or later
Text Domain: dshop
*/

//=============================

//define( 'PLAGINS_DIR', plugin_dir_path( plugin_dir_path( __FILE__ ) ) );
//include_once PLAGINS_DIR.'dshop/trait.DShopHtml.php';
include_once 'class.AM.php';
//include_once 'class.ACAdmin.php';
global $am;
$am = new AM();
add_action( 'init', array( $am, 'init' ) );

