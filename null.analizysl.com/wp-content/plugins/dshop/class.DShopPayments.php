<?php

/* 
 * class.DShopPayments.php
 */

class DShopPayments
{
    
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
    
    public $pyments = [];
    public $pyments_active = [];
    public $pyments_stats = [];
    public $pyments_items = []; // pay system class object
    
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
    public $paybtn_var = "";
    public $is_test = "";
    public $Receipt = "";
    public $use_receipt = 0;
    
    /**
     * получаем список доступных платёжных систем
     * @return type
     */
    public function get_pyments_list(){
        $list = [];
//        add_filter('dsps_pyments_get_list', [$this,'dsps_pyments_get_list'], 5, 3 );
        $list = apply_filters('dsps_pyments_get_list', $list, $this);
        $this->pyments = $list;
       return $list;
    }
    
    /**
     * получаем набор активных платёжных систем
     * @return type
     */
    public function get_pyments_active(){
        $active = [];
//        add_filter('dsps_pyments_get_list', [$this,'dsps_pyments_get_list'], 5, 3 );            $list = apply_filters('dsps_pyments_get_list', $list, $this);

        $list = $this->get_pyments_list();
        if(count($this->pyments_stats) == 0)
            $active = $list;
        else{
            foreach ($this->pyments_stats as $pkey => $v) {
                if($v){
                    $active[$pkey] = $list[$pkey];
                }
            }
        }
//        $this->pyments = $active;
       return $active;
    }
    
    public function get_pyments_items(){
        $list = [];
        $list = apply_filters('dsps_pyments_get_psystem_items', $list, $this);
        $this->pyments_items = $list;
       return $list;
    }
    
    public function build_checklist ($items=[]){
        $list = DShop::_get_check_list($items);
        return $list;
    }
    
