<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Form\Angular;

use Panda\Form\Html\Input as HtmlInput;

/**
 *  Panda Form
 *
 *  @subpackage Framework
 */
class Input extends HtmlInput
{
    /**
     *  @var string
     */
    protected $form;

    /**
     *  @param  string $form
     * 
     *  @return \Panda\Form\Angular\Input
     */
    public function form($form = null)
    {
        $this->form = $form;

        return $this;
    }

    /**
     *  HTML rendered method.
     *
     *  @return string
     */
    public function __toString()
    {
        $attr = array_replace($this->attr, [
            'name' => $this->name, 'ng-model' => sprintf('%s.data.%s', $this->form, $this->name)
        ]);

        if ($this->type === static::TEXTAREA) {
            $attr['ng-init'] = sprintf('%s = \'%s\'', $attr['ng-model'], htmlspecialchars($this->equal));
            return sprintf('<textarea %s></textarea>', $this->attributable($attr));
        }

        $attr['type'] = $this->type;

        if (in_array($this->type, array(static::RADIO, static::CHECKBOX), true)) {
            if (array_key_exists('value', $this->attr) && $this->attr['value'] == $this->equal) {
                array_push($attr, 'checked');
                $attr['ng-checked'] = 'true';
            }

            return sprintf('<input %s />', $this->attributable($attr));
        }

        // $attr['value'] = htmlspecialchars($this->equal);
        $attr['ng-value'] = sprintf('\'%s\'', htmlspecialchars($this->equal));

        return sprintf('<input %s />', $this->attributable($attr));
    }
}
