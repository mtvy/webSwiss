<?php

/* 
 * tpl.dshop-account-orders-sent.php
 * [ds_page page="account-orders" type="sent"]
 */

$r_access = [];
$r_access [] ='administrator';
$r_access [] ='ml_administrator';
$r_access [] ='ml_manager';
//$r_access [] ='ml_doctor';
$r_access [] ='ml_procedurecab';
$user = wp_get_current_user();
if(count( array_intersect($r_access, (array) $user->roles ) ) == 0 ){
    get_template_part( 'template-parts/page/tpl.page-access', 'denied' );
//    get_template_part( 'template-parts/page/tpl.page-access', 'notfound' );
    return null;
}
global $list_cou_def, $ht;


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

ob_start();

        ?>
<table class="table table-hover table-striped -table-dark">
  <caption>Список заказов</caption>
  <thead>
    <tr>
      <th scope="col">№</th>
      <!--<th scope="col">ID заказа</th>-->
      <th scope="col">Номер заказа</th>
      <th scope="col">Дата</th>
<!--      <th scope="col">Товар</th>
      <th scope="col">Дата рождения</th>
      <th scope="col">Телефон / E-mail</th>-->
      <th scope="col">Стоимость</th>
      <th scope="col">Состояние заказа</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
      <?php
    
