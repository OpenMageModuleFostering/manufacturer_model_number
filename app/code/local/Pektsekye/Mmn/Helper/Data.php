<?php

class Pektsekye_Mmn_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function getProductIds()
    {
		$where = '';
		
		if (isset($_GET['Manufacturer'])){
			if ($_GET['Manufacturer'] != 'all')
				$Manufacturer_selected_var = $_GET['Manufacturer'];
		} elseif (isset($_COOKIE['Manufacturer_selected']) && $_COOKIE['Manufacturer_selected'] != 'all')	
			$Manufacturer_selected_var = $_COOKIE['Manufacturer_selected'];
			
		if (isset($_GET['Model'])){
			if ($_GET['Model'] != 'all')
				$Model_selected_var = $_GET['Model'];
		} elseif (isset($_COOKIE['Model_selected']) && $_COOKIE['Model_selected'] != 'all')
			$Model_selected_var = $_COOKIE['Model_selected'];
			
		if (isset($_GET['Number'])){
			if ($_GET['Number'] != 'all')
				$Number_selected_var = $_GET['Number'];
		} elseif (isset($_COOKIE['Number_selected']) && $_COOKIE['Number_selected'] != 'all')
			$Number_selected_var = $_COOKIE['Number_selected'];
			

		if (isset($Manufacturer_selected_var))
			$where .= " (manufacturer='".$Manufacturer_selected_var."' or manufacturer='') ";

		if (isset($Model_selected_var))
			$where .= ($where != '' ? ' and ' : '') . " (model='".$Model_selected_var."' or model='') ";
			
		if (isset($Number_selected_var))
			$where .= ($where != '' ? ' and ' : '') . " (number='".$Number_selected_var."' or number='') ";
			


		if ($where != ''){

			$resource = Mage::getSingleton('core/resource'); 
			$read= $resource->getConnection('core_read');
			$productTable = $resource->getTableName('catalog_product_entity');
			$mmnTable = $resource->getTableName('mmn');
			$rows = $read->fetchAll("SELECT DISTINCT entity_id FROM $productTable LEFT JOIN $mmnTable USING (entity_id) WHERE $where");
			
			if(count($rows)>0){
				foreach ($rows as $r)
					$ids [] = $r['entity_id'];
					
					return $ids;
				
			} else 	return false;
	
		} 
			
		return false;
		
    }

}