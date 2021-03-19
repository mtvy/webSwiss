<?php

/* 
 * @package medlab
 * medlab_ext_lab_groups.php
 */
/*
Plugin Name: MedLab Extension Laborants Groups
Plugin URI: 
Description: управление группами лаборантов
Version: 1.0
Author: wsd
Depends: Medical Laboratory (>= 1)
Author URI: my
License: GPLv2 or later
Text Domain: wsd
*/

/*
 * class.MLENrGen.php
 */

include_once 'class.MLELabGroups.php';

global $mllg;
$mllg = new MLELabGroups();
add_action( 'init', array( $mllg, 'init' ) );
