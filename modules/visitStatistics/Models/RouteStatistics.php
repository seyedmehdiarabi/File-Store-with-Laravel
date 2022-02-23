<?php

namespace Modules\visitStatistics\Models;

class RouteStatistics
{
    protected $route;

    public function __construct()
    {
        $request = request();
        $this->route = $request->route();
        $this->addStatistics();
    }

    public function checkMethod(): bool
    {
        $result = false;
        $methods = $this->route->methods();
        foreach ($methods as $method) {
            if ($method == 'GET') {
                $result = true;
            }
        }
        return $result;
    }

    public function checkMiddleware(): bool
    {
        $result = true;
        $middlewares = $this->route->middleware();
        foreach ($middlewares as $middleware) {
            if ($middleware == 'auth') {
                $result = false;
            }
        }
        return $result;
    }

    public function addStatistics()
    {
        if ($this->checkMethod() /*&& $this->checkMiddleware()*/) {
            $statistics = new Statistics();
            $statistics->addData();
        }
    }
}
