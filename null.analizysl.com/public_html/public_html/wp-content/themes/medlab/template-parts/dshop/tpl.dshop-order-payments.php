<?php

/* 
 * tpl.dshop-order-payments.php
 */
global $list_cou_def;
global $DSPs;


    $oid = filter_input(INPUT_GET, 'oid', FILTER_SANITIZE_NUMBER_INT);
    if($oid===false || $oid===null || $oid==='')$oid=0;
    
    $ftype = filter_input(INPUT_POST, 'Shp_action', FILTER_SANITIZE_STRING);
    if($ftype=='payment'){
        $os = filter_input(INPUT_POST, 'OutSum',FILTER_SANITIZE_STRING);
        $id = filter_input(INPUT_POST, 'InvId',FILTER_SANITIZE_STRING);
        $ac = filter_input(INPUT_POST, 'Shp_action',FILTER_SANITIZE_STRING);
        $sv = filter_input(INPUT_POST, 'SignatureValue',FILTER_SANITIZE_STRING);
//        
        if($id===false || $id===null || $id==='')$id=0;
        if($id>0)$oid = $id;
//        
//        if(strlen($sv)>0){
//            $mrh_pass1 = $DSPs->mrh_pass1;
//            $crc = strtoupper($sv);
//            $my_crc = strtoupper(md5("$os:$id:$mrh_pass1:Shp_action=$ac"));
//            $crc = strtoupper($crc);
//            if ($my_crc ==$crc)
//            {
//                $m = "Оплата на сумму $os прошла успешно";
//            }else{
//                $m = "Контрольная сумма оплаты не верна";
//            }
//            add_log($m);
//        }else if(strlen($os)>0 && $id>0){
//            $m = "Вы отказались от оплаты. Заказ# $id<br/>\n";
//            $m .= "You have refused payment. Order# $id\n";
//            add_log($m);
//        }
    }
        
//    $offset = filter_input(INPUT_POST, 'offset', FILTER_SANITIZE_NUMBER_INT);
//    if($offset===false || $offset===null || $offset==='')$offset=0;
//    $isajax = filter_input(INPUT_POST, 'isajax', FILTER_SANITIZE_NUMBER_INT);
//    if($isajax===false || $isajax===null || $isajax==='')$isajax=0;
//    $count = filter_input(INPUT_POST, 'count', FILTER_SANITIZE_NUMBER_INT);
//    if($count===false || $count===null || $count==='')$count=$list_cou_def;
//    if ( wp_doing_ajax())$isajax=1;
    
//        №
//Дата
//ФИО / Должность в компании
//Дата рождения
//Телефон / E-mail
//Описание ситуации
//Кто добавил / Статус

    /*
      <!--<th scope="col">№</th>-->
      <!--<th scope="col"></th>-->
     * 
     */
        ?>
<table class="table table-hover table-striped -table-dark">
  <caption>Оплаты</caption>
  <thead>
    <tr>
      <th scope="col">ID оплаты</th>
      <th scope="col">ID заказа</th>
      <th scope="col">Дата</th>
<!--      <th scope="col">Товар</th>
      <th scope="col">Дата рождения</th>
      <th scope="col">Телефон / E-mail</th>-->
      <th scope="col">Сумма</th>
      <th scope="col">Способ</th>
      <!--<th scope="col">Состояние заказа</th>-->
    </tr>
  </thead>
  <tbody>
      <?php
    
    $user = wp_get_current_user();
    // параметры по умолчанию
    $opargs = [
//    	'ID' => $oid,
//        'author'  => $user->ID,
    	'numberposts' => 1000,
    	'offset'    => 0,
    //	'numberposts' => $count,
    //	'offset'    => $offset,
    //	'category'    => 0,
        'orderby'     => 'date',
        'order'       => 'DESC',
//    	'include'     => [$oid],
    //	'exclude'     => array(),
        'meta_key'    => 'dsop_ID',
        'meta_value'  =>$oid,
        'post_type'   => 'dspayment',
        'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
    ];
//    if(!current_user_can('manage_options'))
//        $oargs['author']=$user->ID;
    
//    $incposts = wp_parse_id_list( $oargs['include'] );
//    $oargs['post__in'] = $incposts;
    
//    add_log($oargs);
//$count = wp_count_posts( 'dsorder', 'readable' )->publish;
    
$count =  0;
$query = new WP_Query( $opargs );
$count =  $query->found_posts;
//add_log($count);
if(!is_user_logged_in())
    $count =  0;


    $oargs = [
//    	'ID' => $oid,
//        'author'  => $user->ID,
    	'numberposts' => 1000,
    	'offset'    => 0,
    //	'numberposts' => $count,
    //	'offset'    => $offset,
    //	'category'    => 0,
        'orderby'     => 'date',
        'order'       => 'DESC',
    	'include'     => [$oid],
    //	'exclude'     => array(),
        'meta_key'    => '',
        'meta_value'  =>'',
        'post_type'   => 'dsorder',
//        'post_type'   => 'dspayment',
        'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
    ];
