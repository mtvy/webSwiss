<?php

/* 
 * tpl.dshop-order-items-edit.php
 * [ds_page page="order-items" type="edit" old=""]
 */
global $list_cou_def,$ht;
/*
 * 
            <input type="hidden" name="form-type" value="order_intems">
            <input type="hidden" name="act" value="remove_item">
            <input type="hidden" name="oid" value="<?=$oid?>">
            <input type="hidden" name="oiid" value="<?=$item->ID?>">
 * 
            <input type="hidden" name="form-type" value="order_intems">
            <input type="hidden" name="act" value="add_item">
            <input type="hidden" name="oid" value="<?=$oid?>">
 * 
 */
$delivery_use = get_option('delivery_use', 0); // использовать ли доставку и адрес

    $oid = filter_input(INPUT_GET, 'oid', FILTER_SANITIZE_NUMBER_INT);
    if($oid===false || $oid===null || $oid==='')$oid=0;
//    $offset = filter_input(INPUT_POST, 'offset', FILTER_SANITIZE_NUMBER_INT);
//    if($offset===false || $offset===null || $offset==='')$offset=0;
//    $isajax = filter_input(INPUT_POST, 'isajax', FILTER_SANITIZE_NUMBER_INT);
//    if($isajax===false || $isajax===null || $isajax==='')$isajax=0;
//    $count = filter_input(INPUT_POST, 'count', FILTER_SANITIZE_NUMBER_INT);
//    if($count===false || $count===null || $count==='')$count=$list_cou_def;
//    if ( wp_doing_ajax())$isajax=1;
    
//    $oid = filter_input(INPUT_GET, 'oid', FILTER_SANITIZE_NUMBER_INT);
//    if($oid===false || $oid===null || $oid==='')$oid=0;
    
    $ftype = filter_input(INPUT_POST, 'form-type', FILTER_SANITIZE_STRING);
    $act = filter_input(INPUT_POST, 'act', FILTER_SANITIZE_STRING);
    $success = filter_input(INPUT_POST, 'success', FILTER_SANITIZE_STRING);
    $oiid = filter_input(INPUT_POST, 'oiid', FILTER_SANITIZE_NUMBER_INT);
    $pid = filter_input(INPUT_POST, 'pid', FILTER_SANITIZE_NUMBER_INT);
    if($oiid===false || $oiid===null || $oiid==='')$oiid=0;
    if($pid===false || $pid===null || $pid==='')$pid=0;
//    add_log($_POST);
//    echo $ht->pre($_POST);
//    $url_order_edit = get_the_permalink( 27125 );
    $url_order_edit = get_the_permalink( get_the_ID() ) ;
    
    
/**
 * добавление позиции в заказ или изменение количества позиции в заказе.
 * @global obgect $DShop DShop obgect
 * @param int $orderId order id
 * @param int $pid product id
 * @param int $cou items adding count
 * @param boolean $replace true = foll count, false = add count.
 */
