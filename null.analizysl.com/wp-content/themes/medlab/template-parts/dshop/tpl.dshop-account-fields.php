<?php
/*
 * tpl.dshop-account-fields.php
 */
$user = wp_get_current_user();
$adres = $deliv = null;
$delivery_use = get_option('delivery_use', 0); // использовать ли доставку и адрес
if($user->exists()){
//    $user = get_current_user();
    $last_name = esc_attr(get_the_author_meta('last_name', $user->ID));
    $first_name = esc_attr(get_the_author_meta('first_name', $user->ID));
    $second_name = esc_attr(get_the_author_meta('second_name', $user->ID));
    $user_email = esc_attr(get_the_author_meta('user_email', $user->ID));
    $phone = esc_attr(get_the_author_meta('phone', $user->ID));
    if($delivery_use){
        $adres = esc_attr(get_the_author_meta('adres', $user->ID));
        $deliv = esc_attr(get_the_author_meta('deliv', $user->ID));
    }
}else{
//    $user = get_current_user();
    $last_name = '';
    $first_name = '';
    $second_name = '';
    $user_email = '';
    $phone = '';
    $adres = '';
    $deliv = '';
}
$delivs = [];
$delivs[] = 'ТК Энергия';
//$delivs[] = 'ТК Энергия 1';
//$delivs[] = 'ТК Энергия 2';
?>

<form action="" method="POST">
    <input type="hidden" id="pr_ft" name="form-type" value="update_account">
    <input type="hidden" name="guest" value="1">
    <input type="hidden" id="pr_act" name="act" value="update_account">
<!--    <input type="hidden" id="pr_max" name="max[]" value="">
    <input type="hidden" id="pr_cou" name="cou[]" value="">-->
    
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="">Фамилия, имя, отчество.</span>
        </div>
        <input type="text" name="lnm" class="form-control" value="<?=$last_name?>" required="">
        <input type="text" name="fnm" class="form-control" value="<?=$first_name?>" required="">
        <input type="text" name="snm" class="form-control" value="<?=$second_name?>" required="">
    </div>
    <?php if($delivery_use){?>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroup-sizing-default">Адрес</span>
        </div>
        <input type="text" name="adr" class="form-control"
               aria-label="Default" aria-describedby="inputGroup-sizing-default"
               value="<?=$adres?>" required="">
    </div>
    <?php }?>
    
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroup-sizing-default">Телефон</span>
        </div>
        <input type="number" name="phn" class="form-control"
               aria-label="Default" aria-describedby="inputGroup-sizing-default"
               value="<?=$phone?>" required="">
    </div>
    
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroup-sizing-default">Почта</span>
        </div>
        <input type="email" name="eml" class="form-control" aria-label="Default"
               aria-describedby="inputGroup-sizing-default"
               value="<?=$user_email?>" required="" readonly="" >
    </div>
    
    <?php if($delivery_use){?>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroup-sizing-default">Служба доствки</span>
        </div>
        <select name="dlv" class="custom-select" id="inputGroupSelect01" required="">
            <!--<option selected>Choose...</option>-->
            <?php
            $tpl_o_deliv = '<option value="_v_" _s_>_n_</option>'."\n";
            foreach($delivs as $id=>$n){
                $r=[];
                $r['_v_']=$n;
                $r['_n_']=$n;
                $r['_s_']=selected($deliv,$n,0);
                echo strtr($tpl_o_deliv,$r);
            }
            ?>
            <!--<option value="ТК Энергия" <?php selected($deliv,'ТК Энергия'); ?>>ТК Энергия</option>-->
<!--            <option value="2">Two</option>
            <option value="3">Three</option>-->
        </select>
        <!--<input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default">-->
    </div>
    <?php }?>
    <?php if(0){ ?>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroup-sizing-default"
                  >Согласие на обработку персональных данных</span>
<!--            <div class="input-group-text">
            </div>-->
        </div>
            <label class="form-control">
                <input type="checkbox" name="chk" value="1" required=""
                       class="" aria-label="Checkbox for following text input">
        <!--<input type="text" class="form-control"
        aria-label="Text input with checkbox">-->
            </label>
    </div>
    <?php } ?>
    
    <div class="input-group mb-3">
        <!--<a href="/checkout" class="btn btn-success">Оформить</a>-->
<!--        <button id="update-cart-button" class="btn btn-success"
                type="submit" name="go" value = "cart"
                data-analytics-interaction="true"
                data-analytics-interaction-label="AddToCartItem"
                data-analytics-interaction-value="[__prod_ID__]"
                data-analytics-interaction-custom-flow="PurchasingProcess"
                >Обновить корзину</button>-->
        <button class="btn btn-success" type="submit" name="go" value = "checkout"
                data-analytics-interaction="true" 
                data-analytics-interaction-label="PreBuyNow"
                data-analytics-interaction-value="[__prod_ID__]"
                data-analytics-interaction-custom-flow="PurchasingProcess"
                >Обновить</button>
    </div>
</form>