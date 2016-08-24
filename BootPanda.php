<?php
/**
 *  Panda PHP Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

class BootPanda
{
    const PANDA_FOUNDATION_DIR      = '/Foundation';
    const PANDA_FRAMEWORK_DIR       = '/Framework';
    const PANDA_NATIVELOADER_MAP    = '/BootPanda.map.php';

    protected $nativeloader_map;

    protected function __construct($force = false)
    {
        $nativeloader_map = __DIR__ . static::PANDA_NATIVELOADER_MAP;

        $this->nativeloader_map = !$force && file_exists($nativeloader_map) ? 
            include $nativeloader_map : $this->__make_nativeloader_map();

        spl_autoload_register(array($this, '__spl_processor'));
    }

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
                "<?php\n\nreturn %s;", var_export($collection, true)
            )
        );

        return $collection;
    }

    public function __spl_processor($class)
    {
        if (
            isset($this->nativeloader_map[$class])
        ) {
            include __DIR__ . $this->nativeloader_map[$class];
        }
    }

    public static function app($force = false)
    {
        new static($force);
    }
}
