<?php

/**
 * @category    TPH
 * @package     Tph_Fileupload
 * @description Observer to add image in cart 
 */

namespace Tph\Fileupload\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Tph\Fileupload\Helper\Data;


class CartProductAdd implements ObserverInterface {

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
        
        $item = $observer->getQuoteItem();

        $filename = strtotime("now") . '.jpg';
        if(!empty($post['uploadcare_type'])){
            $upt = explode(",",$post['uploadcare_type']);
            if($upt[1] == "application/pdf"){
                $filename = $upt[0];
            }    
        }
        
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/mylog.log');
            $logger = new \Zend\Log\Logger();
            $logger->addWriter($writer);

            
            

        $additionalOptions = [];

        
        if(!empty($post['uploadcare'])){
            //$logger->info($Up);
            $additionalOptions = [];
            
            $additionalOptions[] = array(
                'label' => "Design", //Custom option label
                'value' => $filename,//Custom option value,
                'url' => $post['uploadcare']
            );                        
            

        if (count($additionalOptions) > 0) {
            $item->addOption(array(
                'product_id' => $item->getProductId(),
                'code' => 'additional_options',
                'value' => $this->serializer->serialize($additionalOptions)
            ));
        }

        
        $logger->info($post['uploadcare']);
        $this->helper->copyFile($post['uploadcare'],$filename);
        if (strpos($filename, 'pdf') == false) {
            $item->setCustomImage($filename);
        } else{
            $item->setCustomImage('pdf_icon.png');
        }   
        $item->setCanvaType(3);
        //unset($Up);
        }

    }
}