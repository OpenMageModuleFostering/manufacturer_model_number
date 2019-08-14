<?php

class Pektsekye_Mmn_Model_Mysql4_Mmn extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the mmn_id refers to the key field in your database table.
        $this->_init('mmn/mmn', 'mmn_id');
    }
}