<?php

/* 
 * tpl.dshop-item.php
 */

$ServiceId = filter_input(INPUT_GET, 'sid', FILTER_SANITIZE_NUMBER_INT);
if($ServiceId===false || $ServiceId===null|| $ServiceId==='')$ServiceId='0';
$_ServiceId = filter_input(INPUT_POST, 'sid', FILTER_SANITIZE_NUMBER_INT);
if(strlen($_ServiceId)>0)$ServiceId=$_ServiceId;

$productId = filter_input(INPUT_GET, 'pid', FILTER_SANITIZE_NUMBER_INT);
if($productId===false || $productId===null|| $productId==='')$productId='0';
$_productId = filter_input(INPUT_POST, 'pid', FILTER_SANITIZE_NUMBER_INT);
if(strlen($_productId)>0)$productId=$_productId;

$_productId = filter_input(INPUT_POST, 'product', FILTER_SANITIZE_NUMBER_INT);
if(strlen($_productId)>0)$productId=$_productId;

ob_start();
$out=  ob_get_clean();
if(strlen($out)>0){
    add_log($out);
}


$tpl_file_name = 'tpls/tpl-ml--lp--default.php';
$dir = basename(__FILE__);
if(file_exists(__DIR__.'/'.$tpl_file_name))
    include $tpl_file_name; //  list-price

$tpl_name='';
$tpl_name = apply_filters('ds_styling_tpl_name', $tpl_name);
//include 'tpls/tpl-ml--lp--ml-v-2.php'; //  list-price
$tpl_file_name = 'tpls/tpl-ml--lp--'.$tpl_name.'.php'; 
$dir = basename(__FILE__);
if(file_exists(__DIR__.'/'.$tpl_file_name))
    include $tpl_file_name; //  list-price

if ( current_user_can( 'manage_options' ) ) {
    include 'tpl.dshop-item-ml-desc.php';
}
