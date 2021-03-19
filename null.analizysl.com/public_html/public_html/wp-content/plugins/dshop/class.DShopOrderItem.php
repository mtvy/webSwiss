<?php

/* 
 * class.DShopOrderItem
 */

class DShopOrderItem{//NewsPostPost
    public $name = 'dsoitem';
    public $nm = 'dsoi_';
    public $meta_name = [
//        'order'=>'Заказ',
//        'user'=>'Заказчик',
//        'items'=>'Позиции',
        'item'=>'Позиция',
        ];
    public $meta_pos = [
//        'order'=>'side',
//        'user'=>'normal',
//        'items'=>'normal',
        'item'=>'normal',
        ];
    public $meta_fields = [
        
        'dsoi_prodId'=>'Id продукта',
        'dsoi_prodUrl'=>'Источник продукта',
        'dsoi_prodCategory'=>'Категория',
        'dsoi_prodName'=>'Название',
        'dsoi_count'=>'Количество',
        'dsoi_item_cost'=>'Стоимость единицы',
//        'dsoi_items_count'=>'Количество',
        'dsoi_delivery_poland_cost'=>'Стоимость доставки по Польше',
        'dsoi_delivery_poland'=>'Служба доставки по польше',
        'dsoi_delivery_cost'=>'Стоимость доставки до склада',
        'dsoi_delivery_id'=>'Id службы доставки',
        'dsoi_delivery_name'=>'Транспортная служба',
        'dsoi_markup'=>'Наценка за доставку до склада %',
        'dsoi_items_cost'=>'Сумма',
        
//        'news_type'=>'Тип новостей',
//        'news_source'=>'Источник'
        ];
    public $meta_types = [];
    public $meta_val= [];
    public $meta_vars= [];
    public $meta_validates = [];
    public $meta_ftpl = [];
    public $meta_tpls = [];
    
