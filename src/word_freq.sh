#!/usr/bin/env bash

if [ $# -ne 1 ]
then
    echo "Usage: $0 filename";
    exit -1;
fi

filename=$1

egrep -o "\b[[:alpha:]]+\b" $filename | awk '{ count[$0]++ }
END{
    printf ("%-30s%s\n","Word","Count");
    for(ind in count){
        printf("%-30s%d\n",ind,count[ind]);
    }
}';