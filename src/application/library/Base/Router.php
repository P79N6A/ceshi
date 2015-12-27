<?php namespace Base;


/**
 * Class BaseRoute
 * @desc 重写路由规则
 */
class Router implements \Yaf_Route_Interface
{
    /**
     * <p><b>\Yaf\Route_Interface::route()</b> is the only method that a custom route should implement.</p><br/>
     * <p>if this method return TRUE, then the route process will be end. otherwise,\Yaf\Router will call next route in the route stack to route request.</p><br/>
     * <p>This method would set the route result to the parameter request, by calling \Yaf\Request_Abstract::setControllerName(), \Yaf\Request_Abstract::setActionName() and \Yaf\Request_Abstract::setModuleName().</p><br/>
     * <p>This method should also call \Yaf\Request_Abstract::setRouted() to make the request routed at last.</p>
     *
     * @link http://www.php.net/manual/en/yaf-route-interface.route.php
     *
     * @param \Yaf\Request_Abstract $request
     * @desc 默认module和controller均写死为Index了
     * @return bool
     */
    public function route($request)
    {
        $uri = '';
        try {
            require __DIR__ . '/../../route.php';
            $middlewareClassMap = require ROOT_PATH . '/config/middleware.php';
            $routeList = \Yaf_Registry::get('routeList');
            if (!$routeList) {
                abort(404, 'routeList null');
            }
            $dispatcher = \FastRoute\cachedDispatcher(
                function (\FastRoute\RouteCollector $r) use ($routeList, $middlewareClassMap, $request) {
                    foreach ($routeList as $route) {
                        $r->addRoute($route['method'], $route['route'], $route['handle']);
                    }
                },
                [
                    'cacheFile' => ROOT_PATH . '/storage/route/route.cache', /* required */
                    'cacheDisabled' => !getenv('ROUTER_CACHE'), /* optional, enabled by default */
                ]
            );
            $uri = get_current_page_uri();
            if (substr($uri, -1) === '/' && strlen($uri) > 1) {
                $uri = substr($uri, 0, -1);
            }
            $routeInfo = $dispatcher->dispatch(
                $_SERVER['REQUEST_METHOD'],
                $uri
            );

            switch ($routeInfo[0]) {
                case \FastRoute\Dispatcher::NOT_FOUND:
                    // ... 404 Not Found
                    abort(404);
                    break;
                case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                    // ... 405 Method Not Allowed
                    abort(405);
                    break;
                case \FastRoute\Dispatcher::FOUND:
                    $vars = $routeInfo[2];
                    if (!empty($vars)) {
                        foreach ($vars as $key => $var) {
                            Request::setParam($key, preg_replace('/\?.*/e', '', $var));
                        }
                    }
                    // ... call $handler with $vars
                    $handlers = explode('@', $routeInfo[1][0]);

                    // execute middleware
                    if (!empty($routeInfo[1][1])) {
                        $middlewares = (array)$routeInfo[1][1];
                        foreach ($middlewares as $middleware) {
                            if (isset($middlewareClassMap[$middleware]) && @class_exists(
                                    $middlewareClassMap[$middleware]
                                )
                            ) {
                                $middlewareInstance = new $middlewareClassMap[$middleware]();
                                $middlewareInstance->handle($request);
                            }
                        }
                    }

                    $request->setModuleName('Index');
                    $request->setControllerName(str_replace('Controller', '', $handlers[0]));
                    if ($handlers[1] === '_____resource') {
                        //restful request
                        if ($this->getMethod() === 'GET') {
                            if (!isset($routeInfo[2]['action_id'])) {
                                $request->setActionName('index');
                                return true;
                            } else {
                                if (isset($routeInfo[2]['edit'])) {
                                    $request->setActionName('edit');
                                    return true;
                                } elseif ($routeInfo[2]['action_id'] === 'create') {
                                    $request->setActionName('create');
                                    return true;
                                } else {
                                    $request->setActionName('show');
                                    return true;
                                }
                            }
                        } else {
                            if (!isset($routeInfo[2]['action_id'])) {
                                $request->setActionName('store');
                                return true;
                            } else {
                                if (strtoupper(Request::input('_method')) === 'PUT' || strtoupper(Request::input('_method')) === 'PATCH'
                                    || strtoupper($this->getMethod()) === 'PUT' || strtoupper($this->getMethod()) === 'PATCH'
                                ) {
                                    $request->setActionName('update');
                                    return true;
                                }

                                if (strtoupper(Request::input('_method')) === 'DELETE' || $this->getMethod(
                                    ) === 'DELETE'
                                ) {
                                    $request->setActionName('delete');
                                    return true;
                                }
                            }
                        }


                        abort(405);
                        return true;
                    } else {
                        // if not restful request
                        $request->setActionName($handlers[1]);
                        if (!empty($vars)) {
                            foreach ($vars as $key => $var) {
                                Request::setParam($key, preg_replace('/\?.*/e', '', $var));
                            }
                        }
                    }
                    break;
            }
        } catch (\Exception $e) {
            if (getenv('DEBUG')) {
                \LogFile::error("cant find route 400 ".var_export( $_SERVER['REQUEST_METHOD'], $uri));
                echo $e;
                abort(400, 'inner error');
            } else {
                \LogFile::error("cant find route 400 ".var_export( $_SERVER['REQUEST_METHOD'], $uri));
                abort(400, 'inner error');
            }
        }
        return true;
    }

    private function getMethod()
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    public function assemble(array $mvc, array $query = NULL)
    {

    }
}
