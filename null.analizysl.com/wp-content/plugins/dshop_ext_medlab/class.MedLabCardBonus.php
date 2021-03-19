<?php

/* 
 * class.MedLabCardBonus.php
 */

class MedLabCardBonus{
    public $cards = [];
    public $payds = [];
    public $uid = 0;
    public $card = 0;
    public $payd = 0;
    public function __construct() {
        $this->init();
    }
    
    public function init(){
    }
    
    public function discont($uid = 0,$use_discont = true){
        $d = $this->_discont($uid,$use_discont);
        if($d > 100)$d = 100;
        return $d;
    }
    
    public function _discont($uid = 0,$use_discont = true){
        $card_discont = 0;
        if($use_discont)
        $card_discont = DShop::_get_cart_discont('laborant');
        if($card_discont > 0) return $card_discont;
//        if($discont) $total -= ($total*$discont)/100;
        $lognum=-10;
        if($lognum>-1)add_log($lognum++);
        if( !is_user_logged_in() ){
            return 0;
        }
        if($lognum>-1)add_log($lognum++);
        if(!$uid)return 0+$card_discont;
//        $user = wp_get_current_user();
        $user = new WP_User($uid);
        if($lognum>-1)add_log($lognum++);
        if(!$user->ID)return 0+$card_discont;
        $this->initCards();
        $this->initDisconts();
        $card_numer = (int) get_user_meta($uid, 'card_numer',1);
        $card_numer =  get_user_meta($uid, 'card_numer',1);
        if($lognum>-1)add_log($lognum++);
        if(!$card_numer)return 0+$card_discont;
        $this->uid = $uid;
        $this->card = $card_numer;
        if(array_key_exists($card_numer, $this->cards)) return $this->cards[$card_numer]+$card_discont;
        
        $cards = [];
        for($i = 1000;$i<=10600;$i++){
            $cards[(int)$i] = 3;
        }
        if(!array_key_exists((int)$card_numer, $cards)) return 0+$card_discont;
        
        $user_id = $user->ID;
        global $wpdb;
        $join = [];
        $where=[];
        
        $where[]=" pm.meta_key = 'dso_puid'";
//        $where[]=" pm.meta_value = '$user_id'";
        $where[]=" pm.meta_value in( select `user_id` from $wpdb->usermeta um where um.meta_key = 'card_numer' and um.meta_value = '$card_numer')";
        
        $join=implode("\n    ",$join);
        $where=implode("\n and ",$where);
        if($where) $where = 'and '.$where;
        $order = '';
        $q = "SELECT sum(pc.meta_value) FROM $wpdb->postmeta pm
            LEFT join $wpdb->postmeta pc on pc.post_id = pm.post_id
            LEFT join $wpdb->postmeta ps on ps.post_id = pm.post_id
            $join
            WHERE 1
            and pc.meta_key = 'dso_cost'
            and ps.meta_key = 'dso_status'
            and ( ps.meta_value = 'payd' or ps.meta_value = 'query_sent' )
            $where
            /*order by $order*/
                ";
        $dso_sum = $wpdb->get_var($q);
        $this->payd = $dso_sum;

        if($lognum>-1)add_log($lognum++);
        foreach($this->payds as $d){
            if($d['min'] == $d['max'] && $dso_sum > $d['max'])return $d['discont']+$card_discont;
            if($d['min'] <= $dso_sum && $dso_sum <= $d['max'])return $d['discont']+$card_discont;
        }
        if($dso_sum>0){
            $max = 0;
        }
        if($lognum>-1)add_log($lognum++);
        return  3+$card_discont;
    }
    
    public function initCards(){
        $cards = [];
        $cards['111111111'] = 15;
        $cards['222222222'] = 15;
        $cards['333333333'] = 15;
        $cards['444444444'] = 15;
        $cards['555555555'] = 15;
        $cards['666666666'] = 15;
        $cards['777777777'] = 15;
        $cards['888888888'] = 15;
        $cards['999999999'] = 15;
//        $cards[''] = 15;
        for($i = 1;$i<=101;$i++){
            $cards[$i] = 15;
            $cards[sprintf("%09d",$i)] = 15;
        }
        
        $this->cards = $cards;
        return $cards;
    }
    
    public function initDisconts(){
        $cards = [];
        $cards[] = ['min'=>0,'max'=>3000000,'discont'=>3];
        $cards[] = ['min'=>3000000,'max'=>7000000,'discont'=>5];
        $cards[] = ['min'=>7000001,'max'=>25000000,'discont'=>10];
        $cards[] = ['min'=>25000001,'max'=>25000001,'discont'=>15];
        ksort($cards);
        $this->payds = $cards;
        return $cards;
    }
}
