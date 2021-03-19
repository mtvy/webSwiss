<?php

/* 
 * class.DSPaymentAdmin.php
 */
include_once 'trait.DShopAdminOptions.php';

class DSPaymentAdmin extends DShopPayment
{
    public $rpage = 'dspayment';
    public $page = 'dspayment.php';
    use 
            DShopAdminOptions;
    private static $instance = null;
	private static $initiated = false;
    public function __construct() {
		if ( ! self::$initiated ) {
////			self::_init_hooks();
            $this->init();
            self::$instance = $this;
            self::$initiated = true;
		}
    }
    public function init(){
        $this->rpage = $this->name;
        $this->page = $this->name . '.php';
//        add_action('admin_notices', [$this,'_notice']);
//        add_action('admin_notices', [$this,'_notice']);
//        $this->notice('hello 1');
//        $this->notice('hello 1','error');
//        $this->notice('hello 2','warning');
        
//        add_action('admin_menu', [$this,'admin_menu']);
        add_action('admin_menu', [$this,'options']);
        $this->init_options();
        
        add_action('admin_notices', [$this,'_notices']);
    }
    
    /**
     * регистрируем раздел "кабинет" и основную страницу
     * в меню админа
     * https://developer.wordpress.org/resource/dashicons/#shield
     */
    public function admin_menu() {
//        $this->notice('hello 3','info');
        global $ccab_page;
    //    $ page_title
    //    $ menu_title
    //    $ capability
    //    $ menu_slug
    //    $ function
    //    $ icon_url
    //    $ position
        
//        $hook = add_menu_page('DShop', 'DShop', 'manage_options',//1,
//            $this->page, [$this,'page_wrapper'],'dashicons-admin-site');
            
    //    add_menu_page('Параметры Кабинетов', 'Кабинет', 'manage_options',//1,
    //        $ccab_page, 'ccab_page_wrapper','dashicons-shield');
    //    add_action('load-'.$hook, array($this, 'showScreenOptions'));
//        add_action('load-'.$hook, 'showScreenOptions');
    }
    public function init_options(){
        add_action('admin_menu', [$this,'add_option_field_to_general_admin_page']);
    }
    public function payment_options_descrtiption(){
        echo '<b>DShopPayment - настройки</b>';
    }
    public function payment_kassa_descrtiption(){
        echo '<b>DShopPayment - передача информации о перечне товаров/услуг, <br/>количестве, цене и ставке налога по каждой позиции</b>';
    }
    public function payment_info_descrtiption(){
        echo '<b>DShopPayment - описание</b>';
    }
    public function payment_kassa_get_info_descrtiption(){
        echo '<b>DShopPayment - информация о оплате</b>';
    }
    
