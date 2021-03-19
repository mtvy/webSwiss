<?php

/* 
 * class.WSDLogin.php
 */

/**
 * Provides a simple login form for use anywhere within WordPress.
 *
 * The login format HTML is echoed by default. Pass a false value for `$echo` to return it instead.
 *
 * @since 3.0.0
 *
 * @param array $args {
 *     Optional. Array of options to control the form output. Default empty array.
 *
 *     @type bool   $echo           Whether to display the login form or return the form HTML code.
 *                                  Default true (echo).
 *     @type string $redirect       URL to redirect to. Must be absolute, as in "https://example.com/mypage/".
 *                                  Default is to redirect back to the request URI.
 *     @type string $form_id        ID attribute value for the form. Default 'loginform'.
 *     @type string $label_username Label for the username or email address field. Default 'Username or Email Address'.
 *     @type string $label_password Label for the password field. Default 'Password'.
 *     @type string $label_remember Label for the remember field. Default 'Remember Me'.
 *     @type string $label_log_in   Label for the submit button. Default 'Log In'.
 *     @type string $id_username    ID attribute value for the username field. Default 'user_login'.
 *     @type string $id_password    ID attribute value for the password field. Default 'user_pass'.
 *     @type string $id_remember    ID attribute value for the remember field. Default 'rememberme'.
 *     @type string $id_submit      ID attribute value for the submit button. Default 'wp-submit'.
 *     @type bool   $remember       Whether to display the "rememberme" checkbox in the form.
 *     @type string $value_username Default value for the username field. Default empty.
 *     @type bool   $value_remember Whether the "Remember Me" checkbox should be checked by default.
 *                                  Default false (unchecked).
 *
 * }
 * @return string|void String when retrieving.
 */
function wsd_wp_login_form( $args = array() ) {
	$defaults = array(
		'echo'           => true,
		// Default 'redirect' value takes the user back to the request URI.
		'redirect'       => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
		'form_id'        => 'loginform',
		'label_username' => __( 'Username or Email Address' ),
		'label_password' => __( 'Password' ),
		'label_remember' => __( 'Remember Me' ),
		'label_log_in'   => __( 'Log In' ),
		'id_username'    => 'user_login',
		'id_password'    => 'user_pass',
		'id_remember'    => 'rememberme',
		'id_submit'      => 'wp-submit',
		'remember'       => true,
		'value_username' => '',
		// Set 'value_remember' to true to default the "Remember me" checkbox to checked.
		'value_remember' => false,
	);

	/**
	 * Filters the default login form output arguments.
	 *
	 * @since 3.0.0
	 *
	 * @see wp_login_form()
	 *
	 * @param array $defaults An array of default login form arguments.
	 */
	$args = wp_parse_args( $args, apply_filters( 'login_form_defaults', $defaults ) );

	/**
	 * Filters content to display at the top of the login form.
	 *
	 * The filter evaluates just following the opening form tag element.
	 *
	 * @since 3.0.0
	 *
	 * @param string $content Content to display. Default empty.
	 * @param array  $args    Array of login form arguments.
	 */
	$login_form_top = apply_filters( 'login_form_top', '', $args );

	/**
	 * Filters content to display in the middle of the login form.
	 *
	 * The filter evaluates just following the location where the 'login-password'
	 * field is displayed.
	 *
	 * @since 3.0.0
	 *
	 * @param string $content Content to display. Default empty.
	 * @param array  $args    Array of login form arguments.
	 */
	$login_form_middle = apply_filters( 'login_form_middle', '', $args );

	/**
	 * Filters content to display at the bottom of the login form.
	 *
	 * The filter evaluates just preceding the closing form tag element.
	 *
	 * @since 3.0.0
	 *
	 * @param string $content Content to display. Default empty.
	 * @param array  $args    Array of login form arguments.
	 */
	$login_form_bottom = apply_filters( 'login_form_bottom', '', $args );
    

	$form = '
		<form name="' . $args['form_id'] . '" id="' . $args['form_id'] . '" action="' . esc_url( site_url( 'wp-login.php', 'login_post' ) ) . '" method="post">
			' . $login_form_top . '
			<p class="login-username">
				<label for="' . esc_attr( $args['id_username'] ) . '">' . esc_html( $args['label_username'] ) . '</label>
				<input type="text" name="log" id="' . esc_attr( $args['id_username'] ) . '" class="input" value="' . esc_attr( $args['value_username'] ) . '" size="20" />
			</p>
			<p class="login-password">
				<label for="' . esc_attr( $args['id_password'] ) . '">' . esc_html( $args['label_password'] ) . '</label>
				<input type="password" name="pwd" id="' . esc_attr( $args['id_password'] ) . '" class="input" value="" size="20" />
			</p>
			' . $login_form_middle  . '
			' . ( $args['remember'] ? '<p class="login-remember"><label><input name="rememberme" type="checkbox" id="' . esc_attr( $args['id_remember'] ) . '" value="forever"' . ( $args['value_remember'] ? ' checked="checked"' : '' ) . ' /> ' . esc_html( $args['label_remember'] ) . '</label></p>' : '' ) . '
			<p class="login-submit">
				<input type="submit" name="wp-submit" id="' . esc_attr( $args['id_submit'] ) . '" class="button button-primary" value="' . esc_attr( $args['label_log_in'] ) . '" />
				<input type="hidden" name="redirect_to" value="' . esc_url( $args['redirect'] ) . '" />
			</p>
			' . $login_form_bottom . '
		</form>';

//		<?php
		/**
		 * Fires following the 'Password' field in the login form.
		 *
		 * @since 2.1.0
		 */
		ob_start(); do_action( 'login_form' );
        $acts = ob_get_clean();
//		? >
	$form = '
		<form name="' . $args['form_id'] . '" id="' . $args['form_id'] . '" 
             action="' . esc_url( site_url( 'wp-login.php', 'login_post' ) ) . '" method="post" class="ml-v2-form">
			' . $login_form_top . '
                
        <div class="row">
            <div class="col-12 login-username">
				<label for="' . esc_attr( $args['id_username'] ) . '">' . esc_html( $args['label_username'] ) . '</label>
            </div>
            <div class="col-5">
				<input type="text" name="log" id="' . esc_attr( $args['id_username'] ) . '" class="input form-control" value="' . esc_attr( $args['value_username'] ) . '" s-ize="20" />
			</div>
            <div class="col-12 login-password">
				<label for="' . esc_attr( $args['id_password'] ) . '">' . esc_html( $args['label_password'] ) . '</label>
            </div>
            <div class="col-5">
				<input type="password" name="pwd" id="' . esc_attr( $args['id_password'] ) . '" class="input form-control" value="" s-ize="20" />
			</div>
            <div class="col-5">
			' . $login_form_middle .$acts. '
			</div>
            
			' . ( $args['remember'] ? '<div class="col-5 login-remember"><label><input name="rememberme" type="checkbox" id="' . esc_attr( $args['id_remember'] ) . '" value="forever"' . ( $args['value_remember'] ? ' checked="checked"' : '' ) . ' /> ' . esc_html( $args['label_remember'] ) . '</label></div>' : '' ) . '
			
            <div class="col-12 login-submit">
				<button type="submit" name="wp-submit" id="' . esc_attr( $args['id_submit'] ) . '" class="btn btn-primary -button -button-primary" value="" >' . esc_attr( $args['label_log_in'] ) . '</button>
				<input type="hidden" name="redirect_to" value="' . esc_url( $args['redirect'] ) . '" />
			</div>
			' . $login_form_bottom . '
		</form>';

	if ( $args['echo'] ) {
		echo $form;
	} else {
		return $form;
	}
}

