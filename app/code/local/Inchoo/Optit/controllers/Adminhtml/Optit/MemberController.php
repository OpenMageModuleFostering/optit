<?php


class Inchoo_Optit_Adminhtml_Optit_MemberController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('promo/optit')
            ->_title($this->__('Opt It'))
            ->_title($this->__('Members'));

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
            $data = Mage::getModel('optit/member')->getAllMembers();
            $grid = $this->getLayout()->getBlock('optit_member')->getChild('grid');
            if ($grid) {
                $grid->setData($data);
            }
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError(nl2br($e->getMessage()));
            $this->_initLayoutMessages('adminhtml/session');
        }

        $this->renderLayout();
    }

    public function unsubscribeAction()
    {
        $request = $this->getRequest();
        $phone = $request->getParam('phone');

        $model = Mage::getModel('optit/subscription');

        try {
            $model->unsubscriberMemberFromAllKeywords($phone);
            $this->_getSession()->addSuccess($this->__('Member successfully unsubscribed from all keywords.'));
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError(nl2br($e->getMessage()));
            $this->_getSession()->addError("{$phone} could not be unsubscribed. Maybe it did not have any associated subscriptions.");
        }
        $this->_redirect('*/*/');
    }
}