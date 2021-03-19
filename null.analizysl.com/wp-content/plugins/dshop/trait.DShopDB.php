<?php

/* 
 * trait.DShopDB.php
 */

if(0){
    [
        0 => 2,
        1 => 4,
        2 => 8,
        3 => 16,
        4 => 32,
        5 => 64,
        6 => 128,
        7 => 256,
        
        0 => 512,
        1 => 1024,
        2 => 2048,
        3 => 4096,
        4 => 8192,
        5 => 16384,
        6 => 32768,
        7 => 65536,
        
        0 => 131072,
        1 => 262144,
        2 => 524288,
        3 => 1048576,
        4 => 2097152,
        5 => 4194304,
        6 => 8388608,
        7 => 16777216,
        
        0 => 33554432,
        1 => 67108864,
        2 => 134217728,
        3 => 268435456,
        4 => 536870912,
        5 => 1073741824,
        6 => 2147483648,
        7 => 4294967296,
        
    ];
$t=<<<t
012345678 012345678 012345678 012345678 012345678 012345678 012345678 012345678 012345678 012345678 
012345678 012345678 012345678 012345678 012345678 012345678 012345678 012345678 012345678 012345678 
012345678 012345678 012345678 012345678 012345678 012345678 012345
t;
}
trait DshopDB {
    public function initDB(){
        global $wpdb;

        $dshop = $wpdb->prefix . "dshop";
        $dshop_order = $wpdb->prefix . "ds_order";
        if($wpdb->get_var("SHOW TABLES LIKE '$dshop_order'") != $dshop_order) {
         // тут мы добавляем таблицу в базу данных
            $sql = "CREATE TABLE " . $dshop_order . " (
                 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                 `uid` int(11) unsigned DEFAULT '0' NOT NULL comment '',
                 `time` int(11) unsigned DEFAULT '0' NOT NULL comment '',
                 `date` date NOT NULL comment '',
                 `updated` date  NULL comment '',
                 `update_uid` int(11) DEFAULT NULL NULL comment '',
                 `post_id` int(11) DEFAULT '0' NULL comment '',
                 `items_count` int(11) DEFAULT '0' NULL comment '',
                 `count` int(11) DEFAULT '0' NULL comment '',
                 `cost` int(11) DEFAULT '0' NULL comment '',
                 `total` int(11) DEFAULT '0' NULL comment '',
                 `status` VARCHAR(32) NOT NULL,
                 `user_name` VARCHAR(32) NOT NULL,
                 `user_lastname` VARCHAR(32) NOT NULL,
                 `user_sname` VARCHAR(32) NOT NULL,
                 `agreements` int(11) DEFAULT NULL NULL comment '',
                 `user_phone` VARCHAR(16) NOT NULL,
                 `user_email` VARCHAR(256) NOT NULL,
                 `user_addres` text NOT NULL,
                 PRIMARY KEY (`id`),
                 INDEX uid (uid),
                 INDEX update_uid (update_uid),
                 INDEX date (date),
                 INDEX post_id (post_id),
                 INDEX status (status),
                 INDEX user_name (user_name),
                 INDEX user_lastname (user_lastname),
                 INDEX user_sname (user_sname),
                 INDEX user_phone (user_phone),
                 INDEX user_email (user_email)
               )
               ENGINE=InnoDB AUTO_INCREMENT=0;";

            if(current_user_can('manage_options')){
               require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
               $dbq = dbDelta($sql,1);
//               add_log($dbq);
            }

//           $ds_items = [];
//            foreach ($ds_items as $rn => $rl) {
//    //            $hitems[]=$rl['name'];
//                $acid = $wpdb->get_var("select id from  $table_roles where role = '$rn' ");
//                if($acid === null){
//                    $q = "insert into $dshop set role_id = $num, role = '$rn' ";
//                    $wpdb->query($q);
//                }
//                $num++;
//            }
         }
        $dso_medlab = $wpdb->prefix . "dso_attr";
        if($wpdb->get_var("SHOW TABLES LIKE '$dso_medlab'") != $dso_medlab) {
         // тут мы добавляем таблицу в базу данных
            $sql = "CREATE TABLE " . $dso_medlab . " (
                 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                 `oid` int(11) unsigned DEFAULT '0' NOT NULL comment 'MisId',
                 `puid` int(11) unsigned DEFAULT '0' NOT NULL comment 'patient',
                 `duid` int(11) unsigned DEFAULT '0' NOT NULL comment 'doctor',
                 `query_id` int(11) unsigned DEFAULT '0' NOT NULL comment 'LisId',
                 `query_nr` int(11) unsigned DEFAULT '0' NOT NULL comment 'Nr',
                 `sender` int(11) unsigned DEFAULT '0' NOT NULL comment 'sender Id',
                 `query_status` VARCHAR(32) NOT NULL comment 'sending',
                 `q_ref_comment` text NOT NULL,
                 `q__answer` text NOT NULL,
                 PRIMARY KEY (`id`),
                 INDEX oid (oid),
                 INDEX puid (puid),
                 INDEX duid (duid),
                 INDEX query_id (query_id),
                 INDEX query_nr (query_nr),
                 INDEX sender (sender),
                 INDEX query_status (query_status)
               )
               ENGINE=InnoDB AUTO_INCREMENT=0 comment='ds_order attr';";

