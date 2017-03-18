<?php

// Главный конфиг который содержит в себе большую часть настроек приложения
return [
    // Для подключения к базе данных
    'database' => [
        'host' => '127.0.0.1',
        'db' => 'tc',
        'user' => 'mysql',
        'password' => 'mysql',
        'charset' => 'utf8'
    ],

    // И такое бывает
    'name'     => 'Tc framework',

    // Ссылка на ваш сайт, используется для ....
    'url'      => 'localhost',

    // Тип файла по умолчанию для загрузки шаблона
    'types_file' => 'html',

    // Файл для загрузки ошибок при валидации
    'validate_errors' => 'ru-validate',

    // Классы которые будут загружены при запуске приложения
    'required' => [
        \core\collection\Collection::class,
        \core\response\Response::class,
        \core\cookie\Cookie::class,
        \core\views\View::class,
        \core\validate\Validate::class,
    ],

    // Алиасы для классов
    'aliases'  => [
        'Route' => \core\routers\Router::class,
        'Request' => \core\request\Request::class,
    ]
];