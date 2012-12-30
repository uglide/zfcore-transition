<?php
/**
 * Edit any user form
 *
 * User in All users form
 *
 * @category Application
 * @package Model
 * @subpackage Form
 */
class Application_Form_Filters extends Core_Form
{
    /**
     * @var null
     */
    protected $_values = null;

    /**
     * @param null $options
     * @param null $values
     */
    public function __construct($options = null, $values = null)
    {
        $this->setValues($values);
        parent::__construct($options);
        return $this;
    }

    /**
     * @return Application_Form_Filters|void
     */
    public function init()
    {
        parent::init();

        $this->setName('filter-form')
            ->setAttrib('id', 'filter-form')
            ->setMethod('post')
            ->setAttrib('class', 'span9');

        $this->setDecorators(array('FormElements', 'Form'));


        $this->addElement($this->_text2());

        $this->addDisplayGroup(
            array(
                $this->_text(),
                $this->_filterColumn($this->getValues())
            ),
            'filters-group'
        );
        $this->addDisplayGroup(
            array(
                $this->_filterValue(),
                $this->_find(),
                $this->_reset()
            ),
            'filter-select-group'
        );
        $this->getDisplayGroup('filters-group')->setDecorators(
            array(
                'FormElements',
                array(
                    'HtmlTag',
                    array('tag' => 'div', 'class' => 'input-prepend')
                )
            )
        );
        $this->getDisplayGroup('filter-select-group')->setDecorators(
            array(
                'FormElements',
                array(
                    'HtmlTag',
                    array('tag' => 'div', 'class' => 'input-append')
                )
            )
        );
        return $this;
    }

    /**
     * @return Core_Form_Element_PlainText
     */
    protected function _text()
    {
        $element = new Core_Form_Element_PlainText('text');
        $element->setLabel(null)
            ->setDecorators(array('ViewHelper'))
            ->setAttrib('class', 'add-on')
            ->setValue('<span class="add-on">in</span>');
        return $element;
    }

    /**
     * @return Core_Form_Element_PlainText
     */
    protected function _text2()
    {
        $element = new Core_Form_Element_PlainText('text2');
        $element->setLabel(null)
            ->setDecorators(array('ViewHelper'))
            ->setValue(
                '<label for="filter-column" class="span1">Search:</label>'
            );
        return $element;
    }

    /**
     * @param $values
     *
     * @return Zend_Form_Element_Select
     */
    protected function _filterColumn($values)
    {
        $element = new Zend_Form_Element_Select('filter_column');
        $element->setLabel(null)
            ->setDecorators(array('ViewHelper'))
            ->setAttribs(
                array(
                    'id' => 'filter-column',
                    'class' => 'span3',
                    'placeholder' => 'Enter search query here'
                )
            );
        foreach ($values as $id => $name) {
            $element->addMultiOption($id, $name);
        }

        return $element;
    }

    /**
     * @return Zend_Form_Element_Text
     */
    protected function _filterValue()
    {
        $element = new Zend_Form_Element_Text('filter_value');

        $urlHelper = Zend_Controller_Front::getInstance()
            ->getParam('bootstrap')
            ->getResource('view')
            ->getHelper('url');

        $element->setLabel(null)
            ->setDecorators(array('ViewHelper'))
            ->setAttribs(
                array(
                    'id' => 'filter-value',
                    'class' => 'span3',
                    'data-autocomplete' => $urlHelper->url(
                        array('action' => 'autocomplete'),
                        'default'
                    ) . "/",
                    'data-entityEdit' =>$urlHelper->url(
                        array('action' => 'edit'),
                        'default'
                    )
                )
            );

        return $element;
    }

    /**
     * @return Zend_Form_Element_Button
     */
    protected function _find()
    {
        $element = new Zend_Form_Element_Button('filter_button');
        $element->setLabel('Find')
            ->setDecorators(array('ViewHelper'))
            ->setAttribs(
                array(
                    'id' => 'filter-button',
                    'class' => 'btn'
                )
            );
        return $element;
    }

    /**
     * @return Zend_Form_Element_Button
     */
    protected function _reset()
    {
        $element = new Zend_Form_Element_Button('filter_reset');
        $element->setLabel('Reset')
            ->setDecorators(array('ViewHelper'))
            ->setAttribs(
                array(
                    'id' => 'filter-reset',
                    'class' => 'btn'
                )
            );
        return $element;
    }

    public function prepare(array $elements)
    {
        foreach ($elements as $elementName => $attributes) {
            $element = $this->getElement($elementName);
            $element->setAttribs($attributes);
        }
    }

    /**
     * @param bool $suppressArrayNotation
     * @return array|null
     */
    public function getValues($suppressArrayNotation = false)
    {
        return $this->_values;
    }

    /**
     * @param $values
     *
     * @return Application_Form_Filters
     */
    public function setValues($values)
    {
        $this->_values = $values;
        return $this;
    }
}