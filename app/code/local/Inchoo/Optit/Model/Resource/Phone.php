<?php


class Inchoo_Optit_Model_Resource_Phone extends Mage_Core_Model_Resource_Db_Abstract
{
    protected $_isPkAutoIncrement = false;

    public function _construct()
    {
        $this->_setMainTable('optit/bulk_message_phones');
    }

    public function saveMemberPhoneNumber($data)
    {
        return $this->_getWriteAdapter()->insert(
            $this->getMainTable(), 
            $data
        );
    }

    public function saveKeywordPhoneNumbers($data)
    {
        return $this->_getWriteAdapter()->insertMultiple(
            $this->getMainTable(),
            $data
        );
    }
}