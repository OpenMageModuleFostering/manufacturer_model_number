<?php

class Pektsekye_Mmn_Block_Adminhtml_Mmn_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('mmn_form', array('legend'=>Mage::helper('mmn')->__('Item information')));
     
      $fieldset->addField('sku', 'text', array(
          'label'     => Mage::helper('mmn')->__('SKU'),
          'required'  => true,
          'name'      => 'sku',
      ));
     
      $fieldset->addField('manufacturer', 'text', array(
          'label'     => Mage::helper('mmn')->__('Printer Manufacturer'),
          'required'  => false,
          'name'      => 'manufacturer',
      ));
     
      $fieldset->addField('model', 'text', array(
          'label'     => Mage::helper('mmn')->__('Printer Model'),
          'required'  => false,
          'name'      => 'model',
      ));
     
      $fieldset->addField('number', 'text', array(
          'label'     => Mage::helper('mmn')->__('Printer Number'),
          'required'  => false,
          'name'      => 'number',
      ));


      if ( Mage::getSingleton('adminhtml/session')->getMmnData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getMmnData());
          Mage::getSingleton('adminhtml/session')->setMmnData(null);
      } elseif ( Mage::registry('mmn_data') ) {
          $form->setValues(Mage::registry('mmn_data')->getData());
      }
      return parent::_prepareForm();
  }
}