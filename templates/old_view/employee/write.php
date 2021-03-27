<?php
$current_employee_join =
    $this->model->getTable("SELECT * FROM `join_employee` WHERE `employeeID` = '{$this->employeeData['employeeID']}' AND `activated` = '1' ORDER BY `endDate` DESC")[0];
$availableDateArray =
    $this->model->getTable("
SELECT * FROM
`employee_available_date`
WHERE (`employeeID` = '{$this->employeeData['employeeID']}')
AND (
(`availableDate` > '{$current_employee_join['startDate']}' AND `availableDate` < '{$current_employee_join['endDate']}') OR
(`notAvailableDate` > '{$current_employee_join['startDate']}' AND `notAvailableDate` < '{$current_employee_join['endDate']}')
)
");
$workField_List = $this->get_workfield_list();
$address_List = $this->get_address_list();
?>


<div class="board-write auto-center">
    <div class="title-table">
        <h1>
            <?php
            echo "구직자 정보";
            if (isset ($this->employeeData)) echo " - " . $this->employeeData['employeeName'] . "(" . $this->employeeData['actCondition'] . ")";
            ?>
        </h1>
    </div>
    <div class="form-default">
        <form id="formInsertEmployee" action="" method="post">
            <fieldset>
                <input type="hidden" name="action"
                       value="<?php echo ($this->param->action == 'write') ? 'insert' : 'update' ?>">
                <input type="hidden" name="employeeID" value="<?php echo $this->employeeData['employeeID'] ?>">
                <div class="table">
                    <div class="tr">
                        <div class="td td-3">
                            <label for="">성명</label>
                            <input type="text" name="employeeName" size="20" required autofocus
                                   value="<?php echo $this->employeeData['employeeName']; ?>">
                        </div>
                        <div class="td td-3">
                            <label for="">성별</label>
                            <input type="text" list="sexList" name="sex" size="2" required autofocus
                                   value="<?php if (isset ($this->employeeData['sex'])) {
                                       echo $this->employeeData['sex'];
                                   } else echo "여" ?>">
                            <datalist id="sexList" class="input-field">
                                <option value="여"></option>
                                <option value="남"></option>
                            </datalist>
                        </div>
                        <div class="td td-3">
                            <label for="">생년월일</label>
                            <input type="date" name="birthDate" size="20" required autofocus
                                   style="font-size: 15px; line-height: 0.8; padding: 5px 10px;"
                                   value="<?php echo $this->employeeData['birthDate']; ?>">
                        </div>
                    </div>
                    <div class="duplicate" id="employeeNameDuplicate">이름을 입력 해 주세요</div>
                    <div class="tr">
                        <div class="td td-3">
                            <label for="">업종1</label>
                            <input type="text" list="workFieldList" name="workField1" size="20" required
                                   value="<?php echo $this->employeeData['workField1']; ?>">
                            <datalist id="workFieldList" class="input-field">
                                <?php foreach ($workField_List as $data): ?>
                                    <option value="<?php echo $data['workField']?>"></option>
                                <?php endforeach ?>
                            </datalist>
                        </div>
                        <div class="td td-3">
                            <label for="">업종2</label>
                            <input type="text" list="workFieldList" name="workField2" size="20"
                                   value="<?php echo $this->employeeData['workField2']; ?>">
                            <datalist id="workFieldList" class="input-field">
                                <?php foreach ($workField_List as $data): ?>
                                    <option value="<?php echo $data['workField'] ?>"></option>
                                <?php endforeach ?>
                            </datalist>
                        </div>
                        <div class="td td-3">
                            <label for="">업종3</label>
                            <input type="text" list="workFieldList" name="workField3" size="20"
                                   value="<?php echo $this->employeeData['workField3']; ?>">
                            <datalist id="workFieldList" class="input-field">
                                <?php foreach ($workField_List as $data): ?>
                                    <option value="<?php echo $data['workField'] ?>"></option>
                                <?php endforeach ?>
                            </datalist>
                        </div>
                        <div class="td td-3">
                            <label for="">전화번호</label>
                            <input type="text" name="employeePhoneNumber" size="20" required
                                   value="<?php echo $this->employeeData['employeePhoneNumber']; ?>">
                        </div>
                    </div>
                    <div class="tr">
                        <div class="td td-3">
                            <label for="">간단주소</label>
                            <input type="text" list="addressList" name="address" required
                                   value="<?php echo $this->employeeData['address']; ?>">
                            <datalist id="addressList">
                                <?php foreach ($this->address_List as $data): ?>
                                    <option value="<?php echo $data['address']?>"></option>
                                <?php endforeach ?>
                            </datalist>
                        </div>
                        <div class="td td-3">
                            <label for="">상세주소</label>
                            <input type="text" name="detailAddress" size="20"
                                   value="<?php echo $this->employeeData['detailAddress']; ?>">
                        </div>
                        <div class="td td-3">
                            <label for="">희망근무지</label>
                            <input type="text" list="addressList" name="workPlace"
                                   value="<?php echo $this->employeeData['workPlace']; ?>">
                            <datalist id="addressList">
                                <?php foreach ($this->address_List as $data): ?>
                                    <option value="<?php echo $data['address']?>"></option>
                                <?php endforeach ?>
                            </datalist>
                        </div>
                    </div>
                    <div class="tr">
                        <div class="td td-3">
                            <label for="">한국어</label>
                            <input type="text" list="languageList" name="language" size="2"
                                   value="<?php if (isset($this->employeeData['language'])) echo $this->employeeData['language']; else echo "상"; ?>">
                            <datalist id="languageList" class="input-field">
                                <option value="상"></option>
                                <option value="중"></option>
                                <option value="하"></option>
                            </datalist>
                        </div>
                        <div class="td td-3">
                            <label for="">점수</label>
                            <input type="text" name="grade" size="20"
                                   value="<?php if (isset($this->employeeData['grade'])) echo $this->employeeData['grade']; else echo "100"; ?>">
                        </div>
                    </div>
                    <div class="tr">
                        <div class="td td-4">
                            <label for="">비고</label>
                            <textarea class="textarea-detail" name="detail" required><?php echo $this->get_detail($this->employeeData,'employee');?></textarea>
                        </div>
                        <div class="td td-4" style="margin-top: 30px;">
                            <?php require_once 'employeeAvailableDayTable.php' ?>
                        </div>
                    </div>

                    <?php if ($this->employeeData['actCondition'] == "삭제됨") : ?>
                        <div class="tr">
                            <div class="td td-4">
                                <label for="">삭제비고</label>
                                <textarea
                                        name="deleteDetail"><?php echo $this->employeeData['deleteDetail']; ?></textarea>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (($this->param->action == 'view') && (sizeof($this->blackList) > 0)): ?>
                        <div class="tr">
                            <div class="td td-9">
                                <label for="">블랙</label>
                                <table>
                                    <thead>
                                    <tr>
                                        <th width="150">상호</th>
                                        <th width="150">종류</th>
                                        <th>사유</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($this->blackList as $data) { ?>
                                        <tr>
                                            <td>
                                                <?php echo $this->companyName($data['companyID']) ?>
                                            </td>
                                            <td>
                                                <?php echo ($data['ceoReg'] == 1) ? '오지마세요' : '안가요'; ?>
                                            </td>
                                            <td class="<?php echo $data['detail'] ? 'al_l' : 'al_c'?>">
                                                <?php echo $data['detail'] ? $data['detail'] : '-'; ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <tr>

                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (($this->param->action == 'view') && (sizeof($availableDateArray) > 0)): ?>
                        <div class="tr">
                            <div class="td td-9">
                                <label for="" style="max-width: 200px;">일 주세요 / 일 못가</label>
                                <table>
                                    <thead>
                                    <tr>
                                        <th width="150">날짜</th>
                                        <th width="150">종류</th>
                                        <th width="150">사유</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($availableDateArray as $value) { ?>
                                        <tr>
                                            <td>
                                                <?php echo $value['availableDate'] > 0 ? $value['availableDate'] : $value['notAvailableDate'] ?>
                                            </td>
                                            <td>
                                                <?php echo $value['availableDate'] > 0 ? "일 주세요" : "못가요"; ?>
                                            </td>
                                            <td class="<?php echo $value['detail'] ? 'al_l' : 'al_c'?>">
                                                <?php echo $value['detail'] ? $value['detail'] : '-'; ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <tr>

                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (!isset($this->param->idx)) : ?>
                    <div class="title-table">
                        <h1>가입 정보</h1>
                    </div>

                    <div class="table table-add-join" id="employeeAddJoinTable">
                        <div class="tr">
                            <div class="td td-3">
                                <label for="startDate">가입시작일</label>
                                <input type="date" id="startDate" name="startDate" required>
                            </div>
                            <div class="td td-3">
                                <label for="endDate">가입만기일</label>
                                <input type="date" id="endDate" name="endDate" required>
                            </div>
                            <div class="td td-3">
                                <button type="button" class="btn btn-option" onclick="auto_insert_employee_join('today')"
                                        style="width: 100px; margin: 23px 0;">오늘부터
                                </button>
                                <?php if ($this->param->action != 'write'): ?>
                                    <button type="button" class="btn btn-option"
                                            onclick="auto_insert_employee_join('extend')"
                                            style="width: 100px;">가입연장
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="tr">
                            <div class="td td-3">
                                <label for="price">가입금액</label>
                                <input type="number" id="price" name="price" required>
                            </div>
                            <div class="td td-3">
                                <label for="joinDetail">가입비고</label>
                                <textarea name="joinDetail">내용: &#10;작성자: </textarea>
                            </div>
                            <div class="td td-3">
                                <label for="">수금
                                    <input type="checkbox" id="paid" name="paid" value="1" style="margin-left: 16px;">
                                </label>
                                <input type="text" id="paid" name="receiver" value="지명희" required>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="btn-group al_r">
                    <a class="btn btn-default" href='<?php echo $this->param->get_page ?>'>취소</a>
                    <button class="btn btn-<?php echo ($this->param->action == 'write') ? 'insert' : 'submit' ?>"
                            type="submit"><?php echo ($this->param->action == 'write') ? '추가' : '수정' ?></button>
                </div>
            </fieldset>
        </form>
    </div>
</div>
<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
    check_duplicate_employee();
    check_day_box();

    function check_duplicate_employee() {
        let nameInput = $('#formInsertEmployee input[name=employeeName]');
        if (nameInput.val() === null) {
            $('#employeeNameDuplicate').html('이름을 입력 해 주세요');
        }
        else {
            nameInput.on('input', function () {
                let employeeName = $(this).val();
                $.ajax({
                    type: "POST",
                    method: "POST",
                    url: ajaxURL,
                    data: {action: 'checkDuplicate', table: 'employee', name: employeeName},
                    dataType: "text",
                    success: function (data) {
                        let show = $('#employeeNameDuplicate');
                        let list = JSON.parse(data).list;
                        let msg = JSON.parse(data).msg;
                        let match = JSON.parse(data).match;
                        let allInput = $('#formInsertEmployee input,textarea');
                        let employeeName = $('#formInsertEmployee input[name=employeeName]');
                        if (list) {
                            show.html("유사 : " + list);
                            if (match) {
                                show.html("중복: " + match + " - 다른 이름을 입력 해 주세요");
                                allInput.prop('disabled', true);
                                employeeName.prop('disabled', false);
                            }
                            else {
                                allInput.prop('disabled', false);
                                employeeName.prop('disabled', false);
                            }
                        }
                        else {
                            show.html(msg);
                            allInput.prop('disabled', false);
                        }
                    }
                });
            });
        }
    }

    function check_day_box() {
        $('.day').on('change', function () {
            let day = $(this).attr('class').split(' ')[2];
            let ab = $(this).attr('class').split(' ')[1];
            if (this.checked) {
                if (ab === 'bn') {
                    $('.ad' + '.' + day).prop('checked', false);
                    if ($('.an' + '.' + day).is(":checked")) {
                        $("input[name=" + day + "]").val("반반");
                    }
                    else {
                        $("input[name=" + day + "]").val("오전");
                    }
                }
                if (ab === 'an') {
                    $('.ad' + '.' + day).prop('checked', false);
                    if ($('.bn' + '.' + day).is(":checked")) {
                        $("input[name=" + day + "]").val("반반");
                    }
                    else {
                        $("input[name=" + day + "]").val("오후");
                    }
                }
                if (ab === 'ad') {
                    $('.' + day).prop('checked', false);
                    $(this).prop('checked', true);
                    $("input[name=" + day + "]").val("종일");
                }
            }
            else {
                if (ab === 'bn') {
                    if ($('.an' + '.' + day).is(":checked")) {
                        $("input[name=" + day + "]").val("오후");
                    }
                    else {
                        $("input[name=" + day + "]").val('null');
                    }
                }
                if (ab === 'an') {
                    if ($('.bn' + '.' + day).is(":checked")) {
                        $("input[name=" + day + "]").val("오전");
                    }
                    else {
                        $("input[name=" + day + "]").val('null');
                    }
                }
                if (ab === 'ad') {
                    $(this).prop('checked', false);
                    $("input[name=" + day + "]").val("null");
                }
            }
        });
    }

    $('.btn-insert').on('click', function () {
        if ($('.day:checked').length === 0) {
            $('.day').prop('required', true);
        }
        else {
            $('.day').prop('required', false);
        }
    });
</script>