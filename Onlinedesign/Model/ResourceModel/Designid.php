<?php
/**
 * @category   Tph
 * @package    Tph_Onlinedesign
 *
 */

namespace Tph\Onlinedesign\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Designid extends AbstractDb
{
	/**
     * @var string
     */
    protected $_idFieldName = 'id';
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;
 
    /**
     * Construct.
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Framework\Stdlib\DateTime\DateTime       $date
     * @param string|null                                       $resourcePrefix
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        $resourcePrefix = null
    ) 
    {
        parent::__construct($context, $resourcePrefix);
        $this->_date = $date;
    }
 

    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('tph_design_id', 'id');   //here "tph_onlinedesign" is table name and "onlinedesign_id" is the primary key of custom table
    }
}