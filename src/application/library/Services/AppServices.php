<?php namespace Services;


use Api\ObjectApi;
use Base\DB;
use Base\Request;
use Models\Creative;
use Api\AppApi;
use \UserInfo;
use \Result;
use \Type\CreativeType;

class AppServices
{
    public function getList()
    {
        $result = new Result();

        $data = AppApi::getApps(UserInfo::getTargetUserId());

        $result->data = $data;

        return $result->toArray();
    }



}