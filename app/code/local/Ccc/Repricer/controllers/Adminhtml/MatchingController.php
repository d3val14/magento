<?php
class Ccc_Repricer_Adminhtml_MatchingController extends Mage_Adminhtml_Controller_Action
{
    public function _initAction()
    {
        $this->loadLayout();
        return $this;
    }
    public function indexAction()
    {   
        $this->_title($this->__('Repricer'));
        $this->_initAction();
        $this->renderLayout();
    }
    public function editAction()
    {
        $this->_title($this->__('repricer'))->_title($this->__('Manage Repricer'));

        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('repricer_id');
        $model = Mage::getModel('repricer/matching');
        // 2. Initial checking
        if ($id) {
            $model->load($id); 
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('repricer')->__('This repricer no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }
        $this->_title($model->getId() ? $model->getTitle() : $this->__('New repricer'));

        // 3. Set entered data if was error when we do save
        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
        // 4. Register model to use later in blocks
        Mage::register('matching', $model);
        // 5. Build edit form
        $this->_initAction()
            ->_addBreadcrumb($id ? Mage::helper('repricer')->__('Edit Repricer') : Mage::helper('repricer')->__('New Repricer'), $id ? Mage::helper('repricer')->__('Edit Repricer') : Mage::helper('repricer')->__('New Repricer'));
        $this->renderLayout();
    }
    public function saveAction()
    {
        // Check if data sent
        if ($data = $this->getRequest()->getPost()) {
            // Initialize model and set data
            $model = Mage::getModel('repricer/matching');

            if ($id = $this->getRequest()->getParam('repricer_id')) {
                $model->load($id);
            }

            // Set other data
            $model->setData($data);

            try {
                // Save the data
                $model->save();

                // Display success message
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('repricer')->__('The Repricer has been saved.')
                );
                // Clear previously saved data from session
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                // Check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('competitor_id' => $model->getId(), '_current' => true));
                    return;
                }
                // Go to grid
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addException(
                    $e,
                    Mage::helper('banner')->__('An error occurred while saving the repricer.')
                );
            }

            // Set form data
            $this->_getSession()->setFormData($data);
            $this->_redirect('*/*/edit', array('repricer_id' => $this->getRequest()->getParam('repricer_id')));
            return;
        }
        $this->_redirect('*/*/');
    }
}
