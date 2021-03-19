<?php


/* 
 * list-patients.php
 * [medlab page="list" type="patients" ald="ml_component"]
 * [ml_component tpl="list" type="patients"]
 */

global $ht;
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

//$return_to = get_the_permalink( 41 );
//$return_to = get_the_permalink( 109 ); // order
$return_to = get_the_permalink( 8 ); // items
                
$puid = $ht->postget( 'puid', 0, FILTER_SANITIZE_NUMBER_INT);
$return_to = $ht->postget( 'return_to', $return_to);
$save = $ht->post( 'save', false);

//$hiddens['order'] = $orderby;
//$hiddens['discont'] = $discont;

if($save == 'ok'){
    $_SESSION['dso_puid_remember'] = $puid;
//    DShop::_add_to_cart_discont('laborant',$discont,$user->ID);
    $fio = get_user_meta($puid,'last_name',1);
    $fio .= ' '.get_user_meta($puid,'first_name',1);
    $fio .= ' '.get_user_meta($puid,'second_name',1);
    add_log('Забронирован пациент: '.$fio.' ');
//    header("Refresh:3; url=$return_to", true, 200);
    header("location: $return_to", true, 200);
    exit();
}
$return_to = get_the_permalink( 41 );
$return_to = get_the_permalink( 109 ); // order
$return_to = get_the_permalink( 8 ); // items

$duId = filter_input(INPUT_GET, 'duid', FILTER_SANITIZE_NUMBER_INT);
if($duId===false || $duId===null|| $duId==='')
//    $duId=get_current_user_id();//'0';
    $duId=false;//'0';
$_duId = filter_input(INPUT_POST, 'duid', FILTER_SANITIZE_NUMBER_INT);
if(strlen($_duId)>0)$duId=$_duId;


//add_log($duId);
//if(!$duId || !current_user_can('manage_options')){
$r_access = [];
$r_access [] ='ml_doctor';
if(count( array_intersect($r_access, (array) $user->roles ) ) > 0 ){
    $duId = get_current_user_id();
}
//add_log($duId);
//if(!$duId)return;

//echo 'patients';

global $list_cou_def;
$list_cou_def = 1000;
$list_cou_def = 100;
//$list_cou_def = 1;

    $offset = filter_input(INPUT_POST, 'offset', FILTER_SANITIZE_NUMBER_INT);
    if($offset===false || $offset===null || $offset==='')$offset=0;
    $isajax = filter_input(INPUT_POST, 'isajax', FILTER_SANITIZE_NUMBER_INT);
    if($isajax===false || $isajax===null || $isajax==='')$isajax=0;
    $count = filter_input(INPUT_POST, 'count', FILTER_SANITIZE_NUMBER_INT);
    if($count===false || $count===null || $count==='')$count=$list_cou_def;
    if ( wp_doing_ajax())$isajax=1;
    
$display_v = 1;
$display_v = 2;
$display_v = 3;
$display_d = 5;

    $display_v = filter_input(INPUT_GET, 'display_v', FILTER_SANITIZE_NUMBER_INT);
    if($display_v===false || $display_v===null || $display_v==='')$display_v=$display_d;
    if($display_v>3)$display_v=$display_d;
    
    $user_id = filter_input(INPUT_GET, 'u', FILTER_SANITIZE_NUMBER_INT);
    


include 'tpls/tpl-ml--lp--defoult.php';

$tpl_name='';
$tpl_name = apply_filters('ds_styling_tpl_name', $tpl_name);
//include 'tpls/tpl-ml--lp--ml-v-2.php'; //  list-price
$tpl_file_name = 'tpls/tpl-ml--lp--'.$tpl_name.'.php'; 
$dir = basename(__FILE__);
if(file_exists(__DIR__.'/'.$tpl_file_name))
    include $tpl_file_name; //  list-price
    

