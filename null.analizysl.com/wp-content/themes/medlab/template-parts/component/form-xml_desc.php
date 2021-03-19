<?php

/* 
 * form-xml_desc.php
 */

$ServiceId = filter_input(INPUT_GET, 'sid', FILTER_SANITIZE_NUMBER_INT);
if($ServiceId===false || $ServiceId===null|| $ServiceId==='')$ServiceId='0';
$_ServiceId = filter_input(INPUT_POST, 'sid', FILTER_SANITIZE_NUMBER_INT);
if(strlen($_ServiceId)>0)$ServiceId=$_ServiceId;
$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
if($title===false || $title===null|| $title==='')$title='';
$desc = filter_input(INPUT_POST, 'desc', FILTER_SANITIZE_STRING);
if($desc===false || $desc===null|| $desc==='')$desc='';

$user = wp_get_current_user();
//if($user->exists()){
////    $user = get_current_user();
//    $ServiceId = esc_attr(get_the_author_meta('sid', $user->ID));
//    $desc = esc_attr(get_the_author_meta('desc', $user->ID));
//}else{
////    $user = get_current_user();
//    $last_name = '';
//    $first_name = '';
//    $second_name = '';
//    $user_email = '';
//    $phone = '';
//    $adres = '';
//    $deliv = '';
//}

$xml = MedLabXmlDesc::_getxml();
//add_log($xml);
add_log('count AnalysisDescriptions: '.count($xml->AnalysisDescriptions->Item));
foreach ($xml->AnalysisDescriptions->Item as $key => $item) {
    if($item['ServiceId']==$ServiceId){
        $title = $item['Title'];
        $desc = $item['Desc'];
    }
}
?>

<form action="" method="POST">
    <input type="hidden" id="pr_ft" name="form-type" value="update_xml_desc">
    <input type="hidden" id="pr_act" name="act" value="update_xml_desc">
    <input type="hidden" name="guest" value="1">
    
<!--    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroup-sizing-default">Service Id</span>
        </div>
    <span class="input-group-text form-control"><?=$ServiceId?></span>
    </div>-->
    
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroup-sizing-default">Service Id</span>
        </div>
        <input type="text" name="sid" class="form-control"
               aria-label="Default" aria-describedby="inputGroup-sizing-default"
               value="<?=$ServiceId?>" required="">
    </div>
    
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroup-sizing-default">Заголовок</span>
        </div>
        <input type="text" name="title" class="form-control"
               aria-label="Default" aria-describedby="inputGroup-sizing-default"
               value="<?=$title?>" required="">
    </div>
    
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text">Описание</span>
        </div>
        <textarea class="form-control" name="desc" aria-label="Описание"><?=$desc?></textarea>
    </div>
    
    <?php if(0){ ?>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroup-sizing-default"
                  >Согласие на обработку персональных данных</span>
<!--            <div class="input-group-text">
            </div>-->
        </div>
            <label class="form-control">
                <input type="checkbox" name="chk" value="1" required=""
                       class="" aria-label="Checkbox for following text input">
        <!--<input type="text" class="form-control"
        aria-label="Text input with checkbox">-->
            </label>
    </div>
    <?php } ?>
    
    <div class="input-group mb-3">
        <button class="btn btn-success" type="submit" name="go" value = "checkout"
                >Обновить</button>
        &nbsp;
        <?php
            $tpl = '<a class="_class_" href="_href_">_name_</a>';
            $r=[];
            $r['_class_'] = 'btn btn-secondary';
            $r['_name_'] = 'Посмотреть';
            $r['_href_'] = get_the_permalink( 135 ).'?sid='.$ServiceId;
            echo strtr($tpl,$r);
        ?>
    </div>
</form>
