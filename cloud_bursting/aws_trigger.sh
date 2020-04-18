#!/bin/bash

INPUT_PARSER="/var/www/html/vcl-2.5.1/cloud_bursting/parse_input.py"
AWS_ANSIBLE="/var/www/html/vcl-2.5.1/cloud_bursting/ansible/src/create_aws_instance.yml"
VAULT="/var/www/html/vcl-2.5.1/cloud_bursting/ansible/keys/vault_secret.sh"
SESSION="/var/www/html/vcl-2.5.1/cloud_bursting/user_data/session.txt"
LOG_FILE="/var/www/html/vcl-2.5.1/cloud_bursting/ansible/logs/ansible_log"

parse_input()
{
  if [ -f "$1" ]
  then
    python3 "$1"
    if [ $? -ne 0 ]
    then 
      echo "Parser script execution error"
    fi
  else
    echo "File not found"
    exit 1
  fi
}

fetch_user()
{
  if [ -f "$1" ]
  then
    USER_NAME=$(awk -F '@' '{print $1}' $1)
  else
    echo "Session file not found"
    exit 1
  fi
}

aws_call()
{
  if [[ -f "$1" ]] && [[ -f "$2" ]]
  then
    ansible-playbook "$1" --extra-vars "user=$3" --vault-password-file "$2" -v >> "$4"
    if [ $? -ne 0 ]
    then 
      echo "Ansible playbook execution error"
    fi
  else
    echo "AWS files not found"
    exit 1
  fi
}

################ Main ################

parse_input "$INPUT_PARSER"
fetch_user "$SESSION"
aws_call "$AWS_ANSIBLE" "$VAULT" "$USER_NAME" "$LOG_FILE"
