<?php

/**
 * @category    TPH
 * @package     Tph_Onlinedesign
 *
 */
namespace Tph\Onlinedesign\Controller\Index;

use Tph\Onlinedesign\Helper\Data as HelperData;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;

/**
 * Class Index
 *
 * @package Tph\Onlinedesign\Controller\Index
 */
class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    /**
     * Index constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Tph\Onlinedesign\Helper\Data $helper
     * @param \Magento\Framework\Data\Form\FormKey $formKey
     * @param \Magento\Checkout\Model\Cart $cart
     * @param \Magento\Catalog\Model\Product $product
     * @param \Magento\Quote\Api\CartRepositoryInterface $cartRepository
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        HelperData $helper,
        \Magento\Framework\Data\Form\FormKey $formKey,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Catalog\Model\Product $product,
        \Magento\Quote\Api\CartManagementInterface $quoteManagement,
        \Magento\Quote\Api\CartRepositoryInterface $cartRepository,
        JsonFactory $resultJsonFactory,
        ProductRepositoryInterface $productRepository
    ) {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
        $this->helper = $helper;
        $this->formKey = $formKey;
        $this->cart = $cart;
        $this->product = $product;
        $this->quoteManagement = $quoteManagement;
        $this->cartRepository = $cartRepository;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->productRepository = $productRepository;
    }

    /**
     * Grid List page.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        
        $post = $this->getRequest()->getPostValue();
        $productId = $post['productId'];
        $productData = [];
        if ($post['edit'] == 0) {
            $quote = $this->cart->getQuote();
            $quoteId = $quote->getId();

            $cid = $quote->getData('canva_button_image');
            $cid = json_decode($cid,true);
            if(empty($quoteId)){
                $cartObject = $this->cart->truncate();
                if($this->helper->getLoggedIn()){
                    $customerId = $this->_helperData->getCustomerData()->getId();
                    $this->quoteManagement->createEmptyCartForCustomer($customerId);
                }
                $cartObject->saveQuote();
            }

            if(!empty($cid)){
                if(array_search($post['designType'], array_column($cid, 'designType')) !== false){
                    foreach($cid as $k=>$v){
                        if($k == $post['designType']){
                            $cid[$post['designType']] = $post;

                        }else{
                            $cid[$k] = $v;
                        }
                    }
                    $productData = $cid;
                }else{
                    $newarray[$post['designType']] = $post;
                    $productData = array_merge($newarray,$cid);
                }
            }else{
                $productData[$post['designType']] = $post;
            }

            $filename = strtotime("now") . '.jpg';
            $this->helper->copyFile($post['exportUrl'], $filename);
            $productData[$post['designType']]['display_image'] =  $filename;    
            $quote = $this->cart->getQuote();
            $quote->setData('canva_button_image', json_encode($productData)); // Fill
            $this->cartRepository->save($quote);

         }

        $quoteId = $this->cart->getQuote()->getId();

        $quote = $this->cartRepository->get($quoteId);

        // Edit 2 for the update the canva button image
        if ($post['edit'] == 1 || $post['edit'] == 2) {
            foreach ($quote->getAllItems() as $quoteItem) {
                $filename = strtotime("now") . '.jpg';
                $this->helper->copyFile($post['exportUrl'], $filename);
                if ($productId == $quoteItem->getProductId()) {
                    $quoteItem->setCustomImage($filename);
                    //$quoteItem->setDesignId($post['designId']);

                }
            }
        }


        $quote->save();
        $mediaUrl = $this->helper->getMediaUrl();
       
        if($post['edit'] == 0){
            if($post['productType'] = "configurable"){
                $imgUrl = $mediaUrl . \Tph\Onlinedesign\Helper\Data::IMAGE_FOLDER . $filename;
                if(!empty($post['paretpid'])){
                $_SESSION[$post['paretpid']. "_preview_image"] = $imgUrl;
                }
                else{
                  $_SESSION[$productId. "_preview_image"] = $imgUrl;  
                }
            }else{
                $_SESSION[$productId. "_preview_image"] = $imgUrl;
            }
        }
        $response['product_id'] = $productId;
        $response['redirectUrl'] = $this->_url->getUrl('checkout/cart/');
        $response['media_url'] = $mediaUrl . \Tph\Onlinedesign\Helper\Data::IMAGE_FOLDER . $filename;
        $response['designId'] = $post['designId']; 
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($response);
    }

    /**
     * @param $productId
     *
     * @return bool
     */
    public function _initProduct($productId)
    {
        //$productId = (int)$this->getRequest()->getParam('product');
        if ($productId) {
            $storeId = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore()->getId();
            try {
                return $this->productRepository->getById($productId, false, $storeId);
            } catch (NoSuchEntityException $e) {
                return false;
            }
        }
        return false;
    }
}
