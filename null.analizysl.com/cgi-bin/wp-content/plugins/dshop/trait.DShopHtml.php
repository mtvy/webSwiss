<?php
/*
 * trait.DShopHtml.php
 */
trait DShopHtml {
//    public $mod = 'wsd_acc_inst_serv';
    
    
    /**
     * field form post sort
     * @global type $ht
     * @param type $title
     * @param type $thnum
     * @param type $orders
     * @param type $marker
     * @param array $urlget
     * @param type $ma
     * @param type $md
     * @return type
     */
    public function fsort($title='',$thnum=false,$orders=[],$marker='order',$urlget=[],$ma='↓',$md='↑',$class=false){
        $fields=[];
        foreach($urlget as $n=>$v){
            $at = [];
            $at['type'] = 'hidden';
            $at['name'] = $n;
            $at['value'] = $v;
            $fields[]=$this->f('input','',$at);
        }
        $order_ = false;
        if ($this->post($marker,false)) {
            $order__ =$this->post($marker,false);
            if($orders && array_key_exists($order__,$orders))
                $order_ = $order__;
        }
//        $at['target'] = '_blank';
        $sort = $thnum.'a';
        if($order_==$thnum.'a')$sort = $thnum.'d';
        $at = [];
        $at['type'] = 'hidden';
        $at['name'] = 'order';
        $at['value'] = $sort;
        $fields[]=$this->f('input','',$at);
        $at = [];
        if($class)
        $at['class'] = $class;
        $sw = ''; // sort way
        if($order_==$thnum.'a')$sw = $ma;
        if($order_==$thnum.'d')$sw = $md;
        $fields[]=$this->f('button',$title.' '.$sw,$at);
        return '<form method="post">'.implode("\n",$fields).'</form>';
    }
    
    public function asort($title='',$thnum=false,$orders=[],$marker='order',$urlget='',$ma='↓',$md='↑',$class=false){
//        global $ht;
        if(is_array($urlget)){
            $urlget = http_build_query($urlget);
            if($urlget)$urlget='&'.$urlget;
        }
        $order_ = false;
        if ($this->postget($marker,false)) {
            $order__ =$this->postget($marker,false);
            if($orders && array_key_exists($order__,$orders))
                $order_ = $order__;
        }
        $at = [];
//        $at['target'] = '_blank';
        if($class)
        $at['class'] = $class;
        $q=[];
        $q[$marker] = $thnum.'a';
        if($order_==$thnum.'a')$q[$marker] = $thnum.'d';
        $at['href'] = '?'.http_build_query($q).$urlget;
        $sw = ''; // sort way
        if($order_==$thnum.'a')$sw = $ma; // asc
        if($order_==$thnum.'d')$sw = $md; // desc
        $c=$this->f('a',$title.' '.$sw,$at);
        return $c;
    }
    public function postget($f=false,$d='',$type=FILTER_SANITIZE_STRING){
        $method = INPUT_POST;
        if (!filter_has_var($method, $f)) $method = INPUT_GET;
        if (!filter_has_var($method, $f)) return $d;
        return filter_input($method, $f, $type);
    }
    public function ph($t='',$i=[]){ // panel header
        
        $tpl_panel_header = <<<HTML
      <div class="panel-heading"> _pan_h_title_
        <div class="heading-elements not-collapsible">
          <ul class="icons-list">
            <li> <a  href="?mod=wsd_acc_inst_serv&action=add_doctor"> <i class="fa fa-plus"></i> Добавить аккаунт хирурга</a></li>
            <li> <a  href="?mod=wsd_acc_inst_serv&action=add_patient"> <i class="fa fa-plus"></i> Добавить аккаунт пациента</a></li>
            <li> <a data-toggle="modal" data-target="#advancedsearch" href="#"> <i class="fa fa-search position-left"></i> <span class="visible-lg-inline visible-md-inline visible-sm-inline">Поиск комментариев</span></a></li>
          </ul>
        </div>
      </div>
HTML;

        $c = '';
        if($i !== false){
        $i=[];
        $i[]=['i'=>'plus','act'=>'add_doctor','c'=>' Добавить хирурга'];
        $i[]=['i'=>'plus','act'=>'add_patient','c'=>' Добавить пациента'];
        $i[]=['i'=>'plus','act'=>'add_service','c'=>' Добавить услугу'];
//        $i[]=['i'=>'plus','act'=>'','c'=>''];
        $li=[];
        foreach ($i as $k => $v) {
            $at = [];
            $at['class'] = 'fa fa-'.$v['i'];
            $i=$this->f('i','',$at);
            $at = [];
            $at['href'] = '?mod='.$this->mod.'&action='.$v['act'];
            $c=$this->f('a',$i.$v['c'],$at);
            $at = [];
            $li[]=$this->f('li',$c,$at);
        }
        // serch
        $span = $this->f('span','Поиск комментариев',['class'=>'visible-lg-inline visible-md-inline visible-sm-inline']);
        $at = [];
        $at['class'] = 'fa fa-search position-left';
        $ii=$this->f('i','',$at);
        $at = [];
        $at['href'] = '#';
        $at['data-toggle'] = 'modal';
        $at['data-target'] = '#advancedsearch';
        $c=$this->f('a',$ii.$span,$at);
        $at = [];
//        $li[]=$this->f('li',$c,$at);
        $c=implode("\n",$li);
        
        $at = [];
        $at['class'] = 'icons-list';
        $c=$this->f('ul',$c,$at);
        $at = [];
        $at['class'] = 'heading-elements not-collapsible';
        $c=$this->f('div',$c,$at);
        }
        $at = [];
        $at['class'] = 'panel-heading';
        $out = $this->f('div',$t.$c,$at);
        return  $out;
    }
    public function form_item($t='',$d='',$r=1){
        if($r)$r=' <span style="color: red;">*</span>';else $r='';
        $at = [];
        $at['class'] = 'control-label col-lg-2';
        $t=$this->f('label',$t.$r,$at);
        $at = [];
        $at['class'] = 'col-lg-10';
        $c=$this->f('div',$d,$at);
        $at = [];
        $at['class'] = 'form-group';
        $out = $this->f('div',$t.$c,$at);
        return  $out;
        
    }
    public function row_item($t='',$d='',$r=1){
        if($r)$r=' <span style="color: red;">*</span>';else $r='';
        $at = [];
        $at['class'] = 'control-label col-lg-2';
        $t=$this->f('label',$t.$r,$at);
        $at = [];
        $at['class'] = 'col-lg-10';
        $c=$this->f('div',$d,$at);
        $at = [];
        $at['class'] = 'row';
        $out = $this->f('div',$t.$c,$at);
        return  $out;
        
    }
    public function ul($li,$cl=''){
        
    }
    
