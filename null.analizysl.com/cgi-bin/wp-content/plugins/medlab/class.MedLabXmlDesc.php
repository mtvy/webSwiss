<?php

/* 
 * class.MedLabXmlDesc.php
 */

class MedLabXmlDesc extends MedLabInit{
    public $serviceId = '';
    public $itemId = '';
    public $desc = '';
    
    public function __construct() {
        ;
    }
    public function __call($name, $arguments) {
        ;
    }
    public function __get($name) {
        ;
    }
    public function __set($name, $value) {
        ;
    }
    public function __invoke() {
        ;
    }
    
    public function init(){
        $this->init_shortcodes();
        $this->process();
    }
    
    public function init_shortcodes(){
        add_shortcode('__form_xml_desc__',[$this, 'shortcode']);
        add_shortcode('__md_alz_desc__',[$this, 'shortcode']);
    }

    public function shortcode($atts,$content,$tag){
        // shr__list_ shr__cab_ shr__users_ shc_page shr_wgt_
        $out='';
        switch($tag){
            case'__form_xml_desc__':$out=$this->shr__form_xml_desc__($atts,$content);break;
            case'__md_alz_desc__':$out=$this->shr__page_alz_desc__($atts,$content);break;

        }
        return $out;
    }
    // form-xml_desc
    public function shr__form_xml_desc__($atts,$content){
        $out = '';
        ob_start();
        if( $this->is_user_roles( ['administrator', 'contributor'] ) ){
            get_template_part( 'template-parts/component/form', 'xml_desc' );
        }
        $out.=ob_get_clean();
        $out = do_shortcode( $out );
        return $out;
    }
    
    
    // page-alz_desc
    public function shr__page_alz_desc__($atts,$content){
        $out = '';
        ob_start();
            get_template_part( 'template-parts/component/page', 'alz_desc' );
        $out.=ob_get_clean();
        $out = do_shortcode( $out );
        return $out;
    }
    
    public function is_user_role( $role, $user_id = null ) {
        $user = is_numeric( $user_id ) ? get_userdata( $user_id ) : wp_get_current_user();
        if( ! $user ) return false;
        return in_array( $role, (array) $user->roles );
    }
    
    public function is_user_roles( $roles, $user_id = null ) {
        $user = is_numeric( $user_id ) ? get_userdata( $user_id ) : wp_get_current_user();
        if( ! $user ) return false;
//        add_log(array_keys(get_editable_roles()));
//        add_log((get_editable_roles()));
//        add_log($roles);
//        add_log($user->roles);
        return count( array_intersect ( $roles, (array) $user->roles )) > 0;
    }
    
    public function process(){
        ini_set("display_errors", "1");
        ini_set("display_startup_errors", "1");
        ini_set('error_reporting', E_ALL);
        
        $ftype = filter_input(INPUT_POST, 'form-type', FILTER_SANITIZE_STRING);
        if($ftype=='update_xml_desc')$this->prc_update_xml_desc();
    }
    
    public function defXmlDesc(){
        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<Message MessageType="analysis-descriptions" Date="01.08.2019 06:08:37" Sender="SwissLab" Receiver="50027">
    <Version Version="1"/>
    <AnalysisDescriptions>
        <Item ServiceId="49455" Desc="Описание анализа: Проба Реберга (кровь)"/>
        <Item ServiceId="48306" Desc="Описание анализа: Углеводы в кале"/>
        <Item ServiceId="42908" Desc="Описание анализа: Спермограмма"/>
    </AnalysisDescriptions>

    <EditInfo>
Через атрибут "ServiceId" позицию с описписанем,
можно првязать практически к любой записи словаря.

В атрибуте "Desc" содержится описание подготовки к сдаче, либо иная информация.

При необходимости, можно добавить иные атрибуты.

В данных примерах, привязка через атрибут "Id"
 элементов "&amp;lt;Item&amp;gt;"
 содержащихся в теге "&amp;lt;Analyses&amp;gt;".
Можно сделать  привязку к внутренним элементам тега, так же, через атрибут "Id"

Тег "&amp;lt;Version&amp;gt;" содержит версию файла, для учёта изменений.
Если делать файла загрузку вручную,
 или если тег "&amp;lt;AnalysisDescriptions&amp;gt;" включить в общий словарь,
 добавлять тег версии не обязательно, в словаре он уже есть.
&lt;!--
--&gt;
    </EditInfo>
</Message>
XML;
        return $xml;
    }
    
