<?php

/* 
 * Подробно про страницы настроек в WordPress
 * src https://misha.blog/wordpress/option-pages.html
 * 
 * Добавление загрузчика изображений в метабокс и на страницу настроек WordPress
 * https://misha.blog/wordpress/uploader-metabox-option-pages.html
 */

/*          ========== ==========            */

function ccab_get_option($optname=''){
    $option_name = 'ccab_options';
    $o = get_option( $option_name );
    $d=false;
    if(isset($o[$optname])) $d=$o[$optname];
    return $d;
    
//    $all_options = get_option('true_options'); // это массив
//    echo $all_options['my_text']; // это значение текстового поля
//    // чтобы посмотреть все ключи и значения вы можете сделать так:
//    // print_r( $options );
}

/*          ========== ==========            */

function banners_menu(){
    // регестрируется страница в резделе "консоль"
    add_submenu_page('index.php', 'Настройка баннеров', 'Баннеры', 1,
        'banners-status.php', 'banner_on_off');
    function banner_on_off() {
	// тут уже будет находиться содержимое страницы
//        echo 'hi';
    }
}
//add_action('admin_menu', 'banners_menu');


/*          ========== ==========            */

$ccab_page = 'cab.php'; 

/**
 * обёртка для страницы кабинета
 * @global string $true_page
 */
function ccab_page_wrapper() {
//    $atr = func_get_args();
//    add_log($atr);
    // тут уже будет находиться содержимое страницы
global $ccab_page;
?><div class="wrap">
    <h2>Вакансии</h2>
   <?php
    ob_start();
   
    $Table = new WP2FL_Lessons_Menu_Table_Create();
    $Table -> prepare_items();
 
    ?>
        <div class="wrap">
            <h2>Example List Table</h2>
<form method="get">
                <input type="hidden" name="page" value="<?php echo $ccab_page ?>" />
                <?php $Table -> search_box('search', 'search_id'); ?>
                <?php $Table -> display(); ?>
            <?php // $Table -> display(); ?>
            </form>
 
        </div>
        </div>
    <?php
    /*
   ?>
    <form method="post" enctype="multipart/form-data" action="options.php">
        <?php 
        settings_fields('ccab_options'); // меняем под себя только здесь
        // (название настроек)
        echo '(название настроек)';
//        echo 'show shortcodes';
        do_settings_sections($ccab_page);
        ?>
        <p class="submit">  
                <input type="submit" class="button-primary"
                       value="<?php _e('Save Changes') ?>" />  
        </p>
    </form>
   <?php
    /**/
    $out=ob_get_clean();
    showLogInfo('admin');
    echo $out;
   ?>
</div><?php
}
//http://bz._sandbox/bz12/chop.rf/v1/wp-admin/admin.php?
//page=&s=&_wpnonce=e9c7d26913
//&_wp_http_referer=
//%2Fbz12%2Fchop.rf%2Fv1%2Fwp-admin%2Fadmin.php
//    %3Fpage%3Dcab.php
//        &action=lock&paged=1&id%5B%5D=13&action2=-1
function ccab_page_settings_wrapper() {
//    $atr = func_get_args();
//    add_log($atr);
    // тут уже будет находиться содержимое страницы
global $ccab_page;
?><div class="wrap">
    <h2>Параметры кабинета клиентов</h2>
   <?php
    ob_start();
   ?>
    <form method="post" enctype="multipart/form-data" action="options.php">
        <?php 
        settings_fields('ccab_options'); // меняем под себя только здесь
        // (название настроек)
        do_settings_sections($ccab_page);
        ?>
        <p class="submit">  
                <input type="submit" class="button-primary"
                       value="<?php _e('Save Changes') ?>" />  
        </p>
    </form>
   <?php
    $out=ob_get_clean();
    showLogInfo('admin');
    echo $out;
   ?>
</div><?php
}

/**
 * обёртка для страницы кабинета shortcodes
 * @global string $true_page
 */
