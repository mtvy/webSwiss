<?php

/* 
 * trait.DSGet.php
 */

trait DSGet{
    public static function _count_in_cart(){
        self::init();
        return self::$instance->count_in_cart();
    }
    public function get_a_count_in_cart(){
        $res = [];
        $res['count'] = $this->count_in_cart();
        return $res;
    }
    public function _a_add_to_cart(){
        $pid = filter_input(INPUT_POST, 'pid', FILTER_SANITIZE_NUMBER_INT);
        if($pid===false || $pid===null|| $pid==='')$pid=0;
        $count = filter_input(INPUT_POST, 'count', FILTER_SANITIZE_NUMBER_INT);
        if($count===false || $count===null || $count==='')$count=0;
        $res = [];
        $res['mess']=[];
        
        $added = $this->add_to_cart($pid,$count);
        $added = apply_filters('ds__add_to_cart',$pid,$added);
//        $added = do_action('ds__add_to_cart',$pid,$added);
        if(!is_wp_error($added)){
            $res['mess'][] = sprintf('В корзину добавлено %1$d позиций',$added);
            $res = apply_filters('ds_ajax_addtocart_result_ok', $res,$pid,$added);
        }else{
            $res['mess'][] = 'Сбой добавления в корзину.';
            foreach ( $added->get_error_messages() as $message ) {
//                printf( '<p>%s</p>', $message );
                $res['mess'][] = $message;
            }
            $res = apply_filters('ds_ajax_addtocart_result_err', $res,$pid,$added);
        }
        $res['count'] = $this->count_in_cart();
        return $res;
    }
    public function addXmlAnaliseProdItem($_pid,$added){
        // safe code info of product for create order item
        $added = $this->addProdItem($_pid,$added);
        return $added;
    }
    public function addProdItem($_pid,$added){
        $res = true;
        

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

        $pid=0;
        $max=1000;$min=1;
        $url='';
        $cost=0;
        $deliv=0;
        $percent=0;//deliv percent
        $categid=0;$categ='';
        $item = null;
        
//        add_log($pid);
        
        if(isset($analyses[$_pid])){
            $pid=$_pid;
            $name=$analyses[$_pid]['Name'];
            $url=get_the_permalink( 135 ).'?sid='.$_pid;
            $url=get_the_permalink( get_option('ds_id_page_item') ).'?pid='.$_pid;
            if(isset($analyses[$_pid]['AnalysisGroupId']))
                $categid=$analyses[$_pid]['AnalysisGroupId'];
            if(isset($groups[$categid]))
                $categ=$groups[$categid]['Name'];
        }
        if(isset($panels[$_pid])){
            $pid=$_pid;
            $name=$panels[$_pid]['Name'];
            $url=get_the_permalink( 135 ).'?sid='.$_pid;
            $url=get_the_permalink( get_option('ds_id_page_item') ).'?pid='.$_pid;
            
            if(isset($panels[$_pid]['AnalysisGroupId']))
                $categid=$panels[$_pid]['AnalysisGroupId'];
            if(isset($groups[$categid]))
                $categ=$groups[$categid]['Name'];
        }
        if(isset($price[$_pid])){
            $cost=$price[$_pid]['Price'];
        }
        $errors = new WP_Error();
        if ( $pid==0 )
            $errors->add( 'no_pid', 'Продукт не обнаружен' );
        if ( ! empty( $errors->get_error_codes() ) )
            return $errors;
        $this->add_prod_safe($pid,$max,$min,
                $name,$url,
                $cost,$deliv,$percent,$categid,$categ);
        return $added;
    }
    public function _a_remove_from_cart(){
        $pid = filter_input(INPUT_POST, 'pid', FILTER_SANITIZE_NUMBER_INT);
        if($pid===false || $pid===null|| $pid==='')$pid=0;
        $res = [];
        $res['mess']=[];
        $removed = $this->remove_from_cart($pid);
        if(!is_wp_error($removed)){
            $res['mess'][] = sprintf('из корзины удалено %1$d позиций',$removed);
        }else{
            $res['mess'][] = 'Сбой Удаления из корзины.';
            foreach ( $removed->get_error_messages() as $message ) {
//                printf( '<p>%s</p>', $message );
                $res['mess'][] = $message;
            }
        }
        $res['count'] = $this->count_in_cart();
        return $res;
    }
    public function count_in_cart(){
        if(empty($_SESSION['ds_cart'])){
            $_SESSION['ds_cart']=array();
        }
        $count = count($_SESSION['ds_cart']);
        if($count>0)
            $count = array_sum($_SESSION['ds_cart']);
        return $count;
    }
    public function add_to_cart_discont($name='',$percent=0,$from=0,$id=''){
        if(empty($_SESSION['ds_discont'])){
            $_SESSION['ds_discont']=array();
            $_SESSION['ds_discont_id']=array();
        }
        if($name==='')return;
        
        //if($percent>0)
            $_SESSION['ds_discont'][$name]=$percent;
            $_SESSION['ds_discont_id'][$name]=$id;
        
        if(empty($_SESSION['ds_discont_from'])){
            $_SESSION['ds_discont_from']=array();
        }
        if($from>0)
        $_SESSION['ds_discont_from'][$name]=$from;
    }
    public function remove_from_cart_discont($name=false){
        if(!$name){
            $_SESSION['ds_discont']=array();
            $_SESSION['ds_discont_from']=array();
            $_SESSION['ds_discont_id']=array();
        }else{
            unset($_SESSION['ds_discont'][$name]);
            unset($_SESSION['ds_discont_from'][$name]);
            unset($_SESSION['ds_discont_id'][$name]);
        }
    }
    public function get_cart_discont($name=false,$def=0){
        if(isset($_SESSION['ds_discont'][$name])){
            return $_SESSION['ds_discont'][$name];
        }else{
            return $def;
        }
    }
    public function get_cart_discont_id($name=false,$def=''){
        if(isset($_SESSION['ds_discont_id'][$name])){
            return $_SESSION['ds_discont_id'][$name];
        }else{
            return $def;
        }
    }
    public function get_cart_discont_from($name=false,$def=0){
        if(isset($_SESSION['ds_discont_from'][$name])){
            return $_SESSION['ds_discont_from'][$name];
        }else{
            return $def;
        }
    }
    public function add_to_cart($pid=0,$count=0){
        if(empty($_SESSION['ds_cart'])){
            $_SESSION['ds_cart']=array();
        }
        $postId = $this->ds_get_postid_by_pid($pid);
        $ds_cart_item_max = get_option('ds_cart_item_max',1);
        if($postId){
            
        }
        $cou_0 = $count;
        if(isset($_SESSION['ds_cart'][$pid])){
            $cou_0+=$_SESSION['ds_cart'][$pid];
        }
        $overlimit = 0;
        if($cou_0>$ds_cart_item_max){
            $cou_1=$cou_0-$ds_cart_item_max;
            if( ($count-$cou_1) >0){
                $count-=$cou_1;
            }else if($count>0){
                $overlimit = 1;
            }
        }
        
        if($pid==0 || $count==0 || $overlimit > 0){
            $errors = new WP_Error();
            if ( $pid==0 )
                $errors->add( 'no_pid', 'Продукт не обнаружен' );
            if ( $count==0 )
                $errors->add( 'no_count', 'Нельзя добавить 0 позиций.' );
            if ( $overlimit>0 )
                $errors->add( 'overlimit', 'Достигнут лимит заказа по данной позиции.' );
            // Если возникла хотя бы одна из ошибок.
            if ( ! empty( $errors->get_error_codes() ) )
                return $errors;
        }
        if(empty($_SESSION['ds_cart'][$pid])){
            $_SESSION['ds_cart'][$pid]=0;
        }
        $_SESSION['ds_cart'][$pid]+=$count;
        return $count;
    }
    public function update_cart($pid=0,$count=0){
        if(empty($_SESSION['ds_cart'])){
            $_SESSION['ds_cart']=array();
        }
        $_SESSION['ds_cart'][$pid]=$count;
    }
    public function replace_cart($pid=[],$count=[]){
        $errors = new WP_Error();
        if ( !is_array($pid) )
            $errors->add( 'no_a_pid', 'Не верный формат продуктов.' );
        if ( !is_array($count) )
            $errors->add( 'no_a_count', 'Не верный формат количества позиций.' );

        if ( count($pid)==0 )
            $errors->add( 'no_pid', 'Продукт не обнаружен.' );
        if ( count($count)==0 )
            $errors->add( 'no_count', 'Нельзя добавить 0 позиций.' );
        $pids_=[];$cous_=[];
        foreach($pid as $k=>$pid_){
            if(!is_numeric($pid[$k]) ){
                $errors->add( 'no_i_pid_'.$k, 'Не верный формат id продукта. Требуется INT.' );
                continue;
            }
            if( !is_numeric($count[$k])){
                $errors->add( 'no_i_cou_'.$k, 'Не верный формат количества. Требуется INT.' );
                continue;
            }
            if($count[$k]<1)continue;
            $pids_[] = ceil($pid[$k]);
            $cous_[] = ceil($count[$k]);
        }
        $pid = $pids_;
        $count = $cous_;
        // Если возникла хотя бы одна из ошибок.
        if ( ! empty( $errors->get_error_codes() ) )
            return $errors;
        
        $_SESSION['ds_cart']=array();
        foreach($pid as $k=>$pid_){
            $_SESSION['ds_cart'][$pid[$k]]=$count[$k];
        }
        if(count($_SESSION['ds_cart']) == 0)add_log('Корзина пуста.');
        $cou = array_sum($_SESSION['ds_cart']);
        return $cou;
    }
    public function remove_from_cart($pid=0){
        if(empty($_SESSION['ds_cart'])){
            $_SESSION['ds_cart']=array();
        }
        $_cou = count($_SESSION['ds_cart']);
        if($_cou==0 || $pid>0){
            $errors = new WP_Error();
            if ( $pid>0 && !array_key_exists($pid, $_SESSION['ds_cart']) )
                $errors->add( 'no_pid', 'Продукт в корзине не обнаружен' );
            if ( $_cou==0 )
                $errors->add( 'no_count', 'Корзина пуста, нечего удалять.' );
//            // Если возникла хотя бы одна из ошибок.
            if ( ! empty( $errors->get_error_codes() ) )
                return $errors;
        }
        $cou=0;
        if($pid==0){
            $cou = array_sum($_SESSION['ds_cart']);
//            unset($_SESSION['ds_cart']);
            $_SESSION['ds_cart']=array();
        }
        if($pid>0){
            $cou = (int) $_SESSION['ds_cart'][$pid];
            unset($_SESSION['ds_cart'][$pid]);
        }
        $cou = array_sum($_SESSION['ds_cart']);
        return $cou;
    }
    public static function _add_prod_safe($pid=0,$max=0,$min=0,$name='',$url='',$cost=0,$deliv=0,$percent=0,$categid=0,$categ=''){
        self::init();
        self::$instance->add_prod_safe($pid,$max,$min,$name,$url,$cost,$deliv,$percent,$categid,$categ);
    }
    public function add_prod_safe($pid=0,$max=0,$min=0,$name='',$url='',$cost=0,$deliv=0,$percent=0,$categid=0,$categ=''){
        if(empty($_SESSION['ds_prod_safe'])){
            $_SESSION['ds_prod_safe']=array();
        }
        $_SESSION['ds_prod_safe'][$pid] =
            ['pid'=>$pid,'max'=>$max,'min'=>$min,'name'=>$name,'url'=>$url,
                'cost'=>$cost,'deliv'=>$deliv,'percent'=>$percent,
                'categid'=>$categid,'categ'=>$categ];
    }
    public function clear_prod_safe(){
        $_SESSION['ds_prod_safe']=array();
    }
    public function clear_cart(){
        $_SESSION['ds_cart']=array();
    }
    public function get_prod_safe($pid=0){
        if($pid>0 && isset($_SESSION['ds_prod_safe'][$pid]))
            return $_SESSION['ds_prod_safe'][$pid];
        else if($pid>0)
            return false;
        else
            return $_SESSION['ds_prod_safe'];
    }
    
