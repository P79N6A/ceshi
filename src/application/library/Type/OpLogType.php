<?php namespace Type;

use \Base\Type;


class OpLogType extends Type
{
    const TARGET_TYPE_ACCOUNT = 1;

    const TARGET_TYPE_CAMPAIGN = 3;

    const TARGET_TYPE_CREATIVE = 6;

    /**
     * 修改账户日限额
     */
    const CONTENT_TYPE_ACCOUNT_BUDGET = 6101;

    /**
     * 创建创意
     */
    const CONTENT_TYPE_CREATIVE_CREATE = 6201;

    /**
     * 删除创意
     */
    const CONTENT_TYPE_CREATIVE_DELETE = 6202;

    /**
     * 修改评论状态
     */
    const CONTENT_TYPE_CREATIVE_COMMENT = 6203;

    /**
     * 新建计划
     */
    const CONTENT_TYPE_CAMPAIGN_CREATE = 6301;

    /**
     * 修改计划-定向条件
     */
    const CONTENT_TYPE_CAMPAIGN_CONDITION = 6302;

    /**
     * 修改计划-日限额
     */
    const CONTENT_TYPE_CAMPAIGN_BUDGET = 6303;

    /**
     * 修改计划-出价
     */
    const CONTENT_TYPE_CAMPAIGN_PRICE = 6304;

    /**
     * 修改计划-投放周期
     */
    const CONTENT_TYPE_CAMPAIGN_CYCLE = 6305;

    /**
     * 修改计划-投放状态
     */
    const CONTENT_TYPE_CAMPAIGN_STATUS = 6306;

    /**
     * 新建草稿
     */
    const CONTENT_TYPE_CAMPAIGN_CREATE_DRAFT = 6307;

    /**
     * 删除计划
     */
    const CONTENT_TYPE_CAMPAIGN_DELETE = 6308;

    /**
     * 计划复制
     */
    const CONTENT_TYPE_CAMPAIGN_DUPLICATE = 6309;

    /**
     * 修改操作的中文描述
     * @var array
     */
    public static $content = array(
        6101 => '修改账户日限额',
        6201 => '创建创意',
        6202 => '删除创意',
        6203 => '修改评论状态',
        6301 => '新建计划',
        6302 => '修改计划定向条件',
        6303 => '修改计划日限额',
        6304 => '修改计划出价',
        6305 => '修改计划投放周期',
        6306 => '修改计划投放状态',
        6307 => '新建计划草稿',
        6308 => '删除计划',
        6309 => '计划复制'
    );

    /**
     * @name $_mapping
     * @desc 状态码和中文的映射
     * @author wenyue1
     * @var array
     */
    private $_mapping = array(
        'TARGET_TYPE_ACCOUNT' => '账户',
        'TARGET_TYPE_CREATIVE' => '创意',
        'TARGET_TYPE_CAMPAIGN' => '计划',
        'CONTENT_TYPE_ACCOUNT_BUDGET' => '修改账户日限额',
        'CONTENT_TYPE_CREATIVE_CREATE' => '创建创意',
        'CONTENT_TYPE_CREATIVE_DELETE' => '修改创意',
        'CONTENT_TYPE_CAMPAIGN_CREATE' => '新建计划',
        'CONTENT_TYPE_CAMPAIGN_CONDITION' => '修改计划定向条件',
        'CONTENT_TYPE_CAMPAIGN_BUDGET' => '修改计划日限额',
        'CONTENT_TYPE_CAMPAIGN_PRICE' => '修改计划出价',
        'CONTENT_TYPE_CAMPAIGN_CYCLE' => '修改计划投放周期',
        'CONTENT_TYPE_CAMPAIGN_STATUS' => '修改计划投放状态',
        'CONTENT_TYPE_CAMPAIGN_CREATE_DRAFT' => '新建草稿',
        'CONTENT_TYPE_CAMPAIGN_DELETE' => '删除计划',
        'CONTENT_TYPE_CAMPAIGN_DUPLICATE' => '计划复制',
        'CONTENT_TYPE_CREATIVE_COMMENT' => '修改评论状态',
    );

}