    public function add_option_field($option_name,$option_title,
            $option_field,$id_block,$page,$arg){
        // регистрируем опцию
//        register_setting( 'general', $option_name);
        register_setting( $this->page, $option_name, $arg);
        // добавляем поле
        $_arg = array( 
                'id' => $option_name, 
                'option_name' => $option_name 
            );
        $arg = $_arg + $arg;
        add_settings_field(
    //		'myprefix_setting-id',
            $option_name, 
            $option_title, 
            [$this,'option_setting_'.$option_field.'_callback'], 
            $page,//'general', 
            $id_block,//'default', 
            $arg
        );
    }
    public function add_option_text($option_name,$option_title,
            $option_field,$id_block,$page,$arg){
        // регистрируем опцию
//        register_setting( 'general', $option_name);
//        register_setting( $this->page, $option_name);
        // добавляем поле
        $_arg = array( 
                'id' => $option_name, 
                'option_name' => $option_name 
            );
        $arg = $_arg + $arg;
        add_settings_field(
    //		'myprefix_setting-id',
            $option_name, 
            $option_title, 
            [$this,'field_info_'.$option_field.'_callback'], 
            $page,//'general', 
            $id_block,//'default', 
            $arg
        );
    }
    public function get_info_payment($val){
        if($val == 1){
            global $DSPs;
    //        $this->n($val);
            $oid = get_option('rbc_payment_info_id','zl');
            $_istest = get_option('rbc_istest_info_get','zl');
    //        $_get = get_option('rbc_payment_info_get','zl');

            
            $res = $DSPs->get_p_info($oid,$_istest);
            $r = [];
            $r["><"] = ">\n<";
            $res = strtr($res,$r);
            $res = htmlspecialchars($res);
            $res = nl2br($res);
            $m='<p><b>Результат запроса о состоянии оплаты для заказа №'.$oid.':</b></p>';
            add_log($m.$res,'clear');
    //        add_log($_id,'admin');
    //        add_log($_get,'admin');
    //        add_log($_istest,'admin');
    //        add_log($val,'admin');
            
        }
        $val = 0;
        return $val;
    }
    public function add_option_field_to_general_admin_page(){
        $id_block = 'payments_settings';
        $title = 'Настройки оплат';
//        $this->n($title);
        $callback = [$this,'payment_options_descrtiption'];
        $page = 'general';
        $page = $this->page;
        add_settings_section( $id_block, $title, $callback, $page );

        
    $option_name = 'rbc_merchant_login';
    $option_title = 'Логин формы робокассы. регистрационная информация (логин)';
    $option_field = 'text';
    $items = [];
    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $arg = [
    ];
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

        
    $option_name = 'rbc_merchant_pass1';
    $option_title = 'Пароль формы робокассы. регистрационная информация (пароль #1)';
    $option_field = 'input';
    $items = [];
    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $arg = [
        'type'=>'password'
    ];
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

    $option_name = 'rbc_merchant_pass2';
    $option_title = 'Пароль ответа робокассы. регистрационная информация (пароль #2)';
    $option_field = 'input';
    $items = [];
    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $arg = [
        'type'=>'password'
    ];
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

    $option_name = 'rbc_payment_desc';
    $option_title = 'Дефолтное описание оплаты (возможны подстановки)';
    $option_field = 'text';
    $items = [];
    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $arg = [
//        'type'=>'password'
    ];
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

    $option_name = 'rbc_paybtn_var';
    $option_title = 'Вариант вывода кнопки оплаты';
    $option_field = 'select';
    $items = [];
//    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $items['no'] = 'No button';
    $items['script'] = 'script';
    $items['script_ext'] = 'script_ext';
    $items['form'] = 'form';
    $arg = [
//        'type'=>'password'
        'items'=>$items,
    ];
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

    
    
    $option_name = 'rbc_merchant_login_test';
    $option_title = 'Тестовый Логин формы робокассы. регистрационная информация (логин)';
    $option_field = 'text';
    $items = [];
    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $arg = [
    ];
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

        
    $option_name = 'rbc_merchant_pass1_test';
    $option_title = 'Тестовый Пароль формы робокассы. регистрационная информация (пароль #1)';
    $option_field = 'input';
    $items = [];
    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $arg = [
        'type'=>'password'
    ];
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

    $option_name = 'rbc_merchant_pass2_test';
    $option_title = 'Тестовый Пароль ответа робокассы. регистрационная информация (пароль #2)';
    $option_field = 'input';
    $items = [];
    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $arg = [
        'type'=>'password'
    ];
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

    $option_name = 'rbc_istest';
    $option_title = 'Режим оплаты (рабочий/тестовый)';
    $option_field = 'select';
    $items = [];
//    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $items['0'] = 'production';
    $items['1'] = 'is test';
    $arg = [
//        'type'=>'password'
        'items'=>$items,
    ];
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

    /*      ======      ======      ======      */
        $id_block = 'payments_get_info';
        $title = 'Проверка состояния оплаты по счёту';
        $callback = [$this,'payment_kassa_get_info_descrtiption'];
        $page = 'kassa_';
        $page = $page.$this->page;
        add_settings_section( $id_block, $title, $callback, $page );
        
    $option_name = 'rbc_payment_info_id';
    $option_title = 'Номер счёта (id)';
    $option_field = 'number';
    $items = [];
//    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
//    $items['0'] = 'Не использовать';
//    $items['1'] = 'Использовать';
    $arg = [
//        'type'=>'password'
        'items'=>$items,
//        'sanitize_callback'=>[$this,'get_info_payment'],
    ];
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

        
    $option_name = 'rbc_istest_info_get';
    $option_title = 'Отправить запрос о оплате (рабочий/тестовый)';
    $option_field = 'select';
    $items = [];
//    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $items['0'] = 'production';
    $items['1'] = 'is test';
    $arg = [
//        'type'=>'password'
        'items'=>$items,
//        'sanitize_callback'=>[$this,'get_info_payment'],
    ];
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

    $option_name = 'rbc_payment_info_get';
    $option_title = 'Отправить запрос';
    $option_field = 'select';
    $items = [];
//    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $items['0'] = 'Не проверять';
    $items['1'] = 'Проверить';
    $arg = [
//        'type'=>'password'
        'items'=>$items,
        'sanitize_callback'=>[$this,'get_info_payment'],
    ];
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

    
    /*      ======      ======      ======      */
        $id_block = 'payments_info';
        $title = 'Фискализация для клиентов Robokassa (планируется)';
        $callback = [$this,'payment_kassa_descrtiption'];
        $page = 'kassa_';
        $page = $page.$this->page;
        add_settings_section( $id_block, $title, $callback, $page );
        
    $option_name = 'rbc_use_receipt';
    $option_title = 'Фискализация';
    $option_field = 'select';
    $items = [];
//    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $items['0'] = 'Не использовать';
    $items['1'] = 'Использовать';
    $arg = [
//        'type'=>'password'
        'items'=>$items,
    ];
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

    
    
//    $option_name = 'delivery_percent';
//    $option_title = 'Процент доставки (доставка на склад)';
//    $option_field = 'number';
//    $items = [];
//    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
//    $arg = array(
//        );
//    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

//    $option_name = 'course_zl_rub';
//    $option_title = 'Коэфициент расчёта конечной валюты';
//    $option_field = 'number';
//    $items = [];
//    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
//    $arg = array(
//        );
//    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

//    $option_name = 'currency_short';
//    $option_title = 'Валюта, краткое';
//    $option_field = 'input';
//    $items = [];
//    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
//    $arg = array(
//            'type'=>'text',
//        );
//    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

    
    /*      ======      ======      ======      */
        $id_block = 'payments_info';
        $title = 'Полезная информация';
        $callback = [$this,'payment_info_descrtiption'];
        $page = 'info_';
        $page = $page.$this->page;
        add_settings_section( $id_block, $title, $callback, $page );
        
    $option_name = 'ResultURL';
    $option_title = 'Адрес страницы ResultURL:';
    $option_field = 'text';
    $items = [];
//    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    
    $items['ResultURL'] =
//            admin_url("admin-post.php").'';
            esc_url(home_url("dspresult/")).'';
//    $items['Код кассы с одной кнопкой «Оплатить»'] = 'https://docs.robokassa.ru/#1224';
//    $items['Варианты кнопок и форм'] = 'https://docs.robokassa.ru/#1239';
//    $items['Технические настройки'] = 'https://docs.robokassa.ru/#1160';
    $items['method'] = 'Метод приёма данных:<br/><b>POST</b>';
    $arg = [
//        'type'=>'password'
        'items'=>$items,
    ];
    $this->add_option_text($option_name,$option_title,$option_field,$id_block,$page,$arg );

    $option_name = 'SuccessURL';
    $option_title = 'Адрес страницы SuccessURL:';
    $option_field = 'text';
    $items = [];
//    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    
    $items['SuccessURL'] =
            esc_url(home_url("dssuccess/")).'';
    $items['SuccessURL'] =
            esc_url(home_url("cart/order/")).'';
    $items['method'] = 'Метод приёма данных:<br/><b>POST</b>';
//    $items['Код кассы с одной кнопкой «Оплатить»'] = 'https://docs.robokassa.ru/#1224';
//    $items['Варианты кнопок и форм'] = 'https://docs.robokassa.ru/#1239';
//    $items['Технические настройки'] = 'https://docs.robokassa.ru/#1160';
    $arg = [
//        'type'=>'password'
        'items'=>$items,
    ];
    $this->add_option_text($option_name,$option_title,$option_field,$id_block,$page,$arg );

    $option_name = 'FailURL';
    $option_title = 'Адрес страницы FailURL:';
    $option_field = 'text';
    $items = [];
//    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    
    $items['FailURL'] =
            esc_url(home_url("/dsfail/")).'';
    $items['FailURL'] =
            esc_url(home_url("/cart/order/")).'';
    $items['method'] = 'Метод приёма данных:<br/><b>POST</b>';
//    $items['Код кассы с одной кнопкой «Оплатить»'] = 'https://docs.robokassa.ru/#1224';
//    $items['Варианты кнопок и форм'] = 'https://docs.robokassa.ru/#1239';
//    $items['Технические настройки'] = 'https://docs.robokassa.ru/#1160';
    $arg = [
//        'type'=>'password'
        'items'=>$items,
    ];
    $this->add_option_text($option_name,$option_title,$option_field,$id_block,$page,$arg );

        
    $option_name = 'il1';
    $option_title = 'Полезные ссылки:';
    $option_field = 'dl_links';
    $items = [];
//    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    
    $items['Технические настройки'] = 'https://docs.robokassa.ru/#1160';
    $items['Описание переменных, параметров и значений'] =
            'https://docs.robokassa.ru/#1222';
    $items['Параметры проведения тестовых платежей'] =
            'https://docs.robokassa.ru/#4140';
    $items['Код кассы с одной кнопкой «Оплатить»'] = 'https://docs.robokassa.ru/#1224';
    $items['Варианты кнопок и форм'] = 'https://docs.robokassa.ru/#1239';
    $items['Оповещение об оплате на ResultURL'] = 'https://docs.robokassa.ru/#1250';
    $items['Фискализация для клиентов Robokassa. Облачное решение. Кассовое решение. Решение Робочеки'] =
            'https://docs.robokassa.ru/#1192';
    $arg = [
//        'type'=>'password'
        'items'=>$items,
    ];
    $this->add_option_text($option_name,$option_title,$option_field,$id_block,$page,$arg );

    /*      ======      ======      ======      */
//        $option_name = 'prod_parser_debug';
//        $option_title = 'тестирование парсера';
//        $option_field = 'select';
//        $arg = array( 
//                'items'=>[
//                    ''=>'production',
//                    '1'=>'debug',
//                ],
//            );
//        $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

        
//    $option_name = 'prod_parser_img_pattern';
//    $option_title = 'выбрать паттерн изображений';
//    $option_field = 'select';
//    $items = [];
//    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
//    $arg = array( 
//            'items'=>$items,
//        );
//    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

//    $option_name = 'prod_parser_img_patterns';
//    $option_title = 'паттерны изображений';
//    $option_field = 'textarea';
//    $arg = array( 
//                'cols' => '70',
//        );
//    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

//        $aleg = new Allegro();
//        foreach ($aleg->xpath_t as $key => $value) {
//            $option_name = 'parser_path_'.$key;
//            $option_title = 'Путь парсера к данным поля "'.$value.'"';
//            $option_field = 'textarea';
//            $arg = array( 
//                    'cols' => '120', 
//                );
//            $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );
//        }
        
//        $option_name = 'block_parser_path_1';
//        $option_title = 'Путь парсера к данным поля "Наименование"';
//        $option_field = 'textarea';
//        $arg = array( 
//                'cols' => '120', 
//            );
//        $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );
//
//
//        $option_name = 'block_parser_path_2';
//        $option_title = 'Путь парсера к данным поля "Цена"';
//        $option_field = 'textarea';
//        $arg = array( 
//                'cols' => '120', 
//            );
//        $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

        /*
        $option_name = 'block_asside_facebook';
        // регистрируем опцию
        register_setting( 'general', $option_name );
        // добавляем поле
        add_settings_field( 
    //		'myprefix_setting-id',
            'block_asside_facebook', 
            'Блок в колонке для кода фейсбука', 
            [$this,'myprefix_setting_callback_function'], 
            'general', 
            'default', 
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
            [$this,'lending_change_link_way_function'], 
            'general', 
            'default', 
            array(
                'id' => 'blog_link_way',
                'option_name' => 'blog_link_way',
                'items'=>[
                    'desktop'=>'desktop',
                    'mobile'=>'mobile',
                ],
            )
        );
        /**/
    }
    
