<?php

/* 
 * warehouse.php
 */
include_once 'class.DBConst.php';

class  DBCWarehouse extends DBConst
{
    public $table = '';
    public $filter = [];
    public $filtered = [];
    public $where = [];
    public $data = [];
    public $page = 'warehouse';
    public $action = '';
    public function __construct() {
        $this->page = 'warehouse';
        parent::__construct();
    }
    public function filter(){
        parent::filter();
    }
    public function controller(){
        $this->page = 'warehouse';
        $tpl = '';
        if(strlen($this->action)>0)$tpl = $this->page.'-'.$this->action;
        if(strlen($tpl) && file_exists('page/'.$tpl))$this->page = $tpl;
    }
    public function get(){
        
    }
    public function set(){
        
    }
    public function main(){
        
    }
    public function item(){
        
    }
    public function create(){
        
    }
    public function edit(){
        
    }
    public function delete(){
        
    }
    public function update(){
        
    }
    public function data(){
        
    }
    public function show(){
        ob_start();
        include 'page/'.$this->page.'.php';
        return ob_get_clean();
    }
}
