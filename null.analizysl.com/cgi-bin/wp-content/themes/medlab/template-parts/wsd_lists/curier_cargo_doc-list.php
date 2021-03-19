<?php

/* 
 * wsd_lists
 * curier_cargo_doc-list.php
 * [wsd_list_curier_cargo_doc-list]
 */
global $wsd_lists_obj,$ht;

//echo $wsd_lists_obj->table_name;

$page_curier_blank  = 4246;
global $ht,$page;
$r_access = [];
$r_access [] ='administrator';
$r_access [] ='ml_administrator';
$r_access [] ='ml_manager';
$r_access [] ='ml_doctor';
$r_access [] ='ml_procedurecab';
$user = wp_get_current_user();
if(count( array_intersect($r_access, (array) $user->roles ) ) == 0 ){
//    get_template_part( 'template-parts/page/tpl.page-access', 'denied' );
//    get_template_part( 'template-parts/page/tpl.page-access', 'notfound' );
//    return null;
}

$tab = $ht->postget('tab',0,FILTER_SANITIZE_NUMBER_INT);
$page = $ht->postget('pg',1,FILTER_SANITIZE_NUMBER_INT);
$lab_g = $ht->postget('lab_g',0,FILTER_SANITIZE_NUMBER_INT);
$cbid = $ht->postget('fid',0,FILTER_SANITIZE_NUMBER_INT);
    
$act = filter_input(INPUT_POST, 'act', FILTER_SANITIZE_STRING);
$conf = filter_input(INPUT_POST, 'conferm', FILTER_SANITIZE_STRING);

$date_from = $ht->postget('date-from',false,FILTER_VALIDATE_REGEXP, '/^[\d]{2}\.[\d]{2}\.[\d]{4}$/');
$date_to = $ht->postget('date-to',false,FILTER_VALIDATE_REGEXP, '/^[\d]{2}\.[\d]{2}\.[\d]{4}$/');

$limit = 100;
$limitfrom = $limit * ($page-1);
$where = ['1'];

switch($tab){
    case 0:break;
    case 1:
        $where[] = 'a.`status_deliv` in(1,2)';
        break;
    case 2:
        $where[] = 'a.`status_deliv` in(2,3)';
        break;
}
if($date_from){
    $from = explode('.',$date_from);
    $where[]=sprintf("a.created >= '%d-%d-%d 00:00:00'",$from[2],$from[1],$from[0]);
}
if($date_to){
//    echo $date_to;
    $to = explode('.',$date_to);
//    echo $ht->pre($to);
//    echo $ht->pre($to[2]);
//    echo $ht->pre($to[1]);
//    echo $ht->pre($to[0]);
    $where[]=sprintf("a.created <= '%d-%d-%d 23:59:59'",$to[2],$to[1],$to[0]);
}

$r_access = [];
$r_access [] ='ml_procedurecab';
if( $ht->access($r_access) ){
    $uId = get_current_user_id();
    $group = get_user_meta($uId, 'lab_group', true);
    $where .= "a.`group` = '$group'";
}

$r_access = [];
$r_access [] ='administrator';
$r_access [] ='ml_administrator';
$r_access [] ='ml_manager';
$r_access [] ='ml_doctor';
if( $ht->access($r_access) ){
    if($lab_g  !== null && $lab_g >0)
        $where .= "a.`group` = '$lab_g'";
}
//add_log($where);

$where = implode(' and ',$where);
            $tab_name = 'wsdl_'.'curier_cargo_doc';
            
		global $wpdb;
        $dsp_attr= $wpdb->prefix . "dsp_attr";
        $dsp_fields= $wpdb->prefix . "dsp_fields";
        $table_ml_groups = $wpdb->prefix . "ml_groups";
        
        $tab_value= $wpdb->prefix . $tab_name . "_value";
//        echo $tab_value;
        $q= "select count(*) from `$tab_value` as a \nwhere $where ";
        $fcou  = $wpdb->get_var($q,0);

		// пагинация
//		$per_page = get_user_meta( get_current_user_id(), get_current_screen()->get_option( 'per_page', 'option' ), true ) ?: 100;

//		$this->set_pagination_args( array(
//			'total_items' => $fcou,
//			'per_page'    => $per_page,
//		) );
//		$cur_page = (int) $this->get_pagenum(); // желательно после set_pagination_args()

//        $dsp_fields= $wpdb->prefix . "dsp_fields";
//        $q= "select * from `$dsp_fields` order by `weigh`";
        $tab_fields= $wpdb->prefix . $tab_name . "_fields";
        $q= "select * from `$tab_fields` order by `weigh` ";
        $fields = $wpdb->get_results($q,ARRAY_A);
        
        $select = [];
        $join = [];
        $select[] = "a.`id` as 'id'";
        
        $list_vars = [];
        $join_titles = 'bcdefghijklmnopqrstuvwxyz';
        $join_titles = str_split($join_titles);
		foreach($fields as $field){
            if($field['tpl']!='td_s_from_'){
                $field_t = $field['name'];
                $select[] = "\na.`{$field['name']}` as '{$field['name']}'";
                
//        add_log($field);
                if($field['tpl']=='td_s_'){
                    $list_vars[$field_t] = unserialize($field['vars']);
//                    $list_vars[$field_t] = [];
//                    $sel = $field['vars'];
//                    $ordersId = explode("\n",$sel);
//                    foreach($ordersId as $o){
//                        $o=explode(':',$o);
//                        $list_vars[$field_t][$o[0]] = $o[1];
//                    }
                }
        }else{
                $join_t = array_shift($join_titles);
                $field_t = $field['name'];
                $field_f = "\n`".$field['name']."`";
                $table = $wpdb->prefix.$field['from_table'];
                $values = trim($field['from_value']);
                $titles = $field['from_title'];
                
                $titles = explode(',',$titles);
                $v = [];
                foreach($titles as $t){
                    if(!strlen(trim($t))){$v[]="'$t'";}else{$v[]="$join_t.`$t`";}
                }
                if(strlen(trim($field['from_value']))&&count($v)>0){
                    $v  = implode(',',$v);
                    $field_f = "concat($v)";
                }
                $select[] = "\n$field_f as '$field_t'";
                $join[] = "\nleft join `$table` as $join_t on $join_t.`$values` = a.`$field_t` ";
            }
        }
