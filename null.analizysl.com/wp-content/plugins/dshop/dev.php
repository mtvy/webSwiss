<?php

/* 
dev::pre('test 1 '.date('H:i:s',time()),'controller header: file Start, line: '.__LINE__);
 */


class dev{
    public static $path_count = 5;
    public static $dir_count = 5;
    public static $def_path_count = 5;
    public static $def_dir_count = 5;
    public static function _path_from($l=2){
        $_d=debug_backtrace();
        $f_='';
        $f = str_replace('\\','/',$_d[$l]['file']);
    //    __FILE__.':'.__LINE__
        $f = explode('/',$f);
        $p = [];
        for($dc=0; $dc < self::$dir_count; $dc++){
            $p[] = array_pop($f);
        }
        $p = array_reverse($p);
        $pf = '/'.implode('/',$p).' : '.$_d[$l]['line'];
        return $pf;
    }
            
    public static function path_from(){
        $p = [];
        for($pc=0; $pc < dev::$path_count; $pc++){
            $p[] = self::_path_from($pc+3);
        }
        $path_from = implode('<br/>',$p);
        return $path_from;
    }
    
    public static function pre_sql($d, $title = '', $show = 1)
    {
        $d = self::pre_sql_parse($d);
        $out = self::_pre($d, $title, $show);
        return $out;
    }
    
    public static function pre_sql_parse($d)
    {
        $_r = [];
        $_r [] = ",";
        $_r [] = "FROM";
        $_r [] = "LEFT";
        $_r [] = "WHERE";
        $_r [] = "AND";
        $_r [] = "LIMIT";
        $r = [];
        foreach ($_r as $v) {
            $r [$v] = "\n".$v;
        }
        $out = strtr($d,$r);
        return $out;
    }
    
    public static function pre($d, $title = '', $show = 1,$pc=false)
    {
        if($pc!==false)self::$path_count = $pc;else self::$path_count = self::$def_path_count;
        $out = self::_pre($d, $title, $show);
        return $out;
    }
    
    public static function _pre($d, $title = '', $show = 1)
    {
        $from = self::path_from();
        
        $d = print_r($d,1);
        $wr_tpl = '<section class="description container">
  <p>_t_</p>
  <p>_f_</p>
  <div class="row">
    <div class="col-md-12"><pre>_d_</pre>
    </div>
    <div class="clearfix"></div>
  </div>   
 </section>';
        $r = ['_t_' => $title, '_f_' => $from, '_d_' => $d];
        $out = strtr($wr_tpl,$r);
        if ($show) echo $out;
        return $out;
    }
}