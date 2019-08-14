<?php


class Inchoo_Optit_Block_Adminhtml_Message_Mms_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('optit_message_mms');
        $this->setTitle($this->__('New Message'));
    }

    protected function _prepareForm()
    {
        $params = Mage::registry('params');
        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getData('action'),
            'method'    => 'post',
            'enctype' => 'multipart/form-data',
        ));

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend'    => $this->__('MMS'),
            'class'     => 'fieldset',
        ));

        $fieldset->addField('message_type', 'hidden', array(
            'name' => 'message_type',
            'value'=> Inchoo_Optit_Model_Message::MESSAGE_TYPE_MMS,
        ));

        if (isset($params['type'])) {
            $fieldset->addField('id', 'hidden', array(
                'name' => 'id',
                'value'=> $params['id'],
            ));

            if ($params['type'] == Inchoo_Optit_Model_Subscription::SUBSCRIPTION_TYPE_MEMBER) {
                $fieldset->addField('phone', 'hidden', array(
                    'name' => 'phone',
                    'value'=> $params['phone'],
                ));
            }

            $fieldset->addField('type', 'hidden', array(
                'name' => 'type',
                'value'=> $params['type'],
            ));
        } else {
            $fieldset->addField('id', 'select', array(
                'name'      => 'id',
                'label'     => $this->__('Keyword Id'),
                'title'     => $this->__('Keyword Id'),
                'required'  => true,
                'values'    => $this->getKeywords(),
                'note'      => $this->__('Please choose a keyword'),
            ));

            $fieldset->addField('type', 'hidden', array(
                'name' => 'type',
                'value'=> 'keyword',
            ));
        }

        $fieldset->addField('title', 'text', array(
            'name'      => 'title',
            'label'     => $this->__('Title'),
            'title'     => $this->__('Title'),
            'required'  => true,
            'note'      => $this->__('Please enter a title of message. This does not appear in the text message and is just used in the application as a short description of your message.'),
        ));

        $fieldset->addField('message', 'textarea', array(
            'name'      => 'message',
            'label'     => $this->__('Message'),
            'title'     => $this->__('Message'),
            'required'  => true,
            'note'      => $this->__('Please enter a text message. The message must be less than 160 characters including your keyword in the beginning of the message.'),
            'class'     => 'validate-length maximum-length-160'
        ));

        $fieldset->addField('content_url', 'file', array(
            'name'      => 'content_url',
            'label'     => $this->__('Media'),
            'title'     => $this->__('Media'),
            'note'      => $this->__('Upload a media file to be sent with MMS. Allowed extensions: jpg, jpeg, png, gif, vnd, wap, wbpm, bpm, amr, x-wav, aac, qcp, 3gpp, 3gpp2'),
        ));

        if ($data = Mage::getSingleton('adminhtml/session')->getFormData()) {
            $form->setValues($data);
            Mage::getSingleton('adminhtml/session')->setFormData(null);
        }

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    protected function getType()
    {
        return $this->getRequest()->getParam('type');
    }
}