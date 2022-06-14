<?php

/**
 * @category    TPH
 * @package     Tph_Onlinedesign
 * 
 */

namespace  Tph\Onlinedesign\Model\ResourceModel;

/**
 * Class Canvas
 * @package Tph\Onlinedesign\Model\ResourceModel\Canvas
 */
class Canvas extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('tph_design_type', 'id');   //here "tph_design_type" is table name and "id" is the primary key of custom table
    }
}