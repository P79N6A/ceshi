<?php

final class UserInfo {

    private static $_currentUId = 0;
    private static $_currentUserName = '未初始化操作用户名';
    private static $_targetUId = 0;
    private static $_targetUserName = '未初始化用户名';
    private static $_currentUserType = -1;

    /**
     * 真实用户身份
     */
    public static function setCurrentUserId($id) {
        self::$_currentUId = $id;
    }

    public static function getCurrentUserId() {
        return self::$_currentUId;
    }

    public static function setCurrentUserType($type) {
        self::$_currentUserType = $type;
    }

    /**
     * 当GET参数中customer_id存在时附身的Id
     */
    public static function setTargetUserId($id) {
        self::$_targetUId = $id;
    }

    public static function getTargetUserId() {
        return self::$_targetUId;
    }

    public static function setTargetUserName($strUserName) {
        self::$_targetUserName = $strUserName;
    }

    public static function getTargetUserName() {
        return self::$_targetUserName;
    }

    public static function setCurrentUserName($strUserName) {
        self::$_currentUserName = $strUserName;
    }

    public static function getCurrentUserName() {
        return self::$_currentUserName;
    }
    
    public static function getCurrentUserType() {
        return self::$_currentUserType;
    }

    private static $_ = array();

    public static function add($key, $value) {

        if(isset(self::$_[$key])) {
            return false;
        }

        self::$_[$key] = $value;
        return true;

    }

    public static function set($key, $value) {

        self::$_[$key] = $value;

        return true;

    }

    public static function get($key) {

        if(!isset(self::$_[$key])) {

            return false;

        }

        return self::$_[$key];

    }

}
