<?php

/* 
 * trait.MedLabRequestBuilder
 */

trait MedLabRequestBuilder{
    
    public function queryBuild($query=false){
        $out = '';
        if($query==false)return $out;
        
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
        $sender = '50006';
        $pass = 'P5x8w934JZn62a7F';
        $sender = '50027';

        $encoding = 'Windows-1251';
        $encoding = 'UTF-8';

        $date = date('d.m.Y H:i:s');

        //$MisId="15561815632";
        //$Nr="9930001410";
        $MisId = "15500000001";
        $Nr    = "15500000001";

        $query = $patient = $referral = $assays = '' ;
  
        $droot = '<?xml version="1.0" encoding="_encoding_"?>
<Message
    MessageType="__messType__"
    Date="_date_"
    Sender="_sender_"
    Receiver="_reciver_"
    Password="_pass_">
    __request__
</Message>';
  
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

        $queryType = 'query-create-referral';
        $queryType = 'query-dictionaries';
        $queryType = $query;
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
    
}