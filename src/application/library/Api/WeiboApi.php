<?php namespace Api;


use Curl;
use Alert;
use LogFile;
use Config;

/**
 * Class     WeiboApi
 * WeiboApi调用类
 *
 * @author   yangyang3
 */
class WeiboApi {

	/**
	 * Variable  _default_uid
	 * 默认UID
	 *
	 * @author   yangyang3
	 * @static
	 * @var      string
	 */
	private static $_default_uid = '2654839837';

	/**
	 * Variable  _app_key
	 * APP_KEY
	 *
	 * @author   yangyang3
	 * @static
	 * @var      string
	 */
	private static $_app_key = '4009338982';

	/**
	 * Variable  _app_secret
	 * APP_SECRET
	 *
	 * @author   yangyang3
	 * @static
	 * @var      string
	 */
	private static $_app_secret = 'ffac76a449f5f2ec5a05e14097dcdee5';

	/**
	 * Variable  _object_signature
	 * 对象库签名
	 *
	 * @author   yangyang3
	 * @static
	 * @var      string
	 */
	private static $_object_signature = '22efe9efad434a88bd77cb2882f576c8';

	/**
	 * Variable ALLOW_COMMNET_TYPE
	 * 允许评论
	 *
	 * @author   yangyang3
	 * @static
	 * @var      int
	 */
	const ALLOW_COMMNET_TYPE = 0;

	/**
	 * Variable FORBID_COMMNET_TYPE
	 * 禁止评论
	 *
	 * @author   yangyang3
	 * @static
	 * @var      int
	 */
	const FORBID_COMMNET_TYPE = 1;

	/**
	 * Method  getUserInfoByUid
	 *
	 * @author yangyang3
	 * @static
	 *
	 * @param int $uid
	 *
	 * @return bool|mixed
	 */
	public static function getUserInfoByUid($uid) {
		$url_template = Config::get('weibo')['get_user_info'];

		$uid = intval($uid);

		$url = sprintf($url_template, self::$_app_key, trim($uid));

		$retry_count = intval(Config::get('api')['retry_count']);

		$result = '';

		while (($retry_count--) > 0) {
			//发送GET请求
			$result = Curl::get($url);

			//验证HttpCode
			if (Curl::getHttpCode() === 200) {
				break;
			}
		}

		$http_code = Curl::getHttpCode();

		LogFile::info("{$url}\t{$http_code}\t{$result}");

		/*
        //验证HttpCode
        if ($http_code !== 200) {
            Alert::send("获取用户[{$uid}]微博信息失败, HTTP状态码: {$http_code}!", $result);

            return false;
        }
        */

		$data = json_decode($result, true);

		//验证数据类型
		if (!is_array($data)) {
			LogFile::error("获取用户[{$uid}]微博信息失败, 返回内容格式错误!", $result);
			Alert::send("获取用户[{$uid}]微博信息失败, 返回内容格式错误!", $result);

			return false;
		}

		//验证是否有错误码和错误信息
		if (isset($data['error_code']) && isset($data['error'])) {
			LogFile::error("获取用户[{$uid}]微博信息失败, 错误码: {$data['error_code']}({$data['error']})!", $data);
			Alert::send("获取用户[{$uid}]微博信息失败, 错误码: {$data['error_code']}({$data['error']})!", $data);

			return false;
		}

		//验证返回值
		if (!isset($data['id']) || !isset($data['screen_name'])) {
			LogFile::error("获取用户[{$uid}]微博信息失败, 返回结果中不存在ID!", $data);
			Alert::send("获取用户[{$uid}]微博信息失败, 返回结果中不存在ID!", $data);

			return false;
		}

		return $data;
	}

