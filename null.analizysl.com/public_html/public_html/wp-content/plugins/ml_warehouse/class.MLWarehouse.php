<?php

/* 
 * class.MLWarehouse.php
 */

include_once 'wh/trait.WHMaterial.php';
include_once 'wh/trait.WHWeybill.php';
include_once 'wh/trait.WHWriteOff.php';

class  MLWarehouse
{
    use
    WHMaterial,
    WHWriteOff,
    WHWeybill
    ;
    public $mess = [];
    public function init(){
        
    }
    public function ajax_find_material_names(){
        global $wpdb,$ht;
        
        $name = filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);
        $categ = filter_input(INPUT_POST,'categ',FILTER_SANITIZE_STRING);
        $code = filter_input(INPUT_POST,'code',FILTER_SANITIZE_STRING);
        $manuf = filter_input(INPUT_POST,'manuf',FILTER_SANITIZE_STRING);
        
        $w = [];
        $this->where_in('a','title',$name,$w,1);
        $this->where_in('b','title',$categ,$w,1);
        $this->where_in('a','catalog_num',$code,$w,1);
        $this->where_in('c','title',$manuf,$w,1);
        $this->where_in('a','title',$name,$w);
        $this->where_in('b','title',$categ,$w);
        $this->where_in('a','catalog_num',$code,$w);
        $this->where_in('c','title',$manuf,$w);
        
        if(!count($w)) return ['mess'=>['no result']];
        $names = [];
        $mess = [];
        $q = "select a.`title` as 'title', a.`id` as 'id', a.`catalog_num` as 'catalog', b.`title` as  'category', c.`title` as 'factory'"
                . " from `".$wpdb->prefix.'wsd_dbc_'.'wh_material_name'."` a"
                . " left join `".$wpdb->prefix.'wsd_dbc_'.'wh_material_type'."` b on b.id = a.`category_id`"
                . " left join `".$wpdb->prefix.'wsd_dbc_'.'wh_manufacturer'."` c on c.id = a.`factory_id`"
                . " where ".implode(' or ',$w);
        $catlist = $wpdb->get_col($q);
        $catlist2 = $wpdb->get_results($q,ARRAY_A);
//        if(!$catlist) return ['mess'=>['No result.']];
        if(!$catlist) $mess[] = ['No result.'];
//        foreach($catlist as $cl){
//            $names[] = '<option value="'.$cl.'">';
//        }
//            $str = explode(' ',$name);
//        $catlist = array_merge($catlist,$w,$str);
//        $catlist = array_merge([$ht->f('pre',print_r($catlist,1))],[$ht->f('pre',print_r($catlist2,1))],[$q]);
//        $mess[] = [$ht->f('pre',print_r($catlist,1))];
//        $mess[] = [$ht->f('pre',print_r($catlist2,1))];
//        $mess[] = [$q];
        return ['res'=>$catlist2,'mess'=>$mess];
    }
    public function ajax_find_material_items(){
        global $wpdb,$ht;
        
        $name = filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);
        $categ = filter_input(INPUT_POST,'categ',FILTER_SANITIZE_STRING);
        $code = filter_input(INPUT_POST,'code',FILTER_SANITIZE_STRING);
        $manuf = filter_input(INPUT_POST,'manuf',FILTER_SANITIZE_STRING);
        
        $w = [];
        $this->where_in('a','title',$name,$w,1);
        $this->where_in('b','title',$categ,$w,1);
        $this->where_in('a','catalog_num',$code,$w,1);
        $this->where_in('c','title',$manuf,$w,1);
        $this->where_in('a','title',$name,$w);
        $this->where_in('b','title',$categ,$w);
        $this->where_in('a','catalog_num',$code,$w);
        $this->where_in('c','title',$manuf,$w);
        
        if(!count($w)) return ['mess'=>['no result']];
        $names = [];
        $mess = [];
        $q = "select a.`title` as 'title', d.`id` as 'id', a.`catalog_num` as 'catalog',"
                . " b.`title` as  'category', c.`title` as 'factory'"
                . " from `".$wpdb->prefix.'wsd_dbc_'.'wh_material'."` d"
                . " left join `".$wpdb->prefix.'wsd_dbc_'.'wh_material_name'."` a on a.id = d.`name_id`"
                . " left join `".$wpdb->prefix.'wsd_dbc_'.'wh_material_type'."` b on b.id = a.`category_id`"
                . " left join `".$wpdb->prefix.'wsd_dbc_'.'wh_manufacturer'."` c on c.id = a.`factory_id`"
                . " where ".implode(' or ',$w);
        $catlist = $wpdb->get_col($q);
        $catlist2 = $wpdb->get_results($q,ARRAY_A);
