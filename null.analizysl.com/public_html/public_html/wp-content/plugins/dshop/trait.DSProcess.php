<?php

/* 
 * trait.DSProcess
 */

trait DSProcess{
    
    public static function _process(){
        self::init();
        $out=self::$instance->process();
    }
    
    public function process(){
        ini_set("display_errors", "1");
        ini_set("display_startup_errors", "1");
        ini_set('error_reporting', E_ALL);
//        echo 'fffffffffff';
        $ftype = filter_input(INPUT_POST, 'form-type', FILTER_SANITIZE_STRING);
        if($ftype=='user_register')$this->prc_user_register();
//        if($ftype=='add_bad_owner')$this->prc_add_bad_owner();
//        if($ftype=='add_bad_agent')$this->prc_add_bad_agent();
        
        if($ftype=='add_prod_to_cart')$this->prc_add_prod_to_cart();
        if($ftype=='update_cart')$this->prc_update_cart();
        if($ftype=='create_order')$this->prc_create_order();
        if($ftype=='update_account')$this->prc_update_account();
        
        do_action('dshop_process',$ftype,$this);
        
        $ftype = filter_input(INPUT_POST, 'Shp_action', FILTER_SANITIZE_STRING);
//        $ftype = filter_input(INPUT_GET, 'Shp_action', FILTER_SANITIZE_STRING);
        if($ftype=='payment')$this->prc_ds_payment();
//        if($ftype=='dsp_result')$this->prc_update_account();
//        if($ftype=='dsp_success')$this->prc_update_account();
//        if($ftype=='dsp_fail')$this->prc_update_account();
        
    // Just to see what there
//        wp_send_json( array(
//            '$_POST' => $_POST,
//            '$_GET' => $_GET,
//            '$_REQUEST' => $_REQUEST
//        ) );
        do_action('dshop_process',$ftype,$this);
    }
    
    public function prc_ds_payment(){
        global $wpdb,$is_ajax;
        global $DSPs;
        $is_ajax = false;
        $res=false;
        $res_mess=[];
        
        $os = filter_input(INPUT_POST, 'OutSum',FILTER_SANITIZE_STRING);
        $id = filter_input(INPUT_POST, 'InvId',FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'Email',FILTER_SANITIZE_STRING);
        $fee = filter_input(INPUT_POST, 'Fee',FILTER_SANITIZE_STRING);
        $sv = filter_input(INPUT_POST, 'SignatureValue',FILTER_SANITIZE_STRING);
//        $d = filter_input(INPUT_POST, 'dlv',FILTER_SANITIZE_STRING);
        $test = filter_input(INPUT_POST, 'IsTest',FILTER_SANITIZE_NUMBER_INT);
        $ac = filter_input(INPUT_POST, 'Shp_action',FILTER_SANITIZE_STRING);
        
        
        
        // регистрационная информация (пароль #2)
        // registration info (password #2)
        
//        if($DSPs->is_test == 1)
//        $mrh_pass2 = $DSPs->is_test;
//        else
        $mrh_pass2 = $DSPs->mrh_pass2;
        //установка текущего времени
        //current date
//        $tm=getdate(time()+9*3600);
//        $date="$tm[year]-$tm[mon]-$tm[mday] $tm[hours]:$tm[minutes]:$tm[seconds]";

        // чтение параметров
        // read parameters
//        $out_summ = $_REQUEST["OutSum"];
//        $inv_id = $_REQUEST["InvId"];
//        $shp_item = $_REQUEST["Shp_item"];
//        $crc = $_REQUEST["SignatureValue"];

        $crc = strtoupper($sv);

//        $my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass2:Shp_item=$shp_item"));
        $my_crc = strtoupper(md5("$os:$id:$mrh_pass2:Shp_action=$ac"));
//        if($DSPs->is_test == 1)
//            $my_crc = strtoupper(md5("$os:$id:$mrh_pass2:Shp_action=$ac"));
        $crc = strtoupper($crc);
        
//        $oid = filter_input(INPUT_GET, 'oid',FILTER_SANITIZE_STRING);
//        $res = $DSPs->get_p_info($oid,1);

        // проверка корректности подписи
        // check signature
        if ($my_crc ==$crc)
        {
            $uid = 1;
            $way = 'robokassa';
            $orderId = $DSPs->add_payment($id,$os,$way,$fee,$email,$sv,$istest=$test,$result=$_POST);
            if(is_wp_error($orderId)){
                $m = "Сбой сохранения оплаты";
                add_log($m);
            }
            echo "OK$id\n";
            exit();
        }
//        if ($my_crc !=$crc)
//        {
//          echo "bad sign\n".$res;
//          exit();
//        }

        // признак успешно проведенной операции
        // success
//        echo "OK$id\n";
//          exit();

        // запись в файл информации о проведенной операции
        // save order info to file
//        $f=@fopen("order.txt","a+") or
//                  die("error");
//        fputs($f,"order_num :$inv_id;Summ :$out_summ;Date :$date\n");
//        fclose($f);
    }
    
