<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Form\Html;

/**
 *  Panda Form
 *
 *  @subpackage Framework
 */
class Multi extends Input
{
    /**
     *  @const TEXT
     */ 
    const SELECT            = 'select';

    /**
     *  @const EMAIL
     */ 
    const MULTIRADIO        = 'multiradio';

    /**
     *  @const PASSWORD
     */ 
    const MULTICHECKBOX     = 'multicheckbox';

    /**
     *  @var string
     */ 
    protected $type             = 'select';

    /**
     *  @var array
     */ 
    protected $option           = [];

    /**
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $equal
     *
     *  @return \Panda\Form\Base\Input
     */
    public static function select($name, array $attr = [], $equal = null)
    {
        return new static($name, static::SELECT, $attr, $equal);
    }

    /**
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $equal
     *
     *  @return \Panda\Form\Base\Input
     */
    public static function multiradio($name, array $attr = [], $equal = null)
    {
        return new static($name, static::MULTIRADIO, $attr, $equal);
    }

    /**
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $equal
     *
     *  @return \Panda\Form\Base\Input
     */
    public static function multicheckbox($name, array $attr = [], $equal = null)
    {
        return new static($name, static::MULTICHECKBOX, $attr, $equal);
    }

    public function option($option, $equal = null)
    {
        $options = is_array($option) ? $option : [$option => $equal];

        $this->option = array_replace($this->option, $options);

        return $this;
    }

    /**
     *  HTML rendered method.
     *
     *  @return string
     */
    public function __toString()
    {

        $attr = array_replace($this->attr, ['name' => $this->name]);

        if ($this->type !== static::SELECT) {
            return null;
        }

        $option = array();

        foreach ($this->option as $value => $title) {
            $oattr = array(
                sprintf('value="%s"', htmlspecialchars(
                        stripslashes(
                            trim($value)
                        )
                    )
                )
            );

            if ($value == $this->equal) {
                array_push($oattr, 'selected');
            }

            array_push(
                $option, sprintf('<option %s>%s</option>', $this->attributable($oattr), $title)
            );
        }

        return sprintf(
            '<select %s>%s</select>', $this->attributable($attr), implode('', $option)
        );
    }
}
