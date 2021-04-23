#!/bin/bash
#if [ ! -f /var/lib/mysql/ibdata1 ]; then

#    chown -R mysql:mysql /var/lib/mysql
#    mysql_install_db --user mysql

#    /usr/bin/mysqld_safe &
 #   sleep 15s

#    echo "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY #'$MYSQL_ROOT_PASSWORD'" | mysql

#    killall mysqld
#    sleep 15s
#fi

#/usr/bin/mysqld_safe



    echo "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY '$MYSQL_ROOT_PASSWORD'" | mysql

