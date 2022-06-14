<?php

/**
 * @category    TPH
 * @package     Tph_Onlinedesign
 * 
 */
namespace Tph\Onlinedesign\Model\Config\Source;

/**
 * Class Data
 * @package Tph\Onlinedesign\Model\Config\Source\ButtonSize
 */
class ButtonSize implements \Magento\Framework\Data\OptionSourceInterface {

    /**
     * Admin config  button size option data
     * 
     * @return array
     */
    public function toOptionArray() {
        return [
            ['value' => 'default', 'label' => __('Default')],
            ['value' => 'large', 'label' => __('Large')],
            ['value' => 'small', 'label' => __('Small')],
            ['value' => 'tiny', 'label' => __('Tiny')]
        ];
    }

}
