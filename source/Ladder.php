<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda;

use Panda\Foundation\EssenceReadableAbstract;

use Panda\Foundation\SingletonProviderInterface;
use Panda\Foundation\SingletonProviderExpansion;
use Panda\Foundation\TechnicalProviderExpansion;

/**
 *  Panda Ladder
 *
 *  @subpackage Framework
 */
class Ladder extends EssenceReadableAbstract implements LadderInterface, SingletonProviderInterface
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
     *  @return \Panda\Ladder
     */
    public function add($association, $equal = null)
    {
        $this->tpe_pair_iterator($association, $equal, function($key, $equal) {
            $this->shared['association'][$key]   = $equal;
            $this->shared['collection'][]        = preg_quote($key, '/');
        });

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

    use SingletonProviderExpansion;
    use TechnicalProviderExpansion;
}