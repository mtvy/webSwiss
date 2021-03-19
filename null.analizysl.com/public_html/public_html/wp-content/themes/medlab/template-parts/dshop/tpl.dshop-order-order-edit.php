<?php

/* 
 * tpl.dshop-order-order-edit.php
 */
global $list_cou_def;
global $DSPs;
global $ht;


    $oid = filter_input(INPUT_GET, 'oid', FILTER_SANITIZE_NUMBER_INT);
    if($oid===false || $oid===null || $oid==='')$oid=0;
    /*
    $ftype = filter_input(INPUT_POST, 'Shp_action', FILTER_SANITIZE_STRING);
    if($ftype=='payment'){
        $os = filter_input(INPUT_POST, 'OutSum',FILTER_SANITIZE_STRING);
        $id = filter_input(INPUT_POST, 'InvId',FILTER_SANITIZE_STRING);
        $ac = filter_input(INPUT_POST, 'Shp_action',FILTER_SANITIZE_STRING);
        $sv = filter_input(INPUT_POST, 'SignatureValue',FILTER_SANITIZE_STRING);
        
        if($id===false || $id===null || $id==='')$id=0;
        if($id>0)$oid = $id;
        
        if(strlen($sv)>0){
            $mrh_pass1 = $DSPs->mrh_pass1;
            $crc = strtoupper($sv);
            $my_crc = strtoupper(md5("$os:$id:$mrh_pass1:Shp_action=$ac"));
            $crc = strtoupper($crc);
            if ($my_crc ==$crc)
            {
                $m = "Оплата на сумму $os прошла успешно";
            }else{
                $m = "Контрольная сумма оплаты не верна";
            }
            add_log($m);
        }else if(strlen($os)>0 && $id>0){
            $m = "Вы отказались от оплаты. Заказ# $id<br/>\n";
            $m .= "You have refused payment. Order# $id\n";
            add_log($m);
        }
    }
    /**/
        
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
<style>
    .pay_form_td_logo_cash img{
        height:26px;
    }
</style>
<table class="table table-hover table-striped -table-dark">
  <caption>Заказ</caption>
  <thead>
    <tr>
      <th scope="col">ID заказа</th>
      <th scope="col">Дата</th>
<!--      <th scope="col">Товар</th>
      <th scope="col">Дата рождения</th>
      <th scope="col">Телефон / E-mail</th>-->
      <th scope="col">Стоимость</th>
      <th scope="col">Состояние заказа</th>
    </tr>
  </thead>
  <tbody>
      <?php
    
    $user = wp_get_current_user();
    // параметры по умолчанию
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
        'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
    ];
//    if(!current_user_can('manage_options'))
//        $oargs['author']=$user->ID;
$roless_provided = [];
$roless_provided ['administrator'] ='administrator';
$roless_provided ['ml_administrator'] ='ml_administrator';
$roless_provided ['ml_manager'] ='ml_manager';
//$roless_provided ['ml_doctor'] ='ml_doctor';
$roless_provided ['ml_procedurecab'] ='ml_procedurecab';
if(! current_user_can( 'manage_options' ) && count( array_intersect($roless_provided, (array) $user->roles ) )  == 0 ){
        $oargs['author']=$user->ID;
}
    $incposts = wp_parse_id_list( $oargs['include'] );
    $oargs['post__in'] = $incposts;
//    add_log($oargs);
//$count = wp_count_posts( 'dsorder', 'readable' )->publish;
    
$count =  0;
$query = new WP_Query( $oargs );
$count =  $query->found_posts;
//add_log($count);
if(!is_user_logged_in())
    $count =  0;