//    if(!current_user_can('manage_options'))
//        $oargs['author']=$user->ID;
    $count_o =  0;
    $query = new WP_Query( $oargs );
    $count_o =  $query->found_posts;
    //add_log($count);
    if(!is_user_logged_in())
        $count_o =  0;
    
if($count>0 && $count_o>0){
//if(1 && $count_o>0){
    $_posts = get_posts( $opargs );
    
//    $nm = 'dso_';
//        $state = [];
//        $state['0'] = 'Проблема не решена';
//        $state['1'] = 'Проблема решена';
        $states =[
            'created'=>'Обрабатывается',
            'checked'=>'Оформлен',
            'payd'=>'Оплачен',
            'sent'=>'Отправлен',
            'deliverid'=>'Доставлен',
            'pending'=>'Отклонён',
        ];
        $currency_short = get_option('currency_short','zl');
        
    $num=0;
//    $num=$offset;
//        $ftpl=[];
        
//        $fields[$nm.'fio']='ФИО';
//        $fields[$nm.'position']='Должность в компании';
//        $fields[$nm.'born']=' Дата рождения';
//        $fields[$nm.'phone']='Телефон';
//        $ftpl[$nm.'email'] = false;
//        $ftpl[$nm.'state'] = [];
//        $ftpl[$nm.'state']['0'] = 'Проблема не решена';
//        $ftpl[$nm.'state']['1'] = 'Проблема решена';
    foreach( $_posts as $dsorder ){
//        setup_postdata($dsorder);

        $date = date( "d.m.y", strtotime( $dsorder->post_date ) );
        
        
//        $state_ =  get_post_meta( $dsorder->ID, 'dso_status', true );
        
//        $dso_userId_ =  get_post_meta( $dsorder->ID, 'dso_userId', true );
//        $dso_user_name_ =  get_post_meta( $dsorder->ID, 'dso_user_name', true );
//        $dso_user_lastname_ =  get_post_meta( $dsorder->ID, 'dso_user_lastname', true );
//        $dso_user_sname_ =  get_post_meta( $dsorder->ID, 'dso_user_sname', true );
//        $dso_user_phone_ =  get_post_meta( $dsorder->ID, 'dso_user_phone', true );
//        $dso_user_email_ =  get_post_meta( $dsorder->ID, 'dso_user_email', true );
//        $dso_user_addres_ =  get_post_meta( $dsorder->ID, 'dso_user_addres', true );
//        $dso_agriments_ =  get_post_meta( $dsorder->ID, 'dso_agriments', true );
        
//        $dso_prodId_ =  get_post_meta( $dsorder->ID, 'dso_prodId', true );
//        $dso_prodUrl_ =  get_post_meta( $dsorder->ID, 'dso_prodUrl', true );
//        $dso_prodCategory_ =  get_post_meta( $dsorder->ID, 'dso_prodCategory', true );
//        $dso_prodName_ =  get_post_meta( $dsorder->ID, 'dso_prodName', true );
//        $dso_count_ =  get_post_meta( $dsorder->ID, 'dso_count', true );
//        $dso_item_cost_ =  get_post_meta( $dsorder->ID, 'dso_item_cost', true );
//        $dso_items_count_ =  get_post_meta( $dsorder->ID, 'dso_items_count', true );
//        $dso_delivery_poland_cost_ =  get_post_meta( $dsorder->ID, 'dso_delivery_poland_cost', true );
//        $dso_deliveryPolad_ =  get_post_meta( $dsorder->ID, 'dso_deliveryPolad', true );
//        $dso_delivery_cost_ =  get_post_meta( $dsorder->ID, 'dso_delivery_cost', true );
//        $dso_deliveryId_ =  get_post_meta( $dsorder->ID, 'dso_deliveryId', true );
//        $dso_deliveryName_ =  get_post_meta( $dsorder->ID, 'dso_deliveryName', true );
//        $dso_markup_ =  get_post_meta( $dsorder->ID, 'dso_markup', true );
        
//        $dso_total_ =  get_post_meta( $dsorder->ID, 'dso_cost', true );
        
        $states =[
            'created'=>'Обрабатывается',
            'checked'=>'Оформлен',
            'payd'=>'Оплачен',
            'sent'=>'Отправлен',
            'deliverid'=>'Доставлен',
            'pending'=>'Отклонён',
        ];
//        $state_f = $states[$state_];
//        $state_class = '';
//        switch ($state_) {
//            case 'created': $state_class='bg-primary'; break;//alert-
//            case 'checked': $state_class='bg-success'; break;
//            case 'payd': $state_class='bg-success'; break;
//            case 'sent': $state_class='bg-success'; break;
//            case 'deliverid': $state_class='bg-success'; break;
//            case 'pending': $state_class='bg-danger'; break;
//            default:
//                break;
//        }
        
//        $adress = get_post_meta($post->ID,$nm.'adress',1);
//        $namebc =  get_post_meta($post->ID,$nm.'namebc',1);
//        if($namebc)$adress.=' / '.$namebc;
        

//        $fio = get_post_meta($post->ID,$nm.'fio',1);
//        $position = get_post_meta($post->ID,$nm.'position',1);
//        if($position)$fio.=' / '.$position;
//        
//        $born = get_post_meta($post->ID,$nm.'born',1);
//
//        $phone = get_post_meta($post->ID,$nm.'phone',1);
//        $email = get_post_meta($post->ID,$nm.'email',1);
//        $phone.=' / '.$email;
//        
//        $contnt=get_the_content();
//
//        $state_ = get_post_meta($post->ID,$nm.'state',1);
//        $state_f = $state[$state_];
////        $author = get_the_author();
//        $author = get_the_author_posts_link();
//        $author = get_the_author_posts_link_company();
//        $author.=' / '.$state_f;
        /*
      <!--<th scope="row"><?=++$num?></th>-->
<!--      <td nowrap><a href="/order?oid=<?=$dsorder->ID?>"
                    class="btn btn-success">Открыть</a></td>-->
         * 
         */
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
        $dsop_total_ =  get_post_meta( $dsorder->ID, 'dsop_outsumm', true );
//        $dsop_total_ =  get_post_meta( $dsorder->ID, 'dso_cost', true );
        $dsop_way =  get_post_meta( $dsorder->ID, 'dsop_payway', true );
        $dso_ID =  get_post_meta( $dsorder->ID, 'dsop_ID', true );
    ?>
    <tr>
      <td nowrap><?=$dsorder->ID?></td>
      <td nowrap><?=$dso_ID?></td>
      <td nowrap><?=$date?></td>
      <td><?=$dsop_total_?> <?=$currency_short?></td>
      <td nowrap><?=$dsop_way?></td>
    </tr>
        <?php
        /** /
      <td class="<?=$state_class?>"><?=$state_f?></td>
        $DSPsForm = "";
        if($state_ == 'checked'){
            $state_class = '';
            $DSPsForm = $DSPs->form($dsorder->ID,$dso_total_.'',0);
            if($DSPs->paybtn_var == 'script' || $DSPs->paybtn_var == 'script_ext'){
                
//            $DSPsForm = $DSPs->form($dsorder->ID,$dso_total_.'',1);
    ?>
    <tr>
        <td colspan="4" class="<?=$state_class?>"><?=$DSPsForm?></td>
    </tr>
        <?php
            }
            if($DSPs->paybtn_var == 'form'){
//            $DSPsForm = $DSPs->form($dsorder->ID,$dso_total_,'',2);
        $img = 'https://auth.robokassa.ru/Merchant/PaymentForm/Images/logo-l.png';
        $DSPsFormImg = '<img src="'.$img.'">';
    ?>
    <tr>
        <td colspan="2" class="<?=$state_class?>"></td>
        <td colspan="1" class="<?=$state_class?>"><?=$DSPsFormImg?></td>
        <td colspan="1" class="<?=$state_class?>"><?=$DSPsForm?></td>
    </tr>
        <?php
            }
        }
            /**/

//    echo '<tr><td colspan="8">';
//    echo
////    '<pre>'.
//            print_r($post,1)
////            .'</pre>'
//            ;
//    echo '</td></tr>';
    }
}
else{
    echo '<tr><td colspan="4">';
    echo 'Нет оплат.';
    echo '</td></tr>';
}?>
  </tbody>
</table>
      <?php
/*
wp_reset_postdata(); // сброс

    $posts_count = wp_count_posts('bad_agent')->publish;
//    if(!$isajax){
    if ( !wp_doing_ajax() && $posts_count > $count ) {
        $next = $offset + $count;
        echo '<div class="row " id="upl-agent-btn-wrupp">';
        echo '<div class="col-md-12 text-center">';
        echo '<button type="button" id="get-list-agents"'
            . 'class="btn btn-dark btn-lg btn-block active upload-data" '
            . 'data-type="agent" data-count="'.$count.'" data-offset="'.$next.'" '
            . 'data-target="upl-agent-btn-wrupp" '
            . 'data-all="'.$posts_count.'" >Ещё</button>';
        echo '</div>';
        echo '</div>';
    }
    
    */