    /**
     * 
     * @link https://www.php.net/manual/ru/simplexml.examples-basic.php
     * @return boolean
     */
    public function prc_update_xml_desc(){
        libxml_use_internal_errors(true);
        $wp_up_dir = wp_upload_dir();
    //    add_log($wp_up_dir);
        $mlfd = $wp_up_dir['basedir'].'/medlab/'; // medlab files directories
//            $nfn = $mlfd.'ml_dict_v_'.$nv.'.xml';
        $nfn = $mlfd.'ml_analysis_descriptions.xml';

        if(!file_exists($nfn)){
//                add_log('new no exists <br/>'.$nfn);
//                $this->saveDictCont($mlfd,$nfn,$answer);
                
            $xmlstr = $this->defXmlDesc();
//            $xml = new SimpleXMLElement($xmlstr);
            $xml = simplexml_load_string($xmlstr);

        }else{
//                add_log('new exists <br/>'.$nfn);
            $xml = simplexml_load_file($nfn);
        }
//        $xml = simplexml_load_string("<?xml version='1.0'><broken><xml></broken>");
        if (!$xml) {
            ob_start();
            echo "Ошибка загрузки XML<br/>\n";
            foreach(libxml_get_errors() as $error) {
                echo "<br/>\t", $error->message;
            }
            $err = ob_get_clean();
            add_log($err);
            return false;
        }
        $this->updateElement($xml);
        add_log($xml);
        //echo $xml->asXML();
        $xml->asXML($nfn);
    }
    public function getxml(){
        $wp_up_dir = wp_upload_dir();
//        add_log($wp_up_dir);
        $mlfd = $wp_up_dir['basedir'].'/medlab/'; // medlab files directories
//            $nfn = $mlfd.'ml_dict_v_'.$nv.'.xml';
        $nfn = $mlfd.'ml_analysis_descriptions.xml';
//        add_log($wp_up_dir['baseurl'].'/medlab/'.'ml_analysis_descriptions.xml');

        if(!file_exists($nfn)){
//                add_log('new no exists <br/>'.$nfn);
//                $this->saveDictCont($mlfd,$nfn,$answer);
                
            $xmlstr = $this->defXmlDesc();
//            $xml = new SimpleXMLElement($xmlstr);
            $xml = simplexml_load_string($xmlstr);

        }else{
//                add_log('new exists <br/>'.$nfn);
            $xml = simplexml_load_file($nfn);
        }
        return $xml;
    }
    public function updateElement($xml){
        $ServiceId = filter_input(INPUT_POST, 'sid', FILTER_SANITIZE_NUMBER_INT);
        if($ServiceId===false || $ServiceId===null|| $ServiceId==='')$ServiceId='0';
        $desc = filter_input(INPUT_POST, 'desc', FILTER_SANITIZE_STRING);
        if($desc===false || $desc===null|| $desc==='')$desc='';
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
        if($title===false || $title===null|| $title==='')$title='';
        
//        $item = $xml->AnalysisDescriptions->addChild('Item', 'PG');
        $updated = false;
        foreach ($xml->AnalysisDescriptions->Item as $key => $item) {
            if($item['ServiceId']==$ServiceId){
                $item['ServiceId'] = $ServiceId;
                $item['Title'] = $title;
                $item['Desc'] = $desc;
                $updated = true;
            }
        }
        if(!$updated){
            $item = $xml->AnalysisDescriptions->addChild('Item');
            $item->addAttribute('ServiceId', $ServiceId);
            $item->addAttribute('Title', $title);
            $desc = $xml->ownerDocument->createCDATASection($desc);
            $item->appendChild($desc);
            $item->addAttribute('Desc', $desc);
        }
    }
    
}