    public function prc_update_account(){
        global $wpdb,$is_ajax;
        $is_ajax = false;
        $res=false;
        $res_mess=[];
        
        $l = filter_input(INPUT_POST, 'lnm',FILTER_SANITIZE_STRING);
        $f = filter_input(INPUT_POST, 'fnm',FILTER_SANITIZE_STRING);
        $s = filter_input(INPUT_POST, 'snm',FILTER_SANITIZE_STRING);
        $e = filter_input(INPUT_POST, 'eml',FILTER_VALIDATE_EMAIL);
        $p = filter_input(INPUT_POST, 'phn',FILTER_SANITIZE_NUMBER_INT);
        $a = filter_input(INPUT_POST, 'adr',FILTER_SANITIZE_STRING);
        $d = filter_input(INPUT_POST, 'dlv',FILTER_SANITIZE_STRING);
        $c = filter_input(INPUT_POST, 'chk',FILTER_SANITIZE_NUMBER_INT);
        $c = 1;
        
//        if(is_user_logged_in())
        $user = $this->update_account($l,$f,$s,$e,$p,$a,$d);
        if(!is_wp_error($user)){
            $res_mess[] = 'Профиль обновлён.';
            $res=1;
        }else{
            $res_mess[] = 'Профиль не обновлён.';
            foreach ( $user->get_error_messages() as $message ) {
                $res_mess[] = $message;
            }
        }
        foreach ( $res_mess as $message ) {
            add_log($message);
        }
        
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
    
    public function prc_create_order(){
        global $wpdb,$is_ajax;
        $is_ajax = false;
        $res=false;
        $res_mess='';
        
//add_log($_POST);
//        $pid = filter_input(INPUT_POST, 'pid',FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);
//add_log($pid);
//        if($pid===false || $pid===null|| $pid==='')$pid=[];
//        $count = filter_input(INPUT_POST, 'cou',FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);
//add_log($count);
//        if($count===false || $count===null || $count==='')$count=[];
//add_log($pid);
//add_log($count);
        $go = filter_input(INPUT_POST, 'go',FILTER_SANITIZE_STRING);
        if($go===false || $go===null|| $go==='')$go='my-account';
        
        $l = filter_input(INPUT_POST, 'lnm',FILTER_SANITIZE_STRING);
        $f = filter_input(INPUT_POST, 'fnm',FILTER_SANITIZE_STRING);
        $s = filter_input(INPUT_POST, 'snm',FILTER_SANITIZE_STRING);
        $e = filter_input(INPUT_POST, 'eml',FILTER_VALIDATE_EMAIL);
        $p = filter_input(INPUT_POST, 'phn',FILTER_SANITIZE_NUMBER_INT);
        $a = filter_input(INPUT_POST, 'adr',FILTER_SANITIZE_STRING);
        $d = filter_input(INPUT_POST, 'dlv',FILTER_SANITIZE_STRING);
        $c = filter_input(INPUT_POST, 'chk',FILTER_SANITIZE_NUMBER_INT);
        $is_order=true;
        
//        $ds = new DShop();
        if(is_user_logged_in())
            $user = $this->update_account($l,$f,$s,$e,$p,$a,$d,$c,$is_order);
        else
            $user = $this->update_account($l,$f,$s,$e,$p,$a,$d,$c,$is_order);
//            $user = $this->create_account($l,$f,$s,$e,$p,$a,$d,$c,$is_order);
        
        $order = $this->create_order($l,$f,$s,$e,$p,$a,$d,$c);
//        $added_cou = $this->replace_cart($pid,$count);
//        $res = !is_wp_error($added_cou);
//        if(count($_SESSION['ds_cart']) == 0)$res==0;
        
        $total = 0;
        $currency_short = get_option('currency_short','zl');
        
        $res_mess=[];
        if(!is_wp_error($user) && !is_wp_error($order)){
            do_action( 'ds_process_create_order_success', $user , $order);
//            $res_mess[] = 'Заказ создан.';
            $res=1;
            
            $pc = $order['count'];
            $added_cou = $order['items_count'];
            $total = $order['total'];
            
            $suf = 'ий';
            if($pc>0)
            $suf = 'ия';
            if($pc>1)
            $suf = 'ии';
            if($pc>4)
            $suf = 'ий';
            
            $suf2 = 'ц';
            if($added_cou>0)
            $suf2 = 'ца';
            if($added_cou>1)
            $suf2 = 'цы';
            if($added_cou>4)
            $suf2 = 'ц';
            $res_mess[] = sprintf('Создан заказ %1$d позиц%3$s, %2$d едини%4$s, на сумму %5$01.2f %6$s',$pc,$added_cou,$suf,$suf2,$total,$currency_short);
            $this->clear_cart();
        }else{
            $res_mess[] = 'Заказ не создан.';
            if(is_wp_error($user)){
                foreach ( $user->get_error_messages() as $message ) {
                    $res_mess[] = $message;
                }
            }
            if(is_wp_error($order)){
                foreach ( $order->get_error_messages() as $message ) {
                    $res_mess[] = $message;
                }
            }
        }
        foreach ( $res_mess as $message ) {
            add_log($message);
        }
        
        if($is_ajax){
                    $out=array();
                    echo json_encode($out);
            //        echo "{ts:'hi'}";
                    exit;
        }else{
            if($res){
                wp_redirect('/my-account');
//                switch ($go) {
//                    case 'my-account':
//                        wp_redirect('/my-account');
//                        break;
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
                exit();
            }
            if(!$res){
//                add_log($res_mess);
//                wp_redirect('/cart');
//                exit();
            }
        }
    }
    
    public function prc_update_cart(){
        global $wpdb,$is_ajax;
        $is_ajax = false;
        $res=false;
        $res_mess='';
        
//add_log($_POST);
        $pid = filter_input(INPUT_POST, 'pid',FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);
//add_log($pid);
        if($pid===false || $pid===null|| $pid==='')$pid=[];
        $count = filter_input(INPUT_POST, 'cou',FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);
//add_log($count);
        if($count===false || $count===null || $count==='')$count=[];
//add_log($pid);
//add_log($count);
        $go = filter_input(INPUT_POST, 'go',FILTER_SANITIZE_STRING);
        if($go===false || $go===null|| $go==='')$go='cart';
        
//        $ds = new DShop();
        $added_cou = $this->replace_cart($pid,$count);
        $res = !is_wp_error($added_cou);
        if(count($_SESSION['ds_cart']) == 0)$res==0;
        
        $res_mess=[];
        if(!is_wp_error($added_cou)){
            $pc = count($_SESSION['ds_cart']);
            $suf = 'ий';
            if($pc>0)
            $suf = 'ия';
            if($pc>1)
            $suf = 'ии';
            if($pc>4)
            $suf = 'ий';
            
            $suf2 = 'ц';
            if($added_cou>0)
            $suf2 = 'ца';
            if($added_cou>1)
            $suf2 = 'цы';
            if($added_cou>4)
            $suf2 = 'ц';
            $res_mess[] = sprintf('В корзине %1$d позиц%3$s, %2$d едини%4$s',$pc,$added_cou,$suf,$suf2);
        }else{
            $res_mess[] = 'Сбой обновления корзины.';
            foreach ( $added_cou->get_error_messages() as $message ) {
//                printf( '<p>%s</p>', $message );
                $res_mess[] = $message;
            }
        }
        foreach ( $res_mess as $message ) {
            add_log($message);
        }
        
        if($is_ajax){
                    $out=array();
                    echo json_encode($out);
            //        echo "{ts:'hi'}";
                    exit;
        }else{
            if($res){
                switch ($go) {
                    case 'checkout':
                        wp_redirect('/checkout');
                        break;
                    case 'cart':
                    default:
                        wp_redirect('/cart');
                        break;
                }
    //            wp_redirect($_SERVER['HTTP_REFERER'].'?active=1');
//                if(count($_SESSION['ds_cart'])==0)
//                    wp_redirect('/cart');
//                else wp_redirect('/checkout');
    //            wp_redirect(esc_url(home_url('/кабинет/')));
                exit();
            }
//            if(!$res)
//                add_log($res_mess);
            if(!$res){
                wp_redirect('/cart');
                exit();
            }
        }
    }
    
    public function prc_add_prod_to_cart(){
        global $wpdb,$is_ajax;
        $is_ajax = false;
        $res=false;
        $res_mess='';
        
        $pid = filter_input(INPUT_POST, 'pid', FILTER_SANITIZE_NUMBER_INT);
        if($pid===false || $pid===null|| $pid==='')$pid=0;
        $count = filter_input(INPUT_POST, 'cou', FILTER_SANITIZE_NUMBER_INT);
        if($count===false || $count===null || $count==='')$count=0;
        
//        $ds = new DShop();
        $added_cou = $this->add_to_cart($pid,$count);
        $res = !is_wp_error($added_cou);
        
        $res_mess=[];
        if(!is_wp_error($added_cou)){
            $res_mess[] = sprintf('В корзину добавлено %1$d позиций',$added_cou);
        }else{
            $res_mess[] = 'Сбой добавления в корзину.';
            foreach ( $added_cou->get_error_messages() as $message ) {
//                printf( '<p>%s</p>', $message );
                $res_mess[] = $message;
            }
        }
        foreach ( $res_mess as $message ) {
            add_log($message);
        }
        
        if($is_ajax){
                    $out=array();
                    echo json_encode($out);
            //        echo "{ts:'hi'}";
                    exit;
        }else{
            if($res){
    //            wp_redirect($_SERVER['HTTP_REFERER'].'?active=1');
    //            wp_redirect('/checkout');
                wp_redirect('/cart');
    //            wp_redirect(esc_url(home_url('/кабинет/')));
                exit();
            }
//            if(!$res)
//                add_log($res_mess);
        }
    }
    
    public function prc_add_bad_owner(){
        global $wpdb,$is_ajax;
        $is_ajax = false;
        $res=false;
        $res_mess='';

    //    $max_try_count=CCAB_MAX_TRY_COUNT;
    //    $max_try_time=CCAB_MAX_TRY_TIME;
        if(is_user_logged_in() && !$this->is_user_role( 'subscriber' ) ){
            $user_ = wp_get_current_user();
            $form_type = 'resume_like';
            $fields=[];
    //        $fields[]='form-type';
    $fields[]='adress';
    $fields[]='namebc';
    $fields[]='fio';
    $fields[]='position';
    $fields[]='phone';
    $fields[]='email';
    $fields[]='text';
    $fields[]='state';

            $labels=[];
    //        $labels[]='тип формы';
            $labels[]='id резюме';

            $types=[];
            $filter=[];
            $display=[];
            $def=[];
            $wc=[];
            $err=[];

    //        $types[]=4;
            $types[]=4;

    $filter[]=0;
    $filter[]=0;
    $filter[]=0;
    $filter[]=0;

    $filter[]=2;
    $filter[]=1;
    $filter[]=0;
    $filter[]=2;

    //        $display[]='tpl_i_h';
            $display[]='tpl_i_h';

            $opt = array('default' => NULL);
            $filter_types=[];
            $filter_types[]=array('filter'=>FILTER_SANITIZE_STRING, 'options' => $opt);
            $filter_types[]=array('filter'=>FILTER_VALIDATE_EMAIL, 'options' => $opt);
    $filter_types[]=array('filter'=>FILTER_SANITIZE_NUMBER_INT, 'options' => $opt);
    //$filter_types[]=array('filter'=>FILTER_VALIDATE_INT, 'options' => $opt);

            $ipnput_types=[];
            $ipnput_types[]='text';
            $ipnput_types[]='email';
            $ipnput_types[]='number';
            $ipnput_types[]='file';
            $ipnput_types[]='hidden';

            $inputs_=[];
            //$inputs_['form-type'] = $filter_types[0];
            foreach($fields as $num=>$f){
                $inputs_[$f] = $filter_types[$filter[$num]];
            }

            $inputs = filter_input_array(INPUT_POST,$inputs_);



            $fields=[];
    $fields['adress']='Адрес';
//    $fields['namebc']='Наименование БЦ';
    $fields['fio']='ФИО представителя';
//    $fields['position']='Должность представителя';
    $fields['phone']='Телефон';
    $fields['email']='E-mail';
    $fields['text']='Описание ситуации';
    $fields['state']='Статус решения';
    //        $fields['form-type']='тип формы';
    $labels[]='Адрес';
    $labels[]='Наименование БЦ';
    $labels[]='ФИО представителя';
    $labels[]='Должность представителя';
    $labels[]='Телефон';
    $labels[]='E-mail';
    $labels[]='Описание ситуации';
    $labels[]='Статус решения';
            $noerr_mark=true;
            $nodara=array();
            /* Валидация полученных данных */
            if(10){ // без валидации
                foreach($inputs as $field=>$val){
                    if($val === false || $val === null || strlen($val) == 0){
                        if(isset($fields[$field])){
                            $noerr_mark=false;
                            $nodara[]=$fields[$field];
            //                        add_log($field);
                        }
                    }
                }
            }

//                    if(!$noerr_mark){
//                        $res_mess="Получены не все необходимые данные.\n ";
//                        $res_mess.="Не хватает: <br/>".implode(', <br/>',$nodara);
//    //                    add_log($res_mess);
//                    }
                    if(!$noerr_mark){
                        $res_mess="Получены не все необходимые данные.\n ";
    //                    $res_mess.="не хватает: ".implode(', ',$nodara);
                        $res_mess.="Не хватает: <br/>".implode(', <br/>',$nodara);
                    }else
                    { // если прошёл предварительню проверку
                    /* ====================================== */

    //https://wp-kama.ru/function/wp_insert_post
    if(10){
    //$post = array(
    //	'ID'             => <post id>,                                                     // Вы обновляете существующий пост?
    //	'menu_order'     => <order>,                                                       // Если запись "постоянная страница", установите её порядок в меню.
    //	'comment_status' => 'closed' | 'open',                                             // 'closed' означает, что комментарии закрыты.
    //	'ping_status'    => 'closed' | 'open',                                             // 'closed' означает, что пинги и уведомления выключены.
    //	'pinged'         => ?,                                                             //?
    //	'post_author'    => <user ID>,                                                     // ID автора записи
    //	'post_content'   => <the text of the post>,                                        // Полный текст записи.
    //	'post_date'      => Y-m-d H:i:s,                                                   // Время, когда запись была создана.
    //	'post_date_gmt'  => Y-m-d H:i:s,                                                   // Время, когда запись была создана в GMT.
    //	'post_excerpt'   => <an excerpt>,                                                  // Цитата (пояснительный текст) записи.
    //	'post_name'      => <the name>,                                                    // Альтернативное название записи (slug) будет использовано в УРЛе.
    //	'post_parent'    => <post ID>,                                                     // ID родительской записи, если нужно.
    //	'post_password'  => ?,                                                             // Пароль для просмотра записи.
    //	'post_status'    => 'draft' | 'publish' | 'pending'| 'future' | 'private',         // Статус создаваемой записи.
    //	'post_title'     => <the title>,                                                   // Заголовок (название) записи.
    //	'post_type'      => 'post' | 'page' | 'link' | 'nav_menu_item' | custom post type, // Тип записи.
    //	'post_category'  => array( <category id>, <...> ),                                   // Категория к которой относится пост.
    //	'tags_input'     => array( <tag>, <tag>, <...> ),                                         // Метки поста (указываем ярлыки, имена или ID).
    //	'tax_input'      => array( 'taxonomy_name' => array( 'term', 'term2', 'term3' ) ), // К каким таксам прикрепить запись. Аналог 'post_category', только для для новых такс.
    //	'to_ping'        => ?,                                                             //?
    //	'meta_input'     => array( 'meta_key'=>'meta_value' ),                             // добавит указанные мета поля. По умолчанию: ''. с версии 4.4.
    //);

        if(10){// добавление записи
        // Создаем массив данных новой записи
            $title = $inputs['adress'].' / '.$inputs['fio'];
            if(strlen($inputs['position'])>0)$title .= ' / '.$inputs['position'].'';
            $post_data = array(
            //	'post_title'    => wp_strip_all_tags( $_POST['post_title'] ),
//                'post_content'  => wp_strip_all_tags( $_POST['post_content'] ),
                'post_title'    => wp_strip_all_tags( $title ),
                'post_content'  => $inputs['text'],
                'post_type'   => 'bad_owner',
                'post_status'   => 'draft',
                'post_author'   => $user_->ID,
            //	'post_category' => array( 8,39 )
            );
            $fields=[];
//            $fields['']='adress';
//            $fields['']='namebc';
//            $fields['']='fio';
//            $fields['']='position';
//            $fields['']='phone';
//            $fields['']='email';
//            $fields['']='text';
//            $fields['']='state';
    $fields[]='adress';
    $fields[]='namebc';
    $fields[]='fio';
    $fields[]='position';
    $fields[]='phone';
    $fields[]='email';
//    $fields[]='text';
    $fields[]='state';
//    add_log($inputs);

    $nm = 'ln_';
            // Вставляем запись в базу данных
            $post_id = wp_insert_post( $post_data );
            foreach($fields as $key){
//            foreach($fields as $meta_key=>$f){
//                $inputs_[$f] = $inputs[$filter[$num]];
                $meta_key = $nm.$key;
                add_post_meta( $post_id, $meta_key, $inputs[$key], 1 );
            }
//                foreach($inputs as $field=>$val){
//                    if($val === false || $val === null || strlen($val) == 0){
//                        $noerr_mark=false;
//                        $nodara[]=$fields[$field];
//        //                        add_log($field);
//                    }
//                }
//            $mres =1;
            if($post_id){
                $res_mess.=' Запись о собственнике отправлена на подтверждение администратору.';
                $res=true;
            }else{
//                $res_mess.=' Сбой отправки сообщения.';
            }
            add_log($res_mess);
        }


        if(0){// добавление user_meta
    //    $likes = get_user_meta( $user_->ID, 'likes',1);
    //    if(!$likes)$likes='';
    //    $likes = explode(',',$likes);
    //    if(in_array($inputs['res_id'],$likes)){
    //        $key = array_search($inputs['res_id'],$likes);
    //        unset($likes[$key]);
    //        $res_mess.=' Удалён из избранного.';
    //    }else{
    //        $likes[] = $inputs['res_id'];
    //        $res_mess.=' Добавлен в избранное.';
    //    }
    //    $likes = implode(',',$likes);
    //    add_user_meta ( $user_->ID, 'likes', $likes, 1 );
    //    update_user_meta( $user_->ID, 'likes', $likes );

    //    set_user_meta('likes',$likes);
        }
        /** /
        if(10){// добавление файла
    //    if($post_id){
            $fields=[];
            $fields['userpic']='Фотография';
            $fields['file']='Анкета';

            // все ок! Продолжаем.
            // Эти файлы должны быть подключены в лицевой части (фронт-энде).
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            require_once( ABSPATH . 'wp-admin/includes/media.php' );

            // Позволим WordPress перехватить загрузку.
            // не забываем указать атрибут name поля input - 'my_image_upload'
            $a_id = media_handle_upload( 'file', $post_id=0 );

            if ( is_wp_error( $a_id ) ) {
                $m = "Ошибка загрузки.";
                add_log($m);
            } else {
                $m = "Файл добавлен.";
    //            add_post_meta( $post_id, 'file', $attachment_id, 1 );
                add_user_meta ( $user_->ID, 'files', $a_id, false );
                $files = get_user_meta ( $user_->ID, 'files', false);
                add_log($m);
    //            add_log($files);
                $fin= get_user_meta($user_->ID, 'org_fcou_load', true);
                $fin++;
                $fin=count($files);
                if(!add_user_meta ( $user_->ID, 'org_fcou_load', $fin, true ))
                    update_user_meta($user_->ID, 'org_fcou_load', $fin);
    //            update_user_meta( $user_->ID, 'likes', $likes );
                /**/
                if(0){ // отправка сообщения на почту

    //                $file = get_attached_file($a_id);
                    $attachments=[];
    //                $attachments[]=$file;
                    $name='register_ok';
                    $name='org_send_file_info';
                    $name='org_send_request';
                    $atr=array();
                    $atr['__user_name_f__']=$user_->first_name;
                    $atr['__user_name_s__']=get_user_meta ( $user_->ID, 'second_name', 1);
                    $atr['__user_name_l__']=$user_->last_name;
                    $atr['__user_email__']=$user_->user_email;
                    $atr['__user_phone__']=get_user_meta ( $user_->ID, 'phone', 1);
                    $atr['__org_name__']=get_user_meta ( $user_->ID, 'org_name', 1);
                    $atr['__org_phone__']=get_user_meta ( $user_->ID, 'org_phone', 1);
    //                $atr['__user_url_admin__']=get_edit_user_link ( $user_->ID);
    //                $atr['__file_url__']=the_attachment_link( $a_id, false, false, true);
    //                $atr['__file_url__']=get_attachment_link( $a_id );
                    $link = add_query_arg( 'user_id', $user_->ID, self_admin_url( 'user-edit.php' ) );
                    $atr['__user_url_admin__']=$link;
    //                                $atr['__user_phone__']=$user['phone'];


                    $atr['__f_name_l__']=$inputs['lname'];
                    $atr['__f_name_f__']=$inputs['fname'];
                    $atr['__f_name_s__']=$inputs['sname'];
                    $atr['__f_bdate__']=$inputs['bdate'];
                    $atr['__f_work_place__']=$inputs['work_place'];

                    if(10){ // test 1
                        $to=array();
    //                    $to[]='info@landy-land.ru';//
    //                    $to[]='home_work_mail@mail.ru';
                        $to[]=esc_attr( get_option('email_get_requests') );//bz11
                        $mres=ccab_get_mail($name,$atr,$to,$attachments);
                        if($mres){
                            $res_mess.=' Сообщение отправлено.';
                            $res=true;
                        }else{
                            $res_mess.=' Сбой отправки сообщения.';
                        }
                        add_log($res_mess);
                    }
                }

    //        }
    //    }
    }
    //                    $res_mess.=' Вакансия добавлена.';
    //                    add_log($res_mess);
    //                    $res=true;
                    }

                    $out=array();
                    $out['data']=array();
    //                $out['data']['code']=$_SESSION['code_sms'];
                    $out['data']['mess']=$res_mess;
                    $out['data']['res']=$res?'ok':'error';
                    $out['res']=$res;
                    $err_out = ob_get_clean();
                    if(strlen($err_out)>0)
                    $out['err_out'] = $err_out;
    //                $out['$_']=$_SESSION;

    //    _add_log($out,'json_encode($out);','код регистрации'); // логи в базу
    //    _add_log(' ---------------------------------- '
    //            . '<br/> ---------------------------------- '
    //            ,'end','код регистрации'); // логи в базу
            if($is_ajax){
                        echo json_encode($out);
                //        echo "{ts:'hi'}";
                        exit;
            }else{
                if($res){
                    wp_redirect($_SERVER['HTTP_REFERER'].'?active=1');
        //            wp_redirect(esc_url(home_url('/кабинет/')));
                    exit();
                }
                if(!$res)
                    add_log($res_mess);
        //        add_log($out);
        //        if($res){
        //            wp_redirect(esc_url(home_url('/кабинет/')));
        //            exit();
        //        }
            }
        }
    }
    
    public function prc_add_bad_agent(){
        global $wpdb,$is_ajax;
        $is_ajax = false;
        $res=false;
        $res_mess='';

    //    $max_try_count=CCAB_MAX_TRY_COUNT;
    //    $max_try_time=CCAB_MAX_TRY_TIME;
        if(is_user_logged_in() && !$this->is_user_role( 'subscriber' ) ){
            $user_ = wp_get_current_user();
            $form_type = 'resume_like';
            $fields=[];
    //        $fields[]='form-type';
            $fields[]='fio';
            $fields[]='position';
            $fields[]='born';
            $fields[]='phone';
            $fields[]='text';
            $fields[]='email';
            $fields[]='state';

            $labels=[];
    //        $labels[]='тип формы';
            $labels[]='id резюме';

            $types=[];
            $filter=[];
            $display=[];
            $def=[];
            $wc=[];
            $err=[];

    //        $types[]=4;
            $types[]=4;

$filter[]=0;
$filter[]=0;
$filter[]=0;

$filter[]=2;
$filter[]=0;
$filter[]=1;
$filter[]=2;

    //        $display[]='tpl_i_h';
            $display[]='tpl_i_h';

            $opt = array('default' => NULL);
            $filter_types=[];
            $filter_types[]=array('filter'=>FILTER_SANITIZE_STRING, 'options' => $opt);
            $filter_types[]=array('filter'=>FILTER_VALIDATE_EMAIL, 'options' => $opt);
    $filter_types[]=array('filter'=>FILTER_SANITIZE_NUMBER_INT, 'options' => $opt);
    //$filter_types[]=array('filter'=>FILTER_VALIDATE_INT, 'options' => $opt);

            $ipnput_types=[];
            $ipnput_types[]='text';
            $ipnput_types[]='email';
            $ipnput_types[]='number';
            $ipnput_types[]='file';
            $ipnput_types[]='hidden';

            $inputs_=[];
            //$inputs_['form-type'] = $filter_types[0];
            foreach($fields as $num=>$f){
                $inputs_[$f] = $filter_types[$filter[$num]];
            }

            $inputs = filter_input_array(INPUT_POST,$inputs_);



            $fields=[];
        $fields['fio']='ФИО';
        $fields['position']='Должность в компании';
        $fields['born']=' Дата рождения';
        $fields['phone']='Телефон';
        $fields['text']='Описание ситуации';
        $fields['email']='E-mail';
        $fields['state']='Статус решения';
//    $fields['adress']='Адрес';
//    $fields['namebc']='Наименование БЦ';
//    $fields['fio']='ФИО представителя';
//    $fields['position']='Должность представителя';
//    $fields['phone']='Телефон';
//    $fields['email']='E-mail';
//    $fields['text']='Описание ситуации';
//    $fields['state']='Статус решения';
    //        $fields['form-type']='тип формы';
        $labels[]='ФИО';
        $labels[]='Должность в компании';
        $labels[]='Дата рождения';
        $labels[]='Телефон';
        $labels[]='Описание ситуации';
        $labels[]='E-mail';
        $labels[]='Статус решения';
            $noerr_mark=true;
            $nodara=array();
            /* Валидация полученных данных */
            if(10){ // без валидации
                foreach($inputs as $field=>$val){
                    if($val === false || $val === null || strlen($val) == 0){
                        if(isset($fields[$field])){
                            $noerr_mark=false;
                            $nodara[]=$fields[$field];
            //                        add_log($field);
                        }
                    }
                }
            }

                    if(!$noerr_mark){
                        $res_mess="Получены не все необходимые данные.\n ";
                        $res_mess.="Не хватает: <br/>".implode(', <br/>',$nodara);
    //                    add_log($res_mess);
                    }
                    if(!$noerr_mark){
                        $res_mess="Получены не все необходимые данные.\n ";
    //                    $res_mess.="не хватает: ".implode(', ',$nodara);
                        $res_mess.="Не хватает: <br/>".implode(', <br/>',$nodara);
                    }else
                    { // если прошёл предварительню проверку
                    /* ====================================== */

    //https://wp-kama.ru/function/wp_insert_post
    if(10){
    //$post = array(
    //	'ID'             => <post id>,                                                     // Вы обновляете существующий пост?
    //	'menu_order'     => <order>,                                                       // Если запись "постоянная страница", установите её порядок в меню.
    //	'comment_status' => 'closed' | 'open',                                             // 'closed' означает, что комментарии закрыты.
    //	'ping_status'    => 'closed' | 'open',                                             // 'closed' означает, что пинги и уведомления выключены.
    //	'pinged'         => ?,                                                             //?
    //	'post_author'    => <user ID>,                                                     // ID автора записи
    //	'post_content'   => <the text of the post>,                                        // Полный текст записи.
    //	'post_date'      => Y-m-d H:i:s,                                                   // Время, когда запись была создана.
    //	'post_date_gmt'  => Y-m-d H:i:s,                                                   // Время, когда запись была создана в GMT.
    //	'post_excerpt'   => <an excerpt>,                                                  // Цитата (пояснительный текст) записи.
    //	'post_name'      => <the name>,                                                    // Альтернативное название записи (slug) будет использовано в УРЛе.
    //	'post_parent'    => <post ID>,                                                     // ID родительской записи, если нужно.
    //	'post_password'  => ?,                                                             // Пароль для просмотра записи.
    //	'post_status'    => 'draft' | 'publish' | 'pending'| 'future' | 'private',         // Статус создаваемой записи.
    //	'post_title'     => <the title>,                                                   // Заголовок (название) записи.
    //	'post_type'      => 'post' | 'page' | 'link' | 'nav_menu_item' | custom post type, // Тип записи.
    //	'post_category'  => array( <category id>, <...> ),                                   // Категория к которой относится пост.
    //	'tags_input'     => array( <tag>, <tag>, <...> ),                                         // Метки поста (указываем ярлыки, имена или ID).
    //	'tax_input'      => array( 'taxonomy_name' => array( 'term', 'term2', 'term3' ) ), // К каким таксам прикрепить запись. Аналог 'post_category', только для для новых такс.
    //	'to_ping'        => ?,                                                             //?
    //	'meta_input'     => array( 'meta_key'=>'meta_value' ),                             // добавит указанные мета поля. По умолчанию: ''. с версии 4.4.
    //);

        if(10){// добавление записи
        // Создаем массив данных новой записи
            $title = $inputs['fio'];
            if(strlen($inputs['position'])>0)$title .= ' / '.$inputs['position'].'';
            $post_data = array(
            //	'post_title'    => wp_strip_all_tags( $_POST['post_title'] ),
//                'post_content'  => wp_strip_all_tags( $_POST['post_content'] ),
                'post_title'    => wp_strip_all_tags( $title ),
                'post_content'  => $inputs['text'],
                'post_type'   => 'bad_agent',
                'post_status'   => 'draft',
                'post_author'   => $user_->ID,
            //	'post_category' => array( 8,39 )
            );
            $fields=[];
        $fields['fio']='ФИО';
        $fields['position']='Должность в компании';
        $fields['born']=' Дата рождения';
        $fields['phone']='Телефон';
//        Описание ситуации: (поле с изменяющимся размером)*
//        кто добавил (выбор из списка зарегистрированных участников)
        
        $fields['email']='E-mail';
        $fields['state']='Статус решения';
            $fields=[];
//            $fields['']='adress';
//            $fields['']='namebc';
//            $fields['']='fio';
//            $fields['']='position';
//            $fields['']='phone';
//            $fields['']='email';
//            $fields['']='text';
//            $fields['']='state';
    $fields[]='fio';
    $fields[]='position';
    $fields[]='born';
    $fields[]='phone';
//    $fields[]='text';
    $fields[]='email';
    $fields[]='state';
//    add_log($inputs);

    $nm = 'ln_';
            // Вставляем запись в базу данных
            $post_id = wp_insert_post( $post_data );
            foreach($fields as $key){
//            foreach($fields as $meta_key=>$f){
//                $inputs_[$f] = $inputs[$filter[$num]];
                $meta_key = $nm.$key;
                add_post_meta( $post_id, $meta_key, $inputs[$key], 1 );
            }
//                foreach($inputs as $field=>$val){
//                    if($val === false || $val === null || strlen($val) == 0){
//                        $noerr_mark=false;
//                        $nodara[]=$fields[$field];
//        //                        add_log($field);
//                    }
//                }
//            $mres =1;
            if($post_id){
                $res_mess.=' Запись об агенте отправлена на подтверждение администратору.';
                $res=true;
            }else{
//                $res_mess.=' Сбой отправки сообщения.';
            }
            add_log($res_mess);
        }


        if(0){// добавление user_meta
    //    $likes = get_user_meta( $user_->ID, 'likes',1);
    //    if(!$likes)$likes='';
    //    $likes = explode(',',$likes);
    //    if(in_array($inputs['res_id'],$likes)){
    //        $key = array_search($inputs['res_id'],$likes);
    //        unset($likes[$key]);
    //        $res_mess.=' Удалён из избранного.';
    //    }else{
    //        $likes[] = $inputs['res_id'];
    //        $res_mess.=' Добавлен в избранное.';
    //    }
    //    $likes = implode(',',$likes);
    //    add_user_meta ( $user_->ID, 'likes', $likes, 1 );
    //    update_user_meta( $user_->ID, 'likes', $likes );

    //    set_user_meta('likes',$likes);
        }
        /** /
        if(10){// добавление файла
    //    if($post_id){
            $fields=[];
            $fields['userpic']='Фотография';
            $fields['file']='Анкета';

            // все ок! Продолжаем.
            // Эти файлы должны быть подключены в лицевой части (фронт-энде).
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            require_once( ABSPATH . 'wp-admin/includes/media.php' );

            // Позволим WordPress перехватить загрузку.
            // не забываем указать атрибут name поля input - 'my_image_upload'
            $a_id = media_handle_upload( 'file', $post_id=0 );

            if ( is_wp_error( $a_id ) ) {
                $m = "Ошибка загрузки.";
                add_log($m);
            } else {
                $m = "Файл добавлен.";
    //            add_post_meta( $post_id, 'file', $attachment_id, 1 );
                add_user_meta ( $user_->ID, 'files', $a_id, false );
                $files = get_user_meta ( $user_->ID, 'files', false);
                add_log($m);
    //            add_log($files);
                $fin= get_user_meta($user_->ID, 'org_fcou_load', true);
                $fin++;
                $fin=count($files);
                if(!add_user_meta ( $user_->ID, 'org_fcou_load', $fin, true ))
                    update_user_meta($user_->ID, 'org_fcou_load', $fin);
    //            update_user_meta( $user_->ID, 'likes', $likes );
                /**/
                if(0){ // отправка сообщения на почту

    //                $file = get_attached_file($a_id);
                    $attachments=[];
    //                $attachments[]=$file;
                    $name='register_ok';
                    $name='org_send_file_info';
                    $name='org_send_request';
                    $atr=array();
                    $atr['__user_name_f__']=$user_->first_name;
                    $atr['__user_name_s__']=get_user_meta ( $user_->ID, 'second_name', 1);
                    $atr['__user_name_l__']=$user_->last_name;
                    $atr['__user_email__']=$user_->user_email;
                    $atr['__user_phone__']=get_user_meta ( $user_->ID, 'phone', 1);
                    $atr['__org_name__']=get_user_meta ( $user_->ID, 'org_name', 1);
                    $atr['__org_phone__']=get_user_meta ( $user_->ID, 'org_phone', 1);
    //                $atr['__user_url_admin__']=get_edit_user_link ( $user_->ID);
    //                $atr['__file_url__']=the_attachment_link( $a_id, false, false, true);
    //                $atr['__file_url__']=get_attachment_link( $a_id );
                    $link = add_query_arg( 'user_id', $user_->ID, self_admin_url( 'user-edit.php' ) );
                    $atr['__user_url_admin__']=$link;
    //                                $atr['__user_phone__']=$user['phone'];


                    $atr['__f_name_l__']=$inputs['lname'];
                    $atr['__f_name_f__']=$inputs['fname'];
                    $atr['__f_name_s__']=$inputs['sname'];
                    $atr['__f_bdate__']=$inputs['bdate'];
                    $atr['__f_work_place__']=$inputs['work_place'];

                    if(10){ // test 1
                        $to=array();
    //                    $to[]='info@landy-land.ru';//
    //                    $to[]='home_work_mail@mail.ru';
                        $to[]=esc_attr( get_option('email_get_requests') );//bz11
                        $mres=ccab_get_mail($name,$atr,$to,$attachments);
                        if($mres){
                            $res_mess.=' Сообщение отправлено.';
                            $res=true;
                        }else{
                            $res_mess.=' Сбой отправки сообщения.';
                        }
                        add_log($res_mess);
                    }
                }

    //        }
    //    }
    }
    //                    $res_mess.=' Вакансия добавлена.';
    //                    add_log($res_mess);
    //                    $res=true;
                    }

                    $out=array();
                    $out['data']=array();
    //                $out['data']['code']=$_SESSION['code_sms'];
                    $out['data']['mess']=$res_mess;
                    $out['data']['res']=$res?'ok':'error';
                    $out['res']=$res;
                    $err_out = ob_get_clean();
                    if(strlen($err_out)>0)
                    $out['err_out'] = $err_out;
    //                $out['$_']=$_SESSION;

    //    _add_log($out,'json_encode($out);','код регистрации'); // логи в базу
    //    _add_log(' ---------------------------------- '
    //            . '<br/> ---------------------------------- '
    //            ,'end','код регистрации'); // логи в базу
            if($is_ajax){
                        echo json_encode($out);
                //        echo "{ts:'hi'}";
                        exit;
            }else{
                if($res){
                    wp_redirect($_SERVER['HTTP_REFERER'].'?active=2');
        //            wp_redirect(esc_url(home_url('/кабинет/')));
                    exit();
                }
                if(!$res)
                    add_log($res_mess);
        //        add_log($out);
        //        if($res){
        //            wp_redirect(esc_url(home_url('/кабинет/')));
        //            exit();
        //        }
            }
        }
    }
    
    public function prc_user_register(){
//        add_log($_SERVER);
        global $wpdb,$is_ajax,$prc_error;
    //    $is_ajax = false;
        $prc_error = new WP_Error();
    //    $prc_error->add( 'username_exists', __( '<strong>ERROR</strong>: This username is already registered. Please choose another one.' ) );
    //	if ( $prc_error->get_error_code() )
    //		return $prc_error;
    //    if ( !is_wp_error($prc_error) ) {
    //    }else{
    //        //Something's wrong
    //        $return['result'] = false;
    //        $return['error'] = $prc_error->get_error_message();
    //    }

        $res=false;
        $res_mess='';
        @session_start();
        ob_start();
        /*
        _add_log('=================================='
    //            . '<br/>=================================='
                ,'start','до регистрации'); // логи в базу
        _add_log($_SERVER["HTTP_USER_AGENT"],'HTTP_USER_AGENT','до регистрации');
         * 
         */
    //                $uphone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);

                    $uphone='';
                    $reg_arr = array();
                    $uphone_arr = array();
                    $_uphone_arr = filter_input(INPUT_POST, 'phone',
                            FILTER_DEFAULT , FILTER_REQUIRE_ARRAY);

                    $inputs_ = array();
                    $inputs_['password'] = array('filter'=>FILTER_VALIDATE_REGEXP,
                        'options' => array('regexp' => '/^(?=\S*\d)(?=\S*[a-zA-Zа-яА-ЯЁёЄєЇї])\S{8,}$/'));
                    $inputs_['repassword'] = array('filter'=>FILTER_VALIDATE_REGEXP,
                        'options' => array('regexp' => '/^(?=\S*\d)(?=\S*[a-zA-Zа-яА-ЯЁёЄєЇї])\S{8,}$/'));
                    $inputs_['email'] = array('filter'=>FILTER_VALIDATE_EMAIL);
                    $inputs_['name'] = array('filter'=>FILTER_VALIDATE_REGEXP,
                        'options'   => array('regexp' => '/^[a-zA-Zа-яА-ЯЁёЄєЇї\-]{3,20}$/u'));
                    $inputs_['surname'] = array('filter'=>FILTER_VALIDATE_REGEXP, 
                        'options'   => array('regexp' => '/^[a-zA-Zа-яА-ЯЁёЄєЇї\-]{3,20}$/u'));

                    $inputs_ = array();

    //    [form-type] => user_register
    //    [org_name] => 
    //    [last_name] => 
    //    [first_name] => 
    //    [second_name] => 
    //    [org_reqv] => 
    //    [org_phone] => 
    //    [org_email] => 
    //    [phone] => 

                    $opt = array('options' => array('default' => NULL));
                    $opt = array('default' => NULL);
                    $inputs_['org_name'] = array('filter'=>FILTER_SANITIZE_STRING, 'options' => $opt);
                    $inputs_['last_name'] = array('filter'=>FILTER_SANITIZE_STRING, 'options' => $opt);
                    $inputs_['first_name'] = array('filter'=>FILTER_SANITIZE_STRING, 'options' => $opt);
                    $inputs_['second_name'] = array('filter'=>FILTER_SANITIZE_STRING, 'options' => $opt);

//                    $inputs_['org_reqv'] = array('filter'=>FILTER_SANITIZE_STRING, 'options' => $opt);

//    $inputs_['org_adres'] = array('filter'=>FILTER_SANITIZE_STRING, 'options' => $opt);
//    $inputs_['org_inn'] = array('filter'=>FILTER_SANITIZE_STRING, 'options' => $opt);
//    $inputs_['org_kpp'] = array('filter'=>FILTER_SANITIZE_STRING, 'options' => $opt);
    $inputs_['position'] = array('filter'=>FILTER_SANITIZE_STRING, 'options' => $opt);
    $inputs_['url'] = array('filter'=>FILTER_SANITIZE_STRING, 'options' => $opt);
    $inputs_['pass'] = array('filter'=>FILTER_SANITIZE_STRING, 'options' => $opt);
    $inputs_['repass'] = array('filter'=>FILTER_SANITIZE_STRING, 'options' => $opt);
    $inputs_['pass'] = array('filter'=>FILTER_VALIDATE_REGEXP,
        'options' => array('regexp' => '/^(?=\S*\d)(?=\S*[a-zA-Zа-яА-ЯЁёЄєЇї])\S{8,}$/'));
    $inputs_['repass'] = array('filter'=>FILTER_VALIDATE_REGEXP,
        'options' => array('regexp' => '/^(?=\S*\d)(?=\S*[a-zA-Zа-яА-ЯЁёЄєЇї])\S{8,}$/'));

//                    $inputs_['org_phone'] = array('filter'=>FILTER_SANITIZE_STRING, 'options' => $opt);
                    $inputs_['org_email'] = array('filter'=>FILTER_VALIDATE_EMAIL, 'options' => $opt);
                    $inputs_['phone'] = array('filter'=>FILTER_SANITIZE_NUMBER_INT, 'options' => $opt);

    //                $inputs_['terms'] = array('filter'=>FILTER_SANITIZE_STRING);

    //                $inputs_ = array();
    //                $inputs_['fields'] = array('filter'=>FILTER_REQUIRE_ARRAY);
    //                $inputs_['fields'] = array('filter'=>FILTER_FORCE_ARRAY);

                    // null - no data
                    // false - error data
                    $inputs = filter_input_array(INPUT_POST,$inputs_);
    //                echo '<pre>$_POST '. print_r($_POST,1).'</pre>';
    //                $inputs=  array_shift($_POST);
//                    echo '<pre>$inputs '. print_r($inputs,1).'</pre>';
    //   add_log($inputs,'dump');
    //   add_log($_POST);

    //    _add_log($_COOKIE,'$_COOKIE','до регистрации'); // логи в базу
    //    $sess_test_new=time();
    //    _add_log($sess_test_new,'sess_test new','до регистрации'); // логи в базу
    //    setcookie('sess_test',$sess_test_new);
    //    _add_log($_uphone_arr,'$_uphone_arr 1','до регистрации'); // логи в базу
        //
                    // FILTER_SANITIZE_STRING
                    $uphone_arr = $_uphone_arr;
    //                $uphone=$uphone_arr['name'];
    //                $uphone=$uphone_arr['citizenship'];
    //                $uphone=$uphone_arr['phone'];
    //                $uphone=$uphone_arr['phone'];
                    $_SESSION['code_try']=0;

                    /* проверка наличия элементов */
                    $noerr_mark=true;
                    $nodara=array();

    //                if(isset($uphone_arr['name']))
    //                    $reg_arr['name']=$uphone_arr['name'];
    //                else{
    //                    $noerr_mark=false;
    //                    $nodara[]='name';
    //                }
    //                if(isset($uphone_arr['email']))
    //                    $reg_arr['email']=$uphone_arr['email'];
    //                else{
    //                    $noerr_mark=false;
    //                    $nodara[]='email';
    //                }
    //                if(isset($uphone_arr['year']))
    //                    $reg_arr['year']=$uphone_arr['year'];
    //                else{
    //                    $noerr_mark=false;
    //                    $nodara[]='year';
    //                }
    //                if(isset($uphone_arr['phone'])){
    //                    $reg_arr['phone']=$uphone_arr['phone'];
    //                    $uphone=$reg_arr['phone'];
    //                } else{
    //                    $noerr_mark=false;
    //                    $nodara[]='phone';
    //                }
                    $fields =[];
                $user['org_adres']= '';
                $user['inn']= '';
                $user['kpp']= '';
                $user['bik']= '';
                $user['rs']= '';
                $user['ks']= '';
        $fields['form-type'] = 'form-type';
        $fields['org_name'] = 'Название организации';
        $fields['last_name'] = 'Фамилия руководителя';
        $fields['first_name'] = 'Имя руководителя';
        $fields['second_name'] = 'Отчество руководителя';
        
        $fields['org_phone'] = 'Телефон организации';
        $fields['org_email'] = 'Почта';
        $fields['phone'] = 'Телефон';
        $fields['url'] = 'Сайт';
        $fields['position'] = 'Должность';
        $fields['pass'] = 'Пароль должен быть минимум 8 символов и включать буквы и цифры (a-z A-Z а-я А-Я 0-9)';
        $fields['repass'] = 'Пароль, повторить';
        
//        $fields['org_reqv'] = 'Реквизиты организации';
//        $fields['org_adres'] = 'Юридический адрес';
//        $fields['org_inn'] = 'ИНН';
//        $fields['org_kpp'] = 'КПП';
//        $fields['org_bik'] = 'БИК';
//        $fields['org_rs'] = 'Р/с';
//        $fields['org_ks'] = 'К/с';
//        $fields['org_phone'] = 'Телефон организации';
//        $fields['org_email'] = 'Почта организации';
//        $fields['phone'] = 'Личный номер телефона для связи';
//        $fields['terms'] = 'Соглашение';

        $label=[];
        $label[] = 'Название организации';
        $label[] = 'Фамилия руководителя';
        $label[] = 'Имя руководителя';
        $label[] = 'Отчество руководителя';
        
        $label[] = 'Телефон организации';
        $label[] = 'Почта';
        $label[] = 'Телефон';
        $label[] = 'Сайт';
        $label[] = 'Должность';
        $label[] = 'Пароль';
        $label[] = 'Пароль, повторить';
        
//        $label[] = 'Название организации';
//        $label[] = 'Фамилия руководителя';
//        $label[] = 'Имя руководителя';
//        $label[] = 'Отчество руководителя';
//        $label[] = 'Реквизиты организации';
//        $label[] = 'Юридический адрес';
//        $label[] = 'ИНН';
//        $label[] = 'КПП';
//        $label[] = 'БИК';
//        $label[] = 'Р/с';
//        $label[] = 'К/с';
//        $label[] = 'Телефон организации';
//        $label[] = 'Почта организации';
//        $label[] = 'Личный номер телефона для связи';
//        $label[] = 'Загружено файлов:';
//        $label[] = 'Получено файлов:';

                    /* Валидация полученных данных */
                    foreach($inputs as $field=>$val){
                        if($val === false || $val === null || strlen($val) == 0){
                            $noerr_mark=false;
                            $nodara[]=$fields[$field];
    //                        add_log($field);
                        }
                    }

                    if(!$noerr_mark){
                        $res_mess="Получены не все необходимые данные.\n ";
                        $res_mess.="Не хватает: <br/>".implode(', <br/>',$nodara);
    //                    add_log($res_mess);
                    }
    //   return;

    //    _add_log($reg_arr,'$reg_arr','до регистрации'); // логи в базу
    //    _add_log($nodara,'$nodara','до регистрации'); // логи в базу

                    /* / проверка наличия элементов */
                    $login=$inputs['org_email'];

        /*
    //                $uphone=$_SESSION['new_phone'];
        _add_log($reg_arr,'$reg_arr','код регистрации'); // логи в базу
        _add_log($nodara,'$nodara','код регистрации'); // логи в базу
        /**/
    //                $uphone=$_SESSION['new_phone'];
    //                $phone = preg_replace("/[^0-9]/", '', $uphone);
    //                $code = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    //                $code = preg_replace("/[^0-9]/", '', $code);

    //                $phone = preg_replace("/[^0-9]/", '', $uphone);
    //                $_SESSION['reg_arr']=$reg_arr;
//                    add_log($inputs);
        $res_mess='';
                    if(isset($inputs['pass'])&&isset($inputs['repass'])
                            && $inputs['pass']!==$inputs['repass']){
                   // add_log("Пароль и повтор пароля, не совпадают.\n ");
                        $noerr_mark=false;
                        if(strlen($res_mess)>0)$res_mess.="<br/> ";
                        $res_mess.="Пароль и повтор пароля, не совпадают.\n ";
                    }
                    if(!$noerr_mark){
                        if(count($nodara)>0){
                            if(strlen($res_mess)>0)$res_mess.="<br/> ";
                            $res_mess.="Получены не все необходимые данные.\n ";
        //                    $res_mess.="не хватает: ".implode(', ',$nodara);
                            $res_mess.="Не хватает: <br/>".implode(', <br/>',$nodara);
                        }
                    }else
    //                if(!ccab_user_phone_meta_validate($uphone)){
    //                    $res_mess="Пользователь с номером $uphone не найден";
    //                }else
                        //$uphone
                    if($this->user_login_validate($login)){
                        $res_mess="Пользователь с логином $login уже существует";
                    }else
    //                if(ccab_user_phone_meta_validate($uphone)){
    //                    $res_mess="Пользователь с номером '$uphone' уже существует";
    //                }else
    //                if(ccab_user_login_validate($phone)){
    //                    $res_mess="Пользователь с логином '$phone' уже существует";
    //                }else

                        /* проверить наличие всех данных */
    //                if(
    ////                    isset($_SESSION['code_sms'])
    ////                    && $_SESSION['code_sms'] == $code
    //                        1
    //                    )
                        { // если прошёл предварительню проверку
                        /* ====================================== */
    //                    $user=array();
    //                    $user['phone']=$_SESSION['new_phone'];
                        $user_ = wp_get_current_user();
                        if (empty($user_->ID)){// если не залоогинен

                            //зарегистрировать
                            $reg_res=false;
                            if(10){
                                $user=array();
    //                $reg_res = ccab_add_client();// перенесено в процессор форм
    //                            $email = $reg_arr['email'];
                                $login = $inputs['org_email'];
                                $email = $inputs['org_email'];

    //                            $year = $reg_arr['year'];

                                /*
                                $fio = array();
                                $fio = trim($reg_arr['name']);
                                $fio=strtr($fio,array(','=>' '));
                                $fio=strtr($fio,array('    '=>' '));
                                $fio=strtr($fio,array('   '=>' '));
                                $fio=strtr($fio,array('  '=>' '));
                                $fio = trim($fio);
    //                            $fio=strtr($fio,array(' '=>''));
    //                            $fio=strtr($fio,array(''=>''));
                                $fio = explode(' ',$fio);
                                $first_name='';
                                $last_name='';
                                $patronymic='';//patronymic
                                if(isset($fio[0]))$first_name = $fio[0];
                                if(isset($fio[1]))$last_name = $fio[1];
                                if(isset($fio[2]))$patronymic = $fio[2];
                                /**/
                                $first_name = $inputs['first_name'];
                                $last_name = $inputs['last_name'];
                                $patronymic = $inputs['second_name'];

                                $user['email']=$email;
                                $user['name']=$first_name;
                                $user['lastname']=$last_name;
                                $user['patronymic']=$patronymic;
    //                            $user['send_news']=0;
    //                            $user['birthday']=$year."-01-01";
    //                            $user['phone']=$uphone;

    //                            $pass=PASS_SECRET.$phone;
                                $pass=$this->ccab_code_generate(6);
                                $pass=$inputs['pass'];
                                $m='Новый пароль: '.$pass;
    //                        add_log($m);
                            //                $phone='+7 (456) 142-54-01';
                                $userdata = array(
                            //	'ID'              => 0,  // когда нужно обновить пользователя
                                    'user_pass'       => $pass, // обязательно
                                    'user_login'      => $login, // обязательно
                                    'user_nicename'   => $email,
                                    'user_url'        => $inputs['url'],
                                    'user_email'      => $email,
                            //	'user_phone'      => '',
                                    'display_name'    => $email,
                                    'nickname'        => $email,
                                    'first_name'      => $first_name,
                                    'last_name'       => $last_name,
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
                                $reg_res = wp_insert_user( $userdata );
                                $uid=false;
    //                            add_log($reg_res);
                                $error_string=0;
                                if ( is_wp_error( $reg_res ) ) {
                                    $error_string = $reg_res->get_error_message();
    //                                add_log($error_string);
                                    $res = false;
                                }
                                else {
                                    $uid=$reg_res;
    //                                    $res = true;
                                }

                                if($uid) {
                                    $res_mess='Вы зарегистрировались.';
                                    $res_mess='Регистрация прошла успешно, учетная запись ожидает подтверждения администратором.';


    //                                    add_log($user);
                                    $user_ = ccab_login($login,$pass);

                    /*          ========== отправка почты ==========           */
                                    if(10){
                                    $name='register_ok';
                                    $atr=array();
                                    $atr['__user_name__']=$user['name'];
                                    $atr['__user_name2__']=$user['patronymic'];
                                    $atr['__user_name3__']=$user['lastname'];
                                    $atr['__user_login__']=$user['email'];
                                    $atr['__user_pass__']=$pass;
                                    
                                    $atr['__user_url__']=$inputs['url'];
                                    $atr['__user_email__']=$user['email'];
                                    $atr['__user_company__']=$inputs['org_name'];
                                    $atr['__user_position__']=$inputs['position'];
                                    $atr['__user_phone__']=$inputs['phone'];

                                    if(0){ // test 1
                                        $to=array();
//                                        $to[]='info.cclean2017@gmail.com';//bz11
                                        $to[]='9042006@gmail.com';//bz11
                                        $mres=ccab_get_mail($name,$atr,$to);
        //                                if($mres){
        //                                    add_log('письмо отправленно');
        //                                }else{
        //                                    add_log('письмо НЕ отправленно');
        //                                }
                                    }

                                    if(0){ // test 2
                                        $to=array();
                                        $to[]='home_work_mail@mail.ru';
//                                        $mres=ccab_get_mail($name,$atr,$to);
                                    $mres=ccab_get_mail('new_user',$atr,$to);
        //                                if($mres){
        //                                    add_log('письмо отправленно');
        //                                }else{
        //                                    add_log('письмо НЕ отправленно');
        //                                }
                                    }
                                    $to=array();
                                    $to[]='9042006@gmail.com';//bz11
                                    $mres=ccab_get_mail('new_user',$atr,$to);

                                    $to=array();
                                    $to[]=$user['email'];
                                    $mres=ccab_get_mail($name,$atr,$to);
    //                                if($mres){
    //                                    add_log('письмо отправленно');
    //                                }else{
    //                                    add_log('письмо НЕ отправленно');
    //                                }
                                    }
                    /*          ========== / отправка почты ==========           */

                                    if ($user_ ) {
    //    $fields =[];
    //    $fields['form-type'] = 'form-type';
    //    $fields['org_name'] = 'Название организации';
    //    $fields['last_name'] = 'Фамилия руководителя';
    //    $fields['first_name'] = 'Имя руководителя';
    //    $fields['second_name'] = 'Отчество руководителя';
    //    $fields['org_reqv'] = 'Реквизиты организации';
    //    $fields['org_phone'] = 'Телефон организации';
    ////    $fields['org_email'] = 'Почта организации';
    //    $fields['phone'] = 'Личный номер телефона для связи';
    //    $email = $inputs['email'];
    //                                    add_log($user_);
    //                                $res = $user;
                                    $user_id = $uid;
    //                                $user_id = $reg_res->ID;
                //                    $user['phone']=
                //                            get_user_meta($user_->ID, 'phone', true);
    //                                add_user_meta // update_usermeta
    //                                ( $user_id, 'phone', $user['phone'] );

            add_user_meta ( $user_id, 'second_name', $inputs['second_name'] );

            add_user_meta ( $user_id, 'org_name', $inputs['org_name'] );
//            add_user_meta ( $user_id, 'org_reqv', $inputs['org_reqv'] );

    //    $fields['form-type'] = 'form-type';
    //    $fields['org_name'] = 'Название организации';
    //    $fields['last_name'] = 'Фамилия руководителя';
    //    $fields['first_name'] = 'Имя руководителя';
    //    $fields['second_name'] = 'Отчество руководителя';
    //    $fields['org_reqv'] = 'Реквизиты организации';
    //    $fields['org_adres'] = 'Юридический адрес';
    //    $fields['org_inn'] = 'ИНН';
    //    $fields['org_kpp'] = 'КПП';
    //    $fields['org_bik'] = 'БИК';
    //    $fields['org_rs'] = 'Р/с';
    //    $fields['org_ks'] = 'К/с';
    //    $fields['org_phone'] = 'Телефон организации';
    //    $fields['org_email'] = 'Почта организации';
    //    $fields['phone'] = 'Личный номер телефона для связи';
    //    $fields['terms'] = 'Соглашение';

//            add_user_meta ( $user_id, 'org_adres', $inputs['org_adres'] );
//            add_user_meta ( $user_id, 'org_inn', $inputs['org_inn'] );
//            add_user_meta ( $user_id, 'org_kpp', $inputs['org_kpp'] );
//            add_user_meta ( $user_id, 'org_bik', $inputs['org_bik'] );
//            add_user_meta ( $user_id, 'org_rs', $inputs['org_rs'] );
//            add_user_meta ( $user_id, 'org_ks', $inputs['org_ks'] );
//
//            add_user_meta ( $user_id, 'org_phone', $inputs['org_phone'] );

            add_user_meta ( $user_id, 'position', $inputs['position'] );

            add_user_meta ( $user_id, 'phone', $inputs['phone'] );
//            add_user_meta ( $user_id, 'org_fcou_load', 0 );
//            add_user_meta ( $user_id, 'org_fcou_get', 0 );
    //        add_user_meta ( $user_id, 'phone', $inputs['phone'] );

    //                                add_user_meta
    //                                ( $user_id, 'first_name', $user['name'] );
    //                                add_user_meta
    //                                ( $user_id, 'last_name', $user['lastname'] );
    //                                add_user_meta // update_user_meta
    //                                ( $user_id, 'second_name', $user['patronymic'] );
    //                                add_user_meta
    //                                ( $user_id, 'birthday', $user['birthday'] );
    //                                add_user_meta
    //                                ( $user_id, 'send_news', $user['send_news'] );
    //                                ccab_update_client
    //                                ( $user_id, 'user_email', $user['email'] );

                //                    ccab_update_client_login
                //                    ( $user_id, 'user_login', 'adminZ' );


            //                        if ( is_wp_error( $user ) ) {
            //                            $error_string = $user->get_error_message();
            //                            $res = false;
            //                        }
            //                        else {
            //                            $res = true;
            //                        $user_id=$user_->ID;
            //                        $user['phone']=
            //                                get_user_meta($user_->ID, 'phone', true);
            //                        update_usermeta
            //                            ( $user_id, 'phone'
            //                                , $_SESSION['new_phone'] );
            //                        if(
            //                            current_user_can('subscriber')
            //                            || current_user_can('client')
            //                        ){
            //                            ccab_update_client_login
            //                            ( $user_id, 'user_login', $phone );
            //                        }
//                                        $res_mess.='<br/> Вход осуществлён.';
    //                                    add_log($res_mess);
                                        $res=true;
                                    }else{
                                        $res_mess.='<br/> Ошибка входа.';
                                    }
                                }else //( is_wp_error( $reg_res ) ) 
                                    {
    //                                $error_string = $reg_res->get_error_message();
                                    $res_mess='Сбой регистрации.';
                                    if($error_string){
                                        $res_mess=$error_string;
                                    }
    //                                $res = false;
                                }
                            }
                        }else{
                            $res_mess='Вы уже залогинены.';
                        }
    //                    $user['phone']= $_POST['form-type'];
                    }
    //                else{// если сбой
    //                    
    //                    $res_mess='Нет данных.';
    //                    if(!isset($_SESSION['reg_arr']))//new_phone
    ////                        $res_mess='Нет данных о номере телефона.';
    //                        $res_mess='Нет данных для регистрации.';
    //                    else 
    //                    if(!isset($_SESSION['code_sms']))
    //                        $res_mess='Нет данных о коде.';
    //                    else if(($_SESSION['code_sms'] != $_POST['phone']))
    //                        $res_mess='Не верный код.';
    //                }
                        /* / проверить наличие всех данных */
    //                add_log($res_mess);
                    $out=array();
                    $out['data']=array();
    //                $out['data']['code']=$_SESSION['code_sms'];
                    $out['data']['mess']=$res_mess;
                    $out['data']['res']=$res?'ok':'error';
                    $out['res']=$res;
                    $err_out = ob_get_clean();
                    if(strlen($err_out)>0)
                    $out['err_out'] = $err_out;
    //                $out['$_']=$_SESSION;

//        _add_log($out,'json_encode($out);','код регистрации'); // логи в базу
//        _add_log(' ---------------------------------- '
    //            . '<br/> ---------------------------------- '
//                ,'end','код регистрации'); // логи в базу
        if($is_ajax){
            /*    for flatlastic    */
            if(!$res){
                $prc_error->add( 'user_register_filed', __( '<strong>ERROR</strong>: '.$res_mess ) );
    //	if ( $prc_error->get_error_code() )
                return $prc_error;
            }else{
                $out['user_id']=$user_id;
                return $out;
            }
            /*    / for flatlastic    */
    //                                    add_log($res_mess);
                    echo json_encode($out);
            //        echo "{ts:'hi'}";
                    exit;
        }else{
                $prc_error->add( 'user_register_filed', __( '<strong>ERROR</strong>: '.$res_mess ) );
    //        if(!$res)
                add_log($res_mess);
                    if(strlen($err_out)>0)
                add_log($err_out);
    //        add_log($out);
            if($res){
//                wp_redirect(esc_url(home_url('/кабинет/')));
                wp_redirect(esc_url(home_url('/')));
                exit();
            }
        }
    }
    public function user_login_validate($login=''){
        global $wpdb;
        $r=false;
        $tab=$wpdb->prefix.'users';
        $q="select count(id) from $tab where user_login = %s";
        $c=$wpdb->get_var($wpdb->prepare($q,$login));
        if($c>0)$r=true;
        return $r;
    }
    public function ccab_code_generate($count=4,$type='hard'){
        switch($type){
            case 'overhard':
                $l=5;
                break;
            case 'hard':
                $l=4;
                break;
            case 'full':
                $l=3;
                break;
            case 'medium':
                $l=2;
                break;
            case 'light':
                $l=1;
                break;
            default:
                $l=0;
                break;
        }
        if($count<$l)$count=$l;
        $out='';
        $base=array(0,1,2,3,4,5,6,7,8,9);
        $base=array_merge($base,$base);
        $s0='0123456789';
        $s1=',.:;!?';
        $s2='QWERTYUIOPASDFGHJKLZXCVBNM';
        $s3='qwertyuiopasdfghjklzxcvbnm';
        $s0=str_split($s0);
        $s1=str_split($s1);
        $s2=str_split($s2);
        $s3=str_split($s3);
        switch($type){
            case 'overhard':
            case 'hard':
                $base=array_merge($base,$s1);
                $base=array_merge($base,$s1);
                $base=array_merge($base,$s1);
                $base=array_merge($base,$s1);
                $base=array_merge($base,$s1);
                $base=array_merge($base,$s1);
            case 'full':
                $base=array_merge($base,$s2);
            case 'medium':
                $base=array_merge($base,$s3);
                break;
            case 'light':
            default:
                break;
        }
        for($i=0;$i<$count;$i++){
            shuffle($base);
            $num=  array_rand($base, 1);
    //        echo '<pre>' . print_r($num, 1) . '</pre><br/>';
            $out.=$base[$num];
    //        $out.=$base[$num[0]];
        }
        $out = str_split($out);
        if( $l>=1 && !count(array_intersect($s0, $out))){
            $num=array_rand($s0, 1);
            $out[0]=$s0[$num];
        }
        if( $l>=4 && !count(array_intersect($s1, $out))){
            $num=array_rand($s1, 1);
            $out[1]=$s1[$num];
        }
        if( $l>=3 && !count(array_intersect($s2, $out))){
            $num=array_rand($s2, 1);
            $out[2]=$s2[$num];
        }
        if( $l>=2 && !count(array_intersect($s3, $out))){
            $num=array_rand($s3, 1);
            $out[3]=$s3[$num];
        }
        $out = implode('',$out);

        return $out; 
    }
    
    public function is_user_role( $role, $user_id = null ) {
        $user = is_numeric( $user_id ) ? get_userdata( $user_id ) : wp_get_current_user();

        if( ! $user )
            return false;

        return in_array( $role, (array) $user->roles );
    }
}