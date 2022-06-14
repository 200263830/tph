<?php

/**
 * @category    TPH
 * @package     Tph_Onlinedesign
 *
 */
namespace Tph\Onlinedesign\Block;

use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Session\SessionManager;

/**
 * Class Canvaspage
 *
 * @package Tph\Onlinedesign\Block
 */
class Canvapage extends \Magento\Framework\View\Element\Template
{
    /**
     * MODULE_PATH
     */
    const MODULE_PATH = '/app/code/Tph/Onlinedesign';

    const CANVA_LIBRARY = '/lib/internal/Canva/';

    /**
     * Canvapage constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Filesystem\DirectoryList $dir
     * @param \Tph\Onlinedesign\Helper\Data $helperData
     * @param \Magento\Checkout\Model\SessionFactory $checkoutSession
     * @param \Magento\Quote\Api\CartManagementInterface $quoteManagement
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @param \Magento\Framework\Session\SessionManager $sessionManager
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Filesystem\DirectoryList $dir,
        \Tph\Onlinedesign\Helper\Data $helperData,
        \Magento\Checkout\Model\SessionFactory $checkoutSession,
        \Magento\Quote\Api\CartManagementInterface $quoteManagement,
        \Magento\Framework\Session\SessionManager $sessionManager,
        \Magento\Customer\Model\Session $customerSession,
        Collection $collection,
        ProductFactory $_productloader
    ) {
        $this->_storeManager = $storeManager;
        $this->_dir = $dir;
        $this->_helperData = $helperData;
        $this->_checkoutSession = $checkoutSession;
        $this->_sessionManager = $sessionManager;
        $this->_customerSession = $customerSession;
        $this->collection = $collection;
        $this->quoteManagement = $quoteManagement;
        $this->_productloader = $_productloader;
        parent::__construct($context);
    }

    /**
     * Prepare layout before rendering HTML
     *
     * @return string
     */
    public function _prepareLayout()
    {
        $this->pageConfig->getTitle()->set(__('Canva catalogue'));
        return parent::_prepareLayout();
    }

    public function getSessionId()
    {
        if($this->_customerSession->isLoggedIn()) {
           $sessionId = $this->_customerSession->getCustomer()->getId();
        }else{
            $sessionId = $this->_sessionManager->getSessionId();
        }
        return  $sessionId;
    }

    /**
     * Add the canva library
     */
    public function getAutoAuthToken()
    {
        require_once($this->_dir->getPath('lib_internal') . '/Canva/vendor/autoload.php');
        require_once($this->_dir->getPath('lib_internal') . '/Canva/' . 'CanvaInit.php');
    }

    /**
     *
     * @return type
     */
    public function getBaseUrl()
    {
        return $this->_helperData->getBaseUrl();
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->_helperData->getApiKey();
    }

    /**
     * @return \Magento\Framework\DataObject
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getProductCollection()
    {
        $collection = $this->collection->addAttributeToSelect('*')->addAttributeToFilter(
            'product_design_type',
            ['eq' => 'BusinessCard']
        )->load()->getFirstItem();

        return $collection;
    }

    /**
     * Checkout Quote Data
     */
    public function getCheckoutSession()
    {
        return $this->_checkoutSession->create()->getQuote();
    }

    /**
     * @return bool
     */
    public function getLoggedIn()
    {
        return $this->_helperData->getLoggedIn();
    }

    /**
     * @return int
     */
    public function createEmptyCart()
    {
        if ($this->_helperData->getLoggedIn()) {
            $customerId = $this->_helperData->getCustomerData()->getId();
            $this->quoteManagement->createEmptyCartForCustomer($customerId);
        }

        return $this->quoteManagement->createEmptyCart();
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