	/**
	 * Method  getUserInfoByDomain
	 *
	 * @author yangyang3
	 * @static
	 *
	 * @param int $uid
	 *
	 * @return bool|mixed
	 */
	public static function getUserInfoByDomain($domain) {
		$url_template = Config::get('weibo')['show_domain'];

		$domain = trim($domain);

		$url = sprintf($url_template, self::$_app_key, trim($domain));

		$retry_count = intval(Config::get('api')['retry_count']);

		$token = self::_getTAuthToken();

		//验证token
		if (false === $token) {
			LogFile::error("获取域名[{$domain}]微博信息失败, 获取TAuthToken失败!");
			Alert::send("获取域名[{$domain}]微博信息失败, 获取TAuthToken失败!");

			return false;
		}

		$http_header = array('Authorization:' . sprintf('Token %s', base64_encode(self::$_default_uid . ':' . md5(self::$_default_uid . $token))));

		$result = '';

		while (($retry_count--) > 0) {
			//发送GET请求
			$result = Curl::get($url, null, $http_header);

			//验证HttpCode
			if (Curl::getHttpCode() === 200) {
				break;
			}
		}

		$http_code = Curl::getHttpCode();

		LogFile::info("{$url}\t{$http_code}\t{$result}");

		/*
        //验证HttpCode
        if ($http_code !== 200) {
            Alert::send("获取域名[{$domain}]微博信息失败, HTTP状态码: {$http_code}!", $result);

            return false;
        }
        */

		$data = json_decode($result, true);

		//验证数据类型
		if (!is_array($data)) {
			LogFile::error("获取域名[{$domain}]微博信息失败, 返回内容格式错误!", $result);
			Alert::send("获取域名[{$domain}]微博信息失败, 返回内容格式错误!", $result);

			return false;
		}

		//验证是否有错误码和错误信息
		if (isset($data['error_code']) && isset($data['error'])) {
			LogFile::error("获取域名[{$domain}]微博信息失败, 错误码: {$data['error_code']}({$data['error']})!", $result);
			Alert::send("获取域名[{$domain}]微博信息失败, 错误码: {$data['error_code']}({$data['error']})!", $result);

			return false;
		}

		//验证返回值
		if (!isset($data['id']) || !isset($data['screen_name'])) {
			LogFile::error("获取域名[{$domain}]微博信息失败, 返回结果中不存在ID!", $result);
			Alert::send("获取域名[{$domain}]微博信息失败, 返回结果中不存在ID!", $result);

			return false;
		}

		return $data;
	}

	/**
	 * Method  getUserAvatarByUId
	 *
	 * @author yangyang3
	 * @static
	 *
	 * @param int $uid
	 * @param int $image_size
	 *
	 * @return bool|string
	 */
	public static function getUserAvatarByUId($uid, $image_size = 180) {
		$url_template = Config::get('weibo')['get_user_avatar'];

		$uid = intval($uid);

		$image_size_list = array(
			30,
			50,
			180
		);

		if ($uid <= 0 || !in_array($image_size, $image_size_list)) {
			return false;
		}

		return sprintf($url_template, ($uid % 4) + 1, $uid, $image_size);
	}

	/**
	 * Method  getStatusInfoByMid
	 *
	 * @author yangyang3
	 * @static
	 *
	 * @param $mid
	 *
	 * @return array|bool|string
	 */
	public static function getStatusInfoByMid($mid) {
		$url_template = Config::get('weibo')['get_feed_info'];

		$url = sprintf($url_template, self::$_app_key, intval(trim($mid)));

		$retry_count = intval(Config::get('api')['retry_count']);

		$token = self::_getTAuthToken();

		//验证token
		if (false === $token) {
			LogFile::error("获取Feed[{$mid}]信息失败, 获取TAuthToken失败!");
			Alert::send("获取Feed[{$mid}]信息失败, 获取TAuthToken失败!");

			return false;
		}

		$http_header = array('Authorization:' . sprintf('Token %s', base64_encode(self::$_default_uid . ':' . md5(self::$_default_uid . $token))));

		$result = '';

		while (($retry_count--) > 0) {
			//发送GET请求
			$result = Curl::get($url, null, $http_header);

			//验证HttpCode
			if (Curl::getHttpCode() === 200) {
				break;
			}
		}

		$http_code = Curl::getHttpCode();

		LogFile::info("{$url}\t{$http_code}\t{$result}");

		/*
        //验证HttpCode
        if ($http_code !== 200) {
            Alert::send("获取Feed[{$mid}]信息失败, HTTP状态码: {$http_code}!", $result);

            return false;
        }
        */

		$data = json_decode($result, true);

		//验证数据类型
		if (!is_array($data)) {
			LogFile::error("获取Feed[{$mid}]信息失败, 返回内容格式错误!", $result);
			Alert::send("获取Feed[{$mid}]信息失败, 返回内容格式错误!", $result);

			return false;
		}

		//验证是否有错误码和错误信息
		if (isset($data['error_code']) && isset($data['error'])) {
			LogFile::error("获取Feed[{$mid}]信息失败, 错误码: {$data['error_code']}({$data['error']})!", $result);
			Alert::send("获取Feed[{$mid}]信息失败, 错误码: {$data['error_code']}({$data['error']})!", $result);

			return false;
		}

		//验证返回值
		if (!isset($data['id'])) {
			LogFile::error("获取Feed[{$mid}]信息失败, 返回结果中不存在MID!", $result);
			Alert::send("获取Feed[{$mid}]信息失败, 返回结果中不存在MID!", $result);

			return false;
		}

		return $data;
	}

