<?php

/* 
 * class.MLENrGen.php
 */

class MLENrGen{
    public function __construct() {
        add_filter('medlab_num_query', [$this,'medlab_num_query'], $priority=5, $accepted_args=3);
        add_filter('medlab_num_query_get', [$this,'medlab_num_query_get'], $priority=5, $accepted_args=3);
        add_action('medlab_num_query_reset', [$this,'medlab_num_query_reset'], $priority=5, $accepted_args=2);
//        add_action('medlab_num_query_clear', [$this,'medlab_num_query_clear'], $priority=5, $accepted_args=2);
        add_filter('ds_dsorder_settings_extml__tabs', [$this,'ds_dsorder_settings_extml__tabs'], $priority=11, $accepted_args=2);
        add_action('ds_dsorder_settings_extml__add_tab_link', [$this,'ds_dsorder_settings_extml__add_tab_link'], $priority=11, $accepted_args=4);
        add_action('ds_dsorder_settings_extml__do_tab_sections', [$this,'ds_dsorder_settings_extml__do_tab_sections'], $priority=11, $accepted_args=3);
        add_action('ds_dspayment_settings_extml__do_tab_footer_info', [$this,'ds_dspayment_settings_extml__do_tab_footer_info'], $priority=11, $accepted_args=3);
    }
    
    public function init(){
        global $ht, $aca;
//        $roles = get_option('wp_user_roles',[]);
//        $aca->n($ht->pre('$wpdb->dbname'));
        $this->initDB();
    }
    
    public function ds_dsorder_settings_extml__tabs($tabs, $object){
        $tabs['nr_test']='Nr test';
        return $tabs;
    }
    
    public function ds_dsorder_settings_extml__add_tab_link($object,$name,$rpage, $active_tab){
        
    }
    
    public function ds_dsorder_settings_extml__do_tab_sections($object,$page, $active_tab){
        
        if( $active_tab == 'nr_test' ) {
//            echo 'tttttttttttt';
        global $wpdb;
        global $ht, $aca;
            settings_fields($page); // меняем под себя только здесь
            
//                    $aca->n($ht->pre('$qrootAtt'));
//            $pid = 1175;
//            $pid = 1306;
//            $pid = 1307;
//            $autoi = 1174;
////            $autoi = 1306;
//            $pid = 6;
//            $pid = 5;
//            $autoi = $pid;
//            $numgroup = 9950000000;
//            $nr = $autoi + $numgroup;
//            $qpid = 0;
//            $cou = $this->queryExists($nr,$qpid);
//            echo $ht->pre($qpid);
//            $nr = $this->get_number($pid);
//            echo $ht->pre($nr);
//            $this->reset_nr_resv($pid,1);
        } 
    }
    
