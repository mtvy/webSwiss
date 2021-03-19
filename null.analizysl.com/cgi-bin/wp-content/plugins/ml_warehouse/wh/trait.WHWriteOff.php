<?php

/* 
 * trait.WHWriteOff.php
 */

trait WHWriteOff
{
    
    public $mess = [];
    public function ajax_add_to_writeoff_type(){
        global $wpdb,$ht;
        $w = [];
        $names = [];
        $mess = [];
        ob_start();
        
        if(!isset($_SESSION['writeoff_items_type']))$_SESSION['writeoff_items_type'] = [];
        if(!isset($_SESSION['writeoff_items_comment']))$_SESSION['writeoff_items_comment'] = [];
//        $selo =  filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT,FILTER_REQUIRE_ARRAY); // select order
        $id =  filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT); // select order
        $val =  filter_input(INPUT_POST, 'value', FILTER_SANITIZE_STRING); // select order
//        if($selo){
//            $_SESSION['writeoff_items_type'] += $selo ;
//        }
        if($id)$_SESSION['writeoff_items_type'][$id] = $val ;
//        $names = array_merge([],$_SESSION['writeoff_items_type']);
//        $this->build_weybill($mess);
        
//        $name = filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);
        
        $w = [];
//        $this->where_in('c','title',$manuf,$w);
        
        $w[]=" d.`id` in ('".implode("','",$_SESSION['writeoff_items'])."') ";
//        $w[]=" d.`id` in ('".$id."') ";
        
        
//        if(!$id) return ['res'=>[],'mess'=>['no result']];
        $names = [];
        $this->mess = [];
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
        
//        $catlist = $wpdb->get_col($q); // ?
        $catlist2 = $wpdb->get_results($q,ARRAY_A);
        
        foreach ($catlist2 as $k => $v) {
            $mid = $v['id'];
            $catlist2[$k]['type'] = 1;
            if(isset($_SESSION['writeoff_items_type'][$mid]))
                $catlist2[$k]['type'] = $_SESSION['writeoff_items_type'][$mid];
            $catlist2[$k]['comment'] = '';
            if(isset($_SESSION['writeoff_items_comment'][$mid]))
                $catlist2[$k]['comment'] = $_SESSION['writeoff_items_comment'][$mid];
        }
        
//        if(!$catlist) return ['mess'=>['No result.']];
//        if(!$catlist) $mess[] = ['No result.'];
//        else{
//        }
            $suf = 'ий';
            if(count($catlist2)>0)$suf = 'ия';
            if(count($catlist2)>1)$suf = 'ии';
            if(count($catlist2)>4)$suf = 'ий';
            $this->mess[] = ['В очереди '.count($catlist2).' позиц'.$suf.'.'];
            $dso_query_nr = str_pad($id,20,'0',STR_PAD_LEFT);
            $this->mess[] = ['Обновлён тип списания для материала № '.$dso_query_nr];
        
        if($wpdb->last_error == '') {
//            $id = $wpdb->insert_id;
//            if($id){
//                $this->mess[]=$id;
//            }
        }else{
            $this->mess[]=$wpdb->last_error;
        }
        $err = ob_get_clean();
        if($err){
//            add_log($err);
            $this->mess[]=$err;
        }
        
        return ['res'=>$catlist2,'mess'=>$this->mess];
    }
    public function ajax_add_to_writeoff_comment(){
        global $wpdb,$ht;
        $w = [];
        $names = [];
        $mess = [];
        ob_start();
        
        if(!isset($_SESSION['writeoff_items_type']))$_SESSION['writeoff_items_type'] = [];
        if(!isset($_SESSION['writeoff_items_comment']))$_SESSION['writeoff_items_comment'] = [];
//        $selo =  filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT,FILTER_REQUIRE_ARRAY); // select order
        $id =  filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT); // select order
        $val =  filter_input(INPUT_POST, 'value', FILTER_SANITIZE_STRING); // select order
//        if($selo){
//            $_SESSION['writeoff_items_type'] += $selo ;
//        }
        if($id)$_SESSION['writeoff_items_comment'][$id] = $val ;
//        $names = array_merge([],$_SESSION['writeoff_items_type']);
//        $this->build_weybill($mess);
        
//        $name = filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);
        
        $w = [];
//        $this->where_in('c','title',$manuf,$w);
        
        $w[]=" d.`id` in ('".implode("','",$_SESSION['writeoff_items'])."') ";
