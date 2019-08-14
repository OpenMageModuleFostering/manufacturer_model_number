<?php
class Pektsekye_MmnAdmin_Block_Items extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'items';
    $this->_blockGroup = 'mmnadmin';
    $this->_headerText = Mage::helper('mmn')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('mmn')->__('Add Item');
    parent::__construct();
  }
}