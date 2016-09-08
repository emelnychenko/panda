<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Database;

/**
 *  Database Quering
 *
 *  @subpackage Database
 */
class Quering
{
    /**
     *  @const SELECT
     */ 
    const SELECT = 'SELECT';

    /**
     *  @const INSERT
     */ 
    const INSERT = 'INSERT';

    /**
     *  @const UPDATE
     */ 
    const UPDATE = 'UPDATE';

    /**
     *  @const DELETE
     */ 
    const DELETE = 'DELETE';

    /**
     *  @var string
     */ 
    protected $statement;

    /**
     *  @var string
     */ 
    protected $table;

    /**
     *  @var array
     */ 
    protected $columns  = [];

    /**
     *  @var array
     */
    protected $set      = [];

    /**
     *  @var array
     */
    protected $where    = [];

    /**
     *  @var array
     */
    protected $having    = [];

    /**
     *  @var array
     */
    protected $group    = [];

    /**
     *  @var array
     */
    protected $order    = [];

    /**
     *  @var numeric
     */
    protected $limit;

    /**
     *  @var numeric
     */
    protected $offset;

    /**
     *  @var array
     */
    protected $bind;

    /**
     *  @return \Panda\SQL\Query
     */ 
    public static function factory()
    {
        return new static;
    }

    /**
     *  Set SELECT statement.
     *
     *  @return \Panda\SQL\Query
     */ 
    public function select($column = ['*'])
    {
        $this->columns = is_array($column) ? $column : func_get_args();

        $this->statement = static::SELECT;

        return $this;
    }

    /**
     *  Set INSERT statement and table oriented.
     *
     *  @return \Panda\SQL\Query
     */ 
    public function insert($table, $alias = null)
    {
        $this->statement = static::INSERT; $this->from($table, $alias);

        return $this;
    }

    /**
     *  Set UPDATE statement and table oriented.
     *
     *  @return \Panda\SQL\Query
     */ 
    public function update($table, $alias = null)
    {
        $this->statement = static::UPDATE; $this->from($table, $alias);

        return $this;
    }

    /**
     *  Set DELETE statement.
     *
     *  @return \Panda\SQL\Query
     */ 
    public function delete()
    {
        $this->statement = static::DELETE;

        return $this;
    }

    /**
     *  Get $this->statement value.
     *
     *  @return string
     */ 
    public function statement()
    {
        return $this->statement;
    }

    /**
     *  Set table [, alias] value.
     *
     *  @return \Panda\SQL\Query
     */ 
    public function from($table, $alias = null)
    {
        $this->table = $alias === null ? $table : sprintf('%s AS %s', $table, $alias);

        return $this;
    }

    /**
     *  Set $this->set value. Universal for UPDATE and INSERT.
     *
     *  @return mixed
     */
    public function set($column = null, $equal = null)
    {
        if ($column === null) {
            return $this->set;
        }

        $columns = is_array($column) ? $column : [$column => $equal];

        foreach ($columns as $column => $equal) {
            $this->set[$column] = $this->bind($equal);
        }

        return $this;
    }

    /**
     *  Get, set $this->where value.
     *
     *  @return mixed
     */ 
    public function where($columns = null, $equal = null)
    {
        if ($columns === null) return $this;

        $columns = is_array($columns) ? $columns : (
            $equal === null ? [$columns] : [$columns => $equal]
        );

        foreach ($columns as $column => $equal) {
            $this->where[] = is_numeric($column) ? $column : sprintf(
                '%s = %s', $column, $this->bind($equal)
            );
        }

        return $this;
    }

    /**
     *  Get, set $this->having value.
     *
     *  @return mixed
     */ 
    public function having($columns = null, $equal = null)
    {
        if ($columns === null) return $this;

        $columns = is_array($columns) ? $columns : (
            $equal === null ? [$columns] : [$columns => $equal]
        );

        foreach ($columns as $column => $equal) {
            $this->having[] = is_numeric($column) ? $column : sprintf(
                '%s = %s', $column, $this->bind($equal)
            );
        }

        return $this;
    }

