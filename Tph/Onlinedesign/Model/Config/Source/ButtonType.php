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
class ButtonType extends AbstractSource
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
                ['label' => __('---Please Select --'), 'value' => ''],
                ['label' => __('Canva Button'), 'value' => 1],
                ['label' => __('Canva Catalogue'), 'value' => 2],
            ];
        }
        return $this->_options;
    }
}

