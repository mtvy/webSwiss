<?php

/* 
 * class.DShopExtensionMedLab.php
 */

include_once 'class.MedLabCardBonus.php';
include_once 'trait.DSHelperFields.php';
include_once 'xml_delivery_report.php';

class DShopExtensionMedLab {
    use 
            DSHelperFields
            ;
    public $meta_name = [ // метабоксы
//        'order'=>'Заказ',
//        'user'=>'Заказчик',
//        'payment_message'=>'Сообщение о проверке оплаты',
//        'items'=>'Позиции',
//        'payments'=>'Оплаты',
        'analise_query'=>'Заявка',
        'analise_desc'=>'Подробности',
        ];
    public $meta_pos = [ // позиции метабоксов
//        'order'=>'side',
//        'user'=>'normal',
//        'payment_message'=>'normal',
//        'items'=>'normal',
//        'payments'=>'normal',
        'analise_query'=>'normal',
        'analise_desc'=>'normal',
        ];
    public $meta_weith = [ // позиции метабоксов
//        'order'=>'1',
//        'user'=>'1',
//        'payment_message'=>'1',
//        'items'=>'1',
//        'payments'=>'1',
        
//        'analise_query'=>'5',
//        'analise_desc'=>'5',
        ];
    private static $instance = null;
	private static $initiated = false;
    public function __construct() {
        $this->init_actions();
    }
    public function init(){
//        if(current_user_can('manage_options'))
//            add_log(mldr_xml_data());
        
//		if ( ! self::$initiated ) {
		if ( ! DShopExtensionMedLab::$initiated ) {
//        add_action('manage_'.$this->name.'_posts_custom_column',
//                [$this,'fill_views_column'], 5, 2 );
            $this->init_order();
//            self::$instance = $alleg;
//            self::$initiated = true;
            DShopExtensionMedLab::$instance = $this;
            DShopExtensionMedLab::$initiated = true;
        }
    }
    public static function _init(){
		if ( ! self::$initiated ) {
            $alleg = new DShopExtensionMedLab();
            $alleg->init();
            self::$instance = $alleg;
            self::$initiated = true;
        }
    }
    public function init_order(){
    }
    public function init_actions(){
        add_action('admin_notices', [$this,'_notices']);
        
        add_action('ds_dsorder_post_display_meta_box_user__fields_out',
                [$this,'meta_box_user__fields_out'], 5, 3 );
        
        add_action('ds_dsorder_post_display_meta_box__add',
                [$this,'add_metabox'], 5, 2 );
        
        add_action('ds_dsorder_post_display_meta_box__add_onpos',
                [$this,'add_metabox_onpos'], 5, 3 );
        
        add_action('ds_dsorder_post__save',
                [$this,'save_post'], 5, 3 );
        
        add_filter('ds_dsorder_post_display_meta_box_order__out_status', 
                [$this,'change_status_list'], 5, 3 );
        add_filter('ds_dsorder_post_display_meta_box_order__class_out_status', 
                [$this,'change_status_list__order_list_class'], 5, 4 );
        
        add_action('ds_options_add_field',
                [$this,'add_ds_options_params'], 5, 3 );
        
        add_filter('ds_styling_tpl_name', 
                [$this,'ds_styling_tpl_name'], 5, 1 );
        
        
        add_filter('ds_dsproduct_data_properties', [$this,'ds_dsproduct_data_properties'], 5, 2 );
        add_filter('ds_dsproduct_data_placeholders', [$this,'ds_dsproduct_data_placeholders'], 5, 2 );
        add_filter('ds_dsproduct_data_placeholders_second', [$this,'ds_dsproduct_data_placeholders_second'], 5, 2 );
        add_filter('ds_dsproduct__tpl_name', [$this,'ds_dsproduct__tpl_name'], 5, 2 );
        add_filter('ds_dsproduct_post_init_defaults', [$this,'ds_dsproduct_post_init_defaults'], 5, 2 );
        add_filter('ds_dsproduct_btn_addtocart_name', [$this,'ds_dsproduct_btn_addtocart_name'], 5, 1 );
        add_filter('ds_dsproduct_btn_subm_name', [$this,'ds_dsproduct_btn_subm_name'], 5, 1 );
        
        add_action('save_post', [$this,'redirect_after_edit'], 5, 3 );
        
        
        add_action('ds_chochout_form_fields', [$this,'ds_chochout_form_fields'], 5, 1 );
        add_action('ds_chochout_form_fields_2', [$this,'ds_chochout_form_fields_2'], 5, 1 );
        add_action('ds_process_create_order_success', [$this,'ds_process_create_order_success'], 5, 2 );
        add_action('ds_create_order_email_send', [$this,'ds_create_order_email_send'], 5, 1 );
        
        add_action('dshop_process', [$this,'dshop_process'], 5, 2 );
        
        
        add_filter('ds_ajax_addtocart_result_ok', [$this,'ds_ajax_addtocart_result_ok'], 5, 3 );
        add_filter('ds_ajax_addtocart_result_err', [$this,'ds_ajax_addtocart_result_err'], 5, 3 );
        
        add_filter('dso_create_order__initmetafields', [$this,'dso_create_order__initmetafields'], 5, 2 );
        
        add_action('ds_cart_total__row_pre', [$this,'ds_cart_total__row_pre'], 5, 3 );
        add_filter('ds_cart_total', [$this,'ds_cart_total'], 5, 2 );
        
        add_action('ds_order_total__row_pre', [$this,'ds_order_total__row_pre'], 5, 3 );
        add_filter('ds_order_total', [$this,'ds_order_total'], 5, 2 );
        
        add_action('ds_order_blank_total__row_pre', [$this,'ds_order_blank_total__row_pre'], 5, 3 );
        
        add_action('medlab_cron_start', [$this,'medlab_cron_start'], 5, 1);
        add_action('medlab_cron_finish', [$this,'medlab_cron_finish'], 5, 1 );
        add_action('medlab_delivery_report_send', [$this,'medlab_delivery_report_send'], 5, 1 );
        
        
    }
    
    public function medlab_cron_start($vars = []){
//        save_log('medlab_cron_start ',$vars,'info','ml_cron');
    }
    
    public function medlab_cron_finish($vars = []){
//        save_log('medlab_cron_finish','period: '. number_format ($vars['end'] - $vars['start'],4),'info','ml_cron');
    }
    
    public function medlab_delivery_report_send($vars = []){
        global $ht;
//            $active_report = get_option('ml_delivery_report_active');
//            if($active_report ==1 )$send = 'ok';
//            else $send = 'no';
//            save_log('medlab_delivery_report_send','status: '.$send,'info','ml_cron');
        $istest = $ht->postget('test','0',FILTER_SANITIZE_STRING);
        if( $istest!=1){
            $active_report = get_option('ml_delivery_report_active');
            if($active_report !=1 )return ;
        
            $starttime = current_time('H:i');
            if($starttime != '21:00')return;
        }
        $date = current_time('Y-m-d');
        
        $mails = get_option('ml_delivery_report_mails');
        $mails = explode("\n",$mails);
        $mls = [];
        foreach($mails as $m){
            $ml = explode(":",$m);
            if(count($ml)==2 && $ml[1]==0)continue;
            $mls[]=$ml[0];
        }
        if(count($ml)==0)return;
        
        //$attachments[] = $_FILES[$att]['tmp_name'];
        $attachments = array();
        $file = get_file_delivery_report();
        if($file)
            $attachments[] = $file;
        $sub = 'Delivery raport';
        $head = array('content-type: text/html');
        $message_body = 'Отчёт о доставках за '.$date.'<br/>';
        if(count($attachments) == 0)$message_body .= 'Доставок не было.';
        $message_body = '<!DOCTYPE html><html lang="ru"><body>' . $message_body . '</body></html>';
        $to = '';
        $tpl = $message_body;
        foreach($mls  as  $to){
            $res = wp_mail($to, $sub, $tpl, $head, $attachments);
        }
        save_log('medlab_delivery_report_send ', $file,'info','ml_cron');
        if($file)unset($file);
    }
    
    public function ds_order_blank_total__row_pre($total=0, $td_cou = 8,$oid = null){
        $uid = 0;
        $discontP = 0;
        $currency_short = get_option('currency_short','rub');
        $discont = 0;
        if($oid>0){
            $dso_puid_ =  get_post_meta( $oid, 'dso_puid', true );
            $state_ =  get_post_meta( $oid, 'dso_status', true );
            if($dso_puid_){
                $uid = $dso_puid_;
                if($state_ == 'payd' || $state_ == 'paychecked' || $state_ == 'query_sent'){
                    $discontP =  get_post_meta( $oid, 'dso_discont', true );
                }else{
                    $d = new MedLabCardBonus();
                    $discontP = $d->discont($uid);
                }
                if($discontP>100)$discontP = 100;
                $discont = ($total*$discontP)/100;
            }
        }
        /*
    ?><?=$uid?> - <?=$d->uid?> - <?=$d->card?> - 
    <tr>
      <td colspan="5"><pre><?php var_dump($uid); ?></pre></td>
    </tr>/**/
    ?>
    <tr>
      <td colspan="5">Сумма:</td>
      <?php
      for($i=7;$i<$td_cou;$i++){
        echo '      <td> </td>';
      }
      ?>
      <td width="" valign="top" align="right" nowrap> <?=$total.' '.$currency_short?></td>
      <td width="" valign="top" align="center" nowrap> </td>
    </tr>
    <tr>
      <td colspan="5">Скидка:</td>
      <?php
      for($i=7;$i<$td_cou;$i++){
        echo '      <td> </td>';
      }
      ?>
      <td width="" valign="top" align="right" nowrap><?=$discont.' '.$currency_short?></td>
      <td width="" valign="top" align="center" nowrap><?=$discontP.' % '?></td>
    </tr>
    <?php
    }
    
    public function ds_order_total__row_pre($total=0, $td_cou = 8,$oid = null){
        $uid = 0;
        $discontP = 0;
        $currency_short = get_option('currency_short','rub');
        $discont = 0;
        if($oid>0){
            $dso_puid_ =  get_post_meta( $oid, 'dso_puid', true );
            $state_ =  get_post_meta( $oid, 'dso_status', true );
            if($dso_puid_){
                $uid = $dso_puid_;
                if($state_ == 'payd' || $state_ == 'paychecked' || $state_ == 'query_sent'){
                    $discontP =  get_post_meta( $oid, 'dso_discont', true );
                }else{
                    $d = new MedLabCardBonus();
                    $discontP = $d->discont($uid);
                }
                if($discontP>100)$discontP = 100;
                $discont = ($total*$discontP)/100;
            }
        }
    ?>
    <tr>
      <td colspan="5">Скидка:</td>
      <?php
      for($i=7;$i<$td_cou;$i++){
        echo '      <td> </td>';
      }
      ?>
      <td><?=$discontP.' % '?></td>
      <td><?=$discont.' '.$currency_short?></td>
    </tr>
    <?php
    }
    
    public function ds_order_total($total=0,$oid = null){
        $uid = 0;
        $discontP = 0;
        $currency_short = get_option('currency_short','rub');
        $discont = 0;
        if($oid>0){
            $dso_puid_ =  get_post_meta( $oid, 'dso_puid', true );
            $state_ =  get_post_meta( $oid, 'dso_status', true );
            if($dso_puid_){
                $uid = $dso_puid_;
                if($state_ == 'payd' || $state_ == 'paychecked' || $state_ == 'query_sent'){
                    $discontP =  get_post_meta( $oid, 'dso_discont', true );
                }else{
                    $d = new MedLabCardBonus();
                    $discontP = $d->discont($uid);
                }
                if($discontP>100)$discontP = 100;
                $discont = ($total*$discontP)/100;
            }
        }
        $total = $total-$discont;
        if($total < 0) $total = 0;
        return $total;
    }
    
    public function ds_cart_total__row_pre($total=0, $td_cou = 8,$uid = 0){
        $cart_=null;
        $ccount = dshop::_count_in_cart();
        if(count($ccount)>0){
            $cart_ = $_SESSION['ds_cart'];

        }
        $r_access = [];
        $r_access [] ='ml_patient';
        $r_access [] ='administrator';
        $user = wp_get_current_user();
        if(count( array_intersect($r_access, (array) $user->roles ) ) > 0 ){
            $uid = $user->ID;
        }
        
        $currency_short = get_option('currency_short','rub');
        $d = new MedLabCardBonus();
        $discontP = $d->discont($uid);
        if($discontP>100)$discontP = 100;
        $discont = ($total*$discontP)/100;
        if(0&&current_user_can('manage_options')){
    ?>
    <tr>
      <td colspan="5"><pre><?php print_r($d); ?></pre></td>
    </tr>
    <tr>
      <td colspan="5"><pre><?php print_r($user); ?></pre></td>
    </tr>
    <tr>
      <td colspan="5"><pre><?php var_dump($cart_); ?></pre></td>
    </tr>
    <tr>
      <td colspan="5"><pre><?php print_r($uid); ?></pre></td>
    </tr>
    <?php
        }
    ?>
    <tr>
      <td colspan="5">Скидка:</td>
      <td><?=$discontP.' % '?></td>
      <td><?=$discont.' '.$currency_short?></td>
      <?php
      for($i=7;$i<$td_cou;$i++){
        echo '      <td> </td>';
      }
      ?>
    </tr>
    <?php
    }
    
