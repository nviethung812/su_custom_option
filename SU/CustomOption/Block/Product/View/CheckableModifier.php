<?php

namespace SU\CustomOption\Block\Product\View;

use Magento\Catalog\Api\Data\ProductCustomOptionValuesInterface;

class CheckableModifier extends \Magento\Catalog\Block\Product\View\Options\Type\Select\Checkable
{
    protected $_template = 'SU_CustomOption::product/view/checkable.phtml';

}