//        add_log($fields);
//        add_log($list_vars);
        $select = implode(',',$select);
        $join = implode(' ',$join);
        
        $tab_value= $wpdb->prefix . $tab_name . "_value";
        $q= "select * from `$tab_value` order by `id`";
        $q= "select $select \nfrom `$tab_value` as a $join \nwhere $where \norder by  a.`id` desc \nlimit $limitfrom , $limit";
//        $this->_notice('<div><pre>'.print_r($q,1).'</pre></div>');
        ob_start();
        $items = $wpdb->get_results($q,ARRAY_A);
        $err = ob_get_clean();
//        if($err)$this->n($err);
//        if($err){
//        $this->_notice('<div><pre>'.print_r($q,1).'</pre></div>');
//            $this->_notice($err);
//        }
//		$items = $fields;
//        echo '<pre>'.print_r($this->get_column_info(),1).'</pre>';
        
function delivery_items_update($cargo_id=0){
    global $ds_ext_ml;
    global $ht,$wpdb;
    $bstat =  filter_input(INPUT_POST, 'bstat', FILTER_SANITIZE_NUMBER_INT,FILTER_REQUIRE_ARRAY); // select order
    $bcomm =  filter_input(INPUT_POST, 'bcomm', FILTER_SANITIZE_STRING,FILTER_REQUIRE_ARRAY); // defoult
    $tab_orders= $wpdb->prefix . 'wsdl_' . 'curier_cargo_order' . "_value";
    $tab_bars= $wpdb->prefix . 'wsdl_' . 'curier_cargo_bar' . "_value";
    $q = "select * from $tab_orders where `cargo_id` = '$cargo_id'";
    $orders = $wpdb->get_results($q,ARRAY_A);
        
    foreach($bstat as $bid=>$s){
        $c = $bcomm[$bid];
        $set=[];
        $set['status_cargo']=$s;
        $set['comment']=$c;
        $values = [];
        foreach($set as $name=>$value){
            $values[$name]= "`$name` = '$value'";
        }
        $values = implode(', ', $values);
        $q = "update  $tab_bars set " . $values . " where `id` = '$bid'";
        $wpdb->query($q);
    }
}
/**
 * создание списка заказов и списка контейнеров материалов
 * @global type $ds_ext_ml
 * @global type $wpdb
 * @param type $orders
 * @return type
 */
function delivery_items_form($cargo_id=0){
    global $ds_ext_ml;
    global $ht,$wpdb;
    $tab_orders= $wpdb->prefix . 'wsdl_' . 'curier_cargo_order' . "_value";
    $tab_bars= $wpdb->prefix . 'wsdl_' . 'curier_cargo_bar' . "_value";
    $q = "select * from $tab_orders where `cargo_id` = '$cargo_id'";
    $orders = $wpdb->get_results($q,ARRAY_A);
    
//    $ordersId = explode("\n",$orders);
//    $orders = [];
    $cargo_count_all = 0;
    $cargo_count = [];
    $cargo_containers = [];
    $cont_info  = '';
    $tabls=[];
    
    if(!count($orders))return;
    /**/
//    add_log($orders);
//    add_log($cargo_containers);
    $tab_orders= $wpdb->prefix . 'wsdl_' . 'curier_cargo_order' . "_value";
    $tab_bars= $wpdb->prefix . 'wsdl_' . 'curier_cargo_bar' . "_value";
    foreach($orders as $order){
        $oid = $order['order_id'];
        $q = "select * from $tab_bars where `cargo_id` = '$cargo_id' and `order_id` = '$oid'";
        $bars = $wpdb->get_results($q,ARRAY_A);
        
        $date=date('Y-m-d H:i',strtotime($order['date'] )); // the_time();
        
        $col = [];
        $col[] = $ht->f('div', 'Заказ:',['class'=>'col-3']). $ht->f('div', $ht->f('b',$order['order_id']),['class'=>'col-9']);
        $col[] = $ht->f('div', 'Номер:',['class'=>'col-3']). $ht->f('div', $ht->f('b',$order['nr']),['class'=>'col-9']);
        $col[] = $ht->f('div', 'ФИО:',['class'=>'col-3']). $ht->f('div', $ht->f('b',$order['patient_fio']),['class'=>'col-9']);
        $col[] = $ht->f('div', 'Дата:',['class'=>'col-3']). $ht->f('div', $ht->f('b',$date),['class'=>'col-9']);
//        $col[] = 'Номер: '.$ht->f('b',$order['nr']);
//        $col[] = 'ФИО: '.$ht->f('b',$order['patient_fio']);
//        $col[] = 'Дата: '.$ht->f('b',$date);
//        $cols = '<div class="col">'.implode('</div><div class="col">',$col).'</div>';
        $cols = implode("\n",$col);
        $row = '<div class="row m-2">'.$cols.'</div>';
//        echo $row;
        $data  = [];
        $rclass = [];
        foreach($bars as $rnum=>$cont){
            $bid = $cont['id'];
            $set = [];
            $sel_d = [];
            $sel_d[0]='Норма';
            $sel_d[1]='Проблема';
//            $sel_d[]='Создан';
//            $sel_d[]='Отправлен';
//            $sel_d[]='Доставлен';
//            $sel_d[]='Не доставлен';
            
            // ['.$oid.']
            $c = $ht->select('bstat['.$bid.']',$sel_d,$cont['status_cargo'],'form-control mt-2'); // mass operation bonuses states
            if($cont['status_cargo']==1)$rclass[$rnum]='text-white bg-danger';
            
//            $set['material_name'] = $cont['matr'];
            $at = [];
      //      $at['class'] = 'btn btn-primary texSt-white mb-1';
      //      $at['target'] = '_blank';
            $at['cols'] = '20';
            $at['rows'] = '4';
            // ['.$oid.']
            $at['name'] = 'bcomm['.$bid.']';
              $item = [];
              $item[]=$c;
      //        $item[]=$cont['id'];
      //        $item[]=$cont['cargo_id'];
      //        $item[]=$cont['order_id'];
      //        $item[]=$cont['cont_code'];
              $item[]=$cont['bar_name'];
              $item[]=$cont['material_name'];
              $item[]= '';
              $item[]=$ht->f('textarea',$cont['comment'],$at);
//                $ht->f('a','Бланк',$at)
//                .$ht->f('a','Изменить',$ate)
//                .'</div><div class="col-1"></div><div class="col-11">' . $list_vars['status_deliv'][$it['status_deliv']];
            $data[]=$item;
        }
        $tabl = build_items_tabl($data,$rclass);
        $row = '<div class="col-12 ">'.$row.$tabl.'</div>';
        $row = '<div class="row m-2 border border-primary mb-2">'.$row.'</div>';
        $tabls[] = $row;
    }
//    $c1 = count($orders);
//    $mess = "Добавлено заказов: $c1, контейнеров: $cargo_count_all.";
//    add_log($mess);
//    add_log($cont_info);
    return implode ("\n",$tabls);
}

