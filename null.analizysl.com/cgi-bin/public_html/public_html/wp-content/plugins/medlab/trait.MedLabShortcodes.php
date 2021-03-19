<?php

/* 
 * ccab_shortcodes
 */

trait MedLabShortcodes {
    
    /**
     * инициализация шоткодов
     */
    public function _init_shortcodes(){
        self::init();
    }
    public function init_shortcodes(){
        add_shortcode('__list_price__',['MedLab', 'shortcode']);
        add_shortcode('__list_news__',['MedLab', 'shortcode']);
        add_shortcode('__list_bad_owners__',['MedLab', 'shortcode']);
        add_shortcode('__form_bad_owner__',['MedLab', 'shortcode']);
        add_shortcode('__list_bad_agents__',['MedLab', 'shortcode']);
        add_shortcode('__form_bad_agent__',['MedLab', 'shortcode']);
        add_shortcode('__list_users__',['MedLab', 'shortcode']);
        add_shortcode('__list_docs__',['MedLab', 'shortcode']);
        add_shortcode('__form_doc__',['MedLab', 'shortcode']);
        add_shortcode('__cab_content__',['MedLab', 'shortcode']);
        add_shortcode('__form_entrance__',['MedLab', 'shortcode']);
        add_shortcode('__form_login__',['MedLab', 'shortcode']);
        add_shortcode('__form_register__',['MedLab', 'shortcode']);
        add_shortcode('__users_logo__',['MedLab', 'shortcode']);
        add_shortcode('ml_page',['MedLab', 'shortcode']);
        add_shortcode('ml_component',['MedLab', 'shortcode']);
        add_shortcode('medlab',['MedLab', 'shortcode']);
    }

    public static function shortcode($atts,$content,$tag){
        self::init();
        $out='';
        switch($tag){
            case'__list_price__':$out=self::$instance->shr__list_price__($atts,$content);break;
            case'__list_news__':$out=self::$instance->shr__list_news__($atts,$content);break;
            case'__list_bad_owners__':$out=self::$instance->shr__list_bad_owners__($atts,$content);break;
            case'__form_bad_owner__':$out=self::$instance->shr__form_bad_owner__($atts,$content);break;
            case'__list_bad_agents__':$out=self::$instance->shr__list_bad_agents__($atts,$content);break;
            case'__form_bad_agent__':$out=self::$instance->shr__form_bad_agent__($atts,$content);break;
            case'__list_users__':$out=self::$instance->shr__list_users__($atts,$content);break;
            case'__list_docs__':$out=self::$instance->shr__list_docs__($atts,$content);break;
            case'__form_doc__':$out=self::$instance->shr__form_doc__($atts,$content);break;
            case'__cab_content__':$out=self::$instance->shr__cab_content($atts,$content);break;
            case'__form_entrance__':$out=self::$instance->shr__form_entrance__($atts,$content);break;
            case'__form_login__':$out=self::$instance->shr__form_login__($atts,$content);break;
            case'__form_register__':$out=self::$instance->shr__form_register__($atts,$content);break;
            case'__users_logo__':$out=self::$instance->shr__users_logo__($atts,$content);break;
            case'ml_page':$out=self::$instance->shr__ml_page($atts,$content);break;
            case'ml_component':$out=self::$instance->shr__ml_component($atts,$content);break;
            case'medlab':$out=self::$instance->shr__medlab($atts,$content);break;
            
//        case'allegro_shop_catalog':$out=self::$instance->ccab_shc_page($atts,$content);break;
//            case'allegro_shop_product':$out=self::$instance->ccab_shc_page($atts,$content);break;
//            case'allegro_shop_products_rand':$out=self::$instance->shr_wgt_products_rand($atts,$content);break;
//            case'ccab_page':$out=$this->ccab_shc_page($atts,$content);break;
        }
        return $out;
    }
    // 
    public function shr__medlab($atts,$content){
        $out = '';
        $atts = shortcode_atts( array( 'page' => 'page','type' => 'item', ), $atts );
        ob_start();
//        if(!is_user_logged_in())
        get_template_part( 'template-parts/medlab/'.$atts['page'], $atts['type'] );
        $out.=ob_get_clean();
        $out = do_shortcode( $out );
//        $out=  '__log__'.$out;
        return $out;
    }
    // 
    public function shr__ml_component($atts,$content){
        $out = '';
        $atts = shortcode_atts( array( 'tpl' => 'page','type' => 'item', ), $atts );
        ob_start();
//        if(!is_user_logged_in())
        get_template_part( 'template-parts/component/'.$atts['tpl'], $atts['type'] );
        $out.=ob_get_clean();
        $out = do_shortcode( $out );
//        $out=  '__log__'.$out;
        return $out;
    }
    // 
    public function shr__ml_page($atts,$content){
        $out = '';
        $atts = shortcode_atts( array( 'tpl' => 'page','type' => 'item', ), $atts );
        ob_start();
//        if(!is_user_logged_in())
        get_template_part( 'template-parts/page/'.$atts['tpl'], $atts['type'] );
        $out.=ob_get_clean();
        $out = do_shortcode( $out );
//        $out=  '__log__'.$out;
        return $out;
    }
    
