<?php

/* 
 * class.DShopProductListTable.php
 * 
 * https://wp-kama.ru/function/wp_list_table
 */

class DShopProductListTable extends WP_List_Table {

    public $clms = [];
    public $url_edit='';
	function __construct($url_edit=''){
		global $wpdb;
        $dsp_attr= $wpdb->prefix . "dsp_attr";
        $dsp_fields= $wpdb->prefix . "dsp_fields";
        $q= "select `name`,`title` from `$dsp_fields` ";
        $this->clms  = $wpdb->get_results($q,OBJECT_K);
        
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

		add_action( 'wp_print_scripts', [ __CLASS__, '_list_table_css' ] );
	}

	// создает элементы таблицы
	function prepare_items(){
		global $wpdb;
        $dsp_attr= $wpdb->prefix . "dsp_attr";
        $dsp_fields= $wpdb->prefix . "dsp_fields";
        $q= "select count(*) from `$dsp_fields` ";
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
		$this->items = array(
			(object) array(
				'id'   => 2,
				'key'  => 'aaaaaaaaaa777777',
				'name' => 'Коля',
				'dump' => 'Коля',
			),
//			(object) array(
//				'id'   => 3,
//				'key'  => 'ddddddd555555555',
//				'name' => 'Витя',
//			),
//			(object) array(
//				'id'   => 4,
//				'key'  => 'hhhhhhhhhhh999999',
//				'name' => 'Петя',
//			),
//			(object) array(
//				'id'   => 4,
//				'key'  => 'hhhhhhhhhhh999999',
//				'name' => 'Петя',
//			),
			(object) array(
				'id'   => 4,
				'key'  => 'hhhhhhhhhhh999999',
				'name' => 'Петя',
				'dump' => '<pre>'.print_r($this->clms,1).'</pre>',
			),
		);
        $dsp_fields= $wpdb->prefix . "dsp_fields";
        $q= "select * from `$dsp_fields` order by `weigh`";
        $fields = $wpdb->get_results($q,ARRAY_A);
		$this->items = $fields;
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
		return array(
			'cb'            => '<input type="checkbox" />',
//			'id'            => 'ID',
//			'customer_name' => 'Имя',
//			'license_key'   => 'License Key',
//			'dump'   => 'Dump',
			'title'   => 'title',
			'name'   => 'name',
			'weigh'   => 'weigh',
			'active'   => 'active',
			'tpl'   => 'tpl',
			'type'   => 'type',
			'search'   => 'Index',
			'size'   => 'Length',
			'flsize'   => 'Decimals',
			'unsigned'   => 'unsigned',
			'zerofill'   => 'zerofill',
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
            .column-title{ width:8em; }
			table.logs .column-id{ width:2em; }
			table.logs .column-license_key{ width:8em; }
			table.logs .column-customer_name{ width:15%; }
		</style>
		<?php
	}

	// вывод каждой ячейки таблицы...
	function column_default( $item, $colname ){

		if( $colname === 'title' ){
			// ссылки действия над элементом
			$actions = array();
			$actions['edit'] = sprintf( '<a href="%s">%s</a>', $this->url_edit.'&tab=edit_field&fid='.$item['id'], __('edit','hb-users') );

			$actions['trash'] = sprintf( '<a href="%s">%s</a>', $this->url_edit.'&tab=delete_field&fid='.$item['id'], __('delete','hb-users') );

			return esc_html( $item['title'] ) . $this->row_actions( $actions );
		}
		else
		if( $colname === 'customer_name' ){
			// ссылки действия над элементом
			$actions = array();
			$actions['edit'] = sprintf( '<a href="%s">%s</a>', '#', __('edit','hb-users') );

			return esc_html( $item->name ) . $this->row_actions( $actions );
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
