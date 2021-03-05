<?php
  function get_dow_kor($str)
  {
    $eng = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
    $kor = ['월', '화', '수', '목', '금', '토', '일'];
    foreach ($eng as $key => $value) {
      $str = str_replace($value, $kor[$key], $str);
    }
    return $str;
  }
  
  $today = _TODAY;
  $sql = "SELECT * FROM `fix` LEFT JOIN `call` USING(`fixID`)";
  
  if($_POST['date']) $condition['date'] =  " `call`.`workDate` = '{$_POST['date']}'";
  $year   = ($_POST['year']) ?  $_POST['year'] : date('Y', strtotime(_TODAY));
  $month  = ($_POST['month']) ? $_POST['month'] : date('m', strtotime(_TODAY));
  if($_POST['year'])  $condition['year']    = " (YEAR(`call`.`workDate`) = '{$year}' )";
  if($_POST['month']) $condition['month']   = " (YEAR(`call`.`workDate`) = '{$year}' AND MONTH(`call`.`workDate`) = '{$month}') ";
  if($_POST['week'])  $condition['week']    = " (YEARWEEK(`call`.`workDate`, 1)) = (YEARWEEK(curdate(), 1))";
  if(! ($condition['date'] || $condition['year'] || $condition['month'] || $condition['week'] )) $condition['date'] = " `call`.`workDate` = '{$today}' ";
  
  $sql .= ($condition) ? " WHERE ".implode(' AND ', $condition) : null;
  $sql .= " GROUP BY `fixID` ORDER BY `fixID` DESC";
  $fixList = $this->model->getTable($sql);
?>

<form id="filterForm" method="post" style="display: none;">
    <input type="hidden" name="action" value="filter">
    <input type="hidden" name="callID" value="">
  <?php foreach ($_POST as $key => $value): ?>
      <input type="hidden" name="<?php echo $key ?>" value="<?php echo $value ?>">
  <?php endforeach; ?>
</form>

<div class="inline" style="width: 64%;">
    <table id="fixTable" width="100%">
        <colgroup>
            <col width="5%"><!--구분-->
            <col width="10%"><!--상호-->
            <col width="7%"><!--구직자-->
            <col width="10%"><!--근무기간-->
            <col width="10%"><!--요일-->
            <col width="10%"><!--근무시간-->
            <col width="10%"><!--업종-->
            <col width="auto"><!--비고-->
            <col width="10%"><!--월급,수수료-->
            <col width="5%"><!--취소-->
        </colgroup>
        <thead>
        <tr>
          <?php foreach (['구분', '상호명', '구직자', '근무기간', '요일', '근무시간', '업종', '비고', '월급/수수료', '취소'] as $value): ?>
              <th class="al_c link"><?php echo $value ?></th>
          <?php endforeach; ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($fixList as $key => $data): ?>
          <?php
          $employeeName = $this->model->select('employee', "employeeID = '{$data['employeeID']}'", 'employeeName');
          $companyName = $this->model->select('company', "companyID = '{$data['companyID']}'", 'companyName');
          ?>
            <tr class="selectable fixRow <?php echo ($data['monthlySalary']) ? 'salary' : 'fixed'   ?>" id="<?php echo $data['fixID'] ?>">
                <!--구분-->
                <td class="al_c"><?php echo $data['fixID'] . "<br>" . $this->get_fixType($data) ?></td>
              <?php $dayofweek = ['일', '월', '화', '수', '목', '금', '토'] ?>
                <!--상호명-->
                <td class="al_l ellipsis">
                    <a href="<?php echo _DOMAIN ?>/company/view/<?php echo $data['companyID'] ?>" class="link">
                      <?php echo $companyName ?>
                    </a>
                </td>
                <!--구직자-->
                <td class="al_c ellipsis">
                    <a href="<?php echo _DOMAIN ?>/employee/view/<?php echo $data['employeeID'] ?>" class="link">
                      <?php echo $employeeName ?>
                    </a>
                </td>
                <!--근무기간-->
                <td class="al_c td-work-period">
                  <?php echo date('m/d', strtotime($data['workDate'])) . "(" . $dayofweek[date('w', strtotime($data['workDate']))] . ")" ?>
                    <br>~<br>
                  <?php echo date('m/d', strtotime($data['endDate'])) . "(" . $dayofweek[date('w', strtotime($data['endDate']))] . ")" ?>
                </td>
                <!--근무요일-->
                <td class="al_c"><?php echo get_dow_kor($data['dayofweek']) ?></td>
                <!--근무시간-->
                <td class="al_c" style="padding: 0;"><?php echo $this->timeType($data) ?></td>
                <!--업종-->
                <td class="al_c"><?php echo $data['workField'] ?></td>
                <!--비고-->
                <td class="al_l"><?php echo nl2br(strval($data['detail'])) ?></td>
                <!--월급/수수료-->
                <td class="al_c">
                    <i class="fa fa-won"></i><?php echo number_format($data['monthlySalary']) ?><br>
                  <?php echo $this->getPayBtn($data, 'fix', 'commission'); ?>
                </td>
                <!--취소-->
                <td class="al_c">
                  <?php if ($data['cancelled'] == 0): ?>
                      <button type="button" class="fixCancelBtn btn btn-small btn-danger"
                              id="<?php echo $data['fixID']; ?>">취소
                      </button>
                  <?php else: ?>
                      (취소됨)
                  <?php endif; ?>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>

<script>
    $('.fixRow').on('click', function () {
        $(this).closest('table').find('.fixRow').removeClass('selected');
        $(this).addClass('selected');
        $.ajax({
            type: "POST",
            method: "POST",
            url: ajaxURL,
            data: {action: 'callFilter', id: $(this).attr('id')},
            dataType: "text",
            success: function (data) {
                console.log(data);
                $('#callTable_min').html(JSON.parse(data));
            }
        });
    });
</script>