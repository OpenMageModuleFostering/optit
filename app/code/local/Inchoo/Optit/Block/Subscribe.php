<?php


class Inchoo_Optit_Block_Subscribe extends Mage_Core_Block_Template
{
    public function isOptInEnabled()
    {
        return $this->helper('optit')->isCheckoutOptInEnabled();
    }
}