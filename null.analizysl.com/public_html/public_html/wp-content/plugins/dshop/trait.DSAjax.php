<?php

/* 
 * trait.DSAjax
 * ccab_shortcodes
 */

trait DSAjax {

    public static function ajax(){
        self::init();
        $out=self::$instance->_ajax();
        wp_send_json( $out);
        die; // даём понять, что обработчик закончил выполнение
    }
    public function _ajax(){
        $act = filter_input(INPUT_POST, 'act', FILTER_SANITIZE_STRING);
        if($act===false || $act===null|| $act==='')$act=0;
        $res = [];
        switch($act){
            case 'get':
                $res = $this->_ajax_get();
                break;
            case 'getcart':
                $res = $this->get_a_count_in_cart();
                break;
            case 'add_c':
                $res = $this->_a_add_to_cart();
                break;
            case 'rem_c':
                $res = $this->_a_remove_from_cart();
                break;
            case 'order_ch_mail_ex':
                $res = $this->_a_order_ch_mail_ex();
                break;
            
        }
        return $res;
    }
//    public function get_a_count_in_cart(){
//        $res = [];
//        return $res;
//    }
//    public function get_a_count_in_cart(){
//        $res = [];
//        return $res;
//    }
//    public function get_a_count_in_cart(){
//        $res = [];
//        return $res;
//    }
    public function _ajax_get(){
        
        $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
        if($type===false || $type===null|| $type==='')$type=0;
        $offset = filter_input(INPUT_POST, 'offset', FILTER_SANITIZE_NUMBER_INT);
        if($offset===false || $offset===null || $offset==='')$offset=0;
        $isajax = filter_input(INPUT_POST, 'isajax', FILTER_SANITIZE_NUMBER_INT);
        if($isajax===false || $isajax===null || $isajax==='')$isajax=0;
        $count = filter_input(INPUT_POST, 'count', FILTER_SANITIZE_NUMBER_INT);
        if($count===false || $count===null || $count==='')$count=1;
        if ( wp_doing_ajax())$isajax=1;
        
//                    $out.=$type;
        $res = [];
//        $res['sp']=STYLESHEETPATH;
        $res['limit_end']=0;
        $posts_count=0;
        $out = '';
        switch($type){
            case 'doc':
                    ob_start();
                        get_template_part( 'template-parts/component/list', 'docs' );
                    $out.=ob_get_clean();
                    $posts_count = wp_count_posts('doc')->publish;
                    $out = do_shortcode( $out );
                break;
            case 'owner':
                    ob_start();
                        get_template_part( 'template-parts/component/list', 'bad_owners' );
                    $out.=ob_get_clean();
                    $posts_count = wp_count_posts('bad_owner')->publish;
                    $out = do_shortcode( $out );
                break;
            case 'agent':
                    ob_start();
                        get_template_part( 'template-parts/component/list', 'bad_agents' );
                    $out.=ob_get_clean();
                    $posts_count = wp_count_posts('bad_agent')->publish;
                    $out = do_shortcode( $out );
                break;
            case 'user':
                    ob_start();
                        get_template_part( 'template-parts/component/list', 'users' );
                    $out.=ob_get_clean();
                    $posts_count = count_users();
                    $posts_count = $posts_count['total_users'];
                    $out = do_shortcode( $out );
                break;
        }
        $res['posts_count']=$posts_count;
        $res['offset']=$offset;
        $res['count']=$count;
//        $res['limit']=($offset*$count)+$count;
//        if($posts_count <= ($offset*$count)+$count)
//            $res['limit_end']=1;
        $res['limit'] = $offset + $count;
        if( $posts_count <= $offset + $count )
            $res['limit_end']=1;
        $res['data']=$out;
        return $res;
//    $pages=array();
//    
//    $page=array();
//    $page['title']='Форма добавления вакансий';
//    $page['desc']='Выводит форму добавления вакансий';
//    $page['item']='[ccab_page page="vacancy_create"]';
//    $pages['vacancy_create']=$page;
//    echo "['Allegro', 'ajax']";
	die; // даём понять, что обработчик закончил выполнение
    
//    return $pages;
    }
}

