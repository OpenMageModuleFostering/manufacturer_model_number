<?php

class Pektsekye_MmnAdmin_Block_Items_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('mmnadmin_form', array('legend'=>Mage::helper('mmnadmin')->__('Item information')));
     
      $fieldset->addField('sku', 'text', array(
          'label'     => Mage::helper('mmnadmin')->__('SKU'),
          'required'  => true,
          'name'      => 'sku',
      ));
     
      $fieldset->addField('manufacturer', 'text', array(
          'label'     => Mage::helper('mmnadmin')->__('Printer Manufacturer'),
          'required'  => false,
          'name'      => 'manufacturer',
      ));
     
      $fieldset->addField('model', 'text', array(
          'label'     => Mage::helper('mmnadmin')->__('Printer Model'),
          'required'  => false,
          'name'      => 'model',
      ));
     
      $fieldset->addField('number', 'text', array(
          'label'     => Mage::helper('mmnadmin')->__('Printer Number'),
          'required'  => false,
          'name'      => 'number',
      ));


      if ( Mage::getSingleton('adminhtml/session')->getMmnAdminData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getMmnAdminData());
          Mage::getSingleton('adminhtml/session')->setMmnAdminData(null);
      } elseif ( Mage::registry('mmnadmin_data') ) {
          $form->setValues(Mage::registry('mmnadmin_data')->getData());
      }
      return parent::_prepareForm();
  }
}