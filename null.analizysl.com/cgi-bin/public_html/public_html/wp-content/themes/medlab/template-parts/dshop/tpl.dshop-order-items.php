<?php

/* 
 * tpl.dshop-order-items.php
 */
global $list_cou_def;

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
    ?>
    <tr>
      <td colspan="<?=$all_col?>">Сумма:</td>
      <td><?=$total.' '.$currency_short?></td>
    </tr>
    <?php
    do_action('ds_order_total__row_pre', $total, $td_cou = $all_col,$oid);
    $total = apply_filters('ds_order_total', $total,$oid);
    ?>
    <tr>
        <td colspan="<?=$all_col?>">Всего:</td>
      <td><?=$total.' '.$currency_short?></td>
    </tr>
        <?php
}
else{
    echo '<tr><td colspan="8">';
    echo 'Нет позиций.';
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