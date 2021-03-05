<?php
  $employeeList = $this->model->getTable("SELECT * FROM `employee` WHERE `activated` = 1 OR `bookmark` = 1 ORDER BY `employeeName` ASC");
  $companyList = $this->model->getTable("SELECT * FROM `company` WHERE `activated` = 1 OR `bookmark` =1 ORDER BY `companyName` ASC");
?>
<style>
    .layer {
        display: none;
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        align-items: center;
        justify-content: center;
        /*display:-webkit-flex;*/
        -webkit-align-item;
        center;
        -webkit-justify-content: center;
        background-color: rgba(0, 0, 0, 0.4);
    }
</style>
<div class="write-layout">
    <div class="title-table">
        <h1 class="title-main">콜 만들기</h1>
    </div>
    <div class="board-write">
        <div class="callForm-flex callForm-header">
            <div>
                <button class="btn btn-option selected" id="manualCallBtn">일반콜</button>
                <button class="btn btn-option" id="fixCallBtn">고정</button>
                <button class="btn btn-option" id="monthlyCallBtn">월급제</button>
            </div>
        </div>
    <?php require_once 'callForm.php'; ?>
        <div id="modalProgressBar" class="layer">
            <!--        <span class="content"><img src="/public/img/loading_bar.gif" alt=""></span>-->
        </div>
    </div>

    <?php require_once _VIEW . 'common/modal.php' ?>

    <script>
        $('#btnFixTest').on('click', function () {
            show_snack_bar('테스트입니다.');
        });
    </script>
<script src="/public/js/create_call.js"></script>
</div>