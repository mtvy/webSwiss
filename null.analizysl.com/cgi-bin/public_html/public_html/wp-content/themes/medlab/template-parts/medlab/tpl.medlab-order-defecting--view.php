<?php

/* 
 * tpl.medlab-order-defecting--view.php
 */
global $ht;

?>
<div class="row">
    <div class="col-12">
        <?=$log_out_warning?>
        <?=$log_out?>
    </div>
</div>
<!--<div class="row">-->
<form action="<?=$this->url_order_defect_edit?>" method="post" class="row">
    <div class="col-12">
        <?php
    echo $ht->f('input','',['type'=>'hidden','name'=>'form-type','value'=>'order-defecting'])."\n";
    echo $ht->f('input','',['type'=>'hidden','name'=>'dso_status','value'=>'change_query'])."\n";
//        
//    echo $ht->f('input','',['type'=>'hidden','name'=>'order','value'=>$orderby])."\n";
//    echo $ht->f('input','',['type'=>'hidden','name'=>'count','value'=>$count])."\n";
//    echo $ht->f('input','',['type'=>'hidden','name'=>'offset','value'=>$offset])."\n";
        ?>
        <table class="table table-hover table-striped -table-dark">
            <caption>Список позиций заказа</caption>
            <thead>
                <tr>
                    <th scope="col">№</th>
                    <th scope="col">Название</th>
                    <th scope="col">Код</th>
                    <th scope="col">Состояние</th>
                    <th scope="col">Дефект</th>
                </tr>
            </thead>
            <tbody>
            <?=$order_items_list?>
            <tr>
              <!--<th scope="row"></th>-->
<!--                <td colspan="2">Добавить позицию:</td>
                <td>
                    <form method="post">
                        <input type="hidden" name="form-type" value="order_intems">
                        <input type="hidden" name="act" value="add_item">
                        <input type="hidden" name="oid" value="<?=$oid?>">-->
            <!--            <input type="hidden" name="dso_status" value="send_query">
                        <input type="hidden" name="dso_q_ref_comment" value="may be test">
                        <button type="submit" value="update" class="btn btn-primary text-white">Отправить</button>-->
<!--                        <button type="submit" value="update" class="btn btn-primary cart-button-remove"
                            onclick="jQuery('#item-cou-'+this.dataset.pid).val(0); return true;"
                            type="submit" name="go" value="cart" data-num='<?=$num-1?>' data-pid='<?=$pid?>'
                            ><big><b>+</b></big></button>
                    </form>
                </td>-->
            </tr>
            </tbody>
        </table>
    </div>
    <div class="col-12">
        <button type="submit" value="update" class="btn btn-primary cart-button-remove"
            onclick="jQuery('#item-cou-'+this.dataset.pid).val(0); return true;"
            type="submit" name="go" value="cart" data-num='<?=$num-1?>' data-pid='<?=$pid?>'
            ><big><b>Обновить заявку в лис</b></big></button>
    </div>
</form>
<!--</div>-->
<div class="row">
    <div class="col-12">11.17 Результаты по заявке
    </div>
    <div class="col-12">11.17.4 Атрибуты элемента Orders.Item
    </div>
    
    <div class="col-2">Название
    </div>
    <div class="col-2">Тип данных
    </div>
    <div class="col-8">Комментарий
    </div>
    
    <div class="col-2">State
    </div>
    <div class="col-2">int
    </div>
    <div class="col-8">
        <div class="alert alert-primary">(обязательно) Статус выполнения исследо-вания в ЛИС:<br/>
1 = новое<br/>
3 = сделано (но не одобрено)<br/>
4 = выполнено (одобрено)<br/>
5 = отменено (не может быть выпол-нено)<br/>
        </div>
    </div>
    
    <div class="col-2">Defected
    </div>
    <div class="col-2">boolean
    </div>
    <div class="col-8">
        <div class="alert alert-primary">Исследование не выполнено в связи с браком.
        </div>
    </div>
    
    <div class="col-2">Defects
    </div>
    <div class="col-2">string
    </div>
    <div class="col-8">
        <div class="alert alert-primary">Список наименований браков, из-за которых не удалось выполнить исследование. Если несколько браков, то они разделяются переводом строки.
        </div>
    </div>
    
    <div class="col-2">DefectCode
    </div>
    <div class="col-2">string
    </div>
    <div class="col-8">
        <div class="alert alert-primary">Список кодов браков, из-за которых не удалось выполнить исследование. Если несколько браков, то они разделяются пере-водом строки.
        </div>
    </div>
    
    <div class="col-2">
    </div>
    <div class="col-2">
    </div>
    <div class="col-8">
        <div class="alert alert-primary">
        </div>
    </div>
    
    <div class="col-2">
    </div>
    <div class="col-2">
    </div>
    <div class="col-8">
        <div class="alert alert-primary">
        </div>
    </div>
</div>
<div class="row">
    <form action="<?=get_the_permalink( 1948 ).$p?>" method="post" class="col-12 text-left">
        <?php
//        
//    echo $ht->f('input','',['type'=>'hidden','name'=>'order','value'=>$orderby])."\n";
//    echo $ht->f('input','',['type'=>'hidden','name'=>'count','value'=>$count])."\n";
//    echo $ht->f('input','',['type'=>'hidden','name'=>'offset','value'=>$offset])."\n";
        ?>
        <div class="row">
            <div class="col-3 text-left">
                <? //=$f_i_oid?>
            </div>
            <div class="col-3 text-left">
                <? //=$f_i_nr?>
            </div>
            <div class="col-12 text-left">
                <!--<button type="sumbit" class="btn btn-primary mt-3 mb-3">Применить</button>-->
            </div>
        </div>
    </form>
    <div class="col-3">
        <? //=$date_from||$date_to?'Выборка заказов ':''?>
    </div>
    <div class="col-3">
        <? //=$date_from?'С '.$date_from:''?>
    </div>
    <div class="col-3">
        <? //=$date_to?'По '.$date_to:''?>
    </div>
</div>
<?php

//echo $order_items_out;

echo $answer_out;
