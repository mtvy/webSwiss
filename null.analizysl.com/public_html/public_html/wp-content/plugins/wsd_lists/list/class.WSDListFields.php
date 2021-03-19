<?php

/* 
 * class.WSDListFields.php
 */

include_once DSHOP_DIR.'lend.php';

class WSDListFields extends lend{
//class DSopProductField extends lend{
    public $table_name='';
    public $weight_fields = [];
	function __construct($table = '',$url_edit=''){
		global $wpdb;
        $this->table_name = $table;
        $tab_name = $this->table_name;
        
        $this->init();
    }
    public function init(){
            $sql = "CREATE TABLE " . '$dsp_fields' . " (
                /* `id` int(11) unsigned NOT NULL AUTO_INCREMENT, */
                 `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                 `name` VARCHAR(16) NOT NULL comment '',
                 `weigh` int(11) unsigned DEFAULT '0' NOT NULL comment '',/* weigh form fields*/
                 `active` int(1) unsigned DEFAULT '1' NOT NULL comment '',
                 `weigh_admin` int(11) unsigned DEFAULT '0' NOT NULL comment '',/* weigh list column*/
                 `weigh_public` int(11) unsigned DEFAULT '0' NOT NULL comment '',
                 `show_admin` int(1) unsigned DEFAULT '1' NOT NULL comment '',
                 `show_pablic` int(1) unsigned DEFAULT '1' NOT NULL comment '',
                 `width_adm_css` VARCHAR(8) NOT NULL comment '',
                 `width_publ_css` VARCHAR(8) NOT NULL comment '',
                 `title` VARCHAR(64) NOT NULL comment '',
                 `tpl` VARCHAR(32) NOT NULL comment '',
                 `type` VARCHAR(32) NOT NULL comment '',
                 `search` int(1) unsigned DEFAULT '1' NOT NULL comment '',
                 `size` int(11) unsigned DEFAULT '0' NOT NULL comment '',
                 `flsize` int(11) unsigned DEFAULT '0' NOT NULL comment '',
                 `unsigned` int(1) unsigned DEFAULT '0' NOT NULL comment '',
                 `zerofill` int(1) unsigned DEFAULT '0' NOT NULL comment '',
                 `def` VARCHAR(256) NOT NULL comment '',
                 `vars` text NOT NULL,
                 `desc` text NOT NULL,/*admin*/
                 `help` text NOT NULL,/*public*/
                 PRIMARY KEY (`id`),
                 INDEX weigh (weigh),
                 INDEX weigh_admin (weigh_admin),
                 INDEX weigh_public (weigh_public),
                 UNIQUE INDEX name (name),
                 INDEX title (title)
               )
               ENGINE=InnoDB AUTO_INCREMENT=0 comment='';";
        // список мета тегов
        $fields=[];
        $nm=$this->nm;
        $fields['name']='name';
        $fields['weigh']='weigh';
        $fields['active']='active';
        $fields['weigh_admin']='weigh_admin';
        $fields['weigh_public']='weigh_public';
        $fields['show_admin']='show_admin';
        $fields['show_pablic']='show_pablic';
        $fields['width_adm_css']='width_adm_css';
        $fields['width_publ_css']='width_publ_css';
        $fields['title']='title';
        $fields['tpl']='tpl';
        $fields['type']='type';
        $fields['search']='search';
        $fields['size']='size';
        $fields['flsize']='flsize';
        $fields['unsigned']='unsigned';
        $fields['zerofill']='zerofill';
        $fields['required']='required';
        $fields['filter']='input filter';
        $fields['placeholder']='input placeholder';
        $fields['order']='use in order';
        $fields['weigh_order']='weigh_order';
        $fields['from_table']='from_table';
        $fields['from_value']='from_value';
        $fields['from_title']='from_title';
        $fields['from_where']='from_where';
        $fields['def']='def';
        $fields['vars']='vars';
        $fields['desc']='desc';
        $fields['help']='help';
//        $fields['dump']='dump';
        $this->meta_fields = $fields;
        

        // список мета тегов
        $fields=[];
        $nm=$this->nm;
        $fields['name']='имя поля в базе';
        $fields['weigh']='вес поля в списке';
        $fields['active']='используется/не используется';
        $fields['weigh_admin']='вес колонки admin';
        $fields['weigh_public']='вес колонки public';
        $fields['show_admin']='показать колонку admin';
        $fields['show_pablic']='показать колонку public';
        $fields['width_adm_css']='ширина колонки admin css';
        $fields['width_publ_css']='ширина колонки public css';
        $fields['title']='заголовок';
        $fields['tpl']='шаблон ввода';
        $fields['type']='тип поля в базе';
        $fields['search']='наличие индекса';
        $fields['size']='количество символов';
        $fields['flsize']='кол-во символов после запятой';
        $fields['unsigned']='только положительные';
        $fields['zerofill']='дополнять результат нулями';
        $fields['required']='требует заполнения';
        $fields['filter']='фильтр валидации';
        $fields['placeholder']='плейсхолдер поля формы';
        $fields['order']='использовать для сортировки';
        $fields['weigh_order']='приоритет поля в сортировке';
        $fields['from_table']='таблица исходных данных для select from';
        $fields['from_value']='поле значений для select from';
        $fields['from_title']='поле названий значений для select from';
        $fields['from_where']='условие выборки значений для select from';
        $fields['def']='значение по умолчанию';
        $fields['vars']='список значений для select';
        $fields['desc']='описание';
        $fields['help']='подсказка';
        $fields['dump']='отладочноые данные';
        $this->meta_desc = $fields;
        

        // типы шаблонов поля метатегов
        $ftpl=[];
        $fields['name']='td_i_';
        $fields['weigh']='td_d_';
        $fields['active']='td_s_';
        $fields['weigh_admin']='td_d_';
        $fields['weigh_public']='td_d_';
        $fields['show_admin']='td_s_';
        $fields['show_pablic']='td_s_';
        $fields['width_adm_css']='td_i_';
        $fields['width_publ_css']='td_i_';
        $fields['title']='td_i_';
        $fields['tpl']='td_s_';
        $fields['type']='td_s_';
        $fields['search']='td_s_';
        $fields['size']='td_d_';
        $fields['flsize']='td_d_';
        $fields['unsigned']='td_s_';
        $fields['zerofill']='td_s_';
        $fields['required']='td_s_';
        $fields['filter']='td_s_';
        $fields['placeholder']='td_i_';
        $fields['order']='td_s_';
        $fields['weigh_order']='td_d_';
        $fields['from_table']='td_i_';
        $fields['from_value']='td_i_';
        $fields['from_title']='td_i_';
        $fields['from_where']='td_ta_';
        $fields['def']='td_ta_';
        $fields['vars']='td_ta_';
        $fields['desc']='td_ta_';
        $fields['help']='td_ta_';
        $fields['dump']='td_t_';
        $this->meta_ftpl = $fields;

        // значения метатегов
        $ftpl=[];
        $fields['name']='';
        $fields['weigh']='0';
        $fields['active']='1';
        $fields['weigh_admin']='0';
        $fields['weigh_public']='0';
        $fields['show_admin']='1';
        $fields['show_pablic']='1';
        $fields['width_adm_css']='';
        $fields['width_publ_css']='';
        $fields['title']='';
        $fields['tpl']='td_i_';
        $fields['type']='varchar';
        $fields['search']='0';
        $fields['size']='32';
        $fields['flsize']='0';
        $fields['unsigned']='0';
        $fields['zerofill']='0';
        $fields['required']='0';
        $fields['filter']=FILTER_SANITIZE_STRING;
        $fields['placeholder']='';
        $fields['order']='0';
        $fields['weigh_order']='0';
        $fields['from_table']='';
        $fields['from_value']='';
        $fields['from_title']='';
        $fields['from_where']='';
        $fields['def']='';
        $fields['vars']='';
        $fields['desc']='';
        $fields['help']='';
        $fields['dump']='';
        $ftpl[$nm.'pid']=isset($_GET['pid'])?((int)$_GET['pid']):'';
        $ftpl[$nm.'code']='';
        $this->meta_val = $fields;

        // варианты значений метатегов
        $en = [1=>'Да',0=>'Нет'];
                    $ftps = [];
                    $ftps['date'] = 'DATE ';
                    $ftps['time'] = 'TIME ';
                    $ftps['datetime'] = 'DATETIME ';
                    $ftps['tinytext'] = 'TINYTEXT ';
                    $ftps['text'] = 'TEXT ';
                    $ftps['mediumtext'] = 'MEDIUMTEXT ';
                    $ftps['longtext'] = 'LONGTEXT ';
                    $ftps['varchar'] = "VARCHAR(size) ";
                    $ftps['char'] = "CHAR(size) ";
                    $ftps['int'] = "int(size) ";
                    $ftps['integer'] = "INTEGER(size) ";
                    $ftps['bigint'] = "BIGINT(size) ";
                    $ftps['real'] = "REAL(size,flsize) ";
                    $ftps['double'] = "DOUBLE(size,flsize) ";
                    $ftps['float'] = "FLOAT(size,flsize) ";
                    $ftps['decimal'] = "DECIMAL(size,flsize) ";
                    $ftps['numeric'] = "NUMERIC(size,flsize) ";
                    $tps = [];
                    $tps['td_ta_'] = 'textarea';
                    $tps['td_i_'] = 'text';
                    $tps['td_d_'] = 'number';
//                    $tps['td_o_'] = 'number';
                    $tps['td_s_'] = 'select';
                    $tps['td_s_from_'] = 'select from';
                    $tps['td_t_'] = 'text only';
                    
                    $filter=[];
                    $filter[FILTER_SANITIZE_NUMBER_INT] = 'FILTER_SANITIZE_NUMBER_INT';
                    $filter[FILTER_SANITIZE_STRING] = 'FILTER_SANITIZE_STRING';
        $ftpl=[];
        $fields['name']=false;
        $fields['weigh']=false;
        $fields['active']=$en;
        $fields['weigh_admin']=false;
        $fields['weigh_public']=false;
        $fields['show_admin']=$en;
        $fields['show_pablic']=$en;
        $fields['width_adm_css']=false;
        $fields['width_publ_css']=false;
        $fields['title']=false;
        $fields['tpl']=$tps;
        $fields['type']=$ftps;
        $fields['search']=$en;
        $fields['size']=false;
        $fields['flsize']=false;
        $fields['unsigned']=$en;
        $fields['zerofill']=$en;
        $fields['required']=$en;
        $fields['filter']=$filter;
        $fields['placeholder']=false;
        $fields['order']=$en;
        $fields['weigh_order']=false;
        $fields['from_table']=false;
        $fields['from_value']=false;
        $fields['from_title']=false;
        $fields['from_where']=false;
        $fields['def']=false;
        $fields['vars']=false;
        $fields['desc']=false;
        $fields['help']=false;
        $fields['dump']=false;
        $this->meta_vars = $fields;
        
        $weight_fields = [];
        $weight_fields[] = 'weigh';
        $weight_fields[] = 'weigh_admin';
        $weight_fields[] = 'weigh_public';
        $weight_fields[] = 'weigh_order';
        $this->weight_fields = $weight_fields;
        
    }
    public function display(){
        
        
		global $wpdb;
//        $dsp_attr= $wpdb->prefix . "dsp_attr";
//        $dsp_fields= $wpdb->prefix . "dsp_fields";
        $tab_name = $this->table_name;
        $tab_fields= $wpdb->prefix . $tab_name . "_fields";
        $field = [];
        $ft = filter_input(INPUT_POST,'form_type',FILTER_SANITIZE_STRING);
        $fid = filter_input(INPUT_GET,'fid',FILTER_SANITIZE_NUMBER_INT);
        
        
        $weight_fields = $this->weight_fields;
        
        $select_max = [];
        foreach($weight_fields as $wf){
            
            $select_max[] = "max(`$wf`) as '$wf'";
        }
        $select_max = implode(', ',$select_max);
        
//        $q= "select max(`weigh`) as 'weigh', max(`weigh_admin`) as 'weigh_admin',"
//                . " max(`weigh_public`) as 'weigh_public' from `$tab_fields` ";
//        $maxweigh = $wpdb->get_var($q);
        $q= "select $select_max from `$tab_fields` ";
        $maxweigh = $wpdb->get_row($q,ARRAY_A);
        
        if($fid){
            echo '<input type="hidden" name="fid" value="'.$fid.'">';
            $q= "select * from `$tab_fields` where `id` = '$fid'";
            $field = $wpdb->get_row($q,ARRAY_A);
//            $maxweigh = $field;
        }else{
            $select_max = [];
            foreach($weight_fields as $wf){

                $select_max[] = "max(`$wf`)+1 as '$wf'";
            }
            $select_max[] = "max(`id`) as 'name'";
            $select_max = implode(', ',$select_max);
//            $q= "select max(`weigh`)+1 as 'weigh', max(`weigh_admin`)+1 as 'weigh_admin',"
//                    . " max(`weigh_public`)+1 as 'weigh_public',max(`id`) as 'name' from `$tab_fields` ";
            $q= "select $select_max from `$tab_fields` ";
            $field_ = $wpdb->get_row($q,ARRAY_A);
            $field_['name'] =  'f_'.(int)$field_['name'];
            
//            $field=['name'=>$field_['name'],'weigh'=>$field_['weigh'],
//                'weigh_admin'=>$field_['weigh_admin'],'weigh_public'=>$field_['weigh_public']];
//            $maxweigh = $field_['weigh'];
            $field = $field_;
            $maxweigh = $field_;
        }
        $oldweigh = $field['weigh'];
//            $this->_notice( '<div> 1 </div>');
        if($ft){
            switch($ft){
                case 'dsp_list':
//                    $this->updateListField();
                    break;
                case 'add_field':
//                    $this->addField();
                    $fid = $this->updateField(false,$maxweigh);
                    $this->updateWeigh($fid,$oldweigh,$field);
                    break;
                case 'edit_field':
                    $fid  = filter_input(INPUT_POST,'fid',FILTER_SANITIZE_NUMBER_INT);
                    $this->updateField($fid,$maxweigh);
                    $this->updateWeigh($fid,$oldweigh,$field);
                    break;
                case 'remove_field_field':
                    break;
            }
        }
        $tab  = filter_input(INPUT_GET,'tab',FILTER_SANITIZE_STRING);
        if($tab){
            switch($tab){
                case 'add_field':
                    break;
                case 'edit_field':
                    break;
                case 'delete_field':
                    foreach($this->meta_ftpl as &$tpl){
                        $tpl = 'td_t_';
                    }
                    break;
                case 'list_fields':
                default:
                    break;
            }
        }
        if($fid){
            echo '<input type="hidden" name="fid" value="'.$fid.'">';
            $q= "select * from `$tab_fields` where `id` = '$fid'";
            $field = $wpdb->get_row($q,ARRAY_A);
        }else{
//            $q= "select max(`weigh`)+1 as 'weigh', max(`weigh_admin`)+1 as 'weigh_admin',"
//                    . " max(`weigh_public`)+1 as 'weigh_public',max(`id`) as 'name' from `$tab_fields` ";
//            $field_ = $wpdb->get_row($q,ARRAY_A);
//            $field_['name'] =  (int)$field_['name'];
//            $field=['name'=>'f_'.$field_['name'],'weigh'=>$field_['weigh']];
//            $field=['name'=>'f_'.$field_['name'],'weigh'=>$field_['weigh'],
//                'weigh_admin'=>$field_['weigh_admin'],'weigh_public'=>$field_['weigh_public']];
        }
        
//        $this->meta_fields['dump'] = '<$field>';
//        $this->meta_val['dump'] = '<pre>'.print_r($field,1).'</pre>';
//        $this->meta_val['dump'] .= '<pre>'.print_r($_POST,1).'</pre>';
        
//        $hook = 'dsp__init_fields__dspfield';
//        add_action('ds__init_meta_fields__dsproduct', [$this,'ds__init_meta_fields__dsproduct'], 3, 1 );
//        do_action($hook,[$this]);
        
//            $this->_notice( '<div> 5 </div>');
        $this->init_meta_tpls();
        $this->display_meta_box($field);
//            $this->_notice( '<div> 6 </div>');
//            $this->_notice( 'get_magic_quotes_gpc = '.get_magic_quotes_gpc() );
        
    }

    public function removeField ( $fid=false) { // static
        if( !$fid)return;
        global $wpdb;
//        $dsp_fields= $wpdb->prefix . "dsp_fields";
        $dsp_fields= $wpdb->prefix . $this->table_name . "_fields";
        
        $q= "select * from `$dsp_fields` where `id` = '$fid'";
        $field = $wpdb->get_row($q,ARRAY_A);
        
        $q= "delete from `$dsp_fields` where `id` = '$fid'";
        $wpdb->query($q);
        
//        $weight_fields = [];
//        $weight_fields[] = 'weigh';
//        $weight_fields[] = 'weigh_admin';
//        $weight_fields[] = 'weigh_public';
//        $weight_fields[] = 'weigh_order';
        $weight_fields = $this->weight_fields;
        
        foreach($weight_fields as $wf){
            $oldweigh = $field['weigh'];
            $q= "update `$dsp_fields` set `$wf` = `$wf`-1 where  `$wf` >= '$oldweigh' ";
            $wpdb->query($q);
        }
        
        /*
//        $q= "select `weigh` from `$dsp_fields` where `id` = '$fid'";
//        $oldweigh = $wpdb->get_var($q);
        $oldweigh = $field['weigh'];
        $oldweighA = $field['weigh_admin'];
        $oldweighP = $field['weigh_public'];
        $oldweighO = $field['weigh_order'];
        
        $q= "update `$dsp_fields` set `weigh` = `weigh`-1 where  `weigh` >= '$oldweigh' ";
        $wpdb->query($q);
        
        $q= "update `$dsp_fields` set `weigh_admin` = `weigh_admin`-1 where  `weigh_admin` >= '$oldweighA' ";
        $wpdb->query($q);
        
        $q= "update `$dsp_fields` set `weigh_public` = `weigh_public`-1 where  `weigh_public` >= '$oldweighP' ";
        $wpdb->query($q);
        
        $q= "update `$dsp_fields` set `weigh_order` = `weigh_order`-1 where  `weigh_order` >= '$oldweighO' ";
        $wpdb->query($q);
        /**/
        
        
//            $this->_notice( '<div> 4 </div>');
        $m = '<b>Удалено поле: '.$field['title'].' ['.$field['name'].'] </b>';
        echo '<div class="notice notice-success is-dismissible"> <p>'. $m .'</p></div>';
        return $field;
    }

    public function updateWeigh ( $fid=false, $oldweigh = false, $old_fields = []) {
        if( !$fid)return;
        global $wpdb;
//        $dsp_fields= $wpdb->prefix . "dsp_fields";
//        $q= "select `weigh` from `$dsp_fields` where `id` = '$fid'";
//        $newweigh = $wpdb->get_var($q);
        
        $dsp_fields= $wpdb->prefix . $this->table_name . "_fields";
        $q= "select * from `$dsp_fields` where `id` = '$fid'";
        $field = $wpdb->get_row($q,ARRAY_A);
        
//        $weight_fields = [];
//        $weight_fields[] = 'weigh';
//        $weight_fields[] = 'weigh_admin';
//        $weight_fields[] = 'weigh_public';
//        $weight_fields[] = 'weigh_order';
        $weight_fields = $this->weight_fields;
        
        if($fid){
            foreach($weight_fields as $wf){
                $newweigh = $field[$wf];
                $oldweigh = $old_fields['weigh'];

                if($newweigh != $oldweigh && $fid){
                    if($newweigh < $oldweigh){
                        $q= "update `$dsp_fields` set `$wf` = `$wf`+1 where "
                                . "`id` <> '$fid' and `$wf` >= '$newweigh' and `$wf` <= '$oldweigh' ";
                    }else{
                        $q= "update `$dsp_fields` set `$wf` = `$wf`-1 where "
                                . "`id` <> '$fid' and `$wf` <= '$newweigh' and `$wf` >= '$oldweigh' ";
                    }
                    $wpdb->query($q);
                }
            }
        }
        
        /*
        $newweigh = $field['weigh'];
        $newweighA = $field['weigh_admin'];
        $newweighP = $field['weigh_public'];
        $newweighO = $field['weigh_order'];
        
        $oldweigh = $old_fields['weigh'];
        $oldweighA = $old_fields['weigh_admin'];
        $oldweighP = $old_fields['weigh_public'];
        $oldweighO = $old_fields['weigh_order'];
        
        if($newweigh != $oldweigh && $fid){
            if($newweigh < $oldweigh){
                $q= "update `$dsp_fields` set `weigh` = `weigh`+1 where `id` <> '$fid' and `weigh` >= '$newweigh' and `weigh` <= '$oldweigh' ";
            }else{
                $q= "update `$dsp_fields` set `weigh` = `weigh`-1 where `id` <> '$fid' and `weigh` <= '$newweigh' and `weigh` >= '$oldweigh' ";
            }
            $wpdb->query($q);
        }
        
        if($newweighA != $oldweighA && $fid){
            if($newweighA < $oldweighA){
                $q= "update `$dsp_fields` set `weigh_admin` = `weigh_admin`+1 where `id` <> '$fid' and `weigh_admin` >= '$newweighA' and `weigh_admin` <= '$oldweighA' ";
            }else{
                $q= "update `$dsp_fields` set `weigh_admin` = `weigh_admin`-1 where `id` <> '$fid' and `weigh_admin` <= '$newweighA' and `weigh_admin` >= '$oldweighA' ";
            }
            $wpdb->query($q);
        }
        
        if($newweighP != $oldweighP && $fid){
            if($newweighP < $oldweighP){
                $q= "update `$dsp_fields` set `weigh_public` = `weigh_public`+1 where `id` <> '$fid' and `weigh_public` >= '$newweighP' and `weigh_public` <= '$oldweighP' ";
            }else{
                $q= "update `$dsp_fields` set `weigh_public` = `weigh_public`-1 where `id` <> '$fid' and `weigh_public` <= '$newweighP' and `weigh_public` >= '$oldweighP' ";
            }
            $wpdb->query($q);
        }
        
        if($newweighO != $oldweighO && $fid){
            if($newweighO < $oldweighO){
                $q= "update `$dsp_fields` set `weigh_public` = `weigh_public`+1 where `id` <> '$fid' and `weigh_public` >= '$newweighO' and `weigh_public` <= '$oldweighO' ";
            }else{
                $q= "update `$dsp_fields` set `weigh_public` = `weigh_public`-1 where `id` <> '$fid' and `weigh_public` <= '$newweighO' and `weigh_public` >= '$oldweighO' ";
            }
            $wpdb->query($q);
        }
        /**/
//            $this->_notice( '<div> 4 </div>');
    }

    public function updateField( $fid=false,$maxweigh=0) {
        global $wpdb;
//        $dsp_attr= $wpdb->prefix . "dsp_attr";
//        $dsp_fields= $wpdb->prefix . "dsp_fields";
        $dsp_fields= $wpdb->prefix . $this->table_name . "_fields";
        $set = array(
//			'id'            => 'ID',
			'weigh'   => filter_input(INPUT_POST,'weigh',FILTER_SANITIZE_NUMBER_INT),
			'weigh_admin'   => filter_input(INPUT_POST,'weigh_admin',FILTER_SANITIZE_NUMBER_INT),
			'weigh_public'   => filter_input(INPUT_POST,'weigh_public',FILTER_SANITIZE_NUMBER_INT),
			'show_admin'   => filter_input(INPUT_POST,'show_admin',FILTER_SANITIZE_NUMBER_INT),
			'show_pablic'   => filter_input(INPUT_POST,'show_pablic',FILTER_SANITIZE_NUMBER_INT),
			'width_adm_css'   => filter_input(INPUT_POST,'width_adm_css',FILTER_SANITIZE_STRING),
			'width_publ_css'   => filter_input(INPUT_POST,'width_publ_css',FILTER_SANITIZE_STRING),
			'title'   => filter_input(INPUT_POST,'title',FILTER_SANITIZE_STRING),
			'name'   => filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING),
			'active'   => filter_input(INPUT_POST,'active',FILTER_SANITIZE_NUMBER_INT),
			'tpl'   => filter_input(INPUT_POST,'tpl',FILTER_SANITIZE_STRING),
			'type'   => filter_input(INPUT_POST,'type',FILTER_SANITIZE_STRING),
			'search'   => filter_input(INPUT_POST,'search',FILTER_SANITIZE_NUMBER_INT),
			'size'   => filter_input(INPUT_POST,'size',FILTER_SANITIZE_NUMBER_INT),
			'flsize'   => filter_input(INPUT_POST,'flsize',FILTER_SANITIZE_NUMBER_INT),
			'unsigned'   => filter_input(INPUT_POST,'unsigned',FILTER_SANITIZE_NUMBER_INT),
			'zerofill'   => filter_input(INPUT_POST,'zerofill',FILTER_SANITIZE_NUMBER_INT),
			'required'   => filter_input(INPUT_POST,'required',FILTER_SANITIZE_NUMBER_INT),
			'filter'   => filter_input(INPUT_POST,'filter',FILTER_SANITIZE_NUMBER_INT), // 519
			'placeholder'   => filter_input(INPUT_POST,'placeholder',FILTER_SANITIZE_STRING), // 513
			'order'   => filter_input(INPUT_POST,'order',FILTER_SANITIZE_NUMBER_INT),
			'weigh_order'   => filter_input(INPUT_POST,'weigh_order',FILTER_SANITIZE_NUMBER_INT),
			'from_table'   => filter_input(INPUT_POST,'from_table',FILTER_SANITIZE_STRING),
			'from_value'   => filter_input(INPUT_POST,'from_value',FILTER_SANITIZE_STRING),
			'from_title'   => filter_input(INPUT_POST,'from_title',FILTER_SANITIZE_STRING),
			'from_where'   => filter_input(INPUT_POST,'from_where',FILTER_SANITIZE_STRING),
			'def'   => filter_input(INPUT_POST,'def',FILTER_SANITIZE_STRING),
			'vars'   => filter_input(INPUT_POST,'vars',FILTER_SANITIZE_STRING),
			'desc'   => filter_input(INPUT_POST,'desc',FILTER_SANITIZE_STRING),
			'help'   => filter_input(INPUT_POST,'help',FILTER_SANITIZE_STRING),
		);
//        $r = [];
//        $r['\''] = '\\\'';
        if(!get_magic_quotes_gpc()){
            foreach($set as &$s){
                $s = addslashes($s);
            }
        }
        $setvars = explode("\n",$set['vars']);
        $vars=[];
        if(strlen(trim($set['vars']))){
            foreach($setvars as $v){
                $v=explode(':',trim($v));
                $k=$v[0];
                if(isset($v[1]))$v = $v[1];
                else $v = $v[0];
                $vars[$k] = $v;
            }
        }
        $set['vars'] = serialize($vars);
        
        extract($set);
        
//        $weight_fields = [];
//        $weight_fields[] = 'weigh';
//        $weight_fields[] = 'weigh_admin';
//        $weight_fields[] = 'weigh_public';
//        $weight_fields[] = 'weigh_order';
        $weight_fields = $this->weight_fields;
        
        foreach($weight_fields as $wf){
            
            $weigh = $set[$wf];
            $max = $maxweigh[$wf];

            if(!$weigh)$weigh=0;
            if($weigh<0)$weigh=0;
            if($weigh>$max)$weigh=$max;

            $set[$wf] = $weigh;
        }
        /*
        
        $maxweigh = $maxweigh['weigh'];
        $maxweighA = $maxweigh['weigh_admin'];
        $maxweighP = $maxweigh['weigh_public'];
        
        if(!$weigh)$weigh=0;
        if($weigh<0)$weigh=0;
        if($weigh>$maxweigh)$weigh=$maxweigh;
        
        if(!$weigh_admin)$weigh_admin=0;
        if($weigh_admin<0)$weigh_admin=0;
        if($weigh_admin>$maxweighA)$weigh_admin=$maxweighA;
        
        if(!$weigh_public)$weigh_public=0;
        if($weigh_public<0)$weigh_public=0;
        if($weigh_public>$maxweighP)$weigh_public=$maxweighP;
        
        $set['weigh'] = $weigh;
        $set['weigh_admin'] = $weigh_admin;
        $set['weigh_public'] = $weigh_public;
        /**/
        
        $values = [];
        foreach($set as $name=>$value){
			$values[$name]= "`$name` = '$value'";
        }
        $values = implode(', ', $values);
        
//        $q = "insert into $dsp_fields set `name` = '$name', `weigh` = '$weigh', `active` = '$active', `title` = '$title',"
//                . " `weigh_admin` = '$weigh_admin', `weigh_public` = '$weigh_public', `show_admin` = '$show_admin',"
//                . " `show_pablic` = '$show_pablic', `width_adm_css` = '$width_adm_css', `width_publ_css` = '$width_publ_css',"
//                . " `tpl` = '$tpl', `type` = '$type', `search` = '$search', `size` = '$size', "
//                . " `flsize` = '$flsize', `unsigned` = '$unsigned', `zerofill` = '$zerofill', `required` = '$required',"
//                . " `from_table` = '$from_table', `from_value` = '$from_value', `from_title` = '$from_title', `from_where` = '$from_where', "
//                . " `def` = '$def', `vars` = '$vars', `desc` = '$desc', `help` = '$help'";
//        if($fid)
//        $q = "update  $dsp_fields set `name` = '$name', `weigh` = $weigh, `active` = '$active', `title` = '$title',"
//                . " `weigh_admin` = '$weigh_admin', `weigh_public` = '$weigh_public', `show_admin` = '$show_admin',"
//                . " `show_pablic` = '$show_pablic', `width_adm_css` = '$width_adm_css', `width_publ_css` = '$width_publ_css',"
//                . " `tpl` = '$tpl', `type` = '$type', `search` = '$search', `size` = '$size', "
//                . " `flsize` = '$flsize', `unsigned` = '$unsigned', `zerofill` = '$zerofill', `required` = '$required',"
//                . " `filter` = '$filter', `placeholder` = '$placeholder',"
//                . " `from_table` = '$from_table', `from_value` = '$from_value', `from_title` = '$from_title', `from_where` = '$from_where', "
//                . " `def` = '$def', `vars` = '$vars', `desc` = '$desc', `help` = '$help'"
//                . " where `id` = '$fid'";
        
        $old_fields = false;
        if($fid){
            
            $q= "select * from `$dsp_fields` where `id` = '$fid' order by `weigh`";
            $old_fields = $wpdb->get_results($q,ARRAY_A);
        }
        
        $q = "insert into $dsp_fields set " . $values;
        if($fid)
            $q = "update  $dsp_fields set " . $values . " where `id` = '$fid'";
        $wpdb->query($q);
        
        $notice = '<div> Добавлено поле "'.$set['title'].'" ['.$set['name'].'] </div>';
        if($fid)
            $notice = '<div> Обновлено поле "'.$set['title'].'" ['.$set['name'].'] </div>';
        
        if(!$fid){
            $fid = $wpdb->insert_id;
        }
        
//            $this->_notice( '<div> 2 </div>');
        $this->updateAttr($fid,$old_fields);
        $this->_notice( $notice);
        return $fid;
    }
    public function updateAttr($fid,$old_fields = false){
		global $wpdb;
               
//        $dsp_attr= $wpdb->prefix . "dsp_attr";
        $dsp_attr= $wpdb->prefix . $this->table_name . "_value";
//        $dsp_fields= $wpdb->prefix . "dsp_fields";
        $dsp_fields= $wpdb->prefix . $this->table_name . "_fields";
        $q= "show create table `$dsp_attr` ";
        $fields = explode("\n",$wpdb->get_var($q,1));
        $fs = [];
        foreach($fields as $f){
            $f = trim($f);
//                echo '$f<pre>'.print_r($f,1).'</pre>';
//                echo '$f<pre>'.print_r(str_split($f),1).'</pre>';
            if($f[0] == '`'){
                $matches=null;
                $pattern = '/^`([0-9_a-zA-Z]+)`/'; 
                if(preg_match($pattern, $f, $matches)){ 
                    if(isset($matches[1])){
                        $fs[]=$matches[1];
                    }
                }
            }
        }

