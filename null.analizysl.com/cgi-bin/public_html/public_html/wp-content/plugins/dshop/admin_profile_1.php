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

class ProfileFields{
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
        $alleg = new ProfileFields();
        $user = (object)['ID'=>$user_id];
        $alleg->initFieldsGeneral($user);
        $alleg->initFieldsDoctor($user);
        $alleg->initFieldsPatient($user);
        $alleg->save($user_id);
        
	}
    function save( $user_id )
    {
        foreach ($this->blocks as $bk => $bn) {
            foreach ($this->fields[$bn] as $fk => $fn) {
                if(isset($_POST[$fn]) && $this->fieldtpls[$bn][$fk]!='td_t_'){
                    update_user_meta( $user_id,$fn,
                            sanitize_text_field( $_POST[$fn] ) );
                }
            }
        }
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
        $this->initFieldsGeneral($user);
        $this->initFieldsDoctor($user);
        $this->initFieldsPatient($user);
        $this->initTpls();
        $this->buildBlocks();
    }
    
    public $blocks = ['gen','doctor','patient'];
    public $blocksLabels = [
        'gen'=>'Общие данные',
        'doctor'=>'Данные врача',
        'patient'=>'Данные пациента'];
    
    public $fields = [];
    public $labels = [];
    public $values = [];
    public $valdef = [];
    public $fsel_opts = [];
    public $fieldtpls = [];


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
        $val[] = esc_attr(get_the_author_meta('last_name', $user->ID));
        $val[] = esc_attr(get_the_author_meta('first_name', $user->ID));
        $val[] = esc_attr(get_the_author_meta('second_name', $user->ID));
        $val[] = esc_attr(get_the_author_meta('user_email', $user->ID));
        $val[] = esc_attr(get_the_author_meta('phone', $user->ID));
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
            $val[$fk] = esc_attr(get_the_author_meta($fn, $user->ID));
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
    public function initFieldsPatient($user){
        $key = 'patient';
        $field=[];
        $field[] = 'born_date';
        $field[] = 'born_year';
        $field[] = 'gender';
        $field[] = 'joined_doctor';
        $field[] = 'card_numer';
        $field[] = 'is_corp_cli';
        $field[] = 'corp_discont_perc';
        $field[] = 'discont_perc';
        $this->fields[$key] = $field;
        
        $label=[];
        $label[] = 'Дата рождения';
        $label[] = 'Год рождения';
        $label[] = 'Пол';
        $label[] = 'Лечащий врач ID';
        $label[] = 'Номер карты';
        $label[] = 'Корпаративный клиент';
        $label[] = 'Корпаративная скидка %';
        $label[] = 'Скидка клиента %';
        $this->labels[$key] = $label;
        
        $this->fsel_opts[$key] = [];
        $sel=[];
        $sel[0] = 'пол неизвестен';
        $sel[1] = 'мужской';
        $sel[2] = 'женский';
        $this->fsel_opts[$key]['gender'] = $sel;
        $sel=[];
        $sel[1] = 'Да';
        $sel[0] = 'Нет';
        $this->fsel_opts[$key]['is_corp_cli'] = $sel;
        
        $def=[];
        $def[] = '';
        $def[] = '';
        $def[] = '1';
        $def[] = '0';
        $def[] = '0';
        $def[] = '0';
        $def[] = '30';
        $def[] = '0';
        $this->valdef[$key] = $def;
        
        $val=[];
        foreach ($field as $fk=>$fn) {
            $val[$fk] = esc_attr(get_the_author_meta($fn, $user->ID));
            $val[$fk] = $val[$fk]?$val[$fk]:$this->valdef[$key][$fk];
//            $val[$fk] = $val[$fk]?$val[$fk]:0;
        }
        $this->values[$key] = $val;
        
        $ftpl=[];
        $ftpl[] = 'td_i_';
        $ftpl[] = 'td_i_';
        $ftpl[] = 'td_s_';
        $ftpl[] = 'td_i_';
        $ftpl[] = 'td_i_';
        $ftpl[] = 'td_s_';
        $ftpl[] = 'td_i_';
        $ftpl[] = 'td_i_';
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
                <td><input id="__id__" type="number" name="__name__" value="__val__" class="regular-text" /></td>
            </tr>
td;
        $td_i_=<<<td
            <tr>
                <th><label for="__for__">__label__</label></th>
                <td><input id="__id__" type="text" name="__name__" value="__val__" class="regular-text" /></td>
            </tr>
td;
        $td_t_=<<<td
            <tr>
                <th><label for="__for__">__label__</label></th>
                <td><span id="__id__" class="regular-text field-__name__"><b>__val__</b></span></td>
            </tr>
td;
        $this->tpls['table__'] = $table__;
        $this->tpls['td_d_'] = $td_d_;
        $this->tpls['td_s_'] = $td_s_;
        $this->tpls['td_i_'] = $td_i_;
        $this->tpls['td_t_'] = $td_t_;
    }
    public function buildBlocks(){
        $out = [];
        foreach ($this->blocks as $bk => $bn) {
            $out[]=$this->buildBlock($bn);
        }
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
</style><?php
        $style = ob_get_clean();
        add_log('Стиль изменён'.$style);
        echo $style.$out;
    }
    public function buildBlock($bn){
        $out = '';
        $tds=[];
  
//        $mess = '';
        foreach($this->fields[$bn] as $k=>$v){
    //        ob_start();
            $r=[];
            $r['__id__']=$v;
            $r['__for__']=$v;
            $r['__name__']=$v;
            $r['__label__']=$this->labels[$bn][$k];
            $r['__val__']=$this->values[$bn][$k];
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
            $tds[]=strtr($this->tpls[$this->fieldtpls[$bn][$k]],$r);
    //    $mess .= ob_get_clean();
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
    
    
        foreach ($this->blocks as $bk => $bn) {
            foreach ($this->fields[$bn] as $fk => $fn) {
                if(isset($_POST[$fn]) && 1 ){
                    update_user_meta( $user_id,$fn,
                            sanitize_text_field( $_POST[$fn] ) );
                }
            }
        }
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

