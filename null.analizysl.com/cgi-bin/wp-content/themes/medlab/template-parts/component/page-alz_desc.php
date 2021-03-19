<?php

/* 
 * page-alz_desc
 */

$ServiceId = filter_input(INPUT_GET, 'sid', FILTER_SANITIZE_NUMBER_INT);
if($ServiceId===false || $ServiceId===null|| $ServiceId==='')$ServiceId='0';

$xml = MedLabXmlDesc::_getxml();
//add_log($xml);
//add_log('count AnalysisDescriptions: '.count($xml->AnalysisDescriptions->Item));
$exists = false;
foreach ($xml->AnalysisDescriptions->Item as $key => $item) {
    if($item['ServiceId']==$ServiceId){
        $title = $item['Title'];
        $desc = $item['Desc'];
        $exists = true;
    }
}
if(!$exists){
    $title = 'Описание не найдено';
    $desc = 'Описание не анализа найдено';
}
?>

    <div class="row">
        <div class="col-md-12 ">
           <b><?=$title?></b>
        </div>
        <div class="col-md-12">
            <?=$desc?>
        </div>
        <div class="col-md-12">
        <?php
        if( MedLabXmlDesc::_is_user_roles( ['administrator', 'contributor'] ) ){
            $tpl = '<a class="_class_" href="_href_">_name_</a>';
            $r=[];
            $r['_class_'] = 'btn btn-secondary';
            $r['_name_'] = 'Изменить';
            $r['_href_'] = get_the_permalink( 128 ).'?sid='.$ServiceId;
            echo strtr($tpl,$r);
        }?>
        </div>
    </div>