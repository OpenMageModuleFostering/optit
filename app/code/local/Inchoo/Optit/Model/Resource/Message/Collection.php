<?php


class Inchoo_Optit_Model_Resource_Message_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('optit/message');
    }

    public function addMessageRecipients()
    {
        $this->getSelect()
            ->joinLeft(
                array('phones' => $this->getTable('optit/bulk_message_phones')),
                'phones.message_id=main_table.message_id',
                array('recipients' => 'GROUP_CONCAT(phones.phone_number)'))
            ->group('main_table.message_id');

        return $this;
    }

    protected function _prepareForXml()
    {
        $data = array();
        if (!$this->getSize()) {
            Mage::throwException('No messages to send.');
        }

        foreach ($this as $key => $item) {
            $data[$item->getEntityId()][$key] = array(
                'message' => $item->getMessage(),
                'phones'  => explode(',', $item->getRecipients()),
                'title'   => $item->getTitle(),
            );
            if ($contentUrl = $item->getContentUrl()) {
                $data[$item->getEntityId()][$key] = array_merge(
                    $data[$item->getEntityId()][$key],
                    array('content_url' => $contentUrl)
                );
            }
        }

        $this->_data = $data;
    }

    public function toSmsXml()
    {
        $this->_prepareForXml();
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><keywords/>');
        foreach ($this->_data as $keywordId => $messages) {
            $keywordObj = $xml->addChild('keyword');
            $keywordObj->addAttribute('id', $keywordId);
            $messagesObj = $keywordObj->addChild('messages');
            foreach ($messages as $message) {
                $messageObj = $messagesObj->addChild('message');
                $messageObj->addAttribute('title', $message['title']);
                $messageObj->addAttribute('text', $message['message']);
                $recipientsObj = $messageObj->addChild('recipients');
                foreach ($message['phones'] as $phone) {
                    $recipientsObj->addChild('phone', $phone);
                }
            }
        }

        return $xml->asXML();
    }

    public function toMmsXml()
    {
        $this->_prepareForXml();
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><keywords/>');
        foreach ($this->_data as $keywordId => $messages) {
            $keywordObj = $xml->addChild('keyword');
            $keywordObj->addAttribute('id', $keywordId);
            $messagesObj = $keywordObj->addChild('messages');
            foreach ($messages as $message) {
                $messageObj = $messagesObj->addChild('message');
                $messageObj->addAttribute('title', $message['title']);
                $messageObj->addAttribute('text', $message['message']);
                if ($message['content_url']) {
                    $messageObj->addAttribute('content_url', $message['content_url']);
                }
                $recipientsObj = $messageObj->addChild('recipients');
                foreach ($message['phones'] as $phone) {
                    $recipientsObj->addChild('phone', $phone);
                }
            }
        }

        return $xml->asXML();
    }

    public function clear()
    {
        foreach ($this as $item) {
            $item->delete();
        }
    }

}