<?php

/**
 * @category    TPH
 * @package     Tph_Onlinedesign
 *
 */

namespace Tph\Onlinedesign\Plugin\CheckoutCart;

use Tph\Onlinedesign\Helper\Data as HelperData;
use Magento\Quote\Model\Quote\Item as Cartitem;

/**
 * Class Image
 *
 * @package Tph\Onlinedesign\Plugin\CheckoutCart
 */
class Image
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

    public function afterGetImage($item, $result)
    {
        $itemData = $item->getItem();
        $productData = $this->cartItem->load($itemData->getData('item_id'),'parent_item_id');
        $mediaUrl = $this->helper->getMediaUrl();

        if (!empty($itemData->getCustomImage())) {
            $imageUrl = $mediaUrl . \Tph\Onlinedesign\Helper\Data::IMAGE_FOLDER . $itemData->getCustomImage();
            $result->setImageUrl($imageUrl);
        } else if ($itemData->getProductType() == "configurable") {
            if (!empty($productData->getCustomImage())) {
                $imageUrl =
                    $mediaUrl . \Tph\Onlinedesign\Helper\Data::IMAGE_FOLDER . $productData->getCustomImage();
                $result->setImageUrl($imageUrl);
            }
        }
        
        return $result;
    }

}