    public function ds_cart_total($total=0,$uid = 0){
        $ccount = dshop::_count_in_cart();
        if(count($ccount)>0){
            $cart_ = $_SESSION['ds_cart'];

        }
        $r_access = [];
        $r_access [] ='ml_patient';
        $r_access [] ='administrator';
        $user = wp_get_current_user();
        if(count( array_intersect($r_access, (array) $user->roles ) ) > 0 ){
            $uid = $user->ID;
        }
        $d = new MedLabCardBonus();
        $discontP = $d->discont($uid);
        if($discontP>100)$discontP = 100;
        $discont = ($total*$discontP)/100;
        return $total-$discont;
    }
    
    public function dso_create_order__initmetafields($metafields=[],$dsobject=null){
        return $metafields;
    }
    /**
     * добавление параметров к ответу ajax при удачном добавлении товара в корзину
     * @param array $res
     * @param type $pid
     * @param type $added
     * @return string
     */
    public  function ds_ajax_addtocart_result_ok($res,$prodId,$added){
        $res['act'] = 'no';
//        $ds_item_add_count_def = get_option('ds_item_add_count_def',1);
//        $ds_item_add_min = get_option('ds_item_add_min',1);
//        $ds_item_add_max = get_option('ds_item_add_max',1);
        $ds_cart_item_max = get_option('ds_cart_item_max',1);
//        $ds_cart_items_max = get_option('ds_cart_items_max',1000);
        
        
    //        $WP_Post = new WP_Post();
    //        $post = $WP_Post::get_instance( $post_id );
//        $oargs = [
//    //    	'ID' => $oid,
//    //        'author'  => $user->ID,
//            'numberposts' => 1000,
//            'offset'    => 0,
//        //	'numberposts' => $count,
//        //	'offset'    => $offset,
//        //	'category'    => 0,
//            'orderby'     => 'date',
//            'order'       => 'DESC',
//    //    	'include'     => [$oid],
//        //	'exclude'     => array(),
//            'meta_key'    => 'dsp_pid',
//            'meta_value'  => $pid,
//            'post_type'   => 'dsproduct',
//            'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
//        ];
//        $query = new WP_Query( $oargs );
//        $count =  $query->found_posts;
//        if($count>0 ){
//            $nm = 'dsp_';
//            foreach( $posts as $item ){
//                $data = [];
////                $data['product-exists']=1;
////                $data['post_id']=$item->ID;
////                $data['[__prod_title__]'] =  get_post_meta( $item->ID, $nm.'title', true );
////                $data['[__prod_short__]'] =  get_post_meta( $item->ID, $nm.'short', true );
////                $data['[__prod_desc__]'] =  get_post_meta( $item->ID, $nm.'desc', true );
////                $data['[__prod_cost__]'] =  get_post_meta( $item->ID, $nm.'cost', true );
////                $data['[__prod_quantity__]'] =  get_post_meta( $item->ID, $nm.'count', true );
//                $data['[__prod_count_max__]'] =  get_post_meta( $item->ID, $nm.'max', true );
//                $data['[__prod_count_min__]'] =  get_post_meta( $item->ID, $nm.'min', true );
////                $data['[__prod_code__]'] =  get_post_meta( $item->ID, $nm.'code', true );
////                $data['[__prod_id__]'] =  get_post_meta( $item->ID, $nm.'pid', true );
////                $data['[__prod_properties__]'] = '';
////                $data['[__prod_properties__]'] = apply_filters('ds_dsproduct_data_properties', $data['[__prod_properties__]'], $item->ID);
//                $ds_item_add_max = $data['[__prod_count_max__]'];
//                $ds_item_add_min = $data['[__prod_count_min__]'];
////                if($ds_item_add_max)
//            }
//        }
        $ds_item_add_count_def = $added;
//        if($ds_item_add_min == $ds_item_add_max && $ds_cart_item_max <= $ds_item_add_count_def )
        if(isset( $_SESSION['ds_cart'][$prodId] ) && $_SESSION['ds_cart'][$prodId] >= $ds_cart_item_max)
            $res['act'] = 'block_button';
//            $res['log1'] = '$_SESSION';
//            $res['log'] = $_SESSION;
        return $res;
    }
    /**
     * добавление параметров к ответу ajax при не удачном добавлении товара в корзину
     * @param array $res
     * @param type $prodId
     * @param type $added
     * @return string
     */
    public  function ds_ajax_addtocart_result_err($res,$prodId,$added){
        $res['act'] = 'no';
        $ds_cart_item_max = get_option('ds_cart_item_max',1);
        if(isset( $_SESSION['ds_cart'][$prodId] ) && $_SESSION['ds_cart'][$prodId] >= $ds_cart_item_max)
            $res['act'] = 'block_button';
//            $res['log1'] = '$_SESSION';
//            $res['log'] = $_SESSION;
        return $res;
    }
    
    public  function dshop_process($ftype,$obj){
        if($ftype=='add_patient')$this->prc_add_patient($obj);
        if($ftype=='order_status')$this->prc_set_order_status();
        if($ftype=='query_sent_d')$this->prc_set_order_sentd();
        if($ftype=='update_account_ml')$this->prc_update_account_ml();
//        add_log('dshop_process '.$ftype);
    }
    
    
    public function prc_update_account_ml(){
        global $ht;
        global $wpdb,$is_ajax;
        $is_ajax = false;
        $res=false;
        $res_mess=[];
        
        $l = filter_input(INPUT_POST, 'lnm',FILTER_SANITIZE_STRING);
        $f = filter_input(INPUT_POST, 'fnm',FILTER_SANITIZE_STRING);
        $s = filter_input(INPUT_POST, 'snm',FILTER_SANITIZE_STRING);
//        $e = filter_input(INPUT_POST, 'eml',FILTER_VALIDATE_EMAIL);
        $p = filter_input(INPUT_POST, 'phn',FILTER_SANITIZE_NUMBER_INT);
//        $a = filter_input(INPUT_POST, 'adr',FILTER_SANITIZE_STRING);
//        $d = filter_input(INPUT_POST, 'dlv',FILTER_SANITIZE_STRING);
//        $c = filter_input(INPUT_POST, 'chk',FILTER_SANITIZE_NUMBER_INT);
        
        $puid = filter_input(INPUT_POST, 'puid',FILTER_SANITIZE_NUMBER_INT);
        $user_card = filter_input(INPUT_POST, 'ucard',FILTER_SANITIZE_STRING);
        $passnum = filter_input(INPUT_POST, 'upass',FILTER_SANITIZE_STRING);
        $id_citizen = filter_input(INPUT_POST, 'uidsitiz',FILTER_SANITIZE_STRING);
        $c = 1;
        $user = wp_get_current_user();
        $r_access = [];
        $r_access [] ='administrator';
        //$r_access [] ='ml_administrator';
        //$r_access [] ='ml_manager';
        //$r_access [] ='ml_doctor';
        $r_access [] ='ml_procedurecab';
//        $puid = $ht->postget( 'puid', $user->ID, FILTER_SANITIZE_NUMBER_INT);
        if( $ht->access($r_access)){
            $user = get_userdata($puid);
        }else{
            return;
        }
//        $user = wp_get_current_user();
//        $user = wp_get_user();
        if($user->exists()){
            $user_id = $user->ID;
            $userdata = array(
                'ID'              => $user->ID,  // когда нужно обновить пользователя
                'last_name'       => $l, // обязательно
                'first_name'      => $f, // обязательно
//                'user_email'   => $e, // disabled usage update email
            );
//                            add_log($userdata);
            $user_id = wp_update_user( $userdata ); //  return id or error
            update_user_meta ( $user_id, 'second_name', $s );
            update_user_meta( $user_id,'phone', $p );
//            update_user_meta( $user_id,'adres', $a );
//            update_user_meta( $user_id,'deliv', $d );
            
            update_user_meta( $user_id,'card_numer', $user_card );
            update_user_meta( $user_id,'passnum', $passnum );
            update_user_meta( $user_id,'id_citizen', $id_citizen );
        }
        
//        if(is_user_logged_in())
//        $user = $this->update_account($l,$f,$s,$e,$p,$a,$d);
//        if(!is_wp_error($user)){
//            $res_mess[] = 'Профиль обновлён.';
//            $res=1;
//        }else{
//            $res_mess[] = 'Профиль не обновлён.';
//            foreach ( $user->get_error_messages() as $message ) {
//                $res_mess[] = $message;
//            }
//        }
//        foreach ( $res_mess as $message ) {
//            add_log($message);
//        }
        
        if($is_ajax){
                    $out=array();
                    echo json_encode($out);
            //        echo "{ts:'hi'}";
                    exit;
        }else{
            if($res){
//                switch ($go) {
//                    case 'checkout':
//                        wp_redirect('/my-account');
//                        break;
//                    case 'cart':
//                    default:
//                        wp_redirect('/my-account');
//                        break;
//                }
    //            wp_redirect($_SERVER['HTTP_REFERER'].'?active=1');
//                if(count($_SESSION['ds_cart'])==0)
//                    wp_redirect('/cart');
//                else wp_redirect('/checkout');
    //            wp_redirect(esc_url(home_url('/кабинет/')));
                wp_redirect($_SERVER['HTTP_REFERER']);
                exit();
            }
            if(!$res){
//                add_log($res_mess);
//                wp_redirect('/cart');
//                exit();
            }
        }
    }
    
    public function prc_set_order_sentd(){
        $name = 'dso_send_to_doctor';
        $orderId = filter_input(INPUT_POST, 'oid',FILTER_SANITIZE_NUMBER_INT);
        $val = 'sent';
        update_post_meta( $orderId, 'dso_send_to_doctor', $val );
    }
    
    public function prc_set_order_status(){
        $r_access = [];
        $r_access [] ='administrator';
        $r_access [] ='ml_administrator';
        $r_access [] ='ml_manager';
//        $r_access [] ='ml_doctor';
        $r_access [] ='ml_procedurecab';
        $user = wp_get_current_user();
        if(count( array_intersect($r_access, (array) $user->roles ) ) == 0 ){
            wp_redirect($_SERVER['HTTP_REFERER']);
            exit();
        }
        
        $act = filter_input(INPUT_POST, 'act',FILTER_SANITIZE_STRING);
        $oid = filter_input(INPUT_POST, 'oid',FILTER_SANITIZE_NUMBER_INT);
        
        switch($act){
            case 'pending':$this->prc_order__set_pending($oid); break;
            case 'send_query':$this->prc_order__send_query($oid); break;
            default: break;
        }
    }
    
    public function prc_order__set_pending($orderId){
        $status = 'pending';
        $state_ =  get_post_meta( $orderId, 'dso_status', true );
        update_post_meta( $orderId, 'dso_status', $status );
        add_log('pending');
        wp_redirect($_SERVER['HTTP_REFERER']);
        exit();
    }
    
    public function prc_order__send_query($oid){
        $object=null; $orderId=$oid; $item=null;
        $this->save_post($object, $orderId, $item);
        add_log('send_query');
        wp_redirect($_SERVER['HTTP_REFERER']);
        exit();
    }
    
//    public function prc_order_status_send_query($oid){
//        add_log('prc_order_send_query');
//        wp_redirect($_SERVER['HTTP_REFERER']);
//        exit();
//    }
    
