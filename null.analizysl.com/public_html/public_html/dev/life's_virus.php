<?php

/* 
 * life's virus
 * life's_virus.php
 * https://analizysl.com/dev/life's_virus.php
 */

$ser  = 'a:11:{s:9:"viewCount";s:4:"1728";s:9:"likeCount";s:3:"209";s:12:"dislikeCount";s:1:"6";s:13:"favoriteCount";s:1:"0";s:12:"commentCount";s:2:"16";s:8:"duration";s:5:"PT16S";s:9:"dimension";s:2:"2d";s:10:"definition";s:2:"hd";s:7:"caption";s:5:"false";s:15:"licensedContent";b:1;s:10:"projection";s:11:"rectangular";}';
$mch=null;
$duration = $statistic['duration'];
$dr = [];
$dr [] = 'PT16S';
$dr [] = 'PT16M';
$dr [] = 'PT16H';
$dr [] = 'PT16H45S';
$dr [] = 'PT16H45M';
$dr [] = 'PT16HM45S';
$dr [] = 'PT136H3M45S';

echo '<br/>';
echo '$dr';
echo '<pre>';
print_r($dr);
echo '</pre>';
foreach($dr as   $duration){
//        $statistic=unserialize($v['statistic']);
        $statistic=unserialize($ser);
        $xf_=[];
        $pt = "/(\d+H)?(\d+M)?(\d+S)?/i";
        $pt = "/(\d+S)/";
        $pt = "/(?=\d+H)|(?=\d+M)|(?=\d+S)/";
//        if(isset($statistic['duration'])){
echo '<br/>';
echo 'колония выжила';
            $drn = [];
            $pt = "/(\d+H)/";
            if(preg_match($pt, $duration, $mch)){
                if(isset($mch[1])&& strlen($mch[1])>0){
                    $h = preg_replace("/\D/", "", $mch[1]);
                    $drn['h']=$h;
                }
            }
            $pt = "/(\d+M)/";
            if(preg_match($pt, $duration, $mch)){
                if(isset($mch[1])&& strlen($mch[1])>0){
                    $h = preg_replace("/\D/", "", $mch[1]);
                    $drn['m']=$h;
                }
            }
            $pt = "/(\d+S)/";
            if(preg_match($pt, $duration, $mch)){
                if(isset($mch[1])&& strlen($mch[1])>0){
                    $h = preg_replace("/\D/", "", $mch[1]);
                    $drn['s']=$h;
                }
            }
            if(count($drn)){
                if(!isset($drn['m']))$drn['m']=0;
                if(!isset($drn['s']))$drn['s']=0;
                if(isset($drn['h']))
                    $dr = sprintf("%02d:%02d:%02d",$drn['h'],$drn['m'],$drn['s']);
                else
                    $dr = sprintf("%02d:%02d",$drn['m'],$drn['s']);
                $xf_['time'] = $dr;
            }
            
//        }
//echo '<br/>';
//echo '$pt';
//echo '<pre>';
//print_r($pt);
//echo '</pre>';

echo '<br/>';
echo '$pt';
echo '<pre>';
print_r($drn);
echo '</pre>';

echo '<br/>';
echo 'duration';
echo '<pre>';
print_r($duration);
echo '</pre>';

//echo '<br/>';
//echo 'preg_match';
//echo '<pre>';
//var_dump(preg_match($pt, $duration, $mch));
//echo '</pre>';

//echo '<br/>';
//echo '$statistic';
//echo '<pre>';
//print_r($statistic);
//echo '</pre>';

//echo '$mch';
//echo '<pre>';
//print_r($mch);
//echo '</pre>';
echo '$xf_';
echo '<pre>';
print_r($xf_);
echo '</pre>=========================';

}
if(1){return;}

$n = 3;
$cou =  pow($n-1,$n-1 ) - pow(1+($n-1),$n-1 );

if($cou<=0)echo 'колония погибла';
else echo 'колония выжила';

echo '<br/>';
echo $cou;
echo '<br/>';
echo pow($n-1,$n);
echo '<br/>';
echo pow(1+$n,$n );
echo '<br/>';