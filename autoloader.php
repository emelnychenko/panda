<?php
/**
 *  Panda PHP Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

define('PANDA_FOUNDATION', __DIR__ . '/Foundation');
define('PANDA_FRAMEWORK',  __DIR__ . '/Framework');

spl_autoload_register(function($class) {
    foreach (array(
        'Panda\\Foundation\\'  => PANDA_FOUNDATION,
        'Panda\\'              => PANDA_FRAMEWORK,
    ) as $namespace => $trace) {
        if (strpos($class, $namespace) !== false) {
            $token = str_replace(
                array($namespace, '\\'), array(null, '/'), $class
            );

            file_exists(
                $included = sprintf('%s/%s.php', $trace, $token)
            ) ? include($included) : null;
        }
    }
});

