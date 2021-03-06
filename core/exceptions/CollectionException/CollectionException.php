<?php

namespace core\exceptions\CollectionException;

use core\traits\Files;

/**
 * Исключения для работы с классом Collection (коллекция)
 *
 */
class CollectionException extends \Exception
{
    use Files;

    public function __construct($message)
    {
        \Exception::__construct($message);

        $this->writeToFile(
            '../resources/logs/error.txt',
            "\n"."Error : ".$message."\n".'Error in '.$this->getFile().' on line '.$this->getLine().' with '."\n"
            .$this->getTraceAsString()
        );
    }
}