<?php
class Ccc_Repricer_Block_Adminhtml_Matching_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId = 'repricer_id';
        $this->_controller = 'adminhtml_matching';
        $this->_blockGroup = 'repricer';

        parent::__construct();

        $this->_updateButton('save', 'label', Mage::helper('repricer')->__('Save Repricer'));
        $this->removeButton('delete');

        // $this->_addButton('saveandcontinue', array(
        //     'label' => Mage::helper('adminhtml')->__('Save and Continue Edit'),
        //     'onclick' => 'saveAndContinueEdit()',
        //     'class' => 'save',
        // ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('banner_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'banner_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'banner_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * Get edit form container header text
     *
     * @return string
     */
    public function getHeaderText()
    {
        if (Mage::registry('matching')->getId()) {
            return Mage::helper('repricer')->__("Edit Repricer %s", $this->escapeHtml(Mage::registry('matching')->getTitle()));
        } else {
            return Mage::helper('repricer')->__('New Repricer');
        }
    }

}