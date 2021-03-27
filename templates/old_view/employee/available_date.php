<?php
$query = "SELECT * FROM `employee_available_date`";
if (!$_POST['type'] || $_POST['type'] == 'all') {//전체
    $selected_option['all'] = 'selected';
} else {
    $selected_option[$_POST['type']] = 'selected';
    $condition[] = " `{$_POST['type']}Date` IS NOT NULL ";
}
$date = ($_POST['date']) ? $_POST['date'] : _TODAY;
if($date) $condition[] = " `availableDate` = '{$date}' OR `notAvailableDate` = '{$date}'";


$query.= ($condition) ? " WHERE ".implode(' AND ', $condition) : null;
$available_date_list = $this->model->getTable($query);


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


<style>
    form {
        display: inline;
    }
</style>

<div class="board-write auto-center">
    <div class="title-table">
        <h1 class="title-main">
            근무 가능일 / 근무 불가능일
        </h1>
    </div>
    <div class="form-default">
        <form id="employee_form" action="" method="post">
            <fieldset>
                <input type="hidden" name="action" value="insert_day">
                <input type="hidden" name="employeeID" value="<?php echo $this->employeeData['employeeID'] ?>">
                <div class="table">
                    <div class="tr">
                        <div class="td">
                            <label for="">성명</label>
                            <input type="text" list="employeeList" name="employeeName" required>
                            <datalist id="employeeList" class="input-field">
                                <?php foreach ($this->get_employee_list() as $data): ?>
                                    <option value="<?php echo $data['employeeName']?>"></option>
                                <?php endforeach ?>
                            </datalist>
                        </div>
                    </div>
                    <div class="tr">
                        <div class="td">
                            <label for="">일 주세요</label>
                            <input type="date" name="availableDate" required>
                        </div>
                    </div>
                    <div class="tr">
                        <div class="td">
                            <label for="">일 못가요</label>
                            <input type="date" name="notAvailableDate">
                        </div>
                    </div>
                    <div class="tr">
                        <div class="td">
                            <label for="">비고</label>
                            <textarea name="detail" cols="30" rows="10">내용: &#10;작성자: </textarea>
                        </div>
                    </div>
                </div>
            </fieldset>
            <div class="btn-group al_r  ">
                <button class="btn btn-default" onclick="location.href = '<?php echo $this->param->get_page ?>'">취소
                </button>
                <button class="btn btn-submit" type="submit">추가</button>
            </div>
        </form>
    </div>

    <!--블랙리스트 필터 폼-->
    <div class="btn-group" style="display: inline;height: 150px;">
        <form action="" method="post" style="height: 100%;">
            <input type="hidden" name="type" value="all">
            <input type="submit" class="btn btn-option <?php echo $selected_option['all'] ?>" value="전체"
                   style="height: 100%;">
        </form>
        <form action="" method="post" style="height: 100%;">
            <input type="hidden" name="type" value="available">
            <input type="submit" class="btn btn-option <?php echo $selected_option['available'] ?>" value="일 주세요"
                   style="height: 100%;">
        </form>
        <form action="" method="post" style="height: 100%;">
            <input type="hidden" name="type" value="notAvailable">
            <input type="submit" class="btn btn-option <?php echo $selected_option['notAvailable'] ?>" value="일 못가요"
                   style="height: 100%;">
        </form>
    </div>

    <div class="al_c">
        <?php $table = 'employee_available_date' ?>
        <div class="inline" style="height: 100%; max-width: 255px">
            <div class="datepicker" id="datepicker"></div>
            <form action="" id="toggleForm" method="post">
                <input type="hidden" name="date" id="toggleDate">
            </form>
        </div>
        <div class="inline  call" style="width: calc(100% - 260px);">
            <table style="width:100%;">
                <colgroup>
                    <col width="5%">
                    <col width="15%">
                    <col width="15%">
                    <col width="15%">
                    <col width="40%">
                    <col width="10%">
                </colgroup>
                <thead>
                <tr>
                    <th>#</th>
                    <th>성명</th>
                    <th>일 주세요</th>
                    <th>일 못가요</th>
                    <th>비고</th>
                    <th>삭제</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($available_date_list as $key => $data): ?>
                    <tr class="availableRow" id="<?php echo $data['availableDateID'] ?>">
                        <td class="al_c"><?php echo $data['availableDateID'] ?></td>
                        <?php $employeeName = $this->model->select('employee', "employeeID = $data[employeeID]", 'employeeName'); ?>
                        <td class="al_c link"
                            onClick='location.href="<?php echo _URL . "employee/view/{$data['employeeID']}" ?>"'><?php echo $employeeName ?></td>
                        <td class="al_c"><?php echo ($data['availableDate']) ? $data['availableDate'] : '-' ?></td>
                        <td class="al_c"><?php echo ($data['notAvailableDate']) ? $data['notAvailableDate'] : '-' ?></td>
                        <td class="al_c"><?php echo ($data['detail']) ? nl2br(strval($data['detail'])) : '-' ?></td>
                        <td class="al_c"><button class="btn btn-danger availableDelBtn" value="<?php echo $data['availableDateID']?>">삭제</button></td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
    $(document).ready(function () {
        set_date_picker();
        insert_form();
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
        $("#datepicker").datepicker(
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
        $("#datepicker").datepicker('setDate',
            '<?php echo $set_date_picker ?>'
        );
    }

    function insert_form() {
        let available = $('input[name=availableDate]');
        let notAvailable = $('input[name=notAvailableDate]');
        available.on('input', function () {
            notAvailable.prop('disabled', 'true');
        });
        notAvailable.on('input', function () {
            available.prop('disabled', 'true');
        });
        $('input[name=employeeName]').on('input', function () {
            set_validity($(this), 'employee');
        });
        $('.btn-submit').on('click', function () {
            if (available.val() || notAvailable.val()) {
                available.prop('required', false);
            }
            else {
                available.prop('required', true);
            }
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

    function switch_click() {
        $('.form-switch i').on('mouseup', function () {
            setTimeout(toggle_filter($(this)), 100);
        });
    }

    $('.availableDelBtn').on('click', function () {
        let btn = $(this);
        if (confirm('정말 삭제하시겠습니까?')) {
            $.ajax({
                type: "POST",
                method: "POST",
                url: ajaxURL,
                data: {action: 'deleteAvailable', availableDateID: btn.val()},
                dataType: "text",
                success: function (data) {
                    alert(data);
                    btn.closest('tr').slideUp();
                }
            });
        }
    });
</script>