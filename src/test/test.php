<?php


// cpu:XHPROF_FLAGS_CPU 内存:XHPROF_FLAGS_MEMORY
// 如果两个一起：XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY 
//xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
//
//// 要测试的php代码
//
//
//$data = xhprof_disable();   //返回运行数据
//
//// xhprof_lib在下载的包里存在这个目录,记得将目录包含到运行的php代码中
//include_once "xhprof_lib/utils/xhprof_lib.php";
//include_once "xhprof_lib/utils/xhprof_runs.php";
//
//$objXhprofRun = new XHProfRuns_Default();
//
//// 第一个参数j是xhprof_disable()函数返回的运行信息
//// 第二个参数是自定义的命名空间字符串(任意字符串),
//// 返回运行ID,用这个ID查看相关的运行结果
//$run_id = $objXhprofRun->save_run($data, "xhprof");
//var_dump($run_id);
$image = '["http:\/\/ww3.sinaimg.cn\/large\/9b7f515djw1f0bwcv82phj20yi0yie5r.jpg","http:\/\/ww4.sinaimg.cn\/large\/9b7f515djw1f0bwdjxfp4j20yi0yitam.jpg","http:\/\/ww4.sinaimg.cn\/large\/9b7f515djw1f0bweieiiij20yi0yi0v5.jpg","http:\/\/ww4.sinaimg.cn\/large\/9b7f515djw1f0bwdjxfp4j20yi0yitam.jpg"]';
$tag = '{"9b7f515djw1f0bwcv82phj20yi0yie5r":[{"tag":["\u7acb\u5373\u4e0b\u8f7d"],"tag_type":"app","pos":{"x":0.1595,"y":0.3522},"dir":0}],"9b7f515djw1f0bwcemetaj20yi0yiq5f":[{"tag":["\u7acb\u5373\u6536\u770b"],"tag_type":"app","pos":{"x":0.3615,"y":0.4516},"dir":0}],"9b7f515djw1f0bweieiiij20yi0yi0v5":[{"tag":["\u7acb\u5373\u4f7f\u7528"],"tag_type":"app","pos":{"x":0.6986,"y":0.9072},"dir":0}],"9b7f515djw1f0bwdjxfp4j20yi0yitam":[{"tag":["\u7acb\u5373\u4e0b\u8f7d"],"tag_type":"app","pos":{"x":0.1231,"y":0.392},"dir":0}]}';
$image = json_decode($image, true);
$tag = array_values(json_decode($tag, true));
$tags = array();
if(!empty($image) && !empty($tag)){
    foreach($image as $index => $ival){
        $tags['value'] = $ival;
        foreach($tag[$index] as $tval){
            $tags['label'][] = array(
                'url' => '',
                'tag' => $tval['tag'][0],
            );
        }
    }
}
print_r($tags);

$data['ext'][] = array('image', json_encode(array('value'=>'xxx','label'=>array(array('url'=>'xxx','tag'=>'123123'),array('url'=>'zzz','tag'=>'999')))), 'pic_group');


