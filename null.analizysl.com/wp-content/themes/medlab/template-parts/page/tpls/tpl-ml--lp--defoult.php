<?php

/* 
 * tpl-ml--lp--defoult.php
 */


$card_tpl =<<<c
    <div class="card">
        <div class="card-header" id="__card_btn_id__">
            <h5 class="mb-0">
                <button class="btn btn-link" data-toggle="collapse"
                        data-target="#__card_cont_id__" aria-expanded="__expanded__"
                        aria-controls="__card_cont_id__">
                    __title_group__
                </button>
            </h5>
        </div>

        <div id="__card_cont_id__" class="collapse __open_class__"
                aria-labelledby="__card_btn_id__" data-parent="#__wrupp_id__">
            <div class="-card-body">
              __content_group__
            </div>
        </div>
    </div>
c;
//      <th scope="col">#</th>
if(0){ // old
$table_tpl =<<<c
<table class="table __tclass__">
  <thead>
    <tr>
      <th scope="col">Code</th>
      <th scope="col">Id</th>
      <th scope="col">Name</th>
      <th scope="col">Price</th>
    </tr>
  </thead>
  <tbody>
        __items__
  </tbody>
</table>
c;
//      <th scope="row">1</th>
$item_tpl =<<<c
    <tr>
      <td>__code__</td>
      <td>__id__</td>
      <td>__name__</td>
      <td>__price__</td>
    </tr>
c;
$table_tpl_add =<<<c
<table class="table __tclass__">
  <thead>
    <tr>
      <th scope="col">Code</th>
      <th scope="col">Id</th>
      <th scope="col">Name</th>
      <th scope="col">Price</th>
      <th scope="col">Add</th>
    </tr>
  </thead>
  <tbody>
        __items__
  </tbody>
</table>
c;
//      <th scope="row">1</th>
$item_tpl_add =<<<c
    <tr>
      <td>__code__</td>
      <td>__id__</td>
      <td>__name__</td>
      <td>__price__</td>
      <td><button class="btn btn-info add-to-cart-button" data-id="__id__">Add</button></td>
    </tr>
c;
}

$table_tpl =<<<c
<table class="table __tclass__">
  <thead>
    <tr>
      <th scope="col">Code</th>
      <th scope="col">Name</th>
      <th scope="col">Price</th>
    </tr>
  </thead>
  <tbody>
        __items__
  </tbody>
</table>
c;
//      <th scope="row">1</th>
$item_tpl =<<<c
    <tr>
      <td>__code__<br/>__id__</td>
      <td>__name__</td>
      <td>__price__</td>
    </tr>
c;
$table_tpl_add =<<<c
<table class="table __tclass__">
  <thead>
    <tr>
      <th scope="col">Code</th>
      <th scope="col">Name</th>
      <th scope="col">Price</th>
      <th scope="col">Add</th>
    </tr>
  </thead>
  <tbody>
        __items__
  </tbody>
</table>
c;
//      <th scope="row">1</th>
$item_tpl_add =<<<c
    <tr>
      <td>__code__<br/>__id__</td>
      <td>__name__</td>
      <td>__price__</td>
      <td><button class="btn btn-info add-to-cart-button" data-id="__id__">Add</button></td>
    </tr>
c;
