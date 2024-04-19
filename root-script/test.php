<?php 
require_once('../app/Mage.php'); //Path to Magento
Mage::app();
$coll = Mage::getModel('repricer/matching')->getCollection();
var_dump($coll->getData());
