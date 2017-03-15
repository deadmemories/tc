<?php

// Главный конфиг который содержит в себе большую часть настроек приложения
return [
    // И такое бывает
    'name'     => 'Tc framework',

    // Ссылка на ваш сайт, используется для ....
    'url'      => 'localhost',

    // Тип файла по умолчанию для загрузки шаблона
    'types_file' => 'php',

    // Классы которые будут загружены при запуске приложения
    'required' => [
        \core\collection\Collection::class,
        \core\response\Response::class,
        \core\cookie\Cookie::class,
        \core\views\View::class,
    ],

    // Алиасы для классов
    'aliases'  => [
        'Route' => \core\routers\Router::class,
        'Request' => \core\request\Request::class,
    ]
];