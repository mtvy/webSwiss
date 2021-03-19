<?php

/* 
 * wh_material_name.php
 * {wsd_dbc_wh_material_name]
 */

include_once 'class.DBConst.php';

class  DBCWhMaterialName extends DBConst
{
    public $table = 'wh_material_name';
    public $filter = [];
    public $filtered = [];
    public $where = [];
    public $data = [];
//    public $pager_by = 50;
//    public $pager_by = 1;
    public $page = '';
    public $action = '';
    public function __construct() {
        $this->page = 'wh_material_name';
//        $this->debug=true;
        $this->orderdef = ['id'=>'desc'];
        $this->orderdef = ['id'=>'asc'];
        parent::__construct();
    }
//    public function filter(){
//        parent::filter();
//    }
//    public function controller(){
//        $this->page = 'warehouse';
//        $tpl = '';
//        if(strlen($this->action)>0)$tpl = $this->page.'-'.$this->action;
//        if(strlen($tpl) && file_exists('page/'.$tpl))$this->page = $tpl;
//    }
//    public function get(){
//        
//    }
//    public function set(){
//        
//    }
//    public function main(){
//        
//    }
//    public function item(){
//        
//    }
//    public function create(){
//        
//    }
//    public function edit(){
//        
//    }
//    public function delete(){
//        
//    }
//    public function update(){
//        
//    }
//    public function data(){
//        
//    }
//    public function show(){
//        ob_start();
//        include 'page/'.$this->page.'.php';
//        return ob_get_clean();
//    }
}

$DBCWhMaterialName = new DBCWhMaterialName();
