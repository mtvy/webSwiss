<?php

/* 
 * class.DShopAdmin.php
 */
include_once 'trait.DShopAdminOptions.php';

class DShopAdmin{
    public $rpage = 'dshop';
    public $page = 'dshop.php';
    use 
            DShopAdminOptions;
    public function __construct() {
//        add_action('admin_notices', [$this,'_notice']);
//        add_action('admin_notices', [$this,'_notice']);
//        $this->notice('hello 1');
//        $this->notice('hello 1','error');
//        $this->notice('hello 2','warning');
        add_action('admin_menu', [$this,'admin_menu']);
        add_action('admin_menu', [$this,'options']);
        $this->init_options();
        
        add_action('admin_notices', [$this,'_notices']);
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
//        $hook = add_menu_page('DShop', 'DShop', 1,
        $hook = add_menu_page('DShop', 'DShop', 'manage_options',
            $this->page, [$this,'page_wrapper'],'dashicons-admin-site');
    //    add_menu_page('Параметры Кабинетов', 'Кабинет', 1,
    //        $ccab_page, 'ccab_page_wrapper','dashicons-shield');
    //    add_action('load-'.$hook, array($this, 'showScreenOptions'));
//        add_action('load-'.$hook, 'showScreenOptions');
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
        <h2>Параметры Магазина</h2>
       <?php
        ob_start();
        
       ?>
        <form method="post" enctype="multipart/form-data" action="options.php" >
            <?php 
            settings_fields($this->page); // меняем под себя только здесь
//            settings_fields('ccab_options'); // меняем под себя только здесь
            // (название настроек)
            do_settings_sections($this->page);
    //        do_settings_sections('shortcodes_'.$ccab_page);
    //        do_settings_sections('shortcodes_'.$ccab_page);
    //        echo 'show shortcodes';
//            ccab_show_sortcodes();
	submit_button();
            ?>
        </form>
       <?php
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
    /*  ==========  */

    /**
     * регистрируем страницы подменю в разделе "кабинет"
     * @global string $ccab_page
     */
    public function options() {
        $page = $this->page;
        $r_page = $this->rpage;
    //    $ parent slug
    //    $ page title
    //    $ menu title
    //    $ capability
    //    $ menu slug
    //    $ function
        
        
//		add_submenu_page(
//			'edit.php?post_type=shop-page-wp', // string $parent_slug
//			'Instructions', // string $page_title,
//			'Instructions', // string $menu_title,
//			'manage_options', // string $capability
//			'shop-page-wp-instructions', // string $menu_slug
//			array( 'Shop_Page_WP_Instructions', 'output_admin_page' )
//		);
        
//        add_submenu_page( $page, 'Shortcodes', 'Shortcodes', 'manage_options',
//            ''.$r_page.'/shortcodes.php', 'ccab_page_shortcodes_wrapper');  
//        add_submenu_page( $page, 'Параметры', 'Параметры', 'manage_options',
//            ''.$r_page.'/settings.php', 'ccab_page_settings_wrapper');  
        
    //    add_submenu_page( $ccab_page, 'Параметры 3', 'Параметры 3', 'manage_options',
    //        'p3_'.$ccab_page.'', 'true_option_page2');

    //        add_submenu_page($parent_slug, $page_title, $menu_title,
    //                $capability, $menu_slug, $function);

    }
}
