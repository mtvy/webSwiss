<?php

/* 
 * class.DBConst.php
 */

define('WAREHOUSEUPLOADS',ABSPATH.'wp-content/uploads/ml_warehouse/');


class  DBConst
{
    public $dbconst_table = ''; // database table
    public $table = ''; // database table
    public $title = ''; // 
    public $tab_title = ''; // 
    public $tab_titles = []; // 
    
    public $house_own = 0;
    
    public $filter_use = true;
    public $filters = [];
    public $filter = [];
    public $filtered = [];
    public $filter_def = [];
    
    public $order = [];
    public $ordered = [];
    public $orderdef = ['id'=>'desc'];
    public $orderpref = ['id'=>'a'];
    
    public $select = [];
    public $join = [];
    public $where = [];
    public $id = 0;
    public $data = [];
    public $datas = [];
    public $pager = 0;
    public $pager_use = false;
    public $pager_by = 50;
    public $items_count = 0;
    public $use_limit = true;
    public $pages = [];
    public $page = 'warehouse';
    public $page_name = 'warehouse';
    public $actions = [];
    public $action = '';
    public $conferm = false;
    public $tab = 'main';
    public $tabs = ['main'];
    public $tabs_use = true;
    public $tabs_name = ['main'=>'main'];
    public $tools = [];
    public $tools_def = ['main','create','edit','delete'];
    public $tool_access = ['administrator'];
    
    public $show_admin = [];
    public $show_public = [];
    public $titles_admin = [];
    public $titles_public = [];
    public $descriptions = [];
    public $helps = [];
    
    public $labels_filter = [];
    public $labels_list = [];
    public $labels_form = [];
    public $form_action = '';
    
    public $form_fields = [];
    public $form_hidden = [];
    public $form_filter = [];
    public $form_type = null;
    public $form_pattern = [];
    public $fields_info = [];
    public $form_title = '';
    public $weight_fields = [];
    
    public $is_tree = false;
    public $tree_parent = '';
    public $field_form_tree = []; // fields used with tree
    public $field_form_tree_parent = []; // field in out table used as parent for tree
    
    public $styles = [];
    public $style_inline = [];
    
    public $scripts = ['common'];
    public $scripts_inline = [];
    
    public $router = '';
    public $add_return_url = []; // list tools needed uppend return urls
    public $debug = false;
    public $show_tpl_file_name = false;
    public $show_access_status = false;
    
//    public $def_tab = 'main';
    public $def_tab = '';
    public $main_tab = false;
    public $main_act = false;
    public $mode = 'normal';
    
