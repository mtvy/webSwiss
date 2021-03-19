<?php

/* 
 * class.WSDCharts.php
 */

class WSDCharts{
    public $data = [];
    public $img=null;
    public function __construct() {
        ;
    }
    public function init(){
        
    }
    public function set($attr=[]){
        $attr_=[
            'bgcol' => 'ffddff',
            'gridcol' => '888888',
            'chartcol' => 'b8b8b8',
            'linecol' => [],
            'spotR' => 7,
            'xtitle' => [],
            'ytitle' => [],
//            'bgcol' => '',
            'data' => [],
        ];
        foreach($attr_ as $k=>$v){
            if(!isset($attr[$k]))$attr[$k] = $v;
        }
        // Задаем изменяемые значения #######################################
        
        $this->spotRadius = $attr['spotR'];

        // Размер изображения

        $W=16384;
        $H=8192;

        $W=1630;
        $H=810;
        $W=16384/4;
        $H=8192/4;
        $W=800;
        $H=400;
        
        $this->h=$H;
        $this->w=$W;


        // Отступы
        $MB=20;  // Нижний
        $ML=8;   // Левый 
        $M=5;    // Верхний и правый отступы.
                 // Они меньше, так как там нет текста
        $this->mb=$MB;
        $this->ml=$ML;
        $this->m=$M;

        // Ширина одного символа
        $LW=imagefontwidth(2);
//        $LW=imagefontwidth(5);
        $this->lw = $LW;
        
        // Задаем входные данные ############################################

        // Входные данные - три ряда, содержащие случайные данные.
        // Деление на 2 и 3 взято для того чтобы передние ряды не 
        // пересекались

        // Массив $DATA["x"] содержит подписи по оси "X"

//        $DATA=[];
//        $tx=[];
//        $ty=[];
//        for ($i=0;$i<20;$i++) {
//            $DATA[0][]=rand(0,100);
//            $DATA[1][]=rand(0,100)/2;
//            $DATA[2][]=rand(0,100)/3;
//            //$DATA["x"][]=$i;
//            $tx[]=$i;
//        }
        $this->tx=$attr['xtitle'];
        $this->ty=$attr['ytitle'];
        $this->data = $attr['data'];
        
        // Создадим изображение
        $this->img=imagecreate($W,$H);
        $im = $this->img;
        
        // bar colors
        $lcolor = [];
//        $lcolor [] = $this->hex2rgb('ff0000'); 
//        $lcolor [] = $this->hex2rgb('00ff00'); 
//        $lcolor [] = $this->hex2rgb('0000ff'); 
        foreach($attr['linecol'] as $k=>$hex){
            $lcolor [$k] = $this->hex2rgb($hex);
        }
        $this->lcolor = $lcolor;
        
        // background color
        $this->bgColor=$this->hex2rgb($attr['bgcol']); // ffddff
        
        // chart background color
        $this->bgColorChart=$this->hex2rgb($attr['chartcol']); // ffddff
        
        // chart line color
        $this->lineColorChart=$this->hex2rgb($attr['gridcol']); // ffddff
        
    }
    public function build($attr=[]){
        $DATA = $this->data ;
        $tx = $this->tx;
        $ty = $this->ty;
        $H = $this->h;
        $W = $this->w;
        $MB = $this->mb;
        $ML = $this->ml;
        $M = $this->m;
        $LW = $this->lw;

        // Подсчитаем количество элементов (точек) на графике
        $count=count($DATA[0]);
//        if (count($DATA[1])>$count) $count=count($DATA[1]);
//        if (count($DATA[2])>$count) $count=count($DATA[2]);
        foreach($DATA as $k=>$v){
            if (count($v)>$count) $count=count($v);
        }

        if ($count==0) $count=1;

        // Сглаживаем графики ###############################################
//        if (0 || isset($_GET["smooth"]) && $_GET["smooth"]==1) {
//
//            // Добавим по две точки справа и слева от графиков. Значения в
//            // этих точках примем равными крайним. Например, точка если
//            // y[0]=16 и y[n]=17, то y[1]=16 и y[-2]=16 и y[n+1]=17 и y[n+2]=17
//
//            // Такое добавление точек необходимо для сглаживания точек
//            // в краях графика
//
//            for ($j=0;$j<3;$j++) {
//                $DATA[$j][-1]=$DATA[$j][-2]=$DATA[$j][0];
//                $DATA[$j][$count]=$DATA[$j][$count+1]=$DATA[$j][$count-1];
//                }
//
//            // Сглаживание графики методом усреднения соседних значений
//
//            for ($i=0;$i<$count;$i++) {
//                for ($j=0;$j<3;$j++) {
//                    $DATA[$j][$i]=($DATA[$j][$i-1]+$DATA[$j][$i-2]+
//                                   $DATA[$j][$i]+$DATA[$j][$i+1]+
//                                   $DATA[$j][$i+2])/5;
//                    }
//                }
//            }


        // Подсчитаем максимальное значение
        $max=0;

//        for ($i=0;$i<$count;$i++) {
//            $max=$max<$DATA[0][$i]?$DATA[0][$i]:$max;
//            $max=$max<$DATA[1][$i]?$DATA[1][$i]:$max;
//            $max=$max<$DATA[2][$i]?$DATA[2][$i]:$max;
//            }
        foreach($DATA as $k=>$v){
            foreach($v as $k2=>$m){
                $max=$max<$m?$m:$max;
            }
        }

        // Увеличим максимальное значение на 10% (для того, чтобы столбик
        // соответствующий максимальному значение не упирался в в границу
        // графика
        $max=intval($max+($max/10));

        // Количество подписей и горизонтальных линий
        // сетки по оси Y.
        $county=10;

        // Работа с изображением ############################################
        $im = $this->img;

        // Цвет левой грани графика (серый)
        $bg[2]=imagecolorallocate($im,212,212,212); // ?

        // Цвет сетки (серый, темнее)
//        $c=imagecolorallocate($im,184,184,184);
        $c = $this->lineColorChart;

        // Цвет текста (темно-серый)
        $text=imagecolorallocate($im,136,136,136);

        // Цвета для линий графиков
//        $bar  = $this->lcolor;

        $text_width=0;
        // Вывод подписей по оси Y
        for ($i=1;$i<=$county;$i++) {
            $strl=strlen(($max/$county)*$i)*$LW;
            if ($strl>$text_width) $text_width=$strl;
        }

        // Подравняем левую границу с учетом ширины подписей по оси Y
        $ML+=$text_width;

        // Посчитаем реальные размеры графика (за вычетом подписей и
        // отступов)
        $RW=$W-$ML-$M;
        $RH=$H-$MB-$M;

        // Посчитаем координаты нуля
        $X0=$ML;
        $Y0=$H-$MB;

        $step=$RH/$county;

        // Вывод главной рамки графика
        imagefilledrectangle($im, 0, 0, $W, $H, $this->bgColor);
        imagefilledrectangle($im, $X0, $Y0-$RH, $X0+$RW, $Y0, $this->bgColorChart);
        imagerectangle($im, $X0, $Y0, $X0+$RW, $Y0-$RH, $c);

        // Вывод сетки по оси Y
        for ($i=1;$i<=$county;$i++) {
            $y=$Y0-$step*$i;
            imageline($im,$X0,$y,$X0+$RW,$y,$c);
            imageline($im,$X0,$y,$X0-($ML-$text_width)/4,$y,$text);
        }

        // Вывод сетки по оси X
        // Вывод изменяемой сетки
        for ($i=0;$i<$count;$i++) {
            imageline($im,$X0+$i*($RW/$count),$Y0,$X0+$i*($RW/$count),$Y0,$c);
            imageline($im,$X0+$i*($RW/$count),$Y0,$X0+$i*($RW/$count),$Y0-$RH,$c);
        }

        // Вывод линий графика
        $dx=($RW/$count)/2;
        
        foreach($DATA as $k=>$v){
            if(count($v)==0)continue;
            $px=intval($X0+$dx);
            $pi=$Y0-($RH/$max*(float)$v[0]);
            $count = count($v);
            $num = 0;
            foreach($v as $i=>$m){
                
//                $x=intval($X0+$i*($RW/$count)+$dx);
                $x=intval($X0+$num*($RW/$count)+$dx);

                $y=$Y0-($RH/$max*(float)$m);
                imageline($im,$px,$pi,$x,$y,$this->lcolor[$k]);
                imagefilledellipse($im,$x,$y,$this->spotRadius,$this->spotRadius,$this->lcolor[$k]);
//                imageline($im,$px,$pi,$x,$m,$bar[$k]);
                $pi=$y;
                $px=$x;
                $num++;
            }
        }

        // Уменьшение и пересчет координат
        $ML-=$text_width;

        // Вывод подписей по оси Y
        for ($i=1;$i<=$county;$i++) {
            $str=($max/$county)*$i;
            imagestring($im,2,
                    $X0-strlen($str)*$LW-$ML/4-2,
                    $Y0-$step*$i-imagefontheight(2)/2,$str,$text);
        }

        // Вывод подписей по оси X
        $prev=100000;
        $twidth=$LW*strlen($tx[0])+6;
//        $twidth=$LW*strlen($DATA["x"][0])+6;
        $i=$X0+$RW;

        while ($i>$X0) {
            if ($prev-$twidth>$i) {
                $drawx=$i-($RW/$count)/2;
                if ($drawx>$X0) {
                    $textKey = round(($i-$X0)/($RW/$count))-1;
//                    $str=$tx[round(($i-$X0)/($RW/$count))-1];
//                    $str=$DATA["x"][round(($i-$X0)/($RW/$count))-1];
                    
                    $str = 'xxx';
                    if(isset($tx[$textKey]))
                        $str = $tx[$textKey];
                    
                    imageline($im,$drawx,$Y0,$i-($RW/$count)/2,$Y0+5,$text);
                    imagestring($im,2, $drawx-(strlen($str)*$LW)/2, $Y0+7,$str,$text);
                }
                $prev=$i;
            }
            $i-=$RW/$count;
        }
        $this->img = $im;
    }
    public function rebuild($attr=[]){
        
    }
    public function show($attr=[]){

        return $this->img($attr);
        
    }
    public function img($attr=[]){
        global $ht;
        $src = '';
        $src = $this->asis();
        $at = [];
        $at['src']=$src;
        if(isset($attr['class']))
            $at['class'] = $attr['class'];
        if(isset($attr['h']))
            $at['height'] = $attr['h'];
        if(isset($attr['w']))
            $at['width'] = $attr['w'];
        return $ht->f('img','',$at);
    }
    public function asis(){

        ob_start();
        // Генерация изображения
        ImagePNG($this->img);
        $out = ob_get_clean();

        imagedestroy($this->img);
        $out = base64_encode($out);
//        $out = base64_encode(file_get_contents($out));
        return 'data:image/png;base64, '.$out;
    }
    public function save($path=''){

        // Генерация изображения
        ImagePNG($this->img,$path);

        imagedestroy($this->img);
        
    }
    public function flash(){
        header("Content-Type: image/png");

        // Генерация изображения
        ImagePNG($this->img);

        imagedestroy($this->img);
        
    }
    public function update(){
        
    }
        
    public function hex2rgb($hex=''){
        $hex=  preg_replace('/[^0-9,a,b,c,d,e,f,A,B,C,D,E,F]/', '', $hex);
        $hex = str_split($hex);
        $rbg=[0,0,0];
        $dec='';
        $rbgcou=0;
        for($i=0;$i<(count($hex)>6?6:count($hex));$i++){
            $dec .= $hex[$i];
            if(strlen($dec)==2){
                $dec = hexdec($dec);
                $rbg[$rbgcou] = $dec;
                $rbgcou++;
                $dec = '';
            }
            if($rbgcou==3)break;
        }
        $color = imagecolorallocate($this->img,$rbg[0],$rbg[1],$rbg[2]); 
        return $color;
    }
    
}
