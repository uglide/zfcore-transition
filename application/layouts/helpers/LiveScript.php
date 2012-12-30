<?php
/**
 * LiveScript.php
 * Simple view helper for adding "live" JS to page (Google Analytics, Yandex Metrica, ect)
 * Created by Igor Malinovskiy <u.glide@gmail.com>.
 * Date: 23.07.12
 */
class Application_View_Helper_LiveScript extends Zend_View_Helper_Abstract
{
    public function liveScript($script, $wrapWithBaseUrl = true)
    {
        if (APPLICATION_ENV == 'production') {
            if ($wrapWithBaseUrl) {
                $this->view->baseUrl($script);
            }
            return $this->view->headScript()->appendFile($script);
        } else {
            return false;
        }
    }
}
