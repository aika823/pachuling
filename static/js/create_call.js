let start_hour = $('#startHour');
let end_hour = $('#endHour');

let start_time = $('#startTime');
let end_time = $('#endTime');

let first_start_hour = 10;//근무 시작 시간 기본값
let first_end_hour = 15;//근무 종료 시간 기본값

let work_field = $('#workField');
let work_date = $('#workDate');

let detail = $('#detail');

$(document).ready(function () {//다른 js 파일 모두 불러온 뒤 함수 내용이 실행됨
    reset_call_form();
    if (pageType !== 'ceo') {
        input_company();
        input_employee();
    }
    input_work_date();
    input_work_time();
    input_work_field();
    send_call();
    send_fixed_call();
});

function reset_call_form() {
    start_time.val(first_start_hour + ":00");
    end_time.val(first_end_hour + ":00");
    start_hour.val(first_start_hour);
    end_hour.val(first_end_hour);

    work_field.val('주방보조');
    detail.val('');

    $('input.workDate').prop('min', today);
    $('input.endDate').prop('min', today);

    $('.workDate').val(tomorrow);
    $('.employee').val(null);

    map_time_to_btn(first_start_hour, first_end_hour);
    getSalary(first_start_hour, first_end_hour, tomorrow);
    if (pageType !== 'ceo') {
        $('#callForm input:not(.input-companyName), #callForm select, #callForm textarea, #callForm button').prop('disabled', true);
    }
}

function input_company() {
    $('#companyName').on('input', function () {
        let id_element = $('#companyID');
        let name_element = $(this);
        match_company_id(id_element, name_element);
    });
}

function match_company_id(id_element, name_element) {
    let company_name = name_element.val();
    $.ajax({
        type: "POST",
        method: "POST",
        url: ajaxURL,
        data: {action: 'get_company_id', name: company_name},
        dataType: "text",
        async: true,
        success: function (data) {
            let error = JSON.parse(data).error;
            if (error) {
                console.log(error);
                $('#callForm input:not(.input-companyName), #callForm select, #callForm textarea, #callForm button').prop('disabled', true);
                $('#errorMsg h2').html(error);
                $('#errorMsg').show();
                id_element.val(null);
            }
            else {
                $('#callForm input:not(.input-companyName), #callForm select, #callForm textarea, #callForm button').prop('disabled', false);
                id_element.val(data);
                reset_call_form();
                get_join_type(name_element, work_date.val());
            }
        },
    });
}

function get_join_type(name_element, date) {
    $.ajax({
        type: "POST",
        method: "POST",
        url: ajaxURL,
        data: {action: 'get_join_type', name: name_element.val(), date: date},
        dataType: "text",
        async: true,
        success: function (data) {
            let join_type = JSON.parse(data).joinType;
            let size = JSON.parse(data).size;
            let end_date = JSON.parse(data).endDate;
            let call_type = JSON.parse(data).callType;
            let call_price = JSON.parse(data).callPrice;
            let error = JSON.parse(data).error;

            let total = JSON.parse(data).total;

            console.log(total);
            console.log(call_price);
            console.log(join_type);
            console.log(call_type);

            if (error) {
                console.log(error);
                $('#callForm input:not(.input-companyName), #callForm select, #callForm textarea, #callForm button').prop('disabled', true);
                $('#errorMsg h2').html(error);
                $('#errorMsg').show();
            }
            else {
                $('#callForm input:not(.input-companyName), #callForm select, #callForm textarea, #callForm button').prop('disabled', false);
                $('#errorMsg h2').html(join_type + " (" + size + "개 가입)");
                $('#errorMsg').show();
                if (join_type === '구좌') {
                    $('input.workDate').prop('max', end_date);
                    $('input.endDate').prop('max', end_date);
                }
                if (call_price) {
                    $('#btnSendCall').html("콜 신청하기 <br>" + "콜비 : " + call_price + "원");
                    $('#callPrice').val(call_price);
                }
                else {
                    $('#btnSendCall').html("콜 신청하기");
                    $('#callPrice').val(null);
                }
            }
        }
    });
}

