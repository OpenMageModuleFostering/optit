<?php


class Inchoo_Optit_Model_Subscription extends Inchoo_Optit_Model_Abstract
{
    const SUBSCRIPTION_TYPE_KEYWORD = 'keyword';
    const SUBSCRIPTION_TYPE_INTEREST = 'interest';
    const SUBSCRIPTION_TYPE_MEMBER = 'member';

    const SUBSCRIPTION_TYPE_TO_SUBSCRIBE = "0";
    const SUBSCRIPTION_TYPE_SUBSCRIBED = "1";
    const SUBSCRIPTION_TYPE_KEYWORD_SUBSCRIBED = "2";

    protected $_urlKey = 'subscriptions';

    public function _construct()
    {
        $this->_init('optit/subscription');
        parent::_construct();
    }

    /**
     * Get all subscription by keyword or interest.
     *
     * @param string $type
     * @param int $id
     * @param array $params
     * @return mixed
     */
    public function getAllSubscriptions($type, $id, $params = array())
    {
        $response = '';
        if ($type == self::SUBSCRIPTION_TYPE_KEYWORD) {
            $response = $this->getSubscriptionsByKeyword($id, $params);
        } elseif ($type == self::SUBSCRIPTION_TYPE_INTEREST) {
            $response = $this->getSubscriptionsByInterest($id, $params);
        }

        return $response;
    }

    /**
     * Get an individual subscription.
     * Method: GET
     * http://api.optitmobile.com/1/keywords/{keyword_id}/subscriptions/{phone}.{format}
     *
     * @param int $keywordId
     * @param string $phone
     * @return mixed
     * @throws Zend_Http_Client_Exception
     */
    public function getSubscription($keywordId, $phone)
    {
        $uri = $this->_composeUri(
            'keywords/%s/' . $this->_urlKey . '/%s',
            array($keywordId, $phone)
        );

        $response = $this->getClient()->setUri($uri)->request(Zend_Http_Client::GET);
        return $this->decodeAndCheck($response->getBody());
    }

    /**
     * Subscribes a member to a keyword.
     * Parameters:
     *  phone - mobile phone number of the member with country code - 1 for U.S. phone numbers. (Phone or member_id is required)  Example: 12225551212
     *  member_id - the member_id of a member. It is the ID attribute in the Members entity and can be viewed using the Get Member method. (Phone or member_id is required)
     *  interest_id - add this user to one or many interests. For multiple interests, please comma separate the interest_ids. It is the ID attribute in the Interest entity and can be viewed using the Get Interest method.
     *  first_name - first name of the member
     *  last_name - last name of the member
     *  address1 - address line 1 of the member
     *  address2 - address line 2 of the member
     *  city - city of the member
     *  state - state of the member as a two character abbreviation
     *  zip - zip code or postal code of the member
     *  gender - gender of the member. Values: [male, female]
     *  birth_date - birthdate in the format yyyymmdd
     *  email_address - email address of the member
     * Method: POST
     * http://api.optitmobile.com/1/keywords/{keyword_id}/subscriptions.{format}
     *
     * @param int $keywordId
     * @param array $params
     * @return mixed
     * @throws Zend_Http_Client_Exception
     */
    public function subscribeMemberToKeyword($keywordId, $params)
    {
        $uri = $this->_composeUri(
            'keywords/%s/' . $this->_urlKey,
            array($keywordId)
        );

        $response = $this->getClient()->setUri($uri)->setParameterPost($params)->request(Zend_Http_Client::POST);
        return $this->decodeAndCheck($response->getBody());
    }

    /**
     * Add a subscribed member to an interest.
     * Parameters:
     *  phone - mobile phone number of the member with country code - 1 for U.S. phone numbers. (Phone or member_id is required)  Example: 12225551212
     *  member_id - the member_id of a member. It is the ID attribute in the Members entity and can be viewed using the Get Member method. (Phone or member_id is required)
     * Method: POST
     * http://api.optitmobile.com/1/interests/{interest_id}/subscriptions.{format}
     *
     * @param $interestId
     * @param array $params
     * @return mixed
     * @throws Zend_Http_Client_Exception
     */
    public function subscribeMemberToInterest($interestId, $params = array())
    {
        $uri = $this->_composeUri(
            'interests/%s/' . $this->_urlKey,
            array($interestId)
        );

        $response = $this->getClient()->setUri($uri)->setParameterPost($params)->request(Zend_Http_Client::POST);
        return $this->decodeAndCheck($response->getBody());
    }

    /**
     * Unsubscribe a member from all keywords.
     * Method: DELETE
     * http://api.optitmobile.com/1/subscription/{phone}.{format}
     *
     * @param string $phone
     * @return mixed
     * @throws Zend_Http_Client_Exception
     */
    public function unsubscriberMemberFromAllKeywords($phone)
    {
        $uri = $this->_composeUri(
            'subscription/%s',
            array($phone)
        );

        $response = $this->getClient()->setUri($uri)->request(Zend_Http_Client::DELETE);
        return $this->decodeAndCheck($response->getBody());
    }

