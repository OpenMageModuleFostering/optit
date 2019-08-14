<?php


class Inchoo_Optit_Adminhtml_Optit_SubscriptionController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('promo/optit')
            ->_title($this->__('Opt It'))
            ->_title($this->__('Keywords'))
            ->_title($this->__('Subscriptions'));

        return $this;
    }

    public function indexAction()
    {
        $request = $this->getRequest();

        if ($request->getQuery('ajax')) {
            $this->_forward('grid');
            return;
        }

        $keywordId = $request->getParam('keyword_id');
        if (!$keywordId) {
            $this->_redirect('*/optit_keyword');
            return;
        }

        Mage::register('filter_id', $keywordId);
        Mage::register('filter_type', Inchoo_Optit_Model_Subscription::SUBSCRIPTION_TYPE_KEYWORD);
        $this->_initAction();
        $this->renderLayout();
    }

    public function unsubscribeAction()
    {
        $request = $this->getRequest();
        $filterId = $request->getParam('filter_id');
        $phone = $request->getParam('phone');

        $model = Mage::getModel('optit/subscription');

        try {
            $model->unsubscribeMemberFromKeyword($filterId, $phone);
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError(nl2br($e->getMessage()));
        }
        $this->_redirect('*/*/', array(
            'type_name' => $request->getParam('type_name')
        ));
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function newAction()
    {
        $request = $this->getRequest();
        $keywordId = $request->getParam('keyword_id');

        if (!$keywordId) {
            $this->_redirect('*/optit_keyword');
            return;
        }

        Mage::register('filter_id', $keywordId);
        Mage::register('filter_type', Inchoo_Optit_Model_Subscription::SUBSCRIPTION_TYPE_KEYWORD);
        $this->_initAction();
        $this->_title($this->__('New Subscription'));
        $this->_addBreadcrumb($this->__('New Subscription'), $this->__('New Subscription'))
            ->_addContent($this->getLayout()->createBlock('optit/adminhtml_subscription_edit'))
            ->renderLayout();
    }

    public function saveAction()
    {
        $request = $this->getRequest();
        $params = $request->getPost();
        $keywordId = $params['keyword_id'];

        $subscription = Mage::getModel('optit/subscription');

        try {
            $this->_validateRequired($params);
            $this->_checkInterests($params);
            $params = $this->_formatBirthtDate($params);
            $subscription->subscribeMemberToKeyword($keywordId, $params);
            $this->_getSession()->addSuccess($this->__('Subscription saved.'));
            $this->_getSession()->addNotice($this->__('If single opt-in is enabled, subscriber will be visible.'));
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError(nl2br($e->getMessage()));
            $this->_getSession()->addData('form_data',
                $this->getRequest()->getParams());
        }
        $this->_redirect('*/*/', array(
                'keyword_id' => $request->getParam('keyword_id'),
                'keyword_name' => $request->getParam('keyword_name'),
                'type_name' => $request->getParam('type_name'),
            ));
    }

    protected function _validateRequired($params)
    {
        if ($params['subscription'] === 'member_id' && !Zend_Validate::is($params['member_id'], 'NotEmpty')) {
            Mage::throwException('Required parameters not set.');
        }
        if ($params['subscription'] === 'phone' && !Zend_Validate::is($params['phone'], 'NotEmpty')) {
            Mage::throwException('Required parameters not set.');
        }
    }

    public function interestAction()
    {
        $request = $this->getRequest();
        $interestId = $request->getParam('interest_id');

        if (!$interestId) {
            $this->_redirect('*/optit_keyword');
            return;
        }

        Mage::register('filter_id', $interestId);
        Mage::register('filter_type', Inchoo_Optit_Model_Subscription::SUBSCRIPTION_TYPE_INTEREST);
        $this->_initAction();
        $this->renderLayout();

    }

    protected function _checkInterests($params)
    {
        if (isset($params['interest_id'])) {
            $params['interest_id'] = implode(',', $params['interest_id']);
        }
    }

    protected function _formatBirthtDate($params)
    {
        if (isset($params['birth_date'])) {
            $dateTimestamp = Mage::getModel('core/date')->timestamp(strtotime($params['birth_date']));
            $params['birth_date'] = date('Ymd', $dateTimestamp);
        }

        return $params;
    }
}