<?php

/* 
 * tpl.dshop-cart-items.php
 */

/*
 * 
      <th scope="col">Доставка</th>
      <th scope="col">Наценка</th>
 */
?>

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
        
        $max = 1;
        $name = $prod['Name'];
        $code = $prod['Code'];
        $url = null;
        $cost_ = strtr($p,',','.');
        $deliv = strtr(0,',','.');
        $pid = $pid;
        
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
        
        $item_href = $page_item_url.$pid;
        
//        $tpl_cart_item =
//                dshop::_get_tpl('template-parts/page/tpl.dshop-cart','item',$rep);
//        $field_count=$tpl_cart_item;
        
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
      <td><a href="<?=$item_href?>"><?=$name?></a></td>
      <td><?=$cost_.' '.$currency_short?></td>
      <td nowrap><?=$cou?></td>
      <td nowrap><?=$code?></td>
      <td><?=$cost.' '.$currency_short?></td>
      <!--<td class="<? //=$state_==0?'bg-danger':'bg-success'?>"><? //=$author?></td>-->
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
    </tr>
    <?php
    do_action('ds_cart_total__row_pre', $total, $td_cou = 7);
    $total = apply_filters('ds_cart_total', $total);
    ?>
    <tr>
      <td colspan="6">Всего:</td>
      <td><?=$total.' '.$currency_short?></td>
    </tr>
        <?php
}
else{
    echo '<tr><td colspan="8">';
    echo 'Нет записей.';
    echo '</td></tr>';
}?>
  </tbody>
</table>
<div class="">
    <a href="/cart" class="btn btn-primary text-white">Изменить корзину.</a>
</div>