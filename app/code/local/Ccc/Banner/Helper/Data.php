<?php

class Ccc_Banner_Helper_Data extends Mage_Core_Helper_Abstract
{

    public static function uploadBannerImage($image_type) {
        $banner_image_path = Mage::getBaseDir('media');
        $image = '';
        if (isset($_FILES[$image_type]['name']) && $_FILES[$image_type]['name'] != '') {
  
            try {
                $uploader = new Varien_File_Uploader($image_type);
                $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(false);
                $uploader->save($banner_image_path . DS . 'banner', $uploader->getCorrectFileName($_FILES[$image_type]['name']));
  
                $image = 'banner' . DS . $uploader->getUploadedFileName();
  
            } catch (Exception $e) {
  
            }
        }
  
        return $image;
    }

    public function deleteImageFile($image) {
        if (!$image) {
            return;
        }
        $name = $image;
        $banner_image_path = Mage::getBaseDir('media') . DS .  $name;

        if (!file_exists($banner_image_path)) {
            return;
        }

        try {
            unlink($banner_image_path);
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
  }
}