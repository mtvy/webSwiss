<?php

/* 
 * ml-list-answer.php
 * [ml_component tpl="ml-list" type="answer"]
 */


/* 
 * ml-list-querys.php
 * [ml_component tpl="ml-list" type="answer"]
 */

global $ht;
/*
 * 
Warning: fopen(http://213.230.71.167:9901/): 
 * failed to open stream: Connection timed out in /home/swissl01/public_html/wp-content/plugins/medlab/connect.php on line 425

Fatal error: Using $this when not in object context in /home/swissl01/public_html/wp-content/plugins/medlab/connect.php on line 406
 */
                                    $test_email =get_option('ds_notification_test')=='1'; // тестирование отправки почты
//                                    add_log($test_email,'dump');

$date_from='';
$date_to='';
$order='';
$f_GrCode='';
$f_tests='';
$test='';

$current_pageid = 502;
$current_pageid = 309;

//    $inputs_['repass'] = array('filter'=>FILTER_VALIDATE_REGEXP,
//        'options' => array('regexp' => '/^(?=\S*\d)(?=\S*[a-zA-Zа-яА-ЯЁёЄєЇї])\S{8,}$/'));
//FILTER_VALIDATE_REGEXP
//FILTER_SANITIZE_STRING
$date_from = filter_input(INPUT_GET, 'date-from', FILTER_VALIDATE_REGEXP,['options' =>['regexp' => '/^[\d]{2}\.[\d]{2}\.[\d]{4}$/']]);
if(!$date_from)$date_from='';
$date_to = filter_input(INPUT_GET, 'date-to', FILTER_VALIDATE_REGEXP,['options' =>['regexp' => '/^[\d]{2}\.[\d]{2}\.[\d]{4}$/']]);
if(!$date_to)$date_to='';
$date_from = filter_input(INPUT_POST, 'date-from', FILTER_VALIDATE_REGEXP,
        ['options' =>['default'=>$date_from, 'regexp' => '/^[\d]{2}\.[\d]{2}\.[\d]{4}$/']]);
if(!$date_from)$date_from='';
$date_to = filter_input(INPUT_POST, 'date-to', FILTER_VALIDATE_REGEXP,
        ['options' =>['default'=>$date_to, 'regexp' => '/^[\d]{2}\.[\d]{2}\.[\d]{4}$/']]);
if(!$date_to)$date_to='';


$order = filter_input(INPUT_GET, 'an', FILTER_VALIDATE_REGEXP,['options' =>['regexp' => '/^[\d]{1,3}\-[\d]{1,4}$/']]);
if(!$order)$order='';
$order = filter_input(INPUT_POST, 'an', FILTER_VALIDATE_REGEXP,
        ['options' =>['default'=>$order, 'regexp' => '/^[\d]{1,3}\-[\d]{1,4}$/']]);

$f_GrCode = filter_input(INPUT_GET, 'gc', FILTER_SANITIZE_STRING,['options' =>[]]);
if(!$f_GrCode)$f_GrCode='';
$f_GrCode = filter_input(INPUT_POST, 'gc', FILTER_SANITIZE_STRING, ['options' =>['default'=>$f_GrCode]]);

$f_tests = filter_input(INPUT_GET, 'ts', FILTER_VALIDATE_REGEXP,['options' =>['regexp' => '/^[\d]{1,3}\-[\d]{1,4}\-[\d]{1,4}$/']]);
if(!$f_tests)$f_tests='';
$f_tests = filter_input(INPUT_POST, 'ts', FILTER_VALIDATE_REGEXP,
        ['options' =>['default'=>$f_tests, 'regexp' => '/^[\d]{1,3}\-[\d]{1,4}\-[\d]{1,4}$/']]);

$test = filter_input(INPUT_GET, 't', FILTER_VALIDATE_REGEXP,['options' =>['regexp' => '/^[\d]{1,3}\-[\d]{1,4}\-[\d]{1,4}$/']]);
if(!$test)$test='';
$test = filter_input(INPUT_POST, 't', FILTER_VALIDATE_REGEXP,
        ['options' =>['default'=>$test, 'regexp' => '/^[\d]{1,3}\-[\d]{1,4}\-[\d]{1,4}$/']]);

$lisid = $ht->postget('id',false,FILTER_VALIDATE_INT);
$lab_g = $lgid = $ht->postget('lgid',false,FILTER_VALIDATE_INT);
//echo $lisid;
//echo $ht->pre($_GET);
//echo $ht->pre($_POST);
//echo $ht->pre($ht->postget('id',false,FILTER_VALIDATE_INT));
$query_xml=null;

$pid=0;
$patientid=0;
$doctorid=0;
$_user=false;
$p = '';
$user = wp_get_current_user();
if(is_user_logged_in())
    $patientid=$user->ID;

//if(current_user_can( 'manage_options' ) || count( array_intersect([ 'ml_doctor' ], (array) $user->roles ) ) >0 ){
    
$results = 1;

$roless_provided = [];
$roless_provided ['administrator'] ='administrator';
$roless_provided ['ml_administrator'] ='ml_administrator';
$roless_provided ['ml_manager'] ='ml_manager';
$roless_provided ['ml_doctor'] ='ml_doctor';
$roless_provided ['ml_procedurecab'] ='ml_procedurecab';
if(current_user_can( 'manage_options' ) ||
    count( array_intersect($roless_provided, (array) $user->roles ) ) >0 ){
    $_patientid = filter_input(INPUT_GET, 'pid', FILTER_SANITIZE_NUMBER_INT);
    if($_patientid)$patientid=$_patientid;
    
    if($patientid>0){
        $_user = get_user_by('ID',$patientid);
        if($_user!==false && $_user->exists()){
            $patientid = $_user->ID;
        }
        if(is_wp_error($_user) || $_user===false){
            $patientid=0;
        }
    }
    $pid=$patientid;
}
        
if( $patientid > 0 && count( array_intersect(['ml_doctor'], (array) $user->roles ) ) >0 ){
    $user_doctor = get_user_meta($patientid, 'joined_doctor', true);
    if($user_doctor != $user->ID){
        $patientid=0;
    }
}else if($patientid > 0){
    $user_doctor = get_user_meta($patientid, 'joined_doctor', true);
    $_duser = get_user_by('ID',$user_doctor);
    if($_duser!==false && $_duser->exists()){
        $doctorid = $_duser->ID;
    }
    if(is_wp_error($_duser) || $_duser===false){
        $doctorid=0;
    }
}