    /**
notice-success - для успешных операций. Зеленая полоска слева.
notice-error - для ошибок. Красная полоска слева.
notice-warning - для предупреждений. Оранжевая полоска слева.
notice-info - для информации. Синяя полоска слева.
is-dismissible - добавляет иконку-кнопку "закрыть" (крестик в конце блока).
     *  Иконка добавляется через javascript.
     *  По клику на нее блок-заметка будет скрыт (удален).
     * 
     * @param type $m
     * @param type $s
     */
    public function n($m='',$s='success'){
        $this->addNtce($m,$s);
    }
    public function notice($m='',$s='success'){
        $this->addNtce($m,$s);
    }
    public $ntc=[];
    public function addNtce($m='',$c='success'){
        $this->ntc[]=['c'=>$c,'m'=>$m];
    }
    public function _notices(){
        foreach($this->ntc as $n)$this->_notice($n['m'],$n['c']);
        showLogInfo('admin');
    }
    public function _notice($m='',$s='success'){
        $class = 'notice-success';
        $class = 'notice-'.$s;
        $message = "Ошибка сохранения";
        echo '<div class="notice '.$class.' is-dismissible"> <p>'. $m .'</p></div>';
    }

    /**
     * обёртка для страницы кабинета shortcodes
     * @global string $true_page
     */
    public function page_wrapper() {
        
    //    $atr = func_get_args();
    //    add_log($atr);
        // тут уже будет находиться содержимое страницы
    global $ccab_page;
    ?><div class="wrap">
        <div id="icon-themes" class="icon32"></div>
        <h2>Параметры Оплат</h2>
       <?php
        ob_start();
        
       ?>
        <?php settings_errors(); ?>
            <?php
        $active_tab = 'display_options';
        if( isset( $_GET[ 'tab' ] ) ) {
            $active_tab = $_GET[ 'tab' ];
        } // end if
        $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'display_options';

        $tabs = [];
        $tabs['display_options'] = 'Display Options';
//        $tabs['robokassa_options'] = 'Robokassa Options';
    //    $tabs['display_options2'] = 'Display Options2';
        $tabs = apply_filters('ds_dspayment_settings__tabs', $tabs, $this);
        ?>
        <h2 class="nav-tab-wrapper">
        <?php
        foreach ($tabs as $tn => $tt) { // name => title
        ?>
            <a href="edit.php?post_type=<?=$this->name?>&page=<?=$this->rpage?>_settings.php&tab=<?=$tn?>" class="nav-tab <?php echo $active_tab == $tn ? 'nav-tab-active' : ''; ?>"><?=$tt?></a>
        <?php
        }
        do_action('ds_dspayment_settings__add_tab_link', $this,$this->name,$this->rpage, $active_tab);
        ?>
        </h2>
        
        <form method="post" enctype="multipart/form-data" action="options.php" >
            <?php 
        if( $active_tab == 'display_options' ) {
            settings_fields($this->page); // меняем под себя только здесь
        } 
        if( $active_tab == 'robokassa_options' ) {
            settings_fields($this->page); // меняем под себя только здесь
//            settings_fields('ccab_options'); // меняем под себя только здесь
            // (название настроек)
            do_settings_sections($this->page);
            do_settings_sections('kassa_'.$this->page);
    //        do_settings_sections('shortcodes_'.$ccab_page);
    //        do_settings_sections('shortcodes_'.$ccab_page);
    //        echo 'show shortcodes';
//            ccab_show_sortcodes();
        }
        do_action('ds_dspayment_settings__do_tab_sections', $this,$this->page, $active_tab);
        submit_button();
            ?>
        </form>
       <?php
        if( $active_tab == 'display_options' ) {
        } 
        if( $active_tab == 'robokassa_options' ) {
            do_settings_sections('info_'.$this->page);
        } // end if/else
        do_action('ds_dspayment_settings__do_tab_footer_info', $this,$this->page, $active_tab);
       /*
        * 
            <p class="submit">  
                    <input type="submit" class="button-primary"
                           value="<?php _e('Save Changes') ?>" />  
            </p>
        */
        $out=ob_get_clean();
//        showLogInfo('admin');
        echo $out;
       ?>
    </div><?php
    }
    /*  ==========  */

