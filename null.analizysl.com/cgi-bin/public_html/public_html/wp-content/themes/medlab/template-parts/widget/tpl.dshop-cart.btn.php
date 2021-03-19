<?php

/* 
 * tpl.dshop-cart.btn.php
 */
// В корзине
?>
<!--<a href="/cart" id="test-add-to-cart"
   data-pid="10" data-count="10" data-act="add_c"
   class="btn btn-success">В корзине <span
        >add 1 pid 10</span></a>
<a href="/cart" id="test-remove-from-cart"
   data-pid="10" data-count="1" data-act="rem_c"
   class="btn btn-success">В корзине <span
        >remove 1 pid 10</span></a>-->
<a href="/cart/" id="go-to-cart-btn" class="btn btn-primary text-white mt-md-5 mt-3 mb-md-0 mb-3">Перейти к заказу <span
        id="count-in-cart"><?=dshop::_count_in_cart()?></span></a>