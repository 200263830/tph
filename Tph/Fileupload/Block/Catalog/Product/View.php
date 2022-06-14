<?php

/**
 * @category    TPH
 * @package     Tph_Fileupload
 * @description Block For the detail page
 */


namespace Tph\Fileupload\Block\Catalog\Product;


use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Quote\Model\Quote\Item as Cartitem;
use Magento\Checkout\Model\Cart;

/**
 * Class View
 * @package Tph\Onlinedesign\Block\Catalog\Product\View
 */
class View extends Template
{
    
    private $context;


    /**
     * 
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Context $context,
        RequestInterface $requestInterface,
        Cartitem $cartItem,
        Cart $cart,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->requestInterface = $requestInterface;
        $this->cartItem = $cartItem;
        $this->cart = $cart;
    }
    
    /**
     * @return mixed
     */
    public function getParam()
    {
        return  $this->requestInterface ;
    }

    /**
     * @return mixed
     */
    public function getCartItem($itemId)
    {
        return  $this->cartItem->load($itemId,'parent_item_id');;
    }

    public function getAddtionaOption($id){
        $addition = '';
         $items = $this->cart->getQuote()->getAllItems();
           foreach ($items as $item) {
             $customOptions = $item->getOptionByCode('additional_options');
               if(!empty($customOptions)){
               $row = $customOptions->getData();
               if($row['item_id'] == $id){
                    $addition = $row['value'];
                }
            }
          }
        return  $addition;
    }

}
