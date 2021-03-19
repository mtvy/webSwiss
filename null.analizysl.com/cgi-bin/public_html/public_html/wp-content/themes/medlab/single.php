<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package medlab
 */

get_header();
//get_sidebar('left');
//get_sidebar('left-in-1');
//get_sidebar('admin-dev');
$content_class = 'col-md-12';
?>
	<div id="primary" class="content-area container">
		<main id="main" class="site-main row">
            <div class="col-12 <?=$content_class ?>">

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', get_post_type() );

			the_post_navigation();

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>

            </div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
