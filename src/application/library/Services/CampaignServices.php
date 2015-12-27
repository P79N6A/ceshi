<?php namespace Services;


use Api\AppApi;
use Api\EngineApi;
use Api\SettleApi;
use Api\WeiboApi;
use Base\Request;
use Base\DB;
use Models\Campaign;
use Models\Condition;
use Models\Consume;
use Models\Creative;
use Models\OpLog;
use Type\AppType;
use Type\CampaignType;
use Type\CreativeType;
use Type\CampaignPauseType;
use Type\EngineType;
use Result;
use Type\OpLogType;
use UserInfo;
use Exception\HttpException;
use LogFile;
use Alert;
use Adinf\Dml\Factory as DmlFactory;

class CampaignServices
{
    public function getList()
    {
        $pagination = Request::getPagination();
        $result = new Result();

        $condition = Campaign::where('customer_id', UserInfo::getTargetUserId())->where(
            'status',
            '<>',
            CampaignType::DELETE_STATUS
        );
        $keywords = Request::input("keywords");
        $status = Request::input("status");
        $column = Request::input("column");

        if (isset($keywords)) {
            $condition = $condition->where('name', 'like', "%$keywords%");
        }

        if (isset($status)) {
            if (CampaignType::ERROR_STATUS == $status) {
                $condition = $condition->where('stop_type', '<>', 0);
            } else {
                $condition = $condition->where('status', $status);
            }
        }

        $start_time = Request::input('start_time');

        if (isset($start_time)) {
            $condition = $condition->where('created_at', '>=', $start_time);
            $end_time = Request::input('end_time');
            if (isset($end_time)) {
                $condition = $condition->where('created_at', '<=', $end_time);
            }
        }

        $column_array = null;
        if (isset($column)) {
            $column_array = explode(',', trim($column));
        }

        $total = $condition->count();

        $data = $condition->orderBy('created_at', 'desc')->forPage($pagination[0], $pagination[1])->get(
            $column_array
        )->toArray();

        // link to consume
        foreach ($data as &$campaign) {
            $today = Consume::where('post_date', date('Y-m-d'))->where(
                'customer_id',
                UserInfo::getTargetUserId()
            )->where('campaign_id', $campaign['id'])->first();
            $campaign['consume'] = (empty($today)) ? 0 : round($today->consume, 2);
            $total_consume = DB::table('consume_campaign')->where(
                'customer_id',
                UserInfo::getTargetUserId()
            )->where('campaign_id', $campaign['id'])->sum('consume');
            $campaign['total_consume'] = round($total_consume, 2);
        }

        $result->data = [
            $data
        ];

        $result->total_count = $total;

        return $result->toArray();
    }

    public function show()
    {
        $result = new Result();
        $campaign_id = Request::input("id");
        // 检测重名
        $campaign = Campaign::with('creative')->where('customer_id', \UserInfo::getTargetUserId())
            ->where('status', '<>', CampaignType::DELETE_STATUS)->where('id', $campaign_id)->first();
        $campaign_object = $campaign;

        if (empty($campaign)) {
            throw new HttpException(404, '计划id错误');
        }
        $campaign = $campaign->toArray();
        $app_list = AppApi::getApps(\UserInfo::getTargetUserId());
        if (!isset($campaign['app_id'])) {
            throw new HttpException(500, 'app接口调用失败');
        }
        $campaign['raw_opc'] = $campaign_object->getFormatOpc(false);
        $campaign['opc'] = $campaign_object->getFormatOpc();
        $campaign['budget'] = intval($campaign['budget']);
        $result->app = $app_list[$campaign['app_id']];
        $result->creative = $campaign['creative'];
        unset($campaign['creative']);
        $result->campaign = $campaign;

        return $result->toArray();
    }

