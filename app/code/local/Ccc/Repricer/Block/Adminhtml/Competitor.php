<?php 

class Ccc_Repricer_Block_Adminhtml_Competitor extends Mage_Adminhtml_Block_Widget_Grid_Container{
    public function __construct(){
        $this->_controller = 'adminhtml_competitor';
        $this->_blockGroup = 'repricer';
        $this->_headerText = Mage::helper('repricer')->__('Manage Competitor');
        $this->_addButtonLabel = Mage::helper('repricer')->__('Add New Competitor');
        parent::__construct();
    }
}