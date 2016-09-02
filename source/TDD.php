<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda;

/**
 *  Panda Form
 *
 *  @subpackage Framework
 */
class TDD
{
    public static function input($inputs, $rules = null) 
    {
        $valid = true;

        if (
            is_array($inputs)    && $rules === null
        ) { 
            foreach ($inputs as $input => $rules) {
                $rules = is_array($rules) ? $rules : [$rules];

                foreach ($rules as $rule) {
                    if (
                        call_user_func('is_' . $rule, $input) === false
                    ) {
                        $valid = false;
                    }
                }
            }
        } elseif (
            is_scalar($inputs)   && (
                is_array($rules) || is_scalar($rules)
            )
        ) {
            $rules = is_array($rules) ? $rules : [$rules];

            foreach ($rules as $rule) {
                if (
                    call_user_func('is_' . $rule, $inputs) === false
                ) {
                    $valid = false;
                }
            }
        }

        return $valid;
    }
}
