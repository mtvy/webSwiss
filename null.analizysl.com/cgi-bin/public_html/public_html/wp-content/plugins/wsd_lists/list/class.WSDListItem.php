<?php

/* 
 * class.WSDListItem.php
 */

include_once DSHOP_DIR.'lend.php';

class WSDListItem extends lend{
    public $last_id=0;
    public $table_name='';
    public $input_filter = [];
    public $input_placeholder = [];
    public $from_table = [];
    public $from_value = [];
    public $from_title = [];
    public $from_where = [];
    public $default = [];
    public $tpls = [];
    public $meta_ftpl_ = [];
	function __construct($table = '',$default=[],$tpls=[]){
		global $wpdb;
        $this->table_name = $table;
        $this->default = $default;
        $this->tpls = $tpls;
        $tab_name = $this->table_name;
        $this->init();
    }
    public function init(){
        $fields=[];
        $this->meta_fields = $fields;
        $this->meta_desc = $fields;
        $this->meta_ftpl_ = $fields;
        $this->meta_ftpl = $fields;
        $this->meta_val = $fields;
        $this->meta_vars = $fields;
        
		global $wpdb;
        
        $tab_name = $this->table_name;
        $tab_fields= $wpdb->prefix . $this->table_name . "_fields";
        $q= "select * from `$tab_fields` order by `weigh`";
        $fields = $wpdb->get_results($q,ARRAY_A);
        
        $this->input_filter = [];
        $this->input_placeholder = [];
        $this->from_table = [];
        $this->from_value = [];
        $this->from_title = [];
        $this->from_where = [];
        foreach($fields as $field){
            $name = $field['name'];
            
            $req = $field['required']?'*':'';
            
            $this->meta_fields[$name] = $field['title'].$req;
            $this->meta_ftpl_[$name] = $field['tpl'];
            $this->meta_ftpl[$name] = $field['tpl'];
            $this->meta_val[$name] = $field['def'];
            if(isset($this->default[$name]))$this->meta_val[$name] = $this->default[$name];
            if(isset($this->tpls[$name]))$this->meta_ftpl[$name] = $this->tpls[$name];
            $this->meta_vars[$name] = false;
            $vars = unserialize($field['vars']);
            if(count($vars))$this->meta_vars[$name] = $vars;
            $this->meta_desc[$name] = $field['desc'];
            
            $this->input_filter[$name] = $field['filter'];
            $this->input_placeholder[$name] = $field['placeholder'];
            $this->from_table[$name] = $field['from_table'];
            $this->from_value[$name] = $field['from_value'];
            $this->from_title[$name] = $field['from_title'];
            $this->from_where[$name] = $field['from_where'];
        }
        
    }
    public function display(){
		global $wpdb;
        
        $field = [];
        $ft = filter_input(INPUT_POST,'form_type',FILTER_SANITIZE_STRING);
        $fid = filter_input(INPUT_GET,'fid',FILTER_SANITIZE_NUMBER_INT);
        $tab_value= $wpdb->prefix . $this->table_name . "_value";
        
        
        $maxweigh = 0;
//        $q= "select max(`weigh`) from `$dsp_fields` ";
//        $maxweigh = $wpdb->get_var($q);
        
        if($fid){
            echo '<input type="hidden" name="fid" value="'.$fid.'">';
//            $q= "select * from `$ml_labgroups` where `id` = '$fid'";
            $q= "select * from `$tab_value` where `id` = '$fid'";
            $field = $wpdb->get_row($q,ARRAY_A);
        }else{
//            $q= "select max(`weigh`)+1 as 'weigh',max(`id`) as 'name' from `$dsp_fields` ";
//            $field_ = $wpdb->get_row($q,ARRAY_A);
//            $field=['name'=>'f_'.$field_['name'],'weigh'=>$field_['weigh']];
//            $maxweigh = $field_['weigh'];
        }
//        $oldweigh = $field['weigh'];
//            $this->_notice( '<div> 1 </div>');
        if($ft){
            switch($ft){
                case 'list':
//                    $this->updateListField();
                    break;
                case 'add':
//                    $this->addField();
                    $fid = $this->updateField(false,$maxweigh);
//                    $this->updateWeigh($fid,$oldweigh);
                    break;
                case 'edit':
                    $fid  = filter_input(INPUT_POST,'fid',FILTER_SANITIZE_NUMBER_INT);
                    $this->updateField($fid,$maxweigh);
//                    $this->updateWeigh($fid,$field);
                    break;
                case 'remove':
                    break;
            }
        }
        $tab  = filter_input(INPUT_GET,'tab',FILTER_SANITIZE_STRING);
        if($tab){
            switch($tab){
                case 'add':
                    break;
                case 'edit':
                    break;
                case 'delete':
                    foreach($this->meta_ftpl as &$tpl){
                        $tpl = 'td_t_';
                    }
                    break;
                case 'list':
                default:
                    break;
            }
        }
        if($fid){
//            echo '<input type="hidden" name="fid" value="'.$fid.'">';
            $q= "select * from `$tab_value` where `id` = '$fid'";
            $field = $wpdb->get_row($q,ARRAY_A);
        }else{
//            $q= "select max(`weigh`)+1 as 'weigh',max(`id`) as 'name' from `$dsp_fields` ";
//            $field_ = $wpdb->get_row($q,ARRAY_A);
//            $field=['name'=>'f_'.$field_['name'],'weigh'=>$field_['weigh']];
        }
        
//        $this->meta_fields['dump'] = '<$field>';
        
//        $this->meta_val['dump'] = '<pre>'.print_r($field,1).'</pre>';
//        $this->meta_val['dump'] .= '<pre>'.print_r($_POST,1).'</pre>';
        
        $hook = 'dsp__init_fields__dspfield';
//        add_action('ds__init_meta_fields__dsproduct', [$this,'ds__init_meta_fields__dsproduct'], 3, 1 );
//        do_action($hook,[$this]);
        
//            $this->_notice( '<div> 5 </div>');
        $this->init_meta_tpls();
        $this->init_list_tpls();
        $this->display_meta_box($field);
//            $this->_notice( '<div> 6 </div>');
        
    }
    /**
     * шаблоны полей меттатегов
     */
    public function init_list_tpls(){
        $ftpl=[];
    // input text
    $ftpl['td_h_']=<<<td
        <!-- __label__ -- __desc__ -->
            <input id="__id__" type="hidden" name="__name__" value="__val__" class="" />
td;
        $this->meta_tpls += $ftpl;
    }

