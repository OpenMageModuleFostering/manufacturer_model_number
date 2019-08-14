<?php

class Pektsekye_Mmn_Block_Adminhtml_Mmn_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('mmn_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('mmn')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('mmn')->__('Item Information'),
          'title'     => Mage::helper('mmn')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('mmn/adminhtml_mmn_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}