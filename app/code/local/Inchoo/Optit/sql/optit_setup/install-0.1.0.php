<?php

/** @var $installer Mage_Catalog_Model_Resource_Setup */

$installer = $this;
$connection = $this->getConnection();

$installer->startSetup();

/** optit_bulk_messages */
if (!$connection->isTableExists($installer->getTable('optit/bulk_keyword_messages'))) {
    $table = $this->getConnection()
        ->newTable($this->getTable('optit/bulk_keyword_messages'))
        ->addColumn(
            'message_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'primary'  => true,
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
            ), 'Id')
        ->addColumn(
            'message_type',
            Varien_Db_Ddl_Table::TYPE_VARCHAR,
            null,
            array(
                'nullable' => false,
            ), 'Message Type')
        ->addColumn(
            'entity_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'unsigned' => true,
                'nullable' => false,
            ), 'Entity Id')
        ->addColumn(
            'entity_type_code',
            Varien_Db_Ddl_Table::TYPE_VARCHAR,
            null,
            array(
                'nullable' => false,
            ), 'Entity Type Code')
        ->addColumn(
            'title',
            Varien_Db_Ddl_Table::TYPE_VARCHAR,
            null,
            array(
                'nullable' => false,
            ), 'Title')
        ->addColumn(
            'message',
            Varien_Db_Ddl_Table::TYPE_VARCHAR,
            null,
            array(
                'nullable' => false,
            ), 'Message')
        ->addColumn(
            'content_url',
            Varien_Db_Ddl_Table::TYPE_VARCHAR,
            null,
            array(
                'nullable' => true,
            ), 'Content Url')
        ->setComment('Bulk Messages');

    $this->getConnection()->createTable($table);
}

/** optit_bulk_phones */
if (!$connection->isTableExists($installer->getTable('optit/bulk_message_phones'))) {
    $table = $this->getConnection()
        ->newTable($this->getTable('optit/bulk_message_phones'))
        ->addColumn(
            'message_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'unsigned' => true,
                'nullable' => false,
            ), 'ID')
        ->addColumn(
            'phone_number',
            Varien_Db_Ddl_Table::TYPE_VARCHAR,
            25,
            array(
                'nullable' => false,
            ), 'Phone Number')
        ->addIndex(
            $installer->getIdxName('optit/bulk_message_phones', array('message_id', 'phone_number'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE),
            array('message_id', 'phone_number'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
        ->addForeignKey(
            $this->getFkName('optit/bulk_message_phones', 'message_id', 'optit/bulk_keyword_messages', 'message_id'),
            'message_id',
            $this->getTable('optit/bulk_keyword_messages'), 'message_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
        ->setComment('Bulk Message Phones');

    $this->getConnection()->createTable($table);
}

$installer->endSetup();