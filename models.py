from sqlalchemy import Column, Integer, String, TIMESTAMP, Boolean, Date, ForeignKey
from flask_sqlalchemy import SQLAlchemy

db = SQLAlchemy()


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
    __tablename__ = 'employee'
    userID = Column(Integer)
    employeeID = Column(Integer, primary_key=True, nullable=False, autoincrement=True)
    employeeName = Column(String(20))
    sex = Column(String(10))


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


class Workfield(db.Model):
    __tablename__ = 'workfield'
    workFieldID = Column(Integer, primary_key=True, nullable=False)
    workField = Column(String(20), primary_key=False, nullable=False)
    userID = Column(Integer, primary_key=False, nullable=False)