function build_items_tabl($data,$rclass){
    global $ht,$wpdb;

$rows = [];
        
        $at=[];
        $at['id']='selo-g';
        $at['class']='solo solo-gg';
        $at['name']='selo-g';
        $at['value']='1';
//        if($selo && in_array($orderId,$selo))
//            $at['checked']='checked';
        $at['type']='checkbox';
        $check = '';
//        if( !$isdoctor && !$isagent){
//            $check  =  $ht->f('input','',$at);
//        }

$hitems = [];
//$hitems[]='<label>'.$check.'№</label>';
//$hitems[0]='ID';
//$hitems[]='Создано';
//$hitems[]='Курьер';
//$hitems[]='Лаборатория';
//$hitems[]='Пункт';
//$hitems[]='Кол-во заказов';
//$hitems[]='Распечатать';

//$hitems[]='% бонуса';
//$hitems[]='Состояние выплаты';
//if($showD)
//$hitems[]='doctor id';

    $usenumbers = true;
    $usenumbers = false;

$inorder = [];
//$inorder[] = 0-!$usenumbers;
//$inorder[] = 2;
//$inorder[] = 3;
//$inorder[] = 4;
//$inorder[] = 7;
//$inorder[] = 1-!$usenumbers;
//$inorder[] = 2-!$usenumbers;
//$inorder[] = 3-!$usenumbers;
//$inorder[] = 6-!$usenumbers;

$test_cols = false;
$cclass=[]; // coll class
$cclass[0] = 3;
$cclass[] = 3;
$cclass[] = 3;
$cclass[] = 6;
$cclass[] = 6;
//$cclass[] = 1;
//$cclass[] = 2;
//$cclass[] = 6;
//$cclass[] = 1;
//$cclass[] = 2;

//echo $ht->pre($urlget);
    $defs =[];
    $defs['usenumbers'] = $usenumbers;
    $defs['hclass'] = 'ml-2 mr-2';
    $defs['dclass'] = 'ml-2 mr-2';
    $defs['cclass'] = $cclass;
    $defs['rclass'] = $rclass;
    $defs['hitems'] = $hitems;
    $defs['inorder'] = $inorder;
//    $defs['orders'] = $orders;
    $defs['sortVName'] = 'order';
//    $defs['urlget'] = $urlget;
    $defs['ma']='↓';
    $defs['md']='↑';
    $defs['sortClass']='btn';
    $defs['data'] = $data;
    
    
    $table = $ht->btabl($defs);
    return $table;
}
        
