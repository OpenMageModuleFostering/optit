<?php


class Inchoo_Optit_Block_Adminhtml_Member_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('optit_member_grid');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setFilterVisibility(false);
    }

    protected function _prepareCollection()
    {
        $collection = new Inchoo_Optit_Model_Collecition();
        $data = $this->getData();
        if (isset($data['members']) && !empty($data['members'])) {
            foreach ($data['members'] as $member) {
                $memberObj = new Varien_Object();
                $memberObj->setData($member['member']);
                $collection->addItem($memberObj);
            }

            $totalPages = $data['total_pages'];
            $currentPage = $data['current_page'];

            if ($currentPage <= $totalPages) {
                $collection->setCurPage($currentPage);
                $collection->setSize(Inchoo_Optit_Model_Collecition::OPTIT_ITEMS_COLLECTION_SIZE * $totalPages);
            } else {
                $this->setVarNamePage(1);
            }
        }

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _preparePage()
    {
        $this->getCollection()->setPageSize((int)$this->getParam($this->getVarNameLimit(), $this->_defaultLimit));
//        $this->getCollection()->setCurPage((int) $this->getParam($this->getVarNamePage(), $this->_defaultPage));
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id',
            array(
                'header'=> $this->__('ID'),
                'align' =>'right',
                'width' => '50px',
                'index' => 'id',
                'sortable' => false,
            )
        );

        $this->addColumn('first_name',
            array(
                'header'=> $this->__('First Name'),
                'index' => 'first_name',
                'sortable' => false,
            )
        );

        $this->addColumn('last_name',
            array(
                'header'=> $this->__('Last Name'),
                'index' => 'last_name',
                'sortable' => false,
            )
        );

        $this->addColumn('carrier_name',
            array(
                'header'=> $this->__('Carrier Name'),
                'index' => 'carrier_name',
                'sortable' => false,
            )
        );

        $this->addColumn('phone',
            array(
                'header'=> $this->__('Phone'),
                'index' => 'phone',
                'sortable' => false,
            )
        );

        $this->addColumn('created_at',
            array(
                'header'=> $this->__('Created At'),
                'index' => 'created_at',
                'sortable' => false,
            )
        );

        $this->addColumn('mobile_status',
            array(
                'header'=> $this->__('Status'),
                'index' => 'mobile_status',
                'sortable' => false,
            )
        );


        $this->addColumn('action',
            array(
                'header'=> $this->__('Actions'),
                'index' => 'id',
                'sortable' => false,
                'filter' => false,
                'no_link' => true,
                'width' => '200px',
                'renderer' => 'optit/adminhtml_member_grid_renderer_action'
            )
        );
        return parent::_prepareColumns();
    }
}