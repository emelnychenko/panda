<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Foundation;

use PDO;
use PDOException;
use Closure;

/**
 *  Database Adapter
 *
 *  @subpackage Foundation
 */
abstract class DatabaseAdapterAbstract implements DatabaseAdapterInterface
{
    /**
     *  @var string
     */
    protected $provider;

    /**
     *  @var string [base64]
     */
    protected $username;

    /**
     *  @var string [base64]
     */
    protected $password;

    /**
     *  @var \PDO
     */
    protected $instance;

    /**
     *  I think it`s constructor.
     *
     *  array(
     *      'username'  => '',
     *      'password'  => '',
     *  );
     * 
     *  @var array $conf
     */
    public function __construct(array $config = null)
    {
        $this->__conf_composite($config, array(
            'username' => 'username',
            'password' => 'password',
        ), 'base64_encode');
    }

    /**
     *  Factory static method for creating self instance, set prototype and fast connect.
     * 
     *  @var array  $conf
     *  @var mixed  $prototype
     *  @var bool   $connect
     *
     *  @return \Blink\Database\AdapterProvider
     */
    public static function factory(array $config = null, $connect = true)
    {
        $prototype = new static($config);

        if ($connect) $prototype->connect();

        return $prototype;
    }

    /**
     *  Method for set username value with safe base64 encoding.
     * 
     *  @var string $username
     *
     *  @return \Blink\Database\AdapterProvider
     */
    public function username($username)
    {
        $this->username = base64_encode($username);

        return $this;
    }

    /**
     *  Method for set password value with safe base64 encoding.
     * 
     *  @var string $username
     *
     *  @return \Blink\Database\AdapterProvider
     */
    public function password($password)
    {
        $this->password = base64_encode($password);

        return $this;
    }

    /**
     *  Method for generating PDO connection.
     *
     *  @return \Blink\Database\AdapterProvider
     */
    public function connect()
    {
        if (
            !isset($this->instance)
        ) {
            $this->instance = new PDO(
                $this->data_src_name(), 
                base64_decode($this->username), 
                base64_decode($this->password),
                array(
                    PDO::ATTR_PERSISTENT => true
                )
            );

            $this->instance->setAttribute(
                PDO::ATTR_ERRMODE, 
                PDO::ERRMODE_EXCEPTION
            );
        }
        
        return $this;
    }

    /**
     *  Mixed method for execution string query or handle QueryInjection.
     *
     *  @return \Blink\Database\AdapterProvider
     */
    public function query($decission)
    {
        return $this->__safe_execution(function($instance) use ($decission) {
            if (
                is_string($decission)
            ) {
                return DatabaseStateProvider::create(
                    $instance->query($decission, PDO::FETCH_ASSOC), true
                );
            } elseif (
                is_callable($decission)
            ) {
                $blueprint = DatabaseQueryBlueprint::create();

                call_user_func($decission, $blueprint);

                $query_req = $blueprint->blueprint();

                if (
                    !empty($query_req)
                ) {
                    $statement = DatabaseStateProvider::create($instance->prepare(
                        $query_req
                    ));

                    return $statement->exec(
                        $blueprint->binded()
                    );
                }
            }
        });
    }

    /**
     *  Method for handling transaction block. PDOException return $exception;
     *
     *  @var Closure $callback
     *  @var mixed   $exception
     *
     *  @return bool
     */
    public function transaction(Closure $callback, &$exception = null)
    {
        return $this->__safe_execution(function($instance) use ($callback, &$exception) {
            $exception = null;

            try {
                $instance->beginTransaction();

                call_user_func($callback, $this);

                $instance->commit();

                return true;
            } catch (PDOException $e) {
                $instance->rollBack();

                $exception = $e;

                return false;
            }
        });
    }

    /**
     *  Method for returning lastInsertId \PDO;
     *
     *  @return integer
     */
    public function id()
    {
        return $this->__safe_execution(function($instance) {
            return (int) $instance->lastInsertId();
        });
    }

    /**
     *  Method for execution PDO methods if it exist.
     *
     *  @var Closure $callback
     *
     *  @return mixed
     */
    protected function __safe_execution(Closure $callback)
    {
        return isset($this->instance) ? call_user_func($callback, $this->instance) : null;
    }

    /**
     *  Method for execution handle connection parameters with callback.
     *
     *  @var array $conf
     *  @var array $association
     *  @var Closure $callback
     */
    protected function __conf_composite(array $conf = null, $association, $callback = null) 
    {
        foreach ($association as $argument => $thread) {
            if (
                isset($conf[$thread])
            ) {
                $this->{$argument} = isset($callback) ? 
                    call_user_func(
                        $callback, $conf[$thread]
                    ) : $conf[$thread];
            }
        }
    }

    /**
     *  Method for override PDO::DSN design.
     *
     *  @return string
     */
    abstract protected function data_src_name();
}
