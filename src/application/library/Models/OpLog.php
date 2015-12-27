<?php namespace Models;

use Base\Model;
use Type\CampaignType;
use Type\OpLogType;
use UserInfo;

class OpLog extends Model
{
    protected $table = 'op_logs';
    protected $connection = 'app';


    public static function write($target_type, $target_id, $target_name, $content_type, $from, $to, $ad_type = 12)
    {
        $log = new OpLog();
        $log->from = is_array($from) ? json_encode($from) : $from;
        $log->to = is_array($to) ? json_encode($to) : $to;
        $log->target_type = $target_type;
        $log->target_id = $target_id;
        $log->target_name = $target_name;
        $log->content_type = $content_type;
        $log->ad_type = $ad_type;
        $log->op_id = UserInfo::getCurrentUserId();
        $log->op_name = UserInfo::getCurrentUserName();
        $log->op_type = UserInfo::getCurrentUserType();
        $log->customer_id = UserInfo::getTargetUserId();
        $log->content = OpLogType::$content[$content_type];

        return $log->save();
    }
}