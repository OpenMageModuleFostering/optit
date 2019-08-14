<?php


class Inchoo_Optit_Block_Adminhtml_Subscription_Grid_Renderer_Action extends Inchoo_Optit_Block_Adminhtml_Widget_Grid_Column_Renderer_Action
{
    public function render(Varien_Object $row)
    {
        $actions = array();
        if (Mage::registry('filter_type') == Inchoo_Optit_Model_Subscription::SUBSCRIPTION_TYPE_KEYWORD) {
            $actions[] = array(
                'url'     => $this->getUrl('*/optit_subscription/unsubscribe', array(
                    'filter_id' => Mage::registry('filter_id'), 
                    'phone' => $row->getPhone(),
                    'type_name' => $this->getRequest()->getParam('type_name'),
                )),
                'caption' => $this->__('Unsubscribe'),
                'confirm' => $this->__('Are you sure you want to unsubscribe this subscriber?'),
            );
        } elseif (Mage::registry('filter_type') == Inchoo_Optit_Model_Subscription::SUBSCRIPTION_TYPE_INTEREST) {
            $actions[] = array(
                'url'     => $this->getUrl('*/optit_interest/unsubscribe', array(
                    'filter_id' => Mage::registry('filter_id'),
                    'phone' => $row->getPhone(),
                    'interest_id' => $this->getRequest()->getParam('interest_id'),
                    'keyword_id' => $this->_getKeywordId(),
                    'keyword_name' => $this->getRequest()->getParam('keyword_name'),
                    'type_name' => $this->getRequest()->getParam('type_name'),
                )),
                'caption' => $this->__('Unsubscribe'),
                'confirm' => $this->__('Are you sure you want to unsubscribe this subscriber?'),
            );
        }

        $actions[] = array(
            'url'     => $this->getUrl('*/optit_message/sms', array(
                'phone' => $row->getPhone(),
                'id' => $this->_getKeywordId(),
                'type' => Inchoo_Optit_Model_Subscription::SUBSCRIPTION_TYPE_MEMBER,
                'type_name' => $row->getPhone(),
            )),
            'caption' => $this->__('Send Message'),
            'confirm' => $this->__('Are you sure you want to send SMS to this subscriber?'),
        );

        $this->getColumn()->setActions($actions);

        return parent::render($row);
    }

    protected function _getKeywordId()
    {
        return $this->getRequest()->getParam('keyword_id');
    }
}