from flask import Flask, url_for, render_template, request, redirect, session, flash
from flask_sqlalchemy import SQLAlchemy

import database_function
import call_function
import company_function
# import database_class

import secret

application = app = Flask(__name__)
application.config['SQLALCHEMY_DATABASE_URI'] = \
    'mysql+pymysql://{user}:{password}@{host}/{db}'.format(user=secret.user, password=secret.password,
                                                           host=secret.host, db=secret.db)
application.secret_key = "123"
db = SQLAlchemy(application)
page_list = {'call': None, 'company': None, 'employee': None, 'manage': None}
login_error_message = "ID: admin, PW: bestgood"


def select_page(page):
    for p in page_list: page_list[p] = None
    page_list[page] = 'selected'


@application.route('/')
def fuck():
    calls = database_function.get_calls()
    call_dict = call_function.calculate_price(calls)
    return render_template('call/call.html', calls=calls, call_dict=call_dict, page_list=page_list)


#     # if not session.get('logged_in'):
#     #     return redirect(url_for('login'))
#     # else:
#     #     select_page('call')
#     #     calls = database_function.get_calls()
#     #     call_dict = call_function.calculate_price(calls)
#     #     return render_template('call/call.html', calls=calls, call_dict=call_dict, page_list=page_list)

@application.route('/call')
def call():
    calls = database_function.get_calls()
    call_dict = call_function.calculate_price(calls)
    return render_template('call/call.html', calls=calls, call_dict=call_dict, page_list=page_list)
    # if not session.get('logged_in'):
    #     return redirect(url_for('login'))
    # else:
    #     select_page('call')
    #     calls = database_function.get_calls()
    #     call_dict = call_function.calculate_price(calls)
    #     return render_template('call/call.html', calls=calls, call_dict=call_dict, page_list=page_list)


@application.route('/call', methods=['POST'])
def search_call():
    select_page('call')
    if request.method == 'POST':
        start = request.form['start']
        end = request.form['end']
        content = request.form['content']
        calls = database_function.get_calls(start, end, content)
        call_dict = call_function.calculate_price(calls)
        if len(calls) > 0:
            if content:
                calls = call_function.search_mark(calls, content)
        return render_template('call/call.html',
                               calls=calls, call_dict=call_dict, start=start, end=end, content=content,
                               page_list=page_list)


@application.route('/call/write')
def call_form():
    if not session.get('logged_in'):
        return redirect(url_for('login'))
    else:
        return render_template('call/callForm.html', page_list=page_list)


@application.route('/company')
def company():
    if not session.get('logged_in'):
        return redirect(url_for('login'))
    else:
        select_page('company')
        companies = database_function.get_companies()
        return render_template('company/company.html', companies=companies, page_list=page_list)


@application.route('/company', methods=['POST'])
def search_company():
    select_page('company')
    if request.method == 'POST':
        content = request.form['keyword']
        companies = database_function.get_companies(content=content)
        if len(companies) > 0:
            if content:
                companies = company_function.search_mark(companies, content)
        return render_template('company/company.html', companies=companies, content=content, page_list=page_list)


@application.route('/company/view/<company_id>')
def view_company(company_id):
    if not session.get('logged_in'):
        return redirect(url_for('login'))
    else:
        select_page('company')
        my_company = database_function.get_company(company_id)
        return render_template('company/view.html', company=my_company, page_list=page_list)


@application.route('/company/write')
def company_form():
    if not session.get('logged_in'):
        return redirect(url_for('login'))
    else:
        return render_template('company/companyForm.html', page_list=page_list)


@application.route('/employee')
def employee():
    if not session.get('logged_in'):
        return redirect(url_for('login'))
    else:
        select_page('employee')
        employees = database_function.get_employees()
        return render_template('employee/employee.html', employees=employees, page_list=page_list)


@application.route('/employee/write')
def employee_form():
    if not session.get('logged_in'):
        return redirect(url_for('login'))
    else:
        select_page('employeeForm')
        return render_template('employee/employeeForm.html', page_list=page_list)


@application.route('/employee/available')
def employee_available():
    if not session.get('logged_in'):
        return redirect(url_for('login'))
    else:
        select_page('employeeAvailable')
        return render_template('employee/employeeAvailable.html', page_list=page_list)


@application.route('/manage')
def manage():
    if not session.get('logged_in'):
        return redirect(url_for('login'))
    else:
        select_page('black')
        companies = database_function.get_companies()
        return render_template('manage/black.html', companies=companies, page_list=page_list)


@application.route('/ceo/<ceo_id>')
def show_ceo(ceo_id):
    if not session.get('logged_in'):
        return redirect(url_for('login'))
    else:
        ceo = database_function.get_ceo(ceo_id)
        return render_template('ceo/ceo.html', ceo=ceo, page_list=page_list)


# @application.route('/login', methods=['GET', 'POST'])
# def login():
#     user = database_class.User
#     if request.method == 'GET':
#         return render_template('login/login.html', page_list=page_list)
#     elif request.method == 'POST':
#         user_name = request.form['username']
#         user_pw = request.form['password']
#         data = user.query.filter_by(userName=user_name, userPW=user_pw).first()
#         if data is not None:  # 로그인 성공!
#             session['logged_in'] = True
#             session['user_id'] = data.userID
#             if data.companyID:  # 사장님 로그인
#                 return redirect(url_for('show_ceo', ceo_id=session['user_id']))
#             else:  # 관리자 로그인
#                 return redirect(url_for('call'))
#         else:  # 로그인 실패
#             session['logged_in'] = False
#             return render_template('login/login.html', test="LOGIN FAIL", data=data, user_name=user_name,
#                                    page_list=page_list)


# @application.route("/logout")
# def logout():
#     session['logged_in'] = False
#     return redirect(url_for('login'))


if __name__ == "__main__":
    application.debug = True
    application.run(debug=True)
