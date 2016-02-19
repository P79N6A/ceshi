<?php

/**
 * Created by IntelliJ IDEA.
 * User: haicheng
 * Date: 16/2/19
 * Time: 下午2:52
 */
class Multicurlclass
{
    //一次并发数量
    private $limit = 2;

    //URL集合
    private $urls;

    private $timeout = 10;

    private $ret;

    private $url_list = array();
    private $curl_setopt = array(
        'CURLOPT_RETURNTRANSFER' => 1,//结果返回给变量
        'CURLOPT_HEADER' => 0,//是否需要返回HTTP头
        'CURLOPT_NOBODY' => 0,//是否需要返回的内容
        'CURLOPT_FOLLOWLOCATION' => 0,//自动跟踪
        'CURLOPT_TIMEOUT' => 6//超时时间(s)
    );

    function __construct($seconds = 30)
    {
        set_time_limit($seconds);
    }

    /*
     * 设置网址
     * @list 数组
     */
    public function setUrlList($list = array())
    {
        $this->url_list = $list;
    }

    /*
     * 设置参数
     * @cutPot array
     */
    public function setOpt($cutPot)
    {
        $this->curl_setopt = $cutPot + $this->curl_setopt;
    }

    /*
     * 执行
     * @return array
     */
    public function execute()
    {
        $mh = curl_multi_init();
        foreach ($this->url_list as $k => $url) {
            print_r($url);
            $conn[$k] = curl_init($url);
            curl_setopt($conn[$k], CURLOPT_TIMEOUT, $this->timeout);//设置超时时间
            curl_setopt($conn[$k], CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
            curl_setopt($conn[$k], CURLOPT_MAXREDIRS, 7);//HTTp定向级别 ，7最高
            curl_setopt($conn[$k], CURLOPT_HEADER, false);//这里不要header，加块效率
            curl_setopt($conn[$k], CURLOPT_FOLLOWLOCATION, 1); // 302 redirect
            curl_setopt($conn[$k], CURLOPT_RETURNTRANSFER,1);//要求结果为字符串且输出到屏幕上
            curl_setopt($conn[$k], CURLOPT_HTTPGET, true);
            curl_multi_add_handle($mh, $conn[$k]);
        }
        $active = false;
        do {
            $mrc = curl_multi_exec($mh, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);
        while ($active and $mrc == CURLM_OK) {
            if (curl_multi_select($mh) != -1) {
                do {
                    $mrc = curl_multi_exec($mh, $active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }
        $res = array();
        foreach ($this->url_list as $i => $url) {
            $res[$i] = curl_multi_getcontent($conn[$i]);
            curl_close($conn[$i]);
            curl_multi_remove_handle($mh, $conn[$i]);//释放资源
        }
        curl_multi_close($mh);
        return $res;
    }
}