    public function store()
    {
        $result = new Result();
        // 出价
        $min_budget = (Request::input('budget', 0) == 0) ? 0 : 50;
        $min_budget = ($min_budget > Request::input('price', 0) * 10) ? $min_budget : Request::input('price', 0) * 10;
        if (Request::input('budget', 0) < $min_budget && Request::input('budget', 0) != 0 && Request::input(
                'budget',
                0
            ) != -1
        ) {
            throw new HttpException(422, '日限额错误');
        }

        // 检测creative_id
        $creative = Creative::where('customer_id', \UserInfo::getTargetUserId())->where(
            'id',
            Request::input('creative_id', -1)
        )->where('status', CreativeType::COMMON)->where('audit_status', CreativeType::AUDIT_PASS)->first();

        if (empty($creative)) {
            throw new HttpException(422, 'creative_id 不存在');
        }

        // 检测mid状态
        $weibo_result = WeiboApi::getStatusInfoByMid($creative->mid);
        if ($weibo_result == false) {
            throw new HttpException(422, '创意微博内容异常');
        }

        // 检测重名
        $exist = Campaign::where('customer_id', \UserInfo::getTargetUserId())->where('name', Request::input('name'))
            ->where('status', '<>', CampaignType::DELETE_STATUS)->count();

        if (!empty($exist)) {
            throw new HttpException(422, '计划名称不能重复');
        }

        // 处理时间
        $start_time = Request::input('start_time', '1999-01-01 00:00:00');
        $end_time = Request::input('end_time', '2035-12-12 00:00:00');

        if (strtotime($start_time) >= strtotime($end_time) + 3600) {
            $end_time = date("Y-m-d H:i:s", strtotime($start_time));
        }
        $start_time = ($start_time < date('Y-m-d H:i:s')) ? date('Y-m-d H:i:s') : $start_time;
        $end_time = ($end_time > '2035-12-12 00:00:00' || $start_time == '1999-01-01 00:00:00') ? '2035-12-12 00:00:00' : $end_time;
        // 处理OPC
        $opc = $this->getOpc($creative['app_type']);

        DB::beginTransaction();
        $campaign = new Campaign();
        $campaign->opc = json_encode($opc);
        $campaign->type = 1;
        $campaign->customer_id = \UserInfo::getTargetUserId();
        $campaign->operator_id = \UserInfo::getCurrentUserId();
        $campaign->app_id = $creative->app_id;
        $campaign->creative_id = $creative->id;
        $campaign->start_time = $start_time;
        $campaign->end_time = $end_time;
        $campaign->name = Request::input('name');
        $campaign->price = Request::input('price');
        $campaign->budget = intval(Request::input('budget', 0));
        $campaign->budget = ($campaign->budget < 0) ? 0 : $campaign->budget;

        $monitor = '[]';
        if (Request::input('monitor_url')) {
            $monitor = $campaign->formatMonitorJson(Request::input('monitor_url'));
        }
        $campaign->monitor = $monitor;
        $campaign->status = CampaignType::WAIT_DELIVER_STATUS;

        // 如果是草稿
        if (Request::input('is_campaign_draft')) {
            $campaign->status = CampaignType::DRAFT_STATUS;
            $campaign->save();
            OpLog::write(
                OpLogType::TARGET_TYPE_CAMPAIGN,
                $campaign->id,
                $campaign->name,
                OpLogType::CONTENT_TYPE_CAMPAIGN_CREATE_DRAFT,
                '',
                Request::all()
            );
            DB::commit();
            $result->code = 201;
            $result->id = $campaign->id;
            $result->direct_url = '/app/campaigns/' . $result->id . '?customer_id='. UserInfo::getTargetUserId();
            $result->message = "计划创建成功。";

            return $result->toArray();
        }

        // 立即投放
        if (date('Y-m-d H:i:s') >= $start_time) {
            $campaign->status = CampaignType::DELIVERING_STATUS;
            // 检查余额
            $check_ret = SettleApi::getValidAccountsForOnline(UserInfo::getTargetUserId(), true);

            if (!$check_ret) {
                \LogFile::alert('账户余额查询失败', $campaign->toArray());
                throw new HttpException(500, '系统繁忙，账户余额查询失败。');
            }

            if (!$check_ret['allOk']) {
                if (!empty($check_ret['balanceLack'])) {
                    $campaign->status = CampaignType::PAUSE_STATUS;
                    if (!empty($check_ret['balanceLack'])) {
                        $campaign->stop_type = CampaignPauseType::ACCOUNT_BALANCE_LACK_STATUS;
                    } elseif (!empty($check_ret['budgetReach'])) {
                        $campaign->stop_type = CampaignPauseType::REACH_PRODUCT_BUDGET_STATUS;
                    } else {
                        \LogFile::alert('账户余额查询失败', $campaign->toArray());
                        throw new HttpException(500, '系统繁忙，账户余额查询失败。');
                    }
                }
            }
        }
        $campaign->save();

        $ad_detail = $campaign->getDetail();

        // 通知结算
        $settel_ret = SettleApi::createCampaign(
            UserInfo::getTargetUserId(),
            $campaign->id,
            $campaign->budget,
            $campaign->end_time
        );

        if (!$settel_ret) {
            // 报警
            \LogFile::alert('创建计划通知结算失败', $campaign->toArray());
            DB::rollback();
            throw new HttpException(500, '系统繁忙，创建计划失败。');
        }

        if ($campaign->status == CampaignType::DELIVERING_STATUS) {
            $engine_ret = EngineApi::create($campaign->id, $ad_detail, EngineType::ONLINE);
        } else {
            $engine_ret = EngineApi::create($campaign->id, $ad_detail, EngineType::PAUSE);
        }

        if (!$engine_ret) {
            \LogFile::alert('创建计划通知引擎失败', $campaign->toArray());
            DB::rollback();
            throw new HttpException(500, '系统繁忙，创建计划失败。。');
        }

        OpLog::write(
            OpLogType::TARGET_TYPE_CAMPAIGN,
            $campaign->id,
            $campaign->name,
            OpLogType::CONTENT_TYPE_CAMPAIGN_CREATE,
            '',
            Request::all()
        );
        DB::commit();
        $result->code = 201;
        $result->id = $campaign->id;
        $result->direct_url = '/app/campaigns/' . $result->id . '?customer_id='. UserInfo::getTargetUserId();
        $result->message = "计划创建成功！";

        return $result->toArray();
    }

