<?php

class Pektsekye_Mmn_Block_Adminhtml_Mmn_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'mmn';
        $this->_controller = 'adminhtml_mmn';
        
        $this->_updateButton('save', 'label', Mage::helper('mmn')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('mmn')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('mmn_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'mmn_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'mmn_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('mmn_data') && Mage::registry('mmn_data')->getId() ) {
            return Mage::helper('mmn')->__('Edit Item');
        } else {
            return Mage::helper('mmn')->__('Add Item');
        }
    }
}