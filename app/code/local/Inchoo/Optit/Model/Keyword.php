<?php


class Inchoo_Optit_Model_Keyword extends Inchoo_Optit_Model_Abstract
{
    protected $_urlKey = 'keywords';

    protected $_idFieldName = 'id';

    /**
     * Get the list of keywords.
     * Parameters:
     *  keyword - the name of a keyword
     *  short_code - the 5 or 6 digit short code of a keyword
     *  page - the number of the page result that you are requesting.
     * Method: GET
     * http://api.optitmobile.com/1/keywords.{format}
     *
     * @param array $params
     * @return mixed
     * @throws Zend_Http_Client_Exception
     */
    public function getAllKeywords($params = array())
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
     * Get an individual keyword.
     * Method: GET
     * http://api.optitmobile.com/1/keywords/{keyword_id}.{format}
     *
     * @param int $keywordId
     * @return mixed
     * @throws Zend_Http_Client_Exception
     */
    protected function _getKeyword($keywordId)
    {
        $uri = $this->_composeUri(
            $this->_urlKey . '/%s',
            array($keywordId)
        );

        $response = $this->getClient()->setUri($uri)->request(Zend_Http_Client::GET);
        return $this->decodeAndCheck($response->getBody());
    }

    /**
     * Checks to see if a keyword is already being used on the short code in your account.
     * If it does not already exist, you will be allowed to add it to your account.
     * Parameters:
     *  keyword - the name of a keyword. (Required)
     * Method: GET
     * http://api.optitmobile.com/1/keywords/exists.{format}
     *
     * @param string $keywordName
     * @return mixed
     * @throws Zend_Http_Client_Exception
     */
    public function checkIfExists($keywordName)
    {
        $uri = $this->_composeUri(
            $this->_urlKey . '/%s',
            array('exists')
        );

        $response = $this->getClient()->setUri($uri)->setParameterGet(array('keyword' => $keywordName))->request(Zend_Http_Client::GET);
        $response = $this->decodeAndCheck($response->getBody());
        return $response['keyword']['exists'];
    }

    /**
     * Adds a new keyword for the short code assigned to your account. This will default to a Subscription keyword type
     * and will use the standard Opt It messaging unless other message parameters are included in your POST. Your
     * account will be billed based on your account plan and the billing type you submit.
     * Parameters:
     *  billing_type - the plan type for this keyword. Values: [unlimited, per-message] (Required)
     *  keyword - the name of the keyword. (Required)
     *  internal_name - the internal name of the keyword. This name is only used for reference purposes inside the application. (Required)
     *  interest_id - the interest_id that users wil be added to when they subscribe. It is the ID attribute in the Interest entity and can be viewed using the Get Interest method.
     *  welcome_msg_type - the type of welcome messaage for this keyword. This message is sent after a successful subcription for a member. Defaults to standard. Values: [standard, semi-custom]
     *  welcome_msg - the actual text to be sent in the welcome message. Welcome_msg_type must exist in the POST and be equal to "semi-custom". Max 93 characters.
     *  web_form_verification_msg_type - the type of web form verification messaage for this keyword. This message is sent when someone subscribes using a web form or using the API and asks the member to verify that they would like to subscribe. It prevents someone other than the phone owner from subscribing the member's phone number to a keyword. Defaults to standard. Values: [standard, semi-custom]
     *  web_form_verification_msg - the actual text to be sent in the web form verification message. Web_form_verification_msg_type must exist in the POST and be equal to "semi-custom". Max 120 characters.
     *  already_subscribed_msg_type - the type of already subscribed messaage for this keyword. This message is sent when a member tries to subscribe and they are already subscribed to the keyword. Defaults to standard. Values: [standard, custom, none]
     *  already_subscribed_msg - the actual text to be sent in the already subscribed message. Already_subscribed_msg_type must exist in the POST and be equal to "custom". Max 160 characters.
     * Method: POST
     * http://api.optitmobile.com/1/keywords.{format}
     *
     * @param array $params
     * @return mixed
     * @throws Zend_Http_Client_Exception
     */
    protected function _createKeyword($params)
    {
        $uri = $this->_composeUri(
            $this->_urlKey
        );

        $response = $this->getClient()->setUri($uri)->setParameterPost($params)->request(Zend_Http_Client::POST);
        return $this->decodeAndCheck($response->getBody());
    }

    /**
     *
     * Parameters
     *  billing_type - the plan type for this keyword. Values: [unlimited, per-message] (Required)
     *  internal_name - the internal name of the keyword. This name is only used for reference purposes inside the application. (Required)
     *  interest_id - the interest_id that users wil be added to when they subscribe. It is the ID attribute in the Interest entity and can be viewed using the Get Interest method.
     *  welcome_msg_type - the type of welcome messaage for this keyword. This message is sent after a successful subcription for a member. Defaults to standard. Values: [standard, semi-custom]
     *  welcome_msg - the actual text to be sent in the welcome message. Welcome_msg_type must exist in the POST and be equal to "semi-custom". Max 93 characters.
     *  web_form_verification_msg_type - the type of web form verification messaage for this keyword. This message is sent when someone subscribes using a web form or using the API and asks the member to verify that they would like to subscribe. It prevents someone other than the phone owner from subscribing the member's phone number to a keyword. Defaults to standard. Values: [standard, semi-custom]
     *  web_form_verification_msg - the actual text to be sent in the web form verification message. Web_form_verification_msg_type must exist in the POST and be equal to "semi-custom". Max 120 characters.
     *  already_subscribed_msg_type - the type of already subscribed messaage for this keyword. This message is sent when a member tries to subscribe and they are already subscribed to the keyword. Defaults to standard. Values: [standard, custom, none]
     *  already_subscribed_msg - the actual text to be sent in the already subscribed message. Already_subscribed_msg_type must exist in the POST and be equal to "custom". Max 160 characters.
     * Method: PUT
     * http://api.optitmobile.com/1/keywords/{keyword_id}.{format}
     *
     * @param int $keywordId
     * @param array $params
     * @return mixed
     * @throws Zend_Http_Client_Exception
     */
    public function updateKeyword($keywordId, $params)
    {
        $uri = $this->_composeUri(
            $this->_urlKey .'/%s',
            array($keywordId)
        );

        $response = $this->getClient()->setUri($uri)->setParameterPost($params)->request(Zend_Http_Client::PUT);
        return $this->decodeAndCheck($response->getBody());
    }

    public function loadKeyword($keywordId)
    {
        $response = $this->_getKeyword($keywordId);

        if (isset($response['keyword'])) {
            $this->setData($response['keyword']);
        }

        return $this;
    }

    public function saveKeyword($params)
    {
        if (!isset($params['id']) && !$this->checkIfExists($params['keyword'])) {
            $keyword = $this->_createKeyword($params);
            return $keyword;
        }

        $keyword = $this->updateKeyword($params['id'], $params);
        return $keyword;
    }
}