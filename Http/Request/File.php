<?php
/**
 *
 *
 *
 * 
 */

namespace Panda\Http\Request;

use Panda\Essence\Readable  as Essence;

/**
 *
 *
 *  @subpackage File
 */
class File
{
    /**
     *  @var \Panda\Essence\Readable
     */
    protected $file;

    /**
     *  @var integer
     */
    protected $size = 0;

    /**
     *  @var array
     */
    protected $mime;

    /**
     *  @var bool
     */
    protected $valid    = true;

    /**
     *  
     */
    public function __construct(array $file = null)
    {
        $this->file     = Essence::factory($file);

        $this->valid    = $valid = isset(
            $file['name'], $file['type'], $file['tmp_name'], $file['error'], $file['size']
        );
    }

    /**
     *  
     * 
     *  @return \Panda\File\Upload
     */
    public static function factory(array $file = null)
    {
        return new static($file);
    }

    /**
     *  #
     * 
     *  @param numeric $size
     * 
     *  @return mixed
     */
    public function mime(array $mime = null)
    {
        if ($mime === null) {
            return $this->mime;
        }


        $this->mime = $mime;

        return $this;
    }

    /**
     *  #
     * 
     *  @param numeric $size
     * 
     *  @return mixed
     */
    public function size($size = null)
    {
        if ($size === null) {
            return $this->size;
        }


        $this->size = $size;

        return $this;
    }


    /**
     *  #
     * 
     *  @return \Panda\Http\Request\File
     */
    public function validate()
    {
        $this->valid = true;

        if ($this->size > 0 && $this->file->size > $this->size) {
            $this->valid = false;
        }

        if ($this->mime !== null && in_array($this->file->type, $this->mime, true) == false) {
            $this->valid = false;
        }

        return $this;
    }

    /**
     *  #
     * 
     *  @return bool
     */
    public function valid()
    {
        return $this->valid;
    }

    /**
     *  
     */
    public function put($destination)
    {
        move_uploaded_file($this->file->tmp_name, $destination);

        return $destination;
    }
}

// if (
    //         is_array($file['name'])
    //     ) {
    //         $instance = [];

    //         foreach ($file['name'] as $index => $value) {
    //             array_push($instance, static::factory([
    //                     'name'      => $file['name'][$index],
    //                     'type'      => $file['type'][$index],
    //                     'tmp_name'  => $file['tmp_name'][$index],
    //                     'error'     => $file['name'][$index],
    //                     'size'      => $file['size'][$index]
    //                 ])
    //             );
    //         }

    //         return $instance;
    //     }
