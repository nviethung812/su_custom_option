<?php
//
namespace SU\CustomOption\Plugin;

//
//use Magento\Framework\Exception\LocalizedException;
//use Magento\Framework\File\Uploader;
//
class ProductSave extends \Magento\Catalog\Model\Product\Gallery\CreateHandler
{
//    protected $imageHandle;
    protected $optionTypeValueFactory;

    public function __construct(
        \Magento\Framework\EntityManager\MetadataPool $metadataPool,
        \Magento\Catalog\Api\ProductAttributeRepositoryInterface $attributeRepository,
        \Magento\Catalog\Model\ResourceModel\Product\Gallery $resourceModel,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Catalog\Model\Product\Media\Config $mediaConfig,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\MediaStorage\Helper\File\Storage\Database $fileStorageDb,
        \SU\CustomOption\Model\OptionTypeValueFactory $optionTypeValueFactory
    ) {
        parent::__construct(
            $metadataPool,
            $attributeRepository,
            $resourceModel,
            $jsonHelper,
            $mediaConfig,
            $filesystem,
            $fileStorageDb
        );
        $this->optionTypeValueFactory = $optionTypeValueFactory;
    }

    public function beforeSave(\Magento\Catalog\Model\Product $subject)
    {
        foreach ($subject->getOptions() as $option) {
            if ($option->getType() == "checkbox" || $option->getType() == "radio") {
                if ($this->isDependItself($option)) {
                    throw new \Exception(
                        __('Option can not depend on itself. Please try again.')
                    );
                }
            }
        }

        if ($this->isDependInCircle($subject)) {
            throw new \Exception(
                __('Option can not depend in circle. Please try again.')
            );
        }
    }

    public function afterSave(\Magento\Catalog\Model\Product $subject, \Magento\Catalog\Model\Product $product)
    {
        $originalOptions = $product->getOptions();
        foreach ($originalOptions as $optionKey => $optionValue) {
            $data = $optionValue->getData();
            if ($optionValue->getType() == "checkbox" || $optionValue->getType() == "radio") {
                foreach ($data["values"] as $itemKey => $itemValue) {
                    if (isset($itemValue["custom_image"])) {
                        $image = json_decode($itemValue["custom_image"], true);
                        if (isset($image["file"])) {
                            try {
                                $newName = $this->moveImageFromTmp($image["file"]);
                                $optionTypeValue = $this->optionTypeValueFactory->create()->load($itemValue["option_type_id"]);
                                $optionTypeValue->setData("custom_image", str_replace($image["file"], $newName, $optionTypeValue->getData("custom_image")));
                                $optionTypeValue->setData("custom_image", str_replace("\/tmp", "", $optionTypeValue->getData("custom_image")));
                                $optionTypeValue->save();
                            } catch (\Exception $e) {
                            }
                        }
                    }
                }
                $originalOptions[$optionKey]->setData($data);
            }
        }
        return $product->setOptions($originalOptions);
    }

    protected function isDependItself(\Magento\Catalog\Model\Product\Option $option)
    {
        $depend = $option->getData("depend");
        foreach ($option->getData()["values"] as $value) {
            if (isset($value["option_type_id"])) {
                if ($value["option_type_id"] == $depend) {
                    return true;
                }
            }
        }
        return false;
    }

    protected function isDependInCircle(\Magento\Catalog\Model\Product $product)
    {
        $options = $product->getOptions();
        foreach ($options as $option) {
            $depend = $option->getData("depend");
            foreach ($options as $optionSecond) {
                if ($option->getOptionId() != $optionSecond->getOptionId()) {
                    foreach ($optionSecond->getData()["values"] as $valueSecond) {
                        if ($valueSecond["option_type_id"] == $depend) {
                            foreach ($option->getData()["values"] as $valueFirst) {
                                if ($valueFirst["option_type_id"] == $optionSecond->getData("depend")) {
                                    return true;
                                }
                            }
                        }
                    }
                }
            }
        }
        return false;
    }
}
