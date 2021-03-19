<?php

/* 
 * form-add-user-patient.php
 * [ml_component tpl="form" type="add-user-patient"]
 * 
 */


//    $tpl_name='ml-v2';
//    $tpl_name='';
//    $tpl_name = apply_filters('ds_styling_tpl_name', $tpl_name);
//    echo dshop::_get_account_fields($tpl_name);
    
//echo 'zzzzz';
$r_access = [];
$r_access [] ='administrator';
$r_access [] ='ml_administrator';
$r_access [] ='ml_manager';
$r_access [] ='ml_doctor';
$r_access [] ='ml_procedurecab';
$user = wp_get_current_user();
if(count( array_intersect($r_access, (array) $user->roles ) ) == 0 ){
    get_template_part( 'template-parts/page/tpl.page-access', 'denied' );
//    get_template_part( 'template-parts/page/tpl.page-access', 'notfound' );
    return null;
}
//add_log($user);
$adres = $deliv = null;
$delivery_use = get_option('delivery_use', 0)&&false; // использовать ли доставку и адрес
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

//update_account     checkout
?>

<form action="" method="POST" class="ml-v2-form">
    <input type="hidden" id="pr_ft" name="form-type" value="add_patient">
    <!--<input type="hidden" name="guest" value="<?php echo !$user->ID?>">-->
    <input type="hidden" id="pr_act" name="act" value="add_patient">
