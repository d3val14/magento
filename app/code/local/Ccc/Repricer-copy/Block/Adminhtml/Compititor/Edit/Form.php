<?php

class Ccc_Banner_Block_Adminhtml_Banner_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('banner_form');
        $this->setTitle(Mage::helper('banner')->__('Banner Information'));
    }

    /**
     * Load Wysiwyg on demand and Prepare layout
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        // if (Mage::getSingleton('banner/wysiwyg_config')->isEnabled()) {
        $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        // }
    }

    protected function _prepareForm()
    {
        $model = Mage::registry('banner');

        $form = new Varien_Data_Form(
            array('id' => 'edit_form', 'action' => $this->getUrl('*/*/save'), 'method' => 'post',  'enctype' => 'multipart/form-data')
        );

        $form->setHtmlIdPrefix('banner_');

        $fieldset = $form->addFieldset('base_fieldset', array('legend' => Mage::helper('banner')->__('General Information'), 'class' => 'fieldset-wide'));

        if ($model->getBannerId()) {
            $fieldset->addField('banner_id', 'hidden', array(
                'name' => 'banner_id',
            )
            );
        }

        $fieldset->addField('name', 'text', array(
            'name' => 'name',
            'label' => Mage::helper('banner')->__('Banner Title'),
            'title' => Mage::helper('banner')->__('BannerTitle'),
            'required' => true,
        )
        );

        $fieldset->addField('image', 'image', array(
            'label' => Mage::helper('banner')->__('Image'),
            'title' => Mage::helper('banner')->__('Image'),
            'class'   => 'required-entry',
            'required'=> true,
            'name'    => 'image',
            ));

        // $fieldset->addField('image', 'text', array(
        //     'name' => 'image',
        //     'label' => Mage::helper('banner')->__('Image'),
        //     'title' => Mage::helper('banner')->__('Image'),
        //     'required' => true,
        //     'class' => 'validate-xml-identifier',
        // )
        // );

        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('banner')->__('Status'),
            'title' => Mage::helper('banner')->__('Status'),
            'name' => 'status',
            'required' => true,
            'options' => array(
                '1' => Mage::helper('banner')->__('Enabled'),
                '2' => Mage::helper('banner')->__('Disabled'),
            ),
        )
        );
        if (!$model->getId()) {
            $model->setData('status', '1');
        }

        $fieldset->addField('show_on', 'select', array(
            'label' => Mage::helper('banner')->__('Show On'),
            'title' => Mage::helper('banner')->__('Show On'),
            'name' => 'show_on',
            'required' => true,
            'options' => array(
                '0' => Mage::helper('banner')->__('Select'),
                '1' => Mage::helper('banner')->__('HomePage'),
                '2' => Mage::helper('banner')->__('Checkout'),
            ),
        )
        );
        if (!$model->getId()) {
            $model->setData('show_on', '0');
        }

        $fieldset->addField('content', 'editor', array(
            'name' => 'content',
            'label' => Mage::helper('banner')->__('Content'),
            'title' => Mage::helper('banner')->__('Content'),
            'style' => 'height:6em',
            'required' => true,
        )
        );

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}