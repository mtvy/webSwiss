<?php

/* 
 * bonuses.php
 * [medlab  page="bonuses" oldmt="ds_page"]
 * tpl.dshop-bonuses.php
 * [ds_page  page="bonuses"]
 */

/*
 * 
====================

№
Дата
Номер карты
Номер заказа
Сумма заказа
% бонуса
Состояние выплаты Выплачено Не выплачено

====================

 */
global $ht;
//ini_set("display_errors", "1");
//ini_set("display_startup_errors", "1");
//ini_set('error_reporting', E_ALL);
//return '';
$r_access = [];
$r_access [] ='administrator';
$r_access [] ='ml_administrator';
$r_access [] ='ml_manager';
$r_access [] ='ml_doctor';
$r_access [] ='ml_procedurecab';
$user = wp_get_current_user();
if(count( array_intersect($r_access, (array) $user->roles ) ) == 0 ){
    get_template_part( 'template-parts/page/tpl.page-access', 'denied' );
//    get_template_part( 'template-parts/page/tpl.page-access', 'notfound' );
    return null;
}
//$duid = null; // uid doctor
//
//$duId = filter_input(INPUT_GET, 'duid', FILTER_SANITIZE_NUMBER_INT);
//if($duId===false || $duId===null|| $duId==='')
////    $duId=get_current_user_id();//'0';
//    $duId=false;//'0';
//$_duId = filter_input(INPUT_POST, 'duid', FILTER_SANITIZE_NUMBER_INT);
//if(strlen($_duId)>0)$duId=$_duId;

$duId = $ht->postget('duid',false,FILTER_SANITIZE_NUMBER_INT);
$auId = $ht->postget('auid',false,FILTER_SANITIZE_NUMBER_INT);


//add_log($duId);
//if(!$duId || !current_user_can('manage_options')){
$isdoctor = false;
$r_access = [];
$r_access [] ='ml_doctor';
if(count( array_intersect($r_access, (array) $user->roles ) ) > 0 ){
    $duId = get_current_user_id();
    $isdoctor = true;
}

$isagent = false;
$r_access = [];
$r_access [] ='ml_agent';
if(count( array_intersect($r_access, (array) $user->roles ) ) > 0 ){
    $auId = get_current_user_id();
    $isagent = true;
}
//$isdoctor=1;

global $list_cou_def;
$list_cou_def = 50;

$hiddens = [];
$urlget=[]; // фильтрация

    $offset = filter_input(INPUT_POST, 'offset', FILTER_SANITIZE_NUMBER_INT);
    if($offset===false || $offset===null || $offset==='')$offset=0;
    $isajax = filter_input(INPUT_POST, 'isajax', FILTER_SANITIZE_NUMBER_INT);
    if($isajax===false || $isajax===null || $isajax==='')$isajax=0;
    $count = filter_input(INPUT_POST, 'count', FILTER_SANITIZE_NUMBER_INT);
    if($count===false || $count===null || $count==='')$count=$list_cou_def;
    if ( wp_doing_ajax())$isajax=1;
    
    $hiddens['offset'] = $offset;
    $hiddens['count'] = $count;
    $hiddens['isajax'] = $isajax;
    
    
/*
 * /////////////////////
 * инициализация формы даты
 */
    
$date_from_ = filter_input(INPUT_GET, 'date-from', FILTER_VALIDATE_REGEXP,['options' =>['regexp' => '/^[\d]{2}\.[\d]{2}\.[\d]{4}$/']]);
if(!$date_from_)$date_from_='';
$date_to_ = filter_input(INPUT_GET, 'date-to', FILTER_VALIDATE_REGEXP,['options' =>['regexp' => '/^[\d]{2}\.[\d]{2}\.[\d]{4}$/']]);
if(!$date_to_)$date_to_='';

$date_from = filter_input(INPUT_POST, 'date-from', FILTER_VALIDATE_REGEXP,
        ['options' =>['default'=>$date_from_, 'regexp' => '/^[\d]{2}\.[\d]{2}\.[\d]{4}$/']]);
