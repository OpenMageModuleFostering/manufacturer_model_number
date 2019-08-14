<?php

class Pektsekye_Mmn_Model_Mysql4_Mmn_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('mmn/mmn');
    }
}