<?php

/* 
 * weybill_item.php
 * [ml_warehouse_product_shipment ]
 */

include_once 'class.DBConst.php';

class  DBCWhWeybillItem extends DBConst
{
    public $table = 'wh_weybill_item';
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
    public function __construct($tab=false,$act=false,$mode = 'normal') {
//        global $ht;
//        $tab = $ht->postget('tab','notab',FILTER_SANITIZE_STRING);
//        switch($tab){
//            case '':
//                break;
//            default:
//                break;
//        }
//        $tab = false;
////        $tab = null;
//        $tab = true;
//        $tab = '';
////        $tab = '0';
//        switch($tab){
//            case 2:
//                add_log( 'case 2' );
//                break;
//            case '2':
//                add_log( 'case 2 s' );
//                break;
//            case 1:
//                add_log( 'case 1' );
//                break;
//            case true:
//                add_log( 'case tr' );
//                break;
//            case false:
//                add_log( 'case f' );
//                break;
//            case '0':
//                add_log( 'case 0 s' );
//                break;
//            case 0:
//                add_log( 'case 0 i' );
//                break;
//            case '':
//                add_log( 'case no' );
//                break;
//            default:
//                add_log( 'case d' );
//                break;
//        }
        $this->page = 'weybill_item';
        $this->filter_use = 0;
        $this->filters = [''=>''];
        $this->tools_def = [];
        $this->main_tab = $tab;
        $this->main_act = $act;
        $this->mode = $mode;
        $this->styles = ['prod_getting'];
//        $this->scripts = ['prod_shipment'];
        $this->tabs_use = 0;
        $this->tabs = ['items'=>''];
//        $this->tabs = ['main'=>'Отгрузка товара','sent'=>'Отправленные накладные','received'=>'Полученные накладные','items'=>''];
        
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
//            $this->tabs['items'] = 'Товары в накладной';
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
    /**
     * update current class parameters
     * beafore build query for select list items
     * used in method "data" of parent class
     * concretize "where" paremeter
     * for main list
     * 
     */
    public function data_list_before_build_query(){
//        add_log(__METHOD__.'; main_tab '.$this->main_tab.'; tab '.$this->tab.'; action '.$this->action.'; mode '.$this->mode.';');
//        if(1)return ;
//        add_log('tab '.$this->tab.'; action '.$this->action.';');
        if($this->tab == 'sent' && !$this->action){
            $cuid = get_current_user_id();
            $current_house_id = 1;
//            $this->where['sender'] = $cuid;
            $this->where['house_from'] = $current_house_id;
//            $this->where['house_to'] = $current_house_id;
        }
        if($this->tab == 'received' && !$this->action){
            $cuid = get_current_user_id();
            $current_house_id = 1;
//            $this->where['sender'] = $cuid;
//            $this->where['house_from'] = $current_house_id;
            $this->where['house_to'] = $current_house_id;
        }
        if($this->tab == 'items' ){
            global  $wpdb,$ht;
//            add_log('tab '.$this->tab.'; action '.$this->action.';');
//            add_log($this->select);
//            add_log($this->join);
//            add_log($this->where);
            
            // b c d e
            // f g h i j
            $this->select['material_id'] = " concat(f.`title`,' -- ',i.`index`, '-',c.`stillage`, '-', c.`board`) as 'material_id'";
            $this->select['material_id_id'] = " a.`material_id` as 'material_id_id'";
//            $this->join[] = " left join `".$wpdb->prefix.'wsd_dbc_'.'wh_material'."` f on f.id = a.`material_id`";
            $this->join[] = " left join `".$wpdb->prefix.'wsd_dbc_'.'wh_material_name'."` f on f.id = c.`name_id`";
            $this->join[] = " left join `".$wpdb->prefix.'wsd_dbc_'.'wh_material_type'."` g on g.id = f.`category_id`";
            $this->join[] = " left join `".$wpdb->prefix.'wsd_dbc_'.'wh_manufacturer'."` h on h.id = f.`factory_id`";
            $this->join[] = " left join `".$wpdb->prefix.'wsd_dbc_'.'wh_house_group'."` i on i.id = c.`group_id`";
            $q="select d.`id` as 'value',"
//                . " concat(a.`user_nicename`,' -- ',b.`meta_value`,' ',c.`meta_value`,' ',d.`meta_value`,' ') as 'title',"
                . " concat(a.`title`,' -- ',e.`index`, '-',d.`stillage`, '-', d.`board`) as 'title',"
                . " 0 as 'parent', d.`id` as 'id'"
                . " from `".$wpdb->prefix.'wsd_dbc_'.'wh_material'."` d"
                . " left join `".$wpdb->prefix.'wsd_dbc_'.'wh_material_name'."` a on a.id = d.`name_id`"
                . " left join `".$wpdb->prefix.'wsd_dbc_'.'wh_material_type'."` b on b.id = a.`category_id`"
                . " left join `".$wpdb->prefix.'wsd_dbc_'.'wh_manufacturer'."` c on c.id = a.`factory_id`"
                . " left join `".$wpdb->prefix.'wsd_dbc_'.'wh_house_group'."` e on e.id = d.`group_id`"
                //. " where ".implode(' or ',$w)
                ;
//                $this->select = $select;
//                $this->join = $join;
            if($this->mode == 'weybill_children'){
                $wb_id = $ht->postget('id',0,FILTER_SANITIZE_NUMBER_INT);
                $this->where['wb_id'] = $wb_id;
//                add_log($this->prepare_query($q));
            }
        }
//        add_log($this->where);
    }
    public function data_list_after_build_query(&$sel,&$q_){
//        $this->show_tpl_file_name = true;
//        add_log(strtr(__METHOD__,['::'=>' :: ']).'; <br/>main_tab '.$this->main_tab.'; tab '.$this->tab.';'
//                . ' action '.$this->action.'; mode '.$this->mode.';');
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
        global  $wpdb;
//        if(1)return ;
//        if(!in_array($name,['sender','receiver']))return ;
        if(!in_array($name,['material_id']))return ;  
//        add_log(__FUNCTION__.' <b>'.$name.'</b>');
//        add_log('tab '.$this->tab.'; action '.$this->action.';');
//        add_log($q_);
        if($name == 'material_id'){
        
//            $q = "select a.`title` as 'title', d.`id` as 'id', a.`catalog_num` as 'catalog',"
//                . " b.`title` as  'category', c.`title` as 'factory',"
//                . " d.`stillage` as  'stillage', d.`board` as 'board',"
//                . " concat(e.`index`, '-',d.`stillage`, '-', d.`board`) as 'number'"
//                . " from `".$wpdb->prefix.'wsd_dbc_'.'wh_material'."` d"
//                . " left join `".$wpdb->prefix.'wsd_dbc_'.'wh_material_name'."` a on a.id = d.`name_id`"
//                . " left join `".$wpdb->prefix.'wsd_dbc_'.'wh_material_type'."` b on b.id = a.`category_id`"
//                . " left join `".$wpdb->prefix.'wsd_dbc_'.'wh_manufacturer'."` c on c.id = a.`factory_id`"
//                . " left join `".$wpdb->prefix.'wsd_dbc_'.'wh_house_group'."` e on e.id = d.`group_id`"
//                //. " where ".implode(' or ',$w)
//                ;
        
            $q="select d.`id` as 'value',"
//                . " concat(a.`user_nicename`,' -- ',b.`meta_value`,' ',c.`meta_value`,' ',d.`meta_value`,' ') as 'title',"
                . " concat(a.`title`,' -- ',e.`index`, '-',d.`stillage`, '-', d.`board`) as 'title',"
                . " 0 as 'parent', d.`id` as 'id'"
                . " from `".$wpdb->prefix.'wsd_dbc_'.'wh_material'."` d"
                . " left join `".$wpdb->prefix.'wsd_dbc_'.'wh_material_name'."` a on a.id = d.`name_id`"
                . " left join `".$wpdb->prefix.'wsd_dbc_'.'wh_material_type'."` b on b.id = a.`category_id`"
                . " left join `".$wpdb->prefix.'wsd_dbc_'.'wh_manufacturer'."` c on c.id = a.`factory_id`"
                . " left join `".$wpdb->prefix.'wsd_dbc_'.'wh_house_group'."` e on e.id = d.`group_id`"
                //. " where ".implode(' or ',$w)
                ;
            $q_ = $q;
        }
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
//        add_log($q_);
    }
    public function filter__form_field__status__select_vars($ret = null,$attr=[]){
//        add_log('main_tab '.$this->main_tab.'; tab '.$this->tab.'; action '.$this->action.'; mode '.$this->mode.';');
//        add_log(strtr(__METHOD__,['::'=>' :: ']).'; <br/>main_tab '.$this->main_tab.'; tab '.$this->tab.'; action '.$this->action.'; mode '.$this->mode.';');
        if($this->tab == 'items'){
            if($this->action == 'weybillget'){
                if($this->mode == 'weybill_children'){
    //                <select name="status" id="" class=""><option value="0" selected="selected" id="" class="">Создано</option>
    //                    <option value="1" id="" class="">Отправил</option>
    //                    <option value="2" id="" class="">Принял</option>
    //                    <option value="3" id="" class="">Пришло не полностью</option>
    //                    <option value="4" id="" class="">Не пришло</option></select>
                    $ret = [];
//                    $ret[2]='Создано';
//                    $ret[3]='Отправил';
                    $ret[2]='Принял';
                    $ret[3]='Пришло не полностью';
                    $ret[4]='Не пришло';
                }
            }
            if($this->action == 'weybill'){
                if($this->mode == 'weybill_children'){
                    $ret = [];
                    $ret[2]='Принял';
                    $ret[3]='Пришло не полностью';
                    $ret[4]='Не пришло';
                }
            }
        }
//        add_log($ret);
        return $ret;
    }
    public function filter__form_field__status__name($ret = null,$attr=[]){
//        add_log('main_tab '.$this->main_tab.'; tab '.$this->tab.'; action '.$this->action.'; mode '.$this->mode.';');
//        add_log(strtr(__METHOD__,['::'=>' :: ']).'; <br/>main_tab '.$this->main_tab.'; tab '.$this->tab.'; action '.$this->action.'; mode '.$this->mode.';');
        if($this->tab == 'items'){
            if($this->action == 'weybillget'){
                if($this->mode == 'weybill_children'){
                    $id = 0;
                    if(isset($attr['row']) && isset($attr['row']['id']) ) $id = $attr['row']['id'];
                    $ret = "wbitems[$id][status]";
                }
            }
            if($this->action == 'weybill'){
                if($this->mode == 'weybill_children'){
                }
            }
        }
//        add_log($ret);
        return $ret;
    }
    public function filter__form_field__comment__name($ret = null,$attr=[]){
//        add_log('main_tab '.$this->main_tab.'; tab '.$this->tab.'; action '.$this->action.'; mode '.$this->mode.';');
//        add_log(strtr(__METHOD__,['::'=>' :: ']).'; <br/>main_tab '.$this->main_tab.'; tab '.$this->tab.'; action '.$this->action.'; mode '.$this->mode.';');
        if($this->tab == 'items'){
            if($this->action == 'weybillget'){
                if($this->mode == 'weybill_children'){
                    $id = 0;
                    if(isset($attr['row']) && isset($attr['row']['id']) ) $id = $attr['row']['id'];
                    $ret = "wbitems[$id][comment]";
                }
            }
            if($this->action == 'weybill'){
                if($this->mode == 'weybill_children'){
                }
            }
        }
//        add_log($ret);
        return $ret;
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
    public function items(){
        add_log(strtr(__METHOD__,['::'=>' :: ']).'; <br/>main_tab '.$this->main_tab.'; tab '.$this->tab.'; action '.$this->action.'; mode '.$this->mode.';');
            if($this->dbconst_table == 'wh_weybill_item')add_log($this->fields_info);
//        add_log('main');
        $this->data = $this->data('list');
    }
    public function weybillget(){
//        add_log(strtr(__METHOD__,['::'=>' :: ']).'; <br/>'
//                .'main_tab '.$this->main_tab.'; tab '.$this->tab.'; action '.$this->action.'; mode '.$this->mode.';');
//        if(${'_POST'})add_log(${'_POST'});
//            if($this->dbconst_table == 'wh_weybill_item')add_log($this->fields_info);
//        add_log('main');
        $this->data = $this->data('list');
    }
    public function weybill(){
//            if($this->dbconst_table == 'wh_weybill_item')add_log($this->fields_info);
//        add_log('main');
        $this->data = $this->data('list');
    }
//    public function main(){
//            if($this->dbconst_table == 'wh_weybill_item')add_log($this->fields_info);
//        
//    }
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
if(isset($WeybillItem_activate) && $WeybillItem_activate === false){
    
}else
    $DBCWhWeybillItem = new DBCWhWeybillItem();