// параметры по умолчанию
//$posts = get_posts( array(
//	'numberposts' => $count,
//	'offset'    => $offset,
$args = array(
//	'blog_id'      => $GLOBALS['blog_id'],
//	'role'         => '',
//	'role__in'     => array('contributor','author','editor','administrator','ml_patient','ml_doctor'),
	'role__in'     => array('ml_patient'),
//	'role__not_in' => array('subscriber'),
//	'meta_key'     => 'joined_doctor',
//	'meta_value'   => $duId,
//	'meta_compare' => '',
	'meta_query'   => [
        
    ],
//	'include'      => array(),
//	'exclude'      => array(),
//	'orderby'      => 'login',
//	'order'        => 'ASC',
	'orderby'      => 'ID',
	'order'        => 'DESC',
	'offset'       => $offset,
//	'offset'       => '',
//	'search'       => '',
//	'search_columns' => array(),
	'number'       => $count,
//	'paged'        => 1,
//	'count_total'  => false,
//	'fields'       => 'all',
//	'who'          => '',
//	'has_published_posts' => null,
//	'date_query'   => array() // смотрите WP_Date_Query
);

//$r_access = [];
//$r_access [] ='administrator';
//$r_access [] ='ml_administrator';
//$r_access [] ='ml_manager';
////$r_access [] ='ml_doctor';
//$r_access [] ='ml_procedurecab';
//$user = wp_get_current_user();
//if(count( array_intersect($r_access, (array) $user->roles ) ) == 0 ){
    
if($duId){
    $args['meta_query'][] = 
        [
            'key' => 'joined_doctor',
            'value' => $duId,
            'compare' => '='
    //        'compare' => 'LIKE'
        ];
}
if($user_id)$args['include']=[$user_id];

$dpost = [];

$f_pcode = filter_input(INPUT_POST, 'pcode', FILTER_SANITIZE_NUMBER_INT);
$f_pfn = filter_input(INPUT_POST, 'pfn', FILTER_SANITIZE_STRING);
$f_pln = filter_input(INPUT_POST, 'pln', FILTER_SANITIZE_STRING);
$f_oid = filter_input(INPUT_POST, 'oid', FILTER_SANITIZE_NUMBER_INT);
$f_nr = filter_input(INPUT_POST, 'nr', FILTER_SANITIZE_NUMBER_INT);

$dpost['f_pcode']=$f_pcode;
$dpost['f_pfn']=$f_pfn;
$dpost['f_pln']=$f_pln;
$dpost['f_oid']=$f_oid;
$dpost['f_nr']=$f_nr;
$dpost['duid']=$duId;

if($f_pcode){
    $args['meta_query'][] = 
        [
            'key' => 'card_numer',
            'value' => $f_pcode,
            'compare' => '='
    //        'compare' => 'LIKE'
        ];
}
if($f_pfn){
    $args['meta_query'][] = 
        [
            'key' => 'first_name',
            'value' => $f_pfn,
//            'compare' => '='
            'compare' => 'LIKE'
        ];
}
if($f_pln){
    $args['meta_query'][] = 
        [
            'key' => 'last_name',
            'value' => $f_pln,
//            'compare' => '='
            'compare' => 'LIKE'
        ];
}
if($f_oid){
    $ouid = get_post_meta($f_oid, 'dso_puid', true);
    $args['include']=[$ouid];
}
if($f_nr){
    
    $oargs =[
//        'author'  => $user->ID,
    	'numberposts' => 1000,
    	'offset'    => 0,
    //	'numberposts' => $count,
    //	'offset'    => $offset,
    //	'category'    => 0,
        'orderby'     => 'date',
        'order'       => 'DESC',
    //	'include'     => array(),
    //	'exclude'     => array(),
        'meta_key'    => '',
        'meta_value'  =>'',
        'meta_query'   => [
            [
                'key' => 'dso_query_nr',
                'value' => $f_nr,
                'compare' => '='
        //        'compare' => 'LIKE'
            ]
        ],
        'post_type'   => 'dsorder',
        'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
    ] ;
    $fops = get_posts($oargs);
    $_oid = 0;
    if($fops){
        foreach($fops as $fop){
            $_oid = $fop->ID;
        }
    }
//    add_log($oargs);
//    add_log($fops);
//    if($_oid){
        $ouid = get_post_meta($_oid, 'dso_puid', true);
        $args['include']=[$ouid];
//    }
}

