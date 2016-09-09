<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 *  @since   v1.3.0
 */

namespace Panda\Console;

use Panda\Essence\Writeable         as Essence;
use Panda\Alloy\FactoryInterface    as Factory;

/**
 *  Readable Essence
 *
 *  @subpackage Essence
 */
class Bamboo implements Factory
{
    /**
     *  New instance.
     *
     *  @var mixed  $shared
     */
    public function __construct($shared = null)
    {
        if (isset($shared)) {
            $this->shared = is_array($shared) ? $shared : func_get_args();
        }
    }

    /**
     *  Factory instance.
     *
     *  @var mixed  $shared
     */
    public static function factory($shared = null)
    {
        return new static($shared);
    }

    /**
     *  Return whole shared result.
     *
     *  @return array
     */
    public function all()
    {
        return $this->shared;
    }

    public function run()
    {
        return 'Hello Panda Bamboo.' . PHP_EOL;
    }

    // private $routes;
    // public function add($event, $handler = null)
    // {
    //     bexp_uni_set($event, $handler, function($event, $handler) {
    //         if (
    //             is_object(
    //                 $handler
    //             ) && get_class($handler) === 'Closure'
    //         ) {
    //             $this->routes[] = [
    //                 'identity'  => $event,
    //                 'handler'   => 'closure',
    //                 'closure'   => $handler,
    //             ]; 
    //         } else {
    //             list($constroller, $action) = explode('#', $handler);
    //             $this->routes[] = [
    //                 'identity'      => $event,
    //                 'handler'       => 'event',
    //                 'controller'    => $constroller,
    //                 'action'        => $action,
    //             ]; 
    //         }
    //     });
    // }
    // public function run()
    // {
    //     $argv   = $GLOBALS['argv'];
    //     $argc   = $GLOBALS['argc'];
    //     if (
    //         $argc >= 2 && !empty($this->routes)
    //     ) {
    //         foreach ($this->routes as $container) {
    //             if (
    //                 $container['identity'] === $argv[1]
    //             ) {
    //                 $matches = array_slice($argv, 2);
    //                 if (
    //                     $container['handler'] === 'closure'
    //                 ) {
    //                     return factory(
    //                             'Blink\Http\Router\Reversed'
    //                         )->call(
    //                             $container['closure'], 
    //                             null, 
    //                             $matches
    //                         );
    //                 } 
    //                 return factory(
    //                         'Blink\Http\Router\Reversed'
    //                     )->call(
    //                         $container['controller'], 
    //                         $container['action'], 
    //                         $matches
    //                     );
    //             }
    //         }
    //     }
    //     return text('error#404') . "\n";
    // }
}
