<?php

/* 
 * class.WSDListListTable.php
 * 
 * https://wp-kama.ru/function/wp_list_table
 */

class WSDListListTable extends WP_List_Table {

    public $clms = [];
    public $columns = [];
    public $get_columns = [];
    public $url_edit='';
    public $table_name='';
    public $column_title='';
	function __construct($table = '',$url_edit=''){
		global $wpdb;
        $this->table_name = $table;
        $this->column_title = '';
//        $dsp_attr= $wpdb->prefix . "dsp_attr";
//        $dsp_fields= $wpdb->prefix . "dsp_fields";
//        $dsp_fields= $wpdb->prefix . "dsp_fields";
        $tab_value= $wpdb->prefix . $this->table_name . "_value";
//        $q= "select `name`,`title` from `$tab_value` ";
//        $this->clms  = $wpdb->get_results($q,OBJECT_K);
        $tab_fields= $wpdb->prefix . $this->table_name . "_fields";
        $q= "select `name` from `$tab_fields` where `weigh` = 0";
        $column_title = $wpdb->get_var($q);
        if($column_title)$this->column_title = $column_title;
        
        $this->url_edit=$url_edit;
        
		parent::__construct(array(
			'singular' => 'log',
			'plural'   => 'logs',
			'ajax'     => false,
		));

		$this->bulk_action_handler();

		// screen option
		add_screen_option( 'per_page', array(
			'label'   => 'Показывать на странице',
			'default' => 100,
			'option'  => 'logs_per_page',
		) );

		$this->prepare_items();
        
            
        $columns   = [];
        $columns['cb'] = '<input type="checkbox" />';
        $columns['id'] = 'id';
//        global $wpdb;
        $tab_name = $this->table_name;
        $tab_fields= $wpdb->prefix . $this->table_name . "_fields";
        
        
        $q= "select * from `$tab_fields` where `show_admin` = 1 order by `weigh_admin`";
        $fields = $wpdb->get_results($q,ARRAY_A);
        foreach($fields as $field){
            
            $columns[ $field['name'] ] = $field['title'] ;
//            if(strlen($field['width_adm_css'])>0){
//                echo 'table.logs .column-' . $field['name'] . '{ width:' . $field['width_adm_css'] . "; }\n" ;
//            }
        }
        $this->get_columns = $columns;

		add_action( 'wp_print_scripts', [ $this, '_list_table_css' ] ); // __CLASS__
	}