    /**
     * 计划更新
     */
    public function update()
    {
        $result = new Result();

        $campaign = Campaign::where('customer_id', UserInfo::getTargetUserId())->
        where('status', '<>', CampaignType::STOP_STATUS)->
        where('status', '<>', CampaignType::DELETE_STATUS)->where('id', Request::input('id'))->first();

        if (empty($campaign)) {
            throw new HttpException(422, "计划id错误");
        }

        DB::beginTransaction();
        $campaign->version += 1;
        $lock = $this->lock($campaign->id);
        if (!$lock) {
            throw new HttpException(400, "计划请求超时");
        }

        if ($campaign->name != Request::input('name')) {
            // 检测重名
            $exist = Campaign::with('creative')->where('customer_id', \UserInfo::getTargetUserId())
                ->where('name', Request::input('name'))->where('status', '<>', CampaignType::DELETE_STATUS)
                ->where('id', '<>', $campaign->id)->count();

            if (!empty($exist)) {
                $this->unlock($lock);
                throw new HttpException(422, "计划名称不能重复");
            }
        }

        // 检测mid状态
        $weibo_result = WeiboApi::getStatusInfoByMid($campaign->creative->mid);
        if ($weibo_result == false) {
            $this->unlock($lock);
            throw new HttpException(422, '创意微博内容异常');
        }

        // 记录日志
        $old_campaign = clone $campaign;
        // 处理日限额
        $modify_budget = intval(Request::input('budget', 0));
        $modify_budget = ($modify_budget < 0) ? 0 : $modify_budget;
        $min_budget = ($modify_budget == 0) ? 0 : 50;
        $min_budget = ($min_budget > $modify_budget * 10) ? $min_budget : Request::input('price', 0) * 10;
        if (Request::input('budget', 0) < $min_budget && $modify_budget != 0 && Request::input(
                'budget',
                0
            ) != -1
        ) {
            $this->unlock($lock);
            throw new HttpException(422, "日限额错误");
        }

        //日限额有变动
        if ($modify_budget != $old_campaign->budget) {
            if(!in_array($campaign->status, array(CampaignType::DRAFT_STATUS, CampaignType::WAIT_DELIVER_STATUS))) {
                //把第二天的清掉，防止多次修改冲突
                $campaign->reverse_budget = 0;
                if($modify_budget == 0) {
                    // 不限日限额 立即生效
                    $campaign->budget = $modify_budget;
                } else if ($modify_budget > $old_campaign->budget && $old_campaign->budget != '0.00') {
                    // 改大日限额 立即生效
                    $campaign->budget = $modify_budget;

                    // 增幅判断 每次增幅需要大于100
                    if ($modify_budget - $old_campaign->budget < 100) {
                        throw new HttpException(422, "日限额每次增幅需要大于100");
                    }
                } else {
                    // 改小日限额 隔日生效
                    $campaign->reverse_budget = $modify_budget;
                }
            } else {
                $campaign->reverse_budget = 0;
                $campaign->budget = $modify_budget;
            }
        }

        $opc = json_encode($this->getOpc($campaign->creative['app_type']));
        $campaign->opc = $opc;
        $campaign->operator_id = \UserInfo::getCurrentUserId();
        $campaign->name = Request::input('name');
        $campaign->price = Request::input('price');
        $campaign->budget = ($campaign->budget < 0) ? 0 : $campaign->budget;

        //草稿状态才可修改监控
        if($campaign->status == CampaignType::DRAFT_STATUS){
            $monitor = '[]';
            if (Request::input('monitor_url')) {
                $monitor = $campaign->formatMonitorJson(Request::input('monitor_url'));
            }
            $campaign->monitor = $monitor;
        }
        // 处理时间 只有待投草稿需要修改开始时间
        $deliver_now = false;
        if ($campaign->status == CampaignType::WAIT_DELIVER_STATUS || $campaign->status == CampaignType::DRAFT_STATUS) {
            // 如果开始时间小于计划时间且大于当前时间
            if (Request::input('start_time') < $campaign->start_time) {
                $now_date_time = date('Y-m-d H:i:s');
                // 立即开始
                if ($now_date_time > Request::input('start_time')) {
                    $deliver_now = true;
                    $campaign->start_time = $now_date_time;
                } else {
                    $campaign->start_time = Request::input('start_time');
                }
                // 如果结束时间过早纠错
                if (strtotime($campaign->start_time) + 3600 > strtotime(Request::input('end_time'))) {
                    $campaign->end_time = date('Y-m-d H:i:s', strtotime($campaign->start_time) + 3600);
                } else {
                    $campaign->end_time = Request::input('end_time');
                }
            }
        } else {
            if (strtotime($campaign->start_time) + 3600 > strtotime(Request::input('end_time'))) {
                $campaign->end_time = date('Y-m-d H:i:s', strtotime($campaign->start_time) + 3600);
            } else {
                $campaign->end_time = Request::input('end_time');
            }
        }

        $campaign->end_time = $campaign->end_time > '2035-12-12 00:00:00' ? '2035-12-12 00:00:00' : $campaign->end_time;

        $is_budget_modify = false;
        $is_campaign_modify = false;
        $is_end_time_modify = false;

        // 日志
        if ($old_campaign->budget != $campaign->budget) {
            OpLog::write(
                OpLogType::TARGET_TYPE_CAMPAIGN,
                $campaign->id,
                $campaign->name,
                OpLogType::CONTENT_TYPE_CAMPAIGN_BUDGET,
                $old_campaign->budget,
                $campaign->budget
            );
            $is_budget_modify = true;
        }

        if ($campaign->monitor != $old_campaign->monitor) {
            $is_campaign_modify = true;
        }

        if ($old_campaign->budget == $campaign->budget && $old_campaign->reverse_budget != $campaign->reverse_budget) {
            OpLog::write(
                OpLogType::TARGET_TYPE_CAMPAIGN,
                $campaign->id,
                $campaign->name,
                OpLogType::CONTENT_TYPE_CAMPAIGN_BUDGET,
                $old_campaign->budget,
                $campaign->reverse_budget
            );
        }

        if ($old_campaign->opc != $campaign->opc) {
            OpLog::write(
                OpLogType::TARGET_TYPE_CAMPAIGN,
                $campaign->id,
                $campaign->name,
                OpLogType::CONTENT_TYPE_CAMPAIGN_CONDITION,
                $old_campaign->getFormatOpc(),
                $campaign->getFormatOpc()
            );
            $is_campaign_modify = true;
        }

        if ($old_campaign->price != $campaign->price) {
            OpLog::write(
                OpLogType::TARGET_TYPE_CAMPAIGN,
                $campaign->id,
                $campaign->name,
                OpLogType::CONTENT_TYPE_CAMPAIGN_PRICE,
                $old_campaign->price,
                $campaign->price
            );
            $is_campaign_modify = true;
        }

        if ($old_campaign->start_time != $campaign->start_time || $old_campaign->end_time != $campaign->end_time) {
            OpLog::write(
                OpLogType::TARGET_TYPE_CAMPAIGN,
                $campaign->id,
                $campaign->name,
                OpLogType::CONTENT_TYPE_CAMPAIGN_CYCLE,
                "{$old_campaign->start_time}-{$old_campaign->end_time}",
                "{$campaign->start_time}-{$campaign->end_time}"
            );
            $is_end_time_modify = true;
        }

        // 如果是草稿
        if (Request::input('is_campaign_draft') && $campaign->status == CampaignType::DRAFT_STATUS) {
            $campaign->save();
            $this->unlock($lock);
            DB::commit();
            $result->code = 200;
            $result->id = $campaign->id;
            $result->direct_url = '/app/campaigns/' . $result->id . '?customer_id='. UserInfo::getTargetUserId();
            $result->message = "计划修改成功。";
            return $result->toArray();
        }

        // 草稿转待投
        $is_draft = false;
        if (!Request::input('is_campaign_draft') && $campaign->status == CampaignType::DRAFT_STATUS) {
            $campaign->status = CampaignType::WAIT_DELIVER_STATUS;
            $is_draft = true;
        }

        if ($is_draft) {
            // 如果草稿转待投
            // 通知结算
            $settle = SettleApi::createCampaign(
                UserInfo::getTargetUserId(),
                $campaign->id,
                $campaign->budget,
                $campaign->end_time
            );

            if (!$settle) {
                // 报警
                \LogFile::alert('创建计划通知结算失败', $campaign->toArray());
                $this->unlock($lock);
                DB::rollback();
                throw new HttpException(500, '系统繁忙，创建计划失败。');
            }
        }

        // 立即开始逻辑
        if ($deliver_now) {
            $campaign->status = CampaignType::DELIVERING_STATUS;
            // 检查余额
            $check_ret = SettleApi::getValidAccountsForOnline(UserInfo::getTargetUserId(), true);
            if (!$check_ret) {
                \LogFile::alert('账户余额查询失败', $campaign->toArray());
                $this->unlock($lock);
                throw new HttpException(500, '系统繁忙，账户余额查询失败。');
            }
            if (!$check_ret['allOk']) {
                if (!empty($check_ret['balanceLack'])) {
                    $campaign->status = CampaignType::PAUSE_STATUS;
                    if (!empty($check_ret['balanceLack'])) {
                        $campaign->stop_type = CampaignPauseType::ACCOUNT_BALANCE_LACK_STATUS;
                    } elseif (!empty($check_ret['budgetReach'])) {
                        $campaign->stop_type = CampaignPauseType::REACH_PRODUCT_BUDGET_STATUS;
                    } else {
                        \LogFile::alert('账户余额查询失败', $campaign->toArray());
                        $this->unlock($lock);
                        throw new HttpException(500, '系统繁忙，账户余额查询失败。');
                    }
                }
            }

            $ad_detail = $campaign->getDetail();
            $engine_ret = true;
            // 如果不是草稿
            if (!$is_draft) {
                if ($campaign->status == CampaignType::DELIVERING_STATUS && $old_campaign->status != CampaignType::DELIVERING_STATUS) {
                    $engine_ret = EngineApi::modify($campaign->id, $ad_detail, EngineType::ONLINE);
                }
                if ($campaign->status != CampaignType::DELIVERING_STATUS && $old_campaign->status == CampaignType::DELIVERING_STATUS) {
                    $engine_ret = EngineApi::modify($campaign->id, $ad_detail, EngineType::PAUSE);
                }
            } else {
                //如果是草稿
                if ($campaign->status == CampaignType::DELIVERING_STATUS) {
                    $engine_ret = EngineApi::create($campaign->id, $ad_detail, EngineType::ONLINE);
                } else {
                    $engine_ret = EngineApi::create($campaign->id, $ad_detail, EngineType::PAUSE);
                }
            }

            if (!$engine_ret) {
                $this->unlock($lock);
                DB::rollback();
                throw new HttpException(500, "系统繁忙，更新失败。。");
            }
        } else {
            if ($is_budget_modify) {
                $settel_ret = SettleApi::modifyCampaignBudget($campaign->customer_id, $campaign->id, $campaign->budget);
                if (!$settel_ret) {
                    \LogFile::alert('创建计划通知结算失败', $campaign->toArray());
                    $this->unlock($lock);
                    DB::rollback();
                    throw new HttpException(500, '系统繁忙，更新失败。。。');
                }
            }

            if ($is_end_time_modify) {
                $settel_ret = SettleApi::modifyCampaignEndTime($campaign->customer_id, $campaign->id, $campaign->end_time);
                if (!$settel_ret) {
                    \LogFile::alert('创建计划通知结算失败', $campaign->toArray());
                    $this->unlock($lock);
                    DB::rollback();
                    throw new HttpException(500, '系统繁忙，更新失败。。。');
                }
            }

            if ($is_campaign_modify) {
                $detail = $campaign->getDetail();
                $engine_ret = EngineApi::modify($campaign->id, $detail, $detail['status']);
                if (!$engine_ret) {
                    \LogFile::alert('创建计划通知引擎失败', $campaign->toArray());
                    $this->unlock($lock);
                    DB::rollback();
                    throw new HttpException(500, "系统繁忙，更新失败。。");
                }
            }
        }

        if ($old_campaign->status != $campaign->status) {
            OpLog::write(
                OpLogType::TARGET_TYPE_CAMPAIGN,
                $campaign->id,
                $campaign->name,
                OpLogType::CONTENT_TYPE_CAMPAIGN_STATUS,
                CampaignType::getString($old_campaign->status),
                CampaignType::getString($campaign->status)
            );
        }

        $campaign->save();
        DB::commit();
        $this->unlock($lock);
        $result->code = 200;
        $result->id = $campaign->id;
        $result->direct_url = '/app/campaigns/' . $result->id . '?customer_id='. UserInfo::getTargetUserId();
        $result->message = "计划修改成功。";

        return $result->toArray();
    }