if(!$date_from)$date_from=$date_from_;
$date_to = filter_input(INPUT_POST, 'date-to', FILTER_VALIDATE_REGEXP,
        ['options' =>['default'=>$date_to_, 'regexp' => '/^[\d]{2}\.[\d]{2}\.[\d]{4}$/']]);
if(!$date_to)$date_to=$date_to_;

    $hiddens['date-from'] = $date_from;
    $hiddens['date-to'] = $date_to;

$p = null;
$patientid=false;
if($patientid>0){
    $p = '?pid='.$patientid;
}
//    $orderby = filter_input(INPUT_GET, 'order', FILTER_SANITIZE_STRING);
    $orderby = $ht->postget( 'order', false);

    $f_pcode = filter_input(INPUT_POST, 'pcode', FILTER_SANITIZE_NUMBER_INT);
//    $f_pfn = filter_input(INPUT_POST, 'pfn', FILTER_SANITIZE_STRING);
//    $f_pln = filter_input(INPUT_POST, 'pln', FILTER_SANITIZE_STRING);
//    $f_oid = filter_input(INPUT_POST, 'oid', FILTER_SANITIZE_NUMBER_INT);
    $f_duid = filter_input(INPUT_POST, 'duid', FILTER_SANITIZE_NUMBER_INT);
    $f_nr = filter_input(INPUT_POST, 'nr', FILTER_SANITIZE_NUMBER_INT);
    $f_dso_bs = filter_input(INPUT_POST, 'dso_bs', FILTER_SANITIZE_NUMBER_INT);
    $lab_g = filter_input(INPUT_POST, 'lab_g', FILTER_SANITIZE_NUMBER_INT);

    if( !$f_pcode )$f_pcode = filter_input(INPUT_GET, 'pcode', FILTER_SANITIZE_NUMBER_INT);
    if( !$f_duid )$f_duid = filter_input(INPUT_GET, 'duid', FILTER_SANITIZE_NUMBER_INT);
    if( !$f_nr )$f_nr = filter_input(INPUT_GET, 'nr', FILTER_SANITIZE_NUMBER_INT);
    if( !$f_dso_bs )$f_dso_bs = filter_input(INPUT_GET, 'dso_bs', FILTER_SANITIZE_NUMBER_INT);
    if( $lab_g == null )$lab_g = filter_input(INPUT_GET, 'lab_g', FILTER_SANITIZE_NUMBER_INT);
    
    $f_auid = $ht->postget('auid',false,FILTER_SANITIZE_NUMBER_INT);
    if($isagent){
        $f_auid = $auId;
    }
    if($isdoctor){
        $f_duid = $duId;
    }
    
    $hiddens['order'] = $orderby;
    $hiddens['pcode'] = $f_pcode;
//    $hiddens['pfn'] = $f_pfn;
//    $hiddens['pln'] = $f_pln;
    $hiddens['duid'] = $f_duid;
    $hiddens['auid'] = $f_auid;
    $hiddens['nr'] = $f_nr;
    $hiddens['dso_bs'] = $f_dso_bs;
    $hiddens['lab_g'] = $lab_g;
    
    $selo =  filter_input(INPUT_POST, 'selo', FILTER_SANITIZE_NUMBER_INT,FILTER_REQUIRE_ARRAY); // select order
    $selo_d =  filter_input(INPUT_POST, 'selo-d', FILTER_SANITIZE_NUMBER_INT,FILTER_REQUIRE_ARRAY); // defoult
    $mo_bs = filter_input(INPUT_POST, 'mo_bs', FILTER_SANITIZE_NUMBER_INT);
    $upstat=[];
    
    $r_access = [];
    $r_access [] ='administrator';
    $r_access [] ='ml_administrator';
    $r_access [] ='ml_manager';
    $r_access [] ='ml_doctor';
    $r_access [] ='ml_procedurecab';
    if($ht->access($r_access)){
        if($mo_bs && $selo_d && $selo){
            $upstat[]=1;
            foreach($selo as $orderId){
                $upstat[]=2;
                if($orderId && is_numeric($orderId)){
                    $upstat[]=3;
                    if($mo_bs == 1){
                        $upstat[]=4;
                        update_post_meta( $orderId, 'dso_dbonus_state', 1 );
                    }
                    if($mo_bs == 2){
                        $upstat[]=5;
                        update_post_meta( $orderId, 'dso_dbonus_state', 0 );
                    }
                }
            }
        }
    }