function create_new_order_item($orderId,$pid,$cou=1,$replace = true){
    global $DShop;
//            add_log($DShop);
    $user = wp_get_current_user();

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
//    	'include'     => [$oid],
    //	'exclude'     => array(),
//                'meta_key'    => 'dsoi_orderId',
//                'meta_value'  => $orderId,
        'meta_query' => [
            'relation' => 'AND',
            [
                'key' => 'dsoi_orderId',
                'value' => $orderId
            ],
            [
                'key' => 'dsoi_prodId',
                'value' => $pid
            ]
        ],
        'post_type'   => 'dsoitem',
        'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
    ];
    $roless_provided = [];
    $roless_provided ['administrator'] ='administrator';
    $roless_provided ['ml_administrator'] ='ml_administrator';
    $roless_provided ['ml_manager'] ='ml_manager';
    //$roless_provided ['ml_doctor'] ='ml_doctor';
    $roless_provided ['ml_procedurecab'] ='ml_procedurecab';
    if(! current_user_can( 'manage_options' ) && count( array_intersect($roless_provided, (array) $user->roles ) ) == 0 ){
            $oargs['author']=$user->ID;
    }
    $query = new WP_Query( $oargs );
    $count =  $query->found_posts;
    if(!is_user_logged_in())
        $count =  0;

    $summ_new = 0;
    if($count>0 ){
        if($count>1 ){
            $m = '!!! Дублирование позиций зказа';
        }
        ob_start();
        $_posts = get_posts( $oargs );
        $err = ob_get_clean();
        wp_reset_query();
        $total = 0;
        $pos_cou = 0;
        $items_cou = 0;
        $total = 0;
        $item=null;
        foreach( $_posts as $item ){
            $dsoi_count_ =  get_post_meta( $item->ID, 'dsoi_count', true );
            if($replace) $dsoi_count_ = 0;
            $cou += $dsoi_count_;
            update_post_meta( $item->ID, 'dsoi_count', $cou );
        }
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
        
        $object = null;
        if(isset($analyses[$pid])){
            $object = $analyses[$pid];
            $code=$analyses[$pid]['Code'];
//            add_log($analyses[$pid]);
        }
        if(isset($panels[$pid])){
            $object = $analyses[$pid];
            $code=$panels[$pid]['Code'];
//            add_log($panels[$pid]);
        }
//            add_log($price[$pid]);
//            add_log($groups[$object['AnalysisGroupId']]);
        /*
         * group
         * 
    [Id] => 2495749
    [UpdateTime] => 25.05.2020 11:17:28
    [UpdateVersion] => 682
    [Name] => Пакет: Проверка щитовидной железы
    [Code] => 100-011
    [ShortName] => Пакет: Проверка щитовидной железы
    [AnalysisGroupCode] => 16
    [AnalysisGroupId] => 161014
         * 
         * single
         * 
    [Id] => 225712
    [UpdateTime] => 01.04.2020 14:17:39
    [UpdateVersion] => 608
    [Name] => Клинический анализ крови (общий анализ крови, лейкоцитарная формула и СОЭ)
    [Code] => 20-000
    [ShortName] => Клинический анализ крови
    [AnalysisGroupCode] => 1
    [AnalysisGroupId] => 82971
    [item] => SimpleXMLElement Object
         */

        $prod = [];
        $d = 0;
        $prod['deliv'] = 0;
        $prod['percent'] = 0;
        $prod['cost'] = $price[$pid]['Price'];
        $prod['url'] = get_the_permalink( get_option('ds_id_page_item',0) ) . '?pid=' . $pid;
        $prod['categid'] = $object['AnalysisGroupId'];
        $prod['categ'] = $groups[$object['AnalysisGroupId']]['Name'];
        $prod['name'] = $object['Name'];
//            add_log($prod);

$DShop = new DShop();
        $item = $DShop->create_order_item($orderId,$pid,$cou,$prod,$d);
            if(is_wp_error($item))
                return;
    $last_name = esc_attr(get_the_author_meta('last_name', $user->ID));
    $first_name = esc_attr(get_the_author_meta('first_name', $user->ID));
    $second_name = esc_attr(get_the_author_meta('second_name', $user->ID));
//            $title = $l.' '.$f.' '.$s;
            $title = $last_name.' '.$first_name.' '.$second_name;
            $title = wp_strip_all_tags( $title );
            $item_args = [];
            $item_args['ID']=$item['ID'];
            $item_args['post_title']=$title.' / '.$item['title'];
//            $item_args['post_title']=$title;
            wp_update_post( wp_slash($item_args) );
    }
            
}
function order_count_update($orderId){
    
        $total = 0;
        $dso_items_count=0;
        $dso_count=0;
        
        
    
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
        //    	'include'     => [$oid],
            //	'exclude'     => array(),
                'meta_key'    => 'dsoi_orderId',
                'meta_value'  => $orderId,
                'post_type'   => 'dsoitem',
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
            if(! current_user_can( 'manage_options' ) && count( array_intersect($roless_provided, (array) $user->roles ) ) == 0 ){
                    $oargs['author']=$user->ID;
            }
            //    add_log($oargs);
            //$count = DShop::_get_count_dsoitem( $oid );
            //add_log($count);
            //$count = wp_count_posts( 'dsoitem', 'readable' )->publish;

            //$query = new WP_Query( array(
            //    'post_type'   => 'dsoitem',
            //    'meta_key'    => 'dsoi_orderId',
            //    'meta_value'  => $oid, ) );

            $count =  0;
            $query = new WP_Query( $oargs );
            $count =  $query->found_posts;
            //add_log($count);
            if(!is_user_logged_in())
                $count =  0;

            $summ_new = 0;
            if($count>0 ){
                ob_start();
                $_posts = get_posts( $oargs );
                $err = ob_get_clean();
                wp_reset_query();
                $total = 0;
                $pos_cou = 0;
                $items_cou = 0;
                $total = 0;
                $item=null;
                foreach( $_posts as $item ){
                    $dsoi_prodName_ =  get_post_meta( $item->ID, 'dsoi_prodName', true );
                    $dsoi_count_ =  get_post_meta( $item->ID, 'dsoi_count', true );
                    $dsoi_item_cost_ =  get_post_meta( $item->ID, 'dsoi_item_cost', true );


                    $dsoi_delivery_poland_cost_ =  get_post_meta( $item->ID, 'dsoi_delivery_poland_cost', true );
                    $dsoi_markup_ =  get_post_meta( $item->ID, 'dsoi_markup', true );



                    $cost = $dsoi_item_cost_;
                    $deliv = $dsoi_delivery_poland_cost_;
                    $cou = $dsoi_count_;
                    $delivery_percent = $dsoi_markup_;

            //        $max = $prod['max'];
                    $cost_ = strtr($cost,',','.');
                    $deliv = strtr($deliv,',','.');
                    $percent = $delivery_percent; // esc_attr( get_option('delivery_percent',0) );
                    $percent_=($deliv/100)*$percent;
                    $cost = ($cost_*$cou)+$deliv+$percent_;
                    $total += $cost;
                $pos_cou ++;
                $items_cou += $cou;
                $dso_count ++;
                $dso_items_count += $cou;
                }
            }
        
//            $log=[];
//            $log[]=$total;
//            $log[]=$dso_total_;
//            add_log($log);
        update_post_meta( $orderId, 'dso_items_count', $dso_items_count );
        update_post_meta( $orderId, 'dso_count', $dso_count );
        update_post_meta( $orderId, 'dso_cost', $total );
}
//        add_log($_SESSION);
$url_order_edit = get_the_permalink( get_option('ds_pageid_order_edit',0) ) ;
$url_price = get_the_permalink( get_option('ds_pageid_price',0) ) ;
if($ftype == 'order_intems' && $act == 'add_item' && $oid > 0){
    if($success !== 'ok'){
        if(!isset($_SESSION['ds_order'])) $_SESSION['ds_order'] = [];
        $_SESSION['ds_order']['act'] = 'edit';
        $_SESSION['ds_order']['oid'] = $oid;
//        add_log( get_option('ds_pageid_price',0));
//        add_log( $url_price );
        wp_redirect($url_price);
        exit;
    }else{
//        add_log($_SESSION);
        create_new_order_item($oid,$pid,$cou=1,$replace = true);
        order_count_update($oid);
        wp_redirect($url_order_edit."?oid=$oid");
        exit;
    }
}
unset( $_SESSION['ds_order'] );
if($ftype == 'order_intems' && $act == 'remove_item' && $oiid > 0){
//    echo $ht->pre($_POST);
    $dsoi_prodName_ =  get_post_meta( $oiid, 'dsoi_prodName', true );
    if($success !== 'ok'){
            ?>
<table class="table table-hover table-striped -table-dark">
  <caption>Список позиций заказа</caption>
  <thead>
    <tr>
      <th scope="col" colspan="2">Подтверждение удаления</th>
    </tr>
    <tr>
      <th scope="col" colspan="2">Вы действительно желаете удалить позицию:</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <!--<th scope="row"></th>-->
        <td colspan="2"><?=$dsoi_prodName_?></td>
    </tr>
    <tr>
      <td>
        <form method="post">
            <input type="hidden" name="form-type" value="order_intems">
            <input type="hidden" name="act" value="remove_item">
            <input type="hidden" name="success" value="ok">
            <input type="hidden" name="oid" value="<?=$oid?>">
            <input type="hidden" name="oiid" value="<?=$oiid?>">
<!--            <input type="hidden" name="dso_status" value="send_query">
            <input type="hidden" name="dso_q_ref_comment" value="may be test">
            <button type="submit" value="update" class="btn btn-primary text-white">Отправить</button>-->
          <button id="update-cart-button" class="btn btn-danger -cart-button-remove"
                onclick="jQuery('#item-cou-'+this.dataset.pid).val(0); return true;"
                type="submit" name="go" value="cart" data-num='<?=$num-1?>' data-pid='<?=$pid?>'
                >Удалить</button>
        </form>
      </td>
      <td>
          <a href="?oid=<?=$oid?>" class="btn btn-primary -cart-button-remove"
                >Отмена</a>
      </td>
    </tr>
  </tbody>
</table>
      <?php
      return;
        }else{
            wp_delete_post($oiid,1);
            order_count_update($oid);
    /*
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
        //    	'include'     => [$oid],
            //	'exclude'     => array(),
                'meta_key'    => 'dsoi_orderId',
                'meta_value'  => $oid,
                'post_type'   => 'dsoitem',
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
            if(! current_user_can( 'manage_options' ) && count( array_intersect($roless_provided, (array) $user->roles ) ) == 0 ){
                    $oargs['author']=$user->ID;
            }
            //    add_log($oargs);
            //$count = DShop::_get_count_dsoitem( $oid );
            //add_log($count);
            //$count = wp_count_posts( 'dsoitem', 'readable' )->publish;

            //$query = new WP_Query( array(
            //    'post_type'   => 'dsoitem',
            //    'meta_key'    => 'dsoi_orderId',
            //    'meta_value'  => $oid, ) );

            $count =  0;
            $query = new WP_Query( $oargs );
            $count =  $query->found_posts;
            //add_log($count);
            if(!is_user_logged_in())
                $count =  0;

            $summ_new = 0;
            if($count>0 ){
                ob_start();
                $_posts = get_posts( $oargs );
                $err = ob_get_clean();
                wp_reset_query();
                $total = 0;
                $pos_cou = 0;
                $items_cou = 0;
                $total = 0;
                $item=null;
                foreach( $_posts as $item ){
                    setup_postdata($item);

            //        $date = date( "d.m.y", strtotime( $item->post_date ) );

                    $dsoi_orderId_ =  get_post_meta( $item->ID, 'dsoi_orderId', true );
                    $dsoi_prodId_ =  get_post_meta( $item->ID, 'dsoi_prodId', true );
                    $dsoi_prodUrl_ =  get_post_meta( $item->ID, 'dsoi_prodUrl', true );
                    $dsoi_prodCategory_ =  get_post_meta( $item->ID, 'dsoi_prodCategory', true );
                    $dsoi_prodName_ =  get_post_meta( $item->ID, 'dsoi_prodName', true );
                    $dsoi_count_ =  get_post_meta( $item->ID, 'dsoi_count', true );
            //        $dsoi_items_count_ =  get_post_meta( $item->ID, 'dsoi_items_count', true );
                    $dsoi_item_cost_ =  get_post_meta( $item->ID, 'dsoi_item_cost', true );
                    $dsoi_items_cost_ =  get_post_meta( $item->ID, 'dsoi_items_cost', true );


                    $dsoi_delivery_poland_cost_ =  get_post_meta( $item->ID, 'dsoi_delivery_poland_cost', true );
                    $dsoi_deliveryPolad_ =  get_post_meta( $item->ID, 'dsoi_delivery_poland', true );
                    $dsoi_delivery_cost_ =  get_post_meta( $item->ID, 'dsoi_delivery_cost', true );
                    $dsoi_deliveryId_ =  get_post_meta( $item->ID, 'dsoi_delivery_id', true );
                    $dsoi_deliveryName_ =  get_post_meta( $item->ID, 'dsoi_delivery_name', true );
                    $dsoi_markup_ =  get_post_meta( $item->ID, 'dsoi_markup', true );



                    $cost = $dsoi_item_cost_;
                    $deliv = $dsoi_delivery_poland_cost_;
                    $url = $dsoi_prodUrl_;
                    $name = $dsoi_prodName_;
                    $cou = $dsoi_count_;
                    $delivery_percent = $dsoi_markup_;

            //        $max = $prod['max'];
                    $cost_ = strtr($cost,',','.');
                    $deliv = strtr($deliv,',','.');
                    $pid = $dsoi_prodId_;
                    $percent = $delivery_percent; // esc_attr( get_option('delivery_percent',0) );
                    $percent_=($deliv/100)*$percent;
                    $cost = ($cost_*$cou)+$deliv+$percent_;
                    $total += $cost;
                $pos_cou ++;
                $items_cou += $cou;
                }
            }

            update_post_meta( $oid, 'dso_cost', $total );
        update_post_meta( $oid, 'dso_items_count', $items_cou );
        update_post_meta( $oid, 'dso_count', $pos_cou );
//            $dso_total_ = apply_filters('ds_order_total', $total,$oid);
//            update_post_meta( $oid, 'dso_total', $dso_total_ );
            $log=[];
            $log[]=$total;
            $log[]=$dso_total_;
//            add_log($log);
            /**/
            wp_redirect($url_order_edit."?oid=$oid");
        }
//        $os = filter_input(INPUT_POST, 'OutSum',FILTER_SANITIZE_STRING);
//        $id = filter_input(INPUT_POST, 'InvId',FILTER_SANITIZE_STRING);
//        $ac = filter_input(INPUT_POST, 'Shp_action',FILTER_SANITIZE_STRING);
//        $sv = filter_input(INPUT_POST, 'SignatureValue',FILTER_SANITIZE_STRING);
//        
//        if($id===false || $id===null || $id==='')$id=0;
//        if($id>0)$oid = $id;
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
      <th scope="col">Доставка</th>
      <th scope="col">Наценка</th>
     */
        ?>
<table class="table table-hover table-striped -table-dark">
  <caption>Список позиций заказа</caption>
  <thead>
    <tr>
      <th scope="col">№</th>
      <th scope="col">ID заказа</th>
      <th scope="col">Название</th>
      <th scope="col">Цена единицы</th>
      <th scope="col">Количество</th>
      <th scope="col">Код</th>
        <?php if($delivery_use){ ?>
      <th scope="col">Доставка</th>
      <th scope="col">Процент доставки</th>
        <?php } ?>
      <th scope="col">Стоимость</th>
      <th scope="col"></th>
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
//    	'include'     => [$oid],
    //	'exclude'     => array(),
        'meta_key'    => 'dsoi_orderId',
        'meta_value'  => $oid,
        'post_type'   => 'dsoitem',
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
if(! current_user_can( 'manage_options' ) && count( array_intersect($roless_provided, (array) $user->roles ) ) == 0 ){
        $oargs['author']=$user->ID;
}
//    add_log($oargs);
//$count = DShop::_get_count_dsoitem( $oid );
//add_log($count);
//$count = wp_count_posts( 'dsoitem', 'readable' )->publish;

//$query = new WP_Query( array(
//    'post_type'   => 'dsoitem',
//    'meta_key'    => 'dsoi_orderId',
//    'meta_value'  => $oid, ) );
    
$count =  0;
$query = new WP_Query( $oargs );
$count =  $query->found_posts;
//add_log($count);
if(!is_user_logged_in())
    $count =  0;

if($count>0 ){
    ob_start();
    $_posts = get_posts( $oargs );
    $err = ob_get_clean();
    wp_reset_query();
    
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
    $total = 0;
    
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
    $item=null;
    foreach( $_posts as $item ){
        setup_postdata($item);

//        $date = date( "d.m.y", strtotime( $item->post_date ) );
        
        $dsoi_orderId_ =  get_post_meta( $item->ID, 'dsoi_orderId', true );
        $dsoi_prodId_ =  get_post_meta( $item->ID, 'dsoi_prodId', true );
        $dsoi_prodUrl_ =  get_post_meta( $item->ID, 'dsoi_prodUrl', true );
        $dsoi_prodCategory_ =  get_post_meta( $item->ID, 'dsoi_prodCategory', true );
        $dsoi_prodName_ =  get_post_meta( $item->ID, 'dsoi_prodName', true );
        $dsoi_count_ =  get_post_meta( $item->ID, 'dsoi_count', true );
//        $dsoi_items_count_ =  get_post_meta( $item->ID, 'dsoi_items_count', true );
        $dsoi_item_cost_ =  get_post_meta( $item->ID, 'dsoi_item_cost', true );
        $dsoi_items_cost_ =  get_post_meta( $item->ID, 'dsoi_items_cost', true );
        
        
        $dsoi_delivery_poland_cost_ =  get_post_meta( $item->ID, 'dsoi_delivery_poland_cost', true );
        $dsoi_deliveryPolad_ =  get_post_meta( $item->ID, 'dsoi_delivery_poland', true );
        $dsoi_delivery_cost_ =  get_post_meta( $item->ID, 'dsoi_delivery_cost', true );
        $dsoi_deliveryId_ =  get_post_meta( $item->ID, 'dsoi_delivery_id', true );
        $dsoi_deliveryName_ =  get_post_meta( $item->ID, 'dsoi_delivery_name', true );
        $dsoi_markup_ =  get_post_meta( $item->ID, 'dsoi_markup', true );
        
        
        
        $cost = $dsoi_item_cost_;
        $deliv = $dsoi_delivery_poland_cost_;
        $url = $dsoi_prodUrl_;
        $name = $dsoi_prodName_;
        $cou = $dsoi_count_;
        $delivery_percent = $dsoi_markup_;
        
//        $max = $prod['max'];
        $cost_ = strtr($cost,',','.');
        $deliv = strtr($deliv,',','.');
        $pid = $dsoi_prodId_;
        $percent = $delivery_percent; // esc_attr( get_option('delivery_percent',0) );
        $percent_=($deliv/100)*$percent;
        $cost = ($cost_*$cou)+$deliv+$percent_;
        $total += $cost;
        
        if($deliv>0)$deliv.=' '.$currency_short;
        
        $code = '';
        if(isset($analyses[$pid])){
            $code=$analyses[$pid]['Code'];
        }
        if(isset($panels[$pid])){
            $code=$panels[$pid]['Code'];
        }
        
        $rep=[];
//        $rep['[pid]']=$pid;
//        $rep['[cou]']=$cou;
//        $rep['[min]']=0;
//        $rep['[max]']=$max;
//        $rep['[num]']=$num;
//        $tpl_cart_item =
//                dshop::_get_tpl('template-parts/page/tpl.dshop-cart','item',$rep);
//        $field_count=$tpl_cart_item;
        $field_count=$dsoi_count_;
        
//        setup_postdata($post);
//        // формат вывода the_title() ...
//
//        $date = date( "d.m.y", strtotime( $post->post_date ) );
//        
//        $adress = get_post_meta($post->ID,$nm.'adress',1);
//        $namebc =  get_post_meta($post->ID,$nm.'namebc',1);
//        if($namebc)$adress.=' / '.$namebc;
//
//        $fio = get_post_meta($post->ID,$nm.'fio',1);
//        $position = get_post_meta($post->ID,$nm.'position',1);
//        if($position)$fio.=' / '.$position;
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
         * 
      <td><?=$dsoi_items_cost_?></td>
         */
    ?>
    <tr>
      <th scope="row"><?=++$num?></th>
      <td nowrap><?=$dsoi_orderId_?></td>
      <td><?=$name?></td>
      <td><?=$cost_.' '.$currency_short?></td>
      <td nowrap><?=$field_count?></td>
      <td><?=$code?> {<?=$pid?>}</td>
        <?php if($delivery_use){?>
            <td><?=$deliv?></td>
            <td>%<?=$percent?></td>
        <?php }?>
      <td><?=$cost.' '.$currency_short?></td>
      <!--<td class="<? //=$state_==0?'bg-danger':'bg-success'?>"><? //=$author?></td>-->
      <td>
        <form method="post">
            <input type="hidden" name="form-type" value="order_intems">
            <input type="hidden" name="act" value="remove_item">
            <input type="hidden" name="oid" value="<?=$oid?>">
            <input type="hidden" name="oiid" value="<?=$item->ID?>">
<!--            <input type="hidden" name="dso_status" value="send_query">
            <input type="hidden" name="dso_q_ref_comment" value="may be test">
            <button type="submit" value="update" class="btn btn-primary text-white">Отправить</button>-->
          <button id="update-cart-button" class="btn btn-danger cart-button-remove"
                onclick="jQuery('#item-cou-'+this.dataset.pid).val(0); return true;"
                type="submit" name="go" value="cart" data-num='<?=$num-1?>' data-pid='<?=$pid?>'
                >X</button><?=$item->ID?>
        </form>
      </td>
    </tr>
        <?php

//    echo '<tr><td colspan="8">';
//    echo
////    '<pre>'.
//            print_r($post,1)
////            .'</pre>'
//            ;
//    echo '</td></tr>';
    }
    $all_col = 6;
    if($delivery_use)
    $all_col = 8;
//    $all_col = 7;
//    if($delivery_use)
//    $all_col = 9;
    $td_cou = $all_col+1;
    ?>
    <tr>
      <td colspan="<?=$all_col?>">Сумма:</td>
      <td><?=$total.' '.$currency_short?></td>
      <td>&nbsp;</td>
    </tr>
    <?php
    do_action('ds_order_total__row_pre', $total, $td_cou, $oid);
    $total = apply_filters('ds_order_total', $total,$oid);
    ?>
    <tr>
        <td colspan="<?=$all_col?>">Всего:</td>
      <td><?=$total.' '.$currency_short?></td>
      <td>&nbsp;</td>
    </tr>
        <?php
}
else{
    echo '<tr><td colspan="8">';
    echo 'Нет позиций.';
    echo '</td></tr>';
}?>
    <tr>
      <!--<th scope="row"></th>-->
        <td colspan="7">Добавить позицию:</td>
      <td>
        <form method="post">
            <input type="hidden" name="form-type" value="order_intems">
            <input type="hidden" name="act" value="add_item">
            <input type="hidden" name="oid" value="<?=$oid?>">
<!--            <input type="hidden" name="dso_status" value="send_query">
            <input type="hidden" name="dso_q_ref_comment" value="may be test">
            <button type="submit" value="update" class="btn btn-primary text-white">Отправить</button>-->
            <button type="submit" value="update" class="btn btn-primary cart-button-remove"
                onclick="jQuery('#item-cou-'+this.dataset.pid).val(0); return true;"
                type="submit" name="go" value="cart" data-num='<?=$num-1?>' data-pid='<?=$pid?>'
                ><big><b>+</b></big></button>
        </form>
      </td>
    </tr>
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