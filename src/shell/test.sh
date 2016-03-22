#!/bin/bash

PUBLISH_DATE=`date +%Y-%m-%d_%H-%M`
echo $PUBLISH_DATE


sed -ig 's/cui$PUBLISH_DATE'/'  test.txt