	// создает элементы таблицы
	function prepare_items(){
		global $wpdb;
        $dsp_attr= $wpdb->prefix . "dsp_attr";
        $dsp_fields= $wpdb->prefix . "dsp_fields";
        $table_ml_groups = $wpdb->prefix . "ml_groups";
        
        $tab_value= $wpdb->prefix . $this->table_name . "_value";
//        echo $tab_value;
        $q= "select count(*) from `$tab_value` ";
        $fcou  = $wpdb->get_var($q,0);

		// пагинация
		$per_page = get_user_meta( get_current_user_id(), get_current_screen()->get_option( 'per_page', 'option' ), true ) ?: 100;

		$this->set_pagination_args( array(
			'total_items' => $fcou,
			'per_page'    => $per_page,
		) );
		$cur_page = (int) $this->get_pagenum(); // желательно после set_pagination_args()

		// элементы таблицы
		// обычно элементы получаются из БД запросом
		// $this->items = get_posts();

		// чтобы понимать как должны выглядеть добавляемые элементы
//		$this->items = array(
//			(object) array(
//				'id'   => 2,
//				'key'  => 'aaaaaaaaaa777777',
//				'name' => 'Коля',
//				'dump' => 'Коля',
//			),
////			(object) array(
////				'id'   => 3,
////				'key'  => 'ddddddd555555555',
////				'name' => 'Витя',
////			),
////			(object) array(
////				'id'   => 4,
////				'key'  => 'hhhhhhhhhhh999999',
////				'name' => 'Петя',
////			),
////			(object) array(
////				'id'   => 4,
////				'key'  => 'hhhhhhhhhhh999999',
////				'name' => 'Петя',
////			),
////			(object) array(
////				'id'   => 4,
////				'key'  => 'hhhhhhhhhhh999999',
////				'name' => 'Петя',
////				'dump' => '<pre>'.print_r($this->clms,1).'</pre>',
////			),
//		);
//        $dsp_fields= $wpdb->prefix . "dsp_fields";
//        $q= "select * from `$dsp_fields` order by `weigh`";
        $tab_fields= $wpdb->prefix . $this->table_name . "_fields";
        $q= "select * from `$tab_fields` order by `weigh`";
        $fields = $wpdb->get_results($q,ARRAY_A);
        
        $select = [];
        $join = [];
        $select[] = "a.`id` as 'id'";
        
        $join_titles = 'bcdefghijklmnopqrstuvwxyz';
        $join_titles = str_split($join_titles);
		foreach($fields as $field){
            if($field['tpl']!='td_s_from_')
                $select[] = "\na.`{$field['name']}` as '{$field['name']}'";
            else{
                $join_t = array_shift($join_titles);
                $field_t = $field['name'];
                $field_f = "\n`".$field['name']."`";
                $table = $wpdb->prefix.$field['from_table'];
                $values = trim($field['from_value']);
                $titles = $field['from_title'];
                
                $titles = explode(',',$titles);
                $v = [];
                foreach($titles as $t){
                    if(!strlen(trim($t))){$v[]="'$t'";}else{$v[]="$join_t.`$t`";}
                }
                if(strlen(trim($field['from_value']))&&count($v)>0){
                    $v  = implode(',',$v);
                    $field_f = "concat($v)";
                }
                $select[] = "\n$field_f as '$field_t'";
                $join[] = "\nleft join `$table` as $join_t on $join_t.`$values` = a.`$field_t` ";
            }
        }
        $select = implode(',',$select);
        $join = implode(' ',$join);
        
        $tab_value= $wpdb->prefix . $this->table_name . "_value";
        $q= "select * from `$tab_value` order by `id`";
        $q= "select $select \nfrom `$tab_value` as a $join \norder by  a.`id`";
//        $this->_notice('<div><pre>'.print_r($q,1).'</pre></div>');
        ob_start();
        $items = $wpdb->get_results($q,ARRAY_A);
        $err = ob_get_clean();
        if($err)$this->n($err);
        if($err){
        $this->_notice('<div><pre>'.print_r($q,1).'</pre></div>');
            $this->_notice($err);
        }
		$this->items = $items;
//        add_log($this->items);
//        echo '<pre>'.print_r($this->items,1).'</pre>';
//        echo '<pre>'.print_r($this->get_column_info(),1).'</pre>';
	}

	// колонки таблицы
	function get_columns(){
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
//        add_log($this->get_columns);
		if(1)return $this->get_columns;
//        echo '<pre>'.print_r($columns,1).'</pre>';
		return array(
			'cb'            => '<input type="checkbox" />',
			'id'            => 'id',
			'title'   => 'title',
			'address'   => 'address',
//			'customer_name' => 'Имя',
//			'license_key'   => 'License Key',
//			'dump'   => 'Dump',
            
//			'title'   => 'title',
//			'name'   => 'name',
//			'weigh'   => 'weigh',
//			'active'   => 'active',
//			'tpl'   => 'tpl',
//			'type'   => 'type',
//			'search'   => 'Index',
//			'size'   => 'Length',
//			'flsize'   => 'Decimals',
//			'unsigned'   => 'unsigned',
//			'zerofill'   => 'zerofill',
		);
	}
//	function _column_headers(){
//		return array(
//			'cb'            => '<input type="checkbox" />',
//			'id'            => 'ID',
//			'customer_name' => 'Имя',
//			'license_key'   => 'License Key',
//		);
//	}

	// сортируемые колонки
	function get_sortable_columns(){
		return array(
//			'customer_name' => array( 'name', 'desc' ),
		);
	}

	protected function get_bulk_actions() {
		return array(
//			'delete' => 'Delete',
//			'deactive' => 'Deactive',
		);
	}

	// Элементы управления таблицей. Расположены между групповыми действиями и панагией.
	function extra_tablenav( $which ){
//		echo '<div class="alignleft actions">HTML код полей формы (select). Внутри тега form...</div>';
	}

	// вывод каждой ячейки таблицы -------------

