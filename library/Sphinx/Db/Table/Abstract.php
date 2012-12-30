<?php
/**
 * Abstract.php
 * Created by Igor Malinovskiy <u.glide@gmail.com>.
 * Date: 25.06.12
 */
abstract class Sphinx_Db_Table_Abstract extends Core_Db_Table_Abstract
{
    const MIN_QUERY_LENGTH = 2;

    /**
     * Sphinx SE Table
     * @var string
     */
    protected $_sphinxSearchTable;

    /**
     * SPHYNX_MATCH_MODE
     * @var string
     */
    protected $_matchMode = 'extended2';

    /**
     * Ranker of search results
     * @var string
     */
    protected $_ranker = 'sph04';

    /**
     * Indexes for search
     * @var array
     */
    protected $_indexes = array();

    /**
     * @var bool
     */
    protected $_indexesLoaded = false;

    /**
     * @var array
     */
    protected $_weights = array();

    /**
     * @var int variable for paginator
     */
    protected $_maxItemsOnPage = 15;


    /**
     * Escape query
     * @param $string
     * @param bool $remove
     * @return mixed
     */
    protected function _escapeString($string, $remove = false)
    {

        $from = array ( '\\', '(',')','|','-','!','@','~','"','&', '/', '^', '$', '=', "'", "\x00", "\n", "\r", "\x1a" );

        if ($remove) {
            $to = '';
        } else {
            $to   = array ( '\\\\', '\\\(','\\\)','\\\|','\\\-','\\\!','\\\@','\\\~','\\\"', '\\\&', '\\\/', '\\\^', '\\\$', '\\\=', "\\'", "\\x00", "\\n", "\\r", "\\x1a" );
        }

        return str_replace($from, $to, $string);
    }

    /**
     * @param $string
     *
     * @return string
     */
    protected function _replaceWithSpaces($string)
    {
        $result = preg_replace(
            '/[^\w\s\-]/u',
            ' ',
            $string
        );
        $result = trim(
            $result
        );
        return $result;
    }

    /**
     *
     * @param string $query
     * @return string
     */
    protected function _prepareSearchQuery($query)
    {
        $processedParts = array();
        $parts = explode(' ', strtoupper($query));

        $wordCount = 0;

        foreach ($parts as $word) {
            $word = $this->_escapeString(
                trim($word),
                true
            );

            if (!empty($word) && strlen($word) > 1) {
                $processedParts[] .= "(" . $word . " | *" . $word . "*)";
                $wordCount++;
            }
        }

        if ($wordCount > 0 && mb_strlen($query) >= self::MIN_QUERY_LENGTH) {
            return '(' . implode(' && ', $processedParts) . ') | ('
                . $this->_replaceWithSpaces($query) . ') | (*'
                . $this->_replaceWithSpaces($query) . '*) | ('
                . $this->_escapeString($query) . ');';
        } else {
            return false;
        }
    }

    /**
     * @throws Core_Exception
     */
    protected function _loadIndexes()
    {
        if (!$this->_indexesLoaded) {
            $config = Zend_Registry::get('sphinxIndexes');
            try {
                $reflClss = new ReflectionClass($this);
                $currentClassName = $reflClss->name;
                $indexes = $config[$currentClassName];
                if (is_array($indexes)) {
                    $this->_indexes = $indexes;
                    $this->_indexesLoaded = true;
                }
            } catch (Exception $ex) {
                throw new Core_Exception("Error ocurred on loading indexes from config");
            }
        }
    }

    /**
     * @return string
     */
    protected function _getSphinxQueryParameters()
    {
        $paramStr = 'mode=' . $this->_matchMode . ';' . 'ranker=' . $this->_ranker . ';';

        if (!empty($this->_indexes)) {
            $paramStr .= 'index=' . implode(',', $this->_indexes) . ';';
        }

        if (!empty($this->_weights)) {
            $paramStr .= 'weights=' . implode(',', $this->_weights) . ';';
        }

        return $paramStr;
    }

    /**
     * @param $baseSelect
     * @param int $page
     * @param null $countSelect
     * @return Zend_Paginator
     */
    protected function _paginator($baseSelect, $page = 0, $countSelect = null)
    {
        $adapter = new Zend_Paginator_Adapter_DbTableSelect($baseSelect);

        if (null != $countSelect) {
            $adapter->setRowCount($countSelect);
        }

        $paginator = new Zend_Paginator($adapter);
        $paginator->setDefaultItemCountPerPage($this->_maxItemsOnPage);

        if (!empty($page)) {
            $paginator->setCurrentPageNumber($page);
        }
        return $paginator;
    }

    /**
     * Set max items on page for paginator
     * @param $max
     */
    public function setMaxItemsOnPage($max)
    {
        if (!empty($max) && $max > 0) {
            $this->_maxItemsOnPage = $max;
        }
    }
}
