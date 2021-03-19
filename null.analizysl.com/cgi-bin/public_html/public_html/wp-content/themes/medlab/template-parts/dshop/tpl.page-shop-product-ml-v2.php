<?php
/*
 * tpl.page-shop-product-ml-v2
 * tpl.page-shop-product-count-noedit.php
 * tpl.page-shop-product-count.php
 */
?>
[__admin_options__]
            <div class="row">
                <div class="col-md-2">
                    [[__prod_code__]]
                </div>
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-4">
                                    [__prod_cost__]
                                </div>
                                <div class="col-md-8">
        <form action="/cart" method="POST">
            <input type="hidden" id="pr_ft" name="form-type" value="add_prod_to_cart">
            <input type="hidden" name="guest" value="1">
            <input type="hidden" id="pr_pid" name="pid" value="[__prod_ID__]">
            <input type="hidden" id="pr_act" name="act" value="add_c">

            <input type="hidden" id="pr_max" name="max" value="[__prod_count_max__]">
            [__item_count__]

                                    <!--[__ prod_quantity__]-->
            <div class="">
                <button id="add-to-cart-button" class="btn btn-primary"
                type="button"
                data-label="AddToCartItem"
                data-pid="[__prod_ID__]" [__addtocart_disable__]
                >[__prod_bnt_addtocart_name__]</button>
                <?php if(0){?>
            <button id="add-to-cart-submit-button"  class="btn btn-primary" type="submit"
                    data-label="PreBuyNow"
                    data-pid="[__prod_ID__]" [__submit_disable__]
                    >[__prod_bnt_submit_name__]</button>
                <?php } ?>
            </div>
        </form>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <h1 class="product-title">[__prod_title__]<h1>
                                </div>
                                <div class="col-md-12">
                                    [__prod_desc__]
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 prod_properties">
                    [__prod_properties__]
                </div>
                <?php
                /*
                 * 
                <div class="col-md-12">
                    [__prod_source__]
                </div>
                <div class="col-md-12">
                    [__prod_numer__]
                </div>
                 */
                ?>
            </div>