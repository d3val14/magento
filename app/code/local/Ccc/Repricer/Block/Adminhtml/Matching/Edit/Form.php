<?php
class Ccc_Repricer_Block_Adminhtml_Matching_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('repricer_id');
        $this->setTitle(Mage::helper('repricer')->__('Repricer Information'));
    }

    protected function _prepareForm()
    {

        $model = Mage::registry('matching');
        $product_name = Mage::getModel('catalog/product')->load($model->getProductId())->getName();
        $competitor_name = Mage::getModel('repricer/competitor')->load($model->getCompetitorId())->getName();
        // echo $competitor_name;
        $isEdit = ($model && $model->getId());

        $form = new Varien_Data_Form(
            array('id' => 'edit_form', 'action' => $this->getUrl('*/*/save'), 'method' => 'post', 'enctype' => 'multipart/form-data')
        );

        $form->setHtmlIdPrefix('block_');

        $fieldset = $form->addFieldset('base_fieldset', array('legend' => Mage::helper('repricer')->__('General Information'), 'class' => 'fieldset-wide'));

        if ($isEdit && $model->getRepricerId()) {
            $fieldset->addField(
                'repricer_id',
                'hidden',
                array(
                    'name' => 'repricer_id',
                )
            );
        }

        $fieldset->addField(
            'product_id',
            'hidden',
            array(
                'name' => 'product_id',
                'label' => Mage::helper('repricer')->__('Product Id'),
                'title' => Mage::helper('repricer')->__('Product Id'),
                // Remove 'required' attribute only in edit mode
            )
        );

        $fieldset->addField(
            'product_name',
            'label',
            array(
                'name' => 'product_name',
                'label' => Mage::helper('repricer')->__('Product Name'),
                'title' => Mage::helper('repricer')->__('Product Name'),
                // Remove 'required' attribute only in edit mode
            )
        );
        $fieldset->addField(
            'competitor_id',
            'hidden',
            array(
                'name' => 'competitor_id',
                'label' => Mage::helper('repricer')->__('Competitor Id'),
                'title' => Mage::helper('repricer')->__('Competitor Id'),
                // Remove 'required' attribute only in edit mode
            )
        );
        $fieldset->addField(
            'competitor_name',
            'label',
            array(
                'name' => 'competitor_name',
                'label' => Mage::helper('repricer')->__('Competitor Name'),
                'title' => Mage::helper('repricer')->__('Competitor Name'),
                // Remove 'required' attribute only in edit mode
            )
        );
        $fieldset->addField(
            'competitor_url',
            'text',
            array(
                'name' => 'competitor_url',
                'label' => Mage::helper('repricer')->__('Competitor Url'),
                'title' => Mage::helper('repricer')->__('Competitor Url'),
                // Remove 'required' attribute only in edit mode
            )
        );
        $fieldset->addField(
            'competitor_sku',
            'text',
            array(
                'name' => 'competitor_sku',
                'label' => Mage::helper('repricer')->__('Competitor Sku'),
                'title' => Mage::helper('repricer')->__('Competitor Sku'),
                // Remove 'required' attribute only in edit mode
            )
        );
        $fieldset->addField(
            'competitor_price',
            'text',
            array(
                'name' => 'competitor_price',
                'label' => Mage::helper('repricer')->__('Competitor Price'),
                'title' => Mage::helper('repricer')->__('Competitor Price'),
                // Remove 'required' attribute only in edit mode
            )
        );

        $fieldset->addField(
            'reason',
            'select',
            array(
                'label' => Mage::helper('repricer')->__('Reason'),
                'title' => Mage::helper('repricer')->__('Reason'),
                'name' => 'reason',
                'required' => true,
                'options' => Mage::getModel('repricer/matching')->getReasonOptionArray(),
            )
        );
        if (!$model->getId()) {
            $model->setData('status', Mage::getModel('repricer/matching')::REASON_DEFAULT);
        }

        $data=$model->getData();
        $data['product_name'] = $product_name;
        $data['competitor_name'] = $competitor_name;

        
        $form->setValues($data);
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}