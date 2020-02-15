<?php


namespace SU\CustomOption\Model\ResourceModel;


class OptionTypeValue extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init("catalog_product_option_type_value", "option_type_id");
    }
}
