<?php

class Pektsekye_MmnAdmin_Model_MmnAdmin extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('mmnadmin/mmnAdmin');
    }
}