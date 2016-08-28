<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Foundation;

/**
 *  Database MySQL Adapter
 *
 *  @subpackage Foundation
 */
class DatabaseMySQLAdapter extends DatabaseAdapterAbstract implements DatabaseMySQLInterface
{
    /**
     *  @var string
     */
    const ADAPTER_SOCKET    = 'socket';
    
    /**
     *  @var string
     */
    const ADAPTER_TCP_IP    = 'tcp/ip';

    /**
     *  @var string
     */
    protected $provider    = 'mysql';

    /**
     *  @var string
     */
    protected $database    = null;

    /**
     *  @var string
     */
    protected $listener    = 'tcp/ip';

    /**
     *  @var string
     */
    protected $tcp_host    = '127.0.0.1';

    /**
     *  @var integer
     */
    protected $tcp_port    = 3306;

    /**
     *  @var string
     */
    protected $unixpath    = '/var/lib/mysql/mysql.sock';

    /**
     *  @var string
     */
    protected $encoding = 'utf8';

    /**
     *  I think it`s constructor.
     *
     *  array(
     *      'username'      => '',
     *      'password'      => '',
     *      'dbname'        => '',
     *      'host'          => '',
     *      'port'          => '',
     *      'unix_socket'   => '',
     *      'charset'       => '',
     *  );
     * 
     *  @var array $connect_arr
     */
    public function __construct(array $connect_arr = null)
    {
        $this->__conf_composite($connect_arr, array(
            'database' => 'dbname',
            'tcp_host' => 'host',
            'tcp_port' => 'port',
            'unixpath' => 'unix_socket',
            'encoding' => 'charset'
        ));

        parent::__construct($connect_arr);
    }

    /**
     *  Set TPC\IP host.
     * 
     *  @var string $host
     *
     *  @return Panda\Foundation\DatabaseMySQLAdapter
     */
    public function host($host)
    {
        $this->tcp_host = $host; 
        return $this;
    }

    /**
     *  Set TPC\IP port.
     * 
     *  @var string $port
     *
     *  @return Panda\Foundation\DatabaseMySQLAdapter
     */
    public function port($port)
    {
        $this->tcp_port = $port;
        return $this;
    }

    /**
     *  Set UNIX Socket path.
     * 
     *  @var string $unixpath
     *
     *  @return Panda\Foundation\DatabaseMySQLAdapter
     */
    public function unix_socket($unixpath)
    {
        $this->unixpath = $unixpath;
        $this->listener = static::ADAPTER_SOCKET;
        return $this;
    }

    /**
     *  Set database name.
     * 
     *  @var string $database
     *
     *  @return \Blink\Database\Adapter\MySQL
     */
    public function dbname($database)
    {
        $this->database = $database;
        return $this;
    }

    /**
     *  Set charset value.
     * 
     *  @var string $charset
     *
     *  @return Panda\Foundation\DatabaseMySQLAdapter
     */
    public function charset($charset)
    {
        $this->encoding = $charset;
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
            $this->listener === static::ADAPTER_SOCKET && file_exists($this->unixpath)
        ) {
            return sprintf(
                '%s:unix_socket=%s;dbname=%s', 
                $this->provider, 
                $this->unixpath, 
                $this->database
            );
        }
        return sprintf(
            '%s:host=%s;port=%s;dbname=%s', 
            $this->provider, 
            $this->tcp_host, 
            $this->tcp_port, 
            $this->database
        );
    }
    
    /**
     *  Method override parent connect() for exchange charset.
     *
     *  @return Panda\Foundation\DatabaseMySQLAdapter
     */
    public function connect()
    {
        $init_connection = !isset($this->instance);
        parent::connect();
        if ($init_connection) $this->instance->exec(
            sprintf("set names %s", $this->encoding)
        );
        return $this;
    }
}
