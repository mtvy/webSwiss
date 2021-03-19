<?php
/*
 * class.MLELabGroups.php
 */


include_once 'class.MLECurierFields.php';
include_once 'class.MLECurierItem.php';

class MLECurier {

    public $object = null;
    public $user = null;
    public $page = 'curiers';//lab_groups
    public $table_name = 'curier';
    public $chengBlocked = true;

    public function __construct() {
//        add_filter('ds_admin_profile_block_list', [$this,'ds_admin_profile_block_list'], $priority=11, $accepted_args=3);
//        add_action('ds_admin_profile_list_init', [$this,'ds_admin_profile_list_init'], $priority=11, $accepted_args=3);
//        add_action('ds_admin_profile_save', [$this,'ds_admin_profile_save'], $priority=11, $accepted_args=2);
        add_action('admin_menu', [$this, 'options']);
//        add_filter( 'manage_users_custom_column', [$this,'manage_users_custom_column'], 10, 3 );
    }

    public function init() {
        global $ht, $aca;
//        $roles = get_option('wp_user_roles',[]);
//        $aca->n($ht->pre('$wpdb->dbname'));
        $this->initDB();
    }

    public function initDB() {
        global $wpdb;
        global $ht, $aca;
        
        
        $tab_name = $this->table_name;

        $tab_fields= $wpdb->prefix . $tab_name . "_fields";
        if($wpdb->get_var("SHOW TABLES LIKE '$tab_fields'") != $tab_fields) {
         // тут мы добавляем таблицу в базу данных
            $sql = "CREATE TABLE " . $tab_fields . " (
                 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                 `name` VARCHAR(16) NOT NULL comment '',
                 `weigh` int(11) unsigned DEFAULT '0' NOT NULL comment '',/* weigh form fields*/
                 `active` int(1) unsigned DEFAULT '1' NOT NULL comment '',
                 `weigh_admin` int(11) unsigned DEFAULT '0' NOT NULL comment '',/* weigh list column*/
                 `weigh_public` int(11) unsigned DEFAULT '0' NOT NULL comment '',
                 `show_admin` int(1) unsigned DEFAULT '1' NOT NULL comment '',
                 `show_pablic` int(1) unsigned DEFAULT '1' NOT NULL comment '',
                 `width_adm_css` VARCHAR(8) NOT NULL comment '',
                 `width_publ_css` VARCHAR(8) NOT NULL comment '',
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
                 `desc` text NOT NULL,/*admin*/
                 `help` text NOT NULL,/*public*/
                 PRIMARY KEY (`id`),
                 INDEX weigh (weigh),
                 INDEX weigh_admin (weigh_admin),
                 INDEX weigh_public (weigh_public),
                 UNIQUE INDEX name (name),
                 INDEX title (title)
               )
               ENGINE=InnoDB AUTO_INCREMENT=0 comment='';";

            if(current_user_can('manage_options')){
               require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
               $dbq = dbDelta($sql,1);
//               add_log($dbq);
            }
        }
        $dsp_attr= $wpdb->prefix . "dsp_attr";
        $tab_value= $wpdb->prefix . $tab_name . "_value";
        if($wpdb->get_var("SHOW TABLES LIKE '$tab_value'") != $tab_value) {
         // тут мы добавляем таблицу в базу данных
            $sql = "CREATE TABLE " . $tab_value . " (
                 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                 /*`pid` int(11) unsigned DEFAULT '0' NOT NULL comment 'product id',*/
                 PRIMARY KEY (`id`)
               )
               ENGINE=InnoDB AUTO_INCREMENT=0 comment='';";
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
            }
        }
        
        $table_ml_groups = $wpdb->prefix . "ml_groups";
//        $table_ml_nr_resv = $wpdb->prefix . "ml_nr_resv";
        $table_postmeta = $wpdb->prefix . "postmeta";
//        $q = "insert into $table_ml_nr
//            select post_id, post_id as 'order_id', meta_value as 'nr'
//            from $table_postmeta
//            where meta_key = 'dso_query_nr' ";
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_ml_groups'") != $table_ml_groups) {
            // тут мы добавляем таблицу в базу данных
            $sql = "CREATE TABLE " . $table_ml_groups . " (
                id int(10) NOT NULL AUTO_INCREMENT,
                `title` VARCHAR(64) NOT NULL comment '',
                `address` text NOT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY title (title)
              )
              ENGINE=InnoDB;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
//            $wpdb->query($q);
        }