<!--    <input type="hidden" id="pr_max" name="max[]" value="">
    <input type="hidden" id="pr_cou" name="cou[]" value="">-->
    
    <div class="row">
        <div class="col-10">
            <b class="ml-v2-form-title"></b>
        </div>
        <div class="col-2 text-right">
        <button class="btn btn-link" type="submit" name="go" value = "add_patient"
                >Добавить</button>
        </div>
    </div>
    <?php if(0){ ?>
    <div class="row">
        <?php 
$r_access = [];
$r_access [] ='administrator';
$r_access [] ='ml_administrator';
$r_access [] ='ml_manager';
//$r_access [] ='ml_doctor';
$r_access [] ='ml_procedurecab';
//$user = wp_get_current_user();
if(count( array_intersect($r_access, (array) $user->roles ) ) > 0 ){
            $doctor_id = filter_input(INPUT_POST, 'doctor_id',FILTER_SANITIZE_NUMBER_INT);
            $var_pu=[];
            $var_pu['items'] = DShopExtensionMedLab::_get_doctors();
            $var_pu['items'][0] = 'Не указан';
            $var_pu['option_name'] = 'doctor_id';
            $var_pu['class'] = 'form-control    ';
            $var_pu['id'] = 'field_dso_puid';
    //        $var_pu['post_id'] = ''; // object
    //        $f_patient = DShopExtensionMedLab::_dshf_select($var_pu);
            $var_pu['res'] = $doctor_id;
    //        $dsP = new DShopPayment();
    //        $f_patient = $dsP->_cf_select($var_pu);
    //        $dsP = new DShopPayment();
            $f_sel_docter= DShopExtensionMedLab::_dshf_select_free($var_pu);
    
    ?>
        <div class="col-12">
            <label class="mb-0 mt-2">Доктор*</label>
        </div>
        <div class="col-5">
            <?php
            echo $f_sel_docter;
            ?>
        </div>
        <?php } ?>
        <div class="col-10">
            <b class="ml-v2-form-title">Персональные данные</b>
        </div>
        <div class="col-2 text-right">
        <button class="btn btn-link" type="submit" name="go" value = "checkout"
                data-analytics-interaction="true" 
                data-analytics-interaction-label="PreBuyNow"
                data-analytics-interaction-value="[__prod_ID__]"
                data-analytics-interaction-custom-flow="PurchasingProcess"
                >Добавить</button>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <label class="mb-0 mt-2">Фамилия*</label>
        </div>
        <div class="col-5">
            <input type="text" name="lnm" class="form-control" value="<?=$last_name?>" required="">
        </div>
        <div class="col-12">
            <label class="mb-0 mt-2">Имя*</label>
        </div>
        <div class="col-5">
            <input type="text" name="fnm" class="form-control" value="<?=$first_name?>" required="">
        </div>
        <div class="col-12">
            <label class="mb-0 mt-2">Отчество*</label>
        </div>
        <div class="col-5">
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
        <div class="col-12">
            <label class="mb-0 mt-2">Телефон*</label>
        </div>
        <div class="col-5">
            <input type="number" name="phn" class="form-control"
                   aria-label="Default" aria-describedby="inputGroup-sizing-default"
                   value="<?=$phone?>" required="">
        </div>
        <div class="col-12">
            <label class="mb-0 mt-2">e-mail*</label>
        </div>
        <div class="col-5">
            <input type="email" name="eml" class="form-control" aria-label="Default"
                   aria-describedby="inputGroup-sizing-default"
                   value="<?=$user_email?>" required="" readonly="" >
        </div>
        <div class="col-12">
            <label class="mb-0 mt-2">Пол</label>
        </div>
        <div class="col-5">
        <select name="gender" class="form-control" id="inputGroupSelectSex" required="">
            <!--<option selected>Choose...</option>-->
            <?php
            $gender = 0;
            $genders=[];
            $genders[0] = 'пол неизвестен';
            $genders[1] = 'мужской';
            $genders[2] = 'женский';
            $tpl_o_deliv = '<option value="_v_" _s_>_n_</option>'."\n";
            foreach($genders as $id=>$n){
                $r=[];
                $r['_v_']=$n;
                $r['_n_']=$n;
                $r['_s_']=selected($gender,$n,0);
                echo strtr($tpl_o_deliv,$r);
            }
            ?>
        </select>
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
    <?php } ?>
    <?php
    
    class pfp extends ProfileFields{
        
    public function _select( $val ){
        $id = $val['id'];
        $option_name = $val['name'];
        $tpl_o=<<<t
            <option value="_v_" _s_>_n_</option>
t;
        $r=[];
//        $v_=get_option($option_name,'');
        $v_=$val['val'];
        foreach($val['items'] as $v=>$n){
            $r['_n_']=$n;
            $r['_v_']=$v;
            $r['_s_']=$v_==$v?'selected="selected"':'';
            $val['items'][$v] = strtr($tpl_o,$r);
        }
        $o=implode('',$val['items']);
        ob_start();?>
    <select name="<?= $option_name ?>" class="form-control"
            id="<?= $id ?>" ><?= $o?></select>
        <?php
        return ob_get_clean();
    }
    }
    function initTplsProfileFields($obj=null){
        $table__=<<<td
    <h3>__title__:</h3>

    <table class="__class__">
              __rows__
    </table>
td;
        $td_s_=<<<td
            <tr>
                <th><label for="__for__">__label__</label></th>
                <td>__select__</td>
            </tr>
td;
        $td_d_=<<<td
            <tr>
                <th><label for="__for__">__label__</label></th>
                <td><input id="__id__" type="number" name="__name__" value="__val__" class="regular-text" /></td>
            </tr>
td;
        $td_i_=<<<td
            <tr>
                <th><label for="__for__">__label__</label></th>
                <td><input id="__id__" type="text" name="__name__" value="__val__" class="regular-text" /></td>
            </tr>
td;
        $td_t_=<<<td
            <tr>
                <th><label for="__for__">__label__</label></th>
                <td><span id="__id__" class="regular-text field-__name__"><b>__val__</b></span></td>
            </tr>
td;
        $td_ta_=<<<td
            <tr>
                <th><label for="__for__">__label__</label></th>
                <td><textarea cols="__cols__" rows="__rows__" id="__id__" name="__name__" class="-regular-text __i_class__" placeholder="__placeholder__">__val__</textarea></td>
            </tr>
td;
        $table__=<<<td
    <div class="row  ">
        <div class="col-10">
            <b class="ml-v2-form-title">__title__</b>
        </div>
        <div class="col-2 text-right">
        </div>
    </div>
    <div class="row">
            __rows__
    </div>
td;
        $td_s_=<<<td
        <div class="col-12">
            <label class="mb-0 mt-2"><label for="__for__">__label__</label></label>
        </div>
        <div class="col-5">
            __select__
        </div>
td;
        $td_d_=<<<td
        <div class="col-12">
            <label class="mb-0 mt-2"><label for="__for__">__label__</label></label>
        </div>
        <div class="col-5">
            <input id="__id__" type="number" name="__name__" value="__val__" class="form-control" />
        </div>
td;
        $td_i_=<<<td
        <div class="col-12">
            <label class="mb-0 mt-2"><label for="__for__">__label__</label></label>
        </div>
        <div class="col-5">
            <input id="__id__" type="text" name="__name__" value="__val__"
                class="form-control __i_class__" placeholder="__placeholder__" />
        </div>
td;
        $td_t_=<<<td
        <div class="col-12">
            <label class="mb-0 mt-2"><label for="__for__">__label__</label></label>
        </div>
        <div class="col-5">
            <span id="__id__" class="regular-text field-__name__"><b>__val__</b></span>
        </div>
td;
        $td_ta_=<<<td
        <div class="col-12">
            <label class="mb-0 mt-2"><label for="__for__">__label__</label></label>
        </div>
        <div class="col-5">
            <textarea cols="__cols__" rows="__rows__" id="__id__" name="__name__" class="form-control __i_class__" placeholder="__placeholder__">__val__</textarea>
        </div>
td;
        $obj->tpls['version'] = __CLASS__.' : '.__FUNCTION__ .' : '.__LINE__;
        $obj->tpls[__CLASS__.' : '.__FUNCTION__ .' : '.__LINE__] = '';
        $obj->tpls['table__'] = $table__;
        $obj->tpls['td_d_'] = $td_d_;
        $obj->tpls['td_s_'] = $td_s_;
        $obj->tpls['td_i_'] = $td_i_;
        $obj->tpls['td_t_'] = $td_t_;
        $obj->tpls['td_ta_'] = $td_ta_;
    }
