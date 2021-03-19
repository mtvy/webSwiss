<?php

/**
 * @package medlab
 * ml_laboratory.php
 */
/*
Plugin Name: MedLab Extension -- Laboratory
Plugin URI: 
Description: Расширение MedLab -- Лаборатории
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
    

include_once 'class.MLELaboratory.php';

global $mllg;
$mllg = new MLELaboratory();
add_action( 'init', array( $mllg, 'init' ) );


