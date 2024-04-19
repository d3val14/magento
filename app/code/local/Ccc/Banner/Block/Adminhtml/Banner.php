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

class Ccc_Banner_Block_Adminhtml_Banner extends Mage_Adminhtml_Block_Widget_Grid_Container
{

	public function __construct()
	{
		$this->_controller = 'adminhtml_banner';
		$this->_blockGroup = 'banner';
		$this->_headerText = $this->__('Banner Slider');
		$this->_addButtonLabel = $this->__('New Banner');
		
		parent::__construct();
		if(!Mage::getSingleton('admin/session')->isAllowed('banner/actions/showbutton')){
            $this->_removeButton('add');
		}
	}

}