//    add_log($upstat);
    
//    $pending_reservations = $wpdb->get_results(' 
//    SELECT booking_calendars.cal_name
//    FROM '.$wpdb->prefix.'booking_calendars AS booking_calendars
//    INNER JOIN '. $wpdb->prefix.'booking_reservation AS booking_reservations
//    ON booking_calendars.id =  booking_reservations.calendar_id
//    WHERE status LIKE "pending"');

    
//    add_log($_POST);
$tpl__i_=<<<td
                    <div class="row">
                        <div class="col-12">
                            <label class="mb-0 mt-2" for="__for__">__label__</label>
                        </div>
                        <div class="col-12">
                            <input id="__id__" type="text" name="__name__"
                                value="__val__"
                                class="form-control __i_class__"
                                placeholder="__placeholder__" />
                        </div>
                    </div>
td;
function bFormField($label='',$name='',$val='',$placeholder='',$type='text',$class=''){
    global $ht;
    $at=[];
    $at['id'] = 'fi_'.$name;
    $at['name'] = $name;
    $at['class'] = 'form-control '.$class;
    $at['placeholder'] = $placeholder;
    $at['type'] = $type;
    $at['value'] = $val;
    $c = $ht->f('input','',$at);
    $at=[];
    $at['class'] = 'col-12 ';
    $c = $ht->f('div',$c,$at);
    
    $at=[];
    $at['class'] = 'mb-0 mt-2';
    $at['for'] = 'fi_'.$name;
    $l = $ht->f('label',$label,$at);
    $at=[];
    $at['class'] = 'col-12 ';
    $l = $ht->f('div',$l,$at);
    
    $at=[];
    $at['class'] = 'row ';
    $c = $ht->f('div',$l.$c,$at);
    return $c;
}

    $f_i_ucode = bFormField('Код карты','pcode',$f_pcode,'');
//    $f_i_fname = bFormField('Имя','pfn',$f_pfn,'');
//    $f_i_sname = bFormField('Фамилия','pln',$f_pln,'');
//    $f_i_oid = bFormField('Id заказа','oid',$f_oid,'');
    $f_i_nr = bFormField('Номер заявки','nr',$f_nr,'');

            $var_pu=[];
            $var_pu['items'] = DShopExtensionMedLab::_get_doctors();
            $var_pu['items'][0] = 'Не указан';
            $var_pu['option_name'] = 'duid';//doctor_id
            $var_pu['class'] = 'form-control    ';
            $var_pu['id'] = 'field_dso_duid';
    //        $var_pu['post_id'] = ''; // object
    //        $f_patient = DShopExtensionMedLab::_dshf_select($var_pu);
            $var_pu['res'] = $duId;
    //        $dsP = new DShopPayment();
    //        $f_patient = $dsP->_cf_select($var_pu);
    //        $dsP = new DShopPayment();
            $f_sel_docter= DShopExtensionMedLab::_dshf_select_free($var_pu,false,$isagent);

            $var_pu=[];
            $var_pu['items'] = DShopExtensionMedLab::_get_agents();
            $var_pu['items'][0] = 'Не указан';
            $var_pu['option_name'] = 'auid';//doctor_id
            $var_pu['class'] = 'form-control    ';
            $var_pu['id'] = 'field_dso_auid';
    //        $var_pu['post_id'] = ''; // object
    //        $f_patient = DShopExtensionMedLab::_dshf_select($var_pu);
            $var_pu['res'] = $auId;
    //        $dsP = new DShopPayment();
    //        $f_patient = $dsP->_cf_select($var_pu);
    //        $dsP = new DShopPayment();
            $f_sel_agent= DShopExtensionMedLab::_dshf_select_free($var_pu);
            
/*
 * /////////////////////
 * форма даты
 */
//add_log($p,'dump');

