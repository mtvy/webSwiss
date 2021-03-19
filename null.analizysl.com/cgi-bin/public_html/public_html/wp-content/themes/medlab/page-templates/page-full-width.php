<?php
/**
 * Template Name: full-width
 * The template for displaying all pages
 * page-full-width.php
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
 * e-full-width.php
 */

		global $wpdb,$msect;
ob_start();

//add_log($have_menu);

$content_class = 'col-md-12';
//?>

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
    <?=$msect?><?php
                if ( function_exists('showLogInfo') ){
                    ob_start();
                    showLogInfo();
                    $log = ob_get_clean();
                    if($log){
                        ?>
    <div class="container pt-5">
        <div class="row bg-white">
            <div class="col-12 mt-3" id="info_messages_wrap"><?=$log?></div>
        </div>
    </div>
                            <?php
                    }
                }
            ?>
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

<?php wp_footer(); ?>

</body>
</html>
