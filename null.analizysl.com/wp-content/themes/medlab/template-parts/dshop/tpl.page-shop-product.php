
            <div class="row">
                <div class="col-md-7">
                    [__prod_gall__]
                </div>
                <div class="col-md-5">
                    <div class="row">
                        <div class="col-md-12">
                            [__prod_title__]
                        </div>
                        <div class="col-md-12">
                            [__prod_ouner__]
                        </div>
                        <div class="col-md-12">
                            [__prod_cost__]
                        </div>
                        <div class="col-md-12">
                            [__prod_cost_delivery__]
                        </div>
                        <div class="col-md-12">
                            [__prod_cost_delivery_2__]
                        </div>
                        <div class="col-md-12">
<form action="/cart" method="POST">
    <input type="hidden" id="pr_ft" name="form-type" value="add_prod_to_cart">
    <input type="hidden" name="guest" value="1">
    <input type="hidden" id="pr_pid" name="pid" value="[__prod_ID__]">
    <input type="hidden" id="pr_act" name="act" value="add_c">
    <input type="hidden" id="pr_max" name="max" value="[__prod_count_max__]">
    Количество: 
    <button class="btn btn-primary" type="button" id="btn_cou_plus" >+</button>
    <input type="number" id="pr_cou" class=""
    name="cou" value="1" min="[__prod_count_min__]"
    max="[__prod_count_max__]" autocomplete="off" >
    <button class="btn btn-primary" type="button" id="btn_cou_minus" >-</button>
<!--    <div class="_85145af9">
        <div class="f8688fc0">Liczba sztuk</div>
        <div class="_6c8647b5">
            <div class="ad60a900 _0cc92fd1">
                <input type="number" class="e37c08a7 _515e38ba a80f2bbf"
                name="quantity" value="1" min="1" max="267" autocomplete="off">
                <button class="_147be966 b74ed635 _7bb48a2d" type="button">
                <div class="_56ab60e4 _1fd3fb05"></div>
                </button>
                        
            </div>
            <div class="f8688fc0">z 267 sztuk</div>
                
        </div>
            
    </div>-->
                            <!--[__ prod_quantity__]-->
    <div class="">
        <button id="add-to-cart-button" class="btn btn-primary"
        type="button" data-analytics-interaction="true"
        data-analytics-interaction-label="AddToCartItem"
        data-analytics-interaction-value="[__prod_ID__]"
        data-analytics-interaction-custom-flow="PurchasingProcess"
        >Добавить в корзину</button>
    </div>
    <button class="btn btn-primary" type="submit"
            data-analytics-interaction="true" 
            data-analytics-interaction-label="PreBuyNow"
            data-analytics-interaction-value="[__prod_ID__]"
            data-analytics-interaction-custom-flow="PurchasingProcess"
            >Купить сейчас</button>
</form>
                        </div>

                    </div>
                </div>
                <div class="col-md-12">
                    [__prod_source__]
                </div>
                <div class="col-md-12 prod_properties">
                    [__prod_properties__]
                </div>
                <div class="col-md-12">
                    [__prod_desc__]
                </div>
                <div class="col-md-12">
                    [__prod_numer__]
                </div>
            </div>