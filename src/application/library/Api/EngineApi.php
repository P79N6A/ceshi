<?php namespace Api;

use Curl;
use LogFile;
use Alert;

class EngineApi {

    /**
     * @name $_ad_type
     * @desc 广告类型, trend : 12
     * @var int
     */
    private static $_ad_type = 12;

    /**
     * @name $_create_type_app
     * @desc App新建的类型
     * @author wenyue1
     * @var int
     */
    private static $_create_type_app = 100;

    /**
     * @name $_modify_type_app
     * @desc App修改的类型
     * @author wenyue1
     * @var int
     */
    private static $_modify_type_app = 200;

    /**
     * @name $_simple_modify_type_app
     * @desc App简单修改的类型
     * @author wenyue1
     * @var int
     */
    private static $_simple_modify_type_app = 300;

    /**
     * 结算类型
     * @var int
     */
    private static $_settle_type = 600;

    private static $_offline_status = -1;

    private static $_online_status = 1;

    private static $_pause_status = 0;

    /**
     * @param $campaign_id
     * @param $campaign_status
     * @param $campaign_detail
     * @return bool
     */
    public static function create($campaign_id, $campaign_detail, $campaign_status) {
        //调用接口获取结果并返回结果
        return self::_callEngineApiForApp(self::$_create_type_app, intval($campaign_id), $campaign_detail, $campaign_status);
    }

    /**
     * @param $campaign_id
     * @param $campaign_status
     * @param $campaign_detail
     * @return bool
     */
    public static function modify($campaign_id, $campaign_detail, $campaign_status) {
        //调用接口获取结果并返回结果
        return self::_callEngineApiForApp(self::$_modify_type_app, intval($campaign_id), $campaign_detail, $campaign_status);
    }

    /**
     * @name simpleModify
     * @desc App的简单修改 / 不含简单条件修改
     * @author wenyue1
     * @param int $campaign_id
     * @param int $campaign_status
     * @return boolean
     */
    public static function simpleModify($campaign_id, $campaign_detail, $campaign_status) {
        //调用接口获取结果并返回结果
        return self::_callEngineApiForApp(self::$_simple_modify_type_app, intval($campaign_id), $campaign_detail, $campaign_status);
    }

    /**
     * @param $campaign_id
     * @param $campaign_detail
     * @return bool
     */
    public static function online($campaign_id, $campaign_detail) {
        //调用接口获取结果并返回结果
        return self::simpleModify($campaign_id, $campaign_detail, self::$_online_status);
    }

    /**
     * @param $campaign_id
     * @param $campaign_detail
     * @return bool
     */
    public static function pause($campaign_id, $campaign_detail) {
        //调用接口获取结果并返回结果
        return self::simpleModify($campaign_id, $campaign_detail, self::$_pause_status);

    }

    /**
     * @param $campaign_id
     * @param $campaign_detail
     * @return bool
     */
    public static function stop($campaign_id, $campaign_detail) {
        //调用接口获取结果并返回结果
        return self::simpleModify($campaign_id, $campaign_detail, self::$_offline_status);
    }

    /**
     * @name _callEngineApiForApp
     * @desc 调用引擎的AppApi
     * @param $type
     * @param $campaign_id
     * @param $campaign_detail
     * @param int $status
     * @return bool
     */
    private static function _callEngineApiForApp($type, $campaign_id, $campaign_detail, $status = 0) {
        //从配置文件中获取url
        $url = trim(\Config::get('api')['engine_url']);

        \LogFile::info('callEngineWebService Url: ' . $url);

        $retry_count = intval(\Config::get('api')['retry_count']);
        $rc = $retry_count;

        $result = '';
        $data = [
            'type' => $type,
            'ad_type' => self::$_ad_type,
            'ad_id' => $campaign_id,
            'status' => $status,
            'ad_detail' => json_encode($campaign_detail)
        ];

        while (($retry_count--) > 0) {
            $result = Curl::post($url, $data);

            //验证HttpCode
            if (Curl::getHttpCode() === 200 || Curl::getHttpCode() === 204) {
                break;
            } else {
                LogFile::info('CallEngineWebservice return httpcode != 200, httpcode = ' . Curl::getHttpCode());
            }
        }

        //验证HttpCode
        if (Curl::getHttpCode() !== 200) {
            Alert::send('通知引擎失败', '返回http code错误,id:' . $campaign_id.'httpCode:'.Curl::getHttpCode().'status:'.$status.'type:'.$type, 'engine');
            LogFile::error('CallEngineWebservice return httpcode != 200/204 and retry ' . $rc . ' times, httpcode = ' . Curl::getHttpCode());
            return false;
        }

        $data = json_decode($result, true);

        //验证数据类型
        if (!is_array($data)) {
            Alert::send('通知引擎失败',  '返回格式错误,id:' . $campaign_id.'status:'.$status.'type:'.$type, 'engine');
            LogFile::error(__METHOD__ . " url : $url ,engine api data format error, is not array");
            return false;
        }

        //验证返回码
        if (!isset($data['code']) || intval($data['code']) !== 0) {
            Alert::send('通知引擎失败', '返回码错误,id:' . $campaign_id.',retcode:'.$data['code'].'status:'.$status.'type:'.$type, 'engine');
            LogFile::error(__METHOD__ . " url : $url ,engine api return code !=0");
            return false;
        }
        //通用日志
        LogFile::info(' callEngineApiForApi ' . var_export(array('url' => $url, 'result' => $result), true));
        return true;
    }

    private static function _callSettleApi($detail)
    {
        $url = trim(\Config::get('api')['engine_url']);

        \LogFile::info('callEngineWebService Url: ' . $url);
        $retry_count = intval(\Config::get('api')['retry_count']);
        $rc = $retry_count;

        $result = '';
        $data = [
            'op_type' => self::$_settle_type,
            'ad_type' => self::$_ad_type,
            'ad_id' => 0,
            'status' => 0,
            'ad_detail' => json_encode($detail)
        ];


        while (($retry_count--) > 0) {
            $result = Curl::post($url, $data);

            //验证HttpCode
            if (Curl::getHttpCode() === 200 || Curl::getHttpCode() === 204) {
                break;
            } else {
                LogFile::info('CallEngineWebservice return httpcode != 200, httpcode = ' . Curl::getHttpCode());
            }
        }

        //验证HttpCode
        if (Curl::getHttpCode() !== 200) {
            Alert::send('通知结算失败,返回http code错误,detail:' , var_export($detail, true).'httpCode:'.Curl::getHttpCode());
            LogFile::error('CallEngineWebservice return httpcode != 200/204 and retry ' . $rc . ' times, httpcode = ' . Curl::getHttpCode());
            return false;
        }

        $data = json_decode($result, true);

        //验证数据类型
        if (!is_array($data)) {
            Alert::send('通知结算失败,返回http code错误,detail:' , var_export($detail, true).'httpCode:'.Curl::getHttpCode());
            LogFile::error(__METHOD__ . " url : $url ,engine api data format error, is not array");
            return false;
        }

        //验证返回码
        if (isset($data['code']) && intval($data['code']) == 1 || !isset($data['type'])) {
            Alert::send('通知结算失败,返回http code错误,detail:' , var_export($detail, true).'httpCode:'.Curl::getHttpCode());
            LogFile::error(__METHOD__ . " url : $url ,engine api return code !=0");
            return false;
        }


        //通用日志
        LogFile::info(' callEngineApiForApi ' . var_export(array('url' => $url, 'result' => $result), true));


        //验证结算
        return $data;
    }
}
