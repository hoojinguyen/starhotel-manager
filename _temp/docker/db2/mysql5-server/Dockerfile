FROM centos:6

# update system
RUN yum -y update

# install mysql
RUN yum -y install mysql mysql-server

# config mysql
RUN rm -rf /etc/my.cnf
COPY ./my.cnf /etc/
RUN chmod 400 /etc/my.cnf

COPY ./startup.sh /startup.sh
RUN chmod +x /startup.sh

# remove cache
RUN yum clean all

EXPOSE 3306

ENV MYSQL_ROOT_PASSWORD centos

#ENTRYPOINT ["/startup.sh"]

