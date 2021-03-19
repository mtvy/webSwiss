<?php

/* 
 * download_xml_wh_report.php
 */


add_action('init','action_get_wh_report_init');// ? seconds inits
function action_get_wh_report_init( $post_id =  0 ){
	// action...
//    add_log('action_get_wh_report_init ');

    if(filter_input(INPUT_POST, 'action') == 'get_wh_report'){
        action_get_wh_report();
    }

    if(filter_input(INPUT_GET, 'action') == 'get_wh_report'){
        action_get_wh_report();
    }
}

function action_get_wh_report(){
    $WhReport_activate = false;
    $located = '';
    $template_name = 'template-parts/dbconst/wh_report.php';
    if ( file_exists( STYLESHEETPATH . '/' . $template_name ) ) {
        $located = STYLESHEETPATH . '/' . $template_name;
    } elseif ( file_exists( TEMPLATEPATH . '/' . $template_name ) ) {
        $located = TEMPLATEPATH . '/' . $template_name;
    }
    if($located) include_once $located;
    
    
    $file_name = 'wh_report';
    $tab_name = 'Отчёт'; // max 31 characters
    header('Content-type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="'.$file_name.'_'.date('Y-m-d').'.xls"');
    require_once MLWAREHOUSE_DIR.'phpexcel/PHPExcel.php'; // Подключаем библиотеку PHPExcel
    $phpexcel = new PHPExcel(); // Создаём объект PHPExcel
    global $xls_page;
    $xls_page = $phpexcel->setActiveSheetIndex(0); // Делаем активной первую страницу и получаем её
    
    $tab = 'main';
    $DBCWhReport = new DBCWhReport($tab,'download','normal');
    
    if(0){
    $xls_page->getColumnDimension('A')->setAutoSize(true);
    $xls_page->getColumnDimension('B')->setAutoSize(true);
    $xls_page->getColumnDimension('C')->setAutoSize(true);
    $xls_page->getColumnDimension('D')->setAutoSize(true);
    $xls_page->getColumnDimension('E')->setAutoSize(true);
    $xls_page->getColumnDimension('F')->setAutoSize(true);
    $xls_page->getColumnDimension('G')->setAutoSize(true);
    $xls_page->getColumnDimension('H')->setAutoSize(true);
    $xls_page->getColumnDimension('I')->setAutoSize(true);
    $xls_page->getColumnDimension('J')->setAutoSize(true);
    $row = 1;
    
    $xls_page->setCellValue("A".$row, '№');
    $xls_page->setCellValue("B".$row, 'Дата');
    $xls_page->setCellValue("C".$row, 'Адрес');
    $xls_page->setCellValue("D".$row, 'Наименование БЦ');
    $xls_page->setCellValue("E".$row, 'ФИО представителя');
    $xls_page->setCellValue("F".$row, 'Должность');
    $xls_page->setCellValue("G".$row, 'Телефон ');
    $xls_page->setCellValue("H".$row, 'E-mail');
    $xls_page->setCellValue("I".$row, 'Кто добавил');
//    $xls_page->setCellValue("J".$row, 'Статус');
    $xls_page->setCellValue("J".$row, 'Описание ситуации');
    $row++;
    
    $xls_page->getStyle("A1:L1")->getFont()->setBold( true );
    $count_ =  $query->found_posts;
        
    $xls_page->getStyle("A1:A".($count_))->getFont()->setBold( true );
    $xls_page->getStyle("B1:B".($count_))->getFont()->setBold( true );
    
            $xls_page->setCellValue("A".$row, $count_-$num);$num++;
//            $xls_page->setCellValue("A".$row, $post->ID);
            $xls_page->setCellValue("B".$row, $date);
            $xls_page->setCellValue("C".$row, $adress);
            $xls_page->setCellValue("D".$row, $namebc);
            $xls_page->setCellValue("E".$row, $fio);
            $xls_page->setCellValue("F".$row, $position);
            $xls_page->setCellValue("G".$row, $phone);
            $xls_page->setCellValue("H".$row, $email);
            $xls_page->setCellValue("I".$row, $author);
//            $xls_page->setCellValue("J".$row, $state_f);
            $xls_page->setCellValue("J".$row, $content);
            
//            $xls_page->setCellValue("E".$row, $fio);
//            $xls_page->setCellValue("F".$row, $phone);
//            $xls_page->setCellValue("G".$row, $email);
            $row++;
    }
  
  
  
//  $xls_page->setTitle("Test"); // Ставим заголовок "Test" на странице
    $xls_page->setTitle($tab_name); // Ставим заголовок "Test" на странице
  /* Начинаем готовиться к записи информации в xlsx-файл */
//  $objWriter = PHPExcel_IOFactory::createWriter($phpexcel, 'Excel2007');
    $objWriter = PHPExcel_IOFactory::createWriter($phpexcel, 'Excel5');
  /* Записываем в файл */
//  $objWriter->save("test.xlsx");

    // Write file to the browser
    $objWriter->save('php://output');
    exit();
}

