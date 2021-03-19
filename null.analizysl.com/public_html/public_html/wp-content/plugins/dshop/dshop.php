<?php
/**
 * @package dshop
 */
/*
Plugin Name: DShop
Plugin URI: 
Description: Функционал магазина, корзина, заказы, ЛК юзера, администрирование заказов
Version: 1.0
Author: me
Author URI: my
License: GPLv2 or later
Text Domain: dshop
*/

ini_set("display_errors", "1");
ini_set("display_startup_errors", "1");
ini_set('error_reporting', E_ALL);
if(session_status() != PHP_SESSION_ACTIVE){
    session_start();
}

add_filter( 'plugin_action_links', 'ds_settings_link', 10, 2 );

function ds_settings_link( $actions, $plugin_name ){
    if( false === strpos( $plugin_name, basename(__FILE__) ) )
        return $actions;

//    $settings_link = '<a href="options-general.php?page='.
//         basename(dirname(__FILE__)).'/settings.php' .'">'.__("Settings").'</a>'; 
//    $settings_link = '<a href="options-general.php?page='.
//         basename(dirname(__FILE__)).'.php' .'">'.__("Settings").'</a>'; 
    $settings_link = '<a href="admin.php?page='.
         basename(dirname(__FILE__)).'.php' .'">'.__("Settings").'</a>'; 
    array_unshift( $actions, $settings_link ); 
    return $actions;
}


define( 'DSHOP_DIR', plugin_dir_path( __FILE__ ) );

require_once( DSHOP_DIR . 'dev.php' );
require_once( DSHOP_DIR . 'class.DShop.php' );
require_once( DSHOP_DIR . 'class.WSDHtmlBuild.php' );

require_once( DSHOP_DIR . 'ccab_mail.php' );
require_once( DSHOP_DIR . 'lend.php' );
//require_once( DSHOP_DIR . 'class.DShopCart.php' );
require_once( DSHOP_DIR . 'class.DShopOrder.php' );
require_once( DSHOP_DIR . 'class.DShopProduct.php' );
require_once( DSHOP_DIR . 'class.DShopProductAdmin.php' );
require_once( DSHOP_DIR . 'class.DShopPayment.php' );
require_once( DSHOP_DIR . 'class.DSPaymentAdmin.php' );
require_once( DSHOP_DIR . 'class.DShopOrderItem.php' );
require_once( DSHOP_DIR . 'class.DShopAdmin.php' );
require_once( DSHOP_DIR . 'class.DSPaymentAdmin.php' );
require_once( DSHOP_DIR . 'class.DShopPayments.php' );
//require_once( DSHOP_DIR . 'class.DSPRobokassa.php' );
//require_once( DSHOP_DIR . 'class.DSPCash.php' );
require_once( DSHOP_DIR . 'class.DShopWidgetCartBtn.php' );
require_once( DSHOP_DIR . 'admin_profile.php' );
require_once( DSHOP_DIR . 'class.DSDiscont.php' );

//require_once( DSHOP_DIR . 'class.NewsWidgetTopLine.php' );

add_action( 'init', array( 'DShop', 'init' ) );
//add_action( 'init', array( 'DShopProduct', '_init' ) );
add_action( 'widgets_init', ['DShop','register_wgts_area'] );

function dshop_register_widget() {
//    register_widget( 'NewsWidgetTopLine' );
    register_widget( 'DShopWidgetCartBtn' );
}
add_action( 'widgets_init', 'dshop_register_widget' ); // widget

//add_shortcode('allegro_shop_catalog',['Allegro', 'shortcode']);
add_shortcode('ds_cart',['DShop', 'shortcode']);
add_shortcode('ds_my_accaunt',['DShop', 'shortcode']);
add_shortcode('ds_checkout',['DShop', 'shortcode']);
add_shortcode('ds_order',['DShop', 'shortcode']);
add_shortcode('ds_payment_success',['DShop', 'shortcode']);
add_shortcode('ds_payment_fail',['DShop', 'shortcode']);
add_shortcode('ds_payment_result',['DShop', 'shortcode']);
add_shortcode('ds_item',['DShop', 'shortcode']);
add_shortcode('ds_page',['DShop', 'shortcode']);
add_shortcode('dshop',['DShop', 'shortcode']);


add_action( 'wp_ajax_dscart', ['DShop', 'ajax'] );
// wp_ajax_{ЗНАЧЕНИЕ ПАРАМЕТРА ACTION!!}
add_action( 'wp_ajax_nopriv_dscart', ['DShop', 'ajax'] );
// wp_ajax_nopriv_{ЗНАЧЕНИЕ ACTION!!}
// первый хук для авторизованных, второй для не авторизованных пользователей

//add_action( 'after_setup_theme', ['DShop','_process'] );
add_action( 'init', ['DShop','_process'] );

add_action( 'after_setup_theme', 'testsendmail' );
function testsendmail(){
//    $test_email = get_option('ds_notification_test')=='1'; // тестирование отправки почты
//    if($test_email){
//        add_log('test - письмо отправлять');
//    }else{
//        add_log('test - письмо НЕ отправлять');
//    }
}

global $DShop;
global $DSDiscont;
$DShop = new DShop();
$DShopProduct = new DShopProduct();
$DShopProductAdmin = new DShopProductAdmin();
$lend = new DShopOrder();
$lend = new DShopOrderItem();
$DShopAdmin = new DShopAdmin();
$payment = new DShopPayment();
$payment = new DSPaymentAdmin();
$DSDiscont = new DSDiscont();

//add_action( 'init', array( $DShopProduct, 'init' ) );

$plugin_name = '';
if(function_exists(('get_plugin_data'))){
    $plugin_data = get_plugin_data( __FILE__ );
    $plugin_name = $plugin_data['Name'];
}
global $ht;
$ht = new WSDHtmlBuild($plugin_name);
//if(!defined('HT'))
//    define( 'HT', $ht );


// Include the main WooCommerce class.
if ( ! class_exists( 'WooCommerce' ) ) {
//	include_once dirname( __FILE__ ) . '/includes/class-woocommerce.php';
}
//if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
//    dirname( dirname( $file ) )
//    \wp-content\plugins\woocommerce\includes\class-wc-payment-gateways.php
//	include_once dirname( dirname( __FILE__ ) ) . '/woocommerce/includes/class-wc-payment-gateways.php';
//}
if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
//    echo 'WC_Payment_Gateway exists'.'<br>';
//    dirname( dirname( $file ) )
//    \wp-content\plugins\woocommerce\includes\class-wc-payment-gateways.php
//	include_once dirname( dirname( __FILE__ ) ) . '/woocommerce/includes/class-wc-payment-gateways.php';
}else{
//    echo 'WC_Payment_Gateway not fount'.'<br>';
}