if($date_from && $date_to){
}
if($patientid>0){
    $p = '?pid='.$patientid;
}
?>
<div class="row mb-2">
    <div class="col-12">
        <form action="<?=get_the_permalink( $current_pageid ).$p?>" method="POST">
            <input type="text" name="date-from" id="from" value="<?=$date_from?>">
            <input type="text" name="date-to" id="to" value="<?=$date_to?>">
            <button type="sumbit" class="btn btn-primary">Применить</button>
        </form>
    </div>
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
//    echo DShop::_div(DShop::_div(DShop::_div($f_GrsCode,'alert alert-warning'),'col-12'),'row');
if($patientid==0){
    echo DShop::_div(DShop::_div(DShop::_div('Пациент не найден','alert alert-warning'),'col-12'),'row');
    $results = false;
    return;
}
?>

<?php
if($patientid>0){
//    $code_mis = get_user_meta($patientid, '', true);
    $code_card = get_user_meta($patientid, 'card_numer', true);
    if($_user)echo DShop::_div(DShop::_div(DShop::_div('Пациент: '.$_user->first_name.' '.$_user->last_name.' ','alert alert-primary'),'col-12'),'row');
//    $results = false;
//    return;
}
if($doctorid>0){
    echo DShop::_div(DShop::_div(DShop::_div('Врач пациента: '.$_duser->first_name.' '.$_duser->last_name.' ','alert alert-primary'),'col-12'),'row');
//    $results = false;
//    return;
}else{
//    echo DShop::_div(DShop::_div(DShop::_div('Без врача ','alert alert-primary'),'col-12'),'row');
}
if($code_card>0){
    echo DShop::_div(DShop::_div(DShop::_div('Номер карты пациента: '.$code_card.' ','alert alert-primary'),'col-12'),'row');
//    $results = false;
//    return;
}
?>
<?php
if($patientid==0){
//    return;
}

$groups=[]; // [[groupName [orderName]]]
$orders=[]; // orderCode=>[name group period [test [item] ] ]

if(0){
    $groups['CodeGroup'] = [
        'Groups'=>'',
        'orders'=>[
            
        ]
    ];
    
    $orders = [
        'OrderCode'=>[ // код теста - заказ
            'Code'=>[ // код теста - анализ
                'Name'=>'', // название теста
                'GroupName'=>'', // группа теста
                'Items'=>[ // результаты - график динамики
                    'ValueDate'=>Item // результат по дате
                ]
            ]
        ]
    ];
}
        
        function ttr($d){$t='<tr>_i_</tr>';$r['_i_']=$d;return strtr($t,$r);}
        function tth($d){$t='<th>_i_</th>';$r['_i_']=$d;return strtr($t,$r);}
        function ttd($d){$r['_atr_']=''; 
        if( is_array($d) ){if(isset($d['colspan'])){$r['_atr_']='colspan="'.$d['colspan'].'"'; unset($d['colspan']);
        if(isset($d['val'])){$d = $d['val'];}else{$d=array_pop($d);}
        }}
        $t='<td _atr_>_i_</td>';$r['_i_']=$d;return strtr($t,$r);}
        function ttc($d){$t='<caption>_i_</caption>';$r['_i_']=$d;return strtr($t,$r);}
        /**
         * 
         * @param array $tab_rows массив с данными
         * @param array $tab_title_cols массив с заголовками колонок
         * @param string $caption заголвок таблицы
         * @param array $attr аттрибуты таблицы
         * @return string таблица в виде html
         */
        function table_build($tab_rows=[], $tab_title_cols=[], $caption='', $attr = []){
            $cols_count = count($tab_title_cols);
            foreach($tab_rows as $row){
                if(count($row) > $cols_count)$cols_count = count($row);
            }
            $t_tab='<table _ф_>_h_ _b_ _c_</table>';
            
            $rtpl = array_fill(0, $cols_count, 'banana');

            $tab_title_cols = array_pad($tab_title_cols,$cols_count,'');
            $tab_title_cols = ttr(implode('',array_map('tth',$tab_title_cols)));
            foreach($tab_rows as &$row){
                $colspans=0;
                foreach($row as $col){
                    if(is_array($col)){
                        if(isset($col['colspan']) && $col['colspan']>1){
                            $colspans += $col['colspan']-1;
                        }
                    }
                }
                $row = array_pad($row,$cols_count-$colspans,'');
                $row = ttr(implode('',array_map('ttd',$row)));
            }
            $r=[];
            $r['_h_']=$tab_title_cols;
            $r['_b_']=ttr(implode('',$tab_rows));
            $r['_c_']=ttc($caption);
            return strtr($t_tab, $r);
        }

// 11.7 Запрос на получение результатов по направлению
// query-referral-results
// LisId   int     Идентификатор направления в ЛИС.
// Nr      string  Номер направления.
// MisId   string  Идентификатор направления в МИС.
// Хотя бы одно из вышеперечисленных полей является обязательным.

// 11.13 Поиск заявок данного пациента
// query-patient-referral-results
// DateFrom        datetime    (необязательно) Интервал дат – начала пери-ода.
// DateTill        datetime    (необязательно) Интервал дат – конец перио-да.
// UseUpdateDate   boolean     При поиске по интервалу дат искать по дате создания (false) или по дате изменения ре-зультатов (true)
// PatientMisId    string      Идентификатор пациента.
// PatientCode1    string      Код пациента, передаваемый в поле Pa-tient.Code1 при создании направления.
// PatientCode2    string      Код пациента, передаваемый в поле Pa-tient.Code2 при создании направления.
// Идентификатор либо один из кодов пациента должен быть задан в запросе.


$PatientMisId=$patientid;

if(0){
$where = [];
$order = '`p`.`vip` DESC, `ec`.`date` DESC';
$join = [];
$where=[];

    $join[]=" LEFT join $wpdb->postmeta a on a.post_id = p.ID";
    $where[]=" a.meta_key = 'dso_ml_group'";
    $where[]=" a.meta_value =  '$f_auid'";
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
}
$q = "select g.meta_value from $wpdb->postmeta g
    LEFT join $wpdb->postmeta u on u.post_id = g.post_id
    WHERE g.meta_key = 'dso_ml_group'
    and u.meta_key = 'dso_puid'
    and u.meta_value = '$patientid'
    ";
//$q = "select g.meta_value from $wpdb->postmeta g
//    LEFT join $wpdb->postmeta u on u.post_id = g.post_id
//    WHERE g.meta_key = 'dso_sender'
//    and u.meta_key = 'dso_puid'
//    and u.meta_value = '$patientid'
//    ";
$groupids = $wpdb->get_col($q);
                    
