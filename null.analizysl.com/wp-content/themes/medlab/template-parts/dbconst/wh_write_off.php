<?php

/* 
 * wh_write_off.php
 * [wsd_dbc_wh_write_off]
 */

include_once 'class.DBConst.php';
$WriteOffItem_activate = false;
include_once 'wh_write_off_item.php';

class  DBCWhWriteOff extends DBConst
{
    public $table = 'wh_write_off';
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
        $this->page = 'wh_write_off'; // product_shipment
        $this->filter_use = 1;
        $this->filters = [''=>''];
//        $this->tools = ['main','create','edit','delete','export'];
        $this->tools_def = [];
//        $this->tools_def = ['main','create','edit','delete','export'];
        $this->styles = ['prod_getting'];
        $this->scripts_inline[] = 'prod_write_off'; // prod_shipment
        $this->scripts[] = 'prod_write_off'; // prod_shipment
        $this->tabs_use = 1;
        $this->tabs = ['main'=>'Отгрузка товара','sent'=>'Отправленные накладные','received'=>'Полученные накладные','items'=>''];
        $this->tabs = ['main'=>'Списание товара',
            'bills'=>'Все апросы на списание',
            'bills_new'=>'Новые',
            'bills_approved'=>'Одобренные',
            'bills_rejected'=>'Отклонённые',
            'items'=>''];
        $this->tabs = [
            'bills_new'=>'Новые',
            'bills_approved'=>'Одобренные',
            'bills_rejected'=>'Отклонённые',
            'bills'=>'Все апросы на списание',
            'main'=>'Списание товара',
            'items'=>''];
//        $this->tabs = ['bills'=>'Запросы на списание','main'=>'Списание товара','items'=>''];
        
