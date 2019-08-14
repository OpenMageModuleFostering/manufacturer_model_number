<?php


class Pektsekye_Mmn_Block_Product_Result extends Mage_Catalog_Block_Product_Abstract
{
    protected $_productCollection;

    protected function _prepareLayout()
    {
        $title = $this->getHeaderText();
        $this->getLayout()->getBlock('head')->setTitle($title);
        $this->getLayout()->getBlock('root')->setHeaderTitle($title);
        return parent::_prepareLayout();
    }

    public function setListOrders() {
        $this->getChild('search_result_list')
            ->setAvailableOrders(array(
                'name' => Mage::helper('mmn')->__('Name'),
                'price'=>Mage::helper('mmn')->__('Price'))
            );
    }

    public function setListModes() {
        $this->getChild('search_result_list')
            ->setModes(array(
                'grid' => Mage::helper('mmn')->__('Grid'),
                'list' => Mage::helper('mmn')->__('List'))
            );
    }

    public function setListCollection() {
        $this->getChild('search_result_list')
           ->setCollection($this->_getProductCollection());
    }

    public function getProductListHtml()
    {
        return $this->getChildHtml('search_result_list');
    }

    protected function _getProductCollection()
    {

        if (is_null($this->_productCollection)) {
            $this->_productCollection = Mage::getSingleton('catalogsearch/layer')->getProductCollection();
        }
		
        return $this->_productCollection;
    }

    public function getResultCount()
    {
        if (!$this->getData('result_count')) {
            $size = $this->_getProductCollection()->getSize();
            $this->setResultCount($size);
        }
        return $this->getData('result_count');
    }

    public function getHeaderText()
    {
		
		$manufacturer = $this->getRequest()->getParam('Manufacturer');
		$model = $this->getRequest()->getParam('Model');
		$number = $this->getRequest()->getParam('Number');
		
        if($manufacturer && $manufacturer != 'all') {			
			$manufacturer = $this->getRequest()->getParam('Manufacturer');
			
			if($model == 'all')
				$model = '';
			
			if($number == 0)		
				$number = '';
				
			return Mage::helper('mmn')->__("Products for %s", $this->htmlEscape("$manufacturer $model $number"));
				
        } else {
            return false;
        }
    }

    public function getSubheaderText()
    {
        return false;
    }

    public function getNoResultText()
    {
        return Mage::helper('mmn')->__('No matches found.');
    }
}