function ccab_page_shortcodes_wrapper() {
//    $atr = func_get_args();
//    add_log($atr);
    // тут уже будет находиться содержимое страницы
global $ccab_page;
?><div class="wrap">
    <h2>Доступные шорткоды</h2>
   <?php
    ob_start();
   ?>
    <form method="post" enctype="multipart/form-data" action="options.php">
        <?php 
//        settings_fields('ccab_options'); // меняем под себя только здесь
        // (название настроек)
//        do_settings_sections('shortcodes_'.$ccab_page);
//        echo 'show shortcodes';
        ccab_show_sortcodes();
        ?>
    </form>
   <?php
   /*
    * 
        <p class="submit">  
                <input type="submit" class="button-primary"
                       value="<?php _e('Save Changes') ?>" />  
        </p>
    */
    $out=ob_get_clean();
    showLogInfo('admin');
    echo $out;
   ?>
</div><?php
}
/**
 * регистрируем раздел "кабинет" и основную страницу
 * в меню админа
 * https://developer.wordpress.org/resource/dashicons/#shield
 */
function ccab_admin_menu() {
    global $ccab_page;
//    $ page_title
//    $ menu_title
//    $ capability
//    $ menu_slug
//    $ function
//    $ icon_url
//    $ position
//    $hook = add_menu_page('Вакансии', 'Вакансии', 1,
    $hook = add_menu_page('Вакансии', 'Вакансии', 'manage_options',
        $ccab_page, 'ccab_page_wrapper','dashicons-shield');
//    add_menu_page('Параметры Кабинетов', 'Кабинет', 1,
//        $ccab_page, 'ccab_page_wrapper','dashicons-shield');
//    add_action('load-'.$hook, array($this, 'showScreenOptions'));
    add_action('load-'.$hook, 'showScreenOptions');
}
add_action('admin_menu', 'ccab_admin_menu');
add_filter('set-screen-option', 'setScreenOption', 10, 3);
//add_filter('set-screen-option', array($this, 'setScreenOption'), 10, 3);
function adminHead()
{
    global $ccab_page;
    if($ccab_page == (isset($_GET['page']) ? esc_attr($_GET['page']) : ''))
    {
        echo '
<style type="text/css">';
        echo '.wp-list-table .column-cb { width: 4%; }';
        echo '.wp-list-table .column-ex_id { width: 5%; }';
        echo '.wp-list-table .column-ex_title { width: 30%; }';
        echo '.wp-list-table .column-ex_author { width: 15%; }';
        echo '.wp-list-table .column-ex_description { width: 36%; }';
        echo '.wp-list-table .column-ex_price { width: 10%; }';
        echo '</style>
 
';
    }
}
add_action('admin_head', 'adminHead');
//add_action('admin_head', array($this, 'adminHead'));


/*  ==========  */

/**
 * регистрируем страницы подменю в разделе "кабинет"
 * @global string $ccab_page
 */
function ccab_options() {
    global $ccab_page;
//    $ parent slug
//    $ page title
//    $ menu title
//    $ capability
//    $ menu slug
//    $ function
    add_submenu_page( $ccab_page, 'Shortcodes', 'Shortcodes', 'manage_options',
        'shortcodes_'.$ccab_page.'', 'ccab_page_shortcodes_wrapper');  
    add_submenu_page( $ccab_page, 'Параметры', 'Параметры', 'manage_options',
        'settings_'.$ccab_page.'', 'ccab_page_settings_wrapper');  
//    add_submenu_page( $ccab_page, 'Параметры 3', 'Параметры 3', 'manage_options',
//        'p3_'.$ccab_page.'', 'true_option_page2');
    
//        add_submenu_page($parent_slug, $page_title, $menu_title,
//                $capability, $menu_slug, $function);

}
add_action('admin_menu', 'ccab_options');

/*  ==========  */
if(!defined('CCAB_OPT_DEBUG')){
    $debug = ccab_get_option('ccab_options_debug');
    define('CCAB_OPT_DEBUG', $debug);
}
/*
 * Регистрируем поля настроек
 * Мои настройки будут храниться в базе под названием true_options
 *  (это также видно в предыдущей функции)
 */
