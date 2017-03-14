<?php

namespace core\cookie;

const PASSWORD = 'qow182138ek1239qiweoqwkle.qwek123';
const CIPHER_METHOD = 'AES-256-CBC';

class Hash
{
    /**
     * @param $key
     *
     * @return mixed
     */
    public static function encrypt($key)
    {
        $iv_length = openssl_cipher_iv_length(CIPHER_METHOD);
        $iv = openssl_random_pseudo_bytes($iv_length);
        $str = $iv.$key;
        $val = openssl_encrypt($str, CIPHER_METHOD, PASSWORD, 0, $iv);
        return str_replace(array('+', '/', '='), array('_', '-', '.'), $val);
    }

    /**
     * @param $key
     *
     * @return string
     */
    public static function decrypt($key)
    {
        $val = str_replace(array('_','-', '.'), array('+', '/', '='), $key);
        $data = base64_decode($val);
        $iv_length = openssl_cipher_iv_length(CIPHER_METHOD);
        $body_data = substr($data, $iv_length);
        $iv = substr($data, 0, $iv_length);
        $base64_body_data = base64_encode($body_data);
        return openssl_decrypt($base64_body_data, CIPHER_METHOD, PASSWORD, 0, $iv);
    }
}