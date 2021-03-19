<?php
/*
 * V:\serv\home\_sandbox\bz\bz12\medlab_15\dev\get.php
 * V:\serv\home\_sandbox\bz\bz12\medlab_15\dev\get.php
 */
    return;
    ini_set("display_errors", '1');
    ini_set('display_startup_errors', '1');
    ini_set('error_reporting', E_ALL);
class Utils {

  const RUSSIAN_MONTHS = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];

  /**
   * Processes a URL query parameter array to remove unwanted elements.
   *
   * @param $query
   *   (optional) An array to be processed. Defaults to $_GET.
   * @param $exclude
   *   (optional) A list of $query array keys to remove. Use "parent[child]" to
   *   exclude nested items. Defaults to array('q').
   * @param $parent
   *   Internal use only. Used to build the $query array key for nested items.
   *
   * @return array
   *   An array containing query parameters, which can be used for url().
   */
  public static function getRequestQueryParameters(array $query = NULL, array $exclude = ['q'], $parent = '') {
    // Set defaults, if none given.
    if (!isset($query)) {
      $query = $_REQUEST;
    }
    // If $exclude is empty, there is nothing to filter.
    if (empty($exclude)) {
      return $query;
    }
    elseif (!$parent) {
      $exclude = array_flip($exclude);
    }

    $params = [];
    foreach ($query as $key => $value) {
      $stringKey = ($parent ? $parent . '[' . $key . ']' : $key);
      if (isset($exclude[$stringKey])) {
        continue;
      }

      if (is_array($value)) {
        $params[$key] = self::getRequestQueryParameters($value, $exclude, $stringKey);
      }
      else {
        $params[$key] = $value;
      }
    }

    return $params;
  }

  /**
   * Splits a URL-encoded query string into an array.
   *
   * @param $query
   *   The query string to split.
   *
   * @return
   *   An array of URL decoded couples $param_name => $value.
   */
  public static function getQueryArray($query) {
    $result = [];

    if (!empty($query)) {
      foreach (explode('&', $query) as $param) {
        $param = explode('=', $param, 2);
        $result[$param[0]] = isset($param[1]) ? rawurldecode($param[1]) : '';
      }
    }

    return $result;
  }

  /**
   * @param array $link
   * @param string $url
   */
  public static function setLinkHref(array &$link, $url) {
    if (strpos($url, '?') !== FALSE) {
      list($url, $query) = explode('?', $url);
      if ($query) {
        $link['#options']['query'] = self::getQueryArray($query);
      }
    }

    $link['#href'] = $url;
  }


  /**
   * Проверяет, являются ли все ячейки массива нумериками.
   *
   * @param array $array
   *
   * @return boolean
   */
  public static function isNumericArray(array $array) {
    foreach ($array as $value) {
      if (!is_numeric($value)) {
        return FALSE;
      }
    }

    return TRUE;
  }

  /**
   * @param string[] $phaseForms
   * @param int $quantity
   *
   * @return string
   */
  public static function decline(array $phaseForms, $quantity) {
    return $phaseForms[self::declension($quantity)];
  }

  /**
   * Возвращает индекс склонения числа.
   *
   * @param int $value
   *
   * @return int
   */
  public static function declension($value) {
    $a = $value % 100;

    if (($a > 10) && ($a < 20)) {
      $n = 2;
    } else {
      $b = $value % 10;
      switch ($b) {
        case 1:
          $n = 0;
          break;
        case 2:
        case 3:
        case 4:
          $n = 1;
          break;
        default:
          $n = 2;
      }
    }

    return $n;
  }

  /**
   * @param string $string
   * @param array $replacements
   *
   * @return string
   */
  public static function stringAssociativeReplacement($string, array $replacements) {
    $replace = [];
    $search = [];
    foreach ($replacements as $key => $value) {
      $search[] = $key;
      $replace[] = $value;
    }

    return str_replace($search, $replace, $string);
  }

  /**
   * @param string $string
   *
   * @return string[]
   */
  public static function splitNumericAndAlpha($string) {
    return preg_split('/(\\d+)/', $string, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
  }

  /**
   * @param array $arr
   * @param array $oldNewKeys
   */
  public static function changeKeys(array &$arr, array $oldNewKeys) {
    foreach ($oldNewKeys as $oldKey => $newKey) {
      self::changeKey($arr, $oldKey, $newKey);
    }
  }

  /**
   * @param array $array
   * @param mixed $value
   */
  public static function arrayDeleteValue(array &$array, $value) {
    if (($key = array_search($value, $array)) !== FALSE) {
      unset($array[$key]);
    }
  }

  /**
   * Вставляет элемент в массив перед заданным ключом.
   *
   * @param array $originalArray
   * @param mixed $originalKey
   * @param mixed $insertKey
   * @param mixed $insertValue
   *
   * @return array
   */
  public static function insertBeforeKey(array $originalArray, $originalKey, $insertKey, $insertValue) {
    $newArray = [];

    $inserted = FALSE;
    foreach ($originalArray as $key => $value) {
      if (!$inserted && $key === $originalKey) {
        $newArray[$insertKey] = $insertValue;
        $inserted = TRUE;
      }

      $newArray[$key] = $value;
    }

    return $newArray;
  }

  /**
   * @param float $n
   *
   * @return int
   */
  public static function sign($n) {
    return ($n > 0) - ($n < 0);
  }

  /**
   * @param array $arr
   * @param string $oldKey
   * @param string $newKey
   */
  private static function changeKey(array &$arr, $oldKey, $newKey) {
    if (isset($arr[$oldKey])) {
      $arr[$newKey] = $arr[$oldKey];
      unset($arr[$oldKey]);
    }
  }

  /**
   * @param array $array
   * @param string $index
   * @param mixed $defaultValue
   *
   * @return mixed
   */
  public static function getValue(array $array, $index, $defaultValue = '') {
    return isset($array[$index]) ? $array[$index] : $defaultValue;
  }

  /**
   * Заменяет в строке внутреннее представление LIS перевода строки на заданное.
   *
   * @param string $str
   * @param string $nl
   *
   * @return string
   */
  public static function fixLisCharacters($str, $nl = '<br/>') {
    return str_replace('#$D#$A', $nl, $str);
  }

  /**
   * @param array $attributes
   *
   * @return string
   */
  public static function htmlAttributes(array $attributes = []) {
    foreach ($attributes as $attribute => &$data) {
      $data = implode(' ', (array)$data);
      $data = $attribute . '="' . htmlspecialchars($data, ENT_QUOTES, 'UTF-8') . '"';
    }

    return $attributes ? ' ' . implode(' ', $attributes) : '';
  }

  /**
   * @return boolean
   */
  public static function isStrlenBroken() {
    return ((int) ini_get('mbstring.func_overload') & 2);
  }

  /**
   * @param int $date
   * @param string $locale
   *
   * @return string
   */
  public static function getMonthName($date, $locale = 'ru_RU') {
    if ($locale !== 'ru_RU') {
      $prevLocale = setlocale(LC_TIME, 0);
      setlocale(LC_TIME, $locale);
      $monthName = strftime('%B', $date);
      setlocale(LC_TIME, $prevLocale);
    }
    else {
      $monthName = self::RUSSIAN_MONTHS[date('m', $date) - 1];
    }

    return $monthName;
  }

  /**
   * @param ICodedEntity[] $entities
   *
   * @return array
   */
  public static function mapEntitiesByCode(array $entities) {
    $map = [];

    foreach ($entities as $entity) {
      $map[$entity->getCode()] = $entity;
    }

    return $map;
  }

  /**
   * @param IIdentifiedEntity[] $entities
   *
   * @return array
   */
  public static function mapEntitiesById(array $entities) {
    $map = [];

    foreach ($entities as $entity) {
      $map[$entity->getId()] = $entity;
    }

    return $map;
  }
  /**
   * @param array[] $arrays
   * @param string $fieldName
   *
   * @return array
   */
  public static function mapArraysByField(array $arrays, $fieldName) {
    $map = [];

    foreach ($arrays as $array) {
      $map[$array[$fieldName]] = $array;
    }

    return $map;
  }
}
class TimeoutException extends \Exception {

