<?php

/* 
 * wh_report-report-download.php
 * main template
 */

//echo 'wh_write_off-bills';
//add_log($this->data);

//$tabl = build_items_tabl($data,$rclass);
//$row = '<div class="col-12 ">'.$row.$tabl.'</div>';
//$row = '<div class="row m-2 border border-primary mb-2">'.$row.'</div>';
//echo $row ;

//        $_log(strtr(__METHOD__,['::'=>' :: ']).'; <br/>main_tab '.$this->main_tab.'; tab '.$this->tab.';'
//                . ' action '.$this->action.'; mode '.$this->mode.';');
global $xls_page;
$row = 1;

if(!$this->datas)$this->datas=[$this->data];
$tks='ABCDEFGHIJKLMNOPQRSTUVWXWZ';
$tks = str_split($tks);
    
// =========================

//        $_log=(strtr(__METHOD__,['::'=>' :: ']).';'
//                . ' main_tab '.$this->main_tab.'; tab '.$this->tab.';'
//                . ' main_act '.$this->main_act.'; action '.$this->action.';'
//                . ' mode '.$this->mode.';');
//
//    $xls_page->mergeCells($tks[0].$row.":".$tks[10].$row);
////    $xls_page->setCellValue($tks[0].$row, $this->tab_title);
//    $xls_page->setCellValue($tks[0].$row, $_log);
//    $row++;
//    $row++;
    
// =========================
$ltk=0;
$xls_page->getColumnDimension($tks[0])->setAutoSize(true);
//    $xls_page->getColumnDimension('A')->setAutoSize(true);
//$xls_page->setCellValue("A".$row, '№');

foreach($this->titles_public as $tk=>$title_public ){
    $ltk=1+$tk;
    $xls_page->getColumnDimension($tks[$ltk])->setAutoSize(true);
//    $xls_page->setCellValue($tks[$ltk].$row, $title_public);
    
}

//$xls_page->getStyle($tks[0].$row.":".$tks[$ltk].$row)->getFont()->setBold( true );
// =========================

