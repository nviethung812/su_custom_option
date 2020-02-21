<?php

namespace SU\CustomOption\Controller\Option;

use Magento\Framework\App\Action\Context;

class OptionDepend extends \Magento\Framework\App\Action\Action
{
    protected $optionCollectionFactory;
    protected $optionValueCollectionFactory;

    public function __construct(
        Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\Option\CollectionFactory $optionCollectionFactory,
        \SU\CustomOption\Model\ResourceModel\OptionTypeValue\CollectionFactory $optionValueCollectionFactory
    ) {
        parent::__construct($context);
        $this->optionCollectionFactory = $optionCollectionFactory;
        $this->optionValueCollectionFactory = $optionValueCollectionFactory;
    }
    /**
     * @inheritDoc
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $result = [];
        if (($data["option_type"] == 'checkbox')) {
            $optionTypeId = $data["option_type_id"];
            $optionCollection = $this->optionCollectionFactory->create();
            $optionCollection->addFieldToSelect("*");
            $optionCollection->addFieldToFilter("depend", ["eq" => $optionTypeId]);

            foreach ($optionCollection as $option) {
                array_push($result, [
                    "option_id" => $option->getId()
                ]);
            }
//            if ($data["checked"] == 'false') {
//                $result = $this->getChildDepend($result, 0, true);
//            }
        }
//        elseif ($data["option_type"] == 'radio') {
//            $optionTypeId = $data["option_type_id"];
//
//            $check = [];
//            $optionCollection = $this->optionCollectionFactory->create();
//            $optionCollection->addFieldToSelect("*");
//            $optionCollection->addFieldToFilter("depend", ["eq" => $optionTypeId]);
//
//            foreach ($optionCollection as $option) {
//                array_push($check, $option->getId());
//            }
//
//            $uncheck = [];
//            $option = $this->optionFactory->create()->load($data["option_id"]);
//            foreach ($option->getValues() as $value) {
//                if ($value->getOptionTypeId() != $optionTypeId) {
//                }
//            }
//        }
        echo json_encode($result);
    }

//    public function getChildDepend($result, $index, $continue)
//    {
//        if ($continue) {
//            $count = 0;
//            for ($i = $index; $i < count($result); $i++) {
//                $option = $result[$i];
//                $parentValueCollection = $this->optionValueCollectionFactory->create();
//                $parentValueCollection->addFieldToSelect("*");
//                $parentValueCollection->addFieldToFilter("option_id", ["eq" => $option["option_id"]]);
//
//                foreach ($parentValueCollection as $parentValue) {
//                    $childOptionCollection = $this->optionCollectionFactory->create();
//                    $childOptionCollection->addFieldToSelect("*");
//                    $childOptionCollection->addFieldToFilter("depend", ["eq" => $parentValue->getOptionTypeId()]);
//
//                    if (count($childOptionCollection->getData())) {
//                        foreach ($childOptionCollection as $childOption) {
//                            array_push($result, [
//                                "option_id" => $childOption->getId()
//                            ]);
//                            $count += 1;
//                        }
//                        $index += $count;
//                        $continue = true;
//                    } else {
//                        $continue = false;
//                    }
//                    $this->getChildDepend($result, $index, $continue);
//                }
//            }
//        }
//
//        return $result;
//    }
}
