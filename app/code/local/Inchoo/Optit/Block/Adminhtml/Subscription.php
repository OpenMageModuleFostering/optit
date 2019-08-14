<?php


class Inchoo_Optit_Block_Adminhtml_Subscription extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'optit';
        $this->_controller = 'adminhtml_subscription';

        parent::__construct();

        $filterType = Mage::registry('filter_type');

        if ($filterType == Inchoo_Optit_Model_Subscription::SUBSCRIPTION_TYPE_KEYWORD) {
            $this->removeButton('add');
            $this->addButton('back', array(
                'label' => $this->__('Back'),
                'onclick' => "setLocation('{$this->getBackUrl()}')",
                'class' => 'back'
            ), 0, 1);
            $confirmationMessage = Mage::helper('core')->jsQuoteEscape(
                $this->__('Are you sure you want to send SMS to these subscribers?')
            );
            $this->addButton('send_sms', array(
                'label' => $this->__('Send SMS'),
                'onclick' => "confirmSetLocation('{$confirmationMessage}', '{$this->getSendSmsUrl()}')",
                'class' => 'go'
            ), 0, 2);
            $confirmationMessage = Mage::helper('core')->jsQuoteEscape(
                $this->__('Are you sure you want to send MMS to these subscribers?')
            );
            $this->addButton('send_mms', array(
                'label' => $this->__('Send MMS'),
                'onclick' => "confirmSetLocation('{$confirmationMessage}', '{$this->getSendMmsUrl()}')",
                'class' => 'go'
            ), 0, 3);
            $this->addButton('add', array(
                'label' => $this->__('New Subscription'),
                'onclick' => "setLocation('{$this->getNewUrl()}')",
                'class' => 'add'
            ), 0, 4);
        }
        elseif ($filterType == Inchoo_Optit_Model_Subscription::SUBSCRIPTION_TYPE_INTEREST) {
            $this->removeButton('add');
            $this->addButton('back', array(
                'label' => $this->__('Back'),
                'onclick' => "setLocation('{$this->getBackToInterestsUrl()}')",
                'class' => 'back'
            ), 0, 1);
            $confirmationMessage = Mage::helper('core')->jsQuoteEscape(
                $this->__('Are you sure you want to send SMS to these subscribers?')
            );
            $this->addButton('send', array(
                'label' => $this->__('Send Message'),
                'onclick' => "confirmSetLocation('{$confirmationMessage}', '{$this->getSendSmsUrl()}')",
                'class' => 'go'
            ), 0, 2);
            $this->addButton('add', array(
                'label' => $this->__('Subscribe Member To Interest'),
                'onclick' => "setLocation('{$this->getSubscribeUrl()}')",
                'class' => 'add'
            ), 0, 3);
        }
    }

    protected function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    public function getBackUrl()
    {
        return $this->getUrl('*/optit_keyword');
    }

    public function getBackToInterestsUrl()
    {
        return $this->getUrl('*/optit_interest', array(
            'keyword_id' => $this->getRequest()->getParam('keyword_id'),
            'keyword_name' => $this->_getKeywordName(),
        ));
    }

    public function getNewUrl()
    {
        $filterId = Mage::registry('filter_id');
        return $this->getUrl('*/*/new', array(
            'keyword_id' => $filterId,
            'keyword_name' => $this->_getSubscriptionTypeName()
        ));
    }

    public function getSendSmsUrl()
    {
        return $this->getUrl('*/optit_message/sms', array(
            'id' => Mage::registry('filter_id'),
            'type' => Mage::registry('filter_type'),
            'type_name' => $this->_getSubscriptionTypeName(),
        ));
    }

    public function getSendMmsUrl()
    {
        return $this->getUrl('*/optit_message/mms', array(
            'id' => Mage::registry('filter_id'),
            'type' => Mage::registry('filter_type'),
            'type_name' => $this->_getSubscriptionTypeName(),
        ));
    }

    public function getSubscribeUrl()
    {
        return $this->getUrl('*/optit_interest/subscribe',
            array(
                'interest_id' => $this->getRequest()->getParam('interest_id'),
                'keyword_id' => $this->getRequest()->getParam('keyword_id'),
                'keyword_name' => $this->_getKeywordName(),
                'type_name'  => $this->_getSubscriptionTypeName(),
            ));
    }

    public function getHeaderText()
    {
        return $this->__("Subscriptions for '%s'", $this->_getSubscriptionTypeName());
    }

    protected function _getKeywordName()
    {
        return $this->getRequest()->getParam('keyword_name');
    }

    protected function _getSubscriptionTypeName()
    {
        if ($text = $this->getRequest()->getParam('type_name')) {
            return $text;
        } else {
            return $this->_getKeywordName();
        }
    }
}