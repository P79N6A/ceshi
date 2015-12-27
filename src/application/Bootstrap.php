<?php

/**
 * Class     Bootstrap
 * 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用, 调用的次序和声明的次序相同
 * 这些方法, 都接受一个参数: Yaf_Dispatcher $dispatcher
 *
 */
class Bootstrap extends \Yaf_Bootstrap_Abstract
{

    /**
     * @param Yaf_Dispatcher $dispatcher
     *  初始化配置
     * @throws \Exception\HttpException
     * @author suchong
     */
    public function _initConfig(\Yaf_Dispatcher $dispatcher)
    {
        mb_internal_encoding('utf-8');
        $ini_file = ROOT_PATH . '/system/CONFIG';
        if (!is_file($ini_file)) {
            throw new \Exception\HttpException('Can\'t find the SINASRV_CONFIG.', 500);
        }
        $_SERVER = array_merge($_SERVER, parse_ini_file($ini_file));
        if ($dispatcher->getRequest()->isCli()) {
            ini_set('yaf.name_suffix', '1');
            ini_set('yaf.name_separator', '');
        }
    }

    /**
     * 初始化视图
     * @param Yaf_Dispatcher $dispatcher
     */
    public function _initView(Yaf_Dispatcher $dispatcher)
    {
        $dispatcher->autoRender(false);
        $dispatcher->disableView();
    }

    /**
     * 初始化路由
     * @param Yaf_Dispatcher $dispatcher
     */
    public function _initRoute(Yaf_Dispatcher $dispatcher)
    {
        if ($dispatcher->getRequest()->isCli()) {
            $command_line_plugin = new CommandLinePlugin();
            $dispatcher->registerPlugin($command_line_plugin);
        }
    }

}