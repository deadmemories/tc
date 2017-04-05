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
     * @param string $name
     * @param null $default
     * @return array|string
     */
    public function input(string $name, $default = null)
    {
        $data = [];

        if (is_array($name)) {
            $data = $this->getInputsForArray($name);
        } else {
            $data = $this->getInputsForString($name, $default);
        }

        return $data;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
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

    /**
     * @param string $name
     * @return array
     */
    private function getInputsForArray(string $name): array
    {
        $data = [];

        foreach ($name as $k) {
            $data[$k] = $this->cleanData($_POST[$k]);
        }

        return $data;
    }

    /**
     * @param string $name
     * @param $default
     * @return string
     */
    private function getInputsForString(string $name, $default): string
    {
        return empty($_POST[$name])
            ? $default
            : $this->cleanData($_POST[$name]);
    }
}