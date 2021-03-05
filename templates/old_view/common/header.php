<?php
  $selectArr = array_fill(0, 4, 'selectable');
  switch ($this->param->page_type) {
    case 'company'    :
      $selectArr[0] = 'selected';
      break;
    case 'employee'   :
      $selectArr[1] = 'selected';
      break;
    case 'call'       :
      $selectArr[2] = 'selected';
      break;
    case 'manage'     :
      $selectArr[3] = 'selected';
      break;
  }
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>파출링</title>
    <!--css-->
    <link rel="icon" href="<?php echo _IMG ?>logo_item.png" type="image/png">
    <link rel="stylesheet" href="<?php echo _CSS ?>default.css?<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo _CSS ?>common.css?<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo _CSS ?>toggle.css?<?php echo time(); ?>">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.8.18/themes/base/jquery-ui.css"/><!--date picker-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <!-- DATE RANGE PICKER -->
    <!-- Standalone -->
    <link href="/public/dist/css/datepicker.min.css" rel="stylesheet" />
    <!-- For Bootstrap 4 -->
    <link href="/public/dist/css/datepicker-bs4.min.css" rel="stylesheet" />
    <!-- For Bulma -->
    <link href="/public/dist/css/datepicker-bulma.min.css" rel="stylesheet" />
    <!-- For Foundation -->
    <link href="/public/dist/css/datepicker-foundation.min.css" rel="stylesheet" />

    
    <!--js-->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src="//code.jquery.com/ui/1.8.18/jquery-ui.min.js"></script>
    <!-- DATE RANGE PICKER -->
    <script src="/public/dist/js/datepicker-full.min.js"></script>
    <!--moment.js-->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>

    <!--<script src="/public/js/datepicker.js"></script>-->
    <!--toggle-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<?php if (!in_array($this->param->page_type, ['login', 'ceo'])): ?>
    <body>
    <div id="header">
        <div class="header" id="headerMain">
            <div class="dropdown logo">
                <button class="dropbtn" onClick='location.href="<?php echo _URL ?>call"'><img class="btn-img" src="/public/img/logo.png"></button>
            </div>
            <div class="nav_right">
              <div class="dropdown nav_list">
                  <button class=" dropbtn <?php echo $selectArr[2] ?>"
                          onClick='location.href="<?php echo _URL ?>call"'>콜관리
                  </button>
                  <!-- <div class="dropdown-content ">
                      <a class="" href="<?php echo _URL ?>call/write">콜 만들기</a>
                      <a class="" href="<?php echo _URL ?>call/fix">고정콜/월급제 관리</a>
                  </div> -->
              </div>
              <div class="dropdown nav_list">
                  <button class="dropbtn <?php echo $selectArr[0] ?>"
                          onClick='location.href="<?php echo _URL ?>company"'>거래처관리
                  </button>
              </div>
              <div class="dropdown nav_list">
                  <button class=" dropbtn <?php echo $selectArr[1] ?>"
                          onClick='location.href="<?php echo _URL ?>employee"'>구직자관리
                  </button>
                  <!-- <div class="dropdown-content">
                      <a class="" href="<?php echo _URL ?>employee/available_date">근무 가능일/불가능일</a>
                  </div> -->
              </div>
              <div class="dropdown nav_list">
                  <button class=" dropbtn <?php echo $selectArr[3] ?>"
                          onClick='location.href="<?php echo _URL ?>manage/black"'>블랙리스트 관리
                  </button>
                  <!-- <button class="dropbtn <?php echo $selectArr[3] ?>">기타
                  </button>
                  <div class="dropdown-content">
                      <a class="" href="<?php echo _URL ?>manage/black">블랙리스트 관리</a>
                  </div> -->
              </div>
            </div>
        </div>
      <?php if (in_array($this->param->page_type, ['company', 'employee']) && !isset($this->param->action)) require_once 'filter.php' ?>
    </div>
    </body>
<?php endif; ?>
</html>