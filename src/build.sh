#!/bin/bash

set -x

show_help_and_exit() {
cat <<HELP
stage param must be specified
eg:
    ./build.sh [dev]
HELP
    exit -1
}

if [ $# -lt 1 ]; then
    show_help_and_exit;
fi

#psr0 规范格式化代码
PHP_CS_FIXER=/Users/haicheng/developer/wwwroot/ceshi/src/shell/php-cs-fixer.phar
PROJECT_PATH=/Users/haicheng/developer/wwwroot/ceshi/src/application/

php ${PHP_CS_FIXER} fix ${PROJECT_PATH} --level=psr0
