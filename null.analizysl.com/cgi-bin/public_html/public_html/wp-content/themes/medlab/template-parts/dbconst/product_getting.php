<?php

/* 
 * product_getting.php
 * [wsd_dbc_wh_material]
 */

include_once 'class.DBConst.php';
$WeybillItem_activate = false;
include_once 'weybill_item.php';

class  DBCWhMaterial extends DBConst
{
    public $table = '';
    public $filter = [];
    public $filtered = [];
    public $where = [];
    public $data = [];
//    public $pager_by = 50;
//    public $pager_by = 1;
    public $page = '';
    public $action = '';
    public $main_tab = false;
    public $main_act = false;
    
//    public $field_form_tree = ['group_id']; // fields used with tree
//    public $field_form_tree_parent = ['group_id'=>'parent']; // field in out table used as parent for tree
    
    /*
     * for replace page init tab in:
     * tabs [method] main_tab
     */
    public function __construct($tab=false,$act=false,$mode = 'normal') {
        $this->page = 'product_getting';
        $this->filter_use =  1;
        $this->filters = [''=>''];
        $this->tools_def = [];
        $this->main_tab = $tab;
        $this->main_act = $act;
        $this->mode = $mode;
        $this->styles = ['prod_getting'];
        $this->scripts = ['prod_getting'];
        $this->tabs_use = 1;
        $this->tabs = ['main'=>'Прием товара','from_wh'=>'Прием со стороны складов','sent'=>'Прием со стороны складов'];
        $this->tabs = ['main'=>'Прием товара','sent'=>'Прием со стороны складов'];
        $this->tabs = ['main'=>'Прием товара','from_wh'=>'Прием со стороны складов','items'=>''];
//        $this->debug=true;
//        $this->orderdef = ['id'=>'desc'];
//        $this->orderdef = ['id'=>'asc'];
        
        global $ht;
        $tab = $ht->postget('tab','notab',FILTER_SANITIZE_STRING);
        switch($tab){
            case 'from_wh':
            case 'sent':
            case 'items':
                $this->table = 'wh_weybill';
//                $this->page = 'product_shipment';
//                $this->scripts = ['prod_shipment'];
//                $this->main_tab = 'sent';
                break;
            default:
                break;
        }
        parent::__construct();
    }
    public function create_found(){
        
    }
    public function init_filter(){
        $this->actions = array_merge($this->actions,['from_wh','sent']);
        $this->actions[] = 'weybillget';
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
        if($this->tab == 'from_wh' && !$this->action){
            $current_house_id = $this->house_own;
//            $this->where['sender'] = $cuid;
//            $this->where['house_from'] = $current_house_id;
            $this->where['house_to'] = $current_house_id;
            $this->where['house_to'] = $current_house_id.'\' and `status` <> \'2\' and `status` > \'0';
//            $this->where['status'] = 2;
        }
//        add_log($this->where);
    }
    public function data_list_after_build_query(&$sel,&$q_){
//        add_log($this->prepare_query($sel.$q_));
    }
    public function filter__form_field__status__select_vars($ret = null,$attr=[]){
//        add_log('main_tab '.$this->main_tab.'; tab '.$this->tab.'; action '.$this->action.';');
//        add_log(strtr(__METHOD__,['::'=>' :: ']).'; <br/>main_tab '.$this->main_tab.'; tab '.$this->tab.'; action '.$this->action.'; mode '.$this->mode.';');
        if($this->tab == 'items'){
            if($this->action == 'weybillget'){
//                <select name="status" id="" class=""><option value="0" selected="selected" id="" class="">Создано</option>
//                    <option value="1" id="" class="">Отправил</option>
//                    <option value="2" id="" class="">Принял</option>
//                    <option value="3" id="" class="">Пришло не полностью</option>
//                    <option value="4" id="" class="">Не пришло</option></select>
                $ret = [];
                $ret[2]='Принял';
                $ret[3]='Пришло не полностью';
                $ret[4]='Не пришло';
            }
        }
        return $ret;
    }
    public function from_wh(){
//        add_log('from_wh');
//        add_log($this->action);
        $this->data = $this->data('list');
    }
    public function items(){
        add_log(__METHOD__);
//        add_log($this->action);
        $this->data = $this->data('item');
    }
    public function weybillget(){
        global $ht;
//        add_log(__METHOD__);
//        add_log($this->action);
//        add_log(strtr(__METHOD__,['::'=>' :: ']).'; <br/>'
//                .'main_tab '.$this->main_tab.'; tab '.$this->tab.'; action '.$this->action.'; mode '.$this->mode.';');
//        if(${'_POST'})add_log(${'_POST'});
        $this->id = $ht->postget('wbid',$this->id,FILTER_SANITIZE_NUMBER_INT);
        $this->data = $this->data('item');
    }
    public function action__update__weybillget($attr=[]){
//        add_log(strtr(__METHOD__,['::'=>' :: ']).'; <br/>'
//                .'main_tab '.$this->main_tab.'; tab '.$this->tab.'; action '.$this->action.'; mode '.$this->mode.';');
//        if(${'_POST'})add_log(${'_POST'});
        if($this->tab == 'items'){
            if($this->action == 'weybillget'){
                if($this->mode == 'normal'){
                    $this->update_weybillget_items();
                    $this->update_weybillget();
                }
            }
        }
    }
    public $coming_full = 2;
    public function update_weybillget(){
        global $wpdb;
        if($this->id){
            $this->form_type_gets[]='weybillget';
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
            if($this->data['status']=='2'){
                if($old_fields['date_receiv']=='0000-00-00')
                        $set[] = "`date_receiv` = '".current_time('Y-m-d')."'";
                if($old_fields['time_receiv']=='00:00:00')
                        $set[] = "`time_receiv` = '".current_time('H:i:s')."'";
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
    public function update_weybillget_items(){
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
    public function sent(){
//        add_log('sent');
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
    public function show($echo = 1){
//        ob_start();
//        include 'page/'.$this->page.'.php';
//        return ob_get_clean();
//        add_log('main_tab '.$this->main_tab.'; tab '.$this->tab.'; action '.$this->action.';');
//        add_log($this->page);
        parent::show();
    }
}

$DBCWhMaterial = new DBCWhMaterial();
