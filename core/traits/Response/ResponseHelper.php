<?php

namespace core\traits\Response;

trait ResponseHelper
{
    /**
     * @param int $code
     *
     * @return null
     */
    public function statusText(int $code)
    {
        return array_key_exists($code, $this->statusTexts) ? $this->statusTexts[$code] : null;
    }

    /**
     * @param $data
     * @param $code
     *
     * @return mixed
     */
    public function returnJson($data, $code)
    {
        $this->setContent('application/json');

        $this->setHeader('HTTP/ '.$code.$this->statusText($code));

        return json_encode($data);
    }
}