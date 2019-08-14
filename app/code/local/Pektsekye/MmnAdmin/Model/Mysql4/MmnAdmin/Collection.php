<?php

class Pektsekye_MmnAdmin_Model_Mysql4_MmnAdmin_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('mmnadmin/mmnAdmin');
    }
}