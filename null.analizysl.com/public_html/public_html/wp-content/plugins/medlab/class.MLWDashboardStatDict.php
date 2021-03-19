<?php

/* 
 * class.MLWDashboardStatDict.php
 * MedLabWidgetDashboardStatDict
 * 
 * https://wp-kama.ru/function/wp_add_dashboard_widget
 */

class MLWDashboardStatDict {
    
    public function __construct() {
//		if ( ! self::$initiated ) {
////			self::_init_hooks();
            $this->init();
//            self::$instance = $this;
//            self::$initiated = true;
//		}
    }
    public function init(){
        add_action( 'wp_dashboard_setup', [$this,'init_widget'] );
//        $this->init_type();
//        $this->init_meta();
    }
    public function init_widget(){
        $args = ['side', 'high'];
//        add_meta_box('mlw_dash_statdict',__('Состояние словаря лаборатории','medlab'),[$this,'show'],'dashboard', 'side', 'high');
        wp_add_dashboard_widget('mlw_dash_statdict',__('Состояние словаря лаборатории','medlab'),[$this,'show'],[$this,'edit'],$args);
    }
    public function show($post, $callback_args){
        $fupdated = '--';
        $ov = get_option('ml_dict_ver'); // stored medlab dictionary version
        $wp_up_dir = wp_upload_dir();
        $mlfd = $wp_up_dir['basedir'].'/medlab/'; // medlab files directories
        $ofn = $mlfd.'ml_dict_v_'.$ov.'.xml';
        if(file_exists($ofn)){
            $stat = stat($ofn);
            $ut = $stat['ctime'];
            $fupdated = date('d.m.Y H:i',$ut);
            $fupdated = date('d.m.Y H:i',filemtime($ofn)+(3600*3));
        }
                    $q = 'query-dictionaries-version';
                    $data_ = MedLab::_queryBuild($q);
                    $answer = doPostRequest($data_);
                    $xml = simplexml_load_string($answer);
                    if($xml)
                    $qrootAtt = MedLab::_buildAttrs($xml->Version);
                    
        $medLab = MedLab::_instance();

        $groups = $medLab->groups;
        $analyses = $medLab->analyses;
        $panels = $medLab->panels;

        $tpl = '<div>Последнее обновление словаря было:<br/>_date_</div>';
        $tpl .= '<div>Текущая версия словаря:<br/>_vesion_</div>';
        $tpl .= '<div>Последняя версия словаря:<br/>_l_vesion_</div>';
        $tpl .= '<div>count Analyses: _count_a_</div>';
        $tpl .= '<div>count Panels: _count_p_</div>';
        
                
        $r=[];
        $r['_date_']=$fupdated;
        $r['_vesion_']=$ov;
        if($xml)
        $r['_l_vesion_']=$qrootAtt['Version'];
        else
        $r['_l_vesion_']='[unknown]';
        $r['_count_a_']=count($analyses);
        $r['_count_p_']=count($panels);
        echo strtr($tpl,$r);
    }
    public function edit(){
        global $ht;
        $_status = filter_input(INPUT_POST, 'ml_wgt_dictstate_act', FILTER_DEFAULT); // FILTER_SANITIZE_NUMBER_INT
        $_update_product = filter_input(INPUT_POST, 'mlwd_update_product', FILTER_VALIDATE_INT); // FILTER_SANITIZE_NUMBER_INT
        if($_status && strlen($_status)>0){
            switch($_status){
                case 'update_dict':
                    MedLab::_storedDict(true);
                    
//                    Последнее обновление словаря было:
//                    31.07.2019 23:50
//                    Текущая версия словаря:
//                    24200005
//                    Последняя версия словаря:
//                    31700020
                    
                    break;
            }
        }
        if($_update_product){
            MedLab::_updateProducts();
        }
        $fupdated = '--';
        $ov = get_option('ml_dict_ver'); // stored medlab dictionary version
        $wp_up_dir = wp_upload_dir();
        $mlfd = $wp_up_dir['basedir'].'/medlab/'; // medlab files directories
        $ofn = $mlfd.'ml_dict_v_'.$ov.'.xml';
        if(file_exists($ofn)){
            $stat = stat($ofn);
            $ut = $stat['ctime'];
            $fupdated = date('d.m.Y H:i',$ut);
            $fupdated = date('d.m.Y H:i',filemtime($ofn)+(3600*3));
            //get_the_time();
        }
                    $q = 'query-dictionaries-version';
                    $data_ = MedLab::_queryBuild($q);
                    $answer = doPostRequest($data_);
                    $xml = simplexml_load_string($answer);
                    if($xml)
                    $qrootAtt = MedLab::_buildAttrs($xml->Version);
                    
        $medLab = MedLab::_instance();

        $groups = $medLab->groups;
        $analyses = $medLab->analyses;
        $panels = $medLab->panels;

        $tpl = '<div>Последнее обновление словаря было:<br/>_date_</div>';
        $tpl .= '<div>Текущая версия словаря:<br/>_vesion_</div>';
        $tpl .= '<div>Последняя версия словаря:<br/>_l_vesion_</div>';
        $tpl .= '<div>count Analyses: _count_a_</div>';
        $tpl .= '<div>count Panels: _count_p_</div>';
        $tpl .= '<br/>';
        $tpl .= '<hr/>';
        $tpl .= '<br/>';
        $tpl .= '<div>Обновить товары: _update_product_</div>';
        
                
        $r=[];
        $r['_date_']=$fupdated;
        $r['_vesion_']=$ov;
        if($xml)
        $r['_l_vesion_']=$qrootAtt['Version'];
        else
        $r['_l_vesion_']='[unknown]';
        $r['_count_a_']=count($analyses);
        $r['_count_p_']=count($panels);
        $r['_update_product_']=$ht->f('input','',['type'=>'checkbox','name'=>'mlwd_update_product','value'=>1])."\n";
        echo strtr($tpl,$r);
        
        echo '<br/><div><label><b>Обновить словарь</b></label></div>';
        $hidden_act = '<input type="hidden" name="ml_wgt_dictstate_act" value="update_dict">';
        echo $hidden_act;
    }
}