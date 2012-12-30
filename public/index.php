<?php
/**
 * @category Public
 * @package  Bootstrap
 */

// Define error reporting info
defined('ERROR_REPORT_MAIL')
    || define('ERROR_REPORT_MAIL', 'admin@localhost.com');

// Define path to application directory
defined('ERROR_REPORT_SUBJECT')
    || define('ERROR_REPORT_SUBJECT', 'ZFCore: Error occurred in application');


// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Define path to public directory
defined('PUBLIC_PATH')
    || define('PUBLIC_PATH', dirname(__FILE__));

// Define short alias for DIRECTORY_SEPARATOR
defined('DS')
    || define('DS', DIRECTORY_SEPARATOR);

// Define short alias for DIRECTORY_SEPARATOR
defined('START_TIMER')
    || define('START_TIMER', microtime(true));

// Ensure library/ is on include_path
set_include_path(
    implode(
        PATH_SEPARATOR,
        array(
            realpath(APPLICATION_PATH . '/../vendor/uglide/zendframework1/library'),
            realpath(APPLICATION_PATH . '/../vendor'),
            realpath(APPLICATION_PATH . '/../library'),
            get_include_path()
        )
    )
);

require_once 'Core/ErrorHandler.php';

register_shutdown_function(array('Core_ErrorHandler', 'handler'));
set_error_handler(array('Core_ErrorHandler', 'handler'));

require 'autoload.php';

/** Zend_Application */
require_once 'Zend/Application.php';

try {
    $config = APPLICATION_PATH . '/configs/application.yaml';

    if (APPLICATION_ENV != 'development') {
        if (realpath($config)) {
            require_once 'Zend/Cache.php';
            $frontendOptions = array("lifetime" => 60*60*24,
                                     "automatic_serialization" => true,
                                     "automatic_cleaning_factor" => 1,
                                     "ignore_user_abort" => true);

            $backendOptions  = array("file_name_prefix" => APPLICATION_ENV . "_config",
                                     "cache_dir" =>  APPLICATION_PATH ."/../data/cache",
                                     "cache_file_umask" => 0644);

            // getting a Zend_Cache_Core object
            $cache = Zend_Cache::factory(
                'Core',
                'File',
                $frontendOptions,
                $backendOptions
            );

            if (!$result = $cache->load('application')) {
                require_once 'Zend/Config/Yaml.php';
                require_once 'Core/Config/Yaml.php';

                $result = new Core_Config_Yaml($config, APPLICATION_ENV);
                $result = $result->toArray();
                $cache->save($result, 'application');
            }
            $config = $result;
        } else {
            throw new Exception('Config not founded!');
        }
    }

    // Create application, bootstrap, and run
    $application = new Zend_Application(
        APPLICATION_ENV,
        $config
    );

    $application->bootstrap()
                ->run();

} catch (Exception $exception) {
    Core_ErrorHandler::saveErrorInLog($exception, 'exception');
    Core_ErrorHandler::exitWithMessage('General Application Error!');
}