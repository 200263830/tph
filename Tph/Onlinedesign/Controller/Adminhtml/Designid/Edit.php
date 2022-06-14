<?php
/**
 * @category   Tph
 * @package    Tph_Onlinedesign
 * 
 */

namespace Tph\Onlinedesign\Controller\Adminhtml\Designid;

class Edit extends \Tph\Onlinedesign\Controller\Adminhtml\Items
{

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');

        $model = $this->_objectManager->create('Tph\Onlinedesign\Model\Designid');

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
        $this->_coreRegistry->register('current_tph_onlinedesign_designid', $model);
        $this->_initAction();
        $this->_view->getLayout()->getBlock('designid_items_edit');
        $this->_view->renderLayout();
    }
}
