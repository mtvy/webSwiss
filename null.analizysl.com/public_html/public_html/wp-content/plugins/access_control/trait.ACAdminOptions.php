<?php
/*
 * trait.ACAdminOptions.php
 */
trait ACAdminOptions {
    public $options=[];
/*=======================*/
//https://wp-kama.ru/function/add_settings_field
    
    public function init_options(){
        add_action('admin_menu', [$this,'add_option_field_to_general_admin_page']);
    }
    public function parser_options_descrtiption(){
        echo '<b>Access Control - настройки</b>';
    }
    public function add_option_field($option_name,$option_title,
            $option_field,$id_block,$page,$arg){
        // регистрируем опцию
//        register_setting( 'general', $option_name);
        register_setting( $this->page, $option_name);
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
    public function add_option_field_to_general_admin_page(){
        $id_block = 'parser_path_settings';
        $title = 'Access Control s';
        $callback = [$this,'parser_options_descrtiption'];
        $page = 'general';
        $page = $this->page;
        add_settings_section( $id_block, $title, $callback, $page );

        
    $option_name = 'ds_pageid_login_redirect';
    $option_title = 'Id целевой страницы после логина';
    $option_field = 'number';
    $items = [];
//    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $arg = array(
        );
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

        
    $option_name = 'ds_pageid_personal_data_politic';
    $option_title = 'Id страницы "Политика обработки персональных данных"';
    $option_field = 'number';
    $items = [];
//    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $arg = array(
        );
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

        
    $option_name = 'ds_item_add_count_def';
    $option_title = 'Количество единиц в позиции по умолчанию';
    $option_field = 'number';
    $items = [];
//    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $arg = array(
        );
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );
    
    $option_name = 'ds_item_add_min';
    $option_title = 'Минимум единиц в позиции';
    $option_field = 'number';
    $items = [];
//    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $arg = array(
        );
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

    $option_name = 'ds_item_add_max';
    $option_title = 'Максимум единиц в позиции';
    $option_field = 'number';
    $items = [];
//    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $arg = array(
        );
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

    $option_name = 'ds_cart_item_max';
    $option_title = 'Максимум единиц позиции в корзине';
    $option_field = 'number';
    $items = [];
//    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $arg = array(
        );
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

    $option_name = 'ds_cart_items_max';
    $option_title = 'Максимум позиций в корзине';
    $option_field = 'number';
    $items = [];
//    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $arg = array(
        );
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

    $option_name = 'delivery_percent';
    $option_title = 'Процент доставки (доставка на склад)';
    $option_field = 'number';
    $items = [];
    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $arg = array(
        );
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

    $option_name = 'delivery_use';
    $option_title = 'Использовать доставку';
    $option_field = 'select';
    $items = [];
    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $arg = array(
        'items'=>[
            '0'=>'Нет',
            '1'=>'Да',
        ],
    );
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

    $option_name = 'course_zl_rub';
    $option_title = 'Коэфициент расчёта конечной валюты';
    $option_field = 'number';
    $items = [];
    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $arg = array(
        );
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

    $option_name = 'currency_short';
    $option_title = 'Валюта, краткое';
    $option_field = 'input';
    $items = [];
    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $arg = array(
            'type'=>'text',
        );
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

    $option_name = 'ds_notification_admin_mail';
    $option_title = 'Почта админа для оповещний о заказах';
    $option_field = 'input';
    $items = [];
    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $arg = array(
            'type'=>'text',
        );
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

    $option_name = 'ds_notification_test';
    $option_title = 'Тестирование почтовых опевещений';
    $option_field = 'select';
    $items = [];
    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $arg = array(
        'items'=>[
            '0'=>'Нет',
            '1'=>'Да',
        ],
    );
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );

    $option_name = 'ds_id_page_item';
    $option_title = 'Id страницы заказа';
    $option_field = 'number';
    $items = [];
    $items =  explode("\n",esc_attr( get_option('prod_parser_img_patterns') ));//esc_attr
    $arg = array(
        );
    $this->add_option_field($option_name,$option_title,$option_field,$id_block,$page,$arg );
    

//    add_option_field_to_general_admin_page
//    ds_dsorder_post_display_meta_box_order__out_status
    $dsorder = null;
