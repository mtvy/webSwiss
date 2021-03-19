<?php

/* 
 * tpl.medlab-analise-blank.php
 * [ml_page tpl="tpl.medlab" type="analise-blank" old=""]
 * [medlab page="tpl.medlab" type="analise-blank" old="__md_alz_desc__"]
 */

if(0){
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

if( !$count ) return '';
$dsorder = false;
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
        $state_ =  get_post_meta( $dsorder->ID, 'dso_status', true );
        $dso_total_ =  get_post_meta( $dsorder->ID, 'dso_cost', true );
        $puid =  get_post_meta( $dsorder->ID, 'dso_puid', true );
        
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
        
        $suid =  get_post_meta( $dsorder->ID, 'dso_sender', true ); // sender uid
        if($suid){
            $lab_fio = get_user_meta($suid,'last_name',1);
            $lab_fio .= ' '.get_user_meta($suid,'first_name',1);
            $lab_fio .= ' '.get_user_meta($suid,'second_name',1);
        }
        $address =  get_user_meta($suid, 'blank_address', true ); // sender uid
    }
}
}


$created_date = '';
global $show_blank_footer, $created_date;
$show_blank_footer = 'no';
include 'tpl.medlab-bill-blank.php';
?>

<div class="row justify-content-center">
    <div class="col-10 ">
<table width="100%" border="1" class="bl_tab_pr table-striped">
    <caption>
        на основе заказа
    </caption>
<?php

global $pids,$pids_log;
$containers_ = [];
global $ds_ext_ml;
$cont_info  = '';
if(isset($dsorder) && $dsorder && $ds_ext_ml){
    ob_start();
    $containers_ = $ds_ext_ml->display_meta_box_analise_desc( $dsorder, $meta = null, 1 ) ;
    $cont_info = ob_get_clean();
}
//add_log($pids);
//add_log($pids_log);
//add_log($containers_);

$tr_a = [];
$t=['Биоматериал','Контейнер'];
$tdb = "<td><b>_d_</b></td>";
$td = "<td>_d_</td>";
$tdb_ = "</b></td><td><b>";
$td_ = "</td><td>";
$tr = "<tr>_td_</tr>";
$thead = "<thead>_th_</thead>";
$tbody = "<tbody>_th_</tbody>";

$r=[];
$r['_d_'] = implode($tdb_,$t);
$r['_td_'] = strtr($tdb,$r);
$tr_a[] = strtr($tr,$r);

$r=[];
$th = implode("\n",$tr_a);
$r['_th_'] = implode("\n",$tr_a);
echo strtr($thead,$r);

$r=[];
$tr_a = [];
foreach ($containers_ as $key => $cont) {
    $r['_d_'] = implode($td_,$cont);
    $r['_td_'] = strtr($td,$r);
    $tr_a[] = strtr($tr,$r);
}

$r=[];
$th = implode("\n",$tr_a);
$r['_th_'] = implode("\n",$tr_a);
echo strtr($tbody,$r);


?>
</table>
    </div>
</div>

<table width="100%">
    <tr>
        <td width="" valign="top" colspan="3">
            Зарегистрирована: <?=$created_date?>
        </td>
    </tr>
    <tr>
        <td width="" valign="top" colspan="3"><b>
            Я, <?=$fio?> подтверждаю что мои личные данные и список исследований - верен, претензий
