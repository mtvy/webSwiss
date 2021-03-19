<?php

/* 
 * check-query.php
 * [medlab  tpl="check-query"]
 */

global $ht;


  global $ip,$port;

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
    
    
$duid = null; // uid doctor

$duId = filter_input(INPUT_GET, 'duid', FILTER_SANITIZE_NUMBER_INT);
if($duId===false || $duId===null|| $duId==='')
//    $duId=get_current_user_id();//'0';
    $duId=false;//'0';
$_duId = filter_input(INPUT_POST, 'duid', FILTER_SANITIZE_NUMBER_INT);
if(strlen($_duId)>0)$duId=$_duId;

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
    $oid = filter_input(INPUT_POST, 'oid', FILTER_SANITIZE_NUMBER_INT);
    $f_dso_bs = filter_input(INPUT_POST, 'dso_bs', FILTER_SANITIZE_NUMBER_INT);
    $lab_g = filter_input(INPUT_POST, 'lab_g', FILTER_SANITIZE_NUMBER_INT);

    if( !$f_pcode )$f_pcode = filter_input(INPUT_GET, 'pcode', FILTER_SANITIZE_NUMBER_INT);
    if( !$f_duid )$f_duid = filter_input(INPUT_GET, 'duid', FILTER_SANITIZE_NUMBER_INT);
    if( !$f_nr )$f_nr = filter_input(INPUT_GET, 'nr', FILTER_SANITIZE_NUMBER_INT);
    if( !$f_dso_bs )$f_dso_bs = filter_input(INPUT_GET, 'dso_bs', FILTER_SANITIZE_NUMBER_INT);
    if( $lab_g == null )$lab_g = filter_input(INPUT_GET, 'lab_g', FILTER_SANITIZE_NUMBER_INT);
    
    $hiddens['order'] = $orderby;
    $hiddens['pcode'] = $f_pcode;
//    $hiddens['pfn'] = $f_pfn;
//    $hiddens['pln'] = $f_pln;
    $hiddens['duid'] = $f_duid;
    $hiddens['nr'] = $f_nr;
    $hiddens['oid'] = $oid;
    $hiddens['dso_bs'] = $f_dso_bs;
    $hiddens['lab_g'] = $lab_g;
    
    
    

function bFormField($label='',$name='',$val='',$placeholder='',$type='text',$class=''){
    global $ht;
    $use_data_list = false;
    if($type == 'text-datalist'){
        $type = 'text';
        $use_data_list = true;
    }
    $at=[];
    $at['id'] = 'fi_'.$name;
    $at['name'] = $name;
    $at['class'] = 'form-control '.$class;
    $at['placeholder'] = $placeholder;
    $at['type'] = $type;
    $at['value'] = $val;
    if($use_data_list){
        $at['list'] = 'fi_'.$name.'_dl';
    }
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
    
    $dl = '';
    if($use_data_list){
        $list=[];
        $dl_id = 'fi_'.$name.'_dl';
        if(!isset($_SESSION['fields-cash'])){
            $_SESSION['fields-cash'] = [];
        }
        if(!isset($_SESSION['fields-cash']['datalist'])){
            $_SESSION['fields-cash']['datalist'] = [];
        }
        if(!isset($_SESSION['fields-cash']['datalist'][$dl_id])){
            $_SESSION['fields-cash']['datalist'][$dl_id] = [];
        }
        $_SESSION['fields-cash']['datalist'][$dl_id][$val] = $val;
        $list = $_SESSION['fields-cash']['datalist'][$dl_id];
        $dl = $ht->datalist($dl_id, $list);
    }
    
    $at=[];
    $at['class'] = 'row ';
    $c = $ht->f('div',$l.$c.$dl,$at);
    return $c;
}

    $f_i_ucode = bFormField('Код карты','pcode',$f_pcode,'');
