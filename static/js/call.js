// $('.ui-datepicker-calendar').on('click', function () {
//     $(this).addClass('selected');
//     $(this).css('background', 'red');
// });
$(document).on('click', '.assignBtn', function () {
    let callID = $('.callRow.selected').attr('id');
    let employeeID = this.id;

    $.ajax({
        type: "POST",
        method: "POST",
        url: ajaxURL,
        data: {action: 'get_info', table: 'employee', id: employeeID},
        dataType: "text",
        success: function (data) {

            let activated = parseInt(JSON.parse(data).activated);
            console.log(activated);

            if (activated) {
                $.ajax({
                    type: "POST",
                    method: "POST",
                    url: ajaxURL,
                    data: {action: 'assign', callID: callID, employeeID: employeeID, activated: true},
                    dataType: "text",
                    success: function (data) {
                        $('.callRow.selected .assignedEmployee').html(JSON.parse(data));
                        alert('배정되었습니다.');
                    }
                });
            }
            else {
                if (confirm("만기된 회원입니다.\n가입을 추가하고 배정하시겠습니까?")) {
                    $.ajax({
                        type: "POST",
                        method: "POST",
                        url: ajaxURL,
                        data: {action: 'assign', callID: callID, employeeID: employeeID},
                        dataType: "text",
                        success: function (data) {
                            $('.callRow.selected .assignedEmployee').html(JSON.parse(data));
                            alert('배정되었습니다.');
                        }
                    });
                }
                else {
                    alert("배정을 취소했습니다.");
                }
            }
        }
    });
});
$('.callRow').on('click', function () {
    if (!$(this).hasClass('cancelled')) {
        getHTML($('#employeeTable'), 'assignFilter', $(this).attr('id'));
    }
    else {
        event.stopPropagation();
        alert('취소된 콜입니다');
    }
});