    // list-news
    public function shr__list_price__($atts,$content){
        $out = '';
//        $out = 'test p rand';
        ob_start();
            get_template_part( 'template-parts/component/list', 'price' );
        $out.=ob_get_clean();
        $out = do_shortcode( $out );
        return $out;
    }
    
    public function shr__users_logo__($atts,$content){
        $out = '';
//        $out = 'test p rand';
        ob_start();
//        if(!is_user_logged_in())
            get_template_part( 'template-parts/component/list-users', 'logo' );
        $out.=ob_get_clean();
        $out = do_shortcode( $out );
//        $out=  '__log__'.$out;
        return $out;
    }
    // list-news
    public function shr__form_register__($atts,$content){
        $out = '';
//        $out = 'test p rand';
        ob_start();
        if(!is_user_logged_in())
            get_template_part( 'template-parts/component/form', 'register' );
        $out.=ob_get_clean();
        $out = do_shortcode( $out );
//        $out=  '__log__'.$out;
        return $out;
    }
    // list-news
    public function shr__form_login__($atts,$content){
        $out = '';
//        $out = 'test p rand';
        ob_start();
        if(!is_user_logged_in())
            get_template_part( 'template-parts/component/form', 'login' );
        $out.=ob_get_clean();
        $out = do_shortcode( $out );
//        $out=  '__log__'.$out;
        return $out;
    }
    // list-news
    public function shr__form_entrance__($atts,$content){
        $out = '';
//        $out = 'test p rand';
        ob_start();
            get_template_part( 'template-parts/component/form', 'entrance' );
        $out.=ob_get_clean();
        $out = do_shortcode( $out );
//        $out=  '__log__'.$out;
        return $out;
    }
    // list-news
    public function shr__list_news__($atts,$content){
        $out = '';
//        $out = 'test p rand';
        ob_start();
            get_template_part( 'template-parts/component/list', 'news' );
        $out.=ob_get_clean();
        $out = do_shortcode( $out );
        return $out;
    }
    // list-bad_owners
    public function shr__list_bad_owners__($atts,$content){
        $out = '';
//        $out = 'test p rand';
        ob_start();
            get_template_part( 'template-parts/component/list', 'bad_owners' );
        $out.=ob_get_clean();
        $out = do_shortcode( $out );
        return $out;
    }
    // form-bad_owner
    public function shr__form_bad_owner__($atts,$content){
        $out = '';
//        $out = 'test p rand';
        ob_start();
            get_template_part( 'template-parts/component/form', 'bad_owner' );
        $out.=ob_get_clean();
        $out = do_shortcode( $out );
        return $out;
    }
    // list-bad_agents
    public function shr__list_bad_agents__($atts,$content){
        $out = '';
//        $out = 'test p rand';
        ob_start();
            get_template_part( 'template-parts/component/list', 'bad_agents' );
        $out.=ob_get_clean();
        $out = do_shortcode( $out );
        return $out;
    }
    // form-bad_agent
    public function shr__form_bad_agent__($atts,$content){
        $out = '';
//        $out = 'test p rand';
        ob_start();
            get_template_part( 'template-parts/component/form', 'bad_agent' );
        $out.=ob_get_clean();
        $out = do_shortcode( $out );
        return $out;
    }
    // list-users
    public function shr__list_users__($atts,$content){
        $out = '';
//        $out = 'test p rand';
        ob_start();
            get_template_part( 'template-parts/component/list', 'users' );
        $out.=ob_get_clean();
        $out = do_shortcode( $out );
        return $out;
    }
    // list-docs
    public function shr__list_docs__($atts,$content){
        $out = '';
//        $out = 'test p rand';
        ob_start();
            get_template_part( 'template-parts/component/list', 'docs' );
        $out.=ob_get_clean();
        $out = do_shortcode( $out );
        return $out;
    }
    // form-doc
    public function shr__form_doc__($atts,$content){
        $out = '';
//        $out = 'test p rand';
        ob_start();
            get_template_part( 'template-parts/component/form', 'doc' );
        $out.=ob_get_clean();
        $out = do_shortcode( $out );
        return $out;
    }
    // content-cabinet
    public function shr__cab_content($atts,$content){
        $out = '';
//        $out = 'test p rand';
        ob_start();
        if( is_user_role( 'administrator' )
            || is_user_role( 'contributor' ) ){
            get_template_part( 'template-parts/component/content', 'cabinet' );
        }
        $out.=ob_get_clean();
        $out = do_shortcode( $out );
        return $out;
    }
    
