from flask import Flask, render_template, request
import db as db
import call_fuction as call_function

application = Flask(__name__)
page_list = {'call': None, 'company': None, 'employee': None, 'manage': None}


def select_page(page):
    for p in page_list: page_list[p] = None
    page_list[page] = 'selected'


@application.route('/')
@application.route('/call')
def call():
    select_page('call')
    calls = db.get_calls()
    call_dict = call_function.calculate_price(calls)
    return render_template('call/call.html', calls=calls, call_dict=call_dict, page_list=page_list)


@application.route('/call', methods=['POST'])
def search_call():
    select_page('call')
    if request.method == 'POST':
        start = request.form['start']
        end = request.form['end']
        content = request.form['content']
        calls = db.get_calls(start, end, content)
        call_dict = call_function.calculate_price(calls)
        if len(calls) > 0:
            if content:
                calls = call_function.search_mark(calls, content)
        return render_template('call/call.html',
                               calls=calls, call_dict=call_dict, start=start, end=end, content=content,
                               page_list=page_list)


@application.route('/call/write')
def call_form():
    return render_template('call/callForm.html', page_list=page_list)


@application.route('/company')
def company():
    select_page('company')
    companies = db.get_companies()
    return render_template('company/company.html', companies=companies, page_list=page_list)


@application.route('/company/view/<company_id>')
def view_company(company_id):
    select_page('company')
    my_company = db.get_company(company_id)
    return render_template('company/view.html', company=my_company, page_list=page_list)


@application.route('/company/write')
def company_form():
    return render_template('company/companyForm.html', page_list=page_list)


@application.route('/employee')
def employee():
    select_page('employee')
    # employees = db.get_employees()
    return render_template('employee/employee.html', page_list=page_list)


@application.route('/employee/write')
def employee_form():
    select_page('employeeForm')
    return render_template('employee/employeeForm.html', page_list=page_list)


@application.route('/employee/available')
def employee_available():
    select_page('employeeAvailable')
    return render_template('employee/employeeAvailable.html', page_list=page_list)


@application.route('/manage')
def manage():
    select_page('black')
    companies = db.get_companies()
    return render_template('manage/black.html', companies=companies, page_list=page_list)


@application.route('/ceo/<ceo_id>')
def show_ceo(ceo_id):
    ceo = db.get_ceo(ceo_id)
    return render_template('ceo/ceo.html', ceo=ceo, page_list=page_list)


if __name__ == "__main__":
    application.debug = True
    application.run(debug=True)
