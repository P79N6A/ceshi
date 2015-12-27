<?php namespace Type;


use \Base\Type;

/**
 * @name Type_UserType
 * @desc 用户类型
 * @author wenyue1
 */
class UserType extends Type {
    /**
     * @name NONE
     * @desc 默认异常状态
     * @author wenyue1
     * @var int
     */
    const NONE = -1;
    /**
     * @name ADMIN
     * @desc 管理员
     * @author wenyue1
     * @var int
     */
    const ADMIN = 1;


    /**
     * @name AGENT
     * @desc 代理商
     * @author wenyue1
     * @var int
     */
    const AGENT = 2;

    /**
     * @name BLUEV
     * @desc 客户(蓝V)
     * @author wenyue1
     * @var int
     */
    const BLUEV = 3;

    /**
     * @name $_mapping
     * @desc 状态码和中文的映射
     * @author wenyue1
     * @var array
     */
    private $_mapping = array(
        'NONE'  => '默认异常用户类型',
        'BLUEV' => '客户',
        'AGENT' => '代理商',
        'ADMIN' => '管理员'
    );
}
