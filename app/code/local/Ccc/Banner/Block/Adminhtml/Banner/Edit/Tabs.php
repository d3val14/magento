<?php

/**
 * @category 	  Upment
 * @package 	  Upment_Bannerslider
 * @copyright 	Copyright (c) 2019 Upment (https://www.upment.com/)
 */

/**
 * Bannerslider Edit Tabs Block
 *
 * @category 	Upment
 * @package 	Upment_Bannerslider
 * @author  	Upment
 */

class Upment_Bannerslider_Block_Adminhtml_Bannerslider_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('bannerslider_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('bannerslider')->__('Banner Manager'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('bannerslider')->__('Banner Details'),
          'title'     => Mage::helper('bannerslider')->__('Banner Details'),
          'content'   => $this->getLayout()->createBlock('bannerslider/adminhtml_bannerslider_edit_tab_form')->toHtml(),
      ));

      return parent::_beforeToHtml();
  }
}
