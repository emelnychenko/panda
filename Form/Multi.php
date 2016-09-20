<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Form;

/**
 *  Panda Form
 *
 *  @subpackage Framework
 */
class Multi extends Input implements \Iterator, MultiInterface
{
    /**
     *  @const TEXT
     */ 
    const SELECT                = 'select';

    /**
     *  @const EMAIL
     */ 
    const RADIO                 = 'multiradio';

    /**
     *  @const PASSWORD
     */ 
    const CHECKBOX              = 'multicheckbox';

    /**
     *  @var array
     */ 
    protected static $decide    = [
        'multiradio'     => 'radio', 
        'multicheckbox'  => 'checkbox',
    ];

    /**
     *  @var string
     */ 
    protected $type             = 'select';

    /**
     *  @var array
     */ 
    protected $options          = array();

    /**
     *  @var array
     */ 
    protected $iterated         = false;

    /**
     *  @var array
     */ 
    protected $iterator         = array();

    /**
     *  @var array
     */ 
    protected $iteration        = 0;

    /**
     *  @var array
     */ 
    protected $cached           = array();

    /**
     *  comment ...
     *
     *  @var string $type
     *  @var string $name
     *  @var string $options
     *  @var mixed  $attr
     *  @var mixed  $value
     */ 
    public function __construct($type, $name, $options = [], $attr = [], $value = null)
    {
        $this->type     = $type; 
        $this->name     = $name;
        $this->options  = $options;

        if (
            is_scalar($attr)
        ) {
            $this->set($attr);
        } else {
            $this->attr = $attr;
            $this->set($value);
        }
    }

    /**
     *  comment ...
     *
     *  @var string $type
     *  @var string $name
     *  @var string $options
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Multi
     */
    public static function factory($type, $name, $options = [], $attr = [], $value = null)
    {
        return new static($type, $name, $options, $attr, $value);
    }

    /**
     *  comment ...
     *
     *  @var string $name
     *  @var string $options
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Multi
     */
    public static function select($name, $options = [], $attr = [], $value = null)
    {
        return new static(static::SELECT, $name, $options, $attr, $value);
    }

    /**
     *  comment ...
     *
     *  @var string $name
     *  @var string $options
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Multi
     */
    public static function radio($name, $options = [], $attr = [], $value = null)
    {
        return new static(static::RADIO, $name, $options, $attr, $value);
    }

    /**
     *  comment ...
     *
     *  @var string $type
     *  @var string $name
     *  @var array  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Multi
     */
    public static function checkbox($name, $options = [], $attr = [], $value = null)
    {
        return new static(static::CHECKBOX, $name, $options, $attr, $value);
    }

    /**
     *  comment ...
     *
     *  @var mixed $value
     *  @var mixed $label
     *
     *  @return mixed
     */
    public function option($value, $label = null)
    {
        if (
            is_scalar($value) && $label === null
        ) {
            return array_key_exists($value, $this->options) ? $this->options[$value] : null;
        } elseif (
            is_scalar($value) && is_scalar($label)
        ) {
            $this->options[$value] = $label;
        } elseif (
            is_array($value) && $label === null
        ) {
            foreach ($value as $_value => $_label) {
                $this->options[$_value] = $_label;
            }
        }

        return $this;
    }

    /**
     *  comment ...
     *
     *  @return array
     */
    public function options()
    {
        return $this->options;
    }

    /**
     *  Iterator implementation
     *
     *  Using only with checkbox and radio
     *  
     */
    public function rewind() 
    {
        $this->iterated     = true;
        $this->iteration    = 0;
        $this->iterator     = array_keys($this->options);
        $this->cached       = array();

        foreach (array_replace($this->attr, [
            'name'  => $this->name, 'type'  => $this->decide()
        ]) as $key => $value) {
            array_push(
                $this->cached, sprintf('%s="%s"', $key, $value)
            );
        }
    }

    /**
     *  Iterator implementation
     *
     *  Using only with checkbox and radio
     *  
     */
    public function current() 
    {
        return $this->options[
            $this->iterator[
                $this->iteration
            ]
        ];
    }

    /**
     *  Iterator implementation
     *
     *  Using only with checkbox and radio
     *  
     */
    public function key() 
    {
        $attr   = $this->cached;
        $value  = $this->iterator[$this->iteration];

        array_push($attr, sprintf('value="%s"', htmlspecialchars(
                    stripslashes(
                        trim($value)
                    )
                )
            )
        );

        if (
            $value == $this->value
        ) {
            array_push($attr, 'checked="checked"');
        }

        return sprintf(
            '<input %s />', implode(' ', $attr)
        );
    }

    /**
     *  Iterator implementation
     *
     *  Using only with checkbox and radio
     *  
     */
    public function next() 
    {
        ++$this->iteration;
    }

    /**
     *  Iterator implementation
     *
     *  Using only with checkbox and radio
     *  
     */
    public function valid() 
    {
        if ($this->iterated === false) {
            return parent::valid();
        }

        $valid = isset(
            $this->iterator[$this->iteration]
        );

        if ($valid === false) {
            $this->iterated = false;
            $this->cached   = array();
        }

        return $valid;
    }

    /**
     *  Get association resolved
     *
     *  Using only with checkbox and radio
     *  
     */
    protected function decide()
    {
        return array_key_exists($this->type, static::$decide) ? 
            static::$decide[$this->type] : $this->type;
    }

    /**
     *  Render only Select element. For radio and checkbox need iterator $input => $label
     *
     *  @return string
     */
    public function __toString()
    {
        $attr = array();

        foreach (array_replace($this->attr, ['name'  => $this->name]) as $key => $value) {
            array_push(
                $attr, sprintf('%s="%s"', $key, $value)
            );
        }

        if (
            $this->type !== static::SELECT
        ) {
            return null;
        }

        $options = array();

        foreach ($this->options as $value => $title) {
            $attr_ = array(
                sprintf('value="%s"', htmlspecialchars(
                        stripslashes(
                            trim($value)
                        )
                    )
                )
            );

            if ($value == $this->value) {
                array_push($attr_, 'selected');
            }

            array_push(
                $options, sprintf('<option %s>%s</option>', implode(' ', $attr_), $title)
            );
        }

        return sprintf(
            '<select %s>%s</select>', implode(' ', $attr), implode('', $options)
        );
    }
}
