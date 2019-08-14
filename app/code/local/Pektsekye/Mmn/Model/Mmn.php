<?php

class Pektsekye_Mmn_Model_Mmn extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('mmn/mmn');
    }
}