/*
 * 
<!--            <div class="col-3 text-left">
                <?=$f_i_fname?>
            </div>
            <div class="col-3 text-left">
                <?=$f_i_sname?>
            </div>
            <div class="col-3 text-left">
                <?=$f_i_oid?>
            </div>-->
 * 
 */
?>
<div class="row">
    <form action="<?=get_the_permalink( 44 ).$p?>" method="post" class="col-12 text-left">
        <?php
        
    echo $ht->f('input','',['type'=>'hidden','name'=>'order','value'=>$orderby])."\n";
    echo $ht->f('input','',['type'=>'hidden','name'=>'count','value'=>$count])."\n";
    echo $ht->f('input','',['type'=>'hidden','name'=>'offset','value'=>$offset])."\n";
        ?>
        <div class="row">
<!--            <div class="col-12">
                <?php
//                        var_dump( $lab_g);
                ?>
            </div>-->
<?php

    $r_access = [];
    $r_access [] ='administrator';
    $r_access [] ='ml_administrator';
    $r_access [] ='ml_manager';
//    $r_access [] ='ml_doctor';
    $r_access [] ='ml_agent';
    $r_access [] ='ml_procedurecab';
    if($ht->access($r_access)){
?>
            <div class="col-12 text-left">
                <div class="row">
                    <div class="col-12 text-left">
                        <label class="mb-0 mt-2">Фильтровать по доктору</label>
                    </div>
                    <div class="col-12">
                        <?=$f_sel_docter?>
                    </div>
                </div>
            </div>
<?php
    }

    $r_access = [];
    $r_access [] ='administrator';
    $r_access [] ='ml_administrator';
    $r_access [] ='ml_manager';
//    $r_access [] ='ml_doctor';
    $r_access [] ='ml_procedurecab';
    if($ht->access($r_access)){
?>
            <div class="col-12 text-left">
                <div class="row">
                    <div class="col-12 text-left">
                        <label class="mb-0 mt-2">Фильтровать по представителю</label>
                    </div>
                    <div class="col-12">
                        <?=$f_sel_agent?>
                    </div>
                </div>
            </div>
<?php
    }
?>
            <div class="col-3 text-left">
                <?=$f_i_ucode?>
            </div>
            <div class="col-3 text-left">
                <?=$f_i_nr?>
            </div>
                
            <div class="col-3 text-left">
                <div class="row">
                    <div class="col-12 text-left">
                        <label class="mb-0 mt-2">Дата от</label>
                    </div>
                    <div class="col-12">
                        <input type="text" name="date-from" id="from" value="<?=$date_from?>">
                    </div>
                </div>
            </div>
            <div class="col-3 text-left">
                <div class="row">
                    <div class="col-12 text-left">
                        <label class="mb-0 mt-2">Дата до</label>
                    </div>
                    <div class="col-12">
                        <input type="text" name="date-to" id="to" value="<?=$date_to?>">
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="customRadioInline1" name="dso_bs" class="custom-control-input" value="0"
                           <?=$f_dso_bs==0?'checked="checked"':''?>>
                    <label class="custom-control-label" for="customRadioInline1">Все</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="customRadioInline2" name="dso_bs" class="custom-control-input" value="1"
                           <?=$f_dso_bs==1?'checked="checked"':''?>>
                    <label class="custom-control-label" for="customRadioInline2">Выплачено</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="customRadioInline3" name="dso_bs" class="custom-control-input" value="2"
                           <?=$f_dso_bs==2?'checked="checked"':''?>>
                    <label class="custom-control-label" for="customRadioInline3">Не выплачен о</label>
                </div>
            </div>
<?php
    

    $r_access = [];
    $r_access [] ='administrator';
    $r_access [] ='ml_administrator';
    $r_access [] ='ml_manager';
//    $r_access [] ='ml_doctor';
    $r_access [] ='ml_procedurecab';
    if($ht->access($r_access)){
?>
            <div class="col-6">
                <div class="row">
                    <div class="col-12 text-left">
                        <label class="mb-0 mt-2">Группа лаборантов</label>
                    </div>
                    <div class="col-12">
                        <?php
//                        $sel_g = ProfileFields::get_lab_group_list();
                        $sel_g = MedLabLabGroupFields::get_lab_group_list();
        //                $ht->form_method='post';
                        $c = $ht->select('lab_g',$sel_g,0,'form-control'); // mass operation bonuses states
                        echo $c;
            //            add_log(get_class_methods ($ht) );
                        ?>
                    </div>
                </div>
            </div>
<?php
    }
