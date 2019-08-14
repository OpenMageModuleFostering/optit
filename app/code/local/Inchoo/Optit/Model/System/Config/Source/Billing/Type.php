<?php


class Inchoo_Optit_Model_System_Config_Source_Billing_Type
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'Unlimited', 'label' => Mage::helper('adminhtml')->__('Unlimited')),
            array('value' => 'Per-message', 'label' => Mage::helper('adminhtml')->__('Per Message')),
        );
    }
}