    public function delete()
    {
        $campaign = Campaign::with('creative')->where('customer_id', UserInfo::getTargetUserId())->
        where('status', '<>', CampaignType::DELETE_STATUS)->where('id', Request::input('id'))->first();

        if (empty($campaign)) {
            throw new HttpException(422, '计划id错误');
        }

        DB::beginTransaction();
        $lock = $this->lock($campaign->id);
        $campaign->version++;
        if (CampaignType::DRAFT_STATUS != $campaign->status && CampaignType::STOP_STATUS != $campaign->status) {
            $campaign->status = CampaignType::STOP_STATUS;
            $engine_result = EngineApi::stop($campaign->id, $campaign->getDetail());
            if (!$engine_result) {
                DB::rollback();
                $this->unlock($lock);
                throw new HttpException(500, '系统繁忙，计划删除失败');
            }
        }

        OpLog::write(
            OpLogType::TARGET_TYPE_CAMPAIGN,
            $campaign->id,
            $campaign->name,
            OpLogType::CONTENT_TYPE_CAMPAIGN_DELETE,
            '',
            ''
        );

        $campaign->status = CampaignType::DELETE_STATUS;

        $campaign->save();
        $this->unlock($lock);

        DB::commit();
        $result = new Result();
        $result->code = 200;
        $result->id = $campaign->id;
        $result->message = "计划删除成功！";

        return $result->toArray();

    }

