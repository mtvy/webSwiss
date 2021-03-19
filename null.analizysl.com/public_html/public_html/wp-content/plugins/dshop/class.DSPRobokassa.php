<?php
/* 
 * class.DSPRobokassa.php
 * 
 */
/**
 * @package dshop
 */
/*
Plugin Name: DShop Pyment Robokassa
Plugin URI: 
Description: Расширение функционала магазина: Оплата через платёжную систему - Robokassa
Version: 1.0
Author: me
Author URI: my
License: GPLv2 or later
Text Domain: dshop
*/

class DSPRobokassa{
    
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
    
    public $key = 'robokassa';
    public $name = 'Robokassa';
    public $class = 'DSPRobokassa';
    
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
    public $paybtn_var = "no";
    public $is_test = "";
    public $Receipt = "";
    public $use_receipt = 0;
    
    public $img = 'https://auth.robokassa.ru/Merchant/PaymentForm/Images/logo-l.png';
    
    
    public function dsps_pyments_get_list($list = [],$dsps=null){
        $list[$this->key] = $this->name;
        return $list;
    }
    public function ds_dspayment_settings__tabs($tabs = [],$dsps=null){
        $tabs[$this->key.'_options'] = $this->name.' Options';
        return $tabs;
    }
    public function dsps_pyments_get_psystem_items($list = [],$dsps=null){
        $list[$this->key] = $this;
        return $list;
    }
    public function form_access(){
        $ac=true;
        return $ac;
    }
    
    public function init(){
        add_filter('dsps_pyments_get_list', [$this,'dsps_pyments_get_list'], 5, 2 );
        add_filter('ds_dspayment_settings__tabs', [$this,'ds_dspayment_settings__tabs'], 5, 2 );
        add_filter('dsps_pyments_get_psystem_items', [$this,'dsps_pyments_get_psystem_items'], 5, 2 );
        
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
        $this->paybtn_var = get_option('rbc_paybtn_var');
    }
    public function form($oid,$summ,$desc='',$ft=0){
        ob_start();
        $this->inv_id = $oid;
        $this->out_summ = $summ;
//        $this->inv_desc = $desc;
        
        if(($this->paybtn_var == 'script' && $ft==0) || $ft == 1)
        echo $this->form_btn();
        if(($this->paybtn_var == 'script_ext' && $ft==0) || $ft == 1)
        echo $this->form_btn_ext();
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
              "<input type=hidden name=InvId value=$inv_id>".
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
$DSPRobokassa = new DSPRobokassa();
