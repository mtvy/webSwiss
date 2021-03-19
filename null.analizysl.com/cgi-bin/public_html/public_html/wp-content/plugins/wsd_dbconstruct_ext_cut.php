<?php

/* 
 * @package WSD
 * wsd_dbconstruct_ext_cut
 */
/*
Plugin Name: WSD -- DBConstructExtCut
Plugin URI: 
Description: WSD DBConstruct Ext Cuted -- Ограниченная версия планирования схемы базы данных. 
Version: 1.0
Author: wsd
Author URI: wsd
License: GPLv2 or later
Text Domain: wsd
*/


class WSDDBConstructExtCut
{
    public $out = '';
    public $short_code = 'wsd_dbc_list';
    public function __construct() {
        $this->shortcode();
    }
    public function shortcode(){
        global $wpdb,$ac;
        $dbconst_tables = $wpdb->prefix . "wsd_dbconst_tables";
        ob_start();
        $q= "select * from `$dbconst_tables` ";
//        $q .=" where ";
//        $q .="`sch_id`='$schema'";
//        $q .=" and `id`='$table'";
        $tab_data = $wpdb->get_results($q,ARRAY_A);
        foreach($tab_data as $v){

        }
        
        if($wpdb->get_var("SHOW TABLES LIKE '$dbconst_tables'") == $dbconst_tables) {
            $q= "select * from `$dbconst_tables` order by `id`";
            $lists = $wpdb->get_results($q,ARRAY_A);
            foreach($lists as $list){
                $this->short_code = $list['table'];
//                echo ($this->short_code).'<br>';
                add_shortcode('wsd_dbc_'.$this->short_code.'',[$this, 'shortcode_main']);
                
            }
        }
        $err = ob_get_clean();
        if($err)
            echo $err;
////            add_log($err);
//            $this->_notice($err);
//        echo ($this->short_code.' shortcode');
//        add_shortcode('wsd_list_'.$this->short_code.'-list',[$this, 'shortcode_list']);
//        add_shortcode('wsd_list_'.$this->short_code.'-item',[$this, 'shortcode_item']);
//        add_shortcode('wsd_list_'.$this->short_code.'-edit',[$this, 'shortcode_edit']);
//        add_shortcode('wsd_list_'.$this->short_code.'-add',[$this, 'shortcode_add']);
    }
    public function shortcode_main($atts,$content,$tag){
        if(strpos($tag,'wsd_dbc_')===0)$tag = strtr($tag,['wsd_dbc_'=>'']);
//        add_log($tag);
//        add_log($this->shr__dbconst($this->short_code,''));
//        return $this->shr__dbconst($this->short_code,'');
        return $this->shr__dbconst($tag,'');
    }
    public function shr__dbconst($page,$type=''){
        global $wsd_lists_obj;
        $wsd_lists_obj = $this;
        if(strpos($page,'wsd_list_')===0)$page = strtr($page,['wsd_list_'=>'']);
//        echo $page;
        $out = '';
        ob_start();
//        if(!is_user_logged_in())
//        add_log($page);
//        add_log($type);
        get_template_part( 'template-parts/dbconst/'.$page, $type );
        $out.=ob_get_clean();
        $out = do_shortcode( $out );
        return $out;
    }
}
global $wsd_dbconst_ext_cut;
$wsd_dbconst_ext_cut = new WSDDBConstructExtCut();