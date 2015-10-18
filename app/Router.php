<?php

namespace RomanNumerals;

use \FastRoute\RouteCollector as RouteCollector;
use \FastRoute\Dispatcher as RouteDispatcher;

class Router implements IRouter
{
    private $config = [];

    /**
     * @param $config Routes configuration
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Dispatches request to a controller
     *
     * @param IContainer $container
     * @return mixed
     */
    public function dispatch(IContainer $container)
    {
        $dispatcher = \FastRoute\simpleDispatcher(function (RouteCollector $r) {
            foreach ($this->config as $config) {
                $r->addRoute($config[0], $config[1], $config[2]);
            }
        });

        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri        = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
        switch ($routeInfo[0]) {
            case RouteDispatcher::FOUND:
                $handler    = $routeInfo[1];
                $vars       = $routeInfo[2];
                $controller = $handler;
                $method     = 'index';
                if (strpos($handler, '@') !== false) {
                    list($controller, $method) = explode('@', $handler);
                }
                $controller = $container->make($controller);
                $controller->$method(...array_values($vars));
                break;
            case RouteDispatcher::NOT_FOUND:
                $controller = $container->make('RomanNumerals\Controller');
                $controller->response(['error' => 'Command not found'], 404);
                break;
            case RouteDispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                $controller     = $container->make('RomanNumerals\Controller');
                $controller->response(['error' => 'Method is not allowed, allowed methods are: '.implode(', ', $allowedMethods)], 405);
                break;
        }
    }
}
