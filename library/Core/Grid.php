<?php
/**
 * Copyright (c) 2012 by PHP Team of NIX Solutions Ltd
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * Grid
 *
 * @category Core
 * @package  Core_Grid
 */
class Core_Grid extends Core_Grid_Abstract
{
    /**
     * paginator
     *
     * @var Zend_Paginator
     */
    protected $_paginator = null;

    /**
     * get headers
     *
     * @throws Core_Exception
     * @return array
     */
    public function getHeaders()
    {
        if ($this->_headers === null) {

            /** init paginator if it wasn't initialized */
            $this->getPaginator();

            $this->_headers = array();

            /**
             * @var $column Core_Grid_Column
             */
            foreach ($this->_columns as $columnId => $column) {
                if ($column->shouldBeShowedInHeader()) {
                    $this->_headers[$columnId] = $column;
                }
            }
        }

        return $this->_headers;
    }

    /**
     * get data
     *
     * @throws Core_Exception
     * @return array
     */
    public function getData()
    {
        if ($this->_data === null) {
            $items = $this->getPaginator()->getCurrentItems();

            $this->_data = array();
            foreach ($items as $dataRow) {

                $row = new Core_Grid_Row();

                /**
                 * @var $column Core_Grid_Column
                 */
                foreach ($this->_columns as $id => $column) {
                    if ($column->shouldBeShowedInBody()) {
                        $row[$id] = $column->createCell($dataRow);
                    } elseif ($column->isAttributeCol()) {
                        $row->addAttributes(
                            $id, $column->createCell($dataRow)->getRawData()
                        );
                    }
                }
                $this->_data[] = $row;
            }
        }

        return $this->_data;
    }

    /**
     * get paginator
     *
     * @return Zend_Paginator
     */
    public function getPaginator()
    {
        if (empty($this->_paginator)) {
            $this->_buildPaginator();
        }

        return $this->_paginator;
    }

    /**
     * build paginator
     *
     * @throws Core_Exception
     * @return void
     */
    protected function _buildPaginator()
    {
        if (!$this->_adapter instanceof Core_Grid_Adapter_AdapterInterface) {
            throw new Core_Exception('Adapter is not set');
        }

        if (empty($this->_columns)) {
            throw new Core_Exception('There are no any columns');
        }

        /** default ordering */
        if (empty($this->_orders)) {
            if ($this->_defaultOrders) {
                $this->_orders = $this->_defaultOrders;
            } else {
                /** order by first column if default order isn't set */

                /**
                 * @var $column Core_Grid_Column
                 */
                foreach ($this->_columns as $key => $column) {
                    if ($column->isSortable()) {
                        $this->setOrder($key);
                        break;
                    }
                }
            }
        }

        /** ordering */
        foreach ($this->_orders as $columnId => $direction) {
            $this->_adapter->order($this->_getColumnIndex($columnId), $direction);
            $this->_columns[$columnId]->setOrder($direction);
        }

        /** filtering */
        if ($this->_filters) {
            foreach ($this->_filters as $columnId => $filters) {
                $index = $this->_getColumnIndex($columnId);
                foreach ($filters as $filter) {
                    $this->_adapter->filter($index, $filter);
                }
            }
        }

        if (empty($this->_itemCountPerPage)) {
            throw new Core_Exception('Item count per page is not set');
        }

        if (empty($this->_currentPageNumber)) {
            throw new Core_Exception('Current page is not set');
        }

        $this->_paginator = Zend_Paginator::factory($this->_adapter->getSource())
            ->setItemCountPerPage($this->_itemCountPerPage)
            ->setCurrentPageNumber($this->_currentPageNumber);
    }

    /**
     * get column
     *
     * @throws Core_Exception
     * @param $columnId
     * @return Core_Grid_Column
     */
    protected function _getColumn($columnId)
    {
        if (empty($this->_columns[$columnId])) {
            throw new Core_Exception(
                'Column "' . $columnId . '" does not exist'
            );
        }

        return $this->_columns[$columnId];
    }

    /**
     * get column index
     *
     * @throws Core_Exception
     * @param $columnId
     * @return string
     */
    protected function _getColumnIndex($columnId)
    {
        $column = $this->_getColumn($columnId);

        if (!$column->getIndex()) {
            throw new Core_Exception('Index of column "' . $columnId . '" does not exist');
        }

        return $column->getIndex();
    }
}
