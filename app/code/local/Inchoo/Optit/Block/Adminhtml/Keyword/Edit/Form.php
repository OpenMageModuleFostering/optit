<?php


class Inchoo_Optit_Block_Adminhtml_Keyword_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('optit_keyword_form');
        $this->setTitle($this->__('Keyword Edit'));
    }

    protected function _prepareForm()
    {
        $model = Mage::registry('optit_keyword');

        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getData('action'),
            'method'    => 'post'
        ));

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend'    => $this->__('Keyword'),
            'class'     => 'fieldset',
        ));

        $fieldset->addField('billing_type', 'select', array(
            'name'      => 'billing_type',
            'label'     => $this->__('Billing Type'),
            'title'     => $this->__('Billing Type'),
            'values'    => Mage::getSingleton('optit/system_config_source_billing_type')->toOptionArray(),
            'required'  => true,
        ));

        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', array(
                'name' => 'id',
            ));

            $fieldset->addField('keyword_name', 'text', array(
                'name'      => 'keyword_name',
                'label'     => $this->__('Keyword Name'),
                'title'     => $this->__('Keyword Name'),
                'disabled'  => true,
            ));
        } else {
            $fieldset->addField('keyword', 'text', array(
                'name'      => 'keyword',
                'label'     => $this->__('Keyword Name'),
                'title'     => $this->__('Keyword Name'),
                'required'  => true,
            ));
        }

        $fieldset->addField('internal_name', 'text', array(
            'name'      => 'internal_name',
            'label'     => $this->__('Internal Name'),
            'title'     => $this->__('Internal Name'),
            'required'  => true,
        ));

        // TODO: add interest field when interest is associated with keyword

        $fieldset->addField('welcome_msg_type', 'select', array(
            'name'      => 'welcome_msg_type',
            'label'     => $this->__('Welcome Message Type'),
            'title'     => $this->__('Welcome Message Type'),
            'values'    => Mage::getSingleton('optit/system_config_source_web_form_message_type')->toOptionArray()
        ));

        $fieldset->addField('welcome_msg', 'textarea', array(
            'name'      => 'welcome_msg',
            'label'     => $this->__('Welcome Message'),
            'title'     => $this->__('Welcome Message'),
            'class'     => 'validate-length maximum-length-93',
            'note' => $this->__('Maximum length %d characters', 160)

        ));

        $fieldset->addField('web_form_verification_msg_type', 'select', array(
            'name'      => 'web_form_verification_msg_type',
            'label'     => $this->__('Web Form Verification Message Type'),
            'title'     => $this->__('Web Form Verification Message Type'),
            'values'    => Mage::getSingleton('optit/system_config_source_web_form_message_type')->toOptionArray()
        ));

        $fieldset->addField('web_form_verification_msg', 'textarea', array(
            'name'      => 'web_form_verification_msg',
            'label'     => $this->__('Web Form Verification Message'),
            'title'     => $this->__('Web Form Verification Message'),
            'class'     => 'validate-length maximum-length-120',
            'note' => $this->__('Maximum length %d characters', 120)

        ));

        $fieldset->addField('already_subscribed_msg_type', 'select', array(
            'name'      => 'already_subscribed_msg_type',
            'label'     => $this->__('Already Subscribed Message Type'),
            'title'     => $this->__('Already Subscribed Message Type'),
            'values'    => Mage::getSingleton('optit/system_config_source_subscribed_message_type')->toOptionArray()
        ));

        $fieldset->addField('already_subscribed_msg', 'textarea', array(
            'name'      => 'already_subscribed_msg',
            'label'     => $this->__('Already Subscribed Message'),
            'title'     => $this->__('Already Subscribed Message'),
            'class'     => 'validate-length maximum-length-160',
            'note' => $this->__('Maximum length %d characters', 160)
        ));

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}