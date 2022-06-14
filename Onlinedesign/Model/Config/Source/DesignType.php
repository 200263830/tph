<?php


declare(strict_types=1);

/**
 * @category    TPH
 * @package     Tph_Onlinedesign
 * 
 */

namespace Tph\Onlinedesign\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

/**
 * Class DesignType
 * @package Tph\Onlinedesign\Model\Config\Source\ButtonStyle
 */
class DesignType extends AbstractSource
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
        \Tph\Onlinedesign\Model\ResourceModel\Canvas\CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return array
     */
    public function getAllOptions()
    {
        $test = $this->collectionFactory->create()->toOptionArray();
        return $this->getDesignType();
    }

    /**
     * Retrieve design type
     *
     * @return array
     */
    public function getDesignType()
    {
        $collection = $this->collectionFactory->create();
            $collection->addFieldToFilter('status', ['eq' =>1]);
            $customerById = [];
            $addselect = array (
                        'value' => '',
                        'label' => '--Select Option -- ',
                      );
            $customerById[''] = $addselect;
            if($collection->getSize()) {
                foreach ($collection as $customer) {
                    $customerId = $customer->getDesignType();
                    if (!isset($customerById[$customerId])) {
                        $customerById[$customerId] = [
                            'value' => $customerId
                        ];
                    }

                    $customerById[$customerId]['label'] = $customer->getDesignTitle();
                }
            }
            
        return $customerById;
    }
}