<?php


class Inchoo_Optit_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_DEFAULT_KEYWORD = 'promo/optit_checkout/optit_default_keyword';
    const XML_PATH_DEFAULT_INTERESTS = 'promo/optit_checkout/optit_default_interests';
    const XML_PATH_CHECKOUT_OPTIT_ENABLED = 'promo/optit_checkout/optit_enable_checkout_subscription';
    const XML_PATH_CHECKOUT_CRON_MAX_RETRIES = 'promo/optit_checkout/optit_max_cron_retries';

    public function getDefaultKeywordId()
    {
        return Mage::getStoreConfig(self::XML_PATH_DEFAULT_KEYWORD);
    }

    public function getDefaultInterests()
    {
        return Mage::getStoreConfig(self::XML_PATH_DEFAULT_INTERESTS);
    }

    public function isCheckoutOptInEnabled()
    {
        return Mage::getStoreConfig(self::XML_PATH_CHECKOUT_OPTIT_ENABLED);
    }

    public function getMaxRetries()
    {
        return Mage::getStoreConfig(self::XML_PATH_CHECKOUT_CRON_MAX_RETRIES);
    }
}