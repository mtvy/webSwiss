<?php

/* 
 * tpl-ml--lp--ml-v-2.php
 */


$card_tpl =<<<c
    <div class="card ">
        <div class="card-header bg-primary text-white" id="__card_btn_id__">
            <h5 class="mb-0">
                <button class="btn btn-primary" data-toggle="collapse"
                        data-target="#__card_cont_id__" aria-expanded="__expanded__"
                        aria-controls="__card_cont_id__">
                    __title_group__
                </button> 
            </h5>sasa
        </div>

        <div id="__card_cont_id__" class="collapse __open_class__"
                aria-labelledby="__card_btn_id__" data-parent="#__wrupp_id__">
            <div class="-card-body">
              __content_group__
            </div>
        </div>
    </div>
c;

$card_tpl =<<<c
    <div class="card ">
        <div class="card-header bg-primary text-white __collapsed_class__" id="__card_btn_id__" data-toggle="collapse"
                        data-target="#__card_cont_id__" aria-expanded="__expanded__"
                        aria-controls="__card_cont_id__">
                    <span class="card-header-title">__title_group__</span> <span class="corner-wrapp"><span class="corner-down">&nbsp;</span></span><span class="card-cost">Цена</span>
        </div>

        <div id="__card_cont_id__" class="collapse __open_class__ bg-gray-grdnt-ml"
                aria-labelledby="__card_btn_id__" data-parent="#__wrupp_id__">
            <div class="-card-body">
              __content_group__
            </div>
        </div>
    </div>
c;

$card_tpl =<<<c
    <div class="card ">
        <div class="card-header bg-primary text-white __collapsed_class__" id="__card_btn_id__" data-toggle="collapse"
                        data-target="#__card_cont_id__" aria-expanded="__expanded__"
                        aria-controls="__card_cont_id__">
                    <span class="card-header-title">__title_group__</span> <span class="corner-wrapp"><span class="corner-down">&nbsp;</span></span>
        </div>

        <div id="__card_cont_id__" class="collapse __open_class__ bg-gray-grdnt-ml"
                aria-labelledby="__card_btn_id__" data-parent="#__wrupp_id__">
            <div class="-card-body">
              __content_group__
            </div>
        </div>
    </div>
c;
//      <th scope="col">#</th>

$table_tpl =<<<c
<table class="table table-borderless __tclass__">
  <tbody>
        __items__
  </tbody>
</table>
c;
//      <th scope="row">1</th>
$item_tpl =<<<c
    <tr>
      <td class="td-pr-code">__code__
      <td>__name__</td>
    </tr>
c;
//      <td>__price__</td>
$table_tpl_add =<<<c
<table class="table table-borderless __tclass__">
  <tbody>
        __items__
  </tbody>
</table>
c;
//      <th scope="row">1</th>
// <br/>__id__
//      <td><button class="btn btn-info add-to-cart-button" data-id="__id__">Add</button></td>
//      <td><a class="btn btn-dshop-ml-atc add-to-cart-button" data-id="__id__">Заказать</a></td>
//      <td><button class="btn btn-link add-to-cart-button" data-id="__id__">Заказать</button></td>
$item_tpl_add =<<<c
    <tr>
      <td class="td-pr-code">__code__</td>
      <td>__name__</td>
      <td>__price__</td>
      <td><a class="pl-3 pr-3 text-primary add-to-cart-button" href="#" data-id="__id__">Заказать</a></td>
    </tr>
c;
