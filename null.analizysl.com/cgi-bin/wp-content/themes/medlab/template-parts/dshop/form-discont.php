<?php

/* 
 * form-discont
 * [dshop page="form-discont"]
 */

//ini_set("display_errors", "1");
//ini_set("display_startup_errors", "1");
//ini_set('error_reporting', E_ALL);
//return '';
$r_access = [];
$r_access [] ='administrator';
//$r_access [] ='ml_administrator';
//$r_access [] ='ml_manager';
//$r_access [] ='ml_doctor';
$r_access [] ='ml_procedurecab';
$user = wp_get_current_user();
if(count( array_intersect($r_access, (array) $user->roles ) ) == 0 ){
    get_template_part( 'template-parts/page/tpl.page-access', 'denied' );
//    get_template_part( 'template-parts/page/tpl.page-access', 'notfound' );
    return null;
}

$ret_url = '';


global $ht;



global $list_cou_def;
$list_cou_def = 50;

$hiddens = [];
$urlget=[]; // фильтрация


    $discts = [];
    $discts[0]=['descont'=>0,'mlCode'=>'0','name'=>'0%'];
    $discts[1]=['descont'=>100,'mlCode'=>'100','name'=>'100% скидка'];
    $discts[2]=['descont'=>5,'mlCode'=>'A-5','name'=>'5% Акция'];
    $discts[3]=['descont'=>7,'mlCode'=>'A-7','name'=>'7% Акция'];
    $discts[4]=['descont'=>10,'mlCode'=>'A-10','name'=>'10% Акция'];
    $discts[5]=['descont'=>20,'mlCode'=>'A-20','name'=>'20% Акция'];
    $discts[6]=['descont'=>25,'mlCode'=>'A-25','name'=>'25% Акция'];
    $discts[7]=['descont'=>30,'mlCode'=>'A-30','name'=>'30% Акция'];
    $discts[8]=['descont'=>40,'mlCode'=>'A-40','name'=>'40% Акция'];
    $discts[9]=['descont'=>50,'mlCode'=>'A-50','name'=>'50% Акция'];
    $discts[10]=['descont'=>60,'mlCode'=>'A-60','name'=>'60% Акция'];
    $discts[11]=['descont'=>3,'mlCode'=>'ДК-3','name'=>'Дисконтная карта-3%'];
    $discts[12]=['descont'=>5,'mlCode'=>'ДК-5','name'=>'Дисконтная карта-5%'];
    $discts[13]=['descont'=>7,'mlCode'=>'ДК-7','name'=>'Дисконтная карта-7%'];
    $discts[14]=['descont'=>15,'mlCode'=>'ДК-15','name'=>'Дисконтная карта-15%'];
    $discts[15]=['descont'=>30,'mlCode'=>'ДК-30','name'=>'Дисконтная карта-30%'];
    $discts[16]=['descont'=>50,'mlCode'=>'ДК-50','name'=>'Дисконтная карта-50%'];

    $offset = filter_input(INPUT_POST, 'offset', FILTER_SANITIZE_NUMBER_INT);
    if($offset===false || $offset===null || $offset==='')$offset=0;
    $isajax = filter_input(INPUT_POST, 'isajax', FILTER_SANITIZE_NUMBER_INT);
    if($isajax===false || $isajax===null || $isajax==='')$isajax=0;
    $count = filter_input(INPUT_POST, 'count', FILTER_SANITIZE_NUMBER_INT);
    if($count===false || $count===null || $count==='')$count=$list_cou_def;
    if ( wp_doing_ajax())$isajax=1;
    
    $hiddens['offset'] = $offset;
    $hiddens['count'] = $count;
    $hiddens['isajax'] = $isajax;
    
    
$duid = null; // uid doctor

$duId = filter_input(INPUT_GET, 'duid', FILTER_SANITIZE_NUMBER_INT);
if($duId===false || $duId===null|| $duId==='')
//    $duId=get_current_user_id();//'0';
    $duId=false;//'0';
$_duId = filter_input(INPUT_POST, 'duid', FILTER_SANITIZE_NUMBER_INT);
if(strlen($_duId)>0)$duId=$_duId;

/*
 * /////////////////////
 * инициализация формы даты
 */
    
//$date_from_ = filter_input(INPUT_GET, 'date-from', FILTER_VALIDATE_REGEXP,['options' =>['regexp' => '/^[\d]{2}\.[\d]{2}\.[\d]{4}$/']]);
//if(!$date_from_)$date_from_='';
//$date_to_ = filter_input(INPUT_GET, 'date-to', FILTER_VALIDATE_REGEXP,['options' =>['regexp' => '/^[\d]{2}\.[\d]{2}\.[\d]{4}$/']]);
//if(!$date_to_)$date_to_='';
//
//$date_from = filter_input(INPUT_POST, 'date-from', FILTER_VALIDATE_REGEXP,
//        ['options' =>['default'=>$date_from_, 'regexp' => '/^[\d]{2}\.[\d]{2}\.[\d]{4}$/']]);
//if(!$date_from)$date_from=$date_from_;
//$date_to = filter_input(INPUT_POST, 'date-to', FILTER_VALIDATE_REGEXP,
//        ['options' =>['default'=>$date_to_, 'regexp' => '/^[\d]{2}\.[\d]{2}\.[\d]{4}$/']]);
//if(!$date_to)$date_to=$date_to_;

