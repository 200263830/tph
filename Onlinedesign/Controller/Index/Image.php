<?php

/**
 * @category    TPH
 * @package     Tph_Onlinedesign
 *
 */
namespace Tph\Onlinedesign\Controller\Index;

use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Imagick;

/**
 * Class Image
 *
 * @package Tph\Onlinedesign\Controller\Index
 */
class Image extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_pageFactory;

    /**
     * Image constructor.
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Checkout\Model\SessionFactory $checkoutSession
     * @param \Magento\Quote\Api\CartManagementInterface $quoteManagement
     * @param \Magento\Checkout\Model\Cart $cart
     * @param \Magento\Framework\View\Result\PageFactory $pageFactory
     * @param \Tph\Onlinedesign\Helper\Data $helperData
     * @param \Magento\Quote\Model\QuoteRepository $quoteRepository
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Checkout\Model\SessionFactory $checkoutSession,
        \Magento\Quote\Api\CartManagementInterface $quoteManagement,
        \Magento\Checkout\Model\Cart $cart,   
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Tph\Onlinedesign\Helper\Data $helperData,
        \Magento\Quote\Model\QuoteRepository $quoteRepository,
        Collection $collection,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Tph\Onlinedesign\Model\DesignidFactory $itemsFactory,
         \Magento\Catalog\Model\ProductFactory $_productloader
        )
    {
        $this->_checkoutSession = $checkoutSession;
        $this->quoteManagement = $quoteManagement;
        $this->_pageFactory = $pageFactory;
        $this->cart = $cart;
        $this->_helperData = $helperData;
        $this->quoteRepository = $quoteRepository;
        $this->collection = $collection;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_itemsFactory = $itemsFactory;
        $this->_productloader = $_productloader;
        return parent::__construct($context);
    }

    /**
     * @return $this
     */
    public function execute() {

        $quote = $this->cart->getQuote();
        $quoteId = $quote->getId();
        if (empty($quoteId)) {
            $cartObject = $this->cart->truncate();
            if ($this->_helperData->getLoggedIn()) {
                $customerId = $this->_helperData->getCustomerData()->getId();
                $this->quoteManagement->createEmptyCartForCustomer($customerId);
            }
            $cartObject->saveQuote();
        }

        $post = $this->getRequest()->getPostValue();
        $quote = $this->cart->getQuote();

        // This will return the current quote
        $quoteId = $quote->getId();
        $quote = $this->quoteRepository->get($quoteId);

        $canvaImage = $quote->getData('canva_image');
        $canvaImage = json_decode($canvaImage, true);
        $product_id = $post['productData']['productId'];
        $productData = [];
        if (!empty($canvaImage)) {
            if (array_search($product_id, array_column($canvaImage, 'productId')) !== false) {
                foreach ($canvaImage as $k => $v) {
                    if ($k == $product_id) {
                        $canvaImage[$product_id] = $post['productData'];
                    } else {
                        $canvaImage[$k] = $v;
                    }
                }
                $productData = $canvaImage;
            } else {
                $newarray[$product_id] = $post['productData'];
                $productData = array_merge($newarray, $canvaImage);
            }
        } else {
            $productData[$product_id] = $post['productData'];
        }

        $quote->setData('canva_image', json_encode($productData)); // Fill 


        $this->quoteRepository->save($quote);

        if($post['producdetail'] != 1){
          $sizeoption = $post['productData']['productSizeOptionId']; 
         //$collection = $this->getProductCollection($product_id);
         $collection = $this->getProductCollection($sizeoption);  
         if($collection->count() == 0){
         
           //$collection = $this->getProductCollection($sizeoption); 
           $collection = $this->getProductCollection($product_id);  
         }
        }else{
            $collection = $this->getProductCollection($product_id); 
        }
        
         

        

        $pdf = $post['productData']['previewImageSrc'];
        $mediaUrl = $this->_helperData->getMediaUrl();

        foreach ($quote->getAllItems() as $quoteItem) {
            if (isset($post['productData']['did'])) {
                $pid = $post['productData']['pid'];
                if ($quoteItem->getCanvaDesignId() == $post['productData']['did']) {
                    $filename = $this->_helperData->copyImage($pdf);
                    $quoteItem->setCustomImage($filename);
                    $quoteItem->setCanvaDesignId($post['productData']['designId']);
                    $response['product_id'] = $pid;
                    $response['media_url'] = $mediaUrl . \Tph\Onlinedesign\Helper\Data::IMAGE_FOLDER . $filename;
                    $quote->save();
                }
            }
        }

        $baseUrl = $this->_helperData->getBaseUrl();

        if (!empty($pdf)) {
            $filename = $this->_helperData->copyImage($pdf);
            $imgUrl = $mediaUrl . \Tph\Onlinedesign\Helper\Data::IMAGE_FOLDER . $filename;
            $response['media_url'] = $imgUrl;
        }

        if ($collection->count() > 0) {
            $data = $collection->getFirstItem();
            $product = $this->_productloader->create()->load($data->getProductId());
            $productUrl = $product->getProductUrl();
            if (!empty($data->getProductId())){
                $_SESSION[$data->getProductId() . "_preview_image"] = $imgUrl;
                if($post['edit']==0){
                $_SESSION['views'] = 0;
                $_SESSION['canva_page'] = 1;
                $cand = $post['productData'];
                $cand['filename'] = $filename;
                $_SESSION['canva_detail'] = $cand;
                }
            }
        }

        // if (!empty($post['productData']['pids'])) {
        //         $_SESSION[$post['productData']['pids'] . "_preview_image"] = $imgUrl;
        //         $_SESSION['views'] = 0;
        // }
        
         $redirect = ($collection->count() > 0) ? $productUrl : $baseUrl . 'catalogsearch/result/?q=' . $product_id;
        ;
        $cart_page = $baseUrl . 'checkout/cart/';
        $response['redirectUrl'] = ($post['edit']) ? $cart_page : $redirect;
        $response['canva_detail'] = $post['productData'];
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($response);
    }

    /**
     * @param $designId
     *
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getProductCollection($designId){

        $collection = $this->_itemsFactory->create()->getCollection()
        ->addFieldToFilter('design_id', ['like'=>'%' . $designId. '%' ])
        ->load();

        $collection->getSelect()->__toString();
        return $collection; 
    }

    /**
     * @return \Magento\Quote\Model\Quote
     */
    public function getCheckoutSession()
    {
         return $this->_checkoutSession->create()->getQuote();
    }
}