    /**
     * 计划更新状态
     * @return array
     * @throws HttpException
     */
    public function updateStatus()
    {
        $campaign = Campaign::with('creative')->where('customer_id', UserInfo::getTargetUserId())->
        where('status', '<>', CampaignType::STOP_STATUS)->where('stop_type', CampaignPauseType::USER_OPERATION_STATUS)->where('id', Request::input('id'))->first();

        if (empty($campaign)) {
            throw new HttpException(422, '计划id错误');
        }
        $old_campaign = clone $campaign;
        $status = Request::input('status');
        $error = $status == $campaign->status;
        $error = $error || !in_array(
                $status,
                [
                    CampaignType::DELIVERING_STATUS,
                    CampaignType::WAIT_DELIVER_STATUS,
                    CampaignType::PAUSE_STATUS,
                    CampaignType::STOP_STATUS
                ]
            );
        $error = $error || ($status == CampaignType::PAUSE_STATUS && $campaign->status != CampaignType::DELIVERING_STATUS);
        $error = $error || ($status == CampaignType::DELIVERING_STATUS && $campaign->status != CampaignType::PAUSE_STATUS);

        if ($error) {
            throw new HttpException(422, '计划状态参数错误');
        }

        DB::beginTransaction();
        $lock = $this->lock($campaign->id);
        $campaign->version++;
        $campaign->status = $status;

        // 暂停
        if (CampaignType::PAUSE_STATUS == $status) {
            $engine_result = EngineApi::pause($campaign->id, $campaign->getDetail());
            if (!$engine_result) {
                DB::rollback();
                $this->unlock($lock);
                throw new HttpException(500, '系统繁忙，计划暂停失败');
            }
        }

        // 投放
        if (CampaignType::DELIVERING_STATUS == $status) {
            // 验证余额
            $check = SettleApi::getValidAccountsForOnline($campaign->customer_id, true);
            if (!$check) {
                \LogFile::alert('账户余额查询失败', $campaign->toArray());
                $this->unlock($lock);
                throw new HttpException(500, '系统繁忙，账户余额查询失败。');
            }

            $stop_type = 0;
            if (empty($check['allOk'])) {
                if (!empty($check['balanceLack'])) {
                    $stop_type = CampaignPauseType::ACCOUNT_BALANCE_LACK_STATUS;
                } elseif (!empty($check['budgetReach'])) {
                    $stop_type = CampaignPauseType::REACH_PRODUCT_BUDGET_STATUS;
                } elseif (!empty($check['blackList'])) {
                    $stop_type = CampaignPauseType::BLACKLIST_STATUS;
                } else {
                    \LogFile::alert('账户余额查询失败', $campaign->toArray());
                    $this->unlock($lock);
                    throw new HttpException(500, '系统繁忙，账户余额查询失败。');
                }

                // check mid
                $weibo_result = WeiboApi::getStatusInfoByMid($campaign->creative->mid);
                if ($weibo_result == false) {
                    $stop_type = CampaignPauseType::CAMPAIGN_NOT_EXIST_CONTENT_STATUS;
                }
            }

            if ($stop_type > 0) {
                $campaign->stop_type = $stop_type;
            } else {
                $campaign->status = CampaignType::DELIVERING_STATUS;
                $engine_result = EngineApi::online($campaign->id, $campaign->getDetail());
                if (!$engine_result) {
                    DB::rollback();
                    $this->unlock($lock);
                    throw new HttpException(500, '系统繁忙，计划开始失败');
                }
            }
        }

        // 删除
        if (CampaignType::DELETE_STATUS == $status) {
            if (CampaignType::DRAFT_STATUS != $old_campaign->status) {
                $engine_result = EngineApi::stop($campaign->id, $campaign->getDetail());
                if (!$engine_result) {
                    DB::rollback();
                    $this->unlock($lock);
                    throw new HttpException(500, '系统繁忙，计划删除失败');
                }
            }

        }

        if ($old_campaign->status != $campaign->status) {
            OpLog::write(
                OpLogType::TARGET_TYPE_CAMPAIGN,
                $campaign->id,
                $campaign->name,
                OpLogType::CONTENT_TYPE_CAMPAIGN_STATUS,
                CampaignType::getString($old_campaign->status),
                CampaignType::getString($campaign->status)
            );
        }
        $campaign->save();
        $this->unlock($lock);
        DB::commit();
        $result = new Result();
        $result->code = 200;
        $result->id = $campaign->id;
        $result->message = "计划状态修改成功！";

        return $result->toArray();
    }

