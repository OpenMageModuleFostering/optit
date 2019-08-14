<?php


class Inchoo_Optit_Block_Adminhtml_Interest_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'optit';
        $this->_controller = 'adminhtml_interest';

        parent::__construct();

        $this->_updateButton('save', 'label', $this->__('Save Interest'));
        $this->_updateButton('back', 'onclick', "setLocation('{$this->getBackUrl()}')");
        $this->_removeButton('delete');
        $this->_removeButton('reset');
    }

    public function getHeaderText()
    {
        return $this->__("New Interest for '%s'", $this->_getKeywordName());
    }

    public function getBackUrl()
    {
        return $this->getUrl('*/optit_interest', array(
            'keyword_id' => $this->getRequest()->getParam('keyword_id'),
            'keyword_name' => $this->_getKeywordName()
        ));
    }

    public function getSaveUrl()
    {
        return $this->getUrl('*/*/save', array(
            'keyword_name' => $this->_getKeywordName(),
            ));
    }

    protected function _getKeywordName()
    {
        return $this->getRequest()->getParam('keyword_name');
    }
}