            if(current_user_can('manage_options')){
               require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
               $dbq = dbDelta($sql,1);
//               add_log($dbq);
            }

//           $ds_items = [];
//            foreach ($ds_items as $rn => $rl) {
//    //            $hitems[]=$rl['name'];
//                $acid = $wpdb->get_var("select id from  $table_roles where role = '$rn' ");
//                if($acid === null){
//                    $q = "insert into $dshop set role_id = $num, role = '$rn' ";
//                    $wpdb->query($q);
//                }
//                $num++;
//            }
         }
         do_action('dshop_db_alter_'.'order'.'__'.'attr');
         
        $dso_item = $wpdb->prefix . "dso_item";
        if($wpdb->get_var("SHOW TABLES LIKE '$dso_item'") != $dso_item) {
         // тут мы добавляем таблицу в базу данных
            $sql = "CREATE TABLE " . $dso_item . " (
                 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                 `orderId` int(11) unsigned DEFAULT '0' NOT NULL comment '',
                 `prodId` int(11) unsigned DEFAULT '0' NOT NULL comment '',
                 `count` int(11) unsigned DEFAULT '0' NOT NULL comment '',
                 `item_cost` int(11) unsigned DEFAULT '0' NOT NULL comment '',
                 `items_cost` int(11) unsigned DEFAULT '0' NOT NULL comment '',
                 `prodUrl` text NOT NULL,
                 `prodCategory` text NOT NULL,
                 `prodName` text NOT NULL,
                 PRIMARY KEY (`id`),
                 INDEX orderId (orderId),
                 INDEX prodId (prodId)
               )
               ENGINE=InnoDB AUTO_INCREMENT=0;";

            if(current_user_can('manage_options')){
               require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
               $dbq = dbDelta($sql,1);
//               add_log($dbq);
            }
         }
        $dsoi_attr = $wpdb->prefix . "dsoi_attr";
        if($wpdb->get_var("SHOW TABLES LIKE '$dsoi_attr'") != $dsoi_attr) {
         // тут мы добавляем таблицу в базу данных
            $sql = "CREATE TABLE " . $dsoi_attr . " (
                 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                 `dsoi_id` int(11) unsigned DEFAULT '0' NOT NULL comment 'order item id',
                 PRIMARY KEY (`id`),
                 INDEX dsoi_id (dsoi_id)
               )
               ENGINE=InnoDB AUTO_INCREMENT=0 comment='dso_item attr';";

            if(current_user_can('manage_options')){
               require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
               $dbq = dbDelta($sql,1);
//               add_log($dbq);
            }
         }
         do_action('dshop_db_alter_'.'item'.'__'.'attr');
         
        $dso_delivery = $wpdb->prefix . "dso_delivery";
        if($wpdb->get_var("SHOW TABLES LIKE '$dso_delivery'") != $dso_delivery) {
         // тут мы добавляем таблицу в базу данных
            $sql = "CREATE TABLE " . $dso_delivery . " (
                 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                 `oid` int(11) unsigned DEFAULT '0' NOT NULL comment 'order id',
                 `delivery_id` int(11) unsigned DEFAULT '0' NOT NULL comment '',
                 `markup` int(11) unsigned DEFAULT '0' NOT NULL comment '%',
                 `delivery_name` text NOT NULL,
                 PRIMARY KEY (`id`),
                 INDEX oid (oid)
               )
               ENGINE=InnoDB AUTO_INCREMENT=0;";

            if(current_user_can('manage_options')){
               require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
//               $dbq = dbDelta($sql,1);
//               add_log($dbq);
            }
         }
        $dsod_attr = $wpdb->prefix . "dsod_attr";
        if($wpdb->get_var("SHOW TABLES LIKE '$dso_delivery'") != $dso_delivery) {
         // тут мы добавляем таблицу в базу данных
            $sql = "CREATE TABLE " . $dso_delivery . " (
                 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                 `odid` int(11) unsigned DEFAULT '0' NOT NULL comment 'order id',
                 PRIMARY KEY (`id`),
                 INDEX odid (odid)
               )
               ENGINE=InnoDB AUTO_INCREMENT=0 comment='dso_delivery attr';";

            if(current_user_can('manage_options')){
               require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
               $dbq = dbDelta($sql,1);
//               add_log($dbq);
            }
         }
         do_action('dshop_db_alter_'.'delivery'.'__'.'attr');
         
