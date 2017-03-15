<?php

Route::get('/', '\app\controllers\index\MainController@methodName');

Route::get('/url', '\app\controllers\index\MainController@methodName');

Route::post('/test', '\app\controllers\index\MainController@getPost');

Route::get(
    '/user/{integer}', '\app\controllers\index\MainController@methodName', [
    'middleware' => [
        'name',
        'name2',
    ],
]
);