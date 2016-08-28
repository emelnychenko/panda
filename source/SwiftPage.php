<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */
namespace Panda;

use Panda\Foundation\EssenceWriteableAbstract;
use Panda\Foundation\TechnicalProviderExpansion;

/**
 *  Panda Swift Page
 *
 *  @subpackage Framework
 */
class SwiftPage extends EssenceWriteableAbstract implements SwiftPageInterface
{
    protected $swift        = null;
    protected $page         = null;
    protected $prevent      = null;
    protected $fill         = array();
    protected $render       = null;
    protected $layouted     = false;
    protected $layout       = null;

    public function __construct(SwiftInterface $swift, $page, $container = [], $prevent = false)
    {
        $this->swift        = $swift;
        $this->page         = $page;
        $this->prevent      = $prevent;
        $this->shared       = $container;
    }

    public function layout($page)
    {
        if (
            !$this->prevent
        ) {
            $this->layouted     = true;
            $this->layout       = $page;
        }

        return $this;
    }

    public function hold($key, $default = '')
    {
        if (
            array_key_exists($key, $this->fill)
        ) {
            return $this->fill[$key];
        }

        return $default;
    }

    public function fill($key, $value = null)
    {
        $this->tpe_pair_iterator($key, $value, function($key, $value) {
            $this->fill[$key] = $value;
        });

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
        if (
            isset($this->render)
        ) {
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

    use TechnicalProviderExpansion;
}