//        $w[]=" d.`id` in ('".$id."') ";
        
        
//        if(!$id) return ['res'=>[],'mess'=>['no result']];
        $names = [];
        $this->mess = [];
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
        
//        $catlist = $wpdb->get_col($q); // ?
        $catlist2 = $wpdb->get_results($q,ARRAY_A);
        
        foreach ($catlist2 as $k => $v) {
            $mid = $v['id'];
            $catlist2[$k]['type'] = 1;
            if(isset($_SESSION['writeoff_items_type'][$mid]))
                $catlist2[$k]['type'] = $_SESSION['writeoff_items_type'][$mid];
            $catlist2[$k]['comment'] = '';
            if(isset($_SESSION['writeoff_items_comment'][$mid]))
                $catlist2[$k]['comment'] = $_SESSION['writeoff_items_comment'][$mid];
        }
        
//        if(!$catlist) return ['mess'=>['No result.']];
//        if(!$catlist) $mess[] = ['No result.'];
//        else{
//        }
            $suf = 'ий';
            if(count($catlist2)>0)$suf = 'ия';
            if(count($catlist2)>1)$suf = 'ии';
            if(count($catlist2)>4)$suf = 'ий';
            $this->mess[] = ['В очереди '.count($catlist2).' позиц'.$suf.'.'];
            $dso_query_nr = str_pad($id,20,'0',STR_PAD_LEFT);
            $this->mess[] = ['Обновлён комментарий списания для материала № '.$dso_query_nr];
        
        if($wpdb->last_error == '') {
//            $id = $wpdb->insert_id;
//            if($id){
//                $this->mess[]=$id;
//            }
        }else{
            $this->mess[]=$wpdb->last_error;
        }
        $err = ob_get_clean();
        if($err){
//            add_log($err);
            $this->mess[]=$err;
        }
        
        return ['res'=>$catlist2,'mess'=>$this->mess];
    }
    public function ajax_add_to_writeoff(){
        global $wpdb,$ht;
        $w = [];
        $names = [];
        $mess = [];
        ob_start();
        
        if(!isset($_SESSION['writeoff_items']))$_SESSION['writeoff_items'] = [];
        if(!isset($_SESSION['writeoff_items_type']))$_SESSION['writeoff_items_type'] = [];
        if(!isset($_SESSION['writeoff_items_comment']))$_SESSION['writeoff_items_comment'] = [];
        $selo =  filter_input(INPUT_POST, 'list_items', FILTER_SANITIZE_NUMBER_INT,FILTER_REQUIRE_ARRAY); // select order
        if($selo){
            $_SESSION['writeoff_items'] += $selo ;
        }
        $names = array_merge([],$_SESSION['writeoff_items']);
//        $this->build_weybill($mess);
        
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
        
        $w[]=" d.`id` in ('".implode("','",$_SESSION['writeoff_items'])."') ";
        
        
        if(!count($w)) return ['res'=>[],'mess'=>['no result']];
        $names = [];
        $this->mess = [];
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
        
//        $catlist = $wpdb->get_col($q); // ?
        $catlist2 = $wpdb->get_results($q,ARRAY_A);
        
        foreach ($catlist2 as $k => $v) {
            $mid = $v['id'];
            $catlist2[$k]['type'] = 1;
            if(isset($_SESSION['writeoff_items_type'][$mid]))
                $catlist2[$k]['type'] = $_SESSION['writeoff_items_type'][$mid];
            $catlist2[$k]['comment'] = '';
            if(isset($_SESSION['writeoff_items_comment'][$mid]))
                $catlist2[$k]['comment'] = $_SESSION['writeoff_items_comment'][$mid];
        }
        
//        if(!$catlist) return ['mess'=>['No result.']];
//        if(!$catlist) $mess[] = ['No result.'];
//        else{
//        }
            $suf = 'ий';
            if(count($catlist2)>0)$suf = 'ия';
            if(count($catlist2)>1)$suf = 'ии';
            if(count($catlist2)>4)$suf = 'ий';
            $this->mess[] = ['В очереди '.count($catlist2).' позиц'.$suf.'.'];
        
        if($wpdb->last_error == '') {
//            $id = $wpdb->insert_id;
//            if($id){
//                $this->mess[]=$id;
//            }
        }else{
            $this->mess[]=$wpdb->last_error;
        }
        $err = ob_get_clean();
        if($err){
//            add_log($err);
            $this->mess[]=$err;
        }
        
        return ['res'=>$catlist2,'mess'=>$this->mess];
    }
    public function ajax_get_writeoff_items(){
        global $wpdb,$ht;
        $w = [];
        $names = [];
        $mess = [];
        ob_start();
        if(!isset($_SESSION['writeoff_items']))$_SESSION['writeoff_items'] = [];
        if(!isset($_SESSION['writeoff_items_type']))$_SESSION['writeoff_items_type'] = [];
        if(!isset($_SESSION['writeoff_items_comment']))$_SESSION['writeoff_items_comment'] = [];
        
        $w = [];
        $w[]=" d.`id` in ('".implode("','",$_SESSION['writeoff_items'])."') ";
        
        
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
        
//        $catlist = $wpdb->get_col($q);
        $catlist2 = $wpdb->get_results($q,ARRAY_A);
        
        foreach ($catlist2 as $k => $v) {
            $mid = $v['id'];
            $catlist2[$k]['type'] = 1;
            if(isset($_SESSION['writeoff_items_type'][$mid]))
                $catlist2[$k]['type'] = $_SESSION['writeoff_items_type'][$mid];
            $catlist2[$k]['comment'] = '';
            if(isset($_SESSION['writeoff_items_comment'][$mid]))
                $catlist2[$k]['comment'] = $_SESSION['writeoff_items_comment'][$mid];
        }
        
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
    public function ajax_clear_writeoff(){
        global $wpdb,$ht;
        $w = [];
        $names = [];
        $mess = [];
        ob_start();
        
        $act = filter_input(INPUT_POST, 'act', FILTER_SANITIZE_STRING);
        $conf = filter_input(INPUT_POST, 'conferm', FILTER_SANITIZE_STRING);
        if($act == 'clear_writeoff' && $conf == 'ok'){
//            $selo=[];
            $_SESSION['writeoff_items'] =[];
            $_SESSION['writeoff_items_type'] = [];
            $_SESSION['writeoff_items_comment'] = [];
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
    public function ajax_clear_writeoff_item(){
        global $wpdb,$ht;
        $w = [];
        $names = [];
        $mess = [];
        ob_start();
        if(!isset($_SESSION['writeoff_items']))$_SESSION['writeoff_items'] = [];
        if(!isset($_SESSION['writeoff_items_type']))$_SESSION['writeoff_items_type'] = [];
        if(!isset($_SESSION['writeoff_items_comment']))$_SESSION['writeoff_items_comment'] = [];
        
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $act = filter_input(INPUT_POST, 'act', FILTER_SANITIZE_STRING);
        $conf = filter_input(INPUT_POST, 'conferm', FILTER_SANITIZE_STRING);
        if($act == 'clear_writeoff_item' && $conf == 'ok'){
//            $selo=[];
            unset($_SESSION['writeoff_items'][$id]);
        }
        $w = [];
        $w[]=" d.`id` in ('".implode("','",$_SESSION['writeoff_items'])."') ";
        
        
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
        
//        $catlist = $wpdb->get_col($q); // ?
        $catlist2 = $wpdb->get_results($q,ARRAY_A);
        
        foreach ($catlist2 as $k => $v) {
            $mid = $v['id'];
            $catlist2[$k]['type'] = 1;
            if(isset($_SESSION['writeoff_items_type'][$mid]))
                $catlist2[$k]['type'] = $_SESSION['writeoff_items_type'][$mid];
            $catlist2[$k]['comment'] = '';
            if(isset($_SESSION['writeoff_items_comment'][$mid]))
                $catlist2[$k]['comment'] = $_SESSION['writeoff_items_comment'][$mid];
        }
        
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
    public function ajax_create_writeoff(){
        global $wpdb,$ht;
        $w = [];
        $names = [];
        $mess = [];
        ob_start();
        
        $id = $this->create_writeoff($mess);
        if($this->add_mat_noerr){
            $this->create_writeoff_items($mess);
            $_SESSION['writeoff_items'] =[];
            $_SESSION['writeoff_items_type'] = [];
            $_SESSION['writeoff_items_comment'] = [];
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
    public $list_id = null;
    public function create_writeoff(&$mess){
        global $wpdb,$ht;
        $data = $this->data_def('wh_write_off'); // wh_weybill
//            $mess[] = $ht->pre($data);
//            $mess[] = $ht->pre($_POST);
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
        
//        $mid = current($_SESSION['waybill_items']);
//        $q = "select `group_id` from `".$wpdb->prefix.'wsd_dbc_'.'wh_material'."` where id = '".$mid."'";
//        $house_from = $wpdb->get_var($q);
//        $house_to = filter_input(INPUT_POST,'house_id',FILTER_SANITIZE_NUMBER_INT);
        $cd = current_time('Y-m-d');
        $ct = current_time('H:i:s');
        
//        $data['house_from'] = $house_from;
//        $data['house_to'] = $house_to;
//        $data['sender'] = get_current_user_id();
        $data['user_id'] = get_current_user_id();
        
//        $set[] = "`house_from` = '$house_from'";
//        $set[] = "`house_to` = '$house_to'";
        $set[] = "`date` = '$cd'";
//        $set[] = "`time` = '$ct'";
//        $set[] = "`open_expiry_date` = '$ct'";
        $key_extract = [];
        $key_extract[] = 'date';
//        $key_extract[] = 'time';
        foreach ($data as $key => $value) {
            if(in_array($key,$key_extract))continue;
            $set[] = "`$key` = '".$value."'";
        }

        $q = "insert into `".$wpdb->prefix.'wsd_dbc_'.'wh_write_off'."` set ".implode(',',$set);
        if($this->add_mat_noerr)$wpdb->query($q);
//            $mess[] = $this->prepare_query($q);

        $id = false;
        if($wpdb->last_error == '') {
            $id = $wpdb->insert_id;
            $wpdb->list_id = $id;
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
                $notice = '<div> Создан запрос на списание № '.str_pad($id,20,'0',STR_PAD_LEFT).'. </div>';
                $mess[] = $notice;
            }
        }
        return $id ;
    }
    public function create_writeoff_items(&$mess){
        global $wpdb,$ht;
        $id = $wpdb->list_id;
//        $mess[] = 'create_weybill_items';
//        $mess[] = $wpdb->weybill_id;
        if(!isset($_SESSION['writeoff_items']))$_SESSION['writeoff_items'] = [];
        $types =  filter_input(INPUT_POST, 'types', FILTER_SANITIZE_STRING,FILTER_REQUIRE_ARRAY); // select order
        $comments =  filter_input(INPUT_POST, 'comments', FILTER_SANITIZE_STRING,FILTER_REQUIRE_ARRAY); // select order
        if($id){
            $waybill_items = $_SESSION['writeoff_items'];
            foreach($waybill_items as $mid){
                $t = 1;
                $c = '';
                if(isset($types[$mid]))$t = $types[$mid];
                if(isset($comments[$mid]))$c = $comments[$mid];
                $this->create_writeoff_item($mess,$mid,$t,$c);
            }
        }
    }
    public function create_writeoff_item(&$mess,$mid=false,$t=1,$c=''){
//        $mess[] = $mid;
        global $wpdb,$ht;
        $data = $this->data_def('wh_write_off_item'); // wh_weybill_item
//        $this->data = $data;
        
//        if(!$data['name_id']){
//            $mess[] = 'Не указано название товара.';
//            $mess[] = '<div> Сбой добавления товара. </div>';
//            $this->add_mat_noerr = false;
////            return;
//        }
        
        
        $set = [];
        ob_start();
        $wbid = $wpdb->list_id;
        $q = "select `group_id` from `".$wpdb->prefix.'wsd_dbc_'.'wh_material'."` where id = '".$mid."'";
        $group_from = $wpdb->get_var($q);
        $group_to = filter_input(INPUT_POST,'group_id',FILTER_SANITIZE_NUMBER_INT);
        $data['write_off_id'] = $wbid;
        $data['material_id'] = $mid;
//        $data['group_from'] = $group_from;
//        $data['group_to'] = $group_to;
//        $data['group_to'] = 0;
        $data['type'] = $t;
        $data['comment'] = $c;

//        $ct = current_time('Y-m-d H:i:s');
//        $set[] = "`created` = '$ct'";
        $cd = current_time('Y-m-d');
//        $ct = current_time('H:i:s');
        $set[] = "`date` = '$cd'";
        $key_extract = [];
        $key_extract[] = 'date';
        foreach ($data as $key => $value) {
            if(in_array($key,$key_extract))continue;
            $set[] = "`$key` = '".$value."'";
        }

        $q = "insert into `".$wpdb->prefix.'wsd_dbc_'.'wh_write_off_item'."` set ".implode(',',$set);
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
}
