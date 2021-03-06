<form action="" id="callForm" method="post">
    <input type="hidden" name="action" id="formAction">
    <input type="hidden" name="companyID" id="companyID"
value="<?php if (isset($this->companyID)) echo $this->companyID ?>">
    <input type="hidden" name="employeeID" class="employee" id="employeeID">
    <input type="hidden" name="startTime" class="startTime" id="startTime">
    <input type="hidden" name="endTime" class="endTime" id="endTime">
    <input type="hidden" name="salary" id="salary">
    <input type="hidden" name="price" id="callPrice">
    <input type="hidden" name="point" id="callPoint">
    <input type="hidden" name="workDate" id="workDate" class="workDate" value="<?php echo _TOMORROW ?>">
    <input type="hidden" name="endDate" id="endDate" class="endDate">
    <input type="hidden" name="fixID" id="fixID">
    <input type="hidden" name="commission">
    <div class="table" style="padding-bottom: 50px">

        <?php if ($this->param->page_type != 'ceo'): ?>
            <!--구직자이름, 상호명-->
            <div class="">
                <div class="callForm-flex pl between">
                    <div class="callForm-company">
                        <label for="">상호명</label>
                        <input type="text" class="input-companyName" list="companyList" name="companyName" id="companyName"
                            placeholder="배정 요청한 거래처를 입력하세요">
                        <datalist id="companyList" class="input-field">
                            <?php foreach ($companyList as $key => $data): ?>
                                <option value="<?php echo $data['companyName'] ?>">
                                    <?php echo "(" . $this->model->joinType($data['companyID'], 'kor') . ")"; ?>
                                </option>
                            <?php endforeach ?>
                        </datalist>
                    </div>
                    <div class="callForm-company">
                        <label for="">성명</label>
                        <input type="text" list="employeeList" name="employeeName" class="employee" id="employeeName"
                            placeholder="배정할 구직자을 입력하세요 (생략가능)">
                        <datalist id="employeeList" class="input-field">
                            <?php foreach ($employeeList as $key => $data): ?>
                                <option value="<?php echo $data['employeeName'] ?>">
                                    <?php if ($data['bookmark'] == 1) echo "(북마크)"; ?>
                                </option>
                            <?php endforeach ?>
                        </datalist>
                    </div>
                    <div class="" id="errorMsg" style="display: none;">
                        <h2 style="margin: 0 10px;"></h2>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!--근무요일-->
        <div class="callForm-flex pl" id="workDay" style="display: none;">
            <div class="">
                <label for="">근무요일</label>
                <table style="width: 700px">
                    <tr>
                        <td><input type="checkbox" name="dow[]" value="monday"></td>
                        <td><input type="checkbox" name="dow[]" value="tuesday"></td>
                        <td><input type="checkbox" name="dow[]" value="wednesday"></td>
                        <td><input type="checkbox" name="dow[]" value="thursday"></td>
                        <td><input type="checkbox" name="dow[]" value="friday"></td>
                        <td><input type="checkbox" name="dow[]" value="saturday"></td>
                        <td><input type="checkbox" name="dow[]" value="sunday"></td>
                    </tr>
                    <tr>
                        <td>월</td>
                        <td>화</td>
                        <td>수</td>
                        <td>목</td>
                        <td>금</td>
                        <td>토</td>
                        <td>일</td>
                    </tr>
                </table>
            </div>
        </div>
        <!--근무기간-->
        <div class="callForm-flex pl npd">
            <div class="callForm-term">
                <label for="">근무기간</label>
                <input type="date" class="workDate" value="<?php echo _TOMORROW ?>">
            </div>
            <div class="callForm-term input-endDate fixable td td-3" style="display: none;">
                <label for="" class="endDateLabel">근무종료일</label>
                <span>~</span>
                <input type="date" class="endDate">
            </div>
        </div>
        <div class="btn-wrap">
            <button class="fixable btn btn-option fix-auto-date half-month" type="button" style="display: none" value="15">15일</button>
            <button class="fixable btn btn-option fix-auto-date one-month" type="button" style="display: none" value="1">1개월</button>
            <button class="fixable btn btn-option fix-auto-date two-month" type="button" style="display: none" value="2">2개월</button>
        </div>
        <div class="btn-wrap">
            <button class="basic btn btn-option selected" type="button" id="tomorrow">
                내일(<?php echo unserialize(DAYOFWEEK)[date('w', strtotime(_TOMORROW))] ?>)
            </button>
            <button class="basic btn btn-option" type="button" id="dayAfterTomorrow">
                모레(<?php echo unserialize(DAYOFWEEK)[date('w', strtotime("+2 day"))] ?>)
            </button>
        </div>
        <!--근무시간-->
        <div class="callForm-flex pl npd">
            <div class="callForm-time" id="">
                <label for="">근무시간</label>
                <select id="startHour" class="time hour" form="callForm" required>
                    <option value="" selected disabled hidden>근무 시작 시간</option>
                    <?php for ($i = 1; $i < 25; $i++): ?>
                        <option class="startOption" value="<?php echo $i ?>">
                            <?php echo $this->getTime($i); ?>
                        </option>
                    <?php endfor; ?>
                </select>
                <select class="time minute" id="startMin" form="callForm" required>
                    <option value="00">00분</option>
                    <option value="30">30분</option>
                </select>
                <select id="endHour" class="time hour" form="callForm" required>
                    <option value="" selected disabled hidden>근무 종료 시간</option>
                    <?php for ($i = 1; $i < 37; $i++): ?>
                        <option class="endOption" value="<?php echo $i ?>">
                            <?php echo $this->getTime($i); ?>
                        </option>
                    <?php endfor; ?>
                </select>
                <select class="time minute" id="endMin" form="callForm" required>
                    <option value="00">00분</option>
                    <option value="30">30분</option>
                </select>
            </div>
        </div>
        <div class="btn-wrap">
            <button type="button" class="btn btn-option timeSelect selected" id="morningBtn">오전</button>
            <button type="button" class="btn btn-option timeSelect" id="afternoonBtn">오후</button>
            <button type="button" class="btn btn-option timeSelect" id="allDayBtn">종일</button>
        </div>
        <div class="callForm-flex pl">
            <div class="">
                <label for="">임금</label>
                <p id="salaryInfo" style="display:inline-table;    width: 100%; padding: 0 10px; margin: 0;">근무시간을
                    선택해주세요</p>
            </div>
        </div>
        <!--업종-->
        <div class="callForm-flex pl">
            <div class="callForm-jobs">
                <label for="">업종</label>
                <select class="selector-work-field" name="workField"
                        style="background: #fff; height: 40px; margin-right: 10px; font-size: 16px; padding: 3px;"
                        id="workField" form="callForm" required>
                    <?php foreach ($this->get_workfield_list() as $data): ?>
                        <option value="<?php echo $data['workField']?>">
                            <?php echo $data['workField']?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>
        <div class="container-jobs btn-wrap">
            <button type="button" class="btn btn-option btn-work-field wash">설거지</button>
            <button type="button" class="btn btn-option btn-work-field kitchen selected ">주방보조</button>
            <button type="button" class="btn btn-option btn-work-field hall">홀서빙</button>
        </div>
        <!--월급-->
        <div class="callForm-flex pl monthly" style="display: none;">
            <div class="">
                <label for="">월급</label>
                <input type="number" name="monthlySalary" id="monthlySalary">
            </div>
            <div class="">
                <label for="">수수료 비율</label>
                <input type="number" name="percentage" id="percentage">
            </div>
            <div class="">
                <label for="">수수료</label>
                <input type="number" name="commission" id="commission">
            </div>
        </div>
        <!--기타 요청 사항-->
        <div class="callForm-flex pl">
            <div class="callForm-etc">
                <label for="">기타 요청 사항</label>
                <textarea name="detail" id="detail" cols="30" rows="6">
                </textarea>
            </div>
        </div>
        <!--콜 보내기 버튼-->
        <div class="btn-group al_r" style="position: fixed;bottom: 0;width: 100%;max-width: 500px;">
            <h1 class="callPrice"></h1>
            <button id="btnSendCall" class="btn btn-insert callBtn" type="button">콜 신청하기</button>
            <?php if ($this->param->page_type != 'ceo'): ?>
                <input type="submit" id="submitFixedCallBtn" class="btn btn-insert callBtn fixBtn" type="button" style="display: none" value="고정 콜 만들기">
                <input type="submit" id="submitMonthlyCallBtn" class="btn btn-insert callBtn fixBtn" type="button" style="display: none" value="월급제 만들기">
                <!--              <button id="submitFixedCallBtn" class="btn btn-insert callBtn fixBtn" type="button" style="display: none">고정 콜 만들기</button>-->
                <!--              <button id="submitMonthlyCallBtn" class="btn btn-insert callBtn fixBtn" type="button" style="display: none">월급제 만들기</button>-->
            <?php endif; ?>
        </div>
</form>