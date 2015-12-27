<?php namespace Type;


use \Base\Type;

class CreativeType extends Type {
    const SUMMERY_CATEGORY = 1;

    const SUMMERY_SIZE = 2;

    const SUMMERY_SCORE = 3;

    const SUMMERY_DOWNLOAD = 4;

    const SUMMERY_CUSTOM = 5;

    const DELETE = -9;

    const FEED_DELETE = -9;

    const COMMON = 0;

    const DISABLE_COMMENT = 1;

    const ENABLE_COMMENT = 0;

    const AUDIT_WAIT = 0;

    const AUDIT_PASS = 1;

    const AUDIT_REFUSE = 2;

    const CARD = 1; // 大卡片

    const NINE = 2; // 九宫格

    private $_mapping = array(
        'CARD'  => '大卡片',
        'NINE'  => '9宫格',
        'SUMMERY_CLASSIFY'  => '市场分类',
        'SUMMERY_SIZE'  => '大小',
        'SUMMERY_SCORE'  => '评分',
        'SUMMERY_DOWNLOAD'  => '下载量',
        'SUMMERY_CUSTOM'  => '自定义',
        'FEED_DELETE'  => 'FEED 删除',
    );
}