function action_get_registry_good_owners(){
    add_log('action_get_registry_good_owners ');
    $file_name = 'good_owner';
    $tab_name = 'Добросовестные собственники'; // max 31 characters
//    https://myrusakov.ru/php-xls.html
//    https://github.com/PHPOffice/PHPExcel
//    https://it-blog.club/articles/php/phpexcel-izmenit-shirinu-stolbtsa-kolonki/
    
        // We'll be outputting an excel file
        header('Content-type: application/vnd.ms-excel');

        // It will be called file.xls
//        header('Content-Disposition: attachment; filename="file.xls"');
        header('Content-Disposition: attachment; filename="'.$file_name.'_'.date('Y-m-d').'.xls"');
        
//    ini_set("display_errors", "1");
//    ini_set("display_startup_errors", "1");
//    ini_set('error_reporting', E_ALL);
	// action...
//    echo 'ok get_finfo_all'; // CHOP_DIR
  require_once MLWAREHOUSE_DIR.'phpexcel/PHPExcel.php'; // Подключаем библиотеку PHPExcel
  $phpexcel = new PHPExcel(); // Создаём объект PHPExcel
//    echo '==============';
//        exit;
//        return;

  /* Каждый раз делаем активной 1-ю страницу и получаем её, потом записываем в неё данные */
  global $xls_page;
  $xls_page = $phpexcel->setActiveSheetIndex(0); // Делаем активной первую страницу и получаем её
  
//  $xls_page->setCellValue("A10", "1"); // Добавляем в ячейку A1 слово "Hello"
//  $xls_page->setCellValue("A1", "Hello"); // Добавляем в ячейку A1 слово "Hello"
//  $xls_page->setCellValue("A2", "World!"); // Добавляем в ячейку A2 слово "World!"
//  $xls_page->setCellValue("B1", "MyRusakov.ru"); // Добавляем в ячейку B1 слово "MyRusakov.ru"

//  PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
//  $phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
//  $xls_page->getColumnDimension('A')->setWidth(10);
//    $phpexcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
//    $phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(60);
    $xls_page->getColumnDimension('A')->setAutoSize(true);
    $xls_page->getColumnDimension('B')->setAutoSize(true);
    $xls_page->getColumnDimension('C')->setAutoSize(true);
    $xls_page->getColumnDimension('D')->setAutoSize(true);
    $xls_page->getColumnDimension('E')->setAutoSize(true);
    $xls_page->getColumnDimension('F')->setAutoSize(true);
    $xls_page->getColumnDimension('G')->setAutoSize(true);
    $xls_page->getColumnDimension('H')->setAutoSize(true);
    $xls_page->getColumnDimension('I')->setAutoSize(true);
    $xls_page->getColumnDimension('J')->setAutoSize(true);
//    $xls_page->getColumnDimension('K')->setAutoSize(true);
//    $xls_page->getColumnDimension('B')->setWidth(60);
//  $xls_page->setCellValue("A10", "2"); // Добавляем в ячейку A1 слово "Hello"
  $row = 1;
            
	$new_columns = array(
            'fname'      => 'Имя',
            'sname'      => 'Отчество',
            'lname'      => 'Фамилия',
            'birthday'      => 'День рождения',
            'comment'      => 'Комментарий',
        );
//            $xls_page->setCellValue("A".$row, 'Фамилия');
//            $xls_page->setCellValue("B".$row, 'Имя');
//            $xls_page->setCellValue("C".$row, 'Отчество');
//            $xls_page->setCellValue("D".$row, 'День рождения');
//            $xls_page->setCellValue("E".$row, 'Комментарий');
//            $row++;
    
//Список участников

//№
//Дата
//Название компании
//Сайт
//ФИО представителя / Должность
//Телефон
//E-mail
    
//    $xls_page->setCellValue("A".$row, 'Id');
//    $xls_page->setCellValue("B".$row, 'Дата');
//    $xls_page->setCellValue("C".$row, 'ФИО');
//    $xls_page->setCellValue("D".$row, 'Должность в компании');
//    $xls_page->setCellValue("E".$row, 'Дата рождения');
//    $xls_page->setCellValue("F".$row, 'Телефон');
//    $xls_page->setCellValue("G".$row, 'E-mail');
//    $xls_page->setCellValue("H".$row, 'Кто добавил');
//    $xls_page->setCellValue("I".$row, 'Статус');
//    $xls_page->setCellValue("J".$row, 'Описание ситуации');
    
    $xls_page->setCellValue("A".$row, '№');
    $xls_page->setCellValue("B".$row, 'Дата');
    $xls_page->setCellValue("C".$row, 'Адрес');
    $xls_page->setCellValue("D".$row, 'Наименование БЦ');
    $xls_page->setCellValue("E".$row, 'ФИО представителя');
    $xls_page->setCellValue("F".$row, 'Должность');
    $xls_page->setCellValue("G".$row, 'Телефон ');
    $xls_page->setCellValue("H".$row, 'E-mail');
    $xls_page->setCellValue("I".$row, 'Кто добавил');
//    $xls_page->setCellValue("J".$row, 'Статус');
    $xls_page->setCellValue("J".$row, 'Описание ситуации');
    
//    $xls_page->setCellValue("E".$row, 'ФИО представителя / Должность');
//    $xls_page->setCellValue("F".$row, 'Телефон');
//    $xls_page->setCellValue("G".$row, 'E-mail');
    $row++;
    
    $xls_page->getStyle("A1:L1")->getFont()->setBold( true );
    
    $from = "A1"; // or any value
    $to = "B5"; // or any value
//    $objPHPExcel->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold( true );
    $cell_name = "A1";
//    $objPHPExcel->getActiveSheet()->getStyle( $cell_name )->getFont()->setBold( true );
  
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
    if($count_>0){
        $posts = get_posts( $args );
//        foreach( $posts as $post ){
        /**/
        
//    $posts_count = $count_;
//    $posts_count = count_users();
//    $num = $posts_count['avail_roles']['administrator'];
//    if(isset($posts_count['avail_roles']['contributor']))
//        $num += $posts_count['avail_roles']['contributor'];
    
//    $posts_count = $posts_count['total_users'];
//    $users_count = $num;
        
//    $xls_page->getStyle("A1:A".($num+1))->getFont()->setBold( true );
//    $xls_page->getStyle("B1:B".($num+1))->getFont()->setBold( true );
        
    $xls_page->getStyle("A1:A".($count_))->getFont()->setBold( true );
    $xls_page->getStyle("B1:B".($count_))->getFont()->setBold( true );
    
//$args = array(
////	'blog_id'      => $GLOBALS['blog_id'],
////	'role'         => '',
//	'role__in'     => array('contributor','author','editor','administrator'),
//	'role__not_in' => array('subscriber'),
////	'meta_key'     => '',
////	'meta_value'   => '',
////	'meta_compare' => '',
////	'meta_query'   => array(),
////	'include'      => array(),
////	'exclude'      => array(),
////	'orderby'      => 'login',
////	'order'        => 'ASC',
//	'orderby'      => 'ID',
//	'order'        => 'DESC',
//	'offset'       => $offset,
////	'offset'       => '',
////	'search'       => '',
////	'search_columns' => array(),
//	'number'       => $count,
////	'paged'        => 1,
////	'count_total'  => false,
////	'fields'       => 'all',
////	'who'          => '',
////	'has_published_posts' => null,
////	'date_query'   => array() // смотрите WP_Date_Query
//);
//if($user_id)$args['include']=[$user_id];
//$users = get_users( $args );
//$users = get_users( );
//if(count($users)>0){
//if(count($posts)>0){

//    $num=0;
//    $num=$offset;
//    $posts_count = count_users();
////    echo '<!-- '.print_r( count_users(),1 ).' -->';
//    $num = $posts_count['total_users'];
//    $num = $posts_count['avail_roles']['administrator'];
//    if(isset($posts_count['avail_roles']['contributor']))
//        $num += $posts_count['avail_roles']['contributor'];
//    $num-=$offset;
    
        $nm = 'ln_';
        $state = [];
        $state['0'] = 'Проблема не решена';
        $state['1'] = 'Проблема решена';
        $num = 0;
        foreach( $posts as $post ){
//        foreach( $users as $user ){
            setup_postdata($post);
//            $fname = get_post_meta($post->ID,'org_i_fname',1);
//            $lname = get_post_meta($post->ID,'org_i_lname',1);
//            $sname = get_post_meta($post->ID,'org_i_sname',1);
//            $bdate = get_post_meta($post->ID,'org_i_bdate',1);
////            $comment = get_the_content();
//            $comment = $post->post_content;
            
//            $xls_page->setCellValue("A".$row, $lname);
//            $xls_page->setCellValue("B".$row, $fname);
//            $xls_page->setCellValue("C".$row, $sname);
//            $xls_page->setCellValue("D".$row, $bdate);
//            $xls_page->setCellValue("E".$row, $comment);
            


        /*
    $date = date( "d.m.y", strtotime( $user->get('user_registered') ) );

    $company = get_user_meta($user->ID,'org_name',1);
    
    $site = strtr($user->get('user_url'),['http://'=>'']);
    
    $fio = get_user_meta($user->ID,'last_name',1);
    $fio .= ' '.get_user_meta($user->ID,'first_name',1);
    $fio .= ' '.get_user_meta($user->ID,'second_name',1);
    $position = '';
    $_position = get_user_meta($user->ID,'org_position',1);
//    if($position)$fio.=' / '.$position;
    if($_position)$position=$_position;
    $_position = get_user_meta($user->ID,'position',1);
//    if($position)$fio.=' / '.$position;
    if($_position)$position=$_position;
    
    $phone = get_user_meta($user->ID,'phone',1);
    
    $email = $user->get('user_email');
//№
//Дата
//Название компании
//Сайт
//ФИО представителя / Должность
//Телефон
//E-mail
//
/**/
    

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
    
//    $xls_page->setCellValue("A".$row, 'Id');
//    $xls_page->setCellValue("B".$row, 'Дата');
//    $xls_page->setCellValue("C".$row, 'Адрес');
//    $xls_page->setCellValue("D".$row, 'Наименование БЦ');
//    $xls_page->setCellValue("E".$row, 'ФИО представителя');
//    $xls_page->setCellValue("F".$row, 'Должность');
//    $xls_page->setCellValue("G".$row, 'Телефон ');
//    $xls_page->setCellValue("H".$row, 'E-mail');
//    $xls_page->setCellValue("I".$row, 'Кто добавил');
//    $xls_page->setCellValue("J".$row, 'Статус');
//    $xls_page->setCellValue("K".$row, 'Описание ситуации');
    
//            $xls_page->setCellValue("A".$row, $num--);
            $xls_page->setCellValue("A".$row, $count_-$num);$num++;
//            $xls_page->setCellValue("A".$row, $post->ID);
            $xls_page->setCellValue("B".$row, $date);
            $xls_page->setCellValue("C".$row, $adress);
            $xls_page->setCellValue("D".$row, $namebc);
            $xls_page->setCellValue("E".$row, $fio);
            $xls_page->setCellValue("F".$row, $position);
            $xls_page->setCellValue("G".$row, $phone);
            $xls_page->setCellValue("H".$row, $email);
            $xls_page->setCellValue("I".$row, $author);
//            $xls_page->setCellValue("J".$row, $state_f);
            $xls_page->setCellValue("J".$row, $content);
            
//            $xls_page->setCellValue("E".$row, $fio);
//            $xls_page->setCellValue("F".$row, $phone);
//            $xls_page->setCellValue("G".$row, $email);
            $row++;
            if(0){
                $args = array(
                    'post_parent'    => $post->ID,
                    'post_type'      => 'attachment',
//                    'post_mime_type' => $type,
                    'posts_per_page' => -1,
                    'orderby'        => 'menu_order',
                    'order'          => 'ASC',
                );
                $attachments = get_posts( array(
                    'post_type' => 'attachment',
                    'posts_per_page' => -1,
                    'post_parent' => $post->ID,
//                    'exclude'     => get_post_thumbnail_id()
                ) );
                if ( $attachments ) {
                    foreach ( $attachments as $attachment ) {
//                        $class = "post-attachment mime-" . sanitize_title( $attachment->post_mime_type );
//                        $thumbimg = wp_get_attachment_link( $attachment->ID,
//                                'thumbnail-size', true );
//                        echo '<li class="' . $class . ' data-design-thumbnail">'
//                                . $thumbimg . '</li>';
                        $a_id=$attachment->ID;
//                        the_attachment_link( $a_id, false, false, false);
            $url_attach = wp_get_attachment_link( $a_id, 'thumbnail', false, true,'скачать');
                    }

                }
            }
        }
//        foreach ($files as $key => $a_id) {
//            $post = get_post( $a_id );
//            echo '<tr><th><label for="">'.$post->post_date.'</label></th><td>';
//    //        the_attachment_link( $a_id, false, false, false);
//            echo wp_get_attachment_link( $a_id, 'thumbnail', false, true,'скачать');
//    //        echo '</td><td>';
//    //        echo wp_get_attachment_url($a_id);
//            echo '</td></tr>';
//        }
    }
  
  
  
//  $xls_page->setTitle("Test"); // Ставим заголовок "Test" на странице
  $xls_page->setTitle($tab_name); // Ставим заголовок "Test" на странице
  /* Начинаем готовиться к записи информации в xlsx-файл */
//  $objWriter = PHPExcel_IOFactory::createWriter($phpexcel, 'Excel2007');
  $objWriter = PHPExcel_IOFactory::createWriter($phpexcel, 'Excel5');
  /* Записываем в файл */
//  $objWriter->save("test.xlsx");
  
    // Write file to the browser
    $objWriter->save('php://output');
//    exit();
}

