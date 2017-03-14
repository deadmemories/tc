<?php

namespace core\interfaces\cookie;

interface CookieInterface
{
    public function set($key, $value, $minutes = 0, $path = null, $domain = null, $secure = false, $httponly = true);

    public function has($key): bool;

    public function remove($key);
}