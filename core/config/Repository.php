<?php

namespace core\config;

use \core\exceptions\ConfigException\ConfigException;

class Repository
{
    /**
     * @var null
     */
    private $file = null;

    /**
     * @param string $path
     * @throws ConfigException
     */
    public function load(string $path): void
    {
        if (file_exists('../config/'.$path.'.php')) {
            $this->file = include('../config/'.$path.'.php');
        } else {
            throw new ConfigException($path);
        }
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        $path = explode('.', $key);
        $file = $path[0];

        array_shift($path);

        $this->load($file);

        if (2 == count($path) && is_array($this->file[$path[1]])) {
            return $this->withTwoKeys($path);
        } elseif (2 > count($path)) {
            return $this->withOneKey($path);
        }

        throw new \Exception('Not more 2 length pls..');
    }

    /**
     * @param $path
     * @return mixed
     */
    private function withTwoKeys($path)
    {
        return $this->file[$path[1]][$path[2]];
    }

    /**
     * @param $path
     * @return mixed
     */
    private function withOneKey($path)
    {
        return $this->file[implode('.', $path)];
    }
}