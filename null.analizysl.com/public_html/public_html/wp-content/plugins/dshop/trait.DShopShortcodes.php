<?php

/* 
 * trait.DShopShortcodes
 */

trait DShopShortcodes {

    public static function shortcode($atts,$content,$tag){
        self::init();
        $out='';
        switch($tag){
            case'allegro_shop_catalog':$out=self::$instance->ccab_shc_page($atts,$content);break;
            case'allegro_shop_product':$out=self::$instance->ccab_shc_page($atts,$content);break;
            case'allegro_shop_products_rand':$out=self::$instance->shr_wgt_products_rand($atts,$content);break;
            case'ccab_page':$out=$this->ccab_shc_page($atts,$content);break;
            
            case'ds_cart':$out=self::$instance->shr__ds_cart($atts,$content);break;
            case'ds_my_accaunt':$out=self::$instance->shr__ds_my_account($atts,$content);break;
            case'ds_checkout':$out=self::$instance->shr__ds_checkout($atts,$content);break;
            case'ds_order':$out=self::$instance->shr__ds_order($atts,$content);break;
            case'ds_payment_success':$out=self::$instance->shr__ds_payment_success($atts,$content);break;
            case'ds_payment_fail':$out=self::$instance->shr__ds_payment_fail($atts,$content);break;
            case'ds_payment_result':$out=self::$instance->shr__ds_payment_result($atts,$content);break;
            case'ds_item':$out=self::$instance->shr__ds_item($atts,$content);break;
            case'ds_page':$out=self::$instance->shr__ds_page($atts,$content);break;
            case'dshop':$out=self::$instance->shr__dshop($atts,$content);break;
            
        }
        return $out;
    }
    // 
    public function shr__dshop($atts,$content){
        $out = '';
        $atts = shortcode_atts( array( 'page' => 'page','type' => 'item', ), $atts );
        ob_start();
//        if(!is_user_logged_in())
        get_template_part( 'template-parts/dshop/'.$atts['page'], $atts['type'] );
        $out.=ob_get_clean();
        $out = do_shortcode( $out );
//        $out=  '__log__'.$out;
        return $out;
    }
    // 
    public function shr__ds_page($atts,$content){
        $out = '';
        $atts = shortcode_atts( array( 'page' => 'page','type' => 'item', ), $atts );
        ob_start();
//        if(!is_user_logged_in())
        get_template_part( 'template-parts/dshop/tpl.dshop-'.$atts['page'], $atts['type'] );
        $out.=ob_get_clean();
        $out = do_shortcode( $out );
//        $out=  '__log__'.$out;
        return $out;
    }
    // 
    public function shr__ds_payment_result($atts,$content){
        $out = '';
//        $out = 'test p rand';
        ob_start();
//        if(!is_user_logged_in())
//        tpl.dshop-payment-success.php
//        tpl.dshop-payment-fail.php
//        tpl.dshop-payment-result.php
        get_template_part( 'template-parts/dshop/tpl.dshop', 'payment-result' );
        $out.=ob_get_clean();
        $out = do_shortcode( $out );
//        $out=  '__log__'.$out;
        return $out;
    }
    // 
    public function shr__ds_payment_success($atts,$content){
        $out = '';
//        $out = 'test p rand';
        ob_start();
//        if(!is_user_logged_in())
//        tpl.dshop-payment-success.php
//        tpl.dshop-payment-fail.php
        get_template_part( 'template-parts/dshop/tpl.dshop', 'payment-success' );
        $out.=ob_get_clean();
        $out = do_shortcode( $out );
//        $out=  '__log__'.$out;
        return $out;
    }
    // 
    public function shr__ds_payment_fail($atts,$content){
        $out = '';
//        $out = 'test p rand';
        ob_start();
//        if(!is_user_logged_in())
        get_template_part( 'template-parts/dshop/tpl.dshop', 'payment-fail' );
        $out.=ob_get_clean();
        $out = do_shortcode( $out );
//        $out=  '__log__'.$out;
        return $out;
    }
    // 
    public function shr__ds_order($atts,$content){
        $out = '';
//        $out = 'test p rand';
        ob_start();
//        if(!is_user_logged_in())
        get_template_part( 'template-parts/dshop/tpl.dshop', 'order' );
        $out.=ob_get_clean();
        $out = do_shortcode( $out );
//        $out=  '__log__'.$out;
        return $out;
    }
    // 
    public function shr__ds_cart($atts,$content){
        $out = '';
//        $out = 'test p rand';
        ob_start();
//        if(!is_user_logged_in())
        get_template_part( 'template-parts/dshop/tpl.dshop-cart', 'cart' );
        $out.=ob_get_clean();
        $out = do_shortcode( $out );
//        $out=  '__log__'.$out;
        return $out;
    }
    // 
    public function shr__ds_item($atts,$content){
        $out = '';
        $atts = shortcode_atts( array( 'type' => 'item', ), $atts );
        ob_start();
//        if(!is_user_logged_in())
        get_template_part( 'template-parts/dshop/tpl.dshop-item', $atts['type'] );
        $out.=ob_get_clean();
        $out = do_shortcode( $out );
//        $out=  '__log__'.$out;
        return $out;
    }
    // 
    public function shr__ds_my_account($atts,$content){
        $atts = shortcode_atts( array(
                'type' => 'account',
                'page' => 'list',
                'bar' => 'что-то ещё',
                'only_auth' => '0',
        ), $atts );
        $out = '';
//        $out = 'test p rand';
        ob_start();
//        if(!is_user_logged_in())
//        get_template_part( 'template-parts/page/tpl.dshop-account', 'account' );
        get_template_part( 'template-parts/dshop/tpl.dshop-account', $atts['type'] );
        $out.=ob_get_clean();
        $out = do_shortcode( $out );
//        $out=  '__log__'.$out;
        return $out;
    }
    public function shr__ds_my_account_fields($atts,$content){
        $out = '';
        $out =  dshop::_get_account_fields();
        return $out;
    }
    public function shr__ds_my_account_orders($atts,$content){
        $out = '';
        $out =  dshop::_get_account_orders();
        return $out;
    }
    // 
    public function shr__ds_checkout($atts,$content){
        $out = '';
//        $out = 'test p rand';
        ob_start();
//        if(!is_user_logged_in())
        get_template_part( 'template-parts/dshop/tpl.dshop-checkout', 'checkout' );
        $out.=ob_get_clean();
        $out = do_shortcode( $out );
//        $out=  '__log__'.$out;
        return $out;
    }
    
    public function shr_wgt_products_rand($atts,$content){
        $out = '';
//        $out = 'test p rand';
        ob_start(); $this->collectCategs(5);
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