//add_log($args);
$users = get_users( $args );
$args['number']=10000000;
$args['offset']=0;
$users_limit = count (get_users( $args ));
//$users = DShopExtensionMedLab::_get_patients($duId);//Allegro
//$users = get_users( );

$r_access = [];
$r_access [] ='administrator';
$r_access [] ='ml_administrator';
$r_access [] ='ml_manager';
//$r_access [] ='ml_doctor';
$r_access [] ='ml_procedurecab';
//$user = wp_get_current_user();
    if ( !wp_doing_ajax() && count( array_intersect($r_access, (array) $user->roles ) ) > 0 ){

$r=[];
$r['_url_add_patient_'] = get_the_permalink( 323 );
$options_btns = strtr($tpl_btn_add_opts,$r);
echo $options_btns;

    $r_faind=[];
    $f_pcode = filter_input(INPUT_POST, 'pcode', FILTER_SANITIZE_NUMBER_INT);
    $f_pfn = filter_input(INPUT_POST, 'pfn', FILTER_SANITIZE_STRING);
    $f_pln = filter_input(INPUT_POST, 'pln', FILTER_SANITIZE_STRING);
    $f_oid = filter_input(INPUT_POST, 'oid', FILTER_SANITIZE_NUMBER_INT);
    $f_nr = filter_input(INPUT_POST, 'nr', FILTER_SANITIZE_NUMBER_INT);
//    add_log($_POST);
    $r_i=[];
    $r_i['__label__'] = 'Код карты';
    $r_i['__name__'] = 'pcode';
    $r_i['__id__'] = 'fi_'.$r_i['__name__'];
    $r_i['__for__'] = $r_i['__id__'];
    $r_i['__val__'] = $f_pcode;
    $r_i['__i_class__'] = '';
    $r_i['__placeholder__'] = '';
    $f_i_ucode = strtr($tpl__i_,$r_i);
    $r_i=[];
    $r_i['__label__'] = 'Имя';
    $r_i['__name__'] = 'pfn';
    $r_i['__id__'] = 'fi_'.$r_i['__name__'];
    $r_i['__for__'] = $r_i['__id__'];
    $r_i['__val__'] = $f_pfn;
    $r_i['__i_class__'] = '';
    $r_i['__placeholder__'] = '';
    $f_i_fname = strtr($tpl__i_,$r_i);
    $r_i=[];
    $r_i['__label__'] = 'Фамилия';
    $r_i['__name__'] = 'pln';
    $r_i['__id__'] = 'fi_'.$r_i['__name__'];
    $r_i['__for__'] = $r_i['__id__'];
    $r_i['__val__'] = $f_pln;
    $r_i['__i_class__'] = '';
    $r_i['__placeholder__'] = '';
    $f_i_sname = strtr($tpl__i_,$r_i);
    $r_i=[];
    $r_i['__label__'] = 'Id заказа';
    $r_i['__name__'] = 'oid';
    $r_i['__id__'] = 'fi_'.$r_i['__name__'];
    $r_i['__for__'] = $r_i['__id__'];
    $r_i['__val__'] = $f_oid;
    $r_i['__i_class__'] = '';
    $r_i['__placeholder__'] = '';
    $f_i_oid = strtr($tpl__i_,$r_i);
    $r_i=[];
    $r_i['__label__'] = 'Номер заявки';
    $r_i['__name__'] = 'nr';
    $r_i['__id__'] = 'fi_'.$r_i['__name__'];
    $r_i['__for__'] = $r_i['__id__'];
    $r_i['__val__'] = $f_nr;
    $r_i['__i_class__'] = '';
    $r_i['__placeholder__'] = '';
    $f_i_nr = strtr($tpl__i_,$r_i);
//    duid
//            $doctor_id = filter_input(INPUT_POST, 'doctor_id',FILTER_SANITIZE_NUMBER_INT);
            $var_pu=[];
            $var_pu['items'] = DShopExtensionMedLab::_get_doctors();
            $var_pu['items'][0] = 'Не указан';
            $var_pu['option_name'] = 'duid';//doctor_id
            $var_pu['class'] = 'form-control    ';
            $var_pu['id'] = 'field_dso_puid';
    //        $var_pu['post_id'] = ''; // object
    //        $f_patient = DShopExtensionMedLab::_dshf_select($var_pu);
            $var_pu['res'] = $duId;
    //        $dsP = new DShopPayment();
    //        $f_patient = $dsP->_cf_select($var_pu);
    //        $dsP = new DShopPayment();
            $f_sel_docter= DShopExtensionMedLab::_dshf_select_free($var_pu);
$r=[];
//$r['_select_'] = get_the_permalink( 323 );
$r['_select_'] = $f_sel_docter;
$r['_input_ufilter_code_'] = $f_i_ucode;
$r['_input_ufilter_fname_'] = $f_i_fname;
$r['_input_ufilter_sname_'] = $f_i_sname;
$r['_input_ufilter_oid_'] = $f_i_oid;
$r['_input_ufilter_nr_'] = $f_i_nr;
$options_btns = strtr($tpl_form_patient_by_doctor_filter,$r);
echo $options_btns;
}


