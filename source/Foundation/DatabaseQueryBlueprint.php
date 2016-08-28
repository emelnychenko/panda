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
 *  Database Query Blueprint
 *
 *  @subpackage Foundation
 */
class DatabaseQueryBlueprint
{
    /**
     *  @var string
     */
    const STATEMENT_SELECT  = 'SELECT';

    /**
     *  @var string
     */
    const STATEMENT_INSERT  = 'INSERT';

    /**
     *  @var string
     */
    const STATEMENT_UPDATE  = 'UPDATE';

    /**
     *  @var string
     */
    const STATEMENT_DELETE  = 'DELETE';

    /**
     *  @var string
     */
    const STATEMENT_REGEXP  = '/^(SELECT|INSERT|UPDATE|DELETE)$/ui';

    /**
     *  @var string
     */
    protected $statement   = null;

    /**
     *  @var string
     */
    protected $modifier    = null;
    
    /**
     *  @var string
     */
    protected $table       = null;

    /**
     *  @var array
     */
    protected $column      = array();

    /**
     *  @var array
     */
    protected $set         = array();

    /**
     *  @var array
     */
    protected $where       = array();

    /**
     *  @var array
     */
    protected $join        = array();

    /**
     *  @var array
     */
    protected $group       = array();

    /**
     *  @var array
     */
    protected $having      = array();

    /**
     *  @var array
     */
    protected $order       = array();

    /**
     *  @var string
     */
    protected $limit       = null;

    /**
     *  @var string
     */
    protected $offset      = null;

    /**
     *  @var array
     */
    protected $bind        = array();

    /**
     *  @return \Blink\Database\QueryBlueprint
     */
    public static function create()
    {
        return new static();
    }
    
    /**
     *  @var array  $column
     *
     *  @return \Blink\Database\QueryBlueprint
     */
    public function select(array $column = null)
    {
        return $this->__statement(static::STATEMENT_SELECT)->__column($column);
    }
    
    /**
     *  @var string $table
     *  @var mixed $alias
     *
     *  @return \Blink\Database\QueryBlueprint
     */
    public function insert($table, $alias = null)
    {
        return $this->__statement(static::STATEMENT_INSERT)->__table($table, $alias);
    }
    
    /**
     *  @var string $table
     *  @var mixed $alias
     *
     *  @return \Blink\Database\QueryBlueprint
     */
    public function update($table, $alias = null)
    {
        return $this->__statement(static::STATEMENT_UPDATE)->__table($table, $alias);
    }
    
    /**
     *  @return \Blink\Database\QueryBlueprint
     */
    public function delete()
    {
        return $this->__statement(static::STATEMENT_DELETE);
    }
    
    /**
     *  @var string $table
     *  @var mixed $alias
     *
     *  @return \Blink\Database\QueryBlueprint
     */
    public function from($table, $alias = null)
    {
        return $this->__table($table, $alias);
    }
    
    /**
     *  @var string $table
     *  @var string $alias
     *  @var array  $condition
     *  @var string $glue
     *
     *  @return \Blink\Database\QueryBlueprint
     */
    public function join($table, $alias, array $condition, $glue = "INNER")
    {
        $condition_in = $this->__implode_assoarr($condition, "%s = %s");
        
        $this->__include_assign($glue, array("INNER", "LEFT", "RIGHT", "OUTER"), "INNER");
        
        $this->join[] = sprintf(
                "%s JOIN %s AS %s ON %s", $glue, $table, $alias, implode(", ", $condition_in)
            );
        
        return $this;
    }
    
    /**
     *  @var mixed $column
     *  @var mixed $equal
     *
     *  @return \Blink\Database\QueryBlueprint
     */
    public function set($column, $equal = null)
    {
        return $this->__divide_assign($column, $equal, function($column, $equal) {
            $this->set[$column] = $this->bind($equal);
        });
    }
    
    /**
     *  @var mixed $column
     *  @var mixed $equal
     *
     *  @return \Blink\Database\QueryBlueprint
     */
    public function where($column, $equal = null)
    {
        return $this->__divide_assign($column, $equal, function($column, $equal) {
            $this->where[] = is_string($column) && isset($equal) ? $this->__equal($column, $equal) : $column;
        });
    }
    
    /**
     *  @var mixed $column
     *
     *  @return \Blink\Database\QueryBlueprint
     */
    public function group($column)
    {
        return $this->__divide_assign($column, null, function($column) {
            $this->group[] = $column;
        });
    }
    
    /**
     *  @var mixed $column
     *  @var mixed $equal
     *
     *  @return \Blink\Database\QueryBlueprint
     */
    public function having($column, $equal = null)
    {
        return $this->__divide_assign($column, $equal, function($column, $equal) {
            $this->having[] = is_string($column) && isset($equal) ? $column : $this->__equal($column, $equal);
        });
    }
    
    /**
     *  @var mixed $column
     *  @var mixed $sort
     *
     *  @return \Blink\Database\QueryBlueprint
     */
    public function order($column, $sort = "ASC")
    {
        return $this->__divide_assign($column, $sort, function($column, $sort) {
            $this->order[] = sprintf("%s %s", $column, $sort);
        });
    }
    
    /**
     *  @var integer $column
     *
     *  @return \Blink\Database\QueryBlueprint
     */
    public function limit($equal)
    {
        return $this->__numeric('limit', $equal);
    }
    
