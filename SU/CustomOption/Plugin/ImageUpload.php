<?php

namespace SU\CustomOption\Plugin;

class ImageUpload
{
    public function beforeExecute(\Magento\Catalog\Controller\Adminhtml\Product\Gallery\Upload $subject)
    {
        $fileId = $subject->getRequest()->getParam('param_name', 'image');
        if (substr($fileId, -14) == "[custom_image]") {
            $optionKey = explode("][", $fileId)[1];
            $valueKey = explode("][", $fileId)[3];
            $fileId = "options." . $optionKey . ".values." . $valueKey . ".custom_image";

            $image = [];
            foreach ($_FILES["product"] as $key => $value) {
                $image[$key] = $value["options"][$optionKey]["values"][$valueKey]["custom_image"];
            }
            $_FILES["image"] = $image;
        }
    }
}
