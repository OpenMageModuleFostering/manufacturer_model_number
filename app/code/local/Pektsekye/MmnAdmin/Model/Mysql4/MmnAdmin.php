<?php

class Pektsekye_MmnAdmin_Model_Mysql4_MmnAdmin extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the mmn_id refers to the key field in your database table.
        $this->_init('mmnadmin/mmn', 'mmn_id');
    }
}