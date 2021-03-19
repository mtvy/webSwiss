<?php
//define('TIMER_LOG', false);
if(! defined('TIMER_LOG'))define('TIMER_LOG', true);
//define('DICT_SAVE_LOG', false);
if(! defined('DICT_SAVE_LOG'))define('DICT_SAVE_LOG', true);
//return '';
/* 
 * list-price
 */
$show_dev_info = false;

$_mtime = microtime(1);

global $ht;
        
function http_build_attr(&$attr=[], $prefix='')
    {
//        array_walk($attr,[$this,'_http_build_attr'],$prefix);
        array_walk($attr,'_http_build_attr',$prefix);
        return implode(' ',$attr);
    }
    function _http_build_attr(&$item1, $key, $prefix) 
    {
        if($prefix)$prefix.='-';
        $item1 = "$prefix$key=\"$item1\"";
    }
    function pre($res='',$class=''){
        ob_start();
        echo '<pre>';
        echo htmlspecialchars( print_r($res,1));
        echo '</pre>';
        $cat3 = ob_get_clean();
        return $cat3;
}
function div($v,$attr=[]){
    return "<div ".http_build_attr($attr).">".$v."</div>";
}

function _buildAttrs($xml=false){
    $out = [];
    if($xml===false)return $out;
    foreach ($xml->attributes() as $key2 => $value2) {
        $out[$key2] = ''.$value2;
    }
    return $out;
}

function buildAttrs($xml=false,$contName=false,$idName=false){
    $out = [];
    if($xml===false || !$contName || !$idName)return $out;
    foreach ($xml->$contName->Item as $key => $value) {
    //    $g = $value->attributes(1);
    //    echo div('$g'.pre($g));
        $res = _buildAttrs($value);
        $out[$res[$idName]] = $res;
        $out[$res[$idName]]['item'] = $value;
    }
    return $out;
}
  
/*            ===================                */

        
//$groups=[];
//$price=[];
//
//$analyses=[];
//$tests=[];
//$biomaterials=[];
//$drugs=[];
//$microorganisms=[];
//$containers=[];
//$panels=[];

//    add_log('$version');

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
//        'meta_key'    => 'dsp_pid',
//        'meta_value'  => $prodId,
        'post_type'   => 'dsproduct',
        'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
    ];
$query = new WP_Query( $oargs );
$count =  $query->found_posts;

$short = [];
if($count>0 ){
    ob_start();
    $posts = get_posts( $oargs );
    $err = ob_get_clean();
    wp_reset_query();
    
    foreach( $posts as $item ){
        $short_id =  get_post_meta( $item->ID, 'dsp_pid', true );
        $short[$short_id] =  get_post_meta( $item->ID, 'dsp_short', true );
        
    }
}

//$groups = buildAttrs($xml,'AnalysisGroups','Id');
//$analyses = buildAttrs($xml,'Analyses','Id');
//$panels = buildAttrs($xml,'Panels','Id');
//$tests = buildAttrs($xml,'Tests','Id');
//$biomaterials = buildAttrs($xml,'Biomaterials','Id');
//$drugs = buildAttrs($xml,'Drugs','Id');
//$microorganisms = buildAttrs($xml,'Microorganisms','Id');
//$containers = buildAttrs($xml,'ContainerTypes','Id');
//$price = buildAttrs($xml,'Prices','ServiceId');

//    echo div('$groups'.pre($groups));

if($show_dev_info){
    echo div('count Analyses '.count($analyses));
    echo div('count Panels '.count($panels));
}
    
//    echo div('count Analyses '.count($xml->Analyses->Item));
//foreach ($xml->Analyses->Item as $key => $value) {
//    $res = [];
//    foreach ($value->attributes() as $key2 => $value2) {
//        $res[$key2] = ''.$value2;
//    }
//    
//    if(!isset($groups[$res['AnalysisGroupId']]['analyses']))
//        $groups[$res['AnalysisGroupId']]['analyses']=[];
////    $groups[$res['AnalysisGroupId']]['analyses'][$res['Id']] = $res['Name'];
//    
//    $p = '--';
//    if(isset($price[$res['Id']]['Price']))$p = $price[$res['Id']]['Price'];
//    $groups[$res['AnalysisGroupId']]['analyses'][$res['Id']]
//            = ['name'=> $res['Name'],'price'=>$p];
////    $price
////    AnalysisGroupId
//}