//    $f_i_fname = bFormField('Имя','pfn',$f_pfn,'');
//    $f_i_sname = bFormField('Фамилия','pln',$f_pln,'');
//    $f_i_oid = bFormField('Id заказа','oid',$f_oid,'');
    $f_i_nr = bFormField('Номер заявки','nr',$f_nr,'');
    $f_i_oid = bFormField('ID заказа','oid',$oid,'','text-datalist');

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
            $f_sel_docter= DShopExtensionMedLab::_dshf_select_free($var_pu);
            
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
    <form action="<?=get_the_permalink( 1948 ).$p?>" method="post" class="col-12 text-left">
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
                <?php /** / ?>
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
            <div class="col-3 text-left">
                <?=$f_i_ucode?>
            </div>
                <?php /**/ ?>
            <div class="col-3 text-left">
                <?=$f_i_oid?>
            </div>
            <div class="col-3 text-left">
                <?=$f_i_nr?>
            </div>
                <?php /** / ?>
                
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
            <div class="col-6">
                <div class="row">
                    <div class="col-12 text-left">
                        <label class="mb-0 mt-2">Группа лаборантов</label>
                    </div>
                    <div class="col-12">
                        <?php
                        $sel_g = ProfileFields::get_lab_group_list();
                        $sel_g = MedLabLabGroupFields::get_lab_group_list();
        //                $ht->form_method='post';
                        $c = $ht->select('lab_g',$sel_g,-1,'form-control'); // mass operation bonuses states
                        echo $c;
            //            add_log(get_class_methods ($ht) );
                        ?>
                    </div>
                </div>
            </div>
                <?php /**/ ?>
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
<?php if(0){?>
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
if($oid>0 || $f_nr>0){
                    $q = 'query-edit-referral';
                    $q = 'query-edit-referral';
                    $q = 'query-referral-results';
                    $q = 'query-referral-results';
                    $atts = [];
                    $atts['is_show_test'] = true;
                    $query=[];
                    $numgroup = 9950000000;
                    $num = $numgroup;
                    
                    // имея id заказа, получаем номер заявки
                    if($oid>0){
                        $orderId =  $oid;
                        $num = $num + $orderId;
                        $num = apply_filters( 'medlab_num_query_get', $num, $orderId, $numgroup );
                        add_log('medlab_num_query_get<pre>'.htmlspecialchars(print_r($num,1)).'</pre>');
                    }
                    
//                    $query['MisId'] = $orderId;
//                    $query['Nr'] = $num;
                    if($oid)$query['MisId'] = $oid; // номр заказа
                    if($f_nr)$query['Nr'] = $f_nr; // номер заявки
                    
                    // имея id заказа, получаем id заявки
                    if($oid)$query['LisId'] = get_post_meta( $orderId, 'dso_query_id', true ); // id заявки
                    
                    $atts['query'] = $query;
                    
                    
                    /*         определение доступка к лис        */
                    
                    // глобальные настройки соединения
                    global $ml_ip,$ml_port;
                    global $ml_pass,$ml_sender;
                    
                    // группа, инициализировавшая запрос
                    $gid = (int) get_post_meta( $orderId, 'dso_ml_group', true );
                    
//                    if(!$gid){
//                        $uid = get_current_user_id();
//                        $gid = (int) get_user_meta($uid,'lab_group',1);
//                    }
                    
                    // инициализация настроек соединения
                    set_ml_access_by_group($gid);
                    
                    // отображение настроек соединения
                    if(current_user_can('manage_options')){
                        $group = '';
                        if($gid>0){
                            $sel_g = MedLabLabGroupFields::get_lab_group_list();
                            $group = $sel_g[$gid];
                        }
                        add_log('текущий id группы: '.$gid);
                        add_log('группа: '.$group);
                        add_log('текущий ip лис: '.$ml_ip);
                        add_log('текущий отправитель: '.$ml_sender);
                    }
                    
                    /*        / определение доступка к лис        */
                    
                    // построение запроса для показа
                    if(current_user_can('manage_options')){
//                        unset($atts['is_show_test']);
                    }
                    $data_log = MedLab::_queryBuild($q,$atts); 
//                    add_log('$data_<pre>'.htmlspecialchars(print_r($data_,1)).'</pre>');
                    
                    // построение запроса, для запроса
                    unset($atts['is_show_test']);
                    $data_ = MedLab::_queryBuild($q,$atts);
                    
//                    if(current_user_can('manage_options')){
//                        add_log('текущий ip лис: '.$ml_ip);
//                        add_log('текущий отправитель: '.$ml_sender);
//                    }
                    add_log('$data_<pre>'.htmlspecialchars(print_r($data_log,1)).'</pre>'); // структура запроса
                    
                    // отправка запроса и получение ответа
                    $answer = doPostRequest($data_);
                    $xml = simplexml_load_string($answer);
                    $qrootAtt = MedLab::_buildAttrs($xml);
                    
//                    add_log('$answer<pre>'.htmlspecialchars(print_r($answer,1)).'</pre>');
                    $answer_  =  print_r($xml,1);
//                    update_post_meta( $orderId, 'dso_q__answer', print_r($xml,1) );
//                    $answer_ =  get_post_meta( $orderId, 'dso_q__answer', true );
                    echo('$answer_<pre>'.htmlspecialchars(print_r($answer_,1)).'</pre>');
                    add_log('Номер в лаборатории LisId: <pre>'.htmlspecialchars(print_r(''.$xml->Referral['LisId'],1)).'</pre>');
                    add_log('Номер заказа Nr: <pre>'.htmlspecialchars(print_r(''.$xml->Referral['Nr'],1)).'</pre>');
                    
                    
                    if(!isset($qrootAtt['Error']) ){ // && isset($xml->Referral['LisId'])
//                        add_log(isset($qrootAtt['Error']).' == !isset($qrootAtt[Error])');
                        if($qrootAtt['MessageType'] == 'result-import-referral' ){
////                            add_log($qrootAtt['MessageType'].' == result-import-referral');
//                            update_post_meta( $orderId, 'dso_query_id', ''.$xml->Referral['LisId'] );
//                            update_post_meta( $orderId, 'dso_query_nr', ''.$xml->Referral['Nr'] );
//                            update_post_meta( $orderId, 'dso_query_status', 'sent' );
//                            $state_ =  get_post_meta( $orderId, 'dso_status', true );
//                            $status = 'query_sent';
//                            if($state_=='send_query')$status = 'query_sent';
//                            if($state_=='change_query')$status = 'query_sent';
//                            update_post_meta( $orderId, 'dso_status', $status );
//                            $user = wp_get_current_user();
//                            update_post_meta( $orderId, 'dso_sender', $user->ID );
//                            $num = apply_filters( 'medlab_num_query_reset', $orderId, false );
//                        }else{
//                            $num = apply_filters( 'medlab_num_query_reset', $orderId, true );
////                            add_log($qrootAtt['MessageType'].' != result-import-referral');
                        }
                    }
                    if(isset($xml->Warnings) && isset($xml->Warnings->Item) ){
                        $Items = $xml->Warnings->Item;
                        if(!is_array($Items)){
                            $Items = [ $Items ];
                        }
//                                add_log('count an bm : '.count($an_bm_));

                        foreach($Items as $Item){
                            $mess = $Item['Text'];
                            add_log('Warnings: '.$mess);
                        }
                    }
                    
}