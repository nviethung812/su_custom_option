<?php

namespace SU\CustomOption\Plugin;

class CartEditPlugin
{
    public function afterGetCustomOptions(
        \Magento\Catalog\Helper\Product\Configuration $subject,
        $result,
        \Magento\Catalog\Model\Product\Configuration\Item\ItemInterface $item
    ) {
        $originalOptions = $item->getProduct()->getOptions();
        foreach ($originalOptions as $optionKey => $optionValue) {
            if (!is_null($optionValue->getValues())) {
                if ($optionValue->getIsRequire()) {
                    $match = false;
                    foreach ($result as $value) {
                        if ($optionValue->getOptionId() == $value["option_id"]) {
                            $match = true;
                        }
                    }
                    if (!$match && $optionValue->getData("is_depend_option_enabled")) {
                        $originalOptions[$optionKey]->setIsRequire("0");
                    }
                }
            }
        }
        $item->getProduct()->setOptions($originalOptions);

        return $result;
    }
}
