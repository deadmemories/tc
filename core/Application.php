<?php

namespace core;

class Application
{
    /**
     *
     */
    public function run(): void
    {
        // Загружаем классы в приложение из app ( load classes in app from app.required)
        app()->onlyLoadClass(config()->get('app.required'));

        // загружаем алиасы ( loading aliases from app.aliases)
        $this->loadAliases();

        // запускаем роуты ( init routers)
        require '../app/routers/main.php';
        (new \Route)->startRoute();
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