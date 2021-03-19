def search_mark(companies, keyword):
    matches = ['companyName', 'address', 'businessType']
    if companies is not None:
        for company in companies:
            for match in matches:
                if keyword in str(company[match]):
                    company[match] = company[match].replace(keyword, '<mark>{}</mark>'.format(keyword))
    return companies
