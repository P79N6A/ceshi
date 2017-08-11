#!/bin/bash

set -x

git add .
git commit -am 'upgrade project og test'
git push

# ----- 帮助信息 -----
#show_help_and_exit() {
#cat <<HELP
#stage param must be specified
#eg:
#    ./build.sh [dev]
#HELP
#    exit -1
#}
#
#if [ $# -lt 1 ]; then
#    show_help_and_exit;
#fi
#
## ----- 项目地址 -----
#PROJECT_PATH=${PWD}/
#
## ----- psr0规范格式化代码 -----
#PHP_CS_FIXER=${PROJECT_PATH}/shell/php-cs-fixer.phar
#php ${PHP_CS_FIXER} fix ${PROJECT_PATH}/application/ --level=psr0
#
## ----- 测试用例运行 -----
#PHPUNIT=${PROJECT_PATH}/shell/phpunit-4.8.24.phar
#TEST_PATH=${PROJECT_PATH}/tests
#PHPUNIT ${TEST_PATH} --configuration ${TEST_PATH}/phpunit.xml