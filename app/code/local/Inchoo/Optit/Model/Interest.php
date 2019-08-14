<?php


class Inchoo_Optit_Model_Interest extends Inchoo_Optit_Model_Abstract
{
    protected $_urlKey = 'interests';


    /**
     * Get a list of interests for an individual keyword.
     * Parameters:
     *  name - name of the interest
     *  page - the number of the page result that you are requesting.
     * Method: GET
     * http://api.optitmobile.com/1/keywords/{keyword_id}/interests.{format}
     *
     * @param int $keywordId
     * @param array $params
     * @return Zend_Http_Response
     * @throws Zend_Http_Client_Exception
     */
    public function getAllInterests($keywordId, $params = array())
    {
        $uri = $this->_composeUri(
            'keywords/%s/' . $this->_urlKey,
            array($keywordId)
        );

        if (!empty($params)) {
            $this->getClient()->setParameterGet($params);
        }

        $response = $this->getClient()->setUri($uri)->request(Zend_Http_Client::GET);
        return $this->decodeAndCheck($response->getBody());
    }

    /**
     * Get a list of interests for an individual keyword and subscribers phone number.
     * Parameters:
     *  page - the number of the page result that you are requesting.
     * Method: GET
     * http://api.optitmobile.com/1/keywords/{keyword_id}/subscriptions/{phone}/interests.{format}
     *
     * @param int $keywordId
     * @param string $phone
     * @param int $page
     * @return Zend_Http_Response
     * @throws Zend_Http_Client_Exception
     */
    public function getInterestByPhoneNumber($keywordId,  $phone, $page = 1)
    {
        $uri = $this->_composeUri(
            'keywords/%s/subscriptions/%s/' . $this->_urlKey,
            array($keywordId, $phone)
        );

        $response = $this->getClient()->setUri($uri)->setParameterGet($page)->request(Zend_Http_Client::GET);
        return $this->decodeAndCheck($response->getBody());
    }

    /**
     * Get an individual interest for a keyword.
     * Method: GET
     * http://api.optitmobile.com/1/interests/{interest_id}.{format}
     *
     * @param int $interestId
     * @return Zend_Http_Response
     */
    public function getInterest($interestId)
    {
        $uri = $this->_composeUri(
            $this->_urlKey . '/%s',
            array($interestId)
        );

        $response = $this->getClient()->setUri($uri)->request(Zend_Http_Client::GET);
        return $this->decodeAndCheck($response->getBody());
    }

    /**
     * Create a new interest associated to a keyword.
     * Parameters:
     *  name - name of the interest. (Required)
     *  description - description of the interest
     * Method: POST
     * http://api.optitmobile.com/1/keywords/{keyword_id}/interests.{format}
     * 
     * @param int $keywordId
     * @param array $params
     * @return Zend_Http_Response
     * @throws Zend_Http_Client_Exception
     */
    public function createInterest($keywordId, $params = array())
    {
        $uri = $this->_composeUri(
            'keywords/%s/' . $this->_urlKey,
            array($keywordId)
        );

        $response = $this->getClient()->setUri($uri)->setParameterPost($params)->request(Zend_Http_Client::POST);
        return $this->decodeAndCheck($response->getBody());
    }

    
}