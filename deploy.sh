#!/usr/bin/env bash

# load config
export $(cat ./config/.env-prod)

# run sftp batch file, filling in password when asked
expect -c "
spawn sftp -o \"BatchMode=no\" -o PasswordAuthentication=yes -o PreferredAuthentications=keyboard-interactive,password -o PubkeyAuthentication=no -b deployment_batchfile facilik@www548.your-server.de
expect -nocase \"*password:\" { send \"$SFTP_PASSWORD\r\"; interact }
"