//    echo div('$groups'.pre($groups));
//  echo '<pre>';
//  echo  htmlspecialchars(print_r($xml,1)).'</pre>';

function arr_null_del($str){
    return strlen($str)>0;
}
function arr_sch_patter($sch=[]){
    $a =  [];
    foreach($sch as $k=>$v){
//        $a[$v] = '/('.$v.')/i';
        $a[$k] = '/('.$v.')/iu';
    }
    return $a;
}
function arr_sch_light($sch=[]){
    $a =  [];
    foreach($sch as $k=>$v){
//        $a[$v] = '<b><font color="red">'.$v.'</font></b>';
        $a[$k] = '<b><font color="red">\1</font></b>';
    }
    return $a;
}
function _strtolower($str){
    $r=[];
    $r['('] = ' ';
    $r[')'] = ' ';
    $r[','] = ' ';
    $r['.'] = ' ';
    $str = strtr($str,$r);
    $str = strtolower($str);
    return $str;
}
$found = [];
$searchst = $ht->postget('asch',false,FILTER_DEFAULT);
if($searchst){
//    add_log($searchst);
    $r=[];
    $r['['] = ' ';
    $r[']'] = ' ';
    $r['('] = ' ';
    $r[')'] = ' ';
    $r[','] = ' ';
    $r['.'] = ' ';
    add_log('поиск совпадений: '.$searchst);
    $searchst = strtr($searchst,$r);
//    $found = explode(' ',$searchst);
    $searchst = explode(' ',$searchst);
//    $found = array_filter($found, 'arr_null_del');
//    add_log('поиск совпадений: '.implode(', ',$searchst));
//    
//    $r=[];
//    $r[''] = '';
//    $rf = 'АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЬЫЪЭЮЯ';
//    $rt = 'абвгдеёжзийклмнопрсеуфхцчшщьыъэюя';
//    $rf = preg_split('//u',$rf, null, PREG_SPLIT_NO_EMPTY);
//    $rt = preg_split('//u',$rt, null, PREG_SPLIT_NO_EMPTY);
////    $rf = str_split($rf);
////    $rt = str_split($rt);
//    $r = array_combine($rf, $rt);
//    $searchst = strtr($searchst,$r);
//    $searchst = explode(' ',_strtolower($searchst));
    $searchst = array_filter($searchst, 'arr_null_del');
//    
//    $found = array_merge($found,$searchst);
//    $searchst = $found;
    $found = $searchst;
//    add_log($rf);
//    add_log($rt);
//    add_log($r);
    
//    add_log($searchst);
//    add_log($found);
//    add_log(arr_sch_patter($found));
//    add_log(arr_sch_light($found));
    
    $pregf = "/(".implode("|",$searchst).")/iu";
//    add_log($pregf);
}
$_short=$short;
//$found = [];
$priceList = [];
//setlocale(LC_ALL, 'ru_RU.CP1251', 'rus_RUS.CP1251', 'Russian_Russia.1251');
//setlocale(LC_ALL, 'ru_RU.CP1251', 'rus_RUS.CP1251', 'Russian_Russia.1251');
foreach ($analyses as $aId => $a) {
    // init
    if(!isset($groups[$a['AnalysisGroupId']]['analyses']))
        $groups[$a['AnalysisGroupId']]['analyses']=[];
//    $groups[$res['AnalysisGroupId']]['analyses'][$res['Id']] = $res['Name'];
    
    if($searchst){
        $r=[];
//        $r['('] = ' ';
//        $r[')'] = ' ';
//        $r[','] = ' ';
//        $r['.'] = ' ';
//        $str1 = _strtolower($a['Name']);
//        $str1 = strtr($str1,$r);
//        $str = explode(' ',$str1);
//        $str1r = array_filter($str, 'arr_null_del');
//        $found1 = array_intersect($searchst, $str1r);
        $found1 = preg_match_all($pregf, $a['Name'],$resf);
        if($found1){
//            add_log($resf);
//            add_log($found1);
        }
        
        $found2 = [];
        if(isset($short[$aId])){
//            $str2 = _strtolower($short[$aId]);
//            $str2 = strtr($str2,$r);
//            $str = explode(' ',$str2);
//            $str2r = array_filter($str, 'arr_null_del');
//            $found2 = array_intersect($searchst, $str2r);
            $found2 = preg_match_all($pregf, $short[$aId]);
        }
        
//        if($found1)$a['Name'] = strtr($a['Name'],arr_sch_light($found));
//        if($found2)$short[$aId] = strtr($short[$aId],arr_sch_light($found));
        foreach ($found as $k => $v) {
//            $string = "April 15, 2003";
//            $pattern = "/(\w+) (\d+), (\d+)/i";
//            $replacement = "\${1}1,\$3";
            
//            if($found1)$a['Name'] = preg_replace($a['Name'],arr_sch_light($found));
//            if($found2)$short[$aId] = preg_replace($short[$aId],arr_sch_light($found));
            if($found1)$a['Name'] = preg_replace(arr_sch_patter($found), arr_sch_light($found), $a['Name']);
            if($found2)$short[$aId] = preg_replace(arr_sch_patter($found), arr_sch_light($found), $short[$aId]);
        }
        if(!$found1 && !$found2){
            if(isset($groups[$a['AnalysisGroupId']]['analyses'][$a['Id']])){
                unset($groups[$a['AnalysisGroupId']]['analyses'][$a['Id']]);
            }
            continue;
        }
//        $found = $a['Id'];
//        add_log([$a['Name']]);
//        add_log([$str1,$str2]);
//        add_log([$str1r,$str2r]);
//        add_log([$found1,$found2]);
    }
//        add_log(['===================',$searchst]);
    
    // set price
    $p = '--';
    if(isset($price[$a['Id']]['Price']))$p = $price[$a['Id']]['Price'];
    $groups[$a['AnalysisGroupId']]['analyses'][$a['Id']]
            = ['name'=> $a['Name'],'price'=>$p];
}

