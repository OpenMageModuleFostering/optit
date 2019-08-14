<?php


class Inchoo_Optit_Block_Adminhtml_System_Config_Field_Keyword extends
    Mage_Adminhtml_Block_System_Config_Form_Field
{
    const REQUEST_PARAM_KEYWORD = 'keyword';

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $configDataModel = Mage::getSingleton('adminhtml/config_data');
        $urlParams = array(
            'section' => $configDataModel->getSection(),
            'website' => $configDataModel->getWebsite(),
            'store' => $configDataModel->getStore(),
            self::REQUEST_PARAM_KEYWORD => '__keyword__',
        );
        $urlString = $this->helper('core')
            ->jsQuoteEscape(Mage::getModel('adminhtml/url')->getUrl('*/*/*', $urlParams));
        $jsString = '
            $("' . $element->getHtmlId() . '").observe("change", function () {
                location.href = \'' . $urlString . '\'.replace("__keyword__", this.value);
            });
        ';

        return parent::_getElementHtml($element) . $this->helper('adminhtml/js')
            ->getScript('document.observe("dom:loaded", function() {' . $jsString . '});');
    }

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $keyword = $this->getRequest()->getParam(self::REQUEST_PARAM_KEYWORD);
        if ($keyword) {
            $element->setValue($keyword);
        }

        return parent::render($element);
    }
}