<?php


class Inchoo_Optit_Model_System_Config_Source_Message_Keyword
{
    protected $_data = array();

    protected $_key = 0;


    public function toOptionArray()
    {
        $currentPage = 1;
        $keywords = $this->_getKeywords($currentPage);
        $totalPages = $keywords['total_pages'];
        $this->_populateData($keywords);

        while ($currentPage != $totalPages) {
            $currentPage++;
            $keywords = $this->_getKeywords($currentPage);
            $this->_populateData($keywords);
        }

        Mage::register('optit_keyword_first', $this->_data[0]);
        return $this->_data;
    }

    protected function _getKeywords($page)
    {
        return Mage::getModel('optit/keyword')->getAllKeywords(array('page' => $page));
    }

    protected function _populateData($data)
    {
        foreach ($data['keywords'] as $key => $value) {
            $this->_data[$this->_key]['value'] = $value['keyword']['id'];
            $this->_data[$this->_key]['label'] = $value['keyword']['keyword_name'];
            $this->_key++;
        }
    }

    public function toOptionArrayWithAuthentication()
    {
        $model = Mage::getModel('optit/keyword');

        try {
            $model->getAllKeywords();
        } catch (Mage_Core_Exception $e) {
            return array();
        }

        $keywords = self::toOptionArray();
        $data = array();
        foreach ($keywords as $key => $keyword) {
            $data[$keyword['value']]= $keyword['label'];
        }

        return $data;
    }
}