    public function _a_order_ch_mail_ex($email=''){
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        if($email===false || $email===null|| $email==='')$email='';
        $res = [];
        $res['mess']=[];
//        if(!function_exists('email_exists')){
//            require_once ABSPATH . WPINC .'/registration.php';
//        }
        $userId = email_exists($email);
        if(strlen($email)==0){
            $res['mess'][] = sprintf('Требуется ввести email. %s  %s',$email,strlen($email));
        }else
        if(strlen($email)<6){
            $res['mess'][] = sprintf('Слишком короткий email: %s',$email);
        }else
        if(strpos($email,'@')<1 || strpos($email,'.')<3 
                || (strpos($email,'@')>0 && strpos($email,'.')>4
                && strpos($email,'.') < strpos($email,'@'))){
            $res['mess'][] = sprintf('Не верный формат email: %s',$email,var_export(strpos($email,'@'),1));
            if(strpos($email,'@')===false ){
                $res['mess'][] = sprintf('Отсутствует "@" в email: %s',$email);
            }
            if(strpos($email,'.')===false ){
                $res['mess'][] = sprintf('Отсутствует "." в email: %s',$email);
            }
            if(strpos($email,'@')!==false && strpos($email,'@')<1 ){
                $res['mess'][] = sprintf('Не предусмотренная позиция "@" в email: %s',$email);
            }
            if(strpos($email,'.')!==false && strpos($email,'.')<3 ){
                $res['mess'][] = sprintf('Не предусмотренная позиция "." в email: %s',$email);
            }
            if( (strpos($email,'@')>0 && strpos($email,'.')>4
                    && strpos($email,'.') < strpos($email,'@'))){
                $res['mess'][] = sprintf('Не верный порядок "@" и "." в email: %s',$email);
            }
        }else
        if(!$userId){
            $res['res'] = 1;
//        if(!is_wp_error($exists)){
            $res['mess'][] = sprintf('Email %s свободен.',$email);
            $res['mess'][] = sprintf('Указанный адрес, будет использован для регистрации.',$email);
        }else{
            $res['res'] = 0;
            $res['mess'][] = sprintf('Email %s занят.',$email);
            $res['mess'][] = sprintf('Перед оформлением заказа, зайдите в свой аккаунт.',$email);
//            foreach ( $exists->get_error_messages() as $message ) {
////                printf( '<p>%s</p>', $message );
//                $res['mess'][] = $message;
//            }
        }
//        $res['count'] = $this->count_in_cart();
        return $res;
    }
    public function email_exists(){
        
    }
    
