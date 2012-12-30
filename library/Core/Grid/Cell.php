<?php
/**
 * Created by Igor Malinovskiy <u.glide@gmail.com>.
 * Cell.php
 * Date: 17.12.12
 */
class Core_Grid_Cell implements ArrayAccess
{
    /**
     * @var Core_Grid_Column
     */
    private $_parentCol;

    /**
     * @var Core_Db_Table_Row_Abstract | array
     */
    private $_dataRow;

    /**
     * @param Core_Grid_Column $parentCol
     * @param                  $dataRow
     */
    public function __construct(Core_Grid_Column $parentCol, $dataRow)
    {
        $this->_parentCol = $parentCol;
        $this->_dataRow = $dataRow;
    }

    /**
     * @return mixed
     */
    public function getRawData()
    {
        if (null != $this->_parentCol->getIndex()) {
            return $this->_dataRow[$this->_parentCol->getIndex()];
        } else {
            return $this->_parentCol->getName(); // todo: refactor this
        }
    }

    /**
     * @return array|Core_Db_Table_Row_Abstract
     */
    public function getDataRow()
    {
        return $this->_dataRow;
    }

    /**
     * @return mixed|string
     * @throws Core_Exception
     */
    public function render()
    {
        $formatters = $this->_parentCol->getFormatters();
        $cellVal = $this->getRawData();

        if (!count($formatters)) {
            return $cellVal;
        }

        foreach ($formatters as $formatter) {
            $function = '';
            if (!is_callable($formatter, null, $function)) {
                throw new Core_Exception('"' . $function . '" is not callable');
            }
            $cellVal = call_user_func(
                $formatter,
                $cellVal,
                $this->_dataRow,
                $this->_parentCol
            );
        }

        return $cellVal;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     *
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset <p>
     *                      An offset to check for.
     * </p>
     *
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     *       The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        try {
            return $this->_dataRow->offsetExists($offset);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     *
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     * </p>
     *
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        try {
            return $this->_dataRow->offsetGet($offset);
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     *
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     * </p>
     * @param mixed $value  <p>
     *                      The value to set.
     * </p>
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        try {
            $this->_dataRow->offsetSet($offset, $value);
        } catch (Exception $e) {
            return;
        }
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     *
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset <p>
     *                      The offset to unset.
     * </p>
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        try {
            $this->_dataRow->offsetUnset($offset);
        } catch (Exception $e) {
            return;
        }
    }


}
