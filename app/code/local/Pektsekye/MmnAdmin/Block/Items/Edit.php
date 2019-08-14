<?php

class Pektsekye_MmnAdmin_Block_Items_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'mmnadmin';
        $this->_controller = 'items';
        
        $this->_updateButton('save', 'label', Mage::helper('mmnadmin')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('mmnadmin')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('mmnadmin_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'mmnadmin_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'mmnadmin_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('mmnadmin_data') && Mage::registry('mmnadmin_data')->getId() ) {
            return Mage::helper('mmnadmin')->__('Edit Item');
        } else {
            return Mage::helper('mmnadmin')->__('Add Item');
        }
    }
}