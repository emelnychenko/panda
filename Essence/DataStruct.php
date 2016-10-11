<?php

namespace Panda\Essence;

use Panda\Secure\Guard;

class DataStruct
{
    /**
     *  @const PHP
     */
    const PHP           = 'php';

    /**
     *  @const JSON
     */
    const JSON          = 'json';


    public static function encode($data, $serial = 'json', $essence = 'hash', $crypt = true)
    {
        $data === 'hash' ? (
            is_array($data) ? $data : ["eq" => $data]
        ) : (
            is_array($data) ? current($data) : $data 
        );

        if ($serial === static::JSON) {
            $data = json_encode($data);
        }

        if ($serial === static::PHP) {
             $data = serialize($data);
        }

        if (isset($data) === false) {
            return null;
        }

        return $crypt === true ? Guard::encrypt($data) : $data;
    }

    public static function decode($data, $serial = 'json', $essence = 'hash', $crypt = true)
    {
        $data = $crypt === true ? Guard::decrypt($data) : $data;

        if ($serial === static::JSON) {
            $data = @json_decode($data, true);
        }

        if ($serial === static::PHP) {
            $data = @unserialize($data);
        }

        if (isset($data) === false) {
            return $essence === 'hash' ? [] : null;
        }

        return $essence === 'hash' ? (
            is_array($data) ? $data : ["eq" => $data]
        ) : (
            is_array($data) ? current($data) : $data 
        );
    }
}