    public function removeField ( $fid=false) { // static
        if( !$fid)return;
        global $wpdb;
        $tab_fields= $wpdb->prefix . $this->table_name . "_fields";
        $tab_value= $wpdb->prefix . $this->table_name . "_value";
        
        $q = "select `name` from `$tab_fields` where `weigh` = '0'";
        $field_title = $wpdb->get_var($q);
        $q = "select $field_title from `$tab_value` where `id` = '$fid'";
        $row_name = $wpdb->get_var($q);
//        $field = $wpdb->get_row($q, ARRAY_A);
        
        $q= "select * from `$tab_value` where `id` = '$fid'";
        $field = $wpdb->get_row($q,ARRAY_A);
        
//        $q= "select `weigh` from `$dsp_fields` where `id` = '$fid'";
//        $oldweigh = $wpdb->get_var($q);
        
        $q= "delete from `$tab_value` where `id` = '$fid'";
        $wpdb->query($q);
        
//        $q= "update `$dsp_fields` set `weigh` = `weigh`-1 where  `weigh` >= '$oldweigh' ";
//        $wpdb->query($q);
//            $this->_notice( '<div> 4 </div>');
//        $m = '<b>Удалена запись: '.$field['title'].' ['.$field['name'].'] </b>';
        $m = '<b>Удалена запись: '.$row_name . ' [id=' . $fid .'] </b>';
        echo '<div class="notice notice-success is-dismissible"> <p>'. $m .'</p></div>';
        return $field;
    }