    public function div($d='',$class=''){
        $out='<div class="'.$class.'">'.$d.'</div>';
        return $out;
    }
    public function f($f='div',$d='',$attr=[],$data=[],$echo=false){
        $t='<_f_ _attr_ _data_>_d_</_f_>';
        $nc=[]; // no close
        $nc[]='input';
        $nc[]='img';
        $nc[]='submit';
        $nc[]='hr';
        if(in_array($f,$nc))$t='<_f_ _attr_ _data_/>';
        $attr=$this->http_build_attr($attr);
        $data=$this->http_build_attr($data,'data');
        if(is_array($d))$d=implode("\n",$d);
        $r=[];
        $r['_f_']=$f;
        $r['_d_']=$d;
        $r['_attr_']=$attr;
        $r['_data_']=$data;
        $out =  strtr($t,$r);
        if($echo) echo $out;
        return $out;
    }
    public function td($d='',$c='',$at=[]){
        if($c)$at['class']=$c;
        return $this->f('td',$d,$at);
    }
    public function th($d='',$c='',$s=''){
        $at=[];
        if($c)$at['class']=$c;
        if($s)$at['style']=$s;
        return $this->f('th',$d,$at);
    }
    public function tr($d='',$c=''){
        $at=[];
        if($c)$at['class']=$c;
        return $this->f('tr',$d,$at);
    }
    public function table($d='',$t='',$cp='',$attr=[],$data=[],$echo=false){
        $tab='<table _attr_>_cp__t__d_</table>';
        $tcp='<caption>_cp_</caption>';
        $thd='<thead>_cp_</thead>';
        $tbd='<tbody>_cp_</tbody>';
        $attr=$this->http_build_attr($attr);
        $data=$this->http_build_attr($data,'data');
        if(is_array($t)){$t=implode("\n",$t);$t=$this->tr($t);}
        if(is_array($d))$d=implode("\n",$d);
        if($t)$t=$this->f('thead',$t);
        if($d)$d=$this->f('tbody',$d);
        if($cp)$cp=$this->f('caption',$cp);
        $r=[];
        $r['_cp_']=$cp;
        $r['_t_']=$t;
        $r['_d_']=$d;
        $r['_attr_']=$attr;
        $out =  strtr($tab,$r);
        if($echo) echo $out;
        return $out;
    }
    public function tag($d='',$class='',$tagW='div',$attr=[],$attprefix=''){
        $attr=$this->http_build_attr($attr,$attprefix);
        $out='<div class="'.$class.'">'.$d.'</div>';
        $out='<'.$tagW.' class="'.$class.'" '
                .$attr. '>'.$d.'</'.$tagW.'>';
        return $out;
    }
    public function input($name='',$val='',$type='text',
            $class='',$id='',$placeholder='',
            $attr=[], $attprefix=''){
        $attr=$this->http_build_attr($attr,$attprefix);
        $out='<input  class="'.$class.'"'
                . ' name="'.$name.'"'
                . ' type="'.$type.'"'
                . ' id="'.$id.'"'
                . ' value="'.$val.'"'
                . ' placeholder="'.$placeholder.'" '
                .$attr. '/>';
        return $out;
    }
    
