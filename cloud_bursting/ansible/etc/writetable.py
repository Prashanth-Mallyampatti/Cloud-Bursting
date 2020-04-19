# Usage: python writetable.py awsdetailsfile

import mysql.connector
from collections import defaultdict
import yaml
import sys

aws_list = []
awsdict = {}
awsdetails = sys.argv[1]

with open(awsdetails) as file:
  aws_list = yaml.load(file, Loader=yaml.FullLoader)
file.close()

awsdict['instance_id'] = aws_list['results'][0]['instance_ids'][0]
awsdict['public_ip'] = aws_list['results'][0]['instances'][0]['public_ip']
awsdict['key_name'] = aws_list['results'][0]['item']['key_name']
awsdict['user'] = aws_list['results'][0]['item']['user']
awsdict['dns'] = aws_list['results'][0]['instances'][0]['dns_name']

mydb = mysql.connector.connect(
  host="localhost",
  user="root",
 passwd=""
)
mycursor = mydb.cursor()

mycursor.execute("USE vcl;")

mycursor.execute("CREATE TABLE IF NOT EXISTS awsuser (instance_id VARCHAR(255) PRIMARY KEY, public_ip VARCHAR(255), key_name VARCHAR(255), user VARCHAR(255), dns VARCHAR(255))")

values = (awsdict['instance_id'], awsdict['public_ip'], awsdict['key_name'], awsdict['user'], awsdict['dns'])
print("createtable: command run - INSERT IGNORE INTO awsuser (instance_id, public_ip, key_name, user, dns) VALUES (%s,%s,%s,%s,%s)",values)
mycursor.execute("INSERT IGNORE INTO awsuser (instance_id, public_ip, key_name, user, dns) VALUES (%s,%s,%s,%s,%s)",values)
mydb.commit()
