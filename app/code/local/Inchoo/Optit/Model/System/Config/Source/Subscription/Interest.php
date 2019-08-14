<?php


class Inchoo_Optit_Model_System_Config_Source_Subscription_Interest
{
    protected $_data = array();

    protected $_key = 0;

    public function toOptionArray($multiselect, $keywordId = null)
    {
        $configKeywordId = Mage::getStoreConfig('promo/optit_checkout/optit_default_keyword');
        $paramKeywordId = Mage::app()->getRequest()->getParam(Inchoo_Optit_Block_Adminhtml_System_Config_Field_Keyword::REQUEST_PARAM_KEYWORD);
        $firstKeyword = Mage::registry('optit_keyword_first');

        if (!is_null($paramKeywordId)) {
            $keywordId = $paramKeywordId;
        } elseif (!is_null($configKeywordId)) {
            $keywordId = $configKeywordId;
        } elseif (!is_null($firstKeyword) && !$keywordId) {
            $keywordId = $firstKeyword['value'];
        } elseif (is_null($keywordId)) {
            return array();
        }

        $currentPage = 1;
        $interests = $this->_getInterests($keywordId, $currentPage);
        $totalPages = $interests['total_pages'];
        $this->_populateData($interests);

        while ($currentPage != $totalPages) {
            $currentPage++;
            $interests = $this->_getInterests($keywordId, $currentPage);
            $this->_populateData($interests);
        }

        return $this->_data;
    }

    protected function _getInterests($keywordId, $page)
    {
        return Mage::getModel('optit/interest')->getAllInterests($keywordId, array('page' => $page));
    }

    protected function _populateData($interests)
    {
        foreach ($interests['interests'] as $key => $value) {
            $this->_data[$this->_key]['value'] = $value['interest']['id'];
            $this->_data[$this->_key]['label'] = $value['interest']['name'];
            $this->_key++;
        }
    }

    public function toOptionArrayWithAuthentication()
    {
        // Authenticate
        $model = Mage::getModel('optit/keyword');

        try {
            $model->getAllKeywords();
        } catch (Mage_Core_Exception $e) {
            return array();
        }

        return self::toOptionArray(true, null);
    }
}