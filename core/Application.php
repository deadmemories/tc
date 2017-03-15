<?php

namespace core;

use core\container\ServiceContainer;

class Application
{
    /**
     *
     */
    public function run(): void
    {
        ini_set('include_path', '/var/www/tc-framework/');
        // Загружаем классы в приложение из app ( load classes in app from app.required)
        app()->onlyLoadClass(config()->get('app.required'));

        // загружаем алиасы ( loading aliases from app.aliases)
        $this->loadAliases();

        // запускаем роуты ( init routers)
        require '../app/routers/main.php';
        (new \Route)->initRouters();
    }

    /**
     * Загрузка алиассов
     */
    private function loadAliases(): void
    {
        foreach (config()->get('app.aliases') as $k => $v) {
            app()->set($k, $v)->createAlias($k, $k);
        }
    }
}