<?php

/* 
 * tpl.dshop-cart-items-blank.php
 * [ds_page page="cart-items" type="blank" old=""]
 * [ds_page tpl="tpl.dshop" type="cart-items-blank" old=""]
 * [ml_page tpl="tpl.medlab" type="bill-blank" old=""]
 */

//      <th scope="col">Доставка</th>
//      <th scope="col">Наценка</th>
//add_log($_SESSION);
$address = 'г. Таганрог';
$labgroup = false;
$address =  MedLabLabGroupFields::get_lab_group_address($labgroup ); // sender uid
?>

<table width="100%">
    <tr>
        <td width="30%" valign="top">
                <?php the_custom_logo(); ?>
        </td>
        <td align="center">
            <center>
                <table align="center" width="100%" style="margin-bottom: 0px;">
                    <tr>
                        <td align="center">
                            <?= nl2br( $address, 1)?>
                        </td>
                    </tr>
                    <tr>
                        <td align="center"> <?=strtr(  home_url( '/' ) , ['http://'=>'','https://'=>'','/'=>''])?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            &nbsp;
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            <?php
//$barcode='Med Lab 001';
//echo MLBarcode::img($barcode);?>
                        </td>
                    </tr>
                </table>
            </center>
        </td>
    </tr>
</table>

<!--<form action="" method="POST">
    <input type="hidden" id="pr_ft" name="form-type" value="update_cart">
    <input type="hidden" name="guest" value="1">
    <input type="hidden" id="pr_act" name="act" value="cart_to_checkout">-->
<!--    <input type="hidden" id="pr_max" name="max[]" value="">
    <input type="hidden" id="pr_cou" name="cou[]" value="">-->
<table class="table table-hover table-striped -table-dark">
  <!--<caption></caption>-->
  <thead>
    <tr>
      <th scope="col">№</th>
      <th scope="col"></th>
      <th scope="col">Название</th>
      <th scope="col">Цена единицы</th>
      <th scope="col">Количество</th>
      <th scope="col">Код</th>
      <th scope="col">Стоимость</th>
      <!--<th scope="col"></th>-->
    </tr>
  </thead>
  
  <tbody>
      <?php
      $ccount = dshop::_count_in_cart();