/**  /        

$items_per_page = 50;
//$page = isset($_GET['page']) && (int)$_GET['page'] > 1 ? (int)$_GET['page'] : 1;
$where = [];
$order = '`p`.`vip` DESC, `ec`.`date` DESC';
$order_ = false;
$orders=[];
$orders['0a']='p.post_date asc';
$orders['0d']='p.post_date desc';
$orders['1a']='um.meta_value asc';
$orders['1d']='um.meta_value desc';
$orders['2a']='nr.meta_value asc';
$orders['2d']='nr.meta_value desc';
$orders['3a']='`ec`.`date` asc';
$orders['3d']='`ec`.`date` desc';
$orders['4a']='`p`.`vip` asc';
$orders['4d']='`p`.`vip` desc';
    
$order = 'p.post_date desc';
if ($ht->get('order',false)) {
    $order__ =$ht->get('order',false);
    if($orders && array_key_exists($order__,$orders)){
        $order_ = $order__;
        $order = $orders[$order_];
    }
}else
if ($ht->post('order',false)) {
    $order__ =$ht->post('order',false);
    if($orders && array_key_exists($order__,$orders)){
        $order_ = $order__;
        $order = $orders[$order_];
    }
}

$data = [];

    // параметры по умолчанию
    $args =[
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

        ],
        'post_type'   => 'dsorder',
        'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
    ] ;
    $order_val = false;
    $q_order_by = 'p.post_date desc';
    if($orderby == '0a'){
//        $order = [
//        'order' => 'ASC',
//        'orderby' => 'meta_value',
//        'meta_query' => array(
//            array('key' => 'order_in_archive'))
//        ];
        $order_val = [
        'order' => 'ASC',
        'orderby' => 'date'
        ];
        $args = $order_val+$args;
        $q_order_by = 'p.post_date asc';
    }
    if($orderby == '0d'){
        $order_val = [
        'order' => 'DESC',
        'orderby' => 'date'
        ];
        $args = $order_val+$args;
        $q_order_by = 'p.post_date desc';
    }
    if($orderby == '2a'){
        $order_val = [
        'order' => 'ASC',
        'orderby' => 'meta_value',
        'meta_query' => array(
            array('key' => 'dso_query_nr'))
        ];
        $args = $order_val+$args;
        $q_order_by = 'nr.meta_value asc';
    }
    if($orderby == '2d'){
        $order_val = [
        'order' => 'DESC',
        'orderby' => 'meta_value',
        'meta_query' => array(
            array('key' => 'dso_query_nr'))
        ];
        $args = $order_val+$args;
        $q_order_by = 'nr.meta_value desc';
    }
    if($orderby == '1a'){
        $q_order_by = 'um.meta_value asc';
    }
    if($orderby == '1d'){
        $q_order_by = 'um.meta_value desc';
    }
//    add_log($args);
    $_posts = get_posts( $args );
    
		global $wpdb;
//        $newtable = $wpdb->get_results( "SELECT id FROM newtable" );
        
$meta_key1 = 'model';
$meta_key2 = 'year';
$meta_key3 = 'manufacturer';
$meta_key3_value = 'Ford';

$join = [];
$where=[];
if($f_pcode)$where[]=" um.meta_value =  '$f_pcode'";
if($f_nr)$where[]=" nr.meta_value =  '$f_nr'";
if($f_duid)$where[]=" d.meta_value =  '$f_duid'";
if($f_auid){
    $where[]=" a.meta_key = 'dso_auid'";
    $where[]=" a.meta_value =  '$f_auid'";
    $join[]=" LEFT join $wpdb->postmeta a on a.post_id = p.ID";
}
if($date_from){
    $from = explode('.',$date_from);
    $where[]=sprintf("p.post_date >= '%d-%d-%d 00:00:00'",$from[2],$from[1],$from[0]);
}
if($date_to){
    $to = explode('.',$date_to);
    $where[]=sprintf("p.post_date <= '%d-%d-%d 23:59:59'",$to[2],$to[1],$to[0]);
}
if($f_dso_bs){
    $join[]="LEFT join $wpdb->postmeta bs on bs.post_id = p.ID";
    $where[]=" bs.meta_key = 'dso_dbonus_state'";
    if($f_dso_bs == 1)$where[]=" bs.meta_value =  '1'";
    if($f_dso_bs == 2)$where[]=" bs.meta_value <>  '1'";
}
//if($lab_g >-1){
if($lab_g  !== null && $lab_g >-1){
    $join[]="LEFT join $wpdb->postmeta lb on lb.post_id = p.ID";
    $where[]=" lb.meta_key = 'dso_sender'";
    $join[]="LEFT join  $wpdb->users su on su.ID = lb.meta_value";
    $join[]="LEFT JOIN $wpdb->usermeta lg ON lg.user_id = lb.meta_value";
    $where[]=" lg.meta_key = 'lab_group'";
    $where[]=" lg.meta_value = '$lab_g'";
}
$join=implode("\n    ",$join);
$where=implode("\nand ",$where);
if($where) $where = 'and '.$where;

$q = "SELECT count(p.ID) FROM $wpdb->posts p
    LEFT join $wpdb->postmeta pm on pm.post_id = p.ID
    LEFT join $wpdb->postmeta nr on nr.post_id = p.ID
    LEFT join $wpdb->postmeta d on d.post_id = p.ID
    LEFT join $wpdb->postmeta stat on stat.post_id = p.ID
    LEFT join  $wpdb->users u on u.ID = pm.meta_value
    LEFT JOIN $wpdb->usermeta um ON um.user_id = u.ID
    $join
    WHERE post_type = 'dsorder' AND post_status = 'publish'
    and stat.meta_key = 'dso_status'
    and ( stat.meta_value = 'query_sent' or  stat.meta_value = 'query_sent' )
    and pm.meta_key = 'dso_puid'
    and nr.meta_key = 'dso_query_nr'
    and d.meta_key = 'dso_duid'
    and um.meta_key = 'card_numer' 
    $where
    order by $order
        ";
$dso_count = $wpdb->get_var($q);
//add_log($ht->pre($dso_count));

$q = "SELECT p.ID FROM $wpdb->posts p
    LEFT join $wpdb->postmeta pm on pm.post_id = p.ID
    LEFT join $wpdb->postmeta nr on nr.post_id = p.ID
    LEFT join $wpdb->postmeta d on d.post_id = p.ID
    LEFT join $wpdb->postmeta stat on stat.post_id = p.ID
    LEFT join  $wpdb->users u on u.ID = pm.meta_value
    LEFT JOIN $wpdb->usermeta um ON um.user_id = u.ID
    $join
    WHERE post_type = 'dsorder' AND post_status = 'publish'
    and stat.meta_key = 'dso_status'
    and ( stat.meta_value = 'query_sent' or  stat.meta_value = 'query_sent' )
    and pm.meta_key = 'dso_puid'
    and nr.meta_key = 'dso_query_nr'
    and d.meta_key = 'dso_duid'
    and um.meta_key = 'card_numer' 
    $where
    order by $order
    ";
//add_log($ht->pre($hiddens));
//add_log($ht->pre($q));

if(0){
$postids = $wpdb->get_col($wpdb->prepare("
SELECT      key3.post_id
FROM        $wpdb->postmeta key3
INNER JOIN  $wpdb->postmeta key1
			on key1.post_id = key3.post_id
			and key1.meta_key = %s
INNER JOIN  $wpdb->postmeta key2
			on key2.post_id = key3.post_id
			and key2.meta_key = %s
WHERE       key3.meta_key = %s
			and key3.meta_value = %s
ORDER BY    key1.meta_value, key2.meta_value",$meta_key1, $meta_key2, $meta_key3, $meta_key3_value)); 
}

$postids = $wpdb->get_col($q);
//add_log($postids);
/**/
        
        $data  = [];
        $rclass = [];
    $total = 0;$total_b=0;
