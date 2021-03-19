<?php

/* 
 * tabs.php
 */
$tabs = [];
$tab_url = get_the_permalink( get_the_ID() ) ;
foreach($this->tabs as $k=>$tab){
    $active = '';
    if( ($this->tab == '' && $k == 'main') || $this->tab == $k)$active = ' active ';
    $router = [];
    $router['tab']= $k;
    $href = $tab_url . '?' . http_build_query($router);
    $r=[];
    $r['class'] = 'nav-link disabled';
    $r['class'] = 'nav-link nav-tab-button '.$active;
    $r['href'] = $href;
    $a = $ht->f('a',$tab,$r);
    $r=[];
    $r['class'] = 'nav-item';
    $tabs[] = $ht->f('li',$a,$r);
}
?>
<div class="container-fluid -m-2 -border -border-primary -mb-2">
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-tabs" id="tab-buttons">
                <?=implode("\n",$tabs)?>
<!--                <li class="nav-item">
                  <a class="nav-link disabled" href="">tab: <?=$this->tab?></a>
                </li>-->
            </ul>
        </div>
    </div>
</div>