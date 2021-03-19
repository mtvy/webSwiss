<?php

/* 
 * tpl.medlab-bill-blank.php
 * [ml_page tpl="tpl.medlab" type="bill-blank" old=""]
 */
$user = wp_get_current_user();
global $list_cou_def;

$delivery_use = get_option('delivery_use', 0); // использовать ли доставку и адрес

//echo 'analise-blank<br/>';

$barcode='Med Lab 001';
//echo MLBarcode::img($barcode);

$bar_types = [11,128];
$bar_def=128;
$bar_print = filter_input(INPUT_GET, 'barprint',FILTER_DEFAULT);
$bar_type = filter_input(INPUT_GET, 'bartype',FILTER_SANITIZE_NUMBER_INT);
$barcode = filter_input(INPUT_GET, 'barcode',FILTER_SANITIZE_NUMBER_INT);
$code = filter_input(INPUT_GET, 'zbarcode',FILTER_SANITIZE_NUMBER_INT);
if(!in_array($bar_type,$bar_types))$bar_type=$bar_def;
if($bar_type==128)
    $barcode = filter_input(INPUT_GET, 'barcode',FILTER_DEFAULT);

//$atts = shortcode_atts( array( 'barcode' => $barcode,'bar_type' => $bar_type, ), $atts );
//$bar_type = $atts['bar_type'];
//$barcode = $atts['barcode'];

$oid = filter_input(INPUT_GET, 'oid',FILTER_SANITIZE_NUMBER_INT);
if($oid){
//    $barcode='Med Lab '.$oid;
    $nr = get_post_meta($oid, 'dso_query_nr', true);
    if($nr)$barcode = $nr;
}else $oid=0;
//        
//        $code=123;
//        if(!$barcode)$barcode=$code;


$logo = '';
$address = 'г. Таганрог';
$lab_name = 'SWISS LAB';
$phone = '+7(123)1234-56-78 +7(123)1234-56-78 ';

$lab_name = get_option('ml_lab_name');
$phone = get_option('ml_lab_phone');

$license = '';
$fio = '';
$site_name = '';

$lab_fio = '';
    
    $born_date='';
    $pphone='';
        
        $p_adres = '';
        $passnum = '';
        $temperature = '';
        $pcomment = '';
        
$secod = '';
$pemail = '';

$num=0;
$cost = 500;
$dso_total_ =  get_post_meta( $oid, 'dso_cost', true );

$created_date = '';

$luid=0;
$puid=0;

$date = '';
$date2 = '';
    
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

$puid = 0;
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
//        setup_postdata($dsorder);

        $date = date( "d.m.Y", strtotime( $dsorder->post_date ) );
        $created_date  = date( "d.m.Y H:i", strtotime( $dsorder->post_date ) );
        $created_date  = date( "d.m.Y", strtotime( $dsorder->post_date ) );
        $state_ =  get_post_meta( $dsorder->ID, 'dso_status', true );
        $dso_total_ =  get_post_meta( $dsorder->ID, 'dso_cost', true );
        $puid =  get_post_meta( $dsorder->ID, 'dso_puid', true );
        $patient_id = $puid;
        
//        $states =[
//            'created'=>'Обрабатывается',
//            'checked'=>'Оформлен',
//            'payd'=>'Оплачен',
//            'paychecked'=>'Оплата проверена',
//            'sent'=>'Отправлен',
//            'deliverid'=>'Доставлен',
//            'pending'=>'Отклонён',
//        ];
//        $states = apply_filters('ds_dsorder_post_display_meta_box_order__out_status', $states, $dsorder, $_this=null);
        
        $fio = get_user_meta($puid,'last_name',1);
        $fio .= ' '.get_user_meta($puid,'first_name',1);
        $fio .= ' '.get_user_meta($puid,'second_name',1);
        $born_date = get_user_meta($puid,'born_date',1);
        $pphone = get_user_meta($puid,'phone',1);
        
        $user_data = get_userdata($puid) ;