$count = wp_count_posts( 'dsorder', 'readable' )->publish;
if($count>0){
    $user = wp_get_current_user();
    // параметры по умолчанию
    $args =[
//        'author'  => $user->ID,
    	'numberposts' => 1000,
    	'offset'    => 0,
    //	'numberposts' => $count,
    //	'offset'    => $offset,
    //	'category'    => 0,
        'orderby'     => 'date',
        'order'       => 'DESC',
    //	'include'     => array(),
    //	'exclude'     => array(),
        'meta_key'    => '',
        'meta_value'  =>'',
        'meta_query'   => [

        ],
        'post_type'   => 'dsorder',
        'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
    ] ;
    if(1){
        $qm = [];
        $qm['relation'] = 'OR';
        $qm[] = 
            [
                'key' => 'dso_status',
                'value' => 'created',
                'compare' => '=',
            ];
        $qm[] = 
            [
                'key' => 'dso_query_status',
                'value' => 'sent',
//                'value' => 'send_wait',
                'compare' => '=',
//                'compare' => 'LIKE'
//                'compare' => 'NOT LIKE'
//                'type'  => 'NUMERIC'
            ];
        $qm[] = 
            [
                'key' => 'dso_query_status',
                'compare' => 'NOT EXISTS'
            ];
        $args['meta_query']['relation'] = 'AND';
        $args['meta_query']['relation'] = 'OR';
        $args['meta_query'][] = 
            [
                'key' => 'dso_status',
                'value' => 'query_sent',
                'compare' => '=',
            ];
        $args['meta_query'][] = 
            [
                'key' => 'dso_status',
                'value' => 'answer_got',
                'compare' => '=',
            ];
//        $args['meta_query'][] = 
//            [
//                'key' => 'dso_status',
//                'value' => 'pending',
//                'compare' => '!=',
//            ];
//        $args['meta_query'][]=$qm;
        
//        $args['meta_query']['relation'] = 'OR';
//        $args['meta_query'][] = 
//            [
//                'key' => 'dso_status',
//                'value' => 'created',
//                'compare' => '=',
//            ];
//        $args['meta_query'][] = 
//            [
//                'key' => 'dso_query_status',
////                'value' => 'sent',
//                'value' => 'send_wait',
//                'compare' => '=',
////                'compare' => 'LIKE'
////                'compare' => 'NOT LIKE'
////                'type'  => 'NUMERIC'
//            ];
//        $args['meta_query'][] = 
//            [
//                'key' => 'dso_query_status',
//                'compare' => 'NOT EXISTS'
//            ];
    }
$r_access = [];
//$r_access [] ='administrator';
//$r_access [] ='ml_administrator';
//$r_access [] ='ml_manager';
//$r_access [] ='ml_doctor';
$r_access [] ='ml_procedurecab';
$user = wp_get_current_user();
if(count( array_intersect($r_access, (array) $user->roles ) ) > 0 ){
        $args['author']  = $user->ID;
}
    //    dso_query_status
    ob_start();
//    echo 'dfsd';
    $_posts = get_posts( $args );
    $warning = ob_get_clean();
    if($warning)add_log($warning);
//    add_log($posts);
//    wp_reset_postdata();
////
//    return '';
    
    
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
    
    $url_answer_blank = get_the_permalink( 135 );
    $url_bill_blank = get_the_permalink( 413 );
    $url_order_edit = get_the_permalink( 27125 );
    $url_order_defect_edit = get_the_permalink( 28391 );
    foreach( $_posts as $dsorder ){
        setup_postdata($dsorder);
        $orderId = $dsorder->ID;

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
            case 'get_answer': $state_class='bg-warning'; break;
            case 'get_answer_failure': $state_class='bg-warning'; break;
            case 'answer_got': $state_class='bg-success'; break;
            
            default:
                $state_class='bg-danger'; break;
        }
        $state_class.=' ostat_'.$state_;
        
        
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
        $btn = 'Открыть';
        $btncl = 'btn-success';
        if($state_ == 'checked'){
            $btn = 'Оплатить';
            $btncl = 'btn-success';
        }
        $dso_puid =  get_post_meta( $orderId, 'dso_puid', true );
        $dso_query_id =  get_post_meta( $orderId, 'dso_query_id', true );
        $dso_query_nr =  get_post_meta( $orderId, 'dso_query_nr', true );
        $pfio=[];
        if($dso_puid){
            $last_name = esc_attr(get_the_author_meta('last_name', $dso_puid));
            $first_name = esc_attr(get_the_author_meta('first_name', $dso_puid));
            $second_name = esc_attr(get_the_author_meta('second_name', $dso_puid));
            if($last_name)$pfio[]=$last_name;
            if($first_name)$pfio[]=$first_name;
            if($second_name)$pfio[]=$second_name;
            
        }
        $pfio = implode("<br/>\n",$pfio);
        if($pfio)$pfio="<br/>\n".$pfio;
//        $barcode = MLBarcode::img($dso_query_nr);
        $barcode = MLBarcode::img_file($dso_query_nr,MLBARCODELOADS,'order_nr');
    ?>
    <tr>
      <th scope="row"><?=++$num?></th>
      <!--<td nowrap><?=$dsorder->ID?></td>-->
      <td nowrap><?=$dso_query_nr?>
          <br/><?=$barcode?>
      </td>
      <td nowrap><?=$date?> <?=$pfio?></td>
      <td><?=$dso_total_?> <?=$currency_short?>
<!--        <form method="post">
            <input type="hidden" name="form-type" value="order_status">
            <input type="hidden" name="act" value="pending">
            <input type="hidden" name="oid" value="<?=$dsorder->ID?>">
            <button type="submit" value="update" class="btn btn-primary text-white">Отклонить</button>
        </form>-->
      </td>
      <td class="<?=$state_class?>"><?=$state_f?></td>
      <td nowrap>
            <a href="/order?oid=<?=$dsorder->ID?>"
                class="btn <?=$btncl?>"><?=$btn?></a>
            <a href="<?=$url_bill_blank?>?oid=<?=$dsorder->ID?>"
               target="_blank"
            class="btn btn-primary text-white">Счёт</a>
            <br/>
            <a href="<?=$url_answer_blank?>?oid=<?=$dsorder->ID?>"
               target="_blank"
            class="btn btn-primary text-white mt-2">Контейнеры</a>
          <?php if(current_user_can('manage_options')){ ?>
            <br/>
            <a href="<?=$url_order_defect_edit?>?oid=<?=$dsorder->ID?>"
               target="_blank"
            class="btn btn-primary text-white mt-2">Брак</a>
          <?php } ?>
<!--        <form method="post">
            <input type="hidden" name="form-type" value="order_status">
            <input type="hidden" name="act" value="send_query">
            <input type="hidden" name="oid" value="<?=$dsorder->ID?>">
            <input type="hidden" name="dso_status" value="send_query">
            <input type="hidden" name="dso_q_ref_comment" value="may be test">
            <button type="submit" value="update" class="btn btn-primary text-white">Отправить</button>
        </form>-->
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
}
else{
    echo '<tr><td colspan="6">';
    echo 'Нет заказов.';
    echo '</td></tr>';
}?>
  </tbody>
