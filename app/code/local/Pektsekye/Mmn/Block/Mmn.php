<?php
class Pektsekye_Mmn_Block_Mmn extends Mage_Core_Block_Template
{
		
	public function _prepareLayout()
    {
		if(Mage::getStoreConfig('catalog/navigation/filtering', Mage::app()->getStore()->getStoreId())){
			    Mage::app()->cleanCache(array(Mage_Catalog_Model_Category::CACHE_TAG));
		}

		return parent::_prepareLayout();
    }
    
     public function getMmn()     
     { 
        if (!$this->hasData('mmn')) {
            $this->setData('mmn', Mage::registry('mmn'));
        }
        return $this->getData('mmn');
        
    }
	
}