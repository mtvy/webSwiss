<?php

/* 
 * admin user profile page
 */


//https://hostenko.com/wpcafe/tutorials/kak-dobavit-novoe-pole-v-profil-polzovatelya-wordpress/
add_filter('user_contactmethods', 'my_user_contactmethods');
 
function my_user_contactmethods($user_contactmethods){
//1. Название организации
//2. ФИО руководителя
//3. Реквизиты организации
//4. Телефон организации
//5. Почта организации
//6. Личный номер телефона для связи
 
  $user_contactmethods['second_name'] = 'Отчество';
//  $user_contactmethods['org_reqv'] = 'Реквизиты организации';
//  $user_contactmethods['org_phone'] = 'Телефон организации';
//  $user_contactmethods['org_email'] = 'Почта организации';
//  $user_contactmethods['phone'] = 'Личный номер телефона для связи';
//  echo '<pre>'.print_r($user_contactmethods,1).'</pre>';
//  echo get_user_meta(1, 'twitter', true);

 
  return $user_contactmethods;
}


/*================================*/
/*================================*/
/*================================*/

//https://bloggood.ru/wordpress/dobavlyaem-dopolnitelnye-polya-v-profil-polzovatelya-wordpress.html/
//wp-admin/user-edit.php

/* добавление поля в профиле*/
//add_action( 'show_user_profile', 'add_extra_social_links' );
//add_action( 'edit_user_profile', 'add_extra_social_links' );
//
//add_action( 'show_user_profile', 'add_extra_social_links' );
//add_action( 'edit_user_profile', 'add_extra_social_links' );

add_action( 'show_user_profile', ['ProfileFields','_init'] );
add_action( 'edit_user_profile', ['ProfileFields','_init'] );

add_filter( 'user_contactmethods', ['ProfileFields','_user_contactmethods'], 10, 1 );
add_filter( 'manage_users_columns', ['ProfileFields','_manage_users_columns'] );
add_filter( 'manage_users_custom_column', ['ProfileFields','_manage_users_custom_column'], 10, 3 );
add_filter( 'user_row_actions', ['ProfileFields','_user_row_actions'] , 10, 2 );

add_filter( 'manage_users_sortable_columns', ['ProfileFields','_manage_users_sortable_columns'] );

add_action( "pre_get_users", ['ProfileFields','_pre_get_users'] , 10, 1 );
//add_action( 'admin_footer-users.php', 'print_jquery_wpse_117481' );

function print_jquery_wpse_117481() 
{
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) 
    {
        $('.my-class').click(function(e) 
        {
            console.log( 'Is checked: ' + $(this).is(':checked') );
            console.log( 'This ID: ' + $(this).attr('id') );

            var a_href_approval_id = $(this).data('approval');
            var a_href_final = $( a_href_approval_id ).attr('href');
            console.log( 'Manipulate this attribute: ' + a_href_final );
        });
    });
    </script>
    <?php
}
//add_action( 'pre_user_query', 'filter_users_wpse_10742' );

function filter_users_wpse_10742( $user_search ) 
{
    global $pagenow;
    if( 'users.php' != $pagenow)
        return;

    $user = wp_get_current_user();

    if ( $user->roles[0] != 'administrator' ) 
    {
        global $wpdb;

        $user_search->query_where =
        str_replace('WHERE 1=1',
            "WHERE 1=1 AND {$wpdb->users}.ID IN (
                 SELECT {$wpdb->usermeta}.user_id FROM $wpdb->usermeta
                    WHERE {$wpdb->usermeta}.meta_key = '{$wpdb->prefix}user_level'
                    AND {$wpdb->usermeta}.meta_value != 10)",
            $user_search->query_where
        );
    }
}

class ProfileFields{

    public static function _user_contactmethods( $contactmethods ) {
        $contactmethods['phone'] = 'Телефон';
//        $contactmethods['name'] = 'Ф.И.О.';
        $contactmethods['fio'] = 'Ф.И.О.';
//        $contactmethods['card'] = 'Карта';
//        $contactmethods['discont'] = 'Скидка';
//        $contactmethods['id'] = 'Id';
//        role=ml_procedurecab
//        $role = filter_input(INPUT_GET, 'role');
//        if($role && $role == 'ml_procedurecab'){
//            $contactmethods['lab_group'] = 'lab group';
//        }
        return $contactmethods;
    }

