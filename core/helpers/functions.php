<?php

function app()
{
    return \core\container\ServiceContainer::getInstance();
}

function config()
{
    return app()
        ->set('config', '\core\config\Repository')
        ->bildClass('config');
}

function route()
{
    return app()->bildClass('Route');
}

function cookie()
{
    return app()->bildClass('Cookie');
}