<?php

namespace SU\CustomOption\Plugin;

class AddToCartPlugin
{
    public function beforeAddProduct(\Magento\Quote\Model\Quote $subject, \Magento\Catalog\Model\Product $product, $request)
    {
        $om = \Magento\Framework\App\ObjectManager::getInstance();
        $customLog = $om->create("SU\CustomOption\Model\CustomLogFactory");
        $log = $customLog->create();

        $log->setData(["content" => strval(count($request))]);
        $log->save();die;

        if ($request !== null) {
            $originalOptions = $product->getOptions();
            foreach ($originalOptions as $optionKey => $optionValue) {
                if (!is_null($optionValue->getValues())) {
                    if ($optionValue->getIsRequire()) {
                        $match = false;
                        foreach ($request["options"] as $key => $value) {
                            if ($optionValue->getOptionId() == $key) {
                                $match = true;
                            }
                        }
                        if (!$match && $optionValue->getData("is_depend_option_enabled")) {
                            $originalOptions[$optionKey]->setIsRequire("0");
                        }
                    }
                }
            }
            $product->setOptions($originalOptions);
        }
    }
}