//        dev::pre($user_data);
        $secod = get_user_meta($puid,'secod',1);
        $pemail = $user_data->user_login;
        
        $suid =  get_post_meta( $dsorder->ID, 'dso_sender', true ); // sender uid
        if($suid){
            $lab_fio = get_user_meta($suid,'last_name',1);
            $lab_fio .= ' '.get_user_meta($suid,'first_name',1);
            $lab_fio .= ' '.get_user_meta($suid,'second_name',1);
        }
//        $address =  get_user_meta($suid, 'blank_address', true ); // sender uid
        $labgroup =  get_user_meta($suid, 'lab_group', true ); // sender uid
        $address =  MedLabLabGroupFields::get_lab_group_address($labgroup ); // sender uid
        
        $p_adres = esc_attr(get_the_author_meta('residence_place', $patient_id));
        $passnum = esc_attr(get_the_author_meta('passnum', $patient_id));
        $temperature = esc_attr(get_the_author_meta('temperature', $patient_id));
        $pcomment = esc_attr(get_the_author_meta('pat_comment', $patient_id));
    }
}
/*
 * 
                    <tr>
                        <td align="center">
                            Медецинский Центр<br/>
                            <?=$lab_name?>
                        </td>
                    </tr>
                            Адрес: 
                            Тел: <?=$phone?>
 */
            $gender = get_user_meta( $puid, 'gender', true );
            $pregnancy = get_user_meta( $puid, 'pregnancy', true );
            $pregnancy_weeks = get_user_meta( $puid, 'pregnancy_week', true );
            
            if(!$gender){
                add_log($gender,'dump');
                $gender=0;
            }
            
        $sel=[];
        $sel[0] = 'пол неизвестен';
        $sel[1] = 'мужской';
        $sel[2] = 'женский';
        $gender_ = $sel[$gender];

$top_fields = [];
$top_fields ['Ф.И.О. Пациента'] = $fio;
$top_fields ['Дата рождения'] = $born_date;
$top_fields ['Пол'] = $gender_;
if($gender == 2 && $pregnancy==1 && $pregnancy_weeks>0){
    $top_fields ['Срок беременности (недель)'] = $pregnancy_week;
}
$top_fields ['Дата'] = $date;
$top_fields ['Телефон'] = $pphone;
$top_fields ['Адрес'] = $p_adres;
$top_fields ['Серия паспорта'] = $passnum;
$top_fields ['Температура'] = $temperature;
?>
<table width="100%">
    <tr>
        <td width="30%" valign="top">
                <?php the_custom_logo(); ?>
        </td>
        <td align="center">
            <center>
                <table align="center" width="100%" style="margin-bottom: 0px;">
                    <tr>
                        <td align="center">
                            <?= nl2br( $address, 1)?>
                        </td>
                    </tr>
                    <tr>
                        <td align="center"> <?=strtr(  home_url( '/' ) , ['http://'=>'','https://'=>'','/'=>''])?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            &nbsp;
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            <?php
//$barcode='Med Lab 001';
echo MLBarcode::img($barcode);?>
                        </td>
                    </tr>
                </table>
            </center>
        </td>
    </tr>
</table>

<table width="100%" border="1">
<?php

    foreach ($top_fields as $fname => $fvalue) {
        ?>
    <tr>
        <td width="30%" valign="top" align="center">
                            <?=$fname?>
        </td>
        <td width="" valign="top" align="center">
                            <?=$fvalue?>
        </td>
    </tr>
            
            <?php
    }

?>
</table>
<style>
    .bl_tab_pr tr td{
        padding-left: 5px;
        padding-right: 5px;
    }
</style>