    public function __construct() {
        $this->init();
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
        
        //// Тип 2: Вызов статического метода класса
        //call_user_func(array('MyClass', 'myCallbackMethod'));
        //
        //// Тип 3: Вызов метода класса
        //$obj = new MyClass();
        //call_user_func(array($obj, 'myCallbackMethod'));


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
            'name' => 'Позиции заказа',
            'singular_name' => 'Позиция', // админ панель Добавить->Функцию
            'add_new' => 'Добавить Позицию',
            'add_new_item' => 'Добавить новю Позицию', // заголовок тега <title>
            'edit_item' => 'Редактировать Позицию',
            'new_item' => 'Новый Позиция',
            'all_items' => 'Все Позиции',
            'view_item' => 'Просмотр Позиции на сайте',
            'search_items' => 'Искать Позицию',
            'not_found' =>  'Позиция не найден.',
            'not_found_in_trash' => 'В корзине нет Позиций.',
            'menu_name' => 'Позиции заказа' // ссылка в меню в админке
        );
        $args = array(
            'labels' => $labels,
            'public' => true,
            'show_ui' => true, // показывать интерфейс в админке
            'has_archive' => true, 
    //        https://developer.wordpress.org/resource/dashicons/#controls-repeat
    //		'menu_icon' => get_stylesheet_directory_uri() .'/img/function_icon.png', // иконка в меню
            'menu_position' => 20, // порядок в меню
            'menu_icon' => 'dashicons-layout', // иконка в меню
            
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
//			echo '
//			<style>
//				.column-news_type{ width:40px; text-align:center; }
//			</style>';
		});

        if( array_key_exists('news_type',$this->meta_fields)  ){
		$num = 1; // после какой по счету колонки вставлять новые
		$new_columns = array( 'news_type' => __('Тип') );
//        $columns = array_slice( $columns, 0, $num ) + $new_columns
//                + array_slice( $columns, $num );
        }
        
        if( array_key_exists('news_source',$this->meta_fields)  ){
		$num = 3; // после какой по счету колонки вставлять новые
		$new_columns = array( 'news_source' => __('Источник') );
//        $columns = array_slice( $columns, 0, $num ) + $new_columns
//                + array_slice( $columns, $num );s
//                + array_slice( $columns, $num );
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
    public function display_meta_box_item( $item ) {
        // использовать ли доставку и адрес
        $delivery_use = get_option('delivery_use', 0);
        
        // Retrieve current name of the Director and Movie Rating based on review ID
    //    $movie_director = esc_html( get_post_meta( $dsorder->ID, 'movie_director', true ) );
    //    $movie_rating = intval( get_post_meta( $dsorder->ID, 'movie_rating', true ) );

//        $state_ =  get_post_meta( $dsorder->ID, 'dsoi_status', true );
//        
//        $dsoi_userId_ =  get_post_meta( $dsorder->ID, 'dsoi_userId', true );
//        $dsoi_user_name_ =  get_post_meta( $dsorder->ID, 'dsoi_user_name', true );
//        $dsoi_user_lastname_ =  get_post_meta( $dsorder->ID, 'dsoi_user_lastname', true );
//        $dsoi_user_sname_ =  get_post_meta( $dsorder->ID, 'dsoi_user_sname', true );
//        $dsoi_user_phone_ =  get_post_meta( $dsorder->ID, 'dsoi_user_phone', true );
//        $dsoi_user_email_ =  get_post_meta( $dsorder->ID, 'dsoi_user_email', true );
//        $dsoi_user_addres_ =  get_post_meta( $dsorder->ID, 'dsoi_user_addres', true );
//        $dsoi_agriments_ =  get_post_meta( $dsorder->ID, 'dsoi_agriments', true );
        
        $dsoi_orderId_ =  get_post_meta( $item->ID, 'dsoi_orderId', true );
        $dsoi_prodId_ =  get_post_meta( $item->ID, 'dsoi_prodId', true );
        $dsoi_prodUrl_ =  get_post_meta( $item->ID, 'dsoi_prodUrl', true );
        $dsoi_prodCategory_ =  get_post_meta( $item->ID, 'dsoi_prodCategory', true );
        $dsoi_prodName_ =  get_post_meta( $item->ID, 'dsoi_prodName', true );
        $dsoi_count_ =  get_post_meta( $item->ID, 'dsoi_count', true );
//        $dsoi_items_count_ =  get_post_meta( $item->ID, 'dsoi_items_count', true );
        $dsoi_item_cost_ =  get_post_meta( $item->ID, 'dsoi_item_cost', true );
        $dsoi_items_cost_ =  get_post_meta( $item->ID, 'dsoi_items_cost', true );
        
        
        $dsoi_delivery_poland_cost_ =  get_post_meta( $item->ID, 'dsoi_delivery_poland_cost', true );
        $dsoi_deliveryPolad_ =  get_post_meta( $item->ID, 'dsoi_delivery_poland', true );
        $dsoi_delivery_cost_ =  get_post_meta( $item->ID, 'dsoi_delivery_cost', true );
        $dsoi_deliveryId_ =  get_post_meta( $item->ID, 'dsoi_delivery_id', true );
        $dsoi_deliveryName_ =  get_post_meta( $item->ID, 'dsoi_delivery_name', true );
        $dsoi_markup_ =  get_post_meta( $item->ID, 'dsoi_markup', true );
        
//        $dsoi_delivery_poland_cost_ =  get_post_meta( $dsorder->ID, 'dsoi_delivery_poland_cost', true );
//        $dsoi_deliveryPolad_ =  get_post_meta( $dsorder->ID, 'dsoi_deliveryPolad', true );
//        $dsoi_delivery_cost_ =  get_post_meta( $dsorder->ID, 'dsoi_delivery_cost', true );
//        $dsoi_deliveryId_ =  get_post_meta( $dsorder->ID, 'dsoi_deliveryId', true );
//        $dsoi_deliveryName_ =  get_post_meta( $dsorder->ID, 'dsoi_deliveryName', true );
//        $dsoi_markup_ =  get_post_meta( $dsorder->ID, 'dsoi_markup', true );
//        $dsoi_total_ =  get_post_meta( $dsorder->ID, 'dsoi_cost', true );
        
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
                ?>
        <table>
            
            <tr><td style="" colspan="2"><b>Данные позиции заказа:</b></td></tr>
            
            <tr><td style="" colspan="">ID заказа</td>
                <td colspan=""><?=$dsoi_orderId_?></td></tr>
            
            <tr><td style="" colspan="">ID товара</td>
                <td colspan=""><?=$dsoi_prodId_?></td></tr>
            
            <tr><td style="" colspan="">Ссылка на товар</td>
                <td colspan=""><?=$dsoi_prodUrl_?></td></tr>
            
            <tr><td style="" colspan="">Категория товара</td>
                <td colspan=""><?=$dsoi_prodCategory_?></td></tr>
            
            <tr><td style="" colspan="">Название товара</td>
                <td colspan=""><?=$dsoi_prodName_?></td></tr>
            
            <tr><td style="" colspan="">Количество единиц</td>
                <td colspan=""><input style="width: 100%" type="text"
                name="news_source" value="<?=$dsoi_count_?>"></td></tr>
            
            <tr><td style="" colspan="">Цена позиции</td>
                <td colspan=""><input style="width: 100%" type="text"
                name="news_source" value="<?=$dsoi_item_cost_?>"></td></tr>
            
        <?php if($delivery_use){ ?>
            <tr><td style="" colspan="">Стоимость доставки в Польше</td>
                <td colspan=""><input style="width: 100%" type="text"
                name="news_source" value="<?=$dsoi_delivery_poland_cost_?>"></td></tr>
            
            <tr><td style="" colspan="">?Служба доставки в Польше</td>
                <td colspan=""><input style="width: 100%" type="text"
                name="news_source" value="<?=$dsoi_deliveryPolad_?>"></td></tr>
            
            <tr><td style="" colspan="">Стоимость доставки</td>
                <td colspan=""><input style="width: 100%" type="text"
                name="news_source" value="<?=$dsoi_delivery_cost_?>"></td></tr>
            
            <tr><td style="" colspan="">ID службы доставки</td>
                <td colspan=""><input style="width: 100%" type="text"
                name="news_source" value="<?=$dsoi_deliveryId_?>"></td></tr>
            
            <tr><td style="" colspan="">Имя службы доставки</td>
                <td colspan=""><input style="width: 100%" type="text"
                name="news_source" value="<?=$dsoi_deliveryName_?>"></td></tr>
            
            <tr><td style="" colspan="">Наценка %</td>
                <td colspan=""><input style="width: 100%" type="text"
                name="news_source" value="<?=$dsoi_markup_?>"></td></tr>
        <?php } ?>
            
            <?php do_action('ds_admin_DShopOrderItem_info',$dsoi_prodId_,$item->ID); ?>
            
            <tr><td style="" colspan="">Сумма</td>
                <td colspan=""><input style="width: 100%" type="text"
                name="news_source" value="<?=$dsoi_items_cost_?>"></td></tr>
            
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
    public function add_metabox_fields( $post_id, $dsorder ) {
        if ( $dsorder->post_type == $this->name ) {
            $fields=$this->meta_fields;
            foreach($fields as $f=>$l){
                if ( isset( $_POST[$f] )
                        && $_POST[$f] != '' ) {
                    update_post_meta( $post_id, $f, $_POST[$f] );
                }
            }
        }
    }
}