<?php


class Inchoo_Optit_Model_Collecition extends Varien_Data_Collection
{
    const OPTIT_ITEMS_COLLECTION_SIZE = 20;

    public function setSize($size)
    {
        $this->_totalRecords = $size;
        return $this;
    }
}