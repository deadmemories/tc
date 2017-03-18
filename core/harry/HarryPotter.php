<?php

namespace core\harry;

class HarryPotter
{
    public $data;

    /**
     * HarryPotter constructor.
     */
    public function __construct()
    {
        $this->data = collect([$this->data]);
    }

    /**
     * @param $name
     * @param $data
     */
    public function __set($name, $data)
    {
        $this->data[$name] = $data;
    }
}