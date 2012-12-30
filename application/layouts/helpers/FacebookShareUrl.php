<?php
/**
 * @category Application
 * @package  Core_View
 * @subpackage Helper
 */
/**
 * Usage in "dialog" variant:
 * (this variant uses https://www.facebook.com/dialog/feed functionality to post message to user timeline using facebook app)
 * $facebookShareUrl = $this->facebookShareUrl(
 *     array(
 *         'app_id' => APP_ID,
 *         'link' => link you want to share,
 *         'picture' => picture to show in share message,
 *         'name' => share message name,
 *         'caption' => share message caption,
 *         'description' => share message description,
 *         'redirect_uri' => uri ti redirect
 *     ),
 *     'dialog'
 * );
 *
 * usage in "share" variant:
 * (this variant uses https://www.facebook.com/sharer/sharer.php share functionality)
 * $facebookShareUrl = $this->facebookShareUrl(
 *     array(
 *         'url' => link you want to share
 *     )
 * );
 */
class Application_View_Helper_FacebookShareUrl extends Zend_View_Helper_Abstract
{
    const TYPE_SHARE = 'share';
    const TYPE_DIALOG_FEED = 'dialog';

    /**
     * @param array $params
     * @param string $type
     * @return string
     */
    public function facebookShareUrl(array $params, $type = self::TYPE_SHARE)
    {
        $url = '';
        switch ($type) {
            case self::TYPE_DIALOG_FEED:
                $url = "https://www.facebook.com/dialog/feed";
                if (isset($params['app_id'])) {
                    $url .= "?app_id=$params[app_id]";
                    unset($params['app_id']);
                }
                foreach ($params as $key => $value) {
                    if ('link' === $key) {
                        $params['link'] = urlencode($params['link']);
                    }
                    $url .= "&$key=$value";
                }
                break;
            case self::TYPE_SHARE:
                $url = "https://www.facebook.com/sharer/sharer.php";
                if (isset($params['url'])) {
                    if (isset($params['url'])) {
                        $params['encodedUrl'] = urlencode($params['url']);
                    }
                }
                $url .= "?u=$params[encodedUrl]&p[title]=TITLE_HERE";
                break;
        }
        return $url;
    }
}
