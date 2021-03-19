<?php

/* 
 * product_shipment.php
 */
global  $wpdb;
//выбирает
//количество, проставляет цену за единицу товара и упаковку

//должен поступить на основной склад.
//сотрудник из списка отделов и под разделов отмечает галочку на нужном товаре, ставит
//количество, проставляет цену, выбирает под склад и место хранения , выбирает
//производителя

//add_log('product_shipment');

$measure = [];
$q = "select `id`,`title` from `".$wpdb->prefix.'wsd_dbc_'.'wh_measurement'."`";
$catlist = $wpdb->get_results($q,ARRAY_A);
foreach($catlist as $cl){
    $measure[] = '<option value="'.$cl['id'].'">'.$cl['title'].'</option>';
}

$manufs = [];
$q = "select `id`,`title` from `".$wpdb->prefix.'wsd_dbc_'.'wh_manufacturer'."`";
$catlist = $wpdb->get_results($q,ARRAY_A);
foreach($catlist as $cl){
    $manufs[] = '<option value="'.$cl['id'].'">'.$cl['title'].'</option>';
}

$house = [];
$q = "select `id`,`title` from `".$wpdb->prefix.'wsd_dbc_'.'wh_house'."`";
$catlist = $wpdb->get_results($q,ARRAY_A);
foreach($catlist as $cl){
    $house[] = '<option value="'.$cl['id'].'">'.$cl['title'].'</option>';
}

$group = [];
$q = "select `id`,`title` from `".$wpdb->prefix.'wsd_dbc_'.'wh_house_group'."`";
$catlist = $wpdb->get_results($q,ARRAY_A);
foreach($catlist as $cl){
    $group[] = '<option value="'.$cl['id'].'">'.$cl['title'].'</option>';
}

$status = [];
$stlist = "0:Создано
1:Отправил
2:Принял
3:Пришло не полностью
4:Не пришло";
$stlist = explode("\n",$stlist);
//$status_ = $wpdb->get_results($q,ARRAY_A);
foreach($stlist as $cl_){
    $cl = explode(':',$cl_);
    $status[] = '<option value="'.$cl['0'].'">'.$cl['1'].'</option>';
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12" id="info_messages_wrap_found">
            
        </div>
    </div>
</div>
<form class="container-fluid m-2 border border-primary mb-2">
    <div class="row">
        
        <div class="col-4 form-group">
            <label for="">отправить в склад</label>
            <select name="whm-house_id" id="whwb-house_id" class="form-control" >
               <?=implode("\n",$house);?>
            </select>
        </div>
        
        <div class="col-4 form-group">
            <label for="">отправить в отдел / под склад</label>
            <select name="whm-group_id" id="whwb-group_id" class="form-control" >
               <?=implode("\n",$group);?>
            </select>
        </div>
        
        <div class="col-4 form-group">
            <label for="">статус накладной</label>
            <select name="whm-group_id" id="whwb-status" class="form-control" >
               <?=implode("\n",$status);?>
            </select>
        </div>
        
        <div class="col-12 form-group">
            <label for="">комментарий</label>
            <textarea name="whm-group_id" id="whwb-comment" class="form-control" ></textarea>
        </div>
        <div class="col-12">
            <!--warehouse create weybill-->
            <button type="sumbit" class="btn btn-primary mt-4 -mb-3 btn-block" id="whwb-create">Создать накладную</button>
        </div>
        <div class="col-12">
            <label for="">Позиции</label>
        </div>
        <div class="col-12 stripped-rows-h" id="weybillitems_added">
            
        </div>
    </div>
</form>
<form class="container-fluid m-2 border border-primary mb-2">
    <div class="row">
        <div class="col-12 mb-4">
            <!--warehouse order - add material-->
            <button type="sumbit" class="btn btn-primary mt-4 -mb-3 btn-block" id="whwb-clear">Очистить список товаров накладной</button>
        </div>
    </div>
<!--    <div id="mnf-found" class="row">

    </div>-->
</form>
<form class="container-fluid m-2 border border-primary mb-2">
    <div class="row">
        <div class="col-12">
            <!--warehouse weybill - add material-->
            <button type="sumbit" class="btn btn-primary mt-4 -mb-3 btn-block" id="whwb-add-material">Добавить товар в накладную</button>
        </div>
        <div class="col-12">
            <label for="">Название</label>
        </div>
        <div class="col-12 stripped-rows-h" id="mnf-found">
            
        </div>
    </div>
<!--    <div id="mnf-found" class="row">

    </div>-->
</form>