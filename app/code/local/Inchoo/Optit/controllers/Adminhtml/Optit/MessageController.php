<?php


class Inchoo_Optit_Adminhtml_Optit_MessageController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('promo/optit')
            ->_title($this->__('Opt It'))
            ->_title($this->__('Message'));

        return $this;
    }

    public function indexAction()
    {
        $this->_initAction();

        try {
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError(nl2br($e->getMessage()));
            $this->_initLayoutMessages('adminhtml/session');
        }
        $this->renderLayout();
    }

    public function smsAction()
    {
        $this->_initAction();
        $this->_title('SMS');
        $params = $this->getRequest()->getParams();

        try {
            $data = Mage::getSingleton('optit/system_config_source_message_keyword')->toOptionArray(false, true);
            $form = $this->getLayout()->getBlock('optit_send_sms')->getChild('form');
            if ($form) {
                $form->setKeywords($data);
                Mage::register('params', $params);
            }
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError(nl2br($e->getMessage()));
            $this->_initLayoutMessages('adminhtml/session');
        }

        $this->renderLayout();
    }

    public function mmsAction()
    {
        $this->_initAction();
        $this->_title('MMS');
        $params = $this->getRequest()->getParams();

        try {
            $data = Mage::getSingleton('optit/system_config_source_message_keyword')->toOptionArray();
            $form = $this->getLayout()->getBlock('optit_send_mms')->getChild('form');
            if ($form) {
                $form->setKeywords($data);
                Mage::register('params', $params);
            }
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError(nl2br($e->getMessage()));
            $this->_initLayoutMessages('adminhtml/session');
        }

        $this->renderLayout();
    }

    public function sendSmsAction()
    {
        $request = $this->getRequest();
        $type = $request->getPost('type');
        $post = $request->getPost();

        if (!$request->isPost()) {
            $this->getResponse()->setRedirect($this->getUrl('*/*/', array('id' => $post['id'], 'type' => $type)));
        }

        if ($request->getParam('bulk')) {
            $this->_forward('bulk');
            return;
        }

        $message = Mage::getModel('optit/message');

        try {
            $params = $this->_prepareForSend($post);
            if ($type == Inchoo_Optit_Model_Subscription::SUBSCRIPTION_TYPE_INTEREST) {
                $message->sendMessageToInterest($params);
            } elseif ($type == Inchoo_Optit_Model_Subscription::SUBSCRIPTION_TYPE_MEMBER) {
                $message->sendMessageToMember($params);
            } else {
                $message->sendMessageToKeyword($params);
            }
            $this->_getSession()->addSuccess($this->__('Message sent.'));
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError(nl2br($e->getMessage()));
            $this->_getSession()->setData('form_data', $post);
        }

        $this->_redirectReferer();
    }

    public function sendMmsAction()
    {
        $request = $this->getRequest();
        $type = $request->getPost('type');
        $params = $this->_prepareForSend($request->getPost());

        if (!$request->isPost()) {
            $this->getResponse()->setRedirect($this->getUrl('*/*/', array('id' => $params['id'], 'type' => $type)));
        }

        if ($request->getParam('bulk')) {
            $this->_forward('bulk');
            return;
        }

        $destinationFolder = Mage::getBaseDir('media') . DS . 'optit';

        $message = Mage::getModel('optit/message');

        try {
            $result = $this->_upload('content_url', $destinationFolder);
            $params['content_url'] = Mage::getBaseUrl('media') .
                Inchoo_Optit_Model_Message::MMS_MEDIA_DIRECTORY . $result['file'];
            $message->sendMmsToKeyword($params);
            $this->_getSession()->addSuccess($this->__('Message sent.'));
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError(nl2br($e->getMessage()));
            $this->_getSession()->setData('form_data', $params);
        } catch (Exception $e) {
            $this->_getSession()->addError(nl2br($e->getMessage()));
            $this->_getSession()->setData('form_data', $params);
        }

        $this->_redirectReferer();
    }

    public function bulkAction()
    {
        $request = $this->getRequest();
        $params = $this->_prepareForSave($request->getPost());
        $destinationFolder = Mage::getBaseDir('media') . DS . Inchoo_Optit_Model_Message::MMS_MEDIA_DIRECTORY;

        $message = Mage::getModel('optit/message');

        try {
            if ($params['message_type'] != Inchoo_Optit_Model_Message::MESSAGE_TYPE_SMS) {
                $result = $this->_upload('content_url', $destinationFolder);
                $params['content_url'] = Mage::getBaseUrl('media') .
                    Inchoo_Optit_Model_Message::MMS_MEDIA_DIRECTORY . $result['file'];
            }
            $message->setData($params);
            $message->save();
            $this->_getSession()->addSuccess($this->__('Message added to bulk list.'));
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError(nl2br($e->getMessage()));
            $this->_getSession()->setData('form_data', $params);
        } catch (Exception $e) {
            $this->_getSession()->addError(nl2br($e->getMessage()));
            $this->_getSession()->setData('form_data', $params);
        }

        $this->_redirectReferer();
    }

    protected function _prepareForSend($params)
    {
        if ($params['type'] == Inchoo_Optit_Model_Subscription::SUBSCRIPTION_TYPE_INTEREST) {
            $params['interest_id'] = $params['id'];
        } else {
            $params['keyword_id'] = $params['id'];
        }

        unset($params['form_key'], $params['id'], $params['type'],$params['message_type']);

        return $params;
    }

    protected function _prepareForSave($params)
    {
        $params['entity_id'] = $params['id'];
        $params['entity_type_code'] = $params['type'];

        return $params;
    }

    protected function _upload($fieldId, $destinationFolder)
    {
        $uploader = new Mage_Core_Model_File_Uploader($fieldId);
        $uploader->setAllowedExtensions(
            array('jpg', 'jpeg', 'png', 'gif', 'vnd', 'wap', 'wbpm', 'bpm', 'amr', 'x-wav', 'aac', 'qcp', '3gpp', '3gpp2')
        );
        $uploader->addValidateCallback('optit_send_mms',
            Mage::helper('catalog/image'), 'validateUploadFile');
        $uploader->setAllowRenameFiles(true);
        $uploader->setFilesDispersion(true);
        $result = $uploader->save($destinationFolder);

        return $result;
    }
}