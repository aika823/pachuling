<?php
//  $fixedCondition = " (`fixID` > 0) ";
//  $monthlyCondition = " (`fixID` > 0 AND `salary` = 0) ";
  
  $year = ($_POST['year']) ? $_POST['year'] : date('Y', strtotime(_TODAY));
  
  if ($_POST['month']) {
    $month = $_POST['month'];
  } else {
    if ($_POST['year']) {
      $month = 1;
    } else {
      $month = date('m', strtotime(_TODAY));
    }
  }
  
  if ($_POST['date']) {
    $set_date_picker = date('y-m-d', strtotime($_POST['date']));
  } elseif ($_POST['year'] || $_POST['month']) {
    $set_date_picker = date('y-m-d', strtotime("{$year}-{$month}-01"));
  } else {
    $set_date_picker = date('y-m-d', strtotime(_TODAY));
  }
?>
<div class="inline" style="width: 15%; height: 100%;">
  <div class="datepicker" id="fix-datepicker"></div>
  
  <form action="" id="toggleForm" method="post">
    <input type="hidden" name="action" id="formAction">
    <input type="hidden" name="date" id="toggleDate">
    <input type="hidden" name="year" id="formYear">
    <input type="hidden" name="order" value="<?php echo $_POST['order'] ?>">
    <input type="hidden" name="direction" value="<?php echo $_POST['direction'] ?>">
    <input type="hidden" name="filter" value="<?php echo $_POST['filter'] ?>">
    <?php if ($this->param->action == 'available_date'): ?>
      <input type="hidden" name="table" id="" value="employee_available_date">
    <?php endif; ?>
<!--    --><?php //foreach ($_POST as $key => $value): ?>
<!--      --><?php //if ($key !== 'date'): ?>
<!--        <input type="hidden" name="--><?php //echo $key ?><!--" value="--><?php //echo $value ?><!--">-->
<!--      --><?php //endif; ?>
<!--    --><?php //endforeach; ?>
    <table>
      <!--기간에 따른 필터링-->
      <tr>
        <td>
          <label class="form-switch duration year">
            <b>올해</b>
            <input type="radio" name="year" value="<?php echo date('Y', strtotime(_TODAY)); ?>"
                   id="form-input-year" <?php echo ($_POST['year'] && !$_POST['date'] && !$_POST['week']) ? 'checked' : null ?>>
            <i></i>
          </label>
        </td>
        <td>
          <label class="form-switch duration month">
            <b>이번달</b>
            <input type="radio" name="month" value="<?php echo date('n', strtotime(_TODAY)); ?>"
                   id="form-input-month" <?php echo ($_POST['month'] && !$_POST['date'] && !$_POST['week']) ? 'checked' : null ?>>
            <i></i>
          </label>
        </td>
        <td>
          <label class="form-switch duration week">
            <b>이번주</b>
            <input type="radio" name="week" value="week"
                   id="form-input-week" <?php echo ($_POST['week']) ? 'checked' : null ?>>
            <i></i>
          </label>
        </td>
      </tr>
      <!--고정 유무에 따른 필터링-->
      <tr>
        <td><label class="form-switch">
            <b>고정</b>
            <input type="checkbox" checked><i id="fixed"></i>
          </label>
        </td>
        <td><label class="form-switch">
            <b>월급</b>
            <input type="checkbox" checked><i id="salary"></i>
          </label>
        </td>
      </tr>
    </table>
  </form>
</div>
<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
    $(document).ready(function () {
        set_date_picker();
        click_duration();
        switch_click();
    });

    function set_date_picker() {
        $.datepicker.setDefaults({
            dateFormat: 'yy-mm-dd',
            prevText: '이전 달',
            nextText: '다음 달',
            monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
            monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
            dayNames: ['일', '월', '화', '수', '목', '금', '토'],
            dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
            dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
            showMonthAfterYear: true,
            yearSuffix: '년'
        });
        $("#fix-datepicker").datepicker(
            {
                changeMonth: true,
                changeYear: true,
                onSelect: function () {
                    let date = dateFormat($(this).datepicker('getDate'));
                    $('#toggleDate').val(date);
                    $('#toggleForm').submit();
                },
                onChangeMonthYear: function (year, month, inst) {
                    let now_year = new Date().getFullYear();
                    let now_month = new Date().getMonth() + 1;
                    let year_text = $('.form-switch.duration.year b');
                    let month_text = $('.form-switch.duration.month b');
                    year_text.text(year + '년');
                    month_text.text(month + '월');
                    if (year === now_year) year_text.text('올해');
                    if (month === now_month) month_text.text('이번달');
                    $('#form-input-year').val(year);
                    $('#form-input-month').val(month);

                    if (<?php echo $year?> ===
                    year
                )
                    {
                        console.log('year fit');
                        $('#form-input-year').prop('checked', true);
                    }
                else
                    {
                        console.log(<?php echo $year?>);
                        console.log(year);
                        $('#form-input-year').prop('checked', false);
                    }
                    if (<?php echo $month?> ===
                    month
                )
                    {
                        console.log('month fit');
                        $('#form-input-month').prop('checked', true);
                    }
                else
                    {
                        $('#form-input-month').prop('checked', false);
                    }
                }
            }
        );
        $("#fix-datepicker").datepicker('setDate', '<?php echo $set_date_picker ?>');
    }

    function switch_click() {
        $('.form-switch i').on('mouseup', function () {
            setTimeout(toggle_filter($(this)), 100);
        });
    }

    function click_duration() {
        $('.form-switch.duration.year').on('click', function () {
            $('#toggleDate').val(null);
            $('#form-input-week').prop('checked', false);
            $('#toggleForm').submit();
        });
        $('.form-switch.duration.month').on('click', function () {
            $('#toggleDate').val(null);
            $('#form-input-week').prop('checked', false);
            $('#formYear').val($('#form-input-year').val());
            $('#toggleForm').submit();
        });
        $('.form-switch.duration.week').on('click', function () {
            $('#toggleDate').val(null);
            $('#toggleForm').submit();
        });
    }

    function toggle_filter(element) {
        let id = element.attr('id');
        let status = element.parent().find('input[type=checkbox]').prop('checked');
        let rows = $('.fixRow ');
        if (status) {
            rows.each(function () {
                if ($(this).hasClass(id)) {
                    $(this).hide();
                }
            });
        }
        else {
            rows.each(function () {
                if ($(this).hasClass(id)) {
                    $(this).show();
                }
            });
        }
    }
</script>