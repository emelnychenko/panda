<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Database;

use PDO;
use PDOStatement;

/**
 *  Database Statement
 *
 *  @subpackage Database
 */
class Statement
{
    /**
     *  @var \PDOStatement
     */
    protected $statement;
    /**
     *  @var bool
     */
    protected $executed;
    /**
     *  I think it`s constructor.
     * 
     *  @var \PDOStatement  $statement
     *  @var bool           $executed
     */
    public function __construct(PDOStatement $statement, $executed = false)
    {
        $this->statement   = $statement;
        $this->executed    = $executed;
    }
    /**
     *  Factory method for init QueryStatement intance.
     * 
     *  @var \PDOStatement  $statement
     *  @var bool           $executed
     *
     *  @return \Blink\Database\QueryStatement
     */
    public static function factory(PDOStatement $statement, $executed = false)
    {
        return new static($statement, $executed);
    }
    /**
     *  Override PDOStatement::fetch if query not executed.
     * 
     *  @return array
     */
    public function one()
    {
        return $this->statement->fetch(PDO::FETCH_ASSOC);
    }
    /**
     *  Override PDOStatement::fetchAll if query not executed.
     * 
     *  @return array
     */
    public function all()
    {
        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     *  Override PDOStatement::rowCount if query not executed.
     * 
     *  @return int
     */
    public function count()
    {
        return $this->statement->rowCount();
    }
    /**
     *  Override PDOStatement::execute if query not executed.
     * 
     *  @var array $bind
     *
     *  @return \Blink\Database\QueryStatement
     */
    public function exec(array $bind = null)
    {
        if (
            !$this->executed
        ) {
            $this->statement->execute($bind);
        }
        return $this;
    }
}