//        if(!$catlist) return ['mess'=>['No result.']];
        if(!$catlist) $mess[] = ['No result.'];
//        foreach($catlist as $cl){
//            $names[] = '<option value="'.$cl.'">';
//        }
//            $str = explode(' ',$name);
//        $catlist = array_merge($catlist,$w,$str);
//        $catlist = array_merge([$ht->f('pre',print_r($catlist,1))],[$ht->f('pre',print_r($catlist2,1))],[$q]);
//        $mess[] = [$ht->f('pre',print_r($catlist,1))];
//        $mess[] = [$ht->f('pre',print_r($catlist2,1))];
//        $mess[] = [$q];
        return ['res'=>$catlist2,'mess'=>$mess];
    }
    
    public function where_in($tab='',$field='',$str='',&$w=[],$strong=false){
        if(strlen($tab))$tab.='.';
        if($strong){
            if(strlen(trim($str)))
                $w[]=" $tab`$field` = '$str' ";
        }else{
            $str = explode(' ',$str);
            foreach($str as $s){
                if(strlen(trim($s))==0)continue;
//                $w[]=" 0 /* $s */ ";
//                else
                $w[]=" $tab`$field` like '%$s%' ";
            }
        }
    }
    public function ajax_add_to_waybill(){
        global $wpdb,$ht;
        $w = [];
        $names = [];
        $mess = [];
        ob_start();
        
        if(!isset($_SESSION['waybill_items']))$_SESSION['waybill_items'] = [];
        $selo =  filter_input(INPUT_POST, 'waybill_items', FILTER_SANITIZE_NUMBER_INT,FILTER_REQUIRE_ARRAY); // select order
        if($selo){
            $_SESSION['waybill_items'] += $selo ;
        }
        $names = array_merge([],$_SESSION['waybill_items']);
        $this->build_weybill($mess);
        
//        $name = filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);
//        $categ = filter_input(INPUT_POST,'categ',FILTER_SANITIZE_STRING);
//        $code = filter_input(INPUT_POST,'code',FILTER_SANITIZE_STRING);
//        $manuf = filter_input(INPUT_POST,'manuf',FILTER_SANITIZE_STRING);
        
        $w = [];
//        $this->where_in('a','title',$name,$w,1);
//        $this->where_in('b','title',$categ,$w,1);
//        $this->where_in('a','catalog_num',$code,$w,1);
//        $this->where_in('c','title',$manuf,$w,1);
//        $this->where_in('a','title',$name,$w);
//        $this->where_in('b','title',$categ,$w);
//        $this->where_in('a','catalog_num',$code,$w);
//        $this->where_in('c','title',$manuf,$w);
        
        $w[]=" d.`id` in ('".implode("','",$_SESSION['waybill_items'])."') ";
        
        
        if(!count($w)) return ['mess'=>['no result']];
        $names = [];
        $mess = [];
//        $q = "select a.`title` as 'title', a.`id` as 'id', a.`catalog_num` as 'catalog', b.`title` as  'category', c.`title` as 'factory'"
//                . " from `".$wpdb->prefix.'wsd_dbc_'.'wh_material_name'."` a"
//                . " left join `".$wpdb->prefix.'wsd_dbc_'.'wh_material_type'."` b on b.id = a.`category_id`"
//                . " left join `".$wpdb->prefix.'wsd_dbc_'.'wh_manufacturer'."` c on c.id = a.`factory_id`"
//                . " where ".implode(' or ',$w);
        
        $q = "select a.`title` as 'title', d.`id` as 'id', a.`catalog_num` as 'catalog',"
                . " b.`title` as  'category', c.`title` as 'factory',"
                . " d.`stillage` as  'stillage', d.`board` as 'board',"
                . " concat(e.`index`, '-',d.`stillage`, '-', d.`board`) as 'number'"
                . " from `".$wpdb->prefix.'wsd_dbc_'.'wh_material'."` d"
                . " left join `".$wpdb->prefix.'wsd_dbc_'.'wh_material_name'."` a on a.id = d.`name_id`"
                . " left join `".$wpdb->prefix.'wsd_dbc_'.'wh_material_type'."` b on b.id = a.`category_id`"
                . " left join `".$wpdb->prefix.'wsd_dbc_'.'wh_manufacturer'."` c on c.id = a.`factory_id`"
                . " left join `".$wpdb->prefix.'wsd_dbc_'.'wh_house_group'."` e on e.id = d.`group_id`"
                . " where ".implode(' or ',$w);
//        $mess[] = $this->prepare_query($q);
        $catlist = $wpdb->get_col($q);
        $catlist2 = $wpdb->get_results($q,ARRAY_A);
//        if(!$catlist) return ['mess'=>['No result.']];
//        if(!$catlist) $mess[] = ['No result.'];
//        else{
//        }
            $suf = 'ий';
            if(count($catlist2)>0)$suf = 'ия';
            if(count($catlist2)>1)$suf = 'ии';
            if(count($catlist2)>4)$suf = 'ий';
            $mess[] = ['В очереди '.count($catlist2).' позиц'.$suf.'.'];
        
        if($wpdb->last_error == '') {
//            $id = $wpdb->insert_id;
//            if($id){
//                $mess[]=$id;
//            }
        }else{
            $mess[]=$wpdb->last_error;
        }
        $err = ob_get_clean();
        if($err){
//            add_log($err);
            $mess[]=$err;
        }
        
        return ['res'=>$catlist2,'mess'=>$mess];
    }
    public function ajax_create_weybill(){
        global $wpdb,$ht;
        $w = [];
        $names = [];
        $mess = [];
        ob_start();
        
        $id = $this->create_weybill($mess);
        if($this->add_mat_noerr){
            $this->create_weybill_items($mess);
        }
        
        if($wpdb->last_error == '') {
            $id = $wpdb->insert_id;
            if($id){
                $mess[]=$id;
            }
        }else{
            $mess[]=$wpdb->last_error;
        }
        $err = ob_get_clean();
        if($err){
//            add_log($err);
            $mess[]=$err;
        }
        
        return ['res'=>$names,'mess'=>$mess];
    }
    public function ajax_clear_weybill(){
        global $wpdb,$ht;
        $w = [];
        $names = [];
        $mess = [];
        ob_start();
        
        $act = filter_input(INPUT_POST, 'act', FILTER_SANITIZE_STRING);
        $conf = filter_input(INPUT_POST, 'conferm', FILTER_SANITIZE_STRING);
        if($act == 'clear_weybill' && $conf == 'ok'){
//            $selo=[];
            $_SESSION['waybill_items'] =[];
        }
            $mess[] = ['В очереди '.count([]).' позиций.'];
        
//        if($wpdb->last_error == '') {
//            $id = $wpdb->insert_id;
//            if($id){
//                $mess[]=$id;
//            }
//        }else{
//            $mess[]=$wpdb->last_error;
//        }
        $err = ob_get_clean();
        if($err){
//            add_log($err);
            $mess[]=$err;
        }
        
        return ['res'=>$names,'mess'=>$mess];
    }
    public function ajax_get_weybill_items(){
        global $wpdb,$ht;
        $w = [];
        $names = [];
        $mess = [];
        ob_start();
        if(!isset($_SESSION['waybill_items']))$_SESSION['waybill_items'] = [];
        
        $w = [];
        $w[]=" d.`id` in ('".implode("','",$_SESSION['waybill_items'])."') ";
        
        
        if(!count($w)) return ['mess'=>['no result']];
        
        $q = "select a.`title` as 'title', d.`id` as 'id', a.`catalog_num` as 'catalog',"
                . " b.`title` as  'category', c.`title` as 'factory',"
                . " d.`stillage` as  'stillage', d.`board` as 'board',"
                . " concat(e.`index`, '-',d.`stillage`, '-', d.`board`) as 'number'"
                . " from `".$wpdb->prefix.'wsd_dbc_'.'wh_material'."` d"
                . " left join `".$wpdb->prefix.'wsd_dbc_'.'wh_material_name'."` a on a.id = d.`name_id`"
                . " left join `".$wpdb->prefix.'wsd_dbc_'.'wh_material_type'."` b on b.id = a.`category_id`"
                . " left join `".$wpdb->prefix.'wsd_dbc_'.'wh_manufacturer'."` c on c.id = a.`factory_id`"
                . " left join `".$wpdb->prefix.'wsd_dbc_'.'wh_house_group'."` e on e.id = d.`group_id`"
                . " where ".implode(' or ',$w);
//        $mess[] = $this->prepare_query($q);
        $catlist = $wpdb->get_col($q);
        $catlist2 = $wpdb->get_results($q,ARRAY_A);
//        if(!$catlist) return ['mess'=>['No result.']];
//        if(!$catlist) $mess[] = ['No result.'];
//        else{
//        }
            $suf = 'ий';
            if(count($catlist2)>0)$suf = 'ия';
            if(count($catlist2)>1)$suf = 'ии';
            if(count($catlist2)>4)$suf = 'ий';
            $mess[] = ['В очереди '.count($catlist2).' позиц'.$suf.'.'];
        
        if($wpdb->last_error == '') {
//            $id = $wpdb->insert_id;
//            if($id){
//                $mess[]=$id;
//            }
        }else{
            $mess[]=$wpdb->last_error;
        }
        $err = ob_get_clean();
        if($err){
//            add_log($err);
            $mess[]=$err;
        }
        
        return ['res'=>$catlist2,'mess'=>$mess];
    }
    public function ajax_clear_weybill_item(){
        global $wpdb,$ht;
        $w = [];
        $names = [];
        $mess = [];
        ob_start();
        if(!isset($_SESSION['waybill_items']))$_SESSION['waybill_items'] = [];
        
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $act = filter_input(INPUT_POST, 'act', FILTER_SANITIZE_STRING);
        $conf = filter_input(INPUT_POST, 'conferm', FILTER_SANITIZE_STRING);
        if($act == 'clear_weybill_item' && $conf == 'ok'){
//            $selo=[];
            unset($_SESSION['waybill_items'][$id]);
        }
        $w = [];
        $w[]=" d.`id` in ('".implode("','",$_SESSION['waybill_items'])."') ";
        
        
        if(!count($w)) return ['mess'=>['no result']];
        
        $q = "select a.`title` as 'title', d.`id` as 'id', a.`catalog_num` as 'catalog',"
                . " b.`title` as  'category', c.`title` as 'factory',"
                . " d.`stillage` as  'stillage', d.`board` as 'board',"
                . " concat(e.`index`, '-',d.`stillage`, '-', d.`board`) as 'number'"
                . " from `".$wpdb->prefix.'wsd_dbc_'.'wh_material'."` d"
                . " left join `".$wpdb->prefix.'wsd_dbc_'.'wh_material_name'."` a on a.id = d.`name_id`"
                . " left join `".$wpdb->prefix.'wsd_dbc_'.'wh_material_type'."` b on b.id = a.`category_id`"
                . " left join `".$wpdb->prefix.'wsd_dbc_'.'wh_manufacturer'."` c on c.id = a.`factory_id`"
                . " left join `".$wpdb->prefix.'wsd_dbc_'.'wh_house_group'."` e on e.id = d.`group_id`"
                . " where ".implode(' or ',$w);
//        $mess[] = $this->prepare_query($q);
        $catlist = $wpdb->get_col($q);
        $catlist2 = $wpdb->get_results($q,ARRAY_A);
//        if(!$catlist) return ['mess'=>['No result.']];
//        if(!$catlist) $mess[] = ['No result.'];
//        else{
//        }
            $suf = 'ий';
            if(count($catlist2)>0)$suf = 'ия';
            if(count($catlist2)>1)$suf = 'ии';
            if(count($catlist2)>4)$suf = 'ий';
            $mess[] = ['В очереди '.count($catlist2).' позиц'.$suf.'.'];
        
        if($wpdb->last_error == '') {
//            $id = $wpdb->insert_id;
//            if($id){
//                $mess[]=$id;
//            }
        }else{
            $mess[]=$wpdb->last_error;
        }
        $err = ob_get_clean();
        if($err){
//            add_log($err);
            $mess[]=$err;
        }
        
        return ['res'=>$catlist2,'mess'=>$mess];
    }
    
    public function build_weybill(&$mess){
        
    }
    public $weybill_id = null;
    public function create_weybill(&$mess){
        global $wpdb,$ht;
        $data = $this->data_def('wh_weybill');
//        $this->data = $data;
        
//        if(!$data['name_id']){
//            $mess[] = 'Не указано название товара.';
//            $mess[] = '<div> Сбой добавления товара. </div>';
//            $this->add_mat_noerr = false;
////            return;
//        }
        
        
        $set = [];
        ob_start();

//        $set[] = "`delivery_date` = now()";
//        $set[] = "`expiry_date` = current_date()";
//        $set[] = "`open_expiry_date` = current_timestamp()";

//        $cd = current_time('Y-m-d H:i:s');
        $mid = current($_SESSION['waybill_items']);
        $q = "select `group_id` from `".$wpdb->prefix.'wsd_dbc_'.'wh_material'."` where id = '".$mid."'";
        $house_from = $wpdb->get_var($q);
        $house_to = filter_input(INPUT_POST,'house_id',FILTER_SANITIZE_NUMBER_INT);
        $cd = current_time('Y-m-d');
        $ct = current_time('H:i:s');
        
        $data['house_from'] = $house_from;
        $data['house_to'] = $house_to;
        $data['sender'] = get_current_user_id();
        
//        $set[] = "`house_from` = '$house_from'";
//        $set[] = "`house_to` = '$house_to'";
        $set[] = "`date` = '$cd'";
        $set[] = "`time` = '$ct'";
//        $set[] = "`open_expiry_date` = '$ct'";
        $key_extract = [];
        $key_extract[] = 'date';
        $key_extract[] = 'time';
        foreach ($data as $key => $value) {
            if(in_array($key,$key_extract))continue;
            $set[] = "`$key` = '".$value."'";
        }

        $q = "insert into `".$wpdb->prefix.'wsd_dbc_'.'wh_weybill'."` set ".implode(',',$set);
        if($this->add_mat_noerr)$wpdb->query($q);
//            $mess[] = $this->prepare_query($q);

        $id = false;
        if($wpdb->last_error == '') {
            $id = $wpdb->insert_id;
            $wpdb->weybill_id = $id;
        }else{
            $mess[] = $this->prepare_query($q);
            $this->add_mat_noerr = false;
        }
//        $id = 1;
//        $wpdb->weybill_id = $id;
//        $mess[] = $wpdb->weybill_id;

        $err = ob_get_clean();
        if($err)
            $mess[] = $err;
        if($wpdb->last_error !== '') {
            $error = true;
            $mess[] = $wpdb->last_error;
        }else{
            if($this->add_mat_noerr){
                $notice = '<div> Создана накладная № '.str_pad($id,20,'0',STR_PAD_LEFT).'. </div>';
                $mess[] = $notice;
            }
        }
        return $id ;
    }
    public function create_weybill_items(&$mess){
        global $wpdb,$ht;
        $id = $wpdb->weybill_id;
//        $mess[] = 'create_weybill_items';
//        $mess[] = $wpdb->weybill_id;
        if(!isset($_SESSION['waybill_items']))$_SESSION['waybill_items'] = [];
        if($id){
            $waybill_items = $_SESSION['waybill_items'];
            foreach($waybill_items as $mid){
                $this->create_weybill_item($mess,$mid);
            }
        }
    }
    public function create_weybill_item(&$mess,$mid=false){
//        $mess[] = $mid;
        global $wpdb,$ht;
        $data = $this->data_def('wh_weybill_item');
//        $this->data = $data;
        
//        if(!$data['name_id']){
//            $mess[] = 'Не указано название товара.';
//            $mess[] = '<div> Сбой добавления товара. </div>';
//            $this->add_mat_noerr = false;
////            return;
//        }
        
        
        $set = [];
        ob_start();
        $wbid = $wpdb->weybill_id;
        $q = "select `group_id` from `".$wpdb->prefix.'wsd_dbc_'.'wh_material'."` where id = '".$mid."'";
        $group_from = $wpdb->get_var($q);
        $group_to = filter_input(INPUT_POST,'group_id',FILTER_SANITIZE_NUMBER_INT);
        $data['wb_id'] = $wbid;
        $data['material_id'] = $mid;
        $data['group_from'] = $group_from;
        $data['group_to'] = $group_to;
        $data['group_to'] = 0;
        $data['status'] = 0;
        $data['comment'] = '';

        $ct = current_time('Y-m-d H:i:s');
        $set[] = "`created` = '$ct'";
        $key_extract = [];
        $key_extract[] = 'created';
        foreach ($data as $key => $value) {
            if(in_array($key,$key_extract))continue;
            $set[] = "`$key` = '".$value."'";
        }

        $q = "insert into `".$wpdb->prefix.'wsd_dbc_'.'wh_weybill_item'."` set ".implode(',',$set);
        if($this->add_mat_noerr)$wpdb->query($q);
//            $mess[] = $this->prepare_query($q);

        $id = false;
//        $wpdb->weybill_id = $id;
        if($wpdb->last_error == '') {
            $id = $wpdb->insert_id;
//            $wpdb->weybill_id = $id;
        }else{
            $mess[] = $this->prepare_query($q);
//            $this->add_mat_noerr = false;
        }

        $err = ob_get_clean();
        if($err)
            $mess[] = $err;
        if($wpdb->last_error !== '') {
            $error = true;
            $mess[] = $wpdb->last_error;
        }else{
//            if($this->add_mat_noerr){
//                $notice = '<div> Создана накладная № '.str_pad($id,20,'0').'. </div>';
//                $mess[] = $notice;
//            }
        }
//        return $id ;
    }
    public function data_def($tab='wh_material'){
        global $wpdb,$ht;
        $data = [];
        
        $this->dbconst_table = 'wh_material';
        $dbconst_schema = $wpdb->prefix . "wsd_dbconst_schema";
        $dbconst_tables = $wpdb->prefix . "wsd_dbconst_tables";
        $dbconst_fields = $wpdb->prefix . "wsd_dbconst_fields";
        $q= "select id from `$dbconst_tables` where `table` = '".'wh_material'."' ";
        $q= "select id from `$dbconst_tables` where `table` = '".$tab."' ";
        $tab_id = $wpdb->get_var($q);
//        add_log($this->dbconst_table);
//        add_log($tab_id);
        if($tab_id && $wpdb->get_var("SHOW TABLES LIKE '$dbconst_fields'") == $dbconst_fields) {
            $q= "select * from `$dbconst_fields` where `isprimary` != 1 and `tab_id`= $tab_id and `active` = 1 order by `weigh`";
            $fields = $wpdb->get_results($q,ARRAY_A);
            foreach($fields as $field){
                $k = $field['field'];
//                add_log('data_def '.$k);
//                add_log($this->form_type);
                $data[$k] = $field['def'];
//                if(in_array($k, $this->weight_fields))
//                    $data[$k] = $this->items_count+1;
                
//                if($this->form_type && in_array($this->form_type,['create','edit'])){
                    $filter_type = FILTER_SANITIZE_NUMBER_INT;
                    $def = 0;
                    $regxp = null;
                    if($field['filter'] == FILTER_SANITIZE_STRING){
                        $filter_type = FILTER_SANITIZE_STRING;
                        $def = '';
                    }
                    $def = $data[$k];
                    $filter_type = (int)$field['filter'];
                    $data[$k] = $ht->postget($k,$def,$filter_type,$regxp);
//                    add_log($k);
//                    add_log($data[$k]);
//                }else{
//                }
            }
        }
        return $data;
    }
    public $add_mat_noerr = true;
    public function ajax_add_material(){
        global $wpdb,$ht;
        
//        $name = filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);
//        $categ = filter_input(INPUT_POST,'categ',FILTER_SANITIZE_STRING);
//        $code = filter_input(INPUT_POST,'code',FILTER_SANITIZE_STRING);
//        $manuf = filter_input(INPUT_POST,'manuf',FILTER_SANITIZE_STRING);
        
        $w = [];
        $names = [];
        $mess = [];
        ob_start();
        
        $this->create_material($mess);
        
        if($wpdb->last_error == '') {
            $id = $wpdb->insert_id;
            if($id){
                $mess[]=$id;
            }
        }else{
            $mess[]=$wpdb->last_error;
        }
        $err = ob_get_clean();
        if($err){
//            add_log($err);
            $mess[]=$err;
        }
        
        return ['res'=>$names,'mess'=>$mess];
//        if(!count($w)) return ['mess'=>['no result']];
//        return ['res'=>$names,'mess'=>$mess];
    }
    public function create_material(&$mess){
        global $wpdb,$ht;
        $data = $this->data_def();
//        $this->data = $data;
        
        if(!$data['name_id']){
            $mess[] = 'Не указано название товара.';
            $mess[] = '<div> Сбой добавления товара. </div>';
            $this->add_mat_noerr = false;
//            return;
        }
        
        
        $set = [];
        ob_start();

//        $set[] = "`delivery_date` = now()";
//        $set[] = "`expiry_date` = current_date()";
//        $set[] = "`open_expiry_date` = current_timestamp()";

        $ct = current_time('Y-m-d H:i:s');
        $set[] = "`delivery_date` = '$ct'";
        $set[] = "`expiry_date` = '$ct'";
        $set[] = "`open_expiry_date` = '$ct'";
        $key_extract = [];
        $key_extract[] = 'delivery_date';
        $key_extract[] = 'expiry_date';
        $key_extract[] = 'open_expiry_date';
        foreach ($data as $key => $value) {
            if(in_array($key,$key_extract))continue;
            $set[] = "`$key` = '".$value."'";
        }

        $q = "insert into `".$wpdb->prefix.'wsd_dbc_'.'wh_material'."` set ".implode(',',$set);
        if($this->add_mat_noerr)$wpdb->query($q);
            $mess[] = $this->prepare_query($q);

        if($wpdb->last_error == '') {
            $id = $wpdb->insert_id;
        }

        $err = ob_get_clean();
        if($err)
            $mess[] = $err;
        if($wpdb->last_error !== '') {
            $error = true;
            $mess[] = $wpdb->last_error;
        }else{
            if($this->add_mat_noerr){
                $notice = '<div> Добавлен товар. </div>';
                $mess[] = $notice;
            }
        }
    }
    
    public function prepare_query($q){
        $r=[];
        $r['insert into ']='INSERT INTO<br/> ';
        $r[' set ']=' <br/>SET<br/> ';
        $r[' SET ']=' <br/>SET<br/> ';
        $r[',`']=',<br/>`';
        $r['WHERE ']='<br/>WHERE<br/>';
        $r['where ']='<br/>WHERE<br/>';
        $r[' FROM']='<br/>FROM';
        $r[' from']='<br/>FROM';
        $r['SELECT ']='SELECT<br/>';
        $r['select ']='SELECT<br/>';
//        $r['']='';
//        $r['']='';
//        $r['']='';
//        $r['']='';
        $q = strtr($q,$r);
        return $q;
    }
    public function ajax(){
        
        $act = filter_input(INPUT_POST, 'act', FILTER_SANITIZE_STRING);
        if(!$act)$act='noact';
        $res = [];
        switch($act){
            case 'find_material_names':
                $res = $this->ajax_find_material_names();
                break;
            case 'find_material_items':
                $res = $this->ajax_find_material_items();
                break;
            case 'add_material':
                $res = $this->ajax_add_material();
                break;
            
            case 'add_to_weybill':
                $res = $this->ajax_add_to_waybill();
                break;
            case 'create_weybill':
                $res = $this->ajax_create_weybill();
                break;
            case 'clear_weybill':
                $res = $this->ajax_clear_weybill();
                break;
            case 'clear_weybill_item':
                $res = $this->ajax_clear_weybill_item();
                break;
            case 'get_weybill_items':
                $res = $this->ajax_get_weybill_items();
                break;
            
//            case 'find_material_items':
//                $res = $this->ajax_find_material_items();
//                break;
//            case 'add_material':
//                $res = $this->ajax_add_material();
//                break;
            case 'add_to_writeoff':
                $res = $this->ajax_add_to_writeoff();
                break;
            case 'create_writeoff':
                $res = $this->ajax_create_writeoff();
                break;
            case 'clear_writeoff':
                $res = $this->ajax_clear_writeoff();
                break;
            case 'clear_writeoff_item':
                $res = $this->ajax_clear_writeoff_item();
                break;
            case 'get_writeoff_items':
                $res = $this->ajax_get_writeoff_items();
                break;
            case 'add_to_writeoff_type':
                $res = $this->ajax_add_to_writeoff_type();
                break;
            case 'add_to_writeoff_comment':
                $res = $this->ajax_add_to_writeoff_comment();
                break;
            
//            case 'position':
//                $res = $this->ajax_position();
//                break;
//            case 'size':
//                $res = $this->ajax_size();
//                break;
//            case 'get':
//                $res = $this->_ajax_get();
//                break;
//            case 'getcart':
//                $res = $this->get_a_count_in_cart();
//                break;
//            case 'add_c':
//                $res = $this->_a_add_to_cart();
//                break;
//            case 'rem_c':
//                $res = $this->_a_remove_from_cart();
//                break;
//            case 'order_ch_mail_ex':
//                $res = $this->_a_order_ch_mail_ex();
//                break;
            
        }
        wp_send_json( $res);
        die; // даём понять, что обработчик закончил выполнение
    }
    public function ajax_noname(){
        
        $act = filter_input(INPUT_POST, 'act', FILTER_SANITIZE_STRING);
        if(!$act)$act='noact';
        $res = [];
        switch($act){
        }
        wp_send_json( $res);
        die; // даём понять, что обработчик закончил выполнение
    }
    public function page_product_report(){
        $out = '';
        ob_start();
            get_template_part( 'template-parts/dbconst/wh_report', '' );
        $out.=ob_get_clean();
//                    $posts_count = wp_count_posts('doc')->publish;
//        $out = do_shortcode( $out );
        return $out;
    }
    public function page_product_getting(){
        $out = '';
        ob_start();
            get_template_part( 'template-parts/dbconst/product_getting', '' );
        $out.=ob_get_clean();
//                    $posts_count = wp_count_posts('doc')->publish;
//        $out = do_shortcode( $out );
        return $out;
    }
    public function page_product_shipment(){
        $out = '';
        ob_start();
            get_template_part( 'template-parts/dbconst/product_shipment', '' );
        $out.=ob_get_clean();
//                    $posts_count = wp_count_posts('doc')->publish;
//        $out = do_shortcode( $out );
        return $out;
    }
    public function page_waybill__bill(){
        global $no_tab;
        $no_tab = true;
        $out = '';
        ob_start();
            get_template_part( 'template-parts/dbconst/product_shipment', '' );
        $out.=ob_get_clean();
//                    $posts_count = wp_count_posts('doc')->publish;
//        $out = do_shortcode( $out );
        return $out;
    }
}
