<?php

namespace SU\CustomOption\Plugin;

class OptionDataProvider
{
    public function afterGetData(\Magento\Catalog\Ui\DataProvider\Product\Form\ProductDataProvider $dataProvider, $result)
    {
//        echo '<pre>' . var_export($result, true) . '</pre>';
//        die;
        foreach ($result as $dataKey => $dataValue) {
            if ($dataKey != "config") {
                foreach ($dataValue["product"]["options"] as $optionKey => $optionValue) {
                    if (isset($optionValue["values"])) {
                        foreach ($optionValue["values"] as $itemKey => $itemValue) {
                            $image = $result[$dataKey]["product"]["options"][$optionKey]["values"][$itemKey]["custom_image"];
                            if ($image != null) {
                                $result[$dataKey]["product"]["options"][$optionKey]["values"][$itemKey]["custom_image"] = [json_decode($image, true)];
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }
}
