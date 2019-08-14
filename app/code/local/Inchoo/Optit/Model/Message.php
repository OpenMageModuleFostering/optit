<?php


class Inchoo_Optit_Model_Message extends Inchoo_Optit_Model_Abstract
{
    const MESSAGE_TYPE_SMS = 'SMS';
    const MESSAGE_TYPE_MMS = 'MMS';

    const MMS_MEDIA_DIRECTORY = 'optit';

    protected $_urlKey = 'sendmessage';


    public function _construct()
    {
        $this->_init('optit/message');
        parent::_construct();
    }

    /**
     * Send a text message to one or many members.
     * Parameters:
     *  phone - mobile phone number of the member with country code - 1 for U.S. phone numbers. Comma separate for more than one phone number. (Required)  Example: 12225551212,12225551211
     *  keyword_id - the keyword_id of the keyword the message is coming from and the member is subscribed to. It is the ID attribute in the Keyword entity and can be viewed using the Get Keyword method. (Required)
     *  title - the title of the message. This does not appear in the text message and is just used in the application as a short description of your message. (Required)
     *  message - the message being sent to the member. The message must be less than 160 characters including your keyword in the beginning of the message. (Required)
     * Method: POST
     * http://api.optitmobile.com/1/sendmessage.{format}
     *
     * @param array $params
     * @return mixed
     * @throws Zend_Http_Client_Exception
     */
    public function sendMessageToMember($params)
    {
        $uri = $this->_composeUri(
            $this->_urlKey
        );

        $response = $this->getClient()->setUri($uri)->setParameterPost($params)->request(Zend_Http_Client::POST);
        return $this->decodeAndCheck($response->getBody());
    }

    /**
     * Send a text message to the subscribed members of one or more keywords. If a member is in multiple keywords,
     * they will only receive the message once.
     * Parameters:
     *  keyword_id - the keyword_id of the keyword the message is coming from and the member is subscribed to. It is the ID attribute in the Keyword entity and can be viewed using the Get Keyword method. (Required)
     *  title - the title of the message. This does not appear in the text message and is just used in the application as a short description of your message. (Required)
     *  message - the message being sent to the member. The message must be less than 160 characters including your keyword in the beginning of the message. (Required)
     * Method: POST
     * http://api.optitmobile.com/1/sendmessage/keywords.{format}
     *
     * @param $params
     * @return mixed
     * @throws Zend_Http_Client_Exception
     */
    public function sendMessageToKeyword($params)
    {
        $uri = $this->_composeUri(
            $this->_urlKey . '/keywords'
        );

        $response = $this->getClient()->setUri($uri)->setParameterPost($params)->request(Zend_Http_Client::POST);
        return $this->decodeAndCheck($response->getBody());
    }

    /**
     * Send a text message to the subscribed members of one or more interests. If a member is in multiple interests,
     * they will only receive the message once. Not all interests have to be associated to the same keyword.
     * Parameters:
     *  interest_id - the interest_id of the interest the members are subscribed. It is the ID attribute in the Interest entity and can be viewed using the Get Interest method. (Required)
     *  title - the title of the message. This does not appear in the text message and is just used in the application as a short description of your message. (Required)
     *  message - the message being sent to the member. The message must be less than 160 characters including your keyword in the beginning of the message. (Required)
     * Method: POST
     * http://api.optitmobile.com/1/sendmessage/interests.{format}
     *
     * @param $params
     * @return mixed
     * @throws Zend_Http_Client_Exception
     */
    public function sendMessageToInterest($params)
    {
        $uri = $this->_composeUri(
            $this->_urlKey . '/interests'
        );

        $response = $this->getClient()->setUri($uri)->setParameterPost($params)->request(Zend_Http_Client::POST);
        return $this->decodeAndCheck($response->getBody());
    }

    /**
     * Send an MMS message to all users subscribed to a given keyword.
     * Parameters:
     *  keyword_id - the keyword_id of the keyword the message is coming from and the member is subscribed to. It is the ID attribute in the Keyword entity and can be viewed using the Get Keyword method. (Required)
     *  title - the title of the message. This does not appear in the text message and is just used in the application as a short description of your message. (Required)
     *  message - the message being sent to the member. The message must be less than 160 characters including your keyword in the beginning of the message. (Required)
     *  content_url - URL to the multimedia entity (image, video, audio)
     * Method: POST
     * http://api.optitmobile.com/1/sendmms/keywords.{format}
     * 
     * @param $params
     * @return mixed
     * @throws Zend_Http_Client_Exception
     */
    public function sendMmsToKeyword($params)
    {
        $uri = $this->_composeUri(
            'sendmms/keywords'
        );

        $response = $this->getClient()->setUri($uri)->setParameterPost($params)->request(Zend_Http_Client::POST);
        return $this->decodeAndCheck($response->getBody());
    }

    /**
     * Opt It SMS Bulk Send Message.
     * http://api.optitmobile.com/1/sendmessage/bulk.{format}
     * 
     * @param array $params
     * @return mixed
     * @throws Zend_Http_Client_Exception
     */
    public function sendBulk($params = array())
    {
        $uri = $this->_composeUri(
            $this->_urlKey . '/bulk'
        );

        $response = $this->getClient()->setUri($uri)->setParameterPost($params)->request(Zend_Http_Client::POST);
        return $this->checkStatus($response->getStatus());
    }

    protected function _afterSave()
    {
        $data = array();
        $phone = Mage::getResourceModel('optit/phone');
        
        if ($this->getType() == Inchoo_Optit_Model_Subscription::SUBSCRIPTION_TYPE_MEMBER) {
            $phone->saveMemberPhoneNumber(array(
                'message_id' => $this->getId(),
                'phone_number' => $this->getPhone()
            ));
        } elseif ($this->getType() == Inchoo_Optit_Model_Subscription::SUBSCRIPTION_TYPE_KEYWORD) {
            $phones = Mage::getSingleton('optit/system_config_source_subscription_phone')->toOptionArray($this->getEntityId());
            if (empty($phones)) {
                throw new Exception("There are no phone numbers to save.");
            }
            foreach ($phones['subscriptions'] as $value) {
                $data[] = array(
                    'message_id' => $this->getId(),
                    'phone_number' => $value['subscription']['phone'],
                );
            }
            $phone->saveKeywordPhoneNumbers($data);
        }
        parent::_afterSave();
    }
}