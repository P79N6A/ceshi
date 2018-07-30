<?php
/**
 * 初级算法 数组
 */
//从排序数组中删除重复项
//给定数组 nums = [1,1,2],
//函数应该返回新的长度 2, 并且原数组 nums 的前两个元素被修改为 1, 2。
//你不需要考虑数组中超出新长度后面的元素。
function removeDuplicates($nums = [])
{
    if (empty($nums)) {
        return;
    }
    $i = 0;
    for ($j = 1; $j < count($nums); $j++) {
        if ($nums[$i] !== $nums[$j]) {
            $i++;
            $nums[$i] = $nums[$j];
        }
    }

    return $i + 1;
}

print_r(removeDuplicates([1, 1, 2]));

//买卖股票的最佳时机 II
//输入: [7,1,5,3,6,4]
//输出: 7
//解释: 在第 2 天（股票价格 = 1）的时候买入，在第 3 天（股票价格 = 5）的时候卖出, 这笔交易所能获得利润 = 5-1 = 4 。
//     随后，在第 4 天（股票价格 = 3）的时候买入，在第 5 天（股票价格 = 6）的时候卖出, 这笔交易所能获得利润 = 6-3 = 3 。

// todo 还没有答案
function maxProfit($price = [])
{

    $profit = [];
    $count  = count($price);
    for ($i = 0; $i < $count; $i++) {
        for ($j = $i; $j < $count; $j++) {
            $profit[$i . $j] = $price[$j] - $price[$i];
        }
    }
    sort($profit);
    // 算出结果
}

//旋转数组
//输入: [1,2,3,4,5,6,7] 和 k = 3
//输出: [5,6,7,1,2,3,4]
//解释:
//向右旋转 1 步: [7,1,2,3,4,5,6]
//向右旋转 2 步: [6,7,1,2,3,4,5]
//向右旋转 3 步: [5,6,7,1,2,3,4]

// todo 还没有答案
//递归的解法
function rotate($array = [], $k)
{

}