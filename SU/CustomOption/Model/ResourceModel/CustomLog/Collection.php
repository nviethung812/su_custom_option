<?php


namespace SU\CustomOption\Model\ResourceModel\CustomLog;


class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init('SU\CustomOption\Model\CustomLog', 'SU\CustomOption\Model\ResourceModel\CustomLog');
    }
}