?>
            <div class="col-12 text-left">
                <button type="sumbit" class="btn btn-primary mt-3 mb-3">Применить</button>
            </div>
        </div>
    </form>
    <div class="col-3">
        <?=$date_from||$date_to?'Выборка заказов ':''?>
    </div>
    <div class="col-3">
        <?=$date_from?'С '.$date_from:''?>
    </div>
    <div class="col-3">
        <?=$date_to?'По '.$date_to:''?>
    </div>
</div>
<?php
if( !$isdoctor && !$isagent){
?>
<form action="<?=get_the_permalink( 44 ).$p?>" method="post" class="">
    <div class="row" >
        <div class="col-3 pt-2">
            Массовые операции:
        </div>
        <div class="col-3   pt-2">
            <?php
            $operations = [];
            $operations [0]= '--';
            $operations [1]= 'Выплачен';
            $operations [2]= 'Не выплачен';
            $ht->form_method='post';
            $c = $ht->select('mo_bs',$operations); // mass operation bonuses states
            echo $c;
//            add_log(get_class_methods ($ht) );
            ?>
        </div>
        <div class="col-3">
            <button type="sumbit" class="btn btn-primary -mt-3 -mb-3">Применить</button>
        </div>
    </div>
<?php
}

foreach($hiddens as $hn=>$hv){
    echo $ht->f('input','',['type'=>'hidden','name'=>$hn,'value'=>$hv])."\n";
    if($hn != 'order') $urlget[$hn] = $hv;
}


$items_per_page = 50;
$page = isset($_GET['page']) && (int)$_GET['page'] > 1 ? (int)$_GET['page'] : 1;
$where = [];
$order = '`p`.`vip` DESC, `ec`.`date` DESC';
$order_ = false;
$orders=[];
$orders['0a']='p.post_date asc';
$orders['0d']='p.post_date desc';
$orders['1a']='um.meta_value asc';
$orders['1d']='um.meta_value desc';
$orders['2a']='nr.meta_value asc';
$orders['2d']='nr.meta_value desc';
$orders['3a']='`ec`.`date` asc';
$orders['3d']='`ec`.`date` desc';
$orders['4a']='`p`.`vip` asc';
$orders['4d']='`p`.`vip` desc';
    
$order = 'p.post_date desc';
if ($ht->get('order',false)) {
    $order__ =$ht->get('order',false);
    if($orders && array_key_exists($order__,$orders)){
        $order_ = $order__;
        $order = $orders[$order_];
    }
}else
if ($ht->post('order',false)) {
    $order__ =$ht->post('order',false);
    if($orders && array_key_exists($order__,$orders)){
        $order_ = $order__;
        $order = $orders[$order_];
    }
}

$data = [];

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
    $order_val = false;
    $q_order_by = 'p.post_date desc';
    if($orderby == '0a'){
//        $order = [
//        'order' => 'ASC',
//        'orderby' => 'meta_value',
//        'meta_query' => array(
//            array('key' => 'order_in_archive'))
//        ];
        $order_val = [
        'order' => 'ASC',
        'orderby' => 'date'
        ];
        $args = $order_val+$args;
        $q_order_by = 'p.post_date asc';
    }
    if($orderby == '0d'){
        $order_val = [
        'order' => 'DESC',
        'orderby' => 'date'
        ];
        $args = $order_val+$args;
        $q_order_by = 'p.post_date desc';
    }
    if($orderby == '2a'){
        $order_val = [
        'order' => 'ASC',
        'orderby' => 'meta_value',
        'meta_query' => array(
            array('key' => 'dso_query_nr'))
        ];
        $args = $order_val+$args;
        $q_order_by = 'nr.meta_value asc';
    }
    if($orderby == '2d'){
        $order_val = [
        'order' => 'DESC',
        'orderby' => 'meta_value',
        'meta_query' => array(
            array('key' => 'dso_query_nr'))
        ];
        $args = $order_val+$args;
        $q_order_by = 'nr.meta_value desc';
    }
    if($orderby == '1a'){
        $q_order_by = 'um.meta_value asc';
    }
    if($orderby == '1d'){
        $q_order_by = 'um.meta_value desc';
    }
