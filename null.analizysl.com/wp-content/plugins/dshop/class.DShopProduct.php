<?php

/* 
 * class.DShopProduct.php
 */


/* 
 * lend
 * bad_agent
 * list
 * __list_bad_agents__
 * __form_bad_agent__
 * Реестр недобросовестных собственников
 */

class DShopProduct extends lend {
    public $name = 'dsproduct'; // 'bad_agent';
    public $nm = 'dsp_';
    public $meta_name = 'Опции ';
    /**
     * инициализация типа постов
     */
    public function post_type_init() {
        $labels = array(
            'name' => 'Товары',
            'singular_name' => 'dsproduct', // админ панель Добавить->Функцию
            'add_new' => 'Добавить товар',
            'add_new_item' => 'Добавить новый товар', // заголовок тега <title>
            'edit_item' => 'Редактировать товар',
            'new_item' => 'Новый товар',
            'all_items' => 'Все товары',
            'view_item' => 'Просмотр товара на сайте',
            'search_items' => 'Искать товар',
            'not_found' =>  'Товар не найден.',
            'not_found_in_trash' => 'В корзине нет товаров.',
            'menu_name' => 'Товары' // ссылка в меню в админке
        );
        $args = array(
            'labels' => $labels,
            'public' => false,
            'show_ui' => true, // показывать интерфейс в админке
            'has_archive' => true, 
    //        https://developer.wordpress.org/resource/dashicons/#controls-repeat
    //		'menu_icon' => get_stylesheet_directory_uri() .'/img/function_icon.png', // иконка в меню
            'menu_position' => 20, // порядок в меню
//            'menu_icon' => 'dashicons-plus', // иконка в меню
            'menu_icon' => 'dashicons-clipboard', // иконка в меню
            'supports' => array(
                'title'=>false,
                 'editor'=>false,
//                  'custom-fields',
//                'comments',
//                'author',
//                'thumbnail'
                ),
    //		'rewrite'             => array( 'slug'=>'resume/%resumecat%', 'with_front'=>false, 'pages'=>false, 'feeds'=>false, 'feed'=>false ),
    		'has_archive'         => 'dsproduct',
        );
        register_post_type($this->name, $args);
    }
    /**
     * инициализация метатегов
     */
    public function init_meta_fields(){
        global $wpdb;
        /*
         * bad_agent
         * 
         * adress
         * fio
         * position
         * phone
         * email
         * state
         */
//        $rep=[];
//        $rep['__title__']=  $post->post_title;
//        $rep['__text__']=  $post->post_content;
//        $rep['__author__']=  get_post_meta($id,'ln_author', 1);
//        $rep['__company__']=  get_post_meta($id,'ln_company', 1);
//        $rep['__brand__']=  get_post_meta($id,'ln_brand', 1);
//        $rep['__href__']=  get_post_meta($id,'ln_href', 1);
//        $rep['__type__']=  get_post_meta($id,'ln_type', 1);
//        $rep['__img__']=  get_post_meta($id,'ln_img', 1);
//        $rep['__img2__']=  get_post_meta($id,'ln_img2', 1);
//        $rep['__img3__']=  get_post_meta($id,'ln_img3', 1);
        
//        http://crepartner.ru/bad_agent/%D0%B1%D1%86-%D0%BD%D0%BE%D0%B5%D0%B2-%D0%BA%D0%BE%D0%B2%D1%87%D0%B5%D0%B3-%D0%B8%D0%B2%D0%B0%D0%BD%D0%BE%D0%B2-%D0%B8-%D0%B8-%D0%B4%D0%B8%D1%80%D0%B5%D0%BA%D1%82%D0%BE%D1%80/
        //
        // список мета тегов
        $fields=[];
        $nm=$this->nm;
//        $fields[$nm.'adress']='Адрес*';
//        $fields[$nm.'namebc']=' Наименование БЦ';
        $fields[$nm.'title']='Название товара';
        $fields[$nm.'short']='Краткое описание';
        $fields[$nm.'desc']='Расширенное описание';
        $fields[$nm.'cost']='стоимость';
        $fields[$nm.'count']='количество';
        $fields[$nm.'max']='Максимум в заказе';
        $fields[$nm.'min']='Минимум в заказе';
        $fields[$nm.'pid']='Id товара';
        $fields[$nm.'code']='Код товара';
        
//        $opt_num = 0;
//        $fields[$nm.'opt_'.$opt_num++]='Срок выполнения';
//        $fields[$nm.'opt_'.$opt_num++]='Синонимы (rus)';
//        $fields[$nm.'opt_'.$opt_num++]='Синонимы (eng)';
//        $fields[$nm.'opt_'.$opt_num++]='Методы';
//        $fields[$nm.'opt_'.$opt_num++]='Единицы измерения';
//        $fields[$nm.'opt_'.$opt_num++]='Подготовка к исследованию';
//        $fields[$nm.'opt_'.$opt_num++]='Тип биоматериала и  способы взятия';
        
//        $fields[$nm.'code']='Код товара';
        
//        Проблема решена / Проблема не решена
        
//        Форма для добавления:
//    ФИО:
//    Должность в компании:
//    Дата рождения:
//    Телефон:
//    e-mail:
//    Описание ситуации: (поле с изменяющимся размером)
//    кто добавил (выбор из списка участников)
//    Статус решения: (выбор из двух вариантов) Проблема решена / Проблема не решена
            
        
//        $fields[$nm.'weight']='Позиция в списке';
//        $fields[$nm.'type']='Тип секции';
//        $fields[$nm.'s_id']='ID секции';
//        $fields[$nm.'s_class']='Class секции';
//        
//        $fields[$nm.'author']='author';
//        $fields[$nm.'company']='company';
//        $fields[$nm.'brand']='brand';
//        $fields[$nm.'href']='href';
//        $fields[$nm.'type']='Class секции';
//        $fields[$nm.'img']='img src=';
//        $fields[$nm.'img2']='img2 src=';
//        $fields[$nm.'img3']='img3 src=';
        $this->meta_fields = $fields;
        

        // типы шаблонов поля метатегов
        $ftpl=[];
        $ftpl[$nm.'title']='td_i_';
        $ftpl[$nm.'short']='td_ta_';
        $ftpl[$nm.'desc']='td_ta_';
        $ftpl[$nm.'cost']='td_d_';
        $ftpl[$nm.'count']='td_d_';
        $ftpl[$nm.'max']='td_d_';
        $ftpl[$nm.'min']='td_d_';
        $ftpl[$nm.'pid']='td_i_';
        $ftpl[$nm.'code']='td_i_';
        
//        $opt_num = 0;
//        $ftpl[$nm.'opt_'.$opt_num++]='td_ta_';
//        $ftpl[$nm.'opt_'.$opt_num++]='td_ta_';
//        $ftpl[$nm.'opt_'.$opt_num++]='td_ta_';
//        $ftpl[$nm.'opt_'.$opt_num++]='td_ta_';
//        $ftpl[$nm.'opt_'.$opt_num++]='td_ta_';
//        $ftpl[$nm.'opt_'.$opt_num++]='td_ta_';
//        $ftpl[$nm.'opt_'.$opt_num++]='td_ta_';
        
//        $ftpl[$nm.'adress'] = 'td_i_';
//        $ftpl[$nm.'namebc'] = 'td_i_';
//        $ftpl[$nm.'fio'] = 'td_i_';
//        $ftpl[$nm.'position'] = 'td_i_';
//        $ftpl[$nm.'born'] = 'td_i_';
//        $ftpl[$nm.'phone'] = 'td_d_';
//        $ftpl[$nm.'email'] = 'td_i_';
//        $ftpl[$nm.'state'] = 'td_s_';
        
//        $ftpl[$nm.'weight'] = 'td_d_';
//        $ftpl[$nm.'type'] = 'td_s_';
//        $ftpl[$nm.'s_id'] = 'td_i_';
//        $ftpl[$nm.'s_class'] = 'td_i_';
//        
//        $ftpl[$nm.'author'] = 'td_i_';
//        $ftpl[$nm.'company'] = 'td_i_';
//        $ftpl[$nm.'brand'] = 'td_i_';
//        $ftpl[$nm.'href'] = 'td_i_';
//        $ftpl[$nm.'img'] = 'td_i_';
//        $ftpl[$nm.'img2'] = 'td_i_';
//        $ftpl[$nm.'img3'] = 'td_i_';
//        $ftpl[] = 'td_t_';
//        $ftpl[] = 'td_i_';
        $this->meta_ftpl = $ftpl;

        // значения метатегов
        $ftpl=[];
        $ftpl[$nm.'cost']='0';
        $ftpl[$nm.'count']='1000';
        $ftpl[$nm.'max']='1000';
        $ftpl[$nm.'min']='1';
        $ftpl[$nm.'title']='';
        $ftpl[$nm.'short']='';
        $ftpl[$nm.'desc']='';
        $ftpl[$nm.'pid']=isset($_GET['pid'])?((int)$_GET['pid']):'';
        $ftpl[$nm.'code']='';
        
//        $opt_num = 0;
//        $ftpl[$nm.'opt_'.$opt_num++]='';
//        $ftpl[$nm.'opt_'.$opt_num++]='';
//        $ftpl[$nm.'opt_'.$opt_num++]='';
//        $ftpl[$nm.'opt_'.$opt_num++]='';
//        $ftpl[$nm.'opt_'.$opt_num++]='';
//        $ftpl[$nm.'opt_'.$opt_num++]='';
//        $ftpl[$nm.'opt_'.$opt_num++]='';
        
//    $ds_ext_ml = new DShopExtensionMedLab();
//    $ds_ext_ml->init();
//        if(isset($_GET['pid']) && ((int)$_GET['pid']) > 0){
//            $ftpl[$nm.'code']= 'r' ;
//            $prodId = (int)$_GET['pid'];
//            $this->meta_val = apply_filters('ds_dsproduct_post_init_defaults', $this->meta_val, $prodId);
//        $this->meta_val = $ftpl;
//            
//        }
//        $fff = [];
//            $fff = apply_filters('ds_dsorder_post_display_meta_box_order__out_status', $fff,  $fff, null);
//        $ftpl[$nm.'desc']=print_r($fff,1);
//	global $wp_filter, $wp_current_filter;
//        $ftpl[$nm.'desc']=print_r($wp_filter,1);
    
//            $dsorder = null;
//            $states =[
//                'created'=>'Обрабатывается',
//            ];
//            $states = apply_filters('ds_dsorder_post_display_meta_box_order__out_status', $states, $dsorder, $this);
//        $ftpl[$nm.'desc']=print_r($states,1);
        
//        $ftpl[$nm.'adress'] = '';
//        $ftpl[$nm.'namebc'] = '';
//        $ftpl[$nm.'fio'] = '';
//        $ftpl[$nm.'position'] = '';
//        $ftpl[$nm.'born'] = '';
//        $ftpl[$nm.'phone'] = '';
//        $ftpl[$nm.'email'] = '';
//        $ftpl[$nm.'state'] = '0';
        
//        $ftpl[$nm.'weight'] = '0';
//        $ftpl[$nm.'type'] = '';
//        $ftpl[$nm.'s_id'] = '';
//        $ftpl[$nm.'s_class'] = '';
//        
//        $ftpl[$nm.'author'] = '';
//        $ftpl[$nm.'company'] = '';
//        $ftpl[$nm.'brand'] = '';
//        $ftpl[$nm.'href'] = '';
//        $ftpl[$nm.'img'] = '';
//        $ftpl[$nm.'img2'] = '';
//        $ftpl[$nm.'img3'] = '';
        $this->meta_val = $ftpl;

        // варианты значений метатегов
        $ftpl=[];
        $ftpl[$nm.'cost']=false;
        $ftpl[$nm.'count']=false;
        $ftpl[$nm.'max']=false;
        $ftpl[$nm.'min']=false;
        $ftpl[$nm.'title']=false;
        $ftpl[$nm.'short']=false;
        $ftpl[$nm.'desc']=false;
        $ftpl[$nm.'pid']=false;
        $ftpl[$nm.'code']=false;
        
//        $opt_num = 0;
//        $ftpl[$nm.'opt_'.$opt_num++]=false;
//        $ftpl[$nm.'opt_'.$opt_num++]=false;
//        $ftpl[$nm.'opt_'.$opt_num++]=false;
//        $ftpl[$nm.'opt_'.$opt_num++]=false;
//        $ftpl[$nm.'opt_'.$opt_num++]=false;
//        $ftpl[$nm.'opt_'.$opt_num++]=false;
//        $ftpl[$nm.'opt_'.$opt_num++]=false;
        
//        $ftpl[$nm.'adress'] = false;
//        $ftpl[$nm.'namebc'] = false;
        
//        $ftpl[$nm.'fio'] = false;
//        $ftpl[$nm.'position'] = false;
//        $ftpl[$nm.'born'] = false;
//        $ftpl[$nm.'phone'] = false;
//        $ftpl[$nm.'email'] = false;
//        $ftpl[$nm.'state'] = [];
//        $ftpl[$nm.'state']['0'] = 'Проблема не решена';
//        $ftpl[$nm.'state']['1'] = 'Проблема решена';
        
//        $ftpl[$nm.'weight'] = false;
//        
//        $ftpl[$nm.'type'] = [];
//        $ftpl[$nm.'type']['meet'] = 'meet';
//        $ftpl[$nm.'type']['career'] = 'career';
//        $ftpl[$nm.'type']['blog'] = 'blog';
        
//        $ftpl[$nm.'type']['text'] = 'Текст. Выводится как есть.';
//        $ftpl[$nm.'type']['crs_top'] = 'Карусель в хедере';
//        $ftpl[$nm.'type']['lst_how'] = 'Список:"How we makes"';
//        $ftpl[$nm.'type']['crs_meet'] = 'Карусель:"Let’s Meet"';
//        $ftpl[$nm.'type']['crs_brands'] = 'Карусель:"Brands Such"';
//        $ftpl[$nm.'type']['tab_reviews'] = 'Табы:"Testimonials"';
//        $ftpl[$nm.'type']['lst_summarize'] = 'Список:"Summarize"';
//        $ftpl[$nm.'type']['crs_clients'] = 'Карусель:"Trusted by"';
//        $ftpl[$nm.'type'][''] = '';
        
//        $ftpl[$nm.'s_id'] = false;
//        $ftpl[$nm.'s_class'] = false;
//        
//        $ftpl[$nm.'author'] = false;
//        $ftpl[$nm.'company'] = false;
//        $ftpl[$nm.'brand'] = false;
//        $ftpl[$nm.'href'] = false;
//        $ftpl[$nm.'img'] = false;
//        $ftpl[$nm.'img2'] = false;
//        $ftpl[$nm.'img3'] = false;
        $this->meta_vars = $ftpl;
        
        
        $opt_num = 0;// 7
        $ftpl['f_'.$opt_num]=$nm.'opt_'.$opt_num;$opt_num++;
        $ftpl['f_'.$opt_num]=$nm.'opt_'.$opt_num;$opt_num++;
        $ftpl['f_'.$opt_num]=$nm.'opt_'.$opt_num;$opt_num++;
        $ftpl['f_'.$opt_num]=$nm.'opt_'.$opt_num;$opt_num++;
        $ftpl['f_'.$opt_num]=$nm.'opt_'.$opt_num;$opt_num++;
        $ftpl['f_'.$opt_num]=$nm.'opt_'.$opt_num;$opt_num++;
        $ftpl['f_'.$opt_num]=$nm.'opt_'.$opt_num;$opt_num++;
        $this->meta_field_alt = $ftpl;
        
        $hook = 'ds__init_meta_fields__dsproduct';
        add_action('ds__init_meta_fields__dsproduct', [$this,'ds__init_meta_fields__dsproduct'], 3, 1 );
        do_action($hook,[$this]);
//        do_action($hook,[$this]);
//        echo($hook);
//        apply_filters($hook, $this);
        
        $this->meta_fields += $this->meta_fields_attr;
        $this->meta_ftpl += $this->meta_ftpl_attr;
        $this->meta_val += $this->meta_val_attr;
        $this->meta_vars += $this->meta_vars_attr;
    }
    
