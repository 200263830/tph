<?php

namespace Tph\Onlinedesign\Plugin\CheckoutCart;

use Tph\Onlinedesign\Helper\Data as HelperData;
use Magento\Quote\Model\Quote\Item as Cartitem;

class MinicartImage
{


    /**
     * Image constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Tph\Onlinedesign\Helper\Data $helper
     */
    public function __construct(
        HelperData $helper,
        Cartitem $cartItem
    ) {
        $this->helper = $helper;
        $this->cartItem = $cartItem;
    }


    public function aroundGetItemData($subject, $proceed, $item)
    {

        $result = $proceed($item);

        $itemData = $item;
        $productData = $this->cartItem->load($itemData->getData('item_id'),'parent_item_id');
        $mediaUrl = $this->helper->getMediaUrl();

        try {

            if (!empty($itemData->getCustomImage())) {
                $imageUrl = $mediaUrl . \Tph\Onlinedesign\Helper\Data::IMAGE_FOLDER . $itemData->getCustomImage();
                $result['product_image']['src'] = $imageUrl;
            } else if ($itemData->getProductType() == "configurable") {
                if (!empty($productData->getCustomImage())) {
                    $imageUrl =
                        $mediaUrl . \Tph\Onlinedesign\Helper\Data::IMAGE_FOLDER . $productData->getCustomImage();
                    $result['product_image']['src'] = $imageUrl;
                }
            }
        } //catch exception
        catch (Exception $e) {
            $result['product_image']['src'];
        }

        return $result;
    }
}