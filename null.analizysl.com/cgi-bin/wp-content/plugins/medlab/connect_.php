<?php

/* 
 * medlab connect
 */


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
global $timeout;
$timeout=30;


function createSocketContextParams($data, $optionalHeaders) {
    global $timeout;
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
        'timeout' => $timeout,
      ],
    ];
    if ($optionalHeaders !== NULL) {
      $params['http']['header'] = $optionalHeaders;
    }

    return $params;
  }
  function throwFailException($communicationStartTime) {
    global $timeout;
    $message = "Проблема при соединении с сервером данных, повторите попытку через несколько минут.";
    if (((time() - $communicationStartTime) >=$timeout)) {
        
      add_log($message . "<br/>\n Превышено время ожидания ответа ($timeout)");
//      throw new TimeoutException($message, $this->timeout);
    } else {
      add_log($message);
//      throw new \Exception($message);
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
//    add_log($params);
    $context = stream_context_create($params);
    $communicationStartTime = time();
    ob_start();
    $fp = fopen('http://'.$ip.':'.$port.'/', 'rb', FALSE, $context);
//    $fp = fopen('http://'.$ip.':'.$port.'/isys/', 'rb', FALSE, $context);
//    $fp = fopen('http://'.$ip.':'.$port.'/isys/', 'wb', FALSE, $context);
//    $fp = fopen('http://213.230.71.167:9901/', 'rb', FALSE, $context);
//    $fp = fopen('213.230.71.167:9901', 'rb', FALSE, $context);
    $err = ob_get_clean();
    if($err && current_user_can('manage_options')){
//    if( current_user_can('manage_options')){
//    if($err ){
        add_log($err);
//        $user = wp_get_current_user();
//        add_log($user);
    }
    if (!$fp) {
      throwFailException($communicationStartTime);
      return false;
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
      return false;
    }else{
        
    }
    $uncompressedData = 0 ? gzuncompress($response) : $response;
    if ($uncompressedData === FALSE) {
      add_log("Проблема при расшифровке данных: ");
      add_log($response);
//      throw new \Exception("Проблема при расшифровке данных: \"{$response}\". ");
    }

    return $uncompressedData;
  }
function _doPostRequest($data, $optionalHeaders = NULL) {
  }