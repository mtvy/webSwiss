<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$tpl_name='ml-v2';
$tpl_name='';
$tpl_name = apply_filters('ds_styling_tpl_name', $tpl_name);
echo dshop::_get_checkout_order($tpl_name);
echo dshop::_get_checkout_cart_items();