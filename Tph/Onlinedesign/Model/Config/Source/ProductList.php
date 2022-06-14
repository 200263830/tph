<?php


declare(strict_types=1);

/**
 * @category    TPH
 * @package     Tph_Onlinedesign
 * 
 */

namespace Tph\Onlinedesign\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\Visibility;


/**
 * Class ProductList
 * @package Tph\Onlinedesign\Model\Config\Source\ButtonStyle
 */
class ProductList extends AbstractSource
{
    /**
     * @var \Tph\Onlinedesign\Model\ResourceModel\Canvas\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * 
     * @param \Tph\Onlinedesign\Model\ResourceModel\Canvas\CollectionFactory $collectionFactory
     */
    public function __construct(
    
        CollectionFactory $productCollectionFactory,
        Status $productStatus,
        Visibility $productVisibility
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->productStatus = $productStatus;
        $this->productVisibility = $productVisibility;
    }

    /**
     * @return array
     */
    public function getAllOptions()
    {
        //$test = $this->collectionFactory->create()->toOptionArray();
        return $this->getDesignType();
    }

    /**
     * Retrieve design type
     *
     * @return array
     */
    public function getDesignType()
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToFilter('status', ['in' => $this->productStatus->getVisibleStatusIds()])
            ->addAttributeToSelect(
                'name');
        $collection->setVisibility($this->productVisibility->getVisibleInSiteIds());

            $customerById = [];
            if($collection->getSize()) {
                foreach ($collection as $customer) {
                    $customerId = $customer->getEntityId();
                    if (!isset($customerById[$customerId])) {
                        $customerById[$customerId] = [
                            'value' => $customerId
                        ];
                    }
                    $customerById[$customerId]['label'] = $customer->getName();
                }
            }
        return $customerById;
    }
}