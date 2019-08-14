<?php


class Inchoo_Optit_Block_Adminhtml_Keyword_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('optit_keyword_grid');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setFilterVisibility(false);
    }

    protected function _prepareCollection()
    {
        $collection = new Inchoo_Optit_Model_Collecition();
        $data = $this->getData();
        if (isset($data['keywords']) && !empty($data['keywords'])) {
            foreach ($data['keywords'] as $keyword) {
                $keywordObj = new Varien_Object();
                $keywordObj->setData($keyword['keyword']);
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
        $this->getCollection()->setPageSize((int)$this->getParam($this->getVarNameLimit(), $this->_defaultLimit));
//        $this->getCollection()->setCurPage((int) $this->getParam($this->getVarNamePage(), $this->_defaultPage));
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id',
            array(
                'header' => $this->__('ID'),
                'align' => 'right',
                'width' => '50px',
                'index' => 'id',
                'sortable' => false,
            )
        );

        $this->addColumn('keyword',
            array(
                'header' => $this->__('Keyword'),
                'index' => 'keyword_name',
                'sortable' => false,
            )
        );

        $this->addColumn('name',
            array(
                'header' => $this->__('Name'),
                'index' => 'internal_name',
                'sortable' => false,
            )
        );

        $this->addColumn('type',
            array(
                'header' => $this->__('Type'),
                'index' => 'keyword_type',
                'sortable' => false,
            )
        );

        $this->addColumn('short_code',
            array(
                'header' => $this->__('Short Code'),
                'index' => 'short_code',
                'sortable' => false,
            )
        );

        $this->addColumn('status',
            array(
                'header' => $this->__('Status'),
                'index' => 'status',
                'sortable' => false,
            )
        );

        $this->addColumn('subscription_count',
            array(
                'header' => $this->__('Subscription Count'),
                'index' => 'mobile_subscription_count',
                'sortable' => false,
            )
        );

        $this->addColumn('action',
            array(
                'header' => $this->__('Actions'),
                'sortable' => false,
                'filter' => false,
                'no_link' => true,
                'width' => '160px',
                'renderer' => 'optit/adminhtml_keyword_grid_renderer_action'
            )
        );
        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/optit_keyword/edit', array('id' => $row->getId()));
    }
}