function ccab_option_settings() {
    global $ccab_page;
    $debug = false;
    $debug = 1;
    $debug = ccab_get_option('ccab_options_debug');
    // Присваиваем функцию валидации ( true_validate_settings() ).
    //  Вы найдете её ниже
    register_setting( 'ccab_options', 'ccab_options',
            'ccab_validate_settings');
    // true_options
/*  ==========  */

//    $ id
//    $ title
//    $ callback
//    $ page
    
    // Добавляем секцию
    add_settings_section( 'ccab_section_shortcodes', 'Доступные шорткоды для CrystalCabinet', 
        '', 'shortcodes_'.$ccab_page );

    // Создадим текстовое поле в sms секции
    $true_field_params = array(
        'debug'      => $debug, // тип
        'type'      => 'text', // тип
        'id'        => 'ccab_shc_1',
        'desc'      => 'Логин для sms сервиса.'."<br/>( page = $ balance )", // описание
        'label_for' => 'ccab_shc_1' // позволяет сделать название
        // настройки лейблом (если не понимаете, что это,
        //  можете не использовать), по идее должно быть одинаковым
        //   с параметром id
    );
//    $ id
//    $ title
//    $ callback
//    $ page
//    $ section
//    $ args
    
    add_settings_field( 'ccab_shc_page_edit', 'User edit',
        'ccab_option_display_settings', 'shortcodes_'.$ccab_page, 'ccab_section_shortcodes',
        $true_field_params );
/*  ==========  */

    // Добавляем секцию
//    add_settings_section( 'ccab_section_sms_auth', 'Данные авторизации SMS провайдера', 
//        '', $ccab_page );
    add_settings_section( 'ccab_section_settings', 'Параметры настроек', 
        '', $ccab_page );
    
//    $balance = ccab_sms_send('balance');
    $balance=0;

    // Создадим текстовое поле в sms секции
    $true_field_params = array(
        'debug'      => $debug, // тип
        'type'      => 'text', // тип
        'id'        => 'ccab_sms_login',
        'desc'      => 'Логин для sms сервиса.'."<br/>( баланс = $balance )", // описание
        'label_for' => 'ccab_sms_login' // позволяет сделать название
        // настройки лейблом (если не понимаете, что это,
        //  можете не использовать), по идее должно быть одинаковым
        //   с параметром id
    );
    add_settings_field( 'ccab_sms_login_field', 'Login',
        'ccab_option_display_settings', $ccab_page, 'ccab_section_sms_auth',
        $true_field_params );

    // Создадим текстовое поле в sms секции
    $true_field_params = array(
        'debug'      => $debug, // тип
        'type'      => 'text', // тип
        'id'        => 'ccab_sms_pass',
        'desc'      => 'Пароль для sms сервиса.', // описание
        'label_for' => 'ccab_sms_pass' // позволяет сделать название
    );
    add_settings_field( 'ccab_sms_pass_field', 'Password',
        'ccab_option_display_settings', $ccab_page, 'ccab_section_sms_auth',
        $true_field_params );

    // Создадим текстовое поле в sms секции
    $true_field_params = array(
        'debug'      => $debug, // тип
        'type'      => 'text', // тип
        'id'        => 'ccab_sms_sender',
        'desc'      => 'Отправитель sms.', // описание
        'label_for' => 'ccab_sms_sender' // позволяет сделать название
    );
    add_settings_field( 'ccab_sms_sender_field', 'Sender',
        'ccab_option_display_settings', $ccab_page, 'ccab_section_sms_auth',
        $true_field_params );

    // Создадим выпадающий список sms провайдеров
    $prividers=array();
    $prividers['sms_pov__no']='Отключён';
    $prividers['sms_pov__js_alert']='подсказки (js alert)';
    $prividers['sms_pov__sms_center']='sms-center.su';
//    $prividers['']='';
    $true_field_params = array(
        'debug'      => $debug, // тип
        'type'      => 'select',
        'id'        => 'ccab_sms_provider',
        'desc'      => 'Используемый проваайдер.',
        'vals'		=> $prividers
    );
    add_settings_field( 'ccab_sms_provider_field', 'SMS провайдер',
        'ccab_option_display_settings', $ccab_page, 'ccab_section_sms_auth',
        $true_field_params );

    // Создадим textarea в первой секции
    $true_field_params = array(
        'debug'      => $debug, // тип
        'type'      => 'textarea',
        'id'        => 'ccab_sms_comment',
        'desc'      => 'Комментарий к используемому провайдеру.'
    );
    add_settings_field( 'ccab_sms_comment_field', 'Comment',
        'ccab_option_display_settings', $ccab_page, 'ccab_section_sms_auth',
        $true_field_params );

    $debug_staus=array();
    $debug_staus[1]='Включён';
    $debug_staus[0]='Отключён';
    // Создадим радио-кнопку
    $true_field_params = array(
        'debug'      => $debug, // тип
        'type'      => 'radio_x',
        'id'      => 'ccab_options_debug',
        'desc'      => 'Режим отладки, для опций кабинета.',
        'vals'		=> $debug_staus
    );
    add_settings_field( 'ccab_options_debug_field', 'Debug',
        'ccab_option_display_settings', $ccab_page, 'ccab_section_settings',
        $true_field_params );
}
add_action( 'admin_init', 'ccab_option_settings' );
 