if(count($users)>0){

//    echo '<div class="row">';
//    echo '<div class="col-md-1 label"> №</div>';
//    echo '<div class="col-md-1 label"> Дата</div>';
//    echo '<div class="col-md-2 label"> Название компании</div>';
//    echo '<div class="col-md-2 label"> Сайт</div>';
//    echo '<div class="col-md-2 label"> ФИО представителя / Должность</div>';
//    echo '<div class="col-md-2 label"> Телефон</div>';
//    echo '<div class="col-md-2 label"> e-mail</div>';
//    echo '</div>';
    
        ?>
            <table class="table table-hover table-striped -table-dark">
  <caption>Список пациентов </caption>
  <thead>
      <?php
    if($display_v == 1){
      ?>
    <tr>
      <th scope="col">№</th>
      <th scope="col">Дата</th>
      <th scope="col">Название компании</th>
      <th scope="col">Сайт</th>
      <th scope="col">ФИО представителя / Должность</th>
      <th scope="col">Телефон </th>
      <th scope="col">E-mail</th>
    </tr>
      <?php
    }else if($display_v == 2){
    ?>
    <tr>
        <td>
            <div class="row">
                <div class="col col-12 col-sm-12 col-md-2 label">
                    <div class="row">
                        <div class="col col-12 col-sm-12 col-md-5">
                            <strong>№</strong>
                        </div>
                        <div class="col col-12 col-sm-12 col-md-7">
                            <strong>Дата</strong>
                        </div>
                    </div>
                </div>
                <div class="col col-12 col-sm-12 col-md-2 label-info">
                    <strong>Название компании</strong>
                </div>
                <div class="col col-12 col-sm-12 col-md-2">
                    <strong>Сайт</strong>
                </div>
                <div class="col col-12 col-sm-12 col-md-2">
                    <strong>ФИО представителя / Должность</strong>
                </div>
                <div class="col col-12 col-sm-12 col-md-2">
                    <strong>Телефон</strong>
                </div>
                <div class="col col-12 col-sm-12 col-md-2">
                    <strong>E-mail</strong>
                </div>
            </div>
        </td>
    </tr>
        <?php
    } if($display_v == 3){
    ?>
    <tr>
        <td>
            <div class="row">
                <div class="col col-12 col-sm-12 col-md-2 label">
                    <div class="row">
                        <div class="col col-6 col-sm-6 col-md-5">
                            <strong>№</strong>
                        </div>
                        <div class="col col-6 col-sm-6 col-md-7">
                            <strong>Дата</strong>
                        </div>
                    </div>
                </div>
                <div class="col col-12 col-sm-12 col-md-2 ">
                    <div class="row">
                        <div class="col col-12 ">
                            <strong>Название компании</strong>
                        </div>
                    </div>
                </div>
                <div class="col col-12 col-sm-12 col-md-2">
                    <strong>Сайт</strong>
                </div>
                <div class="col col-12 col-sm-12 col-md-2">
                    <strong>ФИО представителя / Должность</strong>
                </div>
                <div class="col col-12 col-sm-12 col-md-2">
                    <strong>Телефон</strong>
                </div>
                <div class="col col-12 col-sm-12 col-md-2">
                    <strong>E-mail</strong>
                </div>
            </div>
        </td>
    </tr>
        <?php
    } if($display_v == 4){
    ?>
    <tr>
        <td>
            <div class="row">
                <div class="col col-12 col-sm-12 col-md-3 label">
                    <div class="row">
                        <div class="col col-6 col-sm-6 col-md-5">
                            <strong>№</strong>
                        </div>
                        <div class="col col-6 col-sm-6 col-md-7">
                            <strong>Дата</strong>
                        </div>
                    </div>
                </div>
                <div class="col col-12 col-sm-12 col-md-3">
                    <strong>ФИО </strong>
                </div>
                <div class="col col-12 col-sm-12 col-md-3">
                    <strong>Телефон</strong>
                </div>
                <div class="col col-12 col-sm-12 col-md-3">
                    <strong>E-mail</strong>
                </div>
            </div>
        </td>
    </tr>
        <?php
    } if($display_v == 5){
    ?>
    <tr>
        <td>
            <div class="row">
                <div class="col col-12 col-sm-12 col-md-3 label">
                    <div class="row">
                        <div class="col col-6 col-sm-6 col-md-5">
                            <strong>№</strong>
                        </div>
                        <div class="col col-6 col-sm-6 col-md-7">
                            <strong>Дата</strong>
                        </div>
                    </div>
                </div>
                <div class="col col-12 col-sm-12 col-md-4">
                    <strong>ФИО </strong>
                </div>
                <div class="col col-12 col-sm-12 col-md-5">
                    <strong>Телефон / E-mail</strong>
                </div>
            </div>
        </td>
    </tr>
        <?php
    }
      ?>
  </thead>
  <tbody>
      <?php
    
    $num=0;
    $num=0;
    $num=$offset;
    $posts_count = count_users();
//    echo '<!-- '.print_r( count_users(),1 ).' -->';
    $num = $posts_count['total_users'];
    $num = $posts_count['avail_roles']['administrator'];
    if(isset($posts_count['avail_roles']['contributor']))
        $num += $posts_count['avail_roles']['contributor'];
    if(isset($posts_count['avail_roles']['ml_patient']))
        $num += $posts_count['avail_roles']['ml_patient'];
    if(isset($posts_count['avail_roles']['ml_doctor']))
        $num += $posts_count['avail_roles']['ml_doctor'];
    
    $posts_count = count_users();
    $num = $posts_count['avail_roles']['administrator'];
    if(isset($posts_count['avail_roles']['contributor']))
        $num += $posts_count['avail_roles']['contributor'];
    
//    $posts_count = $posts_count['total_users'];
    $num=count($users);
    $num = $posts_count['avail_roles']['ml_patient'];
    $num = $users_limit;
    $num-=$offset;
foreach( $users as $user ){
//	// обрабатываем
//}
//    foreach( $posts as $post ){
//        setup_postdata($post);
//        // формат вывода the_title() ...

        
    $date = date( "d.m.y", strtotime( $user->get('user_registered') ) );

    $company = get_user_meta($user->ID,'org_name',1);
    
    $site = strtr($user->get('user_url'),['http://'=>'']);
    
    $fio = get_user_meta($user->ID,'last_name',1);
    $fio .= ' '.get_user_meta($user->ID,'first_name',1);
    $fio .= ' '.get_user_meta($user->ID,'second_name',1);
//    $position = get_user_meta($user->ID,'org_position',1);
//    if($position)$fio.=' / '.$position;
//    $position = get_user_meta($user->ID,'position',1);
//    if($position)$fio.=' / '.$position;
    
    $phone = get_user_meta($user->ID,'phone',1);
    $card_numer = get_user_meta($user->ID,'card_numer',1);
    
    
    $email = $user->get('user_email');
    
    
    $height = 45;
    $width  = 70;
    //thumbnail, medium, large, full
    //$size = array($width, $height);
    $size = 'medium';
    $size = 'large';
    $attr = [];
    $logo='';
    $thumb='';
    $thumbnail_id = get_user_meta($user->ID, 'u_logo',1);
    if($thumbnail_id>0)
        $thumb = wp_get_attachment_image( $thumbnail_id,
                $size , false , $attr);
    if(strlen($thumb)>0)
        $logo = $thumb;
    
    $patient_answer_list = get_the_permalink( 309 ).'?pid='.$user->ID;
    $patient_profile = get_the_permalink( 26 ).'?puid='.$user->ID;
    $patient_query_list = get_the_permalink( 502 ).'?pid='.$user->ID;
    
    if($display_v == 1){
    ?>
    <tr>
      <th scope="row"><?=$num--?></th>
      <td nowrap><?=$date?></td>
      <td><?=$company?></td>
      <td><?=$site?></td>
      <td><?=$fio?></td>
      <td><?=$phone?></td>
      <td><?=$email?></td>
    </tr>
        <?php
    }else if($display_v == 2){
    ?>
    <tr>
        <td>
            <div class="row">
                <div class="col col-12 col-sm-12 col-md-2">
                    <div class="row">
                        <div class="col col-12 col-sm-12 col-md-4">
                            <strong><?=$num--?></strong>
                        </div>
                        <div class="col col-12 col-sm-12 col-md-7">
                            <strong><?=$date?></strong>
                        </div>
                    </div>
                </div>
                <div class="col col-12 col-sm-12 col-md-2">
                    <div class="row">
                        <div class="col col-12 ">
                            <?=$company?>
                        </div>
                        <div class="col col-12 ">
                            <a href="<?=$user->get('user_url')?>" target="_blank"><?=$logo?></a>
                        </div>
                    </div>
                </div>
                <div class="col col-12 col-sm-12 col-md-2">
                    <?=$site?>
                </div>
                <div class="col col-12 col-sm-12 col-md-2">
                    <?=$fio?>
                </div>
                <div class="col col-12 col-sm-12 col-md-2">
                    <?=$phone?>
                </div>
                <div class="col col-12 col-sm-12 col-md-2">
                    <?=$email?>
                </div>
            </div>
        </td>
    </tr>
        <?php
    }else if($display_v == 3){
    ?>
    <tr>
        <td>
            <div class="row">
                <div class="col col-12 col-sm-12 col-md-2">
                    <div class="row">
                        <div class="col col-6 col-sm-6 col-md-4">
                            <strong><?=$num--?></strong>
                        </div>
                        <div class="col col-6 col-sm-6 col-md-7">
                            <strong><?=$date?></strong>
                        </div>
                    </div>
                </div>
                <div class="col col-12 col-sm-12 col-md-2">
                    <?=$company?>
                </div>
                <div class="col col-12 col-sm-12 col-md-2">
                    <?=$site?>
                </div>
                <div class="col col-12 col-sm-12 col-md-2">
                    <?=$fio?>
                </div>
                <div class="col col-12 col-sm-12 col-md-2">
                    <?=$phone?>
                </div>
                <div class="col col-12 col-sm-12 col-md-2">
                    <?=$email?>
                </div>
            </div>
        </td>
    </tr>
        <?php
    }else if($display_v == 4){
    ?>
    <tr>
        <td>
            <div class="row">
                <div class="col col-12 col-sm-12 col-md-3">
                    <div class="row">
                        <div class="col col-6 col-sm-6 col-md-4">
                            <strong><?=$num--?></strong>
                        </div>
                        <div class="col col-6 col-sm-6 col-md-7">
                            <strong><?=$date?></strong>
                        </div>
                    </div>
                </div>
                <div class="col col-12 col-sm-12 col-md-3">
                    <?=$fio?>
                </div>
                <div class="col col-12 col-sm-12 col-md-3">
                    <?=$phone?>
                </div>
                <div class="col col-12 col-sm-12 col-md-3">
                    <?=$email?>
                </div>
            </div>
        </td>
    </tr>
        <?php
    }else if($display_v == 5){
    ?>
    <tr>
        <td>
            <div class="row">
                <div class="col col-12 col-sm-12 col-md-3">
                    <div class="row">
                        <div class="col col-6 col-sm-6 col-md-4">
                            <strong><?=$num--?></strong>
                        </div>
                        <div class="col col-6 col-sm-6 col-md-7">
                            <strong><?=$date?></strong>
                        </div>
                    </div>
                </div>
                <div class="col col-12 col-sm-12 col-md-4">
                    <?=$fio?><br/>
                    <?=$card_numer?>
                </div>
                <div class="col col-12 col-sm-12 col-md-5">
                    <?=$phone?><br/>
                    <?=$email?><br/>
                    <a class="btn btn-primary text-white" href="<?=$patient_answer_list?>">Ответы</a>
                    <a class="btn btn-primary text-white" href="<?=$patient_profile?>">Профиль</a>
                    <?php if(current_user_can( 'manage_options' )){ ?>
                    <a class="btn btn-primary text-white" href="<?=$patient_query_list?>">Заявки</a>
                    <?php }
                    
//                $discont = DShop::_get_cart_discont('laborant');
//                if($discont)add_log('Имеется скидка: +'.$discont.' %');
//                $return_to = get_the_permalink( 101 ).'?oid='.$oid;
                    
//                $return_to = get_the_permalink( 41 );
//                $return_to = get_the_permalink( 109 );
//                $return_to = get_the_permalink( 8 );
                $btn  = $ht->f('input','',['type'=>'hidden','name'=>'puid','value'=>$user->ID])."\n";
                $btn .= $ht->f('input','',['type'=>'hidden','name'=>'return_to','value'=>$return_to])."\n";
                $btn .= $ht->f('input','',['type'=>'hidden','name'=>'save','value'=>'ok'])."\n";
                $btn .= $ht->f('button','Выбрать',['class'=>'btn btn-primary','type'=>"sumbit"]);
//                $btn = $ht->f('div',$ht->f('div',$btn,['class'=>'col-12 text-right']),['class'=>'row']);
                
                $btn = $ht->f('form',$btn,['method'=>'post','action'=>get_the_permalink( 41 )]);
//                $btn .= htmlspecialchars($brn);
//                $btn = $ht->f('tr',$ht->f('td',$btn,['colspan'=>4]));
                echo $btn;
                    ?>
                </div>
            </div>
        </td>
    </tr>
        <?php
    }

//    echo '<tr><td colspan="8">';
//    echo
////    '<pre>'.
//            print_r($post,1)
////            .'</pre>'
//            ;
//    echo '</td></tr>';
    }
}
else{
    echo '<tr><td colspan="8">';
    echo 'Нет записей.';
    echo '</td></tr>';
}?>
  </tbody>
