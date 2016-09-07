<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 *  @since   v1.2.0
 */

namespace Panda;

use Panda\Essence\ReadableAbstract as Essence;

/**
 *  Bootloader
 *
 *  @subpackage *
 */
class Bootloader extends Essence implements BootloaderInterface
{
    public function __construct()
    {
        spl_autoload_register(array($this, 'spl'));

        $this->shared = array(
            'comparison'    => '',
            'collection'    => array(),
            'association'   => array()
        );
    }

    public static function factory()
    {
        return new static();
    }
    
    /**
     *  Register autoloader matches.
     * 
     *  @var mixed  $namespace
     *  @var mixed  $path
     *  
     *  @return \Panda\Bootloader
     */
    public function load($namespace, $dir = null)
    {
        $namespaces = is_array($namespace) ? $namespace : [$namespace => $dir];

        foreach ($namespaces as $namespace => $dir) {
            $this->shared['association'][$namespace]    = $dir;
            $this->shared['collection'][]               = preg_quote($namespace, '/');
        };

        /**
         *  Update comparsion regexp
         */
        $this->shared['comparison'] = sprintf(
            '/^(%s)/', implode(
                '|', $this->shared['collection']
            )
        );
        return $this;
    }

    /**
     *  Implementation psl_autoloader().
     * 
     *  @var string $class
     *  @var mixed  $param
     */
    public function spl($class, $param = null)
    {
        if (
            !empty($this->shared['comparison']) && preg_match($this->shared['comparison'], $class, $namespace)
        ) {
            $namespace          = current($namespace);
            $autoloader_path    = $this->shared['association'][$namespace];
            $autoloader_slice   = preg_replace(
                    $this->shared['comparison'], '', $class
                );

            $class_file_path    = sprintf(
                    '%s/%s.php',
                    $autoloader_path,
                    str_replace('\\', '/', $autoloader_slice)
                );

            file_exists($class_file_path) ? include($class_file_path) : null;
        }
    }
}
