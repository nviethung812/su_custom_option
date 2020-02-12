<?php

namespace SU\CustomOption\Model;

class CustomLog extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('SU\CustomOption\Model\ResourceModel\CustomLog');
    }
}
