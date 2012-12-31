<?php
/**
 * @category Public
 * @package  Bootstrap
 */

$appRootPath = realpath(__DIR__ . '/../');

//boosted index
$constants = array(
    // Define error reporting info
    'ERROR_REPORT_MAIL' => 'admin@localhost.com',
    // Define path to application directory
    'ERROR_REPORT_SUBJECT' => 'ZFCore: Error occurred in application',
    'APPLICATION_ROOT_PATH' => $appRootPath,
    // Define path to application directory
    'APPLICATION_PATH' => $appRootPath . '/application',
    // Define application environment
    'APPLICATION_ENV' => (($env = getenv('APPLICATION_ENV')) ? $env : 'production'),
    'PUBLIC_PATH' => __DIR__, // Define path to public directory
    'DS' => DIRECTORY_SEPARATOR, // Define short alias for DIRECTORY_SEPARATOR
    'START_TIMER' => microtime(true)
);

apc_define_constants('ZFCoreTransitionConstants', $constants);

// Ensure library/ is on include_path
set_include_path(
    implode(
        PATH_SEPARATOR,
        array(
            $appRootPath . '/vendor/uglide/zendframework1/library',
            $appRootPath . '/vendor',
            $appRootPath . '/library',
            get_include_path()
        )
    )
);

require_once 'Core/ErrorHandler.php';

register_shutdown_function(array('Core_ErrorHandler', 'handler'));
set_error_handler(array('Core_ErrorHandler', 'handler'));

require 'autoload.php';

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

    require_once 'Zend/Loader/AutoloaderFactory.php';
    require_once 'Zend/Loader/ClassMapAutoloader.php';

    Zend_Loader_AutoloaderFactory::factory(
        array(
            'Zend_Loader_ClassMapAutoloader' => array(
                $appRootPath . '/vendor/uglide/zendframework1/library/autoload_classmap.php',
                $appRootPath . '/library/autoload_classmap.php'
            ),
            'Zend_Loader_StandardAutoloader' => array(
                'autoloadernamespaces' => $config['autoloadernamespaces'],
                'fallback_autoloader' => true
            )
        )
    );

    require_once 'Zend/Application.php';

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