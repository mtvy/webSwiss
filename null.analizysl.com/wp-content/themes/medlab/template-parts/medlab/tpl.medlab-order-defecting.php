<?php

/* 
 * tpl.medlab-order-defecting.php
 * [ml_page tpl="tpl.medlab" type="order-defecting" old=""]
 * [medlab page="tpl.medlab" type="order-defecting" old=""]
 */

interface iOrderDefecting {
    public function getParam();
    public function getOrderItems();
    public function getOrderLis();
    public function show();
    
}

class OrderDefecting implements iOrderDefecting
{
    public $oid = 0;
    public $answer_out = '';
    public $log_out_warning = [];
    public $log_out = [];
    public $args_out = [
        'log_out'=>'',
        'answer_out'=>'',
        'order_items_list'=>''
    ];
    public $order_item_row_num = 0;
    public $order_items = [];
    public $order_items_count = 0;
    public $url_order_defect_edit = '';
    public $lis_xml = null;
    public $queryItemsList = [];
    
    public function __construct() {
        $this->getParam();
        $this->url_order_defect_edit = get_the_permalink( 28391 ) . '?oid=' . $this->oid;
        if($this->oid>0){
            $this->process(); // обработка форм
            $this->getOrderLis(); // получаем xml заявки в лис
            $this->getOrderItems(); // получаем список позиций заказа
            $this->biltQueryItemsList(); // выстраиваем список статусов позиций заявки в лис
            $this->biltOrderItems(); // построение таблицы списка позиций заказа
        }
        $this->preShow();
        $this->show();
    }
    public function preShow() {
        global $ht;
        $this->args_out['log_out_warning'] = '';
        if(count($this->log_out_warning)){
            $log_out_warning = $ht->f('div',implode('<br/>', $this->log_out_warning),['class'=>'alert alert-danger']);
            $this->args_out['log_out_warning'] = $log_out_warning;
        }
        $log_out = $ht->f('div',implode('<br/>', $this->log_out),['class'=>'alert alert-primary']);
        $this->args_out['log_out'] = $log_out;
    }
    public function getParam() {
        global $ht;
        $oid = filter_input(INPUT_POST, 'oid', FILTER_SANITIZE_NUMBER_INT);
        $this->oid = $ht->postget( 'oid',0, FILTER_SANITIZE_NUMBER_INT);
        $this->ftype = filter_input(INPUT_POST, 'form-type', FILTER_SANITIZE_STRING);
    }
    public function process() {
        if($this->ftype=='order-defecting'){
            $this->processOrderItemsUpdating();
            $this->processOrderQuerySending();
        }
    }
    public function processOrderItemsUpdating() {
        $this->getOrderItems();
                
//        $pid = filter_input(INPUT_POST, 'dsoi_id',FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);
//        if($pid===false || $pid===null|| $pid==='')$pid=[];
//        $count = filter_input(INPUT_POST, 'dsoi_cou',FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);
//        if($count===false || $count===null || $count==='')$count=[];
        $dso_item = filter_input(INPUT_POST, 'dso_item',FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
//        add_log($dso_item,'def','primary');
        if($dso_item===false || $dso_item===null || $dso_item==='')$dso_item=[];
        if($this->order_items_count>0 ){
            foreach( $this->order_items as $item ){
                if ( isset( $dso_item[$item->ID] ) ){
                    if ( isset( $dso_item[$item->ID]['state'] ) ){
                        update_post_meta($item->ID,'dsoi_refState',$dso_item[$item->ID]['state']);
                    }else{
                        update_post_meta($item->ID,'dsoi_refState',0);
                    }
                    if ( isset( $dso_item[$item->ID]['defected'] ) ){
                        update_post_meta($item->ID,'dsoi_defected',$dso_item[$item->ID]['defected']);
                    }else{
                        update_post_meta($item->ID,'dsoi_defected',0);
                    }
                    if ( isset( $dso_item[$item->ID]['defects'] ) ){
                        update_post_meta($item->ID,'dsoi_defects',$dso_item[$item->ID]['defects']);
                    }else{
                        update_post_meta($item->ID,'dsoi_defects','');
                    }
                }
            }
        }
    }
    public function processOrderQuerySending() {
        global $ds_ext_ml;
        $object=null; $orderId=$this->oid; $item=null;
        $ds_ext_ml->save_post($object, $orderId, $item);
        add_log('send_query');
        wp_redirect($_SERVER['HTTP_REFERER']);
        exit();
    }
    public function getOrderItems() {
        $oargs = [
        //    	'ID' => $oid,
        //        'author'  => $user->ID,
            'numberposts' => 1000,
            'offset'    => 0,
        //	'numberposts' => $count,
        //	'offset'    => $offset,
        //	'category'    => 0,
            'orderby'     => 'date',
            'order'       => 'DESC',
        //    	'include'     => [$oid],
        //	'exclude'     => array(),
            'meta_key'    => 'dsoi_orderId',
            'meta_value'  => $this->oid,
            'post_type'   => 'dsoitem',
            'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
        ];
        $query = new WP_Query( $oargs );
        $this->order_items_count =  $query->found_posts;
        if($this->order_items_count>0 ){
            ob_start();
            $this->order_items = get_posts( $oargs );
            $err = ob_get_clean();
            wp_reset_query();
        }
    }
    public function biltQueryItemsList() {
        $queryItemsList = [];
        if($this->lis_xml
                && isset($this->lis_xml->Referral)
                && isset($this->lis_xml->Referral->Orders)
                && isset($this->lis_xml->Referral->Orders->Item)
                ){
            $state_id = 0;
            foreach($this->lis_xml->Referral->Orders->Item as $Item){
                $qiCode = ''.$Item['Code'];
                $state_Code = ''.$Item['State'];
                $queryItemsList[$qiCode] = [];
                $queryItemsList[$qiCode]['code'] = $qiCode;
                $queryItemsList[$qiCode]['state'] = $state_Code;
                $queryItemsList[$qiCode]['state'] = ''.$Item['State'];
                $queryItemsList[$qiCode]['defected'] = ''.$Item['Defected'];
                $queryItemsList[$qiCode]['defects'] = ''.$Item['Defects'];
                $queryItemsList[$qiCode]['defectcode'] = ''.$Item['DefectCode'];
            }
        }
        $this->queryItemsList = $queryItemsList;
    }
    public function biltOrderItems() {
        if($this->order_items_count>0 ){
            $num=0;
            $medLab = MedLab::_instance();

            $groups = $medLab->groups;
            $analyses = $medLab->analyses;
            $panels = $medLab->panels;
            $tests = $medLab->tests;
            $biomaterials = $medLab->biomaterials;
            $drugs = $medLab->drugs;
            $microorganisms = $medLab->microorganisms;
            $containers = $medLab->containers;
            $price = $medLab->price;
            $currency_short = get_option('currency_short','zl');

            $oitems = [];
            foreach( $this->order_items as $item ){
                setup_postdata($item);
                $dsoi_prodId_ =  get_post_meta( $item->ID, 'dsoi_prodId', true );
                $dsoi_prodName_ =  get_post_meta( $item->ID, 'dsoi_prodName', true );
                
                $dsoi_refState_ =  get_post_meta( $item->ID, 'dsoi_refState', true );
                $dsoi_defected_ =  get_post_meta( $item->ID, 'dsoi_defected', true );
                $dsoi_defects_ =  get_post_meta( $item->ID, 'dsoi_defects', true );
                
                $pid = $dsoi_prodId_;
                $name = $dsoi_prodName_;
                $code = '';
                if(isset($analyses[$pid])){
                    $code=$analyses[$pid]['Code'];
                }
                if(isset($panels[$pid])){
                    $code=$panels[$pid]['Code'];
                }
                
                $state_names = [];
                $state_names [0] = '---';
                $state_names [1] = 'новое';
                $state_names [3] = 'сделано (но не одобрено)';
                $state_names [4] = 'выполнено (одобрено)';
                $state_names [5] = 'отменено (не может быть выпол-нено)';
                
                $state_id = 0;
                $state_name = $state_names[$state_id];
                $defects_names = '';
                
                if(isset($this->queryItemsList[$code])){
                    $state_id_ = $this->queryItemsList[$code]['state'];
                    if(isset($state_names[$state_id_])){
                        $state_id = $state_id_;
                        $state_name = $state_names[$state_id];
                    }

                    $defects_names = $this->queryItemsList[$code]['defects'];
                    $defects_names = nl2br($defects_names);
                }
                
                $args_out = [];
                $args_out ['oiid'] = $item->ID;
                $args_out ['state'] = $dsoi_refState_;
                $args_out ['defected'] = $dsoi_refState_;
                $args_out ['defect'] = $dsoi_refState_;
                $args_out ['num'] = $num;
                $args_out ['name'] = $name;
                $args_out ['code'] = $code;
                $args_out ['pid'] = $pid;
                $args_out ['state'] = $dsoi_refState_;
                $args_out ['defected'] = $dsoi_defected_;
                $args_out ['defects'] = $dsoi_defects_;
                
                $args_out ['state_name'] = $state_name;
                $args_out ['defects_names'] = $defects_names;
                $oitems[] = $this-> showOrderItem($args_out);
            }
            $this->args_out['order_items_list'] = implode("\n",$oitems);
        }
    }
    public function showOrderItem($args_out) {
        global $ht;
        extract($args_out);
        ob_start();
        
        $id_item_pref = "dso_item[".$oiid."]";
        
        $name_state = $id_item_pref . "[state]";
        $id_state = strtr($name_state,['['=>'_',']'=>'_',]);
        $r = [ 'type'=>"checkbox", 'value'=>"5", 'id'=>$id_state , 'name'=>$name_state ];
        if($state == 5)$r['checked']='checked';
        $f_state = $ht->f('input','',$r);
        
        $name_defected = $id_item_pref . "[defected]";
        $id_defected = strtr($name_defected,['['=>'_',']'=>'_',]);
        $r = [ 'type'=>"checkbox", 'value'=>"1", 'id'=>$id_defected, 'name'=>$name_defected ];
        if($defected == 1)$r['checked']='checked';
        $f_defected = $ht->f('input','',$r);
        
        $name_defect = $id_item_pref . "[defects]";
        $id_defect = strtr($name_defect,['['=>'_',']'=>'_',]);
        $rous = 2;
        $rous_ = count(explode("\n",$defects));
        if($rous < $rous_ ) $rous = $rous_;
        $r = [ 'id'=> $id_defect, 'name'=>$name_defect, 'rows'=>$rous ];
        $f_defect = $ht->f('textarea',$defects,$r);
        
        ?>
        <tr>
            <th scope="row" rowspan="2" ><?=++$this->order_item_row_num?></th>
            <td><?=$name?></td>
            <td><?=$code?> {<?=$pid?>}</td>
            <td><?=$state_name?></td>
            <td><?=$defects_names?></td>
        </tr>
        <tr>
            <td colspan="4" class="">
                <div class="row">
                    <div class="col-4">
                        <div class="row">
                            <div class="col-6">
                                <label for="<?=$id_state?>">Отменить:</label>
                            </div>
                            <div class="col-6">
                                <?=$f_state?>
                            </div>
                            <div class="col-6">
                                <label for="<?=$id_defected?>">Брак:</label>
                            </div>
                            <div class="col-6">
                                <?=$f_defected?>
                            </div>
                        </div>
                    </div>
                    <div class="col-8">
                        <label for="<?=$id_defected?>">Название брака: </label>
                        <?=$f_defect?>
                    </div>
                </div>
            </td>
        </tr>
        <?php
        return  ob_get_clean();
    }
    public function getOrderLis() {
        global $ht;
        $q = 'query-edit-referral';
        $q = 'query-edit-referral';
        $q = 'query-referral-results';
        $q = 'query-referral-results';
        $atts = [];
        $atts['is_show_test'] = true;
        $query=[];
        $numgroup = 9950000000;
        $num = $numgroup;

        $_log = [];

        // имея id заказа, получаем номер заявки
        if($this->oid>0){
            $orderId =  $this->oid;
            $num = $num + $orderId;
            $num = apply_filters( 'medlab_num_query_get', $num, $orderId, $numgroup );
//                        add_log('medlab_num_query_get<pre>'.htmlspecialchars(print_r($num,1)).'</pre>');
//                        $_log[] = 'medlab_num_query_get: '.htmlspecialchars(print_r($num,1));
        }

//                    $query['MisId'] = $orderId;
//                    $query['Nr'] = $num;
        if($this->oid)$query['MisId'] = $this->oid; // номр заказа
//                    if($f_nr)$query['Nr'] = $f_nr; // номер заявки

        // имея id заказа, получаем id заявки
        if($this->oid)$query['LisId'] = get_post_meta( $orderId, 'dso_query_id', true ); // id заявки

        $atts['query'] = $query;


        /*         определение доступка к лис        */

        // глобальные настройки соединения
        global $ml_ip,$ml_port;
        global $ml_pass,$ml_sender;

        // группа, инициализировавшая запрос
        $gid = (int) get_post_meta( $orderId, 'dso_ml_group', true );

//                    if(!$gid){
//                        $uid = get_current_user_id();
//                        $gid = (int) get_user_meta($uid,'lab_group',1);
//                    }

        // инициализация настроек соединения
        set_ml_access_by_group($gid);

        // отображение настроек соединения
        if(current_user_can('manage_options')){
            $group = '';
            if($gid>0){
                $sel_g = MedLabLabGroupFields::get_lab_group_list();
                $group = $sel_g[$gid];
            }
            $_log[] = ('id группы: '.$gid);
            $_log[] = ('группа: '.$group);
            $_log[] = ('ip лис: '.$ml_ip);
            $_log[] = ('отправитель: '.$ml_sender);
        }

        /*        / определение доступка к лис        */

        // построение запроса для показа
        if(current_user_can('manage_options')){
//                        unset($atts['is_show_test']);
        }
        $data_log = MedLab::_queryBuild($q,$atts); 
//                    add_log('$data_<pre>'.htmlspecialchars(print_r($data_,1)).'</pre>');

        // построение запроса, для запроса
        unset($atts['is_show_test']);
        $data_ = MedLab::_queryBuild($q,$atts);

//                    if(current_user_can('manage_options')){
//                        add_log('текущий ip лис: '.$ml_ip);
//                        add_log('текущий отправитель: '.$ml_sender);
//                    }
//                    add_log('$data_<pre>'.htmlspecialchars(print_r($data_log,1)).'</pre>'); // структура запроса

        // отправка запроса и получение ответа
        $answer = doPostRequest($data_);
        $xml = simplexml_load_string($answer);
        $this->lis_xml = $xml;
        $qrootAtt = MedLab::_buildAttrs($xml);

//                    add_log('$answer<pre>'.htmlspecialchars(print_r($answer,1)).'</pre>');
        $answer_  =  print_r($xml,1);
//                    update_post_meta( $orderId, 'dso_q__answer', print_r($xml,1) );
//                    $answer_ =  get_post_meta( $orderId, 'dso_q__answer', true );

        $_log[] = 'Номер в лаборатории LisId: '.htmlspecialchars(print_r(''.$xml->Referral['LisId'],1));
        $_log[] = 'Номер заказа Nr (mis): '.$num;
        $_log[] = 'Номер заказа Nr (lis): '.htmlspecialchars(print_r(''.$xml->Referral['Nr'],1));
        $_log[] = 'Заявка выполнена: '.((''.$xml->Referral['Done'])=='true'?'Да':'Нет');
        $Activated = [1 => 'Нет',2 => 'Частично',3 => 'Полностью'];
        if ( isset( $Activated[''.$xml->Referral['Activated']] ) ) $Activated = $Activated[''.$xml->Referral['Activated']]; else $Activated = '---';
        $_log[] = 'Прошла активация биоматериала по заявке в лаборатории: ' . $Activated;
        
        if((''.$xml->Referral['Done'])=='true'){
            $this->log_out_warning[]= 'Изменение заявки не возможно, заявка выполнена.';
        }

//        add_log(implode('<br/>', $_log));
        $this->log_out = array_merge($this->log_out, $_log);

        $answer_out = ('<pre>'.htmlspecialchars(print_r($answer_,1)).'</pre>');
        $this->args_out['answer_out']=$answer_out;
//                    add_log('Номер в лаборатории LisId: <pre>'.htmlspecialchars(print_r(''.$xml->Referral['LisId'],1)).'</pre>');
//                    add_log('Номер заказа Nr: <pre>'.htmlspecialchars(print_r(''.$xml->Referral['Nr'],1)).'</pre>');


        if(!isset($qrootAtt['Error']) ){ // && isset($xml->Referral['LisId'])
//                        add_log(isset($qrootAtt['Error']).' == !isset($qrootAtt[Error])');
            if($qrootAtt['MessageType'] == 'result-import-referral' ){
////                            add_log($qrootAtt['MessageType'].' == result-import-referral');
//                            update_post_meta( $orderId, 'dso_query_id', ''.$xml->Referral['LisId'] );
//                            update_post_meta( $orderId, 'dso_query_nr', ''.$xml->Referral['Nr'] );
//                            update_post_meta( $orderId, 'dso_query_status', 'sent' );
//                            $state_ =  get_post_meta( $orderId, 'dso_status', true );
//                            $status = 'query_sent';
//                            if($state_=='send_query')$status = 'query_sent';
//                            if($state_=='change_query')$status = 'query_sent';
//                            update_post_meta( $orderId, 'dso_status', $status );
//                            $user = wp_get_current_user();
//                            update_post_meta( $orderId, 'dso_sender', $user->ID );
//                            $num = apply_filters( 'medlab_num_query_reset', $orderId, false );
//                        }else{
//                            $num = apply_filters( 'medlab_num_query_reset', $orderId, true );
////                            add_log($qrootAtt['MessageType'].' != result-import-referral');
            }
            if($qrootAtt['MessageType'] == 'referral-results' ){
                
            }
        }
        if(isset($qrootAtt['Error']) ){
            $this->log_out_warning[] = $qrootAtt['Error'];
        }
        if(isset($xml->Warnings) && isset($xml->Warnings->Item) ){
            $Items = $xml->Warnings->Item;
            if(!is_array($Items)){
                $Items = [ $Items ];
            }
//                                add_log('count an bm : '.count($an_bm_));

            foreach($Items as $Item){
                $mess = $Item['Text'];
//                add_log('Warnings: '.$mess);
                $this->log_out_warning[] = 'Warnings: '.$mess;
            }
        }
    }
    public function show() {
        extract($this->args_out);
        include 'tpl.medlab-order-defecting--view.php';
    }
}
//echo 'order-defecting';

$iOrderDefecting = new OrderDefecting();