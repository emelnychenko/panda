<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 *  @since   v1.2.0
 */

namespace Panda\Hook;

/**
 *  Hook Event
 *
 *  @subpackage Hook
 */
class Dispatcher
{
    /**
     *  @var array
     */
    protected $chain = array();

    public function add($event, $callback = null, $priority = 500) 
    {
        if (
            is_array($event) && is_null($callback)
        ) {
            foreach ($event as $_event => $_callback) {
                if (
                    is_string($_event) && is_callable($_callback)
                ) {
                    $this->chain[
                        $_event
                    ][
                        is_numeric($priority) ? $priority : $this->priority
                    ][] = $_callback;
                }
            }
        } elseif (
            is_string($event) && is_callable($callback)
        ) {
            $this->chain[
                $event
            ][
                is_numeric($priority) ? $priority : $this->priority
            ][] = $callback;
        }
    }

    /**
     *  @return \Blink\Service\HookDispatcher
     */
    public function run($event, $payload = null)
    {
        if (
            empty($this->chain[$event])
        ) {
            return;
        }

        $payload = new Event($event, $payload);

        ksort(
            $this->chain[$event]
        );

        foreach ($this->chain[$event] as $prioriy => $events) {
            foreach ($events as $callable) {
                call_user_func($callable, $payload->payload(), $payload);

                if ($payload->prevented()) break;
            }
        }
    }

    private function prioritize($priority = 500)
    {
        return is_numeric($priority) ? $priority : $this->priority;
    }
}

