<div class="mobile_view">
    <div class="user-profile" id="<?php echo $this->companyID ?>">
        <img src="/public/img/ceo.png" alt="Avatar" class="avatar">
        <h1><?php echo $this->companyData['companyName'] ?></h1>
    </div>

    <div class="box">
        <div class="title" style="border-bottom: solid #80808078 2.5px;">가입유형 - <?php echo $this->model->joinType($this->companyID, 'kor') ?></div>
        <div class="content">
            <!--활성화된 가입 내역-->
            <ul type="square">
              <?php foreach ($this->joinData as $key => $value): ?>
                  <li type="disc">
                    <?php echo $value['startDate'] . " ~ " ?>
                    <?php if (isset($value['endDate'])) : ?>
                      <?php echo $value['endDate']."   (" . (strtotime($value['endDate']) - strtotime(date('Y-m-d'))) / 3600 / 24 . "일 남음)" ?>
                    <?php endif; ?>
                  </li>
              <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <!--부른 콜 / 남은 콜-->
  <?php if ($this->model->joinType($this->companyID) != 'deposit'): ?>
      <div class="box">
          <div class="title">이번주 일반 콜</div>
          <div class="content">평일 : <?php echo sizeof($this->weekdayCount) ?> 콜 / 주말
              : <?php echo sizeof($this->weekendCount) ?> 콜
          </div>
      </div>
  <?php endif; ?>
  
  <?php if ($this->model->joinType($this->companyID) != 'point'): ?>
      <div class="box">
          <div class="title">이번주 프리미엄 콜 (콜비￦)</div>
          <div class="content">평일 : <?php echo sizeof($this->weekdayPaidCount) ?> 콜 / 주말
              : <?php echo sizeof($this->weekendPaidCount) ?> 콜
          </div>
      </div>
  <?php endif; ?>
  <?php if ($this->model->joinType($this->companyID) != 'point'): ?>

      <div class="box">
          <div class="title">잔여 콜비 총 합</div>
          <div class="content"><?php echo number_format($this->callPrice)." 원"; ?></div>
      </div>
  <?php else: ?>
      <div class="box">
          <div class="title">잔여 포인트</div>
          <div class="content">
            <?php echo $this->totalPoint; ?>
          </div>
      </div>
  <?php endif; ?>
</div>