    public  function prc_add_patient($obj=null){
        global $wpdb,$is_ajax;
        $is_ajax = false;
        $res=false;
        $res_mess=[];
//        add_log('prc_add_patient ');
        
        $l = filter_input(INPUT_POST, 'last_name',FILTER_SANITIZE_STRING);
        $f = filter_input(INPUT_POST, 'first_name',FILTER_SANITIZE_STRING);
        $s = filter_input(INPUT_POST, 'second_name',FILTER_SANITIZE_STRING);
        $e = filter_input(INPUT_POST, 'user_email',FILTER_VALIDATE_EMAIL);
        $p = filter_input(INPUT_POST, 'phone',FILTER_SANITIZE_NUMBER_INT);
//        $a = filter_input(INPUT_POST, 'adr',FILTER_SANITIZE_STRING);
//        $d = filter_input(INPUT_POST, 'dlv',FILTER_SANITIZE_STRING);
//        $c = filter_input(INPUT_POST, 'chk',FILTER_SANITIZE_NUMBER_INT);
        $a='';
        $d='';
        $c = 1;
        $bd = filter_input(INPUT_POST, 'born_date',FILTER_SANITIZE_STRING);
        $by = filter_input(INPUT_POST, 'born_year',FILTER_SANITIZE_STRING);
        $g = filter_input(INPUT_POST, 'gender',FILTER_SANITIZE_NUMBER_INT);
        $jd = filter_input(INPUT_POST, 'joined_doctor',FILTER_SANITIZE_NUMBER_INT);
        $cn = filter_input(INPUT_POST, 'card_numer',FILTER_SANITIZE_NUMBER_INT);
        $cc = filter_input(INPUT_POST, 'is_corp_cli',FILTER_SANITIZE_NUMBER_INT);
        $cd = filter_input(INPUT_POST, 'corp_discont_perc',FILTER_SANITIZE_NUMBER_INT);
        $dp = filter_input(INPUT_POST, 'discont_perc',FILTER_SANITIZE_NUMBER_INT);
        
        $l = trim($l);
        $f = trim($f);
        $s = trim($s);
        $e = trim($e);
        $p = trim($p);
        
        $a = trim($a);
        $d = trim($d);
        $c = trim($c);
        
        $bd = trim($bd);
        $by = trim($by);
        $g = trim($g);
        $jd = trim($jd);
        $cn = trim($cn);
        $cc = trim($cc);
        $cd = trim($cd);
        $dp = trim($dp);
        
        $errors = new WP_Error();
        if(1 && !is_user_logged_in())
            $errors->add( 'no_lgd', 'Пользователь не залогинен.' );
        if ( !is_string($l) || strlen($l) ==0 )
            $errors->add( 'no_lnm', 'Отсутствует фамилия.' );
        if ( !is_string($f) || strlen($f) ==0 )
            $errors->add( 'no_fnm', 'Отсутствует имя.' );
        if ( !is_string($s) || strlen($s) ==0 )
            $errors->add( 'no_snm', 'Отсутствует отчество.' );
        if (1 && ( !is_string($e) || strlen($e) ==0 ) )
            $errors->add( 'no_eml', 'Отсутствует почта.' );
        if ( !is_string($p) || strlen($p) ==0 )
            $errors->add( 'no_phn', 'Отсутствует телефон.' );
        if ( !is_string($p) || strlen($p) <7 )
            $errors->add( 'no_phn2', 'Телефон не верный.' );
        
//            // Если возникла хотя бы одна из ошибок.
        if ( ! empty( $errors->get_error_codes() ) ){
            if(!is_wp_error($errors)){
//                $res_mess[] = 'Профиль обновлён.';
//                $res=1;
            }else{
                $res_mess[] = 'Профиль не создан.';
                foreach ( $errors->get_error_messages() as $message ) {
                    $res_mess[] = $message;
                    add_log($message);
                }
            }
            add_log($_POST);
            wp_redirect($_SERVER['HTTP_REFERER']);
            exit();
            return $errors;
        }
            $login = $e;
    //                            $pass=PASS_SECRET.$phone;
                                $pass=$obj->ccab_code_generate(6);
//                                $pass=$inputs['pass'];
                                $m='Новый пароль: '.$pass;
                            add_log($m);
                            //                $phone='+7 (456) 142-54-01';
                                $userdata = array(
                            //	'ID'              => 0,  // когда нужно обновить пользователя
                                    'user_pass'       => $pass, // обязательно
                                    'user_login'      => $e, // обязательно
                                    'user_nicename'   => $e,
                                    'user_url'        => '',
                                    'user_email'      => $e,
                            //	'user_phone'      => '',
                                    'display_name'    => $e,
                                    'nickname'        => $e,
                                    'first_name'      => $f,
                                    'last_name'       => $l,
                                    'description'     => '',
                                    'rich_editing'    => 'false', // false - выключить визуальный редактор
                            //	'user_registered' => '', // дата регистрации (Y-m-d H:i:s) в GMT
                            //	'role'            => 'client', // (строка) роль пользователя
                            //	'jabber'          => '',
                            //	'aim'             => '',
                            //	'yim'             => '',

                                    'admin_color'     => 'fresh',
                                    'show_admin_bar_front'  => 'false',
                                    'locale'          => 'ru_RU',
                                );
    //                            add_log($userdata);
                                $reg_res = wp_insert_user( $userdata, true );
                                $uid=false;
    //                            add_log($reg_res);
                                $error_string=0;
                                if ( is_wp_error( $reg_res ) ) {
                                    $res_mess='Сбой регистрации.';
                                    add_log($res_mess);
                                    return $reg_res;
                                }
                                    $uid=$reg_res;
//                                    $res = true;
                                $user_id = $uid;
                                add_user_meta( $uid,'secod', $pass );

                                $res_mess='Регистрация прошла успешно.';
//                                $res_mess='Вы зарегистрировались.';
//                                    $res_mess='Регистрация прошла успешно, учетная запись ожидает подтверждения администратором.';


                                add_log($res_mess);
////                                add_log($user);
////                                $user_ = ccab_login($login,$pass);
//                                $creds = array();
//                                $creds['user_login'] = $login;
//                                $creds['user_password'] = $pass;
//                                $creds['remember'] = true;
//                                $user = wp_signon($creds,false);
//                                if ( is_wp_error( $user ) ) {
//                                    return $user;
//                                }
//                                $user_id = $user->ID;

                /*          ========== отправка почты ==========           */
                                $send_reg_email=true;
                                if($send_reg_email){
                                    $atr=array();
                                    $atr['__user_l_name__']=$l;
                                    $atr['__user_f_name__']=$f;
                                    $atr['__user_s_name__']=$s;
                                    $atr['__user_login__']=$e;
                                    $atr['__user_pass__']=$pass;

//                                    $atr['__user_url__']=$inputs['url'];
                                    $atr['__user_email__']=$e;
                                    $atr['__user_adres__']=$a;
                                    $atr['__user_delivery__']=$d;
                                    $atr['__user_phone__']=$p;

                                    $mail_name='register_ok';
                                    $subject = 'Новый пользователь';
                                    $test_email =false; // тестирование отправки почты
                                    $test_email =true; // тестирование отправки почты
                                    $test_email = get_option('ds_notification_test')=='1'; // тестирование отправки почты
                                    if($test_email){ // test 2
                                        $to=array();
                                        $to[]='home_work_mail@mail.ru';
//                                        $mres=ccab_get_mail($mail_name,$atr,$to,[],$subject);
                                        $mres=ccab_get_mail($mail_name,$atr,$to,[],$subject);
                                        $mres=ccab_get_mail('new_user',$atr,$to,[],$subject);
                                        if($mres){
                                            add_log('письмо отправленно');
                                        }else{
                                            add_log('письмо НЕ отправленно');
                                        }
                                    }
//                                    $to=array();
//                                    $to[]='9042006@gmail.com';//bz11
//                                    $mres=ccab_get_mail('new_user',$atr,$to,[],$subject);

                                    $subject = 'Вы зарегистрировались.';
                                    $to=array();
                                    $to[]=$e;
                                    $mres=ccab_get_mail($mail_name,$atr,$to,[],$subject);
                                    if($mres){
                                        add_log('На указаный email отправленно письмо с учётными данными');
                                    }else{
//                                        add_log('письмо НЕ отправленно');
                                    }
                                }
                /*          ========== / отправка почты ==========           */
            
            add_user_meta ( $user_id, 'second_name', $s );
            add_user_meta( $user_id,'phone', $p );
            add_user_meta( $user_id,'adres', $a );
            add_user_meta( $user_id,'deliv', $d );
            
//        $bd = filter_input(INPUT_POST, 'born_date',FILTER_SANITIZE_STRING);
//        $by = filter_input(INPUT_POST, 'born_year',FILTER_SANITIZE_STRING);
//        $g = filter_input(INPUT_POST, 'gender',FILTER_SANITIZE_NUMBER_INT);
//        $jd = filter_input(INPUT_POST, 'joined_doctor',FILTER_SANITIZE_NUMBER_INT);
//        $cn = filter_input(INPUT_POST, 'card_numer',FILTER_SANITIZE_NUMBER_INT);
//        $cc = filter_input(INPUT_POST, 'is_corp_cli',FILTER_SANITIZE_NUMBER_INT);
//        $cd = filter_input(INPUT_POST, 'corp_discont_perc',FILTER_SANITIZE_NUMBER_INT);
//        $dp = filter_input(INPUT_POST, 'discont_perc',FILTER_SANITIZE_NUMBER_INT);
        
            add_user_meta( $user_id,'born_date', $bd );
            add_user_meta( $user_id,'born_year', $by );
            add_user_meta( $user_id,'gender', $g );
            add_user_meta( $user_id,'joined_doctor', $jd );
            add_user_meta( $user_id,'card_numer', $cn );
            add_user_meta( $user_id,'is_corp_cli', $cc );
            add_user_meta( $user_id,'corp_discont_perc', $cd );
            add_user_meta( $user_id,'discont_perc', $dp );
        
        add_log($_POST);
        wp_redirect($_SERVER['HTTP_REFERER']);
        exit();
    }
    
    public function ds_create_order_email_send($atr){
//        add_log('ds_create_order_email_send');
        $user = wp_get_current_user();
        //$user = wp_get_current_user();
        $r_access = [];
        $r_access [] ='administrator';
        $r_access [] ='ml_administrator';
        $r_access [] ='ml_manager';
        $r_access [] ='ml_doctor';
        $r_access [] ='ml_procedurecab';
        if(count( array_intersect($r_access, (array) $user->roles ) ) > 0 ){
//        if(current_user_can( 'manage_options' ) || in_array( 'ml_doctor', (array) $user->roles ) ){
            $email = filter_input(INPUT_POST, 'eml-copy',FILTER_VALIDATE_EMAIL);
            if($email){
                
                $mail_name='order-success';
                $subject = 'Новый заказ.';
                $to=array();
                $to[]=$email;
                $mres=ccab_get_mail($mail_name,$atr,$to,[],$subject);
                if($mres){
                    add_log('На указаный email отправленно копия письма с данными о заказе');
                }else{
                    add_log('письмо НЕ отправленно');
                }
            }
        }
    }
    
    public function ds_process_create_order_success($user , $order){
//        add_log('ds_process_create_order_success');
//        trait.DSGet.php::create_order
//        $order=[];
//        $order['ID'] = $orderId;
//        $order['items_count'] = $dso_items_count;
//        $order['count'] = $dso_count;
//        $order['total'] = $total;
        $orderId = $order['ID'];
        $patient_id = $user->ID;
        $doctor_id = 0;
        $agent_id = 0;
        $doctor_rent = 0;
        $agent_rent = 0;
        $patients_doctorid = (int)get_user_meta($user->ID,'joined_doctor',1);
        if($patients_doctorid){
            $doctor_id = $patients_doctorid;
            $doctor_agentid = (int)get_user_meta($doctor_id,'agent_id',1);
            $doctor_rate = (int)get_user_meta($doctor_id,'d_rate',1);
            if($doctor_agentid){
                $agent_id = $doctor_agentid;
                $agent_rate = (int)get_user_meta($agent_id,'a_rate',1);
            }
        }
        //$user = wp_get_current_user();
        $r_access = [];
        $r_access [] ='administrator';
        $r_access [] ='ml_administrator';
        $r_access [] ='ml_manager';
        $r_access [] ='ml_doctor';
        $r_access [] ='ml_procedurecab';
        if(count( array_intersect($r_access, (array) $user->roles ) ) > 0 ){
//        if(current_user_can( 'manage_options' ) || in_array( 'ml_doctor', (array) $user->roles ) ){
            $patient_id = filter_input(INPUT_POST, 'patient_id',FILTER_SANITIZE_NUMBER_INT);
            $doctor_id = $user->ID;
            if( in_array( 'ml_doctor', (array) $user->roles ) ){
                $is_doctor = true;
            }
        }
        
        //$user = wp_get_current_user();
        $r_access = [];
        $r_access [] ='administrator';
        $r_access [] ='ml_administrator';
        $r_access [] ='ml_manager';
        //$r_access [] ='ml_doctor';
        $r_access [] ='ml_procedurecab';
        if(count( array_intersect($r_access, (array) $user->roles ) ) > 0 ){
//        if(current_user_can( 'manage_options' ) ){
            $doctor_id = filter_input(INPUT_POST, 'doctor_id',FILTER_SANITIZE_NUMBER_INT);
        }
//        'dso_puid' => 'patient UId',
//        'dso_duid' => 'doctor UId',
        update_post_meta( $orderId, 'dso_puid', $patient_id );
        update_post_meta( $orderId, 'dso_duid', $doctor_id );
        update_post_meta( $orderId, 'dso_auid', $agent_id );
        update_post_meta( $orderId, 'dso_doctor_rate', $doctor_rate );
        update_post_meta( $orderId, 'dso_agent_rate', $agent_rate );
//                    add_log("$ orderId $orderId, 'dso_puid', $patient_id");
//                    add_log("$ orderId $orderId, 'dso_duid', $doctor_id");
//                    add_log($user);
        
        if($patient_id){
            $gender = filter_input(INPUT_POST, 'gender',FILTER_SANITIZE_NUMBER_INT);
//            $pregnancy = filter_input(INPUT_POST, 'pregnancy',FILTER_SANITIZE_NUMBER_INT);
            $pregnancy_week = filter_input(INPUT_POST, 'pregnancy_week',FILTER_SANITIZE_NUMBER_INT);
            if($gender){
                $alleg = new ProfileFields();
                $user = (object)['ID'=>$patient_id];
//                $alleg->initFieldsGeneral($user);
//                $alleg->initFieldsDoctor($user);
//                $alleg->initFieldsRequisites($user);
                $alleg->initFieldsPatient($user);
//                $alleg->save($user_id);
                if(array_key_exists($gender, $alleg->fsel_opts['patient']['gender']) ){
                    update_user_meta($patient_id, 'gender', $gender);
                    if($gender==2 // && array_key_exists($pregnancy, $alleg->fsel_opts['patient']['pregnancy'])
                            ){
//                        update_user_meta($patient_id, 'pregnancy', $pregnancy);
//                        if($pregnancy==1 && array_key_exists($pregnancy_week, $alleg->fsel_opts['patient']['pregnancy_week']) ){
                            update_user_meta($patient_id, 'pregnancy_week', $pregnancy_week);
//                        }else{
//                            update_user_meta($patient_id, 'pregnancy_week', 0);
//                        }
                    }else{
//                        update_user_meta($patient_id, 'pregnancy', 0);
                        update_user_meta($patient_id, 'pregnancy_week', 0);
                    }
                }
            }
            
            $adres = filter_input(INPUT_POST, 'residence_place',FILTER_SANITIZE_STRING);
            $passnum = filter_input(INPUT_POST, 'passnum',FILTER_SANITIZE_STRING);
            $id_citizen = filter_input(INPUT_POST, 'uidsitiz',FILTER_SANITIZE_STRING);
//            $passnum = filter_input(INPUT_POST, 'passnum',FILTER_SANITIZE_NUMBER_INT);
            $temperature = filter_input(INPUT_POST, 'temperature',FILTER_SANITIZE_STRING);
            $pcomment = filter_input(INPUT_POST, 'pat_comment',FILTER_SANITIZE_STRING);
            update_user_meta($patient_id, 'residence_place', $adres);
            update_user_meta($patient_id, 'passnum', $passnum);
            update_user_meta($patient_id, 'id_citizen', $id_citizen);
            update_user_meta($patient_id, 'temperature', $temperature);
            update_user_meta($patient_id, 'pat_comment', $pcomment);
            update_post_meta($orderId, 'dso_q_ref_comment', $pcomment );
            
            /** /
            $data = [];
            $data ['$patient_id'] = $patient_id;
            $data ['$orderId'] = $orderId;
            $data ['residence_place'] = $adres;
            $data ['passnum'] = $passnum;
            $data ['id_citizen'] = $id_citizen;
            $data ['temperature'] = $temperature;
            $data ['pat_comment'] = $pcomment;
            $data ['dso_q_ref_comment'] = $pcomment;
        add_log('ds_process_create_order_success');
        add_log($_POST);
        add_log($data);
        /**/
            
        }
        
        if(current_user_can( 'manage_options' ) || in_array( 'ml_doctor', (array) $user->roles ) ){
            $e = filter_input(INPUT_POST, 'eml-copy',FILTER_VALIDATE_EMAIL);
            if($e){
                
            }
        }
    }
    
