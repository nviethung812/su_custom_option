<?php

namespace SU\CustomOption\Model;

class OptionTypeValue extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('SU\CustomOption\Model\ResourceModel\OptionTypeValue');
    }
}
