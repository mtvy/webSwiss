<?php

/* 
 * code_exemple.php
 */


function onwp_get_wpdb_prefix () {
   global $wpdb;
 
   $table_name = $wpdb->prefix . "access_control";
   if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
    // тут мы добавляем таблицу в базу данных
       $sql = "CREATE TABLE " . $table_name . " (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            time bigint(11) DEFAULT '0' NOT NULL,
            name tinytext NOT NULL,
            text text NOT NULL,
            url VARCHAR(55) NOT NULL,
            UNIQUE KEY id (id)
          );";

      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);
    }
}


function onwp_insert_item($id_user, $id_product, $price) {
    global $wpdb;
    // подготавливаем данные   
    $id_user = esc_sql($id_user);
    $id_product = esc_sql($id_product);
    $price = esc_sql($price);
    $table_name = $wpdb->get_blog_prefix() . 'onwp_price';

    // вставляем строку в таблицу
    $wpdb->insert(
            $table_name, array(
        'id_user' => $id_user,
        'id_product' => $id_product,
        'price' => $price,
        'date' => date("Y-m-d", time())
            ), array('%d', '%d', '%f', '%s')
    );
}

//=============================

// Пример вызова функции
//onwp_insert_item(1, 80, 17.99);

## register_activation_hook( __FILE__, 'create_book_meta_table');
## Функция создания таблицы метаданных. Нужно запустить один раз. Можно повесить на register_activation_hook()
function create_book_meta_table(){
	global $wpdb;

	$collate = '';
	if ( ! empty($wpdb->charset) ) $collate  = "DEFAULT CHARACTER SET $wpdb->charset";
	if ( ! empty($wpdb->collate) ) $collate .= " COLLATE $wpdb->collate";

	/*
	 * Indexes have a maximum size of 767 bytes. Historically, we haven't need to be concerned about that.
	 * As of 4.2, however, we moved to utf8mb4, which uses 4 bytes per character. This means that an index which
	 * used to have room for floor(767/3) = 255 characters, now only has room for floor(767/4) = 191 characters.
	 */
	$max_index_length = 191;

	$main_field = 'book_id'; // название главной колонки, должно выглядеть как: $meta_type . '_id'
	$table_name = 'my_bookmeta';

	$wpdb->query(
		"CREATE TABLE $table_name (
			meta_id      bigint(20)   unsigned NOT NULL auto_increment,
			$main_field  bigint(20)   unsigned NOT NULL default '0',
			meta_key     varchar(255)                   default NULL,
			meta_value   longtext,
			PRIMARY KEY  (meta_id),
			KEY $main_field ($main_field),
			KEY meta_key (meta_key($max_index_length))
		) $collate;"
	);
}
function add_book_meta( $id, $meta_key, $meta_value, $unique = false ) {
	return add_metadata( 'book', $id, $meta_key, $meta_value, $unique );
}

function delete_book_meta( $id, $meta_key, $meta_value = '' ) {
	return delete_metadata( 'book', $id, $meta_key, $meta_value );
}

function get_book_meta( $id, $meta_key = '', $single = false ) {
	return get_metadata( 'book', $id, $meta_key, $single );
}

function update_book_meta( $id, $meta_key, $meta_value, $prev_value = '' ){
	return update_metadata( 'book', $id, $meta_key, $meta_value, $prev_value );
}
//// добавим данные в таблицу метаданных
//update_book_meta( 12, 'author_name', 'Циркон' );
//
//// получим значение метаполя 
//get_book_meta( 12, 'author_name', 1 ); //> Циркон
//
//// получим значения всех метполей
//get_book_meta( 12 ); //> вернет массив

//=============================
// установим таблицы в $wpdb
global $wpdb;
$wpdb->books    = "my_books";
$wpdb->bookmeta = "my_bookmeta";

## Пример функции для получения книг, с возможностью выборки по метаданным
function get_books( $args = array() ){
	global $wpdb;

	$default = [
		'book_id'        => 0,
		'name'           => '',
		'content_search' => '',
		// понимаемые мета-параметры
		'meta_key'       => '',
		'meta_value'     => '',
		'meta_value_num' => '',
		'meta_compare'   => '',
		'meta_query'     => array(),
	];

	$args = array_merge( $default, $args );

	$WHERE = array();
	$JOIN = $ORDER_BY = $LIMIT = '';

	if( $args['book_id'] ){
		// 'my_books.' нужно потому что поле назвается одинаково у главной и у мета таблицы
		$WHERE[] = $wpdb->prepare('my_books.book_id = %d', $args['book_id'] );
	}
	if( $args['name'] ){
		$WHERE[] = $wpdb->prepare('name = %s', $args['name'] );
	}
	if( $args['content_search'] ){
		$WHERE[] = $wpdb->prepare('content LIKE %s', '%'. $wpdb->esc_like( $args['content_search'] ) .'%' );
	}

	// мета запрос
	if( $args['meta_query'] || $args['meta_key'] ){
		$metaq = new WP_Meta_Query();
		$metaq->parse_query_vars( $args ); // парсим возможные мета-параметры из параметров $args

		// первый параметр 'book' должен быть началом свойства $wpdb->bookmeta без суффика 'meta'
		// Т.е. мы указываем 'book' к нему добавляется 'meta' и свойство 'bookmeta' должно существовать в $wpdb
		// см. https://wp-kama.ru/function/_get_meta_table
		$mq_sql = $metaq->get_sql( 'book', $wpdb->books, 'book_id' );

		$JOIN    = $mq_sql['join'];  // INNER JOIN my_bookmeta ON ( my_books.book_id = my_bookmeta.book_id )
		$WHERE[] = $mq_sql['where']; // AND ( ( my_bookmeta.meta_key = 'author_name' AND my_bookmeta.meta_value = 'Циркон' ) )
	}

	$WHERE = 'WHERE '. implode( ' AND ', $WHERE );

	/*
	для сортировки по метаполям понадобится $metaq->get_clauses()
	Array(
		[metasort] => Array(
				[key]     => author_name
				[value]   => Циркон
				[compare] => =
				[alias]   => my_bookmeta
				[cast]    => CHAR
			)
	)
	пример смотрите в: https://wp-kama.ru/function/WP_Query::parse_orderby
	*/
	$ORDER_BY = 'ORDER BY name ASC';

	$res = $wpdb->get_results( "SELECT * FROM $wpdb->books $JOIN $WHERE $ORDER_BY $LIMIT" );

	return $res;
}
if(0){
// запрос на получение книг
$books = get_books([
	'meta_key'   => 'author_name',
	'meta_value' => 'Циркон',
]);

// или так
$books = get_books([
	'meta_query' =>[
		'metasort' => [
			'key'   => 'author_name',
			'value' => 'Циркон',
		]
	]
]);

print_r( $books );
/*
Получим:
Array(
		[0] => stdClass Object(
			[book_id] => 12
			[name] => Вишневый сад
			[content] => Содержание книги ...
			[meta_id] => 2
			[meta_key] => author_name
			[meta_value] => Циркон
		)
)
*/
}
if(0){
    $args = array(
	'meta_query'   => array(
		'relation' => 'AND',
		array(
			'key'     => 'author_name',
			'value'   => 'алекс',
			'compare' => 'LIKE'
		),
		array(
			'key'     => 'price',
			'value'   => array( 20, 100 ),
			'type'    => 'numeric',
			'compare' => 'BETWEEN'
		)
	)
);
$books = get_books( $args );
}
if(0){
    
}
if(0){
    
}
if(0){
    
}
//=============================

//=============================

//=============================

//=============================
