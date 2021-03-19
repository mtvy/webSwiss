<?php
/**
 * @package addlog
 */
/*
Plugin Name: Add Log
Plugin URI: 
Description: allow you to show messages and dump variables for users, for debuging
Version: 1.0
Author: me
Author URI: my
License: GPLv2 or later
Text Domain: wsd
*/



$opt = array('default' => NULL);
$opt = array('default' => '');
$inputs_['get_errors'] = array('filter'=>FILTER_SANITIZE_STRING, 'options' => $opt);
$inputs_['get_debag'] = array('filter'=>FILTER_SANITIZE_STRING, 'options' => $opt);

$inputs = filter_input_array(INPUT_GET,$inputs_);
if(strlen($inputs['get_errors'])>0&& $inputs['get_errors'] == 1){
    ini_set("display_errors", "1");
    ini_set("display_startup_errors", "1");
    ini_set('error_reporting', E_ALL);
}
if(strlen($inputs['get_debag'])>0&& $inputs['get_debag'] == 1){
    global $isdebug;
    $isdebug=true;
}
/*
123456789 123456789 123456789 123456789 123456789 
123456789 123456789 123456789 123456789 123456789 
123456789 123456789 123456789 123456789 123456789 
123456789 123456789 123456789 123456789 123456789 
123456789 123456789 123456789 123456789 123456789 /**/
class WSDLog{
    public function __construct() {
        $this->init();
    }
    public function init(){
        $this->initDB();
    }
    public function initDB(){
        global $wpdb;
        $tab_name  = 'wsdlog';
        $tab_fields= $wpdb->prefix . $tab_name . "";
        if($wpdb->get_var("SHOW TABLES LIKE '$tab_fields'") != $tab_fields) {
         // тут мы добавляем таблицу в базу данных
            $sql = "CREATE TABLE " . $tab_fields . " (
                 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                 `date` date,
                 `time` time,
                 `type` VARCHAR(16),
                 `group` VARCHAR(16),
                 `from` VARCHAR(256),
                 `title` VARCHAR(128),
                 `data` text null,
                 PRIMARY KEY (`id`),
                 key date (`date`),
                 key time (`time`),
                 key mlgroup (`group`),
                 key title (`title`)
               )
               ENGINE=InnoDB AUTO_INCREMENT=0 comment='';";

//            if(current_user_can('manage_options')){
               require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
               $dbq = dbDelta($sql,1);
//               add_log($dbq);
//            }
        }
    }
}
global $wsdlog;
$wsdlog = new WSDLog();

global $LogInfo;
$LogInfo=array();
function save_log($title='',$data=null,$type='info',$group=''){
    global $wsdlog, $wpdb;
    
        
    $_d=debug_backtrace();
    $f_='';
    if(isset($_d[1]['file']) && isset($_d[1]['line'])){
        $f = str_replace('\\','/',$_d[1]['file']);
    //    __FILE__.':'.__LINE__
        $f = explode('/',$f);
        $f1 = array_pop($f);
        $f2 = array_pop($f);
    //    return
        $f_= '/'.$f2.'/'.$f1.':'.$_d[1]['line'].'<br/>';
    }


    $f = str_replace('\\','/',$_d[0]['file']);
//    __FILE__.':'.__LINE__
    $f = explode('/',$f);
    $f1 = array_pop($f);
    $f2 = array_pop($f);
//    return
    $from = $f_. '/'.$f2.'/'.$f1.':'.$_d[0]['line'];

    if($data!==null  && !is_string($data))$data = serialize ($data);

    $date = current_time('Y-m-d');
    $time = current_time('H:i:s');
        
    
    $tab_name  = 'wsdlog';
    $tabl= $wpdb->prefix . $tab_name . "";
    $q="insert into $tabl set `date`='$date',`time`='$time',`type`='$type',`group`='$group',`from`='$from',`title`='$title',`data`='$data' ;";
    $wpdb->query($q);
    
}
//save_log('WSDLog ','init_addlob','info','WSDLog');
//echo __FILE__;