    public function ds_chochout_form_fields($user){
        //get_template_part( 'template-parts/component/tpl-ml--dshop-checkout--form', 'fields' );
        get_template_part( 'template-parts/component/tpl.dshop-checkout-form', 'fields-ml' );
        // tpl.dshop-checkout-form-fields-ml
    }
    
    public function ds_chochout_form_fields_2($user){
//        get_template_part( 'template-parts/page/content', 'page-cart' );
    }
    
    /*
     * __prod_bnt_addtocart_name__
     */
    public function ds_dsproduct_btn_addtocart_name($pbsn){
        $pbsn = 'Добавить к заказу';
        $pbsn = 'Заказать';
        return $pbsn;
    }
    public function ds_dsproduct_btn_subm_name($pbsn){
        $pbsn = 'Купить сейчас';
        $pbsn = 'Заказать в один клик';
        return $pbsn;
    }
    
    public function ds_dsproduct_data_properties($properties, $prodId){
        global $wpdb;
        $dsp_fields= $wpdb->prefix . "dsp_fields";
        $q= "select * from `$dsp_fields` order by `weigh`";
        $fields = $wpdb->get_results($q,ARRAY_A);
        $opts = [];
        $opts_ = [];
        foreach($fields as $field){
            $name = $field['name'];
//            $object->meta_fields[$name] = $field['title'];
//            $object->meta_ftpl[$name] = $field['tpl'];
//            $object->meta_val[$name] = $field['def'];
//            $object->meta_vars[$name] = false;
//            $vars = unserialize($field['vars']);
//            if(count($vars))$object->meta_vars[$name] = $vars;
            
            $opts[] = ['t'=>$field['title'],'v'=>get_post_meta( $prodId, $name, true )];
        }
        foreach($opts as $v){
            $opts_[]= DShop::_div($v['t'],'col-5 mt-3');
            $opts_[]= DShop::_div($v['v'],'col-7 mt-3');
        }
        $properties .= DShop::_div(implode("\n",$opts_),'row');
        if(1) return $properties;
        $nm = 'dsp_';
        $fields=[];
        $opt_num = 0;
        $fields[$nm.'opt_'.$opt_num++]='Срок выполнения';
        $fields[$nm.'opt_'.$opt_num++]='Синонимы (rus)';
        $fields[$nm.'opt_'.$opt_num++]='Синонимы (eng)';
        $fields[$nm.'opt_'.$opt_num++]='Методы';
        $fields[$nm.'opt_'.$opt_num++]='Единицы измерения';
        $fields[$nm.'opt_'.$opt_num++]='Подготовка к исследованию';
        $fields[$nm.'opt_'.$opt_num++]='Тип биоматериала и  способы взятия';
        
        $opt_num = 0;
        $opts = [];
        $opts[] = ['t'=>$fields[$nm.'opt_'.$opt_num],'v'=>get_post_meta( $prodId, $nm.'opt_'.$opt_num++, true )];
        $opts[] = ['t'=>$fields[$nm.'opt_'.$opt_num],'v'=>get_post_meta( $prodId, $nm.'opt_'.$opt_num++, true )];
        $opts[] = ['t'=>$fields[$nm.'opt_'.$opt_num],'v'=>get_post_meta( $prodId, $nm.'opt_'.$opt_num++, true )];
        $opts[] = ['t'=>$fields[$nm.'opt_'.$opt_num],'v'=>get_post_meta( $prodId, $nm.'opt_'.$opt_num++, true )];
        $opts[] = ['t'=>$fields[$nm.'opt_'.$opt_num],'v'=>get_post_meta( $prodId, $nm.'opt_'.$opt_num++, true )];
        $opts[] = ['t'=>$fields[$nm.'opt_'.$opt_num],'v'=>get_post_meta( $prodId, $nm.'opt_'.$opt_num++, true )];
        $opts[] = ['t'=>$fields[$nm.'opt_'.$opt_num],'v'=>get_post_meta( $prodId, $nm.'opt_'.$opt_num++, true )];
        $opts_ = [];
        foreach($opts as $v){
            $opts_[]= DShop::_div($v['t'],'col-5 mt-3');
            $opts_[]= DShop::_div($v['v'],'col-7 mt-3');
        }
        $properties .= DShop::_div(implode("\n",$opts_),'row');
        return $properties;
    }
    
    public function _ds_dsproduct_data_properties($properties, $prodId){
        $nm = 'dsp_';
        $fields=[];
        $opt_num = 0;
        $fields[$nm.'opt_'.$opt_num++]='Срок выполнения';
        $fields[$nm.'opt_'.$opt_num++]='Синонимы (rus)';
        $fields[$nm.'opt_'.$opt_num++]='Синонимы (eng)';
        $fields[$nm.'opt_'.$opt_num++]='Методы';
        $fields[$nm.'opt_'.$opt_num++]='Единицы измерения';
        $fields[$nm.'opt_'.$opt_num++]='Подготовка к исследованию';
        $fields[$nm.'opt_'.$opt_num++]='Тип биоматериала и  способы взятия';
        
        $opt_num = 0;
        $opts = [];
        $opts[] = ['t'=>$fields[$nm.'opt_'.$opt_num],'v'=>get_post_meta( $prodId, $nm.'opt_'.$opt_num++, true )];
        $opts[] = ['t'=>$fields[$nm.'opt_'.$opt_num],'v'=>get_post_meta( $prodId, $nm.'opt_'.$opt_num++, true )];
        $opts[] = ['t'=>$fields[$nm.'opt_'.$opt_num],'v'=>get_post_meta( $prodId, $nm.'opt_'.$opt_num++, true )];
        $opts[] = ['t'=>$fields[$nm.'opt_'.$opt_num],'v'=>get_post_meta( $prodId, $nm.'opt_'.$opt_num++, true )];
        $opts[] = ['t'=>$fields[$nm.'opt_'.$opt_num],'v'=>get_post_meta( $prodId, $nm.'opt_'.$opt_num++, true )];
        $opts[] = ['t'=>$fields[$nm.'opt_'.$opt_num],'v'=>get_post_meta( $prodId, $nm.'opt_'.$opt_num++, true )];
        $opts[] = ['t'=>$fields[$nm.'opt_'.$opt_num],'v'=>get_post_meta( $prodId, $nm.'opt_'.$opt_num++, true )];
        $opts_ = [];
        foreach($opts as $v){
            $opts_[]= DShop::_div($v['t'],'col-5');
            $opts_[]= DShop::_div($v['v'],'col-7');
        }
        $properties .= DShop::_div(implode("\n",$opts_),'row');
        return $properties;
    }
    
    public function redirect_after_edit($post_ID, $post, $update){
        
        if( $post->post_type == 'dsproduct'  ) {
            $nm = 'dsp_';
            $pid = get_post_meta( $post_ID, $nm.'pid', true );
            $q =[];
    //        $q['redirect_to']=urlencode(get_the_permalink( get_option('ds_id_page_item') ).'?pid='.$prodId);
            $q['pid']=$pid;
            $adm_opts = DShop::_a('Перейти',get_the_permalink( get_option('ds_id_page_item') ),$q,['class'=>'btn btn-primary ml-1']);
            add_log('<p>Вернуться на страницу товара: '.$adm_opts.'</p>');
////            wp_redirect('http://localhost/wordpress/');
//            $redirect_to = filter_input(INPUT_POST, 'redirect_to', FILTER_DEFAULT);
//            add_log('Вернуться на страницу товара'.urldecode($redirect_to ));
//            add_log($redirect_to);
//            if($redirect_to){
//                wp_redirect( urldecode($redirect_to ) );
//                add_log('Вернуться на страницу товара'.urldecode($redirect_to ));
//            }
        }
    }
    
    public function ds_dsproduct_post_init_defaults($ftpl, $prodId){
        if($prodId){
        $productId = $prodId;
            $medLab = MedLab::_instance();
            $analyses = $medLab->analyses;
            $panels = $medLab->panels;
            $price = $medLab->price;
            
            $nm = 'dsp_';
            $title = false;
            $code = false;
            $cost = false;
            
//            $data['[__prod_title__]'] = '' ;
//            $data['[__prod_cost__]'] = 0 ;
            if(isset($analyses[$productId])){
                $title = $analyses[$productId]['Name'];
                $code = $analyses[$productId]['Code'];
            }
            if(isset($panels[$productId])){
                $title = $panels[$productId]['Name'];
                $code = $panels[$productId]['Code'];
            }
            if(isset($price[$productId])){
                $cost = $price[$productId]['Price'];
            }
            if($title!==false){
                $ftpl[$nm.'title'] = $title ;
            }
            if($cost!==false){
                $ftpl[$nm.'code']= $code ;
            }
            if($cost!==false){
                $ftpl[$nm.'cost']= $cost ;
            }
        }
        return $ftpl;
    }
    public function ds_dsproduct_data_placeholders($data, $prodId){
        $productId = $prodId;
        if(!$data['product-exists']){

            $medLab = MedLab::_instance();

            $groups = $medLab->groups;
            $analyses = $medLab->analyses;
            $panels = $medLab->panels;
            $tests = $medLab->tests;
            $biomaterials = $medLab->biomaterials;
            $drugs = $medLab->drugs;
            $microorganisms = $medLab->microorganisms;
            $containers = $medLab->containers;
            $price = $medLab->price;

            $title = false;
            $cost = false;
            
            $data['[__prod_title__]'] = '' ;
            $data['[__prod_cost__]'] = 0 ;
            
//if(isset($analyses[$productId]))
//foreach ($analyses as $aId => $a) 
//{
////    $a = $analyses[$productId];
//    // init
//    if(!isset($groups[$a['AnalysisGroupId']]['analyses']))
//        $groups[$a['AnalysisGroupId']]['analyses']=[];
////    $groups[$res['AnalysisGroupId']]['analyses'][$res['Id']] = $res['Name'];
//    
//    // set price
//    $p = '--';
//    if(isset($price[$a['Id']]['Price']))$p = $price[$a['Id']]['Price'];
//    $groups[$a['AnalysisGroupId']]['analyses'][$a['Id']]
//            = ['name'=> $a['Name'],'price'=>$p];
//}
//
////if(isset($panels[$productId]))
//foreach ($panels as $pId => $a)
//{
////    $a = $panels[$productId];
//    // init
//    if(!isset($groups[$a['AnalysisGroupId']]['panels']))
//        $groups[$a['AnalysisGroupId']]['panels']=[];
////    $groups[$res['AnalysisGroupId']]['analyses'][$res['Id']] = $res['Name'];
//    
//    // set price
//    $p = '--';
//    if(isset($price[$a['Id']]['Price']))$p = $price[$a['Id']]['Price'];
//    $groups[$a['AnalysisGroupId']]['panels'][$a['Id']]
//            = ['name'=> $a['Name'],'price'=>$p];
//}
            if(isset($analyses[$productId])){
                $title = $analyses[$productId]['Name'];
                $data['[__prod_code__]'] = $analyses[$productId]['Code'];
            }
            if(isset($panels[$productId])){
                $title = $panels[$productId]['Name'];
                $data['[__prod_code__]'] = $panels[$productId]['Code'];
            }
            if(isset($price[$productId])){
                $cost = $price[$productId]['Price'];
            }
            if($title!==false){
                $data['[__prod_title__]'] = $title ;
            }
            if($cost!==false){
                $data['[__prod_cost__]'] = $cost ;
            }
            $data['[__prod_desc__]'] = '';
            $data['[__prod_properties__]'] = '';
        }
        return $data;
    }
    public function ds_dsproduct_data_placeholders_second($data, $prodId){
//        $data['product-exists']=false;
        return $data;
    }
    public function ds_dsproduct__tpl_name($product_tpl_name, $prodId){
        // tpl.page-shop-product-ml-v2
        $product_tpl_name  = 'shop-product-ml-v2';
        return $product_tpl_name;
    }
    
    public function ds_styling_tpl_name($tpl_name){
        $_tpl_name = get_option('ml_ds_styling') ;
        if(strlen($_tpl_name)>0)$tpl_name = $_tpl_name;
        return $tpl_name;
    }
    
