<?php

namespace SU\CustomOption\Controller\Option;

use Magento\Framework\App\Action\Context;

class OptionDepend extends \Magento\Framework\App\Action\Action
{
    protected $optionCollectionFactory;

    public function __construct(
        Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\Option\CollectionFactory $optionCollectionFactory
    ) {
        parent::__construct($context);
        $this->optionCollectionFactory = $optionCollectionFactory;
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
        echo json_encode($result);
    }
}
