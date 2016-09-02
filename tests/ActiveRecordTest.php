<?php

use PHPUnit\Framework\TestCase;

use Panda\ActiveRecord;
use Panda\Foundation\DatabaseMySQLAdapter;
use Panda\Database;

class ActiveRecordTest extends TestCase
{
    public function testDatabaseConnection()
    {
        $database = Database::create([
            'default' => [
                'adapter' => Database::MYSQL,
                'username' => 'root',
                'password' => '',
                'database' => 'test'
            ],
            'default-1' => [
                'adapter' => Database::MYSQL,
                'username' => 'root',
                'password' => '',
                'database' => 'test'
            ]
        ]);

        $this->assertInstanceOf(Database::class,             Database::singleton());
        $this->assertInstanceOf(DatabaseMySQLAdapter::class, Database::get('default'));
        $this->assertInstanceOf(DatabaseMySQLAdapter::class, Database::get('default-1'));

        $mysql = Database::mysql('default', [
            'username' => 'root',
            'password' => '',
            'database' => 'test'
        ]);

        $this->assertInstanceOf(DatabaseMySQLAdapter::class, $mysql);

        // $form = Form::factory();   

        // $this->assertInstanceOf(Form::class,    $form);
        // $this->assertInstanceOf(Form::class,    $form->validate());
        // $this->assertInstanceOf(Form::class,    $form->sanitize());
        // $this->assertInstanceOf(Form::class,    $form->attr('class', 'form'));
        // $this->assertInternalType('string',     $form->attr('class'));
        // $this->assertInternalType('array',      $form->attr());
        // $this->assertInternalType('null',       $form->attr('class24'));
        // $this->assertInternalType('string',     $form->open());
        // $this->assertInternalType('string',     $form->close());
        // $this->assertInternalType('array',      $form->all());
        // $this->assertInternalType('array',      $form->error());
        // $this->assertInternalType('bool',       $form->valid());
    }

    
    // ...
}