#!/usr/bin/env bash

# load config
export $(cat ./config/.env-prod)

# run sftp batch file, filling in password when asked
expect -c "
spawn sftp -o \"BatchMode=no\" -b deployment_batchfile u73821852-fwapp@home475696686.1and1-data.host
expect -nocase \"*password:\" { send \"$SFTP_PASSWORD\r\"; interact }
"
