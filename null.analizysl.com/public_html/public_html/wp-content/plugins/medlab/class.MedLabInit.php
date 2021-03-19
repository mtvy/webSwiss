<?php

/* 
 * class.MedLabInit.php
 */

class MedLabInit{
    
    public function __construct() {
        ;
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
//        add_log($arguments);
        if(count($arguments)==1)
        return self::$instance->$name($arguments[0]);
        if(count($arguments)==2)
        return self::$instance->$name($arguments[0],$arguments[1]);
        if(count($arguments)==3)
        return self::$instance->$name($arguments[0],$arguments[1],$arguments[2]);
        if(count($arguments)==4)
        return self::$instance->$name($arguments[0],$arguments[1],$arguments[2],$arguments[3]);
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
			self::init_hooks();
		}
	}
	public static function init_hooks() {
//        add_log(static::class);
        $o = static::class;
        $alleg = new $o();
		self::$instance = $alleg;
		self::$initiated = true;
        $alleg->init();
        
	}
    public function init(){
    }
    
}