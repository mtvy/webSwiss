<?php

class MedLabAuth // extends MedLabInit
{

    public $clId = '';
    public $clSec = '';
//  global $ip,$port;
    public $ip = '213.230.71.167';
    public $port = '9901';

//  $port = '9902';
//  $port = '8888';
//  $port = '8887';
//  $port = '9900';
    public function __construct() {
        // juvico_rest DEVICE
        $clId = '';
        $clSec = '';

        // juvico_all WEB
        $clId = '';
        $clSec = '';
    }

    public function createSocketContextParams($data, $optionalHeaders) {
        $header = "Content-Type: application/x-www-form-urlencoded\r\n";
//    if (!Utils::isStrlenBroken()) {
        $dataLength = strlen($data);
        $header .= "Content-Length: {$dataLength}\r\n";
//    }

        $params = [
            'http' => [
                'header' => $header,
                'method' => 'POST',
                'content' => $data,
                'timeout' => 60,
            ],
        ];
        if ($optionalHeaders !== NULL) {
            $params['http']['header'] = $optionalHeaders;
        }

        return $params;
    }

    public function throwFailException($communicationStartTime) {
        $message = "Проблема при соединении с сервером данных, повторите попытку через несколько минут.";
        if (((time() - $communicationStartTime) >= 60)) {
            throw new TimeoutException($message, $this->timeout);
        } else {
            throw new \Exception($message);
        }
    }

    public function doPostRequest($data, $optionalHeaders = NULL) {
//  global $ip,$port;
        $data = 0 ? gzcompress($data, $this->compressLevel) : $data;
        $params = $this->createSocketContextParams($data, $optionalHeaders);
        add_log($params);
        $context = stream_context_create($params);
        $communicationStartTime = time();
        $fp = fopen('http://' . $this->ip . ':' . $this->port . '/', 'rb', FALSE, $context);
//    $fp = fopen('http://'.$ip.':'.$port.'/isys/', 'rb', FALSE, $context);
//    $fp = fopen('http://'.$ip.':'.$port.'/isys/', 'wb', FALSE, $context);
//    $fp = fopen('http://213.230.71.167:9901/', 'rb', FALSE, $context);
//    $fp = fopen('213.230.71.167:9901', 'rb', FALSE, $context);
        if (!$fp) {
            $this->throwFailException($communicationStartTime);
        }
        stream_set_blocking($fp, FALSE);

//    $response = $this->readResponse($fp);
        $response = '';
        while (!feof($fp)) {
            $response .= fgets($fp, 4096);
        }
        fclose($fp);

        if (empty($response)) {
            $this->throwFailException($communicationStartTime);
        } else {
            
        }
        $uncompressedData = 0 ? gzuncompress($response) : $response;
        if ($uncompressedData === FALSE) {
            throw new \Exception("Проблема при расшифровке данных: \"{$response}\". ");
        }

        return $uncompressedData;
    }

    public function _doPostRequest($data, $optionalHeaders = NULL) {
        
    }

    public function auth($clId, $clSec, &$res = false) {
        $url = "https://allegro.pl";
        $p = "/auth/oauth/token";
        $q = "grant_type=client_credentials";
        $h = [];
        //    $h[]='Accept-Language: gb-GB';
        $h[] = 'Accept-Language: ru-RU';
        $h[] = 'Authorization: Basic ' . base64_encode($clId . ':' . $clSec);
        $h[] = 'accept: application/vnd.allegro.public.v1+json';
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url . $p . '?' . $q); // set url to post to 
        curl_setopt($ch, CURLOPT_HTTPHEADER, $h);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable 
        $result = curl_exec($ch); // run the whole process 
        curl_close($ch);
        $res = json_decode($result);
        if (current_user_can('manage_options')) {// manage_options - права администратора
//        echo $this->pre($url.$p.'?'.$q);
//        echo $this->pre($res);
        }

