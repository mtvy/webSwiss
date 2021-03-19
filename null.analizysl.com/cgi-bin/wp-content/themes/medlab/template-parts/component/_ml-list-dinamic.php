<?php

/* 
 * ml-list-dinamic.php
 * [ml_component tpl="ml-list" type="dinamic"]
 */


/* 
 * ml-list-answer.php
 * [ml_component tpl="ml-list" type="answer"]
 */

global $ht;

// wp_enqueue_script('prod_script',
//         get_stylesheet_directory_uri() . '/js/prod_script.js',['jquery'],null,true);
 wp_enqueue_script('flotr',
         get_stylesheet_directory_uri() . '/js/flotr.js',['jquery'],null,false);
/*
 * 
Warning: fopen(http://213.230.71.167:9901/): 
 * failed to open stream: Connection timed out in /home/swissl01/public_html/wp-content/plugins/medlab/connect.php on line 425

Fatal error: Using $this when not in object context in /home/swissl01/public_html/wp-content/plugins/medlab/connect.php on line 406
 */

$date_from='';
$date_to='';
$order='';
$f_GrCode='';
$f_tests='';
$test='';

$current_pageid = 309;
$current_pageid = 38;

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

$f_tests = filter_input(INPUT_GET, 'ts', FILTER_VALIDATE_REGEXP,['options' =>['regexp' => '/^[\d]{1,3}\-[\d]{1,4}\-[\d]{1,4}\.?[\d]{1,4}?$/']]);
if(!$f_tests)$f_tests='';
$f_tests = filter_input(INPUT_POST, 'ts', FILTER_VALIDATE_REGEXP,
        ['options' =>['default'=>$f_tests, 'regexp' => '/^[\d]{1,3}\-[\d]{1,4}\-[\d]{1,4}\.?[\d]{1,4}?$/']]);

$test = filter_input(INPUT_GET, 't', FILTER_VALIDATE_REGEXP,['options' =>['regexp' => '/^[\d]{1,3}\-[\d]{1,4}\-[\d]{1,4}$/']]);
if(!$test)$test='';
$test = filter_input(INPUT_POST, 't', FILTER_VALIDATE_REGEXP,
        ['options' =>['default'=>$test, 'regexp' => '/^[\d]{1,3}\-[\d]{1,4}\-[\d]{1,4}$/']]);

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
//    echo DShop::_div(DShop::_div(DShop::_div($f_GrCode,'alert alert-warning'),'col-12'),'row');
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

                    $q = 'query-referral-results';
                    $q = 'query-patient-referral-results';
                    $atts = [];
                    $atts['is_show_test'] = true;
                    
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
                    
//                    $out = ('<pre>'.htmlspecialchars(print_r($qrootAtt,1)).'</pre>');
//                    echo DShop::_div(DShop::_div(DShop::_div($out,'border border-primary mb-2'),'col-12'),'row');
                    
    $answer_count = 0;
    $answer_count = count($xml->Results->Item);
//    $answer_count = count($xml->Results);
    echo DShop::_div(DShop::_div(DShop::_div('Количество результатов: '.$answer_count.' ','alert alert-primary'),'col-12'),'row');
                    
