<?php

/* 
 * class.DShopPayment
 */

class DShopPayment{//NewsPostPost
    public $name = 'dspayment';
    public $nm = 'dsop_';
    public $meta_name = [
        'payment'=>'Оплата',
//        'order'=>'Заказ',
//        'user'=>'Заказчик',
//        'items'=>'Позиции',
        ];
    public $meta_pos = [
        'payment'=>'normal',
//        'order'=>'side',
//        'user'=>'normal',
//        'items'=>'normal',
        ];
//        $state_ =  get_post_meta( $dsorder->ID, 'dso_status', true );
//        
//        $dso_userId_ =  get_post_meta( $dsorder->ID, 'dso_userId', true );
//        $dso_user_name_ =  get_post_meta( $dsorder->ID, 'dso_user_name', true );
//        $dso_user_lastname_ =  get_post_meta( $dsorder->ID, 'dso_user_lastname', true );
//        $dso_user_sname_ =  get_post_meta( $dsorder->ID, 'dso_user_sname', true );
//        $dso_user_phone_ =  get_post_meta( $dsorder->ID, 'dso_user_phone', true );
//        $dso_user_email_ =  get_post_meta( $dsorder->ID, 'dso_user_email', true );
//        $dso_user_addres_ =  get_post_meta( $dsorder->ID, 'dso_user_addres', true );
//        $dso_agriments_ =  get_post_meta( $dsorder->ID, 'dso_agriments', true );
//        
//        $dso_prodId_ =  get_post_meta( $dsorder->ID, 'dso_prodId', true );
//        $dso_prodUrl_ =  get_post_meta( $dsorder->ID, 'dso_prodUrl', true );
//        $dso_prodCategory_ =  get_post_meta( $dsorder->ID, 'dso_prodCategory', true );
//        $dso_prodName_ =  get_post_meta( $dsorder->ID, 'dso_prodName', true );
//        $dso_count_ =  get_post_meta( $dsorder->ID, 'dso_count', true );
//        $dso_item_cost_ =  get_post_meta( $dsorder->ID, 'dso_item_cost', true );
//        $dso_items_count_ =  get_post_meta( $dsorder->ID, 'dso_items_count', true );
//        $dso_delivery_poland_cost_ =  get_post_meta( $dsorder->ID, 'dso_delivery_poland_cost', true );
//        $dso_deliveryPolad_ =  get_post_meta( $dsorder->ID, 'dso_deliveryPolad', true );
//        $dso_delivery_cost_ =  get_post_meta( $dsorder->ID, 'dso_delivery_cost', true );
//        $dso_deliveryId_ =  get_post_meta( $dsorder->ID, 'dso_deliveryId', true );
//        $dso_deliveryName_ =  get_post_meta( $dsorder->ID, 'dso_deliveryName', true );
//        $dso_markup_ =  get_post_meta( $dsorder->ID, 'dso_markup', true );
//        $dso_total_ =  get_post_meta( $dsorder->ID, 'dso_cost', true );
    public $meta_fields = [
        'dsop_ID' => 'ID заказа',
        'dsop_status'=>'Статус оплаты',
        'dsop_userId'=>'Заказчик (id)',
        'dsop_payway'=>'Способ оплаты',
        'dsop_outsumm'=>'Оплаче-ваемая(-нная?) сумма',
        'dsop_fee'=>'Коммиссия',
        'dsop_signatura'=>'Проверочное число',
        'dsop_user_email'=>'Почта указанная при оплате',
        'dsop_istest'=>'Тест',
        'dsop_result_post'=>'Все пришедшие данные',
        'dsop_cost'=>'Сумма в заказе',
        
//        'dsop_status'=>'Статус заказа',
//        'dsop_userId'=>'Заказчик (id)',
//        'dsop_user_name'=>'Имя',
//        'dsop_user_lastname'=>'Фамилия',
//        'dsop_user_sname'=>'Отчество',
//        'dsop_user_phone'=>'Телефон',
//        'dsop_user_email'=>'Почта',
//        'dsop_user_addres'=>'Адресс назначения',
//        'dsop_agriments'=>'Согласие на обработку персональных данных',
//        
////        'dso_prodId'=>'Id продукта',
////        'dso_prodUrl'=>'Источник продукта',
////        'dso_prodCategory'=>'Категория',
////        'dso_prodName'=>'Название',
////        'dso_count'=>'Количество',
////        'dso_item_cost'=>'Стоимость единицы',
//        'dsop_items_count'=>'Количество единиц',
//        
////        'dso_delivery_poland_cost'=>'Стоимость доставки по Польше',
////        'dso_deliveryPolad'=>'Служба доставки по польше',
////        'dso_delivery_cost'=>'Стоимость доставки до склада',
////        'dso_deliveryId'=>'Id службы доставки',
////        'dso_deliveryName'=>'Транспортная служба',
////        'dso_markup'=>'Наценка за доставку до склада %',
//        
//        'dsop_count'=>'Количество продуктов',
//        'dsop_cost'=>'Всего',
        
//        'news_type'=>'Тип новостей',
//        'news_source'=>'Источник'
        ];
    public $meta_types = [];
    public $meta_val= [];
    public $meta_vars= [];
    public $meta_validates = [];
    public $meta_ftpl = [];
    public $meta_tpls = [];
    
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
        $this->init_type();
        $this->init_meta();
    }
    public function init_type(){
        add_action( 'init', [$this,'post_type_init'] );
        // создаем новую колонку
        // manage_(post_type)_posts_columns
        add_filter( 'manage_'.$this->name.'_posts_columns',
                [$this,'add_views_column'], 4 );
        
        // заполняем колонку данными
        // manage_(post_type)_posts_custom_column
        // wp-admin/includes/class-wp-posts-list-table.php
        add_action('manage_'.$this->name.'_posts_custom_column',
                [$this,'fill_views_column'], 5, 2 );
        
        // добавляем возможность сортировать колонку
        // manage_(screen_id)_sortable_columns
//        add_filter( 'manage_'.'edit-'.$this->name.'_sortable_columns',
//                [$this,'add_views_sortable_column'] );
        
        // изменяем запрос при сортировке колонки
//        add_action( 'pre_get_posts', [$this,'add_column_views_request'] );
        
        // подправим ширину колонки через css
//        add_action('admin_head', [$this,'add_views_column_css']);
    }
    public function init_meta(){
//        $this->init_meta_fields();
//        $this->init_meta_tpls();
        add_action( 'admin_init', [$this,'add_metabox'] );
        add_action( 'save_post', [$this,'add_metabox_fields'], 10, 2 );
        
//        add_action( 'admin_menu', 'true_meta_boxes' );
//        add_action('save_post', 'true_save_box_data');
        
        // вывести превью в списке
        //add_action('init', [$this,'add_post_thumbs_in_post_list_table'], 20 );
    }
    /**
     * инициализация типа постов
     */
    public function post_type_init() {
        $labels = array(
            'name' => 'Оплаты',
            'singular_name' => 'Оплата', // админ панель Добавить->Функцию
            'add_new' => 'Добавить Оплату',
            'add_new_item' => 'Добавить новую Оплату', // заголовок тега <title>
            'edit_item' => 'Редактировать Оплату',
            'new_item' => 'Новая Оплата',
            'all_items' => 'Все Оплаты',
            'view_item' => 'Просмотр Оплаты на сайте',
            'search_items' => 'Искать Оплату',
            'not_found' =>  'Оплата не найдена.',
            'not_found_in_trash' => 'В корзине нет Оплат.',
            'menu_name' => 'Оплаты' // ссылка в меню в админке
        );
        $args = array(
            'labels' => $labels,
            'public' => true,
            'show_ui' => true, // показывать интерфейс в админке
            'has_archive' => true, 
    //        https://developer.wordpress.org/resource/dashicons/#controls-repeat
    //		'menu_icon' => get_stylesheet_directory_uri() .'/img/function_icon.png', // иконка в меню
            'menu_position' => 20, // порядок в меню
            'menu_icon' => 'dashicons-yes', // иконка в меню
            
            'supports' => array( 'title',
//                 'editor',
//                  'custom-fields',
//                'comments',
//                'author',
//                'thumbnail'
                ),
            
    //		'rewrite'             => array( 'slug'=>'resume/%resumecat%', 'with_front'=>false, 'pages'=>false, 'feeds'=>false, 'feed'=>false ),
    //		'has_archive'         => 'resume',
        );
        register_post_type($this->name, $args);
    }
    ## Добавляет миниатюры записи в таблицу записей в админке
