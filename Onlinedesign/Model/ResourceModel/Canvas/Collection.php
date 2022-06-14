<?php

/**
 * @category    TPH
 * @package     Tph_Onlinedesign
 * 
 */

namespace Tph\Onlinedesign\Model\ResourceModel\Canvas;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
 
/**
 * Class Data
 * @package Tph\Onlinedesign\Model\ResourceModel\Canvas\Collection
 */

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'Tph\Onlinedesign\Model\Canvas',
            'Tph\Onlinedesign\Model\ResourceModel\Canvas'
        );
    }

    public function toOptionArray()
    {
        return $this->_toOptionArray('design_type', 'design_title');
    }
    
    
}