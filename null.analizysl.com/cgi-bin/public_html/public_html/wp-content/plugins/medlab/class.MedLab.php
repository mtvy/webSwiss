<?php

/* 
 * class.MedLab.php
 */

include 'class.MedLabAuth.php';
//include 'class.AllegroAuth.php';
//include 'class.AllegroAdmin.php';
//include 'trait.AllegroHtml.php';
//include 'trait.AllegroCatalog.php';
//include 'trait.AllegroCrumbs.php';
//include 'trait.AllegroProducts.php';
//include 'trait.AllegroProduct.php';

include 'trait.MedLabShortcodes.php';
include 'trait.MedLabRequestBuilder.php';
include 'trait.MedLabDictionaries.php';
//include 'trait.AllegroShortcodes.php';
//include 'trait.Chop2Ajax.php';
//include 'trait.Chop2Process.php';
//include 'trait.Chop2AdminProfile.php';
include 'trait.MLDSProducts.php';
include 'trait.MLAjax.php';

class MedLab extends MedLabAuth{

    use 
//            AllegroAdmin,
//            AllegroHtml,
//            AllegroCatalog,
//            AllegroCrumbs,
//            AllegroProducts,
//            AllegroProduct,
    
//            Chop2AdminProfile,
//            Chop2Ajax,
//            Chop2Process,
            MLAjax,
            MedLabRequestBuilder,
            MedLabDictionaries,
            MedLabShortcodes,
            MLDSProducts
            ;
    
    private static $instance = null;
	private static $initiated = false;

    public function __construct() {
//        parent::__construct();
//        $this->init_xpath_title();
//        $this->init_options();
//        $this->auth($this->clId,$this->clSec);
//        $this->auth($this->clId,$this->clSec);
    }
	public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}