//                    $out = ('<pre>'.htmlspecialchars(print_r($xml,1)).'</pre>');
//                    echo DShop::_div(DShop::_div(DShop::_div($out,'border border-primary mb-2'),'col-12'),'row');
    if($answer_count > 0){

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

        $results = $xml->Results;
//                    $out = ('<pre>'.htmlspecialchars(print_r($results,1)).'</pre>');
//                    echo DShop::_div(DShop::_div(DShop::_div($out,'border border-primary mb-2'),'col-12'),'row');
        
        $stat =[];
        $stat [1] = 'новое';
        $stat [3] = 'сделано (но не одобрено)';
        $stat [4] = 'выполнено (одобрено)';
        $stat [5] = 'отменено (не может быть выполнено)';
        
//        $q = 'query-patient-referral-results';
                    
//        https://getbootstrap.com/docs/4.0/components/alerts/
        $results = $xml->Results->Item;
        $xml_res=[];
        foreach ($results as $key => $item) {
            $item_ = MedLab::_buildAttrs($item);
            
        
//            $out = ('<pre>'.htmlspecialchars(print_r($item,1)).'</pre>');
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
            
//            $atts['is_show_test'] = true;
//            $data_ = MedLab::_queryBuild($q,$atts);
//            $out = ('<pre>'.htmlspecialchars(print_r($data_,1)).'</pre>');
//            echo DShop::_div(DShop::_div(DShop::_div($out,'border border-primary mb-2'),'col-12'),'row');

            unset($atts['is_show_test']);
            $data_ = MedLab::_queryBuild($q,$atts);
            
            $answer = doPostRequest($data_);
            $xml = simplexml_load_string($answer);
            $qrootAtt = MedLab::_buildAttrs($xml);
            
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
                    
            $Activated_state=[];
            $Activated_state['1']='нет';
            $Activated_state['2']='частично';
            $Activated_state['3']='полностью';
            
            $Referral = MedLab::_buildAttrs($xml->Referral);
            
            $stat_color=[];
            $stat_color['true']='dark';
            $stat_color['false']='secondary';
            echo DShop::_div(DShop::_div(DShop::_div(
                    'Заявка: '.$item_['LisId'].'. '.
                    'Направление '.$done_state[$Referral['Done']].'. '.
                    'Активация биоматериала: '.$Activated_state[$Referral['Activated']].'. '.
                    ''
                    ,'alert alert-'.$stat_color[$Referral['Done']]),'col-12'),'row');
            
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
            $ref = MedLab::_buildAttrs($xml->Referral);
            $DeliveryDate = $ref['DeliveryDate'];
            $Date = $ref['Date'];
            if($DeliveryDate)$Date = $DeliveryDate;
            $Date = explode(' ',$Date);
            $Date = array_shift($Date);
            
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
            foreach($xml->Blanks->Item as $bi_){ // ответы заказов
                $test = [];
                $OrderCode = '';
                foreach($bi_->Tests->Item as $t){ // тесты
                    $t_ = MedLab::_buildAttrs($t);
                    $OrderCode = $t_['OrderCode'];
                    $GroupCode = $t_['GroupCode'];
                    $test[$t_['Code']][$Date] = $t_;
                    $orders[$OrderCode]['Tests'][$GroupCode]['GroupName'] = $t_['GroupName'];
                    $orders[$OrderCode]['Tests'][$GroupCode]['Tests'][$t_['Code']]['Name'] = $t_['Name'];
                    $orders[$OrderCode]['Tests'][$GroupCode]['Tests'][$t_['Code']]['Items'][$Date] = $t_;
                }
//                $o[$o_->Code] = $o_;
            }
            
//            $order = [];
//            $orders[$OrderCode] = $order;
        }
        
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
        $data = [];
        
        if($order && isset($orders[$order]['Tests'] ))
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
        if($order && $f_GrCode && $f_tests){
            
            $dbgtest =  DShop::_div(DShop::_div(DShop::_div("orders[$order]['Tests'][$f_GrCode]['Tests'][$f_tests]",'alert alert-warning'),'col-12'),'row');

            if($order && isset($orders[$order]['Tests'] )){
                $out = ('<pre>'.htmlspecialchars(print_r($orders[$order]['Tests'][$f_GrCode]['Tests'][$f_tests],1)).'</pre>');
                $dbgtest .= DShop::_div(DShop::_div(DShop::_div($out,'border border-primary mb-2'),'col-12'),'row');
            }
                
//            $hex_ = '34567erSDFGHJKtyuiasdfghklxcvbnm';
//            $hex = preg_replace('/[^0-9,a,b,c,d,e,f,A,B,C,D,E,F]/', '', $hex_);
//            echo  DShop::_div(DShop::_div(DShop::_div($hex_,'alert alert-warning'),'col-12'),'row');
//            echo  DShop::_div(DShop::_div(DShop::_div($hex,'alert alert-warning'),'col-12'),'row');
//            echo  DShop::_div(DShop::_div(DShop::_div(hexdec('Ff'),'alert alert-warning'),'col-12'),'row');
//            echo  DShop::_div(DShop::_div(DShop::_div(dechex(136),'alert alert-warning'),'col-12'),'row');
            global $wsdchart;
            
            $test = $orders[$order]['Tests'][$f_GrCode]['Tests'][$f_tests]['Items'];
            $DATA=[];
            $tx=[];
            $ty=[];
            $dt = [];
            $chtitle = '';
            $UnitName = '';
            $normVal = '';
            $NormsComment = '';
            $chDay = '';
            $chDayS = '';
            $chDayN = '';
            $chval = 0;
            $chmax=null;
            $chmin=null;
            $chmargin = 0;
            $chYfrom = 1000000;
            $chYto = 0;
            $chYmin = 1000000;
            $chYmax = 0;
            $setNorms = false;
            $showChart = true;
            $val_ = null;
            function toflt($v){ // to float
                return ((float)strtr($v,',','.'));
            }
            $chstart = true;
            $i = 0;
//            for($i=0;$i<=3;$i++){
                foreach($test as $date => $item_){
                    $key = explode('.',$date);//echo "".mktime( 3,0,10,$key[1],$key[2],$key[0]) ."000";
//                    $chDay =  (mktime( 0,0,0,$key[1],$key[0],$key[2]) ."000")+(1000*3600*24*$i*$i);
                    $chDay =  (mktime( 0,0,0,$key[1],$key[0],$key[2]) ."000"); // +(1000*3600*24*$i*$i);
                    if($chstart)
                    $chDayS = $chDay-(1000*3600*24);
                    $chDayN = $chDay+(1000*3600*24);
                    $item = [];
                    $item[0]=$item_['Name'];
                    $item[1]=$item_['Value'];
                    $item[2]=$item_['NormsComment'];
                    $item[3]=$item_['UnitName'];
                    $item[4]=$item_['Norms'];

                    $val = toflt($item_['Value']);
                    $chval = toflt($item_['Value']);

                    $chtitle = $item_['Name'];
                    $UnitName = $item_['UnitName'];
                    $normVal = 'Норма: '. $item_['Norms'];

                    if(isset($item_['NormPoint2'])){
                        $chmin = toflt($item_['NormPoint2']);
                    }else{
//                        $chmin = 0;
                    }
                    if(isset($item_['NormPoint3'])){
                        $chmax = toflt($item_['NormPoint3']);
                    }else{
//                        $chmax = 0;
                    }
                    if($val_!==null){
                        $chval = $val = $val_;
                    }
                    
////                    if(!$chstart){
//                        if($chval < $chmin){
//                            $val = $val + (($chmax-$chmin)/4)*$i;
//                        }else
//                        if($chval > $chmax){
//                            $val = $val - (($chmax-$chmin)/4)*$i;
//                        }else{
//                            $val = $val + (($chmax-$chmin)/4)*$i;
//                        }
//                        $val_=$val;
//            echo  DShop::_div(DShop::_div(DShop::_div((($chmax-$chmin)/4)*$i,'alert alert-warning'),'col-12'),'row');
//
////                    }

                    if($val<$chYmin)$chYmin = $val;
                    if($val>$chYmax)$chYmax = $val;
                    $dt[] = [$chDay,$val];

                    if(strlen($item_['NormsComment'])>0){
                        $NormsComment = ' ('. $item_['NormsComment'].')';
                    }
                    if(strlen($item_['Norms'])>0){
                        $setNorms = true;
                    }
//            echo  DShop::_div(DShop::_div(DShop::_div(   strlen(preg_replace('/[0-9,.]/','',trim($item_['Value']))) ,'alert alert-warning'),'col-12'),'row');
//            echo  DShop::_div(DShop::_div(DShop::_div(   (preg_replace('/[0-9\,.]/','',trim($item_['Value'].'...'))) ,'alert alert-warning'),'col-12'),'row');
                    if(
                            //strlen($item_['UnitName'])==0||
                            strlen(preg_replace('/[0-9\,\.]/','',trim($item_['Value']))) > 0 ){
//            echo  DShop::_div(DShop::_div(DShop::_div(   (preg_replace('/[0-9\,\.]/','',trim($item_['Value'].'...'))) ,'alert alert-warning'),'col-12'),'row');
                        $showChart = false;
                    }
                    $chstart = false;
                }
//            }
//            echo  DShop::_div(DShop::_div(DShop::_div(  $ht->pre($dt) ,'alert alert-warning'),'col-12'),'row');
//            echo  DShop::_div(DShop::_div(DShop::_div(  $ht->pre( var_export($showChart,1)) ,'alert alert-warning'),'col-12'),'row');
            $schCou=count($dt)+2;
            /** /
            $pre = "
            $chmargin = ($chmax-$chmin)/4;
            $chYfrom = $chmin;
            $chYto = $chmax;
            if($chYmin<$chYfrom)$chYfrom=$chYmin;
            if($chYmax>$chYto)$chYto=$chYmin;
            $chYfrom = $chYfrom-$chmargin;
            $chYto = $chYto+$chmargin;
                ";
            echo  DShop::_div(DShop::_div(DShop::_div($ht->pre($pre),'alert alert-warning'),'col-12'),'row');
            /**/
            
            // set max & min Y axis
            if($chmax===null || $chmin===null)$setNorms = false;
            if($chmax===null)$chmax=$chYmax+1;
            if($chmin===null)$chmin=$chYmin-1;
            $chmargin = ($chmax-$chmin)/4;
            if($chmargin<1)$chmargin=1;
            $chYfrom = $chmin;
            $chYto = $chmax;
            if($chYmin<$chYfrom)$chYfrom=$chYmin;
            if($chYmax>$chYto)$chYto=$chYmax;
            $chYfrom = $chYfrom-$chmargin;
            $chYto = $chYto+$chmargin;
            
            /** /
            $pre = "
            $chmargin = ($chmax-$chmin)/4;
            $chYfrom = $chmin;
            $chYto = $chmax;
            if($chYmin<$chYfrom)$chYfrom=$chYmin;
            if($chYmax>$chYto)$chYto=$chYmin;
            $chYfrom = $chYfrom-$chmargin;
            $chYto = $chYto+$chmargin;
                ";
            $pre2 = '
            $chmargin = ($chmax-$chmin)/4;
            $chYfrom = $chmin;
            $chYto = $chmax;
            if($chYmin<$chYfrom)$chYfrom=$chYmin;
            if($chYmax>$chYto)$chYto=$chYmin;
            $chYfrom = $chYfrom-$chmargin;
            $chYto = $chYto+$chmargin;
                ';
            echo  DShop::_div(DShop::_div(DShop::_div($ht->pre($pre2),'alert alert-warning'),'col-12'),'row');
            echo  DShop::_div(DShop::_div(DShop::_div($ht->pre($pre),'alert alert-warning'),'col-12'),'row');
            /**/
            
            if(0){
            
            $dt = array_pad($dt,20-count($dt),((float)strtr($item_['Value'],',','.'))+0);
//            $dt = array_pad($dt,count($DATA[0])-count($dt),$item_['Value']);
            $min = ((float)strtr($item_['NormPoint2'],',','.'));
            $max = ((float)strtr($item_['NormPoint3'],',','.'));
            for ($i=0;$i<20;$i++) {
                $DATA[0][]=rand($min,$max);
//                $DATA[1][]=rand($min,$max)/2;
//                $DATA[2][]=rand(0,100)/3;
                //$DATA["x"][]=$i;
                $tx[]=$i;
            }
            $DATA[]=$dt;
            // bar colors
            $lcolor = [];
            $lcolor [] = 'ff0000';
            $lcolor [] = '0000ff'; 
            $lcolor [] = '00ff00'; 
            $chinit = [
                'bgcol' => 'ffddff',
                'gridcol' => '888888',
                'chartcol' => 'b8b8b8',
                'linecol' => $lcolor,
                'spotR' => 7,
                'xtitle' => $tx,
                'ytitle' => [],
    //            'bgcol' => '',
                'data' => $DATA,
            ];
//            $wsdchart->set($chinit);
//            $wsdchart->build();
//            $chart = $wsdchart->show();
//            echo DShop::_div(DShop::_div(DShop::_div($chart,'border border-primary mb-2 p-3'),'col-12'),'row');
            }
            
            if($showChart){
//                http://www.humblesoftware.com/flotr2/documentation
//                https://github.com/HumbleSoftware/Flotr2
                $updateSize = false;
                $updateSize = true;
                $fontcss = '';
                if($updateSize){
                    $fontcss = "
                
#ml-chart *{
    font-size: 1.5em;
}
.flotr-grid-label-x{

}
                ";
                }
            $css = "
                
<style>
#ml-chart{
    width: 100%;
    height: 500px;
}
$fontcss
</style>
                
                ";
            echo $css;
            $chart = $ht->f('div','',['id'=>'ml-chart','style'=>'width=100%;height=400px;']);
            echo DShop::_div(DShop::_div(DShop::_div($chart,'border border-primary mb-2 p-3'),'col-12'),'row');
            ?>
  <script>
(function basic (container) {

  var
//    d1 = [[0, 3], [4, 8], [8, 5], [9, 13]], // First data series
    d1 = [ [<?=$chDay?>, <?=$chval?>]], // First data series
    d1 = <?=  json_encode($dt)?>, // First data series
    d2 = [],                                // Second data series
    d2 = [[8, 5]], // First data series
    d3 = [[<?=$chDayS?>, <?=$chmax?>], [<?=$chDayN?>, <?=$chmax?>], [<?=$chDayN?>, <?=$chmin?>], [<?=$chDayS?>, <?=$chmin?>]], // First data series
    i, graph;

//   Generate first data set
  for (i = 0; i < 14; i += 0.5) {
//    d2.push([i, Math.sin(i)]);
  }
   function  TrackFormatter (Obj){ 
//       console.log(Obj);/*alert(Obj.Y);*/
//       console.log(typeof Obj.x);/*alert(Obj.Y);*/
       var track ='',d,cou,ud,
       months = ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
       weekd = ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'];
       d = new Date(parseInt(Obj.x,10))
       console.log(Obj);
       console.log(Obj.y);
       console.log(Obj.series.data[Obj.index][1]);
       var y = Obj.series.data[Obj.index][1];
       return d.getDate()+' '+months[d.getMonth()]+' '+d.getFullYear()+' '+weekd[d.getDay()]
//               +' '+d.getHours()
               +',<br/> '+Obj.nearest.y+' <?=$UnitName?>';
//               +',<br/> кол-во: '+Obj.y;
       return Obj.Y+'77';
   }
   function  tickFormatter (Obj){ 
//       console.log(Obj);/*alert(Obj.Y);*/
//       console.log(typeof Obj.x);/*alert(Obj.Y);*/
       var track ='',d,cou,ud,
       months = ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
       weekd = ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'];
       d = new Date(parseInt(Obj,10))
       return d.getDate()+' '+months[d.getMonth()]+' '+d.getFullYear()+' '+weekd[d.getDay()]
       ;
//               +' '+d.getHours()
//               +',<br/> кол-во: '+Obj.y;
//       return Obj.Y+'77';
   }

  // Draw Graph
//  graph = Flotr.draw(container, [ d1, d2 ], {
  graph = Flotr.draw(container, [
//      { data : d2, label : '<?=$UnitName?>', lines : { show : true, fill:true }, points : { show : true } },
    <?php if($setNorms){?>
      { data : d3, label : '<?=$normVal?>', lines : { show : true, fill:true }, points : { show : true, size: 10 }, mouse : { track : false } },
    <?php } ?>
        { data : d1, label : '<?=$UnitName.$NormsComment?>', lines : { show : true, fill:false }, points : { show : true } }
  ], {
      <?php if(10){ ?>
//      colors:['blue','green','red'],
//      colors:['#ffddff','blue'],
        colors:['#ff8888','blue'],
        grid: {
          minorVerticalLines: true,
            backgroundColor : '#b8b8b8'
        },
        mouse : {
        mode : 'time', track : true, 
//        TrackFormatter: Function(Obj){ Return Obj.Y; },
//        TrackFormatter: TrackFormatter,
        trackFormatter: TrackFormatter,
        relative: true , Colsor: '#999999'
//            track : true,
//            relative : true
        },
        yaxis:{min:<?=$chYfrom?>,max:<?=$chYto?>,
            titleAngle : 45,
            noTicks: 9
        },
        xaxis: {
        fontSize: 14, // 7.5
//            tickFormatter: function(n){ return '('+n+')'; },
            tickFormatter: tickFormatter,
            min: <?=$chDayS?>,  // => part of the series is not displayed.
            max: <?=$chDayN?>, // => part of the series is not displayed.
            mode: 'time',
//            showLabels: true,
//            timeUnit: 'day',
//            minorTickFreq: 4
//            labelsAngle: 90,
//            title: 'x Axis',
            labelsAngle : -90, 
            titleAngle : 45
    ,
            noTicks: <?=$schCou?>
        }, 
        <?php if(!$updateSize) echo 'HtmlText: false,';?>
//        HtmlText: false,
//        fontSize: 14, // 7.5
        
        //HtmlText: true,
      
        title:'<?=$chtitle?>'
//        ,
//      spreadsheet : {
////        show : true,
////        tickFormatter : tickFormatter
////        tickFormatter : function (e) {
////            return e+'22';
////        }
//      ,  crosshair : {
//        mode : 'xy'
//      }
<?php }else{?>
        mouse : {
        mode : 'time', track : true, 
//        TrackFormatter: Function(Obj){ Return Obj.Y; },
//        TrackFormatter: TrackFormatter,
        trackFormatter: TrackFormatter,
        relative: true , Colsor: '#999999'
//            track : true,
//            relative : true
        },
      title: 'Download Image Example',
      subtitle: 'You can save me as an image',
      xaxis:{
        noTicks: 7, // Display 7 ticks.
        tickFormatter: function(n){ return '('+n+')'; }, // => displays tick values between brackets.
        min: 1,  // => part of the series is not displayed.
        max: 7.5, // => part of the series is not displayed.
        labelsAngle: 45,
        title: 'x Axis'
      },
      yaxis:{
        ticks: [[0, "Lower"], 10, 20, 30, [40, "Upper"]],
        max: 40,
        title: 'y = f(x)'
      },
      y2axis:{color:'#FF0000', max: 500, title: 'y = x^3'},
      grid:{
        verticalLines: false,
        backgroundColor: 'white'
      },
      HtmlText: false,
      legend: {
        position: 'nw'
      }
<?php }?>
  });
})(document.getElementById("ml-chart"));
  </script>
                <?php
  }
            
                
            $tests = [];