    public static function _manage_users_sortable_columns( $columns ) {
        $columns['id'] = 'id';
        $columns['card'] = 'card';

        //To make a column 'un-sortable' remove it from the array unset($columns['date']);

        return $columns;
    }
    public static function _manage_users_columns( $columns ) {
//        $columns['phone'] = 'Телефон';
//        $columns['fio'] = 'Ф.И.О.';
        unset($columns['name']);
//        foreach($column as $k=>$v){
//            $column[$k] = $v.' ['.$k.']';
//        }
		$num = 2; // после какой по счету колонки вставлять новые
        $role = filter_input(INPUT_GET, 'role');
        if($role && $role == 'ml_procedurecab'){
            $new_columns = array( 'lab_group' => 'Группа' );
            $columns = array_slice( $columns, 0, $num ) + $new_columns
                    + array_slice( $columns, $num );
        }
        
		$new_columns = array( 'payd' => 'Потратил' );
        $columns = array_slice( $columns, 0, $num ) + $new_columns
                + array_slice( $columns, $num );
        
		$new_columns = array( 'discont' => 'Скидка' );
        $columns = array_slice( $columns, 0, $num ) + $new_columns
                + array_slice( $columns, $num );
        
		$new_columns = array( 'card' => 'Карта' );
        $columns = array_slice( $columns, 0, $num ) + $new_columns
                + array_slice( $columns, $num );
        
		$new_columns = array( 'phone' => 'Телефон' );
        $columns = array_slice( $columns, 0, $num ) + $new_columns
                + array_slice( $columns, $num );
        
		$new_columns = array( 'fio' => 'Ф.И.О.' );
        $columns = array_slice( $columns, 0, $num ) + $new_columns
                + array_slice( $columns, $num );
        
		$num = 2; // после какой по счету колонки вставлять новые
		$new_columns = array( 'id' => 'ID' );
        $columns = array_slice( $columns, 0, $num ) + $new_columns
                + array_slice( $columns, $num );
        return $columns;
    }

    public static function _manage_users_custom_column( $val, $column_name, $user_id ) {
        switch ($column_name) {
            case 'fio' :
                $val .= ' '.get_the_author_meta( 'last_name', $user_id )
                    .' '.get_the_author_meta( 'first_name', $user_id )
                    .' '.get_the_author_meta( 'second_name', $user_id );
//                $field[] = 'last_name';
//                $field[] = 'first_name';
//                $field[] = 'second_name';
                break;
            case 'phone' :
                $val .= get_the_author_meta( 'phone', $user_id );
                break;
            case 'card' :
                $val .= get_the_author_meta( 'card_numer', $user_id );
//                $card_numer = (int) get_user_meta($user_id, 'card_numer',1);
//                $val .= ' '.sprintf("%09d",$card_numer);
//                $val .= ' ';
//                $val .=  get_user_meta($user_id, 'card_numer',1);
                break;
            case 'discont' :
//                $val .= get_the_author_meta( 'discont_perc', $user_id );
                $ds = new MedLabCardBonus();
                $val .= $ds->discont( $user_id, false );
                break;
            case 'id' :
                $val .= $user_id;
                break;
            case 'payd' :
                global $wpdb;
                $join = [];
                $where=[];
                $where[]=" pm.meta_value > '0'";
                $card_numer = (int) get_user_meta($user_id, 'card_numer',1);
                $card_numer =  get_user_meta($user_id, 'card_numer',1);
                if($card_numer>0){
                    $where[]=" pm.meta_value in( select um.`user_id` from $wpdb->usermeta um where um.meta_key = 'card_numer' and um.meta_value = '$card_numer')";
                    $join=implode("\n    ",$join);
                    $where=implode("\nand ",$where);
                    if($where) $where = 'and '.$where;
                    $order = '';
                    $q = "SELECT sum(pc.meta_value) FROM $wpdb->postmeta pm
                        LEFT join $wpdb->postmeta pc on pc.post_id = pm.post_id
                        LEFT join $wpdb->postmeta ps on ps.post_id = pm.post_id
                        $join
                        WHERE 1
                        and pm.meta_key = 'dso_puid'
                        and pc.meta_key = 'dso_cost'
                        and ps.meta_key = 'dso_status'
                        and ( ps.meta_value = 'payd' or ps.meta_value = 'query_sent' )
                        $where
                        /*order by $order*/
                            ";
                    $dso_sum = $wpdb->get_var($q);
                    $val .= $dso_sum;//. $q;
                }else{
                    $where[]=" pm.meta_value = '$user_id'";
                    $join=implode("\n    ",$join);
                    $where=implode("\nand ",$where);
                    $order = '';
                    $q = "SELECT sum(pc.meta_value) FROM $wpdb->postmeta pm
                        LEFT join $wpdb->postmeta pc on pc.post_id = pm.post_id
                        LEFT join $wpdb->postmeta ps on ps.post_id = pm.post_id
                        $join
                        WHERE 1
                        and pm.meta_key = 'dso_puid'
                        and pc.meta_key = 'dso_cost'
                        and ps.meta_key = 'dso_status'
                        and ( ps.meta_value = 'payd' or ps.meta_value = 'query_sent' )
                        and pm.meta_value = '$user_id'
                        /*order by $order*/
                            ";
                    $dso_sum = $wpdb->get_var($q);
                    $val .= ''. $dso_sum;//. $q;
                }
                break;
            case 'lab_group' :
////                $sel=self::get_lab_group_list();s
//                $sel=MedLabLabGroupFields::get_lab_group_list();
//                $g= get_the_author_meta( 'lab_group', $user_id );
//                if(isset($sel[$g]))
//                    $val .= $sel[$g];
                break;
            default:
                break;
        }
//        $val.=' ['.$column_name.']';
        return $val;
    }
    public static function _pre_get_users( $WP_User_Query ) {

        if ( isset( $WP_User_Query->query_vars["orderby"] ) ) {
            switch ($WP_User_Query->query_vars["orderby"]) {
                case 'id' :
                    $WP_User_Query->query_vars["orderby"] = "ID";
                    break;
                case 'card' :
                    $WP_User_Query->query_vars["meta_key"] = "card_numer";
                    $WP_User_Query->query_vars["orderby"] = "meta_value";
                    break;
                default:
                    break;
            }
        }
    }
    public static function _user_row_actions($actions, $user_object){
//        $current_user = wp_get_current_user();
//        $u = $user_object->ID;
//        if ( $current_user->ID != $user_object->ID ) {
//            /*if ( in_array( 'pending', (array) $user_object->roles ) ) {
//                switch ( get_option( 'type' ) ) {
//                    case 'admin' :*/
//                        // Add "Approve" link
//                        $actions['approve-X-user'] = sprintf( '<a href="%1$s">%2$s</a>',
//                            add_query_arg( 'wp_http_referer',
//                                urlencode( esc_url( stripslashes( $_SERVER['REQUEST_URI'] ) ) ),
//                                wp_nonce_url( "users.php?action=approve&amp;user=$user_object->ID&amp;dbem_X_member_f=1", 'approve-X-user' ) 
//                            ),
//                            __( 'Approve with changes', 'theme-my-login' )
//                        );
//                        //break;
//                //}
//            //}
//        }
        return $actions;
        echo "<pre>";
        print "X row";
        print_r($actions);
        print_r($user_object);
        echo "</pre>";
        return $actions;
        //exit;
    }
    private static $instance = null;
	private static $initiated = false;
    public function __construct() {
        ;
    }
    public function __call($name, $arguments) {
        ;
    }
    public static function __callStatic($name, $arguments) {
        ;
    }
    public function __get($name) {
        ;
    }
    public function __invoke() {
        ;
    }
    public function __set($name, $value) {
        ;
    }
    
