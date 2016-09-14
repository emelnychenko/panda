<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Form\Html;

use Panda\Form\Data\Input as DataInput;

/**
 *  Panda Form
 *
 *  @subpackage Framework
 */
class Input extends DataInput
{
    /**
     *  @const HIDDEN
     */ 
    const HIDDEN            = 'hidden';

    /**
     *  @const TEXT
     */ 
    const TEXT              = 'text';

    /**
     *  @const EMAIL
     */ 
    const EMAIL             = 'email';

    /**
     *  @const PASSWORD
     */ 
    const PASSWORD          = 'password';

    /**
     *  @const RADIO
     */ 
    const RADIO             = 'radio';

    /**
     *  @const CHECKBOX
     */ 
    const CHECKBOX          = 'checkbox';

    /**
     *  @const TEXTAREA
     */ 
    const TEXTAREA          = 'textarea';

    /**
     *  @const EMAIL
     */ 
    const FILE              = 'file';

    /**
     *  @const EMAIL
     */ 
    const DATE              = 'date';

    /**
     *  @const EMAIL
     */ 
    const TIME              = 'time';

    /**
     *  @var string
     */ 
    protected $type         = 'text';

    /**
     *  @var string
     */ 
    protected $name         = null;

    /**
     *  @var array
     */ 
    protected $attr         = [];

    /**
     *  @var string $type
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $value
     */ 
    public function __construct($name = 'panda.input', $type = 'text', array $attr = [], $equal = null)
    {
        $this->name     = $name;
        $this->type     = $type; 
        $this->attr     = $attr;

        $this->set($equal);
    }

    /**
     *  @var string $type
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Base\Input
     */
    public static function factory($name = 'panda.input', $type = 'text', array $attr = [], $equal = null)
    {
        return new static($name, $type, $attr, $equal);
    }

    /**
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $equal
     *
     *  @return \Panda\Form\Base\Input
     */
    public static function hidden($name, array $attr = [], $equal = null)
    {
        return new static($name, static::HIDDEN, $attr, $equal);
    }

    /**
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $equal
     *
     *  @return \Panda\Form\Base\Input
     */
    public static function text($name, array $attr = [], $equal = null)
    {
        return new static($name, static::TEXT, $attr, $equal);
    }

    /**
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $equal
     *
     *  @return \Panda\Form\Base\Input
     */
    public static function time($name, array $attr = [], $equal = null)
    {
        return new static($name, static::TIME, $attr, $equal);
    }

    /**
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $equal
     *
     *  @return \Panda\Form\Base\Input
     */
    public static function date($name, array $attr = [], $equal = null)
    {
        return new static($name, static::DATE, $attr, $equal);
    }

    /**
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $equal
     *
     *  @return \Panda\Form\Base\Input
     */
    public static function email($name, array $attr = [], $equal = null)
    {
        return new static($name, static::EMAIL, $attr, $equal);
    }

    /**
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $equal
     *
     *  @return \Panda\Form\Base\Input
     */
    public static function password($name, array $attr = [], $equal = null)
    {
        return new static($name, static::PASSWORD, $attr, $equal);
    }

    /**
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $equal
     *
     *  @return \Panda\Form\Base\Input
     */
    public static function radio($name, array $attr = [], $equal = null)
    {
        return new static($name, static::RADIO, $attr, $equal);
    }

    /**
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $equal
     *
     *  @return \Panda\Form\Base\Input
     */
    public static function chechbox($name, array $attr = [], $equal = null)
    {
        return new static($name, static::CHECHBOX, $attr, $equal);
    }

    /**
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $equal
     *
     *  @return \Panda\Form\Base\Input
     */
    public static function textarea($name, array $attr = [], $equal = null)
    {
        return new static($name, static::TEXTAREA, $attr, $equal);
    }

    /**
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $equal
     *
     *  @return \Panda\Form\Base\Input
     */
    public static function file($name, array $attr = [], $equal = null)
    {
        return new static($name, static::FILE, $attr, $equal);
    }

    /**
     *  @var mixed  $attr
     *  @var mixed  $equal
     *
     *  @return \Panda\Form\Base\Input
     */
    public function attr($attr, $equal = null)
    {
        $attr = is_array($attr) ? $attr : (
            $equal === null ? [$attr => $attr] : [$attr => $equal]
        );

        $this->attr = array_replace($this->attr, $attr);

        return $this;
    }

    /**
     *  @param mixed $type
     *
     *  @return \Panda\Form\Base\Input
     */
    public function type($type = null)
    {
        if ($type === null) {
            return $this->type;
        }

        $this->type = $type;

        return $this;
    }

    /**
     *  @param mixed $name
     *
     *  @return \Panda\Form\Base\Input
     */
    public function name($name = null)
    {
        if ($name === null) {
            return $this->name;
        }

        $this->name = $name;

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

        if ($this->type === static::TEXTAREA) {
            return sprintf('<textarea %s>%s</textarea>', $this->attributable($attr), $this->equal);
        }

        $attr['type'] = $this->type;

        if (in_array($this->type, array(static::RADIO, static::CHECKBOX), true)) {
            if (array_key_exists('value', $this->attr) && $this->attr['value'] == $this->equal) {
                array_push($attr, 'checked');
            }

            return sprintf('<input %s />', $this->attributable($attr));
        }

        $attr['value'] = htmlspecialchars($this->equal);

        return sprintf('<input %s />', $this->attributable($attr));
    }

    /**
     *  @param  array
     * 
     *  @return [type]
     */
    protected function attributable(array $attr)
    {
        $shared = [];

        foreach ($attr as $key => $equal) {
            array_push(
                $shared, is_numeric($key) ? $equal : sprintf(
                    '%s="%s"', $key, htmlspecialchars($equal)
                )
            );
        }

        return implode(' ', $shared);
    }
}
