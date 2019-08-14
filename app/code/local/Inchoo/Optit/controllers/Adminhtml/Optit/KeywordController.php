<?php


class Inchoo_Optit_Adminhtml_Optit_KeywordController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('promo/optit')
            ->_title($this->__('Opt It'))
            ->_title($this->__('Keywords'));

        return $this;
    }

    public function indexAction()
    {
        $this->_initAction();
        if ($this->getRequest()->getQuery('ajax')) {
            $this->_forward('grid');
            return;
        }

        try {
            $data = Mage::getModel('optit/keyword')->getAllKeywords();
            $grid = $this->getLayout()->getBlock('optit_keyword')->getChild('grid');
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
        $this->_forward('edit');
    }
    
    public function editAction()
    {
        $this->_initAction();

        $id  = $this->getRequest()->getParam('id');
        $model = Mage::getModel('optit/keyword');

        if ($id) {
            $model->loadKeyword($id);

            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('This keyword no longer exists.'));
                $this->_redirect('*/*/');

                return;
            }
        }

        $this->_title($model->getId() ? $model->getKeywordName() : $this->__('New Keyword'));
        $data = Mage::getSingleton('adminhtml/session')->getKeywordData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('optit_keyword', $model);
        $this->_addBreadcrumb($id ? $this->__('Edit Keyword') : $this->__('New Keyword'), $id ? $this->__('Edit Keyword') : $this->__('New Keyword'))
            ->_addContent($this->getLayout()->createBlock('optit/adminhtml_keyword_edit'))
            ->renderLayout();
    }

    public function saveAction()
    {
        $request = $this->getRequest();
        $params = $request->getParams();
        $keywordId = $request->getParam('id');

        $keyword = Mage::getModel('optit/keyword');

        try {
            if (!$keywordId) {
                // new
                $this->_validateNewRequired($params);
                $keyword->setData('keyword', $params['keyword']);
            } else {
                // edit
                $this->_validateEditRequired($params);
                $keyword->loadKeyword($keywordId);
            }

            $keyword->saveKeyword($params);
            $this->_getSession()->addSuccess($this->__('Keyword saved.'));
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError(nl2br($e->getMessage()));
            $this->_getSession()->addData('keyword_data',
                $this->getRequest()->getParams());
        }
        $this->_redirect('*/*/');
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    protected function _validateNewRequired($params)
    {
        if (!Zend_Validate::is($params['billing_type'], 'NotEmpty') ||
            !Zend_Validate::is($params['keyword'], 'NotEmpty') ||
            !Zend_Validate::is($params['internal_name'], 'NotEmpty')
        ) {
            Mage::throwException('Required parameters not set.');
        }
    }

    protected function _validateEditRequired($params)
    {
        if (!Zend_Validate::is($params['billing_type'], 'NotEmpty') ||
            !Zend_Validate::is($params['internal_name'], 'NotEmpty')
        ) {
            Mage::throwException('Required parameters not set.');
        }
    }

}