<?php

/* 
 * class.WSDHtmlBuild.php
 */
include_once 'trait.DShopHtml.php';

class WSDHtmlBuild{

    use 
            DShopHtml
            ;
    public $mod = 'wsd_acc_inst_serv';
    public $form_method='get';
    public function __construct($mod=false) {
//        if($mod)
            $this->mod = $mod;
    }
    public function access($r_access=[]){
        $access = false;
        $user = wp_get_current_user();
        if($user!==null && count( array_intersect($r_access, (array) $user->roles ) ) > 0 ){
//            get_template_part( 'template-parts/page/tpl.page-access', 'denied' );
        //    get_template_part( 'template-parts/page/tpl.page-access', 'notfound' );
            $access = true;
        }
        return $access ;
    }
    public function getpost($method,$f=false,$d='',$type=FILTER_SANITIZE_STRING){
        global $inputs ;
        $inputs = [];
        $inputs_ = [];
        
        if($f===false)return $d;
//        $out=$d;
//        if(isset($_GET[$f]))$out=$_GET[$f];
//        return $out;
        
        if (!filter_has_var($method, $f)) return $d;
        $opt = array('default' => $d);
        $inputs_[$f] = array('filter'=>$type, 'options' => $opt);
//        echo $this->pre($inputs_);
        $inputs = filter_input_array($method,$inputs_);
        if($inputs[$f] === null)$inputs[$f]=$d;
        if($inputs[$f] === false)$inputs[$f]=$d;
        if(strlen($inputs[$f])==0)$inputs[$f]=$d;
        return $inputs[$f];
    }
    public function get($f=false,$d='',$type=FILTER_SANITIZE_STRING){
        return $this->getpost(INPUT_GET,$f,$d,$type);
    }
    public function post($f=false,$d='',$type=FILTER_SANITIZE_STRING){
        return $this->getpost(INPUT_POST,$f,$d,$type);
    }
    /**
     * 
     * @param string $f field name
     * @param string $d default value
     * @param int $type filter
     * @param string $regexp regular query for filter
     * @return type
     */
    public function postget($f=false,$d='',$type= FILTER_SANITIZE_STRING , $regexp = false ){
        $opts = null;
        if($regexp) $opts = ['options' =>['regexp' => $regexp]];
        $method = INPUT_POST;
        if (!filter_has_var($method, $f)) $method = INPUT_GET;
        if (!filter_has_var($method, $f)) return $d;
        return filter_input($method, $f, $type,$opts);
    }

