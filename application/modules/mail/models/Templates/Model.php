<?php
/**
 * This is the DbTable class for the mail table.
 *
 * @category Application
 * @package Model
 * @subpackage DbTable
 *
 * @version  $Id: Manager.php 47 2010-02-12 13:17:34Z AntonShevchuk $
 */
class Mail_Model_Templates_Model extends Core_Mail_Templates_Abstract
{
    /**
     * Assign data to template
     *
     * @param string $name
     * @param string $value
     * @return self
     */
    public function assign($name, $value)
    {
        $this->_data = str_replace("%" . $name . "%", $value, $this->_data);
        return $this;
    }
}
