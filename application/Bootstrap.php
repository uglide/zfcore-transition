<?php
/**
 * Bootstrap Application
 *
 * @category Application
 * @package  Bootstrap
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    /**
     * Set cache for db tables
     * NOTE: We must clean cache after update application to new version
     */
    protected function _initMetadataCache()
    {
        //we don't need cache in development process
        if ($this->_isCurrEnvNeedCache()) {
            $frontendOptions = array(
                "lifetime" => 60 * 60 * 24 * 30,
                "automatic_serialization" => true,
                "automatic_cleaning_factor" => 1,
                "ignore_user_abort" => true
            );

            $backendOptions = array(
                "file_name_prefix" => APPLICATION_ENV . "_db_table_metadata",
                "cache_dir" => APPLICATION_PATH . "/../data/cache",
                "cache_file_umask" => 0644
            );

            // getting a Zend_Cache_Core object
            $cache = Zend_Cache::factory(
                'Core',
                'File',
                $frontendOptions,
                $backendOptions
            );

            Core_Db_Table_Abstract::setDefaultMetadataCache($cache);
        }
    }

    private function _isCurrEnvNeedCache()
    {
        return (APPLICATION_ENV != 'development');
    }
}