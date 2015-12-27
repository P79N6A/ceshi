<?php namespace Base;

use Yaf_Registry;


class Route
{

    public static function get($route, $handle, $middleware=null)
    {
        $newRoute = ['method' => 'GET', 'route' => $route, 'handle' => [$handle, $middleware]];
        $routeList = Yaf_Registry::get('routeList');
        $routeList[] = $newRoute;
        Yaf_Registry::set('routeList', $routeList);
    }

    public static function post($route, $handle, $middleware=null)
    {
        $newRoute = ['method' => 'POST', 'route' => $route, 'handle' => [$handle, $middleware]];

        $routeList = Yaf_Registry::get('routeList');
        $routeList[] = $newRoute;
        Yaf_Registry::set('routeList', $routeList);
    }

    public static function any($route, $handle, $middleware=null)
    {
        $newRoute = ['method' => ['GET', 'POST'], 'route' => $route, 'handle' => [$handle, $middleware]];
        $routeList = Yaf_Registry::get('routeList');
        $routeList[] = $newRoute;
        Yaf_Registry::set('routeList', $routeList);
    }

    public static function resource($route, $handle, $middleware=null)
    {
        $routeList = Yaf_Registry::get('routeList');
        $routeList[] = [
            'method' => ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'HEAD'],
            'route' => $route . '/{action_id}[/{edit}]',
            'handle' => [$handle . '@_____resource', $middleware]
        ];
        $routeList[] = ['method' => ['GET', 'POST'], 'route' => $route . '[/]', 'handle' => [$handle . '@_____resource', $middleware]];
        Yaf_Registry::set('routeList', $routeList);
    }
}