<?php

/**
 * @category   	Upment
 * @package   	Upment_Bannerslider
 * @copyright 	Copyright (c) 2019 Upment (https://www.upment.com/)
 */

/**
 * Bannerslider Adminhtml Block
 *
 * @category 	Upment
 * @package 	Upment_Bannerslider
 * @author  	Upment
 */

class Ccc_Repricer_Block_Adminhtml_Matching extends Mage_Adminhtml_Block_Widget_Grid_Container
{

	public function __construct()
	{
		echo 1123456;
		$this->_controller = 'adminhtml_repricer';
		$this->_blockGroup = 'repricer';
		$this->_headerText = $this->__('Repricer');
		$this->_addButtonLabel = $this->__('New Competitor');
		
		parent::__construct();
		// if(!Mage::getSingleton('admin/session')->isAllowed('banner/actions/showbutton')){
        //     $this->_removeButton('add');
		// }
	}

}
