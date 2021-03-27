let form = $('#formInsertEmployee input[name=employeeName]');
if (form.val() === null) {
    $('#employeeNameDuplicate').html('이름을 입력 해 주세요');
}
else {
    form.on('input', function () {
        let employeeName = $(this).val();
        $.ajax({
            type: "POST",
            method: "POST",
            url: ajaxURL,
            data: {action: 'checkDuplicate', table:'employee', name: employeeName},
            dataType: "text",
            success: function (data) {
                let list = JSON.parse(data);
                $('#employeeNameDuplicate').html(list);
            }
        });
    });
}