не имею.<br/>
Даю свое согласие на обработку персональных данных.<br/>
(более подробно можете ознакомиться на нашем сайте https://analizy.uz/privacy_policy.php),</b>
        </td>
    </tr>
    <tr>
        <td width="" valign="top" style="border-bottom: 1px solid black">
            &nbsp;
        </td>
        <td width="40px" valign="top">/&nbsp;дата:
        </td>
        <td width="30%" valign="top" colspan="" style="border-bottom: 1px solid black">
            &nbsp;
        </td>
    </tr>
</table>
    <?php
    
    return null;


$r_access = [];
$r_access [] ='administrator';
$r_access [] ='ml_administrator';
$r_access [] ='ml_manager';
//$r_access [] ='ml_doctor';
$r_access [] ='ml_procedurecab';
$user = wp_get_current_user();
if(count( array_intersect($r_access, (array) $user->roles ) ) == 0 ){
//    get_template_part( 'template-parts/page/tpl.page-access', 'denied' );
//    get_template_part( 'template-parts/page/tpl.page-access', 'notfound' );
    return null;
}

// построение запроса
$orderId=$oid;
$q = 'query-referral-results';
$atts = [];
$atts['is_show_test'] = true;
$query=[];
$numgroup = 9950000000;
$num = $numgroup;
$num = $num + $orderId;
$num = apply_filters( 'medlab_num_query_get', $num, $orderId, $numgroup );
$query['MisId'] = $orderId;
$query['Nr'] = $num;
$query['LisId'] = get_post_meta( $orderId, 'dso_query_id', true );
$atts['query'] = $query;
$data_ = MedLab::_queryBuild($q,$atts);
//add_log('<pre>'.htmlspecialchars(print_r($atts,1)).'</pre>');
//add_log('<pre>'.htmlspecialchars(print_r($data_,1)).'</pre>');

unset($atts['is_show_test']);
$data_ = MedLab::_queryBuild($q,$atts);

// получение ответа
$answer = doPostRequest($data_);
$xml = simplexml_load_string($answer);
$qrootAtt = MedLab::_buildAttrs($xml);

// обработка результатов
//                    $qrootAtt = MedLab::buildAttrs($xml);

//                    add_log((print_r($answer,1)));
//                    add_log(htmlspecialchars(print_r($xml,1)));
//                    add_log(htmlspecialchars(print_r($qrootAtt,1)));
//                    add_log($xml);
//                    add_log($qrootAtt);

//                    add_log('$answer<pre>'.htmlspecialchars(print_r($answer,1)).'</pre>');
                    
//update_post_meta( $orderId, 'dso_q__answer', print_r($xml,1) );
$answer_ =  get_post_meta( $orderId, 'dso_q__answer', true );
//add_log('$answer_<pre>'.htmlspecialchars(print_r($answer_,1)).'</pre>');

//                    add_log('LisId<pre>'.htmlspecialchars(print_r(''.$xml->Referral['LisId'],1)).'</pre>');
if(isset($xml->Referral) ){
    $atts = $xml->Referral->attributes();
    if(isset($atts['Done']) && $atts['Done'] == 'true'){
        update_post_meta( $orderId, 'dso_answer_status', 'got' );
        $status = 'answer_got';
//                            if($state_=='send_query')$status = 'query_sent';
//                            if($state_=='change_query')$status = 'query_sent';
        update_post_meta( $orderId, 'dso_status', $status );
    }
//                        $Items = $xml->Containers->Item;
//                        if(!is_array($Items)){
//                            $Items = [ $Items ];
//                        }
////                                add_log('count an bm : '.count($an_bm_));
//
//                        foreach($Items as $Item){
////                            $mess = $Item['Text'];
////                            add_log('Warnings: '.$mess);
//                        }
}

$bc_=[];
$bc_[0] = 'BiomaterialCode';

$bc_['59001'] = 'Контейнер с белой крышкой - 59001';
$bc_['50006'] = 'Пробирка с розовой крышкой - 50006';
$bc_['50013'] = 'Пробирка с фиолетовой крышкой - 50013';
$bc_['50004'] = 'Пробирка с желтой крышкой- 50004';
$bc_['50007'] = 'Пробирка с серой крышкой-  50007';
$bc_['50008'] = 'Пробирка с фиолетовой крышкой - 50008';
$bc_['50019'] = 'Пробирка с фиолетовой крышкой (Лейкоцитарная фракция) - 50019';
$bc_['12002'] = 'Предметное стекло (Мазок (глаз левый) - 12002';
$bc_['12001'] = 'Предметное стекло ( Мазок (глаз правый) - 12001';
$bc_['15004'] = 'Предметное стекло ( Мазок (задняя стенка глотки, зев)- 15004';
$bc_['14001'] = 'Туба с транспортной средой Amies с углем ( Мазок (нос)-  14001';
$bc_['14002'] = 'Туба с транспортной средой Amies с углем( Мазок (носоглотка)) - 14002';
$bc_['90028'] = 'Предметное стекло ( Мазок (секрет простаты)-  90028';
$bc_['13003'] = 'Туба с транспортной средой Amies с углем (Мазок (ухо левое)- 13003';
$bc_['13004'] = 'Туба с транспортной средой Amies с углем ( Мазок (ухо)-13004';
$bc_['90019'] = 'Туба с транспортной средой Amies с углем (Отделяемое ВДП) - 90019';
$bc_['90011'] = 'Туба с транспортной средой Amies с углем (Отделяемое ВДП (другое)) - 90011';
$bc_['90012'] = 'Туба с транспортной средой Amies с углем ( Отделяемое ВДП (зев)) - 90012';
$bc_['90013'] = 'Туба с транспортной средой Amies с углем ( Отделяемое ВДП (нос)) - 90013';
$bc_['90062'] = 'Туба с транспортной средой Amies с углем ( Отделяемое ВДП (носоглотка)) - 90062';
$bc_['90014'] = 'Туба с транспортной средой Amies с углем ( Отделяемое ВДП (пазухи)) - 90014';
$bc_['12008'] = 'Туба с транспортной средой Amies с углем ( Отделяемое глаза) - 12008';
$bc_['12004'] = 'Туба с транспортной средой Amies с углем ( Отделяемое глаза (левого) - 12004';
$bc_['12003'] = 'Туба с транспортной средой Amies с углем ( Отделяемое глаза (правого) - 12003';
$bc_['13008'] = 'Туба с транспортной средой Amies с углем ( Отделяемое из уха) - 13008';
$bc_['13001'] = 'Туба с транспортной средой Amies с углем (Отделяемое из уха (левого)-13001';
$bc_['13002'] = 'Туба с транспортной средой Amies с углем ( Отделяемое из уха (правого)) -13002';
$bc_['12006'] = 'Туба с транспортной средой Amies с углем ( Отделяемое конъюнктивы) -12006';
$bc_['14011'] = 'Предметное стекло( Отпечаток слизистой оболочки носа) -14011';

$BiomaterialCode=[];
$bc_v2 = [];

if(isset($xml->Blanks) && isset($xml->Blanks->Item) ){
    $Items = $xml->Blanks->Item;
//    if(!is_array($Items)){
//        $Items = [ $Items ];
//    }
//                                add_log('count an bm : '.count($an_bm_));

    $codes = [];
    $orders = [];
    foreach($Items as $ki => $Item){
//                            $mess = $Item['Text'];
//                            add_log('Warnings: '.$mess);
        ob_start();
        
//        $atts = $xml->$Item->attributes();
        $atts = $Item->attributes();
        $BlankId = $atts['BlankId'];
        $Name = $atts['Name'];
        $BlankGUID = $atts['BlankGUID'];
        $FileName = $atts['FileName'];
        
        $err_out = ob_get_clean();
        if(0 || $err_out){
            add_log($err_out);
            add_log(count($xml->Blanks->Item));
            add_log(count($Items));
            add_log($ki);
            add_log($Item);
        }
$r_access = [];
$r_access [] ='administrator';
//$r_access [] ='ml_administrator';
//$r_access [] ='ml_manager';
//$r_access [] ='ml_doctor';
//$r_access [] ='ml_procedurecab';
$user = wp_get_current_user();
if(count( array_intersect($r_access, (array) $user->roles ) ) > 0 ){
    
//            add_log($err_out);
//            add_log(count($xml->Blanks->Item));
//            add_log(count($Items));
//            add_log($ki);
//            add_log($Item);
}
//            add_log($ki);
        
        $Tests = $Item->Tests->Item;
        foreach($Tests as $kt => $Test){
            $_atts = $Test->attributes();
        //    add_log($Test);

        //    add_log($atts);
        //    add_log($_atts);
        //    $bc = $_atts['BiomaterialCode'];
        //    $oc = $_atts['OrderCode'];
        //    add_log($bc);
        //    add_log($oc);
        $bc = ''.$_atts['BiomaterialCode'];
        $oc = ''.$_atts['OrderCode'];
        $cc = ''.$_atts['Code'];
//        $codes[] = $_atts;
        $codes[$bc] = $bc;
        $orders[$oc] = $oc;
        //    add_log($bc);
        //    add_log($oc);
        //    if(!isset( $BiomaterialCode[$_atts['BiomaterialCode']])) $BiomaterialCode[$_atts['BiomaterialCode']] = [];
    if(!isset($bc_[$bc]))$bc_[$bc] = '';
            $BiomaterialCode[$bc]['Container']=$bc_[$bc];
            $BiomaterialCode[$bc][$oc]=''.$Name;
            
            $bc_v2[$oc][$cc]['Name'] = ''.$_atts['Name'];
            $bc_v2[$oc][$cc]['Container'] = $bc_[$bc];
        }
    }
//    add_log($BiomaterialCode);
//    add_log($bc_v2);
//    add_log($codes);
//    foreach($orders as $orders)
    
    $orders_=[];
    foreach($orders  as $ok=>$o){
        foreach($analyses  as $a){
            if($a['Code'] == $o){
                $orders[$ok] = $a['Id'];
//                add_log($a);
    //            $orders_[$a['Id']]=$a['Id'];
            }
        }
        if($orders[$ok] == $o){
            foreach($panels as $a){
                if($a['Code'] == $o){
                    $orders[$ok] = $a['Id'];
    //                add_log($a);
        //            $orders_[$a['Id']]=$a['Id'];
                }
            }
        }
    }
    
//    add_log($orders);
    $analises = $ds_ext_ml->dmb_build_analises( $orders ) ;
    $containers_ = $ds_ext_ml->dmb_an_build_containers($analises);

//    add_log($containers_);
if(count( array_intersect($r_access, (array) $user->roles ) ) > 0 ){
//    add_log($codes);
//    add_log($orders);
//    add_log($containers_);
//add_log($pids_log);
    
}
    
//$medLab = MedLab::_instance();
//        
//$groups = $medLab->groups;
//$analyses = $medLab->analyses;
//$panels = $medLab->panels;
//$tests = $medLab->tests;
//$biomaterials = $medLab->biomaterials;
//$drugs = $medLab->drugs;
//$microorganisms = $medLab->microorganisms;
//$containers = $medLab->containers;
//$price = $medLab->price;

//    add_log($codes);
//    add_log($biomaterials);
//    add_log($containers);
    
}

//                    2019923968063-968079.pdf
?>

<div class="row justify-content-center">
    <div class="col-10 ">
<table width="100%" border="1" class="bl_tab_pr table-striped">
    <caption>
        на основе ответа
    </caption>
<?php

//$containers_ = [];
//global $ds_ext_ml;
//if($dsorder && $ds_ext_ml){
//    $containers_ = $ds_ext_ml->display_meta_box_analise_desc( $dsorder, $meta = null, false ) ;
//}
$tr_a = [];
$t=['Биоматериал','Контейнер'];
$tdb = "<td><b>_d_</b></td>";
$td = "<td>_d_</td>";
$tdb_ = "</b></td><td><b>";
$td_ = "</td><td>";
$tr = "<tr>_td_</tr>";
$thead = "<thead>_th_</thead>";
$tbody = "<tbody>_th_</tbody>";

$r=[];
$r['_d_'] = implode($tdb_,$t);
$r['_td_'] = strtr($tdb,$r);
$tr_a[] = strtr($tr,$r);

$r=[];
$th = implode("\n",$tr_a);
$r['_th_'] = implode("\n",$tr_a);
echo strtr($thead,$r);

$r=[];
$tr_a = [];
foreach ($containers_ as $key => $cont) {
    $r['_d_'] = implode($td_,$cont);
    $r['_td_'] = strtr($td,$r);
    $tr_a[] = strtr($tr,$r);
}

$r=[];
$th = implode("\n",$tr_a);
$r['_th_'] = implode("\n",$tr_a);
echo strtr($tbody,$r);


?>
</table>
    </div>
</div>
    <?php
    echo $cont_info;
    
    