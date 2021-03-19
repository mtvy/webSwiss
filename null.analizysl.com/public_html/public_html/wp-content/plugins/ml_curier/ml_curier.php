<?php

/**
 * @package medlab
 * ml_curier.php
 */
/*
Plugin Name: MedLab Extension -- Curier
Plugin URI: 
Description: Расширение MedLab -- Курьеры
Version: 1.0
Author: wsd
Depends: Medical Laboratory (>= 1)
Author URI: my
License: GPLv2 or later
Text Domain: wsd
*/

    ini_set("display_errors", "1");
    ini_set("display_startup_errors", "1");
    ini_set('error_reporting', E_ALL);
    

include_once 'class.MLECurier.php';

global $mllg;
$mllg = new MLECurier();
add_action( 'init', array( $mllg, 'init' ) );


