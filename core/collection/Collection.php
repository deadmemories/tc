<?php

namespace core\collection;

use core\exceptions\CollectionException\CollectionException;
use core\interfaces\collection\CollectionInterface;

class Collection implements CollectionInterface
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * Collection constructor.
     *
     * @param array $items
     *
     * @throws CollectionException
     */
    public function __construct(array $items = [])
    {
        if (! is_array($items)) {
            throw new CollectionException('Arguments must be array');
        }

        $this->replace($items);
    }

    /**
     * Set collection item
     *
     * @param string $key
     * @param        $value
     */
    public function set(string $key, $value): void
    {
        $this->items[$key] = $value;
    }

    /**
     * Get collection item
     *
     * @param string $key
     * @param null   $default
     *
     * @return mixed|null
     */
    public function get(string $key, $default = null)
    {
        return $this->has($key)
            ? $this->items[$key]
            : $default;
    }

    /**
     * Add item to collection, replacing existing items with the same data key
     *
     * @param array $items
     */
    public function replace(array $items): void
    {
        foreach ($items as $k => $v) {
            $this->set($k, $v);
        }
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->items);
    }

    /**
     * @param string $key
     */
    public function remove(string $key): void
    {
        unset($this->items[$key]);
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * @param array|string $keys
     *
     * @return $this
     * @throws CollectionException
     */
    public function except($keys)
    {
        if (is_string($keys)) {
            $this->exceptOne($keys);
        } elseif (is_array($keys)) {
            $this->exceptMany($keys);
        } else {
            throw new CollectionException('argument for except must be array or string');
        }

        return $this;
    }

    /**
     * @param string $key
     */
    private function exceptOne(string $key)
    {
        if ($this->has($key)) {
            $this->remove($key);
        }
    }

    /**
     * @param $keys
     */
    private function exceptMany($keys)
    {
        foreach ($keys as $k) {
            if ($this->has($k)) {
                $this->remove($k);
            }
        }
    }

    /**
     * @param array $keys
     *
     * @return Collection
     */
    public function only(array $keys)
    {
        $array = [];

        foreach ($keys as $k) {
            if ($this->has($k)) {
                $array[$k] = $this->items[$k];
            }
        }

        return new self($array);
    }

    /**
     *
     */
    public function clear(): void
    {
        $this->items = [];
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->items);
    }
}