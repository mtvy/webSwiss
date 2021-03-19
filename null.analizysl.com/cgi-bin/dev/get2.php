<?php
//if(1)return ;
$lis = [];

  $date = date('d.m.Y H:i:s');
  $pass = 'P5x8w934JZn62a7F';
//  $pass = 'pass';
  $ip = '213.230.71.167';
  $port = '9901';
  $sender = '50027';
//  $sender = '50006';
//  $sender = '50000';
  
$lis['http_server'] = 'analizysl.com';
$lis['http_server'] = '213.230.71.167';
$lis['http_server'] = '213.230.71.167:9901';
//$lis['http_server'] = '95.211.223.217:9901';
//$lis['http_server'] = '95.211.223.217:80';
//$lis['http_server'] = 'analizysl.com:80';
$lis['sender'] = $sender;
$lis['receiver'] = 'SwissLab';
//$lis['receiver'] = 'test';
$lis['password'] = $pass;

$soap = curl_init($lis['http_server']);
curl_setopt($soap, CURLOPT_POST, 1);
curl_setopt($soap, CURLOPT_RETURNTRANSFER, 1);


header("Content-Type: text/html; charset=Windows-1251");
//<meta http-equiv="content-type" content="text/html; charset=Windows-1251" />

//get price version
$curdate = date('d.m.Y H:i:s');
$request = <<<XML
<?xml version="1.0" encoding="Windows-1251"?>
<Message
  MessageType="query-dictionaries-version"
  Date="{$date}"
  Sender="{$lis['sender']}"
  Receiver="{$lis['receiver']}"
  Password="{$lis['password']}">
</Message>
XML;
  
curl_setopt($soap, CURLOPT_HTTPHEADER, array('Content-Type: text/xml; charset=utf-8',
    'Content-Length: ' . strlen($request)));

curl_setopt($soap, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($soap, CURLINFO_HEADER_OUT, true);
curl_setopt($soap, CURLOPT_POSTFIELDS, $request);
$response = curl_exec($soap);

//$xml = simplexml_load_string($response) or die("Error: Cannot create object: query-dictionaries-version");
  echo '<br/><pre>'.  htmlspecialchars($request).'</pre>';
    echo '<hr/>';
                    echo('$answer<pre>'.htmlspecialchars(print_r($response,1)).'</pre>');
    echo '<hr/>';
echo '<br/><pre>$response>'.($response).'<</pre>';
    echo '<hr/>';
    echo '<br/><pre>htmlspecialchars>'.htmlspecialchars($response).'<</pre>';
    echo '<hr/>';
    $r=['<'=>"&lt;",'>'=>"&gt;\n",'" '=>"\" \n"];
echo '<br/>>strtr<pre>'.strtr($response,$r).'</pre>';
    echo '<hr/>';
echo '<br/>>var_dump<pre>',var_dump($response,1),'</pre>';
    echo '<hr/>';
//if(0)
//if (0 && !isset($lis['dictionaries_version'])
//        or $lis['dictionaries_version'] != $xml->Version['Version']) {
    $request = <<<XML
<?xml version="1.0" encoding="Windows-1251"? >
<Message
  MessageType="query-dictionaries"
  Date="{$curdate}"
  Sender="{$lis['sender']}"
  Receiver="{$lis['receiver']}"
  Password="{$lis['password']}">
</Message>
XML;
    curl_setopt($soap, CURLOPT_HTTPHEADER, array('Content-Type: text/xml; charset=Windows-1251',
        'Content-Length: ' . strlen($request)));

    curl_setopt($soap, CURLOPT_POSTFIELDS, $request);
    $response = curl_exec($soap);
//}
    
  echo '<br/><pre>'.  htmlspecialchars($request).'</pre>';
    echo '<hr/>';
    echo '<br/><pre>>'.$response.'<</pre>';
    echo '<hr/>';
    echo '<br/><pre>>'.htmlspecialchars($response).'<</pre>';
    echo '<hr/>';
    echo '<br/>><pre>'.var_dump($response,1).'</pre>';
    echo '<hr/>';
    echo '<br/>>CURLINFO_HEADER_OUT<pre>',var_dump(curl_getinfo($soap,CURLINFO_HEADER_OUT),1),'</pre>';
    echo '<hr/>';
    echo '<br/>>CURLINFO_EFFECTIVE_URL<pre>',var_dump(curl_getinfo($soap,CURLINFO_EFFECTIVE_URL),1),'</pre>';
    echo '<hr/>';
    echo '<br/>>CURLINFO_HTTP_CODE<pre>',var_dump(curl_getinfo($soap,CURLINFO_HTTP_CODE),1),'</pre>';
    echo '<hr/>';
    echo '<br/>>CURLINFO_RESPONSE_CODE<pre>',var_dump(curl_getinfo($soap,CURLINFO_RESPONSE_CODE),1),'</pre>';
    echo '<hr/>';
    
//var_dump(curl_getinfo($ch,CURLINFO_HEADER_OUT));