//                    $out = ('<pre>'.htmlspecialchars(print_r($groupids,1)).'</pre>');
//                    echo DShop::_div(DShop::_div(DShop::_div($out,'border border-primary mb-2'),'col-12'),'row');
                    
                    $labGroups = [['id'=>0,'title'=>'main']];
                    $where=[];
                    $where[] = '1';
                    $where[] = '`use_access` = 1';
                    if(count($groupids))
                        $where[] = "`id` in ('".implode("','",$groupids)."')";
                    $where=implode("\nand ",$where);
                    $qg  = "SELECT id, title FROM `wp_ml_groups` where $where";
                    $labGroups_ = $wpdb->get_results($qg, ARRAY_A);
                    $labGroups = array_merge($labGroups,$labGroups_);
//                    $labGroups = array_merge([['id'=>0,'title'=>'main']],$labGroups);
//                    $labGroups = array_merge([['id'=>20,'title'=>'main 2']],$labGroups);
//                    $labGroups = array_merge($labGroups,$labGroups);
//                    $labGroups = array_merge([['id'=>5,'title'=>'test 5']],[]);
//                    $labGroups = array_merge([['id'=>5,'title'=>'test 5']],$labGroups);
                    
//                    $out = ('<pre>'.htmlspecialchars(print_r($qg,1)).'</pre>');
//                    echo DShop::_div(DShop::_div(DShop::_div($out,'border border-primary mb-2'),'col-12'),'row');
                    
//                    $out = ('<pre>'.htmlspecialchars(print_r($labGroups,1)).'</pre>');
//                    echo DShop::_div(DShop::_div(DShop::_div($out,'border border-primary mb-2'),'col-12'),'row');
                    
                    $resultOut = '';
                    
    // init table 
    $stat =[];
    $stat [1] = 'новое';
    $stat [3] = 'сделано (но не одобрено)';
    $stat [4] = 'выполнено (одобрено)';
    $stat [5] = 'отменено (не может быть выполнено)';

    $tab_title_cols = [];
    $tab_cols = [];
    $tab_rows_blocks=[];
//    $tab_rows = [];
    $caption='Запросы';
    $caption='';

