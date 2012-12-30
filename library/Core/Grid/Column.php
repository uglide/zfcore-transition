<?php
/**
 * Created by Igor Malinovskiy <u.glide@gmail.com>.
 * Column.php
 * Date: 17.12.12
 */
class Core_Grid_Column
{
    /**
     * types of cols
     */
    const TYPE_DATA = 'data';  // normal visible column
    const TYPE_EMPTY = 'empty'; // visible column without data (with button, link ect)
    const TYPE_FILTER = 'filter'; // not visible column for filtering
    const TYPE_ATTRIBUTE = 'attribute'; // renders as data attr of <tr>

    /**
     * Assigned formatters to current column
     * @var array
     */
    private $_formatters = array();

    /**
     * Index in Row with data
     * @var
     */
    private $_index;

    /**
     * Label showed in header of table
     * (if type of field - data)
     * @var string
     */
    private $_name;

    /**
     * @var
     */
    private $_type = self::TYPE_EMPTY;

    /**
     * Var used for determine is grid ordered by this column
     * @var string
     */
    private $_order = '';

    /**
     * @var array
     */
    private $_attribs = array();

    /**
     * @param array $options
     * @throws InvalidArgumentException
     * @throws Core_Exception
     */
    public function __construct(array $options)
    {
        if (!isset($options['name'])
        ) {
            throw new InvalidArgumentException(
                "index, name - required options "
            );
        }

        $this->_name = $options['name'];

        if (isset($options['type'])
            && $this->_isAllowedType($options['type'])
        ) {
            $this->_type = $options['type'];
        } elseif (isset($options['type'])
            && !$this->_isAllowedType($options['type'])
        ) {
            throw new Core_Exception("Not allowed type");
        }

        if (isset($options['index'])) {
            $this->_index = $options['index'];
        }

        if (isset($options['attribs'])) {
            $this->_attribs = $options['attribs'];
        }

        if (isset($options['formatter'])) {
            if (isset($options['formatter'][0])
                && !is_array($options['formatter'][0])
            ) {
                if (is_array($options['formatter'][1])) {
                    $formatters = array();

                    foreach ($options['formatter'][1] as $formatter) {
                        $formatters[] = array(
                            $options['formatter'][0],
                            $formatter
                        );
                    }
                    $options['formatter'] = $formatters;
                } else {
                    $options['formatter'] = array($options['formatter']);
                }
            }
            $this->_formatters = $options['formatter'];
        }
    }

    /**
     * @return mixed
     */
    public function getIndex()
    {
        return $this->_index;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @return array
     */
    public function getAttribs()
    {
        return $this->_attribs;
    }

    /**
     * @return bool
     */
    public function isOrdered()
    {
        return !empty($this->_order);
    }

    /**
     * @param $direction
     */
    public function setOrder($direction)
    {
        $this->_order = $direction;
    }

    /**
     * @return string
     */
    public function getOrder()
    {
        return $this->_order;
    }

    /**
     * @return array
     */
    public function getFormatters()
    {
        return $this->_formatters;
    }

    /**
     * Simple cell creator
     * @param $row
     *
     * @return Core_Grid_Cell
     */
    public function createCell($row)
    {
        return new Core_Grid_Cell($this, $row);
    }

    /**
     * @return bool
     */
    public function shouldBeShowedInHeader()
    {
        return in_array(
            $this->_type,
            array(
                 self::TYPE_DATA,
                 self::TYPE_EMPTY
            )
        );
    }

    /**
     * @return bool
     */
    public function isSortable()
    {
        return in_array(
            $this->_type,
            array(
                 self::TYPE_DATA
            )
        );
    }

    /**
     * @return bool
     */
    public function isAttributeCol()
    {
        return $this->_type === self::TYPE_ATTRIBUTE;
    }

    /**
     * @return bool
     */
    public function isEmptyCol()
    {
        return $this->_type === self::TYPE_EMPTY;
    }

    /**
     * @return bool
     */
    public function shouldBeShowedInBody()
    {
        return in_array(
            $this->_type,
            array(
                 self::TYPE_DATA,
                 self::TYPE_EMPTY
            )
        );
    }

    /**
     * @param $type
     *
     * @return bool
     */
    private function _isAllowedType($type)
    {
        return in_array(
            $type,
            array(
                 self::TYPE_ATTRIBUTE,
                 self::TYPE_FILTER,
                 self::TYPE_DATA,
                 self::TYPE_EMPTY
            )
        );
    }
}
