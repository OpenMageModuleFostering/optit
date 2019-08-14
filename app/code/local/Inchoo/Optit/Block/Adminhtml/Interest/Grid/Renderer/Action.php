<?php

class Inchoo_Optit_Block_Adminhtml_Interest_Grid_Renderer_Action extends Inchoo_Optit_Block_Adminhtml_Widget_Grid_Column_Renderer_Action
{
    public function render(Varien_Object $row)
    {
        $actions = array();
        $actions[] = array(
            'url'     => $this->getUrl('*/optit_subscription/interest', array(
                'interest_id' => $row->getId(), 
                'keyword_id' => $this->getRequest()->getParam('keyword_id'),
                'keyword_name' => $this->getRequest()->getParam('keyword_name'),
                'type_name' => $row->getName()
            )),
            'caption' => $this->__('View Subscriptions')
        );

        $actions[] = array(
            'url'     => $this->getUrl('*/optit_interest/subscribe', array(
                'interest_id' => $row->getId(), 
                'keyword_id' => $this->getRequest()->getParam('keyword_id'),
                'keyword_name' => $this->getRequest()->getParam('keyword_name'),
                'type_name' => $row->getName(),
            )),
            'caption' => $this->__('Subscribe a Member')
        );

        if ($row->getStatus() == 'Active') {
            $actions[] = array(
                'url'     => $this->getUrl('*/optit_message/sms', array(
                    'id' => $row->getId(),
                    'type' => Inchoo_Optit_Model_Subscription::SUBSCRIPTION_TYPE_INTEREST,
                    'keyword_name' => $this->getRequest()->getParam('keyword_name'),
                    'type_name' => $row->getName(),
                )),
                'caption' => $this->__('Send Message'),
                'confirm' => $this->__('Are you sure you want to send SMS to these subscribers?'),
            );
        }

        $this->getColumn()->setActions($actions);

        return parent::render($row);
    }
}