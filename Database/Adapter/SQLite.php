<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Database\Adapter;

use Panda\Database\Adapter;

/**
 *  Database SQLite Adapter
 *
 *  @subpackage Database
 */
class SQLite extends Adapter
{
    /**
     *  @var string
     */
    const ADAPTER_FILESYSTEM    = 'filesystem';
    /**
     *  @var string
     */
    const ADAPTER_MEMORY    = 'memory';
    /**
     *  @var string
     */
    protected $provider    = 'sqlite';
    /**
     *  @var string
     */
    protected $listener    = 'filesystem';
    /**
     *  @var string
     */
    protected $filepath    = null;
    /**
     *  I think it`s constructor.
     *
     *  array(
     *      'username'  => '',
     *      'password'  => '',
     *      'path'      => '',
     *  );
     * 
     *  @var array $conf
     */
    public function __construct(array $conf = null)
    {
        $this->__conf_composite($conf, array(
            'filepath' => 'path',
        ));
    }
    /**
     *  Method for disable username() parent method.
     * 
     *  @var string $username
     *
     *  @return \Blink\Database\Adapter\SQLite
     */
    public function username($username)
    {
        return $this;
    }
    /**
     *  Method for disable password() parent method.
     * 
     *  @var string $username
     *
     *  @return \Blink\Database\Adapter\SQLite
     */
    public function password($password)
    {
        return $this;
    }
    /**
     *  Set file DB storage.
     * 
     *  @var string $path
     *
     *  @return \Blink\Database\Adapter\SQLite
     */
    public function path($path)
    {
        $this->filepath = $path;
        $this->listener = static::ADAPTER_FILESYSTEM;
        return $this;
    }
    /**
     *  Set storage :memory:.
     *
     *  @return \Blink\Database\Adapter\SQLite
     */
    public function memory()
    {
        $this->listener = static::ADAPTER_MEMORY;
        return $this;
    }
    /**
     *  Method for override PDO::DSN design.
     *
     *  @return string
     */
    protected function data_src_name()
    {
        if (
            $this->listener === static::ADAPTER_FILESYSTEM
        ) {
            return sprintf(
                '%s:%s', 
                $this->provider,
                $this->filepath
            );
        }
        return sprintf(
            '%s:%s', 
            $this->provider,
            ':memory:'
        );
    }
}
