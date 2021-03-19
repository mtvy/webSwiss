<?php

/* 
 * mail-order-new.php
 */

$message_body='';
$lang='en';
$lang='ru';
/*========================================*/
//    switch($lang){case'en':$n='Subject';break;case'ru':$n='Тема';break;}
//    $message_body .= '<p>'.$n.': Регистрация успешна</p>' . "\n" . '<br>' . "\n";
//    $message_body .= '<p>фио: ' . $_POST['modal-name'] . '</p>' . "\n";
//    $message_body .= '<p>гражданство: ' . $_POST['modal-citizenship'] . '</p>' . "\n";
//    $message_body .= '<p>год рождения: ' . $_POST['modal-year'] . '</p>' . "\n";
//    $message_body .= '<p>телефон: ' . $_POST['modal-phone'] . '</p>' . "\n";
    
//    $message_body .= '<p>Имя: ' . $_POST['modal-phone'] . '</p>' . "\n";
//    $message_body .= '<p>Телефон: ' . $_POST['modal-phone'] . '</p>' . "\n";
    $message_body .= '<p>Поступил новый заказ:</p>' . "\n";
    $message_body .= '<p>№ заказа: __order_id__</p>' . "\n";
    $message_body .= '<p>Сумма: __order_summ__ __currency__</p>' . "\n";
    $message_body .= '<p>Количество позиций: __count__</p>' . "\n";
    $message_body .= '<p>Количество единиц: __items_count__</p>' . "\n";
//    $message_body .= '<p>Телефон: __user_phone__</p>' . "\n";
//    $message_body .= '<p>В качестве логина используйте ваш номер телефона.</p>' . "\n";
//    $message_body .= '<p>Ваш аккаунт ожидает подтверждения администрацией.</p>' . "\n";
/*========================================*/
echo $message_body;