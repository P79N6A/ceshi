<?php namespace Models;

use Base\Model;
use Type\CampaignPauseType;
use Api\SettleApi;

class Customer extends Model
{
    protected $primaryKey = 'customer_id';
    protected $connection = 'app';


    public function getCampaignStopType()
    {
        // 从结算获取余额、限额结果
        $check = SettleApi::getValidAccountsForOnline($this->customer_id, true);
        if (!$check) {
            return false;
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
                return false;
            }
        }
        return $stop_type;
    }
}