</table>
      <?php

// получим:
// Значение ключа nickname у пользователя 9 равно: Enot

//wp_reset_postdata(); // сброс

      
//	[total_users] => 2
//	[avail_roles] => Array(
//		[administrator] => 1
//		[subscriber] => 1
//	)
$search = MedLab::_clear_sch();//Allegro
    $posts_count = count_users();
    $num = $posts_count['avail_roles']['administrator'];
    if(isset($posts_count['avail_roles']['contributor']))
        $num += $posts_count['avail_roles']['contributor'];
    
//    $posts_count = $posts_count['total_users'];
    $posts_count = $posts_count['avail_roles']['ml_patient'];
global $posts_count;
    $posts_count = $users_limit;
//    echo $posts_count;
//    echo $ht->pre();
//    $posts_count = $num;
//    if(!$isajax){
    if ( !wp_doing_ajax() && $posts_count > $count && !$user_id) {
        $next = $offset + $count;
        echo '<div class="row " id="upl-user-btn-wrupp">';
        echo '<div class="col-md-12 text-center">';
        echo '<button type="button" id="get-list-users"'
            . 'class="btn btn-dark btn-lg btn-block active upload-data" '
            . 'data-type="patient" data-count="'.$count.'" data-offset="'.$next.'" '
            . 'data-sch="'.$search.'" '
            . 'data-post=\''.wp_json_encode($dpost).'\' '
            . 'data-target="upl-user-btn-wrupp" '
            . 'data-all="'.$posts_count.'" >Ещё</button>';
        echo '</div>';
        echo '</div>';
    }
//    echo $ht->pre(wp_json_encode($dpost));