import pymysql
import secret
import sys
from datetime import date

conn = pymysql.connect(host=secret.host, port=secret.port, user=secret.user, password=secret.password, db=secret.db)
cursor = conn.cursor()


def get_calls(start=date.today(), end=date.today(), content=None, limit=1000):
    cur = conn.cursor(pymysql.cursors.DictCursor)
    sql = " SELECT " \
          " call.workDate, call.startTime, call.endTime, call.workField, " \
          " call.detail, call.salary, call.price, call.paid, " \
          " company.companyName, " \
          " employee.employeeName, " \
          " workField.workField " \
          " FROM `call` " \
          " LEFT JOIN `company` on call.companyID = company.companyID " \
          " LEFT JOIN `employee` on call.employeeID = employee.employeeID " \
          " LEFT JOIN `workField` on call.workField = workField.workFieldID "
    conditions = []
    if start and end:
        conditions.append(" (`workDate` between '{}' and '{}') ".format(start, end))
    if content:
        content_sql = " ( " \
                      " (call.detail LIKE '%{}%') OR " \
                      " (companyName LIKE '%{}%') OR " \
                      " (employeeName LIKE '%{}%') OR " \
                      " (call.workField LIKE '%{}%') ) "
        conditions.append(content_sql.format(content, content, content, content))
    sql += " WHERE " + " AND ".join(conditions)
    sql += " LIMIT {} ".format(str(limit))
    cur.execute(sql)
    calls = cur.fetchall()
    return calls


def get_companies():
    cur = conn.cursor(pymysql.cursors.DictCursor)
    sql = "select * from company limit 100"
    cur.execute(sql)
    companies = cur.fetchall()
    return companies


def get_company(company_id):
    cur = conn.cursor(pymysql.cursors.DictCursor)
    sql = "select * from company where companyID = " + str(company_id)
    cur.execute(sql)
    company = cur.fetchall()
    return company


def get_ceo(ceo_id):
    cur = conn.cursor(pymysql.cursors.DictCursor)
    sql = "select * from company left join ceo on company.ceoID = ceo.ceoID where company.ceoID = " + str(ceo_id)
    cur.execute(sql)
    ceo = cur.fetchall()
    return ceo


def insert_details(name, email, comment, gender):
    cur = conn.cursor()
    cur.execute("INSERT INTO Details (name,email,comment,gender) VALUES (%s,%s,%s,%s)", (name, email, comment, gender))
    conn.commit()