    public function init(){
//        $this->pyments = get_option('dsps_pyments',[]) ; // dshop pyments - pyments all
//        $this->pyments_active = get_option('dsps_active',[]) ; // dshop pyments - pyments active
        $this->pyments_stats = get_option('dsps_stats',[]) ; // dshop pyments - pyments status
        
        return $this;
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
        $this->inv_desc = get_option('rbc_payment_desc') ;
        
        $this->Receipt = [];
        $this->use_receipt = 0;
        $this->use_receipt = get_option('rbc_use_receipt') ;
        
        
        // Вариант вывода кнопки оплаты
        $this->paybtn_var = get_option('rbc_paybtn_var');
    }
    public function add_payment($oid,$summ,$way,$fee,$email,$sv,$istest=0,$result=''){
        global $wpdb;
//        global $DSPs;
//        return 0;
            $dsp = new DShopPayment();
            $dso_userId_ =  get_post_meta( $oid, 'dso_userId', true );
            $dso_total_ =  get_post_meta( $oid, 'dso_cost', true );
            
            $dso_total_ = apply_filters('ds_order_total', $dso_total_,$oid);
            
            
            $dso_status = $dso_total_ >= $summ;
            
        
            $title = 'Оплата по счёту '.$oid.' на сумму '.$summ;
            if($istest) $title .= ' (тест)';
            $user = wp_get_current_user();
            $user_id = $dso_userId_;
//            if($user->exists()){
//                $user_id = $user->ID;
//            }
            $post_data = array(
            //	'post_title'    => wp_strip_all_tags( $_POST['post_title'] ),
//                'post_content'  => wp_strip_all_tags( $_POST['post_content'] ),
                'post_title'    => wp_strip_all_tags( $title ),
                'post_content'  => '',
                'post_type'   => 'dspayment',
                'post_status'   => 'publish', // 'draft' | 'publish' | 'pending'| 'future' | 'private',  
                'post_author'   => $user_id,
            //	'post_category' => array( 8,39 )
            );
            // Вставляем запись в базу данных
            $post_id = wp_insert_post( $post_data, true );
            if(is_wp_error($post_id))
                return $post_id;
//            $this->meta_fields_order=$d;
//            $this->meta_fields_order=[
//                'dso_status'=>'created',
//                'dso_userId'=>$user_id,
//                'dso_user_name'=>$f,
//                'dso_user_lastname'=>$l,
//                'dso_user_sname'=>$s,
//                'dso_user_phone'=>$p,
//                'dso_user_email'=>$e,
//                'dso_user_addres'=>$a,
//                'dso_agriments'=>'1',
//
//                'dso_items_count'=>0,
//
//                'dso_count'=>0,
//                'dso_cost'=>0,
//
//        //        'news_type'=>'Тип новостей',
//        //        'news_source'=>'Источник'
//                ];
//            $meta_fields = [
//                    'dsop_ID' => 'ID заказа',
//                    'dsop_status'=>'Статус оплаты',
//                    'dsop_userId'=>'Заказчик (id)',
//                    'dsop_outsumm'=>'Оплаче-ваемая(-нная?) сумма',
//                    'dsop_fee'=>'Коммиссия',
//                    'dsop_signatura'=>'Проверочное число',
//                    'dsop_user_email'=>'Почта указанная при оплате',
//                    'dsop_istest'=>'Тест',
//                    'dsop_result_post'=>'Все пришедшие данные',
//                    'dsop_cost'=>'Сумма в заказе',
//                ];
//            $oid,$summ,$fee,$email,$sv,$istest=0,$result
            $meta_fields = [
                    'dsop_ID' => $oid,
                    'dsop_status'=> $dso_status?'1':'0',
                    'dsop_userId'=>$dso_userId_,
                    'dsop_outsumm'=>$summ,
                    'dsop_payway'=>$way,
                    'dsop_fee'=>$fee,
                    'dsop_signatura'=>$sv,
                    'dsop_user_email'=>$email,
                    'dsop_istest'=>$istest,
                    'dsop_result_post'=>$result,
                    'dsop_cost'=>$dso_total_,
                ];
            $nm = 'dso_';
            foreach($meta_fields as $meta_key=>$meta_val){
                add_post_meta( $post_id, $meta_key, $meta_val, 1 );
            }
            if($summ >= $dso_total_){
                // dspayment 
                update_post_meta( $oid, 'dso_status', 'payd' );
            }else if($this->get_payments_summ($oid) >= $dso_total_){
                // dspayment 
                update_post_meta( $oid, 'dso_status', 'payd' );
            }
            
            
        /*
         * save discont log
         */
        $dso_discont= $wpdb->prefix . "dso_discont";
        $dso_discont_name= $wpdb->prefix . "dso_discont_name";
        $discont = DShop::_get_cart_discont('laborant');
        $discont_from = DShop::get_cart_discont_from('laborant');
        if($discont){
            $q = "insert into $dso_discont set `oid`='$oid', `uid`='$discont_from', `discont` = '$discont', `name` = (select dn.`id` from $dso_discont_name dn where dn.`name` = 'laborant') ";
            $wpdb->query($q);
        }
        
        $puid =  get_post_meta( $oid, 'dso_puid', true );
        $d = new MedLabCardBonus();
        $discontP = $d->discont($puid,false);
        if($discontP){
            $q = "insert into $dso_discont set `oid`='$oid', `uid`='$puid', `discont` = '$discontP', `name` = (select dn.`id` from $dso_discont_name dn where dn.`name` = 'user_card') ";
            $wpdb->query($q);
        }
        /*
         * save discont order percent
         */
        $discont = $d->discont($puid);
        update_post_meta( $oid, 'dso_discont', $discont );
        
        /*
         * save total order summ
         */
        $dso_total_ =  get_post_meta( $oid, 'dso_cost', true );
        $dso_total_ = apply_filters('ds_order_total', $dso_total_,$oid);
        update_post_meta( $oid, 'dso_total', $dso_total_ );
        
        /*
         * save payd order summ
         */
        $payd = $this-> get_payments_summ($oid);
        update_post_meta( $oid, 'dso_payd', $payd );
            
        DShop::_remove_from_cart_discont('laborant');
        return $post_id;
    }
    public function get_payments_summ( $dsoID ) {
        $total_payd = 0;
    // параметры по умолчанию
     $args = [
         
        'numberposts' => 1000,
        'offset'    => 0,
        'category'    => 0,
        'orderby'     => 'date',
        'order'       => 'DESC',
        'include'     => array(),
        'exclude'     => array(),
        'meta_key'    => 'dsop_ID',
        'meta_value'  => $dsoID,
        'post_type'   => 'dspayment',
        'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
     ];
        $query = new WP_Query( $args );
        $count =  $query->found_posts;
        if($count){
            $posts = get_posts( $args );
            foreach( $posts as $num=>$item ){
                $total_payd +=  get_post_meta( $item->ID, 'dsop_outsumm', true );
            }
        }
        return $total_payd;
    }
}
global $DSPs;
$DSPs = new DShopPayments();