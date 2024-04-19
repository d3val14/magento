<?php
class Ccc_Repricer_Block_Adminhtml_Competitor_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId = 'competitor_id';
        $this->_controller = 'adminhtml_competitor';
        $this->_blockGroup = 'repricer';

        parent::__construct();

        $this->_updateButton('save', 'label', Mage::helper('repricer')->__('Save Competitor'));
        $this->_updateButton('delete', 'label', Mage::helper('repricer')->__('Delete Competitor'));

        $this->_addButton('saveandcontinue', array(
            'label' => Mage::helper('adminhtml')->__('Save and Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
        ), -100);

        // $this->_formScripts[] = "
        //     function toggleEditor() {
        //         if (tinyMCE.getInstanceById('banner_content') == null) {
        //             tinyMCE.execCommand('mceAddControl', false, 'banner_content');
        //         } else {
        //             tinyMCE.execCommand('mceRemoveControl', false, 'banner_content');
        //         }
        //     }

        //     function saveAndContinueEdit(){
        //         editForm.submit($('edit_form').action+'back/edit/');
        //     }
        // ";
    }

    /**
     * Get edit form container header text
     *
     * @return string
     */
    public function getHeaderText()
    {
        if (Mage::registry('competitor')->getId()) {
            return Mage::helper('repricer')->__("Edit Competitor %s", $this->escapeHtml(Mage::registry('competitor')->getTitle()));
        } else {
            return Mage::helper('repricer')->__('New Competitor');
        }
    }

}