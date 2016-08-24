<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda Framework
 *  @author  Eugen Melnychenko
 */

include 'panda.complitable.php';

/**
 *  Native boot with fast class map incuding.
 */
class PandaBootloader
{
    const PANDA_FOUNDATION_DIR      = '/Foundation';
    const PANDA_FRAMEWORK_DIR       = '/Framework';
    const PANDA_NATIVELOADER_MAP    = '/panda.bootloader.map.php';

    /**
     *  @var array
     */
    protected $nativeloader_map;

    /**
     *  Initialize class by static::app($force = false) action.
     *  Force param mean than every boot build class map.
     *
     *  @var bool $force
     */
    protected function __construct($force = false)
    {
        $nativeloader_map = __DIR__ . static::PANDA_NATIVELOADER_MAP;

        $this->nativeloader_map = !$force && file_exists($nativeloader_map) ? 
            include $nativeloader_map : $this->__make_nativeloader_map();

        spl_autoload_register(array($this, '__spl_processor'));
    }

    /**
     *  System finder all classes in child directories.
     *
     *  @var string $directory
     *  @var array  $collection
     *
     *  @return array
     */
    protected function __find_nativeloader_classes($directory, &$collection = array())
    {
        $items = glob($directory . '/*');

        foreach ($items as $item) {
            if (
                is_file($item) && preg_match('/\.php$/', $item)
            ) {
                array_push($collection, $item);
            } elseif (
                is_dir($item)
            ) {
                $this->__find_nativeloader_classes($item, $collection);
            }
        }

        return $collection;
    }

    /**
     *  Export map class to BootPanda.map.php file.
     *
     *  @return array
     */
    protected function __make_nativeloader_map()
    {
        $collection = array();

        foreach (array(
            'Panda\\Foundation'  => __DIR__ . static::PANDA_FOUNDATION_DIR,
            'Panda'              => __DIR__ . static::PANDA_FRAMEWORK_DIR,
        ) as $namespace => $directory) {
            foreach ($this->__find_nativeloader_classes($directory) as $filetrace) {
                $collection[
                    $namespace . str_replace(array($directory, '/', '.php'), array('', '\\', ''), $filetrace)
                ] = str_replace(__DIR__, '', $filetrace);
            }
        }

        file_put_contents(
            __DIR__ . static::PANDA_NATIVELOADER_MAP, 
            sprintf(
                "<?php\n\nreturn %s;\n", var_export($collection, true)
            )
        );

        return $collection;
    }

    /**
     *  SPL Autoloder processor.
     *
     *  @var string $class
     */
    public function __spl_processor($class)
    {
        if (
            isset($this->nativeloader_map[$class])
        ) {
            include __DIR__ . $this->nativeloader_map[$class];
        }
    }

    /**
     *  Factory execution.
     *
     *  @var bool $force
     */
    public static function app($force = false)
    {
        new static($force);
    }
}