/*
 * Функция отображения полей ввода
 * Здесь задаётся HTML и PHP, выводящий поля
 */
function ccab_option_display_settings($args) {
    extract( $args );

//define('p','<pre>');
//define('P','</pre>');
//define('s',"<br/>\n");
    
    if(!isset($debug))$debug = true;
    
    $option_name = 'true_options';
    $option_name = 'ccab_options';
    $o = get_option( $option_name );
    static $debug_value=0;
    if($debug && !$debug_value)
//        echo
    add_log(p.'$o = '.print_r($o,1).P);
    $debug_value++;
    if($debug)echo p.'$args = '.print_r($args,1).P;

    switch ( $type ) {
        case 'text':
            if(!isset($o[$id]))$o[$id]='';
            if($o[$id]!='')$o[$id] = esc_attr( stripslashes($o[$id]) );
            echo "<input class='regular-text' type='text' id='$id'"
                    . " name='" . $option_name
                    . "[$id]' value='$o[$id]' />";
            echo ($desc != '') ? "<br /><span class='description'>"
            . "$desc</span>" : "";
        break;
        case 'textarea':
            if(!isset($o[$id]))$o[$id]='';
            if($o[$id]!='')$o[$id] = esc_attr( stripslashes($o[$id]) );
            echo "<textarea class='code large-text' cols='50'"
                . " rows='10' type='text' id='$id' name='" . $option_name
                . "[$id]'>$o[$id]</textarea>";
            echo ($desc != '') ? "<br /><span class='description'>"
            . "$desc</span>" : "";
        break;
        case 'checkbox':
            $checked = (isset($o[$id]) && $o[$id] == 'on') ? " checked='checked'" :  '';
            echo "<label><input type='checkbox' id='$id' name='"
                . $option_name . "[$id]' $checked /> ";
            echo ($desc != '') ? $desc : "";
            echo "</label>";
        break;
        case 'select':
            echo "<select id='$id' name='" . $option_name . "[$id]'>";
            foreach($vals as $v=>$l){
                $selected = ($o[$id] == $v) ? "selected='selected'" : '';
                echo "<option value='$v' $selected>$l</option>";
            }
            echo "</select>";
            echo ($desc != '') ? "<br /><span class='description'>"
            . "$desc</span>" : "";
        break;
        case 'radio_z':
        case 'radio':
            echo "<fieldset>";
            foreach($vals as $v=>$l){
                $checked = ($o[$id] == $v) ? "checked='checked'" : '';
                echo "<label><input type='radio' name='"
                . $option_name . "[$id]' value='$v' $checked />"
                    . "$l</label>"
                    . "<br />";
            }
            echo ($desc != '') ? "<br /><span class='description'>"
            . "$desc</span>" : "";
            echo "</fieldset>";
        break;
        case 'radio_x':
            echo "<fieldset>";
            foreach($vals as $v=>$l){
                $checked = ($o[$id] == $v) ? "checked='checked'" : '';
                echo "<label><input type='radio' name='"
                . $option_name . "[$id]' value='$v' $checked />"
                    . "$l</label> "
//                    . "<br />"
                        ;
            }
            echo ($desc != '') ? "<br /><span class='description'>"
            . "$desc</span>" : "";
            echo "</fieldset>";
        break;
    }
}
 
