//날짜 데이터 수정
let now = new Date();
let y = now.getFullYear();
let m = now.getMonth();
let d = now.getDate();
let today = dateFormat(now);

function get_next_month(date, num) {
    if (date.getMonth() === (12-num)) {//12월
        nextMon = new Date(date.getFullYear() + 1, 0, date.getDate());
    }
    else {//나머지
        nextMon = new Date(date.getFullYear(), date.getMonth() + num, date.getDate());
    }
    return dateFormat(nextMon);
}
function get_next_date(date, num){
    return dateFormat(new Date(date.getTime() +  (num) * 24 * 60 * 60 * 1000));
}

let tomorrow = dateFormat(new Date(now.getTime() + 24 * 60 * 60 * 1000));
let dayaftertomorrow = dateFormat(new Date(now.getTime() + 2 * 24 * 60 * 60 * 1000));
let thisMonFirstDay = dateFormat(new Date(y, m, 1));
let thisMonLastDay = dateFormat(new Date(y, m + 1, 0));

//js 날짜 -> YYYY-mm-dd 수정 함수
function dateFormat(date) {
    let day = ("0" + date.getDate()).slice(-2);
    let month = ("0" + (date.getMonth() + 1)).slice(-2);
    return date.getFullYear() + "-" + (month) + "-" + (day);
}

//구직자 가입 자동입력 함수
function auto_insert_employee_join(type) {
    switch (type) {
        case 'today':
            $('#startDate').val(today);
            $('#endDate').val(get_next_month(now,1));
            break;
        case 'extend':
            $.ajax({
                type: "POST",
                method: "POST",
                url: ajaxURL,
                data: {action: 'getLastJoinDate', id: pageID},
                dataType: "text",
                success: function (data) {
                    let startDate = JSON.parse(data).startDate;
                    let endDate = JSON.parse(data).endDate;
                    $('#startDate').val(startDate);
                    $('#endDate').val(endDate);
                }
            });
            break;
    }
    $('#price').val(50000);
    document.getElementById('paid').checked = true;
}

//월급제 자동입력
function auto_insert_call_monthly() {
    $('.workDate').val(thisMonFirstDay);
    $('.endDate').val(thisMonLastDay);
}

//1000 단위로 콤마
function number_format(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function set_validity(input_element, table){
    console.log(table);
    let name = input_element.val();
    let input = input_element;
    let type = (table ==='employee') ? '구직자' : '거래처';
    $.ajax({
            type: "POST",
            method: "POST",
            url: ajaxURL,
            data: {action: 'checkDuplicate', table: table, name: name},
            dataType: "text",
            async: true,
            success: function (data) {
                let match = JSON.parse(data).match;
                if (!match) {
                    input.get(0).setCustomValidity('존재하지 않는 '+type+'입니다.');
                }
                else{
                    input.get(0).setCustomValidity('');
                }
            }
        }
    );
}