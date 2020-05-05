#!/bin/bash

INPUT_PARSER="/var/www/html/vcl-2.5.1/cloud_bursting/parse_input.py"
AWS_ANSIBLE="/var/www/html/vcl-2.5.1/cloud_bursting/ansible/src/create_aws_instance.yml"
VAULT="/var/www/html/vcl-2.5.1/cloud_bursting/ansible/keys/vault_secret.sh"
SESSION="/var/www/html/vcl-2.5.1/cloud_bursting/user_data/session.txt"
LOG_FILE="/var/log/cloud_bursting/logs"
LOG_DIR="/var/log/cloud_bursting/"
AWS_OUTPUT="/var/www/html/vcl-2.5.1/cloud_bursting/ansible/var/"
AWS_DB="/var/www/html/vcl-2.5.1/cloud_bursting/write_table.py"

create_log_directory()
{
  mkdir -p -m 777 "$LOG_DIR"
  if [ ! -f "$LOG_FILE" ]
  then
    touch "$LOG_FILE"
  fi
}

parse_input()
{
  if [ -f "$1" ]
  then
    echo >> "$LOG_FILE"
    echo "Parsing user data.." >> "$LOG_FILE"
    echo >> "$LOG_FILE"
    python3 "$1" >> "$LOG_FILE"
    if [ $? -ne 0 ]
    then 
      echo "Parser script execution error" >> "$LOG_FILE"
      exit 1
    else
      echo "Parsing done!" >> "$LOG_FILE"
    fi
  else
    echo "File not found: parse_input.py" >> "$LOG_FILE"
    exit 1
  fi
}

fetch_user()
{
  if [ -f "$1" ]
  then
    echo >> "$LOG_FILE"
    echo "Fetching current user name.." >> "$LOG_FILE"
    echo >> "$LOG_FILE"
    USER_NAME=$(awk -F '@' '{print $1}' $1)
    echo "Done fetching!" >> "$LOG_FILE"
  else
    echo "Session file not found: session.txt" >> "$LOG_FILE"
    exit 1
  fi
}

aws_call()
{
  if [[ -f "$1" ]] && [[ -f "$2" ]]
  then
    echo  >> "$LOG_FILE"
    echo "Executing playbook.." >> "$LOG_FILE"
    echo >> "$LOG_FILE"
    ansible-playbook "$1" --extra-vars "user=$3" --vault-password-file "$2" -v >> "$LOG_FILE"
    if [ $? -ne 0 ]
    then 
      echo "Ansible playbook execution error" >> "$LOG_FILE"
      exit 1
    else
      echo "Playbook executed successfully!" >> "$LOG_FILE"
    fi
  else
    echo "AWS files not found" >> "$LOG_FILE"
    exit 1
  fi
}

update_aws_db()
{
  sleep 5
  if [[ -f "$1" ]] && [[ -f "$2" ]]
  then
    echo >> "$LOG_FILE"
    echo "Writing to AWS database.." >> "$LOG_FILE"
    echo >> "$LOG_FILE"
    python3 "$1" "$2" >> "$LOG_FILE"
    if [ $? -ne 0 ]
    then
      echo "AWS DB script execution error" >> "$LOG_FILE"
      exit 1
    else
      echo >> "$LOG_FILE"
      echo "Write successful!" >> "$LOG_FILE"
    fi
  else
    echo "File not found: write_table.py or $2" >> "$LOG_FILE"
    exit 1
  fi
}

################ Main ################

create_log_directory 
parse_input "$INPUT_PARSER"
fetch_user "$SESSION"
aws_call "$AWS_ANSIBLE" "$VAULT" "$USER_NAME"
USER_INSTANCE="$AWS_OUTPUT""$USER_NAME""_instances.yml"
update_aws_db "$AWS_DB" "$USER_INSTANCE"
