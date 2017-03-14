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
     * @param string $filepath
     *
     * @return bool
     * @throws \Exception
     */
    public function load(string $filepath)
    {
        if (file_exists('../config/'.$filepath.'.php')) {
            $this->file = include('../config/'.$filepath.'.php');
        } else {
            throw new ConfigException($filepath);
        }
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key)
    {
        $path = explode('.', $key);
        $file = $path[0];

        unset($path[0]);

        $key = implode('.', $path);

        $this->load($file);

        return $this->file[$key];
    }
}