    // =============================
    
    public function ds_get_cart(){
        
    }
    public function ds_get_cart_empty(){
        // tpl.dshop-cart-empty.php
        $out = '';
        ob_start();
        get_template_part( 'template-parts/dshop/tpl.dshop', 'cart-empty' );
        $out.=ob_get_clean();
//        $posts_count = wp_count_posts('bad_agent')->publish;
        $out = do_shortcode( $out );
        return $out;
    }
    public function get_cart_item(){
        // tpl.dshop-cart-item.php
        $out = '';
        ob_start();
        get_template_part( 'template-parts/dshop/tpl.dshop', 'cart-item' );
        $out.=ob_get_clean();
//        $posts_count = wp_count_posts('bad_agent')->publish;
        $out = do_shortcode( $out );
        return $out;
    }
    public static function _get_cart_items(){
        self::init();
        return self::$instance->get_cart_items();
    }
    public function get_cart_items(){
        // tpl.dshop-cart-items.php
        $out = '';
        ob_start();
        get_template_part( 'template-parts/dshop/tpl.dshop', 'cart-items' );
        $out.=ob_get_clean();
//        $posts_count = wp_count_posts('bad_agent')->publish;
        $out = do_shortcode( $out );
        return $out;
    }
    public static function _get_checkout_cart_items(){
        self::init();
        return self::$instance->get_checkout_cart_items();
    }
    public function get_checkout_cart_items(){
        // tpl.dshop-cart-items.php
        $out = '';
        ob_start();
        get_template_part( 'template-parts/dshop/tpl.dshop-checkout', 'cart-items' );
        $out.=ob_get_clean();
//        $posts_count = wp_count_posts('bad_agent')->publish;
        $out = do_shortcode( $out );
        return $out;
    }
    public static function _get_checkout_order($name=''){
        self::init();
        return self::$instance->get_checkout_order($name);
    }
    public function get_checkout_order($name=''){
        // tpl.dshop-checkout-order.$name
        $out = '';
        ob_start();
        get_template_part( 'template-parts/dshop/tpl.dshop-checkout-order', $name );
        $out.=ob_get_clean();
//        $posts_count = wp_count_posts('bad_agent')->publish;
        $out = do_shortcode( $out );
        return $out;
    }
    public static function _get_account_fields($name='default'){
        self::init();
        return self::$instance->get_account_fields($name);
    }
    public function get_account_fields($name=''){
        // tpl.dshop-checkout-order.php
        $out = '';
        ob_start();
        get_template_part( 'template-parts/dshop/tpl.dshop-account-fields', $name );
        $out.=ob_get_clean();
//        $posts_count = wp_count_posts('bad_agent')->publish;
        $out = do_shortcode( $out );
        return $out;
    }
    
