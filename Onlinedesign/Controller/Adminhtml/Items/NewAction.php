<?php

/**
 * @category    TPH
 * @package     Tph_Onlinedesign
 * 
 */

namespace Tph\Onlinedesign\Controller\Adminhtml\Items;

class NewAction extends \Tph\Onlinedesign\Controller\Adminhtml\Items
{
    /**
     * 
     * @return string
     */
    public function execute()
    {
    	$resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Canvas Design Type'));
    	$this->_forward('edit');
    	return $resultPage;
    }
}
