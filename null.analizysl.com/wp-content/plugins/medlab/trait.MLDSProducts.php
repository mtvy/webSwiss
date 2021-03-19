<?php

/* 
 * trait.MLDSProducts.php
 * MedLab DShop Proucts
 */

trait MLDSProducts {
    public function updateProducts(){
        global $wpdb;
        $medLab = MedLab::_instance();

        $groups = $medLab->groups;
        $analyses = $medLab->analyses;
        $panels = $medLab->panels;
        $price = $medLab->price;
        
        $q= "select p.meta_value as 'id', p.post_id, c.meta_value as 'cost' from {$wpdb->postmeta} p join {$wpdb->postmeta} c on c.post_id = p.post_id  where p.meta_key = 'dsp_pid' and c.meta_key = 'dsp_cost' ";
        $posts = $wpdb->get_results($q,OBJECT_K);
        
        $cou =  0;
        $t = microtime(1);
        foreach($posts as $k=>$v){
//            add_log($v);
            if(isset($analyses[$k]) && $v->cost != $price[$k]['Price']){
                update_post_meta( $v->post_id, 'dsp_cost', $price[$k]['Price'] );
                $cou++;
//                add_log($v->cost == $price[$k]['Price']);
//                add_log($v->cost != $price[$k]['Price']?'!=':'==');
//                add_log($v->cost,'dump');
//                add_log($price[$k]['Price'],'dump');
//                add_log("'{$v->cost}' != '{$price[$k]['Price']}'");
//                add_log($analyses[$k]['Name']);
            }
            if(isset($panels[$k]) && $v->cost != $price[$k]['Price']){
                update_post_meta( $v->post_id, 'dsp_cost', $price[$k]['Price'] );
                $cou++;
            }
        }
        
//        add_log($posts);
//        add_log($analyses[225712]);
//        add_log($price[225712]);
//        add_log($price);
        add_log('Товаров всего: '.count($posts));
        add_log('Товаров обновлено: '.$cou);
        $s = microtime(1)-$t;
//        add_log('Затрачено секунд: '.$s);
    }
}