    /**
     * построение списка челбоксов
     * html
     */
    public function get_check_list($items=[]){
        $i_=[];
        foreach ($items as $name => $v) {
            $id = 'ch_'.$name;
            $attr=[];
            if(isset($v['checked'])&&$v['checked'])
            $attr['checked']='checked';
            $i = $this->input($name,1,'checkbox','',$id,'',$attr);
            $i_[] = $this->label($v['name'],$id,$i);
        }
    }
    /**
     * html teg "label"
     * @param type $n
     * @param type $id
     * @param type $f
     * @param type $class
     * @param type $pos
     * @return string
     */
    public function label($n='',$id='',$f='',$class='',$pos=0){
        if($n==''&&$f=='')return '';
        $il=[];
        $v='';
        if($pos==0){$il[]=$n;$il[]=$f;}
        if($pos==1){$il[]=$f;$il[]=$n;}
        if($pos==2)$v.=(string)$f;
        $v .= '<label for"'.$id.'" for"'.$class.'">'.implode($il).'</label>';
        if($pos==3)$v.=(string)$f;
        return $v;
    }
    public function button($name='',$val='',$type='text',
            $class='',$id='',$content='',
            $attr=[], $attprefix=''){
        $attr=$this->http_build_attr($attr,$attprefix);
//        add_log($name);
        $out='<button  class="'.$class.'"'
                . ' name="'.$name.'"'
                . ' type="'.$type.'"'
                . ' id="'.$id.'"'
                . ' value="'.$val.'"'
                .$attr. '>'.$content.'</button>';
        return $out;
    }
//    public $form_method='get';
    public function select($name='',$vars=[], $def='',$class='',$id='',
            $attr=[], $attprefix='',$echo=0){
        
        $attr=$this->http_build_attr($attr,$attprefix);
        
//        switch(strtolower($this->form_method)){
//            case 'post':
//                $val = $this->post($name,$def);
//            break;
//            case 'get':
//            default;
//                $val = $this->get($name,$def);
//            break;
//        }
        $val = $this->get($name,$def);
        if($val === $def)
            $val = $this->post($name,$def);
//        echo $this->pre([$this->form_method,$name,$val]);
        $vars_ = [];
        foreach($vars as $k=>$v){
            $ch = ($val == $k);
            $vars_[] = $this->option($k,$v,$ch);
        }
        
        $t='<select name="_n_" id="_id_" class="_cl_" _attr_>_f_</select>';
        $r=[];
        $r['_n_']=$name;
        $r['_cl_']=$class;
        $r['_id_']=$id;
        $r['_attr_']=$attr;
        $r['_f_']=implode("\n",$vars_);
        $out =  strtr($t,$r);
        if($echo) echo $out;
        return $out;
    }
    public function datalist($id='',$vars=[], $class='',
            $attr=[], $attprefix='',$echo=0){
        
        $attr=$this->http_build_attr($attr,$attprefix);
        
        $vars_ = [];
        foreach($vars as $k=>$v){
            $vars_[] = $this->option($k,$v);
        }
        
        $t='<datalist id="_id_" class="_cl_" _attr_>_f_</datalist>';
        $r=[];
        $r['_cl_']=$class;
        $r['_id_']=$id;
        $r['_attr_']=$attr;
        $r['_f_']=implode("\n",$vars_);
        $out =  strtr($t,$r);
        if($echo) echo $out;
        return $out;
    }
    public function option($val='',$d='',$check=false,$class='',$id='',$tagW='option',$echo=0){
        $t='<option value="_v_" _c_ id="_id_" class="_cl_" >_d_</option>';
        $r=[];
        $r['_c_']='';
        if($check)
        $r['_c_']=' selected="selected"';
        $r['_id_']=$id;
        $r['_v_']=$val;
        $r['_d_']=$d;
        $r['_cl_']=$class;
        $out =  strtr($t,$r);
        if($echo) echo $out;
        return $out;
    }
    public function form($fields=[],$class='',$id='',
            $method='',
            $path='',$q=[],$attr=[],$echo=0){
//        echo '$out';
        $t='<form class="_cl_" id="_id_" action="_h__qs__q_" method="_m_" _attr_>_f_</form>';
        if(!count($q) || strpos($path,'?')!==false)
        $qs='';else$s='?';
        $r=[];
        $r['_f_']=implode("\n",$fields);
        $r['_cl_']=$class;
        $r['_id_']=$id;
        $r['_m_']=$method;
        $r['_h_']=$path;
        $r['_qs_']=$qs;
        $r['_q_']=http_build_query($q);
        $r['_attr_']=$this->http_build_attr($attr);
        $out =  strtr($t,$r);
        if($echo) echo $out;
        return $out;
    }
    public function img($src='',$alt='',$class='',$attr=[]){
        $t='<img class="_c_" src="_s_" alt="_a_" _attr_/>';
        $r=[];
        $r['_c_']=$class;
        $r['_s_']=$src;
        $r['_a_']=$alt;
        $r['_attr_']=$this->http_build_attr($attr);
        return strtr($t,$r);
    }
    function http_build_attr(&$attr=[], $prefix='') 
    {
        array_walk($attr,[$this,'_http_build_attr'],$prefix);
        return implode(' ',$attr);
    }
    function _http_build_attr(&$item1, $key, $prefix) 
    {
        if($prefix)$prefix.='-';
        $item1 = "$prefix$key=\"$item1\"";
    }
    public function h($size='1',$name='',$class='',$q=[]){
        $t='<h_s_ class="_c_">_n_</h_s_>';
        $r=[];
        $r['_s_']=$size;
        $r['_n_']=$name;
        $r['_c_']=$class;
        $r['_q_']=http_build_query($q);  // ?
        return strtr($t,$r);
    }
    public function h4($name='',$class='',$q=[]){
        $t='<h4 class="_c_">_n_</h4>';
        $r=[];
        $r['_n_']=$name;
        $r['_c_']=$class;
        $r['_q_']=http_build_query($q);
        return strtr($t,$r);
    }
    public function a($name='',$path='',$q=[],$attr=[],$ancor=''){
        $t='<a href="_h_?_q__a_" _attr_>_n_</a>';
        if(!count($q) || strpos($path,'?')!==false)
        $t='<a href="_h__q__a_" _attr_>_n_</a>';
        $r=[];
        $r['_a_']=$ancor;
        $r['_n_']=$name;
        $r['_h_']=$path;
        $r['_q_']=http_build_query($q);
        if(strpos($path,'?')!==false && strlen($r['_q_'])>0){
            $r['_q_']='&'.$r['_q_'];
        }
        $r['_attr_']=$this->http_build_attr($attr);
//        add_log($r);
        return strtr($t,$r);
    }
    public function pre($res='',$class=''){
        ob_start();
        echo '<pre>';
        echo htmlspecialchars( print_r($res,1));
        echo '</pre>';
        $cat3 = ob_get_clean();
        return $cat3;
    }
    public $tpls=[];
    public function get_tpl($name,$slag='',$rep=[]){
        if(isset($this->tpls[$name])&&isset($this->tpls[$name][$slag])){
            $tpl = $this->tpls[$name][$slag];
        }else{
            ob_start();
                get_template_part( $name,$slag);
            $tpl = ob_get_clean();
//            echo $tpl;
            $this->tpls[$name][$slag] = $tpl;
        }
//        echo $this->pre($rep);
//        echo $this->pre($this->tpls);
        return strtr($tpl,$rep);
    }
    
    public function getpost($method,$f=false,$d='',$type=FILTER_SANITIZE_STRING){
        global $inputs ;
        $inputs = [];
        
        if($f===false)return $d;
//        $out=$d;
//        if(isset($_GET[$f]))$out=$_GET[$f];
//        return $out;
        
        if (!filter_has_var($method, $f)) return $d;
        $opt = array('default' => $d);
        $inputs_[$f] = array('filter'=>$type, 'options' => $opt);
//        echo $this->pre($inputs_);
        $inputs = filter_input_array($method,$inputs_);
        if($inputs[$f] === null)$inputs[$f]=$d;
        if($inputs[$f] === false)$inputs[$f]=$d;
        if(strlen($inputs[$f])==0)$inputs[$f]=$d;
        return $inputs[$f];
    }
    public function get($f=false,$d='',$type=FILTER_SANITIZE_STRING){
        return $this->getpost(INPUT_GET,$f,$d,$type);
    }
    public function post($f=false,$d='',$type=FILTER_SANITIZE_STRING){
        return $this->getpost(INPUT_POST,$f,$d,$type);
    }
}