$showD = true; //  show doctor id
//echo $ht->f('div','Найдено '.$dso_count.' записей',['class'=>'col-12']);
if (10 &&  '$postids') {
//  echo 'List of ' . $meta_key3_value . '(s), sorted by ' . $meta_key1 . ', ' . $meta_key2;
    $num = 1;
    
    $total = 0;$total_b=0;
//  foreach( $postids as $id ){
  foreach( $items as $rnum=>$it ){
      
      /** /
	$dsorder = get_post(intval($id));
    
    /*
	setup_postdata($post);
	?>
	<p><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></p>
	<?php
    /** /
    
//  }
//}
//if(1){
//    foreach( $_posts as $dsorder ){
        setup_postdata($dsorder);
        $orderId = $dsorder->ID;

        $date = date( "d.m.y", strtotime( $dsorder->post_date ) );
        
        
        $state_ =  get_post_meta( $dsorder->ID, 'dso_status', true );
        $dso_total_ =  get_post_meta( $dsorder->ID, 'dso_cost', true );
        $dso_puid =  get_post_meta( $orderId, 'dso_puid', true );
        $dso_duid =  get_post_meta( $orderId, 'dso_duid', true );
        $dso_query_id =  get_post_meta( $orderId, 'dso_query_id', true );
        $dso_query_nr =  get_post_meta( $orderId, 'dso_query_nr', true );
        $card_numer = esc_attr(get_the_author_meta('card_numer', $dso_puid));
        $dso_dbonus_val =  (int)get_post_meta( $orderId, 'dso_dbonus_val', true );
        $dso_dbonus_state =  (int)get_post_meta( $orderId, 'dso_dbonus_state', true );
        
        $total += $dso_total_;$total_b += $dso_dbonus_val;
        
        $at=[];
        $at['name']='selo-d['.$orderId.']';
        $at['value']=''.$dso_query_nr.'';
        $at['type']='hidden';
        $check_d  =  $ht->f('input','',$at);
        
        $at=[];
        $at['id']='selo-'.$orderId.'';
        $at['class']='solo solo-g';
        $at['name']='selo['.$orderId.']';
        $at['value']=''.$dso_query_nr.'';
//        if($selo && in_array($dso_query_nr,$selo))
        if($selo && key_exists($orderId,$selo))
            $at['checked']='checked';
        $at['type']='checkbox';
        $check = '';
        if( !$isdoctor && !$isagent){
            $check  =  $ht->f('input','',$at);
        }
        
        $at=[];
        $at['for']='selo-'.$orderId.'';
        $at['class']='m-0 solo solo-l';
        $check  =  $check_d.$ht->f('label',$check.$num,$at);
        $date  =  $ht->f('label',$date,$at);
        $card_numer  =  $ht->f('label',$card_numer,$at);
        $dso_query_nr  =  $ht->f('label',$dso_query_nr,$at);
        $dso_total_  =  $ht->f('label',$dso_total_,$at);
        $dso_dbonus_val  =  $ht->f('label',$dso_dbonus_val,$at);

        $item = [];
        $item[]=$check;
        $item[]=$date;
        $item[]=$card_numer;
        $item[]=$dso_query_nr;
        $item[]=$dso_total_;
//        $item[]=$dso_dbonus_val;
//        $item[]=$dso_dbonus_state?'Выплачен':'Не выплачен';
//        if($showD)
//        $item[]=$dso_duid;
        $data[]=$item;
    /**/
      
      $at = [];
      $at['class'] = 'btn btn-primary text-white mb-1';
      $at['target'] = '_blank';
      $at['href'] = get_the_permalink( $page_curier_blank ).'?cbid='.$it['id']; // curier blank id
      
      $ate = [];
      $ate['class'] = 'btn btn-success text-white mb-1';
//      $ate['target'] = '_blank';
      $ate['href'] = get_the_permalink(get_the_ID() ) . '?pg=' . $page . '&tab=' . $tab.'&fid='.$it['id']; // curier blank id
//        add_log($it);
        $item = [];
        $item[]=$it['id'];
        $item[]=date('Y-m-d H:i',strtotime($it['created'] )); // the_time();
        $item[]=$it['curier'];
        $item[]=$it['laboratory'] . '<br/>';
        $item[]=$it['group'];
        $item[]=count(explode("\n",$it['orders']));
        $item[]=
                $ht->f('a','Бланк',$at)
                .$ht->f('a','Изменить',$ate)
                .'</div><div class="col-1"></div><div class="col-11">' . $list_vars['status_deliv'][$it['status_deliv']];
//        $item[]=$dso_query_nr;
//        $item[]=$dso_total_;
//        $item[]=$dso_dbonus_val;
//        $item[]=$dso_dbonus_state?'Выплачен':'Не выплачен';
//        if($showD)
//        $item[]=$dso_duid;
        if(($tab == 1)&&$it['status_deliv']==2)$rclass[$rnum]='text-white bg-danger';
        if(($tab == 0 || $tab == 2)&&$it['status_deliv']==3)$rclass[$rnum]='text-white bg-danger';
        $data[]=$item;
        $num++;
    }
}
//add_log($rclass);
$itemTotal = [];
$it=[];
$it['class'] = 'col-10';
$it['val'] = 'Всего:';
$itemTotal[] = $it;
$it['class'] = 'col-2';
$it['val'] = $total;
$itemTotal[] = $it;
//$it['class'] = 'col-1';
//$it['val'] = $total_b;
//$itemTotal[] = $it;
//$data = array_merge([$itemTotal],$data,[$itemTotal]);

