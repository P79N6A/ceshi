<?php namespace Middleware;


use Api\SettleApi;
use Api\WeiboApi;
use Api\AgentApi;
use Base\Request;
use Exception\HttpException;
use Type\UserType;
use UserInfo;
use Models\Customer;


class Auth implements \Base\Middleware {


    public function handle($request)
    {
        if (!getenv('AUTH_ON')) {
            return true;
        }

        $customer_id = $this->getCustomerId();

        if (!$customer_id) {
            redirect("http://weibo.com/login.php?url=".get_current_page_url(), 302);
        } else {
            $current_user = WeiboApi::getUserInfoByUid($customer_id);

            if (false == $current_user) {
                redirect("http://weibo.com/login.php?url=".get_current_page_url(), 302);
            }
            UserInfo::setCurrentUserId($customer_id);
            UserInfo::setCurrentUserName($current_user['screen_name']);
            $targetUserId = intval(Request::input('customer_id', 0));
            if ($targetUserId <= 0 || $targetUserId == UserInfo::getCurrentUserId()) {
                //同一个人直接去验证
                //TargetUserId验证是否在customer表里， 并且有UC权限
                UserInfo::setTargetUserId($current_user['id']);
            } else {
                //附身时不同的人需要验证TargetUserId是否在customer表且有UC权限
                //并且CurrentUser对TargetUserId有管辖权
                UserInfo::setTargetUserId($targetUserId);
            }
            if ($customer_id !== $targetUserId) {
                $username = $this->userExists(UserInfo::getTargetUserId());
                UserInfo::setTargetUserName($username);
            } else {
                UserInfo::setTargetUserName($current_user['screen_name']);
            }
            $this->verifyRights(UserInfo::getTargetUserId(), UserInfo::getCurrentUserId());
        }

        $white_list = getenv('WHITE_LIST');

        if (!empty($white_list)) {
            $white_list = explode(',', $white_list);
            if (!in_array(UserInfo::getTargetUserId(), $white_list)) {
                abort(403, "current user has no right @whitelist ".__LINE__);
            }
        }

        return true;
    }

    /**
     * @param      $weibo_id
     * @param bool $api
     *
     * @return mixed
     */
    private function userExists($weibo_id, $api = false)
    {
        $weibo_id = intval($weibo_id);

        $customer = WeiboApi::getUserInfoByUid($weibo_id);
        if ($customer == array()) {
            if ($api) {
                abort(403, "user not exists @userExists");
            } else {
                abort(403, "用户在微博的状态异常，请登陆微博主站解封被操作用户 | current user has no right @userExists ".__LINE__);
            }
        }

        //check database
        $customer_local = Customer::find($weibo_id);
        if (empty($customer_local)) {
            $customer_local = new Customer;
            $customer_local->customer_id = $weibo_id;
            $customer_local->customer_name = $customer['screen_name'];

            $ret = SettleApi::modifyDailyQuota($customer_local->customer_id, 0);
            if (!$ret) {
                throw new HttpException(500, '通知结算失败。');
            }
            $result = $customer_local->save();
            if (empty($result)) {
                abort(403, "user not exists... @initUserInfo");
            }
        }

        return $customer['screen_name'];
    }

    private function getCustomerId()
    {
        \Sso_Sdk_Config::set_user_config(
            array(
                'service'   => getenv('SSO_SERVICES'),
                'entry'     => getenv('SSO_ENTRY'),
                'pin'       => getenv('SSO_PIN'),
                'domain'    => '.weibo.com',
                'idc'       => strtolower(getenv('SINASRV_ZONE_IDC')),
                'check_level' => 1,
            )
        );
        try {
            $sso = \Sso_Sdk_Client::instance();
            $user = $sso->get_user();
            if ($user->is_status_normal()) {
                return $user->get_uid();
            } else {
                return false;
            }
        } catch(\Exception $e) {
            \LogFile::error('sso error get customer uid error', [$e]);
            return false;
        }
    }

    private function verifyRights($weibo_id, $manager_id, $dienow = true)
    {
        $current_url = get_current_page_uri();
        $aduc_user = new \AdUC_User();
        $ret = $aduc_user->verifyUrl($weibo_id, 'apollo'.$current_url);
        // 注释掉则RBAC权限控制失效, 仅用于开发环境
        if (!isset($ret['retcode']) || $ret['retcode'] != 0) {
            if ($dienow) {
                abort(403, 'current user has no uc right');
            } else {
                return false;
            }
        }

        UserInfo::setCurrentUserType(UserType::BLUEV);
        if ($weibo_id != $manager_id) {
            //检查Id不一致时是否有管辖权
            //1.检查AdUC.Feed管理员权限

            $retFeedMgr = $this->isFeedProperManager($manager_id);

            //2.检查代理商接口
            $retAgent = false;
            if (!$retFeedMgr) {
                $retAgent = AgentApi::checkJurisdiction(
                    $manager_id,
                    $weibo_id,
                    $this->needRW()
                );
            }

            //当两个接口均不允许时， 返回失败
            if (!$retAgent && !$retFeedMgr) {
                if ($dienow) {
                    abort(403, 'current user has no right @agent or mgr '.__LINE__);
                } else {
                    return false;
                }
            } else {
                if ($retFeedMgr) {
                    UserInfo::setCurrentUserType(UserType::ADMIN);
                }
                if ($retAgent) {
                    UserInfo::setCurrentUserType(UserType::AGENT);
                }
                return true;
            }
        }

        return true;
    }

    private function needRW()
    {
        $prefix = '';
        $urls = require ROOT_PATH . '/config/uriRW.php';

        $urls = array_map('strtolower', $urls);

        return in_array(strtolower($prefix . get_current_page_uri()), $urls);
    }

    /**
     * 判断用户是否为粉丝通管理员
     * 当campaign/{add|create|edit}和ajax/{set_price|set_status}时判断读写
     * @param $manager_id
     *
     * @return bool
     */
    private function isFeedProperManager($manager_id)
    {
        $needRW = $this->needRW();
        $rw_role_id = 25; //粉丝通超管 aka 读写管理员
        $ro_role_id = 24; //粉丝通只读管理员

        \AdUC_Config::switchConfig('FEED');
        $handle = new \AdUC_User();
        $ret = $handle->getRolesByUserId($manager_id);
        \AdUC_Config::switchConfig('CARD');

        if ($ret['retcode'] == 0) {
            $raw_array = $ret['data'];
            if (!count($raw_array)) {
                return false;
            }
            $role_ids = array_column($raw_array, 'role_id');

            if ($needRW) { //RW必须是超管
                if (in_array($rw_role_id, $role_ids)) {
                    return true;
                }
            } else {
                //RW 和 RO 都可以
                if (in_array($rw_role_id, $role_ids)) {
                    return true;
                }
                if (in_array($ro_role_id, $role_ids)) {
                    return true;
                }
            }
        } else {
            return false;
        }
        return false;
    }
}
