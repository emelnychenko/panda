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
class Event
{
    /**
     *  @var string
     */
    protected $name;

    /**
     *  @var string
     */
    protected $payload;

    /**
     *  @var bool
     */
    protected $prevent = false;

    public function __construct($name, $payload = null)
    {
        $this->name     = $name;
        $this->payload  = $payload;
    }

    public function payload()
    {
        return $this->payload;
    }

    /**
     *  @return \Panda\Hook\Event
     */
    public function prevent()
    {
        $this->prevent = true;

        return $this;
    }

    /**
     *  @return bool
     */
    public function prevented()
    {
        return $this->prevent;
    }
}