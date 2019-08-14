<?php


class Inchoo_Optit_Adminhtml_Optit_InterestController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('promo/optit')
            ->_title($this->__('Opt It'))
            ->_title($this->__('Interests'));

        return $this;
    }

    public function indexAction()
    {
        $this->_initAction();
        $request = $this->getRequest();
        $grid = $this->getLayout()->getBlock('optit_interest')->getChild('grid');

        if ($request->getQuery('ajax')) {
            $this->_forward('grid');
            return;
        }

        $params = array('page' => $grid->getParam($grid->getVarNamePage(), 1));

        $keywordId = $request->getParam('keyword_id');
        if (!$keywordId) {
            $this->_redirect('*/optit_keyword');
            return;
        }

        try {
            $data = Mage::getModel('optit/interest')->getAllInterests($keywordId, $params);
            if (!$this->validatePages($data)) {
                $this->_redirect('*/optit_interest', array('keyword_id' => $keywordId));
                return;
            }
            if ($grid) {
                $grid->setData($data);
                $grid->setPage($params);
            }
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError(nl2br($e->getMessage()));
            $this->_initLayoutMessages('adminhtml/session');
        }

        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->loadLayout();
        $grid = $this->getLayout()->getBlock('optit_keyword')->getChild('grid');

        $params = array(
            'page' => $grid->getVarNamePage()
        );

        $keywordId = $this->getRequest()->getParam('keyword_id');

        try {
            $data = Mage::getModel('optit/interest')->getAllInterests($keywordId, $params);
            if ($grid) {
                $grid->setData($data);
            }
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError(nl2br($e->getMessage()));
            $this->_initLayoutMessages('adminhtml/session');
        }
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_initAction();
        $this->_title($this->__('New Interest'));
        $this->_addBreadcrumb($this->__('New Interest'), $this->__('New Interest'))
            ->_addContent($this->getLayout()->createBlock('optit/adminhtml_interest_edit'))
            ->renderLayout();
    }

    public function saveAction()
    {
        $request = $this->getRequest();
        $params = $request->getPost();
        $keywordId = $request->getParam('keyword_id');

        $interest = Mage::getModel('optit/interest');

        try {
            $this->_validateInterestRequired($params);
            $interest->createInterest($keywordId, $params);
            $this->_getSession()->addSuccess($this->__('Interest created.'));
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError(nl2br($e->getMessage()));
            $this->_getSession()->setData('form_data', $params);
        }
        $this->_redirect('*/*/', array(
            'keyword_id' => $request->getParam('keyword_id'),
            'keyword_name' => $request->getParam('keyword_name'),
            'type_name' => $request->getParam('type_name'),
        ));
    }

    public function subscribeAction()
    {
        $this->_initAction();
        $request = $this->getRequest();
        $keywordId = $this->getRequest()->getParam('keyword_id');
        $interestId = $this->getRequest()->getParam('interest_id');
        $phones = Mage::getSingleton('optit/system_config_source_subscription_phone')->toOptionArray($keywordId, $interestId);

        if (empty($phones)) {
            Mage::getSingleton('adminhtml/session')->addError('There are no phone numbers subscribed to a keyword.');
            $this->_redirect('*/optit_subscription/interest', array(
                'interest_id' => $request->getParam('interest_id'),
                'keyword_id' => $request->getParam('keyword_id'),
                'keyword_name' => $request->getParam('keyword_name'),
                'type_name' => $request->getParam('type_name'),
            ));
            return;
        }
        $this->_title($this->__('Subscribe To Interest'));
        $this->_addBreadcrumb($this->__('Subscribe To Interest'), $this->__('Subscribe To Interest'))
            ->_addContent($this->getLayout()->createBlock('optit/adminhtml_subscription_subscribe_edit', 'optit_interest_subscribe'));

        if ($form = $this->getLayout()->getBlock('optit_interest_subscribe')->getChild('form')) {
            $form->setPhones($phones);
        }
        $this->renderLayout();
    }

    public function subscribeSaveAction()
    {
        $request = $this->getRequest();
        $params = $request->getPost();
        $interestId = $request->getParam('interest_id');

        $interest = Mage::getModel('optit/subscription');

        try {
            $this->_validateSubscribeRequired($params);
            $interest->subscribeMemberToInterest($interestId, $params);
            $this->_getSession()->addSuccess($this->__('Member Subscribed'));
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError(nl2br($e->getMessage()));
        }
        $this->_redirect('*/optit_subscription/interest/', array(
            'interest_id' => $request->getParam('interest_id'),
            'keyword_id' => $request->getParam('keyword_id'),
            'keyword_name' => $this->getRequest()->getParam('keyword_name'),
            'type_name' => $this->getRequest()->getParam('type_name'),
        ));
    }

    public function unsubscribeAction()
    {
        $request = $this->getRequest();
        $interestId = $request->getParam('filter_id');
        $phone = $request->getParam('phone');

        $model = Mage::getModel('optit/subscription');
        try {
            $model->unsubscribeMemberFromInterest($interestId, $phone);
            $this->_getSession()->addSuccess($this->__('Member Unsubscribed'));
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError(nl2br($e->getMessage()));
        }
        $this->_redirect('*/optit_subscription/interest', array(
            'interest_id' => $this->getRequest()->getParam('interest_id'),
            'keyword_id' => $this->getRequest()->getParam('keyword_id'),
            'keyword_name' => $this->getRequest()->getParam('keyword_name'),
            'type_name' => $this->getRequest()->getParam('type_name'),
        ));
    }

    protected function _validateSubscribeRequired($params)
    {
        $error = false;
        if (!Zend_Validate::is($params['phone'], 'NotEmpty')) {
            $error = true;
        }

        if ($error) {
            Mage::throwException('Required parameters not set.');
        }
    }

    protected function _validateInterestRequired($params)
    {
        $error = false;
        if (!Zend_Validate::is($params['name'], 'NotEmpty')) {
            $error = true;
        }

        if ($error) {
            Mage::throwException('Required parameters not set.');
        }
    }

    protected function validatePages($data)
    {
        if (Zend_Validate::is($data['current_page'], 'GreaterThan', array('min' => $data['total_pages']))) {
            Mage::getSingleton('adminhtml/session')->unsetData('optit_interest_gridpage');
            $this->_getSession()->addError('Requested page does not exist!');
            return false;
        }
        return true;
    }
}