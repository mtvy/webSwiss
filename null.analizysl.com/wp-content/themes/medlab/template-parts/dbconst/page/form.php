<?php

/* 
 * form.php
 * form template
 */

//echo $ht->f('div', $this->return);

$rows = [];
foreach($this->fields_info as $name=>$field){
    ob_start();
    $att = [];
    $att['class'] = 'col-5';
    echo $ht->f('div', $this->form_label($name),$att);
    $att = [];
    $att['class'] = 'col-7 p-2';
    echo $ht->f('div', $this->form_field($name,'',($this->action == 'delete'?1:0)),$att);
    $row=ob_get_clean();
    $att = [];
    $att['class'] = 'row';
    $rows[] = $ht->f('div', $row,$att);
    
}
//add_log($this->fields_info);
//add_log($rows);

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
<div class="row m-2 border border-primary mb-2">
    <div class="col-12 "><h3><?=$this->form_title?></h3>
    </div>
    <?php
    if($this->action == 'delete'){
        ?>
    <div class="col-12 "><h4>Вы действительно хотите удалить запись?<br/> Данные будут удалены безвозвратно.</h4>
    </div>
            <?php
    }
    ?>
    <div class="col-12 ">
        <form method="post" action="" class="mb-5 mt-5 stripped-rows-h">
            <?=implode("\n",$this->form_hidden)?>
            <?=implode("\n",$rows)?>
            <div class="row">
                <div class="col-3 mt-5">
                    <?=$this->form_btn_submit('btn btn-primary',($this->action == 'delete'?"Вы действительно хотите удалить запись?":false))?>
                </div>
                <div class="col-9 mt-5">
                    <?php
//                    if($this->action == 'delete'){
    $ate = [];
    $ate['class'] = 'btn btn-success text-white mb-1';
    $ate['href'] = urldecode(($this->return )); // curier blank id
              echo $ht->f('a','отменить',$ate);
//                    }
                    ?>
                </div>
            </div>
        </form>
    </div>
</div>
