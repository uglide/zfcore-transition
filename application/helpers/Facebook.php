<?php
/**
 * Helper_Facebook
 */
class Helper_Facebook extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * @var Facebook_Facebook
     */
    protected $_client;

    const FB_APP_URL = "http://apps.facebook.com/";

    /**
     * Init fb client
     * @return \Facebook_Facebook
     */
    public function direct()
    {
        return $this->getClient();
    }

    /**
     * Get fb client
     *
     * @throws Zend_Controller_Action_Exception
     * @return Facebook_Facebook
     */
    public function getClient()
    {
        if (!$this->_client) {
            if (!Zend_Registry::isRegistered('fbConfig')) {
                throw new Zend_Controller_Action_Exception(
                    'Facebook Connect: config not found'
                );
            }
            $config = Zend_Registry::get('fbConfig');

            if (empty($config['appId'])) {
                throw new Zend_Controller_Action_Exception(
                    'Facebook Connect: application Id is missed'
                );
            }

            $this->setClient($client = new Facebook($config));
        }

        return $this->_client;
    }

    /**
     * Set client
     *
     * @param Facebook_Facebook $client
     * @return Helper_Facebook
     */
    public function setClient($client)
    {
        $this->_client = $client;

        return $this;
    }

    /**
     * Get info
     *
     * @return ArrayObject
     * @throws Core_Exception
     */
    public function getInfo()
    {
        $info = new ArrayObject(array(), ArrayObject::ARRAY_AS_PROPS);

        // Get User ID
        $user = $this->getClient()->getUser();

        // Make API calls:
        if ($user) {
            try {
                // Proceed knowing you have a logged in user who's authenticated.
                $userInfo = $this->getClient()->api('/me');

                $info->login = $userInfo['username'];
                $info->email = $userInfo['email'];
                $info->firstname = $userInfo['first_name'];
                $info->lastname = $userInfo['last_name'];
                $info->facebookId = $userInfo['id'];
            } catch (FacebookApiException $e) {
                $user = null;
                throw new Core_Exception(
                    'Problem with getting info from Facebook',
                    $e->getMessage()
                );
            }
        }

        return $info;
    }
}