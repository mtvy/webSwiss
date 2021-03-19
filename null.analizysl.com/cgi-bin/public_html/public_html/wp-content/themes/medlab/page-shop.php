<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */
//echo 'rrrrrrrrrrr';
ob_start();

        
        get_template_part( 'template-parts/component/tpl-content-wrapp', 'start' );
echo 'zzzzzzzzzzzzzz';
		while ( have_posts() ) :
			the_post();
//			get_template_part( 'template-parts/content', 'page-shop' );
				get_template_part( 'template-parts/dshop/content', 'page-shop' );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
        get_template_part( 'template-parts/component/tpl-content-wrapp', 'end' );
        
$ctnt = ob_get_clean();

get_header();
echo $ctnt;
//get_sidebar();
get_footer();
