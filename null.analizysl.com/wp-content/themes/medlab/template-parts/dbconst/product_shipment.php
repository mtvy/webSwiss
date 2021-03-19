<?php

/* 
 * product_shipment.php
 * [ml_warehouse_product_shipment ]
 */

include_once 'class.DBConst.php';
$WeybillItem_activate = false;
include_once 'weybill_item.php';

class  DBCWhMaterial extends DBConst
{
    public $table = 'wh_weybill';
    public $filter = [];
    public $filtered = [];
    public $where = [];
    public $data = [];
//    public $pager_by = 50;
//    public $pager_by = 1;
    public $page = '';
    public $action = '';
    
//    public $field_form_tree = ['group_id']; // fields used with tree
//    public $field_form_tree_parent = ['group_id'=>'parent']; // field in out table used as parent for tree
    public function __construct() {
//        $this->show_tpl_file_name = true;
//        $this->show_access_status = true;
        $this->page = 'product_shipment';
        $this->filter_use = 1;
        $this->filters = [''=>''];
        $this->tools_def = [];
        $this->styles = ['prod_getting'];
        $this->scripts = ['prod_shipment'];
        $this->tabs_use = 1;
        global $no_tab;
        if($no_tab){
            $this->tabs_use = 0;
            $this->scripts[] = 'prod_shipment_bill';
        }
        $this->tabs = ['main'=>'Отгрузка товара','sent'=>'Отправленные накладные','received'=>'Полученные накладные','items'=>''];
        
//        $this->debug=true;
//        $this->orderdef = ['id'=>'desc'];
//        $this->orderdef = ['id'=>'asc'];
        parent::__construct();
    }
    public function create_found(){
        
    }
    public function init_filter(){
        $this->actions = array_merge($this->actions,['sent','received']);
        parent::init_filter();
    }
    public function filter(){
//        add_log($this->actions);
//        add_log('tab '.$this->tab.'; action '.$this->action.';');
        if($this->tab == 'items'){
            $this->tabs['items'] = 'Товары в накладной';
        }
        parent::filter();
    }
//    public function controller(){
//        $this->page = 'warehouse';
//        $tpl = '';
//        if(strlen($this->action)>0)$tpl = $this->page.'-'.$this->action;
//        if(strlen($tpl) && file_exists('page/'.$tpl))$this->page = $tpl;
//    }
//    public function get(){
//        
//    }
//    public function set(){
//        
//    }
    public function init_access_after(){
        if( //in_array($this->action, ['create','edit','delete','item']) ||
                 in_array($this->tab, ['items']) ){
            $this->init_form();
        }
    }
    /**
     * update current class parameters
     * beafore build query for select list items
     * used in method "data" of parent class
     * concretize "where" paremeter
     * for main list
     * 
     */
    public function data_list_before_build_query(){
//        global $wpdb;
//        add_log('tab '.$this->tab.'; action '.$this->action.';');
        if($this->tab == 'sent' && !$this->action){
            $cuid = get_current_user_id();
            $current_house_id = 1;
//            $q = "select `meta_value` from `".$wpdb->prefix."usermeta` where `user_id` = '$cuid' and `meta_key` = 'warehouse_id'";
//            add_log($q);
//            $current_house_id = $wpdb->get_var($q);
            $current_house_id = $this->house_own;
//            add_log($current_house_id);
//            $this->where['sender'] = $cuid;
            $this->where['house_from'] = $current_house_id;
//            $this->where['house_to'] = $current_house_id;
        }
        if($this->tab == 'received' && !$this->action){
            $cuid = get_current_user_id();
            $current_house_id = 1;
//            $q = "select `meta_value` from `".$wpdb->prefix."usermeta` where `user_id` = '$cuid' and `meta_key` = 'warehouse_id'";
//            $current_house_id = $wpdb->get_var($q);
            $current_house_id = $this->house_own;
//            $this->where['sender'] = $cuid;
//            $this->where['house_from'] = $current_house_id;
            $this->where['house_to'] = $current_house_id;
        }
//        add_log($this->where);
    }
    public function data_list_after_build_query(&$sel,&$q_){
//        add_log($this->prepare_query($sel.$q_));
    }
    /**
     * update query of select_from during build item edit form
     * to difficult query 
     * used in method "form_field" of parent class
     * @param type $name
     * @param type $q_
     * @return type
     */
    public function field_q_prepare_select_from($name,&$q_){
        if(!in_array($name,['sender','receiver']))return ;
//        add_log(__FUNCTION__.' <b>'.$name.'</b>');
//        add_log('tab '.$this->tab.'; action '.$this->action.';');
//        add_log($q_);
        if($name == 'sender' || $name == 'receiver'){
            $q="select `ID` as 'value', `display_name` as 'title', 0 as 'parent', `id` as 'id'"
                . " from `wp_users`"
                . " where 1 order by `id` desc";
            
            $q="select a.`ID` as 'value', a.`display_name` as 'title', 0 as 'parent', a.`id` as 'id'"
                . " from `wp_users` a"
                . " where 1 order by a.`id` desc";
            
            $cp = '1';
            $caps = [];
            $caps[] = 'administrator';
            $caps[] = 'ml_agent';
//            $caps[] = 'ml_patient';
            $caps[] = 'ml_doctor';
            $caps[] = 'ml_manager';
            $caps[] = 'ml_administrator';
            $caps[] = 'ml_director';
            $caps[] = 'ml_procedurecab';
            
//            $caps[] = 'administrator';
//            $caps[] = 'administrator';
//            $caps[] = 'administrator';
//            $caps[] = 'administrator';
//            $caps[] = 'administrator';
//            $caps[] = 'administrator';
            if(count($caps)){
                $cp = " e.`meta_value` like '%\"";
                $cp .= implode("\"%' or e.`meta_value` like '%\"",$caps);
                $cp .= "\"%'  ";
            }
            
            $q="select a.`ID` as 'value', concat(a.`user_nicename`,' -- ',b.`meta_value`,' ',c.`meta_value`,' ',d.`meta_value`,' ') as 'title', 0 as 'parent', a.`id` as 'id'"
                . " from `wp_users` a"
                . " left join `wp_usermeta` b on b.user_id = a.id"
                . " left join `wp_usermeta` c on c.user_id = a.id"
                . " left join `wp_usermeta` d on d.user_id = a.id"
                . " left join `wp_usermeta` e on e.user_id = a.id"
                . " where 1"
                . " and b.`meta_key` = 'last_name'"
                . " and c.`meta_key` = 'first_name'"
                . " and d.`meta_key` = 'second_name'"
                . " and e.`meta_key` = 'wp_capabilities'"
                . " and ("
                    . $cp
                    . ")"
                . " order by a.`id` desc";
//            a:1:{s:13:"administrator";b:1;}
            
            $q_ = $q;
//            add_log($this->prepare_query($q_));
//            $mess[] = $this->prepare_query($q);
        }
    }
    public function sent(){
//        add_log('sent');
//        add_log($this->action);
        $this->data = $this->data('list');
    }
    public function received(){
//        add_log('received');
//        add_log($this->action);
        $this->data = $this->data('list');
    }
    public function main(){
        
    }
//    public function item(){
//        
//    }
//    public function create(){
//        
//    }
//    public function edit(){
//        
//    }
//    public function delete(){
//        
//    }
//    public function update(){
//        
//    }
//    public function data(){
//        
//    }
//    public function show(){
//        ob_start();
//        include 'page/'.$this->page.'.php';
//        return ob_get_clean();
//    }
}

$DBCWhMaterial = new DBCWhMaterial();
