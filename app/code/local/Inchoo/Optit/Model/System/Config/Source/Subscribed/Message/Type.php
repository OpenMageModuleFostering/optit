<?php


class Inchoo_Optit_Model_System_Config_Source_Subscribed_Message_Type
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'standard', 'label' => Mage::helper('adminhtml')->__('Standard')),
            array('value' => 'custom', 'label' => Mage::helper('adminhtml')->__('Custom')),
            array('value' => 'none', 'label' => Mage::helper('adminhtml')->__('None')),
        );
    }
}