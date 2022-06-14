<?php

/**
 * @category    TPH
 * @package     Tph_Onlinedesign
 *
 */

namespace Tph\Onlinedesign\Plugin\Adminhtml;

use Tph\Onlinedesign\Helper\Data as HelperData;
use Magento\Quote\Model\ResourceModel\Quote\Item\CollectionFactory;

/**
 * Class AddRenderer
 *
 * @package Tph\Onlinedesign\Plugin\Adminhtml
 */
class AddRenderer
{
    /**
     * @var \Magento\Framework\Registry|null
     */
    protected $_coreRegistry = null;

    /**
     * AddRenderer constructor.
     *
     * @param \Magento\Framework\Registry $registry
     * @param \Tph\Onlinedesign\Helper\Data $helper
     * @param \Magento\Quote\Model\ResourceModel\Quote\Item\CollectionFactory $orderItemRepository
     */
    public function __construct(
        \Magento\Framework\Registry $registry,
        HelperData $helper,
        CollectionFactory $orderItemRepository
    ) {
        $this->_coreRegistry = $registry;
        $this->helper = $helper;
        $this->orderItemRepository = $orderItemRepository;
    }

    /**
     * @param $defaultRenderer
     * @param $result
     *
     * @return mixed
     */
    public function afterGetColumns($defaultRenderer, $result)
    {
        if (is_array($result)) {
            $newResult['image'] = 'col-image';
            foreach ($result as $key => $value) {
                $newResult[$key] = $value;
            }
            $result = $newResult;
        }
        return $result;
    }

    /**
     * @param $defaultRenderer
     * @param \Magento\Framework\DataObject $item
     * @param $column
     * @param null $field
     *
     * @return array
     */
    public function beforeGetColumnHtml($defaultRenderer, \Magento\Framework\DataObject $item, $column, $field = null)
    {
        $html = '';

        switch ($column) {
            case 'image':
                $this->_coreRegistry->register('is_image_renderer', 1);
                $this->_coreRegistry->register('tph_current_order_item', $item);
                break;
        }
        return [$item, $column, $field];
    }

    /**
     * @param $defaultRenderer
     * @param $result
     *
     * @return string
     */
    public function afterGetColumnHtml($defaultRenderer, $result)
    {
        $is_image = $this->_coreRegistry->registry('is_image_renderer');
        $item = $this->_coreRegistry->registry('tph_current_order_item');
        $this->_coreRegistry->unregister('is_image_renderer');
        $this->_coreRegistry->unregister('tph_current_order_item');

        if (!empty($item)) {
            return $this->renderImage($item);
        }
        return $result;
    }

    /**
     * @param $product
     *
     * @return string
     */
    protected function renderImage($product)
    {

        $item_id = $product->getData('quote_item_id');
        $quoteItemCollection = $this->orderItemRepository->create();
        $quoteItem = $quoteItemCollection->addFieldToSelect('*')->addFieldToFilter('item_id', $item_id)->getFirstItem();
        $quoteId = $quoteItem->getQuoteId();
        $quoteData = $this->orderItemRepository->create();
        $image = $quoteData->addFieldToSelect('*')->addFieldToFilter('quote_id', $quoteId)->getColumnValues('custom_image');
        $image = implode(',',array_filter($image));

//        $image = $quoteItem->getData('custom_image');
        if(!empty($image)) {
            $mediaUrl = $this->helper->getMediaUrl();
            $imageUrl = $mediaUrl . \Tph\Onlinedesign\Helper\Data::IMAGE_FOLDER . $image;
            return "<img src=" . $imageUrl . " alt=" . $product->getName() . " width='150' >";
        }
        return 'No-Image';
    }
}
