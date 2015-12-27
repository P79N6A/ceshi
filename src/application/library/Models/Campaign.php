<?php namespace Models;

use Base\Model;
use Base\DB;
use Api\WeiboApi;
use Exception\HttpException;
use Api\SettleApi;
use Api\EngineApi;
use Type\EngineType;
use Type\AppType;
use Type\CampaignPauseType;
use Type\CampaignType;
use Config;
use Adinf\Dml\Factory as DmlFactory;


class Campaign extends Model {

    protected $connection = 'app';
    protected $guarded = [''];

    public function creative()
    {
        return $this->belongsTo('\Models\Creative');
    }

    public function customer()
    {
        return $this->belongsTo('\Models\Customer');
    }
    /**
     * Method  getDetail
     * @desc 提交给引擎前将数据格式化
     * @author guangling1<guangling1@staff.weibo.com>
     * @return array
     */
    public function getDetail($update=true)
    {
        if ($update) {
            $this->created_at = date('Y-m-d H:i:s');
            $this->updated_at = $this->created_at;
        }
        $detail = [];
        $detail['ad_id'] = intval($this->id);
        $detail['ad_type'] = 12;

        switch ($this->status) {
            case CampaignType::DELETE_STATUS:
                // ... 404 Not Found
                $detail['status'] = EngineType::OFFLINE;
                break;
            case CampaignType::DELIVERING_STATUS:
                // ... 405 Method Not Allowed
                $detail['status'] = EngineType::ONLINE;
                break;
            case CampaignType::WAIT_DELIVER_STATUS:
                // ... 405 Method Not Allowed
                $detail['status'] = EngineType::PAUSE;
                break;
            case CampaignType::PAUSE_STATUS:
                // ... 405 Method Not Allowed
                $detail['status'] = EngineType::PAUSE;
                break;
            case CampaignType::STOP_STATUS:
                // ... 405 Method Not Allowed
                $detail['status'] = EngineType::OFFLINE;
                break;
            case CampaignType::DRAFT_STATUS:
                // ... 405 Method Not Allowed
                $detail['status'] = EngineType::PAUSE;
                break;
            default:
                $detail['status'] = EngineType::PAUSE;
                break;
        }

        $detail['cust_id'] = intval($this->customer_id);
        $detail['promotion_type'] = 1;
        $detail['app_id'] = "{$this->app_id}";
        $detail['psid'] = 'FEED000000088001';
        $detail['create_time'] = intval(strtotime($this->created_at) . '000');
        $detail['update_time'] = intval(strtotime($this->updated_at) . '000');
        $detail['version'] = intval($this->version);

        $detail['objects'][] = [
            'id' => $this->creative->mid.'',
            'style_type' => intval($this->creative->type),
            'create_time' => intval(strtotime($this->creative->created_at) . '000'),
            'embedded_object' => [
                'id' => $this->creative->object_id.'',
                'desc_type' => intval($this->creative->summery_type),
                'desc_text' => $this->creative->summery
            ]
        ];
        // hack opc
        $opc = json_decode($this->opc, true);
        $province = [];
        $city = [];
        foreach ($opc['location'] as $row) {
            if (strlen($row) == 3) {
                $province[] = $row;
            } else {
                $city[] = $row;
            }
        }
        if (!empty($province)) {
            $children_location =  Condition::getLocationChildren($province);
            $province = array_keys($children_location);
            $opc['location'] = array_merge($province, $city);
        }
        sort($opc['location']);
        $opc['location'] = array_values(array_unique($opc['location']));

        unset($opc['interests']);
        foreach ($opc as &$row) {
            $row = array_unique($row);
        }

        $detail['opc'] = $opc;
        $detail['bid'] = [
            'type' => 1,
            'price' => floatval($this->price),
            'budget' => intval($this->budget),
            'roof_price' => intval($this->roof_price),
        ];

        $monitor = json_decode($this->monitor, true);

        $monitor_object = array_values($monitor);
        $monitor_result = array();
        foreach($monitor_object as $val){
            $monitor_result += $this->formatMonitorDetail($val, $this->creative->app_type);
        }
        $detail['monitor'] = [
            'pv_url' => [],
            'bhv_url' => $monitor_result
        ];

        $detail['extra'] = new \stdClass();

        return $detail;
    }

    public function getStopType(){
        $weibo_result = WeiboApi::getStatusInfoByMid($this->creative->mid);
        $stop_type = 0;
        if ($weibo_result == false) {
            $stop_type = CampaignPauseType::CAMPAIGN_NOT_EXIST_CONTENT_STATUS;
        }
        return $stop_type;
    }