foreach ($panels as $pId => $a) {
    // init
    if(!isset($groups[$a['AnalysisGroupId']]['panels']))
        $groups[$a['AnalysisGroupId']]['panels']=[];
//    $groups[$res['AnalysisGroupId']]['analyses'][$res['Id']] = $res['Name'];
    
    // search
    if($searchst){
//        $r=[];
//        $r['('] = ' ';
//        $r[')'] = ' ';
//        $r[','] = ' ';
//        $r['.'] = ' ';
////        $r['>'] = ' ';
////        $r['<'] = ' ';
//        $str1 = _strtolower($a['Name']);
//        $str1 = strtr($str1,$r);
//        $str = explode(' ',$str1);
//        $str1r = array_filter($str, 'arr_null_del');
//        $found1 = array_intersect($searchst, $str1r);
//        if($found1){
//            add_log('================');
//            add_log($a['Name']);
////            add_log($resf);
//            add_log($found1);
//            add_log('---------');
//            $found1 = preg_match_all($pregf, $a['Name'],$resf);
//            add_log($pregf);
//            add_log($resf);
//            add_log($found1);
//            add_log('================');
//        }
        $found1 = preg_match_all($pregf, $a['Name'],$resf);
        if($found1){
//            add_log($resf);
//            add_log($found1);
        }
        
        $found2 = [];
        $found3 = [];
        $found4 = [];
        if(isset($short[$pId])){
//            $str2 = _strtolower($short[$pId]);
//            $str2 = strtr($str2,$r);
//            $str = explode(' ',$str2);
//            $str2r = array_filter($str, 'arr_null_del');
//            $found2 = array_intersect($searchst, $str2r);
            $found2 = preg_match_all($pregf, $short[$pId]);
        }
        
            
        $panelAnalises = buildAttrs($a['item'],'PanelAnalyses','AnalysisId');
        {
            $paItems=[];
            $psumm = 0;
            foreach ($panelAnalises as $paId => $paV) {
//        $r2=[];
//        $r2['('] = ' ';
//        $r2[')'] = ' ';
//        $r2[','] = ' ';
//        $r2['.'] = ' ';
//        $r2['>'] = ' ';
//        $r2['<'] = ' '; 
//                $paN=$analyses[$paId]['Name'];
//                $str3 = _strtolower($analyses[$paId]['Name']);
//                $str3 = strtr($str3,$r);
//                $str = explode(' ',$str3);
//                $str3r = array_filter($str, 'arr_null_del');
//                $found3 = array_intersect($searchst, $str3r);
//                if($found3)$analyses[$paId]['Name'] = strtr($analyses[$paId]['Name'],arr_sch_light($found));
                $found3 = preg_match_all($pregf, $analyses[$paId]['Name'],$resf);
                if($found3)$analyses[$paId]['Name'] = preg_replace(arr_sch_patter($found), arr_sch_light($found), $analyses[$paId]['Name']);
        
                if(isset($short[$paId])){
//                    $str4 = _strtolower($short[$paId]);
//                    $str4 = strtr($str4,$r2);
//                    $str = explode(' ',$str4);
//                    $str4r = array_filter($str, 'arr_null_del');
//                    $found4 = array_intersect($searchst, $str4r);
                    $found4 = preg_match_all($pregf, $short[$paId]);
                }
//            if(  $found3){
//                add_log([$found3]);
//            }
//            if(  $found4){
//                add_log([$found4]);
//            }
            }
            if( $found3 || $found4){
//                add_log([$found3,$found4]);
            }
        }
        
//        if($found1)$a['Name'] = strtr($a['Name'],arr_sch_light($found));
//        if($found2)$short[$pId] = strtr($short[$pId],arr_sch_light($found));
        
        if($found1)$a['Name'] = preg_replace(arr_sch_patter($found), arr_sch_light($found), $a['Name']);
        if($found2)$short[$pId] = preg_replace(arr_sch_patter($found), arr_sch_light($found), $short[$pId]);
        
        if(!$found1 && !$found2 && !$found3 && !$found4){
            if(isset($groups[$a['AnalysisGroupId']]['panels'][$a['Id']])){
                unset($groups[$a['AnalysisGroupId']]['panels'][$a['Id']]);
            }
            continue;
        }
//        add_log([$groups[$a['AnalysisGroupId']]['panels'][$a['Id']]['name']]);
//        add_log([$str1,$str2]);
//        add_log([$str1r,$str2r]);
//        add_log([$found1,$found2]);
    }
    
    // set price
    $p = '--';
    if(isset($price[$a['Id']]['Price']))$p = $price[$a['Id']]['Price'];
        $groups[$a['AnalysisGroupId']]['panels'][$a['Id']]
                = ['name'=> $a['Name'],'price'=>$p];
}
        
