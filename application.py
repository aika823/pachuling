from datetime import timedelta
from flask import Flask, url_for, render_template, request, redirect, session, flash
from flask_sqlalchemy import SQLAlchemy
from sqlalchemy import Column, Integer, String, TIMESTAMP, Boolean, Date, ForeignKey
from sqlalchemy.orm import sessionmaker
from functools import wraps
from sqlalchemy import create_engine

import function_database
import function_call
import function_company
import secret

# Default Settings
application = app = Flask(__name__)
application.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+pymysql://{user}:{password}@{host}/{db}' \
    .format(user=secret.user, password=secret.password, host=secret.host, db=secret.db)
application.secret_key = "123"
db = SQLAlchemy(application)
page_list = {'call': None, 'company': None, 'employee': None, 'manage': None}
login_error_message = "ID: admin, PW: bestgood"
app.permanent_session_lifetime = timedelta(hours=24)
Session = sessionmaker()
engine = create_engine('sqlite:///:memory:', echo=True)
Session.configure(bind=engine)


# Database Classes
class User(db.Model):
    userID = Column(Integer, primary_key=True, nullable=False)
    userName = Column(String(20), primary_key=False, nullable=False)
    userPW = Column(String(20), primary_key=False, nullable=False)
    companyID = Column(Integer, primary_key=False, nullable=False)


class Company(db.Model):
    userID = Column(Integer, primary_key=False, nullable=False)
    companyID = Column(Integer, primary_key=True, nullable=False)
    ceoID = Column(Integer, primary_key=False, nullable=False)
    businessType = Column(Integer, primary_key=False, nullable=False)
    companyName = Column(Integer, primary_key=False, nullable=False)
    deleted = Column(Integer, nullable=False)
    activated = Column(Integer, nullable=False)
    address = Column(String(20), nullable=False)


class Employee(db.Model):
    employeeID = Column(Integer, primary_key=True, nullable=False, autoincrement=True)
    employeeName = Column(String(20))
    sex = Column(String(10))


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
    companyID = Column(Integer, ForeignKey(Company.companyID))
    employeeID = Column(Integer, ForeignKey(Employee.employeeID))
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


def login_required(f):
    @wraps(f)
    def wrap(*args, **kwargs):
        if session.get('logged_in'):
            return f(*args, **kwargs)
        else:
            flash('로그인 먼저!')
            return redirect(url_for('login'))

    return wrap


@application.route('/')
@application.route('/call')
@login_required
def call():
    select_page('call')
    user_id = session.get('user_id')
    calls = function_database.get_calls(user_id=user_id)
    call_dict = function_call.calculate_price(calls)
    return render_template('call/call.html', calls=calls, call_dict=call_dict, page_list=page_list)


@application.route('/call', methods=['POST'])
@login_required
def search_call():
    select_page('call')
    if request.method == 'POST':
        user_id = session.get('user_id')
        start = request.form['start']
        end = request.form['end']
        content = request.form['content']
        calls = function_database.get_calls(user_id=user_id, start=start, end=end, content=content)
        call_dict = function_call.calculate_price(calls)
        if len(calls) > 0:
            if content:
                calls = function_call.search_mark(calls, content)
        return render_template('call/call.html',
                               calls=calls,
                               call_dict=call_dict,
                               start=start,
                               end=end,
                               content=content,
                               page_list=page_list)


@application.route('/call/write')
@login_required
def call_form():
    return render_template('call/callForm.html', page_list=page_list)


@application.route('/company')
def company():
    select_page('company')
    user_id = session.get('user_id')
    companies = Company.query.filter_by(userID=user_id, deleted=0, activated=1).all()
    size = len(companies)
    return render_template('company/company.html', companies=companies, page_list=page_list, size=size)


@application.route('/company', methods=['POST'])
@login_required
def search_company():
    select_page('company')
    user_id = session.get('user_id')
    if request.method == 'POST':
        keyword = request.form['keyword']
        search = "%{}%".format(keyword)
        companies = Company.query \
            .filter_by(userID=user_id) \
            .filter(Company.companyName.like(search) | Company.address.like(search) | Company.businessType.like(search)) \
            .all()
        companies = function_company.search_mark(companies, keyword)
        print(type(companies))
        size = len(companies)
        return render_template('company/company.html', companies=companies, content=keyword, page_list=page_list,
                               size=size)


@application.route('/company/view/<company_id>')
@login_required
def view_company(company_id):
    select_page('company')
    user_id = session.get('user_id')
    my_company = function_database.get_company(user_id=user_id, company_id=company_id)
    return render_template('company/view.html', company=my_company, page_list=page_list)


@application.route('/company/write')
def company_form():
    return render_template('company/companyForm.html', company=None, page_list=page_list)


@application.route('/company/write', methods=['POST'])
@login_required
def insert_company():
    ceo_id = "ceo_id"
    user_id = session.get('user_id')
    company_name = request.form['companyName']
    business_type = request.form['businessType']
    new_company = Company(user_id, company_name, business_type)
    # 기존 업체 정보 수정
    if request.form['companyID']:
        db.session.add(new_company)
        return render_template('company/companyForm.html', page_list=page_list)
    # 신규 업체 추가
    else:
        db.session.add(new_company)
        return render_template('company/companyForm.html', page_list=page_list)


@application.route('/employee')
@login_required
def employee():
    select_page('employee')
    employees = function_database.get_employees()
    return render_template('employee/employee.html', employees=employees, page_list=page_list)


@application.route('/employee/write')
@login_required
def employee_form():
    select_page('employee')
    return render_template('employee/employee_write.html', page_list=page_list)


@application.route('/employee/view/<employee_id>')
@login_required
def view_employee(employee_id):
    select_page('employee')
    user_id = session.get('user_id')
    work_field = Workfield.query.filter_by(userID=user_id)
    print(work_field)
    address_list = Address.query.filter_by(userID=user_id)
    black_list = Blacklist.query.join(Company, Blacklist.companyID == Company.companyID) \
        .add_columns(Company.companyName) \
        .filter(Blacklist.userID == user_id) \
        .filter(Blacklist.employeeID == employee_id)
    available_date_list = EmployeeAvailableDate.query.filter_by(userID=user_id, employeeID=employee_id)
    my_employee = function_database.get_employee(user_id=user_id, employee_id=employee_id)
    return render_template('employee/employee_view.html',
                           employee=my_employee,
                           action='update',
                           work_field_list=work_field,
                           address_list=address_list,
                           black_list=black_list,
                           available_date_list=available_date_list,
                           page_list=page_list)


@application.route('/employee/available')
@login_required
def employee_available():
    select_page('employeeAvailable')
    return render_template('employee/employeeAvailable.html', page_list=page_list)


@application.route('/manage')
@login_required
def manage():
    select_page('black')
    user_id = session.get('user_id')
    companies = function_database.get_companies(user_id=user_id)
    return render_template('manage/black.html', companies=companies, page_list=page_list)


@application.route('/ceo/<ceo_id>')
@login_required
def show_ceo(ceo_id):
    ceo = function_database.get_ceo(ceo_id)
    return render_template('ceo/ceo.html', ceo=ceo, page_list=page_list)


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
            return render_template('login/login.html', test="LOGIN FAIL", data=data, user_name=user_name,
                                   page_list=page_list)


@application.route("/logout")
@login_required
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
