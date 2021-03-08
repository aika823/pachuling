const elem = document.getElementById('range');
const dateRangePicker = new DateRangePicker(elem);

if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}

$(document).ready(function () {
    console.log("document ready")
    set_date_picker();
    click_duration();
    switch_click();
    calculate_assign();
    calculate_price();
});

function test(){
    $.ajax({
        url : '/test',
        type : 'POST',
        data: {action: 'action'},
        success: function(response){
            console.log('success')
            $('document').html(response);
        }
    });
}

function set_date_picker() {
    console.log("set_date_picker")
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
            }
        }
    );
    $("#datepicker").datepicker('setDate', '<?php echo $set_date_picker ?>');
}

function switch_click() {
    $('.form-switch i').on('mouseup', function () {
        setTimeout(toggle_filter($(this)), 100);
        setTimeout(calculate_assign, 100);
        setTimeout(calculate_price, 100);
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
    let rows = $('.callRow');
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

function calculate_assign() {
    let total = $('.callRow:visible').length - $('.callRow:visible.cancelled').length;
    let assigned = $('.callRow:visible .assignedEmployee a').length;
    let not_assigned = total - assigned;
    let percentage = parseFloat((assigned * 100 / total).toFixed(1));
    if (total > 0) {
        $('.assign-percentage').text("배정률 : " + percentage + "%");
        $('.callStatus').text("총 " + total + " (배정 " + assigned + " / 미배정 " + not_assigned + ")");
        $('.bar.assign').width(percentage + '%');
        $('.bar.assign').text(percentage + "%");
    }
    else {
        $('.assign-percentage').text("콜이 없습니다.");
        $('.callStatus').text("-");
        $('.bar.assign').width('0');
        $('.bar.assign').text("-");
    }
}

function calculate_price() {
    let paid = 0;
    let paid_price = 0;
    let unpaid = 0;
    let unpaid_price = 0;
    let get_money_btn = $('.callRow:visible .btn-money');
    let paid_money_btn = $('.call-price .paid:visible');
    paid_money_btn.each(function () {
        paid += 1;
        paid_price += parseInt($(this).text());
    });
    get_money_btn.each(function () {
        unpaid += 1;
        unpaid_price += parseInt($(this).text());
    });
    let total = paid + unpaid;
    let total_price = paid_price + unpaid_price;
    let percentage = parseFloat((paid_price * 100 / total_price).toFixed(1));
    if (total > 0) {
        $('.paid-percentage').text("수금률 : " + percentage + "%");
        $('.paid_count_status').text("총 " + total + " (수금 " + paid + " / 미수 " + unpaid + ")");
        $('.paid_price_status').text("수금: " + number_format(paid_price * 1000) + "원 / 미수금: " + number_format(unpaid_price * 1000) + "원");
        $('.bar.paid').width(percentage + '%');
        $('.bar.paid').text(percentage + "%");
    }
    else {
        $('.paid-percentage').text("콜이 없습니다.");
        $('.paid_count_status').text("-");
        $('.paid_price_status').text("-");
        $('.bar.paid').width('0');
        $('.bar.paid').text('-');
    }
}

let today_top = new Date();
let year = today_top.getFullYear(); // 년도
let month = today_top.getMonth() + 1;  // 월
let date = today_top.getDate();  // 날짜
// let day = today_top.getDay();  // 요일
let _today = year + '/' + month + '/' + date;

$('.calender .date').attr('value', _today)
$('.today_btn').addClass('active');
$('.tomorrow_btn').removeClass('active');
$('.thisWeek_btn').removeClass('active');
$('.thisMonth_btn').removeClass('active');
$('#normal-btn').addClass('active');
$('#fix-btn').addClass('active');

function setDateToday(){
    let today_top = new Date();
    let year = today_top.getFullYear(); // 년도
    let month = today_top.getMonth() + 1;  // 월
    let date = today_top.getDate();  // 날짜
    // let day = today_top.getDay();  // 요일
    let _today = year + '/' + month + '/' + date;
    $('.calender .date').attr('value', _today);
    $('.today_btn').addClass('active');
    $('.tomorrow_btn').removeClass('active');
    $('.thisWeek_btn').removeClass('active');
    $('.thisMonth_btn').removeClass('active');
}

function setDateTomorrow(){
    let tomorrow = new Date();

    let year = tomorrow.getFullYear();
    let month = tomorrow.getMonth() + 1;
    let date = tomorrow.getDate() + 1;

    let _tomorrow = year + '/' + month + '/' + date;

    $('.calender .date').attr('value', _tomorrow);

    $('.today_btn').removeClass('active');
    $('.tomorrow_btn').addClass('active');
    $('.thisWeek_btn').removeClass('active');
    $('.thisMonth_btn').removeClass('active');
}

function setDateThisWeek(){
    var currentDay = new Date();
    var theYear = currentDay.getFullYear();
    var theMonth = currentDay.getMonth();
    var theDate  = currentDay.getDate();
    var theDayOfWeek = currentDay.getDay();

    var thisWeek = [];

    for(var i=0; i<7; i++) {
        var resultDay = new Date(theYear, theMonth, theDate + (i - theDayOfWeek));
        var yyyy = resultDay.getFullYear();
        var mm = Number(resultDay.getMonth()) + 1;
        var dd = resultDay.getDate();

        mm = String(mm).length === 1 ? '0' + mm : mm;
        dd = String(dd).length === 1 ? '0' + dd : dd;

        thisWeek[i] = yyyy + '/' + mm + '/' + dd;
    }

    $('.calender .date#start').attr('value', thisWeek[0]);
    $('.calender .date#end').attr('value', thisWeek[6]);

    $('.today_btn').removeClass('active');
    $('.tomorrow_btn').removeClass('active');
    $('.thisWeek_btn').addClass('active');
    $('.thisMonth_btn').removeClass('active');
}

function setDateThisMonth(){
    var now = new Date();
    var firstDate, lastDate;

    firstDate = new Date(now.getFullYear(),now.getMonth(), 1);
    lastDate = new Date(now.getFullYear(),now.getMonth()+1, 0);

    $('.calender .date#start').attr('value', moment(firstDate).format('YYYY/MM/DD'));
    $('.calender .date#end').attr('value', moment(lastDate).format('YYYY/MM/DD'));

    $('.today_btn').removeClass('active');
    $('.tomorrow_btn').removeClass('active');
    $('.thisWeek_btn').removeClass('active');
    $('.thisMonth_btn').addClass('active');
}

function fixornot_normal() {
    if($('#normal-btn').hasClass('active')){
        $('#normal-btn').removeClass('active');
    } else {
        $('#normal-btn').addClass('active');
    }
}

function fixornot_fix() {
    if($('#fix-btn').hasClass('active')){
        $('#fix-btn').removeClass('active');
    } else {
        $('#fix-btn').addClass('active');
    }
}