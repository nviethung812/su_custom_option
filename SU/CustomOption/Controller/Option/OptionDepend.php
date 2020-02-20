<?php

namespace SU\CustomOption\Controller\Option;

use Magento\Framework\App\Action\Context;

class OptionDepend extends \Magento\Framework\App\Action\Action
{
    protected $optionCollectionFactory;
    protected $optionFactory;

    public function __construct(
        Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\Option\CollectionFactory $optionCollectionFactory,
        \Magento\Catalog\Model\Product\Option $optionFactory
    ) {
        parent::__construct($context);
        $this->optionCollectionFactory = $optionCollectionFactory;
        $this->optionFactory = $optionFactory;
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
                array_push($result, $option->getId());
            }
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
//                if ($value->getOptionTypeId() != $optionTypeId){
//
//                }
//            }
//        }
        echo json_encode($result);
    }
}