//            foreach($tests as $group => $test){
                $data = [];
//                $title = $group.' ';
                foreach($test as $date => $item_){
                        $title = $date.' '. $item_['GroupName'];
//                        $title = $item_['GroupName'];
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
                $out .= $ht->btabl($defs);
                echo DShop::_div(DShop::_div(DShop::_div($out,'border border-primary mb-2 p-4'),'col-12 '),'row');
//            }
                echo $dbgtest;
        }
        else
        if($order){
//            $out = ('<pre>'.htmlspecialchars(print_r($orders[$order],1)).'</pre>');
//            echo DShop::_div(DShop::_div(DShop::_div($out,'border border-primary mb-2'),'col-12'),'row');
        }
        
        if(0){
            $Referral = MedLab::_buildAttrs($xml->Referral);
            $DeliveryDate = $Referral['DeliveryDate'];
            $DoneDate = $Referral['DoneDate'];
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
                $out .= $ht->btabl($defs);
                echo DShop::_div(DShop::_div(DShop::_div($out,'border border-primary mb-2 p-4'),'col-12 '),'row');
            }
        }
    
                $data = [];
    
//    echo $ht->pre($xml);
        
$hitems = [];
//$hitems[]='<label>'.'$check'.'№</label>';
$hitems[]='Дата';
$hitems[]='Номер карты';
$hitems[]='Номер заказа';
$hitems[]='Сумма заказа';
$hitems[]='% бонуса';
$hitems[]='Состояние выплаты';
//if($showD)
//$hitems[]='doctor id';

    $usenumbers = true;
    $usenumbers = false;

