<?php


class Inchoo_Optit_Block_Adminhtml_Member_Grid_Renderer_Action extends Inchoo_Optit_Block_Adminhtml_Widget_Grid_Column_Renderer_Action
{
    public function render(Varien_Object $row)
    {
        $actions = array();
        $actions[] = array(
            'url'     => $this->getUrl('*/optit_member/unsubscribe', array('phone' => $row->getPhone())),
            'caption' => $this->__('Unsubscribe From All Keywords'),
            'confirm' => $this->__('Are you sure you want to unsubscribe member from all keywords?')
        );

        $this->getColumn()->setActions($actions);

        return parent::render($row);
    }
}