import yaml
import json
import os
import sys

class Create_Input_File():
  def parseUserData(self, user_file, session_file, request_count_file):
    self.user_file = user_file
    self.session_file = session_file
    self.request_count_file = request_count_file

    with open(self.user_file, 'r') as file:
      try:
        user_data = json.load(file)
      except OSError:
        print("File not found or cannot open file")

    with open(self.session_file, 'r') as file:
      try:
        session_data = file.read()
      except OSError:
        print("File not found or cannot open file")

    with open(self.request_count_file, 'r') as file:
      try:
        request_count = file.read()
      except OSError:
        print("File not found or cannot open file")

    self.user_name = user_data[session_data]["userid"].split('@')[0]
    self.image_name = user_data[session_data]["requestDetails"][0]["prettyimage"]
    
    if "Centos" in self.image_name:
      self.image_id = "ami-0c322300a1dd5dc79"  # Centos 8
    else:
      self.image_id = "ami-07ebfd5b3428b6f4d"  # Ubuntu 18

    self.key_name = self.user_name + "_ec2_key"

    request_count = int(request_count) + 1;
    self.instance_name = self.user_name + "_ec2_" + str(request_count);

    with open(self.request_count_file, 'w') as file:
      try:
        file.write(str(request_count))
      except OSError:
        print("File not found or cannot open file")
   
  def dumpData(self, aws_data_file):
    self.aws_data_file = aws_data_file
    aws_contents = []
    contents = {}

    with open(self.aws_data_file, 'r') as file:
      try:
        aws_data = yaml.safe_load(file)
      except OSError:
        print("File not found ot cannot open file")

    contents["user"] = self.user_name
    contents["instance_name"] = self.instance_name
    contents["key_name"] = self.key_name
    contents["image_id"] = self.image_id
    aws_contents.append(contents)
    
    self.instance = {}
    self.instance["AWS"] = aws_contents
    
    with open(self.aws_data_file, "w") as file:
      doc = yaml.dump(self.instance, file, default_flow_style=False)

def main():
  
  user_file = "/var/www/html/vcl-2.5.1/cloud_bursting/user_data/user_file.json"
  session_file = "/var/www/html/vcl-2.5.1/cloud_bursting/user_data/session.txt"
  request_count_file = "/var/www/html/vcl-2.5.1/cloud_bursting/request_count.txt"
  aws_data_file = "/var/www/html/vcl-2.5.1/cloud_bursting/ansible/etc/aws_instance.yml"

  obj = Create_Input_File()
  obj.parseUserData(user_file, session_file, request_count_file)
  obj.dumpData(aws_data_file)
main()
