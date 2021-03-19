<?php

/* 
 * tpl-content-wrapp-start
 */


global $have_menu;
$have_menu['l']=1;
//add_log($have_menu);

//$content_class = 'col-md-12';
$content_class = 'col-md-9';
?>

	<div id="primary" class="content-area container">
		<main id="main" class="site-main row">
            <div class="col-12 col-md-3 side-menu">
                <?php
    get_sidebar('left');
    if(current_user_can('manage_options')){
        get_sidebar('admin-dev');
    }
                ?>
            </div>
            <div class="col-12 <?=$content_class ?>">
                

		<?php
        