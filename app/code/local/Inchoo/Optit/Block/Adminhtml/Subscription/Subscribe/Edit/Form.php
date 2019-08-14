<?php

class Inchoo_Optit_Block_Adminhtml_Subscription_Subscribe_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('optit_subscription_subscribe_form');
        $this->setTitle($this->__('New Subscription'));
    }

    protected function _prepareForm()
    {
        $keywordId = $this->_getKeywordId();
        $interestId = $this->getRequest()->getParam('interest_id');
        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action' => $this->getData('action'),
            'method'    => 'post',
        ));

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend'    => $this->__('Subscription'),
            'class'     => 'fieldset',
        ));

        $fieldset->addField('interest_id', 'hidden', array(
            'name' => 'interest_id',
            'value'=> $interestId,
        ));

        $fieldset->addField('keyword_id', 'hidden', array(
            'name' => 'keyword_id',
            'value'=> $keywordId,
        ));

        $fieldset->addField('phone', 'select', array(
            'name'      => 'phone',
            'label'     => $this->__('Phone Number'),
            'title'     => $this->__('Phone Number'),
            'values'    => $this->getData('phones'),
        ));

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    protected function _getKeywordId()
    {
        $a = $this->getRequest()->getParam('keyword_id');
        return $a;
    }
}