    public function updateWeigh ( $fid=false, $old_fields = []) {
        if( !$fid)return;
        global $wpdb;
        
        $dsp_fields= $wpdb->prefix . $this->table_name . "_fields";
        $q= "select * from `$dsp_fields` where `sort` = 1";
        $sortField = $wpdb->get_row($q,ARRAY_A);
        foreach($sortField as $sf){
            $sfName = $sf['name'];
            
        
            $tab_value= $wpdb->prefix . $this->table_name . "_value";
            $q= "select * from `$tab_value` where `id` = '$fid'";
            $field = $wpdb->get_row($q,ARRAY_A);

            $newweigh = $field[$sfName];

            $oldweigh = $old_fields[$sfName];

            if($newweigh != $oldweigh && $fid){
                if($newweigh < $oldweigh){
                    $q= "update `$tab_value` set `$sfName` = `$sfName`+1 where `id` <> '$fid' and `$sfName` >= '$newweigh' and `$sfName` <= '$oldweigh' ";
                }else{
                    $q= "update `$tab_value` set `$sfName` = `$sfName`-1 where `id` <> '$fid' and `$sfName` <= '$newweigh' and `$sfName` >= '$oldweigh' ";
                }
                $wpdb->query($q);
            }
        }
    }

    public function updateField( $fid=false,$maxweigh=0) {
        global $wpdb;
        $tab_fields= $wpdb->prefix . $this->table_name . "_fields";
        $q= "select * from `$tab_fields` order by `weigh`";
        $fields = $wpdb->get_results($q,ARRAY_A);
        
        $values = [];
        foreach($fields as $field){
            $name = $field['name'];
            $type = $field['type'];
            if (!filter_has_var(INPUT_POST, $name)) continue;
            $filter = FILTER_SANITIZE_STRING;
            $intTypes = ['int','integer','bigint',];
            if(in_array($type, $intTypes))$filter = FILTER_SANITIZE_NUMBER_INT;
			$value = filter_input(INPUT_POST,$name,$filter);
			$values[$name]= "`$name` = '$value'";
            
//            $this->meta_fields[$name] = $field['title'];
//            $this->meta_ftpl[$name] = $field['tpl'];
//            $this->meta_val[$name] = $field['def'];
//            $this->meta_vars[$name] = false;
//            $vars = unserialize($field['vars']);
//            if(count($vars))$this->meta_vars[$name] = $vars;
//            $this->meta_desc[$name] = $field['desc'];
        }
        if(count($values)==0)return;
        $values = implode(', ', $values);
        $tab_value= $wpdb->prefix . $this->table_name . "_value";
        
        $q = "insert into $tab_value set " . $values;
        if($fid)
            $q = "update  $tab_value set " . $values . " where `id` = '$fid'";
        $wpdb->query($q);
        
        $notice = '<div> Добавлены данные </div>';
        if($fid)
            $notice = '<div> Обновлены данные </div>';
        
        if(!$fid){
            $fid = $wpdb->insert_id;
        }
        $this->last_id = $fid;
        
//            $this->_notice( '<div> 2 </div>');
//        $this->updateAttr($fid);
        $this->_notice( $notice);
        return $fid;
    }
	public function css(){
		?>
		<style>
			.th_desc{ font-weight:normal; }
		</style>
		<?php
	}

