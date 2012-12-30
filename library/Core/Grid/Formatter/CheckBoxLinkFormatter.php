<?php
/**
 * CheckBoxLinkFormatter
 *
 * @category Core
 * @subpackage Grid
 * @package Formatter
 *
 * @author Dmitriy Savchenko <savchenko.d.v@nixsolutions.com>
 * @file CheckBoxLinkFormatter.php
 * @date 28.11.12
 */
class Core_Grid_Formatter_CheckBoxLinkFormatter
{
    /**
     * check box link formatter
     *
     * @param $value
     * @param $row
     * @return string
     */
    public function formatter($value, $row)
    {
        return '<input type="checkbox" name="id" value="' . $row['id'] . '"/>';
    }
}
