<?php
/**
 * Created by Igor Malinovskiy <u.glide@gmail.com>
 * Date: 29.11.12
 * Time: 19:23
 */

class Core_Loader_FixturesLoader implements Zend_Loader_Autoloader_Interface
{
    /**
     * Autoload a class
     *
     * @param   string $class
     * @return  mixed
     *          False [if unable to load $class]
     *          get_class($class) [if $class is successfully loaded]
     */
    public function autoload($class)
    {
        $pathToClassFile = explode("_", $class);
        $pathToClassFile[0] = strtolower($pathToClassFile[0]);

        $classFilePath = realpath(
            APPLICATION_PATH . '/../tests/application/modules/'
        ) . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $pathToClassFile) . '.php';

        if (file_exists($classFilePath)) {
            require_once $classFilePath;

            return $class;
        }

        return false;
    }

}
