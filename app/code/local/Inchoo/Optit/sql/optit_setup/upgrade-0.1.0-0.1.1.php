<?php

/** @var $installer Mage_Catalog_Model_Resource_Setup */

$installer = $this;
$connection = $this->getConnection();

$installer->startSetup();

if (!$connection->isTableExists($installer->getTable('optit/subscription'))) {
    $table = $this->getConnection()
        ->newTable($this->getTable('optit/subscription'))
        ->addColumn(
            'id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'primary'  => true,
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
            ), 'Id')
        ->addColumn(
            'keyword',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'nullable' => true,
            ), 'Keyword')
        ->addColumn(
            'interest',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'nullable' => true,
            ), 'Interest')
        ->addColumn(
            'phone',
            Varien_Db_Ddl_Table::TYPE_VARCHAR,
            50,
            array(
                'nullable' => false,
            ), 'Phone')
        ->addColumn(
            'email_address',
            Varien_Db_Ddl_Table::TYPE_VARCHAR,
            255,
            array(
                'nullable' => true,
            ), 'Email')
        ->addColumn(
            'first_name',
            Varien_Db_Ddl_Table::TYPE_VARCHAR,
            255,
            array(
                'nullable' => true,
            ), 'First Name')
        ->addColumn(
            'last_name',
            Varien_Db_Ddl_Table::TYPE_VARCHAR,
            255,
            array(
                'nullable' => true,
            ), 'Last Name')
        ->addColumn(
            'address1',
            Varien_Db_Ddl_Table::TYPE_VARCHAR,
            255,
            array(
                'nullable' => true,
            ), 'Address 1')
        ->addColumn(
            'address2',
            Varien_Db_Ddl_Table::TYPE_VARCHAR,
            255,
            array(
                'nullable' => true,
            ), 'Address 2')
        ->addColumn(
            'city',
            Varien_Db_Ddl_Table::TYPE_VARCHAR,
            255,
            array(
                'nullable' => true,
            ), 'City')
        ->addColumn(
            'state',
            Varien_Db_Ddl_Table::TYPE_VARCHAR,
            255,
            array(
                'nullable' => true,
            ), 'State')
        ->addColumn(
            'zip',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'nullable' => true,
            ), 'Zip')
        ->addColumn(
            'gender',
            Varien_Db_Ddl_Table::TYPE_VARCHAR,
            null,
            array(
                'nullable' => true,
            ), 'Gender')
        ->addColumn(
            'birth_date',
            Varien_Db_Ddl_Table::TYPE_VARCHAR,
            null,
            array(
                'nullable' => true,
            ), 'Birth Date')
        ->addColumn(
            'status',
            Varien_Db_Ddl_Table::TYPE_TINYINT,
            1,
            array(
                'nullable' => false,
                'default' => '0',
            ), 'Status')
        ->addColumn(
            'retries',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'nullable' => false,
                'default' => '0',
            ), 'Retries')
        ->addIndex(
            $installer->getIdxName(
                'optit/subscription',
                array('keyword', 'interest', 'phone'),
                Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
            ),
            array('keyword', 'interest', 'phone'),
            array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
        )->setComment('Subscription Queue');

    $this->getConnection()->createTable($table);
}

$installer->endSetup();