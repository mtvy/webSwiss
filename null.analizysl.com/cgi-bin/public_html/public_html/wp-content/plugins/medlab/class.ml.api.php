<?php

/* 
 * class.ml.api.php
 */

class mlApi{
    private $user = '';
    private $pass = '';
    private $host = '';
    private $port = '';
    public function __construct() {
        ;
    }
    public function init(){
        
    }
    public function connect(){
        $url = 'http://'.$this->host.''.$this->port.'/isys/';
            //. implode('&', $query) . '&Signature=' . $signed
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $data = curl_exec($ch);
        $info = curl_getinfo($ch);
        if ($info['http_code'] != '200') return false;

        return $data;
    }
    public function get(){
        
    }
    public function put(){
        
    }
}