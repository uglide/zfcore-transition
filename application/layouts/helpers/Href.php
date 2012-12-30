<?php
/**
 * For displaying formatted datetime
 *
 */
class Application_View_Helper_Href extends Zend_View_Helper_Abstract
{
    /**
     * @param       $text
     * @param       $href
     * @param array $attributes
     *
     * @return string
     */
    public function href($text, $href, array $attributes = [])
    {
        /** @var Core_View $this */
        // if href is settings for url helper
        if (is_array($href)) {
            $href = call_user_func_array(array($this->view, 'url'), $href);
        }

        // href can be null, if access is denied
        if (null === $href) {
            $href = '';
        } else {
            $href = 'href="' . $href . '" ';
        }

        $attrs = [];

        foreach ($attributes as $attr => $value) {
            $attrs[] = $attr . '="' . $value . '"';
        }

        return '<a ' . $href . join(' ', $attrs) . '>' . $text . '</a>';
    }
}