    /**
     *  @var integer $column
     *
     *  @return \Blink\Database\QueryBlueprint
     */
    public function offset($equal)
    {
        return $this->__numeric('offset', $equal);
    }
    
    /**
     *  @return string
     */
    public function blueprint()
    {
        $container = array();

        if (
            $this->statement === static::STATEMENT_SELECT
        ) {
            $container = array(
                $this->__clause("SELECT",   $this->column, null, null),
                $this->__clause("FROM",     $this->table,  null, null),
                $this->__clause(null,       $this->join,   null, " "),
                $this->__clause("WHERE",    $this->where,  null, " AND "),
                $this->__clause("GROUP BY", $this->group,  null, null),
                $this->__clause("HAVING",   $this->having, null, " AND "),
                $this->__clause("ORDER BY", $this->order,  null, null),
                $this->__clause("LIMIT",    $this->limit,  null, null),
                isset($this->limit) ? $this->__clause("OFFSET",   $this->offset, null, null) : null
            );
        } elseif (
            $this->statement === static::STATEMENT_INSERT
        ) {
            $container = array(
                $this->__clause("INSERT INTO", $this->table,             null,   null),
                $this->__clause(null,            array_keys($this->set), "(%s)", null),
                $this->__clause("VALUES",      array_values($this->set), "(%s)", null),
            );  
        } elseif (
            $this->statement === static::STATEMENT_UPDATE
        ) {
            $container = array(
                $this->__clause("UPDATE",   $this->table,  null, null),
                $this->__clause("SET",      $this->__implode_assoarr($this->set, "%s = %s")),
                $this->__clause(null,       $this->join,   null, " "),
                $this->__clause("WHERE",    $this->where,  null, " AND "),
                $this->__clause("GROUP BY", $this->group,  null, null),
                $this->__clause("HAVING",   $this->having, null, " AND "),
                $this->__clause("ORDER BY", $this->order,  null, null),
                $this->__clause("LIMIT",    $this->limit,  null, null),
                isset($this->limit) ? $this->__clause("OFFSET",   $this->offset, null, null) : null
            );
        } elseif (
            $this->statement === static::STATEMENT_DELETE
        ) {
            $container = array(
                              "DELETE",
                $this->__clause("FROM",     $this->table,  null, null),
                $this->__clause(null,       $this->join,   null, " "),
                $this->__clause("WHERE",    $this->where,  null, " AND "),
                $this->__clause("GROUP BY", $this->group,  null, null),
                $this->__clause("HAVING",   $this->having, null, " AND "),
                $this->__clause("ORDER BY", $this->order,  null, null),
                $this->__clause("LIMIT",    $this->limit,  null, null),
                isset($this->limit) ? $this->__clause("OFFSET",   $this->offset, null, null) : null
            );
        }

        return implode(
            " ", array_filter($container)
        );
    }

    /**
     *  @return array
     */
    public function binded()
    {
        return $this->bind;
    }
    
    /**
     *  @var string $column
     *  @var mixed  $equal
     *
     *  @return \Blink\Database\QueryBlueprint
     */
    public function bind($column, $equal = null)
    {
        if (
            isset($equal)
        ) {
            $this->bind[$column] = $equal;
            
            return $this;
        }
        
        $union                  = ":" . uniqid();
        $this->bind[$union]    = $column;
        
        return $union;
    }

    /**
     *  @var mixed $statement
     *
     *  @return \Blink\Database\QueryBlueprint
     */
    protected function __statement($statement)
    {
        if (
            preg_match(self::STATEMENT_REGEXP, $statement)
        ) {
            $this->statement = $statement;
        }

        return $this;
    }

    /**
     *  @var string $column
     *  @var mixed  $equal
     *
     *  @return \Blink\Database\QueryBlueprint
     */
    protected function __table($table, $alias = null)
    {
        if (
            is_string($table)
        ) {
            $this->table = is_string($alias) ? sprintf(
                '%s AS %s', $table, $alias
            ) : $table;
        }

        return $this;
    }

    /**
     *  @var array $column
     *
     *  @return \Blink\Database\QueryBlueprint
     */
    protected function __column(array $column = null)
    {
        $this->column = !empty($column) ? implode(", ", $column) : "*";

        return $this;
    }

    /**
     *  @var string $argument
     *  @var integer $equal
     *
     *  @return \Blink\Database\QueryBlueprint
     */
    protected function __numeric($argument, $equal = null)
    {
        if (
            is_numeric($equal)
        ) {
            $this->{$argument} = $equal;
        }

        return $this;
    }

    /**
     *  @var string $argument
     *  @var integer $equal
     *
     *  @return \Blink\Database\QueryBlueprint
     */
    protected function __equal($column, $equal)
    {
        return sprintf(
            '%s = %s', $column, $this->bind($equal)
        );
    }
    
    /**
     *  @var string $argument
     *  @var integer $equal
     *
     *  @return \Blink\Database\QueryBlueprint
     */
    private function __clause($clause, $param, $pattern = null, $glue = null)
    {
        if (
            !empty($param)
        ) {
            if (
                is_array($param)
            ) {
                $param = implode(isset($glue) ? $glue : ", ", $param); 
            }

            if (
                empty($clause)
            ) {
                return sprintf(
                    isset($pattern) ? $pattern : "%s", $param
                );
            }

            return sprintf(
                    "%s " . (isset($pattern) ? $pattern : "%s"), $clause, $param
                );
        }
        
        return null;
    }

    use BlueprintArchitectTrait;
}