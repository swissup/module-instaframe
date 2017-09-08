<?php
/**
 * Copyright © 2015 Swissup. All rights reserved.
 */
namespace Swissup\Instaframe\Model\Config\Source;

class Layout implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $result = [
            ['value' => "thumbnail", 'label' => __('Thumbnail')],
            ['value' => "low_resolution", 'label' => __('Low Resolution')],
            ['value' => "standard_resolution", 'label' => __('Standard Resolution')]
        ];
        return $result;
    }
}
