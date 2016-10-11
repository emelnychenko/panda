<?php

use PHPUnit\Framework\TestCase;

use Panda\Form\Input;
use Panda\Form\Multi;

use Panda\Form;

class FormTest extends TestCase
{
    protected $input = [
        Input::HIDDEN, 
        Input::TEXT, 
        Input::EMAIL,  
        Input::PASSWORD,
        Input::RADIO,
        Input::CHECKBOX,
        Input::TEXTAREA,
        Input::FILE,
    ];

    protected $multi = [
        Multi::SELECT, 
        Multi::RADIO, 
        Multi::CHECKBOX, 
    ];

    public function testFormEmptyInstance()
    {
        $form = Form::factory();   

        $this->assertInstanceOf(Form::class,    $form);
        $this->assertInstanceOf(Form::class,    $form->validate());
        $this->assertInstanceOf(Form::class,    $form->sanitize());
        $this->assertInstanceOf(Form::class,    $form->attr('class', 'form'));
        $this->assertInternalType('string',     $form->attr('class'));
        $this->assertInternalType('array',      $form->attr());
        $this->assertInternalType('null',       $form->attr('class24'));
        $this->assertInternalType('string',     $form->open());
        $this->assertInternalType('string',     $form->close());
        $this->assertInternalType('array',      $form->all());
        $this->assertInternalType('array',      $form->error());
        $this->assertInternalType('bool',       $form->valid());
    }

    public function testFormMethodInput()
    {
        $form = Form::factory();   

        $name   = 'input'; 
        $value  = 'exist'; 
        $opt    = ['value' => 2]; 
        $attr   = ['class' => 'noclass'];

        foreach ($this->input as $method) {
            $input = $form->input($method, $name, $attr, $value);

                $this->assertEquals($input->name(), $name);
                $this->assertEquals($input->get(),  $value);
                $this->assertEquals($input->type(), $method);
            $this->assertInstanceOf(Input::class, $input);

            $input = $form->input($method, $name, $value);
            
                $this->assertEquals($input->name(), $name);
                $this->assertEquals($input->get(), $value);
                $this->assertEquals($input->type(), $method);
            $this->assertInstanceOf(Input::class, $input);
        } 

        foreach ($this->multi as $method) {
            $input = $form->input($method, $name, $opt, $attr, $value);

                $this->assertEquals($input->name(), $name);
                $this->assertEquals($input->get(),  $value);
                $this->assertEquals($input->type(), $method);
            $this->assertInstanceOf(Input::class, $input);

            $input = $form->input($method, $name, $opt, $value);
            
                $this->assertEquals($input->name(), $name);
                $this->assertEquals($input->get(), $value);
                $this->assertEquals($input->type(), $method);
            $this->assertInstanceOf(Input::class, $input);
        } 
    }

    public function testFormFactoryMethodsInput()
    {
        $form   = Form::factory(); 
        $name   = 'input'; 
        $value  = 'exist'; 
        $opt    = ['value' => 2]; 
        $attr   = ['class' => 'noclass'];

        foreach ($this->input as $method) {
            $input = $form->{$method}($name, $attr, $value);

                $this->assertEquals($input->name(), $name);
                $this->assertEquals($input->get(),  $value);
                $this->assertEquals($input->type(), $method);
            $this->assertInstanceOf(Input::class, $input);

            $input = $form->{$method}($name, $value);
            
                $this->assertEquals($input->name(), $name);
                $this->assertEquals($input->get(), $value);
                $this->assertEquals($input->type(), $method);
            $this->assertInstanceOf(Input::class, $input);
        } 

        foreach ($this->multi as $method) {
            $input = $form->{$method}($name, $opt, $attr, $value);

                $this->assertEquals($input->name(), $name);
                $this->assertEquals($input->get(), $value);
                $this->assertEquals($input->type(), $method);
            $this->assertInstanceOf(Multi::class, $input);

            $input = $form->{$method}($name, $opt, $value);
            
                $this->assertEquals($input->name(), $name);
                $this->assertEquals($input->get(), $value);
                $this->assertEquals($input->type(), $method);
            $this->assertInstanceOf(Multi::class, $input);
        } 
    }

    public function testFormInputComplete()
    {
        $form = Form::factory();

        foreach ([
            [null,  'name', array(), null,    null],
            [null,  'name', array(), 'foo',   null],
            [null,  'name', 'foo',   null,    null],
            [null,  'name', null,    null,    null],
        ] as $inputs) {
            list($type, $name, $options, $attr, $value) = $inputs;

            foreach ($this->input as $type) {
                $input = $form->input($type, $name, $options, $attr, $value);

                # form 
                $input->set(null);
                $this->assertInternalType('null', $form->get($name));
                $this->assertInternalType('null', $form->{$name});

                $input->set('foo');
                $this->assertInternalType('scalar', $form->get($name));
                $this->assertInternalType('scalar', $form->{$name});

                $this->assertInstanceOf(Input::class, $form->input($name));
                $this->assertEquals($input->name(), $name);
                # parameters assert # type
                $this->assertEquals($input->type(), $type);

                $this->integratedInputImplementation($input);
            }
        }
        

        // $this->assertTrue($input instanceof InputInterface);
    }

    public function testFormInputStaticFactory()
    {
        foreach ($this->input as $type) {
                $input = Input::{$type}($name = $type, [], 'foo');

                $this->assertInstanceOf(Input::class, $input);
                $this->assertEquals($input->type(), $type);

                $this->integratedInputImplementation($input);
            }
    }

    protected function integratedInputImplementation(Input $input)
    {
        $value      = md5(time());
        $message    = 'Invalid.';

        # instance assert
        $this->assertInstanceOf(Input::class, $input);

        # parameters assert # name
        $this->assertInstanceOf(Input::class, $input->name('custom'));
        $this->assertEquals($input->name(), 'custom');

        # parameters assert # type
        $this->assertInstanceOf(Input::class, $input->type('email'));
        $this->assertEquals($input->type(), 'email');

        # parameters assert # value
        $this->assertInstanceOf(Input::class, $input->set($value));
        $this->assertEquals($input->get(), $value);

        # parameters assert # error
        $this->assertInstanceOf(Input::class, $input->error($message));
        $this->assertEquals($input->error(), $message);

        $this->assertInternalType('string', $input->__toString());

        # attr assert
        $this->assertInternalType('array', $input->attr());
        $this->assertInstanceOf(Input::class, $input->attr('class', 'input'));
        $this->assertInternalType('string', $input->attr('class'));
        $this->assertInternalType('null', $input->attr('classfoo'));

        $this->integratedTestFilter($input);
    }

    protected function integratedTestFilter(Input $input)
    {
        # filter assert
        // $this->assertInternalType('array', $input->filters());
        // $this->assertInternalType('bool', $input->valid());
        // $this->assertInstanceOf(Input::class, $input->validate());
        // $this->assertInstanceOf(Input::class, $input->sanitize());
        // $this->assertInstanceOf(Input::class, $input->filter('alnum'));

        # ...
    }

    // ...
}