<?php


class Inchoo_Optit_Block_Adminhtml_Message_Mms_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'optit';
        $this->_controller = 'adminhtml_message_mms';

        parent::__construct();

        $this->updateButton('save', 'label', $this->__('Send'));
        $this->updateButton('save', 'class', 'go');
        $this->removeButton('back');
        $this->removeButton('delete');
        $this->removeButton('reset');

        if ($this->getRequest()->getParam('type') !== Inchoo_Optit_Model_Subscription::SUBSCRIPTION_TYPE_INTEREST) {
            $this->addButton('bulk', array(
                'label' => $this->__('Add To Bulk'),
                'class' => 'add',
                'onclick' => "addToBulk();"
            ));

            $this->_formScripts[] = "function addToBulk()" .
                "{editForm.submit($('edit_form').action + 'bulk/true/')}";
        }
    }

    public function getHeaderText()
    {
        return $this->__("Send Message to '%s'", $this->getRequest()->getParam('type_name'));
    }

    public function getSaveUrl()
    {
        return $this->getUrl('*/*/sendMms');
    }
}