//        $dsp_fields= $wpdb->prefix . "dsp_fields";
        $q= "select * from `$dsp_fields` where `id` = '$fid' order by `weigh`";
        $fields = $wpdb->get_results($q,ARRAY_A);

//            $this->_notice( '$old_fields<pre>'.print_r($old_fields,1).'</pre>');
//            $this->_notice( '$fields<pre>'.print_r($fields,1).'</pre>');
        foreach($fields as $k=>$field){
            // text varchar int float
            $name = $field['name'];
            $old_name = $old_fields[$k]['name'];
            $object->meta_fields[$name] = $field['title'];
            $object->meta_ftpl[$name] = $field['tpl'];
            $object->meta_val[$name] = $field['def'];
            $object->meta_vars[$name] = false;
            $vars = unserialize($field['vars']);
            if(count($vars))$object->meta_vars[$name] = $vars;

            $ftps = [];
            $ftps['date'] = 'DATE NOT NULL';
            $ftps['time'] = 'TIME NOT NULL';
            $ftps['datetime'] = 'DATETIME NOT NULL';
            $ftps['tinytext'] = 'TINYTEXT NOT NULL';
            $ftps['text'] = 'text NOT NULL';
            $ftps['mediumtext'] = 'MEDIUMTEXT NOT NULL';
            $ftps['longtext'] = 'LONGTEXT NOT NULL';
            $ftps['varchar'] = "VARCHAR(_size_) NOT NULL DEFAULT ''";
            $ftps['char'] = "CHAR(_size_) NOT NULL DEFAULT ''";
            $ftps['int'] = "int(_size_) _UNSIGNED_ _ZEROFILL_ NOT NULL DEFAULT '0'";
            $ftps['integer'] = "INTEGER(_size_) _UNSIGNED_ _ZEROFILL_ NOT NULL DEFAULT '0'";
            $ftps['bigint'] = "BIGINT(_size_) _UNSIGNED_ _ZEROFILL_ NOT NULL DEFAULT '0'";
            $ftps['real'] = "REAL(_size_,_flsize_) _UNSIGNED_ _ZEROFILL_ NOT NULL DEFAULT '0'";
            $ftps['double'] = "DOUBLE(_size_,_flsize_) _UNSIGNED_ _ZEROFILL_ NOT NULL DEFAULT '0'";
            $ftps['float'] = "FLOAT(_size_,_flsize_) _UNSIGNED_ _ZEROFILL_ NOT NULL DEFAULT '0'";
            $ftps['decimal'] = "DECIMAL(_size_,_flsize_) _UNSIGNED_ _ZEROFILL_ NOT NULL DEFAULT '0'";
            $ftps['numeric'] = "NUMERIC(_size_,_flsize_) _UNSIGNED_ _ZEROFILL_ NOT NULL DEFAULT '0'";

            $r = [];
            $r['_size_'] = $field['size'];
            $r['_flsize_'] = $field['flsize'];
            $r['_UNSIGNED_'] = $field['unsigned']?'UNSIGNED':'';
            $r['_ZEROFILL_'] = $field['zerofill']?'ZEROFILL':'';
            $ftps = strtr($ftps[$field['type']],$r);

            $q = "alter table `$dsp_attr` add `{$field['name']}` $ftps ";
            if(in_array($name,$fs)){
                $q = "alter table `$dsp_attr` CHANGE COLUMN `{$field['name']}` `{$field['name']}` $ftps ";
            }else
            if(in_array($old_name,$fs)){
                $q = "alter table `$dsp_attr` CHANGE COLUMN `$old_name` `{$field['name']}` $ftps ";
            }
            $wpdb->query($q);
            
//            $this->n( '$q<pre>'.$q.'</pre>');
//            $this->_notice( '<pre>'.$q.'</pre>');
//            $this->_notice( '<div>'.$q.'</div>');

            try {
                
                $q= "show index from `$dsp_attr` where `key_name` = '{$field['name']}'";
                $index = $wpdb->get_results($q,ARRAY_A);
//                    add_log($index);
                if($field['search'] == 1){
                    if(count($index)==0){
                        $q = "alter table `$dsp_attr` add index `{$field['name']}`  (`{$field['name']}`) ";
                        if(in_array($field['type'], ['text','text','text','text',]))
                        $q = "alter table `$dsp_attr` add FULLTEXT `{$field['name']}`  (`{$field['name']}`) ";
                        $wpdb->query($q);
                    }
                }else{
                    if(count($index)>0){
                        $q = "alter table `$dsp_attr` drop index `{$field['name']}`";
                        $wpdb->query($q);
                    }
                }
            
            } catch (Exception $exc) {
//                echo $exc->getTraceAsString();
            } finally {
                
            }

        }
//            $this->_notice( '<div> 3 </div>');
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
        
        if(isset($this->meta_desc[$f])){
            $at=['class'=>'th_desc'];
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
        $tds[]=strtr($this->meta_tpls[$this->meta_ftpl[$f]],$r);
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
}

