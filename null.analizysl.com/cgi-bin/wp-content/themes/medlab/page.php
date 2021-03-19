<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package medlab
 */


        /*
         * 355
         * 358
         */
//        $slug = 'zzzzzzzzzzzz';
//        global $post;
//        if(isset($post->post_name))
//            $slug = $post->post_name;
//
//        global $wp_query;
//        $args = array(
//          'p'         => 358, // ID of a page, post, or custom type
//          'post_type' => 'page'
//        );
//        $wp_query = new WP_Query($args);

//do_action('ml_get_header');
ob_start();

        
        get_template_part( 'template-parts/component/tpl-content-wrapp', 'start' );
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', 'page' );

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
