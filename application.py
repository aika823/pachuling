from flask import Flask, render_template, request, json
import db as db
# import call_fuction as call_function

application = Flask(__name__)
page_list = {'call': None, 'company': None, 'employee': None}


def select_page(page):
    for p in page_list: page_list[p] = None
    page_list[page] = 'selected'


@application.route('/')
@application.route('/call')
def call():
    select_page('call')
    calls = db.get_calls()
    test = "test99999999999"
    return render_template('call/call.html', calls=calls, page_list=page_list, test=test)


@application.route('/call', methods=['POST'])
def search_call():
    select_page('call')
    if request.method == 'POST':
        start = request.form['start']
        end = request.form['end']
        content = request.form['content']
        calls = db.get_calls(start, end, content)
        # if len(calls) > 0:
        #     calls = call_function.search_mark(calls, content)
        return render_template('call/call.html',
                               calls=calls, start=start, end=end, content=content, page_list=page_list)


@application.route('/company')
def company():
    select_page('company')
    companies = db.get_companies()
    return render_template('company/company.html', companies=companies, page_list=page_list)


@application.route('/employee')
def employee():
    select_page('employee')
    return render_template('employee.html', page_list=page_list)


if __name__ == "__main__":
    application.debug = True
    application.run(debug=True)