//123456789-123456789-123456789-123456789-123456789-123456789-123456789-123456789-123456789-123456789-
//123456789-123456789-123456789-123456789-123456789-123456789-123456789-123456789-123456789-123456789-
//123456789-123456789-123456789-123456789-123456789-123456789-123456;
        $ds_product= $wpdb->prefix . "ds_product";
        if($wpdb->get_var("SHOW TABLES LIKE '$ds_product'") != $ds_product) {
         // тут мы добавляем таблицу в базу данных
            $sql = "CREATE TABLE " . $ds_product . " (
                 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                 `cost` int(11) unsigned DEFAULT '0' NOT NULL comment '',
                 `count` int(11) unsigned DEFAULT '0' NOT NULL comment '',
                 `max` int(11) unsigned DEFAULT '0' NOT NULL comment '',
                 `min` int(11) unsigned DEFAULT '0' NOT NULL comment '',
                 `pid` int(11) unsigned DEFAULT '0' NOT NULL comment '',
                 `code` VARCHAR(32) NOT NULL comment 'Код товара',
                 `title` VARCHAR(256) NOT NULL comment 'Заголовок',
                 `short` text NOT NULL,
                 `desc` text NOT NULL,
                 PRIMARY KEY (`id`),
                 INDEX cost (cost),
                 INDEX count (count),
                 INDEX max (max),
                 INDEX min (min),
                 INDEX pid (pid),
                 INDEX code (code),
                 INDEX title (title)
               )
               ENGINE=InnoDB AUTO_INCREMENT=0;";

            if(current_user_can('manage_options')){
               require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
               $dbq = dbDelta($sql,1);
//               add_log($dbq);
            }
         }
         
        if(1){
            // список мета тегов
            $fields=[];
            $nm = 'dsp_opt_';
            $nm = 'f_';
            $opt_num = 0;
            $fields[$nm.$opt_num++]='Срок выполнения';
            $fields[$nm.$opt_num++]='Синонимы (rus)';
            $fields[$nm.$opt_num++]='Синонимы (eng)';
            $fields[$nm.$opt_num++]='Методы';
            $fields[$nm.$opt_num++]='Единицы измерения';
            $fields[$nm.$opt_num++]='Подготовка к исследованию';
            $fields[$nm.$opt_num++]='Тип биоматериала и  способы взятия';
            
            // типы содержимого поля
            $ftpl=[];
            $opt_num = 0;
            $ftpl[$nm.$opt_num++]='text'; // bigtext text integer float
            $ftpl[$nm.$opt_num++]='text';
            $ftpl[$nm.$opt_num++]='text';
            $ftpl[$nm.$opt_num++]='text';
            $ftpl[$nm.$opt_num++]='text';
            $ftpl[$nm.$opt_num++]='text';
            $ftpl[$nm.$opt_num++]='text';
            $types = $ftpl;
            
            // участие в поиске, наличие индекса
            $ftpl=[];
            $opt_num = 0;
            $ftpl[$nm.$opt_num++]='0';
            $ftpl[$nm.$opt_num++]='0';
            $ftpl[$nm.$opt_num++]='0';
            $ftpl[$nm.$opt_num++]='0';
            $ftpl[$nm.$opt_num++]='0';
            $ftpl[$nm.$opt_num++]='0';
            $ftpl[$nm.$opt_num++]='0';
            $searchs = $ftpl;
            
            // рзмеры поля
            $ftpl=[];
            $opt_num = 0;
            $ftpl[$nm.$opt_num++]='0';
            $ftpl[$nm.$opt_num++]='0';
            $ftpl[$nm.$opt_num++]='0';
            $ftpl[$nm.$opt_num++]='0';
            $ftpl[$nm.$opt_num++]='0';
            $ftpl[$nm.$opt_num++]='0';
            $ftpl[$nm.$opt_num++]='0';
            $sizes = $ftpl;
            
            // рзмеры поля float после запятой
            $ftpl=[];
            $opt_num = 0;
            $ftpl[$nm.$opt_num++]='0';
            $ftpl[$nm.$opt_num++]='0';
            $ftpl[$nm.$opt_num++]='0';
            $ftpl[$nm.$opt_num++]='0';
            $ftpl[$nm.$opt_num++]='0';
            $ftpl[$nm.$opt_num++]='0';
            $ftpl[$nm.$opt_num++]='0';
            $flsizes = $ftpl;
            
            // типы шаблонов поля метатегов
            $ftpl=[];
            $opt_num = 0;
            $ftpl[$nm.$opt_num++]='td_ta_';
            $ftpl[$nm.$opt_num++]='td_ta_';
            $ftpl[$nm.$opt_num++]='td_ta_';
            $ftpl[$nm.$opt_num++]='td_ta_';
            $ftpl[$nm.$opt_num++]='td_ta_';
            $ftpl[$nm.$opt_num++]='td_ta_';
            $ftpl[$nm.$opt_num++]='td_ta_';
            $ftpls = $ftpl;

            // значения метатегов
            $ftpl=[];
            $opt_num = 0;
            $ftpl[$nm.$opt_num++]='';
            $ftpl[$nm.$opt_num++]='';
            $ftpl[$nm.$opt_num++]='';
            $ftpl[$nm.$opt_num++]='';
            $ftpl[$nm.$opt_num++]='';
            $ftpl[$nm.$opt_num++]='';
            $ftpl[$nm.$opt_num++]='';
            $vals = $ftpl;

            // варианты значений метатегов
            $ftpl=[];
            $opt_num = 0;
            $ftpl[$nm.$opt_num++]=false;
            $ftpl[$nm.$opt_num++]=false;
            $ftpl[$nm.$opt_num++]=false;
            $ftpl[$nm.$opt_num++]=false;
            $ftpl[$nm.$opt_num++]=false;
            $ftpl[$nm.$opt_num++]=false;
            $ftpl[$nm.$opt_num++]=false;
            $vars = $ftpl;
        }
        $dsp_fields= $wpdb->prefix . "dsp_fields";
        if($wpdb->get_var("SHOW TABLES LIKE '$dsp_fields'") != $dsp_fields) {
         // тут мы добавляем таблицу в базу данных
            $sql = "CREATE TABLE " . $dsp_fields . " (
                 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                 `name` VARCHAR(16) NOT NULL comment '',
                 `weigh` int(11) unsigned DEFAULT '0' NOT NULL comment '',
                 `active` int(1) unsigned DEFAULT '1' NOT NULL comment '',
                 `title` VARCHAR(64) NOT NULL comment '',
                 `tpl` VARCHAR(32) NOT NULL comment '',
                 `type` VARCHAR(32) NOT NULL comment '',
                 `search` int(1) unsigned DEFAULT '1' NOT NULL comment '',
                 `size` int(11) unsigned DEFAULT '0' NOT NULL comment '',
                 `flsize` int(11) unsigned DEFAULT '0' NOT NULL comment '',
                 `unsigned` int(1) unsigned DEFAULT '0' NOT NULL comment '',
                 `zerofill` int(1) unsigned DEFAULT '0' NOT NULL comment '',
                 `def` VARCHAR(256) NOT NULL comment '',
                 `vars` text NOT NULL,
                 `desc` text NOT NULL,
                 `help` text NOT NULL,
                 PRIMARY KEY (`id`),
                 INDEX weigh (weigh),
                 UNIQUE INDEX name (name),
                 INDEX title (title)
               )
               ENGINE=InnoDB AUTO_INCREMENT=0 comment='ds_product attr fields';";

            if(current_user_can('manage_options')){
               require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
               $dbq = dbDelta($sql,1);
//               add_log($dbq);
            }
        
            $num=0;
            foreach ($fields as $name => $title) {
                $vs = $vars[$name];
                if($vs===false || !count($vs))$vs = [];
                $vs = serialize($vs);
                $q = "insert into $dsp_fields set `name` = '$name', `weigh` = $num, `active` = '1', `title` = '$title',"
                        . " `tpl` = '$ftpls[$name]', `type` = '$types[$name]', `search` = '$searchs[$name]', `size` = '$sizes[$name]', "
                        . " `flsize` = '$flsizes[$name]', `def` = '$vals[$name]', `vars` = '$vs', `desc` = '', `help` = '' ";
                $wpdb->query($q);
                $num++;
            }
         }
        $dsp_attr= $wpdb->prefix . "dsp_attr";
        if($wpdb->get_var("SHOW TABLES LIKE '$dsp_attr'") != $dsp_attr) {
         // тут мы добавляем таблицу в базу данных
            $sql = "CREATE TABLE " . $dsp_attr . " (
                 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                 `pid` int(11) unsigned DEFAULT '0' NOT NULL comment 'product id',
                 PRIMARY KEY (`id`)
               )
               ENGINE=InnoDB AUTO_INCREMENT=0 comment='ds_product attr';";
            /*
             * 
                 `f_0` text NOT NULL,
                 `f_1` text NOT NULL,
                 `f_2` text NOT NULL,
                 `f_3` text NOT NULL,
                 `f_4` text NOT NULL,
                 `f_5` text NOT NULL,
                 `f_6` text NOT NULL,
             */

            if(current_user_can('manage_options')){
               require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
               $dbq = dbDelta($sql,1);
//               add_log($dbq);
               
                $dsp_attr= $wpdb->prefix . "dsp_attr";
                $dsp_fields= $wpdb->prefix . "dsp_fields";
                $q= "show create table `$dsp_attr` ";
                $fields = explode("\n",$wpdb->get_var($q,1));
                $fs = [];
                foreach($fields as $f){
                    $f = trim($f);
//                echo '$f<pre>'.print_r($f,1).'</pre>';
//                echo '$f<pre>'.print_r(str_split($f),1).'</pre>';
                    if($f[0] == '`'){
                        $matches=null;
                        $pattern = '/^`([0-9_a-zA-Z]+)`/'; 
                        if(preg_match($pattern, $f, $matches)){ 
                            if(isset($matches[1])){
                                $fs[]=$matches[1];
                            }
//                            echo '$matches<pre>'.print_r($matches,1).'</pre>';
                        }
//                        echo '$pattern<pre>'.print_r($pattern,1).'</pre>';
//                        $pattern = '/([0-9_a-zA-Z]+)/'; 
//                        if(preg_match($pattern, substr($subject,3), $matches, PREG_OFFSET_CAPTURE)){ 
//                            if(isset($matches[1])){
////                                $fs[]=$matches[1];
//                            }
//                            echo '$matches<pre>'.print_r($matches,1).'</pre>';
//                        }
//                        echo '$pattern<pre>'.print_r($pattern,1).'</pre>';
                    }
                }
//                echo '$fs<pre>'.print_r($fs,1).'</pre>';
//                echo '$fields<pre>'.print_r($fields,1).'</pre>';
//                echo '$q<pre>'.print_r($q,1).'</pre>';
//                echo '$fields<pre>'.print_r($wpdb->get_var($q,1),1).'</pre>';
/*
CREATE TABLE `wp_dsp_attr` (
 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'product id',
 `f_0` text NOT NULL,
 `f_1` text NOT NULL,
 `f_2` text NOT NULL,
 `f_3` text NOT NULL,
 `f_4` text NOT NULL,
 `f_5` text NOT NULL,
 `f_6` text NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ds_product attr'


*/
                
                $dsp_fields= $wpdb->prefix . "dsp_fields";
                $q= "select * from `$dsp_fields` order by `weigh`";
                $fields = $wpdb->get_results($q,ARRAY_A);
                
                foreach($fields as $field){
                    // text varchar int float
                    $name = $field['name'];
                    $object->meta_fields[$name] = $field['title'];
                    $object->meta_ftpl[$name] = $field['tpl'];
                    $object->meta_val[$name] = $field['def'];
                    $object->meta_vars[$name] = false;
                    $vars = unserialize($field['vars']);
                    if(count($vars))$object->meta_vars[$name] = $vars;
                    
                    $ftps = [];
                    $ftps['date'] = 'DATE NOT NULL';
                    $ftps['time'] = 'TIME NOT NULL';
                    $ftps['datetime'] = 'DATETIME NOT NULL';
                    $ftps['tinytext'] = 'TINYTEXT NOT NULL';
                    $ftps['text'] = 'text NOT NULL';
                    $ftps['mediumtext'] = 'MEDIUMTEXT NOT NULL';
                    $ftps['longtext'] = 'LONGTEXT NOT NULL';
                    $ftps['varchar'] = "VARCHAR(_size_) NOT NULL DEFAULT ''";
                    $ftps['char'] = "CHAR(_size_) NOT NULL DEFAULT ''";
                    $ftps['int'] = "int(_size_) NOT NULL _UNSIGNED_ _ZEROFILL_ DEFAULT '0' ";
                    $ftps['integer'] = "INTEGER(_size_) NOT NULL _UNSIGNED_ _ZEROFILL_ DEFAULT '0' ";
                    $ftps['bigint'] = "BIGINT(_size_) NOT NULL _UNSIGNED_ _ZEROFILL_ DEFAULT '0' ";
                    $ftps['real'] = "REAL(_size_._flsize_) NOT NULL _UNSIGNED_ _ZEROFILL_ DEFAULT '0'";
                    $ftps['double'] = "DOUBLE(_size_._flsize_) NOT NULL _UNSIGNED_ _ZEROFILL_ DEFAULT '0'";
                    $ftps['float'] = "FLOAT(_size_._flsize_) NOT NULL _UNSIGNED_ _ZEROFILL_ DEFAULT '0'";
                    $ftps['decimal'] = "DECIMAL(_size_._flsize_) NOT NULL _UNSIGNED_ _ZEROFILL_ DEFAULT '0'";
                    $ftps['numeric'] = "NUMERIC(_size_._flsize_) NOT NULL _UNSIGNED_ _ZEROFILL_ DEFAULT '0'";
                    
                    $r = [];
                    $r['_size_'] = $field['size'];
                    $r['_flsize_'] = $field['flsize'];
                    $r['_UNSIGNED_'] = $field['unsigned']?'UNSIGNED':'';
                    $r['_ZEROFILL_'] = $field['zerofill']?'ZEROFILL':'';
                    $ftps = strtr($ftps[$field['type']],$r);
                    
                    $q = "alter table `$dsp_attr` add $field[name] $ftps ";
                    if(in_array($name,$fs)){
                        $q = "alter table `$dsp_attr` CHANGE COLUMN `$field[name]` `$field[name]` $ftps ";
                    }
                    $wpdb->query($q);
                    
                    if($field['search'] == 1){
                        $q = "alter table `$dsp_attr` add index {$field['name']}  ({$field['name']}) ";
                        if(in_array($field['type'], ['text','text','text','text',]))
                        $q = "alter table `$dsp_attr` add FULLTEXT {$field['name']}  ({$field['name']}) ";
                        $wpdb->query($q);
                    }

                }
            }
         }
         do_action('dshop_db_alter_'.'product'.'__'.'attr');
         
        $ds_category= $wpdb->prefix . "ds_category";
        if($wpdb->get_var("SHOW TABLES LIKE '$ds_category'") != $ds_category) {
         // тут мы добавляем таблицу в базу данных
            $sql = "CREATE TABLE " . $ds_category . " (
                 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                 `parent` int(11) unsigned DEFAULT '0' NOT NULL comment '',
                 `title` VARCHAR(128) NOT NULL comment '',
                 `alt` VARCHAR(128) NOT NULL comment '',
                 PRIMARY KEY (`id`),
                 INDEX parent (parent),
                 INDEX title (title),
                 INDEX alt (alt)
               )
               ENGINE=InnoDB AUTO_INCREMENT=0 comment='available categories';";

            if(current_user_can('manage_options')){
               require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
               $dbq = dbDelta($sql,1);
//               add_log($dbq);
            }
         }
        $dsp_category= $wpdb->prefix . "dsp_category";
        if($wpdb->get_var("SHOW TABLES LIKE '$dsp_category'") != $dsp_category) {
         // тут мы добавляем таблицу в базу данных
            $sql = "CREATE TABLE " . $dsp_category . " (
                 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                 `cat` int(11) unsigned DEFAULT '0' NOT NULL comment 'category',
                 `pid` int(11) unsigned DEFAULT '0' NOT NULL comment 'product',
                 PRIMARY KEY (`id`),
                 INDEX cat (cat),
                 INDEX pid (pid)
               )
               ENGINE=InnoDB AUTO_INCREMENT=0 comment='products category';";

            if(current_user_can('manage_options')){
               require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
               $dbq = dbDelta($sql,1);
//               add_log($dbq);
            }
         }
        $ds_payment= $wpdb->prefix . "dso_payment";
        if($wpdb->get_var("SHOW TABLES LIKE '$ds_payment'") != $ds_payment) {
         // тут мы добавляем таблицу в базу данных
            $sql = "CREATE TABLE " . $ds_payment . " (
                 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                 `dso_ID` int(11) DEFAULT '0' NOT NULL comment '',
                 `status` int(11) DEFAULT '0' NOT NULL comment '',
                 `userId` int(11) DEFAULT '0' NOT NULL comment '',
                 `payway` int(11) DEFAULT '0' NOT NULL comment '',
                 `outsumm` int(11) DEFAULT '0' NOT NULL comment '',
                 `fee` int(11) DEFAULT '0' NOT NULL comment '',
                 `signatura` int(11) DEFAULT '0' NOT NULL comment '',
                 `user_email` int(11) DEFAULT '0' NOT NULL comment '',
                 `istest` int(11) DEFAULT '0' NOT NULL comment '',
                 `result_post` text NOT NULL,
                 `cost` int(11) DEFAULT '0' NOT NULL comment '',
                 PRIMARY KEY (`id`),
                 INDEX dso_ID (dso_ID),
                 INDEX userId (userId),
                 INDEX payway (payway),
                 INDEX user_email (user_email)
               )
               ENGINE=InnoDB AUTO_INCREMENT=0;";

            if(current_user_can('manage_options')){
               require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
               $dbq = dbDelta($sql,1);
//               add_log($dbq);
            }
         }
        $dsop_attr= $wpdb->prefix . "dsop_attr";
        if($wpdb->get_var("SHOW TABLES LIKE '$dsop_attr'") != $dsop_attr) {
         // тут мы добавляем таблицу в базу данных
            $sql = "CREATE TABLE " . $dsop_attr . " (
                 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                 `dsop_id` int(11) unsigned DEFAULT '0' NOT NULL comment '',
                 PRIMARY KEY (`id`),
                 INDEX dsop_id (dsop_id)
               )
               ENGINE=InnoDB AUTO_INCREMENT=0 comment='dso_payment attr';";

            if(current_user_can('manage_options')){
               require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
               $dbq = dbDelta($sql,1);
//               add_log($dbq);
            }
         }
         do_action('dshop_db_alter_'.'payment'.'__'.'attr');
         
         
        $dso_discont= $wpdb->prefix . "dso_discont";
        if($wpdb->get_var("SHOW TABLES LIKE '$dso_discont'") != $dso_discont) {
         // тут мы добавляем таблицу в базу данных
            $sql = "CREATE TABLE " . $dso_discont . " (
                 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                 `oid` int(11) unsigned DEFAULT '0' NOT NULL comment '',
                 `uid` int(11) unsigned DEFAULT '0' NOT NULL comment '',
                 `discont` int(11) DEFAULT NULL NULL comment '',
                 `name` int(11) unsigned DEFAULT '0' NOT NULL comment '',
                 PRIMARY KEY (`id`),
                 INDEX oid (oid),
                 INDEX uid (uid),
                 INDEX name (name)
               )
               ENGINE=InnoDB AUTO_INCREMENT=0 comment='';";

            if(current_user_can('manage_options')){
               require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
               $dbq = dbDelta($sql,1);
//               add_log($dbq);
            }
         }
        $dso_discont_name= $wpdb->prefix . "dso_discont_name";
        if($wpdb->get_var("SHOW TABLES LIKE '$dso_discont_name'") != $dso_discont_name) {
         // тут мы добавляем таблицу в базу данных
            $sql = "CREATE TABLE " . $dso_discont_name . " (
                 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                 `name` VARCHAR(32) NOT NULL comment '',
                 PRIMARY KEY (`id`),
                 INDEX name (name)
               )
               ENGINE=InnoDB AUTO_INCREMENT=0 comment='';";

            if(current_user_can('manage_options')){
               require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
               $dbq = dbDelta($sql,1);
//               add_log($dbq);
                $q = "insert into $dso_discont_name set `name` = 'user_card' ";
                $wpdb->query($q);
                $q = "insert into $dso_discont_name set `name` = 'laborant' ";
                $wpdb->query($q);
            }
         }
