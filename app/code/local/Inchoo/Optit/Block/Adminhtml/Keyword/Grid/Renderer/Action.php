<?php


class Inchoo_Optit_Block_Adminhtml_Keyword_Grid_Renderer_Action extends Inchoo_Optit_Block_Adminhtml_Widget_Grid_Column_Renderer_Action
{
    public function render(Varien_Object $row)
    {
        $actions = array();
        $actions[] = array(
            'url'     => $this->getUrl('*/optit_keyword/edit', array(
                'id' => $row->getId(),
                'keyword_name' => $row->getKeywordName(),
            )),
            'caption' => $this->__('Edit'),
        );

        $actions[] = array(
            'url'     => $this->getUrl('*/optit_subscription/', array(
                'keyword_id' => $row->getId(),
                'type_name' => $row->getKeywordName(),
            )),
            'caption' => $this->__('View Subscriptions'),
        );

        $actions[] = array(
            'url'     => $this->getUrl('*/optit_interest/', array('keyword_id' => $row->getId(), 'keyword_name' => $row->getKeywordName())),
            'caption' => $this->__('View Interests'),
        );

        if ($row->getStatus() == 'Active') {
            $actions[] = array(
                'url'     => $this->getUrl('*/optit_message/sms', array(
                    'id' => $row->getId(),
                    'type' => Inchoo_Optit_Model_Subscription::SUBSCRIPTION_TYPE_KEYWORD,
                    'keyword_name' => $row->getKeywordName(),
                )),
                'caption' => $this->__('Send SMS'),
                'confirm' => $this->__('Are you sure you want to send SMS to these subscribers?'),
            );

            $actions[] = array(
                'url'     => $this->getUrl('*/optit_message/mms', array(
                    'id' => $row->getId(),
                    'type' => Inchoo_Optit_Model_Subscription::SUBSCRIPTION_TYPE_KEYWORD,
                    'keyword_name' => $row->getKeywordName(),
                )),
                'caption' => $this->__('Send MMS'),
                'confirm' => $this->__('Are you sure you want to send MMS to these subscribers?'),
            );
        }

        $this->getColumn()->setActions($actions);

        return parent::render($row);
    }
}