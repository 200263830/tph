<?php
/**
 * @category   Tph
 * @package    Tph_Onlinedesign
 * 
 */

namespace Tph\Onlinedesign\Controller\Adminhtml\Designid;

class Index extends \Tph\Onlinedesign\Controller\Adminhtml\Items
{
    /**
     * Items list.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Tph_Core::core');
        $resultPage->getConfig()->getTitle()->prepend(__('Canva Landing Page'));
        $resultPage->addBreadcrumb(__('Canva Product'), __('Canva Product'));
        $resultPage->addBreadcrumb(__('Items'), __('Items'));
        return $resultPage;
    }
}