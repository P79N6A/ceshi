<?php namespace Type;

use \Base\Type;

/**
 * Class     Type_AccountStatus
 *
 * @author   Ren,Wenyue<wenyue1@staff.weibo.com>
 * @desc     账户状态, 是否被拉黑
 *
 */
class CustomerType extends Type {

    CONST NORMAL_STATUS = 0;

    CONST BLACKLIST_STATUS = 1;

    protected $_mapping = array(
        'NORMAL_STATUS'    => '正常',
        'BLACKLIST_STATUS' => '黑名单状态',
    );

} 