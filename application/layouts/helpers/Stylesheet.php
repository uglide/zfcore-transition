<?php
/**
 * @category Application
 * @package  Core_View
 * @subpackage Helper
 *
 * @version  $Id$
 */
class Application_View_Helper_Stylesheet extends Zend_View_Helper_Abstract
{
    /**
     * Generates a html code for include stylesheet
     *
     * @access public
     * @return Application_View_Helper_Stylesheet
     */
    public function stylesheet()
    {
        return $this;
    }

    /**
     * __call
     *
     * append stylesheet by module/controller/action
     *
     * @param   string $method
     * @param   mixed  $args
     * @return  rettype  return
     */
    public function __call($method, $args)
    {
        $request = Zend_Controller_Front::getInstance()->getRequest();

        /* @var $request Zend_Controller_Request_Abstract */
        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();

        // switch statement for $method
        switch ($method) {
            case 'module':
                $style = $this->view->baseUrl("modules/$module/css/style.css");
                break;
            case 'controller':
                $style = $this->view->baseUrl("modules/$module/css/$controller.css");
                break;
            case 'action':
                $style = $this->view->baseUrl("modules/$module/css/$controller/$action.css");
                break;
            case 'external':
                $style = $this->view->baseUrl($args[0]);
                break;
            default:
                break;
        }

        $updateSuffix = '';

        if (strtolower(APPLICATION_ENV) == 'production') {

            $cache = Zend_Registry::get('cache');

            if (($updateSuffix = $cache->load('css_version')) === false) {
                $updateSuffix = '?v=' . time();
                $cache->save($updateSuffix);
            }
        } else {
            $updateSuffix = '?v=' . time();
        }

        /** production enveriment? */
        if (strtolower(APPLICATION_ENV) == 'production') {
            switch ($method) {
                case 'module':
                    $styleMin = $this->view->baseUrl("modules/$module/css/style.min.css");
                    break;
                case 'controller':
                    $styleMin = $this->view->baseUrl("modules/$module/css/$controller.min.css");
                    break;
                case 'action':
                    $styleMin = $this->view->baseUrl("modules/$module/css/$controller/$action.min.css");
                    break;
                case 'external':
                    $styleMin = $style;
                default:
                    break;
            }

            /** min exists? */
            if (file_exists(realpath(APPLICATION_PATH . '/../public' . $styleMin))) {
                $this->view->headLink()->prependStylesheet($styleMin . $updateSuffix);
            } else {
                $this->view->headLink()->prependStylesheet($style . $updateSuffix);
            }
        } else {
            $this->view->headLink()->prependStylesheet($style . $updateSuffix);
        }
    }
}
