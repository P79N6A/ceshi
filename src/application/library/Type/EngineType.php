<?php namespace Type;


use \Base\Type;


class EngineType extends Type {


    const ONLINE = 1;

    const PAUSE = 0;

    const OFFLINE = -1;


    private $_mapping = array(
        'ONLINE' => '在线',
        'PAUSE' => '暂停',
        'OFFLINE' => '下线'
    );
}
