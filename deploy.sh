#!/bin/bash -xe
#70e92c02733fe70ac3f176ab3c7b5b0f2f9a4b7f
lftp sftp://${SFTP_USERNAME}:${SFTP_PASSWORD}@${SFTP_HOSTNAME} -e '
mirror -v -R ./${THEME_NAME} ./wp-content/themes/${THEME_NAME}
quit
'