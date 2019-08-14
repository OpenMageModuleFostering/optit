<?php


class Inchoo_Optit_Adminhtml_Optit_BulkController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('promo/optit')
            ->_title($this->__('Opt It'))
            ->_title($this->__('Bulk Messaging'));

        return $this;
    }

    public function smsAction()
    {
        $this->_initAction();
        if ($this->getRequest()->getQuery('ajax')) {
            $this->_forward('smsGrid');
            return;
        }

        $this->_title('SMS');
        $this->renderLayout();
    }

    public function sendSmsAction()
    {
        try {
            $collection = Mage::getModel('optit/message')->getCollection()
                ->addFieldToFilter('message_type', array('eq' => Inchoo_Optit_Model_Message::MESSAGE_TYPE_SMS))
                ->addMessageRecipients();

            $model = Mage::getModel('optit/message');

            $params = array(
                'data' => $collection->toSmsXml()
            );

            $model->sendBulk($params);
            $collection->clear();
            $this->_getSession()->addSuccess('Messages successfully sent.');
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError(nl2br($e->getMessage()));
        }

        $this->_redirectReferer();
    }

    public function smsGridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function mmsAction()
    {
        $this->_initAction();
        $this->_title('MMS');
        if ($this->getRequest()->getQuery('ajax')) {
            $this->_forward('mmsGrid');
            return;
        }

        $this->renderLayout();
    }

    public function sendMmsAction()
    {
        try {
            $collection = Mage::getModel('optit/message')->getCollection()
                ->addFieldToFilter('message_type', array('eq' => Inchoo_Optit_Model_Message::MESSAGE_TYPE_MMS))
                ->addMessageRecipients();

            $model = Mage::getModel('optit/message');

            $params = array(
                'data' => $collection->toMmsXml()
            );

            $model->sendBulk($params);
            $collection->clear();
            $this->_getSession()->addSuccess('Messages successfully sent.');
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError(nl2br($e->getMessage()));
        }

        $this->_redirectReferer();
    }

    public function mmsGridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function clearAction()
    {
        $type = $this->getRequest()->getParam('type');

        try {
            $collection = Mage::getModel('optit/message')->getCollection()
                ->addFieldToFilter('message_type', array('eq' => $type))
                ->addMessageRecipients();

            $collection->clear();
            $this->_getSession()->addSuccess('List cleared.');
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError(nl2br($e->getMessage()));
        }

        $this->_redirectReferer();
    }
}