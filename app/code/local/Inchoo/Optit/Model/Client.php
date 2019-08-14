<?php


class Inchoo_Optit_Model_Client extends Zend_Http_Client
{
    public function __construct()
    {
        parent::__construct();
        $this->setBasicAuthentication();
    }

    protected function setBasicAuthentication()
    {
        $username = Mage::getStoreConfig('promo/optit/optit_username');
        $password = Mage::getStoreConfig('promo/optit/optit_password');
        
        $this->setAuth($username, $password);
    }

}