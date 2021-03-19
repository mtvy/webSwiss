<?php

/* 
 * medlab
 * medlab/ml_cron.php
 */


define( 'DOING_CRON', true );

if ( ! defined( 'ABSPATH' ) ) {
	/** Set up WordPress environment */
	require_once( dirname( dirname( __FILE__ ) ) . '/wp-load.php' );
}
//global $wpdb;
//$row = $wpdb->get_row( $wpdb->prepare( "SELECT option_value FROM $wpdb->options WHERE option_name = %s LIMIT 1", '_transient_doing_cron' ) );
//if ( is_object( $row ) ) {
//    $value = $row->option_value;
//}
//wp_reschedule_event();

$gmt_time_start = microtime( true );
//save_log('medlab_cron ','init_cron','info','ml_cron');
do_action('medlab_cron_start',['start'=>$gmt_time_start]);
do_action('medlab_delivery_report_send');
$gmt_time_end = microtime( true );
do_action('medlab_cron_finish',['start'=>$gmt_time_start,'end'=>$gmt_time_end]);