//    add_log($args);
    $_posts = get_posts( $args );
    
		global $wpdb;
//        $newtable = $wpdb->get_results( "SELECT id FROM newtable" );
        
$meta_key1 = 'model';
$meta_key2 = 'year';
$meta_key3 = 'manufacturer';
$meta_key3_value = 'Ford';

$join = [];
$where=[];
if($f_pcode)$where[]=" um.meta_value =  '$f_pcode'";
if($f_nr)$where[]=" nr.meta_value =  '$f_nr'";
if($f_duid)$where[]=" d.meta_value =  '$f_duid'";
if($f_auid){
    $where[]=" a.meta_key = 'dso_auid'";
    $where[]=" a.meta_value =  '$f_auid'";
    $join[]=" LEFT join $wpdb->postmeta a on a.post_id = p.ID";
}
if($date_from){
    $from = explode('.',$date_from);
    $where[]=sprintf("p.post_date >= '%d-%d-%d 00:00:00'",$from[2],$from[1],$from[0]);
}
if($date_to){
    $to = explode('.',$date_to);
    $where[]=sprintf("p.post_date <= '%d-%d-%d 23:59:59'",$to[2],$to[1],$to[0]);
}
if($f_dso_bs){
    $join[]="LEFT join $wpdb->postmeta bs on bs.post_id = p.ID";
    $where[]=" bs.meta_key = 'dso_dbonus_state'";
    if($f_dso_bs == 1)$where[]=" bs.meta_value =  '1'";
    if($f_dso_bs == 2)$where[]=" bs.meta_value <>  '1'";
}
//if($lab_g >-1){
//if($lab_g  !== null && $lab_g >-1){
if($lab_g){
    $join[]="LEFT join $wpdb->postmeta lb on lb.post_id = p.ID";
    $where[]=" lb.meta_key = 'dso_sender'";
    $join[]="LEFT join  $wpdb->users su on su.ID = lb.meta_value";
    $join[]="LEFT JOIN $wpdb->usermeta lg ON lg.user_id = lb.meta_value";
    $where[]=" lg.meta_key = 'lab_group'";
    $where[]=" lg.meta_value = '$lab_g'";
}
$join=implode("\n    ",$join);
$where=implode("\nand ",$where);
if($where) $where = 'and '.$where;

$q = "SELECT count(p.ID) FROM $wpdb->posts p
    LEFT join $wpdb->postmeta pm on pm.post_id = p.ID
    LEFT join $wpdb->postmeta nr on nr.post_id = p.ID
    LEFT join $wpdb->postmeta d on d.post_id = p.ID
    LEFT join  $wpdb->users u on u.ID = pm.meta_value
    LEFT JOIN $wpdb->usermeta um ON um.user_id = u.ID
    $join
    WHERE post_type = 'dsorder' AND post_status = 'publish'
    and pm.meta_key = 'dso_puid'
    and nr.meta_key = 'dso_query_nr'
    and d.meta_key = 'dso_duid'
    and um.meta_key = 'card_numer' 
    $where
    order by $order
        ";
$dso_count = $wpdb->get_var($q);
//add_log($ht->pre($dso_count));

$q = "SELECT p.ID FROM $wpdb->posts p
    LEFT join $wpdb->postmeta pm on pm.post_id = p.ID
    LEFT join $wpdb->postmeta nr on nr.post_id = p.ID
    LEFT join $wpdb->postmeta d on d.post_id = p.ID
    LEFT join  $wpdb->users u on u.ID = pm.meta_value
    LEFT JOIN $wpdb->usermeta um ON um.user_id = u.ID
    $join
    WHERE post_type = 'dsorder' AND post_status = 'publish'
    and pm.meta_key = 'dso_puid'
    and nr.meta_key = 'dso_query_nr'
    and d.meta_key = 'dso_duid'
    and um.meta_key = 'card_numer' 
    $where
    order by $order
    ";
