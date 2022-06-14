<?php
/**
 * @category   Tph
 * @package    Tph_Onlinedesign
 * 
 */


namespace Tph\Onlinedesign\Controller\Adminhtml\Designid;

use Tph\Onlinedesign\Controller\Adminhtml\Items;

class NewAction extends \Tph\Onlinedesign\Controller\Adminhtml\Items
{

    public function execute()
    {
        $this->_forward('edit');
    }
}
