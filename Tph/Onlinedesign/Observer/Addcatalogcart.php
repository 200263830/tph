<?php

/**
 * @category    TPH
 * @package     Tph_Onlinedesign
 * @description Observer after product add to the cart
 */

namespace Tph\Onlinedesign\Observer;

use \Magento\Framework\Event\Observer;
use \Magento\Framework\Event\ObserverInterface;
use Imagick;
use Tph\Onlinedesign\Helper\Data as HelperData;
use Magento\Framework\App\RequestInterface;
use \Magento\Checkout\Model\Session as CheckoutSession;

/**
 * Class Addcatalogcart
 *
 * @package Tph\Onlinedesign\Observer
 */
class Addcatalogcart implements ObserverInterface
{

    protected $_checkoutSession;

    public function __construct (
        \Magento\Checkout\Model\Session $_checkoutSession,
        \Magento\Quote\Api\CartRepositoryInterface $cartRepository,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Magento\Quote\Model\Quote\Item $item,
        \Magento\Checkout\Model\Cart $cart,
        RequestInterface $request,
        HelperData $helper
    ) {
        $this->_checkoutSession = $_checkoutSession;
        $this->cartRepository = $cartRepository;
        $this->_productloader = $_productloader;
        $this->item = $item;
        $this->cart = $cart;
        $this->_request = $request;
        $this->helper = $helper;
    }

    public function execute(Observer $observer)
    {
        $post = $this->_request->getPost();
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/test.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        
        $quoteId = $this->_checkoutSession->getQuote()->getId();
        $canvaImage = $this->_checkoutSession->getQuote()->getData('canva_image');
        $canvaImage = json_decode($canvaImage, true);
        $itemsCollection = $this->cart->getQuote()->getItemsCollection();
        $itemsCollection->getSelect()->order('created_at DESC');
        $latestItem = $itemsCollection->getLastItem();
        $quote = $this->cartRepository->get($quoteId);

        foreach ($quote->getAllItems() as $quoteItem) {
            $pid = $quoteItem->getProductId();
            $pidc = $latestItem->getProductId();
            $parentItem = $latestItem->getParentItemId();
            if (!empty($parentItem)) {
                $productData = $this->item->load($parentItem);
                $product = $this->getLoadProduct($productData->getProductId());
            } else {
                $product = $this->getLoadProduct($pidc);
            }
           

            $attributeId = $product->getData('product_design_id');
            $attributeType = $product->getData('product_design_type');

            

            if (!empty($canvaImage)) {
                if(!empty($attributeId) && array_search($attributeId, array_column($canvaImage, 'productId')) !== false) {
                    //$logger->info('2');
                    if($latestItem->getItemId() == $quoteItem->getItemId()) {
                        //$logger->info('3');
                        if (!empty($post['product-canva-image'])) {
                            //$logger->info('4');
                            $pdf = $canvaImage[$attributeId]['previewImageSrc'];
                            $filename = $this->helper->copyImage($pdf);
                            $quoteItem->setCustomImage($filename);
                            $quoteItem->setCanvaDesignId($canvaImage[$attributeId]['designId']);
                            $quoteItem->setArtworkId($canvaImage[$attributeId]['artworkId']);
                            $quoteItem->setCanvaType(1);

                        }
                    }
                }
            }
        }

            $cbi = $this->_checkoutSession->getQuote()->getData('canva_button_image');
            $cbi = json_decode($cbi, true);

            if (!empty($cbi)) {
                if (isset($cbi[$attributeType]['productId'])) {
                    if ($pid == $cbi[$attributeType]['productId']) {
                        $quoteItem->setDesignType($cbi[$attributeType]['designType']);
                        $quoteItem->setDesignId($cbi[$attributeType]['designId']);
                        $filename = strtotime("now") . '.jpg';
                        $this->helper->copyFile($cbi[$attributeType]['exportUrl'], $filename);
                        $quoteItem->setCustomImage($filename);
                        $quoteItem->setCanvaType(2);
                        
                    }
                }
            }


        //Add Canva attribute for canva catlogue flow
        
        $quote->save();
        //$_SESSION['views'] = 1;
    }

    /**
     * @param $id
     *
     * @return $this
     */
    public function getLoadProduct($id)
    {
        return $this->_productloader->create()->load($id);
    }

}
