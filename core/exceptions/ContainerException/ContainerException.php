<?php

namespace core\exceptions\ContainerException;

use core\traits\Files;

class ContainerException extends \Exception
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