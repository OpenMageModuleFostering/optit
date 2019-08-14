<?php


class Inchoo_Optit_Model_System_Config_Source_Web_Form_Message_Type
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'standard', 'label' => Mage::helper('adminhtml')->__('Standard')),
            array('value' => 'semi-custom', 'label' => Mage::helper('adminhtml')->__('Semi-custom')),
        );
    }
}