	/**
	 * Method  publishStatus
	 *
	 * @author yangyang3
	 * @static
	 *
	 * @param int    $uid
	 * @param string $text
	 * @param string $picture_id
	 * @param string $photo_tag
	 *
	 * @return array|bool|mixed
	 */
	public static function publishStatus($uid, $text, $picture_id = null, $photo_tag = null) {
		$data = array();
		if (empty($uid)) {
			$data['error_code'] = '90003';
			return $data;
		}

		if (empty($text)) {
			$data['error_code'] = '90003';
			return $data;
		}

		if (!getenv('AUTH_ON')) {
			$uid = '2608812381';//@todo 防止误操作
		}
		$token = self::_getTAuthToken();
		//验证token
		if (false === $token) {
			Alert::send("用户[{$uid}]发布微博失败, 获取TAuthToken失败!");
			$data['error_code'] = '90002';
			return $data;
		}

		$http_header = array("Authorization:" . sprintf('Token %s', base64_encode($uid . ':' . md5($uid . $token))));
		$post_data = array(
			'source' => self::$_app_key,
			'status' => $text,
		);

		if (null === $picture_id) {
			$url = Config::get('weibo')['publish_feed'];
		} else {
			$url = Config::get('weibo')['publish_picture_feed'];
			$post_data['pic_id'] = $picture_id;
		}
		//标签
		if(!empty($photo_tag)){
			$post_data['photo_tag'] = $photo_tag;
		}

		$result = '';
		$retry_count = intval(Config::get('api')['retry_count']);
		while (($retry_count--) > 0) {
			//POST
			$result = Curl::post($url, $post_data, $http_header);
			//验证HttpCode
			if (Curl::getHttpCode() === 200) {
				break;
			}
		}

		$http_code = Curl::getHttpCode();
		LogFile::info("{$url}\t{$http_code}\t{$result}");
		LogFile::info(urldecode(http_build_query($post_data)));
		$data = json_decode($result, true);
		//验证数据类型
		if (!is_array($data)) {
			LogFile::error("用户[{$uid}]发布微博失败, 返回内容格式错误!", $result);
			Alert::send("用户[{$uid}]发布微博失败, 返回内容格式错误!", $result);
			$data['error_code'] = '90000';
			return $data;
		}
		//验证是否有错误码和错误信息
		if (isset($data['error_code']) && isset($data['error'])) {
			LogFile::error("用户[{$uid}]发布微博失败, 错误码: {$data['error_code']}({$data['error']})!", $result);
			Alert::send("用户[{$uid}]发布微博失败, 错误码: {$data['error_code']}({$data['error']})!", $result);
			return $data;
		}
		return $data;
	}


	/**
	 * 删除微博
	 * @param $mid
	 */
	public static function deleteStatus($uid, $mid)
	{
		if (empty($mid)) {
			return false;
		}

		$url = Config::get('weibo')['delete_feed'];;

		$token = self::_getTAuthToken();
		//验证token
		if (false === $token) {
			Alert::send("用户[{$uid}]发布微博失败, 获取TAuthToken失败!");

			return false;
		}

		$http_header = array("Authorization:" . sprintf('Token %s', base64_encode($uid . ':' . md5($uid . $token))));

		$post_data = array(
			'source' => self::$_app_key,
			'id' => $mid,
		);

		$retry_count = intval(Config::get('api')['retry_count']);

		$result = '';

		while (($retry_count--) > 0) {
			//POST
			$result = Curl::post($url, $post_data, $http_header);

			//验证HttpCode
			if (Curl::getHttpCode() === 200) {
				break;
			}
		}

		$http_code = Curl::getHttpCode();

		LogFile::info("{$url}\t{$http_code}\t{$result}");

		LogFile::info(urldecode(http_build_query($post_data)));

		$data = json_decode($result, true);

		//验证数据类型
		if (!is_array($data)) {
			LogFile::error("删除{$mid}微博失败, 返回内容格式错误!", $result);
			Alert::send("删除{$mid}微博失败, 返回内容格式错误!", $result);

			return false;
		}

		//验证是否有错误码和错误信息
		if (isset($data['error_code']) && isset($data['error'])) {
			LogFile::error("删除{$mid}微博失败, 错误码: {$data['error_code']}({$data['error']})!", $result);
			Alert::send("删除{$mid}微博失败, 错误码: {$data['error_code']}({$data['error']})!", $result);

			return false;
		}

		//验证返回结果
		if (!isset($data['id'])) {
			LogFile::error("删除{$mid}微博失败, 返回结果中不存在MID!", $result);
			Alert::send("删除{$mid}微博失败, 返回结果中不存在MID!", $result);

			return false;
		}

		return $data;
	}