$user = wp_get_current_user();
$user = new WP_User();
    
    $ProfileFields =  new pfp();
//    $del_keys = array_keys($ProfileFields->blocks, ['doctor','requisites']);
//    $del_vals = ['doctor','requisites'];
//    $blocks = ['gen','doctor','requisites','patient'];
//    $blocks = ['gen','patient'];
    $blocks = ['gen','patient'];
//    unset($ProfileFields->blocks[1]);
//    unset($ProfileFields->blocks[2]);
//    add_log($del_keys);
//    foreach ($ProfileFields->blocks as $del_key => $del_val) {
//        if(isset($del_vals[$del_key]))
//        unset($ProfileFields->blocks[$del_key]);
//    }
    
    $ProfileFields->blocks = $blocks;
    $ProfileFields->blocksLabels = [
        'gen'=>'Основные данные', // Общие данные
        'doctor'=>'Данные врача',
        'patient'=>'Дополнительные данные'];// Данные пациента
//    $ProfileFields->init($user);
        $ProfileFields->initFieldsGeneral($user);
//        $ProfileFields->initFieldsDoctor($user);
        $ProfileFields->initFieldsPatient($user);
        
            $var_pu = DShopExtensionMedLab::_get_doctors();
            $var_pu[0] = 'Не указан';
        $ProfileFields->fsel_opts['patient']['joined_doctor'] = $var_pu;
//        $doctor_key = array_search('joined_doctor', $ProfileFields->fields['patient']);
//        if($doctor_key !== false)
//        $ProfileFields->fieldtpls['patient'][$doctor_key] = 'td_s_';
        $ProfileFields->fieldtpls['patient'][2] = 'td_s_';
//        $ProfileFields->initTpls();
        initTplsProfileFields($ProfileFields);
        $ftpl=[];
        $ftpl[] = 'td_i_';
        $ftpl[] = 'td_i_';
        $ftpl[] = 'td_i_';
        $ftpl[] = 'td_i_';
        $ftpl[] = 'td_i_';
        $ProfileFields->fieldtpls['gen'] = $ftpl;
        $ProfileFields->buildBlocks();
    ?>
</form>
<br/>