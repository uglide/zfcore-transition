<?php
/**
 * FileView.php
 * Created by Igor Malinovskiy <u.glide@gmail.com>.
 * Date: 19.07.12
 */
class Mail_Model_Templates_FileView extends Core_Mail_Templates_Abstract
{
    /**
     * @var Zend_View
     */
    private $_view;

    /**
     * @var string
     */
    private $_viewScriptName;

    /**
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        $this->_view = new Zend_View();

        if (isset($data['name'])) {
            $this->_viewScriptName = $data['name'];
        } else {
            throw new InvalidArgumentException('Script name not setted!');
        }

        if (isset($data['basePath'])) {
            $this->_view->setBasePath($data['basePath']);
        }

        if (isset($data['scriptPath'])) {
            $this->_view->setScriptPath($data['scriptPath']);
        }

        parent::__construct($data);
    }

    /**
     * Send email
     *
     * @param Zend_Mail $mail
     * @return Zend_Mail
     */
    public function send(Zend_Mail $mail = null)
    {
        if ($mail) {
            $mail = clone $mail;
        } else {
            $mail = new Zend_Mail();
        }

        $this->bodyHtml = $this->_view->render($this->_viewScriptName);

        $mail = $this->populate($mail);

        return $mail->send();
    }


    /**
     * @param $name
     * @param $value
     */
    public function assign($name, $value)
    {
        $this->_view->assign($name, $value);
    }


}
