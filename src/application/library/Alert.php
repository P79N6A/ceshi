<?php

class Alert
{
    private static $_alert = null;

    private function __construct()
    {

    }

    private static function getInstance()
    {

        if (!self::$_alert instanceof \Alert_Client) {
            self::$_alert = new \Alert_Client(ROOT_PATH . "/config/alert.php");
        }

        return self::$_alert;
    }

    public static function send($subject, $content = '',  $frequency = null, $summation = null, $repeat_standard=null, $type = null)
    {
        if (!getenv('ALERT')) {
            return true;
        }
        return self::getInstance()->send(
            $subject,
            $content,
            $frequency,
            $summation,
            $repeat_standard,
            $type
        );
    }
}