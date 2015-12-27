<?php namespace Type;


use \Base\Type;

class CampaignType extends Type
{
    /**
     * @name DEFAULT_STATUS
     * @desc 默认状态
     * @author wenyue1
     * @var int
     */
    const DEFAULT_STATUS = -1;


    /**
     * @name WAIT_DELIVER_STATUS
     * @desc 待投状态
     * @author wenyue1
     * @var int
     */
    const WAIT_DELIVER_STATUS = 1;


    /**
     * @name DELIVERING_STATUS
     * @desc 在投状态
     * @author wenyue1
     * @var int
     */
    const DELIVERING_STATUS = 2;


    /**
     * @name PAUSE_STATUS
     * @desc 暂停状态
     * @author wenyue1
     * @var int
     */
    const PAUSE_STATUS = 3;

    /**
     * @name STOP_STATUS
     * @desc 终止状态
     * @author wenyue1
     * @var int
     */
    const STOP_STATUS = 4;

    /**
     * @name DRAFT_STATUS
     * @desc 草稿状态
     * @author wenyue1
     * @var int
     */
    const DRAFT_STATUS = 5;

    /**
     * @name ERROR_STATUS
     * @desc 异常状态 仅用作前端约定，数据库中无此种状态
     * @author wenyue1
     * @var int
     */
    const ERROR_STATUS = 6;

    /**
     * @name DELETE_STATUS
     * @desc 删除状态
     * @author wenyue1
     * @var int
     */
    const DELETE_STATUS = -9;


    /**
     * @name $_mapping
     * @desc 状态码和中文的映射
     * @author wenyue1
     * @var array
     */
    private $_mapping = array(
        'DEFAULT_STATUS' => '默认',
        'WAIT_DELIVER_STATUS' => '待投',
        'DELIVERING_STATUS' => '在投',
        'PAUSE_STATUS' => '暂停',
        'STOP_STATUS' => '终止',
        'DELETE_STATUS' => '删除',
        'ERROR_STATUS' => '异常',
        'DRAFT_STATUS' => '草稿',
        'AUDIT_STATUS_WAIT' => '待审',
        'AUDIT_STATUS_PASSED' => '审核通过',
        'AUDIT_STATUS_REFUSED' => '审核拒绝'
    );
}