    private function getOpc($app_type)
    {
        $data = [];

        $condition_map_by_type = Condition::getConditionMap();
        $fans = Request::input('fans', -1, false);
        if ($fans == -1) {
            foreach ($condition_map_by_type['fans'] as $k => $v) {
                $data['fans'][] = intval($v['id']);
            }
        } elseif (is_array($fans)) {
            foreach ($fans as $fan) {
                $data['fans_target_list'][] = intval($fan);
            }
            $data['fans'] = [602];
        } else {
            $data['fans'] = [$fans];
        }

        if (Request::input('age', -1) == -1) {
            foreach ($condition_map_by_type['fans_age'] as $k => $v) {
                $data['age'][] = intval($v['id']);
            }
        } else {
            $tmp_age = Request::input('age', -1);
            foreach ($condition_map_by_type['fans_age'] as $k => $v) {
                if ($tmp_age['start'] <= ($v['id'] - 1000) && $tmp_age['end'] >= ($v['id'] - 1000)) {
                    $data['age'][] = intval($v['id']);
                }
            }
        }

        if (Request::input('gender', -1) == -1) {
            foreach ($condition_map_by_type['gender'] as $k => $v) {
                $data['gender'][] = intval($v['id']);
            }
        } else {
            $data['gender'][] = intval(Request::input('gender', -1));
        }

        if (Request::input('location', -1) == -1) {
            foreach ($condition_map_by_type['location'] as $k => $v) {
                $data['location'][] = intval($v['id']);
            }
        } else {
            foreach (Request::input('location', -1) as $k => $v) {
                $data['location'][] = intval($v);
            }
        }

        if (Request::input('network_type', -1) == -1) {
            foreach ($condition_map_by_type['network_type'] as $k => $v) {
                $data['network_type'][] = intval($v['id']);
            }
        } else {
            foreach (Request::input('network_type', -1) as $k => $v) {
                $data['network_type'][] = intval($v);
            }
        }

        if ($app_type == AppType::ANDROID) {
            $data['device'][] = 110202;
        } else {
            $data['device'][] = 110201;
        }

        if (Request::input('min_os_version', -1) == -1) {
            if ($app_type == AppType::ANDROID) {
                $data['min_os_version'][] = 130201;
            } else {
                $data['min_os_version'][] = 130101;
            }
        } else {
            $data['min_os_version'][] = intval(Request::input('min_os_version'));
        }

        // hack unique
        array_walk($data, function (&$row) {
            $row = array_unique($row);
        });

        return $data;
    }

