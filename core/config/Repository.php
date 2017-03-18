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
     * @throws \Exception
     */
    public function get(string $key)
    {
        $path = explode('.', $key);
        $file = $path[0];

        unset($path[0]);

        $this->load($file);

        if (2 == count($path) && is_array($this->file[$path[1]])) {
            return $this->file[$path[1]][$path[2]];
        } elseif (2 > count($path)) {
            return $this->file[implode('.', $path)];
        } else {
            throw new \Exception('Not more 2 length pls..');
        }
    }
}