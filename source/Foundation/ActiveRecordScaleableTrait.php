<?php
/**
 *  @category   Blink
 *  @author     Eugen Melnychenko
 *  @version    v1.0
 */

namespace Panda\Foundation;

/**
 *  Blueprint Architect Expansion
 *
 *  @package Database
 */
trait ActiveRecordScaleableTrait
{
    protected function scale_fill()
    {
        $natived        = array_values($this->columns);

        $scalable       = array_combine(
            $natived, $natived
        );

        $replace        = array_filter(
            array_flip($this->columns), 'is_string'
        );

        $this->columns  = array_flip(
            array_replace($scalable, $replace)
        );
    }

    /**
     *  On selection action push to shared
     */ 
    protected function scale_push($columns)
    {
        if (
            !empty($this->columns)
        ) {
            foreach($this->columns as $shared => $origin) {
                if (
                    array_key_exists($origin, $columns)
                ) {
                    $this->shared[$shared] = $columns[$origin];
                } 
            }

            return $this->shared;
        }

        $this->shared = array_replace($this->shared, $columns);

        return $this->shared;
    }
    

    /**
     *  On insert, update action pull from diff shared with origin
     */ 
    protected function scale_pull()
    {
        $diffed = array_diff_assoc($this->shared, $this->origin);

        if (
            !empty($this->columns)
        ) {
            $diffed = array_intersect_key($diffed, $this->columns);
            $shared = array();

            foreach($diffed as $scale => $equal) {
                $shared[$this->columns[$scale]] = $equal;
            }

            return $shared;
        }

        return $diffed;
    }

    /**
     *  On insert, update action pull from diff shared with origin
     */ 
    protected function scale_primary(&$diffed)
    {
        # where clause implementation ...
        $collection = array(); 
        $primaries  = is_array($this->primary) ? $this->primary : array($this->primary);

        foreach($primaries as $scale) {
            $primary = array_key_exists($scale, $this->columns) ? $this->columns[$scale] : $scale;

            if ($this->intable) {
                if (
                    isset($diffed[$primary])
                ) {
                    unset($diffed[$primary]);
                }

                $collection[$primary] = $this->origin[$scale];
            } elseif ($this->watch) {
                # insert schema event
                unset($diffed[$primary]);
            }
        }

        return $collection;
    }

    /**
     *  On insert, update action pull from diff shared with origin
     */ 
    protected function scale_increment()
    {
        return $this->scale_column($this->increment);
    }

    protected function scale_column($column)
    {
        return array_key_exists($column, $this->columns) ? $this->columns[$column] : $column;
    }

    protected function scale_selection($condition)
    {
        $exchanged = array();

        if (
            !empty($this->columns)
        ) {
            foreach ($condition as $column => $equal) {
                $exchanged[$this->columns[$column]] = $equal;
            }
        } else {
            $exchanged = $condition;
        }

        return $exchanged;
    }

    /**
     *  Build timestamp format for auto setter.
     */ 
    protected function scale_timestamp()
    {
        if ($this->timestamp === true || $this->timestamp === 'datetime') {
            return date($this->datetime);
        } elseif ($this->timestamp === 'date') {
            return date($this->date);
        } elseif ($this->timestamp === 'time') {
            return date($this->time);
        } elseif ($this->timestamp === 'unix') {
            return date('U');
        }

        return date($this->datetime);
    }
}
