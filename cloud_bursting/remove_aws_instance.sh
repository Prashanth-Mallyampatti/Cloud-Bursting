#!/bin/bash

DELETE_INSTANCE="/var/www/html/vcl-2.5.1/cloud_bursting/ansible/src/delete_aws_instance.yml"
UPDATE_TABLE="/var/www/html/vcl-2.5.1/cloud_bursting/update_table.py"
VAULT="/var/www/html/vcl-2.5.1/cloud_bursting/ansible/keys/vault_secret.sh"
LOG_FILE="/var/log/cloud_bursting/logs"
USER_NAME="$1"
INSTANCE_ID="$2"

delete_aws_instance()
{
  if [[ -f "$1" ]] && [[ -f "$2" ]]
  then
    echo  >> "$LOG_FILE"
    echo "Executing playbook.." >> "$LOG_FILE"
    echo >> "$LOG_FILE"
    ansible-playbook "$1" --extra-vars "instance=$3" --vault-password-file "$2" -v >> "$LOG_FILE"
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

delete_row()
{
  if [ -f "$1" ]
  then
    echo >> "$LOG_FILE"
    echo "Deleting from AWS database.." >> "$LOG_FILE"
    echo >> "$LOG_FILE"
    python3 "$1" "$2" "$3" >> "$LOG_FILE"
    if [ $? -ne 0 ]
    then
      echo "Database update script execution error" >> "$LOG_FILE"
      exit 1
    else
      echo "Database Updated!" >> "$LOG_FILE"
    fi
  else
    echo "File not found: update_table.py" >> "$LOG_FILE"
    exit 1
  fi 
}

############### Main #################

delete_aws_instance "$DELETE_INSTANCE" "$VAULT" "$INSTANCE_ID"
delete_row "$UPDATE_TABLE" "$INSTANCE_ID" "$USER_NAME"
