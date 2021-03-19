<?php

/* 
 * download_xml_good_owners.php
 */


//add_action('init','action_get_registry_good_owners_init');// ? seconds inits
function action_get_registry_good_owners_init( $post_id =  0 ){
	// action...
//    add_log('action_get_registry_good_owners_init ');

    if(filter_input(INPUT_POST, 'action') == 'get_registry_good_owners'){
        action_get_registry_good_owners();
    }
}
function mldr_xml_initfields(){
    $f=[];
//    время ,
// откуда
// куда,
// информацию кто отправил,
// кто принял,
// кто доставщик,
// состояние доставки ( брак, не  доставлено) ,
// поле комментарии
    $f['A']='№';
    $f['B']='время';
    $f['C']='откуда';
    $f['D']='куда';
    $f['E']='отправил';
    $f['F']='принял';
    $f['G']='доставил';
    $f['H']='состояние';
    $f['I']='комментарий';
    return $f;
}
function mldr_xml_data(){
    $page = 1;
    $limit = 100;
    $limitfrom = $limit * ($page-1);
    
    $where = ['1'];
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
        
        /*
         * 
            [id] => 17
            [curier] => Рейн Окампо 9876543211
            [laboratory] => Resident Evil
            [orders] => 4623:9950000362
4569:9950000360
            [note] => 
            [status_deliv] => 3
            [created] => 2020-03-23 12:59:08
            [group] => МО Ганга
         * 
 - номер заказа,
 время ,
 откуда
 куда,
 информацию кто отправил,
 кто принял,
 кто доставщик,
 состояние доставки ( брак, не  доставлено) ,
 поле комментарии( которое указывается при оформление заказа на доставку)
         */
    $num = 0;
    $f['A']='№';
    $f['B']='время';
    $f['C']='откуда';
    $f['D']='куда';
    $f['E']='отправил';
    $f['F']='принял';
    $f['G']='доставил';
    $f['H']='состояние';
    $f['I']='комментарий';
    foreach( $items as $item ){
        $orders = explode("\n",$item['orders']);
        foreach( $orders as $order ){
            $order = explode(":",$order);
            $d = [];
            $d['A']=$order[0];
            $d['B']=$item['created'];
            $d['C']=$item['group'];
            $d['D']=$item['laboratory'];
            $d['E']=$item['group'];
            $d['F']=$item['laboratory'];
            $d['G']=$item['curier'];
            $d['H']=$list_vars['status_deliv'][$item['status_deliv']];
            $d['I']=$item['note'];
            $data[]=$d;
        }
    }
//    return $items;
    return $data;
}
function mldr_xml_data_(){
    $count = 100000;
//    $count = 10;
    $offset = 0;
    
    $args = [
        'numberposts' => $count,
        'offset'    => $offset,
//        'category'    => 0,
//        'orderby'     => 'ID',
//        'order'       => 'DESC',
//        'include'     => array(),
//        'exclude'     => array(),
//        'meta_key'    => '',
//        'meta_value'  =>'',
        'post_type'   => 'good_owner',
    //    'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
        'suppress_filters' => false,
        'post_status' => ['publish'],
//        'post_status' => ['publish','private'],
    ];
    $query = new WP_Query( $args );
    $count_ =  $query->found_posts;
    $data = [];
    if($count_>0){
        $posts = get_posts( $args );
        
        $nm = 'ln_';
        $state = [];
        $state['0'] = 'Проблема не решена';
        $state['1'] = 'Проблема решена';
        $num = 0;
        foreach( $posts as $post ){
            $d = [];
//        foreach( $users as $user ){
            setup_postdata($post);

        $date = date( "d.m.y", strtotime( $post->post_date ) );
        
        $adress = get_post_meta($post->ID,$nm.'adress',1);
        $namebc =  get_post_meta($post->ID,$nm.'namebc',1);
//        if($namebc)$adress.=' / '.$namebc;

        $fio = get_post_meta($post->ID,$nm.'fio',1);
        $position = get_post_meta($post->ID,$nm.'position',1);
//        if($position)$fio.=' / '.$position;

        $phone = get_post_meta($post->ID,$nm.'phone',1);
        $email = get_post_meta($post->ID,$nm.'email',1);
//        $phone.=' / '.$email;
        
        $content=get_the_content(null, false, $post->ID);

//        $state_ = get_post_meta($post->ID,$nm.'state',1);
//        $state_f = $state[$state_];
//        $author = get_the_author();
        
//        $author = get_the_author_posts_link();
//        $author = get_the_author_posts_link_company();
//        $author.=' / '.$state_f;
        $author = get_user_meta($post->post_author,'org_name',1);
        
        $comments = '<a href="_l_" target="_blank" >Комментарии (_cou_)</a>';
        $rep=[];
        $rep['_l_']=$post->guid;
        $rep['_cou_']=get_comments_number($post->ID);
        $comments = strtr($comments,$rep);
        
        $d['A']=$count_-$num;$num++;
        $d['B']=$date;
        $d['C']=$adress;
        $d['D']=$namebc;
        $d['E']=$fio;
        $d['F']=$position;
        $d['G']=$phone;
        $d['H']=$email;
        $d['I']=$author;
        $d['J']=$content;
        $data[]=$d;
        }
    }
    return $data;
}
//action_get_file_delivery_report
function get_file_delivery_report($file_name=false,$isdownload = false){
    if($file_name === false){
        $file_name = 'delivery_report';
        $file_name .= '_'.date('Y-m-d').'.xls';
    }
    $tab_name = 'Отчёт доставки'; // max 31 characters
//    https://myrusakov.ru/php-xls.html
//    https://github.com/PHPOffice/PHPExcel
//    https://it-blog.club/articles/php/phpexcel-izmenit-shirinu-stolbtsa-kolonki/
    
    if($isdownload){
        // We'll be outputting an excel file
        header('Content-type: application/vnd.ms-excel');

        // It will be called file.xls
//        header('Content-Disposition: attachment; filename="file.xls"');
        header('Content-Disposition: attachment; filename="'.$file_name.'"');
    }
    $data = mldr_xml_data();
    if(!count($data))return false;
	// action...
//    echo 'ok get_finfo_all'; // CHOP_DIR
  require_once DSHOP_EXT_ML_DIR.'phpexcel/PHPExcel.php'; // Подключаем библиотеку PHPExcel
  $phpexcel = new PHPExcel(); // Создаём объект PHPExcel

  /* Каждый раз делаем активной 1-ю страницу и получаем её, потом записываем в неё данные */
  $page = $phpexcel->setActiveSheetIndex(0); // Делаем активной первую страницу и получаем её

//  PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
//  $phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
//  $page->getColumnDimension('A')->setWidth(10);
//    $phpexcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
//    $phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(60);
  
    $fields = mldr_xml_initfields();
    $row = 1;
    $fs  = false;
    $fe  = false;
    foreach ($fields as $field => $name) {
        if(!$fs)$fs = $field;
        $fe = $field;
        $page->getColumnDimension($field)->setAutoSize(true);
        $page->setCellValue($field.$row, $name);
    }
    $page->getStyle($fs.$row.":".$fe.$row)->getFont()->setBold( true );
//    $count_ = count($data);
//    $page->getStyle("A1:A".($count_+1))->getFont()->setBold( true );
//    $page->getStyle("B1:B".($count_+1))->getFont()->setBold( true );
    
        $row++;
    foreach ($data as $drow => $d) {
        foreach ($fields as $f => $n) {
            $page->setCellValue($f.$row, $d[$f]);
        }
        $row++;
    }
    
  $page->setTitle($tab_name); // Ставим заголовок на странице
  /* Начинаем готовиться к записи информации в xlsx-файл */
//  $objWriter = PHPExcel_IOFactory::createWriter($phpexcel, 'Excel2007');
  $objWriter = PHPExcel_IOFactory::createWriter($phpexcel, 'Excel5');
  /* Записываем в файл */
//  $objWriter->save("test.xlsx");
  
    // Write file to the browser
    $file_name = dirname(__FILE__) . '/' . $file_name;
    if($isdownload){
        $objWriter->save('php://output');
    }else{
        $objWriter->save($file_name);
    }
    return $file_name;
}

