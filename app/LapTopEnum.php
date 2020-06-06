<?php

namespace App;

class LapTopEnum
{
    const  MACBOOKAIR = 1;
    const MACBOOK = 2;
    const MACBOOKPRO = 3;

    public static function __callStatic($method, $args)
    {
        $instance = new static();

        return $instance->{$method}();
    }

    public function __call($method, $args)
    {
        return self::$method;
    }
}
