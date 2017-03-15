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
     * Request constructor.
     */
    public function __construct()
    {
        $this->cookies = new Cookie;
        (object) $this->response = new Response;
        $this->uploadedFiles = new UploadedFiles;
    }
}