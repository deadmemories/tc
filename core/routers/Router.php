<?php

namespace core\routers;

use core\exceptions\RouterException\RouterException;
use core\traits\Router\RouteHelper;

class Router extends BaseRoute
{
    use RouteHelper;

    /**
     * @var string
     * Текущая ссылка
     */
    public $currentUrl = '';

    /**
     * @var string
     *
     * текущий метод
     */
    public $requestMethod = '';

    /**
     * @var null
     *
     * Текущий роутер (current router)
     */
    protected $currentRouter = null;

    /**
     * @param $func
     * @param $args
     */
    public static function __callStatic($func, $args) { }

    public function __call($func, $args)
    {
        throw new RouterException('Incorrect name for method');
    }

    public function getRequestMethod()
    {
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];

        return $this->requestMethod;
    }

    /**
     * @return null|string
     */
    public function getCurrentUrl()
    {
        $this->currentUrl = $this->returnCurrentUrl();

        return !empty($this->currentUrl) ? $this->currentUrl : null;
    }
}