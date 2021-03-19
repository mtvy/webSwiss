<?php

/* 
 * class.AC.php
 * Access Control
 */

class AC{
    public $user = null;
    public $ac=[];
    public function init(){
        $this->user = wp_get_current_user();
        $this->initDB();
        $this->initCheck();
//                add_log(is_page(),'dump');
//        add_log($this->ac);
        add_filter('the_content', [$this,'checkPageAccess'], $priority=11, $accepted_args=1);
        
//        global $ht, $aca;
//        $roles = get_option('wp_user_roles',[]);
//        $aca->n($ht->pre($roles));
    }
    public function checkPageAccess($content=''){
//                add_log(is_page(),'dump');
//        if(is_singular()){
        if(is_page()){
            $p = get_post();
//            $user = wp_get_current_user();
//            $uid = $user->ID;
            if($p->ID == 220){
//                add_log('220');
//                add_log($user);
            }
            
            $access = $this->check($p->ID);
            
            
            
//            $r_access = [];
//            $r_access [] ='administrator';
//            $r_access [] ='ml_administrator';
//            $r_access [] ='ml_manager';
//            $r_access [] ='ml_doctor';
//            $r_access [] ='ml_procedurecab';
//            if(count( array_intersect($r_access, (array) $user->roles ) ) == 0 ){
//            add_log($access);
//            add_log(!$access,'dump');
            if(!$access){
                ob_start ();
                get_template_part( 'template-parts/ac/tpl.page-access', 'denied' );
//                get_template_part( 'template-parts/page/tpl.page-access', 'denied' );
            //    get_template_part( 'template-parts/page/tpl.page-access', 'notfound' );
                return ob_get_clean();
            }
        }
        return $content;
    }
    public function check($pid){
        $access = true;
        
//        $wroles = [];
//        if($this->user->roles){
//            foreach($this->user->roles as $role){
//                $access = $access || $this->_checkAccess($pid, $role);
//            }
//        }
//        else
//        {
//            $access = $access || $this->_checkAccess($pid, 'guest');
//        }
        if(isset($this->ac[$pid]))
                $access = $access && $this->ac[$pid];
        return $access;
    }
    public function checkAccess($pid=0,$role=''){
    }
    public function initCheck(){
        global $wpdb;
        $wroles = [];
        if($this->user->roles){
            foreach($this->user->roles as $role){
                $wroles[] = "r.role = '$role'";
            }
        }
        else
        {
            $wroles[] = "r.role = 'guest'";
        }
        $wroles = implode(' or ',$wroles);
        $table_roles = $wpdb->prefix . "ac_roles";
        $table_access = $wpdb->prefix . "ac_ac";
        $q = "select a.page_id as p, a.access as a from  $table_access a join $table_roles r on r.role_id  = a.role_id where  ".$wroles;
        
        $ac_ = $wpdb->get_results($q,ARRAY_A );
        $ac = [];
        foreach($ac_ as $a){
            $ac[$a['p']] = $a['a'];
        }
        $this->ac = $ac;
    }
    public function _checkAccess($pid=0,$role=''){
        global $wpdb;
        
        $table_roles = $wpdb->prefix . "ac_roles";
        $table_access = $wpdb->prefix . "ac_ac";
        $q = "select a.access from  $table_access a join $table_roles r on r.role_id  = a.role_id 
            where a.page_id = $pid and ( r.role = '$role' ) ";
//        add_log($q);
        $acid = $wpdb->get_var($q);
//        add_log($acid);
        if(!$acid)$acid=0;
//        add_log($acid);
//        add_log($acid == 1);
        return $acid == 1;
    }
    public function initDB(){
        global $wpdb;

        $table_roles = $wpdb->prefix . "ac_roles";
        if($wpdb->get_var("SHOW TABLES LIKE '$table_roles'") != $table_roles) {
         // тут мы добавляем таблицу в базу данных
            $sql = "CREATE TABLE " . $table_roles . " (
                 id mediumint(9) NOT NULL AUTO_INCREMENT,
                 role_id int(11) DEFAULT '0' NOT NULL,
                 role VARCHAR(32) NOT NULL,
                 UNIQUE KEY id (id),
                 UNIQUE KEY role_id (role_id),
                 UNIQUE KEY role (role)
               )
               ENGINE=InnoDB AUTO_INCREMENT=0;";

           require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
           dbDelta($sql);

            $num=0;
            $acid = $wpdb->get_var("select id from  $table_roles where role = 'guest' ");
            if($acid === null){
                $q = "insert into $table_roles set role_id = $num, role = 'guest' ";
                $wpdb->query($q);
            }
            $num++;

            require_once ABSPATH . 'wp-admin/includes/user.php';
            $roles = get_editable_roles();
    //        $this->n($this->pre($roles));

            foreach ($roles as $rn => $rl) {
    //            $hitems[]=$rl['name'];
                $acid = $wpdb->get_var("select id from  $table_roles where role = '$rn' ");
                if($acid === null){
                    $q = "insert into $table_roles set role_id = $num, role = '$rn' ";
                    $wpdb->query($q);
                }
                $num++;
            }
         }

        $table_access = $wpdb->prefix . "ac_ac";
        if($wpdb->get_var("SHOW TABLES LIKE '$table_access'") != $table_access) {
         // тут мы добавляем таблицу в базу данных
            $sql = "CREATE TABLE " . $table_access . " (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                page_id int(11) DEFAULT '0' NOT NULL,
                role_id int(11) DEFAULT '0' NOT NULL,
                access int(11) DEFAULT '0' NOT NULL,
                UNIQUE KEY id (id)
              )
              ENGINE=InnoDB;";

          require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
          dbDelta($sql);
        }

        $table_access_reserve = $wpdb->prefix . "ac_acr";
        if($wpdb->get_var("SHOW TABLES LIKE '$table_access_reserve'") != $table_access_reserve) {
         // тут мы добавляем таблицу в базу данных
            $sql = "CREATE TABLE " . $table_access_reserve . " (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                page_id int(11) DEFAULT '0' NOT NULL,
                role_id int(11) DEFAULT '0' NOT NULL,
                access int(11) DEFAULT '0' NOT NULL,
                UNIQUE KEY id (id)
              )
              ENGINE=InnoDB;";

          require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
          dbDelta($sql);
          $q = "insert into $table_access_reserve select * from $table_access ";
          $wpdb->query($q);
        }

    }
}