</table>

      <?php
    ?>
<script>
    var mywindow;
    function bp(e){
    mywindow.print();
//    el.width=el;
//    mywindow.close();
    }
    function barcode_print(code)
{
//    var elem = this.id;
    var elem = 'ml_bar_'+code;
    
    console.log(elem);
    console.log(code);
    // var
    mywindow = window.open('', 'PRINT', 'height=700,width=1000');
//    mywindow = window.open('', 'PRINT', 'height=100%,width=100%');

//    mywindow.document.write('<html><head><title>' + document.title  + '</title>');
    mywindow.document.write('<html><head><title>' + code  + '</title>');
    mywindow.document.write('</head><body >');
//    mywindow.document.write('<h1>' + document.title  + '</h1>');
    var el = document.getElementById(elem)
    var size = el.width;
    el.style.width='100%';
//    mywindow.document.write(document.getElementById(elem).parentElement.innerHTML);
    mywindow.document.write(el.outerHTML);
    mywindow.document.write('</body></html>');

    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/

//sleep(1);
//var ms = 1000;
//ms += new Date().getTime();
//while (new Date() < ms){}

//    mywindow.print();
//    el.width=el;
//    mywindow.close();

setTimeout(bp, 3000);

    return true;
}
    function _barcode_print(){
        var id = this.id;
        
        var barform = document.getElementById('barform');
//        barform.style('display','none');

if(barform){
        barform.style.display = 'none';
    }
//        console.log(barform);
        window.print();
if(barform){
        barform.style.display = 'block';
    }
        return false;
}
</script>


<script>
//    jQuery('[data-toggle="popover"]').popover()
    jQuery(function () {
        jQuery('[data-toggle="popover"]').popover({
          trigger: 'hover'
        })
    })
</script>
      <?php
$data = ob_get_clean();

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

/* 
 * tabs.php
 */
$tab_url = get_the_permalink( get_the_ID() ) ;
$active_tab = '';
$active_tab = get_the_ID();
$_tabs = [];
//$_tabs['main']='Не отправленные';
$_tabs[382]='Не отправленные';
$_tabs[389]='Отправленные';
$tabs = [];
foreach($_tabs as $k=>$tab){ // $this->tabs
    $active = '';
//    if( ($this->tab == '' && $k == 'main') || $this->tab == $k)$active = ' active ';
    if( ($active_tab == '' && $k == 'main')
            || ($active_tab == '' && $k == $tab_url)
                    || $active_tab == $k)$active = ' active ';
    $router = [];
    $router['tab']= $k;
    $href = $tab_url . '?' . http_build_query($router);
    if($k == 'main')
        $href = $tab_url ;
    else
        $href = get_the_permalink( $k ) ;
    $r=[];
    $r['class'] = 'nav-link disabled';
    $r['class'] = 'nav-link'.$active;
    $r['href'] = $href;
    $a = $ht->f('a',$tab,$r);
    $r=[];
    $r['class'] = 'nav-item';
    $tabs[] = $ht->f('li',$a,$r);
}
?>
<div class="container-fluid -m-2 -border -border-primary -mb-2">
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-tabs">
                <?=implode("\n",$tabs)?>
<!--                <li class="nav-item">
                  <a class="nav-link disabled" href="">tab: <? //=$this->tab?></a>
                </li>-->
            </ul>
        </div>
    </div>
</div>

<?php
echo $data;