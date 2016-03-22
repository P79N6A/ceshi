#!/bin/bash
echo "#######################################"
##启动nginx
ser=`/usr/bin/pgrep nginx`
if [ "$ser" != "" ]
then
    echo "nginx exist! I will reload it. "
    sudo /usr/local/bin/nginx -s stop
fi
sudo -S /usr/local/bin/nginx
    echo "nginx start success!"

echo "#######################################"
##启动php-fpm
ser=`/usr/bin/pgrep php-fpm`
if [ "$ser" != "" ]
then
    echo "php-fpm exist! I will reload it. "
    sudo killall php-fpm
fi
sudo -S /usr/local/opt/php55/sbin/php-fpm
    echo "php-fpm start success!"
