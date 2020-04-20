import os
import sys
import mysql.connector

instance_id = sys.argv[1]
user_id = sys.argv[2]

#delete aws instance from database.
mydb = mysql.connector.connect(host="localhost",user="root",passwd="",database="vcl")
mycursor = mydb.cursor()

sql_string = "DELETE FROM awsuser WHERE instance_id = '"+instance_id+"' AND user='"+user_id+"'"
mycursor.execute(sql_string)
mydb.commit()