    public function ds_dspayment_settings_extml__do_tab_footer_info($object,$page, $active_tab){
        
    }
    public function initDB(){
        global $wpdb;
        global $ht, $aca;

        $table_ml_nr = $wpdb->prefix . "ml_nr";
        $table_ml_nr_resv = $wpdb->prefix . "ml_nr_resv";
        $table_postmeta = $wpdb->prefix . "postmeta";
//        $q = "insert into $table_ml_nr
//            select post_id, post_id as 'order_id', meta_value as 'nr'
//            from $table_postmeta
//            where meta_key = 'dso_query_nr' ";
        if($wpdb->get_var("SHOW TABLES LIKE '$table_ml_nr'") != $table_ml_nr) {
         // тут мы добавляем таблицу в базу данных
            $sql = "CREATE TABLE " . $table_ml_nr . " (
                id int(10) NOT NULL AUTO_INCREMENT,
                order_id int(10) DEFAULT '0' NOT NULL,
                nr DECIMAL(11) UNSIGNED DEFAULT '0' NOT NULL,
                UNIQUE KEY id (id),
                UNIQUE KEY order_id (order_id),
                UNIQUE KEY nr (nr)
              )
              ENGINE=InnoDB;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
//            $wpdb->query($q);
        }
        if($wpdb->get_var("SHOW TABLES LIKE '$table_ml_nr_resv'") != $table_ml_nr_resv) {
         // тут мы добавляем таблицу в базу данных
            $sql = "CREATE TABLE " . $table_ml_nr_resv . " (
                id int(10) NOT NULL AUTO_INCREMENT,
                num int(10) DEFAULT '0' NOT NULL,
                order_id int(10) DEFAULT '0' NOT NULL,
                nr DECIMAL(11) UNSIGNED DEFAULT '0' NOT NULL,
                UNIQUE KEY id (id),
                UNIQUE KEY num (num),
                UNIQUE KEY order_id (order_id),
                UNIQUE KEY nr (nr)
              )
              ENGINE=InnoDB;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
//        $aca->n($ht->pre($q));
            
//        $roles = get_option('wp_user_roles',[]);
//        $aca->n($ht->pre($wpdb->dbname));
//        $aca->n($ht->pre($q));
//        $aca->n($ht->pre($wpdb->get_var($q)));
        

    }
    public function medlab_num_query_reset ( $orderId, $error ){
        $this->reset_nr_resv($orderId, !$error);
    }
