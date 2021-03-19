<?php

/* 
 * trait.MedLabDictionaries
 */

trait MedLabDictionaries{
    
    /**
     * инициализация словаря
     */
    public function _init_dictionaries(){
        self::init();
    }
    public function init_dictionaries(){
        $xml = $this->storedDict();
        $this->buidDictionaries($xml);
    }
    
    public function storedDict($load = false){
        
  
        $queryType = 'query-create-referral';
        $queryType = 'query-dictionaries';
        $q = 'query-dictionaries';
        
        $_mtime = microtime(1);
        $tl=[];
        $tl['test'] = 'milisec get post';

        $ov = get_option('ml_dict_ver'); // stored medlab dictionary version
        $wp_up_dir = wp_upload_dir();
        //    add_log($wp_up_dir);
        $mlfd = $wp_up_dir['basedir'].'/medlab/'; // medlab files directories
        //$nfn = $mlfd.'ml_dict_v_'.$nv.'.xml';
        $ofn = $mlfd.'ml_dict_v_'.$ov.'.xml';
        
        $answer = '';
        
        if($load){
            $data_ = $this->queryBuild($q);
            $answer = doPostRequest($data_);
        }else{
            if(10&&file_exists($ofn)){
//                add_log('stored exists <br/>'.$ofn);
                $answer = file_get_contents($ofn);
            }else{
//                add_log('stored no exists <br/>'.$ofn);
                $data_ = $this->queryBuild($q);
                $answer = doPostRequest($data_);
            }
        }

        $mtime = microtime(1);

        $_ts = $mtime - $_mtime;
        $tl[] = $_ts;
        $_ts = round($_ts,4);
        $tl[] = $_ts;
//        if(TIMER_LOG)add_log($tl);
        

        if (empty($response)) {
            if($queryType == 'query-dictionaries'){
            //    $version = $this->buildAttrs($xml->Version);
            //    add_log($version);
            }
        }
        //ini_set("display_errors", "1");
        //ini_set("display_startup_errors", "1");
        //ini_set('error_reporting', E_ALL);
        //$answer = '';
        $xml = simplexml_load_string($answer);
        //    add_log($xml,'exp');
        //delete_option('ml_dict_ver');

        $qrootAtt = $this->buildAttrs($xml);

        // if dict loaded
        if($queryType == 'query-dictionaries'
                && $xml!==false
                && $xml!==null
                && isset($qrootAtt['MessageType'])
                && $qrootAtt['MessageType'] == 'dictionaries'){
            
            $version = $this->buildAttrs($xml->Version);
            $nv = $version['Version']; // new medlab dictionary version
//            add_log('version: ' . $version['Version']);
            
            $ov = get_option('ml_dict_ver'); // stored medlab dictionary version
            if($ov===false) add_option('ml_dict_ver',$nv);
//            if($ov!==false && $nv>$ov) update_option('ml_dict_ver',$nv);
            $wp_up_dir = wp_upload_dir();
        //    add_log($wp_up_dir);
            $mlfd = $wp_up_dir['basedir'].'/medlab/'; // medlab files directories
            $nfn = $mlfd.'ml_dict_v_'.$nv.'.xml';
            $ofn = $mlfd.'ml_dict_v_'.$ov.'.xml';
            
            if(!file_exists($nfn)){
//                add_log('new no exists <br/>'.$nfn);
                $saved = $this->saveDictCont($mlfd,$nfn,$answer);
                if($saved){
                    if($ov===false) add_option('ml_dict_ver',$nv);
                    if($ov!==false && $nv>$ov) update_option('ml_dict_ver',$nv);
                }
                if(file_exists($ofn)){
//                    add_log('old exists <br/>'.$ofn);

                }else{
//                    add_log('old no exists <br/>'.$ofn);
                }

            }else{
                if($load){
                    add_log('Словарь версии '.$nv.' уже был создан');
//                add_log('new exists <br/>'.$nfn);
                }
            }
        }

        // if dict load filed
        if($queryType == 'query-dictionaries'
                && (
                        !$xml
                        || $xml === false
                        || $xml === null
                        )){
            $ov = get_option('ml_dict_ver',0); // stored medlab dictionary version
            
            add_log('error load dict. version: ' . $ov);
        }
        
        return $xml;
    }
    
    public    $groups=[];
    public    $price=[];

    public    $analyses=[];
    public    $tests=[];
    public    $biomaterials=[];
    public    $drugs=[];
    public    $microorganisms=[];
    public    $containers=[];
    public    $panels=[];
    
