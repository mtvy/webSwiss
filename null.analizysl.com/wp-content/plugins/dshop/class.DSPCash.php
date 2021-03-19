<?php
/**
 * @package dshop
 */
/*
Plugin Name: DShop Pyment Cash
Plugin URI: 
Description: Расширение функционала магазина: Оплата наличными
Version: 1.0
Author: me
Author URI: my
License: GPLv2 or later
Text Domain: dshop
*/
/* 
 * class.DSPCash.php
 * 
 */

class DSPCash{
    
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
    
    public $key = 'cash';
    public $name = 'Cash';
    public $class = 'DSPCash';
    
    public $mrh_login = "admin";
    public $mrh_pass1 = "*******";
    public $mrh_pass2 = "*******";
    
    public $inv_id = "";
    public $inv_desc = "";
    public $out_summ = "";
    public $shp_item = "";
    public $in_curr = "";
    public $culture = "";
    public $encoding = "";
    public $paybtn_var = "form";
    public $is_test = "";
    public $Receipt = "";
    public $use_receipt = 0;
    
    public $img = 'https://analizysl.com/wp-content/uploads/2019/09/logo.jpg';
    
    
    public function dsps_pyments_get_list($list = [],$dsps=null){
        $list[$this->key] = $this->name;
        return $list;
    }
    public function ds_dspayment_settings__tabs($tabs = [],$dsps=null){
        $tabs[$this->key.'_options'] = $this->name.' Options';
        return $tabs;
    }
    public function ds_dspayment_settings__do_tab_sections($object,$page, $active_tab){
        if($this->key.'_options' == $active_tab){
            settings_fields($page); // меняем под себя только здесь
        }
    }
    public function ds_dspayment_settings__do_tab_footer_info($object,$page, $active_tab){
        if($this->key.'_options' == $active_tab){
        }
    }
    public function dsps_pyments_get_psystem_items($list = [],$dsps=null){
        $list[$this->key] = $this;
        return $list;
    }
    public function form_access(){
        $ac=false;
        $r_access = [];
        $r_access [] ='administrator';
        $r_access [] ='ml_administrator';
        $r_access [] ='ml_manager';
        //$r_access [] ='ml_doctor';
        $r_access [] ='ml_procedurecab';
        $user = wp_get_current_user();
        if(count( array_intersect($r_access, (array) $user->roles ) ) ){
        //    get_template_part( 'template-parts/page/tpl.page-access', 'denied' );
        //    get_template_part( 'template-parts/page/tpl.page-access', 'notfound' );
            $ac=1;
        }
        return $ac;
    }
    /**
     * 
     * @param string $ftype post form-type
     * @param object $obj DShop object
     */
    public  function dshop_process($ftype,$obj){
        if($ftype=='payment_cashe')$this->prc_ds_payment_cash($obj);
//        add_log('dshop_process '.$ftype);
    }
    
    public function prc_ds_payment_cash($ds_obj){
        if(!$this->form_access()) return false;
        global $wpdb,$is_ajax;
        global $DSPs;
        $is_ajax = false;
        $res=false;
        $res_mess=[];
        
                $m = "Тест сохранения оплаты";
//                add_log($m);
        
//        $os = filter_input(INPUT_POST, 'OutSum',FILTER_SANITIZE_STRING);
        $os = filter_input(INPUT_POST, 'summ',FILTER_SANITIZE_STRING);
        $id = filter_input(INPUT_POST, 'InvId',FILTER_SANITIZE_STRING);
        $paymentmethod = filter_input(INPUT_POST, 'paymentmethod_sl',FILTER_SANITIZE_STRING);
        
//        $email = filter_input(INPUT_POST, 'Email',FILTER_SANITIZE_STRING);
//        $fee = filter_input(INPUT_POST, 'Fee',FILTER_SANITIZE_STRING);// Коммиссия
//        $sv = filter_input(INPUT_POST, 'SignatureValue',FILTER_SANITIZE_STRING);
    //        $d = filter_input(INPUT_POST, 'dlv',FILTER_SANITIZE_STRING);
//        $test = filter_input(INPUT_POST, 'IsTest',FILTER_SANITIZE_NUMBER_INT);
//        $ac = filter_input(INPUT_POST, 'Shp_action',FILTER_SANITIZE_STRING);
        if(10){
            $uid = 1;
            $way = 'cash';
            $way = $this->key;
            $discontID = DSDiscont::_get_discont_id('laborant',0);
//            $orderId = $DSPs->add_payment($id,$os,$way,$fee,$email,$sv,$istest=$test,$result=$_POST);
            $paymentId = $DSPs->add_payment($id,$os,$way,$fee=0,$email='',$sv=0,$istest=$test=false,$result=$_POST);
            if(!is_wp_error($paymentId)){
                /*
SELECT * FROM `wp_postmeta` where `post_id` =
  (SELECT `post_id` FROM `wp_postmeta` where `meta_key` = 'dsop_ID' and `meta_value` = '47715')
                 */
                add_post_meta( $id, 'dsop_method_sl', $paymentmethod, 1 );// paymentmethod_sl
                add_post_meta( $paymentId, 'dsop_method_sl', $paymentmethod, 1 );
                add_post_meta( $id, 'dsop_discont_id', $discontID, 1 );// PayCategoryId
                add_post_meta( $paymentId, 'dsop_discont_id', $discontID, 1 );
            }
            add_log( $DSPs-> get_payments_summ($id));
            if(is_wp_error($orderId)){
                $m = "Сбой сохранения оплаты";
                add_log($m);
            }
            wp_redirect($_SERVER['HTTP_REFERER']);
//            echo "OK$id\n";
            exit();
        }
    }
    
