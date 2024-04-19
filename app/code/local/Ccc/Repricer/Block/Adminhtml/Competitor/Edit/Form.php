<?php
class Ccc_Repricer_Block_Adminhtml_Competitor_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('competitor_id');
        $this->setTitle(Mage::helper('repricer')->__('Competitor Information'));
    }

    protected function _prepareForm()
    {
        $model = Mage::registry('competitor');
        $isEdit = ($model && $model->getId());

        $form = new Varien_Data_Form(
            array('id' => 'edit_form', 'action' => $this->getUrl('*/*/save'), 'method' => 'post', 'enctype' => 'multipart/form-data')
        );

        $form->setHtmlIdPrefix('block_');

        $fieldset = $form->addFieldset('base_fieldset', array('legend' => Mage::helper('repricer')->__('General Information'), 'class' => 'fieldset-wide'));

        if ($isEdit && $model->getCompetitorId()) {
            $fieldset->addField(
                'competitor_id',
                'hidden',
                array(
                    'name' => 'competitor_id',
                )
            );
        }

        $fieldset->addField(
            'name',
            'text',
            array(
                'name' => 'name',
                'label' => Mage::helper('repricer')->__('Name'),
                'title' => Mage::helper('repricer')->__('Name'),
                // Remove 'required' attribute only in edit mode
                'required' => !$isEdit,
            )
        );
        $fieldset->addField(
            'url',
            'text',
            array(
                'name' => 'url',
                'label' => Mage::helper('repricer')->__('Url'),
                'title' => Mage::helper('repricer')->__('Url'),
                // Remove 'required' attribute only in edit mode
                'required' => !$isEdit,
            )
        );

        $fieldset->addField(
            'status',
            'select',
            array(
                'label' => Mage::helper('repricer')->__('Status'),
                'title' => Mage::helper('repricer')->__('Status'),
                'name' => 'status',
                'required' => true,
                'options' => Mage::getModel('repricer/status')->getOptionArray(),
            )
        );
        if (!$model->getId()) {
            $model->setData('status', Mage::getModel('repricer/status')::STATUS_DISABLED);
        }

        $fieldset->addField(
            'filename',
            'text',
            array(
                'name' => 'filename',
                'label' => Mage::helper('repricer')->__('File Name'),
                'title' => Mage::helper('repricer')->__('File Name'),
                'required' => true,
            )
        );
        if(!$model->getId()){
            $fieldset->addField(
                'created_date',
                'hidden',
                array(
                    'name' => 'created_date',
                    'label' => Mage::helper('repricer')->__('Created date'),
                    'title' => Mage::helper('repricer')->__('Created date'),
                    'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
                )
            );
        }

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}