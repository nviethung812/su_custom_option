<?php


namespace SU\CustomOption\Model\ResourceModel;


class CustomLog extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init("sosc_log", "id");
    }

}