//if(1){
//	add_action('init', 'add_post_thumbs_in_post_list_table', 20 );
	public function add_post_thumbs_in_post_list_table(){
		// проверим какие записи поддерживают миниатюры
		$supports = get_theme_support('post-thumbnails');

		 $ptype_names = array(
             'news',
//             'post',
//             'page'
             ); // указывает типы для которых нужна колонка отдельно

		// Определяем типы записей автоматически
		if( ! isset($ptype_names) ){
			if( $supports === true ){
				$ptype_names = get_post_types(array( 'public'=>true ), 'names');
				$ptype_names = array_diff( $ptype_names, array('attachment') );
			}
			// для отдельных типов записей
			elseif( is_array($supports) ){
				$ptype_names = $supports[0];
			}
		}

		// добавляем фильтры для всех найденных типов записей
		foreach( $ptype_names as $ptype ){
			add_filter( "manage_{$ptype}_posts_columns", [$this,'add_thumb_column'] );
			add_action( "manage_{$ptype}_posts_custom_column", [$this,'add_thumb_value'], 10, 2 );
		}
	}

	// добавим колонку
	public function add_thumb_column( $columns ){
		// подправим ширину колонки через css
		add_action('admin_notices', function(){
			echo '
			<style>
				.column-thumbnail{ width:80px; text-align:center; }
			</style>';
		});

		$num = 1; // после какой по счету колонки вставлять новые

		$new_columns = array( 'thumbnail' => __('Thumbnail') );

		return array_slice( $columns, 0, $num ) + $new_columns + array_slice( $columns, $num );
	}

	// заполним колонку
	public function add_thumb_value( $colname, $post_id ){
		if( 'thumbnail' == $colname ){
        static $cou = 0; $cou++;
            $height = 45;
			$width  = 70;
            $thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
			// миниатюра
			if( $thumbnail_id ){
				$thumb = wp_get_attachment_image( $thumbnail_id,
                        array($width, $height), true );
			}
			// из галереи...
			else{
                $attachments = get_children( array(
                    'post_parent'    => $post_id,
                    'post_mime_type' => 'image',
                    'post_type'      => 'attachment',
                    'numberposts'    => 1,
                    'order'          => 'DESC',
                ) );
                if( $attachments ){
                    $attach = array_shift( $attachments );
                    $thumb = wp_get_attachment_image( $attach->ID,
                            array($width, $height), true );
                }
            }

			echo (empty($thumb) ? ' ' : $thumb);
//			echo $cou;
		}
	}
    public function add_views_column($columns){
		// подправим ширину колонки через css
		add_action('admin_notices', function(){
			echo '
			<style>
				.column-news_type{ width:40px; height:100%; text-align:center; }
				.column-dso_ID{ width:60px; height:100%; text-align:center; }
				.column-dsop_ID{ width:60px; height:100%; text-align:center; }
				.column-dsop_outsumm{ width:100px; height:100%; text-align:center; }
				.column-dsop_fee{ width:100px; height:100%; text-align:center; }
				.column-dsop_istest{ width:60px; height:100%; text-align:center; }
				.column-dsop_cost{ width:100px; height:100%; text-align:center; }
                
				.column-dso_cost,
				.column-dso_items_count,
				.column-dso_count
                { width:100px;  }
				.column-dso_status{ width:170px; text-align:center; }
                .bg-primary ,
                .alert-primary {
                    color: #004085;
                    background-color: #cce5ff;
                    border-color: #b8daff;
                }
                .bg-secondary ,
                .alert-secondary {
                    color: #383d41;
                    background-color: #e2e3e5;
                    border-color: #d6d8db;
                }
                .bg-success ,
                .alert-success {
                    color: #155724;
                    background-color: #d4edda;
                    border-color: #c3e6cb;
                }
                .bg-danger ,
                .alert-danger {
                    color: #721c24;
                    background-color: #f8d7da;
                    border-color: #f5c6cb;
                }
                .bg-warning ,
                .alert-warning {
                    color: #856404;
                    background-color: #fff3cd;
                    border-color: #ffeeba;
                }
                .bg-0c5460 ,
                .alert-info {
                    color: #0c5460;
                    background-color: #d1ecf1;
                    border-color: #bee5eb;
                }
                .bg-light ,
                .alert-light {
                    color: #818182;
                    background-color: #fefefe;
                    border-color: #fdfdfe;
                }
                .bg-dark ,
                .alert-dark {
                    color: #1b1e21;
                    background-color: #d6d8d9;
                    border-color: #c6c8ca;
                }
                .alert {
                    position: relative;
                    padding: .75rem 1.25rem;
                    /*margin-bottom: 1rem;*/
                    border: 1px solid transparent;
                    border-radius: .25rem;
                }
			</style>';
		});

//        if( array_key_exists('news_type',$this->meta_fields)  ){
//		$num = 1; // после какой по счету колонки вставлять новые
//		$new_columns = array( 'news_type' => __('Тип') );
////        $columns = array_slice( $columns, 0, $num ) + $new_columns
////                + array_slice( $columns, $num );
//        }
//        
//        if( array_key_exists('news_source',$this->meta_fields)  ){
//		$num = 3; // после какой по счету колонки вставлять новые
//		$new_columns = array( 'news_source' => __('Источник') );
////        $columns = array_slice( $columns, 0, $num ) + $new_columns
////                + array_slice( $columns, $num );s
////                + array_slice( $columns, $num );
//        }
        
//				.column-dsop_outsumm
//				.column-dsop_fee
//				.column-dsop_istest
//				.column-dsop_cost
        
    $meta_fields = [
        'dsop_ID' => 'ID заказа',
        'dsop_status'=>'Статус оплаты',
        'dsop_userId'=>'Заказчик (id)',
        'dsop_payway'=>'Способ оплаты',
        'dsop_outsumm'=>'Оплаче-ваемая(-нная?) сумма',
        'dsop_fee'=>'Коммиссия',
        'dsop_signatura'=>'Проверочное число',
        'dsop_user_email'=>'Почта указанная при оплате',
        'dsop_istest'=>'Тест',
        'dsop_result_post'=>'Все пришедшие данные',
        'dsop_cost'=>'Сумма в заказе',
        ];
        if( array_key_exists('dsop_outsumm',$this->meta_fields)  ){
		$num = 2; // после какой по счету колонки вставлять новые
		$new_columns = array( 'dsop_outsumm' => 'Сумма' );
        $columns = array_slice( $columns, 0, $num ) + $new_columns
                + array_slice( $columns, $num );
        }
        if( array_key_exists('dsop_fee',$this->meta_fields)  ){
		$num = 3; // после какой по счету колонки вставлять новые
		$new_columns = array( 'dsop_fee' => 'Коммиссия' );
        $columns = array_slice( $columns, 0, $num ) + $new_columns
                + array_slice( $columns, $num );
        }
        if( array_key_exists('dsop_cost',$this->meta_fields)  ){
		$num = 4; // после какой по счету колонки вставлять новые
		$new_columns = array( 'dsop_cost' => 'Сумма (Контроль)' );
        $columns = array_slice( $columns, 0, $num ) + $new_columns
                + array_slice( $columns, $num );
        }
        if( array_key_exists('dsop_payway',$this->meta_fields)  ){
		$num = 5; // после какой по счету колонки вставлять новые
		$new_columns = array( 'dsop_payway' => 'Способ оплаты' );
        $columns = array_slice( $columns, 0, $num ) + $new_columns
                + array_slice( $columns, $num );
        }
        if( array_key_exists('dsop_istest',$this->meta_fields)  ){
		$num = 6; // после какой по счету колонки вставлять новые
		$new_columns = array( 'dsop_istest' => 'Тест' );
        $columns = array_slice( $columns, 0, $num ) + $new_columns
                + array_slice( $columns, $num );
        }
        
        /*   ==============    */
        
        if( array_key_exists('dso_count',$this->meta_fields)  ){
		$num = 2; // после какой по счету колонки вставлять новые
		$new_columns = array( 'dso_count' => 'Позиций' );
        $columns = array_slice( $columns, 0, $num ) + $new_columns
                + array_slice( $columns, $num );
        }
        
        if( array_key_exists('dso_items_count',$this->meta_fields)  ){
		$num = 3; // после какой по счету колонки вставлять новые
		$new_columns = array( 'dso_items_count' => 'Количество' );
        $columns = array_slice( $columns, 0, $num ) + $new_columns
                + array_slice( $columns, $num );
        }
        
        if( array_key_exists('dso_cost',$this->meta_fields)  ){
		$num = 4; // после какой по счету колонки вставлять новые
		$new_columns = array( 'dso_cost' => 'Сумма' );
        $columns = array_slice( $columns, 0, $num ) + $new_columns
                + array_slice( $columns, $num );
        }
        
        if( array_key_exists('dso_status',$this->meta_fields)  ){
		$num = 6; // после какой по счету колонки вставлять новые
		$new_columns = array( 'dso_status' => 'Состояние' );
        $columns = array_slice( $columns, 0, $num ) + $new_columns
                + array_slice( $columns, $num );
        }
        
        if( array_key_exists('dso_ID',$this->meta_fields)  ){
		$num = 1; // после какой по счету колонки вставлять новые
		$new_columns = array( 'dso_ID' => 'ID заказа' );
        $columns = array_slice( $columns, 0, $num ) + $new_columns
                + array_slice( $columns, $num );
        }
        
        if( array_key_exists('dsop_ID',$this->meta_fields)  ){
		$num = 1; // после какой по счету колонки вставлять новые
		$new_columns = array( 'dsop_ID' => 'ID заказа' );
        $columns = array_slice( $columns, 0, $num ) + $new_columns
                + array_slice( $columns, $num );
        }

		return $columns;
    }
    public function fill_views_column( $colname, $post_id ){
        if(!( array_key_exists($colname,$this->meta_fields) ) ){
            return;
        }
        $out='--';
        if($colname == 'news_type'){
            $types =[
                'russian'=>'Ру',
                'foreign'=>'Ино',
    //            'russian'=>'Русские',
    //            'foreign'=>'Иностранные',
            ];
            $d=get_post_meta( $post_id, $colname, 1 );
            if(( array_key_exists($d,$types) ) ){
                $out=$types[$d];
            }
        }
        if($colname == 'news_source'){
            $d=get_post_meta( $post_id, $colname, 1 );
            if(( strlen(trim($d)) > 0 ) ){
                $out=$d;
            }
        }
        if($colname == 'dso_ID'){
            $out = '<b>'.$post_id.'</b>';
        }
        if($colname == 'dsop_ID'){
            $d=get_post_meta( $post_id, $colname, 1 );
            $out = '<b>'.$d.'</b>';
        }
        if($colname == 'dso_status'){
            $states =[
                'created'=>'Обрабатывается',
                'checked'=>'Оформлен',
                'payd'=>'Оплачен',
                'sent'=>'Отправлен',
                'deliverid'=>'Доставлен',
                'pending'=>'Отклонён',
            ];
            $d=get_post_meta( $post_id, $colname, 1 );
            if(( strlen(trim($d)) > 0 ) && key_exists($d, $states)){
                $state_f = $states[$d];
                $state_class = '';
                switch ($d) {
                    case 'created': $state_class='bg-primary'; break;//alert-
                    case 'checked': $state_class='bg-success'; break;
                    case 'payd': $state_class='bg-success'; break;
                    case 'sent': $state_class='bg-success'; break;
                    case 'deliverid': $state_class='bg-success'; break;
                    case 'pending': $state_class='bg-danger'; break;
                    default:
                        break;
                }
                $out='<div class="alert '.$state_class.'">'.$state_f.'</div>';
            }
        }
        if($colname == 'dsop_istest'){
            $states =[
                '0'=>'',
                '1'=>'test',
            ];
            $d=get_post_meta( $post_id, $colname, 1 );
            if(( strlen(trim($d)) > 0 ) && key_exists($d, $states)){
                $state_f = $states[$d];
                $state_class = '';
                switch ($d) {
//                    case 'created': $state_class='bg-primary'; break;//alert-
//                    case 'checked': $state_class='bg-success'; break;
//                    case 'payd': $state_class='bg-success'; break;
//                    case 'sent': $state_class='bg-success'; break;
//                    case 'deliverid': $state_class='bg-success'; break;
//                    case 'pending': $state_class='bg-danger'; break;
                    default:
                        break;
                }
                $out='<div class="-alert '.$state_class.'">'.$state_f.'</div>';
            }
        }
    $meta_fields = [
        'dsop_ID' => 'ID заказа',
        'dsop_status'=>'Статус оплаты',
        'dsop_userId'=>'Заказчик (id)',
        'dsop_outsumm'=>'Оплаче-ваемая(-нная?) сумма',
        'dsop_fee'=>'Коммиссия',
        'dsop_signatura'=>'Проверочное число',
        'dsop_user_email'=>'Почта указанная при оплате',
        'dsop_istest'=>'Тест',
        'dsop_result_post'=>'Все пришедшие данные',
        'dsop_cost'=>'Сумма в заказе',
        ];
        if($colname == 'dsop_cost'||$colname == 'dsop_outsumm'||$colname == 'dsop_fee'){
            $d=get_post_meta( $post_id, $colname, 1 );
            if(( strlen(trim($d)) > 0 )){
//                $state_f = $states[$d];
                $state_class = '';
//                switch ($d) {
//                    case 'created': $state_class='bg-primary'; break;//alert-
//                    case 'checked': $state_class='bg-success'; break;
//                    case 'payd': $state_class='bg-success'; break;
//                    case 'sent': $state_class='bg-success'; break;
//                    case 'deliverid': $state_class='bg-success'; break;
//                    case 'pending': $state_class='bg-danger'; break;
//                    default:
//                        break;
//                }
                $currency_short = get_option('currency_short','zl');
                $out='<div class="-alert '.$state_class.'">'.$d.' '.$currency_short.'</div>';
            }
        }
        if($colname == 'dsop_payway'){
            $d=get_post_meta( $post_id, $colname, 1 );
            if(( strlen(trim($d)) > 0 )){
                $state_class = '';
//                $currency_short = get_option('currency_short','zl');
                $out='<div class="-alert '.$state_class.'">'.$d.' </div>';
            }
        }
        if($colname == 'dso_count'){
            $d=get_post_meta( $post_id, $colname, 1 );
            if(( strlen(trim($d)) > 0 )){
                $state_class = '';
//                $currency_short = get_option('currency_short','zl');
                $out='<div class="-alert '.$state_class.'">'.$d.' </div>';
            }
        }
        if($colname == 'dso_items_count'){
            $d=get_post_meta( $post_id, $colname, 1 );
            if(( strlen(trim($d)) > 0 )){
                $state_class = '';
//                $currency_short = get_option('currency_short','zl');
                $out='<div class="-alert '.$state_class.'">'.$d.' единиц</div>';
            }
        }
        echo $out;
    }
