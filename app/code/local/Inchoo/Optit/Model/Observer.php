<?php


class Inchoo_Optit_Model_Observer
{
    /* sales_quote_save_after */
    public function subscribeMemberToTextNotifications(Varien_Event_Observer $observer)
    {
        $post = Mage::app()->getRequest()->getPost();

        if (!isset($post['billing'])) {
            return;
        }

        $session = $this->_getSession();
        $subscribeMe = isset($post['billing']['subscribe_me']) ? $post['billing']['subscribe_me'] : null;
        $cellphone = isset($post['billing']['cellphone']) ? $post['billing']['cellphone'] : null;
        $session->setSubscribeMe($subscribeMe);
        $session->setCustomerCellphone($cellphone);
    }

    public function subscribeMembers()
    {
        if (Mage::helper('optit')->isCheckoutOptInEnabled()) {
            Mage::getModel('optit/subscription')->getCollection()->subscribeMembers();
        }
    }

    /* sales_order_place_after */
    public function enableSubscription(Varien_Event_Observer $observer)
    {
        $session = $this->_getSession();
        if (!$session->getSubscribeMe()) {
            return;
        }
        $keyword = Mage::helper('optit')->getDefaultKeywordId();
        $quote = $observer->getOrder()->getQuote();
        $quote->setKeyword($keyword);
        $quote->setCustomerCellphone($session->getCustomerCellphone());
        $subscription = Mage::getModel('optit/subscription');
        $interests = unserialize(Mage::helper('optit')->getDefaultInterests());

        foreach ($interests as $interest) {
            $quote->setInterest($interest);
            $subscription->setData(array(
                'quote' =>  $quote,
            ))->addToQueue();
        }
    }

    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }

    public function cleanSubscribeTable()
    {
        if (Mage::helper('optit')->isCheckoutOptInEnabled()) {
            Mage::getResourceModel('optit/subscription')->cleanTable();
        }
    }
}