//add_log($selo);
$rows = [];
        
        $at=[];
        $at['id']='selo-g';
        $at['class']='solo solo-gg';
        $at['name']='selo-g';
        $at['value']='1';
//        if($selo && in_array($orderId,$selo))
//            $at['checked']='checked';
        $at['type']='checkbox';
        $check = '';
//        if( !$isdoctor && !$isagent){
//            $check  =  $ht->f('input','',$at);
//        }

$hitems = [];
//$hitems[]='<label>'.$check.'№</label>';
$hitems[0]='ID';
$hitems[]='Создано';
$hitems[]='Курьер';
$hitems[]='Лаборатория';
$hitems[]='Пункт';
$hitems[]='Кол-во заказов';
$hitems[]='Распечатать';
//$hitems[]='% бонуса';
//$hitems[]='Состояние выплаты';
//if($showD)
//$hitems[]='doctor id';

    $usenumbers = true;
    $usenumbers = false;

$inorder = [];
//$inorder[] = 0-!$usenumbers;
//$inorder[] = 2;
//$inorder[] = 3;
//$inorder[] = 4;
//$inorder[] = 7;
//$inorder[] = 1-!$usenumbers;
//$inorder[] = 2-!$usenumbers;
//$inorder[] = 3-!$usenumbers;
//$inorder[] = 6-!$usenumbers;

$test_cols = false;
if($test_cols ){
    //$items = [];
    $item = [];
    $item[]='_csize_';
    $item[]='_ncol_';
    $item[]='hi';
    $item[]='hi';
    $item[]=$ht->mod;
    $item[]='hi';
    $data[]=$item;

    $item = [];
    $item[]='_csize_';
    $item[]='_ncol_';
    $item[]=$ht->mod;
    $item[]='hi';
    $item[]=$ht->mod;
    $item[]='hi';
    $data[]=$item;

    $item = [];
    $item[]='_csize_';
    $item[]='_ncol_';
    $item[]='hi';
    $item[]=$ht->mod;
    $item[]='hi';
    $item[]=$ht->mod;
    $data[]=$item;

    $item = [];
    $item[]='_csize_';
    $item[]='_ncol_';
    $item[]='hi';
    $item[]=$ht->mod;
    $item[]='hi';
    $item[]=$ht->mod;
    $data[]=$item;
}

//$cclass=[]; // coll class
//$cclass[0] = 1;
//$cclass[1] = 2;
//$cclass[] = 2;
//$cclass[] = 2;
//$cclass[] = 1;
//$cclass[] = 1;
//$cclass[] = 2;

$cclass=[]; // coll class
$cclass[0] = 1;
$cclass[] = 2;
$cclass[] = 2;
$cclass[] = 2;
$cclass[] = 2;
$cclass[] = 1;
$cclass[] = 2;
//$cclass[] = 1;
//$cclass[] = 2;

//echo $ht->pre($urlget);
    $defs =[];
    $defs['usenumbers'] = $usenumbers;
    $defs['cclass'] = $cclass;
    $defs['rclass'] = $rclass;
    $defs['hitems'] = $hitems;
    $defs['inorder'] = $inorder;
//    $defs['orders'] = $orders;
    $defs['sortVName'] = 'order';
//    $defs['urlget'] = $urlget;
    $defs['ma']='↓';
    $defs['md']='↑';
    $defs['sortClass']='btn';
    $defs['data'] = $data;
    
    
    $table = $ht->btabl($defs);
    
    
//    echo $table;
    
    $pagination  = [];
    $pages = ceil($fcou/$limit);
    for($p=1;$p<=$pages;$p++){
        $atli = ['class'=>'page-item '.($p==$page?'disabled':'')];
        $ata = ['class'=>'page-link '.($p==$page?'bg-primary text-white':''),'href'=>get_the_permalink( get_the_ID() ) . '?pg=' . $p . '&tab=' . $tab];
        $pagination[]=$ht->f('li',$ht->f('a',$p,$ata),$atli);
    }
    if(count($pagination)==1)
        $pagination = [];
    

    
