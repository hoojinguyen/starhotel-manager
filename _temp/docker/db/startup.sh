#!/bin/bash
if [ ! -f /var/lib/mysql/ibdata1 ]; then

    chown -R mysql:mysql /var/lib/mysql
    mysql_install_db --user mysql

    /usr/bin/mysqld_safe &
    sleep 5s

    echo "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY '$MYSQL_ROOT_PASSWORD'" | mysql

    killall mysqld
    sleep 5s
fi

/usr/bin/mysqld_safe
