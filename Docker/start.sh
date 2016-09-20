#!/bin/bash

# mysql需要这样启动因为/etc/init.d无法使用
/usr/bin/mysqld_safe &
sleep 3s

# 设置mysql密码
MYSQL_PASSWORD=lingyun
echo mysql root password: $MYSQL_PASSWORD

# 设置mysql权限
mysqladmin -uroot password $MYSQL_PASSWORD
mysql -uroot -p$MYSQL_PASSWORD -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY '$MYSQL_PASSWORD' WITH GRANT OPTION; FLUSH PRIVILEGES;"
mysql -uroot -p$MYSQL_PASSWORD -e "CREATE DATABASE corethink; GRANT ALL PRIVILEGES ON corethink.* TO 'root'@'％' IDENTIFIED BY '$MYSQL_PASSWORD'; FLUSH PRIVILEGES;"
mysql -uroot -p$MYSQL_PASSWORD corethink < /usr/share/nginx/www/Application/Install/Data/install.sql
killall mysqld

# 启动所有服务
/usr/local/bin/supervisord -n