/*
 * Функция проверки правильности вводимых полей
 */
function ccab_validate_settings($input) {
    add_log('Данные изменены','admin');
//    add_log($input);
    if(CCAB_OPT_DEBUG)add_log(p.'validate: $input = '.print_r($input,1).P);
    foreach($input as $k => $v) {
        $valid_input[$k] = trim($v);

        /* Вы можете включить в эту функцию
         *  различные проверки значений, например
        */
        switch($k){
            case'ccab_sms_pass':
                if($v=='z' ) { // если не выполняется
                        $valid_input[$k] = 'd'; // тогда присваиваем значению пустую строку
                }
                break;
            default:break;
        }
    }
    return $valid_input;
}

/*          ========== ==========            */
/*          ++++++++++ ++++++++++            */
/*          ========== ==========            */

$true_page = 'myparameters.php'; 
// это часть URL страницы, рекомендую использовать
// строковое значение, т.к. в данном случае не будет зависимости от того,
//  в какой файл вы всё это вставите
/*
 * Функция, добавляющая страницу в пункт меню "Настройки"
 */
function true_options() {
	global $true_page;
	add_options_page( 'Параметры1', 'Параметры 1', 'manage_options',
                $true_page, 'true_option_page'); 

}
add_action('admin_menu', 'true_options');
 
/**
 * Возвратная функция (Callback)
 */ 
function true_option_page2(){
    global $true_page;
    ?><div class="wrap">
        <h2>Дополнительные параметры сайта 2</h2>
        <form method="post" enctype="multipart/form-data" action="options.php">
            <?php 
            settings_fields('true_options'); // меняем под себя только здесь
            // (название настроек)
            do_settings_sections($true_page);
            ?>
            <p class="submit">  
                    <input type="submit" class="button-primary"
                           value="<?php _e('Save Changes') ?>" />  
            </p>
        </form>
    </div><?php
}
function true_option_page(){
    global $true_page;
    ?><div class="wrap">
        <h2>Дополнительные параметры сайта</h2>
        <form method="post" enctype="multipart/form-data" action="options.php">
            <?php 
            settings_fields('true_options'); // меняем под себя только здесь
            // (название настроек)
            do_settings_sections($true_page);
            ?>
            <p class="submit">  
                    <input type="submit" class="button-primary"
                           value="<?php _e('Save Changes') ?>" />  
            </p>
        </form>
    </div><?php
}
 
/*
 * Регистрируем настройки
 * Мои настройки будут храниться в базе под названием true_options
 *  (это также видно в предыдущей функции)
 */
function true_option_settings() {
    global $true_page;
    $debug = true;
    // Присваиваем функцию валидации ( true_validate_settings() ).
    //  Вы найдете её ниже
    register_setting( 'true_options', 'true_options', 'true_validate_settings');
    // true_options

    // Добавляем секцию
    add_settings_section( 'true_section_1', 'Текстовые поля ввода', 
        '', $true_page );

    // Создадим текстовое поле в первой секции
    $true_field_params = array(
        'type'      => 'text', // тип
        'id'        => 'my_text',
        'desc'      => 'Пример обычного текстового поля.', // описание
        'label_for' => 'my_text' // позволяет сделать название
        // настройки лейблом (если не понимаете, что это,
        //  можете не использовать), по идее должно быть одинаковым
        //   с параметром id
    );
    add_settings_field( 'my_text_field', 'Текстовое поле',
        'true_option_display_settings', $true_page, 'true_section_1',
        $true_field_params );

    // Создадим textarea в первой секции
    $true_field_params = array(
        'type'      => 'textarea',
        'id'        => 'my_textarea',
        'desc'      => 'Пример большого текстового поля.'
    );
    add_settings_field( 'my_textarea_field', 'Большое текстовое поле',
        'true_option_display_settings', $true_page, 'true_section_1',
        $true_field_params );

    // Добавляем вторую секцию настроек

    add_settings_section( 'true_section_2', 'Другие поля ввода',
        '', $true_page );

    // Создадим чекбокс
    $true_field_params = array(
        'type'      => 'checkbox',
        'id'        => 'my_checkbox',
        'desc'      => 'Пример чекбокса.'
    );
    add_settings_field( 'my_checkbox_field', 'Чекбокс',
        'true_option_display_settings', $true_page, 'true_section_2',
        $true_field_params );

    // Создадим выпадающий список
    $true_field_params = array(
        'type'      => 'select',
        'id'        => 'my_select',
        'desc'      => 'Пример выпадающего списка.',
        'vals'		=> array( 'val1' => 'Значение 1',
            'val2' => 'Значение 2', 'val3' => 'Значение 3')
    );
    add_settings_field( 'my_select_field', 'Выпадающий список',
        'true_option_display_settings', $true_page, 'true_section_2',
        $true_field_params );

    // Создадим радио-кнопку
    $true_field_params = array(
        'type'      => 'radio',
        'id'      => 'my_radio',
        'vals'		=> array( 'val1' => 'Значение 1',
            'val2' => 'Значение 2', 'val3' => 'Значение 3')
    );
    add_settings_field( 'my_radio', 'Радио кнопки',
        'true_option_display_settings', $true_page, 'true_section_2',
        $true_field_params );

}
add_action( 'admin_init', 'true_option_settings' );
 
