<?php

class Pektsekye_Mmn_Block_Adminhtml_Mmn_importExport extends Mage_Adminhtml_Block_Widget
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('mmn/importExport.phtml');
    }
}