	public static function _init_save($user_id) {
        self::init_save($user_id);
		if ( ! self::$initiated ) {
		}
    }

	public static function init_save($user_id) {
        $alleg = false;
        $alleg = new ProfileFields();
        $user = (object)['ID'=>$user_id];
        
        $profile_list = [];
//        $profile_list[]=['class'=>'','method'=>'','inited'=>true,'type'=>'class','isstatic'=>false,'weigh'=>6];
        $profile_list[]=['class'=>'ProfileFields','object'=>$alleg,'method'=>'save','inited'=>true,'type'=>'class','isstatic'=>false,'weigh'=>200];
        $profile_list[]=['class'=>'ProfileFields','object'=>$alleg,'method'=>'initFieldsGeneral','inited'=>true,'type'=>'class','isstatic'=>false,'weigh'=>1];
        $profile_list[]=['class'=>'ProfileFields','object'=>$alleg,'method'=>'initFieldsDoctor','inited'=>true,'type'=>'class','isstatic'=>false,'weigh'=>2];
        $profile_list[]=['class'=>'ProfileFields','object'=>$alleg,'method'=>'initFieldsRequisites','inited'=>true,'type'=>'class','isstatic'=>false,'weigh'=>3];
        $profile_list[]=['class'=>'ProfileFields','object'=>$alleg,'method'=>'initFieldsPatient','inited'=>true,'type'=>'class','isstatic'=>false,'weigh'=>4];
        
//        $alleg->initFieldsGeneral($user);
//        $alleg->initFieldsDoctor($user);
//        $alleg->initFieldsRequisites($user);
//        $alleg->initFieldsPatient($user);
//        $alleg->save($user_id);
        
        $profile_list = apply_filters('ds_admin_profile_block_list',$profile_list,$alleg,$user);
        do_action('ds_admin_profile_list_init',$profile_list,$alleg,$user);
        
        $list=[];
        
        // applay sort by weigh
        foreach($profile_list as $item){
            $weigh = 6;
            if(isset($item['weigh']))
                $weigh = $item['weigh'];
            if(!isset($list[$weigh]))$list[$weigh]=[];
            $list[$weigh][]=$item;
        }
        ksort($list);
        $profile_list = [];
        foreach($list as $items){
            foreach($items as $item){
                $profile_list[]=$item;
            }
        }
        
        $classes_inited = [];
        $classes = [];
        $classes['ProfileFields'] = $alleg;
        
        // applay list
        foreach($profile_list as $item){
            $type = 'type';
            if(isset($item['type']))
                $type = $item['type'];
            switch($type){
                case 'class':
                    $isstatic = false;
                    if(isset($item['isstatic']))
                        $isstatic = $item['isstatic'];
                    if(!isset($item['class']) || !isset($item['method']))
                        continue;
                    if($isstatic){
                        $item['class']::$item['method']($user);
                    }else{
                        $inited = true;
                        if(isset($item['inited']))
                            $inited = $item['inited'];
                        if(!$inited){
                            if(!isset($classes[$item['class']]) && (!isset($classes_inited[$item['class']]) || !$classes_inited[$item['class']] ) ){
                                $classes[$item['class']] = new $item['class']();
                            }
                            $classes_inited[$item['class']]=true;
                        }else{
                            if(!isset($item['object']) || !is_object($item['object']))
                                continue;
                            $classes[$item['class']] = $item['object'];
                        }
                        $classes[$item['class']]->{$item['method']}($user);
                    }
                    break;
                case 'function':
                    if(!isset($item['method']))
                        continue;
                    $item['method']($user);
                    break;
            }
        }
//        add_log(count($profile_list));
//        add_log($profile_list);
//        $alleg->save($user_id);
	}
    function save( $user_id )
    {
        $user_id = $user_id->ID;
    
//        add_log($this->fields);
//        add_log($this->blocks);
//        add_log($_POST);
        foreach ($this->blocks as $bk => $bn) {
            foreach ($this->fields[$bn] as $fk => $fn) {
                if(isset($_POST[$fn]) && $this->fieldtpls[$bn][$fk]!='td_t_'){
                    update_user_meta( $user_id,$fn,
                            ( $_POST[$fn] ) );
                    
//        add_log([$user_id,$fn,$_POST[$fn] ]);
//                    update_user_meta( $user_id,$fn,
//                            sanitize_text_field( $_POST[$fn] ) );
                }
            }
        }
        do_action('ds_admin_profile_save',$this,$user_id);
    }
    
