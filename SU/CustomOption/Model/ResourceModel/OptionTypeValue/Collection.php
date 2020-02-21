<?php


namespace SU\CustomOption\Model\ResourceModel\OptionTypeValue;


class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'option_type_id';

    protected function _construct()
    {
        $this->_init('SU\CustomOption\Model\OptionTypeValue', 'SU\CustomOption\Model\ResourceModel\OptionTypeValue');
    }
}