//    $states = apply_filters('ds_options_add_field', $id_block, $dsorder, $this);
//    $states = apply_filters('ds_options_add_field', $id_block, $dsorder, $this);
        do_action('ds_options_add_field', $this, $page, $id_block);

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

    public function option_setting_textarea_callback( $val ){
        $cols = $val['cols'];
        $rows = 3;
        $id = $val['id'];
        $option_name = $val['option_name'];
        $val = get_option($option_name) ;
        $cou = count( explode("\n",$val));//esc_attr
        if($cou>=$rows)$rows = $cou+1;
        ?>
<textarea cols="<?=$cols?>" rows="<?=$rows?>"
        name="<?= $option_name ?>" 
        id="<?= $id ?>" ><?
        echo esc_attr( $val )
        ?></textarea>
    <?php
    }

    public function option_setting_text_callback( $val ){
        $id = $val['id'];
        $option_name = $val['option_name'];
        
        ?>
        <input 
            type="text" 
            name="<?= $option_name ?>" 
            id="<?= $id ?>" 
            value="<?= esc_attr( get_option($option_name) ) ?>" 
        /> 
        <?php
    }

    public function option_setting_input_callback( $val ){
        $id = $val['id'];
        $option_name = $val['option_name'];
        $type = $val['type'];
        
        ?>
        <input 
            type="<?= $type ?>" 
            name="<?= $option_name ?>" 
            id="<?= $id ?>" 
            value="<?= esc_attr( get_option($option_name) ) ?>" 
        /> 
        <?php
    }

    public function option_setting_number_callback( $val ){
        $id = $val['id'];
        $option_name = $val['option_name'];
        
        ?>
        <input 
            type="number" 
            name="<?= $option_name ?>" 
            id="<?= $id ?>" 
            value="<?= esc_attr( get_option($option_name) ) ?>" 
        /> 
        <?php
    }
    public function option_setting_select_callback( $val ){
        $id = $val['id'];
        $option_name = $val['option_name'];
        $tpl_o=<<<t
            <option value="_v_" _s_>_n_</option>
t;
        $r=[];
        $v_=get_option($option_name,'');
        foreach($val['items'] as $v=>$n){
            $r['_n_']=$n;
            $r['_v_']=$v;
            $r['_s_']=$v_==$v?'selected="selected"':'';
            $val['items'][$v] = strtr($tpl_o,$r);
        }
        $o=implode('',$val['items']);
        ?>
    <select name="<?= $option_name ?>" 
            id="<?= $id ?>" ><?= $o?></select>
        <?php
    }
    public function field_info_dl_links_callback( $val ){
        $id = $val['id'];
        $option_name = $val['option_name'];
        $tpl_o=<<<t
            <dt><b>_v_</b></dt>
            <dd><a href="_l_" target="_blank">_n_</a></dd>
t;
        $r=[];
        $v_=get_option($option_name,'');
        foreach($val['items'] as $v=>$n){
            $r['_n_']=$n;
            $r['_v_']=$v;
            $r['_l_']=$n;
//            $r['_s_']=$v_==$v?'selected="selected"':'';
            $val['items'][$v] = strtr($tpl_o,$r);
        }
        $o=implode('',$val['items']);
        ?><dl><?= $o?></dl>
        <?php
    }
    public function field_info_text_callback( $val ){
        $id = $val['id'];
        $option_name = $val['option_name'];
        $tpl_o=<<<t
            <b>_v_</b>
            <p>_n_</p>
t;
        $tpl_o=<<<t
            <p>_n_</p>
t;
        $r=[];
        $v_=get_option($option_name,'');
        foreach($val['items'] as $v=>$n){
            $r['_n_']=$n;
//            $r['_v_']=$v;
//            $r['_s_']=$v_==$v?'selected="selected"':'';
            $val['items'][$v] = strtr($tpl_o,$r);
        }
        $o=implode('',$val['items']);
        ?><?= $o?>
        <?php
    }

    public function myprefix_setting_callback_function( $val ){
        $id = $val['id'];
        $option_name = $val['option_name'];
        ?>
<textarea cols="70" rows="5"
        name="<?= $option_name ?>" 
        id="<?=  $id ?>" ><?
        echo esc_attr( get_option($option_name) )
        ?></textarea>
    <?php /*
        ?>
        <input 
            type="text" 
            name="<?= $option_name ?>" 
            id="<?= $id ?>" 
            value="<?= esc_attr( get_option($option_name) ) ?>" 
        /> 
        <?php*/
    }
        public function lending_change_link_way_function( $val ){
        $id = $val['id'];
        $option_name = $val['option_name'];
        $tpl_o=<<<t
            <option value="_v_" _s_>_n_</option>
t;
        $r=[];
        $v_=get_option($option_name,'desctop');
        foreach($val['items'] as $v=>$n){
            $r['_n_']=$n;
            $r['_v_']=$v;
            $r['_s_']=$v_==$v?'selected="selected"':'';
            $val['items'][$v] = strtr($tpl_o,$r);
        }
        $o=implode('',$val['items']);
        ?>
    <select name="<?= $option_name ?>" 
            id="<?= $id ?>" ><?= $o?></select>
        <?php /*
        ?>
        <input 
            type="text" 
            name="<?= $option_name ?>" 
            id="<?= $id ?>" 
            value="<?= esc_attr( get_option($option_name) ) ?>" 
        /> 
        <?php*/
    }

/*=======================*/
    
}