//    $tab_title_cols[0]='Заявка';
//    $tab_title_cols[1]='Состояние';
//    $tab_title_cols[2]='Активация биоматериала';
//    $tab_title_cols[3]='Дата';
//    $tab_title_cols[4]='Бланки';

    $tab_title_cols[0]='Дата';
    $tab_title_cols[1]='№ заявки';
    $tab_title_cols[2]='Состояние';
    $tab_title_cols[3]='Бланк';
    $tab_title_cols[4]='Просмотреть';

    if(0&&$doctorid>0){
        $tab_title_cols[5]='Отправить врачу';
    }
            
    $xml_res=[];
        
    $initdict=false;
    
    $answer_count = 0;
    
                foreach($labGroups as $labGroup){
    $answer_count_ = 0;
                        
//                        ['ip']; 
//                        ['port']; 
//                        ['sender']; 
//                        ['password']; 
//                        ['use_access'];
                    $gid = $labGroup['id'];
                    $labTitle = $labGroup['title'];
                        if($labGroup['id']>0){
                            set_ml_access_by_group($labGroup['id']);
//                            $out = 'set_ml_access_by_group';
//                            echo DShop::_div(DShop::_div(DShop::_div($out,'border border-primary mb-2'),'col-12'),'row');
                        }
                        if(0){
    $use_access = get_option_ml_group('use_access',$gid,1);
    $ml_ip = get_option_ml_group('ip',$gid);
    $ml_port = get_option_ml_group('port',$gid);
    $ml_sender = get_option_ml_group('sender',$gid);
//    $ml_pass = get_option_ml_group('password',$gid);
//    $row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->options WHERE id = %d LIMIT 1", $gid ), ARRAY_A);
                        $lgacc = [];
                        $lgacc[]=$gid;
                        $lgacc[]=$use_access;
                        $lgacc[]=$ml_port;
                        $lgacc[]=$ml_ip;
                        $lgacc[]=$ml_sender;
//                    
                    $out = ('<pre>'.htmlspecialchars(print_r($lgacc,1)).'</pre>');
                    echo DShop::_div(DShop::_div(DShop::_div($out,'border border-primary mb-2'),'col-12'),'row');
                        
                        global $ml_ip,$ml_port;
                        global $ml_pass,$ml_sender;
                        $lgacc = [];
                        $lgacc[]=$ml_port;
                        $lgacc[]=$ml_ip;
                        $lgacc[]=$ml_sender;
//                    
                    $out = ('<pre>'.htmlspecialchars(print_r($lgacc,1)).'</pre>');
                    echo DShop::_div(DShop::_div(DShop::_div($out,'border border-primary mb-2'),'col-12'),'row');
                }

                    $q = 'query-referral-results';
                    $q = 'query-patient-referral-results';
                    $atts = [];
                    $atts['is_show_test'] = true;
                    
                    $timer = microtime(1);
                    $query=[];
                    
//                    $num = 9950000000;
//                    $num = $num + $orderId;
//                    $query['MisId'] = $orderId;
//                    $query['Nr'] = $num;
//                    $query['LisId'] = get_post_meta( $orderId, 'dso_query_id', true );
                    
                    
                    $query['PatientMisId'] = $PatientMisId;
//                    $query['PatientCode1'] = $code_card;
                    
                    //DateFrom="03.02.2017 00:00:00"
                    //DateTill=="03.02.2017 23:59:59"
                    if($date_from)$query['DateFrom']=$date_from.' 00:00:00';
                    if($date_to)$query['DateTill']=$date_to.' 23:59:59';
                    
                    $atts['query'] = $query;
                    $data_ = MedLab::_queryBuild($q,$atts);
//                    add_log('<pre>'.htmlspecialchars(print_r($atts,1)).'</pre>');
//                    add_log('<pre>'.htmlspecialchars(print_r($data_,1)).'</pre>');
                    
//                    $out = ('<pre>'.htmlspecialchars(print_r($data_,1)).'</pre>');
//                    echo DShop::_div(DShop::_div(DShop::_div($out,'border border-primary mb-2'),'col-12'),'row');
                    
                    unset($atts['is_show_test']);
                    $data_ = MedLab::_queryBuild($q,$atts);
                    
                    
                    $answer = doPostRequest($data_);
                    $xml = simplexml_load_string($answer);
                    $qrootAtt = MedLab::_buildAttrs($xml);
                    
//                    $out = ('<pre>'.htmlspecialchars(print_r($answer,1)).'</pre>');
//                    echo DShop::_div(DShop::_div(DShop::_div($out,'border border-primary mb-2'),'col-12'),'row');
//                    
//                    $out = ('<pre>'.htmlspecialchars(print_r($qrootAtt,1)).'</pre>');
//                    echo DShop::_div(DShop::_div(DShop::_div($out,'border border-primary mb-2'),'col-12'),'row');
//                    
//echo 'zzzzzzzzzzzzzzzzzzzzzzz 1';
//                    $out = ('<pre>'.htmlspecialchars(print_r($xml,1)).'</pre>');
//                    echo DShop::_div(DShop::_div(DShop::_div($out,'border border-primary mb-2'),'col-12'),'row');
//echo 'zzzzzzzzzzzzzzzzzzzzzzz 2';
                    
                    
                    $timer2 = microtime(1);
                    $mics = $timer2 - $timer;
                    if(current_user_can( 'manage_options' ) ){
//                        echo DShop::_div(DShop::_div(DShop::_div('На запрос затрачено '.$mics.' секунд.','alert alert-primary'),'col-12'),'row');
                    }
                    $answer_count_= count($xml->Results->Item);
//                    if($answer_count_)
    $answer_count += $answer_count_;
//    $answer_count = count($xml->Results->Item);
//    echo DShop::_div(DShop::_div(DShop::_div('Заявки лаборатории: '.$labTitle.' ','alert alert-primary'),'col-12'),'row');
//    echo DShop::_div(DShop::_div(DShop::_div('Количество результатов: '.$answer_count.' ','alert alert-primary'),'col-12'),'row');
//                    
//                    $out = ('<pre>'.htmlspecialchars(print_r($xml,1)).'</pre>');
//                    echo DShop::_div(DShop::_div(DShop::_div($out,'border border-primary mb-2'),'col-12'),'row');
    
        
    if($answer_count_ > 0){

        if(!$initdict){
            $initdict=!$initdict;
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
        }

        $results = $xml->Results;
//                    $out = ('<pre>'.htmlspecialchars(print_r($results,1)).'</pre>');
//                    echo DShop::_div(DShop::_div(DShop::_div($out,'border border-primary mb-2'),'col-12'),'row');
        
//        $q = 'query-patient-referral-results';
//        https://getbootstrap.com/docs/4.0/components/alerts/
        
        $results = $xml->Results->Item;
        foreach ($results as $key => $item) {
            $tab_rows = [];
            $tab_cols = [];
            $item_ = MedLab::_buildAttrs($item);
            
        
//            $out = ('<pre>'.htmlspecialchars(print_r($item,1)).'</pre>');
//            echo DShop::_div(DShop::_div(DShop::_div($out,'border border-primary mb-2'),'col-12'),'row');
        
//            $out = ('<pre>'.htmlspecialchars(print_r($item_,1)).'</pre>');
//            echo DShop::_div(DShop::_div(DShop::_div($out,'border border-primary mb-2'),'col-12'),'row');
            
            $q = 'query-referral-results';
            $atts = [];
            $query=[];
            $query['LisId'] = $item_['LisId'];
//            $query['Nr'] = $item_['Nr'];
//            $query['MisId'] = $item_['MisId'];
//            $query['Nr'] = '';
//            $query['MisId'] = '';
            $atts['query'] = $query;
            
                
//                $div_error =  DShop::_div(DShop::_div(DShop::_div(
//                        dev::pre($labGroup,'$labGroup',0)
//                    ,'alert alert-primary'),'col-12'),'row');
//                echo $div_error;
            
//            if($labGroup['id']>0)
//                set_ml_access_by_group($labGroup['id']);
//            
//            $atts['is_show_test'] = true;
//            $data_ = MedLab::_queryBuild($q,$atts);
//            $out = ('<pre>'.htmlspecialchars(print_r($data_,1)).'</pre>');
//            echo DShop::_div(DShop::_div(DShop::_div($out,'border border-primary mb-2'),'col-12'),'row');
            
            if($labGroup['id']>0)
                set_ml_access_by_group($labGroup['id']);

            unset($atts['is_show_test']);
            $data_ = MedLab::_queryBuild($q,$atts);
                        
            $answer = doPostRequest($data_);
            $xml = simplexml_load_string($answer);
            $qrootAtt = MedLab::_buildAttrs($xml);
            if($lisid == $item_['LisId'])
                $query_xml = $xml;
            
            if(isset($qrootAtt['Error'])){
                
                $div_error =  DShop::_div(DShop::_div(DShop::_div(
                    'Заявка: '.$item_['LisId'].'. '.
                    'Ошибка: <br/>'.$qrootAtt['Error'].
//                    ' <br/> line: '.__LINE__.
                    ''
                    ,'alert alert-danger'),'col-12'),'row');
                echo $div_error;
                
//                $div_error =  DShop::_div(DShop::_div(DShop::_div(
//                        dev::pre($labGroup,'$labGroup',0)
//                    ,'alert alert-danger'),'col-12'),'row');
//                echo $div_error;
//                dev::pre($item_,'$item_');
                continue;
            }
            
            $answer = iconv('windows-1251', 'UTF-8', $answer);
            $out = ('<pre>'.htmlspecialchars(print_r($answer,1)).'</pre>');
            $xml_res[] = DShop::_div(DShop::_div(DShop::_div($out,'border border-primary mb-2'),'col-12'),'row');
                    
//                    $out = ('<pre>'.htmlspecialchars(print_r($xml,1)).'</pre>');
//                    echo DShop::_div(DShop::_div(DShop::_div($out,'border border-primary mb-2'),'col-12'),'row');
            
//            $results = $xml->Referral;
//            $out = ('<pre>'.htmlspecialchars(print_r($results,1)).'</pre>');
//            echo DShop::_div(DShop::_div(DShop::_div($out,'border border-primary mb-2'),'col-12'),'row');
                    
                    
            $done_state=[];
            $done_state['true']='выполнено';
            $done_state['false']='не выполнено';
            $done_state['false']='в процессе';
                    
            $Activated_state=[];
            $Activated_state['1']='нет';
            $Activated_state['2']='частично';
            $Activated_state['3']='полностью';
            
            $Referral = MedLab::_buildAttrs($xml->Referral);
            
            $stat_color=[];
            $stat_color['true']='dark';
            $stat_color['false']='secondary';
            $stat_color['no']='danger';
            
            ob_start();
            
            $div =  DShop::_div(DShop::_div(DShop::_div(
                    'Заявка: '.$item_['LisId'].'. '.
                    'Направление '.$done_state[$Referral['Done']].'. '.
                    'Активация биоматериала: '.$Activated_state[$Referral['Activated']].'. '.
                    ''
                    ,'alert alert-'.$stat_color[$Referral['Done']]),'col-12'),'row');
            $err = ob_get_clean();
            if($err){
                echo $err;
                dev::pre($Referral,'$Referral');
                dev::pre($xml,'$xml');
            }
            echo $div;
            
            
            $ref = MedLab::_buildAttrs($xml->Referral);
            $DeliveryDate = $ref['DeliveryDate'];
            $Date = $ref['Date'];
            if($DeliveryDate)$Date = $DeliveryDate;
            $Date = explode(' ',$Date);
            $Date = array_shift($Date);
            
            $DateR = explode('.',$Date);
            $DateR = array_reverse($DateR);
            $DateR = implode('.',$DateR);
            
            $rowId = $DateR.'_'.count($tab_rows_blocks).'_'.count($tab_rows).'_'.$item_['LisId'].'_'.$labGroup['id'];
            $rowBlokInfo = '';
//            $rowBlokInfo .= $rowId;
            $rowBlokInfo .= ' Пункт: '.$labTitle;
            if(0)$tab_rows [] =  [['colspan'=>5,'val'=>'<hr/> '.$rowBlokInfo]];
            $tab_cols ['laboratory_group'] =  [['colspan'=>5,'val'=>'<hr/> '.$rowBlokInfo]];
            
            $q=[];
            if($pid)$q['pid']=$pid;
            $q['id']=$item_['LisId'];
            $q['lgid']=$labGroup['id'];
            if($date_from)$q['date-from']=$date_from;
            if($date_to)$q['date-to']=$date_to;
            $p = '?'.http_build_query($q);
            $tab_cols ['show_result'] = $Referral['Done']?$ht->f('a',$item_['LisId'],['href'=>  get_the_permalink($current_pageid).$p,'class'=>'btn btn-primary text-white']):$item_['LisId'].'. ';
            $tab_cols ['show_result'] = $Referral['Done']?$ht->f('a','Просмотреть',['href'=>  get_the_permalink($current_pageid).$p,'class'=>'btn btn-primary text-white']):'Не готов'.'. ';
            
            $tab_cols ['done'] = $done_state[$Referral['Done']].'. ';
            $tab_cols ['activated'] = $Activated_state[$Referral['Activated']].'. ';
            
            // группировка результатов
            
            $__orders = [
                'OrderCode'=>[ // код теста - заказ
                    'Code'=>[ // код теста - анализ
                        'Name'=>'', // название теста
                        'GroupName'=>'', // группа теста
                        'Items'=>[ // результаты - график динамики
                            'ValueDate'=>'Item' // результат по дате
                        ]
                    ]
                ]
            ];
            
            $o = [];
            if(0){
                $o = $xml->Referral->Orders->Item=[];
                $t = $xml->Blanks->Item->Tests->Item=[];
            }
            
            $tab_cols ['order_create'] = $Date;
            $tab_cols ['blank_count'] = count($xml->Blanks->Item);
            if(0 && $doctorid>0){//==============
                $send = '';
                if($Referral['Activated']==3){
                    $send = [];
                    $send[] = DShop::_button('btn_name','Отправить','submit','btn btn-primary text-white','','Отправить');
                    $send[] = DShop::_input('form-type','query_sent_d','hidden');
                    $send[] = DShop::_input('oid',$item_['MisId'],'hidden');
                    $send = DShop::_form($send,'','','post');
                    $state_ =  get_post_meta( $item_['MisId'], 'dso_send_to_doctor', true );
                    if($state_=='sent')
                        $send = 'Отправленно';
                }
                $tab_cols ['doctor_send'] = $send;
            }
            
            
            $analyses = $medLab->analyses;
            $panels = $medLab->panels;
            $n = [];
            foreach($analyses as $a)$n[$a['Code']] = $a['Name'];
            foreach($panels as $a)$n[$a['Code']] = $a['Name'];
//        $out = ('<pre>'.htmlspecialchars(print_r($analyses,1)).'</pre>');
//        echo DShop::_div(DShop::_div(DShop::_div($out,'border border-primary mb-2'),'col-12'),'row');
            foreach($xml->Referral->Orders->Item as $o){ // заказы
                $o_ = MedLab::_buildAttrs($o);
                $code = $o_['Code'];
                $oname = '';
                if(isset($n[$code]))$oname = $n[$code];
//                if(isset($panels[$code]))$oname = $panels[$code];
                
                $analyse = $medLab->analyses;
                $panel = $medLab->panels;
                $orders[$o_['Code']]['Name'] = $oname;
                $orders[$o_['Code']]['Order'] = $o_;
            }
            if(0)$tab_rows [] = $tab_cols;//===================
            
            $blank_buttons = [];
            
            // building blank row
            foreach($xml->Blanks->Item as $bi_){ // ответы заказов
                $tab_cols_blank =[]; // $tab_title_cols
//                echo DShop::_div(DShop::_div(DShop::_div($ht->pre($bi_),'alert alert-primary'),'col-12'),'row');
                $test = [];
                $OrderCode = '';
                $b_ = MedLab::_buildAttrs($bi_);
//                echo DShop::_div(DShop::_div(DShop::_div($ht->pre($b_),'alert alert-primary'),'col-12'),'row');
                if($b_['Done'] == 'true'){
                    $tab_cols_blank []  = ['colspan'=>3,'val'=>'Бланк: '.$b_['Name']];
                        
                    $send = [];
//                    $send[] = DShop::_button('btn_name','get_blank','submit','btn btn-primary text-white','','Загрузить бланк');
                    $send[] = DShop::_button('btn_name','get_blank','submit','btn btn-primary text-white','','Скачать бланк');
                    $send[] = DShop::_input('form-type','query_get_blank','hidden');
                    $send[] = DShop::_input('oid',$item_['MisId'],'hidden');
                    $send[] = DShop::_input('bid',$b_['BlankId'],'hidden');
                    $send[] = DShop::_input('bguid',$b_['BlankGUID'],'hidden');
                    $send[] = DShop::_input('lgid',$labGroup['id'],'hidden');
                    $send = DShop::_form($send,'','','post','',[],['target'=>'_blank']);
//                            $state_ =  get_post_meta( $item_['MisId'], 'dso_send_to_doctor', true );
//                            if($state_=='sent')
//                                $send = 'Отправленно';
                    $blank_buttons[] = $send;
                    $tab_cols_blank []  = ['colspan'=>count($tab_title_cols)-3,'val'=> $send];
//                        $tab_cols_blank [] = $send;
                    
                    if(0) $tab_rows [] = $tab_cols_blank;
                        
//                    foreach($bi_->Tests->Item as $t){ // тесты
//                        $t_ = MedLab::_buildAttrs($t);
//                        $OrderCode = $t_['OrderCode'];
//                        $GroupCode = $t_['GroupCode'];
//                        $test[$t_['Code']][$Date] = $t_;
//                        $orders[$OrderCode]['Tests'][$GroupCode]['GroupName'] = $t_['GroupName'];
//                        $orders[$OrderCode]['Tests'][$GroupCode]['Tests'][$t_['Code']]['Name'] = $t_['Name'];
//                        $orders[$OrderCode]['Tests'][$GroupCode]['Tests'][$t_['Code']]['Items'][$Date] = $t_;
//
//                    }
                }
                
//                $o[$o_->Code] = $o_;
            }
            $tab_cols ['blank_buttons'] = implode("\n",$blank_buttons);
            $tab_cols_=[];
//            $tab_cols_[] = $tab_cols ['laboratory_group'];
            $tab_cols_[] = $tab_cols ['order_create'];
//            $tab_cols_[] = $item_['LisId'];
            $dso_query_nr = $item_['Nr'];
            $barcode = MLBarcode::img_file($dso_query_nr,MLBARCODELOADS,'order_nr');
            $tab_cols_[] = $barcode;
            
            $tab_cols_[] = $tab_cols ['done'];
//            $tab_cols_[] = $tab_cols ['activated'];
//            $tab_cols_[] = $tab_cols ['blank_count'];
//            $tab_cols_[] = $tab_cols ['doctor_send'];
            $tab_cols_[] = $tab_cols ['blank_buttons'];
            $tab_cols_[] = $tab_cols ['show_result'];
            $tab_rows [] = $tab_cols_;
            $tab_rows_blocks[$rowId]=$tab_rows;
            
//            $order = [];
//            $orders[$OrderCode] = $order;
//            $tab_rows [] = $tab_cols;
//            $tab_rows [] = $tab_cols;
        }
        
        if(0){
        
        
//        echo DShop::_div(DShop::_div(DShop::_div('Доступные исследования:','-border -border-primary mb-2'),'col-12'),'row');
        $outs =[];
            $out = "Доступные исследования:";
            $outs[0]=DShop::_div(DShop::_div($out,'-border -border-primary mb-2'),'col-12');
        foreach($orders as $code=>$order_){
            $name = "[".$code."] ".$order_['Name'];
            $q=[];
            if(current_user_can( 'manage_options' ) || count( array_intersect($roless_provided, (array) $user->roles ) ) >0 ){
                $q['pid'] = $patientid;
            }
            if( $date_from ){
                $q['date-from'] = $date_from;
            }
            if( $date_to ){
                $q['date-to'] = $date_to;
            }
            $q['an'] = $code;
            
            $btn = DShop::_a('Просмотреть',get_the_permalink( $current_pageid ),$q,['class'=>'btn btn-primary -mr-2 mb-3 text-white']);
            $out = DShop::_div(DShop::_div($btn,''),'col-3');
            $out.= DShop::_div(DShop::_div($name,''),'col-9');
            
            $outs[$code]=$out;
//            $outs[$code]=DShop::_div(DShop::_div($out,'-border -border-primary -mb-2'),'col-12');
        }
        ksort($outs);
        $out = DShop::_div(implode("\n",$outs),'row m-2');
        echo DShop::_div(DShop::_div(DShop::_div($out,'border border-primary mb-2'),'col-12'),'row');
        
    if($order ){
        
        if($order && isset($orders[$order]['Tests'] )){
            foreach($orders[$order]['Tests'] as $GroupCode=>$test_){
                $outs =[];
                $out = "Группа: ". "[".$GroupCode."] ".$test_['GroupName'];
                $outs[0]=DShop::_div(DShop::_div($out,'-border -border-primary mb-2'),'col-12');
                $q=[];
                if(current_user_can( 'manage_options' ) || count( array_intersect($roless_provided, (array) $user->roles ) ) >0 ){
                    $q['pid'] = $patientid;
                }
                if( $date_from ){
                    $q['date-from'] = $date_from;
                }
                if( $date_to ){
                    $q['date-to'] = $date_to;
                }

                $q['an'] = $order;
                $q['gc'] = $GroupCode;
                foreach($test_['Tests'] as $code=>$order_){
                    $name = "[".$code."] ".$order_['Name'];
                    $q['ts'] = $code;
                    if( $f_GrCode ){
                    }

                    $btn = DShop::_a('Динамика',get_the_permalink( $current_pageid ),$q,['class'=>'btn btn-primary -mr-2 mb-3 text-white']);
                    $out = DShop::_div(DShop::_div($btn,''),'col-3');
                    $out.= DShop::_div(DShop::_div($name,''),'col-9');

                    $outs[$code]=$out;
        //            $outs[$code]=DShop::_div(DShop::_div($out,'-border -border-primary -mb-2'),'col-12');
                }
                ksort($outs);
                $out = DShop::_div(implode("\n",$outs),'row m-2');
                echo DShop::_div(DShop::_div(DShop::_div($out,'border border-primary mb-2'),'col-12'),'row');
            }
        }
        if($order && $f_GrCode && $f_tests){
            echo DShop::_div(DShop::_div(DShop::_div("orders[$order]['Tests'][$f_GrCode]['Tests'][$f_tests]",'alert alert-warning'),'col-12'),'row');

            if($order && isset($orders[$order]['Tests'] )){
                $out = ('<pre>'.htmlspecialchars(print_r($orders[$order]['Tests'][$f_GrCode]['Tests'][$f_tests],1)).'</pre>');
                echo DShop::_div(DShop::_div(DShop::_div($out,'border border-primary mb-2'),'col-12'),'row');
            }
        }
        else
        if($order){
//            $out = ('<pre>'.htmlspecialchars(print_r($orders[$order],1)).'</pre>');
//            echo DShop::_div(DShop::_div(DShop::_div($out,'border border-primary mb-2'),'col-12'),'row');
        }
    }
//        echo implode('',$xml_res);
//            $out = ('<pre>'.htmlspecialchars(print_r($orders,1)).'</pre>');
//            echo DShop::_div(DShop::_div(DShop::_div($out,'border border-primary mb-2'),'col-12'),'row');
        
            $q = 'query-count-referral-results';
//            $atts = [];
//            $query=[];
////            $query['LisId'] = $item_['LisId'];
////            $query['Nr'] = $item_['Nr'];
////            $query['MisId'] = $item_['MisId'];
////            $query['Nr'] = '';
////            $query['MisId'] = '';
//            $atts['query'] = $query;
//            $data_ = MedLab::_queryBuild($q,$atts);
//            
//            $answer = doPostRequest($data_);
//            $xml = simplexml_load_string($answer);
//            $qrootAtt = MedLab::_buildAttrs($xml);
//                    
//                    $out = ('<pre>'.htmlspecialchars(print_r($answer,1)).'</pre>');
//                    echo DShop::_div(DShop::_div(DShop::_div($out,'border border-primary mb-2'),'col-12'),'row');
    }
    
    
        if($lisid && $query_xml && $lgid!==false && $lgid == $labGroup['id']){
            ob_start();
            
            echo DShop::_div(DShop::_div(DShop::_div('Пункт: '.$labTitle.' Дата: '.$Date.' Заявка: '.$lisid.' ','alert alert-primary'),'col-12'),'row');
            
        $xml = $query_xml;
        $Referral = MedLab::_buildAttrs($xml->Referral);
        $DeliveryDate = $Referral['DeliveryDate'];
//        $DoneDate = $Referral['DoneDate'];
        $tests = [];
        foreach( $xml->Blanks->Item as $blank_){
//            echo $ht->f('h3','go');
            $blank = MedLab::_buildAttrs($blank_);
            $title = $blank['Groups'];
                $data = [];
            foreach( $blank_->Tests->Item as $test_){
                $test = MedLab::_buildAttrs($test_);
                if(!isset( $tests[$test['GroupCode']]))
                    $tests[$test['GroupCode']] = [];
                $tests[$test['GroupCode']][]= $test;
            }
        }
            foreach($tests as $group => $test){
                $data = [];
                $title = $group.' ';
                foreach($test as $item_){
                        $title = $group.' '. $item_['GroupName'];
                        $title = $item_['GroupName'];
    //    echo $ht->pre($item);
    //                $item = MedLab::_buildAttrs($item);
                        $item = [];
                        $item[0]=$item_['Name'];
                        $item[1]=$item_['Value'];
                        $item[2]=$item_['NormsComment'];
                        $item[3]=$item_['UnitName'];
                        $item[4]=$item_['Norms'];
                        if($item_['NormsFlag']>1){
                            $item[1]= $ht->f('font',$ht->f('b',$item_['Value']),['color'=>'red']);
                        }
                        if(strlen($item_['NormsComment'])==0 && strlen($item_['UnitName'])==0){
                            $item[1]= ['class'=>'col-5','val'=>$item[1]];
                            unset($item[2]);
                            unset($item[3]);
                        }else
                        if(strlen($item_['NormsComment'])==0){
                            $item[1]= ['class'=>'col-3','val'=>$item[1]];
                            unset($item[2]);
                        }
                        $data[]=$item;
                }
$hitems = [];
//$hitems[]='<label>'.'$check'.'№</label>';
$hitems[]='ПОКАЗАТЕЛЬ';
$hitems[]='РЕЗУЛЬТАТ';
$hitems[]='&nbsp;';
$hitems[]='ЕД.ИЗМ.';
$hitems[]='РЕФ. ИНТЕРВАЛ';
$cclass=[]; // coll class
$cclass[0] = 4;
$cclass[1] = 1;
$cclass[] = 2;
$cclass[] = 2;
$cclass[] = 3;
                $defs =[];
                $defs['usenumbers'] = 0;
                $defs['cclass'] = $cclass;
                $defs['hitems'] = $hitems;
            //    $defs['inorder'] = $inorder;
            //    $defs['orders'] = $orders;
            //    $defs['sortVName'] = 'order';
            ////    $defs['urlget'] = $urlget;
            //    $defs['ma']='↓';
            //    $defs['md']='↑';
            //    $defs['sortClass']='btn';
                $defs['data'] = $data;

                $out = $ht->f('h3',$title);
                $title = DShop::_div(DShop::_div(DShop::_div($out,'col-12 '),'row'),'col-12');
                $out = DShop::_div($ht->btabl($defs),'col-12');
//                $out .= $ht->btabl($defs);
                $out = $title . DShop::_div(DShop::_div(DShop::_div($out,'row '),'stripped-rows-h '),'col-12');
                echo DShop::_div(DShop::_div(DShop::_div($out,'row m-2'),'col-12 border border-primary mb-2 p-4'),'row m-0');
            }
                
//            $out = ('<pre>'.htmlspecialchars(print_r($xml,1)).'</pre>');
//            echo DShop::_div(DShop::_div(DShop::_div($out,'border border-primary mb-2'),'col-12'),'row');
            $resultOut = ob_get_clean();
        }
    }
    
    }
    ksort($tab_rows_blocks);
    krsort($tab_rows_blocks);
    $tab_rows = [];
    foreach($tab_rows_blocks as $rows_blocks){
        foreach($rows_blocks as $rows){
            $tab_rows[]=$rows;
        }
    }
    $tab_rows [] =  [['colspan'=>5,'val'=>'<hr/> ']];
    echo DShop::_div(DShop::_div(DShop::_div('Количество результатов: '.$answer_count.' ','alert alert-primary'),'col-12'),'row');
