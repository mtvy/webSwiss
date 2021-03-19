<?php

/* 
 * wh_report.php
 * [wh_report]
 */

include_once 'class.DBConst.php';
//$WhReport_activate = false;
//include_once 'wh_write_off_item.php';

class  DBCWhReport extends DBConst
{
//    public $table = 'wh_write_off';
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
    public function __construct($tab=false,$act=false,$mode = 'normal') {
        $this->table = 'wh_write_off';
        $this->table = 'wh_material';
//        $this->show_tpl_file_name = true;
//        $this->show_access_status = true;
        $this->page = 'wh_report'; // product_shipment
        $this->filter_use = 1;
        $this->filters = [''=>''];
        $this->tools_def = [];
//        $this->tools_def = ['main','create','edit','delete','export'];
        $this->tools_def = ['export'];
        $this->tools_def = [];
        $this->tool_access[] = 'administrator';
        $this->main_tab = $tab;
        $this->main_act = $act;
        $this->mode = $mode;
        $this->styles = ['prod_getting'];
        $this->styles[] = 'test_1';
        $this->scripts_inline[] = 'prod_write_off'; // prod_shipment
        $this->scripts[] = 'prod_write_off'; // prod_shipment
        $this->scripts[] = 'three';
        $this->scripts[] = 'test_1';
        $this->tabs_use = 1;
//        $this->tabs = ['main'=>'Отгрузка товара','sent'=>'Отправленные накладные','received'=>'Полученные накладные','items'=>''];
//        $this->tabs = ['main'=>'Списание товара',
//            'bills'=>'Все апросы на списание',
//            'bills_new'=>'Новые',
//            'bills_approved'=>'Одобренные',
//            'bills_rejected'=>'Отклонённые',
//            'items'=>''];
        $this->tabs = [
            'main'=>'основной отчет по остаткам на складе',
            'report_2'=>'реагенты и сопутствующей материал для проведения исследований',
            'report_3'=>'количество выполнены исследований/тестов',
            'report_4'=>'учет рабочего времени сотрудников',
            'report_5'=>'расчет себестоимости выполнения исследования',
            'report_6'=>'акт сверки сопоставления расходуемого товара с товаром на складах',
            'report_7'=>'акт принятие всего товара на основной склад',
            'report_8'=>'акт отгрузки товара на каждом из складе',
            'report_9'=>'акт сверки по внешнему складу',
            'report_10'=>'список передвижений товара с основного склада',
            'report_11'=>'акт привития, получения списка товарных накладных'
            ];
//        $this->tabs = ['bills'=>'Запросы на списание','main'=>'Списание товара','items'=>''];
        
//        $this->def_tab = 'bills_new';
        
//        $this->debug=true;
//        $this->orderdef = ['id'=>'desc'];
//        $this->orderdef = ['id'=>'asc'];
        if($act=='download'){
            $this->tabs_use = 0;
            $this->styles = [];
            $this->scripts = [];
            $this->scripts_inline = [];
            $this->styles = [];
        }
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
        
        $this->actions[] = 'report_2';
        $this->actions[] = 'report_3';
        $this->actions[] = 'report_4';
        $this->actions[] = 'report_5';
        $this->actions[] = 'report_6';
        $this->actions[] = 'report_7';
        $this->actions[] = 'report_8';
        $this->actions[] = 'report_9';
        $this->actions[] = 'report_10';
        $this->actions[] = 'report_11';
        
        $this->actions[] = 'download';
//        $this->actions[] = 'report_2_download';
        
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
        if(
                (!$this->tab || $this->tab == 'main') && (!$this->action || $this->action == 'main' || $this->action == 'download')
                ){
                $wh_id = $this->wh_id;
                if($wh_id) $this->where['house_id'] = $wh_id;
        }
//        add_log($this->where);
    }
//            'bills_new'=>'Новые',
//            'bills_approved'=>'Одобренные',
//            'bills_rejected'=>'Отклонённые',
    public function data_list_after_build_query(&$sel,&$q_){
//        add_log($this->prepare_query($sel.$q_));
        if((!$this->tab || $this->tab == 'main') && (!$this->action || $this->action == 'main' || $this->action == 'download')){
            $r = [];
            $r['where `house_id`'] = 'where a.`house_id`';
            $q_ = strtr($q_,$r);
        }
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
    public function download(){
//        add_log('received');
//        add_log($this->action);
        $this->main();
        $this->title = false;
    }
    public $wh_id = false;
    public function main(){
//        add_log(strtr(__METHOD__,['::'=>' :: ']).'; <br/>'
//                . ' main_tab '.$this->main_tab.'; tab '.$this->tab.';'
//                . ' main_act '.$this->main_act.'; action '.$this->action.';'
//                . ' mode '.$this->mode.';');
//        if($this->tab && $this->tab !='main') return;
        $this->title = 'остатки на складе';
//        $this->tools_def = ['main','create','edit','delete','export'];
//        $this->tools_def[] = 'export';
//        $this->tools_def[] = 'export';
//        $this->tools_def = ['export'];
        $this->init_access();
        global $wpdb,$ht;
        $roles = [];
        
        $roles[] = 'wh_adminproccab';
        $roles[] = 'wh_doctorlaborant';
//        $roles[] = '';
        $this->use_limit = false;
        $this->datas = [];
        $this->tab_titles = [];
        if($ht->access($roles)){// dir
            $cuid = get_current_user_id();
            $wh_id = get_user_meta($cuid,'last_name',1);
            $this->wh_id = $wh_id;
            $q = "select * from `".$wpdb->prefix.'wsd_dbc_wh_house'."` where `id` = '$wh_id'";    
            $wh = $wpdb->get_row($q,ARRAY_A);
            $this->tab_titles[] = $wh['title'];
            $this->datas[] = $this->data('list');
        }
        $roles = [];
        $roles[] = 'administrator';
        $roles[] = 'ml_administrator';
        
        $roles[] = 'wh_supervisor';
        $roles[] = 'wh_financier';
//        $roles[] = '';
        $this->use_limit = false;
        $this->datas = [];
        $this->tab_titles = [];
        if($ht->access($roles)){
//            $q = "select * from `".$wpdb->prefix.'wh_warehouse'."` where `id` = '$id'";    
            $q = "select * from `".$wpdb->prefix.'wsd_dbc_wh_house'."` ";      
//            $wh = $wpdb->get_row($q,ARRAY_A);
            $wh = $this->get_results($q,ARRAY_A);
//            echo '<pre>'.print_r($wh).'</pre>';
            foreach ($wh as $k => $v) {
//            echo '<pre>'.print_r($v).'</pre>';
                $wh_id = $v['id'];
                $this->wh_id = $wh_id;
                $this->data = [];
                $this->tab_titles[] = $v['title'];
                $this->datas[] = $this->data('list');
            }
//            $this->data = $this->data('list');
        }
    }
    public function report_2(){
//        add_log(strtr(__METHOD__,['::'=>' :: ']).'; <br/>main_tab '.$this->main_tab.'; tab '.$this->tab.';'
//                . ' action '.$this->action.'; mode '.$this->mode.';');
        $this->title = 'реагенты и сопутствующей материал для проведения исследований';
        $this->table ( 'wh_material' );
//        $this->data = $this->data('list');
    }
    public function report_3(){
//        add_log(strtr(__METHOD__,['::'=>' :: ']).'; <br/>main_tab '.$this->main_tab.'; tab '.$this->tab.';'
//                . ' action '.$this->action.'; mode '.$this->mode.';');
        $this->title = 'количество выполнены исследований/тестов за определенный период до
определенного смены расходуемого материала ';
        $this->table ( 'wh_material' );
//        $this->data = $this->data('list');
    }
    public function report_4(){
//        add_log(strtr(__METHOD__,['::'=>' :: ']).'; <br/>main_tab '.$this->main_tab.'; tab '.$this->tab.';'
//                . ' action '.$this->action.'; mode '.$this->mode.';');
        $this->title = 'учет рабочего времени сотрудников';
        $this->table ( 'wh_material' );
//        $this->data = $this->data('list');
    }
    public function report_5(){
//        add_log(strtr(__METHOD__,['::'=>' :: ']).'; <br/>main_tab '.$this->main_tab.'; tab '.$this->tab.';'
//                . ' action '.$this->action.'; mode '.$this->mode.';');
        $this->title = 'расчет себестоимости выполнения исследования учитывая количество выполнены
исследований/тестов за определенный период до определенного смены
расходуемого материала';
        $this->table ( 'wh_material' );
//        $this->data = $this->data('list');
    }
    public function report_6(){
//        add_log(strtr(__METHOD__,['::'=>' :: ']).'; <br/>main_tab '.$this->main_tab.'; tab '.$this->tab.';'
//                . ' action '.$this->action.'; mode '.$this->mode.';');
        $this->title = 'акт сверки сопоставления расходуемого товара с товаром на складах , для
выявления потерь';
        $this->table ( 'wh_material' );
//        $this->data = $this->data('list');
    }
    public function report_7(){
//        add_log(strtr(__METHOD__,['::'=>' :: ']).'; <br/>main_tab '.$this->main_tab.'; tab '.$this->tab.';'
//                . ' action '.$this->action.'; mode '.$this->mode.';');
        $this->title = 'акт принятие всего товара на основной склад';
        $this->table ( 'wh_material' );
//        $this->data = $this->data('list');
    }
    public function report_8(){
//        add_log(strtr(__METHOD__,['::'=>' :: ']).'; <br/>main_tab '.$this->main_tab.'; tab '.$this->tab.';'
//                . ' action '.$this->action.'; mode '.$this->mode.';');
        $this->title = 'акт отгрузки товара на каждом из складе';
        $this->table ( 'wh_material' );
//        $this->data = $this->data('list');
    }
    public function report_9(){
//        add_log(strtr(__METHOD__,['::'=>' :: ']).'; <br/>main_tab '.$this->main_tab.'; tab '.$this->tab.';'
//                . ' action '.$this->action.'; mode '.$this->mode.';');
        $this->title = 'акт сверки по внешнему складу / сопоставления расходуемого товара с
отгруженным товаром на складе , для выявления потерь';
        $this->table ( 'wh_material' );
//        $this->data = $this->data('list');
    }
    public function report_10(){
//        add_log(strtr(__METHOD__,['::'=>' :: ']).'; <br/>main_tab '.$this->main_tab.'; tab '.$this->tab.';'
//                . ' action '.$this->action.'; mode '.$this->mode.';');
        $this->title = 'список передвижений товара с основного склада';
        $this->table ( 'wh_material' );
//        $this->data = $this->data('list');
    }
    public function report_11(){
//        add_log(strtr(__METHOD__,['::'=>' :: ']).'; <br/>main_tab '.$this->main_tab.'; tab '.$this->tab.';'
//                . ' action '.$this->action.'; mode '.$this->mode.';');
        $this->title = 'акт привития, получения списка товарных накладных';
        $this->table ( 'wh_material' );
//        $this->data = $this->data('list');
    }
    public function common(){
//        add_log(strtr(__METHOD__,['::'=>' :: ']).'; <br/>main_tab '.$this->main_tab.'; tab '.$this->tab.';'
//                . ' action '.$this->action.'; mode '.$this->mode.';');
//        $this->data = $this->data('list');
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

if(isset($WhReport_activate) && $WhReport_activate === false){
    
}else
$DBCWhReport = new DBCWhReport();
