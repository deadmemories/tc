<?php

namespace core\request;

class Request
{
    /**
     * @var string
     *
     * the request method
     */
    protected $method = '';

    /**
     * @var array
     *
     * The request cookies
     */
    protected $cookies;

    /**
     * @var
     */
    protected $uploadFiles;

    /**
     * Request constructor.
     */
    public function __construct()
    {

    }
}