<?php namespace Services;

use Api\EngineApi;
use Api\ObjectApi;
use Api\SettleApi;
use Api\WeiboApi;
use Base\DB;
use Base\Request;
use Result;
use Exception\HttpException;
use Models\Campaign;
use Models\Creative;
use Models\Customer;
use Models\Consume;
use Models\OpLog;
use Type\CampaignType;
use Type\CreativeType;
use Type\EngineType;
use Type\OpLogType;
use Adinf\Dml\Factory as DmlFactory;

class InnerServices
{
    public $changebudget = false;

    public function accountOffline($customer_id,$stop_type){
        $campaign_list = Campaign::with('creative')->where('customer_id',$customer_id)->where('status',CampaignType::DELIVERING_STATUS)->get();
        foreach($campaign_list as $campaign){
            DB::beginTransaction();
            $lock = $this->lock($campaign->id);
            $campaign->version += 1;
            if (!$lock) {
                break;
            }
            $campaign->status = CampaignType::PAUSE_STATUS;
            $campaign->stop_type = $stop_type;
            try{
                $campaign->save();
            }catch (\Exception $e){
                \LogFile::error('accountOffline sql error',array($e));
                \Alert::send('accountOffline sql error','计划id:'.$campaign->id.'修改数据库失败');
                $this->unlock($lock);
                continue;
            }
            $ret = EngineApi::pause(
                $campaign->id,
                $campaign->getDetail()
            );
            if($ret){
                DB::commit();
                \LogFile::info('accountOffline success',$campaign->toArray());
            }else{
                DB::rollback();
            }
            $this->unlock($lock);
        }
        return true;
    }

    public function campaignOffline($campaign_id,$stop_type){
        $campaign = Campaign::with('creative')->where('id', $campaign_id)->whereIn(
            'status',
            [
                CampaignType::WAIT_DELIVER_STATUS,
                CampaignType::DELIVERING_STATUS,
            ]
        )->first();

        if (!$campaign) {
            return true;
        }

        DB::beginTransaction();
        $lock = $this->lock($campaign->id);
        $campaign->version += 1;
        if (!$lock) {
            return false;
        }
        $campaign->status = CampaignType::PAUSE_STATUS;
        $campaign->stop_type = $stop_type;
        try{
            $campaign->save();
        } catch (\Exception $e){
            \LogFile::error('campaignOffline sql error',array($e));
            \Alert::send('campaignOffline sql error','计划id:'.$campaign->id.'修改数据库失败');
            $this->unlock($lock);
            return false;
        }
        \LogFile::debug('begin pause');
        $ret = EngineApi::pause(
            $campaign->id,
            $campaign->getDetail()
        );
        \LogFile::debug('engine result', [$ret]);
        if($ret){
            DB::commit();
            \LogFile::info('campaignOffline success',$campaign->toArray());
        } else {
            \LogFile::info('通知引擎失败',$campaign->toArray());
            DB::rollback();
        }
        $this->unlock($lock);
        return true;
    }

    public function updateBudget($customer_id, $budget)
    {
        $customer = Customer::where('customer_id', $customer_id)->first();
        if (!$customer) {
            return false;
        }
        $old_budget = $customer->budget;
        DB::beginTransaction();
        if ($budget == $customer->budget && $customer->reverse_budget == 0) {
            return true;
        } elseif ($budget == 0) {
            $customer->budget = $budget;
            $customer->reverse_budget = 0;
        } elseif ($budget < $customer->budget || $customer->budget == 0) {
            $customer->reverse_budget = $budget;
        } elseif ($budget > $customer->budget) {
            $customer->budget = $budget;
            $customer->reverse_budget = 0;
        } else {
            return false;
        }
        try {
            $customer->save();
        } catch (\Exception $e) {
            \LogFile::error('update_budget sql error', array($e));
            \Alert::send('update_budget sql error', '用户:' . $customer->customer_id . '修改产品日限额时修改数据库失败');
            return false;
        }
        try {
            OpLog::write(
                OpLogType::TARGET_TYPE_ACCOUNT,
                $customer->customer_id,
                $customer->customer_name,
                OpLogType::CONTENT_TYPE_ACCOUNT_BUDGET,
                $old_budget,
                $budget
            );
        } catch (\Exception $e) {
            \LogFile::error('op_log sql error', array($e));
            \Alert::send('op_log sql error', '用户:' . $customer->customer_id . '修改产品日限额时记录操作日志失败');
            DB::rollback();
            return false;
        }
        if ($customer->reverse_budget == 0) {
            $ret = SettleApi::modifyDailyQuota($customer->customer_id, $customer->budget);
            $this->changebudget = true;
        } else {
            $ret = array("result" => true);
        }
        if ($ret["result"]) {
            \LogFile::info('update_budget success', $customer->toArray());
            DB::commit();
            return true;
        } else {
            DB::rollback();
            return false;
        }
    }

