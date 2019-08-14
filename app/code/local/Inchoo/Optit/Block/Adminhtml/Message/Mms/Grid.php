<?php


class Inchoo_Optit_Block_Adminhtml_Message_Mms_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('optitBulkMmsGrid');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setFilterVisibility(false);
        $this->_emptyText = 'The bulk messaging list is empty. Please use appropriate message forms to create messages and add them to bulk.';
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('optit/message')->getCollection()
            ->addFieldToFilter('message_type', array('eq' => Inchoo_Optit_Model_Message::MESSAGE_TYPE_MMS))
            ->addMessageRecipients();

        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    protected function _prepareColumns()
    {
        $this->addColumn('message_title',
            array(
                'header'=> $this->__('Title'),
                'index' => 'title',
                'width' => '10%',
                'sortable' => false,
            )
        );

        $this->addColumn('message',
            array(
                'header'=> $this->__('Message'),
                'index' => 'message',
                'width' => '30%',
                'sortable' => false,
            )
        );

        $this->addColumn('recipients',
            array(
                'header'=> $this->__('Recipients'),
                'index' => 'recipients',
                'sortable' => false,
            )
        );

        return parent::_prepareColumns();
    }
}