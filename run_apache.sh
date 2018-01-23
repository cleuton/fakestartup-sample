#!/bin/bash


set |grep '_PORT_' |tr  '=' ' ' |sed 's/^/SetEnv /' > /etc/apache2/conf-enabled/docker-vars.conf
set |grep '_ENV_' |tr  '=' ' ' |sed 's/^/SetEnv /' >> /etc/apache2/conf-enabled/docker-vars.conf

echo "export SERVER_NAME=$HOSTNAME" >> /etc/apache2/envvars

echo "ServerName $HOSTNAME" > /etc/apache2/conf-enabled/servername.conf

/etc/init.d/apache2 start && \
 tail -F /var/log/apache2/*log