	/**
	 * Method  allowCommentByMid
	 *
	 * @author yangyang3
	 * @static
	 *
	 * @param int $mid
	 *
	 * @return bool
	 */
	public static function allowCommentByMid($mid) {
		$url_template = Config::get('weibo')['allow_comment'];

		$url = sprintf($url_template, self::$_app_key, trim($mid), self::ALLOW_COMMNET_TYPE);

		$retry_count = intval(Config::get('api')['retry_count']);

		$result = '';

		while (($retry_count--) > 0) {
			//发送GET请求
			$result = Curl::get($url);

			//验证HttpCode
			if (Curl::getHttpCode() === 200) {
				break;
			}
		}

		$http_code = Curl::getHttpCode();

		LogFile::info("{$url}\t{$http_code}\t{$result}");

		/*
        //验证HttpCode
        if ($http_code !== 200) {
            Alert::send("Feed[{$mid}]允评失败, HTTP状态码: {$http_code}!", $result);

            return false;
        }
        */

		$data = json_decode($result, true);

		//验证数据类型
		if (!is_array($data)) {
			LogFile::error("Feed[{$mid}]允评失败, 返回内容格式错误!", $result);
			Alert::send("Feed[{$mid}]允评失败, 返回内容格式错误!", $result);

			return false;
		}

		//验证是否有错误码和错误信息
		if (isset($data['error_code']) && isset($data['error'])) {
			LogFile::error("Feed[{$mid}]允评失败, 错误码: {$data['error_code']}({$data['error']})!", $result);
			Alert::send("Feed[{$mid}]允评失败, 错误码: {$data['error_code']}({$data['error']})!", $result);

			return false;
		}

		//验证返回结果
		if (!isset($data['result']) || $data['result'] !== true) {
			LogFile::error("Feed[{$mid}]允评失败, 返回结果中result不为true!", $result);
			Alert::send("Feed[{$mid}]允评失败, 返回结果中result不为true!", $result);

			return false;
		}

		return true;
	}

	/**
	 * Method  forbidCommentByMid
	 *
	 * @author yangyang3
	 * @static
	 *
	 * @param int $mid
	 *
	 * @return bool
	 */
	public static function forbidCommentByMid($mid) {
		$url_template = Config::get('weibo')['forbid_comment'];

		$url = sprintf($url_template, self::$_app_key, trim($mid), self::FORBID_COMMNET_TYPE);

		$retry_count = intval(Config::get('api')['retry_count']);

		$result = '';

		while (($retry_count--) > 0) {
			//发送GET请求
			$result = Curl::get($url);

			//验证HttpCode
			if (Curl::getHttpCode() === 200) {
				break;
			}
		}

		$http_code = Curl::getHttpCode();

		LogFile::info("{$url}\t{$http_code}\t{$result}");

		/*
        //验证HttpCode
        if ($http_code !== 200) {
            Alert::send("Feed[{$mid}]禁评失败, HTTP状态码: {$http_code}!", $result);

            return false;
        }
        */

		$data = json_decode($result, true);

		//验证数据类型
		if (!is_array($data)) {
			LogFile::error("Feed[{$mid}]禁评失败, 返回内容格式错误!", $result);
			Alert::send("Feed[{$mid}]禁评失败, 返回内容格式错误!", $result);

			return false;
		}

		//验证是否有错误码和错误信息
		if (isset($data['error_code']) && isset($data['error'])) {
			LogFile::error("Feed[{$mid}]禁评失败, 错误码: {$data['error_code']}({$data['error']})!", $result);
			Alert::send("Feed[{$mid}]禁评失败, 错误码: {$data['error_code']}({$data['error']})!", $result);

			return false;
		}

		//验证返回结果
		if (!isset($data['result']) || $data['result'] !== true) {
			LogFile::error("Feed[{$mid}]禁评失败, 返回结果中result不为true!", $result);
			Alert::send("Feed[{$mid}]禁评失败, 返回结果中result不为true!", $result);

			return false;
		}

		return true;
	}

	/**
	 * Method  _getTAuthToken
	 *
	 * @author yangyang3
	 * @static
	 * @return bool
	 */
	private static function _getTAuthToken() {
		$url_template = Config::get('weibo')['get_tauth_token'];

		$url = sprintf($url_template, self::$_app_key);

		$post_data = array('app_secret' => self::$_app_secret);

		$retry_count = intval(Config::get('api')['retry_count']);

		$result = '';

		while (($retry_count--) > 0) {
			//发送POST请求
			$result = Curl::post($url, $post_data);

			//验证HttpCode
			if (Curl::getHttpCode() === 200) {
				break;
			}
		}

		$http_code = Curl::getHttpCode();

		LogFile::info("{$url}\t{$http_code}\t{$result}");

		/*
        //验证HttpCode
        if ($http_code !== 200) {
            return false;
        }
        */
		$data = json_decode($result, true);

		//验证数据类型
		if (!is_array($data)) {
			return false;
		}

		//验证是否有错误码和错误信息
		if (isset($data['error_code']) && isset($data['error'])) {
			return false;
		}

		//验证返回结果
		if (!isset($data['tauth_token'])) {
			return false;
		}

		return $data['tauth_token'];
	}



}