function logWrapp($d='',$style='def'){
    ob_start();
    switch($style){
        case'admin':
            echo $d;
            break;
        case 'def':
        default:
    ?><div class="container log wrap">
        <?php if(current_user_can('manage_options')){ ?>
        <div class="-cabinet__info-wr">
            <div class="-cabinet__name-photo-wr">
                <h4 class="-cabinet__h4">Log:</h4>
            </div>
        </div>
    <?php }
    echo $d;?>
    </div>
<?php
            break;
    }
    $d=  ob_get_clean();
    return $d;
}
function logItemWrapp($d='',$style='def',$type = 'primary'){
    ob_start();
    switch($style){
        case'admin':
            // message notice
        ?><div class="updated notice message is-dismissible">
    <!--<p><strong>-->
        <?php echo $d;?>
        <!--</strong></p>-->
        </div>
<?php
//    <button type="button" class="notice-dismiss"><span class="screen-reader-text">Скрыть это уведомление.</span></button>
   
        break;
        case 'def':
        default:
//            https://getbootstrap.com/docs/4.1/components/alerts/
    ?>
    <div class="log_mess alert alert-<?=$type?>">
        <?php echo $d;?>
    </div>
<?php
        break;
    }
$d=  ob_get_clean();
    return $d;
}
function showLogInfo($style='def',$show = 1) {
    global $LogInfo;
    $out = '';
//    if(!isset($_SESSION)||empty($_SESSION['loginfo'])){
//        session_start();
//    }
    if(session_status() != PHP_SESSION_ACTIVE){
        @session_start();
    }
    if(empty($_SESSION['loginfo'])){
        $_SESSION['loginfo']=array();
    }
//        $_SESSION['loginfo'][]=$LogInfo;
//        $_SESSION['loginfo'][]=$_SESSION;
    foreach ($_SESSION['loginfo'] as $l) {//$LogInfo
        $type = 'primary';
        if(is_array($l) && isset($l['alert-type']) && isset($l['data'])){
            $type = $l['alert-type'];
            $l = $l['data'];
        }
        $o = $l;
        if(is_array($l)||  is_object($l)){
            $o = '<pre>' . print_r($l, 1) . '</pre>';
        }
        $o=logItemWrapp($o,$style,$type);
        $out .=$o;
    }
    $_SESSION['loginfo']=array();
//    echo 'zzzzzzzzzzzzzzzzzzzz';
    if ($show&&  strlen($out)>0)
        echo logWrapp($out,$style);
    return $out;
}
function  wrapp_admin_log($d){
        if(is_string($d)){
            $d = "<p><strong>".$d."</strong></p>";
        }
    return $d;
}
function ccab_line_left_wrapp($d=''){
    if(strlen($d)>0){
        $d = "<span style=\"float:right;\">".$d."</span>";
    }
    return $d;
}
function add_log_A($d='',$style='def'){
    if(current_user_can('manage_options'))
        add_log($d,$style);
}
/**
 * 
 * @param mixed $d displyed data
 * @param string $style  style output, containing one of: exp, dump, admin, def
 * @param type $type alert type, bootstrup alerts

This is a primary alert—check it out!
This is a secondary alert—check it out!
This is a success alert—check it out!
This is a danger alert—check it out!
This is a warning alert—check it out!
This is a info alert—check it out!
This is a light alert—check it out!
This is a dark alert—check it out!

 */
function add_log($d = '', $style = 'def', $type = 'primary'){
//function add_log($d='',$style='def',$err=false){
    $f='';
    $globalDebug = 1;
    if($globalDebug){
        
        $_d=debug_backtrace();
        $f_='';
        if(isset($_d[1]['file']) && isset($_d[1]['line'])){
            $f = str_replace('\\','/',$_d[1]['file']);
        //    __FILE__.':'.__LINE__
            $f = explode('/',$f);
            $f1 = array_pop($f);
            $f2 = array_pop($f);
        //    return
            $f_= '/'.$f2.'/'.$f1.':'.$_d[1]['line'].'<br/>';
        }
        
        
        $f = str_replace('\\','/',$_d[0]['file']);
    //    __FILE__.':'.__LINE__
        $f = explode('/',$f);
        $f1 = array_pop($f);
        $f2 = array_pop($f);
    //    return
        $f= $f_. '/'.$f2.'/'.$f1.':'.$_d[0]['line'].'<br/>';
            switch($style){
                case'exp':
                    $d = '<pre>var_export $d = ' . var_export($d, 1) . '</pre>';
                    break;
                case'dump':
                    $_d = '<pre>var_dump $d = ' ;
    ob_start(); var_dump($d) ; $_d .= ob_get_clean();
                    $_d .=  '</pre>';
                    $d = $_d;
                    break;
                case'admin':
                case 'def':
                default:
        if(is_array($d) ||  is_object($d)){
                    $d = '<pre>' . print_r($d, 1) . '</pre>';
            }
                    break;
        }
        $f=ccab_line_left_wrapp($f);
        if(current_user_can('manage_options')&&$style!=='clear')
            $d=$f.$d;
    }
    
    if(session_status() != PHP_SESSION_ACTIVE){
        @session_start();
    }
    if(empty($_SESSION['loginfo'])){
        $_SESSION['loginfo']=array();
    }
//    global $LogInfo;
//    $LogInfo[] = $d;
    switch($style){
        case'admin':
            $d = wrapp_admin_log($d);
            break;
        case 'def':
        default:
            break;
    }
    if($type != 'primary'){
        $d = [
            'alert-type' => $type,
            'data' => $d
        ];
    }
    $_SESSION['loginfo'][] = $d;
}