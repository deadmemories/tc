<?php

namespace core\validate;

class Validate
{
    /**
     * @var array
     */
    public $errors = [];

    /**
     * @var string
     */
    private $errorsFiles = '';

    /**
     * @param       $request
     * @param array $rules
     */
    public function rules($request, array $rules)
    {
        // because $request it's a collection object, and there all data in #data (property in Collection class)
        $request = $request->all();
        $this->errors = collect();
        $this->errorsFiles = config()->get('app.validate_errors');

        foreach ($rules as $key => $value) {
            // если найден такой name с реквеста
            if (array_key_exists($key, $request)) {
                // если указано одно правило
                $allRules = explode('|', $value);
                $moreParams = null;
                // данные с name
                $data = $request[$key];
                // обрезаем по ; чтобы дать методу доп.параметры
                foreach ($allRules as $k) {
                    $moreParams = stristr($k, ':') ? explode(':', $k) : $k;
                    $this->callMethod($data, $moreParams, $key);
                }
            }
        }
    }

    /**
     * @param        $data
     * @param        $params
     * @param string $key
     */
    private function callMethod($data, $params, string $key)
    {
        if (1 == count($params)) {
            call_user_func_array(
                [$this, $params], [$data, $key]
            );
        } else {
            call_user_func_array(
                [$this, $params[0]], [$data, $params[1], $key]
            );
        }
    }

    /**
     * @param        $data
     * @param string $length
     * @param string $input
     */
    public function min($data, string $length, string $input): void
    {
        if (strlen($data) < $length) {
            $this->errors->set(
                $input, ['length' => $length, 'error' => config()->get($this->errorsFiles.'.min')]
            );
        }
    }

    /**
     * @param        $data
     * @param string $length
     * @param string $input
     */
    public function max($data, string $length, string $input): void
    {
        if (strlen($data) > $length) {
            $this->errors->set(
                $input, ['length' => $length, 'error' => config()->get($this->errorsFiles.'.max')]
            );
        }
    }

    /**
     * @param        $data
     * @param string $input
     *
     * @return bool
     */
    public function required($data, string $input)
    {
        if (0 == strlen($data)) {
            $this->errors->set(
                $input, ['error' => config()->get($this->errorsFiles.'.required')]
            );

            return false;
        }
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        $new = [];
        foreach ($this->errors->all() as $k => $v) {
            $a = [];
            $a[1] = '/:attribute/';
            $a[2] = '/:length/';

            $b = [];
            $b[1] = $k;
            $b[2] = $v['length'];

            // заменяем :name на значения
            $data = preg_replace(
                $a, $b, $v
            );

            $new[$k] = collect([[$data['error']]]);
        }

        return collect([$new])->all();
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        if (0 == $this->errors->count()) {
            return true;
        } else {
            return false;
        }
    }
}