class WSDLogin{
    public function __construct() {
        ;
        ## Оставляет пользователя на той же странице при вводе неверного логина/пароля в форме авторизации wp_login_form()
        add_action( 'wp_login_failed', [$this,'my_front_end_login_fail'] );
        add_filter('login_headerurl',  [$this,'login_headerurl'], 10, 1);
        add_filter('login_headertext',  [$this,'login_headertext'], 10, 1);
        add_filter('login_body_class',  [$this,'login_body_class'], 10, 1);
        add_filter('login_message',  [$this,'login_message'], 10, 1);
        add_action( 'login_header', [$this,'login_header'] );
        add_action( 'login_head', [$this,'login_head'] );
        add_action( 'login_footer', [$this,'login_footer'] );
        add_filter('login_redirect',  [$this,'login_redirect'], 10, 3);
        add_action( 'register_form', [$this,'register_form'] );
        
        add_action( 'user_register', [$this,'user_register'] );
        add_filter( 'registration_errors', [$this,'registration_errors'], 10, 3 );
        add_filter( 'shake_error_codes', [$this,'shake_error_codes'] );
        
    }
    public function user_register( $user_id ) {
        if ( ! empty( $_POST['user_gender'] ) ) {
            $user_gender = filter_input(INPUT_POST, 'user_gender', FILTER_SANITIZE_NUMBER_INT);
            update_user_meta( $user_id, 'gender', $user_gender );
        }
        if ( ! empty( $_POST['user_borndate'] ) ) {
            $user_borndate = filter_input(INPUT_POST, 'user_borndate', FILTER_DEFAULT);
            update_user_meta( $user_id, 'born_date', trim( $user_borndate ) );
        }
    }
    public function registration_errors( $errors, $sanitized_user_login, $user_email ) {
            $user_gender = filter_input(INPUT_POST, 'user_gender', FILTER_SANITIZE_NUMBER_INT);
            $user_borndate = filter_input(INPUT_POST, 'user_borndate', FILTER_DEFAULT);
            $user_borndate = filter_input(INPUT_POST, 'user_borndate',
                    FILTER_VALIDATE_REGEXP,['options' =>['regexp' => '/^[\d]{2}\.[\d]{2}\.[\d]{4}$/']]);

        if ( empty( $user_borndate ) || ! empty( $user_borndate ) && trim( $user_borndate ) == '' ) {
            $errors->add( 'user_borndate', __( '<strong>ERROR</strong>: You must include a born date.', 'mydomain' ) );
        }
//        if ( filter_input( INPUT_POST, 'i-not-robot' ) !== 'yes' ) {
//            $errors->add( 'robot_detected', '<strong>ОШИБКА</strong>: Роботам здесь не место!' );
//        }

        return $errors;
    }
    public function shake_error_codes( $shake_error_codes ) {
        return array_merge( $shake_error_codes, [ 'user_borndate' ] );
    }
    public function register_form( ) {
        $user_gender = '';
        $user_borndate = '';
    $first_name = ( ! empty( $_POST['first_name'] ) ) ? trim( $_POST['first_name'] ) : '';
    $user_gender = filter_input(INPUT_POST, 'user_gender', FILTER_SANITIZE_NUMBER_INT);
    $user_borndate = filter_input(INPUT_POST, 'user_borndate', FILTER_DEFAULT);
    
        /* ?>
	<p>
		<label for="user_sex"><?php _e( 'Sex' ); ?><br />
		<input type="text" name="user_sex" id="user_sex" class="input" value="<?php echo esc_attr( wp_unslash( $user_sex ) ); ?>" size="20" autocapitalize="off" /></label>
	</p>
	<p>
		<label for="user_borndate"><?php _e( 'Borndate' ); ?><br />
		<input type="email" name="user_borndate" id="user_borndate" class="input" value="<?php echo esc_attr( wp_unslash( $user_borndate ) ); ?>" size="25" /></label>
	</p>
		<?php/*/
        /*
         * 
                value="<?php echo esc_attr( wp_unslash( $user_gender ) ); ?>" size="20" autocapitalize="off" 
            <option value="0">Пол неизвестен</option>
         */
        ?>
	<p>
		<label for="user_gender">Пол<br />
		<select name="user_gender" id="user_gender" class="input"
                >
            <option value="1">Мужской</option>
            <option value="2">Женский</option>
        </select></label>
	</p>
<script>
jQuery(document).ready(function($) {
    $('.field_birthday').mask('99.99.9999');
});
</script>
	<p>
		<label for="user_borndate">Дата рождения<br />
		<input type="text" name="user_borndate"    
               placeholder="dd.mm.yyyy"
               id="user_borndate" class="input field_birthday" value="<?php echo esc_attr( wp_unslash( $user_borndate ) ); ?>" /></label>
	</p>
		<?php
    }
    public function my_front_end_login_fail( $username ) {
        $referrer = $_SERVER['HTTP_REFERER'];  // откуда пришел запрос

        // Если есть referrer и это не страница wp-login.php
        if( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
//            wp_redirect( add_query_arg('login', 'failed', $referrer ) );  // редиркетим и добавим параметр запроса ?login=failed
//            exit;
        }
    }
    public function login_headerurl($login_header_url){
        return get_the_permalink( 8 );
    }
    public function login_headertext($login_header_text){
        return '';
    }
    public function login_body_class($classes){
        return $classes[0].' page-template-default page page-id-220 wp-custom-logo';
    }
    public function login_message($message){
        $login_div_h1 = ob_get_clean();
	?>
	<div id="login">
	<?php
		/*<h1><a href="<?php echo esc_url( $login_header_url ); ?>"><?php echo $login_header_text; ?></a></h1>/**/
        return $message;
    }
    public function login_head($args=null){
        wp_head();
//        get_template_part( 'template-parts/component/tpl-header', 'page' );();
//        get_header();
    }
    public function login_header($args=null){
        get_template_part( 'template-parts/component/tpl-header-page', 'login' );
        get_template_part( 'template-parts/component/tpl-content-wrapp', 'start' );
        $this->fix_css();
        ob_start();
    }
    public function fix_css(){
        ?>
            <style>
form#loginform p.galogin {
    height: 35px;
}
            </style>
            <?php
    }
    public function login_footer($args=null){
        get_template_part( 'template-parts/component/tpl-content-wrapp', 'end' );
    }

    /**
     * Redirect user after successful login.
     *
     * @param string $redirect_to URL to redirect to.
     * @param string $request URL the user is coming from.
     * @param object $user Logged user's data.
     * @return string
     */
    public function login_redirect( $redirect_to, $request, $user ) {
//            return site_url( '/profile/' );
        return get_the_permalink( get_option('ds_pageid_login_redirect', 26)  );

//        //is there a user to check?
//        if ( isset( $user->roles ) && is_array( $user->roles ) ) {
//
//            // check for admins
//            if ( in_array( 'administrator', $user->roles ) ) {
//                // redirect them to the default place
//                return $redirect_to;
//            }
//            else {
//                return home_url();
//            }
//        }
//        else {
//            return $redirect_to;
//        }
    }
}