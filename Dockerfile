FROM ubuntu:14.04
MAINTAINER ijry <598821125@qq.com>

# Keep upstart from complaining
RUN dpkg-divert --local --rename --add /sbin/initctl
RUN ln -sf /bin/true /sbin/initctl

# Let the conatiner know that there is no tty
ENV DEBIAN_FRONTEND noninteractive

# 更换软件源并更新
ADD ./Docker/sources.list /etc/apt/sources.list
RUN apt-get update
RUN apt-get -y upgrade

# 基础依赖
RUN apt-get -y install curl git git-core unzip nginx php5 php5-fpm php5-mysql mysql-server mysql-client python-setuptools

# 零云依赖
RUN apt-get -y install php5-curl php5-gd

# mysql config
RUN sed -i -e"s/^bind-address\s*=\s*127.0.0.1/bind-address = 0.0.0.0/" /etc/mysql/my.cnf

# nginx config
RUN sed -i -e"s/keepalive_timeout\s*65/keepalive_timeout 2/" /etc/nginx/nginx.conf
RUN sed -i -e"s/keepalive_timeout 2/keepalive_timeout 2;\n\tclient_max_body_size 100m/" /etc/nginx/nginx.conf
RUN echo "daemon off;" >> /etc/nginx/nginx.conf

# php-fpm config
RUN sed -i -e "s/;cgi.fix_pathinfo=0/cgi.fix_pathinfo=1/g" /etc/php5/fpm/php.ini
RUN sed -i -e "s/upload_max_filesize\s*=\s*2M/upload_max_filesize = 100M/g" /etc/php5/fpm/php.ini
RUN sed -i -e "s/post_max_size\s*=\s*8M/post_max_size = 100M/g" /etc/php5/fpm/php.ini
RUN sed -i -e "s/;daemonize\s*=\s*yes/daemonize = no/g" /etc/php5/fpm/php-fpm.conf
RUN sed -i -e "s/;catch_workers_output\s*=\s*yes/catch_workers_output = yes/g" /etc/php5/fpm/pool.d/www.conf

# nginx site conf
ADD ./Docker/nginx-site.conf /etc/nginx/sites-available/default

# Supervisor Config
RUN /usr/bin/easy_install supervisor
RUN /usr/bin/easy_install supervisor-stdout
ADD ./Docker/supervisord.conf /etc/supervisord.conf


# 安装零云
RUN git clone http://git.corethink.cn/admin/corethink.git  /usr/share/nginx/www

# 配置nginx环境变量便于直接使用零云而无需安装
RUN sed -i '/include\sfastcgi_params;/a    fastcgi_param OC_DB_PREFIX "oc_";' /etc/nginx/sites-available/default
RUN sed -i '/include\sfastcgi_params;/a    fastcgi_param OC_DB_PWD "lingyun";' /etc/nginx/sites-available/default
RUN sed -i '/include\sfastcgi_params;/a    fastcgi_param OC_DB_NAME "corethink";' /etc/nginx/sites-available/default
RUN sed -i '/include\sfastcgi_params;/a    fastcgi_param OC_DEV_MODE "true";' /etc/nginx/sites-available/default


# 目录权限设置
RUN chown -R www-data:www-data /usr/share/nginx/www

# Wordpress Initialization and Startup Script
ADD ./Docker/start.sh /start.sh
RUN chmod 755 /start.sh

# private expose
EXPOSE 3306
EXPOSE 80

# 持久化存储数据，容器关闭后只有这里指定的目录数据会保存更改
VOLUME ["/var/lib/mysql", "/usr/share/nginx/www"]

CMD ["/bin/bash", "/start.sh"]