    function btabl($atts = []){
//        global $ht;
        $ht = $this;
        $usenumbers = true;
        $cclass = [];
        $hitems = [];
        $inorder = [];
        $orders = [];
        $sortVName = 'order';
        $urlget = [];
        $ma='↓';
        $md='↑';
        $sortClass='btn';
        $data = [];
    //    $orders = [];
    //    $orders = [];
    /*
     * /////////////////////
     * инициализация набора данных
     */
        $defs =[];
        $defs['usenumbers'] = true;
        $defs['hclass'] = '';
        $defs['dclass'] = '';
        $defs['rclass'] = [];
        $defs['cclass'] = [];
        $defs['hitems'] = [];
        $defs['inorder'] = [];
        $defs['orders'] = [];
        $defs['sortVName'] = 'order';
        $defs['urlget'] = [];
        $defs['ma']='↓';
        $defs['md']='↑';
        $defs['sortClass']='btn';
        $defs['data'] = [];
    //    $defs['cclass'] = [];
    //    $defs['cclass'] = [];
    //    $defs['cclass'] = [];
        $defs[''] = null;
        $defs[''] = null;
        $atts = (array) $atts;
        foreach ( $defs as $name => $default ) {
            if (! array_key_exists( $name, $atts ) ) {
                $atts[ $name ] = $default;
            }
        }
        extract($atts);
        
        foreach($inorder as &$inorde)
            $inorde = $inorde-!$usenumbers;
        
        /*
         * /////////////////////
         * построение заголовков
         */
        //$th['s'] = $ht->th($ht->fsort('Услуга',$thnum++,$orders,'order',$urlget),'','');
        $th=[];$thnum=0;
        $c = [];
        $at=[];
        $at['class']='col-_ncol_';
        if($usenumbers){
            $at_=$at;
            if(isset($cclass[0])){
                $at_['class']=strtr($at_['class'],['_ncol_'=>$cclass[0]]);
            }
            $c_ = $ht->f('div',$this->f('strong','№'),$at_);
            $c []= $c_;
        }
        foreach($hitems as $cnum=>$item){
            $at_=$at;
            if(isset($cclass[$cnum+$usenumbers])){
                $at_['class']=strtr($at_['class'],['_ncol_'=>$cclass[$cnum+$usenumbers]]);
            }
        //    $item = $ht->fsort($item,$thnum++,$orders,'order',$urlget);

            $item = $this->f('strong',$item);
            if(in_array($cnum+$usenumbers,$inorder))
                $item = $ht->asort($item,$thnum++,$orders,$sortVName,$urlget,$ma,$md,$sortClass);
            $c []= $ht->f('div',$item,$at_);
        }

        $r=[];
        $r['_ncol_']=1;
        if((count($c)-count($cclass))>0)
        $r['_ncol_']=floor((12-array_sum($cclass))/(count($c)-count($cclass)));
        $c = implode("\n",$c);
        $c = strtr($c,$r);
        $at=[];
        $at['class']='row';
        $row = $ht->f('div',$c,$at);

        $rowsh = [];
        $rows = [];
        $rowsh[]=$row;

        /*
         * /////////////////////
         * построение данных
         */
        foreach($data as $rnum=>$items){
            if($rnum>0 && ($rnum%20)==0){
                
            }

            $c = [];
            $at=[];
            $at['class']='col-_ncol_';
            if($usenumbers){
                $at_=$at;
                if(isset($cclass[0])){
                    $at_['class']=strtr($at_['class'],['_ncol_'=>$cclass[0]]);
                }
                $c_ = $ht->f('div',$rnum+1,$at_);
                $c []= $c_;
            }
            foreach($items as $cnum=>$item){
                $at_=$at;
                if(is_array($item)&&isset($item['class'])&&isset($item['val'])){
                    $at_['class']=$item['class'];
                    $item = $item['val'];
                }else
                if(isset($cclass[$cnum+$usenumbers])){
                    $at_['class']=strtr($at_['class'],['_ncol_'=>$cclass[$cnum+$usenumbers]]);
                }
        //        if($cnum==0 and $usenumbers)
        //        $c_ = $ht->f('div',$item,$at_);
        //        else
                $c_ = $ht->f('div',$item,$at_);
                $c []= $c_;
            }

            $r=[];
            $r['_ncol_']=1;
            if((count($c)-count($cclass))>0)
            $r['_ncol_']=floor((12-array_sum($cclass))/(count($c)-count($cclass)));
            $r['_csize_']=$r['_ncol_']*(count($c)-count($cclass))+array_sum($cclass);
            $c = implode("\n",$c);
        //    $c = strtr($c,['DShop'=>'_ncol_','hi'=>'_ncol_']);
        //    $c = strtr($c,['DShop'=>'_csize_','hi'=>'_csize_']);
            $c = strtr($c,$r);
            
            $at=[];
            $at['class']='row ';
            if(isset($rclass[$rnum])){
                $at['class'] .= $rclass[$rnum];
            }
            $row = $ht->f('div',$c,$at);

            $rows[]=$row;
        }
//        add_log($atts);
//        add_log($defs);

        /*
         * /////////////////////
         * вывод
         */
        $c = implode("\n",$rowsh);
        $at=[];
        $at['class']='-container '.$hclass;
        $rowh = $ht->f('div',$c,$at);
        
        $c = implode("\n",$rows);
        $at=[];
        $at['class']='-container stripped-rows '.$dclass;
        if($hitems) $at['class']='-container stripped-rows-h ';
        $row = $ht->f('div',$c,$at);

        $styles="
        .stripped-rows-h > div:nth-of-type(odd) {
            /*background: #e0e0e0;*/
            background-color: #F5F7FA;
            background-color: rgba(0,0,0,.05);
        }
        .stripped-rows-h > div:nth-of-type(even) {
            /*background: #FFFFFF;*/
        }
        .stripped-rows-h > div:nth-of-type(odd) {
        }
        .stripped-rows > div:nth-of-type(even) {
            background-color: #F5F7FA;
            background-color: rgba(0,0,0,.05);
        }
        .stripped-rows-h > div:hover,
        .stripped-rows > div:hover
        {
            background-color: rgba(0,0,0,.075);
        }
          ";
        $styles = $ht->f('style',$styles);
        $out = '';
        $out .= $styles;

        $out .= $rowh;
        $out .= $row;

        return $out;
    }
}