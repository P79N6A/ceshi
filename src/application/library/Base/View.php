<?php namespace Base;

use Philo\Blade\Blade;
use Yaf_Registry;

/**
 * Class BladeView
 *
 * @desc 重写View
 */
class View implements \Yaf_View_Interface
{
    /* constants */

    /* properties */
    protected $_tpl_vars = null;

    protected $_tpl_dir = null;

    protected $_options = null;

    protected $_cache_dir = null;

    private static $_instance = null;

    /* methods */
    final public function __construct()
    {

    }

    public static function getInstance() {
        if (!self::$_instance instanceof Blade) {
            self::$_instance = new Blade(Yaf_Registry::get('view_path'), Yaf_Registry::get('view_cache_path'));
        }

        return self::$_instance;
    }

    public static function make($template, $data) {

        return self::getInstance()->view()->make($template, $data);
    }

    public function assign($name, $value = null)
    {
        //@todo
        return null;
    }

    public function render($tpl, $tpl_vars = null)
    {
        return View::make($tpl, (array)$tpl_vars);
    }

    public function display($tpl, $tpl_vars = null)
    {
        echo View::make($tpl, (array)$tpl_vars);
    }

    public function assignRef($name, &$value)
    {
        //@todo
        return false;
    }

    public function clear($name = null)
    {
        //@todo
        return false;
    }

    public function setScriptPath($template_dir, $cache_dir=null)
    {
        $cache_dir = empty($cache_dir)? $template_dir.'/cache' : $cache_dir;
        $this->_tpl_dir = $template_dir;
        $this->_cache_dir = $cache_dir;
        \Yaf_Registry::set('view_path', $template_dir);
        \Yaf_Registry::set('view_cache_path', $cache_dir);
    }

    public function getScriptPath()
    {
        return \Yaf_Registry::get('view_path');
    }

    public function getCachePath()
    {
        return \Yaf_Registry::get('view_cache_path');
    }
}
