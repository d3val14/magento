<?php

class Ccc_Repricer_Adminhtml_MatchingController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()->_setActiveMenu('catalog');
    }
    public function indexAction()
    {
        $this->_initAction();
        echo 'matching';   
        $this->renderLayout();
    }
}