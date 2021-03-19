<?php

/* 
 * @package medlab
 * medlab_ext_nr_gen.php
 */
/*
Plugin Name: MedLab Extension Number Generator
Plugin URI: 
Description: генерирует и учитывает последовательные номера заказов
Version: 1.0
Author: me
Depends: Medical Laboratory (>= 1)
Author URI: my
License: GPLv2 or later
Text Domain: wsd
*/

/*
 * class.MLENrGen.php
 */

include_once 'class.MLENrGen.php';

global $mlng;
$mlng = new MLENrGen();
add_action( 'init', array( $mlng, 'init' ) );
