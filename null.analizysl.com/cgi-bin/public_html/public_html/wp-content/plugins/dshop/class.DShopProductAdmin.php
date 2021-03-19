<?php

/* 
 * class.DShopProductAdmin.php
 */
include_once DSHOP_DIR.'trait.DShopAdminOptions.php';
include_once DSHOP_DIR.'lend.php';

class DSopProductField extends lend{
    public function init(){
            $sql = "CREATE TABLE " . '$dsp_fields' . " (
                 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                 `name` VARCHAR(16) NOT NULL comment '',
                 `weigh` int(11) unsigned DEFAULT '0' NOT NULL comment '',
                 `active` int(1) unsigned DEFAULT '1' NOT NULL comment '',
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
                 `desc` text NOT NULL,
                 `help` text NOT NULL,
                 PRIMARY KEY (`id`),
                 INDEX weigh (weigh),
                 UNIQUE INDEX name (name),
                 INDEX title (title)
               )
               ENGINE=InnoDB AUTO_INCREMENT=0 comment='ds_product attr fields';";
        // список мета тегов
        $fields=[];
        $nm=$this->nm;
        $fields['name']='name';
        $fields['weigh']='weigh';
        $fields['active']='active';
        $fields['title']='title';
        $fields['tpl']='tpl';
        $fields['type']='type';
        $fields['search']='search';
        $fields['size']='size';
        $fields['flsize']='flsize';
        $fields['unsigned']='unsigned';
        $fields['zerofill']='zerofill';
        $fields['def']='def';
        $fields['vars']='vars';
        $fields['desc']='desc';
        $fields['help']='help';
        $fields['dump']='dump';
        $this->meta_fields = $fields;
        

        // список мета тегов
        $fields=[];
        $nm=$this->nm;
        $fields['name']='имя поля в базе';
        $fields['weigh']='вес поля в списке';
        $fields['active']='используется/не используется';
        $fields['title']='заголовок';
        $fields['tpl']='шаблон ввода';
        $fields['type']='тип поля в базе';
        $fields['search']='наличие индекса';
        $fields['size']='количество символов';
        $fields['flsize']='кол-во символов после запятой';
        $fields['unsigned']='только положительные';
        $fields['zerofill']='дополнять результат нулями';
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
        $fields['title']='td_i_';
        $fields['tpl']='td_s_';
        $fields['type']='td_s_';
        $fields['search']='td_s_';
        $fields['size']='td_d_';
        $fields['flsize']='td_d_';
        $fields['unsigned']='td_s_';
        $fields['zerofill']='td_s_';
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
        $fields['active']='0';
        $fields['title']='';
        $fields['tpl']='td_ta_';
        $fields['type']='text';
        $fields['search']='0';
        $fields['size']='0';
        $fields['flsize']='0';
        $fields['unsigned']='0';
        $fields['zerofill']='0';
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
                    $tps['td_t_'] = 'text only';
        $ftpl=[];
        $fields['name']=false;
        $fields['weigh']=false;
        $fields['active']=$en;
        $fields['title']=false;
        $fields['tpl']=$tps;
        $fields['type']=$ftps;
        $fields['search']=$en;
        $fields['size']=false;
        $fields['flsize']=false;
        $fields['unsigned']=$en;
        $fields['zerofill']=$en;
        $fields['def']=false;
        $fields['vars']=false;
        $fields['desc']=false;
        $fields['help']=false;
        $fields['dump']=false;
        $this->meta_vars = $fields;
        
		global $wpdb;
        $dsp_attr= $wpdb->prefix . "dsp_attr";
        $dsp_fields= $wpdb->prefix . "dsp_fields";
        $field = [];
        $ft = filter_input(INPUT_POST,'form_type',FILTER_SANITIZE_STRING);
        $fid = filter_input(INPUT_GET,'fid',FILTER_SANITIZE_NUMBER_INT);
        
        
        $q= "select max(`weigh`) from `$dsp_fields` ";
        $maxweigh = $wpdb->get_var($q);
        