    // =============================
    public static function _get_account_orders(){
        self::init();
        return self::$instance->get_account_orders();
    }
    public static function _get_order_order(){
        self::init();
        return self::$instance->get_order_order();
    }
    public static function _get_order_payments(){
        self::init();
        return self::$instance->get_order_payments();
    }
    public static function _get_order_items(){
        self::init();
        return self::$instance->get_order_items();
    }
    public static function _get_order_item(){
        self::init();
        return self::$instance->get_order_item();
    }
    public function get_account_orders(){
        // tpl.dshop-account-orders.php
        $out = '';
        ob_start();
        get_template_part( 'template-parts/dshop/tpl.dshop-account', 'orders' );
        $out.=ob_get_clean();
//        $posts_count = wp_count_posts('bad_agent')->publish;
        $out = do_shortcode( $out );
        return $out;
    }
    public function get_order_order(){
        // tpl.dshop-account-orders.php
        $out = '';
        ob_start();
        get_template_part( 'template-parts/dshop/tpl.dshop-order', 'order' );
        $out.=ob_get_clean();
//        $posts_count = wp_count_posts('bad_agent')->publish;
        $out = do_shortcode( $out );
        return $out;
    }
    public function get_order_payments(){
        // tpl.dshop-account-orders.php
        $out = '';
        ob_start();
        get_template_part( 'template-parts/dshop/tpl.dshop-order', 'payments' );
        $out.=ob_get_clean();
//        $posts_count = wp_count_posts('bad_agent')->publish;
        $out = do_shortcode( $out );
        return $out;
    }
    public function get_order_items(){
        // tpl.dshop-account-orders.php
        $out = '';
        ob_start();
        get_template_part( 'template-parts/dshop/tpl.dshop-order', 'items' );
        $out.=ob_get_clean();
//        $posts_count = wp_count_posts('bad_agent')->publish;
        $out = do_shortcode( $out );
        return $out;
    }
    public function get_order_item(){
        // tpl.dshop-account-orders.php
        $out = '';
        ob_start();
        get_template_part( 'template-parts/dshop/tpl.dshop-order', 'item' );
        $out.=ob_get_clean();
//        $posts_count = wp_count_posts('bad_agent')->publish;
        $out = do_shortcode( $out );
        return $out;
    }
    