    public function buidDictionaries($xml=false){

        $_mtime = microtime(1);

        $this->groups = $this->buildDict($xml,'AnalysisGroups','Id');
        $this->analyses = $this->buildDict($xml,'Analyses','Id');
        $this->panels = $this->buildDict($xml,'Panels','Id');
        $this->tests = $this->buildDict($xml,'Tests','Id');
        $this->biomaterials = $this->buildDict($xml,'Biomaterials','Id');
        $this->drugs = $this->buildDict($xml,'Drugs','Id');
        $this->microorganisms = $this->buildDict($xml,'Microorganisms','Id');
        $this->containers = $this->buildDict($xml,'ContainerTypes','Id');
        $this->price = $this->buildDict($xml,'Prices','ServiceId');
        
        
        foreach ($this->analyses as $aId => $a) {
            // init
            if(!isset($this->groups[$a['AnalysisGroupId']]['analyses']))
                $this->groups[$a['AnalysisGroupId']]['analyses']=[];
        //    $groups[$res['AnalysisGroupId']]['analyses'][$res['Id']] = $res['Name'];

            // set price
            $p = '--';
            if(isset($this->price[$a['Id']]['Price']))$p = $this->price[$a['Id']]['Price'];
            $this->groups[$a['AnalysisGroupId']]['analyses'][$a['Id']]
                    = ['name'=> $a['Name'],'price'=>$p];
        }

        foreach ($this->panels as $pId => $a) {
            // init
            if(!isset($this->groups[$a['AnalysisGroupId']]['panels']))
                $this->groups[$a['AnalysisGroupId']]['panels']=[];
        //    $groups[$res['AnalysisGroupId']]['analyses'][$res['Id']] = $res['Name'];

            // set price
            $p = '--';
            if(isset($this->price[$a['Id']]['Price']))$p = $this->price[$a['Id']]['Price'];
            $this->groups[$a['AnalysisGroupId']]['panels'][$a['Id']]
                    = ['name'=> $a['Name'],'price'=>$p];
        }
        
        $mtime = microtime(1);

        $_ts = ($mtime - $_mtime) - 0;
        $tl=[];
        $tl['test'] = 'milisec build dictionary';
//        $tl[] = sprintf('%f',$_ts);
        $tl[] = $_ts;
        $_ts =  round($_ts,4);
        $tl[] = $_ts;
//        if(TIMER_LOG)add_log($tl);
    }
    
    public static function _price(){
        self::init();
        $out='';
        $out=self::$instance->price;
        return $out;
    }
    
    public static function _instance(){
        self::init();
        return self::$instance;
    }
    
    public function buildAttrs($xml=false){
        $out = [];
        if(!$xml || $xml===false)return $out;
        foreach ($xml->attributes() as $key2 => $value2) {
            $out[$key2] = ''.$value2;
        }
        return $out;
    }

    /**
     * buildAttrs
     */
    public function buildDict($xml=false,$contName=false,$idName=false){
        $out = [];
        if(!$xml || $xml===false || !$contName || !$idName)return $out;
        ob_start();
        foreach ($xml->$contName->Item as $key => $value) {
        //    $g = $value->attributes(1);
        //    echo div('$g'.pre($g));
            $res = $this->buildAttrs($value);
            $out[$res[$idName]] = $res;
            $out[$res[$idName]]['item'] = $value;
        }
        $w = ob_get_clean();
        if(strlen($w)>0){
//            echo div($gId,['class'=>'alert alert-info']);
//            echo div($w,['class'=>'alert alert-warning']);
            add_log($w);
        }
        return $out;
    }

    public function saveDictCont($mlfd,$fn, $d, $act = 'x') {

        if (!is_dir($mlfd)) {
            mkdir($mlfd, 0777, true);
        }else{
    //        if ($dh = opendir($mlfd)) {
    //            while (($file = readdir($dh)) !== false) {
    //                print "Файл: $file : тип: " . filetype($mlfd . $file) . "\n";
    //            }
    //            closedir($dh);
    //        }
        }

        // В нашем примере мы открываем $filename в режиме "дописать в конец".
        // Таким образом, смещение установлено в конец файла и
        // наш $somecontent допишется в конец при использовании fwrite().
        if (!$handle = fopen($fn, $act)) {
            if(DICT_SAVE_LOG)add_log("Не могу открыть файл ($fn)");
            return false;
        }

        // Записываем $somecontent в наш открытый файл.
        if (fwrite($handle, $d) === FALSE) {
            if(DICT_SAVE_LOG)add_log("Не могу произвести запись в файл ($fn)");
            return false;
        }

        if(DICT_SAVE_LOG)add_log("Ура! Записали новый словарь в файл ($fn)");

        fclose($handle);

        // Вначале давайте убедимся, что файл существует и доступен для записи.
        if (is_writable($fn)) {
            if(DICT_SAVE_LOG)add_log("Файл $fn доступен для записи");
        } else {
            if(DICT_SAVE_LOG)add_log("Файл $fn недоступен для записи");
        }
        return true;
    }
    
}