    /**
     * регистрируем страницы подменю в разделе "кабинет"
     * @global string $ccab_page
     */
    public function options() {
        $page = $this->page;
//        $page = null;
//        $page = 'options.php';
        $page = 'dshop.php'; // страница настроек магазина
        $r_page = $this->rpage;
    //    $ parent slug
    //    $ page title
    //    $ menu title
    //    $ capability
    //    $ menu slug
    //    $ function
//		$ptype_obj = get_post_type_object( $this->name );
//        $page = $ptype_obj->show_in_menu;
//        $this->n('<pre>'.print_r($ptype_obj,1).'</pre>');
//		$ptype_obj = get_post_type_object( 'post' );
//		$ptype_obj = get_post_type_object( 'dspayment' );
//        $page = $ptype_obj->show_in_menu;
//        $this->n('<pre>'.print_r($ptype_obj,1).'</pre>');
//        $screen = get_current_screen();
//    global $_parent_pages;
//        $this->n('<pre>:'.print_r($_parent_pages,1).'</pre>');
        
//        add_submenu_page( $page, 'Shortcodes', 'Shortcodes', 'manage_options',
//            ''.$r_page.'/shortcodes.php', 'ccab_page_shortcodes_wrapper');  
//        add_submenu_page( $page, 'Параметры', 'Параметры', 'manage_options',
//            "edit.php?post_type={$this->name}".'&page='.$r_page.'_settings.php', [$this,'page_wrapper']);
        
        
        $page = 'dshop.php'; // страница настроек магазина
        $page = 'dshop.php'; // страница настроек магазина
        add_submenu_page( "edit.php?post_type={$this->name}",
                'Параметры Оплат', 'Параметры Оплат', 'manage_options',
            ''.$r_page.'_settings.php', [$this,'page_wrapper']);
        
		if(0)add_submenu_page(
                $ptype_obj->show_in_menu,
                $ptype_obj->labels->name,
                $ptype_obj->labels->all_items,
                $ptype_obj->cap->edit_posts,
                "edit.php?post_type=$ptype" );
        
    //    add_submenu_page( $ccab_page, 'Параметры 3', 'Параметры 3', 'manage_options',
    //        'p3_'.$ccab_page.'', 'true_option_page2');

    //        add_submenu_page($parent_slug, $page_title, $menu_title,
    //                $capability, $menu_slug, $function);

    }
}