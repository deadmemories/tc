<?php

namespace core\request;

use core\cookie\Cookie;
use core\response\Response;

class Request
{
    /**
     * @var array
     *
     * The request cookies
     */
    public $cookies;

    /**
     * @var
     *
     * The response
     */
    public $response;

    /**
     * @var
     */
    public $uploadedFiles;

    /**
     * @var array
     *
     * All data from request
     */
    public $data = [];

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->cookies = new Cookie;
        (object) $this->response = new Response;

        $this->uploadedFiles = empty($_FILES)
            ? null
            : (new UploadedFiles)->getFiles();

        $this->data = $this->getAll()->all();
    }

    /**
     * @param      $name
     * @param null $default
     *
     * @return array|mixed|string
     */
    public function input($name, $default = null)
    {
        $data = [];

        if (is_array($name)) {
            foreach ($name as $k) {
                $data[$k] = $this->cleanData($_POST[$k]);
            }
        } else {
            $data = empty($_POST[$name])
                ? $default
                : $this->cleanData($_POST[$name]);
        }

        return $data;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name)
    {
        return ! empty($this->input($name))
            ? true
            : false;
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        $data = $_POST;

        $q = [];
        foreach ($data as $k => $v) {
            $q[$k] = $this->cleanData($v);
        }

        return collect([$q]);
    }

    /**
     * @param $data
     *
     * @return string
     */
    private function cleanData($data)
    {
        return strip_tags(htmlspecialchars($data));
    }
}