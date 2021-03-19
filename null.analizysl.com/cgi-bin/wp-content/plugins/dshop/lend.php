<?php

/* 
 * lend
 */

class lend extends DShopInit{
//class lend extends lend {
    public $name = 'lend';
    public $nm = 'ln_';
    public $meta_name = 'Опции лендинга';
    public $meta_fields = [];
    public $meta_fields_attr = [];
    public $meta_desc = [];
    public $meta_types = [];
    public $meta_val= [];
    public $meta_val_attr= [];
    public $meta_field_alt= [];
    public $meta_vars= [];
    public $meta_vars_attr= [];
    public $meta_validates = [];
    public $meta_ftpl = [];
    public $meta_ftpl_attr = [];
    public $meta_tpls = [];
    public function __construct() {
        $this->init();
    }
//    public function __destruct() {
//        ;
//    }
//    public function __call($name, $arguments) {
//        ;
//    }

    public function init(){
        $this->init_type();
        $this->init_meta();
    }
    public function init_type(){
        add_action( 'init', [$this,'post_type_init'] );
//        add_action( 'plugins_loaded', [$this,'init_meta_fields'] );
        // создаем новую колонку
        // manage_(post_type)_posts_columns
//        add_filter( 'manage_'.$this->name.'_posts_columns',
//                [$this,'add_views_column'], 4 );
        
        // заполняем колонку данными
        // manage_(post_type)_posts_custom_column
        // wp-admin/includes/class-wp-posts-list-table.php
//        add_action('manage_'.$this->name.'_posts_custom_column',
//                [$this,'fill_views_column'], 5, 2 );
        
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
        $this->init_meta_fields();
        $this->init_meta_tpls();
        if(count($this->meta_ftpl)>0)
        add_action( 'admin_init', [$this,'add_metabox'] );
        add_action( 'save_post', [$this,'add_metabox_fields'], 10, 2 );
    }
    /**
     * инициализация типа постов
     */
    public function post_type_init() {
        $labels = array(
            'name' => 'Лендинг',
            'singular_name' => 'Секцию', // админ панель Добавить->Функцию
            'add_new' => 'Добавить секцию лендинга',
            'add_new_item' => 'Добавить новую секцию лендинга', // заголовок тега <title>
            'edit_item' => 'Редактировать секцию лендинга',
            'new_item' => 'Новая секция лендинга',
            'all_items' => 'Все секции лендинга',
            'view_item' => 'Просмотр секции лендинга на сайте',
            'search_items' => 'Искать секцию лендинга',
            'not_found' =>  'Секция лендинга не найдена.',
            'not_found_in_trash' => 'В корзине нет секций лендинга.',
            'menu_name' => 'Лендинг' // ссылка в меню в админке
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
                 'editor',
                //  'custom-fields',
                //'comments',
//                'author',
                'thumbnail'),
    //		'rewrite'             => array( 'slug'=>'resume/%resumecat%', 'with_front'=>false, 'pages'=>false, 'feeds'=>false, 'feed'=>false ),
    //		'has_archive'         => 'resume',
        );
        register_post_type($this->name, $args);
    }
    /**
     * создаем новую колонку
     * manage_(post_type)_posts_columns
     * 
     * @param type $columns
     * @return type
     */
    public function add_views_column( $columns ){
    //	$num = 1; // после какой по счету колонки вставлять новые
        $num = 2; // после какой по счету колонки вставлять новые

        $nm=$this->nm;
        $new_columns = array(
    //        'cb' => '<input type="checkbox" />',
    //            'ex_id'         => 'ID',
    //            'ex_title'      => 'Title',
    //            'ex_author'     => 'Author',
    //            'ex_description'=> 'Description',
    //            'ex_price'      => 'Price',

    //            'id'         => 'ID',
    //            'create'      => 'create',
    //            'update'     => 'update',
                $nm.'weight'=> 'weight',
    //            'status'=> 'status',
    //            'fname'      => 'Имя',
    //            'sname'      => 'Отчество',
    //            'lname'      => 'Фамилия',
    ////            'old'      => 'возраст',
    //            'birthday'      => 'День рождения',
    //            'birthmonth'      => 'Месяц рождения',
    //            'birthyear'      => 'Год рождения',
    //            'lwork1'      => 'место работы №1',
    //            'lwork2'      => 'место работы №2',
    //            'lwork3'      => 'место работы №3',
    //            'phone'      => 'Телефон',
    //            'email'      => 'email',
    ////            'fid'      => 'file id',
    ////            'file'      => 'file path',
    //            'profdata'      => 'Профессиональные данные',
    ////            'publisher' => __('Publisher'),
            );
    //	unset($columns['title'] );
    //	unset($columns['comments'] );
    //	unset($columns['author'] );
    //	unset($columns['date'] );

        return array_slice( $columns, 0, $num ) + $new_columns + array_slice( $columns, $num );
    }

    /**
     * заполняем колонку данными
     * manage_(post_type)_posts_custom_column
     * wp-admin/includes/class-wp-posts-list-table.php
     * 
     * @param type $colname
     * @param type $post_id
     * @return type
     */
    public function fill_views_column( $colname, $post_id ){

        $columns = array(
            'status',
            'fname',
            'sname',
            'lname',
    //        'old',
            'birthday',
            'birthmonth',
            'birthyear',
            'phone',
            'email',
            );
        $columns = $this->meta_fields;
//        if(!( in_array($colname,$columns) ) ){
//            return;
//        }
        if(!( array_key_exists($colname,$this->meta_fields) ) ){
            return;
        }
        
        if( $colname === 'adres' ){
            echo get_post_meta( $post_id, 'Место проживания', 1 );
        }

        $_status_arr =  [];

        $_status_arr['publish']    =  'Oпубликован';
        $_status_arr['future']     = 'future';
        $_status_arr['draft']      =  'Черновик';
        $_status_arr['pending']    =  'На утверждении';
        $_status_arr['private']    = 'private';
        $_status_arr['trash']      = 'trash';
        $_status_arr['auto-draft'] = 'auto-draft';
        $_status_arr['inherit']    = 'inherit';

        $out='';
        $tpl='__t__';
        $r=[];
        switch ($colname){
            case 'fname': $d=get_post_meta( $post_id, 'fname', 1 );
                $r['__t__']=$d;break;
            case 'sname': $d=get_post_meta( $post_id, 'sname', 1 );
                $r['__t__']=$d;break;
            case 'lname': $d=get_post_meta( $post_id, 'lname', 1 );
                $r['__t__']=$d;break;
    //        case 'old':$d=get_post_meta( $post_id, 'old', 1 );
    //            $r['__t__']=$d;break;
            case 'birthday':$d=get_post_meta( $post_id, 'birthday', 1 );
                $r['__t__']=$d;break;
            case 'birthmonth':$d=get_post_meta( $post_id, 'birthmonth', 1 );
                $r['__t__']=$d;break;
            case 'birthyear':$d=get_post_meta( $post_id, 'birthyear', 1 );
                $r['__t__']=$d;break;
            case 'lwork1': $d=get_post_meta( $post_id, 'lwork1', 1 );
                $r['__t__']=$d;break;
            case 'lwork2':$d=get_post_meta( $post_id, 'lwork2', 1 );
                $r['__t__']=$d;break;
            case 'lwork3':$d=get_post_meta( $post_id, 'lwork3', 1 );
                $r['__t__']=$d;break;
            case 'phone':$d=get_post_meta( $post_id, 'phone', 1 );
                $r['__t__']=$d;break;
            case 'email':$d=get_post_meta( $post_id, 'email', 1 );
                $r['__t__']=$d;break;
    //        case 'fid':$d=get_post_meta( $post_id, 'fid', 1 );
    //            $r['__t__']=$d;break;
    //        case 'file':$d=get_post_meta( $post_id, 'file', 1 );
    //            $r['__t__']=$d;break;
            case 'profdata':$d=get_post_meta( $post_id, 'profdata', 1 );
                $r['__t__']=$d;break;
            case 'status':$d=get_post_status( $post_id ); $d= $_status_arr[$d];
                $r['__t__']=$d;break;
        }
        if(( array_key_exists($colname,$this->meta_fields) ) ){
            $d=get_post_meta( $post_id, $colname, 1 );
            $r['__t__']=$d;
        }
        
        $out = strtr($tpl,$r);
        echo $out;

    }

    // 
    // 
    /**
     * добавляем возможность сортировать колонку
     * manage_(screen_id)_sortable_columns
     * 
     * @param type $sortable_columns
     * @return boolean
     */
    public function add_views_sortable_column( $sortable_columns ){
//        if(!( array_key_exists($colname,$this->meta_fields) ) ){
//            return;
//        }
        // false = asc (по умолчанию)
        // true  = desc
    //	$sortable_columns['views'] = [ 'views_views', false ]; 
        foreach ($this->meta_fields as $key => $value) {
            $sortable_columns[$key] = [ $key, false ];
        }
//    	$sortable_columns['views'] = [ 'views_views', false ]; 
//        unset($sortable_columns['comments'] );
        return $sortable_columns;
    }
    
    /**
     * изменяем запрос при сортировке колонки
     * 
     * @param type $query
     * @return type
     */
    public function add_column_views_request( $query ){
        if( ! is_admin() 
            || ! $query->is_main_query() 
            || $query->get('orderby') !== 'ln_weight'// views_views
            || get_current_screen()->id !== 'edit-'.$this->name
        )
            return;

//        $query->set( 'meta_key', 'views' );
//        $query->set( 'orderby', 'meta_value_num' );
//        $query->set( 'orderby', 'ln_weight' );
        $query->set( 'meta_key', 'ln_weight' );
        $query->set( 'orderby', 'meta_value_num' );
    }

    /**
     *  подправим ширину колонки через css
     */
    function add_views_column_css(){
        echo '<style type="text/css">.column-ln_weight{ width:100px; }</style>';
    }

    /**
     * инициализация метатегов
     */
    public function init_meta_fields(){
        // список мета тегов
        $fields=[];
        $nm=$this->nm;
        $fields[$nm.'weight']='Позиция в списке';
        $fields[$nm.'type']='Тип секции';
        $fields[$nm.'s_id']='ID секции';
        $fields[$nm.'s_class']='Class секции';
//        $fields['vac_razrad']='Разряд';
        $this->meta_fields = $fields;
        

        // типы шаблонов поля метатегов
        $ftpl=[];
        $ftpl[$nm.'weight'] = 'td_d_';
        $ftpl[$nm.'type'] = 'td_s_';
        $ftpl[$nm.'s_id'] = 'td_i_';
        $ftpl[$nm.'s_class'] = 'td_i_';
//        $ftpl[] = 'td_t_';
//        $ftpl[] = 'td_i_';
        $this->meta_ftpl = $ftpl;

        // значчения метатегов
        $ftpl=[];
        $ftpl[$nm.'weight'] = wp_count_posts($this->name)->publish+1;
        $ftpl[$nm.'type'] = '';
        $ftpl[$nm.'s_id'] = '';
        $ftpl[$nm.'s_class'] = '';
//        $ftpl[] = 'td_t_';
//        $ftpl[] = 'td_i_';
        $this->meta_val = $ftpl;

        // варианты значений метатегов
        $ftpl=[];
        $ftpl[$nm.'weight'] = false;
        
        $ftpl[$nm.'type'] = [];
        $ftpl[$nm.'type']['text'] = 'Текст. Выводится как есть.';
        $ftpl[$nm.'type']['crs_top'] = 'Карусель в хедере';
        $ftpl[$nm.'type']['lst_how'] = 'Список:"How we makes"';
        $ftpl[$nm.'type']['crs_meet'] = 'Карусель:"Let’s Meet"';
        $ftpl[$nm.'type']['crs_brands'] = 'Карусель:"Brands Such"';
        $ftpl[$nm.'type']['tab_reviews'] = 'Табы:"Testimonials"';
        $ftpl[$nm.'type']['lst_summarize'] = 'Список:"Summarize"';
        $ftpl[$nm.'type']['frm_together'] = 'Форма:"Let’s work together"';
        $ftpl[$nm.'type']['crs_clients'] = 'Карусель:"Trusted by"';
//        $ftpl[$nm.'type'][''] = '';
        
        $ftpl[$nm.'s_id'] = false;
        $ftpl[$nm.'s_class'] = false;
//        $ftpl[] = 'td_t_';
//        $ftpl[] = 'td_i_';
        $this->meta_vars = $ftpl;
    }
    /**
     * шаблоны полей меттатегов
     */
    public function init_meta_tpls(){
        $ftpl=[];
        $ftpl['tpl_i_tr']=<<<t
        <tr>
            <th style="width: 50%">_l_</th>
            <td><input type="text" name="f__n_" value="_v_" /></td>
        </tr>
t;
        // tab wrupper

        $ftpl['tpl_i_tab']=<<<t
    <table width="100%">
        _tr_
    </table>
t;
    
    $ftpl['td_o_']=<<<td
        <tr>
            <th><label for="__for__">__label__</label>__desc__</th>
            <td><input id="__id__" type="number" name="__name__" value="__val__" class="regular-text" /></td>
        </tr>
td;
    // select
    $ftpl['td_s_']=<<<td
        <tr>
            <th><label for="__for__">__label__</label>__desc__</th>
            <td><select name="__name__" id="__id__" >__val__</select></td>
        </tr>
td;
    // decimal
    $ftpl['td_d_']=<<<td
        <tr>
            <th><label for="__for__">__label__</label>__desc__</th>
            <td><input id="__id__" type="number" name="__name__" value="__val__" class="regular-text" /></td>
        </tr>
td;
    // input text
    $ftpl['td_i_']=<<<td
        <tr>
            <th><label for="__for__">__label__</label>__desc__</th>
            <td><input id="__id__" type="text" name="__name__" value="__val__" class="regular-text" /></td>
        </tr>
td;
    // only text
    $ftpl['td_t_']=<<<td
        <tr>
            <th><label for="__for__">__label__</label>__desc__</th>
            <td><span id="__id__" class="regular-text field-__name__"><b>__val__</b></span></td>
        </tr>
td;
    // textarea
    $ftpl['td_ta_']=<<<td
        <tr>
            <th><label for="__for__">__label__</label>__desc__</th>
            <td><textarea cols="70" rows="5" id="__id__" name="__name__" class="regular-text field-__name__">__val__</textarea></td>
        </tr>
td;
        $td_ta_=<<<td
            <tr>
                <th><label for="__for__">__label__</label></th>
                <td><textarea cols="__cols__" rows="__rows__" id="__id__" name="__name__" class="-regular-text __i_class__" placeholder="__placeholder__">__val__</textarea></td>
            </tr>
td;
    $ftpl['td_ta_']=<<<td
        <tr>
            <th><label for="__for__">__label__</label>__desc__</th>
            <td><textarea cols="__cols__" rows="__rows__" id="__id__" name="__name__" class="regular-text field-__name__">__val__</textarea></td>
        </tr>
td;
        $this->meta_tpls = $ftpl;
    }
    
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
        add_meta_box( 'ccab_'.$this->name.'_meta_box',
            $this->meta_name,
            [$this,'display_meta_box'],
            $this->name, 'normal', 'high'
        );
    }

    /**
     * отрисовка полей метатегов
     * @param type $movie_review
     */
    public function display_meta_box( $movie_review ) {
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
            $r['__desc__']=$ht->f('div',$this->meta_desc[$f],$at);
        }
        if(isset($this->meta_val[$f]))
            $r['__val__']=$this->meta_val[$f];
        if(metadata_exists('post', $movie_review->ID, $f)){
//            $mfn = $f;
            $r['__val__']=esc_html( get_post_meta( $movie_review->ID, $f, true ) );
//            if(!$r['__val__']){
//                if(isset($this->meta_field_alt[$f])){
//                    $r['__val__']=esc_html( get_post_meta( $movie_review->ID, $this->meta_field_alt[$f], true ) );
//                }
//            }
        }else{
            if(isset($this->meta_field_alt[$f]) && metadata_exists('post', $movie_review->ID, $this->meta_field_alt[$f])){
                $r['__val__']=esc_html( get_post_meta( $movie_review->ID, $this->meta_field_alt[$f], true ) );
            }
            
        }
            if($this->meta_ftpl[$f] == 'td_ta_'){
                $rows =  count(explode("\n",$r['__val__']));//esc_attr
                $r['__rows__'] += $rows;
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
        /*?>
    <select name="<?php echo $option_name ?>" 
            id="<?php echo $id ?>" ><?= $o?></select>
        <?php/*
        ?>
        <input 
            type="text" 
            name="<?php echo $option_name ?>" 
            id="<?php echo $id ?>" 
            value="<?php echo esc_attr( get_option($option_name) ) ?>" 
        /> 
        <?*/
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
        if(is_array($m)|| is_object($m))
            $m= '<pre>'.print_r($m,1).'</pre>';
        echo '<div class="notice '.$class.' is-dismissible"> <p>'. $m .'</p></div>';
    }
}
//$lend = new lend();
