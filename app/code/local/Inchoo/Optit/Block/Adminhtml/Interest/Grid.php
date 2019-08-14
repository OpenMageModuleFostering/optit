<?php

class Inchoo_Optit_Block_Adminhtml_Interest_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('optit_interest_grid');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setFilterVisibility(false);
    }

    protected function _prepareCollection()
    {
        $collection = new Inchoo_Optit_Model_Collecition();
        $data = $this->getData();
        if (isset($data['interests']) && !empty($data['interests'])) {
            foreach ($data['interests'] as $keyword) {
                $keywordObj = new Varien_Object();
                $keywordObj->setData($keyword['interest']);
                $collection->addItem($keywordObj);
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
        $this->getCollection()->setPageSize((int) $this->getParam($this->getVarNameLimit(), $this->_defaultLimit));
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

        $this->addColumn('name',
            array(
                'header'=> $this->__('Name'),
                'index' => 'name',
                'sortable' => false,
            )
        );

        $this->addColumn('description',
            array(
                'header'=> $this->__('Description'),
                'index' => 'description',
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

        $this->addColumn('subscription_count',
            array(
                'header'=> $this->__('Subscription Count'),
                'index' => 'mobile_subscription_count',
                'sortable' => false,
            )
        );

        $this->addColumn('status',
            array(
                'header'=> $this->__('Status'),
                'index' => 'status',
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
                'width' => '160px',
                'renderer' => 'optit/adminhtml_interest_grid_renderer_action'
            )
        );
        return parent::_prepareColumns();
    }
}