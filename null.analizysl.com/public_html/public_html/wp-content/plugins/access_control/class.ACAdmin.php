<?php

/* 
 * class.ACAdmin.php
 */
include_once 'trait.ACAdminOptions.php';

class ACAdmin{
    public $_debug = false;
    public $rpage = 'ac';
    public $page = 'ac';  // .php
    use 
            DShopHtml,
            ACAdminOptions;
    public function __construct() {
//        add_action('admin_notices', [$this,'_notice']);
//        add_action('admin_notices', [$this,'_notice']);
//        $this->notice('hello 1');
//        $this->notice('hello 1','error');
//        $this->notice('hello 2','warning');
        add_action('admin_menu', [$this,'admin_menu']);
        add_action('admin_menu', [$this,'options']);
//        $this->init_options();
        add_action('admin_notices', [$this,'_notices']);
    }
    public function init(){
        ob_start();
        
        $this->updateRoles();
        $this->initRoles();
        $this->init_actabl();
        
        $err = ob_get_clean();
        if($err)
            $this->n($err);
    }
    
    /**
     * регистрируем раздел "кабинет" и основную страницу
     * в меню админа
     * https://developer.wordpress.org/resource/dashicons/#shield
     */
    public function admin_menu() {
//        $this->notice('hello 3','info');
        global $ccab_page;
    //    $ page_title
    //    $ menu_title
    //    $ capability
    //    $ menu_slug
    //    $ function
    //    $ icon_url
    //    $ position
//        $hook = add_menu_page('DShop', 'DShop', 1,
        $hook = add_menu_page('Access Control', 'Access Control', 'manage_options',
            $this->page, [$this,'page_wrapper'],'dashicons-admin-network');
    //    add_menu_page('Параметры Кабинетов', 'Кабинет', 1,
    //        $ccab_page, 'ccab_page_wrapper','dashicons-shield');
    //    add_action('load-'.$hook, array($this, 'showScreenOptions'));
//        add_action('load-'.$hook, 'showScreenOptions');
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
    public function n($m='',$s='success',$f_shift=0){
        
        if(current_user_can('manage_options')){
            $_d=debug_backtrace();
            $f='';
            $f_='';
            $ln = 1 + $f_shift;
            if(isset($_d[$ln]['file']) && isset($_d[$ln]['line'])){
                $f = str_replace('\\','/',$_d[$ln]['file']);
            //    __FILE__.':'.__LINE__
                $f = explode('/',$f);
                $f1 = array_pop($f);
                $f2 = array_pop($f);
            //    return
                $f_= '/'.$f2.'/'.$f1.':'.$_d[$ln]['line'].'<br/>';
            }


            $ln = 0 + $f_shift;
            $f = str_replace('\\','/',$_d[$ln]['file']);
        //    __FILE__.':'.__LINE__
            $f = explode('/',$f);
            $f1 = array_pop($f);
            $f2 = array_pop($f);
        //    return
            $f= $f_. '/'.$f2.'/'.$f1.':'.$_d[$ln]['line'].'<br/>';
            $f=ccab_line_left_wrapp($f);
        }
        if(is_array($m) ||  is_object($m)){
            $m = '<pre>' . print_r($m, 1) . '</pre>';
        }
        $this->addNtce($f . $m,$s);
    }
    public function notice($m='',$s='success'){
        $this->addNtce($m,$s);
    }
    public $ntc=[];
    public function addNtce($m='',$c='success'){
        $this->ntc[]=['c'=>$c,'m'=>$m];
    }
    public function _notices(){
        foreach($this->ntc as $n)$this->_notice($n['m'],$n['c'],1);
    }
    public function _notice($m='',$s='success',$f_shift=0){
        $class = 'notice-success';
        $class = 'notice-'.$s;
        $message = "Ошибка сохранения";
        
        echo '<div class="notice '.$class.' is-dismissible"> <p>'. $m .'</p></div>';
    }

    /**
     * обёртка для страницы кабинета shortcodes
     * @global string $true_page
     */
    public function page_wrapper() {
        
    //    $atr = func_get_args();
    //    add_log($atr);
        // тут уже будет находиться содержимое страницы
    global $ccab_page;
    ?><div class="wrap">
        <h2>Параметры Access Control</h2>
       <?php
        ob_start();
        
        echo $this->acstyle();
        // action="options.php"
       ?>
        <form method="post" enctype="multipart/form-data"  >
            <input type="hidden" name="form_type" value="ac">
            <?php 
//            settings_fields($this->page); // меняем под себя только здесь
//            settings_fields('ccab_options'); // меняем под себя только здесь
            // (название настроек)
             
//            do_settings_sections($this->page);
            
    //        do_settings_sections('shortcodes_'.$ccab_page);
    //        do_settings_sections('shortcodes_'.$ccab_page);
    //        echo 'show shortcodes';
//            ccab_show_sortcodes();
            $this->buildTabRolesAcc();
	submit_button();
            ?>
        </form>
<script>
    function setselog (){
        var selog = document.getElementById(this.id);
//        var id = this.id;
        var sgs = document.querySelectorAll('.'+this.id);
        for(var i = 0;i<sgs.length;i++){
            if(selog.checked == true){
    //            val sch = selog.checked ;
                $(sgs[i]).prop('checked', true);
            }else{
    //            val sch = selog.checked ;
                $(sgs[i]).prop('checked', false);
            }
        }
    }
//var selog = document.getElementById('ac-g');
var selog = document.querySelectorAll('.acgc');
if(selog){
    for(var i = 0 ; i < selog.length; i++){
        selog[i].onclick = setselog;
    }
}
</script>
       <?php
       /*
        * 
            <p class="submit">  
                    <input type="submit" class="button-primary"
                           value="<?php _e('Save Changes') ?>" />  
            </p>
        */
        $out=ob_get_clean();
//        showLogInfo('admin');
        echo $out;
       ?>
    </div><?php
    }
    public $tabl = '';
    public function init_actabl(){
        
        require_once ABSPATH . 'wp-admin/includes/user.php';
        $roles = get_editable_roles();
        $ac = [];
        $ac = $this->ac;
//        $this->n($this->pre($roles));
        $tabl = [];
        
        $tabl[] = $this->h('2','Access Control');
        $tabl[] = $this->f('b','Access Control - настройки');
        
        $tr = [];
        $th = [];
        $td = [];
        
        $at=[];
        $at['id']='ac-g';
        $at['class']='ac ac-g ac-g-0 acgc';
        $at['class']='ac ac-g acgc';
//        $at['name']='ac-g';
        $at['value']='1';
//        if($selo && in_array($orderId,$selo))
//            $at['checked']='checked';
        $at['type']='checkbox';
        $check  =  $this->f('input','',$at);

        $at=[];
        $at['class']='acrlabel';
        $check  =  $this->f('label',$check.' Все',$at);
        
        
            
        $td[]=$this->td($check);
        $td[]=$this->td('');
//        $td[]=$this->td('');
        
        $at=[];
        $at['id']='ac-g-0';
        $at['class']='ac ac-g ac-g-0 acgc';  // ac group column
        $at['class']='ac ac-g acgc';
//        $at['name']='ac-g';
        $at['value']='1';
//        if($selo && in_array($orderId,$selo))
//            $at['checked']='checked';
        $at['type']='checkbox';
        $check  =  $this->f('input','',$at);

        $at=[];
        $at['class']='acrlabel';
        $check  =  $this->f('label',$check.' Все',$at);
        
        
            
        $td[]=$this->td($check);
        foreach ($roles as $rn => $rl) {
//            $acId = $p->ID.'_'.$rn;
//            $acId = $p->ID.'_'.$this->roles[$rn];

            $at=[];
            $at['id']='ac-g-'.$this->roles[$rn].'';
//            $at['id']='ac-g';
//            $at['class']='ac ac-g';
            $at['class']='ac ac-g ac-g-'.$this->roles[$rn].' acgc';
//            $at['name']='ac['.$acId.']';
            $at['value']='1';
//            if($ac && isset($ac[$acId]) && $ac[$acId])
//                $at['checked']='checked';
            $at['type']='checkbox';
            $check  =  $this->f('input','',$at);
            $at=[];
            $at['class']='acrlabel';
            $check  =  $this->f('label',$check.' Все',$at);

            $td[]=$this->td($check);
        }
        $tr[] = $this->tr(implode("\n",$td));
        
        $hitems = [];
        $hitems[]='Страницы';
//        $hitems[]='Группа';
        $hitems[]='page Id';
        $hitems[]='Guest';
        
        foreach ($roles as $rn => $rl) {
            $hitems[]=$rl['name'];
        }
        
        foreach ($hitems as $h) {
            $th[]=$this->th($h);
        }
//        $tr[] = $this->tr(implode("\n",$th));
        
        $args = array(
            'sort_order'   => 'ASC',
            'sort_column'  => 'ID',
    //        'hierarchical' => 1,
    //        'hierarchical' => 1,
    //        'exclude'      => '',
    //        'include'      => '',
    //        'meta_key'     => '',
    //        'meta_value'   => '',
    //        'authors'      => '',
    //        'child_of'     => 0,
    //        'parent'       => -1,
    //        'exclude_tree' => '',
    //        'number'       => '',
    //        'offset'       => 0,
            'post_type'    => 'page',
            'post_status'  => 'publish',
        ); 
        $pages = get_pages( $args );
//        $this->n($this->pre($pages[0]));
//        $this->n($this->pre($_POST));
        
//        $this->n($this->pre($ac));
        
        foreach ($pages as $p) {
            $td = [];
                
        
            /*
             * заголовок + чекбокс ряда
             */
            $at=[];
            $at['id']='ac-rg-'.$p->ID;
            $at['class']='ac ac-g ac-rg-0 acgc';  // ac group column
            $at['class']='ac ac-g ac-rg acgc';
    //        $at['name']='ac-g';
            $at['value']='1';
    //        if($selo && in_array($orderId,$selo))
    //            $at['checked']='checked';
            $at['type']='checkbox';
            $check  =  $this->f('input','',$at);

            $at=[];
            $at['class']='acrlabel';
            $check  =  $this->f('label',$check.' '.$this->f('b',$p->post_title),$at);
            $td[]=$this->td($check);
//            $td[]=$this->td($this->f('b',$p->post_title));
            
            /*
             * № страницы
             */
            $td[]=$this->td($p->ID);
            
            /*
             * не залогиненный
             */
            $acId = $p->ID.'_0';
            $at=[];
//                $at['id']='ac-'.$acId.'';
            $at['class']='ac ac-g ac-g-0 ac-rg-'.$p->ID.'';
            $at['name']='ac['.$acId.']';
            $at['value']=''.$acId.'';
            if($ac && isset($ac[$acId]) && $ac[$acId])
                $at['checked']='checked';
            $at['type']='checkbox';
            $check  =  $this->f('input','',$at);
            $at=[];
            $at['class']='acrlabel';
            $check  =  $this->f('label',$check,$at);
            
            $td[]=$this->td($check);
            foreach ($roles as $rn => $rl) {
                $acId = $p->ID.'_'.$rn;
                $acId = $p->ID.'_'.$this->roles[$rn];
        
                $at=[];
//                $at['id']='ac-'.$acId.'';
                $at['class']='ac ac-g ac-g-'.$this->roles[$rn].' ac-rg-'.$p->ID.'';
                $at['name']='ac['.$acId.']';
                $at['value']=''.$acId.'';
                if($ac && isset($ac[$acId]) && $ac[$acId])
                    $at['checked']='checked';
                $at['type']='checkbox';
                $check  =  $this->f('input','',$at);
                $at=[];
                $at['class']='acrlabel';
                $check  =  $this->f('label',$check,$at);
                
                $td[]=$this->td($check);
            }
        
            $tr[] = $this->tr(implode("\n",$td));
        }
        
//        $c = implode("\n",$tr);
//        $tabl = $this->f('table',$c);
//        $tabl[] = implode("\n",$tab);
//        $tabl = implode("\n",$tabl);
        $at=[];
        $at['class'] = '-form-table tac';
        $this->tabl = $this->table($tr,$th,'',$at);
    }
    public function buildTabRolesAcc(){
        echo $this->tabl;
    }
    public $ac = [];
    public $roles = [];
    public function initRoles(){
        global $wpdb;
        

        $table_roles = $wpdb->prefix . "ac_roles";

        require_once ABSPATH . 'wp-admin/includes/user.php';
        $roles = get_editable_roles();
//        $this->n($this->pre($roles));

        foreach ($roles as $rn => $rl) {
//            $hitems[]=$rl['name'];
            $acid = $wpdb->get_var("select id from  $table_roles where role = '$rn' ");
            if($acid === null){
                $num = $wpdb->get_var("select max(role_id) from  $table_roles  ");
                $num++;
                $q = "insert into $table_roles set role_id = $num, role = '$rn' ";
                $wpdb->query($q);
            }
        }
        
        $roles =[];
        $r_ = $wpdb->get_results( "SELECT * FROM $table_roles", ARRAY_A);
        if( $r_ ) {
            foreach($r_ as $r){
                $roles[$r['role']]=$r['role_id'];
            }
        }
        $this->roles = $roles;

        $table_access = $wpdb->prefix . "ac_ac";
        
        $ac =[];
        $ac_ = $wpdb->get_results( "SELECT * FROM $table_access",ARRAY_A );
        if( $ac_ ) {
            foreach($ac_ as $a){
                $ac[$a['page_id'].'_'.$a['role_id']]=$a['access'];
            }
        }
        $this->ac = $ac;
    }
    public function updateRoles(){
        global $wpdb;
        $postr = filter_input(INPUT_POST,'ac',FILTER_SANITIZE_NUMBER_INT,FILTER_REQUIRE_ARRAY);
        $submit = filter_input(INPUT_POST,'submit');
        $ft = filter_input(INPUT_POST,'form_type');
        
        $table_access = $wpdb->prefix . "ac_ac";
        $table_access_reserve = $wpdb->prefix . "ac_acr";
        
        $acHashe = $wpdb->get_var("SELECT sum(id) FROM `$table_access` WHERE access = 1 ");
        $acHasheR = $wpdb->get_var("SELECT sum(id) FROM `$table_access_reserve` WHERE access = 1 ");
        $acHasheF = $wpdb->get_var("SELECT sum(id) FROM `$table_access` WHERE 1 ");
        $acHasheRF = $wpdb->get_var("SELECT sum(id) FROM `$table_access_reserve` WHERE 1 ");
        
//        if($submit){
        if($ft=='ac'){
            $this->initRoles();
//            $acp = [];
//            foreach($postr as $ac){
//                
//            }
//            require_once ABSPATH . 'wp-admin/includes/user.php';
//            $roles = get_editable_roles();
            $args = array(
                'sort_order'   => 'ASC',
                'sort_column'  => 'ID',
                'post_type'    => 'page',
                'post_status'  => 'publish',
            ); 
            $pages = get_pages( $args );
    //        $this->n($this->pre($pages[0]));
//            $this->n($this->pre($_POST));

            $ac = [];

            /*
             * build new access hashe
             */
            $ctrAcHashe = 0;
            $newAcHashe = 0;
            $numAcHashe = 1;
            foreach ($pages as $p) {
                $pid = $p->ID;
                foreach ($this->roles as $rl => $rn) {
//                    $hitems[]=$rl['name'];
                    $access = 0;
                    $acId = $p->ID.'_'.$rn;
                    if(isset($postr[$acId])){
                        $access = 1;
                        $newAcHashe+=$numAcHashe;
                    }
                    $ctrAcHashe+=$numAcHashe;
                    $numAcHashe++;
//                    $acid = $wpdb->get_var("select id from  $table_access where page_id = $pid and role_id = $rn ");
//                    if($acid === null){
//                        $q = "insert into $table_access set access = $access , page_id = $pid, role_id = $rn ";
//                    }else{
//                        $q = "update $table_access set access = $access where page_id = $pid and role_id = $rn ";
//                    }
//                        $wpdb->query($q);
                }
            }
            $this->n(" access hashe = $acHashe new access hashe = $newAcHashe control hashe = $ctrAcHashe reserve hashe = $acHasheR");
            if($newAcHashe == 0){
                $this->n('Вы пытались заблокировать доступ всем, на все страницы сайта.');
                return false;
            }
            if($newAcHashe == $ctrAcHashe){
                $this->n('Вы пытались разрешить доступ всем, на все страницы сайта.');
                return false;
            }
            
            if($ctrAcHashe == $acHasheF && $newAcHashe == $acHashe){
                $this->n('Данные не изменились. Нечего сохранять.');
                return false;
            }
            
            if($acHasheR != $acHashe){
                /*
                 * резервные данные, для восстановления
                 */
                $q = "truncate table $table_access_reserve";
                $wpdb->query($q);
                $q = "insert into $table_access_reserve select * from $table_access ";
                $wpdb->query($q);
            }

            /*
             * save new access
             */
            foreach ($pages as $p) {
                $pid = $p->ID;
                foreach ($this->roles as $rl => $rn) {
//                    $hitems[]=$rl['name'];
                    $access = 0;
                    $acId = $p->ID.'_'.$rn;
                    if(isset($postr[$acId]))
                        $access = 1;
                    $acid = $wpdb->get_var("select id from  $table_access where page_id = $pid and role_id = $rn ");
                    if($acid === null){
                        $q = "insert into $table_access set access = $access , page_id = $pid, role_id = $rn ";
                    }else{
                        $q = "update $table_access set access = $access where page_id = $pid and role_id = $rn ";
                    }
                        $wpdb->query($q);
                }
            }
        }
    }
    public function acstyle(){
        ob_start();
        ?>
table.tac-h,
table.tac{
border-spacing: 0px;
}
.tac-h > tbody > tr:nth-of-type(odd) {
    /*background: #e0e0e0;*/
    background-color: #F5F7FA;
    background-color: rgba(0,0,0,.05);
    
}
.stripped-rows-h > div:nth-of-type(even) {
    /*background: #FFFFFF;*/
}
.stripped-rows-h > div:nth-of-type(odd) {
}
.tac > tbody > tr:nth-of-type(even) {
    background-color: #F5F7FA;
    background-color: rgba(0,0,0,.05);
}
.tac-h > tbody > tr:hover,
.tac > tbody > tr:hover
{
    background-color: rgba(0,0,0,.075);
}
.tac-h > tbody > tr td,
.tac > tbody > tr td
{
    padding: 0;
    height: 100%;
    min-height: 100%;
}
.acrlabel{
    display: block;
    width: 100%;
    min-width: 100%;
    height: 100%;
    min-height: 100%;
}
            <?php
        $st = ob_get_clean();
        return $this->f('style',$st);
    }
    /*  ==========  */

    /**
     * регистрируем страницы подменю в разделе "кабинет"
     * @global string $ccab_page
     */
    public function options() {
        $page = $this->page;
        $r_page = $this->rpage;
    //    $ parent slug
    //    $ page title
    //    $ menu title
    //    $ capability
    //    $ menu slug
    //    $ function
        
        
//		add_submenu_page(
//			'edit.php?post_type=shop-page-wp', // string $parent_slug
//			'Instructions', // string $page_title,
//			'Instructions', // string $menu_title,
//			'manage_options', // string $capability
//			'shop-page-wp-instructions', // string $menu_slug
//			array( 'Shop_Page_WP_Instructions', 'output_admin_page' )
//		);
        
//        add_submenu_page( $page, 'Shortcodes', 'Shortcodes', 'manage_options',
//            ''.$r_page.'/shortcodes.php', 'ccab_page_shortcodes_wrapper');  
//        add_submenu_page( $page, 'Параметры', 'Параметры', 'manage_options',
//            ''.$r_page.'/settings.php', 'ccab_page_settings_wrapper');  
        
    //    add_submenu_page( $ccab_page, 'Параметры 3', 'Параметры 3', 'manage_options',
    //        'p3_'.$ccab_page.'', 'true_option_page2');

    //        add_submenu_page($parent_slug, $page_title, $menu_title,
    //                $capability, $menu_slug, $function);

    }
}
