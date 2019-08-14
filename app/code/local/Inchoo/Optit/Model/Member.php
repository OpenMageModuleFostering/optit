<?php


class Inchoo_Optit_Model_Member extends Inchoo_Optit_Model_Abstract
{
    protected $_urlKey = 'members';


    /**
     * Get a list of members information.
     * Parameters:
     * phone - mobile phone number of the member with country code - 1 for U.S. phone numbers. Example: 12225551212
     *  first_name - first name of the member
     *  last_name - last name of the member
     *  zip - zip code or postal code of the member
     *  gender - gender of the member (male or female)
     * Method: GET
     * http://api.optitmobile.com/1/members.{format}
     *
     * @param array $params
     * @return Zend_Http_Response
     */
    public function getAllMembers($params = array())
    {
        $uri = $this->_composeUri(
            $this->_urlKey
        );

        if (!empty($params)) {
            $this->getClient()->setParameterGet($params);
        }

        $response = $this->getClient()->setUri($uri)->request(Zend_Http_Client::GET);
        return $this->decodeAndCheck($response->getBody());
    }

    /**
     * Get an individual members information.
     * Method: GET
     * http://api.optitmobile.com/1/members/{phone}.{format}
     * 
     * @param string $phone
     * @return Zend_Http_Response
     */
    public function getMember($phone)
    {
        $uri = $this->_composeUri(
            $this->_urlKey . '/%s',
            array($phone)
        );

        $response = $this->getClient()->setUri($uri)->request(Zend_Http_Client::GET);
        return $this->decodeAndCheck($response->getBody());
    }

}