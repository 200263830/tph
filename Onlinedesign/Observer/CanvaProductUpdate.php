<?php

/**
 * @category    TPH
 * @package     Tph_Onlinedesign
 * @description Observer to Update custom option for canva
 */

namespace Tph\Onlinedesign\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Tph\Onlinedesign\Helper\Data;
use Magento\Quote\Model\Quote\Item as Cartitem;
use \Magento\Checkout\Model\Session as CheckoutSession;

class CanvaProductUpdate implements ObserverInterface
{
    /**
     * Below is the method that will fire whenever the event runs!
     *
     * @param Observer $observer
     */

    public function __construct(
        RequestInterface $request,
        Data $helper,
        Cartitem $cartItem,
        CheckoutSession $checkoutSession,
        Json $serializer = null) {
        $this->_request = $request;
        $this->helper = $helper; 
        $this->cartItem = $cartItem;
        $this->checkoutSession = $checkoutSession;
        $this->serializer = $serializer ?: \Magento\Framework\App\ObjectManager::getInstance()
            ->get(\Magento\Framework\Serialize\Serializer\Json::class);
    }

    public function execute(Observer $observer)
    {
        $post = $this->_request->getPost();
        $item = $observer->getEvent()->getData('quote_item');
        $item->load($item->getId());
        $productData = $this->cartItem->load($item->getData('item_id'),'parent_item_id');
        $quote = $this->checkoutSession->getQuote();

            $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/test.log');
            $logger = new \Zend\Log\Logger();
            $logger->addWriter($writer);
            $logger->info('Your text message 123');
            //$logger->info($lastItem->getData('item_id'));
            $logger->info($item->getData('item_id'));
            
            //if($item->getData('item_id') == $lastItem->getData('item_id')){
               
                $logger->info($post['product-canva-image']);
               

                foreach ($quote->getAllItems() as $quoteItem) {
                    $logger->info($quoteItem->getData('item_id'));
                    if($quoteItem->getData('item_id') == $productData->getData('item_id')){
                       if(!empty($post['product-canva-image'])){
                        if($post['canva_type'] == 2){
                            $quoteItem->setDesignId($post['designid']);
                            $quoteItem->setCanvaType(2);
                            $quoteItem->setCustomImage($post['canva_custom_image']);
                        }
                        if($post['canva_type'] == 1){
                            $quoteItem->setCanvaDesignId($post['canva_designid']);
                            $quoteItem->setArtworkId($post['artworkid']);
                            $quoteItem->setCanvaType(1);
                            $quoteItem->setCustomImage($post['canva_custom_image']);
                        }
                       }
                    }
                }

       $quote->save();
        
    }
}