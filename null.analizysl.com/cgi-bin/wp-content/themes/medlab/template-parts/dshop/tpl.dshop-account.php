<?php

/* 
 * tpl.dshop-account.php
 */

if(is_user_logged_in()){
    $tpl_name='ml-v2';
    $tpl_name='';
    $tpl_name = apply_filters('ds_styling_tpl_name', $tpl_name);
    echo dshop::_get_account_fields($tpl_name);
    echo dshop::_get_account_orders();
}