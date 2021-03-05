// let ajaxURL = "http://pachuling.cafe24.com/application/ajax/ajax.php";
let ajaxURL = domain+"/application/ajax/ajax.php";
let callForm = $('#callForm');
let arr = [];
let i = arr.length;
let count = 0;

//임금 계산 함수
function getSalary(start_time, end_time, date) {
    let salary = $('#salaryInfo');
    let price_table = {
        'holiday': {
            'night': {//주말야간
                5: 60000,
                6: 69000,
                7: 75000,
                8: 81000,
                9: 88000,
                10: 95000,
                11: 102000,
                12: 111000
            },
            'day': {//주말주간
                5: 50000,
                6: 59000,
                7: 65000,
                8: 71000,
                9: 78000,
                10: 85000,
                11: 92000,
                12: 101000
            }
        },
        'weekday': {
            'night': {//평일야간
                5: 55000,
                6: 64000,
                7: 70000,
                8: 76000,
                9: 83000,
                10: 90000,
                11: 97000,
                12: 106000
            },
            'day': {//평일주간
                5: 45000,
                6: 54000,
                7: 60000,
                8: 66000,
                9: 73000,
                10: 80000,
                11: 87000,
                12: 96000
            }
        }
    };
    let time = end_time - start_time;
    $.ajax({
        type: "POST",
        method: "POST",
        url: ajaxURL,
        data: {action:'check_holiday',date:date},
        dataType: "text",
        async: false,
        success: function (data) {
            let holiday = JSON.parse(data).holiday;
            let money = 0;
            if(holiday){
                if(end_time>=24){money = price_table.holiday.night[time];}//주말야간
                else{money = price_table.holiday.day[time];}//주말주간
                $('input.workDate').css('color','red');
            }
            else{
                if(end_time >=24){money  = price_table.weekday.night[time];}//평일야간
                else{money  = price_table.weekday.day[time];}//평일주간
                $('input.workDate').css('color','black');
            }
            salary.html("근무시간: " + time + " 시간 / 일당: " + number_format(parseInt(money)) + " 원");
            $('#salary').val(money);
        }
    });
}

//콜 취소 함수
function cancel() {
    event.stopPropagation();
    $.ajax({
        type: "POST",
        method: "POST",
        url: ajaxURL,
        data: $('#formCallCancel').serialize(),
        dataType: "text",
        success: function (data) {
            window.location.reload();
            $(this).closest('tr').css('background', 'red');
        }
    });
}

//고정 콜 함수
function fix(time) {
    console.log('fix');
    $('#formAction').val('fix');
    $.ajax({
        type: "POST",
        method: "POST",
        url: ajaxURL,
        dataType: "text",
        data: callForm.serialize(),
        async: false,
    }).success(function (data) {
        let dateArray = JSON.parse(data).dateArray;
        let fixID = parseInt(JSON.parse(data).fixID);
        $('#fixID').val(fixID);
        for (let date in dateArray) {
            myFix(dateArray[date]);
            // initiate(time,true,dateArray[date]);
        }
    });
}

//무료콜, 유료콜, 포인트 부족의 상태 확인
function myFix(date) {
    $('#workDate').val(date);
    $('#formAction').val('initiate');
    $.ajax({
        type: "POST",
        method: "POST",
        url: ajaxURL,
        data: $('#callForm').serialize(),
        dataType: "text",
        async: false,
        success: function (data) {
            let callType = JSON.parse(data).callType;
            if (callType === 'free') {
                freeCall(data);
            }
            if (callType === 'charged') {
                chargedCall(data);
            }
            else if (callType === 'pointExceed') {
                alert('포인트가 부족합니다. 충전해주세요');
                window.location.reload();
            }
            recursive();
        }
    });
    count++;
}

//고정 콜 함수 내 반복 함수
function recursive() {
    if (count < i) {
        $('#startTime').val(startHour.val() + ":" + $('#startMin').val()); //HH:MM
        $('#endTime').val(endHour.val() + ":" + $('#endMin').val()); //HH:MM
        $('#formAction').val('initiate');
        $.ajax({
            type: "POST",
            method: "POST",
            url: ajaxURL,
            data: $('#callForm').serialize(),
            dataType: "text",
            success: function (data) {
                let callType = JSON.parse(data).callType;
                if (callType === 'free') {
                    freeCall(data);
                }
                if (callType === 'charged') {
                    chargedCall(data);
                }
                else if (callType === 'pointExceed') {
                    alert('포인트가 부족합니다. 충전해주세요');
                    window.location.reload();
                }
                recursive();
            }
        });
        count++;
    }
}

//HTML 리턴에 따른 테이블 출력
function getHTML(tableElement, action, id) {
    $.ajax({
        type: "POST",
        method: "POST",
        url: ajaxURL,
        data: {action: action, id: id},
        dataType: "text",
        success: function (data) {
            tableElement.html(JSON.parse(data));
        }
    });
}