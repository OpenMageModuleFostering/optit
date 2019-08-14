<?php

class Inchoo_Optit_Block_Adminhtml_Interest extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'optit';
        $this->_controller = 'adminhtml_interest';

        parent::__construct();
        $this->removeButton('add');
        $this->addButton('back', array(
            'label' => $this->__('Back'),
            'onclick' => "setLocation('{$this->getBackUrl()}')",
            'class' => 'back'
        ), 0, 1);
        $this->addButton('add', array(
            'label' => $this->__('New Interest'),
            'onclick' => "setLocation('{$this->getNewUrl()}')",
            'class' => 'add'
        ), 0, 4);
    }

    protected function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    public function getBackUrl()
    {
        return $this->getUrl('*/optit_keyword', array('keyword_name' => $this->_getKeywordName()));
    }

    public function getNewUrl()
    {
        return $this->getUrl('*/*/new', array(
            'keyword_id' => $this->getRequest()->getParam('keyword_id'),
            'keyword_name' => $this->_getKeywordName(),
        ));
    }

    public function getHeaderText()
    {
        return $this->__("Interests for '%s'", $this->_getKeywordName());
    }

    protected function _getKeywordName()
    {
        return $this->getRequest()->getParam('keyword_name');
    }
}