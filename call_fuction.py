def search_mark(calls, keyword):
    matches = ['detail', 'companyName', 'employeeName', 'workField']
    if calls is not None:
        for call in calls:
            for match in matches:
                if keyword in str(call[match]):
                    call[match] = call[match].replace(keyword, '<mark>{}</mark>'.format(keyword))
    return calls


def calculate_price(calls):
    call_dict = {
        'cnt_total': len(calls), 'price_total': 0,
        'cnt_total_price': 0, 'cnt_paid': 0, 'cnt_unpaid': 0, 'price_paid': 0, 'price_unpaid': 0,
        'cnt_assigned': 0, 'cnt_not_assigned': 0}
    for call in calls:
        if call['price'] > 0:
            call_dict['cnt_total_price'] += 1
            call_dict['price_total'] += call['price']
            if call['paid'] == 1:
                call_dict['cnt_paid'] += 1
                call_dict['price_paid'] += call['price']
            else:
                call_dict['cnt_unpaid'] += 1
                call_dict['price_unpaid'] += call['price']
        if call['employeeName']:
            call_dict['cnt_assigned'] += 1
        else:
            call_dict['cnt_not_assigned'] += 1
    return call_dict
