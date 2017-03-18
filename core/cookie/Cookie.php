<?php

namespace core\cookie;

use Carbon\Carbon;
use core\interfaces\cookie\CookieInterface;

class Cookie implements CookieInterface
{
    /**
     * @var object (Collection)
     *
     * Все куки
     */
    protected $data;

    /**
     * Cookie constructor.
     */
    public function __construct()
    {
        $this->data = collect([$_COOKIE]);

        return $this->data;
    }

    /**
     * @param      $key
     * @param      $value
     * @param int  $minutes
     * @param null $path
     * @param null $domain
     * @param bool $secure
     * @param bool $httponly
     *
     * @return $this
     */
    public function set($key, $value, $minutes = 0, $path = null, $domain = null, $secure = false, $httponly = true)
    {
        $value = Hash::encrypt($value);

        $time = ($minutes == 0)
            ? 0
            : Carbon::now()->getTimestamp() + ($minutes * 60);

        setcookie($key, $value, $time, $path, $domain, $secure, $httponly);

        return $this;
    }

    /**
     * @param $key
     *
     * @return string
     */
    public function get($key)
    {
        return $this->has($key)
            ? 'Nothing'
            : Hash::decrypt($_COOKIE[$key]);
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function has($key): bool
    {
        return ! empty($_COOKIE[$key])
            ? true
            : false;
    }

    /**
     * @param $key
     */
    public function remove($key)
    {
        $this->set($key, "0", time() - 1, "/");
    }

    /**
     * @return mixed|object
     * Возвращает $data
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function __debugInfo()
    {
        return [
            $this->data
        ];
    }
}