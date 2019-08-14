<?php


class Inchoo_Optit_Model_Resource_Subscription_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('optit/subscription');
    }

    /**
     * Subscribe members to corresponding keyword and interest(s)
     */
    public function subscribeMembers()
    {
        $maxRetries = Mage::helper('optit')->getMaxRetries();
        $this->addFieldToFilter('status', array('neq' => Inchoo_Optit_Model_Subscription::SUBSCRIPTION_TYPE_SUBSCRIBED))
            ->addFieldToFilter('retries', array('lt' => $maxRetries))
            ->setPageSize(10);
        $model = Mage::getModel('optit/subscription');
        $subscribedPhone = null;

        foreach ($this as $member) {
            $keywordId = $member->getKeyword();
            $data = $member->getData();
            $retries = $member->getRetries();

            if ($retries >= $maxRetries) {
                continue;
            }

            try {
                $status = $member->getStatus();
                $phone = $member->getPhone();

                // we check subscribedPhone if already subscribed
                if ($status === Inchoo_Optit_Model_Subscription::SUBSCRIPTION_TYPE_TO_SUBSCRIBE && $phone != $subscribedPhone) {
                    $model->subscribeMemberToKeyword($keywordId, $data);
                    $subscribedPhone = $phone;
                }

                if ($interestId = $member->getInterest()) {
                    $model->subscribeMemberToInterest($interestId, $data);
                }

                $member->setData('status', Inchoo_Optit_Model_Subscription::SUBSCRIPTION_TYPE_SUBSCRIBED);
            } catch (Mage_Core_Exception $e) {
                $member->setData('status', Inchoo_Optit_Model_Subscription::SUBSCRIPTION_TYPE_KEYWORD_SUBSCRIBED);
                $member->setRetries($retries + 1);
            }
        }

        $this->save();
    }
}