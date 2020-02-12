<?php

namespace SU\CustomOption\Plugin;

class ProductHelperPlugin
{
    public function afterInitializeFromData(
        \Magento\Catalog\Controller\Adminhtml\Product\Initialization\Helper $helper,
        \Magento\Catalog\Model\Product $product
    ) {
        $originalOptions = $product->getOptions();
        foreach ($originalOptions as $optionKey => $optionValue) {
            $data = $optionValue->getData();
            foreach ($data["values"] as $itemKey => $itemValue) {
                if (isset($data["values"][$itemKey]["custom_image"])) {
                    $data["values"][$itemKey]["custom_image"] = strval(json_encode($itemValue["custom_image"][0]));
                } else {
                    $data["values"][$itemKey]["custom_image"] = null;
                }
            }
            $originalOptions[$optionKey]->setData($data);
        }
        return $product->setOptions($originalOptions);
    }
}
