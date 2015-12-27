<?php namespace Type;


use \Base\Type;
/**
 * @name Type_CampaignPause
 * @desc 计划暂停类型
 * @author Ren,Wenyue<wenyue1@staff.weibo.com>
 */
class CampaignPauseType extends Type {

    /**
     * @name DEFAULT_STATUS
     * @desc 暂停默认状态
     * @author Ren,Wenyue<wenyue1@staff.weibo.com>
     * @var int
     */
    const DEFAULT_STATUS = -1;

    /**
     * @name USER_OPERATION_STATUS
     * @desc 用户操作暂停状态
     * @author Ren,Wenyue<wenyue1@staff.weibo.com>
     * @var int
     */
    const USER_OPERATION_STATUS = 0;

    /**
     * @name REACH_CAMPAIGN_BUDGET_STATUS
     * @desc 计划日限额到达暂停状态
     * @author Ren,Wenyue<wenyue1@staff.weibo.com>
     * @var int
     */
    const REACH_CAMPAIGN_BUDGET_STATUS = 1;

    /**
     * @name REACH_PRODUCT_BUDGET_STATUS
     * @desc 达到产品线日限额暂停状态
     * @author Ren,Wenyue<wenyue1@staff.weibo.com>
     * @var int
     */
    const REACH_PRODUCT_BUDGET_STATUS = 2;

    /**
     * @name ACCOUNT_BALANCE_LACK_STATUS
     * @desc 账户余额不足暂停状态
     * @author Ren,Wenyue<wenyue1@staff.weibo.com>
     * @var int
     */
    const ACCOUNT_BALANCE_LACK_STATUS = 3;

    /**
     * @name CAMPAIGN_NOT_EXIST_CONTENT_STATUS
     * @desc 计划下没有可用内容暂停状态 / 被推广的AccountId状态异常
     * @author Ren,Wenyue<wenyue1@staff.weibo.com>
     * @var int
     */
    const CAMPAIGN_NOT_EXIST_CONTENT_STATUS = 4;

    /**
     * @name BLACKLIST_STATUS 
     * @desc 账户被黑名单/被禁用
     * @author Ren,Wenyue<wenyue1@staff.weibo.com>
     * @var int
     */
    const BLACKLIST_STATUS = 6;

    /**
     * @name CAMPAIGN_NOT_PASSED
     * @desc 计划过期
     * @authoryongqiang3<yongqiang3@staff.weibo.com>
     * @var int
     */
    const CAMPAIGN_NOT_EXPIRE_STATUS = 8;


    /**
     * @name $_mapping
     * @desc 状态码和中文的映射
     * @author Ren,Wenyue<wenyue1@staff.weibo.com>
     *         yongqiang3<yongqiang3@staff.weibo.com>
     * @var array
     */
    private $_mapping = array(
        'DEFAULT_STATUS'                    => '默认',
        'USER_OPERATION_STATUS'             => '用户手动操作',
        'REACH_CAMPAIGN_BUDGET_STATUS'      => '计划日限额到达暂停状态',
        'REACH_PRODUCT_BUDGET_STATUS'       => '达到产品线日限额暂停状态',
        'ACCOUNT_BALANCE_LACK_STATUS'       => '账户余额不足',
        'CAMPAIGN_NOT_EXIST_CONTENT_STATUS' => '被推广AccountId状态异常',
        'BLACKLIST_STATUS'                  => '账户被黑名单',
        'CAMPAIGN_NOT_PASSED_STATUS'        => '计划没有通过审核',
        'CAMPAIGN_NOT_EXPIRE_STATUS'        => '计划过期',
    );

}