    // =============================
    
    public function update_account($l='',$f='',$s='',$e='',$p='',$a='',$d='',$c=0,$is_order=false){
        $l = trim($l);
        $f = trim($f);
        $s = trim($s);
        $e = trim($e);
        $p = trim($p);
        $a = trim($a);
        $d = trim($d);
        $c = trim($c);
        // использовать ли доставку и адрес
        $delivery_use = get_option('delivery_use', 0);
        $errors = new WP_Error();
        if(!$is_order && !is_user_logged_in())
            $errors->add( 'no_lgd', 'Пользователь не залогинен.' );
        if ( !is_string($l) || strlen($l) ==0 )
            $errors->add( 'no_lnm', 'Отсутствует фамилия.' );
        if ( !is_string($f) || strlen($f) ==0 )
            $errors->add( 'no_fnm', 'Отсутствует имя.' );
        if ( !is_string($s) || strlen($s) ==0 )
            $errors->add( 'no_snm', 'Отсутствует отчество.' );
        if ($is_order && ( !is_string($e) || strlen($e) ==0 ) )
            $errors->add( 'no_eml', 'Отсутствует почта.' );
        if ( !is_string($p) || strlen($p) ==0 )
            $errors->add( 'no_phn', 'Отсутствует телефон.' );
        if ( !is_string($p) || strlen($p) <7 )
            $errors->add( 'no_phn2', 'Телефон не верный.' );
        
        if($delivery_use){
            if ( !is_string($a) || strlen($a) ==0 )
                $errors->add( 'no_adr', 'Отсутствует адрес.' );
            if ( !is_string($d) || strlen($d) ==0 )
                $errors->add( 'no_dlv', 'Отсутствует доставка.' );
        }
        
        if ($is_order && ( $c != 1 || strlen($c) ==0 ) )
            $errors->add( 'no_chk', 'Нет соглашения на обработку данных.' );
        
//            // Если возникла хотя бы одна из ошибок.
        if ( ! empty( $errors->get_error_codes() ) )
            return $errors;
        
        $user = wp_get_current_user();
        if($user->exists()){
            $user_id = $user->ID;
        //    $user = get_current_user();
//            $last_name = esc_attr(get_the_author_meta('last_name', $user->ID));
//            $first_name = esc_attr(get_the_author_meta('first_name', $user->ID));
//            $second_name = esc_attr(get_the_author_meta('second_name', $user->ID));
//            $user_email = esc_attr(get_the_author_meta('user_email', $user->ID));
//            $phone = esc_attr(get_the_author_meta('phone', $user->ID));
//            $adres = esc_attr(get_the_author_meta('adres', $user->ID));
//            $deliv = esc_attr(get_the_author_meta('deliv', $user->ID));
            
            $userdata = array(
                'ID'              => $user->ID,  // когда нужно обновить пользователя
                'last_name'       => $l, // обязательно
                'first_name'      => $f, // обязательно
//                'user_email'   => $e, // disabled usage update email
            );
//                            add_log($userdata);
            $user_id = wp_update_user( $userdata ); //  return id or error
        }else{
            $login = $e;
    //                            $pass=PASS_SECRET.$phone;
                                $pass=$this->ccab_code_generate(9);
//                                $pass=$inputs['pass'];
                                $m='Новый пароль: '.$pass;
    //                        add_log($m);
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
                                add_user_meta( $uid,'secod', $pass );

                                $res_mess='Вы зарегистрировались.';
//                                    $res_mess='Регистрация прошла успешно, учетная запись ожидает подтверждения администратором.';


                                add_log($res_mess);
//                                add_log($user);
//                                $user_ = ccab_login($login,$pass);
                                $creds = array();
                                $creds['user_login'] = $login;
                                $creds['user_password'] = $pass;
                                $creds['remember'] = true;
                                $user = wp_signon($creds,false); // return object or error
                                if ( is_wp_error( $user ) ) {
                                    return $user;
                                }
                                $user_id = $user->ID;

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
                                    $test_email = true; // тестирование отправки почты
                                    $test_email = false; // тестирование отправки почты
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
                                    $to=array();
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
            
            do_action('ds_update_account__add_user_meta',$this,$user_id);
        }
            update_user_meta ( $user_id, 'second_name', $s );
            update_user_meta( $user_id,'phone', $p );
            update_user_meta( $user_id,'adres', $a );
            update_user_meta( $user_id,'deliv', $d );
            do_action('ds_update_account__update_user_meta',$this,$user_id);
        return $user;
    }
    public function create_account($l='',$f='',$s='',$e='',$p='',$a='',$d='',$c=0,$is_order=true){
        $user = $this->update_account($l,$f,$s,$e,$p,$a,$d,$c,$is_order);
        return $user;
    }
    public function create_order($l='',$f='',$s='',$e='',$p='',$a='',$d='',$c=0){
        $l = trim($l);
        $f = trim($f);
        $s = trim($s);
        $e = trim($e);
        $p = trim($p);
        $a = trim($a);
        $d = trim($d);
        $c = trim($c);
        // использовать ли доставку и адрес
        $delivery_use = get_option('delivery_use', 0);
        $errors = new WP_Error();
        if(!is_user_logged_in())
            $errors->add( 'no_lgd', 'Пользователь не залогинен.' );
        if ( !is_string($l) || strlen($l) ==0 )
            $errors->add( 'no_lnm', 'Отсутствует фамилия.' );
        if ( !is_string($f) || strlen($f) ==0 )
            $errors->add( 'no_fnm', 'Отсутствует имя.' );
        if ( !is_string($s) || strlen($s) ==0 )
            $errors->add( 'no_snm', 'Отсутствует отчество.' );
        if ( !is_string($e) || strlen($e) ==0 )
            $errors->add( 'no_eml', 'Отсутствует почта.' );
        if ( !is_string($p) || strlen($p) ==0 )
            $errors->add( 'no_phn', 'Отсутствует телефон.' );
        if ( !is_string($p) || strlen($p) <7 )
            $errors->add( 'no_phn2', 'Телефон не верный.' );
        
        if($delivery_use){
            if ( !is_string($a) || strlen($a) ==0 )
                $errors->add( 'no_adr', 'Отсутствует адрес.' );
            if ( !is_string($d) || strlen($d) ==0 )
                $errors->add( 'no_dlv', 'Отсутствует доставка.' );
        }
        
        if ( $c != 1 || strlen($c) ==0 )
            $errors->add( 'no_chk', 'Нет соглашения на обработку данных.'.$c );
        
        if(empty($_SESSION['ds_prod_safe'])){
            $_SESSION['ds_prod_safe']=array();
        }
//        $_SESSION['ds_prod_safe'][$pid] =
//            ['pid'=>$pid,'max'=>$max,'min'=>$min,'name'=>$name,'url'=>$url,
//                'cost'=>$cost,'deliv'=>$deliv];
        if(empty($_SESSION['ds_cart'])){
            $_SESSION['ds_cart']=array();
        }
        $cou = array_sum($_SESSION['ds_cart']);
        if ( $cou ==0 )
            $errors->add( 'no_count', 'Корзина пуста.' );
        
//            // Если возникла хотя бы одна из ошибок.
        if ( ! empty( $errors->get_error_codes() ) )
            return $errors;
        
        // create order
        $orderId = $this->_create_order($l,$f,$s,$e,$p,$a,$d);
        if(is_wp_error($orderId))
            return $orderId;
        
        $cart_ = $_SESSION['ds_cart'];
//        add_log($_SESSION);
//        add_log($cart_);
        $total = 0;
        $currency_short = get_option('currency_short','zl');

        $dso_items_count=0;
        $dso_count=0;
        foreach( $cart_ as $pid=>$cou ){
            if(isset($_SESSION['ds_prod_safe'][$pid]) && $cou>0)
                $prod = $_SESSION['ds_prod_safe'][$pid];
            else
                continue;
            // create order item
            $item = $this->create_order_item($orderId,$pid,$cou,$prod,$d);
            if(is_wp_error($item))
                continue;
            $title = $l.' '.$f.' '.$s;
            $title = wp_strip_all_tags( $title );
            $item_args = [];
            $item_args['ID']=$item['ID'];
            $item_args['post_title']=$title.' / '.$item['title'];
//            $item_args['post_title']=$title;
            wp_update_post( wp_slash($item_args) );
            $dso_items_count+=$cou;
            $dso_count++;
            $total += $item['items_cost'];
            // add order item
        }
        update_post_meta( $orderId, 'dso_items_count', $dso_items_count );
        update_post_meta( $orderId, 'dso_count', $dso_count );
        update_post_meta( $orderId, 'dso_cost', $total );
        
        $this->order_mail_send($e,$orderId,$total,$dso_count,$dso_items_count,$cart_);
        
        $order=[];
        $order['ID'] = $orderId;
        $order['items_count'] = $dso_items_count;
        $order['count'] = $dso_count;
        $order['total'] = $total;
        return $order;
    }
    public function order_mail_send($email,$oid,$cost,$count,$items_count,$items){
        /*          ========== отправка почты ==========           */
        $send_order_email=true;
        if($send_order_email){
            $currency_short = get_option('currency_short','zl');
            $atr=array();
            $atr['__order_id__']=$oid;
            $atr['__order_summ__']=$cost;
            $atr['__currency__']=$currency_short;
            $atr['__count__']=$count;
            $atr['__items_count__']=$items_count;

        //                                    $atr['__user_url__']=$inputs['url'];
//            $atr['__user_email__']=$e;
//            $atr['__user_adres__']=$a;
//            $atr['__user_delivery__']=$d;
//            $atr['__user_phone__']=$p;

            $mail_name='order-new';
            $subject = 'Новый заказ';
            $test_email =false; // тестирование отправки почты
            $test_email =true; // тестирование отправки почты
            $test_email =get_option('ds_notification_test')=='1'; // тестирование отправки почты
            if($test_email){ // test 2
                $to=array();
                $to[]='home_work_mail@mail.ru';
//                $to[]='for_lesson_0001@mail.ru';
        //                                        $mres=ccab_get_mail($mail_name,$atr,$to,[],$subject);
                $mres=ccab_get_mail($mail_name,$atr,$to,[],$subject);
                $mres=ccab_get_mail('order-success',$atr,$to,[],$subject);
                if($mres){
                    add_log('письмо отправленно');
                }else{
                    add_log('письмо НЕ отправленно');
                }
            }
            $to=array();
//            $to[]='admin@juvico.ru';//bz12 juvico
//            $to[]='admin@juvico.ru';//bz12 juvico
            $to[]=get_option('ds_notification_admin_mail');//bz12 juvico
//            add_log($to);
            
            $mres=ccab_get_mail('order-new',$atr,$to,[],$subject);
            if($test_email){
                if($mres){
                    add_log('На административный email отправленно письмо с данными о заказе');
                }else{
                    add_log('письмо администратору НЕ отправленно');
                }
            }

            $mail_name='order-success';
            $subject = 'Новый заказ.';
            $to=array();
            $to[]=$email;
            $mres=ccab_get_mail($mail_name,$atr,$to,[],$subject);
            if($mres){
                add_log('На указаный email отправленно письмо с данными о заказе');
            }else{
                add_log('письмо НЕ отправленно');
            }
            
            do_action( 'ds_create_order_email_send', $atr );
        }
        /*          ========== / отправка почты ==========           */
    }
    public $meta_fields_order = [
        'dso_status'=>'Статус заказа',
        'dso_userId'=>'Заказчик (id)',
        'dso_user_name'=>'Имя',
        'dso_user_lastname'=>'Фамилия',
        'dso_user_sname'=>'Отчество',
        'dso_user_phone'=>'Телефон',
        'dso_user_email'=>'Почта',
        'dso_user_addres'=>'Адресс назначения',
        'dso_agriments'=>'Согласие на обработку персональных данных',
        
        'dso_items_count'=>'Количество единиц',
        
        'dso_count'=>'Количество продуктов',
        'dso_cost'=>'Всего',
        
//        'news_type'=>'Тип новостей',
//        'news_source'=>'Источник'
        ];
    public $delivery = '';
    public function _create_order( $l='',$f='',$s='',$e='',$p='',$a='',$d='' ){
//        return 0;
        
            $title = $l.' '.$f.' '.$s;
            $user = wp_get_current_user();
            $user_id = 0;
            if($user->exists()){
                $user_id = $user->ID;
            }
            $post_data = array(
            //	'post_title'    => wp_strip_all_tags( $_POST['post_title'] ),
//                'post_content'  => wp_strip_all_tags( $_POST['post_content'] ),
                'post_title'    => wp_strip_all_tags( $title ),
                'post_content'  => '',
                'post_type'   => 'dsorder',
                'post_status'   => 'publish', // 'draft' | 'publish' | 'pending'| 'future' | 'private',  
                'post_author'   => $user_id,
            //	'post_category' => array( 8,39 )
            );
            // Вставляем запись в базу данных
            $post_id = wp_insert_post( $post_data, true );
            if(is_wp_error($post_id))
                return $post_id;
            
//            $this->meta_fields_order=$d;
            $this->meta_fields_order=[
                'dso_status'=>'created',
                'dso_userId'=>$user_id,
                'dso_user_name'=>$f,
                'dso_user_lastname'=>$l,
                'dso_user_sname'=>$s,
                'dso_user_phone'=>$p,
                'dso_user_email'=>$e,
                'dso_user_addres'=>$a,
                'dso_agriments'=>'1',

                'dso_items_count'=>0,

                'dso_count'=>0,
                'dso_cost'=>0,
                
                'dso_discont'=>0,
                'dso_total'=>0,
                'dso_payd'=>0,

        //        'news_type'=>'Тип новостей',
        //        'news_source'=>'Источник'
                ];
                $this->meta_fields_order['dso_status'] = apply_filters('dso_create_order_meta__status', $this->meta_fields_order['dso_status'], $this);
                $this->meta_fields_order = apply_filters('dso_create_order__initmetafields', $this->meta_fields_order, $this);

            $nm = 'dso_';
            foreach($this->meta_fields_order as $meta_key=>$meta_val){
                add_post_meta( $post_id, $meta_key, $meta_val, 1 );
            }
            return $post_id;
    }
    public $meta_fields_item = [
        
        'dsoi_prodId'=>'Id продукта',
        'dsoi_prodUrl'=>'Источник продукта',
        'dsoi_prodCategory'=>'Категория',
        'dsoi_prodName'=>'Название',
        'dsoi_count'=>'Количество',
        'dsoi_item_cost'=>'Стоимость единицы',
//        'dsoi_items_count'=>'Количество',
        
        'dsoi_delivery_poland_cost'=>'Стоимость доставки по Польше',
        'dsoi_delivery_poland'=>'Служба доставки по польше',
        'dsoi_delivery_cost'=>'Стоимость доставки до склада',
        'dsoi_delivery_id'=>'Id службы доставки',
        'dsoi_delivery_name'=>'Транспортная служба',
        'dsoi_markup'=>'Наценка за доставку до склада %',
        'dsoi_items_cost'=>'Сумма',
        
//        'news_type'=>'Тип новостей',
//        'news_source'=>'Источник'
        ];
    public function create_order_item($orderId,$pid,$cou,$prod,$d){
        $nm = 'dsoi_';
        
            $user = wp_get_current_user();
            $user_id = 0;
            if($user->exists()){
                $user_id = $user->ID;
            }
            $title='';
            $post_data = array(
            //	'post_title'    => wp_strip_all_tags( $_POST['post_title'] ),
//                'post_content'  => wp_strip_all_tags( $_POST['post_content'] ),
                'post_title'    => wp_strip_all_tags( $title ),
                'post_content'  => '',
                'post_type'   => 'dsoitem',
                'post_status'   => 'publish', // 'draft' | 'publish' | 'pending'| 'future' | 'private',  
                'post_author'   => $user_id,
            //	'post_category' => array( 8,39 )
            );
            // Вставляем запись в базу данных
            $post_id = wp_insert_post( $post_data, true );
            if(is_wp_error($post_id))
                return $post_id;
            
            
//        $_SESSION['ds_prod_safe'][$pid] =
//            ['pid'=>$pid,'max'=>$max,'min'=>$min,'name'=>$name,'url'=>$url,
//                'cost'=>$cost,'deliv'=>$deliv,'percent'=>$percent,
//                'categid'=>$categid,'categ'=>$categ];
        
            $deliv_cost = $prod['deliv'] + ( ($prod['deliv']/100) * $prod['percent']);
            
            $summ = ($prod['cost'] * $cou) + $deliv_cost;
            $this->meta_fields_item = [
                'dsoi_orderId'=>$orderId,
                'dsoi_prodId'=>$pid,
                'dsoi_prodUrl'=>$prod['url'],
                'dsoi_prodCatId'=>$prod['categid'],
                'dsoi_prodCategory'=>$prod['categ'],
                'dsoi_prodName'=>$prod['name'],
                'dsoi_count'=>$cou,
                'dsoi_item_cost'=>$prod['cost'],
        //        'dsoi_items_count'=>'Количество',

                'dsoi_delivery_poland_cost'=>$prod['deliv'],
                'dsoi_delivery_poland'=>'',
                'dsoi_delivery_cost'=>$deliv_cost,
                'dsoi_delivery_id'=>$d,
                'dsoi_delivery_name'=>$d,
                'dsoi_markup'=>$prod['percent'],
                'dsoi_items_cost'=>$summ,

        //        'news_type'=>'Тип новостей',
        //        'news_source'=>'Источник'
                ];

            $nm = 'dsoi_';
            foreach($this->meta_fields_item as $meta_key=>$meta_val){
                add_post_meta( $post_id, $meta_key, $meta_val, 1 );
            }
        $res = [];
        $res['title']=$prod['name'];
        $res['items_cost']=$summ;
        $res['ID']=$post_id;
        return $res;
    }
    public function get_order_payment(){
        
    }
    public static function _get_count_dsoitem($oid=0){
        $query = new WP_Query( array(
            'post_type'   => 'dsoitem',
            'meta_key'    => 'dsoi_orderId',
            'meta_value'  => $oid, ) );

        return  $query->found_posts;
   
        $type = 'dsoitem';
        $perm = 'readable';
        global $wpdb;
        $query = "SELECT post_status, COUNT( * ) AS num_posts FROM {$wpdb->posts}"
        . " WHERE post_type = %s";
//        if ( 'readable' == $perm && is_user_logged_in() ) {
//            $post_type_object = get_post_type_object( $type );
//            if ( ! current_user_can( $post_type_object->cap->read_private_posts ) ) {
//                $query .= $wpdb->prepare(
//                    " AND (post_status != 'private'"
//                        . " OR ( post_author = %d"
//                            . " AND post_status = 'private'"
//                        . " )"
//                    . ")",
//                    get_current_user_id()
//                );
//            }
//        }
        $query .= ' GROUP BY post_status';

        $results = (array) $wpdb->get_results( $wpdb->prepare( $query, $type ), ARRAY_A );
        $counts  = array_fill_keys( get_post_stati(), 0 );

        foreach ( $results as $row ) {
            $counts[ $row['post_status'] ] = $row['num_posts'];
        }

        $counts = (object) $counts;
        return $counts;
    }
}