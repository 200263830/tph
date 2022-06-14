<?php
/**
 * @category   Tph
 * @package    Tph_Onlinedesign
 * 
 */

namespace Tph\Onlinedesign\Controller\Adminhtml\Designid;


class MassDelete extends \Magento\Backend\App\Action {

    protected $_filter;

    protected $_collectionFactory;

    public function __construct(
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Tph\Onlinedesign\Model\ResourceModel\Designid\CollectionFactory $collectionFactory,
        \Magento\Backend\App\Action\Context $context
        ) {
        $this->_filter            = $filter;
        $this->_collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    public function execute() {
        try{ 

            $logCollection = $this->_filter->getCollection($this->_collectionFactory->create());
            

            $recordDeleted=0;
        foreach ($logCollection as $item) {
            $deleteItem = $this->_objectManager->get('Tph\Onlinedesign\Model\Designid')->load($item->getId());
            $deleteItem->delete();
            $recordDeleted++;
        }

           $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $recordDeleted));
        }catch(Exception $e){
            $this->messageManager->addError($e->getMessage());
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('tph_onlinedesign/designid/index'); //Redirect Path
    }

    
}