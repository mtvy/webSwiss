<?php

/* 
 * trait.MedLabRequestBuilder
 */

trait MedLabRequestBuilder{
    public $q = false;
    
    public function queryBuild($queryType=false,$args = []){
        $out = '';
        if($queryType==false)return $out;
        $this->q = $queryType;
        
        $data = '<?xml version="1.0" encoding="_encoding_"?>
<Message
MessageType="query-dictionaries"
Date="_date_"
Sender="_sender_"
    Password="_pass_"
Receiver="SwissLab">
</Message>';
  
        $query = '';
        $orders = '';
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
  
        $date = date('d.m.Y H:i:s');
//        $sender = '50006';
        $pass = 'P5x8w934JZn62a7F';
        $pass = 'P5x8w934JZn62a7F';
        $sender = '50027';
        
        if(isset($args['is_show_test']) && $args['is_show_test']==true){
            $pass = 'xxxxx';
            $sender = 'xxxxx';
        }

        $encoding = 'Windows-1251';
        $encoding = 'UTF-8';

        $date = date('d.m.Y H:i:s');

        //$MisId="15561815632";
        //$Nr="9930001410";
        $MisId = "15500000001";
        $Nr    = "15500000001";

        $query = $patient = $referral = $orders = $assays = '' ;
  
        $droot = '<?xml version="1.0" encoding="_encoding_"?>
<Message
    MessageType="__messType__"
    Date="_date_"
    Sender="_sender_"
    Receiver="_reciver_"
    Password="_pass_">
    __request__
</Message>';
        
        switch($queryType){
            case'query-dictionaries-version': // 
                $n = "11.19 Запрос на выгрузку текущей версии номенкла-туры";
                break;
            case'dictionaries-version': // 
                $n = "11.20 Текущая версии номенклатуры";
                break;
            case'query-dictionaries': // 
                $n = "11.21 Запрос на выгрузку номенклатуры";
                break;
            case'dictionaries':
                $n = "11.22 Выгрузка номенклатуры";
                break;
            
            case'query-create-referral': // Pt Rl As
                $n = "11.1 Запрос на создание направления";
            case'query-edit-referral':
                $n = "11.3 Запрос на коррекцию направления";
//                break;
                $patient = $this->mlrb_Patient($args);
                $referral = $this->mlrb_Referral($args);
                $assays = $this->mlrb_Assays($args);
                $orders = $this->mlrb_Orders($args);
                break;
            case'result-import-referral':
                $n = "11.2 Подтверждение создания/коррекции направления";
                break;
            case'query-referral-remove':
                $n = "11.4 Запрос на удаление направления";
                break;
            case'query-create-doctor-orders':
                $n = "11.5 Запрос на создание предзаказа";
                break;
            case'result-import-doctor-orders':
                $n = "11.6 Подтверждение создания предзаказа";
                break;
            case'query-referral-results': // Q
                $n = "11.7 Запрос на получение результатов по направлению";
                $query = $this->mlrb_Query($args);
                break;
            case'query-next-referral-results': // Q
                $n = "11.8 Запрос на получение результатов по следующему направлению из очереди";
                $query = $this->mlrb_Query($args);
                break;
            case'query-count-referral-results': // Q
                $n = "11.9 Запрос на подсчет количества заявок с результата-ми, ожидающих в очереди";
                $query = $this->mlrb_Query($args);
                break;
            case'count-referral-results':
                $n = "11.10 Результат подсчета количества заявок с резуль-татами, ожидающих в очереди";
                break;
            case'query-new-referral-results': // Q
                $n = "11.11 Запрос на получение информации по следую-щей заявке с результатами, ожидающей в очереди";
                $query = $this->mlrb_Query($args);
                break;
            case'new-referral-results':
                $n = "11.12 Информация по заявкам с результатами, ожидающих в очереди";
                break;
            case'query-patient-referral-results': // Q
                $n = "11.13 Поиск заявок данного пациента";
                $query = $this->mlrb_Query($args);
                break;
            case'patient-referral-results':
                $n = "11.14 Информация по заявкам данного пациента";
                break;
            case'referral-results':
                $n = "11.15 Результаты по заявке";
                break;
            case'result-referral-results-import': // Vn Rl
                $n = "11.16 Подтверждение импорта информации по направлению";
                $query = $this->mlrb_Version($args);
                $referral = $this->mlrb_Referral($args);
                break;
            case'query-blank-file': // Q
                $n = "11.17 Запрос на получение бланка результатов в би-нарном виде";
                $n = "11.18 Бланк результатов в бинарном виде";
                $query = $this->mlrb_Query($args);
                break;
            case'':
                $n = "";
                break;
        }
        
        $r=[];
        $r['__orders__'] = $orders;
        $referral = strtr($referral,$r);
        
  
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

        $_queryType = 'query-create-referral';
        $_queryType = 'query-dictionaries';
//        $queryType = $query;
      //  $r['_reciver_'] = 'test';
        $r=[];
        $r['__messType__'] = $queryType;
        $r['_reciver_'] = 'SwissLab';
        $r['__request__'] = $data;
        $r['_encoding_']=$encoding;

        $data = strtr($droot,$r);

      //  $r['11.11.11.11']= $_SERVER['SERVER_ADDR'];
      //  $r['88.204.196.134']= $_SERVER['SERVER_ADDR'];
        $r = [];
        $r['_date_']= $date;
        $r['_pass_']=$pass;
        $r['_sender_']=$sender;
        $data_ = strtr($data ,$r);
        $out = $data_;
        return $out;
    }
    public function mlrb_queries ($args = []){
        $qs = [];
                $n = "11.1 Запрос на создание направления";
                $qs ['query-create-referral'] = $n;
                $n = "11.3 Запрос на коррекцию направления";
                $qs ['query-edit-referral'] = $n;
                $n = "11.4 Запрос на удаление направления";
                $qs ['query-referral-remove'] = $n;
                $n = "11.5 Запрос на создание предзаказа";
                $qs ['query-create-doctor-orders'] = $n;
                $n = "11.6 Подтверждение создания предзаказа";
                $qs ['result-import-doctor-orders'] = $n;
                $n = "11.7 Запрос на получение результатов по направлению";
                $qs ['query-referral-results'] = $n;
                $n = "11.8 Запрос на получение результатов по следующему направлению из очереди";
                $qs ['query-next-referral-results'] = $n;
                $n = "11.9 Запрос на подсчет количества заявок с результата-ми, ожидающих в очереди";
                $qs ['query-count-referral-results'] = $n;
                $n = "11.11 Запрос на получение информации по следую-щей заявке с результатами, ожидающей в очереди";
                $qs ['query-new-referral-results'] = $n;
                $n = "11.13 Поиск заявок данного пациента";
                $qs ['query-patient-referral-results'] = $n;
                $n = "11.16 Подтверждение импорта информации по направлению";
                $qs ['result-referral-results-import'] = $n;
                $n = "11.17 Запрос на получение бланка результатов в би-нарном виде";
                $qs ['query-blank-file'] = $n;
                $n = "11.19 Запрос на выгрузку текущей версии номенкла-туры";
                $qs ['query-dictionaries-version'] = $n;
                $n = "11.21 Запрос на выгрузку номенклатуры";
                $qs ['query-dictionaries'] = $n;
//                $qs [''] = $n;
    }
    public function mlrb_answers ($args = []){
        $as = [];
                $n = "11.6 Подтверждение создания предзаказа";
                $as ['result-import-doctor-orders'] = $n;
                $n = "11.10 Результат подсчета количества заявок с резуль-татами, ожидающих в очереди";
                $as ['count-referral-results'] = $n;
                $n = "11.12 Информация по заявкам с результатами, ожидающих в очереди";
                $as ['new-referral-results'] = $n;
                $n = "11.14 Информация по заявкам данного пациента";
                $as ['patient-referral-results'] = $n;
                $n = "11.15 Результаты по заявке";
                $as ['referral-results'] = $n;
                $n = "11.18 Бланк результатов в бинарном виде";
                $as ['file'] = $n;
                $n = "11.20 Текущая версии номенклатуры";
                $as ['dictionaries-version'] = $n;
                $n = "11.22 Выгрузка номенклатуры";
                $as ['dictionaries'] = $n;
    }
    public function mlrb_ ($args = []){
        
    }
    public function mlrb_Patient ($args = []){
        /*
MisId string (необязательно) Идентификатор пациента в МИС. Данный атрибут будет возвращен в МИС «как есть» при формировании сообщения с результа-тами.
Code1 string (необязательно) Номер пациента 1.
Code2 string (необязательно) Номер пациента 2.
LastName string (обязательно) Фамилия пациента.
FirstName string (необязательно) Имя пациента.
MiddleName string (необязательно) Отчество пациента.
Gender int (необязательно) Пол пациента. Возможные зна-чения:
     0 = пол неизвестен
     1 = мужской
     2 = женский
BirthDate date (необязательно) Полная дата рождения пациента.
BirthYear int (необязательно) Год рождения пациента (если полная дата неизвестна).
         * 
         */
        $fields = [];
        $fields['MisId'] = false;
        $fields['Code1'] = false;
        $fields['Code2'] = false;
        $fields['LastName'] = false;
        $fields['FirstName'] = false;
        $fields['MiddleName'] = false;
        $fields['Gender'] = false;
        $fields['BirthDate'] = false;
        $fields['BirthYear'] = false;
        
        if(isset($args['puid'])){
//            $atts['duid'] = get_post_meta( $orderId, 'dso_duid', true );
            $uid = $args['puid'];
//            $fields['MisId'] = get_user_meta( $uid, 'card_numer', true );
            $fields['MisId'] = $args['puid'];
            $fields['Code1'] = get_user_meta( $uid, 'card_numer', true );
            $fields['Code2'] = false;
            $fields['LastName'] = get_user_meta( $uid, 'last_name', true );
            $fields['FirstName'] = get_user_meta( $uid, 'first_name', true );
            $fields['MiddleName'] = get_user_meta( $uid, 'second_name', true );
            $fields['Gender'] = get_user_meta( $uid, 'gender', true );
            $fields['BirthDate'] = get_user_meta( $uid, 'born_date', true );
            $fields['Phone'] = get_user_meta( $uid, 'phone', true );
//            $fields['BirthYear'] = get_user_meta( $uid, 'born_year', true );
        }
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
        $patient = '
    <Patient
        _attributes_
    />';
        $attributes = [];
        $sep = "\n        ";
        foreach($fields as $n=>$v){
            if($v !== false && strlen($v)>0){
                $attributes[] = $n.'="'.$v.'"';
            }
        }
        $attributes = implode($sep,$attributes);
        $r=[];
        $r['_attributes_'] = $attributes;
//        $r['_attributes_'] = print_r($fields,1);
        $patient = strtr($patient,$r);
        return $patient;
    }
    public function mlrb_Referral ($args = []){
        /*
MisId   string  (необязательно) Идентификатор направления в МИС. Данный атрибут будет возвращен в МИС «как есть» при формировании сообщения с результатами.
Nr      string  (обязательно) Номер направления. Номер направления должен быть уникальным. По нумерации направлений см. раздел 4.4.2 Ну-мерация направлений.
LisId   int     (необязательно) Идентификатор созданного направления в ЛИС (берется из сообщения «Подтверждение создания/коррекции направ-ления»).
    Если LisId указан, то могут быть откорректи-рованы любые поля и состав проб/услуг.
    Если LisId не указан, то можно откорректиро-вать все, кроме данных пациента и номера направления.
Date    datetime    (обязательно) Дата формирования направле-ния.
SamplingDate    datetime    (необязательно) Дата и время взятия биома-териала. Если известна, то лучше присылать.
DeliveryDate    date        (необязательно) Ожидаемая дата доставки биоматериала в лабораторию (если отличает-ся от даты создания или даты забора).
HospitalCode    string  Код заказчика, от которого поступило направ-ление. Используется, если МИС и ЛИС в пре-делах одного медицинского учреждения
          и разделяют справочник заказчиков. В против-ном случае заказчик будет определяться по отправителю сообщения.
DepartmentName  string  (необязательно) Отделение заказчика (назва-ние).
DepartmentCode  string  (необязательно) Отделение заказчика (код).
         * 
 DoctorName      string  (необязательно) Направивший врач (полное ФИО, либо в фамилия с инициалами).
 DoctorSpecialization    (необязательно) Специализация врача.
DoctorCode      string  (необязательно) Направивший врач (уникаль-ный код или табельный номер).
 Cito            boolean (необязательно) Признак срочности направ-ления.
 DiagnosisName   string  (необязательно) Диагноз строкой.
DiagnosisCode   string  (необязательно) Диагноз (код, по МКБ-10 или другому справочнику).
 Comment         string  (необязательно) Комментарий врача к направлению.
PregnancyWeek   int     (необязательно) Срок беременности для жен-щин (в неделях).
CyclePeriod     int     (необязательно) Фаза цикла. Возможные зна-чения:
     0 = не указана
     1 = овуляторная
     2 = фолликулярная
     3 = лютеиновая
     4 = менопауза
     5 = постменопауза
     6 = пременопауза
     7 = беременности нет (без указания фазы цикла)
     8 = беременность есть (без указания срока)
LastMenstruation    date    (необязательно) Дата последней менструации.
DiuresisMl      int     (необязательно) Объем суточного диуреза (в мл).
WeightKg        float   (необязательно) Вес пациента (в кг).
HeightCm        int     (необязательно) Рост пациента (в см).
         */
        $fields = [];
        $fields['MisId'] = false;
        $fields['Nr'] = false;
        $fields['LisId'] = false;
        $fields['Date'] = false;
        $fields['SamplingDate'] = false;
        $fields['DeliveryDate'] = false;
        $fields['HospitalCode'] = false;
        $fields['DepartmentName'] = false;
        $fields['DepartmentCode'] = false;
        $fields['DoctorName'] = false;
        $fields['DoctorSpecialization'] = false;
        $fields['DoctorCode'] = false;
        $fields['Cito'] = false;
        $fields['DiagnosisName'] = false;
        $fields['DiagnosisCode'] = false;
        $fields['Comment'] = false;
        $fields['PregnancyWeek'] = false;
        $fields['CyclePeriod'] = false;
        $fields['LastMenstruation'] = false;
        $fields['DiuresisMl'] = false;
        $fields['WeightKg'] = false;
        $fields['HeightCm'] = false;
        
        if(isset($args['duid']) && $args['duid']>0){
//            $atts['duid'] = get_post_meta( $orderId, 'dso_duid', true );
            $uid = $args['duid'];
//            $orderId = $args['dso_id'];
//            $fields['MisId'] = get_user_meta( $uid, 'card_numer', true );
//            $fields['Code1'] = $args['puid'];
//            $fields['Code2'] = false;
            $fio = get_user_meta($uid,'last_name',1);
            $fio .= ' '.get_user_meta($uid,'first_name',1);
            $fio .= ' '.get_user_meta($uid,'second_name',1);
            $date = date('d.m.Y H:i:s');
//            нумерация на сайте -  как будет удобно, 
//            нумерация в лис  пос может быть сделать
//            пул номер с 9950000001- до 9979999999 ( что бы по заявке все сразу видели что это пул сайта)
//            $fields['MisId'] = $orderId;
//            $num = 9950000000;
//            $num = $num + $orderId;
////            if(isset($args['nr']))
//            $fields['Nr'] = $num; // 9950000001 до 9979999999
////            $lisId = get_post_meta( $orderId, 'dso_LisId', true );
//            $fields['LisId'] = get_post_meta( $orderId, 'dso_query_id', true );
////            $fields['Nr'] = $args['nr'];
//            $fields['Date'] = $date;
            $fields['DoctorName'] = $fio;
            $fields['DoctorCode'] = $uid;
            $fields['Comment'] = $args['refferral']['comment'];
//            $fields['Gender'] = get_user_meta( $uid, 'gender', true );
//            $fields['BirthDate'] = get_user_meta( $uid, 'born_date', true );
//            $fields['BirthYear'] = get_user_meta( $uid, 'born_year', true );
        }
        if(isset($args['puid']) && $args['puid']>0){
            $puid = $args['puid'];
            $fields['Gender'] = get_user_meta( $puid, 'gender', true );
            if($fields['Gender'] == 2){
//                $pregnancy = get_user_meta( $puid, 'pregnancy', true );
//                if($pregnancy == 1){
                    $pregnancy_week = get_user_meta( $puid, 'pregnancy_week', true );
                    if($pregnancy_week > 0){
                        $fields['PregnancyWeek'] = $pregnancy_week;
                    }
//                }
            }
        }
        if(isset($args['dso_id'])){
//            $atts['duid'] = get_post_meta( $orderId, 'dso_duid', true );
//            $uid = $args['duid'];
            $orderId = $args['dso_id'];
//            $fields['MisId'] = get_user_meta( $uid, 'card_numer', true );
//            $fields['Code1'] = $args['puid'];
//            $fields['Code2'] = false;
//            $fio = get_user_meta($uid,'last_name',1);
//            $fio .= ' '.get_user_meta($uid,'first_name',1);
//            $fio .= ' '.get_user_meta($uid,'second_name',1);
            $date = date('d.m.Y H:i:s');
//            нумерация на сайте -  как будет удобно, 
//            нумерация в лис  пос может быть сделать
//            пул номер с 9950000001- до 9979999999 ( что бы по заявке все сразу видели что это пул сайта)
            $fields['MisId'] = $orderId;
            $numgroup = 9950000000;
            $num = $numgroup;
            $num = $num + $orderId;
            if($this->q == 'query-edit-referral' ){
                $num =  get_post_meta( $orderId, 'dso_query_nr', true );
                $num = apply_filters( 'medlab_num_query_get', $num, $orderId, $numgroup );
            }else{
                $num = apply_filters( 'medlab_num_query', $num, $orderId, $numgroup );
            }
//            if(isset($args['nr']))
            $fields['Nr'] = $num; // 9950000001 до 9979999999
//            $lisId = get_post_meta( $orderId, 'dso_LisId', true );
            $fields['LisId'] = get_post_meta( $orderId, 'dso_query_id', true );
//            $fields['Nr'] = $args['nr'];
            $fields['Date'] = $date;
//            $fields['DoctorName'] = $fio;
//            $fields['DoctorCode'] = $uid;
//            $fields['Comment'] = $args['refferral']['comment'];
//            $fields['Gender'] = get_user_meta( $uid, 'gender', true );
//            $fields['BirthDate'] = get_user_meta( $uid, 'born_date', true );
//            $fields['BirthYear'] = get_user_meta( $uid, 'born_year', true );
        }
        
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
        $referral = '
    <Referral
        _attributes_
    >
      __orders__
    </Referral>';
        $attributes = [];
        $sep = "\n        ";
        foreach($fields as $n=>$v){
            if($v !== false && strlen($v)>0){
                $attributes[] = $n.'="'.$v.'"';
            }
        }
        $attributes = implode($sep,$attributes);
        $r=[];
        $r['_attributes_'] = $attributes;
//        $r['_attributes_'] = print_r($fields,1);
        $referral = strtr($referral,$r);
        return $referral;
    }
    public function mlrb_Orders ($args = []){
        
    
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
    
        $orders = '<Orders>
        _items_
      </Orders>';
        $item = '<Item _attributes_/>';
        $items = [];
        
        
        foreach($args['orders'] as $n=>$v){
            $fields = [];
            $fields['Code'] = false;
            $fields['BiomaterialCode'] = false;
            if(isset($v['code']))$fields['Code'] = $v['code'];
            if(isset($v['BiomaterialCode']))$fields['BiomaterialCode'] = $v['BiomaterialCode'];
            $attributes = [];
            $i_sep = " ";
            $sep = "\n        ";
            foreach($fields as $n=>$v){
                if($v !== false && strlen($v)>0){
                    $attributes[] = $n.'="'.$v.'"';
                }
            }
            $attributes = implode($i_sep,$attributes);
            $r=[];
            $r['_attributes_'] = $attributes;
            $items[] = strtr($item,$r);
        }
        
        $_items = implode($sep,$items);
        $r=[];
        $r['_items_'] = $_items;
//        $r['_attributes_'] = print_r($fields,1);
        $_orders = '';
        if(count($items)>0)$_orders = strtr($orders,$r);
        return $_orders;
    }
    public function mlrb_Assays ($args = []){
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
        $assays = '
    <Assays>
        _items_
    </Assays>';
        
        $sep = "\n        ";
        $item = '<Item _attributes_>
          __orders__
        </Item>';
        $items = [];
        
        if(isset($args['Assays'])){
            foreach($args['Assays'] as $n=>$v){
                $fields = [];
                $fields['Barcode'] = false;
                $fields['Code'] = false;
                $fields['BiomaterialCode'] = false;
                if(isset($v['Barcode']))$fields['Barcode'] = $v['Barcode'];
                if(isset($v['code']))$fields['Code'] = $v['code'];
                if(isset($v['BiomaterialCode']))$fields['BiomaterialCode'] = $v['BiomaterialCode'];
                $attributes = [];
                $i_sep = " ";
                $sep = "\n        ";
                foreach($fields as $n=>$val){
                    if($v !== false && strlen($val)>0){
                        $attributes[] = $n.'="'.$val.'"';
                    }
                }
                $attributes = implode($i_sep,$attributes);
    //            add_log('$v<pre>'.htmlspecialchars(print_r($v,1)).'</pre>');
                $orders = $this->mlrb_Orders($v);
                $r=[];
                $r['_attributes_'] = $attributes;
                $r['__orders__'] = $orders;
                $items[] = strtr($item,$r);
            }
        }
        
        $_items = implode($sep,$items);
        $r=[];
        $r['_items_'] = $_items;
//        $r['_attributes_'] = print_r($fields,1);
        $_assays = '';
        if(count($items)>0)$_assays = strtr($assays,$r);
        return $_assays;
    }
    public function mlrb_Item ($args = []){
        
    }
    public function mlrb_Query ($args = []){
        
        $query = '
    <Query
        _attributes_
    />';
            $attributes = [];
            $i_sep = " ";
            $sep = "\n        ";
            foreach($args['query'] as $n=>$val){
                if($val !== false && strlen($val)>0){
                    $attributes[] = $n.'="'.$val.'"';
                }
            }
            $attributes = implode($i_sep,$attributes);
//            add_log('$v<pre>'.htmlspecialchars(print_r($v,1)).'</pre>');
            $r=[];
            $r['_attributes_'] = $attributes;
            return strtr($query,$r);
    }
    public function mlrb_Version ($args = []){
        
    }
}