        if($fid){
            echo '<input type="hidden" name="fid" value="'.$fid.'">';
            $q= "select * from `$dsp_fields` where `id` = '$fid'";
            $field = $wpdb->get_row($q,ARRAY_A);
        }else{
            $q= "select max(`weigh`)+1 as 'weigh',max(`id`) as 'name' from `$dsp_fields` ";
            $field_ = $wpdb->get_row($q,ARRAY_A);
            $field=['name'=>'f_'.$field_['name'],'weigh'=>$field_['weigh']];
            $maxweigh = $field_['weigh'];
        }
        $oldweigh = $field['weigh'];
//            $this->_notice( '<div> 1 </div>');
        if($ft){
            switch($ft){
                case 'dsp_list':
//                    $this->updateListField();
                    break;
                case 'dsp_add':
//                    $this->addField();
                    $fid = $this->updateField(false,$maxweigh);
                    $this->updateWeigh($fid,$oldweigh);
                    break;
                case 'dsp_edit':
                    $fid  = filter_input(INPUT_POST,'fid',FILTER_SANITIZE_NUMBER_INT);
                    $this->updateField($fid,$maxweigh);
                    $this->updateWeigh($fid,$oldweigh);
                    break;
                case 'dsp_remove':
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
            $q= "select * from `$dsp_fields` where `id` = '$fid'";
            $field = $wpdb->get_row($q,ARRAY_A);
        }else{
            $q= "select max(`weigh`)+1 as 'weigh',max(`id`) as 'name' from `$dsp_fields` ";
            $field_ = $wpdb->get_row($q,ARRAY_A);
            $field=['name'=>'f_'.$field_['name'],'weigh'=>$field_['weigh']];
        }
        
        $this->meta_fields['dump'] = '<$field>';
//        $this->meta_val['dump'] = '<pre>'.print_r($field,1).'</pre>';
//        $this->meta_val['dump'] .= '<pre>'.print_r($_POST,1).'</pre>';
        
        $hook = 'dsp__init_fields__dspfield';
//        add_action('ds__init_meta_fields__dsproduct', [$this,'ds__init_meta_fields__dsproduct'], 3, 1 );
        do_action($hook,[$this]);
        
//            $this->_notice( '<div> 5 </div>');
        $this->init_meta_tpls();
        $this->display_meta_box($field);
//            $this->_notice( '<div> 6 </div>');
        
    }

    public static function removeField ( $fid=false) {
        if( !$fid)return;
        global $wpdb;
        $dsp_fields= $wpdb->prefix . "dsp_fields";
        
        $q= "select * from `$dsp_fields` where `id` = '$fid'";
        $field = $wpdb->get_row($q,ARRAY_A);
        
        $q= "select `weigh` from `$dsp_fields` where `id` = '$fid'";
        $oldweigh = $wpdb->get_var($q);
        
        $q= "delete from `$dsp_fields` where `id` = '$fid'";
        $wpdb->query($q);
        
        $q= "update `$dsp_fields` set `weigh` = `weigh`-1 where  `weigh` >= '$oldweigh' ";
        $wpdb->query($q);
//            $this->_notice( '<div> 4 </div>');
        $m = '<b>Удалено поле: '.$field['title'].' ['.$field['name'].'] </b>';
        echo '<div class="notice notice-success is-dismissible"> <p>'. $m .'</p></div>';
        return $field;
    }

    public function updateWeigh ( $fid=false, $oldweigh = false) {
        if( !$fid)return;
        global $wpdb;
        $dsp_fields= $wpdb->prefix . "dsp_fields";
        $q= "select `weigh` from `$dsp_fields` where `id` = '$fid'";
        $newweigh = $wpdb->get_var($q);
        if($newweigh == $oldweigh || !$fid)return;
        if($newweigh < $oldweigh){
            $q= "update `$dsp_fields` set `weigh` = `weigh`+1 where `id` <> '$fid' and `weigh` >= '$newweigh' and `weigh` <= '$oldweigh' ";
        }else{
            $q= "update `$dsp_fields` set `weigh` = `weigh`-1 where `id` <> '$fid' and `weigh` <= '$newweigh' and `weigh` >= '$oldweigh' ";
        }
        $wpdb->query($q);
//            $this->_notice( '<div> 4 </div>');
    }

