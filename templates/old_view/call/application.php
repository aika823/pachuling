<?php
  
  Class Application
  {
//object 형태의 변수
    var $param;

//생성자
    function __construct()
    {
      //URL을 나눠서 param 변수에 저장 후 application 객체에 저장
      $this->getParam();
      
      if($this->param->page_type != 'dbadmin'){
        //company, ceo 등등의 객체 생성
        new $this->param->page_type($this->param);//ex) new Company
      }
    }

//URL로 받은 주소를 '/' 단위로 나눠서 parameter 배열 내 변수로 저장
    function getParam()
    {
      if (isset($_GET['param'])) $get = explode("/", $_GET['param']);
      $param = '';
      $param['page_type']     = isset($get[0]) && $get[0] != '' ? $get[0]   : 'login'; //기본 URL 연결: 로그인 페이지
      $param['action']        = isset($get[1]) && $get[1] != '' ? $get[1]   : NULL;
      $param['idx']           = isset($get[2]) && $get[2] != '' ? $get[2]   : NULL;
      $param['include_file']  = isset($param['action']) ? $param['action']  : $param['page_type'];
      $param['get_page']      = _URL . "{$param['page_type']}";
      
      //parameter array를 object 형태로 저장
      $this->param = (object)$param;
    }
  }