//add_log($ht->pre($hiddens));
//add_log($ht->pre($q));

if(0){
$postids = $wpdb->get_col($wpdb->prepare("
SELECT      key3.post_id
FROM        $wpdb->postmeta key3
INNER JOIN  $wpdb->postmeta key1
			on key1.post_id = key3.post_id
			and key1.meta_key = %s
INNER JOIN  $wpdb->postmeta key2
			on key2.post_id = key3.post_id
			and key2.meta_key = %s
WHERE       key3.meta_key = %s
			and key3.meta_value = %s
ORDER BY    key1.meta_value, key2.meta_value",$meta_key1, $meta_key2, $meta_key3, $meta_key3_value)); 
}

$postids = $wpdb->get_col($q);
//add_log($postids);

    $total = 0;$total_b=0;
$showD = true; //  show doctor id
echo $ht->f('div','Найдено '.$dso_count.' записей',['class'=>'col-12']);
if (10 &&  $postids) {
//  echo 'List of ' . $meta_key3_value . '(s), sorted by ' . $meta_key1 . ', ' . $meta_key2;
    $num = 1;
    
    $total = 0;$total_b=0;
  foreach( $postids as $id ){
	$dsorder = get_post(intval($id));
    
    /*
	setup_postdata($post);
	?>
	<p><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></p>
	<?php
    /**/
    
//  }
//}
//if(1){
//    foreach( $_posts as $dsorder ){
        setup_postdata($dsorder);
        $orderId = $dsorder->ID;

        $date = date( "d.m.y", strtotime( $dsorder->post_date ) );
        
        
        $state_ =  get_post_meta( $dsorder->ID, 'dso_status', true );
        $dso_total_ =  get_post_meta( $dsorder->ID, 'dso_cost', true );
        $dso_puid =  get_post_meta( $orderId, 'dso_puid', true );
        $dso_duid =  get_post_meta( $orderId, 'dso_duid', true );
        $dso_query_id =  get_post_meta( $orderId, 'dso_query_id', true );
        $dso_query_nr =  get_post_meta( $orderId, 'dso_query_nr', true );
        $card_numer = esc_attr(get_the_author_meta('card_numer', $dso_puid));
        $dso_dbonus_val =  (int)get_post_meta( $orderId, 'dso_dbonus_val', true );
        $dso_dbonus_state =  (int)get_post_meta( $orderId, 'dso_dbonus_state', true );
        
        $total += $dso_total_;$total_b += $dso_dbonus_val;
    
        $at=[];
        $at['name']='selo-d['.$orderId.']';
        $at['value']=''.$orderId.'';
        $at['type']='hidden';
        $check_d  =  $ht->f('input','',$at);
        
        $at=[];
        $at['id']='selo-'.$orderId.'';
        $at['class']='solo solo-g';
        $at['name']='selo['.$orderId.']';
        $at['value']=''.$orderId.'';
        if($selo && in_array($orderId,$selo))
            $at['checked']='checked';
        $at['type']='checkbox';
        $check = '';
        if( !$isdoctor && !$isagent){
            $check  =  $ht->f('input','',$at);
        }
        
        $at=[];
        $at['for']='selo-'.$orderId.'';
        $at['class']='m-0 solo solo-l';
        $check  =  $check_d.$ht->f('label',$check.$num,$at);
        $date  =  $ht->f('label',$date,$at);
        $card_numer  =  $ht->f('label',$card_numer,$at);
        $dso_query_nr  =  $ht->f('label',$dso_query_nr,$at);
        $dso_total_  =  $ht->f('label',$dso_total_,$at);
        $dso_dbonus_val  =  $ht->f('label',$dso_dbonus_val,$at);

        $item = [];
        $item[]=$check;
        $item[]=$date;
        $item[]=$card_numer;
        $item[]=$dso_query_nr;
        $item[]=$dso_total_;
        $item[]=$dso_dbonus_val;
        $item[]=$dso_dbonus_state?'Выплачен':'Не выплачен';
        if($showD)
        $item[]=$dso_duid;
        $data[]=$item;
        
        $num++;
    }
}
$itemTotal = [];
$it=[];
$it['class'] = 'col-7';
$it['val'] = 'Всего:';
$itemTotal[] = $it;
$it['class'] = 'col-1';
$it['val'] = $total;
$itemTotal[] = $it;
$it['class'] = 'col-1';
$it['val'] = $total_b;
$itemTotal[] = $it;
$data = array_merge([$itemTotal],$data,[$itemTotal]);

//add_log($selo);
$rows = [];
        
        $at=[];
        $at['id']='selo-g';
        $at['class']='solo solo-gg';
        $at['name']='selo-g';
        $at['value']='1';
//        if($selo && in_array($orderId,$selo))
//            $at['checked']='checked';
        $at['type']='checkbox';
        $check = '';
        if( !$isdoctor && !$isagent){
            $check  =  $ht->f('input','',$at);
        }

$hitems = [];
$hitems[]='<label>'.$check.'№</label>';
$hitems[]='Дата';
$hitems[]='Номер карты';
$hitems[]='Номер заказа';
$hitems[]='Сумма заказа';
$hitems[]='% бонуса';
$hitems[]='Состояние выплаты';
if($showD)
$hitems[]='doctor id';

    $usenumbers = true;
    $usenumbers = false;

$inorder = [];
//$inorder[] = 0-!$usenumbers;
$inorder[] = 2;
$inorder[] = 3;
$inorder[] = 4;
//$inorder[] = 7;
//$inorder[] = 1-!$usenumbers;
//$inorder[] = 2-!$usenumbers;
//$inorder[] = 3-!$usenumbers;
//$inorder[] = 6-!$usenumbers;

$test_cols = false;
if($test_cols ){
//$items = [];
$item = [];
$item[]='_csize_';
$item[]='_ncol_';
$item[]='hi';
$item[]='hi';
$item[]=$ht->mod;
$item[]='hi';
$data[]=$item;

$item = [];
$item[]='_csize_';
$item[]='_ncol_';
$item[]=$ht->mod;
$item[]='hi';
$item[]=$ht->mod;
$item[]='hi';
$data[]=$item;

$item = [];
$item[]='_csize_';
$item[]='_ncol_';
$item[]='hi';
$item[]=$ht->mod;
$item[]='hi';
$item[]=$ht->mod;
$data[]=$item;

$item = [];
$item[]='_csize_';
$item[]='_ncol_';
$item[]='hi';
$item[]=$ht->mod;
$item[]='hi';
$item[]=$ht->mod;
$data[]=$item;
}

$cclass=[]; // coll class
$cclass[0] = 1;
$cclass[1] = 2;
$cclass[] = 2;
$cclass[] = 2;
$cclass[] = 1;
$cclass[] = 1;
$cclass[] = 2;

//echo $ht->pre($urlget);
    $defs =[];
    $defs['usenumbers'] = $usenumbers;
    $defs['cclass'] = $cclass;
    $defs['hitems'] = $hitems;
    $defs['inorder'] = $inorder;
    $defs['orders'] = $orders;
    $defs['sortVName'] = 'order';
    $defs['urlget'] = $urlget;
    $defs['ma']='↓';
    $defs['md']='↑';
    $defs['sortClass']='btn';
    $defs['data'] = $data;
    
    echo $ht->btabl($defs);

//echo $c;

if( !$isdoctor && !$isagent){
    echo '</form>';
}
include __DIR__. '/../component/tpls/tpl-calendar-init.php';
?>
<script>
    function setselog (){
        var selog = document.getElementById('selo-g');
        var sgs = document.querySelectorAll('.solo-g');
        for(var i = 0;i<sgs.length;i++){
            if(selog.checked == true){
    //            val sch = selog.checked ;
                $(sgs[i]).prop('checked', true);
            }else{
    //            val sch = selog.checked ;
                $(sgs[i]).prop('checked', false);
            }
        }
    }
var selog = document.getElementById('selo-g');
if(selog){
    selog.onclick = setselog;
}
</script>