    /**
     * Unsubscribe a member from one keyword
     * Method: DELETE
     * http://api.optitmobile.com/1/keywords/{keyword_id}/subscription/{phone}.{format}
     *
     * @param int $keywordId
     * @param string $memberPhone
     * @return mixed
     * @throws Zend_Http_Client_Exception
     */
    public function unsubscribeMemberFromKeyword($keywordId, $memberPhone)
    {
        $uri = $this->_composeUri(
            'keywords/%s/subscription/%s',
            array($keywordId, $memberPhone)
        );

        $response = $this->getClient()->setUri($uri)->request(Zend_Http_Client::DELETE);
        return $this->decodeAndCheck($response->getBody());
    }

    /**
     * Delete a subscribed member from an interest.
     * Method: DELETE
     * http://api.optitmobile.com/1/interests/{interest_id}/subscriptions/{phone}.{format}
     *
     * @param int $interestId
     * @param string $memberPhone
     * @return mixed
     * @throws Zend_Http_Client_Exception
     */
    public function unsubscribeMemberFromInterest($interestId, $memberPhone)
    {
        $uri = $this->_composeUri(
            'interests/%s/' . $this->_urlKey . '/%s',
            array($interestId, $memberPhone)
        );

        $response = $this->getClient()->setUri($uri)->request(Zend_Http_Client::DELETE);
        return $this->decodeAndCheck($response->getBody());
    }

    /**
     * Get a list of active subscriptions associated to an interest.
     * Parameters:
     *  phone - mobile phone number of the member with country code - 1 for U.S. phone numbers.  Example: 12225551212
     *  member_id - the member_id of a member. It is the ID attribute in the Members entity and can be viewed using the Get Member method.
     *  first_name - first name of the member
     *  last_name - last name of the member
     *  zip - zip code or postal code of the member
     *  gender - gender of the member. Values: [male, female]
     *  signup_date_start - yyyymmddhhmmss
     *  signup_date_end - yyyymmddhhmmss
     * Method: GET
     * http://api.optitmobile.com/1/interests/{interest_id}/subscriptions.{format}
     *
     * @param int $interestId
     * @param array $params
     * @return mixed
     * @throws Zend_Http_Client_Exception
     */
    public function getSubscriptionsByInterest($interestId, $params = array())
    {
        $uri = $this->_composeUri(
            'interests/%s/' . $this->_urlKey,
            array($interestId)
        );

        $response = $this->getClient()->setUri($uri)->setParameterGet($params)->request(Zend_Http_Client::GET);
        return $this->decodeAndCheck($response->getBody());
    }

    /**
     * Get a list of subscribed members for an individual keyword.
     * Parameters:
     *  phone - mobile phone number of the member with country code - 1 for U.S. phone numbers. Example: 12225551212
     *  member_id - the member_id of a member. It is the ID attribute in the Members entity and can be viewed using the Get Member method.
     *  first_name - first name of the member
     *  last_name - last name of the member
     *  zip - zip code or postal code of the member
     *  gender - gender of the member. Values: [male, female]
     *  signup_date_start - yyyymmddhhmmss
     *  signup_date_end - yyyymmddhhmmss
     * Method: GET
     * http://api.optitmobile.com/1/keywords/{keyword_id}/subscriptions.{format}
     *
     * @param $keywordId
     * @param array $params
     * @return mixed
     * @throws Zend_Http_Client_Exception
     */
    public function getSubscriptionsByKeyword($keywordId, $params = array())
    {
        $uri = $this->_composeUri(
            'keywords/%s/' . $this->_urlKey,
            array($keywordId)
        );

        $response = $this->getClient()->setUri($uri)->setParameterGet($params)->request(Zend_Http_Client::GET);
        return $this->decodeAndCheck($response->getBody());
    }

    public function addToQueue()
    {
        if (!$this->_getSubscriptionFromDB()) {
            $this->_prepareForSave()->save();
        }
        return $this;
    }

    protected function _prepareForSave()
    {
        $quote = $this->getQuote();
        $billingAddress = $quote->getBillingAddress();
        $customerCellPhone = $this->_validateCustomerCellPhone($quote->getCustomerCellphone());

        $this->setData(array(
            'keyword' => $quote->getKeyword(),
            'interest' => $quote->getInterest(),
            'phone' => $customerCellPhone,
            'email_address' => $quote->getCustomerEmail(),
            'first_name' => $quote->getCustomerFirstname(),
            'last_name' => $quote->getCustomerLastname(),
            'gender' => $quote->getCustomerGender(),
            'birth_date' => $quote->getCustomerDob(),
            'address1' => $billingAddress->getStreet1(),
            'address2' => $billingAddress->getStreet2(),
            'city' => $billingAddress->getCity(),
            'state' => $billingAddress->getRegionCode(),
            'zip' => $billingAddress->getPostcode(),
            'status' => self::SUBSCRIPTION_TYPE_TO_SUBSCRIBE,
        ));

        return $this;
    }

    protected function _validateCustomerCellPhone($customerCellphone)
    {
        $error = false;
        if (!Zend_Validate::is($customerCellphone, 'NotEmpty')) {
            $error = true;
        }

        if (!Zend_Validate::is($customerCellphone, 'Digits')) {
            $error = true;
        }

        if ($error) {
            Mage::throwException('Cellphone number not valid');
        }

        return $customerCellphone;
    }

    protected function _getSubscriptionFromDB()
    {
        $interest = $this->getQuote()->getInterest();
        $phone = $this->getQuote()->getCustomerCellphone();
        return $this->getResource()->getSubscriptionByPhoneAndInterest($interest, $phone);
    }
}