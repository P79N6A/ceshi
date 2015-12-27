<?php namespace Api;


class AgentApi {
    /**
     * 检查AgentId/CustomerId的隶属关系
     * @param      $agentId
     * @param      $customerId
     * @param bool $needRW
     *
     * @return bool
     */
    public static function checkJurisdiction($agentId, $customerId, $needRW=FALSE) {

        $enabled = getenv('agent_empower_switch');
        if(!$enabled) {
            return true;
        }

        $res = array();
        $data = '';
        $http_code = 0;
        $url_param = '';
        try {
            $curl = curl_init();
            $url = trim(getenv('agent_empower_api'));
            $username = trim(getenv('agent_empower_username'));
            $password = trim(getenv('agent_empower_password'));
            $url_param = 'parameter=checkEnterAdvert&loginUuId='.intval($agentId).'&customer_id='.intval($customerId);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_TIMEOUT, 10);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS,$url_param);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('userName:'.$username,'password:'.$password));
            $data = curl_exec($curl);

            $res = json_decode($data, true);
            $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            \LogFile::debug("代理商接口调用", [$url, 'POST', $http_code, $url_param, $res, !isset($res['code'])]);
        } catch (\Exception $e) {
            \Alert::send("代理商接口Exception", "http_code : {$http_code} result : {$data}");
        }
        if (isset($res['code']) && $res['code'] == '1'){
            return true;
        } elseif (!$needRW && isset($res['code']) && $res['code'] == '3003') {
            return true;
        } else {
            //当传来的customerId不在接口内，则报错
            \Alert::send("代理授权-判断隶属关系失败", " customerId: $customerId, agentId: $agentId, data: $url_param");
        }   
        return false;

    }

//    /**
//     * 临时接口， 检查用户的代理商是否在白名单中
//     * @param int $customerId
//     * @return bool
//     * @author wenyue1
//     */
//    public static function customerAgentInWhiteList($customerId) {
//
//        $enabled = \Yaf_Registry::get('config')->user_white_list->enable;
//
//        if(!$enabled) {
//            return true;
//        }
//
//        $url = sprintf('http://10.79.96.50:9678/gina-webservice/qushitong?uuid=%s', intval($customerId));
//        $data = json_decode(Curl::get($url), true);
//        $ret = (isset($data['code']) and $data['code'] == 1);
////        Log_Api::log($url, 'GET', $customerId, $data, !isset($data['code']));
//        return $ret;
//    }

    /**
     * 判断用户为自助用户或者代理商所属
     * @param wenyue1
     * @return bool
     */
    public static function userSelfService($customer_id) {
        $url  = getenv('progress_of_application');
        $data = array('clientUuId' => $customer_id);
        $ret = \Curl::post($url, $data);
        $result = json_decode($ret, true);
        if(
            is_array($result) 
            and isset($result['results']) 
            and $result['results'] == 'success' 
        ) {
            if($result['code']['self_service'] == '1' and $result['code']['status'] == '1' ) {
                return true;
            }else{
                return false;
            }
        }

        return false;
    }

}
