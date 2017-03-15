<?php

namespace core\traits\Router;

trait ParseUrl
{
    /**
     * @param string $key
     *
     * @return array
     */
    public static function parse(string $key): array
    {
        $result = explode('/', $key);

        if (empty($result[0])) {
            unset($result[0]);
        }

        return $result;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public static function replaceUrl(string $key)
    {
        return preg_replace(
            array_keys(self::$patterns), array_values(self::$patterns), $key
        );
    }
}