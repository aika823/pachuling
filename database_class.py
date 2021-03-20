from application import db
from sqlalchemy import Column, Integer, String, ForeignKey


class User(db.Model):
    userID = Column(Integer, primary_key=True, nullable=False)
    userName = Column(String(20), primary_key=False, nullable=False)
    userPW = Column(String(20), primary_key=False, nullable=False)
    companyID = Column()
