<?php
/*
 * tpl.dshop-account-fields-ml-v2.php
 */
global $ht;

$user = wp_get_current_user();
$r_access = [];
$r_access [] ='administrator';
//$r_access [] ='ml_administrator';
//$r_access [] ='ml_manager';
//$r_access [] ='ml_doctor';
$r_access [] ='ml_procedurecab';
$puid = $ht->postget( 'puid', $user->ID, FILTER_SANITIZE_NUMBER_INT);
if( $ht->access($r_access)){
    $user = get_userdata($puid);
}
$adres = $deliv = null;
$delivery_use = get_option('delivery_use', 0); // использовать ли доставку и адрес
if($user->exists()){
//    $user = get_current_user();
    $last_name = esc_attr(get_the_author_meta('last_name', $user->ID));
    $first_name = esc_attr(get_the_author_meta('first_name', $user->ID));
    $second_name = esc_attr(get_the_author_meta('second_name', $user->ID));
    $user_email = esc_attr(get_the_author_meta('user_email', $user->ID));
    $phone = esc_attr(get_the_author_meta('phone', $user->ID));
    $user_card = esc_attr(get_the_author_meta('card_numer', $user->ID));
    $passnum = esc_attr(get_the_author_meta('passnum', $user->ID));
    $id_citizen = esc_attr(get_the_author_meta('id_citizen', $user->ID));
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
    $user_card = '';
    $passnum = '';
    $id_citizen = '';
}
$delivs = [];
$delivs[] = 'ТК Энергия';
//$delivs[] = 'ТК Энергия 1';
//$delivs[] = 'ТК Энергия 2';

$readonly   = '';
$editable = false;
if(!$editable){
    $readonly = 'readonly';
}
$phone = preg_replace("/[^0-9]/", '', $phone);

ob_start();
?>
    <div class="row">
        <div class="col-12">
            <label class="mb-0 mt-2">Фамилия*</label>
        </div>
        <div class="col-5">
            <input type="text" name="lnm" class="form-control" value="<?=$last_name?>" required="" _readonly_>
        </div>
        <div class="col-12">
            <label class="mb-0 mt-2">Имя*</label>
        </div>
        <div class="col-5">
            <input type="text" name="fnm" class="form-control" value="<?=$first_name?>" required="" _readonly_>
        </div>
        <div class="col-12">
            <label class="mb-0 mt-2">Отчество*</label>
        </div>
        <div class="col-5">
            <input type="text" name="snm" class="form-control" value="<?=$second_name?>" required="" _readonly_>
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
        
        <div class="col-12">
            <label class="mb-0 mt-2">Телефон*</label>
        </div>
        <div class="col-5">
            <input type="number" name="phn" class="form-control"
                   aria-label="Default" aria-describedby="inputGroup-sizing-default"
                   value="<?=$phone?>" required="" _readonly_>
        </div>
        
        <div class="col-12">
            <label class="mb-0 mt-2">e-mail*</label>
        </div>
        <div class="col-5">
            <input type="email" name="eml" class="form-control" aria-label="Default"
                   aria-describedby="inputGroup-sizing-default"
                   value="<?=$user_email?>" required="" readonly="" >
        </div>
        
        <!---------------------------->
        
        <div class="col-12">
            <label class="mb-0 mt-2">№ карты</label>
        </div>
        <div class="col-5">
            <input type="number" name="ucard" class="form-control" aria-label="Default"
                   aria-describedby="inputGroup-sizing-default"
                   value="<?=$user_card?>" required="" _readonly_>
        </div>
        
        <div class="col-12">
            <label class="mb-0 mt-2">Номер паспорта</label>
        </div>
        <div class="col-5">
            <input type="text" name="upass" class="form-control" aria-label="Default"
                   aria-describedby="inputGroup-sizing-default"
                   value="<?=$passnum?>" required="" _readonly_ >
        </div>
        
        <div class="col-12">
            <label class="mb-0 mt-2">Идентификатор гражданина</label>
        </div>
        <div class="col-5">
            <input type="number" name="uidsitiz" class="form-control" aria-label="Default"
                   aria-describedby="inputGroup-sizing-default"
                   value="<?=$id_citizen?>" required="" _readonly_ >
        </div>
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

<?php
$html_form = ob_get_clean();

ob_start();
?>

<form action="" method="POST" class="ml-v2-form">
    <input type="hidden" id="pr_ft" name="form-type" value="update_account_ml">
    <input type="hidden" name="guest" value="1">
    <input type="hidden" id="pr_act" name="act" value="update_account_ml">
    <input type="hidden" id="puid" name="puid" value="<?=$puid?>">
<!--    <input type="hidden" id="pr_max" name="max[]" value="">
    <input type="hidden" id="pr_cou" name="cou[]" value="">-->
    
    <div class="row">
        <div class="col-10">
            <b class="ml-v2-form-title">Персональные данные</b>
        </div>
        <div class="col-2 text-right">
        <button class="btn btn-link" type="submit" name="go" value = "checkout"
                data-analytics-interaction="true" 
                data-analytics-interaction-label="PreBuyNow"
                data-analytics-interaction-value="[__prod_ID__]"
                data-analytics-interaction-custom-flow="PurchasingProcess"
                >Обновить</button>
        </div>
    </div>
    __fields__
</form>

<?php
$html_form_wrapper = ob_get_clean();

ob_start();
?>

<div  class="ml-v2-form">
    
    <div class="row">
        <div class="col-10">
            <b class="ml-v2-form-title">Персональные данные</b>
        </div>
        <div class="col-2 text-right">
        </div>
    </div>
    __fields__
</div>

<?php
$html_form_wrapper_no_edit = ob_get_clean();
$acc = ['administrator'];
$acc = [];
$acc [] ='administrator';
$acc [] ='ml_procedurecab';
if($editable || $ht->access($acc)){
    $r=[];
    $r['__fields__']=$html_form;
    $html_form = strtr($html_form_wrapper, $r);
    
    $readonly = '';
}else{
    $r=[];
    $r['__fields__']=$html_form;
    $html_form = strtr($html_form_wrapper_no_edit, $r);
    
}

$r=[];
$r['_readonly_']=$readonly;
$html_form = strtr($html_form, $r);

echo $html_form;