//$step = 1;
//if($_SESSION['curier_doc_orders'])
//$step = 2;
$step = 3;
?>
<div class="row">
    <div class="col-12">

        <div class="card-deck justify-content-md-center">
            <div class="card <?=$step==1?'text-white bg-success':'text-primary border-primary'?> mb-3" style="max-width: 18rem;">
              <div class="card-header">Step 1</div>
              <div class="card-body">
                <h5 class="card-title">Выбрать заказы</h5>
                <p class="card-text">Отметить пункты, нажать "Добавить".</p>
                <p class="card-text">Если добавлены лишние или нужно создать новый список, нажать "Сбросить".</p>
                <p class="card-text">После добавления перейти к следующему шагу.</p>
              </div>
            </div>
            <div class="card <?=$step==2?'text-white bg-success':'text-primary border-primary'?> mb-3" style="max-width: 18rem;">
              <div class="card-header">Step 2</div>
              <div class="card-body">
                <h5 class="card-title">Создать документ</h5>
                <p class="card-text">Указать данные курьера.</p>
                <p class="card-text">Нажать "Сформировать отчет".</p>
                <p class="card-text">Перейти на страницу "Документ доставки — список".</p>
              </div>
            </div>
            <div class="card <?=$step==3?'text-white bg-success':'text-primary border-primary'?> mb-3" style="max-width: 18rem;">
              <div class="card-header">Step 3</div>
              <div class="card-body">
                <h5 class="card-title">Распечатать бланк</h5>
                <p class="card-text">Открыть бланк доставки.</p>
                <p class="card-text">Распечатать.</p>
              </div>
            </div>
        </div>
    </div>
</div>
    <form action="<?=get_the_permalink( get_the_ID() ) . '?tab=' . $tab?>" method="post" class="col-12 text-left  border border-primary mb-2">
<div class="row m-2">
        <?php
        
//    echo $ht->f('input','',['type'=>'hidden','name'=>'order','value'=>$orderby])."\n";
//    echo $ht->f('input','',['type'=>'hidden','name'=>'count','value'=>$count])."\n";
//    echo $ht->f('input','',['type'=>'hidden','name'=>'offset','value'=>$offset])."\n";
        ?>
        <!--<div class="row">-->
<!--            <div class="col-12">
                <?php
//                        var_dump( $lab_g);
                ?>
            </div>-->
<?php

    $r_access = [];
    $r_access [] ='administrator';
    $r_access [] ='ml_administrator';
    $r_access [] ='ml_manager';
//    $r_access [] ='ml_doctor';
    $r_access [] ='ml_agent';
    $r_access [] ='ml_procedurecab';
    if(0&&$ht->access($r_access)){
?>
            <div class="col-12 text-left -m-2">
                <div class="row">
                    <div class="col-12 text-left">
                        <label class="mb-0 mt-2">Фильтровать по доктору</label>
                    </div>
                    <div class="col-12">
                        <?=$f_sel_docter?>
                    </div>
                </div>
            </div>
<?php
    }

    $r_access = [];
    $r_access [] ='administrator';
    $r_access [] ='ml_administrator';
    $r_access [] ='ml_manager';
//    $r_access [] ='ml_doctor';
    $r_access [] ='ml_procedurecab';
    if(0&&$ht->access($r_access)){
?>
            <div class="col-12 text-left -m-2">
                <div class="row">
                    <div class="col-12 text-left">
                        <label class="mb-0 mt-2">Фильтровать по представителю</label>
                    </div>
                    <div class="col-12">
                        <?=$f_sel_agent?>
                    </div>
                </div>
            </div>
<?php
    }
?>
<?php
    if(0){
?>
            <div class="col-3 text-left -m-2">
                <?=$f_i_ucode?>
            </div>
            <div class="col-3 text-left -m-2">
                <?=$f_i_nr?>
            </div>
<?php
    }
?>
                
            <div class="col-4 text-left -m-2">
                <div class="row">
                    <div class="col-12 text-left">
                        <label class="mb-0 mt-2">Дата от</label>
                    </div>
                    <div class="col-12">
                        <input type="text" name="date-from" id="from" value="<?=$date_from?>">
                    </div>
                </div>
            </div>
            <div class="col-4 text-left -m-2">
                <div class="row">
                    <div class="col-12 text-left">
                        <label class="mb-0 mt-2">Дата до</label>
                    </div>
                    <div class="col-12">
                        <input type="text" name="date-to" id="to" value="<?=$date_to?>">
                    </div>
                </div>
            </div>
<?php
/** /
?>
            <div class="col-6">
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="customRadioInline1" name="dso_bs" class="custom-control-input" value="0"
                           <?=$f_dso_bs==0?'checked="checked"':''?>>
                    <label class="custom-control-label" for="customRadioInline1">Все</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="customRadioInline2" name="dso_bs" class="custom-control-input" value="1"
                           <?=$f_dso_bs==1?'checked="checked"':''?>>
                    <label class="custom-control-label" for="customRadioInline2">Выплачено</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="customRadioInline3" name="dso_bs" class="custom-control-input" value="2"
                           <?=$f_dso_bs==2?'checked="checked"':''?>>
                    <label class="custom-control-label" for="customRadioInline3">Не выплачен о</label>
                </div>
            </div>
<?php
    /**/

    $r_access = [];
    $r_access [] ='administrator';
    $r_access [] ='ml_administrator';
    $r_access [] ='ml_manager';
//    $r_access [] ='ml_doctor';
//    $r_access [] ='ml_procedurecab';
    if($ht->access($r_access)){
?>
            <div class="col-8">
                <div class="row">
                    <div class="col-12 text-left">
                        <label class="mb-0 mt-2">Группа лаборантов</label>
                    </div>
                    <div class="col-12">
                        <?php
//                        $sel_g = ProfileFields::get_lab_group_list();
                        $sel_g = MedLabLabGroupFields::get_lab_group_list();
        //                $ht->form_method='post';
                        $c = $ht->select('lab_g',$sel_g,-1,'form-control'); // mass operation bonuses states
                        echo $c;
            //            add_log(get_class_methods ($ht) );
                        ?>
                    </div>
                </div>
            </div>
<?php
    }
