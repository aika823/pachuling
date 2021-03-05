# def search_mark(calls, keyword):
#     matches = ['detail', 'companyName', 'employeeName', 'workField']
#     if calls is not None:
#         for call in calls:
#             for match in matches:
#                 if keyword in str(call[match]):
#                     call[match] = call[match].replace(keyword, '<mark>{}</mark>'.format(keyword))
#     return calls
