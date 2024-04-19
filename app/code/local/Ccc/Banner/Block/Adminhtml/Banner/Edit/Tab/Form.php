<?php

/**
 * @category 	  Upment
 * @package 	  Upment_Bannerslider
 * @copyright 	Copyright (c) 2019 Upment (https://www.upment.com/)
 */

/**
 * Bannerslider Edit Form Block
 *
 * @category 	Upment
 * @package 	Upment_Bannerslider
 * @author  	Upment
 */

class Upment_Bannerslider_Block_Adminhtml_Bannerslider_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm() {
      $form = new Varien_Data_Form();
      $this->setForm($form);

      if (Mage::getSingleton('adminhtml/session')->getBannerData()) {
        $data = Mage::getSingleton('adminhtml/session')->getBannerData();
        Mage::getSingleton('adminhtml/session')->setBannerData(null);
      } elseif (Mage::registry('banner_data')) {
        $data = Mage::registry('banner_data')->getData();
      }



      $fieldset = $form->addFieldset('banner_form', array('legend' => Mage::helper('bannerslider')->__('Banner Details')));

      $fieldset->addField('banner_title', 'text', array(
       'label'    => Mage::helper('bannerslider')->__('Banner title'),
       'class'    => 'required-entry',
       'required' => true,
       'name'     => 'banner_title',
       ));

       $fieldset->addField('desktop_image', 'image', array(
        'label'   => Mage::helper('bannerslider')->__('Desktop image'),
        'class'   => 'required-entry',
        'required'=> true,
        'name'    => 'desktop_image',
        ));

       $fieldset->addField('mobile_image', 'image', array(
        'label'   => Mage::helper('bannerslider')->__('Mobile image'),
        'class'   => 'required-entry',
        'required'=> true,
        'name'    => 'mobile_image',
         ));

       $fieldset->addField('link', 'text', array(
        'label'   => Mage::helper('bannerslider')->__('Link'),
        'required'=> false,
        'name'    => 'link',
         ));

      $fieldset->addField('animation', 'select', array(
        'label'   => Mage::helper('bannerslider')->__('Animation'),
        'class'   => 'required-entry',
        'required'=> true,
        'name'    => 'animation',
        'values'  => array(
          array(
                'value' => 0,
                'label' => Mage::helper('bannerslider')->__('fade'),
               ),
          array(
                'value' => 1,
                'label' => Mage::helper('bannerslider')->__('leftslide'),
               ),
          array(
                'value' => 2,
                'label' => Mage::helper('bannerslider')->__('rightslide'),
               ),
          ),
      ));

      $fieldset->addField('delay', 'text', array(
       'label'    => Mage::helper('bannerslider')->__('Delay'),
       'class'    => 'required-entry validate-greater-than-zero',
       'required' => true,
       'name'     => 'delay',
      ));

      $fieldset->addField('visible_in', 'multiselect', array(
       'label'    => Mage::helper('bannerslider')->__('Store'),
       'required' => true,
       'values'   => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
       'name'     => 'visible_in',
      ));

      $fieldset->addField('active', 'select', array(
       'label'    => Mage::helper('bannerslider')->__('Active'),
       'class'    => 'required-entry',
       'required' => true,
       'name'     => 'active',
       'values'   => array(
          array(
                'value' => 0,
                'label' => Mage::helper('bannerslider')->__('yes'),
               ),
          array(
                'value' => 1,
                'label' => Mage::helper('bannerslider')->__('no'),
               ),
          ),
      ));

      $form->setValues($data);

      return parent::_prepareForm();

    }
}