//        if($wpdb->get_var("SHOW TABLES LIKE '$table_ml_nr_resv'") != $table_ml_nr_resv) {
//         // тут мы добавляем таблицу в базу данных
//            $sql = "CREATE TABLE " . $table_ml_nr_resv . " (
//                id int(10) NOT NULL AUTO_INCREMENT,
//                num int(10) DEFAULT '0' NOT NULL,
//                order_id int(10) DEFAULT '0' NOT NULL,
//                nr DECIMAL(11) UNSIGNED DEFAULT '0' NOT NULL,
//                UNIQUE KEY id (id),
//                UNIQUE KEY num (num),
//                UNIQUE KEY order_id (order_id),
//                UNIQUE KEY nr (nr)
//              )
//              ENGINE=InnoDB;";
//
//            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
////            dbDelta($sql);
//        }
//        $aca->n($ht->pre($q));
//        $roles = get_option('wp_user_roles',[]);
//        $aca->n($ht->pre($wpdb->dbname));
//        $aca->n($ht->pre($q));
//        $aca->n($ht->pre($wpdb->get_var($q)));
    }

    /*
     * =============================
     */

    public function page_wrapper() {

        //    $atr = func_get_args();
        //    add_log($atr);
        // тут уже будет находиться содержимое страницы
        global $ccab_page, $ht;
        ?><div class="wrap">
            <div id="icon-themes" class="icon32"></div>
            <h2>Курьеры</h2>
        <?php
        ob_start();
        settings_errors();

        $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'list';
        $tab = filter_input(INPUT_GET, 'tab', FILTER_SANITIZE_STRING);

        global $display_sub_button;
        $display_sub_button = 1;

        global $form_action;
        $form_action = 'options.php';

//        $r_page = $this->rpage;
//        $form_action = "edit.php?post_type={$this->name}&page=".$r_page.'_fields';
//        $form_action = "users.php?page=".'lab_groups';

        $form_action = "admin.php?page=" . 'curiers';
        $form_tab = $form_action;

        if ($tab && $tab == 'delete') {
//            $form_action.='&tab='.$tab;
        } else
        if ($tab && $tab == 'delete_field') {
//            $form_action.='&tab='.$tab;
        } else
        if ($tab) {
            $form_action.='&tab=' . $tab;
        }

        $tabs = [];
        $tabs['list'] = 'List';
        $tabs['add'] = 'Add';
        $fid = filter_input(INPUT_GET, 'fid', FILTER_SANITIZE_NUMBER_INT);
        if ($fid && $tab == 'edit') {
            $tabs['edit'] = 'Edit';
            $form_action.='&fid=' . $fid;
        }
        if ($fid && $tab == 'delete') {
            $tabs['delete'] = 'Delete';
//            $form_action.='&fid='.$fid;
        }

        if(!$this->chengBlocked){
            $tabs_field = ['list_fields', 'add_field', 'edit_field', 'delete_field'];
            $tabs['list_fields'] = 'Fields';
            if (in_array($tab, $tabs_field)) {
                $tabs['add_field'] = 'Add field';
                $fid = filter_input(INPUT_GET, 'fid', FILTER_SANITIZE_NUMBER_INT);
                if ($fid && $tab == 'edit_field') {
                    $tabs['edit_field'] = 'Edit field';
                    $form_action.='&fid=' . $fid;
                }
                if ($fid && $tab == 'delete_field') {
                    $tabs['delete_field'] = 'Delete field';
                    //            $form_action.='&fid='.$fid;
                }
            }
        }

//        $tabs = apply_filters('ds_dsproduct_settings_extml__tabs', $tabs, $this);
        /*
         * <a href="edit.php?post_type=<?=$this->name?>&page=<?=$this->rpage?>_fields&tab=<?=$tn?>" class="nav-tab <?php echo $active_tab == $tn ? 'nav-tab-active' : ''; ?>"><?=$tt?></a>

         */
        ?>
            <h2 class="nav-tab-wrapper">
            <?php
            foreach ($tabs as $tn => $tt) { // name => title
                ?>
                    <a href="<?= $form_tab ?>&tab=<?= $tn ?>" class="nav-tab <?php echo $active_tab == $tn ? 'nav-tab-active' : ''; ?>"><?= $tt ?></a>
                    <?php
                }
                /*
                  <a href="users.php?page=lab_groups&tab=<?=$tn?>" class="nav-tab <?php echo $active_tab == $tn ? 'nav-tab-active' : ''; ?>"><?=$tt?></a>

                  <a href="edit.php?post_type=<?=$this->name?>&page=<?=$this->rpage?>_settings.php&tab=display_options" class="nav-tab <?php echo $active_tab == 'display_options' ? 'nav-tab-active' : ''; ?>">Display Options</a>
                  <a href="edit.php?post_type=<?=$this->name?>&page=<?=$this->rpage?>_settings.php&tab=robokassa_options" class="nav-tab <?php echo $active_tab == 'robokassa_options' ? 'nav-tab-active' : ''; ?>">Robokassa Options</a>
                 */
//            do_action('ds_dsproduct_settings_extml__add_tab_link', $this,$this->name,$this->rpage, $active_tab);
                ?>
            </h2>

            <form method="post" enctype="multipart/form-data" action="<?= $form_action ?>" >
        <?php
        if ($active_tab == 'list') {
            echo '<input type="hidden" name="form_type" value="ist">'; //  dsp_list
            settings_fields($this->page); // меняем под себя только здесь

            global $lt;
            $lt->display();
//            submit_button();
        }
        if ($active_tab == 'list_fields') {
            echo '<input type="hidden" name="form_type" value="list_fields">'; //  dsp_list
            settings_fields($this->page); // меняем под себя только здесь

            global $lt, $lft;
            $lft->display();
//            submit_button();
        }
        if ($active_tab == 'add') {
            echo '<input type="hidden" name="form_type" value="add">';
            settings_fields($this->page); // меняем под себя только здесь
//            $pf = new DSopProductField();
            $pf = new MLECurierItem($this->table_name);
            submit_button();
        }
        if ($active_tab == 'add_field') {
            echo '<input type="hidden" name="form_type" value="add_field">';
            settings_fields($this->page); // меняем под себя только здесь
//            $pf = new DSopProductField();
            $pf = new MLECurierFields($this->table_name);
            submit_button();
        }
        if ($active_tab == 'edit') {
            if ($fid) {
                echo '<input type="hidden" name="form_type" value="edit">';
                settings_fields($this->page); // меняем под себя только здесь
                //            $this->build_edit();
//                $pf = new DSopProductField();
                $pf = new MLECurierItem($this->table_name);
                submit_button();
            } else {
//                $r_page = $this->rpage;
//                $form_action = "edit.php?post_type={$this->name}&page=".$r_page.'_fields';
//                $form_action = "users.php?page=" . 'lab_groups';
                $form_action = "admin.php?page=" . $this->page;
                $m = 'Нечего редактировать.<br/>';
                echo '<div class=""> <h3>' . $m . '</h3></div>';
                $ccl = sprintf('<a href="%s">%s</a>', $form_action, __('Cancel', 'hb-users'));
                echo $ccl;
            }
        }
        if ($active_tab == 'edit_field') {
            if ($fid) {
                echo '<input type="hidden" name="form_type" value="edit_field">';
                settings_fields($this->page); // меняем под себя только здесь
                //            $this->build_edit();
//                $pf = new DSopProductField();
                $pf = new MLECurierFields($this->table_name); // MedLabLabGroupFields();
                submit_button();
            } else {
//                $r_page = $this->rpage;
//                $form_action = "edit.php?post_type={$this->name}&page=".$r_page.'_fields';
                $form_action = "admin.php?page=" . $this->page . '&tab=list_fields';
                $m = 'Нечего редактировать.<br/>';
                echo '<div class=""> <h3>' . $m . '</h3></div>';
                $ccl = sprintf('<a href="%s">%s</a>', $form_action, __('Cancel', 'hb-users'));
                echo $ccl;
            }
        }
        if ($active_tab == 'delete') {
//            var_dump($fid);
            if ($fid) {
                echo '<input type="hidden" name="form_type" value="remove">';
                echo '<input type="hidden" name="fid" value="' . $fid . '">';
                echo '<input type="hidden" name="isremove" value="ok">';
                settings_fields($this->page); // меняем под себя только здесь

                global $wpdb;
                $tab_value= $wpdb->prefix . $this->table_name . "_value";

                $q = "select * from `$tab_value` where `id` = '$fid'";
                $field = $wpdb->get_row($q, ARRAY_A);

                $m = 'Вы действительно хотите удалить запись:<br/>'
                        . $field['title'] . ' [' . $field['name'] . '] ?';
                echo '<div class=""> <h3>' . $m . '</h3></div>';

                $m = '<b>Удаляемое поле, будет удалено безвозвратно.</b>';
                echo '<div class="notice notice-warning is-dismissible"> <p>' . $m . '</p></div>';

                $btn = get_submit_button(__('Remove'));
                //            $form_action
//                $r_page = $this->rpage;
//                $form_action = "edit.php?post_type={$this->name}&page=".$r_page.'_fields';
//                $form_action = "users.php?page=" . 'lab_groups';
                $form_action = "admin.php?page=" . $this->page ;
                $ccl = sprintf('<a href="%s">%s</a>', $form_action, __('Cancel', 'hb-users'));
                $r = ['</p>' => ' &nbsp; ' . $ccl . '</p>'];
                echo strtr($btn, $r);
                //            $this->build_edit();
//                $pf = new DSopProductField();
                $pf = new MLECurierItem($this->table_name);
            } else {
//                $r_page = $this->rpage;
//                $form_action = "edit.php?post_type={$this->name}&page=".$r_page.'_fields';
//                $form_action = "users.php?page=" . 'lab_groups';s
                $form_action = "admin.php?page=" . $this->page ;
                $m = 'Нечего удалять.<br/>';
                echo '<div class=""> <h3>' . $m . '</h3></div>';
                $ccl = sprintf('<a href="%s">%s</a>', $form_action, __('Cancel', 'hb-users'));
                echo $ccl;
            }
        }
        if ($active_tab == 'delete_field') {
//            var_dump($fid);
            if ($fid) {
                    echo '<input type="hidden" name="form_type" value="remove_field">';
                echo '<input type="hidden" name="fid" value="' . $fid . '">';
                echo '<input type="hidden" name="isremovefield" value="ok">';
                settings_fields($this->page); // меняем под себя только здесь

                global $wpdb;
                $tab_fields= $wpdb->prefix . $this->table_name . "_fields";

                $q = "select * from `$tab_fields` where `id` = '$fid'";
                $field = $wpdb->get_row($q, ARRAY_A);

                $m = 'Вы действительно хотите удалить запись:<br/>'
                        . $field['title'] . ' [' . $field['name'] . '] ?';
                echo '<div class=""> <h3>' . $m . '</h3></div>';

                $m = '<b>Удаляемое поле, будет удалено безвозвратно.</b>';
                echo '<div class="notice notice-warning is-dismissible"> <p>' . $m . '</p></div>';

                $btn = get_submit_button(__('Remove'));
                //            $form_action
//                $r_page = $this->rpage;
//                $form_action = "edit.php?post_type={$this->name}&page=".$r_page.'_fields';
//                $form_action = "users.php?page=" . 'lab_groups';
                $form_action = "admin.php?page=" . $this->page . '&tab=list_fields';
                $ccl = sprintf('<a href="%s">%s</a>', $form_action, __('Cancel', 'hb-users'));
                $r = ['</p>' => ' &nbsp; ' . $ccl . '</p>'];
                echo strtr($btn, $r);
                //            $this->build_edit();
//                $pf = new DSopProductField();
                $pf = new MLECurierFields($this->table_name);
            } else {
                $form_action = "admin.php?page=" . $this->page . '&tab=list_fields';
                $m = 'Нечего удалять.<br/>';
                echo '<div class=""> <h3>' . $m . '</h3></div>';
                $ccl = sprintf('<a href="%s">%s</a>', $form_action, __('Cancel', 'hb-users'));
                echo $ccl;
            }
        }
        if ($active_tab == 'robokassa_options') {
//            settings_fields($this->page); // меняем под себя только здесь
////            settings_fields('ccab_options'); // меняем под себя только здесь
//            // (название настроек)
//            do_settings_sections($this->page);
//            do_settings_sections('kassa_'.$this->page);
//    //        do_settings_sections('shortcodes_'.$ccab_page);
//    //        do_settings_sections('shortcodes_'.$ccab_page);
//    //        echo 'show shortcodes';
////            ccab_show_sortcodes();
        }

//        do_action('ds_dsproduct_settings_extml__do_tab_sections', $this,$this->page, $active_tab);
//        if($display_sub_button) submit_button();
        ?>
            </form>
                <?php
                if ($active_tab == 'display_options') {
                    
                }
                if ($active_tab == 'robokassa_options') {
//            do_settings_sections('info_'.$this->page);
                } // end if/else
//        do_action('ds_dsproduct_settings_extml__do_tab_footer_info', $this,$this->page, $active_tab);
                /*
                 * 
                  <p class="submit">
                  <input type="submit" class="button-primary"
                  value="<?php _e('Save Changes') ?>" />
                  </p>
                 */
                $out = ob_get_clean();
//        showLogInfo('admin');
                echo $out;
                ?>
        </div><?php
        }

        public function options() {
//        $page = $this->page;
//        $page = null;
//        $page = 'options.php';
            $page = 'dshop.php'; // страница настроек магазина
//        $r_page = $this->rpage;
            //    $ parent slug
            //    $ page title
            //    $ menu title
            //    $ capability
            //    $ menu slug
            //    $ function
//		$ptype_obj = get_post_type_object( $this->name );
//        $page = $ptype_obj->show_in_menu;
//        $this->n('<pre>'.print_r($ptype_obj,1).'</pre>');
//		$ptype_obj = get_post_type_object( 'post' );
//		$ptype_obj = get_post_type_object( 'dspayment' );
//        $page = $ptype_obj->show_in_menu;
//        $this->n('<pre>'.print_r($ptype_obj,1).'</pre>');
//        $screen = get_current_screen();
//    global $_parent_pages;
//        $this->n('<pre>:'.print_r($_parent_pages,1).'</pre>');
//        add_submenu_page( $page, 'Shortcodes', 'Shortcodes', 'manage_options',
//            ''.$r_page.'/shortcodes.php', 'ccab_page_shortcodes_wrapper');  
//        add_submenu_page( $page, 'Параметры', 'Параметры', 'manage_options',
//            "edit.php?post_type={$this->name}".'&page='.$r_page.'_settings.php', [$this,'page_wrapper']);

            $page = 'dshop.php'; // страница настроек магазина
            $page = 'curiers'; // страница настроек магазина

            $hookname = add_menu_page('Курьеры', 'Курьеры', 'manage_options',
                    'curiers', [$this, 'page_wrapper'],'dashicons-location-alt');
            add_action("load-$hookname", [$this, 'init_table_page_load']);
            
//	add_menu_page( 'My Page Title', 'My Page', 'edit_others_posts', 'my_page_slug', 'my_page_function', plugins_url( 'myplugin/images/icon.png' ), 6 );
//        add_submenu_page( "edit.php?post_type={$this->name}",
//                'Настройки товара', 'Настройки товара', 'manage_options',
//            ''.$r_page.'__settings.php', [$this,'page_wrapper']);
//        $hookname = add_submenu_page( "edit.php?post_type={$this->name}",
//                'Доп поля товара', 'Доп поля товара', 'manage_options',
//            ''.$r_page.'_fields', [$this,'page_wrapper']);
//        $hookname = add_submenu_page( "edit.php?post_type={$this->name}",
//        $hookname = add_submenu_page( "users.php",
//                'Группы лаборантов', 'Группы лаборантов', 'manage_options',
//            ''.'lab_groups', [$this,'page_wrapper']);

//            $hooknsload-$hookname", [$this, 'init_table_page_load']);


//		if(0)add_submenu_page(
//                $ptype_obj->show_in_menu,
//                $ptype_obj->labels->name,
//                $ptype_obj->labels->all_items,
//                $ptype_obj->cap->edit_posts,
//                "edit.php?post_type=$ptype" );
            //    add_submenu_page( $ccab_page, 'Параметры 3', 'Параметры 3', 'manage_options',
            //        'p3_'.$ccab_page.'', 'true_option_page2');
            //        add_submenu_page($parent_slug, $page_title, $menu_title,
            //                $capability, $menu_slug, $function);
        }

        public function init_table_page_load() {

            $fid = filter_input(INPUT_POST, 'fid', FILTER_SANITIZE_STRING);
            $ft = filter_input(INPUT_POST, 'form_type', FILTER_SANITIZE_STRING);
            if ($fid) {
                if ($ft == 'remove') {
                    $isok = filter_input(INPUT_POST, 'isremove', FILTER_SANITIZE_STRING);
                    if ($isok == 'ok') {
//                    $this->_notice( '<div> 0 </div>');
                        MLECurierItem::removeField($fid);
                    }
                }
                if ($ft == 'remove_field') {
                    $isok = filter_input(INPUT_POST, 'isremovefield', FILTER_SANITIZE_STRING);
                    if ($isok == 'ok') {
//                    $this->_notice( '<div> 0 </div>');
                        MLECurierFields::removeField($fid);
                    }
                }
            }
            global $lt, $lft;
//            $ltf = 'class.DShopProductListTable.php';
//            $ltf = 'class.MLLabGroupsListTable.php';
            
        $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'list';
        if ($active_tab == 'list') {
            $ltf = 'class.MLECurierListTable.php';
            require_once $ltf;
            $lt = new MLECurierListTable($this->table_name,"admin.php?page=" . $this->page);
        }
        if ($active_tab == 'list_fields') {
            $ltf = 'class.MLECurierFieldsTable.php';
            require_once $ltf;
            $lft = new MLECurierFieldsTable($this->table_name,"admin.php?page=" . $this->page . '&tab=list_fields');
        }
//        $r_page = $this->rpage;
//        $lt = new DShopProductListTable("edit.php?post_type={$this->name}&page=".$r_page.'_fields');
//            $lt = new MLECurierListTable("admin.php?page=" . 'lab_groups');
        }

    }
    