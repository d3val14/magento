<?php

class Ccc_Repricer_Model_Resource_Matching extends Mage_Core_Model_Resource_Db_Abstract{
    protected function _construct(){
        $this->_init('repricer/matching', 'repricer_id');
    }
}