/*
 * Функция отображения полей ввода
 * Здесь задаётся HTML и PHP, выводящий поля
 */
function true_option_display_settings($args) {
    extract( $args );

//define('p','<pre>');
//define('P','</pre>');
//define('s',"<br/>\n");
    
    if(!isset($debug))$debug = true;
    if($debug)echo p.print_r($args,1).P;
    $option_name = 'true_options';

    $o = get_option( $option_name );
    if($debug)echo p.print_r($o,1).P;

    switch ( $type ) {  
        case 'text':  
            $o[$id] = esc_attr( stripslashes($o[$id]) );
            echo "<input class='regular-text' type='text' id='$id'"
                    . " name='" . $option_name 
                    . "[$id]' value='$o[$id]' />";  
            echo ($desc != '') ? "<br /><span class='description'>"
            . "$desc</span>" : "";  
        break;
        case 'textarea':  
            $o[$id] = esc_attr( stripslashes($o[$id]) );
            echo "<textarea class='code large-text' cols='50'"
                . " rows='10' type='text' id='$id' name='" . $option_name 
                . "[$id]'>$o[$id]</textarea>";  
            echo ($desc != '') ? "<br /><span class='description'>"
            . "$desc</span>" : "";  
        break;
        case 'checkbox':
            $checked = (isset($o[$id]) && $o[$id] == 'on') ? " checked='checked'" :  '';  
            echo "<label><input type='checkbox' id='$id' name='" 
                . $option_name . "[$id]' $checked /> ";  
            echo ($desc != '') ? $desc : "";
            echo "</label>";  
        break;
        case 'select':
            echo "<select id='$id' name='" . $option_name . "[$id]'>";
            foreach($vals as $v=>$l){
                $selected = ($o[$id] == $v) ? "selected='selected'" : '';  
                echo "<option value='$v' $selected>$l</option>";
            }
            echo "</select>";  
            echo ($desc != '') ? "<br /><span class='description'>"
            . "$desc</span>" : "";  
        break;
        case 'radio':
            echo "<fieldset>";
            foreach($vals as $v=>$l){
                $checked = ($o[$id] == $v) ? "checked='checked'" : '';  
                echo "<label><input type='radio' name='" 
                . $option_name . "[$id]' value='$v' $checked />"
                    . "$l</label>"
                    . "<br />";
            }
            echo "</fieldset>";  
        break; 
    }
}
 
/*
 * Функция проверки правильности вводимых полей
 */
function true_validate_settings($input) {
    foreach($input as $k => $v) {
        $valid_input[$k] = trim($v);

        /* Вы можете включить в эту функцию различные проверки значений, например
        if(! задаем условие ) { // если не выполняется
                $valid_input[$k] = ''; // тогда присваиваем значению пустую строку
        }
        */
    }
    return $valid_input;
}


