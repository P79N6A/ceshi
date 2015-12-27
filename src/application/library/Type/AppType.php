<?php namespace Type;


use \Base\Type;

class AppType extends Type {

    const READY = 2;

    const WAIT = 1;

    const IOS = 1;

    const ANDROID = 0;


    private $_mapping = array(
        'READY'  => '推广',
        'WAIT'  => '去入库',
        'IOS'  => 'IOS',
        'ANDROID'  => 'ANDROID',
    );
}