        global $access_token;
        global $token_type;
        $access_token = $res->access_token;
        $token_type = $res->token_type;
    }

    public function authGet($p = '', $q = '', &$res = false, $command = '') {
        global $access_token;
        global $token_type;

        $url = "https://api.allegro.pl";
        //    $p = "/auth/oauth/token"; 
        //    $q = "grant_type=client_credentials"; 
        $h = [];
        $h[] = 'Accept-Language: ru-RU';
        //    $h[]='Accept-Language: gb-GB';
        $h[] = 'Authorization: Bearer ' . $access_token;
        $h[] = 'accept: application/vnd.allegro.public.v1+json';
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url . $p . '?' . $q); // set url to post to 
        curl_setopt($ch, CURLOPT_HTTPHEADER, $h);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable 
        if ($command !== '')
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $command);

        $result = curl_exec($ch); // run the whole process 
        curl_close($ch);
        $res = json_decode($result);
        if (current_user_can('manage_options')) {// manage_options - права администратора
//        echo $this->pre($url.$p.'?'.$q);
//        echo $this->pre($res);
        }
        //    global $access_token;
        //    global $token_type;
        //    $access_token = $res->access_token;
        //    $token_type = $res->token_type;
        return $res;
    }

    public function getpost($method, $f = false, $d = '', $type = FILTER_SANITIZE_STRING) {
        global $inputs;
        $inputs = [];

        if ($f === false)
            return $d;
//        $out=$d;
//        if(isset($_GET[$f]))$out=$_GET[$f];
//        return $out;

        if (!filter_has_var(INPUT_GET, $f))
            return $d;
        $opt = array('default' => $d);
        $inputs_[$f] = array('filter' => $type, 'options' => $opt);
//        echo $this->pre($inputs_);
        $inputs = filter_input_array(INPUT_GET, $inputs_);
        if ($inputs[$f] === null)
            $inputs[$f] = $d;
        if ($inputs[$f] === false)
            $inputs[$f] = $d;
        if (strlen($inputs[$f]) == 0)
            $inputs[$f] = $d;
        return $inputs[$f];
    }

    public function get($f = false, $d = '', $type = FILTER_SANITIZE_STRING) {
        return $this->getpost(INPUT_GET, $f, $d, $type);
    }

    public function post($f = false, $d = '', $type = FILTER_SANITIZE_STRING) {
        return $this->getpost(INPUT_POST, $f, $d, $type);
    }

    function lend_form_process() {
        global $def_args, $def_country;
        global $inputs;
        $inputs = [];
        //$def_country='MX';

        $opt = array('default' => NULL);
        $opt = array('default' => '');
        $opt_slag = array('default' => $def_country);
        $inputs_['form-type'] = array('filter' => FILTER_SANITIZE_STRING, 'options' => $opt);
        $inputs_['form-slag'] = array('filter' => FILTER_SANITIZE_STRING, 'options' => $opt_slag);
        $inputs_['f_name'] = array('filter' => FILTER_SANITIZE_STRING, 'options' => $opt);
        //    $inputs_['org_email'] = array('filter'=>FILTER_VALIDATE_EMAIL, 'options' => $opt);
        $inputs_['f_phone'] = array('filter' => FILTER_SANITIZE_STRING, 'options' => $opt);
        $inputs_['country_code'] = array('filter' => FILTER_SANITIZE_STRING, 'options' => $opt);

        $inputs = filter_input_array(INPUT_POST, $inputs_);
        if (strlen($inputs['form-type']) > 0) {
            switch ($inputs['form-type']) {
                case 'sample-page':
                case 'front':
                case 'blog1':
                case 'blog2':
                case 'mobile':
                case 'article1':
                case 'pay-request':
                    //                add_log('lend_form_process');
                    $res = code_send($inputs);
                    if ($res) {
                        wp_redirect(esc_url(home_url('/success/')));
                        exit();
                    }
                    break;
            }
        }
    }

}
