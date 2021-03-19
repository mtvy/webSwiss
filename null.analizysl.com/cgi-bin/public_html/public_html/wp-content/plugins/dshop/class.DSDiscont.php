<?php

/* 
 * class.DSDiscont.php
 */
//include_once 'class.DShopInit.php';

class DSDiscont // extends DShopInit
{
//    private static $instance = null;
//	private static $initiated = false;
    public $discts = [];
    public function __construct()
    {
        $discts = [];
        $discts[0]=['descont'=>0,'mlCode'=>'0','name'=>'0%'];
        $discts[1]=['descont'=>100,'mlCode'=>'100','name'=>'100% скидка'];
        $discts[2]=['descont'=>5,'mlCode'=>'A-5','name'=>'5% Акция'];
        $discts[3]=['descont'=>7,'mlCode'=>'A-7','name'=>'7% Акция'];
        $discts[4]=['descont'=>10,'mlCode'=>'A-10','name'=>'10% Акция'];
        $discts[5]=['descont'=>20,'mlCode'=>'A-20','name'=>'20% Акция'];
        $discts[6]=['descont'=>25,'mlCode'=>'A-25','name'=>'25% Акция'];
        $discts[7]=['descont'=>30,'mlCode'=>'A-30','name'=>'30% Акция'];
        $discts[8]=['descont'=>40,'mlCode'=>'A-40','name'=>'40% Акция'];
        $discts[9]=['descont'=>50,'mlCode'=>'A-50','name'=>'50% Акция'];
        $discts[10]=['descont'=>60,'mlCode'=>'A-60','name'=>'60% Акция'];
        $discts[11]=['descont'=>3,'mlCode'=>'ДК-3','name'=>'Дисконтная карта-3%'];
        $discts[12]=['descont'=>5,'mlCode'=>'ДК-5','name'=>'Дисконтная карта-5%'];
        $discts[13]=['descont'=>7,'mlCode'=>'ДК-7','name'=>'Дисконтная карта-7%'];
        $discts[14]=['descont'=>15,'mlCode'=>'ДК-15','name'=>'Дисконтная карта-15%'];
        $discts[15]=['descont'=>30,'mlCode'=>'ДК-30','name'=>'Дисконтная карта-30%'];
        $discts[16]=['descont'=>50,'mlCode'=>'ДК-50','name'=>'Дисконтная карта-50%'];
        $this->discts = $discts;
//        parent::__construct();
    }
    public function get_discont_name($id='',$def=''){
        if(isset($this->discts[$id]['name'])){
            return $this->discts[$id]['name'];
        }else{
            return $def;
        }
    }
    public function get_discont_code($id='',$def=''){
        if(isset($this->discts[$id]['mlCode'])){
            return $this->discts[$id]['mlCode'];
        }else{
            return $def;
        }
    }
    public function get_discont_id($name=false,$def=0){
//                    add_log($name);
//                    add_log($def);
//                    add_log($_SESSION);
        if(isset($_SESSION['ds_discont_id'][$name])){
            return $_SESSION['ds_discont_id'][$name];
        }else{
            return $def;
        }
    }
    
    public function add_to_cart_discont($name='',$percent=0,$from=0,$id=''){
        if(empty($_SESSION['ds_discont'])){
            $_SESSION['ds_discont']=array();
            $_SESSION['ds_discont_id']=array();
        }
        if($name==='')return;
        
        //if($percent>0)
            $_SESSION['ds_discont'][$name]=$percent;
            $_SESSION['ds_discont_id'][$name]=$id;
        
        if(empty($_SESSION['ds_discont_from'])){
            $_SESSION['ds_discont_from']=array();
        }
        if($from>0)
        $_SESSION['ds_discont_from'][$name]=$from;
    }
    public function remove_from_cart_discont($name=false){
        if(!$name){
            $_SESSION['ds_discont']=array();
            $_SESSION['ds_discont_from']=array();
            $_SESSION['ds_discont_id']=array();
        }else{
            unset($_SESSION['ds_discont'][$name]);
            unset($_SESSION['ds_discont_from'][$name]);
            unset($_SESSION['ds_discont_id'][$name]);
        }
    }
    public function get_cart_discont($name=false,$def=0){
        if(isset($_SESSION['ds_discont'][$name])){
            return $_SESSION['ds_discont'][$name];
        }else{
            return $def;
        }
    }
    public function get_cart_discont_id($name=false,$def=''){
        if(isset($_SESSION['ds_discont_id'][$name])){
            return $_SESSION['ds_discont_id'][$name];
        }else{
            return $def;
        }
    }
    public function get_cart_discont_from($name=false,$def=0){
        if(isset($_SESSION['ds_discont_from'][$name])){
            return $_SESSION['ds_discont_from'][$name];
        }else{
            return $def;
        }
    }
    
    
    public function __call($name, $arguments) {
        ;
    }
    public static function __callStatic($name, $arguments) {
        self::_init();
        $_name = $name;
        if(strlen($name)>1 && $name[0] === '_'){
            $name = str_split($name);
            unset($name[0]);
            $name = implode('',$name);
        }
        
//        $arrdbg = [];
//        $arrdbg [] = '_get_discont_id';
//        $arrdbg [] = '_get_discont_name';
//        $arrdbg [] = '_get_discont_code';
//        if(in_array($_name,$arrdbg) ){
//            add_log($_name);
//            add_log($name);
//            add_log($arguments);
//            add_log(count($arguments));
//            add_log(self::$instance->$name);
//            if(count($arguments)==2) add_log(self::$instance->$name($arguments[0],$arguments[1]));
//        }
//        add_log($arguments);
        if(count($arguments)==1)
            return self::$instance->$name($arguments[0]);
        if(count($arguments)==2)
            return self::$instance->$name($arguments[0],$arguments[1]);
        if(count($arguments)==3)
            return self::$instance->$name($arguments[0],$arguments[1],$arguments[2]);
        if(count($arguments)==4)
            return self::$instance->$name($arguments[0],$arguments[1],$arguments[2],$arguments[3]);
        if(count($arguments)==5)
            return self::$instance->$name($arguments[0],$arguments[1],$arguments[2],$arguments[3],$arguments[4]);
        if(count($arguments)==6)
            return self::$instance->$name($arguments[0],$arguments[1],$arguments[2],$arguments[3],$arguments[4],$arguments[5]);
        if(count($arguments)==7)
            return self::$instance->$name($arguments[0],$arguments[1],$arguments[2],$arguments[3],$arguments[4],$arguments[5],$arguments[6]);
        if(count($arguments)==8)
            return self::$instance->$name($arguments[0],$arguments[1],$arguments[2],$arguments[3],$arguments[4],$arguments[5],$arguments[6],$arguments[7]);
        return self::$instance->$name($arguments);
//        return self::$instance->$name(extract ($arguments));
//        return self::$instance->$name($arguments);
//        add_log($name);
    }
    public function __get($name) {
        ;
    }
    public function __set($name, $value) {
        ;
    }
    public function __invoke() {
        ;
    }
    
    private static $instance = null;
	private static $initiated = false;
	public static function _init() {
		if ( ! self::$initiated ) {
			self::_init_hooks();
		}
	}
	public static function _init_hooks() {
//        add_log(static::class);
        $o = static::class;
        $alleg = new $o();
		self::$instance = $alleg;
		self::$initiated = true;
        $alleg->__init();
        
	}
    public function __init(){
    }
    
}