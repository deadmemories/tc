<?php

namespace core\collection;

use core\interfaces\collection\CollecitonInterface;

class Collection implements CollecitonInterface
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * Collection constructor.
     *
     * @param array $items
     */
    public function __construct(array $items = [])
    {
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
        $this->data[$key] = $value;
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
        return $this->has($key) ? $this->data[$key] : $default;
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
        return array_key_exists($key, $this->data);
    }

    /**
     * @param string $key
     */
    public function remove(string $key): void
    {
        unset($this->data[$key]);
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     *
     */
    public function clear(): void
    {
        $this->data = [];
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->data);
    }
}