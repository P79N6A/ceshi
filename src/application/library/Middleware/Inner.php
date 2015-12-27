<?php namespace Middleware;


use Base\Request;


class Inner implements \Base\Middleware {


    public function handle($request)
    {
        Request::setParam('_is_ajax', 1);
        if (getenv('DEBUG')) {
            return true;
        }

        $ip = new IpValidator();
        $result = $ip->isValidIp(getenv('IP_LIST'));

        if (!$result) {
            \LogFile::alert('ip limit', $ip->get_client_ip().var_export(Request::all(), true));
            \Alert::send('ip limit', $ip->get_client_ip());
            abort(403, 'ip limit');
        }
        //@todo 域名限制

        return true;
    }


}


/**
 * WeiboAd Team <adtech-bp@staff.sina.com.cn>
 * @filename Cidr.php
 * @date 15/8/20
 * @package bp
 */

Class IpValidator
{
    /**
     * Method  isValidIp
     * @author guangling<guangling1@staff.weibo.com>
     *
     * @param      $ipList
     * @param bool $enable
     *
     * @return bool
     */
    function isValidIp($ipList, $enable = true) {
        if(!$enable) {
            return true;
        }
        $ipPool     = explode(',', str_replace(' ', '', $ipList));
        $clientIp   = self::get_client_ip();
        \LogFile::info('inner ip', $clientIp);
        if (FALSE !== ($pos = strrpos($clientIp, ','))) {
            $clientIp = trim(substr($clientIp, $pos + 1));
        }
        if (!filter_var($clientIp, FILTER_VALIDATE_IP)) {
            return false;
        }
        foreach ($ipPool as $ip) {
            //cidr validate
            if ($pos    = strpos($ip, '/')) {
                list ($ip, $mask) = explode("/", $ip);
                $clientIpSeg = ip2long($clientIp);
                $ipSeg = ip2long($ip);
                $ip_mask = ((1 << (32 - $mask)) - 1) << $mask;
                $clientIpSeg_check = $clientIpSeg & $ip_mask;
                if ($clientIpSeg_check == $ipSeg) {
                    return true;
                }
            } elseif (0 === strcmp($clientIp, $ip)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Method  get_client_ip
     * @author guangling<guangling1@staff.weibo.com>
     *
     * @param bool $to_long
     *
     * @return null|string
     */
    function get_client_ip($to_long = false)
    {
        $forwarded = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : NULL;
        $REMOTE_ADDR = $_SERVER['REMOTE_ADDR'] ? $_SERVER['REMOTE_ADDR'] : NULL;
        if ($forwarded) {
            $ip_chains = explode(',', $forwarded);
            $proxied_client_ip = $ip_chains ? trim(array_pop($ip_chains)) : '';
        }

        if (self::is_private_ip($REMOTE_ADDR) && isset($proxied_client_ip)) {
            $real_ip = $proxied_client_ip;
        } else {
            $real_ip = $REMOTE_ADDR;
        }

        return $to_long ? self::ip2long($real_ip) : $real_ip;
    }

    /**
     * for example: 02.168.010.010 => 2.168.10.10
     *
     * @param string $ip
     * @return float 浣跨敤unsigned int琛ㄧず鐨刬p銆傚鏋渋p鍦板潃杞崲澶辫触锛屽垯浼氳繑鍥�0
     */
    public function ip2long($ip) {
        $ip_chunks = explode('.', $ip, 4);
        foreach ($ip_chunks as $i => $v) {
            $ip_chunks[$i] = abs(intval($v));
        }
        return sprintf('%u', ip2long(implode('.', $ip_chunks)));
    }

    /**
     * @param string $ip
     * @return boolean
     */
    public function is_private_ip($ip) {
        $ip_value = self::ip2long($ip);
        return ($ip_value & 0xFF000000) === 0x0A000000 ||   //10.0.0.0-10.255.255.255
        ($ip_value & 0xFFF00000) === 0xAC100000 ||   //172.16.0.0-172.31.255.255
        ($ip_value & 0xFFFF0000) === 0xC0A80000;     //192.168.0.0-192.168.255.255
    }
}
