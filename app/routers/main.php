<?php

Route::get('/', function() {
    cookie()->set('login', 'DeadMoras');
    var_dump(cookie()->has('login'));
});

Route::get('/url', '\app\controllers\index\MainController@methodName');

Route::get('/user/{integer}', '\app\controllers\index\MainController@methodName', [
    'middleware' => [
        'name', 'name2'
    ]
]);