//    echo '============= 1';
    echo table_build($tab_rows, $tab_title_cols, $caption);
//    echo '============= 2';
    echo $resultOut;
//    echo '============= 3';
?>

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <!--<link rel="stylesheet" href="/resources/demos/style.css">-->
<style>
    
/* 009788 */
/* 7ccac3 */
/* bdbdbd */
/* 727272 */
.ui-datepicker-calendar th span{
    color: #bdbdbd;
}
/*.ui-widget-content .ui-datepicker-calendar td a.ui-state-default,
.ui-datepicker-calendar td a.ui-state-default{*/
.ui-widget-content .ui-state-default{
    color: #727272;
}
.ui-widget.ui-widget-content {
    /*border: 1px solid #c5c5c5;*/
    border: 1px solid #ffffff;
}
.ui-widget-header {
/*    border: 1px solid #dddddd;
    background: #e9e9e9;
    color: #333333;*/
    border: 1px solid #ffffff;
    background: #ffffff;
    color: #000000;
    font-weight: bold;
}
.ui-state-default,
.ui-widget-content .ui-state-default,
.ui-widget-header .ui-state-default,
.ui-button,
html .ui-button.ui-state-disabled:hover,
html .ui-button.ui-state-disabled:active ,
.ui-state-default{
    border: 1px solid #ffffff;/*003eff;*/
    background: #ffffff;/*007fff;*/
}
.ui-widget-content .ui-state-range .ui-state-default,
.ui-state-range .ui-state-default{
    border: 1px solid #7ccac3;
    background: #7ccac3;
    color: #ffffff;
}
.ui-widget-content .ui-state-end-range .ui-state-default,
.ui-widget-content .ui-state-range .ui-state-active,
.ui-state-range .ui-state-active,
.ui-widget-content .ui-state-active,
.ui-state-active{
    border: 1px solid #009788;/*003eff;*/
    background: #009788;/*007fff;*/
    color: #ffffff;
}
.ui-datepicker td {
    border: 0;
    /*padding: 1px;*/
    padding: 0;
    border: 1px solid #ffffff;
}
.ui-datepicker td.ui-state-range{
    border: 1px solid #7ccac3;
}
.ui-widget-content .ui-state-end-range ,
.ui-datepicker td.ui-datepicker-current-day{
    border: 1px solid #009788;/*003eff;*/
}
.ui-datepicker-today,
.ui-state-highlight,
.ui-widget-content .ui-state-highlight,
.ui-widget-content .ui-state-range.ui-datepicker-today,
.ui-widget-content .ui-datepicker-current-day.ui-datepicker-today,
.ui-widget-content .ui-state-range .ui-state-default.ui-state-highlight,
.ui-widget-content .ui-state-range .ui-state-active.ui-state-highlight
{
    border: 1px solid #dad55e;
    background: #fffa90;
    color: #777620;
    color: #000000;
}
</style>
<style>
    .stripped-rows-h > div:nth-of-type(odd) {
        /*background: #e0e0e0;*/
        background-color: #F5F7FA;
        background-color: rgba(0,0,0,.05);
    }
    .stripped-rows-h > div:nth-of-type(even) {
        /*background: #FFFFFF;*/
    }
    .stripped-rows-h > div:nth-of-type(odd) {
    }
    .stripped-rows > div:nth-of-type(even) {
        background-color: #F5F7FA;
        background-color: rgba(0,0,0,.05);
    }
    .stripped-rows-h > div:hover,
    .stripped-rows > div:hover
    {
        background-color: rgba(0,0,0,.075);
    }