	public static function _init($user) {
		if ( ! self::$initiated ) {
			self::init_hooks($user);
		}

//		if ( isset( $_POST['action'] ) && $_POST['action'] == 'enter-key' ) {
//			self::enter_api_key();
//		}
//
//		if ( ! empty( $_GET['akismet_comment_form_privacy_notice'] ) && empty( $_GET['settings-updated']) ) {
//			self::set_form_privacy_notice_option( $_GET['akismet_comment_form_privacy_notice'] );
//		}
	}

	public static function init_hooks($user) {
        $alleg = new ProfileFields();
		self::$instance = $alleg;
		self::$initiated = true;
//        add_action( 'after_setup_theme', 'lend_setup' );
//        add_filter('the_content', [ 'MedLab', '_content']);
//        add_action( 'widgets_init', ['Allegro','register_wgts_area'],1  );
//        $alleg->init_shortcodes();
//        $alleg->init_roles();
//        $alleg->init_dictionaries();
        $alleg->init($user);
        
	}
    
    public function init($user){
//        add_log($this->blocks);
        
//        $this->initFieldsGeneral($user);
//        $this->initFieldsDoctor($user);
//        $this->initFieldsRequisites($user);
//        $this->initFieldsPatient($user);
        
//        $alleg = new ProfileFields();
//        $user = (object)['ID'=>$user_id];
        
        $profile_list = [];
//        $profile_list[]=['class'=>'','method'=>'','inited'=>true,'type'=>'class','isstatic'=>false,'weigh'=>6];
        $profile_list[]=['class'=>'ProfileFields','object'=>$this,'method'=>'initFieldsGeneral','inited'=>true,'type'=>'class','isstatic'=>false,'weigh'=>1];
        $profile_list[]=['class'=>'ProfileFields','object'=>$this,'method'=>'initFieldsDoctor','inited'=>true,'type'=>'class','isstatic'=>false,'weigh'=>2];
        $profile_list[]=['class'=>'ProfileFields','object'=>$this,'method'=>'initFieldsRequisites','inited'=>true,'type'=>'class','isstatic'=>false,'weigh'=>3];
        $profile_list[]=['class'=>'ProfileFields','object'=>$this,'method'=>'initFieldsPatient','inited'=>true,'type'=>'class','isstatic'=>false,'weigh'=>4];
//        $profile_list[]=['class'=>'ProfileFields','object'=>$alleg,'method'=>'save','inited'=>true,'type'=>'class','isstatic'=>false,'weigh'=>100];
        
//        $alleg->initFieldsGeneral($user);
//        $alleg->initFieldsDoctor($user);
//        $alleg->initFieldsRequisites($user);
//        $alleg->initFieldsPatient($user);
//        $alleg->save($user_id);
        
//        $profile_list = apply_filters('ds_admin_profile_block_list',$profile_list);
        $profile_list = apply_filters('ds_admin_profile_block_list',$profile_list,$this,$user);
        do_action('ds_admin_profile_list_init',$profile_list,$this,$user);
        
        $list=[];
        
        // applay sort by weigh
        foreach($profile_list as $item){
            $weigh = 6;
            if(isset($item['weigh']))
                $weigh = $item['weigh'];
            if(!isset($list[$weigh]))$list[$weigh]=[];
            $list[$weigh][]=$item;
        }
        ksort($list);
        $profile_list = [];
        $profile_list_ = [];
        foreach($list as $items){
            foreach($items as $item){
                $profile_list[]=$item;
                $profile_list_[]=$item['class'].'->'.$item['method'];
            }
        }
//        add_log($profile_list_);
        
        $classes = [];
        $classes['ProfileFields'] = $this;
        
        // applay list
        foreach($profile_list as $item){
            $type = 'type';
            if(isset($item['type']))
                $type = $item['type'];
            switch($type){
                case 'class':
                    $isstatic = false;
                    if(isset($item['isstatic']))
                        $isstatic = $item['isstatic'];
                    if(!isset($item['class']) || !isset($item['method']))
                        continue;
                    if($isstatic){
                        $item['class']::$item['method']($user);
                    }else{
                        $inited = true;
                        if(isset($item['inited']))
                            $inited = $item['inited'];
                        if(!$inited){
                            if(!isset($classes[$item['class']])){
                                $classes[$item['class']] = new $classes[$item['class']]();
                            }
                        }else{
                            if(!isset($item['object']) || !is_object($item['object']))
                                continue;
                            $classes[$item['class']] = $item['object'];
                        }
                        $classes[$item['class']]->{$item['method']}($user);
                    }
                    break;
                case 'function':
                    if(!isset($item['method']))
                        continue;
                    $item['method']($user);
                    break;
            }
        }
        
        $this->initTpls();
        $this->buildBlocks();
    }
    
