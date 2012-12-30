<?php
/**
 * @category Application
 * @package  Core_View
 * @subpackage Helper
 */
class Application_View_Helper_TwitterShareUrl extends Zend_View_Helper_Abstract
{
    /**
     * @param $params
     * @return mixed|null
     */
    public function twitterShareUrl(array $params)
    {
        $url = "https://twitter.com/intent/tweet";
        if (isset($params['url'])) {
            $params['encodedUrl'] = urlencode($params['url']);
        }
        if (isset($params['hashtags'])) {
            $params['joinedHashtags'] = join(',', $params['hashtags']);
        }
        $url .= "?text=$params[text]&url=$params[encodedUrl]&hashtags=$params[joinedHashtags]";
        return $url;
    }
}
