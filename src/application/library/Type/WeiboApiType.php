<?php namespace Type;


use \Base\Type;

class WeiboApiType extends Type {

    const CODE_20046 = 20046;
    const CODE_20101 = 20101;
    const CODE_20003 = 20003;
    //自定义
    const CODE_90000 = 90000;
    const CODE_90001 = 90001;
    const CODE_90002 = 90002;
    const CODE_90003 = 90003;

    private $_mapping = array(
        'CODE_20046'  => '注册邮箱未激活，请激活后重试',
        'CODE_20003'  => '用户不存在，请重试',
        'CODE_20101'  => '微博不存在，请重试',
        'CODE_90000'  => '微博返回格式错误，请重试',
        'CODE_90001'  => '微博MID返回格式错误，请重试',
        'CODE_90002'  => '获取TAuthToken失败，请重试',
        'CODE_90003'  => '参数错误，请重试',
    );
}
