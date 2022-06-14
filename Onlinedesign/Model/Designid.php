<?php
/**
 * @category   Tph
 * @package    Tph_Onlinedesign
 * 
 */

namespace Tph\Onlinedesign\Model;

use Magento\Framework\Model\AbstractModel;

class Designid extends AbstractModel
{
    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init('Tph\Onlinedesign\Model\ResourceModel\Designid');
    }
}