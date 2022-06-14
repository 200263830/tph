<?php

/**
 * @category    TPH
 * @package     Tph_Onlinedesign
 * @description Product detail page block
 */

namespace Tph\Onlinedesign\Block\Catalog\Product;


use Magento\Framework\View\Element\Template;
use Tph\Onlinedesign\Helper\Data as HelperData;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Framework\App\Request\Http;
use Magento\Checkout\Model\Cart;
use Magento\Quote\Model\QuoteRepository;
use Magento\Checkout\Model\SessionFactory;
use Magento\Catalog\Model\ProductFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\Session\SessionManager; 
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Serialize\SerializerInterface;
use Imagick;
use Magento\Framework\App\RequestInterface;
/**
 * Class View
 * @package Tph\Onlinedesign\Block\Catalog\Product\View
 */
class View extends Template
{
    /**
     * @var HelperData
     */
    public $helper;
    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;
    /**
     * @var array
     */
    private $data;
    /**
     * @var \Magento\Framework\View\Element\Template\Context
     */
    private $context;

    private $session;

    private $sessionManager;
    
    /**
     * 
     * @var type
     */
    private $serialize;

    /**
     * 
     * @param Context $context
     * @param Session $session
     * @param SessionManager $sessionManager
     * @param Registry $registry
     * @param HelperData $helper
     * @param UrlInterface $urlInterface
     * @param Http $request
     * @param SessionFactory $cart
     * @param QuoteRepository $quoteRepository
     * @param ProductFactory $_productloader
     * @param SerializerInterface $serialize
     * @param array $data
     */
    public function __construct(
        Context $context,
        Session $session,
        SessionManager $sessionManager,
        Registry $registry,
        HelperData $helper,
        UrlInterface $urlInterface,
        Http $request,
        SessionFactory $cart,
        QuoteRepository $quoteRepository,
        ProductFactory $_productloader,
        SerializerInterface $serialize,
        RequestInterface $requestInterface,
        \Magento\Quote\Model\Quote\Item $item,    
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->session = $session;
        $this->_sessionManager = $sessionManager;
        $this->helper = $helper;
        $this->registry = $registry;
        $this->_urlInterface = $urlInterface;
        $this->request = $request;
        $this->requestInterface = $requestInterface;
        $this->cart = $cart;
        $this->quoteRepository = $quoteRepository;
        $this->_productloader = $_productloader;
        $this->item = $item;
        $this->serialize = $serialize;
    }
    
      /**
     * @return mixed
     */
    public function getApiKey()
    {
        $ApiKey = $this->helper->getApiKey();
        return $ApiKey;
    }

    /**
     * @return mixed
     */
    public function getButtonSize()
    {
        $buttonSize = $this->helper->getButtonSize();
        return $buttonSize;
    }

    /**
     * @return mixed
     */
    public function getButtonStyle()
    {
        $buttonStyle = $this->helper->getButtonSize();
        return $buttonStyle;
    }


    /**
     * @return mixed
     */
    public function getParam()
    {
        return  $this->requestInterface ;
    }

    /**
     * @return boolean
     */

    public function copyFile($file)
    {
        return  $this->helper->copyFile($file);
    }

    /**
     * @return boolean
     */

    public function getBaseUrl()
    {
        return  $this->helper->getBaseUrl();
    }

    /**
     * @return boolean
     */

    public function getSessionId()
    {
        //return  $this->_sessionManager->getSessionId();

        if($this->session->isLoggedIn()) {
           $sessionId = $this->session->getCustomer()->getId();
        }else{
            $sessionId = $this->_sessionManager->getSessionId();
        }
        return  $sessionId;
    }


    public function getItemProduct($itemId)
    {
        $productData = $this->item->load($itemId,'parent_item_id');
        
        if(!empty($productData->getProductId())){
            return $productData;
        }else{
            return $this->item->load($itemId);
        }
    }


    public function getItemData($itemId)
    {
        return $this->item->load($itemId);
    }



    /**
     * @return mixed
     */

    public function getCurrentProduct()
    {
        return $this->registry->registry('current_product');
    }

    /**
     * @return mixed
     */
    public function getPost()
    {
        return $this->request->getPost();
    }

    /**
     * @return mixed
     */
    public function getCartQuote()
    {
        return $this->cart;
    }

    /**
     * @return \Magento\Quote\Model\QuoteRepository
     */
    public function quoteDetail(){
        return $this->quoteRepository;
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

    /**
     * 
     *
     * @return $this
     */
    public function getMediaPath()
    {
        return $this->helper->getMediaPath();
    }


    public function setProductImage($canvaImage,$pid,$attributeId,$attributeValue){
        $quote = $this->getCartQuote()->create();
        $canvaImagebutton = $quote->getQuote()->getData('canva_button_image');
        $_SESSION[$pid . "_preview_image"] = "";
        $mediaUrl = $this->getMediaUrl();
        if (!empty($canvaImage[$attributeId]['previewImageSrc'])) {
                $pdf = $canvaImage[$attributeId]['previewImageSrc'];
                $imagick = new Imagick();
                $imagick->setResolution(250, 250);
                $imagick->readImage($pdf);
                $imagick->setImageCompressionQuality(100);
                $mediapath = $this->getMediaPath();
                $filename = rand(1111111111, 9999999999) . '.jpg';
                $pdfPath = $mediapath . '/tph/Images/' . $filename;
                $imagick->writeImages($pdfPath, false);
                $_SESSION[$pid . "_preview_image"] = $mediaUrl . \Tph\Onlinedesign\Helper\Data::IMAGE_FOLDER . $filename;
            
        }

        if (!empty($canvaImagebutton)) {
            $canvaImagebutton = json_decode($canvaImagebutton, true);
            if (!empty($canvaImagebutton[$attributeValue]['exportUrl'])) {
            $image = $canvaImagebutton[$attributeValue]['exportUrl'];
            $filename = strtotime("now") . '.jpg'; 
            $this->helper->copyFile($image, $filename);
            $_SESSION[$pid . "_preview_image"] = $mediaUrl . \Tph\Onlinedesign\Helper\Data::IMAGE_FOLDER . $filename; 
            }  
        }

    }

    /**
     * 
     *
     * @return $this
     */
    public function getMediaUrl()
    {
        return $this->helper->getMediaUrl();
    }

     public function getCustomOptionValue($collection) {
        $categoryId = [];
        if (!empty($collection)) {
            
            foreach ($collection->getOptions() as $o) {
               if (!empty($o->getValues())) {
                    foreach ($o->getValues() as $valuesKey => $valuesVal) {
                       $categoryId['category'][$valuesVal->getOptionTypeId()] = $valuesVal->getCategory();
                       $categoryId['height'][$valuesVal->getOptionTypeId()] = $valuesVal->getHeight();
                       $categoryId['width'][$valuesVal->getOptionTypeId()] = $valuesVal->getWidth();
                    }
                }
            }
        }
        return $this->serialize->serialize($categoryId) ;
    }

}