	static function _list_table_css(){
		?>
		<style>
            /*.column-title{ width:8em; }*/
            .column-title{ width:25%; }
			table.logs .column-id{ width:2em; }
			table.logs .column-license_key{ width:8em; }
			table.logs .column-customer_name{ width:15%; }
            <?php
            global $wpdb;
            $tab_name = $this->table_name;
            $tab_fields= $wpdb->prefix . $tab_name . "_fields";
            $q= "select * from `$tab_fields` order by `weigh`";
            $fields = $wpdb->get_results($q,ARRAY_A);
            foreach($fields as $field){
                if(strlen($field['width_adm_css'])>0){
                    echo 'table.logs .column-' . $field['name'] . '{ width:' . $field['width_adm_css'] . "; }\n" ;
                }
            }
            ?>
		</style>
		<?php
//        echo '<pre>'.print_r($fields,1).'====</pre>';
	}

	// вывод каждой ячейки таблицы...
	function column_default( $item, $colname ){
//        add_log($colname);
//        add_log($item);

		if( $colname === $this->column_title ){
			// ссылки действия над элементом
			$actions = array();
			$actions['edit'] = sprintf( '<a href="%s">%s</a>', $this->url_edit.'&tab=edit&fid='.$item['id'], __('edit','hb-users') );

			$actions['trash'] = sprintf( '<a href="%s">%s</a>', $this->url_edit.'&tab=delete&fid='.$item['id'], __('delete','hb-users') );

			return esc_html( $item[$colname] ) . $this->row_actions( $actions );
//			return esc_html( $item['id'] ) . $this->row_actions( $actions );
		}
		else
		if( $colname === 'customer_name' ){
			// ссылки действия над элементом
			$actions = array();
			$actions['edit'] = sprintf( '<a href="%s">%s</a>', '#', __('edit','hb-users') );

			return esc_html( $item->name ) . $this->row_actions( $actions );
		}
		else
		if( $colname === 'address' ){
			return nl2br ($item['address']);
		}
		else
		if( $colname === 'dump' ){
			return $item->$colname;
		}
		else {
			return isset($item[$colname]) ? $item[$colname] : print_r($colname, 1);
//			return isset($item->$colname) ? $item->$colname : print_r($colname, 1);
//			return isset($item->$colname) ? $item->$colname : print_r($item, 1);
		}

	}

	// заполнение колонки cb
	function column_cb( $item ){
		echo '<input type="checkbox" name="licids[]" id="cb-select-'. $item['id'] .'" value="'. $item['id'] .'" />';
//		echo '<input type="checkbox" name="licids[]" id="cb-select-'. $item->id .'" value="'. $item->id .'" />';
	}

	// остальные методы, в частности вывод каждой ячейки таблицы...

	// helpers -------------

	private function bulk_action_handler(){
		if( empty($_POST['licids']) || empty($_POST['_wpnonce']) ) return;

		if ( ! $action = $this->current_action() ) return;

		if( ! wp_verify_nonce( $_POST['_wpnonce'], 'bulk-' . $this->_args['plural'] ) )
			wp_die('nonce error');

		// делает что-то...
		die( $action ); // delete
		die( print_r($_POST['licids']) );

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
        foreach($this->ntc as $n){
            $this->_notice($n['m'],$n['c']);
            add_log($n['m']);
        }
        showLogInfo('admin');
    }
    public function _notice($m='',$s='success'){
        $class = 'notice-success';
        $class = 'notice-'.$s;
        $message = "Ошибка сохранения";
        if(is_array($m)|| is_object($m))
            $m= '<pre>'.print_r($m,1).'</pre>';
        echo '<div class="notice '.$class.' is-dismissible"> <p>'. $m .'</p></div>';
    }

	/** / 
	// Пример создания действий - ссылок в основной ячейки таблицы при наведении на ряд. 
	// Однако гораздо удобнее указать их напрямую при выводе ячейки - см ячейку customer_name...

	// основная колонка в которой будут показываться действия с элементом
	protected function get_default_primary_column_name() {
		return 'title';
	}

	// действия над элементом для основной колонки (ссылки)
	protected function handle_row_actions( $post, $column_name, $primary ) {
		if ( $primary !== $column_name ) return ''; // только для одной ячейки

		$actions = array();

		$actions['edit'] = sprintf( '<a href="%s">%s</a>', '#', __('edit','hb-users') );

		return $this->row_actions( $actions );
	}
	/**/

}
