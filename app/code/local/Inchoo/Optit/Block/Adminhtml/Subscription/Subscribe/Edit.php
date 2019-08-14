<?php


class Inchoo_Optit_Block_Adminhtml_Subscription_Subscribe_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'optit';
        $this->_controller = 'adminhtml_subscription_subscribe';

        parent::__construct();

        $this->_updateButton('save', 'label', $this->__('Save Subscription'));
        $this->_updateButton('back', 'onclick', "setLocation('{$this->getBackUrl()}')");
        $this->removeButton('delete');
        $this->removeButton('reset');
    }

    public function getHeaderText()
    {
        return $this->__("New Subscription For '%s'", $this->_getSubscriptionTypeName());
    }

    public function getBackUrl()
    {
        return $this->getUrl('*/optit_subscription/interest', array(
            'interest_id' => $this->getRequest()->getParam('interest_id'),
            'keyword_id' => $this->getRequest()->getParam('keyword_id'),
            'keyword_name' => $this->getRequest()->getParam('keyword_name'),
            'type_name'  => $this->getRequest()->getParam('type_name'),
        ));
    }

    public function getSaveUrl()
    {
        return $this->getUrl('*/*/subscribeSave', array(
                'id' => $this->getRequest()->getParam('id'),
                'keyword_name' => $this->getRequest()->getParam('keyword_name'),
                'type_name'  => $this->getRequest()->getParam('type_name'),
            ));
    }

    protected function _getSubscriptionTypeName()
    {
        return $this->getRequest()->getParam('type_name');
    }
}