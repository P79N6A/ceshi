#!/usr/bin/env bash
pwd
echo ${WORKSPACE}
echo ${JENKINS_HOME}
echo ${NODE_NAME}
echo ${NODE_LABELS}
echo ${DATA_DIR} 

PUBLISH_DATE=`date +%Y-%m-%d_%H-%M`
PATH_ROOT=/data1/htdocs
FOLDER_NEW=${PATH_ROOT}/app_new
FOLDER_OLD=${PATH_ROOT}/app
FOLDER_BAK=${PATH_ROOT}/bak/app_bak_${PUBLISH_DATE}

rm -rf `find . -type d -name .svn`

if [ ! -d "$FOLDER_NEW" ]; then
  mkdir -p "$FOLDER_NEW"
fi

if [ ! -d "$FOLDER_OLD" ]; then
  mkdir -p "$FOLDER_OLD"
fi

if [ ! -d "$FOLDER_BAK" ]; then
  mkdir -p "$FOLDER_BAK"
fi

##配置文件替换
if [ "$NODE_NAME" == "beixian10.13.4.163" ]; then
  mv ./src/.env.cron ./src/.env
else
  mv ./src/.env.online ./src/.env
fi	

##替换版本号
sed -ig 's/REPLACE_VERSION/'$PUBLISH_DATE'/' ./src/.env

##备份,删除日志
cp -r $FOLDER_OLD/src $FOLDER_BAK
rm -r $FOLDER_BAK/src/storage/logs

##替换目录
cp -r ./ $FOLDER_NEW
rm -r $FOLDER_NEW/src/storage

mv $FOLDER_OLD ${FOLDER_OLD}_jenkins_tmp_folder
mv $FOLDER_NEW $FOLDER_OLD
mv ${FOLDER_OLD}_jenkins_tmp_folder/src/storage $FOLDER_OLD/src
##目录写权限
chmod -R 777 $FOLDER_OLD/src/storage
rm -r ${FOLDER_OLD}_jenkins_tmp_folder

echo 'PUBLISH FINISH!!'