?>
            <div class="col-12 text-left">
                <button type="sumbit" class="btn btn-primary mt-3 mb-3">Применить</button>
            </div>
    <div class="col-3">
        <?=$date_from||$date_to?'Выборка заказов ':''?>
    </div>
    <div class="col-3">
        <?=$date_from?'С '.$date_from:''?>
    </div>
    <div class="col-3">
        <?=$date_to?'По '.$date_to:''?>
    </div>
</div>
    </form>
<?php
include __DIR__. '/../component/tpls/tpl-calendar-init.php';

if($cbid){
    ?>
<form action="<?=get_the_permalink( get_the_ID() ) . '?pg=' . $page . '&tab=' . $tab?>"
      method="post" class="border border-primary mb-2">
    <!--col-12 text-left  border border-primary mb-2-->
    <div class="row m-2" >
            <div class="col-6">
                <div class="row">
                    <div class="col-12 text-left">
                        <!--<label class="mb-0 mt-2">Курьеры</label>-->
                    </div>
                    <div class="col-12">
                        <?php
////                        $sel_g = ProfileFields::get_lab_group_list();
//                        $sel_g = MedLabLabGroupFields::get_lab_group_list();
//        //                $ht->form_method='post';
//                        $c = $ht->select('lab_g',$sel_g,-1,'form-control'); // mass operation bonuses states
//                        echo $c;
            //            add_log(get_class_methods ($ht) );
                        ?>
                    </div>
                </div>
            </div>
        <div class="col-12 pt-2">
            <!--Массовые операции:-->
            <?php
            
            echo '<input type="hidden" name="form_type" value="edit">';
            
            $table_name = 'wsdl_'.'laboratories';
            $table_name = 'wsdl_'.'curier_cargo_doc';
//            $table_name = 'laboratory';
//                $ltf = WSD_LISTS_DIR.'/list/'.'class.WSDListListTable.php';
//                require_once $ltf;
//                $lt = new WSDListListTable($table_name,get_the_permalink( get_the_ID() ).$p);
//            $lt->display();
            
//            $orders = [];
//            foreach($_SESSION['curier_doc_orders'] as $c=>$n){
//                $orders[] = "$c:$n";
//            }
            
//            $timezone = date_default_timezone_get();
//            add_log('timezone '.$timezone);
//            $timezone = get_option('gmt_offset');
//            add_log('timezone '.$timezone);
            $defs  = [];
//            $defs['orders'] = implode("\n",$orders);
//            $defs['status_deliv'] = 0;
//            $defs['created'] = date('Y-m-d H:i:s');
//            $defs['created'] = current_time('Y-m-d H:i:s');
//            $defs['group'] = 0;
            $tpls  = [];
            $tpls['orders'] = 'td_t_';
//            $tpls['status_deliv'] = 'td_h_';
//            $tpls['status_deliv'] = 'td_t_';
            $tpls['created'] = 'td_t_';
            $tpls['group'] = 'td_t_';
//    add_log($defs);
//    add_log($defs);
            $pf = new WSDListItem($table_name,$defs,$tpls);
            $pf->display();
            if($act == 'update-cargo-doc' && $conf == 'ok'){
                delivery_items_update();
                $selo=[];
                $_SESSION['curier_doc_orders'] =[];
                add_log('Обновлён документ для курьера');
                header("Refresh:3");
//                exit;
            }
            ?>
        </div>
        <div class="col-3   pt-2">
            <!--Сбросить список заявок-->
            <?php
//            $operations = [];
//            $operations [0]= '--';
//            $operations [1]= 'Выплачен';
//            $operations [2]= 'Не выплачен';
//            $ht->form_method='post';
//            $c = $ht->select('mo_bs',$operations); // mass operation bonuses states
//            echo $c;
//            add_log(get_class_methods ($ht) );
            $at = [];
            $at['type'] = 'hidden';
            $at['name'] = 'act';
            $at['value'] = 'update-cargo-doc';
            echo $ht->f('input','',$at);
            $at = [];
            $at['type'] = 'hidden';
            $at['name'] = 'conferm';
            $at['value'] = 'ok';
            echo $ht->f('input','',$at);
            ?>
        </div>
        <div class="col-3">
            <button type="sumbit" name="act-type" value="append" class="btn btn-primary -mt-3 mb-3">Обновить отчет</button>
        </div>
    </div>
            <?php
            echo delivery_items_form($cbid);
            ?>
</form>
        <?php
}

?>
<!--<div class="col-12 text-left  border border-primary mb-2">-->
    <div class="row m-0">
        <div class="col-12">
<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link <?=$tab==0?'active':''?>" href="<?=get_the_permalink( get_the_ID() ) . '?tab=0'?>">Все</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?=$tab==1?'active':''?>" href="<?=get_the_permalink( get_the_ID() ) . '?tab=1'?>">Отправленные</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?=$tab==2?'active':''?>" href="<?=get_the_permalink( get_the_ID() ) . '?tab=2'?>">Полученные</a>
  </li>
  <li class="nav-item">
    <a class="nav-link disabled" href="#"></a>
  </li>
</ul>
        </div>
<!--</div>-->
<div class="col-12 text-left  border border-primary mb-2">
    <div class="row m-2">
        <div class="col-12">
<?php
    echo $table;

?>
        </div>
    </div>
    <div class="row m-2">
        <div class="col-12">
            <nav aria-label="...">
                <ul class="pagination -pagination-sm">
<?php
    echo implode($pagination);

?>
                </ul>
          </nav>
        </div>
    </div>
</div>
    </div>