    public function getFormatOpc($is_chinese=true) {
        $condition_map_all = Condition::getConditionMap();

        $condition_list = [];
        $opc_list = json_decode($this->opc, true);
        unset($opc_list['interests']);

        $location = [];

        foreach ($opc_list as $opc_key => $opc_row) {
            if ($opc_key == 'fans_target_list') {
                foreach ($opc_row as $target_id) {
                    $weibo_info = WeiboApi::getUserInfoByUid($target_id);
                    $condition_list['粉丝关系'][] = $weibo_info['screen_name'];
                }
            }
            if ($opc_key == 'age') {
                if (count($opc_row) == count($condition_map_all['fans_age'])) {
                    $condition_list['年龄'][] = '不限';
                    $opc_list['age'] = -1;
                } else {
                    $fans_age = $opc_row;
                    @sort($fans_age);
                    $fans_age_min = @(int)$fans_age[0] - 1000;
                    $fans_age_max = @(int)end($fans_age) - 1000;
                    $fans_age = "{$fans_age_min}岁-{$fans_age_max}岁";
                    $condition_list['年龄'][] = $fans_age;
                }
            }

            foreach ($opc_row as $condition_id) {
                if (@isset($condition_map_all[$opc_key][$condition_id]['type_name'])) {
                    $key_name = $condition_map_all[$opc_key][$condition_id]['type_name'];
                    if (count($opc_row) == count($condition_map_all[$opc_key])) {
                        if ($key_name == 'interests') {
                            $condition_list[$key_name][] = '广泛兴趣';
                        } else {
                            $condition_list[$key_name][] = '不限';
                            $opc_list[$condition_map_all[$opc_key][$condition_id]['type']] = -1;
                        }
                        break;
                    }
                    $condition_list[$key_name][] = $condition_map_all[$opc_key][$condition_id]['name'];
                }
            }

            foreach ($condition_list['粉丝关系'] as $key => $fans) {
                if ($fans == '同行粉丝') {
                    unset($condition_list['粉丝关系'][$key]);
                }
            }
        }

//        if ($opc_list['location'] != -1) {
//            foreach ($opc_list['location'] as $city) {
//                $location[] = substr($city, 0, 3);
//            }
//            $opc_list['location'] = array_unique($location);
//        }


        if (isset($opc_list['fans_target_list'])) {
            $fans_target_list = [];
            foreach ($opc_list['fans_target_list'] as $fans) {
                $weibo_info = WeiboApi::getUserInfoByUid($fans);
                $fans_target_list[] = array(
                    'id' => $fans,
                    'name' => $weibo_info['name'],
                    'location' => $weibo_info['location'],
                    'followers_count' => $weibo_info['followers_count'],
                    'profile_image_url' => $weibo_info['profile_image_url'],
                    'verified' => $weibo_info['verified']
                );
            }
            $opc_list['fans_target_list'] = $fans_target_list;
        }

        // 前端hack
        if ($opc_list['fans'] == [601]) {
            $opc_list['fans'] = 601;
        }

        if ($opc_list['fans'] == [602]) {
            $opc_list['fans'] = 602;
        }

        $campaign['raw_opc'] = $opc_list;
        $campaign['opc'] = $condition_list;

        if ($is_chinese) {
            return $condition_list;
        } else {
            return $opc_list;
        }
    }

