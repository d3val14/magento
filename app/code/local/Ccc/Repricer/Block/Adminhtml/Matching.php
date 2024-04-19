<?php

class Ccc_Repricer_Block_Adminhtml_Matching extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_matching';
        $this->_blockGroup = 'repricer';
        $this->_headerText = Mage::helper('repricer')->__('Manage Repricer');
        $this->_addButtonLabel = Mage::helper('repricer')->__('Add New Repricer');

        parent::__construct();

        $this->_removeButton('add');
    }

}
