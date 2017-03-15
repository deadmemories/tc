<?php

namespace core\views;

use core\exceptions\ViewException\ViewException;

class View
{
    /**
     * @var string
     */
    private $dir = '../resources/views/';

    /**
     * @var array
     */
    private $fileTypes = [
        'html',
        'twig',
        'php',
        'xml',
    ];

    /**
     * @param        $path
     * @param array  $data
     * @param string $type
     */
    public function showView($path, $data = [], $type = '')
    {
        $path = $this->pathForFile($path, $type);

        return $this->loadView($path, $data);
    }

    /**
     * @param string $path
     * @param string $type
     *
     * @return null
     * @throws ViewException
     */
    private function pathForFile(string $path, string $type)
    {
        $pathToFile = null;
        $type = $type ?: config()->get('app.types_file');


        if (strpos($path, '.') !== false) {
            $pathToFile = str_replace('.', '/', $path).'.'.$type;
        } else {
            $pathToFile = $path.'.'.$type;
        }

        if ( ! file_exists($this->dir.$pathToFile)) {
            throw new ViewException('Incorrect path to file');
        }

        return $pathToFile;
    }

    /**
     * @param $path
     * @param $data
     */
    private function loadView($path, $data)
    {
        $loader = new \Twig_Loader_Filesystem($this->dir);

        $twig = new \Twig_Environment($loader);

        echo $twig->render($path, $data);
    }
}