/*===================================*/
//function _add_option_field_to_general_admin_page(){
//	$option_name = 'my_option';
//
//	// регистрируем опцию
//	register_setting( 'general', $option_name );
//
//	// добавляем поле
//	add_settings_field(
//		'myprefix_setting-id', 
//		'Название опции', 
//		'myprefix_setting_callback_function', 
//		'general', 
//		'default', 
//		array( 
//			'id' => 'myprefix_setting-id', 
//			'option_name' => 'my_option' 
//		)
//	);
//    
////    add_settings_section( $id, $title, $callback, $page );
//}
//add_action('admin_menu', '_add_option_field_to_general_admin_page');

function _myprefix_setting_callback_function( $val ){
	$id = $val['id'];
	$option_name = $val['option_name'];
	?>
	<input 
		type="text" 
		name="<?= $option_name ?>" 
		id="<?= $id ?>" 
		value="<?= esc_attr( get_option($option_name) ) ?>" 
	/> 
	<?php
}

/*=======================*/


/*=======================*/
//https://wp-kama.ru/function/add_settings_field
function ccab_add_option_field_to_general_admin_page(){

    add_settings_section( 'section_sys_opt', 'Дополнительные системные настройки', 
        '', 'general' );
    register_setting( 'general', 'email_get_requests' );
    add_settings_field(
//		'myprefix_setting-id',
        'email_get_requests', 
        'Почта принятия заявок', 
        '__general_field_input', 
        'general', 
//		'default', 
        'section_sys_opt', 
        array( 
            'id' => 'email_get_requests', 
            'option_name' => 'email_get_requests' 
        )
    );
    if(0){
        add_settings_section( 'sys_optionds_section', 'Дополнительные данные', 
            '', 'general' );
        // регистрируем опцию
        $option_name = 'block_asside_facebook';
        register_setting( 'general', $option_name );
    //    add_settings_section( $id, $title, $callback, $page );
        // добавляем поле
        add_settings_field( 
    //		'myprefix_setting-id',
            'block_asside_facebook', 
            'Блок в колонке для кода фейсбука', 
            '__general_field_textarea', 
            'general', 
    //		'default', 
            'sys_optionds_section', 
            array( 
                'id' => 'block_asside_facebook', 
                'option_name' => 'block_asside_facebook' 
            )
        );
        register_setting( 'general', 'blog_link_way' );
        add_settings_field( 
    //		'myprefix_setting-id',
            'blog_link_way',
            'Направление ссылок', 
            '__general_field_select', 
            'general', 
    //		'default', 
            'sys_optionds_section', 
            array(
                'id' => 'blog_link_way',
                'option_name' => 'blog_link_way',
                'items'=>[
                    'desktop'=>'desktop',
                    'mobile'=>'mobile',
                ],
            )
        );
    }
}
add_action('admin_menu', 'ccab_add_option_field_to_general_admin_page');

function __general_field_input( $val ){
	$id = $val['id'];
	$option_name = $val['option_name'];
	?>
	<input 
		type="text" 
		name="<? echo $option_name ?>" 
		id="<?= $id ?>" 
        class="regular-text ltr"
		value="<? echo esc_attr( get_option($option_name) ) ?>" 
	/> 
	<?php
}

function __general_field_textarea( $val ){
	$id = $val['id'];
	$option_name = $val['option_name'];
	?>
<textarea cols="70" rows="5"
		name="<?= $option_name ?>" 
		id="<?= $id ?>" ><?
        echo esc_attr( get_option($option_name) )
        ?></textarea>
	<?php
}
function __general_field_select( $val ){
	$id = $val['id'];
	$option_name = $val['option_name'];
    $tpl_o=<<<t
            <option value="_v_" _s_>_n_</option>
t;
    $r=[];
    $v_=get_option($option_name,'desctop');
    foreach($val['items'] as $v=>$n){
        $r['_n_']=$n;
        $r['_v_']=$v;
        $r['_s_']=$v_==$v?'selected="selected"':'';
        $val['items'][$v] = strtr($tpl_o,$r);
    }
    $o=implode('',$val['items']);
	?>
<select name="<? echo $option_name ?>" 
		id="<?= $id ?>" ><?= $o?></select>
	<?php
}

/*=======================*/
/*=======================*/