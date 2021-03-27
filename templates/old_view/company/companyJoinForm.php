<div class="title-table">
    <h1 class="table_title" style="width: 140px;"><?php echo ($this->param->action == 'write') ? '가입 정보' : '가입 추가'; ?></h1>
    <div class="btn-group">
        <button type="button" class="btn btn-option btn-select-join-type" value="gujwa" id="btnGujwa">구좌</button>
        <button type="button" class="btn btn-option btn-select-join-type" value="deposit">보증금</button>
        <button type="button" class="btn btn-option btn-select-join-type" value="point">포인트</button>
    </div>
</div>


<?php if ($this->param->action == 'view'): ?>
<div class="form-style-1">
    <form action="" method="post" id="formInsertCompanyJoin">
        <input type="hidden" name="action" value="new_insert">
      <?php endif; ?>
        <fieldList id="companyAddJoin">

        </fieldList>
      <?php if ($this->param->action == 'view'): ?>
        <div class="btn-group al_r">
            <button type="submit" class="btn btn-insert" id="btnAddCompanyJoin">가입 추가</button>
        </div>
    </form>
</div>
<?php endif; ?>


<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
    $(document).ready(function () {
        console.log('test');
        check_duplicate_company();
        check_ceo_name();
        type_toggle();
        $('#btnGujwa').click();
    });

    function check_duplicate_company() {
        let nameInput = $('#formInsertCompany input[name=companyName]');
        if (nameInput.val() === null) {
            $('#companyNameDuplicate').html('상호명을 입력 해 주세요');
        }
        else {
            nameInput.on('input', function () {
                let companyname = $(this).val();
                $.ajax({
                    type: "POST",
                    method: "POST",
                    url: ajaxURL,
                    data: {action: 'checkDuplicate', table: 'company', name: companyname},
                    dataType: "text",
                    async: true,
                    success: function (data) {
                        let show = $('#companyNameDuplicate');
                        let list = JSON.parse(data).list;
                        let msg = JSON.parse(data).msg;
                        let match = JSON.parse(data).match;
                        let allInput = $('#formInsertCompany input,textarea');
                        let companyName = $('#formInsertCompany input[name=companyName]');
                        if (list) {
                            show.html("유사 : " + list);
                            if (match) {
                                show.html("중복 : " + match + " - 다른 이름을 입력 해 주세요");
                                allInput.prop('disabled', true);
                                companyName.prop('disabled', false);
                            }
                            else {
                                allInput.prop('disabled', false);
                                companyName.prop('disabled', false);
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

    function check_ceo_name() {
        let form = $('#formInsertCompany input[name=ceoName]');
        let ceoPhoneNumber = $('#formInsertCompany input[name=ceoPhoneNumber]');
        let ceoID = $('#formInsertCompany input[name=ceoID]');
        form.on('input', function () {
            let ceoName = $(this).val();
            $.ajax({
                type: "POST",
                method: "POST",
                url: ajaxURL,
                data: {action: 'matchCeo', name: ceoName},
                dataType: "text",
                async: true,
                success: function (data) {
                    if (data) {
                        let number = JSON.parse(data).ceoPhoneNumber;
                        let id = JSON.parse(data).ceoID;
                        ceoPhoneNumber.val(number);
                        if (pageAction !== 'view') {
                            ceoPhoneNumber.prop('disabled', true);
                        }
                        ceoID.val(id);
                    }
                    else {
                        ceoPhoneNumber.val('010-');
                        ceoPhoneNumber.prop('disabled', false);
                        ceoID.val(null);
                    }
                }
            });
        });
    }

    function type_toggle() {
        $(document).on('click', '.btn-select-join-type', function () {
            console.log('test');
            let type = $(this).val();
            $.ajax({
                type: "POST",
                method: "POST",
                url: ajaxURL,
                data: {action: 'getCompanyJoinForm', type: type},
                dataType: "text",
                async: true,
                success: function (data) {
                    $('#companyAddJoin').html(data);
                    auto_insert();
                }
            });
        });
    }

    function auto_insert() {
        let start = $('.table-add-join input[name=startDate]');
        let endDate = $('.table-add-join input[name=endDate]');
        let price = $('#companyAddJoinTable_gujwa input[name=price]');
        start.on('change', function () {
            let date = new Date(start.val());
            endDate.val(get_next_month(date, 6));
            price.val('150000');
        });
        $('#btn6Month').on('click', function () {
            let date;
            if (start.val()) {
                date = new Date(start.val());
            }
            else {
                start.val(today);
                date = now;
            }
            endDate.val(get_next_month(date, 6));
            price.val('150000');
        });
        $('#btn1Year').on('click', function () {
            let date;
            if (start.val()) {
                date = new Date(start.val());
            }
            else {
                start.val(today);
                date = now;
            }
            endDate.val(get_next_month(date, 12));
            price.val('250000');
        });
    }
</script>