function input_work_date() {

    let workDate = $('.workDate');
    let endDate = $('.endDate');
    let btn1 = $('#tomorrow');
    let btn2 = $('#dayAfterTomorrow');

    workDate.on('input', function () {
        $('#callForm #workDate').val($(this).val());
        endDate.prop('min',$(this).val());

        if($('#fixCallBtn').hasClass('selected')){
            $('.fix-auto-date').removeClass('selected');
            $('.fix-auto-date.half-month').addClass('selected');
            endDate.val(get_next_date(new Date($(this).val()),14));
        }
        else if($('#monthlyCallBtn').hasClass('selected')){
            $('.fix-auto-date').removeClass('selected');
            $('.fix-auto-date.two-month').addClass('selected');
            endDate.val(get_next_month(new Date($(this).val()),2));
        }
        let date = $(this).val();
        if (date === tomorrow) {//내일 날짜 선택 시
            btn1.addClass('selected');
            btn2.removeClass('selected');
        }
        else if (date === dayaftertomorrow) {//모레 날짜 선택 시
            btn1.removeClass('selected');
            btn2.addClass('selected');
        }
        else {//내일, 모레 아닌 날짜 선택 시
            btn1.removeClass('selected');
            btn2.removeClass('selected');
        }
        getSalary(start_hour.val(), end_hour.val(), $(this).val());
        if (pageType !== 'ceo') {
            let name_element = $('#companyName');
            get_join_type(name_element, $(this).val());//match 된 companyID 사용
        }
    });

    $('.fix-auto-date').on('click',function () {
        $(this).closest('div').find('.fix-auto-date').removeClass('selected');
        $(this).addClass('selected');
        if(parseInt($(this).val()) === 15){
            $('.endDate').val(get_next_date(new Date(workDate.val()) , parseInt($(this).val()) ));
        }
        else{
            $('.endDate').val(get_next_month(new Date(workDate.val()) , parseInt($(this).val()) ));
        }
    });

    endDate.on('input', function () {
        $('#callForm #endDate').val($(this).val());
        if($(this).val() === get_next_date(new Date($('#workDate').val()),14)){
            $('.fix-auto-date').removeClass('selected');
            $('.fix-auto-date.half-month').addClass('selected');
        }
        else if($(this).val() === get_next_month(new Date($('#workDate').val()),1)){
            $('.fix-auto-date').removeClass('selected');
            $('.fix-auto-date.one-month').addClass('selected');
        }
        else if($(this).val() === get_next_month(new Date($('#workDate').val()),2)){
            $('.fix-auto-date').removeClass('selected');
            $('.fix-auto-date.two-month').addClass('selected');
        }
        else{
            $('.fix-auto-date').removeClass('selected');
        }
    });

    btn1.on('click', function () {
        workDate.val(tomorrow);
        workDate.trigger('input');
    });
    btn2.on('click', function () {
        workDate.val(dayaftertomorrow);
        workDate.trigger('input');
    });
}

function limit_end_time(starth, endOption) {
    for (let i = 0; i < 50; i++) {
        if ((i < starth + 4) || (i > starth + 11)) {
            endOption.eq(i).css('display', 'none');
        }
        else {
            endOption.eq(i).css('display', 'block');
        }
    }
}

function map_time_to_btn(starth, endh) {
    if (endh - starth >= 10) {//종일
        $('#allDayBtn').addClass('selected');
        $('#allDayBtn').closest('div').find('.btn-option:not(#allDayBtn)').removeClass('selected');
    }
    else {
        if (starth < 12) {//오전
            $('#morningBtn').addClass('selected');
            $('#morningBtn').closest('div').find('.btn-option:not(#morningBtn)').removeClass('selected');
        }
        else {//종일
            $('#afternoonBtn').addClass('selected');
            $('#afternoonBtn').closest('div').find('.btn-option:not(#afternoonBtn)').removeClass('selected');
        }
    }
}

function input_work_time() {
    //front input values
    let start = $('#startHour');
    let end = $('#endHour');
    let minute = $('.minute');
    let workDate = $('.workDate');
    let endOption = $('.endOption');

    //real input values
    let start_time = $('#startTime');
    let end_time = $('#endTime');

    start.on('input', function () {
        let starth = parseInt($(this).val());
        let endh = starth + 5;
        end.val(endh);
        start_time.val(starth + ':' + $('#startMin').val());
        end_time.val(endh + ':' + $('#startMin').val());
        limit_end_time(starth, endOption);
        map_time_to_btn(starth, endh);
        getSalary(start.val(), end.val(), workDate.val());
    });
    end.on('input', function () {
        let starth = parseInt(start.val());
        let endh = parseInt($(this).val());
        start_time.val(starth + ':' + $('#endMin').val());
        end_time.val(endh + ':' + $('#endMin').val());
        limit_end_time(starth, endOption);
        map_time_to_btn(starth, endh);
        getSalary(start.val(), end.val(), workDate.val());
    });
    minute.on('input', function () {
        minute.val($(this).val());
        start_time.val(start.val() + ':' + minute.val());
        end_time.val(end.val() + ':' + minute.val());
    });

    $('#morningBtn').on('click', function () {
        start_hour.val('10');
        end_hour.val('15');
        minute.val('00');
        start.trigger('input');
        minute.trigger('input');
    });
    $('#afternoonBtn').on('click', function () {
        start_hour.val('18');
        end_hour.val('23');
        minute.val('00');
        start.trigger('input');
        minute.trigger('input');
    });
    $('#allDayBtn').on('click', function () {
        start_hour.val('10');
        end_hour.val('21');
        minute.val('00');
        end.trigger('input');
        minute.trigger('input');
    });
}

