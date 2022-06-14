<?php

/**
 * @category    TPH
 * @package     Tph_Onlinedesign
 * add block for the configurable product
 */

namespace Tph\Onlinedesign\Block\Customimage;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\Data\ProductInterfaceFactory;
use Magento\Catalog\Model\Product;
use Magento\Checkout\Block\Cart\Additional\Info as AdditionalBlockInfo;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template as ViewTemplate;
use Magento\Framework\View\Element\Template\Context;
use Magento\Checkout\Model\Cart as CartFactory;
use Magento\Quote\Model\QuoteFactory;
use Tph\Onlinedesign\Helper\Data  as HelperData;

/**
 * Class CartItemBrandBlock
 */
class CartItemImageBlock extends ViewTemplate
{
    /**
     * Product
     *
     * @var ProductInterface|null
     */
    protected $product = null;

    /**
     * Product Factory
     *
     * @var ProductInterfaceFactory
     */
    protected $productFactory;

    /**
     * CartItemBrandBlock constructor
     *
     * @param Context $context
     * @param ProductInterfaceFactory $productFactory
     */
    public function __construct(
        Context $context,
        ProductInterfaceFactory $productFactory,
        CartFactory $cart,
        QuoteFactory $quoteFactory,
        HelperData $helper
    ) {
        parent::__construct($context);
        $this->productFactory = $productFactory;
        $this->cart = $cart;
        $this->quoteFactory = $quoteFactory;
        $this->helper = $helper;
    }

    /**
     * @return int
     * @throws \Magento\Framework\Exception\LocalizedException
     *
     */
    public function getItem()
    {

        $layout = $this->getLayout();
        $block = $layout->getBlock('additional.product.info');

        if ($block instanceof AdditionalBlockInfo) {
            $item = $block->getItem();
        }

        return $item;
    }

    /**
     * @return \Magento\Quote\Model\Quote
     */
    public function getCartDetail()
    {
        /** @var  \Magento\Checkout\Model\Cart $quote  */
        $quote = $this->cart->getQuote();
        // This will return the current quote
        $quoteId = $quote->getId();
        /** @var  \Magento\Quote\Model\Quote $newquote  */
        $newquote = $this->quoteFactory->create()->load($quoteId);
        return $newquote;
    }


    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->helper->getApiKey();
    }


    /**
     * Get Base URLs using URLInterface
     */
    public function getBaseUrl()
    {

        return $this->helper->getBaseUrl();
    }

    /**
     * Get Media URLs using URLInterface
     */
    public function getMediaUrl()
    {

        return $this->helper->getMediaUrl();
    }

    /**
     * Get product from quote item
     *
     * @return ProductInterface
     */
    public function getProduct(): ProductInterface
    {
        if ($this->product instanceof ProductInterface) {

            return $this->product;
        }

        try {
            $layout = $this->getLayout();
        } catch (LocalizedException $e) {
            $this->product = $this->productFactory->create();

            return $this->product;
        }

        /** @var AdditionalBlockInfo $block */
        $block = $layout->getBlock('additional.product.info');

        if ($block instanceof AdditionalBlockInfo) {
            $item = $block->getItem();
            $this->product = $item->getProduct();
        }

        return $this->product;
    }
}