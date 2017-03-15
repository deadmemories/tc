<?php

namespace core\interfaces\collection;

interface CollectionInterface
{
    public function set(string $key, $value);

    public function get(string $key, $default = null);

    public function replace(array $items);

    public function has(string $key);

    public function remove(string $key);
}