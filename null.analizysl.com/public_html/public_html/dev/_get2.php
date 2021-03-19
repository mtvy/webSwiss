<?php

$lis = [];

  $date = date('d.m.Y H:i:s');
  $pass = 'P5x8w934JZn62a7F';
  $pass = 'pass';
  $ip = '213.230.71.167';
  $port = '9901';
  $sender = '50027';
  $sender = '50006';
  $sender = '50000';
  
$lis['http_server'] = 'analizysl.com';
$lis['http_server'] = '213.230.71.167';
$lis['http_server'] = '213.230.71.167:9901';
$lis['sender'] = $sender;
$lis['receiver'] = 'SwissLab';
$lis['receiver'] = 'test';
$lis['password'] = $pass;

$soap = curl_init($lis['http_server']);
curl_setopt($soap, CURLOPT_POST, 1);
curl_setopt($soap, CURLOPT_RETURNTRANSFER, 1);


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

curl_setopt($soap, CURLOPT_POSTFIELDS, $request);
$response = curl_exec($soap);

//$xml = simplexml_load_string($response) or die("Error: Cannot create object: query-dictionaries-version");
  echo '<pre>'.  htmlspecialchars($request).'</pre>';
echo '<pre>>'.($response).'<</pre>';
    echo '<pre>>'.htmlspecialchars($response).'<</pre>';
echo '><pre>'.var_dump($response,1).'</pre>';
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
    
  echo '<pre>'.  htmlspecialchars($request).'</pre>';
    echo '<pre>>'.$response.'<</pre>';
    echo '<pre>>'.htmlspecialchars($response).'<</pre>';
    echo '><pre>'.var_dump($response,1).'</pre>';