//		if ( isset( $_POST['action'] ) && $_POST['action'] == 'enter-key' ) {
//			self::enter_api_key();
//		}
//
//		if ( ! empty( $_GET['akismet_comment_form_privacy_notice'] ) && empty( $_GET['settings-updated']) ) {
//			self::set_form_privacy_notice_option( $_GET['akismet_comment_form_privacy_notice'] );
//		}
	}

	public static function init_hooks() {
        $alleg = new MedLab();
		self::$instance = $alleg;
		self::$initiated = true;
//        add_action( 'after_setup_theme', 'lend_setup' );
//        add_filter('the_content', [ 'MedLab', '_content']);
        add_filter('the_content', [ $alleg, 'content']);
//        add_action( 'widgets_init', ['Allegro','register_wgts_area'],1  );
        $alleg->init_shortcodes();
        $alleg->init_roles();
        $alleg->init_dictionaries();
        $alleg->process();
        
	}
    
    public function process(){
        ini_set("display_errors", "1");
        ini_set("display_startup_errors", "1");
        ini_set('error_reporting', E_ALL);
        
        $ftype = filter_input(INPUT_POST, 'form-type', FILTER_SANITIZE_STRING);
        if($ftype=='query_get_blank')$this->prc_query_get_blank();
    }
    public function prc_query_get_blank(){
        global $ht;
        $oid = filter_input(INPUT_POST, 'oid', FILTER_SANITIZE_NUMBER_INT, ['options' =>['default'=>0]]);
        $bid = filter_input(INPUT_POST, 'bid', FILTER_SANITIZE_NUMBER_INT, ['options' =>['default'=>0]]);
        $bguid = filter_input(INPUT_POST, 'bguid', FILTER_SANITIZE_STRING, ['options' =>['default'=>0]]);
        $btn_name = filter_input(INPUT_POST, 'btn_name', FILTER_SANITIZE_STRING, ['options' =>['default'=>0]]);
        $lgid = $ht->postget('lgid',false,FILTER_VALIDATE_INT);
        
//        echo $ht->pre($_GET);
//        echo $ht->pre($_POST);
        $user = wp_get_current_user();
        if($oid  && $user->ID ){
            // параметры по умолчанию
            $args =[
        //        'author'  => $user->ID,
//                'numberposts' => 1000,
//                'offset'    => 0,
            //	'numberposts' => $count,
            //	'offset'    => $offset,
            //	'category'    => 0,
//                'orderby'     => 'date',
//                'order'       => 'DESC',
                'include'     => [$oid],
            //	'exclude'     => array(),
                'meta_key'    => '',
                'meta_value'  =>'',
                'meta_query'   => [

                ],
                'post_type'   => 'dsorder',
                'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
            ] ;
            $incposts = wp_parse_id_list( $args['include'] );
            $args['post__in'] = $incposts;


            $roless_provided = [];
            $roless_provided ['administrator'] ='administrator';
            $roless_provided ['ml_administrator'] ='ml_administrator';
            $roless_provided ['ml_manager'] ='ml_manager';
            $roless_provided ['ml_doctor'] ='ml_doctor';
            $roless_provided ['ml_procedurecab'] ='ml_procedurecab';
            if(current_user_can( 'manage_options' ) ||
                count( array_intersect($roless_provided, (array) $user->roles ) ) >0 ){

    //            $_patientid = filter_input(INPUT_GET, 'pid', FILTER_SANITIZE_NUMBER_INT);
    //            if($_patientid)$patientid=$_patientid;
    //
    //            if($patientid>0){
    //                $_user = get_user_by('ID',$patientid);
    //                if($_user!==false && $_user->exists()){
    //                    $patientid = $_user->ID;
    //                }
    //                if(is_wp_error($_user) || $_user===false){
    //                    $patientid=0;
    //                }
    //            }

            }else{
                $args['meta_query'][] = 
                    [
                        'key' => 'dso_puid',
                        'value' => $user->ID,
                        'compare' => '=',
                    ];
            }
    
            $count =  0;
            $query = new WP_Query( $args );
            $count =  $query->found_posts;
            //add_log($count);
            if(!is_user_logged_in())
                $count =  0;

            if( !$count ) return '';
            $dsorder = false;
            if($count>0){
                $_posts = get_posts( $args );
                $dsorder = array_shift($_posts);
                
                $LisId =  get_post_meta( $dsorder->ID, 'dso_query_id', true );
                $Nr =  get_post_meta( $dsorder->ID, 'field_dso_query_nr', true );
//                echo $ht->pre($dsorder);

                $q = 'query-referral-results';
//                    $q = 'query-patient-referral-results';
                $atts = [];
                $atts['is_show_test'] = true;

                $timer = microtime(1);
                $query=[];
                $query['LisId'] = $LisId;
                $query['MisId'] = $oid;
                $query['Nr'] = $Nr;
                $atts['query'] = $query;
//                    $data_ = MedLab::_queryBuild($q,$atts);
                
                if($lgid>0){
                    set_ml_access_by_group($lgid);
                }
                $data_ = MedLab::_queryBuild($q,$atts);
                
                if($lgid>0){
                    set_ml_access_by_group($lgid);
                }
                $data_ = MedLab::_queryBuild($q,$atts);
                unset($atts['is_show_test']);
                
                if($lgid>0){
                    set_ml_access_by_group($lgid);
                }
                $data_ = MedLab::_queryBuild($q,$atts);
                
            

                $answer = doPostRequest($data_);
                $xml = simplexml_load_string($answer);
                $qrootAtt = MedLab::_buildAttrs($xml);
                $timer2 = microtime(1);
                $mics = $timer2 - $timer;
//                    echo $ht->pre($xml);
                $Referral = MedLab::_buildAttrs($xml->Referral);
//            echo '============';

                $answer_count = count($xml->Blanks->Item);
                if($answer_count > 0){
                    $results = $xml->Results;
                    foreach($xml->Blanks->Item as $bi_){
                        $b_ = MedLab::_buildAttrs($bi_);
                        if($b_['Done'] == 'true'){
                            $blankID = $b_['BlankId'];
                            $blankGUID = $b_['BlankGUID'];
                            if($blankID == $bid){
                                $FileName = strtr($b_['FileName'],['\\'=>'-']);
                                $q = 'query-blank-file';
                                $atts = [];
                                $query=[];
                                $query['BlankId'] = $blankID;
                                $query['BlankGUID'] = $blankGUID;
                                $atts['query'] = $query;
                
                if($lgid>0){
                    set_ml_access_by_group($lgid);
                }
                                $data_ = MedLab::_queryBuild($q,$atts);

                                $answer = doPostRequest($data_);
                                ob_start();
                                    $xml = simplexml_load_string($answer);
                                    $qrootAtt = MedLab::_buildAttrs($xml);
                                    $err = ob_get_clean();
            if(!$err && isset($qrootAtt['Error'])){
                
                $div_error =  DShop::_div(DShop::_div(DShop::_div(
                    'Заявка: '.$LisId.'. '.
                    'Ошибка: <br/>'.$qrootAtt['Error'].
//                    ' <br/> line: '.__LINE__.
                    ''
                    ,'alert alert-danger'),'col-12'),'row');
                echo $div_error;
                
//                $div_error =  DShop::_div(DShop::_div(DShop::_div(
//                        dev::pre($lgid,'$labGroup',0)
//                    ,'alert alert-danger'),'col-12'),'row');
//                echo $div_error;
//                
//                $div_error =  DShop::_div(DShop::_div(DShop::_div(
//                        dev::pre($qrootAtt,'$labGroup',0)
//                    ,'alert alert-danger'),'col-12'),'row');
//                echo $div_error;
                
//                dev::pre($item_,'$item_');
//                continue;
                return;
            }

                                header("Content-type:application/pdf");

                                // It will be called downloaded.pdf
                                header("Content-Disposition:attachment;filename=$FileName");
                                echo $answer;
                                exit();
                                break;
                            }
                        }
                    }
                }
            }
        }
        echo $ht->pre('нет результатов');
    }
    /*
     * регистрирует область вывода
     * сайдбар фильтра, категорий
     */
    public static function register_wgts_area(){
        static $i = 0;  
//        register_sidebar( array(
//            'name'          => sprintf(__('Sidebar %d'), $i ),
//            'id'            => "sidebar-$i",
//            'description'   => 'Фильтр товаров',
//            'class'         => 'products_filter',
//            'before_widget' => '<div class="row"><div id="%1$s" class="widget %2$s col-md-12">',
//            'after_widget'  => "</li>\n",
//            'before_title'  => '<h2 class="widgettitle">',
//            'after_title'   => "</div></div>\n",
//        ) );
//        $i++;
    }
    public static function _content($content=''){
//add_log('test log 2');
//        ob_start();showLogInfo();$content=  ob_get_clean().$content;
        // $content=  '__log__'.$content;
        return self::$instance->content($content);
//        if(strpos($content,'__content__')>0)
//            return self::$instance->content($content);
//        else
//            return $content;
	}