</style>

  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
      // Source: http://stackoverflow.com/questions/497790
var dt = {
    convert:function(d) {
        // Converts the date in d to a date-object. The input can be:
        //   a date object: returned without modification
        //  an array      : Interpreted as [year,month,day]. NOTE: month is 0-11.
        //   a number     : Interpreted as number of milliseconds
        //                  since 1 Jan 1970 (a timestamp) 
        //   a string     : Any format supported by the javascript engine, like
        //                  "YYYY/MM/DD", "MM/DD/YYYY", "Jan 31 2009" etc.
        //  an object     : Interpreted as an object with year, month and date
        //                  attributes.  **NOTE** month is 0-11.
        return (
            d.constructor === Date ? d :
//            d.constructor === Array ? new Date(d[2],d[1],d[0]) :
            d.constructor === Array ? new Date(d[0],d[1],d[2]) :
            d.constructor === Number ? new Date(d) :
            d.constructor === String ? new Date(d) :
            typeof d === "object" ? new Date(d.year,d.month,d.date) :
            NaN
        );
    },
    compare:function(a,b) {
        // Compare two dates (could be of any type supported by the convert
        // function above) and returns:
        //  -1 : if a < b
        //   0 : if a = b
        //   1 : if a > b
        // NaN : if a or b is an illegal date
        // NOTE: The code inside isFinite does an assignment (=).
        return (
            isFinite(a=this.convert(a).valueOf()) &&
            isFinite(b=this.convert(b).valueOf()) ?
            (a>b)-(a<b) :
            NaN
        );
    },
    eqwal:function(a,b) {
        // Compare two dates (could be of any type supported by the convert
        // function above) and returns:
        //  -1 : if a < b
        //   0 : if a = b
        //   1 : if a > b
        // NaN : if a or b is an illegal date
        // NOTE: The code inside isFinite does an assignment (=).
        return (
            isFinite(a=this.convert(a).valueOf()) &&
            isFinite(b=this.convert(b).valueOf()) ?
            (a == b) :
            NaN
        );
    },
    inRange:function(d,start,end) {
        // Checks if date in d is between dates in start and end.
        // Returns a boolean or NaN:
        //    true  : if d is between start and end (inclusive)
        //    false : if d is before start or after end
        //    NaN   : if one or more of the dates is illegal.
        // NOTE: The code inside isFinite does an assignment (=).
       return (
            isFinite(d=this.convert(d).valueOf()) &&
            isFinite(start=this.convert(start).valueOf()) &&
            isFinite(end=this.convert(end).valueOf()) ?
            start <= d && d <= end :
            NaN
        );
    }
}
  $( function() {
    var dates = ['2019/10/14', '2019/10/18']; //
            //tips are optional but good to have
    var tips  = ['some description','some other description']; 
    function highlightDays(date) {
//            console.log(date.toString() );
//        for (var i = 0; i < dates.length; i++) {
////            console.log(dates[i] );
////            console.log( new Date(dates[i]).toString());
//            
//            if (new Date(dates[i]).toString() == date.toString()) {              
////                return [true, 'ui-state-range'];       
////                return [true, 'highlight', tips[i]];
//            }
//        }
            var from = $( "#from" ).val();
            var to = $( "#to" ).val();
//            let from = from.split('.');
//            let to = to.split('.');
//            dates[0] = from.split('.').reverse().join('.');
//            dates[1] = to.split('.').reverse().join('.');
            from = from.split('.').reverse().join('.');
            to = to.split('.').reverse().join('.');
//            if (dt.inRange(date, dates[0], dates[1])) { 
// ui-state-end-range
            if (from.length>0 && to.length>0 && dt.eqwal(date, from)) {              
                return [true, 'ui-state-end-range'];       
//                return [true, 'highlight', tips[i]];
            }
            if (from.length>0 && to.length>0 && dt.eqwal(date, to)) {              
                return [true, 'ui-state-end-range'];       
//                return [true, 'highlight', tips[i]];
            }
            if (from.length>0 && to.length>0 && dt.inRange(date, from, to)) {              
                return [true, 'ui-state-range'];       
//                return [true, 'highlight', tips[i]];
            }
//            if (new Date(dates[1]).toString() == date.toString()) {              
//                return [true, 'ui-state-range'];       
////                return [true, 'highlight', tips[i]];
//            }
        return [true, ''];
     };
     
    var dateFormat = "dd.mm.yy",
      from = $( "#from" )
        .datepicker({
//            defaultDate: "-1w",
            beforeShowDay: highlightDays,
//          changeMonth: true,
          numberOfMonths: 2
//            beforeShowDay: function(date) {
//            var dto = $( "#to" ).val();
//            console.log(date );
//            console.log( dto);
//             if (date == dto) {
//              return [true, 'ui-state-range', 'tooltipText'];
//
//              }
//           }
        })
        .datepicker( "option", "dateFormat", dateFormat)
        .on( "change", function() {
          to.datepicker( "option", "minDate", getDate( this ) );
        })
        .val('<?= $date_from ?>')
        
      to = $( "#to" ).datepicker({
        defaultDate: "-4w",
            beforeShowDay: highlightDays,
//        changeMonth: true,
        numberOfMonths: 2
      })
      .datepicker( "option", "dateFormat", dateFormat)
      .on( "change", function() {
        from.datepicker( "option", "maxDate", getDate( this ) );
      })
        .val('<?= $date_to ?>');
//      $( "#from" ).datepicker( "option", "dateFormat", dateFormat);
//      $( "#to" ).datepicker( "option", "dateFormat", dateFormat);
 
    function getDate( element ) {
      var date;
      try {
        date = $.datepicker.parseDate( dateFormat, element.value );
      } catch( error ) {
        date = null;
      }
 
      return date;
    }
  } );
  </script>