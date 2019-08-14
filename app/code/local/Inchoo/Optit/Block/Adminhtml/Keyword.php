<?php


class Inchoo_Optit_Block_Adminhtml_Keyword extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'optit';
        $this->_controller = 'adminhtml_keyword';
        $this->_headerText = 'Keywords';

        parent::__construct();
        $this->updateButton('add', 'label', $this->__('New Keyword'));
    }

    protected function _prepareLayout()
    {
        return parent::_prepareLayout();
    }
}