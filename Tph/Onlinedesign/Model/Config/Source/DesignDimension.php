<?php


declare(strict_types=1);

/**
 * @category    TPH
 * @package     Tph_Onlinedesign
 *
 */

namespace Tph\Onlinedesign\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

/**
 * Class DesignDimension
 * @package Tph\Onlinedesign\Model\Config\Source\ButtonStyle
 */
class DesignDimension extends AbstractSource
{

    /**
     * Get all options
     *
     * @return array
     */
    public function getAllOptions()
    {
        if (null === $this->_options) {
            $this->_options=[
                ['label' => __('Pixel'), 'value' => 'px'],
                ['label' => __('Centimeter'), 'value' => 'cm'],
                ['label' => __('Millimetre'), 'value' => 'mm'],
                ['label' => __('Inch'), 'value' => 'in']

            ];
        }
        return $this->_options;
    }
}

