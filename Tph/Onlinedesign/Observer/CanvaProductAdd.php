<?php

/**
 * @category    TPH
 * @package     Tph_Onlinedesign
 * @description Observer to add canva image in cart 
 */

namespace Tph\Onlinedesign\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Tph\Onlinedesign\Helper\Data;


class CanvaProductAdd implements ObserverInterface {

    protected $_request;

    public function __construct(
        RequestInterface $request,
        Data $helper,
        Json $serializer = null) {
        $this->_request = $request;
        $this->helper = $helper; 
        $this->serializer = $serializer ?: \Magento\Framework\App\ObjectManager::getInstance()
            ->get(\Magento\Framework\Serialize\Serializer\Json::class);
    }

    public function execute(EventObserver $observer) {

        $post = $this->_request->getPost();
        if(!empty($post['product-canva-image'])){
        //$Up = explode(",",$post['uploadcare']);
        $item = $observer->getQuoteItem();
        $post = $this->_request->getPost();
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/test.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info('-- Canva page product added page --');
        
        $additionalOptions = [];
            $additionalOptions[] = array(
                'label' => "canva image", //Canva option label
                'value' => 'Image Uploaded', //Canva option value
            );

            if (count($additionalOptions) > 0) {
                //$logger->info('3');
                $item->addOption(array(
                    'product_id' => $item->getProductId(),
                     'code'  => 'additional_options',
                     'value' => $this->serializer->serialize($additionalOptions)
                ));
            }
        }
    }
}