    private function lock($id)
    {
        $lock = DmlFactory::trylock(DmlFactory::REDLOCK, "campaign_{$id}", 30000, 30000);
        if (!$lock) {
            \Alert::send('campaign service get lock 失败', 'campaign service get lock 失败 id = '.$id);
            LogFile::error('campaign service get lock 失败', 'campaign service get lock 失败 id = '.$id);
            throw new HttpException(500, '计划繁忙修改失败');
        }

        return $lock;
    }

    private function unlock($lock)
    {
        DmlFactory::unlock($lock);
    }

    /**
     * 计划复制
     * @return array
     * @throws \Exception
     */
    public function duplicate()
    {
        $campaign_id = Request::input('id');
        $target_campaign = Campaign::where('id', $campaign_id)->first();
        if (empty($target_campaign)) {
            throw new HttpException(422, '计划id错误');
        }
        $target_campaign = $target_campaign->toArray();


        @$app = AppApi::getApps($target_campaign['customer_id'])[$target_campaign['app_id']];

        if (empty($app)) {
            throw new HttpException(500, ' 获取应用失败');
        }

        unset($target_campaign['id']);
        unset($target_campaign['stop_type']);
        $app_name = $app['name'] . '计划';
        if (mb_strlen($app['name'] . '计划', 'utf-8') > 15) {
            $app_name = mb_substr($app['name'] . '计划', 0, 15, 'utf-8') . '...';
        }
        $target_campaign['name'] = $app_name . date('YmdHi');
        $target_campaign['status'] = CampaignType::DRAFT_STATUS;
        $target_campaign['operator_id'] = UserInfo::getCurrentUserId();
        $campaign = new Campaign();
        $campaign->fill($target_campaign);

        $campaign->save();

        OpLog::write(
            OpLogType::TARGET_TYPE_CAMPAIGN,
            $campaign->id,
            $campaign->name,
            OpLogType::CONTENT_TYPE_CAMPAIGN_DUPLICATE,
            '复制ID:' . $campaign_id,
            '计划ID:' . $campaign->id
        );
        redirect('/app/campaigns/' . $campaign->id . '/edit?customer_id=' . UserInfo::getTargetUserId());
    }

