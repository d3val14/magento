<?php

class Ccc_Banner_Block_Adminhtml_Banner_Grid_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $imageUrl = Mage::getBaseUrl('media'). $row->getData($this->getColumn()->getIndex());
        return '<img src="' . $imageUrl . '" width="50" height="50" />';
    }
}