    public function __construct() {
//        $this->debug=true;
//        $this->orderdef = ['id'=>'desc'];
        
        $this->init();
        $this->init_filter();
        $this->controller();
        $this->filter();
//        $this->data();
//        $this->update();
        $this->show(1);
    }
    public function table($t){
        $this->table = $t;
        $this->dbconst_table = ''.$this->table;
        $this->table = 'wsd_dbc_'.$this->table;
    }
    public function init(){
        global $wpdb, $ht;
        $cuid = get_current_user_id();
        $q = "select `meta_value` from `".$wpdb->prefix."usermeta` where `user_id` = '$cuid' and `meta_key` = 'warehouse_id'";
        $current_house_id = $wpdb->get_var($q);
        $this->house_own = $current_house_id;

        $this->actions = ['main','item','create','edit','delete'];
        $this->page_name = $this->page;
        $this->dbconst_table = ''.$this->table;
        $this->table = 'wsd_dbc_'.$this->table;
        
        $weight_fields = [];
        $weight_fields[] = 'weigh';
        $weight_fields[] = 'weight';
        $this->weight_fields = $weight_fields;
        
        $dbconst_tables = $wpdb->prefix . "wsd_dbconst_tables";
        $q= "select `title` from `$dbconst_tables` where `table` = '".$this->dbconst_table."' ";
        $this->form_title = $wpdb->get_var($q);
            
        $dbconst_schema = $wpdb->prefix . "wsd_dbconst_schema";
        $dbconst_tables = $wpdb->prefix . "wsd_dbconst_tables";
        $dbconst_fields = $wpdb->prefix . "wsd_dbconst_fields";
        $q= "select id from `$dbconst_tables` where `table` = '".$this->dbconst_table."' ";
        $tab_id = $wpdb->get_var($q);
        if($tab_id){
            $q= "select * from `$dbconst_fields` where `isprimary` != 1 and `tab_id`= $tab_id order by `weigh`";
            $q= "select * from `$dbconst_fields` where  `tab_id`= $tab_id and `active` = 1 order by `weigh`";
            $fields = $wpdb->get_results($q,ARRAY_A);
            $this->fields_info = [];
            foreach($fields as $field){
                $k = $field['field'];
                $this->fields_info[$k] = $field;
            }
            
//            if($this->dbconst_table == 'wh_weybill_item')add_log($this->fields_info);
//            if($this->dbconst_table == 'wh_weybill_item')add_log(array_keys($this->fields_info));
            
            $sel_cou = "select count(*) from `".$wpdb->prefix.$this->table."` as a ";
            $this->items_count = $wpdb->get_var($sel_cou);
            if($this->weight_fields){
                foreach($this->weight_fields as $wf){
                    if(!array_key_exists($wf, $this->fields_info))continue;
                    $this->fields_info[$wf]['def'] = $this->items_count;
                }
            }
//            add_log($this->fields_info);
            $this->show_admin = [];
            $this->titles_admin = [];
            $q= "select * from `$dbconst_fields` where `show_admin` > 0 and `tab_id`= $tab_id and `active` = 1 order by `weigh_admin`";
            $fields = $wpdb->get_results($q,ARRAY_A);
            foreach($fields as $field){
                $k = $field['field'];
                $this->show_admin[] = $k;
                $this->titles_admin[] = $field['title'];
            }
            
            $this->show_public = [];
            $this->titles_public = [];
            $q= "select * from `$dbconst_fields` where `show_public` > 0 and `tab_id`= $tab_id and `active` = 1 order by `weigh_public`";
            $fields = $wpdb->get_results($q,ARRAY_A);
            foreach($fields as $field){
                $k = $field['field'];
                $this->show_public[] = $k;
                $this->titles_public[] = $field['title'];
            }
            
            $this->descriptions = [];
            $this->helps = [];
            $q= "select * from `$dbconst_fields` where `tab_id`= $tab_id and `active` = 1 order by `weigh_public`";
            $fields = $wpdb->get_results($q,ARRAY_A);
            foreach($fields as $field){
                $k = $field['field'];
                $this->descriptions[$k] = $field['desc'];
                $this->helps[] = $field['help'];
            }
            $order = [0=>'ASC',1=>'DESC'];
            $q= "select * from `$dbconst_fields` where `order` = '1' and `tab_id`= $tab_id and `active` = 1 order by `weigh_order`";
            $fields = $wpdb->get_results($q,ARRAY_A);
            if(count($fields)){
                $this->orderdef = [];
                foreach($fields as $field){
                    $k = $field['field'];
                    $this->orderdef[$k] = $order[$field['order_as']];
                }
            }
//                add_log($this->orderdef);
//            add_log($q);
            if($this->debug)add_log($this->show_public);
        }
    }
    public function init_form(){
        global $wpdb, $ht;
//        $this->form_fields = [
//            'title'=>'title'
//            ];
//        $this->form_filter = [
//            'title'=>FILTER_SANITIZE_STRING
//            ];
//        $this->form_pattern = [
//            'title'=>null
//            ];
        
        
        $dbconst_schema = $wpdb->prefix . "wsd_dbconst_schema";
        $dbconst_tables = $wpdb->prefix . "wsd_dbconst_tables";
        $dbconst_fields = $wpdb->prefix . "wsd_dbconst_fields";
        $q= "select id from `$dbconst_tables` where `table` = '".$this->dbconst_table."' ";
        $tab_id = $wpdb->get_var($q);
//        add_log($this->dbconst_table);
//        add_log($tab_id);
        if($tab_id && $wpdb->get_var("SHOW TABLES LIKE '$dbconst_fields'") == $dbconst_fields) {
            $q= "select * from `$dbconst_fields` where `isprimary` != 1 and `tab_id`= $tab_id and `active` = 1 order by `weigh`";
            $fields = $wpdb->get_results($q,ARRAY_A);
            $this->fields_info = [];
//            add_log($fields);
            foreach($fields as $field){
                $k = $field['field'];
                $this->fields_info[$k] = $field;
                $this->form_fields[$k] = $k;
                $this->form_filter[$k] = $field['filter'];
                $this->form_pattern[$k] = null;
                $this->labels_form[$k] = $field['title'];
            }
            $sel_cou = "select count(*) from `".$wpdb->prefix.$this->table."` as a ";
            $this->items_count = $wpdb->get_var($sel_cou);
            if($this->weight_fields){
                foreach($this->weight_fields as $wf){
                    if(!array_key_exists($wf, $this->fields_info))continue;
                    $this->fields_info[$wf]['def'] = $this->items_count+1;
                }
            }
        }
        
        if($this->action)
        $this->form_hidden[] = $ht->f('input','',['type'=>'hidden','name'=>'form_type','value'=>$this->action]);
        if($this->id)
        $this->form_hidden[] = $ht->f('input','',['type'=>'hidden','name'=>'id','value'=>$this->id]);
        $this->form_hidden[] = $ht->f('input','',['type'=>'hidden','name'=>'return','value'=>$this->return]);
        if($this->action == 'delete')
        $this->form_hidden[] = $ht->f('input','',['value'=>'ok','type'=>'hidden','name'=>'conferm']);
    }
    public function form_label($name=false,$use_asterix = 1){
        $out = '';
        if(isset($this->labels_form[$name]))
            $out .= $this->labels_form[$name];
        if($use_asterix && isset($this->fields_info[$name]) && $this->fields_info[$name]['required'])
            $out .= '<upper><font color="red">*</font></upper>';
        $out = '<b>'.$out.'</b>';
        if(isset($this->descriptions[$name]))
        $out .= '<br/>' . $this->descriptions[$name];
        return $out;
    }
    public function form_btn_submit($class='',$conferm=false){
        global $ht;
        $val = 'Сохранить';
        switch($this->action){
            case 'create':$val = 'Создать';break;
            case 'edit':$val = 'Изменить';break;
            case 'delete':$val = 'Удалить';break;
        }
        $onclick = '';
        if($conferm)$onclick = "return confirm('".$conferm."');";
        $btn = $ht->f('button',$val,['type'=>'submit','name'=>'sumbit','class'=>$class,'value'=>$this->action,'onclick'=>$onclick]);
        return $btn;
    }
    public function form_field($name=false,$class='',$onlytext=false,$row=[]){
        global $wpdb, $ht;
//        add_log(array_keys($this->fields_info));
//        add_log($this->fields_info[$name]);
//        add_log($name);
        $out = '';
        if(!$name)return $out;
        if(!isset($this->fields_info[$name]))return $out;
//        add_log('form_field');
        $field = $this->fields_info[$name];
        $tpl = $this->fields_info[$name]['tpl'];
        $value = $this->fields_info[$name]['def'];
        if(isset($this->data[$name]))
            $value = $this->data[$name];
        
//        if($name == 'status')add_log($value);
//        if($name == 'comment')add_log($value);
//        if($name == 'comment')add_log($this->data);
        
        $value = $this->do_filter('filter__form_field__'.$name.'__value',$value);
        $field_name = $this->do_filter('filter__form_field__'.$name.'__name',$name,['row'=>$row]);
        
//        if($name == 'status')add_log($value);
//        if($name == 'comment')add_log($value);
        
        if(($this->fields_info[$name]) && $this->fields_info[$name]['isprimary'])
            $tpl = 'textonly';
        
//                    $tps = [];
//                    $tps['td_ta_'] = 'textarea';
//                    $tps['td_i_'] = 'text';
//                    $tps['td_d_'] = 'number';
////                    $tps['td_o_'] = 'number';
//                    $tps['td_s_'] = 'select';
//                    $tps['td_s_from_'] = 'select from';
//                    $tps['td_t_'] = 'text only';
                    
        if($onlytext && in_array($tpl,['td_ta_','td_d_','td_i_','',]))$tpl = 'td_t_';
        switch($tpl){
            case 'td_ta_': // textarea
                $courow = count(explode("\n",$value));
                $out = $ht->f('textarea',$value,['name'=>$field_name,'rows'=>$courow+4]);
                break;
            case 'td_d_': // number
                $out = $ht->f('input','',['value'=>$value,'type'=>'number','name'=>$field_name]);
                break;
            case 'td_i_': // text
                $out = $ht->f('input','',['value'=>$value,'type'=>'text','name'=>$field_name]);
                break;
            case 'td_t_': // text only
                $out .= $value;
//                $this->form_hidden[] = $ht->f('input','',['value'=>$value,'type'=>'hidden','name'=>$name]);
                break;
            case 'td_s_': // select
                $vars = $field['vars'];
                $vars = unserialize($vars);
                $vars = $this->do_filter('filter__form_field__'.$name.'__select_vars',$vars);
                if($onlytext){
                    if(isset($vars[$value]))
                        $out .= $vars[$value];
                    else
                        $out .= $value;
                }else{
                    $out = $ht->select($field_name,$vars,$value);
                }
                break;
            case 'td_s_dl_': // select
                $vars = $field['vars'];
                $vars = unserialize($vars);
                $vars = $this->do_filter('filter__form_field__'.$name.'__select_vars',$vars);
                if($onlytext){
                    if(isset($vars[$value]))
                        $out .= $vars[$value];
                    else
                        $out .= $value;
                }else{
                    $out = $ht->select($field_name,$vars,$value);
                }
                break;
            case 'td_s_from_': // select from
            case 'td_s_from_dl_': // select from
                ob_start();
                $parent_f = 0;
                $from = $field['from_table'];
                $select = $field['from_value'];
                $title = $field['from_title'];
                
                // select
                        $field_f = "\n`".$field['field']."`";
                        $titles = $field['from_title'];
//                add_log(' ========== ');
//                            add_log(strlen($titles));

                        $titles = explode(',',$titles);
                        $v = [];
                        foreach($titles as $tn=>$t){
                            $t = html_entity_decode($t,ENT_QUOTES);
//                            add_log($t);
//                            add_log(trim($t));
//                            add_log(trim($t)[0]);
//                            add_log(str_split($t));
//                            add_log(trim($t,"'"));
//                            add_log(strlen(trim($t)));
//                            add_log(strlen(trim($t))-1);
//                            add_log(trim($t)[strlen(trim($t))-1]);
//                add_log(' ---------- ');
//                            $t = trim($t);
                            if(!strlen(trim($t))){$v[]="'$t'";}
                            else
                            if(strlen(trim($t))>1 && trim($t)[0]=="'" && trim($t)[strlen(trim($t))-1]=="'"){
                                $t = trim($t);
                                $t = trim($t,"'");
                                $v[]="'$t'";}
                            else{
                                $t = trim($t);
                                $v[]="`$t`";
//                                $v[]="$join_t.`$t`";
//                                if($tn+1 < count($titles))
//                                $v[]="' '";
                            }
                        }
//                add_log($v);
                        if(strlen(trim($field['from_value']))&&count($v)>0){
                            $v2  = implode(',',$v);
                            if(count($v)>1)
                            $field_f = "concat($v2)";
                            else
                            $field_f = "$v2";
                        }
                        $title = $field_f;
                // where
                $where = $field['from_where'];
                $w = [];
                $w[] = '1';
                if($where)$w[] = $where;
                
                // order by
                $ordered = [];
                if(strpos($from,'wsd_dbc_')===0){
                    $dbconst_schema = $wpdb->prefix . "wsd_dbconst_schema";
                    $dbconst_tables = $wpdb->prefix . "wsd_dbconst_tables";
                    $dbconst_fields = $wpdb->prefix . "wsd_dbconst_fields";
                    $dbconst_table = substr($from,strlen('wsd_dbc_'));
//                    add_log($dbconst_table);
                    $q= "select id from `$dbconst_tables` where `table` = '".$dbconst_table."' ";
                    $tab_id = $wpdb->get_var($q);
                    $order = [0=>'ASC',1=>'DESC'];
                    $q= "select * from `$dbconst_fields` where `order` = '1' and `tab_id`= $tab_id order by `weigh_order`";
                    $fields = $wpdb->get_results($q,ARRAY_A);
                    foreach($fields as $field){
                        $k = $field['field'];
                        $ordered[$k] = $order[$field['order_as']];
                    }
                }
//                    add_log($ordered);
            
                // query
                if(in_array($name, $this->field_form_tree) && isset($this->field_form_tree_parent[$name])){
                    $parent_f = '`'.$this->field_form_tree_parent[$name].'`';
                }
                $q = "select `$select` as 'value', $title as 'title', $parent_f as 'parent', `id` as 'id' from `".$wpdb->prefix.$from."` where ";
                $q .= implode(" and ",$w);
                
                // build order
                if(count($ordered)){
//                    $ordered = $this->build_order($ordered);
                    foreach($ordered as $k => $v){
                        $ordered[$k] = "`$k` $v";
                    }
                    $q .= "\norder by ";
                    $q .= implode(", ", $ordered);
                    $q .= " ";
                }else{
                    $q .= "\norder by `id` desc ";
                }
                if(method_exists($this, 'field_q_prepare_select_from')){
                    $this->field_q_prepare_select_from($name,$q);
                }
                
                // query result
//                $result = $wpdb->get_results($q,ARRAY_A);
                $result = $this->get_results($q,ARRAY_A);
                if(!$result){
                    add_log($name);
                    add_log($q);
                    add_log( $this->prepare_query($q));
                    
                }
                
                // build tree
                if(in_array($name, $this->field_form_tree) && isset($this->field_form_tree_parent[$name])){
                    $struct = [];
                    $parents = [];
                    foreach($result as $row){
//                        $k = $row['id'];
                        $k = $row['value'];
                        $p = $row['parent'];
//                        $parents[$k]['value'] = $row['value'];
                        $parents[$k]['value'] = $row['value'];
                        $parents[$k]['title'] = $row['title'];
                        $parents[$k]['ischild'] = 0;
                        if(!isset($parents[$k]['child']))$parents[$k]['child']=[];
                        if($p>0){
                            $parents[$k]['ischild'] = 1;
                            if(!isset($parents[$p]))$parents[$p]=[];
                            if(!isset($parents[$p]['child']))$parents[$p]['child']=[];
                            $parents[$p]['child'][$k] = &$parents[$k];
                        }else{
                            $struct[$k] = &$parents[$k];
                        }
                    }
//                    ksort($struct);
//                    ksort($parents);
//                    add_log($struct);
//                    add_log($parents);
                    $result = [];
                    $pref_num = 0;
                    foreach($struct as $k=>$row){
                        $pref=str_pad('',$pref_num*13,"&#8212;&nbsp;");
                        $result[$k] = [];
                        $result[$k]['value'] = $row['value'];
                        $result[$k]['title'] = $pref.$row['title'];
                        $this->build_tree($result,$pref_num+1,$row['child']);
                    }
//                    add_log('&mdash;');
//                    add_log('&#8212;');
//                    add_log($result);
                }
                
                // build tag select
                $vars = [];
//                $this->fields_info = [];
                if(!$onlytext && isset($this->fields_info[$name]) && !$this->fields_info[$name]['required'])
                    $vars[0] = 'Выбрать';
                foreach($result as $res){
                    $vars[$res['value']] = $res['title'];
                }
                $vars = $this->do_filter('filter__form_field__'.$name.'__select_vars',$vars);
                if($onlytext){
                    if(isset($vars[$value]))
                        $out .= $vars[$value];
                    else
                        $out .= $value;
                }else{
                    $out = $ht->select($field_name,$vars,$value);
                }
                
                $err = ob_get_clean();
                if($err)
                    add_log($err);
                
                break;
            default:
                break;
        }
        return $out;
    }
    public function build_tree(&$result,$pref_num,$struct){
        if(!$struct)return;
        foreach($struct as $k=>$row){
            $pref=str_pad('',$pref_num*13,"&#8212;&nbsp;");
            $result[$k] = [];
            $result[$k]['value'] = $row['value'];
            $result[$k]['title'] = $pref.$row['title'];
            $this->build_tree($result,$pref_num+1,$row['child']);
        }
    }
    public function init_filter(){
        $filter = [];
        $filter[''] = '';
        $this->filter=$filter;
    }
    public function filter(){
        global $ht;

        $tab = $ht->postget('tab',0,FILTER_SANITIZE_NUMBER_INT);
        $page = $ht->postget('pg',1,FILTER_SANITIZE_NUMBER_INT);
        $lab_g = $ht->postget('lab_g',0,FILTER_SANITIZE_NUMBER_INT);
        $cbid = $ht->postget('fid',0,FILTER_SANITIZE_NUMBER_INT);

        $date_from = $ht->postget('date-from',false,FILTER_VALIDATE_REGEXP, '/^[\d]{2}\.[\d]{2}\.[\d]{4}$/');
        $date_to = $ht->postget('date-to',false,FILTER_VALIDATE_REGEXP, '/^[\d]{2}\.[\d]{2}\.[\d]{4}$/');
        $this->filtered['date_from'] = $date_from;
        $this->filtered['date_to'] = $date_to;
        
        foreach($this->filter as $filter => $type){
            if($filter == 'date_from' || $filter == 'date_to')continue;
            $filter_type = FILTER_SANITIZE_NUMBER_INT;
            $def = 0;
            $regxp = null;
            if($type == 'string'){
                $filter_type = FILTER_SANITIZE_STRING;
                $def = '';
            }
            if(isset($this->filter_def[$filter])) $def = $this->filter_def[$filter];
            $this->filtered[$filter] = $ht->postget($filter,$def,$filter_type,$regxp);
        }
        
    }
    public function init_access(){
        global $ht;
        $tools = [];
        if($ht->access($this->tool_access)){// dir
            $tools = ['main','create','edit','delete'];
            $tools = $this->tools_def;
        }
//        add_log($this->tools);
//        $user = wp_get_current_user();
//        add_log((array) $user->roles);
        foreach($tools as $tool){
//            $url = get_edit_post_link($data['post_id']);
            $url = get_the_permalink( get_the_ID() ) ;
            $q =[];
            if($this->id && in_array($tool, ['edit','delete'])){
                $q['id'] = $this->id;
            }
            if($this->tab){
                $q['tab'] = $this->tab;
            }
            if($tool != 'main'){
                $q['act'] = $tool;
            }
            if(in_array($tool, ['edit'])){
                $q['return']=urlencode(($this->router ).'?id='.$this->id);
            }
            if(in_array($tool, ['create','delete'])){
                $q['return']=urlencode(($this->router ));
            }
            if(in_array($tool,$this->add_return_url)){
                $q['return']=urlencode(($this->router ));
            }
//                        add_log($q);
            $this->tools[$tool] = $ht->a($tool,$url,$q,['class'=>'btn btn-primary ml-1 text-white']);
        }
        if($this->action != 'item'){
            unset($this->tools['edit']);
            unset($this->tools['delete']);
        }
        if(in_array($this->action, ['create','edit','delete'])){
            unset($this->tools['create']);
            unset($this->tools['edit']);
            unset($this->tools['delete']);
        }
        if(in_array($this->action, ['create','edit','delete','item'])
//                || in_array($this->tab, ['items'])
                ){
            $this->init_form();
        }
        if(method_exists($this, 'init_access_after')){
            $this->init_access_after();
        }
//        add_log($this->tools);
    }
    public function controller(){
        global $ht;
//        $this->page = 'warehouse';
        $this->pager = $ht->postget('pg',1,FILTER_SANITIZE_NUMBER_INT);
        $this->pager -= 1;
        if($this->pager<0)$this->pager=0;
        $this->id = $ht->postget('id',0,FILTER_SANITIZE_NUMBER_INT);
        $this->tab = $ht->postget('tab','',FILTER_SANITIZE_STRING);
        $this->tab = $ht->postget('tab',$this->def_tab,FILTER_SANITIZE_STRING);
        $this->form_type = filter_input(INPUT_POST, 'form_type', FILTER_SANITIZE_STRING);
        $conf = filter_input(INPUT_POST, 'conferm', FILTER_SANITIZE_STRING);
        $this->return = $ht->postget('return','',FILTER_SANITIZE_STRING); // page address
        if($conf == 'ok')$this->conferm =true;
        $this->return = urldecode($this->return);
//        $this->return = urldecode($this->return);
//        add_log($conf);
        
        $order = $ht->postget('order','',FILTER_SANITIZE_NUMBER_INT);
        $orderby = $ht->postget('orderby',0,FILTER_SANITIZE_STRING);
        if(in_array($orderby, $this->order))$this->ordered[$orderby] = $order?'desc':'asc';
        else $this->ordered = $this->orderdef;
        
//        $this->action = '';
        $action = $ht->postget('act',$this->action,FILTER_SANITIZE_STRING);
        if($action)$this->action = $action;
        
        if($this->main_tab !== false ) $this->tab = $this->main_tab;
        if($this->main_act !== false ) $this->action = $this->main_act;
        

        $this->router = get_the_permalink( get_the_ID() ) ;
        $router = [];
        if($this->pager > 1) $router['pg']= $this->pager;
        if($this->tab ) $router['tab']= $this->tab;
        if($this->action ) $router['action']= $this->action;
        if($this->id ) $router['id']= $this->id;
        if($router)
            $this->router .= '?' . http_build_query($router);
        
//            if($this->dbconst_table == 'wh_weybill_item')add_log(array_keys($this->fields_info));
        $this->init_access();
//            if($this->dbconst_table == 'wh_weybill_item')add_log(array_keys($this->fields_info));
        
        if((!$this->action || $this->action == 'main') && $this->id ) $this->action = 'item';
        
//        add_log($this->action);
        if(!in_array($this->action,$this->actions))$this->action='main';
        
        if($this->main_tab !== false ) $this->tab = $this->main_tab;
        if($this->main_act !== false ) $this->action = $this->main_act;
        
        if(method_exists($this, 'common'))$this->common();
        
//        add_log('tab '.$this->tab);
//        add_log('action '.$this->action);common()
        if(method_exists($this, $this->action))$this->{$this->action}();
        //else 
        
//        add_log("method_exists( $this->tab) - ".(method_exists($this, $this->tab)?1:0));
        if($this->action=='main')$this->action='';
        if($this->tab != $this->action
                && !$this->action
                && in_array($this->tab,$this->actions)
                && method_exists($this, $this->tab)) $this->{$this->tab}();
//        add_log($this->action);
        
//        add_log('main_tab '.$this->main_tab.'; tab '.$this->tab.'; action '.$this->action.';');
        if($this->form_type)$this->update();
        
        $tab = '';
        $actn = '';
        $action = '';
        $tabaction = '';
        if($this->tab=='main' || !array_key_exists($this->tab,$this->tabs))$this->tab='';
//        if(strlen($this->tab) && !file_exists(__DIR__.'/page/'.$this->page.'-'.$this->tab.'.php')){
//            $this->tab = '';
//            $tab = $this->page.'-'.$this->tab;
//        }
//        
//        $tpl = '';
//        if(strlen($this->action)>0)$tpl = $this->page.'-'.$this->action;
//        if(strlen($tpl) && file_exists(__DIR__.'/page/'.$tpl.'.php'))$this->page = $tpl;
//        else if(strlen($this->action)>0 && in_array($this->action,['create','edit','delete']))$this->page = 'form'; // .php
//        else if(strlen($this->action)>0 && file_exists(__DIR__.'/page/'.$this->action.'.php'))$this->page = $this->action.''; // .php
        
        $pa = false;
        $tab = $this->page.'-'.$this->tab;
        $actn = $this->action;
        $action = $this->page.'-'.$this->action;
        $tabaction = $this->page.'-'.$this->tab.'-'.$this->action;
        if(file_exists(__DIR__.'/page/'.$tab.'.php'))$this->page = $tab;
        if(file_exists(__DIR__.'/page/'.$actn.'.php')){$this->page = $actn; $pa = 1;}
        if(file_exists(__DIR__.'/page/'.$action.'.php')){$this->page = $action; $pa = 1;}
        if(file_exists(__DIR__.'/page/'.$tabaction.'.php')){$this->page = $tabaction; $pa = 1;}
        if($this->action && !$pa && in_array($this->action,['create','edit','delete']))$this->page = 'form';
//         if($this->action
//                && !file_exists(__DIR__.'/page/'.$action.'.php')
//                && !file_exists(__DIR__.'/page/'.$tabaction.'.php')
//                && file_exists(__DIR__.'/page/'.$this->action.'.php'))$this->page = $this->action;
//        add_log($this->action);
//        add_log($this->page);
    }
    public function get(){
        
    }
    public function set(){
        
    }
    public function main(){
//        add_log('main');
        $this->data = $this->data('list');
    }
    public function item(){
        $this->data = $this->data('item');
    }
    public function create(){
        $this->form_title.=': Добавить';
//        add_log('function create');
        $this->data = $this->data('item');
    }
    public function edit(){
        $this->form_title.=': Изменить';
        $this->data = $this->data('item');
    }
    public function delete(){
        $this->form_title.=': Удалить';
        $this->data = $this->data('item');
    }
    public function update(){
        global $wpdb,$ht;
        if($this->debug)
            add_log(${'_POST'});
        if($this->debug)add_log($this->data);
        if(!$this->form_type)return;
        ob_start();
        $error = false;
        switch($this->form_type){
            case 'create':
                $set = [];
                ob_start();
                
                foreach ($this->fields_info as $key => $value) {
                    $set[] = "`$key` = '".$this->data[$key]."'";
                }
                
                $weight_fields = $this->weight_fields;
                $select_max = [];
//                add_log($weight_fields);
                if(count($weight_fields)){
                    foreach($weight_fields as $wf){
                        if(!array_key_exists($wf, $this->fields_info))continue;
                        $select_max[] = "max(`$wf`)+1 as '$wf'";
                    }
                    if(count($select_max)){
                        $select_max = implode(', ',$select_max);
                        $q= "select $select_max from `".$wpdb->prefix.$this->table."` where 1";
                        $old_fields = $wpdb->get_row($q,ARRAY_A);
                        foreach($old_fields as &$f_)if(!$f_)$f_=1;
            //            add_log($q);
    //                    add_log($old_fields);
                    }
                }
            
                $q = "insert into `".$wpdb->prefix.$this->table."` set ".implode(',',$set);
                $wpdb->query($q);
                
                if($wpdb->last_error == '') {
                    $id = $wpdb->insert_id;
                    if(count($select_max)){

                        $q= "select * from `".$wpdb->prefix.$this->table."` where `id` = '$id'";      
                        $field = $wpdb->get_row($q,ARRAY_A);

            //            add_log(['$field',$field]);
            //            add_log(['$old_fields',$old_fields]);
                        foreach($weight_fields as $wf){
                            if(!array_key_exists($wf, $field))continue;
                            $newweigh = $field[$wf];
                            $oldweigh = $old_fields[$wf];
                            $q = 'no q';
                            if($newweigh != $oldweigh && $id){
                                if($newweigh < $oldweigh){
                                    $q= "update `".$wpdb->prefix.$this->table."` set `$wf` = `$wf`+1 where "
                                            . "`id` <> '$id' and `$wf` >= '$newweigh' and `$wf` <= '$oldweigh' ";
                                }else{
                                    $q= "update `".$wpdb->prefix.$this->table."` set `$wf` = `$wf`-1 where "
                                            . "`id` <> '$id' and `$wf` <= '$newweigh' and `$wf` >= '$oldweigh' ";
                                }
                                $wpdb->query($q);
                            }
    //                        add_log([$newweigh,$oldweigh,$id,$wf]);
    //                        add_log($q);
                        }
                    }
                }
                        
                $err = ob_get_clean();
                if($err)
                    add_log($err);
                if($wpdb->last_error !== '') {
                    $error = true;
                    add_log($wpdb->last_error);
        //            add_log($q);
                }else{
                    $notice = '<div> Добавлена запись. </div>';
                    add_log( $notice);
                }
                break;
            case 'edit':
                if($this->id){
                    $set = [];
                    ob_start();
                    if($this->id){
                        $id = $this->id;

                        $q= "select * from `".$wpdb->prefix.$this->table."` where `id` = '$id'";      
                        $old_fields = $wpdb->get_row($q,ARRAY_A);

                        foreach ($this->fields_info as $key => $value) {
                            $set[] = "`$key` = '".$this->data[$key]."'";
                        }
                        $q = "update `".$wpdb->prefix.$this->table."` set ".implode(',',$set);
                        $q .=  " where `id` = '".$this->id."'";
                        $wpdb->query($q);

                        $q= "select * from `".$wpdb->prefix.$this->table."` where `id` = '$id'";      
                        $field = $wpdb->get_row($q,ARRAY_A);
                        $weight_fields = $this->weight_fields;

            //            add_log(['$field',$field]);
            //            add_log(['$old_fields',$old_fields]);
                        foreach($weight_fields as $wf){
                            if(!array_key_exists($wf, $field))continue;
                            $newweigh = $field[$wf];
                            $oldweigh = $old_fields[$wf];
                            $q = 'no q';
                            if($newweigh != $oldweigh && $id){
                                if($newweigh < $oldweigh){
                                    $q= "update `".$wpdb->prefix.$this->table."` set `$wf` = `$wf`+1 where "
                                            . "`id` <> '$id' and `$wf` >= '$newweigh' and `$wf` <= '$oldweigh' ";
                                }else{
                                    $q= "update `".$wpdb->prefix.$this->table."` set `$wf` = `$wf`-1 where "
                                            . "`id` <> '$id' and `$wf` <= '$newweigh' and `$wf` >= '$oldweigh' ";
                                }
                                $wpdb->query($q);
                            }
//                        add_log([$newweigh,$oldweigh,$id,$wf]);
//                        add_log($q);
                        }
                    }
                    
                    $err = ob_get_clean();
                    if($err)
                        add_log($err);
                    if($wpdb->last_error !== '') {
                        $error = true;
                        add_log($wpdb->last_error);
            //            add_log($q);
                    }else{
                        $notice = '<div> Запись изменена. </div>';
                        add_log( $notice);
                    }
                }else{
                    $notice = '<div> Отсутствует идентификатор. </div>';
                    add_log( $notice);
                }
                break;
            case 'delete':
                if($this->conferm){
                    if($this->id){
                        ob_start();
                        $q = "delete from `".$wpdb->prefix.$this->table."` ";
                        $q .=  " where `id` = '".$this->id."'";
                        $wpdb->query($q);
                        $err = ob_get_clean();
                        if($err)
                            add_log($err);
                        if($wpdb->last_error !== '') {
                            $error = true;
                            add_log($wpdb->last_error);
                //            add_log($q);
                        }else{
                            $notice = '<div> Запись удалена. </div>';
                            add_log( $notice);
                        }
                    }else{
                        $notice = '<div> Отсутствует идентификатор. </div>';
                        add_log( $notice);
                    }
                }
                break;
            default:
                break;
        }
        $this->do_action('action__update__'.$this->form_type,['action'=>$this->form_type]);
        if($this->debug)add_log($this->return);
        $err = ob_get_clean();
        if($err)
            add_log($err);
        if(!$err && !$error){
            wp_redirect($this->return);
            exit();
        }
    }
    public $form_type_gets = ['create','edit'];
    public function data_def(){
        global $wpdb,$ht;
        $dbconst_schema = $wpdb->prefix . "wsd_dbconst_schema";
        $dbconst_tables = $wpdb->prefix . "wsd_dbconst_tables";
        $dbconst_fields = $wpdb->prefix . "wsd_dbconst_fields";
        $q= "select id from `$dbconst_tables` where `table` = '".$this->dbconst_table."' ";
        $tab_id = $wpdb->get_var($q);
//        add_log($this->dbconst_table);
//        add_log($tab_id);
        $data = [];
        if($tab_id && $wpdb->get_var("SHOW TABLES LIKE '$dbconst_fields'") == $dbconst_fields) {
            $sel_cou = "select count(*) from `".$wpdb->prefix.$this->table."` as a ";
            $this->items_count = $wpdb->get_var($sel_cou);
            $q= "select * from `$dbconst_fields` where `isprimary` != 1 and `tab_id`= $tab_id and `active` = 1 order by `weigh`";
            $fields = $wpdb->get_results($q,ARRAY_A);
            foreach($fields as $field){
                $k = $field['field'];
//                add_log('data_def '.$k);
//                add_log($this->form_type);
                $data[$k] = $field['def'];
                if(in_array($k, $this->weight_fields))
                    $data[$k] = $this->items_count+1;
                
                if($this->form_type && in_array($this->form_type,$this->form_type_gets)){
                    $filter_type = FILTER_SANITIZE_NUMBER_INT;
                    $def = 0;
                    $regxp = null;
                    if($field['filter'] == FILTER_SANITIZE_STRING){
                        $filter_type = FILTER_SANITIZE_STRING;
                        $def = '';
                    }
                    $filter_type = (int)$field['filter'];
                    $data[$k] = $ht->postget($k,$def,$filter_type,$regxp);
//                    add_log($k);
//                    add_log($data[$k]);
                }else{
                }
            }
        }
        return $data;
    }
    public function data($type='list'){
        global $wpdb;
        $result = [];
        switch($type){
            case 'item':
                ob_start();
                if($this->id && !$this->form_type){
                    $this->where['id'] = $this->id;
                    $q = "select * from `".$wpdb->prefix.$this->table."` ";
                    if(count($this->where)){
                        $this->build_where();
                        $q .= "where ";
                        $q .= implode(" and ", $this->where);
                    }
//                    $result = $wpdb->get_row($q,ARRAY_A);
                    $result = $this->get_row($q,ARRAY_A);
                }else{
                    $result = $this->data_def();
//                    add_log($result);
                }
                
                $err = ob_get_clean();
                if($err)
                    add_log($err);
                break;
            case 'list':
                ob_start();
        
                $select = [];
                $join = [];
        //        $select[] = "a.`id` as 'id'";

                $list_vars = [];
                $join_titles = 'bcdefghijklmnopqrstuvwxyz';
                $join_titles = str_split($join_titles);
//            if($this->dbconst_table == 'wh_weybill_item')add_log($this->fields_info);
//            if($this->dbconst_table == 'wh_weybill_item')add_log(array_keys($this->fields_info));
                foreach($this->fields_info as $field){
//                    add_log($field['field']);
                    if(!in_array($field['tpl'],['td_s_from_','td_s_from_dl_'])){
                        $field_t = $field['field'];
                        $select[$field_t] = "\na.`{$field['field']}` as '{$field['field']}'";

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
                        $table = $wpdb->prefix.$field['from_table'];
                        $field_t = $field['field'];
                        $field_f = "\n`".$field['field']."`";
                        $values = trim($field['from_value']);
                        $titles = $field['from_title'];
//                add_log(' ========== ');
//                            add_log(strlen($titles));

                        $titles = explode(',',$titles);
                        $v = [];
                        foreach($titles as $tn=>$t){
                            $t = html_entity_decode($t,ENT_QUOTES);
//                            add_log($t);
//                            add_log(trim($t));
//                            add_log(trim($t)[0]);
//                            add_log(str_split($t));
//                            add_log(trim($t,"'"));
//                            add_log(strlen(trim($t)));
//                            add_log(strlen(trim($t))-1);
//                            add_log(trim($t)[strlen(trim($t))-1]);
//                add_log(' ---------- ');
//                            $t = trim($t);
                            if(!strlen(trim($t))){$v[]="'$t'";}
                            else
                            if(strlen(trim($t))>1 && trim($t)[0]=="'" && trim($t)[strlen(trim($t))-1]=="'"){
                                $t = trim($t);
                                $t = trim($t,"'");
                                $v[]="'$t'";}
                            else{
                                $t = trim($t);
                                $v[]="$join_t.`$t`";
//                                if($tn+1 < count($titles))
//                                $v[]="' '";
                            }
                        }
//                add_log($v);
                        if(strlen(trim($field['from_value']))&&count($v)>0){
                            $v2  = implode(',',$v);
                            if(count($v)>1)
                            $field_f = "concat($v2)";
                            else
                            $field_f = "$v2";
                        }
                        $select[$field_t] = "\n$field_f as '$field_t'";
                        $join[] = "\nleft join `$table` as $join_t on $join_t.`$values` = a.`$field_t` ";
                    }
                }
                $this->select = $select;
                $this->join = $join;
                if(method_exists($this, 'data_list_before_build_query')){
                    $this->data_list_before_build_query();
                }
//                add_log($this->fields_info);
//                add_log($select);
//                add_log($join);
//                add_log($fields);
//                add_log($list_vars);
                $select = implode(',',$this->select);
                $join = implode(' ',$this->join);
                
                // =====================
        
                $sel = "select * from `".$wpdb->prefix.$this->table."` as a ";
                $sel= "select $select \nfrom `".$wpdb->prefix.$this->table."` as a $join ";
//                        . "\nwhere $where ";
//                . "\norder by  a.`id` desc \nlimit $limitfrom , $limit";
//                . "\norder by  a.`id` desc \nlimit $limitfrom , $limit";
                $q = " ";
                if(count($this->where)){
                    $this->build_where();
                    $q .= "\nwhere ";
                    $q .= implode(" and ", $this->where);
                    $q .= " ";
                }
                if(count($this->ordered)){
                    $ordered = $this->build_order();
                    $q .= "\norder by ";
                    $q .= implode(", ", $ordered);
//                    $q .= implode(", ", $this->ordered);
                    $q .= " ";
//                    add_log($ordered);
                }else{
                    $q .= "\norder by a.`id` desc ";
                }
                $sel_cou = "select count(*) from `".$wpdb->prefix.$this->table."` as a ";
//                $this->items_count = $wpdb->get_var($sel_cou.$q);
                $this->items_count = $this->get_var($sel_cou.$q);
                if($this->use_limit && $this->items_count>$this->pager_by){
                    $q .= "\nlimit ";
                    $q .= ($this->pager * $this->pager_by) . ", " . $this->pager_by;
                    $q .= " ";
                }
//                $result = $wpdb->get_results($sel.$q,ARRAY_A);
                if(method_exists($this, 'data_list_after_build_query')){
                    $this->data_list_after_build_query($sel,$q);
                }
                $result = $this->get_results($sel.$q,ARRAY_A);
//                add_log($this->ordered);
//                add_log([$sel.$q]);
                
                // =====================
                if(count($list_vars)){
//                add_log($list_vars);
                    foreach($list_vars as $fn=>$lv){
                        foreach($result as $rk=>&$row){
                            if(isset($row[$fn])){
                                if(isset($lv[$row[$fn]])){
                                    $row[$fn] = $lv[$row[$fn]];
                                }
                            }
                        }
                    }
                }
                
                $err = ob_get_clean();
                if($err)
                    add_log($err);
                
                break;
        }
        return $result;
    }
    public function get_var($q = null, $x = 0, $y = 0 ){
        global $wpdb;
        ob_start();
        $res = $wpdb->get_var($q,$x,$y);
        $err = ob_get_clean();
        if($err)
            add_log($err);
        return $res;
    }
    public function get_col($q=null,$x = 0){
        global $wpdb;
        ob_start();
        $res = $wpdb->get_col($q,$x);
        $err = ob_get_clean();
        if($err)
            add_log($err);
        return $res;
    }
    public function get_row($q=null,$t=OBJECT){
        global $wpdb;
        ob_start();
        $res = $wpdb->get_row($q,$t);
        $err = ob_get_clean();
        if($err)
            add_log($err);
        return $res;
    }
    public function get_results($q=null,$t=OBJECT){
        global $wpdb;
        ob_start();
        $res = $wpdb->get_results($q,$t);
        $err = ob_get_clean();
        if($err)
            add_log($err);
        return $res;
    }
    public function build_where(){
        foreach($this->where as $k => $v){
            $this->where[$k] = "`$k` = '$v'";
        }
    }
    public function build_order(){
//        $this->ordered = array_reverse($this->ordered);
        $ordered = [];
        foreach($this->ordered as $k => $v){
            $pref = '';
            if(isset($this->orderpref[$k]))$pref = $this->orderpref[$k];
            if($pref)$pref.='.';
            $ordered[$k] = "$pref`$k` $v";
//            $this->ordered[$k] = "$pref`$k` $v";
//            $this->ordered[$k] = "`$k` $v";
        }
        return $ordered;
    }
    public function show($echo = 1){
        global $ht;
        $out = '';
        if($this->title){
            ob_start();
            include 'page/title.php';
            $out .= ob_get_clean();
        }
        
        // add css sctipts inline
        if(count($this->style_inline)){
            foreach($this->style_inline as $style){
                if(!$style)continue;
                if(strlen($style) && !file_exists(__DIR__.'/style/'.$style.'.php'))continue;
                ob_start();
                include 'style/'.$style.'.php';
                $out .= ob_get_clean();
            }
        }
        
        // tabs
        if($this->tabs_use && count($this->tabs) > 1){
            ob_start();
            include 'page/'.'tabs.php';
            $out .= ob_get_clean();
        }
        //steps
        // page tab action
        // page action
        // page tab
        // page
        //
        if(file_exists(__DIR__.'/page/'.$this->page.'-steps.php')){
            ob_start();
            include 'page/'.$this->page.'-steps.php';
            $out .= ob_get_clean();
        }
//        add_log('tab '.$this->tab);
//        add_log('action '.$this->action);
        
        // add filter
//        if($this->filter_use && ( array_key_exists($this->tab,$this->filters) || array_key_exists($this->action,$this->filters) )){
        if( array_key_exists($this->tab,$this->filters) || ( $this->action && array_key_exists($this->action,$this->filters) )){
            $filter_slug = '';
            if( array_key_exists($this->tab,$this->filters) ){
//                add_log('filter_use tab ');
                $filter_slug = $this->filters[$this->tab];
                if(strlen($filter_slug))
                    $filter_slug = '-'.$filter_slug;
            }
            if( $this->action && array_key_exists($this->action,$this->filters) ){
//                add_log('filter_use action ');
                $filter_slug = $this->filters[$this->action];
                if(strlen($filter_slug))
                    $filter_slug = '-'.$filter_slug;
            }
//            $tab = '';
//            if($this->tab)$tab = '-'.$this->tab;
            if($this->filter_use && file_exists(__DIR__.'/page/'.$this->page_name.$filter_slug.'-filter.php')){
                ob_start();
                include 'page/'.$this->page_name.$filter_slug.'-filter.php';
                $out .= ob_get_clean();
            }
        }
        
        
        // add tools
        if(count($this->tools)){
            ob_start();
            include 'page/tools.php';
            $out .= ob_get_clean();
        }
        
        // add page
        // page tab action
        // page action
        // page tab
        // page
        //
        ob_start();
        if($this->show_tpl_file_name)
            add_log('current tpl file name: <b> '.'page/'.$this->page.'.php</b>');
        if($this->show_access_status)
        add_log(strtr(__METHOD__,['::'=>' :: ']).'; <br/>main_tab '.$this->main_tab.'; tab '.$this->tab.';'
                . ' action '.$this->action.'; mode '.$this->mode.';');
        include 'page/'.$this->page.'.php';
        $out .= ob_get_clean();
//        add_log($this->page);
        
        // add pager
        ob_start();
        include 'page/pager.php';
        $out .= ob_get_clean();
        
        // add js sctipts inline
        if(count($this->scripts_inline)){
            foreach($this->scripts_inline as $script){
                if(!$script)continue;
                if(strlen($script) && !file_exists(__DIR__.'/scripts/'.$script.'.php'))continue;
                ob_start();
                include 'scripts/'.$script.'.php';
                $out .= ob_get_clean();
            }
        }
        
        // add css sctipts
        if(count($this->styles)){
            foreach($this->styles as $_style){
                $style = '/template-parts/dbconst/style/'.$_style.'.css';
                wp_enqueue_style( 'dbconst-'.$_style, get_template_directory_uri() . $style,[]);
            }
        }
        
        // add js sctipts
        if(count($this->scripts)){
            foreach($this->scripts as $_script){
                $script = '/template-parts/dbconst/scripts/'.$_script.'.js';
                wp_enqueue_script( 'dbconst-'.$_script, get_template_directory_uri() . $script, array(), null, true );
            }
        }
        if($echo) echo $out;
        return $out;
    }
    public function prepare_query($q){
        $r=[];
        $r['insert into ']='<b>INSERT INTO</b><br/> ';
        $r[' set ']=' <br/><b>SET</b><br/> ';
        $r[' SET ']=' <br/><b>SET</b><br/> ';
        $r[',`']=',<br/>`';
        $r['WHERE ']='<br/><b>WHERE</b><br/>';
        $r['where ']='<br/><b>WHERE</b><br/>';
        $r[' FROM']='<br/><b>FROM</b>';
        $r[' from']='<br/><b>FROM</b>';
        $r["\nfrom"]='<br/><b>FROM</b>';
        $r['SELECT ']='<b>SELECT</b><br/>';
        $r['select ']='<b>SELECT</b><br/>';
        $r['left join ']='<br/><b>left join</b> ';
        $r['left join ']='<br/>left join ';
        $r['and ']='<br/>and ';
        $r['or ']='<br/>or ';
        $r[',']=',<br/>';
        $r['(']='<br/><b>(</b><br/>';
        $r[')']='<br/><b>)</b><br/>';
//        $r['']='';
//        $r['']='';
//        $r['']='';
//        $r['']='';
        $q = strtr($q,$r);
        return $q;
    }
    public function do_filter($method=false,$ret = null,$attr=[]){
        if(method_exists($this, $method)){
            $ret = $this->$method($ret,$attr);
        }
        return $ret;
    }
    public function do_action($method=false,$attr=[]){
        if(method_exists($this, $method)){
            $this->$method($attr);
        }
    }
}
