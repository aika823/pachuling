def search_mark(companies, keyword):
    if companies is not None:
        for company in companies:
            if keyword in company.companyName:
                company.companyName = company.companyName.replace(keyword, '<mark>{}</mark>'.format(keyword))
            if keyword in company.address:
                company.address = company.address.replace(keyword, '<mark>{}</mark>'.format(keyword))
            if keyword in company.businessType:
                company.businessType = company.businessType.replace(keyword, '<mark>{}</mark>'.format(keyword))
    return companies
