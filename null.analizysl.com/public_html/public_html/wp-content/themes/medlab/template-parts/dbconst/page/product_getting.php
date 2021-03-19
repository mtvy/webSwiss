<?php

/* 
 * product_getting.php
 */
global  $wpdb;
//выбирает
//количество, проставляет цену за единицу товара и упаковку

//должен поступить на основной склад.
//сотрудник из списка отделов и под разделов отмечает галочку на нужном товаре, ставит
//количество, проставляет цену, выбирает под склад и место хранения , выбирает
//производителя


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
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12" id="info_messages_wrap_found">
            
        </div>
    </div>
</div>
<form class="container-fluid m-2 border border-primary mb-2">
    <div class="row">
        
        <div class="col-3 form-group">
            <label for="">склад</label>
            <select name="whm-house_id" id="whm-house_id" class="form-control" >
               <?=implode("\n",$house);?>
            </select>
        </div>
        
        <div class="col-3 form-group">
            <label for="">отдел / под склад</label>
            <select name="whm-group_id" id="whm-group_id" class="form-control" >
               <?=implode("\n",$group);?>
            </select>
        </div>
        <div class="col-3 form-group">
            <label for="">цена за единицу</label>
            <input type="text" value="" name="whm-cost_item" id="whm-cost_item" class="form-control" list="">
        </div>
        <div class="col-3 form-group">
            <label for="">цена за упаковку</label>
            <input type="text" value="" name="whm-cost_pack" id="whm-cost_pack" class="form-control" list="">
        </div>
        
        <div class="col-3 form-group">
            <label for="">Количество (пришло)</label>
            <input type="number" value="" name="whm-count" id="whm-count" class="form-control" list="">
        </div>
        <div class="col-3 form-group">
            <label for="">Единица измерений (пришло)</label>
            <select name="whm-measurement" id="whm-measurement" class="form-control" >
               <?=implode("\n",$measure);?>
            </select>
        </div>
        
        <div class="col-3 form-group">
            <label for="">Количество/объём в единице</label>
            <input type="number" value="" name="whm-count_item" id="whm-count_item" class="form-control" list="">
        </div>
        <div class="col-3 form-group">
            <label for="">Единица измерений (штука)</label>
            <select name="whm-measurement_item" id="whm-measurement_item" class="form-control" >
               <?=implode("\n",$measure);?>
            </select>
        </div>
        
        <div class="col-3 form-group">
            <label for="">Количество/объём в упаковке</label>
            <input type="number" value="" name="whm-count_pack" id="whm-count_pack" class="form-control" list="">
        </div>
        <div class="col-3 form-group">
            <label for="">Единица измерений (пак)</label>
            <select name="whm-mesurement_pack" id="whm-mesurement_pack" class="form-control" >
               <?=implode("\n",$measure);?>
            </select>
        </div>
        
        <div class="col-3 form-group">
            <label for="">Количество/объём в коробке</label>
            <input type="number" value="" name="whm-count_box" id="whm-count_box" class="form-control" list="">
        </div>
        <div class="col-3 form-group">
            <label for="">Единица измерений (в коробке)</label>
            <select name="whm-measurement_box" id="whm-measurement_box" class="form-control" >
               <?=implode("\n",$measure);?>
            </select>
        </div>
        
<!--        <div class="col-3 form-group">
            <label for="">Производитель</label>
            <select name="" id="" class="form-control" >
               <?=implode("\n",$manufs);?>
            </select>
        </div>-->
        
        <div class="col-3 form-group">
            <label for="">номер стеллажа</label>
            <input type="number" value="" name="whm-stillage" id="whm-stillage" class="form-control" list="">
        </div>
        <div class="col-3 form-group">
            <label for="">номер полки</label>
            <input type="number" value="" name="whm-board" id="whm-board" class="form-control" list="">
        </div>
        <div class="col-3 form-group">
            <label for="">ширина (см)</label>
            <input type="number" value="" name="whm-pack_width" id="whm-pack_width" class="form-control" list="">
        </div>
        <div class="col-3 form-group">
            <label for="">высота (см)</label>
            <input type="number" value="" name="whm-pack_height" id="whm-pack_height" class="form-control" list="">
        </div>
        <div class="col-3 form-group">
            <label for="">длина (см)</label>
            <input type="number" value="" name="whm-pach_length" id="whm-pach_length" class="form-control" list="">
        </div>
        <div class="col-12">
            <!--warehouse create-->
            <button type="sumbit" class="btn btn-primary mt-4 -mb-3 btn-block" id="whm-create">Добавить</button>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <label for="">Название</label>
        </div>
        <div class="col-12 stripped-rows-h" id="mnf-found">
            
        </div>
    </div>
<!--    <div id="mnf-found" class="row">

    </div>-->
</form>d