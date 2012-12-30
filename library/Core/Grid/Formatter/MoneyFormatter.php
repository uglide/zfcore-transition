<?php
/**
 * MoneyFormatter
 *
 * @category Core
 * @subpackage Grid
 * @package Formatter
 *
 * @author Dmitriy Savchenko <savchenko.d.v@nixsolutions.com>
 * @file MoneyFormatter.php
 * @date 28.11.12
 */
class Core_Grid_Formatter_MoneyFormatter
{

    /**
     * @param $value
     *
     * @return string
     */
    public function formatter($value)
    {
        return '<span class="nowrap">'
            . $this->view->moneyFormat($value) . '</span>';
    }
}
