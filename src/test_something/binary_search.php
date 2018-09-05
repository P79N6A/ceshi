<?php
/**
 * 二分查找
 *
 * @author haicheng
 *
 * @param $array
 * @param $min
 * @param $max
 * @param $target
 *
 * @return float
 */


function binarySearch($array, $min, $max, $target)
{

    $middle = floor(($min + $max) / 2);
    if ($array[$middle] === $target) {
        return $array[$middle];
    } elseif ($array[$middle] > $target) {
        return binarySearch($array, $min, $middle, $target);
    } elseif ($array[$middle] < $target) {
        return binarySearch($array, $middle, $max, $target);
    }

    return false;
}


$array = [1, 4, 6, 89, 100, 233, 567, 999];

var_dump(binarySearch($array, 0, count($array), 4));