    public $blocks = ['gen','doctor','requisites','patient'];
    public $blocksLabels = [
        'gen'=>'Общие данные',
        'doctor'=>'Данные врача',
        'requisites'=>'Реквизиты лаборанта',
        'patient'=>'Данные пациента'];
    
    public $fields = [];
    public $labels = [];
    public $values = [];
    public $valdef = [];
    public $fsel_opts = [];
    public $fieldtpls = [];
    public $classes = [];
    public $placeholders = [];


    public function initFieldsGeneral($user){
        $key = 'gen';
        $field=[];
        $field[] = 'last_name';
        $field[] = 'first_name';
        $field[] = 'second_name';
        $field[] = 'user_email';
        $field[] = 'phone';
        $this->fields[$key] = $field;
        
        $label=[];
        $label[] = 'Фамилия';
        $label[] = 'Имя';
        $label[] = 'Отчество';
        $label[] = 'Почта';
        $label[] = 'Телефон';
        $this->labels[$key] = $label;
        
        $val=[];
        $val[] = esc_attr(get_user_meta($user->ID, 'last_name', true));
        $val[] = esc_attr(get_user_meta($user->ID, 'first_name', true));
        $val[] = esc_attr(get_user_meta($user->ID, 'second_name', true));
        $val[] = esc_attr(get_user_meta($user->ID, 'user_email', true));
        $val[] = esc_attr(get_user_meta($user->ID, 'phone', true));
//        $val[] = esc_attr(get_the_author_meta('last_name', $user->ID));
//        $val[] = esc_attr(get_the_author_meta('first_name', $user->ID));
//        $val[] = esc_attr(get_the_author_meta('second_name', $user->ID));
//        $val[] = esc_attr(get_the_author_meta('user_email', $user->ID));
//        $val[] = esc_attr(get_the_author_meta('phone', $user->ID));
        
//            $val[$fk] = esc_attr(get_user_meta($user->ID, $fn, true));
        $this->values[$key] = $val;
        
        $ftpl=[];
        $ftpl[] = 'td_t_';
        $ftpl[] = 'td_t_';
        $ftpl[] = 'td_t_';
        $ftpl[] = 'td_t_';
        $ftpl[] = 'td_i_';
        $this->fieldtpls[$key] = $ftpl;
    
//    $ftpl[] = 'td_i_'; // input text
//    $ftpl[] = 'td_t_'; // only output text
//    $ftpl[] = 'td_d_'; // input number
    }
    public function initFieldsDoctor($user){
        $key = 'doctor';
        $field=[];
        $field[] = 'specialization';
        $field[] = 'laboratory';
        $field[] = 'spec_code';
        $field[] = 'cabinet';
        $field[] = 'bonus_perc';
        $this->fields[$key] = $field;
        
        $label=[];
        $label[] = 'Специальность';
        $label[] = 'Лаборатория';
        $label[] = 'Код специальности';
        $label[] = 'Кабинет';
        $label[] = 'Бонус %';
        $this->labels[$key] = $label;
        
        $def=[];
        $def[] = '';
        $def[] = '';
        $def[] = '';
        $def[] = '';
        $def[] = '1'; // ( 1,23,5,7 %)
        $this->valdef[$key] = $def;
        
        $val=[];
        foreach ($field as $fk=>$fn) {
//            $val[$fk] = esc_attr(get_the_author_meta($fn, $user->ID));
            $val[$fk] = esc_attr(get_user_meta($user->ID, $fn, true));
            $val[$fk] = $val[$fk]?$val[$fk]:$this->valdef[$key][$fk];
        }
        $this->values[$key] = $val;
        
        $ftpl=[];
        $ftpl[] = 'td_i_';
        $ftpl[] = 'td_i_';
        $ftpl[] = 'td_i_';
        $ftpl[] = 'td_i_';
        $ftpl[] = 'td_i_';
        $this->fieldtpls[$key] = $ftpl;
    
//    $ftpl[] = 'td_i_'; // input text
//    $ftpl[] = 'td_t_'; // only output text
//    $ftpl[] = 'td_d_'; // input number
    }
    public static function get_lab_group_list(){
//        ml_lab_groups
        $ml_lab_groups = get_option('ml_lab_groups') ;
        $ml_lab_groups = explode("\n",$ml_lab_groups);
        $ml_lab_groups = array_map( function($v){return explode(":",$v);},$ml_lab_groups);
        $groups = [];
        foreach($ml_lab_groups as $v){
            $groups [(int)trim($v[0])] = $v[1];
        }
        $sel=[];
//        $sel[-1] = 'Выбрать группу';
        $sel[0] = 'Выбрать группу';
        $sel+=$groups;
        return $sel;
        
    }
    public static function get_lab_group_list__(){
//        ml_lab_groups
        $ml_lab_groups = get_option('ml_lab_groups') ;
        $ml_lab_groups = explode("\n",$ml_lab_groups);
        $ml_lab_groups = array_map( function($v){return explode(":",$v);},$ml_lab_groups);
        $groups = [];
        foreach($ml_lab_groups as $v){
            $groups [(int)trim($v[0])] = $v[1];
        }
        $sel=[];
//        $sel[-1] = 'Выбрать группу';
        $sel[0] = 'Выбрать группу';
        $sel+=$groups;
        return $sel;
        
    }
    public function initFieldsRequisites($user){
//        add_log(get_user_meta($user->ID));
        /*
         * 
сайт, телефон, лицензия, почта. 
- все единое будет 
меняются адрес, имя лаборанта
         */
        $field=[];
        $key = 'requisites';
        $field[] = 'blank_address';
        $field[] = 'lab_group';
        $this->fields[$key] = $field;
        
        $label=[];
        $label[] = 'Адрес лаборатории';
        $label[] = 'Группа лаборантовz';
        $this->labels[$key] = $label;
        
        $sel=self::get_lab_group_list();
        $this->fsel_opts[$key]['lab_group'] = $sel;
        
        $def=[];
        $def[] = '';
        $def[] = -1;
        $this->valdef[$key] = $def;
        
        $val=[];
        foreach ($field as $fk=>$fn) {
//            $val[$fk] = esc_attr(get_the_author_meta($fn, $user->ID));
//            $val[$fk] = esc_attr(get_user_meta($user->ID, $fn, true));
            $val[$fk] = (get_user_meta($user->ID, $fn, true));
            $val[$fk] = $val[$fk]?$val[$fk]:$this->valdef[$key][$fk];
        }
        $this->values[$key] = $val;
//        add_log($this->values);
        
        $ftpl=[];
        $ftpl[] = 'td_ta_';
        $ftpl[] = 'td_s_';
        $this->fieldtpls[$key] = $ftpl;
    
//    $ftpl[] = 'td_i_'; // input text
//    $ftpl[] = 'td_t_'; // only output text
//    $ftpl[] = 'td_d_'; // input number
    }
    public function initFieldsPatient($user){
        $key = 'patient';
        $field=[];
        $field[] = 'born_date';
//        $field[] = 'born_year';
        $field[] = 'gender';
//        $field[] = 'pregnancy';
        $field[] = 'pregnancy_week'; // PregnancyWeek
        $field[] = 'joined_doctor';
        $field[] = 'card_numer';
//        $field[] = 'is_corp_cli';
//        $field[] = 'corp_discont_perc';
        $field[] = 'discont_perc';
        $field[] = 'residence_place';
        $field[] = 'passnum';
        $field[] = 'id_citizen';
        $field[] = 'temperature';
        $field[] = 'pat_comment';
        $this->fields[$key] = $field;
        
        $label=[];
        $label[] = 'Дата рождения (dd.mm.yyyy)';
//        $label[] = 'Год рождения';
        $label[] = 'Пол';
//        $label[] = 'Беременность';
        $label[] = 'Срок беременности';
        $label[] = 'Лечащий врач ID';
        $label[] = 'Номер карты';
//        $label[] = 'Корпаративный клиент';
//        $label[] = 'Корпаративная скидка %';
        $label[] = 'Скидка клиента %';
        $label[] = 'Адрес прописки';
        $label[] = 'Номер паспорта';
        $label[] = 'Идентификатор гражданина';
        $label[] = 'Температрура';
        $label[] = 'Примечание';
        $this->labels[$key] = $label;
        
        $this->fsel_opts[$key] = [];
        $sel=[];
        $sel[0] = 'пол неизвестен';
        $sel[1] = 'мужской';
        $sel[2] = 'женский';
        $this->fsel_opts[$key]['gender'] = $sel;
//        $sel=[];
//        $sel[0] = 'Нет';
//        $sel[1] = 'Да';
//        $this->fsel_opts[$key]['pregnancy'] = $sel;
        $sel=[];
        $sel = range(0, 50);
//        $sel[0] = array_combine($sel, $label);
        $this->fsel_opts[$key]['pregnancy_week'] = $sel;
        $sel=[];
        $sel[1] = 'Да';
        $sel[0] = 'Нет';
        $this->fsel_opts[$key]['is_corp_cli'] = $sel;
        
        $def=[];
        $def[] = '';
//        $def[] = '';
        $def[] = '0';
//        $def[] = '0';
        $def[] = '0';
        $def[] = '0';
        $def[] = '0';
//        $def[] = '0';
//        $def[] = '30';
        $def[] = '0';
        $def[] = '';
        $def[] = '';
        $def[] = '';
        $def[] = '0';
        $def[] = '';
        $this->valdef[$key] = $def;
        
        $val=[];
        foreach ($field as $fk=>$fn) {
//            $val[$fk] = esc_attr(get_the_author_meta($fn, $user->ID));
            $val[$fk] = esc_attr(get_user_meta($user->ID, $fn, true));
            $val[$fk] = $val[$fk]?$val[$fk]:$this->valdef[$key][$fk];
//            $val[$fk] = $val[$fk]?$val[$fk]:0;
        }
        $this->values[$key] = $val;
        
        $this->classes[$key]['born_date'] = ' field_birthday ';
        $this->placeholders[$key]['born_date'] = 'dd.mm.yyyy';
        
        $ftpl=[];
        $ftpl[] = 'td_i_';
//        $ftpl[] = 'td_i_';
        $ftpl[] = 'td_s_';
//        $ftpl[] = 'td_s_';
        $ftpl[] = 'td_s_';
        $ftpl[] = 'td_i_';
        $ftpl[] = 'td_i_';
//        $ftpl[] = 'td_s_';
//        $ftpl[] = 'td_i_';
        $ftpl[] = 'td_i_';
        $ftpl[] = 'td_i_';
        $ftpl[] = 'td_i_';
        $ftpl[] = 'td_i_';
        $ftpl[] = 'td_i_';
        $ftpl[] = 'td_ta_';
        $this->fieldtpls[$key] = $ftpl;
    
//    $ftpl[] = 'td_i_'; // input text
//    $ftpl[] = 'td_t_'; // only output text
//    $ftpl[] = 'td_d_'; // input number
    }
    public $tpls = [];
    public function initTpls(){
        $table__=<<<td
    <h3>__title__:</h3>

    <table class="__class__">
              __rows__
    </table>
td;
        $td_s_=<<<td
            <tr>
                <th><label for="__for__">__label__</label></th>
                <td>__select__</td>
            </tr>
td;
        $td_d_=<<<td
            <tr>
                <th><label for="__for__">__label__</label></th>
                <td><input id="__id__" type="number" name="__name__" value="__val__" class="regular-text __i_class__" placeholder="__placeholder__" /></td>
            </tr>
td;
        $td_i_=<<<td
            <tr>
                <th><label for="__for__">__label__</label></th>
                <td><input id="__id__" type="text" name="__name__" value="__val__" class="regular-text __i_class__" placeholder="__placeholder__"/></td>
            </tr>
td;
        $td_t_=<<<td
            <tr>
                <th><label for="__for__">__label__</label></th>
                <td><span id="__id__" class="regular-text field-__name__"><b>__val__</b></span></td>
            </tr>
td;
        $td_ta_=<<<td
            <tr>
                <th><label for="__for__">__label__</label></th>
                <td><textarea cols="__cols__" rows="__rows__" id="__id__" name="__name__" class="-regular-text __i_class__" placeholder="__placeholder__">__val__</textarea></td>
            </tr>
td;
//        '<b>'.__CLASS__.' : '.__FUNCTION__.'</b>'
        $this->tpls['version'] = __CLASS__.' : '.__FUNCTION__ .' : '.__LINE__;
        $this->tpls[__CLASS__.' : '.__FUNCTION__ .' : '.__LINE__] = '';
        $this->tpls['table__'] = $table__;
        $this->tpls['td_d_'] = $td_d_;
        $this->tpls['td_s_'] = $td_s_;
        $this->tpls['td_i_'] = $td_i_;
        $this->tpls['td_t_'] = $td_t_;
        $this->tpls['td_ta_'] = $td_ta_;
    }
    public function buildBlocks(){
        $out = [];
        foreach ($this->blocks as $bk => $bn) {
            $out[]=$this->buildBlock($bn);
        }
//        add_log($this->blocks);
        $out = implode("\n",$out);
        ob_start();
        ?><style>
.form-table th,
.form-table td,
.tabl_fields td,
.tabl_fields tr{
/*    margin-top: 0px;
    margin-bottom: 0px;*/
    padding: 0px 10px 0px 0;
}
.tabl_fields th {
    vertical-align: top;
    text-align: left;
    /*padding: 20px 10px 20px 0;*/
    padding: 0px 10px 0px 0;
    width: 200px;
    line-height: 1.3;
    font-weight: 600;
}
</style>
<script type="text/javascript" src="https://analizysl.com/wp-content/themes/medlab/js/jquery.mask.min.js"></script>
<script>
jQuery(document).ready(function($) {
    $('.field_birthday').mask('99.99.9999');
});
</script>
    <?php
        $style = ob_get_clean();
//        add_log('Стиль изменён'.$style);
        echo $style.$out;
    }
    public function buildBlock($bn){
//        $this->initTpls();
        $out = '';
        $tds=[];
  
//        $mess = '';
        ob_start();
        if(isset($this->fields[$bn])){
            foreach($this->fields[$bn] as $k=>$v){
        //        ob_start();
                $r=[];
                $r['__id__']=$v;
                $r['__for__']=$v;
                $r['__name__']=$v;
                $r['__label__']=$this->labels[$bn][$k];
                $r['__val__']=$this->values[$bn][$k];
                $r['__i_class__']='';
                $r['__placeholder__']='';
                if(isset($this->classes[$bn][$v]))
                $r['__i_class__']=$this->classes[$bn][$v];
                if(isset($this->placeholders[$bn][$v]))
                $r['__placeholder__']=$this->placeholders[$bn][$v];


                $r['__cols__']=70;
                $r['__rows__']=5;
                if($this->fieldtpls[$bn][$k] == 'td_ta_'){
                    $rows =  count(explode("\n",$r['__val__']));//esc_attr
                    $r['__rows__'] += $rows;
                }

                if($this->fieldtpls[$bn][$k] == 'td_s_'){
                    $o=[];
                    $o['id'] = $v;
                    $o['name'] = $v;
                    $o['val'] = $this->values[$bn][$k];
                    $o['items'] = $this->fsel_opts[$bn][$v];
                    $r['__select__']=$this->_select($o);
                }

        //        $tds[]=strtr($$ftpl[$k],$r); // php v5x 5.6.38
        //        $tds[]=strtr(${$ftpl[$k]},$r); // php v7x 7.1.26
                ob_start();
                $tds[]=strtr($this->tpls[$this->fieldtpls[$bn][$k]],$r);
                $err = ob_get_clean();
                if($err){
                    echo $err;
                    dev::pre([
                        $this->fieldtpls,
                        $this->fieldtpls[$bn][$k],
                        $bn,$k,
                        array_keys($this->tpls)
                    ],'shop profike: file admi_profile, line: '.__LINE__);
                }
        //    $mess .= ob_get_clean();
            }
        }
        $err = ob_get_clean();
        if($err){
            global $ht;
            echo '<b>'.__CLASS__.' : '.__FUNCTION__.'</b>';
            echo $err;
            echo $ht->pre(array_keys($this->fields));
        }
        $r=[];
        $r['__class__']=$this->tabClass[$bn].' tabl_fields tabl_'.$bn;// <br/>
        $r['__title__']=$this->blocksLabels[$bn];// <br/>
        $r['__rows__']=implode("\n",$tds);// <br/>
        $out = strtr($this->tpls['table__'],$r);
    //    echo nl2br( htmlspecialchars(strtr($table__,$r)));
    //    echo $mess;
        return $out;
    }
    public $tabClass=[
        'gen'=>'form-table',
        'doctor'=>'',
        'requisites'=>'',
        'patient'=>''];
    