    /**
     *  Get, set $this->group value.
     *
     *  @return mixed
     */ 
    public function group($column = null) 
    {
        if ($column !== null) {
            $column = is_array($column) ? $column : [$column];

            $this->group = array_merge($this->group, $column);
        }

        return $this;
    }

    /**
     *  Get, set $this->order.
     *
     *  @return mixed
     */ 
    public function order($column = null, $sort = 'asc') 
    {
        if ($column !== null) {
            $column = is_array($column) ? $column : [$column => 'asc'];

            $this->order = array_merge($this->order, $column);
        }

        return $this;
    }

    /**
     *  Get set $this->limit, and set $offset.
     *
     *  @return mixed
     */ 
    public function limit($limit = null, $offset = null) 
    {
        if ($limit !== null) {
            $this->limit = $limit;
        }

        if ($offset !== null) {
            $this->offset = $offset;
        }

        return $this;
    }

    /**
     *  Get set $this->offset.
     *
     *  @return mixed
     */ 
    public function offset($offset = null) 
    {
        if ($offset !== null) {
            $this->offset = $offset;
        }

        return $this;
    }

    /**
     *  Get set $this->bind.
     *
     *  @return string
     */ 
    public function bind($column = null, $equal = null)
    {
        // if ($column === null) {
        //     return $this->bind;
        // }

        $hash = ':' . uniqid();

        if ($equal === null) {
            $this->bind[$hash] = $column;

            return $hash;
        }

        $this->bind[$column] = $equal;

        return $this;
    }

    public function binded()
    {
        return $this->bind;
    }

    /**
     *  Gather query string.
     *
     *  @return string
     */ 
    public function __toString()
    {
        if ($this->statement === static::SELECT) {
            $container = [
                $this->joining('SELECT',    'columns',  ', '),
                $this->joining('FROM',      'table',    ', '),
                $this->joining('WHERE',     'where',    ' AND '),
                $this->joining("GROUP BY",  'group',    ', '),
                $this->joining("HAVING",    'having',   ' AND '),
                $this->joining("ORDER BY",  'order',    ', '),
                $this->joining("LIMIT",     'limit',    ', '),
                $this->joining("OFFSET",    'offset',   ', ')
            ];
        }

        if ($this->statement === static::INSERT) {
            $columns = sprintf('(%s)', implode(', ',   array_keys($this->set)));
            $values  = sprintf('(%s)', implode(', ', array_values($this->set)));

            $container = [
                $this->joining('INSERT INTO', 'table',    ', '),
                $columns,
                $this->joining('VALUES', $values,         ', '),
            ]; 
        }

        if ($this->statement === static::UPDATE) {
            $shared = []; foreach ($this->set as $column => $value) {
                array_push($shared, sprintf('%s = %s', $column, $value));
            }

            $container = [
                $this->joining('UPDATE',    'table',  ', '),
                $this->joining('SET',       $shared,    ', '),
                $this->joining('WHERE',     'where',    ' AND '),
                $this->joining("GROUP BY",  'group',    ', '),
                $this->joining("HAVING",    'having',   ' AND '),
                $this->joining("ORDER BY",  'order',    ', '),
                $this->joining("LIMIT",     'limit',    ', '),
                $this->joining("OFFSET",    'offset',   ', ')
            ];
        }

        if ($this->statement === static::DELETE) {
            $container = [
                'DELETE',
                $this->joining('FROM',      'table',    ', '),
                $this->joining('WHERE',     'where',    ' AND '),
                $this->joining("GROUP BY",  'group',    ', '),
                $this->joining("HAVING",    'having',   ' AND '),
                $this->joining("ORDER BY",  'order',    ', '),
                $this->joining("LIMIT",     'limit',    ', '),
                $this->joining("OFFSET",    'offset',   ', ')
            ];
        }

        return implode(
            ' ', array_filter($container)
        );
    }

    /**
     *  Joining helper method.
     *
     *  @return string
     */ 
    protected function joining($clause, $property = null, $implode = ', ')
    {
        if ($property === null) return $clause;

        $shared = is_string($property) && property_exists($this, $property) ? $this->{$property} : $property;

        if (empty($shared) === true) return null;

        if (is_array($shared) === true) {
            $shared = implode($implode, $shared);
        }

        return sprintf("%s %s", $clause, $shared);
    }
}
