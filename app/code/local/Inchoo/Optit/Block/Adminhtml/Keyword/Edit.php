<?php


class Inchoo_Optit_Block_Adminhtml_Keyword_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'optit';
        $this->_controller = 'adminhtml_keyword';

        parent::__construct();

        $this->_updateButton('save', 'label', $this->__('Save Keyword'));
        $this->_removeButton('delete');
        $this->_removeButton('delete');
    }

    public function getHeaderText()
    {
        if (Mage::registry('optit_keyword')->getId()) {
            return $this->__("Edit Keyword '%s'", $this->_getKeywordName());
        }
        else {
            return $this->__("New Keyword");
        }
    }

    public function getSaveUrl()
    {
        return $this->getUrl('*/*/save', array('id ' => $this->getRequest()->getParam('id')));
    }

    protected function _getKeywordName()
    {
        return $this->getRequest()->getParam('keyword_name');
    }
}