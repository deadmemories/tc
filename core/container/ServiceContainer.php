<?php

namespace core\container;

use core\exceptions\ContainerException\ContainerException;

class ServiceContainer
{
    /**
     * @var array
     *
     * все классы
     */
    protected $bindings = [];

    /**
     * @var array
     * все классы "одиночки"
     */
    protected $singletons = [];

    /**
     * @var object
     */
    private static $instance;

    /**
     * @return ServiceContainer
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * @param string $key
     * @param        $object
     * @param bool   $singleton
     *
     * @return $this|void
     */
    public function set(string $key, $object, $singleton = false)
    {
        if (true == $singleton) {
            return $this->singleton($key, $object);
        }

        $this->bindings[$key] = compact('object');

        return $this;
    }

    /**
     * @param $key
     * @param $object
     */
    public function singleton($key, $object)
    {
        $this->singletons[$key] = compact('object');
    }

    /**
     * @param string $key
     * @param string $alias
     *
     * @return mixed
     * @throws \Exception
     */
    public function createAlias(string $key, string $alias)
    {
        if ( ! array_key_exists($key, $this->bindings)) {
            throw new ContainerException('First you must be use set function');
        }

        if (array_key_exists($key, $this->singletons)) {
            return class_alias($this->singletons[$key]['object'], $alias);
        }

        if ( ! class_exists($this->bindings[$key]['object'])) {
            throw new ContainerException('Incorrect name of class');
        }

        return class_alias($this->bindings[$key]['object'], $alias);
    }

    /**
     * @param string $key
     *
     * @param null   $params
     *
     * @return mixed
     * @throws ContainerException
     */
    public function bildClass(string $key, $params = null)
    {
        $object = null;

        if (array_key_exists($key, $this->singletons)) {
            $object = $this->singletons[$key]['object'];
        } elseif (array_key_exists($key, $this->bindings)) {
            $object = $this->bindings[$key]['object'];
        } else {
            throw new ContainerException('Incorrect key...');
        }

        return $this->instance($object, $params);
    }

    /**
     * @param array $classes
     *
     * @return mixed
     */
    public function onlyLoadClass(array $classes)
    {
        foreach ($classes as $k) {
            $name = explode('\\', $k);
            $this->set(end($name), $k)->instance($k);
        }
    }

    /**
     * @param      $key
     * @param null $parameters
     *
     * @return mixed
     */
    private function instance($key, $parameters = null)
    {
        if ($key instanceof \Closure) {
            return call_user_func_array($key, $parameters);
        }

        if ( ! class_exists($key)) {
            return false;
        }

        if ('\\' != substr($key, 0, 1)) {
            mb_internal_encoding("UTF-8");
            $key = '\\'.$key;
        }

        if ( ! is_null($parameters)) {
            $reflection = new \ReflectionClass($key);

            return $reflection->newInstanceArgs($parameters);
        } else {
            return new $key;
        }
    }
}