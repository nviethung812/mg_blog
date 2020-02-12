<?php

namespace MG\Blog\Model\Config\Source;

class PageLimitValue implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 3, 'label' => __('3')],
            ['value' => 5, 'label' => __('5')],
            ['value' => 10, 'label' => __('10')],
        ];
    }
}