    public function onlineCustomerByStopType($customer_id,$stop_type){

        $customer = Customer::where('customer_id',$customer_id)->first();
        //获取账户校验的stop_type结果
        $ret_stop_type = $customer->getCampaignStopType();

        $campaign_list = Campaign::with('creative')
            ->where('customer_id',$customer_id)
            ->where('status',CampaignType::PAUSE_STATUS)
            ->where('stop_type',$stop_type)
            ->get();

        foreach($campaign_list as $campaign){
            DB::beginTransaction();
            $lock = $this->lock($campaign->id);
            $campaign->version += 1;
            if (!$lock) {
                return false;
            }
            if($ret_stop_type){
                $campaign->stop_type = $ret_stop_type;
            }else{
                $ret_stop_type = $campaign->getStopType();
                $campaign->stop_type = $ret_stop_type;
            }
            if($campaign->stop_type == 0){
                //stop_type 校验通过
                $campaign->status = CampaignType::DELIVERING_STATUS;
                try{
                    $campaign->save();
                }catch (\Exception $e){
                    \LogFile::error('onlineCustomerByStopType sql error',array($e));
                    \Alert::send('onlineCustomerByStopType sql error','计划id:'.$campaign->id.'修改数据库失败');
                    DB::rollback();
                    continue;
                }
                $ret = EngineApi::online(
                    $campaign->id,
                    $campaign->getDetail()
                );
                if($ret){
                    DB::commit();
                    \LogFile::info('onlineCustomerByStopType success',$campaign->toArray());
                }else{
                    DB::rollback();
                }
            }else{
                try{
                    $campaign->save();
                }catch (\Exception $e){
                    \LogFile::error('onlineCustomerByStopType sql error',array($e));
                    \Alert::send('onlineCustomerByStopType sql error','计划id:'.$campaign->id.'修改数据库失败');
                    DB::rollback();
                    continue;
                }
                DB::commit();
            }
        }
        return true;
    }

    public function audit() {
        $id = Request::input('unique_id');
        \LogFile::info('audit data', Request::all());

        $creative = Creative::where('id', $id)->where('status', '<>', CreativeType::DELETE)->first();

        if (empty($creative)) {
            throw new HttpException(404, 'id no exist');
        }

        if (Request::input('audit_status') == 1) {
            $creative->audit_status = CreativeType::AUDIT_PASS;
        } else {
            $creative->audit_status = CreativeType::AUDIT_REFUSE;
            $creative->audit_comment = Request::input('audit_comment');
            //删对象
            ObjectApi::delete($creative->object_id);
            //删微博
            WeiboApi::deleteStatus($creative->customer_id, $creative->mid);
            //发私信

        }
        \LogFile::info('audit success', $creative->toArray());

        $creative->save();
    }

    public function oplog($option) {
        $oplog = OpLog::where('customer_id',$option['customer_id']);
        if(isset($option['target_name']) && $option['target_name']){
            $oplog->where('target_name','like','%'.$option['target_name'].'%');
        }
        if(isset($option['target_type']) && $option['target_type']){
            $oplog->where('target_type',$option['target_type']);
        }
        if(isset($option['op_name']) && $option['op_name']){
            $oplog->where('op_name',$option['op_name']);
        }
        if(isset($option['create_time_start']) && $option['create_time_start']){
            $oplog->where('created_at','>=',$option['create_time_start']);
        }
        if(isset($option['create_time_end']) && $option['create_time_end']){
            $oplog->where('created_at','<=',$option['create_time_end']);
        }
        $count_sql = $oplog;
        $count = $count_sql->count();
        $total_page = ceil($count/$option['page_size']);
        $list = $oplog->forPage(intval($option['page']), $option['page_size'])
                     ->orderBy('created_at','desc')
                     ->get()->toArray();
        $ret = array(
            'total_page'    => $total_page,
            'list'          => $list
        );
        return $ret;
    }

