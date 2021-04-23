FROM debian:9

#meta
RUN apt-get update

#upgrade
RUN apt-get -y upgrade

#mariadb
RUN apt-get -y install mariadb-server

# remove cache

#config
RUN rm -rf /etc/mysql/mariadb.conf.d/50-server.cnf
COPY ./50-server.cnf /etc/mysql/mariadb.conf.d/
RUN chmod 644 /etc/mysql/mariadb.conf.d/50-server.cnf

COPY ./startup.sh /startup.sh
RUN chmod +x /startup.sh

ENV MYSQL_ROOT_PASSWORD debian

EXPOSE 3306
ENTRYPOINT ["/startup.sh"]
