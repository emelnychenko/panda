<?php

// Procedures call

define("is",   "is");
define("with", "with");

if (
    !function_exists('panda_iterator')
) {
    function panda_iterator($key, $equal = null, $callback)
    {
        if (
            is_array($key) && $equal === null
        ) {
            foreach ($key as $_key => $_equal) {
                call_user_func($callback, $_key, $_equal);
            }
        } else {
            call_user_func($callback, $key, $equal);
        }
    }
}

if (
    !function_exists('__')
) {
    function __($word)
    {
        return $word;
    }
}
