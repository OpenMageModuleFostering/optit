<?php

class Inchoo_Optit_Block_Adminhtml_Member extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'optit';
        $this->_controller = 'adminhtml_member';
        $this->_headerText = 'Members';

        parent::__construct();
        $this->removeButton('add');
    }

    protected function _prepareLayout()
    {
        return parent::_prepareLayout();
    }
}