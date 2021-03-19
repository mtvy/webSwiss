<?php

/* 
 * mail-new_user
 */

//                                    $atr['__user_url__']=$inputs['url'];
//                                    $atr['__user_email__']=$user['email'];
//                                    $atr['__user_company__']=$inputs['org_name'];
//                                    $atr['__user_phone__']=$user['phone'];

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
    $message_body .= '<p>Новый пользователь</p>' . "\n";
    $message_body .= '<p>Пользователь: __user_l_name__ __user_f_name__ __user_s_name__</p>' . "\n";
    $message_body .= '<p>Login: __user_login__</p>' . "\n";
//    $message_body .= '<p>Pass: __user_pass__</p>' . "\n";
//    $message_body .= '<p>Сайт: __user_url__</p>' . "\n";
//    $message_body .= '<p>Название компании: __user_company__</p>' . "\n";
//    $message_body .= '<p>Должность: __user_position__</p>' . "\n";
    $message_body .= '<p>Почта: __user_email__</p>' . "\n";
    $message_body .= '<p>Телефон: __user_phone__</p>' . "\n";
//    $message_body .= '<p>В качестве логина используйте ваш номер телефона.</p>' . "\n";
/*========================================*/
echo $message_body;