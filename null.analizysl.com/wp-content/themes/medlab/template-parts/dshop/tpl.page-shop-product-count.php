<?php

/* 
 * tpl.page-shop-product-count.php
 */

?>
    Кол-во: <br/>
    <button class="btn btn-primary" type="button" id="btn_cou_plus" >+</button>
    <input type="number" id="pr_cou" class=""
    name="cou" value="[__prod_count_def__]" min="[__prod_count_min__]"
    max="[__prod_count_max__]" autocomplete="off" >
    <button class="btn btn-primary" type="button" id="btn_cou_minus" >-</button>