<?php

/**
 * @category    TPH
 * @package     Tph_Onlinedesign
 * @Description Add the field for the print production file
 */


namespace Tph\Onlinedesign\Plugin;

/**
 * Class OrderManagement
 */ 
class OrderItem {

    public function aroundConvert(
            \Magento\Quote\Model\Quote\Item\ToOrderItem $subject,
            \Closure $proceed,
            \Magento\Quote\Model\Quote\Item\AbstractItem $item,
            $additional = []
    ) {
        /** @var $orderItem Item */
        $orderItem = $proceed($item, $additional);
        // if($item->getCanvaType()==3){
        //     $orderItem->setCustomImage($item->getCustomImage());
        // }

        /** @var $orderItem Item */
        //$orderItem = $proceed($item, $additional);
        if($item->getCanvaType()==3){
            $orderItem->setDesignType($item->getCanvaType());
            $options = $item->getProduct()->getTypeInstance(true)->getOrderOptions($item->getProduct());
            $uploadcare = $options['info_buyRequest']['uploadcare'];
            if($item->getCustomImage()!= 'pdf_icon.png' ){
               $orderItem->setCustomImage($uploadcare); 
               // $orderItem->setCustomImage($item->getCustomImage());
            }else{
                $orderItem->setCustomImage($uploadcare); 
            }
        }

        if($item->getCanvaType()==2){
            $orderItem->setCustomImage($item->getCustomImage());
        }
        $orderItem->setArtworkId($item->getArtworkId());
        return $orderItem;
    }

}
?>