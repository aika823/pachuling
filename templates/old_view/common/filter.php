<!-- <?php
  $select = array_fill(0, 4, 'selectable');
  if (isset($_POST['filter'])) {
    $select[$_POST['filter']] = 'selected';
  } else {
    $select['activated'] = 'selected';
  }
?>

<div class="header" id="filterMain">
    <button type="button" class="filter btn btn-default <?php echo $select['all'] ?>" id="filter-all">
        전체 : <?php echo $this->model->getListNum([$this->defaultCondition]) ?>
    </button>
    <button type="button" class="filter btn btn-default <?php echo $select['activated'] ?>" id="filter-activated">
        활성화 : <?php echo $this->model->getListNum([$this->activatedCondition]) ?>
    </button>
    <button type="button" class="filter btn btn-default <?php echo $select['imminent'] ?>" id="filter-imminent">
        만기임박 : <?php echo $this->model->getListNum([$this->imminentCondition]) ?>
    </button>
    <button type="button" class="filter btn btn-default <?php echo $select['deactivated'] ?>" id="filter-deactivated">
        만기 : <?php echo $this->model->getListNum([$this->deactivatedCondition]) ?>
    </button>
    <button type="button" class="filter btn btn-default <?php echo $select['deleted'] ?>" id="filter-deleted">
        삭제 : <?php echo $this->model->getListNum([$this->deletedCondition]) ?>
    </button>
</div> -->
<!-- <form class="header" id="filterSub" method="post">
    <input type="text" id="inputKeyword" name="keyword" placeholder="검색할 내용을 입력하세요" value="<?php if(isset($_POST['keyword'])) echo $_POST['keyword']  ?>">
    <button class="btn btn-submit" type="submit" id="btnSearch">검색</button>
    <button type="button" id="btnAddCompany" class="btn btn-insert" onclick="window.location.href='<?php echo $this->param->get_page ?>/write'">신규 추가</button>
</form> -->