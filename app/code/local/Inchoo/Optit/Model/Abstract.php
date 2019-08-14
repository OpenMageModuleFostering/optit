<?php


abstract class Inchoo_Optit_Model_Abstract extends Mage_Core_Model_Abstract
{
    const FORMAT_JSON = 'json';

    const FORMAT_XML = 'xml';

    protected $_format;
    
    protected $_defaultGatewayUrl = 'http://api.optitmobile.com/1/';

    protected $_client;

    public function _construct()
    {
        $this->_format = self::FORMAT_JSON;
    }

    public function getClient()
    {
        if (is_null($this->_client)) {
            $this->_client = Mage::getModel('optit/client');
        }

        return $this->_client;
    }

    public function decodeAndCheck($data, $encoding = self::FORMAT_JSON)
    {
        if ($encoding == self::FORMAT_JSON) {
            $data = Mage::helper('core')->jsonDecode($data);
        } elseif ($encoding == self::FORMAT_XML) {
            // TODO: simplexml
        }

        if (isset($data['Error'])) {
            Mage::throwException($data['Error']['Message']);
        }

        return $data;
    }

    protected function _composeUri($path, $params = array())
    {
        $path .= '.%s';
        $params[] = $this->_format;
        return $this->_defaultGatewayUrl . vsprintf($path, $params);
    }

    public function checkStatus($status)
    {
        if ($status != 200) {
            Mage::throwException('Something went wrong!');
        };
        
    }
}