        $this->def_tab = 'bills_new';
        
//        $this->debug=true;
//        $this->orderdef = ['id'=>'desc'];
//        $this->orderdef = ['id'=>'asc'];
        parent::__construct();
    }
    public function create_found(){
        
    }
    public function init_filter(){
//        $this->actions = array_merge($this->actions,['sent','received']);
        $this->actions = array_merge($this->actions,['bills']);
        $this->actions[] = 'bills_new';
        $this->actions[] = 'bills_approved';
        $this->actions[] = 'bills_rejected';
        $this->actions[] = 'writeoffapprove';
        parent::init_filter();
    }
    public function filter(){
//        add_log($this->actions);
//        add_log('tab '.$this->tab.'; action '.$this->action.';');
        if($this->tab == 'items'){
            $this->tabs['items'] = 'Товары под списание';
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
        if($this->tab == 'bills_new' && !$this->action){
            $this->where['status'] = 2;
        }
        if($this->tab == 'bills_approved' && !$this->action){
            $this->where['status'] = 1;
        }
        if($this->tab == 'bills_rejected' && !$this->action){
            $this->where['status'] = 0;
        }
//        add_log($this->where);
    }
//            'bills_new'=>'Новые',
//            'bills_approved'=>'Одобренные',
//            'bills_rejected'=>'Отклонённые',
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
    public function items(){
        add_log(strtr(__METHOD__,['::'=>' :: ']).'; <br/>main_tab '.$this->main_tab.'; tab '.$this->tab.';'
                . ' action '.$this->action.'; mode '.$this->mode.';');
//        add_log('items');
//        add_log($this->action);
        $this->data = $this->data('item');
    }
    public function writeoffapprove(){
//    public function weybillget(){
        global $ht;
//        add_log(__METHOD__);
//        add_log($this->action);
//        add_log(strtr(__METHOD__,['::'=>' :: ']).'; <br/>'
//                .'main_tab '.$this->main_tab.'; tab '.$this->tab.'; action '.$this->action.'; mode '.$this->mode.';');
//        if(${'_POST'})add_log(${'_POST'});
        $this->id = $ht->postget('wbid',$this->id,FILTER_SANITIZE_NUMBER_INT);
        $this->data = $this->data('item');
    }
    public function action__update__writeoffapprove($attr=[]){
//        add_log(strtr(__METHOD__,['::'=>' :: ']).'; <br/>'
//                .'main_tab '.$this->main_tab.'; tab '.$this->tab.'; action '.$this->action.'; mode '.$this->mode.';');
//        if(${'_POST'})add_log(${'_POST'});
        if($this->tab == 'items'){
            if($this->action == 'writeoffapprove'){
                if($this->mode == 'normal'){
//                    $this->update_writeoffapprove_items();
                    $this->update_writeoffapprove();
                }
            }
        }
    }
    public $coming_full = 2;
    public function update_writeoffapprove(){
        global $wpdb;
        if($this->id){
            $this->form_type_gets[]='writeoffapprove';
//            $result = $this->data_def();
//            add_log($result);
//            $temp = $this->form_type;
//            $this->form_type = false;
            $this->data = $this->data('item');
//            $this->form_type = $temp;
//            $this->init_form();
            $id = $this->id;

            $q= "select * from `".$wpdb->prefix.$this->table."` where `id` = '$id'";      
            $old_fields = $wpdb->get_row($q,ARRAY_A);

            $set=[];
            $to_update = [];
            $to_update[] = 'status';
            $to_update[] = 'comment';
            foreach ($this->fields_info as $key => $value) {
                if(in_array($key,$to_update)){
                    if($key == 'status'){
                        $sts = $this->data[$key];
                        if($sts == 2){
                            if($this->coming_full>2)
                                $this->data[$key] = $this->coming_full;
                        }
                    }
                    $set[] = "`$key` = '".$this->data[$key]."'";
                }
            }
            if($this->data['status']=='0' || $this->data['status']=='1'){
//                if($old_fields['date_receiv']=='0000-00-00')
//                        $set[] = "`date_receiv` = '".current_time('Y-m-d')."'";
                
//                if($old_fields['time_receiv']=='00:00:00')
//                        $set[] = "`time_receiv` = '".current_time('H:i:s')."'";
                
                if($old_fields['appruve_id']=='0'){
                    $uid = get_current_user_id();
                    $set[] = "`appruve_id` = '".$uid."'";
                }
            }
            $q = "update `".$wpdb->prefix.$this->table."` set ".implode(',',$set);
            $q .=  " where `id` = '".$this->id."'";
            $wpdb->query($q);
//            add_log($this->prepare_query($q));
//            add_log($this->data);

//            $q= "select * from `".$wpdb->prefix.$this->table."` where `id` = '$id'";      
//            $field = $wpdb->get_row($q,ARRAY_A);
//            $weight_fields = $this->weight_fields;
//
////            add_log(['$field',$field]);
////            add_log(['$old_fields',$old_fields]);
//            foreach($weight_fields as $wf){
//                if(!array_key_exists($wf, $field))continue;
//                $newweigh = $field[$wf];
//                $oldweigh = $old_fields[$wf];
//                $q = 'no q';
//                if($newweigh != $oldweigh && $id){
//                    if($newweigh < $oldweigh){
//                        $q= "update `".$wpdb->prefix.$this->table."` set `$wf` = `$wf`+1 where "
//                                . "`id` <> '$id' and `$wf` >= '$newweigh' and `$wf` <= '$oldweigh' ";
//                    }else{
//                        $q= "update `".$wpdb->prefix.$this->table."` set `$wf` = `$wf`-1 where "
//                                . "`id` <> '$id' and `$wf` <= '$newweigh' and `$wf` >= '$oldweigh' ";
//                    }
//                    $wpdb->query($q);
//                }
//                        add_log([$newweigh,$oldweigh,$id,$wf]);
//                        add_log($q);
//            }
        }
    }
    public function update_writeoffapprove_items(){
        global $wpdb,$ht;
//        $regxp = null;
//        $filter_type = FILTER_REQUIRE_ARRAY;
//        $data = $ht->postget('wbitems',[],$filter_type,$regxp);
//        $data = $ht->pre('wbitems',[],$filter_type,$regxp);
        $data = filter_input(INPUT_POST, 'wbitems', FILTER_SANITIZE_STRING,FILTER_REQUIRE_ARRAY);
//        add_log($data);
        $id = $this->id;
        $q= "select `id` from `".$wpdb->prefix.$this->table."_item` where `wb_id` = '$id'";      
        $items = $this->get_col($q);
//        add_log($items);
        $to_updates = [];
        $to_update[] = 'status';
        $to_update[] = 'comment';
        foreach ($items as $key => $iid) {
            if(isset($data[$iid])){
                $set=[];
                foreach ($to_update as $key => $field) {
                    $set[] = "`$field` = '".$data[$iid][$field]."'";
                    if($field == 'status'){
                        $sts = $data[$iid][$field];
                        if($sts>2){
                            if($this->coming_full==2)
                                $this->coming_full = $sts;
                            if($this->coming_full==4 && $sts == 3)
                                $this->coming_full = $sts;
                        }
                    }
                }
                $q = "update `".$wpdb->prefix.$this->table."_item` set ".implode(',',$set);
                $q .=  " where `id` = '".$iid."'";
                $wpdb->query($q);
//                add_log($q);s
            }
        }
    }
    public function bills_new(){
//        add_log(__METHOD__.'; main_tab '.$this->main_tab.'; tab '.$this->tab.'; action '.$this->action.'; mode '.$this->mode.';');
//        add_log('sent');
//        add_log($this->action);
        $this->data = $this->data('list');
    }
    public function bills_approved(){
//        add_log(__METHOD__.'; main_tab '.$this->main_tab.'; tab '.$this->tab.'; action '.$this->action.'; mode '.$this->mode.';');
//        add_log('sent');
//        add_log($this->action);
        $this->data = $this->data('list');
    }
    public function bills_rejected(){
//        add_log(__METHOD__.'; main_tab '.$this->main_tab.'; tab '.$this->tab.'; action '.$this->action.'; mode '.$this->mode.';');
//        add_log('sent');
//        add_log($this->action);
        $this->data = $this->data('list');
    }
    public function bills(){
//        add_log(__METHOD__.'; main_tab '.$this->main_tab.'; tab '.$this->tab.'; action '.$this->action.'; mode '.$this->mode.';');
//        add_log('sent');
//        add_log($this->action);
        $this->data = $this->data('list');
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
    public function item(){
//        add_log(strtr(__METHOD__,['::'=>' :: ']).'; <br/>main_tab '.$this->main_tab.'; tab '.$this->tab.';'
//                . ' action '.$this->action.'; mode '.$this->mode.';');
        parent::item();
    }
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
    public function __call($name, $arguments) {
        add_log(strtr(__METHOD__,['::'=>' :: ']).'; collback:: '.$name.'; <br/>main_tab '.$this->main_tab.'; tab '.$this->tab.';'
                . ' action '.$this->action.'; mode '.$this->mode.';');
    }
}

$DBCWhWriteOff = new DBCWhWriteOff();
