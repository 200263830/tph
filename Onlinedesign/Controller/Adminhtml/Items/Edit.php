<?php

/**
 * @category    TPH
 * @package     Tph_Onlinedesign
 * 
 */
namespace Tph\Onlinedesign\Controller\Adminhtml\Items;

/**
 * Class Edit
 */
class Edit extends \Tph\Onlinedesign\Controller\Adminhtml\Items
{
    /**
     * Edit the item
     * 
     * @return mixed
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        
        $model = $this->_objectManager->create('Tph\Onlinedesign\Model\Canvas');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This item no longer exists.'));
                $this->_redirect('tph_onlinedesign/*');
                return;
            }
        }
        // set entered data if was error when we do save
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);
        }
        $this->_coreRegistry->register('current_tph_onlinedesign_items', $model);
        $this->_initAction();
        $this->_view->getLayout()->getBlock('items_items_edit');
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Canvas Design List'));
        $this->_view->renderLayout();
    }
}
