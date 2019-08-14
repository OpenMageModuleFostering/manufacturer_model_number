<?php

class Pektsekye_Mmn_ProductController extends Mage_Core_Controller_Front_Action
{

    public function listAction()
    { 
        if(!$this->getRequest()->getParam('Manufacturer') || $this->getRequest()->getParam('Manufacturer') == 'all') {
			 $this->getResponse()->setRedirect(Mage::getBaseUrl());
            return;
        }

		if(!Mage::helper('mmn')->getProductIds()){
			$this->getResponse()->setRedirect(Mage::getBaseUrl());
            return;
		}
		
        $this->loadLayout();
        $this->_initLayoutMessages('checkout/session');
        $this->_initLayoutMessages('tag/session');
        $this->renderLayout();
    }
}