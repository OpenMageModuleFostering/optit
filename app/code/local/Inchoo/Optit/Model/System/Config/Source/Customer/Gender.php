<?php


class Inchoo_Optit_Model_System_Config_Source_Customer_Gender
{
    public function toOptionArray()
    {
        return array(
            array('value' => '', 'label' => Mage::helper('adminhtml')->__('-- Please select --')),
            array('value' => 'male', 'label' => Mage::helper('adminhtml')->__('Male')),
            array('value' => 'female', 'label' => Mage::helper('adminhtml')->__('Female')),
        );
    }
}