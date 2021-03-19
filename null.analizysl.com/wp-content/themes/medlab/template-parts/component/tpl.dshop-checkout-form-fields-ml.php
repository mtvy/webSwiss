<?php

/* 
 * tpl-ml--dshop-checkout--form-fields.php
 * 
 * class.DShopExtensionMedLab.php
 * ds_chochout_form_fields
 */

global $ht;

$manage = false;


$manage = false;

$df_sex = 0;
$show_pweek = false;
$acc = ['ml_patient'];
if( is_user_logged_in() && $ht->access($acc) ){
    $user = wp_get_current_user();
    $df_sex = get_user_meta($user->ID,'gender',1);
//    $df_sex = 2;
    if($df_sex==2)
        $show_pweek = true;
}
        $dsP = new DShopPayment();
$user = wp_get_current_user();
if($user->exists()){
//$user = wp_get_current_user();
$r_access = [];
$r_access [] ='administrator';
$r_access [] ='ml_administrator';
$r_access [] ='ml_manager';
$r_access [] ='ml_doctor';
$r_access [] ='ml_procedurecab';
if(count( array_intersect($r_access, (array) $user->roles ) ) > 0 ){
//    get_template_part( 'template-parts/page/tpl.page-access', 'denied' );
//    get_template_part( 'template-parts/page/tpl.page-access', 'notfound' );
//    return null;
    $manage = true;
}
// var_dump($manage);
// var_dump($user->roles);

?>
    <?php
    if( $manage || current_user_can( 'manage_options' ) || in_array( 'ml_doctor', (array) $user->roles ) ){
//        add_log($user->roles);
//        $patient_id = filter_input(INPUT_POST, 'patient_id',FILTER_SANITIZE_NUMBER_INT);
        
        $patient_id = 0;
        if(isset($_SESSION['dso_puid_remember']))
            $patient_id = $_SESSION['dso_puid_remember'];
        
        $patient_id = $ht->post('patient_id',$patient_id,FILTER_SANITIZE_NUMBER_INT);
        
        $df_sex = get_user_meta($patient_id,'gender',1);
        if($df_sex==2)
            $show_pweek = true;
        else
            $show_pweek = false;
        
        $df_joined_doctor = get_user_meta($patient_id,'joined_doctor',1);
        
        $is_doctor = false;
        if( in_array( 'ml_doctor', (array) $user->roles ) ){
            $is_doctor = true;
        }
        $var_pu=[];
        $var_pu['items'] = DShopExtensionMedLab::_get_patients($is_doctor);
        $var_pu['option_name'] = 'dso_puid';
        $var_pu['id'] = 'field_dso_puid';
//        $var_pu['post_id'] = ''; // object
//        $f_patient = DShopExtensionMedLab::_dshf_select($var_pu);
        $var_pu['res'] = $patient_id;
//        $dsP = new DShopPayment();
        $f_patient = $dsP->_cf_select($var_pu);
        
        
$r_access = [];
//$r_access [] ='administrator';
//$r_access [] ='ml_administrator';
//$r_access [] ='ml_manager';
$r_access [] ='ml_doctor';
//$r_access [] ='ml_procedurecab';
if(count( array_intersect($r_access, (array) $user->roles ) ) == 0 ){
            $doctor_id = filter_input(INPUT_POST, 'doctor_id',FILTER_SANITIZE_NUMBER_INT);
            $doctor_id = 0;
//            if(isset($_SESSION['dso_puid_remember']))
//                $patient_id = $_SESSION['dso_puid_remember'];
            $doctor_id = $ht->post('doctor_id',$doctor_id,FILTER_SANITIZE_NUMBER_INT);
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
        }
        
        $copy_email = '';
        $email_readonly='';
        $logged='';
        $email_required='required=""';
        $email_required='';
?>
<style>
    .is_hidden{display: none;}
</style>
    <div class="row border border-primary mt-1 pb-2">
        <div class="col-12">
            <label>Данные пациента:</label>
        </div>
        <div class="col-12">
            <label class="mb-0 mt-2">Пациент*</label>
        </div>
        <div class="col-5">
            <select name="patient_id" class="form-control" >
            <?php
            echo $f_patient;
            ?></select>
        </div>
        <?php
        
$r_access = [];
$r_access [] ='administrator';
$r_access [] ='ml_administrator';
$r_access [] ='ml_manager';
//$r_access [] ='ml_doctor';
$r_access [] ='ml_procedurecab';
if(count( array_intersect($r_access, (array) $user->roles ) ) > 0 ){
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
    </div>
        <?php
        
$r_access = [];
$r_access [] ='administrator';
$r_access [] ='ml_administrator';
$r_access [] ='ml_manager';
//$r_access [] ='ml_doctor';
$r_access [] ='ml_procedurecab';
if(0 &&  count( array_intersect($r_access, (array) $user->roles ) ) > 0 ){
    ?>
    <div class="row border border-primary mt-1 pb-2">
        <div class="col-12">
            <label>Отправить копию на e-mail:</label>
        </div>
        <div class="col-12">
            <label class="mb-0 mt-2">e-mail</label>
        </div>
        <div class="col-5">
            <input type="email" name="eml-copy" class="form-control" aria-label="Default"
                   id="order_field_email_copy"
                   aria-describedby="inputGroup-sizing-default"
                   value="<?=$copy_email?>" <?=$email_required?> <?=$email_readonly?> data-logged="<?=$logged?>">
        </div>
    </div>
        <?php } ?>
<?php

    }
}
        
        $sel=[];
        $sel[0] = 'пол неизвестен';
        $sel[1] = 'мужской';
        $sel[2] = 'женский';
        $f_gender = $dsP->_cf_select(['items'=>$sel,'res'=>$df_sex]);
        
        $sel=[];
        $sel[0] = 'Нет';
        $sel[1] = 'Да';
        $f_pregnancy = $dsP->_cf_select(['items'=>$sel,'res'=>0]);
        
        $sel = range(0, 50);
        $f_pregnancy_week = $dsP->_cf_select(['items'=>$sel,'res'=>0]);
    ?>
<style>
    .is_hidden{display: none;}
</style>
    <div class="row border border-primary mt-1 pb-2">
        <div class="col-12">
            <label>Данные пациента:</label>
        </div>

        <div class="col-12">
            <label class="mb-0 mt-2">Пол*</label>
        </div>
        <div class="col-5">
            <?php
            $acc = ['ml_patient'];
if( is_user_logged_in() && $ht->access($acc) ){
    $user = wp_get_current_user();
    $df_sex = get_user_meta($user->ID,'gender',1);
    if(!$df_sex)$df_sex=0;
    $df_ = get_user_meta($user->ID);
    echo '<!--';
    print_r($df_sex);
    echo '-->';
    echo '<!--';
//    print_r($df_);
    echo '-->';
    echo '<!--';
//    print_r($user);
    echo '-->';
    $at = [];
    $at['type']='hidden';
    $at['name']='gender';
    $at['value']=$df_sex;
    echo $ht->f('input','',$at);
    $sel=[];
    $sel[0] = 'пол неизвестен';
    $sel[1] = 'мужской';
    $sel[2] = 'женский';
    echo $sel[$df_sex];
}else{
            ?>
            <select name="gender" class="form-control" id="dso-ch-gender">
            <?php
            echo $f_gender;
            ?></select>
<?php }  ?>
        </div>
        <div class="col-12 <?= !$show_pweek?'is_hidden':'' ?> f_depended" data-fd_parent="dso-ch-gender"
             data-fp_type="sel" data-f_show="2" data-f_hide="1">
            <?php if(0){ ?>
            <div class="row">
                <!-- div class="col-12">
                    <label class="mb-0 mt-2">Беременность</label>
                </div>
                <div class="col-5">
                    <select name="pregnancy" class="form-control" id="dso-ch-pregnancy">
                    <?php
                    echo $f_pregnancy;
                    ?></select>
                </div>
                <div class="col-12 is_hidden_ f_depended" data-fd_parent="dso-ch-pregnancy" 
                     data-fp_type="sel" data-f_show="1" data-f_hide="0">
                </div -->
            </div>
            <?php /**/ }  ?>
                <div class="row">
                    <div class="col-12">
                        <label class="mb-0 mt-2">Срок беременности (недель)</label>
                    </div>
                    <div class="col-5">
                        <select name="pregnancy_week" class="form-control" >
                        <?php
                        echo $f_pregnancy_week;
                        ?></select>
                    </div>
                </div>
        </div>
        <script>
            function dependHideSel(){
                var cr = this.ch;
                var fps= this.s;
                var fph= this.h;
                var fpv = this.p.value;
                if(fpv == fph){
                    cr.classList.add(this.cl);
                }
                if(fpv == fps){
                    cr.classList.remove(this.cl);
                }
            }
            var depends = document.querySelectorAll('.f_depended');
            if(depends && depends.length > 0){
            console.log(depends.length);
                for(var di = 0; di < depends.length; di++){
            console.log(di);
                    var cr = depends[di];
                    var fp=cr.dataset['fd_parent']; // field dependency
                    var fpt=cr.dataset['fp_type'];
                    var fps=cr.dataset['f_show'];
                    var fph=cr.dataset['f_hide'];
                    var fpi = document.getElementById(fp);
                    if(fpi){
                        switch(fpt){
                            case 'sel':
                                var fiv = {}; // field init vlue
                                fiv.p = fpi;
                                fiv.ch = cr;
                                fiv.cl = 'is_hidden';
                                fiv.h = fph;
                                fiv.s = fps;
                                fpi.onchange = dependHideSel.bind(fiv);
                                var fpv = fpi.value;
                                if(fpv == fph){
                                    cr.classList.add('is_hidden');
                                }
                                if(fpv == fps){
                                    cr.classList.remove('is_hidden');
                                }
                                break;
                        }
                    }
                }
            }
        </script>
        <?php
//        $patient_id
//if($user->exists()){
if($patient_id){
    $logged=1;
//    $user = get_current_user();
//    $first_name = esc_attr(get_the_author_meta('first_name', $user->ID));
    $adres = esc_attr(get_the_author_meta('residence_place', $patient_id));
    $passnum = esc_attr(get_the_author_meta('passnum', $patient_id));
    $id_citizen = esc_attr(get_the_author_meta('id_citizen', $patient_id));
    $temperature = esc_attr(get_the_author_meta('temperature', $patient_id));
    $pcomment = esc_attr(get_the_author_meta('pat_comment', $patient_id));
    
//    $email_readonly = 'readonly=""';
    $profile_readonly = '';
//    $profile_readonly = 'readonly=""';
}else{
        $adres = '';
        $passnum = '';
        $id_citizen = '';
        $tempticha = '';
        $pcomment = '';
        $profile_readonly = '';
}
    $profile_required = 'required=""';
    $profile_required = '';
        ?>
        <!-- ===== ===== ===== ===== ===== -->
        <div class="col-12">
            <label class="mb-0 mt-2">Адрес прописки</label>
        </div>
        <div class="col-5">
            <!--<input type="text" name="residence_place" maxlength="10" class="form-control" value="<?=$adres?>" <?=$profile_required?> <?=$profile_readonly?>>-->
            
            <textarea name="residence_place" class="form-control" maxlength="200" <?=$profile_required?> <?=$profile_readonly?>><?=$adres?></textarea>
        </div>
        <!-- ===== ===== ===== ===== ===== -->
        <div class="col-12">
            <label class="mb-0 mt-2">Номер паспорта</label>
        </div>
        <div class="col-5">
            <input type="text" name="passnum" class="form-control" maxlength="20" value="<?=$passnum?>" <?=$profile_required?> <?=$profile_readonly?>>
        </div>
        <!-- ===== ===== ===== ===== ===== -->
        <div class="col-12">
            <label class="mb-0 mt-2">Идентификатор гражданина</label>
        </div>
        <div class="col-5">
            <input type="number" name="uidsitiz" class="form-control" maxlength="20" value="<?=$id_citizen?>" <?=$profile_required?> <?=$profile_readonly?>>
        </div>
        <!-- ===== ===== ===== ===== ===== -->
        <div class="col-12">
            <label class="mb-0 mt-2">Температрура</label>
        </div>
        <div class="col-5">
            <input type="number" name="temperature" step=0.01 max="99" class="form-control" maxlength="4" value="<?=$temperature?>" <?=$profile_required?> <?=$profile_readonly?>>
        </div>
        <!-- ===== ===== ===== ===== ===== -->
        <div class="col-12">
            <label class="mb-0 mt-2">Примечание</label>
        </div>
        <div class="col-5">
            
            <textarea name="pat_comment" class="form-control" maxlength="200" <?=$profile_required?> <?=$profile_readonly?>><?=$pcomment?></textarea>
        </div>
        <!-- ===== ===== ===== ===== ===== -->
    </div>
        <?php
        