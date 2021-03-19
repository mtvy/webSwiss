<?php

/* 
 * tpl.dshop-cart-item.php
 */

?>zzz
    <input type="hidden" id="" name="pid[[num]]" value="[pid]">
    <button class="btn btn-info btn_cou_plus_cart" type="button" id=""
            data-pid="[pid]"
            data-max="[max]">+</button>
    <input type="number" id="item-cou-[pid]" class="pr_cou pr_cou-[pid]"
    name="cou[[num]]" value="[cou]" min="[min]"
    max="[max]" autocomplete="off" >
    <button class="btn btn-info btn_cou_minus_cart" type="button" id=""
            data-pid="[pid]"
            data-min="[min]">-</button>