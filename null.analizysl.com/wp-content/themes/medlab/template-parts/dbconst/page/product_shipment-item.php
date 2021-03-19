<?php

/* 
 * product_shipment-item.php
 * item template
 */

//echo 'product_shipment-item';

//echo $ht->f('div', $this->return);

$rows = [];
foreach($this->fields_info as $name=>$field){
    ob_start();
    $att = [];
    $att['class'] = 'col-5';
    echo $ht->f('div', $this->form_label($name,0),$att);
    $att = [];
    $att['class'] = 'col-7 p-2';
//    echo $ht->f('div', $this->form_field($name,'',($this->action == 'delete'?1:0)),$att);
    echo $ht->f('div', $this->form_field($name,'',1),$att);
    $row=ob_get_clean();
    $att = [];
    $att['class'] = 'row';
    $rows[] = $ht->f('div', $row,$att);
    
}
$dso_query_nr = str_pad($this->id,20,'0',STR_PAD_LEFT);
$bc_attr=[];
$bc_attr['data-toggle'] = 'popover';
$bc_attr['title'] = 'Код накладной';
$bc_attr['data-content'] = 'Штрих код накладной';
        $barcode = MLBarcode::img($dso_query_nr);
//        $barcode = MLBarcode::img_file($dso_query_nr, WAREHOUSEUPLOADS,'wh_item');
        $barcode = MLBarcode::img_file($dso_query_nr, WAREHOUSEUPLOADS,'weybill',$bc_attr);
//add_log($this->fields_info);
//add_log($rows);
ob_start();
//$DBCWhWeybillItem = new DBCWhWeybillItem();
$DBCWhWeybillItem = new DBCWhWeybillItem('items','main','weybill_children');
$wbitems=ob_get_clean();
?>
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
<div class="row m-0">
    <div class="col-12 border border-primary mb-2 ">
    <div class="row m-2 ">
    <div class="col-12 "><h3>Накладная № <?=str_pad($this->id,20,'0',STR_PAD_LEFT) //$this->form_title?></h3>
    </div>
<!--         data-toggle="popover"
         title="Код накладной"
         data-content="Штрих код накладной"-->
    <div class="col-4 "
         >
                    <?=$barcode?>
    </div>
    <?php
    if($this->action == 'delete'){
        ?>
    <div class="col-12 "><h4>Вы действительно хотите удалить запись?<br/> Данные будут удалены безвозвратно.</h4>
    </div>
            <?php
    }
//        <form method="post" action="" class="mb-5 mt-5 stripped-rows-h">
    ?>
    <div class="col-12 ">
        <div class="mb-5 mt-5 stripped-rows-h">
            <?=implode("\n",$this->form_hidden)?>
            <?=implode("\n",$rows)?>
            <div class="row">
                <div class="col-3 mt-5">
                    <?php //=$this->form_btn_submit('btn btn-primary',($this->action == 'delete'?"Вы действительно хотите удалить запись?":false))?>
                </div>
                <div class="col-9 mt-5">
                    <?php
//                    if($this->action == 'delete'){
    $ate = [];
    $ate['class'] = 'btn btn-success text-white mb-1';
    $ate['href'] = urldecode(($this->return )); // curier blank id
//              echo $ht->f('a','отменить',$ate);
//                    }
                    ?>
                    <?php
        global $no_tab;
        if(!$no_tab){
    $ate = [];
    $ate['class'] = 'btn btn-success text-white mb-1';
    $ate['target'] = '_blank';
    $ate['href'] = urldecode(($this->return )); // curier blank id
    $blank_page_id = get_the_ID();
    $blank_page_id = 9483;
    $ate['href'] = get_the_permalink( $blank_page_id ) . '?tab=items&id='.$this->id;
              echo $ht->f('a','На печать',$ate);
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
</div>
<?=$wbitems?>