if(count($ccount)>0){
    $nm = 'ln_';
        $state = [];
        $state['0'] = 'Проблема не решена';
        $state['1'] = 'Проблема решена';
    
    $num=0;
//    $num=$offset;
//        $ftpl=[];
//        
//        $ftpl[$nm.'adress'] = false;
//        $ftpl[$nm.'namebc'] = false;
//        $ftpl[$nm.'fio'] = false;
//        $ftpl[$nm.'position'] = false;
//        $ftpl[$nm.'phone'] = false;
//        $ftpl[$nm.'email'] = false;
//        $ftpl[$nm.'state'] = [];
//        $ftpl[$nm.'state']['0'] = 'Проблема не решена';
//        $ftpl[$nm.'state']['1'] = 'Проблема решена';
    $cart_ = $_SESSION['ds_cart'];
    $total = 0;
    $currency_short = get_option('currency_short','zl');
    $currency_short = get_option('currency_short','rub');
    
    $ds_item_add_count_def = get_option('ds_item_add_count_def',1);
    $ds_item_add_min = get_option('ds_item_add_min',1);
    $ds_item_add_max = get_option('ds_item_add_max',1);
    $ds_cart_item_max = get_option('ds_cart_item_max',1);
    $ds_cart_items_max = get_option('ds_cart_items_max',1000);
    $items_count_summ = 0;
    
//    add_log($_SESSION);

$medLab = MedLab::_instance();
        
$groups = $medLab->groups;
$analyses = $medLab->analyses;
$panels = $medLab->panels;
$tests = $medLab->tests;
$biomaterials = $medLab->biomaterials;
$drugs = $medLab->drugs;
$microorganisms = $medLab->microorganisms;
$containers = $medLab->containers;
$price = $medLab->price;
    
    $page_item_url = get_the_permalink( get_option('ds_id_page_item') ).'?pid=';
    foreach( $cart_ as $pid=>$cou ){
//        if(isset($_SESSION['ds_prod_safe'][$pid]))
//            $prod = $_SESSION['ds_prod_safe'][$pid];
//        else
//            continue;
        
        if($cou > $ds_cart_item_max) $cou = $ds_cart_item_max;
        if(( $cou + $items_count_summ ) > $ds_cart_items_max) $cou = $ds_cart_items_max - $items_count_summ;
        if($cou < 0) $cou = 0;
        $items_count_summ+=$cou;
        
        $itype = false;
        
        if(isset($analyses[$pid])){
            $itype = 'az';
            $prod = $analyses[$pid];
        }
        else
        if(isset($panels[$pid])){
            $itype = 'pl';
            $prod = $panels[$pid];
        }
        else
            continue;
        $p = '--';
        if(isset($price[$pid]['Price']))$p = $price[$pid]['Price'];
        
//        $max = $prod['max'];
//        $name = $prod['name'];
//        $url = $prod['url'];
//        $cost_ = strtr($prod['cost'],',','.');
//        $deliv = strtr($prod['deliv'],',','.');
//        $pid = $prod['pid'];
        
        $max = 1000;
        $min = 0;
        $name = $prod['Name'];
        $code = $prod['Code'];
        $url = null;
        $cost_ = strtr($p,',','.');
        $deliv = strtr(0,',','.');
        $pid = $pid;
        
        
        $max = $ds_item_add_max;
        
        $percent = esc_attr( get_option('delivery_percent',0) );
        $percent_=($deliv/100)*$percent;
        $cost = ($cost_*$cou)+$deliv+$percent_;
        $total += $cost;
        
        if($deliv>0)$deliv.=' '.$currency_short;
        
        $rep=[];
        $rep['[cod]']=$code;
        $rep['[pid]']=$pid;
        $rep['[cou]']=$cou;
        $rep['[min]']=0;
        $rep['[max]']=$max;
        $rep['[num]']=$num;
        $tpl_cart_item = dshop::_get_tpl('template-parts/dshop/tpl.dshop-cart','item',$rep);
        $tpl_cart_item_noedit = dshop::_get_tpl('template-parts/dshop/tpl.dshop-cart','item-no-edit',$rep);
        
        $field_count=$tpl_cart_item;
        if($ds_item_add_min == $ds_item_add_max)
            $field_count=$tpl_cart_item_noedit;
        
        $item_href = $page_item_url.$pid;
        
//        setup_postdata($post);
//        // формат вывода the_title() ...
//
//        $date = date( "d.m.y", strtotime( $post->post_date ) );
//        
//        $adress = get_post_meta($post->ID,$nm.'adress',1);
//        $namebc =  get_post_meta($post->ID,$nm.'namebc',1);
//        if($namebc)$adress.=' / '.$namebc;
//
//        $fio = get_post_meta($post->ID,$nm.'fio',1);
//        $position = get_post_meta($post->ID,$nm.'position',1);
//        if($position)$fio.=' / '.$position;
//
//        $phone = get_post_meta($post->ID,$nm.'phone',1);
//        $email = get_post_meta($post->ID,$nm.'email',1);
//        $phone.=' / '.$email;
//        
//        $contnt=get_the_content();
//
//        $state_ = get_post_meta($post->ID,$nm.'state',1);
//        $state_f = $state[$state_];
////        $author = get_the_author();
//        $author = get_the_author_posts_link();
//        $author = get_the_author_posts_link_company();
//        $author.=' / '.$state_f;
        /*
         * 
      <td><?=$deliv?></td>
      <td>%<?=$percent?></td>
         */
    ?>
    <tr>
      <th scope="row"><?=++$num?></th>
      <td nowrap></td>
      <td><?=$name?></td>
      <!--<td><a href="<?=$item_href?>"><?=$name?></a></td>-->
      <td><?=$cost_.' '.$currency_short?></td>
      <td nowrap><?=$field_count?></td>
      <td nowrap><?=$code?></td>
      <td><?=$cost.' '.$currency_short?></td>
      <!--<td class="<? //=$state_==0?'bg-danger':'bg-success'?>"><? //=$author?></td>-->
<!--      <td><button id="update-cart-button" class="btn btn-danger cart-button-remove" onclick="jQuery('#item-cou-'+this.dataset.pid).val(0); return true;"
        type="submit" name="go" value="cart" data-num='<?=$num-1?>' data-pid='<?=$pid?>'
        >X</button></td>-->
    </tr>
        <?php

//    echo '<tr><td colspan="8">';
//    echo
////    '<pre>'.
//            print_r($post,1)
////            .'</pre>'
//            ;
//    echo '</td></tr>';
    }
    ?>
    <tr>
      <td colspan="6">Сумма:</td>
      <td><?=$total.' '.$currency_short?></td>
      <!--<td> </td>-->
    </tr>
    <?php
    do_action('ds_cart_total__row_pre', $total, $td_cou = 7);///8
    $total = apply_filters('ds_cart_total', $total);
    ?>
    <tr>
      <td colspan="6">Всего:</td>
      <td><?=$total.' '.$currency_short?></td>
      <!--<td> </td>-->
    </tr>
        <?php
}
else{
    echo '<tr><td colspan="8">';//9
    echo 'Нет записей.';
    echo '</td></tr>';
}
// Обновить корзину
?>
  </tbody>
</table>
<!--<div class="">
    <a href="/checkout" class="btn btn-success">Оформить</a>
        <button id="update-cart-button" class="btn btn-primary"
        type="submit" name="go" value = "cart"
        >Обновить заказ</button>
    <button class="btn btn-primary" type="submit" name="go" value = "checkout"
            >Продолжить</button>
    </div>
</form>-->