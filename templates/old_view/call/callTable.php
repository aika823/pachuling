<?php
  $dayofweek = ['일', '월', '화', '수', '목', '금', '토'];
  $table_head_list =
    ['workDate' => '근무날짜', 'startTime' => '근무시간', 'companyName' => '상호명', 'workField' => '업종', 'detail' => '요청사항', 'salary' => '일당', 'price' => '콜비', 'employeeName' => '구직자', 'cancelled' => '취소'];
?>

<form id="filterForm" method="post" style="display: none;">
    <input type="hidden" name="action" value="filter">
    <input type="hidden" name="callID" value="">
    <input type="hidden" name="order" value="<?php echo $_POST['order'] ?>">
    <input type="hidden" name="direction" value="<?php echo $_POST['direction'] ?>">
    <input type="hidden" name="filter" value="<?php echo $_POST['filter'] ?>">
  <?php foreach ($_POST as $key => $value): ?>
      <input type="hidden" name="<?php echo $key ?>" value="<?php echo $value ?>">
  <?php endforeach; ?>
</form>

<?php $width = ($this->param->action == 'fix') ? 20 : 64 ?>
<div class="inline scroll_tbody call">
    <table id="call_table" style="width=100%; height: <?php echo (sizeof($this->callList) == 0) ? '50px;' : null ?>;">
        <colgroup>
            <!-- <col width="5%"> -->
            <col width="10%">
            <col width="10%">
            <col width="15%">
            <col width="8%">
            <col width="auto"><!--요청사항-->
            <col width="10%">
            <col width="10%">
            <col width="10%">
            <col width="6%">
        </colgroup>
        <thead>
        <tr>
          <th class="call call-order link al_l round1" id="refresh-workDate">근무날짜</th>
          <th class="call call-order link al_l" id="refresh-startTime">근무시간</th>
          <th class="call call-order link al_l" id="refresh-companyName">상호명</th>
          <th class="call call-order link al_l" id="refresh-workField">업종</th>
          <th class="call call-order link al_l" id="refresh-detail">요청사항</th>
          <th class="call call-order link al_r" id="refresh-salary">일당</th>
          <th class="call call-order link al_r" id="refresh-price">콜비</th>
          <th class="call call-order link al_c" id="refresh-employeeName">구직자</th>
          <th class="call call-order link al_c round2" id="refresh-cancelled">취소</th>
          <!--<? foreach ($table_head_list as $key => $value): ?>
              <th class="call call-order link" id="refresh-<?php echo $key ?>"><?php echo $value ?></th>
          <?php endforeach; ?> -->
        </tr>
        </thead>
      <?php if (sizeof($this->callList) > 0): ?>
          <tbody>
          <?php foreach ($this->callList as $key => $data): ?>
            <?php
            $employeeName = $this->model->select('employee', "employeeID = '{$data['employeeID']}'", 'employeeName');
            $companyName = $this->model->select('company', "companyID = '{$data['companyID']}'", 'companyName');
            ?>
              <tr class="selectable callRow
              <?php
                echo ($data['cancelled'] == 1) ? 'cancelled' : null;
                echo " ";
                echo $this->assignType($data, true);
                echo " ";
                echo $this->get_fixType($data, true);
                echo " ";
                echo ($data['employeeID']) ? 'assigned' : 'not-assigned';
                echo " ";
                echo $this->get_punk_status($data);
              
              ?> "
                  id="<?php echo $data['callID'] ?>">
                  <!--근무날짜-->
                  <td class="al_l">
                    <?php echo date('m/d', strtotime($data['workDate'])) . "(" . $dayofweek[date('w', strtotime($data['workDate']))] . ")" ?>
                  </td>
                  <!--근무시간-->
                  <td class="al_l" style="padding: 0;"><?php echo $this->timeType($data) ?></td>
                  <!--상호명-->
                  <td class="al_l ellipsis">
                      <a href="<?php echo _DOMAIN ?>/company/view/<?php echo $data['companyID'] ?>" class="link2"
                         title="<?php echo $companyName ?>">
                        <?php echo $companyName ?>
                      </a>
                  </td>
                  <!--업종-->
                  <td class="al_l"> <?php echo $data['workField'] ?></td>
                  <!--요청사항-->
                  <td class="al_l update-call link2 ellipsis">
                    <?php if ($this->get_punk_list($data['callID'])): ?>
                      <?php foreach ($this->get_punk_list($data['callID']) as $value): ; ?>
                            <b class="punk-employee">
                              <?php echo $this->model->select('employee', " `employeeID` = '{$value['employeeID']}'", 'employeeName'); ?>
                                :
                              <?php echo $value['detail'] ?>
                              <?php //echo ($this->param->page_type == 'employee') ? $value['detail'] : $value['detail']; ?>
                            </b><br>
                      <?php endforeach; ?>
                    <?php endif; ?>
                    <?php $this->get_callDetail($data) ?>
                  </td>
                  <!--일당-->
                  <td class="al_r"> <?php echo number_format($data['salary']) ?> 원</td>
                  <!--콜비-->
                  <td class="al_r call-price"
                      style="padding:0"><?php echo $this->getPayBtn($data, 'call', 'price'); ?></td>
                  <!--구직자-->
                  <td class="al_c assignedEmployee ellipsis" id="<?php echo $data['employeeID'] ?>">
                    <?php if ($data['cancelled']): ?>
                        취소됨
                    <?php else: ?>
                      <?php if ($this->get_punk_list($data['callID'])): ?>
                        <?php foreach ($this->get_punk_list($data['callID']) as $value): ; ?>
                                <b class="punk-employee">펑크(<?php echo $this->model->select('employee', " `employeeID` = '{$value['employeeID']}'", 'employeeName'); ?>)</b><br>
                        <?php endforeach; ?>
                      <?php endif; ?>
                      <?php if ($data['employeeID']): ?>
                            <a class="assignCancelBtn link" id="<?php echo $data['callID'] ?>"
                               title="<?php echo $employeeName ?>" value="<?php echo $data['employeeID'] ?>">
                              <?php echo $employeeName; ?>
                            </a>
                      <?php else: ?>
                            <button type="button" class="btn-call-check-modal btn-primary"
                              id="open">배정하기
                            </button>
                      <?php endif; ?>
                    <?php endif; ?>
                  </td>
                  <!-- 취소 -->
                  <td class="al_c hide" style="padding: 0;">
                    <?php if ($data['cancelled'] == 0): ?>
                        <button type="button" class="btn-call-cancel-modal btn btn-small btn-danger"
                                id="<?php echo $data['callID'] ?>">취소
                        </button>
                    <?php else: ?>
                        (취소됨)
                    <?php endif; ?>
                  </td>
              </tr>
          <?php endforeach ?>
          </tbody>
      <?php endif; ?>
    </table>
  <?php if (sizeof($this->callList) == 0): ?>
      <h1 style="text-align: center;">내역이 존재하지 않습니다.</h1>
  <?php endif; ?>
</div>