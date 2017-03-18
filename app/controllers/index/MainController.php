<?php

namespace app\controllers\index;

use app\models\User;
use core\validate\Validate;

class MainController
{
    public function methodName()
    {
        $user = new User;
        dd($user->delete(58));
    }
    public function getPost()
    {
        $request = new \Request;

        $validate = new Validate;

        $validate->rules($request->getAll(), [
                'login'    => 'required|min:5|max:15',
                'password' => 'required|min:2|max:10',
            ]
        );

        if ($validate->isValid()) {
            echo 'success';
        } else {
            dd($validate->getErrors());
        }
    }
}