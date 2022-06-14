<?php

/**
 * @category    TPH
 * @package     Tph_Fileupload
 * @description Observer to Update custom option 
 */

namespace Tph\Fileupload\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Tph\Fileupload\Helper\Data;
use Magento\Quote\Model\Quote\Item as Cartitem;
use \Magento\Checkout\Model\Session as CheckoutSession;

class ProductUpdateCart implements ObserverInterface
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
        $items  = $observer->getEvent()->getData('quote_item');
        $filename = strtotime("now") . '.jpg';
        $item = $observer->getEvent()->getData('quote_item');
        $item->load($item->getId());
        $quote = $this->checkoutSession->getQuote();
        $lastItem = $quote->getLastAddedItem();
        
            
            $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/test.log');
            $logger = new \Zend\Log\Logger();
            $logger->addWriter($writer);
            $logger->info('Your text message 123');
            
                 

               if(!empty($post['uploadcare'])){

                if(!empty($post['uploadcare_type'])){
                   if (strpos($post['uploadcare_type'], ',') !== false) {
                        $upt = explode(",",$post['uploadcare_type']);
                        $filename = $upt[0];
                     }else{
                        if(strpos($post['uploadcare_type'], 'pdf') !== false){
                           $filename = $post['uploadcare_type']; 
                        }
                     }
                }
                   
                   $additionalOptions = [];
                    
                    $additionalOptions[] = array(
                        'label' => "Design", //Custom option label
                        'value' => $filename, //Custom option value
                        'url' => $post['uploadcare'],
                    );                        
                    

                if (count($additionalOptions) > 0) {
                    $item->addOption(array(
                        'product_id' => $item->getProductId(),
                        'code' => 'additional_options',
                        'value' => $this->serializer->serialize($additionalOptions)
                    ));
                }

                
                
                $item->setCustomImage($filename);
                $item->setCanvaType(3);

                 if (strpos($filename, 'pdf') == false) {
                    $this->helper->copyFile($post['uploadcare'],$filename);
                    $item->setCustomImage($filename);
                } else{
                    $item->setCustomImage('pdf_icon.png');
                } 
                //unset($Up);
                }

                if($post['canva_type'] == 1){
                       //     $logger->info('34');
                            $item->setCanvaDesignId($post['canva_designid']);
                            $item->setArtworkId($post['artworkid']);
                            $item->setCanvaType(1);
                            $item->setCustomImage($post['canva_custom_image']);
                }

                if($post['canva_type'] == 2){
                    $item->setDesignId($post['designid']);
                    $item->setCanvaType(2);
                    $item->setCustomImage($post['canva_custom_image']);
                }

       $quote->save();
        
    }
}