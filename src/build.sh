#!/bin/bash

set -x

#######################################
# Ren,Wenyue<wenyue1@staff.sina.com.cn>
#######################################

show_help_and_exit() {
cat <<HELP
stage param must be specified
eg:
    ./build.sh [dev|test|sim|onlineyz|onlineyf]
HELP
    exit -1
}

if [ $# -lt 1 ]; then
    show_help_and_exit;
fi

CURRENT_WD=`pwd`
ENV_STAGE=$1
CONFIG_FROM_BASE='./system/SINASRV_CONFIG_'
CONFIG_TARGET='./system/SINASRV_CONFIG'

if [ $ENV_STAGE = 'dev' ]
then
    CONFIG_FROM=$CONFIG_FROM_BASE'DEV'
elif [ $ENV_STAGE = 'test' ]
then
    CONFIG_FROM=$CONFIG_FROM_BASE'TEST'
elif [ $ENV_STAGE = 'sim' ]
then
    CONFIG_FROM=$CONFIG_FROM_BASE'SIM'
elif [ $ENV_STAGE = 'onlineyz' ]
then
    CONFIG_FROM=$CONFIG_FROM_BASE'ONLINE_YZ'
elif [ $ENV_STAGE = 'onlineyf' ]
then
    CONFIG_FROM=$CONFIG_FROM_BASE'ONLINE_YF'
else
    show_help_and_exit;
fi

if [ ! -e $CONFIG_FROM ]
then
    echo $ENV_STAGE' CONFIG NOT FOUND: '$CONFIG_FROM
    show_help_and_exit;
else
    echo $ENV_STAGE' config file '$CONFIG_FROM' exists, start to replace configs'
fi

chmod -R 777 ./logs

#fix it
mv ./conf/promotedaccounts.ini ./conf/promotedaccounts.ini.dev
mv ./conf/promotedaccounts.ini.online ./conf/promotedaccounts.ini

mv ./conf/goods.ini ./conf/goods.ini.dev
mv ./conf/goods.ini.online ./conf/goods.ini


rm -rf $CONFIG_TARGET
cp $CONFIG_FROM $CONFIG_TARGET
