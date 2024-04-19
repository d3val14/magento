<?php

class Ccc_Repricer_Model_Matching extends Mage_Core_Model_Abstract{
    const REASON_DEFAULT = 0;
    protected function _construct(){
        $this->_init('repricer/matching');
    }
    public function getReasonOptionArray(){
        return array(
            0   => Mage::helper('repricer')->__('No Match'),
            1   => Mage::helper('repricer')->__('Active'),
            2   => Mage::helper('repricer')->__('Out of Stock'),
            3   => Mage::helper('repricer')->__('Not Available'),
            4   => Mage::helper('repricer')->__('Wrong Match'),
        );
    }

    protected function _beforeSave()
{
 
    if ($this->getData('reason') == 0 || $this->getData('reason') == 3 ) {
        $competitorUrl = $this->getData('competitor_url');
        $competitorSku = $this->getData('competitor_sku');
        $price = $this->getData('competitor_price');

        if (!empty($competitorUrl) && !empty($competitorSku) && $price == 0) {
            // Set reason to 3 (Not Available)
            $this->setData('reason', 3);
        } elseif (!empty($competitorUrl) && !empty($competitorSku) && $price != 0) {
            // Set reason to 1 (Active)
            $this->setData('reason', 1);
        }
    }

    return parent::_beforeSave();
}
}