    public function add_ds_options_params($_this, $page, $id_block){
        

        $option_name = 'ml_ds_styling';
        $option_title = 'Шаблон стилизации полей [MedLab]';
        $option_field = 'select';
        $items = [];
//        $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
        $arg = array(
            'items'=>[
                'default'=>'default dshop bootstrup',
                'ml-v2'=>'MedLab v2 bootstrup',
            ],
        );
        $_this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );
    }
    
    /**
     * additional user data fields
     * 
     * @param object $object
     * @param object $item $dsorder
     * @param type $meta
     */
    public function meta_box_user__fields_out($object,$item,$meta){
        return ;
        $dsorder = $item;
        
        $dso_userId_ =  get_post_meta( $dsorder->ID, 'dso_userId', true );
        $dso_user_name_ =  get_post_meta( $dsorder->ID, 'dso_user_name', true );
        $dso_user_lastname_ =  get_post_meta( $dsorder->ID, 'dso_user_lastname', true );
        $dso_user_sname_ =  get_post_meta( $dsorder->ID, 'dso_user_sname', true );
        $dso_user_phone_ =  get_post_meta( $dsorder->ID, 'dso_user_phone', true );
        $dso_user_email_ =  get_post_meta( $dsorder->ID, 'dso_user_email', true );
        $dso_user_addres_ =  get_post_meta( $dsorder->ID, 'dso_user_addres', true );
        $dso_agriments_ =  get_post_meta( $dsorder->ID, 'dso_agriments', true );
                ?>
            
            <tr><td style="" colspan="2"><b>Заказчик:</b></td></tr>
            
            <tr><td style="" colspan="">Имя</td>
                <td colspan=""><input style="width: 100%" type="text"
                name="news_source" value="<?=$dso_user_name_?>"></td></tr>
            
            <tr><td style="" colspan="">Фамилия</td>
                <td colspan=""><input style="width: 100%" type="text"
                name="news_source" value="<?=$dso_user_lastname_?>"></td></tr>
            
            <tr><td style="" colspan="">Отчество</td>
                <td colspan=""><input style="width: 100%" type="text"
                name="news_source" value="<?=$dso_user_sname_?>"></td></tr>
            
            <tr><td style="" colspan="">Телефон</td>
                <td colspan=""><?=$dso_user_phone_?></td></tr>
            
            <tr><td style="" colspan="">EMail</td>
                <td colspan=""><input style="width: 100%" type="text"
                name="news_source" value="<?=$dso_user_email_?>"></td></tr>
            
            <tr><td style="" colspan="">Адрес</td>
                <td colspan=""><input style="width: 100%" type="text"
                name="news_source" value="<?=$dso_user_addres_?>"></td></tr>
            
            <tr><td style="" colspan="">Соглашение о персональных данных</td>
                <td colspan=""><?=$dso_agriments_?></td></tr>
            
        <?php
    }
    public function get_patients($ouner=false,$agent=false){
        return $this->get_users('ml_patient',$ouner,$agent);
    }
    public function get_doctors($ouner=false,$agent=false){
        return $this->get_users('ml_doctor',$ouner,$agent);
    }
    public function get_agents($ouner=false,$agent=false){
        return $this->get_users('ml_agent',$ouner,$agent);
    }
    public function get_users($role='ml_patient',$ouner=false,$agent=false){
        
        // параметры по умолчанию
        //$posts = get_posts( array(
        //	'numberposts' => $count,
        //	'offset'    => $offset,
        $args = array(
        //	'blog_id'      => $GLOBALS['blog_id'],
        //	'role'         => '',
        //	'role__in'     => array('contributor','author','editor','administrator','ml_patient','ml_doctor'),
//            'role__in'     => array('ml_patient'),
        //	'role__not_in' => array('subscriber'),
        //	'meta_key'     => 'joined_doctor',
        //	'meta_value'   => $duId,
        //	'meta_compare' => '',
            'meta_query'   => [

            ],
        //	'include'      => array(),
        //	'exclude'      => array(),
        //	'orderby'      => 'login',
        //	'order'        => 'ASC',
            'orderby'      => 'ID',
            'order'        => 'DESC',
//            'offset'       => $offset,
        //	'offset'       => '',
        //	'search'       => '',
        //	'search_columns' => array(),
//            'number'       => $count,
        //	'paged'        => 1,
        //	'count_total'  => false,
        //	'fields'       => 'all',
        //	'who'          => '',
        //	'has_published_posts' => null,
        //	'date_query'   => array() // смотрите WP_Date_Query
        );
        if($role){
            $args['role__in'] = [$role];
        }
        if($ouner){
            $duId=get_current_user_id();//'0';
            if($ouner !== true)
                $duId=$ouner;
            $args['meta_query'][] = 
                [
                    'key' => 'joined_doctor',
                    'value' => $duId,
                    'compare' => '='
            //        'compare' => 'LIKE'
                ];
        }
        if($agent){
            $auId=get_current_user_id();//'0';
            if($agent !== true)
                $auId=$agent;
            $args['meta_query'][] = 
                [
                    'key' => 'agent_id',
                    'value' => $auId,
                    'compare' => '='
            //        'compare' => 'LIKE'
                ];
        }
//        if($user_id)$args['include']=[$user_id];
        //add_log($args);
        $users = get_users( $args );
        //$users = get_users( );
        $out = [];
        if(count($users)>0){
            foreach ($users as $key => $user) {
                $fio = get_user_meta($user->ID,'last_name',1);
                $fio .= ' '.get_user_meta($user->ID,'first_name',1);
                $fio .= ' '.get_user_meta($user->ID,'second_name',1);
                $out[$user->ID] = '['.$user->ID.'] '.$fio;
            }
        }
        return $out;
    }
    /**
     * добавление блока метатегов
     *
     * @param object $object
     * @param string $object_name
     */
    public function add_metabox($object,$object_name){
/*
 * add_meta_box
 * 
movie_review_meta_box — необходимый HTML атрибут id.
Movie Review Details — текст, видимый в верхней части мета-блока.
display_movie_review_meta_box — обратный вызов, который отображает содержимое мета-блока.
movie_reviews — это имя пользовательского типа записей, где будет отображаться мета-блок.
normal — определяет часть страницы, где должен быть отображен блок редактирования.
high — определяет приоритет в контексте, в котором будут отображаться блоки.
 */
        foreach($this->meta_name as $name=>$title){
            if(isset($this->meta_weith[$name]) ) continue;
//        $this->n(__METHOD__);
            add_meta_box( 'dshop_meta_box_'.$name,
                $title,
                [$this,'display_meta_box_'.$name],
                $object->name, $this->meta_pos[$name], 'high'
            );
        }
        
    }
    public function add_metabox_onpos($object,$object_name,$weith){
/*
 * add_meta_box
 * 
movie_review_meta_box — необходимый HTML атрибут id.
Movie Review Details — текст, видимый в верхней части мета-блока.
display_movie_review_meta_box — обратный вызов, который отображает содержимое мета-блока.
movie_reviews — это имя пользовательского типа записей, где будет отображаться мета-блок.
normal — определяет часть страницы, где должен быть отображен блок редактирования.
high — определяет приоритет в контексте, в котором будут отображаться блоки.
 */
        foreach($this->meta_name as $name=>$title){
            if(isset($this->meta_weith[$name]) && $this->meta_weith[$name] == $weith){
//        $this->n(__METHOD__);
                add_meta_box( 'dshop_meta_box_'.$name,
                    $title,
                    [$this,'display_meta_box_'.$name],
                    $object->name, $this->meta_pos[$name], 'high'
                );
            }
        }
        
    }
    
    public function display_meta_box_analise_desc( $item, $meta, $output = true ) {
        $dsorder = $item;
        $orderId = $dsorder->ID;
        $containers = [];
        
                    $atts = [];
                    
                    $atts['dso_id'] = $orderId;
                    $atts['puid'] = get_post_meta( $orderId, 'dso_puid', true );
                    $atts['duid'] = get_post_meta( $orderId, 'dso_duid', true );
                    $ref = [];
//                    $ref['comment'] = $_comment;
                    $atts['refferral'] = $ref;
                    
                    $orders = [];
                    // параметры по умолчанию
                     $args = [

                        'numberposts' => 1000,
                        'offset'    => 0,
                        'category'    => 0,
                        'orderby'     => 'date',
                        'order'       => 'DESC',
                        'include'     => array(),
                        'exclude'     => array(),
                        'meta_key'    => 'dsoi_orderId',
                        'meta_value'  => $orderId,
                        'post_type'   => 'dsoitem',
                        'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
                     ];
                    $query = new WP_Query( $args );
                    $count =  $query->found_posts;
                    
                    $an_count = $count;
                    
                    if(!$count){
                    }else{
        
                        $posts = get_posts( $args );
                        $total = 0;
                        $dso_items_count=0;
                        $dso_count=0;
                        
                        global $pids;
                        $analises = [];
                        $pids = [];
                        foreach( $posts as $num=>$item ){
                            $dsoi_prodId_ =  get_post_meta( $item->ID, 'dsoi_prodId', true );
                            $pids[] = $dsoi_prodId_;
                        }
                        $analises = $this->dmb_build_analises($pids);
    //                    echo ('<pre>'.htmlspecialchars(print_r($analises,1)).'</pre>');
                        if($output) $this->dmb_an_desc_out($analises);
                        $containers = $this->dmb_an_build_containers($analises);
                    }
                    return $containers ;
    }
    public function dmb_build_analises($pids){
        $medLab = MedLab::_instance();

        $groups = $medLab->groups;
        $analyses = $medLab->analyses;
        $panels = $medLab->panels;
        $tests = $medLab->tests;
        $biomaterials = $medLab->biomaterials;
        $drugs = $medLab->drugs;
        $microorganisms = $medLab->microorganisms;
        $containers = $medLab->containers;
        $price = $medLab->price;
        $currency_short = get_option('currency_short','zl');
        $analises = [];
                        global $pids_log;$pids_log=[];
        foreach( $pids as $num=>$pid ){
            $analise = [];
            $order = [];

            $code = '';
            $bcode = '';
            if(isset($analyses[$pid])){
                $analises [] = $this->dmb_build_analise($pid, $analyses, $biomaterials, $containers);
            }
            if(isset($panels[$pid])){
                $code=$panels[$pid]['Code'];
//                                add_log('<pre>'.htmlspecialchars(print_r($panels[$pid],1)).'</pre>');
                $pa_items = $panels[$pid]['item']->PanelAnalyses->Item;
//                                add_log('<pre>'.htmlspecialchars(print_r($pa_items,1)).'</pre>');

                if(is_array($pa_items)){
                }else{
//                                    $pa_items = [ $pa_items ];
                }
                $analise['Name'] = $panels[$pid]['Name'];
                $analise['Code'] = $panels[$pid]['Code'];
                $analise['ans'] = [];
                $an_bmss = [];
                foreach($pa_items as $an_){
                    $pid = ''.$an_['AnalysisId'];
                    $an_bmss [] = $this->dmb_build_analise($pid, $analyses, $biomaterials, $containers);
                }
                $analise['ans'] = $an_bmss;
                $analises [] = $analise;
            }

        }
        return $analises;
    }
    /*
     * 
                                $analise['Bms'] = $this->dmb_build_analise($pid, $analise, $biomaterials, $containers);
     */
    public function dmb_build_analise($pid, $analyses, $biomaterials, $containers){
                        global $pids_log;
//                                add_log('$pid<pre>'.htmlspecialchars(print_r($pid,1)).'</pre>');
//                                add_log('<pre>'.htmlspecialchars(print_r($analyses,1)).'</pre>');
//        $analyses = $an;
//        $biomaterials = $bm;
//        $containers = $cont;
                            $analise = [];
                                $analise['Name'] = $analyses[$pid]['Name'];
                                $analise['Code'] = $analyses[$pid]['Code'];
                                $analise['Bms'] = [];
                                $an_bms = [];
                                
                                $an = $analyses[$pid];
                                $code=$analyses[$pid]['Code'];
                                $an_bm_ = $an['item']->AnalysisBiomaterials->Item;
//                                $BiomaterialId = ''.$an['item']->AnalysisBiomaterials->Item['BiomaterialId'];
//                                $ContainerTypeId = ''.$an['item']->AnalysisBiomaterials->Item['ContainerTypeId'];
                                if(is_array($an_bm_)){
//                                    $an_bm = $an_bm[0];
                                }else{
                                    
                                    $an_bm_ = [ $an_bm_ ];
                                }
//                                add_log('count an bm : '.count($an_bm_));
                                
                                foreach($an_bm_ as $an_bm){
                                    $bm = [];

                                    $BiomaterialId = ''.$an_bm['BiomaterialId'];
                                    $BiomaterialCode = ''.$an_bm['BiomaterialCode'];
                                    
                                    $_bm = $biomaterials[$BiomaterialId];
//                                    $ContainerTypeId = ''.$_bm['ContainerTypeId'];
                                    $ContainerTypeId = ''.$an_bm['ContainerTypeId'];
                                    
                                    $_cont =  ['Name'=>'unknown','Code'=>'unknown','ImageIndex'=>'unknown',];
                                    if(isset($containers[$ContainerTypeId]))$_cont = $containers[$ContainerTypeId];
                                    $bm['name'] = $_bm['Name'];
                                    $bm['code'] = $BiomaterialCode;
                                    $bm['container'] = $_cont['Name'];
                                    $bm['contCode'] = $_cont['Code'];
                                    $bm['ImageIndex'] = $_cont['ImageIndex'];
//                                add_log('$_cont<pre>'.htmlspecialchars(print_r($_cont,1)).'</pre>');
                                    
                                    $an_bms[]=$bm;
                                }
                                
                                $an_bm_name = $biomaterials[$BiomaterialId]['Name'];
                                
                                $bcode = $BiomaterialCode;
                                
//                                add_log('<pre>'.htmlspecialchars(print_r($BiomaterialId,1)).'</pre>');
//                                add_log('<pre>'.htmlspecialchars(print_r($an_bm_name,1)).'</pre>');
//                                add_log('$biomaterials<pre>'.htmlspecialchars(print_r($biomaterials[$BiomaterialId],1)).'</pre>');
//                                add_log('<pre>'.htmlspecialchars(print_r($containers,1)).'</pre>');
//                                add_log('$analyses<pre>'.htmlspecialchars(print_r($analyses[$pid],1)).'</pre>');
                                $analise['Bms'] = $an_bms;
                                $pids_log[]=$analise;
//                                add_log('<pre>'.htmlspecialchars(print_r($analise,1)).'</pre>');
        return $analise;
    }
    public function dmb_an_build_containers($analises=[]){
        $containers=[];
        foreach ($analises as $key => $analise) {
//            echo '<tr>';
//            echo '<td colspan = "2">';
//            echo $analise['Name'];
//            echo '</td>';
//            echo '<td align="right">';
//            echo $analise['Code'];
//            echo '</td>';
//            echo '</tr>';
//
//            echo '<tr>';
//            echo '<td>&nbsp;</td>';
//            echo '<td colspan = "2">';

            if(isset($analise['ans'])&&count($analise['ans'])>0){
                $containers += $this->dmb_an_build_containers($analise['ans']);
//                        foreach ($analise['ans'] as $bm) {
//                            $this->dmb_an_desc_out($bm);
//                        }
            }else{
                foreach ($analise['Bms'] as $bm) {
                    if(0)$this->dmb_an_desc_out_bm($bm);
//                    echo $bm['code'];
//                    echo $bm['name'];
//                    echo $bm['container'];
                    $containers[$bm['code'].'-'.$bm['contCode']]['b_name'] = $bm['name'];
                    $containers[$bm['code'].'-'.$bm['contCode']]['c_name'] = $bm['container'];
                }
            }

//            echo '</td>';
//            echo '</tr>';
        }
        return $containers;
    }
    public function dmb_an_desc_out($analises=[]){
        echo '<table>';
            echo '<tr>';
//            if(isset($analises['Bmss'])&&count($analises['Bmss'])>0){
//                echo '<th colspan = "2">Панель</th>';
//                echo '<th>Код панели</th>';
//            }
//            else{
                echo '<th colspan = "2">Анализ</th>';
                echo '<th>Код анализа</th>';
//            }
            
            echo '</tr>';
            
//            if(isset($analises['Bmss'])&&count($analises['Bmss'])>0){
//                echo '<tr>';
//                echo '<td colspan = "2">';
//                echo $analises['Name'];
//                echo '</td>';
//                echo '<td align="right">';
//                echo $analises['Code'];
//                echo '</td>';
//                echo '</tr>';
//                    
//                echo '<tr>';
//                echo '<td colspan = "3">';
//                $this->dmb_an_desc_out($analises['Bmss']);
//                echo '</td>';
//                echo '</tr>';
//            }else{
                foreach ($analises as $key => $analise) {
                    echo '<tr>';
                    echo '<td colspan = "2">';
                    echo $analise['Name'];
                    echo '</td>';
                    echo '<td align="right">';
                    echo $analise['Code'];
                    echo '</td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td>&nbsp;</td>';
                    echo '<td colspan = "2">';
                    
                    if(isset($analise['ans'])&&count($analise['ans'])>0){
                        $this->dmb_an_desc_out($analise['ans']);
//                        foreach ($analise['ans'] as $bm) {
//                            $this->dmb_an_desc_out($bm);
//                        }
                    }else{
                        foreach ($analise['Bms'] as $bm) {
                            $this->dmb_an_desc_out_bm($bm);
                        }
                    }
                    
                    echo '</td>';
                    echo '</tr>';
                }
//            }
        echo '</table>';
    }
        
    public function dmb_an_desc_out_bm($bm=[]){
        echo '<table>';
        echo '<tr>';
        echo '<th>Биоматериал </th>';
            echo '<td>';
            echo $bm['name'];
            echo '</td>';
        echo '</tr>';
        
            echo '<tr>';
        echo '<th>Код Биоматериала </th>';
            echo '<td>';
            echo $bm['code'];
            echo '</td>';
        echo '</tr>';
        
            echo '<tr>';
        echo '<th>Контейнер </th>';
            echo '<td>';
            echo $bm['container'];
            echo '</td>';
        echo '</tr>';
        
            echo '<tr>';
        echo '<th>Код Контейнера </th>';
            echo '<td>';
            echo $bm['contCode'];
            echo '</td>';
        echo '</tr>';
        
            echo '<tr>';
        echo '<th>Контейнер ImageIndex </th>';
            echo '<td>';
            echo $bm['ImageIndex'];
            echo '</td>';
            echo '</tr>';
        echo '</table>';
    }
    public function display_meta_box_analise_query( $item, $meta ) {
        $dsorder = $item;
        
        $dso_userId_ =  get_post_meta( $dsorder->ID, 'dso_userId', true );
        $dso_user_name_ =  get_post_meta( $dsorder->ID, 'dso_user_name', true );
        $dso_user_lastname_ =  get_post_meta( $dsorder->ID, 'dso_user_lastname', true );
        $dso_user_sname_ =  get_post_meta( $dsorder->ID, 'dso_user_sname', true );
        $dso_user_phone_ =  get_post_meta( $dsorder->ID, 'dso_user_phone', true );
        $dso_user_email_ =  get_post_meta( $dsorder->ID, 'dso_user_email', true );
        $dso_user_addres_ =  get_post_meta( $dsorder->ID, 'dso_user_addres', true );
        $dso_agriments_ =  get_post_meta( $dsorder->ID, 'dso_agriments', true );
            
        $this->n('$var_pu');
        $var_pu=[];
        $var_pu['items'] = $this->get_patients();
        $var_pu['post_id'] = $dsorder->ID;
        $var_pu['option_name'] = 'dso_puid';
        $var_pu['id'] = 'field_dso_puid';
        $f_patient = $this->dshf_select($var_pu);
//        $this->_notice($var_pu);
        
        $var_pu=[];
        $var_pu['items'] = [];
        $var_pu['items']['hide'] = 'Не доступно доктору';
        $var_pu['items']['sent'] = 'Отправлено доктору';
        $var_pu['post_id'] = $dsorder->ID;
        $var_pu['option_name'] = 'dso_send_to_doctor';
        $var_pu['id'] = 'field_dso_send_to_doctor';
        $f_send_to_doctor = $this->dshf_select($var_pu);
        
            
        $var_pu=[];
        $var_pu['items'] = $this->get_doctors();
        $var_pu['items'][0] = 'Не указан';
        $var_pu['post_id'] = $dsorder->ID;
        $var_pu['option_name'] = 'dso_duid';
        $var_pu['id'] = 'field_dso_duid';
        $f_doctor = $this->dshf_select($var_pu);
//        $this->_notice($var_pu);
            
        $var_pu=[];
        $var_pu['items'] = $this->get_agents();
        $var_pu['items'][0] = 'Не указан';
        $var_pu['post_id'] = $dsorder->ID;
        $var_pu['option_name'] = 'dso_auid';
        $var_pu['id'] = 'field_dso_auid';
        $f_agent = $this->dshf_select($var_pu);
//        $this->_notice($var_pu);
            
        $var_pu=[];
        $var_pu['post_id'] = $dsorder->ID;
        $var_pu['option_name'] = 'dso_query_id';
        $var_pu['id'] = 'field_dso_query_id';
        $f_qid = $this->dshf_text($var_pu);
            
        $var_pu=[];
        $var_pu['post_id'] = $dsorder->ID;
        $var_pu['option_name'] = 'dso_query_nr';
        $var_pu['id'] = 'field_dso_query_nr';
        $f_nr = $this->dshf_text($var_pu);
            
        $var_pu=[];
        $var_pu['items'] = [];
        $var_pu['items']['send_wait'] = 'ожидает отправки';
        $var_pu['items']['sent'] = 'запрос отравлен';
        $var_pu['post_id'] = $dsorder->ID;
        $var_pu['option_name'] = 'dso_query_status';
        $var_pu['id'] = 'field_dso_query_id';
        $f_qstatus = $this->dshf_select($var_pu);
            
        $var_pu=[];
        $var_pu['items'] = [];
        $var_pu['items']['wait'] = 'ожидает получения';
        $var_pu['items']['got'] = 'ответ принят';
        $var_pu['post_id'] = $dsorder->ID;
        $var_pu['option_name'] = 'dso_answer_status';
        $var_pu['id'] = 'field_dso_query_id';
        $f_astatus = $this->dshf_select($var_pu);
            
        $var_pu=[];
        $var_pu['post_id'] = $dsorder->ID;
        $var_pu['option_name'] = 'dso_path_answer_xml';
        $var_pu['id'] = 'field_dso_query_id';
        $f_axml = $this->dshf_text($var_pu);
            
        $var_pu=[];
        $var_pu['post_id'] = $dsorder->ID;
        $var_pu['option_name'] = 'dso_path_answer_file';
        $var_pu['id'] = 'field_dso_query_id';
        $f_afile = $this->dshf_text($var_pu);
            
        $var_pu=[];
        $var_pu['post_id'] = $dsorder->ID;
        $var_pu['option_name'] = 'dso_q_ref_comment';
        $var_pu['id'] = 'field_dso_q_ref_comment';
        $var_pu['cols'] = 50;
        $f_comment = $this->dshf_textarea($var_pu);
        
        $fields = [];
        $fields['MisId'] = false;
        $fields['Nr'] = false;
        $fields['LisId'] = false;
        $fields['Date'] = false;
        $fields['SamplingDate'] = false;
        $fields['DeliveryDate'] = false;
        $fields['HospitalCode'] = false;
        $fields['DepartmentName'] = false;
        $fields['DepartmentCode'] = false;
        $fields['DoctorName'] = false;
        $fields['DoctorSpecialization'] = false;
        $fields['DoctorCode'] = false;
        $fields['Cito'] = false;
        $fields['DiagnosisName'] = false;
        $fields['DiagnosisCode'] = false;
        $fields['Comment'] = false;
        $fields['PregnancyWeek'] = false;
        $fields['CyclePeriod'] = false;
        $fields['LastMenstruation'] = false;
        $fields['DiuresisMl'] = false;
        $fields['WeightKg'] = false;
        $fields['HeightCm'] = false;
                ?>
        <table>
            
            <tr><td style="" colspan="2"><b>Заказчик:</b></td></tr>
            
            <tr><td style="" colspan="">User Id</td>
                <td colspan=""><?=$dso_userId_?></td></tr>
            
            <tr><td style="" colspan="">Отправить ответ врачу</td>
                <td colspan=""><?=$f_send_to_doctor?></td></tr>
            
            <tr><td style="" colspan="">Patient User Id</td>
                <td colspan=""><?=$f_patient?></td></tr>
            
            <tr><td style="" colspan="">Doctor User Id</td>
                <td colspan=""><?=$f_doctor?></td></tr>
            
            <tr><td style="" colspan="">Agent User Id</td>
                <td colspan=""><?=$f_agent?></td></tr>
            
            <tr><td style="" colspan="">Query Lis Id</td>
                <td colspan=""><?=$f_qid?></td></tr>
            
            <tr><td style="" colspan="">Nr</td>
                <td colspan=""><?=$f_nr?></td></tr>
            
            <tr><td style="" colspan="">Query status</td>
                <td colspan=""><?=$f_qstatus?></td></tr>
            
            <tr><td style="" colspan="">Answer status</td>
                <td colspan=""><?=$f_astatus?></td></tr>
            
            <tr><td style="" colspan="">Answer xml</td>
                <td colspan=""><?=$f_axml?></td></tr>
            
            <tr><td style="" colspan="">Answer file</td>
                <td colspan=""><?=$f_afile?></td></tr>
            
            <tr><td style="" colspan="">Comment</td>
                <td colspan=""><?=$f_comment?></td></tr>
            
            <?php if(0){ ?>
            <tr><td style="" colspan="">===</td>
                <td colspan="">===</td></tr>
            
            <tr><td style="" colspan="">Имя</td>
                <td colspan=""><input style="width: 100%" type="text"
                name="news_source" value="<?=$dso_user_name_?>"></td></tr>
            
            <tr><td style="" colspan="">Фамилия</td>
                <td colspan=""><input style="width: 100%" type="text"
                name="news_source" value="<?=$dso_user_lastname_?>"></td></tr>
            
            <tr><td style="" colspan="">Отчество</td>
                <td colspan=""><input style="width: 100%" type="text"
                name="news_source" value="<?=$dso_user_sname_?>"></td></tr>
            
            <tr><td style="" colspan="">Телефон</td>
                <td colspan=""><?=$dso_user_phone_?></td></tr>
            
            <tr><td style="" colspan="">EMail</td>
                <td colspan=""><input style="width: 100%" type="text"
                name="news_source" value="<?=$dso_user_email_?>"></td></tr>
            
            <tr><td style="" colspan="">Адрес</td>
                <td colspan=""><input style="width: 100%" type="text"
                name="news_source" value="<?=$dso_user_addres_?>"></td></tr>
            
            <tr><td style="" colspan="">Соглашение о персональных данных</td>
                <td colspan=""><?=$dso_agriments_?></td></tr>
            <?php } ?>
        </table>
            
        <?php
    }
    /**
     * changing list of status
     * 
     * @param type $states
     * @param type $item
     * @param type $object
     */
    public function change_status_list($states, $item, $object){
        $states =[
            'created'=>'Обрабатывается',
            'checked'=>'Оформлен',
            'payd'=>'Оплачен',
            'paychecked'=>'Оплата проверена',
            
            'Отобразить:' => [
                'show_query_result'=>'результат запроса',
                'show_query_sent'=>'запрос заказа',
                'show_get_answer'=>'запрос ответа',
                'show_get_dict_version'=>'запрос dict version',
                ],
            
            'Отправить:' => [
                'get_dict_version'=>'Оправить запрос dict version',
                'send_query'=>'Оправить запрос',
                'send_query_failure'=>'Сбой отправки запроса',
                'change_query'=>'Изменить запрос',
                'query_sent'=>'Запрос отправлен',
                ],
            
            'Получить:' => [
                'get_answer'=>'Получить состояние',
                'get_answer'=>'Получить ответ',
                'get_answer_failure'=>'Сбой получения ответа',
                'answer_got'=>'Ответ получен',
                'get_answer_file'=>'Получить файл ответа',
                'answer_file_got'=>'Файл ответа получен',
                ],
            
            'sent'=>'Товар отправлен',
            'deliverid'=>'Товар доставлен',
            'pending'=>'Заказ отклонён',
        ];
//        $state_ =  get_post_meta( $item->ID, 'dso_status', true );
        return $states;
    }
    public  function change_status_list__order_list_class($state_class, $status, $item, $object){
                switch ($status) {
                    case 'created': $state_class='bg-primary'; break;//alert-
                    case 'checked': $state_class='bg-success'; break;
                    case 'payd': $state_class='bg-success'; break;
                    case 'paychecked': $state_class='bg-success'; break;
                    case 'sent': $state_class='bg-success'; break;
                    case 'deliverid': $state_class='bg-success'; break;
                    case 'pending': $state_class='bg-danger'; break;
                    
                    case 'query_sent': $state_class='bg-success'; break;
                    default:
//                        $state_class='bg-danger';break;
                }
        return $state_class;
    }
    
    public $meta_fields = [ // поля объекта, атрибуты, опции
        'dso_puid' => 'patient UId',
        'dso_duid' => 'doctor UId',
        'dso_auid' => 'agent UId',
        'dso_q_ref_comment' => 'Referral Comment',
        
        'dso_send_to_doctor' => 'Send answer to doctor',
        
//        'dso_ID' => 'ID заказа',
//        'dso_status'=>'Статус заказа',
//        'dso_userId'=>'Заказчик (id)',
//        'dso_user_name'=>'Имя',
//        'dso_user_lastname'=>'Фамилия',
//        'dso_user_sname'=>'Отчество',
//        'dso_user_phone'=>'Телефон',
//        'dso_user_email'=>'Почта',
//        'dso_user_addres'=>'Адресс назначения',
//        'dso_agriments'=>'Согласие на обработку персональных данных',
//        
////        'dso_prodId'=>'Id продукта',
////        'dso_prodUrl'=>'Источник продукта',
////        'dso_prodCategory'=>'Категория',
////        'dso_prodName'=>'Название',
////        'dso_count'=>'Количество',
////        'dso_item_cost'=>'Стоимость единицы',
//        'dso_items_count'=>'Количество единиц',
//        
////        'dso_delivery_poland_cost'=>'Стоимость доставки по Польше',
////        'dso_deliveryPolad'=>'Служба доставки по польше',
////        'dso_delivery_cost'=>'Стоимость доставки до склада',
////        'dso_deliveryId'=>'Id службы доставки',
////        'dso_deliveryName'=>'Транспортная служба',
////        'dso_markup'=>'Наценка за доставку до склада %',
//        
//        'dso_count'=>'Количество продуктов',
//        'dso_cost'=>'Всего',
//        'dso_payment_message'=>'Сообщение о проверке оплаты',
//        
////        'news_type'=>'Тип новостей',
////        'news_source'=>'Источник'
        ];
    /**
     * 
     * @param object $object
     * @param int $orderId
     * @param object $item dsorder
     */
    public function save_post($object, $orderId, $item){
    //        dso_status
        $_status = filter_input(INPUT_POST, 'dso_status', FILTER_DEFAULT); // FILTER_SANITIZE_NUMBER_INT
        $_comment = filter_input(INPUT_POST, 'dso_q_ref_comment', FILTER_DEFAULT); // FILTER_SANITIZE_NUMBER_INT
    //    if($status===false || $offset===null || $offset==='')$offset=0;
        $states =[
            'created'=>'Обрабатывается',
            'checked'=>'Оформлен',
            'payd'=>'Оплачен',
            'paychecked'=>'Оплата проверена',
            
            'Отобразить:' => [
                'show_query_sent'=>'запрос заказа',
                'show_get_answer'=>'запрос ответа',
                'show_get_dict_version'=>'запрос dict version',
                ],
            
            'Отправить:' => [
                'get_dict_version'=>'Оправить запрос dict version',
                'send_query'=>'Оправить запрос',
                'send_query_failure'=>'Сбой отправки запроса',
                'change_query'=>'Изменить запрос',
                'query_sent'=>'Запрос отправлен',
                ],
            
            'Получить:' => [
                'get_answer'=>'Получить ответ',
                'get_answer_failure'=>'Сбой получения ответа',
                'answer_got'=>'Ответ получен',
                'get_answer_file'=>'Получить файл ответа',
                'answer_file_got'=>'Файл ответа получен',
                ],
            
            'sent'=>'Товар отправлен',
            'deliverid'=>'Товар доставлен',
            'pending'=>'Заказ отклонён',
        ];
        $state_ =  get_post_meta( $orderId, 'dso_status', true );
        if($_status && strlen($_status)>0){
            switch($_status){
                case 'show_query_result':
                    $answer =  get_post_meta( $orderId, 'dso_q__answer', true );
//                    $xml = simplexml_load_string($answer);
//                    $qrootAtt = MedLab::_buildAttrs($xml);
//                    add_log((print_r($answer,1)));
                    add_log('<pre>'.htmlspecialchars(print_r($answer,1)).'</pre>');
//                    add_log($xml);
//                    add_log($qrootAtt);
                    break;
                case 'show_query_sent':
                case 'show_query_change':
//                    $queryType = 'query-create-referral';
//                    $queryType = 'query-dictionaries';
//                    $q = 'query-dictionaries';
                    $q = 'query-create-referral';
                    $q = 'query-create-referral';
                    if($_status == 'show_query_change')
                    $q = 'query-edit-referral';
        //            $data_ = $this->queryBuild($q);
                    
                    /*         определение доступка к лис        */
                    
                    $gid = (int) get_post_meta( $orderId, 'dso_ml_group', true );
                    if(!$gid){
                        $uid = get_current_user_id();
                        $gid = (int) get_user_meta($uid,'lab_group',1);
                    }
                    
                    set_ml_access_by_group($gid);
                    
                    /*        / определение доступка к лис        */
                    $data_ = $this->build_query_referral($q,$orderId,true);
                    add_log('<pre>'.htmlspecialchars(print_r($data_,1)).'</pre>');
                    
                    break;
                case 'show_get_answer':
                    $atts = [];
                    $atts['is_show_test'] = true;
                    add_log('<pre>'.htmlspecialchars(print_r($data_,1)).'</pre>');
                    break;
                
                case 'show_get_dict_version':
                    $q = 'query-dictionaries-version';
        //            $data_ = $this->queryBuild($q);
//                    $data_ = MedLab::queryBuild($q);
//                    add_log(htmlspecialchars(print_r($data_,1)));
                    break;
                case 'get_dict_version':
                    $atts = [];
                    $atts['is_show_test'] = true;
                    $q = 'query-dictionaries-version';
                    $data_x = MedLab::_queryBuild($q,$atts);
                    $data_ = MedLab::_queryBuild($q);
//                    add_log(htmlspecialchars(print_r($data_,1)));
                    
                    $answer = doPostRequest($data_);
                    $xml = simplexml_load_string($answer);
                    $qrootAtt = MedLab::_buildAttrs($xml);
//                    add_log((print_r($answer,1)));
//                    add_log(htmlspecialchars(print_r($xml,1)));
//                    add_log(htmlspecialchars(print_r($qrootAtt,1)));
//                    add_log($xml);
//                    add_log($qrootAtt);
//                    add_log(MedLab::buildDict($xml,'Version','Version'));
                    add_log('<pre>'.htmlspecialchars(print_r($data_x,1)).'</pre>');
                    add_log('<pre>'.htmlspecialchars(print_r($answer,1)).'</pre>');
//                    add_log(MedLab::buildAttrs($xml->Version));
                    break;
                
                case 'send_query':
                case 'change_query':
                    $q = 'query-create-referral';
                    if($_status == 'change_query')
                    $q = 'query-edit-referral';
                    
                    /*         определение доступка к лис        */
                    
                    $gid = (int) get_post_meta( $orderId, 'dso_ml_group', true );
                    if(!$gid){
                        $uid = get_current_user_id();
                        $gid = (int) get_user_meta($uid,'lab_group',1);
                    }
                    
                    set_ml_access_by_group($gid);
                    
                    /*        / определение доступка к лис        */
                    $data_ = $this->build_query_referral($q,$orderId,1);
                    if(current_user_can('manage_options')) add_log('$data_<pre>'.htmlspecialchars(print_r($data_,1)).'</pre>');
                    $num = apply_filters( 'medlab_num_query_reset', $orderId, true );
                    set_ml_access_by_group($gid);
                    $data_ = $this->build_query_referral($q,$orderId);
                    
                    $answer = doPostRequest($data_);
                    $xml = simplexml_load_string($answer);
                    $qrootAtt = MedLab::_buildAttrs($xml);
                    
//                    add_log((print_r($answer,1)));
//                    add_log(htmlspecialchars(print_r($xml,1)));
//                    add_log(htmlspecialchars(print_r($qrootAtt,1)));
//                    add_log($xml);
//                    add_log($qrootAtt);
                    
//                    add_log(MedLab::buildDict($xml,'Version','Version'));
//                    add_log('<pre>'.htmlspecialchars(print_r($data_x,1)).'</pre>');
                    
//                    if(current_user_can('manage_options')) add_log('$answer<pre>'.htmlspecialchars(print_r($answer,1)).'</pre>');
                    update_post_meta( $orderId, 'dso_q__answer', print_r($xml,1) );
                    $answer_ =  get_post_meta( $orderId, 'dso_q__answer', true );
                    if(current_user_can('manage_options')) add_log('$answer_<pre>'.htmlspecialchars(print_r($answer_,1)).'</pre>');
                    add_log('Номер в лаборатории LisId: <pre>'.htmlspecialchars(print_r(''.$xml->Referral['LisId'],1)).'</pre>');
                    add_log('Номер заказа Nr: <pre>'.htmlspecialchars(print_r(''.$xml->Referral['Nr'],1)).'</pre>');
//                    [MisId] => 187
//                    [Nr] => 9950000187
//                    [LisId] => 968063
//                    [MisId] => 187
//                    [Nr] => 9950000187
//                    [LisId] => 968063
//                    sent
//                        add_log('save start');
                    if(!isset($qrootAtt['Error']) ){ // && isset($xml->Referral['LisId'])
//                        add_log(isset($qrootAtt['Error']).' == !isset($qrootAtt[Error])');
                        if($qrootAtt['MessageType'] == 'result-import-referral' ){
//                            add_log($qrootAtt['MessageType'].' == result-import-referral');
                            update_post_meta( $orderId, 'dso_query_id', ''.$xml->Referral['LisId'] );
                            update_post_meta( $orderId, 'dso_query_nr', ''.$xml->Referral['Nr'] );
                            update_post_meta( $orderId, 'dso_query_status', 'sent' );
                            $state_ =  get_post_meta( $orderId, 'dso_status', true );
                            $status = 'query_sent';
                            if($state_=='send_query')$status = 'query_sent';
                            if($state_=='change_query')$status = 'query_sent';
                            update_post_meta( $orderId, 'dso_status', $status );
                            $user = wp_get_current_user();
                            update_post_meta( $orderId, 'dso_sender', $user->ID );
                            $num = apply_filters( 'medlab_num_query_reset', $orderId, false );
                            
                            update_post_meta( $orderId, 'dso_ml_group', $gid ); // medlab user group
                        }else{
                            $num = apply_filters( 'medlab_num_query_reset', $orderId, true );
//                            add_log($qrootAtt['MessageType'].' != result-import-referral');
                        }
                    }else{
                            $num = apply_filters( 'medlab_num_query_reset', $orderId, true );
                        
//                        add_log(isset($qrootAtt['Error']).' == isset($qrootAtt[Error])');
                    }
                    if(isset($xml->Warnings) && isset($xml->Warnings->Item) ){
                        $Items = $xml->Warnings->Item;
                        if(!is_array($Items)){
                            $Items = [ $Items ];
                        }
//                                add_log('count an bm : '.count($an_bm_));

                        foreach($Items as $Item){
                            $mess = $Item['Text'];
                            add_log('Warnings: '.$mess,'def','warning');
                        }
                    }
                    if(isset($xml->Containers) && isset($xml->Containers->Item) ){
                        $Items = $xml->Containers->Item;
                        if(!is_array($Items)){
                            $Items = [ $Items ];
                        }
//                                add_log('count an bm : '.count($an_bm_));

                        foreach($Items as $Item){
//                            $mess = $Item['Text'];
//                            add_log('Warnings: '.$mess);
                        }
                    }
                    if(isset($qrootAtt['Error']) ){
//                        $mess = $xml->Error->Item['Text'];
                        $mess = $qrootAtt['Error'];
                        add_log('Error: '.$mess,'def','danger');
                    }
//                        add_log('save end');
                    break;
//                case 'change_query':
//                    break;
    //            case 'query_sent':
    //                break;
                case 'get_answer':
                    $q = 'query-referral-results';
                    $atts = [];
                    $atts['is_show_test'] = true;
                    $query=[];
                    $numgroup = 9950000000;
                    $num = $numgroup;
                    $num = $num + $orderId;
                    $num = apply_filters( 'medlab_num_query_get', $num, $orderId, $numgroup );
                    $query['MisId'] = $orderId;
                    $query['Nr'] = $num;
                    $query['LisId'] = get_post_meta( $orderId, 'dso_query_id', true );
                    $atts['query'] = $query;
                    $data_ = MedLab::_queryBuild($q,$atts);
//                    add_log('<pre>'.htmlspecialchars(print_r($atts,1)).'</pre>');
//                    add_log('<pre>'.htmlspecialchars(print_r($data_,1)).'</pre>');
                    
                    unset($atts['is_show_test']);
                    $data_ = MedLab::_queryBuild($q,$atts);
                    
                    $answer = doPostRequest($data_);
                    $xml = simplexml_load_string($answer);
                    $qrootAtt = MedLab::_buildAttrs($xml);
//                    $qrootAtt = MedLab::buildAttrs($xml);
                    
//                    add_log((print_r($answer,1)));
//                    add_log(htmlspecialchars(print_r($xml,1)));
//                    add_log(htmlspecialchars(print_r($qrootAtt,1)));
//                    add_log($xml);
//                    add_log($qrootAtt);
                    
//                    add_log('$answer<pre>'.htmlspecialchars(print_r($answer,1)).'</pre>');
                    update_post_meta( $orderId, 'dso_q__answer', print_r($xml,1) );
                    $answer_ =  get_post_meta( $orderId, 'dso_q__answer', true );
                    add_log('$answer_<pre>'.htmlspecialchars(print_r($answer_,1)).'</pre>');
//                    add_log('LisId<pre>'.htmlspecialchars(print_r(''.$xml->Referral['LisId'],1)).'</pre>');
                    if(isset($xml->Referral) ){
                        $atts = $xml->Referral->attributes();
                        if(isset($atts['Done']) && $atts['Done'] == 'true'){
                            update_post_meta( $orderId, 'dso_answer_status', 'got' );
                            $status = 'answer_got';
//                            if($state_=='send_query')$status = 'query_sent';
//                            if($state_=='change_query')$status = 'query_sent';
                            update_post_meta( $orderId, 'dso_status', $status );
                        }
//                        $Items = $xml->Containers->Item;
//                        if(!is_array($Items)){
//                            $Items = [ $Items ];
//                        }
////                                add_log('count an bm : '.count($an_bm_));
//
//                        foreach($Items as $Item){
////                            $mess = $Item['Text'];
////                            add_log('Warnings: '.$mess);
//                        }
                    }
                    if(isset($xml->Blanks) && isset($xml->Blanks->Item) ){
                        $Items = $xml->Blanks->Item;
                        if(!is_array($Items)){
                            $Items = [ $Items ];
                        }
//                                add_log('count an bm : '.count($an_bm_));

                        foreach($Items as $Item){
//                            $mess = $Item['Text'];
//                            add_log('Warnings: '.$mess);
                            $atts = $xml->$Item->attributes();
                            $BlankId = $atts['BlankId'];
                            $BlankGUID = $atts['BlankGUID'];
                            $FileName = $atts['FileName'];
                        }
                    }
//                    2019923968063-968079.pdf
                    break;
    //            case 'answer_got':
    //                break;
                case 'get_answer_file':
                    break;
    //            case 'answer_file_got':
    //                break;
            }
        }
//        add_log('saved');
//        add_log($_status);
//        add_log($state_);
//        $this->n($state_);
        
            $fields=$this->meta_fields;
            foreach($fields as $f=>$l){
                if ( isset( $_POST[$f] )
                        && $_POST[$f] != '' ) {
                    update_post_meta( $orderId, $f, $_POST[$f] );
                }
            }
    }
    public function build_query_referral($q,$orderId,$istest=0){
                $_comment = filter_input(INPUT_POST, 'dso_q_ref_comment', FILTER_DEFAULT); // FILTER_SANITIZE_NUMBER_INT
                    $atts = [];
                    if($istest)
                    $atts['is_show_test'] = true;
                    
                    $atts['dso_id'] = $orderId;
                    $atts['puid'] = get_post_meta( $orderId, 'dso_puid', true );
                    $atts['duid'] = get_post_meta( $orderId, 'dso_duid', true );
                    $ref = [];
                    $ref['comment'] = $_comment;
                    
                    
                    $atts['refferral'] = $ref;
                    
                    $orders = [];
                    // параметры по умолчанию
                     $args = [

                        'numberposts' => 1000,
                        'offset'    => 0,
                        'category'    => 0,
                        'orderby'     => 'date',
                        'order'       => 'DESC',
                        'include'     => array(),
                        'exclude'     => array(),
                        'meta_key'    => 'dsoi_orderId',
                        'meta_value'  => $orderId,
                        'post_type'   => 'dsoitem',
                        'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
                     ];
                    $query = new WP_Query( $args );
                    $count =  $query->found_posts;
                    if(!$count){
                    }else{
                        $medLab = MedLab::_instance();

                        $groups = $medLab->groups;
                        $analyses = $medLab->analyses;
                        $panels = $medLab->panels;
                        $tests = $medLab->tests;
                        $biomaterials = $medLab->biomaterials;
                        $drugs = $medLab->drugs;
                        $microorganisms = $medLab->microorganisms;
                        $containers = $medLab->containers;
                        $price = $medLab->price;
                        $currency_short = get_option('currency_short','zl');
        
                        $posts = get_posts( $args );
                        $total = 0;
                        $dso_items_count=0;
                        $dso_count=0;
                        foreach( $posts as $num=>$item ){
                            $order = [];
                            $dsoi_prodId_ =  get_post_meta( $item->ID, 'dsoi_prodId', true );
                
                            // параметры для брака позиций
                            $dsoi_refState_ =  get_post_meta( $item->ID, 'dsoi_refState', true );
                            $dsoi_defected_ =  get_post_meta( $item->ID, 'dsoi_defected', true );
                            $dsoi_defects_ =  get_post_meta( $item->ID, 'dsoi_defects', true );

                            $pid = $dsoi_prodId_;

                            $code = '';
                            $bcode = '';
                            
                            // параметры для брака позиций
                            $defected = false;
                            $defects = false;
                            $DefectCode = false;
                            
                            // параметры для брака позиций
                            if($dsoi_refState_) $state = $dsoi_refState_;
                            if($dsoi_defected_) $defected = $dsoi_defected_;
                            if($dsoi_defects_) $defects = $dsoi_defects_;
                            
                            if(isset($analyses[$pid])){
                                $an = $analyses[$pid];
                                $code=$analyses[$pid]['Code'];
                                $BiomaterialId = ''.$an['item']->AnalysisBiomaterials->Item['BiomaterialId'];
                                $ContainerTypeId = ''.$an['item']->AnalysisBiomaterials->Item['ContainerTypeId'];
                                $an_bm = $an['item']->AnalysisBiomaterials->Item;
                                if(is_array($an_bm)){
                                    add_log('count an bm : '.count($an_bm));
                                    $an_bm = $an_bm[0];
                                }
                                
                                $BiomaterialId = ''.$an_bm['BiomaterialId'];
                                $BiomaterialCode = ''.$an_bm['BiomaterialCode'];
                                $ContainerTypeId = ''.$an_bm['ContainerTypeId'];
                                
                                $an_bm_name = $biomaterials[$BiomaterialId]['Name'];
                                
                                $bcode = $BiomaterialCode;
//                                add_log('<pre>'.htmlspecialchars(print_r($BiomaterialId,1)).'</pre>');
//                                add_log('<pre>'.htmlspecialchars(print_r($an_bm_name,1)).'</pre>');
//                                add_log('<pre>'.htmlspecialchars(print_r($biomaterials[$BiomaterialId],1)).'</pre>');
//                                add_log('<pre>'.htmlspecialchars(print_r($containers,1)).'</pre>');
//                                add_log('<pre>'.htmlspecialchars(print_r($analyses[$pid],1)).'</pre>');
                            }
                            if(isset($panels[$pid])){
                                $code=$panels[$pid]['Code'];
//                                add_log('<pre>'.htmlspecialchars(print_r($panels[$pid],1)).'</pre>');
                            }
                            $order['code'] = $code;
                            $order['BiomaterialCode'] = $bcode;
                            
                            // параметры для брака позиций
                            $order['State'] = $state;
                            $order['Defected'] = $defected;
                            $order['Defects'] = $defects;
                            $order['DefectCode'] = $DefectCode;
                            
                            $orders[]=$order;
                        }
                    }
                    $atts['orders'] = $orders;
                    
                    $Assays = [];
                    $Items = [];
                    $Item = [];
                    $Order = [];
                    $OItems = [];
                    $OItem = [];
                    $OItem['code'] = '20-002';
                    $OItems[]=$OItem;
                    $OItem['code'] = '20-003';
                    $OItems[]=$OItem;
                    $OItem['code'] = '20-004';
                    $OItems[]=$OItem;
                    $Order=$OItems;
                    $Item['Barcode']='9930001410';
                    $Item['BiomaterialCode']='50002';
                    $Item['orders']=$Order;
                    $Items[]=$Item;
                    $Items[]=$Item;
                    $Assays = $Items;
                    
//                    $atts['Assays'] = $Items;
                    
                    
                    $data_ = MedLab::_queryBuild($q,$atts);
//                    add_log('<pre>'.htmlspecialchars(print_r($atts,1)).'</pre>');
        return $data_;
    }
    /**
     * example
     * сохранение данных полей метатегов
     * @param type $post_id
     * @param type $dsorder
     */
    public function add_metabox_fields( $orderId, $dsorder ) {
        if ( $dsorder->post_type == $this->name ) {
            $fields=$this->meta_fields;
            foreach($fields as $f=>$l){
                if ( isset( $_POST[$f] )
                        && $_POST[$f] != '' ) {
                    update_post_meta( $orderId, $f, $_POST[$f] );
                }
            }
                $total = 0;
                $dso_items_count=0;
                $dso_count=0;
            
            // параметры по умолчанию
            $args = [

               'numberposts' => 1000,
               'offset'    => 0,
               'category'    => 0,
               'orderby'     => 'date',
               'order'       => 'DESC',
               'include'     => array(),
               'exclude'     => array(),
               'meta_key'    => 'dsoi_orderId',
               'meta_value'  => $orderId,
               'post_type'   => 'dsoitem',
               'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
            ];
            $query = new WP_Query( $args );
            $count =  $query->found_posts;
            if($count){
                $posts = get_posts( $args );
                
                $pid = filter_input(INPUT_POST, 'dsoi_id',FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);
                if($pid===false || $pid===null|| $pid==='')$pid=[];
                $count = filter_input(INPUT_POST, 'dsoi_cou',FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);
                if($count===false || $count===null || $count==='')$count=[];
                $percent = filter_input(INPUT_POST, 'dsoi_perc',FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);
                if($percent===false || $percent===null || $percent==='')$percent=[];

                $pids=[];
                foreach( $posts as $num=>$item ){
                    $pids[]=$item->ID;
                }
                foreach( $pid as $num=>$id ){
                    if(in_array($id,$pids) ){
                        if(! isset($count[$num]) )$count[$num]=0;
                        if(! isset($percent[$num]) )$percent[$num]=0;
                        $cou = $count[$num];
                        $perc = $percent[$num];
                        update_post_meta( $id, 'dsoi_count', $cou );
                        update_post_meta( $id, 'dsoi_markup', $perc );
                        
                        $icost =  get_post_meta( $id, 'dsoi_item_cost', true );
                        $deliv =  get_post_meta( $id, 'dsoi_delivery_poland_cost', true );
                        $deliv_cost = $deliv + ( ($deliv/100) * $perc);
                        $summ = ($icost * $cou) + $deliv_cost;
                        update_post_meta( $id, 'dsoi_items_cost', $summ );
                        update_post_meta( $id, 'dsoi_delivery_cost', $deliv_cost );
                        
                    $dso_items_count+=$cou;
                    $dso_count++;
                    $total += $summ;
                    }
                }
                update_post_meta( $orderId, 'dso_items_count', $dso_items_count );
                update_post_meta( $orderId, 'dso_count', $dso_count );
                update_post_meta( $orderId, 'dso_cost', $total );
                
            }
        }
    }
    public static function __callStatic($name, $arguments) {
        self::_init();
//        self::init();
        $_name = $name;
        if(strlen($name)>1 && $name[0] === '_'){
            $name = str_split($name);
            unset($name[0]);
            $name = implode('',$name);
        }
//        add_log($arguments);
        if(count($arguments)==1)
        return self::$instance->$name($arguments[0]);
        if(count($arguments)==2)
        return self::$instance->$name($arguments[0],$arguments[1]);
        if(count($arguments)==3)
        return self::$instance->$name($arguments[0],$arguments[1],$arguments[2]);
        if(count($arguments)==4)
        return self::$instance->$name($arguments[0],$arguments[1],$arguments[2],$arguments[3]);
        return self::$instance->$name($arguments);
//        return self::$instance->$name(extract ($arguments));
//        return self::$instance->$name($arguments);
//        add_log($name);
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
        if(is_array($m)|| is_object($m))
            $m= '<pre>'.print_r($m,1).'</pre>';
        echo '<div class="notice '.$class.' is-dismissible"> <p>'. $m .'</p></div>';
    }
}