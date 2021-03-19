<?php

/* 
 * tpl.page-shop.php
 */

?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            [__crumbs__]
        </div>
        <div class="col-md-3 order-md-1 mb-4">
            [__sidebar__]
        </div>
        <div class="col-md-9 order-md-2">
            [__product__]
        </div>
        <div class="col-md-12 order-md-3">
            [allegro_shop_products_rand]
        </div>
    </div>
</div>
<!-- single-product -->
<?php
// \woodmart\woocommerce\content-product.php
$name='woocommerce/single-product/product-image';
$name='woocommerce/content';
$slag='single-product';
echo '<!-- '.$name.'-'.$slag.' -->';
//get_template_part( $name,$slag);

	$hover = 'list';
?>
		<?php // wc_get_template_part( 'content', 'product-' . $hover ); ?>
<!-- /single-product -->
<?php
/*
<!-- PROMT TRANSLATOR --><link rel="stylesheet" type="text/css" href="https://www.translate.ru/css/promt_transl.css"/><script>function getTranslation(lng){var str_url=location.href;url_trans="https://www.translate.ru/forms/?direction=r"+lng+"&site="+str_url+"&clientID="; location.href=url_trans;}function tgglDir(){if (document.getElementById("ddAllLang").style.display == "none")document.getElementById("ddAllLang").style.display="inline";else{document.getElementById("ddAllLang").style.display="none"}}</script><div id="PROMT_Translator" class="f285"><a href='https://www.translate.ru/'><img src="https://www.translate.ru/img/ru/promt_sm.gif" align="left" hspace="3" vspace="5" border="0"/></a><div>Перевести эту страницу</div><div class='ddForm' id='trBlock'><a href='javascript:;' class='curnt' onclick='javascript:tgglDir();'><span style='padding-left:10px;'>Русский</span></a><span id='ddAllLang'style='display:none;'><a href='javascript:getTranslation("e");'>English</a><br/><a href='javascript:getTranslation("g");'>Deutsch</a><br/><a href='javascript:getTranslation("f");'>Français</a><br/><a href='javascript:getTranslation("s");'>Español</a><br/></span></div></div><!-- end PROMT TRANSLATOR -->
*/
?>