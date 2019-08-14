<?php

class Inchoo_Optit_Block_Adminhtml_Interest_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('optit_interest_form');
        $this->setTitle($this->__('New Interest'));
    }

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getData('action'),
            'method'    => 'post'
        ));

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend'    => $this->__('Interest'),
            'class'     => 'fieldset',
        ));

        $fieldset->addField('keyword_id', 'hidden', array(
            'name' => 'keyword_id',
            'value'=> $this->getRequest()->getParam('keyword_id'),
        ));

        $fieldset->addField('name', 'text', array(
            'name'      => 'name',
            'label'     => $this->__('Name'),
            'title'     => $this->__('Name'),
            'required'  => true,
        ));

        $fieldset->addField('description', 'text', array(
            'name'      => 'description',
            'label'     => $this->__('Description'),
            'title'     => $this->__('Description'),
        ));

        if ($data = Mage::getSingleton('adminhtml/session')->getFormData()) {
            $form->setValues($data);
            Mage::getSingleton('adminhtml/session')->setFormData(null);
        }

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}