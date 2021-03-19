<?php

/* 
 * tpl.dshop-item-ml-desc.php
 * 
 */

$ServiceId = filter_input(INPUT_GET, 'sid', FILTER_SANITIZE_NUMBER_INT);
if($ServiceId===false || $ServiceId===null|| $ServiceId==='')$ServiceId='0';
$_ServiceId = filter_input(INPUT_POST, 'sid', FILTER_SANITIZE_NUMBER_INT);
if(strlen($_ServiceId)>0)$ServiceId=$_ServiceId;

$productId = filter_input(INPUT_GET, 'pid', FILTER_SANITIZE_NUMBER_INT);
if($productId===false || $productId===null|| $productId==='')$productId='0';
$_productId = filter_input(INPUT_POST, 'pid', FILTER_SANITIZE_NUMBER_INT);
if(strlen($_productId)>0)$productId=$_productId;

$_productId = filter_input(INPUT_POST, 'product', FILTER_SANITIZE_NUMBER_INT);
if(strlen($_productId)>0)$productId=$_productId;




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

$priceList = [];

if(isset($analyses[$productId]))
foreach ($analyses as $aId => $a) 
{
//    $a = $analyses[$productId];
    // init
    if(!isset($groups[$a['AnalysisGroupId']]['analyses']))
        $groups[$a['AnalysisGroupId']]['analyses']=[];
//    $groups[$res['AnalysisGroupId']]['analyses'][$res['Id']] = $res['Name'];
    
    // set price
    $p = '--';
    if(isset($price[$a['Id']]['Price']))$p = $price[$a['Id']]['Price'];
    $groups[$a['AnalysisGroupId']]['analyses'][$a['Id']]
            = ['name'=> $a['Name'],'price'=>$p];
}

//if(isset($panels[$productId]))
foreach ($panels as $pId => $a)
{
//    $a = $panels[$productId];
    // init
    if(!isset($groups[$a['AnalysisGroupId']]['panels']))
        $groups[$a['AnalysisGroupId']]['panels']=[];
//    $groups[$res['AnalysisGroupId']]['analyses'][$res['Id']] = $res['Name'];
    
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
foreach ($groups as $gId => $value) {
    if(
            !isset($value['analyses'][$productId])
            && !isset($value['panels'][$productId]))continue;
    $items=[];
    ob_start();
    $cou = 0;
    if(isset($value['analyses'][$productId])){
        $cou = count($value['analyses']);
//        foreach ($value['analyses'] as $ikey => $ivalue) {
            $ikey = $productId;
            $ivalue = $value['analyses'][$productId];
            
            $id=$ikey;
            $code=$analyses[$ikey]['Code'];
            if(!isset($codes[$code]))$codes[$code]=1;
            else $codes[$code]++;
            $n=$ivalue['name'];
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
            $r['__name__'] = $n;
            $r['__price__'] = $p;
            $items[] = strtr($item_tpl_add,$r);
//        }
    }
    if(isset($value['panels'][$productId])){
        $cou = count($value['panels']);
//        foreach ($value['panels'] as $ikey => $ivalue)
//        {
            $ikey = $productId;
            $ivalue = $value['panels'][$productId];
            
            $id=$ikey;
            $code=$panels[$ikey]['Code'];
            if(!isset($codes[$code]))$codes[$code]=1;
            else $codes[$code]++;
            $n=$ivalue['name'];
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
            $r['__name__'] = $n;
            $r['__price__'] = $p;
            $items[] = strtr($item_tpl_add,$r);
//        }
    }
    $w = ob_get_clean();
    if(strlen($w)>0){
        echo div($gId,['class'=>'alert alert-info']);
        echo div($w,['class'=>'alert alert-warning']);
    }
    $r=[];
    $r['__tclass__'] = '-table-striped';
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
    if(!isset($value['panels'][$productId]))continue;
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
        if(isset($value['panels'][$productId]))
//        foreach ($value['panels'] as $ikey => $ivalue)
            {
            $ikey = $productId;
            $ivalue = $value['panels'][$productId];
            
            $id=$ikey;
            $code=$panels[$ikey]['Code'];
            $n=$ivalue['name'];
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
                    $r['__price__'] = $paP;
                    $paItems[] = strtr($item_tpl,$r);
                }
                    $r=[];
                    $r['__code__'] = '';
                    $r['__id__'] = '';
                    $r['__name__'] = 'Сумма';
                    $r['__price__'] = $psumm;
                    $paItems[] = strtr($item_tpl,$r);
                    
                $r=[];
                $r['__tclass__'] = '-table-striped';
                $r['__items__'] = implode("\n",$paItems);
                $table = strtr($table_tpl,$r);
                $n.=$table;
            }
            
            
            
            $r=[];
            $r['__code__'] = $code;
            $r['__id__'] = $ikey;
            $r['__id__'] = '{'.$r['__id__'].'}';
            $r['__name__'] = $n;
            $r['__price__'] = $p;
            $items[] = strtr($item_tpl_add,$r);
        }
    }
    $w = ob_get_clean();
    if(strlen($w)>0){
        echo div($gId,['class'=>'alert alert-info']);
        echo div($w,['class'=>'alert alert-warning']);
    }
    $r=[];
    $r['__tclass__'] = '-table-striped';
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