#!/usr/bin/env bash
pwd
echo $WORKSPACE
PUBLISH_DATE=`date +%Y-%m-%d_%H-%M`
PATH_ROOT=/Users/haicheng/developer/wwwroot/sina_app
PATH_PHP=php

# $PATH_PHP -r "echo exec('find . -type d -iname ".svn" -exec rm -rf {} \; ');"

#将老的代码备份一下
mkdir -p $PATH_ROOT/app_new
mkdir -p $PATH_ROOT/app
mkdir -p $PATH_ROOT/bak/app_bak_$PUBLISH_DATE/src/storage/logs
cp -r $PATH_ROOT/app $PATH_ROOT/bak/app_bak_$PUBLISH_DATE

# 开始移动1
cp -r $PATH_ROOT/trunk/* $PATH_ROOT/app_new

# 处理storage
rm -rf $PATH_ROOT/app_new/src/storage/logs
mv $PATH_ROOT/app/src/storage/logs $PATH_ROOT/app_new/src/storage
chmod -R 777 $PATH_ROOT/app_new/src/storage

# 开始移动2
rm -r $PATH_ROOT/app
mv $PATH_ROOT/app_new $PATH_ROOT/app

# 更新配置文件
rm $PATH_ROOT/app/src/.env
mv $PATH_ROOT/app/src/.env.online $PATH_ROOT/app/src/.env
sed -ig "s/REPLACE_VERSION/${BUILD_ID}$/1" $PATH_ROOT/app/src/.env

echo 'PUBLISH FINISH!!'