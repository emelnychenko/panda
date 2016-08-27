<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda;

use Panda\Foundation\SingletonProviderInterface;
use Panda\Foundation\SingletonProviderExpansion;
use Panda\Foundation\TechnicalProviderExpansion;

/**
 *  Panda Swift
 *
 *  @subpackage Framework
 */
class Swift implements SwiftInterface, SingletonProviderInterface
{
    /**
     *  @var array
     */ 
    protected $pools = array();

    public function append($dir, $extension = null)
    {
        $this->tpe_pair_iterator($dir, $extension, function($dir, $extension) {
            $this->pools[$dir] = $extension;
        });
    }

    public function compile($file, array $container = array(), $prevent = false)
    {
        return new SwiftPage(
            $this, $file, $container, $prevent
        );   
    }

    public function finder($file)
    {
        foreach ($this->pools as $dir => $extension) {
            $filepath = sprintf('%s/%s.%s', $dir, $file, $extension);

            if (
                file_exists($filepath)
            ) {
                return $filepath;
            }
        }
    }

    use SingletonProviderExpansion;
    use TechnicalProviderExpansion;
}
