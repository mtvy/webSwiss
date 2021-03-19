<?php

/* 
 * tpl-ml--lp--ml-v-2.php
 * lp list price
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


$tpl_btn_add=<<<c
    <div class="">
    <!--<a href="/checkout" class="btn btn-success">Оформить</a>-->
        <button id="update-cart-button" class="btn btn-primary" type="submit" name="go" value="cart" data-analytics-interaction="true" data-analytics-interaction-label="AddToCartItem" data-analytics-interaction-value="[__prod_ID__]" data-analytics-interaction-custom-flow="PurchasingProcess">Обновить корзину</button>
    <button class="btn btn-primary" type="submit" name="go" value="checkout" data-analytics-interaction="true" data-analytics-interaction-label="PreBuyNow" data-analytics-interaction-value="[__prod_ID__]" data-analytics-interaction-custom-flow="PurchasingProcess">Продолжить</button>
    </div>
c;

$tpl_btn_add_opts=<<<c
    <div class="row">
    <div class="col-12 text-right">
        <a href="_url_add_patient_" class="btn btn-primary text-white">Добавить пациента</a>
    </div>
    </div>
c;


$tpl__i_=<<<td
                    <div class="row">
                        <div class="col-12">
                            <label class="mb-0 mt-2" for="__for__">__label__</label>
                        </div>
                        <div class="col-12">
                            <input id="__id__" type="text" name="__name__"
                                value="__val__"
                                class="form-control __i_class__"
                                placeholder="__placeholder__" />
                        </div>
                    </div>
td;
$tpl_form_patient_by_doctor_filter=<<<c
    <div class="row">
        <form action="" method="post" class="col-12 text-left">
            <div class="row">
                <div class="col-12 text-left">
                    <div class="row">
                        <div class="col-12 text-left">
                            <label>Фильтровать по доктору</label>
                        </div>
                        <div class="col-12">
                            _select_
                        </div>
                    </div>
                </div>
                <div class="col-3 text-left">
                    _input_ufilter_code_
                </div>
                <div class="col-3 text-left">
                    _input_ufilter_fname_
                </div>
                <div class="col-3 text-left">
                    _input_ufilter_sname_
                </div>
                <div class="col-3 text-left">
                    _input_ufilter_nr_
                </div>
                <div class="col-12 text-left">
                    <button type="sumbit" class="btn btn-primary mt-3 mb-3">Применить</button>
                </div>
            </div>
        </form>
    </div>
c;
