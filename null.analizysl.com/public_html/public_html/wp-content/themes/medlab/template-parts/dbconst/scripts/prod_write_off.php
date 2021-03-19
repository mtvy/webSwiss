<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
global $wpdb;
$q = "select a.`id` as 'id',"
        . " a.`title` as  'title'"
        . " from `".$wpdb->prefix.'wsd_dbc_'.'wh_write_off_type'."` a"
        //. " where ".implode(' or ',$w)
        ;
//        $mess[] = 
//add_log($this->prepare_query($q));

//        $catlist = $wpdb->get_col($q); // ?
$catlist2 = $wpdb->get_results($q,ARRAY_A);
//add_log($catlist2);
$woft = [];
foreach ($catlist2 as $k=>$v){
    $woft[] = $v['id'].":'".$v['title']."'";
}

//https://threejs.org/docs/index.html#api/en/geometries/TextGeometry
$theefonts_ = [];
$script = '/template-parts/dbconst/s_font/fonts/'; // scripts
$path =  get_template_directory_uri() . $script;
// Font	Weight	Style	File Path
$theefonts_['helvetiker__normal__normal'] = $path . 'helvetiker_regular.typeface.json';
$theefonts_['helvetiker__bold__normal'] = $path . 'helvetiker_bold.typeface.json';
$theefonts_['optimer__normal__normal'] = $path . 'optimer_regular.typeface.json';
$theefonts_['optimer__bold__normal'] = $path . 'optimer_regular.typeface.json';
$theefonts_['gentilis__normal__normal'] = $path . 'gentilis_regular.typeface.json';
$theefonts_['gentilis__bold__normal'] = $path . 'gentilis_bold.typeface.json';
$theefonts_['droid_sans__normal__normal'] = $path . 'droid_sans_regular.typeface.json';
$theefonts_['droid_sans__bold__normal'] = $path . 'droid_sans_bold.typeface.json';
$theefonts_['droid_serif__normal__normal'] = $path . 'droid_serif_regular.typeface.json';
$theefonts_['droid_serif__bold__normal'] = $path . 'droid_serif_bold.typeface.json';
$theefonts_['diamond'] = $path . 'diamond.json';

//add_log($theefonts_);
$theefonts = [];
foreach ($theefonts_ as $k=>$v){
    $theefonts[] = $k.":'".$v."'";
}

// droid_serif_regular.typeface
ob_start();
if(10){
?>
<script>
    var write_off_types = {
<?php
}
echo implode(',',$woft);
if(10){
?>
    };
    var threejs_fonts = {
<?php
}
echo implode(',',$theefonts);
if(10){
?>
    };
</script>
<?php
}
$o = ob_get_clean();
//add_log(htmlspecialchars($o));
echo $o;

