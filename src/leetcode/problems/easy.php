<?php
//给定一个 32 位有符号整数，将整数中的数字进行反转。
//示例 1:
//输入: 123
//输出: 321
// 示例 2:
//输入: -123
//输出: -321
//示例 3:
//输入: 120
//输出: 21

function reverse($x)
{
    $result = 0;
    while ($x != 0) {
        $pop = $x % 10;
        $x   = intval($x / 10);
        if (
            $result > PHP_INT_MAX / 10 ||
            $result < PHP_INT_MIN / 10
        ) {
            return 0;
        }
        $result = $result * 10 + $pop;
    }

    return $result;
}

//var_dump(reverse(-123));