    public function _select( $val ){
        $id = $val['id'];
        $option_name = $val['name'];
        $tpl_o=<<<t
            <option value="_v_" _s_>_n_</option>
t;
        $r=[];
//        $v_=get_option($option_name,'');
        $v_=$val['val'];
        foreach($val['items'] as $v=>$n){
            $r['_n_']=$n;
            $r['_v_']=$v;
            $r['_s_']=$v_==$v?'selected="selected"':'';
            $val['items'][$v] = strtr($tpl_o,$r);
        }
        $o=implode('',$val['items']);
        ob_start();?>
    <select name="<?= $option_name ?>" 
            id="<?= $id ?>" ><?= $o?></select>
        <?php
        return ob_get_clean();
    }
}

//add_action( 'show_user_profile', 'my_show_extra_profile_fields' );
//add_action( 'edit_user_profile', 'my_show_extra_profile_fields' );

function my_show_extra_profile_fields( $user ) {
    $files = get_user_meta ( $user->ID, 'files', false);
//    add_log($files,'admin');
    echo '<table class="form-table">';
    foreach ($files as $key => $a_id) {
        $post = get_post( $a_id );
        echo '<tr><th><label for="">'.$post->post_date.'</label></th><td>';
        the_attachment_link( $a_id, false, false, false);
//        echo '</td><td>';
//        echo wp_get_attachment_url($a_id);
        echo '</td></tr>';
    }
    if(count($files)==0)
        echo '<tr><td><p>Нет файла</p></td></tr>';
    echo '</table>';
    /*
	$file = get_user_meta( $user->ID, 'file_meta_field', true );
    if($file){
?>
	<a target="_blank" href="<?php echo $file; ?>">UserFile</a>
<?php 
    }else{
        echo '<p>Нет файла</p>';
    }
    echo get_stylesheet_uri();
    /**/
    phpinfo();
}
 