if($count>0){
    $_posts = get_posts( $oargs );
    
//    $nm = 'dso_';
//        $state = [];
//        $state['0'] = 'Проблема не решена';
//        $state['1'] = 'Проблема решена';
        $states =[
            'created'=>'Обрабатывается',
            'checked'=>'Оформлен',
            'payd'=>'Оплачен',
            'paychecked'=>'Оплата проверена',
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
        setup_postdata($dsorder);

        $date = date( "d.m.y", strtotime( $dsorder->post_date ) );
        
        
        $state_ =  get_post_meta( $dsorder->ID, 'dso_status', true );
        
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
        $dso_total_ =  get_post_meta( $dsorder->ID, 'dso_cost', true );
        $dso_total_ = apply_filters('ds_order_total', $dso_total_,$oid);
        
        $states =[
            'created'=>'Обрабатывается',
            'checked'=>'Оформлен',
            'payd'=>'Оплачен',
            'paychecked'=>'Оплата проверена',
            'sent'=>'Отправлен',
            'deliverid'=>'Доставлен',
            'pending'=>'Отклонён',
        ];
        $states = apply_filters('ds_dsorder_post_display_meta_box_order__out_status', $states, $dsorder, $_this=null);
//        add_log($states);
//        $state_f = $states[$state_];
        $d = $state_;
        
        if(key_exists($d, $states)){
            $state_f = $states[$d];
            $valid = 1;
        }else{
            foreach($states as $g=>$state){
                if(is_array($state) && key_exists($d, $state)){
                    $state_f = $g.': '.$state[$d];
                    $valid = 1;
                }
            }
        }
        $state_class = '';
        switch ($state_) {
            case 'created': $state_class='bg-primary'; break;//alert-
            case 'checked': $state_class='bg-success'; break;
            case 'payd': $state_class='bg-success'; break;
            case 'paychecked': $state_class='bg-success'; break;
            case 'sent': $state_class='bg-success'; break;
            case 'deliverid': $state_class='bg-success'; break;
            case 'pending': $state_class='bg-danger'; break;
            case 'query_sent': $state_class='bg-success'; break;
            default:
                $state_class='bg-danger'; break;
        }
        
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
    ?>
    <tr>
      <td nowrap><?=$dsorder->ID?></td>
      <td nowrap><?=$date?></td>
      <td><?=$dso_total_?> <?=$currency_short?></td>
      <td class="<?=$state_class?>"><?=$state_f?></td>
    </tr>
        <?php
        $DSPsForm = "";
        if(0 && $state_ == 'checked'){ // !!! определить стстус доступный для оплаты
            $DSPs_list = $DSPs->get_pyments_list();
            $DSPs_active = $DSPs->get_pyments_active();
            $DSPs_stats = $DSPs->pyments_stats;
            $DSPs_ps_items = $DSPs->get_pyments_items();
            
//            add_log($DSPs_active);
            
            foreach ($DSPs_active as $pkey => $value) {
                $DSPs_ps_item = $DSPs_ps_items[$pkey];
                if($DSPs_ps_item->paybtn_var == 'no') continue;
                if(!$DSPs_ps_item->form_access()) continue;
//            add_log($DSPs_ps_item->paybtn_var);
                $state_class = '';
                $DSPsForm = $DSPs_ps_item->form($dsorder->ID,$dso_total_.'',0);
                if($DSPs_ps_item->paybtn_var == 'script' || $DSPs_ps_item->paybtn_var == 'script_ext'){

    //            $DSPsForm = $DSPs->form($dsorder->ID,$dso_total_.'',1);
        ?>
        <tr>
            <td colspan="4" class="<?=$state_class?>"><?=$DSPsForm?></td>
        </tr>
            <?php
                }
                if($DSPs_ps_item->paybtn_var == 'form'){
    //            $DSPsForm = $DSPs->form($dsorder->ID,$dso_total_,'',2);
            $img = 'https://auth.robokassa.ru/Merchant/PaymentForm/Images/logo-l.png';
            $img = $DSPs_ps_item->img;
            $DSPsFormImg = '<img src="'.$img.'" height="20">';
            ?>
        <tr>
            <td colspan="2" class="<?=$state_class?>"><?=$DSPs_ps_item->name?></td>
            <td colspan="1" class="<?=$state_class?> pay_form_td_logo pay_form_td_logo_<?=$pkey?>"><?=$DSPsFormImg?></td>
            <td colspan="1" class="<?=$state_class?>"><?=$DSPsForm?></td>
        </tr>
            <?php
                }
            }
            
        }
//        $fields=$this->meta_fields;
//        $states =[
//            'created'=>'Обрабатывается',
//            'checked'=>'Оформлен',
//            'payd'=>'Оплачен',
//            'paychecked'=>'Оплата проверена',
//            'sent'=>'Отправлен',
//            'deliverid'=>'Доставлен',
//            'pending'=>'Отклонён',
//        ];
        $starr = [];
        $starr[]='paychecked';
        $starr[]='sent';
        $starr[]='deliverid';
        if(in_array($state_, $starr)){
        $dso_message_ =  get_post_meta( $dsorder->ID, 'dso_payment_message', true );
        ?>
    <tr>
        <td colspan="4" class=""><?=$dso_message_?></td>
    </tr>
        <?php
        }
        //echo $state_;
        if(0 && $state_ == 'checked'){
            $r_access = [];
            $r_access [] ='administrator';
            //$r_access [] ='ml_administrator';
            //$r_access [] ='ml_manager';
            //$r_access [] ='ml_doctor';
            $r_access [] ='ml_procedurecab';
            $user = wp_get_current_user();
            if(count( array_intersect($r_access, (array) $user->roles ) ) > 0 ){
                $discont = DShop::_get_cart_discont('laborant');
                if($discont)add_log('Имеется скидка: +'.$discont.' %');
                $return_to = get_the_permalink( 101 ).'?oid='.$oid;
                $btn = $ht->f('input','',['type'=>'hidden','name'=>'return_to','value'=>$return_to])."\n";
                $btn .= $ht->f('div',$ht->f('div',$ht->f('button','Добавить скидку',['class'=>'btn btn-primary','type'=>"sumbit"]),['class'=>'col-12 text-right']),['class'=>'row']);
                
                $btn = $ht->f('form',$btn,['method'=>'post','action'=>get_the_permalink( 1968 )]);
//                $btn .= htmlspecialchars($brn);
                echo $ht->f('tr',$ht->f('td',$btn,['colspan'=>4]));
            }
        }

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
    echo 'Нет заказов.';
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