foreach($this->datas as $dk=>$dv ){
    $row++;
    $this->data = $dv;
    if(isset($this->tab_titles[$dk]))
        $this->tab_title = $this->tab_titles[$dk];
    
    $xls_page->mergeCells($tks[0].$row.":".$tks[$ltk].$row);
    $xls_page->setCellValue($tks[0].$row, $this->tab_title);
//    $xls_page->setCellValue($tks[0].$row, $this->tab_title.' -- '.$_log);
    $row++;
    $xls_page->setCellValue($tks[0].$row, 'count: '.count($this->data));
    $row++;
    $row++;

    // =========================
    $ltk=0;
    $xls_page->getColumnDimension($tks[0])->setAutoSize(true);
    //    $xls_page->getColumnDimension('A')->setAutoSize(true);
    $xls_page->setCellValue("A".$row, '№');

    foreach($this->titles_public as $tk=>$title_public ){
        $ltk=1+$tk;
//        $xls_page->getColumnDimension($tks[$ltk])->setAutoSize(true);
        $xls_page->setCellValue($tks[$ltk].$row, $title_public);

    }

    $xls_page->getStyle($tks[0].$row.":".$tks[$ltk].$row)->getFont()->setBold( true );
    $row++;
    // =========================
    
$num = 0;
$rclass = [];
$data = [];
$tab = $this->tab;
if(count($this->data)){
foreach($this->data as $rnum=>$it ){
    $ltk=0;
    $xls_page->setCellValue($tks[$ltk].$row, $num+1);
      
////    $at = [];
////    $at['class'] = 'btn btn-primary text-white mb-1';
////    $at['target'] = '_blank';
////    $at['href'] = get_the_permalink( $page_curier_blank ).'?cbid='.$it['id']; // curier blank id
//
//    $ate = [];
//    $ate['class'] = 'btn btn-success text-white mb-1';
////      $ate['target'] = '_blank';
//    $tab = '';
//    if($this->tab)$tab = 'tab='.$this->tab.'&';
//    $ate['href'] = get_the_permalink(get_the_ID() ) . '?pg=' . $this->pager . '&tab=' . $this->tab.'&id='.$it['id']; // curier blank id
//    $ate['href'] = get_the_permalink(get_the_ID() ) . '?'.$tab.'act=edit&id='.$it['id'].'&return='.urlencode(($this->router )); // curier blank id
//    $ate2 = [];
//    $ate2['class'] = 'btn btn-warning text-white mb-1';
//    $ate2['href'] = get_the_permalink(get_the_ID() ) . '?'.$tab.'act=delete&id='.$it['id'].'&return='.urlencode(($this->router )); // curier blank id
//    $ate3 = [];
//    $ate3['class'] = 'btn btn-primary text-white mb-1 mt-1';
//    $ate3['href'] = get_the_permalink(get_the_ID() ) . '?tab=items&act=writeoffapprove&id='.$it['id'].'&return='.urlencode(($this->router )); // curier blank id
//    
////        add_log($it);
      $item = [];
////      $item[]=$it['id'];
////      $item[]=$it['company'];
    foreach ($this->show_public as $tk=>$field) {
        $ltk=1+$tk;
        $xls_page->setCellValue($tks[$ltk].$row, $it[$field]);
          
//        $item[]=$it[$field];
        
      }
////        $item[]=$ht->f('a','Подробности',$ate3);
//        
////      $item[]=date('Y-m-d H:i',strtotime($it['created'] )); // the_time();
////      $item[]=$it['curier'];
////      $item[]=$it['laboratory'] . '<br/>';
////      $item[]=$it['group'];
////      $item[]=count(explode("\n",$it['orders']));
//      $tools = ''
////              .$ht->f('a','Бланк',$at)
//              .$ht->f('a','Изменить',$ate)
//              .$ht->f('a','Удалить',$ate2)
////              .'</div><div class="col-1"></div><div class="col-11">' . $list_vars['status_deliv'][$it['status_deliv']]
//              ;
////      $item[]= ['val'=>$tools,'class'=>'col-12 text-right'];
//      
////        $item[]=$dso_query_nr;
////        $item[]=$dso_total_;
////        $item[]=$dso_dbonus_val;
////        $item[]=$dso_dbonus_state?'Выплачен':'Не выплачен';
////        if($showD)
////        $item[]=$dso_duid;
//      
////      $rclass[$rnum]='text-white bg-danger';
////      if(($tab == 1)&&$it['status_deliv']==2)$rclass[$rnum]='text-white bg-danger';
////      if(($tab == 0 || $tab == 2)&&$it['status_deliv']==3)$rclass[$rnum]='text-white bg-danger';
//      $data[]=$item;
      $num++;
    $row++;
}

if(!function_exists('build_items_tabl__wh_measurement')){
function build_items_tabl__wh_measurement($data,$rclass,$titles=[]){
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
$hitems = $titles;
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
$cclass[0] = 1;
$cclass[] = 1;
$cclass[] = 1;
$cclass[] = 1;
$cclass[] = 1;
$cclass[] = 1;
$cclass[] = 1;
$cclass[] = 1;
$cclass[] = 1;
$cclass[] = 1;
$cclass[] = 1;
$cclass[] = 1;
$cclass[] = 1;
$cclass[] = 1;
$cclass[] = 1;
$cclass[] = 1;
//$cclass[] = 3;
//$cclass[] = 2;
//$cclass[] = 2;
//$cclass[] = 2;
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
}
//$table = build_items_tabl__wh_measurement($data,$rclass,$this->titles_public);
}
else {
    $table = 'Склад пуст.';
    
    $xls_page->mergeCells($tks[0].$row.":".$tks[$ltk].$row);
    $xls_page->setCellValue($tks[0].$row, $table);

    // =========================
}
    $row++;
if(1) continue;
    
    $tab = '';
    if($this->tab)$tab = '&tab=' . $this->tab;
    $limit = $this->pager_by;
    $page = $this->pager+1;
//    $limit = 2;
    $fcou = $this->items_count;
    $pagination = [];
//    add_log($limit);
//    add_log($fcou);
    $pages = ceil($fcou/$limit);
    for($p=1;$p<=$pages;$p++){
        $atli = ['class'=>'page-item '.($p==$page?'disabled':'')];
        $ata = ['class'=>'page-link '.($p==$page?'bg-primary text-white':''),
            'href'=>get_the_permalink( get_the_ID() ) . '?pg=' . $p . $tab];
        $pagination[]=$ht->f('li',$ht->f('a',$p,$ata),$atli);
    }
    if(count($pagination)==1)
        $pagination = [];
?>
    <div class="row m-0">
        <div class="col-12">
            <?php
                echo $this->tab_title;

            ?>
        </div>
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
<?php
}
    $xls_page->getStyle("A1:A".($row))->getFont()->setBold( true );
//    <div id="scene"></div>


    