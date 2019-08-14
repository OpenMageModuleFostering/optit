<?php


class Inchoo_Optit_Model_System_Config_Source_Subscription_Interest
{
    protected $_data = array();

    protected $_key = 0;

    public function toOptionArray($keywordId)
    {
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
}