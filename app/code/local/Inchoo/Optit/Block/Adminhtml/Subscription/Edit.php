<?php


class Inchoo_Optit_Block_Adminhtml_Subscription_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'optit';
        $this->_controller = 'adminhtml_subscription';

        parent::__construct();

        $this->_updateButton('save', 'label', $this->__('Save Subscription'));
        $this->_updateButton('back', 'onclick', "setLocation('{$this->getBackUrl()}')");
        $this->_removeButton('delete');
        $this->_removeButton('reset');
    }

    public function getHeaderText()
    {
        return $this->__("New Subscription for '%s'", $this->_getKeywordName());
    }

    public function getBackUrl()
    {
        return $this->getUrl('*/optit_subscription', array(
            'keyword_id' => $this->getKeywordId(),
            'type_name' => $this->_getKeywordName()
        ));
    }

    public function getKeywordId()
    {
        return $this->getRequest()->getParam('keyword_id');
    }

    public function getSaveUrl()
    {
        return $this->getUrl('*/*/save', array(
                        'keyword_name' => $this->getRequest()->getParam('keyword_name'),
                        'type_name' => $this->getRequest()->getParam('type_name'),
                    ));
    }

    protected function _getKeywordName()
    {
        return $this->getRequest()->getParam('keyword_name');
    }
}