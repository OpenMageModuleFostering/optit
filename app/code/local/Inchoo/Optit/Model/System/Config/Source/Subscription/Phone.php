<?php


class Inchoo_Optit_Model_System_Config_Source_Subscription_Phone
{
    protected $_data = array();
    protected $_key  = 0;

    public function toOptionArray($keywordId, $interestId = null)
    {
        $data = array();
        $subscribersPhones= array();

        if (!is_null($interestId)) {
            $subscribersKeyword = $this->_getAllSubscribersByKeyword($keywordId);
            foreach ($subscribersKeyword['subscriptions'] as $value) {
                $subscribersPhones[$value['subscription']['phone']] = $value['subscription']['phone'];
            }
            $subscribersInterest = Mage::getModel('optit/subscription')->getSubscriptionsByInterest($interestId);
            foreach ($subscribersInterest['subscriptions'] as $value) {
                unset($subscribersPhones[$value['subscription']['phone']]);
            }

            foreach ($subscribersPhones as $key => $phone) {
                $data[$key]['value'] = $phone;
                $data[$key]['label'] = $phone;
            }
        } else {
            $data = $this->_getAllSubscribersByKeyword($keywordId);
        }

        return $data;
    }

    protected function _getAllSubscribersByKeyword($keywordId)
    {
        $currentPage = 1;
        $phones = $this->_getPhones($keywordId, $currentPage);
        $totalPages = $phones['total_pages'];
        $this->_populateData($phones);

        while ($currentPage != $totalPages) {
            $currentPage++;
            $phones = $this->_getPhones($keywordId, $currentPage);
            $this->_populateData($phones);
        }

        return $this->_data;
    }

    protected function _getPhones($keywordId, $currentPage)
    {
        return Mage::getModel('optit/subscription')->getSubscriptionsByKeyword($keywordId, array('page' => $currentPage));
    }

    protected function _populateData($phones)
    {
        foreach ($phones['subscriptions'] as $key => $value) {
            $this->_data['subscriptions'][$this->_key] = $value;
            $this->_key++;
        }
    }
}