    public function ds__init_meta_fields__dsproduct($object){
        global $wpdb;
        $dsp_fields= $wpdb->prefix . "dsp_fields";
        $q= "select * from `$dsp_fields` order by `weigh`";
        $fields = $wpdb->get_results($q,ARRAY_A);
        foreach($fields as $field){
            $name = $field['name'];
            $object->meta_fields[$name] = $field['title'];
            $object->meta_ftpl[$name] = $field['tpl'];
            $object->meta_val[$name] = $field['def'];
            $object->meta_vars[$name] = false;
            $vars = unserialize($field['vars']);
            if(count($vars))$object->meta_vars[$name] = $vars;
            
        }
    }
    
    public function add_metabox() {
        if(isset($_GET['pid']) && ((int)$_GET['pid']) > 0){
            $prodId = (int)$_GET['pid'];
            $this->meta_val = apply_filters('ds_dsproduct_post_init_defaults', $this->meta_val, $prodId);
        }
        add_meta_box( 'ccab_'.$this->name.'_meta_box',
            $this->meta_name,
            [$this,'display_meta_box'],
            $this->name, 'normal', 'high'
        );
    }
    /**
     * сохранение данных полей метатегов
     * @param type $post_id
     * @param type $movie_review
     */
    public function add_metabox_fields( $post_id, $movie_review ) {
        if ( $movie_review->post_type == $this->name ) {
            $fields=$this->meta_fields;
            foreach($fields as $f=>$l){
                if ( isset( $_POST[$f] )
                        && $_POST[$f] != '' ) {
                    update_post_meta( $post_id, $f, $_POST[$f] );
                }
            }
        }
        $redirect_to = filter_input(INPUT_POST, 'redirect_to', FILTER_DEFAULT);
        if($redirect_to){
            wp_redirect( urldecode($redirect_to ) );
        }
    }
}
//$lend = new lend_it_();