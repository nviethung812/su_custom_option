<?php

namespace SU\CustomOption\Model\Config\Source\Product\Options;

class Depend
{

    /**
     * @inheritDoc
     */
    public function toOptionArray(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        $array = [];
        $array[] = ["value" => 0 , "label" => "Independence"];

        foreach ($product->getOptions() as $option) {
            $array[] = ["value" => $option->getOptionId() , "label" => $option->getTitle()];
        }

        return $array;
    }
}
