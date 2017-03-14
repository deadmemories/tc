<?php

// Главный конфиг который содержит в себе большую часть настроек приложения
return [
    // И такое бывает
    'name'     => 'Tc framework',

    // Ссылка на ваш сайт, используется для ....
    'url'      => 'localhost',

    // Классы которые будут загружены при запуске приложения
    'required' => [
        \core\request\Request::class,
        \core\cookie\Cookie::class,
    ],

    // Алиасы для классов
    'aliases'  => [
        'Route' => \core\routers\Router::class,
    ]
];