$out = [];

include 'tpls/tpl-ml--lp--defoult.php';

$tpl_name='';
$tpl_name = apply_filters('ds_styling_tpl_name', $tpl_name);
//include 'tpls/tpl-ml--lp--ml-v-2.php'; //  list-price
$tpl_file_name = 'tpls/tpl-ml--lp--'.$tpl_name.'.php'; 
$dir = basename(__FILE__);
if(file_exists(__DIR__.'/'.$tpl_file_name))
    include $tpl_file_name; //  list-price

$dlrtpl = ' <a class="_class_" href="_href_">_name_</a>';
$dlr=[];
$dlr['_class_'] = 'btn btn-secondary';
$dlr['_class_'] = 'alz-desc-link';
$dlr['_name_'] = '(подробнее→)';
$dlr['_name_'] = '(подробнее)';

$codes=[];
$cards=[];
$open = true;

$oid = 0;
$url_order_edit = get_the_permalink( get_option('ds_pageid_order_edit',0) ) ;

if(isset( $_SESSION['ds_order'] ) && isset($_SESSION['ds_order']['act']) && $_SESSION['ds_order']['act'] == 'edit'){
    $oid = $_SESSION['ds_order']['oid'];
    $item_tpl_add = $item_tpl_order_edit;
}

foreach ($groups as $gId => $value) {
    $items=[];
    ob_start();
    $cou = 0;
    if(isset($value['analyses'])){
        $cou = count($value['analyses']);
        foreach ($value['analyses'] as $ikey => $ivalue) {
            $id=$ikey;
            $code=$analyses[$ikey]['Code'];
            if(!isset($codes[$code]))$codes[$code]=1;
            else $codes[$code]++;
            $n=$ivalue['name'];
            $n="<b>$n</b>";
            $p=$ivalue['price'];
            if($p == '--')continue;
            
            $dlr['_href_'] = get_the_permalink( 135 ).'?sid='.$ikey;
            $dlr['_href_'] = get_the_permalink( 180 ).'?pid='.$ikey;
            $dlr['_href_'] = get_the_permalink( get_option('ds_id_page_item') ).'?pid='.$ikey;
            $n.=strtr($dlrtpl,$dlr);
            
            $r=[];
            $r['__code__'] = $code;
            $r['__id__'] = $ikey;
            $r['__id__'] = '{'.$r['__id__'].'}';
            $r['__oid__'] = $oid;
            $r['__oiid__'] = $ikey;
            $r['__edit_url__'] = $url_order_edit;
            $r['__name__'] = $n;
            if(isset($short[$ikey])){
                $r['__name__'] .= '<br/>'. $short[$ikey];
            }
            $r['__price__'] = $p;
            $items[] = strtr($item_tpl_add,$r);
//            $items[] = strtr($item_tpl_order_edit,$r);
        }
    }
    if(0&&isset($value['panels'])){
        $cou = count($value['panels']);
        foreach ($value['panels'] as $ikey => $ivalue) {
            $id=$ikey;
            $code=$panels[$ikey]['Code'];
            if(!isset($codes[$code]))$codes[$code]=1;
            else $codes[$code]++;
            $n=$ivalue['name'];
            $n="<b>$n</b>";
            $p=$ivalue['price'];
            if($p == '--')continue;
            
            $dlr['_href_'] = get_the_permalink( 135 ).'?sid='.$ikey;
            $dlr['_href_'] = get_the_permalink( 180 ).'?pid='.$ikey;
            $dlr['_href_'] = get_the_permalink( get_option('ds_id_page_item') ).'?pid='.$ikey;
            $n.=strtr($dlrtpl,$dlr);
            
            $r=[];
            $r['__code__'] = $code;
            $r['__id__'] = $ikey;
            $r['__id__'] = '{'.$r['__id__'].'}';
            $r['__oid__'] = $oid;
            $r['__oiid__'] = $ikey;
            $r['__edit_url__'] = $url_order_edit;
            $r['__name__'] = $n;
            if(isset($short[$ikey])){
                $r['__name__'] .= '<br/>'. $short[$ikey];
            }
            $r['__price__'] = $p;
            $items[] = strtr($item_tpl_add,$r);
        }
    }
    $w = ob_get_clean();
    if(strlen($w)>0){
        echo div($gId,['class'=>'alert alert-info']);
        echo div($w,['class'=>'alert alert-warning']);
    }
    if(!$items)continue;
    $r=[];
    $r['__tclass__'] = 'table-striped';
//    $r['__tclass__'] = '';
    $r['__items__'] = implode("\n",$items);
    $table = strtr($table_tpl_add,$r);
    
    $t=isset($value['Name'])?$value['Name']:'{группа не задана}';
    $r=[];
    $wrName = 'accordion';
    $code = '';
    if(isset($value['Code'])) $code = $value['Code'];
    if($code!==''){
        if(!isset($codes[$code]))$codes[$code]=1;
        else $codes[$code]++;
    }
    $r['__wrupp_id__'] = $wrName;
    $r['__card_btn_id__'] = 'group_btn_'.$gId;
    $r['__card_cont_id__'] = 'group_items_'.$gId;
    $r['__title_group__'] = $t.' ('.$cou.')';
    if($show_dev_info){
        $r['__title_group__'] = $t.' ('.$cou.')'.' {'.$code.'}'.' {'.$gId.'}';
    }
    $r['__content_group__'] = $table;
    
    $r['__collapsed_class__'] = $open?'':'collapsed';
    $r['__open_class__'] = $open?'show':'';
    $r['__expanded__'] = $open?'true':'false';
    $cards[] = strtr($card_tpl,$r);
    $open = false;
}
// // code doubles
//foreach ($codes as $key => $value) {
//    if($value==1)unset($codes[$key]);
//}
//add_log('doublicated codes:');
//add_log($codes);

