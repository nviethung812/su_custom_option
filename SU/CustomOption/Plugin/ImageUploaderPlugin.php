<?php

namespace SU\CustomOption\Plugin;

class ImageUploaderPlugin
{
    public function beforeSaveFileToTmpDir(
        \Magento\Catalog\Model\ImageUploader $subject,
        $fileId
    ) {
        $optionKey = explode("][", $fileId)[1];
        $valueKey = explode("][", $fileId)[3];
        $fileId = "options." . $optionKey . ".values." . $valueKey . ".custom_image";

        $image = [];
        foreach ($_FILES["product"] as $key => $value) {
            $image[$key] = $value["options"][$optionKey]["values"][$valueKey]["custom_image"];
        }
        $_FILES[$fileId] = $image;
        return $fileId;
    }
}
