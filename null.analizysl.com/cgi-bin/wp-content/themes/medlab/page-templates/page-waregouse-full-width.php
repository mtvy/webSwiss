<?php
/**
 * Template Name: waregouse-full-width
 * The template for displaying all pages
 * page-waregouse-full-width.php
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



ob_start();

global $have_menu;
if(current_user_can('ml_access_menu_u_l')
    ||current_user_can('ml_access_menu_d_l')){
    $have_menu['l']=1;
}
if(current_user_can('manage_options')){
    $have_menu['l']=1;
//    $have_menu['in1']=1;
}

//if(current_user_can('ml_access_menu_u_in')
//    ||current_user_can('ml_access_menu_d_in')){
if( (isset($have_menu['ml_access_menu_u_in']) && $have_menu['ml_access_menu_u_in'] == 1)
    || (isset($have_menu['ml_access_menu_d_in']) && $have_menu['ml_access_menu_d_in'] == 1)){
    $have_menu['in1']=1;
}
//add_log($have_menu);

$content_class = 'col-md-12';
if(isset($have_menu['l']) && $have_menu['l'])
    $content_class = 'col-md-10';
if(isset($have_menu['in1']) && $have_menu['in1'])
    $content_class = 'col-md-10';
if(isset($have_menu['l']) && $have_menu['l']
    && isset($have_menu['in1']) && $have_menu['in1'])
    $content_class = 'col-md-6';
?>

	<div id="primary" class="content-area container-fluid">
		<main id="main" class="site-main row">
            <?php
            if(isset($have_menu['l']) && $have_menu['l']){
            ?>
            <div class="col-12 col-md-2 side-menu">
                <?php
    if(current_user_can('ml_access_menu_u_l')
        ||current_user_can('ml_access_menu_d_l')
        || current_user_can('manage_options')
            ){
        get_sidebar('left');
    }
    if(current_user_can('manage_options')){
        get_sidebar('admin-dev');
    }
                ?>
            </div>
            <?php
            }
            
            if(isset($have_menu['in1']) && $have_menu['in1']){
            ?>
            <div class="col-12 col-md-2 side-menu">
                <?php
//    if(current_user_can('ml_access_menu_u_in')
//        ||current_user_can('ml_access_menu_d_in')
//        || current_user_can('manage_options')
//            ){
if( ( (isset($have_menu['ml_access_menu_u_in']) && $have_menu['ml_access_menu_u_in'] == 1) )
    || ( (isset($have_menu['ml_access_menu_d_in']) && $have_menu['ml_access_menu_d_in'] == 1))){
        get_sidebar('left-in-1');
    }
                ?>
            </div>
            <?php
            }
            ?>
            <div class="col-12 <?=$content_class ?>">
                

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', 'page-dshop-clear' );

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
$ctnt = ob_get_clean();

get_header();
echo $ctnt;
//get_sidebar();
get_footer();
