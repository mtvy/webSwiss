<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package medlab
 */

if ( ! is_active_sidebar( 'sidebar-admin-dev' ) ) {
	return;
}
?>

<aside id="sidebar-dev" class="widget-area">
	<?php dynamic_sidebar( 'sidebar-admin-dev' ); ?>
</aside><!-- #secondary -->