//    public function process(){
//	}
    /*
     * 
     */
    public function wgt_category($echo=false){
        
//        $prod_debug=get_option('prod_parser_debug','');
//        if(current_user_can('manage_options'))// manage_options - права администратора
//            $this->prod_debug = $prod_debug;
        
//        $catId = $this->get('categ');
//        $this->cat=$this->get('categ',$this->cat); // ?
//        $this->getCategories($this->cat);
//        $this->getCategories($catId);
//        $cat = $this->getCat() ;
        if($echo)
            echo $cat;
        return $cat;
    }
    public function content($content=''){
        $out='';
        $out = $out.$content;
        
        $role = get_role( 'comm_moderator' ); // указываем роль, которая нам нужна
        
//        require_once ABSPATH . 'wp-admin/includes/user.php';
//        $role = get_editable_roles(  ); // указываем роль, которая нам нужна
        
//        $test = print_r( $role ,1); // так можно вывести содержимое объекта
//        $out.= '<pre>'.$test.'</pre>';
        
        $out = do_shortcode( $out );
        return $out;
    }
    private $ml_roles = [];
    public function init_roles(){
        require_once ABSPATH . 'wp-admin/includes/user.php';
        $roles = get_editable_roles(  ); // указываем роль, которая нам нужна
        $this->ml_roles = $roles;
        
        $this->init_roles_caps_list();
        
        $role_list = [];
        
        $role_list['ml_agent'] = 'Представитель';
        $role_list['ml_patient'] = 'Пациент';
        $role_list['ml_doctor'] = 'Врач';
        $role_list['ml_manager'] = 'Менеджер';
        $role_list['ml_administrator'] = 'Администратор MedLab';
        $role_list['ml_director'] = 'Директор';
//        procedure cabinet`s man
        $role_list['ml_procedurecab'] = 'Пользователь процедурного кабинета';
        $role_list['wh_supervisor'] = 'Руководитель';
        $role_list['wh_merchandiser'] = 'Товаровед';
        $role_list['wh_financier'] = 'Финансист';
        $role_list['wh_doctorlaborant'] = 'Врач-лаборант';
        $role_list['wh_adminproccab'] = 'Администратора процедурного кабинета';
        $role_list['wh_thirdparty'] = 'Третьи лица';
//        $role_list[''] = '';
        
        $caps = [];
//        $caps['ml_access_cabinet'] = true;
        
        $def_caps = get_role( 'contributor' ); //Admin	Editor	Author	Contributor
        $caps += $def_caps->capabilities;
        foreach ($role_list as $role => $name) {
            if(!isset($roles[$role])){
//                $this->set_role_($rsole);
//                $capabilities = $this->caps_list[$role];
                $capabilities = $caps;
                add_role($role, $name, $capabilities);
            }
//            $this->check_role_($role);
        }
        
        global $have_menu;
        $have_menu = [];
//        $have_menu[''] = 1;
//        $have_menu[''] = 1;
        
        $user_id = get_current_user_id();
        // таким образом мы можем взять конкретного пользователя
        $user = new WP_User( $user_id );
        if($user_id && $user){
            // инициализация доступа к меню согласно возможностям роли
            if($user->has_cap( 'ml_access_cabinet' )){
                $have_menu['ml_access_cabinet'] = 1;
            }
            if($user->has_cap( 'ml_access_menu_u_l' )){
                $have_menu['ml_access_menu_u_l'] = 1;
            }
            if($user->has_cap( 'ml_access_menu_u_in' )){
//                $have_menu['ml_access_menu_u_in'] = 1;
            }
            if($user->has_cap( 'ml_access_menu_d_l' )){
                $have_menu['ml_access_menu_d_l'] = 1;
            }
            if($user->has_cap( 'ml_access_menu_d_in' )){
//                $have_menu['ml_access_menu_d_in'] = 1;
            }
        }
        if(current_user_can('manage_options')){
//                $have_menu['ml_access_cabinet'] = 1;
//                $have_menu['ml_access_menu_u_l'] = 1;
//                $have_menu['ml_access_menu_u_in'] = 1;
//                $have_menu['ml_access_menu_d_l'] = 1;
//                $have_menu['ml_access_menu_d_in'] = 1;
//            $caps = [];
//            $caps['ml_access_cabinet'] = true;
//            $caps['ml_access_menu_u_l'] = true;
//            $caps['ml_access_menu_u_in'] = true;
//            $caps['ml_access_menu_d_l'] = true;
//            $caps['ml_access_menu_d_in'] = true;
        }
//add_log($have_menu);
    }
    public static function _show_menu($menu_type = '' , $uset = 0 ){
        self::init();
        return self::$instance->show_menu($menu_type,$uset);
    }
    public function show_menu($menu_type = '' , $uset = 0 ){
        global $have_menu;
        
        
        $user_id = get_current_user_id();
        // таким образом мы можем взять конкретного пользователя
        $user = new WP_User( $user_id );
        if($user_id && $user){
            // инициализация доступа к меню согласно возможностям роли
            switch($menu_type){
                case 'cabinet':
                    if($user->has_cap( 'ml_access_cabinet' )){
                        $have_menu['ml_access_cabinet'] = 1;
                        if($uset)unset($have_menu['ml_access_cabinet']);
                    }
                    break;
                case 'user_left':
                    if($user->has_cap( 'ml_access_menu_u_l' )){
                        $have_menu['ml_access_menu_u_l'] = 1;
                        if($uset)unset($have_menu['ml_access_menu_u_l']);
                    }
                    break;
                case 'user_inside':
                    if($user->has_cap( 'ml_access_menu_u_in' )){
                        $have_menu['ml_access_menu_u_in'] = 1;
                        if($uset)unset($have_menu['ml_access_menu_u_in']);
                    }
                    break;
                case 'doctor_left':
                    if($user->has_cap( 'ml_access_menu_d_l' )){
                        $have_menu['ml_access_menu_d_l'] = 1;
                        if($uset)unset($have_menu['ml_access_menu_d_l']);
                    }
                    break;
                case 'doctor_inside':
                    if($user->has_cap( 'ml_access_menu_d_in' )){
                        $have_menu['ml_access_menu_d_in'] = 1;
                        if($uset)unset($have_menu['ml_access_menu_d_in']);
                    }
                    break;
            }
        }
    }
    public function set_role_($role=''){
        switch($role){
            case 'ml_agent':
                $name = 'Представитель';
                $capabilities = $this->caps_list[$role];
                add_role($role, $name, $capabilities);
                break;
            case 'ml_patient':
//                $author = get_role( 'author' );
//                $capabilities = [];
//                $capabilities = $author->capabilities;
//                $capabilities[''] = true;
                
                $name = 'Пациент';
                $capabilities = $this->caps_list[$role];
                add_role($role, $name, $capabilities);
                break;
            case 'ml_doctor':
                $name = 'Врач';
                $capabilities = $this->caps_list[$role];
                add_role($role, $name, $capabilities);
                break;
            case 'ml_manager':
                $name = 'Менеджер';
                $capabilities = $this->caps_list[$role];
                add_role($role, $name, $capabilities);
                break;
            case 'ml_administrator':
                $name = 'Администратор MedLab';
                $capabilities = $this->caps_list[$role];
                add_role($role, $name, $capabilities);
                break;
            case 'ml_director':
                $name = 'Директор';
                $capabilities = $this->caps_list[$role];
                add_role($role, $name, $capabilities);
                break;
            case 'ml_procedurecab':
                $name = 'Пользователь процедурного кабинета';
                $capabilities = $this->caps_list[$role];
                add_role($role, $name, $capabilities);
                break;
            
        }
//        https://misha.blog/wordpress/roli-i-vozmozhnosti.html
//        remove_role()
        
    }
    //Гость
    //Пациент
    //Врач
    //Менеджер
    //Администратор
    //Директор
    //Админ (разарботчик)
    // установка возможности ролей
    private $caps_list = [];
    public function init_roles_caps_list(){
        $caps_list['ml_agent'] = [];
        $caps_list['ml_patient'] = [];
        $caps_list['ml_doctor'] = [];
        $caps_list['ml_manager'] = [];
        $caps_list['ml_director'] = [];
        $caps_list['ml_administrator'] = [];
        $caps_list['ml_procedurecab'] = [];
        
        $caps = [];
        $caps['ml_access_cabinet'] = true;
        
        $def_caps = get_role( 'contributor' ); //Admin	Editor	Author	Contributor
        $caps += $def_caps->capabilities;
        
        
        $caps = [];
        $caps_list['ml_agent'] += $caps;
        
        $caps = [];
        $caps['ml_access_cabinet'] = true;
        $caps['ml_access_menu_u_l'] = true;
        $caps['ml_access_menu_u_in'] = true;
        $caps_list['ml_patient'] += $caps;
        
        $caps = [];
        $caps['ml_access_cabinet'] = true;
        $caps['ml_access_menu_d_l'] = true;
        $caps['ml_access_menu_d_in'] = true;
        $caps_list['ml_doctor'] += $caps;
        
        $caps = [];
        $caps['ml_access_cabinet'] = true;
        $caps_list['ml_manager'] += $caps;
        
        $caps = [];
        $caps['ml_access_cabinet'] = true;
        $caps_list['ml_director'] += $caps;
        
        $caps = [];
        $caps['ml_access_cabinet'] = true;
        $caps_list['ml_administrator'] += $caps;
        
        $caps = [];
        $caps['ml_access_cabinet'] = true;
        $caps['ml_access_menu_d_l'] = true;
        $caps['ml_access_menu_d_in'] = true;
        $caps_list['ml_procedurecab'] += $caps;
        
        $this->caps_list = $caps_list;
        
    }
    
    public function check_role_($_role=''){
        
//        remove_role($_role);
//        require_once ABSPATH . 'wp-admin/includes/user.php';
//        $roles = get_editable_roles(  ); // указываем роль, которая нам нужна
//        $this->ml_roles = $roles;
        
        global $ht, $aca;
        $update_all = 0;
        if($update_all || !isset($this->ml_roles[$_role])){
            $aca->n('update: '.$_role);
            $this->set_role_($_role);
        }
//        add_cap() и remove_cap() 
        $r = get_role( $_role );
        $caps = $r->capabilities;
	// $role = new WP_User( $user_id ); таким образом мы можем взять конкретного пользователя
        foreach ($this->caps_list[$_role] as $key => $value) {
            if(!isset($caps[$key])){
                $r->add_cap($key);
            }
        }
        if($update_all){
            foreach($caps as $cap=>$c){
                if(!isset($this->caps_list[$_role][$cap])){
                    $r->remove_cap($cap);
                    $aca->n('remove_cap: '.$_role.': '.$cap);
                }
            }
            $aca->n( $ht->pre( get_role( $_role ) ) );
        }
    }
    public static function _clear_sch(){
        self::init();
        return self::$instance->clear_sch();
    }
    public function clear_sch(){  //  clear search

//    $search = filter_input(INPUT_GET, 'sch',[
//        'filter'=>FILTER_SANITIZE_STRING,
//        'flags'=>FILTER_FLAG_NO_ENCODE_QUOTES
//        |FILTER_FLAG_STRIP_LOW
//        |FILTER_FLAG_STRIP_HIGH
//        |FILTER_FLAG_STRIP_BACKTICK
//        |FILTER_FLAG_ENCODE_LOW
//        |FILTER_FLAG_ENCODE_HIGH
//        |FILTER_FLAG_ENCODE_AMP
//        ]);
    $search = filter_input(INPUT_GET, 'sch',FILTER_SANITIZE_STRIPPED
//            ,
//        FILTER_FLAG_NO_ENCODE_QUOTES
//        |FILTER_FLAG_STRIP_LOW
//        |FILTER_FLAG_STRIP_HIGH
//        |FILTER_FLAG_STRIP_BACKTICK
//        |FILTER_FLAG_ENCODE_LOW
//        |FILTER_FLAG_ENCODE_HIGH
//        |FILTER_FLAG_ENCODE_AMP
        );
    if($search===false || $search===null || $search==='')$search='';
    $rep=[];
    $rep["'"]='';
    $rep['"']='';
    $rep['&#39;']='';
    $rep["&#34;"]='';
    $rep["'&quot;"]='';
    $rep['<']='';
    $rep['>']='';
    $search = trim($search);
    $search1 = strtr($search,$rep);
    
    $search = filter_input(INPUT_POST, 'sch',FILTER_SANITIZE_STRIPPED);
    if($search===false || $search===null || $search==='')$search='';
    $search = trim($search);
    $search = strtr($search,$rep);
    return $search?$search:$search1;
    }
    public static function __callStatic($name, $arguments) {
//        self::_init();
        self::init();
        $_name = $name;
        if(strlen($name)>1 && $name[0] === '_'){
            $name = str_split($name);
            unset($name[0]);
            $name = implode('',$name);
        }
//        add_log($arguments);
        if(count($arguments)==1)
        return self::$instance->$name($arguments[0]);
        if(count($arguments)==2)
        return self::$instance->$name($arguments[0],$arguments[1]);
        if(count($arguments)==3)
        return self::$instance->$name($arguments[0],$arguments[1],$arguments[2]);
        if(count($arguments)==4)
        return self::$instance->$name($arguments[0],$arguments[1],$arguments[2],$arguments[3]);
        return self::$instance->$name($arguments);
//        return self::$instance->$name(extract ($arguments));
//        return self::$instance->$name($arguments);
//        add_log($name);
    }
}

//Звонит ПриватБанк
//+38 (056) 733 50 40
//Ответьте на звонок и следуйте инструкции