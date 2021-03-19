<?php

/* 
 * tpl.dshop-order-edit.php
 * tpl.dshop-order-order.php
 * tpl.dshop-order-items-edit.php
 * tpl.dshop-order-item.php
 * [ds_page page="order" type="edit" old=""]
 */


$rep=[];
//$rep['[pid]']=$pid;
//$rep['[cou]']=$cou;
//$rep['[min]']=0;
//$rep['[max]']=$max;
//$rep['[num]']=$num;
//$tpl_cart_item =
//dshop::_get_tpl('template-parts/page/tpl.dshop-cart','item',$rep);
//echo dshop::_get_order_order();
//echo dshop::_get_order_items();
//echo dshop::_get_order_payments();
        get_template_part( 'template-parts/dshop/tpl.dshop-order', 'order-edit' );
        get_template_part( 'template-parts/dshop/tpl.dshop-order', 'items-edit' );