<?php
/**
 * Created by Igor Malinovskiy <u.glide@gmail.com>.
 * Breadcrumbs.php
 * Date: 25.12.12
 */
class Application_View_Helper_AppBreadcrumbs extends Zend_View_Helper_Abstract
{
    public function appBreadcrumbs()
    {
        $path = $this->view->getScriptPaths();

        $renderedBreadcrumbs = $this->view->partial(
            'partial/breadcrumbs-container.phtml',
            array(
                 'currentBreadcrumb' => $this->view->currentBreadcrumb,
                 'currentBreadcrumbParentName' => $this->view->currentBreadcrumbParentName
            )
        );

        $this->view->setScriptPath($path);

        return $renderedBreadcrumbs;
    }
}