    /**
     * отрисовка полей метатегов
     * @param type $movie_review
     */
    public function display_meta_box( $field ) {
        global $ht;
        // Retrieve current name of the Director and Movie Rating based on review ID
    //    $movie_director = esc_html( get_post_meta( $movie_review->ID, 'movie_director', true ) );
    //    $movie_rating = intval( get_post_meta( $movie_review->ID, 'movie_rating', true ) );

        
        $fields=$this->meta_fields;
//        $tr='';
//        foreach($fields as $f=>$l){
//            $r=[];
//            $r['_v_']=esc_html( get_post_meta( $movie_review->ID, $f, true ) );
//            $r['_n_']=$f;
//            $r['_l_']=$l;
//    //        $r['']='';
//            $tr.=strtr($this->meta_tpls['tpl_i_tr'],$r);
//        }
//            $r=[];
//            $r['_tr_']=$tr;
//            $tab = strtr($this->meta_tpls['tpl_i_tab'],$r);
//                echo $tab;
                
    $tds=[];
    foreach($fields as $f=>$v){
//        echo $v;
        $r=[];
        $r['__id__']=$f;
        $r['__for__']=$f;
        $r['__name__']=$f;
        $r['__label__']=$v;
        $r['__val__']='';
        $r['__desc__']='';
        
        $r['__i_class__']='';
        $r['__placeholder__']='';

        $r['__cols__']=70;
        $r['__rows__']=5;
        
        if(isset($this->meta_desc[$f])){
            $at=['class'=>'th_desc'];
            if($this->meta_ftpl[$f] == 'td_h_')
            $r['__desc__']=$this->meta_desc[$f];
            else
            $r['__desc__']=$ht->f('div',$this->meta_desc[$f],$at);
        }
        if(isset($this->meta_val[$f]))
            $r['__val__']=$this->meta_val[$f];
        if(isset( $field[$f])){
//            $r['__val__']=esc_html( $field[$f] );
            $r['__val__']=( $field[$f] );
            if($f == 'vars'){
                $fds = [];
                $fd = unserialize($field[$f]);
                foreach($fd as $sok=>$sov){
                    $fds[] = "$sok:$sov";
                }
                $r['__val__']=implode("\n", $fds );// implode
            }
        }
        
        if($this->meta_ftpl[$f] == 'td_ta_'){
            $rows =  count(explode("\n",$r['__val__']));//esc_attr
            $r['__rows__'] += $rows;
        }
//            add_log($f);
//            add_log($this->meta_vars[$f]);
        if(
            $this->meta_ftpl_[$f]=='td_s_'
            && $this->meta_ftpl[$f]=='td_t_'
            && $this->meta_vars[$f]
//                && count($this->meta_vars[$f])>0
        ){
            $val=[];
            $r['__val__']=$r['__val__'];
            $val['items']=$this->meta_vars[$f];
            if(isset($val['items'][$r['__val__']]))
            $r['__val__'] = $val['items'][$r['__val__']];
            
        }
        if($this->meta_ftpl[$f] == 'td_t_'){
            $r['__val__'] = nl2br($r['__val__']);
        }
        if(
            $this->meta_ftpl[$f]=='td_s_'
            && $this->meta_vars[$f]
//                && count($this->meta_vars[$f])>0
        ){
            $val=[];
            $val['res']=$r['__val__'];
            $val['items']=$this->meta_vars[$f];
            $r['__val__']=$this->_cf_select($val);
            
        }
        if(
            $this->meta_ftpl[$f]=='td_s_from_'
//            && $this->meta_vars[$f]
//                && count($this->meta_vars[$f])>0
            ||(
                $this->meta_ftpl_[$f]=='td_s_from_'
                && $this->meta_ftpl[$f]=='td_t_'
            )
        ){
            $this->meta_vars[$f] = [];
            
//            $this->from_table[$name] = $field['from_table'];
//            $this->from_value[$name] = $field['from_value'];
//            $this->from_title[$name] = $field['from_title'];
//            $this->from_where[$name] = $field['from_where'];
            
            $table = $this->from_table[$f];
            $values = $this->from_value[$f];
            $titles = $this->from_title[$f];
            $where = $this->from_where[$f];
            
            $err_q=0;
            if(!$table)$err_q = 1;
            if(!$values)$err_q = 1;
            if(!$titles)$err_q = 1;
            if(!$where) $where = 1;
            ob_start();
            global $wpdb;
            if(!$err_q){
                $qtpl = 'select * from `__table__` where __where__';
                $r_=[];
                $r_['__table__'] = $wpdb->prefix . $table;
                $r_['__where__'] = $where;
                $q = strtr($qtpl,$r_);
                
                $fields = $wpdb->get_results($q,ARRAY_A);
                
            }
            $err = ob_get_clean();
            if($err){
                $this->_notice( '<div> '.$err.' </div>');
            }else{
                $values = explode(',',$values);
                $titles = explode(',',$titles);
                foreach($fields as $flds){
                    $v = [];
                    foreach($values as $t){
                        if(!strlen(trim($t))){$v[]=$t;continue;}
                        if(!key_exists($t,$flds)){$v[]=$t;continue;}
                        else{$v[]=$flds[$t];}
                    }
                    $v  = implode('',$v);
                    $k = $v;
                    $v = [];
                    foreach($titles as $t){
                        if(!strlen(trim($t))){$v[]=$t;continue;}
                        if(!key_exists($t,$flds)){$v[]=$t;continue;}
                        else{$v[]=$flds[$t];}
                    }
                    $v  = implode('',$v);
//                    add_log($k);
//                    add_log($v);
                    $this->meta_vars[$f][$k]=$v;
                }
            }
            
            
            if(
                $this->meta_ftpl_[$f]=='td_s_from_'
                && $this->meta_ftpl[$f]=='td_t_'
    //                && count($this->meta_vars[$f])>0
            ){
//                $r['__val__']=$r['__val__'];
                
                $val=$this->meta_vars[$f];
                if(isset($val[$r['__val__']]))
                    $r['__val__'] = $val[$r['__val__']];
            }else{
                $val=[];
                $val['res']=$r['__val__'];
                $val['items']=$this->meta_vars[$f];
                $r['__val__']=$this->_cf_select($val);
            }
            
        }
            
        $tpl = $this->meta_ftpl[$f];
        if($tpl=='td_s_from_'){
            $tpl = 'td_s_';
        }
        $tds[]=strtr($this->meta_tpls[$tpl],$r);
    }
    /* ============================ */
        $q=[];
        $q['redirect_to']=get_the_permalink( get_option('ds_id_page_item') );
        $redirect_to = filter_input(INPUT_GET, 'redirect_to', FILTER_DEFAULT);
        if($redirect_to){
//            wp_redirect( urldecode($redirect_to ) );
            $q['redirect_to']=$redirect_to;
        }
        $r=[];
        $r['__id__']='redirect_to';
        $r['__for__']='redirect_to';
        $r['__name__']='redirect_to';
        $r['__label__']='redirect_to';
        $r['__val__']=$q['redirect_to'];
//        $tds[]=strtr($this->meta_tpls['td_i_'],$r);
    /* ============================ */
    $r=[];
//    ech*o implode("\n",$this->meta_tpls);
//    echo implode("\n",$tds);
    $r['_tr_']=implode("\n",$tds);
//    $r['__rows__']=implode("\n",$tds);
//    echo strtr($table__,$r);
//    $r=[];
//    $r['_tr_']=$tr;
    $tab = strtr($this->meta_tpls['tpl_i_tab'],$r);
    $this->css();
        echo $tab;
            /** / if(0){
                ?>
        <table>
            <tr>
                <td style="width: 150px">Movie Rating</td>
                <td>
                    <select style="width: 100px" name="movie_review_rating">
                    <?php
                    // Generate all items of drop-down list
                    for ( $rating = 5; $rating >= 1; $rating -- ) {
                    ?>
                        <option value="<?php echo $rating; ?>" <?php echo selected( $rating, $movie_rating ); ?>>
                        <?php echo $rating; ?> stars <?php } ?>
                    </select>
                </td>
            </tr>
        </table>
        <?php  } /**/
    }
    
    public static function get_list(){
        global $wpdb;
//        $tab_value= $wpdb->prefix . $this->table_name . "_value";
        $tab_value= $wpdb->prefix . "curier_value";
        $q= "select * from `$tab_value`  ";
        $field = $wpdb->get_results($q,ARRAY_A);
        $groups = [];
        foreach($field as $v){
            $groups [(int)trim($v['id'])] = $v['f_0'].' '. $v['f_1'].' '. $v['f_2'];
        }
        $sel=[];
        $sel[-1] = 'Выбрать курьера';
        $sel+=$groups;
        return $sel;
    }
    public static function get_curier($id=0){
        global $wpdb;
        $tab_value= $wpdb->prefix . "curier_value";
        $q= "select concat(`f_0`,' ',`f_1`,' ',`f_2`) from `$tab_value` where id ='$id' ";
        return $wpdb->get_var($q);
    }
}