$cardsPanel=[];
$open = true;
foreach ($groups as $gId => $value) {
    if(!isset($value['panels']))continue;
    $items=[];
    ob_start();
    $cou = 0;
//    if(isset($value['analyses'])){
//        $cou = count($value['analyses']);
//        foreach ($value['analyses'] as $ikey => $ivalue) {
//            $id=$ikey;
//            $n=$ivalue['name'];
//            $p=$ivalue['price'];
//            $r=[];
//            $r['__id__'] = $ikey;
//            $r['__name__'] = $n;
//            $r['__price__'] = $p;
//            $items[] = strtr($item_tpl,$r);
//        }
//    }
    if(isset($value['panels'])){
        $cou = count($value['panels']);
        foreach ($value['panels'] as $ikey => $ivalue) {
            $id=$ikey;
            $code=$panels[$ikey]['Code'];
            $n=$ivalue['name'];
            $n="<b>$n</b>";
            $p=$ivalue['price'];
            
            $dlr['_href_'] = get_the_permalink( 135 ).'?sid='.$ikey;
            $dlr['_href_'] = get_the_permalink( 180 ).'?pid='.$ikey;
            $dlr['_href_'] = get_the_permalink( get_option('ds_id_page_item') ).'?pid='.$ikey;
            $n.=strtr($dlrtpl,$dlr);
            
            $panelAnalises = buildAttrs($panels[$ikey]['item'],'PanelAnalyses','AnalysisId');
            {
                $paItems=[];
                $psumm = 0;
                foreach ($panelAnalises as $paId => $paV) {
                    $id=$ikey;
                    $paN=$analyses[$paId]['Name'];
                    $paC=$analyses[$paId]['Code'];
                    $paP='--';
                    if(isset($price[$paId])){
                        $paP=$price[$paId]['Price'];
                        $psumm+=$paP;
                    }
                    
//                    $paP=$paV['price'];

                    $dlr['_href_'] = get_the_permalink( 135 ).'?sid='.$ikey;
                    $dlr['_href_'] = get_the_permalink( 180 ).'?pid='.$paId;
                    $dlr['_href_'] = get_the_permalink( get_option('ds_id_page_item') ).'?pid='.$ikey;
                    $paN.=strtr($dlrtpl,$dlr);

                    $r=[];
                    $r['__code__'] = $paC;
                    $r['__id__'] = $paId;
                    $r['__id__'] = '{'.$r['__id__'].'}';
                    $r['__name__'] = $paN;
            if(isset($short[$paId])){
                $r['__name__'] .= '<br/>'. $short[$paId];
            }
                    $r['__price__'] = $paP;
                    $paItems[] = strtr($item_tpl,$r);
                }
                    $r=[];
                    $r['__code__'] = '';
                    $r['__id__'] = '';
                    $r['__name__'] = 'Сумма';
                    $r['__price__'] = $psumm;
//                    $paItems[] = strtr($item_tpl,$r);
                    
                $r=[];
                $r['__tclass__'] = '-table-striped bg-gray-ml';
//                $r['__tclass__'] = ' bg-gray-ml';
                $r['__items__'] = implode("\n",$paItems);
                $table = strtr($table_tpl,$r);
                $n.=$table;
            }
            
            
            
            $r=[];
            $r['__code__'] = $code;
            $r['__id__'] = $ikey;
            $r['__id__'] = '{'.$r['__id__'].'}';
            $r['__oid__'] = $oid;
            $r['__oiid__'] = $ikey;
            $r['__edit_url__'] = $url_order_edit;
            $r['__name__'] = $n;
            if(isset($short[$ikey])){
                $r['__name__'] .= '<br/>'. $short[$ikey];
            }
            $r['__price__'] = $p;
            $items[] = strtr($item_tpl_add,$r);
        }
    }
    $w = ob_get_clean();
    if(strlen($w)>0){
        echo div($gId,['class'=>'alert alert-info']);
        echo div($w,['class'=>'alert alert-warning']);
    }
    if(!$items)continue;
    $r=[];
    $r['__tclass__'] = 'table-striped';
    $r['__items__'] = implode("\n",$items);
    $table = strtr($table_tpl_add,$r);
    
    $t=isset($value['Name'])?$value['Name']:'{группа не задана}';
    $r=[];
    $wrName = 'aw2';
    $r['__wrupp_id__'] = $wrName;
    $r['__card_btn_id__'] = 'group_btn_'.$gId.'_'.$wrName;
    $r['__card_cont_id__'] = 'group_items_'.$gId.'_'.$wrName;
    $r['__title_group__'] = $t.' ('.$cou.')';
    if($show_dev_info){
        $r['__title_group__'] = $t.' ('.$cou.')'.' {'.$value['Code'].'}'.' {'.$gId.'}';
    }
    $r['__content_group__'] = $table;
    $r['__open_class__'] = $open?'show':'';
    $r['__expanded__'] = $open?'true':'false';
    $cardsPanel[] = strtr($card_tpl,$r);
    $open = false;
}

