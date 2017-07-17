<?php
$url = 'http://weibo.com/ttarticle/p/show?id=2309404042895433856426#f56b0c9c17188e69d0e0ddb631bdb8c6_weiboad';
$object_id = '1022:100162146812';
generate_scheme_address($url, $object_id);

function generate_scheme_address($url, $object_id = null) {
    //获取配置信息
    $object_config['scheme']['default']['template '] = "sinaweibo://infopage?containerid=%s";
    $object_config['scheme']['userinfo']['template '] = "sinaweibo://infopage?containerid=%s";
    $object_config['scheme']['detail']['template '] = "sinaweibo://infopage?containerid=%s";
    $object_config['scheme']['pageinfo']['template '] = "sinaweibo://infopage?containerid=%s";
    $object_config['scheme']['infopage']['template '] = "sinaweibo://infopage?containerid=%s";
    $object_config['domain']['id'] = '1022';
    //获取域ID
    $domain_id = $object_config['domain']['id'];

    //获取业务号长度
    $object_number_length = 6;

    //获取scheme配置
    //$scheme_config = Yaf_Registry::get('scheme_config')->toArray();
    $scheme_config['default']['template'] = 'sinaweibo://infopage?containerid=%s';
    //定义默认的scheme
    $default_scheme = null;

    //验证object_id
    if (null !== $object_id) {
        $container_id = substr($object_id, strlen($domain_id) + 1);

        //生成默认的scheme地址
        $default_scheme = sprintf($scheme_config['default']['template'], $container_id);
    }

    //解析URL
    $url_info = parse_url($url);

    //验证path, host, 是否为weibo.com或m.weibo.cn
    if (empty($url_info['host']) || empty($url_info['path']) || !preg_match('/^(weibo\.com|m\.weibo\.cn)$/', $url_info['host'])) {
        //返回默认的scheme地址
        return $default_scheme;
    }

    $url_info['path'] = trim($url_info['path'], '/');

    //验证path
    if (0 === strlen($url_info['path'])) {
        //返回默认的scheme地址
        return $default_scheme;
    }

    //按"/"符号分割字path
    $path_list = explode('/', $url_info['path']);

    if (isset($path_list[0]) && $path_list[0] === 'u') {
        array_shift($path_list);
    }

    //验证$path_list
    if (empty($path_list)) {
        //返回默认的scheme地址
        return $default_scheme;
    }

    //验证是否为pageinfo类型
    if ('p' !== $path_list[0]) {
        //userinfo或detail类型, 验证第一段字符是数字格式还是字符串格式
        if (!Validator::is_numeric($path_list[0])) {
            //域名, 通过域名获取用户信息
            $result = WeiboApi::getUserInfoByDomain($path_list[0]);

            //验证返回结果
            if (empty($result)) {
                //返回默认的scheme地址
                return $default_scheme;
            } else {
                //返回userinfo的scheme地址
                return sprintf($scheme_config['userinfo']['template'], $result['id']);
            }
        } else {
            //UID, 通过UID获取用户信息
            $result = WeiboApi::getUserInfoByUid($path_list[0]);

            //验证返回结果
            if (empty($result)) {
                //返回默认的scheme地址
                return $default_scheme;
            }

            //验证第二段字符是否为MID
            if (!empty($path_list[1])) {
                //通过62进制MID转换为10进制MID
                $mid = MIDConverter::from62to10($path_list[1]);

                //验证MID是否为数字格式
                if (!empty($mid) && Validator::is_numeric($mid)) {
                    //获取MID信息
                    $result = WeiboApi::getStatusInfoByMid($mid);

                    //验证返回结果
                    if (isset($result['user']['id']) && (int)$result['user']['id'] === (int)$path_list[0]) {
                        //返回detail的scheme地址
                        return sprintf($scheme_config['detail']['template'], $mid);
                    }
                }
            }

            //返回userinfo的scheme地址
            return sprintf($scheme_config['userinfo']['template'], $path_list[0]);
        }
    } else {
        //page, 获取container_id
        $container_id = $path_list[1];

        //验证是否为个人主页
        if (!empty($container_id) && strlen($container_id) > 0) {
            //验证是否为UID
            if (strlen($container_id) > $object_number_length && Validator::is_numeric($container_id)) {
                //截取number
                $number = substr($container_id, 0, $object_number_length);

                //截取UID
                $uid = substr($container_id, $object_number_length);

                //验证UID
                if (in_array($number, $object_config['profile']['numbers']) && Validator::is_numeric($uid)) {
                    //通过UID获取用户信息
                    $result = WeiboApi::getUserInfoByUid($uid);

                    //验证返回结果
                    if (!empty($result) && (int)$uid === (int)$result['id']) {
                        //返回userinfo的scheme地址
                        return sprintf($scheme_config['userinfo']['template'], $result['id']);
                    }
                }

            }

            /*
            //过滤分隔符
            if (preg_match('/(.*)(?:_-_|-_-|\|)+/', $container_id, $matches)) {
                $container_id = !empty($matches[1]) ? $matches[1] : $container_id;
            }

            //获取业务号
            $object_number = substr($container_id, 0, $object_number_length);

            //验证是否为有对应类型的scheme地址
            if (isset($scheme_config[$object_number]['template'])) {
                //返回对应类型的scheme地址
                return sprintf($scheme_config[$object_number]['template'], $container_id);
            } else {
                Alert::sendAll("生成Scheme失败, 未知业务号: {$object_number}({$url})!");
            }
            */

            //调用接口获取Scheme
            $result = WeiboApi::getSchemeInfoByUrl($url);

            //验证返回结果
            if (isset($result['scheme'])) {
                //返回接口的Scheme
                return trim($result['scheme']);
            }
        }
    }

    //返回默认的scheme地址
    return $default_scheme;
}
