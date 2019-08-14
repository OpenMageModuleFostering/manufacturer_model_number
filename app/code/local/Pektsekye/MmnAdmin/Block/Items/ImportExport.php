<?php

class Pektsekye_MmnAdmin_Block_Items_importExport extends Mage_Adminhtml_Block_Widget
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('mmnadmin/importExport.phtml');
    }
}