    public function online() {
        $lock = $this->lock($this->id);
        if (!$lock) {
            throw new HttpException(500, '加锁失败。');
        }
        $this->version++;
        $old_status = $this->status;
        $check_ret = SettleApi::getValidAccountsForOnline($this->customer_id, true);
        if (!$check_ret) {
            \LogFile::alert('账户余额查询失败', $this->toArray());
            $this->unlock($lock);
            throw new HttpException(500, '系统繁忙，账户余额查询失败。');
        }

        $this->status = CampaignType::DELIVERING_STATUS;
        $this->stop_type = CampaignPauseType::USER_OPERATION_STATUS;

        if (!$check_ret['allOk']) {
            if (!empty($check_ret['balanceLack'])) {
                $this->status = CampaignType::PAUSE_STATUS;
                if (!empty($check_ret['balanceLack'])) {
                    $this->stop_type = CampaignPauseType::ACCOUNT_BALANCE_LACK_STATUS;
                } elseif (!empty($check_ret['budgetReach'])) {
                    $this->stop_type = CampaignPauseType::REACH_PRODUCT_BUDGET_STATUS;
                } else {
                    \LogFile::alert('账户余额查询失败', $this->toArray());
                    $this->unlock($lock);
                    throw new HttpException(500, '系统繁忙，账户余额查询失败。');
                }
            }
        }

        // check 计划日限额
        $consume = Consume::where('campaign_id', $this->id)->where('post_date', date('Y-m-d'))->first();
        if (!empty($consume) && ($consume->consume >= $this->budget && intval($this->budget) != 0)) {
            $this->stop_type = CampaignPauseType::REACH_CAMPAIGN_BUDGET_STATUS;
            $this->status = CampaignType::PAUSE_STATUS;
        }

        if ($this->status == CampaignType::DELIVERING_STATUS && $old_status != CampaignType::DELIVERING_STATUS) {
            $engine_ret = EngineApi::online($this->id, $this->getDetail());
            \LogFile::info('online campaign', $this->toArray());

            if (!$engine_ret) {
                \LogFile::alert('创建计划通知引擎失败', $this->toArray());
                $this->unlock($lock);
                throw new HttpException(500, '系统繁忙，计划上线失败。。');
            }
        }

        if ($this->status == CampaignType::PAUSE_STATUS && $old_status != CampaignType::PAUSE_STATUS) {
            $engine_ret = EngineApi::pause($this->id, $this->getDetail());
            \LogFile::info('pause campaign', $this->toArray());
            if (!$engine_ret) {
                \LogFile::alert('创建计划通知引擎失败', $this->toArray());
                $this->unlock($lock);
                throw new HttpException(500, '系统繁忙，计划暂停失败。。');
            }
        }

        $this->unlock($lock);
        if ($old_status != $this->status) {
            $this->save();
        }
    }

    public function stop() {
        $lock = $this->lock($this->id);
        if (!$lock) {
            throw new HttpException(500, '加锁失败。');
        }
        $old_status = $this->status;
        $this->version++;
        $this->status = CampaignType::STOP_STATUS;
        $this->stop_type = CampaignPauseType::CAMPAIGN_NOT_EXPIRE_STATUS;
        $result = EngineApi::stop($this->id, $this->getDetail());
        $this->unlock($lock);
        if (!$result) {
            throw new HttpException(500, '系统繁忙，创建计划失败。。');
        }
        if ($old_status != $this->status) {
            \LogFile::info('stop campaign', $this->toArray());
            $this->save();
        }
    }

    private function lock($id)
    {
        $lock = DmlFactory::trylock(DmlFactory::REDLOCK, "campaign_{$id}", 30000, 30000);
        if (!$lock) {
            \Alert::send('cron reverse budget get lock 失败', 'cron reverse budget get lock 失败 id = ' . $id);
            \LogFile::error('cron reverse budget get lock 失败', 'cron reverse budget get lock 失败 id = ' . $id);
        }
        return $lock;
    }

    private function unlock($lock)
    {
        DmlFactory::unlock($lock);
    }

    /**
     * 校验、返回monitor
     * @param null $url
     * @return string
     */
    public function formatMonitorJson($url = null){
        $monitor = [];
        if(!empty($url)){
            $monitor_config = Config::get('monitor');
            $monitor_key = array_keys($monitor_config);
            $needle_monitor  = null;
            foreach($monitor_key as $mkey){
                if(strpos($url, $mkey) !== false){
                    $needle_monitor = $monitor_config[$mkey];
                    break;
                }
            }
            if($needle_monitor != null){
                //校验规则
                if($needle_monitor['PREG_RULE']){
                   if(preg_match($needle_monitor['PREG_RULE'], $url)){
                       $monitor[$needle_monitor['KEY']] = $url;
                   }
                } else{
                    $monitor[$needle_monitor['KEY']] = $url;
                }
            }
        }
        return json_encode($monitor);
    }

    /**
     * 校验、返回monitordetail
     * @param null $url
     * @param null $type
     * @return string
     */
    public function formatMonitorDetail($url = null, $type = null){
        $monitor = [];
        if(!empty($url)){
            $monitor_config = Config::get('monitor');
            $monitor_key = array_keys($monitor_config);
            $needle_monitor  = null;
            foreach($monitor_key as $mkey){
                if(strpos($url, $mkey) !== false){
                    $needle_monitor = $monitor_config[$mkey];
                    break;
                }
            }
            if($needle_monitor != null && isset($needle_monitor[$type])){
                //校验规则
                if($needle_monitor['PREG_RULE']){
                    if(preg_match($needle_monitor['PREG_RULE'], $url)){
                        $monitor[$needle_monitor['KEY']] = $url . $needle_monitor[$type] ;
                    }
                } else{
                    $monitor[$needle_monitor['KEY']] = $url . $needle_monitor[$type] ;
                }
            }
        }
        return $monitor;
    }
}
