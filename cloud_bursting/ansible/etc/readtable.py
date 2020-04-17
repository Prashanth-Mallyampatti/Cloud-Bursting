import mysql.connector
from collections import defaultdict 

userdict = defaultdict(list)
mydb = mysql.connector.connect(
  host="localhost",
  user="root",
  passwd=""
)
mycursor = mydb.cursor() 
mycursor.execute("USE vcl;")

password = ('test', )

query = "SELECT instance_id, public_ip, key_name, user, AES_DECRYPT(private_key, %s) FROM awsuser"

mycursor.execute(query, password)
myresult = mycursor.fetchall()
for instance_id, public_ip, key_name, user, private_key in myresult:
  userdict[instance_id].append(public_ip)
  userdict[instance_id].append(key_name)
  userdict[instance_id].append(user)
  userdict[instance_id].append(private_key)


print(userdict)

