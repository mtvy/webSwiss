<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package medlab
 */

ob_start();

global $have_menu;
if(current_user_can('ml_access_menu_u_l')
    ||current_user_can('ml_access_menu_d_l'))
        $have_menu['l']=1;
if(current_user_can('manage_options'))
        $have_menu['l']=1;

if(current_user_can('ml_access_menu_u_in')
    ||current_user_can('ml_access_menu_d_in'))
        $have_menu['in1']=1;

$content_class = 'col-md-12';
if(isset($have_menu['l']) && $have_menu['l'])
    $content_class = 'col-md-9';
if(isset($have_menu['in1']) && $have_menu['in1'])
    $content_class = 'col-md-9';
if(isset($have_menu['l']) && $have_menu['l']
    && isset($have_menu['in1']) && $have_menu['in1'])
    $content_class = 'col-md-6';
?>

	<div id="primary" class="content-area container">
		<main id="main" class="site-main row">
            <?php
            if(isset($have_menu['l']) && $have_menu['l']){
            ?>
            <div class="col-12 col-md-3 side-menu">
                <?php
    if(current_user_can('ml_access_menu_u_l')
        ||current_user_can('ml_access_menu_d_l')){
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
            <div class="col-12 col-md-3 side-menu">
                <?php
    if(current_user_can('ml_access_menu_u_in')
        ||current_user_can('ml_access_menu_d_in')){
        get_sidebar('left-in-1');
    }
                ?>
            </div>
            <?php
            }
            ?>
            <div class="col-12 <?=$content_class ?>">

			<section class="error-404 not-found">
				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'medlab' ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content">
					<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'medlab' ); ?></p>

					<?php
					get_search_form();

					the_widget( 'WP_Widget_Recent_Posts' );
					?>

					<div class="widget widget_categories">
						<h2 class="widget-title"><?php esc_html_e( 'Most Used Categories', 'medlab' ); ?></h2>
						<ul>
							<?php
							wp_list_categories( array(
								'orderby'    => 'count',
								'order'      => 'DESC',
								'show_count' => 1,
								'title_li'   => '',
								'number'     => 10,
							) );
							?>
						</ul>
					</div><!-- .widget -->

					<?php
					/* translators: %1$s: smiley */
					$medlab_archive_content = '<p>' . sprintf( esc_html__( 'Try looking in the monthly archives. %1$s', 'medlab' ), convert_smilies( ':)' ) ) . '</p>';
					the_widget( 'WP_Widget_Archives', 'dropdown=1', "after_title=</h2>$medlab_archive_content" );

					the_widget( 'WP_Widget_Tag_Cloud' );
					?>

				</div><!-- .page-content -->
			</section><!-- .error-404 -->

            </div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
$ctnt = ob_get_clean();

get_header();
echo $ctnt;
//get_sidebar();
get_footer();