//         do_action('dshop_db_alter_'.'dso_discont'.'__'.'attr');
        
    }
    public function updateDB(){
        
    }
    public function dsget($field=false,$dsid=0,$defoult=null){
        global $wpdb;
        $dshop = $wpdb->prefix . "dshop";
        return $wpdb->get_var("select `$field` from `$dshop` ds where ds.`dsid` = '$dsid'");
    }
    public function dsset($field=false,$value=null){
        if($field == false || $field=='' || (is_array($field) && count($field) == 0))return false;
        global $wpdb;
        $dshop = $wpdb->prefix . "dshop";
        $set = [];
        if(is_array()){
            $set = $field;
        }else{
            $set[$field] = $value;
        }
        foreach ( $set as $f=>$v){
            $set[$f] = "`$f` = '$v";
        }
        $set = implode(",\n",$set);
        $wpdb->query("update `$dshop_order` set $set");
    }
    /**
     * dshop order get
     * @global type $wpdb
     * @param type $field
     * @param type $dsid
     * @param type $defoult
     * @return type
     */
    public function dsoget($field=false,$dsid=0,$defoult=null){
        global $wpdb;
        $dshop_order = $wpdb->prefix . "ds_order";
        return $wpdb->get_var("select `$field` from `$dshop_order` ds where ds.`dsid` = '$dsid'");
    }
    /**
     * dshop order set
     * @global type $wpdb
     * @param type $field
     * @param type $value
     * @return boolean
     */
    public function dsoset($field=false,$value=null){
        if($field == false || $field=='' || (is_array($field) && count($field) == 0))return false;
        global $wpdb;
        $dshop_order = $wpdb->prefix . "ds_order";
        $set = [];
        if(is_array()){
            $set = $field;
        }else{
            $set[$field] = $value;
        }
        foreach ( $set as $f=>$v){
            $set[$f] = "`$f` = '$v";
        }
        $set = implode(",\n",$set);
        $wpdb->query("update `$dshop_order` set $set");
    }
    public function dsoadd($fields=[]){
        global $wpdb;
        $dshop_order = $wpdb->prefix . "ds_order";
        return $wpdb->insert($dshop_order,$fields);
    }
    public function getarr($query=[],$rtype=ARRAY_A){
        global $wpdb;
        $dshop_order = $wpdb->prefix . "ds_order";
        return $wpdb->get_results($query,$rtype);
    }
}