    public function getCampaignList()
    {
        $pagination = Request::getPagination();
        $result = new Result();

        $condition = Campaign::with('customer');

        $keywords = Request::input("keywords");
        $has_consume = Request::input("has_consume");
        $status = Request::input("status");
        $column = Request::input("column");
        $customer_id = Request::input("customer_id");
        $app_id = Request::input("app_id");
        $customer_name = Request::input("customer_name");
        $id = Request::input("id");

        if (isset($id)) {
            $condition = $condition->where('id', $id);
        }
        if (isset($customer_name)) {
            $condition = $condition->whereIn('customer_id', Customer::where('customer_name', 'like', "%$customer_name%")->get(['customer_id'])->toArray());
        }
        if (!empty($has_consume)) {
            $condition = $condition->whereIn('id', Consume::where('post_date', date('Y-m-d'))->where('consume', '>', 0)->get(['campaign_id'])->toArray());
        }
        if (isset($keywords)) {
            $condition = $condition->where('name', 'like', "%$keywords%");
        }
        if (isset($customer_id)) {
            $condition = $condition->where('customer_id', $customer_id);
        }
        if (isset($app_id)) {
            $condition = $condition->where('app_id', $app_id);
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
                $campaign['customer_id']
            )->where('campaign_id', $campaign['id'])->first();
            $campaign['consume'] = (empty($today)) ? 0 : round($today->consume, 2);
            $total_consume = DB::table('consume_campaign')->where(
                'customer_id',
                $campaign['customer_id']
            )->where('campaign_id', $campaign['id'])->sum('consume');
            $campaign['total_consume'] = round($total_consume, 2);
        }

        $result->data = [
            $data
        ];

        $result->total_count = $total;

        return $result->toArray();
    }

    public function getCreativeList()
    {
        $pagination = Request::getPagination();
        $result = new Result();

        $condition = Creative::with('customer')->where(
            'status',
            CreativeType::COMMON
        );

        $keywords = Request::input("keywords");
        $column = Request::input("column");
        $app_id = Request::input("app_id");
        $disable_comment = Request::input("disable_comment");
        $audit_status = Request::input("audit_status");
        $customer_name = Request::input("customer_name");
        $customer_id = Request::input("customer_id");
        $id = Request::input("id");

        if (isset($id)) {
            $condition = $condition->where('id', $id);
        }
        if (isset($customer_name)) {
            $condition = $condition->whereIn('customer_id', Customer::where('customer_name', 'like', "%$customer_name%")->get(['customer_id'])->toArray());
        }
        if (isset($customer_id)) {
            $condition = $condition->where('customer_id', $customer_id);
        }
        if (isset($keywords)) {
            $condition = $condition->where(
                function ($query) use ($keywords) {
                    $query->where('name', 'like', "%$keywords%")
                        ->orWhere('content', 'like', "%$keywords%");
                }
            );
        }
        if (isset($app_id)) {
            $condition = $condition->where('app_id', $app_id);
        }
        if (isset($disable_comment)) {
            $condition = $condition->where('disable_comment', $disable_comment);
        }
        if (isset($audit_status)) {
            $condition = $condition->where('audit_status', $audit_status);
        }

        $start_time = Request::input('start_time');

        if (isset($start_time)) {
            $condition = $condition->where('created_at', '>=', $start_time . ' 00:00:00');
            $end_time = Request::input('end_time');
            if (isset($end_time)) {
                $condition = $condition->where('created_at', '<=', $end_time . ' 23:59:59');
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

        $result->data = [
            $data
        ];

        $result->total_count = $total;

        return $result->toArray();
    }

    /**
     * Method  resend
     * 向引擎重发消息
     *
     * @author luoliang1
     */
    public function resend() {
        $ad_id = (int)Request::input('ad_id');
        $type = (int)Request::input('type');
        $status = (int)Request::input('status');

        $operation = array(
            100 => 'create',
            200 => 'modify',
            300 => 'simpleModify'
        );

        if(!in_array($type, array_keys($operation))) {
            throw new HttpException(404, 'type error');
        }


        if(!in_array($status, EngineType::getList())) {
            throw new HttpException(404, 'status error');
        }

        $campaign = Campaign::where('id', $ad_id)->first();

        if(empty($campaign)) {
            throw new HttpException(404, 'campaign not exist');
        }

        // 更新计划版本号
        $campaign->version++;
        $campaign->save();

        $detail = $campaign->getDetail();

        $restul = \Api\EngineApi::$operation[$type]($ad_id, $detail, $status);

        return array('message' => $restul);
    }

    /**
     * Method  updateStaticVersion
     * 更新前端版本号
     *
     * @author luoliang1
     */
    public function updateStaticVersion(){
        $vesion_path = ROOT_PATH . '/cache';
        if(!is_dir($vesion_path)) {
            mkdir($vesion_path, 0755, true);
        }

        file_put_contents($vesion_path . '/STATIC_VERSION', date('Y-m-d_H-i-s'));
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
}