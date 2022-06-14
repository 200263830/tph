<?php

/**
 * @category    TPH
 * @package     Tph_Onlinedesign
 * 
 */

namespace Tph\Onlinedesign\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Class Canvas
 * @package Tph\Onlinedesign\Model\Canvas
 */
class Canvas extends AbstractModel
{
    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init('Tph\Onlinedesign\Model\ResourceModel\Canvas');
    }
}