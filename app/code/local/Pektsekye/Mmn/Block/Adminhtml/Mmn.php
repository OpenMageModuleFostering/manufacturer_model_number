<?php
class Pektsekye_Mmn_Block_Adminhtml_Mmn extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_mmn';
    $this->_blockGroup = 'mmn';
    $this->_headerText = Mage::helper('mmn')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('mmn')->__('Add Item');
    parent::__construct();
  }
}