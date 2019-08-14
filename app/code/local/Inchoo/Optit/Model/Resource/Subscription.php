<?php


class Inchoo_Optit_Model_Resource_Subscription extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('optit/subscription', 'id');
    }

    public function getSubscriptionByPhoneAndInterest($interest, $phone)
    {
        $adapter = $this->_getReadAdapter();
        $select = $adapter
            ->select()
            ->from($this->getMainTable())
            ->where('interest = ?', $interest)
            ->where('phone = ?', $phone);

        $result = $adapter->fetchOne($select);
        return $result;
    }

    /**
     * Delete all rows with retries equal or greater than config setting
     *
     * @throws Mage_Core_Exception
     */
    public function cleanTable()
    {
        try {
            $adapter = $this->_getWriteAdapter();
            $adapter->beginTransaction();

            $condition = array('retries >= ?' => Mage::helper('optit')->getMaxRetries());
            $adapter->delete($this->getMainTable(), $condition);

            $adapter->commit();
        } catch (Mage_Core_Exception $e) {
            $adapter->rollback();
            throw $e;
        } catch (Exception $e) {
            $adapter->rollback();
            Mage::logException($e);
        }
    }
}