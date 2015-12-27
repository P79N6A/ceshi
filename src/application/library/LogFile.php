<?php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

/**
 * Class LogFile
 * @author suchong
 */
class LogFile
{

    private static $_monolog = null;

    private function __construct()
    {

    }

    private static $_level_map = array(
        'DEBUG' => 100,
        'INFO' => 200,
        'NOTICE' => 250,
        'WARNING' => 300,
        'ERROR' => 400,
        'CRITICAL' => 500,
        'ALERT' => 550,
        'EMERGENCY' => 600,
    );

    private static function log()
    {
        if (!self::$_monolog instanceof Logger) {
            $name = getenv('PROJECT_NAME');
            self::$_monolog = new Logger($name);
            //设置日志格式
            $formatter = new LineFormatter("%datetime% %level_name% %message% %context%\n", "Y-m-d H:i:s");
            //设置目录日志等级
            $path = ROOT_PATH . "/storage/logs";
            $date = date('Y-m-d');
            //按天切割
            $file = $path . "/{$name}{$date}.log";
            $stream = new StreamHandler($file, self::$_level_map[getenv('LOG_LEVEL')]);
            $stream->setFormatter($formatter);
            self::$_monolog->pushHandler($stream);
        }

        return self::$_monolog;
    }

    public static function debug($message, $trace = array())
    {
        return self::log()->addDebug($message, (array)$trace);
    }

    public static function info($message, $trace = array())
    {
        return self::log()->addInfo($message, (array)$trace);
    }

    public static function notice($message, $trace = array())
    {
        return self::log()->addNotice($message, (array)$trace);
    }

    public static function warning($message, $trace = array())
    {
        return self::log()->addWarning($message, (array)$trace);
    }

    public static function error($message, $trace = array())
    {
        return self::log()->addError($message, (array)$trace);
    }

    public static function critical($message, $trace = array())
    {
        return self::log()->addCritical($message, (array)$trace);
    }

    public static function alert($message, $trace = array())
    {
        return self::log()->addAlert($message, (array)$trace);
    }

    public static function emergence($message, $trace = array())
    {
        return self::log()->addEmergency($message, (array)$trace);
    }
}
