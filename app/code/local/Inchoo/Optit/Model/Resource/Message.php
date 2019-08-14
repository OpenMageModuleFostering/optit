<?php


class Inchoo_Optit_Model_Resource_Message extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('optit/bulk_keyword_messages', 'message_id');
    }
}