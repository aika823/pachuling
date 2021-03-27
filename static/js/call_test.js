function calculate_price() {
    console.log("calculate_price() function");

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