  /**
   * @var int
   */
  private $timeout;

  public function __construct($message, $timeout) {
    $this->timeout = $timeout;
    parent::__construct($message . " Превышено время ожидания ответа ($this->timeout)");
  }

  /**
   * @return int
   */
  public function getTimeout() {
    return $this->timeout;
  }

}

//-----------------------------
if(0){
    if( xxxxxxxxxxxxxxxx ===  xxxxxxxxxxxxxxxxxx){}
}


function createSocketContextParams($data, $optionalHeaders) {
    $header = "Content-Type: application/x-www-form-urlencoded\r\n";
    if (!Utils::isStrlenBroken()) {
      $dataLength = strlen($data);
      $header .= "Content-Length: {$dataLength}\r\n";
    }

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
  function throwFailException($communicationStartTime) {
    $message = "Проблема при соединении с сервером данных, повторите попытку через несколько минут.";
    if (((time() - $communicationStartTime) >=60)) {
      throw new TimeoutException($message, $this->timeout);
    } else {
      throw new \Exception($message);
    }
  }
  global $ip,$port;
  $ip = '213.230.71.167';
  $port = '9901';
//  $port = '9902';
//  $port = '8888';
//  $port = '8887';
//  $port = '9900';
function doPostRequest($data, $optionalHeaders = NULL) {
  global $ip,$port;
    $data = 0 ? gzcompress($data, $this->compressLevel) : $data;
    $params = createSocketContextParams($data, $optionalHeaders);
    $context = stream_context_create($params);
    $communicationStartTime = time();
    $fp = fopen('http://'.$ip.':'.$port.'/', 'rb', FALSE, $context);
//    $fp = fopen('http://'.$ip.':'.$port.'/isys/', 'rb', FALSE, $context);
//    $fp = fopen('http://'.$ip.':'.$port.'/isys/', 'wb', FALSE, $context);
//    $fp = fopen('http://213.230.71.167:9901/', 'rb', FALSE, $context);
//    $fp = fopen('213.230.71.167:9901', 'rb', FALSE, $context);
    if (!$fp) {
      throwFailException($communicationStartTime);
    }
    stream_set_blocking($fp, FALSE);

//    $response = $this->readResponse($fp);
    $response = '';
    while (!feof($fp)) {
      $response .= fgets($fp, 4096  );
    }
    fclose($fp);

    if (empty($response)) {
      throwFailException($communicationStartTime);
    }
    $uncompressedData = 0 ? gzuncompress($response) : $response;
    if ($uncompressedData === FALSE) {
      throw new \Exception("Проблема при расшифровке данных: \"{$response}\". ");
    }

    return $uncompressedData;
  }
  
//Date="09.11.2013 21:24:51"
  $date = date('d.m.Y H:i:s');
  $datas=[];
  $data_names=[];
  $data = '<?xml version="1.0" encoding="_encoding_"?>
<Envelope SessionId="0" Date="'.$date.'">
  <MethodCall Name="web-labs">
    <Params ClientIp="88.204.196.134"/>
  </MethodCall>
</Envelope>';
//  $datas[]=$data;
//  $data_names[]='web-labs';
  
  $data = '<?xml version="1.0" encoding="_encoding_"?>
<Envelope SessionId="0" Date="'.$date.'">
  <MethodCall Name="web-request-info">
    <Params RequestNr="XXXXXXXX" Password="_pass_" ClientIp="11.11.11.11"/>
  </MethodCall>
</Envelope>';
//  $datas[]=$data;
//  $data_names[]='web-request-info';
  
  $data = '<?xml version="1.0" encoding="_encoding_"?>
<Envelope SessionId="0" Date="'.$date.'"
Sender="50006"
    >
  <MethodCall Name="web-get-partners-dictionaries-info">
    <Params LabCode="" ClientIp="88.204.196.134"/>
  </MethodCall>
</Envelope>';
//  $datas[]=$data;
//$data_names[] = 'web-get-partners-dictionaries-info';

  
//Receiver="20229">
  $data = '<?xml version="1.0" encoding="_encoding_"?>
<Message
MessageType="dictionaries-version"
Date="'.$date.'"
Sender="_sender_"
    Password="_pass_"
Receiver="SwissLab">
<Version Version="1"/>
</Message>';
  $datas[]=$data;
  $data_names[] = 'dictionaries-version';
  
  $data = '<?xml version="1.0" encoding="_encoding_"?>
<Message
MessageType="query-dictionaries-version"
Date="'.$date.'"
Sender="_sender_"
    Password="_pass_"
Receiver="SwissLab">
</Message>';
  $datas[]=$data;
  $data_names[] = 'query-dictionaries-version';
  
  $data = '<?xml version="1.0" encoding="_encoding_"?>
<Message
MessageType="query-dictionaries"
Date="'.$date.'"
Sender="_sender_"
    Password="_pass_"
Receiver="SwissLab">
</Message>';
  $datas[]=$data;
  $data_names[] = 'query-dictionaries';
  
//							Date="25.04.2019 16:10:09"
  $data = '<?xml version="1.0" encoding="_encoding_"?>
<Message
    MessageType="query-create-referral"
    Date="_date_"
    Sender="_sender_"
    Receiver="SwissLab"
    Password="_pass_">
    <Patient MisId="15561815632" Code1="2" LastName="s"
    FirstName="a" MiddleName="a" Gender="1" BirthDate=""/>
    <Referral
        MisId="15561815632"
        Nr="9930001410"
        Date="25.04.2019 00:00:00"
        SamplingDate=""
        DeliveryDate=""
        DepartmentName="admin"
        DepartmentCode="1"
        DoctorName="bekzod Bekzod"
        DoctorCode="admin 1"
        Cito=""
        DiagnosisName=""
        DiagnosisCode=""
        Comment=""
        PregnancyWeek=""
        CyclePeriod=""
        LastMenstruation=""
        DiuresisMl=""
        WeightKg="0"
        HeightCm=""
    />
    <Assays>
        <Item Barcode="9930001410" BiomaterialCode="50011"><Orders>
            <Item Code="20-000"/>
            <Item Code="20-001"/>
            <Item Code="20-002"/>
            <Item Code="20-003"/>
            </Orders>
        </Item>
        <Item Barcode="9930001410" BiomaterialCode="50002"><Orders>
            <Item Code="20-000"/>
            <Item Code="20-001"/>
            <Item Code="20-002"/>
            <Item Code="20-003"/>
            <Item Code="20-004"/>
        </Orders>
        </Item>
    </Assays>
</Message>';
  
  $query = '';
  $orders = '';
  $droot = '<?xml version="1.0" encoding="_encoding_"?>
<Message
    MessageType="__messType__"
    Date="_date_"
    Sender="_sender_"
    Receiver="_reciver_"
    Password="_pass_">
    __request__
</Message>';
//        MisId="15561815632"
  $patient = '
    <Patient
        MisId="15500000001"
        Code1="2"
        LastName="testLastN"
        FirstName="testFirstN"
        MiddleName="testMname"
        Gender="1"
        BirthDate=""
    />';
//        MisId="15561815632"
//        Nr="9930001410"
  $referral = '
    <Referral
        MisId="15500000001"
        Nr="15500000001"
        Date="25.04.2019 00:00:00"
        SamplingDate=""
        DeliveryDate=""
        DepartmentName="admin"
        DepartmentCode="1"
        DoctorName="bekzod Bekzod"
        DoctorCode="admin 1"
        Cito=""
        DiagnosisName="test"
        DiagnosisCode="test-dc"
        Comment="Тестирование функционала. Тестовая заявка."
        PregnancyWeek=""
        CyclePeriod=""
        LastMenstruation=""
        DiuresisMl=""
        WeightKg="0"
        HeightCm=""
    >
    __orders__
    </Referral>';
  $orders = '';
  $orders = '
      <Orders>
        <Item Code="20-000" BiomaterialCode="50011"/>
        <Item Code="20-001" BiomaterialCode="50011"/>
        <Item Code="20-002" BiomaterialCode="50011"/>
        <Item Code="20-003" BiomaterialCode="50011"/>
        <Item Code="20-000" BiomaterialCode="50002"/>
        <Item Code="20-001" BiomaterialCode="50002"/>
        <Item Code="20-002" BiomaterialCode="50002"/>
        <Item Code="20-003" BiomaterialCode="50002"/>
        <Item Code="20-004" BiomaterialCode="50002"/>
      </Orders>
      ';
  
  $r=[];
  $r['__orders__'] = $orders;
  $referral = strtr($referral,$r);
  
  $assays = '
    <Assays>
        <Item Barcode="9930001410" BiomaterialCode="50011"><Orders>
            <Item Code="20-000"/>
            <Item Code="20-001"/>
            <Item Code="20-002"/>
            <Item Code="20-003"/>
            </Orders>
        </Item>
        <Item Barcode="9930001410" BiomaterialCode="50002"><Orders>
            <Item Code="20-000"/>
            <Item Code="20-001"/>
            <Item Code="20-002"/>
            <Item Code="20-003"/>
            <Item Code="20-004"/>
        </Orders>
        </Item>
    </Assays>';
  $assays = '';
  
  $data = '
      __query__
      __patient__
      __referral__
      __assays__
      ';
  
$MisId="15561815632";
$Nr="9930001410";
$MisId = "15500000001";
$Nr    = "15500000001";
  
  $r=[];
  $r['__query__'] = $query;
  $r['__patient__'] = $patient;
  $r['__referral__'] = $referral;
  $r['__assays__'] = $assays;
  $data = strtr($data,$r);
  $r=[];
  $r['__query_id__'] = $MisId;
  $r['__query_mid__'] = $Nr;
  $data = strtr($data,$r);
  
  $r=[];
//  $r['_reciver_'] = 'test';
  $r['__messType__'] = 'query-create-referral';
  $r['_reciver_'] = 'SwissLab';
  $r['__request__'] = $data;
  
  $data = strtr($droot,$r);
    $n =  "11.1 Запрос на создание направления";
  $datas[]=$data;
  $data_names[] = 'query-create-referral'.' '.$n;
  
  $n =  "";
//        LisId="613807"
//        LisId="613807"
//        Nr="9930001410"
//        MisId="15561815632"
  $data = '
  <?xml version="1.0" encoding="Windows-1251"?>
<Message
    MessageType="result-referral-results-import"
    Date="_date_"
    Sender="_sender_"
    Receiver="SwissLab"
    Password="_pass_">
    <Version Version="4"/>
    <Referral
        LisId="613807"
        Nr=""
        MisId=""
    />
</Message>';
  $datas[]=$data;
  $data_names[] = 'result-referral-results-import'.' '.$n;
  
$n =  "11.7 Запрос на получение результатов по направлению";
//        Nr="9930001410"
  $data = '
  <?xml version="1.0" encoding="Windows-1251"?>
<Message
    MessageType="query-referral-results"
    Date="_date_"
    Sender="_sender_"
    Receiver="SwissLab"
    Password="_pass_">
    <Query
        LisId=""
        Nr="15500000001"
        MisId=""
    />
</Message>';
  $datas[]=$data;
  $data_names[] = 'query-referral-results'.' '.$n;
  
 $n =  "11.8 Запрос на получение результатов по следующему направлению из очереди";
  $data = '
  <?xml version="1.0" encoding="Windows-1251"?>
<Message
    MessageType="query-next-referral-results"
    Date="_date_"
    Sender="_sender_"
    Receiver="SwissLab"
    Password="_pass_">
    <Query
        OnlyCreatedFromLis=""
        AllowModified=""
        ModValue=""
        ModCount=""
    />
</Message>';
  $datas[]=$data;
  $data_names[] = 'query-next-referral-results'.' '.$n;
  
 $n =  "11.9 Запрос на подсчет количества заявок с результата-ми, ожидающих в очереди";
  $data = '
  <?xml version="1.0" encoding="Windows-1251"?>
<Message
    MessageType="query-count-referral-results"
    Date="_date_"
    Sender="_sender_"
    Receiver="SwissLab"
    Password="_pass_">
    <Query
        OnlyCreatedFromLis=""
        AllowModified=""
    />
</Message>';
  $datas[]=$data;
  $data_names[] = 'query-count-referral-results'.' '.$n;
  
 $n =  "11.11 Запрос на получение информации по следую-щей заявке с результатами, ожидающей в очереди";
 $d_interv = date("d.m.Y 00:00:00"); 
 $data = '
  <?xml version="1.0" encoding="Windows-1251"?>
<Message
    MessageType="query-new-referral-results"
    Date="_date_"
    Sender="_sender_"
    Receiver="SwissLab"
    Password="_pass_">
    <Query
        OnlyCreatedFromLis=""
        AllowModified=""
        DateFrom="'.$d_interv.'
        DateTill="'.$d_interv.'"
    />
</Message>';
  $datas[]=$data;
  $data_names[] = 'query-new-referral-results'.' '.$n;
  
 $n =  "11.13 Поиск заявок данного пациента";
//    <Patient MisId="15561815632" Code1="2" LastName="s"
//    FirstName="a" MiddleName="a" Gender="1" BirthDate=""/>
//    <Referral
//        MisId="15561815632"
//        Nr="9930001410"
//        PatientCode1=""
//        PatientCode2=""
//        UseUpdateDate="false"
//        PatientMisId="15561815632"
  $data = '
  <?xml version="1.0" encoding="Windows-1251"?>
<Message
    MessageType="query-patient-referral-results"
    Date="_date_"
    Sender="_sender_"
    Receiver="SwissLab"
    Password="_pass_">
    <Query
        PatientMisId="15500000001"
        DateFrom="03.02.2010 00:00:00"
        DateTill="04.02.2020 00:00:00"
    />
</Message>';
  $datas[]=$data;
  $data_names[] = 'query-patient-referral-results'.' '.$n;
  
 $n =  "11.16 Подтверждение импорта информации по направлению";
  $data = '
  <?xml version="1.0" encoding="Windows-1251"?>
<Message
    MessageType="result-referral-results-import"
    Date="_date_"
    Sender="_sender_"
    Receiver="SwissLab"
    Password="_pass_">
    <Version Version="1"/>
    <Referral
        LisId="9072086"
        Nr=""
        MisId=""
    />
</Message>';
  $datas[]=$data;
  $data_names[] = 'result-referral-results-import'.' '.$n;
  
 $n =  "1.17 Запрос на получение бланка результатов в би-нарном виде";
//        BlankId="15526916"
//        BlankGUID="{0903AE0C-2666-4704-B502-272FC9593E84}"
  $data = '
  <?xml version="1.0" encoding="Windows-1251"?>
<Message
    MessageType="query-blank-file"
    Date="_date_"
    Sender="_sender_"
    Receiver="SwissLab"
    Password="_pass_">
    <Query
        BlankId="727116"
        BlankGUID="{213E983A-18D2-4A61-A331-A4DA40D6737E}"
    />
</Message>';
  $datas['blank-file']=$data;
  $data_names['blank-file'] = 'query-blank-file'.' '.$n;
  
 $n =  "";
  $data = '
  <?xml version="1.0" encoding="Windows-1251"?>
<Message
    MessageType="new-referral-results"
    Date="_date_"
    Sender="_sender_"
    Receiver="SwissLab"
    Password="_pass_">
    <Query
        OnlyCreatedFromLis=""
        AllowModified=""
    />
</Message>';
  $datas[]=$data;
  $data_names[] = 'qqqqqqqqqqqqqqq'.' '.$n;
  
    ini_set("display_errors", "1");
    ini_set("display_startup_errors", "1");
    ini_set('error_reporting', E_ALL);
    
//  $sel0 = $_GET['sender'] ?? '';
//  $sel1 = $_GET['port'] ?? '';
  $sel0 =  '50006';
  $sel1 =  '9901';
  $sel2 =  '0';
  $sel3 =  'short';
  
  $encoding = 'Windows-1251';
  $encoding = 'Utf-8';
  
  if(isset($_GET['sender']))  $sel0 = $_GET['sender'];
  if(isset($_GET['port']))  $sel1 = $_GET['port'];
  if(isset($_GET['query']))  $sel2 = $_GET['query'];
  if(isset($_GET['view']))  $sel3 = $_GET['view'];
  
  $sel0_0=$sel0=='50006'?'selected':'';
  $sel0_1=$sel0=='50027'?'selected':'';
  
  $sel1_0=$sel1=='9901'?'selected':'';
  $sel1_1=$sel1=='9902'?'selected':'';
  $sel1_2=$sel1=='8888'?'selected':'';
  $sel1_3=$sel1=='8887'?'selected':'';
  $sel1_4=$sel1=='9900'?'selected':'';
  
  $sel2_opts = [];
  foreach($datas as $k=>$v){
        $sel2_s=$sel2==$k?'selected':'';
        $sel2_opts[] = '<option value="'.$k.'" '.$sel2_s.'>'.$data_names[$k].'</option>';
  }
  $sel2_opts = implode("\n",$sel2_opts);
  
  $sel3_opts = [];
  $views=[];
  $views['short']='short';
  $views['dec']='dec';
  $views['build']='build';
  foreach($views as $k=>$v){
        $sel3_s=$sel3==$k?'selected':'';
        $sel3_opts[] = '<option value="'.$k.'" '.$sel3_s.'>'.$v.'</option>';
  }
  $sel3_opts = implode("\n",$sel3_opts);
$form =<<<xxx
<form method='get'>
    <table>
        <tr><td>
        <label for="sender"> Sender </label>
        </td><td>
        <select id="sender" name="sender">
            <option value="50006" $sel0_0>50006</option>
            <option value="50027" $sel0_1>50027</option>
            <!--<option value=""></option>
            <option value=""></option>-->
        </select>
        </tr>
        <tr><td>
        <label for="port"> Port</label>
        </td><td>
        <select id="port" name="port">
            <option value="9901" $sel1_0>9901</option>
            <option value="9902" $sel1_1>9902</option>
            <option value="8888" $sel1_2>8888</option>
            <option value="8887" $sel1_3>8887</option>
            <option value="9900" $sel1_4>9900</option>
        </select>
        </tr>
        <tr><td>
        <label for="query"> Query</label>
        </td><td>
        <select id="query" name="query">
            $sel2_opts
        </select>
        </tr>
        <tr><td>
        <label for="query"> View</label>
        </td><td>
        <select id="view" name="view">
            $sel3_opts
        </select>
        </tr>
        <tr><td>
        <label> </label>
        </td><td>
        </tr>
    </table>
<button type="submit">отправить</button>
</form>
xxx;
$form1 =<<<xxx
<form method='get'>
    <table>
        <tr><td>
        <label for="sender1"> Sender </label>
        </td><td>
        <input type="text" id="sender1" name="sender" value="$sel0"/>
        </tr>
        <tr><td>
        <label for="port1"> Port</label>
        </td><td>
        <input type="text" id="port1" name="port" value="$sel1"/>
        </tr>
        <tr><td>
        <label for="port1"> Query</label>
        </td><td>
        <input type="text" id="query" name="query" value="$sel2"/>
        </tr>
        <tr><td>
        <label> </label>
        </td><td>
        </tr>
    </table>
<button type="submit">отправить</button>
</form>
xxx;
  
  $date = date('d.m.Y H:i:s');
  $pass = 'P5x8w934JZn62a7F';
  $sender = '50006';
  $sender = '50027';
  
  global $ip,$port;
  $ip = '213.230.71.167';
  $port = '9901';
  $port = '9902';
  $port = '8888';
  $port = '8887';
  $port = '9900';
  
  $query=3;
  
  $view = 'short';
  
  $encoding = 'Windows-1251';
  $encoding = 'UTF-8';
  
  if(isset($_GET['port']))  $port = $_GET['port'];
  if(isset($_GET['sender']))  $sender = $_GET['sender'];
  if(isset($_GET['query']))  $query = $_GET['query'];
  if(isset($_GET['view']))  $view = $_GET['view'];
  
if($query != 'blank-file'){
    echo $form;
    echo $form1;
}
      
  if($query >= count($datas))$query=0;
  $data = $datas[$query];
  $r = [];
  $r['_date_']= $date;
  $r['11.11.11.11']= $_SERVER['SERVER_ADDR'];
  $r['88.204.196.134']= $_SERVER['SERVER_ADDR'];
  $r['_pass_']=$pass;
  $r['_sender_']=$sender;
  $r['_encoding_']=$encoding;
  $data_ = strtr($data ,$r);
  
  
        if($query != 'blank-file'){
  echo 'на запрос ';
  echo '<pre>'.  htmlspecialchars($data).'</pre>';
            
        }
    
    
    function http_build_attr(&$attr=[], $prefix='') 
    {
//        array_walk($attr,[$this,'_http_build_attr'],$prefix);
        array_walk($attr,'_http_build_attr',$prefix);
        return implode(' ',$attr);
    }
    function _http_build_attr(&$item1, $key, $prefix) 
    {
        if($prefix)$prefix.='-';
        $item1 = "$prefix$key=\"$item1\"";
    }
    function pre($res='',$class=''){
        ob_start();
        echo '<pre>';
        echo htmlspecialchars( print_r($res,1));
        echo '</pre>';
        $cat3 = ob_get_clean();
        return $cat3;
    }
function div($v,$attr=[]){
    return "<div ".http_build_attr($attr).">".$v."</div>";
}
$_mtime = microtime(1);
$answer = doPostRequest($data_);

$mtime = microtime(1);

$_ts = $mtime - $_mtime;
echo div ('<hr/>');
echo div ('milisec');
echo div ($_ts);
$_ts = round($_ts,4);
echo div ($_ts);
echo div ('<hr/>');



function buildAttrs($xml=false,$contName=false,$idName=false){
    $out = [];
    if($xml===false || !$contName || !$idName)return $out;
    foreach ($xml->$contName->Item as $key => $value) {
    //    $g = $value->attributes(1);
    //    echo div('$g'.pre($g));
        $res = [];
        foreach ($value->attributes() as $key2 => $value2) {
            $res[$key2] = ''.$value2;
        }
        $out[$res[$idName]]=$res;
        $out[$res[$idName]]['item']=$value;
    }
    return $out;
}

switch($view){
    case 'short':
$xml = simplexml_load_string($answer);

foreach($xml->attributes() as $a => $b) {
    echo div($a.'="'.$b."\"\n");
}
//    echo div($key.pre($xml->attributes()));
foreach ($xml as $key => $value) {
    $res = [];
    foreach ($value as $key2 => $value2) {
        $res[] = ($key2);
        $res[] = ($value2);
        break;
    }
    echo div($key.pre($res));
}
//  echo '<pre>';
//  echo  htmlspecialchars(print_r($xml,1)).'</pre>';

//$price = buildAttrs($xml,'Prices','ServiceId');
  
        break;
    case 'dec':
        
        if($query == 'blank-file'){
//  echo 'был получен Файл ';
  
header("Content-type:application/pdf");

// It will be called downloaded.pdf
header("Content-Disposition:attachment;filename=downloaded.pdf");

// The PDF source is in original.pdf
//readfile("original.pdf");
echo $answer;
            
        }else{

  echo 'был получен ответ ';
  echo '<pre>';
//    $data_ = iconv('windows-1251', 'UTF-8', $data_);
//    $answer = iconv( 'UTF-8', 'windows-1251', $answer);
//  echo print_r($_SERVER);
//  $answer = doPostRequest($data_);
  
//        $doc = new DOMDocument();
////libxml_use_internal_errors(true);
//        @$doc->loadHTML ( $html );
        
            
$answer = iconv('windows-1251', 'UTF-8', $answer);
  
  $r = [];
  $r['" ']= "\"\n ";
//  $answer = strtr($answer ,$r);
  echo  htmlspecialchars($answer).'</pre>';
//  echo '<pre>';
//  echo var_dump($answer,1).'</pre>';
  
//    $answer = doPostRequest($data_);
//    $answer = iconv('windows-1251', 'UTF-8', $answer);
  
//    $doc = new DOMDocument();
////libxml_use_internal_errors(true);
//    @$doc->loadHTML ( $answer );
////libxml_clear_errors(); 
        }
        break;
    case 'build':
        
        
//ini_set("display_errors", "1");
//ini_set("display_startup_errors", "1");
//ini_set('error_reporting', E_ALL);
$xml = simplexml_load_string($answer);
        
$groups=[];
$price=[];
foreach ($xml->Prices->Item as $key => $value) {
//    $g = $value->attributes(1);
//    echo div('$g'.pre($g));
    $res = [];
    foreach ($value->attributes() as $key2 => $value2) {
        $res[$key2] = ''.$value2;
    }
    $price[$res['ServiceId']]=$res;
}
    echo div('$price'.pre($price));


foreach($xml->AnalysisGroups as $key => $res) {
    echo div($key.pre($res));
//    echo div($a.'="'.$b."\"\n");
}
//    echo div($key.pre($xml->attributes()));
foreach ($xml->AnalysisGroups->Item as $key => $value) {
//    $g = $value->attributes(1);
//    echo div('$g'.pre($g));
    $res = [];
    foreach ($value->attributes() as $key2 => $value2) {
        $res[$key2] = ''.$value2;
//    $res[] = div($key2.'="'.$value2."\"");
//        $res[] = ($key2);
//        $res[] = ($value2);
//        break;
    }
    $groups[$res['Id']]=['group'=>$res['Name']];
    echo div($key.pre($res));
}
    echo div('$groups'.pre($groups));
    echo div('count Analyses '.count($xml->Analyses->Item));
foreach ($xml->Analyses->Item as $key => $value) {
    $res = [];
    foreach ($value->attributes() as $key2 => $value2) {
        $res[$key2] = ''.$value2;
    }
    if(!isset($groups[$res['AnalysisGroupId']]['analyses']))
        $groups[$res['AnalysisGroupId']]['analyses']=[];
//    $groups[$res['AnalysisGroupId']]['analyses'][$res['Id']] = $res['Name'];
    $groups[$res['AnalysisGroupId']]['analyses'][$res['Id']]
            = ['name'=> $res['Name'],'price'=>$price[$res['Id']]['Price']];
//    $price
//    AnalysisGroupId
}
    echo div('$groups'.pre($groups));
//  echo '<pre>';
//  echo  htmlspecialchars(print_r($xml,1)).'</pre>';
        
        break;
}