// сохранение
 
add_action( 'personal_options_update', ['ProfileFields','_init_save'] );
add_action( 'edit_user_profile_update', ['ProfileFields','_init_save'] );
//add_action( 'personal_options_update', 'save_extra_social_links' );
//add_action( 'edit_user_profile_update', 'save_extra_social_links' );
//add_action( 'edit_user_profile', ['ProfileFields','_init'] );
 
//https://wp-kama.ru/function/sanitize_text_field
function save_extra_social_links( $user_id )
{
    
    
//        add_log($this->fields);
//        add_log($this->blocks);
//        add_log($this->$_POST);
        foreach ($this->blocks as $bk => $bn) {
            foreach ($this->fields[$bn] as $fk => $fn) {
                if(isset($_POST[$fn]) && 1 ){
                    update_user_meta( $user_id,$fn,
                            sanitize_text_field( $_POST[$fn] ) );
                }
            }
        }
//        exit();
}

// аватары пользователей по умолчанию
// http://wordpressinside.ru/tips/default-avatar/
//add_filter( 'avatar_defaults', 'setnew_gravatar' );
function setnew_gravatar ($avatar_defaults) {
    echo get_avatar($comment,$size='40'); 
	$myavatar = 'http://ваш_сайт/wp-content/uploads/new_avatar.png';
	$avatar_defaults[$myavatar] = "Новый аватар";
	return $avatar_defaults;
}

/** /
function change_display_name_to_textfield() {
  echo "><div>"; // don't remove '>'
  ?>
  <script>
    jQuery(function($) { 
      // replace display_name select with input
      $('select#display_name').after( '<input type="text" name="display_name" id="display_name" value="' + $('#display_name').val() + '" class="regular-text">' ).remove();
    })
  </script>
  <?php
  echo "</div"; // don't add '>'
}

// hook into new user and edit user pages
add_action( "user_new_form_tag", "change_display_name_to_textfield" );
add_action( "user_edit_form_tag", "change_display_name_to_textfield" );
/**/

