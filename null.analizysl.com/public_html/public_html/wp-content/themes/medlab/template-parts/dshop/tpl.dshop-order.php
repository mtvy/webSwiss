<?php

/* 
 * tpl.dshop-order.php
 * tpl.dshop-order-order.php
 * tpl.dshop-order-items.php
 * tpl.dshop-order-item.php
 */


$rep=[];
//$rep['[pid]']=$pid;
//$rep['[cou]']=$cou;
//$rep['[min]']=0;
//$rep['[max]']=$max;
//$rep['[num]']=$num;
//$tpl_cart_item =
//dshop::_get_tpl('template-parts/page/tpl.dshop-cart','item',$rep);
echo dshop::_get_order_order();
echo dshop::_get_order_items();
echo dshop::_get_order_payments();