<table width="100%" border="1" class="bl_tab_pr">
  <!--<caption>Список позиций заказа</caption>-->
  <thead>
    <tr>
      <td align="center" scope="col">№</td>
      <!--<td scope="col">ID заказа</td>-->
      <td align="center" scope="col">Код</td>
      <td align="center" scope="col">Наименование</td>
      <td align="center" scope="col">Цена</td>
      <td align="center" scope="col" nowrap>Кол-во</td>
      <td align="center" scope="col">Сумма</td>
      <td align="center" scope="col">Дисконт</td>
    </tr>
  </thead>
  <tbody>
    <tr>
        <td width="" valign="top" align="center" colspan="7">
            <b>Исследования</b>
        </td>
    </tr>
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
        <td width="" valign="top" align="right" scope="row" nowrap><?=++$num?></th>
      <!--<td nowrap><?=$dsoi_orderId_?></td>-->
        <td width="" valign="top" align="center" nowrap><?=$code?></td>
        <td width="" valign="top" align="left"><?=$name?></td>
        <td width="" valign="top" align="center" nowrap><?=$cost_?></td>
        <td width="" valign="top" align="center" nowrap><?=$field_count?></td>
        <td width="" valign="top" align="right"  nowrap><?=$cost?></td>
      <!--<td class="<? //=$state_==0?'bg-danger':'bg-success'?>"><? //=$author?></td>-->
        <td align="center" scope="col"> </td>
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
    $all_col = 5;
//    if($delivery_use)
//    $all_col = 8;
    
    do_action('ds_order_blank_total__row_pre', $total, $td_cou = $all_col,$oid);
    $total = apply_filters('ds_order_total', $total,$oid);
    ?>
    <tr>
        <td colspan="<?=$all_col+2?>" align="center"> &nbsp;
    </tr>
    <tr>
        <td colspan="<?=$all_col?>" align="center">
            <b>Сумма к оплате</b></td>
      <td width="" valign="top" align="right"  nowrap><b><?=$total.'</b> '.$currency_short?></td>
      <td width="" valign="top" align="right"  nowrap><?=""?></td>
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

//echo dshop::_get_order_items();

global $show_blank_footer;
if($show_blank_footer != 'no'){
    
    $bill_blank_info_text = get_option('ml_blankinfo_bottom_text');
?>

<table width="100%">
    <tr>
        <td width="" valign="top" colspan="3">
            Зарегистрирована: <?=$created_date?>
        </td>
    </tr>
    <tr>
        <td width="" valign="top" colspan="3" class=""><center>
            Получить результаты исследований можете на нашем сайте, в разделе личный кабинет<br/>
https://analizy.uz (https://analizysl.com )
        </center></td>
    </tr>
    <tr>
        <td width="" valign="top" colspan="3">
            <img width="100%" src="/scrinshot_header.png">
        </td>
    </tr>
    <tr>
        <td width="" valign="top" colspan="3">
<b>Данные для входа в личный кабинет:</b><br/>
<b>Логин: <?=$pemail?></b><br/>
<b>Пароль: <?=$secod?$secod:'[пароль]'?></b><br/>
        </td>
    </tr>
    <tr>
        <td width="" valign="top" colspan="3">
            <?=$bill_blank_info_text?>
        </td>
    </tr>
</table>

<?php
/*<b><?=$barcode?></b> (номер заявки(штрих кода), в своем роде капча безопастности)/**/
    if(0){
?>

<table width="100%">
    <tr>
        <td width="" valign="top" colspan="3">
            Зарегистрировал: <?=$lab_fio?>
        </td>
    </tr>
    <tr>
        <td width="" valign="top">
            Дата <?=$created_date?>
        </td>
        <td align="right">
            Я удостоверяю, что мои данные правильно внесены
        </td>
        <td width="10%" valign="top" style="border-bottom: 1px solid black">
            &nbsp;
        </td>
    </tr>
    <tr>
        <td width="100%" valign="top" colspan="3" style="border-bottom: 1px solid black">
            &nbsp;
        </td>
    </tr>
</table>

scrinshot_header.png
<?php
    }
}
?>