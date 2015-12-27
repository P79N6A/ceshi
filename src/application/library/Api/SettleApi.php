<?php namespace Api;

use Curl;
use LogFile;
use Alert;
use \Models\Customer;

/**
 * @name SettleApi
 * @desc 结算API调用类
 * @author wenyue1
 */
class SettleApi
{

    /**
     * @name $_ad_type
     * @desc 广告类型, trend : 4
     * @author wenyue1
     * @var string
     */
    private static $_ad_type = 12;

    /**
     * @name $_query_account_balance_and_consume_type
     * @desc 查询账户余额和消耗的类型
     * @author wenyue1
     * @var string
     */
    private static $_query_account_balance_and_consume_type = '101';

    /**
     * @name $_query_campaign_consume_type
     * @desc 查询计划消耗的类型
     * @author wenyue1
     * @var string
     */
    private static $_query_campaign_consume_type = '102';

    /**
     * @name $_create_campaign_type
     * @desc 创建计划的类型
     * @author wenyue1
     * @var string
     */
    private static $_create_campaign_type = '203';

    /**
     * @name $_modify_daily_quota_type
     * @desc 修改产品线日限额
     * @author wenyue1
     * @var string
     */
    private static $_modify_daily_quota_type = '302';

    /**
     * @name $_modify_campaign_budget_type
     * @desc 修改计划限额的类型
     * @author wenyue1
     * @var string
     */
    private static $_modify_campaign_budget_type = '304';

    /**
     * @name $_modify_campaign_end_time_type
     * @desc 修改计划结束时间的类型
     * @author wenyue1
     * @var string
     */
    private static $_modify_campaign_end_time_type = '305';

    /**
     * @name $_modify_daily_quota_mapping
     * @desc 修改产品线日限额
     * @author wenyue1
     * @var array
     */
    private static $_modify_daily_quota_mapping = array(
        'account' => 'customer_id',
    );

    /**
     * @name $_query_account_balance_and_consume_mapping
     * @desc 查询账户余额和消耗的映射关系
     * @author wenyue1
     * @var array
     */
    private static $_query_account_balance_and_consume_mapping = array(
        'account' => 'customer_id',
        'balance' => 'balance',
        'expense' => 'consume',
        'total_expense' => 'total_consume',
    );

    /**
     * @name $_query_campaign_consume_mapping
     * @desc 查询计划消耗的映射关系
     * @author wenyue1
     * @var array
     */
    private static $_query_campaign_consume_mapping = array(
        'ad_id' => 'campaign_id',
        'expense' => 'consume'
    );

    /**
     * @name $_default_mapping
     * @desc 默认的映射关系
     * @author wenyue1
     * @var array
     */
    private static $_default_mapping = array(
        'ad_id' => 'campaign_id'
    );


    /**
     * @name queryAccountBalanceAndConsume
     * @desc 查询账户余额和消耗信息
     * @author wenyue1
     * @param int /array $customer_id
     * @return mixed
     */
    public static function queryAccountBalanceAndConsume($customer_id)
    {
        //初始化data
        $data = array();

        //验证参数是否为非数组
        if (!is_array($customer_id)) {
            //构造查询格式
            $data[] = array(
                'account' => intval($customer_id)
            );
        } else {
            //循环数组, 构造格式
            foreach ($customer_id as $id) {
                $data[] = array(
                    'account' => intval($id)
                );
            }
        }

        //调用接口获取结果
        $result = self::_callSettleApi(self::$_query_account_balance_and_consume_type, $data);

        //验证返回结果
        if ($result === false) {
            $url = trim(\Config::get('api')['engine_url']);
            Alert::send('结算查询余额失败', $url);
            return false;
        }

        //获取格式化数据
        $data = self::_formatResult($result, self::$_query_account_balance_and_consume_mapping);

        //验证返回数据
        if ($data === false) {
            $url = trim(\Config::get('api')['engine_url']);
            Alert::send('结算查询余额失败', $url);
            return false;
        }

        //返回数据
        return $data;
    }

    /**
     * @name modifyDailyQuota
     * @desc 设置产品线日限额
     * @author wenyue1
     * @param int $customer_id
     * @param int $budget
     * @return mixed
     */
    public static function modifyDailyQuota($customer_id, $budget)
    {

        $data = array(
            array(
                'account' => intval($customer_id),
                'dailyquota' => intval($budget)
            )
        );

        $result = self::_callSettleApi(self::$_modify_daily_quota_type, $data);

        //获取格式化数据
        $data = self::_formatResult($result, self::$_modify_daily_quota_mapping);

        //验证返回数据
        if ($data === false) {
            return false;
        }

        //返回数据
        return $data;

    }

