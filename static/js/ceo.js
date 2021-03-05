// let startHour = $('#startHour');
// let endHour = $('#endHour');
// let minute = $('.minute');
// let endMin = $('#endMin');
// let salary = $('#salaryInfo');
// let date = $('#date');
// let time = endHour.val() - startHour.val();
//
// date.on('change', function () {
//     initiate(endHour.val() - startHour.val());
// });
// minute.on('change', function () {
//     minute.val($(this).val());
// });
// $('#1day').click(function () {
//     date.val(tomorrow);
//     date.trigger('change');
// });
// $('#2day').click(function () {
//     date.val(dayaftertomorrow);
//     date.trigger('change');
// });
// $('#morningBtn').click(function () {
//     startHour.val('10');
//     endHour.val('15');
//     minute.val('00');
// });
// $('#afternoonBtn').click(function () {
//     startHour.val('18');
//     endHour.val('23');
//     minute.val('00');
// });
// $('#allDayBtn').click(function () {
//     startHour.val('10');
//     endHour.val('21');
//     minute.val('00');
//     let starth = parseInt(startHour.val());
// });
// $('.btn-work-field').on('click', function () {
//     console.log($(this).text());
//     $('#workField').val($(this).text());
// });
$('.all-filter').on('change', function () {
    change('all');
});
$('.paid-filter').on('change', function () {
    change('paid');
});
// $(document).on('click','.tr-call',function () {
//     let id = $(this).attr('id');
//     $.ajax({
//         type: "POST",
//         method: "POST",
//         url: ajaxURL,
//         data: {action:'alertDetail', id:id},
//         dataType: "text",
//         success: function (data) {
//             if(data !==null && data !==''){
//                 alert('요청사항 : ' + data);
//             }
//         }
//     });
// });
// $('#btnSendCall').on('click', function () {
//     call(endHour.val() - startHour.val());
// });
// $('.fixBtn').on('click', function () {
//     $('#startTime').val($('#startHour').val() + ":" + $('#startMin').val()); //HH:MM
//     $('#endTime').val($('#endHour').val() + ":" + $('#endMin').val()); //HH:MM
//     fix(endHour.val() - startHour.val());
// });
// $('body').on('click', '.btn-call-cancel-modal', function (e) {
//     $('#callCancelID').val(this.id);
//     $('#modalCallCancel').show();
//     $('#modalCallCancel input[name=callID]').val(this.id);
//     event.stopPropagation(e);
// });
// // $('#closeCallCancelModal').on('click', function () {
// //     $('#modalCallCancel').hide();
// // });
$('.total-price').on('click',function () {
    $('#modalPayChargedCall').show();
});
$('#copyBtn').on('click',function () {
   copy();
});

function fetch_call_table(company_id, year, month, type){
    $.ajax({
        type: "POST",
        method: "POST",
        url: ajaxURL,
        data: {action: 'fetchCallTable', companyID: company_id, year: year, month: month, type: type},
        dataType: "text",
        success: function (data) {
            let body = JSON.parse(data).body;
            console.log(body);
            let total = JSON.parse(data).total;
            $('#'+type+'-call-list-body').html(body);
            if(type === 'paid'){
                $('.total-price').html('콜비 총 합: '+number_format(total)+'원');
                $('#pay-info').val($('#pay-info').val()+" "+number_format(total)+'원');
            }
        }
    });
}

function change(type) {
    let companyID = $('.user-profile').attr('id');
    let year;
    let month;
    let sum=0;
    if(type === 'all'){
        year = $('#all-year').val();
        month = $('#all-month').val();
    }
    else{
        year = $('#paid-year').val();
        month = $('#paid-month').val();
    }
    $.ajax({
        type: "POST",
        method: "POST",
        url: ajaxURL,
        data: {action: 'fetchCallTable', companyID: companyID, year: year, month: month, type: type},
        dataType: "text",
        success: function (data) {
            let body = JSON.parse(data).body;
            console.log(body);
            let total = JSON.parse(data).total;
            $('#'+type+'-call-list-body').html(body);
            if(type === 'paid'){
                $('.total-price').html('콜비 총 합: '+number_format(total)+'원');
                $('#pay-info').val($('#pay-info').val()+" "+number_format(total)+'원');
            }
        }
    });
}
// function copy() {
//     var copyText = document.getElementById("pay-info");
//     copyText.select();
//     document.execCommand("copy");
//     alert("복사되었습니다 : " + copyText.value);
// }