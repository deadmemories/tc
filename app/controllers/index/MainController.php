<?php

namespace app\controllers\index;

class MainController
{
    public function methodName()
    {
        $login = 'qqq';

        view('index', compact('login'));
    }

    public function getPost()
    {
        $images = [];
        dd(request()->uploadedFiles);
//        foreach ($request->uploadedFiles->image as $k) {
//            $images[] = collect([$k])->except(['error', 'tmp_name'])->all();
//        }

//        dd($images);
    }
}