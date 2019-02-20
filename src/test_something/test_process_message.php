<?php

/**
 * shmop 共享内存
 *
 */

//信号量 共享内存直线数据通信

//1、创建共享内存区域
$shm_key = ftok(__FILE__, 't');
$shm_id  = shm_attach($shm_key, 1024, 0655);
const SHARE_KEY = 1;
$childList = [];

//加入信号量
$sem_id = ftok(__FILE__, 's');
$signal = sem_get($sem_id);

//2、开3个进程 读写 该内存区域
for ($i = 0; $i < 3; $i++) {

    $pid = pcntl_fork();
    if ($pid == -1) {
        exit('fork fail!' . PHP_EOL);
    } else if ($pid == 0) {

        // 获得信号量
        sem_acquire($signal);

        //子进程从共享内存块中读取 写入值 +1 写回
        if (shm_has_var($shm_id, SHARE_KEY)) {
            // 有值,加一
            $count = shm_get_var($shm_id, SHARE_KEY);
            $count++;
            //模拟业务处理逻辑延迟
            $sec = rand(1, 3);
            sleep($sec);

            shm_put_var($shm_id, SHARE_KEY, $count);
        } else {
            // 无值,初始化
            $count = 0;
            //模拟业务处理逻辑延迟
            $sec = rand(1, 3);
            sleep($sec);

            shm_put_var($shm_id, SHARE_KEY, $count);
        }

        echo "child process " . getmypid() . " is writing ! now count is $count\n";
        // 用完释放
        sem_release($signal);
        exit("child process " . getmypid() . " end!\n");
    } else {
        $childList[$pid] = 1;
    }
}

// 等待所有子进程结束
while (!empty($childList)) {
    $childPid = pcntl_wait($status);
    if ($childPid > 0) {
        unset($childList[$childPid]);
    }
}

//父进程读取共享内存中的值
$count = shm_get_var($shm_id, SHARE_KEY);
echo "final count is " . $count . PHP_EOL;


//3、去除内存共享区域
#从系统中移除
shm_remove($shm_id);
#关闭和共享内存的连接
shm_detach($shm_id);


//共享内存通信

//1、创建共享内存区域
//$shm_key = ftok(__FILE__, 't');
//$shm_id  = shm_attach($shm_key, 1024, 0655);
//const SHARE_KEY = 1;
//$childList = [];
//
////2、开3个进程 读写 该内存区域
//for ($i = 0; $i < 3; $i++) {
//
//    $pid = pcntl_fork();
//    if ($pid == -1) {
//        exit('fork fail!' . PHP_EOL);
//    } else if ($pid == 0) {
//
//        //子进程从共享内存块中读取 写入值 +1 写回
//        if (shm_has_var($shm_id, SHARE_KEY)) {
//            // 有值,加一
//            $count = shm_get_var($shm_id, SHARE_KEY);
//            $count++;
//            //模拟业务处理逻辑延迟
//            $sec = rand(1, 3);
//            sleep($sec);
//
//            shm_put_var($shm_id, SHARE_KEY, $count);
//        } else {
//            // 无值,初始化
//            $count = 0;
//            //模拟业务处理逻辑延迟
//            $sec = rand(1, 3);
//            sleep($sec);
//
//            shm_put_var($shm_id, SHARE_KEY, $count);
//        }
//
//        echo "child process " . getmypid() . " is writing ! now count is $count\n";
//
//        exit("child process " . getmypid() . " end!\n");
//    } else {
//        $childList[$pid] = 1;
//    }
//}
//
//// 等待所有子进程结束
//while (!empty($childList)) {
//    $childPid = pcntl_wait($status);
//    if ($childPid > 0) {
//        unset($childList[$childPid]);
//    }
//}
//
////父进程读取共享内存中的值
//$count = shm_get_var($shm_id, SHARE_KEY);
//echo "final count is " . $count . PHP_EOL;
//
//
////3、去除内存共享区域
//#从系统中移除
//shm_remove($shm_id);
//#关闭和共享内存的连接
//shm_detach($shm_id);


//$shm_key = ftok(__FILE__, 't');
//
///**
// * 开辟一块共享内存
// *
// * int $key , string $flags , int $mode , int $size
// * $flags: a:访问只读内存段
// * c:创建一个新内存段，或者如果该内存段已存在，尝试打开它进行读写
// * w:可读写的内存段
// * n:创建一个新内存段，如果该内存段已存在，则会失败
// * $mode: 八进制格式  0655
// * $size: 开辟的数据大小 字节
// */
//
//$shm_id = shmop_open($shm_key, "c", 0655, 1024);
//
///**
// * 写入数据 数据必须是字符串格式 , 最后一个指偏移量
// * 注意：偏移量必须在指定的范围之内，否则写入不了
// *
// */
//$size = shmop_write($shm_id, 'hello world', 0);
//echo "write into {$size}";
//
//echo "\r\n";
//
//#读取的范围也必须在申请的内存范围之内,否则失败
//$data = shmop_read($shm_id, 0, 1025);
//var_dump($data);
//
//#删除 只是做一个删除标志位，同时不在允许新的进程进程读取，当在没有任何进程读取时系统会自动删除
//shmop_delete($shm_id);
//
//#关闭该内存段
//shmop_close($shm_id);