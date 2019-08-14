<?php

class Pektsekye_Mmn_Block_Adminhtml_Mmn_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('mmnGrid');
      $this->setDefaultSort('mmn_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('mmn/mmn')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {

      $this->addColumn('sku', array(
          'header'    => Mage::helper('mmn')->__('SKU'),
          'align'     =>'left',
          'index'     => 'sku',
      ));
	  
      $this->addColumn('manufacturer', array(
          'header'    => Mage::helper('mmn')->__('Printer Manufacturer'),
          'align'     =>'left',
          'index'     => 'manufacturer',
      ));

      $this->addColumn('model', array(
          'header'    => Mage::helper('mmn')->__('Printer Model'),
          'align'     =>'left',
          'index'     => 'model',
      ));

      $this->addColumn('number', array(
          'header'    => Mage::helper('mmn')->__('Printer Number'),
          'align'     =>'left',
          'index'     => 'number',
      ));

	  
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('mmn')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('mmn')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));

	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('mmn_id');
        $this->getMassactionBlock()->setFormFieldName('mmn');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('mmn')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('mmn')->__('Are you sure?')
        ));
		
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}