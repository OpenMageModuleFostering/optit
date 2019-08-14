<?php


class Inchoo_Optit_Block_Adminhtml_Message_Mms extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'optit';
        $this->_controller = 'adminhtml_message_mms';
        $this->_headerText = 'MMS Messages';

        parent::__construct();
        $this->removeButton('add');
        $confirmationMessage = Mage::helper('core')->jsQuoteEscape(
            $this->__('Are you sure you want to clear this bulk list?')
        );
        $this->addButton('clear', array(
            'label' => $this->__('Clear'),
            'class' => 'delete',
            'onclick' => "confirmSetLocation('{$confirmationMessage}', '{$this->getClearUrl()}')",
        ));
        $this->addButton('send', array(
            'label' => $this->__('Send'),
            'class' => 'go',
            'onclick' => "setLocation('{$this->getSendUrl()}')",
        ));
    }

    protected function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    protected function getSendUrl()
    {
        return $this->getUrl('*/*/sendMms');
    }

    protected function getClearUrl()
    {
        return $this->getUrl('*/*/clear', array('type' => Inchoo_Optit_Model_Message::MESSAGE_TYPE_MMS));
    }
}