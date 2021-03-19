<?php

/* 
 * mail-register_ok
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
    $message_body .= '<p>Вы успешно зарегистрировались</p>' . "\n";
    $message_body .= '<p>Пользователь: __user_l_name__ __user_f_name__ __user_s_name__</p>' . "\n";
    $message_body .= '<p>Login: __user_login__</p>' . "\n";
    $message_body .= '<p>Pass: __user_pass__</p>' . "\n";
//    $message_body .= '<p>Телефон: __user_phone__</p>' . "\n";
//    $message_body .= '<p>В качестве логина используйте ваш номер телефона.</p>' . "\n";
//    $message_body .= '<p>Ваш аккаунт ожидает подтверждения администрацией.</p>' . "\n";
/*========================================*/
echo $message_body;