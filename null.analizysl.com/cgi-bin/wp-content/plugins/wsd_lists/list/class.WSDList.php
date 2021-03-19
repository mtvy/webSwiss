<?php
/*
 * class.WSDList.php
 */


include_once 'class.WSDListFields.php';
include_once 'class.WSDListItem.php';

define ('WSD_LIST_PARENT','curiers');
if(!defined('WSD_LIST_PARENT'))define ('WSD_LISTS_PARENT','wsdlists');

class WSDList {

    public $object = null;
    public $user = null;
    public $page = 'wsdlist';//lab_groups
    public $parent_page = WSD_LISTS_PARENT;
    public $use_as_parent = false;
    public $shortcode = 'wsdl_list';
    public $table_name = 'wsdl_list';
    public $chengBlocked = !true;
    public $menu_title = 'Список';
    public $menu_name = 'Список';
    public $menu_icon = 'dashicons-location-alt';

    public function __construct($list) {
        $this->short_code = $list['table'];
        $this->table_name = 'wsdl_'.$list['table'];
        $this->menu_name = $list['menu'];
        $this->page = $list['page'];
        $this->parent_page = $list['parent'];
        $this->use_as_parent = $list['asparent'];
        $this->chengeBlocked = $list['nochenge']?true:false;
        $this->menu_title = $list['title'];
        $this->menu_icon = $list['icon']?$list['icon']:'dashicons-location-alt';
                    
//        add_filter('ds_admin_profile_block_list', [$this,'ds_admin_profile_block_list'], $priority=11, $accepted_args=3);
//        add_action('ds_admin_profile_list_init', [$this,'ds_admin_profile_list_init'], $priority=11, $accepted_args=3);
//        add_action('ds_admin_profile_save', [$this,'ds_admin_profile_save'], $priority=11, $accepted_args=2);
        add_action('admin_menu', [$this, 'options']);
//        add_action('admin_enqueue_scripts', [$this, 'admin_style']);
//        add_filter( 'manage_users_custom_column', [$this,'manage_users_custom_column'], 10, 3 );
        $this->shortcode();
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
                /* `id` int(11) unsigned NOT NULL AUTO_INCREMENT, */
                 `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
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
                 `required` int(1) unsigned DEFAULT '0' NOT NULL comment '',
                 `filter` int(1) unsigned DEFAULT '0' NOT NULL comment '',
                 `placeholder` VARCHAR(32) NOT NULL comment '',
                 `order` int(1) unsigned DEFAULT '0' NOT NULL comment '',
                 `weigh_order` int(11) unsigned DEFAULT '0' NOT NULL comment '',
                 `from_table` VARCHAR(32) NOT NULL comment '',
                 `from_value` VARCHAR(32) NOT NULL comment '',
                 `from_title` VARCHAR(32) NOT NULL comment '',
                 `from_where` VARCHAR(64) NOT NULL comment '',
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
                /* `id` int(11) unsigned NOT NULL AUTO_INCREMENT, */
                 `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
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
            <h2><?=$this->menu_title?></h2>
        <?php
        ob_start();
        settings_errors();

//        $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'list';
        $tab = filter_input(INPUT_GET, 'tab', FILTER_SANITIZE_STRING);
        $active_tab = filter_has_var(INPUT_GET, 'tab') ? filter_input(INPUT_GET, 'tab', FILTER_SANITIZE_STRING) : 'list';
//        $tab = filter_has_var(INPUT_GET, 'tab') ? filter_input(INPUT_GET, 'tab', FILTER_SANITIZE_STRING) : 'list';

        global $display_sub_button;
        $display_sub_button = 1;

        global $form_action;
//        $form_action = 'options.php';

//        $r_page = $this->rpage;
//        $form_action = "edit.php?post_type={$this->name}&page=".$r_page.'_fields';
//        $form_action = "users.php?page=".'lab_groups';

        $form_action = "admin.php?page=" . $this->page;
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
            echo '<input type="hidden" name="form_type" value="list">'; //  dsp_list
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
            $pf = new WSDListItem($this->table_name);
            $pf->display();
            submit_button();
        }
        if ($active_tab == 'add_field') {
            echo '<input type="hidden" name="form_type" value="add_field">';
            settings_fields($this->page); // меняем под себя только здесь
//            $pf = new DSopProductField();
            $pf = new WSDListFields($this->table_name);
            $pf->display();
            submit_button();
        }
        if ($active_tab == 'edit') {
            if ($fid) {
                echo '<input type="hidden" name="form_type" value="edit">';
                settings_fields($this->page); // меняем под себя только здесь
                //            $this->build_edit();
//                $pf = new DSopProductField();
                $pf = new WSDListItem($this->table_name);
                $pf->display();
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
                $pf = new WSDListFields($this->table_name); // MedLabLabGroupFields();
                $pf->display();
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
                $tab_fields= $wpdb->prefix . $this->table_name . "_fields";
                $tab_value= $wpdb->prefix . $this->table_name . "_value";

                $q = "select `name` from `$tab_fields` where `weigh` = '0'";
                $field_title = $wpdb->get_var($q);
                $q = "select $field_title from `$tab_value` where `id` = '$fid'";
//                $field = $wpdb->get_row($q, ARRAY_A);
//                
//                $row_name = array_shift($field);
                $row_name = $wpdb->get_var($q);

                $m = 'Вы действительно хотите удалить запись:<br/>'
                        . $row_name . ' [id=' . $fid . '] ?';
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
                $pf = new WSDListItem($this->table_name);
                $pf->display();
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
                $pf = new WSDListFields($this->table_name);
                $pf->display();
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
        public function admin_style() {
          wp_enqueue_style('admin-styles', get_template_directory_uri().'/admin.css');
        }
        public function admin_style_inlune() {
            $css = "
            #adminmenu li a.wp-has-current-submenu .update-plugins.fs-trial {
                background-color: #00b9eb;
            }
            #adminmenu li a.wp-has-current-submenu .update-plugins.bg-magenta {
                background-color: magenta;
            }
            #adminmenu .update-plugins.fs-trial {
                background-color: #00b9eb;
            }
            #adminmenu .update-plugins.bg-magenta {
                background-color: #00b9eb;
                background-color: magenta;
            }
            ";
            if( $this->page == 'curier_cargo_doc')
                $css .= "
                @keyframes Gradient {
                    0%,
                    100% {
                        background-position: 0 50%
                    }
                    50% {
                        background-position: 100% 50%
                    }
                }
                @keyframes Gradient_2 {
                    0%,
                    100% {
                        background-position: 0 50%
                    }
                    50% {
                        background-position: 100% 50%
                    }
                }
                @keyframes Gradient_3 {
                    0%,
                    100% {
                        background-position: 0 50%
                    }
                    50% {
                        background-position: 100% 50%
                    }
                }
                @keyframes Gradient_4 {
                    0%,
                    100% {
                        background-position: 0 50%
                    }
                    50% {
                        background-position: 100% 50%
                    }
                }
                a.toplevel_page_elementskit,
                a.toplevel_page_$this->page,
                span.toplevel_page_$this->page,
                span.count_fashion_0 {
                    background: linear-gradient(-45deg, #EE7752, #E73C7E, #000, #23A6D5, #23D5AB)!important;
                    animation: Gradient 15s ease infinite;
                    background-size: 400% 400%!important;
                    color: #fff!important;
                }
                span.count_fashion_1 {
                    background: linear-gradient(-45deg, #EE7752, #E73C7E, #23A6D5, #23D5AB)!important;
                    animation: Gradient 15s ease infinite;
                    background-size: 400% 400%!important;
                    color: #fff!important;
                }
                span.count_fashion_2 {
                    background: linear-gradient(-45deg, #23D5AB, #EE7752, #E73C7E, #23A6D5)!important;
                    animation: Gradient_2 1s ease infinite;
                    background-size: 400% 400%!important;
                    color: #fff!important;
                }
                span.count_fashion_3 {
                    background: linear-gradient(-45deg, #23A6D5, #23D5AB, #EE7752, #000, #E73C7E)!important;
                    animation: Gradient_3 1s ease infinite;
                    background-size: 400% 400%!important;
                    color: #fff!important;
                }
                span.count_fashion_4 {
                    background: linear-gradient(-45deg, #E73C7E, #23A6D5, #23D5AB, #EE7752)!important;
                    background: linear-gradient(-45deg, #EE7752, #000, #E73C7E, #23A6D5, #23D5AB)!important;
                    animation: Gradient_4 1s ease infinite;
                    background-size: 400% 400%!important;
                    color: #fff!important;
                }
                span.count_fashion_5 {
                    background: linear-gradient(-45deg, #fff, #E73C7E, #23A6D5, #000, #23D5AB, #EE7752)!important;
                    animation: Gradient_4 1s ease infinite;
                    background-size: 400% 400%!important;
                    color: #fff!important;
                }
                ";
            $css = "<style>$css</style>";
            echo $css;
        }

        public function options() {
            add_action('admin_head', [$this, 'admin_style_inlune']);
            $pages_fashion = [];
            $pages_fashion[] = 'curier_cargo_doc';
            $pages_fashion[] = 'curier_cargo_order';
            $pages_fashion[] = 'curier_cargo_bar';
            global $count_fashion;
            if(empty($count_fashion))$count_fashion=0;
            $count_fashion++;
            $warning_title = '';
            $warning_count = 1;
            $bubl ="";
            if( in_array( $this->page, $pages_fashion)){
                $bubl .="<span class='update-plugins count-$warning_count -fs-trial' title='$warning_title'><span class='update-count'>" . number_format_i18n($warning_count) . "</span></span>";
                $bubl .="<span class='update-plugins count-$warning_count fs-trial' title='$warning_title'><span class='update-count'>" . number_format_i18n($warning_count) . "</span></span>";
                $bubl .="<span class='update-plugins count-$warning_count bg-magenta' title='$warning_title'><span class='update-count'>" . number_format_i18n($warning_count) . "</span></span>";
                $bubl .="<span class='update-plugins count_fashion_$count_fashion' title='$warning_title'><span class='update-count'>" . number_format_i18n($warning_count) . "</span></span>";
                $bubl .="<span class='update-plugins count_fashion_".($count_fashion+1)."' title='$warning_title'><span class='update-count'>" . number_format_i18n($warning_count) . "</span></span>";
                $bubl .="<span class='update-plugins count_fashion_".($count_fashion+2)."' title='$warning_title'><span class='update-count'>" . number_format_i18n($warning_count) . "</span></span>";
            }
//            $bubl = '';
            if($this->use_as_parent){
                $hookname = add_menu_page( $this->menu_title, $this->menu_name.$bubl, 'manage_options',
                    $this->page, [$this, 'page_wrapper'], $this->menu_icon);
            }else{
            
                $hookname = add_submenu_page( $this->parent_page,
        //        $hookname = add_submenu_page( "users.php",
                    $this->menu_title, $this->menu_name.$bubl, 'manage_options',$this->page, [$this,'page_wrapper']);
            }
            add_action("load-$hookname", [$this, 'init_table_page_load']);
//            add_action("$hooknsload-$hookname", [$this, 'init_table_page_load']);
        }

        public function init_table_page_load() {
            $this->_notice('init_table_page_load '.$this->table_name);

            $fid = filter_input(INPUT_POST, 'fid', FILTER_SANITIZE_STRING);
            $ft = filter_input(INPUT_POST, 'form_type', FILTER_SANITIZE_STRING);
            if ($fid) {
                if ($ft == 'remove') {
                    $isok = filter_input(INPUT_POST, 'isremove', FILTER_SANITIZE_STRING);
                    if ($isok == 'ok') {
//                    $this->_notice( '<div> 0 </div>');
//                        WSDListItem::removeField($fid);
                        $pf = new WSDListItem($this->table_name);
                        $pf->removeField($fid);
                    }
                }
                if ($ft == 'remove_field') {
                    $isok = filter_input(INPUT_POST, 'isremovefield', FILTER_SANITIZE_STRING);
                    if ($isok == 'ok') {
//                    $this->_notice( '<div> 0 </div>');
//                        WSDListFields::removeField($fid);
                        $pf = new WSDListFields($this->table_name);
                        $pf->removeField($fid);
                    }
                }
            }
            global $lt, $lft;
//            $ltf = 'class.DShopProductListTable.php';
//            $ltf = 'class.MLLabGroupsListTable.php';
            
    //        $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'list';
            $active_tab = filter_has_var(INPUT_GET, 'tab') ? filter_input(INPUT_GET, 'tab', FILTER_SANITIZE_STRING) : 'list';
            if ($active_tab == 'list') {
                $ltf = 'class.WSDListListTable.php';
                require_once $ltf;
                $lt = new WSDListListTable($this->table_name,"admin.php?page=" . $this->page);
            }
            if ($active_tab == 'list_fields') {
                $ltf = 'class.WSDListFieldsTable.php';
                require_once $ltf;
                $lft = new WSDListFieldsTable($this->table_name,"admin.php?page=" . $this->page . '&tab=list_fields');
            }
    //        $r_page = $this->rpage;
    //        $lt = new DShopProductListTable("edit.php?post_type={$this->name}&page=".$r_page.'_fields');
    //            $lt = new WSDListListTable("admin.php?page=" . 'lab_groups');
        }
    
    /**
notice-success - для успешных операций. Зеленая полоска слева.
notice-error - для ошибок. Красная полоска слева.
notice-warning - для предупреждений. Оранжевая полоска слева.
notice-info - для информации. Синяя полоска слева.
is-dismissible - добавляет иконку-кнопку "закрыть" (крестик в конце блока).
     *  Иконка добавляется через javascript.
     *  По клику на нее блок-заметка будет скрыт (удален).
     * 
     * @param type $m
     * @param type $s
     */
    public function n($m='',$s='success'){
        $this->addNtce($m,$s);
    }
    public function notice($m='',$s='success'){
        $this->addNtce($m,$s);
    }
    public $ntc=[];
    public function addNtce($m='',$c='success'){
        $this->ntc[]=['c'=>$c,'m'=>$m];
    }
    public function _notices(){
        foreach($this->ntc as $n)$this->_notice($n['m'],$n['c']);
        showLogInfo('admin');
    }
    public function _notice($m='',$s='success'){
        $class = 'notice-success';
        $class = 'notice-'.$s;
        $message = "Ошибка сохранения";
        if(is_array($m)|| is_object($m))
            $m= '<pre>'.print_r($m,1).'</pre>';
        echo '<div class="notice '.$class.' is-dismissible"> <p>'. $m .'</p></div>';
    }
    public function shortcode(){
//        echo ($this->short_code.' shortcode');
        add_shortcode('wsd_list_'.$this->short_code.'-list',[$this, 'shortcode_list']);
        add_shortcode('wsd_list_'.$this->short_code.'-item',[$this, 'shortcode_item']);
        add_shortcode('wsd_list_'.$this->short_code.'-edit',[$this, 'shortcode_add']);
        add_shortcode('wsd_list_'.$this->short_code.'-add',[$this, 'shortcode_edit']);
    }
    public function shortcode_list($atts,$content,$tag){
        return $this->shr__dshop($this->short_code,'list');
    }
    public function shortcode_item($atts,$content,$tag){
        return $this->shr__dshop($this->short_code,'item');
    }
    public function shortcode_add($atts,$content,$tag){
        return $this->shr__dshop($this->short_code,'add');
    }
    public function shortcode_edit($atts,$content,$tag){
        return $this->shr__dshop($this->short_code,'edit');
    }
    public function shr__dshop($page,$type=''){
        global $wsd_lists_obj;
        $wsd_lists_obj = $this;
        if(strpos($page,'wsd_list_')===0)$page = strtr($page,['wsd_list_'=>'']);
//        echo $page;
        $out = '';
        ob_start();
//        if(!is_user_logged_in())
        get_template_part( 'template-parts/wsd_lists/'.$page, $type );
        $out.=ob_get_clean();
        $out = do_shortcode( $out );
        return $out;
    }
}
    