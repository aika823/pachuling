import pymysql
import secret
from datetime import date

conn = pymysql.connect(host=secret.host, port=secret.port, user=secret.user, password=secret.password, db=secret.db)
cursor = conn.cursor()


def get_calls(user_id, start=date.today(), end=date.today(), content=None, limit=100):
    cur = conn.cursor(pymysql.cursors.DictCursor)
    sql = " SELECT " \
          " `call`.workDate, `call`.startTime, `call`.endTime, " \
          " `call`.workfield, `call`.detail, `call`.salary, `call`.price, `call`.paid, `call`.userID, " \
          " `company`.companyName, " \
          " `employee`.employeeName, " \
          " `workfield`.workField " \
          " FROM `call` " \
          " LEFT JOIN `company` on `call`.companyID = `company`.companyID and `call`.userID = `company`.userID " \
          " LEFT JOIN `employee` on `call`.employeeID = `employee`.employeeID and `call`.userID = `employee`.userID " \
          " LEFT JOIN `workfield` on `call`.workField = `workfield`.workFieldID and `call`.userID = `workfield`.userID "
    conditions = [" `call`.userID = '{}' ".format(user_id)]
    if start and end:
        conditions.append(" (`workDate` between '{}' and '{}') ".format(start, end))
    if content:
        content_sql = " ( " \
                      " (`call`.detail LIKE '%{}%') OR " \
                      " (companyName LIKE '%{}%') OR " \
                      " (employeeName LIKE '%{}%') OR " \
                      " (call.workField LIKE '%{}%') ) "
        conditions.append(content_sql.format(content, content, content, content))
    sql += " WHERE " + " AND ".join(conditions)
    sql += " LIMIT {} ".format(str(limit))
    print(sql)
    cur.execute(sql)
    calls = cur.fetchall()
    return calls


def get_company(user_id, company_id):
    cur = conn.cursor(pymysql.cursors.DictCursor)
    sql = "select * from company where userID='{}' AND companyID = {}".format(user_id, company_id)
    cur.execute(sql)
    company = cur.fetchall()
    return company


def get_employees():
    cur = conn.cursor(pymysql.cursors.DictCursor)
    sql = "select * from employee "
    cur.execute(sql)
    employees = cur.fetchall()
    return employees


def get_employee(user_id, employee_id):
    cur = conn.cursor(pymysql.cursors.DictCursor)
    sql = "select * from employee where `userID`='{}' AND `employeeID` = {}".format(user_id, employee_id)
    cur.execute(sql)
    employee = cur.fetchone()
    return employee


def get_ceo(ceo_id):
    cur = conn.cursor(pymysql.cursors.DictCursor)
    sql = "select * from company left join ceo on company.ceoID = ceo.ceoID where company.ceoID = " + str(ceo_id)
    cur.execute(sql)
    ceo = cur.fetchall()
    return ceo


def get_users():
    cur = conn.cursor(pymysql.cursors.DictCursor)
    sql = "select * from user"
    cur.execute(sql)
    users = cur.fetchall()
    return users
