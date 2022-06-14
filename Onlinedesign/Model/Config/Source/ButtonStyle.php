<?php

/**
 * @category    TPH
 * @package     Tph_Onlinedesign
 * 
 */

namespace Tph\Onlinedesign\Model\Config\Source;

/**
 * Class Data
 * @package Tph\Onlinedesign\Model\Config\Source\ButtonStyle
 */

class ButtonStyle implements \Magento\Framework\Data\OptionSourceInterface {

    /**
     * Admin config  button style option data
     * 
     * @return array
     */
    public function toOptionArray() {
        return [
            ['value' => 'default', 'label' => __('Default')],
            ['value' => 'dark', 'label' => __('Dark')],
            ['value' => 'light', 'label' => __('Light')]
        ];
    }

}