//    public function medlab_num_query_clear ( $orderId, $error ){
//        $this->reset_nr_resv($orderId, !$error);
//        $this->reset_nr_resv_clear($orderId, !$error);
//    }
    public function medlab_num_query_get ( $num, $orderId, $numgroup ){
        global $wpdb;
        global $ht, $aca;
        $table_ml_nr = $wpdb->prefix . "ml_nr";
        $table_ml_nr_resv = $wpdb->prefix . "ml_nr_resv";
        $table_postmeta = $wpdb->prefix . "postmeta";
        
        // свободен ?
        $q = "SELECT nr from $table_ml_nr where order_id = $orderId";
        $nr = $wpdb->get_var($q);
        if($nr !== null){
            return $nr;
//            continue;
        }

        // использовался ?
        $q = "select meta_value  from $table_postmeta where meta_key = 'dso_query_nr' and post_id = '$orderId'";
        $nr = $wpdb->get_var($q);
        if($nr !== null){
            return $nr;
//            continue;
        }
        return $num;
    }
    public function medlab_num_query ( $num, $orderId, $numgroup ){
        $num = $this->get_number($orderId);
        return $num;
    }
    public function reset_nr_resv($pid,$save = false){
        global $wpdb;
        global $ht, $aca;
        
        $table_ml_nr = $wpdb->prefix . "ml_nr";
        $table_ml_nr_resv = $wpdb->prefix . "ml_nr_resv";
//        $table_postmeta = $wpdb->prefix . "postmeta";
        if($save){
            $q = "SELECT count(num) from $table_ml_nr_resv where order_id = '$pid' ";
            $cou = $wpdb->get_var($q);
            if($cou > 0){
                $q = "insert into $table_ml_nr
                    select num, order_id , nr
                    from $table_ml_nr_resv
                    where order_id = '$pid' ";
                $wpdb->query($q);
            }
        }

        $q = "delete from $table_ml_nr_resv
            where order_id = '$pid' ";
        $wpdb->query($q);
    }
    public function get_number($pid){
        global $wpdb;
        global $ht, $aca;
        $numgroup = 9950000000;
        $limit  = 100;
        /*
         * get last autoincrement
         * check existed numer
         * check reserved numer
         * if reserved increment it
         * check reserved number
         * if free reserving
         * check existing query
         * if exist increment number and repeat check from reserv to exists query
         * if free use for creating new query
         * save new number 
         * cleare reserved numbers
         */
        $table_ml_nr = $wpdb->prefix . "ml_nr";
        $table_ml_nr_resv = $wpdb->prefix . "ml_nr_resv";
        $table_postmeta = $wpdb->prefix . "postmeta";
        
        // свободен ?
        $q = "SELECT nr from $table_ml_nr where order_id = $pid";
        $nr = $wpdb->get_var($q);
        if($nr !== null){
            return $nr;
//            continue;
        }

        // использовался ?
        $q = "select meta_value  from $table_postmeta where meta_key = 'dso_query_nr' and post_id = '$pid'";
        $nr = $wpdb->get_var($q);
        if($nr !== null){
//            $q = "SELECT AUTO_INCREMENT
//    FROM information_schema.TABLES
//    WHERE TABLE_SCHEMA = '$wpdb->dbname'
//    AND TABLE_NAME = '$table_ml_nr' ";
//            $autoi = $wpdb->get_var($q);
//            // резервируем
//            $q = "insert into $table_ml_nr_resv set num = $autoi, order_id = $pid, nr = $nr";
//            $wpdb->query($q);
            return $nr;
//            continue;
        }
        
        $nr = 0;
        $autoi = 0;
        $autoi_inner = 0;
        
        while($limit > 0 && $nr == 0){
            $limit--;
            $q = "SELECT AUTO_INCREMENT
    FROM information_schema.TABLES
    WHERE TABLE_SCHEMA = '$wpdb->dbname'
    AND TABLE_NAME = '$table_ml_nr' ";
            $autoi = $wpdb->get_var($q);

            $nr = $autoi + $numgroup + $autoi_inner;

            $q = "select post_id  from $table_postmeta where meta_key = 'dso_query_nr' and meta_value = '$nr'";
            $nr_post = $wpdb->get_var($q);
            if($nr_post !== null){
                $q = "insert into $table_ml_nr set id = $autoi, order_id = $nr_post, nr = $nr";
                $err = $this->query($q);
                if($err){
                    $autoi_inner++;
                }
                $nr = 0;
                continue;
            }
            
        }
        
//        $q = "SELECT count(num) from $table_ml_nr_resv where num = $autoi";
//        $cou = $wpdb->get_var($q);
        $cou = 1;
        $in = false;
        while($limit > 0 && $cou > 0){
            $limit--;
            if($in)
                $autoi++;
            $in = true;
            $nr = $autoi + $numgroup;
            
            // свободен ?
            $q = "SELECT count(id) from $table_ml_nr where id = $autoi";
            $cou = $wpdb->get_var($q);
            if($cou > 0){
                continue;
            }
            
            // зарезервирован ?
            $q = "SELECT count(num) from $table_ml_nr_resv where num = $autoi";
            $cou = $wpdb->get_var($q);
            if($cou > 0){
                continue;
            }
                // check existed query

            // использовался ?
            $q = "select post_id  from $table_postmeta where meta_key = 'dso_query_nr' and meta_value = '$nr'";
            $nr_post = $wpdb->get_var($q);
            if($nr_post !== null){
                $q = "insert into $table_ml_nr set id = $autoi, order_id = $nr_post, nr = $nr";
                $wpdb->query($q);
                $cou = 1;
                continue;
            }
            
            // резервируем
            $q = "insert into $table_ml_nr_resv set num = $autoi, order_id = $pid, nr = $nr";
            $this->query($q);
                
            // занят ?
            $qpid = 0;
            $cou = $this->queryExists($nr,$qpid);
            if($cou > 0){
//                if($qpid){
                    $q = "insert into $table_ml_nr set id = $autoi, order_id = $qpid, nr = $nr";
                    $this->query($q);
//                }
                $nr = 0;
                continue;
            }
        }
        return $nr;
    }
    public function query($q){
        global $wpdb;
        global $ht, $aca;
        ob_start();
        $wpdb->query($q);
        $err = ob_get_clean();
        if($err){
//                    add_log($err);
            $_d=debug_backtrace();
            $f_='';
            if(isset($_d[1]['file']) && isset($_d[1]['line'])){
                $f = str_replace('\\','/',$_d[1]['file']);
            //    __FILE__.':'.__LINE__
                $f = explode('/',$f);
                $f1 = array_pop($f);
                $f2 = array_pop($f);
            //    return
                $f_= '/'.$f2.'/'.$f1.':'.$_d[1]['line'].'<br/>';
            }


            $f = str_replace('\\','/',$_d[0]['file']);
        //    __FILE__.':'.__LINE__
            $f = explode('/',$f);
            $f1 = array_pop($f);
            $f2 = array_pop($f);
        //    return
            $f= $f_. '/'.$f2.'/'.$f1.':'.$_d[0]['line'].'<br/>';
            $err = $err.$ht->f('div',$f).'<br/>';
            add_log($err);
            echo $err;
            return true;
        }
        return false;
    }
    public function nrExists($num){
    }
    public function numResvd($num){
        global $wpdb;
        $r = false;
        $table_ml_nr_resv = $wpdb->prefix . "ml_nr_resv";
        $q = "SELECT count(num) from $table_ml_nr_resv where num = $num";
        $cou = $wpdb->get_var($q);
        return $cou > 0;
    }
    public function queryExists($nr,&$qpid){
        global $wpdb;
        global $ht, $aca;
        $have = false;
                    $q = 'query-referral-results';
                    $atts = [];
                    $atts['is_show_test'] = true;
                    $query=[];
//                    $numgroup = 9950000000;
//                    $num = $numgroup;
//                    $num = $num + $orderId;
//                    $num = apply_filters( 'medlab_num_query', $num, $orderId, $numgroup );
//                    $query['MisId'] = $orderId;
                    $query['Nr'] = $nr;  // $num;
//                    $query['LisId'] = get_post_meta( $orderId, 'dso_query_id', true );
                    $atts['query'] = $query;
                    $data_ = MedLab::_queryBuild($q,$atts);
//                    add_log('<pre>'.htmlspecialchars(print_r($atts,1)).'</pre>');
//                    add_log('<pre>'.htmlspecialchars(print_r($data_,1)).'</pre>');
                    
                    unset($atts['is_show_test']);
                    $data_ = MedLab::_queryBuild($q,$atts);
                    
                    $answer = doPostRequest($data_);
                    $xml = simplexml_load_string($answer);
                    $qrootAtt = MedLab::_buildAttrs($xml);
                    
//                    $aca->n($ht->pre($qrootAtt));
//                    $aca->n($ht->pre($xml));
//                    echo $ht->pre($qrootAtt);
//                    echo $ht->pre($xml);
                    
                    if(isset($xml->Warnings) && isset($xml->Warnings->Item) ){
                        $Items = $xml->Warnings->Item;
                        if(!is_array($Items)){
                            $Items = [ $Items ];
                        }
//                                add_log('count an bm : '.count($an_bm_));

                        foreach($Items as $Item){
                            $mess = $Item['Text'];
//                            add_log('Warnings: '.$mess);
                        }
                    }
//                    $qpid = 'zzzzzzzzzzzz';
                    if(isset($qrootAtt['Error']) ){
//                        $mess = $xml->Error->Item['Text'];
                        $mess = $qrootAtt['Error'];
                        add_log('Error: '.$mess);
                        $have = false;
                    }else{
                        if(isset($xml->Referral) ){
                            $qReferralAtt = MedLab::_buildAttrs($xml->Referral);
                            if(isset($qReferralAtt['MisId']) ){
                                $qpid = (int) $qReferralAtt['MisId'];
                            }else{
                        $aca->n($ht->pre($qrootAtt));
                        $aca->n($ht->pre($xml));
//                        echo $ht->pre($qrootAtt);
//                        echo $ht->pre($xml);
                        
                        add_log('Error anser');
                        add_log('Error: '.$ht->pre($qrootAtt));
                        add_log('Error: '.$ht->pre($xml));
                            }
                        }
                        $have = 1;
                    }
        // $qpid = (int) MisId
        return $have;
    }
}