$inorder = [];
//$inorder[] = 0-!$usenumbers;
//$inorder[] = 2;
//$inorder[] = 3;
//$inorder[] = 4;
//$inorder[] = 7;
//$inorder[] = 1-!$usenumbers;
//$inorder[] = 2-!$usenumbers;
//$inorder[] = 3-!$usenumbers;
//$inorder[] = 6-!$usenumbers;

$test_cols = 1;
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
//$cclass[0] = 1;
//$cclass[1] = 2;
//$cclass[] = 2;
//$cclass[] = 2;
//$cclass[] = 1;
//$cclass[] = 1;
//$cclass[] = 2;

//echo $ht->pre($urlget);
    $defs =[];
    $defs['usenumbers'] = $usenumbers;
//    $defs['cclass'] = $cclass;
    $defs['hitems'] = $hitems;
//    $defs['inorder'] = $inorder;
//    $defs['orders'] = $orders;
//    $defs['sortVName'] = 'order';
////    $defs['urlget'] = $urlget;
//    $defs['ma']='↓';
//    $defs['md']='↑';
//    $defs['sortClass']='btn';
    $defs['data'] = $data;
    
//    echo $ht->btabl($defs);
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
/*#ml-chart{
    width: 100%;
    height: 300px;
}*/
</style>
<!--<div id="ml-chart"></div>-->
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