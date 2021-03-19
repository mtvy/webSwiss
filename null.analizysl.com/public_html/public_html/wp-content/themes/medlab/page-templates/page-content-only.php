<?php
/**
 * Template Name: content-only
 * The template for displaying all pages
 * page-dshop-clear.php
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
 * page-content-only.php
 */

ob_start();

//add_log($have_menu);

$content_class = 'col-md-12';
//?>

	<div id="primary" class="content-area container">
		<main id="main" class="site-main row">
            <?php
            ?>
            <div class="col-12 <?=$content_class ?>">
                

		<?php
		if ( have_posts() ) {
			the_post();
//
//			get_template_part( 'template-parts/content', 'page-only-content' );
		the_content();

			// If comments are open or we have at least one comment, load up the comment template.
//			if ( comments_open() || get_comments_number() ) :
//				comments_template();
//			endif;

		} // End of the loop.
		?>

            </div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
$ctnt = ob_get_clean();

//get_header();
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <div class="container">
        <div class="row ">
            <div class="col-12" id="info_messages_wrap"><?php
                if ( function_exists('showLogInfo') ){
                    showLogInfo();
                }
            ?></div>
        </div>
    </div>
<?php
//$slug = 'zzzzzzzzzzzz';
//
//global $post;
//if(isset($post->post_name))
//    $slug = $post->post_name;
////echo $slug;
//			get_template_part( 'template-parts/component/tpl-header-page', $slug );

//?  >
echo $ctnt;
//get_sidebar();
//get_footer();

?>

<script>
var ajax_url = '<?=admin_url("admin-ajax.php") ?>';
</script>

<?php // wp_footer(); ?>

</body>
</html>
