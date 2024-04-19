<?php

class Ccc_Banner_Adminhtml_BannerController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()->_setActiveMenu('banner');
    }
    public function indexAction()
    {
        $this->_initAction();
        $this->renderLayout();
    }
    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {

       

        $this->_title($this->__('BANNER'));

        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('banner_id');
        $model = Mage::getModel('banner/banner');

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('banner')->__('This banner no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }

        $this->_title($model->getId() ? $model->getTitle() : $this->__('New Banner'));

        // 3. Set entered data if was error when we do save
        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        // 4. Register model to use later in blocks
        Mage::register('banner', $model);

        // 5. Build edit form
        $this->_initAction();
        $this->_addBreadcrumb($id ? Mage::helper('banner')->__('Edit Block') : Mage::helper('banner')->__('New Banner'), $id ? Mage::helper('banner')->__('Edit Banner') : Mage::helper('banner')->__('New Banner'))
            ->renderLayout();
    }

    //     public function saveAction()
// {
//     if ($data = $this->getRequest()->getPost()) {
//         $model = Mage::getModel('banner/banner');

    //         if ($id = $this->getRequest()->getParam('banner_id')) {
//             $model->load($id);
//         }

    //         $helper = Mage::helper('banner');
//         $data = $helper->processImageData($data, $model);

    //         if ($data === false) {
//             $this->_redirect('*/*/edit', array('banner_id' => $this->getRequest()->getParam('banner_id')));
//             return;
//         }

    //         $model->setData($data);

    //         try {
//             $model->save();

    //             Mage::getSingleton('adminhtml/session')->addSuccess(
//                 Mage::helper('banner')->__('The Banner has been saved.')
//             );

    //             Mage::getSingleton('adminhtml/session')->setFormData(false);

    //             if ($this->getRequest()->getParam('back')) {
//                 $this->_redirect('*/*/edit', array('banner_id' => $model->getId(), '_current' => true));
//                 return;
//             }

    //             $this->_redirect('*/*/');
//             return;
//         } catch (Mage_Core_Exception $e) {
//             $this->_getSession()->addError($e->getMessage());
//         } catch (Exception $e) {
//             $this->_getSession()->addException(
//                 $e,
//                 Mage::helper('banner')->__('An error occurred while saving the banner.')
//             );
//         }

    //         $this->_getSession()->setFormData($data);
//         $this->_redirect('*/*/edit', array('banner_id' => $this->getRequest()->getParam('banner_id')));
//         return;
//     }

    //     $this->_redirect('*/*/');
// }

    /**
     * Save action using helper
     */
    public function saveAction()
    {
        //code of Access Rights 
        // check if data sent
        if ($data = $this->getRequest()->getPost()) {


            $type = 'image';

            if (isset($data[$type]['delete'])) {
                Mage::helper('banner')->deleteImageFile($data[$type]['value']);
            }
            $image = Mage::helper('banner')->uploadBannerImage($type);
            if ($image || (isset($data[$type]['delete']) && $data[$type]['delete'])) {
                $data[$type] = $image;
            } else {
                unset($data[$type]);
            }

            $id = $this->getRequest()->getParam('banner_id');
            $model = Mage::getModel('banner/banner')->load($id);
            if (!$model->getId() && $id) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('banner')->__('This banner no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }

            // init model and set data

            $model->setData($data);

            // try to save it
            try {
                // save the data
                $model->save();
                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('banner')->__('The banner has been saved.'));
                // clear previously saved data from session
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                // check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('banner_id' => $model->getId()));
                    return;
                }
                // go to grid
                $this->_redirect('*/*/');
                return;

            } catch (Exception $e) {
                // display error message
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                // save data in session
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                // redirect to edit form
                $this->_redirect('*/*/edit', array('banner_id' => $this->getRequest()->getParam('banner_id')));
                return;
            }
        }
        $this->_redirect('*/*/');
    }

    // public function saveAction()
    // {
    //     // Check if data sent
    //     if ($data = $this->getRequest()->getPost()) {
    //         // Initialize model and set data
    //         $model = Mage::getModel('banner/banner');

    //         if ($id = $this->getRequest()->getParam('banner_id')) {
    //             $model->load($id);
    //         }

    //         // Image upload handling using helper class method
    //         try {
    //             $helper = Mage::helper('banner');
    //             $data = $helper->uploadImage($data, $model);
    //             $data = $helper->updateImage($data, $model);
    //         } catch (Exception $e) {
    //             Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
    //             $this->_redirect('*/*/edit', array('banner_id' => $this->getRequest()->getParam('banner_id')));
    //             return;
    //         }

    //         // Set other data
    //         $model->setData($data);

    //         try {
    //             // Save the data
    //             $model->save();

    //             // Display success message
    //             Mage::getSingleton('adminhtml/session')->addSuccess(
    //                 Mage::helper('banner')->__('The Banner has been saved.')
    //             );
    //             // Clear previously saved data from session
    //             Mage::getSingleton('adminhtml/session')->setFormData(false);
    //             // Check if 'Save and Continue'
    //             if ($this->getRequest()->getParam('back')) {
    //                 $this->_redirect('*/*/edit', array('banner_id' => $model->getId(), '_current' => true));
    //                 return;
    //             }
    //             // Go to grid
    //             $this->_redirect('*/*/');
    //             return;
    //         } catch (Mage_Core_Exception $e) {
    //             $this->_getSession()->addError($e->getMessage());
    //         } catch (Exception $e) {
    //             $this->_getSession()->addException(
    //                 $e,
    //                 Mage::helper('banner')->__('An error occurred while saving the banner.')
    //             );
    //         }

    //         // Set form data
    //         $this->_getSession()->setFormData($data);
    //         $this->_redirect('*/*/edit', array('banner_id' => $this->getRequest()->getParam('banner_id')));
    //         return;
    //     }
    //     $this->_redirect('*/*/');
    // }
    public function deleteAction()
    {
        // code of Access Rights
        // check if we know what should be deleted
        if ($id = $this->getRequest()->getParam('banner_id')) {
            $title = "";
            try {
                // init model and delete
                $model = Mage::getModel('banner/banner');
                $model->load($id);
                $title = $model->getTitle();
                $model->delete();
                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('banner')->__('The banner has been deleted.'));
                // go to grid
                $this->_redirect('*/*/');
                return;

            } catch (Exception $e) {
                // display error message
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                // go back to edit form
                $this->_redirect('*/*/edit', array('banner_id' => $id));
                return;
            }
        }
        // display error message
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('cms')->__('Unable to find a banner to delete.'));
        // go to grid
        $this->_redirect('*/*/');
    }

    protected function _isAllowed()
    {

        // return true;
        // $isAdminAllowed = Mage::getSingleton('admin/session')->isAllowed('banner');
        $action = strtolower($this->getRequest()->getActionName());
        switch ($action) {
            case 'new':
                $aclResource = 'banner/curd/new';
                break;

            case 'edit':
                $aclResource = 'banner/curd/edit';
                break;

            case 'delete':
                $aclResource = 'banner/curd/delete';
                break;

            // case "showbutton":
            //     $aclResource = "banner/actions/showbutton";
            //     break;

            // case 'field/name':
            //     $aclResource = 'banner/filed/name';
            //     break;

            default:
                $aclResource = 'banner';
                break;
        }
        return Mage::getSingleton('admin/session')->isAllowed($aclResource);
    }


    public function massDeleteAction()
    {
        $bannerIds = $this->getRequest()->getParam('banner_id');
        if (!is_array($bannerIds)) {
            $this->_getSession()->addError($this->__('Please select banner(s).'));
        } else {
            if (!empty($bannerIds)) {
                try {
                    foreach ($bannerIds as $bannerId) {
                        $banner = Mage::getSingleton('banner/banner')->load($bannerId);
                        // Mage::dispatchEvent('banner_controller_banner_delete', array('banner' => $banner));
                        $banner->delete();
                    }
                    $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) have been deleted.', count($bannerIds))
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
        $bannerIds = $this->getRequest()->getParam('banner_id');
        $status = $this->getRequest()->getParam('status');

        if (!is_array($bannerIds)) {
            $bannerIds = array($bannerIds);
        }

        try {
            foreach ($bannerIds as $bannerId) {
                $banner = Mage::getModel('banner/banner')->load($bannerId);
                // Check if the status is different than the one being set
                if ($banner->getStatus() != $status) {
                    $banner->setStatus($status)->save();
                }
            }
            // Use appropriate success message based on the status changed
            if ($status == 1) {
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) have been enabled.', count($bannerIds))
                );
            } else {
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) have been disabled.', count($bannerIds))
                );
            }
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }

        $this->_redirect('*/*/index');
    }



}