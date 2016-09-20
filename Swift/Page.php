<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 *  @since   v1.2.0
 */

namespace Panda\Swift;

use Panda\Essence\WriteableAbstract     as Essence;

/**
 *  Swift Page
 *
 *  @subpackage Swift
 */
class Page extends Essence implements PageInterface
{
    /**
     *  @var \Panda\Swift\ViewInterface
     */ 
    protected $swift        = null;

    /**
     *  @var string
     */ 
    protected $page         = null;

    /**
     *  @var bool
     */ 
    protected $prevent      = null;

    /**
     *  @var array
     */ 
    protected $fill         = array();

    /**
     *  @var string
     */ 
    protected $render       = null;

    /**
     *  @var bool
     */ 
    protected $layouted     = false;

    /**
     *  @var string
     */ 
    protected $layout       = null;

    public function __construct(ViewInterface $swift, $page, $container = [], $prevent = false)
    {
        $this->swift        = $swift;
        $this->page         = $page;
        $this->prevent      = $prevent;
        $this->shared       = $container;
    }

    public function layout($page)
    {
        if ($this->prevent === false) {
            $this->layouted     = true;
            $this->layout       = $page;
        }

        return $this;
    }

    public function hold($key, $default = '')
    {
        if (array_key_exists($key, $this->fill)) {
            return $this->fill[$key];
        }

        return $default;
    }

    public function fill($key, $value = null)
    {
        $keys = is_array($key) ? $key : [$key => $value];

        foreach ($keys as $key => $value) {
            $this->fill[$key] = $value;
        }

        return $this;
    }

    public function part($page)
    {
        return $this->extract($page);
    }

    public function inner()
    {
        if ($this->layouted) 
            return $this->content;
    }

    public function render()
    {
        if ($this->render !== null) {
            return $this->render;
        }

        $this->content = $this->extract($this->page);

        if ($this->prevent) {
            return $this->render = $this->content;
        }

        if ($this->layouted) {
            $this->prevent  = true;
            $this->content  = $this->extract($this->layout);  

            return $this->render = $this->content;
        }

        return $this->render = $this->content;
    }
  

    public function __toString()
    {
        return $this->render();
    }

    public function __call($method, $arguments)
    {
        $callable = $this->get($method);

        if (
            is_callable($callable)
        ) {
            return call_user_func_array($callable, $arguments);
        }
    }

    protected function extract($page)
    {
        ob_start();
        include $this->swift->finder($page);
        return ob_get_clean();
    }
}
