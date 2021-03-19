<?php

/* 
 * class.AM.php
 */
include_once 'class.AMWalker.php';

class AM{
    public $ac = [];
    public function init(){
        
//        $this->test();
//        add_filter('the_content', [$this,'test'], $priority=11, $accepted_args=1);
//        if(1  && current_user_can('manage_options')){
            add_filter('widget_nav_menu_args', [$this,'alterMenuArgs'], $priority=11, $accepted_args=4);
//        }
    }
    public function alterMenuArgs($nav_menu_args, $nav_menu, $args, $instance){
        $nav_menu_args['walker']=new AMWalker();
        return $nav_menu_args;
    }
    public function test($c){
        
//                add_log('220 1');
//            $p = get_post();
//            $user = wp_get_current_user();
//            $uid = $user->ID;
//            add_log('220 2');
//                add_log($p->ID);
        if(is_page()){
            $p = get_post();
            $user = wp_get_current_user();
            $uid = $user->ID;
//            add_log('220 2');
            if($p->ID == 220  && current_user_can('manage_options')){
                global $ac;
                add_log($ac->ac);
//                add_log('220');
//                add_log($user);
//                global $wp_registered_sidebars, $wp_registered_widgets;
//                $sidebars_widgets = wp_get_sidebars_widgets();
//                $index ='nav_menu-7';
////                $sidebar = $wp_registered_sidebars[ $index ];
//                add_log($sidebars_widgets);
//                add_log($wp_registered_sidebars);
//                add_log($wp_registered_widgets);
            }
        }
        return $c;
    }
}