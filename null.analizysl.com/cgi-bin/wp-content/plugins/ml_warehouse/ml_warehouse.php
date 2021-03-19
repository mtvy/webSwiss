<?php

/* 
 * @package WSD
 * ml_warehouse.php
 */
/*
Plugin Name: Med Lab -- Warehouse
Plugin URI: 
Description: MedLab Warehouse -- Складской учёт
Version: 1.0
Author: wsd
Author URI: wsd
License: GPLv2 or later
Text Domain: wsd
*/

    ini_set("display_errors", "1");
    ini_set("display_startup_errors", "1");
    ini_set('error_reporting', E_ALL);
    
define( 'MLWAREHOUSE_DIR', plugin_dir_path( __FILE__ ) );
define( 'MLWAREHOUSE_URL', plugin_dir_url( __FILE__ ) );

/* 
 * class.MLWarehouse.php
 */
require_once( MLWAREHOUSE_DIR . 'class.MLWarehouse.php' );
require_once( MLWAREHOUSE_DIR . 'download_xml_wh_report.php' );

global $ml_warehouse;
$ml_warehouse = new MLWarehouse();
add_action( 'init', array( $ml_warehouse, 'init' ) );


add_shortcode('wh_report',[$ml_warehouse, 'page_product_report']);
add_shortcode('ml_warehouse_product_getting',[$ml_warehouse, 'page_product_getting']);
add_shortcode('ml_warehouse_product_shipment',[$ml_warehouse, 'page_product_shipment']);
add_shortcode('wh_waybill__bill',[$ml_warehouse, 'page_waybill__bill']);
//add_shortcode('wsd_dbconst',[$wsd_dbconst, 'show_map']);
//add_shortcode('wsd_dbconst',[$wsd_lists, 'shortcode_reserve']);
//add_shortcode('wsd_list_curier_cargo_doc-blank',[$wsd_lists, 'shortcode_reserve']);

add_action( 'wp_ajax_ml_warehouse', [$ml_warehouse, 'ajax'] );
// wp_ajax_{ЗНАЧЕНИЕ ПАРАМЕТРА ACTION!!}
add_action( 'wp_ajax_nopriv_ml_warehouse', [$ml_warehouse, 'ajax_noname'] );
// wp_ajax_nopriv_{ЗНАЧЕНИЕ ACTION!!}
// первый хук для авторизованных, второй для не авторизованных пользователей