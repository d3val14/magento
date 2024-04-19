<?php 

class Ccc_Repricer_Block_Adminhtml_Matching_Grid_Renderer_Datetime extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $date = $this->_getValue($row);
        if ($date != '') {
            // $date = Mage::app()->getLocale()->date($date, Varien_Date::DATETIME_INTERNAL_FORMAT);
            return $date;
        }
        return '';
    }
}
