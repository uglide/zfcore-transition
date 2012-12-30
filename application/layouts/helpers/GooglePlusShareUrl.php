<?php
/**
 * @category Application
 * @package  Core_View
 * @subpackage Helper
 */
class Application_View_Helper_GooglePlusShareUrl extends Zend_View_Helper_Abstract
{
    /**
     * @param $params
     * @return mixed|null
     */
    public function googlePlusShareUrl(array $params)
    {
        $url = "https://plus.google.com/share";
        if (isset($params['url'])) {
            if (isset($params['url'])) {
                $params['encodedUrl'] = urlencode($params['url']);
            }
        }
        $url .= "?url=$params[encodedUrl]";
        return $url;
    }
}
