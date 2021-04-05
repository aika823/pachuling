from flask import Flask, url_for, render_template, request, redirect, session, flash
from flask_sqlalchemy import SQLAlchemy
from sqlalchemy import Column, Integer, String, TIMESTAMP, Boolean, Date

import database_function
import call_function
import company_function
import secret

application = app = Flask(__name__)
application.config['SQLALCHEMY_DATABASE_URI'] = \
    'mysql+pymysql://{user}:{password}@{host}/{db}'.format(user=secret.user, password=secret.password,
                                                           host=secret.host, db=secret.db)
application.secret_key = "123"
db = SQLAlchemy(application)
page_list = {'call': None, 'company': None, 'employee': None, 'manage': None}
login_error_message = "ID: admin, PW: bestgood"


class User(db.Model):
    userID = Column(Integer, primary_key=True, nullable=False)
    userName = Column(String(20), primary_key=False, nullable=False)
    userPW = Column(String(20), primary_key=False, nullable=False)
    companyID = Column(Integer, primary_key=False, nullable=False)


class Company(db.Model):
    userID = Column(Integer, primary_key=True, nullable=False)
    companyID = Column(Integer, primary_key=False, nullable=False)
    ceoID = Column(Integer, primary_key=False, nullable=False)
    businessType = Column(Integer, primary_key=False, nullable=False)
    companyName = Column(Integer, primary_key=False, nullable=False)


class Workfield(db.Model):
    workFieldID = Column(Integer, primary_key=True, nullable=False)
    workField = Column(String(20), primary_key=False, nullable=False)
    userID = Column(Integer, primary_key=False, nullable=False)


class Address(db.Model):
    addressID = Column(Integer, primary_key=True, nullable=False)
    address = Column(String(50), primary_key=False, nullable=False)
    userID = Column(Integer, primary_key=False, nullable=False)


class Blacklist(db.Model):
    blackListID = Column(Integer, primary_key=True, nullable=False)
    companyID = Column(Integer, primary_key=False, nullable=False)
    employeeID = Column(Integer, primary_key=False, nullable=False)
    detail = Column(String(100), primary_key=False, nullable=True)
    ceoReg = Column(Boolean, primary_key=False, nullable=False)
    createdTime = Column(TIMESTAMP, primary_key=False, nullable=False)
    userID = Column(Integer, primary_key=False, nullable=False)


class EmployeeAvailableDate(db.Model):
    availableDateID = Column(Integer, primary_key=True, nullable=False)
    employeeID = Column(Integer, primary_key=False, nullable=False)
    availableDate = Column(Date, primary_key=False, nullable=True)
    notAvailableDate = Column(Date, primary_key=False, nullable=True)
    detail = Column(String(500), primary_key=False, nullable=True)
    userID = Column(Integer, primary_key=False, nullable=False)

def select_page(page):
    for p in page_list: page_list[p] = None
    page_list[page] = 'selected'


@application.route('/')
@application.route('/call')
def call():
    if not session.get('logged_in'):
        return redirect(url_for('login'))
    else:
        select_page('call')
        user_id = session.get('user_id')
        calls = database_function.get_calls(user_id=user_id)
        call_dict = call_function.calculate_price(calls)
        return render_template('call/call.html',
                               calls=calls,
                               call_dict=call_dict,
                               page_list=page_list)


@application.route('/call', methods=['POST'])
def search_call():
    select_page('call')
    if request.method == 'POST':
        user_id = session.get('user_id')
        start = request.form['start']
        end = request.form['end']
        content = request.form['content']
        calls = database_function.get_calls(user_id=user_id, start=start, end=end, content=content)
        call_dict = call_function.calculate_price(calls)
        if len(calls) > 0:
            if content:
                calls = call_function.search_mark(calls, content)
        return render_template('call/call.html',
                               calls=calls,
                               call_dict=call_dict,
                               start=start,
                               end=end,
                               content=content,
                               page_list=page_list)


@application.route('/call/write')
def call_form():
    if not session.get('logged_in'):
        return redirect(url_for('login'))
    else:
        return render_template('call/callForm.html',
                               page_list=page_list)


@application.route('/company')
def company():
    if not session.get('logged_in'):
        return redirect(url_for('login'))
    else:
        select_page('company')
    user_id = session.get('user_id')
    companies = database_function.get_companies(user_id)
    return render_template('company/company.html',
                           companies=companies,
                           page_list=page_list)


@application.route('/company', methods=['POST'])
def search_company():
    select_page('company')
    if request.method == 'POST':
        content = request.form['keyword']
        user_id = session.get('user_id')
        companies = database_function.get_companies(user_id=user_id, content=content)
        if len(companies) > 0:
            if content:
                companies = company_function.search_mark(companies, content)
        return render_template('company/company.html',
                               companies=companies,
                               content=content,
                               page_list=page_list)


