<?php
/**
 * TrimFormatter
 *
 * @category Core
 * @subpackage Grid
 * @package Formatter
 *
 * @author Dmitriy Savchenko <savchenko.d.v@nixsolutions.com>
 * @file TrimFormatter.php
 * @date 28.11.12
 */
class Core_Grid_Formatter_TrimFormatter
{
    /**
     * @param $value
     *
     * @return string
     */
    public function formatter($value)
    {
        if (strlen($value) >= 200) {
            if (false !== ($breakpoint = strpos($value, ' ', 200))) {
                if ($breakpoint < strlen($value) - 1) {
                    $value = substr($value, 0, $breakpoint) . ' ...';
                }
            }
        }
        return $value;
    }
}
