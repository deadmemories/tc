<?php

namespace core\request;

use core\traits\UploadedHelper\UploadedHelper;

class UploadedFiles
{
    /**
     * @return mixed
     */
    public function getFiles()
    {
        $files = [];
        $name = array_keys($_FILES)[0];
        $file_post = $_FILES[$name];

        $file_count = count($file_post['name']);
        $file_keys = array_keys($file_post);

        if (1 == count($_FILES['image']['name'])) {
            return $this->getFile($file_post, $name);
        } else {
            return $this->getAllFiles($file_keys, $file_count, $file_post, $name);
        }
    }

    /**
     * @param $file_keys
     * @param $file_count
     * @param $file_post
     * @param $name
     *
     * @return mixed
     */
    private function getAllFiles($file_keys, $file_count, $file_post, $name)
    {
        $files = [];

        for ($i = 0; $i < $file_count; $i++) {
            foreach ($file_keys as $key) {
                $files[$i][$key] = $file_post[$key][$i];
            }
        }

        $this->$name = collect([$files])->all();

        return $this->$name;
    }

    /**
     * @param $data
     * @param $name
     *
     * @return mixed
     */
    private function getFile($data, $name)
    {
        $this->$name = collect([$data])->all();

        return $this->$name;
    }
}