<?php

function app()
{
    return \core\container\ServiceContainer::getInstance();
}

function config()
{
    return app()->set('config', '\core\config\Repository')->bildClass('config');
}

function route()
{
    return app()->bildClass('Route');
}

function cookie()
{
    return app()->bildClass('Cookie');
}

function collect(array $data = [])
{
    return app()->bildClass('Collection', $data);
}

function response()
{
    return app()->bildClass('Response');
}

function view($path, $data = [], $type = '')
{
    return app()->bildClass('View')->showView($path, $data, $type);
}

function request()
{
    return app()->bildClass('Request');
}

function dd()
{
    $args = func_get_args();
    call_user_func_array('dump', $args);
    die();
}