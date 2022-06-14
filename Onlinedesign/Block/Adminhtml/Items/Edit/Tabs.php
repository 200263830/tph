<?php

/**
 * @category    TPH
 * @package     Tph_Onlinedesign
 * 
 */

namespace Tph\Onlinedesign\Block\Adminhtml\Items\Edit;

/**
 * Class Tabs
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('tph_onlinedesign_items_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Item'));
    }
}
