<?php


class Inchoo_Optit_Block_Adminhtml_Subscription_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('optit_keyword_form');
        $this->setTitle($this->__('New Subscription'));
    }

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getData('action'),
            'method'    => 'post'
        ));

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend'    => $this->__('Subscription'),
            'class'     => 'fieldset',
        ));

        $fieldset->addField('keyword_id', 'hidden', array(
            'name' => 'keyword_id',
            'value'=> $this->_getKeywordId(),
        ));

        $choose = $fieldset->addField('subscription', 'select', array(
            'name'      => 'subscription',
            'label'     => $this->__('Subscription by'),
            'title'     => $this->__('Subscription by'),
            'values'    => array(
                'phone'     => 'Phone',
                'member_id' => 'Member Id'
            ),
        ));

        $phone = $fieldset->addField('phone', 'text', array(
            'name'      => 'phone',
            'label'     => $this->__('Phone'),
            'title'     => $this->__('Phone'),
            'required'  => true,
            'note'      => $this->__('Mobile phone number of the member with country code - 1 for U.S. phone numbers. Example: 12225551212'),
        ));

        $memberId = $fieldset->addField('member_id', 'text', array(
            'name'      => 'member_id',
            'label'     => $this->__('Member Id'),
            'title'     => $this->__('Member Id'),
            'required'  => true,
            'note'      => $this->__('Id number of the member'),
        ));

        $interestId = $fieldset->addField('interest_id', 'multiselect', array(
            'name'      => 'interest_id',
            'label'     => $this->__('Interest'),
            'title'     => $this->__('Interest'),
            'values'    => Mage::getSingleton('optit/system_config_source_subscription_interest')->toOptionArray($this->_getKeywordId()),
            'note'      => $this->__('Add this user to one or many interests.'),
        ));

        $firstName = $fieldset->addField('first_name', 'text', array(
            'name'      => 'first_name',
            'label'     => $this->__('First Name'),
            'title'     => $this->__('First Name'),
        ));

        $lastName = $fieldset->addField('last_name', 'text', array(
            'name'      => 'last_name',
            'label'     => $this->__('Last Name'),
            'title'     => $this->__('Last Name'),
        ));

        $address1 = $fieldset->addField('address1', 'text', array(
            'name'      => 'address1',
            'label'     => $this->__('Address'),
            'title'     => $this->__('Address'),
        ));

        $address2 = $fieldset->addField('address2', 'text', array(
            'name'      => 'address2',
            'label'     => $this->__('Additional Address'),
            'title'     => $this->__('Additional Address'),
        ));

        $city = $fieldset->addField('city', 'text', array(
            'name'      => 'city',
            'label'     => $this->__('City'),
            'title'     => $this->__('City'),
        ));

        $state = $fieldset->addField('state', 'text', array(
            'name'      => 'state',
            'label'     => $this->__('State'),
            'title'     => $this->__('State'),
            'note' => $this->__('State of the member as a two character abbreviation'),
        ));

        $zip = $fieldset->addField('zip', 'text', array(
            'name'      => 'zip',
            'label'     => $this->__('Zip'),
            'title'     => $this->__('Zip'),
        ));

        $gender = $fieldset->addField('gender', 'select', array(
            'name'      => 'gender',
            'label'     => $this->__('Gender'),
            'title'     => $this->__('Gender'),
            'values'    => Mage::getSingleton('optit/system_config_source_customer_gender')->toOptionArray(),
        ));

        $birthDate = $fieldset->addField('birth_date', 'date', array(
            'name'      => 'birth_date',
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
            'label'     => $this->__('Birth Date'),
            'title'     => $this->__('Birth Date'),
            'format'    => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT)
        ));

        $email = $fieldset->addField('email_address', 'text', array(
            'name'      => 'email_address',
            'label'     => $this->__('Email'),
            'title'     => $this->__('Email'),
            'class'     => 'validate-email',
        ));

        $form->setUseContainer(true);
        $this->setForm($form);
        $this->setChild('form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
            ->addFieldMap($choose->getHtmlId(), $choose->getName())
            ->addFieldMap($phone->getHtmlId(), $phone->getName())
            ->addFieldMap($interestId->getHtmlId(), $interestId->getName())
            ->addFieldMap($firstName->getHtmlId(), $firstName->getName())
            ->addFieldMap($lastName->getHtmlId(), $lastName->getName())
            ->addFieldMap($address1->getHtmlId(), $address1->getName())
            ->addFieldMap($address2->getHtmlId(), $address2->getName())
            ->addFieldMap($city->getHtmlId(), $city->getName())
            ->addFieldMap($state->getHtmlId(), $state->getName())
            ->addFieldMap($zip->getHtmlId(), $zip->getName())
            ->addFieldMap($gender->getHtmlId(), $gender->getName())
            ->addFieldMap($birthDate->getHtmlId(), $birthDate->getName())
            ->addFieldMap($email->getHtmlId(), $email->getName())
            ->addFieldMap($memberId->getHtmlId(), $memberId->getName())
            ->addFieldDependence(
                $phone->getName(),
                $choose->getName(),
                'phone'
            )
            ->addFieldDependence(
                $interestId->getName(),
                $choose->getName(),
                'phone'
            )
            ->addFieldDependence(
                $firstName->getName(),
                $choose->getName(),
                'phone'
            )
            ->addFieldDependence(
                $lastName->getName(),
                $choose->getName(),
                'phone'
            )
            ->addFieldDependence(
                $address1->getName(),
                $choose->getName(),
                'phone'
            )
            ->addFieldDependence(
                $address2->getName(),
                $choose->getName(),
                'phone'
            )
            ->addFieldDependence(
                $city->getName(),
                $choose->getName(),
                'phone'
            )
            ->addFieldDependence(
                $state->getName(),
                $choose->getName(),
                'phone'
            )
            ->addFieldDependence(
                $zip->getName(),
                $choose->getName(),
                'phone'
            )
            ->addFieldDependence(
                $gender->getName(),
                $choose->getName(),
                'phone'
            )
            ->addFieldDependence(
                $birthDate->getName(),
                $choose->getName(),
                'phone'
            )
            ->addFieldDependence(
                $email->getName(),
                $choose->getName(),
                'phone'
            )
            ->addFieldDependence(
                $memberId->getName(),
                $choose->getName(),
                'member_id'
            )
        );

        return parent::_prepareForm();
    }

    protected function _getKeywordId()
    {
        return $this->getRequest()->getParam('keyword_id');
    }
}