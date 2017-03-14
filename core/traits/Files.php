<?php

namespace core\traits;

trait Files
{
    public function writeToFile($fileName, $data, $mode = 'a+')
    {
        if ('a+' != $mode) {
            throw new \Exception('This function have 2 arguments only');
        }

        if ( ! file_exists($fileName)) {
            if ( ! is_writable(dirname($fileName))) {
                throw new RuntimeException($fileName.' not writable');
            }
        } else {
            if ( ! is_writable($fileName)) {
                throw new RuntimeException($fileName.' not writable');
            }
        }
        $handler = fopen($fileName, $mode);
        fwrite($handler, (string)$data);
        fclose($handler);
    }
}