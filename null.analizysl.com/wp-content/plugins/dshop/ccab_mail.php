<?php

/* 
 * ccab_mail
 */

/**
 * 
 * @param type $name имя шаблона
 * @param array $atr подставляемые данные
 * @param array $to массив получателей
 * @param string|array $attachments Optional. Files to attach.
 * @return boolean результат отправки
 */
function ccab_get_mail($name='',$atr=array(),$to=array(), $attachments = array(),$subject = false){
//    ob_start();
//    $to = array($to);
    $out = '';
    $owner_email = 'support@webstudiodreams.com';
    $owner_email = 'info.cclean2017@gmail.com';//bz11
    $owner_email = 'home_work_mail@mail.ru';
//    $to[]=$owner_email;
//    $tpl = ccab_build_tpl($name,$atr,0,'mail_');
    ob_start();
//        get_template_part( 'template-parts/component/mail', 'register_ok' );
        get_template_part( 'template-parts/component/mail', $name );
    $out.=ob_get_clean();
//    $posts_count = wp_count_posts('bad_owner')->publish;
    $tpl = do_shortcode( $out );
    $tpl = strtr($tpl,$atr);
    $sub = 'Сообщение от посетителя вашего сайта.';
    $ft='nono';
    if (isset($_POST['form-type']) and $_POST['form-type'] != '') {
        $ft=$_POST['form-type'];
    //    switch($lang){
    //        case'en':$n='Target';break;
    //        case'ru':$n='Цель';break;}
        switch($ft){
            case'org_send_request':
//                ccab_prc_org_send_request();
                $sub = 'Запрос на получение первичной информации';
                break;
            case'org_send_file_info':
//                ccab_prc_org_send_file_info();
                $sub = 'Файл первичной информации';
                break;
            case'resume_favorite':
//                ccab_prc_add_favorite();
                $sub = 'Добавление в понравившиеся';
                break;
            case'vacancy_create':
//                ccab_prc_vacancy_create();
                $sub = 'Создание вакансии';
                break;
            case'resume_create':
//                ccab_prc_resume_create();
                $sub = 'Создание резюме';
                break;
            case'user_register':
//                ccab_prc_user_register();
                $sub = 'Регистрация пользователя';
                break;
            
            /* ================================= */
            case 'order_create':
            case 'order':
                $sub = 'Заказ';
                break;
            case 'work':
                $sub = 'Работа у нас';
                break;
            case 'cabinet':
                $sub = 'Вхов в кабинет';
                break;
            case 'code':
                $sub = 'Подтверждение кодом';
                break;
            case 'phone-check':
                $sub = 'Проверить телефон';
                break;

            case 'phone_register':
                $sub = 'Сообщение о успешной регистрации';
                break;
            case 'registration':
                $sub = 'Зарегистриоваться';
                break;
            case 'recall':
                $sub = 'Перезвонить';
                break;
        }
    }
    if($subject !==false&&strlen($subject)>0) $sub = $subject;
    $head=array();
    $head=array('content-type: text/html');
    $res = false;
    if($to && $tpl){
//        $tpl = base64_decode($tpl);
//        add_filter( 'wp_mail_content_type', 'ccab_html_content_type' );
        $res = wp_mail ($to, $sub, $tpl, $head, $attachments);
//        remove_filter( 'wp_mail_content_type', 'ccab_html_content_type' );
    }
//    add_log(ob_get_clean());
//    add_log($ft);
//    add_log($to);
//    add_log($sub);
//    add_log($tpl);
//    add_log($head);
//    add_log($tpl);
    return $res;
}
function ccab_html_content_type() {
	return 'text/html';
}
function ccab_get_mail_params($name=''){
    $ret=array();
    return $ret;
}

/**
 * phpmailer_init
 */
function ccab_phpmailer_init($phpmailer){
    $phpmailer->Encoding = 'base64';
}
add_action('phpmailer_init', 'ccab_phpmailer_init');

function ccab_login($uphone='',$code=''){
//    $uphone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
//    $phone = preg_replace("/[^0-9]/", '', $uphone);
//    $pass=PASS_SECRET.$phone;
//    $uphone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    
    $res = false;
    $login = $uphone;
    $pass = $code;
    
    $creds = array();
    $creds['user_login'] = $login;
    $creds['user_password'] = $pass;
    $creds['remember'] = true;
    $user = wp_signon($creds,false);
    
    if ( is_wp_error( $user ) ) {
        $error_string = $user->get_error_message();
        $res = false;
    }
    else {
        $res = $user;
    }
    return $user;
    return;
    
    $key_1_value = get_user_meta( get_the_ID(), 'phone', true );
    // Check if the custom field has a value.
    if ( ! empty( $key_1_value ) ) {
        echo $key_1_value;
    }
//    wp_authenticate_username_password();
//    wp_authenticate_email_password();
}