    /**
     * @name queryCampaignConsume
     * @desc 查询计划消耗信息
     * @author wenyue1
     * @param int $customer_id
     * @param int /array $campaign_id
     * @return mixed
     */
    public static function queryCampaignConsume($customer_id, $campaign_id)
    {
        //显式转换数据类型
        $customer_id = intval($customer_id);

        //初始化data
        $data = array();

        //验证参数是否为非数组
        if (!is_array($campaign_id)) {
            //构造查询格式
            $data[] = array(
                'account' => $customer_id,
                'ad_id' => intval($campaign_id)
            );
        } else {
            //循环数组, 构造格式
            foreach ($campaign_id as $id) {
                $data[] = array(
                    'account' => $customer_id,
                    'ad_id' => intval($id)
                );
            }
        }

        //调用接口并返回结果
        $result = self::_callSettleApi(self::$_query_campaign_consume_type, $data);
        //验证返回结果
        if ($result === false) {
            return false;
        }

        //获取格式化数据
        $data = self::_formatResult($result, self::$_query_campaign_consume_mapping);

        //验证返回数据
        if ($data === false) {
            return false;
        }

        //返回数据
        return $data;
    }

    /**
     * @name createCampaign
     * @desc 创建计划
     * @author wenyue1
     * @param int $customer_id
     * @param int $campaign_id
     * @param int $budget
     * @param timestamp $end_time
     * @return mixed
     */
    public static function createCampaign($customer_id, $campaign_id, $budget, $end_time)
    {
        $end_time = strtotime($end_time);
        //构造格式
        $data = array(
            array(
                'account' => intval($customer_id),
                'ad_id' => intval($campaign_id),
                'dailyquota' => intval($budget),
                'endtime' => intval($end_time)
            )
        );
        //调用接口并返回结果
        $result = self::_callSettleApi(self::$_create_campaign_type, $data);
        //验证返回结果
        if ($result === false) {
            return false;
        }

        //获取格式化数据
        $data = self::_formatResult($result, self::$_default_mapping);

        //验证返回数据
        if ($data === false) {
            return false;
        }

        //返回数据
        return $data;
    }

    /**
     * @name modifyCampaignBudget
     * @desc 修改计划限额
     * @author wenyue1 suchong
     * @param int $customer_id
     * @param int $campaign_id
     * @param int $budget
     * @return mixed
     */
    public static function modifyCampaignBudget($customer_id, $campaign_id, $budget)
    {
        //构造格式
        $data = array(
            array(
                'account' => intval($customer_id),
                'ad_id' => intval($campaign_id),
                'dailyquota' => intval($budget)
            )
        );

        //调用接口并返回结果
        $result = self::_callSettleApi(self::$_modify_campaign_budget_type, $data);

        //验证返回结果
        if ($result === false) {
            return false;
        }

        //获取格式化数据
        $data = self::_formatResult($result, self::$_default_mapping);

        //验证返回数据
        if ($data === false) {
            return false;
        }

        //返回数据
        return $data;
    }

    /**
     * @name modifyCampaignEndTime
     * @desc 修改计划结束时间
     * @author wenyue1 suchong
     * @param int $customer_id
     * @param int $campaign_id
     * @param timestamp $end_time
     * @return array
     */
    public static function modifyCampaignEndTime($customer_id, $campaign_id, $end_time)
    {
        $end_time = strtotime($end_time);

        //构造格式
        $data = array(
            array(
                'account' => intval($customer_id),
                'ad_id' => intval($campaign_id),
                'endtime' => intval($end_time)
            )
        );

        //调用接口并返回结果
        $result = self::_callSettleApi(self::$_modify_campaign_end_time_type, $data);

        //验证返回结果
        if ($result === false) {
            return false;
        }

        //获取格式化数据
        $data = self::_formatResult($result, self::$_default_mapping);

        //验证返回数据
        if ($data === false) {
            return false;
        }

        //返回数据
        return $data;
    }

    /**
     * @name _callSettleApi
     * @desc 调用结算API
     * @author wenyue1
     * @param array $data
     * @return mixed
     */
    private static function _callSettleApi($type, array $data)
    {
        //从配置文件中获取url
        $url = trim(\Config::get('api')['engine_url']);

        $settle_data = [
            'type' => $type,
            'ad_type' => '' . self::$_ad_type,
            'detail' => $data
        ];

        $post_data = [
            'type' => 600,
            'ad_type' => self::$_ad_type,
            'ad_id' => 0,
            'status' => 0,
            'ad_detail' => json_encode($settle_data)
        ];

        $retry_count = intval(\Config::get('api')['retry_count']);

        $result = '';

        while (($retry_count--) > 0) {
            //发送POST请求
            $result = Curl::post($url, $post_data);
            //验证HttpCode
            if (Curl::getHttpCode() === 200) {
                break;
            } else {
                LogFile::info(
                    __METHOD__ . "  settle api httpcode != 200, code is: " . Curl::getHttpCode(
                    ) . " , result :" . var_export($result, 1)
                );
            }
        }

        LogFile::info(
            'settle api : ' . var_export(
                array(
                    'post_data' => $post_data,
                    'result' => $result
                ),
                true
            )
        );

        //验证HttpCode
        if (Curl::getHttpCode() !== 200) {
            Alert::send('通知结算失败3次,last http code:' . Curl::getHttpCode(), 'settle');
            LogFile::error(
                __METHOD__ . "  settle api httpcode != 200, retry 3 times , result :" . var_export($result, 1)
            );
            return false;
        }

        $data = json_decode($result, true);
        if (!is_array($data)) {
            LogFile::error(
                __METHOD__ . "  settle api data format error, is not array , result :" . var_export($data, 1)
            );
            return false;
        }

        if (empty($data['detail'])) {
            LogFile::error(__METHOD__ . "  settle api return detail not set, result :" . var_export($data, 1));
            return false;
        }



        return $data['detail'];

    }