    public function init(){
//        dshop_process
        add_filter('dsps_pyments_get_list', [$this,'dsps_pyments_get_list'], 5, 2 );
        add_filter('ds_dspayment_settings__tabs', [$this,'ds_dspayment_settings__tabs'], 5, 2 );
        add_action('ds_dspayment_settings__do_tab_sections', [$this,'ds_dspayment_settings__do_tab_sections'], 5, 3 );
        add_action('ds_dspayment_settings__do_tab_footer_info', [$this,'ds_dspayment_settings__do_tab_footer_info'], 5, 3 );
        add_filter('dsps_pyments_get_psystem_items', [$this,'dsps_pyments_get_psystem_items'], 5, 2 );
        add_action('dshop_process', [$this,'dshop_process'], 5, 2 );
        $this->name = 'Наличными';
        return true;
//        $option_name = 'rbc_merchant_login';
//        $val = get_option($option_name) ;
//        $this->mrh_login = $val;
//        $option_name = 'rbc_merchant_pass';
//        $val = get_option($option_name) ;
//        $this->mrh_pass1 = $val;
        
        // регистрационная информация (логин, пароль #1)
        // registration info (login, password #1)
        $this->mrh_login = get_option('rbc_merchant_login') ;
        $this->mrh_pass1 = get_option('rbc_merchant_pass1') ;
        $this->mrh_pass2 = get_option('rbc_merchant_pass2') ;
        
        $this->is_test = 1;
        $this->is_test = get_option('rbc_istest') ;
        if($this->is_test == 1){
            $this->mrh_login = get_option('rbc_merchant_login_test') ;
            $this->mrh_pass1 = get_option('rbc_merchant_pass1_test') ;
            $this->mrh_pass2 = get_option('rbc_merchant_pass2_test') ;
        }
        
//        $mrh_login = "demo";
//        $mrh_pass1 = "password_1";
//        $mrh_login = "admin";
//        $mrh_pass1 = "Kd38Whg5fv";

        // номер заказа
        // number of order
        $this->inv_id = 0;

        // описание заказа
        // order description
        $this->inv_desc = "ROBOKASSA Advanced User Guide";
        $this->inv_desc = get_option('rbc_payment_desc') ;

        // сумма заказа
        // sum of order
        $this->out_summ = "8.96";

        // тип товара
        // code of goods
        $this->shp_item = 1;

        // предлагаемая валюта платежа
        // default payment e-currency
        $this->in_curr = "";
        $this->in_curr = "BANKOCEAN2R";

        // язык
        // language
        $this->culture = "ru";

        // кодировка
        // encoding
        $this->encoding = "utf-8";
        
        $this->Receipt = [];
        $this->use_receipt = 0;
        $this->use_receipt = get_option('rbc_use_receipt') ;
        
        
        // Вариант вывода кнопки оплаты
        $this->paybtn_var = get_option('dsp_cash_paybtn_var');
    }
    public function form($oid,$summ,$desc='',$ft=0){
        ob_start();
        $this->inv_id = $oid;
        $this->out_summ = $summ;
//        $this->inv_desc = $desc;
        
//        if(($this->paybtn_var == 'script' && $ft==0) || $ft == 1)
//        echo $this->form_btn();
//        if(($this->paybtn_var == 'script_ext' && $ft==0) || $ft == 1)
//        echo $this->form_btn_ext();
        if(($this->paybtn_var == 'form' && $ft==0) || $ft == 2)
        echo $this->form_ext();
        return ob_get_clean();
    }
    public function form_btn(){
        $mrh_login = $this->mrh_login;
        $mrh_pass1 = $this->mrh_pass1;
        $inv_id = $this->inv_id;
        $inv_desc = $this->inv_desc;
        $out_summ = $this->out_summ;
        $shp_item = $this->shp_item;
        $in_curr = $this->in_curr;
        $culture = $this->culture;
        $encoding = $this->encoding;
        
        $def_sum = $this->out_summ;
        $istest = $this->is_test;
        if($istest == 1 )
            $istest = "&IsTest=$istest";
        else
            $istest = "";
        
        
        // Оплата заданной суммы с выбором валюты на сайте мерчанта
        // Payment of the set sum with a choice of currency on merchant site 

        // формирование подписи
        // generate signature
        //  $crc = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1");
//        $crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item");
        $crc = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1:Shp_action=payment");

        // HTML-страница с кассой
        // ROBOKASSA HTML-page
//        $form = //"<html>".
//                "<script language=JavaScript ".
//                "src='https://auth.robokassa.ru/Merchant/PaymentForm/FormFLS.js?".
//                "MrchLogin=$mrh_login&DefaultSum=$def_sum&InvId=$inv_id&IncCurrLabel=$in_curr".
//                "&Desc=$inv_desc&SignatureValue=$crc&Shp_item=$shp_item".
//                "&Culture=$culture&Encoding=$encoding'></script>"
//                  //. "</html>"
//                ;
        
        $form = //"<html>".
                "<script language=JavaScript ".
                "src='https://auth.robokassa.ru/Merchant/PaymentForm/FormMS.js?".
                "MerchantLogin=$mrh_login&OutSum=$out_summ&InvoiceID=$inv_id".
                "&Description=$inv_desc&SignatureValue=$crc".$istest
                . "&Shp_action=payment"
                . "'></script>"
                  //. "</html>"
                ;
        return $form;
    }
    public function form_btn_ext(){
        $mrh_login = $this->mrh_login;
        $mrh_pass1 = $this->mrh_pass1;
        $inv_id = $this->inv_id;
        $inv_desc = $this->inv_desc;
        $out_summ = $this->out_summ;
        $shp_item = $this->shp_item;
        $in_curr = $this->in_curr;
        $culture = $this->culture;
        $encoding = $this->encoding;
        
        $def_sum = $this->out_summ;
        $istest = $this->is_test;
        if($istest == 1 )
            $istest = "&IsTest=$istest";
        else
            $istest = "";
        
        
        // Оплата заданной суммы с выбором валюты на сайте мерчанта
        // Payment of the set sum with a choice of currency on merchant site 

        // формирование подписи
        // generate signature
        //  $crc = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1");
//        $crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item");
        $crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1:Shp_action=payment");

        // HTML-страница с кассой
        // ROBOKASSA HTML-page
//        $form = //"<html>".
//                "<script language=JavaScript ".
//              "src='https://auth.robokassa.ru/Merchant/PaymentForm/FormFLS.js?".
//              "MrchLogin=$mrh_login&OutSum=$out_summ&InvId=$inv_id&IncCurrLabel=$in_curr".
//              "&Desc=$inv_desc&SignatureValue=$crc&Shp_item=$shp_item".
//              "&Culture=$culture&Encoding=$encoding'></script>"
//                  //. "</html>"
                ;
        $form = //"<html>".
                "<script language=JavaScript ".
                "src='https://auth.robokassa.ru/Merchant/PaymentForm/FormFLS.js?".
                "MrchLogin=$mrh_login&DefaultSum=$def_sum&InvId=$inv_id&IncCurrLabel=$in_curr".
                "&Desc=$inv_desc&SignatureValue=$crc".
//                "&Shp_item=$shp_item".
                "&Culture=$culture&Encoding=$encoding".$istest
                . "&Shp_action=payment"
                ."'></script>"
                  //. "</html>"
                ;
        return $form;
    }
    public function form_ext(){
        $mrh_login = $this->mrh_login;
        $mrh_pass1 = $this->mrh_pass1;
        $inv_id = $this->inv_id;
        $inv_desc = $this->inv_desc;
        $out_summ = $this->out_summ;
        $shp_item = $this->shp_item;
        $in_curr = $this->in_curr;
        $culture = $this->culture;
        $encoding = $this->encoding;
        
//        add_log($out_summ);
        
        global $DSPs;
        $out_summ = $out_summ - $DSPs-> get_payments_summ($inv_id);
        
//        $this->Receipt = ['sno'=>'osn'];
        $Receipt = json_encode($this->Receipt);
        $Receipt = urlencode($Receipt);
        
        $istest = $this->is_test;
        if($istest == 1 )
            $istest = "<input type=hidden name=IsTest value=$istest>";
        else
            $istest = "";
        
        $use_receipt = $this->use_receipt;
        if($use_receipt == 1 )
            $Receipt = "<input type=hidden name=Receipt value=$Receipt>";
        else
            $Receipt = "";
        
        // Оплата заданной суммы с выбором валюты на сайте ROBOKASSA
        // Payment of the set sum with a choice of currency on site ROBOKASSA
        // https://docs.robokassa.ru/?_ga=2.87599644.540070617.1555516949-1254987754.1555516949

//        Описание переменных, параметров и значений
//        https://docs.robokassa.ru/?_ga=2.87599644.540070617.1555516949-1254987754.1555516949#1222
        
//        Код кассы с одной кнопкой «Оплатить»
//        https://docs.robokassa.ru/?_ga=2.87599644.540070617.1555516949-1254987754.1555516949#1224
        
//        Варианты кнопок и форм
//        https://docs.robokassa.ru/?_ga=2.87599644.540070617.1555516949-1254987754.1555516949#1239
        
        // формирование подписи
        // generate signature
//        $crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item");
        $crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1:Shp_action=payment");
        if($use_receipt == 1 )
        $crc  = md5("$mrh_login:$out_summ:$inv_id:$Receipt:$mrh_pass1:Shp_action=payment");

        // форма оплаты товара
        // payment form
//        $img = 'https://auth.robokassa.ru/Merchant/PaymentForm/Images/logo-l.png';
        $form = //"<html>".
              "<form action='https://merchant.roboxchange.com/Index.aspx' method=POST>".
              "<input type=hidden name=MrchLogin value=$mrh_login>".
              "<input type=hidden name=OutSum value=$out_summ>".
              "<input type=hidden name=Desc value='$inv_desc'>".
              "<input type=hidden name=SignatureValue value=$crc>".
              "<input type=hidden name=Shp_action value='payment'>".
              "<input type=hidden name=IncCurrLabel value=$in_curr>".
              "<input type=hidden name=Culture value=$culture>".
              $istest.
              $Receipt.
              "<input type=submit value='Pay'>".
//              '<img src="'.$img.'">'.
              "</form>"
//                . "</html>"
                ;
        $form = //"<html>".
              "<form action='' method=POST>".
              '<input type=hidden name="form-type" value="payment_cashe">'.
              "<input type=hidden name=InvId value=$inv_id>".
              '<input class="form-control mb-2" type="number" name="summ" value="'.$out_summ.'">'.
              '<select class="form-control mb-2" name="paymentmethod_sl" >'.
                '<option value="nal" >Наличный расчет</option>'.
                '<option value="semnal" >Безналичный расчет</option>'.
                '<option value="retake" >Перезабор</option>'.
              '</select>'.
              '<button class="btn btn-primary mr-3" type=submit name="act" value="ps_cash">Оплатить</button>'.
//              '<img src="'.$img.'">'.
              "</form>"
//                . "</html>"
                ;
        return $form;
    }
    public function get_p_info($oid,$istest=0){
//        $mrh_login = $this->mrh_login;
//        $mrh_pass1 = $this->mrh_pass1;
//        $mrh_pass2 = $this->mrh_pass2;
//        $inv_id = $this->inv_id;
//        $inv_desc = $this->inv_desc;
//        $out_summ = $this->out_summ;
//        $shp_item = $this->shp_item;
//        $in_curr = $this->in_curr;
//        $culture = $this->culture;
//        $encoding = $this->encoding;
        
        
//        $this->is_test = 1;
//        $this->is_test = get_option('rbc_istest') ;
        if($istest == 1){
            $mrh_login = get_option('rbc_merchant_login_test') ;
            $mrh_pass1 = get_option('rbc_merchant_pass1_test') ;
            $mrh_pass2 = get_option('rbc_merchant_pass2_test') ;
        }else{
            $mrh_login = get_option('rbc_merchant_login') ;
            $mrh_pass1 = get_option('rbc_merchant_pass1') ;
            $mrh_pass2 = get_option('rbc_merchant_pass2') ;
        }
        
        // MerchantLogin:InvoiceID:Пароль#2
        $crc  = md5("$mrh_login:$oid:$mrh_pass2");
        
        if($istest == 1 )
            $istest = '&IsTest=1';
        else
            $istest = "";
        
        $get = 'https://auth.robokassa.ru/Merchant/WebService/Service.asmx/OpState'
                . '?MerchantLogin='.$mrh_login
                . '&InvoiceID='.$oid
                . $istest
                . '&Signature='.$crc;
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$get); // set url to post to 
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $h); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable 
        $result = curl_exec($ch); // run the whole process 
        curl_close($ch);   
//        $res = json_decode($result);
        return $result;
    }
    
}
$DSPCash = new DSPCash();