$at = [];
$at ['name'] = 'asch';
$at ['type'] = 'text';
$at ['value'] = $found = $ht->postget('asch',false,FILTER_DEFAULT);
$fsch = $ht->f('input','',$at);
$at = [];
//$at ['name'] = 'sch';
$at ['type'] = 'submit';
$at ['value'] = 'Найти';
$fsch .= $ht->f('input','',$at);
//    echo $ht->f('div',$ht->f('form',$fsch));
    
    ?>
<form class="needs-validation mb-3" novalidate>
    <div class="form-row">
    </div>
    <div class="col-md-12 mb-12">
      <!--<label for="validationTooltipUsername"></label>-->
      <div class="input-group">
        <div class="input-group-prepend">
          <!--<input class="input-group-text" type="submit" id="validationTooltipUsernamePrepend" value="Найти:"/>-->
          <button class="input-group-text" type="submit">Найти:</button>
        </div>
          <input type="text" class="form-control" name ="asch" id="validationTooltipUsername" placeholder="Найти ..." aria-describedby="validationTooltipUsernamePrepend" required value="<?=$found?>">
        <div class="invalid-tooltip">
          Please choose a unique and valid username.
        </div>
      </div>
    </div>
    <!--<button class="btn btn-primary" type="submit">Submit form</button>-->
</form>
        <?php
    echo div(implode("\n",$cards),['id'=>'accordion','class'=>'medlab']);
    echo div('<hr/>');
    echo div(implode("\n",$cardsPanel),['id'=>'aw2']);
    

$mtime = microtime(1);

$_ts = $mtime - $_mtime;
$tl=[];
$tl['test'] = 'milisec build price';
$tl[] = $_ts;
$_ts = round($_ts,4);
$tl[] = $_ts;
//if(TIMER_LOG)add_log($tl);

//add_log(MedLab::_price());