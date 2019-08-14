<?php


class Inchoo_Optit_Block_Adminhtml_Subscription_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('optit_subscription_grid');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setFilterVisibility(false);
    }

    protected function _prepareCollection()
    {
        $collection = new Inchoo_Optit_Model_Collecition();
        $filterId = Mage::registry('filter_id');
        $filterType = Mage::registry('filter_type');

        $data = Mage::getModel('optit/subscription')->getAllSubscriptions($filterType, $filterId);
        if (isset($data['subscriptions']) && !empty($data['subscriptions'])) {
            foreach ($data['subscriptions'] as $subscription) {
                $subscribeObj = new Varien_Object();
                $subscribeObj->setData($subscription['subscription']);
                $collection->addItem($subscribeObj);
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
        $this->addColumn('member_id',
            array(
                'header'=> $this->__('Member ID'),
                'align' =>'right',
                'width' => '50px',
                'index' => 'member_id',
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

        $this->addColumn('type',
            array(
                'header'=> $this->__('Subscription Type'),
                'index' => 'type',
                'sortable' => false,
            )
        );

        $this->addColumn('signup_date',
            array(
                'header'=> $this->__('Signup Date'),
                'index' => 'signup_date',
                'sortable' => false,
            )
        );

        $this->addColumn('created_at',
            array(
                'header'=> $this->__('Created'),
                'index' => 'created_at',
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
                'renderer' => 'optit/adminhtml_subscription_grid_renderer_action'
            )
        );
        return parent::_prepareColumns();
    }
}