<?php

/* 
 * tpl-header-page-login
 */

?>
<div id="page" class="siter">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'medlab' ); ?></a>
    <?php if(0){?>

	<header id="masthead" class="site-header container mb-4">
        <div class="row mt-4">
            
            <div class="site-branding col-12 col-md-4 mt-1">
                <?php
                the_custom_logo();
                if ( is_front_page() && is_home() ) :
                    ?>
                    <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                    <?php
                else :
                    ?>
                    <p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
                    <?php
                endif;
                $medlab_description = get_bloginfo( 'description', 'display' );
                if ( $medlab_description || is_customize_preview() ) :
                    ?>
                    <p class="site-description"><?php echo $medlab_description; /* WPCS: xss ok. */ ?></p>
                <?php endif; ?>
            </div><!-- .site-branding -->
            
            <div class="site-branding col-12 col-md-8 text-center mt-1">
                <div class="banner-1">
                    <?php 
                        $attachment_id = 80;
                        $attachment_id = 90;
                        $attachment_id = 176;
                        $size = 'full';
                        $icon = false;
                        $attr = [];
                        echo wp_get_attachment_image( $attachment_id, $size, $icon, $attr );
                    ?>
                </div>
            </div>
        </div>
            
        <div class="row mt-4">
            <div class="site-branding col-12 col-md-12 text-center mt-1">
                <div class="banner-2">
                    <?php 
                        $attachment_id = 81;
                        $attachment_id = 177;
                        $size = 'full';
                        $icon = false;
                        $attr = [];
                        echo wp_get_attachment_image( $attachment_id, $size, $icon, $attr );
                    ?>
                </div>
            </div>
        </div>
            
	</header><!-- #masthead -->
            
    <div class="wrupp-site-nav">
        <div class="container pb-3 pt-3">
            <div class="row ">
                <div class="col-12 text-center">

                    <nav id="site-navigation" class="main-navigation-top ">
                        <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'medlab' ); ?></button>
                        <?php
                        wp_nav_menu( array(
                            'menu_class'=>'nav justify-content-end',
                            'theme_location' => 'menu-1',
                            'menu_id'        => 'primary-menu',
                        ) );
                        ?>
                    </nav><!-- #site-navigation -->
                </div>
            </div>
        </div>
    </div>
    <?php }?>
            
    <?php if(0){ ?>
    <div class="container">
        <div class="row justify-content-end">
            <div class="col-4 text-right"><?php
                if ( function_exists('dynamic_sidebar') ){
                    dynamic_sidebar('sbar-right-cart-btn');
                }
            ?></div>
        </div>
    </div>
    <?php } ?>
            
    <div class="container">
        <div class="row ">
            <div class="col-12" id="info_messages_wrap"><?php
                if ( function_exists('showLogInfo') ){
                    showLogInfo();
                }
            ?></div>
        </div>
    </div>

	<div id="content" class="site-content">