//}
    
/*
 * add_meta_box
 * 
movie_review_meta_box — необходимый HTML атрибут id.
Movie Review Details — текст, видимый в верхней части мета-блока.
display_movie_review_meta_box — обратный вызов, который отображает содержимое мета-блока.
movie_reviews — это имя пользовательского типа записей, где будет отображаться мета-блок.
normal — определяет часть страницы, где должен быть отображен блок редактирования.
high — определяет приоритет в контексте, в котором будут отображаться блоки.
 */
    /**
     * добавление блока метатегов
     */
    public function add_metabox() {
//        add_meta_box( 'ccab_'.$this->name.'_meta_box',
//            $this->meta_name,
//            [$this,'display_meta_box'],
//            $this->name, 'normal', 'high'
//        );
        
//        https://misha.blog/wordpress/meta-boxes.html
//        normal, side, advanced
//        high, core, default или low
        foreach($this->meta_name as $name=>$title){
            add_meta_box( 'dshop_meta_box_'.$name,
                $title,
                [$this,'display_meta_box_'.$name],
                $this->name, $this->meta_pos[$name], 'high'
            );
        }
    }
    /*
     * Этап 2. Заполнение
     */
    public function true_print_box($post) {
        wp_nonce_field( basename( __FILE__ ), 'seo_metabox_nonce' );
        /*
         * добавляем текстовое поле
         */
        $html .= '<label>Заголовок <input type="text" name="seotitle" value="' .
                get_post_meta($post->ID, 'seo_title',true) . '" /></label> ';
        /*
         * добавляем чекбокс
         */
        $html .= '<label><input type="checkbox" name="noindex"';
        $html .= (get_post_meta($post->ID, 'seo_noindex',true) == 'on') 
                ? ' checked="checked"' : '';
        $html .= ' /> Скрыть запись от поисковиков?</label>';

        echo $html;
    }

    /*
     * Этап 3. Сохранение
     */
    public function true_save_box_data ( $post_id ) {
        // проверяем, пришёл ли запрос со страницы с метабоксом
        if ( !isset( $_POST['seo_metabox_nonce'] )
        || !wp_verify_nonce( $_POST['seo_metabox_nonce'], basename( __FILE__ ) ) )
            return $post_id;
        // проверяем, является ли запрос автосохранением
        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
            return $post_id;
        // проверяем, права пользователя, может ли он редактировать записи
        if ( !current_user_can( 'edit_post', $post_id ) )
            return $post_id;
        // теперь также проверим тип записи	
        $post = get_post($post_id);
        if ($post->post_type == $post->name) { // укажите собственный
//            update_post_meta($post_id, 'seo_title', esc_attr($_POST['seotitle']));
//            update_post_meta($post_id, 'seo_noindex', $_POST['noindex']);
        }
        return $post_id;
    }
    /**
     * отрисовка полей метатегов
     * @param type $dsorder
     */
    public function display_meta_box_payment( $dsorder, $meta ) {
//        $meta_fields = [
//            'dsop_ID' => 'ID заказа',
//            'dsop_status'=>'Статус оплаты',
//            'dsop_userId'=>'Заказчик (id)',
//            'dsop_outsumm'=>'Оплаче-ваемая(-нная?) сумма',
//            'dsop_fee'=>'Коммиссия',
//            'dsop_signatura'=>'Проверочное число',
//            'dsop_user_email'=>'Почта указанная при оплате',
//            'dsop_istest'=>'Тест',
//            'dsop_result_post'=>'Все пришедшие данные',
//            'dsop_cost'=>'Сумма в заказе',
//        ];
        $meta_fields = $this->meta_fields;
    
                ?>
        <table style="width: 100%">
            
            <!--<tr><td style="" colspan="2"><b>Заказчик:</b></td></tr>-->
            <?php
        foreach ($meta_fields as $m => $n) {
            $v = get_post_meta( $dsorder->ID, $m, true );
        ?>
        <tr><td style="" colspan=""><?=$n?></td>
            <td colspan="">

        <?php
        
            if($m =='dsop_result_post'){
                $v = print_r($v,1);
                ?><pre><?=$v?></pre><?php
            }else{
        ?><input style="width: 100%" type="text"
            name="<?=$m?>" value="<?=$v?>"><?php
            }
        }
        echo '</td></tr>';
            ?>
        </table>
        <?php
    }
    public function display_meta_box_order( $dsorder ) {
        // Retrieve current name of the Director and Movie Rating based on review ID
    //    $movie_director = esc_html( get_post_meta( $dsorder->ID, 'movie_director', true ) );
    //    $movie_rating = intval( get_post_meta( $dsorder->ID, 'movie_rating', true ) );

        $state_ =  get_post_meta( $dsorder->ID, 'dso_status', true );
        
//        $dso_userId_ =  get_post_meta( $dsorder->ID, 'dso_userId', true );
//        $dso_user_name_ =  get_post_meta( $dsorder->ID, 'dso_user_name', true );
//        $dso_user_lastname_ =  get_post_meta( $dsorder->ID, 'dso_user_lastname', true );
//        $dso_user_sname_ =  get_post_meta( $dsorder->ID, 'dso_user_sname', true );
//        $dso_user_phone_ =  get_post_meta( $dsorder->ID, 'dso_user_phone', true );
//        $dso_user_email_ =  get_post_meta( $dsorder->ID, 'dso_user_email', true );
//        $dso_user_addres_ =  get_post_meta( $dsorder->ID, 'dso_user_addres', true );
//        $dso_agriments_ =  get_post_meta( $dsorder->ID, 'dso_agriments', true );
        
//        $dso_prodId_ =  get_post_meta( $dsorder->ID, 'dso_prodId', true );
//        $dso_prodUrl_ =  get_post_meta( $dsorder->ID, 'dso_prodUrl', true );
//        $dso_prodCategory_ =  get_post_meta( $dsorder->ID, 'dso_prodCategory', true );
//        $dso_prodName_ =  get_post_meta( $dsorder->ID, 'dso_prodName', true );
//        $dso_item_cost_ =  get_post_meta( $dsorder->ID, 'dso_item_cost', true );
        $dso_items_count_ =  get_post_meta( $dsorder->ID, 'dso_items_count', true );
//        $dso_items_cost =  get_post_meta( $dsorder->ID, 'dsoi_items_cost', true );
        
//        $dso_delivery_poland_cost_ =  get_post_meta( $dsorder->ID, 'dso_delivery_poland_cost', true );
//        $dso_deliveryPolad_ =  get_post_meta( $dsorder->ID, 'dso_deliveryPolad', true );
//        $dso_delivery_cost_ =  get_post_meta( $dsorder->ID, 'dso_delivery_cost', true );
//        $dso_deliveryId_ =  get_post_meta( $dsorder->ID, 'dso_deliveryId', true );
//        $dso_deliveryName_ =  get_post_meta( $dsorder->ID, 'dso_deliveryName', true );
//        $dso_markup_ =  get_post_meta( $dsorder->ID, 'dso_markup', true );
        
        $dso_total_ =  get_post_meta( $dsorder->ID, 'dso_cost', true );
        $dso_count_ =  get_post_meta( $dsorder->ID, 'dso_count', true );
        
//        $type_ =  get_post_meta( $dsorder->ID, 'news_type', true );
//        $news_source =  get_post_meta( $dsorder->ID, 'news_source', true );
//        $fields=$this->meta_fields;
        $states =[
            'created'=>'Обрабатывается',
            'checked'=>'Оформлен',
            'payd'=>'Оплачен',
            'sent'=>'Отправлен',
            'deliverid'=>'Доставлен',
            'pending'=>'Отклонён',
        ];
        /*
         *  
         * style="width: 100px"
         */
        $rowsep = '</tr><tr>';
        $rowsep = '';
                ?>
        <table>
            <tr>
                <td style="width: 100px">Состояние заказа</td>
                <td>
                    <select name="dso_status">
                    <?php
                    // Generate all items of drop-down list
                    foreach ( $states as $state=>$name) {
//                    for ( $rating = 5; $rating >= 1; $rating -- ) {
                    ?>
                        <option value="<?php echo $state; ?>" <?php echo
                        selected( $state, $state_ ); ?>><?php echo $name; ?>  <?php } ?>
                    </select>
                </td>
            </tr>
            
            <tr><td style="" colspan="2"><b>Данные заказа:</b></td></tr>
            
            <tr><td style="" colspan="2">Количество позиций</td>
                <td colspan="2"><?=$dso_count_?></td></tr>
            <tr><td style="" colspan="2">Количество единиц</td>
                <td colspan="2"><?=$dso_items_count_?></td></tr>
            
            <tr><td style="" colspan="2"><b>Итог:</b></td></tr>
            
            <tr><td style="" colspan="2">Общая стоимость</td></tr>
            <tr><td colspan="2"><input style="width: 100%" type="text"
                name="news_source" value="<?=$dso_total_?>"></td></tr>
            
        </table>
        <?php
        return;
            /*<tr><td style="" colspan="2">Ссылка на источник</td></tr>
            <tr><td colspan="2"><input style="width: 100%" type="text"
                name="news_source" value="<?=$news_source?>"></td></tr>/**/
        
        $r['__val__']='';
        if(isset($this->meta_val[$f]))
            $r['__val__']=$this->meta_val[$f];
        if(metadata_exists('post', $dsorder->ID, $f))
            $r['__val__']=esc_html( get_post_meta( $dsorder->ID, $f, true ) );
        
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
        echo $tab;
            /**/ if(0){
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
    public function display_meta_box_user( $dsorder, $meta ) {
        // Retrieve current name of the Director and Movie Rating based on review ID
    //    $movie_director = esc_html( get_post_meta( $dsorder->ID, 'movie_director', true ) );
    //    $movie_rating = intval( get_post_meta( $dsorder->ID, 'movie_rating', true ) );

//        $state_ =  get_post_meta( $dsorder->ID, 'dso_status', true );
        
        $dso_userId_ =  get_post_meta( $dsorder->ID, 'dso_userId', true );
        $dso_user_name_ =  get_post_meta( $dsorder->ID, 'dso_user_name', true );
        $dso_user_lastname_ =  get_post_meta( $dsorder->ID, 'dso_user_lastname', true );
        $dso_user_sname_ =  get_post_meta( $dsorder->ID, 'dso_user_sname', true );
        $dso_user_phone_ =  get_post_meta( $dsorder->ID, 'dso_user_phone', true );
        $dso_user_email_ =  get_post_meta( $dsorder->ID, 'dso_user_email', true );
        $dso_user_addres_ =  get_post_meta( $dsorder->ID, 'dso_user_addres', true );
        $dso_agriments_ =  get_post_meta( $dsorder->ID, 'dso_agriments', true );
        
//        $dso_prodId_ =  get_post_meta( $dsorder->ID, 'dso_prodId', true );
//        $dso_prodUrl_ =  get_post_meta( $dsorder->ID, 'dso_prodUrl', true );
//        $dso_prodCategory_ =  get_post_meta( $dsorder->ID, 'dso_prodCategory', true );
//        $dso_prodName_ =  get_post_meta( $dsorder->ID, 'dso_prodName', true );
//        $dso_count_ =  get_post_meta( $dsorder->ID, 'dso_count', true );
//        $dso_item_cost_ =  get_post_meta( $dsorder->ID, 'dso_item_cost', true );
//        $dso_items_count_ =  get_post_meta( $dsorder->ID, 'dso_items_count', true );
//        $dso_delivery_poland_cost_ =  get_post_meta( $dsorder->ID, 'dso_delivery_poland_cost', true );
//        $dso_deliveryPolad_ =  get_post_meta( $dsorder->ID, 'dso_deliveryPolad', true );
//        $dso_delivery_cost_ =  get_post_meta( $dsorder->ID, 'dso_delivery_cost', true );
//        $dso_deliveryId_ =  get_post_meta( $dsorder->ID, 'dso_deliveryId', true );
//        $dso_deliveryName_ =  get_post_meta( $dsorder->ID, 'dso_deliveryName', true );
//        $dso_markup_ =  get_post_meta( $dsorder->ID, 'dso_markup', true );
//        $dso_total_ =  get_post_meta( $dsorder->ID, 'dso_cost', true );
//        
//        $type_ =  get_post_meta( $dsorder->ID, 'news_type', true );
//        $news_source =  get_post_meta( $dsorder->ID, 'news_source', true );
//        $fields=$this->meta_fields;
//        $states =[
//            'created'=>'Обрабатывается',
//            'checked'=>'Оформлен',
//            'payd'=>'Оплачен',
//            'sent'=>'Отправлен',
//            'deliverid'=>'Доставлен',
//            'pending'=>'Отклонён',
//        ];
        /*
         *  
         * style="width: 100px"
         */
        $rowsep = '</tr><tr>';
        $rowsep = '';
        if(1){
                ?>
        <table>
            
            <tr><td style="" colspan="2"><b>Заказчик:</b></td></tr>
            
            <tr><td style="" colspan="">User Id</td>
                <td colspan=""><?=$dso_userId_?></td></tr>
            
            <tr><td style="" colspan="">Имя</td>
                <td colspan=""><input style="width: 100%" type="text"
                name="news_source" value="<?=$dso_user_name_?>"></td></tr>
            
            <tr><td style="" colspan="">Фамилия</td>
                <td colspan=""><input style="width: 100%" type="text"
                name="news_source" value="<?=$dso_user_lastname_?>"></td></tr>
            
            <tr><td style="" colspan="">Отчество</td>
                <td colspan=""><input style="width: 100%" type="text"
                name="news_source" value="<?=$dso_user_sname_?>"></td></tr>
            
            <tr><td style="" colspan="">Телефон</td>
                <td colspan=""><?=$dso_user_phone_?></td></tr>
            
            <tr><td style="" colspan="">EMail</td>
                <td colspan=""><input style="width: 100%" type="text"
                name="news_source" value="<?=$dso_user_email_?>"></td></tr>
            
            <tr><td style="" colspan="">Адрес</td>
                <td colspan=""><input style="width: 100%" type="text"
                name="news_source" value="<?=$dso_user_addres_?>"></td></tr>
            
            <tr><td style="" colspan="">Соглашение о персональных данных</td>
                <td colspan=""><?=$dso_agriments_?></td></tr>
            
        </table>
        <?php
        }else{
                ?>
        <table>
            
            <tr><td style="" colspan="2"><b>Заказчик:</b></td></tr>
            
            <tr><td style="" colspan="">User Id</td>
                <td colspan=""><input style="width: 100%" type="text"
                name="news_source" value="<?=$dso_userId_?>"></td></tr>
            
            <tr><td style="" colspan="">Имя</td>
                <td colspan=""><input style="width: 100%" type="text"
                name="news_source" value="<?=$dso_user_name_?>"></td></tr>
            
            <tr><td style="" colspan="">Фамилия</td>
                <td colspan=""><input style="width: 100%" type="text"
                name="news_source" value="<?=$dso_user_lastname_?>"></td></tr>
            
            <tr><td style="" colspan="">Отчество</td>
                <td colspan=""><input style="width: 100%" type="text"
                name="news_source" value="<?=$dso_user_sname_?>"></td></tr>
            
            <tr><td style="" colspan="">Телефон</td>
                <td colspan=""><input style="width: 100%" type="text"
                name="news_source" value="<?=$dso_user_phone_?>"></td></tr>
            
            <tr><td style="" colspan="">EMail</td>
                <td colspan=""><input style="width: 100%" type="text"
                name="news_source" value="<?=$dso_user_email_?>"></td></tr>
            
            <tr><td style="" colspan="">Адрес</td>
                <td colspan=""><input style="width: 100%" type="text"
                name="news_source" value="<?=$dso_user_addres_?>"></td></tr>
            
            <tr><td style="" colspan="">Соглашение о персональных данных</td>
                <td colspan=""><input style="width: 100%" type="text"
                name="news_source" value="<?=$dso_agriments_?>"></td></tr>
            
        </table>
        <?php
            
        }
    }
    public function display_meta_box_items( $dsorder, $meta ) {
        // Retrieve current name of the Director and Movie Rating based on review ID
    //    $movie_director = esc_html( get_post_meta( $dsorder->ID, 'movie_director', true ) );
    //    $movie_rating = intval( get_post_meta( $dsorder->ID, 'movie_rating', true ) );

//        $state_ =  get_post_meta( $dsorder->ID, 'dso_status', true );
        
//        $dso_userId_ =  get_post_meta( $dsorder->ID, 'dso_userId', true );
//        $dso_user_name_ =  get_post_meta( $dsorder->ID, 'dso_user_name', true );
//        $dso_user_lastname_ =  get_post_meta( $dsorder->ID, 'dso_user_lastname', true );
//        $dso_user_sname_ =  get_post_meta( $dsorder->ID, 'dso_user_sname', true );
//        $dso_user_phone_ =  get_post_meta( $dsorder->ID, 'dso_user_phone', true );
//        $dso_user_email_ =  get_post_meta( $dsorder->ID, 'dso_user_email', true );
//        $dso_user_addres_ =  get_post_meta( $dsorder->ID, 'dso_user_addres', true );
//        $dso_agriments_ =  get_post_meta( $dsorder->ID, 'dso_agriments', true );
        
//        $dso_prodId_ =  get_post_meta( $dsorder->ID, 'dso_prodId', true );
//        $dso_prodUrl_ =  get_post_meta( $dsorder->ID, 'dso_prodUrl', true );
//        $dso_prodCategory_ =  get_post_meta( $dsorder->ID, 'dso_prodCategory', true );
//        $dso_prodName_ =  get_post_meta( $dsorder->ID, 'dso_prodName', true );
        $dso_count_ =  get_post_meta( $dsorder->ID, 'dso_count', true );
//        $dso_item_cost_ =  get_post_meta( $dsorder->ID, 'dso_item_cost', true );
//        $dso_items_count_ =  get_post_meta( $dsorder->ID, 'dso_items_count', true );
//        $dso_delivery_poland_cost_ =  get_post_meta( $dsorder->ID, 'dso_delivery_poland_cost', true );
//        $dso_deliveryPolad_ =  get_post_meta( $dsorder->ID, 'dso_deliveryPolad', true );
//        $dso_delivery_cost_ =  get_post_meta( $dsorder->ID, 'dso_delivery_cost', true );
//        $dso_deliveryId_ =  get_post_meta( $dsorder->ID, 'dso_deliveryId', true );
//        $dso_deliveryName_ =  get_post_meta( $dsorder->ID, 'dso_deliveryName', true );
//        $dso_markup_ =  get_post_meta( $dsorder->ID, 'dso_markup', true );
        $dso_total_ =  get_post_meta( $dsorder->ID, 'dso_cost', true );
        
//        $type_ =  get_post_meta( $dsorder->ID, 'news_type', true );
//        $news_source =  get_post_meta( $dsorder->ID, 'news_source', true );
//        $fields=$this->meta_fields;
//        $states =[
//            'created'=>'Обрабатывается',
//            'checked'=>'Оформлен',
//            'payd'=>'Оплачен',
//            'sent'=>'Отправлен',
//            'deliverid'=>'Доставлен',
//            'pending'=>'Отклонён',
//        ];
        /*
         *  
         * style="width: 100px"
         */
     $meta_fields = [
        
        'dsoi_prodId'=>'Id продукта',
        'dsoi_prodUrl'=>'Источник продукта',
        'dsoi_prodCategory'=>'Категория',
        'dsoi_prodName'=>'Название',
        'dsoi_count'=>'Количество',
        'dsoi_item_cost'=>'Стоимость единицы',
//        'dsoi_items_count'=>'Количество',
        'dsoi_items_cost'=>'Сумма',
        
//        'news_type'=>'Тип новостей',
//        'news_source'=>'Источник'
        ];
        
    // параметры по умолчанию
     $args = [
         
        'numberposts' => 1000,
        'offset'    => 0,
        'category'    => 0,
        'orderby'     => 'date',
        'order'       => 'DESC',
        'include'     => array(),
        'exclude'     => array(),
        'meta_key'    => 'dsoi_orderId',
        'meta_value'  => $dsorder->ID,
        'post_type'   => 'dsoitem',
        'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
     ];
        $query = new WP_Query( $args );
        $count =  $query->found_posts;
        if(!$count){
                    ?>
            <table>

                <tr><td style="" colspan="8"><b>Позиции отсутствуют.</b></td></tr>
            </table>
            <?php
                /*<tr><td style="" colspan="8">
                        <b><pre><?php print_r($args)?></pre></b></td></tr>/**/
                /*<tr><td style="" colspan="8">
                        <b><pre><?php print_r($dsorder)?></pre></b></td></tr>/**/
                /*<tr><td style="" colspan="8">
                        <b><pre><?php print_r($meta)?></pre></b></td></tr>/**/
        }else{
            $posts = get_posts( $args );
                    ?>
            <table>

                <tr><td style="" colspan="8"><b>Позиции заказа:</b></td></tr>

                <tr>
                    <th style="" colspan="">ID товара</th>
                    <th style="" colspan="">Источник</th>
                    <th style="" colspan="">Товар</th>
                    <!--<th style="" colspan="">Категория</th>-->
                    <!--<th style="" colspan="">Название</th>-->
                    <th style="" colspan="">Количество</th>
                    <th style="" colspan="">%</th>
                    <th style="" colspan="">Цена</th>
                    <th style="" colspan="">Сумма</th>
                    <th style="" colspan=""></th>
                    <!--<th style="" colspan="">Подробнее</th>-->
                </tr>
            <?php
            
        $total = 0;
        $dso_items_count=0;
        $dso_count=0;
        foreach( $posts as $num=>$item ){
//            setup_postdata($post);
        
        $dsoi_delivery_poland_cost_ =  get_post_meta( $item->ID, 'dsoi_delivery_poland_cost', true );
        $dsoi_deliveryPolad_ =  get_post_meta( $item->ID, 'dsoi_delivery_poland', true );
        $dsoi_delivery_cost_ =  get_post_meta( $item->ID, 'dsoi_delivery_cost', true );
        $dsoi_deliveryId_ =  get_post_meta( $item->ID, 'dsoi_deliveryId', true );
        $dsoi_deliveryName_ =  get_post_meta( $item->ID, 'dsoi_delivery_name', true );
        $dsoi_markup_ =  get_post_meta( $item->ID, 'dsoi_markup', true );
        
        $dsoi_prodId_ =  get_post_meta( $item->ID, 'dsoi_prodId', true );
        $dsoi_prodUrl_ =  get_post_meta( $item->ID, 'dsoi_prodUrl', true );
        $dsoi_prodCatId_ =  get_post_meta( $item->ID, 'dsoi_prodCatId', true );
        $dsoi_prodCategory_ =  get_post_meta( $item->ID, 'dsoi_prodCategory', true );
        $dsoi_prodName_ =  get_post_meta( $item->ID, 'dsoi_prodName', true );
        $dsoi_count_ =  get_post_meta( $item->ID, 'dsoi_count', true );
//        $dsoi_items_count_ =  get_post_meta( $item->ID, 'dsoi_items_count', true );
        $dsoi_item_cost_ =  get_post_meta( $item->ID, 'dsoi_item_cost', true );
        $dsoi_items_cost_ =  get_post_meta( $item->ID, 'dsoi_items_cost', true );
        $dsoi_item_edit_ = '<a target="_blank" href="/wp-admin/post.php?post='.$item->ID.'&action=edit">Edit</a>';
        
        
        $dsoi_prodUrl_ = '<a target="_blank" href="'.$dsoi_prodUrl_.'">источник→</a>';
        $url = '/?categ='.$dsoi_prodCatId_.'&offset=0&poduct='.$dsoi_prodId_;
        $dsoi_prodName_ = '<a target="_blank" href="'.$url.'">'.$dsoi_prodName_.'</a>';
        
            $dso_items_count+=$dsoi_count_;
            $dso_count++;
            $total += $dsoi_items_cost_;
            
            $currency_short = get_option('currency_short','zl');
        ?>


                <tr>
                    <td colspan=""><?=$dsoi_prodId_?></td>
                    <td colspan=""><?=$dsoi_prodUrl_?></td>
                    <td colspan=""><?=$dsoi_prodName_?></td>
                    <!--<td colspan=""><?=$dsoi_prodCategory_?></td>-->
                    <!--<td colspan=""><?=$dsoi_count_?></td>-->
                    <td colspan="">
                        <input  type="hidden"
                        name="dsoi_id[<?=$num?>]" value="<?=$item->ID?>">
                        <input style="width: 70px" type="number"
                        name="dsoi_cou[<?=$num?>]" value="<?=$dsoi_count_?>"></td>
                    <td colspan="">
                        <input style="width: 60px" type="number"
                        name="dsoi_perc[<?=$num?>]" value="<?=$dsoi_markup_?>"></td>
                    <td colspan=""><?=$dsoi_item_cost_?> <?=$currency_short?></td>
                    <td colspan=""><?=$dsoi_items_cost_?> <?=$currency_short?></td>
                    <td colspan=""><?=$dsoi_item_edit_?></td>
                </tr>
            <?php
        }
        if(count($posts)){
                    ?>
                <tr>
                    <td colspan="8">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="4">Всего</td>
                    <td colspan="2"><?=$dso_items_count?> единиц</td>
                    <td colspan="2"><?=$total?> <?=$currency_short?></td>
                </tr>
            <?php
        }
        }
        ?>
            </table>
            <?php
    }
    /**
     * вспомогательная
     * отрисовка набора элементов <option/>
     * @param type $val
     * @return type
     */
    public function _cf_select( $val ){
//        $id = $val['id'];
//        $option_name = $val['option_name'];
        $tpl_o=<<<t
            <option value="_v_" _s_>_n_</option>
t;
        $r=[];
//        $v_=get_option($option_name,'desctop');
        $v_=$val['res'] ;
        foreach($val['items'] as $v=>$n){
            $r['_n_']=$n;
            $r['_v_']=$v;
            $r['_s_']=$v_==$v?'selected="selected"':'';
            $val['items'][$v] = strtr($tpl_o,$r);
        }
        return $o=implode('',$val['items']);
        ?>
    <select name="<? echo $option_name ?>" 
            id="<?= $id ?>" ><?= $o?></select>
        <?php /*
        ?>
        <input 
            type="text" 
            name="<? echo $option_name ?>" 
            id="<?= $id ?>" 
            value="<? echo esc_attr( get_option($option_name) ) ?>" 
        /> 
        <?php */
    }
    /**
     * сохранение данных полей метатегов
     * @param type $post_id
     * @param type $dsorder
     */
    public function add_metabox_fields( $orderId, $dsorder ) {
        if ( $dsorder->post_type == $this->name ) {
            $fields=$this->meta_fields;
            foreach($fields as $f=>$l){
                if ( isset( $_POST[$f] )
                        && $_POST[$f] != '' ) {
                    update_post_meta( $orderId, $f, $_POST[$f] );
                }
            }
            return;
                $total = 0;
                $dso_items_count=0;
                $dso_count=0;
            
            // параметры по умолчанию
            $args = [

               'numberposts' => 1000,
               'offset'    => 0,
               'category'    => 0,
               'orderby'     => 'date',
               'order'       => 'DESC',
               'include'     => array(),
               'exclude'     => array(),
               'meta_key'    => 'dsoi_orderId',
               'meta_value'  => $orderId,
               'post_type'   => 'dsoitem',
               'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
            ];
            $query = new WP_Query( $args );
            $count =  $query->found_posts;
            if($count){
                $posts = get_posts( $args );
                
                $pid = filter_input(INPUT_POST, 'dsoi_id',FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);
                if($pid===false || $pid===null|| $pid==='')$pid=[];
                $count = filter_input(INPUT_POST, 'dsoi_cou',FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);
                if($count===false || $count===null || $count==='')$count=[];
                $percent = filter_input(INPUT_POST, 'dsoi_perc',FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);
                if($percent===false || $percent===null || $percent==='')$percent=[];

                $pids=[];
                foreach( $posts as $num=>$item ){
                    $pids[]=$item->ID;
                }
                foreach( $pid as $num=>$id ){
                    if(in_array($id,$pids) ){
                        if(! isset($count[$num]) )$count[$num]=0;
                        if(! isset($percent[$num]) )$percent[$num]=0;
                        $cou = $count[$num];
                        $perc = $percent[$num];
                        update_post_meta( $id, 'dsoi_count', $cou );
                        update_post_meta( $id, 'dsoi_markup', $perc );
                        
                        $icost =  get_post_meta( $id, 'dsoi_item_cost', true );
                        $deliv =  get_post_meta( $id, 'dsoi_delivery_poland_cost', true );
                        $deliv_cost = $deliv + ( ($deliv/100) * $perc);
                        $summ = ($icost * $cou) + $deliv_cost;
                        update_post_meta( $id, 'dsoi_items_cost', $summ );
                        update_post_meta( $id, 'dsoi_delivery_cost', $deliv_cost );
                        
                    $dso_items_count+=$cou;
                    $dso_count++;
                    $total += $summ;
                    }
                }
                update_post_meta( $orderId, 'dso_items_count', $dso_items_count );
                update_post_meta( $orderId, 'dso_count', $dso_count );
                update_post_meta( $orderId, 'dso_cost', $total );
                
            }
        }
    }
}