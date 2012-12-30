<?php
/**
 * DateFormatter
 *
 * @category Core
 * @subpackage Grid
 * @package Formatter
 *
 * @author Dmitriy Savchenko <savchenko.d.v@nixsolutions.com>
 * @file DateFormatter.php
 * @date 28.11.12
 */
class Core_Grid_Formatter_DateFormatter
{
    /**
     * @param $value
     *
     * @return string
     */
    public function formatter($value)
    {
        return $this->view->datetime($value);
    }
}