    /**
     * @name _formatResult
     * @desc 格式化并返回结果
     * @author wenyue1
     * @param array $result
     * @param array $mapping_list
     * @return mixed
     */
    private static function _formatResult(array $result, array $mapping_list)
    {
        //初始化data
        $data = array();

        //循环处理返回结果
        foreach ($result as $key => $info) {
            //验证是否包含errcode和errmsg
            if (!isset($info['errcode']) || !isset($info['errmsg'])) {
                LogFile::error(
                    __METHOD__ . " settle api result foreach result, errmsg|errcode not exist, result :" . var_export(
                        $result,
                        1
                    )
                );
                continue;
            }

            //验证是否成功
            if (intval($info['errcode']) !== 10000) {
                $str = __METHOD__ . "errcode != 10000, result : " . var_export($result, 1);
                LogFile::error($str);
                Alert::send($str . 'settle');
                continue;
            }
            //初始化临时变量
            $temp = array(
                'result' => true
            );

            //循环映射列表
            foreach ($mapping_list as $mapping_key => $mapping_value) {
                //验证是否含有映射的key
                if (isset($info[$mapping_key])) {
                    $temp[$mapping_value] = $info[$mapping_key];
                } else {
                    $temp = null;
                    break;
                }
            }

            //验证临时变量是否不为空
            if (!empty($temp)) {
                $data[] = $temp;
            }
        }

        //验证结果是否为空
        if (empty($data)) {
            //没有对应结果, 保存错误信息, URL, post_data, result, array_keys($mapping_list)
            LogFile::error(__METHOD__ . " result : " . var_export($result, 1));
            return false;
        }

        //验证结果个数, 返回结果
        if (count($data) === 1) {
            return $data[0];
        } else {
            return $data;
        }
    }

    public static function getAccounts($customer_list)
    {
        $accountData = SettleApi::queryAccountBalanceAndConsume($customer_list);
        //获取Ids
        $accountIds = array_column($accountData, 'customer_id');

        //重建index， 以customer_id的值为key
        $accountData = array_rebuild($accountData, 'customer_id');

        LogFile::info(__METHOD__ . ' settle returned Ids data ' . var_export($accountIds, true));

    }


    public static function getValidAccountsForOnline($accountIds, $verbose = false)
    {
        if (!count($accountIds)) {
            return array();
        }
        //获取有效accountIds和余额
        $accountData = SettleApi::queryAccountBalanceAndConsume($accountIds);

        if (!$accountData) {
            return false;
        }
        /* accountData array item example:
         * array (
         *   'result' => true,
         *   'customer_id' => 2608812381,
         *   'balance' => 1072, //余额
         *   'consume' => 0, //当日本产品消耗
         * )
         */

        if (isset($accountData['customer_id'])) {
            $accountData = array($accountData);
        }

        if (!is_array($accountData) || !count($accountData)) {
            return array('allOk' => array());
        }

        LogFile::info(__METHOD__ . ' settle data ' . var_export($accountData, true));

        //获取Ids
        $accountIds = array_column($accountData, 'customer_id');
        //重建index， 以customer_id的值为key
        $accountData = array_rebuild($accountData, 'customer_id');
        LogFile::info(__METHOD__ . ' settle returned Ids data ' . var_export($accountIds, true));

        $validIds = array();
        $balanceLack = array(); //账户余额不足
        $budgetReach = array(); //产品线日限额达到

        //余额大于0的查看是否超过当日产品线日限额
        $budgets = Customer::whereIn('customer_id', $accountIds)->get()->toArray();

        if (empty($budgets)) {
            return false;
        }

        foreach ($budgets as $b) {
            $currentUId = $b['customer_id'];
            $currentBudget = $b['budget']; //当前日限额

            $currentBalance = $accountData[$currentUId]['balance'];
            $currentConsume = $accountData[$currentUId]['consume'];
            LogFile::info(
                "[check_finance] customer_id :$currentUId, currentBudget: $currentBudget, currentBalance : $currentBalance, currentConsume: $currentConsume"
            );

            if ($currentBalance <= 0) {
                $balanceLack[] = $currentUId;
                continue;
            }
            if ($currentBudget > 0 && $currentBudget <= $currentConsume) {
                $budgetReach[] = $currentUId;
                continue;
            }

            if ($currentBalance > 0 && $currentConsume < $currentBudget) {
                $validIds[] = $currentUId;
            } else {
                if ($currentBalance > 0
                    && $currentBudget <= 0
                ) {
                    $validIds[] = $currentUId;
                }
            }
        }


        if (!$verbose) {
            return $validIds;
        } else {
            return array(
                'allOk' => $validIds,
                'balanceLack' => $balanceLack,
                'budgetReach' => $budgetReach
            );
        }
    }

}
