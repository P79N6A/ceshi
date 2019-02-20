<?php

$images = [
    '750_126' => [
        'url' => 'http://u1.img.mobile.sina.cn/public/files/image/750x126_img5b7bdf39926da.png',
    ],
    '750_76' => [
        'url' => 'http://u1.img.mobile.sina.cn/public/files/image/750x76_img5b7bdf41b04da.png',
    ],
    '720_180' => [
        'url' => 'http://u1.img.mobile.sina.cn/public/files/image/720x180_img5b7ab0c182bca.png',
    ],
    '720_120' => [
        'url' => 'http://u1.img.mobile.sina.cn/public/files/image/720x120_img5b768db5b4cb4.png',
    ],
    '720_72' => [
        'url' => 'http://u1.img.mobile.sina.cn/public/files/image/720x72_img5b768db55dc23.png',
    ],
];

echo json_encode($images);
exit;





$json = json_decode('{"campaign_id":"http://kadm.test.weibo.com/ajax/tool/down_video_by_id.json?object_id=1034:4333768270657944&name=1548734340.mp4","campaign_id2":"http://kadm.test.weibo.com/ajax/tool/down_video_by_id.json?object_id=1034:4333768685895996&name=1548734441.mp4"}',
    true);


foreach ($json as $key => $item) {

    if (empty($item) || !is_string($item)) {
        continue;
    }

    $ext = substr($item, -3, 3);
    if ('mp4' === $ext) {
        echo $item . "\r\n";
        echo "is taking \r\n";

        $result = file_get_contents($item);
        if (empty($result)) {
            echo 'bad video = ' . $key;
        } else {
            echo strlen($result) . "\r\n";
            file_put_contents('/Users/haicheng/developer/php/ceshi/src/test_something/' . time() . '.mp4', $result);
        }
    }
}

echo 'done';