@application.route('/company/view/<company_id>')
def view_company(company_id):
    if not session.get('logged_in'):
        return redirect(url_for('login'))
    else:
        select_page('company')
    user_id = session.get('user_id')
    my_company = database_function.get_company(user_id=user_id, company_id=company_id)
    return render_template('company/view.html',
                           company=my_company,
                           page_list=page_list)


@application.route('/company/write')
def company_form():
    if not session.get('logged_in'):
        return redirect(url_for('login'))
    else:
        return render_template('company/companyForm.html',
                               company=None,
                               page_list=page_list)


@application.route('/company/write', methods=['POST'])
def insert_company():
    if not session.get('logged_in'):
        return redirect(url_for('login'))
    else:
        ceo_id = "ceo_id"
        user_id = session.get('user_id')
        company_name = request.form['companyName']
        business_type = request.form['businessType']
        new_company = Company(user_id, company_name, business_type)
        # 기존 업체 정보 수정
        if request.form['companyID']:
            db.session.add(new_company)
            return render_template('company/companyForm.html',
                                   page_list=page_list)
        # 신규 업체 추가
        else:
            db.session.add(new_company)
            return render_template('company/companyForm.html',
                                   page_list=page_list)


@application.route('/employee')
def employee():
    if not session.get('logged_in'):
        return redirect(url_for('login'))
    else:
        select_page('employee')
    employees = database_function.get_employees()
    return render_template('employee/employee.html',
                           employees=employees,
                           page_list=page_list)


@application.route('/employee/write')
def employee_form():
    if not session.get('logged_in'):
        return redirect(url_for('login'))
    else:
        select_page('employee')
        return render_template('employee/employee_write.html',
                               page_list=page_list)


@application.route('/employee/view/<employee_id>')
def view_employee(employee_id):
    if not session.get('logged_in'):
        return redirect(url_for('login'))
    else:
        select_page('employee')
        user_id = session.get('user_id')
        work_field = Workfield.query.filter_by(userID=user_id)
        address = Address.query.filter_by(userID=user_id)
        black_list = Blacklist.query.filter_by(userID=user_id, employeeID=employee_id)
        available_date_list = EmployeeAvailableDate.query.filter_by(userID=user_id, employeeID=employee_id)
        my_employee = database_function.get_employee(user_id=user_id, employee_id=employee_id)
        print(my_employee)
        return render_template('employee/employee_view.html',
                               employee=my_employee,
                               action='update',
                               work_field_list=work_field,
                               address_list=address,
                               black_list=black_list,
                               available_date_list = available_date_list,
                               page_list=page_list)


@application.route('/employee/available')
def employee_available():
    if not session.get('logged_in'):
        return redirect(url_for('login'))
    else:
        select_page('employeeAvailable')
    return render_template('employee/employeeAvailable.html',
                           page_list=page_list)


@application.route('/manage')
def manage():
    if not session.get('logged_in'):
        return redirect(url_for('login'))
    else:
        select_page('black')
        user_id = session.get('user_id')
        companies = database_function.get_companies(user_id=user_id)
        return render_template('manage/black.html',
                               companies=companies,
                               page_list=page_list)


@application.route('/ceo/<ceo_id>')
def show_ceo(ceo_id):
    if not session.get('logged_in'):
        return redirect(url_for('login'))
    else:
        ceo = database_function.get_ceo(ceo_id)
    return render_template('ceo/ceo.html',
                           ceo=ceo,
                           page_list=page_list)


@application.route('/login')
def login():
    return render_template('login/login.html',
                           page_list=page_list)


@application.route('/login', methods=['GET', 'POST'])
def login_check():
    user = User
    if request.method == 'GET':
        return render_template('login/login.html',
                               page_list=page_list)
    elif request.method == 'POST':
        user_name = request.form['username']
        user_pw = request.form['password']
        data = user.query.filter_by(userName=user_name, userPW=user_pw).first()
        if data is not None:  # 로그인 성공!
            print("login success")
            session['logged_in'] = True
            session['user_id'] = data.userID
            session['company_id'] = data.companyID
            # 사장님 로그인
            if data.companyID:
                return redirect(url_for('show_ceo', ceo_id=session['user_id']))
            # 관리자 로그인
            else:
                return redirect(url_for('call'))
        # 로그인 실패
        else:
            print("login fail")
            session['logged_in'] = False
            flash("로그인에 실패했습니다. ID:으뜸파출 / PW:123")
            return render_template('login/login.html',
                                   test="LOGIN FAIL",
                                   data=data,
                                   user_name=user_name,
                                   page_list=page_list)


@application.route("/logout")
def logout():
    session['logged_in'] = False
    session['user_id'] = None
    session['company_id'] = None
    flash("로그아웃 되었습니다.")
    return redirect(url_for('login'))


@app.errorhandler(404)
def error_404(e):
    return render_template("error/404.html")


if __name__ == "__main__":
    application.debug = True
    application.run(debug=True)
