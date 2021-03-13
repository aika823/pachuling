from flask import Flask, render_template, request
import db as db
import call_fuction as call_function
import json
from flask.json import JSONEncoder
from datetime import date


class CustomJSONEncoder(JSONEncoder):
    def default(self, obj):
        try:
            if isinstance(obj, date):
                return obj.strftime('%Y-%m-%d')
            iterable = iter(obj)
        except TypeError:
            pass
        else:
            return list(iterable)
        return JSONEncoder.default(self, obj)


application = Flask(__name__)
application.json_encoder = CustomJSONEncoder
page_list = {'call': None, 'company': None, 'employee': None, 'manage': None}


def select_page(page):
    for p in page_list: page_list[p] = None
    page_list[page] = 'selected'


@application.route('/')
@application.route('/call')
def call():
    select_page('call')
    calls = db.get_calls()
    print("###########")
    print(calls)
    print(len(calls))
    print("###########")
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
        if len(calls) > 0:
            calls = call_function.search_mark(calls, content)
        return render_template('call/call.html',
                               calls=calls, start=start, end=end, content=content, page_list=page_list)


@application.route('/call/write')
def call_form():
    return render_template('call/callForm.html', page_list=page_list)


@application.route('/company')
def company():
    select_page('company')
    companies = db.get_companies()
    return render_template('company/company.html', companies=companies, page_list=page_list)


@application.route('/company/write')
def company_form():
    return render_template('company/companyForm.html', page_list=page_list)


@application.route('/employee')
def employee():
    select_page('employee')
    # employees = db.get_employees()
    return render_template('employee/employee.html', page_list=page_list)


@application.route('/employeeForm')
def employee_form():
    select_page('employeeForm')
    return render_template('employee/employeeForm.html', page_list=page_list)


@application.route('/employeeAvailable')
def employee_available():
    select_page('employeeAvailable')
    return render_template('employee/employeeAvailable.html', page_list=page_list)


@application.route('/manage')
def manage():
    select_page('black')
    companies = db.get_companies()
    return render_template('manage/black.html', companies=companies, page_list=page_list)


if __name__ == "__main__":
    application.debug = True
    application.run(debug=True)