    public function shr_wgt_products_rand($atts,$content){
        $out = '';
//        $out = 'test p rand';
        ob_start();
//        $this->collectCategs(5);
        $out.=ob_get_clean();
        return $out;
    }
    /**
     * [ccab_page page="order_edit"]
     * @param type $atts
     * @param type $content
     * @return string
     */
    public function ccab_shc_page($atts,$content){
        $page='';
        $out='tt';
        $only_auth=0;
        extract( shortcode_atts( array(
                'page' => 'list',
                'bar' => 'что-то ещё',
                'only_auth' => '0',
        ), $atts ) );
        if($only_auth==1 && !is_user_logged_in())
            return $out=__('Нет доступа.');
//        $out = $this->ccab_tpl_order_container($page,$content);
        $out = self::$instance->content($content);
        return $out;
    }
    public function ccab_show_sortcodes(){
        $pages = $this->ccab_get_pages_list();
        $out='';
        foreach ($pages as $key => $page) {
            $rep = array();
            $rep['__title__']=$page['title'];
            $rep['__item__']=$page['item'];
            $rep['__desc__']=$page['desc'];
            $out.=ccab_build_tpl('shc_item',$rep,0,$prefix='','admin');
//            $out.=ccab_build_tpl('shc_item',$rep,0,$prefix='','admin');
        }

        $rep = array('__content__'=>$out);
        ccab_build_tpl('shc_container',$rep,$out=true,$prefix='','admin');
//        ccab_build_tpl('shc_container',$rep,$out=true,$prefix='','admin');
    }
    /**
     * ccab_tpl_order_container
     */
    public function ccab_tpl_order_container($page='',$content=''){
        ob_start();
        _ccab_tpl_order_container($page,$content);
        $out=ob_get_clean();
        showLogInfo('public');
        return $out;
    }
    public function ccab_get_pages_list(){
    $pages=array();
    
    $page=array();
    $page['title']='Форма добавления вакансий';
    $page['desc']='Выводит форму добавления вакансий';
    $page['item']='[ccab_page page="vacancy_create"]';
    $pages['vacancy_create']=$page;
    
    return $pages;
    }
}