    public function updateField( $fid=false,$maxweigh=0) {
        global $wpdb;
        $dsp_attr= $wpdb->prefix . "dsp_attr";
        $dsp_fields= $wpdb->prefix . "dsp_fields";
        $set = array(
//			'id'            => 'ID',
			'weigh'   => filter_input(INPUT_POST,'weigh',FILTER_SANITIZE_NUMBER_INT),
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
			'def'   => filter_input(INPUT_POST,'def',FILTER_SANITIZE_STRING),
			'vars'   => filter_input(INPUT_POST,'vars',FILTER_SANITIZE_STRING),
			'desc'   => filter_input(INPUT_POST,'desc',FILTER_SANITIZE_STRING),
			'help'   => filter_input(INPUT_POST,'help',FILTER_SANITIZE_STRING),
		);
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
        
        if(!$weigh)$weigh=0;
        if($weigh<0)$weigh=0;
        if($weigh>$maxweigh)$weigh=$maxweigh;
        
        $q = "insert into $dsp_fields set `name` = '$name', `weigh` = '$weigh', `active` = '$active', `title` = '$title',"
                . " `tpl` = '$tpl', `type` = '$type', `search` = '$search', `size` = '$size', "
                . " `flsize` = '$flsize', `unsigned` = '$unsigned', `zerofill` = '$zerofill',"
                . " `def` = '$def', `vars` = '$vars', `desc` = '$desc', `help` = '$help'";
        if($fid)
        $q = "update  $dsp_fields set `name` = '$name', `weigh` = $weigh, `active` = '$active', `title` = '$title',"
                . " `tpl` = '$tpl', `type` = '$type', `search` = '$search', `size` = '$size', "
                . " `flsize` = '$flsize', `unsigned` = '$unsigned', `zerofill` = '$zerofill',"
                . " `def` = '$def', `vars` = '$vars', `desc` = '$desc', `help` = '$help'"
                . " where `id` = '$fid'";
        $wpdb->query($q);
        if(!$fid){
            $fid = $wpdb->insert_id;
        }
        
//            $this->_notice( '<div> 2 </div>');
        $this->updateAttr($fid);
        return $fid;
    }
    public function updateAttr($fid){
		global $wpdb;
               
        $dsp_attr= $wpdb->prefix . "dsp_attr";
        $dsp_fields= $wpdb->prefix . "dsp_fields";
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

        $dsp_fields= $wpdb->prefix . "dsp_fields";
        $q= "select * from `$dsp_fields` where `id` = '$fid' order by `weigh`";
        $fields = $wpdb->get_results($q,ARRAY_A);

        foreach($fields as $field){
            // text varchar int float
            $name = $field['name'];
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

            $q = "alter table `$dsp_attr` add $field[name] $ftps ";
            if(in_array($name,$fs)){
                $q = "alter table `$dsp_attr` CHANGE COLUMN `$field[name]` `$field[name]` $ftps ";
            }
            $wpdb->query($q);
            
//            $this->n( '$q<pre>'.$q.'</pre>');
//            $this->_notice( '<pre>'.$q.'</pre>');
//            $this->_notice( '<div>'.$q.'</div>');

            if($field['search'] == 1){
                $q = "alter table `$dsp_attr` add index {$field['name']}  ({$field['name']}) ";
                if(in_array($field['type'], ['text','text','text','text',]))
                $q = "alter table `$dsp_attr` add FULLTEXT {$field['name']}  ({$field['name']}) ";
                $wpdb->query($q);
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


class DShopProductAdmin // extends DShopPayment
{
    public $rpage = 'dsproduct'; // dspayment
    public $page = 'dsproduct.php';
    public $name = 'dsproduct';
    use 
            DShopAdminOptions;
    private static $instance = null;
	private static $initiated = false;
    public function __construct() {
		if ( ! self::$initiated ) {
////			self::_init_hooks();
            $this->init();
            self::$instance = $this;
            self::$initiated = true;
		}
    }
    public function init(){
        $this->rpage = $this->name;
        $this->page = $this->name . '.php';
//        add_action('admin_notices', [$this,'_notice']);
//        add_action('admin_notices', [$this,'_notice']);
//        $this->notice('hello 1');
//        $this->notice('hello 1','error');
//        $this->notice('hello 2','warning');
        
        $ft  = filter_input(INPUT_POST,'form_type',FILTER_SANITIZE_STRING);
        if($ft){
            switch($ft){
                case 'dsp_list':
                    $this->updateListField();
                    break;
                case 'dsp_add':
                    $this->addField();
                    break;
                case 'dsp_edit':
                    $this->updateField();
                    break;
            }
        }
        
//        add_action('admin_menu', [$this,'admin_menu']);
        add_action('admin_menu', [$this,'options']);
        $this->init_options();
        
        add_action('admin_notices', [$this,'_notices']);
    }
    public function updateListField(){
        
    }
    public function addField(){
        
    }
    public function updateField(){
        
    }
    
    /**
     * регистрируем раздел "кабинет" и основную страницу
     * в меню админа
     * https://developer.wordpress.org/resource/dashicons/#shield
     */
    public function admin_menu() {
//        $this->notice('hello 3','info');
        global $ccab_page;
    //    $ page_title
    //    $ menu_title
    //    $ capability
    //    $ menu_slug
    //    $ function
    //    $ icon_url
    //    $ position
        
//        $hook = add_menu_page('DShop', 'DShop', 'manage_options',//1,
//            $this->page, [$this,'page_wrapper'],'dashicons-admin-site');
            
    //    add_menu_page('Параметры Кабинетов', 'Кабинет', 'manage_options',//1,
    //        $ccab_page, 'ccab_page_wrapper','dashicons-shield');
    //    add_action('load-'.$hook, array($this, 'showScreenOptions'));
//        add_action('load-'.$hook, 'showScreenOptions');
    }
    public function init_options(){
        add_action('admin_menu', [$this,'add_option_field_to_general_admin_page']);
    }
    public function payment_options_descrtiption(){
        echo '<b>DShopPayment - настройки</b>';
    }
    public function payment_kassa_descrtiption(){
        echo '<b>DShopPayment - передача информации о перечне товаров/услуг, <br/>количестве, цене и ставке налога по каждой позиции</b>';
    }
    public function payment_info_descrtiption(){
        echo '<b>DShopPayment - описание</b>';
    }
    public function payment_kassa_get_info_descrtiption(){
        echo '<b>DShopPayment - информация о оплате</b>';
    }
    
    public function add_option_field($option_name,$option_title,
            $option_field,$id_block,$page,$arg){
        // регистрируем опцию
//        register_setting( 'general', $option_name);
        register_setting( $this->page, $option_name, $arg);
        // добавляем поле
        $_arg = array( 
                'id' => $option_name, 
                'option_name' => $option_name 
            );
        $arg = $_arg + $arg;
        add_settings_field(
    //		'myprefix_setting-id',
            $option_name, 
            $option_title, 
            [$this,'option_setting_'.$option_field.'_callback'], 
            $page,//'general', 
            $id_block,//'default', 
            $arg
        );
    }
    public function add_option_text($option_name,$option_title,
            $option_field,$id_block,$page,$arg){
        // регистрируем опцию
//        register_setting( 'general', $option_name);
//        register_setting( $this->page, $option_name);
        // добавляем поле
        $_arg = array( 
                'id' => $option_name, 
                'option_name' => $option_name 
            );
        $arg = $_arg + $arg;
        add_settings_field(
    //		'myprefix_setting-id',
            $option_name, 
            $option_title, 
            [$this,'field_info_'.$option_field.'_callback'], 
            $page,//'general', 
            $id_block,//'default', 
            $arg
        );
    }
    public function get_info_payment($val){
        if($val == 1){
            global $DSPs;
    //        $this->n($val);
            $oid = get_option('rbc_payment_info_id','zl');
            $_istest = get_option('rbc_istest_info_get','zl');
    //        $_get = get_option('rbc_payment_info_get','zl');

            
            $res = $DSPs->get_p_info($oid,$_istest);
            $r = [];
            $r["><"] = ">\n<";
            $res = strtr($res,$r);
            $res = htmlspecialchars($res);
            $res = nl2br($res);
            $m='<p><b>Результат запроса о состоянии оплаты для заказа №'.$oid.':</b></p>';
            add_log($m.$res,'clear');
    //        add_log($_id,'admin');
    //        add_log($_get,'admin');
    //        add_log($_istest,'admin');
    //        add_log($val,'admin');
            
        }
        $val = 0;
        return $val;
    }
    public function add_option_field_to_general_admin_page(){
        $id_block = 'payments_settings';
        $title = 'Настройки оплат';
//        $this->n($title);
        $callback = [$this,'payment_options_descrtiption'];
        $page = 'general';
        $page = $this->page;
        add_settings_section( $id_block, $title, $callback, $page );

        
    $option_name = 'rbc_merchant_login';
    $option_title = 'Логин формы робокассы. регистрационная информация (логин)';
    $option_field = 'text';
    $items = [];
    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $arg = [
    ];
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

        
    $option_name = 'rbc_merchant_pass1';
    $option_title = 'Пароль формы робокассы. регистрационная информация (пароль #1)';
    $option_field = 'input';
    $items = [];
    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $arg = [
        'type'=>'password'
    ];
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

    $option_name = 'rbc_merchant_pass2';
    $option_title = 'Пароль ответа робокассы. регистрационная информация (пароль #2)';
    $option_field = 'input';
    $items = [];
    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $arg = [
        'type'=>'password'
    ];
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

    $option_name = 'rbc_payment_desc';
    $option_title = 'Дефолтное описание оплаты (возможны подстановки)';
    $option_field = 'text';
    $items = [];
    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $arg = [
//        'type'=>'password'
    ];
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

    $option_name = 'rbc_paybtn_var';
    $option_title = 'Вариант вывода кнопки оплаты';
    $option_field = 'select';
    $items = [];
//    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $items['no'] = 'No button';
    $items['script'] = 'script';
    $items['script_ext'] = 'script_ext';
    $items['form'] = 'form';
    $arg = [
//        'type'=>'password'
        'items'=>$items,
    ];
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

    
    
    $option_name = 'rbc_merchant_login_test';
    $option_title = 'Тестовый Логин формы робокассы. регистрационная информация (логин)';
    $option_field = 'text';
    $items = [];
    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $arg = [
    ];
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

        
    $option_name = 'rbc_merchant_pass1_test';
    $option_title = 'Тестовый Пароль формы робокассы. регистрационная информация (пароль #1)';
    $option_field = 'input';
    $items = [];
    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $arg = [
        'type'=>'password'
    ];
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

    $option_name = 'rbc_merchant_pass2_test';
    $option_title = 'Тестовый Пароль ответа робокассы. регистрационная информация (пароль #2)';
    $option_field = 'input';
    $items = [];
    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $arg = [
        'type'=>'password'
    ];
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

    $option_name = 'rbc_istest';
    $option_title = 'Режим оплаты (рабочий/тестовый)';
    $option_field = 'select';
    $items = [];
//    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $items['0'] = 'production';
    $items['1'] = 'is test';
    $arg = [
//        'type'=>'password'
        'items'=>$items,
    ];
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

    /*      ======      ======      ======      */
        $id_block = 'payments_get_info';
        $title = 'Проверка состояния оплаты по счёту';
        $callback = [$this,'payment_kassa_get_info_descrtiption'];
        $page = 'kassa_';
        $page = $page.$this->page;
        add_settings_section( $id_block, $title, $callback, $page );
        
    $option_name = 'rbc_payment_info_id';
    $option_title = 'Номер счёта (id)';
    $option_field = 'number';
    $items = [];
//    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
//    $items['0'] = 'Не использовать';
//    $items['1'] = 'Использовать';
    $arg = [
//        'type'=>'password'
        'items'=>$items,
//        'sanitize_callback'=>[$this,'get_info_payment'],
    ];
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

        
    $option_name = 'rbc_istest_info_get';
    $option_title = 'Отправить запрос о оплате (рабочий/тестовый)';
    $option_field = 'select';
    $items = [];
//    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $items['0'] = 'production';
    $items['1'] = 'is test';
    $arg = [
//        'type'=>'password'
        'items'=>$items,
//        'sanitize_callback'=>[$this,'get_info_payment'],
    ];
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

    $option_name = 'rbc_payment_info_get';
    $option_title = 'Отправить запрос';
    $option_field = 'select';
    $items = [];
//    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $items['0'] = 'Не проверять';
    $items['1'] = 'Проверить';
    $arg = [
//        'type'=>'password'
        'items'=>$items,
        'sanitize_callback'=>[$this,'get_info_payment'],
    ];
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

    
    /*      ======      ======      ======      */
        $id_block = 'payments_info';
        $title = 'Фискализация для клиентов Robokassa (планируется)';
        $callback = [$this,'payment_kassa_descrtiption'];
        $page = 'kassa_';
        $page = $page.$this->page;
        add_settings_section( $id_block, $title, $callback, $page );
        
    $option_name = 'rbc_use_receipt';
    $option_title = 'Фискализация';
    $option_field = 'select';
    $items = [];
//    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $items['0'] = 'Не использовать';
    $items['1'] = 'Использовать';
    $arg = [
//        'type'=>'password'
        'items'=>$items,
    ];
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

    
    
//    $option_name = 'delivery_percent';
//    $option_title = 'Процент доставки (доставка на склад)';
//    $option_field = 'number';
//    $items = [];
//    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
//    $arg = array(
//        );
//    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

//    $option_name = 'course_zl_rub';
//    $option_title = 'Коэфициент расчёта конечной валюты';
//    $option_field = 'number';
//    $items = [];
//    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
//    $arg = array(
//        );
//    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

//    $option_name = 'currency_short';
//    $option_title = 'Валюта, краткое';
//    $option_field = 'input';
//    $items = [];
//    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
//    $arg = array(
//            'type'=>'text',
//        );
//    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

    
    /*      ======      ======      ======      */
        $id_block = 'payments_info';
        $title = 'Полезная информация';
        $callback = [$this,'payment_info_descrtiption'];
        $page = 'info_';
        $page = $page.$this->page;
        add_settings_section( $id_block, $title, $callback, $page );
        
    $option_name = 'ResultURL';
    $option_title = 'Адрес страницы ResultURL:';
    $option_field = 'text';
    $items = [];
//    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    
    $items['ResultURL'] =
//            admin_url("admin-post.php").'';
            esc_url(home_url("dspresult/")).'';
//    $items['Код кассы с одной кнопкой «Оплатить»'] = 'https://docs.robokassa.ru/#1224';
//    $items['Варианты кнопок и форм'] = 'https://docs.robokassa.ru/#1239';
//    $items['Технические настройки'] = 'https://docs.robokassa.ru/#1160';
    $items['method'] = 'Метод приёма данных:<br/><b>POST</b>';
    $arg = [
//        'type'=>'password'
        'items'=>$items,
    ];
    $this->add_option_text($option_name,$option_title,$option_field,$id_block,$page,$arg );

    $option_name = 'SuccessURL';
    $option_title = 'Адрес страницы SuccessURL:';
    $option_field = 'text';
    $items = [];
//    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    
    $items['SuccessURL'] =
            esc_url(home_url("dssuccess/")).'';
    $items['SuccessURL'] =
            esc_url(home_url("cart/order/")).'';
    $items['method'] = 'Метод приёма данных:<br/><b>POST</b>';
//    $items['Код кассы с одной кнопкой «Оплатить»'] = 'https://docs.robokassa.ru/#1224';
//    $items['Варианты кнопок и форм'] = 'https://docs.robokassa.ru/#1239';
//    $items['Технические настройки'] = 'https://docs.robokassa.ru/#1160';
    $arg = [
//        'type'=>'password'
        'items'=>$items,
    ];
    $this->add_option_text($option_name,$option_title,$option_field,$id_block,$page,$arg );

    $option_name = 'FailURL';
    $option_title = 'Адрес страницы FailURL:';
    $option_field = 'text';
    $items = [];
//    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    
    $items['FailURL'] =
            esc_url(home_url("/dsfail/")).'';
    $items['FailURL'] =
            esc_url(home_url("/cart/order/")).'';
    $items['method'] = 'Метод приёма данных:<br/><b>POST</b>';
//    $items['Код кассы с одной кнопкой «Оплатить»'] = 'https://docs.robokassa.ru/#1224';
//    $items['Варианты кнопок и форм'] = 'https://docs.robokassa.ru/#1239';
//    $items['Технические настройки'] = 'https://docs.robokassa.ru/#1160';
    $arg = [
//        'type'=>'password'
        'items'=>$items,
    ];
    $this->add_option_text($option_name,$option_title,$option_field,$id_block,$page,$arg );

        
    $option_name = 'il1';
    $option_title = 'Полезные ссылки:';
    $option_field = 'dl_links';
    $items = [];
//    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    
    $items['Технические настройки'] = 'https://docs.robokassa.ru/#1160';
    $items['Описание переменных, параметров и значений'] =
            'https://docs.robokassa.ru/#1222';
    $items['Параметры проведения тестовых платежей'] =
            'https://docs.robokassa.ru/#4140';
    $items['Код кассы с одной кнопкой «Оплатить»'] = 'https://docs.robokassa.ru/#1224';
    $items['Варианты кнопок и форм'] = 'https://docs.robokassa.ru/#1239';
    $items['Оповещение об оплате на ResultURL'] = 'https://docs.robokassa.ru/#1250';
    $items['Фискализация для клиентов Robokassa. Облачное решение. Кассовое решение. Решение Робочеки'] =
            'https://docs.robokassa.ru/#1192';
    $arg = [
//        'type'=>'password'
        'items'=>$items,
    ];
    $this->add_option_text($option_name,$option_title,$option_field,$id_block,$page,$arg );

    /*      ======      ======      ======      */
//        $option_name = 'prod_parser_debug';
//        $option_title = 'тестирование парсера';
//        $option_field = 'select';
//        $arg = array( 
//                'items'=>[
//                    ''=>'production',
//                    '1'=>'debug',
//                ],
//            );
//        $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

        
//    $option_name = 'prod_parser_img_pattern';
//    $option_title = 'выбрать паттерн изображений';
//    $option_field = 'select';
//    $items = [];
//    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
//    $arg = array( 
//            'items'=>$items,
//        );
//    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

//    $option_name = 'prod_parser_img_patterns';
//    $option_title = 'паттерны изображений';
//    $option_field = 'textarea';
//    $arg = array( 
//                'cols' => '70',
//        );
//    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

//        $aleg = new Allegro();
//        foreach ($aleg->xpath_t as $key => $value) {
//            $option_name = 'parser_path_'.$key;
//            $option_title = 'Путь парсера к данным поля "'.$value.'"';
//            $option_field = 'textarea';
//            $arg = array( 
//                    'cols' => '120', 
//                );
//            $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );
//        }
        
//        $option_name = 'block_parser_path_1';
//        $option_title = 'Путь парсера к данным поля "Наименование"';
//        $option_field = 'textarea';
//        $arg = array( 
//                'cols' => '120', 
//            );
//        $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );
//
//
//        $option_name = 'block_parser_path_2';
//        $option_title = 'Путь парсера к данным поля "Цена"';
//        $option_field = 'textarea';
//        $arg = array( 
//                'cols' => '120', 
//            );
//        $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

        /*
        $option_name = 'block_asside_facebook';
        // регистрируем опцию
        register_setting( 'general', $option_name );
        // добавляем поле
        add_settings_field( 
    //		'myprefix_setting-id',
            'block_asside_facebook', 
            'Блок в колонке для кода фейсбука', 
            [$this,'myprefix_setting_callback_function'], 
            'general', 
            'default', 
            array( 
                'id' => 'block_asside_facebook', 
                'option_name' => 'block_asside_facebook' 
            )
        );
        register_setting( 'general', 'blog_link_way' );
        add_settings_field( 
    //		'myprefix_setting-id',
            'blog_link_way',
            'Направление ссылок', 
            [$this,'lending_change_link_way_function'], 
            'general', 
            'default', 
            array(
                'id' => 'blog_link_way',
                'option_name' => 'blog_link_way',
                'items'=>[
                    'desktop'=>'desktop',
                    'mobile'=>'mobile',
                ],
            )
        );
        /**/
    }
    
    /**
notice-success - для успешных операций. Зеленая полоска слева.
notice-error - для ошибок. Красная полоска слева.
notice-warning - для предупреждений. Оранжевая полоска слева.
notice-info - для информации. Синяя полоска слева.
is-dismissible - добавляет иконку-кнопку "закрыть" (крестик в конце блока).
     *  Иконка добавляется через javascript.
     *  По клику на нее блок-заметка будет скрыт (удален).
     * 
     * @param type $m
     * @param type $s
     */
    public function n($m='',$s='success'){
        $this->addNtce($m,$s);
    }
    public function notice($m='',$s='success'){
        $this->addNtce($m,$s);
    }
    public $ntc=[];
    public function addNtce($m='',$c='success'){
        $this->ntc[]=['c'=>$c,'m'=>$m];
    }
    public function _notices(){
        foreach($this->ntc as $n)$this->_notice($n['m'],$n['c']);
        showLogInfo('admin');
    }
    public function _notice($m='',$s='success'){
        $class = 'notice-success';
        $class = 'notice-'.$s;
        $message = "Ошибка сохранения";
        echo '<div class="notice '.$class.' is-dismissible"> <p>'. $m .'</p></div>';
    }

    /**
     * обёртка для страницы кабинета shortcodes
     * @global string $true_page
     */
    public function page_wrapper() {
        
    //    $atr = func_get_args();
    //    add_log($atr);
        // тут уже будет находиться содержимое страницы
        global $ccab_page;
        ?><div class="wrap">
        <div id="icon-themes" class="icon32"></div>
        <h2>Настройки товара</h2>
       <?php
        ob_start();
        settings_errors();
        
        $active_tab = 'list_fields';
        if( isset( $_GET[ 'tab' ] ) ) {
            $active_tab = $_GET[ 'tab' ];
        } // end if
        $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'list_fields';
        $tab  = filter_input(INPUT_GET,'tab',FILTER_SANITIZE_STRING);

        global $display_sub_button;
        $display_sub_button = 1;
        
        global $form_action;
        $form_action = 'options.php';
        
        $r_page = $this->rpage;
        $form_action = "edit.php?post_type={$this->name}&page=".$r_page.'_fields';
        if($tab && $tab == 'delete_field'){
//            $form_action.='&tab='.$tab;
        }else
        if($tab){
            $form_action.='&tab='.$tab;
        }
        
        $tabs = [];
        $tabs['list_fields'] = 'List';
        $tabs['add_field'] = 'Add';
        $fid  = filter_input(INPUT_GET,'fid',FILTER_SANITIZE_NUMBER_INT);
        if($fid && $tab == 'edit_field'){
            $tabs['edit_field'] = 'Edit';
            $form_action.='&fid='.$fid;
        }
        if($fid && $tab == 'delete_field'){
            $tabs['delete_field'] = 'Delete';
//            $form_action.='&fid='.$fid;
        }
        
        $tabs = apply_filters('ds_dsproduct_settings_extml__tabs', $tabs, $this);
    ?>
        <h2 class="nav-tab-wrapper">
            <?php
                foreach ($tabs as $tn => $tt) { // name => title
                    ?>
            <a href="edit.php?post_type=<?=$this->name?>&page=<?=$this->rpage?>_fields&tab=<?=$tn?>" class="nav-tab <?php echo $active_tab == $tn ? 'nav-tab-active' : ''; ?>"><?=$tt?></a>
            <?php
                }
            /*
            <a href="edit.php?post_type=<?=$this->name?>&page=<?=$this->rpage?>_settings.php&tab=display_options" class="nav-tab <?php echo $active_tab == 'display_options' ? 'nav-tab-active' : ''; ?>">Display Options</a>
             <a href="edit.php?post_type=<?=$this->name?>&page=<?=$this->rpage?>_settings.php&tab=robokassa_options" class="nav-tab <?php echo $active_tab == 'robokassa_options' ? 'nav-tab-active' : ''; ?>">Robokassa Options</a>
            */
            do_action('ds_dsproduct_settings_extml__add_tab_link', $this,$this->name,$this->rpage, $active_tab);
            ?>
        </h2>
        
        <form method="post" enctype="multipart/form-data" action="<?=$form_action?>" >
            <?php
        if( $active_tab == 'list_fields' ) {
            echo '<input type="hidden" name="form_type" value="dsp_list">';
            settings_fields($this->page); // меняем под себя только здесь
            
            global $lt;
            $lt->display();
//            submit_button();
        }
        if( $active_tab == 'add_field' ) {
            echo '<input type="hidden" name="form_type" value="dsp_add">';
            settings_fields($this->page); // меняем под себя только здесь
            
            $pf = new DSopProductField();
            submit_button();
        }
        if( $active_tab == 'edit_field' ) {
            if($fid){
                echo '<input type="hidden" name="form_type" value="dsp_edit">';
                settings_fields($this->page); // меняем под себя только здесь

    //            $this->build_edit();
                $pf = new DSopProductField();
                submit_button();
            }else{
                $r_page = $this->rpage;
                $form_action = "edit.php?post_type={$this->name}&page=".$r_page.'_fields';
                $m = 'Нечего редактировать.<br/>';
                echo '<div class=""> <h3>'. $m .'</h3></div>';
                $ccl = sprintf( '<a href="%s">%s</a>', $form_action, __('Cancel','hb-users') );
                echo $ccl;
            }
        }
        if( $active_tab == 'delete_field' ) {
//            var_dump($fid);
            if($fid){
                echo '<input type="hidden" name="form_type" value="dsp_remove">';
                echo '<input type="hidden" name="fid" value="'.$fid.'">';
                echo '<input type="hidden" name="isremove" value="ok">';
                settings_fields($this->page); // меняем под себя только здесь

                global $wpdb;
                $dsp_fields= $wpdb->prefix . "dsp_fields";

                $q= "select * from `$dsp_fields` where `id` = '$fid'";
                $field = $wpdb->get_row($q,ARRAY_A);

                $m = 'Вы действительно хотите удалить запись:<br/>'
                    .$field['title'].' ['.$field['name'].'] ?';
                echo '<div class=""> <h3>'. $m .'</h3></div>';

                $m = '<b>Удаляемое поле, будет удалено безвозвратно.</b>';
                echo '<div class="notice notice-warning is-dismissible"> <p>'. $m .'</p></div>';

                $btn = get_submit_button(__( 'Remove' ));
    //            $form_action
                $r_page = $this->rpage;
                $form_action = "edit.php?post_type={$this->name}&page=".$r_page.'_fields';
                $ccl = sprintf( '<a href="%s">%s</a>', $form_action, __('Cancel','hb-users') );
                $r = ['</p>'=>' &nbsp; '.$ccl.'</p>'];
                echo strtr($btn,$r);
    //            $this->build_edit();
                $pf = new DSopProductField();
            }else{
                $r_page = $this->rpage;
                $form_action = "edit.php?post_type={$this->name}&page=".$r_page.'_fields';
                $m = 'Нечего удалять.<br/>';
                echo '<div class=""> <h3>'. $m .'</h3></div>';
                $ccl = sprintf( '<a href="%s">%s</a>', $form_action, __('Cancel','hb-users') );
                echo $ccl;
            }
        }
        if( $active_tab == 'robokassa_options' ) {
            settings_fields($this->page); // меняем под себя только здесь
//            settings_fields('ccab_options'); // меняем под себя только здесь
            // (название настроек)
            do_settings_sections($this->page);
            do_settings_sections('kassa_'.$this->page);
    //        do_settings_sections('shortcodes_'.$ccab_page);
    //        do_settings_sections('shortcodes_'.$ccab_page);
    //        echo 'show shortcodes';
//            ccab_show_sortcodes();
        }
        do_action('ds_dsproduct_settings_extml__do_tab_sections', $this,$this->page, $active_tab);
//        if($display_sub_button) submit_button();
            ?>
        </form>
       <?php
        if( $active_tab == 'display_options' ) {
        } 
        if( $active_tab == 'robokassa_options' ) {
            do_settings_sections('info_'.$this->page);
        } // end if/else
        do_action('ds_dsproduct_settings_extml__do_tab_footer_info', $this,$this->page, $active_tab);
       /*
        * 
            <p class="submit">  
                    <input type="submit" class="button-primary"
                           value="<?php _e('Save Changes') ?>" />  
            </p>
        */
        $out=ob_get_clean();
//        showLogInfo('admin');
        echo $out;
       ?>
    </div><?php
    }
    public function build_edit(){
        
    }
    /*  ==========  */

    /**
     * регистрируем страницы подменю в разделе "кабинет"
     * @global string $ccab_page
     */
    public function options() {
        $page = $this->page;
//        $page = null;
//        $page = 'options.php';
        $page = 'dshop.php'; // страница настроек магазина
        $r_page = $this->rpage;
    //    $ parent slug
    //    $ page title
    //    $ menu title
    //    $ capability
    //    $ menu slug
    //    $ function
//		$ptype_obj = get_post_type_object( $this->name );
//        $page = $ptype_obj->show_in_menu;
//        $this->n('<pre>'.print_r($ptype_obj,1).'</pre>');
//		$ptype_obj = get_post_type_object( 'post' );
//		$ptype_obj = get_post_type_object( 'dspayment' );
//        $page = $ptype_obj->show_in_menu;
//        $this->n('<pre>'.print_r($ptype_obj,1).'</pre>');
//        $screen = get_current_screen();
//    global $_parent_pages;
//        $this->n('<pre>:'.print_r($_parent_pages,1).'</pre>');
        
//        add_submenu_page( $page, 'Shortcodes', 'Shortcodes', 'manage_options',
//            ''.$r_page.'/shortcodes.php', 'ccab_page_shortcodes_wrapper');  
//        add_submenu_page( $page, 'Параметры', 'Параметры', 'manage_options',
//            "edit.php?post_type={$this->name}".'&page='.$r_page.'_settings.php', [$this,'page_wrapper']);
        
        
        $page = 'dshop.php'; // страница настроек магазина
        $page = 'dshop.php'; // страница настроек магазина
//        add_submenu_page( "edit.php?post_type={$this->name}",
//                'Настройки товара', 'Настройки товара', 'manage_options',
//            ''.$r_page.'__settings.php', [$this,'page_wrapper']);
        $hookname = add_submenu_page( "edit.php?post_type={$this->name}",
                'Доп поля товара', 'Доп поля товара', 'manage_options',
            ''.$r_page.'_fields', [$this,'page_wrapper']);
        add_action( "load-$hookname", [$this,'init_table_page_load'] );

        
		if(0)add_submenu_page(
                $ptype_obj->show_in_menu,
                $ptype_obj->labels->name,
                $ptype_obj->labels->all_items,
                $ptype_obj->cap->edit_posts,
                "edit.php?post_type=$ptype" );
        
    //    add_submenu_page( $ccab_page, 'Параметры 3', 'Параметры 3', 'manage_options',
    //        'p3_'.$ccab_page.'', 'true_option_page2');

    //        add_submenu_page($parent_slug, $page_title, $menu_title,
    //                $capability, $menu_slug, $function);

    }
    public function init_table_page_load(){
        
        $fid  = filter_input(INPUT_POST,'fid',FILTER_SANITIZE_STRING);
        $ft  = filter_input(INPUT_POST,'form_type',FILTER_SANITIZE_STRING);
        $isok  = filter_input(INPUT_POST,'isremove',FILTER_SANITIZE_STRING);
        if($fid){
            if($ft=='dsp_remove'){
                if($isok=='ok'){
//                    $this->_notice( '<div> 0 </div>');
                    DSopProductField::removeField($fid);
                }
            }
        }
        $ltf = 'class.DShopProductListTable.php';
        require_once $ltf;
        global $lt;
        $r_page = $this->rpage;
        $lt = new DShopProductListTable("edit.php?post_type={$this->name}&page=".$r_page.'_fields');
    }
}