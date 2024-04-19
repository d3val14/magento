<?php
class Ccc_Repricer_Adminhtml_CompetitorController extends Mage_Adminhtml_Controller_Action
{
    public function _initAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('catalog');
        return $this;
    }
    public function indexAction()
    {
        $this->_title($this->__('Repricer'));
        $this->_initAction();
        $this->renderLayout();
    }
    // public function newAction()
    // {
    //     $this->_forward('edit');
    // }
    public function editAction()
    {
        $this->_title($this->__('repricer'))->_title($this->__('Manage Competitor'));

        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('competitor_id');
        $model = Mage::getModel('repricer/competitor');
        // 2. Initial checking
        if ($id) {
            $model->load($id); 
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('repricer')->__('This competitor no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }
        $this->_title($model->getId() ? $model->getTitle() : $this->__('New competitor'));

        // 3. Set entered data if was error when we do save
        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
        // 4. Register model to use later in blocks
        Mage::register('competitor', $model);
        // 5. Build edit form
        $this->_initAction()
            ->_addBreadcrumb($id ? Mage::helper('repricer')->__('Edit Competitor') : Mage::helper('repricer')->__('New Competitor'), $id ? Mage::helper('repricer')->__('Edit Competitor') : Mage::helper('repricer')->__('New Competitor'));
        $this->renderLayout();
    }
    public function saveAction()
    {
        // Check if data sent
        if ($data = $this->getRequest()->getPost()) {
            // Initialize model and set data
            $model = Mage::getModel('repricer/competitor');

            if ($id = $this->getRequest()->getParam('competitor_id')) {
                $model->load($id);
            }

            // Set other data
            $model->setData($data);

            try {
                // Save the data
                $model->save();

                // Display success message
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('repricer')->__('The Competitor has been saved.')
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
                    Mage::helper('banner')->__('An error occurred while saving the competitor.')
                );
            }

            // Set form data
            $this->_getSession()->setFormData($data);
            $this->_redirect('*/*/edit', array('competitor_id' => $this->getRequest()->getParam('competitor_id')));
            return;
        }
        $this->_redirect('*/*/');
    }
    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('competitor_id')) {
            $title = "";
            try {
                $model = Mage::getModel('repricer/competitor');
                $model->load($id);
                $title = $model->getTitle();
                $model->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('repricer')->__('The Competitor has been deleted.')
                );
                Mage::dispatchEvent('adminhtml_competitor_on_delete', array('title' => $title, 'status' => 'success'));
                $this->_redirect('*/*/');
                return;

            } catch (Exception $e) {
                Mage::dispatchEvent('adminhtml_competitor_on_delete', array('title' => $title, 'status' => 'fail'));
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('page_id' => $id));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('repricer')->__('Unable to find a competitor to delete.'));
        $this->_redirect('*/*/');
    }
    public function massDeleteAction()
    {
        $competitorIds = $this->getRequest()->getParam('competitor_id');
        if (!is_array($competitorIds)) {
            $this->_getSession()->addError($this->__('Please select competitor(s).'));
        } else {
            if (!empty($competitorIds)) {
                try {
                    foreach ($competitorIds as $competitorId) {
                        $competitor = Mage::getSingleton('repricer/competitor')->load($competitorId);
                        // Mage::dispatchEvent('competitor_controller_competitor_delete', array('competitor' => $competitor));
                        $competitor->delete();
                    }
                    $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) have been deleted.', count($competitorIds))
                    );
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction()
    {
        $competitorIds = $this->getRequest()->getParam('competitor_id');
        $status = $this->getRequest()->getParam('status');

        if (!is_array($competitorIds)) {
            $competitorIds = array($competitorIds);
        }

        try {
            foreach ($competitorIds as $competitorId) {
                $competitor = Mage::getModel('repricer/competitor')->load($competitorId);
                // Check if the status is different than the one being set
                if ($competitor->getStatus() != $status) {
                    $competitor->setStatus($status)->save();
                }
            }
            // Use appropriate success message based on the status changed
            if ($status == 1) {
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) have been enabled.', count($competitorIds))
                );
            } else {
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) have been disabled.', count($competitorIds))
                );
            }
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }

        $this->_redirect('*/*/index');
    }
}