$date_from = $ht->postget( 'date-from', '', FILTER_VALIDATE_REGEXP, '/^[\d]{2}\.[\d]{2}\.[\d]{4}$/');
$date_to   = $ht->postget( 'date-to', '', FILTER_VALIDATE_REGEXP, '/^[\d]{2}\.[\d]{2}\.[\d]{4}$/');
$hiddens['date-from'] = $date_from;
$hiddens['date-to'] = $date_to;

$p = null;
$patientid=false;
if($patientid>0){
    $p = '?pid='.$patientid;
}
//    $orderby = filter_input(INPUT_GET, 'order', FILTER_SANITIZE_STRING);

$discont = DShop::_get_cart_discont('laborant');
$orderby = $ht->postget( 'order', false);
//$discont = $ht->postget( 'discont', $discont, FILTER_SANITIZE_NUMBER_INT);

$discont_ = 0;
foreach ($discts as $k => $v) {
    if($v['descont']==$discont)
    {
        $discont_ = $k;
    }
}
$discontID = DSDiscont::_get_discont_id('laborant');
$discont_ = $discontID;
$discont_ = $ht->postget( 'discont', $discont_, FILTER_SANITIZE_NUMBER_INT);

$return_to = $ht->postget( 'return_to', false);
$save = $ht->post( 'save', false);

$hiddens['order'] = $orderby;
$hiddens['discont'] = $discont;

if($save == 'ok'){
    $discontId = $discont_;
    $discont = $discts[$discont_]['descont'];
    $discontN = $discts[$discont_]['name'];
    $discontC = $discts[$discont_]['mlCode'];
//    add_log($discontId);
    
    DShop::_add_to_cart_discont('laborant',$discont,$user->ID,$discontId);
    add_log('Добавлена скидка: '.$discont.' % -- '.$discontN.' ['.$discontC.']');
    header("Refresh:3; url=$return_to", true, 200);
}
//                    add_log($_SESSION);
add_log($discont);
    

function bFormField($label='',$name='',$val='',$placeholder='',$type='text',$class=''){
    global $ht;
    $at=[];
    $at['id'] = 'fi_'.$name;
    $at['name'] = $name;
    $at['class'] = 'form-control '.$class;
    $at['placeholder'] = $placeholder;
    $at['type'] = $type;
    $at['value'] = $val;
    $c = $ht->f('input','',$at);
    $at=[];
    $at['class'] = 'col-12 ';
    $c = $ht->f('div',$c,$at);
    
    $at=[];
    $at['class'] = 'mb-0 mt-2';
    $at['for'] = 'fi_'.$name;
    $l = $ht->f('label',$label,$at);
    $at=[];
    $at['class'] = 'col-12 ';
    $l = $ht->f('div',$l,$at);
    
    $at=[];
    $at['class'] = 'row ';
    $c = $ht->f('div',$l.$c,$at);
    return $c;
}
    

function bFormFieldSelect($label='',$name='',$val='',$vars='',$class=''){
    global $ht;
//    $at=[];
//    $at['id'] = 'fi_'.$name;
//    $at['name'] = $name;
//    $at['class'] = 'form-control '.$class;
//    $at['placeholder'] = $placeholder;
//    $at['type'] = $type;
//    $at['value'] = $val;
    //$c = $ht->f('select',$name,$at);
    
    $c = $ht->select($name,$vars,$val,$class,'fi_'.$name);
    $at=[];
    $at['class'] = 'col-12 ';
    $c = $ht->f('div',$c,$at);
    
    $at=[];
    $at['class'] = 'mb-0 mt-2';
    $at['for'] = 'fi_'.$name;
    $l = $ht->f('label',$label,$at);
    $at=[];
    $at['class'] = 'col-12 ';
    $l = $ht->f('div',$l,$at);
    
    $at=[];
    $at['class'] = 'row ';
    $c = $ht->f('div',$l.$c,$at);
    return $c;
}

    $f_i_disconts = bFormField('Скидка (%)','discont',$discont,'');
    
    $vals = [];
    $vals['']='';
    $discCode = [];
    $discCode['']='';
    $vars = [];
    foreach ($discts as $k => $v) {
        $vars[$k]=$v['name'];
    }
    $f_i_disconts = bFormFieldSelect('Скидка (%)','discont',$discont_,$vars);
//add_log($p,'dump');

/*
 * 
<!--            <div class="col-3 text-left">
                <?=$f_i_fname?>
            </div>
            <div class="col-3 text-left">
                <?=$f_i_sname?>
            </div>
            <div class="col-3 text-left">
                <?=$f_i_oid?>
            </div>-->
 * 
 */
?>
<div class="row">
    <form action="<?=get_the_permalink( 1968 ).$p?>" method="post" class="col-12 text-left">
        <?php
        
    echo $ht->f('input','',['type'=>'hidden','name'=>'return_to','value'=>$return_to])."\n";
    echo $ht->f('input','',['type'=>'hidden','name'=>'save','value'=>'ok'])."\n";
//    echo $ht->f('input','',['type'=>'hidden','name'=>'offset','value'=>$offset])."\n";
        ?>
        <div class="row">
<!--            <div class="col-12">
                <?php
//                        var_dump( $lab_g);
                ?>
            </div>-->
            <div class="col-3 text-left">
                <?=$f_i_disconts?>
            </div>
            <div class="col-12 text-left">
                <button type="sumbit" class="btn btn-primary mt-3 mb-3">Применить</button>
            </div>
        </div>
    </form>
</div>
<?php