function input_work_field() {
    work_field.on('input', function () {
        let value = $(this).val();
        if (value === '설거지') {
            let btn = $('.btn-option.wash');
            btn.closest('div').find('.btn-option').removeClass('selected');
            btn.addClass('selected');
        }
        else if (value === '주방보조') {
            let btn = $('.btn-option.kitchen');
            btn.closest('div').find('.btn-option').removeClass('selected');
            btn.addClass('selected');
        }
        else if (value === '홀서빙') {
            let btn = $('.btn-option.hall');
            btn.closest('div').find('.btn-option').removeClass('selected');
            btn.addClass('selected');
        }
        else {
            let btn = $('.btn-option.wash');
            btn.closest('div').find('.btn-option').removeClass('selected');
        }
    });
    $('.btn-work-field').on('click', function () {
        work_field.val($(this).text());
    });
}

function input_employee() {
    $('#employeeName').on('input', function () {
        $('#formAction').val('getEmployeeID');
        $.ajax({
            type: "POST",
            url: ajaxURL,
            method: "POST",
            data: $('#callForm').serialize(),
            dataType: "text",
            success: function (data) {
                $('#employeeID').val(data);
            }
        });
        set_validity($(this), 'employee');
    });
}

function send_call() {

    $('#btnSendCall').on('click', function () {

        if (confirm("콜을 신청하시겠습니까???")) {
            console.log('콜을 신청하시겠습니까? - 확인 버튼 누름');
            $('#formAction').val('call');
            console.log('폼 액션 value call로 설정');
            $.ajax({
                type: "POST",
                method: "POST",
                url: ajaxURL,
                data: $('#callForm').serialize(),
                dataType: "text",
                async: false,
                success: function (data) {
                    console.log(JSON.parse(data).post);
                    console.log(JSON.parse(data).join_type);
                    console.log(JSON.parse(data).salary)
                    alert('콜을 보냈습니다!');
                    if (pageType !== 'call') {
                        //window.location.reload();
                    }
                }
            })
        }
        else {
            alert("콜을 취소했습니다.");
            if (pageType !== 'call') {
                //window.location.reload();
            }
        }
    });
}

function send_fixed_call() {

    $('.fixBtn').on('click', function () {

        if (confirm('고정 콜을 만드시겠습니까?')) {
            $('#formAction').val('fix');
            $.ajax({
                type: "POST",
                method: "POST",
                url: ajaxURL,
                data: $('#callForm').serialize(),
                dataType: "text",
                async: false,
                success: function (data) {
                    let date_array = JSON.parse(data).dateArray;
                    if(date_array){
                        let string = '아래 날짜의 콜 '+date_array.length+'개 를  생성했습니다.\n';
                        date_array.forEach(function (element) {
                            string += element + '\n';
                        });
                        alert(string);
                        window.location.reload();
                    }
                    else{
                        alert('근무기간이 정상적으로 입력되지 않았습니다.');
                    }
                }
            });
        }
        else {
            alert('고정 콜 생성을 취소했습니다.');
        }
    });
}

$('#percentage').on('input', function () {
    $('#commission').val($('#percentage').val() * 0.01 * $('#monthlySalary').val());
    $('#callForm input[name=commission]').val($('#percentage').val() * 0.01 * $('#monthlySalary').val());
});
$('#monthlySalary').on('input', function () {
    $('#commission').val($('#percentage').val() * 0.01 * $('#monthlySalary').val());
    $('#callForm input[name=commission]').val($('#percentage').val() * 0.01 * $('#monthlySalary').val());
});
$('#manualCallBtn').on('click', function () {
    $('.callBtn').hide();
    $('#btnSendCall').show();
    $('.basic').slideDown();
    $('.monthly').slideUp();
    $('.fixable').slideUp();
    $('.endDate').val(null);
});
$('#fixCallBtn').on('click', function () {
    $('.callBtn').hide();
    $('#submitFixedCallBtn').show();
    $('.input-endDate').css('display', 'inline');
    $('.basic').slideUp();
    $('.monthly').slideUp();
    $('.fixable').slideDown();
    $('.fix-auto-date').removeClass('selected');
    $('.fix-auto-date.half-month').addClass('selected');
    $('.endDate').val(get_next_date(new Date($('#workDate').val()) , 14));
});
$('#monthlyCallBtn').on('click', function () {
    $('.callBtn').hide();
    $('#submitMonthlyCallBtn').show();
    $('.endDate').css('display', 'inline');
    $('.basic').slideUp();
    $('.fixable').slideDown();
    $('.monthly').slideDown();
    $('.fix-auto-date').removeClass('selected');
    $('.fix-auto-date.two-month').addClass('selected');
    $('.endDate').val(get_next_month(new Date($('#workDate').val()) , 2));
});
$('.btn-option').on('click', function () {
    $(this).closest('div').find('.btn-option').removeClass("selected");
    $(this).addClass('selected');
});