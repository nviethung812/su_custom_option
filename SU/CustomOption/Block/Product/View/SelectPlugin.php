<?php

namespace SU\CustomOption\Block\Product\View;

class SelectPlugin
{
    public function beforeToHtml(\Magento\Catalog\Block\Product\View\Options\Type\Select $subject)
    {
        $subject->setTemplate("SU_CustomOption::product/view/select.phtml");
    }
}
