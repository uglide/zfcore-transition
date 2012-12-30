<?php
/**
 * StripTagsFormatter
 *
 * @category Core
 * @subpackage Grid
 * @package Formatter
 *
 * @author Dmitriy Savchenko <savchenko.d.v@nixsolutions.com>
 * @file StripTagsFormatter.php
 * @date 28.11.12
 */
class Core_Grid_Formatter_StripTagsFormatter
{

    /**
     * @param $value
     *
     * @return string
     */
    public function formatter($value)
    {
        return strip_tags($value);
    }
}
