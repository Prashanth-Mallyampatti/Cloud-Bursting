# Cloud-Bursting

## Designing of Cloud Bursting from VCL to Amazon EC2

In this project, we implemented a solution to enhance the current VCL architecture to handle resource scarcity due to excessive demand on the system. We architected, developed and integrated a new cloudbusting provisioning framework that enables the system to detect excessive demand from a user and burst any extra demand to AWS EC2 public cloud seamlessly. We implemented the feature to be transparent to the user and that bursting only happens in the case of resource scarcity or load spikes. We also ensured that multiple users are able to reserve VMs at the same time on AWS. We provide the user the functionality to delete provisioned EC2 instances and remove all data that has been entered by the user. We also introduce a basic IAM system in which an admin user can seamlessly stop a malicious user’s AWS instances.

### System Setup:
Follow the instructions in file `vcl_setup.pdf` placed in this repository. This sets the base VCL system in your sandbox.

Setup Environment for Cloud Bursting:

1. Install Ansible:
    ```
    yum update
    yum install epel-release 
    yum install ansible
    ```
2. Install Python 3, Boto3 packages and mySQL packages:
    ```
    yum install centos-release-scl
    yum install rh-python36
    yum install python-pip
    pip install boto boto3
    pip install mysql-connector-python-rf
    ```
 3. Set SELinux as permissive (for Centos):
    ```
    /usr/sbin/setenforce Permissive
    ```
 4. Clone and Copy the contents of this repository:
    ```
    yes | cp -rf /root/Cloud-Bursting/* /var/www/html/vcl-2.5.1/
    yes | cp -rf /root/Cloud-Bursting/.ht-inc/* /var/www/html/vcl-2.5.1/.ht-inc/
    ```
 5. Set Persmissions:
    ```
    chmod 777 /var/www/html/vcl-2.5.1
    chmod -R 777 /var/www/html/vcl-2.5.1/cloud_bursting
    chmod -R 777 /var/log/cloud_bursting/
    ```
    Any Ansible permission errors, please set the persmissions to 777 to the file or directory pointed in ansible logs or in httpd error logs.
    
 6. Add AWS access and secret keys in `cloud_bursting/ansible/keys/aws_keys.yml` and feed the password from `cloud_bursting/ansible/keys/vault_secret.sh`.  
    ```
    ansible-vault create ansible/keys/aws_keys.yml
    ```
    Set your password and add Access and secret keys to the file. When viewed it should look like this:
    ```
    [root@mn cloud_bursting]# ansible-vault view ansible/keys/aws_keys.yml
    Vault password:
    aws_access_key: AKIAJGC4RPZZXEOMAYDQ
    aws_secret_key: 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
    ```
