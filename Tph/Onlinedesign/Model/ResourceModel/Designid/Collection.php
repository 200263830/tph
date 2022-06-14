<?php
/**
 * @category   Tph
 * @package    Tph_Onlinedesign
 *
 */

namespace Tph\Onlinedesign\Model\ResourceModel\Designid;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
 
class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';
    /**
     * Define model & resource model
     */

    
    protected function _construct()
    {
        $this->_init(
            'Tph\Onlinedesign\Model\Designid',
            'Tph\Onlinedesign\Model\ResourceModel\Designid'
        );
    }

}