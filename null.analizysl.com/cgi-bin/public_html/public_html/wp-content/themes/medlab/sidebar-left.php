<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package medlab
 */

?>

<aside id="sidebar-left" class="widget-area">
	<?php
//    if(current_user_can('manage_options'))
        dynamic_sidebar( 'sidebar-left' );
    ?>
<section id="categories-z" class="widget widget_categories"><ul>
<li class="cat-item cat-item-1"><a href="<?= is_user_logged_in()? wp_logout_url() : wp_login_url()?>"><?= is_user_logged_in()? 'Выход' : 'Вход' ?></a>
</li>
</ul>
</section>
</aside><!-- #secondary -->
<?php

if(current_user_can('ml_access_menu_u_l')){
//    echo '<!-- ul -->';
}
if(current_user_can('ml_access_menu_d_l')){
//    echo '<!-- dl -->';
}
if(current_user_can('manage_options')){
//    echo '<!-- mo -->';
}
