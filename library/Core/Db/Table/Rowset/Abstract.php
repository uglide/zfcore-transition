<?php
/**
 * Created by Igor Malinovskiy <u.glide@gmail.com>.
 * Abstract.php
 * Date: 21.11.12
 */
class Core_Db_Table_Rowset_Abstract extends Zend_Db_Table_Rowset_Abstract
{

    public function getIdentityRange($primaryKey = null)
    {
        $ids = array();

        if (null == $primaryKey) {
            $primaryKey = $this->_table->info('primary');

            if (count($primaryKey) != 1) {
                throw new Zend_Db_Exception(
                    "Can't get range of composite primary key"
                );
            } else {
                $primaryKey = array_shift($primaryKey);
            }
        }

        /**
         * @var $row Zend_Db_Table_Row_Abstract
         */
        foreach ($this as $row) {
            $ids[] = $row[$primaryKey];
        }

        return $ids;
    }

    /**
     * @param $accessKey
     * @return Core_Db_Table_Row_Abstract []
     */
    public function toQuickAccessArray($accessKey)
    {
        $quickAccessArray = array();

        foreach ($this as $row) {
            $quickAccessArray[$row[$accessKey]][] = $row;
        }

        return $quickAccessArray;
    }
}
