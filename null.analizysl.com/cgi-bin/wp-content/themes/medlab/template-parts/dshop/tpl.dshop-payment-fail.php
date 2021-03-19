<?php

/* 
 * tpl.dshop-payment-fail.php
 * 
 * https://docs.robokassa.ru/#1268
 * 
 * OutSum
 * InvId
 * Culture
 * 
 * 
 */


$inv_id = @$_REQUEST["InvId"];
echo "Вы отказались от оплаты. Заказ# $inv_id<br/>\n";
echo "You have refused payment. Order# $inv_id\n";