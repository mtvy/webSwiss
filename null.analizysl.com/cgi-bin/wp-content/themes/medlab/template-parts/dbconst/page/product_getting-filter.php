<?php

/* 
 * product_getting-filter.php
 */
global  $wpdb;
$names = [];
$q = "select `title` from `".$wpdb->prefix.'wsd_dbc_'.'wh_material_name'."`";
$catlist = $wpdb->get_col($q);
foreach($catlist as $cl){
    $names[] = '<option value="'.$cl.'">';
}
$categories = [];
$q = "select `title` from `".$wpdb->prefix.'wsd_dbc_'.'wh_material_type'."`";
$catlist = $wpdb->get_col($q);
foreach($catlist as $cl){
    $categories[] = '<option value="'.$cl.'">';
}
$codes = [];
$q = "select `catalog_num` from `".$wpdb->prefix.'wsd_dbc_'.'wh_material_name'."`";
$catlist = $wpdb->get_col($q);
foreach($catlist as $cl){
    $codes[] = '<option value="'.$cl.'">';
}
$manufs = [];
$q = "select `title` from `".$wpdb->prefix.'wsd_dbc_'.'wh_manufacturer'."`";
$catlist = $wpdb->get_col($q);
foreach($catlist as $cl){
    $manufs[] = '<option value="'.$cl.'">';
}
?>
<div class="container-fluid m-2 border border-primary mb-2">
    <div class="row">
        <div class="col-12">
            <label for="">Поиск товара</label>
        </div>
        <div class="col-3 form-group">
            <label for="">Название</label>
            <input type="text" value="" name="" id="mnf-name" class="form-control mn-find" list="matnames">
            <datalist id="matnames">
               <?=implode("\n",$names);?>
            </datalist>
        </div>
        <div class="col-3 form-group">
            <label for="">Категория</label>
            <input type="text" value="" name="" id="mnf-categ" class="form-control mn-find" list="categories">
            <datalist id="categories">
               <?=implode("\n",$categories);?>
            </datalist>
        </div>
        <div class="col-3 form-group">
            <label for="">Код в каталоге</label>
            <input type="text" value="" name="" id="mnf-code" class="form-control mn-find" list="catloglist">
            <datalist id="catloglist">
               <?=implode("\n",$codes);?>
            </datalist>
        </div>
        <div class="col-3 form-group">
            <label for="">Производитель</label>
            <input type="text" value="" name="" id="mnf-manuf" class="form-control mn-find" list="manuflist">
            <datalist id="manuflist">
               <?=implode("\n",$manufs);?>
            </datalist>
        </div>
<!--        <div class="col-3">
            <button type="sumbit" class="btn btn-primary -mt-3 -mb-3">Применить</button>
        </div>-->
    </div>
</div>