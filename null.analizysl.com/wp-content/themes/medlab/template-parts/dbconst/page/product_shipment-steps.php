<?php

/* 
 * product_shipment-steps.php
 */

$all = 0;
$step = 0;
//if($_SESSION['curier_doc_orders'])
//$step = 2;
$card_styles = "";
//$card_styles = "max-width: 18rem;";
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            <div class="card-deck justify-content-md-center">
                <div class="card <?=$step==1||$all?'text-white bg-success':'text-primary border-primary'?> mb-3" style="<?=$card_styles?>">
                  <div class="card-header">Step 1</div>
                  <div class="card-body">
                    <h5 class="card-title">Найти товары</h5>
                    <p class="card-text">В фильтре задать параметры нужного товара.</p>
                  </div>
                </div>
                <div class="card <?=$step==2||$all?'text-white bg-success':'text-primary border-primary'?> mb-3" style="<?=$card_styles?>">
                  <div class="card-header">Step 2</div>
                  <div class="card-body">
                    <h5 class="card-title">Добавить товары в накладную</h5>
                    <p class="card-text">Добавить нужные, убрать ошибочно добавленные.</p>
                  </div>
                </div>
                <div class="card <?=$all?'text-white bg-success':'text-primary border-primary'?> mb-3" style="<?=$card_styles?>">
                  <div class="card-header">Step 3</div>
                  <div class="card-body">
                    <h5 class="card-title">Создать накладную</h5>
                    <p class="card-text"></p>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>