    /**
     * 更新出价
     * @return array
     * @throws HttpException
     */
    public function updatePrice(){
        $result = new Result();
        $customer_id = UserInfo::getTargetUserId();
        $id = request::input("id");
        $price = number_format(request::input("price"), 2, '.', '');

        // 数据不为空校验
        if(empty($id) || empty($customer_id) || empty($price)) {
            $result->code = 1000;
            $result->message = "参数不合法！";
            return $result->toArray();
        }

        // 出价校验
        if( $price < 20 || $price > 1000) {
            $result->code = 1003;
            $result->message = "出价不合法，必须在20~1000之间！";
            return $result->toArray();
        }

        // 获取计划信息
        $campaign = Campaign::where('customer_id', $customer_id)->
        where('status', '<>', CampaignType::STOP_STATUS)->
        where('status', '<>', CampaignType::DELETE_STATUS)->where('id', $id)->first();

        if (empty($campaign)) {
            $result->code = 1001;
            $result->message = "计划id错误！";
            return $result->toArray();
        }

        // 原价格
        $old_price = $campaign->price;

        // 开始事务
        DB::beginTransaction();

        $campaign->price = $price;
        $campaign->version += 1;
        $lock = $this->lock($campaign->id);
        if (!$lock) {
            DB::rollback();
            $result->code = 1002;
            $result->message = "计划请求超时！";
            return $result->toArray();
        }

        // 检测mid状态
        $weibo_result = WeiboApi::getStatusInfoByMid($campaign->creative->mid);
        if ($weibo_result == false) {
            DB::rollback();
            $this->unlock($lock);
            $result->code = 1002;
            $result->message = "创意微博内容异常！";
            return $result->toArray();
        }

        // 通知引擎修改
        $detail = $campaign->getDetail();
        $engine_ret = EngineApi::modify($campaign->id, $detail, $detail['status']);
        if (!$engine_ret) {
            \LogFile::alert('创建计划通知引擎失败', $campaign->toArray());
            $this->unlock($lock);
            DB::rollback();
            $result->code = 1004;
            $result->message = "系统繁忙，更新失败。";
            return $result->toArray();
        }

        $campaign->save();
        DB::commit();
        $this->unlock($lock);

        // 记录日志
        OpLog::write(
            OpLogType::TARGET_TYPE_CAMPAIGN,
            $campaign->id,
            $campaign->name,
            OpLogType::CONTENT_TYPE_CAMPAIGN_PRICE,
            $old_price,
            $campaign->price
        );

        $result->code = 200;
        $result->id = $campaign->id;
        $result->message = "价格更新成功！";
        return $result->toArray();
    }
}
