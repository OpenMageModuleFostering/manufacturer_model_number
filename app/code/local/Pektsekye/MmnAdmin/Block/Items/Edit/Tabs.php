<?php

class Pektsekye_MmnAdmin_Block_Items_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('mmnadmin_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('mmnadmin')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('mmnadmin')->__('Item Information'),
          'title'     => Mage::helper('mmnadmin')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('mmnadmin/items_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}